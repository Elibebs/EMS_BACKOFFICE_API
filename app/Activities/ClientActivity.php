<?php

namespace App\Activities;

use App\Repos\ClientRepo;
use App\Repos\LocationRepo;
use App\Api\ApiResponse;
use App\Traits\ClientTrait;
use App\Utilities\Validator;
use App\Events\ErrorEvents;
use App\Tenant;
use Illuminate\Support\Facades\Log;
use App\Activities\ClientActivity;
use App\Utilities\Generators;
use Hyn\Tenancy\Environment;
use Hyn\Tenancy\Models\Website;
use Illuminate\Support\Facades\DB;

class ClientActivity
{
    use ClientTrait;

    protected $clientRepo;
    protected $apiResponse;
    protected $locationRepo;

    public function __construct(ClientRepo $clientRepo, ApiResponse $apiResponse, LocationRepo $locationRepo)
    {
        $this->clientRepo = $clientRepo;
        $this->apiResponse = $apiResponse;
        $this->locationRepo = $locationRepo;
    }

    public function getClientByUid($uid)
    {
        return $this->clientRepo->getClientByUid($uid);
    }

    /**
     * Get socket clients
     */
    public function getSocketClients()
    {
        //Get All Clients
        $clients = $this->clientRepo->getAllClients();
        foreach($clients as $client){
            $this->setUpTenantEnvironment($client->short_name);
            $client['locations'] = $this->locationRepo->getSocketLocationWithSite();
        }

        return $clients;
    }

    /**
     * Create a new client
     */
    public function createClient(Array $data)
    {
        // Validate request parameters
		$missingParams = Validator::validateRequiredParams($this->clientRegisterParams, $data);
		if(!empty($missingParams))
		{
			$errors = Validator::convertToRequiredValidationErrors($missingParams);
			ErrorEvents::apiErrorOccurred("Validation error, " . join(";", $errors));

			return $this->apiResponse->validationError(
				["errors" => $errors]
			);
		}

        //Check for name
        if($this->clientRepo->getClientByName($data['name'])){
            $message = "The specified client name {$data['name']} already exists";
            Log::warning($message);
            return $this->apiResponse->generalError($message);
        }

        // Check for phone number
        if($this->clientRepo->getClientByPhoneNumber($data['phone_number'])){
            $message = "The specified client phone number {$data['phone_number']} already exists";
            Log::warning($message);
            return $this->apiResponse->generalError($message);
        }

        //Check for email
        if($this->clientRepo->getClientByEmail($data['email'])){
            $message = "The specified client email {$data['email']} already exists";
            Log::warning($message);
            return $this->apiResponse->generalError($message);
        }

        //Setup tenant
        $this->setUpTenant($data['short_name']);

        $data['uid'] = strtoupper(substr($data['short_name'], 0, 2)) . '-'. Generators::generateOrderNumber();

        $client = $this->clientRepo->createClient($data);
        if($client)
        {
            $user = $this->clientRepo->createClientUser($data,$client->id);
            if(!$user){
                $client->delete();
                $message = "Unable to create account for {$data['name']}";
                ErrorEvents::apiErrorOccurred($message);
                return $this->apiResponse->generalError($message);
            }

            $message = "Client created successfully";
    	    return $this->apiResponse->success($message, ["data" => $client]);
        }

        $message = "Unable to create account for {$data['name']}";
        ErrorEvents::apiErrorOccurred($message);
        return $this->apiResponse->generalError($message);

    }

    /****
     * Update Client
     */
    public function updateClient(Array $data,$uid)
    {
        //Get client by uid
        $client = $this->clientRepo->getClientByUid($uid);
        if(!$client){
            $message = "The specified client does not exists";
            Log::warning($message);
            return $this->apiResponse->notFoundError($message);
        }

        $data['uid'] = $uid;
        if($client = $this->clientRepo->updateClient($data,$client)){
            $message = "Client updated successfully";
    	    return $this->apiResponse->success($message, ["data" => $client]);
        }

        $message = "Could not update client";
        ErrorEvents::apiErrorOccurred($message);
        return $this->apiResponse->generalError($message);

    }

    
    /***
     * Delete Client and Tenant Structure
     */
    public function deleteClient($uid)
    {
        //Get client by uid
        $client = $this->clientRepo->getClientByUid($uid);
        if(!$client){
            $message = "The specified client does not exists";
            Log::warning($message);
            return $this->apiResponse->notFoundError($message);
        }
        //Delete Client
        $result = $this->deleteTenant($client->short_name);
        if(!$result){
            $message = "Could not delete client";
            Log::warning($message);
            return $this->apiResponse->notFoundError($message);
        }

        $client->delete();

        //Return response
        return $this->apiResponse->success($result, ["data" => null]);
    }

    /**
     * Setup a tenant
     */
    private function setUpTenant($name)
    {
        if (Tenant::tenantExists($name)) {
            $message = "The specified client already have a tenant db";
            Log::warning($message);
            return $this->apiResponse->generalError($message);
        }
        $tenant = Tenant::registerTenant($name);

        $this->setUpTenantEnvironment($name);

        return $tenant;
    }

    /**
     * Setup tenant environment
     */
    private function setUpTenantEnvironment($client_name)
    {
        $website = Website::where('uuid',$client_name)->first();
        app(Environment::class)->tenant($website);
        return $website;
    }

    /**
     * Delete a tenant
     */
    private function deleteTenant($name){
        $result = Tenant::delete($name);
        if($result){
            DB::statement("DROP DATABASE IF EXISTS {$name}");
            DB::statement("DROP USER IF EXISTS {$name}");
        }
        return $result;
    }

    private function setUpTenantUser(Array $data)
    {
        $website = Website::where('uuid',$data['short_name'])->first();
        app(Environment::class)->tenant($website);
    }

    /***
     * Client logo Upload
     */
    public function uploadLogo(Array $data, $client_uid)
    {
        // Validate request parameters
		$missingParams = Validator::validateRequiredParams($this->clientLogoUpload, $data);
		if(!empty($missingParams))
		{
			$errors = Validator::convertToRequiredValidationErrors($missingParams);
			ErrorEvents::apiErrorOccurred("Validation error, " . join(";", $errors));

			return $this->apiResponse->validationError(
				["errors" => $errors]
			);
		}

        //Get Client by uid
        $client = $this->clientRepo->getClientByUid($client_uid);
        if(!$client){
            $message = "The specified client {$client_uid} does not exists";
            Log::warning($message);
            return $this->apiResponse->notFoundError($message);
        }


        if($logo = $this->clientRepo->uploadLogo($data, $client)){
            $message = "Client logo uploaded successfully";
    	    return $this->apiResponse->success($message, ["data" => $logo]);
        }

        $message = "Could not upload client logo";
        ErrorEvents::apiErrorOccurred($message);
        return $this->apiResponse->generalError($message);
    }

    public function getLogo($client_uid)
    {
        return $this->clientRepo->getLogo($client_uid);
    }
}
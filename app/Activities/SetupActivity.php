<?php

namespace App\Activities;

use App\Repos\LocationRepo;
use App\Repos\SiteRepo;
use App\Api\ApiResponse;
use App\Traits\SetupTrait;
use App\Utilities\Validator;
use App\Events\ErrorEvents;
use App\Tenant;
use Illuminate\Support\Facades\Log;
use App\Activities\ClientActivity;
use App\Utilities\Generators;
use Hyn\Tenancy\Environment;
use Hyn\Tenancy\Models\Website;
use Illuminate\Support\Facades\DB;
use App\Utilities\ClientTenant;

class SetupActivity
{
    use SetupTrait;

    protected $locationRepo;
    protected $siteRepo;
    protected $apiResponse;

    public function __construct(LocationRepo $locationRepo, ApiResponse $apiResponse,SiteRepo $siteRepo)
    {
        $this->locationRepo = $locationRepo;
        $this->siteRepo = $siteRepo;
        $this->apiResponse = $apiResponse;
    }

    public function createLocation(Array $data,$client)
    {
        // Validate request parameters
		$missingParams = Validator::validateRequiredParams($this->locationRegisterParams, $data);
		if(!empty($missingParams))
		{
			$errors = Validator::convertToRequiredValidationErrors($missingParams);
			ErrorEvents::apiErrorOccurred("Validation error, " . join(";", $errors));

			return $this->apiResponse->validationError(
				["errors" => $errors]
			);
		}

        //Check for name
        if($this->locationRepo->getLocationByName($data['name'])){
            $message = "The specified location name {$data['name']} already exists";
            Log::warning($message);
            return $this->apiResponse->generalError($message);
        }

        $data['uid'] = substr($client->uid, 0, 2) .'-'. strtoupper(substr($data['name'], 0, 2)) . '-'. Generators::generateOrderNumber();

        $location = $this->locationRepo->createLocation($data);
        if($location)
        {
            $message = "Location created successfully";
    	    return $this->apiResponse->success($message, ["data" => $location]);
        }

        $message = "Unable to create account for {$data['name']}";
        ErrorEvents::apiErrorOccurred($message);
        return $this->apiResponse->generalError($message);

    }

    /**
     * Setup Location Site
     */
    public function createSite(Array $data,$client)
    {
        // Validate request parameters
		$missingParams = Validator::validateRequiredParams($this->siteRegisterParams, $data);
		if(!empty($missingParams))
		{
			$errors = Validator::convertToRequiredValidationErrors($missingParams);
			ErrorEvents::apiErrorOccurred("Validation error, " . join(";", $errors));

			return $this->apiResponse->validationError(
				["errors" => $errors]
			);
		}

        //Check for name
        $location = $this->locationRepo->getLocationByUid($data['location_uid']);
        if(!$location){
            $message = "The specified location {$data['location_uid']} does not exist";
            Log::warning($message);
            return $this->apiResponse->generalError($message);
        }

        //Check if site for name
        if($this->siteRepo->getSiteByName($data['name'])){
            $message = "The specified site {$data['name']} already exist";
            Log::warning($message);
            return $this->apiResponse->generalError($message);
        }

        //Check site for serial number
        if(isset($data['unit_serial_number']) && $this->siteRepo->getSiteBySerialNumber($data['unit_serial_number'])){
            $message = "The specified site {$data['unit_serial_number']} already exist";
            Log::warning($message);
            return $this->apiResponse->generalError($message);
        }

        $data['uid'] = substr($location->uid, 0, 5) .'-'. strtoupper(substr($data['name'], 0, 2)) . '-'. Generators::generateOrderNumber();
        $data['location_id'] = $location->id;

        $site = $this->siteRepo->createSite($data);
        if($site)
        {
            $message = "Site created successfully";
    	    return $this->apiResponse->success($message, ["data" => $site]);
        }

        $message = "Unable to create site for {$data['name']}";
        ErrorEvents::apiErrorOccurred($message);
        return $this->apiResponse->generalError($message);

    }

    /**
     * Get Site By Serial Number
     */

     public function getSiteBySerialNumber($serial_number,$client)
     {
        ClientTenant::setUpTenantEnvironment($client->short_name);

        $site = $this->siteRepo->getSiteBySerialNumber($serial_number);

        if(!$site){
            $message = "The specified site does not exists";
            Log::warning($message);
            return $this->apiResponse->notFoundError($message);
        }

        $site['client'] = $client;
        $message = "Site retrieved successfully";
        return $this->apiResponse->success($message, ["data" => $site]);
     }

    /**
     * Setup Site Sources
     */
    public function addSiteSources(Array $data,$site_uid)
    {
        // Validate request parameters
		// $missingParams = Validator::validateRequiredParams($this->siteSourceParams, $data);
		// if(!empty($missingParams))
		// {
		// 	$errors = Validator::convertToRequiredValidationErrors($missingParams);
		// 	ErrorEvents::apiErrorOccurred("Validation error, " . join(";", $errors));

		// 	return $this->apiResponse->validationError(
		// 		["errors" => $errors]
		// 	);
		// }

        $site = $this->siteRepo->getSiteByUid($site_uid);

        if(!$site){
            $message = "The specified site does not exists";
            Log::warning($message);
            return $this->apiResponse->notFoundError($message);
        }


        if($this->siteRepo->addSiteSources($site->id))
        {
            $message = "Site sources added successfully";
    	    return $this->apiResponse->success($message, ["data" => $site->sources]);
        }

        $message = "Unable to add site site source";
        ErrorEvents::apiErrorOccurred($message);
        return $this->apiResponse->generalError($message);

    }

}
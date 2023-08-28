<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Activities\SetupActivity;
use App\Activities\ClientActivity;
use App\Api\ApiResponse;
use App\Events\ErrorEvents;
use Illuminate\Http\Request;
use Hyn\Tenancy\Environment;
use Hyn\Tenancy\Models\Website;
use Illuminate\Support\Facades\Log;

class SetupController extends Controller
{
    protected $setupActivity;
    protected $apiResponse;

    public function __construct(SetupActivity $setupActivity,ApiResponse $apiResponse,ClientActivity $clientActivity)
    {
        $this->setupActivity = $setupActivity;
        $this->clientActivity = $clientActivity;
        $this->apiResponse = $apiResponse;
    }

    public function createLocation(Request $request)
    {
        try {
            
            $client = $this->setupTenant($request);

            return $this->setupActivity->createLocation($request->all(),$client);
        } catch (\Exception $e) {
            ErrorEvents::ServerErrorOccurred($e);
            return $this->apiResponse->serverError();
        }
    }

    public function update(Request $request,$uid)
    {
        
    }

    public function delete($uid){
        
    }

    public function createSite(Request $request)
    {
        try {
            
            $client = $this->setupTenant($request);

            return $this->setupActivity->createSite($request->all(),$client);
        } catch (\Exception $e) {
            ErrorEvents::ServerErrorOccurred($e);
            return $this->apiResponse->serverError();
        }
    }

    public function getSiteBySerialNumber(Request $request, $serial_number){
        try {
            
            $client = $this->setupTenant($request);

            return $this->setupActivity->getSiteBySerialNumber($serial_number,$client);
        } catch (\Exception $e) {
            ErrorEvents::ServerErrorOccurred($e);
            return $this->apiResponse->serverError();
        }
    }

    private function setupTenant(Request $request){
        
        /**
         * Add a check in the middleware for uid
         */
        $client_uid = $request->header('client-uid');

        $client = $this->clientActivity->getClientByUid($client_uid);

        if(!$client){
            $message = "The specified client does not exists";
            Log::warning($message);
            return $this->apiResponse->notFoundError($message);
        }

        $website = Website::where('uuid',$client->short_name)->first();
        app(Environment::class)->tenant($website);

        return $client;
    }

    public function addSiteSources(Request $request,$uid)
    {
        try {
            
            $client = $this->setupTenant($request);

            return $this->setupActivity->addSiteSources($request->all(),$uid);
        } catch (\Exception $e) {
            ErrorEvents::ServerErrorOccurred($e);
            return $this->apiResponse->serverError();
        }
    }
}

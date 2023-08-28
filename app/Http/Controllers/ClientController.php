<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Activities\ClientActivity;
use App\Api\ApiResponse;
use App\Events\ErrorEvents;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Log;

class ClientController extends Controller
{
    protected $clientActivity;
    protected $apiResponse;

    public function __construct(ClientActivity $clientActivity,ApiResponse $apiResponse)
    {
        $this->clientActivity = $clientActivity;
        $this->apiResponse = $apiResponse;
    }

    public function create(Request $request)
    {
        try {
            return $this->clientActivity->createClient($request->all());
        } catch (\Exception $e) {
            ErrorEvents::ServerErrorOccurred($e);
            return $this->apiResponse->serverError();
        }
    }

    public function update(Request $request,$uid)
    {
        try {
            return $this->clientActivity->updateClient($request->all(),$uid);
        } catch (\Exception $e) {
            ErrorEvents::ServerErrorOccurred($e);
            return $this->apiResponse->serverError();
        }
    }

    public function delete($uid)
    {
        try {
            return $this->clientActivity->deleteClient($uid);
        } catch (\Exception $e) {
            ErrorEvents::ServerErrorOccurred($e);
            return $this->apiResponse->serverError();
        }
    }

    public function socketClients(Request $request)
    {
    
        try {
            return $this->clientActivity->getSocketClients();
        } catch (\Exception $e) {
            ErrorEvents::ServerErrorOccurred($e);
            return $this->apiResponse->serverError();
        }
    }

    public function uploadLogo(Request $request, $client_uid)
    {
        try {
            return $this->clientActivity->uploadLogo($request->all(),$client_uid);
        } catch (\Exception $e) {
            ErrorEvents::ServerErrorOccurred($e);
            return $this->apiResponse->serverError();
        }
    }

    public function logo(Request $request, $client_uid)
    {
        $logo = $this->clientActivity->getLogo($client_uid);

        if(!isset($logo)){
            return "image not found";
        }

        $logo_image = base64_decode($logo->img);

        $response = Response::make($logo_image);

        $response->header('Content-Type', 'image/jpeg');

        return $response;

    }
}

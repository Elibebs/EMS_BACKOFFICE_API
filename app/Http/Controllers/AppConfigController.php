<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Activities\AppConfigActivity;
use App\Api\ApiResponse;
use App\Events\ErrorEvents;
use Illuminate\Http\Request;
use Hyn\Tenancy\Environment;
use Hyn\Tenancy\Models\Website;
use Illuminate\Support\Facades\Log;

class AppConfigController extends Controller
{
    protected $setupActivity;
    protected $apiResponse;

    public function __construct(AppConfigActivity $appConfigActivity,ApiResponse $apiResponse)
    {
        $this->appConfigActivity = $appConfigActivity;
        $this->apiResponse = $apiResponse;
    }

    public function getClientVersionCode(Request $request)
    {
        try {
            return $this->appConfigActivity->getClientVersionCode();
        } catch (\Exception $e) {
            ErrorEvents::ServerErrorOccurred($e);
            return $this->apiResponse->serverError();
        }
    }


    public function addClientVersionCode(Request $request)
    {
        try {
            return $this->appConfigActivity->addClientVersionCode($request->all());
        } catch (\Exception $e) {
            ErrorEvents::ServerErrorOccurred($e);
            return $this->apiResponse->serverError();
        }
    }

}

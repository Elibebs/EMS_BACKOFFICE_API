<?php

namespace App\Activities;

use App\Repos\ClientRepo;
use App\Repos\LocationRepo;
use App\Api\ApiResponse;
use App\Traits\AppConfigTrait;
use App\Utilities\Validator;
use App\Events\ErrorEvents;
use App\Tenant;
use Illuminate\Support\Facades\Log;
use App\Activities\ClientActivity;
use App\Utilities\Generators;
use Hyn\Tenancy\Environment;
use Hyn\Tenancy\Models\Website;
use Illuminate\Support\Facades\DB;

class AppConfigActivity
{
    use AppConfigTrait;

    protected $apiResponse;

    public function __construct(ApiResponse $apiResponse)
    {
        $this->apiResponse = $apiResponse;
    }

    public function getClientVersionCode()
    {
        $jsonString = file_get_contents(base_path('storage/version_db.json'));
        $version_data = json_decode($jsonString, true);

        $message = "Client version code retrieved successfully";
        return $this->apiResponse->success($message, ["data" => $version_data]);
    }

    /**
     * Create a new client
     */
    public function addClientVersionCode(Array $data)
    {
        // Validate request parameters
		$missingParams = Validator::validateRequiredParams($this->clientVersionCodeParams, $data);
		if(!empty($missingParams))
		{
			$errors = Validator::convertToRequiredValidationErrors($missingParams);
			ErrorEvents::apiErrorOccurred("Validation error, " . join(";", $errors));

			return $this->apiResponse->validationError(
				["errors" => $errors]
			);
		}

        $jsonString = file_get_contents(base_path('storage/version_db.json'));
        $version_data = json_decode($jsonString, true);
        $version_data['version.code'] = $data['version_code'];

        $newJsonString = json_encode($version_data, JSON_PRETTY_PRINT);

        
        if(file_put_contents(base_path('storage/version_db.json'), stripslashes($newJsonString)))
        {
            $message = "Client version code updated successfully";
    	    return $this->apiResponse->success($message, ["data" => $version_data]);
        }

        $message = "Unable to create client version code";
        ErrorEvents::apiErrorOccurred($message);
        return $this->apiResponse->generalError($message);

    }

}
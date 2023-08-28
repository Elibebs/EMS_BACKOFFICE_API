<?php

namespace App\Repos;

use App\Models\Location;
use App\Utilities\Constants;
use Carbon\Carbon;


class LocationRepo
{
    public function __construct()
    {

    }

    public function getLocationByName($name){
        return Location::where('name',$name)->first();
    }

    public function getLocationByUid($uid){
        return Location::where('uid',$uid)->first();
    }

    public function getSocketLocationWithSite(){
        return Location::with('sites')->get();
    }

    public function createLocation(Array $data)
    {
        $location = new Location();
        $location->name = $data['name'];
        $location->uid = $data['uid'];
        $location->created_at = Carbon::now();
    	$location->updated_at = Carbon::now();

        if($location->save()){
            return $location;
        }
        return null;
    }

}
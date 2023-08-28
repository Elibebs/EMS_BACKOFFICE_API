<?php

namespace App\Repos;

use App\Models\Site;
use App\Utilities\Constants;
use Carbon\Carbon;
use App\Utilities\ClientTenant;
use App\Models\SiteSource;


class SiteRepo
{
    public function __construct()
    {

    }

    public function getSiteByName($name){
        return Site::where('name',$name)->first();
    }

    public function getSiteByUid($uid){
        return Site::where('uid',$uid)->first();
    }

    public function getSiteBySerialNumber($serial_number){
        return Site::with('location')->where('unit_serial_number',$serial_number)->first();
    }

    public function createSite(Array $data)
    {
        $site = new Site();
        $site->name = $data['name'];
        $site->uid = $data['uid'];
        $site->unit_serial_number = isset($data['unit_serial_number']) ? $data['unit_serial_number'] : null;
        $site->location_id = $data['location_id'];
        $site->created_at = Carbon::now();
    	$site->updated_at = Carbon::now();
        $site->ct = $data['ct'];
        $site->active = isset($data['active']) ? $data['active'] : false;

        if($site->save()){
            if(SiteSource::insert(ClientTenant::getSiteSources($site->id)))
                return $site;
            else
                $site->delete();
        }
        return null;
    }

    public function addSiteSources($site_id)
    {
        if(SiteSource::insert(ClientTenant::getSiteSources($site_id))){
            return true;
        }

        return false;
    }

}
<?php 

namespace App\Utilities; 

use Hyn\Tenancy\Environment;
use Hyn\Tenancy\Models\Website;

class ClientTenant
{

    public static function setUpTenantEnvironment($client_name)
    {
        $website = Website::where('uuid',$client_name)->first();
        app(Environment::class)->tenant($website);
        return $website;
    }

    public static function getSiteSources($site_id)
    {
        return [
                [
                    'key'=>'MAINS1', 
                    'label'=>'ECG',
                    'site_id'=>$site_id
                ],
                [   
                    'key'=>'SOLAR1', 
                    'label'=>'SOLAR 1',
                    'site_id'=>$site_id
                ],
                [   
                    'key'=>'GENERATOR1', 
                    'label'=>'GENERATOR 1',
                    'site_id'=>$site_id
                ],
                [   
                    'key'=>'MAINS2', 
                    'label'=>'MAINS 2',
                    'site_id'=>$site_id
                ],
                [   
                    'key'=>'GENERATOR2', 
                    'label'=>'GENERATOR 2',
                    'site_id'=>$site_id
                ],
                [   
                    'key'=>'SOLAR2', 
                    'label'=>'SOLAR 2',
                    'site_id'=>$site_id
                ],
                [   
                    'key'=>'GENERATOR3', 
                    'label'=>'GENERATOR 3',
                    'site_id'=>$site_id
                ],
                [   
                    'key'=>'SOLAR3', 
                    'label'=>'SOLAR 3',
                    'site_id'=>$site_id
                ],
                [   
                    'key'=>'HVAC', 
                    'label'=>'HVAC',
                    'site_id'=>$site_id
                ],
                [   
                    'key'=>'LIGHTING', 
                    'label'=>'LIGHTING',
                    'site_id'=>$site_id
                ],
                [   
                    'key'=>'POWERPOINTS_SOCKETS', 
                    'label'=>'SOCKET & OTHERS',
                    'site_id'=>$site_id
                ],
                [   
                    'key'=>'CRITICAL_POWER', 
                    'label'=>'UPS',
                    'site_id'=>$site_id
                ],
                [   
                    'key'=>'LOAD1', 
                    'label'=>'LOAD 1',
                    'site_id'=>$site_id
                ],
                [   
                    'key'=>'LOAD2', 
                    'label'=>'LOAD 2',
                    'site_id'=>$site_id
                ],
                [   
                    'key'=>'LOAD3', 
                    'label'=>'LOAD 3',
                    'site_id'=>$site_id
                ],
                [   
                    'key'=>'LOAD4', 
                    'label'=>'LOAD 4',
                    'site_id'=>$site_id
                ],
            ];
    }
}
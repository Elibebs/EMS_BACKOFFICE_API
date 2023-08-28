<?php

namespace App\Repos;

use App\Models\Client;
use App\Models\ClientUser;
use App\Utilities\Constants;
use Carbon\Carbon;
use App\Models\Logo;
use Illuminate\Support\Facades\Log;


class ClientRepo
{
    public function __construct()
    {

    }

    public function createClient(Array $data)
    {
        $client = new Client();

        $client->name = $data['name'];
        $client->email = $data['email'];
        $client->phone_number = $data['phone_number'];
        $client->short_name = $data['short_name'];
        $client->uid = $data['uid'];
        $client->created_at = Carbon::now();
    	$client->updated_at = Carbon::now();

        if($client->save()){
            return $client;
        }

        return null;
    }

    public function updateClient(Array $data)
    {
        $client = $this->getClientByUid($data['uid']);

        if(isset($data['name']))
            $client->name = $data['name'];
        if(isset($data['email']))
            $client->email = $data['email'];
        if(isset($data['phone_number']))
            $client->phone_number = $data['phone_number'];
        
        $client->updated_at = Carbon::now();
        $client->created_at = Carbon::now();
        
        if($client->update()){
            return $client;
        }

        return null;
    }

    public function createClientUser(Array $data,$client_id)
    {
        $user = new ClientUser();

        $user->name = ucfirst($data['short_name']) . ' Admin';
        $user->client_id = $client_id;
        $user->email = $data['email'];
        $user->password = \Hash::make('secret2021');
        $user->status = Constants::STATUS_ENABLED;
        $user->access_token = null;
		$user->session_id = null;
		$user->session_id_time = null;
		$user->last_logged_in = null;
    	$user->created_at = Carbon::now();
    	$user->updated_at = Carbon::now();

        if($user->save()){
            return $user;
        }

        return null;
    }

    public function uploadLogo(Array $data, $client)
    {

    	try
    	{
            Log::notice('Image Passed');
            Log::notice($data['image']);

            $imageData=$data['image'];
            if(isset($imageData))
            {
                $json_image = json_decode($imageData);
                $base64 = preg_replace('/data:[\s\S]+?base64,/', '', $json_image->base64String);
                
                //Get Image for client
                $logo = Logo::where('client_id',$client->id)->first();
                if(!$logo){
                    $logo = new Logo();
                    $logo->created_at = Carbon::now();
                    $logo->updated_at = Carbon::now();
                    $logo->client_id = $client->id;
                }
                    
                $logo->name = $client->uid;
                $logo->img = base64_encode($base64);

                if($logo->save()){
                    return $logo;
                }

            }
            Log::notice('user image skipped = ');
    	}
        catch (\Exception $e) 
        {
            Log::notice('user image exception = '.$e);
			return null;
		}

		return false;
    }


    public function deleteClient($id)
    {

    }

    public function getClientByName($name)
    {
        return Client::where('name','like',$name)->first();
    }

    public function getClientByPhoneNumber($phone_number)
    {
        return Client::where('phone_number','=',$phone_number)->first();
    }

    public function getClientByEmail($email)
    {
        return Client::where('email','=',$email)->first();
    }

    public function getClientByUid($uid)
    {
        return Client::where('uid','=',$uid)->first();
    }

    public function getAllClients()
    {
        return Client::all();
    }

    public function getLogo($client_id)
    {
        return Logo::where('name',$client_id)->first();
    }

}
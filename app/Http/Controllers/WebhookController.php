<?php

namespace App\Http\Controllers;

// Get our helper functions
require_once("inc/functions.php");

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Redirect;
use DB;

class WebhookController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        //
    }


    public function appUninstalledWebhook()
    {
        $payload = json_decode(file_get_contents('php://input'), true);
        file_put_contents('app_uninstalled.txt', print_r($payload,true));

        if(isset($payload) && !empty($payload)){
            $shopify_domain = $payload['myshopify_domain'];

            $user = User::where('name', '=', $shopify_domain)->first();

            if(!empty($user)){
                DB::table('users')->where('id', '=', $user->id)->delete();
            }
        }
    }

}

<?php

namespace App\Http\Controllers;

// Get our helper functions
require_once("inc/functions.php");

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Redirect;
use DB;
use App\Mail\ShopDelete;
use App\Mail\CustomerDelete;
use App\Mail\CustomerRequest;
use Mail;

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

    public function customersDelete()
    {
        $webhook_payload = file_get_contents('php://input');
        $webhook_payload = json_decode($webhook_payload, true);

        $shop_id = $webhook_payload['shop_id'];
        $shop_domain = $webhook_payload['shop_domain'];

        $customer_id = $webhook_payload['customer']['id'];
        $customer_email = $webhook_payload['customer']['email'];
        $customer_phone = $webhook_payload['customer']['phone'];

        $customer_orders = $webhook_payload['orders_to_redact'];

        $details = [
            'subject' => 'Customer ('.$customer_email.') request to delete their data',
            'shop_id' => $shop_id,
            'shop_domain' => $shop_domain,
            'customer_id' => $customer_id,
            'customer_email' => $customer_email,
            'customer_phone' => $customer_phone,
            'customer_orders' => $customer_orders,
        ];

        Mail::to('mwalmer+pzwuv61db1kcfrbh7otz@boards.trello.com')->send(new CustomerDelete($details));
    }

    public function customersRequest()
    {
        $webhook_payload = file_get_contents('php://input');
        $webhook_payload = json_decode($webhook_payload, true);

        $shop_id = $webhook_payload['shop_id'];
        $shop_domain = $webhook_payload['shop_domain'];

        $customer_id = $webhook_payload['customer']['id'];
        $customer_email = $webhook_payload['customer']['email'];
        $customer_phone = $webhook_payload['customer']['phone'];

        $customer_orders = $webhook_payload['orders_requested'];

        $details = [
            'subject' => 'Customer ('.$customer_email.') request to view their data',
            'shop_id' => $shop_id,
            'shop_domain' => $shop_domain,
            'customer_id' => $customer_id,
            'customer_email' => $customer_email,
            'customer_phone' => $customer_phone,
            'customer_orders' => $customer_orders,
        ];

        Mail::to('mwalmer+pzwuv61db1kcfrbh7otz@boards.trello.com')->send(new CustomerRequest($details));
    }

    public function shopDelete()
    {
        $webhook_payload = file_get_contents('php://input');
        $webhook_payload = json_decode($webhook_payload, true);

        $shop_id = $webhook_payload['shop_id'];
        $shop_domain = $webhook_payload['shop_domain'];

        $details = [
            'subject' => 'Remove Shop Data',
            'shop_id' => $shop_id,
            'shop_domain' => $shop_domain,
        ];

        Mail::to('anis.infowind@gmail.com')->send(new ShopDelete($details));
        Mail::to('mwalmer+pzwuv61db1kcfrbh7otz@boards.trello.com')->send(new ShopDelete($details));
    }

}

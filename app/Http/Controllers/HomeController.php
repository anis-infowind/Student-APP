<?php

namespace App\Http\Controllers;

// Get our helper functions
require_once("inc/functions.php");

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\User;
use Auth;
use Illuminate\Support\Facades\Redirect;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $previous_shop = User::orderBy('id','asc')->first();

        if(!empty($previous_shop)) {
            $user = User::find($previous_shop->id);

            if(!empty($user)) {
                Auth::login($user);

                $shop = Auth::user();
            }
        }
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $previous_shop = User::orderBy('id','asc')->first();

        if(!empty($previous_shop)) {
            $user = User::find($previous_shop->id);

            if(!empty($user)) {
                Auth::login($user);

                $shop = Auth::user();
                $shop_name = $shop->name;
                $access_token = $shop->password;

                $arrContextOptions=array(
                    "ssl"=>array(
                        "verify_peer"=>false,
                        "verify_peer_name"=>false,
                    ),
                );

                // Run API call to get themes
                $theme_api = shopify_call($access_token, $shop_name, "/admin/themes.json", array(), 'GET');

                if(!empty($theme_api['response'])){
                    $theme = json_decode($theme_api['response'], true);
                    //$theme_id = $theme['themes'][0]['id'];
                    $theme_id = 120205148224;

                    // Modify test assets snippets data
                    $appdata = file_get_contents(url('/').'/verifystudent_common.liquid', false, stream_context_create($arrContextOptions));

                    $update_assets = array(
                        "asset" => array(
                            "key"   => "snippets/verifystudent_common.liquid",
                            "value" => $appdata,
                        )
                    );

                    $theme_res = shopify_call($access_token, $shop_name, '/admin/themes/'.$theme_id.'/assets.json', $update_assets, 'PUT');
                }


                $check_webhook = shopify_call($access_token, $shop_name, '/admin/webhooks.json', array(), 'GET');

                if(!empty($check_webhook['response'])){
                    $webhook_data = json_decode($check_webhook['response'], true);
                    $app_unistalled_url = url('/api/app-uninstall');

                    if(!empty($webhook_data['webhooks'])){
                        $get_unistalled_url = $webhook_data['webhooks'][0]['address'];

                        if($get_unistalled_url == $app_unistalled_url){
                            $update_webhook = array(
                                "webhook" => array(
                                    "topic"   => "app/uninstalled",
                                    "address" => url('/api/app-uninstall')
                                )
                            );

                            shopify_call($access_token, $shop_name, '/admin/webhooks.json', $update_webhook, 'PUT');
                        }
                    } else {
                        $add_webhook = array(
                            "webhook" => array(
                                "topic"   => "app/uninstalled",
                                "address" => $app_unistalled_url
                            )
                        );
        
                        shopify_call($access_token, $shop_name, '/admin/webhooks.json', $add_webhook, 'POST');
                    }

                }

                // Add and Update Script tag
                $liquid_array = shopify_call($access_token, $shop_name, '/admin/script_tags.json', array() , 'GET');

                if(!empty($liquid_array['response'])){
                    $script_tag_array = json_decode($liquid_array['response'], true);

                    $verifystudent_script = url('/').'/public/js/verifystudentcode.min.js';


                    $script_tag = array(
                        "script_tag" => array(
                            "event"   => "onload",
                            "src" => "".$verifystudent_script."",
                        )
                    );

                    if(!empty($script_tag_array['script_tags'][0])){

                        $value = $script_tag_array['script_tags'][0];
                        $script_id = $value['id'];
                        $script_src = $value['src'];
                        
                        if ($script_src == $verifystudent_script) {

                            $script_tag_update = array(
                                "script_tag" => array(
                                    "id"   => $script_id,
                                    "src" => "".$verifystudent_script."",
                                )
                            );

                            shopify_call($access_token, $shop_name, '/admin/script_tags.json', $script_tag, 'PUT');
                        }
                    } else {
                        shopify_call($access_token, $shop_name, '/admin/script_tags.json', $script_tag, 'POST');
                    }
                }

                /*$get_theme_assets = shopify_call($access_token, $shop_name, '/admin/themes/122026098893/assets.json?asset[key]=layout/theme.liquid&theme_id=122026098893', array(), 'GET');

                if(!empty($get_theme_assets['response'])){
                    $theme_liquid_array = json_decode($get_theme_assets['response'], true);
                    $theme_liquid_html = $theme_liquid_array['asset']['value'];

                    if (strpos($theme_liquid_html, "{% include 'verifystudent_common' %}") == false) {
                        $pos = strpos($theme_liquid_html, '{{ content_for_header }}');
                        $str_to_insert = "{% include 'verifystudent_common' %}";
                        $new_theme_liquid = substr_replace($theme_liquid_html, $str_to_insert, $pos, 0);

                        $update_theme_assets = array(
                            "asset" => array(
                                "key" => "layout/theme.liquid",
                                "value" => ".$new_theme_liquid."
                            )
                        );

                        $theme_assets = shopify_call($access_token, $shop_name, '/admin/themes/122026098893/assets.json', $update_theme_assets, 'PUT');
                    }
                    
                }*/

                return view('welcome');
                
            }
        } else {
            return view('login');
        }

    }

}

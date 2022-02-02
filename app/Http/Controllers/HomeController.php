<?php

namespace App\Http\Controllers;

// Get our helper functions
require_once("inc/functions.php");

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\User;
use App\Settings;
use Auth;
use Illuminate\Support\Facades\Redirect;
use Session;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public $app_block_templates = array('cart');

    public function __construct()
    {
        if(isset($_GET['host']) && !empty($_GET['host'])){
            Session::put('shopify_Host', $_GET['host']);
        }

        if(isset($_GET['shop']) && !empty($_GET['shop'])){
            Session::put('shopify_Shop', $_GET['shop']);
        }
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        
        $shop = Auth::user();

        if(!empty($shop)) {
            $shop_name = $shop->name;
            $access_token = $shop->password;

            $shop_id = $shop->id;

            // Success text
            $settings = Settings::where('store_id', '=', $shop_id)->get();

            if(count($settings) == 0) {
                $new_settings = new Settings();
                $new_settings->store_id = $shop_id;
                $new_settings->meta_key = 'student_btn_text';
                $new_settings->meta_value = 'Student Discount';
                $new_settings->save();

                $new_settings = new Settings();
                $new_settings->store_id = $shop_id;
                $new_settings->meta_key = 'success_txt';
                $new_settings->meta_value = 'Success';
                $new_settings->save();

                $new_settings = new Settings();
                $new_settings->store_id = $shop_id;
                $new_settings->meta_key = 'failure_txt';
                $new_settings->meta_value = 'Failure';
                $new_settings->save();

                $new_settings = new Settings();
                $new_settings->store_id = $shop_id;
                $new_settings->meta_key = 'default_discount';
                $new_settings->meta_value = '';
                $new_settings->save();

            }

            $arrContextOptions=array(
                "ssl"=>array(
                    "verify_peer"=>false,
                    "verify_peer_name"=>false,
                ),
            );

            $theme_has_app_block = false;

            // Run API call to get themes
            $theme_api = shopify_call($access_token, $shop_name, "/admin/themes.json", array(), 'GET');
            if(!empty($theme_api['response'])){
                $theme_id = '';
                $themes = json_decode($theme_api['response'], true);
                foreach ($themes['themes'] as $theme) {
                    if ($theme['role'] === 'main') {
                        $theme_id = $theme['id'];
                    }
                }
                //$theme_id = 120205148224;

                $theme_assets_res = shopify_call($access_token, $shop_name, '/admin/themes/'.$theme_id.'/assets.json', array('fields' => 'key'), 'GET');
                if(!empty($theme_assets_res['response'])){
                    $theme_assets = json_decode($theme_assets_res['response'], true);

                    $theme_assets_make = array_filter($theme_assets['assets'], array($this, 'filter_template_item'));

                    if(!empty($theme_assets_make)) {
                        foreach($theme_assets_make as $key => $value) {
                            $theme_assets_template = shopify_call($access_token, $shop_name, '/admin/api/2021-10/themes/'.$theme_id.'/assets.json', array('asset[key]' => $value['key'], 'fields' => 'value'), 'GET');
                            $theme_assets_template_decode = json_decode($theme_assets_template['response'], true);
                            $theme_assets_sections = json_decode($theme_assets_template_decode['asset']['value'], true);

                            foreach($theme_assets_sections['sections'] as $section_key => $section_value) {
                                $theme_sections_data = shopify_call($access_token, $shop_name, '/admin/api/2021-10/themes/'.$theme_id.'/assets.json', array('asset[key]' => 'sections/'.$section_value['type'].'.liquid', 'fields' => 'value'), 'GET');
                                
                                $theme_sections_decode = json_decode($theme_sections_data['response'], true);

                                if (str_contains($theme_sections_decode['asset']['value'], '@app')) {
                                    $theme_has_app_block = true;
                                }
                                
                                
                            }
                        }
                    }

                    
                }

                // Modify test assets snippets data
                $appdata = file_get_contents(url('/').'/verifystudent_common.liquid', false, stream_context_create($arrContextOptions));

                $update_assets = array(
                    "asset" => array(
                        "key"   => "snippets/verifystudent_common.liquid",
                        "value" => $appdata,
                    )
                );

                if(!$theme_has_app_block) {
                    $theme_res = shopify_call($access_token, $shop_name, '/admin/themes/'.$theme_id.'/assets.json', $update_assets, 'PUT');
                }
                


                // Modify test assets snippets data
                $verify_btn_snippet = file_get_contents(url('/').'/verifystudent_btn.liquid', false, stream_context_create($arrContextOptions));

                $verify_btn_update_assets = array(
                    "asset" => array(
                        "key"   => "snippets/verifystudent_btn.liquid",
                        "value" => $verify_btn_snippet,
                    )
                );

                if(!$theme_has_app_block) {
                    $verify_btn_res = shopify_call($access_token, $shop_name, '/admin/themes/'.$theme_id.'/assets.json', $verify_btn_update_assets, 'PUT');
                }
                
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

                if(!empty($script_tag_array['script_tags'])){

                    foreach ($script_tag_array['script_tags'] as $value) {
                        $script_id = $value['id'];
                        $script_src = $value['src'];
                        if ($script_src == $verifystudent_script) {
        
                            $script_tag_update = array(
                                "script_tag" => array(
                                    "id"   => $script_id,
                                    "src" => "".$verifystudent_script."",
                                )
                            );

                            shopify_call($access_token, $shop_name, '/admin/script_tags/'.$script_id.'.json', $script_tag_update, 'PUT');
                            
                            if($theme_has_app_block) {
                                $ddd = shopify_call($access_token, $shop_name, '/admin/script_tags/'.$script_id.'.json', array(), 'DELETE');
                            }
                        }
                    }

                } else {
                    if(!$theme_has_app_block) {
                        shopify_call($access_token, $shop_name, '/admin/script_tags.json', $script_tag, 'POST');
                    }
                }
            }
            if(isset($_GET['page']) && $_GET['page'] == 'home') {
                return view('welcome');
            } else {
                $shop_name = $shop->name;
                $access_token = $shop->password;

                $discount_response = shopify_call($access_token, $shop_name, "/admin/price_rules.json", array(), 'GET');

                if(!empty($discount_response['response'])){
                    $discounts = json_decode($discount_response['response'], true);

                    return view('discounts.discount', ['discounts' => $discounts]);
                } else {
                    return view('discounts.discount', ['discounts' => []]);
                }
            }
            
            
        } else {
            return view('login');
        }

    }

    public function filter_template_item($template){
        

        $app_block_templates_arr = array();
        foreach ($this->app_block_templates as $key => $a) {
            $app_block_templates_arr[] = 'templates/'.$a.'.json';
        }
        
        if ( in_array($template["key"], $app_block_templates_arr) ) {
            return $template;
        }
    }

}

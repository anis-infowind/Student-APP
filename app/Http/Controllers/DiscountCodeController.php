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
use Response;
use Session;

class DiscountCodeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

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

            $discount_response = shopify_call($access_token, $shop_name, "/admin/price_rules.json", array(), 'GET');

            if(!empty($discount_response['response'])){
                $discounts = json_decode($discount_response['response'], true);

                return view('discounts.discount', ['discounts' => $discounts]);
            } else {
                return view('discounts.discount', ['discounts' => []]);
            } 
        } else {
            return view('discounts.discount', ['discounts' => []]);
        }

    }

    public function show()
    {
        return view('discounts.create');
    }

    public function store(Request $request)
    {
        $shop = Auth::user();
        $shop_name = $shop->name;
        $access_token = $shop->password;

        $discount_code = $request->discount_code;
        $value_type = $request->value_type;
        $value = $request->value;
        $apply_to = $request->apply_to;
        $start_date = $request->start_date;
        $end_date = $request->end_date;



        if($apply_to == 'all_products'){

            if(!empty($end_date)){
                $price_rule_json = array(
                    "price_rule" => array(
                        "title" => $discount_code,
                        "target_type" => "line_item",
                        "target_selection" => "all",
                        "allocation_method" => "across",
                        "value_type" => $value_type,
                        "value" => "-".$value,
                        "customer_selection" => "all",
                        "starts_at" => $start_date,
                        "ends_at" => $end_date
                    )
                );
            } else {
                $price_rule_json = array(
                    "price_rule" => array(
                        "title" => $discount_code,
                        "target_type" => "line_item",
                        "target_selection" => "all",
                        "allocation_method" => "across",
                        "value_type" => $value_type,
                        "value" => "-".$value,
                        "customer_selection" => "all",
                        "starts_at" => $start_date
                    )
                );
            }
            
        } elseif($apply_to == 'specific_collections'){
            $collection_ids = [];
            $collections = $request->collections;

            foreach($collections as $collection) {
                $collection_ids[] = $collection;
            }

            if(!empty($end_date)){
                $price_rule_json = array(
                    "price_rule" => array(
                        "title" => $discount_code,
                        "target_type" => "line_item",
                        "target_selection" => "entitled",
                        "allocation_method" => "across",
                        "value_type" => $value_type,
                        "value" => "-".$value,
                        "customer_selection" => "all",
                        "entitled_collection_ids" => $collection_ids,
                        "starts_at" => $start_date,
                        "ends_at" => $end_date
                    )
                );
            } else {
                $price_rule_json = array(
                    "price_rule" => array(
                        "title" => $discount_code,
                        "target_type" => "line_item",
                        "target_selection" => "entitled",
                        "allocation_method" => "across",
                        "value_type" => $value_type,
                        "value" => "-".$value,
                        "customer_selection" => "all",
                        "entitled_collection_ids" => $collection_ids,
                        "starts_at" => $start_date
                    )
                );
            }
            
        } elseif($apply_to == 'specific_products'){
            $product_ids = [];
            $products = $request->products;

            foreach($products as $product) {
                $product_ids[] = $product;
            }

            if(!empty($end_date)){
                $price_rule_json = array(
                    "price_rule" => array(
                        "title" => $discount_code,
                        "target_type" => "line_item",
                        "target_selection" => "entitled",
                        "allocation_method" => "across",
                        "value_type" => $value_type,
                        "value" => "-".$value,
                        "customer_selection" => "all",
                        "entitled_product_ids" => $product_ids,
                        "starts_at" => $start_date,
                        "ends_at" => $end_date
                    )
                );
            } else {
                $price_rule_json = array(
                    "price_rule" => array(
                        "title" => $discount_code,
                        "target_type" => "line_item",
                        "target_selection" => "entitled",
                        "allocation_method" => "across",
                        "value_type" => $value_type,
                        "value" => "-".$value,
                        "customer_selection" => "all",
                        "entitled_product_ids" => $product_ids,
                        "starts_at" => $start_date
                    )
                );
            }
            
        } else {

            if(!empty($end_date)){
                $price_rule_json = array(
                    "price_rule" => array(
                        "title" => $discount_code,
                        "target_type" => "line_item",
                        "target_selection" => "all",
                        "allocation_method" => "across",
                        "value_type" => $value_type,
                        "value" => "-".$value,
                        "customer_selection" => "all",
                        "starts_at" => $start_date,
                        "ends_at" => $end_date
                    )
                );
            } else {
                $price_rule_json = array(
                    "price_rule" => array(
                        "title" => $discount_code,
                        "target_type" => "line_item",
                        "target_selection" => "all",
                        "allocation_method" => "across",
                        "value_type" => $value_type,
                        "value" => "-".$value,
                        "customer_selection" => "all",
                        "starts_at" => $start_date
                    )
                );
            }
            
        }
        

        $price_rule_json = shopify_call($access_token, $shop_name, "/admin/price_rules.json", json_encode($price_rule_json), 'POST', array('Content-Type: application/json'));

        if(!empty($price_rule_json['response'])){

            $price_rule = json_decode($price_rule_json['response'], true);
            $price_rule_id = $price_rule['price_rule']['id'];
            $price_rule_title = $price_rule['price_rule']['title'];

            $discount_code_json = array(
                "discount_code" => array(
                    "code" => $price_rule_title
                )
            );

            // Create discount code
            shopify_call($access_token, $shop_name, "/admin/price_rules/".$price_rule_id."/discount_codes.json", json_encode($discount_code_json), 'POST', array('Content-Type: application/json'));

            return redirect('/discounts');
        }

        
    }

    public function getAllCollections(Request $request)
    {
        $shop = Auth::user();
        $shop_name = $shop->name;
        $access_token = $shop->password;

        $old_collection_ids = [];

        if(!empty($request->old_collection_ids)){

            $old_collection_ids_array = explode(",", $request->old_collection_ids);
            $old_collection_ids = $old_collection_ids_array;
        }
        

        $smart_response = shopify_call($access_token, $shop_name, "/admin/smart_collections.json", array(), 'GET');
        $custom_response = shopify_call($access_token, $shop_name, "/admin/custom_collections.json", array(), 'GET');

        if(!empty($smart_response['response']) && !empty($custom_response['response'])){
            $smart_collections = json_decode($smart_response['response'], true);
            $custom_collections = json_decode($custom_response['response'], true);

            $collections = array_merge($smart_collections['smart_collections'], $custom_collections['custom_collections']);

            return Response::json(\View::make('discounts.collections', ['collections' => $collections, 'old_collection_ids' => $old_collection_ids])->render(), );
        } else {
            return Response::json(\View::make('discounts.collections', ['collections' => []])->render(), );
        }

    }

    public function getAllProducts(Request $request)
    {
        $shop = Auth::user();
        $shop_name = $shop->name;
        $access_token = $shop->password;

        $old_product_ids = [];

        if(!empty($request->old_product_ids)){

            $old_product_ids_array = explode(",", $request->old_product_ids);
            $old_product_ids = $old_product_ids_array;
        }

        $product_response = shopify_call($access_token, $shop_name, "/admin/products.json", array(), 'GET');

        if(!empty($product_response['response'])){
            $products = json_decode($product_response['response'], true);

            return Response::json(\View::make('discounts.products', ['products' => $products['products'], 'old_product_ids' => $old_product_ids])->render(), );
        } else {
            return Response::json(\View::make('discounts.products', ['products' => []])->render(), );
        }
    }

    public function edit($id)
    {
        $shop = Auth::user();
        $shop_name = $shop->name;
        $access_token = $shop->password;

        $price_rule_id = $id;

        $price_rule_response = shopify_call($access_token, $shop_name, "/admin/price_rules/".$price_rule_id.".json", array(), 'GET');

        $price_rule = [];
        $default_discount_settings = '';

        if(!empty($price_rule_response['response'])){

            $default_discount_settings = Settings::where('meta_key', '=', 'default_discount')->first();

            $price_rule = json_decode($price_rule_response['response'], true);
            $collections = [];
            $products = [];

            if(!empty($price_rule['price_rule']['entitled_collection_ids'])){
                $collections_ids = $price_rule['price_rule']['entitled_collection_ids'];

                foreach($collections_ids as $collections_id) {
                    $collection_response = shopify_call($access_token, $shop_name, "/admin/api/2021-10/collections/".$collections_id.".json", array(), 'GET');

                    if(!empty($collection_response['response'])){
                        $collection_data = json_decode($collection_response['response'], true);

                        $collections[$collection_data['collection']['id']] = $collection_data['collection']['title'];
                    }
                }
            } else if(!empty($price_rule['price_rule']['entitled_product_ids'])){
                $products_ids = $price_rule['price_rule']['entitled_product_ids'];
                foreach($products_ids as $products_id) {
                    $product_response = shopify_call($access_token, $shop_name, "/admin/api/2021-10/products/".$products_id.".json", array(), 'GET');

                    if(!empty($product_response['response'])){
                        $product_data = json_decode($product_response['response'], true);

                        $products[$product_data['product']['id']] = $product_data['product']['title'];
                    }
                }
            }

            return view('discounts.edit', ['price_rule' => $price_rule['price_rule'], 'collections' => $collections, 'products' => $products, 'shop_name' => $shop_name, 'default_discount_settings' => $default_discount_settings]);
        } else {
            return view('discounts.edit', ['price_rule' => [], 'collections' => [], 'products' => [], 'shop_name' => $shop_name, 'default_discount_settings' => $default_discount_settings]);
        }
    }

    public function update(Request $request)
    {
        $shop = Auth::user();
        $shop_name = $shop->name;
        $access_token = $shop->password;

        $rule_id = $request->rule_id;
        $value_type = $request->value_type;
        $value = $request->value;
        $apply_to = $request->apply_to;
        $start_date = $request->start_date;
        $end_date = $request->end_date;

        $default_discount = $request->default_discount;

        $default_discount_settings = Settings::where('meta_key', '=', 'default_discount')->first();

        if($default_discount == 'yes') {
            if(!empty($default_discount_settings)){
                Settings::where('meta_key', '=', 'default_discount')->update(['meta_value' => $rule_id]);
            } else {
                $discount_setting = new Settings();
                $discount_setting->meta_key = 'default_discount';
                $discount_setting->meta_value = $rule_id;
                $discount_setting->save();
            }
        }


        if($apply_to == 'all_products'){

            if(!empty($end_date)){
                $update_price_rule_json = array(
                    "price_rule" => array(
                        "target_type" => "line_item",
                        "target_selection" => "all",
                        "allocation_method" => "across",
                        "value_type" => $value_type,
                        "value" => "-".$value,
                        "customer_selection" => "all",
                        "starts_at" => $start_date,
                        "ends_at" => $end_date,
                        'entitled_collection_ids' => [],
                        'entitled_product_ids' => []

                    )
                );
            } else {
                $update_price_rule_json = array(
                    "price_rule" => array(
                        "target_type" => "line_item",
                        "target_selection" => "all",
                        "allocation_method" => "across",
                        "value_type" => $value_type,
                        "value" => "-".$value,
                        "customer_selection" => "all",
                        "starts_at" => $start_date,
                        'entitled_collection_ids' => [],
                        'entitled_product_ids' => []
                    )
                );
            }
            
        } elseif($apply_to == 'specific_collections'){

            $collection_ids = [];
            $collections = $request->collections;

            foreach($collections as $collection) {
                $collection_ids[] = $collection;
            }

            if(!empty($end_date)){
                $update_price_rule_json = array(
                    "price_rule" => array(
                        "target_type" => "line_item",
                        "target_selection" => "entitled",
                        "allocation_method" => "across",
                        "value_type" => $value_type,
                        "value" => "-".$value,
                        "customer_selection" => "all",
                        "entitled_collection_ids" => $collection_ids,
                        "starts_at" => $start_date,
                        "ends_at" => $end_date,
                        'entitled_product_ids' => []
                    )
                );
            } else {
                $update_price_rule_json = array(
                    "price_rule" => array(
                        "target_type" => "line_item",
                        "target_selection" => "entitled",
                        "allocation_method" => "across",
                        "value_type" => $value_type,
                        "value" => "-".$value,
                        "customer_selection" => "all",
                        "entitled_collection_ids" => $collection_ids,
                        "starts_at" => $start_date,
                        'entitled_product_ids' => []
                    )
                );
            }
            
        } elseif($apply_to == 'specific_products'){

            $product_ids = [];
            $products = $request->products;

            foreach($products as $product) {
                $product_ids[] = $product;
            }

            if(!empty($end_date)){
                $update_price_rule_json = array(
                    "price_rule" => array(
                        "target_type" => "line_item",
                        "target_selection" => "entitled",
                        "allocation_method" => "across",
                        "value_type" => $value_type,
                        "value" => "-".$value,
                        "customer_selection" => "all",
                        "entitled_product_ids" => $product_ids,
                        "starts_at" => $start_date,
                        "ends_at" => $end_date,
                        'entitled_collection_ids' => []
                    )
                );
            } else {
                $update_price_rule_json = array(
                    "price_rule" => array(
                        "target_type" => "line_item",
                        "target_selection" => "entitled",
                        "allocation_method" => "across",
                        "value_type" => $value_type,
                        "value" => "-".$value,
                        "customer_selection" => "all",
                        "entitled_product_ids" => $product_ids,
                        "starts_at" => $start_date,
                        'entitled_collection_ids' => []
                    )
                );
            }
            
        } else {

            if(!empty($end_date)){
                $update_price_rule_json = array(
                    "price_rule" => array(
                        "target_type" => "line_item",
                        "target_selection" => "all",
                        "allocation_method" => "across",
                        "value_type" => $value_type,
                        "value" => "-".$value,
                        "customer_selection" => "all",
                        "starts_at" => $start_date,
                        "ends_at" => $end_date
                    )
                );
            } else {
                $update_price_rule_json = array(
                    "price_rule" => array(
                        "target_type" => "line_item",
                        "target_selection" => "all",
                        "allocation_method" => "across",
                        "value_type" => $value_type,
                        "value" => "-".$value,
                        "customer_selection" => "all",
                        "starts_at" => $start_date
                    )
                );
            }
            
        }

        // update price rule
        $price_rule_response = shopify_call($access_token, $shop_name, "/admin/price_rules/".$rule_id.".json", json_encode($update_price_rule_json), 'PUT', array('Content-Type: application/json'));

        if(!empty($price_rule_response['response'])){

            $price_rule = json_decode($price_rule_response['response'], true);
            
            if(!empty($price_rule['price_rule']['id'])){
                return redirect('/discounts');
            } else {
                return redirect('/discounts');
            }
        }

    }

    public function destroy(Request $request)
    {
        $shop = Auth::user();
        $shop_name = $shop->name;
        $access_token = $shop->password;

        $rule_id = $request->rule_id;

        shopify_call($access_token, $shop_name, "/admin/price_rules/".$rule_id.".json", array(), 'DELETE');

        return redirect('/discounts');
    }

    public function settings()
    {
        $shop = Auth::user();
        $shop_name = $shop->name;
        $shop_id = $shop->id;
            
        $discount_btn_settings = Settings::where('meta_key', '=', 'student_btn_text')->where('store_id', '=', $shop_id)->first();
        $success_txt_settings = Settings::where('meta_key', '=', 'success_txt')->where('store_id', '=', $shop_id)->first();
        $failure_txt_settings = Settings::where('meta_key', '=', 'failure_txt')->where('store_id', '=', $shop_id)->first();

        return view('settings', ['discount_btn_settings' => $discount_btn_settings, 'success_txt_settings' => $success_txt_settings, 'failure_txt_settings' => $failure_txt_settings]);
    }

    public function updateSettings(Request $request)
    {
        $shop = Auth::user();
        $shop_name = $shop->name;
        $shop_id = $shop->id;

        $student_btn_text = $request->student_btn_text;

        $discount_btn_settings = Settings::where('meta_key', '=', 'student_btn_text')->where('store_id', '=', $shop_id)->first();
        $success_txt_settings = Settings::where('meta_key', '=', 'success_txt')->where('store_id', '=', $shop_id)->first();
        $failure_txt_settings = Settings::where('meta_key', '=', 'failure_txt')->where('store_id', '=', $shop_id)->first();
        
        if(isset($discount_btn_settings) && !empty($discount_btn_settings)){

            $discount_btn_settings->meta_value = $student_btn_text;
            $discount_btn_settings->save();

            \Session::flash('message','Button text update successfully!'); 
            \Session::flash('alert-class','alert-success'); 

            //return redirect('/settings');
            return view('settings', ['discount_btn_settings' => $discount_btn_settings, 'success_txt_settings' => $success_txt_settings, 'failure_txt_settings' => $failure_txt_settings]);
        }
    }

    public function updateSuccessText(Request $request)
    {
        $shop = Auth::user();
        $shop_name = $shop->name;
        $shop_id = $shop->id;

        $success_txt = $request->success_txt;

        $discount_btn_settings = Settings::where('meta_key', '=', 'student_btn_text')->where('store_id', '=', $shop_id)->first();
        $success_txt_settings = Settings::where('meta_key', '=', 'success_txt')->where('store_id', '=', $shop_id)->first();
        $failure_txt_settings = Settings::where('meta_key', '=', 'failure_txt')->where('store_id', '=', $shop_id)->first();
        
        if(isset($success_txt_settings) && !empty($success_txt_settings)){

            $success_txt_settings->meta_value = $success_txt;
            $success_txt_settings->save();

            \Session::flash('message','Success text update successfully!'); 
            \Session::flash('alert-class','alert-success'); 

            //return redirect('/settings');
            return view('settings', ['discount_btn_settings' => $discount_btn_settings, 'success_txt_settings' => $success_txt_settings, 'failure_txt_settings' => $failure_txt_settings]);
        }
    }

    public function updateFailureText(Request $request)
    {
        $shop = Auth::user();
        $shop_name = $shop->name;
        $shop_id = $shop->id;

        $failure_txt = $request->failure_txt;

        $discount_btn_settings = Settings::where('meta_key', '=', 'student_btn_text')->where('store_id', '=', $shop_id)->first();
        $success_txt_settings = Settings::where('meta_key', '=', 'success_txt')->where('store_id', '=', $shop_id)->first();
        $failure_txt_settings = Settings::where('meta_key', '=', 'failure_txt')->where('store_id', '=', $shop_id)->first();
        
        if(isset($failure_txt_settings) && !empty($failure_txt_settings)){

            $failure_txt_settings->meta_value = $failure_txt;
            $failure_txt_settings->save();

            \Session::flash('message','Failure text update successfully!'); 
            \Session::flash('alert-class','alert-success'); 

            //return redirect('/settings');
            return view('settings', ['discount_btn_settings' => $discount_btn_settings, 'success_txt_settings' => $success_txt_settings, 'failure_txt_settings' => $failure_txt_settings]);
        }
    }

    public function showVerifyButton(Request $request)
    {
        $shop_url = $request->shop_url;
        
        $shop = User::where('name', '=', $shop_url)->first();
        $shop_name = $shop->name;
        $shop_id = $shop->id;

        $setting = Settings::where('meta_key', '=', 'student_btn_text')->where('store_id', '=', $shop_id)->first();
        //$shop = Auth::user();
        

        if(!empty($setting)){
            $shop_cart_url = 'https://'.$request->shop_url.'/apps/studentenrabatt/verify-student';
            return Response::json(\View::make('verify-button', ['setting' => $setting, 'shop' => $shop_cart_url])->render() );
        } else {
            return Response::json(\View::make('verify-button', ['setting' => []])->render(), );
        }
    }

    public function verifyStudent($verification)
    {
        return view('verify-student', ['identifier' => $_GET['identifier'] ]);
        //return view('verify-student');
    }

    public function getDiscountCode(Request $request)
    {
        //var_dump($request);die;
        //$shop = Auth::user();
        //$shop_name = $shop->name;
        //$access_token = $shop->password;

        $cart_item_arr = $request->cart_url_array;

        $cart_data_arr = json_decode($cart_item_arr, true);
        
        $shop = User::where('name', '=', $cart_data_arr['store_id'])->first();
        $shop_name = $shop->name;
        $access_token = $shop->password;
        $shop_id = $shop->id;

        // Success text
        $success_txt_settings = Settings::where('meta_key', '=', 'success_txt')->where('store_id', '=', $shop_id)->first();
        $success_txt = $success_txt_settings->meta_value;

        // Failure txt
        $failure_txt_settings = Settings::where('meta_key', '=', 'failure_txt')->where('store_id', '=', $shop_id)->first();
        $failure_txt = $failure_txt_settings->meta_value;

        //Default Discount
        $default_discount = '';
        $default_discount_settings = Settings::where('meta_key', '=', 'default_discount')->where('store_id', '=', $shop_id)->first();
        if(isset($default_discount_settings) && !empty($default_discount_settings)){
            $default_discount = $default_discount_settings->meta_value;
        }
        

        $cart_items = $cart_data_arr['cart']['items'];

        if(!empty($cart_items) && count($cart_items) > 0){

            $product_id_arr = [];

            foreach($cart_items as $cart_item){
                $variant_id = $cart_item['variant_id'];

                $product_id = $cart_item['product_id'];
                $product_id_arr[] = $product_id;
            }

            $discount_response = shopify_call($access_token, $shop_name, "/admin/price_rules.json", array(), 'GET');


            $discount_flag = false;
            
            

            if(!empty($discount_response['response'])){
                $discounts = json_decode($discount_response['response'], true);
                $price_rules_arr = $discounts['price_rules'];

                if(!empty($price_rules_arr) && count($price_rules_arr) > 0){
                    foreach($price_rules_arr as $price_rule){
                        $price_rule_id = $price_rule['id'];

                        if ($default_discount == $price_rule_id) {
                            $start_date = strtotime($price_rule['starts_at']);
                            $end_date = strtotime($price_rule['ends_at']);
                            $today_date = time();

                            $discount_flag = true;

                            if (!empty($end_date) && ($end_date != false) && ($today_date < $end_date)) {
                                if ($today_date > $start_date) {
                                    if ($price_rule['target_selection'] == 'all') {
                                        // return discount code
                                        $discount_code_response = shopify_call($access_token, $shop_name, "/admin/price_rules/".$price_rule_id."/discount_codes.json", array(), 'GET');

                                        if (!empty($discount_code_response['response'])) {
                                            $discounts = json_decode($discount_code_response['response'], true);
                                            $discount_arr = $discounts['discount_codes'];

                                            $discount_code = $discount_arr[0]['code'];
                                            
                                            echo json_encode(array('status' => true, 'discount_code' => $discount_code, 'success_txt' => $success_txt));
                                            die;
                                        } else {
                                            echo json_encode(array('status' => false, 'error' => 'Sorry we do not offer a Student discount at the moment')); die;
                                        }
                                    } elseif ($price_rule['target_selection'] == 'entitled') {
                                        $product_ids = $price_rule['entitled_product_ids'];
                                        $collection_ids = $price_rule['entitled_collection_ids'];

                                        if (!empty($product_ids) && count($product_ids) > 0) {
                                            foreach ($product_ids as $product_id) {
                                                if (in_array($product_id, $product_id_arr)) {
                                                    // return discount code
                                                    $discount_code_response = shopify_call($access_token, $shop_name, "/admin/price_rules/".$price_rule_id."/discount_codes.json", array(), 'GET');

                                                    if (!empty($discount_code_response['response'])) {
                                                        $discounts = json_decode($discount_code_response['response'], true);
                                                        $discount_arr = $discounts['discount_codes'];

                                                        $discount_code = $discount_arr[0]['code'];
                                                        
                                                        echo json_encode(array('status' => true, 'discount_code' => $discount_code, 'success_txt' => $success_txt));
                                                        die;
                                                    } else {
                                                        echo json_encode(array('status' => false, 'error' => 'Sorry we do not offer a Student discount at the moment')); die;
                                                    }
                                                }
                                            }
                                        } elseif (!empty($collection_ids) && count($collection_ids) > 0) {
                                            foreach ($collection_ids as $collection_id) {
                                                $collection_product_response = shopify_call($access_token, $shop_name, "/admin/collections/".$collection_id."/products.json", array(), 'GET');

                                                if (!empty($collection_product_response['response'])) {
                                                    $products = json_decode($collection_product_response['response'], true);
                                                    $product_arr = $products['products'];

                                                    foreach ($product_arr as $product) {
                                                        if (in_array($product['id'], $product_id_arr)) {
                                                            // return discount code

                                                            $discount_code_response = shopify_call($access_token, $shop_name, "/admin/price_rules/".$price_rule_id."/discount_codes.json", array(), 'GET');

                                                            if (!empty($discount_code_response['response'])) {
                                                                $discounts = json_decode($discount_code_response['response'], true);
                                                                $discount_arr = $discounts['discount_codes'];

                                                                $discount_code = $discount_arr[0]['code'];
                                                                
                                                                echo json_encode(array('status' => true, 'discount_code' => $discount_code, 'success_txt' => $success_txt));
                                                                die;
                                                            } else {
                                                                echo json_encode(array('status' => false, 'error' => 'Sorry we do not offer a Student discount at the moment')); die;
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        } else {
                                        }
                                    } else {
                                        // else
                                    }
                                }
                            } elseif (($end_date == false) && ($today_date > $start_date)) {
                                if ($price_rule['target_selection'] == 'all') {
                                    // return discount code
                                    $discount_code_response = shopify_call($access_token, $shop_name, "/admin/price_rules/".$price_rule_id."/discount_codes.json", array(), 'GET');

                                    if (!empty($discount_code_response['response'])) {
                                        $discounts = json_decode($discount_code_response['response'], true);
                                        $discount_arr = $discounts['discount_codes'];

                                        $discount_code = $discount_arr[0]['code'];
                                        
                                        echo json_encode(array('status' => true, 'discount_code' => $discount_code, 'success_txt' => $success_txt));
                                        die;
                                    } else {
                                        echo json_encode(array('status' => false, 'error' => 'Sorry we do not offer a Student discount at the moment')); die;
                                    }
                                } elseif ($price_rule['target_selection'] == 'entitled') {
                                    $product_ids = $price_rule['entitled_product_ids'];
                                    $collection_ids = $price_rule['entitled_collection_ids'];

                                    if (!empty($product_ids) && count($product_ids) > 0) {
                                        foreach ($product_ids as $product_id) {
                                            if (in_array($product_id, $product_id_arr)) {
                                                // return discount code
                                                $discount_code_response = shopify_call($access_token, $shop_name, "/admin/price_rules/".$price_rule_id."/discount_codes.json", array(), 'GET');

                                                if (!empty($discount_code_response['response'])) {
                                                    $discounts = json_decode($discount_code_response['response'], true);
                                                    $discount_arr = $discounts['discount_codes'];

                                                    $discount_code = $discount_arr[0]['code'];
                                                    
                                                    echo json_encode(array('status' => true, 'discount_code' => $discount_code, 'success_txt' => $success_txt));
                                                    die;
                                                } else {
                                                    echo json_encode(array('status' => false, 'error' => 'Sorry we do not offer a Student discount at the moment')); die;
                                                }
                                            }
                                        }
                                    } elseif (!empty($collection_ids) && count($collection_ids) > 0) {
                                        foreach ($collection_ids as $collection_id) {
                                            $collection_product_response = shopify_call($access_token, $shop_name, "/admin/collections/".$collection_id."/products.json", array(), 'GET');

                                            if (!empty($collection_product_response['response'])) {
                                                $products = json_decode($collection_product_response['response'], true);
                                                $product_arr = $products['products'];

                                                foreach ($product_arr as $product) {
                                                    if (in_array($product['id'], $product_id_arr)) {
                                                        // return discount code

                                                        $discount_code_response = shopify_call($access_token, $shop_name, "/admin/price_rules/".$price_rule_id."/discount_codes.json", array(), 'GET');

                                                        if (!empty($discount_code_response['response'])) {
                                                            $discounts = json_decode($discount_code_response['response'], true);
                                                            $discount_arr = $discounts['discount_codes'];

                                                            $discount_code = $discount_arr[0]['code'];
                                                            
                                                            echo json_encode(array('status' => true, 'discount_code' => $discount_code, 'success_txt' => $success_txt));
                                                            die;
                                                        } else {
                                                            echo json_encode(array('status' => false, 'error' => 'Sorry we do not offer a Student discount at the moment')); die;
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    } else {
                                        echo json_encode(array('status' => false, 'error' => 'Sorry we do not offer a Student discount at the moment')); die;
                                    }
                                } else {
                                    echo json_encode(array('status' => false, 'error' => 'Sorry we do not offer a Student discount at the moment')); die;
                                }
                            } else {
                                // else
                                echo json_encode(array('status' => false, 'error' => $failure_txt));
                                die;
                            }
                        }
                    }

                    if(!$discount_flag) {
                        echo json_encode(array('status' => false, 'error' => 'Sorry we do not offer a Student discount at the moment')); die;
                    }
                }

            } else {
                echo json_encode(array('status' => false, 'error' => 'Sorry we do not offer a Student discount at the moment')); die;
            }
        } else {
            echo json_encode(array('status' => false, 'error' => 'Sorry we do not offer a Student discount at the moment')); die;
        }
    }

}

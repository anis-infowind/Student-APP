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

class DiscountCodeController extends Controller
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

                $discount_response = shopify_call($access_token, $shop_name, "/admin/price_rules.json", array(), 'GET');

                if(!empty($discount_response['response'])){
                    $discounts = json_decode($discount_response['response'], true);

                    return view('discounts.discount', ['discounts' => $discounts]);
                } else {
                    return view('discounts.discount', ['discounts' => []]);
                } 
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
        $collection_ids = $request->collection_ids;
        $product_ids = $request->product_ids;
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

        if(!empty($price_rule_response['response'])){

            $price_rule = json_decode($price_rule_response['response'], true);
            $collections = [];
            $products = [];

            if(!empty($price_rule['price_rule']['entitled_collection_ids'])){
                $smart_response = shopify_call($access_token, $shop_name, "/admin/smart_collections.json", array(), 'GET');
                $custom_response = shopify_call($access_token, $shop_name, "/admin/custom_collections.json", array(), 'GET');

                if(!empty($smart_response['response']) && !empty($custom_response['response'])){
                    $smart_collections = json_decode($smart_response['response'], true);
                    $custom_collections = json_decode($custom_response['response'], true);

                    $collections = array_merge($smart_collections['smart_collections'], $custom_collections['custom_collections']);


                }
            } else if(!empty($price_rule['price_rule']['entitled_product_ids'])){
                $product_response = shopify_call($access_token, $shop_name, "/admin/products.json", array(), 'GET');

                if(!empty($product_response['response'])){
                    $product_array = json_decode($product_response['response'], true);
                    $products = $product_array['products'];
                }
            }

            return view('discounts.edit', ['price_rule' => $price_rule['price_rule'], 'collections' => $collections, 'products' => $products]);
        } else {
            return view('discounts.edit', ['price_rule' => [], 'collections' => [], 'products' => []]);
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
        $collection_ids = $request->collection_ids;
        $old_collection_ids = $request->old_collection_ids;
        $product_ids = $request->product_ids;
        $old_product_ids = $request->old_product_ids;
        $start_date = $request->start_date;
        $end_date = $request->end_date;


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
            
        } elseif($apply_to == 'specific_collections'){

            $new_collection_ids = [];

            if(!empty($old_collection_ids) && count($old_collection_ids) > 0){
                $new_collection_ids = $old_collection_ids;
            } elseif(!empty($collection_ids) && count($collection_ids) > 0){
                $new_collection_ids = $collection_ids;
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
                        "entitled_collection_ids" => $new_collection_ids,
                        "starts_at" => $start_date,
                        "ends_at" => $end_date
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
                        "entitled_collection_ids" => $new_collection_ids,
                        "starts_at" => $start_date
                    )
                );
            }
            
        } elseif($apply_to == 'specific_products'){

            $new_product_ids = [];

            if(!empty($old_product_ids) && count($old_product_ids) > 0){
                $new_product_ids = $old_product_ids;
            } elseif(!empty($product_ids) && count($product_ids) > 0){
                $new_product_ids = $product_ids;
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
                        "entitled_product_ids" => $new_product_ids,
                        "starts_at" => $start_date,
                        "ends_at" => $end_date
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
                        "entitled_product_ids" => $new_product_ids,
                        "starts_at" => $start_date
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

        echo "test";
        echo "<pre>";
        print_r($update_price_rule_json);die;

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
        $settings = Settings::where('meta_key', '=', 'student_btn_text')->first();

        return view('settings', ['settings' => $settings]);
    }

    public function updateSettings(Request $request)
    {
        $student_btn_text = $request->student_btn_text;

        $settings = Settings::where('meta_key', '=', 'student_btn_text')->first();
        
        if(isset($settings) && !empty($settings)){

            $settings->meta_value = $student_btn_text;
            $settings->save();

            \Session::flash('message','Button text update successfully!'); 
            \Session::flash('alert-class','alert-success'); 

            //return redirect('/settings');
            return view('settings', ['settings' => $settings]);
        }
    }

}

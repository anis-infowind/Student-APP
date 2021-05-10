<?php

use DB;

function checkProductOption($product_id,$option_id)
{
	$product_options = DB::table('add_options_product')->where('product_id',$product_id)->where('option_id',$option_id)->first();

	return !empty($product_options) ? true : false;
}

?>
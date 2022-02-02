@extends('layouts.default')



@section('title', 'Edit Discount')

@section('content')

    <div class="container second">

        <div class="row">

            <a class="btn btn-primary add-discount" href="{{url('/discounts')}}"><i class="fa fa-arrow-left"></i> Back</a>

        </div>

        <br />

        @if(isset($price_rule['id']) && !empty($price_rule['id']))

        <div class="row">

            <form class="form-horizontal submit-discount-form" action="{{url('/discount/update')}}" method="post">

                <input type="hidden" name="rule_id" value="@if(isset($price_rule['id']) && !empty($price_rule['id'])){{$price_rule['id']}}@endif">

                <div class="form-group">

                    <label class="control-label col-sm-2" for="discount_code">Discount code <span style="color:red;">*</span></label>

                    <div class="col-sm-10">

                        <input type="text" name="discount_code" id="discount_code" class="form-control" value="@if(isset($price_rule['title']) && !empty($price_rule['title'])){{$price_rule['title']}}@endif" @if(isset($price_rule['title']) && !empty($price_rule['title'])){{'disabled'}}@endif required>

                    </div>

                </div>

                <div class="form-group">

                    <label class="control-label col-sm-2" for="value_type">Discount Type</label>

                    <div class="col-sm-10"> 

                        <select name="value_type" class="form-control" id="value_type"> 

                            <option value="percentage">Percentage</option>

                        </select> 

                    </div>

                </div>

                <div class="form-group"> 

                    <label class="control-label col-sm-2" for="discount_value">Discount Value <span style="color:red;">*</span></label>

                    <div class="col-sm-10">

                        <input type="number" min="1" max="100" name="value" id="discount_value" class="form-control" value="@if(isset($price_rule['value']) && !empty($price_rule['value'])){{str_replace('-','',$price_rule['value'])}}@endif" required>

                    </div>

                </div>

                <div class="form-group">

                    <label class="control-label col-sm-2" for="apply_to">Applies To</label>

                    <div class="col-sm-10"> 

                        <select name="apply_to" class="form-control applies_to" id="apply_to">

                            <option value="all_products" @if(!empty($price_rule['target_selection']) && $price_rule['target_selection'] == 'all'){{'selected'}}@endif>All products</option>

                            <option value="specific_collections" data-collection-ids="@if(!empty($price_rule['target_selection']) && count($price_rule['entitled_collection_ids']) > 0){{implode(',',$price_rule['entitled_collection_ids'])}}@endif" @if(!empty($price_rule['target_selection']) && count($price_rule['entitled_collection_ids']) > 0){{'selected'}}@endif>Specific collections</option>

                            <option value="specific_products" data-product-ids="@if(!empty($price_rule['target_selection']) && count($price_rule['entitled_product_ids']) > 0){{implode(',',$price_rule['entitled_product_ids'])}}@endif" @if(!empty($price_rule['target_selection']) && count($price_rule['entitled_product_ids']) > 0){{'selected'}}@endif>Specific products</option>

                        </select>

                        <div class="collection-product-list"> 
                        </div>

                    </div>

                </div>

                <div class="product-filters-block form-group" style="@if(isset($products) && !empty($products)){{ 'display:block' }}@endif">
                    <label class="control-label col-sm-2" for="">&nbsp;</label>
                    <div class="col-sm-10">
                        <div class="product_filters">
                            <button type="button" class="studentdis-btn studentdis-btn-primary form" id="apply_product_filter">Select Products</button>
                            <div class="right_table">
                                <div style="display: flex;align-items: center;justify-content: space-between;">
                                    <label>Selected products</label>
                                    <ul class="product_operations">
                                        <li><button type="button" class="studentdis-btn studentdis-btn-monochrome remove-all-products">Remove all</button></li>
                                    </ul>
                                </div>
                                <div class="selected-products-section">
                                    <table class="table selected_objects selected_products dataTable">
                                        <thead>
                                            <tr>
                                                <th class="col-md-10">Product name</th>
                                                <th class="col-md-2"><span class="translation_missing" title="">Action</span></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @if(isset($products) && !empty($products))
                                        @foreach($products as $product_key => $product_title)
                                        <tr class="{{ $product_key }}"><td>{{ $product_title }} <a href="https://{{ $shop_name }}/admin/products/{{ $product_key }}" target="_blank">View in store</a></td><td><a href="javascript:void(0)" class="table-action-btn btn-small btn btn-small btn-danger product-remove" data-pid="{{ $product_key }}"><i class="fa fa-trash" aria-hidden="true"></i></a></td></tr>
                                        @endforeach
                                        @else
                                            <tr class="no_products_selected">
                                                <td>No products selected</td><td></td>
                                            </tr>
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="selected_objects_count selected_products_count">Showing {{ count($products) }} entries</div>
                        </div>
                    </div>
                </div>

                <div class="collection-filters-block form-group" style="@if(isset($collections) && !empty($collections)){{ 'display:block' }}@endif">
                    <label class="control-label col-sm-2" for="">&nbsp;</label>
                    <div class="col-sm-10">
                        <div class="collection_filters">
                            <button type="button" class="studentdis-btn studentdis-btn-primary form" id="apply_collection_filter">Select Collections</button>
                            <div class="right_table">
                                <div style="display: flex;align-items: center;justify-content: space-between;">
                                    <label>Selected collections</label>
                                    <ul class="collection_operations">
                                        <li><button type="button" class="studentdis-btn studentdis-btn-monochrome remove-all-collections">Remove all</button></li>
                                    </ul>
                                </div>
                                <div class="selected-collections-section">
                                    <table class="table selected_objects selected_collections dataTable">
                                        <thead>
                                            <tr>
                                                <th class="col-md-10">Collection name</th>
                                                <th class="col-md-2"><span class="translation_missing" title="">Action</span></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @if(isset($collections) && !empty($collections))
                                        @foreach($collections as $collection_key => $collection_title)
                                        <tr class="{{ $collection_key }}"><td>{{ $collection_title }} <a href="https://{{ $shop_name }}/admin/collections/{{ $collection_key }}" target="_blank">View in store</a></td><td><a href="javascript:void(0)" class="table-action-btn btn-small btn btn-small btn-danger collection-remove" data-cid="{{ $collection_key }}"><i class="fa fa-trash" aria-hidden="true"></i></a></td></tr>
                                        @endforeach
                                        @else
                                        <tr class="no_collections_selected">
                                                <td>No collections selected</td><td></td>
                                            </tr>
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="selected_objects_count selected_collections_count">Showing {{ count($collections) }} entries</div>
                        </div>
                    </div>
                </div>

                <div class="form-group">

                    <label class="control-label col-sm-2" for="pwd">Start date <span style="color:red;">*</span></label>

                    <div class="col-sm-5"> 

                        <input type="datetime-local" name="start_date" class="form-control" value="@if(isset($price_rule['starts_at']) && !empty($price_rule['starts_at'])){{date('Y-m-d\TH:i', strtotime($price_rule['starts_at']))}}@endif" required>

                    </div>

                </div>

                <div class="form-group">

                    <label class="control-label col-sm-2" for="pwd">End date</label>

                    <div class="col-sm-5">

                        <input type="datetime-local" name="end_date" value="@if(isset($price_rule['ends_at']) && !empty($price_rule['ends_at'])){{date('Y-m-d\TH:i', strtotime($price_rule['ends_at']))}}@endif" class="form-control">

                    </div>

                </div>

                <div class="form-group">

                    <label class="control-label col-sm-2" for="pwd">&nbsp;</label>

                    <div class="col-sm-5">

                    <div class="checkbox">
                        <label><input type="checkbox" <?php if( (isset($default_discount_settings->meta_value)) && ($default_discount_settings->meta_value == $price_rule['id'])){ echo "checked"; }?> value="yes" name="default_discount" id="default_discount">Use this as student discount</label>
                    </div>

                    </div>

                </div>

                <div class="form-group">

                    <div class="col-sm-offset-2 col-sm-10">
                        <input type="hidden" name="" id="products_id" value="">
                        <input type="hidden" name="" id="collections_id" value="">
                        @if(isset($products) && !empty($products))
                            @if(count($products) > 0)
                            @foreach($products as $product_key => $product_title)
                        <input class="products {{$product_key}}" type="hidden" name="products[{{$product_key}}]" value="{{$product_key}}">
                            @endforeach
                            @endif
                        @endif

                        @if(isset($collections) && !empty($collections))
                            @if(count($collections) > 0)
                            @foreach($collections as $collection_key => $collection_title)
                        <input class="collections {{$collection_key}}" type="hidden" name="collections[{{$collection_key}}]" value="{{$collection_key}}">
                            @endforeach
                            @endif
                        @endif
                        <button type="submit" class="btn btn-primary add-discount add-discount-btn">Save</button>

                    </div>

                </div>

            </form>

        </div>

        @endif

    </div>

@endsection



@section('scripts')

    @parent



    <script type="text/javascript">

        var AppBridge = window['app-bridge'];

        var actions = AppBridge.actions;

        var TitleBar = actions.TitleBar;

        var Button = actions.Button;

        var Redirect = actions.Redirect;

        var titleBarOptions = {

            title: 'Edit Discount',

        };

        var myTitleBar = TitleBar.create(app, titleBarOptions);



    </script>
    

@endsection
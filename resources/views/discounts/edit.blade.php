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

            <form class="form-horizontal" action="{{url('/discount/update')}}" method="post">

                <input type="hidden" name="rule_id" value="@if(isset($price_rule['id']) && !empty($price_rule['id'])){{$price_rule['id']}}@endif">

                <div class="form-group">

                    <label class="control-label col-sm-2" for="email">Discount code <span style="color:red;">*</span></label>

                    <div class="col-sm-10">

                        <input type="text" name="discount_code" class="form-control" value="@if(isset($price_rule['title']) && !empty($price_rule['title'])){{$price_rule['title']}}@endif" @if(isset($price_rule['title']) && !empty($price_rule['title'])){{'disabled'}}@endif required>

                    </div>

                </div>

                <div class="form-group">

                    <label class="control-label col-sm-2" for="pwd">Discount Type</label>

                    <div class="col-sm-10"> 

                        <select name="value_type" class="form-control"> 

                            <option value="percentage">Percentage</option>

                        </select> 

                    </div>

                </div>

                <div class="form-group"> 

                    <label class="control-label col-sm-2" for="pwd">Discount Value <span style="color:red;">*</span></label>

                    <div class="col-sm-10">

                        <input type="number" min="1" max="100" name="value" class="form-control" value="@if(isset($price_rule['value']) && !empty($price_rule['value'])){{str_replace('-','',$price_rule['value'])}}@endif" required>

                    </div>

                </div>

                <div class="form-group">

                    <label class="control-label col-sm-2" for="pwd">Applies To</label>

                    <div class="col-sm-10"> 

                        <select name="apply_to" class="form-control applies_to">

                            <option value="all_products" @if(!empty($price_rule['target_selection']) && $price_rule['target_selection'] == 'all'){{'selected'}}@endif>All products</option>

                            <option value="specific_collections" data-collection-ids="@if(!empty($price_rule['target_selection']) && count($price_rule['entitled_collection_ids']) > 0){{implode(',',$price_rule['entitled_collection_ids'])}}@endif" @if(!empty($price_rule['target_selection']) && count($price_rule['entitled_collection_ids']) > 0){{'selected'}}@endif>Specific collections</option>

                            <option value="specific_products" data-product-ids="@if(!empty($price_rule['target_selection']) && count($price_rule['entitled_product_ids']) > 0){{implode(',',$price_rule['entitled_product_ids'])}}@endif" @if(!empty($price_rule['target_selection']) && count($price_rule['entitled_product_ids']) > 0){{'selected'}}@endif>Specific products</option>

                        </select>

                        <div class="collection-product-list"> 

                            @if(isset($collections) && !empty($collections))

                            @if(count($collections) > 0)

                            <ul>

                            @foreach($collections as $collection)

                            @if(in_array($collection['id'], $price_rule['entitled_collection_ids']))

                                <li>

                                    <input type="hidden" name="old_collection_ids[]" value="{{$collection['id']}}">

                                    <span>{{$collection['title']}}</span>

                                    <span class="remove_collection">×</span> 

                                </li>

                            @endif

                            @endforeach

                            </ul>

                            @endif

                            @endif



                            @if(isset($products) && !empty($products))

                            @if(count($products) > 0)

                            <ul>

                            @foreach($products as $product)

                            @if(in_array($product['id'], $price_rule['entitled_product_ids']))

                                <li>

                                    <input type="hidden" name="old_product_ids[]" value="{{$product['id']}}">

                                    <span>{{$product['title']}}</span>

                                    <span class="remove_product">×</span> 

                                </li>

                            @endif

                            @endforeach

                            </ul>

                            @endif

                            @endif

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

                    <div class="col-sm-offset-2 col-sm-10">

                        <button type="submit" class="btn btn-primary add-discount">Submit</button>

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
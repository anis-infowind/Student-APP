@extends('layouts.default')



@section('title', 'Add Discount')

@section('content')

    <div class="container second"> 

        <div class="row">

            <a class="btn btn-primary add-discount" href="{{url('/discounts')}}"><i class="fa fa-arrow-left"></i> Back</a>

        </div>

        <br />

        <div class="row">

            <form class="form-horizontal submit-discount-form" action="{{url('/discount/store')}}" method="post">

                <div class="form-group"> 

                    <label class="control-label col-sm-2" for="discount_code">Discount code <span style="color:red;">*</span></label>

                    <div class="col-sm-10">

                        <input type="text" name="discount_code" class="form-control" placeholder="e.g. SPRINGSALE" id="discount_code" required>

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

                        <input type="number" min="1" max="100" name="value" id="discount_value" class="form-control" required>

                    </div>

                </div>

                <div class="form-group">

                    <label class="control-label col-sm-2" for="apply_to">Applies To</label>

                    <div class="col-sm-10">

                        <select name="apply_to" class="form-control applies_to" id="apply_to">

                            <option value="all_products">All products</option>

                            <option value="specific_collections">Specific collections</option>

                            <option value="specific_products">Specific products</option>

                        </select>

                        <div class="collection-product-list hide">

                        </div>

                    </div>

                </div>
                
                <div class="product-filters-block form-group">
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
                                            <tr class="no_products_selected">
                                                <td>No products selected</td><td></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="selected_objects_count selected_products_count">Showing 0 entries</div>
                        </div>
                    </div>
                </div>

                <div class="collection-filters-block form-group">
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
                                            <tr class="no_collections_selected">
                                                <td>No collections selected</td><td></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="selected_objects_count selected_collections_count">Showing 0 entries</div>
                        </div>
                    </div>
                </div>

                <div class="form-group">

                    <label class="control-label col-sm-2" for="pwd">Start date <span style="color:red;">*</span></label>

                    <div class="col-sm-5">

                        <input type="datetime-local" name="start_date" class="form-control" required>

                    </div>

                </div>

                <div class="form-group">

                    <label class="control-label col-sm-2" for="pwd">End date</label>

                    <div class="col-sm-5"> 

                        <input type="datetime-local" name="end_date" class="form-control">

                    </div>

                </div> 

                <div class="form-group">

                    <div class="col-sm-offset-2 col-sm-10"> 
                    <input type="hidden" name="" id="products_id" value="">
                    <input type="hidden" name="" id="collections_id" value="">
                    <button type="submit" class="btn btn-primary add-discount add-discount-btn">Add</button>

                    </div>

                </div>

            </form>

        </div>

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

            title: 'Discounts',

        };

        var myTitleBar = TitleBar.create(app, titleBarOptions);



    </script>

@endsection
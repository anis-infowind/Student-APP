@extends('layouts.default')



@section('title', 'Add Discount')

@section('content')

    <div class="container second"> 

        <div class="row">

            <a class="btn btn-primary add-discount" href="{{url('/discounts')}}"><i class="fa fa-arrow-left"></i> Back</a>

        </div>

        <br />

        <div class="row">

            <form class="form-horizontal" action="{{url('/discount/store')}}" method="post">

                <div class="form-group"> 

                    <label class="control-label col-sm-2" for="email">Discount code <span style="color:red;">*</span></label>

                    <div class="col-sm-10">

                        <input type="text" name="discount_code" class="form-control" placeholder="e.g. SPRINGSALE" id="field1" required>

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

                        <input type="number" min="1" max="100" name="value" class="form-control" required>

                    </div>

                </div>

                <div class="form-group">

                    <label class="control-label col-sm-2" for="pwd">Applies To</label>

                    <div class="col-sm-10">

                        <select name="apply_to" class="form-control applies_to">

                            <option value="all_products">All products</option>

                            <option value="specific_collections">Specific collections</option>

                            <option value="specific_products">Specific products</option>

                        </select>

                        <div class="collection-product-list hide">

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

                    <button type="submit" class="btn btn-primary add-discount">Add</button>

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
@extends('layouts.default')


@section('title', 'Discounts')

@section('content')
    <div class="container second">
        <div class="row">
            <div class="col-sm-12">
                @if(Session::has('message'))
                <p class="alert {{ Session::get('alert-class', 'alert-info') }} alert-dismissable"><button type="button" class="close" data-dismiss="alert">×</button>{{Session::get('message') }}</p>
                @endif
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4 st-app"> 
                @if(isset($discount_btn_settings) && !empty($discount_btn_settings))
                <form method="post" action="{{url('settings/update')}}" class="form-horizontal">
                    <div class="form-group"> 
                        <label class="control-label col-sm-12" for="email">Button text <span style="color:red;">*</span></label> 
                        <div class="col-sm-12">  
                            <input type="text" name="student_btn_text" class="form-control" placehoder="Enter button text." value="{{$discount_btn_settings->meta_value}}" required>
                        </div>
                    </div>
                    <div class="form-group"> 
                        <div class="col-sm-10">
                        <button type="submit" class="btn btn-primary add-discount">Save</button> 
                        </div>
                    </div>
                </form>
                @endif
            </div> 
            <div class="col-sm-4 st-app">
                @if(isset($success_txt_settings) && !empty($success_txt_settings))
                <form method="post" action="{{url('settings/success-text')}}" class="form-horizontal">
                    <div class="form-group"> 
                        <label class="control-label col-sm-12" for="email">Success text <span style="color:red;">*</span></label> 
                        <div class="col-sm-12">  
                            <input type="text" name="success_txt" class="form-control" placehoder="Enter button text." value="{{$success_txt_settings->meta_value}}" required>
                        </div>
                    </div>
                    <div class="form-group"> 
                        <div class="col-sm-10">
                        <button type="submit" class="btn btn-primary add-discount">Save</button> 
                        </div>
                    </div>
                </form> 
                @endif
            </div>
            <div class="col-sm-4 st-app">
                @if(isset($failure_txt_settings) && !empty($failure_txt_settings))
                <form method="post" action="{{url('settings/failure-text')}}" class="form-horizontal">
                    <div class="form-group"> 
                        <label class="control-label col-sm-12" for="email">Failure text <span style="color:red;">*</span></label> 
                        <div class="col-sm-12">  
                            <input type="text" name="failure_txt" class="form-control" placehoder="Enter button text." value="{{$failure_txt_settings->meta_value}}" required>
                        </div>
                    </div> 
                    <div class="form-group">
                        <div class="col-sm-10">
                        <button type="submit" class="btn btn-primary add-discount">Save</button> 
                        </div>
                    </div>
                </form> 
                @endif
            </div>
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

<style type="text/css">
    .col-sm-4.st-app label {
    text-align: left;
} 
.st-app form {
    border: 1px solid #ccc;
    padding: 15px;
    background: #fff;
    border-radius: 10px;
}
.st-app  .form-horizontal .form-group {
    margin-left: -15px !important;
}
</style>
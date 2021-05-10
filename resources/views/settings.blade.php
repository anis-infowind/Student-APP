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

            <div class="col-sm-12">

                @if(isset($settings) && !empty($settings))

                <form method="post" action="{{url('settings/update')}}" class="form-horizontal">

                    <div class="form-group">

                        <label class="control-label col-sm-2" for="email">Button text <span style="color:red;">*</span></label>

                        <div class="col-sm-10">

                            <input type="text" name="student_btn_text" class="form-control" placehoder="Enter button text." value="{{$settings->meta_value}}" required>

                        </div>

                    </div>

                    <div class="form-group">

                        <div class="col-sm-offset-2 col-sm-10">

                        <button type="submit" class="btn btn-primary">Submit</button>

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
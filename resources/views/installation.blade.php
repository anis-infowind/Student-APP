@extends('layouts.default')

@section('title', 'Installation')

@section('content')
    <div class="container second">
        <div class="row">
            Manual Installation Steps
        </div>
        <br />
        <div class="row">
            <div class="col-sm-12">
                <div class="steps">
                    <p>Step 1</p>
                    <img src="{{url('/public/images/installation/step-1.png')}}" data-step-url="{{url('/public/images/installation/step-1.png')}}" class="step-image-modal" width="500" height="250" />
                </div>
                <div class="steps">
                    <p>Step 2</p>
                    <p>From here you choose a theme to which you want to apply.</p>
                    <img src="{{url('/public/images/installation/step-2.png')}}" data-step-url="{{url('/public/images/installation/step-2.png')}}" class="step-image-modal" width="500" height="250" />
                </div>
                <div class="steps">
                    <p>Step 3</p>
                    <pre>{% include 'verifystudent_common' %}</pre>
                    <img src="{{url('/public/images/installation/step-3.png')}}" data-step-url="{{url('/public/images/installation/step-3.png')}}" class="step-image-modal" width="500" height="250" />
                </div>
                <div class="steps">
                    <p>Step 4</p>
                    <pre>{% include 'verifystudent_btn' %}</pre>
                    <img src="{{url('/public/images/installation/step-4.png')}}" data-step-url="{{url('/public/images/installation/step-4.png')}}" class="step-image-modal" width="500" height="250" />
                </div>
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
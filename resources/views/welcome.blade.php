@extends('layouts.default')

@section('title', 'Home')
@section('content')
    <div class="container second">
        <!-- <div class="row">
            {{ Auth::user()->name }}
        </div> -->
        <div class="firstpage_header">
            <!-- <p>Store name: {{ Auth::user()->name }}</p> -->
            <h1>Welcome to Dashboard.</h1>
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
            title: 'Home',
        };
        var myTitleBar = TitleBar.create(app, titleBarOptions);

    </script>
@endsection
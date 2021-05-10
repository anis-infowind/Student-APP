@extends('layouts.default')

@section('title', 'Options')
@section('content')
    <div class="container second">
        <!-- <div class="row">
            {{ Auth::user()->name }}
        </div> -->
        <div class="firstpage_header">
            <!-- <p>Store name: {{ Auth::user()->name }}</p> -->
            <h1>Welcome to Options.</h1>
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
            title: 'Welcome',
        };
        var myTitleBar = TitleBar.create(app, titleBarOptions);

    </script>
@endsection
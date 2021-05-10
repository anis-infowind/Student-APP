<!DOCTYPE html>

<html>

    <head>

        <meta charset="UTF-8">

        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('shopify-app.app_name') }}</title>

        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>

        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">


        <!-- Bootstrap 3.3.4 -->

        <link href="{{ asset('public/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />

        <link href="{{ asset('public/bootstrap-modal/css/bootstrap-modal.css') }}" rel="stylesheet" type="text/css" />

        <!-- Datatables 1.10.19 -->

        <link href="{{ asset('public/datatable/css/dataTables.bootstrap.min.css') }}" rel="stylesheet" type="text/css" />  

        <!-- FontAwesome 4.3.0 --> 

        <link href="{{ asset('public/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css" />

        <!-- Spectrum 1.8.0 -->

        <link href="{{ asset('public/spectrum/css/spectrum.css') }}" rel="stylesheet" type="text/css" />

        <!-- Sweetalert -->

        <link href="{{ asset('public/sweetalert/css/sweetalert.css') }}" rel="stylesheet" type="text/css" />

        <!-- Ionicons 2.0.0 -->

        <link href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet" type="text/css" />

        <!-- App style -->

        <link href="{{ asset('public/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" type="text/css" />

        <!-- App style -->

        <link href="{{ asset('public/css/bootstrap-datetimepicker.css') }}" rel="stylesheet" type="text/css" />

        <!-- App style -->

        <link href="{{ asset('public/css/app.css') }}" rel="stylesheet" type="text/css" />

        <link href="{{ asset('public/css/custom.css') }}" rel="stylesheet" type="text/css" />

        <!-- jQuery 2.1.4 -->



        <script src="{{ asset('public/js/jQuery-2.1.4.min.js') }}"></script>

        <script src="{{ asset('public/js/bootstrap-datetimepicker.min.js') }}"></script>

        <script src="{{ asset('public/js/bootstrap-datetimepicker.js') }}"></script>

        <script type="text/javascript">
            var APP_URL = "{{url('/')}}";
        </script>

    </head>

    <body class="d-flex flex-column min-vh-100">

        <div id="wrapper" class="wrapper">

            <header class="header">

                <div class="container">

                    <div class="navbar-header">

                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">

                          <span class="icon-bar"></span>

                          <span class="icon-bar"></span>

                          <span class="icon-bar"></span>

                        </button> 

                        @if(!empty(Auth::user()))

                        @php $shopname = explode('.',Auth::user()->name) @endphp

                        <a class="navbar-brand" href="{{ url('/?shop='.$shopname[0]) }}">Verify Students</a>

                        @else

                        <a class="navbar-brand" href="{{ url('/') }}">Verify Students</a>

                        @endif

                    </div> 

                    <div class="collapse navbar-collapse" id="myNavbar">

                        <ul class="nav navbar-nav navbar-right">
                            <li class="home "> 
                                @if(!empty(Auth::user()))
                                @php $shopname = explode('.',Auth::user()->name) @endphp
                                <a href="{{ url('/?shop='.$shopname[0]) }}"><i class="glyphicon glyphicon-home"></i>Home</a>
                                @else
                                <a href="{{ url('/') }}"><i class="glyphicon glyphicon-home"></i>Home</a>
                                @endif
                            </li>
                            <li class="home option">
                                <a href="{{ url('/discounts') }}"><i class="glyphicon glyphicon-cog"></i>Discount Settings</a>
                            </li>
                            <li class="home option">
                                <a href="{{ url('/settings') }}"><i class="glyphicon glyphicon-cog"></i>Settings</a>
                            </li>
                        </ul>

                    </div>

                </div>

            </header>

            <div id="content">

                <div class="container">
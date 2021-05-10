<!DOCTYPE html>

<html>

    <head>

        <meta charset="UTF-8">

        <title>Verify Students App</title>

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

        <!-- jQuery 2.1.4 -->



        <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">





        <script src="{{ asset('public/js/jQuery-2.1.4.min.js') }}"></script>

        <script src="{{ asset('public/js/bootstrap-datetimepicker.min.js') }}"></script>

        <script src="{{ asset('public/js/bootstrap-datetimepicker.js') }}"></script>

        

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->

        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->

        <!--[if lt IE 9]>

            <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>

            <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>

        <![endif]-->

    </head>

    <body class="loginPage">

        <div id="wrapper" class="wrapper">

            <header class="header">

                <div class="container"> 

                    <div class="navbar-header">

                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">

                          <span class="icon-bar"></span>

                          <span class="icon-bar"></span>

                          <span class="icon-bar"></span>

                        </button>

                        <a class="navbar-brand" href="{{ url('/') }}">Verify Students App</a>

                    </div>

                    

                </div>

            </header>

            <div id="content">

                <div class="container">



					<div class="container second loginPageInner">

					    <div class="row">

				      		<div class="firstpage_header">

				          		<h2>Welcome to the Verify Students App!</h2>

					      	</div> 



					      	<div class="col-sm-12 col-md-6 col-md-offset-3 text-center"> 

					      		<form class="form-horizontal app-login" id="app-login" action="{{ route('shop') }}" method="get">

					      			{{ csrf_field() }}

					      			<div class="input-group mb-lg-2  position-relative free_install emailField">
                                        <label>Store address</label> 
					      				<input required type="text" id="shop" name="shop" placeholder="Enter store address" class="form-control border-0 input-style">

					      				<span class="input-group-btn py-lg-0 py-md-0 py-3">

	                                      	<button type="submit" class="btn border-0 p-0 text-blue w-100 btn_get_started">Try it for free</button>

	                                  	</span>
					      			</div> 

					      		</form>

					      	</div>

					  	</div>

					</div>



                    <footer class="main-footer">

                        <div class="container">

                            <div class="pull-right hidden-xs">

                                <b>Verify Students App</b> | Version 1.0

                            </div>

                            <strong>Copyright &copy; {{ date('Y') }} <a href="{{ url('/') }}">Verify Students App</a>.</strong> All rights reserved.

                        </div>

                    </footer>

                    

                    <!-- jQuery UI 1.11.2 -->

                    <!-- <script src="http://code.jquery.com/ui/1.11.2/jquery-ui.min.js" type="text/javascript"></script> -->

                    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->

                    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

                    <!-- Bootstrap 3.3.2 JS -->

                    <script src="{{ asset('public/bootstrap/js/bootstrap.min.js') }}" type="text/javascript"></script>

                    <script src="{{ asset('public/bootstrap-modal/js/bootstrap-modalmanager.js') }}" type="text/javascript"></script>

                    <script src="{{ asset('public/bootstrap-modal/js/bootstrap-modal.js') }}" type="text/javascript"></script>

                    <!-- Datatables 1.10.19 JS -->

                    <script src="{{ asset('public/datatable/js/jquery.dataTables.min.js') }}>" type="text/javascript"></script>

                    <!-- Datatables Bootstrap 1.10.19 JS -->

                    <script src="{{ asset('public/datatable/js/dataTables.bootstrap.min.js') }}" type="text/javascript"></script>

                    <!-- Spectrum 1.8.0 -->

                    <script src="{{ asset('public/spectrum/js/spectrum.js') }}" type="text/javascript"></script>

                    <!-- Sweetalert -->

                    <script src="{{ asset('public/sweetalert/js/sweetalert.js') }}" type="text/javascript"></script>

                    <script src="{{ asset('public/js/jquery.validate.js') }}" type="text/javascript"></script>

                    <script src="{{ asset('public/js/app-home.js') }}" type="text/javascript"></script>

                </div>

            </div>

        </div>

    </body>

</html>
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
 
        <div class="loader" style="display: none;"> 
            <div class="container-in">
              <div class="wrapper-in">
               <div class="left1"></div>
               <div class="right1"></div>
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
            title: 'Home',
        };
        var myTitleBar = TitleBar.create(app, titleBarOptions);

    </script>
@endsection



<style type="text/css">
.container-in {
  height: 100vh;
  width: 100vw;
 /* background-color: black;*/
  display: grid;
  place-content: center;
}
.wrapper-in {
  height: 60px;
  width: 100px;
  display: flex;
  justify-content: space-between;
  align-items: center;
}
.left1,
.right1 {
  height: 50px;
  width: 50px;
  border-radius: 50%;
  background-color: #17A589;
  animation: pulse 1.4s linear infinite;
}
.right1 {
  animation-delay: 0.7s;
}
@keyframes pulse {
  0%,
  100% {
    transform: scale(0);
    opacity: 0.1;
  }
  50% {
    transform: scale(1);
    opacity: 1;
  }
}


</style>
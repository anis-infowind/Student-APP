<footer class="main-footer">

    <div class="container">

        <div class="pull-right hidden-xs">

            <b class="soundwave-name">Verify Students</b> | Version 1.0

        </div>

        <strong>Copyright &copy; {{ date('Y') }} <a href="{{ url('/') }}">Verify Students</a>.</strong> All rights reserved.

    </div>

</footer>

                    

@if(config('shopify-app.appbridge_enabled'))
    <script src="https://unpkg.com/@shopify/app-bridge{{ config('shopify-app.appbridge_version') ? '@'.config('shopify-app.appbridge_version') : '' }}"></script> 
    <script>
        var AppBridge = window['app-bridge']; 
        var createApp = AppBridge.default; 
        var app = createApp({
            apiKey: '{{ config('shopify-app.api_key') }}',
            shopOrigin: '{{ Auth::user()->name }}',
            forceRedirect: true,
        });
    </script>
@endif

                    <!-- jQuery UI 1.11.2 -->

                    <!-- <script src="http://code.jquery.com/ui/1.11.2/jquery-ui.min.js" type="text/javascript"></script> -->

                    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->

                    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

                    <!-- Bootstrap 3.3.2 JS -->

                    <script src="{{ asset('public/bootstrap/js/bootstrap.min.js') }}" type="text/javascript"></script>

                    <script src="{{ asset('public/bootstrap-modal/js/bootstrap-modalmanager.js') }}" type="text/javascript"></script>

                   <!--  <script src="{{ asset('public/bootstrap-modal/js/bootstrap-modal.js') }}" type="text/javascript"></script> -->

                    <!-- Datatables 1.10.19 JS -->

                    <script src="{{ asset('public/datatable/js/jquery.dataTables.min.js') }}>" type="text/javascript"></script>

                    <!-- Datatables Bootstrap 1.10.19 JS -->

                    <script src="{{ asset('public/datatable/js/dataTables.bootstrap.min.js') }}" type="text/javascript"></script>

                    <!-- Spectrum 1.8.0 -->

                    <script src="{{ asset('public/spectrum/js/spectrum.js') }}" type="text/javascript"></script>

                    <!-- Sweetalert -->

                    <script src="{{ asset('public/sweetalert/js/sweetalert.js') }}" type="text/javascript"></script>

                    <script src="{{ asset('public/js/jquery.validate.js') }}" type="text/javascript"></script>

                    <script src="{{ asset('public/js/app.js') }}" type="text/javascript"></script>

                </div> 

            </div>

        </div>



        <!-- Modal -->

        <div id="optionEditModal" class="modal fade" role="dialog">

          <div class="modal-dialog">

            <!-- Modal content-->

            <div class="modal-content">

              <div class="modal-header">

                <button type="button" class="close" data-dismiss="modal">&times;</button>

                <h4 class="modal-title">Modal Header</h4>

              </div>

              <div class="modal-body">

                <p>Some text in the modal.</p> 

              </div>

              <div class="modal-footer">

                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

              </div>

            </div>



          </div>

        </div>


        <!-- Delete Modal -->
        <div id="modal2" class="modal fade" role="dialog">
          <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Discount Delete</h4>
              </div>
              <form method="post" action="{{url('/discount/destroy')}}">
                <input type="hidden" name="rule_id" id="rule_id" value="">
                <div class="modal-body">
                  <p>Are you sure you want to delete?</p>
                </div>
                <div class="modal-footer">
                  <button type="button" style="background: #117864 !important;  color: #fff !important; border: none;" class="btn btn-default add-discount1" data-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-info delte-btn1">Delete</button>
                </div>
              </form>
            </div>
          </div>
        </div>

       <!--  <script type="text/javascript">
          $(document).ready(function(){
            $(".modal-scrollable").click(function(){
              $(".close").click();
            });
          });

        </script> -->

    </body>



<script type="text/javascript">
  
      $(document).on("mousedown", '.collection-list option', function(e){
          e.preventDefault();
          var originalScrollTop = $(this).parent().scrollTop();
          console.log(originalScrollTop);
          $(this).prop('selected', $(this).prop('selected') ? false : true);
          var self = this;
          //$(this).parent().focus();
          setTimeout(function() {
              $(self).parent().scrollTop(originalScrollTop);
          }, 0);
           
          return false; 
      });
</script>

</html>
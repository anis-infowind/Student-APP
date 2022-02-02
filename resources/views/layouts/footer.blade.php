<footer class="main-footer">
    <div class="container">
        <div class="pull-right hidden-xs">
            <b class="soundwave-name">Verify Students</b> | Version 1.0
        </div>
        <strong>Copyright &copy; {{ date('Y') }} <a href="{{ url('/') }}">Verify Students</a>.</strong> All rights reserved.
    </div>
</footer>

    <script src="https://unpkg.com/@shopify/app-bridge@2"></script> 
    <script src="https://unpkg.com/@shopify/app-bridge-utils@2"></script> 
    <script>
		var shopUrl = '{{ Session::get('shopify_Shop') }}';
        var AppBridge = window['app-bridge'];
        var appBrdigeUtils = window['app-bridge-utils'];
        var getSessionToken = appBrdigeUtils.getSessionToken;
        var createApp = AppBridge.default; 
        var app = createApp({
            apiKey: '{{ config('shopify-app.api_key') }}',
            host: '{{ Session::get('shopify_Host') }}',
            forceRedirect: true,
        });
    </script>
    <script type="text/javascript">
      var SESSION_TOKEN_REFRESH_INTERVAL = 2000; // Request a new token every 2s

        var actions = AppBridge.actions;
        window.addEventListener('load', function(event) {
          const history = actions.History.create(app);
          history.dispatch(actions.History.Action.PUSH, window.location.pathname+window.location.search);
        });
        var Toast = actions.Toast;
        var TitleBar = actions.TitleBar;
        var Button = actions.Button;
        var ButtonGroup = actions.ButtonGroup;
        var Redirect = actions.Redirect;
        var ResourcePicker = actions.ResourcePicker
        var redirect = Redirect.create(app);
        var SubmitTicket = Button.create(app, {label: "Need an Expert?"});
        var Faq = Button.create(app, {label: "FAQ"});
        var Guide = Button.create(app, {label: "Set-up Guide"});
        var Installation = Button.create(app, {label: "Installation"});
        var Uninstall = Button.create(app, {label: "How to Uninstall?"});
        var ActivityLogs = Button.create(app, {label: "Activity Logs"});
        var SupportMenu = []
        var InstallMenu = []
        var feedbackMenu = []

        //To redirect Javascript links using Shopify AppBrdige Redirect.
        window.appRedirect = function (path) {
                var redirect = Redirect.create(window.app);
                redirect.dispatch(Redirect.Action.APP, path);
            }
            // To redirect external using Shopify AppBrdige Redirect.
            window.remoteRedirect = function(url) {
                var redirect = Redirect.create(window.app);
                redirect.dispatch(Redirect.Action.REMOTE, url);
            }
    </script>

                    <!-- jQuery UI 1.11.2 -->
                    <!-- <script src="http://code.jquery.com/ui/1.11.2/jquery-ui.min.js" type="text/javascript"></script> -->
                    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
                    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

                    <!-- Bootstrap 3.3.2 JS -->
                    <script src="{{ asset('public/bootstrap/js/bootstrap.min.js') }}" type="text/javascript"></script>
                    <script src="{{ asset('public/bootstrap-modal/js/bootstrap-modalmanager.js') }}" type="text/javascript"></script>

                   <!--  <script src="{{ asset('public/bootstrap-modal/js/bootstrap-modal.js') }}" type="text/javascript"></script> -->

                    <!-- Datatables 1.10.19 JS -->
                    <!-- <script src="{{ asset('public/datatable/js/jquery.dataTables.min.js') }}>" type="text/javascript"></script> -->

                    <!-- Datatables Bootstrap 1.10.19 JS -->
                    <!-- <script src="{{ asset('public/datatable/js/dataTables.bootstrap.min.js') }}" type="text/javascript"></script> -->

                    <!-- Spectrum 1.8.0 -->
                    <!-- <script src="{{ asset('public/spectrum/js/spectrum.js') }}" type="text/javascript"></script> -->

                    <!-- Sweetalert -->
                    <script src="{{ asset('public/sweetalert/js/sweetalert.js') }}" type="text/javascript"></script>
                    <script src="{{ asset('public/js/jquery.validate.js') }}" type="text/javascript"></script>
                    <script src="{{ asset('public/js/app.js') }}" type="text/javascript"></script>
                </div> 
            </div>
        </div>

        <script>
          $(document).ready(function () {
                //Redirect every anchor tag using JavaScript AppBridge Redirect.
                $('a').on('click', function (event) {
                    var href = $(this).attr('href');
                    var target = $(this).attr('target');
                    var dmethod = $(this).attr('data-method');
                    var download = $(this).attr('download');
                    if (href !== '#' && target != "_blank" && dmethod != "delete" && download != "file" && href != undefined) {
                        event.preventDefault();
                        var redirect = Redirect.create(window.app);
                        if (href.includes("https")){
                            var parsedHref = new URL(href); 
                            href = parsedHref.pathname
                        }
                        redirect.dispatch(Redirect.Action.APP, href);
                    }
                });

                $("form, a[data-method=delete]").on("ajax:beforeSend", function (event) {
                    event.preventDefault();
                    $.ajaxSetup({
                        headers: {
                            'Authorization': "Bearer " + window.sessionToken
                        }
                    });
                });

                const fetch = appBrdigeUtils.authenticatedFetch(window.app);
                //console.log("Fetch: " + fetch);

                document.addEventListener("turbolinks:request-start", function (event) {
                    var xhr = event.data.xhr;
                    xhr.setRequestHeader("Authorization", "Bearer " + window.sessionToken);
                });
                document.addEventListener("turbolinks:render", function () {
                    $("form, a[data-method=delete]").on("ajax:beforeSend", function (event) {
                        const xhr = event.detail[0];
                        xhr.setRequestHeader("Authorization", "Bearer " + window.sessionToken);
                    });
                });
            });

            document.addEventListener("DOMContentLoaded", async function () {
                // Wait for a session token before trying to load an authenticated page
                await retrieveToken(window.app);

                // Keep retrieving a session token periodically
                keepRetrievingToken(window.app);

                async function retrieveToken(app) {
                    window.sessionToken = await getSessionToken(app);
                    $.ajaxSetup({
                        headers: {
                            'Authorization': "Bearer " + window.sessionToken
                        }
                    });
                }

                function keepRetrievingToken(app) {
                    setInterval(function () {
                        retrieveToken(app);
                    }, SESSION_TOKEN_REFRESH_INTERVAL);
                }
            });
            /* Cookieless authentication JS END */
            
            window.addEventListener('load', function(event) {
                const history = actions.History.create(app);
                history.dispatch(actions.History.Action.PUSH, window.location.pathname+window.location.search);
            });

		$(document).ready(function(){
			$("body").on('click', '#apply_product_filter', function(event) {
            	var query;
            	const productPicker = ResourcePicker.create(app, {
					resourceType: ResourcePicker.ResourceType.Product,
					options: {
						selectMultiple: true,
						showHidden: false,
						showVariants: false,
						initialQuery: query
					},
				});

				productPicker.dispatch(ResourcePicker.Action.OPEN);
				const selectSubscribe = productPicker.subscribe(ResourcePicker.Action.SELECT, ({
				selection
				}) => {
					var repeat_products = [];
					$.each(selection, function(index, val) {
						$('.remove-all-products').show();
						var pid = val.id.replace('gid://shopify/Product/', '');
						var allset_ids = $("#products_id").val().split(',');
						if ($.inArray(pid, allset_ids) >= 0) {
							repeat_products.push(val.title);
						}
						else {
							$('tr.all_products_selected, tr.no_products_selected').remove();
							if ($("." + pid).length == 0) {
								$('.selected_products tbody').append('<tr class="' + pid + '"><td>' + val.title + ' <a href="https://'+shopUrl+'/admin/products/' + pid + '" target="_blank">View in store</a></td><td><a href="javascript:void(0)" class="table-action-btn btn-small btn btn-small btn-danger product-remove" data-pid="' + pid + '"><i class="fa fa-trash" aria-hidden="true"></i></a></td></tr>');
								$('.submit-discount-form').append('<input class="products ' + pid + '" type="hidden" name="products[' + pid + ']" value="' + pid + '">');
								$('.no_products_error').remove();
							}
						}
					});

					$('.selected_products_count').html('Showing ' + $(".selected_products tbody tr:not(.no_products_selected)").length + ' entries');
					if (repeat_products.length > 0) {
						var selected_products = $(".selected_products tbody tr:not(.no_products_selected)").length;
						if (selected_products == 0) {
							$('.selected_products tbody').html('<tr class="no_products_selected"><td>No products selected</td><td></td></tr>');
						}
					}
				});
        	});

			$(document).on('click', '.product-remove', function(event) {
				$(this).parents('tr').remove();
				$('.' + $(this).attr('data-pid')).remove();
				var selected_products = $(".selected_products tbody tr:not(.no_products_selected)").length;
				if (selected_products == 0) {
					$('.selected_products tbody').html('<tr class="no_products_selected"><td>No products selected</td><td></td></tr>');
				}
				$('.selected_products_count').html('Showing ' + selected_products + ' entries');
			});

			$(document).on('click', '.remove-all-products', function() {
				$('.selected_products tbody tr').remove();
				$('.selected_products tbody').html('<tr class="no_products_selected"><td>No products selected</td><td></td></tr>');
				$('.selected_products_count').html('Showing ' + $(".selected_products tbody tr:not(.no_products_selected)").length + ' entries');
				$('.products').remove();
				$(this).hide();
			});

			$("body").on('click', '#apply_collection_filter', function(event) {
            	var query;
            	const collectionPicker = ResourcePicker.create(app, {
					resourceType: ResourcePicker.ResourceType.Collection,
					options: {
						selectMultiple: true,
						showHidden: false,
						initialQuery: query
					},
				});

				collectionPicker.dispatch(ResourcePicker.Action.OPEN);
				const selectSubscribe = collectionPicker.subscribe(ResourcePicker.Action.SELECT, ({
				selection
				}) => {
					var repeat_collections = [];
					$.each(selection, function(index, val) {
						$('.remove-all-collections').show();
						var cid = val.id.replace('gid://shopify/Collection/', '');
						var allset_ids = $("#collections_id").val().split(',');
						if ($.inArray(cid, allset_ids) >= 0) {
							repeat_collections.push(val.title);
						}
						else {
							$('tr.all_collections_selected, tr.no_collections_selected').remove();
							if ($("." + cid).length == 0) {
								$('.selected_collections tbody').append('<tr class="' + cid + '"><td>' + val.title + ' <a href="https://'+shopUrl+'/admin/collections/' + cid + '" target="_blank">View in store</a></td><td><a href="javascript:void(0)" class="table-action-btn btn-small btn btn-small btn-danger collection-remove" data-cid="' + cid + '"><i class="fa fa-trash" aria-hidden="true"></i></a></td></tr>');
								$('.submit-discount-form').append('<input class="collections ' + cid + '" type="hidden" name="collections[' + cid + ']" value="' + cid + '">');
								$('.no_collections_error').remove();
							}
						}
					});

					$('.selected_collections_count').html('Showing ' + $(".selected_collections tbody tr:not(.no_collections_selected)").length + ' entries');
					if (repeat_collections.length > 0) {
						var selected_collections = $(".selected_collections tbody tr:not(.no_collections_selected)").length;
						if (selected_collections == 0) {
							$('.selected_collections tbody').html('<tr class="no_collections_selected"><td>No collections selected</td><td></td></tr>');
						}
					}
				});
        	});

			$(document).on('click', '.collection-remove', function(event) {
				$(this).parents('tr').remove();
				$('.' + $(this).attr('data-cid')).remove();
				var selected_collections = $(".selected_collections tbody tr:not(.no_collections_selected)").length;
				if (selected_collections == 0) {
					$('.selected_collections tbody').html('<tr class="no_collections_selected"><td>No collections selected</td><td></td></tr>');
				}
				$('.selected_collections_count').html('Showing ' + selected_collections + ' entries');
			});

			$(document).on('click', '.remove-all-collections', function() {
				$('.selected_collections tbody tr').remove();
				$('.selected_collections tbody').html('<tr class="no_collections_selected"><td>No collections selected</td><td></td></tr>');
				$('.selected_collections_count').html('Showing ' + $(".selected_collections tbody tr:not(.no_collections_selected)").length + ' entries');
				$('.collections').remove();
				$(this).hide();
			});
		});
        </script>



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


        <!-- Image Modal -->
        <div id="modal3" class="modal fade" role="dialog">
          <div class="modal-dialog">
             <button type="button" class="close" data-dismiss="modal">x</button>
            <!-- Modal content-->
            <div class="modal-content">
              <img src="" class="step_image" />
            </div>
          </div>
        </div>
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

<style type="text/css">
  .modal {

      background: none !important;

  } 

  #modal2 .modal-dialog,
  #modal3 .modal-dialog {
    width: 900px;
}
 #modal2 .modal-content img,
  #modal3 .modal-content img{
  width: 100%;
}

 #modal2 button.close,
 #modal3 button.close {
    background: #f00;
    color: #fff;
    position: absolute;
    display: block;
    z-index: 1;
    right: 0;
    opacity: 1;
    font-weight: normal;
    width: 30px;
    height: 30px;
    font-size: 18px;
    padding: 0 !important;
    line-height: 0;
}

</style>

</html>
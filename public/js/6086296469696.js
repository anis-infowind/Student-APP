
		function ready(fn) {
			  if (document.readyState != 'loading'){
				fn();
			  } else {
				document.addEventListener('DOMContentLoaded', fn);
			  }
			}
		 (function(){
			ready(function() {
				console.log('checking product..');
				var idx = $('form[action="/cart/add"] *[name="id"]:eq(0)').val();
				/*if ( idx == undefined ){
					console.log('idx:'+ idx);
					return false;
				}*/
				var product_handle_check = product_detail.handle;
				if (product_handle_check.substr(product_handle_check.length - 10) == 'customized'){
					if ( typeof product_ori_id != undefined && typeof product_ori_handle != undefined ) {
						console.log('redirection..');
						$('body').html('<p>Loading..</p>');
						//console.log('product_ori_id');
						//console.log(product_ori_id);
						var the_link_direct = '/soundwave/view/' + product_ori_handle + '/' + product_ori_id + '?variant=' + idx + '&domain=' + window.location.hostname;
						window.location = the_link_direct;
						return false;
					}
				}
				$('form[action="/cart/add"]').on('click', '#customify-link' , function(e){
					 e.preventDefault();
					 	var inputQty = $( this ).closest('form').find('input[name=quantity]');
							//console.log('customify link clicked');
							idx = $('select[name=id]').length > 0 ? $('select[name=id]').val() : $('input[name=id]');
							var qty = '1';
							qty = (inputQty.length > 0 && inputQty.val() != '') ? inputQty.val() : '1';
							var the_link = '/soundwave/view/' + $(this).data('handle') + '/' + $(this).data('product') + '?variant=' + idx + '&qty='+ qty +'&domain=' + window.location.hostname;
							//console.log(the_link);
							window.location = the_link;
							return false;
				});
				 var result = {"status":"no","setting":{"keep_addtocart":"0","redirect_instant":"0","customize_it":"Customize it"}}
				 if ( result.status == 'yes' )
						{
							keep_addtocart = result.setting.keep_addtocart;
							redirect_instant = result.setting.redirect_instant;
							var the_link = '/soundwave/view/' + product_detail.handle + '/' + product_detail.id + '?variant=' + idx + '&domain=' + window.location.hostname;
							if ( redirect_instant == 1 ) {
							window.location = the_link;
							return false;
							}
							if ( $('#add-to-cart').length > 0 )
							var the_button = $('#add-to-cart');
							else if ( $('button[name=add]').length > 0 )
							var the_button = $('button[name=add]');
							else
							var the_button =  $('form[action="/cart/add"] button[type="submit"]').length > 0 ? $('form[action="/cart/add"] button[type="submit"]') : $('form[action="/cart/add"] input[type="submit"]');
							the_button.hide();
							var the_class = the_button.attr('class');
							if ( the_class != undefined ) {
							the_class = the_class.replace('add-to-cart','');
							the_class = the_class.replace('hidden','');
							}
							else
							{
								the_class = 'btn button';
							}
							var the_style = '';
							button_width = the_button.width() * 1;
							if ( keep_addtocart == 1 ) {
								if ( button_width > 200 )
								the_style = 'style="clear: both; margin-top: 10px;"';
								else
								the_style = 'style="margin-left: 10px;"';
							}
							//console.log('check wheter the customify custom container available..');
							if ($('#customify-container').length > 0 )
							{
							the_class = $('#customify-container').data('class');
							$('#customify-container').html('<a  data-handle="' + product_detail.handle + '"  data-product="' + product_detail.id + '"  id="customify-link" class="' + the_class + '" href="' + the_link + '" >' + result.setting.customize_it + '</a>');
							}
							else
							{
							$('<a ' + the_style + ' data-handle="' + product_detail.handle + '"  data-product="' + product_detail.id + '"  id="customify-link" class="' + the_class + '" href="' + the_link + '" >' + result.setting.customize_it + '</a>').insertAfter(the_button);
							}
							if ( the_button.attr('data-show') == undefined && keep_addtocart == 0 ) {
							//console.log('hide the add to cart');
							$('form[action="/cart/add"] *[type="submit"]').remove();
							the_button.remove();
							$('body').append('<style>form[action="/cart/add"] *[type="submit"] { display:none!important; visibility: hidden }</style>');
							}
							else
							the_button.show();
							if (typeof customify_callback !== 'undefined' && typeof customify_callback === 'function') {
								customify_callback();
							}
						}
						else {
							$('form[action="/cart/add"] *[type="submit"]').show();
						}
					});
		   })();
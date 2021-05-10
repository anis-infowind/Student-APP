function startVogueApp() {
    window.vogueappsLoadScript = function(url, callback) {
        var script = document.createElement("script");
        script.type = "text/javascript";
        if (script.readyState) {
            script.onreadystatechange = function() {
                if (script.readyState == "loaded" || script.readyState == "complete") {
                    script.onreadystatechange = null;
                    callback()
                }
            }
        } else {
            script.onload = function() {
                callback()
            }
        }
        script.src = url;
        document.getElementsByTagName("head")[0].appendChild(script)
    };
    window.checkAppInstalled = function(version) {
        window.vogueapps.is_volume_discount = !0;
        window.vogueapps.is_product_option = !0;
        if (window.vogueapps.is_product_option) {
            vogueappsLoadScript(window.vogueapps.po_url + '/assets/js/zoom.min.js', function() {});
            vogueappsLoadScript(window.vogueapps.po_url + '/assets/js/intl-telephone-input.js', function() {
                $.ajax({
                    type: "GET",
                    url: window.vogueapps.po_url + "/appstore/discounts/is_installed_and_upgraded",
                    data: {
                        store_id: window.vogueapps.store_id
                    },
                    crossDomain: !0,
                    dataType: "json",
                    success: function(data) {
                        window.vogueapps.is_volume_discount_upgraded = data.is_upgraded
                        window.vogueapps.is_volume_discount = data.is_installed
                        console.log("VD vogueapps is_installed===>", window.vogueapps.is_volume_discount);
                        console.log("VD vogueapps is_upgraded===>", window.vogueapps.is_volume_discount_upgraded);
                        $.ajax({
                            type: "GET",
                            url: window.vogueapps.po_url + "/appstore/app/is_installed_and_upgraded",
                            data: {
                                store_id: window.vogueapps.store_id
                            },
                            crossDomain: !0,
                            dataType: "json",
                            success: function(data) {
                                window.vogueapps.is_product_option_upgraded = data.is_upgraded
                                window.vogueapps.is_product_option = data.is_installed
                                console.log("PO vogueapps is_installed===>", window.vogueapps.is_product_option);
                                console.log("PO vogueapps is_upgraded===>", window.vogueapps.is_product_option_upgraded);
                                if (window.vogueapps.is_product_option_upgraded && window.vogueapps.is_volume_discount_upgraded) {
                                    vogueappsCommonJS(version);
                                    vogueappsCartPageJS(version);
                                    vogueappsProductPageJS(version)
                                }
                            }
                        })
                    }
                })
            })
        } else {
            vogueappsCommonJS(version);
            vogueappsCartPageJS(version);
            vogueappsProductPageJS(version)
        }
    };
    window.vogueappsCommonJS = function($) {
        window.vogueappsDoActions = function(data) {
            data = JSON.parse(data);
            if (data.discounts.discount_show) {
                $(".discount_code_box").css("display", "block")
            }
            if (data.discounts.plan) {
                $(".edit_cart_option").css("display", "block")
            }
            if (typeof data.discounts == "object" && typeof data.discounts.cart == "object" && typeof data.discounts.cart.items == "object") {
                vogueappsShowCartDiscounts(data.discounts)
            }
            jQuery(document).on('click', "input[name='checkout']:not(.vogueapps-ignore), input[value='Checkout']:not(.vogueapps-ignore), button[name='checkout']:not(.vogueapps-ignore), [href$='checkout']:not(.vogueapps-ignore), button[value='Checkout']:not(.vogueapps-ignore), input[name='goto_pp'], button[name='goto_pp'], input[name='goto_gc'], button[name='goto_gc'],.vogueapps_checkout", function(e) {
                e.preventDefault();
                if (typeof vogueappsCheckout != "function") {
                    window.location = "/checkout"
                }
                if (typeof vogueappsCheckoutClick === 'undefined') {
                    console.log('vogueappsCheckoutClick function not found');
                    vogueappsCheckout()
                } else {
                    var result = vogueappsCheckoutClick();
                    if (result == !0) {
                        vogueappsCheckout()
                    } else if (result != !1) {
                        vogueappsCheckout()
                    }
                }
            })
        }
        window.vogueappsShowCartDiscounts = function(discounts) {
            window.vogueapps.discounts = discounts;
            var flag = 0;
            discounts.cart.items.forEach(function(item) {
                if (item.discounted_price < item.original_price) {
                    flag = 1;
                    jQuery(".vogueapps-cart-item-price[data-key='" + item.key + "']").html("<span class='original_price' style='text-decoration:line-through;'>" + item.original_price_format + "</span><br/>" + "<span class='discounted_price'>" + item.discounted_price_format + "</span><br/>" + "<span class='discounted_code'>" + item.discounted_code + "</span>");
                    jQuery(".vogueapps-cart-item-line-price[data-key='" + item.key + "']").html("<span class='original_price' style='text-decoration:line-through;'>" + item.original_line_price_format + "</span><br/>" + "<span class='discounted_price'>" + item.discounted_line_price_format + "</span>")
                } else if (item.discounted_price > item.original_price) {
                    flag = 1;
                    jQuery(".vogueapps-cart-item-price[data-key='" + item.key + "']").html("<span class='original_price' style='text-decoration:line-through;'>" + item.original_price_format + "</span><br/>" + "<span class='discounted_price'>" + item.discounted_price_format + "</span><br/>" + "<span class='discounted_code'>" + item.discounted_code + "</span>");
                    jQuery(".vogueapps-cart-item-line-price[data-key='" + item.key + "']").html("<span class='original_price' style='text-decoration:line-through;'>" + item.original_line_price_format + "</span><br/>" + "<span class='discounted_price'>" + item.discounted_line_price_format + "</span>")
                } else {
                    jQuery(".vogueapps-cart-item-price[data-key='" + item.key + "']").html("<span class='original_price'>" + item.original_price_format + "</span>");
                    jQuery(".vogueapps-cart-item-line-price[data-key='" + item.key + "']").html("<span class='original_price'>" + item.original_line_price_format + "</span>")
                }
            });
            if (flag == 1) {
                jQuery(".vogueapps-cart-original-total").html(discounts.original_price_total).css("text-decoration", "line-through");
                if (discounts.final_with_discounted_price == null) {
                    jQuery("</br><span class='vogueapps-cart-total'>" + discounts.discounted_price_total + "</span>").insertAfter('.vogueapps-cart-original-total')
                } else {
                    jQuery("</br><span class='vogueapps-cart-total'>" + discounts.final_with_discounted_price + "</span>").insertAfter('.vogueapps-cart-original-total')
                }
            }
            if (discounts.discount_code && discounts.discount_error == 1) {
                jQuery(".vogueapps-cart-original-total").html(discounts.original_price_total);
                jQuery(".vogueapps_discount_hide").after("<span class='vogueapps_summary'>Discount code does not match</span>");
                localStorage.removeItem('discount_code')
            } else if (discounts.discount_code && $('.discount_code_box').is(":visible")) {
                jQuery(".vogueapps_discount_hide").after("<span class='vogueapps-summary-line-discount-code'><span class='discount-tag'>" + discounts.discount_code + "<span class='close-tag'></span></span><span class='vogueapps_with_discount'>" + " -" + discounts.with_discount + "</span></span><span class='after_discount_price'><span class='final-total'>Total</span>" + discounts.final_with_discounted_price + "</span>");
                if (flag == 1) {
                    jQuery(".vogueapps-cart-original-total").html(discounts.discounted_price_total).css("text-decoration", "line-through")
                } else {
                    jQuery(".vogueapps-cart-original-total").html(discounts.original_price_total).css("text-decoration", "line-through")
                }
                jQuery(".vogueapps-cart-total").remove()
            } else {
                jQuery(".vogueapps-cart-original-total").html(discounts.original_price_total)
            }
        }
        window.vogueappsCheckout = function() {
            for (var i = 0; i < window.vogueapps.cart.items.length; i++) {
                var item = window.vogueapps.cart.items[i];
                var el = document.querySelectorAll("[id='updates_" + item.key + "']");
                if (el.length != 1) {
                    el = document.querySelectorAll("[id='updates_" + item.variant_id + "']")
                }
                if (el.length == 1) {
                    window.vogueapps.cart.items[i].quantity = el[0].value
                }
            }
            var pv_draft_url = '';
            if (window.vogueapps.is_volume_discount) {
                pv_draft_url = window.vogueapps.vd_url + "/shop/create_draft_order"
            } else if (window.vogueapps.is_product_option) {
                pv_draft_url = window.vogueapps.po_url + "/appstore/checkout/create_draft_order"
            }
            var btn_text = $('.wc-proceed-to-checkout .checkout-button').text();
            $('.wc-proceed-to-checkout .checkout-button').text("Please Wait...");
            $('.wc-proceed-to-checkout .checkout-button').attr('disabled', 'disabled');
            var storage_code = localStorage.getItem('discount_code');
            $.ajax({
                type: "POST",
                url: pv_draft_url,
                data: {
                    cart_json: JSON.stringify(window.vogueapps),
                    store_id: window.vogueapps.store_id,
                    discount_code: storage_code,
                    cart_collections: JSON.stringify(window.vogueapps.cart_collections)
                },
                crossDomain: !0,
                success: function(res) {
                    if (res.is_sale) {
                        window.location.href = res.url;
                        //window.location.href = "/checkout";
                    } else {
                        window.location.href = "/checkout";
                    }
                    localStorage.removeItem('discount_code');

                    $('.wc-proceed-to-checkout .checkout-button').text(btn_text);
                $('.wc-proceed-to-checkout .checkout-button').removeAttr('disabled');
                }
            })
        }
        window.getUrlParameter = function(sParam) {
            var sPageURL = window.location.search.substring(1),
                sURLVariables = sPageURL.split('&'),
                sParameterName, i;
            for (i = 0; i < sURLVariables.length; i++) {
                sParameterName = sURLVariables[i].split('=');
                if (sParameterName[0] === sParam) {
                    return sParameterName[1] === undefined ? !0 : decodeURIComponent(sParameterName[1])
                }
            }
        }
        window.vogueappsStart = function() {
            window.vogueappsc = {};
            setTimeout(function() {
                window.vogueappsc.$first_add_to_cart_el = null;
                var selectors = ["input[name='add']", "button[name='add']", "#add-to-cart", "#AddToCartText", "#AddToCart", ".variations_button"];
                var found_selectors = 0;
                selectors.forEach(function(selector) {
                    found_selectors += jQuery(selector).length;
                    if (window.vogueappsc.$first_add_to_cart_el == null && found_selectors) {
                        window.vogueappsc.$first_add_to_cart_el = jQuery(selector).first()
                    }
                });
                if (window.vogueapps.page_type == "product" && window.vogueappsc.$first_add_to_cart_el != null) {
                    var vol_el_after = window.vogueappsc.$first_add_to_cart_el;
                    if (vol_el_after.parent().is("div")) {
                        vol_el_after = vol_el_after.parent()
                    }
                    if (jQuery(".vogueapps-volumes").length == 0) {
                        vol_el_after.after('<div class="vogueapps-volumes"></div>')
                    }
                    if (jQuery('#vogueapps_custom_options_' + window.vogueapps.product_id).length == 0) {
                        vol_el_after.before('<div id="vogueapps_custom_options_' + window.vogueapps.product_id + '"></div>')
                    }
                }
                if (window.vogueapps.page_type == "product") {
                    if (window.vogueapps.is_volume_discount) {}
                    if (window.vogueapps.is_product_option) {
                        $.ajax({
                            type: "GET",
                            url: window.vogueapps.po_url + "/appstore/get_all_product_options",
                            data: {
                                pid: window.vogueapps.product_id,
                                store_id: window.vogueapps.store_id,
                                store_currency: window.vogueapps.store_currency
                            },
                            sync: !1,
                            crossDomain: !0,
                            success: function(data) {
                                $('#vogueapps_img_loader').remove();
                                $("#vogueapps_custom_options_" + window.vogueapps.product_id).html(data);
                                $('.product-single__photos').append('<div class="vougueapps-single-product-sale"></div><div class="vougueapps-single-product-image"><img class="vogue-img-zoom" data-zoom="' + window.vogueapps.po_url + '/assets/images/canvas_default.jpeg" src="' + window.vogueapps.po_url + '/assets/images/canvas_default.jpeg" style="display:none;"></div>');
                                $('.product-images-wrapper').append('<div class="vougueapps-single-product-sale"></div><div class="vougueapps-single-product-image"><img class="vogue-img-zoom" data-zoom="' + window.vogueapps.po_url + '/assets/images/canvas_default.jpeg" src="' + window.vogueapps.po_url + '/assets/images/canvas_default.jpeg" style="display:none;"></div>');

                                jQuery('#product_form_' + window.vogueapps.product_id).show();
                                setTimeout(function() {
                                    var options_data = $("#vogueapps_custom_options_" + window.vogueapps.product_id).html();
                                    if (options_data != '') {
                                        var p_price = $('#ProductPrice-product-template').closest('.product-single__price-product-template');
                                        var p_price_clone = p_price.clone();
                                        p_price.remove();
                                        //$('#option_total').after(p_price_clone.clone());

                                        $('#vogueapps_custom_options_' + window.vogueapps.product_id).closest("form").find(':submit').addClass('vogueapps_submit_cart');
                                      if (jQuery(".vogueapps_submit_checkout").length == 0) {
                                          jQuery('.shopify-payment-button > div').hide();
                                          jQuery('.shopify-payment-button').append('<button type="button" class="shopify-payment-button__button shopify-payment-button__button--unbranded vogueapps_submit_checkout">Buy it now</button>')
                                      }

                                      

                                      $('#variations-product-template').remove();
                                      $('#ProductSelect-product-template').remove();
                                    }
                                    
                                }, 100);

                                setTimeout(function(){
                                  var product_sale_title = $('.vogueapps_sale_title').html();
                                  var product_sale_data = $('.vogueapps_sale_data').html();
                                    console.log(product_sale_title);
                                  if (product_sale_title != undefined) {
                                    $('.vougueapps-single-product-sale').append('<div class="vogueapps-single-product-sale-title">'+product_sale_title+'</div>');
                                  }
                                  if (product_sale_data != undefined) {
                                    $('.vougueapps-single-product-sale').append('<div class="vogueapps-single-product-sale-data">'+product_sale_data+'</div>');
                                  }
                                }, 300);
                            }
                        })
                    }
                }
                if (window.vogueapps.page_type == 'cart') {
                    success_checkout = getUrlParameter('success_checkout');
                    if (success_checkout == 'true') {
                        jQuery('body').prepend('<div class="vogue_pageloader"><div class="vogue_loader_content"> Please Wait...Redirecting to checkout.</div></div');
                        vogueappsCheckout()
                    }
                }
                if (window.vogueapps.page_type == 'cart') {
                    $('body').on('click', '.edit_vogue_cart_option', function(e) {
                        $('body').addClass('body_fixed');
                        e.preventDefault();
                        var key = $(this).data("key");
                        var cart = window.vogueapps.cart;
                        var store_id = window.vogueapps.store_id;
                        var pid = $(this).data("product_id");
                        var variant_id = $(this).data("variant_id");
                        $("[name^='properties']").each(function() {
                            if ($(this).val() == '') {
                                $(this).attr('disabled', !0)
                            }
                        });
                        $.ajax({
                            type: 'POST',
                            url: window.vogueapps.po_url + '/store/edit_cart',
                            data: {
                                cart_data: cart,
                                item_key: key,
                                store_id: store_id,
                                variant_id: variant_id
                            },
                            cache: !1,
                            crossDomain: !0,
                            success: function(data) {
                                if (data == 'ok') {
                                    location.reload()
                                } else {
                                    $('#edit_vogue_cart_popup').html(data);
                                    $('.edit_vougue_popup').show();
                                    vogueapps_calc_options_total(pid);
                                    vogueapps_conditional_rules(pid)
                                }
                            }
                        })
                    })
                }
                jQuery("body").on("change", 'input[name="updates[]"]', function(ev) {
                    jQuery('[name="update"]').click()
                })
            }, 1);
            if (window.vogueapps.page_type == "cart") {
                $('.cart-property').each(function() {
                    var $value = $(this).find('.property_name').html();
                    if ($value.trim() == 'Option Total:') {}
                });
                $('.cart-property').find('a[href^="https://cdn.shopify.com/s/files/"][href*="/uploads/"]').each(function() {
                    if ($(this).attr("href").match(/^https:\/\/cdn\.shopify\.com\/s\/files\/([0-9]+\/)+uploads\/([0-9a-z]+)\.(png|gif|jpg|jpeg)$/i)) {
                        var e = $('<img src="' + $(this).attr("href") + '" class="vogueapps-file-upload-preview" />');
                        $(this).on("mouseenter", function(t) {
                            $("body").append(e), e.css({
                                top: t.pageY - ("relative" == $("body").css("position") ? parseInt($("body").offset().top) : 0),
                                left: t.pageX
                            })
                        }).on("mouseleave", function() {
                            e.remove()
                        }).on("mousemove", function(t) {
                            e.css({
                                top: t.pageY - ("relative" == $("body").css("position") ? parseInt($("body").offset().top) : 0),
                                left: t.pageX
                            })
                        })
                    }
                });
                var pv_cart_url = '';
                if (window.vogueapps.is_volume_discount) {
                    pv_cart_url = window.vogueapps.vd_url + "/shop/get_cart_details"
                } else if (window.vogueapps.is_product_option) {
                    pv_cart_url = window.vogueapps.po_url + "/appstore/cart/get_cart_details"
                }
                if (pv_cart_url != '') {
                    var storage_code = localStorage.getItem('discount_code');
                    if (storage_code != '') {
                        $('.vogueapps_discount_code').val(storage_code);
                        var get_cart_data = {
                            cart_data: JSON.stringify(window.vogueapps),
                            store_id: window.vogueapps.store_id,
                            discount_code: storage_code,
                            cart_collections: JSON.stringify(window.vogueapps.cart_collections)
                        }
                    } else {
                        var get_cart_data = {
                            cart_data: JSON.stringify(window.vogueapps),
                            store_id: window.vogueapps.store_id
                        }
                    }
                    $('.vogueapps_checkout').attr('disabled', !0);
                    $.ajax({
                        type: "POST",
                        url: pv_cart_url,
                        data: get_cart_data,
                        crossDomain: !0,
                        success: function(data) {
                            setTimeout(function() {
                                vogueappsDoActions(data);
                                $('.vogueapps_checkout').attr('disabled', !1)
                            }, 1)
                        }
                    })
                }
            }
            if (window.vogueapps.template_type == "not_cart") {
                $('.cart-property').each(function() {
                    var $value = $(this).find('.property_name').html();
                    if ($value.trim() == 'Option Total:') {
                        $(this).hide().remove()
                    }
                });
                $('.cart-property').find('a[href^="https://cdn.shopify.com/s/files/"][href*="/uploads/"]').each(function() {
                    if ($(this).attr("href").match(/^https:\/\/cdn\.shopify\.com\/s\/files\/([0-9]+\/)+uploads\/([0-9a-z]+)\.(png|gif|jpg|jpeg)$/i)) {
                        var e = $('<img src="' + $(this).attr("href") + '" class="vogueapps-file-upload-preview" />');
                        $(this).on("mouseenter", function(t) {
                            $("body").append(e), e.css({
                                top: t.pageY - ("relative" == $("body").css("position") ? parseInt($("body").offset().top) : 0),
                                left: t.pageX
                            })
                        }).on("mouseleave", function() {
                            e.remove()
                        }).on("mousemove", function(t) {
                            e.css({
                                top: t.pageY - ("relative" == $("body").css("position") ? parseInt($("body").offset().top) : 0),
                                left: t.pageX
                            })
                        })
                    }
                });
                var pv_cart_url = '';
                if (window.vogueapps.is_volume_discount) {
                    pv_cart_url = window.vogueapps.vd_url + "/shop/get_cart_details"
                } else if (window.vogueapps.is_product_option) {
                    pv_cart_url = window.vogueapps.po_url + "/appstore/cart/get_cart_details"
                }
                if (pv_cart_url != '') {
                    var storage_code = localStorage.getItem('discount_code');
                    if (storage_code != '') {
                        $('.vogueapps_discount_code').val(storage_code);
                        var get_cart_data = {
                            cart_data: JSON.stringify(window.vogueapps),
                            store_id: window.vogueapps.store_id,
                            discount_code: storage_code,
                            cart_collections: JSON.stringify(window.vogueapps.cart_collections)
                        }
                    } else {
                        var get_cart_data = {
                            cart_data: JSON.stringify(window.vogueapps),
                            store_id: window.vogueapps.store_id
                        }
                    }
                    $('.vogueapps_checkout').attr('disabled', !0);
                    $.ajax({
                        type: "POST",
                        url: pv_cart_url,
                        data: get_cart_data,
                        crossDomain: !0,
                        success: function(data) {
                            setTimeout(function() {
                                vogueappsDoActions(data);
                                $('.vogueapps_checkout').attr('disabled', !1)
                            }, 1)
                        }
                    })
                }
            }
        }
        if (window.vogueapps.is_product_option || window.vogueapps.is_volume_discount) {
            console.log('Vogueapps Start....');
            vogueappsStart()
        }
    }
    window.vogueappsCartPageJS = function($) {
        $('body').on('keypress', '.vogueapps_discount_code', function(e) {
            if (e.which == 13) {
                $(".vogueapps_discount_button").click()
            }
            if (e.which === 32) {
                return !1
            }
        });
        $('body').on('click', '.vogueapps_discount_button', function(e) {
            e.preventDefault();
            var code = $('.vogueapps_discount_code').val();
            if (code == '') {
                $('.vogueapps_discount_code').addClass('discount_error')
            } else {
                localStorage.setItem('discount_code', code);
                $('.vogueapps_discount_code').removeClass('discount_error');
                location.reload()
            }
        });
        $('body').on('click', '.close-tag', function(e) {
            localStorage.removeItem('discount_code');
            location.reload()
        });
        $('body').on('click', '.vogueapp_save', function(e) {
            e.preventDefault();
            if (vogueapps_validate_options($(this).data('product_id'))) {
                var line = parseInt($(this).parents('.vogueapp_popupBox').find('.vogueapp_mainContent').find('.h_index').val()) + 1;
                $.ajax({
                    type: 'POST',
                    url: '/cart/change.js',
                    data: {
                        quantity: 0,
                        line: line
                    },
                    dataType: 'json',
                    success: function(cart) {
                        if ($('.upload_cls').val() != '') {
                            $('.upload_h_cls').remove()
                        } else {
                            $('.upload_cls').remove()
                        }
                        $('#edit_vogue_cart_popup .conditional').each(function(index, element) {
                            $(this).find('.vogueapps_option_value input[type="hidden"]').val('')
                        });
                        $('#edit_vogue_cart_popup').submit()
                    }
                })
            }
        });
        $('body').on('click touchstart', '.vogueapp_close', function(e) {
            $('.edit_vougue_popup').hide();
            $('body').removeClass('body_fixed')
        })
    }
    window.vogueappsProductPageJS = function($) {
        window.vogueapps_conditional_rules = function(prod_id) {
            pass = !1;
            verify_all = Array();
            verify_any = Array();
            verified_condition = Array();
            pass_array = Array();
            $("#vogueapps_option_list_" + prod_id + " .condition_hide").removeClass("conditional");
            $("#vogueapps_option_list_" + prod_id + " .condition_show").addClass("conditional");
            $("#vogueapps_custom_options_" + prod_id + " #conditional_rules").children().each(function() {
                pass_array = Array();
                pass = !1;
                $(this).children().each(function() {
                    pass = !1;
                    var condition_rule = $(this).text();
                    var field_value;
                    if ($("#vogueapps_option_list_" + prod_id + " .option_type_id_" + $(this).attr("data-field-num")).hasClass("dd_multi_render") == !0) {
                        var aa = condition_rule;
                        if (aa.indexOf("!=") >= 0) {
                            pass = !0
                        }
                        var count = $("#vogueapps_option_list_" + prod_id + " .vogueapps_option_" + $(this).attr("data-field-num") + "_visible:visible :selected").length;
                        var ct = 1;
                        var selected_array = Array();
                        if ($("#vogueapps_option_list_" + prod_id + " .vogueapps_option_" + $(this).attr("data-field-num") + "_visible:visible :selected").length > 0) {
                            $("#vogueapps_option_list_" + prod_id + " .vogueapps_option_" + $(this).attr("data-field-num") + "_visible:visible :selected").each(function() {
                                var condition_rule = aa;
                                field_value = $(this).data("conditional-value");
                                condition_rule = condition_rule.replace("**value11**", field_value);
                                if (condition_rule.indexOf("==") >= 0) {
                                    var condition_rule = condition_rule.split("==");
                                    if (condition_rule[0] == condition_rule[1]) {
                                        pass = !0
                                    } else {
                                        pass = !1
                                    }
                                } else {
                                    var condition_rule = condition_rule.split("!=");
                                    if (condition_rule[0] != condition_rule[1]) {
                                        pass = !0
                                    } else {
                                        pass = !1
                                    }
                                }
                                selected_array.push(pass);
                                if (ct == count && count > 1) {
                                    var result = selected_array.join(' || ');
                                    result = eval(result);
                                    pass_array.push(result)
                                } else if (count == 1) {
                                    pass_array.push(pass)
                                }
                                ct = ct + 1
                            })
                        } else {
                            pass_array.push(!1)
                        }
                    } else if ($("#vogueapps_option_list_" + prod_id + " .option_type_id_" + $(this).attr("data-field-num")).hasClass("cb_render") == !0) {
                        var aa = condition_rule;
                        if (aa.indexOf("!=") >= 0) {
                            pass = !0
                        }
                        var ctt = 1;
                        var checked_array = Array();
                        var countt = $("#vogueapps_option_list_" + prod_id + " .vogueapps_option_" + $(this).attr("data-field-num") + "_visible:visible:checked").length;
                        $("#vogueapps_option_list_" + prod_id + " .vogueapps_option_" + $(this).attr("data-field-num") + "_visible:visible:checked").each(function() {
                            var condition_rule = aa;
                            field_value = $(this).data("conditional-value");
                            condition_rule = condition_rule.replace("**value11**", field_value);
                            if (condition_rule.indexOf("==") >= 0) {
                                var condition_rule = condition_rule.split("==");
                                if (condition_rule[0] == condition_rule[1]) {
                                    pass = !0
                                } else {
                                    pass = !1
                                }
                            } else {
                                var condition_rule = condition_rule.split("!=");
                                if (condition_rule[0] != condition_rule[1]) {
                                    pass = !0
                                } else {
                                    pass = !1
                                }
                            }
                            checked_array.push(pass);
                            if (ctt == countt && countt > 1) {
                                var result = checked_array.join(' || ');
                                result = eval(result);
                                pass_array.push(result)
                            } else if (countt == 1) {
                                pass_array.push(pass)
                            }
                            ctt = ctt + 1
                        })
                    } else {
                        pass = !1;
                        if ($("#vogueapps_option_list_" + prod_id + " .option_type_id_" + $(this).attr("data-field-num")).hasClass("rb_render") == !0) {
                            field_value = $("#vogueapps_option_list_" + prod_id + " .vogueapps_option_" + $(this).attr("data-field-num") + ":checked").data("conditional-value")
                        } else if ($("#vogueapps_option_list_" + prod_id + " .option_type_id_" + $(this).attr("data-field-num")).hasClass("dd_render") == !0) {
                            field_value = $("#vogueapps_option_list_" + prod_id + " #" + $(this).attr("data-field-num") + " option:selected").data("conditional-value")
                        } else if ($("#vogueapps_option_list_" + prod_id + " .option_type_id_" + $(this).attr("data-field-num")).hasClass("swatch_render") == !0) {
                            field_value = $("#vogueapps_option_list_" + prod_id + " .vogueapps_option_" + $(this).attr("data-field-num") + ".swatch_selected").data("conditional-value")
                        } else {
                            field_value = $("#vogueapps_option_list_" + prod_id + " #" + $(this).attr("data-field-num") + "").val()
                        }
                        condition_rule = condition_rule.replace("**value11**", field_value);
                        if (condition_rule.indexOf("==") >= 0) {
                            var condition_rule = condition_rule.split("==");
                            if (condition_rule[0] == condition_rule[1]) {
                                pass = !0
                            } else {
                                pass = !1
                            }
                        } else {
                            var condition_rule = condition_rule.split("!=");
                            if (condition_rule[0] != condition_rule[1]) {
                                pass = !0
                            } else {
                                pass = !1
                            }
                        }
                        pass_array.push(pass)
                    }
                });
                var type_rule = $(this).attr("data-verify-all");
                var condition_id = $(this).attr("name");
                if (type_rule == "0") {
                    var res = pass_array.join(' || ')
                } else {
                    var res = pass_array.join(' && ')
                }
                res = eval(res);
                if (res) {
                    $("#vogueapps_option_list_" + prod_id + " ." + condition_id + "_show").removeClass("conditional");
                    $("#vogueapps_option_list_" + prod_id + " ." + condition_id + "_hide").addClass("conditional");
                    $("#vogueapps_option_list_" + prod_id + " ." + condition_id + "_hide.conditional").find('.vogueapps_option_child').each(function() {
                        vogueapps_conditional_change($(this))
                    })
                } else {
                    $("#vogueapps_option_list_" + prod_id + " ." + condition_id + "_show.conditional").find('.vogueapps_option_child').each(function() {
                        vogueapps_conditional_change($(this))
                    })
                }
            });
            vogueapps_calc_options_total(prod_id)
        }
        window.vogueapps_conditional_change = function(obj) {
            if (obj.prop("type") == "select-one" || obj.prop("type") == "select-multiple") {
                if (obj.val()) {
                    obj.val('').change()
                }
            } else if (obj.prop("type") == "radio") {
                if (obj.prop("checked")) {
                    obj.prop("checked", !1);
                    obj.val('');
                    obj.parent().find('.radio_selected').removeClass("radio_selected")
                }
            } else if (obj.prop("type") == "textarea" || obj.prop("type") == "text" || obj.prop("type") == "hidden" || obj.prop("type") == "file" || obj.prop("type") == "email") {
                if (obj.val()) {
                    obj.val('').change();
                    obj.parents('.vogueapps_option_value').find('.tb_property_val').val('');
                    obj.parents('.vogueapps_option_value').find('#valid-msg').remove()
                }
            } else if (obj.prop("type") == "checkbox") {
                if (obj.prop("checked")) {
                    obj.prop("checked", !1)
                }
            } else if (obj.prop("tagName") == "DIV") {
                if (obj.find('.swatch_radio').prop("checked")) {
                    obj.find('.swatch_radio').prop("checked", !1);
                    obj.removeClass("swatch_selected")
                }
            }
        }
        window.vogueapps_calc_options_total = function(product_id) {
            var i;
            var total = 0;
            var sizePrice = 0;
            var frame_type_price = 0;
            var frame_type_value = '';
            var frame_color_value = '';
            var format = window.vogueapps.money_format;
            checked_variant = $("#vogueapps_option_list_" + product_id + ":visible .price-change:checked, #vogueapps_option_list_" + product_id + ":visible .price-change:selected, .vogueapps_swatch_option .swatch_selected,.textarea_selected,.textbox_selected,.emailbox_selected");
            for (i = 0; i < checked_variant.length; i++) {
                if (!$(checked_variant[i]).parents(".vogueapps_option").hasClass('conditional')) {
                    if ($(checked_variant[i]).attr("data-conditional-value") == 'Canvas Frame') {
                        total = Number($(checked_variant[i]).attr("data-price")) + Number(total)
                    } else if ($(checked_variant[i]).attr("data-conditional-value") == 'Floating Frame' || $(checked_variant[i]).attr("data-conditional-value") == 'Picture Frame') {
                        var size_price = $('#vogueapps_option_list_' + product_id).find("select[name^='properties'] option:selected").data('price');
                        total = size_price + total;
                        total = Number($(checked_variant[i]).attr("data-price")) + Number(total)
                    } else if ($(checked_variant[i]).attr("data-conditional-value") == 'Rolled Canvas') {
                        var size_price = $('#vogueapps_option_list_' + product_id).find("select[name^='properties'] option:selected").data('price');
                        total = size_price - Number($(checked_variant[i]).attr("data-price"));
                        total = Number(total)
                    } else {
                        total = Number($(checked_variant[i]).attr("data-price")) + Number(total)
                    }
                }
            }
            if ($('#vogueapps_option_list_' + product_id).find("select[name^='properties'] option:selected").val() != '') {
                sizePrice = $('#vogueapps_option_list_' + product_id).find("select[name^='properties'] option:selected").data('price')
            }
            if ($('#vogueapps_option_list_' + product_id).find("input[name^='properties']:checked").closest('.vogueapps_swatch_image').find('.vogueapps_option_child').data('price') != undefined) {
                frame_type_price = $('#vogueapps_option_list_' + product_id).find("input[name^='properties']:checked").closest('.vogueapps_swatch_image').find('.vogueapps_option_child').data('price');
                frame_type_value = $('#vogueapps_option_list_' + product_id).find("input[name^='properties']:checked").closest('.vogueapps_swatch_image').find('.vogueapps_option_child').data('conditional-value')
            }
            if ($('#vogueapps_option_list_' + product_id).find("input[name^='properties']:checked").closest('.vogueapps_swatch_color').find('.vogueapps_option_child').data('conditional-value') != undefined) {
                frame_color_value = $('#vogueapps_option_list_' + product_id).find("input[name^='properties']:checked").closest('.vogueapps_swatch_color').find('.vogueapps_option_child').data('conditional-value')
            }
            if (frame_type_value == 'Canvas Frame') {
                frame_type_price = 0;
                $("#vogueapps_options_" + product_id + " #raw_option_painting_type").attr('name', 'properties[Add Canvas Frame]');
                $("#vogueapps_options_" + product_id + " #raw_option_painting_type").val(format.replace("{{amount}}", frame_type_price.toFixed(2)))
            } else if (frame_type_value == 'Rolled Canvas') {
                $("#vogueapps_options_" + product_id + " #raw_option_painting_type").attr('name', 'properties[Add Rolled Canvas]');
                $("#vogueapps_options_" + product_id + " #raw_option_painting_type").val('- ' + format.replace("{{amount}}", frame_type_price.toFixed(2)))
            } else if (frame_type_value == 'Floating Frame') {
                $("#vogueapps_options_" + product_id + " #raw_option_painting_type").attr('name', 'properties[Add ' + frame_color_value + ' Floating Frame]');
                $("#vogueapps_options_" + product_id + " #raw_option_painting_type").val(format.replace("{{amount}}", frame_type_price.toFixed(2)))
            } else if (frame_type_value == 'Picture Frame') {
                $("#vogueapps_options_" + product_id + " #raw_option_painting_type").attr('name', 'properties[Add ' + frame_color_value + ' Picture Frame]');
                $("#vogueapps_options_" + product_id + " #raw_option_painting_type").val(format.replace("{{amount}}", frame_type_price.toFixed(2)))
            } else {
                $("#vogueapps_options_" + product_id + " #raw_option_painting_type").attr('name', '');
                $("#vogueapps_options_" + product_id + " #raw_option_painting_type").val(format.replace("{{amount}}", frame_type_price.toFixed(2)))
            }
            var qty = parseInt($(".qty-" + product_id).val());
            var total_price = total * qty;
            $("#calculated_option_total").html(total_price.toFixed(2));
            var sale_discount_value = $('#vogueapps_seasonal_sale').data('value');
            var sale_discount_type = $('#vogueapps_seasonal_sale').data('type');
            var total_discount = '';
            var cut_discount_price = '';
            if (sale_discount_value != '') {
                if (sale_discount_type == 'fixed_amount') {
                    total_discount = total - sale_discount_value;
                    $('.vogueapps-sale-discount').html(format.replace("{{amount}}", total_discount.toFixed(2)));
                } else if (sale_discount_type == 'percentage') {
                    cut_discount_price = (total * sale_discount_value) / 100;
                    total_discount = total - cut_discount_price;
                    $('.vogueapps-sale-discount').html(format.replace("{{amount}}", total_discount.toFixed(2)));
                }
            } else {
                $('.vogueapps-sale-discount').html('');
            }
            $("#vogueapps_options_" + product_id + " #raw_option_total").val(total.toFixed(2));
            $("#vogueapps_options_" + product_id + " #calculated_option_total").html(total_price.toFixed(2));
            $('.vogueapps-money').remove();
            if (total > 0 && 1) {
                $('#vogueapps_options_' + product_id + ' #option_total').show();
                $('#ProductPrice-product-template span.money').hide();
                $('#ProductPrice-product-quickview-template span.money').hide();
                $('.product-single__price-product-template').hide();
                var resTotal = format.replace("{{amount}}", total.toFixed(2));
                $('#ProductPrice-product-quickview-template').append('<span class="vogueapps-money">' + resTotal + '</span>');
                $("#vogueapps_options_" + product_id + " #raw_option_painting").val(format.replace("{{amount}}", sizePrice.toFixed(2)))
            } else {
                $('#vogueapps_options_' + product_id + ' #option_total').show();
                $('#ProductPrice-product-template span.money').show();
                $('.product-single__price-product-template').show();
                $('#ProductPrice-product-quickview-template span.money').show();
                $("#vogueapps_options_" + product_id + " #raw_option_painting").val(format.replace("{{amount}}", sizePrice.toFixed(2)))
            }
        }

        window.vogueapps_validate_options = function(product_id) {
            var good = !0;
            $(".vogueapps_option:visible").each(function() {
                $(this).removeClass("validation_error")
            });
            $('#vogueapps_options_' + product_id + ' #error_text').html('');
            var vogueapps_req = $("#vogueapps_option_list_" + product_id + ":visible .required:visible");
            var i;
            for (i = 0; i < vogueapps_req.length; i++) {
                if ($(vogueapps_req[i]).find("select[name^='properties']").length == 1 && !$(vogueapps_req[i]).find("select[name^='properties']").val()) {
                    $(vogueapps_req[i]).addClass("validation_error");
                    good = !1
                } else if ($(vogueapps_req[i]).find(".vogueapps_radio_option").length && !$(vogueapps_req[i]).find("input[name^='properties']:checked").length) {
                    $(vogueapps_req[i]).addClass("validation_error");
                    good = !1
                } else if ($(vogueapps_req[i]).find(".vogueapps_swatch_option").length && !$(vogueapps_req[i]).find("input[name^='properties']:checked").length) {
                    $(vogueapps_req[i]).addClass("validation_error");
                    good = !1
                } else if ($(vogueapps_req[i]).find("input[type='text']").length > 1) {
                    $(vogueapps_req[i]).find("input[type='text']").each(function() {
                        if ($(this).val() == '') {
                            $(vogueapps_req[i]).addClass("validation_error");
                            good = !1
                        }
                    })
                } else if ($(vogueapps_req[i]).find("input[type='text']").length && !$(vogueapps_req[i]).find("input[name^='properties']").val()) {
                    $(vogueapps_req[i]).addClass("validation_error");
                    good = !1
                } else if ($(vogueapps_req[i]).find("input[type='email']").length && !$(vogueapps_req[i]).find("input[name^='properties']").val()) {
                    $(vogueapps_req[i]).addClass("validation_error");
                    good = !1
                } else if ($(vogueapps_req[i]).find(".vogueapps_check_option").length && !$(vogueapps_req[i]).find("input[name^='properties']").val()) {
                    $(vogueapps_req[i]).addClass("validation_error");
                    good = !1
                } else if ($(vogueapps_req[i]).find("input[type='file']").length && !$(vogueapps_req[i]).find("input[name^='properties']").val()) {
                    $(vogueapps_req[i]).addClass("validation_error");
                    good = !1
                } else if ($(vogueapps_req[i]).hasClass("cb_render") && $(vogueapps_req[i]).find("input[type='checkbox']:checked").length && !$(vogueapps_req[i]).find("input[name^='properties']").length) {
                    $(vogueapps_req[i]).addClass("validation_error");
                    good = !1
                } else if ($(vogueapps_req[i]).find("textarea").length && !$(vogueapps_req[i]).find("input[name^='properties']").val()) {
                    $(vogueapps_req[i]).addClass("validation_error");
                    good = !1
                } else if ($(vogueapps_req[i]).find("select[multiple]").length && !$(vogueapps_req[i]).find("input[name^='properties']").val()) {
                    $(vogueapps_req[i]).addClass("validation_error");
                    good = !1
                } else {
                    $(vogueapps_req[i]).removeClass("validation_error")
                }
            }
            $("#vogueapps_option_list_" + product_id + " .pn_render.required:visible").each(function() {
                if ($(this).find("input[type='textbox']").length && ((!$(this).find("input[name^='properties']").val() && $(this).hasClass('required')))) {
                    $(this).addClass("validation_error");
                    good = !1
                } else {
                    if ($(this).find(".phone_number").hasClass('error')) {
                        $(this).addClass("validation_error");
                        good = !1
                    } else {
                        $(this).removeClass("validation_error")
                    }
                }
            });
            $("#vogueapps_option_list_" + product_id + " .et_render.required:visible").each(function() {
                if ($(this).find("input[type='email']").length && ((!$(this).find("input[name^='properties']").val() && $(this).hasClass('required')) || (($(this).find("input[type='email']").val()) != ''))) {
                    var userEmail = $(this).find("input[type='email']").val();
                    var filter = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
                    if (!filter.test(userEmail)) {
                        $(this).addClass("validation_error");
                        good = !1
                    } else {
                        $(this).removeClass("validation_error")
                    }
                }
            });
            $("#vogueapps_option_list_" + product_id + " .cb_render.required:visible").each(function() {
                if ($(this).find("input[type='checkbox']").length && $(this).hasClass('required')) {
                    if ($(this).find("input[name^='properties']").val()) {
                        if ($(this).find('.error_span').length > 0) {
                            $(this).addClass("validation_error");
                            good = !1
                        } else {
                            $(this).removeClass("validation_error")
                        }
                    } else {
                        $(this).addClass("validation_error");
                        good = !1
                    }
                } else {
                    if (!$(this).hasClass('required')) {
                        if ($(this).find('.error_span').length > 0) {
                            $(this).addClass("validation_error");
                            good = !1
                        } else {
                            $(this).removeClass("validation_error")
                        }
                    }
                }
            });
            $("#vogueapps_option_list_" + product_id + " .dd_multi_render.required:visible").each(function() {
                if ($(this).find("select[multiple]").length && $(this).hasClass('required')) {
                    if ($(this).find("input[name^='properties']").val()) {
                        if ($(this).find('.error_span').length > 0) {
                            $(this).addClass("validation_error");
                            good = !1
                        } else {
                            $(this).removeClass("validation_error")
                        }
                    } else {
                        $(this).addClass("validation_error");
                        good = !1
                    }
                } else {
                    if (!$(this).hasClass('required')) {
                        if ($(this).find('.error_span').length > 0) {
                            $(this).addClass("validation_error");
                            good = !1
                        } else {
                            $(this).removeClass("validation_error")
                        }
                    }
                }
            });
            return good
        }
        window.vogueapps_validate_single_option = function(option_type_id, option_type) {
            if (option_type == 'dd_render') {
                if ($('.' + option_type_id).find("select[name^='properties']").length == 1 && !$('.' + option_type_id).find("select[name^='properties']").val() && $('.' + option_type_id).hasClass('required')) {
                    $('.' + option_type_id).addClass("validation_error")
                } else {
                    $('.' + option_type_id).removeClass("validation_error")
                }
                var first_option = $('.' + option_type_id).find("select[name^='properties'] option:selected").val();
                if (first_option != '') {
                    var variant_img_id = $('.' + option_type_id).find("select[name^='properties'] option:selected").data('variant-img');
                    if (variant_img_id != '') {
                        $('.thumbnails-wrapper, .product-single__photo-wrapper, .product-images-wrapper .product-images-content').hide();
                        setTimeout(function() {
                            $('.vougueapps-single-product-image').find('.zoomImg').remove();
                            $('.vogue-img-zoom').attr('src', variant_img_id);
                            $('.vogue-img-zoom').attr('data-zoom', variant_img_id);
                            $('#frame_type_img').val(variant_img_id)
                        }, 300);
                        setTimeout(function() {
                            $('.vogue-img-zoom').css('display', 'block');
                            $('.vogue-img-zoom').parent().zoom({
                                url: this.src,
                                callback: function() {}
                            });
                        }, 500)
                    }
                }
                var size_option = $('.' + option_type_id).find("select[name^='properties'] option:selected").data('size-cond');
                var size_stock = $('.' + option_type_id).find("select[name^='properties'] option:selected").data('stock');
                if (size_stock != '') {
                    $('.arrival_time').html(size_stock)
                }
                var size_variation = $('.' + option_type_id).find("select[name^='properties'] option:selected").data('variation-cond');
                $('.vogueapps_product_variations option').filter(function() {
                    return ($(this).data('title') == size_variation)
                }).prop('selected', !0);
                $('.vogueapps_frame_type .vogueapps_swatch_option').hide();
                $('.vogueapps_frame_type .vogueapps_swatch_option.no-frame-type').css('display', 'inline-block');
                var frame_type_size_val_arr = [];
                $(".vogueapps_frame_type .vogueapps_swatch_option").each(function() {
                    var size_val = $(this).data("size-value");
                    if (size_option != size_val) {} else {
                        frame_type_size_val_arr.push(size_val);
                        $(this).css('display', 'inline-block')
                    }
                });
                if (frame_type_size_val_arr.length === 0) {
                    $('.vogueapps_no_frame_text').show()
                } else {
                    $('.vogueapps_no_frame_text').hide()
                }
                $('.vogueapps_frame_color .vogueapps_swatch_option').hide();
                $('.vogueapps_frame_color .vogueapps_swatch_option.no-frame-color').css('display', 'inline-block');
                var frame_color_size_val_arr = [];
                $(".vogueapps_frame_color .vogueapps_swatch_option").each(function() {
                    var size_val = $(this).data("size-value");
                    if (size_option != size_val) {} else {
                        frame_color_size_val_arr.push(size_val);
                        $('.vogueapps_frame_color .vogueapps_swatch_option[data-size-value="80_80_size"][data-frame-value="Floating Frame"]').css('display', 'inline-block')
                    }
                });
                if (frame_color_size_val_arr.length === 0) {
                    $('.vogueapps_no_color_text').show()
                } else {
                    $('.vogueapps_no_color_text').hide()
                }
                $('.vogueapps_swatch_option .swatch_radio').prop('checked', !1);
                $('.vogueapps_swatch_option .swatch_radio').parents('.swatch_render').find('.swatch_selected').removeClass('swatch_selected');
                $('.vogueapps_frame_type .vogueapps_swatch_option[data-size-value="' + size_option + '"][data-frame-cond="Canvas Frame"] .swatch_radio').trigger('click');
                $('.vogueapps_frame_type .vogueapps_swatch_option[data-size-value="' + size_option + '"][data-frame-cond="Canvas Frame"] .swatch_radio').parent('.vogueapps_option_child').addClass('swatch_selected')
            } else if (option_type == 'dd_multi_render') {
                if ($('.' + option_type_id).find("select[multiple]").length && !$('.' + option_type_id).find("input[name^='properties']").val() && $('.' + option_type_id).hasClass('required')) {
                    $('.' + option_type_id).addClass("validation_error")
                } else {
                    $('.' + option_type_id).removeClass("validation_error")
                }
            } else if (option_type == 'swatch_render') {
                if ($('.' + option_type_id).find(".vogueapps_swatch_option").length && !$('.' + option_type_id).find("input[name^='properties']:checked").length && $('.' + option_type_id).hasClass('required')) {
                    $('.' + option_type_id).addClass("validation_error")
                } else {
                    $('.' + option_type_id).removeClass("validation_error")
                }
                var size_cond = $('.' + option_type_id).find("input[name^='properties']:checked").closest('.vogueapps_swatch_option').data('size-value');
                var frame_cond = $('.' + option_type_id).find("input[name^='properties']:checked").closest('.vogueapps_swatch_option').data('frame-cond');
                var frame_type_val = $('.' + option_type_id).find("input[name^='properties']:checked").val();
                var variation_cond = $('.' + option_type_id).find("input[name^='properties']:checked").closest('.vogueapps_swatch_option').data('variation-cond');
                var size_ex_cond = $('.' + option_type_id).find("input[name^='properties']:checked").closest('.vogueapps_swatch_option').data('size-ex-value');
                if (frame_type_val == 'Floating Frame' || frame_type_val == 'Picture Frame') {
                    $('.vogueapps_product_variations option').filter(function() {
                        return ($(this).data('title') == variation_cond)
                    }).prop('selected', !0)
                } else if (frame_type_val == 'Rolled Canvas') {
                  $('.arrival_time').html('6-8 weeks');
                    $('.vogueapps_product_variations option').filter(function() {
                        return ($(this).data('title') == variation_cond)
                    }).prop('selected', !0)
                } else {
                  var size_stock = $('.vogueapps_option_set').find("select[name^='properties'] option:selected").data('stock');
                  $('.arrival_time').html(size_stock);

                    $('.vogueapps_product_variations option').filter(function() {
                        return ($(this).data('title') == size_ex_cond)
                    }).prop('selected', !0)
                }
                $('.vogueapps_frame_color .vogueapps_swatch_option').hide();
                $('.vogueapps_frame_color .vogueapps_swatch_option.no-frame-color').css('display', 'inline-block');
                var ratio_val = $('.vogueapps_canvas_ratio').find(".vogueapps_radio_option").find('.vogueapps_option_child:checked').data('ratio-cond');
                ratio_val = ratio_val.trim();
                var frame_color_ratio_val_arr = [];
                $(".vogueapps_frame_color .vogueapps_swatch_option").each(function() {
                    var ratio_value = $(this).data("ratio-value");
                    var frame_value = $(this).data("frame-value");
                    if (ratio_val == ratio_value && frame_cond == frame_value) {
                        frame_color_ratio_val_arr.push(ratio_value);
                        $(this).css('display', 'inline-block');
                        $('.vogueapps_frame_color .vogueapps_swatch_option[data-ratio-value="' + ratio_value + '"][data-frame-value="' + frame_value + '"][data-color-value="White"] .swatch_radio').trigger('click');
                        $('.vogueapps_frame_color .vogueapps_swatch_option[data-ratio-value="' + ratio_value + '"][data-frame-value="' + frame_value + '"][data-color-value="White"] .swatch_radio').parent('.vogueapps_option_child').addClass('swatch_selected')
                    } else {}
                });
                if (frame_color_ratio_val_arr.length === 0) {
                    $('.vogueapps_no_color_text').show();
                    $('.vogueapps_frame_color .vogueapps_swatch_option .swatch_radio').prop('checked', !1)
                } else {
                    $('.vogueapps_no_color_text').hide()
                }
                var variant_img_id = $('.' + option_type_id).find("input[name^='properties']:checked").data('variant-img');
                if (variant_img_id != '') {
                    $('.thumbnails-wrapper, .product-single__photo-wrapper, .product-images-wrapper .product-images-content').hide();
                    setTimeout(function() {
                        $('.vougueapps-single-product-image').find('.zoomImg').remove();
                        $('.vogue-img-zoom').attr('src', variant_img_id);
                        $('.vogue-img-zoom').attr('data-zoom', variant_img_id);
                        $('#frame_type_img').val(variant_img_id)
                    }, 300);
                    setTimeout(function() {
                        $('.vogue-img-zoom').css('display', 'block');
                        $('.vogue-img-zoom').parent().zoom({
                            url: this.src,
                            callback: function() {}
                        });
                    }, 500)
                }
            } else if (option_type == 'swatch_render_color') {
                if ($('.' + option_type_id).find(".vogueapps_swatch_option").length && !$('.' + option_type_id).find("input[name^='properties']:checked").length && $('.' + option_type_id).hasClass('required')) {
                    $('.' + option_type_id).addClass("validation_error")
                } else {
                    $('.' + option_type_id).removeClass("validation_error")
                }
                var variant_img_id = $('.' + option_type_id).find("input[name^='properties']:checked").data('variant-img');
                if (variant_img_id != '') {
                    $('.thumbnails-wrapper, .product-single__photo-wrapper, .product-images-wrapper .product-images-content').hide();
                    setTimeout(function() {
                        $('.vougueapps-single-product-image').find('.zoomImg').remove();
                        $('.vogue-img-zoom').attr('src', variant_img_id);
                        $('.vogue-img-zoom').attr('data-zoom', variant_img_id);
                        $('#frame_type_img').val(variant_img_id)
                    }, 300);
                    setTimeout(function() {
                        $('.vogue-img-zoom').css('display', 'block');
                        $('.vogue-img-zoom').parent().zoom({
                            url: this.src,
                            callback: function() {}
                        });
                    }, 500)
                }
            } else if (option_type == 'cb_render') {
                if ($('.' + option_type_id).find(".vogueapps_check_option").length && !$('.' + option_type_id).find("input[name^='properties']").val() && $('.' + option_type_id).hasClass('required')) {
                    $('.' + option_type_id).addClass("validation_error")
                } else {
                    $('.' + option_type_id).removeClass("validation_error")
                }
            } else if (option_type == 'tb_render') {
                if ($('.' + option_type_id).find("input[type='text']").length && !$('.' + option_type_id).find("input[name^='properties']").val() && $('.' + option_type_id).hasClass('required')) {
                    $('.' + option_type_id).addClass("validation_error")
                } else {
                    $('.' + option_type_id).removeClass("validation_error")
                }
            } else if (option_type == 'ta_render') {
                if ($('.' + option_type_id).find("textarea").length && !$('.' + option_type_id).find("input[name^='properties']").val() && $('.' + option_type_id).hasClass('required')) {
                    $('.' + option_type_id).addClass("validation_error")
                } else {
                    $('.' + option_type_id).removeClass("validation_error")
                }
            } else if (option_type == 'rb_render') {
                if ($('.' + option_type_id).find(".vogueapps_radio_option").length && !$('.' + option_type_id).find("input[name^='properties']:checked").length && $('.' + option_type_id).hasClass('required')) {
                    $('.' + option_type_id).addClass("validation_error")
                } else {
                    $('.' + option_type_id).removeClass("validation_error")
                }
                setTimeout(function() {
                    var variant_img_id = $('.' + option_type_id).find("input[name^='properties']:checked").data('variant-img');
                    if (variant_img_id != '') {
                        $('.thumbnails-wrapper, .product-single__photo-wrapper, .product-images-wrapper .product-images-content').hide();
                        setTimeout(function() {
                            $('.vougueapps-single-product-image').find('.zoomImg').remove();
                            $('.vogue-img-zoom').attr('src', variant_img_id);
                            $('.vogue-img-zoom').attr('data-zoom', variant_img_id);
                            $('#frame_type_img').val(variant_img_id)
                        }, 300);
                        setTimeout(function() {
                            $('.vogue-img-zoom').css('display', 'block');
                            $('.vogue-img-zoom').parent().zoom({
                                url: this.src,
                                callback: function() {}
                            });
                        }, 500)
                    }
                }, 50);
                var ratio_val = $('.' + option_type_id).find(".vogueapps_radio_option").find('.vogueapps_option_child:checked').data('ratio-cond');
                //console.log(ratio_val);
                //ratio_val = ratio_val.trim();
                var ratio_val123 = $('.' + option_type_id).find(".radio_selected").parent('.vogueapps_radio_option').find('.vogueapps_option_child').data('ratio-cond');
                if (ratio_val123 == '1_1_ratio') {
                    //$('.vogueapps_canvas_size .vogueapps_dd').val('80 x 80').trigger('change');
                } else if (ratio_val123 == '4_3_ratio') {
                    //$('.vogueapps_canvas_size .vogueapps_dd').val('80 x 110').trigger('change');
                } else if (ratio_val123 == '16_9_ratio') {
                    //$('.vogueapps_canvas_size .vogueapps_dd').val('80 x 140').trigger('change');
                }

                if (ratio_val != '') {
                    $('.vogueapps_swatch_option').hide();
                    $('.vogueapps_frame_type .vogueapps_swatch_option.no-frame-type').css('display', 'inline-block');
                    $('.vogueapps_frame_color .vogueapps_swatch_option.no-frame-color').css('display', 'inline-block');
                    $('.vogueapps_frame_type .vogueapps_swatch_option[data-size-value="80_80_size"]').css('display', 'inline-block');
                    $('.vogueapps_frame_color .vogueapps_swatch_option[data-ratio-value="1_1_ratio"][data-frame-value="Floating Frame"]').css('display', 'inline-block');
                    $('.vogueapps_swatch_option .swatch_radio').prop('checked', !1);
                    $('.vogueapps_swatch_option .swatch_radio').parents('.swatch_render').find('.swatch_selected').removeClass('swatch_selected')
                }
            } else if (option_type == 'fu_render') {
                if ($('.' + option_type_id).find("input[type='file']").length && !$('.' + option_type_id).find("input[name^='properties']").val() && $('.' + option_type_id).hasClass('required')) {
                    $('.' + option_type_id).addClass("validation_error")
                } else {
                    $('.' + option_type_id).removeClass("validation_error")
                }
            } else if (option_type == 'pn_render') {
                if ($('.' + option_type_id).find("input[type='textbox']").length && ((!$('.' + option_type_id).find("input[name^='properties']").val() && $('.' + option_type_id).hasClass('required')))) {
                    $('.' + option_type_id).addClass("validation_error");
                    good = !1
                } else {
                    if ($('.' + option_type_id).find(".phone_number").hasClass('error')) {
                        $('.' + option_type_id).addClass("validation_error");
                        good = !1
                    } else {
                        $('.' + option_type_id).removeClass("validation_error")
                    }
                }
            } else if (option_type == 'et_render') {
                if ($('.' + option_type_id).find("input[type='email']").length && ((!$('.' + option_type_id).find("input[name^='properties']").val() && $('.' + option_type_id).hasClass('required')) || (($('.' + option_type_id).find("input[type='email']").val().length) != ''))) {
                    var userEmail = $('.' + option_type_id).find("input[type='email']").val();
                    var filter = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
                    if (!filter.test(userEmail)) {
                        $('.' + option_type_id).addClass("validation_error")
                    } else {
                        $('.' + option_type_id).removeClass("validation_error")
                    }
                }
            }
        }
        window.vogueapps_b64toBlob = function(b64Data, contentType, sliceSize) {
            contentType = contentType || '';
            sliceSize = sliceSize || 512;
            var byteCharacters = atob(b64Data);
            var byteArrays = [];
            for (var offset = 0; offset < byteCharacters.length; offset += sliceSize) {
                var slice = byteCharacters.slice(offset, offset + sliceSize);
                var byteNumbers = new Array(slice.length);
                for (var i = 0; i < slice.length; i++) {
                    byteNumbers[i] = slice.charCodeAt(i)
                }
                var byteArray = new Uint8Array(byteNumbers);
                byteArrays.push(byteArray)
            }
            var blob = new Blob(byteArrays, {
                type: contentType
            });
            return blob
        }
        window.vogueapps_AddToCart = function(vogueapps_product_id) {
            var vogue_flag = 0;
            $("body").on('click', '.vogueapps_submit_cart', function(e) {
                e.preventDefault();
                var re = $(this);
                var form = re.closest("form");
                formdata = new FormData(form[0]);
                var isRatioChecked = !1;
                $('.vogueapps_canvas_ratio input[type="radio"]').each(function() {
                    if ($(this).is(':checked')) {
                        isRatioChecked = !0
                    }
                });
                var isFrameTypeChecked = !1;
                $('.vogueapps_frame_type input[type="radio"]').each(function() {
                    if ($(this).is(':checked')) {
                        isFrameTypeChecked = !0
                    }
                });
                isSizeSelected = $(".vogueapps_canvas_size select[name^='properties'] option:selected").val();
                if (!isRatioChecked) {
                    alert('Please select Canvas Ratio');
                    return !1
                } else if (isSizeSelected == '') {
                    alert('Please select Canvas Size');
                    return !1
                } else if (!isFrameTypeChecked) {
                    alert('Please select Frame Type');
                    return !1
                } else {
                    if (vogue_flag == 0) {
                        var res = !0;
                        if (vogueapps_validate_options(vogueapps_product_id)) {
                            $("[name^='properties']").each(function() {
                                if ($(this).val() == '') {}
                            });
                            vogue_flag = 0;
                            var btn_text = $('.vogueapps_submit_cart').find('span').first().text();
                            $('.vogueapps_submit_cart').find('span').first().text("Please Wait...");
                            $('.vogueapps_submit_cart').attr('disabled', 'disabled');
                            re.addClass("loading");
                            var frame_img = $('#frame_type_img').val();
                            var raw_option_total = $('#raw_option_total').val();
                            var form_data = $(form).serializeArray();
                            form_data.push({
                                name: 'store_id',
                                value: window.vogueapps.store_id
                            });
                            form_data.push({
                                name: 'theme_id',
                                value: window.vogueapps.theme_id
                            });
                            form_data.push({
                                name: 'product_id',
                                value: vogueapps_product_id
                            });
                            form_data.push({
                                name: 'raw_option_total',
                                value: raw_option_total
                            });
                            $.ajax({
                                type: "POST",
                                url: window.vogueapps.po_url + "/appstore/add_to_cart",
                                data: form_data,
                                crossDomain: !0,
                                dataType: "json"
                            }).done(function(e) {
                                if ("success" == e.status) {
                                    var i = new FormData;
                                    var new_size_value = '';
                                    i.append("id", e.data.id);
                                    i.append("quantity", e.data.quantity);
                                    $.each(e.data.properties, function(key, val) {
                                        if (key == 'Canvas Size') {
                                            var sizeArray = val.split('x');
                                            new_size_value = sizeArray[0].trim()+'cm x '+sizeArray[1].trim()+'cm';
                                            val = new_size_value;
                                        }
                                        i.append("properties[" + key + "]", val)
                                    });
                                    $.ajax({
                                        method: "POST",
                                        type: "POST",
                                        url: "/cart/add.js",
                                        cache: !1,
                                        contentType: !1,
                                        processData: !1,
                                        global: !1,
                                        dataType: "json",
                                        data: i
                                    }).done(function() {
                                        setTimeout(function() {
                                            re.removeClass("loading");
                                            $('.vogueapps_submit_cart').find('span').first().text(btn_text);
                                            $('.vogueapps_submit_cart').removeAttr('disabled');
                                            window.location.href = "/cart"
                                        }, 2000)
                                    }).fail(function(jqXHR, exception) {
                                        re.removeClass("loading");
                                        $('.vogueapps_submit_cart').find('span').first().text(btn_text);
                                        $('.vogueapps_submit_cart').removeAttr('disabled');
                                        //alert("An error occurred while adding the product to the cart.");
                                        var err = eval("(" + jqXHR.responseText + ")");
                      alert(err.description);
                                    })
                                } else {
                                    re.removeClass("loading");
                                    $('.vogueapps_submit_cart').find('span').first().text(btn_text);
                                    $('.vogueapps_submit_cart').removeAttr('disabled');
                                    alert(e.message)
                                }
                            }).fail(function() {
                                re.removeClass("loading");
                                $('.vogueapps_submit_cart').find('span').first().text(btn_text);
                                $('.vogueapps_submit_cart').removeAttr('disabled');
                                alert("An error occurred while adding the product to the cart.")
                            })
                        }
                    }
                }
            })
        }
        vogueapps_AddToCart(window.vogueapps.product_id);
        /*$("body").on('click', '.quantity .q_up', function(e) {
            e.preventDefault();
            var y = $(this).data("product_id");
            var b = parseInt($(".qty-" + y).val());
            var qty = b;
            var total = $('#raw_option_total').val();
            var total_price = total * qty;
            $("#calculated_option_total").html(total_price.toFixed(2))
        });
        $("body").on('click', '.quantity .q_down', function(e) {
            e.preventDefault();
            var y = $(this).data("product_id");
            var b = parseInt($(".qty-" + y).val());
            var qty = b;
            var total = $('#raw_option_total').val();
            var total_price = total * qty;
            $("#calculated_option_total").html(total_price.toFixed(2))
        });*/
        var vogue_checkout_flag = 0;
        $("body").on('click', '.vogueapps_submit_checkout', function(e) {
            e.preventDefault();
            var form = $(this).closest("form");
            formdata = new FormData(form[0]);
            pv_draft_checkout_url = window.vogueapps.po_url + "/appstore/checkout/draft_order";
            var isRatioChecked = !1;
            $('.vogueapps_canvas_ratio input[type="radio"]').each(function() {
                if ($(this).is(':checked')) {
                    isRatioChecked = !0
                }
            });
            var isFrameTypeChecked = !1;
            $('.vogueapps_frame_type input[type="radio"]').each(function() {
                if ($(this).is(':checked')) {
                    isFrameTypeChecked = !0
                }
            });
            isSizeSelected = $(".vogueapps_canvas_size select[name^='properties'] option:selected").val();
            if (!isRatioChecked) {
                alert('Please select Canvas Ratio');
                return !1
            } else if (isSizeSelected == '') {
                alert('Please select Canvas Size');
                return !1
            } else if (!isFrameTypeChecked) {
                alert('Please select Frame Type');
                return !1
            } else {
                if (vogue_checkout_flag == 0) {
                    var res = !0;
                    if (vogueapps_validate_options(window.vogueapps.product_id)) {
                        $("[name^='properties']").each(function() {
                            if ($(this).val() == '') {}
                        });
                        vogue_checkout_flag = 0;
                        var btn_text = $('.vogueapps_submit_checkout').text();
                        $('.vogueapps_submit_checkout').text("Please Wait...");
                        $('.vogueapps_submit_checkout').attr('disabled', 'disabled');
                        var frame_img = $('#frame_type_img').val();
                        var raw_option_total = $('#raw_option_total').val();
                        var form_data = $(form).serializeArray();
                        form_data.push({
                            name: 'store_id',
                            value: window.vogueapps.store_id
                        });
                        form_data.push({
                            name: 'theme_id',
                            value: window.vogueapps.theme_id
                        });
                        form_data.push({
                            name: 'product_id',
                            value: window.vogueapps.product_id
                        });
                        form_data.push({
                            name: 'raw_option_total',
                            value: raw_option_total
                        });
                        $.ajax({
                            type: "POST",
                            url: window.vogueapps.po_url + "/appstore/add_to_cart_checkout",
                            data: form_data,
                            crossDomain: !0,
                            dataType: "json"
                        }).done(function(e) {
                            if ("success" == e.status) {
                                var i = new FormData;
                                    var new_size_value = '';
                                    i.append("id", e.data.id);
                                    i.append("quantity", e.data.quantity);
                                    $.each(e.data.properties, function(key, val) {
                                        if (key == 'Canvas Size') {
                                            var sizeArray = val.split('x');
                                            new_size_value = sizeArray[0].trim()+'cm x '+sizeArray[1].trim()+'cm';
                                            val = new_size_value;
                                        }
                                        i.append("properties[" + key + "]", val)
                                    });
                                    $.ajax({
                                        method: "POST",
                                        type: "POST",
                                        url: "/cart/clear.js",
                                        cache: !1,
                                        contentType: !1,
                                        processData: !1,
                                        global: !1,
                                        dataType: "json",
                                    });
                                    $.ajax({
                                        method: "POST",
                                        type: "POST",
                                        url: "/cart/add.js",
                                        cache: !1,
                                        contentType: !1,
                                        processData: !1,
                                        global: !1,
                                        dataType: "json",
                                        data: i
                                    });
                                $.ajax({
                                    type: "POST",
                                    url: pv_draft_checkout_url,
                                    data: {
                                        cart_json: JSON.stringify(e.data),
                                        store_id: window.vogueapps.store_id
                                    },
                                    crossDomain: !0,
                                }).done(function(res) {
                                    $('.vogueapps_submit_checkout').text(btn_text);
                                    $('.vogueapps_submit_checkout').removeAttr('disabled');
                                    if (res.is_sale) {
                                window.location.href = res.url;
                                //window.location.href = "/checkout";
                            } else {
                                window.location.href = "/checkout";
                            }
                                    localStorage.removeItem('discount_code')
                                }).fail(function() {
                                    $('.vogueapps_submit_checkout').text(btn_text);
                                    $('.vogueapps_submit_checkout').removeAttr('disabled');
                                    alert("An error occurred while adding the product to the cart.")
                                })
                            } else {
                                $('.vogueapps_submit_checkout').text(btn_text);
                                $('.vogueapps_submit_checkout').removeAttr('disabled');
                                alert(e.message)
                            }
                        }).fail(function() {
                            $('.vogueapps_submit_checkout').text(btn_text);
                            $('.vogueapps_submit_checkout').removeAttr('disabled');
                            alert("An error occurred while adding the product to the cart.")
                        })
                    }
                }
            }
        })
    }
}
startVogueApp();
if (typeof window.vogueapps !== 'undefined') {
    if ((typeof(jQuery) == 'undefined') || (parseInt(jQuery.fn.jquery) == 3 && parseFloat(jQuery.fn.jquery.replace(/^1\./, "")) < 2.1)) {
        console.log('Inside if in voguecode');
        vogueappsLoadScript('//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js', function() {
            jQuery321 = jQuery.noConflict(!0);
            checkAppInstalled(jQuery321)
        })
    } else {
        console.log('Inside else in voguecode');
        checkAppInstalled(jQuery)
    }
}
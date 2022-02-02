/**
 * @author Infowind
 */
jQuery(function($) {
	
    $(document).ready(function(){
        var focus_erorr_element = function(){
            $('form').animate({
                scrollTop: $('.errorr:first').offset().top
            }, 500);
        }

        $("body").on('focusout', '.errorr:not(.opt_val),.error', function(event) {
            var val=$(this).val();
            if($.trim(val).length == 0){
                $(this).addClass('errorr');
                focus_erorr_element();
            }
            else{
                $(this).removeClass('errorr');
                $(this).removeClass('error');
            }
        });

        $(".applies_to").on("change", function(){
            var apply_val = $(this).val();

            if(apply_val === 'all_products'){
                // no ajax
                $(".collection-product-list").html('');
                $('.product-filters-block').hide();
                $('.collection-filters-block').hide();
                $('.collections, .products').remove();
            } else if(apply_val === 'specific_collections') {
                // get collections list
                $('.product-filters-block').hide();
                $('.collection-filters-block').show();
                $('.selected_collections tbody').html('<tr class="no_collections_selected"><td>No products selected</td><td></td></tr>');
                $('.selected_collections_count').html('Showing 0' + ' entries');
            } else if(apply_val === 'specific_products') {
                // get products list
                $('.product-filters-block').show();
                $('.collection-filters-block').hide();
                $('.selected_products tbody').html('<tr class="no_products_selected"><td>No products selected</td><td></td></tr>');
                $('.selected_products_count').html('Showing 0' + ' entries');
            } else {
                // no ajax
                $(".collection-product-list").html('');
            }
            
        });

        $("body").on('click', '.add-discount-btn', function(event) {
            event.preventDefault();
            flag = 0;
            var assign_val_array = [];
            var discount_code = $('#discount_code').val();
            if($.trim(discount_code).length == 0){
                $('#discount_code').addClass('errorr');
            }else{
                $('#discount_code').removeClass('errorr');
            }

            var discount_value = $('#discount_value').val();
            if($.trim(discount_value).length == 0){
                $('#discount_value').addClass('errorr');
            }else{
                $('#discount_value').removeClass('errorr');
            }

            var applies_to = $('.applies_to').val();
            if(applies_to == 'specific_collections') {
                if($('.no_collections_selected').length == 1){
                    $('.selected_collections tbody').html("<tr class='no_objects_error no_collections_error errorr'><td class='errorr'>*Select collections first</td><td></td></tr>");
                    flag = 1;
                }
            }

            if(applies_to == 'specific_products') {
                if($('.no_products_selected').length == 1){
                    $('.selected_products tbody').html("<tr class='no_objects_error no_products_error errorr'><td class='errorr'>*Select products first</td><td></td></tr>");
                    flag = 1;
                }
            }

            if ($('.errorr').length > 0){
                focus_erorr_element();
            }

            if(flag==0 && $('.errorr').length == 0){
                $('.submit-discount-form').submit();
            } else {
                focus_erorr_element();
            }
        });

        //  rule delete
        $(".rule-delete").on("click", function(){
            $('#modal2').modal('show');
	        var rule_id = $(this).data('rule-id');

            $('#modal2').find("#rule_id").val(rule_id);
        });

        //  Step image modal
        $(".step-image-modal").on("click", function(){
            $('#modal3').modal('show');
	        var image_url = $(this).data('step-url');

            $('#modal3').find(".step_image").attr('src', image_url);
        });

        // Remove collection
        $(".remove_collection").on("click", function(){
            $(this).closest('li').remove();
        });

        // Remove product
        $(".remove_product").on("click", function(){
            $(this).closest('li').remove();
        });
        
    });

});

//document.getElementById("field1").addEventListener("keypress", forceKeyPressUppercase, false);

function forceKeyPressUppercase(e)
{
var charInput = e.keyCode;
if((charInput >= 97) && (charInput <= 122)) { // lowercase
    if(!e.ctrlKey && !e.metaKey && !e.altKey) { // no modifier key
    var newChar = charInput - 32;
    var start = e.target.selectionStart;
    var end = e.target.selectionEnd;
    e.target.value = e.target.value.substring(0, start) + String.fromCharCode(newChar) + e.target.value.substring(end);
    e.target.setSelectionRange(start+1, start+1);
    e.preventDefault();
    }
}
}

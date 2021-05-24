/**
 * @author Infowind
 */
jQuery(function($) {
	
    $(document).ready(function(){
        $(".applies_to").on("change", function(){
            var apply_val = $(this).val();

            if(apply_val === 'all_products'){
                // no ajax
                $(".collection-product-list").html('');
            } else if(apply_val === 'specific_collections') {
                // get collections list
                var old_collection_ids = '';

                old_collection_ids = $('option:selected', this).attr('data-collection-ids');

                if(old_collection_ids !== ''){
                    old_collection_ids = old_collection_ids;
                } else {
                    old_collection_ids = '';
                }

                $.ajax({
                    type:"POST",
                    url: APP_URL+'/discount/get-all-collections',
                    data : 'apply_val='+apply_val+'&old_collection_ids='+old_collection_ids,
                    success:function(response){
                        $(".collection-product-list").html('');
                        if(response !== ''){
                            $(".collection-product-list").removeClass('hide');
                            $(".collection-product-list").append(response);
                        }
                        
                    }
                });
            } else if(apply_val === 'specific_products') {
                // get products list
                var old_product_ids = '';

                old_product_ids = $('option:selected', this).attr('data-product-ids');

                if(old_product_ids !== ''){
                    old_product_ids = old_product_ids;
                } else {
                    old_product_ids = '';
                }

                $.ajax({
                    type:"POST",
                    url: APP_URL+'/discount/get-all-products',
                    data : 'apply_val='+apply_val+'&old_product_ids='+old_product_ids,
                    success:function(response){
                        $(".collection-product-list").html('');
                        if(response !== ''){
                            $(".collection-product-list").removeClass('hide');
                            $(".collection-product-list").append(response);
                        }
                        
                    }
                });
            } else {
                // no ajax
                $(".collection-product-list").html('');
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

document.getElementById("field1").addEventListener("keypress", forceKeyPressUppercase, false);

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

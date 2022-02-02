<?php
header('Content-Type: application/liquid');
header("Content-Length: 2400");
//echo "hasd";
if(isset($identifier)){
    //echo $identifier;
?>    

<script type="text/javascript">
  
  var getUrlParameter = function getUrlParameter(sParam) {
    var sPageURL = window.location.search.substring(1),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return typeof sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
        }
    }
    return false;
};
  
  
var identifier = "<?php echo $identifier; ?>";

window.opener.location.href = '/cart?identifier='+identifier;
window.close();
</script>

<?php }
?>
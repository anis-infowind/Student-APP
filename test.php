<?php

$ch = curl_init("https://www.nseindia.com/");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
$content = curl_exec($ch);
curl_close($ch);

var_dump($content);die;
    
if(!empty($content)){
    //
} else {
    echo "else";
}

?>
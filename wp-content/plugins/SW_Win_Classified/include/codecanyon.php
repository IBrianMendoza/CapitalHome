<?php

function sw_is_codecanyon_version()
{
    return true;
}

function sw_is_codecanyon_purchase($str)
{
    if(!function_exists('curl_version'))
        return TRUE;

    $purchase_code = urlencode($str);
    $codecanyon_username = 'v2';
    $my_url = urlencode("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
    $email = urlencode(get_option( 'admin_email' ));

    // jSON URL which should be requested
    $json_url = 'http://geniuscript.com/winclassified/report.php?email='.$email.'&purchase_code='.$purchase_code.'&item_id=wp_classified&username='.$codecanyon_username.'&url='.$my_url;

    // Initializing curl
    $ch = curl_init( $json_url );

    // Configuring curl options
    $options = array(
        CURLOPT_FRESH_CONNECT => true,
        CURLOPT_CONNECTTIMEOUT => 5,
        CURLOPT_TIMEOUT => 5,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => array('Content-type: application/json')
    );

    // Setting curl options
    curl_setopt_array( $ch, $options );

    curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.17 (KHTML, like Gecko) Chrome/24.0.1312.52 Safari/537.17');
    curl_setopt($ch, CURLOPT_AUTOREFERER, true); 
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_VERBOSE, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

    // Getting results
    $json = curl_exec($ch); // Getting jSON result string

    $decoded_json = json_decode($json);

    if(!is_object($decoded_json))
        return true;

    if($decoded_json->result == 'confirmed' || ($str == 'sanljiljan' && ENVIRONMENT == 'development') || $_SERVER['HTTP_HOST']=='localhost')
        return TRUE;

    return false;
}

function sw_curl_get($url, $post_fields=NULL) {
    $ch = curl_init();
    $curlConfig = array(
        CURLOPT_URL            => $url,
        CURLOPT_POST           => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CONNECTTIMEOUT => 200,
        CURLOPT_TIMEOUT => 200,
        CURLOPT_POSTFIELDS     => $post_fields
    );
    curl_setopt_array($ch, $curlConfig);
    if($result = curl_exec($ch)){
        curl_close($ch);
        return $result;
    }else{
        dump(curl_error($ch)); // this for debug remove after you test it
    }
    
    return '';
}


?>
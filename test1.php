<?php
ini_set('max_execution_time', 30);
$hurl = 'https://skanray-access.com/iCRM_8be7f6c4bcc9/application/assets/images/skanray_new.png';
echo $hurl;
$url = 'http://localhost/live/icrm4/application/assets/images/skanray_new.png';
function get_data($url) {
	$ch = curl_init();
	$timeout = 5;
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	$data = curl_exec($ch);
	curl_close($ch);
	return $data;
}
//$file = file_get_contents($hurl);

function getImageRawData($image_url) {
  if (function_exists('curl_init')) {
    $opts                                   = array();
    $http_headers                           = array();
    $http_headers[]                         = 'Expect:';
    
    $opts[CURLOPT_URL]                      = $image_url;
    $opts[CURLOPT_HTTPHEADER]               = $http_headers;
    $opts[CURLOPT_CONNECTTIMEOUT]           = 10;
    $opts[CURLOPT_TIMEOUT]                  = 60;
    $opts[CURLOPT_HEADER]                   = FALSE;
    $opts[CURLOPT_BINARYTRANSFER]           = TRUE;
    $opts[CURLOPT_VERBOSE]                  = FALSE;
    $opts[CURLOPT_SSL_VERIFYPEER]           = FALSE;
    $opts[CURLOPT_SSL_VERIFYHOST]           = 2;
    $opts[CURLOPT_RETURNTRANSFER]           = TRUE;
    $opts[CURLOPT_FOLLOWLOCATION]           = TRUE;
    $opts[CURLOPT_MAXREDIRS]                = 2;
    $opts[CURLOPT_IPRESOLVE]                = CURL_IPRESOLVE_V4;

    # Initialize PHP/CURL handle
    $ch = curl_init();
    curl_setopt_array($ch, $opts);
    $content = curl_exec($ch);
    
    # Close PHP/CURL handle
    curl_close($ch);
  }// use file_get_contents
  elseif (ini_get('allow_url_fopen')) {
    $content = file_get_contents($image_url);
  }


  # Return results
  return $content;
}

//$data = get_data($url);
$data = getImageRawData($hurl);
var_dump($data);
?>
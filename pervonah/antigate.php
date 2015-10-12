<?php
/*
This is a standard captcha recognition script for Antigate. It was a bit modified to work with image URLs instead of local files.

$rtimeout - delay between captcha status checks
$mtimeout - captcha recognition timeout
$is_phrase - 0 OR 1 - captcha has 2 or more words
$is_regsense - 0 OR 1 - captcha is case sensetive
$is_numeric -  0 OR 1 - captcha has digits only
$min_len    -  0 is no limit, an integer sets minimum text length
$max_len    -  0 is no limit, an integer sets maximum text length
$is_russian -  0 OR 1 - with flag = 1 captcha will be given to a Russian-speaking worker
*/

// Download a captcha image
function getFile($file){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $file);
	curl_setopt($ch, CURLOPT_TIMEOUT, 300);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$result = curl_exec($ch);
	curl_close($ch);
	return $result;
}
// Save captcha image locally
function saveFile($file,$name){
	$fd = @fopen($name, 'w');
	fwrite($fd, getFile($file));
	@fclose($fd);
}

function recognize(
            $filename,
            $apikey,
            $is_verbose = false,
            $domain="antigate.com",
            $rtimeout = 3,
            $mtimeout = 120,
            $is_phrase = 0,
            $is_regsense = 0,
            $is_numeric = 0,
            $min_len = 0,
            $max_len = 0,
            $is_russian = 0
            )
{
	saveFile($filename,'cap.jpg');
	$filename='cap.jpg';
    $postdata = array(
        'method'    => 'post', 
        'key'       => $apikey, 
        'file'      => '@'.$filename,
        'phrase'	=> $is_phrase,
        'regsense'	=> $is_regsense,
        'numeric'	=> $is_numeric,
        'min_len'	=> $min_len,
        'max_len'	=> $max_len,
        
    );
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,             "http://$domain/in.php");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,     1);
    curl_setopt($ch, CURLOPT_TIMEOUT,             60);
    curl_setopt($ch, CURLOPT_POST,                 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS,         $postdata);
    $result = curl_exec($ch);
    curl_close($ch);
	
	$ex = explode("|", $result);
	$captcha_id = $ex[1];
	$waittime = 0;
	sleep($rtimeout);
	while(true){
		$result = file_get_contents("http://$domain/res.php?key=".$apikey.'&action=get&id='.$captcha_id);
		if ($result=="CAPCHA_NOT_READY"){
			$waittime += $rtimeout;
			if ($waittime>$mtimeout){
				break;
			}
			sleep($rtimeout);
		}
		else{
			$ex = explode('|', $result);
			if (trim($ex[0])=='OK') return trim($ex[1]);
		}
	}
	return false;
}
?>
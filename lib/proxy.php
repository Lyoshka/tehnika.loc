<?php

	//$proxy = '188.165.141.151:80';  	//Finland
	//$proxy = '46.37.193.74:8080';		//Ukraine
	//$proxy = '94.23.200.49:3128';		//France
	//$proxy = '86.57.177.11:1080';		//Belarus

$proxy = '10.247.19.22:9090';
$proxyauth = 'spb\eav:recf40vehf}|';


	$login  = 'admiral@mebelkerch.ru';
	$pass = 'vertex';
	$url_auth = 'http://stl-partner.ru/index.php?route=account/login';	

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url_auth);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
	curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; rv:43.0) Gecko/20100101 Firefox/43.0");
	curl_setopt($ch, CURLOPT_REFERER, $referer);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_COOKIEJAR, getcwd() . "/cookies.txt");
	curl_setopt($ch, CURLOPT_COOKIEFILE, getcwd() . "/cookies.txt");
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS,"email=".$login."&password=".$pass);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_PROXY, $proxy);
	curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxyauth);


	$ret = curl_exec($ch);
	
	if (empty($ret)) { 
		echo "Сервер <b>" . $url_auth . "</b> не отвечает :( <br>";
		exit();
	}
?>

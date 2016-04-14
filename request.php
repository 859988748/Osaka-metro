<?php


function request($url, $method, $params = [], $opts = []) {
	$ch = curl_init();
	if($method == 'get') {
		if(!empty($params)) {
			$str = '?';
			foreach($params as $k=>$v) {
				$str .= $k . '=' . $v . '&';
			}
			$str = substr($str, 0, -1);
			$url .= $str;
		}
		curl_setopt($ch, CURLOPT_HTTPGET, 1);
		curl_setopt($ch, CURLOPT_URL, $url);
	} else if($method == 'post') {
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
	}

	$headers = [];
	
	$headers[] = 'X-Forwarded-For: '. (!empty($opts['ip']) ? $opts['ip'] : '192.168.10.3');
	$headers[] = !empty($opts['referer']) ? $opts['referer'] : $url;
	
	if(!empty($opts['timeout'])) {
		$timeout = (int) $opts['timeout'];
	} else {
		$timeout = 10;
	}
	
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_MAXREDIRS, 4);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$data = curl_exec($ch);
	

	if (empty($data)) {
		echo "data is empty\n";
		echo (curl_error($ch));
	}else{
		echo "have data";
	}
	curl_close($ch);
	return $data;
}

// $url = "http://maps.googleapis.com/maps/api/geocode/json?address=1600+Amphitheatre+Parkway,+Mountain+View,+CA&key=ddd";
// $method = "get";
// echo request($url,$method);
?>
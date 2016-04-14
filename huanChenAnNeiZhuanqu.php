<?php 

require_once 'request.php';
require_once 'writeFileToDisk.php';

//解析线路,存储线路
$linesRawData = request("http://transit.yahoo.co.jp/station/list?pref=27.&prefname=%E5%A4%A7%E9%98%AA","get");
$pattern = '%<ul class="elmSearchItem line">(.*?)mdSearchLine -->%si';
$match = [];
preg_match($pattern, $linesRawData, $match);
$pattern = '/<dd>(.*?)<\/dd>/is';
$results =[];
preg_match_all($pattern, $match[0], $results);
$pattern = '/<li>(.*?)<\/li>/is';
$lineUrlPattern = "/href=\"(.*?)\">/is";
$lineNamePattern = "/<a href=\"[^\"]*\"[^>]*>(.*)<\/a>/is";
$linesArray = [];
foreach ($results[1] as $value) {
		# code...
		unset($tempresule);
		$tempresule = [];
		preg_match_all($pattern,  $value, $tempresule);
		foreach ($tempresule[1] as $innerValue) {
			# code...
			preg_match($lineNamePattern,  $innerValue, $tempname);
			preg_match($lineUrlPattern,  $innerValue, $tempurl);
			$tempLine ["name"] = $tempname[1];
			$tempLine ["url"] = $tempurl[1];
			$linesArray[] = $tempLine;
		}
}
echo "line Count :".count($linesArray)."\n";
$filename = "lineInfo.json";
$jsonToSuccessFile = json_encode($linesArray,JSON_UNESCAPED_UNICODE);
writeToFile($filename,$jsonToSuccessFile);
echo "---------\n";


//解析每条线路上的站
$host = "http://transit.yahoo.co.jp";
$allStation = [];
foreach($linesArray as $eachline){
	$linename = $eachline["name"];
	$Url = $host.$eachline["url"];
	$eachlineStationRawData = request($Url,"get");
	$pattern = '/<ul class="elmSearchItem quad">(.*?)<\/ul>/is';
	$stationsLI = [];
	preg_match($pattern,  $eachlineStationRawData, $stationsLI);
	$pattern = '/<li>(.*?)<\/li>/is';
	$stationTagA = [];
	preg_match_all($pattern, $stationsLI[1], $stationTagA);
	$StationUrlPattern = "/href=\"(.*?)\">/is";
	$StationNamePattern = "/<a href=\"[^\"]*\"[^>]*>(.*)<\/a>/is";
	unset($tempstation);
	$tempstation = [];
	foreach ($stationTagA[1] as $value) {
		preg_match($StationNamePattern,  $value, $tempname);
		preg_match($StationUrlPattern,  $value, $tempurl);
		$s ["name"] = $tempname[1];
		$s ["url"] = $tempurl[1];
		$s ["linename"] = $linename;
		$allStation [] = $s;
	}
}
echo "line Count :".count($allStation)."\n";
$filename = "StationInfo.json";
$jsonToSuccessFile = json_encode($allStation,JSON_UNESCAPED_UNICODE);
writeToFile($filename,$jsonToSuccessFile);
echo "---------\n";
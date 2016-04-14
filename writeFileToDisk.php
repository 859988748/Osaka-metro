<?php
	function writeToFile($newFileName,$newFileContent){
		$folder = dirname($newFileName);
		if (!file_exists($folder)) {
			# code...
			mkdir($folder,0777,true);
		}
		if(file_put_contents($newFileName,$newFileContent)!=false){
	    	echo "File created (".basename($newFileName).")";
		}else{
	    	echo "Cannot create file (".basename($newFileName).")";
		}
	}
?>
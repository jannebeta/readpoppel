<?php


	// get bytearray
	$pdf = $GLOBALS["HTTP_RAW_POST_DATA"];
	
	$file = fopen("testi.pdf","w");
	fwrite($file,$pdf);
	fclose($file);


?>
<?php
	date_default_timezone_set('Asia/Jakarta');

	//set initial variable
	define('HOST','localhost');
	define('USER','root');
	define('PASS','');
	define('DB','copypos');
	$image_path = $_SERVER['DOCUMENT_ROOT'].'copypos/images/';

	$con = mysqli_connect(HOST, USER, PASS, DB) or die('Unable to Connect');

?>
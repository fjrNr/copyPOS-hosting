<?php
	date_default_timezone_set('Asia/Jakarta');
	
	//set initial variable
	$conn = null;
	$image_path = $_SERVER['DOCUMENT_ROOT'].'copypos/images/';
	$response = null;

	//set connection to database
	$hostname = "localhost";
	$username = "root";
	$userpass = "";
	$dbname = "copypos"; 
	
	try{
        $conn = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $userpass);
	    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);    
    }catch(PDOException $e) {
		echo "Connection failed: " . $e->getMessage();
	}
?>
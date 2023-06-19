<?php
	$ownerId = $_GET['ownerId'];
	require_once('../koneksiMySQL.php');

	$r = mysqli_query($con, "SELECT * FROM branchs WHERE ownerId=$ownerId");

	$response = array();
	while($row = mysqli_fetch_assoc($r)){
		array_push($response, 
			array(
			"id" => $row['id'],
			"name" => $row['name'],
			"address" => $row['address'],
			"phone" => $row['phone'],
			"imageName" => $row['image']
		));
	}

	echo json_encode($response);

	mysqli_close($con);
?>
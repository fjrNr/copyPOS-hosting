<?php
	$branchId = $_GET['branchId'];
	require_once('../koneksiMySQL.php');

	$query = mysqli_query($con, "SELECT * FROM products WHERE branchId = $branchId AND isPaper = 1");
	$response = array();

	while($row = mysqli_fetch_assoc($query)){
		array_push($response, array(
			"id" => $row['id'],
			"name" => $row['name'],
		));
	}

	echo json_encode($response);
	mysqli_close($con);
?>
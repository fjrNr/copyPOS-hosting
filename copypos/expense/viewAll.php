<?php
	require_once('../koneksiMySQL.php');

	$query = "";
	$branchId = $_GET['branchId'];
	$key = $_GET['key'];
	$response = array();

	if(isset($key)){
		$query = mysqli_query($con, "SELECT * FROM expenses WHERE branchId = $branchId AND name LIKE '%$key%'");
	}else{
		$query = mysqli_query($con, "SELECT * FROM expenses WHERE branchId = $branchId");
	}

	$response = array();

	while($row = mysqli_fetch_assoc($query)){
		array_push($response, array(
			"id" => $row['id'],
			"name" => $row['name'],
			"date" => $row['date'],
			"amount" => $row['amount'],
		));
	}

	echo json_encode($response);
	mysqli_close($con);
?>
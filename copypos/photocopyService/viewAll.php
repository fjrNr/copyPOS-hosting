<?php
	require_once('../koneksiMySQL.php');
	$query = "";
	$branchId = $_GET['branchId'];
	$key = $_GET['key'];
	$response = array();

	if(isset($key)){
		$query = mysqli_query($con,"SELECT * FROM photocopy_services WHERE branchId = $branchId AND name LIKE '%$key%'");
	}else{
		$query = mysqli_query($con,"SELECT * FROM photocopy_services WHERE branchId = $branchId");
	}


	while($row = mysqli_fetch_assoc($query)){
		array_push($response, array(
			"id" => $row['id'],
			"paperId" => $row['paperProductId'],
			"name" => $row['name'],
			"sellPrice" => $row['sellPrice'],
		));
	}

	echo json_encode($response);
	mysqli_close($con);

?>
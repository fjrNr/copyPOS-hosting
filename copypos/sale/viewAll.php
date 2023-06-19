<?php
	require_once('../koneksiMySQL.php');

	$query = "";
	$branchId = $_GET['branchId'];
	$key = $_GET['key'];
	$month = date('m');
	$result = array();

	if(isset($key)){
		$query = mysqli_query($con, "SELECT * FROM sales WHERE branchId = $branchId AND (invoiceNo LIKE '%$key%' OR name LIKE '%$key%') ORDER BY ".'date'." DESC");
	}else{
		$query = mysqli_query($con, "SELECT * FROM sales WHERE branchId = $branchId ORDER BY ".'date'." DESC");
	}

	while($row = mysqli_fetch_assoc($query)){
		array_push($result, $row);
	}

	echo json_encode($result);
	mysqli_close($con);
?>
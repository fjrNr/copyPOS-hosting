<?php
	require_once('../koneksiMySQL.php');
	$query = "";
	$branchId = $_GET['branchId'];
	$key = $_GET['key'];
	$response = array();

	if(isset($key)){
		$query = mysqli_query($con, "SELECT * FROM products WHERE branchId = $branchId AND name LIKE '%$key%'");
	}else{
		$query = mysqli_query($con, "SELECT * FROM products WHERE branchId = $branchId");
	}

	while($row = mysqli_fetch_assoc($query)){
		array_push($response, array(
			"id" => $row['id'],
			"name" => $row['name'],
			"stock" => $row['stock'],
			"minStock" => $row['minStock'],
			"purchasePrice" => $row['purchasePrice'],
			"sellPrice" => $row['sellPrice'],
			"isPaper" => boolval($row['isPaper']),
			"imageName" => $row['image'],
		));
	}

	echo json_encode($response);
	mysqli_close($con);
?>

<!-- response = {{[id] = '', [name] = ''}, {[id] = '', [name] = ''}}
result = {{[totalSale] = '', [totalPuchase] = ''}} -->
<?php 
	$branchId = $_GET['branchId'];
	require_once('../koneksiMySQL.php');

	$r = mysqli_query($con, "SELECT * FROM employees WHERE branchId = $branchId");

	$response = array();
	while ($row = mysqli_fetch_assoc($r)) {
		array_push($response, array(
			"id" => $row['id'],
			"name" => $row['name'],
			"phone" => $row['phone'],
			"username" => $row['username'],
			"allSell" => boolval($row['allowSale']),
			"allPurchase" => boolval($row['allowPurchase']),
			"allStock" => boolval($row['allowStock']),
			"allExp" => boolval($row['allowExpense']),
			"imageName" => $row['image']
		));
	}

	echo json_encode($response);

	mysqli_close($con);

?>
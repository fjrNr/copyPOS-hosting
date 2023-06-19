<?php
	$purchaseId = $_GET['purchaseId'];

	require_once('../koneksiMySQL.php');

	$query = mysqli_query($con, "SELECT p.name, price, qty FROM purchase_details pd JOIN products p ON pd.productId = p.id WHERE purchaseId = $purchaseId");
	$result = array();

	while($row = mysqli_fetch_assoc($query)){
		array_push($result, array(
			"name" => $row['name'],
			"purchasePrice" => $row['price'],
			"stock" => $row['qty'],
		));
	}

	echo json_encode($result);
	mysqli_close($con);
?>
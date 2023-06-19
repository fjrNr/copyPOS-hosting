<?php
	$productId = $_GET['productId'];
	require_once('../koneksiMySQL.php');

	$query = mysqli_query($con, "SELECT * FROM stock_histories WHERE productId = $productId ORDER BY ".'date'." DESC");
	$response = array();

	while($row = mysqli_fetch_assoc($query)){
		array_push($response, array(
			"notes" => $row['notes'],
			"method" => $row['changeMethod'],
			"date" => $row['date'],
			"changedStock" => $row['changeAmount'],
		));
	}

	echo json_encode($response);
	mysqli_close($con);
?>
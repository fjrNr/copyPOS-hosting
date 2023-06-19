<?php
	$purchaseId = $_GET['purchaseId'];

	require_once('../koneksiMySQL.php');

	$query = mysqli_query($con, "SELECT * FROM payment_submissions WHERE purchaseId = $purchaseId");
	$response = array();

	while($row = mysqli_fetch_assoc($query)){
		array_push($response, array(
			"date" => $row['date'],
			"amount" => $row['amount'],
		));
	}

	echo json_encode($response);
	mysqli_close($con);
?>
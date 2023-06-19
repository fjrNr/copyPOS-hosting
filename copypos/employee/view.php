<?php 

	$id = $_GET['id'];

	require_once('../koneksiMySQL.php');

	$sql = "SELECT * FROM employees WHERE id=$id";

	$r = mysqli_query($con, $sql);

	$result = array();
	$row = mysqli_fetch_array($r);
	array_push($result, array(
		"id" => $row['id'],
		"name" => $row['name'],
		"phone" => $row['phone'],
		"username" => $row['username'],
		"password" => $row['password'],
		"allPur" => $row['allPurTrans'],
		"allSell" => $row['allSellTrans'],
		"allHistory" => $row['allHistory'],
		"allInvent" => $row['allInvent'],
	));

	echo json_encode(array('result'=>$result));

	mysqli_close($con);

?>
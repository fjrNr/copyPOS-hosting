<?php 

	$id = $_GET['id'];

	require_once('../koneksiMySQL.php');

	$sql = "SELECT * FROM products WHERE id=$id";

	$r = mysqli_query($con, $sql);

	$result = array();
	$row = mysqli_fetch_array($r);
	array_push($result, array(
		"id" => $row['id'],
		"name" => $row['name'],
		"stock" => $row['stock'],
		"minStock" => $row['minStock'],
		"sellPrice" => $row['sellPrice'],
	));

	echo json_encode(array('result'=>$result));

	mysqli_close($con);

?>
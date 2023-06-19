<?php
	$saleId = $_GET['saleId'];

	require_once('../koneksiMySQL.php');

	$query = mysqli_query($con, 
		"SELECT p.name, price, qty FROM product_sale_details psd JOIN products p ON psd.productId = p.id WHERE saleId = $saleId
		UNION 
		SELECT ps.name, price, qty FROM print_sale_details psd JOIN print_services ps ON psd.printServiceId = ps.id WHERE saleId = $saleId
		UNION
		SELECT ps.name, price, qty FROM photocopy_sale_details psd JOIN photocopy_services ps ON psd.photocopyServiceId = ps.id WHERE saleId = $saleId
		UNION
		SELECT s.name, price, qty FROM service_sale_details ssd JOIN services s ON ssd.serviceId = s.id WHERE saleId = $saleId");

	$result = array();

	while($row = mysqli_fetch_assoc($query)){
		array_push($result, array(
			"name" => $row['name'],
			"sellPrice" => $row['price'],
			"stock" => $row['qty'],
		));
	}

	echo json_encode($result);
	mysqli_close($con);
?>
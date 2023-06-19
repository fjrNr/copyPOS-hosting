<?php
	//belum selesai
	require_once('../koneksiMySQL.php');
	
	$date = 'date';
	$branchId = $_GET['branchId'];
	$periodeType = $_GET['periodeTypeId'];
	$periode = $_GET['periode'];
	$itemType = $_GET['itemType'];

	if($periodeType == 1){
		$dt = date('Y-m-d',strtotime($periode));

		if($itemType == 1){
			$query = mysqli_query($con, "SELECT productId, SUM(qty) AS totalItem FROM product_sale_details psd JOIN sales sale ON psd.saleId = sale.id WHERE sale.branchId = $branchId AND sale.$date = '$dt' GROUP BY productId ORDER BY totalItem DESC");
		}else if($itemType == 2){
			$query = mysqli_query($con, "SELECT printServiceId, SUM(qty) AS totalItem FROM print_sale_details psd JOIN sales sale ON psd.saleId = sale.id WHERE sale.branchId = $branchId AND sale.$date = '$dt' GROUP BY printServiceId ORDER BY totalItem DESC");
		}else if($itemType == 3){
			$query = mysqli_query($con, "SELECT photocopyServiceId, SUM(qty) AS totalItem FROM photocopy_sale_details psd JOIN sales sale ON psd.saleId = sale.id WHERE sale.branchId = $branchId AND sale.$date = '$dt' GROUP BY photocopyServiceId ORDER BY totalItem DESC");
		}else{
			$query = mysqli_query($con, "SELECT serviceId, SUM(qty) AS totalItem FROM service_sale_details ssd JOIN sales sale ON ssd.saleId = sale.id WHERE sale.branchId = $branchId AND sale.$date = '$dt' GROUP BY serviceId ORDER BY totalItem DESC");
		}

	}else{
		$year = date('Y',strtotime($periode));
		$month = date('m',strtotime($periode));

		if($itemType == 1){
			$query = mysqli_query($con, "SELECT productId, SUM(qty) AS totalItem FROM product_sale_details psd JOIN sales sale ON psd.saleId = sale.id WHERE sale.branchId = $branchId AND MONTH(sale.$date) = '$month' AND YEAR(sale.$date) = '$year' GROUP BY productId ORDER BY totalItem DESC");
		}else if($itemType == 2){
			$query = mysqli_query($con, "SELECT printServiceId, SUM(qty) AS totalItem FROM print_sale_details psd JOIN sales sale ON psd.saleId = sale.id WHERE sale.branchId = $branchId AND MONTH(sale.$date) = '$month' AND YEAR(sale.$date) = '$year' GROUP BY printServiceId ORDER BY totalItem DESC");
		}else if($itemType == 3){
			$query = mysqli_query($con, "SELECT photocopyServiceId, SUM(qty) AS totalItem FROM photocopy_sale_details psd JOIN sales sale ON psd.saleId = sale.id WHERE sale.branchId = $branchId AND MONTH(sale.$date) = '$month' AND YEAR(sale.$date) = '$year' GROUP BY photocopyServiceId ORDER BY totalItem DESC");
		}else{
			$query = mysqli_query($con, "SELECT serviceId, SUM(qty) AS totalItem FROM service_sale_details ssd JOIN sales sale ON ssd.saleId = sale.id WHERE sale.branchId = $branchId AND MONTH(sale.$date) = '$month' AND YEAR(sale.$date) = '$year' GROUP BY serviceId ORDER BY totalItem DESC");
		}
	}

	$list = array();

	if($itemType == 1){
		while($row = mysqli_fetch_assoc($query)){
			$itemQuery = mysqli_query($con,"SELECT * FROM products WHERE id = ".$row['productId']." AND branchId = $branchId");
			$item = mysqli_fetch_assoc($itemQuery);

			array_push($list, array(
				"name" => $item['name'], 
				"stock" => $row['totalItem'],
			));
		};
	}else if($itemType == 2){
		while($row = mysqli_fetch_assoc($query)){
			$itemQuery = mysqli_query($con,"SELECT * FROM print_services WHERE id = ".$row['printServiceId']." AND branchId = $branchId");
			$item = mysqli_fetch_assoc($itemQuery);

			array_push($list, array(
				"name" => $item['name'], 
				"amount" => $row['totalItem'],
			));
		};
	}else if($itemType == 3){
		while($row = mysqli_fetch_assoc($query)){
			$itemQuery = mysqli_query($con,"SELECT * FROM photocopy_services WHERE id = ".$row['photocopyServiceId']." AND branchId = $branchId");
			$item = mysqli_fetch_assoc($itemQuery);

			array_push($list, array(
				"name" => $item['name'], 
				"amount" => $row['totalItem'],
			));
		};
	}else{
		while($row = mysqli_fetch_assoc($query)){
			$itemQuery = mysqli_query($con,"SELECT * FROM services WHERE id = ".$row['serviceId']." AND branchId = $branchId");
			$item = mysqli_fetch_assoc($itemQuery);

			array_push($list, array(
				"name" => $item['name'], 
				"amount" => $row['totalItem'],
			));
		};
	}

	if($itemType == 1){
		$result = array(
			"productList" => $list,
		);
	}else if($itemType == 2){
		$result = array(
			"printList" => $list,
		);
	}else if($itemType == 3){
		$result = array(
			"FCList" => $list,
		);
	}else{
		$result = array(
			"serviceList" => $list,
		);
	}
	

	echo json_encode($result);
	mysqli_close($con);

?>
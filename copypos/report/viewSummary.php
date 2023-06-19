<?php
	//belum selesai
	require_once('../koneksiMySQL.php');
	
	$date = 'date';
	$dtNow = date('Y-m-d');
	$branchId = $_GET['branchId'];
	$periodeType = $_GET['periodeTypeId'];
	$periode = $_GET['periode'];
	
	if($periodeType == 1){
		$dtThis = date('Y-m-d',strtotime($periode));

		$queryJual = mysqli_query($con,"SELECT SUM(totalPrice) AS totalSale FROM sales WHERE branchId = $branchId AND $date = '$dtThis'");
		$queryBeli = mysqli_query($con,"SELECT SUM(totalPrice) AS totalPurchase FROM purchases WHERE branchId = $branchId AND $date = '$dtThis'");
		$queryBiaya = mysqli_query($con,"SELECT SUM(amount) AS totalExpense FROM expenses WHERE branchId = $branchId AND $date = '$dtThis'");

		$queryProduct = mysqli_query($con, "SELECT productId, SUM(qty) AS totalItem FROM product_sale_details psd JOIN sales sale ON psd.saleId = sale.id WHERE sale.branchId = $branchId AND sale.$date = '$dtThis' GROUP BY productId ORDER BY totalItem DESC LIMIT 3");
		$queryPrint = mysqli_query($con, "SELECT printServiceId, SUM(qty) AS totalItem FROM print_sale_details psd JOIN sales sale ON psd.saleId = sale.id WHERE sale.branchId = $branchId AND sale.$date = '$dtThis' GROUP BY printServiceId ORDER BY totalItem DESC LIMIT 3");
		$queryFC = mysqli_query($con, "SELECT photocopyServiceId, SUM(qty) AS totalItem FROM photocopy_sale_details psd JOIN sales sale ON psd.saleId = sale.id WHERE sale.branchId = $branchId AND sale.$date = '$dtThis' GROUP BY photocopyServiceId ORDER BY totalItem DESC LIMIT 3");
		$queryService = mysqli_query($con, "SELECT serviceId, SUM(qty) AS totalItem FROM service_sale_details ssd JOIN sales sale ON ssd.saleId = sale.id WHERE sale.branchId = $branchId AND sale.$date = '$dtThis' GROUP BY serviceId ORDER BY totalItem DESC LIMIT 3");

		$queryJualberPiutang = mysqli_query($con,"SELECT SUM(totalPrice) AS totalSale FROM sales WHERE branchId = $branchId AND $date <='$dtThis' AND paymentStatus = 'Belum Lunas'");
		$queryBeliberUtang = mysqli_query($con,"SELECT SUM(totalPrice) AS totalPurchase FROM purchases WHERE branchId = $branchId AND $date <= '$dtThis' AND paymentStatus = 'Belum Lunas'");
		

		$queryDebt = mysqli_query($con,"SELECT SUM(pur.totalPrice)-SUM(ps.amount) AS total FROM payment_submissions ps JOIN purchases pur ON ps.purchaseId = pur.id WHERE pur.branchId = $branchId AND pur.$date <='$dtThis' AND pur.paymentStatus = 'Belum Lunas'");


		$queryCredit = mysqli_query($con,"SELECT SUM(sale.totalPrice)-SUM(pr.amount) AS total FROM payment_receptions pr JOIN sales sale ON pr.saleId = sale.id WHERE sale.branchId = $branchId AND sale.$date <='$dtThis' AND sale.paymentStatus = 'Belum Lunas'");

	}else{
		$year = date('Y',strtotime($periode));
		$month = date('m',strtotime($periode));
		$dtThis = date('Y-m-t',strtotime($periode));

		$queryJual = mysqli_query($con,"SELECT SUM(totalPrice) AS totalSale FROM sales WHERE branchId = $branchId AND MONTH($date) = $month AND YEAR($date) = $year");
		$queryBeli = mysqli_query($con,"SELECT SUM(totalPrice) AS totalPurchase FROM purchases WHERE branchId = $branchId AND MONTH($date) = $month AND YEAR($date) = $year");
		$queryBiaya = mysqli_query($con,"SELECT SUM(amount) AS totalExpense FROM expenses WHERE branchId = $branchId AND MONTH($date) = $month AND YEAR($date) = $year");

		$queryProduct = mysqli_query($con, "SELECT productId, SUM(qty) AS totalItem FROM product_sale_details psd JOIN sales sale ON psd.saleId = sale.id WHERE sale.branchId = $branchId AND MONTH(sale.$date) = '$month' AND YEAR(sale.$date) = '$year' GROUP BY productId ORDER BY totalItem DESC LIMIT 3");
		$queryPrint = mysqli_query($con, "SELECT printServiceId, SUM(qty) AS totalItem FROM print_sale_details psd JOIN sales sale ON psd.saleId = sale.id WHERE sale.branchId = $branchId AND MONTH(sale.$date) = '$month' AND YEAR(sale.$date) = '$year' GROUP BY printServiceId ORDER BY totalItem DESC LIMIT 3");
		$queryFC = mysqli_query($con, "SELECT photocopyServiceId, SUM(qty) AS totalItem FROM photocopy_sale_details psd JOIN sales sale ON psd.saleId = sale.id WHERE sale.branchId = $branchId AND MONTH(sale.$date) = '$month' AND YEAR(sale.$date) = '$year' GROUP BY photocopyServiceId ORDER BY totalItem DESC LIMIT 3");
		$queryService = mysqli_query($con, "SELECT serviceId, SUM(qty) AS totalItem FROM service_sale_details ssd JOIN sales sale ON ssd.saleId = sale.id WHERE sale.branchId = $branchId AND MONTH(sale.$date) = '$month' AND YEAR(sale.$date) = '$year' GROUP BY serviceId ORDER BY totalItem DESC LIMIT 3");


		$queryDebt = mysqli_query($con,"SELECT SUM(pur.totalPrice)-SUM(ps.amount) AS total FROM payment_submissions ps JOIN purchases pur ON ps.purchaseId = pur.id WHERE pur.branchId = $branchId AND pur.$date <='$dtThis' AND pur.paymentStatus = 'Belum Lunas'");


		$queryCredit = mysqli_query($con,"SELECT SUM(sale.totalPrice)-SUM(pr.amount) AS total FROM payment_receptions pr JOIN sales sale ON pr.saleId = sale.id WHERE sale.branchId = $branchId AND sale.$date <='$dtThis' AND sale.paymentStatus = 'Belum Lunas'");
	}

	$rowJual = mysqli_fetch_assoc($queryJual);
	$rowBeli = mysqli_fetch_assoc($queryBeli);
	$rowBiaya = mysqli_fetch_assoc($queryBiaya);
	$productList = array();
	$printServiceList = array();
	$FCServiceList = array();
	$serviceList = array();
	$rowUtang = mysqli_fetch_assoc($queryDebt);
	$rowPiutang = mysqli_fetch_assoc($queryCredit);

	while($row = mysqli_fetch_assoc($queryProduct)){
		$query = mysqli_query($con,"SELECT * FROM products WHERE id = ".$row['productId']." AND branchId = $branchId");
		$item = mysqli_fetch_assoc($query);

		array_push($productList, array(
			"name" => $item['name'], 
			"stock" => $row['totalItem'],
		));
	};

	while($row = mysqli_fetch_assoc($queryPrint)){
		$query = mysqli_query($con,"SELECT * FROM print_services WHERE id = ".$row['printServiceId']." AND branchId = $branchId");
		$item = mysqli_fetch_assoc($query);

		array_push($printServiceList, array(
			"name" => $item['name'], 
			"amount" => $row['totalItem'],
		));
	};

	while($row = mysqli_fetch_assoc($queryFC)){
		$query = mysqli_query($con,"SELECT * FROM photocopy_services WHERE id = ".$row['photocopyServiceId']." AND branchId = $branchId");
		$item = mysqli_fetch_assoc($query);

		array_push($FCServiceList, array(
			"name" => $item['name'], 
			"amount" => $row['totalItem'],
		));
	};

	while($row = mysqli_fetch_assoc($queryService)){
		$query = mysqli_query($con,"SELECT * FROM services WHERE id = ".$row['serviceId']." AND branchId = $branchId");
		$item = mysqli_fetch_assoc($query);

		array_push($serviceList, array(
			"name" => $item['name'], 
			"amount" => $row['totalItem'],
		));
	};
	
	$result = array(
		"totalSale" => $rowJual['totalSale'],
		"totalPurchase" => $rowBeli['totalPurchase'],
		"totalExpense" => $rowBiaya['totalExpense'],
		"productList" => $productList,
		"printList" => $printServiceList,
		"FCList" => $FCServiceList,
		"serviceList" => $serviceList,
		"totalDebt" => $rowUtang['total'],
		"totalCredit" => $rowPiutang['total'],
	);

	echo json_encode($result);
	mysqli_close($con);

?>
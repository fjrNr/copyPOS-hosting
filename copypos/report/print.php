<?php
	// $branchId = (int);
	// $periodeType = (int) 1: date, 2: month;
	// $periode = (string);
	// $report_type = (string);

	require_once('../fpdf.php');
	require_once('../koneksiMySQL.php');

	$dateColumn = 'date';
	$branchId = $_GET['branchId'];
	$periodeType = $_GET['periodeTypeId'];
	$periode = $_GET['periode'];
	$report_type = $_GET['reportType'];

	function tgl_indo($date){
		$bulan = array (
			1 =>   'Januari',
			'Februari',
			'Maret',
			'April',
			'Mei',
			'Juni',
			'Juli',
			'Agustus',
			'September',
			'Oktober',
			'November',
			'Desember'
		);
		$pecahkan = explode('-', $date);
		return $pecahkan[2] . ' ' . $bulan[ (int)$pecahkan[1] ] . ' ' . $pecahkan[0];
	}

	function bln_indo($month){
		$bulan = array (
			1 =>   'Januari',
			'Februari',
			'Maret',
			'April',
			'Mei',
			'Juni',
			'Juli',
			'Agustus',
			'September',
			'Oktober',
			'November',
			'Desember'
		);
		return $bulan[(int)$month];
	}

	if($periodeType == 1){
		$date = date('Y-m-d',strtotime($periode));

	}else{
		$month = date('m',strtotime($periode));
		$year = date('Y',strtotime($periode));
	}

	$query = mysqli_query($con, "SELECT * FROM branchs WHERE id = $branchId");
	$branch = mysqli_fetch_assoc($query);

	if($report_type == 'sales'){
		
		$titleType = 'Penjualan';

		if($periodeType == 1){

			$queryTrans = mysqli_query($con, "SELECT * FROM sales WHERE branchId = $branchId AND $dateColumn = '$date' ORDER BY $dateColumn ASC");

			$headerTable = array('No.', 'No. Invoice', 'Nama Pelanggan', 'Tanggal Penjualan', 'Total Harga');
		
		}else{
		
			$queryTrans = mysqli_query($con, "SELECT $dateColumn, COUNT(id) AS totalTrans, SUM(totalPrice) AS totalSale FROM sales WHERE branchId = $branchId AND MONTH($dateColumn) = '$month' AND YEAR($dateColumn) = '$year' GROUP BY $dateColumn ORDER BY $dateColumn ASC");

			$headerTable = array('No.', 'Tanggal Penjualan', 'Total Transaksi', 'Total Penjualan');
		
		}
		
	}else if($report_type == 'purchases'){
		
		$titleType = 'Pembelian';

		if($periodeType == 1){
		
			$queryTrans = mysqli_query($con, "SELECT * FROM purchases WHERE branchId = $branchId AND $dateColumn = '$date' ORDER BY $dateColumn ASC");

			$headerTable = array('No.', 'No. Invoice', 'Nama Pemasok', 'Tanggal Pembelian', 'Total Harga');

		}else{
		
			$queryTrans = mysqli_query($con, "SELECT $dateColumn, COUNT(id) AS totalTrans, SUM(totalPrice) AS totalPurchase FROM purchases WHERE branchId = $branchId AND MONTH($dateColumn) = '$month' AND YEAR($dateColumn) = '$year' GROUP BY $dateColumn ORDER BY $dateColumn ASC");
	
			$headerTable = array('No.', 'Tanggal Pembelian', 'Total Transaksi', 'Total Penjualan');
		
		}

	}else if($report_type == 'product_sales'){
		
		$titleType = 'Penjualan Barang';

		if($periodeType == 1){

			$queryTrans = mysqli_query($con, "SELECT productId, SUM(qty) AS totalItem, AVG(price) AS ratePrice, SUM(qty*price) AS totalSale FROM product_sale_details psd JOIN sales sale ON psd.saleId = sale.id WHERE sale.branchId = $branchId AND sale.date = '$date' GROUP BY productId ORDER BY totalItem DESC");

		}else{

			$queryTrans = mysqli_query($con, "SELECT productId, SUM(qty) AS totalItem, AVG(price) AS ratePrice, SUM(qty*price) AS totalSale FROM product_sale_details psd JOIN sales sale ON psd.saleId = sale.id WHERE sale.branchId = $branchId AND MONTH(sale.date) = '$month' AND YEAR(sale.date) = '$year' GROUP BY productId ORDER BY totalItem DESC");
		
		}

		$headerTable = array('No.', 'Nama Barang', 'Jumlah Barang Terjual', 'Harga Jual Rata-rata', 'Total Penjualan');


	}else if($report_type == 'print_sales'){

		$titleType = 'Penjualan Print';

		if($periodeType == 1){

			$queryTrans = mysqli_query($con, "SELECT printServiceId, SUM(qty) AS totalItem, AVG(price) AS ratePrice, SUM(qty*price) AS totalSale FROM print_service_sale_details pssd JOIN sales sale ON pssd.saleId = sale.id WHERE sale.branchId = $branchId AND sale.date = '$date' GROUP BY printServiceId ORDER BY totalItem DESC");	

		}else{

			$queryTrans = mysqli_query($con, "SELECT printServiceId, SUM(qty) AS totalItem, AVG(price) AS ratePrice, SUM(qty*price) AS totalSale FROM print_service_sale_details pssd JOIN sales sale ON pssd.saleId = sale.id WHERE sale.branchId = $branchId AND MONTH(sale.date) = '$month' AND YEAR(sale.date) = '$year' GROUP BY printServiceId ORDER BY totalItem DESC");

		}

		$headerTable = array('No.', 'Nama jasa', 'Jumlah Print (lembar)', 'Harga Jual Rata-rata', 'Total Penjualan');
		
	}else if($report_type == 'photocopy_sales'){

		$titleType = 'Penjualan Fotokopi';

		if($periodeType == 1){

			$queryTrans = mysqli_query($con, "SELECT photocopyServiceId, SUM(qty) AS totalItem, AVG(price) AS ratePrice, SUM(qty*price) AS totalSale FROM photocopy_service_sale_details pssd JOIN sales sale ON pssd.saleId = sale.id WHERE sale.branchId = $branchId AND sale.date = '$date' GROUP BY photocopyServiceId ORDER BY totalItem DESC");

		}else{

			$queryTrans = mysqli_query($con, "SELECT photocopyServiceId, SUM(qty) AS totalItem, AVG(price) AS ratePrice, SUM(qty*price) AS totalSale FROM photocopy_service_sale_details pssd JOIN sales sale ON pssd.saleId = sale.id WHERE sale.branchId = $branchId AND MONTH(sale.date) = '$month' AND YEAR(sale.date) = '$year' GROUP BY photocopyServiceId ORDER BY totalItem DESC");

		}

		$headerTable = array('No.', 'Nama jasa', 'Jumlah Fotokopi (lembar)', 'Harga Jual Rata-rata', 'Total Penjualan');
		

	}else if($report_type == 'service_sales'){

		$titleType = 'Penjualan jasa lain';

		if($periodeType == 1){

			$queryTrans = mysqli_query($con, "SELECT serviceId, SUM(qty) AS totalItem, AVG(price) AS ratePrice, SUM(qty*price) AS totalSale FROM service_sale_details ssd JOIN sales sale ON ssd.saleId = sale.id WHERE sale.branchId = $branchId AND sale.date = '$date' GROUP BY serviceId ORDER BY totalItem DESC");

		}else{

			$queryTrans = mysqli_query($con, "SELECT serviceId, SUM(qty) AS totalItem, AVG(price) AS ratePrice, SUM(qty*price) AS totalSale FROM service_sale_details ssd JOIN sales sale ON ssd.saleId = sale.id WHERE sale.branchId = $branchId AND MONTH(sale.date) = '$month' AND YEAR(sale.date) = '$year' GROUP BY serviceId ORDER BY totalItem DESC");

		}
		
		$headerTable = array('No.', 'Nama jasa', 'Jumlah Item (kali)', 'Harga Jual Rata-rata', 'Total Penjualan');

	}else if($report_type == 'expenses'){

		$titleType = 'Biaya';

		if($periodeType == 1){

			$queryTrans = mysqli_query($con,"SELECT * FROM expenses WHERE branchId = $branchId AND $dateColumn = '$date'");

		}else{

			$queryTrans = mysqli_query($con,"SELECT * FROM expenses WHERE branchId = $branchId AND MONTH($dateColumn) = '$month' AND YEAR($dateColumn) = '$year' ORDER BY $dateColumn");

		}

		$headerTable = array('No.', 'Tanggal Bayar', 'Nama Biaya', 'Jumlah (Rp.)');

	}else if($report_type == 'overview'){

		$titleType = 'Laba Rugi';

		if($periodeType == 1){

			$queryJual = mysqli_query($con,"SELECT SUM(grandTotalPrice) AS totalSale FROM sales WHERE branchId = $branchId AND $dateColumn = '$date'");
		
			$queryBeli = mysqli_query($con,"SELECT SUM(grandTotalPrice) AS totalPurchase FROM purchases WHERE branchId = $branchId AND $dateColumn = '$date'");
		
			$queryBiaya = mysqli_query($con,"SELECT SUM(amount) AS totalExpense FROM expenses WHERE branchId = $branchId AND $dateColumn = '$date'");

		}else{

			$queryJual = mysqli_query($con,"SELECT SUM(grandTotalPrice) AS totalSale FROM sales WHERE branchId = $branchId AND MONTH($dateColumn) = '$month' AND YEAR($dateColumn) = '$year'");
		
			$queryBeli = mysqli_query($con,"SELECT SUM(grandTotalPrice) AS totalPurchase FROM purchases WHERE branchId = $branchId AND MONTH($dateColumn) = '$month' AND YEAR($dateColumn) = '$year'");
		
			$queryBiaya = mysqli_query($con,"SELECT SUM(amount) AS totalExpense FROM expenses WHERE branchId = $branchId AND MONTH($dateColumn) = '$month' AND YEAR($dateColumn) = '$year'");

		}

	}else if($report_type == 'debt'){

	}else if($report_type == 'credit'){


	}

	if($periodeType == 1){
		$titlePeriode = 'Tanggal '.tgl_indo($date);
	}else{
		$titlePeriode = 'Bulan '.bln_indo($month).' '.$year;
	}

	$reportTitle = 'Laporan '.$titleType.' '.$titlePeriode;

	class PDF extends FPDF{
		// Page header
		function Header(){
		    // Title
		    // $this->Cell(pjg cel (0 = full cel), lbr cel, text, tebal border, pindah posisi kursor, rerata teks);
		    // $this->Ln() ==> tambah baris baru;
		    $this->SetFont('Arial','B',12);
		    $this->Cell(0,6,$this->branchName,0,0,'C');
		    $this->Ln();
		    $this->SetFont('Arial','',10);
		    $this->Cell(0,6,$this->branchAddress,0,0,'C');
		    $this->Ln();
		    $this->Cell(0,6,$this->branchPhone,0,0,'C');
		    $this->Ln();
		    $this->Cell(0,6,'=============================================================================================',0,0,'C');
		    $this->Ln(10);
		    $this->SetFont('Arial','B',12);
			$this->Cell(0,6,$this->reportTitle,0,0,'C');
			$this->Ln(10);
			
			if($this->reportType == 'sales' || $this->reportType == 'purchases'){
				if($this->periodeType == 1){
					foreach($this->headerTable as $col) {
						if($col == 'No.'){
							$this->Cell(10,6,$col,1,0,'C');
						}else if($col == 'No. Invoice'){
							$this->Cell(60,6,$col,1,0,'C');
						}else{
							$this->Cell(40,6,$col,1,0,'C');
						}
					}
				}else{
					foreach($this->headerTable as $col) {
						if($col == 'No.'){
							$this->Cell(10,6,$col,1,0,'C');
						}else{
							$this->Cell(40,6,$col,1,0,'C');
						}
					}
				}
			}else if($this->reportType == 'product_sales' || $this->reportType == 'print_sales' || $this->reportType == 'photocopy_sales' || $this->reportType == 'service_sales'){
				foreach($this->headerTable as $col) {
					if($col == 'No.'){
						$this->Cell(10,6,$col,1,0,'C');
					}else if($col == 'Nama Barang' || $col == 'Nama jasa'){
						$this->Cell(60,6,$col,1,0,'C');
					}else{
						$this->Cell(40,6,$col,1,0,'C');
					}
				}
			}else if($this->reportType == 'expenses'){
				foreach($this->headerTable as $col) {
					if($col == 'No.'){
						$this->Cell(10,6,$col,1,0,'C');
					}else if($col == 'Nama Biaya'){
						$this->Cell(60,6,$col,1,0,'C');
					}else{
						$this->Cell(40,6,$col,1,0,'C');
					}
				}
			}

			if($this->PageNo() != 1){
				$this->Ln();
			}
		}

		// Page footer
		function Footer(){
		    // Position at 1.5 cm from bottom
		    $this->SetY(-15);
		    // Arial italic 8
		    $this->SetFont('Arial','I',8);
		    // Page number
		    $this->Cell(0,10,'Halaman '.$this->PageNo().' dari {nb}',0,0,'C');
		}
	}

	function decimalTiga($number){
		$result = number_format($number,0,'','.');
		return $result;
	}

	$pdf = new PDF();
	//header

	$pdf->branchName = $branch['name'];
	$pdf->branchAddress = $branch['address'];
	$pdf->branchPhone = 'Telp. '.$branch['phone'];
	$pdf->reportTitle = $reportTitle;
	$pdf->reportType = $report_type;
	$pdf->periodeType = $periodeType;
	if($report_type != 'overview'){
		$pdf->headerTable = $headerTable;
	}
	$pdf->AddPage();
	$pdf->AliasNbPages();
	//table filler
	$i = 0;
	$pdf->SetFont('Arial','',10);
	

	if($report_type == 'sales'){
		
		if($periodeType == 1){

			while($trans = mysqli_fetch_assoc($queryTrans)){
				$i++;
				$pdf->Ln();
				$pdf->Cell(10,6,$i.'.',1,0,'R');
				$pdf->Cell(60,6,$trans['invoiceNo'],1,0,'L');
				$pdf->Cell(40,6,$trans['name'],1,0,'L');
				$pdf->Cell(40,6, date('d-m-Y',strtotime($trans['date'])) ,1,0,'L');
				$pdf->Cell(5,6,'Rp. ','T,L,B',0,'L');
				$pdf->Cell(35,6,decimalTiga($trans['totalPrice']),'T,R,B',0,'R');
			}

		}else{

			while($trans = mysqli_fetch_assoc($queryTrans)){
				$i++;
				$pdf->Ln();
				$pdf->Cell(10,6,$i.'.',1,0,'R');
				$pdf->Cell(40,6, date('d-m-Y',strtotime($trans['date'])) ,1,0,'L');
				$pdf->Cell(40,6, $trans['totalTrans'] ,1,0,'R');
				$pdf->Cell(5,6,'Rp. ','T,L,B',0,'L');
				$pdf->Cell(35,6,decimalTiga($trans['totalSale']),'T,R,B',0,'R');
			}

		}

	}else if($report_type == 'purchases'){
		
		if($periodeType == 1){

			while($trans = mysqli_fetch_assoc($queryTrans)){
				$i++;
				$pdf->Ln();
				$pdf->Cell(10,6,$i.'.',1,0,'R');
				$pdf->Cell(60,6,$trans['invoiceNo'],1,0,'L');
				$pdf->Cell(40,6,$trans['name'],1,0,'L');
				$pdf->Cell(40,6, date('d-m-Y',strtotime($trans['date'])) ,1,0,'L');
				$pdf->Cell(5,6,'Rp. ','T,L,B',0,'L');
				$pdf->Cell(35,6,decimalTiga($trans['totalPrice']),'T,R,B',0,'R');
			}

		}else{

			while($trans = mysqli_fetch_assoc($queryTrans)){
				$i++;
				$pdf->Ln();
				$pdf->Cell(10,6,$i.'.',1,0,'R');
				$pdf->Cell(40,6, date('d-m-Y',strtotime($trans['date'])) ,1,0,'L');
				$pdf->Cell(40,6, $trans['totalTrans'] ,1,0,'R');
				$pdf->Cell(5,6,'Rp. ','T,L,B',0,'L');
				$pdf->Cell(35,6,decimalTiga($trans['totalPurchase']),'T,R,B',0,'R');
			}

		}		

	}else if($report_type == 'product_sales'){
		
		while($row = mysqli_fetch_assoc($queryTrans)){
			$query = mysqli_query($con,"SELECT * FROM products WHERE id = ".$row['productId']." AND branchId = $branchId");
			$item = mysqli_fetch_assoc($query);

			$i++;
			$pdf->Ln();
			$pdf->Cell(10,6,$i.'.',1,0,'R');
			$pdf->Cell(60,6,$item['name'],1,0,'L');
			$pdf->Cell(40,6,$row['totalItem'],1,0,'R');
			$pdf->Cell(5,6,'Rp. ','T,L,B',0,'L');
			$pdf->Cell(35,6,decimalTiga($row['ratePrice']),'T,R,B',0,'R');
			$pdf->Cell(5,6,'Rp. ','T,L,B',0,'L');
			$pdf->Cell(35,6,decimalTiga($row['totalSale']),'T,R,B',0,'R');
		}

	}else if($report_type == 'print_sales'){

		while($row = mysqli_fetch_assoc($queryTrans)){
			$query = mysqli_query($con,"SELECT * FROM print_services WHERE id = ".$row['printServiceId']." AND branchId = $branchId");
			$item = mysqli_fetch_assoc($query);

			$i++;
			$pdf->Ln();
			$pdf->Cell(10,6,$i.'.',1,0,'R');
			$pdf->Cell(60,6,$item['name'],1,0,'L');
			$pdf->Cell(40,6,$row['totalItem'],1,0,'R');
			$pdf->Cell(5,6,'Rp. ','T,L,B',0,'L');
			$pdf->Cell(35,6,decimalTiga($row['ratePrice']),'T,R,B',0,'R');
			$pdf->Cell(5,6,'Rp. ','T,L,B',0,'L');
			$pdf->Cell(35,6,decimalTiga($row['totalSale']),'T,R,B',0,'R');
		}
		
	}else if($report_type == 'photocopy_sales'){

		while($row = mysqli_fetch_assoc($queryTrans)){
			$query = mysqli_query($con,"SELECT * FROM photocopy_services WHERE id = ".$row['photocopyServiceId']." AND branchId = $branchId");
			$item = mysqli_fetch_assoc($query);

			$i++;
			$pdf->Ln();
			$pdf->Cell(10,6,$i.'.',1,0,'R');
			$pdf->Cell(60,6,$item['name'],1,0,'L');
			$pdf->Cell(40,6,$row['totalItem'],1,0,'R');
			$pdf->Cell(5,6,'Rp. ','T,L,B',0,'L');
			$pdf->Cell(35,6,decimalTiga($row['ratePrice']),'T,R,B',0,'R');
			$pdf->Cell(5,6,'Rp. ','T,L,B',0,'L');
			$pdf->Cell(35,6,decimalTiga($row['totalSale']),'T,R,B',0,'R');
		}

	}else if($report_type == 'service_sales'){

		while($row = mysqli_fetch_assoc($queryTrans)){
			$query = mysqli_query($con,"SELECT * FROM services WHERE id = ".$row['serviceId']." AND branchId = $branchId");
			$item = mysqli_fetch_assoc($query);

			$i++;
			$pdf->Ln();
			$pdf->Cell(10,6,$i.'.',1,0,'R');
			$pdf->Cell(60,6,$item['name'],1,0,'L');
			$pdf->Cell(40,6,$row['totalItem'],1,0,'R');
			$pdf->Cell(5,6,'Rp. ','T,L,B',0,'L');
			$pdf->Cell(35,6,decimalTiga($row['ratePrice']),'T,R,B',0,'R');
			$pdf->Cell(5,6,'Rp. ','T,L,B',0,'L');
			$pdf->Cell(35,6,decimalTiga($row['totalSale']),'T,R,B',0,'R');
		}

	}else if($report_type == 'expenses'){

		while($trans = mysqli_fetch_assoc($queryTrans)){
			$i++;
			$pdf->Ln();
			$pdf->Cell(10,6,$i.'.',1,0,'R');
			$pdf->Cell(40,6, date('d-m-Y',strtotime($trans['date'])) ,1,0,'L');
			$pdf->Cell(60,6, $trans['name'] ,1,0,'L');
			$pdf->Cell(5,6,'Rp. ','T,L,B',0,'L');
			$pdf->Cell(35,6,decimalTiga($trans['amount']),'T,R,B',0,'R');
		}

	}else if($report_type == 'overview'){

		$row1 = mysqli_fetch_assoc($queryJual);
		$row2 = mysqli_fetch_assoc($queryBeli);
		$row3 = mysqli_fetch_assoc($queryBiaya);
		$income = $row1['totalSale'] - $row2['totalPurchase'];
		$net = $income - $row3['totalExpense'];

		$pdf->Cell(60,6,'Total Penjualan',1,0,'L');
		$pdf->Cell(5,6,'Rp. ','T,L,B',0,'L');
		$pdf->Cell(55,6,decimalTiga($row1['totalSale']),'T,R,B',1,'R');

		$pdf->Cell(60,6,'Total Pembelian',1,0,'L');
		$pdf->Cell(5,6,'Rp. ','T,L,B',0,'L');
		$pdf->Cell(55,6,decimalTiga($row2['totalPurchase']),'T,R,B',1,'R');
		
		$pdf->SetFont('Arial','B',10);
		$pdf->Cell(60,6,'Pendapatan',1,0,'L');
		$pdf->Cell(5,6,'Rp. ','T,L,B',0,'L');
		$pdf->Cell(55,6,decimalTiga($income),'T,R,B',1,'R');

		$pdf->SetFont('Arial','',10);
		$pdf->Cell(60,6,'Total Biaya',1,0,'L');
		$pdf->Cell(5,6,'Rp. ','T,L,B',0,'L');
		$pdf->Cell(55,6,decimalTiga($row3['totalExpense']),'T,R,B',1,'R');
		
		$pdf->SetFont('Arial','B',10);
		$pdf->Cell(60,6,'Laba / Rugi',1,0,'L');
		$pdf->Cell(5,6,'Rp. ','T,L,B',0,'L');
		$pdf->Cell(55,6,decimalTiga($net),'T,R,B',1,'R');

	}

	$pdf->setTitle("test");
	$pdf->Output();
?>
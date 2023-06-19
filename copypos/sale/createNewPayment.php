<?php 
	if($_SERVER['REQUEST_METHOD']=='POST'){
		require_once('../koneksi.php');

		//set variable
		$saleId = $_POST['saleId'];
		$amount = $_POST['amount'];
		$date = date('Y-m-d');

		//count total payment
		$stmt = $conn->prepare("SELECT SUM(amount) AS total FROM payment_receptions WHERE saleId = ?");
		$stmt->execute([$saleId]);
		$totalPayment = $stmt->fetch(PDO::FETCH_COLUMN);

		//search a sale
		$stmt = $conn->prepare("SELECT * FROM sales WHERE id = ?");
		$stmt->execute([$saleId]);
		$sale = $stmt->fetch();

		$piutang = $sale['totalPrice'] - $totalPayment;
		$kembalian = $amount - $piutang;
		
		if($kembalian >= 0){
			$amount = $piutang;
			$stmt = $conn->prepare("UPDATE sales SET paymentStatus = ? WHERE id = ?");
			$stmt->execute(['Lunas', $sale['id']]);
		}

		//create payment history
		try{
			$stmt = $conn->prepare("INSERT INTO payment_receptions (saleId, amount, "."date) VALUES (?, ?, ?)");
			$stmt->execute([$sale['id'], $amount, $date]);
			$response['success'] = true;
			if($kembalian >= 0){
				$response['message'] = 'Penjualan lunas';
			}else{
				$response['message'] = 'Penjualan belum lunas';
			}
			$response['amount'] = $kembalian;
		}catch(PDOException $e){
			$response['success'] = false;
			$response['message'] = 'Pembayaran gagal, silakan coba lagi';
		}
		$conn = null;
	}else{
		$response['success'] = false;
		$response['message'] = 'Permintaan gagal';
	}
	echo json_encode($response);
?>
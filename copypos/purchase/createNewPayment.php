<?php 
	if($_SERVER['REQUEST_METHOD']=='POST'){
		require_once('../koneksi.php');

		//set variable
		$purchaseId = $_POST['purchaseId'];
		$amount = $_POST['amount'];
		$date = date('Y-m-d');

		//count total payment
		$stmt = $conn->prepare("SELECT SUM(amount) AS total FROM payment_submissions WHERE purchaseId = ?");
		$stmt->execute([$purchaseId]);
		$totalPayment = $stmt->fetch(PDO::FETCH_COLUMN);

		//search a purchase
		$stmt = $conn->prepare("SELECT * FROM purchases WHERE id = ?");
		$stmt->execute([$purchaseId]);
		$purchase = $stmt->fetch();

		$utang = $purchase['totalPrice'] - $totalPayment;
		$kembalian = $amount - $utang;

		if($kembalian >= 0){
			$amount = $utang;
			$stmt = $conn->prepare("UPDATE purchases SET paymentStatus = ? WHERE id = ?");
			$stmt->execute(['Lunas', $purchase['id']]);
		}

		//create payment history
		try{
			$stmt = $conn->prepare("INSERT INTO payment_submissions (purchaseId, amount, "."date) VALUES (?, ?, ?)");
			$stmt->execute([$purchase['id'], $amount, $date]);
			$response['success'] = true;
			if($kembalian >= 0){
				$response['message'] = 'Pembelian lunas';
			}else{
				$response['message'] = 'Pembelian belum lunas';
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
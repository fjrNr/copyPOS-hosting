<?php 	
	if($_SERVER['REQUEST_METHOD']=='POST'){
		require_once('../koneksi.php');

		//set variable
		$branchId = $_POST['branchId'];
		$date = $_POST['date'];
		$dueDate = date('Y-m-d',strtotime($_POST['dueDate']));
		$invoiceNo = 'INV-B-T'.$branchId.'-'.date('dmY').'-'.date('His');
		$name = $_POST['name'];
		$payAmount = $_POST['payAmount']; 
		$paymentStatus = '';
		$phone = $_POST['phone'];
		$totalPrice = $_POST['totalPrice'];

		//count charge
		$kembalian = $payAmount - $totalPrice;
		if($kembalian >= 0){
			$paymentStatus = 'Lunas';
			$payAmount = $totalPrice;
		}else{
			$paymentStatus = 'Belum Lunas';
		}

		try{
			$stmt = $conn->prepare("INSERT INTO purchases (branchId, "."date".", dueDate, invoiceNo, name, paymentStatus, phone, totalPrice) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
			$stmt->execute([$branchId, $date, $dueDate, $invoiceNo, $name, $paymentStatus, $phone, $totalPrice]);
			$purchaseId = $conn->lastInsertId();

			//create purchase details
			for ($i=0; $i < count($_POST['idList']); $i++) { 
				//set variable
				$productId = $_POST['idList'][$i];
				$productPrice = $_POST['pList'][$i];
				$productAmount = $_POST['qtyList'][$i];

				$stmt = $conn->prepare("INSERT INTO purchase_details (purchaseId, productId, price, qty) VALUES (?, ?, ?, ?)");
				$stmt->execute([$purchaseId, $productId, $productPrice, $productAmount]);

				//update stock
				$stmt = $conn->prepare("SELECT id, stock FROM products WHERE id = ?");
				$stmt->execute([$productId]);
				$product = $stmt->fetch();
				$stmt = $conn->prepare("UPDATE products SET stock = stock + ? WHERE id = ?");
				$stmt->execute([$productAmount, $product['id']]);

				//create stock history
				$currStock = $product['stock'] + $productAmount;
				$stmt = $conn->prepare("INSERT INTO stock_histories (productId, "."date".", changeAmount, price, currStock, changeMethod, notes) VALUES (?, ?, ?, ?, ?, ?, ?) ");
				$stmt->execute([$product['id'], $date, $productAmount, $productPrice, $currStock, 'Pembelian' ,$invoiceNo]);
			}


			//create payment history
			$stmt = $conn->prepare("INSERT INTO payment_submissions (purchaseId, "."date".", amount) VALUES (?, ?, ?)");
			$stmt->execute([$purchaseId, $date, $payAmount]);
		
			$response['success'] = true;
			if($kembalian >=0){
				$response['message'] = 'Pembelian lunas';
			}else{
				$response['message'] = 'Pembelian berhasil';
			}
			$response['totalPrice'] = $kembalian;

		}catch(PDOException $e){
			$response['success'] = false;
			$response['message'] = 'Pembelian gagal, silakan coba lagi';
		}
		$conn = null;
	}else{
		$response['success'] = false;
		$response['message'] = 'Permintaan gagal';
	}
	echo json_encode($response);
?>
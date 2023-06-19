<?php 	
	if($_SERVER['REQUEST_METHOD']=='POST'){
		require_once('../koneksi.php');

		//set variable
		$branchId = $_POST['branchId'];
		$date = date('Y-m-d');
		$dueDate = date('Y-m-d',strtotime($_POST['dueDate']));
		$invoiceNo = 'INV-J-T'.$branchId.'-'.date('dmY').'-'.date('His');
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
			$stmt = $conn->prepare("INSERT INTO sales (branchId, "."date".", dueDate, invoiceNo, name, paymentStatus, phone, totalPrice) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
			$stmt->execute([$branchId, $date, $dueDate, $invoiceNo, $name, $paymentStatus, $phone, $totalPrice]);
			$saleId = $conn->lastInsertId();

			//create sale details
			for ($i=0; $i < count($_POST['idList']); $i++) {
				//set variable
				$table = '';
				$foreignTable = '';
				$foreignIdField = '';
				$itemType = $_POST['typeList'][$i];
				$itemId = $_POST['idList'][$i];
				$itemPrice = $_POST['pList'][$i];
				$itemAmount = $_POST['qtyList'][$i];

		        if($itemType != 'otherService'){
					$stmt2 = $conn->prepare("SELECT id, stock FROM products WHERE id = ?");		        	
		        	if($itemType == 'print' || $itemType == 'photocopy'){
				        if($itemType == 'print'){
							$table = 'print_sale_details';
							$foreignTable = 'print_services';
							$foreignIdField = 'printServiceId';
				        }else{
							$table = 'photocopy_sale_details';
							$foreignTable = 'photocopy_services';
							$foreignIdField = 'photocopyServiceId';
						}

						$stmt = $conn->prepare("SELECT paperProductId FROM $foreignTable WHERE id = ?");
						$stmt->execute([$itemId]);
						$service = $stmt->fetch();						

						$stmt2->execute([$service['paperProductId']]);
					}else{
						$table = 'product_sale_details';
						$foreignIdField = 'productId';		

						$stmt2->execute([$itemId]);
					}

					$product = $stmt2->fetch();

					//update stock
					$currStock = $product['stock'] - $itemAmount;
					$stmt = $conn->prepare("UPDATE products SET stock = ? WHERE id = ?");
				  	$stmt->execute([$currStock, $product['id']]);
					$stmt = $conn->prepare("INSERT INTO stock_histories (productId, "."date".", changeAmount, price, currStock, changeMethod, notes) VALUES (?, ?, ?, ?, ?, ?, ?) ");
					$stmt->execute([$product['id'], $date, -($itemAmount), $itemPrice, $currStock, 'Penjualan', $invoiceNo]);		

		        }else{
		        	$table = 'service_sale_details';
					$foreignIdField = 'serviceId';	
		        }

				$stmt = $conn->prepare("INSERT INTO $table (saleId, $foreignIdField, price, qty) VALUES (?, ?, ?, ?)");
		  		$stmt->execute([$saleId, $itemId, $itemPrice, $itemAmount]);
			}

			//create payment receptions
			$stmt = $conn->prepare("INSERT INTO payment_receptions (saleId, "."date".", amount) VALUES (?, ?, ?)");
			$stmt->execute([$saleId, $date, $payAmount]);
		
			$response['success'] = true;
			if($kembalian >=0){
				$response['message'] = 'Penjualan lunas';
			}else{
				$response['message'] = 'Penjualan berhasil';
			}
			$response['totalPrice'] = $kembalian;
		}catch(PDOException $e){
			echo $e->postMessage();
			$stmt = $conn->prepare("DELETE FROM product_sale_details WHERE saleId = ?");
			$stmt->execute([$saleId]);
			$stmt = $conn->prepare("DELETE FROM photocopy_sale_details WHERE saleId = ?");
			$stmt->execute([$saleId]);
			$stmt = $conn->prepare("DELETE FROM print_sale_details WHERE saleId = ?");
			$stmt->execute([$saleId]);
			$stmt = $conn->prepare("DELETE FROM service_sale_details WHERE saleId = ?");
			$stmt->execute([$saleId]);
			$stmt = $conn->prepare("DELETE FROM payment_receptions WHERE saleId = ?");
			$stmt->execute([$saleId]);
			$stmt = $conn->prepare("DELETE FROM sales WHERE id = ?");
			$stmt->execute([$saleId]);
			$response['success'] = false;
			$response['message'] = 'Penjualan gagal, silakan coba lagi';		
		}

		$conn = null;
	}else{
		$response['success'] = false;
		$response['message'] = 'Permintaan gagal';
	}
	echo json_encode($response);
?>

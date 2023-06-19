<?php
	if($_SERVER['REQUEST_METHOD']=='POST'){
		require_once('../koneksi.php');

		//set variable
		$id = $_POST['id'];
		$name = $_POST['name'];
		$purchasePrice = $_POST['purchasePrice'];
		$sellPrice = $_POST['sellPrice'];
		$minStock = $_POST['minStock'];
		$stock = $_POST['stock'];
		$isPaper = filter_var($_POST['isPaper'], FILTER_VALIDATE_BOOLEAN)? 1:0;
		$oldImageName = $_POST['oldImageName'];
		$image_path = $image_path.'product/';

		$stmt2 = $conn->prepare("SELECT id, stock FROM products WHERE id = ?");
		$stmt2->execute([$id]);
		$product = $stmt2->fetch();

		if(isset($_FILES['image'])){
			//if image is exist
			$newImage = $_FILES['image'];
			$ext = pathinfo($newImage['name'], PATHINFO_EXTENSION);
			$imageName = time().'.'.$ext;
		
			if(move_uploaded_file($newImage['tmp_name'], $image_path.$imageName)){
				//if move file is successfull
				try{
					$stmt = $conn->prepare("UPDATE products SET name = ?, minStock = ?, stock = ?, purchasePrice = ?, sellPrice = ?, isPaper = ?, image = ? WHERE id = ?");
					$stmt->execute([$name, $minStock, $stock, $purchasePrice, $sellPrice, $isPaper, $imageName, $id]);
					if(!empty($oldImageName)){
						if(file_exists($image_path.$oldImageName)){
							unlink($image_path.$oldImageName);
						}
					}

					if($stock != $product['stock']){
						$diffAmount = $stock - $product['stock'];

						$stmt = $conn->prepare("INSERT INTO stock_histories (productId, "."date".", changeAmount, price, currStock, changeMethod, notes) VALUES (?, ?, ?, ?, ?, ?, ?) ");
						$stmt->execute([$product['id'], date('Y-m-d'), $diffAmount, $purchasePrice, $stock, 'Penyesuaian Stok', '']);
					}

					$response['success'] = true;
					$response['message'] = 'Update produk berhasil';
				}catch(PDOException $e){
					$response['success'] = false;
					$response['message'] = 'Update produk gagal';
				}
			}else{
				$response['success'] = false;
				$response['message'] = 'Upload gambar gagal';
			}
		}else{
			$deleteImage = filter_var($_POST['deleteImage'], FILTER_VALIDATE_BOOLEAN);
			try{
				if($deleteImage){
					$stmt = $conn->prepare("UPDATE products SET name = ?, minStock = ?, stock = ?, purchasePrice = ?, sellPrice = ?, isPaper = ?, image = ? WHERE id = ?");
					$stmt->execute([$name, $minStock, $stock, $purchasePrice, $sellPrice, $isPaper, '', $id]);
					//ingin hapus gambar
					if(file_exists($image_path.$oldImageName)){
						unlink($image_path.$oldImageName);
					}
				}else{
					$stmt = $conn->prepare("UPDATE products SET name = ?, minStock = ?, stock = ?, purchasePrice = ?, sellPrice = ?, isPaper = ? WHERE id = ?");
					$stmt->execute([$name, $minStock, $stock, $purchasePrice, $sellPrice, $isPaper, $id]);
				}

				if($stock != $product['stock']){
					$diffAmount = $stock - $product['stock'];

					$stmt = $conn->prepare("INSERT INTO stock_histories (productId, "."date".", changeAmount, price, currStock, changeMethod, notes) VALUES (?, ?, ?, ?, ?, ?, ?) ");
					$stmt->execute([$product['id'], date('Y-m-d'), $diffAmount, $purchasePrice, $stock, 'Penyesuaian Stok', '']);
				}
				
				$response['success'] = true;
				$response['message'] = 'Update produk berhasil';
			}catch(PDOException $e){
				$response['success'] = false;
				$response['message'] = 'Update produk gagal';
			}
		}
		$conn = null;
	}else{
		$response['success'] = false;
		$response['message'] = 'Permintaan gagal';
	}
	echo json_encode($response);
	
?>
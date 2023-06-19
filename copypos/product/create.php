<?php 	
	if($_SERVER['REQUEST_METHOD']=='POST'){
		require_once('../koneksi.php');

		//set variable
		$branchId = $_POST['branchId'];
		$name = $_POST['name'];
		$purchasePrice = $_POST['purchasePrice'];
		$sellPrice = $_POST['sellPrice'];
		$minStock = $_POST['minStock'];
		$stock = $_POST['stock'];
		$isPaper = filter_var($_POST['isPaper'], FILTER_VALIDATE_BOOLEAN)? 1:0;
		$image_path = $image_path.'product/';

		if(isset($_FILES['image'])){
			//if image is exist
			$image = $_FILES['image'];
			$ext = pathinfo($image['name'], PATHINFO_EXTENSION);
			$imageName = time().'.'.$ext;
		
			if(move_uploaded_file($image['tmp_name'], $image_path.$imageName)){
				//if move file is successfull
				try{
					$stmt = $conn->prepare("INSERT INTO products (branchId, name, stock, minStock, purchasePrice, sellPrice, isPaper, image) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
					$stmt->execute([$branchId, $name, $stock, $minStock, $purchasePrice,$sellPrice, $isPaper, $imageName]);
					$productId = $conn->lastInsertId();

					//create stock history
					if($stock > 0){
						$stmt = $conn->prepare("INSERT INTO stock_histories (productId, "."date".", changeAmount, price, currStock, changeMethod, notes) VALUES (?, ?, ?, ?, ?, ?, ?) ");
						$stmt->execute([$productId, date('Y-m-d'), $stock, $purchasePrice, $stock, 'Persediaan barang baru', '']);
					}

					$response['success'] = true;
					$response['message'] = 'Tambah produk berhasil';
				}catch(PDOException $e){
					$response['success'] = false;
					$response['message'] = 'Tambah produk gagal';
				}
			}else{
				$response['success'] = false;
				$response['message'] = 'Upload gambar gagal';
			}	
		}else{

			try{
				$stmt = $conn->prepare("INSERT INTO products (branchId, name, stock, minStock, purchasePrice, sellPrice, isPaper, image) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
				$stmt->execute([$branchId, $name, $stock, $minStock, $purchasePrice,$sellPrice, $isPaper, '']);
				$productId = $conn->lastInsertId();

				//create stock history
				if($stock > 0){
					$stmt = $conn->prepare("INSERT INTO stock_histories (productId, "."date".", changeAmount, price, currStock, changeMethod, notes) VALUES (?, ?, ?, ?, ?, ?, ?) ");
					$stmt->execute([$productId, date('Y-m-d'), $stock, $purchasePrice, $stock, 'Persediaan barang baru', '']);
				}

				$response['success'] = true;
				$response['message'] = 'Tambah produk berhasil';
			}catch(PDOException $e){
				$response['success'] = false;
				$response['message'] = 'Tambah produk gagal';
			}

		}
		$conn = null;
	}else{
		$response['success'] = false;
		$response['message'] = 'Error';	
	}
	echo json_encode($response);
?>
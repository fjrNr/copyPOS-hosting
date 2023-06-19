<?php 	
	if($_SERVER['REQUEST_METHOD']=='POST'){
		require_once('../koneksi.php');

		//set variable
		$branchId = $_POST['branchId'];
		$name = $_POST['name'];
		$phone = $_POST['phone'];
		$uname = $_POST['username'];
		$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
		$allSell = filter_var($_POST['allSell'], FILTER_VALIDATE_BOOLEAN) ? 1 : 0;
		$allPurchase = filter_var($_POST['allPurchase'], FILTER_VALIDATE_BOOLEAN) ? 1 : 0;
		$allStock = filter_var($_POST['allStock'], FILTER_VALIDATE_BOOLEAN) ? 1 : 0;
		$allExp = filter_var($_POST['allExp'], FILTER_VALIDATE_BOOLEAN) ? 1 : 0;
		$image_path = $image_path.'employee/';
		
		if(isset($_FILES['image'])){
			//if image is exist
			$image = $_FILES['image'];
			$ext = pathinfo($image['name'], PATHINFO_EXTENSION);
			$imageName = time().'.'.$ext;
		
			if(move_uploaded_file($image['tmp_name'], $image_path.$imageName)){
				//if move file is successfull
				try{
					$stmt = $conn->prepare("INSERT INTO employees (branchId, name, phone, username, password, allowSale, allowPurchase, allowStock, allowExpense, image) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
					$stmt->execute([$branchId, $name, $phone, $uname, $password, $allSell, $allPurchase, $allStock, $allExp, $imageName]);
					$response['success'] = true;
					$response['message'] = 'Tambah pegawai berhasil';
				}catch(PDOException $e){
					$response['success'] = false;
					$response['message'] = 'Tambah pegawai gagal';
				}

			}else{
				$response['success'] = false;
				$response['message'] = 'Upload gambar gagal';
			}	
		}else{
			try{
				$stmt = $conn->prepare("INSERT INTO employees (branchId, name, phone, username, password, allowSale, allowPurchase, allowStock, allowExpense, image) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
				$stmt->execute([$branchId, $name, $phone, $uname, $password, $allSell, $allPurchase, $allStock, $allExp, '']);
				$response['success'] = true;
				$response['message'] = 'Tambah pegawai berhasil';
			}catch(PDOException $e){
				$response['success'] = false;
				$response['message'] = 'Tambah pegawai gagal';
			}
			// $response['success'] = false;
			// $response['message'] = $allSell.' '.$allPurchase.' '.$allStock.' '.$allExp;
		}
		$conn = null;
	}else{
		$response['success'] = false;
		$response['message'] = 'Permintaan gagal';
	}
	echo json_encode($response);
?>
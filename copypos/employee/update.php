<?php
	if($_SERVER['REQUEST_METHOD']=='POST'){
		require_once('../koneksi.php');

		//set variable
		$id = $_POST['id'];
		$name = $_POST['name'];
		$phone = $_POST['phone'];
		$uname = $_POST['username'];
		// $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
		$oldImageName = $_POST['oldImageName'];
		$allSell = filter_var($_POST['allSell'], FILTER_VALIDATE_BOOLEAN)? 1:0;
		$allPurchase = filter_var($_POST['allPurchase'], FILTER_VALIDATE_BOOLEAN)? 1:0;
		$allStock = filter_var($_POST['allStock'], FILTER_VALIDATE_BOOLEAN)? 1:0;
		$allExp = filter_var($_POST['allExp'], FILTER_VALIDATE_BOOLEAN)? 1:0;
		$image_path = $image_path.'employee/';

		//check registered user
		$stmt = $conn->prepare("SELECT * FROM employees WHERE username = ? OR phone = ?");
        $stmt->execute([$uname, $phone]);
        $row = $stmt->fetch();
		
		if(isset($_FILES['image'])){
			//if image is exist
			$newImage = $_FILES['image'];
			$ext = pathinfo($newImage['name'], PATHINFO_EXTENSION);
			$imageName = time().'.'.$ext;
		
			if(move_uploaded_file($newImage['tmp_name'], $image_path.$imageName)){
				//if move file is successfull
				try{
					if($row['username'] == $uname && $row['phone'] == $phone){
						$stmt = $conn->prepare("UPDATE employees SET name = ?, allowSale = ? , allowPurchase = ? , allowStock = ? , allowExpense = ?, image = ? WHERE id = ?");
						$stmt->execute([$name, $allSell, $allPurchase, $allStock, $allExp, $imageName, $id]);
					}else if($row['username'] == $uname){
						$stmt = $conn->prepare("UPDATE employees SET name = ?, phone = ?, allowSale = ? , allowPurchase = ? , allowStock = ? , allowExpense = ?, image = ? WHERE id = ?");
						$stmt->execute([$name, $phone, $allSell, $allPurchase, $allStock, $allExp, $imageName, $id]);
					}else if($row['phone'] == $phone){
						$stmt = $conn->prepare("UPDATE employees SET name = ?, username = ?, allowSale = ? , allowPurchase = ? , allowStock = ? , allowExpense = ?, image = ? WHERE id = ?");
						$stmt->execute([$name, $uname, $allSell, $allPurchase, $allStock, $allExp, $imageName, $id]);

					}else{
						$stmt = $conn->prepare("UPDATE employees SET name = ?, username = ?, phone = ?, allowSale = ? , allowPurchase = ? , allowStock = ? , allowExpense = ?, image = ? WHERE id = ?");
						$stmt->execute([$name, $uname, $phone, $allSell, $allPurchase, $allStock, $allExp, $imageName, $id]);
					}
					if(!empty($oldImageName)){
						if(file_exists($image_path.$oldImageName)){
							unlink($image_path.$oldImageName);
						}
					}
					$response['success'] = true;
					$response['message'] = 'Update pegawai Berhasil';
				}catch(PDOException $e){
					$response['success'] = false;
					$response['message'] = 'Update pegawai gagal';				
				}

			}else{
				$response['success'] = false;
				$response['message'] = 'Upload gambar gagal';
			}
		}else{
			$deleteImage = filter_var($_POST['deleteImage'], FILTER_VALIDATE_BOOLEAN);
			try{
				if($deleteImage){
					if($row['username'] == $uname && $row['phone'] == $phone){
						$stmt = $conn->prepare("UPDATE employees SET name = ?, allowSale = ? , allowPurchase = ? , allowStock = ? , allowExpense = ?, image = ? WHERE id = ?");
						$stmt->execute([$name, $allSell, $allPurchase, $allStock, $allExp, '', $id]);
					}else if($row['username'] == $uname){
						$stmt = $conn->prepare("UPDATE employees SET name = ?, phone = ?, allowSale = ? , allowPurchase = ? , allowStock = ? , allowExpense = ?, image = ? WHERE id = ?");
						$stmt->execute([$name, $phone, $allSell, $allPurchase, $allStock, $allExp, '', $id]);
					}else if($row['phone'] == $phone){
						$stmt = $conn->prepare("UPDATE employees SET name = ?, username = ?, allowSale = ? , allowPurchase = ? , allowStock = ? , allowExpense = ?, image = ? WHERE id = ?");
						$stmt->execute([$name, $uname, $allSell, $allPurchase, $allStock, $allExp, '', $id]);
					}else{
						$stmt = $conn->prepare("UPDATE employees SET name = ?, username = ?, phone = ?, allowSale = ? , allowPurchase = ? , allowStock = ? , allowExpense = ?, image = ? WHERE id = ?");
						$stmt->execute([$name, $uname, $phone, $allSell, $allPurchase, $allStock, $allExp, '', $id]);
					}
					//hapus gambar
					if(file_exists($image_path.$oldImageName)){
						unlink($image_path.$oldImageName);
					}
				}else{
					if($row['username'] == $uname && $row['phone'] == $phone){
						$stmt = $conn->prepare("UPDATE employees SET name = ?, allowSale = ? , allowPurchase = ? , allowStock = ? , allowExpense = ? WHERE id = ?");
						$stmt->execute([$name, $allSell, $allPurchase, $allStock, $allExp, $id]);
					}else if($row['username'] == $uname){
						$stmt = $conn->prepare("UPDATE employees SET name = ?, phone = ?, allowSale = ? , allowPurchase = ? , allowStock = ? , allowExpense = ? WHERE id = ?");
						$stmt->execute([$name, $phone, $allSell, $allPurchase, $allStock, $allExp, $id]);
					}else if($row['phone'] == $phone){
						$stmt = $conn->prepare("UPDATE employees SET name = ?, username = ?, allowSale = ? , allowPurchase = ? , allowStock = ? , allowExpense = ? WHERE id = ?");
						$stmt->execute([$name, $uname, $allSell, $allPurchase, $allStock, $allExp, $id]);
					}else{
						$stmt = $conn->prepare("UPDATE employees SET name = ?, username = ?, phone = ?, allowSale = ? , allowPurchase = ? , allowStock = ? , allowExpense = ? WHERE id = ?");
						$stmt->execute([$name, $uname, $phone, $allSell, $allPurchase, $allStock, $allExp, $id]);
					}
				}
				$response['success'] = true;
				$response['message'] = 'Update pegawai berhasil';
			}catch(PDOException $e){
				$response['success'] = false;
				$response['message'] = 'Update pegawai gagal';
			}
		}
		$conn = null;
	}else{
		$response['success'] = false;
		$response['message'] = 'Permintaan gagal';
	}
	echo json_encode($response);
?>
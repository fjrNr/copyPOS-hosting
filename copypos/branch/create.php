<?php 	
	if($_SERVER['REQUEST_METHOD']=='POST'){
		require_once('../koneksi.php');

		//set variable
		$ownerId = $_POST['ownerId'];
		$name = $_POST['name'];
		$address = $_POST['address'];
		$phone = $_POST['phone'];
		$image_path = $image_path.'branch/';

		if(isset($_FILES['image'])){
			//if image is exist
			$image = $_FILES['image'];
			$ext = pathinfo($image['name'], PATHINFO_EXTENSION);
			$imageName = time().'.'.$ext;
		
			if(move_uploaded_file($image['tmp_name'], $image_path.$imageName)){
				//if move file is successfull
				try{
					$stmt = $conn->prepare("INSERT INTO branchs (ownerId, name, address, phone, image) VALUES (?, ?, ?, ?, ?)");
					$stmt->execute([$ownerId, $name, $address, $phone, $imageName]);
					$response['success'] = true;
					$response['message'] = 'Tambah toko berhasil';
				}catch(PDOException $e) {
					$response['success'] = false;
					$response['message'] = 'Tambah toko gagal';
				}
			}else{
				$response['success'] = false;
				$response['message'] = 'Upload gambar gagal';
			}	

		}else{
			try{
				$stmt = $conn->prepare("INSERT INTO branchs (ownerId, name, address, phone, image) VALUES (?, ?, ?, ?, ?)");
				$stmt->execute([$ownerId, $name, $address, $phone, '']);
				$response['success'] = true;
				$response['message'] = 'Tambah toko berhasil';
			}catch(PDOException $e) {
				$response['success'] = false;
				$response['message'] = 'Tambah toko gagal';
			}
		}

		$conn = null;
	}else{
		$response['success'] = false;
		$response['message'] = 'Permintaan gagal';
	}
	echo json_encode($response);
?>
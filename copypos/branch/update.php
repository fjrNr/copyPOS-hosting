<?php
	if($_SERVER['REQUEST_METHOD']=='POST'){
		require_once('../koneksi.php');

		//set variable
		$id = $_POST['id'];
		$name = $_POST['name'];
		$address = $_POST['address'];
		$phone = $_POST['phone'];
		$oldImageName = $_POST['oldImageName'];
		$image_path = $image_path.'branch/';

		if(isset($_FILES['image'])){
			//if image is exist
			$newImage = $_FILES['image'];
			$ext = pathinfo($newImage['name'], PATHINFO_EXTENSION);
			$imageName = time().'.'.$ext;
		
			if(move_uploaded_file($newImage['tmp_name'], $image_path.$imageName)){
				//if move file is successfull
				try{
					$stmt = $conn->prepare("UPDATE branchs SET name = ?, address = ?, phone = ?, image = ? WHERE id = ?");
					$stmt->execute([$name, $address, $phone, $imageName, $id]);
					if(!empty($oldImageName)){
						if(file_exists($image_path.$oldImageName)){
							unlink($image_path.$oldImageName);
						}
					}
					$response['success'] = true;
					$response['message'] = 'Update toko berhasil';
				}catch(PDOException $e){
					$response['success'] = false;
					$response['message'] = 'Update toko gagal';
				}

			}else{
				$response['success'] = false;
				$response['message'] = 'Upload gambar gagal';
			}

		}else{
			$deleteImage = filter_var($_POST['deleteImage'], FILTER_VALIDATE_BOOLEAN);
			try{
				if($deleteImage){
					$stmt = $conn->prepare("UPDATE branchs SET name = ?, address = ?, phone = ?, image = ? WHERE id = ?");
					$stmt->execute([$name, $address, $phone, '', $id]);
					//hapus gambar
					if(file_exists($image_path.$oldImageName)){
						unlink($image_path.$oldImageName);
					}

				}else{
					$stmt = $conn->prepare("UPDATE branchs SET name = ?, address = ?, phone = ? WHERE id = ?");
					$stmt->execute([$name, $address, $phone, $id]);
				}
				$response['success'] = true;
				$response['message'] = 'Update toko berhasil';

			}catch(PDOException $e){
				$response['success'] = false;
				$response['message'] = 'Update toko gagal';
			}
		}
		$conn = null;
	}else{
		$response['success'] = false;
		$response['message'] = 'Error';
	}
	echo json_encode($response);
	
?>
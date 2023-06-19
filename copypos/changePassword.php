<?php 
	if($_SERVER['REQUEST_METHOD']=='POST'){
		require_once('koneksi.php');
	
		$table = '';	
		$type = $_POST['personType'];
		if($type == 'owner'){
			$table = 'owners';	
		}else{
			$table = 'employees';
		}

		$id = $_POST['id'];
		$oldPassword = $_POST['oldPassword'];
		$newPassword = $_POST['newPassword'];

		$query = mysqli_query($con, "SELECT * FROM $table WHERE id = '$id'");
		$row = mysqli_fetch_assoc($query);

		if($oldPassword != $newPassword){
			if(password_verify($oldPassword, $row['password'])){
				$hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
				$sql = "UPDATE $table SET password = '$hashedPassword' WHERE id = '$id'";

				if(mysqli_query($con, $sql)){
					$response['success'] = true;
					$response['message'] = 'Ubah password berhasil.';
				}else{
					$response['success'] = false;
					$response['message'] = 'Gagal ubah password baru.';
				}
			}
		}else{
			$response['success'] = false;
			$response['message'] = 'Password lama tidak boleh sama dengan password baru.';
		}
		
		mysqli_close($con);
	}else{
		$response['success'] = false;
		$response['message'] = 'Error';
	}

	echo json_encode($response);
 ?>
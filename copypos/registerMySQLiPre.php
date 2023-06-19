<?php 
	if($_SERVER['REQUEST_METHOD']=='GET'){
		require_once('koneksi.php');
		
		$name = $_GET['name'];
		$phone = $_GET['phone'];
		$email = $_GET['email'];
		$password = password_hash($_GET['password'], PASSWORD_DEFAULT);

		$stmt = $con->prepare("INSERT INTO owners (name, phone, email, password) VALUES (?, ?, ?, ?)");
		$stmt->bind_param("ssss", $name, $phone, $email, $password);
		$stmt->execute();
		if($stmt){
			$response['success'] = true;
			$response['message'] = 'Registrasi berhasil. Selamat datang di Copy POS!';
		}else{
			$response['success'] = false;
			$response['message'] = 'Email atau nomor telepon telah terdaftar';
		}
		
		$stmt->close();
		$con->close();
	}else{
		$response['success'] = false;
		$response['message'] = 'Error';
	}

	echo json_encode($response);
 ?>
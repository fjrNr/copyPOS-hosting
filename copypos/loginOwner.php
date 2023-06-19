<?php 
	if($_SERVER['REQUEST_METHOD']=='POST'){
		require_once('koneksi.php');
		
		//set variable
		$email = $_POST['email'];
		$password = $_POST['password'];

		//check registered user
		$stmt = $conn->prepare("SELECT * FROM owners WHERE email = ?");
        $stmt->execute([$email]);
        $row = $stmt->fetch();

		if (password_verify($password, $row['password'])){
			//if the password is right
			$response['success'] = true;
			$response['message'] = 'Selamat datang kembali di Copy POS';
			$response['id'] = $row['id'];
			$response['name'] = $row['name'];
			$response['imageName'] = $row['image'];
		}else if(strcmp($email, $row['email']) == 0){
			$response['success'] = false;
			$response['message'] = 'Password salah';
		}else{
			$response['success'] = false;
			$response['message'] = 'Email tidak terdaftar, silakan daftar terlebih dahulu';
		}
		
		$conn = null;
	}else{
		$response['success'] = false;
        $response['message'] = 'Permintaan Gagal';
	}
	echo json_encode($response);
 ?>
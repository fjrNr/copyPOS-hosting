<?php 
	if($_SERVER['REQUEST_METHOD']=='POST'){
		require_once('koneksi.php');
		
		//set variable
		$name = $_POST['username'];
		$password = $_POST['password'];

		//check registered user
		$stmt = $conn->prepare("SELECT * FROM employees WHERE username = ?");
        $stmt->execute([$name]);
        $row = $stmt->fetch();

		if (password_verify($password, $row['password'])){
			//if the password is right
			$response['success'] = true;
			$response['message'] = 'Selamat datang kembali di Copy POS';
			$response['id'] = $row['id'];
			$response['branchId'] = $row['branchId'];
			$response['name'] = $row['name'];
			$response['imageName'] = $row['image'];
			$response['allSell'] = boolval($row['allowSale']);
			$response['allPurchase'] = boolval($row['allowPurchase']);
			$response['allStock'] = boolval($row['allowStock']);
			$response['allExp'] = boolval($row['allowExpense']);
		}else{ 
			$response['success'] = false;
			$response['message'] = 'Username atau password salah';
		}
		$conn = null;
	}else{
		$response['success'] = false;
        $response['message'] = 'Permintaan Gagal';
	}
	echo json_encode($response);
?>
<?php	
	if($_SERVER['REQUEST_METHOD']=='POST'){
		require_once('../koneksi.php');

		//set variable
		$id = $_POST['id'];
		$name = $_POST['name'];
		$price = $_POST['sellPrice'];

		try{
			$stmt = $conn->prepare("UPDATE services SET name = ?, sellPrice = ? WHERE id = ?");
			$stmt->execute([$name, $price, $id]);
			$response['success'] = true;
			$response['message'] = 'Update jasa lain berhasil';
		}catch(PDOException $e){
			$response['success'] = false;
			$response['message'] = 'Update jasa lain gagal';
		}
		$conn = null;
	}else{
		$response['success'] = false;
		$response['message'] = 'Permintaan gagal';
	}
	echo json_encode($response);
?>
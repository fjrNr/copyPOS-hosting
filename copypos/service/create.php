<?php 	
	if($_SERVER['REQUEST_METHOD']=='POST'){
		require_once('../koneksi.php');

		//set variable
		$branchId = $_POST['branchId'];
		$name = $_POST['name'];
		$price = $_POST['sellPrice'];
		$table = 'services';

		try{
			$stmt = $conn->prepare("INSERT INTO $table (branchId, name, sellPrice) VALUES (?,?,?)");
			$stmt->execute([$branchId, $name, $price]);
			$response['success'] = true;
			$response['message'] = 'Tambah jasa berhasil';
		}catch(PDOException $e){
			$response['success'] = false;
			$response['message'] = 'Tambah jasa gagal';
		}
		$conn = null;

	}else{
		$response['success'] = false;
		$response['message'] = 'Permintaan gagal';
	}
	echo json_encode($response);
?>
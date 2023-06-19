<?php 	
	if($_SERVER['REQUEST_METHOD']=='POST'){
		require_once('../koneksi.php');

		//set variable
		$branchId = $_POST['branchId'];
		$paperId = $_POST['paperId'];
		$name = $_POST['name'];
		$price = $_POST['sellPrice'];

		try{
			$stmt = $conn->prepare("INSERT INTO print_services (branchId, paperProductId, name, sellPrice) VALUES (?, ?, ?, ?)");
			$stmt->execute([$branchId, $paperId, $name, $price]);
			$response['success'] = true;
			$response['message'] = 'Tambah jasa cetak berhasil';
		}catch(PDOException $e){
			$response['success'] = false;
			$response['message'] = 'Tambah jasa cetak gagal';
		}
		$conn = null;

	}else{
		$response['success'] = false;
		$response['message'] = 'Permintaan gagal';
	}
	echo json_encode($response);
?>
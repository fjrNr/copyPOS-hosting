<?php 	
	if($_SERVER['REQUEST_METHOD']=='POST'){
		require_once('../koneksi.php');

		//set variable
		$id = $_POST['id'];
		$paperId = $_POST['paperId'];
		$name = $_POST['name'];
		$price = $_POST['sellPrice'];

		try{
			$stmt = $conn->prepare("UPDATE photocopy_services SET paperProductId = ?, name = ?, sellPrice = ? WHERE id = ?");
			$stmt->execute([$paperId, $name, $price, $id]);
			$response['success'] = true;
			$response['message'] = 'Update jasa fotokopi berhasil';
		}catch(PDOException $e){
			$response['success'] = false;
			$response['message'] = 'Update jasa fotokopi gagal';
		}
		$conn = null;
	}else{
		$response['success'] = false;
		$response['message'] = 'Permintaan gagal';
	}
	echo json_encode($response);
?>
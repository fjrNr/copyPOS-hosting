<?php
	if($_SERVER['REQUEST_METHOD']=='POST'){
		require_once('../koneksi.php');

		//set variable
		$id = $_POST['id'];
		$name = $_POST['name'];
		$date = date('Y-m-d',strtotime($_POST['date']));
		$amount = $_POST['amount'];

		try{
			$stmt = $conn->prepare("UPDATE expenses SET name = ?, "."date"." = ?, amount = ? WHERE id = ?");
			$stmt->execute([$name, $date, $amount, $id]);
			$response['success'] = true;
			$response['message'] = 'Update pengeluaran berhasil';
		}catch(PDOException $e){
			$response['success'] = false;
			$response['message'] = 'Update pengeluaran gagal';
		}
		$conn = null;
	}else{
		$response['success'] = false;
		$response['message'] = 'Permintaan gagal';
	}
	echo json_encode($response);
?>
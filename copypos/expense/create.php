<?php 	
	if($_SERVER['REQUEST_METHOD']=='POST'){
		require_once('../koneksi.php');

		//set variable
		$branchId = $_POST['branchId'];
		$name = $_POST['name'];
		$date = date('Y-m-d',strtotime($_POST['date']));
		$amount = $_POST['amount'];

		try{
			$stmt = $conn->prepare("INSERT INTO expenses (branchId, name, amount, "."date".") VALUES (?,?,?,?)");
			$stmt->execute([$branchId, $name, $amount, $date]);
			$response['success'] = true;
			$response['message'] = 'Tambah pengeluaran berhasil';
		}catch(PDOException $e){
			$response['success'] = false;
			$response['message'] = 'Tambah pengeluaran gagal';
		}

		$conn = null;
	}else{
		$response['success'] = false;
		$response['message'] = 'Permintaan gagal';
	}
	echo json_encode($response);
?>
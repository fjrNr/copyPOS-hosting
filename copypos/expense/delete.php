<?php
	$id = $_POST['id'];
	$sql = "DELETE FROM expenses WHERE id='$id'";
	require_once('../koneksiMySQL.php');

	if(mysqli_query($con, $sql)){
		$response['success'] = true;
		$response['message'] = 'Hapus biaya pengeluaran berhasil';
	}else{
		$response['success'] = false;
		$response['message'] = 'Gagal hapus biaya pengeluaran';
	}
	mysqli_close($con);
	echo json_encode($response);
?>
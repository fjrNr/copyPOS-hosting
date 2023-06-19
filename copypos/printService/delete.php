<?php
	$id = $_POST['id'];
	$sql = "DELETE FROM print_services WHERE id='$id'";
	
	require_once('../koneksiMySQL.php');

	if(mysqli_query($con, $sql)){
		$response['success'] = true;
		$response['message'] = 'Hapus barang berhasil';
	}else{
		$response['success'] = false;
		$response['message'] = 'Gagal hapus barang';
	}
	mysqli_close($con);
	echo json_encode($response);
?>
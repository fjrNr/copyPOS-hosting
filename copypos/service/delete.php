<?php
	$id = $_POST['id'];
	$sql = "DELETE FROM services WHERE id='$id'";
	require_once('../koneksiMySQL.php');

	if(mysqli_query($con, $sql)){
		$response['success'] = true;
		$response['message'] = 'Hapus jasa berhasil';
	}else{
		$response['success'] = false;
		$response['message'] = 'Gagal hapus jasa';
	}
	mysqli_close($con);
	echo json_encode($response);
?>
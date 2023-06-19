<?php
	require_once('../koneksiMySQL.php');

	$id = $_POST['id'];
	$imageName = $_POST['imageName'];

	$image_path = $image_path.'employee/';
	$sql = "DELETE FROM employees WHERE id='$id'";

	if(mysqli_query($con, $sql)){
		if(!empty($imageName)){
			if(file_exists($image_path.$imageName)){
				unlink($image_path.$imageName);
			}
		}
		$response['success'] = true;
		$response['message'] = 'Hapus pegawai berhasil';
	}else{
		$response['success'] = false;
		$response['message'] = 'Gagal hapus pegawai';
	}
	mysqli_close($con);
	echo json_encode($response);
?>
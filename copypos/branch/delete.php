<?php
	require_once('../koneksiMySQL.php');

	$id = $_POST['id'];
	$imageName = $_POST['imageName'];

	$image_path = $image_path.'branch/';
	$sql = "DELETE FROM branchs WHERE id='$id'";

	if(mysqli_query($con, $sql)){
		if(!empty($imageName)){
			if(file_exists($image_path.$imageName)){
				unlink($image_path.$imageName);
			}
		}
		$response['success'] = true;
		$response['message'] = 'Berhasil hapus toko';
	}else{
		$response['success'] = false;
		$response['message'] = 'Gagal hapus toko';
	}
	mysqli_close($con);
	echo json_encode($response);
?>
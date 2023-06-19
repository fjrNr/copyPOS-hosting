<?php 
	if($_SERVER['REQUEST_METHOD']=='POST'){
		require_once('koneksi.php');

		//set variable
		$id = $_POST['ownerId'];
		$name = $_POST['name'];
		$phone = $_POST['phone'];
		$email = $_POST['email'];

		//check registered user
		$stmt = $conn->prepare("SELECT * FROM owners WHERE email = ? OR phone = ?");
        $stmt->execute([$email, $phone]);
        $row = $stmt->fetch();

        try{
        	if($row['phone'] == $phone && $row['email'] == $email){
				$stmt = $conn->prepare("UPDATE owners SET name = ? WHERE id = ?");
				$stmt->execute([$name, $id]);
			}else if($row['phone'] == $phone){
				$stmt = $conn->prepare("UPDATE owners SET name = ?, email = ? WHERE id = ?");
				$stmt->execute([$name, $email, $id]);
			}else if($row['email'] == $email){
				$stmt = $conn->prepare("UPDATE owners SET name = ?, phone = ? WHERE id = ?");
				$stmt->execute([$name, $phone, $id]);
			}else{
				$stmt = $conn->prepare("UPDATE owners SET name = ?, phone = ?, email = ? WHERE id = ?");
				$stmt->execute([$name, $phone, $email, $id]);
			}	
			$response['success'] = true;
			$response['message'] = 'Update profile pengguna berhasil';
        }catch(PDOException $e){
        	$response['success'] = false;
			$response['message'] = 'Update profile pengguna gagal';
        }

		$conn = null;
	}else{
		$response['success'] = false;
		$response['message'] = 'Permintaan gagal';
	}
	echo json_encode($response);
 ?>
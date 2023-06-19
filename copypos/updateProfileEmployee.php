<?php 
	if($_SERVER['REQUEST_METHOD']=='POST'){		
		require_once('koneksi.php');

		//set variable
		$id = $_POST['employeeId'];
		$name = $_POST['name'];
		$uname = $_POST['username'];
		$phone = $_POST['phone'];

		//check registered user
		$stmt = $conn->prepare("SELECT * FROM employees WHERE username = ? OR phone = ?");
        $stmt->execute([$uname, $phone]);
        $row = $stmt->fetch();

   		try{
   			if($row['phone'] == $phone && $row['username'] == $username){
				$stmt = $conn->prepare("UPDATE employees SET name = ? WHERE id = ?");
				$stmt->execute([$name, $id]);
			}else if($row['username'] == $username){
				$stmt = $conn->prepare("UPDATE employees SET name = ?, phone = ? WHERE id = ?");
				$stmt->execute([$name, $phone, $id]);
			}else if($row['phone'] == $phone){
				$stmt = $conn->prepare("UPDATE employees SET name = ?, username = ? WHERE id = ?");
				$stmt->execute([$name, $uname, $id]);
			}else{
				$stmt = $conn->prepare("UPDATE employees SET name = ?, username = ?, phone = ? WHERE id = ?");
				$stmt->execute([$name, $uname, $phone, $id]);
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
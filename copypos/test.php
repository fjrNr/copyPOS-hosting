<?php 
	// try{
	// 	$stmt = $conn->prepare();
	// 	$stmt->execute();
	// }catch(PDOException $e){
	
	// }

	// function fun1($number){
	// 	if($number == 20){
	// 		return $number + 5;
	// 	}else{
	// 		return $number;		
	// 	}
	// }

	// $boolVar = true;
	// $allSell = filter_var($boolVar, FILTER_VALIDATE_BOOLEAN);
	// echo $allSell;

	// $date = '00-00-000';
	// $dueDate = date('Y-m-d',strtotime($date));
	// echo $dueDate;

	// $image_path = $_SERVER['DOCUMENT_ROOT'].'copypos/images/branch/';
	// echo $image_path.'<br><br>';
	// echo 'test<br>April mop<br>';

	// require_once('koneksi.php');
	// $stmt = $conn->prepare("SELECT SUM(amount) AS total FROM payment_receptions WHERE saleId = ?");
	// $stmt->execute([10]);
	// $totalPayment = $stmt->fetch(PDO::FETCH_COLUMN);
	// echo 'Rp. '.$totalPayment;

	// require_once('koneksiMySQL.php');

        //if phone nor email is exist 
    // try{

    // 	for($i = 0; $i < $idArray; $i++){
    //         $stmt = $conn->prepare("INSERT INTO supplier (ownerId, name, image) VALUES (?, ?, ?)");
    //         $stmt->execute([$idArray[$i], 'Pemasok Umum','']);
    // 	}
        
    //     $response['success'] = true;
    //     $response['message'] = 'Registrasi berhasil, selamat datang di Copy POS ';
    // }catch(PDOException $e){
    //     $response['success'] = false;
    //     $response['message'] = 'Registrasi gagal';
    // }    
    
    // $conn = null;


	// $idArray = array();
	// $query = mysqli_query($con,"SELECT * FROM owners");

	// while($row = mysqli_fetch_assoc($query)){
	// 	array_push($idArray, $row['id']);
	// }

	// for($i = 0; $i < count($idArray); $i++){
	// 	$id = $idArray[$i];
 //        $query = "INSERT IGNORE INTO customers (ownerId, name, totalCredit) VALUES ('$id', 'Pemasok Umum', 0)";
 //        if(mysqli_query($con, $query)){

 //        	$response['success'] = true;
	// 	    $response['message'] = 'Registrasi berhasil, selamat datang di Copy POS '; 
 //        }else{
 //        	$response['success'] = false;
	// 	    $response['message'] = 'TIDAK TIDAK'; 
 //        }
	// }
    

 //    echo json_encode($response);
	
	$periode = '2020-09';
	$year = date('Y-m-t',strtotime($periode));
	echo($year);
?>
<?php 
    if($_SERVER['REQUEST_METHOD']=='POST'){
        require_once('koneksi.php');

        //set variable
        $name = $_POST['name'];
        $phone = $_POST['phone'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        //count rows
        $stmt = $conn->prepare("SELECT * FROM owners WHERE email = ? OR phone = ?");
        $stmt->execute([$email, $phone]);
        $rows = $stmt->fetch(PDO::FETCH_NUM);
        
        if($rows == 0){
            //if phone nor email is exist 
            $idOwn = null;
            try{
                $stmt = $conn->prepare("INSERT INTO owners (name, phone, email, password, image) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$name, $phone, $email, $password, '']);
                $idOwn = $conn->lastInsertId();
                
                $response['success'] = true;
                $response['message'] = 'Registrasi berhasil, selamat datang di Copy POS ';
                $response['id'] = $idOwn;
                $response['name'] = $name;
            }catch(PDOException $e){
                echo $e->postMessage();
                $response['success'] = false;
                $response['message'] = 'Registrasi gagal';
            }    
        }else{
            $response['success'] = false;
            $response['message'] = 'Email atau telepon telah terdaftar';
        }
        
        $conn = null;
    }else{
        $response['success'] = false;
        $response['message'] = 'Permintaan Gagal';
    }

    echo json_encode($response);
 ?>
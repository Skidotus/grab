<?php
$servername = '127.0.0.1';
$dbname = 'student_transport';
$dbusername = 'root';
$dbpassword = '';

$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

    function register($username, $email, $password, $phonenumber, $address, $birthdate, $filename) {
        global $connection;
        $sql = "INSERT INTO user_creds (User_Name, User_Email, User_Pass, User_Phone, User_Address, User_Birthdate, User_Picture) VALUES ('$username', '$email', '$password', '$phonenumber', '$address', '$birthdate', '$filename')";
        return $connection->query($sql);
    }

    function registerdriver($drusername, $dremail, $drpassword, $drphonenumber, $draddress, $drbirthdate) {
        global $connection;
        $sql = "INSERT INTO drivers (Driver_Name, Driver_Email, Driver_Pass, Driver_Phone, Driver_Address, Driver_Birthdate) VALUES ('$drusername', '$dremail', '$drpassword', '$drphonenumber', '$draddress', '$drbirthdate')";
        return $connection->query($sql);
    }

function login($email, $password) {
    global $conn;
    $sql = "SELECT * FROM user_creds 
            WHERE User_Email = '$email' AND User_Pass = '$password'";
    $result = $conn->query($sql);
    return $result->fetch_assoc();
}

    function logindriver($dremail, $drpassword) {
        global $connection;
        $sql = "SELECT * FROM drivers WHERE Driver_Email = '$dremail' AND Driver_Pass = '$drpassword'";
        $result = $connection->query($sql);
        return $result->fetch_assoc();
    }

    function selectAllUsers(){
        global $connection;
        $sql = "SELECT * FROM user_creds";
        $result = $connection->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

function deleteUserByID($id){
    global $conn;
    $sql = "DELETE FROM user_creds WHERE User_ID = '$id'";
    $conn->query($sql);
}

function selectUserByID($id){
    global $conn;
    $sql = "SELECT * FROM user_creds WHERE User_ID ='$id'";
    $result = $conn->query($sql);
    return $result->fetch_assoc();
}

    function updateByID($id, $username, $email, $password, $address, $birthdate, $phonenumber){
        global $connection;
        $sql = "UPDATE user_creds SET User_Name = '$username', User_Email = '$email', User_Pass = '$password', User_Address = '$address', User_Birthdate = '$birthdate', User_Phone = '$phonenumber' WHERE User_ID = '$id'";
        return $connection->query($sql);
    }

     function DriverupdateByID($drid, $drusername, $dremail, $drpassword, $draddress, $drbirthdate, $drphonenumber){
        global $connection;
        $sql = "UPDATE drivers SET Driver_Name = '$drusername', Driver_Email = '$dremail', Driver_Pass = '$drpassword', Driver_Address = '$draddress', Driver_Birthdate = '$drbirthdate', Driver_Phone = '$drphonenumber' WHERE Driver_ID = '$drid'";
        return $connection->query($sql);
    }

    function selectUserByEmail($email){
        global $connection;
        $sql = "SELECT * FROM user_creds where User_Email ='$email'";
        $result = $connection->query($sql);
        return $result->fetch_assoc();
    }

    // Select a driver row by email from the drivers table
    function selectDriverByEmail($email){
        global $connection;
        $sql = "SELECT * FROM drivers WHERE Driver_Email = '$email'";
        $result = $connection->query($sql);
        return $result->fetch_assoc();
    }
    

    function uploadImage($picture) {
        $filename = $picture['name']; //extract nama file so calling senang
        $tempname = $picture['tmp_name']; //php simpan temporary dalam temp folder xampp
        $targetFolder = "./image/" . $filename; //destination gambar letak mana

        if (move_uploaded_file($tempname, $targetFolder)) {
            return $filename; 
        } else {
            return null; //
        }
    }

    


    ?>


     

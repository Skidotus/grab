<?php
$servername = '127.0.0.1';
$dbname = 'student_transport';
$dbusername = 'root';
$dbpassword = '';

$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function register($username, $email, $password) {
    global $conn;
    $sql = "INSERT INTO user_creds (User_Name, User_Email, User_Pass) 
            VALUES ('$username', '$email', '$password')";
    return $conn->query($sql);
}

function login($email, $password) {
    global $conn;
    $sql = "SELECT * FROM user_creds 
            WHERE User_Email = '$email' AND User_Pass = '$password'";
    $result = $conn->query($sql);
    return $result->fetch_assoc();
}

function selectAllUsers(){
    global $conn;
    $sql = "SELECT * FROM user_creds";
    $result = $conn->query($sql);
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

function updateByID($id, $username, $email, $password){
    global $conn;
    $sql = "UPDATE user_creds 
            SET User_Name = '$username', User_Email = '$email', User_Pass = '$password' 
            WHERE User_ID = '$id'";
    return $conn->query($sql);
}

function selectUserByEmail($email){
    global $conn;
    $sql = "SELECT * FROM user_creds WHERE User_Email ='$email'";
    $result = $conn->query($sql);
    return $result->fetch_assoc();
}
?>
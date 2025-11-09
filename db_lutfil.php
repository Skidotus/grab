<?php
$servername = '127.0.0.1';
$dbname = 'student_transport';
$dbusername = 'root';
$dbpassword = 'prime1208';
// $dbusername = 'root';
// $dbpassword = '';


$connection = new mysqli($servername, $dbusername, $dbpassword, $dbname);

// Check connection
if ($connection->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// echo "Connected successfully\n";



function register($username, $email, $password)
{
    global $connection;
    $sql = "INSERT INTO user_creds (User_Name, User_Email, User_Pass) VALUES ('$username', '$email', '$password')";
    return $connection->query($sql);
}

function login($email, $password)
{
    global $connection;
    $sql = "SELECT * FROM user_creds WHERE User_Email = '$email' AND User_Pass = '$password'";
    $result = $connection->query($sql);
    return $result->fetch_assoc();
}

function selectAllUsers()
{
    global $connection;
    $sql = "SELECT * FROM user_creds";
    $result = $connection->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}

function deleteUserByID($id)
{
    global $connection;
    $sql = "DELETE FROM user_creds where User_ID = '$id'";
    $connection->query($sql);
}

function selectUserByID($id)
{
    global $connection;
    $sql = "SELECT * FROM user_creds where User_ID ='$id'";
    $result = $connection->query($sql);
    return $result->fetch_assoc();
}

function updateByID($id, $username, $email, $password)
{
    global $connection;
    $sql = "UPDATE user_creds SET User_Name = '$username', User_Email = '$email', User_Pass = '$password' WHERE User_ID = '$id'";
    return $connection->query($sql);
}

function selectUserByEmail($email)
{
    global $connection;
    $sql = "SELECT * FROM user_creds where User_Email ='$email'";
    $result = $connection->query($sql);
    return $result->fetch_assoc();
}

function select_pending_bookings()
{
    global $connection;
    $sql = "SELECT booking_number AS booking_ID,user_ID,ST_AsWKT(current_user_location) AS user_location , ST_AsWKT(user_pickup_location) AS pickup_location, ST_AsWKT(user_dropoff_location) AS dropoff_location ,status FROM booking where status='pending'";
    $result = $connection->query($sql);
    return $result;

}
?>
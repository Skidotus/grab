<?php
    $servername = '127.0.0.1';
    $dbname = 'student_transport';
    $dbusername = 'pma';
    $dbpassword = 'SuperStrongPass123!';

    $connection = new mysqli($servername, $dbusername, $dbpassword, $dbname);


    function register($username, $email, $password) {
        global $connection;
        $sql = "INSERT INTO user_creds (User_Name, User_Email, User_Pass) VALUES ('$username', '$email', '$password')";
        return $connection->query($sql);
    }

    function login($email, $password) {
        global $connection;
        $sql = "SELECT * FROM user_creds WHERE User_Email = '$email' AND User_Pass = '$password'";
        $result = $connection->query($sql);
        return $result->fetch_assoc();
    }

    function selectAllUsers(){
        global $connection;
        $sql = "SELECT * FROM users";
        $result = $connection->query($sql);
        return $result->fetch_all(MYSQL_ASSOC);
    }

    function deleteUserByID($id){
        global $connection;
        $sql = "DELETE FROM users where id = '$id'";
        $connection->query($sql);
    }
?>

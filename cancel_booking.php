<?php
require "db.php";
session_start();
if (!isset($_SESSION["email"])) {
    header("Location: login.php");
}

if ($_POST) {
    $email = $_SESSION["email"];
    $booking_number_stmnt = "SELECT booking_number FROM bookings WHERE email = '$email' ORDER BY booking_time DESC LIMIT 1";
    $result = $conn->query($booking_number_stmnt);    
    $booking_number = $result->fetch_assoc();
    echo $booking_number['booking_number'];

    // $stmt = "DELETE FROM bookings WHERE email = '$email'";
    // return $conn->query($stmt);
}
?>
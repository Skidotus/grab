<?php 
require "db.php";
session_start();
if (!isset($_SESSION["email"])) {
    header("Location: driver_login.php");
    exit;
}
?>
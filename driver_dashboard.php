<?php
require "db.php";
session_start();
if (!isset($_SESSION["email"])) {
    header("Location: driver_login.php");
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
</head>

<body>
    <h1 class=" p-6 m-2 max-w-sm mx-auto text-3xl font-bold text-clifford"">Driver Dashboard is in different branch dev at the moment. Stay tuned</h1>
    <div class="p-6 max-w-sm mx-auto bg-white rounded-xl shadow-md flex items-center space-x-4">Lutfil tengah dev dekat feature/tailwinds/ </div>
    <div class="text-2x"></div>   
</body>
</html>
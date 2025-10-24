<?php
session_start();
require 'db.php';

if (!isset($_SESSION['email'])) {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Access denied. Please submit the payment form properly.");
}
    header("Location: login.php");
    exit;
}

$trip_id = $_POST['trip_id'];
$User_ID = $_POST['User_ID'];
$amount = $_POST['amount'];
$method = $_POST['method'];

// Insert payment record
$stmt = $conn->prepare("
    INSERT INTO payment (trip_id, User_ID, amount, method, status, paid_at)
    VALUES (?, ?, ?, ?, 'Paid', NOW())
");
$stmt->bind_param("iids", $trip_id, $User_ID, $amount, $method);
$stmt->execute();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Successful</title>
    <style>
        body {
            background-color: #000;
            color: #fff;
            font-family: Arial;
            text-align: center;
            margin-top: 100px;
        }
        .box {
            display: inline-block;
            background-color: #1c1c1c;
            padding: 30px;
            border-radius: 10px;
        }
        h2 { color: #00ff88; }
        a {
            display: inline-block;
            color: #000;
            background-color: #f9d342;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            margin-top: 20px;
        }
        a:hover { background-color: #ffe97a; }
    </style>
</head>
<body>

<div class="box">
    <h2>Thank you, your payment was successful!</h2>

    <!-- Show the Trip ID -->
    <p>
        <?php 
            echo "Trip ID: " . $trip_id;
        ?>
    </p>

    <!-- Show the Amount Paid -->
    <p>
        <?php 
            // number_format makes the amount show 2 decimal places like money
            echo "Amount Paid: RM " . number_format($amount, 2);
        ?>
    </p>

    <!-- Show the Payment Method -->
    <p>
        <?php 
            // Replace underscore with space, e.g., "credit_card" becomes "credit card"
            $methodText = str_replace("_", " ", $method);

            // Capitalize first letter of each word
            $methodText = ucwords($methodText);

            // Print it
            echo "Payment Method: " . $methodText;
        ?>
    </p>

    <!-- Link to go back to trip list -->
    <a href="trip_list.php">View Trips</a>
</div>
</body>
</html>
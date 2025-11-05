<?php
require "db.php";
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$email = $_SESSION['email'];
$userData = $conn->query("SELECT User_ID FROM user_creds WHERE User_Email='$email'");

if ($userData->num_rows == 0) {
    echo "You don't exist! Please register or relogin.";
    exit();
}

$user = $userData->fetch_assoc();
$user_id = $user['User_ID'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $trip = $_POST['trip_id'];
    $amount = $_POST['amount'];
    $method = $_POST['method'];

    if (empty($trip) || empty($amount) || empty($method)) {
        echo "Please fill in all fields.";
        exit();
    }

    $checkTrip = $conn->query("
        SELECT t.trip_id 
        FROM Trip t 
        JOIN Booking b ON b.booking_number = t.booking_number 
        WHERE t.trip_id = '$trip' AND b.User_ID = '$user_id'
    ");

    if ($checkTrip->num_rows == 0) {
        echo "Trip not found or not yours.";
        exit();
    }

    $checkPay = $conn->query("SELECT * FROM Payment WHERE trip_id = '$trip'");

    if ($checkPay->num_rows > 0) {
        echo "
        <!DOCTYPE html>
        <html>
        <head>
            <title>Payment</title>
            <style>
                body {
                    background: black;
                    color: white;
                    font-family: Arial;
                    text-align: center;
                    margin-top: 80px;
                }
                .box {
                    background: #1a1a1a;
                    padding: 25px;
                    border-radius: 10px;
                    width: 400px;
                    margin: auto;
                }
                a.button {
                    display: inline-block;
                    width: 100%;
                    padding: 10px;
                    margin-top: 10px;
                    border: none;
                    border-radius: 5px;
                    background: yellow;
                    color: black;
                    text-decoration: none;
                    cursor: pointer;
                }
                a.button:hover { opacity: 0.8; }
            </style>
        </head>
        <body>
            <div class='box'>
                <h2>Payment Already Made</h2>
                <p>You already paid for this trip.</p>
                <a class='button' href='index.php'>Back to Main Menu</a>
            </div>
        </body>
        </html>";
        exit();
    }


    if ($method == "cash" || $method == "qr") {
        $status = "Pending";
    } else {
        $status = "Paid";
    }

    $insert = "
        INSERT INTO Payment (trip_id, User_ID, amount, method, status, paid_at)
        VALUES ('$trip', '$user_id', '$amount', '$method', '$status', NOW())
    ";

    if ($conn->query($insert)) {
        echo "
        <body style='background:black;color:white;text-align:center;margin-top:100px;font-family:Arial'>
        ";

        if ($status == "Paid") {
            echo "<h2 style='color:lightgreen'>Payment Successful</h2>";
        } else {
            echo "<h2 style='color:yellow'>Payment Pending</h2>";
            echo "<p>Please pay the driver using " . strtoupper($method) . ".</p>";
        }

        echo "
        <p>Trip ID: $trip</p>
        <p>Amount: RM " . number_format($amount, 2) . "</p>
        <p>Status: $status</p>
        <a href='index.php' style='background:yellow;color:black;padding:10px 20px;border-radius:5px;text-decoration:none;'>Main Menu</a>
        </body>
        ";
        exit();
    } else {
        echo "Error saving payment. Please check your payment or make the payment again.";
        exit();
    }
}

$queryTrip = "
    SELECT t.trip_id, t.fare_amount, t.distance_km, t.booking_number
    FROM Trip t
    JOIN Booking b ON b.booking_number = t.booking_number
    LEFT JOIN Payment p ON p.trip_id = t.trip_id
    WHERE b.User_ID = '$user_id' 
    AND (p.trip_id IS NULL OR p.status != 'Paid')
    ORDER BY t.created_at DESC 
    LIMIT 1
";

$resultTrip = $conn->query($queryTrip);

if ($resultTrip->num_rows == 0) {
    echo "
    <p style='color:white;text-align:center;margin-top:100px'>
        No unpaid trips.<br>
        <a href='booking.html' style='background:yellow;color:black;padding:10px 20px;border-radius:5px;text-decoration:none;'>Book Now</a>
    </p>
    ";
    exit();
}

$tripData = $resultTrip->fetch_assoc();

?>

<!DOCTYPE html>
<html>
<head>
    <title>Payment</title>
    <style>
        body {
            background: black;
            color: white;
            font-family: Arial;
            text-align: center;
            margin-top: 80px;
        }
        .box {
            background: #1a1a1a;
            padding: 25px;
            border-radius: 10px;
            width: 300px;
            margin: auto;
        }
        input, select, button {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            border: none;
            border-radius: 5px;
        }
        button {
            background: yellow;
            color: black;
            cursor: pointer;
        }
        button:hover {
            opacity: 0.8;
        }
    </style>
</head>
<body>
    <div class="box">
        <h2>Payment Page</h2>
        <p>Trip: <?php echo $tripData['trip_id'] ?></p>
        <p>Booking: <?php echo $tripData['booking_number'] ?></p>
        <p>Distance: <?php echo $tripData['distance_km'] ?> km</p>
        <p>Fare: RM <?php echo $tripData['fare_amount'] ?></p>

        <form method="post">
            <label>Trip ID:</label>
            <input type="hidden" name="trip_id" value="<?php echo $tripData['trip_id'] ?>">

            <label>Amount (RM):</label>
            <input type="hidden" name="amount" value="<?php echo $tripData['fare_amount'] ?>">

            <label>Payment Method:</label>
            <select name="method" required>
                <option value="">Select Method</option>
                <option value="cash">Cash (Make payment with driver)</option>
                <option value="qr">QR (Make payment with driver)</option>
                <option value="ewallet">E-Wallet</option>
                <option value="credit_card">Credit Card</option>
                <option value="debit_card">Debit Card</option>
            </select>

            <button type="submit">Pay</button>
        </form>
    </div>
</body>
</html>

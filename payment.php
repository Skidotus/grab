<?php
require "db.php";
session_start();

// --- Check if user is logged in ---
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// --- Get user info ---
$email = $_SESSION['email'];
$getUser = "SELECT User_ID, User_Name FROM user_creds WHERE User_Email = '$email'";
$userResult = $connection->query($getUser);

if ($userResult->num_rows == 0) {
    echo "User not found!";
    exit();
}

$user = $userResult->fetch_assoc();
$User_ID = $user['User_ID'];

// --- When user submits payment form ---
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $trip_id = $_POST['trip_id'];
    $amount = $_POST['amount'];
    $method = $_POST['method'];

    // --- Check if all form data is filled ---
    if (empty($trip_id) || empty($amount) || empty($method)) {
        echo "Please fill in all fields.";
        exit();
    }

    // --- Make sure this trip belongs to the current user ---
    $checkTrip = "SELECT t.trip_id 
                  FROM Trip t 
                  JOIN Booking b ON b.booking_number = t.booking_number 
                  WHERE t.trip_id = '$trip_id' AND b.User_ID = '$User_ID'";
    $checkResult = $connection->query($checkTrip);

    if ($checkResult->num_rows == 0) {
        echo "Trip not found or not yours!";
        exit();
    }

    // --- Check if payment already made ---
    $checkPayment = "SELECT * FROM Payment WHERE trip_id = '$trip_id'";
    $paymentResult = $connection->query($checkPayment);

    if ($paymentResult->num_rows > 0) {
        echo "You already made a payment for this trip.";
        exit();
    }

    // --- Decide payment status (pending or paid) ---
    $pendingMethods = ['cash', 'qr', 'ewallet'];
    if (in_array($method, $pendingMethods)) {
        $status = 'Pending'; // driver needs to confirm
    } else {
        $status = 'Paid'; // credit/debit are instant
    }

    // --- Insert payment record ---
    $insertPayment = "INSERT INTO Payment (trip_id, User_ID, amount, method, status, paid_at)
                      VALUES ('$trip_id', '$User_ID', '$amount', '$method', '$status', NOW())";

    if ($connection->query($insertPayment) === TRUE) {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>Payment <?php echo $status; ?></title>
            <style>
                body { background-color: black; color: white; text-align: center; font-family: Arial; margin-top: 100px; }
                .box { background-color: #1c1c1c; padding: 30px; border-radius: 10px; display: inline-block; }
                h2 { color: <?php echo $status == 'Paid' ? '#00ff88' : '#f9d342'; ?>; }
                a { background-color: #f9d342; color: black; padding: 10px 20px; border-radius: 5px; text-decoration: none; display: inline-block; margin-top: 20px; }
                a:hover { background-color: #ffe97a; }
            </style>
        </head>
        <body>
        <div class="box">
            <?php if ($status == 'Paid') { ?>
                <h2>Payment Successful!</h2>
                <p>Your online payment was processed successfully.</p>
            <?php } else { ?>
                <h2>⌛ Payment Pending</h2>
                <p>Please pay the driver using <?php echo ucfirst($method); ?> and wait for confirmation.</p>
            <?php } ?>
            <p>Trip ID: <?php echo $trip_id; ?></p>
            <p>Amount: RM <?php echo number_format($amount, 2); ?></p>
            <p>Payment Method: <?php echo ucfirst($method); ?></p>
            <p>Status: <strong><?php echo $status; ?></strong></p>
            <a href="index.php">Back to Dashboard</a>
        </div>
        </body>
        </html>
        <?php
        exit();
    } else {
        echo "Error saving payment: " . $connection->error;
        exit();
    }
}

// --- Show unpaid or latest trip ---
$sqlTrip = "SELECT t.trip_id, t.fare_amount, t.distance_km, t.booking_number
            FROM Trip t
            JOIN Booking b ON b.booking_number = t.booking_number
            LEFT JOIN Payment p ON p.trip_id = t.trip_id
            WHERE b.User_ID = '$User_ID' AND (p.trip_id IS NULL OR p.status != 'Paid')
            ORDER BY t.created_at DESC LIMIT 1";

$resultTrip = $connection->query($sqlTrip);

if ($resultTrip->num_rows == 0) {
    echo "<p style='color:white;text-align:center;margin-top:100px;'>No unpaid trips found. Please make a booking first.</p>";
    echo "<p style='text-align:center;'><a href='booking.html' style='background:#f9d342;color:#000;padding:10px 16px;border-radius:6px;text-decoration:none;'>Book Transport</a></p>";
    exit();
}

$trip = $resultTrip->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Make a Payment</title>
    <style>
        body { background-color: #000; color: #fff; font-family: Arial; text-align: center; margin-top: 80px; }
        .box { background-color: #1c1c1c; padding: 30px; border-radius: 10px; width: 360px; margin: auto; }
        input, select { width: 100%; padding: 10px; margin: 8px 0; border: none; border-radius: 6px; }
        button { background: #f9d342; color: #000; border: none; padding: 10px 16px; border-radius: 6px; cursor: pointer; }
        button:hover { background: #ffe97a; }
    </style>
</head>
<body>

<div class="box">
    <h2>Make a Payment</h2>
    <p>Trip ID: <?php echo $trip['trip_id']; ?></p>
    <p>Booking #: <?php echo $trip['booking_number']; ?></p>
    <p>Distance: <?php echo number_format($trip['distance_km'], 2); ?> km</p>
    <p>Fare: RM <?php echo number_format($trip['fare_amount'], 2); ?></p>

    <form method="POST">
        <input type="hidden" name="trip_id" value="<?php echo $trip['trip_id']; ?>">
        <input type="hidden" name="amount" value="<?php echo $trip['fare_amount']; ?>">

        <label>Payment Method:</label>
        <select name="method" required>
            <option value="">-- Select Method --</option>
            <option value="cash">Cash (Pay Driver)</option>
            <option value="qr">QR Payment</option>
            <option value="ewallet">E-Wallet</option>
            <option value="credit_card">Credit Card</option>
            <option value="debit_card">Debit Card</option>
        </select>

        <button type="submit">Confirm & Pay</button>
    </form>
</div>

</body>
</html>

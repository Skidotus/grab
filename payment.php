<?php
require "db.php";
session_start();

// ========== CHECK LOGIN ==========
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// ========== GET USER INFO ==========
$email = $_SESSION['email'];
$sql = "SELECT User_ID, User_Name FROM user_creds WHERE User_Email = '$email'";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    echo "User not found!";
    exit();
}

$user = $result->fetch_assoc();
$User_ID = $user['User_ID'];

// ========== PROCESS PAYMENT ==========
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $trip_id = $_POST['trip_id'];
    $amount = $_POST['amount'];
    $method = $_POST['method'];

    if (empty($trip_id) || empty($amount) || empty($method)) {
        echo "Please fill in all fields.";
        exit();
    }

    // Check if trip belongs to this user
    $checkTrip = "SELECT t.trip_id
                  FROM Trip t
                  JOIN Booking b ON b.booking_number = t.booking_number
                  WHERE t.trip_id = '$trip_id' AND b.User_ID = '$User_ID' LIMIT 1";
    $checkResult = $conn->query($checkTrip);

    if ($checkResult->num_rows == 0) {
        echo "Trip not found for this user.";
        exit();
    }

    // Insert payment record
    $insertPayment = "INSERT INTO Payment (trip_id, User_ID, amount, method, status, paid_at)
                      VALUES ('$trip_id', '$User_ID', '$amount', '$method', 'Paid', NOW())";

    if ($conn->query($insertPayment) === TRUE) {
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title>Payment Successful</title>
            <style>
                body { background-color: #000; color: #fff; text-align: center; margin-top: 100px; font-family: Arial; }
                .box { display: inline-block; background-color: #1c1c1c; padding: 30px; border-radius: 10px; }
                h2 { color: #00ff88; }
                a { display: inline-block; background-color: #f9d342; color: #000; padding: 10px 20px; border-radius: 5px; text-decoration: none; margin-top: 20px; }
                a:hover { background-color: #ffe97a; }
            </style>
        </head>
        <body>
        <div class="box">
            <h2>✅ Payment Successful!</h2>
            <p>Trip ID: <?php echo $trip_id; ?></p>
            <p>Amount Paid: RM <?php echo number_format($amount, 2); ?></p>
            <p>Payment Method: <?php echo ucfirst(str_replace('_', ' ', $method)); ?></p>
            <a href="index.php">Back to Dashboard</a>
        </div>
        </body>
        </html>
        <?php
        exit();
    } else {
        echo "Error: " . $conn->error;
        exit();
    }
}

// ========== DISPLAY PAYMENT FORM ==========

// Try to find unpaid trip first
$sqlTrip = "SELECT t.trip_id, t.fare_amount, t.distance_km, t.booking_number
            FROM Trip t
            JOIN Booking b ON b.booking_number = t.booking_number
            LEFT JOIN Payment p ON p.trip_id = t.trip_id
            WHERE b.User_ID = '$User_ID' AND (p.trip_id IS NULL OR p.status != 'Paid')
            ORDER BY t.created_at DESC
            LIMIT 1";
$resultTrip = $conn->query($sqlTrip);

// Fallback to latest trip if no unpaid found
if ($resultTrip->num_rows == 0) {
    $sqlTrip = "SELECT t.trip_id, t.fare_amount, t.distance_km, t.booking_number
                FROM Trip t
                JOIN Booking b ON b.booking_number = t.booking_number
                WHERE b.User_ID = '$User_ID'
                ORDER BY t.created_at DESC
                LIMIT 1";
    $resultTrip = $conn->query($sqlTrip);
}

$trip = $resultTrip->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Make a Payment</title>
    <style>
        body { background-color: #000; color: #fff; font-family: Arial; text-align: center; margin-top: 80px; }
        .box { display: inline-block; background-color: #1c1c1c; padding: 30px; border-radius: 10px; width: 360px; }
        input, select { width: 100%; padding: 10px; margin: 8px 0; border: none; border-radius: 6px; }
        button { background: #f9d342; color: #000; border: none; padding: 10px 16px; border-radius: 6px; cursor: pointer; }
        button:hover { background: #ffe97a; }
        .muted { color: #bbb; font-size: 14px; }
    </style>
</head>
<body>
<div class="box">
    <h2>Make a Payment</h2>

    <?php if ($trip): ?>
        <p class="muted">Prefilled with your latest unpaid (or latest) trip.</p>
        <p>Trip ID: <?php echo $trip['trip_id']; ?></p>
        <p>Booking #: <?php echo $trip['booking_number']; ?></p>
        <p>Distance: <?php echo number_format($trip['distance_km'], 2); ?> km</p>
        <p>Fare: RM <?php echo number_format($trip['fare_amount'], 2); ?></p>

        <form method="POST" action="payment.php">
            <input type="hidden" name="trip_id" value="<?php echo $trip['trip_id']; ?>">
            <input type="hidden" name="User_ID" value="<?php echo $User_ID; ?>">
            <input type="hidden" name="amount" value="<?php echo $trip['fare_amount']; ?>">

            <label for="method">Payment Method</label>
            <select id="method" name="method" required>
                <option value="">-- Select --</option>
                <option value="cash">Cash</option>
                <option value="credit_card">Credit Card</option>
                <option value="debit_card">Debit Card</option>
                <option value="ewallet">E-Wallet</option>
                <option value="qr">QR</option>
            </select>

            <button type="submit">Confirm & Pay</button>
        </form>
    <?php else: ?>
        <p>No trips found for your account. Please make a booking first.</p>
        <a href="booking.html" style="background:#f9d342;color:#000;padding:10px 16px;border-radius:6px;text-decoration:none;">Book Transport</a>
    <?php endif; ?>
</div>
</body>
</html>

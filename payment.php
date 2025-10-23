<?php
session_start();
require 'db.php';

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

// Get current user's ID from email
$userEmail = $_SESSION['email'];
$u = $conn->prepare("SELECT User_ID, User_Name FROM user_creds WHERE User_Email = ?");
$u->bind_param("s", $userEmail);
$u->execute();
$ures = $u->get_result();
$user = $ures->fetch_assoc();
if (!$user) { die("User not found."); }
$User_ID = (int)$user['User_ID'];

// POST: perform payment insert
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $trip_id = isset($_POST['trip_id']) ? (int)$_POST['trip_id'] : 0;
    $amount  = isset($_POST['amount']) ? (float)$_POST['amount'] : 0;
    $method  = isset($_POST['method']) ? $_POST['method'] : '';

    if ($trip_id <= 0 || $amount <= 0 || $method === '') {
        die("Missing payment data. Please go back and try again.");
    }

    // (Optional) make sure the trip belongs to this user
    $chk = $conn->prepare("
        SELECT t.trip_id
        FROM Trip t
        JOIN booking b ON b.booking_number = t.booking_number
        WHERE t.trip_id = ? AND b.User_ID = ?
        LIMIT 1
    ");
    $chk->bind_param("ii", $trip_id, $User_ID);
    $chk->execute();
    if ($chk->get_result()->num_rows === 0) {
        die("Trip not found for this user.");
    }

    // Insert payment
    $ins = $conn->prepare("
        INSERT INTO payment (trip_id, User_ID, amount, method, status, paid_at)
        VALUES (?, ?, ?, ?, 'Paid', NOW())
    ");
    $ins->bind_param("iids", $trip_id, $User_ID, $amount, $method);
    $ins->execute();

    // Success screen
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8"><title>Payment Successful</title>
        <style>
            body{background:#000;color:#fff;font-family:Arial;text-align:center;margin-top:100px}
            .box{display:inline-block;background:#1c1c1c;padding:30px;border-radius:10px}
            a{display:inline-block;color:#000;background:#f9d342;padding:10px 20px;border-radius:5px;text-decoration:none;margin-top:20px}
            a:hover{background:#ffe97a}
            h2{color:#00ff88}
        </style>
    </head>
    <body>
        <div class="box">
            <h2>Thank you, your payment was successful!</h2>
            <p><?php echo "Trip ID: ".$trip_id; ?></p>
            <p><?php echo "Amount Paid: RM ".number_format($amount,2); ?></p>
            <p><?php $mt=ucwords(str_replace('_',' ',$method)); echo "Payment Method: ".$mt; ?></p>
            <a href="index.php">Back to Dashboard</a>
        </div>
    </body>
    </html>
    <?php
    exit;
}

// GET: show a simple form prefilled with the user's latest trip
$trip = null;
// Prefer an unpaid/missing-payment trip; otherwise latest trip
$q = $conn->prepare("
    SELECT t.trip_id, t.fare_amount, t.distance_km, t.booking_number
    FROM Trip t
    JOIN booking b ON b.booking_number = t.booking_number
    LEFT JOIN payment p ON p.trip_id = t.trip_id AND p.status = 'Paid'
    WHERE b.User_ID = ? AND p.trip_id IS NULL
    ORDER BY t.created_at DESC
    LIMIT 1
");
$q->bind_param("i", $User_ID);
$q->execute();
$r = $q->get_result();
if ($r->num_rows === 0) {
    // fallback: latest trip of this user
    $q2 = $conn->prepare("
        SELECT t.trip_id, t.fare_amount, t.distance_km, t.booking_number
        FROM Trip t
        JOIN booking b ON b.booking_number = t.booking_number
        WHERE b.User_ID = ?
        ORDER BY t.created_at DESC
        LIMIT 1
    ");
    $q2->bind_param("i", $User_ID);
    $q2->execute();
    $trip = $q2->get_result()->fetch_assoc();
} else {
    $trip = $r->fetch_assoc();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><title>Make a Payment</title>
    <style>
        body{background:#000;color:#fff;font-family:Arial;text-align:center;margin-top:80px}
        .box{display:inline-block;background:#1c1c1c;padding:30px;border-radius:10px;width:360px}
        input,select{width:100%;padding:10px;margin:8px 0;border:none;border-radius:6px}
        button{background:#f9d342;color:#000;border:none;padding:10px 16px;border-radius:6px;cursor:pointer}
        button:hover{background:#ffe97a}
        .muted{color:#bbb;font-size:14px}
    </style>
</head>
<body>
<div class="box">
    <h2>Make a Payment</h2>

    <?php if ($trip): ?>
        <p class="muted">Prefilled with your latest unpaid (or latest) trip.</p>
        <p><?php echo "Trip ID: ".$trip['trip_id']; ?></p>
        <p><?php echo "Booking #: ".$trip['booking_number']; ?></p>
        <p><?php echo "Distance: ".number_format((float)$trip['distance_km'],2)." km"; ?></p>
        <p><?php echo "Fare: RM ".number_format((float)$trip['fare_amount'],2); ?></p>

        <form method="POST" action="payment.php">
            <input type="hidden" name="trip_id" value="<?php echo (int)$trip['trip_id']; ?>">
            <input type="hidden" name="User_ID" value="<?php echo $User_ID; ?>">
            <input type="hidden" name="amount" value="<?php echo (float)$trip['fare_amount']; ?>">

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
        <p>No trips found for your account. Please create a booking first.</p>
        <a href="booking.html" style="display:inline-block;margin-top:10px;background:#f9d342;color:#000;padding:10px 16px;border-radius:6px;text-decoration:none;">Book Transport</a>
    <?php endif; ?>
</div>
</body>
</html>

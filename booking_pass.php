<?php
require "db.php";
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$email = $_SESSION['email'];
$sql = "SELECT User_ID, User_Name FROM user_creds WHERE User_Email = '$email'";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    echo "User not found!";
    exit();
}

$user = $result->fetch_assoc();
$User_ID = $user['User_ID'];

// Retrieve POST data
$pickup_destination = $_POST['pickup-destination'] ?? '';
$pax = $_POST['pax'] ?? '';
$luggage = $_POST['luggage'] ?? '';
$remarks = $_POST['remarks'] ?? '';
$pickup_time = $_POST['pickup-time'] ?? '';
$contact = $_POST['contact'] ?? '';
$payment = $_POST['payment'] ?? '';
$service = $_POST['service'] ?? '';
$distance = $_POST['distance'] ?? ''; // 🟢 Added

if (empty($pickup_destination) || empty($pax) || empty($pickup_time) || empty($contact) || empty($service) || empty($distance)) {
    echo "Please fill all required fields.";
    exit();
}

// Insert into booking table
$sqlBooking = "INSERT INTO booking (User_ID, current_user_location, user_pickup_location, user_dropoff_location, status, created_at)
               VALUES ('$User_ID', ST_GeomFromText('POINT(0 0)'), ST_GeomFromText('POINT(0 0)'), ST_GeomFromText('POINT(0 0)'), 'confirmed', NOW())";

if ($conn->query($sqlBooking) === TRUE) {
    $booking_number = $conn->insert_id;

    // Fare calculation logic
    switch ($service) {
        case "premium": $fare = 35.00; break;
        case "six-seater": $fare = 45.00; break;
        case "executive": $fare = 60.00; break;
        default: $fare = 20.00;
    }

    // Adjust fare based on distance (for testing)
    $fare = $fare + ($distance * 1.5); // Simple multiplier

    // Insert trip record
    $sqlTrip = "INSERT INTO Trip (booking_number, Driver_ID, created_at, fare_amount, distance_km)
                VALUES ('$booking_number', 1, NOW(), '$fare', '$distance')";
    $conn->query($sqlTrip);
    $trip_id = $conn->insert_id;
    ?>

    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <title>Booking Confirmation</title>
        <style>
            body { background-color: #000; color: #fff; text-align: center; font-family: Arial; margin-top: 80px; }
            .box { background-color: #1c1c1c; padding: 30px; border-radius: 10px; display: inline-block; }
            h2 { color: #00ff88; }
            a { background-color: #f9d342; color: #000; padding: 10px 20px; border-radius: 5px; text-decoration: none; margin-top: 20px; display: inline-block; }
            a:hover { background-color: #ffe97a; }
            .timer { color: #bbb; margin-top: 10px; }
        </style>
        <script>
            let seconds = 3;
            function countdown() {
                const timer = document.getElementById("countdown");
                if (seconds > 0) {
                    timer.textContent = seconds + " second(s)";
                    seconds--;
                    setTimeout(countdown, 1000);
                } else {
                    window.location.href = "payment.php?trip_id=<?php echo $trip_id; ?>";
                }
            }
            window.onload = countdown;
        </script>
    </head>
    <body>
        <div class="box">
            <h2>Booking Successful!</h2>
            <p><strong>Name:</strong> <?php echo htmlspecialchars($user['User_Name']); ?></p>
            <p><strong>Trip ID:</strong> <?php echo $trip_id; ?></p>
            <p><strong>Pickup Destination:</strong> <?php echo htmlspecialchars($pickup_destination); ?></p>
            <p><strong>Distance:</strong> <?php echo htmlspecialchars($distance); ?> km</p>
            <p><strong>Number of Pax:</strong> <?php echo htmlspecialchars($pax); ?></p>
            <p><strong>Service Type:</strong> <?php echo ucfirst($service); ?></p>
            <p><strong>Fare:</strong> RM <?php echo number_format($fare, 2); ?></p>
            <p><strong>Payment Method:</strong> <?php echo ucfirst(str_replace('_', ' ', $payment)); ?></p>

            <a href="payment.php?trip_id=<?php echo $trip_id; ?>">Proceed to Payment</a>
            <p class="timer">Redirecting automatically in <span id="countdown">3 second(s)</span>...</p>
        </div>
    </body>
    </html>

    <?php
} else {
    echo "Error inserting booking: " . $conn->error;
}
?>

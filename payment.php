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
        echo "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='utf-8' />
            <meta name='viewport' content='width=device-width, initial-scale=1' />
            <script src='https://cdn.tailwindcss.com'></script>
            <title>Payment</title>
        </head>
        <body class='min-h-screen bg-gradient-to-b from-neutral-900 via-black to-neutral-900 text-neutral-100 flex items-center justify-center p-6'>
            <div class='w-full max-w-md'>
                <div class='rounded-2xl border border-neutral-800 bg-neutral-900/60 shadow-2xl p-8'>
                    <h2 class='text-xl font-semibold mb-2'>Incomplete Form</h2>
                    <p class='text-neutral-300 mb-6'>Please fill in all fields.</p>
                    <a href='javascript:history.back()' class='inline-flex items-center justify-center rounded-xl bg-yellow-300 text-black px-4 py-2 font-medium hover:opacity-90 transition'>Go Back</a>
                </div>
            </div>
        </body>
        </html>";
        exit();
    }

    $checkTrip = $conn->query("
        SELECT t.trip_id 
        FROM Trip t 
        JOIN Booking b ON b.booking_number = t.booking_number 
        WHERE t.trip_id = '$trip' AND b.User_ID = '$user_id'
    ");

    if ($checkTrip->num_rows == 0) {
        echo "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='utf-8' />
            <meta name='viewport' content='width=device-width, initial-scale=1' />
            <script src='https://cdn.tailwindcss.com'></script>
            <title>Payment</title>
        </head>
        <body class='min-h-screen bg-gradient-to-b from-neutral-900 via-black to-neutral-900 text-neutral-100 flex items-center justify-center p-6'>
            <div class='w-full max-w-md'>
                <div class='rounded-2xl border border-neutral-800 bg-neutral-900/60 shadow-2xl p-8'>
                    <h2 class='text-xl font-semibold mb-2'>Trip Not Found</h2>
                    <p class='text-neutral-300 mb-6'>Trip does not exist or does not belong to your account.</p>
                    <a href='index.php' class='inline-flex items-center justify-center rounded-xl bg-yellow-300 text-black px-4 py-2 font-medium hover:opacity-90 transition'>Main Menu</a>
                </div>
            </div>
        </body>
        </html>";
        exit();
    }

    $checkPay = $conn->query("SELECT * FROM Payment WHERE trip_id = '$trip'");

    if ($checkPay->num_rows > 0) {
        echo "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='utf-8' />
            <meta name='viewport' content='width=device-width, initial-scale=1' />
            <script src='https://cdn.tailwindcss.com'></script>
            <title>Payment</title>
        </head>
        <body class='min-h-screen bg-gradient-to-b from-neutral-900 via-black to-neutral-900 text-neutral-100 flex items-center justify-center p-6'>
            <div class='w-full max-w-md'>
                <div class='rounded-2xl border border-neutral-800 bg-neutral-900/60 shadow-2xl p-8'>
                    <div class='mb-4 inline-flex items-center gap-2 text-emerald-300'>
                        <svg xmlns=\"http://www.w3.org/2000/svg\" class=\"h-5 w-5\" viewBox=\"0 0 20 20\" fill=\"currentColor\"><path fill-rule=\"evenodd\" d=\"M16.707 5.293a1 1 0 010 1.414l-7.071 7.071a1 1 0 01-1.414 0L3.293 9.85a1 1 0 111.414-1.414l4.1 4.1 6.364-6.364a1 1 0 011.536.121z\" clip-rule=\"evenodd\"/></svg>
                        <span class='font-semibold'>Payment Already Made</span>
                    </div>
                    <p class='text-neutral-300 mb-6'>You have already paid for this trip.</p>
                    <a href='index.php' class='inline-flex w-full items-center justify-center rounded-xl bg-yellow-300 text-black px-4 py-2 font-medium hover:opacity-90 transition'>Back to Main Menu</a>
                </div>
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
        $trip_safe   = htmlspecialchars($trip);
        $amount_safe = number_format((float)$amount, 2);
        $status_safe = htmlspecialchars($status);
        $method_safe = htmlspecialchars(strtoupper($method));

        echo "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='utf-8' />
            <meta name='viewport' content='width=device-width, initial-scale=1' />
            <script src='https://cdn.tailwindcss.com'></script>
            <title>Payment</title>
        </head>
        <body class='min-h-screen bg-gradient-to-b from-neutral-900 via-black to-neutral-900 text-neutral-100 flex items-center justify-center p-6'>
            <div class='w-full max-w-lg'>
                <div class='rounded-2xl border border-neutral-800 bg-neutral-900/60 shadow-2xl p-8'>
                    <div class='mb-6'>
                        " . ($status == "Paid"
                            ? "<div class='inline-flex items-center gap-2 text-emerald-300'><svg xmlns=\"http://www.w3.org/2000/svg\" class=\"h-5 w-5\" viewBox=\"0 0 20 20\" fill=\"currentColor\"><path fill-rule=\"evenodd\" d=\"M16.707 5.293a1 1 0 010 1.414l-7.071 7.071a1 1 0 01-1.414 0L3.293 9.85a1 1 0 111.414-1.414l4.1 4.1 6.364-6.364a1 1 0 011.536.121z\" clip-rule=\"evenodd\"/></svg><h2 class='text-xl font-semibold'>Payment Successful</h2></div>"
                            : "<div class='inline-flex items-center gap-2 text-yellow-300'><svg xmlns=\"http://www.w3.org/2000/svg\" class=\"h-5 w-5\" viewBox=\"0 0 20 20\" fill=\"currentColor\"><path fill-rule=\"evenodd\" d=\"M8.257 3.099c.765-1.36 2.721-1.36 3.486 0l6.518 11.593c.75 1.336-.213 2.998-1.743 2.998H3.482c-1.53 0-2.493-1.662-1.743-2.998L8.257 3.1zM11 14a1 1 0 10-2 0 1 1 0 002 0zm-1-2a1 1 0 01-1-1V7a1 1 0 112 0v4a1 1 0 01-1 1z\" clip-rule=\"evenodd\"/></svg><h2 class='text-xl font-semibold'>Payment Pending</h2></div>")
                        . "
                        " . ($status == "Pending" ? "<p class='mt-2 text-neutral-300'>Please pay the driver using <span class='font-semibold text-neutral-100'>$method_safe</span>.</p>" : "") . "
                    </div>

                    <div class='grid grid-cols-2 gap-4 text-sm'>
                        <div class='rounded-xl border border-neutral-800 bg-black/30 p-4'>
                            <p class='text-neutral-400'>Trip ID</p>
                            <p class='font-semibold mt-1'>$trip_safe</p>
                        </div>
                        <div class='rounded-xl border border-neutral-800 bg-black/30 p-4'>
                            <p class='text-neutral-400'>Amount</p>
                            <p class='font-semibold mt-1'>RM $amount_safe</p>
                        </div>
                        <div class='rounded-xl border border-neutral-800 bg-black/30 p-4'>
                            <p class='text-neutral-400'>Status</p>
                            <p class='font-semibold mt-1'>$status_safe</p>
                        </div>
                        <div class='rounded-xl border border-neutral-800 bg-black/30 p-4'>
                            <p class='text-neutral-400'>Method</p>
                            <p class='font-semibold mt-1'>$method_safe</p>
                        </div>
                    </div>

                    <div class='mt-8'>
                        <a href='index.php' class='inline-flex w-full items-center justify-center rounded-xl bg-yellow-300 text-black px-4 py-2 font-medium hover:opacity-90 transition'>Main Menu</a>
                    </div>
                </div>
            </div>
        </body>
        </html>";
        exit();
    } else {
        echo "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='utf-8' />
            <meta name='viewport' content='width=device-width, initial-scale=1' />
            <script src='https://cdn.tailwindcss.com'></script>
            <title>Payment</title>
        </head>
        <body class='min-h-screen bg-gradient-to-b from-neutral-900 via-black to-neutral-900 text-neutral-100 flex items-center justify-center p-6'>
            <div class='w-full max-w-md'>
                <div class='rounded-2xl border border-neutral-800 bg-neutral-900/60 shadow-2xl p-8'>
                    <h2 class='text-xl font-semibold mb-2'>Error</h2>
                    <p class='text-neutral-300 mb-6'>Error saving payment. Please check your payment or try again.</p>
                    <a href='javascript:history.back()' class='inline-flex items-center justify-center rounded-xl bg-yellow-300 text-black px-4 py-2 font-medium hover:opacity-90 transition'>Go Back</a>
                </div>
            </div>
        </body>
        </html>";
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
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset='utf-8' />
        <meta name='viewport' content='width=device-width, initial-scale=1' />
        <script src='https://cdn.tailwindcss.com'></script>
        <title>Payment</title>
    </head>
    <body class='min-h-screen bg-gradient-to-b from-neutral-900 via-black to-neutral-900 text-neutral-100 flex items-center justify-center p-6'>
        <div class='w-full max-w-md'>
            <div class='rounded-2xl border border-neutral-800 bg-neutral-900/60 shadow-2xl p-8 text-center'>
                <h2 class='text-xl font-semibold mb-2'>No Unpaid Trips</h2>
                <p class='text-neutral-300 mb-6'>You don't have any unpaid trips right now.</p>
                <a href='booking.html' class='inline-flex w-full items-center justify-center rounded-xl bg-yellow-300 text-black px-4 py-2 font-medium hover:opacity-90 transition'>Book Now</a>
            </div>
        </div>
    </body>
    </html>";
    exit();
}

$tripData = $resultTrip->fetch_assoc();
$tripId        = htmlspecialchars($tripData['trip_id']);
$bookingNumber = htmlspecialchars($tripData['booking_number']);
$distanceKm    = htmlspecialchars($tripData['distance_km']);
$fareAmount    = number_format((float)$tripData['fare_amount'], 2);

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name='viewport' content='width=device-width, initial-scale=1' />
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Payment</title>
</head>
<body class="min-h-screen bg-gradient-to-b from-neutral-900 via-black to-neutral-900 text-neutral-100 flex items-center justify-center p-6">
    <div class="w-full max-w-2xl">
        <div class="rounded-2xl border border-neutral-800 bg-neutral-900/60 shadow-2xl p-6 md:p-8">
            <div class="mb-6">
                <h1 class="text-2xl md:text-3xl font-bold tracking-tight">Payment</h1>
                <p class="text-neutral-400 mt-1">Review your trip details and choose a payment method.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="md:col-span-2 rounded-xl border border-neutral-800 bg-black/30 p-4">
                    <p class="text-neutral-400 text-sm">Trip ID</p>
                    <p class="font-semibold mt-1"><?php echo $tripId; ?></p>
                </div>
                <div class="md:col-span-2 rounded-xl border border-neutral-800 bg-black/30 p-4">
                    <p class="text-neutral-400 text-sm">Booking No.</p>
                    <p class="font-semibold mt-1"><?php echo $bookingNumber; ?></p>
                </div>
                <div class="rounded-xl border border-neutral-800 bg-black/30 p-4">
                    <p class="text-neutral-400 text-sm">Distance</p>
                    <p class="font-semibold mt-1"><?php echo $distanceKm; ?> km</p>
                </div>
                <div class="rounded-xl border border-neutral-800 bg-black/30 p-4">
                    <p class="text-neutral-400 text-sm">Fare</p>
                    <p class="font-semibold mt-1">RM <?php echo $fareAmount; ?></p>
                </div>
            </div>

            <form method="post" class="space-y-5`">
                <!-- Hidden inputs keep your original behavior -->
                <input type="hidden" name="trip_id" value="<?php echo $tripId; ?>">
                <input type="hidden" name="amount" value="<?php echo $tripData['fare_amount']; ?>">

                <div>
                    <label class="block text-sm font-medium mb-2">Payment Method</label>
                    <select name="method" required class="w-full rounded-xl border border-neutral-800 bg-black/40 px-4 py-3 outline-none focus:ring-2 focus:ring-yellow-300/60">
                        <option value="">Select Method</option>
                        <option value="cash">Cash (Pay driver)</option>
                        <option value="qr">QR (Pay driver)</option>
                        <option value="ewallet">E-Wallet</option>
                        <option value="credit_card">Credit Card</option>
                        <option value="debit_card">Debit Card</option>
                    </select>
                    <p class="text-xs text-neutral-400 mt-2">Cash/QR will be marked as <span class="font-semibold text-neutral-200">Pending</span> until the driver confirms.</p>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <a href="index.php" class="inline-flex items-center justify-center rounded-xl border border-neutral-800 px-4 py-2 hover:bg-white/5 transition">Cancel</a>
                    <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-yellow-300 text-black px-5 py-2.5 font-semibold hover:opacity-90 transition">
                        Pay Now
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>

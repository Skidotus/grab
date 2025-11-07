<?php
require "db.php";
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$email = $_SESSION['email'];
$user  = UserEmail($email);

if (!$user) {
    echo "You don't exist! Please register or relogin.";
    exit();
}

$user_id  = $user['User_ID'];

//latest unpaid trip
$tripData = LatestUnpaidTrip($user_id);

if (!$tripData) {
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
                <a href='booking.html' class='inline-flex w-full items-center justify-center rounded-2xl bg-yellow-300 text-black px-4 py-2 font-medium hover:opacity-90 transition'>
                    Book Now
                </a>
            </div>
        </div>
    </body>
    </html>";
    exit();
}

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
                <p class="text-neutral-400 mt-1">Review your booking details and choose a payment method.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="md:col-span-2 rounded-2xl border border-neutral-800 bg-black/30 p-4">
                    <p class="text-neutral-400 text-sm">Trip ID</p>
                    <p class="font-semibold mt-1"><?php echo $tripId; ?></p>
                </div>
                <div class="md:col-span-2 rounded-2xl border border-neutral-800 bg-black/30 p-4">
                    <p class="text-neutral-400 text-sm">Booking No.</p>
                    <p class="font-semibold mt-1"><?php echo $bookingNumber; ?></p>
                </div>
                <div class="rounded-2xl border border-neutral-800 bg-black/30 p-4">
                    <p class="text-neutral-400 text-sm">Distance</p>
                    <p class="font-semibold mt-1"><?php echo $distanceKm; ?> km</p>
                </div>
                <div class="rounded-2xl border border-neutral-800 bg-black/30 p-4">
                    <p class="text-neutral-400 text-sm">Fare</p>
                    <p class="font-semibold mt-1">RM <?php echo $fareAmount; ?></p>
                </div>
            </div>

            <form method="post" action="payment_process.php" class="space-y-5">
                <input type="hidden" name="trip_id" value="<?php echo $tripData['trip_id']; ?>">
                <input type="hidden" name="amount" value="<?php echo $tripData['fare_amount']; ?>">

                <div>
                    <label class="block text-sm font-medium mb-2">Payment Method</label>
                    <select name="method" required class="w-full rounded-2xl border border-neutral-800 bg-black/40 px-4 py-3 outline-none focus:ring-2 focus:ring-yellow-300/60">
                        <option value="">Select Method</option>
                        <option value="cash">Cash (Pay driver)</option>
                        <option value="qr">QR (Pay driver)</option>
                        <option value="ewallet">E-Wallet (Pay driver)</option>
                        <option value="credit_card">Credit Card</option>
                        <option value="debit_card">Debit Card</option>
                    </select>
                    <p class="text-xs text-neutral-400 mt-2">
                        Cash/QR/E-Wallet will be marked as
                        <span class="font-semibold text-neutral-200">Pending</span> until the driver confirms.
                        You can later return here and choose Credit/Debit Card to complete a pending payment.
                    </p>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <a href="index.php" class="inline-flex items-center justify-center rounded-2xl border border-neutral-800 px-4 py-2 hover:bg-white/5 transition">
                        Cancel
                    </a>
                    <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-yellow-300 text-black px-5 py-2.5 font-semibold hover:opacity-90 transition">
                        Pay Now
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>

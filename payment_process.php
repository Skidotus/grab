<?php
require "db.php";
require "payment_views.php";
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

$user_id = $user['User_ID'];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: payment.php");
    exit();
}

$trip   = $_POST['trip_id'] ?? '';
$amount = $_POST['amount'] ?? '';
$method = $_POST['method'] ?? '';

if (empty($trip) || empty($amount) || empty($method)) {
    // Incomplete form
    viewIncompleteForm();
}

// Ensure trip belongs to the current user
if (!thisUserTrip($trip, $user_id)) {
    viewTripNotFound();
}

$method_upper    = strtoupper($method);
$existingPayment = getPaymentByTrip($trip);

// Prevent duplicate payment (Paid already page)
if ($existingPayment && $existingPayment['status'] === 'Paid') {
    viewPaymentAlreadyMade();
}

// If there is pending payment and user switches to credit/debit card -> mark as paid
if ($existingPayment &&
    $existingPayment['status'] === 'Pending' &&
    in_array($method_upper, ['CREDIT_CARD', 'DEBIT_CARD'])
) {
    if (updatePendingPaymentToPaid($trip, $method_upper)) {
        viewPendingConvertedToPaid($trip, $existingPayment['amount'], $method_upper);
    }
    viewGenericError();
}

// If still pending and user chooses another pending method -> keep pending
if ($existingPayment &&
    $existingPayment['status'] === 'Pending' &&
    in_array($method_upper, ['CASH', 'QR', 'EWALLET', 'E-WALLET'])
) {
    viewStillPending(
        $trip,
        $existingPayment['amount'],
        $existingPayment['status'],
        $existingPayment['method']
    );
}

// No existing payment -> create new (insertPayment also enforces pending for cash/qr/ewallet)
if (in_array($method_upper, ['CASH', 'QR', 'EWALLET', 'E-WALLET'])) {
    $status = 'Pending';
} else {
    $status = 'Paid';
}

if (insertPayment($trip, $user_id, $amount, $method, $status)) {
    viewNewPaymentResult($trip, $amount, $status, $method_upper);
}

viewGenericError();

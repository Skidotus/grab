<?php

function viewIncompleteForm()
{
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
                <h2 class='text-xl font-semibold mb-2'>Incomplete Form</h2>
                <p class='text-neutral-300 mb-6'>Please fill in all fields.</p>
                <a href='payment.php' class='inline-flex items-center justify-center rounded-2xl bg-yellow-300 text-black px-4 py-2 font-medium hover:opacity-90 transition'>
                    Go Back
                </a>
            </div>
        </div>
    </body>
    </html>";
    exit();
}

function viewTripNotFound()
{
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
                <h2 class='text-xl font-semibold mb-2'>Trip Not Found</h2>
                <p class='text-neutral-300 mb-6'>Trip does not exist or does not belong to your account.</p>
                <a href='index.php' class='inline-flex items-center justify-center rounded-2xl bg-yellow-300 text-black px-4 py-2 font-medium hover:opacity-90 transition'>
                    Main Menu
                </a>
            </div>
        </div>
    </body>
    </html>";
    exit();
}

function viewPaymentAlreadyMade()
{
    // Prevent duplicate payment (Paid already page)
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
                    <svg xmlns=\"http://www.w3.org/2000/svg\" class=\"h-5 w-5\" viewBox=\"0 0 20 20\" fill=\"currentColor\">
                        <path fill-rule=\"evenodd\" d=\"M16.707 5.293a1 1 0 010 1.414l-7.071 7.071a1 1 0 01-1.414 0L3.293 9.85a1 1 0 111.414-1.414l4.1 4.1 6.364-6.364a1 1 0 011.536.121z\" clip-rule=\"evenodd\"/>
                    </svg>
                    <span class='font-semibold'>Payment Already Made :)</span>
                </div>
                <p class='text-neutral-300 mb-6'>You have already paid for this trip :></p>
                <a href='index.php' class='inline-flex w-full items-center justify-center rounded-2xl bg-yellow-300 text-black px-4 py-2 font-medium hover:opacity-90 transition'>
                    Back to Main Menu
                </a>
            </div>
        </div>
    </body>
    </html>";
    exit();
}

function viewPendingConvertedToPaid($trip, $amount, $method_upper)
{
    $trip_safe   = htmlspecialchars($trip);
    $amount_safe = number_format((float)$amount, 2);
    $method_safe = htmlspecialchars($method_upper);

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
                    <div class='inline-flex items-center gap-2 text-emerald-300'>
                        <svg xmlns=\"http://www.w3.org/2000/svg\" class=\"h-5 w-5\" viewBox=\"0 0 20 20\" fill=\"currentColor\">
                            <path fill-rule=\"evenodd\" d=\"M16.707 5.293a1 1 0 010 1.414l-7.071 7.071a1 1 0 01-1.414 0L3.293 9.85a1 1 0 111.414-1.414l4.1 4.1 6.364-6.364a1 1 0 011.536.121z\" clip-rule=\"evenodd\"/>
                        </svg>
                        <h2 class='text-xl font-semibold'>Payment Successful :></h2>
                    </div>
                    <p class='mt-2 text-neutral-300'>
                        Your pending payment has been completed using
                        <span class='font-semibold text-neutral-100'>{$method_safe}</span>.
                    </p>
                </div>

                <div class='grid grid-cols-2 gap-4 text-sm'>
                    <div class='rounded-2xl border border-neutral-800 bg-black/30 p-4'>
                        <p class='text-neutral-400'>Trip ID</p>
                        <p class='font-semibold mt-1'>{$trip_safe}</p>
                    </div>
                    <div class='rounded-2xl border border-neutral-800 bg-black/30 p-4'>
                        <p class='text-neutral-400'>Amount</p>
                        <p class='font-semibold mt-1'>RM {$amount_safe}</p>
                    </div>
                    <div class='rounded-2xl border border-neutral-800 bg-black/30 p-4'>
                        <p class='text-neutral-400'>Status</p>
                        <p class='font-semibold mt-1'>Paid</p>
                    </div>
                    <div class='rounded-2xl border border-neutral-800 bg-black/30 p-4'>
                        <p class='text-neutral-400'>Method</p>
                        <p class='font-semibold mt-1'>{$method_safe}</p>
                    </div>
                </div>

                <div class='mt-8'>
                    <a href='index.php' class='inline-flex w-full items-center justify-center rounded-2xl bg-yellow-300 text-black px-4 py-2 font-medium hover:opacity-90 transition'>
                        Main Menu
                    </a>
                </div>
            </div>
        </div>
    </body>
    </html>";
    exit();
}

function viewStillPending($trip, $amount, $status, $method)
{
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
                    <div class='inline-flex items-center gap-2 text-yellow-300'>
                        <svg xmlns=\"http://www.w3.org/2000/svg\" class=\"h-5 w-5\" viewBox=\"0 0 20 20\" fill=\"currentColor\">
                            <path fill-rule=\"evenodd\" d=\"M8.257 3.099c.765-1.36 2.721-1.36 3.486 0l6.518 11.593c.75 1.336-.213 2.998-1.743 2.998H3.482c-1.53 0-2.493-1.662-1.743-2.998L8.257 3.1zM11 14a1 1 0 10-2 0 1 1 0 002 0zm-1-2a1 1 0 01-1-1V7a1 1 0 011-1z\" clip-rule=\"evenodd\"/>
                        </svg>
                        <h2 class='text-xl font-semibold'>Payment Pending TwT</h2>
                    </div>
                    <p class='mt-2 text-neutral-300'>
                        Your payment for this trip is still pending. Please pay the driver using
                        <span class='font-semibold text-neutral-100'>{$method_safe}</span>.
                    </p>
                </div>

                <div class='grid grid-cols-2 gap-4 text-sm'>
                    <div class='rounded-2xl border border-neutral-800 bg-black/30 p-4'>
                        <p class='text-neutral-400'>Trip ID</p>
                        <p class='font-semibold mt-1'>{$trip_safe}</p>
                    </div>
                    <div class='rounded-2xl border border-neutral-800 bg-black/30 p-4'>
                        <p class='text-neutral-400'>Amount</p>
                        <p class='font-semibold mt-1'>RM {$amount_safe}</p>
                    </div>
                    <div class='rounded-2xl border border-neutral-800 bg-black/30 p-4'>
                        <p class='text-neutral-400'>Status</p>
                        <p class='font-semibold mt-1'>{$status_safe}</p>
                    </div>
                    <div class='rounded-2xl border border-neutral-800 bg-black/30 p-4'>
                        <p class='text-neutral-400'>Method</p>
                        <p class='font-semibold mt-1'>{$method_safe}</p>
                    </div>
                </div>

                <div class='mt-8'>
                    <a href='index.php' class='inline-flex w-full items-center justify-center rounded-2xl bg-yellow-300 text-black px-4 py-2 font-medium hover:opacity-90 transition'>
                        Main Menu
                    </a>
                </div>
            </div>
        </div>
    </body>
    </html>";
    exit();
}

function viewNewPaymentResult($trip, $amount, $status, $method_upper)
{
    $trip_safe   = htmlspecialchars($trip);
    $amount_safe = number_format((float)$amount, 2);
    $status_safe = htmlspecialchars($status);
    $method_safe = htmlspecialchars($method_upper);

    $isPaid = ($status === 'Paid');

    // Payment method logic (force pending for CASH, QR, E-WALLET)
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
                <div class='mb-6'>";
    if ($isPaid) {
        echo "
                    <div class='inline-flex items-center gap-2 text-emerald-300'>
                        <svg xmlns=\"http://www.w3.org/2000/svg\" class=\"h-5 w-5\" viewBox=\"0 0 20 20\" fill=\"currentColor\">
                            <path fill-rule=\"evenodd\" d=\"M16.707 5.293a1 1 0 010 1.414l-7.071 7.071a1 1 0 01-1.414 0L3.293 9.85a1 1 0 111.414-1.414l4.1 4.1 6.364-6.364a1 1 0 011.536.121z\" clip-rule=\"evenodd\"/>
                        </svg>
                        <h2 class='text-xl font-semibold'>Payment Successful :></h2>
                    </div>";
    } else {
        echo "
                    <div class='inline-flex items-center gap-2 text-yellow-300'>
                        <svg xmlns=\"http://www.w3.org/2000/svg\" class=\"h-5 w-5\" viewBox=\"0 0 20 20\" fill=\"currentColor\">
                            <path fill-rule=\"evenodd\" d=\"M8.257 3.099c.765-1.36 2.721-1.36 3.486 0l6.518 11.593c.75-1.336-.213-2.998-1.743-2.998H3.482c-1.53 0-2.493-1.662-1.743-2.998L8.257 3.1zM11 14a1 1 0 10-2 0 1 1 0 002 0zm-1-2a1 1 0 01-1-1V7a1 1 0 011-1z\" clip-rule=\"evenodd\"/>
                        </svg>
                        <h2 class='text-xl font-semibold'>Payment Pending TwT</h2>
                    </div>
                    <p class='mt-2 text-neutral-300'>
                        Please pay the driver using <span class='font-semibold text-neutral-100'>{$method_safe}</span>.
                    </p>";
    }
    echo "
                </div>

                <div class='grid grid-cols-2 gap-4 text-sm'>
                    <div class='rounded-2xl border border-neutral-800 bg-black/30 p-4'>
                        <p class='text-neutral-400'>Trip ID</p>
                        <p class='font-semibold mt-1'>{$trip_safe}</p>
                    </div>
                    <div class='rounded-2xl border border-neutral-800 bg-black/30 p-4'>
                        <p class='text-neutral-400'>Amount</p>
                        <p class='font-semibold mt-1'>RM {$amount_safe}</p>
                    </div>
                    <div class='rounded-2xl border border-neutral-800 bg-black/30 p-4'>
                        <p class='text-neutral-400'>Status</p>
                        <p class='font-semibold mt-1'>{$status_safe}</p>
                    </div>
                    <div class='rounded-2xl border border-neutral-800 bg-black/30 p-4'>
                        <p class='text-neutral-400'>Method</p>
                        <p class='font-semibold mt-1'>{$method_safe}</p>
                    </div>
                </div>

                <div class='mt-8'>
                    <a href='index.php' class='inline-flex w-full items-center justify-center rounded-2xl bg-yellow-300 text-black px-4 py-2 font-medium hover:opacity-90 transition'>
                        Main Menu
                    </a>
                </div>
            </div>
        </div>
    </body>
    </html>";
    exit();
}

function viewGenericError()
{
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
                <p class='text-neutral-300 mb-6'>Error processing payment. Please try again.</p>
                <a href='payment.php' class='inline-flex items-center justify-center rounded-2xl bg-yellow-300 text-black px-4 py-2 font-medium hover:opacity-90 transition'>
                    Go Back
                </a>
            </div>
        </div>
    </body>
    </html>";
    exit();
}

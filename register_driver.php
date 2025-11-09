<?php
require 'db.php';

if ($_POST) {
    $drusername = $_POST['drusername'];
    $dremail = $_POST['dremail'];
    $drpassword = $_POST['drpassword'];
    $drphonenumber = $_POST['drphonenumber'];
    $draddress = $_POST['draddress'];
    $drbirthdate = $_POST['drbirthdate'];

    $picture = $_FILES['uploadimage'];
    $filename = uploadImage($picture);

    if (registerdriver($drusername, $dremail, $drpassword, $drphonenumber, $draddress, $drbirthdate,$filename)) {
        header("Location: driver_login.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Driver Registration | Student Transport</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen bg-gradient-to-b from-slate-900 via-black to-slate-900 text-neutral-100 flex items-center justify-center p-6">

    <div class="w-full max-w-md">
        <div class="rounded-2xl border border-slate-800 bg-slate-900/60 shadow-2xl p-8">
            
            <div class="flex flex-col items-center mb-6">
                <div class="bg-blue-600/20 text-blue-400 p-3 rounded-full mb-3">
                    <!-- Car Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-8 h-8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15a2.25 2.25 0 002.25 2.25h.75v.75A2.25 2.25 0 007.5 20.25h9a2.25 2.25 0 002.25-2.25v-.75h.75a2.25 2.25 0 002.25-2.25v-2.25a2.25 2.25 0 00-.659-1.591l-2.25-2.25a2.25 2.25 0 00-1.591-.659H6.5a2.25 2.25 0 00-1.591.659l-2.25 2.25A2.25 2.25 0 002.25 12.75V15z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15.75h7.5" />
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-blue-400 text-center">Driver Registration</h1>
            </div>

            <form method="POST" enctype="multipart/form-data" class="space-y-5">

                <div>
                    <label for="drusername" class="block text-sm font-medium mb-2">Full Name</label>
                    <input type="text" id="drusername" name="drusername" required
                        class="w-full rounded-xl border border-slate-700 bg-black/40 px-4 py-3 outline-none focus:ring-2 focus:ring-blue-400/60">
                </div>

                <div>
                    <label for="dremail" class="block text-sm font-medium mb-2">Email</label>
                    <input type="email" id="dremail" name="dremail" required
                        class="w-full rounded-xl border border-slate-700 bg-black/40 px-4 py-3 outline-none focus:ring-2 focus:ring-blue-400/60">
                </div>

                <div>
                    <label for="drpassword" class="block text-sm font-medium mb-2">Password</label>
                    <input type="password" id="drpassword" name="drpassword" required
                        class="w-full rounded-xl border border-slate-700 bg-black/40 px-4 py-3 outline-none focus:ring-2 focus:ring-blue-400/60">
                </div>

                <div>
                    <label for="drphonenumber" class="block text-sm font-medium mb-2">Phone Number</label>
                    <input type="text" id="drphonenumber" name="drphonenumber" required
                        class="w-full rounded-xl border border-slate-700 bg-black/40 px-4 py-3 outline-none focus:ring-2 focus:ring-blue-400/60">
                </div>

                <div>
                    <label for="drbirthdate" class="block text-sm font-medium mb-2">Birth Date</label>
                    <input type="date" id="drbirthdate" name="drbirthdate" required
                        class="w-full rounded-xl border border-slate-700 bg-black/40 px-4 py-3 text-neutral-300 outline-none focus:ring-2 focus:ring-blue-400/60">
                </div>

                <div>
                    <label for="draddress" class="block text-sm font-medium mb-2">Address (optional)</label>
                    <input type="text" id="draddress" name="draddress"
                        class="w-full rounded-xl border border-slate-700 bg-black/40 px-4 py-3 outline-none focus:ring-2 focus:ring-blue-400/60">
                </div>

                <div>
                    <label for="uploadimage" class="block text-sm font-medium mb-2">Profile Picture (optional)</label>
                    <input type="file" id="uploadimage" name="uploadimage"
                        class="w-full text-sm text-neutral-300 border border-neutral-700 rounded-xl cursor-pointer bg-black/40 focus:ring-2 focus:ring-yellow-300/60">
                </div>

                <button type="submit"
                    class="w-full inline-flex items-center justify-center rounded-2xl bg-blue-500 text-black px-5 py-3 font-semibold hover:opacity-90 transition">
                    Register as Driver
                </button>
            </form>

            <div class="text-center text-sm text-neutral-400 mt-6">
                <p>Already registered? <a href="driver_login.php" class="text-blue-400 hover:underline">Login here</a></p>
            </div>
        </div>
    </div>

</body>
</html>

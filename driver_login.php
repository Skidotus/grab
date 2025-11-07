<?php
require "db.php";
session_start();

if ($_POST) {
    $dremail = $_POST['dremail'];
    $drpassword = $_POST['drpassword'];
    if (logindriver($dremail, $drpassword)) {
        $_SESSION['dremail'] = $dremail;
        header("Location: driver_dashboard.php");
        exit();
    } else {
        $error = "❌ Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Driver Login | Student Transport</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen bg-gradient-to-b from-slate-900 via-black to-slate-900 text-neutral-100 flex items-center justify-center p-6">

    <div class="w-full max-w-md">
        <div class="rounded-2xl border border-slate-800 bg-slate-900/60 shadow-2xl p-8">

            <div class="flex flex-col items-center mb-6">
                <div class="bg-blue-600/20 text-blue-400 p-3 rounded-full mb-3">
                    <!-- Truck/Car Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-8 h-8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15a2.25 2.25 0 002.25 2.25h.75v.75A2.25 2.25 0 007.5 20.25h9a2.25 2.25 0 002.25-2.25v-.75h.75a2.25 2.25 0 002.25-2.25v-2.25a2.25 2.25 0 00-.659-1.591l-2.25-2.25a2.25 2.25 0 00-1.591-.659H6.5a2.25 2.25 0 00-1.591.659l-2.25 2.25A2.25 2.25 0 002.25 12.75V15z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15.75h7.5" />
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-blue-400 text-center">Driver Login</h1>
                <p class="text-neutral-400 text-sm mt-2">Access your driver dashboard</p>
            </div>

            <?php if (!empty($error)): ?>
                <div class="mb-4 p-3 text-sm rounded-xl bg-red-900/30 border border-red-700 text-red-400 text-center">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="space-y-5">
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

                <button type="submit"
                    class="w-full inline-flex items-center justify-center rounded-2xl bg-blue-500 text-black px-5 py-3 font-semibold hover:opacity-90 transition">
                    Login
                </button>
            </form>

            <div class="text-center text-sm text-neutral-400 mt-6 space-y-2">
                <p>Not yet registered? <a href="register_driver.php" class="text-blue-400 hover:underline">Sign up here</a></p>
                <p>Are you a regular student? <a href="login.php" class="text-blue-400 hover:underline">Login here</a></p>
            </div>
        </div>
    </div>

</body>
</html>

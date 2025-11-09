<?php
session_start();
require 'db.php';

if (isset($_SESSION['email'])) {
    header("Location: index.php");
    exit;
}

$error = '';

if ($_POST) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (login($email, $password)) {
        $_SESSION['email'] = $email;
        header("Location: index.php");
        exit();
    } else {
        $error = "❌ Invalid email or password. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Student Transport</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gradient-to-b from-neutral-900 via-black to-neutral-900 text-neutral-100 flex items-center justify-center p-6">

    <div class="w-full max-w-md">
        <div class="rounded-2xl border border-neutral-800 bg-neutral-900/60 shadow-2xl p-8">
            <h1 class="text-3xl font-bold text-center mb-6 text-yellow-300">Welcome Back</h1>

            <?php if ($error): ?>
                <div class="mb-4 text-center text-red-400">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="space-y-5">
                <div>
                    <label for="email" class="block text-sm font-medium mb-2">Email</label>
                    <input type="email" id="email" name="email" required
                        class="w-full rounded-xl border border-neutral-700 bg-black/40 px-4 py-3 outline-none focus:ring-2 focus:ring-yellow-300/60">
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium mb-2">Password</label>
                    <input type="password" id="password" name="password" required
                        class="w-full rounded-xl border border-neutral-700 bg-black/40 px-4 py-3 outline-none focus:ring-2 focus:ring-yellow-300/60">
                </div>

                <button type="submit"
                    class="w-full inline-flex items-center justify-center rounded-2xl bg-yellow-300 text-black px-5 py-3 font-semibold hover:opacity-90 transition">
                    Login
                </button>
            </form>

            <div class="text-center text-sm text-neutral-400 mt-6 space-y-2">
                <p>Not yet a member? <a href="register.php" class="text-yellow-300 hover:underline">Sign up here</a></p>
                <p>Are you a driver? <a href="driver_login.php" class="text-yellow-300 hover:underline">Login here</a></p>
            </div>
        </div>
    </div>

</body>
</html>

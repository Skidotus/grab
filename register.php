<?php
require 'db.php';

$errorMessage = '';

if ($_POST) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $phonenumber = $_POST['phonenumber'];
    $address = $_POST['address'];
    $birthdate = $_POST['birthdate'];

    // Validate birthdate
    $birthDateTime = new DateTime($birthdate);
    $currentDateTime = new DateTime();
    $age = $currentDateTime->diff($birthDateTime)->y;

    if ($age < 19) {
        $errorMessage = "You must be at least 19 years old to register.";
    }

    if (empty($errorMessage)) {
        $picture = $_FILES['uploadimage'];
        $filename = uploadImage($picture);

        if (register($username, $email, $password, $phonenumber, $address, $birthdate, $filename)) {
            header("Location: login.php");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | Student Transport</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const errorMessage = "<?php echo addslashes($errorMessage); ?>";
            if (errorMessage) {
                const toast = document.getElementById('toast');
                toast.classList.remove('hidden');
                toast.innerText = errorMessage;
                setTimeout(() => {
                    toast.classList.add('hidden');
                }, 5000);
            }
        });
    </script>
</head>

<body class="min-h-screen bg-gradient-to-b from-neutral-900 via-black to-neutral-900 text-neutral-100 flex items-center justify-center p-6">

    <div id="toast" class="hidden fixed top-5 right-5 bg-red-500 text-white p-3 rounded-lg shadow-lg">
        <!-- Error message will be displayed here -->
    </div>

    <div class="w-full max-w-md">
        <div class="rounded-2xl border border-neutral-800 bg-neutral-900/60 shadow-2xl p-8">
            <h1 class="text-3xl font-bold text-center mb-6 text-yellow-300">Create Your Account</h1>

            <form method="POST" enctype="multipart/form-data" class="space-y-5">

                <div>
                    <label for="name" class="block text-sm font-medium mb-2">Full Name</label>
                    <input type="text" id="name" name="username" required
                        class="w-full rounded-xl border border-neutral-700 bg-black/40 px-4 py-3 outline-none focus:ring-2 focus:ring-yellow-300/60">
                </div>

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

                <div>
                    <label for="phonenumber" class="block text-sm font-medium mb-2">Phone Number</label>
                    <input type="text" id="phonenumber" name="phonenumber" required
                        class="w-full rounded-xl border border-neutral-700 bg-black/40 px-4 py-3 outline-none focus:ring-2 focus:ring-yellow-300/60">
                </div>

                <div>
                    <label for="birthdate" class="block text-sm font-medium mb-2">Birth Date</label>
                    <input type="date" id="birthdate" name="birthdate" required
                        class="w-full rounded-xl border border-neutral-700 bg-black/40 px-4 py-3 text-neutral-300 outline-none focus:ring-2 focus:ring-yellow-300/60">
                </div>

                <div>
                    <label for="address" class="block text-sm font-medium mb-2">Address (optional)</label>
                    <input type="text" id="address" name="address"
                        class="w-full rounded-xl border border-neutral-700 bg-black/40 px-4 py-3 outline-none focus:ring-2 focus:ring-yellow-300/60">
                </div>

                <div>
                    <label for="uploadimage" class="block text-sm font-medium mb-2">Profile Picture (optional)</label>
                    <input type="file" id="uploadimage" name="uploadimage"
                        class="w-full text-sm text-neutral-300 border border-neutral-700 rounded-xl cursor-pointer bg-black/40 focus:ring-2 focus:ring-yellow-300/60">
                </div>

                <button type="submit"
                    class="w-full inline-flex items-center justify-center rounded-2xl bg-yellow-300 text-black px-5 py-3 font-semibold hover:opacity-90 transition">
                    Register
                </button>
            </form>

            <div class="text-center text-sm text-neutral-400 mt-6 space-y-2">
                <p>Already a member? <a href="login.php" class="text-yellow-300 hover:underline">Login here</a></p>
                <p>Register as Driver? <a href="register_driver.php" class="text-yellow-300 hover:underline">Register here!</a></p>
            </div>
        </div>
    </div>

</body>
</html>

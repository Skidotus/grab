<?php
require "db.php";
session_start();

if (!isset($_SESSION['dremail'])) {
    header("Location: driver_login.php");
    exit;
}

$message = "";

if ($_POST) {
    $drid = $_POST['drID'];
    $drusername = $_POST['drName'];
    $dremail = $_POST['drEmail'];
    $drpassword = $_POST['drPassword'];
    $draddress = $_POST['drAddress'];
    $drphonenumber = $_POST['drPhone'];
    $drbirthdate = $_POST['drBirthdate'];

    if (DriverupdateByID($drid, $drusername, $dremail, $drpassword, $draddress, $drbirthdate, $drphonenumber)) {
        $message = "✅ Profile updated successfully!";
        $_SESSION['dremail'] = $dremail;
    } else {
        $message = "❌ Failed to update profile.";
    }
}

$user = selectDriverByEmail($_SESSION['dremail']);
?>
<!DOCTYPE html>
<html lang="en" class="bg-gray-950 text-white">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Driver Profile | Student Transport</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen flex flex-col">

    <!-- HEADER -->
    <header class="bg-gradient-to-r from-amber-500 to-orange-600 shadow-lg py-4 px-6 flex justify-between items-center">
        <h1 class="text-2xl font-bold tracking-wide flex items-center gap-2">
            🧾 Edit Driver Profile
        </h1>
        <div class="text-right">
            <p class="text-sm text-gray-200">Logged in as</p>
            <p class="font-semibold text-lg text-white"><?php echo htmlspecialchars($_SESSION['dremail']); ?></p>
        </div>
    </header>

    <!-- MAIN -->
    <main class="flex-grow flex items-center justify-center py-12 px-4">
        <div class="bg-gray-900 rounded-2xl shadow-xl p-8 w-full max-w-2xl">

            <h2 class="text-2xl font-bold text-amber-400 mb-6 text-center">Update Profile</h2>

            <!-- Message Box -->
            <?php if ($message): ?>
                <div class="mb-6 text-center p-3 rounded-xl 
                    <?php echo strpos($message, '✅') !== false 
                        ? 'bg-green-600 text-white' 
                        : 'bg-red-600 text-white'; ?>">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <!-- FORM -->
            <form method="POST" class="space-y-5">
                <input type="hidden" name="drID" value="<?php echo $user['Driver_ID']; ?>">

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Full Name</label>
                    <input type="text" name="drName" 
                           value="<?php echo htmlspecialchars($user['Driver_Name']); ?>" 
                           required
                           class="w-full px-4 py-2 rounded-xl bg-gray-800 border border-gray-700 focus:ring-2 focus:ring-amber-500 focus:outline-none">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Email</label>
                    <input type="email" name="drEmail" 
                           value="<?php echo htmlspecialchars($user['Driver_Email']); ?>" 
                           required
                           class="w-full px-4 py-2 rounded-xl bg-gray-800 border border-gray-700 focus:ring-2 focus:ring-amber-500 focus:outline-none">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Password</label>
                    <input type="password" name="drPassword" 
                        value="<?php echo isset($user['Driver_Password']) ? htmlspecialchars($user['Driver_Password'], ENT_QUOTES) : ''; ?>"
                        class="w-full px-4 py-2 rounded-xl bg-gray-800 border border-gray-700 focus:ring-2 focus:ring-amber-500 focus:outline-none">
                    <p class="text-xs text-gray-400 mt-1">Leave blank to keep your current password.</p>
                </div>


                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Address</label>
                    <input type="text" name="drAddress" 
                           value="<?php echo htmlspecialchars($user['Driver_Address']); ?>"
                           class="w-full px-4 py-2 rounded-xl bg-gray-800 border border-gray-700 focus:ring-2 focus:ring-amber-500 focus:outline-none">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Phone Number</label>
                    <input type="text" name="drPhone" 
                           value="<?php echo htmlspecialchars($user['Driver_Phone']); ?>"
                           class="w-full px-4 py-2 rounded-xl bg-gray-800 border border-gray-700 focus:ring-2 focus:ring-amber-500 focus:outline-none">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Birthdate</label>
                    <input type="date" name="drBirthdate" 
                           value="<?php echo htmlspecialchars($user['Driver_Birthdate']); ?>"
                           class="w-full px-4 py-2 rounded-xl bg-gray-800 border border-gray-700 focus:ring-2 focus:ring-amber-500 focus:outline-none">
                </div>

                <!-- Buttons -->
                <div class="flex justify-center gap-4 mt-8">
                    <button type="submit"
                            class="px-6 py-2 rounded-xl bg-amber-500 hover:bg-amber-600 text-white font-semibold transition shadow-md">
                        💾 Save Changes
                    </button>
                    <a href="driver_profile.php"
                       class="px-6 py-2 rounded-xl bg-gray-700 hover:bg-gray-600 text-white font-semibold transition shadow-md">
                        ← Back
                    </a>
                </div>
            </form>
        </div>
    </main>

    <!-- FOOTER -->
    <footer class="bg-gray-900 text-gray-500 text-center py-3 text-sm mt-auto">
        © <?php echo date('Y'); ?> Student Transport | Driver Portal
    </footer>
</body>
</html>

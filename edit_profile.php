<?php
require "db.php";
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

$message = "";

if ($_POST) {
    $id = $_POST['ID'];
    $username = $_POST['Name'];
    $email = $_POST['Email'];
    $password = $_POST['Password'];
    $address = $_POST['Address'];
    $phonenumber = $_POST['Phone'];
    $birthdate = $_POST['Birthdate'];

    if (updateByID($id, $username, $email, $password, $address, $birthdate, $phonenumber)) {
        $message = "✅ Profile updated successfully!";
        $_SESSION['email'] = $email;
    } else {
        $message = "❌ Failed to update profile.";
    }
}

$user = selectUserByEmail($_SESSION['email']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gradient-to-b from-neutral-900 via-black to-neutral-900 text-neutral-100 flex items-center justify-center p-6">

    <div class="w-full max-w-2xl">
        <div class="rounded-2xl border border-neutral-800 bg-neutral-900/60 shadow-2xl p-8">
            <h2 class="text-2xl md:text-3xl font-bold mb-6 text-yellow-300 text-center">Edit Profile</h2>

            <?php if ($message): ?>
                <div class="mb-6 text-center <?php echo strpos($message, '✅') !== false ? 'text-green-400' : 'text-red-400'; ?>">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <form class="space-y-5" method="POST">
                <input type="hidden" name="ID" value="<?php echo $user['User_ID']; ?>">

                <div>
                    <label class="block text-sm font-medium mb-1">Name</label>
                    <input type="text" name="Name" value="<?php echo htmlspecialchars($user['User_Name']); ?>"
                        class="w-full rounded-xl border border-neutral-700 bg-black/40 px-4 py-3 outline-none focus:ring-2 focus:ring-yellow-300/60"
                        required>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Email</label>
                    <input type="email" name="Email" value="<?php echo htmlspecialchars($user['User_Email']); ?>"
                        class="w-full rounded-xl border border-neutral-700 bg-black/40 px-4 py-3 outline-none focus:ring-2 focus:ring-yellow-300/60"
                        required>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Password</label>
                    <input type="password" name="Password" 
                        value="<?php echo isset($user['User_Password']) ? htmlspecialchars($user['User_Password'], ENT_QUOTES) : ''; ?>"
                        class="w-full rounded-xl border border-neutral-700 bg-black/40 px-4 py-3 outline-none focus:ring-2 focus:ring-yellow-300/60">
                    <p class="text-xs text-neutral-400 mt-1">Leave blank to keep your current password.</p>
                </div>



                <div>
                    <label class="block text-sm font-medium mb-1">Address</label>
                    <input type="text" name="Address" value="<?php echo htmlspecialchars($user['User_Address']); ?>"
                        class="w-full rounded-xl border border-neutral-700 bg-black/40 px-4 py-3 outline-none focus:ring-2 focus:ring-yellow-300/60">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Phone Number</label>
                        <input type="text" name="Phone" value="<?php echo htmlspecialchars($user['User_Phone']); ?>"
                            class="w-full rounded-xl border border-neutral-700 bg-black/40 px-4 py-3 outline-none focus:ring-2 focus:ring-yellow-300/60">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Birth Date</label>
                        <input type="date" name="Birthdate" value="<?php echo htmlspecialchars($user['User_Birthdate']); ?>"
                            class="w-full rounded-xl border border-neutral-700 bg-black/40 px-4 py-3 outline-none focus:ring-2 focus:ring-yellow-300/60">
                    </div>
                </div>

                <div class="flex items-center justify-center gap-4 pt-4">
                    <button type="submit"
                        class="inline-flex items-center justify-center rounded-2xl bg-yellow-300 text-black px-6 py-2.5 font-semibold hover:opacity-90 transition">
                        Save Changes
                    </button>
                    <a href="profile.php"
                        class="inline-flex items-center justify-center rounded-2xl border border-neutral-700 px-6 py-2.5 hover:bg-white/5 transition">
                        Back
                    </a>
                </div>
            </form>
        </div>
    </div>

</body>
</html>

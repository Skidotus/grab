<?php
require "db.php";
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

$users = selectAllUsers();

?>
<html>
<p>Welcome <?php echo $_SESSION['email']; ?></p>
<a href="logout.php">Logout</a>
<table>
    <tr>
        <th>Username</th>
        <th>Email</th>
        <th>Action</th>
    </tr>
    <?php
    foreach ($users as $user) {
        echo "<tr>";
        echo "<td>" . $user['User_Name'] . "</td>";
        echo "<td>" . $user['User_Email'] . "</td>";
        echo "<td><a href='delete.php?id=" . $user['User_ID'] . "'>Delete</a></td>";
        echo "<td><a href='edit.php?id=" . $user['User_ID'] . "'>Edit</a></td>";
        echo "</tr>";
    }
    ?>
</table>

</html>
<?php
$servername = '127.0.0.1';
$dbname = 'student_transport';
$dbusername = 'root';
$dbpassword = '';

$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

    function register($username, $email, $password, $phonenumber, $address, $birthdate, $filename) {
        global $conn;
        $sql = "INSERT INTO user_creds (User_Name, User_Email, User_Pass, User_Phone, User_Address, User_Birthdate, User_Picture) VALUES ('$username', '$email', '$password', '$phonenumber', '$address', '$birthdate', '$filename')";
        return $conn->query($sql);
    }

    function registerdriver($drusername, $dremail, $drpassword, $drphonenumber, $draddress, $drbirthdate, $filename) {
        global $conn;
        $sql = "INSERT INTO drivers (Driver_Name, Driver_Email, Driver_Pass, Driver_Phone, Driver_Address, Driver_Birthdate, Driver_Picture) VALUES ('$drusername', '$dremail', '$drpassword', '$drphonenumber', '$draddress', '$drbirthdate', '$filename')";
        return $conn->query($sql);
    }

    function login($email, $password) {
    global $conn;
    $sql = "SELECT * FROM user_creds 
            WHERE User_Email = '$email' AND User_Pass = '$password'";
    $result = $conn->query($sql);
    return $result->fetch_assoc();
}

    function logindriver($dremail, $drpassword) {
        global $conn;
        $sql = "SELECT * FROM drivers WHERE Driver_Email = '$dremail' AND Driver_Pass = '$drpassword'";
        $result = $conn->query($sql);
        return $result->fetch_assoc();
    }

    function selectAllUsers(){
        global $conn;
        $sql = "SELECT * FROM user_creds";
        $result = $conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    function deleteUserByID($id){
        global $conn;
        $sql = "DELETE FROM user_creds WHERE User_ID = '$id'";
        $conn->query($sql);
    }

    function selectUserByID($id){
        global $conn;
        $sql = "SELECT * FROM user_creds WHERE User_ID ='$id'";
        $result = $conn->query($sql);
        return $result->fetch_assoc();
    }

    function updateByID($id, $username, $email, $password, $address, $birthdate, $phonenumber){
        global $conn;
        $sql = "UPDATE user_creds SET User_Name = '$username', User_Email = '$email', User_Pass = '$password', User_Address = '$address', User_Birthdate = '$birthdate', User_Phone = '$phonenumber' WHERE User_ID = '$id'";
        return $conn->query($sql);
    }

     function DriverupdateByID($drid, $drusername, $dremail, $drpassword, $draddress, $drbirthdate, $drphonenumber){
        global $conn;
        $sql = "UPDATE drivers SET Driver_Name = '$drusername', Driver_Email = '$dremail', Driver_Pass = '$drpassword', Driver_Address = '$draddress', Driver_Birthdate = '$drbirthdate', Driver_Phone = '$drphonenumber' WHERE Driver_ID = '$drid'";
        return $conn->query($sql);
    }

    function selectUserByEmail($email){
        global $conn;
        $sql = "SELECT * FROM user_creds where User_Email ='$email'";
        $result = $conn->query($sql);
        return $result->fetch_assoc();
    }

    // Select a driver row by email from the drivers table
    function selectDriverByEmail($email){
        global $conn;
        $sql = "SELECT * FROM drivers WHERE Driver_Email = '$email'";
        $result = $conn->query($sql);
        return $result->fetch_assoc();
    }
    

    function uploadImage($picture) {
        $filename = $picture['name']; //extract nama file so calling senang
        $tempname = $picture['tmp_name']; //php simpan temporary dalam temp folder xampp
        $targetFolder = "./image/" . $filename; //destination gambar letak mana

        if (move_uploaded_file($tempname, $targetFolder)) {
            return $filename; 
        } else {
            return null; //
        }
    }















//hok ni bahagiay payment
function UserEmail($email) {
    global $conn;
    $sql = "SELECT * FROM user_creds WHERE User_Email = '$email' LIMIT 1";
    $result = $conn->query($sql);
    return $result ? $result->fetch_assoc() : null;
}

function thisUserTrip($trip_id, $user_id) {
    global $conn;
    $sql = "
        SELECT t.trip_id
        FROM Trip t
        JOIN Booking b ON b.booking_number = t.booking_number
        WHERE t.trip_id = '$trip_id' AND b.User_ID = '$user_id'
        LIMIT 1
    ";
    $result = $conn->query($sql);
    return $result && $result->num_rows > 0;
}

function isTripPaid($trip_id) {
    global $conn;
    $sql = "SELECT * FROM Payment WHERE trip_id = '$trip_id' LIMIT 1";
    $result = $conn->query($sql);
    return $result && $result->num_rows > 0;
}

function insertPayment($trip_id, $user_id, $amount, $method, $status) {
    global $conn;

    $method_upper = strtoupper($method);
    if (in_array($method_upper, ["CASH", "QR", "EWALLET", "E-WALLET"])) {
        $status = "Pending";
    }

    $sql = "
        INSERT INTO Payment (trip_id, User_ID, amount, method, status, paid_at)
        VALUES ('$trip_id', '$user_id', '$amount', '$method', '$status', NOW())
    ";
    return $conn->query($sql);
}

function LatestUnpaidTrip($user_id) {
    global $conn;
    $sql = "
        SELECT t.trip_id, t.fare_amount, t.distance_km, t.booking_number
        FROM Trip t
        JOIN Booking b ON b.booking_number = t.booking_number
        LEFT JOIN Payment p ON p.trip_id = t.trip_id
        WHERE b.User_ID = '$user_id' 
          AND (p.trip_id IS NULL OR p.status != 'Paid')
        ORDER BY t.created_at DESC 
        LIMIT 1
    ";
    $result = $conn->query($sql);
    return $result && $result->num_rows > 0 ? $result->fetch_assoc() : null;
}

function getPaymentByTrip($trip_id) {
    global $conn;
    $sql = "SELECT * FROM Payment WHERE trip_id = '$trip_id' ORDER BY paid_at DESC LIMIT 1";
    $result = $conn->query($sql);
    return ($result && $result->num_rows > 0) ? $result->fetch_assoc() : null;
}

function updatePendingPaymentToPaid($trip_id, $new_method) {
    global $conn;
    $new_method = $conn->real_escape_string($new_method);
    $sql = "
        UPDATE Payment
        SET method = '$new_method', status = 'Paid', paid_at = NOW()
        WHERE trip_id = '$trip_id' AND status = 'Pending'
        ORDER BY paid_at DESC
        LIMIT 1
    ";
    return $conn->query($sql);
}
//habih payment


function select_pending_bookings()
{
    global $conn;
    $sql = "SELECT booking_number AS booking_ID,user_ID,ST_AsWKT(current_user_location) AS user_location , ST_AsWKT(user_pickup_location) AS pickup_location, ST_AsWKT(user_dropoff_location) AS dropoff_location ,status FROM booking where status='pending'";
    $result = $conn->query($sql);
    return $result;

}
?>

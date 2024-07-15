<?php
include_once('db_details.php');

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$email = $_POST['email'];
$password = $_POST['password'];

// Check for the user in the database
$stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();
    
    // Verify password
    if (password_verify($password, $row['password'])) {
        // Set session variables
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['user_name'] = $row['name'];
        $_SESSION['user_email'] = $row['email'];
        //$_SESSION['user_type'] = $row['user_type'];

        // if($_SESSION['user_type'] == "Station_Owner"){
        //     echo "SO";
        // }else if($_SESSION['user_type'] == "Car_Owner"){
        //     echo "CO";
        // }

        echo "GRANTED";

    } else {
        echo "Invalid email or password";
    }
} else {
    echo "Invalid email or password";
}

$stmt->close();
$conn->close();
?>

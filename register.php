<?php
include_once('db_details.php');

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$name = $_POST['name'];
$phone = $_POST['phone'];
$email = $_POST['email'];
$address = $_POST['address'];
$password = $_POST['password'];
//$user_type = $_POST['user_type'];

// Check for duplicate email
$email_check = $conn->prepare("SELECT * FROM users WHERE email = ?");
$email_check->bind_param("s", $email);
$email_check->execute();
$email_check_result = $email_check->get_result();

if ($email_check_result->num_rows > 0) {
    echo "Email already registered";
    $email_check->close();
    $conn->close();
    exit();
}

// Check for duplicate phone
$phone_check = $conn->prepare("SELECT * FROM users WHERE phone = ?");
$phone_check->bind_param("s", $phone);
$phone_check->execute();
$phone_check_result = $phone_check->get_result();

if ($phone_check_result->num_rows > 0) {
    echo "Phone already registered";
    $phone_check->close();
    $conn->close();
    exit();
}

// Compute password hash
$hashed_password = password_hash($password, PASSWORD_BCRYPT);

// Insert new user
//$stmt = $conn->prepare("INSERT INTO users (name, phone, email, address, password, user_type) VALUES (?, ?, ?, ?, ?, ?)");
//$stmt->bind_param("ssssss", $name, $phone, $email, $address, $hashed_password, $user_type);

$stmt = $conn->prepare("INSERT INTO users (name, phone, email, address, password) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $name, $phone, $email, $address, $hashed_password);

if ($stmt->execute()) {
    echo "Registration successful";
} else {
    echo "Registration failed";
}

$stmt->close();
$conn->close();
?>

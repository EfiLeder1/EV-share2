<?php
error_reporting(1);
debug_print_backtrace(1);
include_once('db_details.php');

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // echo '<pre>';print_r($_POST);
    // echo '<pre>';print_r($_FILES);
    // exit;

    // Retrieve form data
    $station_name = $_POST['station_name'];
    $station_model = $_POST['station_model'];
    $station_year = $_POST['station_year'];
    $address = $_POST['address'];
    $charging_type = $_POST['charging_type'] ? join(',', $_POST['charging_type']):'';
    $city = $_POST['city'];
    $charging_capacity = $_POST['charging_capacity'];
    $how_to_find = $_POST['how_to_find'];
    $asking_price = $_POST['asking_price'];
    $algo_price = $_POST['algo_price'];
    $using_algorithm = isset($_POST['using_algorithm']) ? ($_POST['using_algorithm'] == 'on' ? 1:0):0;
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];

    // Handle image uploads
    $upload_dir = 'uploads/';
    $user_id = $_SESSION['user_id']; // Assuming user_id is stored in session

    // Generate unique filenames with user_id prefix
    $image1_name = $user_id . '_' . uniqid() . '_' . basename($_FILES["image1"]["name"]);
    $image1_path = $upload_dir . $image1_name;

    $image2_name = $user_id . '_' . uniqid() . '_' . basename($_FILES["image2"]["name"]);
    $image2_path = $upload_dir . $image2_name;

    // Move uploaded files to the uploads directory
    move_uploaded_file($_FILES["image1"]["tmp_name"], $image1_path);
    move_uploaded_file($_FILES["image2"]["tmp_name"], $image2_path);

    // Insert data into database
    $sql = "INSERT INTO station (user_id, station_name, station_model, station_year, address, charging_type, city, charging_capacity, how_to_find, asking_price, algo_price, latitude, longitude, image1, image2, using_algorithm)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issssssisddsssss", $user_id, $station_name, $station_model, $station_year, $address, $charging_type, $city, $charging_capacity, $how_to_find, $asking_price, $algo_price, $latitude, $longitude, $image1_name, $image2_name, $using_algorithm);
    
    if ($stmt->execute()) {
        // Redirect to station management page
        header("Location: station-management.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $stmt->close();
}

$conn->close();
?>
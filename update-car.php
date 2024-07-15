<?php
// Database connection parameters
include_once('db_details.php');

// Initialize variables for form data
$id = $car_brand = $license_plate = $model = $charging_type = $year = $battery_capacity = '';

// Check if form is submitted and ID parameter is set
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    // Assign form data to variables
    $id = $_POST['id'];
    $car_brand = $_POST['car_brand'];
    $license_plate = $_POST['license_plate'];
    $model = $_POST['model'];
    $charging_type = $_POST['charging_type'];
    $year = $_POST['year'];
    $battery_capacity = $_POST['battery_capacity'];

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare SQL statement to update car record
    $sql = "UPDATE car SET car_brand=?, license_plate=?, model=?, charging_type=?, year=?, battery_capacity=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssiiidi", $car_brand, $license_plate, $model, $charging_type, $year, $battery_capacity, $id);

    // Execute statement
    if ($stmt->execute()) {
        // Redirect to car-management.php upon success
        header("Location: car-management.php");
        exit;
    } else {
        echo "Error updating car record: " . $stmt->error;
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
} else {
    // Redirect to edit_car.php if form is not submitted properly
    header("Location: edit-car.php?id=" . $id);
    exit;
}
?>

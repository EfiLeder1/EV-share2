<?php
include_once('db_details.php');

// Create connection using PDO
$pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
// Set the PDO error mode to exception
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $carBrand = $_POST['car_brand'];
    $licensePlate = $_POST['license_plate'];
    $model = $_POST['model'];
    $chargingType = $_POST['charging_type'];
    $year = $_POST['year'];
    $batteryCapacity = $_POST['battery_capacity'];

    // SQL insert statement
    $sql = "INSERT INTO car (user_id, car_brand, license_plate, model, charging_type, year, battery_capacity) 
            VALUES (:user_id, :car_brand, :license_plate, :model, :charging_type, :year, :battery_capacity)";
    
    // Prepare the SQL statement
    $stmt = $pdo->prepare($sql);

    // Bind parameters
    $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->bindParam(':car_brand', $carBrand, PDO::PARAM_INT);
    $stmt->bindParam(':license_plate', $licensePlate, PDO::PARAM_STR);
    $stmt->bindParam(':model', $model, PDO::PARAM_INT);
    $stmt->bindParam(':charging_type', $chargingType, PDO::PARAM_STR);
    $stmt->bindParam(':year', $year, PDO::PARAM_INT);
    $stmt->bindParam(':battery_capacity', $batteryCapacity, PDO::PARAM_INT);

    try {
        // Execute the SQL statement
        $stmt->execute();
        // Redirect after successfully adding the vehicle
        header("Location: car-management.php");
        exit();
    } catch (PDOException $e) {
        // Redirect with error if SQL execution fails
        header("Location: add_vehicle.php?error=1");
        exit();
    }
}
?>

<?php
include_once('db_details.php');

// Check if ID parameter is set and valid
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    // Assign ID from GET parameter
    $id = $_GET['id'];

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare SQL statement to delete car record
    $sql = "DELETE FROM car WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    // Execute statement
    if ($stmt->execute()) {
        // Redirect to car-management.php upon success
        header("Location: car-management.php");
        exit;
    } else {
        echo "Error deleting car record: " . $stmt->error;
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
} else {
    // Redirect to car-management.php if ID parameter is not set properly
    header("Location: car-management.php");
    exit;
}
?>

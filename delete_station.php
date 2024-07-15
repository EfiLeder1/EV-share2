<?php
include_once('db_details.php');

if($database == "")
    $database = "ewevolut_ev-share";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if station_id parameter is provided through GET
if (isset($_GET['id'])) {
    // Sanitize the input to prevent SQL injection
    $station_id = mysqli_real_escape_string($conn, $_GET['id']);

    // SQL query to delete the station from the database
    $sql = "DELETE FROM station WHERE id = " . $station_id;

    // Execute the DELETE query
    if (mysqli_query($conn, $sql)) {
        // If deletion is successful, redirect to a success page or display a message
        header("Location: station-management.php"); // Replace with your desired redirect location
        exit();
    } else {
        // If deletion fails, handle the error
        echo "Error deleting station: " . mysqli_error($conn);
    }
} else {
    // If station_id parameter is missing, redirect to an error page or display a message
    echo "Station ID parameter is missing.";
}

// Close the database connection
mysqli_close($conn);
?>

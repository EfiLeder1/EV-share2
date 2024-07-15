<?php
include_once('db_details.php');
include_once("functions.php");

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if ID parameter is set and valid
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $panel_action = isset($_GET['action']) ? $_GET['action']:'';

    if($panel_action == 'remove-car'){
        $car_id = isset($_GET['car']) ? $_GET['car']:'';
    
        /** Fetching Chargin Session */
        $session_sql = "select * from charging_sessions WHERE car_id = ".$car_id." order by id DESC";
        $session_result = $conn->query($session_sql);
        $session_data = $session_result->fetch_assoc();

        // If charging session invalid
        if(!$session_data || count($session_data) <= 0 || $session_data['end_time'] || $session_data['end_time'] != null){
            header("Location: panel-page.php"); exit;
        } 
    
        /** Updating Car Charging Status */
        $car_sql = "update car set is_charging = 0 WHERE id=? and user_id = ?";
        $car_stmt = $conn->prepare($car_sql);
        $car_stmt->bind_param("ii", $car_id,  $_SESSION['user_id']);
        if (!$car_stmt->execute()) {
            echo "Error updating car charging status: " . $car_stmt->error;
        }
        $car_stmt->close();
    
        /** Updating Station Available Status */
        $station_sql = "update station set is_available = 1 WHERE id=? and user_id = ?";
        $station_stmt = $conn->prepare($station_sql);
        $station_stmt->bind_param("ii", $session_data['station_id'],  $_SESSION['user_id']);
        if (!$station_stmt->execute()) {
            echo "Error updating station available status: " . $station_stmt->error;
        }
        $station_stmt->close();
    
        /** Updating Charging Session */
        $session_end_time = date('Y-m-d H:i:s', strtotime('now'));
        $session_total_time = getTimeDifferenceInHrs($session_data['start_time'], $session_end_time);
        $session_total_price = $session_total_time == 0 && $session_data['hourly_price'] ? 0: (float)$session_data['hourly_price'] * (float)$session_total_time;

        $session_sql = "update charging_sessions set end_time = '$session_end_time', total_time = $session_total_time, total_price = $session_total_price WHERE id=?";
        $session_stmt = $conn->prepare($session_sql);
        $session_stmt->bind_param("i", $session_data['id']);
        if (!$session_stmt->execute()) {
            echo "Error updating charging session: " . $session_stmt->error;
        }
        $session_stmt->close();
    }
} if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $panel_action = isset($_POST['action']) ? $_POST['action']:'';
    // echo $panel_action;die;

    if ($panel_action == 'rent-car'){
        $car_id = isset($_POST['car']) ? $_POST['car']:'';
        $station_id = isset($_POST['station']) ? $_POST['station']:'';
        $charging_price = isset($_POST['charging_price']) ? $_POST['charging_price']:0;
        $user_id = $_SESSION['user_id'];

        // echo $station_id;
        // echo $charging_price;
        // echo $user_id;

        if(!$car_id || !$station_id){
            header("Location: panel-page.php"); exit;
        }
    
        /** Updating Car Charging Status */
        $car_sql = "update car set is_charging = 1 WHERE id=? and user_id = ?";
        $car_stmt = $conn->prepare($car_sql);
        $car_stmt->bind_param("ii", $car_id,  $user_id);
        if (!$car_stmt->execute()) {
            echo "Error updating car charging status: " . $car_stmt->error;
        }
        $car_stmt->close();
    
        /** Updating Station Available Status */
        $station_sql = "update station set is_available = 0 WHERE id=? and user_id = ?";
        $station_stmt = $conn->prepare($station_sql);
        $station_stmt->bind_param("ii", $station_id,  $user_id);
        if (!$station_stmt->execute()) {
            echo "Error updating station available status: " . $station_stmt->error;
        }
        $station_stmt->close();
    
        /** Updating Charging Session */
        $hourly_price = $charging_price;
        $start_time = date('Y-m-d H:i:s', strtotime('now'));
        $session_sql = "insert into charging_sessions (user_id, station_id, car_id, hourly_price, start_time) values ($user_id, $station_id, $car_id, $hourly_price, '$start_time')";
        $session_stmt = $conn->prepare($session_sql);
        if (!$session_stmt->execute()) {
            echo "Error updating charging session: " . $session_stmt->error;
        }
        $session_stmt->close();
    }
}

$conn->close();

// Redirect to Panel Page
header("Location: panel-page.php"); exit;

?>

<?php 
    session_start();

    include_once('db_details.php');
    include_once('functions.php');

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    $user_id = $_SESSION['user_id'];

    /** Fetching all stations */
    $station_sql = "SELECT *,
         (select name from station_models where id = station.station_model) as model
    FROM station WHERE user_id = " . $user_id;
    $station_result = $conn->query($station_sql);
    $stations = [];
    if ($station_result->num_rows > 0) {
        while ($row = $station_result->fetch_assoc()) {
            $stations[] = $row;
        }
    }
    
    $selected_station = "";
    $sessions = [];
    if (isset($_GET['station']) && is_numeric($_GET['station'])) {
        $selected_station = $_GET['station'];

        $session_sql= "select car.id, SUM(session.total_price) as usage_cost, SUM(session.total_time) as usage_time,
            (select name from car_brands where id = car.car_brand) as car_brand,
            (select name from car_models where id = car.model) as car_model,
            (select count(*) from charging_sessions where station_id = session.station_id and car_id = car.id) as charge_counts
        from charging_sessions as session 
        inner join car as car on session.car_id = car.id 
        where session.station_id = $selected_station and session.user_id = $user_id
        group by session.car_id";

        $session_result = $conn->query($session_sql);
        if ($session_result->num_rows > 0) {
            while ($row = $session_result->fetch_assoc()) {
                $sessions[] = $row;
            }
        }
    }


    $conn->close();

    // echo "<pre>"; print_r($sessions); echo "</pre>";die;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EV SHARE </title>
    <link rel="shortcut icon" href="assets/images/favicon.png" type="image/png">
    <link rel="stylesheet" href="./assets/css/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <!-- ====================== Header===================== -->
    <div class="main" style="background-image: url(./assets/images/wave-bg.jpg);">
        <div class="container h-100 z-index-3 position-relative">
            <div class="d-flex align-items-center gap-md-5 gap-2 flex-wrap mt-5">
                <div class="locate-button position-relative"><a class="text-dark" href="reports.php">All Reports</a></div>
            </div>
            <div class="station-management w-lg-75">
                <h1>Stations Report</h1>
                <div class="col-md-6 mb-5">
                    <div class="w-100 position-relative">
                        <div class="si-border"></div>
                        <select id="station-selection" required class="form-select h-55px form-select-solid ps-13">
                            <option value="">Choose Station</option>
                            <?php
                                if($stations){
                                    foreach($stations as $station){
                                        $selected =  $selected_station == $station['id'] ? "selected":"";
                                        echo '<option value="'.$station['id'].'" '. $selected .' >'.$station['station_name'].' '.$station['model'].'</option>';
                                    }
                                }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
            <?php if($selected_station != ""){ ?>
                <table class="table">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col" class="text-start">Car</th>
                            <th scope="col">Charge Count</th>
                            <th scope="col">Usage Time</th>
                            <th scope="col">Cost Spend</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if($sessions && count($sessions) > 0){ ?>
                            <?php foreach($sessions as $session){ ?>
                                <tr>
                                    <th class="text-start"><?php echo $session['car_brand'] . ' ' . $session['car_model']?></th>
                                    <td><?php echo $session['charge_counts']?> Times</td>
                                    <td><?php echo $session['usage_time']?> Hours</td>
                                    <td>$<?php echo $session['usage_cost']?></td>
                                </tr>
                            <?php } ?>
                        <?php } else { ?>
                            <tr>
                                <th class="text-center" scope="row" colspan="3">No Record Found</th>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            <?php } ?>
        </div>
    </div>
    <script>
        const baseUrl = '<?php echo baseUrl() ?>';

        $('document').ready(() => {
        });

        $('#station-selection').change((event) => {
            const stationId = event.target.value;
            const currentPage = window.location.href.split('?')[0];
            
            if(stationId){
                window.location = `${currentPage}?station=${stationId}`;
            } else {
                window.location = currentPage;
            }
        });
    </script>
</body>
</html>
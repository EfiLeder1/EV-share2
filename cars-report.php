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

    /** Fetching all cars */
    $car_sql = "SELECT *,
        (select name from car_brands where id = car.car_brand) as selected_brand,
        (select name from car_models where id = car.model) as selected_model 
    FROM car WHERE user_id = " . $user_id;
    $car_result = $conn->query($car_sql);
    $cars = [];
    if ($car_result->num_rows > 0) {
        while ($row = $car_result->fetch_assoc()) {
            $cars[] = $row;
        }
    }
    
    $selected_car = "";
    $sessions = [];
    if (isset($_GET['car']) && is_numeric($_GET['car'])) {
        $selected_car = $_GET['car'];

        $session_sql = "select station.station_name, station.charging_capacity, station.address, SUM(session.total_price) as usage_cost, SUM(session.total_time) as usage_time
        from charging_sessions as session
        inner join station as station on session.station_id = station.id
        where session.car_id = $selected_car and session.user_id = $user_id
        group by session.car_id, station.station_name, station.charging_capacity, station.address";

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
                <h1>Cars Report</h1>
                <div class="col-md-6 mb-5">
                    <div class="w-100 position-relative">
                        <div class="si-border"></div>
                        <select id="car-selection" required class="form-select h-55px form-select-solid ps-13">
                            <option value="">Choose Car</option>
                            <?php
                                if($cars){
                                    foreach($cars as $car){
                                        $selected =  $selected_car == $car['id'] ? "selected":"";
                                        echo '<option value="'.$car['id'].'" '. $selected .' >'.$car['selected_brand'].' '.$car['selected_model'].'</option>';
                                    }
                                }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
            <?php if($selected_car != ""){ ?>
                <table class="table">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col" class="text-start">Station</th>
                            <th scope="col" class="text-start">Address</th>
                            <th scope="col">Capacity</th>
                            <th scope="col">Usage Time</th>
                            <th scope="col">Cost Spend</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if($sessions && count($sessions) > 0){ ?>
                            <?php foreach($sessions as $session){ ?>
                                <tr>
                                    <th class="text-start"><?php echo $session['station_name']?></th>
                                    <td class="text-start"><?php echo $session['address']?></td>
                                    <td><?php echo $session['charging_capacity']?> KWH</td>
                                    <td><?php echo $session['usage_time']?> Hours</td>
                                    <td>$<?php echo $session['usage_cost']?></td>
                                </tr>
                            <?php } ?>
                        <?php } else { ?>
                            <tr>
                                <th class="text-center" scope="row" colspan="6">No Record Found</th>
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

        $('#car-selection').change((event) => {
            const carId = event.target.value;
            const currentPage = window.location.href.split('?')[0];
            
            if(carId){
                window.location = `${currentPage}?car=${carId}`;
            } else {
                window.location = currentPage;
            }
        });
    </script>
</body>
</html>
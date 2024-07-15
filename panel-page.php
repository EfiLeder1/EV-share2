<?php 
    session_start();

    include_once('db_details.php');
    include_once('functions.php');

    // echo baseUrl();die;

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    /** Fetching all stations */
    $station_sql = "SELECT *,
         (select name from station_models where id = station.station_model) as model
    FROM station WHERE user_id = " . $_SESSION['user_id'];
    $station_result = $conn->query($station_sql);
    $stations = [];
    if ($station_result->num_rows > 0) {
        while ($row = $station_result->fetch_assoc()) {
            $stations[] = $row;
        }
    }

    /** Fetching all cars */
    $car_sql = "SELECT *,
        (select name from car_brands where id = car.car_brand) as selected_brand,
        (select name from car_models where id = car.model) as selected_model 
    FROM car WHERE user_id = " . $_SESSION['user_id'];
    $car_result = $conn->query($car_sql);
    $cars = [];
    if ($car_result->num_rows > 0) {
        while ($row = $car_result->fetch_assoc()) {
            $cars[] = $row;
        }
    }

    /** Fetching system values */
    $system_query = "SELECT * FROM system_values";
    $system_stmt = $conn->prepare($system_query);
    $system_stmt->execute();
    $sys_result = $system_stmt->get_result();

    $system_values = [];
    while ($row = $sys_result->fetch_assoc()) {
        $system_values[$row['name']] = $row['value'];
    }

    // echo "<pre>"; print_r($system_values); echo "</pre>";die;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EV SHARE</title>
    <link rel="shortcut icon" href="assets/images/favicon.png" type="image/png">
    <link rel="stylesheet" href="./assets/css/style.css">
    
    <style>
        #map {
            width: 100%;
            height: 500px;
        }
        .payment-options button{
            font-size: 20px !important;
        }
    </style>
</head>
<body>
    <!-- ====================== Locate Charging Station ===================== -->
    <div class="main h-auto min-h-auto" style="background-image: url(./assets/images/locate-charging-station-bg.jpg);">
        <div class="container h-100 z-index-3 position-relative">
            <div class="locate-charging-station">
                <div class="d-flex align-items-center gap-md-5 gap-2 flex-wrap">
                    <div class="locate-button position-relative"><a class="text-dark" href="reports.php">Reports</a></div>
                    <?php if($_SESSION['user_type'] == "Station_Owner"){ ?>
                        <div class="locate-button position-relative"><a class="text-dark" href="station-management.php">Stations Managment</a></div>
                    <?php } else if($_SESSION['user_type'] == "Car_Owner"){ ?>
                        <div class="locate-button position-relative"><a class="text-dark" href="car-management.php">Car Managment</a></div>
                    <?php } ?>
                    <div class="locate-button position-relative"><a class="text-dark" href="logout.php">Logout</a></div>
                </div>
                <h1 class="py-5 mb-7">Locate Charging Station</h1>
                <div class="row">
                    <div id="map-container" class="col-xl-12">
                        <div class="d-flex  align-items-center gap-md-5 gap-2 mb-5 flex-wrap">
                            <img src="./assets/images/car-Icon.svg" class="h-40px" alt="">
                            <h2 class="m-0">choose vehicle</h2>
                            <div class="w-200px position-relative">
                                <div class="si-border"></div>
                                <select id="selectCar" class="form-select form-select-solid ps-13" data-kt-select2="true" data-placeholder="Select option" data-dropdown-parent="#kt_menu_62cfa323042ea" data-allow-clear="true">
                                    <option value="">--select--</option>
                                    <?php
                                        if($cars){
                                            foreach($cars as $key => $car){
                                                if($car['is_charging'] !== '1'){
                                                    echo '<option value="'.$car['id'].'">'.$car['selected_brand'].' '.$car['selected_model'].'</option>';
                                                }
                                            }
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="map">
                            <div id="searchInputArea" class="search-bar">
                                <input id="searchInput" type="text" placeholder="search..." value="">
                                <div class="search-btn"><i class="fa fa-search" aria-hidden="true"></i></div>
                            </div>
                            <!-- <input id="searchInput" class="controls" type="text" placeholder="Enter a location"> -->
                            <div id="map"></div>
                            <!-- <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d54084.94909065688!2d34.75604657871049!3d32.08792483335339!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x151d4ca6193b7c1f%3A0xc1fb72a2c0963f90!2sTel%20Aviv-Yafo%2C%20Israel!5e0!3m2!1sen!2s!4v1716064810014!5m2!1sen!2s" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe> -->
                        </div>
                        <div class="d-flex align-items-center gap-md-12 gap-4 mt-7 flex-wrap">
                            <h2 class="m-0">Being charged</h2>
                            <div class="d-flex gap-3 flex-column butto">
                                <?php
                                    if($cars){
                                        foreach($cars as $key => $car){
                                            if($car['is_charging'] == '1'){ ?>
                                                <div class="d-flex gap-3">
                                                    <button class="btn btn-success rounded-pill fs-1 fw-500 w-250px"><?php echo $car['selected_brand'].' '.$car['selected_model'] ?></button>
                                                    <a href="panel-page-actions.php?action=remove-car&car=<?php echo $car['id'] ?>" class="btn btn-danger fw-500 rounded-pill pt-1">
                                                        <i class="fa fa-times fs-2tx m-0 p-0" aria-hidden="true"></i>
                                                    </a>
                                                </div>
                                            <?php }
                                        }
                                    }
                                ?>
                                <!-- <div class="d-flex gap-3">
                                    <button class="btn btn-success rounded-pill fs-1 fw-500 w-250px">ORA FUNKY CAT</button>
                                    <button class="btn btn-danger fw-500 rounded-pill pt-1">
                                        <i class="fa fa-times fs-2tx m-0 p-0" aria-hidden="true"></i>
                                    </button>
                                </div> -->
                            </div>
                        </div>
                    </div>
                    <div id="station-details" class="col-xl-4 position-relative d-none">
                        <div class="clearfix">
                            <h1 id="st-d-info" class="text-end mt-0 mb-4 float-start">Station INFO</h1>
                            <h1 class="text-end mt-0 mb-4 float-end cursor-pointer" onclick="toggleStationDetails()">X</h1>
                        </div>
                        <div class="row d-flex justify-content-end">
                            <div class="mb-1 col-sm-5">
                                <p class="fs-5 fw-semibold text-white mr-3 text-end text-uppercase">Status</p>
                                <p class="fs-5 fw-semibold text-white mr-3 text-end">
                                    <span id="st-d-status-color" class="h-10px w-10px rounded-pill mr-2 position-absolute" style="margin-top: 5px;"></span>
                                    <span id="st-d-available-status" class="ps-5 text-uppercase">Available</span>
                                </p>
                            </div>
                            <div class="mb-1 col-sm-7  text-end">
                                <label class="fs-3 fw-semibold text-white mr-3">
                                    STATION NAME
                                </label>
                                <input id="st-d-station" type="text" class="form-control form-control-lg form-control-solid" name="name" placeholder="EV Edge Charging Station" value="">
                            </div>
                            <div class="mb-1 col-sm-5">
                                <p class="fs-5 fw-semibold text-white mr-3 text-end text-uppercase"> Available Until:</p>
                                <p id="st-d-avilable-until" class="fs-1 fw-semibold text-white mr-3 text-end">17:00</p>
                            </div>
                            <div class="mb-1 col-sm-7 text-end">
                                <div class="row">
                                    <div class="mb-1 col-6">
                                        <label class="fs-3 fw-semibold text-white mr-3">
                                            CITY
                                        </label>
                                        <input id="st-d-city" type="text" class="form-control form-control-lg form-control-solid text-end" name="name" placeholder="תל אביב" value="">
                                    </div>
                                    <div class="mb-1 col-6">
                                        <label class="fs-3 fw-semibold text-white mr-3">
                                            ADDRESS
                                        </label>
                                        <input id="st-d-address" type="text" class="form-control form-control-lg form-control-solid text-end" name="name" placeholder="הוברמן 6 " value="">
                                    </div>
                                </div>
                            </div>
                            <div class="mb-1 col-sm-12 text-end">
                                <div class="row">
                                    <div class="mb-1 col-6">
                                        <label class="fs-3 fw-semibold text-white mr-3">
                                            KWH
                                        </label>
                                        <input id="st-d-charge-capacity" type="text" class="form-control form-control-lg form-control-solid text-center" name="name" placeholder="50" value="">
                                    </div>
                                    <div class="mb-1 col-6">
                                        <label class="fs-3 fw-semibold text-white mr-3">
                                            CHARGER TYPE
                                        </label>
                                        <input id="st-d-charge-type" type="text" class="form-control form-control-lg form-control-solid text-center" name="name" placeholder="CCS2" value="">
                                    </div>
                                </div>
                            </div>
                            <div class="mb-1 col-sm-12 text-end">
                                <div class="row mb-1">
                                    <div class="col-sm-6 text-end">
                                        <label class="fs-3 fw-semibold text-white mr-3">
                                            IMAGES
                                        </label>
                                        <div class="row">
                                            <div class="mb-1 col-6">
                                                <img src="./assets/images/imgpsh_fullsize_anim.jpg" class="h-xl-80px w-100 rounded-4 img-fluid" alt="">
                                            </div>
                                            <div class="mb-1 col-6">
                                                <img src="./assets/images/t.webp" class="h-xl-80px w-100 rounded-4 img-fluid" alt="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <label class="fs-3 fw-semibold text-white mr-3">
                                            DESCRIPTION
                                        </label>
                                        <textarea id="st-d-description" type="text" class="form-control form-control-lg form-control-solid text-end h-150px" name="name" placeholder="" value=""> עמדה ממוקמת סמוך למרכז המסחרי, בצמוד לסופר, מיקום סופר מרכזי, הגעה נוחה ובקרבת מקומות תעסוקה רבים. חנייה נקייה ומוצלת ברוב שעות היום.</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 text-end">
                                <form action="<?php echo baseUrl() ?>panel-page-actions.php" method="post">
                                    <div class="row mb-1 payment-options">
                                        <div id="rent-actions" class="col-6 mt-1">
                                            <a id="future-rent-action" href="#" class="btn btn-primary rounded-pill fs-1 fw-500 w-md-auto w-225px mb-2">FUTURE RENT</a>
                                            <input type="hidden" name="car">
                                            <input type="hidden" name="station">
                                            <input type="hidden" name="action" value="rent-car">
                                            <button type="submit" id="rent-now-action" class="btn btn-primary rounded-pill fs-1 fw-500 w-md-auto w-225px">RENT NOW</button>
                                        </div>
                                        <div class="col-6">
                                            <label class="fs-3 fw-semibold text-white">
                                                CHARGING PRICE
                                            </label>
                                            <span  class="tooltip-pro">
                                                <span class="tooltip-info-icon">&#8505;</span>
                                                <span class="tooltiptext">Charging Price</span>
                                            </span>
                                            <input id="st-d-charge-price" type="text" class="form-control form-control-lg form-control-solid text-end fs-1 py-1 h-55px" name="charging_price" placeholder="1.3" value="">
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn-script.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
        var map, bounds, selectedCar;
        const cars = JSON.parse('<?php echo json_encode($cars) ?>');
        const stations = JSON.parse('<?php echo json_encode($stations) ?>');
        const systemValues = JSON.parse('<?php echo json_encode($system_values) ?>');

        $('#selectCar').change((event) => {
            const carId = event.target.value;
            if(cars && carId){
                selectedCar = cars.find(({id})=> id == carId);

                $('.payment-options').find('input[name=car]').val(carId);
            }

            toggleStationDetails();
        });

        function initMap() {
            bounds = new google.maps.LatLngBounds();
            map = new google.maps.Map(document.getElementById('map'), {
                center: {lat: 32.087925, lng: 34.756047},
                zoom: 9,
                mapTypeControl: false,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            });

            var marker = new google.maps.Marker({
                map: map,
            });

            setupAddressAutoComplete();
            populateStations();
            getUserLocation();
        }
        
        async function getUserLocation() {
            const {PinElement, AdvancedMarkerElement} = await google.maps.importLibrary("marker");

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition((pos) => {
                    const coordinates = {lat: pos.coords.latitude, lng: pos.coords.longitude};
                    const marker = new google.maps.Marker({
                        position: coordinates,
                        map: map,
                        icon: {
                            url: "http://maps.google.com/mapfiles/ms/icons/blue-dot.png",
                        }
                    });

                    map.setCenter(new google.maps.LatLng(coordinates.lat, coordinates.lng));
                    map.setZoom(15);
                });
            } else {
                console.log("Geolocation is not supported by this browser.");
            }
        }

        function setupAddressAutoComplete() {
            var inputArea = document.getElementById('searchInputArea');
            map.controls[google.maps.ControlPosition.TOP_LEFT].push(inputArea);

            var input = document.getElementById('searchInput');
            var autocomplete = new google.maps.places.Autocomplete(input);

            autocomplete.bindTo('bounds', map);

            autocomplete.addListener('place_changed', () => {
                var place = autocomplete.getPlace();

                if (!place.geometry) {
                    window.alert("Autocomplete's returned place contains no geometry");
                    return;
                }

                // If the place has a geometry, then present it on a map.
                if (place.geometry.viewport) {
                    map.fitBounds(place.geometry.viewport);
                } else {
                    map.setCenter(place.geometry.location);
                    map.setZoom(17);
                }

                var address = '';
                if (place.address_components) {
                    address = [
                        (place.address_components[0] && place.address_components[0].short_name || ''),
                        (place.address_components[1] && place.address_components[1].short_name || ''),
                        (place.address_components[2] && place.address_components[2].short_name || '')
                    ].join(' ');
                }
            });
        }

        function populateStations() {
            const chargeStations = JSON.parse('<?php echo json_encode($stations) ?>');

            if(chargeStations && chargeStations.length){
                chargeStations.forEach(createStationMarker);
                map.fitBounds(bounds);
            }
        }

        function createStationMarker(station) {
            const {station_name, address, latitude, longitude, is_available} = station;

            const stationMarker = `http://maps.google.com/mapfiles/ms/icons/${is_available == 1 ? 'green':'red'}-dot.png`;
            var marker = new google.maps.Marker({
                position: {lat: parseFloat(latitude), lng: parseFloat(longitude)},
                map: map,
                icon: {
                    url: stationMarker,
                }
            });

            google.maps.event.addListener(marker, 'click', (function (marker) {
                return function () {
                    map.panTo(marker.getPosition());
                    toggleStationDetails(station);
                }
            })(marker));

            bounds.extend(marker.position);
        }

        function toggleStationDetails(details) {
            if(details){
                //station-details
                updateStationDetails(details);

                $('#map-container').attr('class', 'col-xl-8');
                $('#station-details').removeClass('d-none');
            } else {
                $('#map-container').attr('class', 'col-xl-12');
                $('#station-details').addClass('d-none');
            }
        }

        function updateStationDetails(details) {
            const {id, station_name, city, address, charging_type, charging_capacity, how_to_find, asking_price, algo_price, is_available, using_algorithm} = details;

            $('.payment-options').find('input[name=station]').val(id);
            $('#st-d-station').val(station_name);
            $('#st-d-avilable-until').text('17:00');
            $('#st-d-city').val(city);
            $('#st-d-address').val(address);
            $('#st-d-charge-capacity').val(charging_capacity);
            $('#st-d-charge-type').val(charging_type);
            $('#st-d-description').val(how_to_find);
            $('#st-d-charge-price').val(calculateChargePrice(details));

            if(!isCarStationCompatible(details)){
                $('#rent-actions').hide();
            } else {
                $('#rent-actions').show();
            }

            if(using_algorithm == 1){
                $('.payment-options .tooltip-pro').show();
            } else {
                $('.payment-options .tooltip-pro').hide();
            }

            if(is_available == 1){
                $('#st-d-available-status').text('Available');
                $('#st-d-status-color').removeClass('bg-danger');
                $('#st-d-status-color').addClass('bg-success');
            } else {
                $('#st-d-available-status').text('Unavailable');
                $('#st-d-status-color').removeClass('bg-success');
                $('#st-d-status-color').addClass('bg-danger');
                $('#rent-actions').hide();
            }
        }

        function isCarStationCompatible(station) {
            if(!selectedCar || !station) return false;

            const carChargeType = selectedCar?.charging_type;
            const stationChargeTypes = station?.charging_type ? station?.charging_type.split(',') :[];

            return stationChargeTypes.some(type => {
                return type.trim() == carChargeType;
            });
        }

        function baseUrl() {
            return window.location.href.split('/').slice(0, -1).join('/');
        }

        function calculateChargePrice(station) {
            const {using_algorithm, asking_price, algo_price, charging_capacity} = station;
            const {demand_hour_price, medium_demand_price, high_demand_price} = systemValues;

            const DEMAND_HOURS_START = 17; // 17:00 or 5 PM
            const DEMAND_HOURS_END = 22;   // 22:00 or 10 PM

            if(using_algorithm == 1){
                const reservedStations = stations.reduce((c, {is_available}) =>  c + (is_available == 1 ? 0: 1), 0);
                const availableStations = stations.reduce((c, {is_available}) =>  c + (is_available == 1 ? 1: 0), 0);

                if (availableStations === 0) return algo_price;

                const demand = reservedStations / availableStations;

                const currentHour = new Date().getHours();
                const isDemandHours = (currentHour >= DEMAND_HOURS_START && currentHour <= DEMAND_HOURS_END);

                if (demand >= 0 && demand <= 20) {
                    // Low demand location
                    if (isDemandHours) {
                        return demand_hour_price + algo_price;
                    } else {
                        return algo_price;
                    }
                } else if (demand > 20 && demand <= 50) {
                    // Medium demand location
                    if (isDemandHours) {
                        return medium_demand_price + demand_hour_price + algo_price;
                    } else {
                        console.log('50%');
                        return medium_demand_price + algo_price;
                    }
                } else if (demand > 50 && demand <= 100) {
                    // High demand location
                    if (isDemandHours) {
                        return high_demand_price + demand_hour_price + algo_price;
                    } else {
                        return high_demand_price + algo_price;
                    }
                } else {
                    return algo_price;
                }
            } else {
                return asking_price;
            }
        }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCst-5X8nOQwqiboEiq0xzZs7DMCMSFkYs&libraries=places&callback=initMap" async defer></script>
</body>
</html> 
<?php 
    session_start();
    include_once('db_details.php');

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    /** Fetching all system values */
    $query = "SELECT * FROM system_values";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $sys_result = $stmt->get_result();

    $system_values = [];
    while ($row = $sys_result->fetch_assoc()) {
        $system_values[$row['name']] = $row['value'];
    }

    /** Fetching all stations */
    $sql = "SELECT * FROM station_models";
    $result = $conn->query($sql);

    $station_models = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $station_models[] = $row;
        }
    }
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
    <link rel="stylesheet" href="./assets/css/jquery.multi-select.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="./assets/js/jquery.multi-select.min.js"></script>
    
</head>
<body>
    <!-- ====================== Header===================== -->
    <div class="main-vid add-new-charging-station-sec">
        <video autoplay loop muted style="transform: rotatex(180deg);">
            <source src="./assets/images/wave.mp4" type="video/mp4">
        </video>
        <div class="container h-100 z-index-3 position-relative">
            <div class="registor justify-content-start align-items-start py-12 px-lg-0 px-sm-10">
                <h2>Add New Charging station</h2>
                <form id="add-station-form" action="add_station.php" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-xl-7">
                            <div class="row">
                                <div class="col-md-6 mb-5">
                                    <label class="d-flex align-items-center fs-5 fw-semibold mb-2 text-white">Station Name</label>
                                    <input type="text" class="form-control form-control-lg form-control-solid" name="station_name" placeholder="" required>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-6 mb-5">
                                            <label class="d-flex align-items-center fs-5 fw-semibold mb-2 text-white">Station Model</label>
                                            <div class="w-100 position-relative">
                                                <div class="si-border"></div>
                                                <select class="form-select h-55px form-select-solid ps-13" name="station_model" required>
                                                    <option value="">--select--</option>
                                                    <?php
                                                        if($station_models){
                                                            foreach($station_models as $model){
                                                                echo '<option value="'.$model['id'].'">'.$model['name'].'</option>';
                                                            }
                                                        }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-5">
                                            <label class="d-flex align-items-center fs-5 fw-semibold mb-2 text-white">Year Model</label>
                                            <div class="w-100 position-relative">
                                                <div class="si-border"></div>
                                                <select class="form-select h-55px form-select-solid ps-13" name="station_year" required>
                                                    <option value="">--select--</option>
                                                    <option value="2023">2024</option>
                                                    <option value="2023">2023</option>
                                                    <option value="2020">2020</option>
                                                    <option value="2019">2019</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-5">
                                    <label class="d-flex align-items-center fs-5 fw-semibold mb-2 text-white">Address</label>
                                    <input id="searchInput" type="text" class="form-control form-control-lg form-control-solid" name="address" placeholder="" required>
                                </div>
                                <div class="col-md-6 mb-5">
                                    <label class="d-flex align-items-center fs-5 fw-semibold mb-2 text-white">Charging Type</label>
                                    <div class="w-100 position-relative dropdown-control">
                                        <div class="si-border"></div>
                                        <select multiple id="chargin-types" class="multi-select-container h-55px form-select-solid ps-13" name="charging_type[]" required>
                                            <option value="">--select--</option>
                                            <option value="TESLA 1">TESLA 1</option>
                                            <option value="TESLA 2">TESLA 2</option>
                                            <option value="TESLA 3">TESLA 3</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-5">
                                    <label class="d-flex align-items-center fs-5 fw-semibold mb-2 text-white">City</label>
                                    <div class="w-100 position-relative">
                                        <div class="si-border"></div>
                                        <select class="form-select h-55px form-select-solid ps-13" name="city" required>
                                            <option value="">--select--</option>
                                            <option value="1">Afula</option>
                                            <option value="2">Akko</option>
                                            <option value="3">Arad</option>
                                            <option value="4">Arraba</option>
                                            <option value="5">Ashdod</option>
                                            <option value="6">Ashkelon</option>
                                            <option value="7">Baqa al-Gharbiyye</option>
                                            <option value="8">Bat Yam</option>
                                            <option value="9">Beersheba</option>
                                            <option value="10">Beit She'an</option>
                                            <option value="11">Beit Shemesh</option>
                                            <option value="12">Beitar Illit</option>
                                            <option value="13">Bnei Brak</option>
                                            <option value="14">Dimona</option>
                                            <option value="15">Eilat</option>
                                            <option value="16">Elad</option>
                                            <option value="17">Givatayim</option>
                                            <option value="18">Giv'at Shmuel</option>
                                            <option value="19">Hadera</option>
                                            <option value="20">Haifa</option>
                                            <option value="21">Herzliya</option>
                                            <option value="22">Hod HaSharon</option>
                                            <option value="23">Holon</option>
                                            <option value="24">Jaffa</option>
                                            <option value="25">Jerusalem</option>
                                            <option value="26">Karmiel</option>
                                            <option value="27">Kfar Saba</option>
                                            <option value="28">Kiryat Ata</option>
                                            <option value="29">Kiryat Bialik</option>
                                            <option value="30">Kiryat Gat</option>
                                            <option value="31">Kiryat Malakhi</option>
                                            <option value="32">Kiryat Motzkin</option>
                                            <option value="33">Kiryat Ono</option>
                                            <option value="34">Kiryat Shmona</option>
                                            <option value="35">Kiryat Yam</option>
                                            <option value="36">Lod</option>
                                            <option value="37">Ma'ale Adumim</option>
                                            <option value="38">Ma'alot-Tarshiha</option>
                                            <option value="39">Migdal HaEmek</option>
                                            <option value="40">Modi'in Illit</option>
                                            <option value="41">Modi'in-Maccabim-Re'ut</option>
                                            <option value="42">Nahariya</option>
                                            <option value="43">Nazareth</option>
                                            <option value="44">Nazareth Illit (Nof HaGalil)</option>
                                            <option value="45">Netanya</option>
                                            <option value="46">Netivot</option>
                                            <option value="47">Ness Ziona</option>
                                            <option value="48">Nof HaGalil</option>
                                            <option value="49">Ofakim</option>
                                            <option value="50">Or Akiva</option>
                                            <option value="51">Or Yehuda</option>
                                            <option value="52">Petah Tikva</option>
                                            <option value="53">Qalansawe</option>
                                            <option value="54">Rahat</option>
                                            <option value="55">Ramat Gan</option>
                                            <option value="56">Ramat HaSharon</option>
                                            <option value="57">Ramla</option>
                                            <option value="58">Rehovot</option>
                                            <option value="59">Rishon LeZion</option>
                                            <option value="60">Rosh HaAyin</option>
                                            <option value="61">Sakhnin</option>
                                            <option value="62">Sderot</option>
                                            <option value="63">Shefa-Amr</option>
                                            <option value="64">Tamra</option>
                                            <option value="65">Tayibe</option>
                                            <option value="66">Tel Aviv</option>
                                            <option value="67">Tiberias</option>
                                            <option value="68">Tira</option>
                                            <option value="69">Tirat Carmel</option>
                                            <option value="70">Umm al-Fahm</option>
                                            <option value="71">Yavne</option>
                                            <option value="72">Yehud-Monosson</option>
                                            <option value="73">Yokneam Illit</option>
                                            <option value="74">Zefat (Safed)</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-5">
                                    <label class="d-flex align-items-center fs-5 fw-semibold mb-2 text-white">Charging Capacity [kwh]</label>
                                    <input type="number" class="form-control form-control-lg form-control-solid" name="charging_capacity" placeholder="" value="0" maxlength="5" required>
                                </div>
                                <div class="col-md-6 mb-5">
                                    <label class="fs-3 fw-semibold text-white mr-3">How To Find</label>
                                    <textarea class="form-control form-control-lg form-control-solid text-end h-150px" name="how_to_find" placeholder="" required></textarea>
                                </div>
                                <div class="col-md-6 mb-5">
                                    <div class="row">
                                        <div class="col-md-6 mb-5">
                                            <label class="fs-3 fw-semibold text-white mr-3">Photos</label>
                                            <div class="row">
                                                <div class="mb-1 col-6">
                                                    <input type="file" name="image1" class="form-control form-control-lg form-control-solid" required>
                                                </div>
                                                <div class="mb-1 col-6">
                                                    <input type="file" name="image2" class="form-control form-control-lg form-control-solid" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-5">
                                    <label class="d-flex align-items-center fs-5 fw-semibold mb-2 text-white">
                                        Asking Price for kWh [NIS]
                                        <span class="tooltip-pro">
                                            <span class="tooltip-info-icon">&#8505;</span>
                                            <span class="tooltiptext">ASKING PRICE FOR KWH</span>
                                        </span>
                                    </label>
                                    <input readonly type="number" class="form-control form-control-lg form-control-solid fs-1 py-1 h-55px" name="asking_price" value="0" placeholder="" required>
                                </div>
                                <div class="col-md-6 mb-5">
                                    <label class="d-flex align-items-center fs-5 fw-semibold mb-2 text-white">
                                        ALGORITHM Price
                                        <span class="tooltip-pro">
                                            <span class="tooltip-info-icon">&#8505;</span>
                                            <span class="tooltiptext">ALGORITHM Price</span>
                                        </span></label>
                                    <input readonly type="number" class="form-control form-control-lg form-control-solid fs-1 py-1 h-55px" name="algo_price" value="0" placeholder="" required>
                                    <div>
                                        <label class="mt-2 text-white">Use the suggested price <input name="using_algorithm" type="checkbox" checked/> </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-5">
                            <div id="map-canvas" style="height: 300px;width:100%"></div>
                            <input type="hidden" name="latitude" id="latitude" required>
                            <input type="hidden" name="longitude" id="longitude" required>
                            <div class="d-flex justify-content-end mt-9">
                                <button type="submit" class="btn btn-primary fs-2qx pt-1 pb-0 fw-bold rounded-pill text-Jaturat">Add Station</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCst-5X8nOQwqiboEiq0xzZs7DMCMSFkYs&libraries=places&callback=initMap" async defer></script>
    <script>
        var map;
        const defaultLocation = {lat: 32.087925, lng: 34.756047};
        const systemValues = JSON.parse('<?php echo json_encode($system_values) ?>');

        $('document').ready(() => {
            $('#chargin-types').multiSelect();
        });

        $("input[name='charging_capacity']").keyup( function() {
            const algoPrice = calcAlgoPrice($(this).val());

            $("input[name='algo_price']").val(algoPrice);
            if($("input[name='using_algorithm']").is(':checked')){
                $("input[name='asking_price']").val(algoPrice);
            }
        });

        $("input[name='using_algorithm']").change( function() {
            if($(this).is(':checked')){
                $("input[name='asking_price']").val($("input[name='algo_price']").val());
                $("input[name='asking_price']").prop('readonly', true);
            } else {
                $("input[name='asking_price']").prop('readonly', false);
            }
        });

        function calcAlgoPrice(chargeCapacity){
            if(!chargeCapacity || chargeCapacity.length == 0 || chargeCapacity == 0) return 0;

            return (systemValues['profit_fee'] + systemValues['electricity_price']) * parseFloat(chargeCapacity);
        }

        function initMap() {
            map = new google.maps.Map(document.getElementById('map-canvas'), {
                center: defaultLocation,
                zoom: 9,
                mapTypeControl: false,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            });

            setAutoComplete();
        }

        function setMarker(coordinates) {
            var marker = new google.maps.Marker({
                position: coordinates,
                map: map,
                draggable: true
            });

            map.setZoom(17);

            google.maps.event.addListener(marker, 'dragend', function(evt) {
                const coordinates = {lat: evt.latLng.lat(), lng: evt.latLng.lng()};

                setCoordinateFields(coordinates);
                geocodePosition(coordinates);
            });
        }

        function setAutoComplete() {
            var input = document.getElementById('searchInput');
            var autocomplete = new google.maps.places.Autocomplete(input);

            autocomplete.addListener('place_changed', function() {
                var place = autocomplete.getPlace();

                if (!place.geometry) {
                    console.log("Autocomplete's returned place contains no geometry");
                    return;
                }

                const coordinates = place.geometry.location;

                map.setCenter(coordinates);
                setMarker(coordinates);
                setCoordinateFields({lat: coordinates.lat(), lng: coordinates.lng()});
            });
        }

        function setCoordinateFields(coordinates) {
            document.getElementById('latitude').value = coordinates.lat;
            document.getElementById('longitude').value = coordinates.lng;
        }

        function geocodePosition(pos) {
            var geocoder = new google.maps.Geocoder();
            geocoder.geocode({
                latLng: pos
            }, function(responses) {
                if (responses && responses.length > 0) {
                    $('#searchInput').val(get_geocode_formatted_address(responses));
                }
            });
        }

        function get_geocode_formatted_address(results){
            var geocode_address_segments = get_geocode_address_segments(results);

            var street = geocode_address_segments['street'];
            var region = geocode_address_segments['region'];
            var country = geocode_address_segments['country'];
            var formatted_address = street + " " + region + ", " + country;

            return formatted_address;
        }

        function get_geocode_address_segments(results) {

            var street_number = '';
            var route = '';
            var region = '';
            var country = '';

            for (var j=0; j<results.length; j++)
            {
                for (var i=0; i<results[j].address_components.length; i++)
                {
                    if(!street_number){
                        if (results[j].address_components[i].types[0] == "street_number") {
                            street_number = results[j].address_components[i].long_name+' ';
                        }
                    }
                    if(!route){
                        if (results[j].address_components[i].types[0] == "route") {
                            route += results[j].address_components[i].long_name;
                        }
                    }
                    if(!region){
                        if (results[j].address_components[i].types[0] == "administrative_area_level_1") {
                            region = results[j].address_components[i].long_name;
                        }
                    }
                    if(!country){
                        if (results[j].address_components[i].types[0] == "country") {
                            country = results[j].address_components[i].long_name;
                        }
                    }

                    if(route && region && country){
                        break;
                    }
                }
            }

            return {
                'street':street_number+route,
                'region':region,
                'country':country
            };
        }
    </script>
</body>
</html>

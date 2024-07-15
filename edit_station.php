<?php
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

// Check if station ID is provided in the URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['error'] = "Invalid station ID.";
    header("Location: station_management.php");
    exit();
}

$station_id = $_GET['id'];

// Fetch station details from database
$query = "SELECT * FROM station WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $station_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $_SESSION['error'] = "Station not found.";
    header("Location: stations.php");
    exit();
}

$station = $result->fetch_assoc();

// Handle form submission for updating station details
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // echo '<pre>';print_r($_POST);
    // exit;

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
    $image1_name = $station['image1']; // Retain existing image name by default
    $image2_name = $station['image2']; // Retain existing image name by default

    // Check if image1 is uploaded
    if ($_FILES["image1"]["error"] === UPLOAD_ERR_OK) {
        $image1_name = uniqid('img1_') . '_' . basename($_FILES["image1"]["name"]);
        $image1_path = $upload_dir . $image1_name;
        move_uploaded_file($_FILES["image1"]["tmp_name"], $image1_path);
    }

    // Check if image2 is uploaded
    if ($_FILES["image2"]["error"] === UPLOAD_ERR_OK) {
        $image2_name = uniqid('img2_') . '_' . basename($_FILES["image2"]["name"]);
        $image2_path = $upload_dir . $image2_name;
        move_uploaded_file($_FILES["image2"]["tmp_name"], $image2_path);
    }

    // Update station details in database
    $query_update = "UPDATE station SET 
                     station_name = ?, 
                     station_model = ?, 
                     station_year = ?, 
                     address = ?, 
                     charging_type = ?, 
                     city = ?, 
                     charging_capacity = ?, 
                     how_to_find = ?, 
                     asking_price = ?, 
                     algo_price = ?, 
                     latitude = ?, 
                     longitude = ?,
                     image1 = ?,
                     image2 = ?,
                     using_algorithm = ?
                     WHERE id = ?";
                     
    $stmt_update = $conn->prepare($query_update);
    $stmt_update->bind_param("sissssisddsssssi", 
                             $station_name, 
                             $station_model, 
                             $station_year, 
                             $address, 
                             $charging_type, 
                             $city, 
                             $charging_capacity, 
                             $how_to_find, 
                             $asking_price, 
                             $algo_price, 
                             $latitude, 
                             $longitude, 
                             $image1_name,
                             $image2_name,
                             $using_algorithm,
                             $station_id);

    if ($stmt_update->execute()) {
        $_SESSION['success'] = "Station updated successfully!";
        header("Location: station-management.php");
        exit();
    } else {
        echo 'errro';die;
        $_SESSION['error'] = "Failed to update station.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Station</title>
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
                <h2>Edit Charging station</h2>
                <form id="edit-station-form" action="edit_station.php?id=<?php echo htmlspecialchars($station_id); ?>" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-xl-7">
                            <div class="row">
                                <div class="col-md-6 mb-5">
                                    <label class="d-flex align-items-center fs-5 fw-semibold mb-2 text-white">Station Name</label>
                                    <input type="text" class="form-control form-control-lg form-control-solid" name="station_name" value="<?php echo htmlspecialchars($station['station_name']); ?>" required>
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
                                                        $selected_models = explode(',', $station_models);
                                                        if($station['station_model']){
                                                            foreach($station_models as $model){
                                                                $selected = $station['station_model'] == $model['id'] ? 'selected' : '';
                                                                echo '<option value="'.$model['id'].'" '.$selected.'>'.$model['name'].'</option>';
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
                                                    <option value="2024" <?php echo $station['station_year'] == '2024' ? 'selected' : ''; ?>>2024</option>
                                                    <option value="2023" <?php echo $station['station_year'] == '2023' ? 'selected' : ''; ?>>2023</option>
                                                    <option value="2020" <?php echo $station['station_year'] == '2020' ? 'selected' : ''; ?>>2020</option>
                                                    <option value="2019" <?php echo $station['station_year'] == '2019' ? 'selected' : ''; ?>>2019</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-5">
                                    <label class="d-flex align-items-center fs-5 fw-semibold mb-2 text-white">Address</label>
                                    <input id="searchInput" type="text" class="form-control form-control-lg form-control-solid" name="address" value="<?php echo htmlspecialchars($station['address']); ?>" required>
                                </div>
                                <div class="col-md-6 mb-5">
                                    <label class="d-flex align-items-center fs-5 fw-semibold mb-2 text-white">Charging Type</label>
                                    <div class="w-100 position-relative dropdown-control">
                                        <div class="si-border"></div>
                                        <?php $slected_charge_types = explode(',', $station['charging_type']); ?>
                                        <select multiple id="chargin-types" class="form-select h-55px form-select-solid ps-13" name="charging_type[]" required>
                                            <option value="">--select--</option>
                                            <option value="TESLA 1" <?php echo in_array("TESLA 1", $slected_charge_types) ? 'selected':''; ?>>TESLA 1</option>
                                            <option value="TESLA 2" <?php echo in_array("TESLA 2", $slected_charge_types) ? 'selected':''; ?>>TESLA 2</option>
                                            <option value="TESLA 3" <?php echo in_array("TESLA 3", $slected_charge_types) ? 'selected':''; ?>>TESLA 3</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-5">
                                    <label class="d-flex align-items-center fs-5 fw-semibold mb-2 text-white">City</label>
                                    <div class="w-100 position-relative">
                                        <div class="si-border"></div>
                                        <select class="form-select h-55px form-select-solid ps-13" name="city" required>
                                        <option value="">--select--</option>
                                        <option value="1" <?php echo $station['city'] == '1' ? 'selected' : ''; ?>>Afula</option>
                                        <option value="2" <?php echo $station['city'] == '2' ? 'selected' : ''; ?>>Akko</option>
                                        <option value="3" <?php echo $station['city'] == '3' ? 'selected' : ''; ?>>Arad</option>
                                        <option value="4" <?php echo $station['city'] == '4' ? 'selected' : ''; ?>>Arraba</option>
                                        <option value="5" <?php echo $station['city'] == '5' ? 'selected' : ''; ?>>Ashdod</option>
                                        <option value="6" <?php echo $station['city'] == '6' ? 'selected' : ''; ?>>Ashkelon</option>
                                        <option value="7" <?php echo $station['city'] == '7' ? 'selected' : ''; ?>>Baqa al-Gharbiyye</option>
                                        <option value="8" <?php echo $station['city'] == '8' ? 'selected' : ''; ?>>Bat Yam</option>
                                        <option value="9" <?php echo $station['city'] == '9' ? 'selected' : ''; ?>>Beersheba</option>
                                        <option value="10" <?php echo $station['city'] == '10' ? 'selected' : ''; ?>>Beit She'an</option>
                                        <option value="11" <?php echo $station['city'] == '11' ? 'selected' : ''; ?>>Beit Shemesh</option>
                                        <option value="12" <?php echo $station['city'] == '12' ? 'selected' : ''; ?>>Beitar Illit</option>
                                        <option value="13" <?php echo $station['city'] == '13' ? 'selected' : ''; ?>>Bnei Brak</option>
                                        <option value="14" <?php echo $station['city'] == '14' ? 'selected' : ''; ?>>Dimona</option>
                                        <option value="15" <?php echo $station['city'] == '15' ? 'selected' : ''; ?>>Eilat</option>
                                        <option value="16" <?php echo $station['city'] == '16' ? 'selected' : ''; ?>>Elad</option>
                                        <option value="17" <?php echo $station['city'] == '17' ? 'selected' : ''; ?>>Givatayim</option>
                                        <option value="18" <?php echo $station['city'] == '18' ? 'selected' : ''; ?>>Giv'at Shmuel</option>
                                        <option value="19" <?php echo $station['city'] == '19' ? 'selected' : ''; ?>>Hadera</option>
                                        <option value="20" <?php echo $station['city'] == '20' ? 'selected' : ''; ?>>Haifa</option>
                                        <option value="21" <?php echo $station['city'] == '21' ? 'selected' : ''; ?>>Herzliya</option>
                                        <option value="22" <?php echo $station['city'] == '22' ? 'selected' : ''; ?>>Hod HaSharon</option>
                                        <option value="23" <?php echo $station['city'] == '23' ? 'selected' : ''; ?>>Holon</option>
                                        <option value="24" <?php echo $station['city'] == '24' ? 'selected' : ''; ?>>Jaffa</option>
                                        <option value="25" <?php echo $station['city'] == '25' ? 'selected' : ''; ?>>Jerusalem</option>
                                        <option value="26" <?php echo $station['city'] == '26' ? 'selected' : ''; ?>>Karmiel</option>
                                        <option value="27" <?php echo $station['city'] == '27' ? 'selected' : ''; ?>>Kfar Saba</option>
                                        <option value="28" <?php echo $station['city'] == '28' ? 'selected' : ''; ?>>Kiryat Ata</option>
                                        <option value="29" <?php echo $station['city'] == '29' ? 'selected' : ''; ?>>Kiryat Bialik</option>
                                        <option value="30" <?php echo $station['city'] == '30' ? 'selected' : ''; ?>>Kiryat Gat</option>
                                        <option value="31" <?php echo $station['city'] == '31' ? 'selected' : ''; ?>>Kiryat Malakhi</option>
                                        <option value="32" <?php echo $station['city'] == '32' ? 'selected' : ''; ?>>Kiryat Motzkin</option>
                                        <option value="33" <?php echo $station['city'] == '33' ? 'selected' : ''; ?>>Kiryat Ono</option>
                                        <option value="34" <?php echo $station['city'] == '34' ? 'selected' : ''; ?>>Kiryat Shmona</option>
                                        <option value="35" <?php echo $station['city'] == '35' ? 'selected' : ''; ?>>Kiryat Yam</option>
                                        <option value="36" <?php echo $station['city'] == '36' ? 'selected' : ''; ?>>Lod</option>
                                        <option value="37" <?php echo $station['city'] == '37' ? 'selected' : ''; ?>>Ma'ale Adumim</option>
                                        <option value="38" <?php echo $station['city'] == '38' ? 'selected' : ''; ?>>Ma'alot-Tarshiha</option>
                                        <option value="39" <?php echo $station['city'] == '39' ? 'selected' : ''; ?>>Migdal HaEmek</option>
                                        <option value="40" <?php echo $station['city'] == '40' ? 'selected' : ''; ?>>Modi'in Illit</option>
                                        <option value="41" <?php echo $station['city'] == '41' ? 'selected' : ''; ?>>Modi'in-Maccabim-Re'ut</option>
                                        <option value="42" <?php echo $station['city'] == '42' ? 'selected' : ''; ?>>Nahariya</option>
                                        <option value="43" <?php echo $station['city'] == '43' ? 'selected' : ''; ?>>Nazareth</option>
                                        <option value="44" <?php echo $station['city'] == '44' ? 'selected' : ''; ?>>Nazareth Illit (Nof HaGalil)</option>
                                        <option value="45" <?php echo $station['city'] == '45' ? 'selected' : ''; ?>>Netanya</option>
                                        <option value="46" <?php echo $station['city'] == '46' ? 'selected' : ''; ?>>Netivot</option>
                                        <option value="47" <?php echo $station['city'] == '47' ? 'selected' : ''; ?>>Ness Ziona</option>
                                        <option value="48" <?php echo $station['city'] == '48' ? 'selected' : ''; ?>>Nof HaGalil</option>
                                        <option value="49" <?php echo $station['city'] == '49' ? 'selected' : ''; ?>>Ofakim</option>
                                        <option value="50" <?php echo $station['city'] == '50' ? 'selected' : ''; ?>>Or Akiva</option>
                                        <option value="51" <?php echo $station['city'] == '51' ? 'selected' : ''; ?>>Or Yehuda</option>
                                        <option value="52" <?php echo $station['city'] == '52' ? 'selected' : ''; ?>>Petah Tikva</option>
                                        <option value="53" <?php echo $station['city'] == '53' ? 'selected' : ''; ?>>Qalansawe</option>
                                        <option value="54" <?php echo $station['city'] == '54' ? 'selected' : ''; ?>>Rahat</option>
                                        <option value="55" <?php echo $station['city'] == '55' ? 'selected' : ''; ?>>Ramat Gan</option>
                                        <option value="56" <?php echo $station['city'] == '56' ? 'selected' : ''; ?>>Ramat HaSharon</option>
                                        <option value="57" <?php echo $station['city'] == '57' ? 'selected' : ''; ?>>Ramla</option>
                                        <option value="58" <?php echo $station['city'] == '58' ? 'selected' : ''; ?>>Rehovot</option>
                                        <option value="59" <?php echo $station['city'] == '59' ? 'selected' : ''; ?>>Rishon LeZion</option>
                                        <option value="60" <?php echo $station['city'] == '60' ? 'selected' : ''; ?>>Rosh HaAyin</option>
                                        <option value="61" <?php echo $station['city'] == '61' ? 'selected' : ''; ?>>Sakhnin</option>
                                        <option value="62" <?php echo $station['city'] == '62' ? 'selected' : ''; ?>>Sderot</option>
                                        <option value="63" <?php echo $station['city'] == '63' ? 'selected' : ''; ?>>Shefa-'Amr</option>
                                        <option value="64" <?php echo $station['city'] == '64' ? 'selected' : ''; ?>>Tamra</option>
                                        <option value="65" <?php echo $station['city'] == '65' ? 'selected' : ''; ?>>Tayibe</option>
                                        <option value="66" <?php echo $station['city'] == '66' ? 'selected' : ''; ?>>Tel Aviv-Yafo</option>
                                        <option value="67" <?php echo $station['city'] == '67' ? 'selected' : ''; ?>>Tiberias</option>
                                        <option value="68" <?php echo $station['city'] == '68' ? 'selected' : ''; ?>>Tira</option>
                                        <option value="69" <?php echo $station['city'] == '69' ? 'selected' : ''; ?>>Tirat Carmel</option>
                                        <option value="70" <?php echo $station['city'] == '70' ? 'selected' : ''; ?>>Umm al-Fahm</option>
                                        <option value="71" <?php echo $station['city'] == '71' ? 'selected' : ''; ?>>Yavne</option>
                                        <option value="72" <?php echo $station['city'] == '72' ? 'selected' : ''; ?>>Yehud-Monosson</option>
                                        <option value="73" <?php echo $station['city'] == '73' ? 'selected' : ''; ?>>Yokneam Illit</option>
                                        <option value="74" <?php echo $station['city'] == '74' ? 'selected' : ''; ?>>Zikhron Ya'akov</option>

                                            <!-- List all cities with their respective values and check the current value with PHP to select -->
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-5">
                                    <label class="d-flex align-items-center fs-5 fw-semibold mb-2 text-white">Charging Capacity [kwh]</label>
                                    <input type="number" class="form-control form-control-lg form-control-solid" name="charging_capacity" value="<?php echo htmlspecialchars($station['charging_capacity']); ?>" required>
                                </div>
                                <div class="col-md-6 mb-5">
                                    <label class="fs-3 fw-semibold text-white mr-3">How To Find</label>
                                    <textarea class="form-control form-control-lg form-control-solid text-end h-150px" name="how_to_find" required><?php echo htmlspecialchars($station['how_to_find']); ?></textarea>
                                </div>

                                <div class="col-md-6 mb-5">
                                    <label class="fs-3 fw-semibold text-white mr-3">Photos</label>
                                    <div class="row">
                                        <div class="mb-1 col-6">
                                        <img src="<?php echo 'uploads/' . $station['image1']; ?>" class="rounded-4 img-fluid h-150px img-fit" alt="">
                                        </div>
                                        <div class="mb-1 col-6">
                                            <img src="<?php echo 'uploads/' . $station['image2']; ?>" class="rounded-4 img-fluid h-150px img-fit" alt="">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-5">
                                    <label class="fs-3 fw-semibold text-white mr-3"></label>
                                    <textarea style="display:none;" class="form-control form-control-lg form-control-solid text-end h-150px"></textarea>
                                </div>
                                <div class="col-md-6 mb-5">
                                    <div class="row">
                                        <!-- Other input fields... -->
                                        <div class="col-md-6 mb-5">
                                            <label class="fs-3 fw-semibold text-white mr-3">Photos</label>
                                            <div class="row">
                                                <div class="mb-1 col-6">
                                                    <input type="file" name="image1" class="form-control form-control-lg form-control-solid" >
                                                </div>
                                                <div class="mb-1 col-6">
                                                    <input type="file" name="image2" class="form-control form-control-lg form-control-solid" >
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Other input fields... -->
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
                                    <input type="number" class="form-control form-control-lg form-control-solid fs-1 py-1 h-55px" name="asking_price" value="<?php echo htmlspecialchars(number_format((float)$station['asking_price'], 2, '.', '')); ?>" required>
                                </div>
                                <div class="col-md-6 mb-5">
                                    <label class="d-flex align-items-center fs-5 fw-semibold mb-2 text-white">ALGORITHM Price
                                        <span class="tooltip-pro">
                                            <span class="tooltip-info-icon">&#8505;</span>
                                            <span class="tooltiptext">ALGORITHM Price</span>
                                        </span></label>
                                    <input readonly type="number" class="form-control form-control-lg form-control-solid fs-1 py-1 h-55px" name="algo_price" value="<?php echo htmlspecialchars($station['algo_price']); ?>" required>
                                    <div>
                                        <label class="mt-2 text-white">Use the suggested price <input name="using_algorithm" type="checkbox" /> </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-5">
                            <div id="map-canvas" style="height: 300px;width:100%"></div>
                            <input type="hidden" name="latitude" id="latitude" value="<?php echo htmlspecialchars($station['latitude']); ?>" required>
                            <input type="hidden" name="longitude" id="longitude" value="<?php echo htmlspecialchars($station['longitude']); ?>" required>
                            <div class="d-flex justify-content-end mt-9">
                                <button type="submit" class="btn btn-primary fs-2qx pt-1 pb-0 fw-bold rounded-pill text-Jaturat">Update Station</button>
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
        const defaultLocation = {lat: <?php echo $station['latitude'] ?>, lng: <?php echo $station['longitude'] ?>};
        const systemValues = JSON.parse('<?php echo json_encode($system_values) ?>');
        const usingAlgorithm = '<?php echo $station['using_algorithm'] ?>';
        console.log('usingAlgorithm', usingAlgorithm);

        $('document').ready(() => {
            $('#chargin-types').multiSelect();

            setUseAlgoFields();
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

        function setUseAlgoFields() {
            const fieldStatus = usingAlgorithm == '1' ? true:false;

            $("input[name='using_algorithm']").attr('checked', fieldStatus);
            $("input[name='algo_price']").prop('readonly', fieldStatus);
        }

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

            setMarker(defaultLocation);
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


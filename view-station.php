<?php
include_once('db_details.php');

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
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
    $station_name = $_POST['station_name'];
    $station_model = $_POST['station_model'];
    $station_year = $_POST['station_year'];
    $address = $_POST['address'];
    $charging_type = $_POST['charging_type'];
    $city = $_POST['city'];
    $charging_capacity = $_POST['charging_capacity'];
    $how_to_find = $_POST['how_to_find'];
    $asking_price = $_POST['asking_price'];
    $algo_price = $_POST['algo_price'];
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
                     image2 = ?
                     WHERE id = ?";
                     
    $stmt_update = $conn->prepare($query_update);
    $stmt_update->bind_param("sssssssisddssss", 
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
                             $station_id);

    if ($stmt_update->execute()) {
        $_SESSION['success'] = "Station updated successfully!";
        header("Location: station-management.php");
        exit();
    } else {
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
    <title>View Station</title>
    <link rel="shortcut icon" href="assets/images/favicon.png" type="image/png">
    <link rel="stylesheet" href="./assets/css/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <!-- ====================== Header===================== -->
    <div class="main-vid add-new-charging-station-sec">
        <video autoplay loop muted style="transform: rotatex(180deg);">
            <source src="./assets/images/wave.mp4" type="video/mp4">
        </video>
        <div class="container h-100 z-index-3 position-relative">
            <div class="registor justify-content-start align-items-start py-12 px-lg-0 px-sm-10">
                <h2>View Charging station</h2>
                <form id="edit-station-form" action="edit_station.php?id=<?php echo htmlspecialchars($station_id); ?>" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-xl-7">
                            <div class="row">
                                <div class="col-md-6 mb-5">
                                    <label class="d-flex align-items-center fs-5 fw-semibold mb-2 text-white">Station Name</label>
                                    <input readonly type="text" class="form-control form-control-lg form-control-solid" name="station_name" value="<?php echo htmlspecialchars($station['station_name']); ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-6 mb-5">
                                            <label class="d-flex align-items-center fs-5 fw-semibold mb-2 text-white">Station Model</label>
                                            <div class="w-100 position-relative">
                                                <div class="si-border"></div>
                                                <select disabled class="form-select h-55px form-select-solid ps-13" name="station_model" required>
                                                    <option value="">--select--</option>
                                                    <option value="Gen 3" <?php echo $station['station_model'] == 'Gen 3' ? 'selected' : ''; ?>>Gen 3</option>
                                                    <option value="Gen 2" <?php echo $station['station_model'] == 'Gen 2' ? 'selected' : ''; ?>>Gen 2</option>
                                                    <option value="Gen 1" <?php echo $station['station_model'] == 'Gen 1' ? 'selected' : ''; ?>>Gen 1</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-5">
                                            <label class="d-flex align-items-center fs-5 fw-semibold mb-2 text-white">Year Model</label>
                                            <div class="w-100 position-relative">
                                                <div class="si-border"></div>
                                                <select disabled class="form-select h-55px form-select-solid ps-13" name="station_year" required>
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
                                    <input readonly type="text" class="form-control form-control-lg form-control-solid" name="address" value="<?php echo htmlspecialchars($station['address']); ?>" required>
                                </div>
                                <div class="col-md-6 mb-5">
                                    <label class="d-flex align-items-center fs-5 fw-semibold mb-2 text-white">Charging Type</label>
                                    <div class="w-100 position-relative">
                                        <div class="si-border"></div>
                                        <select disabled class="form-select h-55px form-select-solid ps-13" name="charging_type" required>
                                            <option value="">--select--</option>
                                            <option value="TESLA 1" <?php echo $station['charging_type'] == 'TESLA 1' ? 'selected' : ''; ?>>TESLA 1</option>
                                            <option value="TESLA 2" <?php echo $station['charging_type'] == 'TESLA 2' ? 'selected' : ''; ?>>TESLA 2</option>
                                            <option value="TESLA 3" <?php echo $station['charging_type'] == 'TESLA 3' ? 'selected' : ''; ?>>TESLA 3</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-5">
                                    <label class="d-flex align-items-center fs-5 fw-semibold mb-2 text-white">City</label>
                                    <div class="w-100 position-relative">
                                        <div class="si-border"></div>
                                        <select disabled class="form-select h-55px form-select-solid ps-13" name="city" required>
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
                                    <input readonly type="number" class="form-control form-control-lg form-control-solid" name="charging_capacity" value="<?php echo htmlspecialchars($station['charging_capacity']); ?>" required>
                                </div>
                                <div class="col-md-6 mb-5">
                                    <label class="fs-3 fw-semibold text-white mr-3">How To Find</label>
                                    <textarea readonly class="form-control form-control-lg form-control-solid text-end h-150px" name="how_to_find" required><?php echo htmlspecialchars($station['how_to_find']); ?></textarea>
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
                                    <label class="d-flex align-items-center fs-5 fw-semibold mb-2 text-white">Asking Price for kwh [NIS]</label>
                                    <input readonly type="number" class="form-control form-control-lg form-control-solid fs-1 py-1 h-55px" name="asking_price" value="<?php echo htmlspecialchars($station['asking_price']); ?>" required>
                                </div>
                                <div class="col-md-6 mb-5">
                                    <label class="d-flex align-items-center fs-5 fw-semibold mb-2 text-white">ALGORITHM Price</label>
                                    <input readonly type="number" class="form-control form-control-lg form-control-solid fs-1 py-1 h-55px" name="algo_price" value="<?php echo htmlspecialchars($station['algo_price']); ?>" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-5">
                            <iframe id="map" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d54084.94909065688!2d34.75604657871049!3d32.08792483335339!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x151d4ca6193b7c1f%3A0xc1fb72a2c0963f90!2sTel%20Aviv-Yafo%2C%20Israel!5e0!3m2!1sen!2s!4v1716064810014!5m2!1sen!2s" width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                            <div id="map-canvas" style="height: 300px;"></div>
                            <input type="hidden" name="latitude" id="latitude" value="<?php echo htmlspecialchars($station['latitude']); ?>" required>
                            <input type="hidden" name="longitude" id="longitude" value="<?php echo htmlspecialchars($station['longitude']); ?>" required>
                            <div class="d-flex justify-content-end mt-9">
                                <!-- <button type="submit" class="btn btn-primary fs-2qx pt-1 pb-0 fw-bold rounded-pill text-Jaturat">Update Station</button> -->
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        // Initialize and add the map
        function initMap() {
            var latitude = <?php echo htmlspecialchars($station['latitude']); ?>;
            var longitude = <?php echo htmlspecialchars($station['longitude']); ?>;
            
            var map = new google.maps.Map(document.getElementById('map-canvas'), {
                center: { lat: latitude, lng: longitude },
                zoom: 13
            });

            var marker = new google.maps.Marker({
                position: { lat: latitude, lng: longitude },
                map: map,
                draggable: true
            });

            google.maps.event.addListener(marker, 'dragend', function(evt) {
                document.getElementById('latitude').value = evt.latLng.lat().toFixed(6);
                document.getElementById('longitude').value = evt.latLng.lng().toFixed(6);
            });
        }
    </script>
</body>
</html>


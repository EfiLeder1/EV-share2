<?php 
    include_once('db_details.php');

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare SQL statement to fetch car details
    $sql = "SELECT * FROM car WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $_SESSION['user_id']);

    // Execute statement
    $stmt->execute();

    // Bind result variables
    $stmt->bind_result($id, $user_id, $car_brand, $license_plate, $model, $charging_type, $year, $battery_capacity);

    // Initialize an empty array to store car details
    $cars = [];

    // Fetch records
    while ($stmt->fetch()) {
        $cars[] = [
            'id' => $id,
            'car_brand' => $car_brand,
            'model' => $model,
        ];
    }
    $stmt->close();

    // Prepare SQL statement to fetch car details
    $sql1 = "SELECT * FROM car";
    $stmt1 = $conn->prepare($sql1);

    // Execute statement
    $stmt1->execute();

    // Bind result variables
    $stmt1->bind_result($id, $user_id, $car_brand, $license_plate, $model, $charging_type, $year, $battery_capacity);

    // Initialize an empty array to store car details
    $cars_C = [];

    // Fetch records
    while ($stmt1->fetch()) {
        $cars_C[] = [
            'id' => $id,
            'car_brand' => $car_brand,
            'model' => $model,
        ];
    }


    $stmt1->close();
    $conn->close();
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
</head>
<body>
    <!-- ====================== Locate Charging Station ===================== -->
    <div class="main h-auto min-h-auto" style="background-image: url(./assets/images/locate-charging-station-bg.jpg);">
        <div class="container h-100 z-index-3 position-relative">
            <div class="locate-charging-station">
                <div class="d-flex align-items-center gap-md-5 gap-2 flex-wrap">
                    <div class="locate-button position-relative"><a class="text-dark" href="reports.php">Reports</a></div>
                    <?php
                    //if($_SESSION['user_type'] == "Station_Owner"){
                    ?>
                    <div class="locate-button position-relative"><a class="text-dark" href="station-management.php">Stations Managment</a></div>
                    <?php
                    //}else if($_SESSION['user_type'] == "Car_Owner"){
                    ?>
                    <div class="locate-button position-relative"><a class="text-dark" href="car-management.php">Car Managment</a></div>
                    <?php
                    //}
                    ?>
                    
                    <div class="locate-button position-relative"><a class="text-dark" href="logout.php">Logout</a></div>
                </div>
                <h1 class="py-5 mb-7">Locate Charging Station</h1>
                <div class="row">
                    <div class="col-xl-8">
                        <div class="d-flex  align-items-center gap-md-5 gap-2 mb-5 flex-wrap">
                            <img src="./assets/images/car-Icon.svg" class="h-40px" alt="">
                            <h2 class="m-0">choose vehicle</h2>
                            <div class="w-200px position-relative">
                                <div class="si-border"></div>
                                <select class="form-select form-select-solid ps-13" data-kt-select2="true" data-placeholder="Select option" data-dropdown-parent="#kt_menu_62cfa323042ea" data-allow-clear="true">
                                    <option>--select--</option>
                                    <?php foreach ($cars as $car): ?>
                                        <option value="<?php echo htmlspecialchars($car['id']); ?>"><?php echo htmlspecialchars($car['car_brand'] . ' ' . $car['model']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="map">
                            <div class="search-bar">
                                <input type="text" placeholder="" value="הקלד כתובת לחיפוש">
                                <div class="search-btn"><i class="fa fa-search" aria-hidden="true"></i></div>
                            </div>
                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d54084.94909065688!2d34.75604657871049!3d32.08792483335339!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x151d4ca6193b7c1f%3A0xc1fb72a2c0963f90!2sTel%20Aviv-Yafo%2C%20Israel!5e0!3m2!1sen!2s!4v1716064810014!5m2!1sen!2s" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                        <div class="d-flex align-items-center gap-md-12 gap-4 mt-7 flex-wrap">
                        <h2 class="m-0">Being charged</h2>
                            <div class="d-flex gap-3 flex-column butto">
                                <?php foreach ($cars_C as $car) { ?>
                                    <div class="d-flex gap-3">
                                        <button class="btn btn-success rounded-pill fs-1 fw-500 w-250px"><?php echo htmlspecialchars($car['car_brand']) . " " . htmlspecialchars($car['model']); ?></button>
                                        <button class="btn btn-danger fw-500 rounded-pill pt-1">
                                            <i class="fa fa-times fs-2tx m-0 p-0" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 position-relative">
                        <h1 class="text-end">Station INFO</h1>
                        <div class="row d-flex justify-content-end">
                            <div class="mb-1 col-sm-5">
                                <p class="fs-5 fw-semibold text-white mr-3 text-end text-uppercase">Status</p>
                                <p class="fs-5 fw-semibold text-white mr-3 text-end">
                                    <span class="h-10px w-10px bg-success rounded-pill mr-2 position-absolute" style="margin-top: 5px;"></span>
                                    <span class="ps-5 text-uppercase">Available</span>
                                </p>
                            </div>
                            <div class="mb-1 col-sm-7  text-end">
                                <label class="fs-3 fw-semibold text-white mr-3">
                                    STATION NAME
                                </label>
                                <input type="text" class="form-control form-control-lg form-control-solid" name="name" placeholder="" value="">
                            </div>
                            <div class="mb-1 col-sm-5">
                                <p class="fs-5 fw-semibold text-white mr-3 text-end text-uppercase"> Available Until:</p>
                                <p class="fs-1 fw-semibold text-white mr-3 text-end">17:00 </p>
                            </div>
                            <div class="mb-1 col-sm-7 text-end">
                                <div class="row">
                                    <div class="mb-1 col-6">
                                        <label class="fs-3 fw-semibold text-white mr-3">
                                            CITY
                                        </label>
                                        <input type="text" class="form-control form-control-lg form-control-solid text-end" name="name" placeholder="" value="">
                                    </div>
                                    <div class="mb-1 col-6">
                                        <label class="fs-3 fw-semibold text-white mr-3">
                                            ADDRESS
                                        </label>
                                        <input type="text" class="form-control form-control-lg form-control-solid text-end" name="name" placeholder="" value="">
                                    </div>
                                </div>
                            </div>
                            <div class="mb-1 col-sm-7  text-end">
                                <div class="row">
                                    <div class="mb-1 col-6">
                                        <label class="fs-3 fw-semibold text-white mr-3">
                                            KWH
                                        </label>
                                        <input type="text" class="form-control form-control-lg form-control-solid text-center" name="name" placeholder="" value="">
                                    </div>
                                    <div class="mb-1 col-6">
                                        <label class="fs-3 fw-semibold text-white mr-3">
                                            CHARGER TYPE
                                        </label>
                                        <input type="text" class="form-control form-control-lg form-control-solid text-center" name="name" placeholder="" value="">
                                    </div>
                                </div>
                            </div>
                            <div class="mb-1 col-sm-7  text-end">
                                <label class="fs-3 fw-semibold text-white mr-3">
                                    DESCRIPTION
                                </label>
                                <textarea type="text" class="form-control form-control-lg form-control-solid text-end h-150px" name="name" placeholder="" value=""> עמדה ממוקמת סמוך למרכז המסחרי, בצמוד לסופר, מיקום סופר מרכזי, הגעה נוחה ובקרבת מקומות תעסוקה רבים. חנייה נקייה ומוצלת ברוב שעות היום.</textarea>
                            </div>
                            <div class="mb-1 col-sm-7 text-end">
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
                            <div class="mb-1 col-sm-7  text-end">
                                <label class="fs-3 fw-semibold text-white mr-3">
                                    CHARGING PRICE [ILS]
                                </label>
                                <input type="text" class="form-control form-control-lg form-control-solid text-end fs-1 py-1 h-55px" name="name" placeholder="" value="">
                            </div>

                        </div>
                        <div style="margin-left:-55px;" class="d-flex flex-column justify-content-end position-md-absolute bottom-0 gap-4 align-items-end mt-6">
                            <button class="btn btn-primary rounded-pill fs-1 fw-500 w-md-auto w-225px">FUTURE RENT</button>
                            <button class="btn btn-primary rounded-pill fs-1 fw-500 w-md-auto w-225px">RENT NOW</button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html> 
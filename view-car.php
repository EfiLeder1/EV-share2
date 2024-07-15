<?php
include_once('db_details.php');

// Initialize variables for form values
$id = $user_id = $car_brand = $license_plate = $model = $charging_type = $year = $battery_capacity = '';

// Check if ID parameter is set and numeric
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare SQL statement to fetch car details
    $sql = "SELECT * FROM car WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    // Execute statement
    $stmt->execute();

    // Bind result variables
    $stmt->bind_result($id, $user_id, $car_brand, $license_plate, $model, $charging_type, $year, $battery_capacity);

    // Fetch record
    if ($stmt->fetch()) {
        // Car record found, proceed to display form
    } else {
        // No car record found with the given ID
        echo "No car found with ID: " . $id;
        exit;
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
} else {
    // ID parameter is missing or not numeric
    echo "Invalid ID parameter.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Car</title>
    <link rel="shortcut icon" href="assets/images/favicon.png" type="image/png">
    <link rel="stylesheet" href="./assets/css/style.css">
</head>
<body>
    <!-- ====================== Header ===================== -->
    <div class="main-vid add-new-car-sec">
        <video autoplay loop muted style="transform: rotatex(180deg);">
            <source src="./assets/images/wave.mp4" type="video/mp4">
        </video>
        <div class="container h-100 z-index-3 position-relative">
            <div class="registor w-xl-50 w-lg-75 w-100 px-lg-0 px-lg-0 px-sm-10 justify-content-start align-items-start py-12">
                <h2>View Car</h2>
                <form action="update-car.php" method="post">
                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                    <div class="row">
                        <div class="col-md-6 mb-5">
                            <label class="d-flex align-items-center fs-5 fw-semibold mb-2 text-white">Car Brand</label>
                            <div class="w-100 position-relative">
                                <div class="si-border"></div>
                                <select disabled required name="car_brand" class="form-select h-55px form-select-solid ps-13">
                                    <option value="">--select--</option>
                                    <option value="Tesla" <?php if ($car_brand === 'Tesla') echo 'selected'; ?>>Tesla</option>
                                    <option value="Audi" <?php if ($car_brand === 'Audi') echo 'selected'; ?>>Audi</option>
                                    <option value="Ferrari" <?php if ($car_brand === 'Ferrari') echo 'selected'; ?>>Ferrari</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 mb-5">
                            <label class="d-flex align-items-center fs-5 fw-semibold mb-2 text-white">LICENSE Plate number</label>
                            <input readonly required type="text" class="form-control form-control-lg form-control-solid" name="license_plate" placeholder="982-52-976" value="<?php echo htmlspecialchars($license_plate); ?>">
                        </div>
                        <div class="col-md-6 mb-5">
                            <label class="d-flex align-items-center fs-5 fw-semibold mb-2 text-white">Model</label>
                            <div class="w-100 position-relative">
                                <div class="si-border"></div>
                                <select disabled required name="model" class="form-select h-55px form-select-solid ps-13">
                                    <option value="">--select--</option>
                                    <option value="1" <?php if ($model === '1') echo 'selected'; ?>>Model S</option>
                                    <option value="2" <?php if ($model === '2') echo 'selected'; ?>>Model M</option>
                                    <option value="3" <?php if ($model === '3') echo 'selected'; ?>>Model L</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 mb-5">
                            <label class="d-flex align-items-center fs-5 fw-semibold mb-2 text-white">Charging type</label>
                            <div class="w-100 position-relative">
                                <div class="si-border"></div>
                                <select disabled required name="charging_type" class="form-select h-55px form-select-solid ps-13">
                                    <option value="">--select--</option>
                                    <option value="1" <?php if ($charging_type === '1') echo 'selected'; ?>>TESLA 1</option>
                                    <option value="2" <?php if ($charging_type === '2') echo 'selected'; ?>>TESLA 2</option>
                                    <option value="3" <?php if ($charging_type === '3') echo 'selected'; ?>>TESLA 3</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 mb-5">
                            <label class="d-flex align-items-center fs-5 fw-semibold mb-2 text-white">Year Model</label>
                            <div class="w-100 position-relative">
                                <div class="si-border"></div>
                                <select disabled required name="year" class="form-select h-55px form-select-solid ps-13">
                                    <option value="">--select--</option>
                                    <option value="1" <?php if ($year === '1') echo 'selected'; ?>>2021</option>
                                    <option value="2" <?php if ($year === '2') echo 'selected'; ?>>2020</option>
                                    <option value="3" <?php if ($year === '3') echo 'selected'; ?>>2019</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 mb-5">
                            <label class="d-flex align-items-center fs-5 fw-semibold mb-2 text-white">Battery capacity [kwh]</label>
                            <input readonly type="text" class="form-control form-control-lg form-control-solid" name="battery_capacity" placeholder="100" required value="<?php echo htmlspecialchars($battery_capacity); ?>">
                        </div>
                        <div class="d-flex justify-content-end mt-9">
                            <!-- <button type="submit" class="btn btn-primary col-md-6 fs-2qx pt-1 pb-0 fw-bold rounded-pill text-Jaturat">Update Vehicle</button> -->
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>

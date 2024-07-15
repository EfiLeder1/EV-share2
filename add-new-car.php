<?php 
    session_start();
    include_once('db_details.php');

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    /** Fetching Car Brands */
    $query = "SELECT * FROM car_brands";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $sys_result = $stmt->get_result();

    $car_brands = [];
    while ($row = $sys_result->fetch_assoc()) {
        $car_brands[] = $row;
    }

    /** Fetching Car Models */
    $sql = "SELECT * FROM car_models";
    $result = $conn->query($sql);

    $car_models = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $car_models[] = $row;
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Car</title>
    <link rel="shortcut icon" href="assets/images/favicon.png" type="image/png">
    <link rel="stylesheet" href="./assets/css/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <!-- ====================== Header===================== -->
    <div class="main-vid add-new-car-sec">
        <video autoplay loop muted style="transform: rotatex(180deg);">
            <source src="./assets/images/wave.mp4" type="video/mp4">
        </video>
        <div class="container h-100 z-index-3 position-relative">
            <div class="registor w-xl-50 w-lg-75 w-100 px-lg-0 px-lg-0 px-sm-10 justify-content-start align-items-start py-12">
                <h2>Add New Car</h2>
                <form action="add-car.php" method="post">
                    <div class="row">
                        <div class="col-md-6 mb-5">
                            <label class="d-flex align-items-center fs-5 fw-semibold mb-2 text-white">Car Brand</label>
                            <div class="w-100 position-relative">
                                <div class="si-border"></div>
                                <select id="car-brands" required name="car_brand" class="form-select h-55px form-select-solid ps-13">
                                    <option value="">--select--</option>
                                    <?php
                                        if($car_brands){
                                            foreach($car_brands as $model){
                                                echo '<option value="'.$model['id'].'">'.$model['name'].'</option>';
                                            }
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 mb-5">
                            <label class="d-flex align-items-center fs-5 fw-semibold mb-2 text-white">LICENSE Plate number</label>
                            <input required type="text" class="form-control form-control-lg form-control-solid" name="license_plate" placeholder="" value="">
                        </div>
                        <div class="col-md-6 mb-5">
                            <label class="d-flex align-items-center fs-5 fw-semibold mb-2 text-white">Model</label>
                            <div class="w-100 position-relative">
                                <div class="si-border"></div>
                                <select id="car-model" required name="model" class="form-select h-55px form-select-solid ps-13">
                                    <option value="">--select--</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 mb-5">
                            <label class="d-flex align-items-center fs-5 fw-semibold mb-2 text-white">Charging type</label>
                            <div class="w-100 position-relative">
                                <div class="si-border"></div>
                                <select required name="charging_type" class="form-select h-55px form-select-solid ps-13">
                                    <option value="">--select--</option>
                                    <option value="TESLA 1">TESLA 1</option>
                                    <option value="TESLA 2">TESLA 2</option>
                                    <option value="TESLA 2">TESLA 3</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 mb-5">
                            <label class="d-flex align-items-center fs-5 fw-semibold mb-2 text-white">Year Model</label>
                            <div class="w-100 position-relative">
                                <div class="si-border"></div>
                                <select required name="year" class="form-select h-55px form-select-solid ps-13">
                                    <option value="">--select--</option>
                                    <option value="1">2021</option>
                                    <option value="2">2020</option>
                                    <option value="3">2019</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 mb-5">
                            <label class="d-flex align-items-center fs-5 fw-semibold mb-2 text-white">Battery capacity [kwh]</label>
                            <input type="text" class="form-control form-control-lg form-control-solid" name="battery_capacity" placeholder="" required>
                        </div>
                        <div class="d-flex justify-content-end mt-9">
                            <button type="submit" class="btn btn-primary col-md-6 fs-2qx pt-1 pb-0 fw-bold rounded-pill text-Jaturat">Add Vehicle</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        const carModels = JSON.parse('<?php echo json_encode($car_models) ?>');

        $('document').ready(() => {
            
        });

        $('#car-brands').change((event) => {
            prepareCarModelOptions(event.target.value);
        });

        function prepareCarModelOptions(brand_id) {
            const brandModels = carModels.filter(({brand}) => brand == brand_id );

            const optionsHtml = brandModels.reduce((optionsHtml, {id, name}) => {
                return optionsHtml + `<option value="${id}">${name}</option>`;
            }, '<option value="">--select--</option>');
            
            $('#car-model').html(optionsHtml);
        }
    </script>
</body>
</html>

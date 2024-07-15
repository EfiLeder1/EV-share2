<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cars MANAGEMENT</title>
    <link rel="shortcut icon" href="assets/images/favicon.png" type="image/png">
    <link rel="stylesheet" href="./assets/css/style.css">
    <!-- Include FontAwesome CSS for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-+5w1JfAnFCCRqBOSJ1WdV0A3+UOsj6X3X29sXtnEQ4K2yz4f+lc+SP4KTXUmHLOe2Kmsc2JzDBiF0H3tq5oJKA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    <!-- ====================== Header ===================== -->
    <div class="main" style="background-image: url(./assets/images/wave-bg.jpg);">
        <div class="container h-100 z-index-3 position-relative">
            <div class="station-management w-lg-75">
                <h1>Cars MANAGEMENT</h1>
                <div class="sta-card">
                    <?php
                    include_once('db_details.php');

                    // Create connection
                    $conn = new mysqli($servername, $username, $password, $dbname);

                    // Check connection
                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }

                    // SQL query to fetch cars
                    $sql = "SELECT *,
                        (select name from car_brands where id = car.car_brand) as selected_brand,
                        (select name from car_models where id = car.model) as selected_model 
                    FROM car WHERE user_id = " . $_SESSION['user_id'];
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        // Output data of each row
                        while ($row = $result->fetch_assoc()) {
                            $delete_script = "return confirm('Are you sure you want to delete?')";

                            echo '<div class="sta-search d-flex gap-md-4 gap-0 align-items-center mb-5">';
                            echo '<input type="text" class="flex-grow-1 search-in" name="name" placeholder="' . htmlspecialchars($row['selected_brand']) . '" value="">';
                            echo '<div class="d-flex gap-4 align-items-center">';
                            echo '<a href="edit-car.php?id=' . $row['id'] . '"><i class="fa fa-pencil-square-o text-primary" aria-hidden="true"></i></a>';
                            echo '<a href="delete-car.php?id=' . $row['id'] . '" onclick="'.$delete_script.'"><i class="fa fa-trash text-primary" aria-hidden="true"></i></a>';
                            echo '<a href="view-car.php?id=' . $row['id'] . '"><i class="fa fa-search text-primary" aria-hidden="true"></i></a>';
                            echo '</div>';
                            echo '</div>';
                        }
                    } else {
                        echo '<p>No cars found.</p>';
                    }

                    // Close connection
                    $conn->close();
                    ?>
                    
                    <div class="d-flex justify-content-end">
                        <a href="./add-new-car.php" class="btn btn-primary btn-ss rounded-pill">Add New Car</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

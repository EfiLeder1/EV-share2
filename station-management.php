<?php
include_once('db_details.php');

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch stations from database
//echo '<pre>';print_r($_SESSION['user_id']);exit;
$sql = "SELECT * FROM station WHERE user_id = " . $_SESSION['user_id'];
$result = $conn->query($sql);
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
    <!-- ====================== Header===================== -->
    <div class="main station-management-sec" style="background-image: url(./assets/images/wave-bg.jpg);">
        <div class="container h-100 z-index-3 position-relative">
            <div class="station-management w-lg-75">
                <h1>stations MANAGEMENT </h1>
                <div class="sta-card">
                    <?php if ($result->num_rows > 0): ?>
                        <?php while($row = $result->fetch_assoc()): ?>
                        <div class="sta-search d-flex gap-md-4 gap-0  align-items-center mb-5 ">
                            <input type="text" class="flex-grow-1 search-in" name="name" placeholder="<?php echo $row['station_name'] . ' ' . $row['address']; ?>" value="" readonly>
                            <div class="d-flex gap-4 align-items-center">
                                <i class="fa fa-clock-o openpopup text-primary cursor-pointer" aria-hidden="true"></i>
                                <div class="form-check form-switch form-switch-sm form-check-custom form-check-solid">
                                    <input row-id="<?php echo $row['id']; ?>" class="form-check-input w-70px h-35px" type="checkbox" name="status" <?php echo $row['is_available'] == '1' ? 'checked':''; ?> />
                                </div>
                                <a href="delete_station.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete?')"><i class="fa cursor-pointer fa-trash text-primary" aria-hidden="true"></i></a>
                                <a href="view-station.php?id=<?php echo $row['id']; ?>"><i class="fa cursor-pointer fa-search text-primary" aria-hidden="true"></i></a>
                                <a href="edit_station.php?id=<?php echo $row['id']; ?>"><i class="fa cursor-pointer fa-pencil-square-o text-primary" aria-hidden="true"></i></a>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p>No stations found</p>
                    <?php endif; ?>
                    <div class="d-flex justify-content-end">
                      <a href="./add-new-charging-station.php" class="btn btn-primary btn-ss rounded-pill">add new station</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--  ---------------------- popup ---------------------- -->
    <div class="calender-date-and-time-popup" style="display: none;">
        <span class="close-button">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
              </svg>
        </span>
          
        <div class="date-calender">
            <div class="sub-header ">
                <div class="d-flex gap-2">
                    <div class="dote"></div>
                    <div class="dote"></div>
                    <div class="dote"></div>
                </div>
                <div class="dote-line w-65px"></div>
            </div>
            <div class="break-line"></div>
            <div id="datepicker"></div>

        </div>
        <div class="popup-time ms-1">
            <div class="d-flex w-100 justify-content-between align-items-center">
                <h2>From</h2>
                <div class="d-flex flex justify-content-center align-items-center flex-column position-relative h-60px me-3">
                    <i class="fa fa-angle-up fs-1 text-black position-absolute top-0" aria-hidden="true"></i>
                    <input type="time" name="form" id="from" value="12:24">
                    <i class="fa fa-angle-down fs-1 text-black position-absolute bottom-0" aria-hidden="true"></i>
                </div>
            </div>
            <div class="d-flex w-100 justify-content-between align-items-center">
                <h2>To</h2>
                <div class="d-flex flex justify-content-center align-items-center flex-column position-relative h-60px me-3">
                    <i class="fa fa-angle-up fs-1 text-black position-absolute top-0" aria-hidden="true"></i>
                    <input type="time" name="to" id="to" value="14:20">
                    <i class="fa fa-angle-down fs-1 text-black position-absolute bottom-0" aria-hidden="true"></i>
                </div>
            </div>
            <div class="d-flex gap-4">
                <button class="btn btn-primary rounded-pill w-80px px-2">DELETE</button>
                <button class="btn btn-primary rounded-pill w-80px px-2">SAVE</button>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://code.jquery.com/ui/1.9.1/jquery-ui.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js" type="text/javascript"></script>
    <script src="./assets/js/pages/calender.js"></script>
    <script>
        $(".openpopup").on('click', function(){
            $('.calender-date-and-time-popup').toggle().css({'top': '183px'});
        }) 
        $(".openpopup2").on('click', function(){
            $('.calender-date-and-time-popup').toggle().css({'top': '223px'});
        }) 
        $(".close-button").on('click', function(){
            $('.calender-date-and-time-popup').toggle()
        })
        $("input[name=status]").on('change', function(){
            const newStatus = $(this).is(':checked');
            const rowId = $(this).attr('row-id');
            const statusChangesUrl = `${baseUrl()}/update_station_status.php?id=${rowId}&status=${newStatus}`;
            
            window.location = statusChangesUrl;
        });

        function baseUrl() {
            return window.location.href.split('/').slice(0, -1).join('/');
        }
</script>
    </script>
</body>
</html>

<?php
$conn->close();
?>

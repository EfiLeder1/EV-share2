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
    <div class="main" style="background-image: url(./assets/images/wave-bg.jpg);">
        <div class="container h-100 z-index-3 position-relative">
            <div class="station-management w-lg-75">
                <h1>Reports</h1>
                <div class="rep-search mt-20">
                    <div class="d-flex gap-4 align-items-center mb-5">
                        <input type="text" class="flex-grow-1 search-in" name="name" placeholder="Charging Station usage report" disabled>
                        <a href="stations-report.php" class="btn btn-primary btn-ss rounded-pill position-relative bottom-0 mt-1">Generate </a>
                    </div>
                </div>
                <div class="rep-search mt-4">
                    <div class="d-flex gap-4 align-items-center mb-5">
                        <input type="text" class="flex-grow-1 search-in" name="name" placeholder="Electric cars charging sessions report"  disabled>
                        <a href="cars-report.php" class="btn btn-primary btn-ss rounded-pill position-relative bottom-0 mt-1">Generate </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
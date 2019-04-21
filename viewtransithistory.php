<?php

session_start();
$username = $_SESSION['username'];

$type = filter_input(INPUT_GET, 'transportType');
$route = filter_input(INPUT_GET, 'route');
$site = filter_input(INPUT_GET, 'site');
$pricelow = filter_input(INPUT_GET, 'pricelow');
$pricehi = filter_input(INPUT_GET, 'pricehi');
$connection = mysqli_connect(
    $_SERVER['DB_SERVERNAME'],
    $_SERVER['DB_USERNAME'],
    $_SERVER['DB_PASSWORD'],
    $_SERVER['DB_DATABASE']
);

$transits = [];
$query = mysqli_prepare($connection, "SELECT TransitType, TransitRoute, TransitDate, 
                                            (SELECT TransitPrice FROM Transit 
                                                WHERE TakeTransit.TransitType = Transit.TransitType 
                                                AND TakeTransit.TransitRoute = Transit.TransitRoute) AS Price
                                            FROM TakeTransit");
mysqli_stmt_execute($query);
mysqli_stmt_bind_result($query, $resulttype, $resultroute, $resultdate, $resultprice);
while (mysqli_stmt_fetch($query)) {
    array_push($transits, array('type' => $resulttype, 'route' => $resultroute, 'price' => $resultprice, 'date' => $resultdate));
}
mysqli_stmt_close($query);

$query = mysqli_prepare($connection, "SELECT SiteName FROM Site");
mysqli_stmt_execute($query);
mysqli_stmt_bind_result($query, $sitesresult);
$sites = [];
while (mysqli_stmt_fetch($query)) {
    array_push($sites, $sitesresult);
}
mysqli_stmt_close($query);


mysqli_close($connection);
?>

<?php include('header.php') ?>

<!-- Page Content -->
<div class="container">
    <div class="row">
        <div class="col-lg-8 offset-lg-2 text-center">
            <h1 class="mt-5">Transit History</h1>
            <form action="viewtransithistory.php" method="GET">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Transport Type</label>
                            <div class="col-sm-8">
                                <select name="transportType" class="form-control">
                                    <option value="all">-- ALL --</option>
                                    <option value="marta">MARTA</option>
                                    <option value="bus">Bus</option>
                                    <option value="bike">Bike</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Contain Site</label>
                            <div class="col-sm-8">
                                <select name="siteFilter" class="form-control">
                                    <option value="all">-- ALL --</option>
                                    <?php
                                    foreach ($sites as $site) {
                                        echo '<option value="'.$site.'">'.$site.'</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Route</label>
                            <div class="col-sm-8">
                                <input class="form-control" type="text" name="route">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Start Date</label>
                            <div class="col-sm-8">
                                <input class="form-control" type="date" name="start">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">End Date</label>
                            <div class="col-sm-8">
                                <input class="form-control" type="date" name="end">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 text-center mb-4">
                    <button type="submit" class="btn btn-primary">Filter</button>
                </div>
            </form>
            <div class="row">
                <div class="col-md-12">
                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col">Date</th>
                            <th scope="col">Route</th>
                            <th scope="col">Transport Type</th>
                            <th scope="col">Price</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        foreach ($transits as $t) {
//                                if (($site == 'all' || $site == null || $site == $s['name']) && ($manager == 'all' || $manager == null || $manager == $s['username']) && ($open == 'all' || $open == null || (($open == 'yes') == $s['open'])))
                            echo '<tr><td>'. $t['date'] .'</td><td>' . $t['route'] . '</td><td>' . $t['type'] . '</td><td>' . $t['price'] . '</td><td>' . $t['sites'] . '</td></tr>';
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 text-center">
                    <a href="home.php" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('footer.php') ?>

<?php

session_start();
$username = $_SESSION['username'];

$type = filter_input(INPUT_GET, 'transportType');
$route = filter_input(INPUT_GET, 'route');
$site = filter_input(INPUT_GET, 'site');
$start = filter_input(INPUT_GET, 'start');
$end = filter_input(INPUT_GET, 'end');
$connection = mysqli_connect(
    $_SERVER['DB_SERVERNAME'],
    $_SERVER['DB_USERNAME'],
    $_SERVER['DB_PASSWORD'],
    $_SERVER['DB_DATABASE']
);

$transits = [];
if ($site != 'all') {
    $query = mysqli_prepare($connection, "SELECT TakeTransit.TransitType, TakeTransit.TransitRoute, TakeTransit.TransitDate, SiteName,
                                            (SELECT TransitPrice FROM Transit 
                                                WHERE TakeTransit.TransitType = Transit.TransitType 
                                                AND TakeTransit.TransitRoute = Transit.TransitRoute) AS Price
                                            FROM TakeTransit, Connect 
                                            WHERE TakeTransit.Username=?
                                                AND SiteName=?
                                                AND Connect.TransitType = TakeTransit.TransitType 
                                                AND Connect.TransitRoute = TakeTransit.TransitRoute");
    mysqli_stmt_bind_param($query, 'ss', $username, $site);
    mysqli_stmt_execute($query);
    mysqli_stmt_bind_result($query, $resulttype, $resultroute, $resultdate, $sitename, $resultprice);
} else {
    $query = mysqli_prepare($connection, "SELECT TransitType, TransitRoute, TransitDate,
                                                (SELECT TransitPrice FROM Transit 
                                                    WHERE TakeTransit.TransitType = Transit.TransitType 
                                                    AND TakeTransit.TransitRoute = Transit.TransitRoute) AS Price
                                                FROM TakeTransit 
                                                WHERE TakeTransit.Username=?");
    mysqli_stmt_bind_param($query, 's', $username);
    mysqli_stmt_execute($query);
    mysqli_stmt_bind_result($query, $resulttype, $resultroute, $resultdate, $resultprice);
}
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
                                    <option <?= $type == 'all' ? 'selected' : '' ?> value="all">-- ALL --</option>
                                    <option <?= $type == 'MARTA' ? 'selected' : '' ?> value="MARTA">MARTA</option>
                                    <option <?= $type == 'Bus' ? 'selected' : '' ?> value="Bus">Bus</option>
                                    <option <?= $type == 'Bike' ? 'selected' : '' ?> value="Bike">Bike</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Contain Site</label>
                            <div class="col-sm-8">
                                <select name="site" class="form-control">
                                    <option value="all">-- ALL --</option>
                                    <?php
                                    foreach ($sites as $s) {
                                        if ($site != $s) {
                                            echo '<option value="'.$s.'">'.$s.'</option>';
                                        } else {
                                            echo '<option selected value="'.$s.'">'.$s.'</option>';
                                        }
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
                                <input value="<?= $route ?>" class="form-control" type="text" name="route">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Start Date</label>
                            <div class="col-sm-8">
                                <input value="<?= $start ?>" class="form-control" type="date" name="start">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">End Date</label>
                            <div class="col-sm-8">
                                <input value="<?= $end ?>" class="form-control" type="date" name="end">
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
                            if (($type == 'all' || $type == null || $type == $t['type'])
                                && ($route == null || $route == '' || $route == $t['route'])
                                && ($start == '' || $start == null || strtotime($start) <= strtotime($t['date']))
                                && ($end == '' || $end == null || strtotime($end) >= strtotime($t['date'])))
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

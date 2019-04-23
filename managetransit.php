<?php

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
// if we are filtering by site only display those rows
if ($site != 'all') {
    $query = mysqli_prepare($connection, "SELECT DISTINCT Transit.TransitType, Transit.TransitRoute, TransitPrice, SiteName,
                                                (SELECT COUNT(*) FROM Connect 
                                                    WHERE Connect.TransitType = Transit.TransitType 
                                                    AND Connect.TransitRoute = Transit.TransitRoute) AS SiteCount,
                                                (SELECT COUNT(*) FROM TakeTransit 
                                                    WHERE TakeTransit.TransitType = Transit.TransitType 
                                                    AND TakeTransit.TransitRoute = Transit.TransitRoute) AS LogCount
                                                FROM Transit, Connect
                                                WHERE SiteName=? AND Connect.TransitType = Transit.TransitType 
                                                        AND Connect.TransitRoute = Transit.TransitRoute");
    mysqli_stmt_bind_param($query, 's', $site);
    mysqli_stmt_execute($query);
    mysqli_stmt_bind_result($query, $resulttype, $resultroute, $resultprice, $sitename, $resultsitecount, $resultlogcount);
} else {
    $query = mysqli_prepare($connection, "SELECT TransitType, TransitRoute, TransitPrice, 
                                            (SELECT COUNT(*) FROM Connect 
                                                WHERE Connect.TransitType = Transit.TransitType 
                                                AND Connect.TransitRoute = Transit.TransitRoute) AS SiteCount,
                                            (SELECT COUNT(*) FROM TakeTransit 
                                                WHERE TakeTransit.TransitType = Transit.TransitType 
                                                AND TakeTransit.TransitRoute = Transit.TransitRoute) AS LogCount
                                            FROM Transit");
    mysqli_stmt_execute($query);
    mysqli_stmt_bind_result($query, $resulttype, $resultroute, $resultprice, $resultsitecount, $resultlogcount);
}
while (mysqli_stmt_fetch($query)) {
    array_push($transits, array('type' => $resulttype, 'route' => $resultroute, 'price' => $resultprice, 'sites' => $resultsitecount, 'logs' => $resultlogcount));
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
            <h1 class="mt-5">Manage Transit</h1>
            <form action="managetransit.php" method="GET">
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
                            <label class="col-sm-4 col-form-label">Route</label>
                            <div class="col-sm-8">
                                <input value="<?= $route ?>" class="form-control" type="text" name="route">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
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
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Price Range</label>
                            <div class="col-sm-3">
                                <input value="<?= $pricelow ?>" class="form-control" type="text" name="pricelow">
                            </div>
                            <div class="col-sm-2 mt-2">
                                to
                            </div>
                            <div class="col-sm-3">
                                <input value="<?= $pricehi ?>" class="form-control" type="text" name="pricehi">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4"></div>
                    <div class="col-md-4">
                        <div class="form-group row">
                            <div class="col-sm-12 text-center">
                                <button type="submit" class="btn btn-primary">Filter</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4"></div>
                </div>
            </form>
            <form onsubmit="return verify()">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col"></th>
                                <th scope="col">Route</th>
                                <th scope="col">Transport Type</th>
                                <th scope="col">Price</th>
                                <th scope="col"># Connected Sites</th>
                                <th scope="col"># Transit Logged</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            foreach ($transits as $t) {
                                $pricecheck = ($pricelow == null || $pricelow == '' || intval($pricelow) <= $t['price']) && ($pricehi == null || $pricehi == '' || intval($pricehi) >= $t['price']);
                                if (($type == 'all' || $type == null || $type == $t['type']) && ($route == null || $route == '' || $route == $t['route']) && $pricecheck)
                                    echo '<tr><td><input type="radio" name="routetbl" value="' . $t['route'] . ',' . $t['type'] .'"></td><td>' . $t['route'] . '</td><td>' . $t['type'] . '</td><td>' . $t['price'] . '</td><td>' . $t['sites'] . '</td><td>' . $t['logs'] . '</td></tr>';
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
                    <div class="col-md-3 text-center">
                        <a href="createtransit.php" class="btn btn-primary">Create</a>
                    </div>
                    <div class="col-md-3 text-center">
                        <button type="submit" class="btn btn-primary" formaction="edittransit.php" formmethod="GET">Edit</button>
                    </div>
                    <div class="col-md-3 text-center">
                        <button type="submit" class="btn btn-primary" formaction="deletetransitscript.php" formmethod="POST">Delete</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function verify() {
        if (typeof $('input[name=routetbl]:checked').val() === 'undefined') {
            return false;
        }
        return true;
    }
</script>

<?php include('footer.php') ?>

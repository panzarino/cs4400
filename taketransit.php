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
$query = mysqli_prepare($connection, "SELECT TransitType, TransitRoute, TransitPrice, 
                                            (SELECT COUNT(*) FROM Connect 
                                                WHERE Connect.TransitType = Transit.TransitType 
                                                AND Connect.TransitRoute = Transit.TransitRoute) AS SiteCount
                                            FROM Transit");
mysqli_stmt_execute($query);
mysqli_stmt_bind_result($query, $resulttype, $resultroute, $resultprice, $resultsitecount);
while (mysqli_stmt_fetch($query)) {
    array_push($transits, array('type' => $resulttype, 'route' => $resultroute, 'price' => $resultprice, 'sites' => $resultsitecount));
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
            <h1 class="mt-5">Take Transit</h1>
            <form action="taketransit.php" method="GET">
                <div class="row">
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
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Price Range</label>
                            <div class="col-sm-3">
                                <input class="form-control" type="text" name="pricelow">
                            </div>
                            <div class="col-sm-2 mt-2">
                                to
                            </div>
                            <div class="col-sm-3">
                                <input class="form-control" type="text" name="pricehi">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <div class="col-sm-12 text-center">
                                <button type="submit" class="btn btn-primary">Filter</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <form method="POST" action="logtransitscript.php" onsubmit="return verify()">
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
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            foreach ($transits as $t) {
//                                if (($site == 'all' || $site == null || $site == $s['name']) && ($manager == 'all' || $manager == null || $manager == $s['username']) && ($open == 'all' || $open == null || (($open == 'yes') == $s['open'])))
                                echo '<tr><td><input type="radio" name="routetbl" value="' . $t['route'] . ',' . $t['type'] .'"></td><td>' . $t['route'] . '</td><td>' . $t['type'] . '</td><td>' . $t['price'] . '</td><td>' . $t['sites'] . '</td></tr>';
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
                    <label class="col-sm-3 col-form-label">Transit Date</label>
                    <div class="col-sm-3">
                        <input class="form-control" id="datepick" type="date" name="date">
                    </div>
                    <div class="col-md-3 text-center">
                        <button type="submit" class="btn btn-primary">Log Transit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function verify() {
        if (typeof $('input[name=routetbl]:checked').val() === 'undefined' || $('#datepick').val() === '') {
            return false;
        }
        return true;
    }
</script>

<?php include('footer.php') ?>

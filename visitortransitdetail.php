<?php

$type = filter_input(INPUT_GET, 'type');
$site = filter_input(INPUT_GET, 'site');

$connection = mysqli_connect(
    $_SERVER['DB_SERVERNAME'],
    $_SERVER['DB_USERNAME'],
    $_SERVER['DB_PASSWORD'],
    $_SERVER['DB_DATABASE']
);

// get list of transits to display in table
$transits = [];
$qstr = "SELECT DISTINCT Transit.TransitType, Transit.TransitRoute, TransitPrice, SiteName,
                                                (SELECT COUNT(*) FROM Connect 
                                                    WHERE Connect.TransitType = Transit.TransitType 
                                                    AND Connect.TransitRoute = Transit.TransitRoute) AS SiteCount
                                                FROM Transit, Connect
                                                WHERE SiteName=? AND Connect.TransitType = Transit.TransitType 
                                                        AND Connect.TransitRoute = Transit.TransitRoute";
if (isset($type) && $type != 'all') $qstr .= " AND Transit.TransitType=?";
$query = mysqli_prepare($connection, $qstr);
if (isset($type) && $type != 'all') {
    mysqli_stmt_bind_param($query, 'ss', $site, $type);
} else {
    mysqli_stmt_bind_param($query, 's', $site);
}
mysqli_stmt_execute($query);
mysqli_stmt_bind_result($query, $resulttype, $resultroute, $resultprice, $sitename, $resultsitecount);
while (mysqli_stmt_fetch($query)) {
    array_push($transits, array('type' => $resulttype, 'route' => $resultroute, 'price' => $resultprice, 'sites' => $resultsitecount));
}
mysqli_stmt_close($query);

// get sites for filter dropdown
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
            <h1 class="mt-5">Transit Detail</h1>
            <br/>
            <form action="visitortransitdetail.php" method="GET">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Site</label>
                            <div class="col-sm-8">
                                <p class="text-left mt-2"><b><?= $site ?></b></p>
                                <input type="hidden" value="<?= $site ?>" name="site" >
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Transport Type</label>
                            <div class="col-sm-8">
                                <select name="type" class="form-control">
                                    <option <?= $type == 'all' ? 'selected' : '' ?> value="all">-- ALL --</option>
                                    <option <?= $type == 'MARTA' ? 'selected' : '' ?> value="MARTA">MARTA</option>
                                    <option <?= $type == 'Bus' ? 'selected' : '' ?> value="Bus">Bus</option>
                                    <option <?= $type == 'Bike' ? 'selected' : '' ?> value="Bike">Bike</option>
                                </select>
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
            <form method="POST" action="logvisitortransitscript.php" onsubmit="return verify()">
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
                                echo '<tr><td><input type="radio" name="routetbl" value="' . $t['route'] . ',' . $t['type'] .'"></td><td>' . $t['route'] . '</td><td>' . $t['type'] . '</td><td>' . $t['price'] . '</td><td>' . $t['sites'] . '</td></tr>';
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 text-center">
                        <a href="exploresite.php" class="btn btn-primary">Back</a>
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

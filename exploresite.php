<?php

$startdate = filter_input(INPUT_GET, 'startdate');
if (strlen($startdate) == 0) $startdate = 0;
$enddate = filter_input(INPUT_GET, 'endddate');
if (strlen($enddate) == 0) $enddate = PHP_INT_MAX;
$site = filter_input(INPUT_GET, 'site');
$open = filter_input(INPUT_GET, 'open');
$vrstart = filter_input(INPUT_GET, 'vrstart');
$vrend = filter_input(INPUT_GET, 'vrend');
$erstart = filter_input(INPUT_GET, 'erstart');
$erend = filter_input(INPUT_GET, 'erend');
$visited = filter_input(INPUT_GET, 'visited');

session_start();

$username = $_SESSION['username'];

$connection = mysqli_connect(
    $_SERVER['DB_SERVERNAME'],
    $_SERVER['DB_USERNAME'],
    $_SERVER['DB_PASSWORD'],
    $_SERVER['DB_DATABASE']
);

$siteNames = [];
$sitenamessquery = mysqli_prepare($connection, "SELECT SiteName FROM Site");
mysqli_stmt_execute($sitenamessquery);
mysqli_stmt_bind_result($sitenamessquery, $sitesresult);
$sites = [];
while (mysqli_stmt_fetch($sitenamessquery)) {
    array_push($siteNames, $sitesresult);
}
mysqli_stmt_close($sitenamessquery);

$sites = [];
$sitesquery = mysqli_prepare($connection, "SELECT SiteName, OpenEveryday, (SELECT COUNT(*) FROM Event WHERE Event.SiteName=Site.SiteName AND EndDate >= ? AND StartDate <= ?), (SELECT COUNT(*) FROM VisitEvent WHERE VisitEvent.SiteName=Site.SiteName AND VisitEventDate >= ? AND VisitEventDate <= ?) + (SELECT COUNT(*) FROM VisitSite WHERE VisitSite.SiteName=Site.SiteName AND VisitSiteDate >= ? AND VisitSiteDate <= ?), (SELECT COUNT(*) FROM VisitEvent WHERE VisitEvent.SiteName=Site.SiteName AND VisitEventDate >= ? AND VisitEventDate <= ? AND VisitorUsername=?) + (SELECT COUNT(*) FROM VisitSite WHERE VisitSite.SiteName=Site.SiteName AND VisitSiteDate >= ? AND VisitSiteDate <= ? AND VisitorUsername=?) FROM Site");
mysqli_stmt_bind_param($sitesquery, 'ssssssssssss', $startdate, $enddate, $startdate, $enddate, $startdate, $enddate, $startdate, $enddate, $username, $startdate, $enddate, $username);
mysqli_stmt_execute($sitesquery);
mysqli_stmt_bind_result($sitesquery, $resultname, $resultopen, $resulteventcount, $resultvisits, $resultmyvisits);
while (mysqli_stmt_fetch($sitesquery)) {
    array_push($sites, array('name' => $resultname, 'open' => $resultopen, 'eventCount' => $resulteventcount, 'visits' => $resultvisits, 'myVisits' => $resultmyvisits));
}
mysqli_stmt_close($sitesquery);

mysqli_close($connection);
?>

<?php include('header.php') ?>

<!-- Page Content -->
<div class="container">
    <div class="row">
        <div class="col-lg-8 offset-lg-2 text-center">
            <h1 class="mt-5">Explore Site</h1>
            <form action="exploresite.php" method="GET">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Site</label>
                            <div class="col-sm-8">
                                <select name="site" class="form-control" required>
                                    <option value="all" <?= $site == 'all' || $site == null ? 'selected' : '';?>>All</option>
                                    <?php
                                    foreach ($sites as $s) {
                                        if ($site == $s['name']) {
                                            echo '<option value="'.$s['name'].'" selected>'.$s['name'].'</option>';
                                        } else {
                                            echo '<option value="' . $s['name'] . '">' . $s['name'] . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Open Everyday</label>
                            <div class="col-sm-8">
                                <select name="open" class="form-control" required>
                                    <option value="all" <?= $open == 'all' || $open == null ? 'selected' : '';?>>All</option>
                                    <option value="yes" <?= $open == 'yes' ? 'selected' : '';?>>Yes</option>
                                    <option value="no" <?= $open == 'no' ? 'selected' : '';?>>No</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Start Date</label>
                            <div class="col-sm-8">
                                <input type="date" class="form-control" name="startdate" value="<?= $startdate ?>">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">End Date</label>
                            <div class="col-sm-8">
                                <input type="date" class="form-control" name="enddate" value="<?= $enddate ?>">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Total Visits Range</label>
                            <div class="col-sm-8">
                                <div class="row">
                                    <div class="col-md-5">
                                        <input type="number" class="form-control" name="vrstart" value="<?= $vrstart ?>">
                                    </div>
                                    <div class="col-md-2">
                                        <b>-</b>
                                    </div>
                                    <div class="col-md-5">
                                        <input type="number" class="form-control" name="vrxend" value="<?= $vrend ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Event Count Range</label>
                            <div class="col-sm-8">
                                <div class="row">
                                    <div class="col-md-5">
                                        <input type="number" class="form-control" name="erstart" value="<?= $erstart ?>">
                                    </div>
                                    <div class="col-md-2">
                                        <b>-</b>
                                    </div>
                                    <div class="col-md-5">
                                        <input type="number" class="form-control" name="erend" value="<?= $erend ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="visited" value="true" <?= $visited ? 'checked' : '' ?>>
                            <label class="form-check-label">Include Visited</label>
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
            <form method="GET" onsubmit="return verify()">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col"></th>
                                <th scope="col">Site Name</th>
                                <th scope="col">Event Count</th>
                                <th scope="col">Total Visits</th>
                                <th scope="col">My Visits</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            foreach ($sites as $s) {
                                if (($site == 'all' || $site == $s['name'])
                                    && ($open == 'all' || $open == null || (($open == 'yes') == $s['open']))
                                    && ($vrstart == '' || $vrstart == null || intval($vrstart) <= $s['visits'])
                                    && ($vrend == '' || $vrend == null || intval($vrend) >= $s['visits'])
                                    && ($erstart == '' || $erstart == null || intval($erstart) <= $s['eventCount'])
                                    && ($erend == '' || $erend == null || intval($erend) >= $s['eventCount'])
                                    && ($visited || (!$visited && $s['myVisits'] == 0))
                                ) {
                                    echo '<tr><td><input type="radio" name="site" value="' . $s['name'] . '"></td><td>' . $s['name'] . '</td><td>' . $s['eventCount'] . '</td><td>' . $s['visits'] . '</td><td>' . $s['myVisits'] . '</td></tr>';
                                }
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 text-center">
                        <a href="home.php" class="btn btn-primary">Back</a>
                    </div>
                    <div class="col-md-4 text-center">
                        <button type="submit" class="btn btn-primary" formaction="visitorsitedetail.php" formmethod="GET">Site Detail</button>
                    </div>
                    <div class="col-md-4 text-center">
                        <button type="submit" class="btn btn-primary" formaction="visitortransitdetail.php" formmethod="GET">Transit Detail</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function verify() {
        if (typeof $('input[name=site]:checked').val() === 'undefined') {
            return false;
        }
        return true;
    }
</script>

<?php include('footer.php') ?>

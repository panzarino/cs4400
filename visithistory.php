<?php

$site = filter_input(INPUT_GET, 'site');
$event = filter_input(INPUT_GET, 'event');
$startdate = filter_input(INPUT_GET, 'startdate');
$enddate = filter_input(INPUT_GET, 'enddate');

session_start();

$username = $_SESSION['username'];

$connection = mysqli_connect(
    $_SERVER['DB_SERVERNAME'],
    $_SERVER['DB_USERNAME'],
    $_SERVER['DB_PASSWORD'],
    $_SERVER['DB_DATABASE']
);

$sites = [];
$sitenamessquery = mysqli_prepare($connection, "SELECT SiteName FROM Site");
mysqli_stmt_execute($sitenamessquery);
mysqli_stmt_bind_result($sitenamessquery, $sitesresult);
$sites = [];
while (mysqli_stmt_fetch($sitenamessquery)) {
    array_push($sites, $sitesresult);
}
mysqli_stmt_close($sitenamessquery);

$visits = [];
$visitsquery = mysqli_prepare($connection, "SELECT VisitEventDate, Event.EventName, Event.SiteName, Event.EventPrice FROM VisitEvent JOIN Event ON VisitEvent.EventName=Event.EventName AND VisitEvent.StartDate=Event.StartDate AND VisitEvent.SiteName=Event.SiteName WHERE VisitorUsername=? UNION SELECT VisitSiteDate as VisitDate, \"\", SiteName, 0 FROM VisitSite WHERE VisitorUsername=?");
mysqli_stmt_bind_param($visitsquery, 'ss', $username, $username);
mysqli_stmt_execute($visitsquery);
mysqli_stmt_bind_result($visitsquery, $resultdate, $resultevent, $resultsite, $resultprice);
while (mysqli_stmt_fetch($visitsquery)) {
    array_push($visits, array('date' => $resultdate, 'event' => $resultevent, 'site' => $resultsite, 'price' => $resultprice));
}
mysqli_stmt_close($visitsquery);

mysqli_close($connection);
?>

<?php include('header.php') ?>

<!-- Page Content -->
<div class="container">
    <div class="row">
        <div class="col-lg-8 offset-lg-2 text-center">
            <h1 class="mt-5">Visit History</h1>
            <form action="visithistory.php" method="GET">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Event</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="event" value="<?= $event ?>">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Site</label>
                            <div class="col-sm-8">
                                <select name="site" class="form-control" required>
                                    <option value="all" <?= $site == 'all' || $site == null ? 'selected' : '';?>>All</option>
                                    <?php
                                    foreach ($sites as $s) {
                                        if ($site == $s) {
                                            echo '<option value="'.$s.'" selected>'.$s.'</option>';
                                        } else {
                                            echo '<option value="' . $s . '">' . $s . '</option>';
                                        }
                                    }
                                    ?>
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
                    <div class="col-md-12">
                        <div class="form-group row">
                            <div class="col-sm-12 text-center">
                                <button type="submit" class="btn btn-primary">Filter</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <div class="row">
                <div class="col-md-12">
                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col">Date</th>
                            <th scope="col">Event</th>
                            <th scope="col">Site</th>
                            <th scope="col">Price ($)</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        foreach ($visits as $visit) {
                            if (($event == '' || $event == null || stripos($visit['event'], $event) || strcasecmp($visit['event'], $event) == 0)
                                && ($site == 'all' || $site == $visit['site'])
                                && ($startdate == '' || $startdate == null || strtotime($startdate) <= strtotime($visit['date']))
                                && ($enddate == '' || $enddate == null || strtotime($enddate) >= strtotime($visit['date']))
                            ) {
                                echo '<tr><td>' . $visit['date'] . '</td><td>' . $visit['event'] . '</td><td>' . $visit['site'] . '</td><td>' . $visit['price'] . '</td></tr>';
                            }
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 text-center">
                    <a href="home.php" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('footer.php') ?>

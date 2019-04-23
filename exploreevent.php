<?php

$name = filter_input(INPUT_GET, 'name');
$description = filter_input(INPUT_GET, 'description');
$site = filter_input(INPUT_GET, 'site');
$startdate = filter_input(INPUT_GET, 'startdate');
$enddate = filter_input(INPUT_GET, 'enddate');
$vrstart = filter_input(INPUT_GET, 'vrstart');
$vrend = filter_input(INPUT_GET, 'vrend');
$prstart = filter_input(INPUT_GET, 'prstart');
$prend = filter_input(INPUT_GET, 'prend');
$visited = filter_input(INPUT_GET, 'visited');
$soldout = filter_input(INPUT_GET, 'soldout');

session_start();

$username = $_SESSION['username'];

$connection = mysqli_connect(
    $_SERVER['DB_SERVERNAME'],
    $_SERVER['DB_USERNAME'],
    $_SERVER['DB_PASSWORD'],
    $_SERVER['DB_DATABASE']
);

$sites = [];
$sitesquery = mysqli_prepare($connection, "SELECT SiteName FROM Site");
mysqli_stmt_execute($sitesquery);
mysqli_stmt_bind_result($sitesquery, $sitesresult);
$sites = [];
while (mysqli_stmt_fetch($sitesquery)) {
    array_push($sites, $sitesresult);
}
mysqli_stmt_close($sitesquery);

$events = [];
$eventsquery = mysqli_prepare($connection, "SELECT EventName, StartDate, SiteName, EndDate, Description, EventPrice, Capacity, (SELECT COUNT(*) FROM VisitEvent WHERE VisitEvent.EventName=Event.EventName AND VisitEvent.StartDate=Event.StartDate AND VisitEvent.SiteName=Event.SiteName), (SELECT COUNT(*) FROM VisitEvent WHERE VisitEvent.EventName=Event.EventName AND VisitEvent.StartDate=Event.StartDate AND VisitEvent.SiteName=Event.SiteName AND VisitorUsername=?) FROM Event");
mysqli_stmt_bind_param($eventsquery, 's', $username);
mysqli_stmt_execute($eventsquery);
mysqli_stmt_bind_result($eventsquery, $resultname, $resultstartdate, $resultsitename, $resultenddate, $resultdescription, $resulteventprice, $resultcapacity, $resultvisits, $resultmyvisits);
while (mysqli_stmt_fetch($eventsquery)) {
    array_push($events, array('name' => $resultname, 'startdate' => $resultstartdate, 'siteName' => $resultsitename, 'enddate' => $resultenddate, 'description' => $resultdescription, 'price' => $resulteventprice, 'ticketsRemaining' => $resultcapacity - $resultvisits, 'visits' => $resultvisits, 'myVisits' => $resultmyvisits));
}
mysqli_stmt_close($eventsquery);

mysqli_close($connection);
?>

<?php include('header.php') ?>

<!-- Page Content -->
<div class="container">
    <div class="row">
        <div class="col-lg-8 offset-lg-2 text-center">
            <h1 class="mt-5">Explore Event</h1>
            <form action="exploreevent.php" method="GET">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Name</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="name" value="<?= $name ?>">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Description Keyword</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="description" value="<?= $description ?>">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Site</label>
                            <div class="col-sm-8">
                                <select name="site" class="form-control">
                                    <option value="all">All</option>
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
                            <label class="col-sm-4 col-form-label">Ticket Price Range</label>
                            <div class="col-sm-8">
                                <div class="row">
                                    <div class="col-md-5">
                                        <input type="number" class="form-control" name="prstart" value="<?= $prstart ?>">
                                    </div>
                                    <div class="col-md-2">
                                        <b>-</b>
                                    </div>
                                    <div class="col-md-5">
                                        <input type="number" class="form-control" name="prend" value="<?= $prend ?>">
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
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="soldout" value="true" <?= $soldout ? 'checked' : '' ?>>
                            <label class="form-check-label">Include Sold Out Event</label>
                        </div>
                    </div>
                </div>
                <br />
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <div class="col-sm-12 text-center">
                                <button type="submit" class="btn btn-primary">Filter</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <form action="visitoreventdetail.php" method="GET" onsubmit="return verify()">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col"></th>
                                <th scope="col">Event Name</th>
                                <th scope="col">Site Name</th>
                                <th scope="col">Ticket Price</th>
                                <th scope="col">Tickets Remaining</th>
                                <th scope="col">Total Visits</th>
                                <th scope="col">My Visits</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            foreach ($events as $event) {
                                if (($name == '' || $name == null || stripos($event['name'], $name) || strcasecmp($event['name'], $name) == 0)
                                    && ($description == '' || $description == null || stripos($event['description'], $description) || strcasecmp($event['description'], $description) == 0)
                                    && ($startdate == '' || $startdate == null || strtotime($startdate) <= strtotime($event['enddate']))
                                    && ($enddate == '' || $enddate == null || strtotime($enddate) >= strtotime($event['startdate']))
                                    && ($site == 'all' || $site == $event['siteName'])
                                    && ($vrstart == '' || $vrstart == null || intval($vrstart) <= $event['visits'])
                                    && ($vrend == '' || $vrend == null || intval($vrend) >= $event['visits'])
                                    && ($prstart == '' || $prstart == null || intval($prstart) <= $event['price'])
                                    && ($prend == '' || $prend == null || intval($prend) >= $event['price'])
                                    && ($visited || (!$visited && $event['myVisits'] == 0))
                                    && ($soldout || (!$soldout && $event['ticketsRemaining'] != 0))
                                ) {
                                    echo '<tr><td><input type="radio" name="site" value="' . $event['name'] . ',' . $event['startdate'] . ',' . $event['siteName'] . '"></td><td>' . $event['name'] . '</td><td>' . $event['siteName'] . '</td><td>' . $event['price'] . '</td><td>' . $event['ticketsRemaining'] . '</td><td>' . $event['visits'] . '</td><td>' . $event['myVisits'] . '</td></tr>';
                                }
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 text-center">
                        <a href="home.php" class="btn btn-primary">Back</a>
                    </div>
                    <div class="col-md-6 text-center">
                        <button type="submit" class="btn btn-primary">Event Detail</button>
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

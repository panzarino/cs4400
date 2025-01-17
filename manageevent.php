<?php

$sort = filter_input(INPUT_GET, 'sort');
if (isset($sort) && $sort != '') {
    $sort = ' ORDER BY '.$sort;
} else {
    $sort = ' ORDER BY EventName ASC';
}

$name = filter_input(INPUT_GET, 'name');
$description = filter_input(INPUT_GET, 'description');
$startdate = filter_input(INPUT_GET, 'startdate');
$enddate = filter_input(INPUT_GET, 'enddate');
$drstart = filter_input(INPUT_GET, 'drstart');
$drend = filter_input(INPUT_GET, 'drend');
$vrstart = filter_input(INPUT_GET, 'vrstart');
$vrend = filter_input(INPUT_GET, 'vrend');
$rrstart = filter_input(INPUT_GET, 'rrstart');
$rrend = filter_input(INPUT_GET, 'rrend');

session_start();

$username = $_SESSION['username'];

$connection = mysqli_connect(
    $_SERVER['DB_SERVERNAME'],
    $_SERVER['DB_USERNAME'],
    $_SERVER['DB_PASSWORD'],
    $_SERVER['DB_DATABASE']
);

$sitequery = mysqli_prepare($connection, "SELECT SiteName FROM Site WHERE ManagerUsername=?");
mysqli_stmt_bind_param($sitequery, 's', $username);
mysqli_stmt_execute($sitequery);
mysqli_stmt_bind_result($sitequery, $site);
mysqli_stmt_fetch($sitequery);
mysqli_stmt_close($sitequery);

if (!isset($site)) {
    mysqli_close($connection);
    header('Location: ./home.php?error=manageevent');
    exit();
}

$events = [];

$query = mysqli_prepare($connection, "SELECT EventName, StartDate, EndDate, Description, EventPrice, DATEDIFF(EndDate, StartDate) + 1 as Duration, (SELECT COUNT(*) FROM AssignTo WHERE AssignTo.EventName=Event.EventName AND AssignTo.StartDate=Event.StartDate AND AssignTo.SiteName=Event.SiteName) AS StaffCount, (SELECT COUNT(*) FROM VisitEvent WHERE VisitEvent.EventName=Event.EventName AND VisitEvent.StartDate=Event.StartDate AND VisitEvent.SiteName=Event.SiteName) AS VisitCount, ((SELECT COUNT(*) FROM VisitEvent WHERE VisitEvent.EventName=Event.EventName AND VisitEvent.StartDate=Event.StartDate AND VisitEvent.SiteName=Event.SiteName) * EventPrice) AS Revenue FROM Event WHERE SiteName=?".$sort);
mysqli_stmt_bind_param($query, 's', $site);
mysqli_stmt_execute($query);
mysqli_stmt_bind_result($query, $resultname, $resultstartdate, $resultenddate, $resultdescription, $resultprice, $resultduration, $resultstaffcount, $resultvisitcount, $resultrevenue);
while (mysqli_stmt_fetch($query)) {
    array_push($events, array('name' => $resultname, 'startdate' => $resultstartdate, 'enddate' => $resultenddate, 'siteName' => $site, 'description' => $resultdescription, 'price' => $resultprice, 'duration' => $resultduration, 'staffCount' => $resultstaffcount, 'visitCount' => $resultvisitcount, 'revenue' => $resultrevenue));
}
mysqli_stmt_close($query);

?>

<?php include('header.php') ?>

<!-- Page Content -->
<div class="container">
    <div class="row">
        <div class="col-lg-8 offset-lg-2 text-center">
            <h1 class="mt-5">Manage Event</h1>
            <form action="manageevent.php" method="GET">
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
                            <label class="col-sm-4 col-form-label">Duration Range</label>
                            <div class="col-sm-8">
                                <div class="row">
                                    <div class="col-md-5">
                                        <input type="number" class="form-control" name="drstart" value="<?= $drstart ?>">
                                    </div>
                                    <div class="col-md-2">
                                        <b>-</b>
                                    </div>
                                    <div class="col-md-5">
                                        <input type="number" class="form-control" name="drend" value="<?= $drend ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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
                                        <input type="number" class="form-control" name="vrend" value="<?= $vrend ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Total Revenue Range</label>
                            <div class="col-sm-8">
                                <div class="row">
                                    <div class="col-md-5">
                                        <input type="number" class="form-control" name="rrstart" value="<?= $rrstart ?>">
                                    </div>
                                    <div class="col-md-2">
                                        <b>-</b>
                                    </div>
                                    <div class="col-md-5">
                                        <input type="number" class="form-control" name="rrend" value="<?= $rrend ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <div class="col-sm-12 text-center">
                                <input type="hidden" name="sort" value="<?= filter_input(INPUT_GET, 'sort') ?>">
                                <button type="submit" class="btn btn-primary">Filter</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <form onsubmit="return verify()">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col"></th>
                                <th scope="col">Name <a href="<?php echo "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"?>&sort=EventName+ASC"><i class="fas fa-chevron-up"></i></a> <a href="<?php echo "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"?>&sort=EventName+DESC"><i class="fas fa-chevron-down"></i></a></th>
                                <th scope="col">Staff Count <a href="<?php echo "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"?>&sort=StaffCount+ASC"><i class="fas fa-chevron-up"></i></a> <a href="<?php echo "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"?>&sort=StaffCount+DESC"><i class="fas fa-chevron-down"></i></a></th>
                                <th scope="col">Duration (days) <a href="<?php echo "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"?>&sort=Duration+ASC"><i class="fas fa-chevron-up"></i></a> <a href="<?php echo "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"?>&sort=Duration+DESC"><i class="fas fa-chevron-down"></i></a></th>
                                <th scope="col">Total Visits <a href="<?php echo "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"?>&sort=VisitCount+ASC"><i class="fas fa-chevron-up"></i></a> <a href="<?php echo "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"?>&sort=VisitCount+DESC"><i class="fas fa-chevron-down"></i></a></th>
                                <th scope="col">Total Revenue ($) <a href="<?php echo "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"?>&sort=Revenue+ASC"><i class="fas fa-chevron-up"></i></a> <a href="<?php echo "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"?>&sort=Revenue+DESC"><i class="fas fa-chevron-down"></i></a></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            foreach ($events as $event) {
                                if (isset($name)
                                    && ($name == '' || $name == null || stripos($event['name'], $name) || strcasecmp($event['name'], $name) == 0)
                                    && ($description == '' || $description == null || stripos($event['description'], $description) || strcasecmp($event['description'], $description) == 0)
                                    && ($startdate == '' || $startdate == null || strtotime($startdate) <= strtotime($event['enddate']))
                                    && ($enddate == '' || $enddate == null || strtotime($enddate) >= strtotime($event['startdate']))
                                    && ($drstart == '' || $drstart == null || intval($drstart) <= $event['duration'])
                                    && ($drend == '' || $drend == null || intval($drend) >= $event['duration'])
                                    && ($vrstart == '' || $vrstart == null || intval($vrstart) <= $event['visitCount'])
                                    && ($vrend == '' || $vrend == null || intval($vrend) >= $event['visitCount'])
                                    && ($rrstart == '' || $rrstart == null || intval($rrstart) <= $event['revenue'])
                                    && ($rrend == '' || $rrend == null || intval($rrend) >= $event['revenue'])
                                ) {
                                    echo '<tr><td><input type="radio" name="event" value="' . $event['name'] . ',' . $event['startdate'] . ',' . $event['siteName'] . '"></td><td>' . $event['name'] . '</td><td>' . $event['staffCount'] . '</td><td>' . $event['duration'] . '</td><td>' . $event['visitCount'] . '</td><td>' . $event['revenue'] . '</td></tr>';
                                }
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <input type="hidden" name="site" value="<?= $site ?>">
                <div class="row">
                    <div class="col-md-3 text-center">
                        <a href="home.php" class="btn btn-primary">Back</a>
                    </div>
                    <div class="col-md-3 text-center">
                        <button type="submit" class="btn btn-primary" formaction="createevent.php" formmethod="GET" value="create">Create</button>
                    </div>
                    <div class="col-md-3 text-center">
                        <button type="submit" class="btn btn-primary" formaction="editevent.php" formmethod="GET">View/Edit</button>
                    </div>
                    <div class="col-md-3 text-center">
                        <button type="submit" class="btn btn-primary" formaction="deleteeventscript.php" formmethod="POST">Delete</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function verify() {
        if (typeof $('input[name=event]:checked').val() === 'undefined' && $(document.activeElement).val() !== 'create') {
            return false;
        }
        return true;
    }
</script>

<?php include('footer.php') ?>

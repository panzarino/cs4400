<?php

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

$query = mysqli_prepare($connection, "SELECT EventName, StartDate, SiteName, EventPrice, DATEDIFF(EndDate, StartDate) + 1 as Duration, (SELECT COUNT(*) FROM AssignTo WHERE AssignTo.EventName=Event.EventName AND AssignTo.StartDate=Event.StartDate AND AssignTo.SiteName=Event.SiteName) AS StaffCount, (SELECT COUNT(*) FROM VisitEvent WHERE VisitEvent.EventName=Event.EventName AND VisitEvent.StartDate=Event.StartDate AND VisitEvent.SiteName=Event.SiteName) AS VisitCount FROM Event WHERE SiteName=?");
mysqli_stmt_bind_param($query, 's', $site);
mysqli_stmt_execute($query);
mysqli_stmt_bind_result($query, $resultname, $resultstartdate, $resultsitename, $resultprice, $resultduration, $resultstaffcount, $resultvisitcount);
while (mysqli_stmt_fetch($query)) {
    array_push($events, array('name' => $resultname, 'startdate' => $resultstartdate, 'siteName' => $resultsitename, 'price' => $resultprice, 'duration' => $resultduration, 'staffCount' => $resultstaffcount, 'visitCount' => $resultvisitcount));
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
<!--                <div class="row">-->
<!--                    <div class="col-md-6">-->
<!--                        <div class="form-group row">-->
<!--                            <label class="col-sm-4 col-form-label">Site</label>-->
<!--                            <div class="col-sm-8">-->
<!--                                <select name="site" class="form-control" required>-->
<!--                                    <option value="all" --><?//= $site == 'all' || $site == null ? 'selected' : '';?><!-->All</option>-->
<!--                                    --><?php
//                                    foreach ($sites as $s) {
//                                        if ($site == $s['name']) {
//                                            echo '<option value="'.$s['name'].'" selected>'.$s['name'].'</option>';
//                                        } else {
//                                            echo '<option value="' . $s['name'] . '">' . $s['name'] . '</option>';
//                                        }
//                                    }
//                                    ?>
<!--                                </select>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                    <div class="col-md-6">-->
<!--                        <div class="form-group row">-->
<!--                            <label class="col-sm-4 col-form-label">Manager</label>-->
<!--                            <div class="col-sm-8">-->
<!--                                <select name="manager" class="form-control" required>-->
<!--                                    <option value="all" --><?//= $manager == 'all' || $manager == null ? 'selected' : '';?><!-->All</option>-->
<!--                                    --><?php
//                                    foreach ($managers as $m) {
//                                        if ($manager == $m['username']) {
//                                            echo '<option value="'.$m['username'].'" selected>'.$m['firstName'].' '.$m['lastName'].'</option>';
//                                        } else {
//                                            echo '<option value="'.$m['username'].'">'.$m['firstName'].' '.$m['lastName'].'</option>';
//                                        }
//                                    }
//                                    ?>
<!--                                </select>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
<!--                <div class="row">-->
<!--                    <div class="col-md-6">-->
<!--                        <div class="form-group row">-->
<!--                            <label class="col-sm-4 col-form-label">Open Everyday</label>-->
<!--                            <div class="col-sm-8">-->
<!--                                <select name="open" class="form-control" required>-->
<!--                                    <option value="all" --><?//= $open == 'all' || $open == null ? 'selected' : '';?><!-->All</option>-->
<!--                                    <option value="yes" --><?//= $open == 'yes' ? 'selected' : '';?><!-->Yes</option>-->
<!--                                    <option value="no" --><?//= $open == 'no' ? 'selected' : '';?><!-->No</option>-->
<!--                                </select>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                    <div class="col-md-6">-->
<!--                        <div class="form-group row">-->
<!--                            <div class="col-sm-12 text-center">-->
<!--                                <button type="submit" class="btn btn-primary">Filter</button>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
            </form>
            <form onsubmit="return verify()">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col"></th>
                                <th scope="col">Name</th>
                                <th scope="col">Staff Count</th>
                                <th scope="col">Duration (days)</th>
                                <th scope="col">Total Visits</th>
                                <th scope="col">Total Revenue ($)</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            foreach ($events as $event) {
                                echo '<tr><td><input type="radio" name="event" value="' . $event['name'] . ',' . $event['startdate'] . ',' . $event['siteName'] . '"></td><td>' . $event['name'] . '</td><td>' . $event['staffCount'] . '</td><td>' . $event['duration'] . '</td><td>' . $event['visitCount'] . '</td><td>' . $event['visitCount'] * $event['price'] . '</td></tr>';
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
                        <button type="submit" class="btn btn-primary" formaction="editsite.php" formmethod="GET">View/Edit</button>
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

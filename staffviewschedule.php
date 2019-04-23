<?php
session_start();
$username = $_SESSION['username'];
$event = filter_input(INPUT_GET, 'event');
$eventq = "%" . $event . "%";
$keyword = filter_input(INPUT_GET, 'keyword');
$keywordq = "%" . $keyword . "%";
$start = filter_input(INPUT_GET, 'start');
if (strlen($start) == 0) $start = 0;
$end = filter_input(INPUT_GET, 'end');
if (strlen($end) == 0) $end = PHP_INT_MAX;

$connection = mysqli_connect(
    $_SERVER['DB_SERVERNAME'],
    $_SERVER['DB_USERNAME'],
    $_SERVER['DB_PASSWORD'],
    $_SERVER['DB_DATABASE']
);

$rows = [];
$query = mysqli_prepare($connection, "SELECT Event.EventName, Event.StartDate, EndDate, Event.SiteName, (SELECT COUNT(*) FROM AssignTo as A
                                            WHERE A.EventName=AssignTo.EventName AND A.StartDate=AssignTo.StartDate AND A.SiteName=AssignTo.SiteName) as StaffCount 
                                            FROM AssignTo
                                            JOIN EVENT ON AssignTo.EventName = Event.EventName AND AssignTo.SiteName = Event.SiteName AND AssignTo.StartDate = Event.StartDate 
                                                AND Event.EventName LIKE ? AND Event.Description LIKE ? 
                                                AND Event.StartDate <= ? AND EndDate >= ?
                                            WHERE StaffUsername=?");
mysqli_stmt_bind_param($query, 'sssss', $eventq, $keywordq, $end, $start, $username);
mysqli_stmt_execute($query);
mysqli_stmt_bind_result($query, $resevent, $resstart, $resend, $ressite, $resstaffcnt);
while (mysqli_stmt_fetch($query)) {
    array_push($rows, array('event' => $resevent, 'site' => $ressite, 'start' => $resstart, 'end' => $resend, 'staff' => $resstaffcnt));
}
mysqli_stmt_close($query);


mysqli_close($connection);
?>

<?php include('header.php') ?>

<!-- Page Content -->
<div class="container">
    <div class="row">
        <div class="col-lg-8 offset-lg-2 text-center">
            <h1 class="mt-5">View Schedule</h1>
            <br/>
            <form action="staffviewschedule.php" method="GET">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Event Name</label>
                            <div class="col-sm-8">
                                <input value="<?= $event ?>" class="form-control" type="text" name="event">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Description Keyword</label>
                            <div class="col-sm-8">
                                <input value="<?= $keyword ?>" class="form-control" type="text" name="keyword">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Start Date</label>
                            <div class="col-sm-8">
                                <input value="<?= $start ?>"  type="date" class="form-control" name="start">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">End Date</label>
                            <div class="col-sm-8">
                                <input value="<?= $end ?>"  type="date" class="form-control" name="end">
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
                                <th scope="col">Event Name</th>
                                <th scope="col">Site Name</th>
                                <th scope="col">Start Date</th>
                                <th scope="col">End Date</th>
                                <th scope="col">Staff Count</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            foreach ($rows as $t) {
                                if (isset($event)) {
                                    echo '<tr><td><input type="radio" name="key" value="' . $t['event'] . ',' . $t['start'] . ',' . $t['site'] . '"></td><td>' . $t['event'] . '</td><td>' . $t['site'] . '</td><td>' . $t['start'] . '</td><td>' . $t['end'] . '</td><td>' . $t['staff'] . '</td></tr>';
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
                        <button type="submit" class="btn btn-primary" formaction="staffeventdetail.php" formmethod="GET">View Event</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function verify() {
        if (typeof $('input[name=key]:checked').val() === 'undefined') {
            return false;
        }
        return true;
    }
</script>

<?php include('footer.php') ?>

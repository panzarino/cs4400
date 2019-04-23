<?php

$data = explode(',', filter_input(INPUT_GET, 'event'));
$name = $data[0];
$startdate = $data[1];
$site = $data[2];

$dvstart = filter_input(INPUT_GET, 'dvstart');
$dvend = filter_input(INPUT_GET, 'dvend');
$drstart = filter_input(INPUT_GET, 'drstart');
$drend = filter_input(INPUT_GET, 'drend');

$connection = mysqli_connect(
    $_SERVER['DB_SERVERNAME'],
    $_SERVER['DB_USERNAME'],
    $_SERVER['DB_PASSWORD'],
    $_SERVER['DB_DATABASE']
);

$eventquery = mysqli_prepare($connection, "SELECT EndDate, EventPrice, Capacity, Description, MinStaffRequired FROM Event WHERE EventName=? AND StartDate=? AND SiteName=?");
mysqli_stmt_bind_param($eventquery, 'sss', $name, $startdate, $site);
mysqli_stmt_execute($eventquery);
mysqli_stmt_bind_result($eventquery, $resultenddate, $resultprice, $resultcapacity, $resultdescription, $resultminstaffreq);
mysqli_stmt_fetch($eventquery);
mysqli_stmt_close($eventquery);


$staff = [];
$staffquery = mysqli_prepare($connection, "SELECT 1 AS Selected, Username, Firstname, Lastname FROM AssignTo JOIN User on User.Username=AssignTo.StaffUsername WHERE EventName=? AND StartDate=? AND SiteName=? UNION SELECT 0 AS Selected, User.Username, Firstname, Lastname FROM Staff JOIN User on User.Username=Staff.Username WHERE User.Username NOT IN (SELECT Username FROM AssignTo JOIN User on User.Username=AssignTo.StaffUsername WHERE EventName=? AND StartDate=? AND SiteName=? UNION SELECT Username FROM Staff JOIN AssignTo ON Staff.Username=AssignTo.StaffUsername JOIN Event ON AssignTo.EventName=Event.EventName AND AssignTo.StartDate=Event.StartDate AND AssignTo.SiteName=Event.SiteName WHERE Event.StartDate <= ? AND Event.EndDate >= ?)");
mysqli_stmt_bind_param($staffquery, 'ssssssss', $name, $startdate, $site, $name, $startdate, $site, $startdate, $resultenddate);
mysqli_stmt_execute($staffquery);
mysqli_stmt_bind_result($staffquery, $resultselected, $resultusername, $resultfirstname, $resultlastname);
while (mysqli_stmt_fetch($staffquery)) {
    array_push($staff, array('selected' => $resultselected, 'username' => $resultusername, 'firstName' => $resultfirstname, 'lastName' => $resultlastname));
}
mysqli_stmt_close($staffquery);

$visits = [];
$visitquery = mysqli_prepare($connection, "SELECT VisitEventDate, COUNT(*) FROM VisitEvent WHERE EventName=? AND StartDate=? AND SiteName=? GROUP BY VisitEventDate");
mysqli_stmt_bind_param($visitquery, 'sss', $name, $startdate, $site);
mysqli_stmt_execute($visitquery);
mysqli_stmt_bind_result($visitquery, $resultdate, $resultcount);
while (mysqli_stmt_fetch($visitquery)) {
    array_push($visits, array('date' => $resultdate, 'count' => $resultcount));
}
mysqli_stmt_close($visitquery);

mysqli_close($connection);
?>

<?php include('header.php') ?>

<!-- Page Content -->
<div class="container">
    <div class="row">
        <div class="col-lg-8 offset-lg-2 text-center">
            <h1 class="mt-5">View/Edit Event</h1>
            <form action="editeventscript.php" method="POST" onsubmit="return verify()">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-5 col-form-label">Name</label>
                            <div class="col-sm-7">
                                <p class="text-left mt-2"><b><?= $name ?></b></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-5 col-form-label">Price ($)</label>
                            <div class="col-sm-7">
                                <p class="text-left mt-2"><b><?= $resultprice ?></b></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-5 col-form-label">Start Date</label>
                            <div class="col-sm-7">
                                <p class="text-left mt-2"><b><?= $startdate ?></b></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-5 col-form-label">End Date</label>
                            <div class="col-sm-7">
                                <p class="text-left mt-2"><b><?= $resultenddate ?></b></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-5 col-form-label">Minimum Staff Required</label>
                            <div class="col-sm-7">
                                <p class="text-left mt-2"><b><?= $resultminstaffreq ?></b></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-5 col-form-label">Capacity</label>
                            <div class="col-sm-7">
                                <p class="text-left mt-2"><b><?= $resultcapacity ?></b></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Staff Assigned</label>
                            <div class="col-sm-10">
                                <select class="form-control" multiple id="staff">
                                    <?php
                                    foreach ($staff as $s) {
                                        echo '<option value="'.$s['username'].'" '.($s['selected'] ? 'selected' : '').'>'.$s['firstName'].' '.$s['lastName'].'</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Description</label>
                            <div class="col-sm-10">
                                <textarea class="form-control" name="description" required><?= $resultdescription ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="event" value="<?= filter_input(INPUT_GET, 'event') ?>">
                <input type="hidden" id="staffstr" name="staff">
                <div class="form-group row">
                    <div class="col-sm-6 offset-sm-6 text-center">
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </div>
            </form>
            <div id="errorMessage"></div>
            <br />
            <br />
            <form>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Daily Visits Range</label>
                            <div class="col-sm-8">
                                <div class="row">
                                    <div class="col-md-5">
                                        <input type="number" class="form-control" name="dvstart" value="<?= $dvstart ?>">
                                    </div>
                                    <div class="col-md-2">
                                        <b>-</b>
                                    </div>
                                    <div class="col-md-5">
                                        <input type="number" class="form-control" name="dvend" value="<?= $dvend ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Daily Revenue Range</label>
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
                </div>
                <input type="hidden" name="event" value="<?= filter_input(INPUT_GET, 'event') ?>">
                <div class="form-group row">
                    <div class="col-sm-6 offset-sm-6 text-center">
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col">Date</th>
                                <th scope="col">Daily Visits</th>
                                <th scope="col">Daily Revenue ($)</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $daterange = new DatePeriod(new DateTime($startdate), new DateInterval('P1D'), (new DateTime($resultenddate))->modify('+1 day'));
                            foreach($daterange as $date){
                                $dv = 0;
                                $dr = 0;
                                foreach($visits as $visit) {
                                    if ($visit['date'] == $date->format("Y-m-d")) {
                                        $dv = $visit['count'];
                                        $dr = $visit['count'] * $resultprice;
                                    }
                                }
                                if (($dvstart == '' || $dvstart == null || intval($dvstart) <= $dv)
                                    && ($dvend == '' || $dvend == null || intval($dvend) >= $dv)
                                    && ($drstart == '' || $drstart == null || intval($drstart) <= $dr)
                                    && ($drend == '' || $drend == null || intval($drend) >= $dr)
                                ) {
                                    echo '<tr><td>' . $date->format("Y-m-d") . '</td><td>' . $dv . '</td><td>' . $dr . '</td></tr>';
                                }
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </form>
            <div class="form-group row">
                <div class="col-sm-6 text-center">
                    <a href="manageevent.php" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var staff = [];

    function handleStaff() {
        staff = $('#staff').val();
        var staffString = '';
        staff.forEach(function (staff) {
            staffString += staff + ',';
        });
        staffString = staffString.slice(0, -1);
        $('#staffstr').val(staffString);
    }

    function verify() {
        handleStaff();

        if (staff.length < <?= $resultminstaffreq ?>) {
            $('#errorMessage').html('<div class="alert alert-danger text-center" role="alert" style="margin-top: 30px">Must have at least minimum staff required selected.</div>');
            return false;
        }

        return true;
    }
</script>

<?php include('footer.php') ?>

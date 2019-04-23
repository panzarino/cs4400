<?php

$startdate = filter_input(INPUT_GET, 'startdate');
$enddate = filter_input(INPUT_GET, 'enddate');
$estart = filter_input(INPUT_GET, 'estart');
$eend = filter_input(INPUT_GET, 'eend');
$sstart = filter_input(INPUT_GET, 'sstart');
$send = filter_input(INPUT_GET, 'send');
$vstart = filter_input(INPUT_GET, 'vstart');
$vend = filter_input(INPUT_GET, 'vend');
$rstart = filter_input(INPUT_GET, 'rstart');
$rend = filter_input(INPUT_GET, 'rend');

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
    header('Location: ./home.php?error=sitereport');
    exit();
}

$reports = [];

if (isset($startdate) && isset($enddate)) {
    $reportquery = mysqli_prepare($connection, "SELECT Date, SUM(VisitorCount), SUM(EventCount), SUM(StaffCount), SUM(Revenue) FROM (SELECT V.Date AS Date, COUNT(V.VisitorUsername) AS VisitorCount, COUNT(DISTINCT V.EventName) AS EventCount, COUNT(V.StaffUsername) AS StaffCount, SUM(V.EventPrice) AS Revenue FROM (SELECT VisitEventDate AS Date, VisitorUsername, Event.EventName AS EventName, AssignTo.StaffUsername AS StaffUsername, Event.EventPrice AS EventPrice FROM VisitEvent JOIN Event ON Event.EventName=VisitEvent.EventName AND Event.StartDate=VisitEvent.StartDate AND Event.SiteName=VisitEvent.SiteName JOIN AssignTo ON AssignTo.EventName=VisitEvent.EventName AND AssignTo.StartDate=VisitEvent.StartDate AND AssignTo.SiteName=VisitEvent.SiteName WHERE VisitEvent.SiteName=?) as V GROUP BY V.Date UNION SELECT VisitSiteDate AS Date, COUNT(VisitorUsername) AS VisitorCount, 0 AS EventCount, 0 AS StaffCount, 0 AS Revenue FROM VisitSite WHERE SiteName=? GROUP BY Date) AS RESULT GROUP BY RESULT.Date");
    mysqli_stmt_bind_param($reportquery, 'ss', $site, $site);
    mysqli_stmt_execute($reportquery);
    mysqli_stmt_bind_result($reportquery, $resultdate, $resultvisitcount, $resulteventcount, $resultstaffcount, $resultrevenue);
    while (mysqli_stmt_fetch($reportquery)) {
        array_push($reports, array('date' => $resultdate, 'visitCount' => $resultvisitcount, 'eventCount' => $resulteventcount, 'staffCount' => $resulteventcount, 'revenue' => $resultrevenue));
    }
    mysqli_stmt_close($reportquery);
}

mysqli_close($connection);
?>

<?php include('header.php') ?>

<!-- Page Content -->
<div class="container">
    <div class="row">
        <div class="col-lg-8 offset-lg-2 text-center">
            <h1 class="mt-5">Site Report</h1>
            <form action="sitereport.php" method="GET">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Start Date</label>
                            <div class="col-sm-8">
                                <input type="date" class="form-control" name="startdate" value="<?= $startdate ?>" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">End Date</label>
                            <div class="col-sm-8">
                                <input type="date" class="form-control" name="enddate" value="<?= $enddate ?>" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Event Count Range</label>
                            <div class="col-sm-8">
                                <div class="row">
                                    <div class="col-md-5">
                                        <input type="number" class="form-control" name="estart" value="<?= $estart ?>">
                                    </div>
                                    <div class="col-md-2">
                                        <b>-</b>
                                    </div>
                                    <div class="col-md-5">
                                        <input type="number" class="form-control" name="eend" value="<?= $eend ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Staff Count Range</label>
                            <div class="col-sm-8">
                                <div class="row">
                                    <div class="col-md-5">
                                        <input type="number" class="form-control" name="sstart" value="<?= $sstart ?>">
                                    </div>
                                    <div class="col-md-2">
                                        <b>-</b>
                                    </div>
                                    <div class="col-md-5">
                                        <input type="number" class="form-control" name="send" value="<?= $send ?>">
                                    </div>
                                </div>
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
                                        <input type="number" class="form-control" name="vstart" value="<?= $vstart ?>">
                                    </div>
                                    <div class="col-md-2">
                                        <b>-</b>
                                    </div>
                                    <div class="col-md-5">
                                        <input type="number" class="form-control" name="vend" value="<?= $vend ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Total Revenue Range</label>
                            <div class="col-sm-8">
                                <div class="row">
                                    <div class="col-md-5">
                                        <input type="number" class="form-control" name="rstart" value="<?= $rstart ?>">
                                    </div>
                                    <div class="col-md-2">
                                        <b>-</b>
                                    </div>
                                    <div class="col-md-5">
                                        <input type="number" class="form-control" name="rend" value="<?= $rend ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
            <form action="dailydetail.php" method="GET" onsubmit="return verify()">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table">
                            <thead>
                            <?php
                            if (isset($startdate) && isset($enddate)) {
                                echo '<tr><th scope="col"></th><th scope="col">Date</th><th scope="col">Event Count</th><th scope="col">Staff Count</th><th scope="col">Total Visits</th><th scope="col">Total Revenue ($)</th></tr>';
                            }
                            ?>
                            </thead>
                            <tbody>
                            <?php
                            $daterange = new DatePeriod(new DateTime($startdate), new DateInterval('P1D'), (new DateTime($enddate))->modify('+1 day'));
                            foreach($daterange as $date){
                                foreach ($reports as $report) {
                                    if ($report['date'] == $date->format("Y-m-d")
                                        && ($estart == '' || $estart == null || intval($estart) <= $report['eventCount'])
                                        && ($eend == '' || $eend == null || intval($eend) >= $report['eventCount'])
                                        && ($sstart == '' || $sstart == null || intval($sstart) <= $report['siteCount'])
                                        && ($send == '' || $send == null || intval($send) >= $report['siteCount'])
                                        && ($vstart == '' || $vstart == null || intval($vstart) <= $report['visitCount'])
                                        && ($vend == '' || $vend == null || intval($vend) >= $report['visitCount'])
                                        && ($rstart == '' || $rstart == null || intval($rstart) <= $report['revenue'])
                                        && ($rend == '' || $rend == null || intval($rend) >= $report['revenue'])
                                    ) {
                                        echo '<tr><td><input type="radio" name="date" value="' . $report['date'] . '"></td><td>' . $report['date'] . '</td><td>' . $report['eventCount'] . '</td><td>' . $report['staffCount'] . '</td><td>' . $report['visitCount'] . '</td><td>' . $report['revenue'] . '</td></tr>';
                                    }
                                }
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <input type="hidden" name="site" value="<?= $site ?>">
                <div class="row">
                    <div class="col-md-6 text-center">
                        <a href="home.php" class="btn btn-primary">Back</a>
                    </div>
                    <div class="col-md-6 text-center">
                        <?php
                        if (isset($startdate) && isset($enddate)) {
                            echo '<button type="submit" class="btn btn-primary">Daily Detail</button>';
                        }
                        ?>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function verify() {
        if (typeof $('input[name=date]:checked').val() === 'undefined' && $(document.activeElement).val() !== 'create') {
            return false;
        }
        return true;
    }
</script>

<?php include('footer.php') ?>

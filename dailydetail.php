<?php

$date = filter_input(INPUT_GET, 'date');
$site = filter_input(INPUT_GET, 'site');

$connection = mysqli_connect(
    $_SERVER['DB_SERVERNAME'],
    $_SERVER['DB_USERNAME'],
    $_SERVER['DB_PASSWORD'],
    $_SERVER['DB_DATABASE']
);

$events = [];
$query = mysqli_prepare($connection, "SELECT V.EventName, COUNT(V.VisitorUsername), GROUP_CONCAT(DISTINCT V.Name SEPARATOR '<br />'), SUM(V.EventPrice) FROM (SELECT VisitEventDate AS Date, VisitorUsername, Event.EventName AS EventName, AssignTo.StaffUsername AS StaffUsername, Event.EventPrice AS EventPrice, Event.StartDate AS StartDate, Event.SiteName AS SiteName, CONCAT(Firstname, ' ', Lastname) AS Name FROM VisitEvent JOIN Event ON Event.EventName=VisitEvent.EventName AND Event.StartDate=VisitEvent.StartDate AND Event.SiteName=VisitEvent.SiteName JOIN AssignTo ON AssignTo.EventName=VisitEvent.EventName AND AssignTo.StartDate=VisitEvent.StartDate AND AssignTo.SiteName=VisitEvent.SiteName JOIN User ON AssignTo.StaffUsername=User.Username WHERE VisitEvent.SiteName=?) as V WHERE V.Date=? GROUP BY V.EventName, V.StartDate, V.SiteName");
mysqli_stmt_bind_param($query, 'ss', $site, $date);
mysqli_stmt_execute($query);
mysqli_stmt_bind_result($query, $resultname, $resultvisitcount, $resultstaff, $resultrevenue);
while (mysqli_stmt_fetch($query)) {
    array_push($events, array('name' => $resultname, 'visitCount' => $resultvisitcount, 'staff' => $resultstaff, 'revenue' => $resultrevenue));
}
mysqli_stmt_close($query);

mysqli_close($connection);

?>

<?php include('header.php') ?>

<!-- Page Content -->
<div class="container">
    <div class="row">
        <div class="col-lg-8 offset-lg-2 text-center">
            <h1 class="mt-5">Daily Detail</h1>
            <div class="row">
                <div class="col-md-12">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Event Name</th>
                            <th>Staff Names</th>
                            <th>Visits</th>
                            <th>Revenue ($)</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        foreach ($events as $event) {
                            echo '<tr><td>' . $event['name'] . '</td><td>' . $event['staff'] . '</td><td>' . $event['visitCount'] . '</td><td>' . $event['revenue'] . '</td></tr>';
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 text-center">
                    <a href="sitereport.php" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('footer.php') ?>


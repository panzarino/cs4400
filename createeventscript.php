<?php

$name = filter_input(INPUT_POST, 'name');
$price = filter_input(INPUT_POST, 'price');
$capacity = filter_input(INPUT_POST, 'capacity');
$minstaffreq = filter_input(INPUT_POST, 'minstaffreq');
$startdate = filter_input(INPUT_POST, 'startdate');
$enddate = filter_input(INPUT_POST, 'enddate');
$description = filter_input(INPUT_POST, 'description');
$staff = explode(',', filter_input(INPUT_POST, 'staff'));
$site = filter_input(INPUT_POST, 'site');

$connection = mysqli_connect(
    $_SERVER['DB_SERVERNAME'],
    $_SERVER['DB_USERNAME'],
    $_SERVER['DB_PASSWORD'],
    $_SERVER['DB_DATABASE']
);

$conflictquery = mysqli_prepare($connection, "SELECT EventName FROM `Event` WHERE StartDate <= ? AND EndDate >= ? AND SiteName=?");
mysqli_stmt_bind_param($conflictquery, 'sss', $enddate, $startdate, $site);
mysqli_stmt_execute($conflictquery);
mysqli_stmt_bind_result($conflictquery, $conflictresult);
mysqli_stmt_fetch($conflictquery);
mysqli_stmt_close($conflictquery);

if (isset($conflictresult)) {
    mysqli_close($connection);
    header('Location: ./createevent.php?error=conflict');
    exit();
}

foreach ($staff as $s) {
    $staffconflictquery = mysqli_prepare($connection, "SELECT Username FROM Staff JOIN AssignTo ON Staff.Username=AssignTo.StaffUsername JOIN Event ON AssignTo.EventName=Event.EventName AND AssignTo.StartDate=Event.StartDate AND AssignTo.SiteName=Event.SiteName WHERE Event.StartDate <= ? AND Event.EndDate >= ? AND Staff.Username=?");
    mysqli_stmt_bind_param($staffconflictquery, 'sss', $enddate, $startdate, $s);
    mysqli_stmt_execute($staffconflictquery);
    mysqli_stmt_bind_result($staffconflictquery, $staffconflictresult);
    mysqli_stmt_fetch($staffconflictquery);
    mysqli_stmt_close($staffconflictquery);

    if (isset($staffconflictresult)) {
        mysqli_close($connection);
        header('Location: ./createevent.php?error=staffconflict');
        exit();
    }
}

$eventquery = mysqli_prepare($connection, "INSERT INTO `Event`(`EventName`, `StartDate`, `SiteName`, `EndDate`, `EventPrice`, `Capacity`, `Description`, `MinStaffRequired`) VALUES (?,?,?,?,?,?,?,?)");
mysqli_stmt_bind_param($eventquery, 'ssssssss', $name, $startdate, $site, $enddate, $price, $capacity, $description, $minstaffreq);
mysqli_stmt_execute($eventquery);
mysqli_stmt_fetch($eventquery);
mysqli_stmt_close($eventquery);

foreach ($staff as $s) {
    $staffquery = mysqli_prepare($connection, "INSERT INTO `AssignTo`(`StaffUsername`, `EventName`, `StartDate`, `SiteName`) VALUES (?,?,?,?)");
    mysqli_stmt_bind_param($staffquery, 'ssss', $s, $name, $startdate, $site);
    mysqli_stmt_execute($staffquery);
    mysqli_stmt_fetch($staffquery);
    mysqli_stmt_close($staffquery);
}

mysqli_close($connection);
header('Location: ./manageevent.php');
exit();

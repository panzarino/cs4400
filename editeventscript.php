<?php

$data = explode(',', filter_input(INPUT_POST, 'event'));
$name = $data[0];
$startdate = $data[1];
$site = $data[2];
$description = filter_input(INPUT_POST, 'description');
$staff = explode(',', filter_input(INPUT_POST, 'staff'));

// Create connection
$connection = mysqli_connect(
    $_SERVER['DB_SERVERNAME'],
    $_SERVER['DB_USERNAME'],
    $_SERVER['DB_PASSWORD'],
    $_SERVER['DB_DATABASE']
);

$query = mysqli_prepare($connection, "UPDATE Event SET Description=? WHERE EventName=? AND StartDate=? AND SiteName=?");
mysqli_stmt_bind_param($query, 'ssss', $description, $name, $startdate, $site);
mysqli_stmt_execute($query);
mysqli_stmt_fetch($query);
mysqli_stmt_close($query);

$deletestaffquery = mysqli_prepare($connection, "DELETE FROM AssignTo WHERE EventName=? AND StartDate=? AND SiteName=?");
mysqli_stmt_bind_param($deletestaffquery, 'sss', $name, $startdate, $site);
mysqli_stmt_execute($deletestaffquery);
mysqli_stmt_fetch($deletestaffquery);
mysqli_stmt_close($deletestaffquery);

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

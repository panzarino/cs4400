<?php

session_start();
$username = $_SESSION['username'];

// getting post data
$event = filter_input(INPUT_POST, 'event');
$start = filter_input(INPUT_POST, 'start');
$site = filter_input(INPUT_POST, 'site');
$date = filter_input(INPUT_POST, 'date');

// Create connection
$connection = mysqli_connect(
    $_SERVER['DB_SERVERNAME'],
    $_SERVER['DB_USERNAME'],
    $_SERVER['DB_PASSWORD'],
    $_SERVER['DB_DATABASE']
);

// check if same visit logged on same day
$query = mysqli_prepare($connection, "SELECT VisitorUsername FROM VisitEvent 
                                                WHERE VisitorUsername=? AND EventName=? AND SiteName=? AND StartDate=? AND VisitEventDate=?");
mysqli_stmt_bind_param($query, 'sssss', $username, $event, $site, $start, $date);
mysqli_stmt_execute($query);
mysqli_stmt_bind_result($query, $founduser);
mysqli_stmt_fetch($query);
mysqli_stmt_close($query);

// log visitevent in db if user didn't take same one today
if (!isset($founduser)) {
    $query = mysqli_prepare($connection, "INSERT INTO VisitEvent (VisitorUsername, EventName, SiteName, StartDate, VisitEventDate) VALUES (?,?,?,?,?)");
    mysqli_stmt_bind_param($query, 'sssss', $username, $event, $site, $start, $date);
    mysqli_stmt_execute($query);
    mysqli_stmt_close($query);
}


// redirect
mysqli_close($connection);
header('Location: ./exploreevent.php');
exit();

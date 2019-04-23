<?php

session_start();
$username = $_SESSION['username'];

// getting post data
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
$query = mysqli_prepare($connection, "SELECT VisitorUsername FROM VisitSite WHERE VisitorUsername=? AND SiteName=? AND VisitSiteDate=?");
mysqli_stmt_bind_param($query, 'sss', $username,$site, $date);
mysqli_stmt_execute($query);
mysqli_stmt_bind_result($query, $founduser);
mysqli_stmt_fetch($query);
mysqli_stmt_close($query);

if (!isset($founduser)) {
    $query = mysqli_prepare($connection, "INSERT INTO VisitSite (VisitorUsername, SiteName, VisitSiteDate) VALUES (?,?,?)");
    mysqli_stmt_bind_param($query, 'sss', $username,$site, $date);
    mysqli_stmt_execute($query);
    mysqli_stmt_close($query);
}


// redirect
mysqli_close($connection);
header('Location: ./exploresite.php');
exit();

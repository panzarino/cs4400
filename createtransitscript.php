<?php
// getting post data
$type = filter_input(INPUT_POST, 'transportType');
$route = filter_input(INPUT_POST, 'route');
$price = filter_input(INPUT_POST, 'price');
$sites = explode(',', filter_input(INPUT_POST, 'sites'));

// Create connection
$connection = mysqli_connect(
    $_SERVER['DB_SERVERNAME'],
    $_SERVER['DB_USERNAME'],
    $_SERVER['DB_PASSWORD'],
    $_SERVER['DB_DATABASE']
);

// add transit to db
$query = mysqli_prepare($connection, "INSERT INTO Transit (TransitType, TransitRoute, TransitPrice) VALUES (?,?,?)");
mysqli_stmt_bind_param($query, 'sss', $type, $route, $price);
mysqli_stmt_execute($query);
mysqli_stmt_fetch($query);
mysqli_stmt_close($query);

// add connected sites
$sitesquery = mysqli_prepare($connection, "INSERT INTO Connect (SiteName, TransitType, TransitRoute) VALUES (?,?,?)");
foreach ($sites as $site) {
    mysqli_stmt_bind_param($sitesquery, 'sss', $site, $type, $route);
    mysqli_stmt_execute($sitesquery);
}
mysqli_stmt_close($sitesquery);

// redirect
mysqli_close($connection);
header('Location: ./managetransit.php');
exit();

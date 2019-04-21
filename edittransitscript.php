<?php
// getting post data
$route = filter_input(INPUT_POST, 'route');
$oldroute = filter_input(INPUT_POST, 'oldroute');
$price = filter_input(INPUT_POST, 'price');
$type = filter_input(INPUT_POST, 'type');
$sites = explode(',', filter_input(INPUT_POST, 'sites'));

// Create connection
$connection = mysqli_connect(
    $_SERVER['DB_SERVERNAME'],
    $_SERVER['DB_USERNAME'],
    $_SERVER['DB_PASSWORD'],
    $_SERVER['DB_DATABASE']
);

$query = mysqli_prepare($connection, "UPDATE Transit SET TransitRoute=?, TransitPrice=? WHERE TransitType=? AND TransitRoute=?");
mysqli_stmt_bind_param($query, 'sdss', $route, $price, $type, $oldroute);
mysqli_stmt_execute($query);
mysqli_stmt_close($query);

$query = mysqli_prepare($connection, "DELETE FROM Connect WHERE TransitType=? AND TransitRoute=?");
mysqli_stmt_bind_param($query, 'ss', $type, $oldroute);
mysqli_stmt_execute($query);
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

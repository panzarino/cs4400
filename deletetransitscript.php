<?php

$data = explode(',', filter_input(INPUT_POST, 'routetbl'));
$route = $data[0];
$type = $data[1];

// Create connection
$connection = mysqli_connect(
    $_SERVER['DB_SERVERNAME'],
    $_SERVER['DB_USERNAME'],
    $_SERVER['DB_PASSWORD'],
    $_SERVER['DB_DATABASE']
);


$query = mysqli_prepare($connection, "DELETE FROM Transit WHERE TransitType=? AND TransitRoute=?");
mysqli_stmt_bind_param($query, 'ss', $type, $route);
mysqli_stmt_execute($query);
mysqli_stmt_fetch($query);
mysqli_stmt_close($query);

// redirect
mysqli_close($connection);
header('Location: ./managetransit.php');
exit();

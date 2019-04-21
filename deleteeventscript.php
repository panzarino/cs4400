<?php

$data = explode(',', filter_input(INPUT_POST, 'event'));

// Create connection
$connection = mysqli_connect(
    $_SERVER['DB_SERVERNAME'],
    $_SERVER['DB_USERNAME'],
    $_SERVER['DB_PASSWORD'],
    $_SERVER['DB_DATABASE']
);

// check if username exists
$query = mysqli_prepare($connection, "DELETE FROM Event WHERE EventName=? AND StartDate=? AND SiteName=?");
mysqli_stmt_bind_param($query, 'sss', $data[0], $data[1], $data[2]);
mysqli_stmt_execute($query);
mysqli_stmt_fetch($query);
mysqli_stmt_close($query);

// redirect
mysqli_close($connection);
header('Location: ./manageevent.php');
exit();

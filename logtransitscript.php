<?php

session_start();
$username = $_SESSION['username'];

// getting post data
$key = explode(',', filter_input(INPUT_POST, 'routetbl'));
$route = $key[0];
$type = $key[1];
$date = filter_input(INPUT_POST, 'date');

// Create connection
$connection = mysqli_connect(
    $_SERVER['DB_SERVERNAME'],
    $_SERVER['DB_USERNAME'],
    $_SERVER['DB_PASSWORD'],
    $_SERVER['DB_DATABASE']
);

// log transit in db
$query = mysqli_prepare($connection, "INSERT INTO TakeTransit (Username, TransitType, TransitRoute, TransitDate) VALUES (?,?,?,?)");
mysqli_stmt_bind_param($query, 'ssss', $username, $type, $route, $date);
mysqli_stmt_execute($query);
mysqli_stmt_close($query);


// redirect
mysqli_close($connection);
header('Location: ./taketransit.php');
exit();

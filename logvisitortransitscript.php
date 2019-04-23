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

// check if same transit logged on same day
$query = mysqli_prepare($connection, "SELECT Username FROM TakeTransit 
                                                WHERE Username=? AND TransitType=? AND TransitRoute=? AND TransitDate=?");
mysqli_stmt_bind_param($query, 'ssss', $username, $type, $route, $date);
mysqli_stmt_execute($query);
mysqli_stmt_bind_result($query, $founduser);
mysqli_stmt_fetch($query);
mysqli_stmt_close($query);

// log transit in db if user didn't take same one today
if (!isset($founduser)) {
    $query = mysqli_prepare($connection, "INSERT INTO TakeTransit (Username, TransitType, TransitRoute, TransitDate) VALUES (?,?,?,?)");
    mysqli_stmt_bind_param($query, 'ssss', $username, $type, $route, $date);
    mysqli_stmt_execute($query);
    mysqli_stmt_close($query);
}


// redirect
mysqli_close($connection);
header('Location: ./exploresite.php');
exit();

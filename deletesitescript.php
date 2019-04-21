<?php
// getting post data
$site = filter_input(INPUT_POST, 'site');

// Create connection
$connection = mysqli_connect(
    $_SERVER['DB_SERVERNAME'],
    $_SERVER['DB_USERNAME'],
    $_SERVER['DB_PASSWORD'],
    $_SERVER['DB_DATABASE']
);

// check if username exists
$query = mysqli_prepare($connection, "DELETE FROM Site WHERE SiteName=?");
mysqli_stmt_bind_param($query, 's', $site);
mysqli_stmt_execute($query);
mysqli_stmt_fetch($query);
mysqli_stmt_close($query);

// redirect
mysqli_close($connection);
header('Location: ./managesite.php');
exit();

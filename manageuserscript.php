<?php
// getting post data
$username = filter_input(INPUT_POST, 'username');
$status = filter_input(INPUT_POST, 'status');

// Create connection
$connection = mysqli_connect(
    $_SERVER['DB_SERVERNAME'],
    $_SERVER['DB_USERNAME'],
    $_SERVER['DB_PASSWORD'],
    $_SERVER['DB_DATABASE']
);

$query = mysqli_prepare($connection, "UPDATE User SET Status=? WHERE Username=?");
mysqli_stmt_bind_param($query, 'ss', $status, $username);
mysqli_stmt_execute($query);
mysqli_stmt_fetch($query);
mysqli_stmt_close($query);

// redirect
mysqli_close($connection);
header('Location: ./manageuser.php?updated='.$username);
exit();

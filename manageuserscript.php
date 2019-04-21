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

if ($status == 'approved') {
    $id = strval(mt_rand(100000000,999999999));
    $queryemployee = mysqli_prepare($connection, "UPDATE Employee SET EmployeeID=? WHERE Username=?");
    mysqli_stmt_bind_param($queryemployee, 'ss', $id, $username);
    mysqli_stmt_execute($queryemployee);
    mysqli_stmt_fetch($queryemployee);
    mysqli_stmt_close($queryemployee);
}

// redirect
mysqli_close($connection);
header('Location: ./manageuser.php?updated='.$username);
exit();

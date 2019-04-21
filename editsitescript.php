<?php
// getting post data
$oldname = filter_input(INPUT_POST, 'oldname');
$name = filter_input(INPUT_POST, 'name');
$zipcode = filter_input(INPUT_POST, 'zipcode');
$address = filter_input(INPUT_POST, 'address');
$manager = filter_input(INPUT_POST, 'manager');
$open = filter_input(INPUT_POST, 'open') == 'true' ? 1 : 0;

// Create connection
$connection = mysqli_connect(
    $_SERVER['DB_SERVERNAME'],
    $_SERVER['DB_USERNAME'],
    $_SERVER['DB_PASSWORD'],
    $_SERVER['DB_DATABASE']
);

// check if username exists
$query = mysqli_prepare($connection, "UPDATE Site SET SiteName=?, SiteAddress=?, SiteZipcode=?, OpenEveryday=?, ManagerUsername=? WHERE SiteName=?");
mysqli_stmt_bind_param($query, 'sssiss', $name, $address, $zipcode, $open, $manager, $oldname);
mysqli_stmt_execute($query);
mysqli_stmt_fetch($query);
mysqli_stmt_close($query);

// redirect
mysqli_close($connection);
header('Location: ./managesite.php');
exit();

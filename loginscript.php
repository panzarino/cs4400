<?php

$email = filter_input(INPUT_POST, 'email');
$password = filter_input(INPUT_POST, 'password');

$connection = mysqli_connect(
    $_SERVER['DB_SERVERNAME'],
    $_SERVER['DB_USERNAME'],
    $_SERVER['DB_PASSWORD'],
    $_SERVER['DB_DATABASE']
);

$emailquery = mysqli_prepare($connection, "SELECT Username FROM UserEmail WHERE Email=? LIMIT 1");
mysqli_stmt_bind_param($emailquery, 's', $email);
mysqli_stmt_execute($emailquery);
mysqli_stmt_bind_result($emailquery, $usernameresult);
mysqli_stmt_fetch($emailquery);
mysqli_stmt_close($emailquery);

if (!isset($usernameresult)) {
    mysqli_close($connection);
    header('Location: ./?error=credentials');
    exit();
}

$userquery = mysqli_prepare($connection, "SELECT Password, Status FROM User WHERE Username=? LIMIT 1");
mysqli_stmt_bind_param($userquery, 's', $usernameresult);
mysqli_stmt_execute($userquery);
mysqli_stmt_bind_result($userquery, $passwordresult, $statusresult);
mysqli_stmt_fetch($userquery);
mysqli_stmt_close($userquery);

if (!password_verify($password, $passwordresult)) {
    mysqli_close($connection);
    header('Location: ./?error=credentials');
    exit();
}
if ($statusresult != 'approved') {
    mysqli_close($connection);
    header('Location: ./?error=approval');
    exit();
}

$visitorquery = mysqli_prepare($connection, "SELECT Username FROM Visitor WHERE Username=? LIMIT 1");
mysqli_stmt_bind_param($visitorquery, 's', $usernameresult);
mysqli_stmt_execute($visitorquery);
mysqli_stmt_bind_result($visitorquery, $visitorresult);
mysqli_stmt_fetch($visitorquery);
mysqli_stmt_close($visitorquery);

$employeequery = mysqli_prepare($connection, "SELECT Username FROM Employee WHERE Username=? LIMIT 1");
mysqli_stmt_bind_param($employeequery, 's', $usernameresult);
mysqli_stmt_execute($employeequery);
mysqli_stmt_bind_result($employeequery, $employeeresult);
mysqli_stmt_fetch($employeequery);
mysqli_stmt_close($employeequery);

if (isset($employeeresult)) {
    $adminquery = mysqli_prepare($connection, "SELECT Username FROM Administrator WHERE Username=? LIMIT 1");
    mysqli_stmt_bind_param($adminquery, 's', $usernameresult);
    mysqli_stmt_execute($adminquery);
    mysqli_stmt_bind_result($adminquery, $adminresult);
    mysqli_stmt_fetch($adminquery);
    mysqli_stmt_close($adminquery);

    if (!isset($adminresult)) {
        $managerquery = mysqli_prepare($connection, "SELECT Username FROM Manager WHERE Username=? LIMIT 1");
        mysqli_stmt_bind_param($managerquery, 's', $usernameresult);
        mysqli_stmt_execute($managerquery);
        mysqli_stmt_bind_result($managerquery, $managerresult);
        mysqli_stmt_fetch($managerquery);
        mysqli_stmt_close($managerquery);

        if (!isset($adminresult)) {
            $staffquery = mysqli_prepare($connection, "SELECT Username FROM Staff WHERE Username=? LIMIT 1");
            mysqli_stmt_bind_param($staffquery, 's', $usernameresult);
            mysqli_stmt_execute($staffquery);
            mysqli_stmt_bind_result($staffquery, $staffresult);
            mysqli_stmt_fetch($staffquery);
            mysqli_stmt_close($staffquery);
        }
    }
}

session_start();

$_SESSION['username'] = $usernameresult;

if (isset($visitorresult)) {
    if (isset($adminresult)) {
        $_SESSION['type'] = 'administrator-visitor';
    }
    if (isset($managerresult)) {
        $_SESSION['type'] = 'manager-visitor';
    }
    if (isset($staffresult)) {
        $_SESSION['type'] = 'staff-visitor';
    }
    $_SESSION['type'] = 'visitor';
}
if (isset($adminresult)) {
    $_SESSION['type'] = 'administrator';
}
if (isset($managerresult)) {
    $_SESSION['type'] = 'manager';
}
if (isset($staffresult)) {
    $_SESSION['type'] = 'staff';
}
$_SESSION['type'] = 'user';

header('Location: ./home.php');
exit();

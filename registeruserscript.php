<?php
// getting post data
$firstName = filter_input(INPUT_POST, 'firstName');
$lastName = filter_input(INPUT_POST, 'lastName');
$username = filter_input(INPUT_POST, 'username');
$password = filter_input(INPUT_POST, 'password');
$emails = explode(',', filter_input(INPUT_POST, 'emails'));

// Create connection
$connection = mysqli_connect(
    $_SERVER['DB_SERVERNAME'],
    $_SERVER['DB_USERNAME'],
    $_SERVER['DB_PASSWORD'],
    $_SERVER['DB_DATABASE']
);

// check if username exists
$usernamequery = mysqli_prepare($connection, "SELECT Username FROM User WHERE Username=?");
mysqli_stmt_bind_param($usernamequery, 's', $username);
mysqli_stmt_execute($usernamequery);
mysqli_stmt_bind_result($usernamequery, $usernameresult);
mysqli_stmt_fetch($usernamequery);
mysqli_stmt_close($usernamequery);

if ($usernameresult == $username) {
    mysqli_close($connection);
    header('Location: ./registeruser.php?username=error');
    exit();
}

// check if email exists
$emailerror = '';
$emailquery = mysqli_prepare($connection, "SELECT Email FROM UserEmail WHERE Email=?");
foreach ($emails as $email) {
    mysqli_stmt_bind_param($emailquery, 's', $email);
    mysqli_stmt_execute($emailquery);
    mysqli_stmt_bind_result($emailquery, $emailresult);
    mysqli_stmt_fetch($emailquery);
    if (isset($emailresult)) {
        $emailerror .= $emailresult . ', ';
    }
}
mysqli_stmt_close($emailquery);

if ($emailerror != '') {
    $emailerror = substr($emailerror, 0, -2);
    mysqli_close($connection);
    header('Location: ./registeruser.php?email='.$emailerror);
    exit();
}

// create user in database
$hash = password_hash($password, PASSWORD_BCRYPT);
$status = 'pending';

$userquery = mysqli_prepare($connection, "INSERT INTO User (`Username`, `Password`, `Status`, `Firstname`, `Lastname`) VALUES (?,?,?,?,?)");
mysqli_stmt_bind_param($userquery, 'sssss', $username, $hash, $status, $firstName, $lastName);
mysqli_stmt_execute($userquery);
mysqli_stmt_close($userquery);

// create emails in database
$emailinserquery = mysqli_prepare($connection, "INSERT INTO UserEmail (`Username`, `Email`) VALUES (?, ?)");
foreach ($emails as $email) {
    mysqli_stmt_bind_param($emailinserquery, 'ss', $username, $email);
    mysqli_stmt_execute($emailinserquery);
}
mysqli_stmt_close($emailinserquery);

// redirect
mysqli_close($connection);
header('Location: ./registeruser.php?success=true');
exit();

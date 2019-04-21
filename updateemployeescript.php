<?php
session_start();

// getting post data
$firstName = filter_input(INPUT_POST, 'firstName');
$lastName = filter_input(INPUT_POST, 'lastName');
$emails = explode(',', filter_input(INPUT_POST, 'emails'));
$phone = filter_input(INPUT_POST, 'phone');
$username = $_SESSION['username'];

// Create connection
$connection = mysqli_connect(
    $_SERVER['DB_SERVERNAME'],
    $_SERVER['DB_USERNAME'],
    $_SERVER['DB_PASSWORD'],
    $_SERVER['DB_DATABASE']
);

// check if phone exists
$phonequery = mysqli_prepare($connection, "SELECT Phone FROM Employee WHERE Phone=? AND Username!=? LIMIT 1");
mysqli_stmt_bind_param($phonequery, 'ss', $phone, $username);
mysqli_stmt_execute($phonequery);
mysqli_stmt_bind_result($phonequery, $phoneresult);
mysqli_stmt_fetch($phonequery);
mysqli_stmt_close($phonequery);

if ($phoneresult == $phone) {
    mysqli_close($connection);
    header('Location: ./manageprofile.php?phone=error');
    exit();
}

// check if email exists
$emailerror = '';
$emailquery = mysqli_prepare($connection, "SELECT Email FROM UserEmail WHERE Email=? AND Username!=?");
foreach ($emails as $email) {
    mysqli_stmt_bind_param($emailquery, 'ss', $email, $username);
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
    header('Location: ./manageprofile.php?email='.$emailerror);
    exit();
}

// update user in database
$userquery = mysqli_prepare($connection, "UPDATE User SET Firstname=?, Lastname=? WHERE Username=?");
mysqli_stmt_bind_param($userquery, 'sss', $firstName, $lastName, $username);
mysqli_stmt_execute($userquery);
mysqli_stmt_close($userquery);

// update employee in database
$employeequery = mysqli_prepare($connection, "UPDATE Employee SET Phone=? WHERE Username=?");
mysqli_stmt_bind_param($employeequery, 'ss', $phone, $username);
mysqli_stmt_execute($employeequery);
mysqli_stmt_close($employeequery);

// delete user's old emails
$emaildeletequery = mysqli_prepare($connection, "DELETE FROM UserEmail WHERE Username=?");
mysqli_stmt_bind_param($emaildeletequery, 's', $username);
mysqli_stmt_execute($emaildeletequery);
mysqli_stmt_close($emaildeletequery);

// insert new emails in database
$emailinserquery = mysqli_prepare($connection, "INSERT INTO UserEmail (`Username`, `Email`) VALUES (?, ?)");
foreach ($emails as $email) {
    mysqli_stmt_bind_param($emailinserquery, 'ss', $username, $email);
    mysqli_stmt_execute($emailinserquery);
}
mysqli_stmt_close($emailinserquery);

// redirect
mysqli_close($connection);
header('Location: ./manageprofile.php?success=true');
exit();

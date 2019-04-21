<?php
// getting post data
$firstName = filter_input(INPUT_POST, 'firstName');
$lastName = filter_input(INPUT_POST, 'lastName');
$username = filter_input(INPUT_POST, 'username');
$userType = filter_input(INPUT_POST, 'userType');
$password = filter_input(INPUT_POST, 'password');
$emails = filter_input(INPUT_POST, 'emails');
$phone = filter_input(INPUT_POST, 'phone');
$address = filter_input(INPUT_POST, 'address');
$city = filter_input(INPUT_POST, 'city');
$state = filter_input(INPUT_POST, 'state');
$zipcode = filter_input(INPUT_POST, 'zipcode');

// Create connection
$connection = mysqli_connect(
    $_SERVER['DB_SERVERNAME'],
    $_SERVER['DB_USERNAME'],
    $_SERVER['DB_PASSWORD'],
    $_SERVER['DB_DATABASE']
);

// check if username exists
$usernamequery = mysqli_prepare($connection, "SELECT Username FROM User WHERE Username=? LIMIT 1");
mysqli_stmt_bind_param($usernamequery, 's', $username);
mysqli_stmt_execute($usernamequery);
mysqli_stmt_bind_result($usernamequery, $usernameresult);
mysqli_stmt_fetch($usernamequery);
mysqli_stmt_close($usernamequery);

if ($usernameresult == $username) {
    mysqli_close($connection);
    header('Location: ./registeremployeevisitor.php?username=error');
    exit();
}

// check if phone exists
$phonequery = mysqli_prepare($connection, "SELECT Phone FROM Employee WHERE Phone=? LIMIT 1");
mysqli_stmt_bind_param($phonequery, 's', $phone);
mysqli_stmt_execute($phonequery);
mysqli_stmt_bind_result($phonequery, $phoneresult);
mysqli_stmt_fetch($phonequery);
mysqli_stmt_close($phonequery);

if ($phoneresult == $phone) {
    mysqli_close($connection);
    header('Location: ./registeremployeevisitor.php?phone=error');
    exit();
}

// check if emails exist
$emailquery = mysqli_prepare($connection, "SELECT Email FROM UserEmail WHERE Email IN (?)");
mysqli_stmt_bind_param($emailquery, 's', $emails);
mysqli_stmt_execute($emailquery);
mysqli_stmt_bind_result($emailquery, $emailresult);

$emailerror = '';
while(mysqli_stmt_fetch($emailquery)) {
    $emailerror .= $emailresult . ', ';
}
mysqli_stmt_close($emailquery);

if ($emailerror != '') {
    $emailerror = substr($emailerror, 0, -2);
    mysqli_close($connection);
    header('Location: ./registeremployeevisitor.php?email='.$emailerror);
    exit();
}

// create user in database
$hash = password_hash($password, PASSWORD_BCRYPT);
$status = 'pending';

$userquery = mysqli_prepare($connection, "INSERT INTO User (`Username`, `Password`, `Status`, `Firstname`, `Lastname`) VALUES (?,?,?,?,?)");
mysqli_stmt_bind_param($userquery, 'sssss', $username, $hash, $status, $firstName, $lastName);
mysqli_stmt_execute($userquery);
mysqli_stmt_close($userquery);

// create employee in database
$employeequery = mysqli_prepare($connection, "INSERT INTO Employee (`Username`, `Phone`, `EmployeeAddress`, `EmployeeCity`, `EmployeeState`, `EmployeeZipcode`) VALUES (?,?,?,?,?,?)");
mysqli_stmt_bind_param($employeequery, 'ssssss', $username, $phone, $address, $city, $state, $zipcode);
mysqli_stmt_execute($employeequery);
mysqli_stmt_close($employeequery);

// create visitor in database
$visitorquery = mysqli_prepare($connection, "INSERT INTO Visitor (`Username`) VALUES (?)");
mysqli_stmt_bind_param($visitorquery, 's', $username);
mysqli_stmt_execute($visitorquery);
mysqli_stmt_close($visitorquery);

// update staff/manager table
if ($userType == 'staff') {
    $userTypeQuery = mysqli_prepare($connection, "INSERT INTO Staff (`Username`) VALUES (?)");
} else {
    $userTypeQuery = mysqli_prepare($connection, "INSERT INTO Manager (`Username`) VALUES (?)");
}
mysqli_stmt_bind_param($userTypeQuery, 's', $username);
mysqli_stmt_execute($userTypeQuery);
mysqli_stmt_close($userTypeQuery);


// create emails in database
$emailinserquery = mysqli_prepare($connection, "INSERT INTO UserEmail (`Username`, `Email`) VALUES (?, ?)");
foreach (explode(',', $emails) as $email) {
    mysqli_stmt_bind_param($emailinserquery, 'ss', $username, $email);
    mysqli_stmt_execute($emailinserquery);
}
mysqli_stmt_close($emailinserquery);

// redirect
mysqli_close($connection);
header('Location: ./registeremployeevisitor.php?success=true');
exit();

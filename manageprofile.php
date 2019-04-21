<?php
session_start();

if (!isset($_SESSION['username'])) {
    header('Location: ./');
    exit();
}

$type = $_SESSION['type'];
$username = $_SESSION['username'];

$connection = mysqli_connect(
    $_SERVER['DB_SERVERNAME'],
    $_SERVER['DB_USERNAME'],
    $_SERVER['DB_PASSWORD'],
    $_SERVER['DB_DATABASE']
);

$profilequery = mysqli_prepare($connection, "SELECT Firstname, Lastname, EmployeeID, Phone, EmployeeAddress, EmployeeCity, EmployeeState, EmployeeZipcode 
                                                 FROM Employee
                                                 INNER JOIN User ON Employee.Username=User.Username
                                                 WHERE Employee.Username=? LIMIT 1");
mysqli_stmt_bind_param($profilequery, 's', $username);
mysqli_stmt_execute($profilequery);
mysqli_stmt_bind_result($profilequery, $firstName, $lastName, $employeeID, $phone, $address, $city, $state, $zip);
mysqli_stmt_fetch($profilequery);
mysqli_stmt_close($profilequery);
$address = $address . ", " . $city . ", " . $state . " " . $zip;

if ($type == 'manager' || $type == 'manager-visitor') {
    $sitequery = mysqli_prepare($connection, "SELECT SiteName FROM Site WHERE ManagerUsername=? LIMIT 1");
    mysqli_stmt_bind_param($sitequery, 's', $username);
    mysqli_stmt_execute($sitequery);
    mysqli_stmt_bind_result($sitequery, $siteName);
    mysqli_stmt_fetch($sitequery);
    mysqli_stmt_close($sitequery);
}

$emailquery = mysqli_prepare($connection, "SELECT Email FROM UserEmail WHERE Username=?");
mysqli_stmt_bind_param($emailquery, 's', $username);
mysqli_stmt_execute($emailquery);
mysqli_stmt_bind_result($emailquery, $emailresult);

$emails = '';
while(mysqli_stmt_fetch($emailquery)) {
    $emails .= '"'.$emailresult . '",';
}
mysqli_stmt_close($emailquery);

$visitorquery = mysqli_prepare($connection, "SELECT Username FROM Visitor WHERE Username=?");
mysqli_stmt_bind_param($visitorquery, 's', $username);
mysqli_stmt_execute($visitorquery);
mysqli_stmt_bind_result($visitorquery, $visitor);
mysqli_stmt_fetch($visitorquery);
mysqli_stmt_close($visitorquery);

?>

<?php include('header.php') ?>
<!-- Page Content -->
<div class="container">
    <div class="row">
        <div class="col-lg-8 offset-lg-2 text-center">
            <h1 class="mt-5">Manage Profile</h1>
            <form onsubmit="return verify()" action="updateemployeescript.php" method="POST">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">First Name</label>
                            <div class="col-sm-8">
                                <input type="text" value=<?= $firstName ?> class="form-control" name="firstName" maxlength="20" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Last Name</label>
                            <div class="col-sm-8">
                                <input type="text" value=<?= $lastName ?> class="form-control" name="lastName" maxlength="20" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Username</label>
                            <div class="col-sm-8">
                                <p class="text-left mt-2"><b><?= $username ?></b></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Site Name</label>
                            <div class="col-sm-8">
                                <p class="text-left mt-2"><b><?= $siteName ?></b></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Employee ID</label>
                            <div class="col-sm-8">
                                <p class="text-left mt-2"><b><?= $employeeID ?></b></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label mt">Phone</label>
                            <div class="col-sm-8">
                                <input class="form-control" value="<?= $phone ?>" type="text" name="phone" maxlength="10" >
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Address</label>
                            <div class="col-sm-10">
                                <p class="text-left mt-2"><b><?= $address ?></b></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-3 col-form-label">Emails</label>
                    <div class="col-md-6 offset-md-2">
                        <div class="row" id="emailDisplay">

                        </div>
                        <br />
                        <div class="form-group row">
                            <div class="col-sm-6">
                                <input type="email" class="form-control" name="email" id="email" maxlength="60">
                            </div>
                            <button id="add" type="button" class="col-sm-3 btn btn-outline-secondary" onclick="addEmail()">Add</button>
                        </div>
                    </div>
                </div>
                <input type="hidden" id="emails" name="emails" value="">
                <div class="row">
                    <div class="col-md-12">
                        <input type="checkbox" id="visitorCheckbox" name="visitor" value="true">Visitor Account <br/>
                        <script>
                            if (<?= $visitor == $username ?>) {
                                $("#visitorCheckbox").prop('checked', true);
                            }
                        </script>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-6 text-center">
                        <a href="home.php" class="btn btn-primary">Back</a>
                    </div>
                    <div class="col-sm-6 text-center">
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </div>
            </form>
            <?php
            if (isset($_GET['phone'])) {
                echo '<div class="alert alert-danger text-center" role="alert" style="margin-top: 30px">That phone number is already in use.</div>';
            }
            if (isset($_GET['email'])) {
                echo '<div class="alert alert-danger text-center" role="alert" style="margin-top: 30px">The following emails are already in use: '.htmlspecialchars($_GET['email']).'.</div>';
            }
            if (isset($_GET['success'])) {
                echo '<div class="alert alert-success text-center" role="alert" style="margin-top: 30px">Your account has been updated.</div>';
            }
            ?>
            <div id="errorMessage"></div>
        </div>
    </div>
</div>

<script>
    var emails = [<?= $emails ?>];
    function renderEmails() {
        $('#emailDisplay').html('');
        var emailString = '';
        emails.forEach(function(email, index) {
            $('#emailDisplay').append('<p class="col-sm-6 offset-sm-3">' + email + '</p><button id="add" type="button" class="col-sm-3 btn btn-outline-secondary" onclick="removeEmail(' + index +')">Remove</button>');
            emailString += (email + ',');
        });
        $('#emails').val(emailString.slice(0, -1));
    }

    function addEmail() {
        if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test($('#email').val())) {
            emails.push($('#email').val());
            $('#email').val('');
            renderEmails();
        }
    }

    function removeEmail(index) {
        emails.splice(index, 1);
        renderEmails();
    }

    function verify() {
        if (emails.length === 0) {
            $('#errorMessage').html('<div class="alert alert-danger text-center" role="alert" style="margin-top: 30px">You must add at least one email.</div>');
            return false;
        }
        if ($('#password').val().length < 8) {
            $('#errorMessage').html('<div class="alert alert-danger text-center" role="alert" style="margin-top: 30px">Password must be at least 8 characters.</div>');
            return false;
        }
        if ($('#password').val() !== $('#confirmPassword').val()) {
            $('#errorMessage').html('<div class="alert alert-danger text-center" role="alert" style="margin-top: 30px">Passwords do not match.</div>');
            return false;
        }
        return true;
    }
    renderEmails();
</script>

<?php include('footer.php') ?>

<?php
session_start();

if (!isset($_SESSION['username'])) {
    header('Location: ./');
    exit();
}

$type = $_SESSION['type'];

?>

<?php include('header.php') ?>

<!-- Page Content -->
<div class="container">
    <div class="row">
        <div class="col-lg-6 offset-lg-3 text-center">
            <?php
            if ($type == 'administrator' || $type == 'administrator-visitor') {
                echo '<h1 class="mt-5">Administrator Functionality</h1>';
            } else if ($type == 'manager' || $type == 'manager-visitor') {
                echo '<h1 class="mt-5">Manager Functionality</h1>';
            } else if ($type == 'staff' || $type == 'staff-visitor') {
                echo '<h1 class="mt-5">Staff Functionality</h1>';
            } else if ($type == 'visitor') {
                echo '<h1 class="mt-5">Visitor Functionality</h1>';
            } else {
                echo '<h1 class="mt-5">User Functionality</h1>';
            }
            ?>
            <div class="row">
                <div class="col-md-6 text-center">
                    <a href="taketransit.php" class="btn btn-primary">Take Transit</a>
                    <br />
                    <br />
                </div>
                <div class="col-md-6 text-center">
                    <a href="viewtransithistory.php" class="btn btn-primary">View Transit History</a>
                    <br />
                    <br />
                </div>
                <?php
                if ($type == 'administrator' || $type == 'administrator-visitor' || $type == 'manager' || $type == 'manager-visitor' || $type == 'staff' || $type == 'staff-visitor') {
                    echo '<div class="col-md-6 text-center"><a href="manageprofile.php" class="btn btn-primary">Manage Profile</a><br /><br /></div>';
                }
                if ($type == 'administrator' || $type == 'administrator-visitor') {
                    echo '<div class="col-md-6 text-center"><a href="manageuser.php" class="btn btn-primary">Manage User</a><br /><br /></div>';
                }
                if ($type == 'administrator' || $type == 'administrator-visitor') {
                    echo '<div class="col-md-6 text-center"><a href="managetransit.php" class="btn btn-primary">Manage Transit</a><br /><br /></div>';
                }
                if ($type == 'administrator' || $type == 'administrator-visitor') {
                    echo '<div class="col-md-6 text-center"><a href="managesite.php" class="btn btn-primary">Manage Site</a><br /><br /></div>';
                }
                if ($type == 'manager' || $type == 'manager-visitor') {
                    echo '<div class="col-md-6 text-center"><a href="manageevent.php" class="btn btn-primary">Manage Event</a><br /><br /></div>';
                }
                if ($type == 'manager' || $type == 'manager-visitor') {
                    echo '<div class="col-md-6 text-center"><a href="managestaff.php" class="btn btn-primary">Manage Staff</a><br /><br /></div>';
                }
                if ($type == 'manager' || $type == 'manager-visitor') {
                    echo '<div class="col-md-6 text-center"><a href="sitereport.php" class="btn btn-primary">View Site Report</a><br /><br /></div>';
                }
                if ($type == 'staff' || $type == 'staff-visitor') {
                    echo '<div class="col-md-6 text-center"><a href="staffviewschedule.php" class="btn btn-primary">View Schedule</a><br /><br /></div>';
                }
                if ($type == 'administrator-visitor' || $type == 'manager-visitor' || $type == 'staff-visitor' || $type == 'visitor') {
                    echo '<div class="col-md-6 text-center"><a href="exploreevent.php" class="btn btn-primary">Explore Event</a><br /><br /></div>';
                }
                if ($type == 'administrator-visitor' || $type == 'manager-visitor' || $type == 'staff-visitor' || $type == 'visitor') {
                    echo '<div class="col-md-6 text-center"><a href="exploresite.php" class="btn btn-primary">Explore Site</a><br /><br /></div>';
                }
                if ($type == 'administrator-visitor' || $type == 'manager-visitor' || $type == 'staff-visitor' || $type == 'visitor') {
                    echo '<div class="col-md-6 text-center"><a href="" class="btn btn-primary">View Visit History</a><br /><br /></div>';
                }
                ?>
                <div class="col-md-6 text-center">
                    <a href="logoutscript.php" class="btn btn-primary">Back</a>
                    <br />
                    <br />
                </div>
                <?php
                if (isset($_GET['error'])) {
                    if ($_GET['error'] == 'manageevent') {
                        echo '<div class="alert alert-danger text-center" role="alert" style="margin-top: 30px">Cannot access Manage Event because you do not have a site assigned.</div>';
                    }
                    if ($_GET['error'] == 'sitereport') {
                        echo '<div class="alert alert-danger text-center" role="alert" style="margin-top: 30px">Cannot access Site Report because you do not have a site assigned.</div>';
                    }
                }
                ?>
            </div>
        </div>
    </div>
</div>

<?php include('footer.php') ?>


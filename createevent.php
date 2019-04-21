<?php

$site = filter_input(INPUT_GET, 'site');

$connection = mysqli_connect(
    $_SERVER['DB_SERVERNAME'],
    $_SERVER['DB_USERNAME'],
    $_SERVER['DB_PASSWORD'],
    $_SERVER['DB_DATABASE']
);

$staff = [];
$query = mysqli_prepare($connection, "SELECT User.Username, Firstname, Lastname FROM Staff JOIN User ON Staff.Username=User.Username");
mysqli_stmt_execute($query);
mysqli_stmt_bind_result($query, $resultusername, $resultfirstname, $resultlastname);
while (mysqli_stmt_fetch($query)) {
    array_push($staff, array('username' => $resultusername, 'firstName' => $resultfirstname, 'lastName' => $resultlastname));
}
mysqli_stmt_close($query);
mysqli_close($connection);

?>

<?php include('header.php') ?>

<!-- Page Content -->
<div class="container">
    <div class="row">
        <div class="col-lg-8 offset-lg-2 text-center">
            <h1 class="mt-5">Create Event</h1>
            <form action="createeventscript.php" method="POST" onsubmit="return verify()">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Name</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="name" maxlength="20" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group row">
                            <label class="col-sm-6 col-form-label">Price ($)</label>
                            <div class="col-sm-6">
                                <input type="number" class="form-control" name="price" min="0" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group row">
                            <label class="col-sm-6 col-form-label">Capacity</label>
                            <div class="col-sm-6">
                                <input type="number" class="form-control" name="capacity" min="1" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group row">
                            <label class="col-sm-8 col-form-label">Minimum Staff Required</label>
                            <div class="col-sm-4">
                                <input type="number" class="form-control" name="minstaffreq" min="1" id="minstaffreq" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Start Date</label>
                            <div class="col-sm-8">
                                <input type="date" class="form-control" id="startdate" name="startdate" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">End Date</label>
                            <div class="col-sm-8">
                                <input type="date" class="form-control" id="enddate" name="enddate" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Description</label>
                            <div class="col-sm-10">
                                <textarea class="form-control" name="description" required></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Assigned Staff</label>
                            <div class="col-sm-10">
                                <select class="form-control" multiple id="staff">
                                    <?php
                                    foreach ($staff as $s) {
                                        echo '<option value="'.$s['username'].'">'.$s['firstName'].' '.$s['lastName'].'</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="site" value="<?= $site ?>">
                <input type="hidden" id="staffstr" name="staff">
                <div class="form-group row">
                    <div class="col-sm-6 text-center">
                        <a href="manageevent.php" class="btn btn-primary">Back</a>
                    </div>
                    <div class="col-sm-6 text-center">
                        <button type="submit" class="btn btn-primary">Create</button>
                    </div>
                </div>
                <div id="errorMessage"></div>
            </form>
        </div>
    </div>
</div>

<script>
    var staff = [];

    function handleStaff() {
        staff = $('#staff').val();
        var staffString = '';
        staff.forEach(function (staff) {
            staffString += staff + ',';
        });
        staffString = staffString.slice(0, -1);
        $('#staffstr').val(staffString);
    }

    function verify() {
        var startDate = document.getElementById('startdate').valueAsDate;
        var endDate = document.getElementById('enddate').valueAsDate;

        if (endDate < startDate) {
            $('#errorMessage').html('<div class="alert alert-danger text-center" role="alert" style="margin-top: 30px">Start date must be before end date.</div>');
            return false;
        }

        handleStaff();

        if (staff.length < parseInt($('#minstaffreq').val())) {
            $('#errorMessage').html('<div class="alert alert-danger text-center" role="alert" style="margin-top: 30px">Must have at least minimum staff required selected.</div>');
            return false;
        }

        return true;
    }
</script>

<?php include('footer.php') ?>

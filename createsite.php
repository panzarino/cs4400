<?php

$connection = mysqli_connect(
    $_SERVER['DB_SERVERNAME'],
    $_SERVER['DB_USERNAME'],
    $_SERVER['DB_PASSWORD'],
    $_SERVER['DB_DATABASE']
);

$managers = [];
$query = mysqli_prepare($connection, "SELECT User.Username, User.Firstname, User.Lastname FROM Manager JOIN User ON Manager.Username=User.Username WHERE Manager.Username NOT IN (SELECT ManagerUsername FROM Site)");
mysqli_stmt_execute($query);
mysqli_stmt_bind_result($query, $resultusername, $resultfirstname, $resultlastname);
while (mysqli_stmt_fetch($query)) {
    array_push($managers, array('username' => $resultusername, 'firstName' => $resultfirstname, 'lastName' => $resultlastname));
}
mysqli_stmt_close($query);
mysqli_close($connection);

?>

<?php include('header.php') ?>

<!-- Page Content -->
<div class="container">
    <div class="row">
        <div class="col-lg-8 offset-lg-2 text-center">
            <h1 class="mt-5">Create Site</h1>
            <form action="createsitescript.php" method="POST">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Name</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="name" maxlength="40" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Zipcode</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="zipcode" maxlength="5" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Address</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="address" maxlength="40">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Manager</label>
                            <div class="col-sm-8">
                                <select name="manager" class="form-control" required>
                                    <?php
                                    foreach ($managers as $manager) {
                                        echo '<option value="'.$manager['username'].'">'.$manager['firstName'].' '.$manager['lastName'].'</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="open" value="true">
                            <label class="form-check-label">Open Everyday</label>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-6 text-center">
                        <a href="managesite.php" class="btn btn-primary">Back</a>
                    </div>
                    <div class="col-sm-6 text-center">
                        <button type="submit" class="btn btn-primary">Create</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include('footer.php') ?>

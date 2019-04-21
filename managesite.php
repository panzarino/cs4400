<?php

$site = filter_input(INPUT_GET, 'site');
$manager = filter_input(INPUT_GET, 'manager');
$open = filter_input(INPUT_GET, 'open');

$connection = mysqli_connect(
    $_SERVER['DB_SERVERNAME'],
    $_SERVER['DB_USERNAME'],
    $_SERVER['DB_PASSWORD'],
    $_SERVER['DB_DATABASE']
);

$sites = [];
$query = mysqli_prepare($connection, "SELECT SiteName, OpenEveryday, Username, Firstname, Lastname FROM Site JOIN User ON Site.ManagerUsername=User.Username");
mysqli_stmt_execute($query);
mysqli_stmt_bind_result($query, $resultname, $resultopen, $resultusername, $resultfirstname, $resultlastname);
while (mysqli_stmt_fetch($query)) {
    array_push($sites, array('name' => $resultname, 'open' => $resultopen, 'username' => $resultusername, 'firstName' => $resultfirstname, 'lastName' => $resultlastname));
}
mysqli_stmt_close($query);

$managers = [];
$query = mysqli_prepare($connection, "SELECT User.Username, User.Firstname, User.Lastname FROM Manager JOIN User ON Manager.Username=User.Username");
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
            <h1 class="mt-5">Manage Site</h1>
            <form action="managesite.php" method="GET">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Site</label>
                            <div class="col-sm-8">
                                <select name="site" class="form-control" required>
                                    <option value="all" <?= $site == 'all' || $site == null ? 'selected' : '';?>>All</option>
                                    <?php
                                    foreach ($sites as $s) {
                                        if ($site == $s['name']) {
                                            echo '<option value="'.$s['name'].'" selected>'.$s['name'].'</option>';
                                        } else {
                                            echo '<option value="' . $s['name'] . '">' . $s['name'] . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Manager</label>
                            <div class="col-sm-8">
                                <select name="manager" class="form-control" required>
                                    <option value="all" <?= $manager == 'all' || $manager == null ? 'selected' : '';?>>All</option>
                                    <?php
                                    foreach ($managers as $m) {
                                        if ($manager == $m['username']) {
                                            echo '<option value="'.$m['username'].'" selected>'.$m['firstName'].' '.$m['lastName'].'</option>';
                                        } else {
                                            echo '<option value="'.$m['username'].'">'.$m['firstName'].' '.$m['lastName'].'</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Open Everyday</label>
                            <div class="col-sm-8">
                                <select name="open" class="form-control" required>
                                    <option value="all" <?= $open == 'all' || $open == null ? 'selected' : '';?>>All</option>
                                    <option value="yes" <?= $open == 'yes' ? 'selected' : '';?>>Yes</option>
                                    <option value="no" <?= $open == 'no' ? 'selected' : '';?>>No</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <div class="col-sm-12 text-center">
                                <button type="submit" class="btn btn-primary">Filter</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <form onsubmit="return verify()">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col"></th>
                                <th scope="col">Name</th>
                                <th scope="col">Manager</th>
                                <th scope="col">Open Everyday</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            foreach ($sites as $s) {
                                if (($site == 'all' || $site == null || $site == $s['name']) && ($manager == 'all' || $manager == null || $manager == $s['username']) && ($open == 'all' || $open == null || (($open == 'yes') == $s['open'])))
                                    echo '<tr><td><input type="radio" name="site" value="' . $s['name'] . '"></td><td>' . $s['name'] . '</td><td>' . $s['firstName'] . ' ' . $s['lastName'] . '</td><td>' . ($s['open'] ? 'Yes' : 'No') . '</td></tr>';
                                }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 text-center">
                        <a href="home.php" class="btn btn-primary">Back</a>
                    </div>
                    <div class="col-md-3 text-center">
                        <a href="createsite.php" class="btn btn-primary">Create</a>
                    </div>
                    <div class="col-md-3 text-center">
                        <button type="submit" class="btn btn-primary" formaction="editsite.php" formmethod="GET">Edit</button>
                    </div>
                    <div class="col-md-3 text-center">
                        <button type="submit" class="btn btn-primary" formaction="deletesitescript.php" formmethod="POST">Delete</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function verify() {
        if (typeof $('input[name=site]:checked').val() === 'undefined') {
            return false;
        }
        return true;
    }
</script>

<?php include('footer.php') ?>

<?php

session_start();

$username = $_SESSION['username'];

$connection = mysqli_connect(
    $_SERVER['DB_SERVERNAME'],
    $_SERVER['DB_USERNAME'],
    $_SERVER['DB_PASSWORD'],
    $_SERVER['DB_DATABASE']
);

$sitequery = mysqli_prepare($connection, "SELECT SiteName FROM Site WHERE ManagerUsername=?");
mysqli_stmt_bind_param($sitequery, 's', $username);
mysqli_stmt_execute($sitequery);
mysqli_stmt_bind_result($sitequery, $site);
mysqli_stmt_fetch($sitequery);
mysqli_stmt_close($sitequery);

if (!isset($site)) {
    mysqli_close($connection);
    header('Location: ./home.php?error=manageevent');
    exit();
}


?>

<?php include('header.php') ?>

<!-- Page Content -->
<div class="container">
    <div class="row">
        <div class="col-lg-8 offset-lg-2 text-center">
            <h1 class="mt-5">Manage Event</h1>
            <form action="manageevent.php" method="GET">
<!--                <div class="row">-->
<!--                    <div class="col-md-6">-->
<!--                        <div class="form-group row">-->
<!--                            <label class="col-sm-4 col-form-label">Site</label>-->
<!--                            <div class="col-sm-8">-->
<!--                                <select name="site" class="form-control" required>-->
<!--                                    <option value="all" --><?//= $site == 'all' || $site == null ? 'selected' : '';?><!-->All</option>-->
<!--                                    --><?php
//                                    foreach ($sites as $s) {
//                                        if ($site == $s['name']) {
//                                            echo '<option value="'.$s['name'].'" selected>'.$s['name'].'</option>';
//                                        } else {
//                                            echo '<option value="' . $s['name'] . '">' . $s['name'] . '</option>';
//                                        }
//                                    }
//                                    ?>
<!--                                </select>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                    <div class="col-md-6">-->
<!--                        <div class="form-group row">-->
<!--                            <label class="col-sm-4 col-form-label">Manager</label>-->
<!--                            <div class="col-sm-8">-->
<!--                                <select name="manager" class="form-control" required>-->
<!--                                    <option value="all" --><?//= $manager == 'all' || $manager == null ? 'selected' : '';?><!-->All</option>-->
<!--                                    --><?php
//                                    foreach ($managers as $m) {
//                                        if ($manager == $m['username']) {
//                                            echo '<option value="'.$m['username'].'" selected>'.$m['firstName'].' '.$m['lastName'].'</option>';
//                                        } else {
//                                            echo '<option value="'.$m['username'].'">'.$m['firstName'].' '.$m['lastName'].'</option>';
//                                        }
//                                    }
//                                    ?>
<!--                                </select>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
<!--                <div class="row">-->
<!--                    <div class="col-md-6">-->
<!--                        <div class="form-group row">-->
<!--                            <label class="col-sm-4 col-form-label">Open Everyday</label>-->
<!--                            <div class="col-sm-8">-->
<!--                                <select name="open" class="form-control" required>-->
<!--                                    <option value="all" --><?//= $open == 'all' || $open == null ? 'selected' : '';?><!-->All</option>-->
<!--                                    <option value="yes" --><?//= $open == 'yes' ? 'selected' : '';?><!-->Yes</option>-->
<!--                                    <option value="no" --><?//= $open == 'no' ? 'selected' : '';?><!-->No</option>-->
<!--                                </select>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                    <div class="col-md-6">-->
<!--                        <div class="form-group row">-->
<!--                            <div class="col-sm-12 text-center">-->
<!--                                <button type="submit" class="btn btn-primary">Filter</button>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
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
<!--                            <tbody>-->
<!--                            --><?php
//                            foreach ($sites as $s) {
//                                if (($site == 'all' || $site == null || $site == $s['name']) && ($manager == 'all' || $manager == null || $manager == $s['username']) && ($open == 'all' || $open == null || (($open == 'yes') == $s['open'])))
//                                    echo '<tr><td><input type="radio" name="site" value="' . $s['name'] . '"></td><td>' . $s['name'] . '</td><td>' . $s['firstName'] . ' ' . $s['lastName'] . '</td><td>' . ($s['open'] ? 'Yes' : 'No') . '</td></tr>';
//                            }
//                            ?>
<!--                            </tbody>-->
                        </table>
                    </div>
                </div>
                <input type="hidden" name="site" value="<?= $site ?>">
                <div class="row">
                    <div class="col-md-3 text-center">
                        <a href="home.php" class="btn btn-primary">Back</a>
                    </div>
                    <div class="col-md-3 text-center">
                        <button type="submit" class="btn btn-primary" formaction="createevent.php" formmethod="GET" value="create">Create</button>
                    </div>
                    <div class="col-md-3 text-center">
                        <button type="submit" class="btn btn-primary" formaction="editsite.php" formmethod="GET">View/Edit</button>
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
        if (typeof $('input[name=site]:checked').val() === 'undefined' && $(document.activeElement).val() !== 'create') {
            return false;
        }
        return true;
    }
</script>

<?php include('footer.php') ?>

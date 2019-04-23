<?php
session_start();

if (!isset($_SESSION['username'])) {
    header('Location: ./');
    exit();
}

$site = filter_input(INPUT_GET, 'site');

$connection = mysqli_connect(
    $_SERVER['DB_SERVERNAME'],
    $_SERVER['DB_USERNAME'],
    $_SERVER['DB_PASSWORD'],
    $_SERVER['DB_DATABASE']
);

$query = mysqli_prepare($connection, "SELECT SiteAddress, OpenEveryday FROM Site WHERE SiteName=?");
mysqli_stmt_bind_param($query, 's', $site);
mysqli_stmt_execute($query);
mysqli_stmt_bind_result($query, $address, $open);
mysqli_stmt_fetch($query);
mysqli_stmt_close($query);

?>

<?php include('header.php') ?>
<!-- Page Content -->
<div class="container">
    <div class="row">
        <div class="col-lg-8 offset-lg-2 text-center">
            <h1 class="mt-5">Site Detail</h1>
            <br/>
            <form action="logvisitsitescript.php" method="POST">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Site</label>
                            <div class="col-sm-8 mt-2">
                                <p class="text-left"><b><?= $site ?></b></p>
                                <input type="hidden" value="<?= $site ?>" name="site">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Open Everyday</label>
                            <div class="col-sm-8 mt-2">
                                <p class="text-left"><b><?= $open ? 'Yes' : 'No' ?></b></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Address</label>
                            <div class="col-sm-8">
                                <p class="text-left mt-2"><b><?= $address ?></b></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Visit Date</label>
                            <div class="col-sm-10 mt-2">
                                <input type="date" class="form-control" name="date">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <div class="col-sm-12 text-center mt-2">
                                <button type="submit" class="btn btn-primary">Log Visit</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-12 text-center">
                        <a href="exploresite.php" class="btn btn-primary">Back</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include('footer.php') ?>

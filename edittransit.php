<?php

$key = explode(',', filter_input(INPUT_GET, 'routetbl'));
$route = $key[0];
$type = $key[1];

$connection = mysqli_connect(
    $_SERVER['DB_SERVERNAME'],
    $_SERVER['DB_USERNAME'],
    $_SERVER['DB_PASSWORD'],
    $_SERVER['DB_DATABASE']
);

$query = mysqli_prepare($connection, "SELECT TransitPrice FROM Transit WHERE TransitType=? AND TransitRoute=?");
mysqli_stmt_bind_param($query, 'ss', $type, $route);
mysqli_stmt_execute($query);
mysqli_stmt_bind_result($query, $price);
mysqli_stmt_fetch($query);
mysqli_stmt_close($query);

$sites = [];
$query = mysqli_prepare($connection, "SELECT SiteName FROM Site");
mysqli_stmt_execute($query);
mysqli_stmt_bind_result($query, $sitesresult);
while (mysqli_stmt_fetch($query)) {
    array_push($sites, $sitesresult);
}
mysqli_stmt_close($query);

$connected = [];
$query = mysqli_prepare($connection, "SELECT SiteName FROM Connect WHERE TransitType=? AND TransitRoute=?");
mysqli_stmt_bind_param($query, 'ss', $type, $route);
mysqli_stmt_execute($query);
mysqli_stmt_bind_result($query, $consitesresult);
while (mysqli_stmt_fetch($query)) {
    array_push($connected, $consitesresult);
}
mysqli_stmt_close($query);

mysqli_close($connection);

?>

<?php include('header.php') ?>

<!-- Page Content -->
<div class="container">
    <div class="row">
        <div class="col-lg-8 offset-lg-2 text-center">
            <h1 class="mt-5">Edit Transit</h1>
            <form onsubmit="return verify()" action="edittransitscript.php" method="POST">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Transport Type</label>
                            <div class="col-sm-8 mt-2">
                                <input type="hidden" value="<?= $type ?>" name="type">
                                <p><b><?= $type ?></b></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Route</label>
                            <div class="col-sm-6">
                                <input type="hidden" value="<?= $route ?>" name="oldroute">
                                <input type="text" value="<?= $route ?>" class="form-control" name="route" maxlength="20" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group row">
                            <label class="col-sm-6 col-form-label">Price ($)</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" value="<?= $price ?>" name="price" maxlength="11" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Connected Sites</label>
                            <div class="col-sm-8">
                                <select multiple id="sites">
                                    <?php
                                    foreach ($sites as $site) {
                                        if (in_array($site, $connected)) {
                                            echo '<option selected value="'.$site.'">'.$site.'</option>';
                                        } else {
                                            echo '<option value="'.$site.'">'.$site.'</option>';
                                        }
                                    }
                                    ?>
                                </select>
                                <input type="hidden" id="sitestr" name="sites">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-6 text-center">
                        <a href="managetransit.php" class="btn btn-primary">Back</a>
                    </div>
                    <div class="col-sm-6 text-center">
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    let sites = [];
    function handleSites() {
        sites = $('#sites').val();
        let siteString = '';
        sites.forEach(function (site) {
            siteString += site + ',';
        });
        siteString = siteString.slice(0, -1);
        $('#sitestr').val(siteString);
    }

    function verify() {
        handleSites();
        return sites.length >= 2;
    }
</script>

<?php include('footer.php') ?>

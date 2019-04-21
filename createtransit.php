<?php

$connection = mysqli_connect(
    $_SERVER['DB_SERVERNAME'],
    $_SERVER['DB_USERNAME'],
    $_SERVER['DB_PASSWORD'],
    $_SERVER['DB_DATABASE']
);


$query = mysqli_prepare($connection, "SELECT SiteName FROM Site");
mysqli_stmt_execute($query);
mysqli_stmt_bind_result($query, $sitesresult);
$sites = [];
while (mysqli_stmt_fetch($query)) {
    array_push($sites, $sitesresult);
}
mysqli_stmt_close($query);
mysqli_close($connection);

?>

<?php include('header.php') ?>

<!-- Page Content -->
<div class="container">
    <div class="row">
        <div class="col-lg-8 offset-lg-2 text-center">
            <h1 class="mt-5">Create Transit</h1>
            <form onsubmit="return verify()" action="createtransitscript.php" method="POST">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Transport Type</label>
                            <div class="col-sm-8">
                                <select name="transportType" class="form-control" required>
                                    <option value="MARTA">MARTA</option>
                                    <option value="Bus">Bus</option>
                                    <option value="Bike">Bike</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Route</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" name="route" maxlength="20" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group row">
                            <label class="col-sm-6 col-form-label">Price ($)</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" name="price" maxlength="11" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Connected Sites</label>
                            <div class="col-sm-8">
                                <select class="form-control" multiple id="sites">
                                    <?php
                                    foreach ($sites as $site) {
                                        echo '<option value="'.$site.'">'.$site.'</option>';
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
                        <button type="submit" class="btn btn-primary">Create</button>
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

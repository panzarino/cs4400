<?php

$sort = filter_input(INPUT_GET, 'sort');
if (isset($sort) && $sort != '') {
    $sort = ' ORDER BY '.$sort;
} else {
    $sort = ' ORDER BY Name ASC';
}

$site = filter_input(INPUT_GET, 'site');
$first = filter_input(INPUT_GET, 'firstName');
$firstq = "%" . $first . "%";
$last = filter_input(INPUT_GET, 'lastName');
$lastq = "%" . $last . "%";
$start = filter_input(INPUT_GET, 'start');
if (strlen($start) == 0) $start = 0;
$end = filter_input(INPUT_GET, 'end');
if (strlen($end) == 0) $end = PHP_INT_MAX;
$connection = mysqli_connect(
    $_SERVER['DB_SERVERNAME'],
    $_SERVER['DB_USERNAME'],
    $_SERVER['DB_PASSWORD'],
    $_SERVER['DB_DATABASE']
);

$rows = [];
$qstr = "SELECT CONCAT(Firstname, \" \", Lastname) as Name, COUNT(AssignTo.EventName) as Shifts FROM AssignTo 
        JOIN User ON StaffUsername=Username 
        JOIN Event ON AssignTo.EventName=Event.EventName AND AssignTo.StartDate=Event.StartDate AND AssignTo.SiteName=Event.SiteName
        WHERE Firstname LIKE ? AND Lastname LIKE ? AND AssignTo.StartDate <= ? AND EndDate >= ? ";
if (isset($site) && $site != 'all') $qstr .= "AND AssignTo.SiteName=? ";
$qstr .= "GROUP BY Username";

$qstr .= $sort;

$query = mysqli_prepare($connection, $qstr);

if (isset($site) && $site != 'all') {
    mysqli_stmt_bind_param($query, 'sssss', $firstq, $lastq, $end, $start, $site);
} else {
    mysqli_stmt_bind_param($query, 'ssss', $firstq, $lastq, $end, $start);
}
mysqli_stmt_execute($query);
mysqli_stmt_bind_result($query, $resultname, $resultshifts);

while (mysqli_stmt_fetch($query)) {
    array_push($rows, array('name' => $resultname, 'shifts' => $resultshifts));
}
mysqli_stmt_close($query);

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
            <h1 class="mt-5">Manage Staff</h1>
            <br/>
            <form action="managestaff.php" method="GET">
                <div class="row">
                    <div class="col-md-4"></div>
                    <div class="col-md-4">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Site</label>
                            <div class="col-sm-8">
                                <select name="site" class="form-control">
                                    <option value="all">-- ALL --</option>
                                    <?php
                                    foreach ($sites as $s) {
                                        if ($site != $s) {
                                            echo '<option value="'.$s.'">'.$s.'</option>';
                                        } else {
                                            echo '<option selected value="'.$s.'">'.$s.'</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4"></div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">First Name</label>
                            <div class="col-sm-8">
                                <input value="<?= $first ?>" type="text" class="form-control" name="firstName">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Last Name</label>
                            <div class="col-sm-8">
                                <input value="<?= $last ?>"  type="text" class="form-control" name="lastName">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Start Date</label>
                            <div class="col-sm-8">
                                <input value="<?= $start ?>"  type="date" class="form-control" name="start">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">End Date</label>
                            <div class="col-sm-8">
                                <input value="<?= $end ?>"  type="date" class="form-control" name="end">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4"></div>
                    <div class="col-md-4">
                        <div class="form-group row">
                            <div class="col-sm-12 text-center">
                                <input type="hidden" name="sort" value="<?= filter_input(INPUT_GET, 'sort') ?>">
                                <button type="submit" class="btn btn-primary">Filter</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4"></div>
                </div>
            </form>
            <div class="row">
                <div class="col-md-12">
                    <table class="table align-self-center text-center">
                        <thead>
                        <tr>
                            <th scope="col">Staff Name <a href="<?php echo "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"?>&sort=Name+ASC"><i class="fas fa-chevron-up"></i></a> <a href="<?php echo "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"?>&sort=Name+DESC"><i class="fas fa-chevron-down"></i></a></th>
                            <th align="center" scope="col"># Event Shifts <a href="<?php echo "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"?>&sort=Shifts+ASC"><i class="fas fa-chevron-up"></i></a> <a href="<?php echo "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"?>&sort=Shifts+DESC"><i class="fas fa-chevron-down"></i></a></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        foreach ($rows as $r) {
                            if (isset($site)) {
//                            $pricecheck = ($pricelow == null || $pricelow == '' || intval($pricelow) <= $t['price']) && ($pricehi == null || $pricehi == '' || intval($pricehi) >= $t['price']);
//                            if (($type == 'all' || $type == null || $type == $t['type']) && ($route == null || $route == '' || $route == $t['route']) && $pricecheck)
                                echo '<tr><td>' . $r['name'] . '</td><td>' . $r['shifts'] . '</td></tr>';
                            }
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
            </div>
        </div>
    </div>
</div>

<script>
    function verify() {
        if (typeof $('input[name=routetbl]:checked').val() === 'undefined') {
            return false;
        }
        return true;
    }
</script>

<?php include('footer.php') ?>

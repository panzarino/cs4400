<?php

$sort = filter_input(INPUT_GET, 'sort');
if (isset($sort)) {
    $sort = ' ORDER BY '.$sort;
}

$username = filter_input(INPUT_GET, 'username');
$type = filter_input(INPUT_GET, 'type');
$status = filter_input(INPUT_GET, 'status');

$connection = mysqli_connect(
    $_SERVER['DB_SERVERNAME'],
    $_SERVER['DB_USERNAME'],
    $_SERVER['DB_PASSWORD'],
    $_SERVER['DB_DATABASE']
);


$users = [];
$query = mysqli_prepare($connection, "SELECT 'visitor' AS Type, User.Username as Username, Status, (SELECT COUNT(*) FROM UserEmail WHERE UserEmail.Username = User.Username) AS EmailCount FROM User JOIN Visitor ON User.Username=Visitor.Username WHERE User.Username NOT IN (SELECT Username FROM Employee) UNION SELECT 'staff' AS Type, User.Username as Username, Status, (SELECT COUNT(*) FROM UserEmail WHERE UserEmail.Username = User.Username) AS EmailCount FROM User JOIN Staff ON User.Username=Staff.Username UNION SELECT 'manager' AS Type, User.Username as Username, Status, (SELECT COUNT(*) FROM UserEmail WHERE UserEmail.Username = User.Username) AS EmailCount FROM User JOIN Manager ON User.Username=Manager.Username UNION SELECT 'user' AS Type, User.Username as Username, Status, (SELECT COUNT(*) FROM UserEmail WHERE UserEmail.Username = User.Username) AS EmailCount FROM User WHERE Username NOT IN (SELECT Username FROM Employee UNION SELECT Username FROM Visitor)".$sort);
mysqli_stmt_execute($query);
mysqli_stmt_bind_result($query, $resulttype, $resultusername, $resultstatus, $resultemailcount);
while (mysqli_stmt_fetch($query)) {
    array_push($users, array('type' => $resulttype, 'username' => $resultusername, 'status' => $resultstatus, 'emailCount' => $resultemailcount));
}
mysqli_stmt_close($query);
mysqli_close($connection);
?>

<?php include('header.php') ?>

<!-- Page Content -->
<div class="container">
    <div class="row">
        <div class="col-lg-8 offset-lg-2 text-center">
            <h1 class="mt-5">Manage User</h1>
            <form action="manageuser.php" method="GET">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Username</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="username" value="<?= $username ?>">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Type</label>
                            <div class="col-sm-8">
                                <select name="type" class="form-control" required>
                                    <option value="all" <?= $type == 'all' || $type == null ? 'selected' : '';?>>All</option>
                                    <option value="user" <?= $type == 'user' ? 'selected' : '';?>>User</option>
                                    <option value="visitor" <?= $type == 'visitor' ? 'selected' : '';?>>Visitor</option>
                                    <option value="staff" <?= $type == 'staff' ? 'selected' : '';?>>Staff</option>
                                    <option value="manager" <?= $type == 'manager' ? 'selected' : '';?>>Manager</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Status</label>
                            <div class="col-sm-8">
                                <select name="status" class="form-control" required>
                                    <option value="all" <?= $status == 'all' || $status == null ? 'selected' : '';?>>All</option>
                                    <option value="approved" <?= $status == 'approved' ? 'selected' : '';?>>Approved</option>
                                    <option value="pending" <?= $status == 'pending' ? 'selected' : '';?>>Pending</option>
                                    <option value="declined" <?= $status == 'declined' ? 'selected' : '';?>>Declined</option>
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
            <form action="manageuserscript.php" method="POST" onsubmit="return verify()">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col"></th>
                                <th scope="col">Username <a href="<?php echo "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"?>&sort=Username+ASC"><i class="fas fa-chevron-up"></i></a> <a href="<?php echo "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"?>&sort=Username+DESC"><i class="fas fa-chevron-down"></i></a></th>
                                <th scope="col">Email Count <a href="<?php echo "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"?>&sort=EmailCount+ASC"><i class="fas fa-chevron-up"></i></a> <a href="<?php echo "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"?>&sort=EmailCount+DESC"><i class="fas fa-chevron-down"></i></a></th>
                                <th scope="col">User Type <a href="<?php echo "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"?>&sort=Type+ASC"><i class="fas fa-chevron-up"></i></a> <a href="<?php echo "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"?>&sort=Type+DESC"><i class="fas fa-chevron-down"></i></a></th>
                                <th scope="col">Status <a href="<?php echo "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"?>&sort=Status+ASC"><i class="fas fa-chevron-up"></i></a> <a href="<?php echo "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"?>&sort=Status+DESC"><i class="fas fa-chevron-down"></i></a></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            foreach ($users as $user) {
                                if (($username == '' || $username == null || $username == $user['username']) && ($type == 'all' || $type == null || $type == $user['type']) && ($status == 'all' || $status == $user['status'])) {
                                    echo '<tr><td><input type="radio" name="username" value="' . $user['username'] . '"></td><td>' . $user['username'] . '</td><td>' . $user['emailCount'] . '</td><td>' . ucfirst($user['type']) . '</td><td>' . ucfirst($user['status']) . '</td></tr>';
                                }
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 text-center">
                        <a href="home.php" class="btn btn-primary">Back</a>
                    </div>
                    <div class="col-md-4 text-center">
                        <button type="submit" class="btn btn-primary" name="status" value="approved">Approve</button>
                    </div>
                    <div class="col-md-4 text-center">
                        <button type="submit" class="btn btn-primary" name="status" value="declined">Decline</button>
                    </div>
                </div>
            </form>
            <?php
            if (isset($_GET['updated'])) {
                echo '<div class="alert alert-success text-center" role="alert" style="margin-top: 30px">Updated user '.$_GET['updated'].'.</div>';
            }
            ?>
        </div>
    </div>
</div>

<script>
    function verify() {
        if (typeof $('input[name=username]:checked').val() === 'undefined') {
            return false;
        }
        return true;
    }
</script>

<?php include('footer.php') ?>

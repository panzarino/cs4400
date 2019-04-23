<?php
session_start();

if (!isset($_SESSION['username'])) {
    header('Location: ./');
    exit();
}

$keys = explode(',', filter_input(INPUT_GET, 'event'));
$event = $keys[0];
$start = $keys[1];
$site = $keys[2];

$connection = mysqli_connect(
    $_SERVER['DB_SERVERNAME'],
    $_SERVER['DB_USERNAME'],
    $_SERVER['DB_PASSWORD'],
    $_SERVER['DB_DATABASE']
);

$query = mysqli_prepare($connection, "SELECT EndDate, EventPrice, Capacity, Description, (SELECT GROUP_CONCAT(CONCAT(Firstname, ' ', Lastname) SEPARATOR ', ')
                                                FROM AssignTo 
                                                JOIN User ON User.Username=AssignTo.StaffUsername WHERE AssignTo.EventName=Event.EventName 
                                                AND AssignTo.StartDate=Event.StartDate AND AssignTo.SiteName=Event.SiteName), DATEDIFF(EndDate, StartDate) + 1 as Duration
                                                FROM Event
                                                WHERE EventName=? AND StartDate=? AND SiteName=?");
mysqli_stmt_bind_param($query, 'sss', $event, $start, $site);
mysqli_stmt_execute($query);
mysqli_stmt_bind_result($query, $end, $price, $capacity, $description, $staff, $duration);
mysqli_stmt_fetch($query);
mysqli_stmt_close($query);

$query = mysqli_prepare($connection, "SELECT COUNT(EventName) FROM VisitEvent 
                                            WHERE EventName=? AND StartDate=? AND SiteName=?");
mysqli_stmt_bind_param($query, 'sss', $event, $start, $site);
mysqli_stmt_execute($query);
mysqli_stmt_bind_result($query, $tickets);
mysqli_stmt_fetch($query);
mysqli_stmt_close($query);

$tickets = $capacity - $tickets;

?>

<?php include('header.php') ?>
<!-- Page Content -->
<div class="container">
    <div class="row">
        <div class="col-lg-8 offset-lg-2 text-center">
            <h1 class="mt-5">Event Detail</h1>
            <br/>
            <form action="logvisiteventscript.php" method="POST">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Event</label>
                            <div class="col-sm-8 mt-2">
                                <p class="text-left"><b><?= $event ?></b></p>
                                <input type="hidden" value="<?= $event ?>" name="event">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Site</label>
                            <div class="col-sm-8 mt-2">
                                <p class="text-left"><b><?= $site ?></b></p>
                                <input type="hidden" value="<?= $site ?>" name="site">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Start Date</label>
                            <div class="col-sm-8">
                                <p class="text-left mt-3"><b><?= $start ?></b></p>
                                <input type="hidden" value="<?= $start ?>" name="start">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">End Date</label>
                            <div class="col-sm-8">
                                <p class="text-left mt-3"><b><?= $end ?></b></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Ticket Price ($)</label>
                            <div class="col-sm-8">
                                <p class="text-left mt-2"><b><?= $price ?></b></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label mt">Tickets Remaining</label>
                            <div class="col-sm-8">
                                <p class="text-left mt-2"><b><?= $tickets ?></b></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Description</label>
                            <div class="col-sm-10">
                                <p class="text-left mt-2"><b><?= $description ?></b></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Visit Date</label>
                            <div class="col-sm-10 mt-2">
                                <input type="date" class="form-control" name="date" min="<?= $start ?>" max="<?= $end ?>">
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
                        <a href="exploreevent.php" class="btn btn-primary">Back</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include('footer.php') ?>

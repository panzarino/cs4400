<?php include('header.php') ?>

<!-- Page Content -->
<div class="container">
    <div class="row">
        <div class="col-lg-6 offset-lg-3 text-center">
            <h1 class="mt-5">Atlanta Beltline Login</h1>
            <form action="loginscript.php" method="POST">
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Email</label>
                    <div class="col-sm-10">
                        <input type="email" class="form-control" name="email" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Password</label>
                    <div class="col-sm-10">
                        <input type="password" class="form-control" name="password" required>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-6 text-center">
                        <button type="submit" class="btn btn-primary">Log In</button>
                    </div>
                    <div class="col-sm-6 text-center">
                        <a href="register.php" class="btn btn-primary">Register</a>
                    </div>
                </div>
            </form>
            <?php
            if (isset($_GET['error'])) {
                if ($_GET['error'] == 'credentials') {
                    echo '<div class="alert alert-danger text-center" role="alert" style="margin-top: 30px">Incorrect username or password.</div>';
                }
                if ($_GET['error'] == 'approval') {
                    echo '<div class="alert alert-warning text-center" role="alert" style="margin-top: 30px">Your administrator has not approved your account yet.</div>';
                }
            }
            ?>
        </div>
    </div>
</div>

<?php include('footer.php') ?>

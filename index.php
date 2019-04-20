<?php include('header.php') ?>

<!-- Page Content -->
<div class="container">
    <div class="row">
        <div class="col-lg-6 offset-lg-3 text-center">
            <h1 class="mt-5">Atlanta Beltline Login</h1>
            <form>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Email</label>
                    <div class="col-sm-10">
                        <input type="email" class="form-control" name="email">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Password</label>
                    <div class="col-sm-10">
                        <input type="password" class="form-control" name="password">
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
        </div>
    </div>
</div>

<?php include('footer.php') ?>

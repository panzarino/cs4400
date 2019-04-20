<?php include('header.php') ?>

<!-- Page Content -->
<div class="container">
    <div class="row">
        <div class="col-lg-8 offset-lg-2 text-center">
            <h1 class="mt-5">Register Visitor</h1>
            <form onsubmit="return verify()">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">First Name</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="firstName" maxlength="20" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Last Name</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="lastName" maxlength="20" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Username</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="username" maxlength="20" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Password</label>
                            <div class="col-sm-8">
                                <input type="password" class="form-control" name="password" id="password" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Confirm Password</label>
                            <div class="col-sm-8">
                                <input type="password" class="form-control" name="confirmPassword" id="confirmPassword" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 offset-md-2">
                        <div class="row" id="emailDisplay">

                        </div>
                        <br />
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Email</label>
                            <div class="col-sm-6">
                                <input type="email" class="form-control" name="email" id="email" maxlength="60">
                            </div>
                            <button id="add" type="button" class="col-sm-3 btn btn-outline-secondary" onclick="addEmail()">Add</button>
                        </div>
                    </div>
                </div>
                <input type="hidden" id="emails" name="emails" value="">
                <div class="form-group row">
                    <div class="col-sm-6 text-center">
                        <a href="register.php" class="btn btn-primary">Back</a>
                    </div>
                    <div class="col-sm-6 text-center">
                        <button type="submit" class="btn btn-primary">Register</button>
                    </div>
                </div>
            </form>
            <div id="errorMessage"></div>
        </div>
    </div>
</div>

<script>
    var emails = [];

    function renderEmails() {
        $('#emailDisplay').html('');
        var emailString = '';
        emails.forEach(function(email, index) {
            $('#emailDisplay').append('<p class="col-sm-6 offset-sm-3">' + email + '</p><button id="add" type="button" class="col-sm-3 btn btn-outline-secondary" onclick="removeEmail(' + index +')">Remove</button>');
            emailString += (email + ',');
        });
        $('#emails').val(emailString.slice(0, -1));
    }

    function addEmail() {
        if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test($('#email').val())) {
            emails.push($('#email').val());
            $('#email').val('');
            renderEmails();
        }
    }

    function removeEmail(index) {
        emails.splice(index, 1);
        renderEmails();
    }

    function verify() {
        if (emails.length === 0) {
            $('#errorMessage').html('<div class="alert alert-danger text-center" role="alert" style="margin-top: 30px">You must add at least one email.</div>');
            return false;
        }
        if ($('#password').val().length < 8) {
            $('#errorMessage').html('<div class="alert alert-danger text-center" role="alert" style="margin-top: 30px">Password must be at least 8 characters!</div>');
            return false;
        }
        if ($('#password').val() !== $('#confirmPassword').val()) {
            $('#errorMessage').html('<div class="alert alert-danger text-center" role="alert" style="margin-top: 30px">Passwords do not match!</div>');
            return false;
        }
        return true;
    }
</script>

<?php include('footer.php') ?>

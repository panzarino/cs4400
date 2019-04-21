<?php include('header.php') ?>

<!-- Page Content -->
<div class="container">
    <div class="row">
        <div class="col-lg-8 offset-lg-2 text-center">
            <h1 class="mt-5">Register Employee</h1>
            <form onsubmit="return verify()" action="registeremployeescript.php" method="POST">
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
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">User Type</label>
                            <div class="col-sm-8">
                                <select name="userType" class="form-control" required>
                                    <option value="manager">Manager</option>
                                    <option value="staff">Staff</option>
                                </select>
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
                                <input class="form-control" type="password"  name="confirmPassword" id="confirmPassword" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Phone</label>
                            <div class="col-sm-8">
                                <input class="form-control" type="text" name="phone" maxlength="10" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Address</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="address" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">City</label>
                            <div class="col-sm-8">
                                <input class="form-control" type="text" name="city" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">State</label>
                            <div class="col-sm-8">
                                <select name="state" class="form-control" required>
                                    <option value="AL">AL</option>
                                    <option value="AK">AK</option>
                                    <option value="AR">AR</option>
                                    <option value="AZ">AZ</option>
                                    <option value="CA">CA</option>
                                    <option value="CO">CO</option>
                                    <option value="CT">CT</option>
                                    <option value="DC">DC</option>
                                    <option value="DE">DE</option>
                                    <option value="FL">FL</option>
                                    <option value="GA">GA</option>
                                    <option value="HI">HI</option>
                                    <option value="IA">IA</option>
                                    <option value="ID">ID</option>
                                    <option value="IL">IL</option>
                                    <option value="IN">IN</option>
                                    <option value="KS">KS</option>
                                    <option value="KY">KY</option>
                                    <option value="LA">LA</option>
                                    <option value="MA">MA</option>
                                    <option value="MD">MD</option>
                                    <option value="ME">ME</option>
                                    <option value="MI">MI</option>
                                    <option value="MN">MN</option>
                                    <option value="MO">MO</option>
                                    <option value="MS">MS</option>
                                    <option value="MT">MT</option>
                                    <option value="NC">NC</option>
                                    <option value="NE">NE</option>
                                    <option value="NH">NH</option>
                                    <option value="NJ">NJ</option>
                                    <option value="NM">NM</option>
                                    <option value="NV">NV</option>
                                    <option value="NY">NY</option>
                                    <option value="ND">ND</option>
                                    <option value="OH">OH</option>
                                    <option value="OK">OK</option>
                                    <option value="OR">OR</option>
                                    <option value="PA">PA</option>
                                    <option value="RI">RI</option>
                                    <option value="SC">SC</option>
                                    <option value="SD">SD</option>
                                    <option value="TN">TN</option>
                                    <option value="TX">TX</option>
                                    <option value="UT">UT</option>
                                    <option value="VT">VT</option>
                                    <option value="VA">VA</option>
                                    <option value="WA">WA</option>
                                    <option value="WI">WI</option>
                                    <option value="WV">WV</option>
                                    <option value="WY">WY</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Zipcode</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="zipcode" maxlength="5" required>
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
            <?php
            if (isset($_GET['username'])) {
                echo '<div class="alert alert-danger text-center" role="alert" style="margin-top: 30px">That username is already in use.</div>';
            }
            if (isset($_GET['phone'])) {
                echo '<div class="alert alert-danger text-center" role="alert" style="margin-top: 30px">That phone number is already in use.</div>';
            }
            if (isset($_GET['email'])) {
                echo '<div class="alert alert-danger text-center" role="alert" style="margin-top: 30px">The following emails are already in use: '.htmlspecialchars($_GET['email']).'.</div>';
            }
            if (isset($_GET['success'])) {
                echo '<div class="alert alert-success text-center" role="alert" style="margin-top: 30px">Your account has been created. It is pending admin approval before you can log in.</div>';
            }
            ?>
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
            $('#errorMessage').html('<div class="alert alert-danger text-center" role="alert" style="margin-top: 30px">Password must be at least 8 characters.</div>');
            return false;
        }
        if ($('#password').val() !== $('#confirmPassword').val()) {
            $('#errorMessage').html('<div class="alert alert-danger text-center" role="alert" style="margin-top: 30px">Passwords do not match.</div>');
            return false;
        }
        return true;
    }
</script>

<?php include('footer.php') ?>

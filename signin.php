<?php
require_once "theme/header.php";
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-6 offset-md-3">
            <div class="card mt-3 bg-light">
                <div class="card-body">
                <form method="POST" id="FormSignin">
                    <div class="form-group row mt-3">
                        <label for="UserNameOrUserEmail" class="col-md-3 col-form-label">Username or Email</label>
                        <div class="col-md-9">
                        <input type="text" class="form-control" id="UserNameOrUserEmail" name="UserNameOrUserEmail" placeholder="Enter your username or email" maxlength="50">
                        </div>
                    </div>
                    <div class="form-group row mt-3">
                        <label for="UserPassword" class="col-md-3 col-form-label">Password</label>
                        <div class="col-md-9">
                        <input type="password" class="form-control" id="UserPassword" name="UserPassword" placeholder="Enter your password" maxlength="25">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-9 offset-md-3">
                            <p id="result"></p>
                        </div>
                    </div>
                    <div class="form-group row mt-3">
                        <div class="col-md-9 offset-md-3">
                            <p class="text-danger">İf you aren't registered <a href="javascript:void(0);" onclick="GoSignup()">sign up</a></p>
                        </div>
                    </div>
                    <div class="form-group row mt-3">
                        <div class="col-md-9 offset-md-5">
                            <button type="button" class="btn btn-primary" id="mysubmit" name="mysubmit" onclick="Signin('FormSignin','Signin')">Sign in <span class="myload"></span></button>
                        </div>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require_once "theme/footer.php";

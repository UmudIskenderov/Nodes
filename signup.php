<?php
require_once "theme/header.php";
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-6 offset-md-3">
            <div class="card mt-3 bg-light">
                <div class="card-body">
                <div class="offset-md-5"><h1 style="color: aqua;"><i>To Do</i></h1></div>
                <form method="POST" id="FormSignup" enctype="multipart/form-data">
                    <div class="form-group row">
                        <label for="UserFirstname" class="col-md-3 col-form-label">Firstname</label>
                        <div class="col-md-9">
                        <input type="text" class="form-control" id="UserFirstname" name="UserFirstname" placeholder="Enter your name" maxlength="25">
                        </div>
                    </div>
                    <div class="form-group row mt-3">
                        <label for="UserLastname" class="col-md-3 col-form-label">Lastname</label>
                        <div class="col-md-9">
                        <input type="text" class="form-control" id="UserLastname" name="UserLastname" placeholder="Enter your surname" maxlength="25">
                        </div>
                    </div>
                    <div class="form-group row mt-3">
                        <label for="UserName" class="col-md-3 col-form-label">Username</label>
                        <div class="col-md-9">
                        <input type="text" class="form-control" id="UserName" name="UserName" placeholder="Enter your username" maxlength="50">
                        </div>
                    </div>
                    <div class="form-group row mt-3">
                        <label for="UserEmail" class="col-md-3 col-form-label">Email</label>
                        <div class="col-md-9">
                        <input type="email" class="form-control" id="UserEmail" name="UserEmail" placeholder="Enter your email" maxlength="50">
                        </div>
                    </div>
                    <div class="form-group row mt-3">
                        <label for="UserPassword" class="col-md-3 col-form-label">Password</label>
                        <div class="col-md-9">
                        <input type="password" class="form-control" id="UserPassword" name="UserPassword" placeholder="Enter your password" maxlength="25">
                        <small class="text-muted">Your password must be at least 8 characters!</small>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-9 offset-md-3">
                            <div class="form-check">
                                <label for="myFile">Photo choose</label>
                                <input type="file" class="form-control-file" id="myFile" name="myFile">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-9 offset-md-3">
                            <p id="result"></p>
                        </div>
                    </div>
                    <div class="form-group row mt-3">
                        <div class="col-md-9 offset-md-3">
                            <button type="submit" class="btn btn-primary" name="mysubmit" id="mysubmit">Sign up<span class="myload"></span></button>
                        </div>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require_once "theme/footer.php";
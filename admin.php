<?php
session_start();
require_once "theme/header.php";
require_once "classes/allClassNamespace.php";
$userID = $_GET['UserID'];
if ($_SESSION["LogedIn"] == true && md5(md5(md5(sha1($_SESSION["ID"])))) == $userID) {
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-6">
            <div class="card mt-3 bg-light">
                <div class="card-body">
                <form method="POST" id="FormUserAdd" enctype="multipart/form-data">
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
                            <button type="submit" class="btn btn-primary" name="mysubmit2" id="mysubmit2">Add</button>
                        </div>
                    </div>
                </form>
                </div>
            </div>
        </div>
        <div class="col-lg-2">
            <div class="card mt-3 bg-light">
                <div class="card-body text-center">
                    <h5>Exercise</h5>
                    <a href="javascript:void(0);" onclick="GoAdminExercise()"><i class="fa-solid fa-clipboard-list text-success"></i></a>
                </div>
            </div>
        </div>
        <div class="col-lg-2">
            <div class="card mt-3 bg-light">
                <div class="card-body text-center">
                    <h5>Logout</h5>
                    <a href="javascript:void(0);" onclick="Logout('Logout')"><i class="fa-sharp fa-solid fa-arrow-right-from-bracket text-warning"></i></a>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Picture</th>
                            <th scope="col">Firstname</th>
                            <th scope="col">Lastname</th>
                            <th scope="col">Username</th>
                            <th scope="col">Email</th>
                            <th scope="col">Position</th>
                            <th scope="col">Status</th>
                            <th scope="col">Update</th>
                            <th scope="col">Active/Deactive</th>
                        </tr>
                    </thead>
                    <tbody id="result1">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php 
} else {
    classes\sessionRouting::go('signin.php',1);
}
require_once "theme/footer.php";?>
<script>
    UserInfoForAdmin()
</script>
<?php
session_start();
require_once "theme/header.php";
require_once "classes/allClassNamespace.php";
$userID = $_GET['KriptoID'];
if ($_SESSION["LogedIn"] == true && md5(md5(md5(sha1($_SESSION["ID"])))) == $userID) {
?>
<div class="cantainer-fluid">
    <div class="row">
        <div class="col-lg-6">
            <div class="card mt-3 bg-light">
                <div class="card-body">
                <form method="POST" id="UserUpdate" >
                    <div class="form-group row">
                        <label for="UserFirstname" class="col-md-3 col-form-label">Firstname</label>
                        <div class="col-md-9">
                        <input type="text" class="form-control" id="UserFirstname" name="UserFirstname" maxlength="25">
                        </div>
                    </div>
                    <div class="form-group row mt-3">
                        <label for="UserLastname" class="col-md-3 col-form-label">Lastname</label>
                        <div class="col-md-9">
                        <input type="text" class="form-control" id="UserLastname" name="UserLastname" maxlength="25">
                        </div>
                    </div>
                    <div class="form-group row mt-3">
                        <label for="UserName" class="col-md-3 col-form-label">Username</label>
                        <div class="col-md-9">
                        <input type="text" class="form-control" id="UserName" name="UserName" maxlength="50">
                        </div>
                    </div>
                    <div class="form-group row mt-3">
                        <label for="UserEmail" class="col-md-3 col-form-label">Email</label>
                        <div class="col-md-9">
                        <input type="email" class="form-control" id="UserEmail" name="UserEmail" maxlength="50">
                        </div>
                    </div>
                    <div class="form-group row mt-3">
                        <label for="UserPassword" class="col-md-3 col-form-label">Password</label>
                        <div class="col-md-9">
                        <input type="password" class="form-control" id="UserPassword" name="UserPassword" maxlength="25">
                        <small class="text-muted">Your password must contain uppercase letters, lowercase letters, numbers and must be at least 8 characters!</small>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-9 offset-md-3">
                            <p id="result"></p>
                        </div>
                    </div>
                    <div class="form-group row mt-3">
                        <div class="col-md-9 offset-md-3">
                            <button type="button" class="btn btn-primary" name="mysubmit" id="mysubmit" onclick="UserUpdate('UserUpdate','UserUpdate')">Update</button>
                        </div>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php 
} else {
    classes\sessionRouting::go('signin.php',1);
}
require_once "theme/footer.php"; ?>
<script>
getUserInfo();
</script>
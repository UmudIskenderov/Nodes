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
                    <form method="POST" id="FormExerciseAddUser">
                        <div class="form-group row">
                            <label for="ExerciseName" class="col-md-3 col-form-label">Exercise</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" id="ExerciseName" placeholder="Enter text" name="ExerciseName">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-9 offset-md-3">
                                <p id="result"></p>
                            </div>
                        </div>
                        <div class="form-group row mt-3">
                            <div class="col-md-9 offset-md-3">
                                <button type="button" class="btn btn-success" name="mysubmit" id="mysubmit" onclick="ExerciseAdd('FormExerciseAddUser','ExerciseAdd')">Remember<span class="myload"></span></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-2">
            <div class="card mt-3 bg-light">
                <div class="card-body text-center">
                    <h5>Delete account</h5>
                    <a href="javascript:void(0);" onclick="UserDelete('UserDelete')"><i class="fa-solid fa-user-xmark text-danger"></i></a>
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
                            <th scope="col">Username</th>
                            <th scope="col">Exercise's name</th>
                            <th scope="col">Update</th>
                            <th scope="col">Delete</th>
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
require_once "theme/footer.php"; ?>
<script>
    getUserExerciseInfo();
</script>
<script src="js/bootstrap.min.js"></script>
<script src="js/jquery-3.6.1.min.js"></script>
<script src="js/baguetteBox.min.js"></script>
<script>
    var SITE_URL = "http://localhost/NOTES-MANAGEMENT-MAIN";
    baguetteBox.run('.photoGallery');
    function Signin(FormID, Operation) {
        $(".myload").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
        $("#mysubmit").prop("disabled", true);
        var myData = $("form#" + FormID).serialize();
        $.ajax({
            type: "post",
            url: SITE_URL + '/ajaxprocesses.php?page=' + Operation,
            data: myData,
            success: function(data) {
                $(".myload").html("");
                $("#mysubmit").prop("disabled", false);
                const obj = JSON.parse(data);
                if (obj.warning) {
                    $("#result").html('<div class="alert alert-warning">' + obj.warning + '</div>');
                } else if (obj.danger) {
                    $("#result").html('<div class="alert alert-danger">' + obj.danger + '</div>');
                } else if (obj.success) {
                    setTimeout(function() {
                        if (obj.success == "admin") {
                            window.location.href = SITE_URL + '/admin.php?UserID=' + obj.id;
                        } else if (obj.success == "user") {
                            window.location.href = SITE_URL + '/todo.php?UserID=' + obj.id;
                        }
                    }, 1500)
                }
            }
        })
    }

    function GoSignup() {
        window.location.href = SITE_URL + '/signup.php';
    }

    $(function(){
        $("#FormSignup").on('submit',function(e){
            e.preventDefault();
            $(".myload").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
            $("#mysubmit").prop("disabled", true);
            $.ajax({
                type: "post",
                url: SITE_URL + '/ajaxprocesses.php?page=Signup',
                data: new FormData(this),
                contentType:false,
                cache:false,
                processData:false,
                success: function(data) {
                $(".myload").html("");
                $("#mysubmit").prop("disabled", false);
                const obj = JSON.parse(data);
                if (obj.warning) {
                    $("#result").html('<div class="alert alert-warning">' + obj.warning + '</div>');
                } else if (obj.danger) {
                    $("#result").html('<div class="alert alert-danger">' + obj.danger + '</div>');
                } else if (obj.success) {
                            window.location.href = SITE_URL + '/todo.php?UserID=' + obj.id;
                }
            }
            })
        })
    })

    function ExerciseAdd(FormID, Operation) {
        $(".myload").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
        $("#mysubmit").prop("disabled", true);
        var myData = $("form#" + FormID).serialize();
        const queryString = window.location.search;
        const urlParams = new URLSearchParams(queryString);
        const UserID = urlParams.get('UserID')
        $.ajax({
            type: "post",
            url: SITE_URL + '/ajaxprocesses.php?page=' + Operation + '&UserID=' + UserID,
            data: myData,
            success: function(data) {
                $(".myload").html("");
                $("#mysubmit").prop("disabled", false);
                const obj = JSON.parse(data);
                if (obj.warning) {
                    $("#result").html('<div class="alert alert-warning">' + obj.warning + '</div>');
                } else if (obj.danger) {
                    setTimeout(function() {
                        window.location.href = SITE_URL + '/signin.php';
                    }, 1500)
                } else if (obj.success) {
                    $('form').trigger("reset")
                    $("#result").html('<div class="alert alert-success">' + obj.success + '</div>');
                    getUserExerciseInfo();
                    ExerciseInfoForAdmin();
                }
            }
        })
    }

    function UserDelete(Operation) {
        const queryString = window.location.search;
        const urlParams = new URLSearchParams(queryString);
        const UserID = urlParams.get('UserID')
        $.ajax({
            type: "post",
            url: SITE_URL + '/ajaxprocesses.php?page=' + Operation + '&UserID=' + UserID,
            success: function(data) {
                const obj = JSON.parse(data);
                if (obj.danger) {
                    setTimeout(function() {
                        window.location.href = SITE_URL + '/signin.php';
                    }, 1500)
                } else if (obj.success) {
                    setTimeout(function() {
                        window.location.href = SITE_URL + '/signin.php';
                    }, 1500)
                    alert(obj.success)
                }
            }
        })
    }

    function Logout(Operation) {
        const queryString = window.location.search;
        const urlParams = new URLSearchParams(queryString);
        const UserID = urlParams.get('UserID')
        $.ajax({
            type: "post",
            url: SITE_URL + '/ajaxprocesses.php?page=' + Operation + '&UserID=' + UserID,
            success: function(data) {
                const obj = JSON.parse(data);
                if (obj.danger) {
                    setTimeout(function() {
                        window.location.href = SITE_URL + '/signin.php';
                    }, 1500)
                } else if (obj.success) {
                    setTimeout(function() {
                        window.location.href = SITE_URL + '/signin.php';
                    }, 1500)
                    alert(obj.success)
                }
            }
        })
    }

    function getUserExerciseInfo() {
        const queryString = window.location.search;
        const urlParams = new URLSearchParams(queryString);
        const UserID = urlParams.get('UserID')
        var message=""
        $.ajax({
            type: "post",
            url: SITE_URL + '/ajaxprocesses.php?page=getUserExerciseInfo',
            success: function(data) {
                const obj = JSON.parse(data);
                for (let i = 1;; i++) {
                    if(obj[i]){
                        message+='<tr><th scope="row">'+obj[i].ExerciseID+'</th><td>'+obj[i].UserName+'</td><td>'+obj[i].ExerciseName+'</td><td><a href="javascript:void(0);" onclick="GoExerciseEdit('+obj[i].ExerciseID+')"><svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" class="bi bi-pencil-square text-success" viewBox="0 0 16 16"><path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/><path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/></svg></a></td><td><a href="javascript:void(0);" onclick="ExerciseDelete('+obj[i].ExerciseID+')"><svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" class="bi bi-trash-fill text-danger" viewBox="0 0 16 16"><path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z"/></svg></a></td></tr>'
                        
                    }else{
                        break;
                    }
                }
                $("#result1").html(message)
            }
        })
    }

    function GoExerciseEdit(ExerciseID) {
        const queryString = window.location.search;
        const urlParams = new URLSearchParams(queryString);
        const UserID = urlParams.get('UserID')
        window.location.href = SITE_URL + '/edit.php?ExerciseID='+ExerciseID+'&UserID='+UserID;
    }

    function ExerciseDelete(ExerciseID) {
        const queryString = window.location.search;
        const urlParams = new URLSearchParams(queryString);
        const UserID = urlParams.get('UserID')
        $.ajax({
            type: "post",
            url: SITE_URL + '/ajaxprocesses.php?page=ExerciseDelete&UserID=' + UserID + '&ExerciseID=' + ExerciseID,
            success: function(data) {
                const obj = JSON.parse(data);
                if (obj.danger) {
                    window.location.href = SITE_URL + '/signin.php';
                } else if (obj.success) {
                    if (obj.userposition == "admin") {
                        setTimeout(function() {
                            window.location.href = SITE_URL + '/exercise.php?UserID=' + UserID;
                        }, 1500)
                        alert(obj.success)
                    } else if (obj.userposition == "user") {
                        setTimeout(function() {
                            window.location.href = SITE_URL + '/todo.php?UserID=' + UserID;
                        }, 1500)
                        alert(obj.success)
                    }
                } else if (obj.warning) {
                    setTimeout(function() {
                        window.location.href = SITE_URL + '/todo.php?UserID=' + UserID;
                    }, 1500)
                    alert(obj.warning)
                }
            }
        })
    }

    $(function(){
        $("#FormUserAdd").on('submit',function(e){
            e.preventDefault();
            $(".myload").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
            $("#mysubmit").prop("disabled", true);
            const queryString = window.location.search;
            const urlParams = new URLSearchParams(queryString);
            const UserID = urlParams.get('UserID')
            $.ajax({
                type: "post",
                url: SITE_URL + '/ajaxprocesses.php?page=AdminUserAdd&UserID='+UserID,
                data: new FormData(this),
                contentType:false,
                cache:false,
                processData:false,
                success: function(data) {
                $(".myload").html("");
                $("#mysubmit").prop("disabled", false);
                const obj = JSON.parse(data);
                if (obj.warning) {
                    $("#result").html('<div class="alert alert-warning">' + obj.warning + '</div>');
                } else if (obj.danger) {
                        window.location.href = SITE_URL + '/signin.php';
                } else if (obj.success) {
                    $("form").trigger('reset')
                    $("#result").html('<div class="alert alert-success">' + obj.success + '</div>');
                    UserInfoForAdmin();
                }
                }
            })
        })
    })

    function GoAdminExercise(){
        const queryString = window.location.search;
        const urlParams = new URLSearchParams(queryString);
        const UserID = urlParams.get('UserID')
        window.location.href = SITE_URL + '/exercise.php?UserID='+UserID;
    }

    function UserInfoForAdmin() {
        const queryString = window.location.search;
        const urlParams = new URLSearchParams(queryString);
        const UserID = urlParams.get('UserID')
        var message=""
        $.ajax({
            type: "post",
            url: SITE_URL + '/ajaxprocesses.php?page=UserInfoForAdmin',
            success: function(data) {
                const obj = JSON.parse(data);
                for (let i = 1;; i++) {
                    if(obj[i]){
                        message +='<tr><th scope="row">'+obj[i].UserID+'</th><td class="photoGallery"><a href="images/'+obj[i].UserPicture+'"><img src="images/'+obj[i].UserPicture+'" width="80" height="80"></a></td><td>'+obj[i].UserFirstname+'</td><td>'+obj[i].UserLastname+'</td><td>'+obj[i].UserName+'</td><td>'+obj[i].UserEmail+'</td><td>'+((obj[i].UserPosition)==1 ? "admin" : "user")+'</td><td>'+((obj[i].UserStatus)==1 ? "active" : "deactive")+'</td>'
                        if(obj[i].UserPosition==1){
                            message += '<td></td><td></td></tr>'
                        }else{
                            message += '<td><a href="javascript:void(0);" onclick="GoAdminUserUpdate('+obj[i].UserID+')"><svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill=" currentColor" class="bi bi-pencil-square text-primary" viewBox="0 0 16 16"><path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/><path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/></svg></a></td>'
                            if(obj[i].UserStatus==1){
                                message += '<td><a href="javascript:void(0);" onclick="UserStatus('+obj[i].UserID+')"><svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" class="bi bi-toggle-on text-success" viewBox="0 0 16 16"><path d="M5 3a5 5 0 0 0 0 10h6a5 5 0 0 0 0-10H5zm6 9a4 4 0 1 1 0-8 4 4 0 0 1 0 8z"/></svg></a>On</td></tr>'
                            }else{
                                message += '<td><a href="javascript:void(0);" onclick="UserStatus('+obj[i].UserID+')"><svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" class="bi bi-toggle-off text-danger" viewBox="0 0 16 16"><path d="M11 4a4 4 0 0 1 0 8H8a4.992 4.992 0 0 0 2-4 4.992 4.992 0 0 0-2-4h3zm-6 8a4 4 0 1 1 0-8 4 4 0 0 1 0 8zM0 8a5 5 0 0 0 5 5h6a5 5 0 0 0 0-10H5a5 5 0 0 0-5 5z"/></svg></a>Off</td></tr>'
                            }
                        }
                    }else{
                        break;
                    }
                }
                $("#result1").html(message)
            }
        })
    }

    function GoAdminUserUpdate(UserID) {
        const queryString = window.location.search;
        const urlParams = new URLSearchParams(queryString);
        const KriptoID = urlParams.get('UserID')
        window.location.href = SITE_URL + '/userupdate.php?&UserID='+UserID+'&KriptoID='+KriptoID;
    }

    function UserStatus(UserID) {
        const queryString = window.location.search;
        const urlParams = new URLSearchParams(queryString);
        const UserKriptoID = urlParams.get('UserID')
        $.ajax({
            type: "post",
            url: SITE_URL + '/ajaxprocesses.php?page=UserStatus&UserID='+UserID+'&KriptoID='+UserKriptoID,
            success: function(data) {
                const obj = JSON.parse(data);
                if(obj.referer=="admin"){
                    if(obj.success){
                        UserInfoForAdmin()
                    }else if(obj.warning){
                        $("#result").html('<div class="alert alert-warning">' + obj.warning + '</div>');
                    }else if(obj.danger){
                        window.location.href = SITE_URL + '/signin.php';
                    }
                }else if(obj.referer=="exercise"){
                    if(obj.success){
                        ExerciseInfoForAdmin()
                    }else if(obj.warning){
                        $("#result").html('<div class="alert alert-warning">' + obj.warning + '</div>');
                    }else if(obj.danger){
                        window.location.href = SITE_URL + '/signin.php';
                    }
                }
            }
        })
    }

    function GoAdminUser(){
        const queryString = window.location.search;
        const urlParams = new URLSearchParams(queryString);
        const UserID = urlParams.get('UserID')
        window.location.href = SITE_URL + '/admin.php?UserID='+UserID;
    }

    function ExerciseInfoForAdmin() {
        const queryString = window.location.search;
        const urlParams = new URLSearchParams(queryString);
        const UserID = urlParams.get('UserID')
        var message=""
        $.ajax({
            type: "post",
            url: SITE_URL + '/ajaxprocesses.php?page=ExerciseInfoForAdmin',
            success: function(data) {
                const obj = JSON.parse(data);
                for (let i = 1;; i++) {
                    if(obj[i]){
                        message += '<tr><th scope="row">'+obj[i].ExerciseID+'</th><td>'+obj[i].UserName+'</td><td>'+obj[i].ExerciseName+'</td><td><a href="javascript:void(0);" onclick="GoExerciseEdit('+obj[i].ExerciseID+')"><svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" class="bi bi-pencil-square text-success" viewBox="0 0 16 16"><path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/><path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/></svg></a></td><td><a href="javascript:void(0);" onclick="ExerciseDelete('+obj[i].ExerciseID+')"><svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" class="bi bi-trash-fill text-danger" viewBox="0 0 16 16"><path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z"/></svg></a></td>'
                        if(obj[i].UserPosition==1){
                            message += '<td></td></tr>'
                        }else{
                            if(obj[i].UserStatus==1){
                                message += '<td><a href="javascript:void(0);" onclick="UserStatus('+obj[i].UserID+')"><svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" class="bi bi-toggle-on text-success" viewBox="0 0 16 16"><path d="M5 3a5 5 0 0 0 0 10h6a5 5 0 0 0 0-10H5zm6 9a4 4 0 1 1 0-8 4 4 0 0 1 0 8z"/></svg></a>On</td></tr>'
                            }else{
                                message += '<td><a href="javascript:void(0);" onclick="UserStatus('+obj[i].UserID+')"><svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" class="bi bi-toggle-off text-danger" viewBox="0 0 16 16"><path d="M11 4a4 4 0 0 1 0 8H8a4.992 4.992 0 0 0 2-4 4.992 4.992 0 0 0-2-4h3zm-6 8a4 4 0 1 1 0-8 4 4 0 0 1 0 8zM0 8a5 5 0 0 0 5 5h6a5 5 0 0 0 0-10H5a5 5 0 0 0-5 5z"/></svg></a>Off</td></tr>'
                            }
                        }
                    }else{
                        break;
                    }
                }
                $("#result2").html(message)
            }
        })
    }   

    function UserUpdate(FormID, Operation) {
        $(".myload").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
        $("#mysubmit").prop("disabled", true);
        var myData = $("form#" + FormID).serialize();
        const queryString = window.location.search;
        const urlParams = new URLSearchParams(queryString);
        const UserID = urlParams.get('UserID')
        const KriptoID = urlParams.get('KriptoID')
        $.ajax({
            type: "post",
            url: SITE_URL + '/ajaxprocesses.php?UserID=' + UserID + '&KriptoID=' + KriptoID + '&page=' + Operation,
            data: myData,
            success: function(data) {
                $(".myload").html("");
                $("#mysubmit").prop("disabled", false);
                const obj = JSON.parse(data);
                if (obj.success) {
                    window.location.href = SITE_URL + '/admin.php?UserID=' + KriptoID;
                } else if (obj.warning) {
                    $("#result").html('<div class="alert alert-warning">' + obj.warning + '</div>');
                } else if (obj.danger) {
                    window.location.href = SITE_URL + '/signin.php';
                }
            }
        })
    }

    function getUserInfo() {
        const queryString = window.location.search;
        const urlParams = new URLSearchParams(queryString);
        const UserID = urlParams.get('UserID')
        const KriptoID = urlParams.get('KriptoID')
        $.ajax({
            type: "post",
            url: SITE_URL + '/ajaxprocesses.php?UserID=' + UserID + '&KriptoID=' + KriptoID + '&page=GetUserInfo',
            success: function(data) {
                const obj = JSON.parse(data);
                $("#UserFirstname").val(obj.UserFirstname);
                $("#UserLastname").val(obj.UserLastname);
                $("#UserName").val(obj.UserName);
                $("#UserEmail").val(obj.UserEmail);
            }
        })
    }

    function ExerciseUpdate(FormID, Operation){
        var myData = $("form#" + FormID).serialize();
        const queryString = window.location.search;
        const urlParams = new URLSearchParams(queryString);
        const UserID = urlParams.get('UserID')
        const ExerciseID = urlParams.get('ExerciseID')
        $.ajax({
            type: "post",
            url: SITE_URL + '/ajaxprocesses.php?page='+Operation+'&ExerciseID='+ExerciseID+'&UserID='+UserID,
            data: myData,
            success: function(data) {
                const obj = JSON.parse(data);
                if (obj.danger) {
                    window.location.href = SITE_URL + '/signin.php';
                } else if (obj.success) {
                    if (obj.position == "admin") {
                        setTimeout(function() {
                            window.location.href = SITE_URL + '/exercise.php?UserID=' + UserID;
                        }, 1500)
                        alert(obj.success)
                    } else if (obj.position == "user") {
                        setTimeout(function() {
                            window.location.href = SITE_URL + '/todo.php?UserID=' + UserID;
                        }, 1500)
                        alert(obj.success)
                    }
                } else if (obj.warning) {
                    $("#result").html('<div class="alert alert-warning">' + obj.warning + '</div>');
                }
            }
        })
    }

    function getExerciseInfo() {
        const queryString = window.location.search;
        const urlParams = new URLSearchParams(queryString);
        const UserID = urlParams.get('UserID')
        const ExerciseID = urlParams.get('ExerciseID')
        $.ajax({
            type: "post",
            url: SITE_URL + '/ajaxprocesses.php?UserID=' + UserID + '&ExerciseID=' + ExerciseID + '&page=getExerciseInfo',
            success: function(data) {
                const obj = JSON.parse(data);
                $("#ExerciseName").val(obj.ExerciseName);
            }
        })
    }
</script>
</body>
</html>
<?php
ob_end_flush();
?>

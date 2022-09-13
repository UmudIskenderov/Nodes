<?php
require_once "functions/combine.php";
require_once "classes/allClassNamespace.php";
$db = new classes\Database();
$operation = $_GET["page"];
$data = array();
switch ($operation) {
    case 'Signin':
        session_set_cookie_params(0, '/', 'localhost', false, true);
        session_start();
        if ($_SERVER["REQUEST_METHOD"] == 'POST') {
            $usernameoruseremail = security("UserNameOrUserEmail");
            $userpassword = security("UserPassword");
            if (empty($usernameoruseremail) or empty($userpassword)) {
                $data['warning'] = "Please, enter the text";
            } else {
                if (strlen($userpassword) < 8) {
                    $data['warning'] = "Password mustn't be less than 8 characters!";
                } else {
                    if (strlen($usernameoruseremail) > 50) {
                        $data['warning'] = "Username or email mustn't be more than 50 characters!";
                    } else {
                        $userpassword = md5(md5(md5(sha1($userpassword))));
                        $usercolumn = $db->getRow('SELECT * FROM users WHERE (UserEmail=? AND UserPassword=?) OR (UserName=? AND UserPassword=?)', array($usernameoruseremail, $userpassword, $usernameoruseremail, $userpassword));
                        $isHave = $usercolumn->UserID;
                        if (!$isHave) {
                            $data['danger'] = "User not found.";
                        } else {
                            if ($usercolumn->UserPosition == 1) {
                                session_regenerate_id(true);
                                $_SESSION["LogedIn"] = true;
                                $_SESSION["ID"] = $isHave;
                                $isHave = md5(md5(md5(sha1($isHave))));
                                $data['success'] = "admin";
                                $data['id'] = $isHave;
                            } elseif ($usercolumn->UserPosition == 2 && $usercolumn->UserStatus == 1) {
                                session_regenerate_id(true);
                                $_SESSION["LogedIn"] = true;
                                $_SESSION["ID"] = $isHave;
                                $isHave = md5(md5(md5(sha1($isHave))));
                                $data['success'] = "user";
                                $data['id'] = $isHave;
                            } else {
                                $data['danger'] = "Your account has been blocked, please contact admin.";
                            }
                        }
                    }
                }
            }
        }
        echo json_encode($data);
        break;

    case 'Signup':
        session_start();
        if ($_SERVER["REQUEST_METHOD"] == 'POST') {
            $userfirstname = security("UserFirstname");
            $userlastname = security("UserLastname");
            $username = security("UserName");
            $useremail = security("UserEmail");
            $userpassword = security("UserPassword");
            if (empty($userfirstname) or empty($userlastname) or empty($username) or empty($useremail) or empty($userpassword)) {
                $data['warning'] = "Please, fill in the blanks!";
            } else {
                if (strlen($userfirstname) < 3 or strlen($userlastname) < 3 or strlen($username) < 3) {
                    $data['warning'] = "Firstname, lastname and username mustn't be less than 3 characters!";
                } else {
                    if (strlen($userpassword) < 8) {
                        $data['warning'] = "Password mustn't be less than 8 characters!";
                    } else {
                        if (strlen($userfirstname) > 25 or strlen($userlastname) > 25 or strlen($userpassword) > 25) {
                            $data['warning'] = "Firstname and lastname and password mustn't be more than 25 characters!";
                        } else {
                            if (strlen($username) > 50 or strlen($useremail) > 50) {
                                $data['warning'] = "Username and email mustn't be more than 50 characters!";
                            } else {
                                if (!preg_match("/^[a-zA-ZıIğĞöÖüÜşŞçÇ\s]+$/u", $userfirstname) or !preg_match("/^[a-zA-ZıIğĞöÖüÜşŞçÇ\s]+$/u", $userlastname)) {
                                    $data['warning'] = "Enter your firstname and lastname correctly!";
                                } else {
                                    if (!filter_var($useremail, FILTER_VALIDATE_EMAIL)) {
                                        $data['warning'] = "Enter your email correctly!";
                                    } else {
                                        $userpassword = md5(md5(md5(sha1($userpassword))));
                                        $isHave = $db->getColumn('SELECT UserID FROM users WHERE UserEmail=?', array($useremail));
                                        if ($isHave) {
                                            $data['danger'] = "This email address have been used.";
                                        } else {
                                            $isHave = $db->getColumn('SELECT UserID FROM users WHERE UserName=?', array($username));
                                            if ($isHave) {
                                                $data['danger'] = "This username address have been used.";
                                            } else {
                                                if(empty($_FILES["myFile"]["name"])){
                                                    $data['warning'] = "Please, choose photo";
                                                }else{
                                                    $fileName=$_FILES["myFile"]["name"];
                                                    $fileTmp=$_FILES["myFile"]["tmp_name"];
                                                    $ext=strtolower(pathinfo($fileName,PATHINFO_EXTENSION));
                                                    $newName=rand()."_".time().".".$ext;
                                                    $myPath='images/'.$newName;
                                                    if(move_uploaded_file($fileTmp,$myPath)){
                                                        $add = $db->Insert('INSERT INTO users(UserFirstname,UserLastname,UserName,UserEmail,UserPassword,UserPicture) VALUES(?,?,?,?,?,?)', array($userfirstname, $userlastname, $username, $useremail, $userpassword,$newName));
                                                        if ($add) {
                                                            $userID = $db->getColumn('SELECT MAX(UserID) FROM users');
                                                            session_regenerate_id(true);
                                                            $_SESSION["LogedIn"] = true;
                                                            $_SESSION["ID"] = $userID;
                                                            $userID = md5(md5(md5(sha1($userID))));
                                                            $data['success'] = "Sign up successfully";
                                                            $data["id"] = $userID;
                                                        } else {
                                                            $data['danger'] = '<div class="alert alert-danger">An error occurred</div>';
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        echo json_encode($data);
        break;

    case "ExerciseAdd":
        session_start();
        $userID = $_GET['UserID'];
        if ($_SESSION["LogedIn"] == true && md5(md5(md5(sha1($_SESSION["ID"])))) == $userID) {
            $dekriptoID = $_SESSION["ID"];
            if ($_SERVER["REQUEST_METHOD"] == 'POST') {
                $exerciseName = security("ExerciseName");
                if (empty($exerciseName)) {
                    $data["warning"] = "Please, enter the text";
                } else {
                    $add = $db->Insert('INSERT INTO exercises(UserID,ExerciseName) VALUES(?,?)', array($dekriptoID, $exerciseName));
                    if ($add) {
                        $data["success"] = 'Task added';
                    } else {
                        $data["warning"] = 'An error occurred';
                    }
                }
            }
        } else {
            $data["danger"] = 'danger';
        }
        echo json_encode($data);
        break;

    case "UserDelete":
        session_start();
        $userID = $_GET['UserID'];
        if ($_SESSION["LogedIn"] == true && md5(md5(md5(sha1($_SESSION["ID"])))) == $userID) {
            if ($_SERVER["REQUEST_METHOD"] == 'POST') {
                $dekriptoID = $_SESSION["ID"];
                $deletemessage = $db->Delete('DELETE FROM exercises WHERE UserID=?', array($dekriptoID));
                $deleteuser = $db->Delete('DELETE FROM users WHERE UserID=?', array($dekriptoID));
                if ($deleteuser == true) {
                    $data['success'] = "Account delete succesfully";
                    session_unset();
                    session_destroy();
                }
            }
        } else {
            $data['danger'] = "An error occurred";
        }
        echo json_encode($data);
        break;

    case 'Logout':
        session_start();
        $userID = $_GET['UserID'];
        if ($_SESSION["LogedIn"] == true && md5(md5(md5(sha1($_SESSION["ID"])))) == $userID) {
            if ($_SERVER["REQUEST_METHOD"] == 'POST') {
                $data['success'] = "Succesfully logout";
                session_unset();
                session_destroy();
            }
        } else {
            $data['danger'] = "An error occured!";
        }
        echo json_encode($data);
        break;

    case 'getUserExerciseInfo':
        $i = 1;
        $myQuery = $db->getRows("SELECT
                                *
                                FROM exercises
                                INNER JOIN users ON users.UserID=exercises.UserID ORDER BY ExerciseID ASC");
        foreach ($myQuery as $items) {
            if ($items->UserStatus == 1) {
                $data[$i]["ExerciseID"] = $items->ExerciseID;
                $data[$i]['UserName'] = $items->UserName;
                $data[$i]['ExerciseName'] = $items->ExerciseName;
                $i++;
            }
        }
        echo json_encode($data);
        break;

    case 'ExerciseDelete':
        session_start();
        $userID = $_GET['UserID'];
        if ($_SESSION["LogedIn"] == true && md5(md5(md5(sha1($_SESSION["ID"])))) == $userID) {
            $exerciseID = intval($_GET['ExerciseID']);
            $dekriptoID = $_SESSION["ID"];
            $USERID = $db->getColumn("SELECT UserID FROM exercises WHERE ExerciseID=?", array($exerciseID));
            if (($db->getColumn("SELECT UserPosition FROM users WHERE UserID=?", array($dekriptoID))) == 1) {
                $del = $db->Delete('DELETE FROM exercises WHERE ExerciseID=?', array($exerciseID));
                if ($del) {
                    $data['success'] = "Delete successfully";
                    $data['userposition'] = "admin";
                }
            } elseif ($dekriptoID == $USERID) {
                $del = $db->Delete('DELETE FROM exercises WHERE ExerciseID=?', array($exerciseID));
                if ($del) {
                    $data['success'] = "Delete successfully";
                    $data['userposition'] = "user";
                }
            } else {
                $data['warning'] = "Cann't delete anybody's exercise";
            }
        } else {
            $data['danger'] = "danger";
        }
        echo json_encode($data);
        break;

    case 'AdminUserAdd':
        session_start();
        $userID = $_GET['UserID'];
        $dekriptoID = $_SESSION["ID"];
        if ($_SESSION["LogedIn"] == true && md5(md5(md5(sha1($_SESSION["ID"])))) == $userID) {
            if ($_SERVER["REQUEST_METHOD"] == 'POST') {
                $userfirstname = security("UserFirstname");
                $userlastname = security("UserLastname");
                $username = security("UserName");
                $useremail = security("UserEmail");
                $userpassword = security("UserPassword");
                if (empty($userfirstname) or empty($userlastname) or empty($username) or empty($useremail) or empty($userpassword)) {
                    $data['warning'] = "Please, fill in the blanks!";
                } else {
                    if (strlen($userfirstname) < 3 or strlen($userlastname) < 3 or strlen($username) < 3) {
                        $data['warning'] = "Firstname, lastname and username mustn't be less than 3 characters!";
                    } else {
                        if (strlen($userpassword) < 8) {
                            $data['warning'] = "Password mustn't be less than 8 characters!";
                        } else {
                            if (strlen($userfirstname) > 25 or strlen($userlastname) > 25 or strlen($userpassword) > 25) {
                                $data['warning'] = "Firstname and lastname and password mustn't be more than 25 characters!";
                            } else {
                                if (strlen($username) > 50 or strlen($useremail) > 50) {
                                    $data['warning'] = "Username and email mustn't be more than 50 characters!";
                                } else {
                                    if (!preg_match("/^[a-zA-ZıIğĞöÖüÜşŞçÇ\s]+$/u", $userfirstname) or !preg_match("/^[a-zA-ZıIğĞöÖüÜşŞçÇ\s]+$/u", $userlastname)) {
                                        $data['warning'] = "Enter your firstname and lastname correctly!";
                                    } else {
                                        if (!filter_var($useremail, FILTER_VALIDATE_EMAIL)) {
                                            $data['warning'] = "Enter your email correctly!";
                                        } else {
                                            $userpassword = md5(md5(md5(sha1($userpassword))));
                                            $isHave = $db->getColumn('SELECT UserID FROM users WHERE UserEmail=?', array($useremail));
                                            if ($isHave) {
                                                $data['warning'] = "This email address have been used.";
                                            } else {
                                                $isHave = $db->getColumn('SELECT UserID FROM users WHERE UserName=?', array($username));
                                                if ($isHave) {
                                                    $data['warning'] = "This username address have been used.";
                                                } else {
                                                    if(empty($_FILES["myFile"]["name"])){
                                                        $data['warning'] = "Please, choose photo";
                                                    }else{
                                                        $fileName=$_FILES["myFile"]["name"];
                                                        $fileTmp=$_FILES["myFile"]["tmp_name"];
                                                        $ext=pathinfo($fileName,PATHINFO_EXTENSION);
                                                        $newName=rand()."_".time().".".$ext;
                                                        $myPath='images/'.$newName;
                                                        if(move_uploaded_file($fileTmp,$myPath)){
                                                            $add = $db->Insert('INSERT INTO users(UserFirstname,UserLastname,UserName,UserEmail,UserPassword,UserPicture) VALUES(?,?,?,?,?,?)', array($userfirstname, $userlastname, $username, $useremail, $userpassword,$newName));
                                                            if ($add) {
                                                                $data['success'] = 'User added';
                                                            } else {
                                                                $data['warning'] = 'An error occurred';
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        } else {
            $data['danger'] = 'An error occurred';
        }
        echo json_encode($data);
        break;

    case 'UserInfoForAdmin':
        $i = 1;
        $myQuery = $db->getRows("SELECT
                                *
                                FROM users ORDER BY UserID ASC");
        foreach ($myQuery as $items) {
            $data[$i]["UserID"] = $items->UserID;
            $data[$i]["UserPicture"] = $items->UserPicture;
            $data[$i]['UserFirstname'] = $items->UserFirstname;
            $data[$i]['UserLastname'] = $items->UserLastname;
            $data[$i]['UserName'] = $items->UserName;
            $data[$i]['UserEmail'] = $items->UserEmail;
            $data[$i]['UserPosition'] = $items->UserPosition;
            $data[$i]['UserStatus'] = $items->UserStatus;
            $i++;
        }
        echo json_encode($data);
        break;

    case 'UserStatus':
        session_start();
        $userID = intval($_GET['UserID']);
        $kriptoID = $_GET['KriptoID'];
        if ($_SESSION["LogedIn"] == true && md5(md5(md5(sha1($_SESSION["ID"])))) == $kriptoID) {
            $userstatus = $db->getColumn("SELECT UserStatus FROM users WHERE UserID=?", array($userID));
            if ($_SERVER["HTTP_REFERER"] == "http://localhost/NM/admin.php?UserID=$kriptoID") {
                if ($userstatus == 1) {
                    $edit = $db->Update("UPDATE users SET UserStatus=? WHERE UserID=?", array(0, $userID));
                    if ($edit) {
                        $data['success'] = "Successfully";
                        $data['referer'] = "admin";
                    } else {
                        $data['warning'] = 'an error occurred';
                    }
                } elseif ($userstatus == 0) {
                    $edit = $db->Update("UPDATE users SET UserStatus=? WHERE UserID=?", array(1, $userID));
                    if ($edit) {
                        $data['success'] = "Successfully";
                        $data['referer'] = "admin";
                    } else {
                        $data['warning'] = 'an error occurred';
                    }
                }
            } elseif ($_SERVER["HTTP_REFERER"] == "http://localhost/NM/exercise.php?UserID=$kriptoID") {
                if ($userstatus == 1) {
                    $edit = $db->Update("UPDATE users SET UserStatus=? WHERE UserID=?", array(0, $userID));
                    if ($edit) {
                        $data['success'] = "Successfully";
                        $data['referer'] = "exercise";
                    } else {
                        $data['warning'] = 'an error occurred';
                    }
                } elseif ($userstatus == 0) {
                    $edit = $db->Update("UPDATE users SET UserStatus=? WHERE UserID=?", array(1, $userID));
                    if ($edit) {
                        $data['success'] = "Successfully";
                        $data['referer'] = "exercise";
                    } else {
                        $data['warning'] = 'an error occurred';
                    }
                }
            }
        } else {
            $data['danger'] = "danger";
        }
        echo json_encode($data);
        break;

    case 'ExerciseInfoForAdmin':
        $i = 1;
        $myQuery = $db->getRows("SELECT
                                *
                                FROM exercises
                                INNER JOIN users ON users.UserID=exercises.UserID ORDER BY ExerciseID ASC");
        foreach ($myQuery as $items) {
            $data[$i]["ExerciseID"] = $items->ExerciseID;
            $data[$i]['UserName'] = $items->UserName;
            $data[$i]['ExerciseName'] = $items->ExerciseName;
            $data[$i]['UserID'] = $items->UserID;
            $data[$i]['UserPosition'] = $items->UserPosition;
            $data[$i]['UserStatus'] = $items->UserStatus;
            $i++;
        }
        echo json_encode($data);
        break;

    case 'UserUpdate':
        session_start();
        $userID = intval($_GET['UserID']);
        $kriptoID = $_GET['KriptoID'];
        if ($_SESSION["LogedIn"] == true && md5(md5(md5(sha1($_SESSION["ID"])))) == $kriptoID) {
            if ($_SERVER["REQUEST_METHOD"] == 'POST') {
                $userfirstname = security("UserFirstname");
                $userlastname = security("UserLastname");
                $username = security("UserName");
                $useremail = security("UserEmail");
                $userpassword = security("UserPassword");
                if (empty($userfirstname) or empty($userlastname) or empty($username) or empty($useremail) or empty($userpassword)) {
                    $data['warning'] = "Please, fill in the blanks!";
                } else {
                    if (strlen($userfirstname) < 3 or strlen($userlastname) < 3 or strlen($username) < 3) {
                        $data['warning'] = "Firstname, lastname and username mustn't be less than 3 characters!";
                    } else {
                        if (strlen($userpassword) < 8) {
                            $data['warning'] = "Password mustn't be less than 8 characters!";
                        } else {
                            if (strlen($userfirstname) > 25 or strlen($userlastname) > 25 or strlen($userpassword) > 25) {
                                $data['warning'] = "Firstname and lastname and password mustn't be more than 25 characters!";
                            } else {
                                if (strlen($username) > 50 or strlen($useremail) > 50) {
                                    $data['warning'] = "Username and email mustn't be more than 50 characters!";
                                } else {
                                    if (!preg_match("/^[a-zA-ZıIğĞöÖüÜşŞçÇ\s]+$/u", $userfirstname) or !preg_match("/^[a-zA-ZıIğĞöÖüÜşŞçÇ\s]+$/u", $userlastname)) {
                                        $data['warning'] = "Enter your firstname and lastname correctly!";
                                    } else {
                                        if (!filter_var($useremail, FILTER_VALIDATE_EMAIL)) {
                                            $data['warning'] = "Enter your email correctly!";
                                        } else {
                                            $userpassword = md5(md5(md5(sha1($userpassword))));
                                            $isHave = $db->getColumn('SELECT UserID FROM users WHERE UserEmail=?', array($useremail));
                                            if ($isHave) {
                                                $data['warning']  = "This email address have been used.";
                                            } else {
                                                $isHave = $db->getColumn('SELECT UserID FROM users WHERE UserName=?', array($username));
                                                if ($isHave) {
                                                    $data['warning'] = "This username address have been used.";
                                                } else {
                                                    $edit = $db->Update('UPDATE users SET UserFirstname=?,UserLastname=?,UserName=?,UserEmail=?,UserPassword=? WHERE UserID=?', array($userfirstname, $userlastname, $username, $useremail, $userpassword, $userID));
                                                    if ($edit) {
                                                        $data['success'] = "success";
                                                    } else {
                                                        $data['warning'] = 'An error occurred';
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        } else {
            $data['danger'] = "danger";
        }
        echo json_encode($data);
        break;

    case "GetUserInfo":
        session_start();
        $userID = intval($_GET['UserID']);
        $kriptoID = $_GET['KriptoID'];
        if ($_SESSION["LogedIn"] == true && md5(md5(md5(sha1($_SESSION["ID"])))) == $kriptoID) {
            $users = $db->getRow("SELECT * FROM users WHERE UserID=?", array($userID));
            if ($_SERVER["REQUEST_METHOD"] == 'POST') {
                echo json_encode($users);
            }
        } else {
            echo '{"message": "' . $message . '"}';
        }
        break;

    case 'ExerciseUpdate':
        session_start();
        $userID = $_GET['UserID'];
        if ($_SESSION["LogedIn"] == true && md5(md5(md5(sha1($_SESSION["ID"])))) == $userID) {
            $exerciseID = intval($_GET['ExerciseID']);
            $exercises = $db->getRow("SELECT * FROM exercises WHERE ExerciseID=?", array($exerciseID));
            $USERID = $exercises->UserID;
            $dekriptoID = $_SESSION["ID"];
            if ($_SERVER["REQUEST_METHOD"] == 'POST') {
                $exerciseName = security("ExerciseName");
                if (empty($exerciseName)) {
                    $data['warning'] = "Please, enter the text";
                } else {
                    if (($db->getColumn("SELECT UserPosition FROM users WHERE UserID=?", array($dekriptoID))) == 1) {
                        $data['position'] = "admin";
                        $edit = $db->Update('UPDATE exercises SET ExerciseName=? WHERE ExerciseID=?', array($exerciseName, $exerciseID));
                        if ($edit) {
                            $data['success'] = "success";
                        } else {
                            $data['warning'] = 'an error occurred';
                        }
                    } elseif ($USERID == $dekriptoID) {
                        $data['position'] = "user";
                        $edit = $db->Update('UPDATE exercises SET ExerciseName=? WHERE ExerciseID=?', array($exerciseName, $exerciseID));
                        if ($edit) {
                            $data['success'] = "success";
                        } else {
                            $data['warning'] = 'an error occurred';
                        }
                    } else {
                        $data['warning'] = "Cann't update anybody's exercise";
                    }
                }
            }
        } else {
            $data['danger'] = 'danger';
        }
        echo json_encode($data);
        break;

    case 'getExerciseInfo':
        session_start();
        $exerciseID = intval($_GET['ExerciseID']);
        $kriptoID = $_GET['UserID'];
        if ($_SESSION["LogedIn"] == true && md5(md5(md5(sha1($_SESSION["ID"])))) == $kriptoID) {
            $exerciseName = $db->getColumn("SELECT ExerciseName FROM exercises WHERE ExerciseID=?", array($exerciseID));
            if ($_SERVER["REQUEST_METHOD"] == 'POST') {
                $data['ExerciseName'] = $exerciseName;
            }
        } else {
            $data['danger'] = "danger";
        }
        echo json_encode($data);
        break;
}

<?php
function security($text){
    $text=trim($_POST[$text]);
    $text=stripslashes($text);
    $text=htmlspecialchars($text);
    return $text;
}
?>
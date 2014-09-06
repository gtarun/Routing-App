<?php
if(!isset($_SESSION['USER_ID']))
{
    $_SESSION['MSG'] = "Please Login First";
    header("location:login.php");
    exit;
}
 ?>
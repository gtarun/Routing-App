<?php
ob_start();
session_start();
unset($_SESSION['USER_ID']);
header("location:login.php");
 ?>
<?php
ob_start();
session_start();
include('includes/func.php');
    
if($_SERVER['SERVER_NAME']=="localhost")
{
    $db = mysql_connect("localhost", "root", "")
    or die("Unable to connect database");
    mysql_select_db("hackaton", $db)
    or die("Unable to find database");
}
 ?>
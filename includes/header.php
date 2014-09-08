<?php
include('config.php');
$is_login = false;
if(isset($_SESSION['USER_ID']))
{
    if($_SESSION['USER_ID'] > 0)
    {
        $is_login = true;
        $user_detail = user_detail();
    }
    
}
 ?>
<!DOCTYPE html>
<html lang="en">
  <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Venturepact Routing Application</title>
    
        <!-- Bootstrap -->
        <link href="css/bootstrap.css" rel="stylesheet">
        <link href="css/custom.css" rel="stylesheet">
         <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="js/bootstrap.min.js"></script>
    
  </head>
  <body>
       <div class="navbar top_header_color" role="navigation">
              <div class="container">
                    <div class="navbar-header">
                      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                      </button>
                      
                    </div>
                    <?php
                    if(isset($_SESSION['USER_ID'])){
                        $user_detail = user_detail($_SESSION['USER_ID']);    
                    }
                    
                     ?>
                    <div class="navbar-collapse collapse">
                      
                        <ul class="nav navbar-nav navbar-right nav_bar_a_color">
                            <li><a href="javascript:Void()"><span class=" glyphicon glyphicon-eye-open"></span> Welcome <?php if(isset($_SESSION['USER_ID'])){ echo $user_detail['email'];  }else{ echo "Guest"; } ?></a></li>
                            <?php
                            if(isset($_SESSION['USER_ID'])){
                             ?>
                            <li><a href="source_destiny.php"><span class=" glyphicon glyphicon-eye-open"></span> Add</a></li>
                            <?php
                            }
                             ?>
                             
                            <li><a href="listing.php"><span class=" glyphicon glyphicon-eye-open"></span> Search</a></li>
                            <?php
                            if(isset($_SESSION['USER_ID'])){
                             ?>
                                <li><a href="logout.php"><span class=" glyphicon glyphicon-eye-open"></span> Logout</a></li>
                            <?php
                            }else{
                                ?>
                                <li><a href="login.php"><span class=" glyphicon glyphicon-eye-open"></span> Login</a></li>
                                <?php
                                
                            }
                             ?>
                        </ul>
                        
                     
                    </div><!--/.navbar-collapse -->
              </div>
        </div>
        
        <div class="container-fluid logo_div_color"> 
        
              <div class="container">
                    <div class="col-sm-8">
                      <a href="index.html" style="color: #000;text-decoration: none!important;"><h1>Venturepact Routing Application</h1></a> 
                    </div>
                  
               </div> 
        </div>
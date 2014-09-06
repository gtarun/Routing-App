<?php
include('includes/header.php');
 ?>

     <?php
     $sMsg = "";
     if(isset($_REQUEST['submit']))
     {
         $email = $_REQUEST["email"];
         $password = $_REQUEST["password"];
         
       
         if(frSafeChar("email")==""){
            $sMsg = $sMsg."Please enter the email!<br/>";
         }elseif(strpos(frSafeChar("email"),"@")===false || strpos(frSafeChar("email"),".")===false){
            $sMsg = $sMsg."Please enter valid email address!<br/>";
         }
         
         if($password=="")
         {
            $sMsg = $sMsg."Please enter password!<br/>";
         }
         
         
         if($sMsg=="")
         {
            
                $query = mysql_query("select * from users where email='$email' and password='$password'") or die("error".mysql_error());
                if(mysql_num_rows($query) > 0)
                {
                    $row = mysql_fetch_array($query);
                    $_SESSION['USER_ID'] = $row['user_id'];
                    ob_clean();
                    header("location:source_destiny.php");
                    exit;
                }else{
                    $sMsg = $sMsg."Wrong username or password!<br/>";
                        
                }
                
          
         }       
         
         
         
     }
      ?>  
        <div class="container main_body">
                
                <?php
                
                if($sMsg!=""){
                    echo "<div class='msg'>".$sMsg."</div>";
                }
                if(isset($_SESSION['MSG']))
                {
                    echo $_SESSION['MSG'];
                    unset($_SESSION['MSG']);
                }
                ?>
                
              
             <h4>Login</h4>
              <form role="form" method="post">
                      <div class="form-group">
                        <label for="exampleInputEmail1">Email</label>
                        <input type="email" class="form-control" name="email" value="<?php if(isset($_REQUEST['email'])){ echo $_REQUEST['email']; } ?>" id="exampleInputEmail1" placeholder="Enter email"/>
                      </div>
                      <div class="form-group">
                        <label for="exampleInputPassword1">Password</label>
                        <input type="password" class="form-control" name="password" value="<?php if(isset($_REQUEST['password'])){ echo $_REQUEST['password']; } ?>" id="exampleInputPassword1" placeholder="Password"/>
                      </div>
                      
                      <button type="submit" name="submit" class="btn btn-default">Submit</button>
                      <br /><br />
                </form>

              
        </div>
    
    <?php
    include('includes/footer.php');
     ?>
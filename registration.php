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
         
         $query_chk_email = mysql_query("select 1 from users where email='$email'") or die("err".mysql_error());
         if(mysql_num_rows($query_chk_email) > 0)
         {
            $sMsg = $sMsg."Email already exist!<br/>";
         }
         
         if($sMsg=="")
         {
            
                mysql_query("insert into users(email,password)values('$email','$password')") or die("error".mysql_error());
                ob_clean();
                $_SESSION['MSG'] = "<div class='msg'>You are successfully registered with us. Now you can login</div>";
                header("location:registration.php");
                exit;
          
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
                
              
              <h4>Registration</h4>
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
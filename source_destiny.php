<?php
include('includes/header.php');
include('is_login.php');
 ?>

     <?php
     $sMsg = "";
     if(isset($_REQUEST['submit']))
     {
         
         $s_terminal = $_REQUEST["s_terminal"];
         
       
         
         if($s_terminal=="")
         {
            $sMsg = $sMsg."Please enter Source Terminal!<br/>";
         }
         
         
         
         if($sMsg=="")
         {
            
               // mysql_query("insert into users(email,password)values('$email','$password')") or die("error".mysql_error());
                ob_clean();
                $_SESSION['MSG'] = "<div class='msg'></div>";
                header("location:listing.php");
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
                
              
              <h4>test</h4>
              <form role="form" method="post">
                    
                   
                      <div class="form-group">
                        <label for="exampleInputPassword1">Source Terminal</label>
                        <?php
                        include('google_map.php');
                         ?>
                      </div>
                      
                     <div class="form-group">
                        <label for="exampleInputPassword1">Type</label>
                        <select class="form-control" name="vehicle_type">
                            <option value="">Select Type</option>
                            <option value="1">Bus</option>
                            <option value="2">Train</option>
                            
                        </select>
                      </div>
                     
                      <button type="submit" name="submit" class="btn btn-default">Submit</button>
                      <br /><br />
                </form>

              
        </div>
    
    <?php
    include('includes/footer.php');
     ?>
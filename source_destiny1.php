<?php
include('includes/header.php');
include('is_login.php');
 ?>

     <?php
     $sMsg = "";
     if(isset($_REQUEST['submit']))
     {
         
         $d_terminal = $_REQUEST["route"];
         $d_city     = $_REQUEST['city'];
         $d_state    = $_REQUEST['state'];
         $d_postal   = $_REQUEST['postal_code'];
         $d_country  = $_REQUEST['country'];

        
         
         
       //  if($s_terminal=="")
//         {
//            $sMsg = $sMsg."Please choose Terminal!<br/>";
//         }
//         if($country=="")
//         {
//            $sMsg = $sMsg."Please choose Country!<br/>";
//         }
//         if($state=="")
//         {
//            $sMsg = $sMsg."Please choose State!<br/>";
//         }
//         if($city=="")
//         {
//            $sMsg = $sMsg."Please choose City!<br/>";
//         }
         
         $d_datetime = date('Y-m-d h:i:s');
         
         if($sMsg=="")
         {
                
                mysql_query("update source_destiny set d_terminal='$d_terminal',d_country='$d_country',d_state='$d_state',d_city='$d_city',d_datetime='$d_datetime' where id=".$_REQUEST['id']) or die("err".mysql_error());
               
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
                
    
   
              <h4>Choose Destination</h4>
              <form role="form" method="post">
                    
                   
                  
                   
                      <div class="form-group">
                        <label for="exampleInputPassword1">Destination Terminal</label>
                        <?php
                        include('google_map.php');
                         ?>
                      </div>
                      
                      

                     
                      <button type="submit" name="submit" class="btn btn-default">Submit</button>
                      <br /><br />
                </form>

              
        </div>
  
    <?php
    include('includes/footer.php');
     ?>
<?php
include('includes/header.php');
include('is_login.php');
 ?>

     <?php
     $sMsg = "";
     if(isset($_REQUEST['submit']))
     {
         
         $s_terminal = $_REQUEST["route"];
         $s_city     = $_REQUEST['city'];
         $s_state    = $_REQUEST['state'];
         $s_postal   = $_REQUEST['postal_code'];
         $s_country  = $_REQUEST['country'];
         $vehicle_type = $_REQUEST['vehicle_type'];
        
         if($vehicle_type=="")
         {
            $sMsg = $sMsg."Please choose Vehicle Type!<br/>";
         }
         
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
         
         $s_datetime = date('Y-m-d h:i:s');
         
         if($sMsg=="")
         {
            
                mysql_query("insert into source_destiny(user_id,s_terminal,s_country,s_state,s_city,type,s_datetime)values('".$_SESSION['USER_ID']."','$s_terminal','$s_country','$s_state','$s_city','$vehicle_type','$s_datetime')") or die("error".mysql_error());
                $insert_id = mysql_insert_id();
                ob_clean();
                $_SESSION['MSG'] = "<div class='msg'></div>";
                header("location:source_destiny1.php?id=".$insert_id);
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
                
    
   
              <h4>Choose Source</h4>
              <form role="form" method="post">
                    
                   
                    <div class="form-group">
                        <label for="exampleInputPassword1">Type</label>
                        <select class="form-control" name="vehicle_type">
                            <option value="">Select Type</option>
                            <option value="1">Bus</option>
                            <option value="2">Train</option>
                            
                        </select>
                      </div>
                   
                      <div class="form-group">
                        <label for="exampleInputPassword1">Source Terminal</label>
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
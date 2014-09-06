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
         
         $d_terminal = $_REQUEST["d_route"];
         $d_city     = $_REQUEST['d_city'];
         $d_state    = $_REQUEST['d_state'];
         $d_postal   = $_REQUEST['d_postal_code'];
         $d_country  = $_REQUEST['d_country'];
         
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
                
              <link type="text/css" rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500" />
   
    
    <style>
      #map-canvas {
        height: 100%;
        margin: 0px;
        padding: 0px
      }
    </style>
              <h4>test</h4>
              <form role="form" method="post">
                    
                   
                      <div class="form-group">
                        <label for="exampleInputPassword1">Source Terminal</label>
                        <?php
                        //include('google_map.php');
                         ?>
                      </div>
                      
                      <div class="form-group">
                        <label for="exampleInputPassword1">Destiny Terminal</label>
                        <?php
                        include('google_map1.php');
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
    <script type="text/javascript">
    var placeSearch, autocomplete;
        var autocomplete1;
    function initialize() {
  // Create the autocomplete object, restricting the search
  // to geographical location types.
 // autocomplete = new google.maps.places.Autocomplete(
//      /** @type {HTMLInputElement} */(document.getElementById('autocomplete')),
//      { types: ['geocode'] });
//  // When the user selects an address from the dropdown,
//  // populate the address fields in the form.
//  google.maps.event.addListener(autocomplete, 'place_changed', function() {
//    fillInAddress();
//  });
  
  
   autocomplete1 = new google.maps.places.Autocomplete(
      /** @type {HTMLInputElement} */(document.getElementById('autocomplete1')),
      { types: ['geocode'] });
  // When the user selects an address from the dropdown,
  // populate the address fields in the form.
  google.maps.event.addListener(autocomplete1, 'place_changed', function() {
    fillInAddress1();
  });
}
    
       window.onload = initialize();
        
    </script>
    <?php
    include('includes/footer.php');
     ?>
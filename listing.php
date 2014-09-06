<?php
include('includes/header.php');
include('is_login.php');
 ?>
        
        <div class="container main_body">
              <?php
               if(isset($_SESSION['MSG']))
                {
                    echo $_SESSION['MSG'];
                    unset($_SESSION['MSG']);
                }
                ?>
 
         <div class="col-md-2">
            <div class="search_filters">
                <font class="title_size">Filter</font>
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Search" />
                              
                    <div class="clear_class"></div>
                </div>
                
            </div>
            
            <div class="search_filters">
              
                 
                 <button class="btn btn-default" name="submit" type="submit">Search</button>
                
            </div>
            
            
            
                               
         </div>
         
          <div class="col-md-10">
              
                       <div class="press_div">
                          listing listing
                       </div>
                       <div class="press_div">
                          listing listing
                       </div>    <div class="press_div">
                          listing listing
                       </div>       
         </div>
                    
                
         
          
        </div>
    
    <?php
    include('includes/footer.php');
     ?>
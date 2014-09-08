<?php
include('includes/header.php');

 ?>
        
        <div class="container main_body">
              <?php
               if(isset($_SESSION['MSG']))
                {
                    echo $_SESSION['MSG'];
                    unset($_SESSION['MSG']);
                }
                ?>
         <form action="listing.php">
             <div class="col-md-2">
                <div class="search_filters">
                    <font class="title_size">Filter</font>
                    <div class="form-group">
                       <select class="form-control" name="vehicle_type">
                            <option value="">Select Type</option>
                            <option value="1">Bus</option>
                            <option value="2">Train</option>
                            
                        </select><br />
                    
                        <input type="text" class="form-control" name="source" placeholder="Source" /><br />
                        <input type="text" class="form-control" name="destination" placeholder="Destination" />          
                        <div class="clear_class"></div>
                    </div>
                    
                </div>
                
                <div class="search_filters">
                  
                     
                     <button class="btn btn-default" name="submit" type="submit">Search</button>
                    
                </div>
                
                
                
                                   
             </div>
         </form>
          <div class="col-md-10">
              <?php
              $where = "";
              
              if(isset($_REQUEST['vehicle_type']))
              {
                $where .= " and type='".$_REQUEST['vehicle_type']."' ";
              }
              
              if(isset($_REQUEST['source']) && isset($_REQUEST['destination']))
              {
                $where .= " and (s_terminal like'%".$_REQUEST['source']."%' or d_terminal like'%".$_REQUEST['destination']."%') ";
              }
              
             // echo "select *,DATE_FORMAT(s_datetime,'%H:%i:%s')as from_date,DATE_FORMAT(d_datetime,'%H:%i:%s')as to_date from source_destiny where 1=1 $where order by id desc";
              $query = mysql_query("select *,DATE_FORMAT(s_datetime,'%H:%i:%s')as from_date,DATE_FORMAT(d_datetime,'%H:%i:%s')as to_date from source_destiny where 1=1 $where order by id desc") or die("err".mysql_error());
              if(mysql_num_rows($query) > 0)
              {
                while($row = mysql_fetch_array($query))
                {
                    ?>
                     <div class="press_div">
                        <b>Vehicle Type : </b> <?php echo ($row['type']==1)?"Bus":"Train"; ?><br />
                        <b>Source :</b>  <?php echo $row['s_terminal']; ?>      <br />
                        <b>Destination :</b> <?php echo $row['d_terminal']; ?>   <br />
                        <b>Timing :</b> 
                         <?php 
                         echo $row['from_date']." - ".$row['to_date'];
                          ?>     <br />
                        <b>Description :</b> <?php echo $row['vehicle_detail']; ?>    <br />
                     </div>
                    <?php
                }
              }else{
                echo "No Vehicle found..";
              }
               ?>
                      
                      
         </div>
                    
                
         
          
        </div>
    
    <?php
    include('includes/footer.php');
     ?>
<?php
include('includes/header.php');

 ?>
<style>
.press_div
{
    font-size: 12px;
}
</style>        
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
                            <option value="1" <?php if(isset($_REQUEST['vehicle_type'])){ echo ($_REQUEST['vehicle_type']==1)?"selected='selected'":""; } ?>>Bus</option>
                            <option value="2" <?php if(isset($_REQUEST['vehicle_type'])){ echo ($_REQUEST['vehicle_type']==2)?"selected='selected'":""; } ?>>Train</option>
                            
                        </select><br />
                    
                        <input type="text" class="form-control" name="source" value="<?php if(isset($_REQUEST['source'])) echo $_REQUEST['source']; ?>" placeholder="Source" /><br />
                        <input type="text" class="form-control" name="destination" value="<?php if(isset($_REQUEST['destination'])) echo $_REQUEST['destination']; ?>" placeholder="Destination" />          
                        <div class="clear_class"></div>
                    </div>
                    
                </div>
                
                <div class="search_filters">
                  
                     
                     <button class="btn btn-default" name="submit" type="submit">Search</button> <a href="listing.php">Show all</a>
                    
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
                        <?php
                        if(false)
                        {
                         ?>
                            <b>Vehicle Type : </b> <?php echo ($row['type']==1)?"Bus":"Train"; ?><br />
                            <b>Source :</b>  <?php echo $row['s_terminal']; ?>      <br />
                            <b>Destination :</b> <?php echo $row['d_terminal']; ?>   <br />
                            <b>Timing :</b> 
                             <?php 
                             echo $row['from_date']." - ".$row['to_date'];
                              ?>     <br />
                            <b>Description :</b> <?php echo $row['vehicle_detail']; ?>    <br />
                        <?php
                        }
                         ?>
                         <div class="col-md-4">
                                <b>From:</b><br />
                             <?php echo $row['s_terminal']; ?>   
                         </div>
                         <div class="col-md-4">
                            <?php
                            $img_name = "bus.png";
                            if($row['type']==2)
                            {
                                $img_name = "train.png";
                            }
                           
                             ?>
                             <img src="images/<?php echo $img_name; ?>" />
                         </div>
                         <div class="col-md-4">
                            <b>To:</b><br />
                            <?php echo $row['d_terminal']; ?>
                         </div>
                         
                         <div style="clear: both;"></div>
                         
                         <div style="margin-left: 1.4%;">
                         <b>Timing :</b> 
                             <?php 
                             echo $row['from_date']." - ".$row['to_date'];
                              ?>
                         </div>     
                     </div>
                     
                     
                    <?php
                }
              }else{
                echo "No Vehicle found..";
              }
               ?>
                      
             <br />         
         </div>
                    
                
         
          
        </div>
    
    <?php
    include('includes/footer.php');
     ?>
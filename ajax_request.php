<?php
require_once('config.php');

$action = $_REQUEST['action'];
switch ($action)
{
case "get_countries":
   $country_id = $_REQUEST['country'];
   $id = $_REQUEST['id1'];
   $query = mysql_query("select region_id,region from region where country_id = '$country_id' order by region") or die("err".mysql_error());
   if(mysql_num_rows($query) > 0)
   {
      echo "<option value=''>Select Province</option>";
      while($row=mysql_fetch_array($query))
      {
        ?>
        <option <?php if($id==$row['region_id']){ echo "selected=selected"; } ?> value="<?php echo $row['region_id'] ?>"><?php echo $row['region'] ?></option>
        <?php
      }
   }
   
    
   
	
  break;
  
  
  case "get_states":
   $state_id = $_REQUEST['state'];
   $id = $_REQUEST['id1'];
   $query = mysql_query("select city_id,city from city where region_id = '$state_id' order by city") or die("err".mysql_error());
   if(mysql_num_rows($query) > 0)
   {
      echo "<option value=''>Select City</option>";
      while($row=mysql_fetch_array($query))
      {
        ?>
        <option <?php if($id==$row['city_id']){ echo "selected=selected"; } ?> value="<?php echo $row['city_id'] ?>"><?php echo $row['city'] ?></option>
        <?php
      }
   }
    
   
	
  break;
  
  
  case "del_myjob_listing":
   $jid = $_REQUEST['jid'];
    
   
    mysql_query("update apply_jobs set is_delete='1' where user_id='".$_SESSION['user_id']."' and  job_id='".$jid."'") or die("error".mysql_error());
	
  break;

case "add_job":
   $jid          = $_REQUEST['job_id'];
   $cover_letter = $_REQUEST['cover_letter']; 
   
    mysql_query("insert into apply_jobs(job_id,user_id,cover_letter)values('$jid','".$_SESSION['user_id']."','$cover_letter')") or die("error".mysql_error());
	
  break;
  
case "sub_cat":
   $parent_id    = $_REQUEST['id'];
   $hidden_id    = $_REQUEST['sub_id'];
   $query = mysql_query("select * from category where p_id='$parent_id' and category<>'All' order by category") or die("error".mysql_error());
    ?>
   
        <?php
        while($row = mysql_fetch_array($query))
        {
            ?>
            <option  value="<?php echo $row['id'] ?>"  <?php if($hidden_id > 0){ echo ($row['id']==$hidden_id)?"selected='selected'":""; } ?> ><?php echo $row['category'] ?></option>
            <?php
        }
        ?>
    
    <?php
  break;

  
case "user_ac_inac":
   $user_id = $_REQUEST['user_id'];
   $active = $_REQUEST['active'];
    if(mysql_query("update users set active='$active' where user_id='".$user_id."'"))
	{
		echo "1";
	}else{
	 	echo "0";	
	}
  break;
  
  case "chk_user":
  
      $user_name = $_REQUEST['user_name'];
      $query = mysql_query("select 1 from user where username='$user_name'") or die("error".mysql_error());
      if(mysql_num_rows($query) > 0)
      {
        echo "1";
      }
  
  break;
  
   case "chk_email":
  
      $email = $_REQUEST['email'];
      $query = mysql_query("select 1 from user where email='$email'") or die("error".mysql_error());
      if(mysql_num_rows($query) > 0)
      {
        echo "1";
      }
  
  break;
  
  case "subscription_users":
      $first_name = $_REQUEST['first_name'];
      $last_name = $_REQUEST['last_name'];
      $city = $_REQUEST['city'];
      $email = $_REQUEST['email'];
      
      if(mysql_query("insert into subscription_users(first_name,last_name,email,city)values('$first_name','$last_name','$email','$city')"))
      {
        echo "1";
      }else{
        echo "0";
      }
    
  break;
  
  
   case "on_popup":
      $id = $_REQUEST['id'];
      country_city_detail($id);
    
  break;
  
     case "get_post_models":
      $id = $_REQUEST['make_id'];
      $query = mysql_query("select * from models where make_id = '$id'") or die("error".mysql_error());
      if(mysql_num_rows($query) > 0)
      {
        echo "<option value=''>Select Models</option>";
        while($row = mysql_fetch_array($query))
        {
            ?>
            <option value="<?php echo $row['title'] ?>"><?php echo $row['title'] ?></option>
            <?php
        }
      }
    
  break;
  
  
  case "get_sub_categories":
  
    $category = $_REQUEST['category'];
    $query = mysql_query("select * from sub_categories where category_id = '$category'") or die("err".mysql_error());
    if(mysql_num_rows($query) > 0)
    {
        while($row = mysql_fetch_array($query))
        {
            ?>
            <option value="<?php echo $row['sub_category_id']; ?>"><?php echo $row['sub_category']; ?></option>
            <?php
        }
    }
  
  break;
  
  case "get_regions":
        $country_id = $_REQUEST['id'];
        $query = mysql_query("select region_id,region from region where country_id = '$country_id' order by region") or die("err".mysql_error());
        if(mysql_num_rows($query) > 0)
        {
            echo "<option value=''>Select Region</option>";
            while($row=mysql_fetch_array($query))
            {
                ?>
                <option value="<?php echo $row['region_id']; ?>"><?php echo $row['region']; ?></option>
                <?php
            }
        }
        
  break;
  
  
   case "get_cities":
        $region_id = $_REQUEST['id'];
        
        $query = mysql_query("select `city_id`,`city` from city where region_id = '$region_id' order by `city`") or die("err".mysql_error());
        if(mysql_num_rows($query) > 0)
        {
            echo "<option value=''>Select City</option>";
            while($row=mysql_fetch_array($query))
            {
                ?>
                <option value="<?php echo $row['city_id']; ?>"><?php echo $row['city']; ?></option>
                <?php
            }
        }else{
            echo "<option value=''>No City Found..</option>";
        }
        
  break;
  
  
default:
  
} 
?>
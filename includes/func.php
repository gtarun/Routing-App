<?php
$url = $_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
if(strpos($url,"http")===false){
    $url = "http://".$url;
}
if(strpos($url,"www.")!==false){
    $url  = str_replace("www.","",$url);
    header('Location: '.$url);
}


global $sSqlInsertInit, $sSqlInsertColsName, $sSqlInsertColsValue;
global $sSqlUpdateInit, $sSqlUpdateColsNameValue, $sSqlUpdateWhereClause;
global $validate_msg;
/*
$field_name: filed name on which you want to make validation
$show_name: after validation which name you want to show
$rules: required
        required_num
        valid_num
        required_num
        required_email
        valid_email
*/
function show_left_letter($var, $length){
    if(strlen($var)<=$length){
        return $var;
    }else{
        return substr($var, 0, $length)."...";
    }
}
function maintain_ssl($bHTTPS=true){
    if(false){  
        $blocal = (strpos($_SERVER['SERVER_NAME'],"localhost")!==false || strpos($_SERVER['SERVER_NAME'],"solgens.net")!==false || strpos($_SERVER['SERVER_NAME'],"uc.hellasgear.com")!==false);
        if ($bHTTPS && !$blocal && $_SERVER['SERVER_PORT']==80){
            $url = $_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
            if(strpos($url,"http")===false){
                $url = "http://".$url;
            }
            $url = str_replace("http://","https://",$url);
            $url  = str_replace("www.","",$url);
            header('Location: '.$url);
        }elseif (!$bHTTPS && !$blocal && $_SERVER['SERVER_PORT']==443){
            $url = $_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
            if(strpos($url,"http")===false){
                $url = "http://".$url;
            }
            $url = str_replace("https://","http://",$url);
            header('Location: '.$url);
        }
    }
}
function encrypt($string, $key) {
    $result = '';
    for($i=0; $i<strlen($string); $i++) {
        $char = substr($string, $i, 1);
        $keychar = substr($key, ($i % strlen($key))-1, 1);
        $char = chr(ord($char)+ord($keychar));
        $result.=$char;
    }
    return base64_encode($result);
}

function decrypt($string, $key) {
    $result = '';
    $string = base64_decode($string);
    for($i=0; $i<strlen($string); $i++) {
        $char = substr($string, $i, 1);
        $keychar = substr($key, ($i % strlen($key))-1, 1);
        $char = chr(ord($char)-ord($keychar));
        $result.=$char;
    }
    return $result;
}

function PPHttpPost($methodName_, $nvpStr_) {
	global $gsPaypalURL, $gsPaypalUsername, $gsPaypalPassword, $gsPaypalSig;

	// Set up your API credentials, PayPal end point, and API version.
	$API_UserName = urlencode($gsPaypalUsername);
	$API_Password = urlencode($gsPaypalPassword);
	$API_Signature = urlencode($gsPaypalSig);
	$API_Endpoint = $gsPaypalURL;

	$version = urlencode('51.0');

	// Set the curl parameters.
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $API_Endpoint);
	curl_setopt($ch, CURLOPT_VERBOSE, 1);

	// Turn off the server and peer verification (TrustManager Concept).
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);

	// Set the API operation, version, and API signature in the request.
	$nvpreq = "METHOD=$methodName_&VERSION=$version&PWD=$API_Password&USER=$API_UserName&SIGNATURE=$API_Signature$nvpStr_";

	// Set the request as a POST FIELD for curl.
	curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq);

	// Get response from the server.
	$httpResponse = curl_exec($ch);

	if(!$httpResponse) {
		exit("$methodName_ failed: ".curl_error($ch).'('.curl_errno($ch).')');
	}

	// Extract the response details.
	$httpResponseAr = explode("&", $httpResponse);

	$httpParsedResponseAr = array();
	foreach ($httpResponseAr as $i => $value) {
		$tmpAr = explode("=", $value);
		if(sizeof($tmpAr) > 1) {
			$httpParsedResponseAr[$tmpAr[0]] = $tmpAr[1];
		}
	}

	if((0 == sizeof($httpParsedResponseAr)) || !array_key_exists('ACK', $httpParsedResponseAr)) {
		exit("Invalid HTTP Response for POST request($nvpreq) to $API_Endpoint.");
	}

	return $httpParsedResponseAr;
}

function validate_rule($field_name, $show_name, $rule){
    global $validate_msg;
    $rule_arr = explode("|", $rule);
    for($n=0;$n<count($rule_arr);$n++){
        switch ($rule_arr[$n]){
            case "required":
                if(trim($_REQUEST[$field_name])==""){
                    $validate_msg = $validate_msg ."Please enter $show_name<br/>";
                }
                break;
            case "required_num":
                if(frSafeNum($field_name)==0){
                    $validate_msg = $validate_msg ."Please enter valid number for $show_name<br/>";
                }
                break;
            case "valid_num":
                if(trim($_REQUEST[$field_name])!="" && frSafeNum($field_name)==0){
                    $validate_msg = $validate_msg ."Please enter valid number for $show_name<br/>";
                }
                break;
            case "required_email":
                if(strpos($_REQUEST[$field_name],"@")===false || strpos($_REQUEST[$field_name],".")===false){
                    $validate_msg = $validate_msg ."Please enter valid email address<br/>";
                }
                break;
            case "valid_email":
                if(trim($_REQUEST[$field_name])!="" && (strpos($_REQUEST[$field_name],"@")===false || strpos($_REQUEST[$field_name],".")===false)){
                    $validate_msg = $validate_msg ."Please enter valid email address<br/>";
                }
                break;
        }
        if(strpos($rule_arr[$n],"matched~")!==false){
            $field_arr = explode("~",$rule_arr[$n]);
            $matched_field = $field_arr[1];
            if(trim($_REQUEST[$field_name])!=trim($_REQUEST[$matched_field])){
                $validate_msg = $validate_msg ."$show_name is not matched<br/>";
            }
        }
    }
}

function check_validation(){
    global $validate_msg;
    if($validate_msg!=""){
        $temp_validate_msg = $validate_msg;
        $validate_msg = "";
        return $temp_validate_msg;
    }
}

function fSafeChar($str)
{
	$str = trim($str);
	$str = str_replace("'","''",$str);
	return $str;
}
function fsSafeChar($str)
{
	return fSafeChar($_SESSION[$str]);
}
function fsSafeNum($str)
{
	return fSafeNum($_SESSION[$str]);
}
function fcSafeNum($str)
{
	return fSafeNum($_COOKIE[$str]);
}
function frSafeChar($str)
{
	return fSafeChar($_REQUEST[$str]);
}
function frSafeNum($str)
{
	return fSafeNum($_REQUEST[$str]);
}
function fSafeNum($str)
{
	$str =trim($str);
    $str = str_replace(" ","",$str);
    if (is_numeric($str))
    {
    	return doubleval($str);
    }
    else
    {
    	return 0 ;
    }
}
function validateForm($sArrayObj)
{
    $errmsg = "";
    $errMisingMsg = "";
    $errInvalidMsg = "";
    foreach($sArrayObj as $key=>$value)
    {
    	$key = str_replace("_"," ",$key);
        if(strpos($key,"txt req" )!== false && trim($value)==""){
            $errMisingMsg=$errMisingMsg."&nbsp;&nbsp;&nbsp;&nbsp;".ucwords(strtolower(str_replace("txt req","",$key)))."<br/>";
        }elseif (strpos($key,"num req" )!== false && fSafeNum($value)==0){
            $errMisingMsg=$errMisingMsg."&nbsp;&nbsp;&nbsp;&nbsp;". ucwords(strtolower(str_replace("num req","",$key)))."<br/>" ;
        }elseif (strpos($key,"email req" )!== false && (trim($value)=="")){
            $errMisingMsg=$errMisingMsg."&nbsp;&nbsp;&nbsp;&nbsp;". ucwords(strtolower(str_replace("email req","",$key)))."<br/>";
        }elseif (strpos($key,"email req" )!== false && (strpos($value,"@")===false || strpos($value,".")===false)){
            $errInvalidMsg=$errInvalidMsg."&nbsp;&nbsp;&nbsp;&nbsp;". ucwords(strtolower(str_replace("email req","",$key)))."<br/>";
        }elseif (strpos($key,"txt email" )!== false && (trim($value)!="") && (strpos($value,"@")===false || strpos($value,".")===false)){
            $errInvalidMsg=$errInvalidMsg."&nbsp;&nbsp;&nbsp;&nbsp;". ucwords(strtolower(str_replace("txt","",$key)))."<br/>";
        }

    }
	if($errMisingMsg!=""){
		$errmsg = $errmsg. "Following information is missing:<br>".$errMisingMsg;
	}
	if($errInvalidMsg!=""){
		$errmsg = $errmsg. "Following information is invalid:<br>".$errInvalidMsg;
	}
    return str_replace("_"," ",$errmsg);
}
function Trim1($sString, $sTrimPart)
{
	$sString = trim($sString);
	if($sString=="")
	{
		return $sString;
	}
	else
	{
		while(substr($sString,0,strlen($sTrimPart))==$sTrimPart)
		{
			$sString = substr($sString,strlen($sTrimPart));
		}
		while(substr($sString,strlen($sString)- strlen($sTrimPart),strlen($sTrimPart))==$sTrimPart)
		{
			$sString = substr($sString,0,strlen($sString)- strlen($sTrimPart));
		}
		return $sString;
	}
}
function sendMail($sTo,$sFrom,$sSubject,$sMsg,$sBcc="",$sCC="")
{
	global $gsSiteURL, $gsEnvironment;
	$headers = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
	$headers .= 'From: '.$sFrom."\r\n";
	$headers .= 'Cc: '.$sCC."\r\n";
	$headers .= 'Bcc: '.$sBcc."\r\n";
    $year = date("Y");
    $html_msg= <<<END
        <html>
        <head>
            <link href="$gsSiteURL/css/screen.css" rel="stylesheet"/>
        </head>
        <body style="background:#fff">
            <table width='640' cellpadding='3' cellspacing='0' align="center" class="normal" style="border:1px solid #ccc;background:#fff">
                <tr>
                    <td><img width="100" src="$gsSiteURL/images/logo.png">
                <tr>
                <tr>
                    <td>$sMsg</td>
                </tr>
            </table>
            <br/>
            <table width='640' cellpadding='3' cellspacing='0' align="center" class="normal">
                <tr>
                    <td align="center" bgcolor="#cccccc" style="text-align:center;">
                        This is an automatically generated mail. Please, don't reply.
                        <br/>If you encounter any error in this mail please feel free to mail info(at)madaancommunication.
                        <br/>We will be happy to assist you.
                        <br/>Copyright &copy; $year info@madaancommunication. All Rights Reserved.
                        <br/>Designed and developed by MYL&trade;
                    </td>
                </tr>
            <table>
        </body>
        </html>
END;
       // if($gsEnvironment=="LIVE"){
    	   @mail($sTo,$sSubject,$html_msg,$headers);
       // }else{
      //  }
}

function pagination($row_count, $limit){
    $num_rows = $row_count;
    $pages = $num_rows/$limit;
    if((int)$pages!=$pages){
        $pages = (int)$pages+1;
    }
    if(frSafeNum("page")>0){
        $current_page_num = frSafeNum("page");
    }else{
        $current_page_num = 1;
    }
    $st_page = (($current_page_num-1) * $limit) + 1;

    $en_page = ($st_page+$limit)-1;
    if($en_page>$num_rows)
        $en_page = $num_rows;

    //echo "<!--total: $row_count \n limit: $limit \n start: $st_page \n end page: $en_page-->";
    $current_page = $_SERVER['SCRIPT_NAME'];
    $query_string = $_SERVER['QUERY_STRING'];
    $query_string = str_replace("?page=".frSafeNum("page"),"",$query_string);
    $query_string = str_replace("&page=".frSafeNum("page"),"",$query_string);
    $query_string = str_replace("page=".frSafeNum("page"),"",$query_string);
    if($query_string!=""){
        $query_string = $query_string."&";
    }

    $loop_st = $st_page - 2;
    if($loop_st<1){
        $loop_st = 1;
    }

    $loop_en = $loop_st + 4;

    if($loop_en>$pages){
        $loop_en = $pages;
        $loop_st = $loop_en-4;
        if($loop_st<1){
            $loop_st = 1;
        }
    }
    global $st_limit;
    $st_limit = $st_page-1;

    $loop_st = $current_page_num;
    if($loop_st<1){
        $loop_st = 1;
    }
    $loop_en = $loop_st + 4;
    if($loop_en>$pages){
        $loop_en = $pages;
    }
    ?>
    <div style="clear:both"></div>
    <table align="center" style="width:98%" cellpadding="5" cellspacing="0">
        <tr>
            <td width="60%">
                <?php
                if($current_page_num  > 1)
                {
                    ?>
                        <a href="<?php echo $current_page ?>?<?php echo $query_string ?>page=1"><span class="page_num">First</span></a>
                        <a href="<?php echo $current_page?>?<?php echo $query_string?>page=<?php echo $current_page_num - 1?>"><span class="page_num">Previous</span></a>
                    <?php
                }
                ?>
                <?php for($n=$loop_st;$n<=$loop_en;$n++){
                    if($n==$current_page_num){
                        $page_class="selected_page";
                    }else{
                        $page_class="page_num";
                    }
                    ?>
                    <a href="<?php echo $current_page?>?<?php echo $query_string?>page=<?php echo $n?>"><span class="<?php echo $page_class?>"><?php echo $n?></span></a>
                <?php }?>
                <?php
                if($current_page_num  < $en_page)
                {
                    ?>
                        <a href="<?php echo $current_page?>?<?php echo $query_string?>page=<?php echo $current_page_num + 1?>"><span class="page_num">Next</span></a>
                        <a href="<?php echo $current_page?>?<?php echo $query_string?>page=<?php echo $pages ?>"><span class="page_num">Last</span></a>
                    <?php
                }
                ?>
            </td>
            <td style="text-align: right;">Showing <?php echo $st_page ?> to <?php echo $en_page ?> of <?php echo $num_rows ?></td>
        </tr>
    </table>
    <div style="clear:both"></div>
    <?php
}

function DBQuery($strSql)
{
	global $DBConn;
	$resultSet = DBQueryResultset($strSql);
	if(strpos(strtolower($strSql),"select")!==false)
	{
		$n = 0;
		while($countResult = mysql_fetch_array($resultSet))
		{
			$sReturnVal[$n] = $countResult;
			$n++;
		}
	}
	else
	{
		$sReturnVal = $resultSet;
	}
	return $sReturnVal;
}
function DBUpdateInit($sTableName,$sWhereClause)
{
    global $sSqlUpdateColsNameValue, $sSqlUpdateInit, $sSqlUpdateWhereClause;
	$sSqlUpdateColsNameValue="";
	$sSqlUpdateInit = "UPDATE $sTableName SET";
	$sSqlUpdateWhereClause = "WHERE $sWhereClause";
}
function DBUpdateCol($sColName, $sColValue)
{
    global $sSqlUpdateColsNameValue, $sSqlUpdateInit, $sSqlUpdateWhereClause;
	if(trim($sSqlUpdateInit)=="" || trim($sSqlUpdateWhereClause)==""){
		echo("Table name or where clause is not intialized before updating columns.");
	}else{
		$sSqlUpdateColsNameValue=$sSqlUpdateColsNameValue.",".$sColName." = ".$sColValue;
	}
}
function DBUpdateEnd()
{
    global $sSqlUpdateColsNameValue, $sSqlUpdateInit, $sSqlUpdateWhereClause;
	if(trim($sSqlUpdateInit)=="" || trim($sSqlUpdateWhereClause)==""){
		echo("Table name or where clause is not intialized before updating columns.");
	}elseif(trim($sSqlUpdateColsNameValue)==""){
		echo("Columns name is not intialized before updating columns.");
	}else{
		$sSqlUpdateColsNameValue = Trim1($sSqlUpdateColsNameValue, ",");
		$sStrSql = $sSqlUpdateInit." ".$sSqlUpdateColsNameValue." ".$sSqlUpdateWhereClause;
		$sSqlUpdateInit = "";
		$sSqlUpdateColsNameValue = "";
		$sSqlUpdateWhereClause = "";
		return DBQuery($sStrSql);
	}
}

function DBInsertInit($sTableName)
{
    global $sSqlInsertColsName, $sSqlInsertColsValue, $sSqlInsertInit;
	$sSqlInsertColsName="";
	$sSqlInsertColsValue="";
	$sSqlInsertInit = "INSERT INTO $sTableName";

}
function DBInsertCol($sColName, $sColValue)
{
    global $sSqlInsertColsName, $sSqlInsertColsValue, $sSqlInsertInit;
	if(trim($sSqlInsertInit)==""){
		echo("Table name is not intialized befire inserting columns.");
	}else{
		$sSqlInsertColsName=$sSqlInsertColsName.",".$sColName;
		$sSqlInsertColsValue=$sSqlInsertColsValue.",".$sColValue;
	}
}
function DBInsertEnd()
{
    global $sSqlInsertColsName, $sSqlInsertColsValue, $sSqlInsertInit;
	if(trim($sSqlInsertInit)==""){
		echo("Table name is not intialized befire inserting columns.");
	}elseif(trim($sSqlInsertColsName)=="" || trim($sSqlInsertColsValue)==""){
		echo("Columns name or values is not intialized befire inserting columns.");
	}else{
		$sSqlInsertColsName = Trim1($sSqlInsertColsName, ",");
		$sSqlInsertColsValue = Trim1($sSqlInsertColsValue, ",");
		$sStrSql = $sSqlInsertInit."(".$sSqlInsertColsName.") VALUES(".$sSqlInsertColsValue.")";
		$sSqlInsertInit = "";
		$sSqlInsertColsName = "";
		$sSqlInsertColsValue = "";
		return DBQueryResultset($sStrSql);
	}
}
function DBQueryResultSet($strSql)
{
	global $db;
	for($n=6;$n>1;$n--)
	{
		$_SESSION["Recent_SQL_".$n] = $_SESSION["Recent_SQL_".(int)($n-1)];
	}
	$_SESSION["Recent_SQL_1"] = $strSql;
	try
	{
		$resultSet = mysql_query($strSql)
		or die("Error: ". mysql_error()."<br>Query: ".$strSql);
		if(strpos(strtolower($strSql),"select")!==false)
		{
			$sReturnVal = $resultSet;
		}
		elseif(strpos(strtolower($strSql),"insert")!==false)
		{
			$sReturnVal = mysql_insert_id();
		}
		else
		{
			$sReturnVal = mysql_affected_rows();
		}
	}
	catch (Exception $ex)
	{
		echo("Unable to Run Query (".$ex->getCode()."): ".$ex->getMessage()."\n\n QUERY: ".$strSql);
	}
	return $sReturnVal;
}

function CreateRandomString($nLength) {
    $chars = "abcdefghijkmnopqrstuvwxyz0123456789";
    srand((double)microtime()*1000000);
    $i = 0;
    $pass = '' ;

    while ($i <= $nLength) {
        $num = rand() % 33;
        $tmp = substr($chars, $num, 1);
        $pass = $pass . $tmp;
        $i++;
    }
    return $pass;
}
function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}

function getTempOrderID(){
	if(fsSafeNum("tempOrderID")>0){
		return fsSafeNum("tempOrderID");
	}elseif(fcSafeNum("tempOrderID")>0){
		return fcSafeNum("tempOrderID");
	}else{
		$nTempOrderID = microtime_float();
		$_SESSION["tempOrderID"]= $nTempOrderID;
        setcookie("tempOrderID", $nTempOrderID, time()+(3600*24*7));
		return $nTempOrderID;
	}
}

function genRandomString($length) {
    $characters = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $string = "";

    for ($p = 0; $p < $length; $p++) {
        $string .= $characters[mt_rand(0, strlen($characters)-1)];
    }

    return $string;
}

function send_invoice(){

    global $from_address, $admin_email, $gsSiteURL, $gb_is_renewal, $enc_key;
    $order_id = fsSafeNum("order_id");

    $strSql = "select oi.expiry_date, oi.is_renewal, o.ship_name, o.ship_address, o.ship_city, o.ship_state, o.ship_country, o.ship_zipcode, o.shipping, p.thumb, oi.price, oi.gift_discount, oi.quantity, p.product_name from orders o
                    join order_items oi on o.order_id = oi.order_id
                    join products p on p.product_id = oi.product_id
                    where o.order_id = ".fsSafeNum("order_id");

    if($gb_is_renewal){

        $strSql = $strSql." and oi.is_renewal=1 ";
    }

    $rs_cart = mysql_query($strSql)
    or die(mysql_error());

    if(mysql_num_rows($rs_cart)>0){

        $count_cart2=mysql_fetch_array($rs_cart);
        $shipping_name = $count_cart2['ship_name'];
        $shipping_address = $count_cart2['ship_address'];
        $shipping_city = $count_cart2['ship_city'];
        $shipping_state = $count_cart2['ship_state'];
        $shipping_country = $count_cart2['ship_country'];
        $shipping_zipcode = $count_cart2['ship_zipcode'];
        if($gb_is_renewal){
            $top_line = "<b>Your renewal order will be expired on ".$count_cart2['expiry_date'].". We will charge payment from your credit card on ".$count_cart2['expiry_date']." of order no. is $order_id. Order details are following: </b>";
        }else{
            $top_line = "Your order has been successfully placed. We have received your payment. Your order no. is $order_id";
            if($count_cart2['is_renewal']==1){
                $top_line = "Your auto renewal order has been successfully placed. We have received your payment. Your order no. is $order_id";
            }
        }

        $foo = <<<END
        <table width="640" cellpadding="3" cellspacing="1" align="center" class="normal">
            <tr>
                <td colspan="20">
                    $top_line<br>
                </td>
            </tr>
            <tr>
                <td colspan="20"><div class="sub_heading">Product Information</div></td>
            </tr>
            <tr class="cart_header_row">
                <td width="250" colspan="2">Product Overview</td>
                <td width="50">Quantity</td>
                <td width="70" style="padding-right:10px;text-align: right!important;">Unit Price</td>
                <td width="50" style="padding-right:10px;text-align: right!important;">Price</td>
            </tr>
END;
            $total_discount = 0;
            $total_unit_price = 0;
            $total_price = 0;
            $nIndex = 0;
            mysql_data_seek($rs_cart,0);

            while($count_cart=mysql_fetch_array($rs_cart)){
                $nIndex++;
                $total_unit_price = $total_unit_price + $count_cart['price'];
                $total_price = $total_price + ($count_cart['price']*$count_cart['quantity']);
                if($sClass=="rowone"){
                    $sClass="rowtwo";
                }else{
                    $sClass="rowone";
                }
                $nShipping = $count_cart['shipping'];
                $nShipping = 0;
                $thumb = $count_cart['thumb'];
                $product_name = $count_cart['product_name'];
                $size = "";
                $colors = "";
                if($count_cart['size']!=""){
                    $size = "<br />Size: ".$count_cart['size'];
                }
                if($count_cart['icon']!=""){
                    $colors = '<br />Color: <span style="width:20px;height:20px;border:1px solid #000;background:#'.$count_cart['icon'].'">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>';
                }
                $quantity = $count_cart['quantity'];
                $price = $count_cart['price'];
                $product_price = $quantity*$price;
                $foo = $foo.<<<END
                <tr class="$sClass">
                    <td width="50"><img height="50" src="$gsSiteURL/upload/products/thumb/$thumb"/></td>
                    <td>$product_name.$size.$colors</td></td>
                    <td>$quantity</td>
                    <td style="padding-right:10px;text-align: right;">Rs. $price</td>
                    <td style="padding-right:10px;text-align: right;">Rs. $product_price</td>
                </tr>
END;
            }
            $foo = $foo.<<<END
            <tr class="rowone">
                <td style="text-align: right;" colspan="4"><b>Subtotal: </b></td>
                <td style="text-align: right;padding-right:10px;" ><b>Rs. $total_price<b></td>
            </tr>
            <tr class="rowtwo">
                <td style="text-align: right;" colspan="4"><b>Discount: </b></td>
                <td style="text-align: right;padding-right:10px;" ><b>Rs. $total_discount<b></td>
            </tr>
END;
            if(fSafeNum($nShipping)>0 && false){
                $foo = $foo.<<<END
                <tr class="rowone">
                    <td style="text-align: right;" colspan="4"><b>Shipping: </b></td>
                    <td style="text-align: right;padding-right:10px;" ><b>Rs. $nShipping<b></td>
                </tr>
END;
            }
            $gross_amount = $total_price+$nShipping-$total_discount;
            $foo = $foo.<<<END
            <tr  class="rowone">
                <td style="text-align: right;" colspan="4"><b>Total: </b></td>
                <td style="text-align: right;padding-right:10px;" ><b>Rs. $gross_amount<b></td>
            </tr>
        </table>
END;
    }

    $rs_customer = mysql_query("select * from users where user_id = ".fsSafeNum("customer_id"));
    if(mysql_num_rows($rs_customer)>0){
        $count_customer = mysql_fetch_array($rs_customer);
        $username = $count_customer['username'];
        $password = $count_customer['password'];
        $confirm_password = $count_customer['password'];
        $name = $count_customer['name'];
        $company_name = $count_customer['company_name'];
        $address = $count_customer['address'];
        $city = $count_customer['city'];
        $state = $count_customer['state'];
        $email = $count_customer['email'];
        $country = $count_customer['country'];
        $zipcode = $count_customer['zipcode'];
        $fax = $count_customer['fax'];
        $mobile = $count_customer['mobile'];
        $phone = $count_customer['phone'];
    }
    mysql_free_result($rs_customer);

    $foo = $foo.<<<END
    <br/><table width="640" cellpadding="3" cellspacing="1" align="center" class="normal">
        <tr valign="top">
            <td width="50%">
                <table width="100%" cellpadding="3" cellspacing="1" align="center" class="normal">
                    <tr>
                        <td colspan="20"><div class="sub_heading">Billing Information</div></td>
                    </tr>
                    <tr class="rowone">
                        <td>Name: </td>
                        <td>$name</td>
                    </tr>
                    <tr class="rowtwo">
                        <td>Company Name: </td>
                        <td>$company_name</td>
                    </tr>
                    <tr class="rowone" valign="top">
                        <td>Address: </td>
                        <td>$address</td>
                    </tr>
                    <tr class="rowone">
                        <td>City: </td>
                        <td>$city</td>
                    </tr>
                    <tr class="rowtwo">
                        <td>State: </td>
                        <td>$state</td>
                    </tr>
                    <tr class="rowone">
                        <td>Country: </td>
                        <td>$country</td>
                    </tr>
                    <tr class="rowtwo">
                        <td>Zipcode: </td>
                        <td>$zipcode</td>
                    </tr>
                    <tr class="rowone">
                        <td>Email: </td>
                        <td>$email</td>
                    </tr>
                    <tr class="rowtwo">
                        <td>Phone: </td>
                        <td>$phone</td>
                    </tr>
                    <tr class="rowone">
                        <td>Mobile: </td>
                        <td>$mobile</td>
                    </tr>
                    <tr class="rowtwo">
                        <td>Fax: </td>
                        <td>$fax</td>
                    </tr>
                </table>
            </td>
            <td width="50%">
                <table width="100%" cellpadding="3" cellspacing="1" align="center" class="normal">
                    <tr>
                        <td colspan="20"><div class="sub_heading">Shipping Information</div></td>
                    </tr>
                    <tr class="rowone">
                        <td>Name: </td>
                        <td>$shipping_name</td>
                    </tr>
                    <tr class="rowone" valign="top">
                        <td>Address: </td>
                        <td>$shipping_address</td>
                    </tr>
                    <tr class="rowone">
                        <td>City: </td>
                        <td>$shipping_city</td>
                    </tr>
                    <tr class="rowtwo">
                        <td>State: </td>
                        <td>$shipping_state</td>
                    </tr>
                    <tr class="rowone">
                        <td>Country: </td>
                        <td>$shipping_country</td>
                    </tr>
                    <tr class="rowone">
                        <td>Zipcode: </td>
                        <td>$shipping_zipcode</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
END;
    if($gb_is_renewal){
        $sig = md5($order_id.$enc_key);
        $foo = $foo.<<<END
        <br/><table width="640" cellpadding="3" cellspacing="1" align="center" class="normal">
            <tr valign="top">
                <td width="100%">
                    <b>If you don't want to continue your subscription, <a href="$gsSiteURL/cancel_subscription.php?oid=$order_id&sig=$sig">click here</a> to cancel your subscription for this order.</b>
                    <br/>If the above link is not clickable then copy the following url and paste in your browser address bar.
                    <br/>$gsSiteURL/cancel_subscription.php?oid=$order_id&sig=$sig
                </td>
            </tr>
        </table>
END;
}
    if($gb_is_renewal){
        sendMail($email,$admin_email,"Renewal Order Notification of Order #$order_id",$foo);
    }else{
        sendMail($email,$admin_email,"Order Confirmation of Order #$order_id",$foo,$admin_email);
        send_gift_certificate();
    }
}

function send_gift_certificate(){
    global $gsSiteURL;
    $order_id = fsSafeNum("order_id");
    $rs_cart = mysql_query("select c.name, c.email, o.ship_name, oi.price, oi.quantity, oi.gift_name, oi.gift_email, oi.gift_message from orders o
                    join order_items oi on o.order_id = oi.order_id
                    join users c on o.user_id = c.user_id
                    where oi.gift_certificate=1 and o.order_id = ".fsSafeNum("order_id"));
    while($count_cart=mysql_fetch_array($rs_cart)){
        $gift_code = "GC-".genRandomString(8);
        DBInsertInit("gift_certificates");
        DBInsertCol("order_id", $order_id);
        DBInsertCol("to_name", "'".$count_cart['gift_name']."'");
        DBInsertCol("to_email", "'".$count_cart['gift_email']."'");
        DBInsertCol("message", "'".$count_cart['gift_message']."'");
        DBInsertCol("coupon_code", "'".$gift_code."'");
        DBInsertCol("sent_on", "'".date("Y-m-d")."'");
        DBInsertCol("amount", $count_cart['price']*$count_cart['quantity']);
        DBInsertEnd();
        $nPrice = (float)($count_cart['price'] * $count_cart['quantity']);
        $nPrice = round($nPrice, 2);
        $sMsg = "Dear ".$count_cart['gift_name'].", ";
        $sMsg.="<br/><br>Your friend (".$count_cart['name'].") has sent you a gift from $gsSiteURL of worth \$$nPrice";
        $sMsg.="<br/>You can purchase any product and get \$$nPrice discount by entering following coupon code";
        $sMsg.="<br/><b>Your Coupon Code is: $gift_code</b>";
        $sMsg.="<br/>You can purchase from here:  $gsSiteURL";        
        sendMail($count_cart['gift_email'],$count_cart['email'],"Gift Sent by Friend",$sMsg);
    }

}
function clear_cart_session(){
    global $nTempOrderID;
    mysql_query("delete from cart where temp_order_id = '".$nTempOrderID."'");
    $_SESSION['order_id'] = 0;
    setcookie("order_id",0);
    $_SESSION['tempOrderID'] = "";
    setcookie("tempOrderID","");
}


function DateAdd($interval, $number, $date) {

    $date_time_array = getdate($date);


    $hours = $date_time_array['hours'];
    $minutes = $date_time_array['minutes'];
    $seconds = $date_time_array['seconds'];
    $month = $date_time_array['mon'];
    $day = $date_time_array['mday'];
    $year = $date_time_array['year'];


    switch ($interval) {

        case 'yyyy':
            $year+=$number;
            break;
        case 'q':
            $year+=($number*3);
            break;
        case 'm':
            $month+=$number;
            break;
        case 'y':
        case 'd':
        case 'w':
            $day+=$number;
            break;
        case 'ww':
            $day+=($number*7);
            break;
        case 'h':
            $hours+=$number;
            break;
        case 'n':
            $minutes+=$number;
            break;
        case 's':
            $seconds+=$number;
            break;
    }
    $timestamp= date("c", mktime($hours,$minutes,$seconds,$month,$day,$year));
    return $timestamp;
}

function get_price_product($price,$promo_price,$need_br = False)
{
    $br  = "<br>";
    if($need_br)
    {
        $br="&nbsp;";
        
    }
    $product_price = "";
    if($promo_price > 0 && $promo_price < $price)
    {
        $product_price = "  <strike>".convert_currency($price)." </strike> <br>".convert_currency($promo_price);
    }else{
        $product_price = convert_currency($price);
    }
    
    return $product_price;
}

function get_matter($id)
{
    $query = mysql_query("select description from matters where matter_id='$id'") or die("error".mysql_error());
    $row = mysql_fetch_array($query);
    return $row['description'];
}

function convert_currency($amount, $cur_code="", $currency_symbol=true){
    
    
    if(!isset($_SESSION['CURRENCY_CODE']))
    {
        $cur_code = "USD";
    }else{
        $cur_code = $_SESSION['CURRENCY_CODE'];
    }
	if($cur_code=="" || $cur_code=="RS"){
		if($currency_symbol)
			return $cur_code." ".number_format($amount,2,".","");
		else
			return number_format($amount,2,".","");
	}else{	
	    
	    $rsConRate = mysql_query("SELECT value FROM currency WHERE currency_code = '$cur_code'");
        if(mysql_num_rows($rsConRate) > 0){
            $row_conRate = mysql_fetch_array($rsConRate); 
            $convRate = $row_conRate['value'];
        }else{
            $convRate = "1";
            $cur_code = "RS";
        }   
		if($currency_symbol)
        {
            
            return $cur_code." ".number_format($amount * $convRate,2,".","");
        }			
		else
        {
            return number_format($amount * $convRate,2,".","");
        }
			
	}
}

function get_main_attr($mid)
{
   
    
    $query = mysql_query("select attribute from attributes where attribute_id='$mid'")or die("err".mysql_error());
    if(mysql_num_rows($query) > 0)
    {
        $row = mysql_fetch_array($query);
        $id = $row['attribute']; 
    }
    return $id;
}
function get_sub_attr($sid)
{
   
    
    $query = mysql_query("select attribute_detail from attribute_detail where attribute_detail_id='$sid'")or die("err".mysql_error());
    if(mysql_num_rows($query) > 0)
    {
        $row = mysql_fetch_array($query);
        $id = $row['attribute_detail']; 
    }
    return $id;
}

function cur_page_name()
{
    $pageName = basename($_SERVER['PHP_SELF']);
    return $pageName;
}

function show_attributes($attributes_cart)
{
    $attribute_are = "";
    if(is_array($attributes_cart))
    {
       for($i=1;$i<count($attributes_cart);$i++)
       {
          $attribute_are .=  ",".get_sub_attr($attributes_cart[$i]);
       }
       $attribute_are = "&nbsp;<b>(".substr($attribute_are,1,strlen($attribute_are)).")</b>";
    }
    echo $attribute_are;
}

function select_makes_value($id)
{
    
    $query = mysql_query("select * from makes where make_id='$id'") or die("err".mysql_error());
    $row   = mysql_fetch_array($query);
    return $row['make'];
}

function get_mobile_categories($id)
{
    $query = mysql_query("select * from mobile_categories where id='$id'") or die("err".mysql_error());
    $row = mysql_fetch_array($query);
    return $row['mob_category'];
}
function get_mobile_operator($id)
{
    $query = mysql_query("select * from operators where id='$id'") or die("err".mysql_error());
    $row = mysql_fetch_array($query);
    return $row['operator'];
}

function user_detail()
{
    $query = mysql_query("select * from users where user_id = '".$_SESSION['USER_ID']."'") or die("err".mysql_error());
    $row = mysql_fetch_array($query);
    return $row;
    
}
function pages_text($id)
{
    $query = mysql_query("select description from custom_text where id = '$id'") or die("err".mysql_error());
    $row = mysql_fetch_array($query);
    return $row['description'];
}

function getExtension($str)
{
	 $i = strrpos($str,".");
	 if (!$i) { return ""; }
	 $l = strlen($str) - $i;
	 $ext = substr($str,$i+1,$l);
	 return $ext;
}
function imagetranstowhite($trans) {
    // Create a new true color image with the same size
	
	
    $w = imagesx($trans);
    $h = imagesy($trans);
    $white = imagecreatetruecolor($w, $h);
 
    // Fill the new image with white background
    $bg = imagecolorallocate($white, 255, 255, 255);
    imagefill($white, 0, 0, $bg);
 
    // Copy original transparent image onto the new image
    imagecopy($white, $trans, 0, 0, 0, 0, $w, $h);
    return $white;
	
	
}
function get_thumb($image,$thumb_width,$path,$optional_filename="")
{  
   
    if ($image) 
    {
        
        //get the original name of the file from the clients machine
        $filename = stripslashes($_FILES['image']['name']);
	
        //get the extension of the file in a lower case format
        $extension = getExtension($filename);
        $extension = strtolower($extension);
        if($extension=="jpg" || $extension=="jpeg" || $extension=="png" || $extension=="gif" )
        {
            if($extension=="jpg" || $extension=="jpeg" )
            {
            	$uploadedfile = $_FILES['image']['tmp_name'];
            	$src = imagecreatefromjpeg($uploadedfile);
            }
            if($extension=="png")
            {
            	$uploadedfile = $_FILES['image']['tmp_name'];
            	$src = imagecreatefrompng($uploadedfile);
				
            }
            if($extension=="gif")
            {
            	$uploadedfile = $_FILES['image']['tmp_name'];
            	$src = imagecreatefromgif($uploadedfile);
            }
             
            list($width,$height)=getimagesize($uploadedfile);
    
            $newwidth=$thumb_width;//pass by programmer
            $newheight=($height/$width)*$newwidth;
            $tmp=imagecreatetruecolor($newwidth,$newheight);
            
                         
            imagecopyresampled($tmp,$src,0,0,0,0,$newwidth,$newheight,$width,$height);
            
           
            $filename = uniqid(). $_FILES['image']['name'];
            $filename1 ="";
            if($optional_filename == "")
            {          
                $filename1 = $filename;
            }else{
              
                $filename1 = $optional_filename;
            }
            
			if($extension=="png")
			{
				$uploadedfile = $_FILES['image']['tmp_name'];
            	$src = imagecreatefrompng($uploadedfile);
				$jpg = imagetranstowhite($src);
				imagejpeg($jpg,$path.$filename1,100); 
			}else{
				imagejpeg($tmp,$path.$filename1,100); 
			}
			
            if($optional_filename == "")
            {
               return $filename; 
            }else{
               return $optional_filename;
            }    
                                 
         }
     }
        
}
function get_multiple_images($image,$thumb_width,$path,$optional_filename="",$i)
{  
   
    if ($image) 
    {
        
        //get the original name of the file from the clients machine
        $filename = stripslashes($_FILES['image']['name'][$i]);
        //get the extension of the file in a lower case format
        $extension = getExtension($filename);
        $extension = strtolower($extension);
        if($extension=="jpg" || $extension=="jpeg" || $extension=="png" || $extension=="gif" )
        {
            if($extension=="jpg" || $extension=="jpeg" )
            {
            	$uploadedfile = $_FILES['image']['tmp_name'][$i];
            	$src = imagecreatefromjpeg($uploadedfile);
            }
            if($extension=="png")
            {
            	$uploadedfile = $_FILES['image']['tmp_name'][$i];
            	$src = imagecreatefrompng($uploadedfile);
            }
            if($extension=="gif")
            {
            	$uploadedfile = $_FILES['image']['tmp_name'][$i];
            	$src = imagecreatefromgif($uploadedfile);
            }
             
            list($width,$height)=getimagesize($uploadedfile);
    
            $newwidth=$thumb_width;//pass by programmer
            $newheight=($height/$width)*$newwidth;
            $tmp=imagecreatetruecolor($newwidth,$newheight);
            
                         
            imagecopyresampled($tmp,$src,0,0,0,0,$newwidth,$newheight,$width,$height);
            
           
            $filename = uniqid(). $_FILES['image']['name'][$i];
            $filename1 ="";
            if($optional_filename == "")
            {          
                $filename1 = $filename;
            }else{
              
                $filename1 = $optional_filename;
            }
           
			
			if($extension=="png")
			{
				$uploadedfile = $_FILES['image']['tmp_name'][$i];
            	$src = imagecreatefrompng($uploadedfile);
				$jpg = imagetranstowhite($src);
				imagejpeg($jpg,$path.$filename1,100); 
			}else{
				imagejpeg($tmp,$path.$filename1,100); 
			}
			
            if($optional_filename == "")
            {
                return $filename; 
            }else{
               return $optional_filename;
            }    
                                 
         }
     }
        
}




function getTopMargin($maxHeight,$image)
{
	$margin_top = "";    
	if(strpos($image,"uploads/") !== false)
	{        
		if(file_exists($image))
		{  
		    list($w,$h) = getimagesize($image);
			if($h<$maxHeight)
			  $margin_top = (int)($maxHeight-$h)/2;    
		}
	}
	
	return $margin_top;
}

function get_ad_image($id)
{
    $query = mysql_query("select image from ads_images where ad_id = '$id' order by rand() limit 1") or die("err".mysql_error());
    $row = mysql_fetch_array($query);
    return $row['image'];
    
}

?>
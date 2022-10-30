<?php global $url_root; ?>

<link rel="icon" href="<?=$url_root?>/images/favicon.png" type="image/png" /> 
<script
  src="https://code.jquery.com/jquery-2.2.4.min.js"
  integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="
  crossorigin="anonymous"></script>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<script>
	function textCounter(field,field2,maxlimit)
		{
		var countfield = document.getElementById(field2);
		if ( field.value.length > maxlimit ) {
			field.value = field.value.substring( 0, maxlimit );
			return false;
		} else {
			countfield.innerHTML = maxlimit - field.value.length + " characters left";
		}
	}
	
	function delete_conform(){
	if(confirm("Are you sure you want to delete this record?")){
	}else{
		return false;
	}
}
</script>
<style>
.pagination li.active a{
	background-color:#D4E6F1 !important;
}
.pagination .active a{
	color: #eb9316 !important;
	border-color : #D4E6F1 !important;
}
</style>

<?php
//error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE & ~E_DEPRECATED);
require($_SERVER['DOCUMENT_ROOT'] . "/lib/inc_connection.php");
header("Content-Type: text/html; charset=ISO-8859-1");
//Globals for logging mechanism

define("LOGFILE","../site_log.txt", TRUE);
//log levels
define("ERR","Fatal Error:", TRUE);
define("INFO","Info:", TRUE);

function func_display_msg($msg){
	echo "<center><font class='msg_box' face=verdana size=2 color='red'>$msg</font></center>";
}

//Function to write all the site wide logs to a text file "site_log.txt" in root folder
function write_log($error_code,$error_desc)
{
	$err_string='';
	$err_string.=date("y-m-d h:i:s");
	$err_string.="	".$error_code;
	$err_string.="	".$error_desc.PHP_EOL;
	file_put_contents(LOGFILE,$err_string,FILE_APPEND);
}

function func_option($txt,$val,$fld){
	$sel="";
	if($val==$fld)$sel="selected";	

	echo "<option value='".$val."' $sel>$txt</option>";
}

function func_insert_qry($table,$fld_arr){
	$qry = "insert into $table (";
	
	foreach($fld_arr as $fld => $val){
		$qry .= ",$fld";   
	}
	$qry .= ") values (";

	$m = array('jan'=>1, 'feb'=>2, 'mar'=>3, 'apr'=>4, 'may'=>5, 'jun'=>6, 'jul'=>7, 'aug'=>8, 'sep'=>9, 'oct'=>10, 'nov'=>11, 'dec'=>12);

	foreach($fld_arr as $fld => $val){
		if(strpos($fld,"_date")){
			$date_arr = explode("-", $val);
			$date_arr[1] = $m[strtolower($date_arr[1])];
			$val = "$date_arr[2]-$date_arr[1]-$date_arr[0]";
		}
		if($val."x"=="x"){
			$qry .= ",\N";
		}else{
			$val = addslashes($val);
			$qry .= ",'$val'";
		}
	}
	$qry = str_replace("(,","(",$qry);
	$qry .= ")";

	return $qry;
}


function func_update_qry($table,$fld_arr,$where){
	$qry = "update $table set |";

	$m = array('jan'=>1, 'feb'=>2, 'mar'=>3, 'apr'=>4, 'may'=>5, 'jun'=>6, 'jul'=>7, 'aug'=>8, 'sep'=>9, 'oct'=>10, 'nov'=>11, 'dec'=>12);

	foreach($fld_arr as $fld => $val){
		if($val."x"=="x"){
			$qry .= ", $fld=\N";
		}else{
			$val = addslashes($val);
			$qry .= ", $fld='$val'";
		}
	}
	$qry = str_replace("|,","",$qry);
	$qry .= " $where";
	
	return $qry;
}

function func_read_qs($key){
	if(isset($_GET[$key])){
		return addslashes($_GET[$key])."";
	}elseif(isset($_POST[$key])){
		return addslashes($_POST[$key])."";
	}else{
        return "";
	}
}

function func_var(&$var){
	if(isset($var)){
		return $var;
	}else{
		return "";
	}
}

function formatNumber($var,$dec=0){
	return number_format($var,$dec,".",",");
}

function formatDate($v_date){
	if($v_date<>""){
		$date = date_create($v_date);
		return date_format($date,'d-M-Y');
	}else{
		return "";
	}
}

function send_mail($to,$subject,$body,$from="noreply@Company-Name.com",$cc="",$bcc=""){
	return xsend_mail($to, $subject, $body, $from);
}

function xsend_mail($to,$subject,$body, $from="noreply@Company-Name.com"){
    require_once($_SERVER['DOCUMENT_ROOT'] . '/lib/PHPMailer/PHPMailerAutoload.php');
    global $env_prod;
    $mail       = new PHPMailer();
    $mail->IsSMTP(true);          
	$mail->IsHTML(true);
	$mail->Subject    = $subject;
	$mail->MsgHTML($body);
	$address = $to;
	$mail->AddAddress($address);
	//$mail->SMTPDebug = 4; 	//enable error log to see the cause of email failure.
	if($env_prod){
		//$mail->Host       = "localhost"; // SMTP host
		$mail->SMTPAuth   = true;                  // enable SMTP authentication
		$mail->Host       = "smtpout.asia.secureserver.net"; // SMTP host
		$mail->Port       =  80;                    // set the SMTP port
		$mail->SetFrom($from, 'Company-Name');
		$mail->Username   = "welcome@Company-Name.com";  // SMTP  username
		$mail->Password   = "alifwel@123";  // SMTP password
		$mail->SMTPSecure = false;
		$mail->SMTPAutoTLS = false;
	}else{	//mail server configuration for test environment
		$mail->SMTPAuth   = true;                  // enable SMTP authentication
		$mail->SMTPSecure = 'ssl';
		$mail->Host       = "smtp.gmail.com"; // SMTP host
		$mail->Port       =  465;                    // set the SMTP port
		$mail->SetFrom("Company-Name.test@gmail.com", 'Company-Name');
		$mail->Username   = "Company-Name.test@gmail.com";  // SMTP  username
		$mail->Password   = "";  // SMTP password
	}
	if($mail->Send()){
		return true;
	} else{
		write_log(ERR, "Fail - " . $mail->ErrorInfo);
		return false;
	}	
}
function multi_attach_mail($to,$subject,$body,$from,$docs,$cc){
    require_once($_SERVER['DOCUMENT_ROOT'] . '/lib/PHPMailer/PHPMailerAutoload.php');
    global $env_prod;
    $mail       = new PHPMailer();
    $mail->IsSMTP(true);          
	$mail->IsHTML(true);
	$mail->Subject    = $subject;
	$mail->MsgHTML($body);
	$address = $to;
	$mail->AddAddress($address);
	$mail->AddCC($cc);
	if(count($docs) >= 1){			//images attached with mail
			for($i=0;$i<=count($docs);$i++){
				$mail->addAttachment($docs[$i]);
			}	
		}
	else{
		$mail->addAttachment($docs);
	}
	//$mail->SMTPDebug = 4; 	//enable error log to see the cause of email failure.
	if($env_prod){
		//$mail->Host       = "localhost"; // SMTP host
		$mail->SMTPAuth   = true;                  // enable SMTP authentication
		$mail->Host       = "smtpout.asia.secureserver.net"; // SMTP host
		$mail->Port       =  80;                    // set the SMTP port
		$mail->SetFrom($from, 'Company-Name');
		$mail->Username   = "welcome@Company-Name.com";  // SMTP  username
		$mail->Password   = "alifwel@123";  // SMTP password
		$mail->SMTPSecure = false;
		$mail->SMTPAutoTLS = false;
	}else{	//mail server configuration for test environment
		$mail->SMTPAuth   = true;                  // enable SMTP authentication
		$mail->SMTPSecure = 'ssl';
		$mail->Host       = "smtp.gmail.com"; // SMTP host
		$mail->Port       =  465;                    // set the SMTP port
		$mail->SetFrom("Company-Name.test@gmail.com", 'Company-Name');
		$mail->Username   = "Company-Name.test@gmail.com";  // SMTP  username
		$mail->Password   = "";  // SMTP password
	}
	if($mail->Send()){
		return true;
	} else{
		write_log(ERR, "Fail - " . $mail->ErrorInfo);
		return false;
	}	
}
?>
<script>
function js_img_remove(id,img){
	//alert("here")
	document.getElementById(id + "_remove_img").value=img
	document.getElementById(id + "_img").src=""
	document.getElementById(id ).style.display=""
	document.getElementById(id + "_remove").style.display="none"
}
</script>


<?php

function show_img($img){
	if($img.""<>""){
		return "data:image/jpeg;base64,".base64_encode($img);
	}else{
		return "$url_root/images/photonotavailable.gif";
	}
}

function img_control_db($obj_id,$path,$img){
	$select_display = "";
	if($img<>""){
		$select_display="none";
		?>
		<img id="<?=$obj_id?>_img" src="<?=show_img($img);?>" border="0" width="100">
		&nbsp;
		<a id="<?=$obj_id?>_remove" onclick="javascript: js_img_remove('<?=$obj_id?>','1');">Remove [X]</a>
	<?php }else{?>
	<?php }?>
	<input style="display:<?=$select_display?>;" id="<?=$obj_id?>" onchange="validate_fileupload(this);" type="file" name="<?=$obj_id?>">
	<input type="hidden" name="<?=$obj_id?>_remove_img" id="<?=$obj_id?>_remove_img">
	<?php
}

function img_control($obj_id,$path,$img){
	$select_display = "";
	if($img<>""){
		$select_display="none";
		?>
		<img id="<?=$obj_id?>_img" src="<?=$path.$img?>" border="0" width="100">
		&nbsp;
		<a id="<?=$obj_id?>_remove" onclick="javascript: js_img_remove('<?=$obj_id?>','<?=$img?>');">Remove [X]</a>
	<?php }else{?>
	<?php }?>
	<input style="display:<?=$select_display?>;" id="<?=$obj_id?>_select" type="file" name="<?=$obj_id?>">
	<input type="hidden" name="<?=$obj_id?>_remove_img" id="<?=$obj_id?>_remove_img">
	<?php
}

function remove_file($file_name){
	try{
		if(file_exists($file_name)){
			if(is_writable($file_name)){
				unlink($file_name);
			}
		}
	}
	catch(Exception $e){
		write_log(ERR, $e->getMessage());
	}
}

function img_upload_db($obj_id,$target_dir,&$img_name,&$img_thumb,$w="900",$h="625",$thumb="1"){
	
	$target_file = $target_dir . basename($_FILES[$obj_id]["name"]);
	$uploadOk = 1;
	$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
	$msg="1";
	// Check if(image file is a actual image or fake image
	
	if($_FILES[$obj_id]["name"]<>"") {
		$check = getimagesize($_FILES[$obj_id]["tmp_name"]);
		if($check !== false) {
			$msg =  "File is an image - " . $check["mime"] . ".";
			$uploadOk = 1;
		} else {
			$msg =  "File is not an image.";
			$uploadOk = 0;
		}
		
		if($_FILES[$obj_id]["size"] > 800 * 1024) {
			$msg =  "Sorry, your file is too large. Please upload files within 800kb only.";
			$uploadOk = 0;
		}
		// Allow certain file formats
		if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
			$msg =  "Sorry, only JPG, JPEG, PNG & Gif(files are allowed.";
			$uploadOk = 0;
		}
		
		if($uploadOk == 0) {
			// if(everything is ok, try to upload file
		} else {
			$target_file = $_FILES[$obj_id]["tmp_name"];
			$msg =  "1";
			$img_name = $_FILES[$obj_id]["name"];
			
			$img_name = img_resize_db($target_file, $w, $h, $imageFileType);
			
			if($thumb=="1"){
				$img_thumb = img_resize_db($target_file, 180, 200, $imageFileType);
			}

		}
		
	}
	return $msg;
}

function img_upload($obj_id,$target_dir,&$img_name,$w="900",$h="625",$thumb="1"){
	//$target_dir = "uploads/";
	$remove_img = func_read_qs($obj_id."_remove_img");
	if($remove_img <> ""){
		remove_file($target_dir.$remove_img);
		remove_file($target_dir."thumb/".$img_name);
	}
	
	$target_file = $target_dir . basename($_FILES[$obj_id]["name"]);
	$uploadOk = 1;
	$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
	$msg="1";
	// Check if(image file is a actual image or fake image
	
	if($_FILES[$obj_id]["name"]<>"") {
		$check = getimagesize($_FILES[$obj_id]["tmp_name"]);
		if($check !== false) {
			$msg =  "File is an image - " . $check["mime"] . ".";
			$uploadOk = 1;
		} else {
			$msg =  "File is not an image.";
			$uploadOk = 0;
		}

		if($_FILES[$obj_id]["size"] > 800 * 1024) {
			$msg =  "Sorry, your file is too large. Please upload files within 800kb only.";
			$uploadOk = 0;
		}
		// Allow certain file formats
		if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
			$msg =  "Sorry, only JPG, JPEG, PNG & Gif(files are allowed.";
			$uploadOk = 0;
		}
		
		if($uploadOk == 0) {
		} else {
			if(move_uploaded_file($_FILES[$obj_id]["tmp_name"], $target_file)) {
				$msg =  "1";
				$img_name = $_FILES[$obj_id]["name"];
				
				img_resize($target_file,$target_file, $w, $h, $imageFileType);
				if($thumb=="1"){
					img_resize($target_file,$target_dir."/thumb/".$img_name, 180, 200, $imageFileType);
				}								
			} else {
				$msg =  "Sorry, there was an error uploading your file.";
			}
		}
		
	}
	return $msg;
}

// Function for resizing jpg, gif, or png image files
function img_resize($target, $newcopy, $maxWidth=0, $maxHeight=0, $ext="jpg") {
    list($w_orig, $h_orig) = getimagesize($target);

	if ($originalWidth / $maxWidth > $originalHeight / $maxHeight) {
		// width is the limiting factor
		$width = $maxWidth;
		$height = floor($width * $originalHeight / $originalWidth);
	} else { // height is the limiting factor
		$height = $maxHeight;
		$width = floor($height * $originalWidth / $originalHeight);
	}
	
	
    $img = "";
    $ext = strtolower($ext);
    if ($ext == "gif"){ 
      $img = imagecreatefromgif($target);
    } else if($ext =="png"){ 
      $img = imagecreatefrompng($target);
    } else { 
      $img = imagecreatefromjpeg($target);
    }
    $tci = imagecreatetruecolor($w, $h);
    imagecopyresampled($tci, $img, 0, 0, 0, 0, $width, $height, $originalWidth, $originalHeight);
    imagejpeg($tci, $newcopy, 80);
}

function img_resize_db($target, $maxWidth=0, $maxHeight=0, $ext="jpg") {
	if(file_exists($target)){
		list($originalWidth, $originalHeight) = getimagesize($target);
	
		if ($originalWidth / $maxWidth > $originalHeight / $maxHeight) {
			// width is the limiting factor
			$width = $maxWidth;
			$height = floor($width * $originalHeight / $originalWidth);
		} else { // height is the limiting factor
			$height = $maxHeight;
			$width = floor($height * $originalWidth / $originalHeight);
		}

		$img = "";
		$ext = strtolower($ext);
		if ($ext == "gif"){ 
		  $img = imagecreatefromgif($target);
		} else if($ext =="png"){ 
		  $img = imagecreatefrompng($target);
		} else { 
		  $img = imagecreatefromjpeg($target);
		}
		$tci = imagecreatetruecolor($width, $height);
		imagecopyresampled($tci, $img, 0, 0, 0, 0, $width, $height, $originalWidth, $originalHeight);
		$temp_path = $_SERVER['DOCUMENT_ROOT']."/images/thumb.".$ext;
		imagejpeg($tci, $temp_path , 80);
		return file_get_contents($temp_path);
	}else{
		return "";
	}
}


function name_value($name){
	echo(" name='$name' value='".eval("return func_var($".$name.");")."'");
}

function create_pagination($cur_page,$page,$last_page,$start_page,$end_page){
	if($last_page>1){
		echo("<center><ul class=\"pagination page-link\">");
		
		if($page==0){
			echo "<li class=\"active\"><a href=\"#\"> << First</a>";
		}else{
			echo "<li><a href=\"$cur_page?page=0\"><< First</a>";
		}
		echo("</li><li>");
		
		if($page>0){
			echo " <a href=\"$cur_page?page=".($page-1)."\">< Previous</a>";
		}else{
			echo "<a href=\"#\"> < Previous</a>";
		}
		echo("</li>");
		
		if(intval($start_page)>1){
			echo("<li>...</li>");
		}
		for($p=$start_page-1;$p<$end_page;$p++){
			
			if($p==$page){
				echo("<li class=\"active\"><a href=\"#\">");
				echo(($p+1)."</a>");
			}else{
				echo("<li>");
				echo("<a href=\"$cur_page?page=$p\">".($p+1)."</a>");
			}
			echo("</li>");
		}
		if(intval($end_page)<intval($last_page)){
			echo("<li>...</li>");
		}
	
		echo("<li>");
		
		
		if(intval($page+1)<intval($last_page)){
			echo " <a href=\"$cur_page?page=".($page+1)."\">Next > </a>";
		}else{
			echo "<a href=\"#\">Next ></a>";
		}
		echo("</li><li>");
		
		if(intval($page+1)==$last_page){
			echo ("<li class=\"active\" ><a href=\"#\"> Last >></a>");
		}else{
			echo " <a href=\"$cur_page?page=".($last_page-1)."\">Last >></a>";
		}
		
		echo("</li></ul></center>");
		
	}
}


function create_list($sql,$url,$rec_limit=200,$pg_class="tbl_pages",$ba=5,$no_edit="",$list_class="table_form", $show_prof=3){
	global $con;
	?>
	<table width="100%" border="1" class="<?=$list_class?>">
	<?php
	$row_no = 0;
	$id="";
	
	$sql_count = "SELECT count(*) from ($sql) tot_rows ";

	if(get_rst($sql_count,$row,$rst,"1")){
		//echo($row[0]);
		$rec_count = $row[0];
		
	}else{
		die('Could not get data: ' . mysqli_error($con));
	}

	$page = func_read_qs("page");
	if($page==""){
		$page = 0;
		$offset = 0;
	}else{
		$offset = $rec_limit * $page ;
	}
	
	$left_rec = $rec_count - ($page * $rec_limit);

	//--------------------------------------------
	$rst = mysqli_query($con, $sql." LIMIT $offset, $rec_limit");
	if(mysqli_num_rows($rst)==0){ ?>
	<div class="alert alert-info">
		<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
		<?php echo "No records found";?>
	</div>	<?php
	}else{
		while ($row = mysqli_fetch_assoc($rst)) {
			$col = 0;
			
			if($row_no==0){
				$col = 0;
				foreach($row as $key => $value) {			
					if($col>0){
						echo("<th align='left' style='padding-left:10px; border: 0px;'>$key</th>");
					}
					$col++;
				}
				if($no_edit==""){
					if($show_prof == 1){
						echo("<th colspan=\"3\" style='padding-left:10px; border: 0px;'>Action</th>");
					}else{
						echo("<th style='padding-left:10px; border: 0px;'>Action</th>");
					}	
				}
				echo("</tr>");
			}
			echo("<tr>");
			$col = 0;
			foreach($row as $key => $value) {			
				if($col==0){
					$id = $value;
				}else{
					if($key == 'Default Address' || $key=='Account Activated' || $key=='Admin Approved' || $key=='LMD' || $key=='MOU Accepted' || $key=='Active Status' || $key=='Credit Status'){
						$var_value = "No";
						if($value == "1"){
							$var_value = "Yes";
						}
						echo("<td>&nbsp;&nbsp;<a href='$url?id=$id'>$var_value</a></td>");
					}elseif(($url == 'memb_order_details.php' || $url == 'order_details_with_coupon.php') && $key == 'Order Value'){
						if(get_rst("select coupon_id, coupon_amt, coupon_deduct from ord_coupon where cart_id=$id", $row_c)){
							$coupon_amt = $row_c["coupon_amt"] - $row_c["coupon_deduct"];
						}
							get_rst("select sum(cancel_coupon) as cancel_coupon,sum(cancel_amount) as cancel_amount from track_refund where cart_id=$id",$ro_c);
							if($ro_c["cancel_amount"] <> ""){
								$total_amount = $value - ($ro_c["cancel_amount"] + $ro_c["cancel_coupon"]);
								$value = $total_amount - ($row_c["coupon_amt"] - $ro_c["cancel_coupon"]);
							}else{
								$value = $value - $row_c["coupon_amt"];
							}
						echo("<td>&nbsp;&nbsp;<a href='$url?id=$id'>$value</a></td>");
					}else{
						$value = stripcslashes($value);
						echo("<td>&nbsp;&nbsp;<a href='$url?id=$id'>$value</a></td>");
					}	
				}
				$col++;
			}
			if($no_edit==""){
				if($show_prof == 1){
						echo("<td>&nbsp;&nbsp;<a title=\"Show Details\" href='show_sellers.php?id=$id'><span class=\"glyphicon glyphicon-user\"></span></a></td>
							<td>&nbsp;&nbsp;<a title=\"Seller Alias\" href='sup_alias.php?id=$id'><span class=\"glyphicon glyphicon-font\"></span></a></td>	
							<td>&nbsp;&nbsp;<a title=\"Edit\" href='$url?id=$id'><span class=\"glyphicon glyphicon-pencil\"></span></a></td>
						");
				}elseif($show_prof == 2){
					echo("<td><p><a title=\"Edit\" href='$url?id=$id'><span class=\"glyf glyphicon glyphicon-pencil\"></span></a><span>&nbsp;&nbsp;&nbsp;&nbsp;<a title=\"Remove\" href='$url?id=$id&dl=1' onclick=\"javascript: return delete_conform();\"><span class=\"glyf glyphicon glyphicon-remove\"></span></a></span></td>");
				}else{
					echo("<td>&nbsp;&nbsp;<a title=\"Edit Details\" href='$url?id=$id'><span class=\"glyphicon glyphicon-pencil\"></span></a></td>");
				}
			}
			echo("</tr>");
			$row_no++;
		}
	}
	//-------------------------------------------------------------
	$cur_page=$_SERVER['PHP_SELF'];
	$last_page = ceil($rec_count/$rec_limit);

	$start_page=1;
	$end_page=1;

	set_pages($ba,$page+0,$last_page,$start_page,$end_page);
	echo("</td></tr></table>");
	create_pagination($cur_page,$page,$last_page,$start_page,$end_page);		
	?>
	</table>	
	<?php
}

function create_ord_list($sql,$url,$rec_limit=200,$pg_class="tbl_pages",$ba=5, $no_edit="",$list_class="table_form"){
	global $con;
	?>
	<table width="100%" border="1" class="<?=$list_class?>">
	<?php
	$row_no = 0;
	$id="";
	$sup_id = 0;
	$sql_count = "SELECT count(*) from ($sql) tot_rows ";
	$retval = mysqli_query($con, $sql_count);
	if(! $retval )
	{
	  write_log(ERR,'Could not get data: ' . mysqli_error($con));
	}
	$row = mysqli_fetch_array($retval, MYSQLI_NUM );
	$rec_count = $row[0];
	
	$page = func_read_qs("page");
	if($page==""){
		$page = 0;
		$offset = 0;
	}else{
		$offset = $rec_limit * $page ;
	}
	
	$left_rec = $rec_count - ($page * $rec_limit);

	//--------------------------------------------
	$rst = mysqli_query($con, $sql." LIMIT $offset, $rec_limit");
	if($rst){
		while ($row = mysqli_fetch_assoc($rst)) {
			$col = 0;
			
			if($row_no==0){
				$col = 0;
				foreach($row as $key => $value) {			
					if($col>1){
						echo("<th align='left'>$key</th>");
					}
					$col++;
				}
				echo("<th>Action</th>");
				
				echo("</tr>");
			}
			echo("<tr>");
			$col = 0;
			foreach($row as $key => $value) {			
				if($col==0){
					$id = $value;
				}elseif($col==1){
					$sup_id	= $value;
				}else{
					echo("<td>&nbsp;&nbsp;<a href='$url?id=$id&sid=$sup_id'>$value</a></td>");
				}
				$col++;
			}
			echo("<td><p><a title=\"Edit\" href='$url?id=$id&sid=$sup_id'><span class=\"glyphicon glyphicon-pencil\"></span></a></td>");
			echo("</tr>");
			$row_no++;
		}
	}
	//-------------------------------------------------------------
	
	$cur_page=$_SERVER['PHP_SELF'];
	$last_page = ceil($rec_count/$rec_limit);

	$start_page=1;
	$end_page=1;

	set_pages($ba,$page+0,$last_page,$start_page,$end_page);
	echo("</td></tr></table>");
	create_pagination($cur_page,$page,$last_page,$start_page,$end_page);
	?>
	</table>
	
	<?php
}

function create_cbo($sql,$val=""){
	if(get_rst($sql,$row,$rst,"1")){
		do{
			?>
			<option value="<?=$row[0]?>" <?=sel_($val,$row[0])?> ><?=$row[1]?></option>
			<?php
		}while($row = mysqli_fetch_array($rst));
	}
}

function sel_($opt,$val){
	if($opt==$val){
		echo(" selected checked ");
	}
}

function sel_I($val,$opt){
	if($val.""<>"" AND $opt."" <> ""){
		$val = trim($val);
		$opt = trim($opt);
	
		if (strpos($val,$opt) !== false) {
			echo(" selected checked ");
			write_log(INFO, " selected checked ");
		}
	}
}

function instr($txt,$find){
	$pos = strpos($txt,$find);
	if($pos !== false) {
		return $pos;
	}else{
		return false;
	}
}

function set_pages($ba,$cur_page,$total_pages,&$start_page,&$end_page){
	$fixed_pages=(intval($ba) * 2) + 1;

	$start_page=1;
	$end_page=$total_pages;

	if(intval($total_pages)>intval($fixed_pages)){
		if(intval($cur_page)>intval($ba)+1){ $start_page=$cur_page-intval($ba); }
		
		if(intval($cur_page)+intval($ba)>intval($total_pages)){
			$start_page=intval($start_page) - (intval($cur_page)+intval($ba)-intval($total_pages));
		}
		$end_page=intval($start_page)+ intval($ba) * 2;
		if(intval($end_page)>intval($total_pages)){ 
			$start_page=intval($start_page) - (intval($end_page)-intval($total_pages));
			$end_page=$total_pages;
		}
	}
}

function limit_chars($txt,$limit){
	$v_txt = $txt;
	
	if(strlen($v_txt)>$limit){
		$v_txt = left($v_txt,$limit)."...";
	}
	
	return $v_txt;
}

function js_alert($msg){
	?>
	<script>
		alert("<?=$msg?>")
	</script>
	<?php
}

function js_redirect($url){
	?>
	<script>
		window.location.href="<?=$url?>"
	</script>
	<?php
	die();
}

function js_script($script){
	?>
	<script>
		<?=$script?>
	</script>
	<?php
}

function get_prod_url($rst_id,$cat_id,$rst_name=""){
	get_rst("SELECT level_name FROM levels WHERE level_id IN (SELECT level_parent FROM prod_mast WHERE prod_id=0$rst_id)",$row_lvl);
	$level_urlname = preg_replace('/[^a-zA-Z0-9]/',"-",$row_lvl["level_name"]);
	$urlname = preg_replace('/[^a-zA-Z0-9]/',"-",$rst_name);
	return "prod_details.php?product=$urlname&category=$level_urlname";
	global $con;
	$prod_url="";$qry_str="";$prod_id="";
	
	$prod_id = $rst_id;
	$rst = mysqli_query($con, "select prod_id,prod_url from prod_mast where prod_id=$rst_id");
	
	if ($rst){ 
		$row = mysqli_fetch_assoc($rst);
		$prod_url = $row["prod_url"]."";
		
		if($prod_url == ""){
			if($cat_id <> ""){ $qry_str = "&cat_id=" . cat_id;}
			return "details.php?prod_id=" . $prod_id . $qry_str;
		}else{
			if($cat_id <> ""){ $qry_str = "|" . $cat_id;}
			return $prod_url . $qry_str . ".htm";
		}
	}
}

//The following code snippet is used to find child of current level/category
function findchild($level_id,$level){
	global $con;
    //echo("[".$level_id."]");
	if($level_id <>""){
		$rslevels =mysqli_query($con, "select level_id from levels where level_parent=$level_id");
        if($rslevels){
			while($row=mysqli_fetch_assoc($rslevels)){
				$child_available = findchild($row["level_id"],intval($level)+1);

				$rsprods = mysqli_query("select prod_id from prod_cats where level_id=$level_id");
				if($rsprods){
					if(mysqli_num_rows($rsprods)){
						return true;
					}else{
						return $child_available;
					}
				}else{
					return $child_available;
				}
			}
		}else{
			$rslevels = mysqli_query($con, "select prod_id from prod_mast where prod_status=1 and prod_id in (select prod_id from prod_cats where level_id=$level_id)");
			if($rslevels){
				return true;
			}else{
				return false;
			}
		} //if not rslevels.EOF then
	}else{
		return false;
	} //if cat_id<>"" then
}


function display($txt){
	$txt = htmlspecialchars($txt."");
	$txt=str_replace("'","&#39;",$txt);

	return $txt;
}

function productbreadcrumb_new($cat_id,$prod_id,$prod_cat_name){
	$breadcrumb="";
    $cat_ids = "";
	global $con;
	while(intval($cat_id > 0)){
		$rslevels = mysqli_query($con, "select level_parent,level_id,level_name from levels where level_id=$cat_id");
		$row = mysqli_fetch_assoc($rslevels);
		$cat_ids = $cat_id . "," . $cat_ids;

		$cat_id = $row["level_parent"];

	} //'if level1_id <> "" then

	$cat_ids = $cat_ids . ",,";
	$cat_arr = explode(",",$cat_ids);
	$level1_id = $cat_arr[0];
	$level2_id = $cat_arr[1];
	$level3_id = $cat_arr[2];

	return productbreadcrumb($level1_id,$level2_id,$level3_id,$prod_cat_name);
}

function productbreadcrumb($level1_id,$level2_id,$level3_id,$prod_level_name){
	$breadcrumb = "";
	global $con;
	if($level1_id <> ""){

		$rslevels= mysqli_query($con,"select level_id,level_name from levels where level_id=$level1_id");		
		if($rslevels){
			$row = mysqli_fetch_assoc($rslevels);
			$level_name1 = $row["level_name"];
			$level_name = preg_replace('/[^a-zA-Z0-9]/',"-",$level_name1);
			$ahref="<a href='$url_root/prod_list.php?level=1&category=$level_name'>";
			$ehref="</a>";
			if($level2_id <> ""){
				$breadcrumb=  "| ".$ahref.$level_name1.$ehref;
			}else{
				$breadcrumb= $breadcrumb."|".$ahref."<span class='you-are-normal'>".$level_name1."</span>$ehref";
				$prod_level_name = $level_name1;
			}
		}		
		
		if($level2_id <> ""){
			$rslevels= mysqli_query($con, "select level_id,level_name from levels where level_id=$level2_id");		
			if($rslevels){ 
				$row = mysqli_fetch_assoc($rslevels);
				$level_name1 = $row["level_name"];
				$level_name = preg_replace('/[^a-zA-Z0-9]/',"-",$level_name1);
				$ahref="<a href='$url_root/prod_list.php?level=2&category=$level_name'>";
				$ehref="</a>";
				if($level3_id <> ""){	
					$breadcrumb= $breadcrumb." | ".$ahref.$level_name1.$ehref;
				}else{
					$breadcrumb= $breadcrumb." | ".$ahref."<span class='you-are-normal'>".$level_name1."</span>$ehref";
					$prod_level_name = $level_name1;
				}
			}	
			if($level3_id <> ""){
				$rslevels= mysqli_query($con, "select level_id,level_name from levels where level_id=$level3_id");	
				$level_name1 = $row["level_name"];
				$level_name = preg_replace('/[^a-zA-Z0-9]/',"-",$level_name1);
				$ahref="<a href='$url_root/prod_list.php?level=3&category=$level_name'>";
				$ehref="</a>";
				if($rslevels){
					$breadcrumb= $breadcrumb." | ".$ahref."<span class='you-are-normal'>".$level_name1."</span>$ehref";
					$prod_level_name = $level_name1;
				}
			}
		}
	}
	return $breadcrumb;
}


function get_max($TableName,$fieldName){
	global $con;
	$sqlMax = "select IFNULL(max($fieldName),0)+1 as max_id from $TableName";
		
	$rstMax= mysqli_query($con, $sqlMax);
	$row = mysqli_fetch_assoc($rstMax);
	
	return $row["max_id"];
}

function get_next($table,$fld){
	execute_qry("UPDATE $table SET $fld=LAST_INSERT_ID($fld+1)");
    get_rst("SELECT $fld from $table",$row);
	
	return $row[$fld];
}

//The following code snippet is used to build breadcrumb
function breadcrumblink($level_id,$level,$brand_id){
    //die($level_id.",".$level.",".$brand_id);
	global $con;
	$lvl = $level;
	$breadcrumb = "";
	if($level_id<>""){

        $breadcrumb="";

		$rslevels = mysqli_query($con, "select level_id,level_name,level_parent from levels where level_id=$level_id");
        $parent = "";

		if($rslevels){
			$row = mysqli_fetch_assoc($rslevels);

			$parent=$row["level_parent"]."";
			$cat_name=$row["level_name"]."";
			while(intval($parent) <> 0){
				$lvl = $lvl - 1;
				$ahref="<a href='levels.php?level=$lvl&level_id=$parent'>";
				$ehref="</a>";
				$rslevels= mysqli_query($con, "select level_name, level_id,level_parent from levels where level_id=$parent");
                $row = mysqli_fetch_assoc($rslevels);
                $parent=$row["level_parent"];
				$breadcrumb= $ahref.$row["level_name"].$ehref." | ".$breadcrumb;
			}
			$breadcrumb= $breadcrumb."<span class='you-are-normal'>".$cat_name."</span>";
		}
	}

	if($brand_id<> ""){;
		$rst_b = mysqli_query($con, "select brand_id,brand_name from brand_mast where brand_id=$brand_id");
		if($rst_b){;
			$row = mysqli_fetch_assoc($rst_b);
			if(intval($level)==0){
				$breadcrumb= $breadcrumb."<span class='you-are-normal'>".$row["brand_name"]."</span>";
			}else{
				$ahref="<a href='levels.php?brand_id=$brand_id'>";
				$ehref="</a>";
				
				$breadcrumb= $ahref.$row["brand_name"].$ehref." | ".$breadcrumb;
			}
		}
	}

	return $breadcrumb;
}

function breadcrumbs(){
	$level_id = func_read_qs("level_id");
	$brand_id = func_read_qs("brand_id");
	$path="";
	if($level_id.""<>""){
		$level_parent = $level_id;
		while(intval($level_parent) > 0){
			get_rst("select level_parent,level_id,level_name from levels where level_id=$level_parent",$row);
			
			$path = "<a href='prod_list.php?level_id=".$row["level_id"]."' >".$row["level_name"]."</a> | ".$path;
			$level_parent = $row["level_parent"];
		}		
	}
	
	if($brand_id.""<>""){
		if(get_rst("select brand_name,brand_id from brand_mast where brand_id=$brand_id",$row)){
			$path = "<a href='prod_list.php?brand_id=".$brand_id."' >".$row["brand_name"]."</a> | ";
		}
	}
	return $path;
	
}

function replace($txt ,$find ,$replace){
	return str_replace($find,$replace,$txt);
}

function left($txt,$len){
    return substr($txt,0,$len);
}


function get_cur_page_name(){
	$cur_page=$_SERVER['PHP_SELF'];
	return $cur_page;
}

function get_pagename($url){
	preg_match('/\/[a-z0-9]+.php/', $url, $match);
	return array_shift($match);
}

function push_body($file_name,$fld_arr,$html_format=""){
	$file = fopen(__DIR__."/../emails/".$file_name, "r") or die("not found");
	$count = 0;
	$line = "";
	$mail_body = "";
	
	while(!feof($file) AND $count<1000) {
	  $line = fgets($file);
	  
	  foreach($fld_arr as $fld => $val){
		$line = replace($line,"[$fld]",$val);
	  }
	  
	  $mail_body = $mail_body.$line;
	  if($html_format=="1"){
		$mail_body = $mail_body."<br>";
	  }
	  $count++;
	}
	fclose($file);
	return $mail_body;
}


function validate($rule){
	$arrRuleValues = explode("^",$rule);

	$validate_counter = 0;
	$numeric_counter = 0;
	$len_counter = 0;
	$checkcounter=0;
	$comppass=0;
    $comp_msg="";
    $numeric_msg="";
    $len_msg="";
    $date_msg="";
    $email_msg="";
    $pass_msg="";
    $date_msg="";
    $email_msg="";
    $pass_msg="";
    $makeRedField="";
	$invalid=false;
	$passwordErr = "";
    
	for ($intForCount = 0; $intForCount<count($arrRuleValues)-1; $intForCount++){

		$date_flag = false;
		$validate_flag=false;
		$numeric_flag = false;
		$email_flag=false;
		$len_flag=false;

		$arrRuleFieldValues = explode("|",$arrRuleValues[$intForCount]);

		$field_Name = $arrRuleFieldValues[0];
		$field_value = func_read_qs($arrRuleFieldValues[0]);

		$validationType = trim(strtolower(substr($arrRuleFieldValues[1],0,1)));
		$charLength = trim(substr($arrRuleFieldValues[1],1,strlen($arrRuleFieldValues[1])));
		
		switch($validationType){
			case "c":
				if($field_value == ""){ $validate_flag = true;}
                break;
			case "p":
				if (strlen($field_value) < '8') {
					$passwordErr = "Your Password Must Contain At Least 8 Characters";
				}
				elseif(!preg_match("#[0-9]+#",$field_value)) {
					$passwordErr = "Your Password Must Contain At Least 1 Number";
				}
				elseif(!preg_match("#[A-Z]+#",$field_value)) {
					$passwordErr = "Your Password Must Contain At Least 1 Capital Letter";
				}
				elseif(!preg_match("#[a-z]+#",$field_value)) {
					$passwordErr = "Your Password Must Contain At Least 1 Lowercase Letter";
				}else{
					$validate_flag="";
				}
				break;
			case "d":
				if($field_value <> ""){
					if(strtotime($field_value)){
						$date_flag = true;
					}
				}
                break;
			case "e":
				if($field_value <> ""){
					
					if(stripos($field_value," ") > 0) $invalid = true;
						
					if(stripos($field_value,"@") == 0) $invalid = true;
					
					if(stripos($field_value,".") == 0) $invalid = true;
					
					if(stripos($field_value,"@",stripos($field_value,"@")+1) > 0) $invalid = true;
					
					if($invalid) $email_flag = true;
				}
                break;
			case "n":
				if($field_value <> ""){
					if(!is_numeric($field_value)){
						$numeric_flag = true;
					}
				}
                break;
			case "l":
				if($field_value <> ""){
					if(strlen(trim($field_value)) > intval($charLength)){
						$len_flag=true;
					}
				}
                break;
			case "m":
				$checkcounter=$checkcounter-2;
				$pval=$arrRuleValues[$checkcounter];
				$splval=explode("|",$pval);
				$splvalue=$splval[0];
				$splvalue=func_read_qs($splvalue);
				//$comppass=strcomp(field_value,splvalue,vbTextCompare)
				if($field_value<>"" AND $field_value <> $splvalue){
					$comppass = 1;
					$makeRedField =  "|" . $field_Name . "|";
				}
                break;
		}
		
		if($validate_flag){
			if((trim($comp_msg) == "")){
				$comp_msg = "The form is incomplete. Kindly enter in the following field(s) and resubmit :<br>&nbsp;� ".trim($arrRuleFieldValues[2]);
			}else{
				$comp_msg = $comp_msg."<br>&nbsp;� ".Trim($arrRuleFieldValues[2]);
			}
			$validate_counter = $validate_counter + 1;
		}
			
		if($date_flag){
			if(trim($date_msg) == ""){
				$date_msg = "Invalid date format :<br>&nbsp;� trim($arrRuleFieldValues[2])";
			}else{
				$date_msg = $date_msg. "<br>&nbsp;� trim($arrRuleFieldValues[2])";
			}
		}

		if($numeric_flag){
			if(trim($numeric_msg) == ""){
				$numeric_msg = "Please enter numerical value for following field(s) :<br>&nbsp;� trim($arrRuleFieldValues[2])";
			}else{
				$numeric_msg = $numeric_msg. "<br>&nbsp;� trim($arrRuleFieldValues[2])";
			}
			$numeric_counter = $numeric_counter + 1;
		}
				
		if($email_flag){
			if(trim($email_msg) == ""){
				$email_msg = "Invalid E-mail format : <br>&nbsp;� ".trim($arrRuleFieldValues[2]);
			}else{
				$email_msg = $email_msg. "<br>&nbsp;� ".Trim($arrRuleFieldValues[2]);
			}	
		}
		
		if($len_flag){
			if(trim($len_msg) == ""){
				$len_msg = "You have exceeded the maximum limit for this field(s) :<br>&nbsp;� ".trim($arrRuleFieldValues[2]);
			}else{
				$len_msg = $len_msg. "<br>&nbsp;� ".trim($arrRuleFieldValues[2]);
			}
			$len_counter = $len_counter + 1;
		}
		
		if($comppass <> 0){
			$pass_msg="The Password and Confirm Password fields do not match, please retype.";
		}	
				
		if($validate_flag or $date_flag or $numeric_flag or $len_flag or $email_flag){
			$makeRedField = $makeRedField."|".$field_Name."|";
		}
		$checkcounter=$checkcounter+1;
	}
	
	if($validate_counter > 1){
		$comp_msg = replace($comp_msg ,"(s)" ,"s");
	}else{
		$comp_msg = replace($comp_msg ,"(s)" ,"");
	}	
	
	if($numeric_counter > 1){
		$numeric_msg = replace($numeric_msg ,"(s)" ,"s");
	}else{
		$numeric_msg = replace($numeric_msg ,"(s)" ,"")	;
	}	
	
	if($len_counter > 1){
		$len_msg = replace($len_msg ,"(s)" ,"s");
	}else{
		$len_msg = replace($len_msg ,"(s)" ,"");
	}
	$incomp_msg = "";

	if($comp_msg <>""){ $comp_msg = $comp_msg."<br><br>"; }
	if($date_msg <>""){ $date_msg = $date_msg."<br><br>"; }
	if($numeric_msg <>""){ $numeric_msg = $numeric_msg."<br><br>"; }
	if($email_msg <>""){ $email_msg = $email_msg."<br><br>"; }
	if($len_msg <>""){ $len_msg = $len_msg."<br><br>"; }
	if($pass_msg <> ""){ $pass_msg=$pass_msg."<br><br>"; }
	if($passwordErr <> ""){ "<br>&nbsp;� ".$passwordErr=$passwordErr."<br><br>"; }

	$incomp_msg = trim($comp_msg .$date_msg .$numeric_msg .$email_msg.$len_msg.$pass_msg.$passwordErr);

	if($incomp_msg <>""){ $incomp_msg = left($incomp_msg,strlen($incomp_msg)-8); }

    return $incomp_msg;
}

function check_pwd($field_value){
	$passwordErr="";
	if (strlen($field_value) < '8') {
		$passwordErr = "Your password must contain at least 8 characters";
	}
	elseif (strlen($field_value) > '54') {
		$passwordErr = "Your password can contain maximum 54 characters";
	}
	elseif(!preg_match("#[0-9]+#",$field_value)) {
		$passwordErr = "Your password must contain at Least 1 number";
	}
	elseif(!preg_match("#[A-Z]+#",$field_value)) {
		$passwordErr = "Your password must contain at least 1 capital letter";
	}
	elseif(!preg_match("#[a-z]+#",$field_value)) {
		$passwordErr = "Your password must contain At least 1 lowercase letter";
	}else{
		$passwordErr = "Ok";
	}
	
	return $passwordErr;
}

function check_email($field_value){
	$invalid = false;
	
	if(stripos($field_value," ") > 0) $invalid = true;

	if(stripos($field_value,"@") == 0) $invalid = true;
	
	if(stripos($field_value,".") == 0) $invalid = true;
	
	if(stripos($field_value,"@",stripos($field_value,"@")+1) > 0) $invalid = true;
	
	return $invalid;
}

function get_act_id($id){
	return sha1(mt_rand(10000,99999).time().$id);
}

function get_base_url(){
	return "http://".$_SERVER["SERVER_NAME"];	//.":".$_SERVER['SERVER_PORT'];
}

function func_act_link($id,$entity){
	$link = "http://".$_SERVER["SERVER_NAME"];
	$link = $link."\\".$entity.".phpid=".sha1(mt_rand(10000,99999).time().$id);
	return $link;
}

function prev_page_url(){
    $url = "";
    if(isset($_SERVER["HTTP_REFERER"])){
        $url=$_SERVER["HTTP_REFERER"];
        }
    If($url == ""){ $url = "index.php"; }
	$from_page = replace($url,"//","");
	$splitUrl = explode("/",$from_page);
	$prev_page_url = $splitUrl[sizeof($splitUrl)-1];
}


function get_price($row,&$price,&$prod_ourprice,&$disc_per=0){
	$prod_offerprice = $row["prod_offerprice"];
	$prod_ourprice=$row["prod_ourprice"];
	$price = $prod_ourprice;
	$offer=false;
	$disc_per = "";
	if($prod_offerprice > 0){ 
		$disc_per = ($price-$prod_offerprice)/$price*100;
		$disc_per = round($disc_per,2);
		if($disc_per < 0){ $disc_per = 0;}
		$price=$prod_offerprice;
		$offer=true;
	}
}

function SEO_fields($page_title,$meta_key,$meta_desc){
	?>
	<tr>
		<td>Page Title</td>
		<td><input type="text" size="71" maxlength="100" name="page_title" value="<?=func_var($page_title)?>" title="Will appear as title of Browser Window"></td>	
	</tr>
	<tr>
		<td>Meta Keywords</td>
		<td><input type="text" size="71" maxlength="500" name="meta_key" value="<?=func_var($meta_key)?>">
		<br>(Relevant keywords separated by a space)<br><br>
		</td>
	</tr>
	<tr>
		<td>Meta Description</td>
		<td valign="middle">
		<?php control_textarea("meta_desc",func_var($meta_desc),2000) ?>		
		<br><br> (This tag provides a short description of the page. In some situations this description is used as a part of the snippet shown in the search results.)
		<br><br>
		</td>
		
	</tr>
	<?php
}

function get_rst($sql,&$row="",&$rst="",$get_arr=""){

	global $con;
	$rst = mysqli_query($con,$sql);
	if (mysqli_errno($con) <>0) {
		write_log(ERR, "Problem selecting records... ".mysqli_error($con)."SQL Query: ".$sql);
		?>
	<div class="alert alert-info">
	<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
		<?php die("Oops! There is something went wrong. Please try again after some time. "); ?>
	</div>	
	<?php
	return false;
	}
	
	if($rst){
		if($get_arr=="1"){
			$row = mysqli_fetch_array($rst);
		}else{
			$row = mysqli_fetch_assoc($rst);
		}
	}else{
		return false;
	}
	
	if($row){
		return true;
	}else{
		return false;
	}
	
}

function control_text($name,$value,$maxlength,$id="",$size="72",$help=""){
	?>
	<input type="text" name="<?=$name?>" value="<?=$value?>" size="<?=$size?>" maxlength="<?=$maxlength?>" id="<?=$id?>">
	<?php
	if($help<>""){
		echo("<br>$help<br>");
	}
}

function control_textarea($name,$value,$maxlength,$id="",$size="72",$help="",$class=""){
	$max_msg = "max $maxlength characters";
	if($value.""<>""){
		$max_msg = intval($maxlength) - strlen($value) . " characters left";
	}
	?>
	<textarea name="<?=$name?>" <?=$class?> onkeyup="javascript: textCounter(this,'left_<?=$name?>',<?=$maxlength?>);"><?=func_var($value)?></textarea>
	<span id="left_<?=$name?>" style="color:#aaaaaa;"><?=$max_msg?></span>
	<?php
	if($help<>""){
		echo("<br>$help<br>");
	}
}

function remove_tab($txt){
	$txt = replace($txt,"\t"," ");
	$txt = replace($txt,"\n"," ");
	$txt = replace($txt,"\r"," ");
	
	return $txt;
}

function prod_viewed($prod_id){
	global $con;
	execute_qry("update prod_mast set prod_viewed=IFNULL(prod_viewed,0)+1 where prod_id=0$prod_id");
	
	$user_id = intval(func_var($_SESSION["memb_id"]));
	
	$fld_arr = array();
	$sql = "select view_id from prod_viewed where (session_id='".session_id()."' or ($user_id<>0 AND user_id=$user_id)) and prod_id=0$prod_id";

	if(get_rst($sql,$row)){
		
		mysqli_query($con, "update prod_viewed set view_count=view_count+1,view_date=CURRENT_TIMESTAMP where view_id=".$row["view_id"]);
		if(mysqli_errno($con)<>0){
			write_log(ERR, mysqli_error($con));
		}
	}else{
		$fld_arr["session_id"] = session_id();
		$fld_arr["user_id"] = $user_id;
		$fld_arr["prod_id"] = $prod_id;
		$fld_arr["view_count"] = 1;
		
		$sql = func_insert_qry("prod_viewed",$fld_arr);
		mysqli_query($con, $sql);	
	}
}

function logging($log_page="",$log_event="",$log_activity=""){
	global $con;
	if($log_page==""){
		$log_page = get_cur_page_name();	
	}

	$fld_arr = array();
	$fld_arr["log_page"] = $log_page;
	$fld_arr["log_event"] = $log_event;
	$fld_arr["log_activity"] = $log_activity;
	
	$sql = func_insert_qry("logging",$fld_arr);
	mysqli_query($con, $sql);	
}

function add_to_basket($prod_id,$sup_name,$img_thumb,$price,$qty,$sup_id,$item_wish, $memb_id=0){
	if($item_wish <> ""){
		$qry = "";
		if($memb_id <> 0){
			$qry = "select cart_id from cart_summary where user_id=$memb_id";
		}else{
			$qry = "select cart_id from cart_summary where session_id='".session_id()."' and user_id IS NULL";
		}
		
		if(!get_rst($qry,$row)){
			$cart_id = get_next("seq_cart_id","cart_id");
			$_SESSION["cart_id"] = $cart_id;
		}else{
			$_SESSION["cart_id"] = $row["cart_id"];
		}
		
		$qry = "";
		if($memb_id <> 0){
			$qry = "select cart_id from cart_summary where user_id=$memb_id and sup_id=$sup_id";
		}else{
			$qry = "select cart_id from cart_summary where session_id='".session_id()."' and sup_id=$sup_id and user_id IS NULL";
		}	
		if(!get_rst($qry, $row)){
			$fld_s_arr = array();
			$fld_s_arr["session_id"] = "".session_id()."";
			$fld_s_arr["sup_id"] = $sup_id;
			$fld_s_arr["cart_id"] = $_SESSION["cart_id"];
			if($memb_id <> 0){
				$fld_s_arr["user_id"] = $memb_id;
			}
			$sql = func_insert_qry("cart_summary",$fld_s_arr);
			execute_qry($sql);
		}else{
			$_SESSION["cart_id"] = $row["cart_id"];
		}
	}
	$v_item_wish="";
	$qry = "";
	$qry2 = "";
	if($memb_id <> 0){
		$qry = "select * from cart_items where prod_id=$prod_id and sup_id=$sup_id and memb_id=$memb_id" ;
	}else{
		$qry = "select * from cart_items where session_id='".session_id()."' and prod_id=$prod_id and sup_id=$sup_id and memb_id IS NULL";
	}
	if(get_rst($qry,$row)){
		$qry2 = "";
		$cart_qty = $row["cart_qty"];
		$v_item_wish = $row["item_wish"];
		if($item_wish == "1" and  $v_item_wish == 0){
			remove_item_from_cart_summary($sup_id, $_SESSION["cart_id"], $prod_id);
			if($memb_id <> 0){
				execute_qry("delete from cart_items where memb_id=$memb_id and prod_id=$prod_id and sup_id=$sup_id");
			}else{
				execute_qry("delete from cart_items where session_id='".session_id()."' and prod_id=$prod_id and sup_id=$sup_id");
			}
			
			add_to_basket($prod_id,$sup_name,$img_thumb,$price,$qty,$sup_id,$item_wish,$memb_id);
		}else{
			if($item_wish == $v_item_wish and $item_wish=="0") $cart_qty = $cart_qty + $qty;
			$fld_arr = array();
			$fld_arr["cart_qty"] = $cart_qty;
			$fld_arr["item_wish"] = $item_wish;
			get_rst("select prod_weight from prod_mast where prod_id=$prod_id",$row_wt);
			
			//recalculate shipping charges
			$ship_amt = 0; 
			
			get_rst("select sup_ext_pincode from sup_ext_addr where sup_id=$sup_id LIMIT 1", $s_row);
			
			//check for local logistics if applicable
			if(((substr($s_row["sup_ext_pincode"],0,3)==411 || substr($s_row["sup_ext_pincode"],0,3)==410 || substr($s_row["sup_ext_pincode"],0,3)==412) && 
				(substr($_SESSION["memb_pin"],0,3) == 411 ||  substr($_SESSION["memb_pin"],0,3) == 410 || substr($_SESSION["memb_pin"],0,3) == 412)) || (floatval($row_wt["prod_weight"]) * $cart_qty)> 5)
			
			{ 
				$ship_amt = get_local_ship_charges($_SESSION["memb_pin"], $sup_id, floatval($row_wt["prod_weight"]) * $cart_qty);
				execute_qry("update cart_summary set local_logistics = 1 where sup_id=$sup_id AND cart_id=".$_SESSION["cart_id"]);
			}else{
				execute_qry("update cart_summary set local_logistics = 0 where sup_id=$sup_id AND cart_id=".$_SESSION["cart_id"]);
				$ship_amt = get_shipping_charges($s_row["sup_ext_pincode"], $_SESSION["memb_pin"], $row_wt["prod_weight"] * $cart_qty);
			}
		

			if($row_wt["prod_weight"] < 1 && $price >= 2000){	//For free shipping offer
				$fld_arr["ship_amt"]= 0;
				$fld_arr["actual_ship_amt"]=$ship_amt;
			}else{
				$fld_arr["ship_amt"] = $ship_amt;
			}

			if($memb_id <> 0){
				$qry2 = func_update_qry("cart_items",$fld_arr," where memb_id=$memb_id and prod_id=$prod_id and sup_id=$sup_id");
				//"update cart_items set cart_qty = $cart_qty, item_wish=$item_wish where memb_id=$memb_id and prod_id=$prod_id and sup_id=$sup_id";
			}else{
				$qry2 = func_update_qry("cart_items",$fld_arr," where session_id='".session_id()."' and prod_id=$prod_id and sup_id=$sup_id");
				//"update cart_items set cart_qty = $cart_qty, item_wish=$item_wish where session_id='".session_id()."' and prod_id=$prod_id and sup_id=$sup_id and memb_id IS NULL";
			}
			execute_qry($qry2);
			update_cart_summary($memb_id);
		}
	}else{
		if(get_rst("select prod_name,prod_lmd,prod_thumb1,prod_stockno,prod_tax_id,prod_tax_name,prod_tax_percent, prod_weight from prod_mast where prod_id=$prod_id",$row)){

			$fld_arr = array();
			$fld_arr["session_id"] = session_id();
			$fld_arr["prod_id"] = $prod_id;
			$fld_arr["item_name"] = $row["prod_name"];
			$fld_arr["item_stock_no"] = $row["prod_stockno"];
			$fld_arr["item_thumb"] = $row["prod_thumb1"];
			$fld_arr["sup_name"] = $sup_name;
			
			$fld_arr["cart_qty"] = $qty;
			$fld_arr["cart_price"] = $price;
			$fld_arr["sup_id"] = $sup_id;
			$fld_arr["item_wish"] = $item_wish;			
			
			$fld_arr["tax_id"] = $row["prod_tax_id"];
			$fld_arr["tax_name"] = $row["prod_tax_name"]."";
			$fld_arr["tax_percent"] = $row["prod_tax_percent"];
			$fld_arr["tax_value"] = floatval($price) * floatval($row["prod_tax_percent"]."")/100;
			$fld_arr["cart_price_tax"] = floatval($price) + $fld_arr["tax_value"];
			
			$fld_arr["cart_id"] = $_SESSION["cart_id"];
			if($memb_id <> 0){
				$fld_arr["memb_id"] = $memb_id;
			}	
			$ship_amt = 0; 
			
			//if((get_rst("select sup_company from sup_mast where sup_id=$sup_id and sup_lmd=1")) && $row["prod_lmd"]==1){
				get_rst("select sup_ext_pincode from sup_ext_addr where sup_id=$sup_id LIMIT 1", $s_row);
				
				//check for local logistics if applicable
				if(((substr($s_row["sup_ext_pincode"],0,3)==411 || substr($s_row["sup_ext_pincode"],0,3)==410 || substr($s_row["sup_ext_pincode"],0,3)==412) && 
					(substr($_SESSION["memb_pin"],0,3) == 411 ||  substr($_SESSION["memb_pin"],0,3) == 410 || substr($_SESSION["memb_pin"],0,3) == 412)) || (floatval($row["prod_weight"]) * $qty)> 5)
				
				{ 
					$ship_amt = get_local_ship_charges($_SESSION["memb_pin"], $sup_id, floatval($row["prod_weight"]) * $qty);
					execute_qry("update cart_summary set local_logistics = 1 where sup_id=$sup_id AND cart_id=".$_SESSION["cart_id"]);
				}else{
					execute_qry("update cart_summary set local_logistics = 0 where sup_id=$sup_id AND cart_id=".$_SESSION["cart_id"]);
					$ship_amt = get_shipping_charges($s_row["sup_ext_pincode"], $_SESSION["memb_pin"], $row["prod_weight"] * $qty);
				}
			//}

			if($row["prod_weight"] < 1 && $price >= 2000){	//For free shipping offer
				$fld_arr["ship_amt"]= 0;
				$fld_arr["actual_ship_amt"]=$ship_amt;
			}else{
				$fld_arr["ship_amt"] = $ship_amt;
			}
			$sql = func_insert_qry("cart_items",$fld_arr);
			execute_qry($sql);
		}
	}
	if($item_wish <> "1"){
		update_cart_summary($memb_id);
	}
}

function update_cart_summary($memb_id = 0){
	$qry = "";
	$qry2 = "";
	if($memb_id <> 0){
		$qry = "select sup_id,cart_qty, sum(cart_qty * cart_price) as item_total,count(1) as item_count,sum(cart_qty * tax_value) as tax_total,sum(ship_amt) as shipping_charges from cart_items where item_wish<>1 and memb_id=$memb_id group by sup_id";
	}else{
		$qry = "select sup_id,sum(cart_qty * cart_price) as item_total,count(1) as item_count,sum(cart_qty * tax_value) as tax_total,sum(ship_amt) as shipping_charges from cart_items where session_id='".session_id()."' and item_wish<>1 and memb_id IS NULL group by sup_id";		
	}
	
	if(get_rst($qry,$row,$rst)){
		do{

			$fld_arr = array();
			$sup_id = $row["sup_id"];
			$fld_arr["item_count"] = $row["item_count"];
			$fld_arr["item_total"] = $row["item_total"];
			//$fld_arr["vat_percent"] = $_SESSION["vat"];
			$fld_arr["vat_value"] = $row["tax_total"];
			$fld_arr["shipping_charges"] = 0;
			//if(get_rst("select sup_company from sup_mast where sup_id=$sup_id and sup_lmd=1")){
			$fld_arr["shipping_charges"] = $row["shipping_charges"];
			//}
			$fld_arr["ord_total"] = floatval($row["item_total"]."") + floatval($fld_arr["shipping_charges"])+ floatval($fld_arr["vat_value"]);
			if($memb_id <> 0){
				$sql = func_update_qry("cart_summary",$fld_arr," where user_id=$memb_id and sup_id=$sup_id");
			}else{
				$sql = func_update_qry("cart_summary",$fld_arr," where session_id='".session_id()."' and sup_id=$sup_id and user_id IS NULL");
			}
			execute_qry($sql);
		}while($row = mysqli_fetch_assoc($rst));
	}
	if(get_rst("select coupon_id from ord_coupon where cart_id='".$_SESSION["cart_id"]."'", $row_c)){   //if change in cart delete coupon...
		execute_qry("delete from ord_coupon where coupon_id='".$row_c["coupon_id"]."' and cart_id='".$_SESSION["cart_id"]."'");
	}
}

function remove_item_from_cart_summary($sup_id, $cart_id, $prod_id){
	$qry = "select cart_qty, sum(cart_qty * cart_price) as item_total,count(1) as item_count,sum(cart_qty * tax_value) as tax_total,sum(ship_amt) as shipping_charges from cart_items where prod_id=$prod_id and cart_id=$cart_id and sup_id=$sup_id group by sup_id";		
	
	if(get_rst($qry,$row,$rst)){
		get_rst("select item_count, item_total, vat_value, shipping_charges, ord_total from cart_summary where cart_id=$cart_id and sup_id=$sup_id",$row_sum);
		$fld_arr = array();
		$fld_arr["item_count"] = $row_sum["item_count"] - $row["item_count"];
		$fld_arr["item_total"] = $row_sum["item_total"] - $row["item_total"];

		$fld_arr["vat_value"] = $row_sum["tax_total"] - $row["tax_total"];
		$fld_arr["shipping_charges"] = 0;
		$fld_arr["shipping_charges"] = $row_sum["shipping_charges"] - $row["shipping_charges"];

		$fld_arr["ord_total"] = $row_sum["ord_total"] - (floatval($row["item_total"]."") + floatval($fld_arr["shipping_charges"]) + floatval($fld_arr["vat_value"]));
		
		if($fld_arr["item_count"] == 0){
			$sql = "delete from cart_summary where cart_id=$cart_id and sup_id=$sup_id";
		}else{
			$sql = func_update_qry("cart_summary",$fld_arr," where cart_id=$cart_id and sup_id=$sup_id");
		}
		execute_qry($sql);
	}
	if(get_rst("select coupon_id from ord_coupon where cart_id='".$_SESSION["cart_id"]."'", $row_c)){   //if change in cart delete coupon...
		execute_qry("delete from ord_coupon where coupon_id='".$row_c["coupon_id"]."' and cart_id='".$_SESSION["cart_id"]."'");
	}
}

function update_order($memb_id, $cart_id,$new_sup_id,$old_sup_id,$ord_no,$prod_id,$actual_price,$new_qty,$tax_per){
	$qry = "select sup_id,cart_datetime, cart_qty, sum(cart_qty * cart_price) as item_total,count(1) as item_count,sum(cart_qty * tax_value) as tax_total,sum(ship_amt) as shipping_charges from ord_items where item_wish<>1 and memb_id=$memb_id and cart_id=$cart_id group by sup_id";
	
	if(get_rst($qry,$row,$rst)){
		//echo $qry;
		do{
			$fld_arr = array();
			$sup_id = $row["sup_id"];
			$fld_arr["item_count"] = $row["item_count"];
			$fld_arr["item_total"] = $row["item_total"];
			$fld_arr["vat_value"] = $row["tax_total"];
			$fld_arr["shipping_charges"] = 0;
			$fld_arr["shipping_charges"] = $row["shipping_charges"];
			$fld_arr["cart_datetime"] = $row["cart_datetime"];
			$new_price = ($actual_price * $tax_per)/100;
			$new_seller_price = (($actual_price + $new_price) * $new_qty) + $row["shipping_charges"];
			$fld_arr["ord_total"] = floatval($row["item_total"]."") + floatval($fld_arr["shipping_charges"])+ floatval($fld_arr["vat_value"]);
			$sql = func_update_qry("ord_summary",$fld_arr," where user_id=$memb_id and sup_id=$sup_id and cart_id=$cart_id");
			execute_qry($sql);
			execute_qry("update ord_summary set new_seller_price=".$new_seller_price." where cart_id=$cart_id and sup_id=$new_sup_id and user_id=$memb_id");
		}while($row = mysqli_fetch_assoc($rst));
	}
	send_re_assign_seller_mail($new_sup_id,$cart_id,$old_sup_id,$ord_no,$prod_id);
}

function update_ord_summary($cart_id,$cart_item_id,$sup_id,$ord_no){
	$row_item_total=0;
	$row_item_count=0;
	$row_tax_total=0;
	if(get_rst("select sup_id,sum(cart_qty * cart_price) as item_total,count(1) as item_count,sum(cart_qty * tax_value) as tax_total,sum(ship_amt) as shipping_charges from ord_items where cart_id=0$cart_id and sup_id=0$sup_id and item_buyer_action is null group by sup_id",$row,$rst)){
		$row_item_total = $row["item_total"];
		$row_item_count = $row["item_count"];
		$row_tax_total = $row["tax_total"];
	}
	$shipping_charges = 0;
	if(intval($row_item_total)>0){
		if(get_rst("select sup_company from sup_mast where sup_id=$sup_id and sup_lmd=1")){
			$shipping_charges = $row["shipping_charges"];
		}
	}
	
	$fld_arr = array();
	
	$fld_arr["item_count"] = $row_item_count;
	$fld_arr["item_total"] = $row_item_total;
	//$fld_arr["vat_percent"] = $_SESSION["vat"];
	$fld_arr["vat_value"] = $row_tax_total;
	$fld_arr["shipping_charges"] = $shipping_charges;
	
	$fld_arr["ord_total"] = floatval($fld_arr["item_total"]."") + floatval($shipping_charges)+ floatval($fld_arr["vat_value"]);
	
	$sql = func_update_qry("ord_summary",$fld_arr," where cart_id=$cart_id and sup_id=$sup_id");
	execute_qry($sql);
	update_refund_amt($cart_id,$cart_item_id,$sup_id,$ord_no);
}
function update_refund_amt($cart_id,$cart_item_id,$sup_id,$ord_no){
 get_rst("select coupon_id,coupon_amt,coupon_deduct from ord_coupon where cart_id=$cart_id",$row_id); 
 $fld_arr = array();
 $fld_arr_ref = array();
 
 get_rst("select buyer_action,pay_status from ord_summary where cart_id=$cart_id and sup_id=$sup_id",$rc);
 get_rst("select cancel_amount,cancel_coupon from track_refund where cart_id=$cart_id and sup_id=$sup_id",$r);
 if($row_id["coupon_id"] <> ""){
		get_rst("select ord_id,user_id from ord_summary where cart_id=$cart_id",$row_canc,$rst_canc); 
	
		if($rc["buyer_action"] == 'Cancelled'){
			get_rst("select sum((cart_price_tax * cart_qty) + ship_amt) as total from ord_items where cart_id=$cart_id and sup_id=$sup_id",$rwc);
			if($rc["pay_status"] == 'Paid'){
				if($r["cancel_amount"] <> ""){
					$actual_amt = $rwc["total"] - ($r["cancel_amount"] + $r["cancel_coupon"]);
				}else{
					$actual_amt = $rwc["total"];
				}
			}else{
				if($r["cancel_amount"] <> ""){
					$actual_amt = $rwc["total"];
				}else{
					$actual_amt = $rwc["total"];
				}
			}
			get_rst("select sum((cart_price_tax * cart_qty) + ship_amt) as total_val from ord_items where cart_id=$cart_id",$rw_c);
			$total = $rw_c["total_val"];
			
			$act_amt_per = $actual_amt / $total;
			$coupon_ref = floatval((floatval($row_id["coupon_amt"]) * $act_amt_per));
			$coupon_ded = $coupon_ref + $row_id["coupon_deduct"];	
			$refund_amt = $actual_amt - $coupon_ref;			
		}else{
		get_rst("select ((cart_price_tax *cart_qty) + ship_amt) as total, item_buyer_action from ord_items where cart_id=$cart_id and cart_item_id=$cart_item_id",$row_rtn);
		$actual_amt = $row_rtn["total"];
		
		get_rst("select sum((cart_price_tax * cart_qty) + ship_amt) as ord_total from ord_items where cart_id=$cart_id",$rw_tot);
		
			$total = $rw_tot["ord_total"];
			$act_amt_per = $actual_amt / $total;
			
			$coupon_ref = floatval((floatval($row_id["coupon_amt"]) * $act_amt_per));
			$coupon_ded = $coupon_ref + $row_id["coupon_deduct"];
			$refund_amt = $actual_amt - $coupon_ref;
		}
		$fld_arr_ref["ord_id"] = $row_canc["ord_id"];
		$fld_arr_ref["user_id"] = $row_canc["user_id"];
		$fld_arr_ref["sup_id"] = $sup_id;
		$fld_arr_ref["ord_no"] = $ord_no;
		$fld_arr_ref["cart_id"] = $cart_id;
		$fld_arr_ref["cart_item_id"] = $cart_item_id;
		$fld_arr_ref["pay_status"] = $rc["pay_status"];
		
	if($rc["pay_status"]=='Paid'){
		$fld_arr_ref["refund_amt"] = $refund_amt;
		$fld_arr_ref["coupon_deduct"] = $coupon_ref; 
	}else{		
		$cancel_amount = $refund_amt;
		$cancel_coupon = $coupon_ref;
		$fld_arr_ref["cancel_amount"] = $cancel_amount;
		$fld_arr_ref["cancel_coupon"] = $cancel_coupon;
		}
	
	if($rc["buyer_action"] == 'Cancelled'){
		get_rst("select ord_no from ord_summary where cart_id=$cart_id and sup_id=$sup_id",$r);
		if($r["ord_no"] == $ord_no){
			$qry = func_update_qry("track_refund",$fld_arr_ref,"where ord_no='$ord_no'");
			execute_qry($qry);
		}
		if(get_rst("select sup_id from track_refund where ord_no='$ord_no'")){
			$qry2 = func_update_qry("track_refund",$fld_arr_ref,"where ord_no='$ord_no'");
			execute_qry($qry2);
		}else{
			$qry1 = func_insert_qry("track_refund",$fld_arr_ref);
			execute_qry($qry1);
		}
	}else{	
		$qry1 = func_insert_qry("track_refund",$fld_arr_ref);
		execute_qry($qry1);
	}	
	$fld_arr["coupon_deduct"] = $coupon_ded;
	
		$qry = func_update_qry("ord_coupon",$fld_arr,"where cart_id=$cart_id");
		execute_qry($qry);
	
   if($row_rtn["item_buyer_action"]=='Returned'){
		 
	   get_rst("select cart_price,actual_ship_amt from ord_items where cart_item_id=$cart_item_id",$row_rtn);
	   $amt_return = $row_rtn["cart_price"];
	   if($row_rtn["actual_ship_amt"] <> ""){
		   $amt_return = $amt_return - $row_rtn["actual_ship_amt"];
	   }
	   
	   $actual_return = $amt_return;
	   get_rst("select pay_total from invoice_mast where ord_no=(select ord_no from ord_summary where cart_id=$cart_id and sup_id=$sup_id)",$row_r);

	   $return_amt = $row_r["pay_total"] - $actual_return;
	   
	   $fld_arr_inv["pay_total"] = $return_amt;
	   
	   get_rst("select ord_no from ord_summary where cart_id=$cart_id and sup_id=$sup_id",$row_inv);
	   
	   $qry2 = func_update_qry("invoice_mast",$fld_arr_inv,"where ord_no='".$row_inv["ord_no"]."'");
	   execute_qry($qry2);
   }
 }
 else{
	 $fld_arr_ref = array();
	 $flrd_arr_inv = array();
		 get_rst("select ord_id,user_id from ord_summary where cart_id=$cart_id",$row_canc,$rst_canc);
		 
		 get_rst("select buyer_action,pay_status from ord_summary where cart_id=$cart_id and sup_id=$sup_id",$rc);
		 get_rst("select sum((cart_price_tax * cart_qty) + ship_amt) as total from ord_items where cart_id=$cart_id and sup_id=$sup_id",$rwc);
		if($rc["buyer_action"] == 'Cancelled'){
			if($rc["pay_status"] == 'Paid'){
				if($r["cancel_amount"] <> ""){
					$actual_amt = $rwc["total"] - ($r["cancel_amount"] + $r["cancel_coupon"]);
					$refund_amt = $actual_amt;
				}else{
					$actual_amt = $rwc["total"];
					$refund_amt = $actual_amt;
				}
			}else{
				if($r["cancel_amount"] <> ""){
					$actual_amt = $rwc["total"];
					$refund_amt = $actual_amt;
				}else{
					$actual_amt = $rwc["total"];
					$refund_amt = $actual_amt;
				}
			}
		}else{
	
		get_rst("select ((cart_price_tax * cart_qty) + ship_amt) as total, item_buyer_action from ord_items where cart_id=$cart_id and cart_item_id=$cart_item_id",$row_rtn);
		$actual_amt = $row_rtn["total"];
		$refund_amt = $actual_amt;
		}
		$fld_arr_ref["ord_id"] = $row_canc["ord_id"];
		$fld_arr_ref["user_id"] = $row_canc["user_id"];
		$fld_arr_ref["sup_id"] = $sup_id;
		$fld_arr_ref["cart_id"] = $cart_id;
		$fld_arr_ref["cart_item_id"] = $cart_item_id;
		$fld_arr_ref["ord_no"] = $ord_no;
		$fld_arr_ref["pay_status"] = $rc["pay_status"];
		
	if($rc["pay_status"]=='Paid'){
		$fld_arr_ref["refund_amt"] = $refund_amt;	
	}else{
		$cancel_amount = $refund_amt;
		$fld_arr_ref["cancel_amount"] = $cancel_amount;
	}	
	if($rc["buyer_action"] == 'Cancelled'){
		get_rst("select ord_no from ord_summary where cart_id=$cart_id and sup_id=$sup_id",$r);
		if($r["ord_no"] == $ord_no){
			$qry = func_update_qry("track_refund",$fld_arr_ref,"where ord_no='$ord_no'");
		}
		if(get_rst("select sup_id from track_refund where ord_no='$ord_no'")){
			$qry = func_update_qry("track_refund",$fld_arr_ref,"where ord_no='$ord_no'");
			execute_qry($qry);
		}else{
			$qry1 = func_insert_qry("track_refund",$fld_arr_ref);
			execute_qry($qry1);
		}
	}else{		
		$qry = func_insert_qry("track_refund",$fld_arr_ref);
		execute_qry($qry);
	}
	 if($row_rtn["item_buyer_action"]=='Returned'){
		 
	   get_rst("select cart_price,actual_ship_amt from ord_items where cart_item_id=$cart_item_id",$row_rtn);
	   $amt_return = $row_rtn["cart_price"];
	   if($row_rtn["actual_ship_amt"] <> ""){
		   $amt_return = $amt_return - $row_rtn["actual_ship_amt"];
	   }
	   $actual_return = $amt_return;
	   get_rst("select pay_total from invoice_mast where ord_no=(select ord_no from ord_summary where cart_id=$cart_id and sup_id=(select sup_id from ord_items where cart_item_id=$cart_item_id))",$row_r);
	   
	   $return_amt =  $row_r["pay_total"] - $actual_return;
	   
	   $fld_arr_inv["pay_total"] = $return_amt;
	   
	   get_rst("select ord_no from ord_summary where cart_id=$cart_id and sup_id=(select sup_id from ord_items where cart_item_id=$cart_item_id)",$row_inv);
	   
	   $qry2 = func_update_qry("invoice_mast",$fld_arr_inv,"where ord_no='".$row_inv["ord_no"]."'");
	   execute_qry($qry2);
   }
	}
	if($rc["pay_status"] == 'Paid'){
	send_refund_mail($cart_id,$cart_item_id,$coupon_ref,$sup_id,$actual_return);
	}else{
		get_rst("select bill_name,bill_tel from ord_details where cart_id=$cart_id",$row);
		if($row_id["coupon_id"] == ""){
			$sms_body = "Dear ".$row["bill_name"].", <br> your order $ord_no with amount Rs.$cancel_amount has been cancelled";
			send_sms($row["bill_tel"],$sms_body);
		}else{
			$sms_body = "Dear ".$row["bill_name"].", <br> your order $ord_no with amount Rs.$cancel_amount and coupon deduction Rs.$cancel_coupon has been cancelled";
			send_sms($row["bill_tel"],$sms_body);
		}
	}
}

function send_refund_mail($cart_id,$cart_item_id,$coupon_ref,$sup_id,$actual_return,$ord_no){
	$fld_arr = array();
	$fld_sum_arr = array();
	$fld_item_arr = array();
	$body_details = "";
	$body_sums = "";
	$body_items = "";
	$bill_email = "";
	$bill_fname = "";
	$bill_tel = "";
	$ord_no = "";
	$body_supplier = "";
	$cc=" Company-Name <".$_SESSION["admin_email"].">";
	
	get_rst("select buyer_action from ord_summary where cart_id=$cart_id and sup_id=$sup_id",$rwc);
	if($rwc["buyer_action"]==""){
	get_rst("select item_buyer_action from ord_items where cart_item_id=$cart_item_id",$row_item);
	$item_buyer_action = $row_item["item_buyer_action"];
	
	if(get_rst("select * from ord_summary where cart_id=(select cart_id from ord_items where cart_item_id=$cart_item_id) and sup_id=$sup_id",$row_s,$rst_s)){
		$cart_id = $row_s["cart_id"];
		foreach($row_s as $key => $value) {
			$fld_arr[$key] = $value;
		}
	}	
	
	$ord_pay_total = 0;
	
	if($cart_id <> ""){
		if(get_rst("select * from ord_details where cart_id=".$cart_id,$row)){
			foreach($row as $key => $value) {
				$fld_arr[$key] = $value;
			}
			$bill_email=$row["bill_email"];
			$bill_fname=$row["bill_name"];
			$bill_tel=$row["bill_tel"];
		}
		
		do{
			$ord_no = $row_s["ord_no"];
			$body_supplier = "";
			foreach($row_s as $key => $value) {
				$fld_sum_arr[$key] = $value;
			}
			$sup_id = $row_s["sup_id"];
			$ord_pay_total = $ord_pay_total + $row_s["ord_total"];
			$body_items = "";
			$body_items_sup = "";

			if(get_rst("select * from ord_items where cart_id=$cart_id and cart_item_id=$cart_item_id and sup_id=$sup_id",$row,$rst)){
				if(get_rst("select sup_company from sup_mast where sup_id=$sup_id",$rw)){
					$fld_item_arr["sup_name"] = $rw["sup_company"];
				}
				do{				
					foreach($row as $key => $value) {
						$fld_item_arr[$key] = $value;
					}				
					$fld_item_arr["prod_url"] = get_base_url()."/prod_details.php?prod_id=".$row["prod_id"];
					$fld_item_arr["item_thumb"] = show_img($row["item_thumb"]);
					if($row["tax_percent"].""<>""){
						$fld_item_arr["tax_percent"] = $row["tax_percent"]."%";
					}else{
						$fld_item_arr["tax_percent"] = "";
					}
					
					if($row["ship_amt"]==0){
						$fld_item_arr["shipping_charges"] = "Free";
					}else{
					$fld_item_arr["shipping_charges"] = $row["ship_amt"];
					}
					$fld_item_arr["total_amount"] = (floatval($row["cart_price_tax"]) * intval($row["cart_qty"]))+(intval($row["cart_qty"]) * floatval($row["ship_amt"]));
					
					get_rst("select refund_amt from track_refund where cart_id=$cart_id and cart_item_id=$cart_item_id",$rww);
					$fld_item_arr["refund_amt"] = $rww["refund_amt"];
					
					$body_item = push_body("ord_refund_item.txt",$fld_item_arr);
					$body_items = $body_items.$body_item;
					if($item_buyer_action == 'Returned'){
						$body_item_sup = push_body("ord_return_items_sup.txt",$fld_item_arr);
						$body_items_sup = $body_items_sup.$body_item_sup;
					}
				}while($row = mysqli_fetch_array($rst));
				
				$fld_sum_arr["ord_no"]=$ord_no;
				$fld_sum_arr["ord_date"]=$fld_arr["ord_date"];
				
				if($item_buyer_action == 'Returned'){
					$fld_sum_arr["ord_return_items_sup"] = $body_items_sup;
					
					get_rst("select cart_price,actual_ship_amt from ord_items where cart_item_id=$cart_item_id",$row_price);
					$fld_sum_arr["cart_price"] = $row_price["cart_price"];
					
					get_rst("select pay_total from invoice_mast where sup_id=$sup_id and ord_no='$ord_no'",$rw_pay);
					$fld_sum_arr["return_amt"] = $rw_pay["pay_total"];
					
					if($row_price["actual_ship_amt"] <> ""){
						$fld_sum_arr["actual_ship_amt"] = $row_price["actual_ship_amt"];
					}else{
						$fld_sum_arr["actual_ship_amt"] = 0.00;
					}
					
					$body_sum_sup = push_body("ord_return_summary_sup.txt",$fld_sum_arr);
					$body_sum_sup = $body_sums_sup.$body_sum_sup;
					$fld_arr["ord_return_summary_sup"] = $body_sum_sup;
				}
				get_rst("select coupon_id from ord_coupon where cart_id=$cart_id",$row_id); 
				get_rst("select ((cart_price_tax * cart_qty) + ship_amt) as actual_amount from ord_items where cart_item_id=$cart_item_id",$row_ref);
				if($row_id["coupon_id"] <> ""){
					
					$fld_sum_arr["actual_amount"] = $row_ref["actual_amount"];
					$fld_sum_arr["coupon_deductables"] = formatNumber($coupon_ref,2);
					$fld_sum_arr["total_refund"] = $fld_item_arr["refund_amt"];
				}else{
					$fld_sum_arr["actual_amount"] = $row_ref["actual_amount"];
					$fld_sum_arr["coupon_deductables"] = 0.00;
					$fld_sum_arr["total_refund"] = $fld_item_arr["refund_amt"];
				}
				$fld_sum_arr["ord_no"]=$ord_no;
				$fld_sum_arr["ord_date"]=$fld_arr["ord_date"];
				
				$fld_sum_arr["ord_refund_item"] = $body_items;
				$body_sum = push_body("ord_refund_summary.txt",$fld_sum_arr);
			}
			$fld_arr["ord_pay_total"] = $row_s["ord_total"];

			if($item_buyer_action == 'Returned'){
				$fld_arr["ord_return_summary_sup"] = $body_sum_sup;
				$body_supplier = push_body("ord_return_details_sup.txt",$fld_arr);
				
				if(get_rst("select sup_email,sup_contact_no, sup_company from sup_mast where sup_id=$sup_id",$row)){
					$sup_email = $row["sup_email"];
					$sup_tel = $row["sup_contact_no"];
					$sup_company = $row["sup_company"];
					send_mail($sup_email,"Company-Name - Order Return(Seller): $ord_no",$body_supplier,"",$cc);
				}
			}
			//send sms to buyer
					$fld_sms_arr = array();
					$fld_sms_arr["ord_no"] = $ord_no;
					$fld_sms_arr["memb_fname"] = $bill_fname;
					$fld_sms_arr["refund_amt"] = $fld_item_arr["refund_amt"];
					$sms_msg = push_body("sms_ord_refund.txt",$fld_sms_arr);
					send_sms($bill_tel,$sms_msg);
					
			$fld_arr["ord_refund_summary"] = $body_sum;
		}while($row_s = mysqli_fetch_assoc($rst_s));

		$fld_arr["ord_refund_item"] = $body_items.$body_items;
		$fld_arr["ord_refund_summary"] = $body_sum;
		$fld_arr["ord_pay_total"] = $ord_pay_total;
		
		$body_details = push_body("ord_refund.txt",$fld_arr);
		send_mail($bill_email,"Company-Name - Order Amount Refund: $ord_no",$body_details,"",$cc);
    }
  }else{
	 if(get_rst("select * from ord_summary where cart_id=$cart_id and sup_id=$sup_id",$row_s,$rst_s)){
		$cart_id = $row_s["cart_id"];
		foreach($row_s as $key => $value) {
			$fld_arr[$key] = $value;
		}
	}	
	
	$ord_pay_total = 0;
	
	if($cart_id <> ""){
		if(get_rst("select * from ord_details where cart_id=".$cart_id,$row)){
			foreach($row as $key => $value) {
				$fld_arr[$key] = $value;
			}
			$bill_email=$row["bill_email"];
			$bill_fname=$row["bill_name"];
			$bill_tel=$row["bill_tel"];
		}
		
		do{
			$ord_no = $row_s["ord_no"];
			$body_supplier = "";
			foreach($row_s as $key => $value) {
				$fld_sum_arr[$key] = $value;
			}
			$sup_id = $row_s["sup_id"];
			$ord_pay_total = $ord_pay_total + $row_s["ord_total"];
			$body_items = "";
			$body_items_sup = "";

			if(get_rst("select * from ord_items where cart_id=$cart_id and sup_id=$sup_id",$row,$rst)){
				if(get_rst("select sup_company from sup_mast where sup_id=$sup_id",$rw)){
					$fld_item_arr["sup_name"] = $rw["sup_company"];
				}
				do{				
					foreach($row as $key => $value) {
						$fld_item_arr[$key] = $value;
					}				
					$fld_item_arr["prod_url"] = get_base_url()."/prod_details.php?prod_id=".$row["prod_id"];
					$fld_item_arr["item_thumb"] = show_img($row["item_thumb"]);
					if($row["tax_percent"].""<>""){
						$fld_item_arr["tax_percent"] = $row["tax_percent"]."%";
					}else{
						$fld_item_arr["tax_percent"] = "";
					}
					
					if($row["ship_amt"]==0){
						$fld_item_arr["shipping_charges"] = "Free";
					}else{
					$fld_item_arr["shipping_charges"] = $row["ship_amt"];
					}
					$fld_item_arr["total_amount"] = (floatval($row["cart_price_tax"]) * intval($row["cart_qty"]))+(intval($row["cart_qty"]) * floatval($row["ship_amt"]));
					
					$fld_item_arr["refund_amt"] = $row["cart_price_tax"] + $row["ship_amt"];
					
					$body_item = push_body("ord_refund_item.txt",$fld_item_arr);
					$body_items = $body_items.$body_item;

				}while($row = mysqli_fetch_array($rst));
				
				$fld_sum_arr["ord_no"]=$ord_no; 
				$fld_sum_arr["ord_date"]=$fld_arr["ord_date"];
				get_rst("select sum(cancel_amount) as cancel_amount,sum(cancel_coupon) as cancel_coupon,coupon_deduct,refund_amt from track_refund where cart_id=$cart_id and sup_id=$sup_id",$ro_c);
				get_rst("select coupon_id ,coupon_amt from ord_coupon where cart_id=$cart_id",$row_id); 
				get_rst("select sum((cart_price_tax * cart_qty) + ship_amt) as actual_amount from ord_items where cart_id=$cart_id and sup_id=$sup_id",$row_ref);
				if($row_id["coupon_id"] <> ""){
					if($ro_c["cancel_amount"] <> ""){
						$fld_sum_arr["actual_amount"] = formatNumber($row_ref["actual_amount"] - ($ro_c["cancel_amount"] + $ro_c["cancel_coupon"]),2);
						$fld_sum_arr["coupon_deductables"] = formatNumber($ro_c["coupon_deduct"],2);
						$fld_sum_arr["total_refund"] = formatNumber($ro_c["refund_amt"],2);
					}else{
						$fld_sum_arr["actual_amount"] = formatNumber($row_ref["actual_amount"],2);
						$fld_sum_arr["coupon_deductables"] = formatNumber($ro_c["coupon_deduct"],2);
						$fld_sum_arr["total_refund"] = formatNumber($row_ref["actual_amount"] - $ro_c["coupon_deduct"],2);
					}
				}else{
					if($ro_c["cancel_amount"] <> ""){
						$fld_sum_arr["actual_amount"] = formatNumber($row_ref["actual_amount"] - $ro_c["cancel_amount"],2);
						$fld_sum_arr["total_refund"] = $fld_sum_arr["actual_amount"];
					}else{
						$fld_sum_arr["actual_amount"] = formatNumber($row_ref["actual_amount"],2);
						$fld_sum_arr["total_refund"] = formatNumber($row_ref["actual_amount"],2);
					}
					$fld_sum_arr["coupon_deductables"] = 0.00;
				}
				$fld_sum_arr["ord_no"]=$ord_no;
				$fld_sum_arr["ord_date"]=$fld_arr["ord_date"];
				
				$fld_sum_arr["ord_refund_item"] = $body_items;
				$body_sum = push_body("ord_refund_summary.txt",$fld_sum_arr);
			}
			
			$fld_arr["ord_pay_total"] = $row_s["ord_total"];
			
			$fld_arr["ord_refund_summary"] = $body_sum;
		}while($row_s = mysqli_fetch_assoc($rst_s));

					//send sms to buyer
					$fld_sms_arr = array();
					$fld_sms_arr["ord_no"] = $ord_no;
					$fld_sms_arr["memb_fname"] = $bill_fname;
					$fld_sms_arr["refund_amt"] = $fld_sum_arr["total_refund"];
					$sms_msg = push_body("sms_ord_refund.txt",$fld_sms_arr);
					send_sms($bill_tel,$sms_msg);
					
		$fld_arr["ord_refund_item"] = $body_items.$body_items;
		$fld_arr["ord_refund_summary"] = $body_sum;
		$fld_arr["ord_pay_total"] = $ord_pay_total;
		
		$body_details = push_body("ord_refund.txt",$fld_arr);
		
		send_mail($bill_email,"Company-Name - Order Amount Refund: $ord_no",$body_details,"",$cc);
    } 
  }
}

function update_shipping_charges($memb_pincode, $cart_id){
	if(get_rst("select cart_item_id, prod_id, cart_price, cart_qty, sup_id from cart_items where cart_id=$cart_id ", $row, $rst)){
		do{
			$prod_id = $row["prod_id"];
			$sup_id = $row["sup_id"];
			$cart_qty = $row["cart_qty"];
			$cart_item_id= $row["cart_item_id"];
			get_rst("select prod_weight, prod_lmd from prod_mast where prod_id=0$prod_id",$rw);
			$ship_amt = 0; 
			//if((get_rst("select sup_company from sup_mast where sup_id=$sup_id and sup_lmd=1")) && $rw["prod_lmd"]==1){
				get_rst("select sup_ext_pincode from sup_ext_addr where sup_id=$sup_id LIMIT 1", $s_row);
				
				//check for local logistics if applicable
				if(((substr($s_row["sup_ext_pincode"],0,3)==411 || substr($s_row["sup_ext_pincode"],0,3)==410 || substr($s_row["sup_ext_pincode"],0,3)==412) && 
					(substr($memb_pincode,0,3) == 411 ||  substr($memb_pincode,0,3) == 410 || substr($memb_pincode,0,3) == 412)) || (floatval($rw["prod_weight"]) * $cart_qty) > 5)
				{ 					
					$ship_amt = get_local_ship_charges($_SESSION["memb_pin"], $sup_id, floatval($rw["prod_weight"]) * $cart_qty);
					execute_qry("update cart_summary set local_logistics = 1 where sup_id=$sup_id AND cart_id=".$_SESSION["cart_id"]);
				}else{
					execute_qry("update cart_summary set local_logistics = 0 where sup_id=$sup_id AND cart_id=".$_SESSION["cart_id"]);
					$ship_amt = get_shipping_charges($s_row["sup_ext_pincode"], $memb_pincode, $rw["prod_weight"] * $cart_qty);			
				}
			//}

			if($rw["prod_weight"] < 1 && $row["cart_price"] >= 2000){	//for free shipping....
				execute_qry("update cart_items set ship_amt=0,actual_ship_amt=$ship_amt where cart_item_id=$cart_item_id");
			}else{
				execute_qry("update cart_items set ship_amt=$ship_amt where cart_item_id=$cart_item_id");
			}
		}while($row = mysqli_fetch_assoc($rst));
	}
}
function get_local_ship_charges($memb_pincode,$sup_id,$weight){
	$ship_charges = 0;
	if(get_rst("select charges from local_logistics_charges where min_weight<=$weight and max_weight>=$weight", $s_row)){
		$ship_charges = $s_row["charges"];
	}
	return $ship_charges;
}


function get_shipping_charges($sup_pincode, $memb_pincode, $weight){
	$service_tax = 0.04;
	$fuel_surcharge = 0.25;

	$shipping_charges = 0;
	$shipping_addn = 0;
	$zone = "";
	if(substr($sup_pincode,0,3) == substr($memb_pincode,0,3)){
	//withing city ship charges
		$zone = "WITHINCITY";
	}elseif(getDistance($sup_pincode, $memb_pincode) < 500){
		//Regional-within 500km charges
		$zone = "WITHIN500";
	}else{
		//zonal charges METRO/ROI/NE
		$sql = "select zone from city_mast where pincode LIKE '".substr($memb_pincode,0,3)."%' LIMIT 1";
		if(get_rst($sql,$row_s)){		
			$zone = $row_s["zone"];
		}
	}
	$weight_factor = 0;
	if($weight > 0.5){
		$weight_factor = intval($weight / 0.5) - 1;
	}
	
	$sql = "select ship_min_charge, ship_addn_500 from ship_mast where ship_zone='$zone'";
	if(get_rst($sql,$row_s)){		
		$shipping_charges = $row_s["ship_min_charge"] + ($weight_factor * $row_s["ship_addn_500"]);
		$shipping_charges = $shipping_charges + ($shipping_charges * ($service_tax + $fuel_surcharge)); 
	}
	return $shipping_charges;
}

function execute_qry($sql){
	global $con;
	if(!mysqli_query($con, $sql)){
		write_log(ERR, "Problem updating database... ".mysqli_error($con)."SQL query: ".$sql);
	?>
	<div class="alert alert-info">
	<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <?php echo "Oops! There is some problem updating your record. Please try again after some time.";?>
	</div>	
	<?php
	}else{
		return mysqli_insert_id($con);
	}
}

function isRed($txt){
	return "";

}

function save_order($pay_method,$cart_id=""){
	$pay_status = "Pending";
	if($cart_id==""){
		$memb_id = $_SESSION["memb_id"];
		$sql_where_summary = " where user_id=$memb_id";
		$sql_where = " where memb_id=$memb_id";
	}else{
		$sql_where_summary = " where cart_id=$cart_id";	
		$sql_where = " where cart_id=$cart_id";	
	}
	if($pay_method == "OC"){
		if(get_rst("select memb_credit_limit, memb_min_credit_ord_amt from member_mast where memb_id=".$_SESSION["memb_id"]." and memb_credit_status=1", $row_credit)){
			$this_month = date('Y-m-01')." 00:00:00";
			$credit_used = 0;
			if(get_rst("select sum(ord_total) as total_amt from ord_summary where ord_date >= '".$this_month."' and pay_method='OC' and user_id=".$_SESSION["memb_id"], $row_credit_amt)){
				$credit_used = $row_credit_amt["total_amt"];
			}
			get_rst("select sum(ord_total) as total_amt from cart_summary $sql_where_summary",$row_s);
			if ($row_s["total_amt"] > ($row_credit["memb_credit_limit"] - $credit_used)){
				return "Your order amount exceeds your credit limit, please complete the order with other mode of payment.";
			}elseif($row_s["total_amt"] < $row_credit["memb_min_credit_ord_amt"]){
				return "Your order amount is less than minimum order amount required to buy on credit.";
			}
		}else{
			 return "Sorry, You do not have permission to place the order on credit";
		}
		$pay_status = "OnCredit";
	}
	execute_qry("INSERT INTO ord_summary SELECT * FROM cart_summary $sql_where_summary and item_count is NOT NULL");
	execute_qry("INSERT INTO ord_items SELECT * FROM cart_items $sql_where and item_wish<>1");
	execute_qry("INSERT INTO ord_details SELECT * FROM cart_details $sql_where");
	execute_qry("delete FROM cart_summary $sql_where_summary");
	execute_qry("delete FROM cart_items $sql_where and item_wish<>1");
	execute_qry("delete FROM cart_details $sql_where");
	if($_SESSION["cart_id"] == ""){
		get_rst("select cart_id from ord_summary $sql_where_summary and session_id='".session_id()."'", $row_cart);
		$_SESSION["cart_id"] = $row_cart["cart_id"];
	}
    $count=1;
	if(get_rst("select count(sup_id) as scount from ord_summary where cart_id=".$_SESSION["cart_id"]. " group by cart_id", $row)){
		$count = $row["scount"];
	}
	$ord_id = get_max("ord_summary","ord_id");
    do{
		$qry = "update ord_summary set ord_id=$ord_id, ord_no='".$_SESSION["user_type"]."".date('ymd').str_pad($ord_id, 7-strlen($ord_id), "0", STR_PAD_LEFT)."".$count."'";
		$qry = $qry.", ord_date=NOW(), user_type='M', pay_status='".$pay_status."', delivery_status='Pending' where cart_id=".$_SESSION["cart_id"]." and ord_no IS NULL LIMIT 1";
		execute_qry($qry);
		$count = $count - 1;
	}while($count > 0);
	
	if(get_rst("select coupon_id from ord_coupon where cart_id=".$_SESSION["cart_id"],$row_oc )){
		$fld_arr = array();
		$fld_arr["memb_id"] = $_SESSION["memb_id"];
		$fld_arr["coupon_id"] = $row_oc["coupon_id"];
		$fld_arr["applied_status"] = 1;
		$qry = func_insert_qry("memb_coupon",$fld_arr);
		execute_qry($qry);
		
		if(get_rst("select max_discount_value from offer_coupon where credit_flag=1 and coupon_id=".$row_oc["coupon_id"],$mdv)){
			execute_qry("update offer_coupon set credit_flag=NULL where coupon_id=".$row_oc["coupon_id"]);
			get_rst("select credit_amt from credit_details where memb_id=".$_SESSION["memb_id"],$cr);
			$creditamt= $cr['credit_amt'] - $mdv["max_discount_value"];
			execute_qry("update credit_details set credit_amt=$creditamt where memb_id=".$_SESSION["memb_id"]);
		}
	}
	execute_qry("update prod_mast set prod_purchased=IFNULL(prod_purchased,0)+1 where prod_id in (select prod_id from ord_items where cart_id=".$_SESSION["cart_id"].")");
	
	sendcustomernotification_order($_SESSION["cart_id"],$_SESSION["memb_id"]);
	sendsellernotification_order($_SESSION["cart_id"]);
	
	$_SESSION["cart_id"]="";
	$_SESSION["ord_id"]=$ord_id;
	
	// Update wishlist item after order is placed
	$new_cart_id = get_next("seq_cart_id","cart_id");
	execute_qry("update cart_items set cart_id=0$new_cart_id $sql_where");
	if(get_rst("select DISTINCT sup_id from cart_items where cart_id=0$new_cart_id", $row, $rst)){
		do{
			$fld_s_arr = array();
			$fld_s_arr["session_id"] = "".session_id()."";
			$fld_s_arr["sup_id"] = $row["sup_id"];
			$fld_s_arr["cart_id"] = $new_cart_id;
			if($memb_id <> 0){
				$fld_s_arr["user_id"] = $memb_id;
			}
			$sql = func_insert_qry("cart_summary",$fld_s_arr);
			execute_qry($sql);
		}while($row = mysqli_fetch_array($rst));
	}
	send_ord_mail();
	return "success";
}

function re_assign_seller($cart_id, $prod_id, $new_sup_id, $old_sup_id, $memb_id, $ord_no, $item_count,$cart_price,$tax_value,$ship_amt,$cart_qty,$tax_per){
	get_rst("select final_price from prod_sup where prod_id=$prod_id and sup_id=$new_sup_id", $row_p);
	if(get_rst("select cart_item_id from ord_items where cart_id=$cart_id and prod_id=$prod_id and sup_id=$old_sup_id",$row)){
		$actual_price = $row_p["final_price"];
		get_rst("select sup_alias from sup_mast where sup_id=$new_sup_id",$row_s);
		
		//update the record in the order item with the new seller details
		
		if(get_rst("select cart_item_id,cart_id,tax_percent,sup_id,cart_price,cart_qty,tax_value,ship_amt from ord_items where prod_id=$prod_id and cart_id=$cart_id and sup_id=$new_sup_id",$row_qty)){
			$new_qty = $cart_qty + $row_qty["cart_qty"];
			$new_cart_price = (($cart_price * $cart_qty) + $row_qty["cart_price"])/$new_qty;
			$new_tax_value = $new_cart_price*($row_qty["tax_percent"]/100);
			$new_ship_amt = $ship_amt + $row_qty["ship_amt"];
			$new_cart_price_tax = $new_cart_price + $new_tax_value;
			
			execute_qry("delete from ord_items where prod_id=$prod_id and cart_id=$cart_id and sup_id=$old_sup_id");
			execute_qry("update ord_items set cart_price=$new_cart_price, cart_qty=$new_qty, tax_value=$new_tax_value, ship_amt=$new_ship_amt, cart_price_tax=$new_cart_price_tax,new_seller_price=$actual_price where prod_id=$prod_id and cart_item_id=".$row_qty["cart_item_id"]);
			execute_qry("update prod_sup set out_of_stock=1 where prod_id=$prod_id and sup_id=$old_sup_id");
		}else{
			execute_qry("update ord_items set new_seller_price=$actual_price, sup_id=$new_sup_id, sup_name='".$row_s["sup_alias"]."' where cart_item_id=".$row["cart_item_id"]);
			execute_qry("update prod_sup set out_of_stock=1 where prod_id=$prod_id and sup_id=$old_sup_id");
		}
		$del_flag = 0;
		//to insert the record in order summary after deleting for 1 item
		get_rst("select ord_date,pay_status,delivery_status,pay_method,pay_method_name,ord_id,user_type from ord_summary where cart_id=$cart_id and ord_no='$ord_no' and sup_id=$old_sup_id",$row_ins);
		
		if($item_count == 1){
			execute_qry("delete from ord_summary where ord_no='$ord_no'");
			$del_flag = 1;
		}
		if(!get_rst("select sup_id from ord_summary where cart_id=$cart_id and sup_id=$new_sup_id")){
			$session_id = "".session_id()."";
			$count=1;
			if(get_rst("select count(sup_id) as scount from ord_summary where cart_id=$cart_id group by cart_id", $row)){
				$count = $row["scount"] + 1;
			}
			$order_no="M".date('ymd').str_pad($row_ins["ord_id"], 7-strlen($row_ins["ord_id"]), "0", STR_PAD_LEFT)."".$count;
			execute_qry("insert into ord_summary SET session_id='".$session_id."',sup_id=".$new_sup_id.",cart_id=".$cart_id.",ord_no='".$order_no."',user_id=".$memb_id.",pay_status='".$row_ins["pay_status"]."',delivery_status='".$row_ins["delivery_status"]."',ord_id='".$row_ins["ord_id"]."',user_type='".$row_ins["user_type"]."',pay_method='".$row_ins["pay_method"]."',pay_method_name='".$row_ins["pay_method_name"]."',ord_date='".$row_ins["ord_date"]."'");
		}else{
			$order_no = $ord_no;
			execute_qry("delete from ord_summary where sup_id=$old_sup_id and cart_id=$cart_id");
		}
		$fld_s_arr = array();
		$fld_s_arr["sup_id"] = $old_sup_id;
		$fld_s_arr["cart_id"] = $cart_id;
		$fld_s_arr["ord_no"] = $order_no;
		$fld_s_arr["prod_id"] = $prod_id;
		
		$sql = func_insert_qry("sup_cancelled_ord",$fld_s_arr);
		execute_qry($sql);
		//update order summary
		update_order($memb_id, $cart_id,$new_sup_id,$old_sup_id,$order_no,$prod_id,$actual_price,$cart_qty,$tax_per);
	}
}

function send_re_assign_seller_mail($new_sup_id,$cart_id,$old_sup_id,$ord_no,$prod_id){
	if(get_rst("select * from ord_summary where cart_id=$cart_id",$row_s,$rst_s)){
		$cart_id = $row_s["cart_id"];
		foreach($row_s as $key => $value) {
			$fld_arr[$key] = $value;
		}
	}
	$ord_pay_total = 0;
	
	if($cart_id <> ""){
		if(get_rst("select * from ord_details where cart_id=".$cart_id,$row)){
			foreach($row as $key => $value) {
				$fld_arr[$key] = $value;
			}
			$bill_email=$row["bill_email"];
			$bill_fname=$row["bill_name"];
			$bill_tel=$row["bill_tel"];
		}

			$body_supplier = "";
			foreach($row_s as $key => $value) {
				$fld_sum_arr[$key] = $value;
			}
			$ord_pay_total = $ord_pay_total + $row_s["ord_total"];
			$body_items = "";
			$body_items_sup = "";
			if(get_rst("select * from ord_items where cart_id=".$cart_id." and sup_id=$new_sup_id and prod_id=$prod_id",$row,$rst)){
				if(get_rst("select sup_company from sup_mast where sup_id=$new_sup_id",$rw)){
					$fld_item_arr["sup_name"] = $rw["sup_company"];
				}			
					foreach($row as $key => $value) {
						$fld_item_arr[$key] = $value;
					}				
					$fld_item_arr["prod_url"] = get_base_url()."/prod_details.php?prod_id=".$row["prod_id"];
					$fld_item_arr["item_thumb"] = show_img($row["item_thumb"]);
					if($row["tax_percent"].""<>""){
						$fld_item_arr["tax_percent"] = $row["tax_percent"]."%";
					}else{
						$fld_item_arr["tax_percent"] = "";
					}
					
					if($row["ship_amt"]==0){
						$fld_item_arr["shipping_charges"] = "Free";
					}else{
					$fld_item_arr["shipping_charges"] = $row["ship_amt"];
					}
					$fld_item_arr["total_amount"] = (floatval($row["cart_price_tax"]) * intval($row["cart_qty"]))+(floatval($row["ship_amt"]));
					$body_item_sup = push_body("ord_items_sup.txt",$fld_item_arr);
					
					$body_items_sup = $body_items_sup.$body_item_sup;

				$fld_sum_arr["item_total"] = floatval($row["cart_price"]) * intval($row["cart_qty"]);
				$fld_sum_arr["vat_value"] = $row["tax_value"] * $row["cart_qty"];
				$fld_sum_arr["ord_total"] = $fld_item_arr["total_amount"];
				if($row["ship_amt"]==0){
					$fld_sum_arr["shipping_charges1"]="Free Delivery";
				}else{
					$fld_sum_arr["shipping_charges1"]=$fld_item_arr["shipping_charges"];
				}
				$fld_sum_arr["ord_no"]=$ord_no;
				$fld_sum_arr["ord_date"]=$fld_arr["ord_date"];
				$fld_sum_arr["ord_items_sup"] = $body_items_sup;
				$body_sum_sup = push_body("ord_summary_sup.txt",$fld_sum_arr);
				$body_sum_sup = $body_sums_sup.$body_sum_sup;
			}
			$fld_arr["ord_pay_total"] = $fld_item_arr["total_amount"];
			$fld_arr["ord_summary_sup"] = $body_sum_sup;
			$body_supplier = push_body("ord_details_sup.txt",$fld_arr);
			if(get_rst("select sup_email,sup_contact_no, sup_company from sup_mast where sup_id=$new_sup_id",$row)){
				$sup_email = $row["sup_email"];
				$sup_tel = $row["sup_contact_no"];
				$sup_company = $row["sup_company"];
				send_mail($sup_email,"Company-Name - Order Confirmation (Seller): $ord_no",$body_supplier,"orders@Company-Name.com");
			}
			
			$body = "Dear Seller,A Product in Order $ord_no has been Cancelled by the admin.";
			
			get_rst("select sup_email from sup_mast where sup_id=$old_sup_id",$rw);
			send_mail($rw["sup_email"],"Company-Name - Order Cancelled (Seller): $ord_no",$body,"orders@Company-Name.com");
	}
}

function send_ord_mail(){
	$fld_arr = array();
	$fld_sum_arr = array();
	$fld_item_arr = array();
	$cart_id="";
	$body_details = "";
	$body_sums = "";
	$body_items = "";
	$bill_email = "";
	$bill_fname = "";
	$bill_tel = "";
	$ord_no = "";
	$body_supplier = "";
	$cc=" Company-Name <".$_SESSION["admin_email"].">";
	global $orders;
	if(get_rst("select * from ord_summary where ord_id=".$_SESSION["ord_id"],$row_s,$rst_s)){
		$cart_id = $row_s["cart_id"];
		foreach($row_s as $key => $value) {
			$fld_arr[$key] = $value;
		}
	}	
	
	$ord_pay_total = 0;
	
	if($cart_id <> ""){
		if(get_rst("select * from ord_details where cart_id=".$cart_id,$row)){
			foreach($row as $key => $value) {
				$fld_arr[$key] = $value;
			}
			$bill_email=$row["bill_email"];
			$bill_fname=$row["bill_name"];
			$bill_tel=$row["bill_tel"];
		}
		
		do{
			$ord_no = $row_s["ord_no"];
			$body_supplier = "";
			foreach($row_s as $key => $value) {
				$fld_sum_arr[$key] = $value;
			}
			$sup_id = $row_s["sup_id"];
			$ord_pay_total = $ord_pay_total + $row_s["ord_total"];
			$body_items = "";
			$body_items_sup = "";
			if(get_rst("select * from ord_items where cart_id=".$cart_id." and sup_id=$sup_id",$row,$rst)){
				do{				
					foreach($row as $key => $value) {
						$fld_item_arr[$key] = $value;
					}		
					$fld_item_arr["prod_url"] = get_base_url()."/prod_details.php?prod_id=".$row["prod_id"];
					$fld_item_arr["item_thumb"] = show_img($row["item_thumb"]);
					if($row["tax_percent"].""<>""){
						$fld_item_arr["tax_percent"] = $row["tax_percent"]."%";
					}else{
						$fld_item_arr["tax_percent"] = "";
					}
					get_rst("select hsn_code from prod_mast where prod_id=".$row["prod_id"], $hsn_rw);
					$fld_item_arr["hsn_code"] = $hsn_rw["hsn_code"];
					if($row["ship_amt"]==0){
						$fld_item_arr["shipping_charges"] = "Free";
					}else{
					$fld_item_arr["shipping_charges"] = $row["ship_amt"];
					}
					$fld_item_arr["total_amount"] = (floatval($row["cart_price_tax"]) * intval($row["cart_qty"]))+(floatval($row["ship_amt"]));

					$body_item = push_body("ord_items.txt",$fld_item_arr);
					$body_item_sup = push_body("ord_items_sup.txt",$fld_item_arr);
					
					$body_items = $body_items.$body_item;
					$body_items_sup = $body_items_sup.$body_item_sup;

				}while($row = mysqli_fetch_array($rst));
				if($row_s["shipping_charges"]==0){
					$fld_sum_arr["shipping_charges1"]="Free Delivery";
				}else{
					$fld_sum_arr["shipping_charges1"]=$row_s["shipping_charges"];
				}
				$fld_sum_arr["ord_no"]=$ord_no;
				$fld_sum_arr["ord_date"]=$fld_arr["ord_date"];
				$fld_sum_arr["ord_items_sup"] = $body_items_sup;
				$body_sum_sup = push_body("ord_summary_sup.txt",$fld_sum_arr);
				$body_sum_sup = $body_sums_sup.$body_sum_sup;
				
				$fld_sum_arr["ord_items"] = $body_items;
				$body_sum = push_body("ord_summary.txt",$fld_sum_arr);
				$body_sums = $body_sums.$body_sum;
			}
			
			$fld_arr["ord_pay_total"] = $row_s["ord_total"];
			$fld_arr["ord_summary_sup"] = $body_sum_sup;
			$body_supplier = push_body("ord_details_sup.txt",$fld_arr);
			
			$fld_arr["ord_summary"] = $body_sum;
			if(get_rst("select sup_email,sup_contact_no, sup_company from sup_mast where sup_id=$sup_id",$row)){
				$sup_email = $row["sup_email"];
				$sup_tel = $row["sup_contact_no"];
				$sup_company = $row["sup_company"];
				send_mail($sup_email,"Company-Name - Order Confirmation (Seller): $ord_no",$body_supplier,"orders@Company-Name.com");
				$fld_sms_arr = array();
				$fld_sms_arr["memb_fname"] = $sup_company;
				$fld_sms_arr["ord_no"] = $ord_no;
				//send sms to seler
				$sms_msg = push_body("sms_ord_confirmation.txt",$fld_sms_arr);
				send_sms($sup_tel,$sms_msg);
				
				//send sms to buyer
				$fld_sms_arr["memb_fname"] = $bill_fname;
				$sms_msg = push_body("sms_ord_confirmation.txt",$fld_sms_arr);
				send_sms($bill_tel,$sms_msg);
			}
		}while($row_s = mysqli_fetch_assoc($rst_s));

		$fld_arr["ord_items"] = $body_items;
		$fld_arr["ord_summary"] = $body_sums;
		$fld_arr["ord_pay_total"] = $ord_pay_total;
		
		$body_details = push_body("ord_details.txt",$fld_arr);
		send_mail($bill_email,"Company-Name - Order Confirmation: $ord_no",$body_details,"orders@Company-Name.com");
		send_mail($orders,"Company-Name - Order Confirmation: $ord_no",$body_details,"info@Company-Name.com");
	}
}

/* Generate sku code from the category, sub category and brand
 * information provided during creation of product.
 */
function generate_sku($category, $sub_category, $brand){
	$sku_code = "A";
	//get category code from category table
	$qry = "select cat_code from cat_master where cat_name='".$category."'";
	$row = "";
	get_rst($qry, $row);
	if($row <> ""){
		$sku_code.= str_pad($row["cat_code"], 2, '0', STR_PAD_LEFT);
	}else{
		$sku_code.="00";
	}
	$cat_code = $row["cat_code"];
	
	//get sub category code from sub-category table
	$row="";
    $qry = "select sub_cat_code from sub_cat_master where sub_cat_name='".$sub_category."' AND cat_code='".$cat_code."'";
	get_rst($qry, $row);
	if($row <> ""){
		$sku_code.= str_pad($row["sub_cat_code"], 2, '0', STR_PAD_LEFT);
	}else{
		$sku_code.="00";
	}
	
	//get sub brand code from brand table
	$row="";
    $qry = "select brand_code from brand_master where brand_name='".$brand."'";
	get_rst($qry, $row);
	if($row <> ""){
		$sku_code.=$row["brand_code"];
	}else{
		$sku_code.="XX";
	}
	
	//get serial number for the above code combination
	$row="";
    $qry = "select count(0)+1 as serial_code from prod_mast where prod_stockno LIKE '$sku_code%' AND prod_stockno is NOT NULL";
	if(get_rst($qry, $row)){
		$sku_code.= str_pad($row["serial_code"], 5, '0', STR_PAD_LEFT);
	}else{
		$sku_code.="00001";
	}
	return $sku_code;
}

function get_category_name($level_parent, $level_name)
{
	$category_name = "";
	$sub_category_name = "";
	$qry="select level_parent from levels where level_id=".$level_parent." AND level_name='".$level_name."'" ;
	if(get_rst($qry,$row)){
		if($row["level_parent"] == 0){
			$category_name = $level_name;
		}else{
			$sub_category_name = $level_name;
			$level_parent = $row["level_parent"];
			$qry="";
			$row="";
			$qry="select level_name from levels where level_id=0".$level_parent;
			if(get_rst($qry, $row)){
				$category_name=$row["level_name"];
			}
		}
	}	
	return array($category_name,$sub_category_name);
}

function get_brand_name($brand_id){
	$brand_name = "";
	$qry= "select brand_name from brand_mast where brand_id=0".$brand_id;
	if(get_rst($qry,$row)){
		$brand_name=$row["brand_name"];
	}
	return $brand_name;
}


function get_client_IP(){
	if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} else {
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	
	return $ip;
}

function calc_vol_wt($height=0, $width=0, $depth=0){
	return ($height * $width * $depth)/5000; 
}

function pay_by_cc(){
	// Merchant key here as provided by Payu
	$MERCHANT_KEY = "somestring";	//LIVE
	
	// Merchant Salt as provided by Payu
	$SALT = "somestring";			//LIVE

	// End point - change to https://secure.payu.in for LIVE mode
	//$PAYU_BASE_URL = "https://test.payu.in";
	$PAYU_BASE_URL = "https://secure.payu.in";

	$action = '';
	$site_base_url = get_base_url();
	
	$posted = array();
	if(isset($_POST)) {
	  foreach($_POST as $key => $value) {    
		$posted[$key] = $value; 
		
	  }
	}

	$formError = 0;
	$txnid="";
	if(!isset($posted['txnid'])) {
	  // Generate random transaction id
	  $txnid = substr(hash('sha256', mt_rand() . microtime()), 0, 20);
	} else {
	  $txnid = $posted['txnid'];
	}
	$hash = '';
	// Hash Sequence
	$hashSequence = "key|txnid|amount|productinfo|firstname|email|udf1|udf2|udf3|udf4|udf5|udf6|udf7|udf8|udf9|udf10";
	if(empty($posted['hash']) && sizeof($posted) > 0) {
	  if(
			  empty($posted['key'])
			  || empty($posted['txnid'])
			  || empty($posted['amount'])
			  || empty($posted['firstname'])
			  || empty($posted['email'])
			  || empty($posted['phone'])
			  || empty($posted['productinfo'])
			  || empty($posted['surl'])
			  || empty($posted['furl'])
			  || empty($posted['service_provider'])
	  ) {
		$formError = 1;
	  } else {
		$hashVarsSeq = explode('|', $hashSequence);
		$hash_string = '';	
		foreach($hashVarsSeq as $hash_var) {
		  $hash_string .= isset($posted[$hash_var]) ? $posted[$hash_var] : '';
		  $hash_string .= '|';
		}

		$hash_string .= $SALT;

		$hash = hash('sha512', $hash_string);
		$action = $PAYU_BASE_URL . '/_payment';
	  }
	} elseif(!empty($posted['hash'])) {
	  $hash = $posted['hash'];
	  $action = $PAYU_BASE_URL . '/_payment';
	}
	?>

	<form action="<?php echo $action; ?>" method="post" name="payuForm">
		  <input type="hidden" name="key" value="<?php echo $MERCHANT_KEY ?>" />
		  <input type="hidden" name="hash" value="<?php echo $hash ?>"/>
		  <input type="hidden" name="txnid" value="<?php echo $txnid ?>" />

		  <?php
		  $all_ok = "";
		  $cart_id = "";
		  $sql_where = " where session_id='".session_id()."' group by cart_id";
		  if(get_rst("select cart_id, sum(ord_total) as total_amt from cart_summary $sql_where",$row_s)){
			
			$cart_id = $row_s["cart_id"];
		  }else{ $all_ok = "cart_summary"; }
		  
		  if(get_rst("select * from cart_details where cart_id=$cart_id",$row_d)){
			
		  }else{ $all_ok = "cart_details"; }
		  $coupon_amt = 0;
		  if(get_rst("select coupon_id, coupon_amt from ord_coupon where cart_id=$cart_id", $row_c)){
				$coupon_amt = $row_c["coupon_amt"];
     	  }		
		  $prod_info = "";
		  if(get_rst("select * from cart_items where cart_id=$cart_id and item_wish<>1",$row_p,$rst_p)){
			do{
				$prod_info = $prod_info.$row_p["item_stock_no"]." - ".$row_p["item_name"]." (Rs.".$row_p["cart_price_tax"]."\n\r";
			}while($row=mysqli_fetch_assoc($rst_p));
		  }else{ $all_ok = "cart_items"; }		  
		  
		  if($all_ok == ""){
			?>
			  <input type="hidden" name="amount" value="<?=$row_s["total_amt"] - $coupon_amt?>" />
			  <input type="hidden" name="firstname" id="firstname" value="<?=$row_d["bill_name"]?>" />
				<input type="hidden" name="email" id="email" value="<?=$row_d["bill_email"]?>" />
				<input type="hidden" name="phone" value="<?=$row_d["bill_tel"]?>" />
				<input type="hidden" name="productinfo" value="<?=$prod_info?>">

				<input type="hidden" name="surl" value="<?=$site_base_url?>/payu_success.php" size="64" />
				<input type="hidden" name="furl" value="<?=$site_base_url?>/payu_failure.php" size="64" />
				<input type="hidden" name="service_provider" value="payu_paisa" size="64" />

				<!--Optional-->
				<input type="hidden" name="lastname" id="lastname" value="-" />
				<input type="hidden" name="curl" value="<?=$site_base_url?>/ord_summary.php" />
				<input type="hidden" name="address1" value="<?=$row_d["bill_add1"]?>" />
				<input type="hidden" name="address2" value="<?=$row_d["bill_add2"]?>" />
				<input type="hidden" name="city" value="<?=$row_d["bill_city"]?>" />
				<input type="hidden" name="state" value="<?=$row_d["bill_state"]?>" />
				<input type="hidden" name="country" value="<?=$row_d["bill_country"]?>" />
				<input type="hidden" name="zipcode" value="<?=$row_d["bill_postcode"]?>" />
				<input type="hidden" name="udf1" value="<?=$cart_id?>" />
				<input type="hidden" name="udf2" value="" />
				<input type="hidden" name="udf3" value="" />
				<input type="hidden" name="udf4" value="" />
				<input type="hidden" name="udf5" value="" />
				<input type="hidden" name="pg" value="" />
				  <?php if(!$hash) { ?>
					<input type="submit" value="Click here to Continue..." />
				  <?php } ?>
			<?php }?>
		</form>
	  <script>

		var payuForm = document.forms.payuForm;
		  payuForm.submit();
	  </script>

<?php }

function pay_by_payu_credit(){
	// Merchant key here as provided by Payu
	$MERCHANT_KEY = "something";	//LIVE
	
	// Merchant Salt as provided by Payu
	$SALT = "somestring";			//LIVE

	// End point - change to https://secure.payu.in for LIVE mode
	$PAYU_BASE_URL = "https://secure.payu.in";

	$action = '';
	$site_base_url = get_base_url();
	
	$posted = array();
	if(isset($_POST)) {
	  foreach($_POST as $key => $value) {    
		$posted[$key] = $value; 
		
	  }
	}
	$formError = 0;
	$txnid="";
	if(!isset($posted['txnid'])) {
	  // Generate random transaction id
	  $txnid = substr(hash('sha256', mt_rand() . microtime()), 0, 20);
	  $posted['txnid'] = $txnid;
	} else {
	  $txnid = $posted['txnid'];
	}
	$hash = '';
	// Hash Sequence
	$posted['service_provider'] = "payu_paisa";
	$posted['surl'] = $url_root."payu_success.php";
	$posted['furl'] = $url_root."payu_failure.php";
	$posted['key'] = $MERCHANT_KEY;
	$posted['productinfo'] = "Company-Name Inndustrial Products";

	$hashSequence = "key|txnid|amount|productinfo|firstname|email|udf1|udf2|udf3|udf4|udf5|udf6|udf7|udf8|udf9|udf10";
	if(empty($posted['hash']) && sizeof($posted) > 0) {
	  if(
			  empty($posted['key'])
			  || empty($posted['txnid'])
			  || empty($posted['amount'])
			  || empty($posted['firstname'])
			  || empty($posted['email'])
			  || empty($posted['phone'])
			  || empty($posted['productinfo'])
			  || empty($posted['surl'])
			  || empty($posted['furl'])
			  || empty($posted['service_provider'])
	  ) {
		$formError = 1;
	  } else {
		$hashVarsSeq = explode('|', $hashSequence);
		$hash_string = '';	
		foreach($hashVarsSeq as $hash_var) {
		  $hash_string .= isset($posted[$hash_var]) ? $posted[$hash_var] : '';
		  $hash_string .= '|';
		}

		$hash_string .= $SALT;

		$hash = hash('sha512', $hash_string);				//strtolower(hash('sha512', $hash_string));
		$action = $PAYU_BASE_URL . '/_payment';
	  }
	} elseif(!empty($posted['hash'])) {
	  $hash = $posted['hash'];
	  $action = $PAYU_BASE_URL . '/_payment';
	}
	?>

	<form action="<?php echo $action; ?>" method="post" name="payuForm">
		  <input type="hidden" name="key" value="<?php echo $MERCHANT_KEY ?>" />
		  <input type="hidden" name="hash" value="<?php echo $hash ?>"/>
		  <input type="hidden" name="txnid" value="<?php echo $txnid ?>" />

		  <?php
		  $all_ok = "";
		  $cart_id = $_SESSION["cart_id"];
		  $sql_where = " where cart_id=$cart_id group by cart_id";
		  if(get_rst("select sum(ord_total) as total_amt from ord_summary $sql_where",$row_s)){
			
			//$cart_id = $row_s["cart_id"];
		  }else{ $all_ok = "ord_summary"; }
		  
		  if(get_rst("select * from ord_details where cart_id=$cart_id",$row_d)){
			
		  }else{ $all_ok = "ord_details"; }
		  $coupon_amt = 0;
		  if(get_rst("select coupon_id, coupon_amt from ord_coupon where cart_id=$cart_id", $row_c)){
				$coupon_amt = $row_c["coupon_amt"];
     	  }		
		  $prod_info = "";
		  if(get_rst("select * from ord_items where cart_id=$cart_id and item_wish<>1",$row_p,$rst_p)){
			do{
				$prod_info = $prod_info.$row_p["item_stock_no"]." - ".$row_p["item_name"]." (Rs.".$row_p["cart_price_tax"]."\n\r";
			}while($row=mysqli_fetch_assoc($rst_p));
		  }else{ $all_ok = "cart_items"; }		  
		  
		  if($all_ok == ""){
			?>
			  <input type="hidden" name="amount" value="<?=$row_s["total_amt"] - $coupon_amt?>" />
			  <input type="hidden" name="firstname" id="firstname" value="<?=$row_d["bill_name"]?>" />
				<input type="hidden" name="email" id="email" value="<?=$row_d["bill_email"]?>" />
				<input type="hidden" name="phone" value="<?=$row_d["bill_tel"]?>" />
				<input type="hidden" name="productinfo" value="<?=$prod_info?>">

				<input type="hidden" name="surl" value="<?=$site_base_url?>/payu_success.php" size="64" />
				<input type="hidden" name="furl" value="<?=$site_base_url?>/payu_failure.php" size="64" />
				<input type="hidden" name="service_provider" value="payu_paisa" size="64" />

				<!--Optional-->
				<input type="hidden" name="lastname" id="lastname" value="-" />
				<input type="hidden" name="curl" value="<?=$site_base_url?>/ord_summary.php" />
				<input type="hidden" name="address1" value="<?=$row_d["bill_add1"]?>" />
				<input type="hidden" name="address2" value="<?=$row_d["bill_add2"]?>" />
				<input type="hidden" name="city" value="<?=$row_d["bill_city"]?>" />
				<input type="hidden" name="state" value="<?=$row_d["bill_state"]?>" />
				<input type="hidden" name="country" value="<?=$row_d["bill_country"]?>" />
				<input type="hidden" name="zipcode" value="<?=$row_d["bill_postcode"]?>" />
				<input type="hidden" name="udf1" value="<?=$cart_id?>" />
				<input type="hidden" name="udf2" value="" />
				<input type="hidden" name="udf3" value="" />
				<input type="hidden" name="udf4" value="" />
				<input type="hidden" name="udf5" value="" />
				<input type="hidden" name="pg" value="" />
				  <?php if(!$hash) { ?>
					<input type="submit" value="Click here to Continue..." />
				  <?php } ?>
			<?php }?>
		</form>
	  <script>

		var payuForm = document.forms.payuForm;
		  payuForm.submit();

	  </script>

<?php }

//Order tracking request to logistics service provider
function logistics_pull_req(){
	$msg="";
	$url="https://track.delhivery.com/api/packages/xml/?token=eb0a01d91defdbde966d72f828983dc61c6d93b3&waybill=";

	// Get the waybill number of the orders if the status is dispatched
	$qry = "select way_billl_no from ord_summary where delivery_status='Dispatched' and way_billl_no is not NULL";
	if(get_rst($qry, $row, $rst)){
		$waybill_params=""; // parameter list of waybill numbers of all orders that needs to be tracked
		do{	
			$waybill_params.= $row["way_billl_no"].",";
		}while($row = mysqli_fetch_assoc($rst));	
	}
	$waybill_params = rtrim($waybill_params, ",");
	
	$response = file_get_contents($url.$waybill_params);
	$xml_p=simplexml_load_string($response);
	//return $response; //returns xml formatted response
	foreach($xml_p->Shipment as $fld => $val){
		$way_billl_no = $val->AWB;
		$logistics_status=$val->Status[0]->Status;
		write_log(INFO, "L_P: way_billl_no=$way_billl_no, logistics_status=$logistics_status");	
		
		$delivery_status="";
		$sql_free = "";
		if($logistics_status=="DTO" or $logistics_status=="Delivered"){
			$delivery_status = "delivery_status='Delivered',";
			$sql_free = "update ord_waybill set in_use=0 where way_bill_no='$way_billl_no'";
			execute_qry($sql_free);
		}
		$sql = "update ord_summary set $delivery_status logistics_status='$logistics_status' where way_billl_no='$way_billl_no'";
		execute_qry($sql);
		write_log(INFO, "L_P: way_billl_no=$way_billl_no updated");
	}	
}

function pay_by_ccZ(){

  $all_ok = "";
  $cart_id = "";
  $sql_where = " where session_id='".session_id()."' group by cart_id";
  if(get_rst("select cart_id, sum(ord_total) as total_amt from cart_summary $sql_where",$row_s)){
	
	$cart_id = $row_s["cart_id"];
  }else{ $all_ok = "cart_summary"; }
  
  if(get_rst("select * from cart_details where cart_id=$cart_id",$row_d)){
	
  }else{ $all_ok = "cart_details"; }
  $coupon_amt = 0;
  if(get_rst("select coupon_id, coupon_amt from ord_coupon where cart_id=$cart_id", $row_c)){
		$coupon_amt = $row_c["coupon_amt"];
  }	  
  $site_base_url = get_base_url();
  
  if($all_ok == ""){
	?>
	<form name="frm_zak" action="payment.php" method="post">
	<input type="hidden" name="merchantIdentifier" value="0e2537d4ba5e4367a92c0467f5e6756a" />
	<input type="hidden" id="orderId" name="orderId" value="<?=$cart_id?>"/>
	<input type="hidden" name="returnUrl" value="<?=$site_base_url?>/pg_zak_response.php"/>
	<input type="hidden" name="buyerEmail" value="<?=$row_d["bill_email"]?>"  />
	<input type="hidden" name="buyerFirstName" value="<?=$row_d["bill_name"]?>" />
	<input type="hidden" name="buyerLastName" value=" - " />
	<input type="hidden" name="buyerAddress" value="<?=$row_d["bill_add1"]."\r\n".$row_d["bill_add2"]?>" />
	<input type="hidden" name="buyerCity" value="<?=$row_d["bill_city"]?>" />
	<input type="hidden" name="buyerState" value="<?=$row_d["bill_state"]?>" />
	<input type="hidden" name="buyerCountry" value="<?=$row_d["bill_country"]?>" />
	<input type="hidden" name="buyerPincode" value="<?=$row_d["bill_postcode"]?>" />
	<input type="hidden" name="buyerPhoneNumber" value="<?=$row_d["bill_tel"]?>" />
	<input type="hidden" name="txnType" value="1" />
	<input type="hidden" name="zpPayOption" value="1" />
	<input type="hidden" name="mode" value="1" />
	<input type="hidden" name="currency" value="INR" />
	<input type="hidden" name="amount" value="<?=($row_s["total_amt"] - $coupon_amt) * 100?>" />
	<input type="hidden" name="merchantIpAddress" value="<?=get_client_IP()?>" />
	<input type="hidden" name="purpose" value="1" />
	
	<?php
	  $prod_info = "";
	  if(get_rst("select * from cart_items where cart_id=$cart_id and item_wish<>1",$row_p,$rst_p)){
		$item_no=1;
		do{
			$item_info = $row_p["item_stock_no"]." - ".$row_p["item_name"]." (Rs.".$row_p["cart_price_tax"].")";
			$prod_info = $prod_info.$item_info." | \n\r";
			?>
			<input type="hidden" name="product<?=$item_no?>Description" value="<?=$item_info?>" />
			<?php
			$item_no++;
			}while($row_p=mysqli_fetch_assoc($rst_p));
	  }else{ $all_ok = "cart_items"; }
	 ?>
	<input type="hidden" name="productDescription" value="<?=$prod_info?>"/>

	
	<input type="hidden" name="shipToAddress" value="<?=$row_d["ship_add1"]."\r\n".$row_d["ship_add2"]?>"/>
	<input type="hidden" name="shipToCity" value="<?=$row_d["ship_city"]?>" />
	<input type="hidden" name="shipToState" value="<?=$row_d["ship_state"]?>" />
	<input type="hidden" name="shipToCountry" value="<?=$row_d["ship_country"]?>" />
	<input type="hidden" name="shipToPincode" value="<?=$row_d["ship_postcode"]?>" />
	<input type="hidden" name="shipToPhoneNumber" value="<?=$row_d["ship_tel"]?>" />
	<input type="hidden" name="shipToFirstname" value="<?=$row_d["ship_name"]?>" />
	<input type="hidden" name="shipToLastname" value=" - " />
	<input type="hidden" name="txnDate" id="txnDate" value="<?=date('Y-m-d')?>" />
	<input type="submit" value="Click here to continue...">
	</form>	

	<script>
		document.frm_zak.submit();
	</script>
	<?php
	}
}

function send_sms($tele,$sms_msg){
	//authentication key
	global $enable_sms;
	if($enable_sms == false){ return 200;}
	$authKey = "97350AP8uE8No9Gv564866b9";

	//Multiple mobiles numbers separated by comma
	$mobileNumber = "91".$tele;
	//Sender ID,While using route4 sender id should be 6 characters long.
	$senderId = "ALFMRT";

	//Your message to send, Add URL encoding here.
	$message = urlencode($sms_msg);

	//Define route 
	$route = "4";
	//Prepare you post parameters
	$postData = array(
	'authkey' => $authKey,
	'mobiles' => $mobileNumber,
	'message' => $message,
	'sender' => $senderId,
	'route' => $route
	);

	//API URL
	$url="http://api.msg91.com/sendhttp.php";

	// init the resource
	$ch = curl_init();
	curl_setopt_array($ch, array(
	CURLOPT_URL => $url,
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_POST => true,
	CURLOPT_POSTFIELDS => $postData
	//,CURLOPT_FOLLOWLOCATION => true
	));


	//Ignore SSL certificate verification
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

	$output = curl_exec($ch);

	if(curl_errno($ch))
	{
	echo 'error:' . curl_error($ch);
	}

	curl_close($ch);
	return $output;
}

// This function returns Longitude & Latitude from zip code.
function getLnt($zip){
	$url = "http://maps.googleapis.com/maps/api/geocode/json?address=
	".urlencode($zip)."&sensor=false";
	$result_string = file_get_contents($url);
	$result = json_decode($result_string, true);
	$result1[]=$result['results'][0];
	$result2[]=$result1[0]['geometry'];
	$result3[]=$result2[0]['location'];
	return $result3[0];
}

function getDistance($zip1, $zip2){
	$first_lat = getLnt($zip1);
	$next_lat = getLnt($zip2);
	$lat1 = $first_lat['lat'];
	$lon1 = $first_lat['lng'];
	$lat2 = $next_lat['lat'];
	$lon2 = $next_lat['lng']; 
	$theta=$lon1-$lon2;
	$dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +
	cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
	cos(deg2rad($theta));
	$dist = acos($dist);
	$dist = rad2deg($dist);
	$miles = $dist * 60 * 1.1515;
	return round($miles * 1.609344, 0);
}

//Verify coupon code for buyer
function verify_offer_coupon($memb_id, $cart_id, $coupon_code, $ord_amt){
	$cur_date = Date("m/d/Y");
	$response = "";
	$qry = "select coupon_id, disc_per, valid_from, valid_till, min_ord_value, memb_id from offer_coupon where coupon_code='$coupon_code' and active=1";
	if(get_rst($qry, $row)){
		$coupon_id = $row["coupon_id"];
		$min_ord_value = $row["min_ord_value"];
		$disc_per = $row["disc_per"];
		$valid_from = $row["valid_from"];
		$valid_till = $row["valid_till"];
		if($memb_id <> $row["memb_id"] && $row["memb_id"]<>"")
		{
			$response = "Invalid coupon!";
			return $response;
		}
		if(strtotime($cur_date) < strtotime($valid_from) || strtotime($cur_date) > strtotime($valid_till)){
			$response = "Invalid coupon! Offer validity expired";
			return $response;
		}
		
		if($ord_amt < $min_ord_value){
			$response = "Invalid coupon! Offer is valid on a minimum order of Rs. $min_ord_value (excluding tax and shipping charges)";
			return $response;
		}
		
		$qry = ""."select * from memb_coupon where coupon_id=$coupon_id and memb_id=$memb_id";
		if(get_rst($qry, $row)){
			$response = "Invalid coupon! It is a one time offer and it has already been applied.";
			return $response;
		}	
		
		$qry = ""."select * from ord_coupon where cart_id=$cart_id";
		if(get_rst($qry, $row)){
			$response = "Invalid coupon! Only one coupon can be applied per order.";
			return $response;
		}
		return $disc_per;
	}
	return $response;
}

function sendnotification($message)
{
		$curl = curl_init();
		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://fcm.googleapis.com/fcm/send",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "POST",
		  CURLOPT_POSTFIELDS => $message,
		  CURLOPT_HTTPHEADER => array(
			"authorization: key=AIzaSyDUwPIUb32S2aT0cUKoDIpY1TfPiCCSBI8",
			"cache-control: no-cache",
			"content-type: application/json",
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);
		curl_close($curl);
}

function sendcustomernotification_order($cart_id,$memb_id)
{
	$flag=0;
	if(get_rst("select * from notification where user_id=0$memb_id and login_status=1 and device='android' and type='user'",$row)){
		$message['title']="Order Detail";
		$message['notification']=array(
			'title'=>"Order Detail",
			'text'=>"Order placed Successfully.",
			'click_action'=>'OrderListItemActivity'
			);
			
		$message['data']=array(
				'activity'=>'OrderListItemActivity',
				'id_offer'=>$cart_id,		
		);

		$registration_ids=$row['device_token'];
		$message['registration_ids'][]=$registration_ids;
		sendnotification(json_encode($message));
	}
}

function sendsellernotification_order($cart_id)
{
	global $con;
	$sql="select * from ord_summary where cart_id=0$cart_id";
	$rs_find = mysqli_query($con,$sql);
	$flag=0;
	if($rs_find){
		
		$flag=1;
		while($row = mysqli_fetch_assoc($rs_find)){
			$seller_id=$row['sup_id'];
			
			if(get_rst("select * from notification where user_id=0$seller_id and login_status=1 and device='android' and type='seller'",$row)){
				$message['title']="Order Detail";
				$message['notification']=array(
					'title'=>"Order Detail",
					'text'=>"New Order arrived.",
					'click_action'=>'Seller_ViewOrders'
					);
					
				$message['data']=array(
						'activity'=>'Seller_ViewOrders',
				);

				$registration_ids=$row['device_token'];
				$message['registration_ids'][]=$registration_ids;
				sendnotification(json_encode($message));
			}
		  
		}
	}
} 

function sendnotifiction_deliverystatus($cart_id,$memb_id,$delivery_status,$ord_no)
{
		$flag=0;
		$text = "The status of your Order with Order no. $ord_no is updated. The new status is $delivery_status.";
		$message['title']="Delivery Status";
		$message['notification']=array(
			'title'=>"Delivery Status",
			'text'=>$text,
			//'text'=>$flag,
			'click_action'=>'OrderListItemActivity'
			);
			
		$message['data']=array(
				'activity'=>'OrderListItemActivity',
				'id_offer'=>$cart_id,	
		);

		$registration_ids=$row['device_token'];
		$message['registration_ids'][]=$registration_ids;
		sendnotification(json_encode($message));
	}
}

function sendnotifiction_seller_trackpayment($sup_id,$ord_no)
{
	$flag=0;
	if(get_rst("select * from notification where user_id=0$sup_id and login_status=1 and device='android' and type='seller'",$row)){
		$text = "Payment Status for the order with Order no. $ord_no is changed.";
		$message['title']="Payment Status";
		$message['notification']=array(
			'title'=>"Payment Status",
			'text'=>$text,
			//'text'=>$flag,
			'click_action'=>'Seller_TrackPayment'
			);
			
		$message['data']=array(
				'activity'=>'Seller_TrackPayment',
		);

		$registration_ids=$row['device_token'];
		$message['registration_ids'][]=$registration_ids;
		sendnotification(json_encode($message));
	}
}


function sendnotifiction_productstatus($sup_id,$sku)
{
	//$sql="select * from notification where user_id=0$memb_id and login_status=1 and device='android'";
	$flag=0;
	if(get_rst("select * from notification where user_id=0$sup_id and login_status=1 and device='android' and type='seller'",$row)){
		$text = "The status of your Product with SKU- $sku has been updated by the system.";
		$message['title']="Product Status";
		$message['notification']=array(
			'title'=>"Product Status",
			'text'=>$text,
			//'text'=>$flag,
			'click_action'=>'SellerProductList'
			);
			
		$message['data']=array(
				'activity'=>'SellerProductList',
		);

		$registration_ids=$row['device_token'];
		$message['registration_ids'][]=$registration_ids;
		sendnotification(json_encode($message));
	}
}

// Get country code from ip address
function iptocountry($ip)
{
  $numbers = preg_split( "/\./", $ip);    
  include("ip_files/".$numbers[0].".php");
  $code=($numbers[0] * 16777216) + ($numbers[1] * 65536) + ($numbers[2] * 256) + ($numbers[3]);    
  foreach($ranges as $key => $value)
  {
    if($key<=$code)
    {
      if($ranges[$key][0]>=$code)
      {
        $country=$ranges[$key][1];break;
      }
    }
  }
  if ($country=="")
  {
    $country="unknown";
  }
  return $country;
}
?>

<script>
	function js_page(v_page){
		document.getElementById("page").value=v_page;
		document.frm_list.submit();
	}
</script>
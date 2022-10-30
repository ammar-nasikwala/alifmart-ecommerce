<?
require_once("inc_admin_header.php");

$user_img="";

$img_path = "../images/users/";
$id = func_read_qs("id");
if($id<>""){
	$qry = "select * from user_mast where user_id=".$id;
	if(get_rst($qry, $row)){
		//$row = mysqli_fetch_assoc($rst);
			
		$user_name = $row["user_name"];
		$user_email = $row["user_email"];
		$user_pwd = $row["user_pwd"];
		$user_type = $row["user_type"];
		$user_status = $row["user_status"];
		
	}
}

if(isset($_POST["submit"])){
	$msg = "1";
	$email = func_read_qs("user_email");
	if(get_rst("select user_name from user_mast where user_email='".$email."'", $r) && $id==""){
		$msg="The user with this email has already been registered. Please use another email id.";
	}
	if($msg=="1"){
		$fld_arr = array();
		
		$fld_arr["user_name"] = func_read_qs("user_name");
		$fld_arr["user_email"] = func_read_qs("user_email");
		$fld_arr["user_pwd"] = func_read_qs("user_pwd");
		$fld_arr["user_type"] = func_read_qs("user_type");
		$fld_arr["user_status"] = func_read_qs("user_status");
		
		if($id==""){
				$qry = func_insert_qry("user_mast",$fld_arr);
		}else{
			$qry = func_update_qry("user_mast",$fld_arr," where user_id=$id");
		}
		$mail = array();
		$mail["user_name"]=$_POST["user_name"];
		$mail["user_email"]=$_POST["user_email"];
		$mail["user_pwd"]=$_POST["user_pwd"];
		$mail["user_type"]=$_POST["user_type"];
		
		if($mail["user_type"]=="AM")
		{
			$mail["user_type"]="Account Manager";
		}else if($mail["user_type"]=="FM")
		{
			$mail["user_type"]="Finance Settlement Manager";
		}
		else
		{
			$mail["user_type"]="Logistics Partner";
		}
		$mail_body = push_body("registeration_user.txt",$mail);
		$from = "noreply@Company-Name.com";		
		if(xsend_mail($mail["user_email"],"Company-Name - Admin account confirmation",$mail_body,$from )){
			$incomp_msg=" Mail to admin account has been successfully sent.";	
		}else{ 
				$incomp_msg="There was a problem sending email."; 
		}
		 
		if(!mysqli_query($con, $qry)){
			echo("Problem updating database... $qry");
		}else{
			?>
			<script>
				alert("Record saved successfully.");
				window.location.href="manage_users.php";
			</script>
			<?
			die("");
		}
	
	}else{
		?>
		<script>
			alert("<?=$msg?>");
			//window.location.href="manage_users.php";
		</script>
		<?
	}
}

$act = func_read_qs("act");
if($act=="d"){
	if(!mysqli_query($con, "delete from user_mast where user_id=".func_read_qs("id"))){
		echo("Problem updating database... $qry");
	}else{
		?>
		<script>
			alert("Record Deleted successfully.");
			window.location.href="manage_users.php";
		</script>
		<?
		die("");
	}	
}

?>

<script>


	function js_delete(){
		if(confirm("Are you sure you want to delete this record?")){		
			document.frm.act.value="d";
		}else{
			return false;
			
		}
	}

	function Submit_form(){
		if(chkForm(document.frm)==false)
			return false;
		else
			document.frm.submit();
	}
	
</script>

<style>

.div_tree{
  position:absolute;
  top:0px;
  left:0px;
  width:100%;
  height:100%;
  background:rgba(200,200,255,0.9);
  
}

.div_inner
{
    position:relative; 
    top:100px;
    height:500px;
    width:800px;
    border: solid 1px #000000;
    background-color:#FFFFFF;
	xoverflow: scroll;
}

.div_inner_tree{
	overflow: scroll;
	background-color:#FFFFFF;
	border:none;
	padding:10px;
}
	
</style>


<?
if($id){
	$page_head = "Edit User Details";
}else{
	$page_head = "Create New User";
}
?>

<h2><?=$page_head?></h2>

<form name="frm" id="frm" method="post" enctype="multipart/form-data">
	<input type="hidden" id="id" name="id" value="<?=func_var($id)?>">
	<input type="hidden" id="act" name="act" value="">
	
	<table border="1" class="table_form">

		<tr>
		<td>User Type</td>
		<td>
		<select name="user_type" id="100" title="User type">
			<?func_option("--Select--","",func_var($user_type))?>
			<?func_option("AM (Account Manager)","AM",func_var($user_type))?>
			<?func_option("FM (Finance Settlement Manager)","FM",func_var($user_type))?>
			<?func_option("LP (Logistics Partner)","LP",func_var($user_type))?>
		</select>*
		</td>
		</tr>
		
		<tr>
		<td>User Full Name</td>
		<td><input type="text" size="71" maxlength="120" id="100" title="Full Name" name="user_name" value="<?=func_var($user_name)?>">*</td>
		</tr>

		<tr>
		<td>Email Address</td>
		<td><input type="text" size="71" maxlength="100" id="120" title="Email Address" name="user_email" value="<?=func_var($user_email)?>">*</td>
		</tr>

		<tr>
		<td>Password</td>
		<td><input type="password" size="71" maxlength="20" id="100" title="Password" name="user_pwd" value="<?=func_var($user_pwd)?>">*</td>
		</tr>
		
		<tr>
		<td>Status</td>
		<td>
		<select name="user_status">
			<?func_option("Active","1",func_var($user_status))?>
			<?func_option("Inactive","0",func_var($user_status))?>
		</select>
		</td>
		</tr>
			
		<tr>
		<th colspan="2" id="centered">
		<input type="submit" class="btn btn-warning" value="Save" name="submit" onClick="javascript:return Submit_form();">
		<?if($id<>""){?>
			&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="submit" class="btn btn-warning" value="Delete" name="btn_delete" onclick="javascript: return js_delete();">
		<?}?>
		
		</td>
		</tr>
		
	</table>
</form>

<?
require_once("inc_admin_footer.php");

?>

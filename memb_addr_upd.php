<?php
session_start();
$msg="";
require("inc_init.php");
?>

<?php
	$msg = "";
	$memb_id = "";
	$ext_addr_name = "";	
	$ext_addr = "";
	$ext_addr_default = "";
	$ext_addr_state = "";	
	$ext_addr_city = "";
	$ext_addr_pin = "";
	$ext_addr_contact = "";
	$id = "";	
	$action="";
	$action=func_read_qs("action");
	
	if(isset($_GET["id"])){
		$id = $_GET["id"];
		if(isset($_GET["dl"])){
				$memb_id = $_SESSION["memb_id"];
				get_rst("Select count(addr_id) as total from memb_ext_addr where memb_id=$memb_id",$row_c);
				$total=$row_c["total"];
				if($total == 1){
						$msg = "You can not delete the default address";	
				}
				else{
					$qry = "delete from memb_ext_addr where addr_id=$id ";
					$result=mysqli_query($con, $qry);
					if($result){
						$msg = "Record deleted successfully";
					}
				}
		}
	}
	if(isset($_SESSION["memb_id"]) && $_SESSION["memb_id"] <> ""){
		$memb_id = $_SESSION["memb_id"];
	}else{
		$login_msg = "Please sign-in if you are a member or register to continue.";
	}
	if(isset($_POST["act"])){		
		$ext_addr_name = $_POST["ext_addr_name"];	
		$ext_addr1 = $_POST["ext_addr1"];
		$ext_addr2 = $_POST["ext_addr2"];
		$ext_addr_state = $_POST["ext_addr_state"];	
		$ext_addr_city = $_POST["ext_addr_city"];
		$ext_addr_pin = $_POST["ext_addr_pin"];
		$ext_addr_contact = $_POST["ext_addr_contact"];
		
		
		$fld_arr = array();
		$fld_arr["memb_id"] = $memb_id;
		$fld_arr["ext_addr_name"] = $ext_addr_name;
		$fld_arr["ext_addr1"] = $ext_addr1;
		$fld_arr["ext_addr2"] = $ext_addr2;
		$fld_arr["ext_addr_state"] = $ext_addr_state;
		$fld_arr["ext_addr_city"] = $ext_addr_city;
		$fld_arr["ext_addr_pin"] = $ext_addr_pin;
		$fld_arr["ext_addr_contact"] = $ext_addr_contact;
		
		$qry = "";
		if(isset($_POST["ext_addr_default"])){
			execute_qry("update memb_ext_addr set ext_addr_default=0 where memb_id=$memb_id");
			$fld_arr["ext_addr_default"] = 1;
		}else{
			$fld_arr["ext_addr_default"] = 0;
		}
		
		if(isset($_GET["id"]))
		{
			if($action == ""){
				$qry = func_update_qry("memb_ext_addr",$fld_arr," where addr_id=".$id);
		}else{
				$qry = func_insert_qry("memb_ext_addr",$fld_arr);
		}
		}
		else{	
				$qry = func_insert_qry("memb_ext_addr",$fld_arr);
		
		}
		$result=mysqli_query($con, $qry);
		if($result){
			$msg = "Details saved successfully";
		}else{
			$msg = "Update failed! Please try again after some time or contact out customer support.";
		}
	}
		if(!get_rst("select memb_id from memb_ext_addr where ext_addr_default=1 and memb_id=$memb_id", $rw)){
			execute_qry("update memb_ext_addr set ext_addr_default=1 where memb_id=$memb_id LIMIT 1");
		}		
	
?>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Company-Name - Buyer Edit Address Details</title>
<link href="styles/styles.css" rel="stylesheet" type="text/css" />
<script src="scripts/scripts.js" type="text/javascript"></script>
<script src="lib/frmCheck.js" type="text/javascript"></script>
<script type="text/javascript" src="scripts/jquery-1.6.1.min.js"></script>
<script>
function validate_key(evt) {
  var theEvent = evt || window.event;
  var key = theEvent.keyCode || theEvent.which;
  key = String.fromCharCode( key );
  var regex = /[0-9]|\./;
  if( !regex.test(key) ) {
    theEvent.returnValue = false;
    if(theEvent.preventDefault) theEvent.preventDefault();
  }
}

function Submit_form()
{	
	if(chkForm(document.frm)==false)
		return false;
	else
		document.frm.submit();
}
function clearfields(){
	var formElements = document.getElementById("myForm").elements;
	var fieldType = "";
	for (i = 0; i < formElements.length; i++)
		{
			fieldType = formElements[i].type.toLowerCase();
			switch (fieldType)
			{
				case "text":
				case "password":
				case "textarea":
				
					formElements[i].value = "";
					break;
				default:
					break;
			}
		}
	if(document.getElementById('act').value==1){
		document.getElementById('button1').value="Save";
	}
		document.getElementById('action').value= 0;
}

</script>

<head>
<body>
<?php require("header.php"); ?>
<div id="contentwrapper">
<div id="contentcolumn">
  <div class="center-panel">
    <div class="you-are-here">
      <p align="left"> YOU ARE HERE:<span class="you-are-normal"><a href="index.php" title="Home Page">Home</a> | Membership Registration</span> </p>
    </div>
	<?php
	if($login_msg <> "") { ?>
	<br>
	<div class="alert alert-info">
		<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
		<?=$login_msg?>
	</div>
	<?php }else{ ?>
	<div id="contentarea">
		<div class=" panel panel-info">
			<div class="panel-heading">Manage My Address</div>
			<?php
			if($msg <> "") { ?>
			<br>
			<div class="alert alert-info">
				<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				<?=$msg?>
			</div>
			<?php } ?>
			<div id="memb_addr_upd">
				<table width="100%" border="0" cellspacing="1" cellpadding="5">
					<?php if(true){
						if($id == "")
						{
							$rst = mysqli_query($con, "select * from memb_ext_addr where memb_id=$memb_id and ext_addr_default=1");
							$row = mysqli_fetch_assoc($rst);	
						}
						else
						{
							$rst = mysqli_query($con, "select * from memb_ext_addr where addr_id=$id");
							$row = mysqli_fetch_assoc($rst);
						}
						
					?>	
					
					<form name="frm" id="myForm" method="post" >
					<input type="hidden" id="act" name="act" value="1" >
					<input type="hidden" name="action" id="action" value="" >
					<tr>
						<td bgcolor="#F5F5F5"><input type="button" class="btn btn-warning" onClick="javascript:clearfields();" value="Create New Address"/></td>
						<td bgcolor="#F5F5F5"></td>
					</tr>
					<tr>
					<td bgcolor="#F5F5F5">Address Name Alias</td>
						<td bgcolor="#F5F5F5"><input type="textbox" size="25" style="width:auto;" class="form-control" title="Company Name" name="ext_addr_name" value="<?php echo $row["ext_addr_name"]; ?>" maxlength="100" id="100" /> </td>
						
					</tr>
					<tr>
						<td bgcolor="#F5F5F5">Default Address</td>
						<td bgcolor="#F5F5F5">
						<?php if ($row["ext_addr_default"] == 1){ ?>
						<input type="checkbox" name="ext_addr_default" checked tabindex="14" value="1" >
						<?php }else{ ?>
						<input type="checkbox" name="ext_addr_default" tabindex="14" value="" >
						<?php } ?>
						</td>
					</tr>
					<tr>
						<td bgcolor="#F5F5F5">Address 1</td>
						<td bgcolor="#F5F5F5"><input type="textbox" title="Address 1" class="form-control" name="ext_addr1" id="101500" style="width:220px;" value="<?php echo $row["ext_addr1"]; ?>"></td>
					</tr>
					<tr>
						<td bgcolor="#F5F5F5">Address 2</td>
						<td bgcolor="#F5F5F5"><input type="textbox" title="Address 2" class="form-control" name="ext_addr2" style="width:220px;" value="<?php echo $row["ext_addr2"]; ?>"></td>
					</tr>
					
					<tr>
						<td bgcolor="#F5F5F5">State</td>
						
						<td bgcolor="#F5F5F5">	<select id="100" title="State" style="width:220px;" class="form-control" name="ext_addr_state">
						<?php if($row["ext_addr_state"] == ""){ ?>
							<option value="">Select</option>
						<?php }else{ ?>
							<option value="<?php echo $row["ext_addr_state"]; ?>"><?php echo $row["ext_addr_state"]; ?></option>
						<?php } ?>
							<?=create_cbo("select state_name,state_name from state_mast",func_var($ext_addr_state))?>
						</select> </td>
					</tr>
					<tr>
						<td bgcolor="#F5F5F5">City</td>
						<td bgcolor="#F5F5F5"><input type="textbox" class="form-control" title="City" name="ext_addr_city" value="<?php echo $row["ext_addr_city"]; ?>" maxlength="50" id="100" size="25" style="width:auto;" /></td>
					</tr>
					<tr>
						<td bgcolor="#F5F5F5">Pincode</td>
						<td bgcolor="#F5F5F5"><input type="textbox" class="form-control" title="Pincode" onkeypress='validate_key(event)' name="ext_addr_pin" value="<?php echo $row["ext_addr_pin"]; ?>" maxlength="6" id="100" size="25" style="width:auto;" /></td>
					</tr>
					<tr>
						<td bgcolor="#F5F5F5">Contact Number</td>
						<td bgcolor="#F5F5F5"><input type="textbox" class="form-control" title="Contact Number" onkeypress='validate_key(event)' name="ext_addr_contact" value="<?php echo $row["ext_addr_contact"]; ?>" maxlength="10" id="100" size="25" style="width:auto;"/></td>
					</tr>
					<tr>
						<td bgcolor="#F5F5F5"></td>
						<td bgcolor="#F5F5F5"><input type="button" class="btn btn-warning" onClick="javascript:return Submit_form();" value="Save" id=button1 name=button1></td>
					</tr>
					</form>	
					<?php } ?>
				</table>
			</div>
		</div>	
		
		<div id="memb_addr_list" class=" panel panel-info">
			<div class="panel-heading">Address List</div>
			<br>
			<?php
				$sql=	"select addr_id";
				$sql = $sql.",ext_addr_name as 'Address Name Alias'"; 
				$sql = $sql.",ext_addr_default as 'Default Address'";
				$sql = $sql." from memb_ext_addr where memb_id=$memb_id";			
				//echo $sql;
				$url = "memb_addr_upd.php";
				
				create_list($sql,$url,$rec_limit=200,$pg_class="tbl_pages",5,"", "table_form", 2);
			?>
		</div>
	</div>
	<? } ?>
  </div>
</div>
</div>
<? require("left.php"); ?>

<? require("footer.php"); ?>	
</body>
<script src="scripts/chat.js" type="text/javascript"></script>
<html>
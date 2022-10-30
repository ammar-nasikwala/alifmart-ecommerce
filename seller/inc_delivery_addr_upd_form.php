<?php
session_start();
$msg="";
?>

<link type="text/css" rel="stylesheet" href="css/main.css" />
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

<?php
	$msg = "";
	$sup_id = "";
	$sup_company = "";	
	$sup_ext_address_type = "";
	$sup_ext_address = "";
	$sup_ext_state = "";	
	$sup_ext_city = "";
	$sup_ext_pincode = "";
	$sup_ext_contact_no = "";
	$id = "";	
	$action="";
	$action=func_read_qs("action");
	if(isset($_GET["id"])){
		$id = $_GET["id"];
		if(isset($_GET["dl"])){
			$sup_id = $_SESSION["sup_id"];
			get_rst("Select count(addr_id) as total from sup_ext_addr where sup_id=$sup_id",$row_c);
			
			$total=$row_c["total"];
			if($total == 1){
				$msg = "You can not delete the default address";	
			}
			else{
				$qry = "delete from sup_ext_addr where addr_id=$id";
				$result=mysqli_query($con, $qry);
				if($result){
					$msg = "Record deleted successfully";
				}
			}
		}
	}
	
	if(isset($_SESSION["sup_id"])){
		$sup_id=$_SESSION["sup_id"];
	}else{	?>
		<script>
			alert("Your session timed out. Please login again.");
			window.location.href="index.php";
		</script>
		<?php
	}
	if(isset($_POST["act"])){		
		$sup_company = $_POST["sup_company"];	
		$sup_ext_address_type = $_POST["sup_ext_address_type"];
		$sup_ext_address = $_POST["sup_ext_address"];
		$sup_ext_state = $_POST["state_name"];	
		$sup_ext_city = $_POST["sup_ext_city"];
		$sup_ext_pincode = $_POST["sup_ext_pincode"];
		$sup_ext_contact_no = $_POST["sup_ext_contact_no"];
		
		
		$fld_arr = array();
		$fld_arr["sup_id"] = $sup_id;
		
		$fld_arr["sup_ext_name"] = $sup_company;
		
		$fld_arr["sup_ext_address"] = $sup_ext_address;
		
		$fld_arr["sup_ext_state"] = $sup_ext_state;
		
		$fld_arr["sup_ext_city"] = $sup_ext_city;
		
		$fld_arr["sup_ext_address_type"] = $sup_ext_address_type;
		$fld_arr["sup_ext_pincode"] = $sup_ext_pincode;
		$fld_arr["sup_ext_contact_no"] = $sup_ext_contact_no;
		
		$qry = "";
		
		if(isset($_GET["id"]))
		{
			if($action == ""){
				$qry = func_update_qry("sup_ext_addr",$fld_arr," where addr_id=".$id);
		}else{
				$qry = func_insert_qry("sup_ext_addr",$fld_arr);
		}
		}
		else{	
				$qry = func_insert_qry("sup_ext_addr",$fld_arr);
		
		}
		
		$result=mysqli_query($con, $qry);
	
		if($result){
			$msg = "Details Saved Successfully";
		}
		else{
			$msg = "Update failed! Cannot add the record to the database";
		}	
	}

?>
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
		<div id="sup_addr_upd">
			<table width="100%" border="0" cellspacing="1" cellpadding="5">
				<?php if(true){
					if($id == "")
					{
						$rst = mysqli_query($con, "select * from sup_ext_addr where sup_id=$sup_id limit 1");
						$row = mysqli_fetch_assoc($rst);	
					}
					else
					{
						$rst = mysqli_query($con, "select * from sup_ext_addr where addr_id=$id");
						$row = mysqli_fetch_assoc($rst);
					}
					
				?>	
				
				<form name="frm" id="myForm" method="post" >
				<input type="hidden" name="act" id="act" value="1" >
				<input type="hidden" name="action" id="action" value="" >
				<tr>
				<td bgcolor="#F5F5F5"><input type="button" class="btn btn-warning" onClick="javascript:clearfields();" value="Create New Address"/></td>
				<td bgcolor="#F5F5F5"></td>
				</tr>
				<tr>
				<td bgcolor="#F5F5F5">Establishment Name</td>
					<td bgcolor="#F5F5F5"><input type="textbox" size="25" style="width:auto;" class="form-control" title="Company Name" name="sup_company" value="<?php echo $row["sup_ext_name"]; ?>" maxlength="100" id="100" /> </td>
					
				</tr>
				<tr>
					<td bgcolor="#F5F5F5">Address Type</td>
					<td bgcolor="#F5F5F5">
					<?php if ($row["sup_ext_address_type"] == ""){ ?>
					<input type="textbox" title="Company Name" class="form-control" name="sup_ext_address_type" value="Billing Address" maxlength="100" id="100" size="25" style="width:auto;" />
					<?php }else{ ?>
					<input type="textbox" title="Company Name" class="form-control" name="sup_ext_address_type" value="<?php echo $row["sup_ext_address_type"]; ?>" maxlength="100" id="100" size="25" style="width:auto;" />
					<?php } ?>
					<br>Eg. Warehouse, Sales office, Manufacturing Depot etc. </td>
				</tr>
				<tr>
					<td bgcolor="#F5F5F5">Address</td>
					<td bgcolor="#F5F5F5"><textarea title="Address" rows=5 cols=27 class="form-control" name="sup_ext_address" id="101500" style="width:220px;"><?php echo $row["sup_ext_address"]; ?></textarea></td>
				</tr>
				
				<tr>
					<td bgcolor="#F5F5F5">State</td>
					
					<td bgcolor="#F5F5F5">	<select id="100" title="State" style="width:220px;" class="form-control" name="state_name">
					<?php if($row["sup_ext_state"] == ""){ ?>
						<option value="">Select</option>
					<?php }else{ ?>
						<option value="<?php echo $row["sup_ext_state"]; ?>"><?php echo $row["sup_ext_state"]; ?></option>
					<?php } ?>
						<?php create_cbo("select state_name,state_name from state_mast",func_var($state_name))?>
					</select> </td>
					<!-- <td bgcolor="#F5F5F5"><input type="textbox" title="State" name="sup_ext_state" value="<?php echo $row["sup_ext_state"]; ?>" maxlength="50" id="100" size="30" class="textfield" /> </td> -->
				</tr>
				<tr>
					<td bgcolor="#F5F5F5">City</td>
					<td bgcolor="#F5F5F5"><input type="textbox" class="form-control" title="City" name="sup_ext_city" value="<?php echo $row["sup_ext_city"]; ?>" maxlength="50" id="100" size="25" style="width:auto;" /></td>
				</tr>
				<tr>
					<td bgcolor="#F5F5F5">Pincode</td>
					<td bgcolor="#F5F5F5"><input type="textbox" class="form-control" title="Pincode" onkeypress='validate_key(event)' name="sup_ext_pincode" value="<?php echo $row["sup_ext_pincode"]; ?>" maxlength="6" id="100" size="25" style="width:auto;" /></td>
				</tr>
				<tr>
					<td bgcolor="#F5F5F5">Contact Number</td>
					<td bgcolor="#F5F5F5"><input type="textbox" class="form-control" title="Contact Number" onkeypress='validate_key(event)' name="sup_ext_contact_no" value="<?php echo $row["sup_ext_contact_no"]; ?>" maxlength="10" id="100" size="25" style="width:auto;"/></td>
				</tr>
				<tr>
					<td bgcolor="#F5F5F5"></td>
					<td bgcolor="#F5F5F5"><input type="button" class="btn btn-warning" onClick="javascript:return Submit_form();" value="Update" id=button1 name=button1></td>
				</tr>
				</form>	
				<?php } ?>
			</table>
		</div>
	</div>	
	<?php
		$sql=	"select addr_id";
		$sql = $sql.",sup_ext_name as 'Establishment Name'"; 
		$sql = $sql.",sup_ext_address_type as 'Address Type'";
		$sql = $sql." from sup_ext_addr where sup_id=0$sup_id";			
		//echo $sql;
		$url = "seller_delivery_addr.php";
		
		create_list($sql,$url,$rec_limit=200,$pg_class="tbl_pages",5,"", "table_form", 2);
	?>
</div>
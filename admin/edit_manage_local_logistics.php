<?php
require_once("inc_admin_header.php");


$id = func_read_qs("id");

if(isset($_POST["submit"])){

		$fld_arr = array();
		
		$fld_arr["vendor_name"] = func_read_qs("vendor_name");
		$fld_arr["contact_no"] = func_read_qs("contact_no");
		$fld_arr["alt_contact_no"] = func_read_qs("alt_contact_no");
		$fld_arr["city"]=func_read_qs("city");

		
		if($id==""){
			//$fld_arr["brand_id"] = get_max("brand_mast","brand_id");
			$qry = func_insert_qry("local_logistics_vendor",$fld_arr);
		}else{
			$qry = func_update_qry("local_logistics_vendor",$fld_arr," where id=$id");
		}
				
		if(!mysqli_query($con, $qry)){
			echo("Problem updating database... $qry");
		}else{
			?>
			<script>
				alert("Record saved successfully.");
				window.location.href="manage_local_logistics_vendor.php";
			</script>
			<?php
			die("");
		}
	
}

$act = func_read_qs("act");
if($act=="d"){
	if(!mysqli_query($con, "delete from local_logistics_vendor where id=".func_read_qs("id"))){
		echo("Problem updating database... $qry");
	}else{
		?>
		<script>
			alert("Record Deleted successfully.");
			window.location.href="manage_local_logistics_vendor.php";
		</script>
		<?php
		die("");
	}	
}

//global $brand_name;

if($id<>""){
	$qry = "select * from local_logistics_vendor where id=".$id;
	if(get_rst($qry, $row)){
		//$row = mysqli_fetch_assoc($rst);
		$vendor_name = $row["vendor_name"];
		$contact_no = $row["contact_no"];
		$alt_contact_no=$row["alt_contact_no"];
		$city=$row["city"];
		
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


<?php
if($id){
	$page_head = "Edit Local Logistic Vendor Details";
}else{
	$page_head = "Create New Local Logistic Vendor";
}
?>

<h2><?=$page_head?></h2>

<form name="frm" id="frm" method="post" enctype="multipart/form-data">
	<input type="hidden" id="id" name="id" value="<?=func_var($id)?>">
	<input type="hidden" id="act" name="act" value="">
	
	<table border="1" class="table_form">
	
		<tr>
		<td>Vendor Name : *</td>
		<td><input class="form-control textbox-lrg" type="text" size="71" id="100" title="Vendor Name" name="vendor_name" value="<?=func_var($vendor_name)?>"></td>
		</tr>
		
		<tr>
		<td>Contact No.: *</td>
		<td><input class="form-control textbox-lrg" type="text" size="71" id="100" title="Contact No." maxlength="10" onkeypress='validate_key(event)' name="contact_no" value="<?=func_var($contact_no)?>"></td>
		</tr>
		
		<tr>
		<td>Alternative Contact No.: </td>
		<td><input class="form-control textbox-lrg" type="text" size="71" title="Alternative Contact No." maxlength="10" onkeypress='validate_key(event)' name="alt_contact_no" value="<?=func_var($alt_contact_no)?>"></td>
		</tr>
		
		<tr>
		<td>City: *</td>
		<td><select id="100" class="form-control textbox-lrg"  title="City" name="city" >
			<?=create_cbo("select DISTINCT city_name,city_name from city_mast",func_var($city))?>">
			</select></td>
		</tr>
		
		<tr>
		<th colspan="2" id="centered">
		<input type="submit" class="btn btn-warning" value="Save" name="submit" onClick="javascript:return Submit_form();">
		<?php if($id<>""){?>
			&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="submit" class="btn btn-warning" value="Delete" name="btn_delete" onclick="javascript: return js_delete();">
		<?php }?>
		
		</td>
		</tr>
		
	</table>
</form>

<?php
require_once("inc_admin_footer.php");

?>

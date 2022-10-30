<?php
require_once("inc_admin_header.php");

$img_path = "../images/props/";
$id = func_read_qs("id");

if(isset($_POST["submit"])){

	//echo("[".$_POST["prop_status"]."|".intval(func_read_qs("prop_status"))."]<br>");
	
	$fld_arr = array();
	
	$fld_arr["prop_name"] = func_read_qs("prop_name");
	$fld_arr["prop_status"] = intval(func_read_qs("prop_status"));
	//$fld_arr["prop_test"] = "";
	//echo("[".$fld_arr["prop_status"]."]<br>");
	
	if($id==""){
		$qry = func_insert_qry("prop_mast",$fld_arr);
	}else{
		$qry = func_update_qry("prop_mast",$fld_arr," where prop_id=$id");
	}
			
	if(!mysqli_query($con,$qry)){
		echo("Problem updating database... $qry");
	}else{
		?>
		<script>
			alert("Record saved successfully.");
			window.location.href="manage_props.php";
		</script>
		<?php
		die("");
	}
}

$act = func_read_qs("act");
if($act=="d"){
	if(!mysqli_query($con,"delete from prop_mast where prop_id=".func_read_qs("id"))){
		echo("Problem updating database... $qry");
	}else{
		?>
		<script>
			alert("Record Deleted successfully.");
			window.location.href="manage_props.php";
		</script>
		<?php
		die("");
	}	
}

//global $brand_name;

if($id<>""){
	$qry = "select * from prop_mast where prop_id=".$id;
	if(get_rst($qry, $row)){
		//$row = mysqli_fetch_assoc($rst);
		$prop_name = $row["prop_name"];
		$prop_status = $row["prop_status"];
	}
}

?>

<script>


	function js_delete(){
		if(confirm("Are you sure you want to delete this record? Deleting this brand will delete all its related products")){		
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


<?php
if($id){
	$page_head = "Edit Property Details";
}else{
	$page_head = "Create New Property";
}
?>

<h2><?php echo $page_head; ?></h2>

<form name="frm" id="frm" method="post">
	<input type="hidden" id="id" name="id" value="<?=func_var($id)?>">
	<input type="hidden" id="act" name="act" value="">
	
	<table border="1" class="table_form">
	
		<tr>
		<td>Property Name</td>
		<td><input type="text" size="71" maxlength="50" id="100" title="Property Name" name="prop_name" value="<?=func_var($prop_name)?>">*</td>
		</tr>

		<tr>
		<td>Status</td>
		<td>
		<select name="prop_status">
			<?php func_option("Active","1",func_var($prop_status))?>
			<?php func_option("Inactive","0",func_var($prop_status))?>
		</select>
		</td>
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

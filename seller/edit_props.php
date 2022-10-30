<?
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
			
	if(!mysql_query($qry)){
		echo("Problem updating database... $qry");
	}else{
		?>
		<script>
			alert("Record saved successfully.");
			window.location.href="manage_props.php";
		</script>
		<?
		die("");
	}
}

$act = func_read_qs("act");
if($act=="d"){
	if(!mysql_query("delete from prop_mast where prop_id=".func_read_qs("id"))){
		echo("Problem updating database... $qry");
	}else{
		?>
		<script>
			alert("Record Deleted successfully.");
			window.location.href="manage_props.php";
		</script>
		<?
		die("");
	}	
}

//global $brand_name;

if($id<>""){
	$rst = mysql_query("select * from prop_mast where prop_id=".$id);
	if($rst){
		$row = mysql_fetch_assoc($rst);
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


<?
if($id){
	$page_head = "Edit Property Details";
}else{
	$page_head = "Create New Property";
}
?>

<h2><?=$page_head?></h2>

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
			<?func_option("Active","1",func_var($prop_status))?>
			<?func_option("Inactive","0",func_var($prop_status))?>
		</select>
		</td>
		</tr>

		<tr>
		<th colspan="2" id="centered">
		<input type="submit" class="btn btn-default" value="Save" name="submit" onClick="javascript:return Submit_form();">
		<?if($id<>""){?>
			&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="submit" class="btn btn-default" value="Delete" name="btn_delete" onclick="javascript: return js_delete();">
		<?}?>
		
		</td>
		</tr>
		
	</table>
</form>

<?
require_once("inc_admin_footer.php");

?>

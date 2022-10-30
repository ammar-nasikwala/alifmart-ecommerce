<?php
require_once("inc_admin_header.php");

$id = func_read_qs("id");

$saved="";

if(isset($_POST["submit"])){

	$msg="1";
	$saved="1";

	$fld_arr = array();

	$fld_arr["tax_name"] = func_read_qs("tax_name");
	$fld_arr["tax_percent"] = func_read_qs("tax_percent");
	$fld_arr["tax_status"] = func_read_qs("tax_status");

	if($msg=="1"){
		$sql_upd_prods="";	
		if($id==""){
			$qry = func_insert_qry("tax_mast",$fld_arr);
		}else{
			$qry = func_update_qry("tax_mast",$fld_arr," where tax_id=$id");
			
			$sql_upd_prods = "update prod_mast set prod_tax_name='".$fld_arr["tax_name"]."', prod_tax_percent=0".$fld_arr["tax_percent"]." where prod_tax_id=$id";
		}
				
		if(!mysqli_query($con, $qry)){
			echo("Problem updating database... $qry");
		}else{
			if($sql_upd_prods<>""){
				execute_qry($sql_upd_prods);
			}
			?>
			<script>
				alert("Record saved successfully.");
				window.location.href="manage_tax.php";
			</script>
			<?php
			die("");
		}
	}else{
		$saved="0";
		?>
		<script>
			alert("<?=$msg?>");
			//window.history.back();
			//window.location.href="manage_tax.php";
		</script>
		<?php
	}
}

$act = func_read_qs("act");
if($act=="d"){
	if(!mysqli_query($con, "delete from tax_mast where tax_id=".func_read_qs("id"))){
		echo("Problem updating database... $qry");
	}else{
		?>
		<script>
			alert("Record Deleted successfully.");
			window.location.href="manage_tax.php";
		</script>
		<?php
		die("");
	}	
}

//global $brand_name;
if($id<>""){
	if(get_rst("select * from tax_mast where tax_id=0".$id,$row)){
		$tax_name = $row["tax_name"];
		$tax_percent = $row["tax_percent"];
		$tax_status = $row["tax_status"];
	}
}

if($saved=="0"){
	$tax_name = $fld_arr["tax_name"];
	$tax_percent = $fld_arr["tax_percent"];
	$tax_status = $fld_arr["tax_status"];
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

<?php
if($id){
	$page_head = "Edit Tax Details";
}else{
	$page_head = "Create New Tax Type";
}
?>

<h2><?php echo $page_head?></h2>

<form name="frm" id="frm" method="post" enctype="multipart/form-data">
	<input type="hidden" id="id" name="id" value="<?=func_var($id)?>">
	<input type="hidden" id="act" name="act" value="">
	
	<table border="1" class="table_form">
	
		<tr>
		<td>Tax Type Name</td>
		<td><input type="text" size="71" maxlength="30" id="100" title="Tax Type Name" name="tax_name" value="<?=func_var($tax_name)?>">*</td>
		</tr>
		
		<tr>
		<td>Tax Percentage</td>
		<td><input type="text" size="10" maxlength="10" id="110" title="Tax percentage" name="tax_percent" value="<?=func_var($tax_percent)?>">%</td>
		</tr>		
				
		<tr>
		<td>Status</td>
		<td>
		<select name="tax_status">
			<?php func_option("Active","1",func_var($tax_status))?>
			<?php func_option("Inactive","0",func_var($tax_status))?>
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

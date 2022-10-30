<?php
require_once("inc_admin_header.php");

$id = func_read_qs("id");

$saved="";

if(isset($_POST["submit"])){

	$msg="1";
	$saved="1";

	$fld_arr = array();

	$fld_arr["pay_name"] = func_read_qs("pay_name");
	$fld_arr["pay_sort"] = func_read_qs("pay_sort");
	$fld_arr["pay_status"] = func_read_qs("pay_status");

	echo("[".func_read_qs("pay_status")."]");
	if($msg=="1"){
		$sql_upd_prods="";	
		if($id==""){
			$qry = func_insert_qry("pay_method",$fld_arr);
		}else{
			$qry = func_update_qry("pay_method",$fld_arr," where pay_id=$id");			
		}
				
		if(!mysqli_query($con, $qry)){
			echo("Problem updating database... $qry");
		}else{
			if($sql_upd_prods<>""){
				execute_qry($sql_upd_prods);
			}
			?>
			<script>
				alert("Changes updated successfully.");
				window.location.href="manage_pm.php";
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
	if(!mysqli_query($con, "delete from pay_method where pay_id=".func_read_qs("id"))){
		echo("Problem updating database... $qry");
	}else{
		?>
		<script>
			alert("Record Deleted successfully.");
			window.location.href="manage_pm.php";
		</script>
		<?php
		die("");
	}	
}

//global $brand_name;
if($id<>""){
	if(get_rst("select * from pay_method where pay_id=0".$id,$row)){
		$pay_name = $row["pay_name"];
		$pay_sort = $row["pay_sort"];
		$pay_status = $row["pay_status"];
		$pay_code = $row["pay_code"];
	}
}

if($saved=="0"){
	$pay_name = $fld_arr["pay_name"];
	$pay_sort = $fld_arr["pay_sort"];
	$pay_status = $fld_arr["pay_status"];
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
	$page_head = "Edit Payment Options";
}else{
	$page_head = "Create New Tax Type";
}
?>

<h2><?php echo  $page_head?></h2>

<form name="frm" id="frm" method="post" >
	<input type="hidden" id="id" name="id" value="<?=func_var($id)?>">
	<input type="hidden" id="act" name="act" value="">
	
	<table border="1" class="table_form">
	
		<tr>
		<td>Payment Internal Code</td>
		<td><?php echo func_var($pay_code)?></td>
		</tr>

		<tr>
		<td>Payment Method Text</td>
		<td><input type="text" size="71" maxlength="30" id="100" title="Payment Text" name="pay_name" value="<?=func_var($pay_name)?>">*</td>
		</tr>
		
		<tr>
		<td>Sort Position</td>
		<td><input type="text" size="10" maxlength="10" id="110" title="Tax percentage" name="pay_sort" value="<?=func_var($pay_sort)?>"></td>
		</tr>		
				
		<tr>
		<td>Status</td>
		<td>
		<select name="pay_status">
			<?php func_option("1 - Active","1",func_var($pay_status))?>
			<?php func_option("2 - Disabled","2",func_var($pay_status))?>
			<?php func_option("0 - Inactive","00",func_var($pay_status))?>
		</select>
		</td>
		</tr>		
	
		<tr>
		<th colspan="2" id="centered" >
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

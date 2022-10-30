 <?
require_once("inc_admin_header.php");

$sup_id = func_read_qs("id");
$act = func_read_qs("act");
if($act == "y"){

	$msg = "";

	$sup_alias_name=func_read_qs("sup_alias_name");
	
	$qry1 = "update sup_alias_name set status=1 where sup_alias='".$sup_alias_name."'";
	$qry2 = "update sup_mast set sup_alias='".$sup_alias_name."' where sup_id=$sup_id";		
	if(!mysqli_query($con, $qry1) || !mysqli_query($con, $qry2)){
	?>
		<script>
			alert("Problem updating database, please contact support team.");
		</script>
	<?	
	}else{
		?>
		<script>
			alert("Record saved successfully.");
			window.location.href="manage_sellers.php";
		</script>
		<?
		die("");
	}
	
}

?>
<script type="text/javascript" src="frmCheck.js"></script>
<script>


function Submit_form(){
	if(chkForm(document.frm)==false)
		return false;
	else
		document.frm.submit();
}
	
</script>


<h2>Seller Alias Name</h2>

<form name="frm" id="frm" method="post" enctype="multipart/form-data">
	<input type="hidden" id="id" name="id" value="<?=func_var($id)?>">
	<input type="hidden" id="act" name="act" value="y">
	
	<table border="1" class="table_form">
	<?php get_rst("select sup_company, sup_alias from sup_mast where sup_id=$sup_id", $row);?>
	
		<tr>
		<td>Seller Name</td>
		<td><?=$row["sup_company"]?></td>
		</tr>
		
		<tr>
			<td>Seller Alias</td>
			<td >
				<select id="100" title="State" name="sup_alias_name">
				<? if($row["sup_alias"] == ""){ ?>
				<option value="">Select</option>
				
				<?=create_cbo("select sup_alias,sup_alias from sup_alias_name where status=0 LIMIT 10",func_var($sup_alias_name));
				}else{ ?>
				<option value=""><?=$row["sup_alias"]?></option>
				<? } ?>
				</select>
			</td>
		</tr>
		
		<tr>
		<th colspan="2" id="centered" >
		<? if($row["sup_alias"] == ""){ ?>
		<input type="submit" class="btn btn-warning" value="Save" name="submit" onClick="javascript:return Submit_form();">
		<?}else{ ?>
			<input type="submit" class="btn btn-warning" disabled value="Save" name="submit" onClick="javascript:return Submit_form();">
		<? } ?>	
		</th>
		</tr>
		
	</table>
</form>

<?
require_once("inc_admin_footer.php");

?>

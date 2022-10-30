<?php
require_once("inc_admin_header.php");
$sup_id = $_SESSION["sup_id"];
if(isset($_POST["save_subdomain"])){
	$checked_arr = $_POST["chk_subdomain"];
	execute_qry("delete from subdomain_prod_list where sup_id=$sup_id");
	foreach($checked_arr as $chk_key => $chk_value){
		$fld_s_arr["prod_id"] = $chk_value;
		$fld_s_arr["sup_id"] = $sup_id;
		
		$qry1 = func_insert_qry("subdomain_prod_list",$fld_s_arr);
		execute_qry($qry1);
	}
	$dir = $_SERVER['DOCUMENT_ROOT']."subdomain/".$sup_id;
	if(is_dir($dir) == false){
		mkdir($dir,0700);
	}
	$a = '$';
	$file_data = "<?php ${a}sup_id=$sup_id; ?>\n";
	$file_data .= file_get_contents('../subdomain/index.php');
	file_put_contents('../subdomain/'.$sup_id.'/index.php', $file_data);
	js_alert("Record Saved Successfully");
} 
if($sup_id <> ""){
get_rst("select * from subdomain_mast where sup_id=$sup_id",$r);
$subdomain_name = $r["subdomain_name"];
$status = $r["subdomain_status"];
}
?>
<form name="frm_subdomain" id="frm_subdomain" method = "post">
<table border="1" class="table_form">
	<tr>
		<td>Subdomain Name: </td>
		<td><input type="text" name="subdomain_name" id="subdomain_name" readonly class="form-control textbox-lrg" value="<?=$subdomain_name.".Company-Name.com"?>" maxlength="240">
	</tr>
	<tr>
		<td>Status: </td>
		<td><select name="subdomain_status" disabled>
			<?php func_option("Active","Active",func_var($status))?>
			<?php func_option("Inactive","Inactive",func_var($status))?>
		</select></td>
	</tr>
	
	<tr class="showRow">
		<td>Select Product to be display on subdomain</td>
		<td>
		<div style="height:400px;border:solid 1px #cccccc;overflow-y:scroll;">
		<table border="0" width="100%">
			<?php
			$i=0;
			$sql = "select p.prod_name,p.prod_id from prod_mast p join prod_sup ps on ps.prod_id = p.prod_id where sup_id=$sup_id and p.prod_status=1 ORDER BY p.prod_name asc";
			get_rst($sql,$row,$rst);
			do{	?>
				<tr id="tr_oos">
				<?php if(get_rst("select prod_id from subdomain_prod_list where prod_id='".$row["prod_id"]."' and sup_id=$sup_id",$r1)){?>
					<td>
						<input type="checkbox" id = "chk_subdomain_<?=$row["prod_id"]?>" name="chk_subdomain[]?>" checked value=<?=$row["prod_id"]?> onclick="chk_select('<?=$row["prod_id"]?>')"> <?=$row["prod_name"]?>
					</td>
				<?php }else{?>
					<td>
						<input type="checkbox" id = "chk_subdomain_<?=$row["prod_id"]?>" name="chk_subdomain[]?>" value=<?=$row["prod_id"]?> onclick="chk_select('<?=$row["prod_id"]?>')"> <?=$row["prod_name"]?>
					</td>
				<?php }?></tr>
			<?php }while($row = mysqli_fetch_assoc($rst)); ?>
		</table>
	</tr>
</table>
<table cellspacing="1" cellpadding="5" align="center" width="100%">
<tr>
		<th colspan="10" style="text-align: center; padding-top: 5px">
		<input type="submit" class="btn btn-warning" value="Save" name ="save_subdomain">
		<input type="button" class="btn btn-warning" value="Back" name="Back" onClick="javascript: window.location.href='manage_seller_subdomain.php';">
		</td>
</tr>
</table>
</form>
<script>
function chk_select(obj){
	if($("input[type=checkbox]:checked").length > 12){
		alert("You have exceeded the max limit of 12 products.");
		document.getElementById("chk_subdomain_"+obj).checked = false;
	}
}

</script>
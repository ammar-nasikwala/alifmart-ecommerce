<?php
require_once("inc_admin_header.php");
require_once("ajax_admin.php");
$sup_id = func_read_qs("id");
if(isset($_POST["save_subdomain"])){
	$fld_arr = array();
	$fld_arr["subdomain_name"] = func_read_qs("subdomain_name");
	$fld_arr["subdomain_status"] = func_read_qs("subdomain_status");
	if(!get_rst("select * from subdomain_mast where sup_id=$sup_id",$r)){
		$fld_arr["sup_id"] = $sup_id;
		$qry = func_insert_qry("subdomain_mast",$fld_arr);
		execute_qry($qry);
		get_rst("select sup_email from sup_mast where sup_id=$sup_id", $row_em);
		$sup_email = $row_em["sup_email"];
		$from = "support@Company-Name.com";
		$body="Dear Seller,<br>";
		$body.="Congratulations!! Your free subdomain website has been created successfully.<br><br>";
		$body.="Log on to : http://".$fld_arr["subdomain_name"].".Company-Name.com to view your own portal. Please note that it might take a few minutes to hours to go live. Please check after a while.<br><br>";
		$body.="To change the products appearing on your portal, you can go to your 'Seller Panel -> Manage Subdomain' and do the changes you want.<br><br>";
		$body.="If you have any queries or want to add more than 12 products please get in touch with our support team.<br>";
		$body.="<br><br>Best Regards,<br>Team Company-Name";		
		xsend_mail($sup_email,"Company-Name - Your Subdomain Website created successfully.",$body,$from );
	}
	$checked_arr = $_POST["chk_subdomain"];
	execute_qry("delete from subdomain_prod_list where sup_id=$sup_id");
	foreach($checked_arr as $chk_key => $chk_value){
		$fld_s_arr["prod_id"] = $chk_value;
		$fld_s_arr["sup_id"] = $sup_id;
		
		$qry1 = func_insert_qry("subdomain_prod_list",$fld_s_arr);
		execute_qry($qry1);
	}
	$dir = $_SERVER['DOCUMENT_ROOT']."/subdomain/".$sup_id;
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
		<?php if($subdomain_name == ""){?>
		<td>Subdomain Name: </td>
		<td><input type="text" name="subdomain_name" id="subdomain_name" class="form-control textbox-lrg" value="<?=$subdomain_name?>" onblur="javascript: chk_subdomain_name(this);" maxlength="240" onkeypress="javascript: remove_msg('domain_name');" >
		<span id="domain_name"></span></td>
		<?php }else{?>
		<td>Subdomain Name: </td>
		<td><input type="text" name="subdomain_name" id="subdomain_name" disabled class="form-control textbox-lrg" value="<?=$subdomain_name?>">
		<?php }?>
	</tr>
	<tr>
		<td>Status: </td>
		<td><select name="subdomain_status">
			<?php func_option("Active","Active",func_var($status))?>
			<?php func_option("Inactive","Inactive",func_var($status))?>
		</select></td>
	</tr>
	<tr>
		<td>Subdomain Path: </td>
		<td>/public_html/subdomain/<?=$sup_id?></td>
	</tr>
	<tr class="showRow">
		<td style="width:200px">Select Product to be display on subdomain <br>(max 12 products can be selected)</td>
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
<table cellspacing="1" cellpadding="5" align="center">
<tr>
		<th colspan="10" id="centered">
		<input type="submit" class="btn btn-warning" value="Save" name ="save_subdomain" onclick="javascript: return validate_subdomain();">
		<input type="button" class="btn btn-warning" value="Back" name="Back" onClick="javascript: window.location.href='manage_seller_subdomain.php';">
		</td>
</tr>
</table>
</form>
<script type="text/javascript" src="../lib/ajax.js"></script>
<script>
function validate_subdomain(){
	res = chk_subdomain_name(document.frm_subdomain.subdomain_name);
	if(document.getElementById("subdomain_name").value == ""){
		alert("Please provide the name of subdomain");
		return false;
	}
	if(res != "Available"){
		alert(res);
		return false;
	}
}
function remove_msg(obj_id){
	document.getElementById(obj_id).innerHTML = "";
}
function chk_select(obj){
	if($("input[type=checkbox]:checked").length > 12){
		alert("You have exceeded the max limit of 12 products.");
		document.getElementById("chk_subdomain_"+obj).checked = false;
	}
}
function chk_subdomain_name(obj){
	v_valid = ""
	if(obj.value != ""){
		var obj_lbl = document.getElementById("domain_name");
		
		v_valid = call_ajax("ajax_admin.php","process=check_subdomain_name&subdomain_name=" + obj.value);
		obj_lbl.innerHTML = v_valid
		if(obj_lbl.innerHTML=="Available"){
			obj_lbl.style.color="green"
		}else{
			obj_lbl.style.color="#FF5588"
		}
	}
	return v_valid;
}
</script>
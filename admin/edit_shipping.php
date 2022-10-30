<?php
require_once("inc_admin_header.php");

$memb_img="";

$img_path = "../images/users/";
$id = func_read_qs("id");


if(isset($_POST["submit"])){

	$zone_name = func_read_qs("zone_name");
	execute_qry("delete from ship_zone where zone_name='$zone_name'");
	
	$fld_arr = array();
	for($i=1;$i<=10;$i++){
		$ord_upto = func_read_qs("ord_upto_$i");
		$ship_charge = func_read_qs("ship_charge_$i");
		
		if($ord_upto <> "" and $ship_charge <>""){
			$fld_arr["zone_name"] = $zone_name;
			$fld_arr["ord_upto"] = $ord_upto;
			$fld_arr["ship_charge"] = $ship_charge;
			
			$sql = func_insert_qry("ship_zone",$fld_arr);
			execute_qry($sql);
		}
	}
	
	?>
	<script>
		alert("Record saved successfully.");
		window.location.href="manage_shipping.php";
	</script>
	<?php
	die("");
}

$act = func_read_qs("act");

if($act=="d"){
	if(!mysqli_query($con, "delete from member_mast where user_id=".func_read_qs("id"))){
		echo("Problem updating database... $qry");
	}else{
		?>
		<script>
			alert("Record Deleted successfully.");
			window.location.href="manage_buyers.php";
		</script>
		<?php
		die("");
	}
}

$ord_arr = array();
$ship_arr = array();
$row_count=1;

if($id<>""){
	$zone_name=$id;
	if(get_rst("select * from ship_zone where zone_name='$zone_name' order by ord_upto",$row,$rst)){
		do{
			$ord_arr[$row_count] = $row["ord_upto"];
			$ship_arr[$row_count] = $row["ship_charge"];
				
			
			$row_count++;
		}while($row = mysqli_fetch_assoc($rst));
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
	
</script>

<?php
if($id){
	$page_head = "Shipping Charges ";
}else{
	$page_head = "Shipping Charges";
}
?>

<h2><?php echo $page_head?></h2>

<form name="frm" id="frm" method="post" enctype="multipart/form-data">
	<input type="hidden" id="id" name="id" value="<?=func_var($id)?>">
	<input type="hidden" id="act" name="act" value="">
	
	<table border="1" class="table_form">

		<?php
		$sql = "SELECT state_zone, CONCAT(state_zone,' (', GROUP_CONCAT( state_name ) ,  ')' ) AS ids ";
		$sql = $sql." FROM state_mast";
		$sql = $sql." GROUP BY state_zone";
		$sql = $sql." LIMIT 0 , 30";
		?>
		
		<tr>
		<td align="left" colspan="10">Select Zone
		<select id="100" title="Zone" name="zone_name">
			<option value="">Select</option>
			<?php create_cbo($sql,func_var($zone_name))?>
		</select>*
		</td>
		</tr>	
				
		<tr>
			<th align="right">Order Value upto</th>
			<th colspan="3" align="left">Shipping Charges</th>
		</tr>

		<tr>
			<td width="20%" colspan="2"></td>
			<td width="100" rowspan="12">Provide shipping charges slab in ascending order of order value with their charges. (e.g. 100-10, 200-8)</td>
			<td rowspan="12" width="60%"></td>
		</tr>
		
		<?php
		for($i=1;$i<=10;$i++){
			?>
			<tr>
				<td align="right"><?=$i?>. <input type="text" name="ord_upto_<?=$i?>" value="<?=func_var($ord_arr[$i])?>" size="5" maxlength="5" title="Order Upto on line <?=$i?>" id="010"></td>
				<td><input type="text" name="ship_charge_<?=$i?>" size="5" maxlength="5" value="<?=func_var($ship_arr[$i])?>" title="Shipping Charge on line <?=$i?>" id="010"></td>
			</tr>
			<?php
		}?>

		<tr>
		<th colspan="10" id="centered">
			<input type="submit" class="btn btn-warning" value="Save" name="submit" onClick="javascript:return Submit_form();">		
		</td>
		</tr>
		
	</table>
</form>

<?php
require_once("inc_admin_footer.php");

?>

<?php
require_once("inc_admin_header.php");
$msg = "";
$id = func_read_qs("id");

if($id<>""){
	$qry = "select * from offer_coupon where coupon_id=0".$id;
	if(get_rst($qry, $row)){
		$coupon_code = $row["coupon_code"];
		$min_ord_value = $row["min_ord_value"];
		$disc_per = $row["disc_per"];
		$active = $row["active"];
		$valid_from = $row["valid_from"];
		$valid_till = $row["valid_till"];
		$max_discount_value = $row["max_discount_value"];
		$show_onscreen = $row["show_onscreen"];
		$memb_id = $row["memb_id"];
	}
}

if(isset($_POST["submit"])){
	if(func_read_qs("act") == "y"){
		$from_date = func_read_qs("valid_from");
		$till_date = func_read_qs("valid_till");
		if(strtotime($from_date) < strtotime($till_date)){
			$fld_arr = array();
			$result = func_read_qs("coupon_code");
			$fld_arr["coupon_code"] = strtoupper($result);
			$fld_arr["min_ord_value"] = func_read_qs("min_ord_value");
			$fld_arr["disc_per"] = func_read_qs("disc_per");
			$fld_arr["active"] = func_read_qs("active");
			$fld_arr["valid_from"] = $from_date;
			$fld_arr["valid_till"] = $till_date;
			$fld_arr["max_discount_value"] = func_read_qs("max_discount_value");
			if (isset($_POST["show_onscreen"])) {
				$fld_arr["show_onscreen"] = 1;
			}else{
				$fld_arr["show_onscreen"] = 0;
			}
			if(func_read_qs("memb_id") > 0){
				$fld_arr["memb_id"] = func_read_qs("memb_id");
			}
			if($id==""){
				$qry = func_insert_qry("offer_coupon",$fld_arr);
			}else{
				$qry = func_update_qry("offer_coupon",$fld_arr," where coupon_id=0$id");
			}
					 
			if(!mysqli_query($con, $qry)){
				echo("Problem updating database... $qry");
			}else{ ?>
				<script>
					alert("Record saved successfully.");
					window.location.href="manage_coupons.php";
				</script>
				<?php
				die("");
			}
		}else{ ?>
				<script>
					alert("Invalid date selection, valid till date should be greater then valid from date.");
				</script>
				<?php
		}
	}else{
		?>
		<script>
			alert("<?=$msg?>");
		</script>
		<?php
	}
}

$act = func_read_qs("act");
if($act=="d"){
	if(!mysqli_query($con, "delete from offer_coupon where coupon_id=0".func_read_qs("id"))){
		echo("Problem updating database... $qry");
	}else{
		?>
		<script>
			alert("Record Deleted successfully.");
			window.location.href="manage_coupons.php";
		</script>
		<?php
		die("");
	}	
}

?>

<script>
	$( function() {
		$( "#datepicker1" ).datepicker({
			minDate: 0
		});
	} );

	$( function() {
		$( "#datepicker2" ).datepicker({
			minDate: 0
		});
	} );
	
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
<link href="../lib/chosen/chosen.min.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../lib/chosen/chosen.jquery.min.js"></script>

<?php
if($id){
	$page_head = "Edit Coupon Details";
}else{
	$page_head = "Create Offer Coupon";
}
?>

<h2><?=$page_head?></h2>

<form name="frm" id="frm" method="post" enctype="multipart/form-data">
	<input type="hidden" id="id" name="id" value="<?=func_var($id)?>">
	<input type="hidden" id="act" name="act" value="y">
	
	<table border="1" class="table_form">
		<tr><td align="right" colspan='2'><p class="color-amber">*All fields are compulsory<p></td></tr>
		<tr>
		<td>Coupon Code</td>
		<td><input type="text" maxlength="50" id="upper" title="Coupon Code" style="text-transform:uppercase" name="coupon_code" value="<?=func_var($coupon_code)?>"></td>
		</tr>

		<tr>
		<td>Minimum Order Value</td>
		<td><input type="text" maxlength="10" id="100" title="Minimum Order Value" onkeypress='validate_key(event)' name="min_ord_value" value="<?=func_var($min_ord_value)?>"></td>
		</tr>

		<tr>
		<td>Discount Percent</td>
		<td><input type="text" maxlength="5" id="100" title="Discount Percent" onkeypress='validate_key(event)' name="disc_per" value="<?=func_var($disc_per)?>"></td>
		</tr>
		
		<tr>
		<td>Maximum Order Discount Amount</td>
		<td><input type="text" maxlength="10" id="100" title="Maximum Order Discount Amount" onkeypress='validate_key(event)' name="max_discount_value" value="<?=func_var($max_discount_value)?>"></td>
		</tr>
		
		<tr>
		<td>Coupon Status</td>
		<td>
		<select name="active" id="100" title="Coupons active status">
			<?php func_option("Active", 1 ,func_var($active))?>
			<?php func_option("Inactive",0,func_var($active))?>
		</select>
		</td>
		</tr>
			
		<tr>
		<td>Valid From</td>
		<td><input type="text" id="datepicker1" name="valid_from" value="<?=func_var($valid_from)?>"></td>
		</tr>
		
		<tr>
		<td>Valid Till</td>
		<td><input type="text" id="datepicker2" name="valid_till" value="<?=func_var($valid_till)?>"></td>
		</tr>
		
		<tr>
		<td>Select Buyer (For Buyer specific coupons only)</td>
			<td>
				<select name="memb_id" data-placeholder="Select Buyer" style="width:150px;" class="chosen-select">
					<option value="0">Select</option>
					<?php create_cbo("select memb_id, memb_company from member_mast where memb_act_status=1 and memb_company is not null ",$memb_id)?>
				</select>
			</td>
		</tr>
		<tr>
         <td>Display OnWebsite</td>
            <td>
               <input type="checkbox" name="show_onscreen" value="1" <?=sel_("1",$show_onscreen."")?>>
            </td>
        </tr>
		<tr>
		<th colspan="2" id="centered">
		<input type="button" class="btn btn-warning" value="Back" name="Back" onClick="javascript: window.location.href='manage_coupons.php';">
		&nbsp;&nbsp;&nbsp;&nbsp;
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

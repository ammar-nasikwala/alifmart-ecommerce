<?php
require_once("inc_admin_header.php");

$memb_img="";
$msg_class = "info";
$img_path = "../images/users/";
$id = func_read_qs("id");
if(isset($_POST["act"])) $act=$_POST["act"];
if(isset($_POST["save"])){
	
	$fld_arr = array();
	$fld_arr["memb_credit_status"]=0;
	if(isset($_POST['memb_credit_status'])){
		$fld_arr["memb_credit_status"]=1;
	}
	$fld_arr["memb_credit_limit"]=func_read_qs("memb_credit_limit");
	$fld_arr["memb_min_credit_ord_amt"]=func_read_qs("memb_min_credit_ord_amt");
	if($fld_arr["memb_credit_limit"] < $fld_arr["memb_min_credit_ord_amt"]) { 
		$msg_class = "danger";
		$msg = "Error! Credit limit cannot be greater than minimum order amount. Please correct details and try again.";
		goto END;
	}
	$qry = func_update_qry("member_mast",$fld_arr," where memb_id=".$id);
	$result = mysqli_query($con,$qry);
	if($result){
			$msg_class = "info";
			$msg = "The details has been updated successfully";
	}else{
		$msg_class = "danger";
		$msg = "Update failed! Please check details or try again after some time.";
	}	
}

$act = func_read_qs("act");
if($act=="d"){
	if(!mysqli_query($con, "delete from member_mast where memb_id=".func_read_qs("id"))){
		echo("Problem updating database... $qry");
	}else{
		?>
		<script>
			alert("Record Deleted successfully.");
			window.location.href="manage_buyer_credits.php";
		</script>
		<?php
		die("");
	}	
}
END:
if($id<>""){
	$qry = "select * from member_mast where memb_id=".$id;
	if(get_rst($qry, $row)){
		$memb_id = $row["memb_id"];
		$memb_fname = stripcslashes($row["memb_fname"]);
		$memb_credit_status = $row["memb_credit_status"];
		$isChecked = "";
		if($memb_credit_status == 1) $isChecked = "checked";
		$memb_credit_limit = $row["memb_credit_limit"];
		$memb_min_credit_ord_amt = $row["memb_min_credit_ord_amt"];
	}
}
?>

<script type="text/javascript" src="frmCheck.js"></script>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script>
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
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<h2>Edit Buyer Credit Details</h2>
<div id="sup_upd_form">
<?php
	if($msg <> "") { ?>
	<div class="alert alert-<?=$msg_class?>">
		<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
		<?=$msg?>
	</div>
	<?php } ?>
<form name="frm" id="frm" method="post" enctype="multipart/form-data">
	<input type="hidden" id="act" name="act" value="1">
<div class="solidTab panel panel-info">
<div class="panel-heading">Members Details</div>

	<table width="100%" border="0" cellspacing="1" cellpadding="5">	
		<tr>
		<td>Buyer Name</td>
		<td><?=func_var($memb_fname)?></td>
		</tr>
		<tr>
		<td>Credit Status</td>
        
		<td><input type="checkbox" name="memb_credit_status"  value="<?php echo $memb_credit_status;?>" <?php echo $isChecked;?>></td>
		</tr>
		<tr>
		<td>Credit Limit</td>
		<td><input type="text" size="25" maxlength="50" id="100" title="Credit Limit" onkeypress='validate_key(event)' name="memb_credit_limit" value="<?=func_var($memb_credit_limit)?>" class="form-control" style="width:auto;"></td>
		</tr>
				<tr>
		<td>Minimum Credit Order Amount</td>
		<td><input type="text" size="25" maxlength="50" id="100" title="Minimum Credit Order Amount" onkeypress='validate_key(event)' name="memb_min_credit_ord_amt" value="<?=func_var($memb_min_credit_ord_amt)?>" class="form-control" style="width:auto;"></td>
		</tr>
		<tr><td colspan="2"><input type="submit" class="btn btn-warning" value="Save" name="save"></td></tr>
	</table>
</div>
</form>


<?php
require_once("inc_admin_footer.php");
?>

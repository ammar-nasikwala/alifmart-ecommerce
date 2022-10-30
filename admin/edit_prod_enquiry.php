<?php require("inc_admin_header.php");

$id = func_read_qs("id");
if($id<>""){
	get_rst("select * from prod_enquiry where id=0$id",$row);
}

$act = func_read_qs("act");

if($act<>""){
	
	$enq_status= func_read_qs("enq_status");
	if($enq_status."" == ""){
		$enq_status = "Pending";
	}
	execute_qry("update prod_enquiry set enq_status='$enq_status' where id=0$id");
	$v_cancelled = "";
	
	if($v_cancelled == ""){
		js_alert("Enquiry status updated successfully.");	
	}
}

?>
<style>
table#trbg tr:nth-child(even) {
    background-color: #eee;
}
table#trbg tr:nth-child(odd) {
   background-color:#fff;
}
</style>
<form name="frm_enquiry" method="post">
<input type="hidden" name="act" value="1">
<div id="sup_profile" style="font-size: 14px" >	
<div class="solidTab panel panel-info" style="display: block" id="b2">
<div class="panel-heading">Product Enquiry</div>
<table id="trbg" width="100%" border="0" cellspacing="1" cellpadding="5">
	
	<tr>
		<td>Email</h3></td>
		<td></td>
		<td><?=$row["email"]?></td>
		
	</tr>	
	
	<tr>
		<td>Product Details</td>
		<td></td>
		<td><?=stripcslashes($row["prod_name"])?></td>
	</tr>
	
	<?php if($row["image_path1"] <> "" && $row["image_path2"] <> ""){?>
	<tr>
		<td>Product Image1</td>
		<td></td>
		<td><button type="button" class="btn btn-warning" data-toggle="modal" data-target="#myModal1">Show Image</button></td>
	</tr>
	
	<tr>
		<td>Product Image2</td>
		<td></td>
		<td><button type="button" class="btn btn-warning" data-toggle="modal" data-target="#myModal2">Show Image</button></td>
	</tr>
	<?php }else if($row["image_path1"] <> ""){?>
		<tr>
		<td>Product Image</td>
		<td></td>
		<td><button type="button" class="btn btn-warning" data-toggle="modal" data-target="#myModal1">Show Image</button></td>
	</tr>
	<?php }else{?>
		<tr>
		<td>Product Image</td>
		<td></td>
		<td><button type="button" class="btn btn-warning" data-toggle="modal" data-target="#myModal2">Show Image</button></td>
	</tr>
	<?php }?>
	<tr>
		<td>Enquiry Status</td>
		<td></td>
		<td>
		<select name="enq_status" id="enq_status" title="Enquiry status">
			<?php func_option("Pending","Pending",func_var($enq_status))?>
			<?php func_option("Processed", "Processed" ,func_var($enq_status))?>
		</select>
		</td>
	</tr>
</table>
</div>
</div>
<table cellspacing="1" cellpadding="5" align="center">
<tr>
		<th colspan="10" id="centered">
		<input type="button" class="btn btn-warning" value="Back" name="Back" onClick="javascript: window.location.href='manage_prod_enquiry.php';">
		&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="submit" class="btn btn-warning" value="Save" name="submit">		
		</td>
	</tr>
</table>

</form>
<?php
require_once("inc_admin_footer.php");
?>
<div id="myModal1" class="modal fade" role="dialog">
  <div class="modal-dialog" style="width:900px;">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-body" style=" overflow=scroll;">
		<center><img id="canvas" src = "<?="/".$row["image_path1"]?>" width="100%" /></center>
	  </div>
	  <div class="modal-footer">
        <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
	  </div>
    </div>
  </div>
</div>

<div id="myModal2" class="modal fade" role="dialog">
  <div class="modal-dialog" style="width:900px;">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-body" style=" overflow=scroll;">
		<center><img id="canvas" src = "<?="/".$row["image_path2"]?>" width="100%" /></center>
	  </div>
	  <div class="modal-footer">
        <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
	  </div>
    </div>
  </div>
</div>

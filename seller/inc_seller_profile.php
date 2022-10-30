<?php
$msg="";
error_reporting(E_ERROR | E_PARSE);
    	
$act = "";
$sup_id = 0;
$sup_shop_act_license="";
$sup_bk_can_chk="";
$sup_logo="";
	
if(isset($_POST["act"])) $act=$_POST["act"];
if(isset($_SESSION["sup_id"])) $sup_id=$_SESSION["sup_id"];		
?>
<script>

window.onload = function() {
      $("#b1").show();
	  $("#b2").hide();
	  $("#b3").hide();
	  $("#b4").hide();
    };
$(function () {
     $("#bt1").click(function () {
		$("#b1").show();
		$("#b2").hide();
	    $("#b3").hide();
	    $("#b4").hide();
            
        });
    });
$(function () {
     $("#bt2").click(function () {
		$("#b2").show();
		$("#b1").hide();
	    $("#b3").hide();
	    $("#b4").hide();
        });
    });
$(function () {
     $("#bt3").click(function () {	 
		$("#b3").show();
		$("#b2").hide();
	    $("#b1").hide();
	    $("#b4").hide();
            
        });
    });
$(function () {
     $("#bt4").click(function () {
		$("#b4").show();
		$("#b2").hide();
	    $("#b3").hide();
	    $("#b1").hide();
            
        });
    });	
	
</script>
<style>
#sup_profile{
	padding-left: 5px;
	padding-right: 5px;
	padding-top: 5px;
}

.solidTab {
  background-color: #fff;
  line-height: 1.15em;
  word-wrap: break-word;
  margin-bottom: 12px;
  margin-top: 12px;
  /*box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);*/
}


table#trbg tr:nth-child(even) {
    background-color: #eee;
}
table#trbg tr:nth-child(odd) {
   background-color:#fff;
}

</style>

<?php
require("inc_chk_session.php");
if($msg <> "") { ?>
<div class="alert alert-info">
	<?=$msg?>
</div>
<?php } ?>
<center>
	<div class="glossymenu" style="width:auto; display:inline-block; margin-top:20px;">
		<ul id="profilemenu" class="nav navbar-nav">
			<li><a id="bt1" class="menuitem" href="#" >Seller Details</a></li>
			<li><a id="bt2" class="menuitem" href="#" >My Documents</a></li>
			<li><a id="bt3" class="menuitem" href="#" >Bank Details</a></li>
			<li><a id="bt4" class="menuitem" href="#" >Billing Details</a></li>
		</ul>
	</div>
</center>


<div id="sup_profile" >	
<div class="solidTab panel panel-info" style="display: block" id="b1">
<div class="panel-heading">Seller Details</div>
	<table id="trbg" width="100%" border="0" cellspacing="1" cellpadding="5" >
		<?php if(true){ 
		$rst = mysqli_query($con,"select * from sup_mast where sup_id=$sup_id");
		$row = mysqli_fetch_assoc($rst);
		?>
		<tr>
			<td>Establishment Name:</td>
			<td><b><?php echo $row["sup_company"]; ?> </b></td>
		</tr>
		<tr>
			<td>Type of Business:</td>
			<td><b>
			<?php echo $row["sup_business_type"];?>
			</b></td>
		</tr>
		<tr>
			<td>Owner Name:</td>
			<td><b><?php echo $row["sup_contact_name"]; ?></b></td>
		</tr>
		<tr>	
			<td>Authorised/Contact Person Name:</td>
			<td><b><?php echo $row["sup_contact_per_name"]; ?></b></td>
		</tr>
		<tr>
			<td>Email:</td>
			<td><b><?php echo $row["sup_email"]; ?></b></td>
		</tr>
		<?php if(func_var($admin_login)=="1"){ ?>
		<tr>
			<td>Seller Token:</td>
			<td><b><?php echo $row["sup_seller_token"]; ?></b></td>
		</tr>
		<?php }
		} ?>
	</table>
</div>

<div class="solidTab panel panel-info" style="display: block" id="b2">
<div class="panel-heading">My Documents</div>
<table id="trbg" width="100%" border="0" cellspacing="1" cellpadding="5">
	
	<tr>
		<td>Shop Act License:</h3></td>
		<td></td>
		<td><button type="button" class="btn btn-warning" data-toggle="modal" data-target="#myModal1">
		Show Document</button></td>
	</tr>	
	
	<tr>
		<td>Companies & Firm Income Tax number (PAN):</td>
		<td><b><?php echo $row["sup_pan"]; ?></b></td>
		<td><button type="button" class="btn btn-warning" data-toggle="modal" data-target="#myModal2">Show Document</button></td>
	</tr>
	<tr>
		<td>Goods & Service Tax number (GST):</td>
		<td><b><?php echo $row["sup_vat"]; ?> </b></td>	
		<td><button type="button" class="btn btn-warning" data-toggle="modal" data-target="#myModal3">Show Document</button></td>
	<tr>
	</tr>
	<!--
	<tr>
		<td>Central Sales Tax Account number (CST):</td>
		<td><b><?php echo $row["sup_cstn"]; ?></b></td>	
		<td><button type="button" class="btn btn-warning" data-toggle="modal" data-target="#myModal4">Show Document</button></td>
	</tr>
	-->
</table>
</div>

<div class="solidTab panel panel-info" style="display: block" id="b3">
<div class="panel-heading">Bank Details</div>
<table id="trbg" width="100%" border="0" cellspacing="1" cellpadding="5">
	
		<td>Account Number:</td>
		<td><b><?php echo $row["sup_bk_acc"]; ?></b></td>
	</tr>
	<tr>
		<td>IFSC Code:</td>
		<td><b><?php echo $row["sup_bk_ifsc"]; ?></b></td>
	</tr>
	<tr>
		<td>Bank Name:</td>
		<td><b><?php echo $row["sup_bk_name"]; ?></b></td>
	</tr>
	<tr>
		<td>Branch Name:</td>
		<td><b><?php echo $row["sup_bk_brname"]; ?></b></td>	
	</tr>
	
	<tr>
		<td>Cancelled Check:</h3></td>
		<td><button type="button" class="btn btn-warning" data-toggle="modal" data-target="#myModal5">Show Document</button></td>
		<td></td>
		<td></td>
	</tr>
	
</table>
</div>

<div class="solidTab panel panel-info" style="display: block" id="b4">
<div class="panel-heading">Billing Details</div>
<?php
	$rst = mysqli_query($con,"select * from sup_ext_addr where sup_id=$sup_id LIMIT 1");
	$row2 = mysqli_fetch_assoc($rst);
?>
<table id="trbg" width="100%" border="0" cellspacing="1" cellpadding="5">
	
	<tr>
		<td>Address:</td>
		<td><b><?php echo $row2["sup_ext_address"]; ?></b></td>		
	</tr>
	<tr>
		<td>State:</td>
		<td><b> <?php echo $row2["sup_ext_state"]; ?></b></td>
	</tr>
	<tr>
		<td>City:</td>
		<td><b><?php echo $row2["sup_ext_city"]; ?> </b></td>
	</tr>
	<tr>
		<td>Pincode:</td>
		<td><b><?php echo $row2["sup_ext_pincode"]; ?></b></td>
	</tr>
	
	<tr>
		<td>Mobile:</td>	
		<td><b><?php echo $row["sup_contact_no"]; ?> </b></td>
	</tr>
	<tr>	
		<td>Alternate Contact Number:</td>	
		<td><b><?php echo $row["sup_alt_contact_no"]; ?> </b></td>
	</tr>
</table>
</div>
</div>

<form name="frm" method="post">
<br><br><center>
<?php if(func_var($admin_login)=="1" and func_var($not_show_btn)!= "1"){ ?>&nbsp; &nbsp; &nbsp; &nbsp;<input type="hidden" name="act" value="B" > 

<input type="hidden" name="sup_city" value=<?php echo $row2["sup_ext_city"]; ?>>
<input type="hidden" name="sup_contact_name" value=<?php echo $row["sup_contact_name"]; ?>>
<input type="hidden" name="sup_email" value=<?php echo $row["sup_email"]; ?>>
<input type="hidden" name="sup_contact_no" value=<?php echo $row2["sup_ext_contact_no"]; ?>>

<input type="submit" class="btn btn-warning" value="Generate Code" id=button2 name=button2 style="margin-right:10px;"><?php } ?>
</center><br>
</form>

<!-- Modal -->
<div id="myModal1" class="modal fade" role="dialog">
  <div class="modal-dialog" style="width:900px;">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-body" style=" overflow=scroll;">
		<button class="btn btn-info" id="counterclockwise" onclick="javascript: rotate_counter_clockwise('canvas');">
		<span class="color-white glyphicon glyphicon-share-alt gly-flip-horizontal"></span> Rotate left</button>
		<button class="btn btn-info" style="float:right;" id="clockwise" onclick="javascript: rotate_clockwise('canvas');">Rotate right 
		<span class="color-white glyphicon glyphicon-share-alt"></span></button>
		<?php if($row["sup_shop_act_license"] == ""){ ?>
		<center><h1>No document image to display</h1></center>
		<?php }else{ ?>	
		<img id="canvas" src="<?=show_img($row["sup_shop_act_license"]);?>" width="100%">
		<?php } ?>
	  </div>
	  <div class="modal-footer">
        <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
	  </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div id="myModal2" class="modal fade" role="dialog">
  <div class="modal-dialog" style="width:900px;">
    <!-- Modal content-->
    <div class="modal-content" >
      <div class="modal-body" style=" overflow=scroll;">
	  <center><h1 class="modal-heading">Companies & Firm Income Tax number (PAN): <?=$row["sup_pan"];?></h1></center>
	  <button class="btn btn-info" id="counterclockwise" onclick="javascript: rotate_counter_clockwise('canvas1');">
		<span class="color-white glyphicon glyphicon-share-alt gly-flip-horizontal"></span> Rotate left</button>
		<button class="btn btn-info" style="float:right;" id="clockwise" onclick="javascript: rotate_clockwise('canvas1');">Rotate right 
		<span class="color-white glyphicon glyphicon-share-alt"></span></button>
		<?php if($row["sup_pan_doc"] == ""){ ?>
		<center><h1>No document image to display</h1></center>
		<?php }else{ ?>			
		<img id="canvas1" src="<?=show_img($row["sup_pan_doc"]);?>" width="100%">
		<?php } ?>
	  </div>
	  <div class="modal-footer">
        <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
	  </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div id="myModal3" class="modal fade" role="dialog">
  <div class="modal-dialog" style="width:900px;">
    <!-- Modal content-->
    <div class="modal-content" >
      <div class="modal-body" style=" overflow=scroll;">
	  <center><h1 class="modal-heading">Goods & Service Tax number (GST): <?=$row["sup_vat"];?></h1></center>
	  <button class="btn btn-info" id="counterclockwise" onclick="javascript: rotate_counter_clockwise('canvas2');">
		<span class="color-white glyphicon glyphicon-share-alt gly-flip-horizontal"></span> Rotate left</button>
		<button class="btn btn-info" style="float:right;" id="clockwise" onclick="javascript: rotate_clockwise('canvas2');">Rotate right 
		<span class="color-white glyphicon glyphicon-share-alt"></span></button>
		<?php if($row["sup_vat_doc"] == ""){ ?>
		<center><h1>No document image to display</h1></center>
		<?php }else{ ?>
		<img id="canvas2" src="<?=show_img($row["sup_vat_doc"]);?>" width="100%">
		<?php } ?>
	  </div>
	  <div class="modal-footer">
        <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
	  </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div id="myModal4" class="modal fade" role="dialog">
  <div class="modal-dialog" style="width:900px;">
    <!-- Modal content-->
    <div class="modal-content" >
      <div class="modal-body" style="overflow=scroll;">
	  <center><h1 class="modal-heading">Central Sales Tax Account number (CST): <?=$row["sup_cstn"];?></h1></center>
	  <button class="btn btn-info" id="counterclockwise" onclick="javascript: rotate_counter_clockwise('canvas3');">
		<span class="color-white glyphicon glyphicon-share-alt gly-flip-horizontal"></span> Rotate left</button>
		<button class="btn btn-info" style="float:right;" id="clockwise" onclick="javascript: rotate_clockwise('canvas3');">Rotate right 
		<span class="color-white glyphicon glyphicon-share-alt"></span></button>
	    <?php if($row["sup_cst_doc"] == ""){ ?>
		<center><h1>No document image to display</h1></center>
		<?php }else{ ?>
		<img id="canvas3" src="<?=show_img($row["sup_cst_doc"]);?>" width="100%">		
		<?php } ?>
	  </div>
	  <div class="modal-footer">
        <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
	  </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div id="myModal5" class="modal fade" role="dialog">
  <div class="modal-dialog" style="width:900px;">
    <!-- Modal content-->
    <div class="modal-content" >
      <div class="modal-body" style="overflow=scroll;">
	  <center>
	  <table id="disp_border">
		<tr>
			<td><h1>Account Number: <?=$row["sup_bk_acc"];?></h1></td>
			<td style="padding-left:20px;"><h1>IFSC Code: <?=$row["sup_bk_ifsc"];?></h1></td>
		</tr>
		<tr style="border-bottom:5px solid #DFDFDF">
			<td><h1> Bank Name: <?=$row["sup_bk_name"];?></h1></td>
			<td style="padding-left:20px;"><h1>Branch Name: <?=$row["sup_bk_brname"];?></h1></td>
		</tr>
		</table>
		</center>
		<button class="btn btn-info" id="counterclockwise" onclick="javascript: rotate_counter_clockwise('canvas4');">
		<span class="color-white glyphicon glyphicon-share-alt gly-flip-horizontal"></span> Rotate left</button>
		<button class="btn btn-info" style="float:right;" id="clockwise" onclick="javascript: rotate_clockwise('canvas4');">Rotate right 
		<span class="color-white glyphicon glyphicon-share-alt"></span></button>
		<?php if($row["sup_bk_can_chk"] == ""){ ?>
		<center><h1>No document image to display</h1></center>
		<?php }else{ ?>
		<img id="canvas4" src="<?=show_img($row["sup_bk_can_chk"]);?>" width="100%">		
	    <?php } ?>
	  </div>
	  <div class="modal-footer">
        <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
	  </div>
    </div>
  </div>
</div>
<style>
.gly-flip-horizontal {
  filter: progid:DXImageTransform.Microsoft.BasicImage(rotation=0, mirror=1);
  -webkit-transform: scale(-1, 1);
  -moz-transform: scale(-1, 1);
  -ms-transform: scale(-1, 1);
  -o-transform: scale(-1, 1);
  transform: scale(-1, 1);
}

.gly-rotate-90 {
  filter: progid:DXImageTransform.Microsoft.BasicImage(rotation=1);
  -webkit-transform: rotate(90deg);
  -moz-transform: rotate(90deg);
  -ms-transform: rotate(90deg);
  -o-transform: rotate(90deg);
  transform: rotate(90deg);
}
.gly-rotate-180 {
  filter: progid:DXImageTransform.Microsoft.BasicImage(rotation=2);
  -webkit-transform: rotate(180deg);
  -moz-transform: rotate(180deg);
  -ms-transform: rotate(180deg);
  -o-transform: rotate(180deg);
  transform: rotate(180deg);
}
.gly-rotate-270 {
  filter: progid:DXImageTransform.Microsoft.BasicImage(rotation=3);
  -webkit-transform: rotate(270deg);
  -moz-transform: rotate(270deg);
  -ms-transform: rotate(270deg);
  -o-transform: rotate(270deg);
  transform: rotate(270deg);
}

	#disp_border h1{
		border-bottom: none;
	}
</style>
<script>
var rc = 0;
var rcc = 0;

function rotate_clockwise(obj_id){
	var obj = document.getElementById(obj_id);
	rc = ++rc % 4;
	switch(rc){
		case 1:	obj.className = "gly-rotate-90"; rcc = 3; break;
		case 2: obj.className = "gly-rotate-180"; rcc = 2; break;
		case 3: obj.className = "gly-rotate-270"; rcc = 1;  break;
		default: obj.className = ""; rcc = 0; break;
	}
}
function rotate_counter_clockwise(obj_id){
	var obj = document.getElementById(obj_id);
	rcc = ++rcc % 4;
	switch(rcc){
		case 1:	obj.className = "gly-rotate-270"; rc = 3; break;
		case 2: obj.className = "gly-rotate-180"; rc = 2; break;
		case 3: obj.className = "gly-rotate-90"; rc = 1; break;
		default: obj.className = ""; rc = 0; break;
	}
}
</script>
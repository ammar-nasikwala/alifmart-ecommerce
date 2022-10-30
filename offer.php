<?php
require("inc_init.php");
get_rst("select count(*) as tot from offer_coupon where active = 1 and show_onscreen = 1",$row_c);
$total = $row_c["tot"];
$row_no = ceil($total/3);

$coupon_code = array();
$max_dis_val = array();
$valid_till = array();
$ord_value = array();
$x=0;
get_rst("select coupon_code, max_discount_value, valid_till, min_ord_value from offer_coupon where show_onscreen = 1 and active = 1", $row,$rst); // for showing offer data
do{
	$coupon_code[$x] = $row["coupon_code"];
	$max_dis_val[$x] = $row["max_discount_value"];
	$valid_till[$x] = $row["valid_till"];
	$ord_value[$x] = $row["min_ord_value"];
	$x++;
}while($row = mysqli_fetch_assoc($rst));
$count = 0;

if($total<3){		//to get first strip size
	$col=$total;
}else{
	$col=3;
}

?>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
<title>Company-Name Offers</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="styles/styles.css" rel="stylesheet" type="text/css" />
<script src="scripts/scripts.js" type="text/javascript"></script>

<body>
<?php
require("header.php");
?>
<div id="contentwrapper">
<div id="contentcolumn">
<div class="center-panel">
	<div class="you-are-here">
		<p align="left">
			<a name="here"></a>
			YOU ARE HERE:<a href="index.php" title="">Home</a> | <?=breadcrumbs()?><span class="you-are-normal">Offers</span>
		</p>
	</div>
	<div align="center"><h1>Company-Name Offers</h1></div>
	<table  width="100%">
	<?php
		for($i=1;$i<=$row_no;$i++){
			$col_r = $total-$count;
			if($col_r<3){		//to get first strip size
				$col=$col_r;
			}else{
		$col=3;
	}?>
	<tr>
	<?php 
		for($j=0;$j<$col;$j++){
	?>
	<td class="offer_box">
	<center>
		<div class="offer_sticker" id="offer_sticker_<?=$count?>" data-toggle="modal" data-target="#offer_terms_<?=$count?>">
			<div height="80px"><label style="color:#6190a1;">Save upto</label> <label style="color: #eb9316;"><?=$max_dis_val[$count]?></label></div>
			 <img src="images/logo-app.gif" width="100px" height="100px"/>
				<div height="80px"><label style="color:#6190a1;">Use Code:</label> <label style="color: #eb9316;"><?=$coupon_code[$count]?></label></div>
		</div>
	</center>
</td>
		<?php 
			$count++;
		}?>
</tr>
		<?php }?>

</table>
<?php for($i=0; $i<$count; $i++){?>
<div id = "offer_terms_<?=$i?>" class="modal fade" role="dialog">
	<div class="modal-dialog" style="width:500px; top:200px;">
		<!-- Modal content-->
		<div class="modal-content" style="height: auto;">
			<div class="modal-header" style="padding-bottom:0px;">
			<img src="images/close_header.gif" width="12px" height="12px" alt="close" align="right" data-dismiss="modal"/>
				<center><h2 class="color-amber"><b style="font-size: 20px;">Offer Description</b></h2></center>
			</div>
			<div class="modal-body" style="padding-top:5px;">
				<ul class="offer_terms_ul">
					<li> Applicable on minimum purchase of Rs.<?=$ord_value[$i]?></li>
					<li> Maximum discount applicable is Rs.<?=$max_dis_val[$i]?></li>
					<li> Offer applicable once per user</li>
					<li> This offer cannot be combined with any other offer</li>
					<li> Offer valid till <?=$valid_till[$i]?></li>
				</ul>
			</div>
		</div>
	</div>
</div>
<?php }?>
</div>
</div>
</div>

<?php 
require("left.php");
require("footer.php");
?>
</body>
<script src="scripts/chat.js" type="text/javascript"></script>
</html>
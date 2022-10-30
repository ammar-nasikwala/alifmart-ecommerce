<?php session_start();
require("../lib/inc_library.php");
require("inc_chk_session.php");
$msg="";
$sup_id = $_SESSION["sup_id"];

if(isset($_GET['mou'])){
	if($_GET['mou'] == "1"){
		$rst = mysqli_query($con,"update sup_mast set sup_mou_accept=1 where sup_id=$sup_id");
		if($rst){
			$msg = "Congratulations! Your seller account is now activated.";
		}else{
			$msg = "Sorry, we have encountered a problem. Please try again after sometime.";
		}
	}
}
?>
<script> 
function mou_dec() {
	alert('You have selected to decline the MoU agreement. If you have any concerns related the MoU, please contact support@Company-Name.com.')
	window.location = "../seller/seller_profile.php";
}
</script>
	

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo $company_title ?> | Seller Zone</title>
<link type="text/css" rel="stylesheet" href="css/main.css" />
<link type="text/css" rel="stylesheet" href="css/collapse_menu.css" />
<link type="text/css" rel="stylesheet" href="css/tooltip.css" />
<script type="text/javascript" src="js/ddaccordion.js"></script>
<script type="text/javascript" src="js/collapse_menu.js"></script>
<script type="text/javascript" src="js/logohover.js"></script>
<script type="text/javascript" src="js/tooltip.js"></script>

</head>
<body>
<div id="maincontainer" class="table-responsive">
<table class="tbl_dash_main">

<tr>
<td colspan="2">
<?php require("inc_top-menu.php") ?>


<!--div id="panels"!-->
<tr>
<td align="left" width="210px">
<?php require("inc_left-menu.php") ?>
</td>
<td align="left">
<div id="centerpanel">
<div id="contentarea">
<h2>Welcome to Company-Name Seller Zone</h2>
<!--<span style="float:right">Registration status: Pending Approval</span> -->
<div id="buttons">
</div>
<?php
if($msg <> ""){?>
<div class="alert alert-info">
    <?=$msg?>
</div>
<?php }
get_rst("select sup_mou_accept from sup_mast where sup_id=0$sup_id",$row);
if($row["sup_mou_accept"] <> 1)
{ ?>
<p style="float:right" title="Your are a registered seller, but your account activation is pending.">Status: <span style="color:#eb9316">Registered</span></p>
<center>
<table>
	<tr>
		<td>
			<a class="btn btn-warning simple" href="mainmenu.php?mou=1">Accept</a>
		</td>
		<td>
			<a class="btn btn-warning simple" onclick="mou_dec()">Decline</a>
		</td>
	</tr>
</table>
</center>
<iframe src="../extras/docs/Company-Name_MoU.pdf" width="100%" style="height:100%"></iframe>

<?php }else{
	if($sup_id=="") $sup_id = "0";
	get_rst("select count(0) as tot from ord_summary where delivery_status='Pending' and pay_status='Paid' and sup_id=$sup_id and buyer_action is null",$row);
	$ord_pending = $row["tot"];
	get_rst("select count(0) as tot from ord_summary where sup_id=$sup_id",$row);
	$ord_total = $row["tot"];
	get_rst("select count(0) as tot from prod_mast where prod_status = 1 and prod_id in (select prod_id from prod_sup where sup_id=$sup_id)",$row);
	$prod_total = $row["tot"];
	get_rst("select count(0) as tot from prod_mast where prod_status<>1 and prod_id in (select prod_id from prod_sup where sup_id=$sup_id)",$row);
	$prod_total_inactive = $row["tot"];
	?>
	<p style="float:right" title="Your account status is activated.">Status: <span style="color:#1E9BDD">Activated</span></p>
	<table border="0" width="100%" class="tbl_dash_main">
	<tr>
		<td width="33%">
			<table class="tbl_dash">
				<tr>
					<th>&nbsp;<span class="glyphicon glyphicon-gift"></span> Orders</th>
				</tr>
				<tr>
					<td><?=dash_new($ord_pending)?> <a href="order_list.php?ds=0">Orders awaiting dispatch</a></td>
				</tr><tr>
					<td><?=dash_total($ord_total)?> <a href="order_list.php?ds=1">Total Orders</a></td>
				</tr>
			</table>
		</td>
		<td width="33%">
			<table class="tbl_dash">
				<tr>
					<th>&nbsp;<span class="glyphicon glyphicon-wrench"></span> Products</th>
				</tr><tr>
					<td><?=dash_total($prod_total)?> <a href="manage_prods.php?active=1">Total Active Products</a></td>
				</tr>
				<tr>
					<td><?=dash_total($prod_total_inactive)?> <a href="manage_prods.php?active=0">Total In-Active Products</a></td>
				</tr>
			</table>
		</td>
		<td width="33%">
			<table class="tbl_dash">
				<tr>
					<th>&nbsp;<span class="glyphicon glyphicon-star-empty"></span> Top Selling Products</th>
				</tr>
				<?php if(get_rst("select prod_id,prod_name,prod_purchased from prod_mast where prod_purchased is not null and prod_id in (select prod_id from prod_sup where sup_id=$sup_id) order by prod_purchased desc LIMIT 0 , 5",$row,$rst)){
					do{
						?>
							<tr><td><?=$row["prod_name"]." [".$row["prod_purchased"]." orders]"?></td></tr>
						<?php
					}while($row = mysqli_fetch_array($rst));
				}?>

			</table>
		</td>
	</tr>

	<tr>
		<td>
		</td>
		<td>
		</td>
		<td>
		</td>
		
	</tr>
	</table>
<?php } ?>  
</div>
</div>
</td>
</tr>
<tr>
<td colspan="2">
<?php require("inc_footer.php"); ?>
</td>
</table>

</body>
</html>
<?php
function dash_new($val){
?>
	<span class="dash_new"><?=$val?></span>
	<?php
}
function dash_total($val){
	?>
	<span class="dash_total"><?=$val?></span>
	<?php
}
?>

<?php require("../lib/inc_close_connection.php");?>
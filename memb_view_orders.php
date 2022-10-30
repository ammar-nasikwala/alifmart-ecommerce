<?php

require("inc_init.php");

if(($_SESSION["memb_id"]) == ""){ ?>
<script>
		alert("Your session timed out. Please login again.");
		window.location.href="index.php";
</script>
<?php } 

$sql="select os.cart_id";
$sql = $sql.",ord_no as 'Order No.'";
$sql = $sql.",ord_date as 'Date'";
$sql = $sql.",(select sum((cart_price_tax * cart_qty) + ship_amt) as ord_total from ord_items where cart_id=os.cart_id) as 'Order Value'";
$sql = $sql.",sum(item_count) as 'Item Count'";
$sql = $sql.",pay_method as 'Payment Type'";
$sql = $sql.",pay_status as 'Payment Status'";
$sql = $sql.",delivery_status as 'Delivery Status'";
$sql = $sql." from ord_summary os inner join ord_details od on os.cart_id=od.cart_id and os.user_id=od.memb_id";

$sql_where = " where user_id=".$_SESSION["memb_id"];

$sql = $sql.$sql_where;
$sql = $sql." group by ord_id order by ord_id desc ";

?>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
<title>Company-Name - Track Orders</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta name="DESCRIPTION" content="<?=$cms_meta_key?>"/>
<meta name="KEYWORDS" content="<?=$cms_meta_desc?>"/>
<link href="styles/styles.css" rel="stylesheet" type="text/css" />
<link href="styles/bannerstyle.css" rel="stylesheet" type="text/css" />
<script src="scripts/scripts.js" type="text/javascript"></script>

</head>
<body>
<?php require("header.php"); ?>
<div id="contentwrapper">
<div id="contentcolumn">
<div class="center-panel">
	<div class="you-are-here">
		<p align="left">
			YOU ARE HERE:<a href="index.php" title="">Home</a> | <span class="you-are-normal"><?=$cms_title?></span>
		</p>
	</div>
	
	<br>
	
	<div id="memb_ord_list">
		<?php create_list($sql,"memb_order_details.php",20,"tbl_pages",5,"1","list");?>
	</div>
</div>
</div>
</div>

<?php
	require("left.php");
	require("footer.php");
?>

</div>
</div>

</body>
<script src="scripts/chat.js" type="text/javascript"></script>
</html>
	

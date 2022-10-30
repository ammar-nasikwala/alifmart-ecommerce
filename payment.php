<?php
session_start();
require("inc_init.php");
if(func_read_qs("merchantIdentifier").""==""){
?>
	<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
	<head>
	<title><?=$cms_title?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<meta name="DESCRIPTION" content="<?=$cms_meta_key?>"/>
	<meta name="KEYWORDS" content="<?=$cms_meta_desc?>"/>
	<link href="styles/styles.css" rel="stylesheet" type="text/css" />
	<script src="scripts/scripts.js" type="text/javascript"></script>

	<script type="text/javascript">
	function js_prev(){
		window.location.href = "ord_summary.php"
	}

	function js_next(){
		document.frmCheckout.submit();
	}

	</script>
	</head>
	<body>
<?php
require("header.php");
?>
	<div id="contentwrapper">
	<div id="contentcolumn">
	<div class="center-panel">
		<div class="you-are-here">
			<p align="left">
				YOU ARE HERE:<a href="index.php" title="">Home</a> | <span class="you-are-normal">Payment</span>
			</p>
		</div>
		
		<br>
		<center>
		<table border="1" width="90%" class="table_checkout boxed-shadow">
			<tr>
				<th>1. Your Details</th>
				<th>2. Order Summary</th>
				<th>3. Payment</th>
				<td>4. Order Confirmation</td>			
			</tr>
		</table>
		</center>
		<?php
		$memb_id = $_SESSION["memb_id"];
		if(get_rst("select pay_method from cart_summary where user_id=$memb_id",$row)){
			$pay_method=$row["pay_method"];
			
			switch($pay_method){
				case "CC":
					pay_by_cc();
					break;
					
				case "CCZ":
					pay_by_ccZ();
					break;

				default:
					$response = save_order($pay_method);
					if($response == "success"){
						header('location: ord_confirmation.php');
						js_redirect("ord_confirmation.php");
					}else{
						$_SESSION["error_msg"] = $response;
						header('location: checkout.php');
						js_redirect("checkout.php");	
					}
					break;

			}

		}else{
			header('location: basket.php');	
			js_redirect("basket.php");
		}
	?>
		
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
	</html>
<?php }else{  include('zaakpay_checksum.php'); 
	$secret = 'f990ccc164ef4e09854e445104d3c359';

	$all = Checksum::getAllParams();
	error_log("AllParams:".$all);
	error_log("Secret Key : ".$secret);
	$checksum = Checksum::calculateChecksum($secret, $all);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Zaakpay</title>
<script type="text/javascript">
function submitForm(){
			var form = document.forms[0];
			form.submit();
		}
</script>
</head>
<body onload="javascript:submitForm()">
<center>
<table width="500px;">
	<tr>
		<td align="center" valign="middle">Do Not Refresh or Press Back <br/> Redirecting to Zaakpay</td>
	</tr>
	<tr>
		<td align="center" valign="middle">
			<form action="https://api.zaakpay.com/transact" method="post">
				<?php
				Checksum::outputForm($checksum);
				?>
			</form>
		</td>

	</tr>

</table>

</center>
</body>
</html>

<?php } ?>

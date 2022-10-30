<?php 
require_once("inc_init.php");

//filer access from blocked countries ips
if ($_SERVER['HTTP_X_FORWARDED_FOR']){
	$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
}else{
	$ip   = $_SERVER['REMOTE_ADDR'];
}
$country_code=iptocountry($ip);
if($country_code == "RU" || $country_code == "CN" || $country_code == "AF" || $country_code == "KZ" || $country_code == "UZ" || $country_code == "UA"){
	die("Page not available.");
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo $company_title ?> - Register Seller</title>
<meta name="DESCRIPTION" content="Company-Name Seller Registration"/>
<meta name="KEYWORDS" content="Sell On Company-Name, Seller Registration "/>

<meta property="og:url" content="http://www.Company-Name.com/seller.php" />
<meta property="og:image" content="http://www.Company-Name.com/images/seller.png"/>  
<meta property="og:title" content="Company-Name - Seller Registration"/>  
<meta property="og:description" content="Register on Company-Name to give your business a whole new dimension."/>

<link href="styles/styles.css" rel="stylesheet" type="text/css" />
<link href="styles/seller.css" rel="stylesheet" type="text/css" />
<script src="scripts/scripts.js" type="text/javascript"></script>
<script src="scripts/seller.js" type="text/javascript"></script>
<script type="text/javascript" src="scripts/animatedcollapse.js"></script>
</head>


<?php
$s_flag = "";
$msg ="";
$sup_business_type = "";
$sms_verify_flag = false;

if(func_read_qs("hdnsubmit") == "y"){	
	
	$sup_company = func_read_qs("sup_company");
	$sup_contact_name = func_read_qs("sup_contact_name");
	$sup_business_type = func_read_qs("sup_business_type");
	$sup_email = func_read_qs("sup_email");
	$sup_contact_per_name = func_read_qs("sup_contact_per_name");
	$sup_contact_no = func_read_qs("sup_contact_no");
	$sup_pwd = func_read_qs("sup_pwd");
	$sup_cpwd = func_read_qs("sup_cpwd");
	//$sup_mou_accept = 0;
	
	$rule = "";
	$rule = "sup_email|c|Email^";
	$rule = "sup_email|e|Email^";
	$rule = $rule."sup_pwd|c|Password^";
	$rule = $rule."sup_pwd|p|Password^";
	$rule = $rule."sup_cpwd|c|Confirm Password^";
	$rule = $rule."sup_cpwd|m|Confirm Password^";
	$rule = $rule."sup_contact_name|c|First Name^";
	$rule = $rule."sup_company|c|Company Name^";
	$rule = $rule."sup_business_type|c|Business Type^";
	//$rule = $rule."sup_pincode|c|Post Code^";
	$rule = $rule."sup_contact_no|c|Mobile^";
    $msg = validate($rule);
	if($sup_pwd <> $sup_cpwd){
		$msg = $msg."<br>Password and Confirm Password should match.";
	}
	
	//check captach confirmation
	if($_POST['captcha'] != $_SESSION['digit']){
		$msg = "Please enter valid CAPTCHA code";
	}elseif($msg == ""){
		// encrypted email+timestamp to generate email activation code
		$sup_activation=md5($sup_email.time()); 
		
		// 4 Digit random number code for sms verification.
		$sms_verify_code = mt_rand(1000, 9999);		
		
		$fld_arr = array();
		if($sup_company == "google" || $sup_company == "Google"){ goto END; }
		$fld_arr["sup_company"] = $sup_company;
		$fld_arr["sup_business_type"] = $sup_business_type;
		$fld_arr["sup_contact_name"] = $sup_contact_name;
		$fld_arr["sup_contact_per_name"] = $sup_contact_per_name;
		$fld_arr["sup_email"] = $sup_email;
		$fld_arr["sup_contact_no"] = $sup_contact_no;
		$fld_arr["sup_pwd"] = $sup_pwd;
		$fld_arr["sup_active_status"] = 0;
		$fld_arr["sup_activation"] = $sup_activation;
		$fld_arr["sup_mou_accept"] = 0;
		$fld_arr["sms_verify_code"] = $sms_verify_code;
		$fld_arr["sms_verify_status"] = 0;
		$fld_arr["sup_lmd"] = 1;
		execute_qry("delete from sup_mast where sup_email='$sup_email' and sup_delete_account=1");
		$qry = func_insert_qry("sup_mast",$fld_arr);
		$result=mysqli_query($con, $qry);
		if (mysqli_errno($con) <>0) {
			if (mysqli_errno($con) == 1062) {
				$msg = "This email has already been registered.";
			}else{
				$msg = mysqli_error($con);
			}
		}else{
		
			//Sending confirmation SMS
			$sms_msg = "Hi ".$sup_company.", Your mobile verification code is ".$sms_verify_code.". Kindly enter this code in the email activation page to enable your account for transactions.";			
			$output=send_sms($sup_contact_no,$sms_msg);
			if($output == "202"){
				$msg = "There is a problem verifying your Mobile number. Please check the number or try again later.";
				goto END;
			}
			
			// sending verification email
			$to=$sup_email;
			$subject="Company-Name-seller email verification";
			
			$mail_fld_arr = array();
			$mail_fld_arr["sup_contact_name"] = $sup_company;
			$mail_fld_arr["sup_email"] = $sup_email;
			$mail_fld_arr["sup_pwd"] = $sup_pwd;
			$mail_fld_arr["act_link"] = $base_url.$sup_activation;
			$body = push_body("seller_activation.txt",$mail_fld_arr);
			$from = "welcome@Company-Name.com";
			if(xsend_mail($to,$subject,$body,$from)){
				$msg = "Thank You for registering on Company-Name.com. Activate your account by clicking on the activation link sent to '$sup_email'";
			}else {
				$msg = "There is a problem sending activation link to your email address";
			}
			$body="";
			$body.="<p>Congrats, a new seller has registered with the system & the email activation link is being sent to his registered email id. The seller details are as below:</p><br> ";
			$body.="<br>Estsblishment Name : $sup_company";
			$body.="<br>Contact Person Name : $sup_contact_name";
			$body.="<br>Registered Email : $sup_email";
			$body.="<br>Registered Mobile No. : $sup_contact_no";
			$body.="<br><br>Regards,<br> Company-Name Admin";
			
			xsend_mail("sales@Company-Name.com",$subject,$body,$from);
			
			$sup_company = "";
			$sup_contact_name = "";
			$sup_business_type = "";
			$sup_email ="";
			$sup_contact_per_name = "";
			$sup_contact_no ="";
			$sup_pwd ="";
			$sup_cpwd ="";
		}
	}
} 
END:
?>

<body>
<?php require("header.php"); 
error_reporting(E_ALL ^ E_WARNING);
?>
<div id = "gif_show" style="display:none"></div>
<div id="contentwrapper">
	<div id="content_hide">
		 <div class="center-panel">
			 <div class="you-are-here">
				  <table width="100%" border="0" cellspacing="1" cellpadding="5">
				  <tr>
				<td><p align="left" style="color:#FFF"> YOU ARE HERE:<span class="you-are-normal"><a href="index.php" title="Home Page">Home</a> | Seller Registration </span></p></td>
				<td style="padding-right:10px;"><input type="button" class="btn btn-warning" style="float:right; padding:5px; background-color: #f0ad4e !important;" value="Register Me" onClick="document.getElementById('JumpHere').scrollIntoView();" /></td>
				</tr>
				</table>	
				</div>
				
			<?php if($msg <> "") { ?>
			<br>
			<div class="alert alert-info">
				<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				<?php echo $msg;?>
			</div>
			<?php } ?>	
			<!-- Welcome seller section starts -->
					<div class="solidTab panel panel-info">	
						<div class="panel-heading"><h4>Benefits</h4></div>
							<table width="100%" border="0" cellspacing="1" cellpadding="5">
								<tr>
									<td>	
										<p>&#10004	&nbsp; Sell 24x7 anywhere in the country.</p>		
									</td>
								</tr>
								<tr>
									<td>
										<p>&#10004 &nbsp; Leave the burden of logistics & transportation with us.</p>		
									</td>
								</tr>
								<tr>
									<td>
										<p>&#10004	&nbsp; Payments made easy with integrated payment gateways.</p>		
									</td>
								</tr>					
								<tr>
									<td>
										<p>&#10004	&nbsp; List unlimited number of products, without any registration charges.</p>		
									</td>
								</tr>
								<tr>
									<td>
										<p>&#10004	&nbsp; �Company-Name Showcase� - Free dedicated webpage (Sub-Domain Site) to showcase any 12 registered products.</p>
									</td>
								</tr>		
								
								<tr>
									<td>
										<p>&#10004 &nbsp; Give your  brand a superior online presence.</p>
									</td>
								</tr>		
								
								<tr>
									<td>
										<p>&#10004	&nbsp; Ready-made data analysis for your future growth plans.</p>
									</td>
								</tr>
								</table><br>
								</div>
								
								<div class="solidTab panel panel-info">	
								<div class="panel-heading"><h4>How it works</h4></div>
								<table width="100%" border="0" cellspacing="1" cellpadding="5">					
								<tr>
									<td align="center">
										<img src="images/process.png" alt="Seller" width="490" height="560">		
									</td>	
								</tr>
								</table></div>
								
								<div class="solidTab panel panel-info">
								<div class="panel-heading"><h4>General FAQs</h4></div>
								<table width="100%" border="0" cellspacing="1" cellpadding="5">
								<tr>
									<td>
									
										<p><a style="cursor:pointer; width:100%;" onclick="return toggleMe('para1')"><img id="para1_image" src="images/dhtmlgoodies_plus.gif" style="border:0;margin-right:5px;vertical-align:middle;" />Who can become a seller on Company-Name.com?</a></p>
										<div id="para1" style="display:none;">
										<p class="highlight_box_cream">Any vendor who has existing brick-n-mortar establishment & has all necessary documentation in place for doing business in India can become a seller & list their products with Company-Name.com eCommerce Portal. Manufacturers, Distributors & Dealers are preferred.
										
										<br>We support you at every step of your Online business journey right from Order receipt to product Delivery; covering product pick-up, packaging, shipping, shipment tracking and payment collection. Company-Name helps you expand your business and reach customers throughout India, which you couldn�t have otherwise achieved from your Brick-n-Mortar establishment.</p>
										</div>
									</td>
								</tr>
								
								<tr>
									<td>
										<p><a style="cursor:pointer; width:100%;" onclick="return toggleMe('para2')"><img id="para2_image" src="images/dhtmlgoodies_plus.gif" style="border:0;margin-right:5px;vertical-align:middle;" />Do I have to make any upfront payment for selling with Company-Name.com?</a></p>
										<div id="para2" style="display:none;">
										<p class="highlight_box_cream">No, you do not have to make any upfront payment to us for selling your products using our eCommerce portal.</p>
										</div>
									</td> 
									
								</tr>
								
								<tr>
									<td>
										<p><a style="cursor:pointer; width:100%;" onclick="return toggleMe('para7')"><img id="para7_image" src="images/dhtmlgoodies_plus.gif" style="border:0;margin-right:5px;vertical-align:middle;" />What documents are needed to register as seller?</a></p>
										<div id="para7" style="display:none;">
										<div class="highlight_box_cream">
										<p>To start selling, you need to have the following :</p>
										
										<ul style="padding-left:3em">
										<li>Individual & Establishment PAN Card</li>
										<li>VAT/TIN Number, CST to sell across India</li>
										<li>Bank account and supporting KYC documents (ID Proof, Address Proof, and Cancelled cheque)</li>
										</ul>
										</div>
										</div>
									</td>
								</tr>	
								
								<tr>
									<td>
										<p><a style="cursor:pointer; width:100%;" onclick="return toggleMe('para8')"><img id="para8_image" src="images/dhtmlgoodies_plus.gif" style="border:0;margin-right:5px;vertical-align:middle;" />Do I need to courier my products to Company-Name?</a></p>		
										<div id="para8" style="display:none;">
										<p class="highlight_box_cream">No, Company-Name will handle shipping of your products. All that you need is to pack the product as per our guidelines and keep it ready for dispatch. Our logistics partner will pick up the product from you from your registered Business/Warehouse address and deliver it to the customer.</p>
										</div>
									</td>
								</tr>					
								
								<tr>
									<td>
										<p><a style="cursor:pointer; width:100%;" onclick="return toggleMe('para3')"><img id="para3_image" src="images/dhtmlgoodies_plus.gif" style="border:0;margin-right:5px;vertical-align:middle;" />Do I need to have a Bank Account for doing business with Company-Name.com?</a></p>		
										<div id="para3" style="display:none;">
										<p class="highlight_box_cream">Yes, you need to have a Current account with a Nationalized, Private or Cooperative Bank to enable us make payments to you using NEFT/RTGS services offered by the Banks.</p>
										</div>
									</td>
								</tr>					
								</table>
								
								<div id="table1" style="display:none;">
								
								<table width="100%" border="0" cellspacing="1" cellpadding="5">
								<tr>
									<td>
										<p><a style="cursor:pointer; width:100%;" onclick="return toggleMe('para4')"><img id="para4_image" src="images/dhtmlgoodies_plus.gif" style="border:0;margin-right:5px;vertical-align:middle;" />When can I start getting traction for my products listed with Company-Name.com?</a></p>		
										<div id="para4" style="display:none;">
										<p class="highlight_box_cream">As soon as we�ve jointly completed all formalities & have validated your establishment & necessary documentation and once you�ve provided the list of products along with its brochures & competitive price-list, you will be ready to go online & you can start seeing how our online marketing makes wonder for your business & its revenues.</p>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<p><a style="cursor:pointer; width:100%;" onclick="return toggleMe('para5')"><img id="para5_image" src="images/dhtmlgoodies_plus.gif" style="border:0;margin-right:5px;vertical-align:middle;" />Who are potential buyers for my product?</a></p>
										<div id="para5" style="display:none;">
										<p class="highlight_box_cream">Any user, be it a household end-user, a small-time Entrepreneur/Trader or a business house such as Hardware retailer, Small to Medium Scale Industrial Manufacturer or even a large conglomerate.</p>
										</div>
									</td>
								</tr>		
								
								<tr>
									<td>
										<p><a style="cursor:pointer; width:100%;" onclick="return toggleMe('para9')"><img id="para9_image" src="images/dhtmlgoodies_plus.gif" style="border:0;margin-right:5px;vertical-align:middle;" />Who decides the price of the products?</a></p>
										<div id="para9" style="display:none;">
										<p class="highlight_box_cream">As a seller, you will set the competitive price of your products. Remember, there are other sellers who may be selling the same items at aggressive price, buyer will obviously want the best deal.</p>
										</div>
									</td>
								</tr>
								
								<tr>
									<td>
										<p><a style="cursor:pointer; width:100%;" onclick="return toggleMe('para6')"><img id="para6_image" src="images/dhtmlgoodies_plus.gif" style="border:0;margin-right:5px;vertical-align:middle;" />What products can I sell through Company-Name.com?</a></p>
										<div id="para6" style="display:none;">
										<div class="highlight_box_cream">
										<p>Sellers in the following categories can use our eCommerce services for doing business online :</p>
										
										<ul style="padding-left:3em">
										<li>General Hardware</li>
										<li>Construction Hardware</li>
										<li>Adhesives Domestic & Industrial Use</li>
										<li>Grinders</li>
										<li>Drilling equipment</li>
										<li>Architectural Hardware</li>
										<li>Bearing</li>
										<li>Metal & Machine Cutting Tools</li>
										<li>Automobile Tools</li>
										<li>Hand Tools & Power Tools</li>
										<li>Sanitary Ware & CP Fittings</li>
										<li>Gardening and Plumbing equipment & fittings</li>
										<li>Lubricants, Oils</li>
										<li>Compressors, Pneumatic Tools & Pressure Gauges</li>
										<li>Hydraulic Tools & Pressure Gauges</li>
										<li>Lathe Machines, Cutting Machines, Punching Machines, etc.</li>
										<li>Farming equipment</li>
										<li>Pumps & Motors</li>
										<li>Paints, Enamels, painting equipment & accessories</li>
										<li>Testing tools & equipment</li>
										</ul>
										</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<p><a style="cursor:pointer; width:100%;" onclick="return toggleMe('para10')"><img id="para10_image" src="images/dhtmlgoodies_plus.gif" style="border:0;margin-right:5px;vertical-align:middle;" />How does Company-Name get benefited from the sale?</a></p>
										<div id="para10" style="display:none;">
										<p class="highlight_box_cream">Listing of products on Company-Name.com is absolutely free. Company-Name get a share of commission after paying for Logistics & Payment Gateway charges on the deal value & shipped item.</p>
										</div>
									</td>
								</tr>	
								<tr>
									<td>
										<p><a style="cursor:pointer; width:100%;" onclick="return toggleMe('para11')"><img id="para11_image" src="images/dhtmlgoodies_plus.gif" style="border:0;margin-right:5px;vertical-align:middle;" />How many listings are required to start selling?</a></p>
										<div id="para11" style="display:none;">
										<p class="highlight_box_cream">You can even start with just one listing and gradually increase your product listings.</p>
										</div>
									</td>
								</tr>			
						</table>
						</div>
						<p><a id="table1_link" style="cursor:pointer; width:100%;" onclick="return showHideMe('table1')">Show more..</a></p>	
						</div>
						
						<div class="solidTab panel panel-info">
						<div class="panel-heading"><h4>Execution by Company-Name FAQs</h4></div>
						<table width="100%" border="0" cellspacing="1" cellpadding="5">
							<tr>
								<td>
									<a id="JumpHere"></a>
									<p><a style="cursor:pointer; width:100%;" onclick="return toggleMe('parax1')"><img id="parax1_image" src="images/dhtmlgoodies_plus.gif" style="border:0;margin-right:5px;vertical-align:middle;" />What is Execution by Company-Name (XBATM) Service?</a></p>
									<div id="parax1" style="display:none;">
									<p class="highlight_box_cream">Execution by Company-Name (XBATM) is a service provided by Company-Name to deliver orders to customers from our Warehouses, provide customer service and handle returns.</p>
									
									</div>
								</td>
							</tr>
							
							<tr>
								<td>
									<p><a style="cursor:pointer; width:100%;" onclick="return toggleMe('parax2')"><img id="parax2_image" src="images/dhtmlgoodies_plus.gif" style="border:0;margin-right:5px;vertical-align:middle;" />Can I sell my products in India only or outside India too?</a></p>
									<div id="parax2" style="display:none;">
									<p class="highlight_box_cream">You can currently sell products in India only. Currently, Company-Name does not have a provision to sell internationally.</p>
									</div>
								</td> 
								
							</tr>
							
							<tr>
								<td>
									<p><a style="cursor:pointer; width:100%;" onclick="return toggleMe('parax7')"><img id="parax7_image" src="images/dhtmlgoodies_plus.gif" style="border:0;margin-right:5px;vertical-align:middle;" />Is there any minimum number of items required to use XBA?</a></p>
									<div id="parax7" style="display:none;">
									<p class="highlight_box_cream">There are no minimum requirements on the number of products to use XBA.</p>
								</td>
							</tr>	
							
							<tr>
								<td>
									<p><a style="cursor:pointer; width:100%;" onclick="return toggleMe('parax8')"><img id="parax8_image" src="images/dhtmlgoodies_plus.gif" style="border:0;margin-right:5px;vertical-align:middle;" />How will you charge me for Execution by Company-Name?</a></p>		
									<div id="parax8" style="display:none;">
									<p class="highlight_box_cream">Charges for execution services are included in the Company-Name fees. No additional charges are applicable.</p>
									</div>
								</td>
							</tr>					
							
							<tr>
								<td>
									<p><a style="cursor:pointer; width:100%;" onclick="return toggleMe('parax3')"><img id="parax3_image" src="images/dhtmlgoodies_plus.gif" style="border:0;margin-right:5px;vertical-align:middle;" />Who will handle customer service?</a></p>		
									<div id="parax3" style="display:none;">
									<p class="highlight_box_cream">Company-Name�s dedicated support team will handle customer service for all sellers.</p>
									</div>
								</td>
							</tr>					
						</table>
								
						<div id="table2" style="display:none;">
							<table width="100%" border="0" cellspacing="1" cellpadding="5">
								<tr>
									<td>
										<p><a style="cursor:pointer; width:100%;" onclick="return toggleMe('parax4')"><img id="parax4_image" src="images/dhtmlgoodies_plus.gif" style="border:0;margin-right:5px;vertical-align:middle;" />Who will handle the installation, maintenance and repairs (all after-sales services)?</a></p>		
										<div id="parax4" style="display:none;">
										<p class="highlight_box_cream">Company-Name is not responsible for any installation, maintenance and repairs of the products sold via Company-Name.com. Company-Name does not provide any after-sales services.</p>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<p><a style="cursor:pointer; width:100%;" onclick="return toggleMe('parax5')"><img id="parax5_image" src="images/dhtmlgoodies_plus.gif" style="border:0;margin-right:5px;vertical-align:middle;" />Who will handle customer returns?</a></p>
										<div id="parax5" style="display:none;">
										<p class="highlight_box_cream">Company-Name will handle customer returns. Please refer to the <a href="../returns.php">Cancellation & Returns Policy</a> for more information.</p>
										</div>
									</td>
								</tr>		
								
								<tr>
									<td>
										<p><a style="cursor:pointer; width:100%;" onclick="return toggleMe('parax9')"><img id="parax9_image" src="images/dhtmlgoodies_plus.gif" style="border:0;margin-right:5px;vertical-align:middle;" />How will customer refunds happen?</a></p>
										<div id="parax9" style="display:none;">
										<p class="highlight_box_cream">We refund to the customer and charge the same to your account.</p>
										</div>
									</td>
								</tr>
								
								<tr>
									<td>
										<p><a style="cursor:pointer; width:100%;" onclick="return toggleMe('parax6')"><img id="parax6_image" src="images/dhtmlgoodies_plus.gif" style="border:0;margin-right:5px;vertical-align:middle;" />What will happen to my unsold inventory at your execution centres?</a></p>
										<div id="parax6" style="display:none;">
										<p class="highlight_box_cream">Goods not sold within duration of 60 days since receipt of goods will be returned back to the seller.</p>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<p><a style="cursor:pointer; width:100%;" onclick="return toggleMe('parax10')"><img id="parax10_image" src="images/dhtmlgoodies_plus.gif" style="border:0;margin-right:5px;vertical-align:middle;" />How do we decide which products to be stored at your warehouse?</a></p>
										<div id="parax10" style="display:none;">
										<p class="highlight_box_cream">Sellers generally prefer to keep fast moving products at our warehouse and slow-moving products at their own premises.</p>
										</div>
									</td>
								</tr>	
								<tr>
									<td>
										<p><a style="cursor:pointer; width:100%;" onclick="return toggleMe('parax11')"><img id="parax11_image" src="images/dhtmlgoodies_plus.gif" style="border:0;margin-right:5px;vertical-align:middle;" />How should I package the items for shipment by you?</a></p>
										<div id="parax11" style="display:none;">
										<p class="highlight_box_cream">Products should be packed as per Company-Name�s packaging guidelines that are shared with you via email after successful on-boarding process.</p>
										</div>
									</td>
								</tr>	
								<tr>
									<td>
										<p><a style="cursor:pointer; width:100%;" onclick="return toggleMe('parax12')"><img id="parax12_image" src="images/dhtmlgoodies_plus.gif" style="border:0;margin-right:5px;vertical-align:middle;" />Can I pass Credit to my preferred customers?</a></p>
										<div id="parax12" style="display:none;">
										<p class="highlight_box_cream">Yes, once you have authorized a buyer as a preferred buyer, buyer will be allowed to use the allocated Credit value & period as per your mutual agreement. Our system will honour your agreement & will allow customer to place order without making any upfront payment.</p>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<p><a style="cursor:pointer; width:100%;" onclick="return toggleMe('parax13')"><img id="parax13_image" src="images/dhtmlgoodies_plus.gif" style="border:0;margin-right:5px;vertical-align:middle;" />Who can become my preferred buyer?</a></p>
										<div id="parax13" style="display:none;">
										<p class="highlight_box_cream">Your long term customer relationship with your brick-n-mortar buyer can be brought in purview of Company-Name�s XBA system & we will create an account for your preferred buyer based on the agreed terms betweeCompany-Namert & You and between You & your preferred buyer.</p>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<p><a style="cursor:pointer; width:100%;" onclick="return toggleMe('parax14')"><img id="parax14_image" src="images/dhtmlgoodies_plus.gif" style="border:0;margin-right:5px;vertical-align:middle;" />How will I get the payments from my preferred buyers?</a></p>
										<div id="parax14" style="display:none;">
										<p class="highlight_box_cream">We will follow-up on email & SMS with your preferred seller to make payment as per the credit period allowed by you & will send the payment link to execute the Payment using our Payment Gateway System.</p>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<p><a style="cursor:pointer; width:100%;" onclick="return toggleMe('parax15')"><img id="parax15_image" src="images/dhtmlgoodies_plus.gif" style="border:0;margin-right:5px;vertical-align:middle;" />What happens when my preferred buyer fails to make a payment?</a></p>
										<div id="parax15" style="display:none;">
										<p class="highlight_box_cream">We inform you of the failure to make the payment due by the end of credit period & we expect you to resolve the payment & terms with your preferred buyer. You may increase or decrease his credit limits & credit period based on your relationship with the preferred buyer & also update the same directly using your Seller Dashboard or authorize us to do the same.</p>
										</div>
									</td>
								</tr>		
							</table>		
						</div>
						<p><a id="table2_link" style="cursor:pointer; width:100%;" onclick="return showHideMe('table2')">Show more..</a></p>
						</div>	
			<!-- welcome seller section ends -->
			<div class="solidTab panel panel-info">
			<div class="panel-heading"><h4>Seller Registration</h4></div>
			<?=$cms_middle_panel?>
			<?if($s_flag==""){?>
				<form name="frm" method="post" >
				<input type="hidden" name="hdnsubmit" value="y">
				<table width="100%"  border="0" align="center" class="list">
				  <tr>
					<td colspan="2" align="left" class="table-bg"><div align="left">
						<p>Your Sign In Details</p>
					</div></td>
				  </tr>
				  <tr>
					<td width="31%" align="right" class="table-bg-left"><div align="right">
						<label for="Email"></label>
						<p>Email* :</p>
					</div></td>
					<td width="69%" align="left" xclass="table-bg"><input name="sup_email" type="text" id="120" title="Email" class="display-inline form-control textbox-lrg" tabindex="1" size="20" maxlength="50" onblur="javascript: check_email(this);" value="<?=func_var($sup_email)?>"/>
					 <span id="msg_email"></span>
					</td>
				  </tr>
				   <tr>
					<td align="right" xclass="table-bg"><div align="right">
						<label for="Mobile No"></label>
						<p>Mobile* :</p>
					</div></td>
					<td align="left" xclass="table-bg"><input name="sup_contact_no" type="text" onkeypress='validate_key(event)' id="110" tabindex="2" size="20" maxlength="10" title="Mobile Number" class="display-inline form-control textbox-lrg" onblur="javascript: check_phno(this);" value="<?=func_var($sup_contact_no)?>"/>
					 <span id="msg_phone"></td>
				  </tr>
				  <tr>
					<td align="right" class="table-bg-left"><div align="right">
						<label for="Password"></label>
						<p>Password* :</p>
					</div></td>
					<td align="left" xclass="table-bg"><input name="sup_pwd" type="password" class="display-inline form-control textbox-lrg" title="Your password must contain a minimum of 8 characters and a maximum of 54 characters. It must contain at-least 1 number, 1 capital letter and 1 lower-case letter.	" tabindex="3" size="20" maxlength="50" onblur="javascript: check_pwd(this);" value="<?=func_var($sup_pwd)?>"/>
					 <span id="msg_pwd"></span></td>
				  </tr>
				  <tr>
					<td align="right" class="table-bg-left"><div align="right">
						<label for="Confirm-Password"></label>
						<p>Confirm Password* :</p>
					</div></td>
					<td align="left" xclass="table-bg"><input name="sup_cpwd" class="display-inline form-control textbox-lrg" type="password" tabindex="4" size="20" maxlength="50"  value="<?=func_var($sup_cpwd)?>"/>
					 </td>
				  </tr>
				</table>
				<table width="100%"  border="0" align="center" class="list">
				  <tr>
					<td colspan="2" align="left" class="table-bg"><div align="left">
						<p>Your Company Details</p>
					</div></td>
				  </tr>
				  <tr>
					<td width="31%" align="right" xclass="table-bg"><div align="right">
						<label for="First Name"></label>
						<p>Owner Full Name* :</p>
					</div></td>
					<td align="left" xclass="table-bg"><input name="sup_contact_name" type="text" class="display-inline form-control textbox-lrg" id="100" title="Owner's Name" tabindex="5" size="20" maxlength="50"  value="<?=func_var($sup_contact_name)?>"/>
					</td>
				  </tr>
				  <tr>
					<td width="31%" align="right" xclass="table-bg"><div align="right">
						<label for="Contact Person Name"></label>
						<p>Authorised/Contact Person Full Name :</p>
					</div></td>
					<td align="left" xclass="table-bg"><input name="sup_contact_per_name" placeholder="Optional, if the Owner is same as contact person." type="text" class="display-inline form-control textbox-lrg" title="Optional, if the Owner is same as contact person." tabindex="5" size="20" maxlength="50"  value="<?=func_var($sup_contact_per_name)?>"/>
					</td>
				  </tr>
				 <tr>
					<td align="right" xclass="table-bg"><div align="right">
						<label for="Company Name"></label>
						<p>Establishment Name* :</p>
					</div></td>
					<td align="left" xclass="table-bg"><input name="sup_company" type="text" class="display-inline form-control textbox-lrg" id="100" title="Company Name" tabindex="6" size="20" maxlength="50"  value="<?=func_var($sup_company)?>"/>
					</td>
				  </tr>
				  <tr>
					<td align="right" xclass="table-bg"><div align="right">
						<label for="type"></label>
						<p>Business Type* :</p>
					</div></td>
					<td align="left" xclass="table-bg">
					<select id="100" class="display-inline form-control textbox-lrg" title="Business Type" name="sup_business_type" tabindex="7">
						<option value="">Select</option>
						<?=create_cbo("select type_name,type_name from sup_type_mast order by type_sort",($sup_business_type))?>
					</select> 
					</td>
				  </tr>
				  <tr>
					<td align="right" class="table-bg2"><div align="right">
					<p>Validation code:</p>
					</div></td>
					<td><img src="/captcha.php" width="120" height="30" border="1" alt="CAPTCHA" id="captchaimg"><br>
					<label for='message'>Enter the code above here : </label><input type="text" style="margin-left:5px" id="captcha_code" size="8" class="form-control textbox-auto" maxlength="5" name="captcha" value="">
			
					<br>
					Can't read the image? click <a class="color-amber" href="javascript: refreshCaptcha();">here</a> to refresh.</td>
				</tr>
				   <tr>
					<td align="right" xclass="table-bg"><div align="right">
						<p></p>
					</div></td>
					<td align="right" xclass="table-bg"></td>
				  </tr>	  
				</table>
				<table width="100%"  border="0" align="center" class="list">
			  <tr>
				<td align="right" style="padding-righ:10px">
					<input type="button" class="btn btn-warning" value=" Clear" onclick="javascript:clearForm()" tabindex="8">
				</td>
				<td align="left" style="padding-left:10px">
					<input type="submit" class="btn btn-warning" xsrc="images/submit.gif" onclick="javascript: return validate();"value=" Register"  tabindex="9" border="0" / id=image1 name=image1>
				</td>
				
			   </tr>
			</table>
			</form>
			<?}?>
			</div>
		</div>
	</div>
</div>
<?php require("footer.php"); ?>
</body>
<style>
.solidTab td a {
	font-size: 15px;
}
</style>
<script src="scripts/chat.js" type="text/javascript"></script>
</html>
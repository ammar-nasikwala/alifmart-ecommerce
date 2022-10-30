<?php

require("inc_init.php");
$vid = func_read_qs("id");
$level_id = func_read_qs("level_id");
?>

<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
<title>Company-Name - Sitemap</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta name="DESCRIPTION" content="Company-Name Sitemap"/>
<meta name="KEYWORDS" content="Company-Name Sitemap"/>
<link href="styles/styles.css" rel="stylesheet" type="text/css" />
<script src="scripts/scripts.js" type="text/javascript"></script>

<style>

#sitemap-table a,h2{
	color: #6190A1;
}

.table-padding{
	width: 300px;
}

ul{
	padding-left: 15px; 
}

.undotted li{
	list-style-type: none;
	padding-bottom: 10px;
}

</style>
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
					YOU ARE HERE:<span class="you-are-normal">Sitemap</span>
				</p>
			</div>

			<br><br>

			<table id="sitemap-table" class="" border="0">
				<tr>
					<td class="table-padding"><h2><strong>Company-Name - Company</strong></h2></td>
					<td class="table-padding"><h2><strong>Company-Name - Products</strong></h2></td>
					<td class="table-padding"><h2><strong>Company-Name - Buyer Zone</strong></h2></td>
					<td class="table-padding"><h2><strong>Company-Name - Seller Zone</strong></h2></td>
					<td class="table-padding"><h2><strong>Company-Name - Policies</strong></h2></td>
				</tr>
				<tr>
					<td style="vertical-align: top; border-right: 1px #fff;">
					<ul class="undotted">
					<li><a href="index.php" title="Company-Name Home">Home</a></li>
					<li><a href="about-us.php" title="About Us">About Us</a></li>
					<li><a href="contact-us.php" title="Contact Us">Contact Us</a></li>
					<li><a href="careers.php" title="Careers at Company-Name">Careers</a></li>
					<li><a href="faq.php" title="FAQ's">FAQ's</a></li>
					<li><a href="http://www.Company-Name.com/blog" title="Company-Name Blog">Blog</a></li>
					</ul></td>

					<td style="vertical-align: top;">
					<ul class="undotted">
					<li><a href="prod_list.php" title="All Products">All Products</a></li>
					<li><a href="prod_list.php?prod_frd=1" title="Featured Products">Featured Products</a></li>
					<li><a href="prod_list.php?prod_tpsl=1" title="Best Selling Products">Best Selling Products</a></li>
					<li><a href="prod_list.php?prod_pspl=1" title="Popular Products">Popular Products</a></li>
					<li><a href="#" data-toggle="modal" data-target="#myModalx" title="All Available Categories">Available Categories</a></li>
					<li><a href="#" data-toggle="modal" data-target="#myModal2" title="All Available Brands">Available Brands</a></li>
					
					</ul></td>

					<td style="vertical-align: top;">
					<ul class="undotted">
					<li><a href="register.php" title="Buyer Registration">Register</a></li>
					<li><a href="basket.php" title="Shopping Cart">Shopping Cart</a></li>
					<li><a href="support.php" title="Support">Support</a></li>
					<li><a href="feedback.php" title="Feedback">Feedback</a></li>
					</ul></td>
					
					<td style="vertical-align: top;">
					<ul class="undotted">
					<li><a href="seller.php">Register</a></li>
					<li><a href="../seller/index.php">Sign in</a></li>
					</ul></td>
					<td style="vertical-align: top;">
					<ul class="undotted">
					<li><a href="privacy.php" title="Privacy Policy">Privacy Policy</a></li>
					<li><a href="terms.php" title="Terms and Conditions">Terms &amp; Conditions</a></li>
					<li><a href="returns.php" title="Cancellation and Returns Policy">Cancellation & Returns Policy</a></li>
					<li><a href="copyright.php" title="Copyright">Copyright</a></li>
					</ul></td>
				</tr>
			</table> 	
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




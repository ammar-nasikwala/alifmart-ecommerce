<?php require("inc_admin_init.php");?>
<!--[if IE 6]>
<div id="ie6">We have detected that you are using <strong>Internet Explorer 6</strong> which is quite an old browser. Although your Administration Panel will still work correctly, some features wont work as well as they would on <strong>Internet Explorer 7+</strong>. <a href="http://www.microsoft.com/uk/windows/internet-explorer/download-ie.aspx" target="_blank">We recommend you upgrade for free</a></div><![endif]-->
<script>
//bootstrap popover
$(document).ready(function(){
	$('[data-toggle="popover"]').popover(); 
});
</script>

<?php 
$seller_logo="";
$seller_name="";
if(isset($_SESSION["sup_id"]))
{
	$sup_id=$_SESSION["sup_id"];
	$rst = mysqli_query($con,"select sup_company,sup_logo from sup_mast where sup_id=$sup_id");	
	$row = mysqli_fetch_assoc($rst);
	$seller_name = $row["sup_company"];
	if($row["sup_logo"] <> ""){
		$seller_logo = "../seller/".$row["sup_logo"];	
	}else{
		$seller_logo = "../images/profile-icon.png";
	}
}
?>
<noscript><div id="javascript">We have detected that you have <strong>javascript disabled</strong> in your browser. For best results with your Seller panel, <strong>javascript must be enabled</strong>. <a href="http://www.google.com/support/bin/answer.py?hl=en&amp;answer=23852" target="_blank">How do I enable javascript?</a></div></noscript>
<div id="maincontainer">
<div id="header">
<div id="logo" align="center"><a href="mainmenu.php?submenuheader=500"><img src="../images/logo-black.gif" border="0" height=50 alt="<?php echo $company_title ?> | View Dashboard" onMouseOver="toggleDiv('mydiv');toggleDiv('link');" onMouseOut="toggleDiv('mydiv');toggleDiv('link');" /></a></div>
<div class="logolabel"><img width="50" height="50" class="img-circle" src="<?=show_img($row["sup_logo"]);?>"/>&nbsp;&nbsp;<span><div id="link" style="display:inline-block;"><?php echo $seller_name; ?></div></span></div>
<div class="top-center">

<p><strong>Customer Care:</strong> &nbsp; <a href="mailto:support@Company-Name.com?subject=Contact%20Customer%20care" title="support@Company-Name.com" style="text-decoration:none;"> <span class=" glyphicon glyphicon-envelope glyf"></span> </a>
			&nbsp;<a href="#"  title="Call Us" data-toggle="popover" data-content="77 9849 5353" data-trigger=focus style="text-decoration:none;"> <span class=" glyphicon glyphicon-earphone glyf"></span> </a> </p>
</div>
<div class="topright">
<div class="topbuttons">

<!--<div class="aerobuttonmenu black"> -->
<a href="logout.php" class="btn btn-warning simple" title="Logout of seller panel "><span class="glyphicon glyphicon-log-out"></span> Logout</a>
</div>
<!-- </div> -->

<div class="datestamp">
<SCRIPT language="JavaScript" type="text/javascript">
var dayNames = new Array("Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday");

var monthNames = new Array("January","February","March","April","May","June","July",
                           "August","September","October","November","December");

var dt = new Date();
var y  = dt.getYear();

if (y < 1000) y +=1900;

document.write(dayNames[dt.getDay()] + ", " + monthNames[dt.getMonth()] + " " + dt.getDate() + ", " + y);
	
</SCRIPT>

</div>
</div>
</div>
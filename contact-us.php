<?php

require("inc_init.php");
require("lib/inc_home_banner.php");

$vid = func_read_qs("id");

$level_id = func_read_qs("level_id");

?>

<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
<title><?=$cms_title?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta name="DESCRIPTION" content="<?=$cms_meta_desc?>"/>
<meta name="KEYWORDS" content="<?=$cms_meta_key?>"/>
<link href="styles/styles.css" rel="stylesheet" type="text/css" />
<link href="styles/bannerstyle.css" rel="stylesheet" type="text/css" />
<script src="scripts/scripts.js" type="text/javascript"></script>
<script type="text/javascript" src="scripts/animatedcollapse.js"></script>


	
<script type="text/javascript">


animatedcollapse.addDiv('left-nav-1', 'fade=0,speed=800,hide=1')
animatedcollapse.addDiv('left-nav-2', 'fade=0,speed=800,persist=1,hide=1')

animatedcollapse.ontoggle=function($, divobj, state){ //fires each time a DIV is expanded/contracted
	//$: Access to jQuery
	//divobj: DOM reference to DIV being expanded/ collapsed. Use "divobj.id" to get its ID
	//state: "block" or "none", depending on state
}

//animatedcollapse.init()

</script>

</head>
<body>
<div class="">
<?php

require("header.php");

?>



<div id="contentwrapper">
	<div id="contentcolumn">
		<div class="center-panel">
			<div class="you-are-here">
				<p align="left">
					YOU ARE HERE:<span class="you-are-normal">Contact Us</span>
				</p>
			</div>

			<br><br>
			
			<div>		
				<p><Strong>Registered Office:</strong></p>
				<p><Strong>Company-Name Online Services LLP,</strong></p>
				<p>PAP S-12,  Near Indrayani Nagar Corner,</p>
				<p>Telco Road, MIDC, Bhosari, PUNE - 411026</p>
				<br>
				<p>Phone Number: <strong>+91 20 4003 5353</strong></p>
				<p>Email: <strong>feedback@Company-Name.com</strong></p>
			</div>
			<br><br>
				<iframe class="boxed-shadow" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3780.7799984180583!2d73.83207811434074!3d18.628962570713554!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3bc2b87b8aaf1c73%3A0x4dd0fc4b612f7e85!2sCompany-Name!5e0!3m2!1sen!2sin!4v1455359921089" width="600" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>
			<hr color="#000000" size="1px" width="91%" align="center" />
		</div>

	</div>
</div>

<?php
	require("left.php");
	require("right.php");
	require("footer.php");

?>

</div>
</div>
</div>
</body>
<script src="scripts/chat.js" type="text/javascript"></script>
</html>




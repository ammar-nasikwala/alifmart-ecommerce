<?php
require("inc_init.php");
$cms_title = "Seller - Details"

?>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
<title><?=$cms_title?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta name="DESCRIPTION" content="<?=$cms_meta_key?>"/>
<meta name="KEYWORDS" content="<?=$cms_meta_desc?>"/>
<link href="styles/styles.css" rel="stylesheet" type="text/css" />
<link href="styles/bannerstyle.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="scripts/jquery-1.6.1.min.js"></script>
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

function Submit_form(){	
	if(chkForm(document.frm)==false)
		return false;
	else
		document.frm.act.value="1";
		document.frm.submit();
}

</script>
</head>
<body>
<?php

require("header.php");

?>
<div id="contentwrapper">
<div >
<div class="center-panel">
	<div class="you-are-here">
		<p align="left">
			YOU ARE HERE:<a href="index.php" title="">Home</a> | <span class="you-are-normal"><?=$cms_title?></span>
			<?php if(isset($_SESSION["sup_id"])){ ?>	 <span style="float: right" class="you-are-normal"><a href="../seller/logout.php" title="">Sign out</a></span> 
			<?php } ?>

		</p>
	</div>
	
	<br>
	<?
	//$_SESSION["sup_id"] = $_SESSION["sup_id"];
	//die( $_SESSION["sup_id"]);
	require("seller/inc_chk_session.php");
	require_once("seller/inc_update_form.php");

	//$_SESSION["sup_id"] = "";	
	?>

</div>

</div>
</div>

<?php
	//require("left.php");
	require("right.php");
	require("footer.php");
?>

</div>
</div>

</body>
</html>
	
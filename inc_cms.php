<?php
require("inc_init.php");
?>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
<title><?=$cms_title?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta name="DESCRIPTION" content="<?=$cms_meta_key?>"/>
<meta name="KEYWORDS" content="<?=$cms_meta_desc?>"/>
<link href="styles/styles.css" rel="stylesheet" type="text/css" />
<link href="styles/bannerstyle.css" rel="stylesheet" type="text/css" />
<script src="scripts/scripts.js" type="text/javascript"></script>

</head>
<body>
<?php
require("header.php");
?>
<div id="contentwrapper">
<div>
<div class="center-panel">
	<div class="you-are-here">
		<p align="left">
			YOU ARE HERE:<a href="index.php" title="">Home</a> | <span class="you-are-normal"><?=$cms_title?></span>
		</p>
	</div>
	
	<br>
	
	<div>
		<?=$cms_middle_panel?>
	</div>
	

</div>

</div>
</div>

<?php
	require("footer.php");
?>

</div>
</div>

</body>
<script src="scripts/chat.js" type="text/javascript"></script>
</html>
	
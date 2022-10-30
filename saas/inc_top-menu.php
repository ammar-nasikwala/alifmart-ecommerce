<?php
require("inc_admin_init.php");

$type=$_SESSION["saas_user"]."";
$company_id = $_SESSION["company_id"]."";
$name = $_SESSION["user_name"];
?>
<noscript><div id="javascript">We have detected that you have <strong>javascript disabled</strong> in your browser. For best results with your Web Admin panel, <strong>javascript must be enabled</strong>. <a href="http://www.google.com/support/bin/answer.py?hl=en&amp;answer=23852" target="_blank">How do I enable javascript?</a></div></noscript>
<div id="maincontainer">
<div id="header">
<div id="logo" align="center">
	<a href="mainmenu.php?submenuheader=500"><img src="../images/logo-black.gif" border="0" height=50 alt="<?php echo $company_title ?> | View Dashboard" onMouseOver="toggleDiv('mydiv');toggleDiv('link');" onMouseOut="toggleDiv('mydiv');toggleDiv('link');" /></a>
</div>
<div class="logolabel_new"><div id="mydiv" style="display:none">View Dashboard</div>
<div id="link"><?php echo $name?>  |  Company-Name Briefcase</div></div>


<div class="topright">
	<br>
	<table>
	<tr> <td>
	<div class="topbuttons">
	<a href="mainmenu.php?submenuheader=500" class="btn btn-info" title="Click here to go back to your main Dashboard"><span class="glyphicon glyphicon-home"></span> Dashboard</a>
	<a href="logout.php" class="btn btn-warning" title="Click here once you are finished to log out of the admin panel"><span class="glyphicon glyphicon-log-out"></span> Logout</a>
	</div>
	</td>
	</tr>
	</table>
	</div>
</div>

<?php
require("inc_admin_init.php");

$type="";
$name="";
if(isset($_SESSION["user_name"])){ $name=$_SESSION["user_name"]; }
$type=$_SESSION["admin"];
	if($type=="AM"){
		$user_type="Account Manager";
		}elseif($type=="FM"){
				$user_type="Finance Manager";
				}elseif($type=="LP"){
						$user_type="Logistic Partner";
				}

?><!--[if IE 6]>
<div id="ie6">We have detected that you are using <strong>Internet Explorer 6</strong> which is quite an old browser. Although your Administration Panel will still work correctly, some features wont work as well as they would on <strong>Internet Explorer 7+</strong>. <a href="http://www.microsoft.com/uk/windows/internet-explorer/download-ie.aspx" target="_blank">We recommend you upgrade for free</a></div><![endif]-->

<noscript><div id="javascript">We have detected that you have <strong>javascript disabled</strong> in your browser. For best results with your Web Admin panel, <strong>javascript must be enabled</strong>. <a href="http://www.google.com/support/bin/answer.py?hl=en&amp;answer=23852" target="_blank">How do I enable javascript?</a></div></noscript>
<div id="maincontainer">
<div id="header">
<div id="logo" align="center"><a href="mainmenu.php?submenuheader=500"><img src="../images/logo-black.gif" border="0" height=50 alt="<?php echo $company_title ?> | View Dashboard" onMouseOver="toggleDiv('mydiv');toggleDiv('link');" onMouseOut="toggleDiv('mydiv');toggleDiv('link');" /></a></div>

<?php if($type=="1"){?>
	<div class="logolabel"><div id="mydiv" style="display:none">View Dashboard</div><div id="link"> Web Admin Panel</div></div>
<?php }else{?><div class="logolabel_new"><div id="mydiv" style="display:none">View Dashboard</div><div id="link">
<?php if($type=="1"){}else{echo $name.", ".$user_type;}?> | Web Admin Panel</div></div>
<?php }?>

<div class="topright">
	<br>
	<table>
	<tr> <td>
	<div class="topbuttons">
	<a href="mainmenu.php?submenuheader=500" class="btn btn-info" title="Click here to go back to your main Dashboard"><span class="glyphicon glyphicon-home"></span> Dashboard</a>
	<?php if($_SESSION["admin"]=="AM"|| $_SESSION["admin"]=="FM"){?>
	<a href="configuration.php?action=new" style="display:none;" class="btn btn-info" title="Click here to view or change your Site Configuration and Settings"><span class="glyphicon glyphicon-cog"></span> Site Configuration</a>
	<?php }else{?>
	<a href="configuration.php?action=new" class="btn btn-info" title="Click here to view or change your Site Configuration and Settings"><span class="glyphicon glyphicon-cog"></span> Site Configuration</a>
	<?php }?>
	<a href="logout.php" class="btn btn-warning" title="Click here once you are finished to log out of the admin panel"><span class="glyphicon glyphicon-log-out"></span> Logout</a>
	</div>
	</td></tr>

	<tr><td>
	<div class="datestamp">
	<a href="/" title="Click here to view your website (Opens in new window)" target="_blank"><img src="images/view_website.gif" alt="Click here to view your website (Opens in new window)" border="0" />View Live Website</a> 

	<SCRIPT language="JavaScript" type="text/javascript">
	document.write("&nbsp; | &nbsp;");
	var dayNames = new Array("Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday");

	var monthNames = new Array("January","February","March","April","May","June","July",
							   "August","September","October","November","December");

	var dt = new Date();
	var y  = dt.getYear();

	if (y < 1000) y +=1900;

	document.write(dayNames[dt.getDay()] + ", " + monthNames[dt.getMonth()] + " " + dt.getDate() + ", " + y);

	</SCRIPT>
	</div>
	</td></tr>
	</table>
	</div>
</div>

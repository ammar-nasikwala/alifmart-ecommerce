<?php
session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="icon" href="../images/favicon.png" type="image/png" />     <!--change-->
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Company-Name | Seller Profile</title>
<script type="text/javascript" src="js/jquery-1.2.6.pack.js"></script>
<script type="text/javascript" src="js/ddaccordion.js"></script>
<script type="text/javascript" src="js/collapse_menu.js"></script>
<script type="text/javascript" src="js/logohover.js"></script>
<script type="text/javascript" src="js/tooltip.js"></script>
<script type="text/javascript" src="js/form-hints.js"></script>
<script language="javascript" src="frmCheck.js"></script>
	<script language="javascript">
	
		function DoCal(objDOI,DOIVal){	
			var sRtn;
			var err=0
			sRtn = showModalDialog("calendar.htm",DOIVal,"center=yes;dialogWidth=200pt;dialogHeight=200pt;status=no");
				
			if(String(sRtn)!="undefined"){
					objDOI.value=sRtn;	
			}
		}
	
	</script>
</head>
<body onload="document.frm.event_name.focus();">
<?php
require("inc_chk_session.php");
?>
<div id="maincontainer" class="table-responsive">
<table class="tbl_dash_main" width="100%">
<tr>
<td colspan="2">
<?php require("inc_top-menu.php") ?>
</td>
</tr>
<!--div id="panels"!-->
<tr>
<td width="210px">
<?php require("inc_left-menu.php") ?>
</td>
<td>
<div id="centerpanel">
<?php require("inc_seller_profile.php")?>
<link type="text/css" rel="stylesheet" href="css/main.css" />
<link type="text/css" rel="stylesheet" href="css/collapse_menu.css" />
<link type="text/css" rel="stylesheet" href="css/tooltip.css" />
</div>
</td>
</tr>
<tr>
<td colspan="2">
<?php require("inc_footer.php") ?>
</td>
</tr>
</table>
</div>
</body>
</html>
<?php require("../lib/inc_close_connection.php") ?>
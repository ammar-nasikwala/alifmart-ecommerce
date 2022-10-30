<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php
require_once("inc_admin_init.php");
$msg="";
?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title> Company-Name Briefcase</title>
<link type="text/css" rel="stylesheet" href="css/main.css" />
<link type="text/css" rel="stylesheet" href="css/collapse_menu.css" />
<link type="text/css" rel="stylesheet" href="css/tooltip.css" />
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
<script src="../scripts/jquery-ui-timepicker-addon.js"></script>
<link type="text/css" rel="stylesheet" href="../styles/jquery-ui-timepicker-addon.css" />
<script type="text/javascript" src="js/logohover.js"></script>
<script type="text/javascript" src="js/tooltip.js"></script>

<script type="text/javascript">
	function toggleMe(a){	
		var e=document.getElementById(a);
		var i = document.getElementById(a + '_image');
		if(!e)return true;	if(e.style.display=="none"){
		e.style.display="block"
		i.src = 'images/dhtmlgoodies_minus.gif';
		} else {
			e.style.display="none"
			i.src = 'images/dhtmlgoodies_plus.gif';
		}
			return false;
	}
	function showHideMe(a){
		var e=document.getElementById(a);
		var i = document.getElementById(a + '_link');
		if(!e)return true;
		if(e.style.display=="none"){
			e.style.display="block"
			i.innerHTML = 'Show less..'
		}	else{	
			e.style.display="none"
			i.innerHTML = 'Show more..'
		}
		return true;
	}
	function display_gif(){
				(function(){
			var gif_show = document.getElementById("gif_show")
			var content_hide = document.getElementById("content_hide"),
		show = function(){
			gif_show.style.display = "block";
		},
		hide = function(){
			gif_show.style.display = "none";
		};

	show();
  })();
}
</script>

<style>
div.solidTab {  
	width: auto;
	color: #000000;
	background-color: #f1f1f1;
	line-height: 1.15em;
	word-wrap: break-word;
	margin-bottom: 12px;
	margin-top: 12px;
	box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
}
</style>
</head>

<body>
<div id="maincontainer" class="table-responsive">
<table class="tbl_dash_main" width="100%">
<tr>
<td colspan="2">
<?php require("inc_top-menu.php") ?>
</td>
</tr>
<!--div id="panels"-->
<tr>
<td align="left" width="210px">
<?php require("inc_left-menu.php") ?>
</td>
<td>
<div id="centerpanel">
<div id="contentarea">
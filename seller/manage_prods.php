<?php
session_start();
require("../lib/inc_library.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo $company_title ?> | Manage Products</title>
<link type="text/css" rel="stylesheet" href="css/main.css" />
<link type="text/css" rel="stylesheet" href="css/collapse_menu.css" />
<link type="text/css" rel="stylesheet" href="css/tooltip.css" />
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
	
	
		function Submit_form()
		{	
			if(chkForm(document.frm)==false)
				return false;
			else
				document.frm.submit();
		}
	
	</script>
<script type="text/javascript" language="javascript"> 
<!--
function limitChars(textid, limit, infodiv)
{
	var text = $('#'+textid).val();	
	var textlength = text.length;
	if(textlength > limit)
	{
		$('#' + infodiv).html('You cannot write more then '+limit+' characters!');
		$('#'+textid).val(text.substr(0,limit));
		return false;
	}
	else
	{
		$('#' + infodiv).html('You have <strong>'+ (limit - textlength) +'</strong> characters left');
		return true;
	}
}
 
$(function(){
 	$('#event_details').keyup(function(){
 		limitChars('event_details', 1000, 'charlimitinfo');
 	})
});
-->
</script>
</head>
<?php
//$txt_key = func_read_qs("txt_key");
$_SESSION["page_id"] = 0;
$_SESSION["page_id"] = func_read_qs("page");
if(func_read_qs("active") <> "") { 
	$_SESSION["active"] = func_read_qs("active"); 
}else{
	$_SESSION["active"] = "";
}

$sup_id=0;
if(isset($_SESSION["sup_id"])){
	$sup_id=$_SESSION["sup_id"];
}else{	?>
	<script>
		alert("Your session timed out. Please login again.");
		window.location.href="index.php";
	</script>
	<?php
}

if(($_SESSION["txt_key"]."" == "" || $_SESSION["txt_key"] <> func_read_qs("txt_key")) && func_read_qs("txt_key") <> ""){
 $_SESSION["txt_key"] = func_read_qs("txt_key"); 
}
 
if(func_read_qs("page") == "" && func_read_qs("txt_key") == ""){$_SESSION["txt_key"] = "";}
if(func_read_qs("page") <> ""){
	$txt_key = trim($_SESSION["txt_key"]);
}else{
	$txt_key = trim(func_read_qs("txt_key"));
}
$page = func_read_qs("page");
$sql="select prod_id";
$sql = $sql.",prod_stockno as 'Stock No.'"; 
$sql = $sql.",prod_name as 'Product Name'"; 
$sql = $sql.",(select brand_name from brand_mast b where b.brand_id = p.prod_brand_id) as 'Brand'"; 
$sql = $sql.",(select level_name from levels l where l.level_id = p.level_parent) as 'Category'"; 
$sql = $sql.",prod_status as 'Active Status'"; 
$sql = $sql."from prod_mast p ";
$sql_where = "";
$prod_status = "";
if($_SESSION["active"] <> ""){
	$prod_status= "prod_status=".$_SESSION["active"]." AND";
}
	
if($txt_key<>""){
	$sql_where = $sql_where." where ".$prod_status." (prod_name like '%$txt_key%'";
	$sql_where = $sql_where." OR prod_stockno like '%$txt_key%'";
	$sql_where = $sql_where." OR prod_brand_id in (select brand_id from brand_mast where brand_name like '%$txt_key%')";
	$sql_where = $sql_where." OR level_parent in (select level_id from levels where level_name like '%$txt_key%'))";
	$sql_where = $sql_where." AND prod_id IN (select prod_id from prod_sup where sup_id=$sup_id)";
}else{
	$sql_where = $sql_where." where ".$prod_status." prod_id IN (select prod_id from prod_sup where sup_id=$sup_id)";
}
$sql = $sql.$sql_where." order by prod_stockno";
$export_display="none";
?>
<body>
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
<!--div id="panels"-->
<tr>
<td width="210px">
<?php require("inc_left-menu.php") ?>
</td>
<td>
<div id="centerpanel">
<div id="div_export" style="display:<?=$export_display?>;position:absolute;left:0px;top:0px;height:100%;width:100%;background-color:#eeeeee;">
	
	<br>&nbsp;
	<center>
	<textarea style="width:1200px;height:500px" wrap="off"><?=func_var($export_data)?></textarea>
	<br>
	Tab delimited data. Please copy/paste directly in an excel sheet.
	<br>
	<input type="button" value=" Close " onclick="javascript: close_export(this);">
	</center>
</div>
<table border="0" width="100%">
	<tr>
		<td colspan="10"><h2>Manage Products</h2></td>
	</tr>
	
	<form name="frm_list" method="post" action="manage_prods.php">
	<input type="hidden" name="act" value="1">
	<tr>
		<td><a href="edit_prods.php">Create New Product [+]</a></td>

		<td align="right" style="padding-bottom:10px;">	
		<input type="hidden" name="page" id="page" value="<?=$page?>">
		<input type="text" class="form-control textbox-mid" name="txt_key" value="<?=$txt_key?>"> 
		<input type="submit" class="btn btn-warning" name="btn_filter" value=" Filter " onclick="javascript: document.getElementById('page').value='';">
			
		<br>
		</td>
	</tr>
</form>
    <tr>
        <td colspan="10">
			<?php create_list($sql,"edit_prods.php",20,"tbl_pages",5);?>
        </td>
    </tr>
</table>
</div>
</td>
</tr>
<!--/div-->
<tr>
<td colspan="2">
<?php require("inc_footer.php") ?>
</td>
</tr>
</table>
</div>
</body>

</html>
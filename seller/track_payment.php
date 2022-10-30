<?php
session_start();
require("../lib/inc_library.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo $company_title ?> | Track Payment</title>
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
</script>
</head>
<?php
//$txt_key = func_read_qs("txt_key");
$_SESSION["page_id"] = 0;
$_SESSION["page_id"] = func_read_qs("page");
$sup_id=$_SESSION["sup_id"];	

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
$sql="select invoice_id";
$sql = $sql.",ord_no as 'Order ID'"; 
$sql = $sql.",pay_total as 'Pay Amount'"; 
$sql = $sql.",pay_status as 'Payment Status'"; 
$sql = $sql."from invoice_mast I where sup_id=0$sup_id ";
$sql_where = "";
if($txt_key<>""){
	$sql_where = $sql_where." and ord_no like '%$txt_key%'";
}

$sql = $sql.$sql_where." order by invoice_id desc";

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
						<form name="frm_list" method="post" action="">
						<table border="0" width="100%">
							<tr>
								<td><h3>Track Payments</h3></td>
								<td align="right" style="padding-top: 10px;">	
								<input type="hidden" name="page" id="page" value="<?=$page?>">
								<input type="text" class="form-control textbox-mid" name="txt_key" value="<?=$txt_key?>"> 
								<input type="submit" class="btn btn-warning" name="btn_filter" value=" Filter " onclick="javascript: document.getElementById('page').value='';">
								
								<br>
								</td>
							</tr>
							
							
							<input type="hidden" name="act" value="1">
							<tr>

								
							</tr>
							</form>
							<tr>
								<td colspan="10">
									<?php echo create_list($sql,"edit_payments.php",20,"tbl_pages",5);?>
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
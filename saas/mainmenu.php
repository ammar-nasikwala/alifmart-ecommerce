<?php
$msg="";
session_start();
$cid=$_SESSION["po_company_id"];

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="icon" href="../images/favicon.png" type="image/png" /> 
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title> Company-Name Briefcase </title>

<link type="text/css" rel="stylesheet" href="css/main.css" />
<link type="text/css" rel="stylesheet" href="css/collapse_menu.css" />
<link type="text/css" rel="stylesheet" href="css/tooltip.css" />
<script type="text/javascript" src="js/jquery-1.2.6.pack.js"></script>
<script type="text/javascript" src="js/ddaccordion.js"></script>
<script type="text/javascript" src="js/collapse_menu.js"></script>
<script type="text/javascript" src="js/logohover.js"></script>
<script type="text/javascript" src="js/tooltip.js"></script>
<script type="text/javascript">function toggleMe(a){	var e=document.getElementById(a);	var i = document.getElementById(a + '_image');	if(!e)return true;	if(e.style.display=="none"){	e.style.display="block"	i.src = 'images/dhtmlgoodies_minus.gif';	} else {	e.style.display="none"	i.src = 'images/dhtmlgoodies_plus.gif';	}	return false;}function showHideMe(a){	var e=document.getElementById(a);	var i = document.getElementById(a + '_link');	if(!e)return true;	if(e.style.display=="none"){	e.style.display="block"	i.innerHTML = 'Show less..'	}	else{	e.style.display="none"	i.innerHTML = 'Show more..'	}	return true;}</script><style>div.solidTab {  width: auto;  color: #000000;  background-color: #f1f1f1;  padding: 15px;  line-height: 1.15em;  word-wrap: break-word;  margin-bottom: 12px;  margin-top: 12px;  box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);}</style></head>
<script>
window.onload = function() {
	 $(".statusicon").attr({src:"images/plus.gif"});
     $(".submenu").hide();
    };
</script>
<body>

<div id="maincontainer" class="table-responsive">
<table class="tbl_dash_main">

<tr>
<td colspan="2">
<?php require("inc_top-menu.php");

if(isset($_POST["accept"])){		//Subscription request
	execute_qry("update po_company_mast set accept_terms=1 where po_company_id=$cid");
	js_alert("Welocme to Company-Name Briefcase Panel.");
	js_redirect("mainmenu.php");
}
 ?>


<!--div id="panels"!-->
<tr>
<td align="left" width="220px">
<?php 
$disp="";
if(get_rst("select po_company_id from po_company_mast where po_company_id=$cid and accept_terms is null",$t)){
	$disp=1;
}else{
require("inc_left-menu.php");
}?>
</td>
<td>
		<div id="centerpanel"> 
			<div id="contentarea">
			<?php if($disp == ""){?>
			<h2>Dashboard</h2><p style="float:right; font-size:1.2em;" title="Your Company ID.">Company ID: <?php echo $_SESSION["company_id"]; ?></p>
				<br />
				<div id="buttons">

				</div>
				<?php
					get_rst("select count(0) as tot from request_for_quotation where po_company_id=$cid",$row);
					$total_rfq = $row["tot"];
					get_rst("select count(0) as tot from quotation_mast where po_company_id=$cid and quot_status='Sent'",$row);
					$total_quotation = $row["tot"];
					get_rst("select member_count from po_company_mast where po_company_id=$cid",$row);
					$total_users = $row["member_count"];
					get_rst("select count(0) as tot from po_details where po_company_id=$cid",$row);
					$po_total = $row["tot"];
					get_rst("select count(0) as tot from po_details where po_company_id=$cid and po_status='Approved'",$row);
					$po_total_approved = $row["tot"];
				?>
				<table border="0" width="100%" class="tbl_dash_main">
				<tr>	
					<td width="33%">
						<table class="tbl_dash">
							<tr>
								<th>&nbsp;<span class="glyphicon glyphicon-open-file"></span> RFQ</th>
							</tr>
							<tr>			
								<td><?=dash_new($total_rfq)?> <a href="manage_rfq.php">  RFQ Raised</a></td>	
							</tr>
						</table>
					</td>
					<td width="33%">	
						<table class="tbl_dash">
							<tr>			
								<th>&nbsp;<span class="glyphicon glyphicon-file"></span> Quotation</th>		
							</tr>					
							<tr>			
								<td><?=dash_new($total_quotation)?> <a href="manage_quotation.php"> Quotation Generated</a></td>	
							</tr>
							<tr>
								<td><?=dash_total($po_total)?> <a href="manage_quotation.php"> Quotation Accepted</a></td>
							</tr>
										
						</table>
						<table class="tbl_dash">
							<tr>			
								<th>&nbsp;<span class="glyphicon glyphicon-file"></span> Purchase Orders</th>		
							</tr>					
							<tr>			
								<td><?=dash_new($po_total)?> <a href="manage_po.php"> Purchase Orders</a></td>	
							</tr>
							<tr>
								<td><?=dash_total($po_total_approved)?> <a href="manage_po.php">  Purchase Orders Accepted</a></td>
							</tr>
										
						</table>
					</td>
				<?php if($_SESSION['saas_user'] == 1){ ?>
					<td width="33%">	
						<table class="tbl_dash">
							<tr>			
								<th>&nbsp;<span class="glyphicon glyphicon-user"></span> Users</th>			
							</tr>
							<tr>	
								<td><?=dash_total($total_users)?> <a href="manage_users.php">  Users</a></td>	
							</tr>	
							
						</table>
					</td>
				<?php }?>	
				</tr><tr>	<td>	</td>	<td>	</td>	<td>	</td></tr><tr>	<td>	</td>	<td>	</td>	<td>	</td></tr>
				</table>
			<?php }else{?>
				<?php get_rst("SELECT middle_panel FROM cms_pages WHERE page_name='terms'",$term);?>
				<form name="frmterms" method="post">
					<center><div style="height:380px; width:900px; margin-bottom:20px; overflow:scroll; border: 1px solid grey">
						<?=$term["middle_panel"]?>
					</div></center>
					<div>	
						<center><input type="submit" name="accept" value="Accept" class="btn btn-warning"></center>
					</div>
					<br>
				</form>
			<?php }?>
			</div>
		</div>
</td>
</tr>
<tr>
<td colspan="2">
<?php require("inc_footer.php"); ?>
</td>
</tr>
</table>
</div>
</body>
</html>
<?php function dash_new($val){	//if($val=="0"){	//	$val = "No ";	//}	?>	<span class="dash_new"><?=$val?></span>	<?php }function dash_total($val){	?>	<span class="dash_total"><?=$val?></span>	<?php }?>
<?php require("../lib/inc_close_connection.php");?>
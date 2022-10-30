<?php
$msg="";
session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="icon" href="../images/favicon.png" type="image/png" /> 
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title> Administration Panel</title>

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
<style>
.rupee-sign {
    font-size: 14px;
    display: inline-block;
}
</style>
</head>
<body>

<div id="maincontainer" class="table-responsive">
<table class="tbl_dash_main">
	<tr>
	<td colspan="2">
	<?php require("inc_top-menu.php") ?>

	<!--div id="panels"!-->
	<tr>
	<td align="left" width="220px">
	<?php require("inc_left-menu.php")?>
	</td>
	<td>
		<div id="centerpanel">
			<div id="contentarea">
			<h2>Dashboard</h2>
				<br />
				<?php
					//get orders ready for dispatch
					get_rst("select count(0) as tot from ord_summary where delivery_status='Pending' and pay_status='Paid' and buyer_action is null",$row);
					$ord_pending = $row["tot"];
					
					//get total number of orders
					get_rst("select distinct ord_id, count(0) as tot from ord_summary",$row);
					$ord_total = $row["tot"];
					
					//get total number of new sellers
					get_rst("select count(0) as tot from sup_mast where sup_active_status <> 1",$row);
					$seller_new = $row["tot"];
					
					//get total number of sellers
					get_rst("select count(0) as tot from sup_mast where sup_active_status = 1",$row);
					$seller_total = $row["tot"];
					
					//get total number of active buyers
					get_rst("select count(0) as tot from member_mast where memb_act_status = 1",$row);
					$memb_total = $row["tot"];
					
					//get total number of active products
					get_rst("select count(0) as tot from prod_mast where prod_status = 1",$row);
					$prod_total = $row["tot"];
					
					//get the company name of highest grossing seller
					get_rst("select s.sup_id, s.sup_company, o.Order_Total
							from sup_mast s
							join (select sup_id, SUM(ord_total) as Order_Total
								  from ord_summary 
								  where YEAR(ord_date) = 2017
									and buyer_action IS NULL
								  group by sup_id) o
							  on s.sup_id = o.sup_id
							order by o.Order_Total desc LIMIT 1", $row);
					$top_seller_name = $row["sup_company"];
					$top_seller_id = $row["sup_id"];
					$total_seller_value = $row["Order_Total"];
					
					//get the company name of highest grossing seller
					get_rst("select m.memb_id, m.memb_fname, m.memb_company, o.Order_Total
							from member_mast m
							join (select user_id, SUM(ord_total) as Order_Total
								  from ord_summary 
								  where YEAR(ord_date) = 2017
									and buyer_action IS NULL
								  group by user_id) o
							  on m.memb_id = o.user_id
							order by o.Order_Total desc LIMIT 1", $row);
					if($row["memb_company"]<>""){
						$top_buyer_name = $row["memb_company"];
					}else{
						$top_buyer_name = $row["memb_fname"];
					}
					$top_buyer_id = $row["memb_id"];
					$total_buyer_value = $row["Order_Total"];
				?>
				<table border="0" width="100%" class="tbl_dash_main">
				<tr>	
					<td width="33%">
						<table class="tbl_dash">
							<tr>
								<th>&nbsp;<span class="glyphicon glyphicon-gift"></span> Orders</th>
							</tr>
							<tr>			
								<td><?=dash_new($ord_pending)?> <a href="order_list.php?ds=0">Orders awaiting dispatch</a></td>	
							</tr>
							<tr>	
								<td><?=dash_total($ord_total)?> <a href="order_list.php">Total Orders</a></td>
							</tr>
							<tr>			
								<th>&nbsp;<span class="glyphicon glyphicon-star"></span> Highest Grossing Seller</th>		
							</tr>
							<tr>	
								<td><a href="edit_sellers.php?id=<?=$top_seller_id?>"><?=$top_seller_name?></a></td>
							</tr>
							<tr>
								<td style="font-size: 14px; color:#eb9316; "> Total Value : <div class="rupee-sign">&#8377; </div> <span><?=$total_seller_value?></span></td>
							</tr>
							<tr>			
								<th>&nbsp;<span class="glyphicon glyphicon-star"></span> Most Valuable Buyer</th>		
							</tr>
							<tr>	
								<td><a href="edit_buyers.php?id=<?=$top_buyer_id?>"><?=$top_buyer_name?></a></td>
							</tr>
							<tr>
								<td style="font-size: 14px; color:#eb9316; "> Total Value : <div class="rupee-sign">&#8377; </div> <span><?=$total_buyer_value?></span></td>
							</tr>
						</table>
					</td>
					<td width="33%">	
						<table class="tbl_dash">
							<tr>			
								<th>&nbsp;<span class="glyphicon glyphicon-user"></span> Site Users</th>		
							</tr>					
							<tr>			
								<td><?=dash_new($seller_new)?> <a href="manage_sellers.php">New Seller requests</a></td>	
							</tr>
							<tr>
								<td><?=dash_total($seller_total)?> <a href="manage_sellers.php">Total Sellers</a></td>
							</tr>
							<tr>
								<td><?=dash_total($memb_total)?> <a href="manage_buyers.php">Total Buyers</a></td>		
							</tr>			
						</table>	
					</td>	
					<td width="33%">	
						<table class="tbl_dash">
							<tr>			
								<th>&nbsp;<span class="glyphicon glyphicon-wrench"></span> Products</th>			
							</tr>
							<tr>	
								<td><?=dash_total($prod_total)?> <a href="manage_prods.php">Total Active Products</a></td>	
							</tr>	
							<tr>	
								<th>&nbsp;<span class="glyphicon glyphicon-star-empty"></span> Top Selling Products</th>		
							</tr>		
							<?php if(get_rst("select prod_id,prod_name,prod_purchased from prod_mast where prod_purchased is not null order by prod_purchased desc LIMIT 0 , 5",$row,$rst)){
							do{					
							?>	
							<tr>
								<td><?=$row["prod_name"]." [".$row["prod_purchased"]." orders]"?></td>
							</tr>					
							<?php				}while($row = mysqli_fetch_array($rst));			}?>	
						</table>
					</td>
				</tr><tr>	<td>	</td>	<td>	</td>	<td>	</td></tr><tr>	<td>	</td>	<td>	</td>	<td>	</td></tr>
				</table>
			  
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
<?php function dash_new($val){ ?>	<span class="dash_new"><?=$val?></span>	<?php }function dash_total($val){ ?>	<span class="dash_total"><?=$val?></span>	<?php }?>
<?php require("../lib/inc_close_connection.php");?>

<?php

require("inc_init.php");

$vid = func_read_qs("id");
$levels_id = func_read_qs("level_id");
$prod_sort = func_read_qs("prod_sort");
$featured = func_read_qs("prod_frd");
$bestseller = func_read_qs("prod_tpsl"); 
$popular = func_read_qs("prod_pspl");
$search_type = func_read_qs("search_criteria");		


if(func_read_qs("chk_brand_ids")."" <> ""){ $chk_brand_ids = func_read_qs("chk_brand_ids");}
if(func_read_qs("chk_level_ids")."" <> ""){ $chk_level_ids = func_read_qs("chk_level_ids");}

$prod_per_page = func_read_qs("prod_per_page");
if($prod_per_page=="") $prod_per_page=func_var($_SESSION["prod_per_page"]);
if($prod_per_page=="") $prod_per_page="12";
$_SESSION["prod_per_page"] = $prod_per_page;

$rs_find = mysqli_query($con, "select * from cms_pages where page_type='hp'");
//Set Page title and other details
if($rs_find){
	while($row = mysqli_fetch_assoc($rs_find)){
		
		$page_heading = $row["page_heading"];
		$page_title = $row["page_title"];
		$meta_key = $row["meta_key"];
		$meta_desc = $row["meta_desc"];
		$middle_panel = $row["middle_panel"];
		
		break;
	}
}

if($bestseller == "1"){
		$page_title = "Company-Name's Best Selling Products";
		$meta_key = "Company-Name Best Seller";
		$meta_desc = "Company-Name's Best Selling Products";
}elseif($popular == "1"){
				$page_title = "Company-Name's Most Popular Products";
		$meta_key = "Company-Name Most Popular Products";
		$meta_desc = "Company-Name's Most Popular Products";
}elseif($featured == "1"){
		$page_title = "Company-Name's Featured Products";
		$meta_key = "Company-Name Featured Products";
		$meta_desc = "Company-Name's Featured Products";
}elseif(func_read_qs("brand") <> ""){
		$page_title = "Buy ".func_read_qs("brand")." Products Online at Company-Name";
		$meta_key = func_read_qs("brand")." Products";
		$meta_desc = "Buy ".func_read_qs("brand")." Products Online at Company-Name";
}elseif(func_read_qs("category") <> ""){
		$page_title = "Buy ".func_read_qs("category")." Online at Company-Name";
		$meta_key = func_read_qs("category")." Products";
		$meta_desc = "Buy ".func_read_qs("category")." Online at Company-Name";
}else{
		$page_title = "Company-Name's Product Listing";
		$meta_key = "Company-Name Products";
		$meta_desc = "Company-Name's Product Listing";	
}

if(isset($_SESSION["memb_id"]) && $_SESSION["memb_id"] <> ""){
$memb_id=$_SESSION["memb_id"];
get_rst("select memb_postcode from member_mast where memb_id=$memb_id",$row_pin);
$_SESSION["memb_pin"]=$row_pin["memb_postcode"];
}
?>

<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>

<title><?php echo(func_var($page_title))?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta name="DESCRIPTION" content="Master List of all the Industrial Hardware Products available on Company-Name.com, Industrial Shopping Redefined"/>
<meta name="KEYWORDS" content="Industrial Hardware Products on Company-Name.com, Industrial Shopping Redefined"/>
<link href="<?php echo $url_root?>/styles/styles.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $url_root?>/styles/bannerstyle.css" rel="stylesheet" type="text/css" />

</head>

<body>

<?php require("header.php"); ?>

<div id="contentwrapper">
<div id="contentcolumn">
<div class="center-panel">
	
	<div class="you-are-here">
		<p align="left">
			<a name="here"></a>
			YOU ARE HERE:<a href="<?=$url_root?>index.php" title="">Home</a> | <?=breadcrumbs()?><span class="you-are-normal">Products Listing</span>
		</p>
	</div>

	<?php
	$txt_key = trim(func_read_qs("searchtext"));
	$txt_key = rtrim($txt_key);
	?>
	<div id="snackbar">Please Login for Wishlisting the Product.</div>
	<form name="frm" method="post" >
		<input type="hidden" name="searchtext" value="<?=$txt_key?>">
		<input type="hidden" name="chk_level_ids[]" value="<?=$chk_level_ids?>">
		<input type="hidden" name="chk_brand_ids[]" value="<?=$chk_brand_ids?>">
	
	<table width="100%" cellpadding="0" cellspacing="0" >
		<tr>
	<?php

	$sql_key="";
	//Search bar string search mechanism
	if($search_type <> ""){
		switch($search_type){
			case "2": if(get_rst("select level_id from levels where level_name='$txt_key'", $row_lvl)){
						$chk_level_ids = $row_lvl["level_id"];
					}
				break;
			case "3": if(get_rst("select brand_id from brand_mast where brand_name='$txt_key'", $row_brd)){
						$chk_brand_ids = $row_brd["brand_id"];
					} 
				break;
		}
	}

	if($txt_key<>""){
		$sql_key = $sql_key." AND (prod_name like '%$txt_key%'";
		$sql_key = $sql_key." OR prod_stockno like '%$txt_key%'";
		$sql_key = $sql_key." OR prod_brand_id in (select brand_id from brand_mast where brand_name like '%$txt_key%' )";
		$sql_key = $sql_key." OR level_parent in (select level_id from levels where level_name like '%$txt_key%' )";
		$sql_key = $sql_key." OR level_parent in (select level_id from levels where level_parent in (select level_id from levels where level_name like '%$txt_key%'))";
		$sql_key = $sql_key.")";
	}	//echo $sql_key;
	$sql_brand="";
	if($chk_brand_ids<>""){
		$sql_brand = $sql_brand . " AND prod_brand_id in ($chk_brand_ids) ";
	}
	$sql_level="";
	if($chk_level_ids<>""){
		$sql_level = $sql_level . " AND (level_parent in ($chk_level_ids) ";
		$sql_level = $sql_level . " or level_parent in (select level_id from levels where level_parent in($chk_level_ids)) ";
		//$sql_level = $sql_level . " or level_parent in (select level_id from levels where level_parent in (select level_id from levels where level_parent in ($chk_level_ids)))";
		$sql_level = $sql_level . ")";
	}
	
	$sel_props = "";
	$sql_prop = "";

	if(!empty($_POST['cbo_prop'])) {
		$sql_prop_where="";
		$prop_count = 0;
		foreach($_POST['cbo_prop'] as $sel) {
			if($sel.""<>""){
				$sel_props = $sel_props.",".$sel;
				if($sql_prop<>""){
					$sql_prop = $sql_prop." OR ";
				}
				$sql_prop = $sql_prop."concat(prop_id,'|',opt_value) = $sel ";
				$prop_count++;
			}
		}
		$sql_prop = " AND (prod_id in (select prod_id from prod_props where (".$sql_prop.")";
				
		$sql_prop = $sql_prop." group by prod_id having count(prod_id)=$prop_count";
		$sql_prop = $sql_prop.") )";
		
		$sel_props = substr($sel_props,1);
		if($sel_props==""){	$sql_prop="";}
	}
	
	$sql_filter = $sql_level;
	$sql_filter = $sql_filter.$sql_brand;
	$sql_filter = $sql_filter.$sql_key;
	?>
	<td class="prop_filter">
	<?php
	if($chk_level_ids<>""){
		if(get_rst("select prop_id, prop_name from prop_mast where prop_status=1 and prop_id in (select prop_id from prod_props where prod_id in (select prod_id from prod_mast where prod_status=1 $sql_filter))",$row_p,$rst_p)){
		?>
		Filter by: 
		<?php
			do{
				$prop_id = $row_p["prop_id"];
				$prop_name=$row_p["prop_name"];
				echo("<b>$prop_name</b>");
				if(get_rst("SELECT distinct opt_value from prod_props where prop_id=$prop_id and prod_id in (select prod_id from prod_mast where prod_status=1 $sql_filter)",$row,$rst)){
					?>
					<select name="cbo_prop[]" onchange="javascript: js_refresh();">
					<option value="">All</option>
					<?php
					do{
						$cbo_val = "'".$prop_id."|".trim($row["opt_value"])."'";
						?>
						<option value="<?=$cbo_val?>" <?=sel_I($sel_props."",$cbo_val)?> ><?=$row["opt_value"]?></option>
						<?php
					}while($row = mysqli_fetch_assoc($rst));
					?>
					</select>&nbsp; &nbsp;
					<?php
				}
			}while($row_p = mysqli_fetch_assoc($rst_p));

		}
	}
	?>
	</td>
	<td class="prod_order">
		Sort by: 
		<select name="prod_sort" onchange="javascript: js_refresh();">
			<option value="" >None</option>
			<?php func_option("Price (Low to High)","and prod_advrt<>1 order by prod_effective_price",$prod_sort)?>
			<?php func_option("Price (High to Low)","order by prod_effective_price desc",$prod_sort)?>
			<?php func_option("Discount (Low to High )","order by ((prod_offerprice-prod_ourprice)/prod_ourprice)*100 desc",$prod_sort)?>
			<?php func_option("Discount (High to Low )","order by ((prod_offerprice-prod_ourprice)/prod_ourprice)*100",$prod_sort)?>
			<?php func_option("Most Recent","order by inserted_date desc",$prod_sort)?>
			<?php func_option("Most Popular","order by prod_purchased desc",$prod_sort)?>
			<?php func_option("Most Viewed","order by prod_viewed desc",$prod_sort)?>
		</select>
	</td>
	<td class="prod_order"> 
		Products per page:
		<select name="prod_per_page" onchange="javascript: js_refresh();">
			<?php func_option("12","12",$prod_per_page)?>
			<?php func_option("15","15",$prod_per_page)?>
			<?php func_option("18","18",$prod_per_page)?>
			<?php func_option("21","21",$prod_per_page)?>
			<?php func_option("24","24",$prod_per_page)?>
			<?php func_option("51","51",$prod_per_page)?>
			<?php func_option("75","75",$prod_per_page)?>
			<?php func_option("99","99",$prod_per_page)?>
		</select>
		
	</td>	
	</tr></table>
	</form>
	<?php
	
	$sql="select prod_id";
	$sql = $sql.",prod_stockno,prod_thumb1,prod_ourprice,prod_offerprice,min_qty"; 
	$sql = $sql.",prod_name,prod_advrt,p.level_parent"; 
	$sql = $sql.",(select brand_name from brand_mast b where b.brand_id = p.prod_brand_id) "; 
	$sql = $sql.",(select level_name from levels l where l.level_id = p.level_parent) "; 
	$sql = $sql.",(SELECT COUNT(0) - IFNULL(SUM(out_of_stock),0) FROM prod_sup s where s.prod_id = p.prod_id and sup_status=1) prod_outofstock"; 
	$sql = $sql." from prod_mast p where prod_id in (select prod_id from parent_products) ";
	if($featured == "1"){
		$sql = $sql."and prod_featured=1 ";
	}

	$sql = $sql.$sql_filter;
	$sql = $sql.$sql_prop;
	
	if($prod_sort.""<>""){
		$sql = $sql." $prod_sort";
	}elseif($bestseller == "1"){
		$sql = $sql." order by prod_purchased desc";
	}elseif($popular == "1"){
		$sql = $sql." order by prod_viewed desc";
	}
	
	//echo($sql);
	$url="$url_root/prod_list.php";
	$rec_limit=$prod_per_page;
	$pg_class="tbl_pages";
	$ba=5
	
	?>		
	<table width="100%" border="1" class="table_prod_list table">
	<tr>
	<?php
	$row_no = 0;
	$id="";
	
	$sql_count = "SELECT count(*) from ($sql) tot_rows ";
	$retval = mysqli_query($con,$sql_count);
	if(! $retval ) { ?>
	  	<br>
		<div class="alert alert-info">
		<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
		No Products found matching your search string! Please enter correct search string or navigate using our Product Category Library.
		</div>
		<button align="right"class="btn btn-warning" onclick="javascript: window.location='enquiry.php'"> Leave us an Enquiry</button>
		<center><img src="images/logo-app.gif" alt="" style="opacity: 0.2" /></center>
	<?php }
	$row = mysqli_fetch_array($retval, MYSQLI_NUM );
	$rec_count = $row[0];
	
	$page = func_read_qs("page");
	if($page==""){
		$page = 0;
		$offset = 0;
	}else{
		$offset = $rec_limit * $page ;
	}
	
	$left_rec = $rec_count - ($page * $rec_limit);

	//--------------------------------------------
	$rst = mysqli_query($con, $sql." LIMIT $offset, $rec_limit");
	//die($sql);
	if(mysqli_num_rows($rst)==0){?>
	<br>
	<div class="alert alert-info">
	<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    No Products found matching your search string! Please enter correct search string or navigate using our Product Category Library.
	</div>
	<button align="right"class="btn btn-warning" onclick="javascript: window.location='enquiry.php'"> Leave us an Enquiry</button>
	<center><img src="<?=$url_root?>/images/logo-app.gif" alt="" style="opacity: 0.2;" /></center>
	<?php }else{
		$status="1";
		$message="Please Login for Wishlisting the Product";		
		if(isset($_SESSION["memb_id"]) && $_SESSION["memb_id"] <> ""){
			$memb_id=$_SESSION["memb_id"];
			$message="Click to Add to Wishlist";
			$status="";
		 }

		 while ($row = mysqli_fetch_assoc($rst)) {
			$col = 0;

			if($row_no>0 AND $row_no % 3==0){
				echo("</tr><tr>");
			}
			get_rst("select level_name from levels where level_id='".$row["level_parent"]."'",$row_level);
			$level_urlname = preg_replace('/[^a-zA-Z0-9]/',"-",$row_level["level_name"]);
	
			$product_urlname = preg_replace('/[^a-zA-Z0-9]/',"-",$row["prod_name"]);

			$url = "$url_root/prod_details.php?product=".$product_urlname."&category=".$level_urlname;
			$prod_id=$row["prod_id"];
			if(intval($row["prod_outofstock"]."")<=0){?>
				<td style="background-color:#ffefef;">
			<?php }else{?>
				<td>
			<?php }
			$sqll = "select sup_alias,out_of_stock,p.* from sup_mast s inner join prod_sup p on s.sup_id=p.sup_id where p.prod_id=0$prod_id and p.sup_status=1 order by final_price";
			if(get_rst($sqll,$roww,$rstt)){
				$qry = "";
				
				if($memb_id <> 0){
					$cqry = "select * from cart_items where memb_id=$memb_id and item_wish=1 and prod_id=0$prod_id";
					$cart_item_id="";
					if(get_rst($cqry,$crow,$crst)){
						$cart_item_id=$crow["cart_item_id"];
					}
				}
				if(intval($row["prod_advrt"]) <> 1){
			?>
			<button  id="bbc"  title="<?=$message?>" onclick="<?php if($status<>""){?>javascript: show_snackbar_msg('snackbar') <?php }elseif($cart_item_id <> ""){?>javascript: remove_from_wishlist('<?=$cart_item_id?>')<?php }else{?>javascript: add_to_wishlist('<?=$roww["sup_id"]?>','<?=$roww["final_price"];?>','','<?=$row["sup_name"];?>','<?=$memb_id;?>','<?=$row["prod_id"];?>','<?=$row["min_qty"]?>','1')<?php }?>" class="glyphicon glyphicon-heart wl <?php if($cart_item_id <> ""){?>wli<?php }?>"></button>
			<?php } }?>
			<a href="<?=$url?>">
				<table border="0" class="table_prod" >	
				<tr>
				<td height="60" align="center" valign="top" style="padding-left:5px">
					<h2><?=$row["prod_name"]?></h2>
				</td>			
				</tr>

				<?php if(intval($row["prod_outofstock"]."")<=0){?>
					<tr><td align='center'>
					<b><font color="red" size="4px">Out of Stock</font></b>
					</td></tr>
				<?php }?>	
				
				<tr>
					<td align="center" height="200px">
						<img xwidth="190" src="<?php echo show_img($row["prod_thumb1"])?>" alt="<?php echo display($row["prod_name"])?>" border="0" />
					</td>
				</tr>
				
				<?php
				if(intval($row["prod_advrt"]) == 1){ ?>
					<tr>
						<td align="center" valign="bottom">
							<b><font color="green" size="2px">Ask for a Quote</font></b>
						</td>
					</tr>	
				<?php }else{
					get_price($row,$price,$our_price,$disc_per);	?>
					<tr>
						<td align="center" valign="bottom">
						<?php if(intval($disc_per) <> 0){?>
							<div class="rupee-sign">&#8377; </div> <span class="our_price"><?php echo formatnumber($our_price)?></span>&nbsp;&nbsp;(<?php echo $disc_per?>%)&nbsp;&nbsp;|&nbsp;&nbsp;Offer
						<?php }
						if($price==0){
							$price = "N/A";
						}else{
							$price = formatnumber($price, 2);
						}?>
						<div class="rupee-sign">&#8377; </div> <span class="final_price"><?=$price?></span>
						<?php if(intval($row["prod_outofstock"]."")>0){?>
						<tr><td align='right'>
						<b><font color="green" size="2px">In Stock</font></b>
						</td></tr>
						<?php }?>
						</td>
					</tr>
				<?php } ?>
				</table>
			</a>
			</td>
			
			<?php
			$row_no++;
		}
		while($row_no>0 AND $row_no % 3 >0){
			echo("<td></td>");
			$row_no++;
		}
	}
	//-------------------------------------------------------------
	?>
	</tr>
	</table>	
	<?php
	$cur_page = $_SERVER['PHP_SELF']."?";
	if($vid."" <> ""){$cur_page = $cur_page."id=".$vid."&";}
	if($levels_id."" <> ""){$cur_page = $cur_page."level_id=".$levels_id."&";}
	if($chk_level_ids."" <> ""){$cur_page = $cur_page."chk_level_ids=".$chk_level_ids."&";}
	if($chk_brand_ids."" <> ""){$cur_page = $cur_page."chk_brand_ids=".$chk_brand_ids."&";}
	if($prod_sort."" <> ""){$cur_page = $cur_page."prod_sort=".$prod_sort."&";}
	if($featured."" <> ""){$cur_page = $cur_page."prod_frd=".$featured."&";}
	if($bestseller."" <> ""){$cur_page = $cur_page."prod_tpsl=".$bestseller."&";}
	if($popular."" <> ""){$cur_page = $cur_page."prod_pspl=".$popular."&";}
	if($txt_key."" <> ""){$cur_page = $cur_page."searchtext=".$txt_key."&";}
	
	$last_page = ceil($rec_count/$rec_limit);
	$start_page=1;
	$end_page=1;

	set_pages($ba,$page+0,$last_page,$start_page,$end_page);

	if($last_page>1){
		echo("<center><ul class=\"pagination page-link\">");
		
		if($page==0){
			echo "<li class=\"active\"><a href=\"#\"> << First</a>";
		}else{
			echo "<li><a href=\"$cur_page&page=0\"><< First</a>";
		}
		echo("</li><li >");
		
		if($page>0){
			echo " <a href=\"$cur_page&page=".($page-1)."\">< Previous</a>";
		}else{
			echo "<a href=\"#\"> < Previous</a>";
		}
		echo("</li>");
		
		if(intval($start_page)>1){
			echo("<li>...</li>");
		}
		for($p=$start_page-1;$p<$end_page;$p++){
			
			if($p==$page){
				echo("<li class=\"active\"><a href=\"#\">");
				echo(($p+1)."</a>");
			}else{
				echo("<li>");
				echo("<a href=\"$cur_page&page=$p\">".($p+1)."</a>");
			}
			echo("</li>");
		}
		if(intval($end_page)<intval($last_page)){
			echo("<li>...</li>");
		}
	
		echo("<li>");
		
		
		if(intval($page+1)<intval($last_page)){
			echo " <a href=\"$cur_page&page=".($page+1)."\">Next > </a>";
		}else{
			echo "<a href=\"#\">Next ></a>";
		}
		echo("</li><li>");
		
		if(intval($page+1)==$last_page){
			echo ("<li class=\"active\" ><a href=\"#\"> Last >></a></li>");
		}else{
			echo " <a href=\"$cur_page&page=".($last_page-1)."\">Last >></a>";
		}
		echo("</li></ul></center>");
	} ?>
</div>

</div>
</div>

<?php
	require("left.php");
	require("footer.php");
?>

</div>
</div>

</body>
<script src="<?=$url_root?>/scripts/chat.js" type="text/javascript"></script>
<script src="<?=$url_root?>/scripts/scripts.js" type="text/javascript"></script>
<script type="text/javascript">

function js_refresh(){
	document.frm.submit();
}

function add_to_wishlist(sup_id,final_price,prod_thumb1,sup_name,memb_id,prod_id,qty,item_wish) 
	{	
		var vsup_id=sup_id
		var vfinal_price=final_price
		var vprod_thumb1=prod_thumb1
		var vsup_name=sup_name
		var vmemb_id=memb_id
		var vprod_id=prod_id
		var vqty=qty
		var vitem_wish=item_wish
		var response= call_ajax("<?=$url_root?>ajax.php","process=add_to_wishlist&sup_id=" + vsup_id + "&final_price=" + vfinal_price + "&prod_thumb1=" + vprod_thumb1 + "&sup_name=" + vsup_name + "&memb_id=" + vmemb_id + "&prod_id=" + vprod_id + "&qty=" + vqty + "&item_wish=" + vitem_wish);
		document.frm.submit();
	}
	
function remove_from_wishlist(cart_item_id)
	{
		var vcart_item_id=cart_item_id
		var response= call_ajax("<?=$url_root?>ajax.php","process=remove_from_wishlist&cart_item_id=" + vcart_item_id )
		document.frm.submit();
}

jQuery(document).ready(function(jQuery){
	jQuery("#bbc[title='Click to Add to Wishlist']").on("click", function(){
        jQuery(this).toggleClass("wli");
    });
});
</script>
</html>

<?php
	require_once("inc_init.php");
	
	$_SESSION["pg_source"] = $_SERVER["SCRIPT_NAME"]."?".$_SERVER["QUERY_STRING"];
	$flag = func_read_qs("flag");
	switch($flag){
		case "1":
			$incomp_msg = "Please enter quantity.";	
			break;
		case "2":
			$incomp_msg = "Please enter number only.";
			break;
		case "3":
			$incomp_msg = "Unable to add item to shopping cart. Please try again.";
			break;
		case "4":
			$incomp_msg="Please enter whole number only.";
			break;
		case "5":
			$incomp_msg="Please enter positive number only.";
			break;
		default:
			$incomp_msg="";
			$_SESSION["from_page"] = prev_page_url();
			break;
	}

	if($_SESSION["product_id"] <> "" ){
		$prod_id = $_SESSION["product_id"];
		$levels_id = $_SESSION["category_id"];
		$_SESSION["product_id"] ="";
		$_SESSION["category_id"] ="";
	}else{
		$prod_name = func_read_qs("product");
		$level_name = func_read_qs("category");
		$prod_urlname = str_replace("-","%",$prod_name);
		$lvl_urlname = str_replace("-","%",$level_name);
		$start = strpos($prod_name,'Size');
		if($start <> ""){
			$last = strpos($prod_name,'-Pack');
			if($last <> ""){
				$end = $last-($start+4);
				$str = substr($prod_name,$start+4,$end);
				$str = preg_replace('/[^0-9]/',"-",$str);
				$str = trim($str,'-');
				$str = str_replace("-","%",$str);
				get_rst("select prod_size from prod_mast where prod_name LIKE '$prod_urlname' and prod_size LIKE '$str %'",$row_size);
				$prod_size = $row_size["prod_size"];
				if($prod_size <> ""){
					get_rst("select level_id from levels where level_name LIKE '$lvl_urlname'", $row_lvl);
					$levels_id = $row_lvl["level_id"];
					get_rst("select prod_id from prod_mast where prod_name LIKE '$prod_urlname' and prod_size LIKE '$prod_size'", $row_prod);
					$prod_id= $row_prod["prod_id"];
				}else{
					get_rst("select level_id from levels where level_name LIKE '$lvl_urlname'", $row_lvl);
					$levels_id = $row_lvl["level_id"];
					get_rst("select prod_id from prod_mast where prod_name LIKE '$prod_urlname'", $row_prod);
					$prod_id= $row_prod["prod_id"];
				}
			}else{
				$last = strpos($prod_name,"-model");
				if($last <> ""){
					$end = $last-($start+4);
					$str = substr($prod_name,$start+4,$end);
					$str = preg_replace('/[^0-9]/',"-",$str);
					$str = trim($str,'-');
					$str = str_replace("-","%",$str);
					get_rst("select prod_size from prod_mast where prod_name LIKE '$prod_urlname' and prod_size LIKE '$str %'",$row_size);
					$prod_size = $row_size["prod_size"];
					
					if($prod_size <> ""){
						get_rst("select level_id from levels where level_name LIKE '$lvl_urlname'", $row_lvl);
						$levels_id = $row_lvl["level_id"];
						get_rst("select prod_id from prod_mast where prod_name LIKE '$prod_urlname' and prod_size LIKE '$prod_size'", $row_prod);
						$prod_id= $row_prod["prod_id"];
					}else{
						get_rst("select level_id from levels where level_name LIKE '$lvl_urlname'", $row_lvl);
						$levels_id = $row_lvl["level_id"];
						get_rst("select prod_id from prod_mast where prod_name LIKE '$prod_urlname'", $row_prod);
						$prod_id= $row_prod["prod_id"];
					}
				}else{
					$str = substr($prod_name,$start+4);
					$str = preg_replace('/[^0-9]/',"-",$str);
					$str = trim($str,'-');
					$str = str_replace("-","%",$str);
					get_rst("select prod_size from prod_mast where prod_name LIKE '$prod_urlname' and prod_size LIKE '$str %'",$row_size);
					$prod_size = $row_size["prod_size"];
					if($prod_size <> ""){
						get_rst("select level_id from levels where level_name LIKE '$lvl_urlname'", $row_lvl);
						$levels_id = $row_lvl["level_id"];
						get_rst("select prod_id from prod_mast where prod_name LIKE '$prod_urlname' and prod_size LIKE '$prod_size'", $row_prod);
						$prod_id= $row_prod["prod_id"];
					}else{
						get_rst("select level_id from levels where level_name LIKE '$lvl_urlname'", $row_lvl);
						$levels_id = $row_lvl["level_id"];
						get_rst("select prod_id from prod_mast where prod_name LIKE '$prod_urlname'", $row_prod);
						$prod_id= $row_prod["prod_id"];
					}
				}
			}
		}else{
			get_rst("select level_id from levels where level_name LIKE '$lvl_urlname'", $row_lvl);
			$levels_id = $row_lvl["level_id"];
			get_rst("select prod_id from prod_mast where prod_name LIKE '$prod_urlname'", $row_prod);
			$prod_id= $row_prod["prod_id"];
		}
	}
	
	$prod_found=false;
	$rs_prod = mysqli_query($con, "select *, p.meta_key as prod_meta_key, p.meta_desc as prod_meta_desc from prod_mast p inner join brand_mast b on p.prod_brand_id=b.brand_id where prod_id=0$prod_id");
	
	if($rs_prod)
	{
		$row = mysqli_fetch_assoc($rs_prod);
		$prod_found=true;
	}
?>

<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?=$row["prod_name"]?> - Available at Company-Name</title>
<link href="<?=$url_root?>/styles/styles.css" rel="stylesheet" type="text/css" />
<meta name="DESCRIPTION" content="<?=$row["prod_meta_desc"]?>"/>
<meta name="KEYWORDS" content="<?=$row["prod_meta_key"]?>"/>

<style>
.div_tree{
  position:fixed;
  top:0px;
  left:0px;
  width:100%;
  height:100%;
  background:rgba(200,200,255,0.5);
  z-index:10;
  
}

.div_inner
{
    position:relative; 
    height:74%;
    width:85%;
    border: solid 1px #000000;
    background-color:#FFFFFF;
}

.span_cat
{
    font-family:Verdana;
    font-size:12px;
    }
    
    .span_cat:hover
    {
    background-color: #CCCCFF;
    cursor:pointer;
    cursor:hand;
}

#offer-box {
	color:#eb9316;
	border: dashed 2px #eb9316;
	border-radius: 5px;
	font-size: 1.2em;
	width: 62%;
}

#offer-box a{
	color: #eb9316;
}

#offer-box a:hover{
	text-decoration: none;
}
.img-pop-close-cursor{
	cursor:hand; 
	padding-right: 10px; 
	padding-top: 10px;
}
#th-prod{padding:0 0 5px;margin-top:-15px;margin-left:10px;font-size:1.0em !important;color: #000 !important; border-bottom: none !important;}
</style>
</head>

<body>
<?php
	require("header.php");
    if($prod_found){ 
		$prod_id=$row["prod_id"];
		$prod_name=$row["prod_name"];
		$prod_stockno=$row["prod_stockno"];
		$brand_name=$row["brand_name"];
		$prod_free_text=$row["prod_briefdesc"];
		$prod_offerprice = intval($row["prod_offerprice"]);
		$prod_ourprice=intval($row["prod_ourprice"]);
		$price = $prod_ourprice;
		$offer=false;
		if($prod_offerprice > 0){ 
			$price=$prod_offerprice;
			$offer=true;
		}
		$min_qty = $row["min_qty"];
		$prod_thumb1=$row["prod_thumb1"];
		$prod_thumb2=$row["prod_thumb2"];
		$prod_thumb3=$row["prod_thumb3"];
		$prod_thumb4=$row["prod_thumb4"];
		$prod_large1=$row["prod_large1"];
		$prod_large2=$row["prod_large2"];
		$prod_large3=$row["prod_large3"];
		$prod_large4=$row["prod_large4"];
		$prod_pricestatus = $row["prod_pricestatus"];	
		$is_vat = $row["is_vat"];
		$prod_level_name = "";
		$prod_status = $row["prod_status"];
		$parent_product = $row["parent_product"];
		$prod_size = $row["prod_size"];
		$prod_advrt = $row["prod_advrt"];
		$img_arr = array();
		for($i=1;$i<=4;$i++){
			$img_arr["prod_thumb".$i] = $row["prod_thumb".$i];
			//$img_arr["prod_large".$i] = $row["prod_large".$i];
			?>
			<img id="pop_large_<?=$i?>" src="<?=show_img($row["prod_large".$i])?>" style="display:none;">
		<?php
		}
		if($prod_status <> "1"){ $incomp_msg = "This product is no longer available. Please continue browsing the rest of the site.";}
	}
	$incomp_msg = func_read_qs("incomp_msg") & "<br>$incomp_msg";
?>

<div class="div_tree" id="div_cat_tree" style="display:none;" >
	<div id="div_cat_inner" class="div_inner" style="left:101px">
		<table border="0" xclass="table_border" width="100%">
			<tr>
				<td valign="top" rowspan="4" class="pad-td">
					<?php
					for($i=1;$i<=4;$i++){
						if($row["prod_thumb".$i] <> ""){?>
							<img class="desc-image" id="thumb$i"
							width="50px" height="50px" src="<?=show_img($row["prod_thumb".$i])?>" 
							onClick="javascript:show_large(<?=$i?>);">
							<br><br>
						<?php
						}
					}?>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    
				</td>
				<td style="min-height:520px" align="center" width="1210">
					<img id="img_pop_large" >
				</td>
				<td valign="top">
					<span class="color-amber glyphicon glyphicon-remove glyf img-pop-close-cursor" onclick="js_close_tree();">  </span>&nbsp;
				</td>
			</tr>
		</table>
	</div>
</div>

<div id="contentwrapper">
 <div id="contentcolumn">
  <div class="center-panel">
    <?php if($prod_status == "1"){?>
			<div class="you-are-here">			
			  <p align="left"> YOU ARE HERE:<a href="<?=$url_root?>index.php" title="Home">Home</a><?=productbreadcrumb_new($levels_id,$prod_id,$prod_level_name)?><span class='you-are-normal'>| <?=$prod_name?></span></p>
			</div>
    <?php }?>
	<br>
    <?php if($prod_found){
		if($incomp_msg <> ""){?>
		<hr color="#FF0000" size="1px" width="100%" align="center" />
		<p>
			<span class="red">
			<?=$incomp_msg?>
			</span>
		</p>		
		<hr color="#FF0000" size="1px" width="100%" align="center" />
		<?php }
		if($prod_status == "1"){?>
   </div>
    
	<div id="snackbar">Please Login for Wishlisting the Product.</div>
	<form name="frmdetails" method="post" action="<?=$url_root?>basket.php" onSubmit="Javascript: return validate();">
		<input type="hidden" name="sup_id" value="">
		<input type="hidden" name="act" value="add">
		<input type="hidden" id="prod_name" name="prod_name" value="<?=$prod_name?>">
		<input type="hidden" id="stock_no" name="stock_no" value="<?=$prod_stockno?>">
		<input type="hidden" name="img_thumb" value="">
		<input type="hidden" name="item_wish" value="0">	
		
      <center>
	  <table align="center" style="width:98%" border="0" align="center" class="table_border">
		  <tr>
		  <th class="th-normal" colspan="10" width="30px" align="left">
		  &nbsp;<h1 id="th-prod"><?=$prod_name?></h1></th>
        </tr>
	  <tr>
          <td width="55%" rowspan="6" valign="top">
		  <br>
		 <table border="0" width="100%" xclass="table_border" cellpadding="0" cellspacing="0">

			<tr>
			<td valign="top" rowspan="4">
		   <?php
		   for($i=1;$i<=4;$i++){
			//if($img_arr["prod_large".$i] <> ""){
			if($row["prod_thumb".$i] <> ""){
			?>
			
			 <img class="desc-image" id="thumb$i" class="prod_thumb" 
			  width="50px" height="50px" src="<?=show_img($row["prod_thumb".$i]);?>" 
			  onmousemove="javascript:swap_thumb(this);" onClick="javascript:open_cat('<?=$i?>');">
			 <br><br>
			<?php
			}
		   }
		   ?>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    
		   </td>
		   <td xrowspan="10" valign="center" height="205">
		   <img id="img_large" class="desc-image" src="<?=show_img($prod_thumb1)?>" alt="<?=display($prod_name)?>" border="0" onclick="javascript: open_cat('1');" />
		   </td>
		   </tr>
   
		</table>
		</td>
		<td colspan="4" align="left" valign="top" style="border-left:solid 1px #cccccc;border-right:solid 1px #cccccc;">
		  
		<table class="prod-brief-desc-tab" width="100%" cellspacing="10">

			<tr>
			  <td colspan="1" align="left" valign="top">SKU: </td><td><?=$prod_stockno?></td>
			</tr>
			<tr>
			  <td colspan="1" align="left" valign="top">Brand: </td><td><?=$brand_name?></td>
			</tr>
			<?php 
				$message="Please Login for Wishlisting the Product";		
				if(isset($_SESSION["memb_id"]) && $_SESSION["memb_id"] <> ""){
					$memb_id=$_SESSION["memb_id"];
					$message="";
					get_rst("select memb_postcode, memb_fname, memb_sname, memb_email, memb_tel from member_mast where memb_id=$memb_id", $row_p);
				} 
		if($prod_advrt == 0){
			get_price($row,$price,$our_price,$disc_per);
			if(intval($disc_per) <> 0){?>
				<tr>
				  <td colspan="1">
					Unit Price</td><td><div class="rupee-sign">&#8377; </div>  <span class="our_price"><?=formatnumber($our_price)?></span> Discount (<?=$disc_per?>%)
				  </td>
				</tr>		
			<?php }?>			
				<tr>
				  <td colspan="1">
					<?php if(intval($disc_per) <> 0){?>Offer Price:<?php }else{?> Price: <?php } ?></td>
					<td> <span>&#8377; <?=formatnumber($price,2)?></span>
				  </td>
				</tr>
				<?php if(intval($price >= 10000) <> 0){?>
				<tr>
				  <td colspan="2" align="center">
						<div id="offer-box">
						<a href="<?=$url_root?>offer.php">	Apply Coupon* | <span style="color: #6190A1; font-size: 1.1em;">ALIF500</span></a>
						</div>
				  </td>
				</tr>				
				<?php }else{ ?>
				<tr>
				  <td colspan="2" align="center">
						<div id="offer-box">
						<a href="<?=$url_root?>offer.php">	Apply Coupon* | <span style="color: #6190A1; font-size: 1.1em;">1STBUY</span></a>
						</div>
				  </td>
				</tr>
				<?php } ?>
			 <input type="hidden" name="prod_id" value="<?=$prod_id?>">
			 <input type="hidden" name="sup_name" value="">
			 <input type="hidden" name="prod_price" value="<? echo(number_format($price,2,".",false));?>">
			 <?php if($is_vat == "1"){ $vat_percent = $sv_vat_percent; }else{ $vat_percent = "0";}?>
			 <input type="hidden" name="vat_percent" value="<?=$vat_percent?>">
			 <input type="hidden" name="cart_price" value="<?=number_format($price,2,".",false)?>">
			  						  
			<tr bgcolor="#dfedfa">
			  <td xwidth="13%" align="left">Quantity: </td>
			  <?php $show_qty = 1; 
			  if($min_qty > 1){ $show_qty = $min_qty;} ?>
			  <td xwidth="1%" align="left" bgcolor="#dfedfa">
				<input id="qty" name="qty" type="text" class="form-control textbox-nano" size="2" maxlength="4" xclass="search-area" value="<?=$show_qty?>" onblur="javascript:verify_qty('qty', <?=$show_qty?>);" onkeypress="return isNumberKey(event)"/>
				<button class="check_delivery btn btn-warning" style="height: 34px;" type="button" onclick = "javascript:js_qty_up('qty');">
					<span class="color-white glyphicon glyphicon-chevron-up glyf">  </span>
				</button>
				<button class="check_delivery btn btn-warning" style="height: 34px;" type="button" onclick = "javascript:js_qty_down('qty', <?=$show_qty?>);">
					<span class="color-white glyphicon glyphicon-chevron-down glyf">  </span>
				</button>
			  </td>
			</tr>

			<?php 
			 if($parent_product == 0){
				 if(get_rst("select prod_id from child_products where parent_id = 0$prod_id",$row)){?>
			<tr>
				<td>Select Size:</td>
				<td>
					<select name="sub_product" class="form-control textbox-auto" id="sub_product" onchange="javascript:handleSelect(this)">
						<option value=""><?=$prod_size?></option>
						<?=create_cbo("select concat(prod_id,'|',level_parent),prod_size from prod_mast where parent_product = 0$prod_id and prod_status=1")?>
					</select>
				</td>
		  </tr>
		  <?php }
			 }else{?>
				<tr>
				<?php get_rst("select parent_id from child_products where prod_id=0$prod_id",$row_c);
					$child_product = $row_c["parent_id"];
				?>
			<td>Size:</td>
			<td>
				<select name="sub_product" class="form-control textbox-auto" id="sub_product" onchange="javascript:handleSelect(this)">
					<option value=""><?=$prod_size?></option>
					<?=create_cbo("select concat(prod_id,'|',level_parent),prod_size from prod_mast where prod_status=1 and parent_product=0$child_product and prod_id<>0$prod_id or prod_id=0$child_product ")?>
				</select>
			</td>
			</tr>
			<?php } ?>
			<tr>	  
				<td xwidth="13%" align="t">Check Delivery: </td>
				<td xwidth="1%" align="left" bgcolor="#dfedfa">
					<input name="memb_pin" class="form-control textbox-sml" id="memb_pin" type="text" size="10" maxlength="6" xclass="search-area" 
						value="<?=$row_p["memb_postcode"]?>" onkeypress="return isNumberKey(event)" onblur="javascript:check_delivery();"/>
					<button class="check_delivery btn btn-warning" type="button" onclick = "javascript:check_delivery();">Go</button>
					<p id="msg_pin" style="margin-top:5px"></p>
					<p id="check" style="margin-top:5px"></p>

				</td>
				</tr>
				<tr>
					<td colspan="2"><p class="color-amber">For Bulk buying call : 77 9849 5353 or ask for Quote <a href="mailto:sales@Company-Name.com?subject=Contact%20Customer%20care" title="sales@Company-Name.com"><span class="color-amber glyphicon glyphicon-envelope glyf"></span> </a></p>
				</td>
				</tr>
				<?php }else{
					$sql = "select sup_alias, out_of_stock,p.* from sup_mast s inner join prod_sup p on s.sup_id=p.sup_id where p.prod_id=0$prod_id and p.sup_status=1";
					get_rst($sql, $row_ad);
					get_rst
					?>
					<input type="hidden" name="sup_name" value="">
					 <input type="hidden" name="prod_id" value="<?=$prod_id?>">
					 <input type="hidden" name="qty" value="0">
					<input type="hidden" name="prod_price" value="<? echo(number_format($price,2,".",false));?>">
				<tr>
					<td colspan="2"><p class="theme-blue">This product is available only upon inquiry.</p></td>
				</tr>
				<tr>
				<td colspan="2"><u>Enter Details</u></td>
				</tr>
				<tr>
				<td>Name:</td>
				<td><input name="buyer_name" class="form-control textbox-mid" id="buyer_name" type="text" size="10" maxlength="100" value="<?=$row_p["memb_fname"]?> <?=$row_p["memb_sname"]?>" />
				</td>
				</tr>
				<tr>
				<td>Email:</td>
				<td><input name="buyer_email" class="form-control textbox-mid" id="buyer_email" type="text" size="10" maxlength="50" value="<?=$row_p["memb_email"]?>" />
				</td>
				</tr>
				<tr>
				<td>Mobile:</td>
				<td><input name="buyer_mob" class="form-control textbox-mid" onkeypress="return isNumberKey(event)" id="buyer_mob" type="text" size="10" maxlength="11" value="<?=$row_p["memb_tel"]?>" />
				</td>
				</tr>
				
				<tr>
					<td>
					<input id="sendInquiry" type="button" class="btn btn-warning" 
				    onclick="javascript: js_send_inquiry('Your product inquiry has been sent successfully')" value=" Send Inquiry " 
					title="Send Product Inquiry"/>&nbsp;</td>
					<?php if($message==""){?>
					<td>
					<input type="button" class="btn btn-warning" 
					onclick="<?php if($message<>""){?>javascript: show_snackbar_msg('snackbar'); <?php
							}else{?>javascript: js_send_inquiry('The product has been added to wishlist.'); js_add_to_cart_advrt('<?=$row_ad["sup_id"]?>','<?=$row_ad["sup_alias"];?>',
							'<?=$row_ad["final_price"];?>','1')<?php }?>" value=" Add to Wishlist " 
					xstyle="padding-left:5px;padding-right:5px;" title="<?=$message?>"/>&nbsp;</td>
					<?php } ?>
				</tr>
			<?php }?>	
       </table>
      </form>
	  </td>
	  <tr>
  </table>

	<table style="width:98%" border="1" class="table_border">
		<?php
		if($prod_advrt == 0){
			$sql = "select sup_alias,out_of_stock,p.* from sup_mast s inner join prod_sup p on s.sup_id=p.sup_id where p.prod_id=0$prod_id and p.sup_status=1 order by final_price";
			if(get_rst($sql,$row,$rst)){
				if(mysqli_num_rows($rst)>1){?>
				<tr>
					<th class="th-normal" colspan="5">&nbsp;This product is available with following Suppliers</th>
				</tr>
				<?php }
				do{
					$cls = "";
					if($row["out_of_stock"]=="1"){
						$cls = "class='del_pend'";
					} ?>
					<tr <?=$cls?>>
						<td>
						<?php echo $row["sup_alias"];?>
						
						</td>
						<td><img src=""></td>
						<td><div class="rupee-sign">&#8377; </div> <?=$row["final_price"];?></td>
						<td align="right"><input type="button" class="btn btn-warning" onclick="<?php if($message<>""){?>javascript: show_snackbar_msg('snackbar') <?php }else{?>javascript: js_add_to_cart('<?=$row["sup_id"]?>','<?=$row["sup_alias"];?>','<?=$row["final_price"];?>','1')<?php }?>" value=" Add to Wishlist " xstyle="padding-left:5px;padding-right:5px;" title="<?=$message?>"/>&nbsp;</td>
						<?php if(1){?>
							<td align="right" class="back_red"><span class="back_red">Out of Stock</span>&nbsp;						
						<?php }else{?>
							<td align="right">
							<input type="button" class="btn btn-warning" onclick="javascript: js_add_to_cart('<?=$row["sup_id"]?>','<?=$row["sup_alias"];?>','<?=$row["final_price"];?>','0')" value=" Add to Cart " style="padding-left:30px;padding-right:30px;"/>
						<?php }?>
						&nbsp;&nbsp;</td>					
					</tr>
					<?php
				}while($row = mysqli_fetch_assoc($rst));
			}else{?>
				<tr>
					<th class="th-normal" colspan="10">&nbsp;Sorry! This product is not available with any Suppliers at the moment.</th>
				</tr>
			<?php }
		}?>
		
	</table>	
	<!-- Display detail descripton file -->
	<?php if(file_exists("prod_desc/".$prod_stockno."/Template.htm")) { ?>
		<br>
		<table style="width:98%;" class="table_border">
			<tr>
				<th class="th-normal">&nbsp;Product Desciption</th>
			</tr>		
			<tr>
				<td align="left">
					<?php include("prod_desc/".$prod_stockno."/Template.htm"); ?>
				</td>
			</tr>		
		</table>
	<?php }elseif($prod_free_text."" <> ""){ ?>
		<br>
		<table style="width:98%;" class="table_border">
			<tr>
				<th class="th-normal">&nbsp;Product Desciption</th>
			</tr>
			<tr>
				<td align="left">
					<?=stripcslashes($prod_free_text); ?>
				</td>
			</tr>		
		</table>
	<?php } ?>
	<hr><br>
  <?php
	
	$sql = "select view_count,p.prod_id,prod_advrt,prod_stockno,prod_name,prod_thumb1,prod_ourprice,prod_offerprice,level_parent from prod_mast p inner join "; 
	$sql = $sql." prod_viewed v on p.prod_id=v.prod_id where prod_status=1 and p.prod_id<>0$prod_id and parent_product=0 and session_id='".session_id()."' order by view_date desc LIMIT 4";

  //echo($sql);
  if(get_rst($sql,$row,$rst)){?>
	<table width="98%" border="1" class="table_prod_list">
		<tr>
			<th class="th-normal" colspan="10">&nbsp;Recently Viewed Products</th>
		</tr>
		<tr class="display_recent">
		<?php
		$row_count = 0;
		do{
			get_rst("select level_name from levels where level_id='".$row["level_parent"]."'",$row_level);
			$url_recentlevel = preg_replace('/[^a-zA-Z0-9]/',"-",$row_level["level_name"]);
			$url_recentprod = preg_replace('/[^a-zA-Z0-9]/',"-",$row["prod_name"]);
			$url = "$url_root/prod_details.php?product=".$url_recentprod."&category=".$url_recentlevel;
			?>
			<td>
			
			<table border="0" width="98%" class="table_prod" style="width: 266px">
				<tr>
				<td height="60" align="center" valign="bottom" style="padding-top:5px;">
					<h3><?=$row["prod_stockno"]?><br><?=$row["prod_name"]?></h3>				
				</td>			
				</tr>

				<tr>
					<td align="center" height="200px">
					<a href="<?=$url?>"><img xwidth="190" src="<?=show_img($row["prod_thumb1"])?>" alt="<?=display($row["prod_name"])?>" border="0" /></a>
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
					get_price($row,$price,$our_price,$disc_per);
				?>
				
				<tr>
					<td valign="bottom" align="center">
					<?php if(intval($price)<intval($our_price)){?>
						<div class="rupee-sign">&#8377; </div> <span class="our_price"><?=formatnumber($our_price)?></span>&nbsp;&nbsp;(<?=$disc_per?>%)&nbsp;&nbsp;Offer
					<?php }?>
					<div class="rupee-sign">&#8377; </div> <span class="final_price"><?=formatnumber($price)?></span>
					<?php if(intval($price)<intval($our_price)){?>
						
					<?php }?>
					
					</td>
				</tr>
				<?php } ?>
				</table>
			</td>		
			
			<?php
			$row_count++;
		}while($row = mysqli_fetch_assoc($rst) AND $row_count<3); ?>
		</tr>
		<?php
		if($row){ ?>
			<tr>
			<th class="th-normal" colspan="3"><br>
			<a href="javascript: js_show_all_viewed(<?=$prod_id?>)"><span id="up-down-glyf" class="glyphicon glyphicon-menu-down glyf"></span> Display other recently viewed products </a>
			<br><br>
			</th>
			</tr>
			
			<tr>
			<td colspan="10">
			<span id="span_view_all" style="display:none;"></span>
			</td>
			</tr>
			<?php
		}
		?>
  </table>
  <br>
  <?php }
    }
  } ?>
</div>
</div>
<?php
	require_once("left.php");
	require_once("footer.php");
	
	prod_viewed($prod_id);
?>
<script src="<?=$url_root?>/scripts/prod_details.js" type="text/javascript"></script>
<script type="text/javascript">
function handleSelect(obj)
{ 
	if(obj.value != ""){
		v_add = obj.value
		v_add_arr = v_add.split('|');
		var var_res = call_ajax("<?=$url_root?>/ajax.php","process=child_product&product_id=" + v_add_arr[0] + "&category_id=" + v_add_arr[1]);
		var prod_url = var_res.split('|');
		prod_url[0] = prod_url[0].replace(/[^a-zA-Z0-9]/gi, '-');
		prod_url[1] = prod_url[1].replace(/[^a-zA-Z0-9]/gi, '-');
		window.location = "<?=$url_root?>prod_details.php?product="+ prod_url[0] + "&category="+prod_url[1];
	}
}
$(window).load(function() {
	$("#sub_product option:first").attr('selected','selected');
});

function js_send_inquiry(success_msg) {
	var buyer_name = document.getElementById("buyer_name").value;
	var mobile = document.getElementById("buyer_mob").value;
	var email = document.getElementById("buyer_email").value;
	var prod_name = document.getElementById("prod_name").value;
	var prod_sku = document.getElementById("stock_no").value;

	var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
			if (!filter.test(email)) {
				alert("Please enter valid email.");
				return false;
			}
	if(mobile.length < 10){
		alert("Please enter valid mobile number");
		return false;
	}
	if(buyer_name != "" && mobile != "" && email != ""){
		var result = call_ajax("<?=$url_root?>ajax.php","process=send_inquiry&prod_name="+prod_name+"&prod_sku="+prod_sku+"&buyer_name="+buyer_name+"&buyer_mobile="+mobile+"&buyer_email="+email);
		if(result == "false"){
			return false;
		}
	}else{
		alert("Please enter all the details");
		return false;
	}
	document.getElementById("snackbar").innerHTML = success_msg;
	show_snackbar_msg('snackbar');
	return true;
}
function get_delivery_status(pincode){
	document.getElementById("check").innerHTML = "";
	v_valid="";
	var obj_lbl = document.getElementById("msg_pin")
	if(pincode!=""){
		if(pincode.length < 6){
			obj_lbl.innerHTML = "Invalid pincode"
			obj_lbl.style.color="#FF5588"
			return false
		}else{
			v_valid = call_ajax("<?=$url_root?>/ajax.php","process=check_delivery_pin&pincode=" + pincode) 
			if(v_valid=="Available"){
				obj_lbl.innerHTML = "Delivery Available"
				obj_lbl.style.color="#1E6F00"
				return true;
			}else{
				obj_lbl.innerHTML = "Delivery not Available at this location."
				obj_lbl.style.color="#FF5588"
				return false
			}
		}
	}
}
function check_delivery(){
	var obj = document.getElementById("memb_pin").value;
	var obj_lbl = document.getElementById("check");
	v_valid = call_ajax("<?=$url_root?>/ajax.php","process=check_delivery_pin&pincode=" + obj) 
	
	if(obj!=""){
		if(obj.length < 6){
			document.getElementById("msg_pin").innerHTML="";
			obj_lbl.innerHTML = "Invalid pincode"
			obj_lbl.style.color="#FF5588"
			return false;
		}else{
			obj = obj.substring(0,3);
			if(v_valid == "Available"){
				if(obj == 411 || obj == 410 || obj == 412){
					document.getElementById("msg_pin").innerHTML="";
					obj_lbl.innerHTML = "Delivery within 2 days"
					obj_lbl.style.color="#1E6F00"
				}else{
					document.getElementById("msg_pin").innerHTML="";
					obj_lbl.innerHTML = "Delivery within 7 days"
					obj_lbl.style.color="#1E6F00"
				}
			}else{
				document.getElementById("msg_pin").innerHTML="";
				obj_lbl.innerHTML = "Delivery not Available at this location"
				obj_lbl.style.color="#FF5588"
				return false;
			}
		}
	}else{
		document.getElementById("msg_pin").innerHTML="";
		obj_lbl.innerHTML = "Enter valid pincode"
		obj_lbl.style.color="#FF5588"
		return false;
	}
}

var v_var_all_viewed="";
function js_show_all_viewed(prods_id){
	var v_obj = document.getElementById("span_view_all");
	var a_obj = document.getElementById("up-down-glyf");
	if(v_var_all_viewed==""){
		if(v_obj.innerHTML ==""){
		//alert(v_prods)	
			var v_prods = call_ajax("<?=$url_root?>ajax.php","process=show_all_viewed&prod_id="+prods_id)
			v_obj.innerHTML = v_prods;
		}
		v_obj.style.display="";
		v_var_all_viewed = "1"
		a_obj.className = "glyphicon glyphicon-menu-up glyf"
		window.scroll();
	}else{
		v_var_all_viewed=""
		v_obj.style.display="none";
		a_obj.className = "glyphicon glyphicon-menu-down glyf"
	}
}
function js_qty_up(obj_id){
	var qty = parseInt(document.getElementById(obj_id).value);
	qty += 1 ;
	document.getElementById(obj_id).value = qty;
}

function js_qty_down(obj_id, min_qty){
	var qty = parseInt(document.getElementById(obj_id).value);;
	if(qty > 1 && qty>min_qty){
		qty -= 1;
	}
	document.getElementById(obj_id).value = qty;
}

function verify_qty(obj_id, min_qty){
	var qty = parseInt(document.getElementById(obj_id).value);;
	if(qty < min_qty){
		document.getElementById(obj_id).value = min_qty;
	}
}
</script>
</body>
<script src="<?=$url_root?>/scripts/chat.js" type="text/javascript"></script>
<script src="<?=$url_root?>/scripts/scripts.js" type="text/javascript"></script>
</html>
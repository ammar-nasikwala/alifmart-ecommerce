<script>
	function js_forgot(){
		if(frmsignin.email.value=="" || frmsignin.email.value=="Email address..."){
			alert("Please provide your Email in the above box.");
		}else{
			document.frmsignin.frm_sign_in.value="f";
			document.frmsignin.submit();
		}
	}

	/* toggle category images*/	
	$(function($) {
		$(".toggle-me").mouseover(function() { 
		var $img = $(this).find('img')
		var src = $img.attr('src', $img.attr('src').replace(/(grey.png)$/, '') + 'copper.png')
		})
		.mouseout(function() { 
		var $img = $(this).find('img')
		var src = $img.attr("src").replace("copper", "grey");
			$img.attr("src", src);
		});
	});

</script>
<style>
//Styles for search bar
.dropdown.dropdown-lg .dropdown-menu {
    margin-top: -1px;
    padding: 6px 20px;
}

.search-btn{
    border-radius: 0;
	height: 30px;
}

.tooltip{
	z-index: 1;
}

.tooltip-inner{
	width: 150px;
}

#prof-icon{
	margin-top: 15px;
}

#result{
	border: 1px solid #eb9316;
	background: #fff;
	position: absolute;
	z-index: 99;
	border-radius: 4px;
	width: 27%;
	display: none;
}

</style>

<?php

$wish_items = 0;
$memb_id = 0;
if(isset($_SESSION["memb_id"]) && $_SESSION["memb_id"] <> ""){
	$memb_id=$_SESSION["memb_id"];
}

if(!empty($_POST['chk_level_ids'])) {
    foreach($_POST['chk_level_ids'] as $check) {
		$chk_level_ids = $chk_level_ids.",".$check;
    }
	$chk_level_ids = substr($chk_level_ids,1);
}

if(func_read_qs("level_id")<>""){
	$chk_level_ids = func_read_qs("level_id");
}
if(func_read_qs("category")<>""){
	$category = func_read_qs("category");
	$category = str_replace("-","%",$category);
	get_rst("select level_id from levels where level_name LIKE '$category' LIMIT 1",$row1);
	$chk_level_ids = $row1["level_id"];
}

if(!empty($_POST['chk_brand_ids'])) {
    foreach($_POST['chk_brand_ids'] as $check) {
		$chk_brand_ids = $chk_brand_ids.",".$check;
    }
	$chk_brand_ids = substr($chk_brand_ids,1);
}

if(func_read_qs("brand_id")<>""){
	$chk_brand_ids = func_read_qs("brand_id");
}

if(func_read_qs("brand")<>""){
	$brand = func_read_qs("brand");
	$brand = str_replace("-","%",$brand);
	get_rst("select brand_id from brand_mast where brand_name LIKE '$brand'",$row2);
	$chk_brand_ids = $row2["brand_id"];
}


if(func_read_qs("frm_sign_in")=="f"){
	$email = func_read_qs("email");

	if($email <> ""){
		$qry = "select memb_fname,memb_email,memb_pwd from member_mast where memb_email='".$email."'";
		if(get_rst($qry, $row)){
			$fld_arr = array();
			$fld_arr["memb_fname"] = $row["memb_fname"];
			$fld_arr["memb_email"] = $row["memb_email"];
			$fld_arr["memb_pwd"] = $row["memb_pwd"];
			
			$mail_body = push_body("buyer_forgot.txt",$fld_arr);
			send_mail($row["memb_email"],"Company-Name - Buyer Forgot Password",$mail_body);
			js_alert("The password has been mailed to your registered email address");
		}else{
			js_alert("Sorry! This email address does not exist");
		}
	}
}

if(func_read_qs("frm_sign_in")=="s"){
	$email = func_read_qs("email");
	$pwd = func_read_qs("pwd");
	if($email <> "" and $pwd <> ""){
		$row = "";
		$sql = "select memb_id,memb_email,memb_fname, memb_act_status from member_mast where memb_email='".$email."' and memb_pwd='".$pwd."'";
		get_rst($sql,$row);
		if($row <> ""){
		
			if($row["memb_act_status"] <> 1){
				js_alert("Your account has not been activated yet. Please click on activation link sent to your registered email ID to activate your account. Check your Spam folder for the activation email.");
			}else{
				$_SESSION["memb_id"] = $row["memb_id"];
				$_SESSION["user_type"] = "M";
				$_SESSION["memb_email"] = $row["memb_email"];
				$name_arr = explode(" ",$row["memb_fname"]);
				$_SESSION["memb_fname"] = $name_arr[0];
				$memb_id = $_SESSION["memb_id"];
				if(get_rst("select * from cart_items where session_id='".session_id()."' AND memb_id IS NULL", $rw, $rst)){
					$sup_id_old = 0;
					$cart_id_old = 0;
					$cart_id_new = 0;
					do{
						$sup_id = $rw["sup_id"];
						$prod_id = $rw["prod_id"];
						$cart_item_id = $rw["cart_item_id"];
						$cart_id = $rw["cart_id"];
						$cart_qty = $rw["cart_qty"];
						$cart_price = $rw["cart_price"];
						$tax_value = $rw["tax_value"];
						$ship_amt = $rw["ship_amt"];
						$item_total = $cart_price * $cart_qty;
						$vat_value = $tax_value * $cart_qty;
						$shipping_charges = 0;
						if(get_rst("select sup_id from sup_mast where sup_id=$sup_id and sup_lmd=1")){
							$shipping_charges = $ship_amt;
						}
						$ord_total = $item_total + $vat_value + $shipping_charges;
						if(get_rst("select cart_item_id, cart_id, cart_qty, item_wish from cart_items where memb_id=$memb_id and sup_id=$sup_id and prod_id=$prod_id", $rw_cart) && $sup_id<>$sup_id_old){
							$cart_item_id_new = $rw_cart["cart_item_id"];
							$cart_id_new = $rw_cart["cart_id"];
							$cart_id_old = $cart_id;
							if($rw_cart["item_wish"] == 1){
								execute_qry("update cart_items set cart_id=$cart_id_new, memb_id=$memb_id where cart_item_id=$cart_item_id");
								execute_qry("delete from cart_items where cart_item_id=$cart_item_id_new");
								execute_qry("update cart_summary set item_total=$item_total,
										ord_total=$ord_total, vat_value=$vat_value,
										shipping_charges=$shipping_charges,
										item_count=1
										where cart_id=$cart_id_new and sup_id=$sup_id");
								execute_qry("update cart_summary set item_count=item_count-1 where cart_id=$cart_id AND user_id IS NULL and sup_id=$sup_id");	
								execute_qry("delete from cart_summary where item_count=0 and cart_id=$cart_id AND user_id IS NULL and sup_id=$sup_id");
							}else{
								execute_qry("update cart_items set cart_qty=cart_qty+$cart_qty where cart_item_id=$cart_item_id_new");
								execute_qry("delete from cart_items where cart_item_id=$cart_item_id");							
								execute_qry("update cart_summary set item_total=item_total+$item_total,
										ord_total=ord_total+$ord_total, vat_value=vat_value+$vat_value,
										shipping_charges=shipping_charges+$shipping_charges
										where cart_id=$cart_id_new and sup_id=$sup_id");
								execute_qry("update cart_summary set item_count=item_count-1 where cart_id=$cart_id AND user_id IS NULL and sup_id=$sup_id");
								execute_qry("delete from cart_summary where item_count=0 and cart_id=$cart_id AND user_id IS NULL and sup_id=$sup_id");
							}	
						}elseif( get_rst("select cart_id from cart_items where memb_id=$memb_id and sup_id=$sup_id", $rw_cart)){
							$cart_id_new = $rw_cart["cart_id"];
							$cart_id_old = $cart_id;
							execute_qry("update cart_items set cart_id=$cart_id_new, memb_id=$memb_id where cart_item_id=$cart_item_id");
							execute_qry("update cart_summary set item_total=item_total+$item_total, ord_total=ord_total+$ord_total, vat_value=vat_value+$vat_value, shipping_charges=shipping_charges+$shipping_charges, item_count=item_count+1 where cart_id=$cart_id_new and sup_id=$sup_id");					
							execute_qry("delete from cart_summary where cart_id=$cart_id_old and session_id='".session_id()."' AND user_id IS NULL");
						}else{
							if($cart_id_old == $cart_id && $cart_id_new <> 0){
								execute_qry("update cart_items set memb_id=$memb_id, cart_id=$cart_id_new where cart_id=$cart_id and session_id='".session_id()."' AND memb_id IS NULL");
								execute_qry("update cart_summary set user_id=$memb_id, cart_id=$cart_id_new where cart_id=$cart_id and session_id='".session_id()."' AND user_id IS NULL");
							}elseif($cart_id_new == 0){
								if(get_rst("select cart_id from cart_items where memb_id=$memb_id and session_id='".session_id()."'", $nrow)){
									execute_qry("update cart_items set memb_id=$memb_id, cart_id=".$nrow["cart_id"]." where sup_id=$sup_id and cart_id=$cart_id and session_id='".session_id()."' AND memb_id IS NULL");
									execute_qry("update cart_summary set user_id=$memb_id, cart_id=".$nrow["cart_id"]." where cart_id=$cart_id and session_id='".session_id()."' AND user_id IS NULL");	
								}else{
									execute_qry("update cart_items set memb_id=$memb_id where cart_id=$cart_id and sup_id=$sup_id and session_id='".session_id()."' AND memb_id IS NULL");
									execute_qry("update cart_summary set user_id=$memb_id, cart_id=$cart_id where cart_id=$cart_id and session_id='".session_id()."' AND user_id IS NULL");
								}
							}
							$sup_id_old = $sup_id;
						}
					}while($rw = mysqli_fetch_assoc($rst));
				}
				//header("location: index.php");
				if($page_name == "" || $page_name == "prod_details"){
					js_redirect("index.php");
				}else{
					js_redirect($url_root.$page_name.".php");
				}
			}					
			
		}else{
			js_alert("Invalid email ID and password combination");
		} 	
	}else{
		js_alert("Please enter your email ID and password to Sign in");
	}	
}
$logout = 0;
if(isset($_GET["l"])){
	//user logout
	$_SESSION["memb_id"] = "";
	session_unset();  
	session_destroy();
    $logout = 1;
}

$items=0;
$ord_total=0;
$qry = "";
if($memb_id <> 0 && $logout <> 1){
	$qry = "select sum(ord_total) as ord_total,sum(item_count) as item_count from cart_summary where user_id=$memb_id group by cart_id" ;
}else{
	$qry = "select sum(ord_total) as ord_total,sum(item_count) as item_count from cart_summary where session_id='".session_id()."' and user_id IS NULL group by cart_id";
}

if(get_rst($qry,$row_cart)){
	$items = $row_cart["item_count"];
	$ord_total=$row_cart["ord_total"];
}

?>

<div id="topsection" data-spy="affix" data-offset-top="1">
	
	<table id="top-table">
		<tr class="space-under">
			<td rowspan="2"><a href="/" title="Home"><img class="logo" alt="Company-Name" src="<?php echo "data:image/gif;base64,".base64_encode(file_get_contents("$url_root/images/logo.gif")) ?>" /></a></td>		
			<td class="center"><a style="text-decoration: underline;" class="color-white" href="#" data-toggle="popover" data-html="true" data-placement="bottom"
			data-content="<a class='color-amber' href='<?=$url_root?>seller/login.php' title='Login to Seller Panel'>Signin</a>&nbsp;|&nbsp;<span> <a class='color-amber' href='<?=$url_root?>seller.php' title='Seller Registration'>Register</a> </span>" title="Company-Name Seller Login/Register"><span class="entypo-tools" style="color:#f0ad4e;"></span> Sellerzone Login</a> &nbsp;&nbsp;&nbsp;&nbsp; <a style="text-decoration: underline;" class="color-white" href="<?=$url_root?>/saas/index.php" target="_blank" title="Company-Name Briefcase for Industrial Buyers"> <span class="entypo-briefcase" style="color:#f0ad4e;"></span> Briefcase Login</a>
			</td>
			
			<td class="reg-sell"> 
			<p class="color-white">Customer Care: &nbsp; <a href="mailto:support@Company-Name.com?subject=Contact%20Customer%20care" title="support@Company-Name.com"> <span class="color-white glyphicon glyphicon-envelope glyf"></span> </a>
			&nbsp;<a href="#"  title="Call Us" data-toggle="popover" data-content="77 9849 5353" data-trigger=focus> <span class="color-white glyphicon glyphicon-earphone glyf"></span> </a> </p></td>
		</tr>
		
		<tr class="space-under">	
			<td><div id="search-box">
				<form name="frm_list" method="post" action="<?=$url_root?>prod_list.php">
				<table width="100%">
					<tr>
						<td><a class="all-cats-btn btn btn-warning ht30" title="View All Categories" style="font-size:12px;" href="#" data-toggle="modal" data-target="#myModalx">
						<span class="all-cats-glyf color-white glyphicon glyphicon-th glyf"></span></a>
						</td>
						<td>
						<div class="input-group" id="adv-search">
						<input name="searchtext" id="searchBar" type="text" class="form-control search ht30" size="40" placeholder="Search Products" maxlength="50" value="<?php echo(func_read_qs("searchtext")); ?>" class="search-area" autocomplete="off"/> 
						
						<!-- Creating an advanced search bar -->
						<div class="input-group-btn">
							<a class="btn btn-default search-btn" id="seachCriteria" tabindex="0" href="#" data-placement="bottom" data-trigger="focus" data-toggle="popover" data-html="true" 
							data-content="<strong>Search by:</strong><br>
							<a class='color-amber' href='javascript:setSearchCriteria(1);' title='Search by Products'>Products</a> <br>
							<a class='color-amber' href='javascript:setSearchCriteria(2);' title='Search by Category'>Category</a> <br>
							<a class='color-amber' href='javascript:setSearchCriteria(3);' title='Search by Brand'>Brand</a>">
							<span class="entypo-down-dir color-amber"></span></a>
							<input type="hidden" id="search_type" name="search_criteria" value="1">
						</div>
						</div>
						<div id="result"></div>
						</td>
						<td>	
						<button class="btn btn-warning ht30 search-right-btn" type="submit"> <span class="color-white glyphicon glyphicon-search glyf"></span></button>
						</td>
					</tr>
				</table>
				</form>
			</div></td>
			
			<td>
			<table width="100%">
			  <tr>
			   <td>
			  
				<div id="basket-view" class="basket">

					<a href="<?=$url_root?>basket.php" style="color:#fff" title="Rs.<?=number_format($ord_total,2,".",",");?> (Prices displayed exc. TAX)">
					  <img class="cart_icon" alt="Shopping Cart" src="<?php echo "data:image/png;base64,".base64_encode(file_get_contents("$url_root/images/cart_icon.png")) ?>">
					  <span class="badge amber-background">&nbsp;
					  <?php if($items == ""){echo "0";}else{echo $items;}?></span>
					</a> </br>
				</div>
				</td>

				<td class="reg-sell">
				
				<?php if(func_var($_SESSION["memb_id"]) == ""){	?>
				<div id="signin-link">
					<p class="color-white"><a class="color-white" href="" data-toggle="modal" data-target="#login-modal" title="User Signin">Signin</a>&nbsp; |&nbsp; <a class="color-white" href="<?=$url_root?>register.php" title="Register Details">Register</a></p>
				</div>
				<?php }else{ ?>
				<div id="memb-box" class="dropdown reg-sell">
					<div id="prof-icon" >
						<a href="#" class="color-white">
						 <?=$_SESSION["memb_fname"]?>
						 &nbsp;<span class="entypo-user color-amber"></span>
						</a>
					</div>
					<ul id="prof_dropdown" class="prof-menu dropdown-menu" style="display:none;">
						<li><a href="<?=$url_root?>memb_edit_details.php" title="Edit Details"><span class=" color-amber glyphicon glyphicon-pencil glyf"></span></a>						</li>
						<li><a href="<?=$url_root?>memb_view_orders.php" title="Track Orders"><span class="color-amber glyphicon glyphicon-map-marker glyf"></a></li>
						<li><a href="<?=$url_root?>wish_list.php" title="My Wishlist"><span class="color-amber  glyphicon glyphicon-heart glyf"><span class="badge"><?php
							$qry = "";
							if($memb_id <> 0){
								$qry = "select count(cart_item_id) as rcount from cart_items where memb_id=$memb_id and item_wish=1";
							}else{
								$qry = "select count(cart_item_id) as rcount from cart_items where session_id='".session_id()."' and item_wish=1 and memb_id IS NULL";
							}
							if(get_rst($qry,$wrow)){
								$wish_items = $wrow["rcount"];
							}
							echo $wish_items;?></span>
						</a></li>
						<li><a href="#" title="Rewards & Credits" data-toggle="modal" data-target="#credit-wallet"><span class="color-amber glyphicon glyphicon-gift glyf"></a></li>
						<li><a href="<?=$url_root?>index.php?l=1" title="Sign Out"><span class="color-amber glyphicon glyphicon-log-out glyf"></a></li>
					</ul>
				</div>
				<?php } ?>
			   </td>					
				</tr>
				</table>
			</td>
		</tr>
	</table>
 </div>
</div>
<?php
if(isset($_SESSION["memb_id"])){
	$credit_amt=0;
	if(get_rst("select credit_amt from credit_details where memb_id=".$_SESSION["memb_id"],$cr)){
		$credit_amt=formatNumber($cr['credit_amt'],2);
	}?>
	<div id = "credit-wallet" class="modal fade" role="dialog">
		<div class="modal-dialog" style="width:600px; top:150px;">
			<!-- Modal content-->
			<div class="modal-content" style="height: auto;">
				<div class="modal-header" style="padding-bottom:0px;">
				<img src="images/close_header.gif" width="12px" height="12px" alt="close" align="right" data-dismiss="modal"/>
					<h2 class="color-amber center"><b style="font-size: 20px;">Rewards & Credits</b></h2></center>
				</div>
				<div class="modal-body" style="padding-top:15px;">
				<div class="center"><b style="font-size: 18px;" class="color-amber">Total Reward Points: <?=$credit_amt?></b></div>
				<?php if(get_rst("select coupon_code, max_discount_value, min_ord_value from offer_coupon where credit_flag=1 and memb_id=".$_SESSION["memb_id"], $row_cdetail)){?>
					<ul class="offer_terms_ul">
						<li> Coupon Code: <?=$row_cdetail["coupon_code"]?></li>
						<li> Applicable on minimum purchase of Rs.<?php echo $row_cdetail["min_ord_value"] * 10;?></li>
						<li> Maximum discount applicable is Rs.<?=$row_cdetail["max_discount_value"]?></li>
						<li> Offer applicable once per user and it cannot be combined with any other offer</li>
					</ul>
				<?php }
				if(get_rst("select memb_credit_limit, memb_min_credit_ord_amt from member_mast where memb_credit_status=1 and memb_id=".$_SESSION["memb_id"], $row_credit)){
					$this_month = date('Y-m-01')." 00:00:00";
					$credit_used = 0;
					$total_outstanding = 0;
					if(get_rst("select sum(ord_total) as total_amt from ord_summary where ord_date >= '".$this_month."' and pay_method='OC' and user_id=".$_SESSION["memb_id"], $row_credit_amt)){
						$credit_used = $row_credit_amt["total_amt"];
					}

					if(get_rst("select sum(ord_total) as total_amt from ord_summary where pay_status <> 'Paid' and pay_method='OC' and user_id=".$_SESSION["memb_id"], $row_credit_outstanding)){
						$total_outstanding = $row_credit_outstanding["total_amt"];
					}
				?>
				<br>
				<hr>
				<div class="center"><b style="font-size: 18px;" class="color-amber">Credit Details:</b></div>
					<ul class="offer_terms_ul">
						<li> Your total credit outstanding is Rs. <?php echo $row_credit_outstanding["total_amt"] + 0;?></li>
						<li> Available Credit Limit: Rs. <?php echo $row_credit["memb_credit_limit"] - $credit_used + 0;?></li>
						<li> Applicable on minimum purchase of Rs. <?php echo $row_credit["memb_min_credit_ord_amt"] + 0;?></li>
					</ul>
				<?php }?>
				</div>
			</div>
		</div>
	</div>
<?php }?>
  <!-- top panel --> 
  <!-- ***********************************************************  top panel1 ************************************-->
	<!--topsection -->
<div id="maincontainer">
<script type='text/javascript'>

function android_app(){
	window.open("https://play.google.com/store/apps/details?id=com.Company-Name.app");
}
function ios_app(){
	window.open("https://itunes.apple.com/in/app/Company-Name/id1187580527");
}

	// Handle ESC key (key code 27)
  document.addEventListener('keyup', function(e) {
    if (e.keyCode == 27) {
        $('#login-modal').modal('hide');
		$('#myModalx').modal('hide');
		$('#myModal1').modal('hide');
		$('#myModal2').modal('hide');
		$('#subscribe-modal').modal('hide');
    }
  });
  
$(function() {
    $('#seachCriteria').tooltip({title:"Select search criteria for better results", placement:"bottom"});
    $('#searchBar').hover(
        function(e){$('#seachCriteria').tooltip('toggle');}
    );
    
}); 

$('#seachCriteria').click(function(e) {
      $('#seachCriteria').tooltip('hide');
});

$('[data-toggle="popover"]').popover({
    container: 'body'
});

function setSearchCriteria(search_type){
	$(this).popover('hide');
	document.getElementById("search_type").value = search_type;
}

//Update search bar based on input values
$(function(){	
	$("#searchBar").keyup(function() 
		{ 
		var searchText = $(this).val();
		if(searchText!='')
		{
			var result = do_live_search(searchText);
			//alert(result);
			if(result != ""){
				var result_array = result.split("|");
				document.getElementById("result").style.width = document.getElementById("searchBar").style.width;
				var result_html = "<ul style=\"list-style: none; margin-left: -20px;\">";
				for (var i = 0; i < result_array.length; i++) {
					result_html = result_html + "<li><a style=\"color: #000; \" href=\"#\" onclick=\"select_text('" + result_array[i] + "');\">" + result_array[i] + "</a></li>";
				}
				result_html = result_html + "</ul>";
				$("#result").html(result_html).show();
				
			}
		}else{$("#result").html("").hide();}    
	});

	$('#searchBar').click(function(){
		jQuery("#result").fadeIn();
	});
});

function select_text(search_result){
	document.getElementById("searchBar").value = search_result;
	document.frm_list.submit();
	$("#result").html("").hide();
	
}
</script>
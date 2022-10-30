<?php
	if(session_id() == '') {
		session_start();
	}
	require_once("lib/inc_library.php");
	require_once("lib/inc_home_banner.php");
	global $sales;
	if(func_read_qs("process")=="show_all_viewed"){
		$prod_id = func_read_qs("prod_id");
		?>
		<response>
		
	<?php
	$sql = "select view_count,p.prod_id,prod_stockno,prod_name,prod_thumb1,prod_ourprice,prod_offerprice,level_parent from prod_mast p inner join "; 
	$sql = $sql." prod_viewed v on p.prod_id=v.prod_id where prod_status=1 and p.prod_id<>$prod_id and session_id='".session_id()."' order by view_date desc LIMIT 3, 1000";
	//echo($sql);
	
	if(get_rst($sql,$row,$rst)){?>
	  <table width="100%" border="1" class="table_prod_list table">
		<tr class="display_recent">
		<?php
		$row_count = 0;
		do{
			if($row_count>0 AND $row_count % 3==0){
				echo("</tr><tr class=\"display_recent\">");
			}
			get_rst("select level_name from levels where level_id='".$row["level_parent"]."'",$row_level);
			$url_recentlevel = preg_replace('/[^a-zA-Z0-9]/',"-",$row_level["level_name"]);
			
			$product_urlname = preg_replace('/[^a-zA-Z0-9]/',"-",$row["prod_name"]);
			$url = "prod_details.php?product=".$product_urlname."&category=".$url_recentlevel;
			?>
			<td>
			
			<table border="0" class="table_prod" style="width: 253px"> 
			<tr>
			<td height="60" align="center" valign="bottom">
				<h2><?=$row["prod_stockno"]?><br><?=$row["prod_name"]?></h2>				
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
		}while($row = mysqli_fetch_assoc($rst));
		echo("</tr></table>");
	}

	?>
	
	</response>
	<?php
	}

	if(func_read_qs("process")=="check_memb_email"){
		$response = "Available";
		$memb_email = func_read_qs("memb_email");
		if(check_email($memb_email)==false){
			if(get_rst("select memb_id from member_mast where memb_email='$memb_email'",$row,$rst)){
				$response = "This email already exists. Please provide another one.";
			}
		}else{
			$response = "Invalid email format";
		}
		echo("<response>$response</response>");
	}

	if(func_read_qs("process")=="check_memb_tel"){
		$response = "Available";
		$memb_tel = func_read_qs("memb_tel");
		if(strlen($memb_tel) < 10){
			$response = "Invalid number! Please enter 10 digit mobile number.";
		}elseif(get_rst("select memb_id from member_mast where memb_tel='$memb_tel'",$row,$rst)){
			$response = "This phone number already exists. Please provide another one.";
		}
		echo("<response>$response</response>");
	}

	if(func_read_qs("process")=="check_sup_tel"){
		$response = "Available";
		$sup_contact_no = func_read_qs("sup_contact_no");
		if(strlen($sup_contact_no) < 10){
			$response = "Invalid number! Please enter 10 digit mobile number.";
		}elseif(get_rst("select sup_id from sup_mast where sup_contact_no='$sup_contact_no'",$row,$rst)){
			$response = "This phone number already exists. Please provide another one.";
		}
		echo("<response>$response</response>");
	}


	if(func_read_qs("process")=="get_sms_msg"){
		$fld_arr = array();
		$fld_arr["memb_fname"] = func_read_qs("memb_fname");
		$fld_arr["memb_email"] = func_read_qs("memb_email");
		
		$response = push_body("sms_register.txt",$fld_arr);
		$response = replace($response,"<br>","");
		echo("<response>$response</response>");
	}
	
	if(func_read_qs("process")=="check_seller_email"){
		$response = "Available";
		$sup_email = func_read_qs("sup_email");
		if(check_email($sup_email)==false){
			if(get_rst("select sup_id from sup_mast where sup_email='$sup_email' and sup_delete_account is NULL",$row,$rst)){
				$response = "This email already exists. Please provide another one.";
			}
		}else{
			$response = "Invalid email format";
		}
		echo("<response>$response</response>");
	}	
	
	if(func_read_qs("process")=="check_pwd"){
		$response = check_pwd(func_read_qs("pwd"));

		echo("<response>$response</response>");
	}
	//Ajax call to send sms during buyer registration
	if(func_read_qs("process")=="send_sms"){
		$f_name=func_read_qs("fname");
		$tele = func_read_qs("tele_no");
		$sms_code = func_read_qs("sms_code");
		$sms_msg = "Hi ".$f_name.", your mobile verification code for Company-Name.com is ".$sms_code;			
		$response = "";
		$output=send_sms($tele,$sms_msg);
		if($output == "202"){
			$response = "There is some problem in sending sms to your number";
		}else{
			$response = "The sms has been sent successfully to your registered mobile number";
		}
		echo("<response>$response</response>");
	}
	
	//Ajax call to get the code for displaying the banner images on home page
	if(func_read_qs("process")=="getBannerImages"){
		require_once("lib/inc_home_banner.php");
		$rs_RotatingImages=mysqli_query($con,"Select * from banner_mast where banner_status=1 order by banner_sort");
		$i=0;
		if($rs_RotatingImages){
			//$response=home_banners($rs_RotatingImages);
			home_banners($rs_RotatingImages);
		}
		//echo("<response>$response</response>");
	}
	
	//Ajax call to check pincode availability for delivery
	if(func_read_qs("process")=="check_delivery_pin"){
		$response = "Available";
		$pincode = func_read_qs("pincode");
		if(get_rst("select city_id from city_mast where pincode='$pincode'",$row,$rst)){
			$response = "Available";
		}else{
			$response = "Not Available";
		}
		echo("<response>$response</response>");
	}
	
	if(func_read_qs("process")=="add_to_wishlist"){
		$response = "Available";
		$sup_id=func_read_qs("sup_id");
		$prod_id=func_read_qs("prod_id");
		$sup_name=func_read_qs("sup_name");
		$img_thumb=func_read_qs("prod_thumb1");
		$price=func_read_qs("final_price");
		$qty=func_read_qs("qty");
		$item_wish=func_read_qs("item_wish");
		$memb_id=func_read_qs("memb_id");
		 
		add_to_basket($prod_id,$sup_name,$img_thumb,$price,$qty,$sup_id,$item_wish,$memb_id);
		
		echo("<response>$response</response>");
	}
	
	if(func_read_qs("process")=="remove_from_wishlist"){
		$response = "Available";
		$cart_item_id=func_read_qs("cart_item_id");
		
		execute_qry("delete from cart_items where cart_item_id=$cart_item_id and item_wish=1");
		echo("<response>$response</response>");
	}
	
	if(func_read_qs("process")=="apply_coupon_code"){
		$response = "";
		$memb_id = func_read_qs("memb_id");
		$coupon_code = func_read_qs("coupon_code");
		$ord_amt = func_read_qs("ord_amt");
		$cart_id = func_read_qs("cart_id");
		
		$result = verify_offer_coupon($memb_id, $cart_id, $coupon_code, $ord_amt);
		get_rst("select coupon_id, max_discount_value from offer_coupon where coupon_code='$coupon_code'", $row);
		$fld_arr = array();
		$max_discount_value = $row["max_discount_value"];
		if($result."" <> ""){
			if(intval($result) == 0){
				$response = $result;
			}else{
				$disc_per = intval($result);
				$coupon_amt = (floatval($ord_amt) * floatval($disc_per)/100);
				if($coupon_amt > $max_discount_value){
					$response = "$max_discount_value";
					$coupon_amt = $max_discount_value;
				}else{
					$response = "$coupon_amt";
				}
				
				$fld_arr["cart_id"] = $cart_id;
				$fld_arr["coupon_amt"] = $coupon_amt;
				$fld_arr["coupon_id"] = $row["coupon_id"];
				$qry = func_insert_qry("ord_coupon",$fld_arr);
				execute_qry($qry);
			}
		}else{
			$response = "Invalid Coupon";
		}
		echo("<response>$response</response>");
	}
	
	if(func_read_qs("process")=="remove_coupon"){
		$response = "";
		$coupon_id = func_read_qs("coupon_id");
		$cart_id = func_read_qs("cart_id");
		execute_qry("delete from ord_coupon where coupon_id=$coupon_id and cart_id=$cart_id");
		echo("<response>$response</response>");
	}
	if(func_read_qs("process")=="child_product"){
		$response = "";
		$_SESSION["product_id"] = func_read_qs("product_id");
		$_SESSION["category_id"] = func_read_qs("category_id");
		get_rst("select concat(p.prod_name,'|',l.level_name) as url from prod_mast p inner join levels l on p.level_parent=l.level_id where prod_id='".$_SESSION["product_id"]."'",$row_url);
		$response = $row_url["url"];
		echo("<response>$response</response>");
	}
	
	if(func_read_qs("process")=="load_mailchimp_js"){?>
		<script type='text/javascript' src='//s3.amazonaws.com/downloads.mailchimp.com/js/mc-validate.js'></script>
		<script type='text/javascript'>(function($) {window.fnames = new Array(); window.ftypes = new Array();fnames[0]='EMAIL';ftypes[0]='email';fnames[1]='FNAME';ftypes[1]='text';fnames[2]='LNAME';ftypes[2]='text';}(jQuery));var $mcj = jQuery.noConflict(true);</script>
	<?php echo("<response>Available</response>"); }
	
	if(func_read_qs("process")=="live_search"){
		$response = "";
		$table_name = func_read_qs("tab_name");
		$column_name = func_read_qs("col_name");
		$search_text = func_read_qs("search_str");
		$return_parameter = func_read_qs("return_parameter");
		$qry = "";
		if($table_name == "prod_mast"){
			$qry = "select $return_parameter from $table_name where $column_name LIKE '%$search_text%' and prod_status=1 LIMIT 10";
		}else{
			$qry = "select $return_parameter from $table_name where $column_name LIKE '%$search_text%' LIMIT 10";
		}
		if(get_rst($qry, $row, $rst)){
			do{
				$response = stripslashes($response.$row["$return_parameter"])."|";
			}while($row = mysqli_fetch_assoc($rst));
			$response = rtrim($response, '|');
		}else{
			$response = "false";
		}
		echo("<response>$response</response>");
	}
	
	if(func_read_qs("process")=="send_inquiry"){
		$response = "";
		$prod_name = func_read_qs("prod_name");
		$buyer_name = func_read_qs("buyer_name");
		$prod_sku = func_read_qs("prod_sku");		
		$buyer_mobile = func_read_qs("buyer_mobile");		
		$buyer_email = func_read_qs("buyer_email");
		$prod_name = str_replace("[space]", " ", $prod_name);
		$buyer_name = str_replace("[space]", " ", $buyer_name);
		
		$msg = "Hi,<br><br>";
		$msg .= "An enquiry of a registered product has been recieved with the following details:<br><br>";
		$msg .= "Product SKU: $prod_sku <br>";
		$msg .= "Product Name: $prod_name <br>";
		$msg .= "Buyer Name: $buyer_name <br>";
		$msg .= "Buyer Email: $buyer_email <br>";
		$msg .= "Buyer Mobile: $buyer_mobile <br><br>";
		$msg .= "-- Company-Name Sales";
		xsend_mail($sales,"Company-Name - Registered product inquiry",$msg,"noreply@Company-Name.com" );
		$msg = "Dear $buyer_name,<br><br>";
		$msg .= "Thank you for your valuable inquiry, our sales team will connect with you on the following product.<br><br>";
		$msg .= "Product SKU: $prod_sku <br>";
		$msg .= "Product Name: $prod_name <br><br>";
		$msg .= "Best Regards, <br>";
		$msg .= "Company-Name Sales Team";
		xsend_mail($buyer_email,"Company-Name - Registered product inquiry",$msg,"sales@Company-Name.com" );	
		
		$response = "true";
		echo("<response>$response</response>");
	}
	
	if(func_read_qs("process")=="search_job"){
		$job_type = func_read_qs("searchjob");
		$job=str_replace('[space]'," ",$job_type);
		get_rst("select job_des, job_opening, posting_date from job_requirement where job_title='$job' ",$row);
		$desc=$row["job_des"];
		$ope=$row["job_opening"];
		$date=$row["posting_date"];
		$newdate = date("d-m-Y", strtotime($date));
		$response=$desc."|".$ope."|".$newdate;
		echo("<response>$response</response>");
		
	}
	if(func_read_qs("process")=="add_po_number"){
		$po_number = func_read_qs("po_no");
		$response = "OK";
		if($po_number <> ""){
			execute_qry("update cart_summary set po_no='$po_number' where cart_id=".$_SESSION["cart_id"]);
		}
		echo("<response>$response</response>");
	}
	
	
	
?>

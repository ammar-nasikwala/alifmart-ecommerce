<?php
session_start();

require("../admin/ajax_admin.php");
require_once("../lib/inc_library.php");
require_once("cat_tree.php");

$img_arr = array();
$thumb_arr = array();
$_SESSION["tree"] = "yes";
$img_arr["prod_large1"]="";
$img_arr["prod_large2"]="";
$img_arr["prod_large3"]="";
$img_arr["prod_large4"]="";

$thumb_arr["prod_thumb1"]="";
$thumb_arr["prod_thumb2"]="";
$thumb_arr["prod_thumb3"]="";
$thumb_arr["prod_thumb4"]="";

$img_path = "../images/products/";
$id = func_read_qs("id");
$page_title="";
$meta_key = "";
$meta_desc = "";
$prod_brand_id = "";
$prod_tax_id = "";
$sup_id = $_SESSION["sup_id"];
if(isset($_POST["submit"])){
	//$img_no = 1;
	foreach($img_arr as $fld => $val){
		$img_name="";
		$msg = img_upload_db($fld,$img_path,$img_name,$img_thumb);
		
		if($msg=="1"){
			$img_arr[$fld] = $img_name;
			$thumb_arr[replace($fld,"large","thumb")] = $img_thumb;
		}else{
			break;
		}
	}
	
	if($msg=="1"){
		$fld_arr = array();
		
		
		$fld_arr["prod_name"] = func_read_qs("prod_name");
		$fld_arr["hsn_code"] = func_read_qs("hsn_code");
		$fld_arr["level_parent"] = intval(func_read_qs("level_parent"));
		$fld_arr["page_title"] = func_read_qs("page_title");
		$fld_arr["meta_key"] = func_read_qs("meta_key");
		$fld_arr["meta_desc"] = func_read_qs("meta_desc");
		$fld_arr["prod_briefdesc"] = func_read_qs("prod_briefdesc");
		$fld_arr["prod_ourprice"] = 1;
		//$fld_arr["prod_offerprice"] = func_read_qs("prod_offerprice");
		$fld_arr["prod_stockno"] = func_read_qs("prod_stockno");
		$fld_arr["prod_brand_id"] = func_read_qs("prod_brand_id");
		$fld_arr["prod_weight"] = func_read_qs("prod_weight");
		$prod_size = func_read_qs("prod_size");
		$prod_unit = func_read_qs("prod_unit");
		$fld_arr["prod_size"] = $prod_size." ".$prod_unit;
		//create a folder in the image folder to store images/products/
		mkdir("../prod_desc/".$fld_arr["prod_stockno"]);
		if(func_read_qs("prod_tax_info")<>""){
			$tax_arr = explode("|",func_read_qs("prod_tax_info"));
			$fld_arr["prod_tax_id"] = $tax_arr[0];
			$fld_arr["prod_tax_name"] = $tax_arr[1];
			$fld_arr["prod_tax_percent"] = $tax_arr[2];
		}
		if($_POST["prod_level"]==1){
			$sub_product = func_read_qs("sub_product");
			$fld_arr["parent_product"] = func_read_qs("sub_product");
		}else{
			$fld_arr["parent_product"] = 0;
		}
		
		foreach($img_arr as $fld => $val){
			$remove_img = func_read_qs($fld."_remove_img");
			if($remove_img<>""){
				$fld_arr[$fld] = "";
				$fld_arr[replace($fld,"large","thumb")] = "";
			}
			if($val<>""){
				$fld_arr[$fld] = $val;
				$fld_arr[replace($fld,"large","thumb")] = $thumb_arr[replace($fld,"large","thumb")];
			}
		}
		
		if($id==""){
			$fld_arr["prod_status"] = 0; // inactive by default for seller
			$qry = func_insert_qry("prod_mast",$fld_arr);
			$mail_body="Inserted";
		}else{
			$qry = func_update_qry("prod_mast",$fld_arr," where prod_id=$id");
			$mail_body="Updated";
		}
		
		if(!mysqli_query($con,$qry)){
		?>
			<script>
				alert("Sorry! There is some problem in creating product. Please try after some time or contact our customer support.");
				window.location.href="manage_prods.php";
			</script>
		<?php
		}else{
			if($id==""){
				$id=mysqli_insert_id($con);
			}
			$sup_id=$_SESSION["sup_id"];
			execute_qry("delete from prod_sup where prod_id=$id and sup_id=$sup_id");
			$prod_price=999999;
			$prod_offer_price=999999;
			$prod_final_price=999999;
			$prod_final_offer_price="";
			$price = func_read_qs("price");
			$offer_price = func_read_qs("offer_price");
			$offer_disc = func_read_qs("offer_disc");
			$final_price = "";
				
			if(intval($price)>0){
				$offer_disc_price = $price - (intval($price) * floatval($offer_disc)/100);
				$final_price=$offer_price;
				$prod_final_offer_price=$offer_disc_price;
				if(intval($offer_price) == 0){
					$final_price = $price;
				}				
				/*if(intval($offer_price)>0 and intval($offer_price)<intval($offer_disc_price)){
					$final_price=$offer_price;
					$prod_final_offer_price=$offer_price;
				}elseif(intval($offer_disc)>0){
					$final_price=$offer_disc_price;
					$prod_final_offer_price=$offer_disc_price;
				}else{
					$final_price = $price;
				}*/
				
				if(intval($prod_final_price)>intval($final_price)){
					$prod_final_price = $final_price;
					$prod_price = $price;
					$prod_offer_price = $final_price;
				}

				$fld_s_arr = array();
				get_rst("select sup_company from sup_mast where sup_id=$sup_id",$row_ps);
				$fld_s_arr["prod_id"] = $id;
				$fld_s_arr["sup_id"] = $_SESSION["sup_id"];
				$fld_s_arr["price"] = $price;
				$fld_s_arr["offer_price"] = $offer_price;
				$fld_s_arr["offer_disc"] = $offer_disc;
				$fld_s_arr["final_price"] = $final_price;
				$fld_s_arr["sup_status"] = 1;
				$fld_s_arr["sup_company"] = $row_ps["sup_company"];
				$fld_s_arr["sup_commission"] = 10;
				$qry = func_insert_qry("prod_sup",$fld_s_arr);
				execute_qry($qry);
			}
			//Update prices of items in wish-list for this supplier.
			$qry = "update cart_items set ";
			$qry = $qry . "cart_price=$final_price";
			$qry = $qry . ",tax_value=$final_price * tax_percent/100";
			$qry = $qry . ",cart_price_tax=$final_price + ($final_price * tax_percent/100)";
			$qry = $qry . " where prod_id=$id and sup_id =". $_SESSION["sup_id"] ." and item_wish=1";
			execute_qry($qry);	
			$prod_effective_price = $prod_price;
			if($prod_offer_price>0 and $prod_price<$prod_offer_price){
				$prod_effective_price = $prod_offer_price;
			}
			
			if($prod_offer_price >= 999999){	$prod_offer_price="";}
			if($prod_offer_price ==""){	$prod_offer_price="\N";}
			if($prod_price==999999){ $prod_price=0;};
			
			
			execute_qry("update prod_mast set prod_ourprice=$prod_price,prod_offerprice=$prod_offer_price,prod_effective_price=$prod_effective_price where prod_id=$id");

			execute_qry("delete from prod_props where prod_id=0$id");
			if(!empty($_POST['prop_ids'])) {
				foreach($_POST['prop_ids'] as $prop_id) {
					
					if(!empty($_POST['opt_'.$prop_id])) {
						foreach($_POST['opt_'.$prop_id] as $opt_value) {
							$opt_value = trim($opt_value);
								if($opt_value."" <> ""){
								$fld_p_arr = array();
								$fld_p_arr["prod_id"] = $id;
								$fld_p_arr["prop_id"] = $prop_id;
								$fld_p_arr["opt_value"] = $opt_value;
								$qry = func_insert_qry("prod_props",$fld_p_arr);
								execute_qry($qry);
							}
						}
					}

					$opt_arr = explode("\n",func_read_qs("opt_new_".$prop_id));
					if(func_read_qs("opt_new_".$prop_id)."" <> ""){
						for($j=0;$j<count($opt_arr);$j++){
							$fld_p_arr = array();
							$fld_p_arr["prod_id"] = $id;
							$fld_p_arr["prop_id"] = $prop_id;
							$fld_p_arr["opt_value"] = trim($opt_arr[$j]);
							$qry = func_insert_qry("prod_props",$fld_p_arr);
							execute_qry($qry);
							if(!get_rst("select prop_id from prop_options where prop_id=".$prop_id." and opt_value='".$opt_arr[$j]."'")){
								$fld_pm_arr = array();
								$fld_pm_arr["prop_id"] = $prop_id;
								$fld_pm_arr["opt_value"] = trim($opt_arr[$j]);
								$qry = func_insert_qry("prop_options",$fld_pm_arr);
								execute_qry($qry);
							}					

						}
					}
				}
			}
			
			get_rst("select sup_company from sup_mast where sup_id=".$sup_id."",$row_m);	//getting seller company name
			$sup_company=$row_m['sup_company'];
			
			get_rst("select user_email from user_mast where user_type='AM'",$row_u);	//getting Account Manager email id
			$user_email=$row_u['user_email'];
			$sku=func_read_qs("prod_stockno");
			
			$body = "Hi ,<br>";
			$body.= "<br>Product is $mail_body by Seller.<br>";
			$body.= "Seller: $sup_company<br>Seller ID: $sup_id<br>Product SKU: $sku";
			$body.= "<br><br>From<br>Team Company-Name";
				
			$from = "noreply@Company-Name.com";		
				xsend_mail($user_email,"Company-Name - Product Update ",$body,$from );
				
			?>
			<script>
				alert("Record saved successfully.");
				window.location.href="manage_prods.php?page=<?=$_SESSION["page_id"]?>";
			</script>
			<?php
			die("");
		}
	}else{ ?>
		<script>
			alert("<?=$msg?>");
			window.location.href="manage_prods.php";
		</script>
		<?php
	}
}

$act = func_read_qs("act");
if($act=="d"){
	//if(!mysql_query("delete from prod_mast where prod_id=".func_read_qs("id"))){
	if(execute_qry("delete from prod_sup where sup_id=$sup_id and prod_id=".func_read_qs("id"))){
		echo("Problem updating database... $qry");
	}else{
		?>
		<script>
			alert("Product deleted successfully.");
			window.location.href="manage_prods.php";
		</script>
		<?php
		die("");
	}	
}

if($id<>""){
	$qry = "select * from prod_mast where prod_id=".$id;
	if(get_rst($qry, $row)){
		//$row = mysqli_fetch_assoc($rst);
		$level_parent = $row["level_parent"];
		$hsn_code = $row["hsn_code"];
		$prod_name = stripcslashes($row["prod_name"]);
		$page_title = stripcslashes($row["page_title"]);
		$meta_key = $row["meta_key"];
		$meta_desc = $row["meta_desc"];
		$prod_briefdesc = stripcslashes($row["prod_briefdesc"]);
		$prod_status = $row["prod_status"];
		$prod_ourprice = $row["prod_ourprice"];
		$prod_offerprice = $row["prod_offerprice"];
		$prod_stockno = $row["prod_stockno"];
		$prod_brand_id = $row["prod_brand_id"];
		$prod_tax_id = $row["prod_tax_id"]."|".$row["prod_tax_name"]."|".$row["prod_tax_percent"];
		$prod_weight = $row["prod_weight"];
		$parent_product = $row["parent_product"];
		$prod_advrt = $row["prod_advrt"];
		$prod = preg_replace("/[^a-z,A-Z]/","", $row["prod_size"]);
		$prod_size = preg_replace("/[^0-9,*,.]/","", $row["prod_size"]);
		$cmSelected = "";
		$mmSelected = "";
		$InchSelected = "";
		$ftSelected = "";
		$mSelected = "";
		$lSelected = "";
		$parentSelected="";
		$childSelected="";
		if($parent_product == 0){
			$prod_levels = 0;
		}else{
			$prod_levels = 1;
		}
		switch($prod_levels){
			case 0: $parentSelected = "selected";
					break;
			case 1: $childSelected = "selected";
					break;
		}
		
		//Making Unit selected based
		switch($prod) {
			case "cm": $cmSelected = "selected"; 
						break;
			case "mm": $mmSelected = "selected"; 
						break;
			case "Inch": $InchSelected = "selected"; 
						break;
			case "ft": $ftSelected = "selected"; 
						break;		
			case "m": $mSelected = "selected"; 
						break;
			case "Ltr": $lSelected = "selected"; 
						break;							
		}
		foreach($img_arr as $fld => $val){
			$img_arr[$fld] = $row[replace($fld,"large","thumb")];
		}
	}
}

function get_parent_name($level_parent){
	$parent_name="";
	if(intval($level_parent)==0){
		$parent_name = "[Root]";
	}else{
		$qry = "select level_name from levels where level_id=".$level_parent;
		if(get_rst($qry, $row)){
			$parent_name = $row["level_name"];
		}
	}
	
	return $parent_name;
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo $company_title ?> | Edit Product</title>
<link type="text/css" rel="stylesheet" href="css/main.css" />
<link type="text/css" rel="stylesheet" href="css/collapse_menu.css" />
<link type="text/css" rel="stylesheet" href="css/tooltip.css" />
<script type="text/javascript" src="js/ddaccordion.js"></script>
<script type="text/javascript" src="js/collapse_menu.js"></script>
<script type="text/javascript" src="js/logohover.js"></script>
<script type="text/javascript" src="js/tooltip.js"></script>
<script type="text/javascript" src="../lib/frmCheck.js"></script>
<script type="text/javascript">

	function set_brand(obj){	//change
		document.getElementById("brand_id").value = obj.value;
		document.getElementById("chk_brand_id").value = obj.value;
	}
	
 	function get_sku(level_name) //change
	{
		var obj_level_name = level_name
		var obj_level_parent= document.getElementById("level_parent").value;
		var obj_brand_id = document.getElementById("brand_id").value;
		var result = call_ajax("../admin/ajax_admin.php","process=get_sku&level_parent=" + obj_level_parent + "&brand_id=" + obj_brand_id + "&level_name=" + obj_level_name);
		document.getElementById("prod_stockno").value = result;
		
	}
    function open_cat() {
		if(document.getElementById("chk_brand_id").value == ""){
			alert("Please select the brand for the product.");
		}else{
			div_cat_tree.style.height=document.body.offsetHeight + "px";
			div_cat_tree.style.display = "";
			v_top = ((parseInt(window.innerHeight) - parseInt(div_cat_inner.offsetHeight)) / 2) 
			v_top = v_top + parseInt(getScrollTop())
			div_cat_inner.style.top = v_top + "px"

			v_left = ((parseInt(div_cat_tree.offsetWidth) - parseInt(div_cat_inner.offsetWidth)) / 2)
			v_left = v_left
			div_cat_inner.style.left = v_left + "px"        
    
			div_inner_tree.style.height = (parseInt(div_cat_inner.offsetHeight)-70) + "px"
		}
	}


    function js_close_tree() {
        div_cat_tree.style.display = "none";
    }

    function js_sel(obj, v_id, v_path) {
        p_obj = obj.parentNode
        span_cat_path.innerHTML = v_path;
        document.getElementById("level_parent").value = v_id
        js_close_tree()
		get_sku(v_path)
		var obj_category = document.getElementById("level_parent").value;
		var select_option = call_ajax("../admin/ajax_admin.php","process=get_sub_product&level_parent=" + obj_category);
		document.getElementById("sub_product").innerHTML = select_option;
    }

	function js_delete(){
		if(confirm("Are you sure you want to delete this product?")){		
			document.frm.act.value="d";
		}else{
			return false;
			
		}
	}
	
	var p_div = "div_basic";
	var p_td = "td_basic";
	
	function show_tab(o_td,o_div){
		document.getElementById(p_div).style.display = "none";
		document.getElementById(p_td).className = "btn_tab";
		document.getElementById(o_div).style.display = "";
		document.getElementById(o_td).className = "btn_tab_sel";

		p_div = o_div;
		p_td = o_td;
		
	}

	function Submit_form(){
		if(document.getElementById("chk_brand_id").value == ""){
			alert("Please select the brand for the product");
			return false;
		}
		if(document.getElementById("level_parent").value == ""){
			alert("Please select the category for the product");
			return false;
		}
		if(document.getElementsByName("prod_weight")[0].value==0)
		{
			alert("Weight cannot be Zero, Please enter valid weight");
			return false;
		}
		
		if(document.getElementById("prod_price").value == "" || document.getElementById("prod_price").value == 0)
		{
			alert("Please enter product price");
			return false;
		}
		if(document.getElementById("prod_level").value==1){
			if(document.getElementById("sub_product").value==0){
				alert("Please select parent product.");
				return false;
			}
		}
		if(document.getElementById("prod_unit").value==""){
			alert("please provide the unit");
			return false;
		}
		if(document.getElementById("id").value == ""){
			if(document.getElementById("prod_large1").value == "" && document.getElementById("prod_large2").value == "" && document.getElementById("prod_large3").value == "" && document.getElementById("prod_large4").value == "" ){
				alert("Please provide atleast one Product Image");
				return false;
			}
		}
		
		if(chkForm(document.frm)==false)
			return false;
		else{
			(function(){
			var gif_show_s = document.getElementById("gif_show_s")
			var content_hide_s = document.getElementById("content_hide_s"),
		show = function(){
			gif_show_s.style.display = "block";
		},
		hide = function(){
			gif_show_s.style.display = "none";
		};

	show();
  })();
			document.frm.submit();
		}
	}

	var prop_count=1;
	function js_add_prop(obj){
		var v_prods = call_ajax("../admin/ajax_admin.php","process=get_prop_options&prop_id=" + obj.value)
		//alert(v_prods)
		var v_obj = document.getElementById("span_prop_area_" + prop_count);
		if(prop_count==3){
			//v_obj.innerHTML = v_obj.innerHTML + "</tr><tr>";
			prop_count=0;
		}
		v_obj.innerHTML = v_obj.innerHTML + v_prods;
		
		v_obj.style.display="";
		remove_option(obj);
		prop_count++;
	}
	function validate_fileupload(obj)
	{
		var fileName = obj.value;
		var allowed_extensions = new Array("jpg","png","gif","tif","tiff","bmp","JPG", "PNG", "JPEG", "jpeg", "GIF", "TIF", "TIFF", "BMP");
		var file_extension = fileName.split('.').pop(); 
		for(var i = 0; i <= allowed_extensions.length; i++)
		{
			if(allowed_extensions[i]==file_extension)
			{
				if(obj.files[0].size > 100000){
					alert("Error! File too large, file size must be less than 100Kb.");
					obj.value = "";
					return false;
				}
				return true;
			}
		}
		alert("Error! File type not allowed, only image files of jpg, png, gif, tif, tiff and bmp are accepted.");
		
		return false;
	}
	
	function calculate_offerprice(){
		var offer_percent=document.getElementById("offer_disc").value;
		if(offer_percent >= 100){
			alert("Offer discount can not be more than or equal to 100%");
			document.getElementById(per_dis).value = 0;
			return false;
		}else{
			var orignal_price=document.getElementById("prod_price").value;
			var commission_per = 10;
			
			var disc_price = orignal_price - ((orignal_price * offer_percent)/100);
			var comm_amt = (disc_price * commission_per)/100;
			document.getElementById("offer_price").value= parseFloat(disc_price + comm_amt + (comm_amt * 18 / 100)).toFixed(2) ; //adds GST of 18 % to the commmission
		}
	}
	
	function validate_key(evt) {
		var theEvent = evt || window.event;
		var key = theEvent.keyCode || theEvent.which;
		key = String.fromCharCode( key );
		var regex = /[0-9]|\./;
	if( !regex.test(key) ) {
		theEvent.returnValue = false;
		if(theEvent.preventDefault) theEvent.preventDefault();
	}
	}
	function display_prod_levels(){
		if(document.getElementById("prod_level").value==1){
			document.getElementById("sub_level").style.display = "table-row";
		}
		else{
			document.getElementById("sub_level").style.display = "none";
		}
	}

</script>
<script type="text/javascript" src="../lib/ajax.js"></script>

<style>

.div_tree{
  position:absolute;
  top:0px;
  left:0px;
  width:100%;
  height:100%;
  background:rgba(200,200,255,0.9);
  
}

.div_inner
{
    position:relative; 
    top:100px;
    height:500px;
    width:800px;
    border: solid 1px #000000;
    background-color:#FFFFFF;
	overflow: scroll;
}

#div_inner_tree{
	background-color:#FFFFFF;
	border:none;
	padding:10px;
	text-align: left !important;
	float: left;
	padding-left: 20px;
}

.table_tabs {
	width:100%;
	padding:0px;
	xborder-collapse: collapse;
}

.table_tabs th{

}

</style>
</head>
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
<div id = "gif_show_s" style="display:none"></div>
<div id="content_hide_s">
<div id="centerpanel">
<div id="contentarea">
<center>
<div class="div_tree" id="div_cat_tree" style="display:none;" >
<div id="div_cat_inner" class="div_inner">
	<table width="100%" border="0">
	<tr>
	<td><h2>Select Category</h2></td>
	<td width="50" valign="middle"><a onclick="javascript: js_close_tree();"><span class="glyphicon glyphicon-remove"></a></td>
	</tr>
	</table>
	<div id="div_inner_tree">

	<?php create_cat_tree();?>

</div>
</div>
</div>
</center>
<?php
if($id){
	$page_head = "Edit Product Details";
}else{
	$page_head = "Create New Product";
}
 ?>

<h2><?php echo $page_head;?></h2>

<form name="frm" id="frm" method="post" enctype="multipart/form-data">
	<input type="hidden" id="id" name="id" value="<?=func_var($id)?>">
	<input type="hidden" id="act" name="act" value="">
	
	<table class="table_tabs">
		<tr>
		<th id="td_basic" class="btn_tab th-normal" onclick="javascrpt:show_tab('td_basic','div_basic');">Basic Details</th>
		<th id="td_prop" class="btn_tab th-normal" onclick="javascrpt:show_tab('td_prop','div_prop');">Properties</th>
		<th id="td_img" class="btn_tab th-normal" onclick="javascrpt:show_tab('td_img','div_img');">Product Images</th>
		<th id="td_desc" class="btn_tab th-normal" onclick="javascrpt:show_tab('td_desc','div_desc');">Product Description</th>
		<th id="td_seo" class="btn_tab th-normal" onclick="javascrpt:show_tab('td_seo','div_seo');">SEO Enhancing Fields</th>
		</tr>
	<tr>
		<td colspan="10">&nbsp;</td>
	</tr>
	</table>

	<div id="div_basic" style="height:400px;overflow-y:scroll;">
	<table border="1" class="table_form">
		<tr>
		<td>Product Name</td>
		<td><input type="text" size="60" maxlength="200" id="100" title="Product Name" name="prod_name" value="<?=func_var($prod_name)?>">*</td>
		</tr>
		
		<tr>
		<td>Brand</td>
		<td>
		<select id="100" title="Brand" name="prod_brand_id" onchange="javascript: set_brand(this);">
			<option value="">Select</option>
			<?=create_cbo("select brand_id,brand_name,brand_id from brand_mast order by brand_name",($prod_brand_id))?>">
		</select>*
		</td>
		<input type="hidden" id="brand_id" value="">
		</tr>
		<input type="hidden" id="chk_brand_id" value="<?=$prod_brand_id?>">
		<tr>
		<td>Category</td>
		<td>
		<input type="hidden" name="level_parent" id="level_parent" value="<?=func_var($level_parent)?>">
		<span id="span_cat_path"><?=get_parent_name($level_parent);?></span>&nbsp;&nbsp;&nbsp;<input type=button class="btn btn-warning" onclick="javascript:open_cat();" value="Select..."></td>
		</tr>
		
		<tr>
			<td>SKU</td>
			<td>
			<?php if($page_head=="Create New Product"){?>
			<input type="text" size="20" maxlength="20" id="prod_stockno" readonly title="SKU" name="prod_stockno" value=""></td>
			<?php }else {?>
			<input type="text" size="20" maxlength="20" id="prod_stockno" readonly title="SKU" name="prod_stockno" value="<?=func_var($prod_stockno)?>"></td>	
			<?php }?>
		</tr>
		<tr>
			<td>HSN Code</td>
			<td><input type="text" size="20" maxlength="10" title="HSN Code" name="hsn_code" value="<?=func_var($hsn_code)?>">*</td>
		</tr>
		<tr>
		<td>Advertised Product</td>
		<td>
			<input id="chk_prod_advrt" type="checkbox" name="prod_advrt" value="1" <?=sel_("1",$prod_advrt."")?> disabled />
		</td>
		</tr>
		<tr class="hideRow">
			<td>Dead Weight <a href="#" data-toggle="popover" data-trigger="hover" data-html="true" data-content="This is the absolute weight of the product, taken from your weighing machine or as per the product catalogue."><span class="glyphicon glyphicon-question-sign help"></span></a></td>
			<td>
			<input type="text" size="10" id="100" maxlength="20" id="prod_stockno" onkeypress='validate_key(event)' title="Product weight in Kgs" name="prod_weight" value="<?=func_var($prod_weight)?>">
			Kgs</td> 	
		</tr>
		
		<tr class="hideRow">
		<td>Seller's Price</td>
		<td>
		<table border="0" width="100%">
		<?php
		$sql = "select price,offer_price,offer_disc,sup_status,out_of_stock from prod_sup where prod_id=0$id and sup_id=$sup_id";
		get_rst($sql,$row);
		?>
			<tr>
			<td>Price</td>
			<td><input type="text" size="10" maxlength="10" id="prod_price" title="Price" onkeypress='validate_key(event)' onchange="javascript: calculate_offerprice();" name="price" value="<?=$row["price"];?>">*</td>

			<td>Offer Discount</td>
			<td><input type="text" size="3" maxlength="10" id="offer_disc" title="Offer Discount" onkeypress='validate_key(event)' name="offer_disc" onchange="javascript: calculate_offerprice();" value="<?=$row["offer_disc"];?>">%</td>
			
			<td>Company-Name Display Price</td>
			<td><input type="text" size="10" maxlength="10" id="offer_price" title="Offer Price" onkeypress='validate_key(event)' name="offer_price" value="<?=$row["offer_price"];?>" readonly></td>
			
			</tr>
		</table>
		</td>
		</tr>
		
		<tr class="hideRow">
		<td>Applicable Tax</td>
		<td>
		<select id="100" title="Applicable Tax" name="prod_tax_info">
			<option value="">Select</option>
			<?=create_cbo("select concat(tax_id,'|',tax_name,'|',tax_percent),concat(tax_name,' - ',tax_percent,'%') from tax_mast where tax_name='VAT'",$prod_tax_id)?>">
		</select>*
		</td>
		</tr>
		
		<tr class="hideRow">
		<td>Status</td>
		<td>
		<select name="prod_status" disabled>
			<?php func_option("Active",1,func_var($prod_status))?>
			<?php func_option("Inactive",0,func_var($prod_status))?>
		</select>
		</td>
		</tr>	
		<tr class="hideRow">
		<td>Product Levels</td>
		<td>
			<select name="prod_level" id="prod_level" onChange="javascript: display_prod_levels();">
				<option value="0" <?=$parentSelected?>>Parent</option>
				<option value="1" <?=$childSelected?>>Child</option>
			</select>
		</td>
		</tr>
		<?php if($parent_product <> 0){
			$display = "table-row";
		}else{
			$display = "none";
		} ?>
		<tr id="sub_level" class="hideRow" style="display: <?=$display?>;">
			<td>Select Parent Product</td>
			<td>
				<?php if($id<>""){?>
				<select name="sub_product" id="sub_product">
					<option value="0">Select</option>
					<?=create_cbo("select prod_id, prod_name from prod_mast where parent_product = 0 and level_parent= 0$level_parent",$parent_product)?>
				</select>
				<?php }else{?>
					<select name="sub_product" id="sub_product"></select>
				<?php } ?>
			</td>
		</tr>
		<tr>
		<tr class="hideRow">
			<td>Size</td>
			<td><input type="text" size="10" id="100" maxlength="20" title="Product Size" name="prod_size" value="<?=func_var($prod_size)?>">
			<span>
				<select title = "Product Unit" id="prod_unit" name="prod_unit">
					<option value="">Unit</option>
					<option value="cm" <?=$cmSelected?>>cm</option>
					<option value="mm" <?=$mmSelected?>>mm</option>
					<option value="Inch" <?=$InchSelected?>>Inch</option>
					<option value="m" <?=$mSelected?>>m</option>
					<option value="ft" <?=$ftSelected?>>ft</option>
					<option value="l" <?=$lSelected?>>l</option>
				</select>
			</span>
			</td>
		</tr>
		<tr><td colspan="2" align="right"><input type="button" class="btn btn-warning" onclick="javascrpt:show_tab('td_prop','div_prop');" value="Next"></td></tr>
	</table>
	</div>

	<div id="div_prop" style="height:400px;overflow-y:scroll;display:none;">
	<table border="1" class="table_form">
		
		<tr>
		<td width="auto">Select to add property:</td>
		<td valign="top">
			<select name="cbo_prop" onchange="javascript: js_add_prop(this);">
			<option value="">--Select--</option>
			<?=create_cbo("select prop_id,prop_name from prop_mast","")?>
			</select>
		</td>
		</tr>
		
		<tr>
		<td colspan="10">
			<table border="0" width="100%">
			<tr>
			<?php
			$row_count = 1;
			if(get_rst("select * from prop_mast where prop_id in (select prop_id from prod_props where prod_id=0$id)",$row_p,$rst_p)){
				do{
					if($row_count==3){
						echo("</tr><tr>");
						$row_count=0;
					}
					echo("<td>");
					$prop_id = $row_p["prop_id"];
				
					//if(get_rst("select * from prop_mast where prop_id=$prop_id",$row)){
						$prop_name=$row_p["prop_name"];
					//}
					?>
					<b><?=$prop_name?></b><br>
					<input type="hidden" name="prop_ids[]" value="<?=$row_p["prop_id"]?>">
					<?php
					$prod_opts="";
					if(get_rst("SELECT opt_value FROM prod_props where prod_id=0$id AND prop_id=0$prop_id",$row,$rst)){
						do{
							$prod_opts = $prod_opts.",".$row["opt_value"];
						}while($row = mysqli_fetch_assoc($rst));
						$prod_opts = $prod_opts.",";
					}
					
					if(get_rst("SELECT distinct opt_value FROM prod_props where prop_id=$prop_id",$row,$rst)){
						do{ ?>
							<input type="checkbox" name="opt_<?=$prop_id?>[]" value="<?=$row["opt_value"]?>" <?=sel_I($prod_opts,",".$row["opt_value"].",")?> ><?=$row["opt_value"]?><br>
							<?php
						}while($row = mysqli_fetch_assoc($rst));
					} ?>
					<br>Add additional multiple options in below box by pressing enter<br>
					<textarea name="opt_new_<?=$prop_id?>" style="width:200px;height:45px;"></textarea>
					<br></td>
					<?php
				}while($row_p = mysqli_fetch_assoc($rst_p));
			} ?>
			</tr>
			</table>
			
			<table border="0" width="100%">
			<tr>
			<td valign="top">
			<span id="span_prop_area_1" style="width:100%">
				
			</span>
			</td>

			<td valign="top">
			<span id="span_prop_area_2" style="width:100%">
				
			</span>
			</td>

			<td valign="top">
			<span id="span_prop_area_3" style="width:100%">
				
			</span>
			</td>
			
			</tr>
			</table>
		</td>
		</tr>
		<tr>
		<td align="left"><input type="button" class="btn btn-warning" onclick="javascrpt:show_tab('td_basic','div_basic');" value="Previous"></td>
		<td align="right"><input type="button" class="btn btn-warning" onclick="javascrpt:show_tab('td_img','div_img');" value="Next"></td>
		</tr>
	</table>
	</div>
	
	<div id="div_img" style="height:400px;overflow-y:scroll;display:none;">
	<table border="1" class="table_form">
		
		<?php foreach($img_arr as $fld => $val){ ?>
		<tr>
		<td><?="Product Image ".  substr($fld,10,10); ?></td>
		<td valign="top"><?=img_control_db($fld,$img_path,$val);?></td>
		</tr>
		<?php } ?>
		<tr>
		<td align="left"><input type="button" class="btn btn-warning" onclick="javascrpt:show_tab('td_prop','div_prop');" value="Previous"></td>
		<td align="right"><input type="button" class="btn btn-warning" onclick="javascrpt:show_tab('td_desc','div_desc');" value="Next"></td>
		</tr>	
	</table>
	</div>

	<div id="div_desc" style="height:400px;overflow-y:scroll;display:none;">
	<table border="1" class="table_form">
		<?php control_textarea("prod_briefdesc",func_var($prod_briefdesc),5000,"","","","class='wysiwyg'") ?>

	<tr>
		<td align="left"><input type="button" class="btn btn-warning" onclick="javascrpt:show_tab('td_img','div_img');" value="Previous"></td>
		<td align="right"><input type="button" class="btn btn-warning" onclick="javascrpt:show_tab('td_seo','div_seo');" value="Next"></td>
	</tr>
	</table>
	</div>
	
	<div id="div_seo" style="height:400px;overflow-y:scroll;display:none;">
	<table border="1" class="table_form">
		<?php SEO_fields($page_title,$meta_key,$meta_desc)?>
	<tr>
		<td align="left"><input type="button" class="btn btn-warning" onclick="javascrpt:show_tab('td_desc','div_desc');" value="Previous"></td>
	</tr>
	</table>
	</div>
	
	<table class="table_form">
		<tr>
		<th colspan="2" id="centered">
		<input type="submit" class="btn btn-warning" value="Save" name="submit" onClick="javascript:return Submit_form();">
		<?php if($id<>""){?>
			&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="submit" class="btn btn-warning" value="Delete" name="btn_delete" onclick="javascript: return js_delete();">
		<?php } ?>
		
		</td>
		</tr>
	</table>

</form>

</div>



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

<script>
show_tab("td_basic","div_basic");
$(document).ready(function () {
	if(document.getElementById("chk_prod_advrt").checked){
		$('.hideRow').fadeOut('fast');
		$('.showRow').fadeIn('fast');
	}else{
		$('.showRow').fadeOut('fast');
	}
    $('#chk_prod_advrt').change(function () {
        if (!this.checked){ 
            $('.hideRow').fadeIn('fast');
			$('.showRow').fadeOut('fast');
        }else{ 
            $('.hideRow').fadeOut('fast');
			$('.showRow').fadeIn('fast');
		}	
    });
});
</script>

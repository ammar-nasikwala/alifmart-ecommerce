<?php

require_once("inc_admin_header.php");
require_once("ajax_admin.php");
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
$error_msg = "";
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
		$fld_arr["prod_status"] = intval(func_read_qs("prod_status"));
		$fld_arr["prod_ourprice"] = 1;
		$fld_arr["prod_stockno"] = func_read_qs("prod_stockno");
		$fld_arr["prod_brand_id"] = func_read_qs("prod_brand_id");
		$fld_arr["prod_weight"] = func_read_qs("prod_weight");
		$prod_size = func_read_qs("prod_size");
		$prod_unit = func_read_qs("prod_unit");
		$fld_arr["prod_size"] = $prod_size." ".$prod_unit;
		//check for LMD product
		if (isset($_POST["prod_lmd"])) {
			$fld_arr["prod_lmd"] = 1;
		}else{
			$fld_arr["prod_lmd"] = 0;
		}
		//check for featured product
		if (isset($_POST["prod_featured"])) {
			$fld_arr["prod_featured"] = 1;
		}else{
			$fld_arr["prod_featured"] = 0;
		}
		
		if($_POST["prod_level"]==1){
			$sub_product = func_read_qs("sub_product");
			$fld_arr["parent_product"] = func_read_qs("sub_product");
		}else{
			$fld_arr["parent_product"] = 0;
		}
		
		//check advertised product
		if (isset($_POST["prod_advrt"])) {
			$fld_arr["prod_advrt"] = 1;
			$fld_arr["parent_product"] = 0;
			$fld_arr["prod_status"] = 1;
		}else{
			$fld_arr["prod_advrt"] = 0;
		}
		
		//Check before creating a new one
		if(is_dir("../prod_desc/".$fld_arr["prod_stockno"])==false){	
			mkdir("../prod_desc/".$fld_arr["prod_stockno"], 0700);
		}
		if(isset($_FILES['uploadfile'])){
			$errors= array();
			$maxsize    = 2097152;
			$file_name = $_FILES['uploadfile']['name'];
			if($file_name <> ""){
				$file_tmp =$_FILES['uploadfile']['tmp_name'];
				$desired_dir="../prod_desc/".$fld_arr["prod_stockno"];
				
				if((filesize($file_tmp) >= $maxsize) || (filesize($file_tmp) == 0)) {
					$errors[] = 'File is too large. File size must be less than 2 megabytes.';
				}
				
				if(empty($errors)==true){
					if(is_dir($desired_dir)==false){
						mkdir("$desired_dir", 0700);		// Create directory if it does not exist
					}
					
					$file_path="";
					$file_path=$desired_dir."/".$file_name;
					move_uploaded_file($file_tmp,$file_path);
					$fld_arr["prod_detaildesc"] = $file_path;
				}else{
					$act="0";
					$msg= "File upload failed, description file could not be uploaded.";
				}
			}
		}
		if(func_read_qs("prod_tax_info")<>""){
			$tax_arr = explode("|",func_read_qs("prod_tax_info"));
			$fld_arr["prod_tax_id"] = $tax_arr[0];
			$fld_arr["prod_tax_name"] = $tax_arr[1];
			$fld_arr["prod_tax_percent"] = $tax_arr[2];
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
			$qry = func_insert_qry("prod_mast",$fld_arr);			
		}else{
			get_rst("select prod_status from prod_mast where prod_id = $id",$row_sid);	//for notification in app
			$prod_status_old = $row_sid["prod_status"];
			
			if($prod_status_old == 0 && $fld_arr["prod_status"] == 1){
				
				get_rst("select sup_id from prod_sup where prod_id = $id",$row_sid);
				sendnotifiction_productstatus($row_sid["sup_id"],$fld_arr["prod_stockno"]);
			}
			
			$qry = func_update_qry("prod_mast",$fld_arr," where prod_id=$id");
		}
		if(!mysqli_query($con, $qry)){
			$error_msg = "There is some problem updating your record, please try again after sometime.";
		}else{
			if($id==""){
				$id=mysqli_insert_id($con);
			}
			if(isset($_POST["prod_advrt"])){	
			//In case of advertised product, save seller reference in the prod_sup table and nothing else
				execute_qry("delete from prod_sup where prod_id=$id");
				get_rst("select sup_id from sup_mast where sup_mou_accept=1",$row,$rst);
				do{
					$chk_sup_advrt = "";
					$company_advrt = "";
					$chk_sup_advrt = func_read_qs("chk_advrt_".$row["sup_id"]);
					$company_advrt = func_read_qs("comp_advrt_".$row["sup_id"]);
					if($chk_sup_advrt=="1"){
						$fld_s_arr = array();
						$fld_s_arr["prod_id"] = $id;
						$fld_s_arr["sup_id"] = $row["sup_id"];
						$fld_s_arr["price"] = 0;
						$fld_s_arr["offer_price"] = 0;
						$fld_s_arr["offer_disc"] = 0;
						$fld_s_arr["final_price"] = 0;
						$fld_s_arr["sup_status"] = $chk_sup_advrt;
						$fld_s_arr["out_of_stock"] = 0;
						$fld_s_arr["sup_company"] = $company_advrt;
						$fld_s_arr["sup_commission"] = 0;
						$qry = func_insert_qry("prod_sup",$fld_s_arr);
						execute_qry($qry);
					}
				}while($row = mysqli_fetch_assoc($rst));
			}else{
				execute_qry("delete from prod_sup where prod_id=$id");
				get_rst("select sup_id from sup_mast where sup_mou_accept=1",$row,$rst);
				$prod_price=999999;
				$prod_offer_price=999999;
				$prod_final_price=999999;
				$prod_final_offer_price="";
				
				$chk_sup = "";
				$chk_oos = "";
				$min_qty = 0;
				do{
					$price = func_read_qs("price_".$row["sup_id"]);
					$offer_price = (func_read_qs("offer_price_".$row["sup_id"]));
					$offer_disc = func_read_qs("offer_disc_".$row["sup_id"]);
					$sup_commission = func_read_qs("sup_commission_".$row["sup_id"]);
					$chk_sup = func_read_qs("chk_".$row["sup_id"]);
					$chk_oos = func_read_qs("chk_oos_".$row["sup_id"]);
					$final_price = "";
					$company = func_read_qs("comp_".$row["sup_id"]);
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
						
						if($chk_sup=="1" and $chk_oos==""){
							if(intval($prod_final_price)>intval($final_price)){			
								$prod_final_price = $final_price;
								$prod_price = $price;
								$prod_offer_price = $final_price;
							}
						}					
						$fld_s_arr = array();
						
						$fld_s_arr["prod_id"] = $id;
						$fld_s_arr["sup_id"] = $row["sup_id"];
						$fld_s_arr["price"] = $price;
						$fld_s_arr["offer_price"] = $offer_price;
						$fld_s_arr["offer_disc"] = $offer_disc;
						$fld_s_arr["final_price"] = $final_price;
						$fld_s_arr["sup_status"] = $chk_sup;
						$fld_s_arr["out_of_stock"] = $chk_oos;
						$fld_s_arr["sup_company"] = $company;
						$fld_s_arr["sup_commission"] = $sup_commission;
						$qry = func_insert_qry("prod_sup",$fld_s_arr);
						execute_qry($qry);
						//Set min product quantity
						if($final_price < 500){
							$min_qty_temp = (int)(500/$final_price);
							if($min_qty_temp > 0 && $min_qty < $min_qty_temp){
								$min_qty = $min_qty_temp;
							}
						}
						//Update prices of items in wish-list for this supplier.
						$qry = "update cart_items set ";
						$qry = $qry . "cart_price=$final_price";
						$qry = $qry . ",tax_value=$final_price * tax_percent/100";
						$qry = $qry . ",cart_price_tax=$final_price + ($final_price * tax_percent/100)";
						$qry = $qry . " where prod_id=$id and sup_id =". $row["sup_id"] ." and item_wish=1";
						execute_qry($qry);						
					}
				}while($row = mysqli_fetch_assoc($rst));

				$prod_effective_price = $prod_price;
				if($prod_offer_price>0 and $prod_price<$prod_offer_price){
					$prod_effective_price = $prod_offer_price;
				}
				
				if($prod_offer_price >= 999999){	$prod_offer_price="";}
				if($prod_offer_price ==""){	$prod_offer_price="\N";}
				if($prod_price==999999){ $prod_price=0;};
				
				execute_qry("update prod_mast set min_qty=$min_qty+1,prod_ourprice=$prod_price,prod_offerprice=$prod_offer_price,prod_effective_price=$prod_effective_price where prod_id=$id");
			}
			execute_qry("delete from prod_props where prod_id=0$id");
			if(!empty($_POST['prop_ids'])) {
				foreach($_POST['prop_ids'] as $prop_id) {					
					if(!empty($_POST['opt_'.$prop_id])) {
						foreach($_POST['opt_'.$prop_id] as $opt_value) {
							$opt_value = trim($opt_value);
							//echo("prop_id:|".$opt_value."|<br>");
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
			?>
			<script>
				alert("Record saved successfully.");
			</script>
			<?php
		}
	}else{
		?>
		<script>
			alert("<?=$msg?>");
			window.location.href="manage_prods.php?page=<?=$_SESSION["id"]?>";
		</script>
		<?php
	}
}

$act = func_read_qs("act");
if($act=="d"){
	if(!mysqli_query($con, "delete from prod_mast where prod_id=".func_read_qs("id"))){
		$error_msg="The product could not be delete, please contact technical support.";
	}else{
		?>
		<script>
			alert("Record Deleted successfully.");
			window.location.href="manage_prods.php";
		</script>
		<?php
		die("");
	}	
}

//global $level_name;

if($id<>""){
	$qry = "select * from prod_mast where prod_id=".$id;
	if(get_rst($qry, $row)){
		$level_parent = $row["level_parent"];
		$prod_name = stripcslashes($row["prod_name"]);
		$page_title = stripcslashes($row["page_title"]);
		$meta_key = $row["meta_key"];
		$meta_desc = $row["meta_desc"];
		$prod_briefdesc = stripcslashes($row["prod_briefdesc"]);
		$prod_status = $row["prod_status"];
		$prod_ourprice = $row["prod_ourprice"];
		$hsn_code = $row["hsn_code"];
		$prod_offerprice = $row["prod_offerprice"];
		$prod_stockno = $row["prod_stockno"];
		$prod_brand_id = $row["prod_brand_id"];
		$prod_tax_id = $row["prod_tax_id"]."|".$row["prod_tax_name"]."|".$row["prod_tax_percent"];
		$prod_detaildesc = $row["prod_detaildesc"];
		$prod_weight = $row["prod_weight"];
		$prod_featured = $row["prod_featured"];
		$parent_product = $row["parent_product"];
		$prod_advrt = $row["prod_advrt"];
		$prod_lmd  = $row["prod_lmd"];
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

<script>

	function set_brand(obj){	
		document.getElementById("brand_id").value = obj.value;
		document.getElementById("chk_brand_id").value = obj.value;
	}
	
 	function get_sku(level_name) 
	{
		var obj_level_name = level_name
		var obj_level_parent = document.getElementById("level_parent").value;
		var obj_prod_stockno = document.getElementById("prod_stockno").value;
		var obj_prod_id = document.getElementById("id").value;
		var obj_brand_id = document.getElementById("brand_id").value;
		var result = call_ajax("ajax_admin.php","process=get_sku&level_parent=" + obj_level_parent + "&brand_id=" + obj_brand_id + "&level_name=" + obj_level_name + "&prod_stockno=" + obj_prod_stockno + "&prod_id=" + obj_prod_id);		document.getElementById("prod_stockno").value = result;
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
        span_cat_path.innerHTML = v_path
        document.getElementById("level_parent").value = v_id
        js_close_tree()
		get_sku(v_path)	
		var obj_category = document.getElementById("level_parent").value;
		var select_option = call_ajax("ajax_admin.php","process=get_sub_product&level_parent=" + obj_category);
		document.getElementById("sub_product").innerHTML = select_option;
    }

	function js_delete(){
		if(confirm("Are you sure you want to delete this record? Deleting this category will delete all its related products and categories")){		
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
	
		if(!document.getElementById("chk_prod_advrt").checked){
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
			if(document.getElementById("prod_level").value==1){
				if(document.getElementById("sub_product").value==0){
					alert("Please select parent product.");
					return false;
				}
			}
			if(document.getElementById("id").value == ""){
				if(document.getElementById("prod_large1").value == "" && document.getElementById("prod_large2").value == "" && document.getElementById("prod_large3").value == "" && document.getElementById("prod_large4").value == "" ){
					alert("Please provide atleast one Product Image");
					return false;
				}
			}
			
			if(chkForm(document.frm)==false)
				return false;
		}else{
			if(document.getElementById("chk_brand_id").value == ""){
				alert("Please select the brand for the product");
				return false;
			}
			if(document.getElementById("level_parent").value == ""){
				alert("Please select the category for the product");
				return false;
			}
			document.frm.submit();
		}
	}

	var prop_count=1;
	function js_add_prop(obj){
		var v_prods = call_ajax("ajax_admin.php","process=get_prop_options&prop_id=" + obj.value)
		var v_obj = document.getElementById("span_prop_area_" + prop_count);
		if(prop_count==3){
			prop_count=0;
		}
		v_obj.innerHTML = v_obj.innerHTML + v_prods;
		
		v_obj.style.display="";
		remove_option(obj);
		prop_count++;
	}

	function js_oos(obj){
		var p_obj = obj.parentNode
		if(obj.checked){
			p_obj.style.backgroundColor = "#FF9999";
		}else{
			p_obj.style.backgroundColor = "";
		}
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
	
	function calculate_offerprice(sup_id){
		if(sup_id != ""){
			var price_value = "price_".concat(sup_id);
			var per_dis = "offer_disc_".concat(sup_id);
			var price_dis = "offer_price_".concat(sup_id);
			var commission = "sup_commission_".concat(sup_id);
			
			var offer_percent=document.getElementById(per_dis).value;
			if(offer_percent >= 100){
				alert("Offer discount can not be more than or equal to 100%");
				document.getElementById(per_dis).value = 0;
				return false;
			}else{
				var orignal_price=document.getElementById(price_value).value;
				var commission_per = document.getElementById(commission).value;
				
				var disc_price = orignal_price - ((orignal_price * offer_percent)/100);
				var comm_amt = (disc_price * commission_per)/100;
				document.getElementById(price_dis).value= parseFloat(disc_price + comm_amt + (comm_amt * 18 / 100)).toFixed(2) ; //adds GST of 18 % to the commmission
			}
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
  color: #000;
  background:rgba(200,200,255,0.9);
  
}

.div_inner
{
    position:relative; 
    top:100px;
    height:500px;
    width:800px;
	color: #000;
    border: solid 1px #000000;
    background-color:#FFFFFF;
	overflow: scroll;
}

.div_inner_tree{
	overflow: scroll;
	background-color:#FFFFFF;
	border:none;
	padding:10px;
}

.table_tabs {
	width:100%;
	padding:0px;
	xborder-collapse: collapse;
}
.showRow{display: none;}

</style>


<div class="div_tree" id="div_cat_tree" style="display:none;"  >
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

<?php
if($id){
	$page_head = "Edit Product Details";
}else{
	$page_head = "Create New Product";
}
?>

<h2><?php echo $page_head;?></h2>
<?php if($error_msg<>""){?>
<div class="alert alert-info"><p>
	<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
	<?php echo $error_msg;?></p>
	</div>
<?php } ?>
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

	<div id="div_basic" style="height:450px;overflow-y:scroll;">
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
			<?=create_cbo("select brand_id,brand_name,brand_id from brand_mast order by (brand_name='Others') ASC, brand_name",($prod_brand_id))?>
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
			<input type="text" size="20" maxlength="20" id="prod_stockno" readonly title="SKU" name="prod_stockno" value="<?=func_var($prod_stockno)?>"></td>
		</tr>
		<tr>
			<td>HSN Code</td>
			<td><input type="text" size="20" maxlength="10" title="HSN Code" name="hsn_code" value="<?=func_var($hsn_code)?>">*</td>
		</tr>
		<tr>
		<td>Advertised Product</td>
		<td>
			<input id="chk_prod_advrt" type="checkbox" name="prod_advrt" value="1" <?=sel_("1",$prod_advrt."")?>>
		</td>
		</tr>
		<tr class="showRow">
		<td>Select Seller</td>
		<td>
		<div style="height:180px;border:solid 1px #cccccc;overflow-y:scroll;">
		<table border="0" width="100%">
		<?php
		$sql = "select s.sup_id,s.sup_company,sup_status from sup_mast s left join prod_sup p on s.sup_id=p.sup_id and p.prod_id=0$id where s.sup_mou_accept=1 order by sup_company";
		get_rst($sql,$row,$rst);
		do{	?>
			<tr id="tr_oos">
				<td>
				<input type="checkbox" name="chk_advrt_<?=$row["sup_id"]?>" value="1" <?=sel_("1",$row["sup_status"]."")?>> <?=$row["sup_company"]?>
				<input type="hidden" name="comp_advrt_<?=$row["sup_id"]?>" value="<?=$row["sup_company"]?>">
				</td>
			</tr>
			<?php
		}while($row = mysqli_fetch_assoc($rst)); ?>
		</table>
		</div>
		</td>
		</tr>
		<tr class="hideRow">
			<td>Dead Weight</td>
			<td>
			<input type="text" size="10" id="100" maxlength="20" title="Product weight in Kgs" name="prod_weight" onkeypress='validate_key(event)' value="<?=func_var($prod_weight)?>">
			Kgs</td>	
		</tr>
		<tr class="hideRow">
		<td>Seller's Price</td>
		<td>
		<div style="height:180px;border:solid 1px #cccccc;overflow-y:scroll;">
		<table border="0" width="100%">
		<?php
		$sql = "select s.sup_id,s.sup_company,p.price,offer_price,offer_disc,sup_status,out_of_stock, sup_commission from sup_mast s left join prod_sup p on s.sup_id=p.sup_id and p.prod_id=0$id where s.sup_mou_accept=1 order by sup_company";
		get_rst($sql,$row,$rst);
		do{
			$offer_price = $row["offer_price"];
			//$sup_commission = $row["sup_commission"];
			if(intval($offer_price==0)){	$offer_price="";}
			$offer_disc = $row["offer_disc"];
			if(floatval($offer_disc==0)){	$offer_disc="";}
			?>
			<tr id="tr_oos">
				<td>
				<input type="checkbox" name="chk_<?=$row["sup_id"]?>" value="1" <?=sel_("1",$row["sup_status"]."")?>> <?=$row["sup_company"]?>
				</td>
				<td align="right">Seller Price</td>
				<td><input type="text" size="10" maxlength="10" id="price_<?=$row["sup_id"]?>" title="Price" onkeypress='validate_key(event)' name="price_<?=$row["sup_id"]?>" onblur="calculate_offerprice('<?=$row["sup_id"]?>');" value="<?=$row["price"]?>">*</td>
				<input type="hidden" name="comp_<?=$row["sup_id"]?>" value="<?=$row["sup_company"]?>">
				<td align="right">Discount %</td>
				<td><input type="text" size="3" maxlength="10" id="offer_disc_<?=$row["sup_id"]?>" onkeypress='validate_key(event)' title="Offer Discount" name="offer_disc_<?=$row["sup_id"]?>" onblur="javascript: calculate_offerprice('<?=$row["sup_id"]?>');" value="<?=formatnumber("0".$offer_disc,2)?>"></td>				
				<td align="right">Commission %</td>
				<td><input type="text" size="3" maxlength="10" id="sup_commission_<?=$row["sup_id"]?>" title="Seller Commission" onblur="calculate_offerprice('<?=$row["sup_id"]?>');" onkeypress='validate_key(event)' name="sup_commission_<?=$row["sup_id"]?>" value="<?=$row["sup_commission"]?>"></td>
				<td align="right">Our Price</td>
				<td><input type="text" size="10" readonly maxlength="10" id="offer_price_<?=$row["sup_id"]?>" onkeypress='validate_key(event)' title="Offer Price" name="offer_price_<?=$row["sup_id"]?>" value="<?=$offer_price?>"></td>
				<td valign="right">
					<input type="checkbox" value="1" name="chk_oos_<?=$row["sup_id"]?>" id="chk_oos_<?=$row["sup_id"]?>" onchange="javascript: js_oos(this);" <?=sel_("1",$row["out_of_stock"]."")?>> Out of Stock
				</td>
			</tr>
			<?php
			js_script("js_oos(document.getElementById('chk_oos_".$row["sup_id"]."'))");
		}while($row = mysqli_fetch_assoc($rst));
		?>
		</table>
		</div>
		</td>
		</tr>
		
		<tr class="hideRow">
		<td>Applicable Tax</td>
		<td>
		<select id="100" title="Applicable Tax" name="prod_tax_info">
			<option value="">Select</option>
			<?=create_cbo("select concat(tax_id,'|',tax_name,'|',tax_percent),concat(tax_name,' - ',tax_percent,'%') from tax_mast order by tax_name",$prod_tax_id)?>">
		</select>*
		</td>
		</tr>
		
		<tr class="hideRow">
		<td>Status</td>
		<td>
		<?php if(func_var($prod_status)=="") $prod_status="1";?>
		<select name="prod_status">
			<?php func_option("Active",1,func_var($prod_status))?>
			<?php func_option("Inactive",0,func_var($prod_status))?>
		</select>
		</td>
		</tr>

		<tr class="hideRow">
		<td>Featured Product</td>
		<td>
			<input type="checkbox" name="prod_featured" value="1" <?=sel_("1",$prod_featured."")?>>
		</td>
		</tr>
		
		<tr class="hideRow">
		<td>Is LMD?</td>
		<td>
			<input type="checkbox" name="prod_lmd" value="1" <?=sel_("1",$prod_lmd."")?>>
		</td>
		</tr>
		
		<tr class="hideRow">
		<td class="hideRow">Product Levels</td>
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
		<tr class="hideRow" id="sub_level" style="display: <?=$display?>;">
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
		<tr class="hideRow">
			<td>Size</td>
			<td><input type="text" size="10" maxlength="20" id="prod_size" title="Product Size" name="prod_size" value="<?=func_var($prod_size)?>">
			<span>
				<select title = "Product Unit" id="prod_unit" name="prod_unit">
					<option value="">Unit</option>
					<option value="cm" <?=$cmSelected?>>cm</option>
					<option value="mm" <?=$mmSelected?>>mm</option>
					<option value="Inch" <?=$InchSelected?>>Inch</option>
					<option value="m" <?=$mSelected?>>m</option>
					<option value="ft" <?=$ftSelected?>>ft</option>
					<option value="Ltr" <?=$lSelected?>>Ltr</option>		
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
		<td width="180">Select to add property:</td>
		<td valign="top">
			<select name="cbo_prop" onchange="javascript: js_add_prop(this);">
			<option value="">--Select--</option>
			<?=create_cbo("select prop_id,prop_name from prop_mast where prop_id not in (select prop_id from prod_props where prod_id=0$id)","");
			    if($id==""){?>
			<?=create_cbo("select prop_id,prop_name from prop_mast where prop_id","");?>
				<?php }?>
			</select>
		</td>
		</tr>
		
		<tr>
		<td colspan="10">
			<table border="0" width="100%">
			<tr>
			<?php
			//if(get_rst("select * from prop_mast pm inner join prop_options po on pm.prop_id = po.prop_id where po.prop_id in (select prod_props where prod_id=$id)",$row,$rst)){
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
					//if(get_rst("SELECT po.opt_value as opt_value,pp.opt_value as prod_opt_value FROM prop_options po LEFT JOIN prod_props pp ON po.prop_id = pp.prop_id AND po.opt_value = pp.opt_value AND prod_id = $id where po.prop_id=$prop_id",$row,$rst)){
					$prod_opts="";
					if(get_rst("SELECT opt_value FROM prod_props where prod_id=0$id AND prop_id=0$prop_id",$row,$rst)){
						do{
							$prod_opts = $prod_opts.",".$row["opt_value"];
						}while($row = mysqli_fetch_assoc($rst));
						$prod_opts = $prod_opts.",";
					}

					if(get_rst("SELECT distinct opt_value FROM prod_props where prop_id=$prop_id",$row,$rst)){
						do{
							?>
							<input type="checkbox" name="opt_<?=$prop_id?>[]" value="<?=$row["opt_value"]?>" <?=sel_I($prod_opts,",".$row["opt_value"].",")?> ><?=$row["opt_value"]?><br>
							<?php
						}while($row = mysqli_fetch_assoc($rst));
					}
					?>
					<br>Add additional multiple options in below box by pressing enter<br>
					<textarea name="opt_new_<?=$prop_id?>" style="width:200px;height:45px;"></textarea>
					<br></td>
					<?php
				}while($row_p = mysqli_fetch_assoc($rst_p));
			}
			?>
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
		
		<?php
		foreach($img_arr as $fld => $val){	
		?>
		<tr>
		<td><?=$fld?></td>
		<td valign="top"><?=img_control_db($fld,$img_path,$val)?></td>
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
	<tr style="height: 20px;"><br></tr>
	<tr>
		<td style="font-size: 14px;"><strong>Upload Product Description File</strong></td>
		
		<?php if($prod_detaildesc <> ""){ ?>
		<td><input name="uploadfile" type="file" size="40" class="transparent" style="width: 250px;"/></td>
		<td><img src="../images/checkmark_green.gif" title="File Uploaded"></td>
		<?php } else{ ?>
		<td><input name="uploadfile" type="file" size="40" style="width: 250px;"/></td>
		<?php } ?>
	</tr>
	<tr style="height: 20px;"><br></tr>	
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
		<?php }?>
		</td>
		</tr>
	</table>

</form>
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

<?php
require_once("inc_admin_footer.php");
?>

<?

$sup_id = func_var($_SESSION["sup_id"]);
if($sup_id<>""){
	$sup_filter = " and sup_id = $sup_id";
}

require_once("cat_tree.php");

$img_arr = array();
$thumb_arr = array();

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
		//$img_no++;
	}
	
	//echo($msg);
	if($msg=="1"){
		$fld_arr = array();
		
		$fld_arr["prod_name"] = func_read_qs("prod_name");
		$fld_arr["level_parent"] = intval(func_read_qs("level_parent"));
		$fld_arr["prod_sup_id"] = func_read_qs("prod_sup_id");
		$fld_arr["page_title"] = func_read_qs("page_title");
		$fld_arr["meta_key"] = func_read_qs("meta_key");
		$fld_arr["meta_desc"] = func_read_qs("meta_desc");
		$fld_arr["prod_briefdesc"] = func_read_qs("prod_briefdesc");
		$fld_arr["prod_status"] = intval(func_read_qs("prod_status"));
		//$fld_arr["prod_outofstock"] = intval(func_read_qs("prod_outofstock"));
		$fld_arr["prod_ourprice"] = "1";	func_read_qs("prod_ourprice");
		//$fld_arr["prod_offerprice"] = func_read_qs("prod_offerprice");
		$fld_arr["prod_stockno"] = func_read_qs("prod_stockno");
		$fld_arr["prod_brand_id"] = func_read_qs("prod_brand_id");

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
			//$fld_arr["level_id"] = get_max("levels","level_id");
			$qry = func_insert_qry("prod_mast",$fld_arr);			
		}else{
			$qry = func_update_qry("prod_mast",$fld_arr," where prod_id=$id");
		}
		
		if(!mysqli_query($qry)){
			echo("Problem updating database... $qry");
		}else{
			if($id==""){
				$id=mysqli_insert_id();
			}
			
			execute_qry("delete from prod_sup where prod_id=$id $sup_filter");
			get_rst("select sup_id from sup_mast where 1 $sup_filter",$row,$rst);
			$prod_price=999999;
			$prod_offer_price=999999;
			$prod_final_price=999999;
			$prod_final_offer_price="";
			
			$chk_sup = "";
			$chk_oos = "";
			do{
				$price = func_read_qs("price_".$row["sup_id"]);
				$offer_price = (func_read_qs("offer_price_".$row["sup_id"]));
				$offer_disc = func_read_qs("offer_disc_".$row["sup_id"]);
				$chk_sup = func_read_qs("chk_".$row["sup_id"]);
				$chk_oos = func_read_qs("chk_oos_".$row["sup_id"]);
				$final_price = "";
				
				//echo("[$price] <br>");
				if(intval($price)>0){
					//echo("Inside <br>");			
					$offer_disc_price = $price - (intval($price) * intval($offer_disc)/100);
					if(intval($offer_price)>0 and intval($offer_price)<intval($offer_disc_price)){
						$final_price=$offer_price;
						$prod_final_offer_price=$offer_price;
					}elseif(intval($offer_disc)>0){
						$final_price=$offer_disc_price;
						$prod_final_offer_price=$offer_disc_price;
					}else{
						$final_price = $price;
					}
					//echo("[$final_price]");
					
					if($chk_sup=="1" and $chk_oos==""){
						if(intval($prod_final_price)>intval($final_price)){
							
							$prod_final_price = $final_price;
							$prod_price = $price;
							$prod_offer_price = $prod_final_offer_price;
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
					
					$qry = func_insert_qry("prod_sup",$fld_s_arr);
					//echo($qry);
					execute_qry($qry);
				}
			}while($row = mysqli_fetch_assoc($rst));

			$prod_effective_price = $prod_price;
			if($prod_offer_price>0 and $prod_price<$prod_offer_price){
				//$prod_effective_price = $prod_price;
			//}else{
				$prod_effective_price = $prod_offer_price;
			}
			
			if($prod_offer_price >= 999999){	$prod_offer_price="";}
			if($prod_offer_price ==""){	$prod_offer_price="\N";}
			if($prod_price==999999){ $prod_price=0;};
			
			
			if(get_rst("SELECT * FROM prod_sup WHERE prod_id = $id ORDER BY final_price LIMIT 0 , 1",$row)){
				$prod_price = $row["price"];
				$prod_offer_price = $row["offer_price"];
				if($prod_offer_price ==""){	$prod_offer_price="\N";}
				$prod_effective_price = $row["final_price"];
				execute_qry("update prod_mast set prod_ourprice=$prod_price,prod_offerprice=$prod_offer_price,prod_effective_price=$prod_effective_price where prod_id=$id");
			}			
			
			//execute_qry("update prod_mast set prod_ourprice=$prod_price,prod_offerprice=$prod_offer_price,prod_effective_price=$prod_effective_price where prod_id=$id");
			
			//die("here");
			//echo("<pre>");
			execute_qry("delete from prod_props where prod_id=$id");
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
//echo($qry."<br>");
								execute_qry($qry);
							}
						}
					}

					//echo("opt_new_".$prop_id."=".func_read_qs("opt_new_".$prop_id)."|<br>");
					$opt_arr = explode("\n",func_read_qs("opt_new_".$prop_id));
					if(func_read_qs("opt_new_".$prop_id)."" <> ""){
						for($j=0;$j<count($opt_arr);$j++){
							$fld_p_arr = array();
							
							//echo($j."=".$opt_arr[$j]."|<br>");
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
								//echo($qry."<br>");
								execute_qry($qry);
								
							}					

						}
					}
				}
			}
			?>
			<script>
				alert("Record saved successfully.");
				window.location.href="manage_prods.php";
			</script>
			<?
			die("");
		}
	}else{
		?>
		<script>
			alert("<?=$msg?>");
			window.location.href="manage_prods.php";
		</script>
		<?
	}
}

$act = func_read_qs("act");
if($act=="d"){
	if(!mysqli_query("delete from prod_mast where prod_id=".func_read_qs("id"))){
		echo("Problem updating database... $qry");
	}else{
		?>
		<script>
			alert("Record Deleted successfully.");
			window.location.href="manage_prods.php";
		</script>
		<?
		die("");
	}	
}

//global $level_name;

if($id<>""){
	$rst = mysqli_query("select * from prod_mast where prod_id=".$id);
	if($rst){
		$row = mysqli_fetch_assoc($rst);
		$level_parent = $row["level_parent"];
		$prod_name = $row["prod_name"];
		$prod_sup_id = $row["prod_sup_id"];
		$page_title = $row["page_title"];
		$meta_key = $row["meta_key"];
		$meta_desc = $row["meta_desc"];
		$prod_briefdesc = $row["prod_briefdesc"];
		$prod_status = $row["prod_status"];
		//$prod_outofstock = $row["prod_outofstock"];
		$prod_ourprice = $row["prod_ourprice"];
		$prod_offerprice = $row["prod_offerprice"];
		$prod_stockno = $row["prod_stockno"];
		$prod_brand_id = $row["prod_brand_id"];
		$prod_tax_id = $row["prod_tax_id"]."|".$row["prod_tax_name"]."|".$row["prod_tax_percent"];
		
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
		$rst = mysqli_query("select level_name from levels where level_id=".$level_parent);
		if($rst){
			$row = mysqli_fetch_assoc($rst);
			$parent_name = $row["level_name"];
		}
	}
	
	return $parent_name;
}

?>

<script>
	
    function open_cat() {
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


    function js_close_tree() {
        div_cat_tree.style.display = "none";
    }

    function js_sel(obj, v_id, v_path) {
        p_obj = obj.parentNode
        span_cat_path.innerHTML = v_path;
        document.getElementById("level_parent").value = v_id
        js_close_tree()
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
		if(chkForm(document.frm)==false)
			return false;
		else
			document.frm.submit();
	}

	var prop_count=1;
	function js_add_prop(obj){
		var v_prods = call_ajax("ajax_admin.php","process=get_prop_options&prop_id=" + obj.value)
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

	function js_oos(obj){
		//alert(obj.id);
		var p_obj = obj.parentNode
		if(obj.checked){
			p_obj.style.backgroundColor = "#FF9999";
		}else{
			p_obj.style.backgroundColor = "";
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
	xoverflow: scroll;
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

.table_tabs th{

}

</style>


<div class="div_tree" id="div_cat_tree" style="display:none;" onclick="javascript: js_close_tree();" >
<div id="div_cat_inner" class="div_inner">
	<table width="100%" border="0">
	<tr>
	<td><h2>Select Category</h2></td>
	<td width="50" valign="middle"><a onclick="javascript: js_close_tree();">Close [X]</a></td>
	</tr>
	</table>
	<div class="div_inner_tree" id="div_inner_tree">
	<!--
		<table width="100%" border="0">
		<tr>
		<td><h2>Select Parent Category</h2></td>
		<td width="50" valign="middle"><a onclick="javascript: js_close_tree();">Close [X]</a></td>
		</tr>
		
		<tr>
		<td colspan="2">
		--><!--	<span class='span_cat' onclick="javascript: js_sel(this,'0','[Root Level]');"><b>[Root Level]</b></span>
			--><!--<br><br>
		</td>
		</tr>
		</table>
	-->
	<?create_cat_tree();?>

</div>
</div>
</div>

<?
if($id){
	$page_head = "Edit Product Details";
}else{
	$page_head = "Create New Product";
}
?>

<h2><?=$page_head?></h2>

<form name="frm" id="frm" method="post" enctype="multipart/form-data">
	<input type="hidden" id="id" name="id" value="<?=func_var($id)?>">
	<input type="hidden" id="act" name="act" value="">
	
	<table class="table_tabs">
		<tr>
		<th id="td_basic" class="btn_tab" onclick="javascrpt:show_tab('td_basic','div_basic');">Basic Details</th>
		<th id="td_prop" class="btn_tab" onclick="javascrpt:show_tab('td_prop','div_prop');">Properties</th>
		<th id="td_img" class="btn_tab" onclick="javascrpt:show_tab('td_img','div_img');">Product Images</th>
		<th id="td_desc" class="btn_tab" onclick="javascrpt:show_tab('td_desc','div_desc');">Product Description</th>
		<th id="td_seo" class="btn_tab" onclick="javascrpt:show_tab('td_seo','div_seo');">SEO Enhancing Fields</th>
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
		<td>SKU</td>
		<td><input type="text" size="20" maxlength="20" id="100" title="SKU" name="prod_stockno" value="<?=func_var($prod_stockno)?>"></td>
		</tr>

		<tr>
		<td>Brand</td>
		<td>
		<select id="100" title="Brand" name="prod_brand_id">
			<option value="">Select</option>
			<?=create_cbo("select brand_id,brand_name,brand_id from brand_mast order by brand_name",($prod_brand_id))?>">
		</select>*
		</td>
		</tr>

		<tr>
		<td>Category</td>
		<td>
		<input type="hidden" name="level_parent" id="level_parent" value="<?=func_var($level_parent)?>">
		<span id="span_cat_path"><?=get_parent_name($level_parent);?></span>&nbsp;&nbsp;&nbsp;<input type=button onclick="javascript:open_cat();" value="Select..."></td>
		</tr>
		
		<tr>
		<td>Seller's Price</td>
		<td>
		<div style="height:180px;border:solid 1px #cccccc;overflow-y:scroll;">
		<table border="0" width="100%">
		<?
		$sup_filter_s = "";
		if($sup_filter<>""){
			$sup_filter_s = replace($sup_filter,"sup_id","s.sup_id");
		}		
		$sql = "select s.sup_id,sup_company,p.price,offer_price,offer_disc,sup_status,out_of_stock from sup_mast s left join prod_sup p on s.sup_id=p.sup_id and p.prod_id=0$id where 1 $sup_filter_s order by sup_company";
		//echo($sql);
		get_rst($sql,$row,$rst);
		do{
			$offer_price = $row["offer_price"];
			if(intval($offer_price==0)){	$offer_price="";}
			$offer_disc = $row["offer_disc"];
			if(intval($offer_disc==0)){	$offer_disc="";}
			?>
			<tr id="tr_oos">
			<td>
			<input type="checkbox" name="chk_<?=$row["sup_id"]?>" value="1" <?=sel_("1",$row["sup_status"]."")?>> <?=$row["sup_company"]?>
			</td>
			<td align="right">Price</td>
			<td><input type="text" size="10" maxlength="10" id="010" title="Price" name="price_<?=$row["sup_id"]?>" value="<?=$row["price"]?>">*</td>

			<td align="right">Offer Price</td>
			<td><input type="text" size="10" maxlength="10" id="010" title="Offer Price" name="offer_price_<?=$row["sup_id"]?>" value="<?=$offer_price?>"></td>
			<td align="right">Offer Discount</td>
			<td><input type="text" size="3" maxlength="10" id="010" title="Offer Discount" name="offer_disc_<?=$row["sup_id"]?>" value="<?=formatnumber("0".$offer_disc,0)?>">%</td>

			<td valign="right">
				<input type="checkbox" value="1" name="chk_oos_<?=$row["sup_id"]?>" id="chk_oos_<?=$row["sup_id"]?>" onchange="javascript: js_oos(this);" <?=sel_("1",$row["out_of_stock"]."")?>> Out of Stock
			</td>
			</tr>
			
			</tr>
			<?
			js_script("js_oos(document.getElementById('chk_oos_".$row["sup_id"]."'))");
		}while($row = mysqli_fetch_assoc($rst));
		?>
		</table>
		</div>
		</td>
		</tr>
		
		<tr>
		<td>Applicable Tax</td>
		<td>
		<select id="000" title="Applicable Tax" name="prod_tax_info">
			<option value="">Select</option>
			<?=create_cbo("select concat(tax_id,'|',tax_name,'|',tax_percent),concat(tax_name,' - ',tax_percent,'%') from tax_mast order by tax_name",$prod_tax_id)?>">
		</select>
		</td>
		</tr>
		
		<tr>
		<td>Status</td>
		<td>
		<?if(func_var($prod_status)=="") $prod_status="1";?>
		<select name="prod_status">
			<?func_option("Active",1,func_var($prod_status))?>
			<?func_option("Inactive",0,func_var($prod_status))?>
		</select>
		</td>
		</tr>	
		
	</table>
	</div>

	<div id="div_prop" style="height:400px;overflow-y:scroll;display:none;">
	<table border="1" class="table_form">
		
		<tr>
		<td width="180">Select to add property:</td>
		<td valign="top">
			<select name="cbo_prop" onchange="javascript: js_add_prop(this);">
			<option value="">--Select--</option>
			<?=create_cbo("select prop_id,prop_name from prop_mast where prop_id not in (select prop_id from prod_props where prod_id=$id)","")?>
			</select>
		</td>
		</tr>
		
		<tr>
		<td colspan="10">
			<table border="0" width="100%">
			<tr>
			<?
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
					<?
					//if(get_rst("SELECT po.opt_value as opt_value,pp.opt_value as prod_opt_value FROM prop_options po LEFT JOIN prod_props pp ON po.prop_id = pp.prop_id AND po.opt_value = pp.opt_value AND prod_id = $id where po.prop_id=$prop_id",$row,$rst)){
					$prod_opts="";
					if(get_rst("SELECT opt_value FROM prod_props where prod_id=$id AND prop_id=$prop_id",$row,$rst)){
						do{
							$prod_opts = $prod_opts.",".$row["opt_value"];
						}while($row = mysqli_fetch_assoc($rst));
						$prod_opts = $prod_opts.",";
					}
					
					//if(get_rst("SELECT trim(po.opt_value) as opt_value FROM prop_options po where po.prop_id=$prop_id",$row,$rst)){
					if(get_rst("SELECT distinct opt_value FROM prod_props where prop_id=$prop_id",$row,$rst)){
						do{
							//if (strpos($row["opt_value"],$prod_opts) !== false) {
								
							//}
							//echo("[".$row["opt_value"]."|$prod_opts]");
							?>
							<input type="checkbox" name="opt_<?=$prop_id?>[]" value="<?=$row["opt_value"]?>" <?=sel_I($prod_opts,",".$row["opt_value"].",")?> ><?=$row["opt_value"]?><br>
							<?
						}while($row = mysqli_fetch_assoc($rst));
					}
					?>
					<br>Add additional multiple options in below box by pressing enter<br>
					<textarea name="opt_new_<?=$prop_id?>" style="width:200px;height:45px;"></textarea>
					<br></td>
					<?
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
		
	</table>
	</div>
	
	<div id="div_img" style="height:400px;overflow-y:scroll;display:none;">
	<table border="1" class="table_form">
		
		<?
		foreach($img_arr as $fld => $val){	
		?>
		<tr>
		<td><?=$fld?></td>
		<td valign="top"><?=img_control_db($fld,$img_path,$val)?></td>
		</tr>
		<?
		}
		?>
			
	</table>
	</div>

	<div id="div_desc" style="height:400px;overflow-y:scroll;display:none;">
	<table border="1" class="table_form">
		<? control_textarea("prod_briefdesc",func_var($prod_briefdesc),5000,"","","","class='wysiwyg'") ?>


	</table>
	</div>
	
	<div id="div_seo" style="height:400px;overflow-y:scroll;display:none;">
	<table border="1" class="table_form">
		<?SEO_fields($page_title,$meta_key,$meta_desc)?>

	</table>
	</div>
	
	<table class="table_form">
		<tr>
		<th colspan="2" id="centered">
		<input type="submit" class="btn btn-default" value="Save" name="submit" onClick="javascript:return Submit_form();">
		<?if($id<>""){?>
			&nbsp;&nbsp;&nbsp;&nbsp;
			
			<input type="submit" class="btn btn-default" value="Delete" name="btn_delete" onclick="javascript: return js_delete();">
	<?}?>
		
		</td>
		</tr>
	</table>

</form>
<script>
	show_tab("td_basic","div_basic")
</script>

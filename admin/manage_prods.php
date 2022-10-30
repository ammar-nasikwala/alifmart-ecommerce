<?php
session_start();
require_once("inc_admin_header.php");

require_once("cat_tree.php");

if(($_SESSION["prod_sort"]."" == "" || $_SESSION["prod_sort"] <> func_read_qs("prod_sort")) && func_read_qs("prod_sort") <> ""){
 $_SESSION["prod_sort"] = func_read_qs("prod_sort"); 
}

if(($_SESSION["txt_key"]."" == "" || $_SESSION["txt_key"] <> func_read_qs("txt_key")) && func_read_qs("txt_key") <> ""){
 $_SESSION["txt_key"] = func_read_qs("txt_key"); 
}
 
if(func_read_qs("page") == "" && func_read_qs("txt_key") == ""){$_SESSION["txt_key"] = "";}
if(func_read_qs("page") <> ""){
	$txt_key = trim($_SESSION["txt_key"]);
	$prod_sort = trim($_SESSION["prod_sort"]);
}else{
	$txt_key = trim(func_read_qs("txt_key"));
	$prod_sort = trim(func_read_qs("prod_sort"));
}
$page = func_read_qs("page");
$_SESSION["page_id"] = func_read_qs("page");

$sql="select p.prod_id";
$sql = $sql.",prod_stockno as 'Stock No.'"; 
$sql = $sql.",prod_name as 'Product Name'"; 
$sql = $sql.",(select brand_name from brand_mast b where b.brand_id = p.prod_brand_id) as 'Brand'"; 
$sql = $sql.",(select level_name from levels l where l.level_id = p.level_parent) as 'Category'"; 
$sql = $sql.", ps.sup_company as 'Seller' from prod_sup ps right join";
$sql = $sql." prod_mast p on p.prod_id=ps.prod_id ";

$sql_where = "";
if($txt_key<>""){
	$sql_where = $sql_where." where prod_name like '%$txt_key%'";
	$sql_where = $sql_where." OR prod_stockno like '%$txt_key%'";
	$sql_where = $sql_where." OR prod_brand_id in (select brand_id from brand_mast where brand_name like '%$txt_key%')";
	$sql_where = $sql_where." OR level_parent in (select level_id from levels where level_name like '%$txt_key%')";
	$sql_where = $sql_where." OR p.prod_id in (select prod_id from prod_sup where sup_company like '%$txt_key%')";
}

if($prod_sort.""<>""){
	$sql = $sql.$sql_where." order by $prod_sort";
}else{
	$sql = $sql.$sql_where." order by prod_stockno";

}

$export_display="none";
$export_data = "";

if(func_read_qs("act")=="e_prod"){
	$export_data = "Category-1	Category-2	Category-3	Brand	Seller-estd	SKU	Name	Price	Offer-Price	Description	Image-1	Image-2	Image-3	Image-4	page-title	meta-key	meta-desc	Status";
	$export_data = $export_data."	Property 1	Property 2	Property 3	Property 4	Property 5 \n";
	
	$export_sql = "select * from prod_mast p ".$sql_where." order by prod_stockno";
	get_rst($export_sql,$row,$rst);
	do{	
		$level_parent = $row["level_parent"];
		$levels = "";
		$level_count = 0;
		while(intval($level_parent)>0){
			if(get_rst("select level_id,level_parent,level_name from levels where level_id=0".$level_parent,$row_level)){
				//echo("select level_id,level_parent from levels where level_id=0".$level_parent);
				$level_parent = $row_level["level_parent"];
				if($level_count==0){
					$levels = $row_level["level_name"];
				}else{
					$levels = $row_level["level_name"]."\t".$levels;
				}
				$level_count++;
				
			}
		}
		
		for($l=1;$l<=3-$level_count;$l++){
			$levels = $levels."\t";
		}

		$brand_name = "";
		if(get_rst("select brand_name from brand_mast where brand_id=0".$row["prod_brand_id"]."",$row_brand)){
			$brand_name = $row_brand["brand_name"];
		}

		$sup_name = "";
		//if(get_rst("select sup_company from sup_mast where sup_id=0".$row["prod_sup_id"]."",$row_sup)){
		//	$sup_name = $row_sup["sup_company"];
		//}
		
		$export_data = $export_data.$levels;
		$export_data = $export_data."\t".$brand_name;
		$export_data = $export_data."\t".$sup_name;
		$export_data = $export_data."\t".$row["prod_stockno"];
		$export_data = $export_data."\t".$row["prod_name"];		
		$export_data = $export_data."\t".$row["prod_ourprice"];
		$export_data = $export_data."\t".$row["prod_offerprice"];
		$export_data = $export_data."\t".remove_tab($row["prod_briefdesc"]);
		$export_data = $export_data."\t";	//.$row["prod_large1"];
		$export_data = $export_data."\t";	//.$row["prod_large2"];
		$export_data = $export_data."\t";	//.$row["prod_large3"];
		$export_data = $export_data."\t";	//.$row["prod_large4"];
		$export_data = $export_data."\t".$row["page_title"];
		$export_data = $export_data."\t".$row["meta_key"];
		$export_data = $export_data."\t".remove_tab($row["meta_desc"]);
		$export_data = $export_data."\t".remove_tab($row["prod_status"]);
		
		$prod_id = $row["prod_id"];
		$prop_count = 0;
		if(get_rst("select prop_name,prop_id from prop_mast where prop_id in (select prop_id from prod_props where prod_id =$prod_id)",$row_p,$rst_p)){
			do{
				$prop_text = "";
				get_rst("select opt_value from prod_props where prod_id =$prod_id and prop_id=".$row_p["prop_id"],$row_op,$rst_op);
				do{
					if($prop_text == ""){
						$prop_text = $row_op["opt_value"];
					}else{
						$prop_text = $prop_text.", ".$row_op["opt_value"];
					}
				}while($row_op = mysqli_fetch_assoc($rst_op));
				$prop_text = $row_p["prop_name"]."=".$prop_text;
				
				$export_data = $export_data."\t".$prop_text;
				
				$prop_count++;
			}while($row_p = mysqli_fetch_assoc($rst_p));
		}
		
		for($i_p=$prop_count;$i_p<5;$i_p++){
			$export_data = $export_data."\t";
		}
		
		$export_data = $export_data."\n";
		
	}while($row = mysqli_fetch_assoc($rst));
	
	$export_display="";
}

if(func_read_qs("act")=="e_price"){
	$export_data = "A=Seller Company	B=SKU	C=Price	D=Offer Price	E=Discount %	F=Active	G=Out of Stock\n";
		
	$export_sql = "select prod_stockno,sp.* from prod_mast p inner join prod_sup sp on p.prod_id=sp.prod_id $sql_where order by final_price";
	get_rst($export_sql,$row,$rst);
	do{	
		
		$sup_name = "";
		if(get_rst("select sup_company from sup_mast where sup_id=".$row["sup_id"]."",$row_sup)){
			$sup_name = $row_sup["sup_company"];
		}
		
		$export_data = $export_data.$sup_name;
		$export_data = $export_data."\t".$row["prod_stockno"];
		$export_data = $export_data."\t".$row["price"];
		$export_data = $export_data."\t".$row["offer_price"];
		$export_data = $export_data."\t".$row["offer_disc"];
		$export_data = $export_data."\t".$row["sup_status"];
		$export_data = $export_data."\t".$row["out_of_stock"];		
		$export_data = $export_data."\n";
		
	}while($row = mysqli_fetch_assoc($rst));
	
	
	$export_display="";
}
?>
<div id="div_export" style="display:<?=$export_display?>;position:absolute;left:0px;top:0px;height:100%;width:100%;background-color:#eeeeee;">
	
	<br>&nbsp;
	<center>
	<textarea style="width:1200px;height:500px" wrap="off"><?=func_var($export_data)?></textarea>
	<br>
	Tab delimited data. Please copy/paste directly in an excel sheet.
	<br>
	<input type="button" value=" Close " onclick="javascript: close_export(this);">
	</center>
</div>

<table border="0" width="100%">
	<tr>
		<td colspan="10"><h2>Manage Products</h2></td>
	</tr>
	
	<form name="frm_list" method="post" action="manage_prods.php">
	<input type="hidden" name="act" value="1">
	<tr>
		<td><a href="edit_prods.php">Create New Product [+]</a></td>
		
		<td align="center">
		<input type="button" class="btn btn-warning" value="Export Product Data" onclick="javascript: js_export_prods();"></td>
		</td>
		
		<td align="center">
		<input type="button" class="btn btn-warning" value="Export Prices" onclick="javascript: js_export_prices();">
		</td>
		<td align="right">
			Sort by: 
			<select class="height30" name="prod_sort" onchange="javascript: js_refresh();">
				<option value="" >None</option>
				<?php func_option("Product Name","prod_name",$prod_sort)?>
				<?php func_option("Brand","prod_brand_id",$prod_sort)?>
				<?php func_option("Category","level_parent",$prod_sort)?>
				<?php func_option("Seller","ps.sup_company",$prod_sort)?>
			</select>
		</td>
		<td align="right">
		<input type="hidden" name="page" id="page" value="<?=$page?>">
		<input type="text" class="height30" name="txt_key" value="<?=$txt_key?>"> 
		<input type="submit" class="btn btn-warning" name="btn_filter" value=" Filter " onclick="javascript: document.getElementById('page').value='';">
		</td>
	</tr>
</form>
    <tr>
        <td colspan="10">

			<?php create_list($sql,"edit_prods.php",20,"tbl_pages",5);?>

        </td>
    </tr>
</table>

<?php
require_once("inc_admin_footer.php");
?>

<script>
	function js_export_prods(){
		document.frm_list.act.value="e_prod";
		document.frm_list.submit();
	}

	function js_export_prices(){
		document.frm_list.act.value="e_price";
		document.frm_list.submit();
	}
	
	function close_export(obj){
		p_obj = document.getElementById("div_export")
		p_obj.style.display="none"
	}
	function js_refresh(){
		document.frm_list.submit();
	}
</script>

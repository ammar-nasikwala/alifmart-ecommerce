<?php
require_once("inc_admin_header.php");
require_once("ajax_admin.php");
$brand_img="";

$img_path = "../images/brands/";
$id = func_read_qs("id");
$page_title="";
$meta_key = "";
$meta_desc = "";

if(isset($_POST["submit"])){

	$msg = img_upload("brand_img",$img_path,$img_name);
	//echo($msg);
	if($msg=="1"){
		$fld_arr = array();
		
		$fld_arr["brand_name"] = func_read_qs("brand_name");
		$fld_arr["page_title"] = func_read_qs("page_title");
		$fld_arr["meta_key"] = func_read_qs("meta_key");
		$fld_arr["meta_desc"] = func_read_qs("meta_desc");
		$fld_arr["brand_desc"] = func_read_qs("brand_desc");
		$fld_arr["brand_status"] = intval(func_read_qs("brand_status"));

		if(func_read_qs("brand_img"."_remove_img")<>"") $fld_arr["brand_img"] = "";
		if($img_name<>"") $fld_arr["brand_img"] = $img_name;
		
		if($id==""){
			//$fld_arr["brand_id"] = get_max("brand_mast","brand_id");
			$qry = func_insert_qry("brand_mast",$fld_arr);
			
			$brand_code = array();
			$brand_code["brand_name"] = $fld_arr["brand_name"];
			
			$result = func_read_qs("brand_code");
			$result = strtoupper($result);
			
			$brand_code["brand_code"]=$result;
			$code_qry = func_insert_qry("brand_master",$brand_code);
			mysqli_query($con,$code_qry);
		}else{
			$qry = func_update_qry("brand_mast",$fld_arr," where brand_id=$id");
		}
				
		if(!mysqli_query($con, $qry)){
			echo("Problem updating database... $qry");
		}else{
			?>
			<script>
				alert("Record saved successfully.");
				window.location.href="manage_brands.php";
			</script>
			<?php
			die("");
		}
	}else{
		?>
		<script>
			alert("<?=$msg?>");
			//window.location.href="manage_brands.php";
		</script>
		<?php
	}
}

$act = func_read_qs("act");
if($act=="d"){
	if(!mysqli_query($con, "delete from brand_mast where brand_id=".func_read_qs("id"))){
		echo("Problem updating database... $qry");
	}else{
		?>
		<script>
			alert("Record Deleted successfully.");
			window.location.href="manage_brands.php";
		</script>
		<?php
		die("");
	}	
}

//global $brand_name;

if($id<>""){
	$qry = "select * from brand_mast where brand_id=".$id;
	if(get_rst($qry, $row)){
		//$row = mysqli_fetch_assoc($rst);
		$brand_name = $row["brand_name"];
		$page_title = $row["page_title"];
		$meta_key = $row["meta_key"];
		$meta_desc = $row["meta_desc"];
		$brand_desc = $row["brand_desc"];
		$brand_status = $row["brand_status"];
		$brand_img = $row["brand_img"];
		
	}

	$qry = "select brand_code from brand_master where brand_name='$brand_name'";
	if(get_rst($qry, $row)){
		//$row = mysqli_fetch_assoc($rst);
		$brand_code = $row["brand_code"];
		
	}
}

?>
<script type="text/javascript" src="../lib/ajax.js"></script>
<script>
	function js_delete(){
		if(confirm("Are you sure you want to delete this record? Deleting this brand will delete all its related products")){		
			document.frm.act.value="d";
		}else{
			return false;
			
		}
	}

	function Submit_form(){
	    if(document.frm.id.value == ""){
			v_valid = check_code(document.frm.brand_code);
		}else{
			v_valid = "Available";
		}
		if(chkForm(document.frm)==false)
			return false;
		else if(v_valid != "Available"){
			alert(v_valid)
			return false;	
		}
		else
			document.frm.submit();
	}

	function check_code(obj){
	v_valid = ""
	if(obj.value != ""){
		var obj_lbl = document.getElementById("msg_code")
		
		v_valid = call_ajax("ajax_admin.php","process=check_brand_code&brand_code=" + obj.value)
		obj_lbl.innerHTML = v_valid
		if(obj_lbl.innerHTML=="Available"){
			obj_lbl.style.color="#44FF88"
			//v_valid = "1"
		}else{
			obj_lbl.style.color="#FF5588"
		}
	}
	return v_valid;
}

	
</script>

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
	
</style>


<?php
if($id){
	$page_head = "Edit Brand Details";
	$disable="disabled";
}else{
	$page_head = "Create New Brand";
	$disable="";
}
?>

<h2><?=$page_head?></h2>

<form name="frm" id="frm" method="post" enctype="multipart/form-data">
	<input type="hidden" id="id" name="id" value="<?=func_var($id)?>">
	<input type="hidden" id="act" name="act" value="">
	
	<table border="1" class="table_form">
	
		<tr>
		<td>Brand Name</td>
		<td><input type="text" size="71" maxlength="50" id="100" title="Brand Name" name="brand_name" value="<?=func_var($brand_name)?>">*</td>
		</tr>
		
		<tr>
		<td>Brand Code</td>
		<td><input type="text" size="10" maxlength="2" id="100" title="Brand Code" name="brand_code" <?=$disable?> style="text-transform:uppercase" onblur="javascript: check_code(this);" value="<?=func_var($brand_code)?>">*<span id="msg_code"></span></td>
		</tr>

		<tr>
		<td>Brand Description</td>
		<td>
		<?php control_textarea("brand_desc",func_var($brand_desc),2000) ?>
		</td>
		</tr>

		<tr>
		<td>Brand Image</td>
		<td valign="top"><?=img_control("brand_img",$img_path,$brand_img)?></td>
		</tr>
		
		<tr>
		<td>Status</td>
		<td>
		<select name="brand_status">
			<?php func_option("Active","1",func_var($brand_status))?>
			<?php func_option("Inactive","0",func_var($brand_status))?>
		</select>
		</td>
		</tr>

		<tr>
		<th colspan="2">SEO Enhancing fields</th>
		</tr>		
		<?php SEO_fields($page_title,$meta_key,$meta_desc)?>
	
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

<?php
require_once("inc_admin_footer.php");
?>

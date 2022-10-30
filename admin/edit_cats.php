<?php
require_once("inc_admin_header.php");

require_once("cat_tree.php");

$level_image="";

$img_path = "../images/levels/";
$id = func_read_qs("id");
$page_title="";
$meta_key = "";
$meta_desc = "";

$_SESSION["tree"] = "yes";

if(isset($_POST["submit"])){
	//$msg = img_upload("level_image",$img_path,$img_name);
	$msg = img_upload_db("level_image",$img_path,$img_name,$img_thumb);
	//echo($msg);
	if($msg=="1"){
		$fld_arr = array();
		
		$fld_arr["level_name"] = func_read_qs("level_name");
		$fld_arr["level_parent"] = "0".intval(func_read_qs("level_parent"));
		//echo("|[".$fld_arr["level_parent"]."]|");
		$fld_arr["page_title"] = func_read_qs("page_title");
		$fld_arr["meta_key"] = func_read_qs("meta_key");
		$fld_arr["meta_desc"] = func_read_qs("meta_desc");
		$fld_arr["level_desc"] = func_read_qs("level_desc");
		$fld_arr["level_status"] = intval(func_read_qs("level_status"));
		//echo("|".$img_name."|");
		if(func_read_qs("level_image"."_remove_img")<>"") $fld_arr["level_image"] = "";
		if($img_name<>"") $fld_arr["level_image"] = $img_name;
		
		if($id==""){
			//$fld_arr["level_id"] = get_max("levels","level_id");
			$qry = func_insert_qry("levels",$fld_arr);
		}else{
			$qry = func_update_qry("levels",$fld_arr," where level_id=$id");
		}
		
		//die($qry);
		if(!mysqli_query($con, $qry)){
			echo("Problem updating database... $qry");
		}else{
			
			/*
			execute_qry("delete from levels_props where level_id=$id");
			get_rst("select prop_id from prop_mast",$row,$rst);
			do{
				$prop_id = func_read_qs("chk_".$row["prop_id"]);
				if($prop_id <> ""){
					$fld_s_arr = array();
					
					$fld_s_arr["level_id"] = $id;
					$fld_s_arr["prop_id"] = $row["prop_id"];

					$qry = func_insert_qry("levels_props",$fld_s_arr);

					execute_qry($qry);					
				}
			}while($row = mysqli_fetch_assoc($rst));
			*/
			
			?>
			<script>
				alert("Record saved successfully.");
				window.location.href="manage_cats.php";
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
	if(!mysqli_query($con, "delete from levels where level_id=".func_read_qs("id"))){
		echo("Problem updating database... $qry");
	}else{
		?>
		<script>
			alert("Record Deleted successfully.");
			window.location.href="manage_cats.php";
		</script>
		<?php
		die("");
	}	
}

//global $level_name;

if($id<>""){
	$qry = "select * from levels where level_id=".$id;
	if(get_rst($qry, $row)){
		//$row = mysqli_fetch_assoc($rst);
		$level_parent = $row["level_parent"];
		$level_name = $row["level_name"];
		$page_title = $row["page_title"];
		$meta_key = $row["meta_key"];
		$meta_desc = $row["meta_desc"];
		$level_desc = $row["level_desc"];
		$level_status = $row["level_status"];
		$level_image = $row["level_image"];
		
	}
}

function get_parent_name($level_parent){
	$parent_name="";
	if(intval($level_parent)==0){
		$parent_name = "[Root]";
	}else{
		$qry = "select level_name from levels where level_id=".$level_parent;
		if(get_rst($qry, $row)){
			//$row = mysqli_fetch_assoc($rst);
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

	function Submit_form(){
		if(chkForm(document.frm)==false)
			return false;
		else
			document.frm.submit();
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
	overflow: scroll;
}

.div_inner_tree{
	overflow: scroll;
	background-color:#FFFFFF;
	border:none;
	padding:10px;
}
	
</style>


<div class="div_tree" id="div_cat_tree" style="display:none;">
<div id="div_cat_inner" class="div_inner">
	<table width="100%" border="0">
	<tr>
	<td><h2>Select Parent Category</h2></td>
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
	$page_head = "Edit Category Details";
}else{
	$page_head = "Create New Category";
}
?>

<h2><?php echo $page_head?></h2>

<form name="frm" id="frm" method="post" enctype="multipart/form-data">
	<input type="hidden" id="id" name="id" value="<?=func_var($id)?>">
	<input type="hidden" id="act" name="act" value="">
	
	<table border="1" class="table_form">

		<tr>
		<td>Parent</td>
		<td>
		<input type="hidden" name="level_parent" id="level_parent" title="Parent category" value="0<?=intval(func_var($level_parent))?>">
		<span id="span_cat_path"><?=get_parent_name($level_parent);?></span>&nbsp;&nbsp;&nbsp;<input type=button class="btn btn-warning" onclick="javascript:open_cat();" value="Select..."></td>
		</tr>

		
		<tr>
		<td>Category Name</td>
		<td><input type="text" size="71" maxlength="50" id="100" title="Category Name" name="level_name" value="<?=func_var($level_name)?>">*</td>
		</tr>

		<tr>
		<td>Category Description</td>
		<td><?php control_textarea("level_desc",func_var($level_desc),2000) ?>
		</td>
		</tr>

		<tr>
		<td>Category Image</td>
		<td valign="top"><?=img_control_db("level_image",$img_path,$level_image)?></td>
		</tr>

		<!--
		<tr>
		<td>Properties Applicable</td>
		<td>
		<div style="height:180px;border:solid 1px #cccccc;overflow-y:scroll;">
		<table border="0" width="100%">
		<?
		/*
		$sql = "select p.prop_id,prop_name,level_id from prop_mast p left join levels_props l on p.prop_id=l.prop_id and l.level_id=0$id order by prop_name";
		//$sql = "select * from prop_mast";
		//echo($sql);
		get_rst($sql,$row,$rst);
		do{
			$level_chk="";
			if($row["level_id"]."" <> ""){$level_chk="1";}
			?>
			<tr>
			<td>
			<input type="checkbox" name="chk_<?=$row["prop_id"]?>" value="1" <?=sel_("1",$level_chk."")?>>
			<?=$row["prop_name"]?>
			</td>
			
			</tr>
		<?
		}while($row = mysqli_fetch_assoc($rst));
		*/
		?>
		</table>
		</div>
		</td>
		</tr>
		-->
		
		<tr>
		<td>Status</td>
		<td>
		<select name="level_status">
			<?php func_option("Active","1",func_var($level_status))?>
			<?php func_option("Inactive","0",func_var($level_status))?>
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

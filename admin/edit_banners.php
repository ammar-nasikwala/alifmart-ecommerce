<?php
require_once("inc_admin_header.php");

$img_path = "../images/banners/";

try{
	if(!is_dir($img_path)){
		mkdir($img_path);
	}
}
catch(Exception $e){}


$banner_img="";
$id = func_read_qs("id");

$saved="";

if(isset($_POST["submit"])){
	$msg = img_upload_db("banner_img",$img_path,$img_name,$img_thumb,"966.5","350","");
	$saved="1";

	$fld_arr = array();

	$fld_arr["banner_alt_text"] = func_read_qs("banner_alt_text");
	$fld_arr["banner_status"] = func_read_qs("banner_status");
	$fld_arr["banner_target_url"] = func_read_qs("banner_target_url");
	$fld_arr["banner_position"] = func_read_qs("banner_position");
	$fld_arr["banner_sort"] = func_read_qs("banner_sort");
	if(func_read_qs("banner_img"."_remove_img")<>"") $fld_arr["banner_img"] = "";
	if($img_name<>"") $fld_arr["banner_img"] = $img_name;

	if($msg=="1"){	
		if($id==""){
			$qry = func_insert_qry("banner_mast",$fld_arr);
		}else{
			$qry = func_update_qry("banner_mast",$fld_arr," where banner_id=$id");
		}
				
		if(!mysqli_query($con, $qry)){
			echo("Problem updating database... $qry");
		}else{
			?>
			<script>
				alert("Record saved successfully.");
				window.location.href="manage_banners.php";
			</script>
			<?php
			die("");
		}
	}else{
		$saved="0";
		?>
		<script>
			alert("<?=$msg?>");
		</script>
		<?php
	}
}

$act = func_read_qs("act");
if($act=="d"){
	if(!mysqli_query($con, "delete from banner_mast where banner_id=".func_read_qs("id"))){
	?>
		<script>
			alert("Problem updating database, please try after sometime.");
			window.location.href="manage_banners.php";
		</script>
	<?php
	}else{
		?>
		<script>
			alert("Record Deleted successfully.");
			window.location.href="manage_banners.php";
		</script>
		<?
		die("");
	}	
}

if($id<>""){
	$qry = "select * from banner_mast where banner_id=".$id;
	if(get_rst($qry, $row)){
		//$row = mysqli_fetch_assoc($rst);
		$banner_alt_text = $row["banner_alt_text"];
		$banner_status = $row["banner_status"];
		$banner_img = $row["banner_img"];
		$banner_target_url = $row["banner_target_url"];
		$banner_sort = $row["banner_sort"];
	}
}

if($saved=="0"){
	$banner_alt_text = $fld_arr["banner_alt_text"];
	$banner_status = $fld_arr["banner_status"];
	$banner_target_url = $fld_arr["banner_target_url"];

}
?>

<script>

	function js_delete(){
		if(confirm("Are you sure you want to delete this record?")){		
			document.frm.act.value="d";
		}else{
			return false;
		}
	}

	function Submit_form(){
		var inp=document.getElementById("banner_img");
		if(document.getElementById("Alt_text").value==""){
			alert("Kindly Enter the Alt text")
			return false;
		}else{
			if(inp.files.length==0 && inp.style.display!="none"){
				alert("Attachment Required") 
				inp.focus();
				return false;
			}
		}
		if(chkForm(document.frm)==false)
			return false;
		else
			document.frm.submit();
	}
	
	function isNumberKey(evt)
	{
	  var charCode = (evt.which) ? evt.which : evt.keyCode;
	  if (charCode != 46 && charCode > 31 
		&& (charCode < 48 || charCode > 57))
		 return false;

	  return true;
	}
	
</script>

<?php
if($id){
	$page_head = "Edit Banner Details";
}else{
	$page_head = "Create New Banner";
}
?>

<h2><?=$page_head?></h2>

<form name="frm" id="frm" method="post" enctype="multipart/form-data">
	<input type="hidden" id="id" name="id" value="<?=func_var($id)?>">
	<input type="hidden" id="act" name="act" value="">
	
	<table border="1" class="table_form">
	
		<tr>
		<td>Banner Alt Text</td>
		<td><input type="text" size="71" maxlength="50" id="Alt_text" title="Alt text" name="banner_alt_text" value="<?=func_var($banner_alt_text)?>">*</td>
		</tr>

		<tr>
		<td>Banner Image</td>
		<td valign="top"><?=img_control_db("banner_img",$img_path,$banner_img)?> <br>(Recommended Dimension: 966 x 350)</td>
		</tr>
		
		<tr>
		<td>Target url</td>
		<td><input type="text" size="71" maxlength="255" id="000" title="targe url" name="banner_target_url" value="<?=func_var($banner_target_url)?>"></td>
		</tr>		
		
		
		<tr>
		<td>Status</td>
		<td>
		<select name="banner_status">
			<?php func_option("Active","1",func_var($banner_status))?>
			<?php func_option("Inactive","0",func_var($banner_status))?>
		</select>
		</td>
		</tr>
		
		<tr>
		<td>Sort Index</td>
		<td><input type="text" size="20" maxlength="2" id="000" title="Banner Index" name="banner_sort" onkeypress="return isNumberKey(event)" value="<?=func_var($banner_sort)?>"></td>
		</tr>
		
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

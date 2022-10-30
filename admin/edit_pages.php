<?php
require_once("inc_admin_header.php");

$brand_img="";

$img_path = "../images/cms/";
$id = func_read_qs("id");
$page_title="";
$meta_key = "";
$meta_desc = "";

if(isset($_POST["submit"])){

	//$msg = img_upload("brand_img",$img_path,$img_name);
	//echo($msg);
	$msg="1";
	if($msg=="1"){
		$fld_arr = array();
		
		$fld_arr["page_title"] = func_read_qs("page_title");
		$fld_arr["meta_key"] = func_read_qs("meta_key");
		$fld_arr["meta_desc"] = func_read_qs("meta_desc");
		$fld_arr["middle_panel"] = $_POST["middle_panel"];
		$fld_arr["page_updated"] = date("Y-m-d H:i:s");

		if($id==""){
			//$fld_arr["brand_id"] = get_max("brand_mast","brand_id");
			$qry = func_insert_qry("cms_pages",$fld_arr);
		}else{
			$qry = func_update_qry("cms_pages",$fld_arr," where page_id=$id");
		}
		
		if(!mysqli_query($con, $qry)){
			echo("Problem updating database... $qry");
		}else{
			?>
			<script>
				alert("Record saved successfully.");
				window.location.href="manage_pages.php";
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

//global $brand_name;

if($id<>""){
	$qry = "select * from cms_pages where page_id=".$id;
	if(get_rst($qry, $row)){
		//$row = mysqli_fetch_assoc($rst);
		$page_name = $row["page_name"];
		$page_heading = $row["page_heading"];
		$page_title = $row["page_title"];
		$meta_key = $row["meta_key"];
		$meta_desc = $row["meta_desc"];
		$middle_panel = $row["middle_panel"];
		$page_updated = $row["page_updated"];
	
	}
}

?>

<script>


	function js_delete(){
		if(confirm("Are you sure you want to delete this record? Deleting this brand will delete all its related products")){		
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
	$page_head = "Edit Page Content of $page_heading";
}else{
	$page_head = "Create New Brand";
}
?>

<h2><?=$page_head?></h2>

<form name="frm" id="frm" method="post" enctype="multipart/form-data">
	<input type="hidden" id="id" name="id" value="<?=func_var($id)?>">
	<input type="hidden" id="act" name="act" value="">
	
	<table border="1" class="table_form">
		
		<?php SEO_fields($page_title,$meta_key,$meta_desc)?>	
		
		<tr>
		<td>Page Content</td>
		<td><textarea class="wysiwyg" name="middle_panel"><?=func_var($middle_panel)?></textarea>
		</td>
		</tr>		
		
		<tr>
		<th colspan="2" id="centered">
		<input type="submit" class="btn btn-warning" value="Save" name="submit" onClick="javascript:return Submit_form();">
		</th>
		</tr>
		
	</table>
</form>

<?php
require_once("inc_admin_footer.php");
?>

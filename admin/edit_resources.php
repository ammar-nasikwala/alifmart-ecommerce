<?
require_once("inc_admin_header.php");


$id = func_read_qs("id");

if(isset($_POST["submit"])){

		
		
		$fld_arr = array();
		
		$fld_arr["resource_name"] = func_read_qs("resource_name");
		$fld_arr["resource_path"] = func_read_qs("resource_path");

		
		if($id==""){
			//$fld_arr["brand_id"] = get_max("brand_mast","brand_id");
			$qry = func_insert_qry("resources",$fld_arr);
		}else{
			$qry = func_update_qry("resources",$fld_arr," where resource_id=$id");
		}
				
		if(!mysqli_query($con, $qry)){
			echo("Problem updating database... $qry");
		}else{
			?>
			<script>
				alert("Record saved successfully.");
				window.location.href="resources.php";
			</script>
			<?
			die("");
		}
	
}

$act = func_read_qs("act");
if($act=="d"){
	if(!mysqli_query($con, "delete from resources where resource_id=".func_read_qs("id"))){
		echo("Problem updating database... $qry");
	}else{
		?>
		<script>
			alert("Record Deleted successfully.");
			window.location.href="resources.php";
		</script>
		<?
		die("");
	}	
}

//global $brand_name;

if($id<>""){
	$qry = "select * from resources where resource_id=".$id;
	if(get_rst($qry, $row)){
		//$row = mysqli_fetch_assoc($rst);
		$resource_name = $row["resource_name"];
		$resource_path = $row["resource_path"];
		
	}
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


<?
if($id){
	$page_head = "Edit Resource Details";
}else{
	$page_head = "Create New Resource";
}
?>

<h2><?=$page_head?></h2>

<form name="frm" id="frm" method="post" enctype="multipart/form-data">
	<input type="hidden" id="id" name="id" value="<?=func_var($id)?>">
	<input type="hidden" id="act" name="act" value="">
	
	<table border="1" class="table_form">
	
		<tr>
		<td>Resource Name : *</td>
		<td><input class="form-control textbox-lrg" type="text" size="71" id="100" title="Resource Name" name="resource_name" value="<?=func_var($resource_name)?>"></td>
		</tr>
		
		<tr>
		<td>Resource Path : *</td>
		<td><input class="form-control textbox-lrg" type="text" size="71" id="100" title="Resource Path" name="resource_path" value="<?=func_var($resource_path)?>"></td>
		</tr>
		
		<tr>
		<th colspan="2" id="centered">
		<input type="submit" class="btn btn-warning" value="Save" name="submit" onClick="javascript:return Submit_form();">
		<?if($id<>""){?>
			&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="submit" class="btn btn-warning" value="Delete" name="btn_delete" onclick="javascript: return js_delete();">
		<?}?>
		
		</td>
		</tr>
		
	</table>
</form>

<?
require_once("inc_admin_footer.php");

?>

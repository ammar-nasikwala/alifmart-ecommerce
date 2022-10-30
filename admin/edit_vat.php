<?
require_once("inc_admin_header.php");

if(isset($_POST["submit"])){

	$fld_arr = array();
	
	$fld_arr["vat"] = func_read_qs("vat");

	$qry = func_update_qry("configuration",$fld_arr,"");
				
	if(!mysqli_query($qry)){
		echo("Problem updating database... $qry");
	}else{
		?>
		<script>
			alert("Record saved successfully.");
			//window.location.href="edit_.php";
		</script>
		<?
		//die("");
	}
}

$act = func_read_qs("act");

//global $brand_name;

$rst = mysqli_query("select vat from configuration");
if($rst){
	$row = mysqli_fetch_assoc($rst);
	$vat = $row["vat"];
}

?>

<script>


	function Submit_form(){
		if(chkForm(document.frm)==false)
			return false;
		else
			document.frm.submit();
	}
	
</script>

<?

$page_head = "Set VAT";

?>

<h2><?=$page_head?></h2>

<form name="frm" id="frm" method="post" enctype="multipart/form-data">
	<input type="hidden" id="id" name="id" value="<?=func_var($id)?>">
	<input type="hidden" id="act" name="act" value="">
	
	<table border="1" class="table_form">
	
		<tr>
		<td>Vat %</td>
		<td><input type="text" size="5" maxlength="5" id="110" title="VAT %" name="vat" value="<?=func_var($vat)?>">%</td>
		</tr>

		<tr>
			<td>&nbsp;</td>
		</tr>
		
		<tr>
		<th colspan="2" >
		<input type="submit" class="btn btn-warning" value="Save" name="submit" onClick="javascript:return Submit_form();">		
		</td>
		</tr>
		
	</table>
</form>

<?
require_once("inc_admin_footer.php");

?>

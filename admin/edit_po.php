<?php 
require("inc_admin_header.php");

$id = func_read_qs("id");

?>
<style>
	.quotation{
		border-top: solid 1px #FFFFFF !important; 
		font-size: initial;
	}
</style>
<?php
if($id){
	$page_head = "Purchase Order Details";
}else{
	$page_head = "Create New Purchase Order";
}
?>
<h2><?=$page_head?></h2>


	
<?php  get_rst("select po_path from po_details where id=0$id",$row1)?>
		<iframe src="<?php echo $row1["po_path"];?>" width="100%" height="500px"></iframe>
		

	
<?php 
require_once("inc_admin_footer.php");
?>

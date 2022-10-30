<?php
require_once("inc_admin_header.php");

$sql="select id";
$sql = $sql.",city as 'City'"; 
$sql = $sql.",min_weight as 'Min-Weight'";
$sql = $sql.",max_weight as 'Max-Weight'";
$sql = $sql.",charges as 'Charges'";
$sql = $sql."from local_logistics_charges";
		
if(isset($_GET["id"])){
$id = $_GET["id"];
if(isset($_GET["dl"])){
		
		$qry = "delete from local_logistics_charges where id=$id ";
		$result=mysqli_query($con, $qry);
		if($result){
			$msg = "Record deleted successfully";
		}
	}else{
	header("location: edit_local_shipping_charges.php?id=$id");
	}
}
	
?>

<table border="0" width="100%">
	<tr>
		<td><h2>Manage Local Logistic Charges</h2></td>
	</tr>
	
	<tr>
		<td><a href="edit_local_shipping_charges.php">Create Local Logistic Charges[+]</a><br><br></td>
	</tr>

    <tr>
        <td>

				<?php create_list($sql,"edit_local_shipping_charges.php",$rec_limit=200,$pg_class="tbl_pages",5,"", "table_form", 2);?>

        </td>
    </tr>
</table>

<?php
require_once("inc_admin_footer.php");
?>

<?php
require_once("inc_admin_header.php");

$sql="select id";
$sql = $sql.",vendor_name as 'Vendor Name'"; 
$sql = $sql.",city as 'City'";
$sql = $sql.",contact_no as 'Contact Number'";
$sql = $sql."from local_logistics_vendor";
		
		if(isset($_GET["id"])){
		$id = $_GET["id"];
		if(isset($_GET["dl"])){
				
				$qry = "delete from local_logistics_vendor where id=$id ";
				$result=mysqli_query($con, $qry);
				if($result){
					$msg = "Record deleted successfully";
				}
			}else{
			header("location: edit_manage_local_logistics.php?id=$id");
			}
		}
	
?>

<table border="0" width="100%">
	<tr>
		<td><h2>Manage Local Logistic Vendors</h2></td>
	</tr>
	
	<tr>
		<td><a href="edit_manage_local_logistics.php">Create New Vendor [+]</a><br><br></td>
	</tr>

    <tr>
        <td>

				<?php create_list($sql,$url,$rec_limit=200,$pg_class="tbl_pages",5,"", "table_form", 2);?>

        </td>
    </tr>
</table>

<?php
require_once("inc_admin_footer.php");

?>

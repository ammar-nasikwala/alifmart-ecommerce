<?php
require_once("inc_admin_header.php");

$sql="select memb_id";
$sql = $sql.",memb_email as 'Email'"; 
$sql = $sql.",memb_company as 'Establishment Name'"; 
$sql = $sql.",(select company_id from po_company_mast where owner_id=memb_id) as 'Company Token'";
$sql = $sql." from member_mast where subscribe_saas IS NOT NULL order by memb_id DESC"; 

?>
<table border="0" width="100%">
	<tr>
		<td><h2>Manage Briefcase User</h2></td>
	</tr>
    <tr>
        <td>
			<?php create_list($sql,"edit_briefcase_user.php",20,"tbl_pages",5);?>
        </td>
    </tr>
</table>
<?php
require_once("inc_admin_footer.php");
?>
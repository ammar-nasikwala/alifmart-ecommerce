<?php
require_once("inc_admin_header.php");

$sql="select job_id";
$sql = $sql.",job_title as 'Job Title'"; 
$sql = $sql.",job_opening as 'No. of opening'"; 
$sql = $sql.",hiring_mng_email as 'Hiring Manger Email ID'";
$sql = $sql.",job_status as 'Status'";  
$sql = $sql.",posting_date as 'Posting Date'"; 
$sql = $sql."from job_requirement order by job_id desc";
?>




<table border="0" width="100%">
	<tr>
		<td><h2>Manage Job Requirement</h2></td>
	</tr>
	<form name="frm_list" method="post" action="manage_job_requirement.php">
	<input type="hidden" name="act" value="1">
	<tr>
		<td><a href="job_requirement.php">Create New Requirement [+]</a></td>
    <tr>
        <td>
						<?php create_list($sql,"job_requirement.php",20,"tbl_pages",5);?>

        </td>
    </tr>
	</tr>
</table>








<?php
require_once("inc_admin_footer.php");
?>
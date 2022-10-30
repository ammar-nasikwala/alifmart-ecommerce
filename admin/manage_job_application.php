<?php
require_once("inc_admin_header.php");


$sql="select cr_applicant_id";
$sql = $sql.",cr_applicant_name as 'Name'"; 
$sql = $sql.",cr_applicant_email as 'Email'"; 
$sql = $sql.",job_title as 'Job Title'";
$sql = $sql.",app_status as 'Application Status'";  
$sql = $sql."from career_applicants order by cr_applicant_id desc";


?>


<table border="0" width="100%">
	<tr>
		<td><h2>Manage Job Application</h2></td>
	</tr>

    <tr>
        <td>
			<?php create_list($sql,"edit_job_application.php",20,"tbl_pages",5);?>
        </td>
    </tr>
</table>


<?php
require_once("inc_admin_footer.php");
?>
<?php 	

if(session_id() == '') {
		session_start();
		$session_memb = "";
}
require("lib/inc_library.php");

$server_type="0";
if (strpos(gethostname(),"localhost") !== false) {
	$server_type="1";
} ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<?php

$page_name = get_cur_page_name();
$page_name = substr($page_name,1);
$page_name = substr($page_name,0,strlen($page_name)-4);
$qry = "Select page_title, meta_key, meta_desc, middle_panel from cms_pages where page_name='$page_name'";
$cms_title = "";
$cms_meta_key = "";
$cms_meta_desc = "";
$cms_middle_panel = "";
	
if(get_rst($qry, $row)){
	$cms_title = "Company-Name - ". $row["page_title"];
	$cms_meta_key = $row["meta_key"];
	$cms_meta_desc = $row["meta_desc"];
	$cms_middle_panel = $row["middle_panel"];
}
?>

<script type="text/javascript" src="<?=$url_root?>/lib/ajax.js"></script>
<script>
function popup(msg)
{
	alert(msg);
}
</script>
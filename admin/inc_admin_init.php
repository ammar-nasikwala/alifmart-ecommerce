<?php 	

if(session_id() == '') {
		session_start();
		$session_admin = "";
}
require_once("../lib/inc_library.php");
header("Content-Type: text/html; charset=ISO-8859-1");
if(isset($_SESSION["admin"])){
	$session_admin = $_SESSION["admin"];
}

$page_name = strtolower(get_pagename($_SERVER["SCRIPT_NAME"]));

if($session_admin=="" AND $page_name != "/index.php" AND $page_name != "/mysql.php"){
?>
	<script>
		alert("Your session timed out. Please login again.");
		window.location.href="index.php";
	</script>
<?php
}
?>

<script src="../lib/tinymce/tinymce.min.js"></script>
<script type="text/javascript">
//Configure Text editor options
tinymce.init({
    selector: "textarea.wysiwyg",
	maxlength: "10",
	theme: 'modern',
	plugins: "textcolor colorpicker table spellchecker code image link imagetools fullscreen",
    toolbar: "forecolor backcolor | bold italic underline | alignleft aligncenter alignright alignjustify |	formatselect fontsizeselect | bullist numlist | link image | fullscreen",
	menu: {
    file: {title: 'File', items: 'newdocument'},
    edit: {title: 'Edit', items: 'undo redo | cut copy paste pastetext | selectall'},
    insert: {title: 'Insert', items: 'link media | template hr'},
    view: {title: 'View', items: 'visualaid'},
    format: {title: 'Format', items: 'bold italic underline strikethrough superscript subscript | formats | removeformat'},
    table: {title: 'Table', items: 'inserttable tableprops deletetable | cell row column'},
    tools: {title: 'Tools', items: 'spellchecker code'}
	},
	setup:function(ed) {
		//ed.onKeyDown.add(function(ed, e) {
			//alert("1")

		//});//ed.onKeyUp.add
	}//setup

});
</script>
<?php
ini_set("display_errors", "1");

require_once("../lib/inc_connection.php");
require_once("../lib/inc_library.php");

$sql = func_var($_POST["sql"]);

?>

<html>
<head>

</head>
<body>

<form name="frm" method="post">
<textarea name="sql" rows=5 cols=90><?=$sql?></textarea>

<input type=submit>
</form>

<?php
if($sql<>"" and func_read_qs("admin")=="-1"){
	if(substr(trim($sql),0,6)=="select"){
		view_table($sql);
	}else{
		mysqli_query($sql);
		echo("Executed...");
	}
}

function view_table($sql){
	$row_no = 0;
	$rst = mysqli_query($sql);
	
	if (!$rst) {
		die('Invalid query: ' . mysqli_error());
	}
	?>

	<table border=1>
	<tr>
	<?php

	while ($row = mysqli_fetch_assoc($rst)) {
		if($row_no==0){
			foreach($row as $key => $value) {			
				echo("<th>$key</th>");
			}
			echo("</tr>");
		}
		echo("<tr>");
		foreach($row as $key => $value) {			
			echo("<td>$value</td>");
		}
		echo("</tr>");
		$row_no++;
	}

	?>
	</tr>
	</table>
	<hr>
<?php } ?>
</body>
</html>
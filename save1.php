<html>

<head>

<script>

function js_save(){
	document.frm.act.value="s"
	document.frm.submit()
}
</script>

</head>
<?php

function func_read_qs($key){
	//echo("|$key|");
	if(isset($_GET[$key])){
		return $_GET[$key]."";
	}elseif(isset($_POST[$key])){
		return $_POST[$key]."";
	}else{
        return "";
	}
}


$file_name = func_read_qs("file_name");
$act = func_read_qs("act");
$file_txt = func_read_qs("file_txt");

if($act=="s"){
	$myfile = fopen(__DIR__."/".$file_name, "w");
	fwrite($myfile, $file_txt);
	fclose($myfile);
	echo("Saved");
}
?>

<body>

<form name="frm" method="post">

<input type="hidden" value="1" name="act">

<input type="text" name="file_name" value="<?=$file_name?>">
<input type="submit" value="Open">
<input type="button" value="Save" onclick="javascript: js_save();">

<textarea name="file_txt" rows="40" cols="190">
<?
//$file_name = $file_name.".php";

/*
$myfile = fopen($file_name, "r") or die("Unable to open file!");
echo fread($myfile,filesize($file_name));
fclose($myfile);
*/

if($file_name<>"" and file_exists($file_name)){

	$file = fopen(__DIR__."/".$file_name, "r") or die("not found");
	$count = 0;
	$line = "";
	?>
	
	<?
	while(!feof($file) AND $count<10000) {
	  $line = fgets($file);
		echo($line);
		
	  $count++;
	}
	?>
	
	<?
	fclose($file);
}
?>
</textarea>

</form>
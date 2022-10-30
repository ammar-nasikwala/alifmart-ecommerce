<?php

require("inc_init.php");
$msg="";

if(isset($_POST["submit"])){
	if($_POST['captcha'] != $_SESSION['digit']){
			 $msg = "Please enter valid CAPTCHA code";
	}else{
	
	$fld_s_arr = array();
					
	$fld_s_arr["q1_ratting"] = func_read_qs("q1");
	$fld_s_arr["q2_ratting"] = func_read_qs("q2");
	$fld_s_arr["q3_ratting"] = func_read_qs("q3");
	$fld_s_arr["q4_ratting"] = func_read_qs("q4");
	$fld_s_arr["q5_ratting"] = func_read_qs("q5");
	$fld_s_arr["q6_ratting"] = func_read_qs("q6");
	$fld_s_arr["q7_ratting"] = func_read_qs("q7");
	$fld_s_arr["q8_ratting"] = func_read_qs("q8");
	$fld_s_arr["q9_ratting"] = func_read_qs("q9");
	$fld_s_arr["q10_ratting"] = func_read_qs("q10");
	$fld_s_arr["suggestion"] = func_read_qs("suggestion");
	
		$qry = func_insert_qry("feedback_buyer",$fld_s_arr);
		execute_qry($qry);

	$msg="Thank you for providing your valuable inputs.";
	}
}

?>

<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
<title><?=$cms_title?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta name="DESCRIPTION" content="<?=$cms_meta_desc?>"/>
<meta name="KEYWORDS" content="<?=$cms_meta_key?>"/>
<link href="styles/styles.css" rel="stylesheet" type="text/css" />
<link href="styles/bannerstyle.css" rel="stylesheet" type="text/css" />
<script src="scripts/scripts.js" type="text/javascript"></script>
<script type="text/javascript" src="scripts/animatedcollapse.js"></script>
</head>
<body>
<div class="">
<?php
require("header.php");
?>

<div id="contentwrapper">
	<div class="center-panel">
		<div class="you-are-here">
			<p align="left">
				YOU ARE HERE:<span class="you-are-normal">Feedback Form</span>
			</p>
		</div>
	<form name="frm" method="post" action="">
		<br>
	<div class="panel panel-info">
		<div class="panel-heading">
			<h4>Company-Name Buyer Feedback</h4>
		</div>	
		<table  width="100%"  border="0" align="center" class="list">
		<br>
	<?if($msg<>""){?>
		<div class="alert alert-info">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			<?=$msg?>
		</div>
	<?}?>		
	<div>
		<table  width="100%"  border="0" align="center" class="list">
			<tr>
				<td>
					<div align="left" class="color-amber" style="padding:2px;">
					&emsp;Note: Please choose from 1 to 5 ratings for each questions, 1 being lowest and 5 being highest.
					</div>
				</td>	
			</tr>
			<tr>
				<td>
				<p>&emsp; How statisfied are you with the product quality on our store?</p>
				</td>
			</tr>
			<tr>
				<td>
					<p>&emsp;<input type="radio" name="q1" value="very poor "> 1&emsp;
					<input type="radio" name="q1" value="poor"> 2&emsp;
					<input type="radio" name="q1" value="average"> 3&emsp;
					<input type="radio" name="q1" value="good"> 4&emsp;
					<input type="radio" name="q1" value="excellent"> 5 </p>
				</td>
			</tr>
			<tr>
				<td align="right" class="table-bg" colspan="10"></td>
			</tr>
			<tr>
				<td>
				<p>&emsp; How easy do you find our order checkout process?</p>
				</td>
			</tr>
			<tr>
				<td>
					<p>&emsp;<input type="radio" name="q2" value="very poor "> 1&emsp;
					<input type="radio" name="q2" value="poor"> 2&emsp;
					<input type="radio" name="q2" value="average"> 3&emsp;
					<input type="radio" name="q2" value="good"> 4&emsp;
					<input type="radio" name="q2" value="excellent"> 5 </p>
				</td>
			</tr>
			<tr>
				<td align="right" class="table-bg" colspan="10"></td>
			</tr>
			<tr>
				<td>
				<p>&emsp; How did you hear about our business?</p>
				</td>
			</tr>
			<tr>
				<td>
					<p>&emsp;<input type="radio" name="q3" value="Search Engine"> Search Engine&emsp;
					<input type="radio" name="q3" value="Link From Another Site"> Link From Another Site&emsp;
					<input type="radio" name="q3" value="Printed Media"> Printed Media&emsp;
					<input type="radio" name="q3" value="Word of Mouth"> Word of Mouth&emsp;
				</td>
			</tr>
			<tr>
				<td align="right" class="table-bg" colspan="10"></td>
			</tr>
			<tr>
				<td>
				<p>&emsp; Were our product pictures, descriptions, and prices clear and easy to understand?</p>
				</td>
			</tr>
			<tr>
				<td>
					<p>&emsp;<input type="radio" name="q4" value="yes"> Yes&emsp;
					<input type="radio" name="q4" value="They we're pretty easy to understand"> They we're pretty easy to understand&emsp;
					<input type="radio" name="q4" value="no"> No </p>
				</td>
			</tr>
			<tr>
				<td align="right" class="table-bg" colspan="10"></td>
			</tr>
			<tr>
				<td>
				<p>&emsp; How happy are you with the purchase?</p>
				</td>
			</tr>
			<tr>
				<td>
					<p>&emsp;<input type="radio" name="q5" value="very poor "> 1&emsp;
					<input type="radio" name="q5" value="poor"> 2&emsp;
					<input type="radio" name="q5" value="average"> 3&emsp;
					<input type="radio" name="q5" value="good"> 4&emsp;
					<input type="radio" name="q5" value="excellent"> 5 </p>
				</td>
			</tr>
			<tr>
				<td align="right" class="table-bg" colspan="10"></td>
			</tr>
			<tr>
				<td>
				<p>&emsp; How often do you visit our web site?</p>
				</td>
			</tr>
			<tr>
				<td>
					<p>&emsp;<input type="radio" name="q6" value="Daily"> Daily&emsp;
					<input type="radio" name="q6" value="Once a week"> Once a week&emsp;
					<input type="radio" name="q6" value="Once a month"> Once a month&emsp;
					<input type="radio" name="q6" value="Every few months"> Every few months&emsp;
					<input type="radio" name="q6" value="A couple times a year"> A couple times a year&emsp;
					<input type="radio" name="q6" value="Once a year"> Once a year</p>
				</td>
			</tr>
			<tr>
				<td align="right" class="table-bg" colspan="10"></td>
			</tr>
			<tr>
				<td>
				<p>&emsp; Were you able to easily locate what you were looking for in our online catalog?</p>
				</td>
			</tr>
			<tr>
				<td>
					<p>&emsp;<input type="radio" name="q7" value="yes"> Yes&emsp;
					<input type="radio" name="q7" value="It took a little bit of extra effort"> It took a little bit of extra effort&emsp;
					<input type="radio" name="q7" value="no"> No </p>
				</td>
			</tr>
			<tr>
				<td align="right" class="table-bg" colspan="10"></td>
			</tr>
			<tr>
				<td>
				<p>&emsp; How well did we communicate?</p>
				</td>
			</tr>
			<tr>
				<td>
					<p>&emsp;<input type="radio" name="q8" value="very poor "> 1&emsp;
					<input type="radio" name="q8" value="poor"> 2&emsp;
					<input type="radio" name="q8" value="average"> 3&emsp;
					<input type="radio" name="q8" value="good"> 4&emsp;
					<input type="radio" name="q8" value="excellent"> 5 </p>
				</td>
			</tr>
			<tr>
				<td align="right" class="table-bg" colspan="10"></td>
			</tr>
			<tr>
				<td>
				<p>&emsp; How likely is that would you recommend us to a friend or a colleague?</p>
				</td>
			</tr>
			<tr>
				<td>
					<p>&emsp;<input type="radio" name="q9" value="very poor "> 1&emsp;
					<input type="radio" name="q9" value="poor"> 2&emsp;
					<input type="radio" name="q9" value="average"> 3&emsp;
					<input type="radio" name="q9" value="good"> 4&emsp;
					<input type="radio" name="q9" value="excellent"> 5 </p>
				</td>
			</tr>
			<tr>
				<td align="right" class="table-bg" colspan="10"></td>
			</tr>
			<tr>
				<td>
				<p>&emsp; How is overall customer experience of our store?</p>
				</td>
			</tr>
			<tr>
				<td>
					<p>&emsp;<input type="radio" name="q10" value="very poor "> 1&emsp;
					<input type="radio" name="q10" value="poor"> 2&emsp;
					<input type="radio" name="q10" value="average"> 3&emsp;
					<input type="radio" name="q10" value="good"> 4&emsp;
					<input type="radio" name="q10" value="excellent"> 5 </p>
				</td>
			</tr>
			<tr>
				<td align="right" class="table-bg" colspan="10"></td>
			</tr>
		</table>
		<table width="100%" class="list">	
			<tr>
				<td align="right" class="table-bg2">
				<p>Are there any suggestions you have for our site?</p>
				</td>
				<td align="left" class="table-bg2"><textarea rows="3" class="form-control textbox-lrg" cols="50" name="suggestion" id="description" maxlength="500"></textarea>(Max character 500)
				</td></td>
			</tr>
			<tr>
				<td align="right" class="table-bg2"><div align="right">
				<p>Validation code:</p>
				</div></td>
				<td><img src="/captcha.php" width="120" height="30" border="1" alt="CAPTCHA" id="captchaimg"><br>
				<label for='message'>Enter the code above here : </label><input type="text" style="margin-left:5px" id="captcha_code" size="8" class="form-control textbox-auto" maxlength="5" name="captcha" value="">
				
				<br>
				Can't read the image? click <a class="color-amber" href="javascript: refreshCaptcha();">here</a> to refresh.</td>
			</tr>
		</table>	
		<table width="100%" class="list">
			<tr>
				<td align="center"><input type="submit" class="btn btn-warning" value="Submit" name="submit" onclick="return validate();"></td>
			</tr>
		</table>
	</div>	
	</form>
	</div>

</div>


<?php
	require("footer.php");
?>

</div>
</div>
</div>
</body>
<script src="scripts/chat.js" type="text/javascript"></script>
<script>
function validate(){
	if(document.getElementById("captcha_code").value=="") {
		alert('Please enter the CAPTCHA code');
		frm.captcha.focus();
		return false;
	}
}
</script>
</html>
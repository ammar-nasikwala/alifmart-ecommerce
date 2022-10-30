<?
require_once("inc_admin_header.php");
$msg="";

if(isset($_POST["submit"])){
	
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
	//$fld_s_arr["q10_ratting"] = func_read_qs("q10");
	$fld_s_arr["suggestion"] = func_read_qs("suggestion");
	
		$qry = func_insert_qry("feedback_seller",$fld_s_arr);
		execute_qry($qry);

	$msg="Thank you for providing your valuable inputs.";
}	


?>
<div class="panel panel-info">
<div class="panel-heading">
<h4>Company-Name Seller Feedback</h4>
</div>
<br>
<?if($msg<>""){?>
<div class="alert alert-info">
	<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <?=$msg?>
</div>
<?}?>
<form name="frm" method="post" action="">
		<table width="100%" border="0">
			<tr>
				<td>
					<div  style="color:#eb9316 !important; padding:2px;">
					&emsp;Note: Please choose from 1 to 5 ratings for each questions, 1 being lowest and 5 being highest.
					</div>
				</td>	
			</tr>
			<tr style="border-top: 2px solid #e5e5e5;">
				<td>
					<p>&emsp; How satisfied are you with support provided?</p>
				</td>
			</tr>
			<tr>
				<td width="100%">
					<p>&emsp;<input type="radio" name="q1" value="very poor "> 1&emsp;
					<input type="radio" name="q1" value="poor"> 2&emsp;
					<input type="radio" name="q1" value="average"> 3&emsp;
					<input type="radio" name="q1" value="good"> 4&emsp;
					<input type="radio" name="q1" value="excellent"> 5 </p>
				</td>
			</tr>
			<tr style="border-top: 2px solid #e5e5e5;">
				<td>
					<p>&emsp; How easy it is to register and start selling on our store?</p>
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
			<tr style="border-top: 2px solid #e5e5e5;">
				<td>
					<p>&emsp; How easy it is to upload and edit product on our store?</p>
				</td>
			</tr>
			<tr>
				<td>
					<p>&emsp;<input type="radio" name="q3" value="very poor "> 1&emsp;
					<input type="radio" name="q3" value="poor"> 2&emsp;
					<input type="radio" name="q3" value="average"> 3&emsp;
					<input type="radio" name="q3" value="good"> 4&emsp;
					<input type="radio" name="q3" value="excellent"> 5 </p>
				</td>
			</tr>
			<tr style="border-top: 2px solid #e5e5e5;">
				<td>
					<p>&emsp; How well did we able to solve the problem of selling in larger industrial market?</p>
				</td>
			</tr>
			<tr>
				<td>
					<p>&emsp;<input type="radio" name="q4" value="very poor "> 1&emsp;
					<input type="radio" name="q4" value="poor"> 2&emsp;
					<input type="radio" name="q4" value="average"> 3&emsp;
					<input type="radio" name="q4" value="good"> 4&emsp;
					<input type="radio" name="q4" value="excellent"> 5 </p>
				</td>
			</tr>
			<tr style="border-top: 2px solid #e5e5e5;">
				<td>
					<p>&emsp; How well did we communicate?</p>
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
			<tr style="border-top: 2px solid #e5e5e5;">
				<td>
					<p>&emsp; Did you find our commission rates reasonable?</p>
				</td>
			</tr>
			<tr>
				<td>
					<p>&emsp;<input type="radio" name="q6" value="yes"> Yes&emsp;
					<input type="radio" name="q6" value="no"> No</p>
				</td>
			</tr>
			<tr style="border-top: 2px solid #e5e5e5;">
				<td>
					<p>&emsp; How did you hear about our business?</p>
				</td>
			</tr>
			<tr>
				<td>
					<p>&emsp;<input type="radio" name="q7" value="Search Engine"> Search Engine&emsp;
					<input type="radio" name="q7" value="Link From Another Site"> Link From Another Site&emsp;
					<input type="radio" name="q7" value="Printed Media"> Printed Media&emsp;
					<input type="radio" name="q7" value="Word of Mouth"> Word of Mouth&emsp;
				</td>
			</tr>
			<tr style="border-top: 2px solid #e5e5e5;">
				<td>
					<p>&emsp; How likely is that would you recommend us to a friend or a colleague?</p>
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
			<tr style="border-top: 2px solid #e5e5e5;">
				<td>
					<p>&emsp; How is overall selling experience with us?</p>
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
		</table>
		<table width="100%">	
			<tr style="border-top: 2px solid #e5e5e5;">
				<td style="width:350px !important;">
					<p>Are there any suggestions you have for our site?</p>
				</td>
				<td style="padding:5px !important;">
					<textarea rows="3" class="form-control textbox-lrg" cols="50" name="suggestion" id="description" maxlength="500" style="width:350px !important;"></textarea>(Max character 500)
				</td>
			</tr>
		</table>
	<br>
	
	<table width="100%">
		<tr>
			<td align="center"><input type="submit" class="btn btn-warning" value="Submit" name="submit"></td>
		</tr>
	</table>
</div>
</form>
<?
require_once("inc_admin_footer.php");
?>

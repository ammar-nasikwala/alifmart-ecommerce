<?php
if(session_id() == '') {
	session_start();
}
	require_once("../lib/inc_library.php");


	if(func_read_qs("process")=="get_prop_options"){
		$prop_id = func_read_qs("prop_id");	
		?>
		<response>

		<?php
		if(get_rst("select * from prop_mast where prop_id=$prop_id",$row)){
			$prop_name=$row["prop_name"];
		}
		?>
		<b><?=$prop_name?></b><br>
		<input type="hidden" name="prop_ids[]" value="<?=$row["prop_id"]?>">
		<?php
		if(get_rst("select distinct opt_value from prod_props where prop_id=$prop_id",$row,$rst)){
			do{?>
				<input type="checkbox" name="opt_<?=$prop_id?>[]" value="<?=$row["opt_value"]?>"><?=$row["opt_value"]?><br>
				<?php
			}while($row = mysqli_fetch_assoc($rst));
		}
		?>
		<br>Add additional multiple options in below box by pressing enter<br>
		<textarea name="opt_new_<?=$prop_id?>" style="width:300px;height:45px;"></textarea>
		<br>

		</response>
		<?php
	}
		// ajax call to generate sku code automatically
	if(func_read_qs("process")=="get_sku"){
		$prod_stockno = func_read_qs("prod_stockno");
		$prod_id = func_read_qs("prod_id");
		if($prod_id <> ""){
			$sku_code = $prod_stockno;
			echo ("<response>$sku_code</response>");
		}else{
			$level_parent=func_read_qs("level_parent");
			$level_name = func_read_qs("level_name");
			$cat = array();
			$cat = get_category_name($level_parent, str_replace("[space]", " ", $level_name));
			$category = $cat[0];
			$sub_category = $cat[1];
			$prod_brand_id=func_read_qs("brand_id");
			$brand_name = get_brand_name($prod_brand_id);
			$sku_code = generate_sku($category, $sub_category,$brand_name);
			echo ("<response>$sku_code</response>");
		}
	}
	
	if(func_read_qs("process")=="check_brand_code"){
		$response = "Available";
		$brand_code = func_read_qs("brand_code");
		
			if(get_rst("select brand_name from brand_master where brand_code='$brand_code'",$row,$rst)){
				$response = "This brand code already exists. Please provide another one.";
			}
			
		echo("<response>$response</response>");
	}
	
	if($_POST['v_id'])      // for local logistics to fetch vendor name when city gets selected
	{
		$city=$_POST['v_id'];
	
		get_rst("select id,vendor_name from local_logistics_vendor where city LIKE '%$city%'",$row,$rst);
		?><option selected="selected">Select Vendor :</option><?php
		do
		{
		?>
        	<option value="<?php echo $row['id']; ?>"><?php echo $row['vendor_name']; ?></option>
        <?php
	}while($row=mysqli_fetch_assoc($rst));
}

if(func_read_qs("process")=="get_sub_product")      // for local logistics to fetch vendor name when city gets selected
	{
		$prod_level= func_read_qs("level_parent");
		$result="<option value='0'>Select</option>";
		get_rst("select prod_id, prod_name from prod_mast where parent_product = 0 and level_parent= 0$prod_level",$row_p,$rst_p);
		do
		{
			$result = $result."<option value = '".$row_p["prod_id"]."'>".$row_p['prod_name']."</option>";
	}while($row_p=mysqli_fetch_assoc($rst_p));
	echo("<response>$result</response>");
}

if(func_read_qs("process") == "check_subdomain_name"){
	$subdomain_name = func_read_qs("subdomain_name");
	if (preg_match('/^[a-z\d][a-z\d-]{0,240}/i', $subdomain_name) && !preg_match('/([%\$#\*,.=@%&\\s*"()\'!^{}+:";>+_<?])/',$subdomain_name)&& !preg_match('/-$/', $subdomain_name) && !preg_match('/^[0-9]/', $subdomain_name) && !preg_match('/\d$/', $subdomain_name)){
		if(get_rst("select subdomain_name from subdomain_mast where subdomain_name='$subdomain_name'",$row,$rst)){
			$response = "This subdomain already exists. Please provide another one.";
		}else{
			$response = "Available";
		}
	}else{
		$response = "Subdomain can only contains hypen(-) and should not start or end with a number or any special character.";
	}
	echo("<response>$response</response>");
}
?>
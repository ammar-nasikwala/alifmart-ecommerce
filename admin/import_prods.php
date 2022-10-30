<?php
require_once("inc_admin_header.php");

$act = func_read_qs("act");
$sup_id = func_read_qs("sup_id");

$txt_data = "A=Category-1	B=Category-2	C=Category-3	D=Brand	E=Seller	F=SKU	G = Product Name	H = Our Price	I = Offer Price	J = description	K = Image 1	L = Image 2	M = Image 3	N = Image 4	O = Page Title	P = Meta Keywords	Q = Meta description	R=Status";
$txt_data = $txt_data . "   S = Property 1	T = Property 2	U = Property 3	V = Property 4	W = Property 5";

if($act=="1"){
	$fld_arr = array();
	$count_ins=0;
	$count_upd=0;
	$count_failed=0;
	$failed_reason="";
	$msg_failed="";
	
	$txt_data = func_read_qs("txt_data");
	//echo("A".$txt_data."B");
	$line_arr = explode("\n", $txt_data);
	for($l=0;$l<count($line_arr);$l++){
		if($line_arr[$l].""<>""){
			$err = "";
			$f_arr = explode("\t",$line_arr[$l]);
			//echo("[".$line_arr[$l]."]");
			if(count($f_arr)<18){
				$err = "No. of columns are less than 18";		
			}
			
			if($err==""){
				for($f=0;$f<count($f_arr);$f++){
					$f_arr[$f] = trim($f_arr[$f]);
				}
				
				if($f_arr[0].""==""){
					$err = "Category 1 not provided. ";
				}
				if($f_arr[3].""==""){
					$err = $err."Brand not provided. ";
				}
				if($f_arr[4].""==""){
					//$err = $err."Seller not provided. ";
				}
				if($f_arr[5].""==""){
					//$err = $err."SKU not provided. ";
				}				
				if($f_arr[6].""==""){
					$err = $err."Product Name not provided. ";
				}
				if(intval($f_arr[7]."")==""){
					//$err = $err."price not provided. ";
				}
				if(intval($f_arr[17]."")==""){
					$err = $err."Status not provided. ";
				}
				
				if($err==""){
					$level_parent=0;
					for($c=0;$c<2;$c++){
						if($f_arr[$c]."" <> ""){
							//Check if Category exists
							if(get_rst("select level_id from levels where level_name='".$f_arr[$c]."' and level_parent=$level_parent",$row)){
								$level_id = $row["level_id"];
							}else{
								$level_id = create_level($f_arr[$c],$level_parent);
							}
							$level_parent = $level_id;
						}
					}

					//Check if Brand exists
					if(get_rst("select brand_id from brand_mast where brand_name='".$f_arr[3]."'",$row)){
						$brand_id = $row["brand_id"];
					}else{
						$brand_id = create_brand($f_arr[3]);
					}
					$prod_sku = "";
					$prod_sku = generate_sku($f_arr[0], $f_arr[1], $f_arr[3]);
					if(is_dir("../prod_desc/".$prod_sku)==false){
					mkdir("../prod_desc/".$prod_sku, 0700);
					}
					/*
					//Check if Seller exists
					if(get_rst("select sup_id from sup_mast where sup_company='".$f_arr[4]."'",$row)){
						$sup_id = $row["sup_id"];
					}else{
						$err = $err."Seller ".$f_arr[4]." not Found. ";
					}
					*/
			
					if($err == ""){
						$o=0;
						$fld_arr["prod_stockno"] = $prod_sku;
						$fld_arr["level_parent"] = $level_id;	$o=$o+3;
						$fld_arr["prod_brand_id"] = $brand_id;	$o++;
						$fld_arr["prod_sup_id"] = $sup_id;		$o++;
						$fld_arr["prod_stockno"] = $f_arr[$o];	$o++;
						$fld_arr["prod_name"] = $f_arr[$o];		$o++;
						$fld_arr["prod_ourprice"] = intval($f_arr[$o]);	$o++;
						$fld_arr["prod_offerprice"] = intval($f_arr[$o]);	$o++;
						$fld_arr["prod_briefdesc"] = $f_arr[$o];	$o++;
					/*	$fld_arr["prod_large1"] = $f_arr[$o];	$o++;
						$fld_arr["prod_large2"] = $f_arr[$o];	$o++;
						$fld_arr["prod_large3"] = $f_arr[$o];	$o++;
						$fld_arr["prod_large4"] = $f_arr[$o];	$o++;
					*/
						for($img=1;$img<=4;$img++){
							//if($fld_arr["prod_large".$img].""<>""){
							if($f_arr[$o]."" <> ""){
								$large_path = $_SERVER['DOCUMENT_ROOT']."/images/products/".$f_arr[$o];
								if(file_exists($large_path)){
									//$thumb_path = "../images/products/thumb/".$fld_arr["prod_large".$img];
									try{
										$imageFileType = strtolower(pathinfo($large_path,PATHINFO_EXTENSION));

										$fld_arr["prod_large".$img] = img_resize_db($large_path, 1200, 500, $imageFileType);
										$fld_arr["prod_thumb".$img] = img_resize_db($large_path, 180, 200, $imageFileType);
										//remove_file($large_path);
									}
									catch(Exception $e){}
								}
							}else{
								$fld_arr["prod_large".$img] = "";
								$fld_arr["prod_thumb".$img] = "";
							}
							$o++;
						}
						$fld_arr["page_title"] = $f_arr[$o];	$o++;
						$fld_arr["meta_key"] = $f_arr[$o];		$o++;
						$fld_arr["meta_desc"] = $f_arr[$o];		$o++;
						$fld_arr["prod_status"] = intval($f_arr[$o]);		$o++;

						
						//if(get_rst("select prod_id from prod_mast where prod_stockno='".$fld_arr["prod_stockno"]."' and prod_sup_id=".$fld_arr["prod_sup_id"],$row)){
						if(get_rst("select prod_id from prod_mast where prod_stockno='".$fld_arr["prod_stockno"]."'",$row)){
							$prod_id=$row["prod_id"];
							$qry = func_update_qry("prod_mast",$fld_arr," where prod_id=$prod_id");
							mysqli_query($con,$qry);
							//echo($qry);
							if(mysqli_errno($con)==0){
								$count_upd++;
							}
						}else{
							$qry = func_insert_qry("prod_mast",$fld_arr);
							mysqli_query($con,$qry);
							if(mysqli_errno()==0){
								$prod_id=mysqli_insert_id($con);
								$count_ins++;
							}
						}
						
						execute_qry("delete from prod_props where prod_id=$prod_id");
						for($i_p=18;$i_p<18+5;$i_p++){
							//echo("[".$i_p."|".$f_arr[$i_p]."]");
							if($f_arr[$i_p]<>""){
						 		$prop_arr = explode("=",$f_arr[$i_p]);
								if(count($prop_arr)==2){
									$prop_name = trim($prop_arr[0]);
									
									$prop_fld_arr = array();
									
									$prop_fld_arr["prop_name"] = $prop_name;
									
									if(get_rst("select prop_id from prop_mast where prop_name='$prop_name'",$row)){
										$prop_id = $row["prop_id"];
										$qry = func_update_qry("prop_mast",$fld_arr," where prop_id=$prop_id");
									}else{
										$prop_fld_arr["prop_status"] = "1";
										$qry = func_insert_qry("prop_mast",$prop_fld_arr);
										$prop_id = execute_qry($qry);
									}
									
									$opt_arr = explode(",",$prop_arr[1]);

									for($i_op=0;$i_op<count($opt_arr);$i_op++){
										$opt_value = trim($opt_arr[$i_op]);
										
										$fld_p_arr = array();
										$fld_p_arr["prod_id"] = $prod_id;
										$fld_p_arr["prop_id"] = $prop_id;
										$fld_p_arr["opt_value"] = $opt_value;
										$qry = func_insert_qry("prod_props",$fld_p_arr);
										
										execute_qry($qry);
										
										if(!get_rst("select prop_id from prop_options where prop_id=".$prop_id." and opt_value='$opt_value'")){
											$fld_pm_arr = array();
											
											$fld_pm_arr["prop_id"] = $prop_id;
											$fld_pm_arr["opt_value"] = $opt_value;

											$qry = func_insert_qry("prop_options",$fld_pm_arr);
											execute_qry($qry);
											
										}
									}
								}
							}
						
						}

					}
				}
			//echo($qry."<br>");

			}
			if($err<>""){
				$count_failed++;
				//$line_err = "ERROR on Row no. $l: $err"; 
				$msg_failed = $msg_failed."ERROR on Row no.". ($l+1).": $err\n"; 
			}else{
				//$line_err = "";
			}			
		}

	}
	
	$msg = "";	//Import Summary\n";
	$msg = $msg.$count_ins." new records created\n";
	$msg = $msg.$count_upd." existing records updated\n";
	
	if($count_failed>0){
	//	$msg = $msg.$count_failed." failed due to seller not matching on row nos.".$failed_reason;
		$msg = $msg.$count_failed." failed "."\n".$msg_failed;
	}
}

?>

<table border="0" width="100%">
	<tr>
		<td colspan="1"><h2>Import Products</h2></td>
	</tr>
	
	<?php if($msg<>""){?>
	<tr>
		<td align="center"><textarea style="width:1000px;height:200px;"><?=$msg?></textarea></td>
	</tr>
	<?php }?>
	
	<tr>
		<td align="center">
		<form name="frm_list" method="post" action="import_prods.php">
			
			<input type="hidden" name="act" value="1">
			<!--
			Select Seller
			<select name="sup_id">
				<?=create_cbo("select sup_id,sup_company from sup_mast",$sup_id);?>
			</select>
			-->
			&nbsp; &nbsp; &nbsp; &nbsp;
			Please make sure the product images have been uploaded under <b>/images/products/</b> folder and set names to image columns for automatic resizing and thumbnail creation. <br>
			<br>then paste tab delimited product details here in the following sequence of colums.
			<br><br>
			<textarea rows="100" cols="100" style="height:400px;width:1000px;border:solid 1px #000000;" wrap="off" name="txt_data" ><?=$txt_data?></textarea>
			<!--
			<br><input type="checkbox">Tick here if pasted data contains row header
			-->
			<br><input type="submit" class="btn btn-warning" name="btn_filter" value=" Start Import " style="margin-top:5px;" >
		</form>
		<br>
		</td>
	</tr>

</table>

<?php
require_once("inc_admin_footer.php");

function create_level($level_name,$level_parent){
	global $con;
	$qry = "insert into levels (level_name,level_parent,level_status) values ('$level_name',$level_parent,1)";
	echo($qry);
	mysqli_query($con,$qry);
	return mysqli_insert_id($con);
}

function create_brand($brand_name){
	global $con;
	$qry = "insert into brand_mast (brand_name,brand_status) values ('$brand_name',1)";
	mysqli_query($con, $qry);
	return mysqli_insert_id($con);
}

?>

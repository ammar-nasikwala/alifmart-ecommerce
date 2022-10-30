<?php
require_once("inc_admin_header.php");

$act = func_read_qs("act");
$sup_id = func_read_qs("sup_id");

$txt_data="A=Seller Company	B=SKU	C=Price	D=Offer Price	E=Discount %	F=Active	G=Out of Stock";

if($act=="1"){
	$fld_arr = array();
	$prod_ids = "";
	$count_ins=0;
	$count_upd=0;
	$count_failed=0;
	$failed_reason="";
	$msg_failed="";
	
	$txt_data = func_read_qs("txt_data");

	$line_arr = explode("\n", $txt_data);
	for($l=0;$l<count($line_arr);$l++){
		if($line_arr[$l].""<>""){
			$err = "";
			$f_arr = explode("\t",$line_arr[$l]);
			//echo("[".$line_arr[$l]."]");
			if(count($f_arr)<7){
				$err = "No. of columns are less than 7";		
			}
			
			if($err==""){
				for($f=0;$f<count($f_arr);$f++){
					$f_arr[$f] = trim($f_arr[$f]);
				}
				
				if($f_arr[0].""==""){
					$err = "Seller Name is not provided. ";
				}
				if($f_arr[1].""==""){
					$err = $err."SKU is not provided. ";
				}
				if($f_arr[2].""==""){
					$err = $err."Basic Price is not provided. ";
				}
				
				if($err==""){
					
					//Check if Seller exists
					if(get_rst("select sup_id from sup_mast where sup_company='".$f_arr[0]."'",$row)){
						$sup_id = $row["sup_id"];
					}else{
						$err = $err."Seller ".$f_arr[0]." not Found. ";
					}
					
					//Check if SKU exists
					if(get_rst("select prod_id from prod_mast where prod_stockno='".$f_arr[1]."'",$row)){
						$prod_id = $row["prod_id"];
					}else{
						$err = $err."SKU ".$f_arr[1]." not Found. ";
					}					
			
					if($err == ""){
											
						$o=0;
						$fld_arr["sup_id"] = $sup_id;	$o++;
						$fld_arr["prod_id"] = $prod_id;	$o++;
						$fld_arr["price"] = $f_arr[$o];		$o++;
						$fld_arr["offer_price"] = $f_arr[$o];	$o++;
						$fld_arr["offer_disc"] = $f_arr[$o];		$o++;
						$fld_arr["sup_status"] = intval($f_arr[$o]);	$o++;
						$fld_arr["out_of_stock"] = intval($f_arr[$o]);	$o++;
						
						$offer_disc_price = intval($fld_arr["price"]) - (intval($fld_arr["price"]) * intval($fld_arr["offer_disc"])/100);
						if(intval($fld_arr["offer_price"])>0 and intval($fld_arr["offer_price"])<intval($offer_disc_price)){
							$final_price=$fld_arr["offer_price"];
						}elseif(intval($fld_arr["offer_disc"])>0){
							$final_price=$offer_disc_price;
						}else{
							$final_price = $fld_arr["price"];
						}
						
						$fld_arr["final_price"] = $final_price;	$o++;
						
						if(get_rst("select id from prod_sup where prod_id='".$fld_arr["prod_id"]."' and sup_id=".$fld_arr["sup_id"],$row)){
							$qry = func_update_qry("prod_sup",$fld_arr," where id=".$row["id"]);
							mysqli_query($qry);
							//echo($qry);
							if(mysqli_errno()==0){
								$count_upd++;
							}
						}else{
							$qry = func_insert_qry("prod_sup",$fld_arr);
							mysqli_query($qry);
							if(mysqli_errno()==0){
								$count_ins++;
							}
						}
												
						if(!strpos(",".$prod_ids.",",$prod_id)){
							$prod_ids = $prod_ids.",".$prod_id;
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
	

	$prod_arr = explode(",",$prod_ids);
	for($i=0;$i<count($prod_arr);$i++){
		if($prod_arr[$i].""<>""){
			if(get_rst("SELECT * FROM prod_sup WHERE prod_id = ".$prod_arr[$i]." ORDER BY final_price LIMIT 0 , 1",$row)){
				$prod_price = $row["price"];
				$prod_offer_price = $row["offer_price"];
				if($prod_offer_price ==""){	$prod_offer_price="\N";}
				$prod_effective_price = $row["final_price"];
				execute_qry("update prod_mast set prod_ourprice=$prod_price,prod_offerprice=$prod_offer_price,prod_effective_price=$prod_effective_price where prod_id=".$prod_arr[$i]);
			}
			/*
			$prod_price=999999;
			$prod_offer_price=999999;
			$prod_final_price=999999;
			$prod_final_offer_price="";			
			get_rst("select * from prod_sup where prod_id=".$prod_arr[$i],$row,$rst);
			do{

				$price = $row["price"];
				$offer_price = $row["offer_price"]."";
				$offer_disc = $row["offer_disc"];
				$final_price = "";
				
				if(intval($price)>0){
					$offer_disc_price = $price - (intval($price) * intval($offer_disc)/100);
					if(intval($offer_price)>0 and intval($offer_price)<intval($offer_disc_price)){
						$final_price=$offer_price;
						$prod_final_offer_price=$offer_price;
					}elseif(intval($offer_disc)>0){
						$final_price=$offer_disc_price;
						$prod_final_offer_price=$offer_disc_price;
					}else{
						$final_price = $price;
					}
					
					if($row["sup_status"]=="1" AND $row["sup_status"].""<>"1"){
						if(intval($prod_final_price)>intval($final_price)){

							$prod_final_price = $final_price;
							$prod_price = $price;
							$prod_offer_price = $prod_final_offer_price;
						}					
					}
				}
			
			}while($row = mysqli_fetch_assoc($rst));

			$prod_effective_price = $prod_price;
			if($prod_offer_price>0 and $prod_price<$prod_offer_price){
				//$prod_effective_price = $prod_price;
			//}else{
				$prod_effective_price = $prod_offer_price;
			}
			
			if($prod_offer_price >= 999999){	$prod_offer_price="";}
			if($prod_offer_price ==""){	$prod_offer_price="\N";}
			if($prod_price==999999){ $prod_price=0;};
			
			execute_qry("update prod_mast set prod_ourprice=$prod_price,prod_offerprice=$prod_offer_price,prod_effective_price=$prod_effective_price where prod_id=".$prod_arr[$i]);
			*/
		}
	}
	
	$msg = "";	//Import Summary\n";
	$msg = $msg.$count_ins." new records created\n";
	$msg = $msg.$count_upd." existing records updated\n";
	
	if($count_failed>0){
		$msg = $msg.$count_failed." failed "."\n".$msg_failed;
	}
}

?>

<table border="0" width="100%">
	<tr>
		<td colspan="1"><h2>Import Products Prices</h2></td>
	</tr>
	
	<?php if($msg<>""){?>
	<tr>
		<td align="center"><textarea style="width:1000px;height:200px;"><?=$msg?></textarea></td>
	</tr>
	<?php }?>
	
	<tr>
		<td align="center">
		<form name="frm_list" method="post" action="import_prices.php">
			
			<input type="hidden" name="act" value="1">
			<!--
			Select Seller
			<select name="sup_id">
				<?=create_cbo("select sup_id,sup_company from sup_mast",$sup_id);?>
			</select>
			-->
			&nbsp; &nbsp; &nbsp; &nbsp;
			
			<br>Paste tab delimited prices here in the following sequence of colums.
			<br><br>
			<textarea rows="100" cols="100" style="height:400px;width:1000px;border:solid 1px #000000;" wrap="off" name="txt_data" ><?=$txt_data?></textarea>
			<!--
			<br><input type="checkbox">Tick here if pasted data contains row header
			-->
			<br><input type="submit" class="btn btn-warning" name="btn_filter" value=" Start Import " style="margin-top:5px" >
		</form>
		<br>
		</td>
	</tr>

</table>

<?php
require_once("inc_admin_footer.php");

function create_level($level_name,$level_parent){
	$qry = "insert into levels (level_name,level_parent,level_status) values ('$level_name',$level_parent,1)";
	echo($qry);
	mysqli_query($qry);
	return mysqli_insert_id();
}

function create_brand($brand_name){
	$qry = "insert into brand_mast (brand_name,brand_status) values ('$brand_name',1)";
	mysqli_query($qry);
	return mysqli_insert_id();
}

?>

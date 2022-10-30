<script language ="javascript">

function GetXmlHttpObject()
{ 
	var objXMLHttp=null
	if (window.XMLHttpRequest)
	{
		objXMLHttp=new XMLHttpRequest()
	}
	else if (window.ActiveXObject)
	{
		objXMLHttp=new ActiveXObject("Microsoft.XMLHTTP")
	}
	return objXMLHttp
}
		
function funcEmail(t)
{			
	var t_email;
	t_email=document.frmEmail.txtEmail.value;	
	xmlHttp=GetXmlHttpObject()
	if (xmlHttp==null)
	{
		alert ("Browser does not support HTTP Request")
		return;
	}
	
	var url="subscribe.php"
	url=url+"?subsc="+t+ "&txtEmail=" + t_email			
	xmlHttp.open("GET",url,false)
	xmlHttp.send(null)
	
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
	{	
		document.getElementById('MSG').innerHTML = '<p><span class="red-text" id="MSG">' + xmlHttp.responseText + '</span></p>'
	} //if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
}

		/* toggle category images*/	
$(function($) {
	$(".toggle-me2").mouseover(function() { 
		var $img = $(this).find('img')
		var src = $img.attr('src', $img.attr('src').replace(/(grey.png)$/, '') + 'copper.png')
		})
		.mouseout(function() { 
		var $img = $(this).find('img')
		var src = $img.attr("src").replace("copper", "grey");
			$img.attr("src", src);
	});
});	

function submit_form(){
	document.frm_list_2.submit();
}
</script>


<div id="leftcolumn">
<div class="left-panel">

	<form name="frm_list_2" method="post" action="<?=$url_root?>prod_list.php">
	 	<!-- **************************************************  left-nav-1 ***********************************-->
		 <div id="expand-button-blue" onmouseout="javascript: hide_cats();">Browse by Category</div>
         <div id="left-nav-1" >

		<ul>
		<?php
		$r_level_id = func_read_qs("level_id");

		$qry = "SELECT level_id, level_name FROM levels l1 WHERE level_parent =0 AND (
				exists  ( SELECT level_parent FROM prod_mast WHERE prod_status =1 AND level_parent=l1.level_id )
				OR exists (SELECT level_parent FROM levels l2 WHERE level_parent=l1.level_id and exists(
				SELECT level_parent FROM prod_mast WHERE prod_status =1 AND level_parent=l2.level_id)))
				ORDER BY level_name LIMIT 10";

		
		if(get_rst($qry, $row, $rs_cats)){
			do{
				$level_id = $row["level_id"];
				
				$level_sel = "";$level_class="";
				if(stripos(",".$chk_level_ids."," , ",".$level_id.",").""<>""){
					$level_sel = " checked ";
					$level_class="style='background-color:#eb9316;color:#FFFFFF;'";
				}
				$level_urlname = preg_replace('/[^a-zA-Z0-9]/',"-",$row["level_name"]);
				?>
				<li>
				<a class="boxed-shadow" href="<?=$url_root?>prod_list.php?category=<?=$level_urlname?>" <?=$level_class?> title="<?=$row["level_name"]?>" onmousemove="javascript: show_cats('<?=$row["level_id"]?>',this)" onmouseout="javascript: hide_cats();">
				
				<input name="chk_level_ids[]" type="checkbox" onclick="javascript: submit_form();" value="<?=$row["level_id"]?>" <?=$level_sel?>>
				<?=limit_chars($row["level_name"],18)?>
				<?php
				$sql_count = "select count(*) as prod_count from prod_mast p where prod_status=1 and parent_product=0 and (level_parent = $level_id ";
				$sql_count = $sql_count . "or exists (select level_id from levels where level_parent=$level_id and level_id=p.level_parent)) ";

				$rst_count = mysqli_query($con, $sql_count);
				$row_count = mysqli_fetch_assoc($rst_count);
				
				echo("(".$row_count["prod_count"].")");
				?>
				</a>				
				</li>
			
				<?php
				$child_sel="";
				$qry = "select level_id,level_name from levels where level_parent=$level_id and level_status=1";
				if(get_rst($qry, $row_child, $rst_child)){
					do{
						if($row_child["level_id"]==$r_level_id){
							$child_sel="1";
						}
					}while($row_child = mysqli_fetch_assoc($rst_child));
				}
				
				if($r_level_id == $row["level_id"] OR $child_sel=="1"){
					$qry = "select level_id,level_name from levels where level_parent=$level_id and level_status=1";
					if(get_rst($qry, $row_child, $rst_child)){
						do{
							
							$level_id = $row_child["level_id"];

							$level_sel = "";
							$level_class="";
							if(stripos(",".$chk_level_ids."," , ",".$level_id.",").""<>""){
								$level_sel = " checked ";
								$level_class="style='background-color:#eb9316 ;color:#FFFFFF;'";
							}							
						}while($row_child = mysqli_fetch_assoc($rst_child));
					}
				}
			}while($row = mysqli_fetch_assoc($rs_cats));
		}
		?>
		<li><a class="boxed-shadow" href="#" data-toggle="modal" data-target="#myModal1"><center>View All..</center></a></li>
	</ul>	
	</div>

	<?php
	$qry = "select brand_id, brand_name from brand_mast b where brand_status=1 and exists (
			select distinct prod_brand_id from prod_mast where prod_status=1 and prod_brand_id=b.brand_id)
			order by brand_name LIMIT 10";
	if(get_rst($qry, $row, $rst_b)){?>
		<div id="expand-button-blue">Browse by Brand</div>
		<div id="left-nav-1">
			<ul>
				<?php do{ 
					$brand_urlname = preg_replace('/[^a-zA-Z0-9]/',"-",$row["brand_name"]);?>
					<li><a class="boxed-shadow" href="<?=$url_root?>prod_list.php?brand=<?=$brand_urlname?>">

					<?php
					$brand_sel = "";
					if(stripos(",".$chk_brand_ids."," , ",".$row["brand_id"].",").""<>""){
						$brand_sel = " checked ";
					}
					?>

					<input name="chk_brand_ids[]" type="checkbox" onclick="javascript: submit_form();" value="<?=$row["brand_id"]?>" <?=$brand_sel?> >

					<?=limit_chars($row["brand_name"],18)?>  
					<?php
					$sql_count = "select count(*) as prod_count from prod_mast p where prod_status=1 and prod_brand_id = ". $row["brand_id"];

					$rst_count = mysqli_query($con, $sql_count);
					$row_count = mysqli_fetch_assoc($rst_count);
					
					echo("(".$row_count["prod_count"].")");
					?>
					</a></li>
					<?php
				}while($row = mysqli_fetch_assoc($rst_b));
				?>
				<li>
				<a class="boxed-shadow" href="#" data-toggle="modal" data-target="#myModal2"><center>View All..</center></a></li>
			</ul>
		</div>
	<?php } ?>

	<input type="hidden" name="page" id="page" value="<?=$page?>">

</form>

<br>

     </div>
	</div>
	
	<!--Popups for 1st Level-->
	<?php get_rst("select level_id,level_name from levels where level_parent=0 and level_status=1 order by level_name",$row,$rst);
	do{
	
	$row_no = 0;
	
	if(get_rst("select level_id,level_name from levels where level_parent=0".$row["level_id"]." and level_status=1",$row2,$rst2)){
	$sql_cnt = "select count(*) as prod_count from prod_mast p where prod_status=1 and (level_parent =0".$row["level_id"];
	$sql_cnt = $sql_cnt. " or level_parent in (select level_id from levels where level_parent=".$row["level_id"].")) ";
	get_rst($sql_cnt , $row_cnt);
	$level_urlname = preg_replace('/[^a-zA-Z0-9]/',"-",$row["level_name"]);
	?>
	<div class="left_pop" id="left_<?=$row["level_id"]?>" style="display:none;"
		onmousemove="javascript: show_cats('<?=$row["level_id"]?>',this)" onmouseout="javascript: hide_cats();">
		
		<table class="pop_l1_head">
			<tr><td>
				<a href="<?=$url_root?>prod_list.php?category=<?=$level_urlname?>"><?=$row["level_name"]; echo "(".$row_cnt["prod_count"].")"?></a>
			</td></tr>
		</table>

		<table border="1" width="100%" >
			<tr>
			<td>
			<table class="show_row" cellspacing="0" cellpadding="0" border="1">
			<?php do{
				if($row_no>0 AND $row_no % 30==0){
					?>
					
					</td>
					<td valign="top" width="25%">
						</table>
						<table class="show_row" border="1">
					<?php
				}
				get_rst("select count(*) as prod_count from prod_mast where prod_status=1 and level_parent =0".$row2["level_id"] , $row_cnt);
				$level2_urlname = preg_replace('/[^a-zA-Z0-9]/',"-",$row2["level_name"]);
				?>
				<tr><td class="pop_l2">
					<a href="<?=$url_root?>prod_list.php?category=<?=$level2_urlname?>"><span ><?=$row2["level_name"];  echo "(".$row_cnt["prod_count"].")"?></span></a>
				</td></tr>
				
				<?php 
			}while($row2 = mysqli_fetch_array($rst2));?>
			</table>
			
			</td>
			</tr>
		</table>
	</div>
	<?php }
	}while($row = mysqli_fetch_array($rst));?>
		
	<script>
		var prev_div = "";
		var prev_src = "";
		var prev_color = "";
		var TO;
		function show_cats(id,src_obj){
			//alert(id);
			clearTimeout(TO);
			if(prev_div != ""){
				//alert(prev_div)
				prev_div.style.display = "none"
				//prev_src.style.backgroundColor=prev_color;
			}
			obj = document.getElementById("left_" + id)
			if(obj != src_obj){
				obj.style.left = parseInt(document.getElementById("left-nav-1").offsetLeft) - 2 + parseInt(document.getElementById("left-nav-1").offsetWidth) + "px"
				obj.style.top = parseInt(src_obj.offsetTop) + "px"
				obj_p = src_obj
				prev_color = obj_p.style.backgroundColor
				prev_src = src_obj
			}
			
			obj.style.display=""
			if(parseInt(obj.offsetTop) + parseInt(obj.offsetHeight) - parseInt(getScrollTop()) > parseInt(window.innerHeight)){
				obj.style.top = parseInt(window.innerHeight) - parseInt(obj.offsetHeight)-10 + parseInt(getScrollTop()) + "px"
			}

			prev_div = obj
					
		}
		
		function hide_cats(){
			clearTimeout(TO);
			if(prev_div != ""){
				TO=setTimeout('prev_div.style.display=\'none\';',500);
				//prev_src.style.backgroundColor=prev_color;
			}			
		}
	</script>

 <div id="myModal1" class="modal fade" role="dialog">
	<div class="modal-dialog" style="width:900px;">
    <!-- Modal content-->
		<div class="modal-content" style="height:auto">
			<div class="modal-body">
			<img src="<?=$url_root?>images/close_header.gif" width="12px" height="12px" alt="close" align="right" data-dismiss="modal"/>
				<center><h1 class="modal-heading">All Categories</h1></center>
				<div id="table_layout">
					<?php
						$r_level_id = func_read_qs("level_id");
						$sql_level_2 = "";
						$qry = "select level_id,level_name from levels where level_parent=0 and level_id>0 and level_status=1 order by level_name";
						if(get_rst($qry, $row, $rs_cats)){
						do{
							if(get_rst("select prod_id from prod_mast where prod_status=1 and (level_parent=0".$row["level_id"]." or level_parent in (select level_id from levels where level_parent=0".$row["level_id"]."))")){
								$level_id = $row["level_id"];
								$level_urlname = preg_replace('/[^a-zA-Z0-9]/',"-",$row["level_name"]);
								$level_sel = "";$level_class="";
								if(stripos(",".$chk_level_ids."," , ",".$level_id.",").""<>""){
									$level_sel = " checked ";
									$level_class="style='background-color:#eb9316;color:#FFFFFF;'";
								}
								?>
								<div class="display_box toggle-me2">
								<a class="display-box" href="<?=$url_root?>prod_list.php?category=<?=$level_urlname?>" title="<?=$row["level_name"]?>">
								<span><img class="cat-img" width="20" height="20" src="<?=$url_root?>images/levels/<?=$row["level_id"];?>/grey.png"/></span>
								<?=limit_chars($row["level_name"],18)?>
								</a>
											
								<?php
									$result=mysqli_query($con,"SELECT level_id,level_name from levels where level_parent=$level_id");
									$lastcat = 0;?>
									
									<ul><li>
									<?php
									while ($row1 = mysqli_fetch_array($result)){
									if(get_rst("select prod_id from prod_mast where prod_status=1 and level_parent=0".$row1['level_id'])){
										$level1_urlname =  preg_replace('/[^a-zA-Z0-9]/',"-",$row1["level_name"]);
										if($lastcat != $row1['level_id']){
											$lastcat = $row1['level_id'];
											?>
												<a style="font-size:14px;" href="<?=$url_root?>prod_list.php?category=<?=$level1_urlname?>"><?=$row1["level_name"]?></a>	
										<?php }
										}
									}?>
									</li></ul>
									</div>
								<?php
								}
						}while($row = mysqli_fetch_assoc($rs_cats));
					}
					?>	
				</div>
			</div>
		</div>
	</div>
</div>
<!--Modal for brand-->
<div id="myModal2" class="modal fade" role="dialog">
	<div class="modal-dialog" style="width:900px;">
    <!-- Modal content-->
		<div class="modal-content" style="height:auto">
			<div class="modal-body">
			<img src="<?=$url_root?>images/close_header.gif" width="12px" height="12px" alt="close" align="right" data-dismiss="modal"/>
				<center><h1 class="modal-heading">All Brands</h1></center>
				<div id="table_layout">
					<?php
							$qry = "select brand_id, brand_name from brand_mast where brand_status=1 order by brand_name";
							if(get_rst($qry, $row, $rst_b)){?>
								<?php do{ 
								if(get_rst("select prod_id from prod_mast where prod_status=1 and prod_brand_id=0".$row["brand_id"])){
									$brand_urlname = preg_replace('/[^a-zA-Z0-9]/',"-",$row["brand_name"]);?>
									<div class="display_box">
									<a class="display-box" href="<?=$url_root?>prod_list.php?brand=<?=$brand_urlname?>">

									<?php
										$brand_sel = "";
										if(stripos(",".$chk_brand_ids."," , ",".$row["brand_id"].",").""<>""){
										$brand_sel = " checked ";
									}
									?>
									<?=limit_chars($row["brand_name"],18)?>  
									</a><br></div>
									<?php
									}
								}while($row = mysqli_fetch_assoc($rst_b));
							}
						?>
				</div>
			</div>
		</div>
	</div>
</div>
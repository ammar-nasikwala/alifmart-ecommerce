<?php

require("inc_init.php");
$vid = func_read_qs("id");
$level_id = func_read_qs("level_id");

$query  = "Select * from banner_mast where banner_status=1 order by banner_sort";
$res    = mysqli_query($con,$query);
$count  =   mysqli_num_rows($res);
$slides='';
$Indicators='';
$counter=0;
 
while($row=mysqli_fetch_array($res))
{
	$url = $row["banner_target_url"];
	$image = show_img($row["banner_img"]);
	if($counter == 0)
	{
		$Indicators .='<li data-target="#carousel" data-slide-to="'.$counter.'" class="active"></li>';
		$slides .= '<div class="item active">
		<a href="'.$url.'" target="_blank">
		<img alt="Banner" src="'.$image.'" />
		</a>
	    </div>';
	}
	else
	{
		$Indicators .='<li data-target="#carousel" data-slide-to="'.$counter.'"></li>';
		$slides .= '<div class="item">
		<a href="'.$url.'" target="_blank">
		<img alt="Banner" src="'.$image.'" />
		</a>
	    </div>';
	}
	$counter++;
}
?>


<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
<meta name="msvalidate.01" content="AD81FD49EFC849902D7EA8F2EB0BC2CF" />
<title>
Company-Name - Online marketplace for Industrial Tools and Hardware
</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta name="DESCRIPTION" content="Online Marketplace for Industrial Tools & Hardware, 
Shop for Industrial Safety Equipments, Adhesives, Abrasives, Paints, Lubricants & more"/>
<meta name="KEYWORDS" content="Industrial hardware and tools, Make in India with Company-Name, 
Manufacture in India with Company-Name, industrial tools online, industrial supply, Industrial hardware,
industrial tools, Power Tools , Industrial shopping, Sanitary ware, Industrial machinery, Industrial & House hold Safety Equipment, Company-Name"/>
<meta property="og:url" content="http://www.Company-Name.com/" />
<meta property="og:image" content="http://www.Company-Name.com/images/logo-app-with-black-text.png"/>  
<meta property="og:title" content="Company-Name - Industrial Shopping Redefined - Online marketplace for Industrial hardware, Tools and Supplies"/>  
<meta property="og:description" content="Online Marketplace for Industrial Hardware, Tools and Supplies, Industrial shopping redefined"/>
<link href="styles/styles.css" rel="stylesheet" type="text/css" />
</head>
<body>

<!-- Mobile App download Modal -->

<div id="mobile-app-modal" class="modal mobileShow" role="dialog">
  <div class="modal-dialog" style="width:980px; height: 100%;">
    <!-- Modal content-->
    <div class="modal-content" style="height: 100%;">
      
	  <div id="mobile-app-body" class="modal-body" style="height: 100%;">
		<img src="<?=$url_root?>images/close_header.gif" width="80px" height="80px" alt="close" align="right" onclick="javascript: dismissModal();"/>		
		<table class="center-tab">
			<tr>
				<td align="center"><p class="color-amber" style="font-size: 60px;">Download Company-Name App</p></td>
			</tr>
			<tr>
				<td align="center"><a href="https://play.google.com/store/apps/details?id=com.Company-Name.app"><img alt="Google Play" src="<?=$url_root?>images/Googleplay.png" style="margin-top:60px;" alt="" title="Android App" width="450" height="120"/></a></td>
			</tr>
		</table>
	  </div>
    </div>
  </div>
</div>
<style>
#mobile-app-body{
	background-image:  url('images/ic_newsplash.png');
	background-repeat: no-repeat;
	background-size: 100% 100%;
	z-index = 9999;
}

	.mobileShow { display: none;}
   /* Smartphone Portrait and Landscape */
   @media only screen
   and (min-device-width : 320px)
   and (max-device-width : 480px){ .mobileShow { display: block;}
   }
   
   /* Smartphone Portrait and Landscape */
   @media only screen
   and (min-device-width : 320px)
   and (max-device-width : 480px){  #login-modal { display: none; z-index = 999;}
   }
   
</style>

<div id="gif_show"></div>

<?php
require("header.php");
?>
<marquee><div style="color:#eb9316; font-size:16px;">
	Free shipping for all Products above <div class="rupee-sign" style="color:#eb9316">&#8377; </div>2000.
	<a href="#" data-toggle="modal" data-target="#offer_modal" class="offer"> Conditions Apply*</a>
</div></marquee>
<div id="offer_modal" class="modal fade" role="dialog">
	<div class="modal-dialog" style="width:600px;">
		<!-- Modal content-->
		<div class="modal-content" style="height: auto;">
			<div class="modal-header" style="padding-bottom:0px;">
				<div class="center"><h2 class="color-amber"><b>Company-Name Offers</b></h2></div>
			</div>
			<div class="modal-body" style="padding-top:5px;">
				<p style="font-size:16px;">Free shipping for all Products above Rs.2000 whose <span style="color:#eb9316"><b>weight</b></span> is less than 1 Kg.
			</div>
		</div>
	</div>
</div>
<div id="contentwrapper">
	<div class="center-panel">
		<div id="hometopbanner">
		   <div class="container" style="width: 966px;">
				<div id="carousel" class="carousel slide" data-ride="carousel" data-interval="5000">
				<!-- Indicators -->
					<ol class="carousel-indicators glyf" style="padding-bottom:15px;">
						<?php echo $Indicators; ?>
					</ol>
 
				<!-- Wrapper for slides -->
					<div class="carousel-inner">
						<?php echo $slides; ?>  
					</div>
 
				<!-- Controls -->
					<a class="left carousel-control barrowimage" href="#carousel" data-slide="prev">
						<span class="glyphicon glyphicon-chevron-left glyf barrowmarginl"></span>
					</a>
					<a class="right carousel-control barrowimage" href="#carousel" data-slide="next">
						<span class="glyphicon glyphicon-chevron-right glyf barrowmarginr"></span>
					</a>
				</div>
			</div>
		</div>
	</div>
	<br>
	
	<div class="center-panel">
		<?php
		$qry = "select prod_id, prod_advrt, prod_name,prod_thumb1,level_parent,prod_briefdesc,prod_free_text,prod_offerprice,prod_ourprice from prod_mast where prod_status=1 and prod_featured=1 LIMIT 5";
		if(get_rst($qry,$row, $rst)){ ?>
			<table width="100%"  border="0" align="center">
			  <tr>
				<td align="left"><p><span class="featured">Featured Items</span></p></td>
				<td align="right"><a class="theme-blue" href="prod_list.php?prod_frd=1">Show all featured products</a></td>
			  </tr>
			</table>
			<div id=""><!-- featured items -->
			<table width="100%"  border="0" align="center" class="list">
				<tr>				
				<?php
					do{
						get_rst("select level_name from levels where level_id='".$row["level_parent"]."'",$row_level);
						$url_recentlevel = preg_replace('/[^a-zA-Z0-9]/',"-",$row_level["level_name"]);
						$product_urlname = preg_replace('/[^a-zA-Z0-9]/',"-",$row["prod_name"]);
						$url = "prod_details.php?product=".$product_urlname."&category=".$url_recentlevel;
						get_price($row,$price,$our_price,$disc_per);
						?>
						<td>
							<table>	
								<tr>
								<td align="center"><a style="font-size: 1.2em;" href="<?=$url?>" title="<?=$row["prod_name"]?>">
								<?php
								if(strlen($row["prod_name"]) > 20){
								echo (substr($row["prod_name"], 0, 20)."...");
								}else{
									echo $row["prod_name"];
								}
								?></a></td>
								</tr>
								<tr>
								  <td align="center" height="200px" width="180px"><a href="<?=$url?>"><img src="<?php echo show_img($row["prod_thumb1"])?>" alt="<?php echo display($row["prod_name"])?>" border="0" /></a></td>
								</tr>
								<?php if(intval($row["prod_advrt"]) == 1){ ?>
								<tr>
									<td align="center" valign="bottom">
										<p style="text-align: center; padding-bottom: 10px; padding-top: 10px;"><b><font color="green" size="2px">Ask for a Quote</font></b></p>
									</td>
								</tr>	
								<?php }else{ ?>
								<tr>
								  <td align="center"><?php 
									if($price==0){
										$price = "N/A";
									}else{
										$price = formatnumber($price, 2);
									}
									if(intval($disc_per) <> 0){?>
									<div class="rupee-sign">Offer &#8377; </div><span class="final_price"><?=$price?></span>
									<br><div class="rupee-sign">&#8377; </div> <span class="our_price"><?=formatnumber($our_price)?></span>&nbsp;&nbsp;(<?=$disc_per?>%)
									<?php }else{?>
									<div class="rupee-sign"> &#8377; </div><span class="final_price"><?=$price?></span>
									<br><br>
									<?php } ?>
									</td>
								</tr>
								<?php } ?>
							</table>
						</td>	
					<?php 
					}while($row = mysqli_fetch_assoc($rst));
					?>
				</tr>
			</table>
		 </div>		
		<?php } ?>
	   
		<!-- featured items-->
		<br>
		<hr>
		<?php
		$qry = "select prod_id, prod_advrt, prod_name,prod_thumb1,level_parent,prod_briefdesc,prod_free_text,prod_offerprice,prod_ourprice from prod_mast";
		$qry = $qry." where prod_status=1 ORDER BY prod_purchased desc LIMIT 5";
		if(get_rst($qry,$row, $rst)){ ?>
			<table width="100%"  border="0" align="center">
			  <tr>
				<td align="left"><p><span class="featured">Best-sellers</span></p></td>
				<td align="right"><a class="theme-blue" href="prod_list.php?prod_tpsl=1">Show all top selling products</a></td>
			  </tr>
			</table>
			<div id=""><!-- Best selling items -->
			<table width="100%"  border="0" align="center" class="list">
				<tr>				
				<?php
					do{
						get_rst("select level_name from levels where level_id='".$row["level_parent"]."'",$row_level);
						$url_recentlevel = preg_replace('/[^a-zA-Z0-9]/',"-",$row_level["level_name"]);
						$product_urlname = preg_replace('/[^a-zA-Z0-9]/',"-",$row["prod_name"]);
						$url = "prod_details.php?product=".$product_urlname."&category=".$url_recentlevel;
						get_price($row,$price,$our_price,$disc_per);
						?>
						<td>
							<table>	
								<tr>
								<td align="center"><a style="font-size: 1.2em;" href="<?=$url?>" title="<?=$row["prod_name"]?>">
								<?php
								if(strlen($row["prod_name"]) > 20){
								echo (substr($row["prod_name"], 0, 20)."...");
								}else{
									echo $row["prod_name"];
								}
								?></a></td>
								</tr>
								<tr>
								  <td align="center" width="180px" height="200px"><a href="<?=$url?>"><img src="<?php echo show_img($row["prod_thumb1"])?>" alt="<?php echo display($row["prod_name"])?>" border="0" /></a></td>
								</tr>
								<?php if(intval($row["prod_advrt"]) == 1){ ?>
								<tr>
									<td align="center" valign="bottom">
										<p style="text-align: center; padding-bottom: 10px; padding-top: 10px;"><b><font color="green" size="2px">Ask for a Quote</font></b></p>
									</td>
								</tr>	
								<?php }else{ ?>
								<tr>
								  <td align="center"><?php 
									if($price==0){
										$price = "N/A";
									}else{
										$price = formatnumber($price, 2);
									} ?>
									
									<?php if(intval($disc_per) <> 0){?>
									<div class="rupee-sign">Offer &#8377; </div><span class="final_price"><?=$price?></span>
									<br><div class="rupee-sign">&#8377; </div> <span class="our_price"><?=formatnumber($our_price)?></span>&nbsp;&nbsp;(<?=$disc_per?>%)
									<?php }else{?>
									<div class="rupee-sign"> &#8377; </div><span class="final_price"><?=$price?></span>
									<br><br>
									<?php } ?>
									</td>
								</tr>
								<?php } ?>
							</table>
						</td>	
					<?php }while($row = mysqli_fetch_assoc($rst)); ?>
				</tr>
			</table>
		 </div>		
		<?php } ?>
		
		<br>
		<hr>
		<?php
		$qry = "select prod_advrt, prod_id, prod_name,prod_thumb1,level_parent,prod_briefdesc,prod_free_text,prod_offerprice,prod_ourprice from prod_mast";
		$qry = $qry." where prod_status=1 ORDER BY prod_viewed desc LIMIT 5";
		if(get_rst($qry,$row, $rst)){ ?>
			<table width="100%"  border="0" align="center">
			  <tr>
				<td align="left"><p><span class="featured">Most Popular Products</span></p></td>
				<td align="right"><a class="theme-blue" href="prod_list.php?prod_pspl=1">Show all popular products</a></td>
			  </tr>
			</table>
			<div id=""><!-- Best selling items -->
			<table width="100%"  border="0" align="center" class="list">
				<tr>				
				<?php
					do{
						get_rst("select level_name from levels where level_id='".$row["level_parent"]."'",$row_level);
						$url_recentlevel = preg_replace('/[^a-zA-Z0-9]/',"-",$row_level["level_name"]);
						$product_urlname = preg_replace('/[^a-zA-Z0-9]/',"-",$row["prod_name"]);
						$url = "prod_details.php?product=".$product_urlname."&category=".$url_recentlevel;
						get_price($row,$price,$our_price,$disc_per);
						?>
						<td>
							<table>	
								<tr>
								<td align="center"><a style="font-size: 1.2em;" href="<?=$url?>" title="<?=$row["prod_name"]?>">
								<?php
								if(strlen($row["prod_name"]) > 20){
								echo (substr($row["prod_name"], 0, 20)."...");
								}else{
									echo $row["prod_name"];
								} ?>
								
								</a></td>
								</tr>
								<tr >
								  <td align="center" width="180px" height="200px"><a href="<?=$url?>"><img src="<?php echo show_img($row["prod_thumb1"])?>" alt="<?php echo display($row["prod_name"])?>" border="0" /></a></td>
								</tr>
								<?php if(intval($row["prod_advrt"]) == 1){ ?>
								<tr>
									<td align="center" valign="bottom">
										<p style="text-align: center; padding-bottom: 10px; padding-top: 10px;"><b><font color="green" size="2px">Ask for a Quote</font></b></p>
									</td>
								</tr>
								<?php }else{ ?>
								<tr>
								  <td align="center"><?php 
									if($price==0){
										$price = "N/A";
									}else{
										$price = formatnumber($price, 2);
									}
									if(intval($disc_per) <> 0){?>
									<div class="rupee-sign">Offer &#8377; </div><span class="final_price"><?=$price?></span>
									<br><div class="rupee-sign">&#8377; </div> <span class="our_price"><?=formatnumber($our_price)?></span>&nbsp;&nbsp;(<?=$disc_per?>%)
									<?php }else{?>
									<div class="rupee-sign"> &#8377; </div><span class="final_price"><?=$price?></span>
									<br><br>
									<?php } ?>
									</td>
								</tr>
								<?php } ?>
							</table>
						</td>	
					<?php 
					}while($row = mysqli_fetch_assoc($rst));
					?>
				</tr>
			</table>
		 </div>		
		<?php } ?>

	</div>
</div>
<?php 
	require("brand_img.php");
	require("footer.php");
?>
</div>
</div>

</body>
<script src="scripts/chat.js" type="text/javascript"></script>
<script src="scripts/scripts.js" type="text/javascript"></script>
	
<script type="text/javascript">  
  $(document).ready(function() {
    //when page is loaded
	var logout =0 ; 
	logout = <?php echo intval(isset($_GET['l']));?>

	if($('#prof_dropdown').css('display') != 'none' && logout!=1 && $('#mobile-app-modal').css('display') != 'block'){	
		$('#login-modal').modal('show');
	}
    var valid = call_ajax("ajax.php","process=load_mailchimp_js");
  });
 $(window).load(function() {
		$("#gif_show").fadeOut("slow");
	}); 

</script>

<style> 
.carousel-inner>.item>img, .carousel-inner>.item>a>img
{
    width: 966px;
    height: 350px;
}
.barrowmarginr{ margin-right: -35px !important; }
.barrowmarginl{ margin-left: -35px !important; }
.barrowimage{
	 background-image: none !important;
	 width:0%;
}
.carousel-indicators .active { background-color: #eea236; }
.offer{
	font-size:12px;
	color:#0553f6!important;
}

</style> 

</html>
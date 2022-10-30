<?php

$csql="select count(brand_id) as total_img from brand_mast where brand_status=1 and brand_img!='NULL'"; //to get number of image in table
get_rst($csql, $row_c);
$noimg=$row_c["total_img"];

$divvalue=$noimg/8;
$div=ceil($divvalue);	//rounding of the division reseult

$image = array();	//array in which image and id will store
$brandid = array();
$i=0;

	$qry = "select brand_id,brand_name,brand_img from brand_mast where brand_status=1 and brand_img!='NULL' "; //to get brandid and image from database

if(get_rst($qry, $row, $rst_b))
	{
		do{
			$image[$i]=$row["brand_img"];
			$brandid[$i]=$row["brand_id"];
			$brand_name[$i]=preg_replace('/[^a-zA-Z0-9]/',"-",$row["brand_name"]);
			$i++;
		}while($row = mysqli_fetch_assoc($rst_b));
	}
	
if($noimg<8){		//to get first strip size
	$firststrip=$noimg;
}else{
	$firststrip=8;
	}
	
$count=0;

?>
<style>
 .imagepadding{
	padding-right: 5px;
    padding-left: 5px;
    padding-bottom: 10px;
    padding-top: 10px;
 }
 .arrowmarginr{
	 margin-right: -45px !important;
 }
 .arrowmarginl{
	 margin-left: -45px !important;
 }
 .arrowimage{
	 background-image: none !important;
 }
</style>
<div class="center-div">

<?php if($noimg!=0){?>
	  
<div id="myCarousel" class="carousel slide " data-ride="carousel" style=" display: inline-flex;">
 
	<ul class="carousel-inner" role="listbox" style=" width:980px">
		<li class="item active" >
			<div  style=" display: inline-block;">
			<?php for($j=1;$j<=$firststrip;$j++){?>	
				<a href="prod_list.php?brand=<?php echo $brand_name[$count]?>">
					<img src="<?php echo "data:image/png;base64,".base64_encode(file_get_contents("images/brands/$image[$count]")) ?>" width="90px" height="80px" alt="<?php echo  $image[$count]?>" class="imagepadding"/>
				</a>
			<?php $count++;}?> 
			</div>
		</li>
	 
	<?php if($noimg>8){					// for number of images greater than 8
		for($x=$div-1;$x>0;$x--){	//for display N numnber of strips
			$remainimg=$noimg-$count;
			if($remainimg>8){ 
				$stripsize=8;		
				}else{
					$stripsize=$remainimg;
				} ?>
			<li class="item" >
				<div  style=" display: inline-block;">
				<?php for($p=1;$p<=$stripsize;$p++){?>
					<a href="prod_list.php?brand=<?php echo $brand_name[$count]?>">
						<img src="<?php echo "data:image/png;base64,".base64_encode(file_get_contents("images/brands/$image[$count]")) ?>" width="90px" height="80px" alt="<?php echo $image[$count]?>" class="imagepadding"/>
					</a>
				<?php $count++;}?> 
				</div>
			</li>
	 <?php }
	}?>
  </ul>

  <!-- Left and right controls -->
	<a class="left carousel-control arrowimage" href="#myCarousel" role="button" data-slide="prev">
		<span class="glyphicon glyphicon-chevron-left glyf arrowmarginl" aria-hidden="true"></span>
		<span class="sr-only">Previous</span>
	</a>
	<a class="right carousel-control arrowimage" href="#myCarousel" role="button" data-slide="next">
		<span class="glyphicon glyphicon-chevron-right glyf arrowmarginr" aria-hidden="true"></span>
		<span class="sr-only">Next</span>
	</a>
</div>
<?php }?>
</div>
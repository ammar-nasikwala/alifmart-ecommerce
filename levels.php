<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<?php
    require("inc_init.php");

    $_SESSION["pg_source"] = $_SERVER["PHP_SELF"]."?". $_SERVER["QUERY_STRING"];

    $level = intval(func_read_qs("level"));
    $level_id = func_read_qs("level_id");
    $brand_id = func_read_qs("brand_id");

    $url_brand = "";
    if($brand_id <> ""){
        $url_brand = "&brand_id=" . $brand_id;
    }

    if($brand_id == "" or intval($level)>0){
        $sql = "select * from levels where level_id=0$level_id";
    }else{
        $sql = "select brand_name as level_name,brand_desc as level_desc,brand_title as page_title,brand_meta_key as meta_key,brand_meta_desc as meta_desc,brand_img as level_image from brand_mast where brand_id=0$brand_id";
    }
    flush();
    $rs_level = mysqli_query($con, $sql);
    flush();

    $breadcrumb = breadcrumblink($level_id,$level,$brand_id);

    if($rs_level){
        $row = mysqli_fetch_assoc($rs_level);
        $level_name = $row["level_name"];
        $level_desc = $row["level_desc"];
        $page_title = $row["page_title"];
        $meta_key = $row["meta_key"];
        $meta_desc = $row["meta_desc"];
        $level_image = $row["level_image"] . "";

        if($brand_id <> "" and intval($level)==0){
            if($level_image <> ""){ $level_image = "images/admin_images/user-images/brands/". $level_image;   }
        }
    }

?>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?=$page_title?></title>
<meta name="DESCRIPTION" content="<?=$meta_desc?>"/>
<meta name="KEYWORDS" content="<?=$meta_key?>"/>
<link href="styles/styles.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="scripts/jquery-1.6.1.min.js"></script>
<script type="text/javascript">

function addtocart(prod_id,cart_price,qty){
    if(qty=='' || isNaN(qty)){
        alert("Please enter a numeric value for the quantity.")
    }else{
        window.location.href="addtocart.php?prod_id=" + prod_id + "&cart_price=" + cart_price + "&qty=" + qty
    }
}

</script>

</head>

<body>
<?php
    require("header.php");
?>
<div id="contentwrapper">
<div id="contentcolumn">
  <div class="center-panel">
    <div class="you-are-here">
      <p align="left"> YOU ARE HERE:<a href="index.php" title="">Home</a>| <?=func_var($breadcrumb)?></p>
    </div>
    <?php
    $incomp_msg = func_read_qs("incomp_msg");
    if($incomp_msg <> ""){?>
         <hr color="#006699" size="1px" width="91%" align="center" />
        <p>
            <span class="red">
            <?=incomp_msg?>
            </span>
        </p>
        <hr color="#006699" size="1px" width="91%" align="center" />
    <?php } ?>

   <div class="main-image"><?if(func_var($level_image) <> "") {?><img style="padding: 10px; float:left" src="images/levels/<?=func_var($level_image)?>" border="0" align="left" xwidth="105" height="85" title="<?=func_var($level_name)?>" /><? } ?>

   </div>
    <h1><?=func_var($level_name)?></h1>
    <p><?=func_var($level_desc)?></p>
   </div>

<div class="center-panel">
<?php
    $level_ids = "";
    if($level < 3){  
        ?>
        <div id="sub-category">
        <!-- featured items -->
          <ul class="level1-list">
        <?php
        if($brand_id == ""){
            $sql = "select * from levels where level_status=1 and level_parent=$level_id order by level_sort desc";
        }
        else{
            $sql = "select level_id,level_parent from levels where level_status=1 and level_id in ((select level_id from prod_cats where prod_id in (select prod_id from prod_mast where prod_status = 1 and prod_brand_id=$brand_id)))";
            $sql_brand = "select level_id from prod_cats where prod_id in (select prod_id from prod_mast where prod_status = 1 and prod_brand_id=$brand_id)";
            switch (intval($level)){
                case 0:
                    //die($sql);
                    $rst_l = mysqli_query($con, $sql);
                    flush();
                    if($rst_l){
                        
                        while($row = mysqli_fetch_assoc($rst_l)){
                            $level_parent = $row["level_parent"];
                            $root_id = $row["level_id"];

                            while(intval($level_parent)>0){
                            
                                $rst_ls = mysqli_query($con, "select level_id,level_parent from levels where level_id=$level_parent");
                                $row = mysqli_fetch_assoc($rst_ls);
                                $level_parent = $row["level_parent"];
                                $root_id = $row["level_id"];
                            }

                            $level_ids = $level_ids . "," . $root_id;
                            
                        }
                    }
                    Flush();
                    if($level_ids <> ""){ $level_ids = substr($level_ids,1); }
                    $sql = "select * from levels where level_status=1 and level_id in ($level_ids) order by level_sort desc";
                    break;
                case 1:
                    Flush();
                    $rst_l = mysqli_query($con, $sql);
                    Flush();
                    if($rst_l){
                        while($row = mysqli_fetch_assoc($rst_l)){
                            $level_parent = $row["level_parent"];
                            $root_id = $row["level_id"];

                            while(intval($level_parent)>0){
                                $rst_ls = mysqli_query($con, "select level_id,level_parent from levels where level_id=$level_parent");
                                $row = mysqli_fetch_assoc($rst_ls);
                                $level_parent = $row["level_parent"];
                                if(intval($level_parent)>0){ $root_id = $row["level_id"]; }
                            }
                            $level_ids = $level_ids ."," . $root_id;
                        }
                    }
                    if($level_ids <> ""){ $level_ids = substr($level_ids,1); }

                    $sql = "select * from levels where level_status=1 and level_parent=$level_id and level_id in ($level_ids) order by level_sort desc";
                    break;
                case 2:
                    $sql = "select * from levels where level_status=1 and level_parent=$level_id and (level_id in ($sql_brand)) order by level_sort desc";
                    break;
            }

        }
    
    
    $rs_levels = mysqli_query($con, $sql);
    if($rs_levels){
        
        $cnt = 0;
        Flush();
        $fc_count = 0;
        while($row = mysqli_fetch_assoc($rs_levels)){
            
            $fc_count = $fc_count + 1;
            Flush();
            $fc_return = findchild($row["level_id"],($level + 1));
            Flush();
            if($fc_return){
                
                $cnt = $cnt + 1;
                $level_name = $row["level_name"];
                $level_desc = $row["level_desc"];
                $level_image = $row["level_image"];
                if((($cnt + 2) % 3) == 0){
                    }
                    ?>
                    <li>
                        <a href="levels.php?level=<?=($level + 1)?>&level_id=<?=$row["level_id"]?><?=$url_brand?>">
                            <img class="prod_thumb" style="border-style: none;" src="images/levels/<?php if($level_image <> ""){?><?=$level_image?><?}else{?>images/photonotavailable.gif<?php }?>" alt="<?=$level_name?>" xwidth="105" height="85" border="0" />
                        </a>
                        &nbsp;
                        <p>
                            <a href="levels.php?level=<?=($level + 1)?>&level_id=<?=$row["level_id"]?><?=$url_brand?>"><?=$level_name?></a>
                        </p>
                     </li>

                    <?php
                    if(($cnt % 3) == 0){
                    }
                } //if findchild(level_id,level)){
            }
            Flush();
            if (($cnt % 3) <> 0){
                while (($cnt % 3) <> 0){
                    $cnt = $cnt + 1;
                }
            }
        }  
        ?>
          </ul>
        </div>
    <?php
    }    
    ?>


   <hr style="clear:both" color="#000000" size="1px" width="91%" align="center" />
    <form name="frm_list" method=post>
    <?php

    $rs_prod=""; $sql_prod="";$pgcount = 1;
    $where_brand = "";
    
    if($brand_id <> ""){ $where_brand = " and prod_brand_id = $brand_id";}
    $sql_prod = "select * from prod_mast where prod_status=1 and prod_id in (select prod_id from prod_cats where level_id=0$level_id) $where_brand order by prod_sort";

    Flush();
    $rs_prod = mysqli_query($con, $sql_prod);
    Flush();

    if(intval($level) < 2){ 
        
        $cnt = 0;
        if($rs_prod){
            if(mysqli_num_rows($rs_prod)){?>
                <span class="featured">PRODUCT SELECTION</span>
                <div id="featured-items">
                 <!-- featured items -->
                <ul>
                <?php
                $cnt = 0;
                Flush();
                while($row = mysqli_fetch_assoc($rs_prod)){
                    $cnt = $cnt + 1;
                    $prod_id = $row["prod_id"];
                    $prod_name = $row["prod_name"];
                    $prod_free_text = $row["prod_free_text"];
                    $prod_thumb1 = $row["prod_thumb1"];
                    $prod_thumb_display = $row["prod_thumb_display"]. "";
                    $price = $row["prod_effectiveprice"];
                    $is_vat = $row["is_vat"];
                    $sell_online = $row["sell_online"];
                    
                    if ((($cnt + 2) % 3) == 0){
                        ?>
                        <tr>
                        <?php
                    }
                    $url = "prod_details.php?prod_id=$prod_id"; //get_prod_url($rs_prod,$level_id)
                    ?>
                        <li>
                    <?php if($prod_thumb_display == "1"){?>
                      <p class="thumb"><a href="<?=$url?>"><img src="images/products/thumb/<?php if($prod_thumb1 <> ""){?><?=$prod_thumb1?><?php }else{?>images/photonotavailable.gif <?php }?>" width="100" xheight="93" border="0" /></a></p>
                    <?php }?>
                       <p class="title"><a href="<?=$url?>"><strong><?=$prod_name?></strong></a></p>
                           <?php if($sell_online <> "1"){?>
                                <table width="250px" class="table-price" >
                                  <tr>
                                    <td align="center" bgcolor="#006699"><p><span class="white-small">NEED A PRICE?</span></p></td>
                                  </tr>
                                  <tr>
                                    <td align="center" bgcolor="#FFFFFF"><p>Call us on 020 4003 5353</p></td>
                                  </tr>
                                </table>
                               <?php }else{?>

                                <table width="250px">
                                  <tr>
                                    <th><p><span class="white-small"><?=$row["prod_pricestatus"]?> PRICE</span></p></th>
                                    <?php show_qty($prod_id,$price,"1")?>
                                    <td align="center" rowspan="2"><a href="<?=url?>"><img src="images/more-info.gif" alt="DETAILS" width="50" height="50" border="0" /></a></td>
                                  </tr>
                                  <tr>
                                    <td align="center" bgcolor="#FFFFFF"><p><span class="red-big">&pound;<?=formatNumber($price,2)?></span> exc.GST<?php if($is_vat == "1"){?><br />&pound;<?=formatNumber((price+(price*sv_vat_percent/100)),2)?>&nbsp;inc.GST <?php }?></p></td>
                                    <?php show_qty1($prod_id)?>
                                  </tr>
                                </table>
                           <?php }?>
                        </li>
                    <?php
                    if(($cnt % 3) == 0){
                        ?>
                        </tr>
                        <?php
                    }
                }
                Flush();
                if (($cnt % 3) <> 0){
                    while(($cnt % 3) <> 0){
                        ?>
                        <td width="33%"></td>
                        <?php
                        $cnt = $cnt + 1;
                    }
                    ?>
                    </tr>
                <?php }?>
            </ul>
        </div>
            <!--featured items-->
        <?php
        }
    }    // if not rs_prod.EOF
    }else{ ?>
    <!---Products in Listing format-->
    <?php
        $pgno = func_read_qs("pgno");
        if($pgno == ""){
            $pgno = 1;
        }
        $pg_size = 10;
        $pgcount = 10/$pg_size;
        if($rs_prod){
    ?>
         <span class="featured">PRODUCT SELECTION</span>
        <?php
            $i = 0;
            if($pgcount>1){
        ?>
                <div id="numbers">
                  <table width="100%"  border="1">
                    <tr>
                      <td width="20%" align="left">
                        <?php
                        if($pgno > 1){
                        ?>
                            <a href="levels.php?pgno=<?=(pgno-1)?>&level=<?=level?>&level_id=<?=level_id?><?=url_brand?>">Previous</a>
                        <?php
                        }else{
                        ?>
                            &nbsp;
                        <?php
                        }
                        ?>
                      </td>
                      <td width="60%"><p align="center">Page
                        <?php //call set_pages(2,abpage,pgcount,s,e)
                        $S=1;$e=4;
                        for($i = $s; $i <= $e; $i++){?>
                            <?php if($i == intval($pgno)){?>
                                <span class="active"><?=$i?></span>
                            <?php }else{?>
                            <a href="levels.php?pgno=<?=$i?>&level=<?=$level?>&level_id=<?=$level_id?><?=$url_brand?>"><?=$i?></a>
                            <?php }?>
                        <?php } ?>
                      </p></td>
                      <td width="20%"><p align="right">
                        <?php if($abpage < $pgcount){ ?>
                            <a href="levels.php?pgno=<?=($pgno+1)?>&level=<?=$level?>&level_id=<?=$level_id?><?=$url_brand?>">Next</a>
                        <?php }?>
                      </p></td>
                    </tr>
                  </table>
                </div>
            <?php
            } // if pgcount>1){
        }
        ?>
        <div id="products">
     <!--featured items-->
     <ul class="level2-list">
    <?php
    if($rs_prod){
        $i = 0;
        Flush();
        while($row = mysql_fetch_assoc($rs_prod)){
            $prod_id = $row["prod_id"];
            $prod_name = $row["prod_name"];
            $prod_free_text = $row["prod_free_text"];
            $prod_thumb1 = $row["prod_thumb1"];
            $prod_thumb_display = $row["prod_thumb_display"]. "";
            $price = $row["prod_effectiveprice"];
            $is_vat = $row["is_vat"];
            $sell_online = $row["sell_online"];
            //$url = get_prod_url($rs_prod,$level_id);
            $url = "prod_details.php?prod_id=$prod_id";
    ?>
              <li>
              <?php
                    $td_width="37";
                    if($prod_thumb_display == "1"){?>
                    <?php
                        }else{
                            $td_width="100";
                        }?>
                    <table width="250px" border="0">
                        <tr>

                        <td rowspan="2">
                            <a href="<?=$url?>"><img src="<?php if($prod_thumb1 <> ""){?>images/products/thumb/<?=$prod_thumb1?><?php }else{?>images/photonotavailable.gif <?php }?>" alt="<?=display($prod_name)?>" border="0" /></a>
                        </td>
                        
                        <td rowspan="2">
                        <p class="title"><a href="<?=$url?>" title="<?=$prod_name?>"><?=$prod_name?></a></p>
                        </td>
                        
                      <td rowspan="2" align="center"><p><?=$prod_free_text?></p></td>

                      <?php if($sell_online <> "1"){?>
                        <th><p><span class="white-small"><strong>NEED A PRICE?</strong></span></p></th>
                      <?php }else{?>
                        <th><p><span class="white-small"><?=$row["prod_pricestatus"];?> PRICE</span></p></th>
                        <?php show_qty($prod_id,$price,"2")?>
                      <?php }?>

                      <td rowspan="2" width="12%" align="center"><a href="<?=$url?>"><img src="images/more-info.gif" alt="Details" width="50" height="50" border="0" /></a></td>
                    </tr>

                    <?php if($sell_online <> "1"){?>
                        <tr><td align="center" class="red-border"><p><strong>CALL US +91 20 4003 5353</strong></p></td></tr>
                    <?php }else{?>
                        <tr>
                        <td align="center" class="red-border"><p><span class="red-list">&pound;<?=formatNumber($price,2)?></span> exc.GST<br /><?php if($is_vat == "1"){?>&pound;<?=formatNumber(($price+($price*$sv_vat_percent/100)),2)?>  inc.GST<?php }?></p></td>
                       <?php show_qty1($prod_id)?>
                    </tr>
                    <?php }?>
                </table></li>
                <?php
                    $i = $i + 1;
                    }
                    Flush();
                } // if not rs_prod.EOF
                ?>
        </ul></div>


<!---End of Products in Listing format-->
    <?php
        }
    ?>

    <?php
    if($pgcount>1){
    ?>
        <div id="numbers">
          <table width="100%"  border="0">
            <tr>
              <td width="20%" align="left">
            <?php
                if(pgno > 1){
            ?>
                    <a href="levels.php?pgno=<?=($pgno-1)?>&level=<?=$level?>&level_id=<?=$level_id?><?=$url_brand?>">Previous</a>
            <?php
                }else{
            ?>
                    &nbsp;
            <?php
                }
            ?>
              </td>
              <td width="60%"><p align="center">Page
                <?php //call set_pages(2,abpage,pgcount,s,e)
                for($i = $s; $i <= $e; $i++){?>
                <?php if($i = intval($pgno)){?>
                    <span class="active"><?=i?></span>
                <?php }else{?>
                <a href="levels.php?pgno=<?=$i?>&level=<?=$level?>&level_id=<?=$level_id?><?=$url_brand?>"><?=$i?></a>
                <?php }?>
                <?php } ?>
              </p></td>
              <td width="20%"><p align="right">
                <?php if($abpage < $pgcount){ ?>
                    <a href="levels.php?pgno=<?=($pgno+1)?>&level=<?=$level?>&level_id=<?=$level_id?><?=$url_brand?>">Next</a>
                <?php }?>
              </p></td>
            </tr>
          </table>
        </div>
    <?php
    } //if pgcount>1){
    ?>

</form>
</div>
</div>
</div>

<?php
    require("left.php");
    require("footer.php");
?>

</div>
</div>
</body>
</html>

<?php
function show_qty($prod_id,$cart_price,$level){
    $rsta = mysqli_query($con,"select prod_id from prod_addon where prod_id=$prod_id");    
    if($rsta){
        ?>
         <th ><p><span class="white-small">QTY:</span></p></th>
         <td width="12%" rowspan="2" align="center"><a href="javascript: addtocart(""<?=$prod_id?>"",""<?=$cart_price?>"",document.frm_list.qty_<?=$prod_id?>.value);">
         <img src="images/add-to-basket.gif" alt="Buy" width="50" height="50" border="0"></a></td>
        <?php
    }else{
        if($level <> "1"){
            ?>
            <td colspan=2>&nbsp;</td>
            <?php
        }
    }
}

function show_qty1($prod_id){
    $rsta = mysqli_query($con,"select prod_id from prod_addon where prod_id=$prod_id");
    if($rsta){
        ?>
        <td align="center" class="red-border"><input name="qty_<?=$prod_id?>" type="text" size="3" maxlength="20"></td>
        <?php
    }
}

?>

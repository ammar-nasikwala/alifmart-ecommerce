<?php
require_once("inc_admin_header.php");

$sql=get_rst("select resource_name,resource_path from resources",$row,$rst);
?>
<center>
<h3>My Resources</h3>
<table border="1px" cellspacing="5" cellpadding="5" class="table_resource" style="border: 2px solid #e5e5e5; margin-top:40px;">
	<?php if($sql<>""){?>
	
	<?php do{?>
	<tr style="border: 2px solid #e5e5e5;">
        <td style="padding-top: 18px;  width:50% !important;   text-align: left !important;">
		<?=limit_chars($row["resource_name"],25)?>
        </td>
		<td style="border-left: 2px solid #e5e5e5; width:200px;">
		<?php
		$ext="";
		$ext = pathinfo($row["resource_path"], PATHINFO_EXTENSION);
		if($ext=="pdf"){
		?>
		<img src="images/pdficon.png" data-toggle="modal" data-target="#myModal" alt="PDF Document" width="45px" height="35px"/>
		
			<div id="myModal" class="modal fade" role="dialog">
				<div class="modal-dialog" style="width:900px;">
				<!-- Modal content-->
				<div class="modal-content" >
				<div class="modal-body" style="overflow=scroll;">	  
					<iframe src="http://<?php echo $_SERVER["SERVER_NAME"]."/".$row["resource_path"];?>" width="100%" height="500px"></iframe>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
				</div>
				</div>
				</div>
			</div>
		<?php }else{?>
			<a target = '_blank' href="<?php echo $row["resource_path"];?>"><img src="images/youtubeicon.png" alt="PDF Document" width="45px" height="35px"/></a>
		<?php }?>
        </td>
	</tr>
	<?php }while($row = mysqli_fetch_assoc($rst));
	}?>
</table>
</center>

<?php
require_once("inc_admin_footer.php");
?>

<script>
function toggle(a){
	var i = document.getElementById('image' + a)
	var e =  document.getElementById(a)
	if(i.src.indexOf("dhtmlgoodies_minus.gif") > -1){
		e.style.display = "none"
		i.src = "../images/dhtmlgoodies_plus.gif"
	} else {
		e.style.display = "block"
		i.src = '../images/dhtmlgoodies_minus.gif';
	}
}
</script>

<style>
	
	
	.td_cat{
		font-family:Verdana;
		font-size:12px;
		color:#000000;
		Title:Edit;
		height:35px;
		text-decoration:underline;
	}
	
	.td_cat:hover{
		background-color: #CCCCFF;
		cursor:pointer;
		cursor:hand;
		Title:Edit;
	}
	
</style>

<?
function create_cat_tree(){
	?>
	<table xborder="1" class="table_tree" CELLPADDING=0 CELLSPACING=0>   	

		<tr>
			<td><a onclick="javascript: js_sel(this,'0','[Root Level]');"><b>[Root Level]</b> </a><br></td>
		</tr>
		<tr> <td> <div> <table>
		
	<?
	
	If(func_read_qs("i") <> ""){
		//v_hdn_cat_id = hdn_cat_id.Value
	}
	
	traverse_levels(0);
	
	?>
	</table>
	</div>
	</table>
	<?	
}

function traverse_levels($level_parent){
	global $v_hdn_cat_id;
	global $shift;
	$v_tree = "";
	global $v_level_path;
	//if(!isset($level_name)){ $level_name = ""; }
	$shift++;
	global $path_arr;
	$path_arr = array();
	global $con;
	
	$rst_cats_1 = mysqli_query($con,"select * from levels where level_parent=$level_parent order by level_name");
	if($rst_cats_1){
		//echo("|".$level_parent."<br>");
		//While($row = mysqli_fetch_assoc($rst_cats_1)){
			$v_cat = "";
			$v_cat_n = "";
			$v_cat_path = "";
			$v_cat_id = "";
			$v_cat_id_n = "";
			$v_cat_parent = "";
			$v_cat_parent_n = "";
			$v_img = "";
			
			while(True){
				
				if($v_cat == ""){

					if($row = mysqli_fetch_assoc($rst_cats_1)){
						
						$v_cat_n = $row["level_name"];
						$v_cat_id_n = $row["level_id"];
						$v_cat_parent_n = $row["level_parent"];
						$v_img = "tree_T.png";
						
						//echo("v_cat: $v_cat_n <br>");
					}Else{
						$v_img = "tree_L.png";
						//echo("Only 1 <br>");
					}
				}
				$v_cat = $v_cat_n;
				//$level_name = $level_name." > ".$row["level_name"];
				$path_arr[$shift] = $row["level_name"];
				
				$v_cat_path = $v_cat;

				
				$v_cat_id = $v_cat_id_n;
				$v_cat_parent = $v_cat_parent_n;
	
				if($v_hdn_cat_id == $v_cat_id){
					$v_sel_cat_path = $v_cat_path;
				}

				if($row = mysqli_fetch_assoc($rst_cats_1)){
					$v_cat_n = $row["level_name"];
					$v_cat_id_n = $row["level_id"];
					$v_cat_parent_n = $row["level_parent"];
					$v_img = "tree_T.png";
					//echo("v_cat: $v_cat_n <br>");
				}Else{
					$v_img = "tree_L.png";
					//echo("Last <br>");
				}

				If($v_cat <> ""){
					if($_SESSION["tree"] == "yes"){
						if(intval($v_cat_parent)>0){
							$v_tree = $v_tree . "<tr><td></td><td></td><td><a onclick=\"javascript: js_sel(this,'" . $v_cat_id . "','" . $v_cat_path . "');\"><strong>" . $v_cat . "</strong></a></td></tr>";
						}else{
							//$padding_td$img
							$v_tree = $v_tree . "</table></div></td></tr>\n<table><tr><td><a onclick=\"javascript: toggle('" . $v_cat_id . "');\"><img id=\"image".$v_cat_id."\" src=\"../images/dhtmlgoodies_plus.gif\" style=\"border:0;margin-right:5px;vertical-align:middle;\" /></a>";
							$v_tree = $v_tree . "<span><a onclick=\"javascript: js_sel(this,'" . $v_cat_id . "','" . $v_cat_path . ", 1');\"><strong>" . $v_cat . "</strong></a></span></td></tr>\n<tr><td><div id=\"".$v_cat_id."\" style=\"display:none;\"><table>";
						}
					
					}else{
						if(intval($v_cat_parent)>0){
							$v_tree = $v_tree . "<tr><td></td><td><strong>" . $v_cat . "</strong></td><td><a title\"Edit\" onclick=\"javascript: js_sel(this,'" . $v_cat_id . "','" . $v_cat_path . "');\"><span class=\"glyphicon glyphicon-pencil\"></span></a></td></tr>";
						}else{
							//$padding_td$img
							$v_tree = $v_tree . "</table></div></td></tr>\n<table><tr><td><a onclick=\"javascript: toggle('" . $v_cat_id . "');\"><img id=\"image".$v_cat_id."\" src=\"../images/dhtmlgoodies_plus.gif\" style=\"border:0;margin-right:5px;vertical-align:middle;\" /><strong>" . $v_cat . "</strong></a></td>";
							$v_tree = $v_tree . "<td><a title=\"Edit\" onclick=\"javascript: js_sel(this,'" . $v_cat_id . "','" . $v_cat_path . ", 1');\"><span class=\"glyphicon glyphicon-pencil\"></span></a></td></tr>\n<tr><td><div id=\"".$v_cat_id."\" style=\"display:none;\"><table>";
						}
					}
				}

				echo($v_tree."\n");
				$v_tree="";
				
				traverse_levels($v_cat_id);
				$shift = $shift -1;
				//$level_name="";
				If($v_img == "tree_L.png"){
					
					break;
				}
				
			}
	}

}
?>

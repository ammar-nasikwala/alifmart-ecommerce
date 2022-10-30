<?php

//if(get_rst("select prod_thumb1, prod_thumb2, prod_thumb3, prod_thumb4, prod_large1, prod_large2, prod_large3, prod_large4, prod_stockno from prod_mast", $row, $rst)){
if(get_rst("select prod_large1,prod_stockno from prod_mast", $row, $rst)){
	do {
		/*$imgData = $row['prod_thumb1'];
		// Path where the image is going to be saved
		if(is_dir("images/products/".$row["prod_stockno"])==false){	
			mkdir("images/products/".$row["prod_stockno"], 0700);
		}
		$fileThumbPath = 'images/products/'. $row["prod_stockno"].'/prod_thumb1.jpg';
		// Write $imgData into the image file
		$file = fopen($fileThumbPath, 'w');
		fwrite($file, $imgData);
		fclose($file);
		*/
		$imgData = $row['prod_large1'];
		// Path where the image is going to be saved
		$fileLargePath = 'extras/images/products/'.$row["prod_stockno"].'.jpg';
		// Write $imgData into the image file
		$file = fopen($fileLargePath, 'w');
		fwrite($file, $imgData);
		fclose($file);
		/*
		if($row['prod_thumb2'] <> NULL || $row['prod_thumb2'] <> ""){
			$imgData = $row['prod_thumb2'];
			// Path where the image is going to be saved
			$fileThumbPath = 'images/products/'. $row["prod_stockno"].'/prod_thumb2.jpg';
			// Write $imgData into the image file
			$file = fopen($fileThumbPath, 'w');
			fwrite($file, $imgData);
			fclose($file);
			
			$imgData = $row['prod_large2'];
			// Path where the image is going to be saved
			$fileLargePath = 'images/products/'.$row["prod_stockno"].'/prod_large2.jpg';
			// Write $imgData into the image file
			$file = fopen($fileLargePath, 'w');
			fwrite($file, $imgData);
			fclose($file);
		}
		if($row['prod_thumb3'] <> NULL || $row['prod_thumb3'] <> ""){
			$imgData = $row['prod_thumb3'];
			// Path where the image is going to be saved
			$fileThumbPath = 'images/products/'. $row["prod_stockno"].'/prod_thumb3.jpg';
			// Write $imgData into the image file
			$file = fopen($fileThumbPath, 'w');
			fwrite($file, $imgData);
			fclose($file);
			
			$imgData = $row['prod_large3'];
			// Path where the image is going to be saved
			$fileLargePath = 'images/products/'.$row["prod_stockno"].'/prod_large3.jpg';
			// Write $imgData into the image file
			$file = fopen($fileLargePath, 'w');
			fwrite($file, $imgData);
			fclose($file);
		}
		
		if($row['prod_thumb4'] <> NULL || $row['prod_thumb4'] <> ""){
			$imgData = $row['prod_thumb4'];
			// Path where the image is going to be saved
			$fileThumbPath = 'images/products/'. $row["prod_stockno"].'/prod_thumb4.jpg';
			// Write $imgData into the image file
			$file = fopen($fileThumbPath, 'w');
			fwrite($file, $imgData);
			fclose($file);
			
			$imgData = $row['prod_large4'];
			// Path where the image is going to be saved
			$fileLargePath = 'images/products/'.$row["prod_stockno"].'/prod_large4.jpg';
			// Write $imgData into the image file
			$file = fopen($fileLargePath, 'w');
			fwrite($file, $imgData);
			fclose($file);
		}*/
	}while($row=mysqli_fetch_assoc($rst));
}
?>
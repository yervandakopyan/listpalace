<?

class image {
	
	public function resize_image($image_info, $directory, $max_upload_width, $max_upload_height) {
		##IMAGE UPLOAD AND RESIZE##
		
		$image_names=array();
		
		foreach ($image_info as $image_files) {
			// some settings
			//$max_upload_width = 110;
			//$max_upload_height = 110;
			
			// if uploaded image was JPG/JPEG
			if($image_files["type"] == "image/jpeg" || $image_files["type"] == "image/pjpeg"){	
				$image_source = imagecreatefromjpeg($image_files["tmp_name"]);
			}		
			// if uploaded image was GIF
			if($image_files["type"] == "image/gif"){	
				$image_source = imagecreatefromgif($image_files["tmp_name"]);
			}	
			// BMP doesn't seem to be supported so remove it form above image type test (reject bmps)	
			// if uploaded image was BMP
			if($image_files["type"] == "image/bmp"){	
				throw new Exception("BMP images are not supported. Please upload a JPG, GIF or PNG format.");
			}			
			// if uploaded image was PNG
			if($image_files["type"] == "image/png"){
				$image_source = imagecreatefrompng($image_files["tmp_name"]);
			}
			
			
			if($image_files['name'] != ""){
				$parray=explode(".", $image_files['name']);
				$ext=strtolower($parray[(sizeof($parray)-1)]);
				$fname=substr($image_files['name'], 0, (strlen($ext) * -1));
				$filename = trim(str_replace(" ", "_", $fname));  //replace the spaces in file names with underscore
				$uniqfn = uniqid($filename);
				$image = $uniqfn . "." . $ext;
			} 
			
			$remote_file = $directory .$image;
			imagejpeg($image_source,$remote_file,100);
			chmod($remote_file,0644);
		
		

			// get width and height of original image
			list($image_width, $image_height) = getimagesize($remote_file);
		
			if($image_width>$max_upload_width || $image_height >$max_upload_height){
				$proportions = $image_width/$image_height;
				
				if($image_width>$image_height){
					$new_width = $max_upload_width;
					$new_height = round($max_upload_width/$proportions);
				}		
				else{
					$new_height = $max_upload_height;
					$new_width = round($max_upload_height*$proportions);
				}		
				
				
				$new_image = imagecreatetruecolor($new_width , $new_height);
				$image_source = imagecreatefromjpeg($remote_file);
				
				imagecopyresampled($new_image, $image_source, 0, 0, 0, 0, $new_width, $new_height, $image_width, $image_height);
				imagejpeg($new_image,$remote_file,100);
				
				imagedestroy($new_image);
			}
			
			imagedestroy($image_source);
			$image_names[]=$image;
			##end of image upload##
		}
		
		return $image_names;
	}


}
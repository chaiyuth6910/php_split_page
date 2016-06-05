<?php 
 /*
|--------------------------------------------------------------------------
| Random String
|--------------------------------------------------------------------------
|
| ฟังก์ชันสุ่มตัวอักษร
|
*/
function random_string($length) {
    $key = '';
    $keys = array_merge(range(0, 9), range('a', 'z'));

    for ($i = 0; $i < $length; $i++) {
        $key .= $keys[array_rand($keys)];
    }

    return $key;
}

 /*
|--------------------------------------------------------------------------
| Upload Multiple Image to database
|--------------------------------------------------------------------------
|
| ฟังก์ชันัพโหลดไฟล์และบันทึกลงฐานข้อมูล
|
*/

function genius_muup_todb($inputname="files",$maxfilesize="2097152",$orgdirectory="uploadphotos",$thumbdirectory="thumbphotos",$thumbwidth="100",$thumbheight="100"){
	if(isset($_FILES[$inputname])){

                          $errors= array();
                	foreach($_FILES[$inputname]['tmp_name'] as $key => $tmp_name){

                                        $file_name = $key."_".random_string(32).".".pathinfo($_FILES[$inputname]['name'][$key], PATHINFO_EXTENSION);
                                        $file_size =$_FILES[$inputname]['size'][$key];
                                        $file_tmp =$_FILES[$inputname]['tmp_name'][$key];
                                        $file_type=$_FILES[$inputname]['type'][$key];

                                        if($file_size > $maxfilesize){
                        		      $errors[]='File size must be less than 2 MB';
                                        }		
                                
                                        // Query to insert database
                                        $query = "INSERT into tbl_images(imgname,imgtype,imgsize) VALUES('$file_name','$file_type','$file_size')";
                                                                                
                                        if(empty($errors)==true){

                                                if(is_dir($orgdirectory)==false){
                                                    mkdir("$orgdirectory", 0700); 
                                                }

                                                 if(is_dir($thumbdirectory)==false){
                                                    mkdir("$thumbdirectory", 0700); 
                                                }
                                                
                                                if(is_dir("$orgdirectory/".$file_name)==false){

                                                    $max_width = $thumbwidth;
                                                    $max_height = $thumbheight;
                                                    $thumbname = $thumbdirectory."/".$file_name;

                                                    if ($file_type == 'image/jpeg') {
                                                        $src = imagecreatefromjpeg($tmp_name);
                                                    } else if ($file_type == 'image/png') {
                                                        $src = imagecreatefrompng($tmp_name);
                                                    } else if ($file_type == 'image/gif') {
                                                        $src = imagecreatefromgif($tmp_name);
                                                    }

                                                    list($width,$height)=getimagesize($tmp_name);

                                                    $tmp=imagecreatetruecolor($max_width,$max_height);

                                                    $width_new = $height * $max_width / $max_height;
                                                    $height_new = $width * $max_height / $max_width;

                                                    if($width_new > $width){
                                                        $h_point = (($height - $height_new) / 2);
                                                        imagecopyresampled($tmp, $src, 0, 0, 0, $h_point, $max_width, $max_height, $width, $height_new);
                                                    }else{
                                                        $w_point = (($width - $width_new) / 2);
                                                        imagecopyresampled($tmp, $src, 0, 0, $w_point, 0, $max_width, $max_height, $width_new, $height);
                                                    }

                                                    if ($file_type == 'image/jpeg') {
                                                         imagejpeg($tmp,$thumbname,100);
                                                    } else if ($file_type == 'image/png') {
                                                        imagepng($tmp,$thumbname);
                                                    } else if ($file_type == 'image/gif') {
                                                        imagegif($tmp,$thumbname,100);
                                                    }

                                                    imagedestroy($src);
                                                    move_uploaded_file($file_tmp,"$orgdirectory/".$file_name);

                                                    $count++;

                                                }else{
                                                        $new_dir="$orgdirectory/".$file_name.time();
                                                        rename($file_tmp,$new_dir) ;				
                                                }
                                	
                                                mysql_query($query);

                                        }else{
                                            return $errors;
                                        }

                           }// foreach

                	if(empty($errors)){
                	       return "Your Photos Is Successfully Uploded";
                	}else{
                                return $errors;
                         }
            }	
}

 ?>

 <!--
 Onchang Image
 -->

<!--
onchange="document.getElementById('output').src = window.URL.createObjectURL(this.files[0])"
-->
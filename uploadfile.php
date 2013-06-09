<?php
// In PHP versions earlier than 4.1.0, $HTTP_POST_FILES should be used instead
// of $_FILES.

$uploaddir = 'images/';//<----This is all I changed
$randval = rand(10000,99999);
$filebase = strtolower(str_replace(' ', '_',(basename($_FILES['userfile']['name']))));
$uploadfile  = $uploaddir . 'upload_'. (string)$randval . $filebase;
$newfilename = $uploaddir . 'sml_'   . (string)$randval . $filebase;
		
$maxwidth = 315;

$allowedExts = array("gif", "jpeg", "jpg", "png");
$extension = strtolower(end(explode(".", $_FILES["userfile"]["name"])));
$filetype = $_FILES["userfile"]["type"];
include 'chatlib.php';	

writetolog('newfile:'. $uploadfile.','.$filetype);

// currently not checking for filetypes - android browser returns application/octetstream 
//if ((($filetype == "image/gif") || ($_FILES["userfile"]["type"] == "image/jpeg")
//|| ($_FILES["userfile"]["type"] == "image/jpg") || ($_FILES["userfile"]["type"] == "image/pjpeg")
//|| ($_FILES["userfile"]["type"] == "image/x-png") || ($_FILES["userfile"]["type"] == "image/png")) &&
if  (($_FILES["userfile"]["size"] < 5000000) and in_array($extension, $allowedExts))
{
	if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) 
	{
    writetolog('newfile:'.$uploadfile.' to '.$newfilename);
		
		if (resizeimage($uploadfile, $newfilename, 313,600,75, $resizegif=false))
		{
			$line = buildline('pic', $newfilename);
		} else
		{
			writetolog('newfile:'.$uploadfile.' fail');

		}
	
	} else {
		$line = "Couldn't find file '". $_FILES['userfile']['tmp_name']."'";
	}
	addline2file($line);
	
} else {
	$line = "Upload fail. Max 5Mb, png, jpg";
}
	header('Location: ' . $_SERVER['HTTP_REFERER']);
?>

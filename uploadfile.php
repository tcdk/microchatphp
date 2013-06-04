<?php
// In PHP versions earlier than 4.1.0, $HTTP_POST_FILES should be used instead
// of $_FILES.

$uploaddir = 'images/';//<----This is all I changed
$uploadfile = $uploaddir . (string)rand(10000,99999).'_' .basename($_FILES['userfile']['name']);
$uploadfile = str_replace(' ', '_', $uploadfile);

$maxwidth = 315;

$allowedExts = array("gif", "jpeg", "jpg", "png");
$extension = strtolower(end(explode(".", $_FILES["userfile"]["name"])));
$filetype = $_FILES["userfile"]["type"];
include 'chatlib.php';	

writetolog('newfile:'.$filetype);

// currently not checking for filetypes - android browser returns application/octetstream 
//if ((($filetype == "image/gif") || ($_FILES["userfile"]["type"] == "image/jpeg")
//|| ($_FILES["userfile"]["type"] == "image/jpg") || ($_FILES["userfile"]["type"] == "image/pjpeg")
//|| ($_FILES["userfile"]["type"] == "image/x-png") || ($_FILES["userfile"]["type"] == "image/png")) &&
if  (($_FILES["userfile"]["size"] < 5000000)
&& in_array($extension, $allowedExts))
{
	if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) 
	{
    $newfilename = strtolower($uploaddir  . 'sml_'. (string)rand(10000,99999) .  str_replace(' ', '_', basename($_FILES['userfile']['name'])));
		writetolog('newfile:'.$uploadfile.' to '.$newfilename);
		
		if (resizeimage($uploadfile, $newfilename, 313,600,75))
		{
			$line = buildline('pic', $newfilename);
		} else
		{
			writetolog('newfile:'.$uploadfile.' fail');

		}
	
	} else {
		$line = "Upload fail. Max 5Mb, png, jpg";
	}
	addline2file($line);
	
	header('Location: ' . $_SERVER['HTTP_REFERER']);
}
?>

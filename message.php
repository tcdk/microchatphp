<?php 
header('Content-type: text/html; charset=utf-8');



if(!@file_exists('config.php') ) {
  $links = array();  
} else {
   include_once('config.php');
}

<<<<<<< HEAD
=======
$maxlines = 40;
require("chatlib.php"); 
>>>>>>> 65d87655639ca83be0cffb58b1b36fb60c5714a1
require("UrlLinker.php");

if (isset($_GET['msg'])){
	if (isset($_GET['msg']) and (strtolower(trim($_GET['msg'])) == 'clear')) {
		unlink("msg.html");
	}
	if (file_exists('msg.html')) {
		$f = fopen('msg.html',"a+");
	} else {
		$f = fopen('msg.html',"w+");
	}


	$nick = isset($_GET['nick']) ? $_GET['nick'] : "Hidden";
    $line = buildline($nick, $_GET['msg']);
	fwrite($f,$line."\r\n");
	fclose($f);
	echo "one," . html_entity_decode(stripslashes($line));

} else if (isset($_GET['all'])) {
	
	$flag = file('msg.html');
    $content = "";
    foreach ($flag as $value) {
<<<<<<< HEAD
	    $content .= html_entity_decode(stripslashes($value));
	}
    echo $content;

=======
		$content .= html_entity_decode(stripslashes($value));
	}
	echo "all," . $content;
	
>>>>>>> 65d87655639ca83be0cffb58b1b36fb60c5714a1
}

	
?>

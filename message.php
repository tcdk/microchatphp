<?php 
header('Content-type: text/html; charset=utf-8');



if(!@file_exists('config.php') ) {
  $links = array();  
} else {
   include_once('config.php');
}

$maxlines = 40;
require("chatlib.php"); 

if (isset($_GET['msg'])){
	if (trim($_GET['msg'])!='')
    {
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
 	} else
 	{
 		echo "nul,nul";
 	}
} else if (isset($_GET['all'])) {
	
	$flag = file('msg.html');
    $content = "";
    foreach ($flag as $value) {
	    $content .= html_entity_decode(stripslashes($value));
	}
	echo "all," . $content;
	
}

	
?>

<?php 
header('Content-type: text/html; charset=utf-8');



if(!@file_exists('config.php') ) {
  $links = array();  
} else {
   include_once('config.php');
}

$maxlines = 40;
require("chatlib.php"); 
require("UrlLinker.php");

if (isset($_GET['msg'])){
	if (trim($_GET['msg'])!='')
    {
    	if (isset($_GET['msg']) and (strtolower(trim($_GET['msg'])) == 'clear')) {
			unlink("msg.html");
		}
		
    
	    $nick = isset($_GET['nick']) ? $_GET['nick'] : "Hidden";
	    $line = buildline($nick, $_GET['msg']);
	    addlinetofile($line);
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

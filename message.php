<?php 
header('Content-type: text/html; charset=utf-8');



if(!@file_exists('config.php') ) {
  $links = array();  
} else {
   include_once('config.php');
}

require("UrlLinker.php");

function Smilify(&$subject)
{
    $smilies = array(
        ';)'  => 'wink',
        ';-)' => 'wink',
        ':p'  => 'razz',
        ':-p' => 'razz',
        ':P'  => 'razz',
        ':-P' => 'razz',
        ':D'  => 'biggrin',
        ':-D' => 'biggrin',
        ':)'  => 'smile',
        ':-)' => 'smile',
        ':('  => 'nosmile',
        ':-(' => 'nosmile',
        ':*' => 'bussi',
        '&amp;lt;3' => 'heart'
        
    );

    $sizes = array(
        'biggrin' => 20,
        'cool' => 20,
        'haha' => 20,
        'mellow' => 20,
        'ohmy' => 20,
        'sad' => 20,
        'smile' => 18,
        'tongue' => 20,
        'wink' => 20,
    );

    $replace = array();
    foreach ($smilies as $smiley => $imgName)
    {
        $size = $sizes[$imgName];
        array_push($replace, 
        '<img  height="14px" src="imgs/smiley_emoticons_'.$imgName.'.gif" alt="'.$smiley.'"  />');
    }
    $subject = str_replace(array_keys($smilies), $replace, $subject);
}

if (isset($_GET['msg'])){
	if (isset($_GET['msg']) and (strtolower(trim($_GET['msg'])) == 'clear')) {
		unlink("msg.html");
	}
	if (file_exists('msg.html')) {
		$f = fopen('msg.html',"a+");
	} else {
		$f = fopen('msg.html',"w+");
	}

	$new_date = strtotime(time()) + strtotime("+0 hours");

	$nick = isset($_GET['nick']) ? $_GET['nick'] : "Hidden";
	$c = "black";
	if (isset($usercolors[$nick]))
	  $c = $usercolors[$nick];
	
	
	$nick = $nick . " (" . date('H:i', $new_date).")";
	$msg  = isset($_GET['msg']) ? htmlEscapeAndLinkUrls(htmlentities($_GET['msg'], ENT_NOQUOTES, "UTF-8")) : ".";
	Smilify($msg);
	$line = "<p><span class=\"name\" style=\"color:".$c."\">".$nick .": </span><span class=\"txt\">$msg</span></p>";
	fwrite($f,$line."\r\n");
	fclose($f);

	echo html_entity_decode(stripslashes($line));

} else if (isset($_GET['all'])) {
	
	$flag = file('msg.html');
    $content = "";
    foreach ($flag as $value) {
	    $content .= html_entity_decode(stripslashes($value));
	}
    echo $content;

}

	
?>

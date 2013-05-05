<?php 

if(!@file_exists('config.php') ) {
  $links = array();  
} else {
   include_once('config.php');
}

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

    $replace = array();
    foreach ($smilies as $smiley => $imgName)
    {
        array_push($replace, 
        '<img  height="14px" src="imgs/smiley_emoticons_'.$imgName.'.gif" alt="'.$smiley.'"  />');
    }
    $subject = str_replace(array_keys($smilies), $replace, $subject);
}

function buildline($nick, $text)
{
    global $usercolors;

	$c = "black";
	if (isset($usercolors[$nick]))
	  $c = $usercolors[$nick];
	
	$new_date = strtotime(time()) + strtotime("+0 hours");

	$nick = $nick . " (" . date('H:i', $new_date).")";
	$msg  = isset($_GET['msg']) ? htmlEscapeAndLinkUrls(htmlentities($_GET['msg'], ENT_NOQUOTES, "UTF-8")) : ".";
	Smilify($msg);
	$line = "<p><span class=\"name\" style=\"color:".$c."\">".$nick .": </span><span class=\"txt\">$msg</span></p>";
	return $line;
}

?>

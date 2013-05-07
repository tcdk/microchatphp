<?php 

$lineformat = "<p><span class=\"name\" style=\"color: %(color)s\">%(nick)s </span><span class=\"txt\">%(msg)s</span></p>";
if(!@file_exists('config.php') ) {
  $links = array();  
} else {
   include_once('config.php');
}


/**
 * version of sprintf for cases where named arguments are desired (python syntax)
 *
 * TC: Taken from http://www.php.net/manual/en/function.sprintf.php 
 *
 * with sprintf: sprintf('second: %2$s ; first: %1$s', '1st', '2nd');
 *
 * with sprintfn: sprintfn('second: %(second)s ; first: %(first)s', array(
 *  'first' => '1st',
 *  'second'=> '2nd'
 * ));
 *
 * @param string $format sprintf format string, with any number of named arguments
 * @param array $args array of [ 'arg_name' => 'arg value', ... ] replacements to be made
 * @return string|false result of sprintf call, or bool false on error
 */
function sprintfn ($format, array $args = array()) {
    // map of argument names to their corresponding sprintf numeric argument value
    $arg_nums = array_slice(array_flip(array_keys(array(0 => 0) + $args)), 1);

    // find the next named argument. each search starts at the end of the previous replacement.
    for ($pos = 0; preg_match('/(?<=%)\(([a-zA-Z_]\w*)\)/', $format, $match, PREG_OFFSET_CAPTURE, $pos);) {
        $arg_pos = $match[0][1];
        $arg_len = strlen($match[0][0]);
        $arg_key = $match[1][0];

        // programmer did not supply a value for the named argument found in the format string
        if (! array_key_exists($arg_key, $arg_nums)) {
            user_error("sprintfn(): Missing argument '${arg_key}'", E_USER_WARNING);
            return false;
        }

        // replace the named argument with the corresponding numeric one
        $format = substr_replace($format, $replace = $arg_nums[$arg_key] . '$', $arg_pos, $arg_len);
        $pos = $arg_pos + strlen($replace); // skip to end of replacement for next iteration
    }

    return vsprintf($format, array_values($args));
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

function addlinetofile($line)
{
	if (file_exists('msg.html')) {
		$f = fopen('msg.html',"a+");
	} else {
		$f = fopen('msg.html',"w+");
	}
	fwrite($f,$line."\r\n");
	fclose($f);
}

function buildline($nick, $text)
{
    global $usercolors, $lineformat;


	$c = "black";
	if (isset($usercolors[$nick]))
	  $c = $usercolors[$nick];
	
	$new_date = strtotime(time()) + strtotime("+0 hours");

	$nick = $nick . " (" . date('H:i', $new_date).")";
    $msg  = isset($_GET['msg']) ? htmlEscapeAndLinkUrls(htmlentities($_GET['msg'], ENT_NOQUOTES, "UTF-8")) : ".";
    Smilify($msg);
    $values = array('color' => $c, 'nick' => $nick, 'msg' => $msg);
    $line = sprintfn($lineformat, $values);
	return $line;
}

?>

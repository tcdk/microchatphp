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
        '&lt;3' => 'heart'
        
    );

    $replace = array();
    foreach ($smilies as $smiley => $imgName)
    {
        array_push($replace, 
        '<img  height="14px" src="imgs/smiley_emoticons_'.$imgName.'.gif" alt="'.$smiley.'"  />');
    }
    $subject = str_replace(array_keys($smilies), $replace, $subject);
}

function makeinlinelinksandimages($msg)
{ // takes from http://stackoverflow.com/questions/8027023/regex-php-auto-detect-youtube-image-and-regular-links

   return preg_replace_callback('#(?:https?://\S+)|(?:www.\S+)|(?:\S+\.\w+)#', function($arr)
{
    $url = parse_url($arr[0]);
    if(preg_match('#\.(png|jpg|jpeg|gif)$#', $url['path']))
    {
        return '<img style="max-width:313px;" src="'. $arr[0] . '" />';
    }
    if (strpos($arr[0], 'http') !== 0) 
    {
        $arr[0] = 'http://' . $arr[0];
    }
    $url = parse_url($arr[0]);
    // images
   
    // instagram
    if(in_array($url['host'], array('instagram.com')))
    {
        $p = explode('/', $url['path']);
        if ($p[1] == 'p')
        {
          return sprintf('<img style="max-width:313px;" src="http://instagram.com/p/%s/media" />', $p[2]);
        }  
    }

    // youtube
    if(in_array($url['host'], array('www.youtube.com', 'youtube.com'))
      && $url['path'] == '/watch'
      && isset($url['query']))
    {
        parse_str($url['query'], $query);
        return sprintf('<iframe class="embedded-video" src="http://www.youtube.com/embed/%s" allowfullscreen></iframe>', $query['v']);
    }
    //links
    return sprintf('<a href="%1$s">%1$s</a>', $arr[0]);
}, $msg);
}

function resizeimage( $filename, $newfilename, $maxw, $maxh, $quality=85, $forceext = '', $resizegif = false)
{
  $ext = strtolower( pathinfo( $filename, PATHINFO_EXTENSION ) );
  switch($ext)
  {
    case 'jpeg':
    case 'jpe':
    case 'jpg':
      $srcim = imagecreatefromjpeg( $filename );
      break;
    case 'gif':
      if ($resizegif)
      {
        $srcim = imagecreatefromgif( $filename );
      } else
      {  // don't resize gifs - destroys animation
        copy($filename, $newfilename);
        return file_exists($newfilename); 
      }
      break;
    case 'png':
      $srcim = imagecreatefrompng( $filename );
      break;
    default:
      return false;
  }
   // rotate if exif says so 
  $exif = exif_read_data($filename);
  if(!empty($exif['Orientation'])) {
    switch($exif['Orientation']) {
        case 8:
            $srcim = imagerotate($srcim,90,0);
            break;
        case 3:
            $srcim = imagerotate($srcim,180,0);
            break;
        case 6:
            $srcim = imagerotate($srcim,-90,0);
            break;
    }
  }
  $ow = imagesx( $srcim );
  $oh = imagesy( $srcim );
  $wscale = $maxw / $ow;
  $hscale = $maxh / $oh;
  $scale = min( $hscale, $wscale );
  $nw = round( $ow * $scale, 0 );
  $nh = round( $oh * $scale, 0 );
  $dstim = imagecreatetruecolor( $nw, $nh );
  imagecopyresampled( $dstim, $srcim, 0, 0, 0, 0, $nw, $nh, $ow, $oh );

 


  if ($forceext)
     $ext = $forceext;
  switch($ext)
  {
    case 'jpeg':
    case 'jpe':
    case 'jpg':
      imagejpeg( $dstim, $newfilename, $quality );
      break;
    case 'gif':
      imagegif( $dstim, $newfilename );
      break;
    case 'png':
      $png_q = floor( abs( $quality / 10 - 9.9 ) );
      imagepng( $dstim, $newfilename, $png_q );
      break;
    default:
      return false;
  }
  imagedestroy( $dstim );
  imagedestroy( $srcim );
  return file_exists($newfilename);
}

function addline2file($line)
{

			if (file_exists('msg.html')) {
				$msgf = fopen('msg.html',"a+");
			} else {
				$msgf = fopen('msg.html',"w+");
			}
			fwrite($msgf,$line."\r\n");
	    	fclose($msgf);
			
}

function writetolog($txt = '')
{
	global $timedelta;


	$new_date = strtotime(time()) + strtotime($timedelta);

	$logfilename = 'log/'. @date('dmY-H', $new_date).'.log';
	if (file_exists($logfilename)) {
		$f = fopen($logfilename,"a+");
	} else {
		$f = fopen($logfilename,"w+");
	}

	$nick = isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] : $_SERVER['REMOTE_USER'];
	fwrite($f,$nick." : ".@date('[d/m/Y:H:i:s]', $new_date)." ".$txt."\r\n");
}

function buildline($nick, $text)
{
    global $usercolors, $lineformat, $timedelta;
	$c = "black";
	if (isset($usercolors[$nick]))
	  $c = $usercolors[$nick];
	
	$new_date = strtotime(time()) + strtotime($timedelta);

	$nick = $nick . " (" . date('H:i', $new_date).")";
    $msg = isset($text) ? $text : ".";
    $msg = htmlentities($msg, ENT_NOQUOTES, "UTF-8");
    $msg = makeinlinelinksandimages($msg);
    Smilify($msg);
    
    $values = array('color' => $c, 'nick' => $nick, 'msg' => $msg);
    $line = sprintfn($lineformat, $values);
    return $line;
}

?>

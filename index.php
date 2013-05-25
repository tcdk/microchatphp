<?php
header('Content-type: text/html; charset=utf-8');


$useauthuser = true;

if(!@file_exists('config.php') ) {
  $links = array();  
} else {
   include_once('config.php');
}

// Normally a .htaccess and .htpasswd file would force an auth basic
// but if it doesnt work, the systeme still need a php auth user to work
if (($useauthuser) and (!isset($_SERVER['PHP_AUTH_USER']))) {
    header('WWW-Authenticate: Basic realm="My Realm"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'Text to send if user hits Cancel button';
    exit;
}
session_start();


function createForm(){
?>
      <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <table align="center">
          <tr><td colspan="2">Please select a chat name:</td></tr>
          <tr><td>Your name : </td>
          <td><input class="text" type="text" name="name" /></td></tr>
          <tr><td colspan="2" align="center">
             <input class="text" type="submit" name="submitBtn" value="Login" />
          </td></tr>
        </table>
      </form>
<?php
}

// a bit messy with the u and nickname mixup
if (isset($_GET['u'])){
   unset($_SESSION['nickname']);
}

// Process login info
if (isset($_POST['submitBtn'])){
      $name    = isset($_POST['name']) ? $_POST['name'] : "Unnamed";
      $_SESSION['nickname'] = $name;
}

$nickname = isset($_SESSION['nickname']) ? $_SESSION['nickname'] : "Hidden";   
if (($useauthuser) and (isset($_SERVER['PHP_AUTH_USER']))) {
  $nickname = $_SERVER['PHP_AUTH_USER'];
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "DTD/xhtml1-transitional.dtd">
<html>
<head>
   <title>chat</title>
   <link href="style/style2.css" rel="stylesheet" type="text/css" />
   
    <script language="javascript" type="text/javascript">
    <!--
      var httpObject = null;
      var timerID = 0;
      var nickName = "<?php echo $nickname; ?>";

      // Get the HTTP Object
      var link = "";

      function getHTTPObject(){
         if (window.ActiveXObject) return new ActiveXObject("Microsoft.XMLHTTP");
         else if (window.XMLHttpRequest) return new XMLHttpRequest();
         else {
            alert("Your browser does not support AJAX.");
            return null;
         }
      }   

	  function addlinetoMsg(s)
	  {
	        var objDiv = document.getElementById("result");
          objDiv.innerHTML += s;
          objDiv.scrollTop = objDiv.scrollHeight;
          d =  document.getElementById('msg');
          d.style.backgroundImage ='';
    }

	  function setMsg(s)
	  {
	        var objDiv = document.getElementById("result");
          objDiv.innerHTML = s;
          objDiv.scrollTop = objDiv.scrollHeight;
          d =  document.getElementById('msg');
          d.style.backgroundImage ='';

    }
      // Change the value of the outputText field
      function handleresponse(){
         if(httpObject.readyState == 4){
            var response = httpObject.responseText;
            if (response)
            {
              z = response.substr(0,3); // the command
              response = response.substr(4); // rest
              if (z == 'one')
                addlinetoMsg(response);
              if (z == 'all') {
                setMsg(response); 
              } 
              if (z == 'nul') {
                d =  document.getElementById('msg');
          		d.style.backgroundImage ='';
              }
            }
         }
      }
      

      // clear the input field
      // if a paramter is added, the input field gets the value appended
      // and the aret moved to the end of the field
      function clearmsg(s){
	     var inpObj = document.getElementById("msg");
		 if (s){
		   inpObj.value += " "+ s + " ";
		   if (typeof inpObj.selectionStart == "number") {
              inpObj.selectionStart = inpObj.selectionEnd = inpObj.value.length;
           }
		 } else
		 {
          inpObj.value = "";
		 }	
         inpObj.focus();
	  }
	  
	  function sendtoserver(s)
	  {

		 httpObject = getHTTPObject();
         if (httpObject != null) {
		      httpObject.open("GET", s , true);
              httpObject.onreadystatechange = handleresponse;
              httpObject.send(null);
         }
		
	  }

      // Implement business logic    
      function doWork(){
		  d =  document.getElementById('msg');
		  if (d != null) {
              link = "message.php?nick="+nickName+"&msg="+d.value;
              clearmsg();
              d.style.backgroundImage  ='url(ajax-loader.gif)';
              sendtoserver(link);
          }
      }

      // Implement business logic    
      function doReload(){    
         var randomnumber=Math.floor(Math.random()*10000);
         link = "message.php?all=1&rnd="+randomnumber;
         sendtoserver(link);
      }

      // refresh
      function UpdateTimer() {
         if (document.getElementById('msg').value == '') {
           doReload();   
         }
         timerID = setTimeout("UpdateTimer()", 5000);
      }
    
    
      function keypressed(e){
         if(e.keyCode=='13'){
            doWork();
         } 
      }
    //-->
    </script>   
</head>
<body onload="UpdateTimer();">
    <div id="main" align=left valign=left>
      <div style="float:right">
		  <ul>
			<?php
			   foreach($links as $key => $value ) { 
                 echo "<li><a href='". $value. "' target='_blank'>" . $key ."</a></li>";
               }
			?>
		  </ul>
	  </div>
<?php 

  if ((!$useauthuser) and (!isset($_SESSION['nickname']))) { 
      createForm();
  } else
  { 
  	
      if (($useauthuser) and (isset($_SERVER['PHP_AUTH_USER']))) {
      	$name = $_SERVER['PHP_AUTH_USER'];
      } else {
        $name    = isset($_POST['name']) ? $_POST['name'] : "Unnamed";
      }
      echo "<div>Hej ". $name ."</div>";
      $_SESSION['nickname'] = $name;
    ?>
  
     <div id="result">
     <?php 
        $data = file("msg.html");
        foreach ($data as $line) {
        	echo html_entity_decode(stripslashes($line));
        }
     ?>
      </div>
      <div id="sender" onkeyup="keypressed(event);" style="vertical-align:text-top">
         Msg: 
		  <textarea cols="30" rows="5" name="msg" id="msg" 
               style="background-repeat:no-repeat;background-image:'url(ajax-loader.gif)" ></textarea>
         <button onclick="doWork();">Send</button>
         <div style="float:right">
         <button onclick="clearmsg();">Clear</button>
         <img src="imgs/smiley_emoticons_heart.gif" onclick="clearmsg('<3');">&nbsp;
         <img src="imgs/smiley_emoticons_smile.gif" onclick="clearmsg(':)');">&nbsp;
         <img src="imgs/smiley_emoticons_razz.gif" onclick="clearmsg(':p');">&nbsp;
         <img src="imgs/smiley_emoticons_bussi.gif" onclick="clearmsg(':*');">
         </div>
      </div>   
<?php            
    }

?>
    </div>
</body>   

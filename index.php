<?php
header('Content-type: text/html; charset=utf-8');

$useauthuser = true;

if (($useauthuser) and (!isset($_SERVER['PHP_AUTH_USER']))) {
    header('WWW-Authenticate: Basic realm="My Realm"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'Text to send if user hits Cancel button';
    exit;
}
session_start();
// unset($_SESSION['realm']);
// session_destroy();



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
   <title>r</title>
   <link href="style/style2.css" rel="stylesheet" type="text/css" />
   
    <script language="javascript" type="text/javascript">
    <!--
      var httpObject = null;
      var timerID = 0;
      var timerinms = 5000;
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

      // Change the value of the outputText field
      function setOutput(){
         if(httpObject.readyState == 4){
            var response = httpObject.responseText;
            var objDiv = document.getElementById("result");
            objDiv.innerHTML += response;
            objDiv.scrollTop = objDiv.scrollHeight;
            var inpObj = document.getElementById("msg");
            inpObj.value = "";
            inpObj.focus();
         }
      }
      
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

      // Change the value of the outputText field
      function setAll(){
         if(httpObject.readyState == 4){
            var response = httpObject.responseText;
            if (response !='.')
            {
              var objDiv = document.getElementById("result");
              objDiv.innerHTML = response;
              objDiv.scrollTop = objDiv.scrollHeight;
            }
         }
      }

      // Implement business logic    
      function doWork(){     
         httpObject = getHTTPObject();
         if (httpObject != null) {
			d =  document.getElementById('msg');
			if (d != null) {
              link = "message.php?nick="+nickName+"&msg="+d.value;
              httpObject.open("GET", link , true);
              httpObject.onreadystatechange = setOutput;
              httpObject.send(null);
		    }
         }
         
      }

      // Implement business logic    
      function doReload(){    
         httpObject = getHTTPObject();
         var randomnumber=Math.floor(Math.random()*10000);
         if (httpObject != null) {
            link = "message.php?all=1&rnd="+randomnumber;
            httpObject.open("GET", link , true);
            httpObject.onreadystatechange = setAll;
            httpObject.send(null);
         }
      }

      function UpdateTimer() {
         if (document.getElementById('msg').value == '') {
           doReload();   
         }
         timerID = setTimeout("UpdateTimer()", 5000);
      }
    
    
      function keypressed(e){
         if(e.keyCode=='13'){
            doWork();
         } else
         {
	    	d =  document.getElementById('msg');
			if (d != null) {
		     // d.rows += 1;
		    }
         } 
      }
    //-->
    </script>   
</head>
<body onload="UpdateTimer();">
    <div id="main" align=left valign=left>
      <div style="float:right">
		  <ul>
			  <li><a href="http://migmigmigmig.tumblr.com" target="_blank">mig⁴</a></li>
			  <li><a href="http://migmigmigmig.tumblr.com/submit" target="_blank">Upload</a></li>
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
         Skriv: <!--input type="text" name="msg" style="min-width:250; width:75%; height:35px"
                id="msg" /-->
		 <textarea cols="30" rows="5" name="msg" id="msg" ></textarea>
         <button onclick="doWork();doReload();">Send</button>
         <div style="float:right">
         <button onclick="clearmsg();">Slet</button>
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

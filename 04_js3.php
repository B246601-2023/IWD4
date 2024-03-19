<?php
echo <<<_HEAD
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
   <meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>This is 04_js5.php</title>
<meta name="keywords" content="" />
<meta name="description" content="" />
<link href="04_style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
function displayMessage()
{
document.getElementById("mypara1").innerHTML="Type something to identify yourself!";
}

function delMessage()
{
document.getElementById("mypara1").innerHTML="";
}
</script>
</head>
<img border="0" hspace="0" src="./images/test_titlebar.gif" width="800" height="20">
<body>
_HEAD;

echo <<<_EOF
   <form  action="04_js5.php" method="post">
   <p id="mypara1"></p>
   <p>Your ID  <input type="text" name="userid" onfocus="displayMessage()" onblur="delMessage()"/></p>
   <p><input type="submit" value="go" /></p>
  </form>
_EOF;

echo <<<_TAIL
</body>
</html>
_TAIL;
?>

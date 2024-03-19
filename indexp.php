<?php
session_start();
if(isset($_POST['fn']) &&
   isset($_POST['sn']))
  {
    echo<<<_HEAD1
    <html>
    <body>
_HEAD1;
    include 'menuf.php';
    $_SESSION['forname'] = $_POST['fn'];
    $_SESSION['surname'] = $_POST['sn'];
    $smask =  $_SESSION['supmask'];
echo <<<_TAIL1
<pre>
   Mask Value $smask
</pre>
</body>
</html>
_TAIL1;
    } else { 
  header('location: https://bioinfmsc8.bio.ed.ac.uk/${USER}/complib.php');
  }
?>

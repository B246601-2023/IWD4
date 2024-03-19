<?php
session_start();
include 'redir.php';
echo<<<_HEAD1
<html>
<body>
_HEAD1;
$fn = $_SESSION['forname'];
echo <<<_MAIN1
    <pre>
    Goodbye  $fn;
    You have now exited Complib
    </pre>
_MAIN1;
$_SESSION = array();
if( session_id() != "" || isset($_COOKIE[session_name()]))
  setcookie(session_name(), '', time() - 2592000, '/');
  session_destroy();
echo <<<_TAIL1
</pre>
</body>
</html>
_TAIL1;

?>

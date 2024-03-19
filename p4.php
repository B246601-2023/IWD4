<?php
session_start();
include 'redir.php';
echo<<<_HEAD1
<html>
<body>
_HEAD1;
include 'menuf.php';
echo <<<_MAIN1
    <pre>
This is the Correlation Page  (not Complete)
    </pre>
_MAIN1;
echo <<<_TAIL1
</body>
</html>
_TAIL1;

?>

<?php
session_start();
include 'redir.php';
require_once 'login.php';
echo <<<_HEAD1
<html>
<body>
_HEAD1;
include 'menuf.php';
echo <<<_MAIN1
    <pre>
This is the initial version of a property search page
    </pre>
    </pre><form action="props_out.php" method="post">
<pre>
   MW <input type="radio" name="tgval" value="mw" checked/>
 TPSA <input type="radio" name="tgval" value="TPSA"/>
XlogP <input type="radio" name="tgval" value="XlogP"/>
Value <input type="text" name="cval"/>

<input type="submit" value="Submit my request" />
</pre></form>
</body>
</html>
_MAIN1;
?>
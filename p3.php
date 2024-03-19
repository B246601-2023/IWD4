<?php
session_start();
include 'redir.php';
require_once 'login.php';
echo<<<_HEAD1
<html>
<body>
_HEAD1;
include 'menuf.php';
$dbfs = array("natm","ncar","nnit","noxy","nsul","ncycl","nhdon","nhacc","nrotb","mw","TPSA","XLogP");
$nms = array("n atoms","n carbons","n nitrogens","n oxygens","n sulphurs","n cycles","n H donors","n H acceptors","n rot bonds","mol wt","TPSA","XLogP");
echo <<<_MAIN1
    <pre>
This is the Statistics Page  (not Complete)
    </pre>
_MAIN1;
if(isset($_POST['tgval'])) 
   {
     $chosen = 0;
     $tgval = $_POST['tgval'];
     for($j = 0 ; $j <sizeof($dbfs) ; ++$j) {
       if(strcmp($dbfs[$j],$tgval) == 0) $chosen = $j; 
     } 
     printf(" Statistics for %s (%s)<br />\n",$dbfs[$chosen],$nms[$chosen]);
// THE CONNECTION AND QUERY SECTIONS NEED TO BE MADE TO WORK FOR PHP 8 USING PDO... //
     //Your mysql and statistics calculation goes here
     $db_server = mysql_connect($db_hostname,$db_username,$db_password);
     if(!$db_server) die("Unable to connect to database: " . mysql_error());
     mysql_select_db($db_database,$db_server) or die ("Unable to select database: " . mysql_error());     
     $query = sprintf("select AVG(%s), STD(%s) from Compounds",$dbfs[$chosen],$dbfs[$chosen]);
     $result = mysql_query($query);
     if(!$result) die("unable to process query: " . mysql_error());
     $row = mysql_fetch_row($result);
     printf(" Average %f  Standard Dev %f <br />\n",$row[0],$row[1]);
   }
echo '<form action="p3.php" method="post"><pre>';
for($j = 0 ; $j <sizeof($dbfs) ; ++$j) {
  if($j == 0) {
     printf(' %15s <input type="radio" name="tgval" value="%s" checked"/>',$nms[$j],$dbfs[$j]);
  } else {
     printf(' %15s <input type="radio" name="tgval" value="%s"/>',$nms[$j],$dbfs[$j]);
  }
  echo "\n";
} 
echo '<input type="submit" value="OK" />';
echo '</pre></form>';
echo <<<_TAIL1
</body>
</html>
_TAIL1;

?>

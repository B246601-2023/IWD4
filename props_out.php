<?php
session_start();
require_once 'login.php';
include 'redir.php';
echo <<<_HEAD1
<html>
<body>
_HEAD1;
include 'menuf.php';

// THE CONNECTION AND QUERY SECTIONS NEED TO BE MADE TO WORK FOR PHP 8 USING PDO... 

$db_server = mysql_connect($db_hostname,$db_username,$db_password);
if(!$db_server) die("Unable to connect to database: " . mysql_error());
mysql_select_db($db_database,$db_server) or die ("Unable to select database: " . mysql_error());     
$query = "select * from Manufacturers";
$result = mysql_query($query);
if(!$result) die("unable to process query: " . mysql_error());
$rows = mysql_num_rows($result);
$manarray = array();
for($j = 0 ; $j < $rows ; ++$j)
  {
    $row = mysql_fetch_row($result);
    $manarray[$j] = $row[1];
  }
echo <<<_MAIN1
    <pre>
This is the initial property retrieval page

// THE CONNECTION AND QUERY SECTIONS NEED TO BE MADE TO WORK FOR PHP 8 USING PDO... 

    </pre>
_MAIN1;

// Here we generate our query
if (($_POST['tgval'] != "") && ($_POST['cval']!="")) {
    $mychoice=get_post('tgval');
    $myvalue=get_post('cval');
    $compsel = "select * from Compounds where ";
    if($mychoice == "mw") {
      $compsel = $compsel."( mw > ".($myvalue - 1.0)." and  mw < ".($myvalue + 1.0).")";
    }
    if($mychoice == "TPSA") {
      $compsel = $compsel."( TPSA > ".($myvalue - 0.1)." and  TPSA < ".($myvalue + 0.1).")";
    }
    if($mychoice == "XlogP") {
      $compsel = $compsel."( XlogP > ".($myvalue - 0.1)." and  XlogP < ".($myvalue + 0.1).")";
    }
    echo "<pre>";
    //    echo $compsel;
    echo "\n";
    $result = mysql_query($compsel);
    if(!$result) die("unable to process query: " . mysql_error());
    $rows = mysql_num_rows($result);

 if($rows > 10000) {
      echo "Too many results ",$rows," Max is 10000\n";
    } else  {
      echo <<<TABLESET_
<table border="1">
  <tr>
    <td>CAT Number</td>
    <td>Manufacturer</td>
    <td>Property</td>
  </tr>
TABLESET_;

// This is the results processing section, which also needs to be recoded for PHP 8 PDO 
      for($j = 0 ; $j < $rows ; ++$j)
  {
    echo "<tr>";
    $row = mysql_fetch_row($result);
    //  Maybe modify this line (see below)
    printf("<td>%s</td> <td>%s</td>", $row[11],$manarray[$row[10] - 1]);
    if($mychoice == "mw") {
       printf("<td>%s</td> ", $row[12]);
    }
    if($mychoice == "TPSA") {
       printf("<td>%s</td> ", $row[13]);
    }
    if($mychoice == "XlogP") {
       printf("<td>%s</td> ", $row[14]);
    }     
          echo "</tr>";
  }
      echo "</table>";
    }
  } else {
    echo "No Query Given\n";
  }
echo "</pre>"; 
echo <<<_TAIL1
</body>
</html>
_TAIL1;

// Here we use a function
function get_post($var)
{
  return mysql_real_escape_string($_POST[$var]);
}
?>

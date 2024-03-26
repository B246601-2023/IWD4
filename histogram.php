<?php
session_start();
include 'redir.php';
require_once 'login.php'; 
echo<<<_HEAD1
<html>
<body>
_HEAD1;
include 'menuf.php';
// Set labels and names

$dbfs = array("natm","ncar","nnit","noxy","nsul","ncycl","nhdon","nhacc","nrotb","mw","TPSA","XLogP"); 
$nms = array("n atoms","n carbons","n nitrogens","n oxygens","n sulphurs","n cycles","n H donors","n H acceptors","n rot bonds","mol wt","TPSA","XLogP");
echo <<<_MAIN1
    <pre>
This is the histogram page
    </pre>
_MAIN1;
// Check if form is filled in

if(isset($_POST['tgval']))                      
   {
     $chosen = 0;
     $tgval = $_POST['tgval'];
// Figure out which radio button was chosen

     for($j = 0 ; $j <sizeof($dbfs) ; ++$j) {
       if(strcmp($dbfs[$j],$tgval) == 0) $chosen = $j;
     }
// THE CONNECTION AND QUERY SECTIONS NEED TO BE MADE TO WORK FOR PHP 8 USING PDO... //
     try {
          $pdo = new PDO("mysql:host=$db_hostname;dbname=$db_database", $db_username, $db_password);
          $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
          echo "Connected successfully<br>";

     // Prepare and execute query to get manufacturers
          $stmt = $pdo->prepare("SELECT * FROM Manufacturers");
          $stmt->execute();

     // Fetch all manufacturers
          $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
          $smask = $_SESSION['supmask'];
          $firstmn = false;
          $mansel = "(";

          foreach ($rows as $row) {
               $sid = $row['id']; // Assuming 'id' is the column name for manufacturer ID
               $snm = $row['name']; // Assuming 'name' is the column name for manufacturer name
               $sact = 0;
               $tvl = 1 << ($sid - 1);

               if ($tvl == ($tvl & $smask)) {
                    $sact = 1;
                if ($firstmn) {
                    $mansel .= " OR ";
               }
               $firstmn = true;
               $mansel .= "ManuID = $sid";
               }
          }

          $mansel .= ")";
          if (!$firstmn) {
          // No manufacturer selected
               $mansel = "(ManuID IS NULL)"; // Adjust as necessary to handle no selection
          }
     
     // Further queries can be executed using $pdo
     // For example, selecting data based on the manufacturer selection:
     // $query = "SELECT * FROM SomeTable WHERE $mansel";

     } catch(PDOException $e) {
          echo "Connection failed: " . $e->getMessage();
     }
// Prepare command to run external program

     $comtodo = "python3 histog.py ".$dbfs[$chosen]." \"".$nms[$chosen]."\" \"".$mansel."\"";
     print($comtodo);
// Run command and capture output converting to base64 encoding

     $rawOutput = shell_exec($comtodo);
     if ($rawOutput !== null) {
          $output = base64_encode($rawOutput);
          echo <<< _IMGPUT
          <pre>
          <img src="data:image/png;base64,$output" />
          </pre>
     _IMGPUT;
     } else {
          echo "Error executing command or command produced no output.";
     }
}
// Set up the form

echo '<form action="histogram.php" method="post"><pre>';                                            
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
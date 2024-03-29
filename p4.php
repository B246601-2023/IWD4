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
This is the Correlation Page  (not Complete)
    </pre>
_MAIN1;
if(isset($_POST['tgval']) && isset($_POST['tgvalb'])) 
   {
    $chosen = 0;
    $tgval = $_POST['tgval'];
    $tgvalb = $_POST['tgvalb'];
    $mansel = "(";
    for($j = 0 ; $j <sizeof($dbfs) ; ++$j) {
      if(strcmp($dbfs[$j],$tgval) == 0) $chosen = $j; 
    } 
    for($j = 0 ; $j <sizeof($dbfs) ; ++$j) {
      if(strcmp($dbfs[$j],$tgvalb) == 0) $chosenb = $j;
    }
    try {
        // 使用PDO连接到MySQL数据库
        $pdo = new PDO("mysql:host=$db_hostname;dbname=$db_database;charset=utf8", $db_username, $db_password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // 准备并执行查询
        $query = $pdo->prepare("SELECT AVG(".$dbfs[$chosen]."), STD(".$dbfs[$chosen].") FROM Compounds");
        $query->execute();

        // 获取并输出结果
        $row = $query->fetch(PDO::FETCH_NUM);
        if ($row) {
            printf(" Average %f  Standard Dev %f <br />\n", $row[0], $row[1]);
        }
    } catch (PDOException $e) {
        die("Unable to connect to database: " . $e->getMessage());
    }
    $mansel = $mansel.")";
    $comtodo = "./correlate3.py ".$dbfs[$chosen]." ".$dbfs[$chosenb]." \"".$mansel."\"";
    printf(" Correlation for %s (%s) vs %s (%s) \n",$dbfs[$chosen],$nms[$chosen],$dbfs[$chosenb],$nms[$chosenb]);
    $rescor = system($comtodo);
    printf("\n");
   }
echo '<form action="p4.php" method="post"><pre>';
for($j = 0 ; $j <sizeof($dbfs) ; ++$j) {
    if($j == 0) {
        printf(' %15s <input type="radio" name="tgval" value="%s" checked"/> %15s <input type="radio" name="tgvalb" value="%s" checked"/>', 
        $nms[$j],$dbfs[$j],$nms[$j],$dbfs[$j]);
    }
    else {
        printf(' %15s <input type="radio" name="tgval" value="%s"/>  %15s <input type="radio" name="tgvalb" value="%s"/>',$nms[$j],$dbfs[$j],$nms[$j],$dbfs[$j]);
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

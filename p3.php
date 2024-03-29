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
            printf(" Average %f  Standard Dev %f for all compounds <br />\n", $row[0], $row[1]);
        }

        //尝试访问部分数据
        if (isset($_SESSION['supmask']) && isset($_SESSION['id'])) {
            $manuID = $_SESSION['supmask'];
            $ids = $_SESSION['id'];
            // 打印session to check
            //echo "<pre>"; // 使用<pre>标签以便格式化输出
            //print_r($_SESSION); // 打印所有session数据
            //echo "</pre>";

            $inQuery = implode(',', array_fill(0, count($ids), '?'));
            $sql = "SELECT AVG(".$dbfs[$chosen]."), STD(".$dbfs[$chosen].") FROM Compounds WHERE ManuID = ? AND id IN ($inQuery)";
            $query = $pdo->prepare($sql);
            $params = array_merge([$manuID], $ids);
            $query->execute($params);
    
            // 获取并输出结果
            $row = $query->fetch(PDO::FETCH_NUM);
            if ($row) {
                printf("Average %f  Standard Dev %f for selected compounds <br />\n", $row[0], $row[1]);
            }
        } else {
            echo "ManuID or id not set in session.";
        }

        
    } catch (PDOException $e) {
        die("Unable to connect to database: " . $e->getMessage());
    }
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

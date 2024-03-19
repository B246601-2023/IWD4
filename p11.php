<?php
session_start();
require_once 'login.php'; // 包含数据库连接信息：$db_hostname, $db_username, $db_password, $db_database
include 'redir.php';
echo<<<_HEAD1
<html>
<body>
_HEAD1;
include 'menuf.php';

try {
    // 使用PDO连接到MySQL数据库
    $pdo = new PDO("mysql:host=$db_hostname;dbname=$db_database;charset=utf8", $db_username, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // 执行查询
    $query = "SELECT * FROM Manufacturers";
    $result = $pdo->query($query);

    $sid = [];
    $snm = [];
    $sact = [];
    $smask = $_SESSION['supmask'] ?? 0; // 使用null合并运算符以兼容未设置的情况

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $sid[] = $row['id']; // 假设第一个字段是id
        $snm[] = $row['name']; // 假设第二个字段是name
        $tvl = 1 << ($row['id'] - 1);
        $sact[] = ($tvl == ($tvl & $smask)) ? 1 : 0;
    }
    $rows = count($sid); // 获取行数

    if(isset($_POST['supplier'])) {
        $supplier = $_POST['supplier'];
        $nele = sizeof($supplier);
        $sact = array_fill(0, $rows, 0); // 重置激活状态
        foreach($supplier as $supp) {
            $key = array_search($supp, $snm);
            if($key !== false) $sact[$key] = 1;
        }
        $smask = 0;
        foreach($sid as $i => $id) {
            if($sact[$i]) $smask |= 1 << ($id - 1);
        }
        $_SESSION['supmask'] = $smask;
    }

    echo 'Currently selected Suppliers: ';
    foreach($snm as $i => $name) {
        if($sact[$i]) echo "$name ";
    }
    echo '<br><pre> <form action="p1.php" method="post">';
    foreach($snm as $i => $name) {
        echo $name;
        echo ' <input type="checkbox" name="supplier[]" value="';
        echo $name;
        echo "\"";
        if($sact[$i]) echo " checked";
        echo "/>\n";
    }
    echo <<<_TAIL1
 <input type="submit" value="OK" />
</pre></form>
</body>
</html>
_TAIL1;
} catch (PDOException $e) {
    die("Unable to connect to database: " . $e->getMessage());
}
?>

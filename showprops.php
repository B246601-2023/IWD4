<?php
require_once 'login.php';

echo "<html><head></head><body>";
include 'menuf.php';

$dbfs = array("natm", "ncar", "nnit", "noxy", "nsul", "ncycl", "nhdon", "nhacc", "nrotb", "ManuID", "catn", "mw", "TPSA", "XLogP");
$nms = array("n atoms", "n carbons", "n nitrogens", "n oxygens", "n sulphurs", "n cycles", "n H donors", "n H acceptors", "n rot bonds", "ManuID", "catid", "mol wt", "TPSA", "XLogP");
$rowid = array(11, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 12, 13, 14);

if (isset($_GET['cid'])) {
    try {
        // 使用PDO连接到MySQL数据库
        $pdo = new PDO("mysql:host=$db_hostname;dbname=$db_database;charset=utf8mb4", $db_username, $db_password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $cid = $_GET['cid'];
        // 使用预处理语句防止SQL注入
        $stmt = $pdo->prepare("SELECT * FROM Compounds WHERE id = :cid");
        $stmt->execute([':cid' => $cid]);

        echo "<h1>Details for Compound $cid</h1>";
        echo "<table id=\"myTable\" width=\"70%\" border=\"2\" cellspacing=\"1\" align=\"center\"><thead><tr>";
        foreach ($nms as $nm) {
            echo "<th>" . htmlspecialchars($nm) . "</th>";
        }
        echo "</tr></thead><tbody>";

        // 获取查询结果
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            echo "<tr>";
            foreach ($rowid as $index) {
                // 假设$row包含所有需要的字段且$rowid与$dbfs对应
                $fieldname = $dbfs[$index - 1];
                //printf("$fieldname, ");
                echo "<td>" . htmlspecialchars($row[$dbfs[$index - 1]]) . "</td>";
            }
            echo "</tr>";
        }
        echo "</tbody></table>";
    } catch (PDOException $e) {
        die("Unable to connect to database: " . $e->getMessage());
    }
} else {
    echo "<pre>No Compound selected</pre>";
}

echo "</body></html>";
?>

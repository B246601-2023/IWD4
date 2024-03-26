<?php
session_start();
require_once 'login.php'; // 确保这个文件中定义了数据库连接信息
include 'redir.php';

echo "<html><body>";
include 'menuf.php';

try {
    // 使用PDO连接到MySQL数据库
    $pdo = new PDO("mysql:host=$db_hostname;dbname=$db_database;charset=utf8mb4", $db_username, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 查询Manufacturers表
    $stmt = $pdo->query("SELECT * FROM Manufacturers");
    $manarray = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "<pre>This is the initial property retrieval page</pre>";

    if (!empty($_POST['tgval']) && !empty($_POST['cval'])) {
        $mychoice = $_POST['tgval'];
        $myvalue = $_POST['cval'];

        // 构建查询条件
        $condition = match ($mychoice) {
            'mw' => "(mw > :value - 1.0 AND mw < :value + 1.0)",
            'TPSA' => "(TPSA > :value - 0.1 AND TPSA < :value + 0.1)",
            'XlogP' => "(XlogP > :value - 0.1 AND XlogP < :value + 0.1)",
            default => null,
        };

        if ($condition) {
            $query = "SELECT * FROM Compounds WHERE " . $condition;
            $stmt = $pdo->prepare($query);
            $stmt->execute([':value' => $myvalue]);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (count($rows) > 10000) {
                echo "Too many results ", count($rows), " Max is 10000\n";
            } else {
                // 显示结果表格
                echo "<table border=\"1\"><tr><td>CAT Number</td><td>Manufacturer</td><td>Property</td></tr>";
    
                foreach ($rows as $row) {
                    echo "<tr>";
                    //printf("<td>%s</td> <td>%s</td>", htmlspecialchars($row['cat_number']), htmlspecialchars($manarray[$row['manufacturer_id'] - 1]['name']));
                    printf("<td><a href=showprops.php?cid=%s>%s <td>%s</td>", $row['id'],$row['catn'],$manarray[$row['ManuID'] - 1]['name']);
                    if ($mychoice == 'mw' || $mychoice == 'TPSA' || $mychoice == 'XlogP') {
                        printf("<td>%s</td> ", htmlspecialchars($row[$mychoice]));
                    }

                    echo "</tr>";
                }

                echo "</table>";
            }
        } else {
            echo "No valid query condition.\n";
        }
    } else {
        echo "No Query Given\n";
    }

} catch (PDOException $e) {
    die("Unable to connect to database: " . $e->getMessage());
}

echo "</body></html>";
?>


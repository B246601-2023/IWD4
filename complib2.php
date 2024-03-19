<?php
session_start();
require_once 'login.php'; // 确保此文件包含数据库连接信息

try {
    // 使用PDO连接到数据库
    $dsn = "mysql:host=$db_hostname;dbname=$db_database;charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ];
    $pdo = new PDO($dsn, $db_username, $db_password, $options);

    // 执行查询
    $query = "SELECT * FROM Manufacturers";
    $stmt = $pdo->query($query);

    // 处理查询结果
    $rows = $stmt->fetchAll();
    $mask = 0;
    foreach ($rows as $row) {
        $mask = (2 * $mask) + 1;
    }
    $_SESSION['supmask'] = $mask;
} catch (PDOException $e) {
    die("Unable to connect to database: " . $e->getMessage());
}

echo <<<EOP
<html>
<body>
<script>
function validate(form) {
    var fail = "";
    if(form.fn.value =="") fail = "Must Give Forname ";
    if(form.sn.value == "") fail += "Must Give Surname";
    if(fail =="") return true
    else {alert(fail); return false}
}
</script>
<form action="indexp.php" method="post" onSubmit="return validate(this)">
<pre>
     First Name<input type="text" name="fn"/>
     Second Name <input type="text" name="sn"/>
                 <input type="submit" value="go" />
</pre></form>
EOP;

echo <<<TAIL1
</pre>
</body>
</html>
TAIL1;
?>

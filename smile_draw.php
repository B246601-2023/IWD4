<?php
session_start();
include 'redir.php';
include 'menuf.php';
echo <<<_HEAD
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>This is smile_draw.php</title>
<link href="https://bioinfmsc8.bio.ed.ac.uk/Als_stylesheet_2324.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="refresh" content="0; url=./draw_my_smile.php">;
</script>
<script type="text/javascript">
function validate(form) {
     fail = validatefield(form.smile.value)
       if(fail =="") { return true }
         else {alert(fail); return false}
   }
function validatefield(field) {
  if(field == "")  
return "No smile string entered "
else { return "" }
}
</script>
</head>
_HEAD ;

echo <<<_EOF
<body>
<div style="padding-left:100px;">
<h3>Give me a smile please! &#x1F603;</h3>
Credit to <a target="_blank" href="https://smilesdrawer.surge.sh/use.html">https://smilesdrawer.surge.sh/use.html</a>
<br/> <font style="font-size: 20px;">An example for you: [H]OC2=C([H])C([H])=C([H])C([H])=C2(C([H])=NN([H])C(=O)C1=C([H])C([H])=NC([H])=C1([H]))</font>
   <form  action="draw_my_smile.php" method="post" onSubmit="return validate(this)">
   <p>Smile string <input type="text" size="100" name="smile" /> </p>
   <p><input type="Submit" value="Convert smile to structure!" /></p>
  </form>
</div>
_EOF;

require_once 'login.php'; // 保证已经包含了数据库连接信息

$compoundData = []; // 用来存储查询结果的数组

if (isset($_SESSION['id']) && is_array($_SESSION['id'])) {
    try {
        $pdo = new PDO("mysql:host=$db_hostname;dbname=$db_database;charset=utf8", $db_username, $db_password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // 构建IN子句
        $inQuery = implode(',', array_fill(0, count($_SESSION['id']), '?'));
        
        // 准备SQL查询
        $stmt = $pdo->prepare("SELECT cid, smiles FROM Smiles WHERE cid IN ($inQuery)");
        
        // 执行查询
        $stmt->execute($_SESSION['id']);
        
        // 获取查询结果
        $compoundData = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Unable to connect to database: " . $e->getMessage());
    }
}
if (!empty($compoundData)) {
  echo "<table border='1'>";
  echo "<tr><th>Compounds id</th><th>smiles</th></tr>";

  foreach ($compoundData as $compound) {
      echo "<tr>";
      echo "<td>" . htmlspecialchars($compound['cid']) . "</td>";
      echo "<td>" . htmlspecialchars($compound['smiles']) . "</td>";
      echo "</tr>";
  }

  echo "</table>";
}



echo <<<_TAIL
</body>
</html>
_TAIL;
?>

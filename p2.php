<?php
session_start();
require_once 'login.php';
include 'redir.php';
echo<<<_HEAD1
<html>
<body>
_HEAD1;
include 'menuf.php';
// THE CONNECTION AND QUERY SECTIONS NEED TO BE MADE TO WORK FOR PHP 8 USING PDO... //
try {
  $pdo = new PDO("mysql:host=$db_hostname;dbname=$db_database;charset=utf8", $db_username, $db_password);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $stmt = $pdo->query("SELECT * FROM Manufacturers");

  $smask = $_SESSION['supmask'] ?? 0;
  $firstmn = false;
  $mansel = "(";

  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $sid[] = $row['id'];
      $snm[] = $row['name'];
      $sact[] = ($smask & (1 << ($row['id'] - 1))) ? 1 : 0;
      if ($sact[count($sact) - 1]) {
          $mansel .= $firstmn ? " OR " : "";
          $firstmn = true;
          $mansel .= "ManuID = ?";
          $params[] = $row['id'];
      }
  }

  $mansel .= $firstmn ? ")" : "FALSE)"; // 确保WHERE子句始终有效
  $setpar = isset($_POST['natmax']);

  echo <<<_MAIN1
  <pre>
This is the catalogue retrieval Page
  </pre>
_MAIN1;

  if ($setpar) {
      $queryParts = [];
      $queryParams = [];

      // 构建查询条件
      foreach (['natm' => 'nat', 'ncar' => 'ncr', 'nnit' => 'nnt', 'noxy' => 'nox'] as $dbField => $postField) {
          if (!empty($_POST[$postField . 'max']) && !empty($_POST[$postField . 'min'])) {
              $queryParts[] = "($dbField > ? AND $dbField < ?)";
              $queryParams[] = $_POST[$postField . 'min'];
              $queryParams[] = $_POST[$postField . 'max'];
          }
      }

      $query = "SELECT id, catn FROM Compounds WHERE (" . implode(' AND ', $queryParts) . ") AND " . $mansel;
      $stmt = $pdo->prepare($query);
      $stmt->execute(array_merge($queryParams, $params));

      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
      echo "<pre>";
      echo "Number of selected results: ", count($rows), "\n";
      if (count($rows) > 100) {
          echo "Too many results ", count($rows), " Max is 100\n";
      }
      elseif (count($rows) == 0) {
          echo "No results are selected, please change the requirments.";
      }
      else {
        echo "<table border='1'>"; // 开始表格并添加边框，以便更容易看到表格的结构
        echo "<tr><th>ID</th><th>catn</th></tr>"; // 打印表头

        foreach ($rows as $row) {
            echo "<tr>"; // 开始一行
            echo "<td>" . htmlspecialchars($row['id']) . "</td>"; // 打印 ID 单元格
            echo "<td>" . htmlspecialchars($row['catn']) . "</td>"; // 打印 catn 单元格
            echo "</tr>"; // 结束这一行
            $_SESSION['id'] = array_map(function($row) { return $row['id']; }, $rows);
        }

        echo "</table>"; // 结束表格
        //  foreach ($rows as $row) {
        //      echo htmlspecialchars($row['catn']), "\n";
        //      $_SESSION['id'] = array_map(function($row) { return $row['id']; }, $rows);
        //      $_SESSION['catn_values'] = array_map(function($row) { return $row['catn']; }, $rows);
        //  }
      }

      echo "</pre>";
  } 
} catch (PDOException $e) {
  die("Unable to connect to database: " . $e->getMessage());
}


echo <<<_TAIL1
   <form action="p2.php" method="post"><pre>
       Max Atoms      <input type="text" name="natmax"/>    Min Atoms    <input type="text" name="natmin"/>
       Max Carbons    <input type="text" name="ncrmax"/>    Min Carbons  <input type="text" name="ncrmin"/>
       Max Nitrogens  <input type="text" name="nntmax"/>    Min Nitrogens<input type="text" name="nntmin"/>
       Max Oxygens    <input type="text" name="noxmax"/>    Min Oxygens  <input type="text" name="noxmin"/>
                   <input type="submit" value="list" />
</pre></form>

</body>
</html>
_TAIL1;
?>

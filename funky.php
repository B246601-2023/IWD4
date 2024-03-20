<?php
function doQuery($databasechosen,$sql)
 {
	 try {
        require_once 'login.php';
	$database=$databasechosen ;
        $dsn = "mysql:host=127.0.0.1;dbname=$database;charset=utf8mb4";
        $conn = new PDO($dsn, $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo "<br/>Connected you successfully to the <b>$database</b> database!<br/>" ;
	    // Prepared statement method
	    $stmt = $conn->prepare($sql) ;
            $stmt->execute([]);
$arr = $stmt->fetchAll(PDO::FETCH_ASSOC);
$num_rows = count($arr);
$num_col = $stmt->columnCount();
$mask = 0 ;

// query() method

$data2 = $conn->query($sql)->fetchAll(PDO::FETCH_UNIQUE);

echo "<br/>Trying to access the $database db with the query $sql <br><br>" ;

return array($arr,$num_rows,$data2,$num_col) ;
	 } catch(PDOException $e) {
          echo "<br/><br/><b><font color=\"red\">Something bad happened, sorry!</font></b>:<br/>" . $e->getMessage();
	  exit ;

} # catch error option
} # function end
?>

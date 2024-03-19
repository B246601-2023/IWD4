<?php
session_start();
echo <<<_HEAD
<html>
<head>
<title>let_me_in.php</title>
<link rel="stylesheet" href="https://bioinfmsc8.bio.ed.ac.uk/Als_stylesheet_IWD.css">
</head>
<body>
<div style="padding-left: 75px;">
_HEAD;
if(isset($_POST['password'])&& strlen($_POST['password'])>0) {
if(isset($_POST['database']) && strlen($_POST['database'])>5) {$database=$_POST['database'];}else{die("Unlikely database name");}
if(isset($_POST['user']) && strlen($_POST['user'])>3) {$username=$_POST['user'];}else{die("Unlikely user name") ;}
$servername = "127.0.0.1";
$password = $_POST['password'] ;
$shortpass = substr($password,1,3) ;
echo "<div style=\"padding-left: 75px;\">
  <h1>Database access request result</h1>
  ";

echo "Attempting to connect you:
<ul><li>to the <b>$database</b> database</li>
<li> as user <b>$username</b> </li>
<li> with a password that contains the string <i>$shortpass</i> </li></ul>";
try {
        $dsn = "mysql:host=127.0.0.1;dbname=$database;charset=utf8mb4";
        $conn = new PDO($dsn, $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo "\nConnected you successfully to the <b>$database</b> database!<br/>
            \n<p>Hah, <b><font color=\"red\">I know your MySQL password now</font></b>, as you typed <i>...",$shortpass,"...</i> into the box...</p>
            \nInitiating example query (selecting all rows where <code>cathv2.cat</code> is 2)...";
            $sql = 'SELECT DISTINCT * FROM cathv2 WHERE cat = ?';
            $stmt = $conn->prepare($sql) ;
            $stmt->execute([2]);
$arr = $stmt->fetchAll(PDO::FETCH_ASSOC);
$num_rows = count($arr);
echo "\n<br/>There were $num_rows rows of data returned from <code>$database</code> using the query I have coded in...<br/>" ;

if ($num_rows > 0) {
    // 'cat' 'arch' 'topol' 'homol' 'repchn' 'name' 
  echo "\n<br/><table><tr><th>ID</th><th>cat</th><th>arch</th><th>topol</th><th>homol</th><th>repchn</th><th>name</th></tr>";
  // output data of each row
foreach ($arr as $record) {
    echo "\n<tr>" ;
    foreach ($record as $key => $value) {
        echo "<td>$value</td>" ; 
    }
    echo "</tr>" ;
} 
echo "\n</table>" ;
} else {
exit('No rows selected, sorry!');
}
$stmt = null;
} catch(PDOException $e) {
          echo "<b><font color=\"red\">Connection failed</font></b>:<br/>" . $e->getMessage();
}
} else {
echo <<<_FORM
<form action="let_me_in.php" method="post">
<h1>Database access request</h1>Please enter your relevant details in the table below.
<br/><br/><table border="0px" <font face="arial">
<tr><td>Host</td><td><input type="text" value="127.0.0.1" name="host"/></td></tr>
<tr><td>Database</td><td><input type="text" name="database" required/></td></tr>
<tr><td>MySQL username</td><td><input type="text" name="user" required/></td></tr>
<tr><td>MySQL password</td><td><input type="password" name="password" required/></td></tr>
</table>
<br/><input type="submit" value="Request access" />
</form>
_FORM;
}
echo <<<_TAIL
<br/>Session destroyed...<br/><br/><br/><hr>
</body>
</html>
_TAIL;
session_destroy() ;
?>


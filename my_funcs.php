<?php
session_start();
echo <<<_HEAD1
<html>
<body>
_HEAD1;

echo <<<_MyFor
<div style="padding-left: 100px">
<br/><h3>Checking out a function that does a SQL query...</h3>
The results come back in differing formats depending on our query, so if you 
decide to use this, do have a look at this for 
<a target="_blank" href="https://stackoverflow.com/questions/20017409/notice-array-to-string-conversion-in-error">further guidance!</a> ;
<br/><br/>
<form action="my_funcs2.php" method="POST">
<pre style="font-family: cursive;" >
Database to query      <input type="text" name="database" value="" size="20" />

SQL query to submit    <input type="text" name="sql" value="" size="60" />

<input type="submit" value="Let's try it...." />

Three suggested queries for you to try:
<ol>
<li> show tables ; </li>
<li> describe Manufacturers ; </li>
<li> select * from Manufacturers ; </li>
</pre></form>
<br/><br/><hr>
</body></html>
_MyFor;
session_destroy() ;
?>


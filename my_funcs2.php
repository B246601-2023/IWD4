<?php
session_start();
echo <<<_HEAD1
<html>
<body>
<div style="padding-left: 75px; background-color:lightblue;" >
_HEAD1;

echo "<h1>Thanks!</h1><br/>You asked to search the <b>". $_POST['database']."</b> database <br>" ;
echo "for the following SQL query: <b>". $_POST['sql'] . "</b><hr><br/> ";
$databasechosen=$_POST['database'] ;
$sql=$_POST['sql'] ;

// Pull in a php file that contains our functions
include 'funky.php' ;

// Call a function that does things for us
$returned_values = doQuery($databasechosen,$sql) ;
echo "</div><div  style=\"padding-left: 75px; background-color:#FEF5E7;\" > " ; 
// Provide a
// Make the new objects from $returned_values
$arr = $returned_values[0];
$num_rows  = $returned_values[1] ;
$data2 = $returned_values[2] ;
$num_col=  $returned_values[3] ;
echo "<hr><br/><b>Results</b><br/>Searching the <b>$databasechosen</b> database using \"<b>$sql</b>\" as the query, there were $num_rows rows, with $num_col columns, returned<br/><br/>" ;

print_r($returned_values[1]) ;

echo "<br>The var_dump of the fetchAll search:<br/>" ;
print_r(var_dump($arr)) ;
echo "<hr><br>The array keys of the fetchAll search are:<br/>";
echo var_dump(array_keys($arr));

echo "<hr><br>If we loop through the output using the <b>foreach()</b> way, we get:<br/>";
foreach ($arr as $subarray)
{
        $fullline="Tab separated: \t";
        foreach($subarray as $key=>$value)
        {
                echo "Key: $key has Value: $value <br/>" ;
                $fullline .= $key ."\t". $value . "\t" ;
        }
        print($fullline."<br/><br/>". PHP_EOL) ;
}
echo "<hr>The query way (rather than a prepared statement) :<br/>";
echo "<pre>";
// var_dump($data2);
echo '1,1 $data2[1][1] <br/>' ;
print($data2[1][1]);
echo ' <br/>2,1 $data2[2][1] <br/>' ;
print($data2[2][1]);
echo ' <br/>5,1 $data2[5][1] <br/>' ;
print($data2[5][1]) ;

echo "</pre>";

echo "<hr>The <b>implode</b> way on arr (one sub-array at a time) :<br/>";
for($a = 0 ; $a < $num_rows ; ++$a)
 {
$newarr=implode(", ",$arr[$a]) ;
echo $newarr."<br>" ;
 }

echo "<hr>The implode way on data2 :<br/>";
for($a = 1 ; $a < $num_rows ; ++$a)
 {
echo implode(", ",$data2[$a]) . "<br/>";
}

foreach ($arr as $result)
{
        echo "<br/><br/><b>Printing result var_dump</b><br>";
        print(var_dump($result)) ;
        echo "<br>The array keys of the result are:<br/>";
var_dump(array_keys($result)) ;
        echo "<br>The array values of the result are:<br/>";
var_dump(array_values($result)) ;
        echo "<br>The array values of the result are:<br/>";
var_dump(array_values($result)[1]) ;
}
echo "<br/><br/><br/><br/><br/></div></body></html>  " ;
?>


<?php
$USER="~s2495354";
if(!(isset($_SESSION['forname']) &&
     isset($_SESSION['surname'])))
  {
  header('location: https://bioinfmsc8.bio.ed.ac.uk/{$USER}/complib2.php');
  }
?>

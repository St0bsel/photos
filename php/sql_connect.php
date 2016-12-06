<!--
____________________________________________________________________________________
INFORMATION:
Created on:     23.11.16
Created by:     Tobias Zweifel
Last changes:
Last edited by:
Function:       script contains database information and open a connection
____________________________________________________________________________________
-->

<?php
  $dbname="photos";
  $dbhost="localhost";
  $dbuser="";
  $dbpass="";
  $sql_connection=mysqli_connect($dbhost,$dbuser,$dbpass,$dbname);
  // Check connection
  if (mysqli_connect_errno()){
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }
 ?>

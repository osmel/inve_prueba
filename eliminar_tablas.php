<?php
/* fill in your database name */
$database_name = "tinbox_db";
  
/* connect to MySQL */
if (!$link = mysql_connect("199.188.74.64", "estrategas_digitales", "Fb=$rgPRQH%47Cn&")) {
  die("Could not connect: " . mysql_error());
}
  
/* query all tables */
$sql = "SHOW TABLES FROM $database_name";
if($result = mysql_query($sql)){
  /* add table name to array */
  while($row = mysql_fetch_row($result)){
    $found_tables[]=$row[0];
  }
}
else{
  die("Error, could not list tables. MySQL Error: " . mysql_error());
}
  
/* loop through and drop each table */
foreach($found_tables as $table_name){
  $sql = "DROP TABLE $database_name.$table_name";
  if($result = mysql_query($sql)){
    echo "Success - table $table_name deleted.";
  }
  else{
    echo "Error deleting $table_name. MySQL Error: " . mysql_error() . "";
  }
}
?>
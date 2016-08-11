<?php
    $key=$_GET['key'];
    $array = array();
    $con=mysql_connect("localhost","root","root");
    $db=mysql_select_db("dev_impromed",$con);
    $query=mysql_query("select * from ed_unidad_datos_generales where placas LIKE '%{$key}%'");
    while($row=mysql_fetch_assoc($query))
    {
      $array[] = $row['placas'];
    }
    echo json_encode($array);
?>

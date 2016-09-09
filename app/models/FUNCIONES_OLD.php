/*
mysql http://www.cristalab.com/tutoriales/fechas-con-mysql-c84136l/
SELECT DATEDIFF( NOW( ) ,  '2016-09-02' ) //diferencia en dias
SELECT DATEDIFF( NOW( ) ,  `fecha_entrada` ) 
FROM  `inven_historico_ctasxpagar`

                  //fecha
                  $date1 = strtotime('2013-07-03 18:00:00');
                  $date2 = time();
                  $subTime = $date1 - $date2;
                  $y = ($subTime/(60*60*24*365));
                  $d = ($subTime/(60*60*24))%365;
                  $h = ($subTime/(60*60))%24;
                  $m = ($subTime/60)%60;

                  echo "diferencia entre ".date('Y-m-d H:i:s',$date1)." and ".date('Y-m-d H:i:s',$date2)." es:"."<br/>";
                  echo $y." años"."<br/>";
                  echo $d." dias"."<br/>";
                  echo $h." horas"."<br/>";
                  echo $m." minutos"."<br/>";
                        

                  //$date1 = new DateTime('now');
                  //$date1 = strtotime('2013-07-03 18:00:00');
                  $date1 = date('d/M/Y:H:i:s', strtotime('2013-07-03 18:00:00'));
                  $date2 = new DateTime('tomorrow');                                    

                  //$date = strtotime($s);
                  //echo date('d/M/Y:H:i:s', $date);
                  
                  $interval = date_diff($date1, $date2);

                  //$date1 = new DateTime(date('d-m-Y',mktime(0,0,0,$thismonth,$thisday,$thisyear)));



                  echo $interval->format('In %a days');
                  echo "<br/>";
*/




/*
$this->db->select('"'.addslashes($num_movimiento).'" AS movimiento',false); 
                  $this->db->select('m.id_tipo_pago');
                  $this->db->select('m.id_almacen');
                  $this->db->select('m.id_empresa');
                  $this->db->select('m.fecha_entrada');
                  $this->db->select('m.factura');
                  $this->db->select('m.id_factura');
                  $this->db->select('m.fecha_mac, m.id_operacion,m.id_usuario');
                  $this->db->select('m.comentario');
                  
                  $this->db->select('sum(m.precio) as subtotal');           
                  $this->db->select("sum(m.precio*m.iva)/100 as iva", FALSE);
                  $this->db->select("sum(m.precio)+((sum(m.precio*m.iva))/100) as total", FALSE);

*/

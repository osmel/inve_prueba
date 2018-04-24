 <?
  //historico

public function buscador_historico_conteo($data){
          $cadena = addslashes($data['search']['value']);

          $inicio = $data['start'];
           $largo = $data['length'];

          $columa_order = $data['order'][0]['column'];
                 $order = $data['order'][0]['dir'];

          switch ($columa_order) {
                   case '0':
                        $columna = 'p.consecutivo';
                     break;
                   case '1':
                        $columna = 'p.filtro';
                     break;
                   
                   case '5':
                   case '6':
                   case '7':
                        $columna = 'sum(p.cantidad_royo>p.conteo3)*1';
                     break;                     
                   
                     case '8':
                     case '9':
                    case '10':
                        $columna = 'sum(p.cantidad_royo<p.conteo3)*1';
                     break;                   

                   default:
                         $columna = 'p.consecutivo';
                     break;
                 }                 
          
          $this->db->select("SQL_CALC_FOUND_ROWS (p.id)", FALSE); //
          $this->db->select("p.consecutivo, p.filtro, p.id_factura, p.id_estatus, p.id_operacion,p.id_operacion_salida,p.devolucion"); //,
          $this->db->select("p.cantidad_royo, p.conteo3, p.mov_faltante, p.mov_sobrante, movimiento, movimiento_unico");
          $this->db->select("sum(p.cantidad_royo>p.conteo3)*1 as cant_faltante", FALSE);
          $this->db->select("sum(p.cantidad_royo<p.conteo3)*1 as cant_sobrante", FALSE);
          $this->db->select("prov.nombre AS vendedor",FALSE);
          


          //$this->db->select("m.id_operacion_salida");
            $this->db->select("
                CONCAT('[',
                ( CASE 
                  WHEN (p.id_operacion=73)  THEN 'A' 
                  WHEN (p.id_operacion=99)  THEN 'J' 
                  else 'NO' 
                end),
                ']',
                  p.id_almacen,'-',  
                  tf.tipo_factura,'-',

               ( CASE 
                  WHEN (p.id_operacion=73)  THEN p.c234
                  WHEN (p.id_operacion=99)  THEN p.cs234 
                  else 'NO' 
                end)
                     
               )
                AS mov",FALSE);




          $this->db->from($this->historico_conteo_almacen.' as p');
          $this->db->join($this->usuarios.' As us' , 'us.id = p.id_usuario','LEFT');
          $this->db->join($this->proveedores.' As prov' , 'prov.id = us.id_cliente','LEFT');
          $this->db->join($this->proveedores.' As provee' , 'provee.id = p.id_empresa','LEFT');  
          $this->db->join($this->tipos_facturas.' As tf' , 'tf.id = p.id_factura','LEFT'); //

          if ($data["id_almacen"]==0){
            $id_almacenid = '';
          } else {
            $id_almacenid = ' AND (p.id_almacen =  '.$data["id_almacen"].')';   
          }
          


          if ($data["id_factura"]==0){
            $id_facturaid = '';
          } else {
            $id_facturaid = ' AND (p.id_factura =  '.$data["id_factura"].')';   
          }

          $fechas = ' ';
          if  ( ($data['fecha_inicial'] !="") and  ($data['fecha_final'] !="")) {
                           $fecha_inicial = date( 'Y-m-d', strtotime( $data['fecha_inicial'] ));
                           $fecha_final = date( 'Y-m-d', strtotime( $data['fecha_final'] ));
                          
                            $fechas .= ' AND ( ( DATE_FORMAT((p.fecha_creacion),"%Y-%m-%d")  >=  "'.$fecha_inicial.'" )  AND  ( DATE_FORMAT((p.fecha_creacion),"%Y-%m-%d")  <=  "'.$fecha_final.'" ) )'; 

          } else {
           $fechas .= ' ';
          }            


          
          if ( (addslashes($data['proveedor'])!="")  && (addslashes($data['proveedor'])!= null) ) {
            $proveedorid= 'and ( provee.nombre =  "'.addslashes($data['proveedor']).'" ) ';
          } else {
            $proveedorid = '';
          }

          $where = '(
                      
                      (
                        (p.filtro LIKE  "%'.$cadena.'%" ) OR 
                        (p.consecutivo LIKE  "%'.$cadena.'%" ) OR 
                        (p.mov_faltante LIKE  "%'.$cadena.'%") OR
                        (p.mov_sobrante LIKE  "%'.$cadena.'%") 
                       ) AND (  (p.num_conteo>=3)'.$id_almacenid.$id_facturaid.$fechas.$proveedorid.' )

            ) ' ;                         

          $this->db->where($where);
          
          $this->db->order_by($columna, $order); 

          $this->db->group_by('p.id_operacion,consecutivo');

          $result = $this->db->get();
          
         // print_r($result->result()); die;

              if ( $result->num_rows() > 0 ) {
                 // return $result->num_rows();

                    $cantidad_consulta = $this->db->query("SELECT FOUND_ROWS() as cantidad");
                    $found_rows = $cantidad_consulta->row(); 
                    $registros_filtrados =  ( (int) $found_rows->cantidad);

                    
                     
                  foreach ($result->result() as $clave => $row) {
                    $dato[$clave]=array(0,'No','No','-','No','No','-',0,0,0,0,0,0,0,0,0);
                            $arreglo= explode(";", $row->filtro);
                           
                           $filtro =''; 
                          for ($i=0; $i < count($arreglo); $i++) { 
                            if  ($arreglo[$i]!='') {
                              $filtro .= (($i!=0) ? '<br/>': '').$arreglo[$i];
                            }
                            
                          }
                          
                        
                           $dato[$clave]= array(
                                      0=>$row->consecutivo,   //numero de mov de ajuste
                                      1=>($row->id_operacion==99) ?  ( ($row->cant_faltante>0)  ? "Si":"No" ) : $dato[$clave][1], 
                                      2=>($row->id_operacion==99) ?  ( ($row->mov_faltante!=0)  ? "Si":"No" ) : $dato[$clave][2], 
                                      3=>($row->id_operacion==99) ?  ( ($row->mov_faltante!=0)  ? $row->movimiento_unico :"-") : $dato[$clave][3],

                                      4=>($row->id_operacion==73) ?  ( ($row->cant_sobrante>0)  ? "Si":"No" ) : $dato[$clave][4], 
                                      5=>($row->id_operacion==73) ?  ( ($row->mov_sobrante!=0)  ? "Si":"No" ) : $dato[$clave][5], 
                                      6=>($row->id_operacion==73) ?  ( ($row->mov_sobrante!=0)  ? $row->movimiento_unico :"-") : $dato[$clave][6],

                                      7=>$row->vendedor,
                                      8=>$filtro,
                                      9=>$row->id_factura,
                                      10=>0, //$row->id_estatus,
                                      11=>(($row->id_operacion==72) ? 'B-' : (($row->id_operacion==71) ? 'C-' : (($row->devolucion<>0) ? 'D-' :  (($row->id_operacion==70) ? 'T-' : (($row->id_operacion==73) ? 'A-' :'E-') ) ))),
                                      12=>99,  //aqui devuelve 
                                      13=>73,  //aqui devuelve 
                                      
                                      14=>($row->id_operacion==99) ?  ( (true)  ? $row->mov :"-") : $dato[$clave][14],
                                      15=>($row->id_operacion==73) ?  ( (true)  ? $row->mov :"-") : $dato[$clave][15],

                                    );                    
                           

                      }



  
                      return json_encode ( array(
                        "draw"            => intval( $data['draw'] ),
                        "recordsTotal"    => $registros_filtrados,  //intval( self::total_ajustes_historico($where) ),  
                        "recordsFiltered" => $registros_filtrados, 
                        "data"            =>  $dato, 
                      ));
                    
              }   
              else {
                  $output = array(
                  "draw" =>  intval( $data['draw'] ),
                  "recordsTotal" => 0,
                  "recordsFiltered" =>0,
                  "aaData" => array(),
                  );
                  $array[]="";
                  return json_encode($output);
              }

              $result->free_result();   
      }  
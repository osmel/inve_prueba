
     //$cadena = addslashes($data['search']['value']);
     
     //activar nuevo, editar  y eliminar
     //<!-- si configuracion lo tiene activo y es(administrador o por el contrario tiene "permiso de ver y editar") -->     
  /*
     $perfil= $this->session->userdata('id_perfil'); 
     $coleccion_id_operaciones= json_decode($this->session->userdata('coleccion_id_operaciones')); 
     if ( (count($coleccion_id_operaciones)==0) || (!($coleccion_id_operaciones)) ) {
          $coleccion_id_operaciones = array();
     }   
     $activar = (($data['configuracion']->activo==1) and ( ( $perfil == 1 ) || (( (in_array(29, $coleccion_id_operaciones)) || (in_array(30, $coleccion_id_operaciones)) ) && (in_array(28, $coleccion_id_operaciones)))  ));

 0=>$row->documento_pago,
                                      1=>$row->instrumento_pago,
                                      2=>$row->fecha_pago,
                                      3=>number_format($row->importe, 2, '.', ','),
                                      4=>$row->comentario,
                                      5=>$row->id,
                                      6=>$activar,  
                                      7=>( (($row->pagos_tardios-$row->dias_ctas_pagar)<0) ? 1:0), //0->son tardios los pagos
                                      8=>$row->movimiento, 


      "movimiento"=>$row->movimiento, 
                                          "tipo_pago"=>$row->tipo_pago,
                                          "almacen"=>$row->almacen,
                                          "nombre"=>$row->nombre,
                                          "fecha"=>$row->fecha,
                                          "factura"=>$row->factura,
                                          "subtotal"=>number_format($row->subtotal, 2, '.', ','),
                                          "iva"=>number_format($row->iva, 2, '.', ','),
                                          "total"=>number_format($row->total, 2, '.', ','),
                                          "dias_vencidos"=>abs($row->diferencia_dias-$row->dias_ctas_pagar),
                                          "monto_restante"=>(($row->monto_restante==null) ? $row->total : $row->monto_restante)
*/


          //$this->db->select(' m.factura,tp.tipo_pago,pr.id_documento_pago');
          //$this->db->select("DATEDIFF( pr.fecha_pago ,  m.fecha_entrada ) as pagos_tardios", false);                    
          

          









//paginacion
          $this->db->limit($largo,$inicio); 


          $result = $this->db->get();

              if ( $result->num_rows() > 0 ) {

                    $cantidad_consulta = $this->db->query("SELECT FOUND_ROWS() as cantidad");
                    $found_rows = $cantidad_consulta->row(); 
                    $registros_filtrados =  ( (int) $found_rows->cantidad);

                  $retorno= " ";  


             

                  foreach ($result->result() as $row) {
                              $totales =array(
                                          "movimiento"=>$row->movimiento, 
                                          "tipo_pago"=>$row->tipo_pago,
                                          "almacen"=>$row->almacen,
                                          "nombre"=>$row->nombre,
                                          "fecha"=>$row->fecha,
                                          "factura"=>$row->factura,
                                          "subtotal"=>number_format($row->subtotal, 2, '.', ','),
                                          "iva"=>number_format($row->iva, 2, '.', ','),
                                          "total"=>number_format($row->total, 2, '.', ','),
                                          "dias_vencidos"=>abs($row->diferencia_dias-$row->dias_ctas_pagar),
                                          "monto_restante"=>(($row->monto_restante==null) ? $row->total : $row->monto_restante)           
                                      );

                               $dato[]= array(
                                      
                                      0=>$row->documento_pago,
                                      1=>$row->instrumento_pago,
                                      2=>$row->fecha_pago,
                                      3=>number_format($row->importe, 2, '.', ','),
                                      4=>$row->comentario,
                                      5=>$row->id,
                                      6=>$activar,  
                                      7=>( (($row->pagos_tardios-$row->dias_ctas_pagar)<0) ? 1:0), //0->son tardios los pagos
                                      8=>$row->movimiento, 
                                    );
                      }






                      return json_encode ( array(
                        "draw"            => intval( $data['draw'] ),
                        "recordsTotal"    =>intval( self::total_pagosrealizados($where_total) ), 
                        "recordsFiltered" =>   $registros_filtrados, 
                        "data"            =>  $dato,

                                      0=>$row->movimiento,
                                      1=>$row->tipo_pago,
                                      2=>$row->almacen,
                                      3=>$row->nombre,
                                      4=>$row->fecha,
                                      5=>$row->factura,
                                      6=>number_format($row->subtotal, 2, '.', ','),
                                      7=>number_format($row->iva, 2, '.', ','),
                                      8=>number_format($row->total, 2, '.', ','),
                                      9=>abs($row->diferencia_dias-$row->dias_ctas_pagar),
                                      10=>(($row->monto_restante==null) ? $row->total : $row->monto_restante),

                      "totales"     =>  $totales   
                      ));
                                      


                    
              }   
              else {


                  $totales = json_decode(self::encabezado_pagosrealizados($data));

                  $output = array(
                  "draw" =>  intval( $data['draw'] ),
                  "recordsTotal" => 0,
                  "recordsFiltered" =>0,
                  "aaData" => array(),
                  "totales"     =>  $totales  

                  );
                  $array[]="";
                  return json_encode($output);
                  

              }

              $result->free_result();        
          //ordenacion
         //$this->db->order_by($columna, $order); 
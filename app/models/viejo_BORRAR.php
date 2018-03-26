

//2da regilla. ya procesando la confirmacion de la operacion
 public function procesando_operacion_pedido_bodega_automatico( $data ){
        

          $id_session = $this->session->userdata('id');


          $id_cliente_asociado = $this->session->userdata('id_cliente_asociado');
          $consecutivo = self::consecutivo_operacion(2,$data['id_tipo_pedido_new'],$data['id_tipo_factura_new']); 
          $consecutivo_unico = self::consecutivo_operacion_unico(2); 

          $fecha_hoy = date('Y-m-d H:i:s');  //date_format($fecha_hoy , 'Y-m-d H:i:s');
          $fecha_hoy_entrada= date ( 'Y-m-d H:i:s' , strtotime ( '+1 g' , strtotime ($fecha_hoy) ) );

        
          $this->db->select('"2" AS id_operacion',false);
          $this->db->select('"0" AS estatus_salida',false);
          $this->db->select('"'.$id_session.'" AS id_usuario',false); 
          $this->db->select('"'.$id_session.'" AS id_usuario_salida',false); 
          $this->db->select('"'.$id_session.'" AS id_usuario_traspaso',false); 
          
          $this->db->select('"'.addslashes($data['id_almacen']).'" AS id_almacen',false); 
          $this->db->select('"'.htmlspecialchars($data['id_cargador']).'" AS id_cargador',false);
          $this->db->select('"'.$fecha_hoy.'" AS fecha_salida',false);
          $this->db->select('"'.$consecutivo.'" AS mov_salida',false); 
          $this->db->select('"'.$consecutivo_unico.'" AS mov_salida_unico',false); 

          $this->db->select('u.id_cliente AS id_cliente',false); 
          $this->db->select('"6" AS id_apartado',false); 
          
          
          $this->db->select('peso_real,proceso_traspaso,id_tipo_pago, comentario_traspaso, num_control');
          $this->db->select('m.id id_entrada, movimiento,movimiento_unico, movimiento_unico_apartado, id_empresa, id_descripcion, id_color, devolucion, m.num_partida');
          $this->db->select('id_composicion, id_calidad, referencia, id_medida, factura, cantidad_um, cantidad_royo, ancho');
          $this->db->select('codigo, comentario, id_estatus, id_lote, consecutivo');
          $this->db->select('fecha_entrada,consecutivo_venta');

          $this->db->select('id_usuario_apartado, id_cliente_apartado,  fecha_apartado');
          $this->db->select('precio, iva, id_pedido, id_factura,id_fac_orig, id_factura_original,incluir');
          $this->db->select('precio_anterior, precio_cambio, id_prorroga, fecha_vencimiento, consecutivo_cambio');

          $this->db->select('on_off');

          $this->db->select('"'.$this->session->userdata('config_tienda_activo').'" AS id_tienda_origen',FALSE);


          $this->db->select($data['id_tipo_pedido_new'].' AS  id_tipo_pedido',FALSE);
          $this->db->select($data['id_tipo_factura_new'].' AS id_tipo_factura',FALSE);
  


          $this->db->from($this->registros_entradas.' As m');
          $this->db->join($this->usuarios.' As u' , 'u.id = m.id_usuario_apartado');

          if ($data["id_almacen"]!=0) {
                            $id_almacenid = ' AND ( m.id_almacen =  '.$data["id_almacen"].' ) ';  
                        } else {
                            $id_almacenid = '';
                }                         

                $where=  '(
                       (m.id_factura='.$data['id_tipo_factura_new'].' ) AND (m.id_tipo_pedido='.$data['id_tipo_pedido'].' ) AND (m.id_tipo_factura='.$data['id_tipo_factura'].' ) AND 
                      (m.id_apartado='.$data["id_apartado"].') and  (movimiento_unico_apartado='.$data['num_mov'].' ) AND ( proceso_traspaso = 0 ) AND ( estatus_salida = "0" )'.$id_almacenid.'
                    )';

          $this->db->where($where);     
          $result = $this->db->get();
          $objeto = $result->result();

          //return $objeto;





         
//copiar a tabla "historico_registros_salidas"
          $dato = array();
          $tienda_envio=0; //almacen a donde se va a enviar
          $tienda_on_off=0;
          foreach ($objeto as $key => $value) {
            $this->db->insert($this->historico_registros_salidas, $value); 
            $tienda_envio=$value->consecutivo_venta;
            $tienda_on_off=(int)$value->on_off;
          }

        
//actualizar (consecutivo) en tabla "operacion"   == "salida"
          if ($data['id_tipo_factura_new']==1) {
              $this->db->set( 'conse_factura', 'conse_factura+1', FALSE  );  
          } else {
              $this->db->set( 'conse_remision', 'conse_remision+1', FALSE  );  
          }
          $this->db->set( 'consecutivo', 'consecutivo+1', FALSE  );  

          $this->db->set( 'id_usuario', $id_session );
          $this->db->where('id',2);
          $this->db->update($this->operaciones);

  

      //Aqui comienza el proceso de entrada automatico generando el nuevo consecutivo de entrada

              $consecutivo_entrada = self::consecutivo_operacion_entrada(72, $data['id_tipo_factura_new']); //72->id_operacion de bodega(id,id_factura) $data['id_factura'] =3
              $consecutivo_unico_entrada = self::consecutivo_operacion_unico(72); 

              //actualizar (consecutivo) en tabla "operacion"  
              $this->db->set( 'conse_bodega', 'conse_bodega+1', FALSE  );  
              $this->db->set( 'consecutivo', 'consecutivo+1', FALSE  );  
              $this->db->set( 'id_usuario', $id_session );
              $this->db->where('id',72);
              $this->db->update($this->operaciones);



              $datum["id_operacion"] = 72;
              $datum["id_almacen"] =  $data["id_almacen"];
              $datum["id_factura"] =  $data['id_tipo_factura_new']; 
              $datum["id_pedido"] =  $data['id_tipo_pedido_new']; 

               //En el caso de bodega, voy a correr este proceso primero solo para q cree los consecutivos q faltan
               $this->catalogo->consecutivo_general($datum);

               //actualizando nuevos consecutivos
               $this->catalogo->actualizando_nuevos_consecutivos($datum);
               
               //Obtener nuevos consecutivos
               $new_consecutivo   = $this->catalogo->consecutivo_general($datum);


            if ($data["id_almacen"]!=0) {
                            $id_almacenid = ' AND ( id_almacen =  '.$data["id_almacen"].' ) ';  
                        } else {
                            $id_almacenid = '';
                }                         
                $id_almacenid = '';
                $where_actualizar=  '(
                      (id_factura='.$data['id_tipo_factura_new'].' ) AND  (id_tipo_pedido='.$data['id_tipo_pedido'].' ) AND (id_tipo_factura='.$data['id_tipo_factura'].' ) AND
                       (id_apartado='.$data["id_apartado"].') and  (movimiento_unico_apartado='.$data['num_mov'].' ) AND ( proceso_traspaso = 0 ) AND ( estatus_salida = "0" )'.$id_almacenid.'
                    )';


            
            /*
                      $this->db->select($new_consecutivo->c1.' AS c1',false); 
          $this->db->select($new_consecutivo->c2.' AS c2',false); 
          $this->db->select($new_consecutivo->c1234.' AS c1234',false); 
          $this->db->select($new_consecutivo->c234.' AS c234',false); 
          $this->db->select($new_consecutivo->c34.' AS c34',false); 
          */


               $this->db->set( 'c1', $new_consecutivo->c1  );  
               $this->db->set( 'c2', $new_consecutivo->c2  );  
               $this->db->set( 'c1234', $new_consecutivo->c1234  );  
               $this->db->set( 'c234', $new_consecutivo->c234  );  
               $this->db->set( 'c34', $new_consecutivo->c34  );  

            


              $this->db->set( 'id_almacen', 'consecutivo_venta', FALSE  );  //en consecutivo venta es donde trae el numero de almacen
              
              $this->db->set( 'consecutivo_venta', 0 );  //que lo quite 


              $this->db->set( 'id_tipo_pago', 2, FALSE  ); //de contado
              $this->db->set( 'id_factura', $data['id_tipo_factura_new'], FALSE  );  //nuevo 
              $this->db->set( 'id_fac_orig', $data['id_tipo_factura_new'], FALSE  ); //

              $this->db->set( 'id_tipo_pedido', 0, FALSE  ); //
              $this->db->set( 'id_tipo_factura', 0, FALSE  ); //

              //on_off  = 2 traspaso de bodega a bodega
              //id_tienda_origen
               
              $this->db->set( 'movimiento_unico_apartado', 0, FALSE  ); //
              
              $this->db->set( 'iva', 16.00, FALSE  );

              $this->db->set( 'comentario', 'Movimiento entre bodegas' );


              $this->db->set( 'fecha_entrada', $fecha_hoy_entrada  );  
              $this->db->set( 'movimiento', $consecutivo_entrada, FALSE  );  
              $this->db->set( 'movimiento_unico', $consecutivo_unico_entrada, FALSE  );  
              $this->db->set( 'id_usuario', $id_session );
              $this->db->set( 'id_operacion', 72 );
              $this->db->set( 'id_cargador', '"'.htmlspecialchars($data['id_cargador']).'"' );

              $this->db->set( 'id_apartado', 0 );  //que lo quite 
              $this->db->set( 'id_usuario_apartado', '' );  //que lo quite 
              $this->db->set( 'id_cliente_apartado', 0 );  //que lo quite 


              $this->db->where($where_actualizar);
              $this->db->update($this->registros_entradas);
              


            //aqui lista todos los datos que fueron entrados por un usuario especifico   
                       

                    $this->db->select('id_empresa, factura, id_descripcion, id_color, id_composicion, id_calidad, referencia, num_partida,id_almacen,id_factura,id_fac_orig,iva, id_tipo_pago');
                    $this->db->select('id_medida, cantidad_um, peso_real, cantidad_royo, ancho, precio, codigo, comentario, id_estatus, id_lote, consecutivo');
                    $this->db->select('id_cargador, id_usuario, fecha_mac, id_operacion');
                    $this->db->select('fecha_entrada, movimiento, movimiento_unico');
                    $this->db->select('c1,c2,c1234,c234,c34');

                    $this->db->from($this->registros_entradas);
                    $this->db->where('id_usuario',$id_session);
                    $this->db->where('id_operacion',72);
                    $this->db->where('movimiento_unico',$consecutivo_unico_entrada);
                    $result = $this->db->get();
                    $objeto = $result->result();
                    //copiar a tabla "historico_registros_entradas"
                    foreach ($objeto as $key => $value) {
                      $this->db->insert($this->historico_registros_entradas, $value); 
                      /*
                      $value->peso_real = 0;
                      $num_movimiento = $value->movimiento;
                      $num_movimiento_unico = $value->movimiento_unico;
                      */
                    }


            ///datos a retornar
                      $this->db->select('m.mov_salida,m.mov_salida_unico ,ca.nombre cargador');
                      $this->db->select('CONCAT(u.nombre,"  ",u.apellidos) as cliente', FALSE); //, p.nombre cliente
                      $this->db->from($this->historico_registros_salidas.' As m');
                      $this->db->join($this->usuarios.' As u' , 'u.id = m.id_usuario_apartado','LEFT');
                      $this->db->join($this->cargadores.' As ca' , 'ca.id = m.id_cargador','LEFT');
                      //$this->db->join($this->usuarios.' As u' , 'u.id = m.id_usuario_apartado');
                      //$this->db->join($this->proveedores.' As p' , 'p.id = m.id_cliente','LEFT');
                      if ($data["id_almacen"]!=0) {
                                        $id_almacenid = ' AND ( m.id_almacen =  '.$data["id_almacen"].' ) ';  
                                    } else {
                                        $id_almacenid = '';
                            }                         

                            $where=  '(
                                  (m.mov_salida_unico='.$consecutivo_unico.' ) AND (m.id_tipo_pedido='.$data['id_tipo_pedido_new'].' ) AND (m.id_tipo_factura='.$data['id_tipo_factura_new'].' ) '.$id_almacenid.'
                                   
                                )';
                       $this->db->where($where);           


                        $result = $this->db->get();
                    
                        if ( $result->num_rows() > 0 )
                           return $result->row();
                        else
                           return False;
                        $result->free_result();

                

}   
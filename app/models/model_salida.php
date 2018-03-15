<?php if(! defined('BASEPATH')) exit('No tienes permiso para acceder a este archivo');

  class model_salida extends CI_Model {
    
    private $key_hash; 
    private $timezone;

    function __construct(){

      parent::__construct();
      
      $this->load->database("default");
      //$this->db2 = $this->load->database("remoto",TRUE);
      $this->key_hash    = $_SERVER['HASH_ENCRYPT'];
      $this->timezone    = 'UM1';

      date_default_timezone_set('America/Mexico_City'); 

      
        //usuarios
      $this->usuarios    = $this->db->dbprefix('usuarios');
        //catalogos     
      $this->actividad_comercial     = $this->db->dbprefix('catalogo_actividad_comercial');
      
      $this->estratificacion_empresa = $this->db->dbprefix('catalogo_estratificacion_empresa');
      
      $this->productos               = $this->db->dbprefix('catalogo_productos');
      $this->proveedores             = $this->db->dbprefix('catalogo_empresas');
      $this->cargadores             = $this->db->dbprefix('catalogo_cargador');
      $this->catalogo_destinos             = $this->db->dbprefix('catalogo_destinos');
      $this->unidades_medidas        = $this->db->dbprefix('catalogo_unidades_medidas');

      $this->operaciones             = $this->db->dbprefix('catalogo_operaciones');
      $this->movimientos               = $this->db->dbprefix('movimientos');
      $this->registros_temporales               = $this->db->dbprefix('temporal_registros');
      $this->registros               = $this->db->dbprefix('registros_entradas');
      $this->registros_salidas       = $this->db->dbprefix('registros_salidas');

      $this->colores                 = $this->db->dbprefix('catalogo_colores');
      $this->unidades_medidas        = $this->db->dbprefix('catalogo_unidades_medidas');
      
      $this->historico_registros_entradas = $this->db->dbprefix('historico_registros_entradas');
      $this->historico_registros_salidas = $this->db->dbprefix('historico_registros_salidas');
      $this->historico_registros_traspasos        = $this->db->dbprefix('historico_registros_traspasos');
      
      $this->composiciones     = $this->db->dbprefix('catalogo_composicion');
      $this->calidades                 = $this->db->dbprefix('catalogo_calidad');

      $this->registros_entradas               = $this->db->dbprefix('registros_entradas');
      $this->registros_cambios               = $this->db->dbprefix('registros_cambios');

      $this->almacenes             = $this->db->dbprefix('catalogo_almacenes');
      
      $this->tipos_facturas                         = $this->db->dbprefix('catalogo_tipos_facturas');
      $this->tipos_pedidos                         = $this->db->dbprefix('catalogo_tipos_pedidos');
      $this->tipos_ventas                         = $this->db->dbprefix('catalogo_tipos_ventas');


      $this->temporal_bodegas               = $this->db->dbprefix('temporal_bodegas');
      

       //$this->load->database("remoto"); 

      /*

      $this->db_remoto = array(  //remoto
            //'dsn'       => '',
              'hostname' => 'localhost',
              'username' => 'root',
              'password' => 'root',
              'database' => 'bd_remota',
              'dbdriver' => 'mysql',
              'dbprefix' => 'inven_',
              'pconnect' => TRUE,
              'db_debug' => TRUE,
              'cache_on' => FALSE,
              'cachedir' => '',
              'char_set' => 'utf8',
              'dbcollat' => 'utf8_general_ci',
              'swap_pre' => '',
              'autoinit' => true,
              'stricton' => FALSE,
          );
          */


    }


/////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////
          //1ra regilla. detalles   <| pedidos
/////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////


        //1ra regilla. verificar si existe tal apartado de salida para el detalle.Cdo mandan a confirmar 
        public function existencia_apartado_salida($data){
              $id_session = $this->session->userdata('id');
              $cant=0;

              
                  if ($data["id_almacen"]!=0) {
                              $id_almacenid = ' AND ( m.id_almacen =  '.$data["id_almacen"].' ) ';  
                          } else {
                              $id_almacenid = '';
                  }                         

                  $dependencia= ' AND pr.nombre ="'.$data['dependencia'].'"';

                  //( us.id_cliente = '.$data['id_cliente'].' ) 
                  //'id_operacion'=2
                  $where=  '(
                        ( m.id_tipo_pedido =  '.$data["id_tipo_pedido"].' )  AND ( m.id_tipo_factura =  '.$data["id_tipo_factura"].' )  AND 
                        (m.id_apartado='.$data["id_apartado"].') and  (m.consecutivo_venta='.$data['num_mov'].' ) AND ( m.proceso_traspaso = 0 ) AND ( m.estatus_salida = "0" )'.$id_almacenid.$dependencia.'
                         
                      )';

              $this->db->where($where);     
              $this->db->from($this->registros_entradas.' As m');
              $this->db->join($this->usuarios.' As u' , 'u.id = m.id_usuario_apartado');
              $this->db->join($this->proveedores.' As pr', 'u.id_cliente = pr.id'.$dependencia);

              $cant = $this->db->count_all_results();          

              if ( $cant > 0 )
                 return true;
              else
                 return false;              
        }  



      





   //1ra regilla. detalles. verificar sus pesos!=0
   public function existencia_apartado_peso_real($data){

              $id_session = $this->session->userdata('id');
              $cant=0;


           if ($data["id_almacen"]!=0) {
                              $id_almacenid = ' AND ( m.id_almacen =  '.$data["id_almacen"].' ) ';  
                          } else {
                              $id_almacenid = '';
                  }                         

                  $dependencia= ' AND pr.nombre ="'.$data['dependencia'].'"';

                  //( us.id_cliente = '.$data['id_cliente'].' ) 
                  //'id_operacion'=2
                  $where=  '(
                      ( m.id_tipo_pedido =  '.$data["id_tipo_pedido"].' )  AND ( m.id_tipo_factura =  '.$data["id_tipo_factura"].' )  AND 

                       (m.peso_real=0) AND (m.id_apartado='.$data["id_apartado"].')  and  (m.consecutivo_venta='.$data['num_mov'].' ) AND ( m.proceso_traspaso = 0 ) AND ( m.estatus_salida = "0" )'.$id_almacenid.$dependencia.'
                         
                      )';

              $this->db->where($where);     
              $this->db->from($this->registros_entradas.' As m');
              $this->db->join($this->usuarios.' As u' , 'u.id = m.id_usuario_apartado');
              $this->db->join($this->proveedores.' As pr', 'u.id_cliente = pr.id'.$dependencia);

             
              $cant = $this->db->count_all_results();          

              if ( $cant > 0 )
                 return false;
              else
                 return true;              

        }    


       //1ra regilla. traspasar si es necesario
        public function traspaso_apartado( $data ){
                
                //$id_almacen= $data['id_almacen'];
                
                if ($data['id_tipo_factura']==1){
                    $porciento_aplicar =16;  
                } else {
                     $porciento_aplicar = 0;  
                }
                
                $this->db->set( 'id_factura_original', 'id_factura', false);
                $this->db->set( 'id_factura', 'id_tipo_factura', false);
               
                
                if ($data['id_tipo_factura']==1){
                    $this->db->set( 'iva', '((id_factura = 1)*'.$porciento_aplicar.')', false);
                }

                //$this->db->set( 'id_apartado', '3');
                


                $this->db->set( 'incluir', 1);
                
                /*
                if ($id_almacen!=0) {
                    $id_almacenid = ' AND ( id_almacen =  '.$id_almacen.' ) ';  
                } else {
                    $id_almacenid = '';
                } */

                $cond_traspaso = ' AND ( ( id_factura <>  id_tipo_factura ) AND ( incluir =  0 ) )';  

                if ($data["id_almacen"]!=0) {
                            $id_almacenid = ' AND ( id_almacen =  '.$data["id_almacen"].' ) ';  
                        } else {
                            $id_almacenid = '';
                }      

                //$id_almacenid.
                $where = '(
                          ( id_tipo_pedido =  '.$data["id_tipo_pedido"].' )  AND ( id_tipo_factura =  '.$data["id_tipo_factura"].' )  AND 

                          (
                            (id_apartado='.$data["id_apartado"].')  AND ( consecutivo_venta = "'.$data['num_mov'].'" )
                          )'.$cond_traspaso.$id_almacenid.' 

                      )';   

                $this->db->where($where);
                $this->db->update($this->registros );
                if ($this->db->affected_rows() > 0) {
                  return TRUE;
                }  else
                   return FALSE;

        }



   //1ra regilla. Procesando todas las operaciones de salida
   public function procesando_operacion_apartado_salida( $data ){

          $id_session = $this->session->userdata('id');

          //este hay que checarlo porque no es para el cliente activo"almacenista", sino para quien hizo el "pedido"
          //$id_cliente_asociado = $this->session->userdata('id_cliente_asociado');
          $consecutivo = self::consecutivo_operacion(2,$data['id_tipo_pedido'],$data['id_tipo_factura']); 
           $consecutivo_unico = self::consecutivo_operacion_unico(2); 

          $fecha_hoy = date('Y-m-d H:i:s');  //date_format($fecha_hoy , 'Y-m-d H:i:s');
        
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
          $this->db->select('"3" AS id_apartado',false); 
          
          $this->db->select('peso_real,proceso_traspaso,id_tipo_pago, id_tipo_pedido, id_tipo_factura,comentario_traspaso, num_control');
          $this->db->select('m.id id_entrada, movimiento, movimiento_unico, movimiento_unico_apartado,  id_empresa, id_descripcion, id_color, devolucion, m.num_partida');
          $this->db->select('id_composicion, id_calidad, referencia, id_medida,factura cantidad_um, cantidad_royo, ancho');
          $this->db->select('codigo, comentario, id_estatus, id_lote, consecutivo');
          $this->db->select('fecha_entrada,consecutivo_venta');

          $this->db->select('id_usuario_apartado, id_cliente_apartado,  fecha_apartado');
          $this->db->select('precio, iva, id_pedido, id_factura,id_fac_orig, id_factura_original,incluir');
          $this->db->select('precio_anterior, precio_cambio, id_prorroga, fecha_vencimiento, consecutivo_cambio');

          $this->db->select('on_off');

          $this->db->select('"'.$this->session->userdata('config_tienda_activo').'" AS id_tienda_origen',FALSE);
           
          $this->db->from($this->registros_entradas.' As m');
          $this->db->join($this->usuarios.' As u' , 'u.id = m.id_usuario_apartado');

    
          if ($data["id_almacen"]!=0) {
                            $id_almacenid = ' AND ( m.id_almacen =  '.$data["id_almacen"].' ) ';  
                        } else {
                            $id_almacenid = '';
                }                         

  
                $where=  '(
                       (m.id_tipo_pedido='.$data['id_tipo_pedido'].' ) AND (m.id_tipo_factura='.$data['id_tipo_factura'].' ) AND
                      (m.id_apartado='.$data["id_apartado"].') and  (movimiento_unico_apartado='.$data['num_mov'].' ) AND ( proceso_traspaso = 0 ) AND ( estatus_salida = "0" )'.$id_almacenid.'
                    )';

          $this->db->where($where);     
          $result = $this->db->get();
          $objeto = $result->result();
          
         
          //copiar a tabla "historico_registros_salidas" y historico_registros_traspasos si hay alguno 
          $dato = array();
          $consecutivo_traspaso = self::consecutivo_operacion(26,$data['id_tipo_pedido'],$data['id_tipo_factura']); 
          $consecutivo_traspaso_unico = self::consecutivo_operacion_unico(26); 
          $traspaso=0;
          $tienda_envio=0; //almacen a donde se va a enviar
          $tienda_on_off=0;


          foreach ($objeto as $key => $value) {
            $this->db->insert($this->historico_registros_salidas, $value); 

            if ($value->incluir==1) {
              $traspaso=1;
              $value_traspaso = $value;
              $value_traspaso->consecutivo_traspaso = $consecutivo_traspaso;
              $value_traspaso->consecutivo_traspaso_unico = $consecutivo_traspaso_unico;
              $this->db->insert($this->historico_registros_traspasos, $value_traspaso); 
            }

             $tienda_envio=$value->consecutivo_venta;
             $tienda_on_off=(int)$value->on_off;

            //$this->historico_registros_traspasos        = $this->db->dbprefix('historico_registros_traspasos');
            /*
            $dato['num_movimiento'] = $value->mov_salida;
            $dato['cargador'] = $value->cargador;
            $dato['cliente'] = $value->cliente;
            */

          }
 

  

 //actualizar el consecutivo en "operaciones" == "traspasos"
          if ($traspaso==1) {
              
              if ($data['id_tipo_pedido']==2) {
                   $this->db->set( 'conse_surtido', 'conse_surtido+1', FALSE  );  
              }  else if ($data['id_tipo_factura']==1) {
                  $this->db->set( 'conse_factura', 'conse_factura+1', FALSE  );  
              } else {
                  $this->db->set( 'conse_remision', 'conse_remision+1', FALSE  );  
              }
              $this->db->set( 'consecutivo', 'consecutivo+1', FALSE  );  

              $this->db->set( 'id_usuario', $id_session );
              $this->db->where('id',26);
              $this->db->update($this->operaciones);          
          }



        //actualizar (consecutivo) en tabla "operacion"   == "salida"
          if ($data['id_tipo_pedido']==3) {
               $this->db->set( 'conse_bodega', 'conse_bodega+1', FALSE  );  
          } else if ($data['id_tipo_pedido']==2) {
               $this->db->set( 'conse_surtido', 'conse_surtido+1', FALSE  );  
          }  else if ($data['id_tipo_factura']==1) {
              $this->db->set( 'conse_factura', 'conse_factura+1', FALSE  );  
          } else {
              $this->db->set( 'conse_remision', 'conse_remision+1', FALSE  );  
          }
          $this->db->set( 'consecutivo', 'consecutivo+1', FALSE  );  

          $this->db->set( 'id_usuario', $id_session );
          $this->db->where('id',2);
          $this->db->update($this->operaciones);

          //eliminar los registros en "registros_salidas"

            if ($data["id_almacen"]!=0) {
                            $id_almacenid = ' AND ( id_almacen =  '.$data["id_almacen"].' ) ';  
                        } else {
                            $id_almacenid = '';
                }                         

                $where_borrar=  '(
                      (id_tipo_pedido='.$data['id_tipo_pedido'].' ) AND (id_tipo_factura='.$data['id_tipo_factura'].' ) AND
                      (id_apartado='.$data["id_apartado"].') and  (movimiento_unico_apartado='.$data['num_mov'].' ) AND ( proceso_traspaso = 0 ) AND ( estatus_salida = "0" )'.$id_almacenid.'
                       
                    )';



          $this->db->where($where_borrar);            
          $this->db->delete($this->registros_entradas);



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
                      (m.mov_salida_unico='.$consecutivo.' ) AND (m.id_tipo_pedido='.$data['id_tipo_pedido'].' ) AND (m.id_tipo_factura='.$data['id_tipo_factura'].' ) '.$id_almacenid.'
                       
                    )';

                    

           $this->db->where($where);           


            $result = $this->db->get();
        
            if ( $result->num_rows() > 0 )
               return $result->row();
            else
               return False;
            $result->free_result();

        

        }

      

        //1ra y 2da regilla. detalles. actualizacion de los pesos
       public function actualizar_peso_real_salida_pedido( $data ){
           
            $id_session = ($this->session->userdata('id'));


            foreach ($data['pesos'] as $key => $value) {

                if(!is_numeric($value['peso_real'])) {  //caso cuando el peso viene vacio
                  $value['peso_real'] = 0;
                  
                } 
                $this->db->set( 'peso_real', $value['peso_real'], FALSE  );
                $this->db->where('codigo',$value['codigo']);      
                $this->db->where('id_apartado',$data['id_apartado']);             
                $this->db->update($this->registros_entradas);
              }
            
            return TRUE;       
        }              


/////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////
          //2da regilla. detalles   <| pedidos
/////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////

 //2da regilla. verificar si existe tal apartado de salida para el detalle.Cdo mandan a confirmar 
        public function existencia_pedido_salida($data){
              $id_session = $this->session->userdata('id');
              $cant=0;

              
                  if ($data["id_almacen"]!=0) {
                              $id_almacenid = ' AND ( m.id_almacen =  '.$data["id_almacen"].' ) ';  
                          } else {
                              $id_almacenid = '';
                  }                         

                  $dependencia= ' AND pr.nombre ="'.$data['dependencia'].'"';

                  //( us.id_cliente = '.$data['id_cliente'].' ) 
                  //'id_operacion'=2
                  $where=  '(
                      ( m.id_tipo_pedido =  '.$data["id_tipo_pedido"].' )  AND ( m.id_tipo_factura =  '.$data["id_tipo_factura"].' )  AND 
                        (m.id_apartado='.$data["id_apartado"].') and  (m.movimiento_unico_apartado='.$data['num_mov'].' ) AND ( m.proceso_traspaso = 0 ) AND ( m.estatus_salida = "0" )'.$id_almacenid.$dependencia.'
                         
                      )';

              $this->db->where($where);     
              $this->db->from($this->registros_entradas.' As m');
              $this->db->join($this->usuarios.' As u' , 'u.id = m.id_usuario_apartado');
              $this->db->join($this->proveedores.' As pr', 'u.id_cliente = pr.id'.$dependencia);

              $cant = $this->db->count_all_results();          

              if ( $cant > 0 )
                 return true;
              else
                 return false;              
        }  


    //1ra y 2da regilla. detalles. verificar sus pesos!=0
    public function existencia_salida_peso_real($data){

              $id_session = $this->session->userdata('id');
              $cant=0;


           if ($data["id_almacen"]!=0) {
                              $id_almacenid = ' AND ( m.id_almacen =  '.$data["id_almacen"].' ) ';  
                          } else {
                              $id_almacenid = '';
                  }                         

                  $dependencia= ' AND pr.nombre ="'.$data['dependencia'].'"';

                  //( us.id_cliente = '.$data['id_cliente'].' ) 
                  //'id_operacion'=2
                  $where=  '(
                      ( m.id_tipo_pedido =  '.$data["id_tipo_pedido"].' )  AND ( m.id_tipo_factura =  '.$data["id_tipo_factura"].' )  AND 
                       (m.peso_real=0) AND (m.id_apartado='.$data["id_apartado"].') and  (m.movimiento_unico_apartado='.$data['num_mov'].' ) AND ( m.proceso_traspaso = 0 ) AND ( m.estatus_salida = "0" )'.$id_almacenid.$dependencia.'
                         
                      )';

              $this->db->where($where);     
              $this->db->from($this->registros_entradas.' As m');
              $this->db->join($this->usuarios.' As u' , 'u.id = m.id_usuario_apartado');
              $this->db->join($this->proveedores.' As pr', 'u.id_cliente = pr.id'.$dependencia);

             
              $cant = $this->db->count_all_results();          

              if ( $cant > 0 )
                 return false;
              else
                 return true;              

        }            





        //2da regilla. Traspasando los productos si fuese necesario
        public function traspaso_pedido( $data ){
                
                //$id_almacen= $data['id_almacen'];
                
                if ($data['id_tipo_factura']==1){
                    $porciento_aplicar =16;  
                } else {
                     $porciento_aplicar = 0;  
                }
                
                $this->db->set( 'id_factura_original', 'id_factura', false);
                $this->db->set( 'id_factura', 'id_tipo_factura', false);
               
                
                if ($data['id_tipo_factura']==1){
                    $this->db->set( 'iva', '((id_factura = 1)*'.$porciento_aplicar.')', false);
                }

                //$this->db->set( 'id_apartado', '6');
                


                $this->db->set( 'incluir', 1);
                

                $cond_traspaso = ' AND ( ( id_factura <>  id_tipo_factura ) AND ( incluir =  0 ) )';  

                if ($data["id_almacen"]!=0) {
                            $id_almacenid = ' AND ( id_almacen =  '.$data["id_almacen"].' ) ';  
                        } else {
                            $id_almacenid = '';
                }      

                //$id_almacenid.
                $where = '(
                           ( id_tipo_pedido =  '.$data["id_tipo_pedido"].' )  AND ( id_tipo_factura =  '.$data["id_tipo_factura"].' )  AND 
                          (
                           (id_apartado='.$data["id_apartado"].') AND ( movimiento_unico_apartado = "'.$data['num_mov'].'" )
                          )'.$cond_traspaso.$id_almacenid.' 

                      )';   

                $this->db->where($where);
                $this->db->update($this->registros );
                if ($this->db->affected_rows() > 0) {
                  return TRUE;
                }  else
                   return FALSE;
        }



//2da regilla. ya procesando la confirmacion de la operacion
 public function procesando_operacion_pedido_bodega( $data ){
        

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
            
            //$this->db->insert($this->$this->temporal_bodegas, $value); //copiar a temporal_bodega
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

 
//eliminar registros de entrada

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

                    $this->db->where($where_actualizar); 
                    $this->db->delete($this->registros_entradas); 


            ///datos a retornar
                      $this->db->select('m.mov_salida,m.mov_salida_unico ,ca.nombre cargador');
                      $this->db->select('CONCAT(u.nombre,"  ",u.apellidos) as cliente', FALSE); //, p.nombre cliente
                      $this->db->from($this->historico_registros_salidas.' As m');
                      $this->db->join($this->usuarios.' As u' , 'u.id = m.id_usuario_apartado','LEFT');
                      $this->db->join($this->cargadores.' As ca' , 'ca.id = m.id_cargador','LEFT');
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

 public function checar_bodega_sihay2_tipoFactura( $data ){

                //$this->db->select('m.movimiento_unico_apartado');
                $this->db->from($this->historico_registros_salidas.' As m');
                $where=  '(
                      (movimiento_unico_apartado='.$data['num_mov'].' ) 
                )';


                 $this->db->where($where);           
                 $this->db->group_by('id_tipo_pedido, id_tipo_factura');           

                 

                  $result = $this->db->get();
                  $cant =$result->num_rows();     

                  if ( $cant > 1 )
                      return true;
                  else
                  return False;

 } 


public function bodegas_agrupado_tipoFactura( $num_movimiento_pedido ){
                      $this->db->select('m.id_tipo_pedido, m.id_tipo_factura, m.id_estatus, m.mov_salida,m.mov_salida_unico ,ca.nombre cargador, m.movimiento_unico_apartado');
                      $this->db->select('CONCAT(u.nombre,"  ",u.apellidos) as cliente', FALSE); //, p.nombre cliente
                      $this->db->from($this->historico_registros_salidas.' As m');
                      $this->db->join($this->usuarios.' As u' , 'u.id = m.id_usuario_apartado','LEFT');
                      $this->db->join($this->cargadores.' As ca' , 'ca.id = m.id_cargador','LEFT');
                     
                      $where=  '(
                            (m.movimiento_unico_apartado='.$num_movimiento_pedido.' ) 
                      )';


                       $this->db->where($where);           
                       $this->db->group_by('m.id_tipo_pedido, m.id_tipo_factura');           



                        $result = $this->db->get();
                    
                        if ( $result->num_rows() > 0 )
                           return $result->result();
                        else
                           return False;
                        $result->free_result();

}

//2da regilla. ya procesando la confirmacion de la operacion
 public function procesando_operacion_pedido_salida( $data ){
        

          $id_session = $this->session->userdata('id');

//este hay que checarlo porque no es para el cliente activo" almacenista", sino para quien hizo el "pedido"
          $id_cliente_asociado = $this->session->userdata('id_cliente_asociado');
          $consecutivo = self::consecutivo_operacion(2,$data['id_tipo_pedido'],$data['id_tipo_factura']); 
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
          
          
          $this->db->select('peso_real,proceso_traspaso,id_tipo_pago, id_tipo_pedido, id_tipo_factura,comentario_traspaso, num_control');
          $this->db->select('m.id id_entrada, movimiento,movimiento_unico, movimiento_unico_apartado, id_empresa, id_descripcion, id_color, devolucion, m.num_partida');
          $this->db->select('id_composicion, id_calidad, referencia, id_medida, factura, cantidad_um, cantidad_royo, ancho');
          $this->db->select('codigo, comentario, id_estatus, id_lote, consecutivo');
          $this->db->select('fecha_entrada,consecutivo_venta');

          $this->db->select('id_usuario_apartado, id_cliente_apartado,  fecha_apartado');
          $this->db->select('precio, iva, id_pedido, id_factura,id_fac_orig, id_factura_original,incluir');
          $this->db->select('precio_anterior, precio_cambio, id_prorroga, fecha_vencimiento, consecutivo_cambio');

          $this->db->select('on_off');

          $this->db->select('"'.$this->session->userdata('config_tienda_activo').'" AS id_tienda_origen',FALSE);

  


          $this->db->from($this->registros_entradas.' As m');
          $this->db->join($this->usuarios.' As u' , 'u.id = m.id_usuario_apartado');

          if ($data["id_almacen"]!=0) {
                            $id_almacenid = ' AND ( m.id_almacen =  '.$data["id_almacen"].' ) ';  
                        } else {
                            $id_almacenid = '';
                }                         

                $where=  '(
                       (m.id_tipo_pedido='.$data['id_tipo_pedido'].' ) AND (m.id_tipo_factura='.$data['id_tipo_factura'].' ) AND
                      (m.id_apartado='.$data["id_apartado"].') and  (movimiento_unico_apartado='.$data['num_mov'].' ) AND ( proceso_traspaso = 0 ) AND ( estatus_salida = "0" )'.$id_almacenid.'
                    )';

          $this->db->where($where);     
          $result = $this->db->get();
          $objeto = $result->result();

          //return $objeto;




         //objeto para la transferencia remota

         $this->db->select('"'.$fecha_hoy.'" AS fecha_transferencia',false);
         $this->db->select('"'.$consecutivo.'" AS mov_salida',false); 
         $this->db->select('"'.$consecutivo_unico.'" AS mov_salida_unico',false); 
         $this->db->select('m.id_empresa, m.id_descripcion, m.id_color,m.id_calidad,m.id_composicion,m.referencia, m.id_medida, m.cantidad_um, m.cantidad_royo, m.ancho,m.peso_real,m.precio, m.codigo, m.id_estatus, m.id_lote,m.consecutivo, m.id_usuario_apartado,m.on_off,m.consecutivo_venta,prod.imagen');
         $this->db->select('"'.$id_session.'" AS id_usuario_salida',false);  
         $this->db->select('"'.$this->session->userdata('config_tienda_activo').'" AS id_tienda_origen',FALSE);

         $this->db->select('u.nombre AS nombre_usuario',false); 
         
         $this->db->select('c.color, c.hexadecimal_color, p.composicion, d.calidad');

          $this->db->from($this->registros_entradas.' As m');
          $this->db->join($this->productos.' As prod' , 'prod.referencia = m.referencia');
          $this->db->join($this->colores.' As c' , 'c.id = m.id_color');
          $this->db->join($this->composiciones.' As p' , 'p.id = m.id_composicion');
          $this->db->join($this->calidades.' As d' , 'd.id = m.id_calidad');
          $this->db->join($this->usuarios.' As u' , 'u.id = m.id_usuario_apartado');


          if ($data["id_almacen"]!=0) {
                            $id_almacenid = ' AND ( m.id_almacen =  '.$data["id_almacen"].' ) ';  
                        } else {
                            $id_almacenid = '';
            }                         

      
            $where=  '(
                       (m.id_tipo_pedido='.$data['id_tipo_pedido'].' ) AND (m.id_tipo_factura='.$data['id_tipo_factura'].' ) AND
                      (m.id_apartado='.$data["id_apartado"].') and  (movimiento_unico_apartado='.$data['num_mov'].' ) AND ( proceso_traspaso = 0 ) AND ( estatus_salida = "0" )'.$id_almacenid.'
                    )';


          $this->db->where($where);     
          $resulta = $this->db->get();
          $objeto_transferencia = $resulta->result();

         
//copiar a tabla "historico_registros_salidas" y historico_registros_traspasos si hay alguno 
          $dato = array();
          $consecutivo_traspaso = self::consecutivo_operacion(26,$data['id_tipo_pedido'],$data['id_tipo_factura']); 
          $consecutivo_traspaso_unico = self::consecutivo_operacion_unico(26); 
          $traspaso=0;
          $tienda_envio=0; //almacen a donde se va a enviar
          $tienda_on_off=0;
          foreach ($objeto as $key => $value) {
            $this->db->insert($this->historico_registros_salidas, $value); 
            
            if ($value->incluir==1) {
              $traspaso=1;
              $value_traspaso = $value;
              $value_traspaso->consecutivo_traspaso = $consecutivo_traspaso;
              $value_traspaso->consecutivo_traspaso_unico = $consecutivo_traspaso_unico;

              $this->db->insert($this->historico_registros_traspasos, $value_traspaso); 
            }

             $tienda_envio=$value->consecutivo_venta;
             $tienda_on_off=(int)$value->on_off;
          }



          //////////caso de transferencia a tienda
          if  ($tienda_on_off==1) { //si es una transferencia a tienda 

                       // remoto
                       $bd_transferencia = $this->load->database('remoto'.$tienda_envio, TRUE);
                      
                        //objeto_transferencia

                        foreach ($objeto_transferencia as $key => $value) {
                          $bd_transferencia->insert($bd_transferencia->dbprefix('remoto_registros_transferencia'), $value );
                        }

                        

                           //agregar al catalogo los colores los que faltan
                          $cons= 'INSERT INTO '.$bd_transferencia->dbprefix("catalogo_colores").' (color, hexadecimal_color)  
                          SELECT t.color, t.hexadecimal_color
                          FROM '.$bd_transferencia->dbprefix("remoto_registros_transferencia").' t
                          LEFT JOIN '.$bd_transferencia->dbprefix("catalogo_colores").' c ON t.color = c.color
                          AND t.hexadecimal_color = c.hexadecimal_color
                          where c.id is null
                          group by t.color, t.hexadecimal_color';
                          $bd_transferencia->query($cons);

                          //agregar al catalogo de composiciones los que faltan
                          $cons= 'INSERT INTO '.$bd_transferencia->dbprefix("catalogo_composicion").' (composicion)  
                          SELECT t.composicion
                          FROM '.$bd_transferencia->dbprefix("remoto_registros_transferencia").' t
                          LEFT JOIN '.$bd_transferencia->dbprefix("catalogo_composicion").' c ON t.composicion = c.composicion
                          where c.id is null
                          group by t.composicion';
                          $bd_transferencia->query($cons);

                          //agregar al catalogo de calidades los que faltan
                          $cons= 'INSERT INTO '.$bd_transferencia->dbprefix("catalogo_calidad").' (calidad)  
                          SELECT t.calidad
                          FROM '.$bd_transferencia->dbprefix("remoto_registros_transferencia").' t
                          LEFT JOIN '.$bd_transferencia->dbprefix("catalogo_calidad").' c ON t.calidad = c.calidad
                          where c.id is null
                          group by t.calidad';
                          $bd_transferencia->query($cons);


                          // actualizacion de id de color
                          $cons= 'UPDATE '.$bd_transferencia->dbprefix("remoto_registros_transferencia").' t 
                            INNER JOIN '.$bd_transferencia->dbprefix("catalogo_colores").' c
                            ON t.color = c.color AND t.hexadecimal_color = c.hexadecimal_color
                            SET t.id_new_color = c.id';
                          $bd_transferencia->query($cons);

                          // actualizacion de id de calidad 
                          $cons= 'UPDATE '.$bd_transferencia->dbprefix("remoto_registros_transferencia").' t 
                            INNER JOIN '.$bd_transferencia->dbprefix("catalogo_calidad").' c
                            ON t.calidad = c.calidad
                            SET t.id_new_calidad = c.id ';
                          $bd_transferencia->query($cons);

                        // actualizacion de id de  composicion
                          $cons= 'UPDATE '.$bd_transferencia->dbprefix("remoto_registros_transferencia").' t 
                            INNER JOIN '.$bd_transferencia->dbprefix("catalogo_composicion").' c
                            ON t.composicion = c.composicion
                            SET t.id_new_composicion = c.id ';
                          $bd_transferencia->query($cons);


                          

                          //crear la referencia  que faltan (id_descripcion, id_color,id_composicion, id_calidad)
                           //falta definir id_usuario, activo, imagen, img_20170927OMwe615
                          $cons= 'INSERT INTO '.$bd_transferencia->dbprefix("catalogo_productos").' (descripcion, id_composicion, id_color, id_calidad, precio, ancho,  id_usuario,comentario,imagen,activo,referencia)  
                         SELECT t.id_descripcion, t.id_new_composicion, t.id_new_color, t.id_new_calidad,t.precio,t.ancho, "usuario","transferencia",
                         RTRIM(SUBSTRING( t.imagen, LOCATE(".", t.imagen ) , 4 )) imagen,

                     0,
                                         CONCAT(DATE_FORMAT(CURDATE(),"%Y"),DATE_FORMAT(CURDATE(),"%m"),DATE_FORMAT(CURDATE(),"%d"),SUBSTRING("ABCDEFGHIJKLMNOPQRSTUVWXYZ", RAND() *26 +1, 1 ),SUBSTRING("ABCDEFGHIJKLMNOPQRSTUVWXYZ", RAND() *26 +1, 1 ),SUBSTRING("ABCDEFGHIJKLMNOPQRSTUVWXYZ", RAND() *26 +1, 1 ),SUBSTRING("ABCDEFGHIJKLMNOPQRSTUVWXYZ", RAND() *26 +1, 1 ),
                            FLOOR(RAND()*10),FLOOR(RAND()*10),FLOOR(RAND()*10)) referencia
                          FROM '.$bd_transferencia->dbprefix("remoto_registros_transferencia").' t
                          LEFT JOIN '.$bd_transferencia->dbprefix("catalogo_productos").' p
                          ON t.id_new_color = p.id_color AND t.id_new_composicion = p.id_composicion AND t.id_new_calidad = p.id_calidad
                          AND t.id_descripcion = p.descripcion
                          where p.id is null              
                          group by t.id_descripcion, t.id_new_color, t.id_new_composicion, t.id_new_calidad';
                          $bd_transferencia->query($cons);

                          
                           //actualizar las referencia en "'.$bd_transferencia->dbprefix("remoto_registros_transferencia").'"

                          $cons= 'UPDATE '.$bd_transferencia->dbprefix("remoto_registros_transferencia").' t 
                            INNER JOIN '.$bd_transferencia->dbprefix("catalogo_productos").' p
                            ON t.id_new_color = p.id_color AND t.id_new_composicion = p.id_composicion AND t.id_new_calidad = p.id_calidad
                            AND t.id_descripcion = p.descripcion
                            SET t.new_referencia = p.referencia';
                          $bd_transferencia->query($cons);

                           $cons= '
                                  UPDATE '.$bd_transferencia->dbprefix("catalogo_productos").' p 
                                  SET p.imagen = CONCAT("img_",p.referencia,p.imagen) where p.grupo="" AND p.imagen<>"" ';
                          $bd_transferencia->query($cons);

                          
                           /*aqui es donde hay q copiar las imagenes*/ 

                          //actualizar los grupos q ya existen con las nuevas referencias que fueron creadas
                          $cons= '
                                  UPDATE '.$bd_transferencia->dbprefix("catalogo_productos").' p 
                                    INNER JOIN  (
                                      SELECT  p.grupo, t.id_new_composicion, t.id_new_calidad, t.id_descripcion 
                                            FROM '.$bd_transferencia->dbprefix("remoto_registros_transferencia").' t
                                            INNER JOIN '.$bd_transferencia->dbprefix("catalogo_productos").' p
                                            ON t.id_new_composicion = p.id_composicion AND t.id_new_calidad = p.id_calidad
                                            AND t.id_descripcion = p.descripcion
                                            where p.grupo<>"" 
                                             group by t.id_descripcion, t.id_new_composicion, t.id_new_calidad
                                             ) t
                                ON t.id_new_composicion = p.id_composicion AND t.id_new_calidad = p.id_calidad
                                            AND t.id_descripcion = p.descripcion
                                SET p.grupo = t.grupo';
                          $bd_transferencia->query($cons);
                          //update `inven_catalogo_productos` set grupo="", imagen=""



                         //crear el grupo  que faltan (id_descripcion, id_composicion, id_calidad) e incluir la imagen

                          $cons= '
                                  UPDATE '.$bd_transferencia->dbprefix("catalogo_productos").' p 
                                    INNER JOIN  (
                                      SELECT  p.referencia, t.id_new_composicion, t.id_new_calidad, t.id_descripcion,
                                                           CONCAT(DATE_FORMAT(CURDATE(),"%Y"),DATE_FORMAT(CURDATE(),"%m"),DATE_FORMAT(CURDATE(),"%d"),SUBSTRING("ABCDEFGHIJKLMNOPQRSTUVWXYZ", RAND() *26 +1, 1 ),SUBSTRING("ABCDEFGHIJKLMNOPQRSTUVWXYZ", RAND() *26 +1, 1 ),SUBSTRING("ABCDEFGHIJKLMNOPQRSTUVWXYZ", RAND() *26 +1, 1 ),
                                              FLOOR(RAND()*10),FLOOR(RAND()*10),FLOOR(RAND()*10),FLOOR(RAND()*10)) grupo
                                            FROM '.$bd_transferencia->dbprefix("remoto_registros_transferencia").' t
                                            INNER JOIN '.$bd_transferencia->dbprefix("catalogo_productos").' p
                                            ON t.id_new_composicion = p.id_composicion AND t.id_new_calidad = p.id_calidad
                                            AND t.id_descripcion = p.descripcion
                                            where p.grupo="" 
                                             group by t.id_descripcion, t.id_new_composicion, t.id_new_calidad
                                             ) t

                                ON t.id_new_composicion = p.id_composicion AND t.id_new_calidad = p.id_calidad
                                            AND t.id_descripcion = p.descripcion AND p.grupo = ""
                                SET p.grupo = t.grupo';
                          $bd_transferencia->query($cons);

                      
                      $this->db = $this->load->database('default', TRUE);
          }            


          
 //actualizar el consecutivo en "operaciones" == "traspasos"
          if ($traspaso==1) {
              
              if ($data['id_tipo_pedido']==2) {
                   $this->db->set( 'conse_surtido', 'conse_surtido+1', FALSE  );  
              }  else if ($data['id_tipo_factura']==1) {
                  $this->db->set( 'conse_factura', 'conse_factura+1', FALSE  );  
              } else {
                  $this->db->set( 'conse_remision', 'conse_remision+1', FALSE  );  
              }
              $this->db->set( 'consecutivo', 'consecutivo+1', FALSE  );  

              $this->db->set( 'id_usuario', $id_session );
              $this->db->where('id',26);
              $this->db->update($this->operaciones);          
          }
              
//actualizar (consecutivo) en tabla "operacion"   == "salida"
          if ($data['id_tipo_pedido']==3) {
               $this->db->set( 'conse_bodega', 'conse_bodega+1', FALSE  );  
          } else if ($data['id_tipo_pedido']==2) {
               $this->db->set( 'conse_surtido', 'conse_surtido+1', FALSE  );  
          }  else if ($data['id_tipo_factura']==1) {
              $this->db->set( 'conse_factura', 'conse_factura+1', FALSE  );  
          } else {
              $this->db->set( 'conse_remision', 'conse_remision+1', FALSE  );  
          }
          $this->db->set( 'consecutivo', 'consecutivo+1', FALSE  );  

          $this->db->set( 'id_usuario', $id_session );
          $this->db->where('id',2);
          $this->db->update($this->operaciones);

//**eliminar los registros en "registros_entradas"

        if  ($tienda_on_off!=2) {   // si es diferente a bodega que lo elimine

            if ($data["id_almacen"]!=0) {
                            $id_almacenid = ' AND ( id_almacen =  '.$data["id_almacen"].' ) ';  
                        } else {
                            $id_almacenid = '';
                }                         
                $where_borrar=  '(
                      (id_tipo_pedido='.$data['id_tipo_pedido'].' ) AND (id_tipo_factura='.$data['id_tipo_factura'].' ) AND
                       (id_apartado='.$data["id_apartado"].') and  (movimiento_unico_apartado='.$data['num_mov'].' ) AND ( proceso_traspaso = 0 ) AND ( estatus_salida = "0" )'.$id_almacenid.'
                    )';
          $this->db->where($where_borrar);            
          $this->db->delete($this->registros_entradas);  //eliminar los que son transferidos
       } else {  //si es el caso de la bodega q entonces solo actualice el nuevo consecutivo de entrada

              $consecutivo_entrada = self::consecutivo_operacion_entrada(1, 3); //$data['id_factura'] =3
              $consecutivo_unico_entrada = self::consecutivo_operacion_unico(1); 

              //actualizar (consecutivo) en tabla "operacion"  
              $this->db->set( 'conse_bodega', 'conse_bodega+1', FALSE  );  
              $this->db->set( 'consecutivo', 'consecutivo+1', FALSE  );  
              $this->db->set( 'id_usuario', $id_session );
              $this->db->where('id',1);
              $this->db->update($this->operaciones);




            if ($data["id_almacen"]!=0) {
                            $id_almacenid = ' AND ( id_almacen =  '.$data["id_almacen"].' ) ';  
                        } else {
                            $id_almacenid = '';
                }                         
                $id_almacenid = '';
                $where_actualizar=  '(
                      (id_tipo_pedido='.$data['id_tipo_pedido'].' ) AND (id_tipo_factura='.$data['id_tipo_factura'].' ) AND
                       (id_apartado='.$data["id_apartado"].') and  (movimiento_unico_apartado='.$data['num_mov'].' ) AND ( proceso_traspaso = 0 ) AND ( estatus_salida = "0" )'.$id_almacenid.'
                    )';


             /*igual
              id_empresa 
              id_descripcion, id_color, id_composicion, id_calidad, referencia, num_partida,
              id_medida, cantidad_um, peso_real, cantidad_royo, ancho, precio, codigo,  id_estatus, id_lote, consecutivo
              fecha_mac
              */

              //$this->db->set( 'factura', 'bod-'.$consecutivo_unico_entrada, FALSE  );
              $this->db->set( 'id_almacen', 'consecutivo_venta', FALSE  );  //en consecutivo venta es donde trae el numero de almacen
              
              $this->db->set( 'consecutivo_venta', 0 );  //que lo quite 


              $this->db->set( 'id_tipo_pago', 2, FALSE  ); //de contado
              $this->db->set( 'id_factura', 3, FALSE  );  //bodega
              $this->db->set( 'id_fac_orig', 3, FALSE  ); //

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
              $this->db->set( 'id_operacion', 1 );
              $this->db->set( 'id_cargador', '"'.htmlspecialchars($data['id_cargador']).'"' );

              $this->db->set( 'id_apartado', 0 );  //que lo quite 
              $this->db->set( 'id_usuario_apartado', '' );  //que lo quite 
              $this->db->set( 'id_cliente_apartado', 0 );  //que lo quite 


              $this->db->where($where_actualizar);
              $this->db->update($this->registros_entradas);
              


            //aqui lista todos los datos que fueron entrados por un usuario especifico   
                        //$this->db->select($consecutivo.' AS movimiento',false); //cambio
                    //$this->db->select($consecutivo_unico.' AS movimiento_unico',false); //cambio

                    $this->db->select('id_empresa, factura, id_descripcion, id_color, id_composicion, id_calidad, referencia, num_partida,id_almacen,id_factura,id_fac_orig,iva, id_tipo_pago');
                    $this->db->select('id_medida, cantidad_um, peso_real, cantidad_royo, ancho, precio, codigo, comentario, id_estatus, id_lote, consecutivo');
                    $this->db->select('id_cargador, id_usuario, fecha_mac, id_operacion');
                    $this->db->select('fecha_entrada, movimiento, movimiento_unico');
                    $this->db->from($this->registros_entradas);
                    $this->db->where('id_usuario',$id_session);
                    $this->db->where('id_operacion',1);
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
                      (m.mov_salida_unico='.$consecutivo_unico.' ) AND (m.id_tipo_pedido='.$data['id_tipo_pedido'].' ) AND (m.id_tipo_factura='.$data['id_tipo_factura'].' ) '.$id_almacenid.'
                       
                    )';
           $this->db->where($where);           


            $result = $this->db->get();
        
            if ( $result->num_rows() > 0 )
               return $result->row();
            else
               return False;
            $result->free_result();

          //return $dato;

          //$result->free_result();          

}             


       public function consecutivo_operacion_entrada( $id,$id_factura ){
              $this->db->select("o.consecutivo,o.conse_factura,o.conse_remision,o.conse_surtido, o.conse_bodega");         
              $this->db->from($this->operaciones.' As o');
              $this->db->where('o.id',$id);
              $result = $this->db->get( );
                  if ($result->num_rows() > 0) {
                        $consecutivo_actual = ( ($id_factura==1) ? $result->row()->conse_factura : (($id_factura==3) ? $result->row()->conse_bodega : $result->row()->conse_remision ) );
                        return $consecutivo_actual+1;
                  }                    
                  else 
                      return FALSE;
                  $result->free_result();
       }  


/////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////
          //2da regilla. Listado que servira para reportes solo de BODEGAS
/////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////

/////////////////////////////////listado de la regilla///////////////////////////
        public function listado_movimientos_registros_bodega($data){

          $id_session = $this->session->userdata('id');
          $nombre_completo = $this->session->userdata('nombre_completo');
                    
          $this->db->select('m.id, m.mov_salida, m.mov_salida_unico, m.movimiento,m.id_empresa, m.factura, m.id_descripcion, m.id_operacion,m.devolucion, m.num_partida');
          $this->db->select('m.id_color, m.id_composicion, m.id_calidad, m.referencia');
          $this->db->select('m.id_medida, m.cantidad_um, m.cantidad_royo, m.ancho, m.precio, m.codigo, m.comentario');
          $this->db->select('m.id_estatus, m.id_lote, m.consecutivo, m.id_cargador, m.id_usuario, m.fecha_mac fecha');

          $this->db->select('c.hexadecimal_color, u.medida,p.nombre, ca.nombre cargador');
          $this->db->select("( CASE WHEN m.devolucion <> 0 THEN 'red' ELSE 'black' END ) AS color_devolucion", FALSE);
          
          //$this->db->select("( CASE WHEN id_usuario_apartado <> '' THEN id_usuario_apartado ELSE '".$id_session."' END ) AS id_usuario_apartado", FALSE);
          $this->db->select("( CASE WHEN m.id_usuario_apartado <> '' THEN CONCAT(us.nombre,' ', us.apellidos) ELSE '".$nombre_completo."' END ) AS nom_vendedor", FALSE);
          
          $this->db->select("( CASE WHEN m.id_apartado = 3 THEN m.consecutivo_venta ELSE m.id_cliente_apartado END ) AS mov_pedido", FALSE);
          

          

          $this->db->select('a.almacen');
          $this->db->select("tp.tipo_pedido,m.id_tipo_pedido");          
          $this->db->select("tf.tipo_factura,m.id_tipo_factura");          
          $this->db->select('m.peso_real');
          $this->db->select("m1.peso_real peso_entrada");          

            $this->db->select("prod.codigo_contable");  

          $this->db->from($this->historico_registros_salidas.' as m');
          $this->db->join($this->almacenes.' As a' , 'a.id = m.id_almacen'); // AND a.activo=1
          $this->db->join($this->historico_registros_entradas.' as m1' , 'm1.codigo = m.codigo AND m1.movimiento = m.movimiento','LEFT');
          $this->db->join($this->productos.' As prod' , 'prod.referencia = m.referencia','LEFT');
          $this->db->join($this->colores.' As c' , 'c.id = m.id_color','LEFT');
          $this->db->join($this->unidades_medidas.' As u' , 'u.id = m.id_medida','LEFT');
          $this->db->join($this->proveedores.' As p' , 'p.id = m.id_cliente','LEFT');
          $this->db->join($this->cargadores.' As ca' , 'ca.id = m.id_cargador','LEFT');
          $this->db->join($this->usuarios.' As us' , 'us.id = m.id_usuario_apartado','LEFT');
          $this->db->join($this->tipos_pedidos.' As tp' , 'tp.id = m.id_tipo_pedido','LEFT');
          $this->db->join($this->tipos_facturas.' As tf' , 'tf.id = m.id_tipo_factura','LEFT');



          $this->db->where('m.id_tipo_pedido',$data["id_tipo_pedido"]);
          $this->db->where('m.id_tipo_factura',$data["id_tipo_factura"]);
          $this->db->where('m.id_operacion',2);
          $this->db->where('m.mov_salida_unico',$data['encabezado']['num_movimiento']);

          $this->db->group_by('m.id');

          if (!(isset($data['id_estatus']))) {
             $this->db->where('m.id_estatus !=',15);
          } else if ($data['id_estatus']==15) {
             //$id_estatusid = ' and ( m.id_estatus =  '.$data['id_estatus'].' ) ';  
             $this->db->where('m.id_estatus',$data['id_estatus']);
          } else {
             //$id_estatusid = '';
            $this->db->where('m.id_estatus !=',15);
          }               


          $this->db->order_by('m.id_lote', 'asc'); 
          $this->db->order_by('m.codigo', 'asc'); 
          $this->db->order_by('m.consecutivo', 'asc'); 

           $result = $this->db->get();
        
            if ( $result->num_rows() > 0 )
               return $result->result();
            else
               return False;
            $result->free_result();
        }      

 
/////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////
          //1ra y 2da regilla. Listado que servira para reportes de ambas regillas
/////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////

/////////////////////////////////listado de la regilla///////////////////////////
        public function listado_movimientos_registros($data){

          $id_session = $this->session->userdata('id');
          $nombre_completo = $this->session->userdata('nombre_completo');
                    
          $this->db->select('m.id, m.mov_salida, m.mov_salida_unico, m.movimiento,m.id_empresa, m.factura, m.id_descripcion, m.id_operacion,m.devolucion, m.num_partida');
          $this->db->select('m.id_color, m.id_composicion, m.id_calidad, m.referencia');
          $this->db->select('m.id_medida, m.cantidad_um, m.cantidad_royo, m.ancho, m.precio, m.codigo, m.comentario');
          $this->db->select('m.id_estatus, m.id_lote, m.consecutivo, m.id_cargador, m.id_usuario, m.fecha_mac fecha');

          $this->db->select('c.hexadecimal_color, u.medida,p.nombre, ca.nombre cargador');
          $this->db->select("( CASE WHEN m.devolucion <> 0 THEN 'red' ELSE 'black' END ) AS color_devolucion", FALSE);
          
          //$this->db->select("( CASE WHEN id_usuario_apartado <> '' THEN id_usuario_apartado ELSE '".$id_session."' END ) AS id_usuario_apartado", FALSE);
          $this->db->select("( CASE WHEN m.id_usuario_apartado <> '' THEN CONCAT(us.nombre,' ', us.apellidos) ELSE '".$nombre_completo."' END ) AS nom_vendedor", FALSE);
          
          $this->db->select("( CASE WHEN m.id_apartado = 3 THEN m.consecutivo_venta ELSE m.id_cliente_apartado END ) AS mov_pedido", FALSE);
          

          

          $this->db->select('a.almacen');
          $this->db->select("tp.tipo_pedido,m.id_tipo_pedido");          
          $this->db->select("tf.tipo_factura,m.id_tipo_factura");          
          $this->db->select('m.peso_real');
          $this->db->select("m1.peso_real peso_entrada");          

            $this->db->select("prod.codigo_contable");  

          $this->db->from($this->historico_registros_salidas.' as m');
          $this->db->join($this->almacenes.' As a' , 'a.id = m.id_almacen'); // AND a.activo=1
          $this->db->join($this->historico_registros_entradas.' as m1' , 'm1.codigo = m.codigo AND m1.movimiento = m.movimiento','LEFT');
          $this->db->join($this->productos.' As prod' , 'prod.referencia = m.referencia','LEFT');
          $this->db->join($this->colores.' As c' , 'c.id = m.id_color','LEFT');
          $this->db->join($this->unidades_medidas.' As u' , 'u.id = m.id_medida','LEFT');
          $this->db->join($this->proveedores.' As p' , 'p.id = m.id_cliente','LEFT');
          $this->db->join($this->cargadores.' As ca' , 'ca.id = m.id_cargador','LEFT');
          $this->db->join($this->usuarios.' As us' , 'us.id = m.id_usuario_apartado','LEFT');
          $this->db->join($this->tipos_pedidos.' As tp' , 'tp.id = m.id_tipo_pedido','LEFT');
          $this->db->join($this->tipos_facturas.' As tf' , 'tf.id = m.id_tipo_factura','LEFT');



          $this->db->where('m.id_tipo_pedido',$data["id_tipo_pedido"]);
          $this->db->where('m.id_tipo_factura',$data["id_tipo_factura"]);
          $this->db->where('m.id_operacion',2);
          $this->db->where('m.mov_salida_unico',$data['encabezado']['num_movimiento']);

          $this->db->group_by('m.id');

          if (!(isset($data['id_estatus']))) {
             $this->db->where('m.id_estatus !=',15);  //15=Ajuste
          } else if ($data['id_estatus']==15) {
             //$id_estatusid = ' and ( m.id_estatus =  '.$data['id_estatus'].' ) ';  
             $this->db->where('m.id_estatus',$data['id_estatus']);
          } else {
             //$id_estatusid = '';
            $this->db->where('m.id_estatus !=',15);
          }               


          $this->db->order_by('m.id_lote', 'asc'); 
          $this->db->order_by('m.codigo', 'asc'); 
          $this->db->order_by('m.consecutivo', 'asc'); 

           $result = $this->db->get();
        
            if ( $result->num_rows() > 0 )
               return $result->result();
            else
               return False;
            $result->free_result();
        }      
        

/////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////
          //
/////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////



    //solo para quitar traspaso a los que no se incluyeron a la salida
    public function traspaso_quitar_apartados($data) {
            $id_almacen= $data['id_almacen'];
                 $porciento_aplicar = 16;    

            $sql= 'UPDATE '.$this->registros.' as m JOIN '.$this->usuarios.' As u  ON u.id = m.id_usuario_apartado'.
              ' SET 
                  m.id_tipo_pedido=0,m.id_tipo_factura=0,
                  m.iva= ((m.id_factura_original = 1)*'.$porciento_aplicar.'),
                  m.incluir =0, m.id_factura = m.id_factura_original,
                  m.id_factura_original =0
              ';

              $cond_traspaso = ' AND ( ( m.id_factura_original <>  0 ) AND ( m.incluir =  1 ) )';  
              
              //$cond_traspaso = ' AND ( ( m.id_tipo_factura =  0 ) OR ( m.id_tipo_factura =  '.$data['id_tipo_factura'].' ) )'; 

              //$cond_filtro_tipo = ' AND ( ( m.id_tipo_factura =  '.$data['id_tipo_factura'].' ) )'; 

              $cond_filtro_tipo = ' AND ( 
                ( ( m.id_tipo_factura =  '.$data['id_tipo_factura'].' ) AND ( m.id_tipo_pedido =  '.$data['id_tipo_pedido'].' ) )
                )';  


              if ($id_almacen!=0) {
                      $id_almacenid = ' AND ( m.id_almacen =  '.$id_almacen.' ) ';  
                  } else {
                      $id_almacenid = '';
              }             

              $where = ' where (
                            (
                              (( m.id_apartado = 6 ) or ( m.id_apartado = 3 ) ) AND ( u.id_cliente = '.$data['id_cliente'].' )
                              
                            )'.$id_almacenid.$cond_traspaso.$cond_filtro_tipo.'  

                        )';   

          $consulta = $this->db->query($sql.$where);
          return TRUE;
    }      


      public function quitar_apartados( $data ){
            $id_almacen= $data['id_almacen'];

            $sql= 'UPDATE '.$this->registros.' as m JOIN '.$this->usuarios.' As u  ON u.id = m.id_usuario_apartado'.
              ' SET m.id_apartado = 0, m.id_usuario_apartado ="", m.id_cliente_apartado=0,
              m.id_prorroga=0,m.fecha_apartado="",m.fecha_vencimiento="",m.id_tipo_pedido=0,m.id_tipo_factura=0';

              if ($id_almacen!=0) {
                      $id_almacenid = ' AND ( m.id_almacen =  '.$id_almacen.' ) ';  
                  } else {
                      $id_almacenid = '';
              }             

              //$cond_filtro_tipo =  ' AND ( ( m.id_tipo_factura =  0 ) OR ( m.id_tipo_factura =  '.$data['id_tipo_factura'].' ) )'; 

              $cond_filtro_tipo = ' AND ( ( ( m.id_tipo_factura =  0 ) AND ( m.id_tipo_pedido =  0 ) ) OR 
                ( ( m.id_tipo_factura =  '.$data['id_tipo_factura'].' ) AND ( m.id_tipo_pedido =  '.$data['id_tipo_pedido'].' ) )
                )';  


              $where = ' where (
                            (
                              (( m.id_apartado = 6 ) or ( m.id_apartado = 3 ) ) AND ( u.id_cliente = '.$data['id_cliente'].' )
                              
                            )'.$id_almacenid.$cond_filtro_tipo.'  

                        )';   

          $consulta = $this->db->query($sql.$where);
          return TRUE;

      }


      public function cantidad_apartados($data){
              $id_almacen= $data['id_almacen'];
              $id_session = $this->session->userdata('id');
              $this->db->from($this->registros.' as m');
              $this->db->join($this->usuarios.' As u' , 'u.id = m.id_usuario_apartado','LEFT');
              

                if ($id_almacen!=0) {
                    $id_almacenid = ' AND ( m.id_almacen =  '.$id_almacen.' ) ';  
                } else {
                    $id_almacenid = '';
                } 

                //$cond_traspaso = ' AND ( ( m.id_factura_original <>  0 ) AND ( m.incluir =  1 ) )';  

                $cond_traspaso = ' AND ( ( ( m.id_tipo_factura =  0 ) AND ( m.id_tipo_pedido =  0 ) ) OR 
                ( ( m.id_tipo_factura =  '.$data['id_tipo_factura'].' ) AND ( m.id_tipo_pedido =  '.$data['id_tipo_pedido'].' ) )
                )';  

                $where = '(
                          (
                            (( m.id_apartado = 6 ) or ( m.id_apartado = 3 ) ) AND ( u.id_cliente = '.$data['id_cliente'].' )
                            AND ( m.estatus_salida = "0" )
                          )'.$id_almacenid.$cond_traspaso.' 

                      )';   

                $this->db->where($where); 



              $cant = $this->db->count_all_results();          
     
              if ( $cant > 0 )
                 return 1;
              else
                 return 2;         
       }

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

      public function total_entrada_home($where){
              $id_session = $this->session->userdata('id');
              $this->db->from($this->registros.' as m');
              $this->db->join($this->colores.' As c' , 'c.id = m.id_color','LEFT');
              $this->db->join($this->unidades_medidas.' As u' , 'u.id = m.id_medida','LEFT');
              $this->db->join($this->proveedores.' As p' , 'p.id = m.id_empresa','LEFT');
              $this->db->join($this->usuarios.' As us' , 'us.id = m.id_usuario_apartado','LEFT');

              $this->db->where($where);
              $cant = $this->db->count_all_results();          
     
              if ( $cant > 0 )
                 return $cant;
              else
                 return 0;         
       }     


      public function buscador_entrada($data){

          $cadena = addslashes($data['search']['value']);
          $inicio = $data['start'];
          $largo = $data['length'];

           $id_tipo_factura = $data['id_tipo_factura'];
           $id_tipo_pedido = $data['id_tipo_pedido'];

                $producto_filtro = addslashes($data['producto_filtro']); 
                $color_filtro = $data['color_filtro']; 
                //$ancho_filtro = number_format((float)$data['ancho_filtro'],2,'.','');  
                //$ancho_filtro = (float)$data['ancho_filtro'];  
                $ancho_filtro   = $data['ancho_filtro'];  
                $factura_filtro = addslashes($data['factura_filtro']);           
                $proveedor_filtro = addslashes($data['proveedor_filtro']);    


          $columa_order = $data['order'][0]['column'];
                 $order = $data['order'][0]['dir'];

          switch ($columa_order) {
                   case '0':
                        $columna = 'm.codigo';
                     break;
                   case '1':
                        $columna = 'm.id_descripcion';
                     break;
                   case '2':
                        $columna = 'c.color';
                     break;
                   case '3':
                        $columna = 'm.cantidad_um';
                     break;
                   case '4':
                        $columna = 'm.ancho';
                     break;
                   case '5':
                        $columna = 'm.movimiento';
                     break;
                   case '6':
                              $columna= 'p.nombre';
                     break;
                   case '7':
                              $columna= 'm.id_lote, m.consecutivo';  
                     break;
                   
                   default:
                       $columna = 'm.codigo';
                     break;
                 }                 
          

          $id_session = $this->db->escape($this->session->userdata('id'));

          $this->db->select("SQL_CALC_FOUND_ROWS *", FALSE); //

          $this->db->select('m.id, m.movimiento,m.id_empresa, m.factura, m.id_factura,m.id_fac_orig, m.id_descripcion, m.id_operacion,m.devolucion, m.num_partida');
          $this->db->select('m.id_color, m.id_composicion, m.id_calidad, m.referencia');
          $this->db->select('m.id_medida, m.cantidad_um, m.cantidad_royo, m.ancho, m.precio, m.codigo, m.comentario');
          $this->db->select('m.id_estatus, m.id_lote, m.consecutivo, m.id_cargador, m.id_usuario, m.fecha_mac fecha');

          $this->db->select('c.hexadecimal_color, c.color, u.medida,p.nombre, m.id_apartado');
         
          $this->db->select("( CASE WHEN m.id_medida = 1 THEN m.cantidad_um ELSE 0 END ) AS metros", FALSE);
          $this->db->select("( CASE WHEN m.id_medida = 2 THEN m.cantidad_um ELSE 0 END ) AS kilogramos", FALSE);

          

          $this->db->from($this->registros.' as m');
          $this->db->join($this->colores.' As c' , 'c.id = m.id_color','LEFT');
          $this->db->join($this->unidades_medidas.' As u' , 'u.id = m.id_medida','LEFT');
          $this->db->join($this->proveedores.' As p' , 'p.id = m.id_empresa','LEFT');
          $this->db->join($this->usuarios.' As us' , 'us.id = m.id_usuario_apartado','LEFT');
         
          //filtro de busqueda



        $donde1 = '';
        $donde = '';
        if ($producto_filtro!="") {
            $donde .= ' AND ( m.id_descripcion  =  "'.$producto_filtro.'" ) ';
        } 

        if ($color_filtro!="") {
            $donde .= ' AND ( m.id_color  =  '.$color_filtro.' ) ';
        } 
                

        if ($ancho_filtro!=0) {
            //$donde .= ' AND ( CAST(m.ancho AS DECIMAL)   =  CAST('.$ancho_filtro.' AS DECIMAL) ) ';
            $donde .= ' AND ( ROUND(m.ancho, 3)   =  ROUND('.$ancho_filtro.' ,3) ) ';
        } 
                

        if ($factura_filtro!="") {
            $donde .= ' AND ( m.factura  =  "'.$factura_filtro.'" ) ';
        } 
                
        if ($proveedor_filtro!="") {
            $donde .= ' AND ( p.nombre  =  "'.$proveedor_filtro.'" ) ';
        } 
                           

         //este no hace falta en pedido porq no se filtra
          if ($id_tipo_factura!=0) {
              $id_tipo_facturaid = ' AND ( m.id_factura =  '.$id_tipo_factura.' )  AND ( m.id_tipo_pedido  <>2  ) ';  
              //$id_tipo_facturaid = '';
          } else {
              //$id_tipo_facturaid = '';
              $id_tipo_facturaid = ' AND (( m.id_tipo_pedido  =0  ) OR ( m.id_tipo_pedido  =2  ) )';  
          } 

        
          $where = '(
                      (
                        (
                          ( ( us.id_cliente = '.$data['id_cliente'].' )  AND  ( (m.id_apartado = 3)  or ( m.id_apartado = 6 ) ) ) OR
                          (( m.id_apartado = 0 ) AND ( m.id_operacion = "1" ) )
                        )  AND ( m.proceso_traspaso = 0 ) AND ( m.estatus_salida = "0" ) AND (m.id_almacen = '.$data['id_almacen'].' )  '.$donde.'

                      )'.$id_tipo_facturaid.' 
                       AND

                      (
                        ( m.codigo LIKE  "%'.$cadena.'%" ) OR (m.id_descripcion LIKE  "%'.$cadena.'%") OR (c.color LIKE  "%'.$cadena.'%")  OR
                        ( CONCAT(m.cantidad_um," ",u.medida) LIKE  "%'.$cadena.'%" ) OR (CONCAT(m.ancho," cm") LIKE  "%'.$cadena.'%")  OR
                        ( m.movimiento LIKE  "%'.$cadena.'%" ) OR  
                        (p.nombre LIKE  "%'.$cadena.'%") OR  (CONCAT(m.id_lote,"-",m.consecutivo) LIKE  "%'.$cadena.'%") '.
                        $donde1
                       .')


            )';   

          $where_total = '(
                        (
                          ( ( us.id_cliente = '.$data['id_cliente'].' )  AND  ( (m.id_apartado = 3)  or ( m.id_apartado = 6 ) ) ) OR
                          (( m.id_apartado = 0 ) AND ( m.id_operacion = "1" ) )
                        )  AND ( m.estatus_salida = "0" ) AND (m.id_almacen = '.$data['id_almacen'].' )
                        '.$id_tipo_facturaid.'
                       )';
          $this->db->where($where);

          //ordenacion
          $this->db->order_by('m.id_apartado', 'desc'); 
          $this->db->order_by($columna, $order); 
    


          //paginacion
          $this->db->limit($largo,$inicio); 


          $result = $this->db->get();

              if ( $result->num_rows() > 0 ) {

                    $cantidad_consulta = $this->db->query("SELECT FOUND_ROWS() as cantidad");
                    $found_rows = $cantidad_consulta->row(); 
                    $registros_filtrados =  ( (int) $found_rows->cantidad);

                  $retorno= " ";  
                  foreach ($result->result() as $row) {
                            $dato[]= array(
                                      0=>$row->codigo,
                                      1=>$row->id_descripcion,
                                      2=>$row->color.
                                        '<div style="background-color:#'.$row->hexadecimal_color.';display:block;width:15px;height:15px;margin:0 auto;"></div>',
                                      3=>$row->cantidad_um.' '.$row->medida,
                                      4=>$row->ancho.' cm',
                                      5=>
                                           '<a style="  padding: 1px 0px 1px 0px;" href="'.base_url().'procesar_entradas/'.base64_encode($row->movimiento).'/'.base64_encode($row->devolucion).'/'.base64_encode($retorno).'/'.base64_encode($row->id_fac_orig).'/'.base64_encode($row->id_estatus).'"
                                               type="button" class="btn btn-success btn-block">'.$row->movimiento.'</a>', 
                                      6=>$row->nombre,
                                      7=>$row->id_lote.'-'.$row->consecutivo,
                                      8=>$row->id,
                                      9=>$row->id_apartado,
                                      10=>$row->num_partida,
                                      11=>$row->metros,
                                      12=>$row->kilogramos,
                                    );
                      }



                      return json_encode ( array(
                        "draw"            => intval( $data['draw'] ),
                        "recordsTotal"    => intval( self::total_entrada_home($where_total) ), 
                        "recordsFiltered" => $registros_filtrados, 
                        "data"            =>  $dato,
                        "totales"            =>  array("pieza"=>intval( self::total_campos_salida_home($where_total)->pieza ), "metro"=>floatval( self::total_campos_salida_home($where_total)->metros ), "kilogramo"=>floatval( self::total_campos_salida_home($where_total)->kilogramos )),  
                      ));
                    
              }   
              else {
                  $output = array(
                  "draw" =>  intval( $data['draw'] ),
                  "recordsTotal" => 0,
                  "recordsFiltered" =>0,
                  "aaData" => array(),
                   "totales"            =>  array("pieza"=>intval( self::total_campos_salida_home($where_total)->pieza ), "metro"=>floatval( self::total_campos_salida_home($where_total)->metros ), "kilogramo"=>floatval( self::total_campos_salida_home($where_total)->kilogramos )),  
                  );
                  $array[]="";
                  return json_encode($output);
                  

              }

              $result->free_result();           

      }  
      

  public function total_campos_salida_home($where) {

              $this->db->select("SUM((id_medida =1) * cantidad_um) as metros", FALSE);
              $this->db->select("SUM((id_medida =2) * cantidad_um) as kilogramos", FALSE);
              $this->db->select("COUNT(m.id_medida) as 'pieza'");
              
             
              $this->db->from($this->registros.' as m');
              $this->db->join($this->usuarios.' As us' , 'us.id = m.id_usuario_apartado','LEFT');

              $this->db->where($where);

             $result = $this->db->get();
          
              if ( $result->num_rows() > 0 )
                 return $result->row();
              else
                 return False;
              $result->free_result();              

       }  



      public function total_registros(){
              $id_session = $this->session->userdata('id');
              $this->db->from($this->registros.' as m');
              $this->db->where('m.id_usuario',$id_session);
              $this->db->where('m.id_operacion',1);
              $this->db->where('m.estatus_salida',0);


              $cant = $this->db->count_all_results();          
     
              if ( $cant > 0 )
                 return $cant;
              else
                 return 0;         
       }      

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    

       public function buscador_cambio($data){

          $cadena = addslashes($data['search']['value']);
          $inicio = $data['start'];
          $largo = $data['length'];
          $codigo= $data['codigo'];

          $id_session = $this->db->escape($this->session->userdata('id'));

          //$this->db->select('m.referencia'); 
          // , m.id_color,m.movimiento, m.fecha_entrada, p.nombre proveedor, m.factura,
          //$this->db->select('m.peso_real');m.id_medida, m.ancho,m.id,m.id_lote,


          $this->db->select("SQL_CALC_FOUND_ROWS(m.id)"); //
          $this->db->select(' m.num_partida');
          $this->db->select('m.codigo,m.id_descripcion,c.color,c.hexadecimal_color,  co.composicion, ca.calidad');
          $this->db->select('m.cantidad_um');
          $this->db->select('m.id_estatus, consecutivo_cambio,u.medida,m.comentario ');
          $this->db->select("prod.codigo_contable");  

          $this->db->from($this->registros_cambios.' as m');
          $this->db->join($this->productos.' As prod' , 'prod.referencia = m.referencia','LEFT');
          $this->db->join($this->colores.' As c' , 'c.id = m.id_color','LEFT');
          $this->db->join($this->composiciones.' As co' , 'co.id = m.id_composicion','LEFT');
          $this->db->join($this->calidades.' As ca' , 'ca.id = m.id_calidad','LEFT');
          $this->db->join($this->proveedores.' As p' , 'p.id = m.id_empresa','LEFT');
          $this->db->join($this->unidades_medidas.' As u' , 'u.id = m.id_medida','LEFT');
         
          //filtro de busqueda

          $where = '(
                      (
                        ( m.codigo = "'.$codigo.'" ) 
                      ) 
            )';   
          $this->db->where($where);
    
          //ordenacion

          $this->db->order_by('m.consecutivo_cambio', 'asc'); 

          //paginacion
         // $this->db->limit($largo,$inicio); 


          $result = $this->db->get();

              if ( $result->num_rows() > 0 ) {

                    $cantidad_consulta = $this->db->query("SELECT FOUND_ROWS() as cantidad");
                    $found_rows = $cantidad_consulta->row(); 
                    $registros_filtrados =  ( (int) $found_rows->cantidad);


                  foreach ($result->result() as $row) {
                            $dato[]= array(
                                      0=>$row->codigo,
                                      1=>$row->id_descripcion,
                                      2=>$row->color.'<div style="margin-right: 15px;float:left;background-color:#'.$row->hexadecimal_color.';width:15px;height:15px;"></div>',
                                      3=>$row->composicion,
                                      4=>$row->calidad,
                                      5=>$row->cantidad_um.' '.$row->medida,
                                      6=>$row->consecutivo_cambio,
                                      7=>$row->comentario,
                                      8=>$row->num_partida,
                                      9=>$row->codigo_contable,            
                                      10=>$row->id_estatus,   
                                      
                                    );
                      }

                      return json_encode ( array(
                        "draw"            => intval( $data['draw'] ),
                        "recordsTotal"    => $registros_filtrados, 
                        "recordsFiltered" => $registros_filtrados, //intval( $result->num_rows() ),   //$recordsFiltered
                        "data"            =>  $dato //self::data_output( $columns, $data )
                      ));
                    
              }   
              else {
                  //cuando este vacio la tabla que envie este
                //http://www.datatables.net/forums/discussion/21311/empty-ajax-response-wont-render-in-datatables-1-10
                  $output = array(
                  "draw" =>  intval( $data['draw'] ),
                  "recordsTotal" => 0,
                  "recordsFiltered" =>0,
                  "aaData" => array()
                  );
                  $array[]="";
                  return json_encode($output);
                  

              }

              $result->free_result();           

      }  




       public function buscador_salida($data){

          $cadena = addslashes($data['search']['value']);
          $inicio = $data['start'];
          $largo = $data['length'];
          

          $id_session = $this->db->escape($this->session->userdata('id'));

          $this->db->select("SQL_CALC_FOUND_ROWS *", FALSE); //

          $this->db->select('m.id, m.movimiento,m.id_empresa, m.factura, m.id_factura,m.id_fac_orig, m.id_descripcion, m.id_operacion,m.devolucion, m.num_partida');
          $this->db->select('m.id_color, m.id_composicion, m.id_calidad, m.referencia');
          $this->db->select('m.peso_real');

          $this->db->select('m.id_medida, m.cantidad_um, m.cantidad_royo, m.ancho, m.precio, m.codigo, m.comentario');
          $this->db->select('m.id_estatus, m.id_lote, m.consecutivo, m.id_cargador, m.id_usuario, m.fecha_mac fecha,  m.id_apartado');

          $this->db->select('c.hexadecimal_color, c.color, u.medida,p.nombre');
          
          $this->db->select("( CASE WHEN m.id_medida = 1 THEN m.cantidad_um ELSE 0 END ) AS metros", FALSE);
          $this->db->select("( CASE WHEN m.id_medida = 2 THEN m.cantidad_um ELSE 0 END ) AS kilogramos", FALSE);

         
          $this->db->from($this->registros_salidas.' as m');
          $this->db->join($this->colores.' As c' , 'c.id = m.id_color','LEFT');
          $this->db->join($this->unidades_medidas.' As u' , 'u.id = m.id_medida','LEFT');
          $this->db->join($this->proveedores.' As p' , 'p.id = m.id_empresa','LEFT');
         
          //filtro de busqueda

          $where = '(
                      (
                        ( m.id_usuario = '.$id_session.' ) AND ( m.id_operacion = "2" ) AND ( m.estatus_salida = "0" )
                      ) 
            )';   

          $where_total = $where;
          $this->db->where($where);
    
          //ordenacion

          $this->db->order_by('m.id_lote', 'asc'); 
          $this->db->order_by('m.codigo', 'asc'); 
          $this->db->order_by('m.consecutivo', 'asc'); 

          //paginacion
         // $this->db->limit($largo,$inicio); 


          $result = $this->db->get();

              if ( $result->num_rows() > 0 ) {

                    $cantidad_consulta = $this->db->query("SELECT FOUND_ROWS() as cantidad");
                    $found_rows = $cantidad_consulta->row(); 
                    $registros_filtrados =  ( (int) $found_rows->cantidad);

                   $retorno= " ";   
                  foreach ($result->result() as $row) {
                            $dato[]= array(
                                      0=>$row->codigo,
                                      1=>$row->id_descripcion,
                                      2=>$row->color.
                                        '<div style="background-color:#'.$row->hexadecimal_color.';display:block;width:15px;height:15px;margin:0 auto;"></div>',
                                      3=>$row->cantidad_um.' '.$row->medida,
                                      4=>$row->ancho.' cm',
                                      5=>
                                           '<a style="  padding: 1px 0px 1px 0px;" href="'.base_url().'procesar_entradas/'.base64_encode($row->movimiento).'/'.base64_encode($row->devolucion).'/'.base64_encode($retorno).'/'.base64_encode($row->id_fac_orig).'/'.base64_encode($row->id_estatus).'"
                                               type="button" class="btn btn-success btn-block">'.$row->movimiento.'</a>', 
                                      6=>$row->nombre,
                                      7=>$row->id_lote.'-'.$row->consecutivo,
                                      8=>$row->id,
                                      9=>$row->id_apartado,
                                      10=>$row->num_partida,
                                      11=>$row->metros,
                                      12=>$row->kilogramos,
                                      13=>$row->peso_real,
                                      
                                    );
                      }




                      return json_encode ( array(
                        "draw"            => intval( $data['draw'] ),
                        "recordsTotal"    => intval( self::total_registros_salida() ),  //$recordsTotal
                        "recordsFiltered" => $registros_filtrados, //intval( $result->num_rows() ),   //$recordsFiltered
                        "data"            =>  $dato, //self::data_output( $columns, $data )
                        "totales"            =>  array("pieza"=>intval( self::totales_campos_salida($where_total)->pieza ), "metro"=>floatval( self::totales_campos_salida($where_total)->metros ), "kilogramo"=>floatval( self::totales_campos_salida($where_total)->kilogramos )),  
                      ));
                    
              }   
              else {
                  //cuando este vacio la tabla que envie este
                //http://www.datatables.net/forums/discussion/21311/empty-ajax-response-wont-render-in-datatables-1-10
                  $output = array(
                  "draw" =>  intval( $data['draw'] ),
                  "recordsTotal" => 0,
                  "recordsFiltered" =>0,
                  "aaData" => array(),
                  "totales"            =>  array("pieza"=>intval( self::totales_campos_salida($where_total)->pieza ), "metro"=>floatval( self::totales_campos_salida($where_total)->metros ), "kilogramo"=>floatval( self::totales_campos_salida($where_total)->kilogramos )),  
                  

                  );
                  $array[]="";
                  return json_encode($output);
                  

              }

              $result->free_result();           

      }  


 public function totales_campos_salida($where){

           $this->db->select("SUM((id_medida =1) * cantidad_um) as metros", FALSE);
              $this->db->select("SUM((id_medida =2) * cantidad_um) as kilogramos", FALSE);
              $this->db->select("COUNT(m.id_medida) as 'pieza'");
              
             
              $this->db->from($this->registros_salidas.' as m');
              $this->db->where($where);

             $result = $this->db->get();
          
              if ( $result->num_rows() > 0 )
                 return $result->row();
              else
                 return False;
              $result->free_result();              

       }  
       
     public function total_registros_salida(){

              $id_session = $this->session->userdata('id');
              $this->db->from($this->registros_salidas.' as m');
              $this->db->where('m.id_usuario',$id_session);
              $this->db->where('m.id_operacion',2);
              $this->db->where('m.estatus_salida',0);


              $cant = $this->db->count_all_results();          
     
              if ( $cant > 0 )
                 return $cant;
              else
                 return 0;         

       }

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


    public function checar_prod_salida($data){
            
            $this->db->select("id", FALSE);         
            $this->db->from($this->registros_salidas);
            $this->db->where('id_entrada',$data['id']);
            $login = $this->db->get();
            if ($login->num_rows() > 0) {
                return true;
            }    
            else
                return false;
            $login->free_result();
    } 


       public function enviar_prod_salida( $data ){

            $id_session = $this->session->userdata('id');
            $fecha_hoy = date('Y-m-d H:i:s');  //date_format($fecha_hoy , 'Y-m-d H:i:s');
        
             $this->db->select('"2" AS id_operacion',false);
             $this->db->select('"'.$id_session.'" AS id_usuario',false); 

             $this->db->select('"'.addslashes($data['id_almacen']).'" AS id_almacen',false); 

             /*
             if  (isset($data['factura'])) {
              $this->db->select('"'.addslashes($data['factura']).'" AS factura',false); 
             } */

             $this->db->select('factura');

             $this->db->select('"'.htmlspecialchars($data['id_cliente']).'" AS id_cliente',false);
             $this->db->select('"'.htmlspecialchars($data['id_cargador']).'" AS id_cargador',false);
             $this->db->select('"'.$fecha_hoy.'" AS fecha_salida',false);

             $this->db->select('"'.$data['id_movimiento'].'" AS mov_salida',false); 
             

             $this->db->select('id id_entrada, movimiento, id_empresa, id_descripcion, id_color, devolucion, num_partida');
             $this->db->select('id_composicion, id_calidad, referencia, id_medida, cantidad_um, cantidad_royo, ancho');
             $this->db->select('codigo, comentario, id_estatus, id_lote, consecutivo');
             $this->db->select('fecha_entrada,estatus_salida,consecutivo_venta');

             $this->db->select('id_apartado, id_usuario_apartado, id_cliente_apartado, fecha_apartado');

             //$this->db->select('"'.$data['id_destino'].'" AS id_destino',false); 

             
             $this->db->select('"'.$data['id_tipo_factura'].'" AS id_tipo_factura',false); 
             $this->db->select('"'.$data['id_tipo_pedido'].'" AS id_tipo_pedido',false); 

             //id_tipo_pedido,id_tipo_factura, 
             $this->db->select('precio, iva, id_pedido, id_factura,id_fac_orig, id_factura_original,incluir');

             
            


            $this->db->from($this->registros);
            $this->db->where('id',$data['id']);
            $result = $this->db->get();
            $objeto = $result->result();

            //copiar a tabla "registros"
            foreach ($objeto as $key => $value) {
              //return $value;
              $this->db->insert($this->registros_salidas, $value); 
            }
           return TRUE;
            
            
       }

      // agregar producto a la salida 

        public function quitar_prod_entrada( $data ){
           
            $id_session = $this->db->escape($this->session->userdata('id'));

            $this->db->set( 'id_usuario_salida', $id_session, FALSE  );
            $this->db->set( 'estatus_salida', '1', FALSE  );
            $this->db->where('id',$data['id']);
            $this->db->update($this->registros);
     
            if ( $this->db->affected_rows() > 0 ) return TRUE;
            else return FALSE;
            
        }

              
       public function enviar_prod_entrada( $data ){
              //id,
            $this->db->select('id_entrada');
            $this->db->from($this->registros_salidas);
            $this->db->where('id',$data['id']);
            $result = $this->db->get();
            $objeto = $result->row();

            //$this->db->set( 'precio_anterior', 'precio', FALSE  );
            //$this->db->set( 'precio', 'precio_cambio', FALSE  );

            $this->db->set('id_usuario_salida', '""', FALSE  );
            $this->db->set('estatus_salida', '0', FALSE  );
            $this->db->where('id',$objeto->id_entrada);
            $this->db->update($this->registros);

           return TRUE;
            
       }

          // Quitar producto de la salida 
                        
        public function quitar_prod_salidas( $data ){
            $this->db->delete( $this->registros_salidas, array( 'id' => $data['id'] ) );
            if ( $this->db->affected_rows() > 0 ) return TRUE;
            else return FALSE;
        }

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

 /*        
  `precio_anterior` float NOT NULL,
  `precio_cambio` float NOT NULL,
  `id_prorroga` bigint(1) NOT NULL DEFAULT '0',
  `fecha_vencimiento` datetime NOT NULL,
  `consecutivo_cambio` int(11) NOT NULL DEFAULT '0',
  precio_anterior, precio_cambio, id_prorroga, fecha_vencimiento, consecutivo_cambio

  */

  

       public function consecutivo_operacion( $id,$id_tipo_pedido,$id_tipo_factura ){
              $this->db->select("o.consecutivo,o.conse_factura,o.conse_remision,o.conse_surtido,o.conse_bodega");         
              $this->db->from($this->operaciones.' As o');
              $this->db->where('o.id',$id);
              $result = $this->db->get( );
                  if ($result->num_rows() > 0) {
                  $consecutivo_actual = (( ($id_tipo_pedido == 1) && ($id_tipo_factura == 1) ) ? $result->row()->conse_factura : $result->row()->conse_remision );
                  $consecutivo_actual = ( ($id_tipo_pedido==2) ? $result->row()->conse_surtido : $consecutivo_actual);
                  $consecutivo_actual = ( ($id_tipo_pedido==3) ? $result->row()->conse_bodega : $consecutivo_actual);
                       
                        return $consecutivo_actual+1;
                  }                    
                  else 
                      return FALSE;
                  $result->free_result();
       }  


        //procesando operaciones de salidas (afecta entradas y salidas)
        public function procesando_operacion_salida( $data ){

          $id_session = $this->session->userdata('id');

          $consecutivo = self::consecutivo_operacion(2,$data['id_tipo_pedido'],$data['id_tipo_factura']); 
           
          //registros de entradas
          $this->db->select('id id_entrada, fecha_entrada,  fecha_salida, movimiento, id_empresa, factura, id_descripcion, id_color, id_composicion, id_calidad,devolucion, num_partida');
          $this->db->select('referencia, id_medida, cantidad_um, cantidad_royo, ancho,  codigo, comentario, id_estatus, id_lote, consecutivo');
          $this->db->select('id_cargador, id_usuario, id_usuario_salida, fecha_mac, id_operacion, estatus_salida');

          $this->db->select('id_apartado,id_usuario_apartado, id_cliente_apartado,fecha_apartado');
          $this->db->select('precio_anterior, precio_cambio, id_prorroga, fecha_vencimiento, consecutivo_cambio');

          $this->db->select('id_almacen');
          $this->db->select('precio, iva, id_pedido, id_factura,id_fac_orig id_tipo_pedido,id_tipo_factura, id_factura_original,incluir');

          $this->db->select('id_tienda_origen');
         

          $this->db->from($this->registros);

          $this->db->where('id_usuario_salida',$id_session);
          $this->db->where('estatus_salida','1');
          $this->db->where('id_tipo_pedido',$data["id_tipo_pedido"]);
          $this->db->where('id_tipo_factura',$data["id_tipo_factura"]);

          $result = $this->db->get();

          $objeto = $result->result();
          
          //eliminar los registros en "registros_entradas"
          $this->db->delete($this->registros, array('id_usuario'=>$id_session,'estatus_salida'=>'1', 'id_tipo_pedido'=>$data["id_tipo_pedido"], 'id_tipo_factura'=>$data["id_tipo_factura"])); 

          //actualizar a registros_salidas el "mov_salida" al consecutivo q le toque
          $this->db->set('mov_salida', $consecutivo, FALSE  );
          $this->db->where('id_usuario',$id_session);
          $this->db->where('id_operacion',$data['id_operacion']); //2
          $this->db->where('id_tipo_pedido',$data["id_tipo_pedido"]);
          $this->db->where('id_tipo_factura',$data["id_tipo_factura"]);
          $this->db->update($this->registros_salidas);



          //registros de salidas    
          $this->db->select('m.id id_salida, m.id_entrada, m.mov_salida, m.fecha_entrada, m.fecha_salida, m.movimiento, m.id_empresa, m.id_cliente, m.factura, m.factura_salida,m.devolucion, m.num_partida');
          $this->db->select('m.id_descripcion, m.id_color, m.id_composicion, m.id_calidad, m.referencia, m.id_medida, m.cantidad_um, m.cantidad_royo, m.ancho,  m.codigo');
          $this->db->select('m.comentario, m.id_estatus, m.id_lote, m.consecutivo, m.id_cargador, m.id_usuario, m.id_usuario_salida, m.fecha_mac, m.id_operacion, m.estatus_salida');
          
          $this->db->select('ca.nombre cargador');
          $this->db->select('CONCAT(u.nombre,"  ",u.apellidos) as cliente', FALSE); //, p.nombre cliente

          $this->db->select('m.id_apartado, m.id_usuario_apartado, m.id_cliente_apartado,m.fecha_apartado');

          $this->db->select('m.precio_anterior, m.precio_cambio, m.id_prorroga, m.fecha_vencimiento, m.consecutivo_cambio');
          
          //$this->db->select($data['valor'].' as tipo_salida', FALSE);                 
          //$this->db->select('"'.htmlspecialchars($data['id_cargador']).'" AS id_cargador',false);
          $this->db->select('"'.$data['valor'].'" AS tipo_salida',FALSE);

          $this->db->select('m.peso_real');
          //$this->db->select('m.id_destino,de.nombre destino');
          $this->db->select('m.id_almacen');
          $this->db->select('m.consecutivo_venta');
        
          $this->db->select('m.precio, m.iva, m.id_pedido, m.id_factura,m.id_fac_orig, m.id_tipo_pedido,m.id_tipo_factura, m.id_factura_original,m.incluir');


          //$this->db->select('"'.$this->session->userdata('config_tienda_activo').'" AS id_tienda_origen',FALSE);

          $this->db->from($this->registros_salidas.' As m');
          $this->db->join($this->usuarios.' As u' , 'u.id = m.id_cliente','LEFT');
          //$this->db->join($this->proveedores.' As p' , 'p.id = m.id_cliente','LEFT');
          $this->db->join($this->cargadores.' As ca' , 'ca.id = m.id_cargador','LEFT');

/*
sasad

          $this->db->select('m.mov_salida,ca.nombre cargador');
          $this->db->select('CONCAT(u.nombre,"  ",u.apellidos) as cliente', FALSE); //, p.nombre cliente
          $this->db->from($this->historico_registros_salidas.' As m');
          $this->db->join($this->usuarios.' As u' , 'u.id = m.id_usuario_salida','LEFT');
          $this->db->join($this->cargadores.' As ca' , 'ca.id = m.id_cargador','LEFT');

*/


          //$this->db->join($this->catalogo_destinos.' As de' , 'de.id = m.id_destino','LEFT'); 


          $this->db->where('m.id_usuario',$id_session);
          $this->db->where('m.id_operacion',$data['id_operacion']); //2
          $this->db->where('m.id_tipo_pedido',$data["id_tipo_pedido"]);
          $this->db->where('m.id_tipo_factura',$data["id_tipo_factura"]);


          $result = $this->db->get();


          $objeto = $result->result();
          //copiar a tabla "historico_registros_salidas"
          $dato = array();
          $consecutivo_traspaso = self::consecutivo_operacion(26,$data['id_tipo_pedido'],$data['id_tipo_factura']); 
          $consecutivo_traspaso_unico = self::consecutivo_operacion_unico(26); 
          $traspaso=0;
          foreach ($objeto as $key => $value) {
            $this->db->insert($this->historico_registros_salidas, $value); 

            if ($value->incluir==1) {
              $traspaso=1;
              $value_traspaso = $value;
              $value_traspaso->consecutivo_traspaso = $consecutivo_traspaso;
              $value_traspaso->consecutivo_traspaso_unico = $consecutivo_traspaso_unico;
              $this->db->insert($this->historico_registros_traspasos, $value_traspaso); 
            }

            //$this->historico_registros_traspasos        = $this->db->dbprefix('historico_registros_traspasos');
            $dato['num_movimiento'] = $value->mov_salida;
            $dato['cargador'] = $value->cargador;
            $dato['cliente'] = $value->cliente;

          }
          
          if ($traspaso==1) {
              //actualizar (consecutivo de traspaso)
              //$this->db->set( 'consecutivo', 'consecutivo+1', FALSE  );

              if ($data['id_tipo_pedido']==2) {
                   $this->db->set( 'conse_surtido', 'conse_surtido+1', FALSE  );  
              }  else if ($data['id_tipo_factura']==1) {
                  $this->db->set( 'conse_factura', 'conse_factura+1', FALSE  );  
              } else {
                  $this->db->set( 'conse_remision', 'conse_remision+1', FALSE  );  
              }

              $this->db->set( 'id_usuario', $id_session );
              $this->db->where('id',26);
              $this->db->update($this->operaciones);          
          }
              
          //actualizar (consecutivo) en tabla "operacion"   == "salida"
          //$this->db->set( 'consecutivo', 'consecutivo+1', FALSE  );
          if ($data['id_tipo_pedido']==2) {
               $this->db->set( 'conse_surtido', 'conse_surtido+1', FALSE  );  
          }  else if ($data['id_tipo_factura']==1) {
              $this->db->set( 'conse_factura', 'conse_factura+1', FALSE  );  
          } else {
              $this->db->set( 'conse_remision', 'conse_remision+1', FALSE  );  
          }

          $this->db->set( 'id_usuario', $id_session );
          $this->db->where('id',2);
          $this->db->update($this->operaciones);

          //eliminar los registros en "registros_salidas"
          $this->db->delete($this->registros_salidas, array('id_usuario'=>$id_session,'id_operacion'=>$data['id_operacion'], 'id_tipo_pedido'=>$data["id_tipo_pedido"], 'id_tipo_factura'=>$data["id_tipo_factura"])); 

          return $dato;

          $result->free_result();          

        }



        //cambiar estatus de pedidos
        public function cancelar_pedido_salida( $data ){
              
                $id_session = $this->session->userdata('id');
                $fecha_hoy = date('Y-m-d H:i:s');  

                $this->db->set( 'fecha_apartado', '' );  
                $this->db->set( 'id_cliente_apartado', 0 );
                $this->db->set( 'id_apartado', 0);
                $this->db->set( 'id_usuario_apartado', '');

                $where = '(
                          (
                            (( id_apartado = 3 ) or ( id_apartado = 6 ) ) AND ( id_cliente_apartado = "'.$data['num_mov'].'" )
                          ) 

                      )';   

                $this->db->where($where);                

                $this->db->update($this->registros );

                if ($this->db->affected_rows() > 0) {
                  return TRUE;
                }  else
                   return FALSE;
       
        }   



        
  



        public function existencia_temporales(){

              $id_session = $this->session->userdata('id');
              $cant=0;

              $this->db->where('id_usuario',$id_session);
              $this->db->where('id_operacion',2);
              $this->db->from($this->registros_salidas);
              $cant = $this->db->count_all_results();          

              if ( $cant > 0 )
                 return true;
              else
                 return false;              

        }            


        public function actualizar_peso_real( $data ){
           
            $id_session = ($this->session->userdata('id'));


            foreach ($data['pesos'] as $key => $value) {

                if(!is_numeric($value['peso_real'])) {  //caso cuando el peso viene vacio
                  $value['peso_real'] = 0;
                  
                } 
                $this->db->set( 'peso_real', $value['peso_real'], FALSE  );
                
                $this->db->where('id_usuario',$id_session);
                $this->db->where('id_operacion',2);
                $this->db->where('id',$value['id']);                

                $this->db->update($this->registros_salidas);
              }

            

            
            return TRUE;       
            /*
            if ( $this->db->affected_rows() > 0 ) return TRUE;
            else return FALSE;
            */
            
        }
  


      public function existencia_temporales_peso_real(){

              $id_session = $this->session->userdata('id');
              $cant=0;

              $this->db->where('id_usuario',$id_session);
              $this->db->where('id_operacion',2);
              $this->db->where('peso_real',0);  //no tiene peso real
              $this->db->from($this->registros_salidas);
              $cant = $this->db->count_all_results();          

              if ( $cant > 0 )
                 return false;
              else
                 return true;              

        }            


    



//////////////////////////////Procesando apartados pendientes

public function valores_movimientos_temporal(){

          $id_session = $this->session->userdata('id');
          
          $this->db->distinct();          
          $this->db->select('m.id, m.id_cliente, m.id_cargador, m.factura, m.id_almacen'); //m.id_destino,
          $this->db->select('p.nombre, ca.nombre cargador');
           $this->db->select('m.id_tipo_pedido,m.id_tipo_factura, m.id_tipo_factura');
          
          $this->db->from($this->registros_salidas.' as m');
          $this->db->join($this->proveedores.' As p' , 'p.id = m.id_cliente','LEFT');
          $this->db->join($this->cargadores.' As ca' , 'ca.id = m.id_cargador','LEFT');

          $this->db->where('m.id_usuario',$id_session);
          $this->db->where('id_operacion',2);
           $result = $this->db->get();
        
            if ( $result->num_rows() > 0 )
               return $result->row();
            else
               return False;
            $result->free_result();
        }   

 /////////////////////////////////////////////////////////////nuevo/////////////////


        
   
          
    public function consecutivo_operacion_unico($id ){
            $this->db->select("o.consecutivo");         
            $this->db->from($this->operaciones.' As o');
            $this->db->where('o.id',$id);
            $result = $this->db->get( );
                if ($result->num_rows() > 0) {
                      return $result->row()->consecutivo+1;
                }                    
                else 
                    return FALSE;
                $result->free_result();
    }  

  


        /////////////////////////////////////////////////////////////fin de nuevo/////////////////




         


   





  } 






?>

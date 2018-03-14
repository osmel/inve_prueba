<?php if(! defined('BASEPATH')) exit('No tienes permiso para acceder a este archivo');

  class model_transferencia extends CI_Model {
    
    private $key_hash;
    private $timezone;

    function __construct(){

      parent::__construct();
      $this->load->database("default");
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
      $this->unidades_medidas        = $this->db->dbprefix('catalogo_unidades_medidas');

      $this->operaciones             = $this->db->dbprefix('catalogo_operaciones');
      $this->movimientos               = $this->db->dbprefix('movimientos');
      $this->registros_temporales               = $this->db->dbprefix('temporal_registros');
      $this->registros               = $this->db->dbprefix('registros_entradas');
      $this->registros_cambios               = $this->db->dbprefix('registros_cambios');

      $this->colores                 = $this->db->dbprefix('catalogo_colores');
      $this->unidades_medidas        = $this->db->dbprefix('catalogo_unidades_medidas');
      $this->historico_registros_entradas        = $this->db->dbprefix('historico_registros_entradas');

      $this->almacenes                 = $this->db->dbprefix('catalogo_almacenes');
      $this->catalogo_configuraciones  = $this->db->dbprefix('catalogo_configuraciones');

      $this->catalogo_tipos_pagos  = $this->db->dbprefix('catalogo_tipos_pagos');

      $this->historico_pagos_realizados        = $this->db->dbprefix('historico_pagos_realizados');
      $this->historico_ctasxpagar        = $this->db->dbprefix('historico_ctasxpagar');


      $this->remoto_registros_transferencia                   = $this->db->dbprefix('remoto_registros_transferencia');

      $this->catalogo_tiendas  = $this->db->dbprefix('catalogo_tiendas');
      $this->catalogo_estatus                         = $this->db->dbprefix('catalogo_estatus');

      $this->catalogo_tiendas                         = $this->db->dbprefix('catalogo_tiendas');
      
      /*
      $this->remoto_calidad  = $this->db->dbprefix('remoto_calidad');
      $this->remoto_colores  = $this->db->dbprefix('remoto_colores');
      $this->remoto_composicion  = $this->db->dbprefix('remoto_composicion');
      $this->remoto_productos  = $this->db->dbprefix('remoto_productos');
      */

    }





    //Para el selector de transferencia
    public function listado_transferencias() {

         $this->db->select("m.id_tienda_origen,t.nombre, m.mov_salida, mov_salida_unico, m.nombre_usuario");
  
         $this->db->from($this->remoto_registros_transferencia.' as m');
         $this->db->join($this->catalogo_tiendas.' As t' , 't.id = m.id_tienda_origen');


        $this->db->group_by('m.id_tienda_origen, m.mov_salida_unico'); 
        //,m.id_tipo_pedido,m.id_tipo_factura,m.id_almacen,m.id_cliente,m.id_estatus

        $result = $this->db->get();

            if ( $result->num_rows() > 0 )
               return $result->result();
            else
               return False;
            $result->free_result();
  }    


 //regilla de transferencia recibida
  public function buscando_transferencia_recibida($data){

          $cadena = addslashes($data['search']['value']);
          $inicio = $data['start'];
           $largo = $data['length'];

          $columa_order = $data['order'][0]['column'];
                 $order = $data['order'][0]['dir'];

      
          switch ($columa_order) {
                 case '1':
                        $columna = 'm.codigo';
                     break;
                   case '2':
                        $columna = 'm.id_descripcion';
                     break;
                   case '3':
                        $columna = 'c.hexadecimal_color';
                     break;
                   case '4':
                        $columna = 'm.cantidad_um, u.medida';
                     break;
                   case '5':
                        $columna = 'm.ancho';
                     break;

                   /*case '6':
                        $columna = 'm.id_lote, m.consecutivo';
                     break;                     
                     */
                   case '7':
                        $columna = 'm.precio';
                     break;      
                   case '8':
                   case '9':
                   case '10':
                        $columna = 'm.precio, m.iva';
                     break;  

                   default:
                         $columna = 'm.id_descripcion';
                     break;
                 }                 


                      
          
          $fecha_hoy =  date("Y-m-d h:ia"); 
          $hoy = new DateTime($fecha_hoy);

          $id_session = $this->db->escape($this->session->userdata('id'));

          $this->db->select("SQL_CALC_FOUND_ROWS(m.id)"); //
          //,  m.num_partida,  p.nombre, 

          $this->db->select('m.id,m.codigo, m.id_descripcion'); 
          $this->db->select('c.hexadecimal_color,u.medida,m.cantidad_um,m.ancho, m.peso_real, m.id_lote, m.consecutivo,s.estatus'); 
          $this->db->select('m.precio');
          $this->db->select('m.iva');
          $this->db->select("((m.precio*m.cantidad_um*m.iva))/100 as sum_iva");
          $this->db->select("(m.precio*m.cantidad_um)+((m.precio*m.cantidad_um*m.iva))/100 as sum_total");
          $this->db->select("( CASE WHEN m.id_medida = 1 THEN m.cantidad_um ELSE 0 END ) AS metros");
          $this->db->select("( CASE WHEN m.id_medida = 2 THEN m.cantidad_um ELSE 0 END ) AS kilogramos");

          $this->db->from($this->remoto_registros_transferencia.' as m');
          $this->db->join($this->productos.' As prod' , 'prod.referencia = m.new_referencia'); //,'LEFT'
          $this->db->join($this->colores.' As c' , 'c.id = m.id_new_color','LEFT'); //,'LEFT'
          $this->db->join($this->unidades_medidas.' As u' , 'u.id = m.id_medida','LEFT'); //,'LEFT'

          $this->db->join($this->catalogo_estatus.' As s' , 's.id = m.id_estatus','LEFT'); //,'LEFT'

          
          
          $where = '(
                      (
                        ( m.id_usuario_salida = '.$id_session.' ) AND 
                        ( m.mov_salida_unico = '.$data['mov_salida_unico'].' )
                      ) 
                       AND
                      (    
                          (m.codigo LIKE  "%'.$cadena.'%") OR ( m.id_descripcion LIKE  "%'.$cadena.'%" ) OR 
                          (m.ancho LIKE  "%'.$cadena.'%") 
                       )   

            )';   

          $where_total = '( m.id_usuario_salida = '.$id_session.' ) AND 
                        ( m.mov_salida_unico = '.$data['mov_salida_unico'].' ) '; 

          $this->db->where($where);

          //ordenacion
          $this->db->order_by($columna, $order); 

          //paginacion
          $this->db->limit($largo,$inicio); 


          $result = $this->db->get();

          //return json_encode($result->result());

              if ( $result->num_rows() > 0 ) {

                    $cantidad_consulta = $this->db->query("SELECT FOUND_ROWS() as cantidad");
                    $found_rows = $cantidad_consulta->row(); 
                    $registros_filtrados =  ( (int) $found_rows->cantidad);


                  foreach ($result->result() as $row) {
                            $dato[]= array(
                                      0=>$row->id,
                                      1=>$row->codigo,
                                      2=>$row->id_descripcion,
                                      3=>'<div style="background-color:#'.$row->hexadecimal_color.';display:block;width:15px;height:15px;margin:0 auto;"></div>',
                                      4=>$row->cantidad_um.' '.$row->medida,
                                      5=>$row->ancho.' cm', 
                                      6=>$row->id_lote.' - '.$row->consecutivo, 
                                      7=>$row->precio,  //18
                                      8=>$row->precio*$row->cantidad_um, //13
                                      9=>$row->iva,  //14
                                      10=>$row->metros,
                                      11=>$row->kilogramos,  
                                      12=>$row->sum_iva, //15
                                      13=>$row->sum_total, //16
                                      14=>$row->estatus, //16
                                      15=>$row->peso_real,
                                    );
                   }

                      

                      return json_encode ( array(
                        "draw"            => intval( $data['draw'] ),
                        "recordsTotal"    =>$registros_filtrados, 
                        "recordsFiltered" => $registros_filtrados, 
                        "data"            =>  $dato,
                        "totales"            =>  array("pieza"=>intval( self::totales_campos_salida($where_total)->pieza ), "metro"=>floatval( self::totales_campos_salida($where_total)->metros ), "kilogramo"=>floatval( self::totales_campos_salida($where_total)->kilogramos )),  
                          "totales_importe"            =>  array(
                                "subtotal"=>floatval( self::totales_importes($where_total)->subtotal ), 
                                "iva"=>floatval( self::totales_importes($where_total)->iva ), 
                                "total"=>floatval( self::totales_importes($where_total)->total ),
                                ),  

                      ));
                    
              }   
              else {
                  $output = array(
                  "draw" =>  intval( $data['draw'] ),
                  "recordsTotal" => 0, 
                  "recordsFiltered" =>0,
                  "aaData" => array(),
                   "totales"            =>  array("pieza"=>intval( self::totales_campos_salida($where_total)->pieza ), "metro"=>floatval( self::totales_campos_salida($where_total)->metros ), "kilogramo"=>floatval( self::totales_campos_salida($where_total)->kilogramos )),  

                          "totales_importe"            =>  array(
                                "subtotal"=>floatval( self::totales_importes($where_total)->subtotal ), 
                                "iva"=>floatval( self::totales_importes($where_total)->iva ), 
                                "total"=>floatval( self::totales_importes($where_total)->total ),
                                ),  


                  );
                  $array[]="";
                  return json_encode($output);
                  

              }

              $result->free_result();           
      } 


public function totales_importes($where){

           $this->db->select("SUM(precio*cantidad_um) as subtotal");
           $this->db->select("(SUM(precio*cantidad_um*iva))/100 as iva");
           $this->db->select("SUM(precio*cantidad_um)+(SUM(precio*cantidad_um*iva))/100 as total");
   
          $this->db->from($this->remoto_registros_transferencia.' as m');
          $this->db->where($where);

          $result = $this->db->get();
      
          if ( $result->num_rows() > 0 )
             return $result->row();
          else
             return False;
          $result->free_result();              

    }  



 public function totales_campos_salida($where){

           $this->db->select("SUM((id_medida =1) * cantidad_um) as metros", FALSE);
              $this->db->select("SUM((id_medida =2) * cantidad_um) as kilogramos", FALSE);
              $this->db->select("COUNT(m.id_medida) as 'pieza'");
             
              $this->db->from($this->remoto_registros_transferencia.' as m');
              $this->db->where($where);

             $result = $this->db->get();
          
              if ( $result->num_rows() > 0 )
                 return $result->row();
              else
                 return False;
              $result->free_result();              

       }  


       //para evitar duplicidad de factura  
    public function existencia_factura($data){
              $this->db->where('factura',$data['factura']);
              $this->db->from($this->historico_registros_entradas);
              $cant = $this->db->count_all_results();          

              if ( $cant > 0 )
                 return false;
              else
                 return true;              

        }   



      //para el caso de la validación checar que todavía exista si existe elemento en la tabla temporal
      public function existencia_transferencia($data){
              $cant=0;
              $this->db->where('mov_salida_unico',$data['mov_salida_unico']);
              $this->db->where('id_tienda_origen',$data['id_tienda_origen']);
              $this->db->from($this->remoto_registros_transferencia);
              $cant = $this->db->count_all_results();          

              if ( $cant > 0 )
                 return true;
              else
                 return false;              
        }   


        //actualizando precio de la transferencia
       public function actualizar_precio_transferencia( $data ){
           
            $id_session = ($this->session->userdata('id'));


            foreach ($data['precios'] as $key => $value) {

                if(!is_numeric($value['precio_nuevo'])) {  //caso cuando el peso viene vacio
                  $value['precio_nuevo'] = 0;
                  
                } 
                $this->db->set( 'precio_nuevo', $value['precio_nuevo'], FALSE  );
                $this->db->where('codigo',$value['codigo']);                
                $this->db->update($this->remoto_registros_transferencia);
              }
            
            return TRUE;       
        }              

        //actualizando peso de la transferencia
       public function actualizar_peso_transferencia( $data ){
           
            $id_session = ($this->session->userdata('id'));


            foreach ($data['pesos'] as $key => $value) {

                if(!is_numeric($value['peso_nuevo'])) {  //caso cuando el peso viene vacio
                  $value['peso_nuevo'] = 0;
                  
                } 
                $this->db->set( 'peso_real', $value['peso_nuevo'], FALSE  );
                $this->db->where('codigo',$value['codigo']);                
                $this->db->update($this->remoto_registros_transferencia);
              }
            
            return TRUE;       
        }              



           //procesando operaciones
        public function procesando_operacion_transferencia( $data ){

          $id_session = $this->session->userdata('id');
          $fecha_hoy = date('Y-m-d H:i:s');  

          $consecutivo = self::consecutivo_operacion(70,$data['id_factura']); //cambio
          $consecutivo_unico = self::consecutivo_operacion_unico(70); 

          

          //actualizar (consecutivo) en tabla "operacion" 
  
          if ($data['id_factura']==1) {
              $this->db->set( 'conse_factura', 'conse_factura+1', FALSE  );  
          } else {
              $this->db->set( 'conse_remision', 'conse_remision+1', FALSE  );  
          }
            $this->db->set( 'consecutivo', 'consecutivo+1', FALSE  );  
          $this->db->set( 'id_usuario', $id_session );
          $this->db->where('id',70);
          $this->db->update($this->operaciones);


          //actualizando nuevos consecutivos
           $this->catalogo->actualizando_nuevos_consecutivos($data);
           
           //Obtener nuevos consecutivos
           $new_consecutivo   = $this->catalogo->consecutivo_general($data);


          

            
            $this->db->select('new_referencia AS referencia',false);     
            $this->db->select('id_new_calidad AS id_calidad',false);     
            $this->db->select('id_new_composicion AS id_composicion',false);     
            $this->db->select('id_new_color AS id_color',false);     
            
            
           $this->db->select('precio_nuevo AS precio',false);     //
           // $this->db->select('precio AS precio',false);      //
           $this->db->select('precio AS precio_viejo',false);      //

           $this->db->select('1 AS cantidad_royo',false);    
           //$this->db->select('0 AS peso_real',false);   
           $this->db->select('"p-trans" AS num_partida',false);                      
           $this->db->select('"p-trans" AS comentario',false);   
           
           $this->db->select('"'.addslashes($data['factura']).'" AS factura',false); 

           $this->db->select($data['id_almacen'].' AS id_almacen',false);  
           $this->db->select($data['id_factura'].' AS id_factura',false);  
           $this->db->select($data['id_factura'].' AS id_fac_orig',false);  
           $this->db->select($data['id_tipo_pago'].' AS id_tipo_pago',false);

          $this->db->select('id_descripcion, id_lote, consecutivo, id_estatus, codigo, ancho, peso_real, cantidad_um, id_medida, iva, nombre_usuario');
          $this->db->select('"'.$id_session.'" AS id_usuario',false); 
          $this->db->select($data['id_operacion'].' AS id_operacion',false);  //creo 1(entrada)
          $this->db->select('"'.$fecha_hoy.'" AS fecha_entrada',false);
          $this->db->select($consecutivo.' AS movimiento',false); // 
          $this->db->select($consecutivo_unico.' AS movimiento_unico',false); //cambio


          $this->db->select($new_consecutivo->c1.' AS c1',false); 
          $this->db->select($new_consecutivo->c2.' AS c2',false); 
          $this->db->select($new_consecutivo->c1234.' AS c1234',false); 
          $this->db->select($new_consecutivo->c234.' AS c234',false); 
          $this->db->select($new_consecutivo->c34.' AS c34',false);           



          //id_empresa (poner el origin de tienda desde donde viene la transferencia)
            
          $this->db->select('id_tienda_origen AS id_empresa');  //                
          $this->db->select('id_tienda_origen');    
          $this->db->select('mov_salida_unico AS mov_transferencia_enviada');   //este es quien indica q hay una transferencia 

          $this->db->from($this->remoto_registros_transferencia);
          $this->db->where('mov_salida_unico',$data['mov_salida_unico']);
          $this->db->where('id_tienda_origen',$data['id_tienda_origen']);
          

          $result = $this->db->get();
          
          //return $result->result(); //cambio
          $objeto = $result->result();
          
          //copiar a tabla "registros" e "historico_registros_entradas"
          foreach ($objeto as $key => $value) {
            $this->db->insert($this->historico_registros_entradas, $value); 
            $value->peso_real = 0;
            $this->db->insert($this->registros, $value);
            $num_movimiento = $value->movimiento;
            $num_movimiento_unico = $value->movimiento_unico;

          }

          //aqui es donde voy a agregar "historico_ctasxpagar"

              $this->db->select('"'.addslashes($num_movimiento).'" AS movimiento',false); 
              $this->db->select('"'.addslashes($num_movimiento_unico).'" AS movimiento_unico',false); 


              $this->db->select($new_consecutivo->c1.' AS c1',false); 
              $this->db->select($new_consecutivo->c2.' AS c2',false); 
              $this->db->select($new_consecutivo->c1234.' AS c1234',false); 
              $this->db->select($new_consecutivo->c234.' AS c234',false); 
              $this->db->select($new_consecutivo->c34.' AS c34',false); 
              
              $this->db->select($data['id_almacen'].' AS id_almacen',false);  
              $this->db->select($data['id_factura'].' AS id_factura',false);  
              $this->db->select($data['id_factura'].' AS id_fac_orig',false);  
              $this->db->select($data['id_tipo_pago'].' AS id_tipo_pago',false);
              $this->db->select('"'.addslashes($data['factura']).'" AS factura',false); 
              $this->db->select('m.id_empresa'); // aqui es origen
              $this->db->select('m.id_tienda_origen'); 
              $this->db->select('"'.$fecha_hoy.'" AS fecha_entrada',false);
              $this->db->select($data['id_operacion'].' AS id_operacion',false);  //creo 1
              $this->db->select('"'.$id_session.'" AS id_usuario',false); 
              $this->db->select('"p-trans" AS comentario',false);   
              $this->db->select('sum(m.precio*m.cantidad_um) as subtotal');           
              $this->db->select("sum(m.precio*m.cantidad_um*m.iva)/100 as iva", FALSE);
              $this->db->select("sum(m.precio*m.cantidad_um)+((sum(m.precio*m.cantidad_um*m.iva))/100) as total", FALSE);
              $this->db->select('m.id_estatus');
              $this->db->select('1 as transferencia',false);

              //  id_tienda_origen

              $this->db->from($this->remoto_registros_transferencia.' as m');
              $this->db->where('mov_salida_unico',$data['mov_salida_unico']);
              $this->db->where('id_tienda_origen',$data['id_tienda_origen']);
              $this->db->group_by('m.mov_salida_unico,m.id_almacen,m.id_tienda_origen');

              $result_ctas_pagar = $this->db->get();
              $objeto_ctas_pagar = $result_ctas_pagar->result();
              foreach ($objeto_ctas_pagar as $key => $value) {
                    $this->db->insert($this->historico_ctasxpagar, $value); 
              }

          //fin de  agregar "historico_ctasxpagar"

          //eliminar los registros en "temporal_registros" del usuario 
          $this->db->delete($this->remoto_registros_transferencia, array('mov_salida_unico'=>$data['mov_salida_unico'],'id_tienda_origen'=>$data['id_tienda_origen'])); 

          return $num_movimiento_unico; //$num_movimiento;

          $result->free_result();          

        }         


           //listado de movimiento de una entrada, de un movimiento especifico
        public function listado_movimientos_transferencia($data){

          $id_session = $this->session->userdata('id');
                    
          $this->db->select('m.id, m.movimiento,m.id_empresa, m.factura, m.id_descripcion, m.id_operacion,m.devolucion, num_partida,id_almacen, a.almacen, id_factura,m.id_fac_orig,id_tipo_pago, iva');
          $this->db->select('m.id_color, m.id_composicion, m.id_calidad, m.referencia');
          $this->db->select('m.id_medida, m.cantidad_um,m.peso_real, m.cantidad_royo, m.ancho, m.peso_real,m.precio, m.codigo, m.comentario');
          $this->db->select('m.id_estatus, m.id_lote, m.consecutivo, m.id_cargador, m.id_usuario, m.fecha_mac fecha');
          $this->db->select('DATE_FORMAT((m.fecha_mac),"%d-%m-%Y %H:%i") as fecha2', false);

          $this->db->select("( CASE WHEN m.devolucion <> 0 THEN 'red' ELSE 'black' END ) AS color_devolucion", FALSE);
          

          $this->db->select('c.hexadecimal_color, u.medida,p.nombre');

          $this->db->select('(m.precio*m.cantidad_um) as sum_precio');           
          $this->db->select("(m.precio*m.cantidad_um*m.iva)/100 as sum_iva", FALSE);
          $this->db->select("(m.precio*m.cantidad_um)+(((m.precio*m.cantidad_um*m.iva))/100) as sum_total", FALSE);


          $this->db->select("prod.codigo_contable");            
          
          $this->db->from($this->historico_registros_entradas.' as m');
          $this->db->join($this->almacenes.' As a' , 'a.id = m.id_almacen'); //AND a.activo=1
          $this->db->join($this->productos.' As prod' , 'prod.referencia = m.referencia','LEFT');
          $this->db->join($this->colores.' As c' , 'c.id = m.id_color','LEFT');
          $this->db->join($this->unidades_medidas.' As u' , 'u.id = m.id_medida','LEFT');
          $this->db->join($this->catalogo_tiendas.' As p' , 'p.id = m.id_tienda_origen','LEFT');

          $where = '(
                        ( m.movimiento_unico = '.$data['num_mov'].' ) AND
                        ( m.id_tienda_origen = '.$data['id_tienda_origen'].' ) 
            )';   

          $this->db->where($where);



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


        /////////////////////Funciones auxiliares




       public function consecutivo_operacion( $id,$id_factura ){
              $this->db->select("o.consecutivo,o.conse_factura,o.conse_remision,o.conse_surtido");         
              $this->db->from($this->operaciones.' As o');
              $this->db->where('o.id',$id);
              $result = $this->db->get( );
                  if ($result->num_rows() > 0) {
                        $consecutivo_actual = ( ($id_factura==1) ? $result->row()->conse_factura : $result->row()->conse_remision );
                        return $consecutivo_actual+1;
                  }                    
                  else 
                      return FALSE;
                  $result->free_result();
       }  

       public function consecutivo_operacion_unico( $id ){
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


    




  } 






?>

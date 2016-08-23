<?php if(! defined('BASEPATH')) exit('No tienes permiso para acceder a este archivo');

  class model_traspaso extends CI_Model {
    
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
      $this->cargadores             = $this->db->dbprefix('catalogo_cargador');
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
      
      $this->composiciones     = $this->db->dbprefix('catalogo_composicion');
      $this->calidades                 = $this->db->dbprefix('catalogo_calidad');

      $this->registros_entradas               = $this->db->dbprefix('registros_entradas');
      $this->registros_cambios               = $this->db->dbprefix('registros_cambios');

      $this->almacenes             = $this->db->dbprefix('catalogo_almacenes');

      $this->tipos_facturas                         = $this->db->dbprefix('catalogo_tipos_facturas');
      $this->tipos_pedidos                         = $this->db->dbprefix('catalogo_tipos_pedidos');
      $this->tipos_ventas                         = $this->db->dbprefix('catalogo_tipos_ventas');

      $this->historico_registros_traspasos        = $this->db->dbprefix('historico_registros_traspasos');

    }








      public function buscador_traspaso_historico($data){

          $cadena = addslashes($data['search']['value']);
          $inicio = $data['start'];
          $largo = $data['length'];
          $id_almacen= $data['id_almacen'];

          $columa_order = $data['order'][0]['column'];
                 $order = $data['order'][0]['dir'];

          switch ($columa_order) {
                   
                   case '0':
                        $columna = 'm.id_tipo_factura';
                     break;

                   case '1':
                        //$columna = 'pr.nombre'; //automatico y manual
                     break;

                   case '2':
                        $columna = 'm.id_almacen';  //,m.id_cliente_apartado,
                     break;
                   case '3':
                        $columna = 'm.fecha_apartado';
                     break;                     
                   
                   /*

                   case '4':
                        $columna = 'm.tipo_salida,m.id_apartado';
                     break;
                   case '5':
                          $columna = 'm.mov_salida';
                     break;
                   */

                   default:
                       $columna = 'u.nombre, u.apellidos';
                     break;
                 }            
          

          $id_session = $this->db->escape($this->session->userdata('id'));

          $this->db->select("SQL_CALC_FOUND_ROWS *", FALSE); //

          $this->db->select('m.id_usuario_apartado, m.id_cliente_apartado,consecutivo_venta, m.fecha_apartado');  
          $this->db->select('p.nombre comprador, m.id_apartado apartado');   
          $this->db->select('CONCAT(u.nombre,"  ",u.apellidos) as vendedor', FALSE);
          $this->db->select('pr.nombre as dependencia', FALSE);

          $this->db->select('m.consecutivo_traspaso', FALSE);
          $this->db->select('m.mov_salida', FALSE);
          

          $this->db->select('
                        CASE m.id_apartado
                           WHEN "3" THEN "(Pedido de vendedor)"
                           WHEN "6" THEN "(Pedido de Tienda)"
                           ELSE "No Pedido"
                        END AS tipo_pedido
         ',False);  

          $this->db->select("a.almacen");
          $this->db->select("m.consecutivo_venta");
          $this->db->select("tp.tipo_pedido tip_pedido", false);          
          $this->db->select("tf.tipo_factura");          

          $this->db->from($this->historico_registros_traspasos.' as m');
          $this->db->join($this->usuarios.' As u' , 'u.id = m.id_usuario_apartado','LEFT');
          $this->db->join($this->proveedores.' As pr', 'u.id_cliente = pr.id','LEFT');
          $this->db->join($this->proveedores.' As p' , 'p.id = m.id_cliente_apartado','LEFT');
          $this->db->join($this->almacenes.' As a' , 'a.id = m.id_almacen','LEFT');
          $this->db->join($this->tipos_pedidos.' As tp' , 'tp.id = m.id_tipo_pedido','LEFT');
          $this->db->join($this->tipos_facturas.' As tf' , 'tf.id = m.id_tipo_factura','LEFT');


          if ($id_almacen!=0) {
              $id_almacenid = ' AND ( m.id_almacen =  '.$id_almacen.' ) ';  
          } else {
              $id_almacenid = '';
          }          

          //filtro de los pedidos que tienen traspasos
          $filtro = ' ( ( m.id_tipo_factura <> 0 ) AND ( m.incluir <> 0 ) ) ';  
//$id_almacenid = '';
//$filtro ='';
          $where = '(
                      ('.$filtro.$id_almacenid.') 
                       AND
                      (
                        ( CONCAT(u.nombre," ",u.apellidos) LIKE  "%'.$cadena.'%" ) OR
                        ( pr.nombre LIKE  "%'.$cadena.'%" ) OR (p.nombre LIKE  "%'.$cadena.'%") OR
                        (m.id_cliente_apartado LIKE  "%'.$cadena.'%") OR 
                        ((DATE_FORMAT((m.fecha_apartado),"%d-%m-%Y") ) LIKE  "%'.$cadena.'%") OR
                        
                        ( "Salida Parcial" LIKE  "%'.$cadena.'%" ) OR
                        ( "Salida Total" LIKE  "%'.$cadena.'%" ) OR
                        ( "(Vendedor)" LIKE  "%'.$cadena.'%" ) OR
                        ( "(Tienda)" LIKE  "%'.$cadena.'%" ) 
                       ) 

            )';            


          $this->db->where($where);
          //$where_total = '( m.id_apartado = 3 ) or ( m.id_apartado = 6 )'.$id_almacenid.$filtro; 
          $where_total = '('.$filtro.$id_almacenid.')'; 
          $this->db->order_by($columna, $order); 


          $this->db->group_by("m.consecutivo_traspaso,m.id_usuario_apartado, m.id_cliente_apartado,m.consecutivo_venta");
          //paginacion
          $this->db->limit($largo,$inicio); 


          $result = $this->db->get();

              if ( $result->num_rows() > 0 ) {

                    $cantidad_consulta = $this->db->query("SELECT FOUND_ROWS() as cantidad");
                    $found_rows = $cantidad_consulta->row(); 
                    $registros_filtrados =  ( (int) $found_rows->cantidad);


                  foreach ($result->result() as $row) {

                              if ($row->apartado==3) {
                                 $num=$row->consecutivo_venta;
                              } else  {
                                 $num= $row->id_cliente_apartado;
                              }   

                              if ($row->apartado!=0) {
                                  $proceso = "automatico";
                                  $motivo = $row->tipo_pedido.' <b>Nro.</b>'.$num.'<br/>Salida Nro.<b>'.$row->mov_salida.'</b>';
                              } else {
                                  $proceso = "manual";
                                  $motivo = "comentario";
                              }    
                              
                            $dato[]= array(
                                      0=>$row->tip_pedido, //venta o surtido
                                      1=>$row->tipo_factura,   //factura o remision
                                      2=>$proceso, //automatico o manual          
                                      3=>$row->almacen,  //$row->mov_salida,
                                      4=>date( 'd-m-Y', strtotime($row->fecha_apartado)), //fecha de lo apartado
                                      
                                      5=>$motivo, //"Apartado o pedido"
                                      6=>$num,  //consecutivo de apartado
                                      7=>$row->consecutivo_traspaso,  //consecutivo de apartado
                                      
                                      8=>$row->vendedor, //responsable
                                      9=>$row->dependencia,//dependencia a la cual pertenece responsable que aparto  
                                      
                                    );
                      }



                      return json_encode ( array(
                        "draw"            => intval( $data['draw'] ),
                        "recordsTotal"    => intval( self::total_traspaso_historico($where_total) ), 
                        "recordsFiltered" => $registros_filtrados, 
                        "data"            =>  $dato 
                      ));
                    
              }   
              else {
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

 
    //3ra regilla de "/pedidos"
      public function total_traspaso_historico($where){

              $this->db->from($this->historico_registros_traspasos.' as m');
              $this->db->where($where);
        
              $this->db->group_by("m.consecutivo_traspaso,m.id_usuario_apartado, m.id_cliente_apartado,m.consecutivo_venta");

              $result = $this->db->get();
              $cant = $result->num_rows();
     
              if ( $cant > 0 )
                 return $cant;
              else
                 return 0;         

       }     



/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////






      public function buscador_general_traspaso($data){

          $cadena = addslashes($data['search']['value']);
          $inicio = $data['start'];
          $largo = $data['length'];
          $id_almacen= $data['id_almacen'];

          $columa_order = $data['order'][0]['column'];
                 $order = $data['order'][0]['dir'];

          switch ($columa_order) {
                   
                   case '0':
                        $columna = 'm.id_tipo_factura';
                     break;

                   case '1':
                        //$columna = 'pr.nombre'; //automatico y manual
                     break;

                   case '2':
                        $columna = 'm.id_almacen';  //,m.id_cliente_apartado,
                     break;
                   case '3':
                        $columna = 'm.fecha_apartado';
                     break;                     
                   
                   /*

                   case '4':
                        $columna = 'm.tipo_salida,m.id_apartado';
                     break;
                   case '5':
                          $columna = 'm.mov_salida';
                     break;
                   */

                   default:
                       $columna = 'u.nombre, u.apellidos';
                     break;
                 }            
          

          $id_session = $this->db->escape($this->session->userdata('id'));

          $this->db->select("SQL_CALC_FOUND_ROWS *", FALSE); //

          $this->db->select('m.id_usuario_apartado, m.id_cliente_apartado,consecutivo_venta, m.fecha_apartado');  
          $this->db->select('p.nombre comprador, m.id_apartado apartado');   
          $this->db->select('CONCAT(u.nombre,"  ",u.apellidos) as vendedor', FALSE);
          $this->db->select('pr.nombre as dependencia', FALSE);

          $this->db->select('
                        CASE m.id_apartado
                           WHEN "3" THEN "(Pedido de vendedor)"
                           WHEN "6" THEN "(Pedido de Tienda)"
                           ELSE "No Pedido"
                        END AS tipo_pedido
         ',False);  

          $this->db->select("a.almacen");
          $this->db->select("m.consecutivo_venta");
          $this->db->select("tp.tipo_pedido tip_pedido", false);          
          $this->db->select("tf.tipo_factura");          

          $this->db->from($this->registros_entradas.' as m');
          $this->db->join($this->usuarios.' As u' , 'u.id = m.id_usuario_apartado','LEFT');
          $this->db->join($this->proveedores.' As pr', 'u.id_cliente = pr.id','LEFT');
          $this->db->join($this->proveedores.' As p' , 'p.id = m.id_cliente_apartado','LEFT');
          $this->db->join($this->almacenes.' As a' , 'a.id = m.id_almacen','LEFT');
          $this->db->join($this->tipos_pedidos.' As tp' , 'tp.id = m.id_tipo_pedido','LEFT');
          $this->db->join($this->tipos_facturas.' As tf' , 'tf.id = m.id_tipo_factura','LEFT');


          if ($id_almacen!=0) {
              $id_almacenid = ' AND ( m.id_almacen =  '.$id_almacen.' ) ';  
          } else {
              $id_almacenid = '';
          }          

          //filtro de los pedidos que tienen traspasos
          $filtro = ' AND ( ( m.id_tipo_factura <> 0 ) AND ( m.incluir <> 0 ) )';  

          $where = '(
                      (
                        ( m.id_apartado = 3 ) or ( m.id_apartado = 6 ) 
                      )'.$id_almacenid.$filtro.' 
                       AND
                      (
                        ( CONCAT(u.nombre," ",u.apellidos) LIKE  "%'.$cadena.'%" ) OR
                        ( pr.nombre LIKE  "%'.$cadena.'%" ) OR (p.nombre LIKE  "%'.$cadena.'%") OR
                        (m.id_cliente_apartado LIKE  "%'.$cadena.'%") OR 
                        ((DATE_FORMAT((m.fecha_apartado),"%d-%m-%Y") ) LIKE  "%'.$cadena.'%") OR
                        
                        ( "Salida Parcial" LIKE  "%'.$cadena.'%" ) OR
                        ( "Salida Total" LIKE  "%'.$cadena.'%" ) OR
                        ( "(Vendedor)" LIKE  "%'.$cadena.'%" ) OR
                        ( "(Tienda)" LIKE  "%'.$cadena.'%" ) 
                       )

            )'; 


          $this->db->where($where);
          $where_total = '( m.id_apartado = 3 ) or ( m.id_apartado = 6 )'.$id_almacenid.$filtro; 
          $this->db->order_by($columna, $order); 


          $this->db->group_by("m.id_usuario_apartado, m.id_cliente_apartado,m.consecutivo_venta");
          //paginacion
          $this->db->limit($largo,$inicio); 


          $result = $this->db->get();

              if ( $result->num_rows() > 0 ) {

                    $cantidad_consulta = $this->db->query("SELECT FOUND_ROWS() as cantidad");
                    $found_rows = $cantidad_consulta->row(); 
                    $registros_filtrados =  ( (int) $found_rows->cantidad);


                  foreach ($result->result() as $row) {

                              if ($row->apartado==3) {
                                 $num=$row->consecutivo_venta;
                              } else  {
                                 $num= $row->id_cliente_apartado;
                              }   
                              $proceso = "automatico";
                              $consecutivo_traspaso ="<b>en proceso</b>";
                            $dato[]= array(
                                      0=>$row->tip_pedido, //venta o surtido
                                      1=>$row->tipo_factura,   //factura o remision
                                      2=>$proceso, //automatico o manual          
                                      3=>$row->almacen,  //$row->mov_salida,
                                      4=>date( 'd-m-Y', strtotime($row->fecha_apartado)), //fecha de lo apartado
                                      
                                      5=>$row->tipo_pedido,//"Apartado o pedido"
                                      6=>$num,  //consecutivo de apartado
                                      7=>$consecutivo_traspaso,  //consecutivo de apartado
                                      
                                      8=>$row->vendedor, //responsable
                                      9=>$row->dependencia,//dependencia a la cual pertenece responsable que aparto  
                                      
                                    );
                      }



                      return json_encode ( array(
                        "draw"            => intval( $data['draw'] ),
                        "recordsTotal"    => intval( self::total_pedidos_completo($where_total) ), 
                        "recordsFiltered" => $registros_filtrados, 
                        "data"            =>  $dato 
                      ));
                    
              }   
              else {
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

 
    //3ra regilla de "/pedidos"
      public function total_pedidos_completo($where){

              $this->db->from($this->historico_registros_salidas.' as m');
              $this->db->where($where);
        
              //$this->db->group_by("m.mov_salida, m.id_usuario_apartado, m.id_cliente_apartado");
              $this->db->group_by("m.id_usuario_apartado, m.id_cliente_apartado,m.consecutivo_venta");

              $result = $this->db->get();
              $cant = $result->num_rows();
     
              if ( $cant > 0 )
                 return $cant;
              else
                 return 0;         

       }                   



  } 


?>

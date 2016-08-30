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








/////////////detalle_traspaso_historico
    public function buscador_traspaso_historico_detalle($data){

          $cadena = addslashes($data['search']['value']);
          $inicio = $data['start'];
          $largo = $data['length'];

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
                              $columna= 'm.precio';
                     break;
                   case '6':
                              $columna= 'm.id_lote, m.consecutivo';  
                     break;
                   case '7':
                              $columna= 'm.num_partida';
                     break;                     
                   
                   default:
                       $columna = 'm.codigo';
                     break;
                 }                       
                 
          $consecutivo_traspaso = $data['consecutivo_traspaso'];    

          $id_session = $this->db->escape($this->session->userdata('id'));

          $this->db->select("SQL_CALC_FOUND_ROWS *", FALSE); //
          $this->db->select('m.id_usuario_apartado, m.id_cliente_apartado, m.num_partida');  //fecha falta
          $this->db->select('pr.nombre dependencia ');  
          $this->db->select('CONCAT(u.nombre,"  ",u.apellidos) as cliente', FALSE);
          $this->db->select('CONCAT(u.nombre,"  ",u.apellidos) as vendedor', FALSE);

          $this->db->select('m.codigo,m.id_descripcion, m.id_lote,m.precio, m.fecha_apartado, m.consecutivo');  
          $this->db->select('c.hexadecimal_color,c.color nombre_color, m.ancho, um.medida');
          
          $this->db->select("( CASE WHEN m.id_medida = 1 THEN m.cantidad_um ELSE 0 END ) AS metros", FALSE);
          $this->db->select("( CASE WHEN m.id_medida = 2 THEN m.cantidad_um ELSE 0 END ) AS kilogramos", FALSE);

          $this->db->select('p.nombre comprador , m.id_apartado');  

          $this->db->select('
                        CASE m.id_apartado
                           WHEN "3" THEN "Vendedor"
                           WHEN "6" THEN "Tienda"
                           ELSE "No Pedido"
                        END AS tipo_apartado
         ',False);          


          $this->db->select('
                        CASE m.tipo_salida
                           WHEN 1 THEN "(Salida Parcial)"
                           WHEN 2 THEN "(Salida Total)"
                           ELSE "xxxx"
                        END AS tipo_pedido
         ',False);  



          $this->db->select('
                        CASE m.id_apartado
                           WHEN "3" THEN "ab1d1d"
                           WHEN "6" THEN "14b80f"
                           ELSE "No Pedido"
                        END AS color_apartado
         ',False);  

          $this->db->select("a.almacen");
          
          $this->db->select("m.id_factura,m.id_factura_original,m.id_tipo_factura, m.id_tipo_pedido");
          $this->db->select("tp.tipo_pedido");          
          $this->db->select("tf.tipo_factura");  
          $this->db->select("tff.tipo_factura t_factura");  

          $this->db->select("m.consecutivo_traspaso");  
          $this->db->select("m.id_apartado apartado");  
          $this->db->select('m.mov_salida', FALSE);


          $this->db->from($this->historico_registros_traspasos.' as m');
          $this->db->join($this->usuarios.' As u' , 'u.id = m.id_usuario_apartado','LEFT');
          $this->db->join($this->proveedores.' As pr', 'u.id_cliente = pr.id','LEFT');
          $this->db->join($this->proveedores.' As p' , 'p.id = m.id_cliente_apartado','LEFT');
          $this->db->join($this->unidades_medidas.' As um' , 'um.id = m.id_medida','LEFT');
          $this->db->join($this->colores.' As c', 'm.id_color = c.id','LEFT');

          $this->db->join($this->almacenes.' As a' , 'a.id = m.id_almacen','LEFT');
          $this->db->join($this->tipos_pedidos.' As tp' , 'tp.id = m.id_tipo_pedido','LEFT');
          $this->db->join($this->tipos_facturas.' As tf' , 'tf.id = m.id_tipo_factura','LEFT');
          $this->db->join($this->tipos_facturas.' As tff' , 'tff.id = m.id_factura','LEFT');

          //filtro de busqueda

          $where = '(
                      (
                        ( m.consecutivo_traspaso =  '.$consecutivo_traspaso.' )
                      )
                   AND
                      (
                        ( CONCAT(m.cantidad_um," ",um.medida) LIKE  "%'.$cadena.'%" ) OR (CONCAT(m.ancho," cm") LIKE  "%'.$cadena.'%")  OR
                        ( m.codigo LIKE  "%'.$cadena.'%" ) OR (m.id_descripcion LIKE  "%'.$cadena.'%") OR (c.color LIKE  "%'.$cadena.'%")  OR
                         (CONCAT(m.id_lote,"-",m.consecutivo) LIKE  "%'.$cadena.'%") OR 
                         (m.precio LIKE  "%'.$cadena.'%")
                       )
            )';   

          $this->db->where($where);
          $where_total = '( m.consecutivo_traspaso =  '.$consecutivo_traspaso.')';
          $this->db->order_by($columna, $order); 

          //paginacion
          $this->db->limit($largo,$inicio); 
          $result = $this->db->get();

              if ( $result->num_rows() > 0 ) {

                    $cantidad_consulta = $this->db->query("SELECT FOUND_ROWS() as cantidad");
                    $found_rows = $cantidad_consulta->row(); 
                    $registros_filtrados =  ( (int) $found_rows->cantidad);

                  foreach ($result->result() as $row) {

                              if ($row->id_apartado==3) {
                                $mi_cliente = $row->comprador; 
                                $num_mov = $row->cliente; 
                                
                              } else  {
                                 $mi_cliente = $row->cliente; 
                                 $num_mov = $row->id_cliente_apartado;
                              }   

                            $tipo_apartado = $row->tipo_apartado;
                            $color_apartado = $row->color_apartado;
                            $mi_fecha = date( 'd-m-Y', strtotime($row->fecha_apartado));
                            $mi_hora = date( 'h:ia', strtotime($row->fecha_apartado));

                            $dato[]= array(
                                      0=>$row->codigo,
                                      1=>$row->id_descripcion,
                                      2=>
                                      $row->nombre_color.'<div style="margin-right: 15px;float:left;background-color:#'.$row->hexadecimal_color.';width:15px;height:15px;"></div>',
                                      3=>$row->cantidad_um.' '.$row->medida, //metros,
                                      4=>$row->ancho.' cm',
                                      5=>$row->precio,
                                      6=>$row->id_lote.'-'.$row->consecutivo,         
                                      7=>$row->num_partida,
                                      8=>$row->almacen,
                                      
                                      9=>$row->id_factura,
                                      10=>$row->id_tipo_factura,
                                      11=>$row->id_tipo_pedido,
                                      12=>$row->t_factura,  
                                      13=>$row->id_factura_original                                    
                                                                   
                                    );

                            ///////////////////////////////
                              $tipo_pedido=$row->tipo_pedido;
                              $tipo_factura=$row->tipo_factura; 
                              $consecutivo_traspaso=$row->consecutivo_traspaso;
                              $traspaso=$row->t_factura;

                              $responsable =$row->vendedor; //responsable
                              $dependencia = $row->dependencia;//dependencia a la cual pertenece responsable que aparto  

                              $almacen = $row->almacen;
                              if ($row->apartado==3) {
                                 $num=$row->consecutivo_venta;
                              } else  {
                                 $num= $row->id_cliente_apartado;
                              }   

                              if ($row->apartado!=0) {
                                  $proceso = "automatico";
                                  $motivos = $row->tipo_pedido.' <b>Nro.</b>'.$num.'<br/>Salida Nro.<b>'.$row->mov_salida.'</b>';
                              } else {
                                  $proceso = "manual";
                                  $motivos = "comentario";
                              }    

                      }

                      return json_encode ( array(
                        "draw"            => intval( $data['draw'] ),
                        "recordsTotal"    => intval( self::total_completo_especifico($where_total) ), 
                        "recordsFiltered" => $registros_filtrados, 
                        "data"            =>  $dato, 
                        "datos"            =>  array(
                              "consecutivo_traspaso"=>$consecutivo_traspaso,  
                              "proceso"=>$proceso,  
                              "traspaso"=>$traspaso,  
                              "mi_fecha"=>$mi_fecha,
                              "motivos"=>$motivos,
                              "responsable"=>$responsable,
                              "dependencia"=>$dependencia,
                              "almacen"=>$almacen,
                         ),                        
                      ));
                    
              }   
              else {
                  $output = array(
                  "draw" =>  intval( $data['draw'] ),
                  "recordsTotal" => 0, //intval( self::total_completo_especifico($where_total) ), 
                  "recordsFiltered" =>0,
                  "aaData" => array()
                  );
                  $array[]="";
                  return json_encode($output);
              }
              $result->free_result();           
      }  


  public function total_completo_especifico($where){
        $this->db->from($this->historico_registros_traspasos.' as m');
        $this->db->where($where);
        $cant = $this->db->count_all_results();          

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

          $this->db->select('m.id_apartado',FALSE); //, m.mov_salida

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
                                      10=>$row->id_apartado,  

                                    );
                      }



                      return json_encode ( array(
                        "draw"            => intval( $data['draw'] ),
                        "recordsTotal"    => intval( self::total_traspaso_completo($where_total) ), 
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
      public function total_traspaso_completo($where){

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




/////////////detalle_traspaso_historico
    public function buscador_traspaso_general_detalle($data){

          $cadena = addslashes($data['search']['value']);
          $inicio = $data['start'];
          $largo = $data['length'];

          $columa_order = $data['order'][0]['column'];
                 $order = $data['order'][0]['dir'];

          $num_movimiento = $data['num_movimiento'];
          $id_apartado = $data['id_apartado'];        
          $id_almacen = $data['id_almacen'];           

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
                              $columna= 'm.precio';
                     break;
                   case '6':
                              $columna= 'm.id_lote, m.consecutivo';  
                     break;
                   case '7':
                              $columna= 'm.num_partida';
                     break;                     
                   
                   default:
                       $columna = 'm.codigo';
                     break;
                 }                       
                 
            

          $id_session = $this->db->escape($this->session->userdata('id'));

          $this->db->select("SQL_CALC_FOUND_ROWS *", FALSE); //
          $this->db->select('m.id_usuario_apartado, m.id_cliente_apartado, m.num_partida');  //fecha falta
          $this->db->select('pr.nombre dependencia ');  
          $this->db->select('CONCAT(u.nombre,"  ",u.apellidos) as cliente', FALSE);
          $this->db->select('CONCAT(u.nombre,"  ",u.apellidos) as vendedor', FALSE);

          $this->db->select('m.codigo,m.id_descripcion, m.id_lote,m.precio, m.fecha_apartado, m.consecutivo');  
          $this->db->select('c.hexadecimal_color,c.color nombre_color, m.ancho, um.medida');
          
          $this->db->select("( CASE WHEN m.id_medida = 1 THEN m.cantidad_um ELSE 0 END ) AS metros", FALSE);
          $this->db->select("( CASE WHEN m.id_medida = 2 THEN m.cantidad_um ELSE 0 END ) AS kilogramos", FALSE);

          $this->db->select('p.nombre comprador , m.id_apartado');  

          $this->db->select('
                        CASE m.id_apartado
                           WHEN "3" THEN "Vendedor"
                           WHEN "6" THEN "Tienda"
                           ELSE "No Pedido"
                        END AS tipo_apartado
         ',False);          

          $this->db->select('
                        CASE m.id_apartado
                           WHEN "3" THEN "ab1d1d"
                           WHEN "6" THEN "14b80f"
                           ELSE "No Pedido"
                        END AS color_apartado
         ',False);  

          $this->db->select("a.almacen");
          
          $this->db->select("m.id_factura,m.id_factura_original,m.id_tipo_factura, m.id_tipo_pedido");
          $this->db->select("tp.tipo_pedido");          
          $this->db->select("tf.tipo_factura");  
          $this->db->select("tff.tipo_factura t_factura");  

          //$this->db->select("m.consecutivo_traspaso");  
          $this->db->select("m.id_apartado apartado");  
          //$this->db->select('m.mov_salida', FALSE);


          $this->db->from($this->registros_entradas.' as m');
          $this->db->join($this->usuarios.' As u' , 'u.id = m.id_usuario_apartado','LEFT');
          $this->db->join($this->proveedores.' As pr', 'u.id_cliente = pr.id','LEFT');
          $this->db->join($this->proveedores.' As p' , 'p.id = m.id_cliente_apartado','LEFT');
          $this->db->join($this->unidades_medidas.' As um' , 'um.id = m.id_medida','LEFT');
          $this->db->join($this->colores.' As c', 'm.id_color = c.id','LEFT');

          $this->db->join($this->almacenes.' As a' , 'a.id = m.id_almacen','LEFT');
          $this->db->join($this->tipos_pedidos.' As tp' , 'tp.id = m.id_tipo_pedido','LEFT');
          $this->db->join($this->tipos_facturas.' As tf' , 'tf.id = m.id_tipo_factura','LEFT');
          $this->db->join($this->tipos_facturas.' As tff' , 'tff.id = m.id_factura','LEFT');

          //filtro de busqueda
          if ($id_almacen!=0) {
              $id_almacenid = ' AND ( m.id_almacen =  '.$id_almacen.' ) ';  
          } else {
              $id_almacenid = '';
          }          

          //filtro de los pedidos que tienen traspasos
          $filtro = ' AND ( ( m.id_tipo_factura <> 0 ) AND ( m.incluir <> 0 ) )';  


                              if ($id_apartado==3) {
                                 $num_mov = ' AND ( m.consecutivo_venta = '.$num_movimiento.' )';
                              } else  {
                                 $num_mov = ' AND ( m.id_cliente_apartado = "'.$num_movimiento.'" )';
                              }   



          $where = '(
                      (
                        ( m.id_apartado = '.$id_apartado.' ) 
                      )'.$id_almacenid.$filtro.$num_mov.' 
                       AND          
                      (
                        ( CONCAT(m.cantidad_um," ",um.medida) LIKE  "%'.$cadena.'%" ) OR (CONCAT(m.ancho," cm") LIKE  "%'.$cadena.'%")  OR
                        ( m.codigo LIKE  "%'.$cadena.'%" ) OR (m.id_descripcion LIKE  "%'.$cadena.'%") OR (c.color LIKE  "%'.$cadena.'%")  OR
                         (CONCAT(m.id_lote,"-",m.consecutivo) LIKE  "%'.$cadena.'%") OR 
                         (m.precio LIKE  "%'.$cadena.'%")
                       )
            )';   

          $this->db->where($where);
          $where_total = '(  m.id_apartado = '.$id_apartado.' ) '.$id_almacenid.$filtro.$num_mov; 
          $this->db->order_by($columna, $order); 

          //paginacion
          $this->db->limit($largo,$inicio); 
          $result = $this->db->get();

              if ( $result->num_rows() > 0 ) {

                    $cantidad_consulta = $this->db->query("SELECT FOUND_ROWS() as cantidad");
                    $found_rows = $cantidad_consulta->row(); 
                    $registros_filtrados =  ( (int) $found_rows->cantidad);

                  foreach ($result->result() as $row) {

                              if ($row->id_apartado==3) {
                                $mi_cliente = $row->comprador; 
                                $num_mov = $row->cliente; 
                                
                              } else  {
                                 $mi_cliente = $row->cliente; 
                                 $num_mov = $row->id_cliente_apartado;
                              }   

                            $tipo_apartado = $row->tipo_apartado;
                            $color_apartado = $row->color_apartado;
                            $mi_fecha = date( 'd-m-Y', strtotime($row->fecha_apartado));
                            $mi_hora = date( 'h:ia', strtotime($row->fecha_apartado));

                            $dato[]= array(
                                      0=>$row->codigo,
                                      1=>$row->id_descripcion,
                                      2=>
                                      $row->nombre_color.'<div style="margin-right: 15px;float:left;background-color:#'.$row->hexadecimal_color.';width:15px;height:15px;"></div>',
                                      3=>$row->cantidad_um.' '.$row->medida, //metros,
                                      4=>$row->ancho.' cm',
                                      5=>$row->precio,
                                      6=>$row->id_lote.'-'.$row->consecutivo,         
                                      7=>$row->num_partida,
                                      8=>$row->almacen,
                                      
                                      9=>$row->id_factura,
                                      10=>$row->id_tipo_factura,
                                      11=>$row->id_tipo_pedido,
                                      12=>$row->t_factura,  
                                      13=>$row->id_factura_original                                    
                                                                   
                                    );

                            ///////////////////////////////
                              $tipo_pedido=$row->tipo_pedido;
                              $tipo_factura=$row->tipo_factura; 
                              //$consecutivo_traspaso=$row->consecutivo_traspaso;
                              $traspaso=$row->t_factura;

                              $responsable =$row->vendedor; //responsable
                              $dependencia = $row->dependencia;//dependencia a la cual pertenece responsable que aparto  

                              $almacen = $row->almacen;
                              if ($row->apartado==3) {
                                 $num=$row->consecutivo_venta;
                              } else  {
                                 $num= $row->id_cliente_apartado;
                              }   

                              if ($row->apartado!=0) {
                                  $proceso = "automatico";
                                  $motivos =  $row->tipo_pedido.' <b>Nro.</b>'.$num;
                              } else {
                                  $proceso = "manual";
                                  $motivos = "comentario";
                              }    

                      }

                      return json_encode ( array(
                        "draw"            => intval( $data['draw'] ),
                        "recordsTotal"    => intval( self::total_general_detalle($where_total) ), 
                        "recordsFiltered" => $registros_filtrados, 
                        "data"            =>  $dato, 
                        "datos"            =>  array(
                              //"consecutivo_traspaso"=>$consecutivo_traspaso,  
                              "proceso"=>$proceso,  
                              "traspaso"=>$traspaso,  
                              "mi_fecha"=>$mi_fecha,
                              "motivos"=>$motivos,
                              "responsable"=>$responsable,
                              "dependencia"=>$dependencia,
                              "almacen"=>$almacen,
                         ),                        
                      ));
                    
              }   
              else {
                  $output = array(
                  "draw" =>  intval( $data['draw'] ),
                  "recordsTotal" => 0, //intval( self::total_general_detalle($where_total) ), 
                  "recordsFiltered" =>0,
                  "aaData" => array()
                  );
                  $array[]="";
                  return json_encode($output);
              }
              $result->free_result();           
      }  


  public function total_general_detalle($where){
        $this->db->from($this->registros_entradas.' as m');
        $this->db->where($where);
        $cant = $this->db->count_all_results();          

        if ( $cant > 0 )
           return $cant;
        else
           return 0;         
  }     




/////////////detalle_traspaso_historico
    public function imprimir_traspaso_general_detalle($data){

          
          $num_movimiento = $data['num_movimiento'];
          $id_apartado = $data['id_apartado'];        
          $id_almacen = $data['id_almacen'];      

          $id_session = $this->db->escape($this->session->userdata('id'));

          $this->db->select('m.id_usuario_apartado, m.id_cliente_apartado, m.num_partida');  //fecha falta
          $this->db->select('pr.nombre dependencia ');  
          $this->db->select('CONCAT(u.nombre,"  ",u.apellidos) as cliente', FALSE);
          $this->db->select('CONCAT(u.nombre,"  ",u.apellidos) as vendedor', FALSE);

          $this->db->select('m.codigo,m.id_descripcion, m.id_lote,m.precio, m.fecha_apartado, m.consecutivo');  
          $this->db->select('c.hexadecimal_color,c.color nombre_color, m.ancho, um.medida,m.cantidad_um');
          
          $this->db->select("( CASE WHEN m.id_medida = 1 THEN m.cantidad_um ELSE 0 END ) AS metros", FALSE);
          $this->db->select("( CASE WHEN m.id_medida = 2 THEN m.cantidad_um ELSE 0 END ) AS kilogramos", FALSE);

          $this->db->select('p.nombre comprador , m.id_apartado');  

          $this->db->select('
                        CASE m.id_apartado
                           WHEN "3" THEN "Vendedor"
                           WHEN "6" THEN "Tienda"
                           ELSE "No Pedido"
                        END AS tipo_apartado
         ',False);          

          $this->db->select('
                        CASE m.id_apartado
                           WHEN "3" THEN "ab1d1d"
                           WHEN "6" THEN "14b80f"
                           ELSE "No Pedido"
                        END AS color_apartado
         ',False);  

          $this->db->select("a.almacen");
          
          $this->db->select("m.id_factura,m.id_factura_original,m.id_tipo_factura, ,m.id_tipo_pedido");
          $this->db->select("tp.tipo_pedido");          
          $this->db->select("tf.tipo_factura");  
          $this->db->select("tff.tipo_factura t_factura");  
          $this->db->select("m.id_apartado apartado");  

          $this->db->select("m.consecutivo_venta consecutivo_venta");  

          

          $this->db->from($this->registros_entradas.' as m');
          $this->db->join($this->usuarios.' As u' , 'u.id = m.id_usuario_apartado','LEFT');
          $this->db->join($this->proveedores.' As pr', 'u.id_cliente = pr.id','LEFT');
          $this->db->join($this->proveedores.' As p' , 'p.id = m.id_cliente_apartado','LEFT');
          $this->db->join($this->unidades_medidas.' As um' , 'um.id = m.id_medida','LEFT');
          $this->db->join($this->colores.' As c', 'm.id_color = c.id','LEFT');

          $this->db->join($this->almacenes.' As a' , 'a.id = m.id_almacen','LEFT');
          $this->db->join($this->tipos_pedidos.' As tp' , 'tp.id = m.id_tipo_pedido','LEFT');
          $this->db->join($this->tipos_facturas.' As tf' , 'tf.id = m.id_tipo_factura','LEFT');
          $this->db->join($this->tipos_facturas.' As tff' , 'tff.id = m.id_factura','LEFT');

          //filtro de busqueda
          if ($id_almacen!=0) {
              $id_almacenid = ' AND ( m.id_almacen =  '.$id_almacen.' ) ';  
          } else {
              $id_almacenid = '';
          }          

          //filtro de los pedidos que tienen traspasos
          $filtro = ' AND ( ( m.id_tipo_factura <> 0 ) AND ( m.incluir <> 0 ) )';  


                              if ($id_apartado==3) {
                                 $num_mov = ' AND ( m.consecutivo_venta = '.$num_movimiento.' )';
                              } else  {
                                 $num_mov = ' AND ( m.id_cliente_apartado = "'.$num_movimiento.'" )';
                              }   



          $where = '(
                      (
                        ( m.id_apartado = '.$id_apartado.' ) 
                      )'.$id_almacenid.$filtro.$num_mov.' 
                       
            )';   

          $this->db->where($where);
          $where_total = '(  m.id_apartado = '.$id_apartado.' ) '.$id_almacenid.$filtro.$num_mov; 
          //$this->db->order_by($columna, $order); 


           $result = $this->db->get();
        
            if ( $result->num_rows() > 0 )
               return $result->result();
            else
               return False;
            $result->free_result();
   
      }  



    /////////////detalle_traspaso_historico
    public function imprimir_traspaso_historico_detalle($data){
                 
          $consecutivo_traspaso = $data['consecutivo_traspaso'];    

          $id_session = $this->db->escape($this->session->userdata('id'));

          $this->db->select('m.id_usuario_apartado, m.id_cliente_apartado, m.num_partida');  //fecha falta
          $this->db->select('pr.nombre dependencia ');  
          $this->db->select('CONCAT(u.nombre,"  ",u.apellidos) as cliente', FALSE);
          $this->db->select('CONCAT(u.nombre,"  ",u.apellidos) as vendedor', FALSE);

          $this->db->select('m.codigo,m.id_descripcion, m.id_lote,m.precio, m.fecha_apartado, m.consecutivo');  
          $this->db->select('c.hexadecimal_color,c.color nombre_color, m.ancho, um.medida,m.cantidad_um');
          
          $this->db->select("( CASE WHEN m.id_medida = 1 THEN m.cantidad_um ELSE 0 END ) AS metros", FALSE);
          $this->db->select("( CASE WHEN m.id_medida = 2 THEN m.cantidad_um ELSE 0 END ) AS kilogramos", FALSE);

          $this->db->select('p.nombre comprador , m.id_apartado');  

          $this->db->select('
                        CASE m.id_apartado
                           WHEN "3" THEN "Vendedor"
                           WHEN "6" THEN "Tienda"
                           ELSE "No Pedido"
                        END AS tipo_apartado
         ',False);          


          $this->db->select('
                        CASE m.tipo_salida
                           WHEN 1 THEN "(Salida Parcial)"
                           WHEN 2 THEN "(Salida Total)"
                           ELSE "xxxx"
                        END AS tipo_pedido
         ',False);  



          $this->db->select('
                        CASE m.id_apartado
                           WHEN "3" THEN "ab1d1d"
                           WHEN "6" THEN "14b80f"
                           ELSE "No Pedido"
                        END AS color_apartado
         ',False);  

          $this->db->select("a.almacen");
          
          $this->db->select("m.id_factura,m.id_factura_original,m.id_tipo_factura,m.consecutivo_venta ,m.id_tipo_pedido");
          $this->db->select("tp.tipo_pedido");          
          $this->db->select("tf.tipo_factura");  
          $this->db->select("tff.tipo_factura t_factura");  

          $this->db->select("m.consecutivo_traspaso");  
          $this->db->select("m.id_apartado apartado");  
          $this->db->select('m.mov_salida', FALSE);


          $this->db->from($this->historico_registros_traspasos.' as m');
          $this->db->join($this->usuarios.' As u' , 'u.id = m.id_usuario_apartado','LEFT');
          $this->db->join($this->proveedores.' As pr', 'u.id_cliente = pr.id','LEFT');
          $this->db->join($this->proveedores.' As p' , 'p.id = m.id_cliente_apartado','LEFT');
          $this->db->join($this->unidades_medidas.' As um' , 'um.id = m.id_medida','LEFT');
          $this->db->join($this->colores.' As c', 'm.id_color = c.id','LEFT');

          $this->db->join($this->almacenes.' As a' , 'a.id = m.id_almacen','LEFT');
          $this->db->join($this->tipos_pedidos.' As tp' , 'tp.id = m.id_tipo_pedido','LEFT');
          $this->db->join($this->tipos_facturas.' As tf' , 'tf.id = m.id_tipo_factura','LEFT');
          $this->db->join($this->tipos_facturas.' As tff' , 'tff.id = m.id_factura','LEFT');

          //filtro de busqueda

          $where = '(
                      (
                        ( m.consecutivo_traspaso =  '.$consecutivo_traspaso.' )
                      )
            )';   

          $this->db->where($where);
          $where_total = '( m.consecutivo_traspaso =  '.$consecutivo_traspaso.')';
          //$this->db->order_by($columna, $order); 


           $result = $this->db->get();
        
            if ( $result->num_rows() > 0 )
               return $result->result();
            else
               return False;
            $result->free_result();

            
      }  




/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////


 

        public function establecer_productos_traspasado( $data ){
                
                $id_almacen= $data['id_almacen'];
                
                if ($data['id_tipo_factura']==1){
                    $porciento_aplicar =16;  
                } else {
                     $porciento_aplicar = 0;  
                }
                
                $this->db->set( 'id_factura_original', 'id_factura', false);
                $this->db->set( 'id_factura', $data['id_tipo_factura'], false);

                $this->db->set( 'proceso_traspaso', 1, false);
                
                
                if ($data['id_tipo_factura']==1){
                    $this->db->set( 'iva', '((id_factura = 1)*'.$porciento_aplicar.')', false);
                }
                


                $this->db->set( 'incluir', 1);

                
                if ($id_almacen!=0) {
                    $id_almacenid = ' AND ( id_almacen =  '.$id_almacen.' ) ';  
                } else {
                    $id_almacenid = '';
                } 

                $cond_traspaso = ' AND ( ( incluir =  0 ) AND (proceso_traspaso = 0) )';  

                //$this->db->where('id',$data['id']);

                $where = '(
                          (
                            ( id = '.$data['id'].' )  
                          )'.$id_almacenid.$cond_traspaso.' 

                      )';   

                $this->db->where($where);
                $this->db->update($this->registros );
                if ($this->db->affected_rows() > 0) {
                  return TRUE;
                }  else
                   return FALSE;

        }   


 public function buscador_entrada($data){

          $cadena = addslashes($data['search']['value']);
          $inicio = $data['start'];
          $largo = $data['length'];

          $id_tipo_factura = $data['id_tipo_factura'];
          $id_tipo_factura_inversa = $data['id_tipo_factura_inversa'];
          
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

          $this->db->select('m.id, m.movimiento,m.id_empresa, m.factura, m.id_descripcion, m.id_operacion,m.devolucion, m.num_partida');
          $this->db->select('m.id_color, m.id_composicion, m.id_calidad, m.referencia');
          $this->db->select('m.id_medida, m.cantidad_um, m.cantidad_royo, m.ancho, m.precio,m.iva, m.codigo, m.comentario');
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

        $id_tipo_facturaid = ' AND ( m.id_factura =  '.$id_tipo_factura_inversa.' ) AND ( ( incluir =  0 ) AND (proceso_traspaso = 0) ) ';      
                           

         //este no hace falta en pedido porq no se filtra
          
        
          $where = '(
                      (
                        (
                          ( ( (m.id_apartado = 3)  or ( m.id_apartado = 6 ) ) ) OR
                          (( m.id_apartado = 0 ) AND ( m.id_operacion = "1" ) )
                        )  AND ( m.estatus_salida = "0" ) AND (m.id_almacen = '.$data['id_almacen'].' )  '.$donde.'

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
                          (   ( (m.id_apartado = 3)  or ( m.id_apartado = 6 ) ) ) OR
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
                                      5=>$row->precio,
                                      6=>$row->iva,
                                      7=>
                                           '<a style="  padding: 1px 0px 1px 0px;" href="'.base_url().'procesar_entradas/'.base64_encode($row->movimiento).'/'.base64_encode($row->devolucion).'/'.base64_encode($retorno).'"
                                               type="button" class="btn btn-success btn-block">'.$row->movimiento.'</a>', 
                                      8=>$row->nombre,
                                      9=>$row->id_lote.'-'.$row->consecutivo,
                                      10=>$row->id,
                                      11=>$row->id_apartado,
                                      12=>$row->num_partida,
                                      13=>$row->metros,
                                      14=>$row->kilogramos,
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









 public function buscador_salida($data){

          $cadena = addslashes($data['search']['value']);
          $inicio = $data['start'];
          $largo = $data['length'];

          $id_tipo_factura = $data['id_tipo_factura'];
          $id_tipo_factura_inversa = $data['id_tipo_factura_inversa'];
          
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

          $this->db->select('m.id, m.movimiento,m.id_empresa, m.factura, m.id_descripcion, m.id_operacion,m.devolucion, m.num_partida');
          $this->db->select('m.id_color, m.id_composicion, m.id_calidad, m.referencia');
          $this->db->select('m.id_medida, m.cantidad_um, m.cantidad_royo, m.ancho, m.precio,m.iva, m.codigo, m.comentario');
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

        $id_tipo_facturaid = ' AND ( m.id_factura =  '.$id_tipo_factura.' ) AND ( ( incluir =  1 ) AND (proceso_traspaso = 1) ) ';      
                           

         //este no hace falta en pedido porq no se filtra
          
        
          $where = '(
                      (
                        (
                          ( ( (m.id_apartado = 3)  or ( m.id_apartado = 6 ) ) ) OR
                          (( m.id_apartado = 0 ) AND ( m.id_operacion = "1" ) )
                        )  AND ( m.estatus_salida = "0" ) AND (m.id_almacen = '.$data['id_almacen'].' )  '.$donde.'

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
                          (   ( (m.id_apartado = 3)  or ( m.id_apartado = 6 ) ) ) OR
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
                                      5=>$row->precio,
                                      6=>$row->iva,
                                      7=>
                                           '<a style="  padding: 1px 0px 1px 0px;" href="'.base_url().'procesar_entradas/'.base64_encode($row->movimiento).'/'.base64_encode($row->devolucion).'/'.base64_encode($retorno).'"
                                               type="button" class="btn btn-success btn-block">'.$row->movimiento.'</a>', 
                                      8=>$row->nombre,
                                      9=>$row->id_lote.'-'.$row->consecutivo,
                                      10=>$row->id,
                                      11=>$row->id_apartado,
                                      12=>$row->num_partida,
                                      13=>$row->metros,
                                      14=>$row->kilogramos,
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
      




        public function quitar_productos_traspasado( $data ){
                $id_almacen= $data['id_almacen'];
                $porciento_aplicar = 16;                 

                $this->db->set( 'iva', '((id_factura_original = 1)*'.$porciento_aplicar.')', false);
                $this->db->set( 'incluir', 0);
                $this->db->set( 'proceso_traspaso', 0);

                $this->db->set( 'id_factura', 'id_factura_original', false);
                $this->db->set( 'id_factura_original', 0, false);

                $cond_traspaso = '  AND ( ( incluir =  1 ) AND ( proceso_traspaso =  1 ) )';  

                if ($id_almacen!=0) {
                    $id_almacenid = ' AND ( id_almacen =  '.$id_almacen.' ) ';  
                } else {
                    $id_almacenid = '';
                } 

                $where = '(
                          (
                            ( id = '.$data['id'].' )  
                          )'.$id_almacenid.$cond_traspaso.' 

                      )';   

                $this->db->where($where);                

                $this->db->update($this->registros );

                if ($this->db->affected_rows() > 0) {
                  return TRUE;
                }  else
                   return FALSE;                

          }      




  } 


?>

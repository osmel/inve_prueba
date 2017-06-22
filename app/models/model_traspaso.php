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

                   case '2':
                        $columna = 'pr.nombre'; //automatico y manual
                     break;

                   case '3':
                        $columna = 'm.id_almacen';  //,m.id_cliente_apartado,
                     break;
                   case '4':
                        $columna = 'm.fecha_apartado';
                     break;                     
                   case '5':
                        $columna = 'm.mov_salida';
                     break;   
                   case '6':
                        $columna = 'm.consecutivo_venta, m.id_cliente_apartado';
                     break;   
                   case '7':
                        $columna = 'u.nombre, u.apellidos';
                     break;                      
                   case '8':
                        $columna = 'pr.nombre';
                     break;                

                   case '9':
                        $columna = 'metros';
                     break;
                   case '10':
                        $columna = 'kilogramos';
                     break;
                   case '11':
                        $columna = 'pieza';
                     break;

                   case '12':
                        $columna = 'subtotal';
                     break;
                   case '13':
                        $columna = 'iva';
                     break;

                           

                   default:
                       $columna = 'u.nombre, u.apellidos';
                     break;
                 }            
          

          $id_session = $this->db->escape($this->session->userdata('id'));

          $this->db->select("SQL_CALC_FOUND_ROWS(m.consecutivo_traspaso)"); // , FALSE
          //m.id_usuario_apartado, ,m.id_fac_orig, p.nombre comprador,
          $this->db->select('m.id_apartado apartado');   

          $this->db->select('m.id_cliente_apartado,consecutivo_venta, m.fecha_apartado,m.comentario_traspaso,m.id_factura');  
          $this->db->select('CONCAT(u.nombre,"  ",u.apellidos) as vendedor', FALSE);
          $this->db->select('pr.nombre as dependencia'); //, FALSE


          $this->db->select('m.consecutivo_traspaso'); //, FALSE
          $this->db->select('m.mov_salida'); //, FALSE
          

          $this->db->select('
                        CASE m.id_apartado
                           WHEN "3" THEN "(Pedido de vendedor)"
                           WHEN "6" THEN "(Pedido de Tienda)"
                           ELSE "No Pedido"
                        END AS tipo_pedido
         ',False);  

          $this->db->select("a.almacen");
          $this->db->select("m.consecutivo_venta");
          $this->db->select("tp.tipo_pedido tip_pedido"); //, false
          $this->db->select("tf.tipo_factura");          


          $this->db->select("SUM((m.id_medida =1) * m.cantidad_um) as metros"); //, FALSE
          $this->db->select("SUM((m.id_medida =2) * m.cantidad_um) as kilogramos"); //, FALSE
          $this->db->select("COUNT(m.id_medida) as 'pieza'");
          $this->db->select("SUM(m.precio*m.cantidad_um) as subtotal"); //, FALSE
          $this->db->select("(SUM(m.precio*m.cantidad_um*m.iva))/100 as iva"); //, FALSE
          $this->db->select("SUM(m.precio*m.cantidad_um)+(SUM(m.precio*m.cantidad_um*m.iva))/100 as total"); //, FALSE
              
              


          
          $this->db->from($this->historico_registros_traspasos.' as m');
          $this->db->join($this->almacenes.' As a' , 'a.id = m.id_almacen AND a.activo=1');
          $this->db->join($this->usuarios.' As u' , 'u.id = m.id_usuario_apartado','LEFT'); //
          $this->db->join($this->proveedores.' As pr', 'u.id_cliente = pr.id','LEFT'); //
          $this->db->join($this->proveedores.' As p' , 'p.id = m.id_cliente_apartado','LEFT'); //
          $this->db->join($this->tipos_pedidos.' As tp' , 'tp.id = m.id_tipo_pedido','LEFT'); //
          $this->db->join($this->tipos_facturas.' As tf' , 'tf.id = m.id_tipo_factura','LEFT'); //


          if ($id_almacen!=0) {
              $id_almacenid = ' AND ( m.id_almacen =  '.$id_almacen.' ) ';  
          } else {
              $id_almacenid = '';
          }          

         if ($data['id_factura']!=0) {
              $id_facturaid = ' AND ( m.id_factura =  '.$data['id_factura'].' ) ';  
          } else {
              $id_facturaid = '';
          }          

          
          if  ( ($data['fecha_inicial'] !="") and  ($data['fecha_final'] !="")) {
                           $fecha_inicial = date( 'Y-m-d', strtotime( $data['fecha_inicial'] ));
                           $fecha_final = date( 'Y-m-d', strtotime( $data['fecha_final'] ));
                          
                          
                            $fechas = ' AND ( ( DATE_FORMAT((m.fecha_apartado),"%Y-%m-%d")  >=  "'.$fecha_inicial.'" )  AND  ( DATE_FORMAT((m.fecha_apartado),"%Y-%m-%d")  <=  "'.$fecha_final.'" ) )'; 
                          

          } else {
           $fechas = '';
          }          

          //filtro de los pedidos que tienen traspasos
          $filtro = ' (  ( m.incluir <> 0 ) ) ';   //( m.id_tipo_factura <> 0 ) AND
//$id_almacenid = '';
//$filtro ='';
          $where = '(
                      ('.$filtro.$id_almacenid.$id_facturaid.$fechas.') 
                       AND
                      (
                        ( CONCAT(u.nombre," ",u.apellidos) LIKE  "%'.$cadena.'%" ) OR
                        ( pr.nombre LIKE  "%'.$cadena.'%" ) OR (p.nombre LIKE  "%'.$cadena.'%") OR
                        (m.id_cliente_apartado LIKE  "%'.$cadena.'%") OR 
                        ((DATE_FORMAT((m.fecha_apartado),"%d-%m-%Y") ) LIKE  "%'.$cadena.'%") OR
                        
                        ( "Salida Parcial" LIKE  "%'.$cadena.'%" ) OR
                        ( "Salida Total" LIKE  "%'.$cadena.'%" ) OR
                        ( "(Vendedor)" LIKE  "%'.$cadena.'%" ) OR
                        ( "(Tienda)" LIKE  "%'.$cadena.'%" ) OR
                        ( m.codigo LIKE  "%'.$cadena.'%" ) 
                       ) 

            )';            


          $this->db->where($where);
          //$where_total = '( m.id_apartado = 3 ) or ( m.id_apartado = 6 )'.$id_almacenid.$filtro; 
          $where_total = '('.$filtro.$id_almacenid.$id_facturaid.$fechas.')'; 
          $this->db->order_by($columna, $order); 

          /*
          SELECT m.consecutivo_traspaso,m.id_factura,m.id_fac_orig,m.id_usuario_apartado, m.id_cliente_apartado,m.consecutivo_venta 
            , id_almacen,  fecha_apartado, incluir,cantidad_um,iva,precio
          FROM inven_historico_registros_traspasos m WHERE m.consecutivo_traspaso =18
          m.id_factura,m.id_fac_orig,m.id_cliente_apartado,m.consecutivo_venta

          //$this->db->group_by("m.consecutivo_traspaso,m.id_factura,m.id_fac_orig,m.id_usuario_apartado, m.id_cliente_apartado,m.consecutivo_venta");
          //$this->db->group_by("m.consecutivo_traspaso,m.id_usuario_apartado,m.id_factura,m.id_fac_orig");
          //paginacion          

          */

          //m.id_fac_orig,
          $this->db->group_by("m.consecutivo_traspaso,m.id_factura,m.id_usuario_apartado, m.id_cliente_apartado,m.consecutivo_venta");

         
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
                                  $motivo = $row->comentario_traspaso;
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
                                      10=>number_format($row->metros, 2, '.', ','), //total
                                      11=>number_format($row->kilogramos, 2, '.', ','), //total
                                      12=>$row->pieza, //total
                                      13=>number_format($row->subtotal, 2, '.', ','), //total
                                      14=>number_format($row->iva, 2, '.', ','), //total
                                      15=>number_format($row->total, 2, '.', ','), //total
                                      16=>$row->id_factura, //responsable

                                    );
                      }



                      return json_encode ( array(
                        "draw"            => intval( $data['draw'] ),
                        "recordsTotal"    => $registros_filtrados, //intval( self::total_traspaso_historico($where_total) ), 
                        "recordsFiltered" => $registros_filtrados, 
                        "data"            =>  $dato,

                        "totales"            =>  array(
                                "pieza"=>intval( self::totales_campos_traspaso($where_total)->pieza ), 
                                "metro"=>floatval( self::totales_campos_traspaso($where_total)->metros ),
                                "kilogramo"=>floatval( self::totales_campos_traspaso($where_total)->kilogramos ),
                              ),  
                          "totales_importe"            =>  array(
                                "subtotal"=>floatval( self::totales_importes_traspaso($where_total)->subtotal ), 
                                "iva"=>floatval( self::totales_importes_traspaso($where_total)->iva ), 
                                "total"=>floatval( self::totales_importes_traspaso($where_total)->total ),
                                ),  


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





public function totales_importes_traspaso($where){

           $this->db->select("SUM(m.precio*m.cantidad_um) as subtotal", FALSE);
           $this->db->select("(SUM(m.precio*m.cantidad_um*m.iva))/100 as iva", FALSE);
           $this->db->select("SUM(m.precio*m.cantidad_um)+(SUM(m.precio*m.cantidad_um*m.iva))/100 as total", FALSE);
   
           $this->db->from($this->historico_registros_traspasos.' as m');
          $this->db->where($where);
    
          //$this->db->group_by("m.consecutivo_traspaso,m.id_factura,m.id_usuario_apartado, m.id_cliente_apartado,m.consecutivo_venta");


          $result = $this->db->get();
      
          if ( $result->num_rows() > 0 )
             return $result->row();
          else
             return False;
          $result->free_result();              

    }  



 public function totales_campos_traspaso($where){

              $this->db->select("SUM((id_medida =1) * cantidad_um) as metros"); //, FALSE
              $this->db->select("SUM((id_medida =2) * cantidad_um) as kilogramos"); //, FALSE
              $this->db->select("COUNT(m.id_medida) as 'pieza'");
             
               $this->db->from($this->historico_registros_traspasos.' as m');
              $this->db->where($where);
        
             // $this->db->group_by("m.consecutivo_traspaso,m.id_factura,m.id_usuario_apartado, m.id_cliente_apartado,m.consecutivo_venta");


             $result = $this->db->get();
          
              if ( $result->num_rows() > 0 )
                 return $result->row();
              else
                 return False;
              $result->free_result();              

       }  





 
    //3ra regilla de "/pedidos"
      public function total_traspaso_historico($where){

              $this->db->from($this->historico_registros_traspasos.' as m');
              $this->db->where($where);
        
              $this->db->group_by("m.consecutivo_traspaso,m.id_factura,m.id_fac_orig,m.id_usuario_apartado, m.id_cliente_apartado,m.consecutivo_venta");

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

          //m.id_usuario_apartado,
          $this->db->select("SQL_CALC_FOUND_ROWS(m.id_cliente_apartado)"); // , FALSE
          $this->db->select('m.id_cliente_apartado, m.num_partida, m.id_factura_original, m.cantidad_um');  //fecha falta
          $this->db->select('pr.nombre dependencia');  
          $this->db->select('CONCAT(u.nombre,"  ",u.apellidos) as cliente', FALSE); //
          $this->db->select('CONCAT(u.nombre,"  ",u.apellidos) as vendedor', FALSE); //

          $this->db->select('m.codigo,m.id_descripcion, m.id_lote,m.precio, m.fecha_apartado, m.consecutivo');  

          $this->db->select('(m.precio*m.cantidad_um) as subtotal');           
          $this->db->select("((m.precio*m.cantidad_um*m.iva))/100 as sum_iva"); //, FALSE

          $this->db->select('c.hexadecimal_color,c.color nombre_color, m.ancho, um.medida, m.id_estatus');
          
          $this->db->select("( CASE WHEN m.id_medida = 1 THEN m.cantidad_um ELSE 0 END ) AS metros"); //, FALSE
          $this->db->select("( CASE WHEN m.id_medida = 2 THEN m.cantidad_um ELSE 0 END ) AS kilogramos"); //, FALSE

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
                           ELSE "(Salida Total)"
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

            //m.id_fac_orig, m.id_factura_original,
          $this->db->select("m.id_factura,m.id_tipo_factura, m.id_tipo_pedido");
          $this->db->select("tp.tipo_pedido");          
          $this->db->select("tf.tipo_factura");  
          $this->db->select("tff.tipo_factura t_factura");  

          $this->db->select("m.consecutivo_traspaso");  
          $this->db->select("m.id_apartado apartado,m.comentario_traspaso");  
          $this->db->select('m.mov_salida');
          $this->db->select("prod.codigo_contable");  

        


          $this->db->from($this->historico_registros_traspasos.' as m');
          $this->db->join($this->almacenes.' As a' , 'a.id = m.id_almacen AND a.activo=1');
          $this->db->join($this->productos.' As prod' , 'prod.referencia = m.referencia'); //,'LEFT'
          $this->db->join($this->usuarios.' As u' , 'u.id = m.id_usuario_apartado','LEFT'); //
          $this->db->join($this->proveedores.' As pr', 'u.id_cliente = pr.id','LEFT'); //,'LEFT'
          $this->db->join($this->proveedores.' As p' , 'p.id = m.id_cliente_apartado','LEFT'); //,'LEFT'
          $this->db->join($this->unidades_medidas.' As um' , 'um.id = m.id_medida'); //,'LEFT'
          $this->db->join($this->colores.' As c', 'm.id_color = c.id'); //,'LEFT'
          $this->db->join($this->tipos_pedidos.' As tp' , 'tp.id = m.id_tipo_pedido','LEFT'); //,'LEFT'
          $this->db->join($this->tipos_facturas.' As tf' , 'tf.id = m.id_tipo_factura','LEFT'); //,'LEFT'
          $this->db->join($this->tipos_facturas.' As tff' , 'tff.id = m.id_factura','LEFT'); //

          //filtro de busqueda

          $where = '(
                      (
                        ( m.consecutivo_traspaso =  '.$consecutivo_traspaso.' ) AND ( m.id_factura =  '.$data["id_factura"].' )
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
                            //como es apartado se recoge la fecha de apartado
                            //$mi_fecha = date('Y-m-d');
                            $mi_hora = date( 'h:ia', strtotime($row->fecha_apartado));

                            $dato[]= array(
                                      0=>$row->codigo,
                                      1=>$row->id_descripcion,
                                      2=>
                                      $row->nombre_color.'<div style="margin-right: 15px;float:left;background-color:#'.$row->hexadecimal_color.';width:15px;height:15px;"></div>',
                                      3=>$row->cantidad_um.' '.$row->medida, //metros,
                                      4=>$row->ancho.' cm',
                                      5=>number_format($row->subtotal, 2, '.', ','), 
                                      6=>number_format($row->sum_iva, 2, '.', ','),                                       
                                      7=>$row->id_lote.'-'.$row->consecutivo,         
                                      8=>$row->num_partida,
                                      9=>$row->almacen,
                                      
                                      10=>$row->id_factura,
                                      11=>$row->id_tipo_factura,
                                      12=>$row->id_tipo_pedido,
                                      13=>$row->t_factura,  
                                      14=>$row->id_factura_original,
                                      15=>$row->codigo_contable,                                      
                                      16=>$row->metros,                                      
                                      17=>$row->kilogramos,                           
                                      18=>number_format($row->precio, 2, '.', ','),
                                      19=>$row->id_estatus,
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
                                  $motivos = $row->comentario_traspaso;
                              }    

                      }

                      return json_encode ( array(
                        "draw"            => intval( $data['draw'] ),
                        "recordsTotal"    => $registros_filtrados, 
                        "recordsFiltered" => $registros_filtrados, 
                        "data"            =>  $dato, 
                        "datos"           =>  array(
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
                  "recordsTotal" => 0, 
                  "recordsFiltered" =>0,
                  "aaData" => array()
                  );
                  $array[]="";
                  return json_encode($output);
              }
              $result->free_result();           
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
          $this->db->select('p.nombre comprador, m.id_apartado apartado, m.id_factura,m.id_fac_orig'); 
          
          

          $this->db->select('CONCAT(u.nombre,"  ",u.apellidos) as vendedor', FALSE);
          $this->db->select('pr.nombre as dependencia', FALSE);

          $this->db->select('CONCAT(u_manual.nombre,"  ",u_manual.apellidos) as vendedor_manual', FALSE);
          $this->db->select('pr_manual.nombre as dependencia_manual', FALSE);

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
          $this->db->select("tf_manual.tipo_factura tipo_factura_manual");    


          $this->db->select('m.id_usuario_traspaso,m.comentario_traspaso comentario,m.num_control factura');

          $this->db->select("SUM((m.id_medida =1) * m.cantidad_um) as metros", FALSE);
          $this->db->select("SUM((m.id_medida =2) * m.cantidad_um) as kilogramos", FALSE);
          $this->db->select("COUNT(m.id_medida) as 'pieza'");
           $this->db->select("SUM(m.precio*m.cantidad_um) as subtotal", FALSE);
           $this->db->select("(SUM(m.precio*m.cantidad_um*m.iva))/100 as iva", FALSE);
           $this->db->select("SUM(m.precio*m.cantidad_um)+(SUM(m.precio*m.cantidad_um*m.iva))/100 as total", FALSE);

          

          
          $this->db->from($this->registros_entradas.' as m');
          $this->db->join($this->almacenes.' As a' , 'a.id = m.id_almacen AND a.activo=1');
          $this->db->join($this->usuarios.' As u' , 'u.id = m.id_usuario_apartado','LEFT');
          $this->db->join($this->proveedores.' As pr', 'u.id_cliente = pr.id','LEFT');
          $this->db->join($this->proveedores.' As p' , 'p.id = m.id_cliente_apartado','LEFT');
          $this->db->join($this->tipos_pedidos.' As tp' , 'tp.id = m.id_tipo_pedido','LEFT');
          $this->db->join($this->tipos_facturas.' As tf' , 'tf.id = m.id_tipo_factura','LEFT');
          $this->db->join($this->tipos_facturas.' As tf_manual' , 'tf_manual.id = m.id_factura','LEFT');
          $this->db->join($this->usuarios.' As u_manual' , 'u_manual.id = m.id_usuario_traspaso','LEFT');
          $this->db->join($this->proveedores.' As pr_manual', 'u_manual.id_cliente = pr_manual.id','LEFT');
          

          if ($id_almacen!=0) {
              $id_almacenid = ' AND ( m.id_almacen =  '.$id_almacen.' ) ';  
          } else {
              $id_almacenid = '';
          }          

          if ($data['id_factura']!=0) {
              $id_facturaid = ' AND ( m.id_factura =  '.$data['id_factura'].' ) ';  
          } else {
              $id_facturaid = '';
          }          

          
          if  ( ($data['fecha_inicial'] !="") and  ($data['fecha_final'] !="")) {
                           $fecha_inicial = date( 'Y-m-d', strtotime( $data['fecha_inicial'] ));
                           $fecha_final = date( 'Y-m-d', strtotime( $data['fecha_final'] ));
                          
                          
                            $fechas = ' AND ( ( DATE_FORMAT((m.fecha_apartado),"%Y-%m-%d")  >=  "'.$fecha_inicial.'" )  AND  ( DATE_FORMAT((m.fecha_apartado),"%Y-%m-%d")  <=  "'.$fecha_final.'" ) )'; 
                          

          } else {
           $fechas = '';
          }           

          //filtro de los pedidos que tienen traspasos
          //$filtro = ' AND ( ( m.id_tipo_factura <> 0 ) AND ( m.incluir <> 0 ) )';  
          //$filtro = ' AND ( (      (m.id_tipo_factura <> 0 ) OR (m.proceso_traspaso = 1 )  )  AND ( m.incluir <> 0 ) )  ';  

          $filtro = '(   
                       (  ( ( m.id_apartado = 3 ) or ( m.id_apartado = 6 ) ) AND  ( m.id_tipo_factura <> 0 ) AND ( m.incluir <> 0 )  ) 
                        OR
                       (m.proceso_traspaso = 1)
                    )
                    ';    

          $where = '(
                      '.$filtro.$id_almacenid.$id_facturaid.$fechas.' 
                       AND
                      (
                        ( CONCAT(u.nombre," ",u.apellidos) LIKE  "%'.$cadena.'%" ) OR
                        ( pr.nombre LIKE  "%'.$cadena.'%" ) OR (p.nombre LIKE  "%'.$cadena.'%") OR
                        (m.id_cliente_apartado LIKE  "%'.$cadena.'%") OR 
                        ((DATE_FORMAT((m.fecha_apartado),"%d-%m-%Y") ) LIKE  "%'.$cadena.'%") OR
                        
                        ( "Salida Parcial" LIKE  "%'.$cadena.'%" ) OR
                        ( "Salida Total" LIKE  "%'.$cadena.'%" ) OR
                        ( "(Vendedor)" LIKE  "%'.$cadena.'%" ) OR
                        ( "(Tienda)" LIKE  "%'.$cadena.'%" ) OR
                        ( m.codigo LIKE  "%'.$cadena.'%" ) 
                       )

            )'; 


          $this->db->where($where);
          //$where_total = '( m.id_apartado = 3 ) or ( m.id_apartado = 6 )'.$id_almacenid.$filtro; 
          $where_total = $filtro.$id_almacenid.$id_facturaid.$fechas; 
          $this->db->order_by($columna, $order); 


          $this->db->group_by("m.id_usuario_apartado, m.id_factura,m.id_fac_orig, m.id_cliente_apartado,m.consecutivo_venta");
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

                              
                                if ($row->proceso_traspaso==1) {
                                  $proceso = "manual";    
                                  $responsable = $row->vendedor_manual;
                                  $dependencia =$row->dependencia_manual;
                                } else {
                                  $proceso = "automatico";    
                                  $responsable = $row->vendedor;
                                  $dependencia =$row->dependencia;
                                }  
                              
                              $consecutivo_traspaso ="<b>en proceso</b>";
                            $dato[]= array(
                                      0=>$row->tip_pedido, //venta o surtido
                                      1=>$row->tipo_factura,   //factura o remision
                                      2=>$proceso, //automatico o manual          
                                      3=>$row->almacen,  //$row->mov_salida,
                                      4=>date( 'd-m-Y'), //date( 'd-m-Y', strtotime($row->fecha_apartado)), 

                                      5=>$row->tipo_pedido,//"Apartado o pedido"
                                      6=>$num,  //consecutivo de apartado
                                      7=>$consecutivo_traspaso,  //consecutivo de apartado
                                      
                                      8=>$responsable, //responsable
                                      9=>$dependencia,//dependencia a la cual pertenece responsable que aparto  
                                      10=>$row->id_apartado,  
                                      11=>$row->proceso_traspaso,  
                                      12=>$row->comentario,  
                                      13=>$row->comentario,
                                      14=>$row->tipo_factura_manual,
                                      15=>$row->id_usuario_traspaso,
                                      16=>number_format($row->metros, 2, '.', ','), //total
                                      17=>number_format($row->kilogramos, 2, '.', ','), //total
                                      18=>$row->pieza, //total
                                      19=>number_format($row->subtotal, 2, '.', ','), //total
                                      20=>number_format($row->iva, 2, '.', ','), //total
                                      21=>number_format($row->total, 2, '.', ','), //total
                                      22=>$row->id_factura,

                                      


                                    );
                      }



                      return json_encode ( array(
                        "draw"            => intval( $data['draw'] ),
                        "recordsTotal"    => $registros_filtrados, //intval( self::total_traspaso_completo($where_total) ), 
                        "recordsFiltered" => $registros_filtrados, 
                        "data"            =>  $dato,
                        "totales"            =>  array(
                                "pieza"=>intval( self::totales_campos_traspaso_especifico($where_total)->pieza ), 
                                "metro"=>floatval( self::totales_campos_traspaso_especifico($where_total)->metros ),
                                "kilogramo"=>floatval( self::totales_campos_traspaso_especifico($where_total)->kilogramos ),
                              ),  
                          "totales_importe"            =>  array(
                                "subtotal"=>floatval( self::totales_importes_traspaso_especifico($where_total)->subtotal ), 
                                "iva"=>floatval( self::totales_importes_traspaso_especifico($where_total)->iva ), 
                                "total"=>floatval( self::totales_importes_traspaso_especifico($where_total)->total ),
                                ),  

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

 





public function totales_importes_traspaso_especifico($where){

           $this->db->select("SUM(m.precio*m.cantidad_um) as subtotal", FALSE);
           $this->db->select("(SUM(m.precio*m.cantidad_um*m.iva))/100 as iva", FALSE);
           $this->db->select("SUM(m.precio*m.cantidad_um)+(SUM(m.precio*m.cantidad_um*m.iva))/100 as total", FALSE);
   
           $this->db->from($this->registros_entradas.' as m');
          $this->db->where($where);
    
          //$this->db->group_by("m.consecutivo_traspaso,m.id_factura,m.id_usuario_apartado, m.id_cliente_apartado,m.consecutivo_venta");


          $result = $this->db->get();
      
          if ( $result->num_rows() > 0 )
             return $result->row();
          else
             return False;
          $result->free_result();              

    }  



 public function totales_campos_traspaso_especifico($where){

              $this->db->select("SUM((id_medida =1) * cantidad_um) as metros", FALSE);
              $this->db->select("SUM((id_medida =2) * cantidad_um) as kilogramos", FALSE);
              $this->db->select("COUNT(m.id_medida) as 'pieza'");
             
               $this->db->from($this->registros_entradas.' as m');
              $this->db->where($where);
        
             // $this->db->group_by("m.consecutivo_traspaso,m.id_factura,m.id_usuario_apartado, m.id_cliente_apartado,m.consecutivo_venta");


             $result = $this->db->get();
          
              if ( $result->num_rows() > 0 )
                 return $result->row();
              else
                 return False;
              $result->free_result();              

       }  




    //3ra regilla de "/pedidos"
      public function total_traspaso_completo($where){

              $this->db->from($this->registros_entradas.' as m');
              $this->db->where($where);
        
              //$this->db->group_by("m.mov_salida, m.id_usuario_apartado, m.id_cliente_apartado");
              $this->db->group_by("m.id_usuario_apartado,m.id_factura,m.id_fac_orig, m.id_cliente_apartado,m.consecutivo_venta");

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

          $this->db->select('m.codigo,m.id_descripcion, m.id_lote,m.precio,  m.fecha_apartado, m.consecutivo');  

          $this->db->select("((m.precio*m.cantidad_um*m.iva))/100 as iva", FALSE);



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
          
          $this->db->select("m.id_factura,m.id_fac_orig, m.id_factura_original,m.id_tipo_factura, m.id_tipo_pedido");
          $this->db->select("tp.tipo_pedido");          
          $this->db->select("tf.tipo_factura");  
          $this->db->select("tff.tipo_factura t_factura");  

          //$this->db->select("m.consecutivo_traspaso");  
          $this->db->select("m.id_apartado apartado");  
          //$this->db->select('m.mov_salida', FALSE);

          $this->db->select("prod.codigo_contable");  

          $this->db->from($this->registros_entradas.' as m');
          $this->db->join($this->almacenes.' As a' , 'a.id = m.id_almacen AND a.activo=1');
          $this->db->join($this->productos.' As prod' , 'prod.referencia = m.referencia','LEFT');
          $this->db->join($this->usuarios.' As u' , 'u.id = m.id_usuario_apartado','LEFT');
          $this->db->join($this->proveedores.' As pr', 'u.id_cliente = pr.id','LEFT');
          $this->db->join($this->proveedores.' As p' , 'p.id = m.id_cliente_apartado','LEFT');
          $this->db->join($this->unidades_medidas.' As um' , 'um.id = m.id_medida','LEFT');
          $this->db->join($this->colores.' As c', 'm.id_color = c.id','LEFT');
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
                        ( m.id_apartado = '.$id_apartado.' ) AND ( m.id_factura =  '.$data["id_factura"].' )
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
                            //$mi_fecha = date( 'd-m-Y', strtotime($row->fecha_apartado));
                            $mi_fecha = date('Y-m-d');
                            $mi_hora = date( 'h:ia', strtotime($row->fecha_apartado));

                            $dato[]= array(
                                      0=>$row->codigo,
                                      1=>$row->id_descripcion,
                                      2=>
                                      $row->nombre_color.'<div style="margin-right: 15px;float:left;background-color:#'.$row->hexadecimal_color.';width:15px;height:15px;"></div>',
                                      3=>$row->cantidad_um.' '.$row->medida, //metros,
                                      4=>$row->ancho.' cm',
                                      5=>number_format($row->precio, 2, '.', ','), 
                                      6=>number_format($row->iva, 2, '.', ','), 
                                      7=>$row->id_lote.'-'.$row->consecutivo,         
                                      8=>$row->num_partida,
                                      9=>$row->almacen,
                                      
                                      10=>$row->id_factura,
                                      11=>$row->id_tipo_factura,
                                      12=>$row->id_tipo_pedido,
                                      13=>$row->t_factura,  
                                      14=>$row->id_factura_original,
                                      15=>$row->codigo_contable,
                                      16=>$row->metros,
                                      17=>$row->kilogramos,                                        
                                      18=>number_format($row->precio, 2, '.', ','), 

                                                                   
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
    public function buscador_traspaso_general_detalle_manual($data){

          $cadena = addslashes($data['search']['value']);
          $inicio = $data['start'];
          $largo = $data['length'];

          $columa_order = $data['order'][0]['column'];
                 $order = $data['order'][0]['dir'];


          $id_almacen = $data['id_almacen'];           
          $id_usuario = $data['id_usuario'];   

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

                      //m.id_fac_orig,tp.tipo_pedido, tf_manual.tipo_factura
                    //$this->db->select('p.nombre comprador , m.id_apartado');  
          //$this->db->select("m.consecutivo_traspaso");  
          //$this->db->select("m.id_apartado apartado");  
          //$this->db->select('m.mov_salida', FALSE);
          //
          //
          //
          //$this->db->select('m.id_usuario_apartado, m.id_cliente_apartado, ');  //fecha falta

          $this->db->select("SQL_CALC_FOUND_ROWS(m.codigo)"); //, FALSE
          $this->db->select('m.codigo,m.id_descripcion, m.id_lote,m.precio,  m.fecha_apartado, m.consecutivo, m.num_partida, m.id_estatus, m.cantidad_um');  
          $this->db->select('CONCAT(u_manual.nombre,"  ",u_manual.apellidos) as vendedor_manual', FALSE);
          $this->db->select('pr_manual.nombre as dependencia_manual');
          $this->db->select('(m.precio*m.cantidad_um) as subtotal');           
          $this->db->select("((m.precio*m.cantidad_um*m.iva))/100 as sum_iva");
          $this->db->select('c.hexadecimal_color,c.color nombre_color, m.ancho, um.medida,m.comentario_traspaso');
          $this->db->select("( CASE WHEN m.id_medida = 1 THEN m.cantidad_um ELSE 0 END ) AS metros");
          $this->db->select("( CASE WHEN m.id_medida = 2 THEN m.cantidad_um ELSE 0 END ) AS kilogramos");
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
          $this->db->select("m.id_factura, m.id_factura_original,m.id_tipo_factura, m.id_tipo_pedido");
          $this->db->select("tf_manual.tipo_factura tipo_factura_manual");    
          $this->db->select("prod.codigo_contable");  


          $this->db->from($this->registros_entradas.' as m');
          $this->db->join($this->almacenes.' As a' , 'a.id = m.id_almacen AND a.activo=1');
          $this->db->join($this->productos.' As prod' , 'prod.referencia = m.referencia','LEFT');
          $this->db->join($this->proveedores.' As p' , 'p.id = m.id_cliente_apartado','LEFT');
          $this->db->join($this->unidades_medidas.' As um' , 'um.id = m.id_medida','LEFT');
          $this->db->join($this->colores.' As c', 'm.id_color = c.id','LEFT');          
          $this->db->join($this->tipos_pedidos.' As tp' , 'tp.id = m.id_tipo_pedido','LEFT');
          $this->db->join($this->tipos_facturas.' As tf' , 'tf.id = m.id_tipo_factura','LEFT');
          $this->db->join($this->tipos_facturas.' As tff' , 'tff.id = m.id_factura','LEFT');
          $this->db->join($this->tipos_facturas.' As tf_manual' , 'tf_manual.id = m.id_factura','LEFT');
          $this->db->join($this->usuarios.' As u_manual' , 'u_manual.id = m.id_usuario_traspaso','LEFT');
          $this->db->join($this->proveedores.' As pr_manual', 'u_manual.id_cliente = pr_manual.id','LEFT');


          //filtro de busqueda
          if ($id_almacen!=0) {
              $id_almacenid = ' AND ( m.id_almacen =  '.$id_almacen.' ) ';  
          } else {
              $id_almacenid = '';
          }          

          //filtro de los pedidos que tienen traspasos
          $filtro = '( m.proceso_traspaso = 1 )  AND ( m.id_usuario_traspaso = "'.$id_usuario.'" )';  

                              



          $where = '( 
                      '.$filtro.$id_almacenid.' 
                       AND          
                      (
                        ( CONCAT(m.cantidad_um," ",um.medida) LIKE  "%'.$cadena.'%" ) OR (CONCAT(m.ancho," cm") LIKE  "%'.$cadena.'%")  OR
                        ( m.codigo LIKE  "%'.$cadena.'%" ) OR (m.id_descripcion LIKE  "%'.$cadena.'%") OR (c.color LIKE  "%'.$cadena.'%")  OR
                         (CONCAT(m.id_lote,"-",m.consecutivo) LIKE  "%'.$cadena.'%") OR 
                         (m.precio LIKE  "%'.$cadena.'%")
                       )  AND ( m.id_factura =  '.$data["id_factura"].' )
            )';   

          $this->db->where($where);
          $where_total = $filtro.$id_almacenid; 
          $this->db->order_by($columna, $order); 

          //paginacion
          $this->db->limit($largo,$inicio); 
          $result = $this->db->get();

              if ( $result->num_rows() > 0 ) {

                    $cantidad_consulta = $this->db->query("SELECT FOUND_ROWS() as cantidad");
                    $found_rows = $cantidad_consulta->row(); 
                    $registros_filtrados =  ( (int) $found_rows->cantidad);

                  foreach ($result->result() as $row) {

                            $tipo_apartado = $row->tipo_apartado;
                            $color_apartado = $row->color_apartado;
                            $mi_fecha = date('Y-m-d');  //$mi_fecha = date('Y-m-d H:i:s');  
                            $mi_hora = date( 'h:ia', strtotime($row->fecha_apartado));

                            $dato[]= array(
                                      0=>$row->codigo,
                                      1=>$row->id_descripcion,
                                      2=>
                                      $row->nombre_color.'<div style="margin-right: 15px;float:left;background-color:#'.$row->hexadecimal_color.';width:15px;height:15px;"></div>',
                                      3=>$row->cantidad_um.' '.$row->medida, //metros,
                                      4=>$row->ancho.' cm',
                                      5=>number_format($row->subtotal, 2, '.', ','), 
                                      6=>number_format($row->sum_iva, 2, '.', ','), 
                                      7=>$row->id_lote.'-'.$row->consecutivo,         
                                      8=>$row->num_partida,
                                      9=>$row->almacen,
                                      
                                      10=>$row->id_factura,
                                      11=>$row->id_tipo_factura,
                                      12=>$row->id_tipo_pedido,
                                      13=>$row->tipo_factura_manual,  
                                      14=>$row->id_factura_original,
                                      15=>$row->codigo_contable,        
                                      16=>$row->metros,
                                      17=>$row->kilogramos,                         
                                      18=>number_format($row->precio, 2, '.', ','), 
                                      19=>$row->id_estatus,                               
                                    );

                            ///////////////////////////////
                              
                              
                              $traspaso=$row->tipo_factura_manual;
                              $responsable =$row->vendedor_manual; //responsable
                              $dependencia = $row->dependencia_manual;//dependencia a la cual pertenece responsable que aparto  
                              $almacen = $row->almacen;
                              $proceso = "manual"; 
                              $motivos = $row->comentario_traspaso;

                      }

                      return json_encode ( array(
                        "draw"            => intval( $data['draw'] ),
                        "recordsTotal"    => $registros_filtrados, 
                        "recordsFiltered" => $registros_filtrados, 
                        "data"            =>  $dato, 
                        "datos"            =>  array(
                              
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




 public function imprimir_detalle_general_traspaso_manual($data){

          

          $id_almacen = $data['id_almacen'];           
          $id_usuario = $data['id_usuario'];   


          $id_session = $this->db->escape($this->session->userdata('id'));

          $this->db->select('m.id_usuario_apartado, m.id_cliente_apartado, m.num_partida');  //fecha falta

          $this->db->select('CONCAT(u_manual.nombre,"  ",u_manual.apellidos) as vendedor_manual', FALSE);
          $this->db->select('pr_manual.nombre as dependencia_manual', FALSE);

          $this->db->select('m.codigo,m.id_descripcion, m.id_lote,m.precio,m.iva, m.fecha_apartado, m.consecutivo');  
          $this->db->select('c.hexadecimal_color,c.color nombre_color, m.ancho, um.medida,m.comentario_traspaso,m.cantidad_um');
          
          $this->db->select("( CASE WHEN m.id_medida = 1 THEN m.cantidad_um ELSE 0 END ) AS metros", FALSE);
          $this->db->select("( CASE WHEN m.id_medida = 2 THEN m.cantidad_um ELSE 0 END ) AS kilogramos", FALSE);

          $this->db->select('(m.precio*m.cantidad_um) as subtotal'); 

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
          
          $this->db->select("m.id_factura,m.id_fac_orig, m.id_factura_original,m.id_tipo_factura, m.id_tipo_pedido");
          $this->db->select("tp.tipo_pedido");          
          
          $this->db->select("tf_manual.tipo_factura tipo_factura_manual");          

          $this->db->select("m.id_apartado apartado");  

          $this->db->select("prod.codigo_contable");  

          $this->db->from($this->registros_entradas.' as m');
          $this->db->join($this->almacenes.' As a' , 'a.id = m.id_almacen AND a.activo=1');
          $this->db->join($this->productos.' As prod' , 'prod.referencia = m.referencia','LEFT');
          $this->db->join($this->proveedores.' As p' , 'p.id = m.id_cliente_apartado','LEFT');
          $this->db->join($this->unidades_medidas.' As um' , 'um.id = m.id_medida','LEFT');
          $this->db->join($this->colores.' As c', 'm.id_color = c.id','LEFT');
          $this->db->join($this->tipos_pedidos.' As tp' , 'tp.id = m.id_tipo_pedido','LEFT');
          $this->db->join($this->tipos_facturas.' As tf' , 'tf.id = m.id_tipo_factura','LEFT');
          $this->db->join($this->tipos_facturas.' As tff' , 'tff.id = m.id_factura','LEFT');
          $this->db->join($this->tipos_facturas.' As tf_manual' , 'tf_manual.id = m.id_factura','LEFT');
          $this->db->join($this->usuarios.' As u_manual' , 'u_manual.id = m.id_usuario_traspaso','LEFT');
          $this->db->join($this->proveedores.' As pr_manual', 'u_manual.id_cliente = pr_manual.id','LEFT');


          //filtro de busqueda
          if ($id_almacen!=0) {
              $id_almacenid = ' AND ( m.id_almacen =  '.$id_almacen.' ) ';  
          } else {
              $id_almacenid = '';
          }          

          $filtro = '( m.proceso_traspaso = 1 )  AND ( m.id_usuario_traspaso = "'.$id_usuario.'" )';  

          $where = '( ( m.id_factura =  '.$data["id_factura"].' ) AND
                      '.$filtro.$id_almacenid.' 
            )';   



          $this->db->where($where);
          $where_total = $filtro.$id_almacenid; 
          //$this->db->order_by($columna, $order); 

            $result = $this->db->get();
          
            if ( $result->num_rows() > 0 )
               return $result->result();
            else
               return False;
            $result->free_result();
  
      }  





public function total_imprimir_detalle_general_traspaso_manual($data){


          if ($data['id_almacen']!=0) {
              $id_almacenid = ' AND ( m.id_almacen =  '.$data['id_almacen'].' ) ';  
          } else {
              $id_almacenid = '';
          }          

          $filtro = '( m.proceso_traspaso = 1 )  AND ( m.id_usuario_traspaso = "'.$data['id_usuario'].'" )';  

          $where = '( ( m.id_factura =  '.$data["id_factura"].' ) AND
                      '.$filtro.$id_almacenid.' 
          )';   




           $this->db->select("SUM(m.precio*m.cantidad_um) as subtotal", FALSE);
           $this->db->select("(SUM(m.precio*m.cantidad_um*m.iva))/100 as iva", FALSE);
           $this->db->select("SUM(m.precio*m.cantidad_um)+(SUM(m.precio*m.cantidad_um*m.iva))/100 as total", FALSE);
           $this->db->select("SUM((id_medida =1) * cantidad_um) as metros", FALSE);
           $this->db->select("SUM((id_medida =2) * cantidad_um) as kilogramos", FALSE);
           $this->db->select("COUNT(m.id_medida) as 'pieza'");
 
   
          $this->db->from($this->registros_entradas.' as m');
          $this->db->where($where);
    
          
          $result = $this->db->get();
      
          if ( $result->num_rows() > 0 )
             return $result->row();
          else
             return False;
          $result->free_result();              

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

          $this->db->select('m.codigo,m.id_descripcion, m.id_lote,m.precio,m.iva,  m.consecutivo');  
          $this->db->select("(DATE_FORMAT(m.fecha_apartado,'%d-%m-%Y')) as fecha_apartado",false);
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
          
          $this->db->select("m.id_factura,m.id_fac_orig, m.id_factura_original,m.id_tipo_factura, ,m.id_tipo_pedido");
          $this->db->select("tp.tipo_pedido");          
          $this->db->select("tf.tipo_factura");  
          $this->db->select("tff.tipo_factura t_factura");  
          $this->db->select("m.id_apartado apartado");  

          $this->db->select("m.consecutivo_venta consecutivo_venta");  

          
          $this->db->select("prod.codigo_contable");  

          $this->db->from($this->registros_entradas.' as m');
          $this->db->join($this->almacenes.' As a' , 'a.id = m.id_almacen AND a.activo=1');
          $this->db->join($this->productos.' As prod' , 'prod.referencia = m.referencia','LEFT');
          $this->db->join($this->usuarios.' As u' , 'u.id = m.id_usuario_apartado','LEFT');
          $this->db->join($this->proveedores.' As pr', 'u.id_cliente = pr.id','LEFT');
          $this->db->join($this->proveedores.' As p' , 'p.id = m.id_cliente_apartado','LEFT');
          $this->db->join($this->unidades_medidas.' As um' , 'um.id = m.id_medida','LEFT');
          $this->db->join($this->colores.' As c', 'm.id_color = c.id','LEFT');
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
                      ( ( m.id_factura =  '.$data["id_factura"].' ) AND
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

          $this->db->select('(m.precio*m.cantidad_um) as subtotal');

          $this->db->select('m.codigo,m.id_descripcion, m.id_lote,m.precio, m.iva, m.fecha_apartado, m.consecutivo');  
          $this->db->select('c.hexadecimal_color,c.color nombre_color, m.ancho, um.medida,m.cantidad_um,comentario_traspaso');
          
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
                           ELSE "(Salida Total)"
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
          
          $this->db->select("m.id_factura,m.id_fac_orig, m.id_factura_original,m.id_tipo_factura,m.consecutivo_venta ,m.id_tipo_pedido");
          $this->db->select("tp.tipo_pedido");          
          $this->db->select("tf.tipo_factura");  
          $this->db->select("tff.tipo_factura t_factura");  

          $this->db->select("m.consecutivo_traspaso");  
          $this->db->select("m.id_apartado apartado");  
          $this->db->select('m.mov_salida', FALSE); 
          $this->db->select("prod.codigo_contable");  


          $this->db->from($this->historico_registros_traspasos.' as m');
          $this->db->join($this->almacenes.' As a' , 'a.id = m.id_almacen AND a.activo=1');
          $this->db->join($this->productos.' As prod' , 'prod.referencia = m.referencia','LEFT');
          $this->db->join($this->usuarios.' As u' , 'u.id = m.id_usuario_apartado','LEFT');
          $this->db->join($this->proveedores.' As pr', 'u.id_cliente = pr.id','LEFT');
          $this->db->join($this->proveedores.' As p' , 'p.id = m.id_cliente_apartado','LEFT');
          $this->db->join($this->unidades_medidas.' As um' , 'um.id = m.id_medida','LEFT');
          $this->db->join($this->colores.' As c', 'm.id_color = c.id','LEFT');
          $this->db->join($this->tipos_pedidos.' As tp' , 'tp.id = m.id_tipo_pedido','LEFT');
          $this->db->join($this->tipos_facturas.' As tf' , 'tf.id = m.id_tipo_factura','LEFT');
          $this->db->join($this->tipos_facturas.' As tff' , 'tff.id = m.id_factura','LEFT');

          //filtro de busqueda

          $where = '(
                      ( ( m.id_factura =  '.$data["id_factura"].' ) AND
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






public function totales_imprimir_traspaso_historico_detalle($data){

          $where = '(
                      ( ( m.id_factura =  '.$data["id_factura"].' ) AND
                        ( m.consecutivo_traspaso =  '.$data['consecutivo_traspaso'].' )
                      )
            )';   

           $this->db->select("SUM(m.precio*m.cantidad_um) as subtotal", FALSE);
           $this->db->select("(SUM(m.precio*m.cantidad_um*m.iva))/100 as iva", FALSE);
           $this->db->select("SUM(m.precio*m.cantidad_um)+(SUM(m.precio*m.cantidad_um*m.iva))/100 as total", FALSE);
           $this->db->select("SUM((id_medida =1) * cantidad_um) as metros", FALSE);
           $this->db->select("SUM((id_medida =2) * cantidad_um) as kilogramos", FALSE);
           $this->db->select("COUNT(m.id_medida) as 'pieza'");
 
   
           $this->db->from($this->historico_registros_traspasos.' as m');
          $this->db->where($where);
    
          
          $result = $this->db->get();
      
          if ( $result->num_rows() > 0 )
             return $result->row();
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


 /*
ALTER TABLE  `inven_historico_registros_traspasos` ADD 
 `id_almacen_traspaso` INT NOT NULL ,
ADD  `comentario_traspaso` TEXT NOT NULL ,
ADD  `num_control` VARCHAR( 30 ) NOT NULL ;
 */

        public function establecer_productos_traspasado( $data ){
                
                $id_almacen= $data['id_almacen'];
                
                if ($data['id_tipo_factura']==1){
                    $porciento_aplicar =16;  
                } else {
                     $porciento_aplicar = 0;  
                }

                $id_session = $this->session->userdata('id');
                //$this->db->set( 'fecha_pc',  gmt_to_local( $timestamp, $this->timezone, TRUE) );
            
                
                if  (isset($data['factura'])) {
                  $this->db->set( 'num_control', '"'.$data['factura'].'"', false);
                }  
                $this->db->set( 'comentario_traspaso', '"'.$data['comentario'].'"', false);
                $this->db->set( 'id_usuario_traspaso', $id_session );




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
                        $columna = 'm.precio';
                     break;
                   case '6':
                        $columna = 'm.iva';
                     break;                     

                   case '7':
                        $columna = 'm.movimiento';
                     break;
                   case '8':
                              $columna= 'p.nombre';
                     break;
                   case '9':
                              $columna= 'm.id_lote, m.consecutivo';  
                     break;
                   case '10':
                              $columna= 'm.num_partida';  
                     break;

                   default:
                       $columna = 'm.codigo';
                     break;
                 }                 
 
          $id_session = $this->db->escape($this->session->userdata('id'));


          //m.id_empresa,,m.id_factura ,  m.factura,, m.id_operacion, , m.referencia
          //$this->db->select('m.id_color, m.id_composicion, m.id_calidad');
          //m.id_medida, m.cantidad_royo,m.iva,, m.comentario
          
          $this->db->select("SQL_CALC_FOUND_ROWS(m.id)"); // , FALSE
          $this->db->select('m.id, m.movimiento,m.id_fac_orig, m.id_descripcion,m.devolucion, m.num_partida');
          $this->db->select('m.cantidad_um,  m.ancho, m.precio, m.codigo,m.id_estatus');
          $this->db->select('m.id_estatus, m.id_lote, m.consecutivo, m.id_cargador, m.id_usuario, m.fecha_mac fecha');
          $this->db->select('c.hexadecimal_color, c.color, u.medida,p.nombre, m.id_apartado');
          $this->db->select("( CASE WHEN m.id_medida = 1 THEN m.cantidad_um ELSE 0 END ) AS metros");
          $this->db->select("( CASE WHEN m.id_medida = 2 THEN m.cantidad_um ELSE 0 END ) AS kilogramos");
          $this->db->select('(m.precio*m.cantidad_um) as subtotal');           
          $this->db->select("((m.precio*m.cantidad_um*m.iva))/100 as sum_iva"); //, FALSE
          $this->db->select("(m.precio*m.cantidad_um)+((m.precio*m.cantidad_um*m.iva))/100 as sum_total"); //, FALSE
          $this->db->select("prod.codigo_contable");  

          $this->db->from($this->registros.' as m');
          $this->db->join($this->productos.' As prod' , 'prod.referencia = m.referencia','LEFT'); //
          $this->db->join($this->colores.' As c' , 'c.id = m.id_color','LEFT'); //,'LEFT'
          $this->db->join($this->unidades_medidas.' As u' , 'u.id = m.id_medida','LEFT'); //,'LEFT'
          $this->db->join($this->proveedores.' As p' , 'p.id = m.id_empresa','LEFT'); //,'LEFT'
          $this->db->join($this->usuarios.' As us' , 'us.id = m.id_usuario_apartado','LEFT'); //,'LEFT'
         
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
                                      5=>number_format($row->subtotal, 2, '.', ','),
                                      6=>number_format($row->sum_iva, 2, '.', ','),
                                      7=>
                                           '<a style="  padding: 1px 0px 1px 0px;" href="'.base_url().'procesar_entradas/'.base64_encode($row->movimiento).'/'.base64_encode($row->devolucion).'/'.base64_encode($retorno).'/'.base64_encode($row->id_fac_orig).'/'.base64_encode($row->id_estatus).'"
                                               type="button" class="btn btn-success btn-block">'.$row->movimiento.'</a>', 
                                      8=>$row->nombre,
                                      9=>$row->id_lote.'-'.$row->consecutivo,
                                      10=>$row->id,
                                      11=>$row->id_apartado,
                                      12=>$row->num_partida,
                                      13=>$row->metros,
                                      14=>$row->kilogramos,
                                      15=>$row->sum_iva,
                                      16=>$row->sum_total,
                                      17=>$row->codigo_contable,                                         
                                      18=>number_format($row->precio, 2, '.', ','), 
                                      19=>$row->id_estatus,                    
                                    );
                      }



                      return json_encode ( array(
                        "draw"            => intval( $data['draw'] ),
                        "recordsTotal"    => $registros_filtrados,  
                        "recordsFiltered" => $registros_filtrados, 
                        "data"            =>  $dato,
                        "totales"            =>  array("pieza"=>intval( self::total_campos_salida_home($where_total)->pieza ), "metro"=>floatval( self::total_campos_salida_home($where_total)->metros ), "kilogramo"=>floatval( self::total_campos_salida_home($where_total)->kilogramos )),  
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
                   "totales"            =>  array("pieza"=>intval( self::total_campos_salida_home($where_total)->pieza ), "metro"=>floatval( self::total_campos_salida_home($where_total)->metros ), "kilogramo"=>floatval( self::total_campos_salida_home($where_total)->kilogramos )),  
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
      

  public function total_campos_salida_home($where) {

              $this->db->select("SUM((id_medida =1) * cantidad_um) as metros"); //, FALSE
              $this->db->select("SUM((id_medida =2) * cantidad_um) as kilogramos"); //, FALSE
              $this->db->select("COUNT(m.id_medida) as 'pieza'");
              
             
              $this->db->from($this->registros.' as m');
              $this->db->join($this->usuarios.' As us' , 'us.id = m.id_usuario_apartado','LEFT'); //,'LEFT'

              $this->db->where($where);

             $result = $this->db->get();
          
              if ( $result->num_rows() > 0 )
                 return $result->row();
              else
                 return False;
              $result->free_result();              

       }  

   






public function valores_movimientos_temporal(){

          $id_session = $this->session->userdata('id');
          
          $this->db->distinct();          
          $this->db->select('m.id, m.id_usuario_traspaso, m.id_almacen, m.comentario_traspaso comentario,m.num_control factura');
           $this->db->select('m.id_factura,m.id_fac_orig');
          
          $this->db->from($this->registros.' as m');

          $this->db->where('m.id_usuario_traspaso',$id_session);
          $this->db->where('proceso_traspaso',1);
           $result = $this->db->get();
        
            if ( $result->num_rows() > 0 )
               return $result->row();
            else
               return False;
            $result->free_result();
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
                        $columna = 'm.precio';
                     break;
                   case '6':
                        $columna = 'm.iva';
                     break;                     

                   case '7':
                        $columna = 'm.movimiento';
                     break;
                   case '8':
                              $columna= 'p.nombre';
                     break;
                   case '9':
                              $columna= 'm.id_lote, m.consecutivo';  
                     break;
                   case '10':
                              $columna= 'm.num_partida';  
                     break;

                   default:
                       $columna = 'm.codigo';
                     break;
                 }                
          

          $id_session = $this->db->escape($this->session->userdata('id'));

          //$this->db->select('m.id_color, m.id_composicion, m.id_calidad, m.referencia');
          //m.id,m.id_empresa,m.factura, m.id_factura,m.id_factura_original,m.id_operacion,m.id_medida,
          //m.cantidad_royo,m.iva,, m.comentario,  m.id_cargador,m.id_usuario, m.fecha_mac fecha
          //$this->db->select("num_control, comentario_traspaso, id_usuario_traspaso", FALSE);

          $this->db->select("SQL_CALC_FOUND_ROWS(m.id)"); //
          $this->db->select(' m.id, m.movimiento, m.id_fac_orig, m.id_descripcion, m.devolucion, m.num_partida');
          $this->db->select('m.cantidad_um,  m.ancho, m.precio, m.codigo');
          $this->db->select('m.id_estatus, m.id_lote, m.consecutivo');
          $this->db->select('c.hexadecimal_color, c.color, u.medida,p.nombre, m.id_apartado');
          $this->db->select("( CASE WHEN m.id_medida = 1 THEN m.cantidad_um ELSE 0 END ) AS metros"); //, FALSE
          $this->db->select("( CASE WHEN m.id_medida = 2 THEN m.cantidad_um ELSE 0 END ) AS kilogramos"); //, FALSE
          $this->db->select('(m.precio*m.cantidad_um) as subtotal');           
          $this->db->select("((m.precio*m.cantidad_um*m.iva))/100 as sum_iva");
          $this->db->select("(m.precio*m.cantidad_um)+((m.precio*m.cantidad_um*m.iva))/100 as sum_total");                
          $this->db->select("prod.codigo_contable");  

          $this->db->from($this->registros.' as m');
          $this->db->join($this->productos.' As prod' , 'prod.referencia = m.referencia','LEFT');          
          $this->db->join($this->colores.' As c' , 'c.id = m.id_color','LEFT');
          $this->db->join($this->unidades_medidas.' As u' , 'u.id = m.id_medida','LEFT');
          $this->db->join($this->proveedores.' As p' , 'p.id = m.id_empresa','LEFT');
          $this->db->join($this->usuarios.' As us' , 'us.id = m.id_usuario_apartado','LEFT');
         
          //filtro de busqueda



        $donde1 = '';
        $donde = '';

        $id_tipo_facturaid = ' AND ( m.id_factura =  '.$id_tipo_factura.' ) AND ( ( incluir =  1 ) AND (proceso_traspaso = 1)) ';      
                           

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

                  $retorno= "traspasos";  
                  foreach ($result->result() as $row) {
                            $dato[]= array(
                                      0=>$row->codigo,
                                      1=>$row->id_descripcion,
                                      2=>$row->color.
                                        '<div style="background-color:#'.$row->hexadecimal_color.';display:block;width:15px;height:15px;margin:0 auto;"></div>',
                                      3=>$row->cantidad_um.' '.$row->medida,
                                      4=>$row->ancho.' cm',
                                      
                                      5=>number_format($row->subtotal, 2, '.', ','),
                                      6=>number_format($row->sum_iva, 2, '.', ','),
                                      7=>
                                           '<a style="  padding: 1px 0px 1px 0px;" href="'.base_url().'procesar_entradas/'.base64_encode($row->movimiento).'/'.base64_encode($row->devolucion).'/'.base64_encode($retorno).'/'.base64_encode($row->id_fac_orig).'/'.base64_encode($row->id_estatus).'"
                                               type="button" class="btn btn-success btn-block">'.$row->movimiento.'</a>', 
                                      8=>$row->nombre,
                                      9=>$row->id_lote.'-'.$row->consecutivo,
                                      10=>$row->id,
                                      11=>$row->id_apartado,
                                      12=>$row->num_partida,
                                      13=>$row->metros,
                                      14=>$row->kilogramos,
                                      15=>$row->sum_iva,
                                      16=>$row->sum_total,                                      
                                      17=>$row->codigo_contable,
                                      18=>number_format($row->precio, 2, '.', ','),
                                      19=>$row->id_estatus,
                                    );
                      }



                      return json_encode ( array(
                        "draw"            => intval( $data['draw'] ),
                        "recordsTotal"    => $registros_filtrados,  
                        "recordsFiltered" => $registros_filtrados, 
                        "data"            =>  $dato,
                        "totales"            =>  array("pieza"=>intval( self::total_campos_salida_home($where_total)->pieza ), "metro"=>floatval( self::total_campos_salida_home($where_total)->metros ), "kilogramo"=>floatval( self::total_campos_salida_home($where_total)->kilogramos )),  
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
                   "totales"            =>  array("pieza"=>intval( self::total_campos_salida_home($where_total)->pieza ), "metro"=>floatval( self::total_campos_salida_home($where_total)->metros ), "kilogramo"=>floatval( self::total_campos_salida_home($where_total)->kilogramos )),  
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

           $this->db->select("SUM(m.precio*m.cantidad_um) as subtotal"); //, FALSE
           $this->db->select("(SUM(m.precio*m.cantidad_um*m.iva))/100 as iva"); //, FALSE
           $this->db->select("SUM(m.precio*m.cantidad_um)+(SUM(m.precio*m.cantidad_um*m.iva))/100 as total"); //, FALSE
   
          $this->db->from($this->registros.' as m');
          $this->db->where($where);


          $result = $this->db->get();
      
          if ( $result->num_rows() > 0 )
             return $result->row();
          else
             return False;
          $result->free_result();              

    }  

    public function quitar_productos_traspasado( $data ){
                $id_almacen= $data['id_almacen'];
                $porciento_aplicar = 16;                 

                
                $this->db->set( 'num_control', '');
                $this->db->set( 'comentario_traspaso', '');
                $this->db->set( 'id_usuario_traspaso', '');


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

          public function total_registros_traspaso(){

              $id_session = $this->session->userdata('id');
              $this->db->from($this->registros.' as m');
              $this->db->where('m.id_usuario_traspaso',$id_session);
              $this->db->where('proceso_traspaso',1);


              $cant = $this->db->count_all_results();          
     
              if ( $cant > 0 )
                 return $cant;
              else
                 return 0;         
          }




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


      public function procesando_traspaso_definitivo( $data ){
             $id_session = $this->session->userdata('id');
             $fecha_hoy = date('Y-m-d H:i:s');

             $this->db->select('"'.$id_session.'" AS id_usuario',false); 

             $this->db->select('"'.addslashes($data['consecutivo']).'" AS consecutivo_traspaso',false); 
             $this->db->select('"'.$id_session.'" AS id_usuario_apartado',false); 
             $this->db->select('"'.$fecha_hoy.'" AS fecha_apartado',false); 
             $this->db->select('"1" AS id_tipo_pedido',false); 
             $this->db->select('id_factura AS id_tipo_factura',false); 

             

             $this->db->select('id_operacion,id id_entrada, id_almacen, factura,  id_cargador, fecha_salida,   movimiento, id_empresa, id_descripcion, id_color, devolucion, num_partida');
             $this->db->select('id_composicion, id_calidad, referencia, id_medida, cantidad_um, cantidad_royo, ancho');
             $this->db->select('codigo, comentario, id_estatus, id_lote, consecutivo');
             $this->db->select('fecha_entrada,estatus_salida');
             $this->db->select('id_apartado, id_cliente_apartado, consecutivo_venta');
             $this->db->select('precio, iva, id_pedido, id_factura,id_fac_orig, id_factura_original,incluir');
             
             $this->db->select('id_usuario_traspaso, proceso_traspaso,comentario_traspaso,num_control');

             $this->db->from($this->registros);
             $this->db->where('id_usuario_traspaso',$id_session);
             $this->db->where('proceso_traspaso',1);

             $result = $this->db->get();
             $objeto = $result->result();

             //copiar a tabla "registros"
             foreach ($objeto as $key => $value) {
               $this->db->insert($this->historico_registros_traspasos, $value); 
             }

              //$this->db->set( 'consecutivo', 'consecutivo+1', FALSE  );

              if ($data['id_factura']==1) {
                  $this->db->set( 'conse_factura', 'conse_factura+1', FALSE  );  
              } else {
                  $this->db->set( 'conse_remision', 'conse_remision+1', FALSE  );  
              }

              $this->db->set( 'id_usuario', $id_session );
              $this->db->where('id',26);
              $this->db->update($this->operaciones);  

             return TRUE;
      }

       public function eliminar_traspaso_definitivo( $data ){ 

                $id_session = $this->session->userdata('id');

                $this->db->set( 'num_control', '');
                $this->db->set( 'comentario_traspaso', '');
                $this->db->set( 'proceso_traspaso', 0);
                $this->db->set( 'incluir', 0);
                $this->db->set( 'id_factura_original', 0, false);
                $this->db->set( 'id_tipo_factura', 0, false);
                $this->db->set( 'id_tipo_pedido', 0, false);
                $this->db->set( 'id_pedido', 0, false);
                $this->db->set( 'id_usuario_traspaso', '');

                $this->db->where('id_usuario_traspaso',$id_session);
                $this->db->where('proceso_traspaso',1);

                $this->db->update($this->registros);  

       }



///////////////////////////////////impresion del resumen/////////////////////////////////////////

  public function imprimir_traspaso_historico($data){

          
          $cadena = addslashes($data['busqueda']);
          $id_almacen= $data['id_almacen'];
          $id_session = $this->db->escape($this->session->userdata('id'));

          $this->db->select("SQL_CALC_FOUND_ROWS *", FALSE); //

          $this->db->select('m.id_usuario_apartado, m.id_cliente_apartado,consecutivo_venta, m.comentario_traspaso');  

          $this->db->select("(DATE_FORMAT(m.fecha_apartado,'%d-%m-%Y')) as fecha_apartado",false);

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


          $this->db->select("SUM((m.id_medida =1) * m.cantidad_um) as metros", FALSE);
          $this->db->select("SUM((m.id_medida =2) * m.cantidad_um) as kilogramos", FALSE);
          $this->db->select("COUNT(m.id_medida) as 'pieza'");
          
          $this->db->select("SUM(m.precio*m.cantidad_um) as subtotal", FALSE);
          $this->db->select("(SUM(m.precio*m.cantidad_um*m.iva))/100 as iva", FALSE);
          $this->db->select("SUM(m.precio*m.cantidad_um)+(SUM(m.precio*m.cantidad_um*m.iva))/100 as total", FALSE);



           
          
          $this->db->from($this->historico_registros_traspasos.' as m');          
          $this->db->join($this->almacenes.' As a' , 'a.id = m.id_almacen AND a.activo=1');
          $this->db->join($this->usuarios.' As u' , 'u.id = m.id_usuario_apartado','LEFT');
          $this->db->join($this->proveedores.' As pr', 'u.id_cliente = pr.id','LEFT');
          $this->db->join($this->proveedores.' As p' , 'p.id = m.id_cliente_apartado','LEFT');          
          $this->db->join($this->tipos_pedidos.' As tp' , 'tp.id = m.id_tipo_pedido','LEFT');
          $this->db->join($this->tipos_facturas.' As tf' , 'tf.id = m.id_tipo_factura','LEFT');


          if ($id_almacen!=0) {
              $id_almacenid = ' AND ( m.id_almacen =  '.$id_almacen.' ) ';  
          } else {
              $id_almacenid = '';
          }          

         if ($data['id_factura']!=0) {
              $id_facturaid = ' AND ( m.id_factura =  '.$data['id_factura'].' ) ';  
          } else {
              $id_facturaid = '';
          }          

          
          if  ( ($data['fecha_inicial'] !="") and  ($data['fecha_final'] !="")) {
                           $fecha_inicial = date( 'Y-m-d', strtotime( $data['fecha_inicial'] ));
                           $fecha_final = date( 'Y-m-d', strtotime( $data['fecha_final'] ));
                          
                          
                            $fechas = ' AND ( ( DATE_FORMAT((m.fecha_apartado),"%Y-%m-%d")  >=  "'.$fecha_inicial.'" )  AND  ( DATE_FORMAT((m.fecha_apartado),"%Y-%m-%d")  <=  "'.$fecha_final.'" ) )'; 
                          

          } else {
           $fechas = '';
          }          

          //filtro de los pedidos que tienen traspasos
          $filtro = ' (  ( m.incluir <> 0 ) ) ';   //( m.id_tipo_factura <> 0 ) AND
          $where = '(
                      ('.$filtro.$id_almacenid.$id_facturaid.$fechas.') 
                       AND
                      (
                        ( CONCAT(u.nombre," ",u.apellidos) LIKE  "%'.$cadena.'%" ) OR
                        ( pr.nombre LIKE  "%'.$cadena.'%" ) OR (p.nombre LIKE  "%'.$cadena.'%") OR
                        (m.id_cliente_apartado LIKE  "%'.$cadena.'%") OR 
                        ((DATE_FORMAT((m.fecha_apartado),"%d-%m-%Y") ) LIKE  "%'.$cadena.'%") OR
                        
                        ( "Salida Parcial" LIKE  "%'.$cadena.'%" ) OR
                        ( "Salida Total" LIKE  "%'.$cadena.'%" ) OR
                        ( "(Vendedor)" LIKE  "%'.$cadena.'%" ) OR
                        ( "(Tienda)" LIKE  "%'.$cadena.'%" ) OR
                        ( m.codigo LIKE  "%'.$cadena.'%" ) 
                       ) 

            )';            


          $this->db->where($where);
          
          $where_total = '('.$filtro.$id_almacenid.$id_facturaid.$fechas.')'; 
          //$this->db->order_by($columna, $order); 
          
          $this->db->group_by("m.consecutivo_traspaso,m.id_factura, m.id_usuario_apartado, m.id_cliente_apartado,m.consecutivo_venta");
            $result = $this->db->get();


            if ( $result->num_rows() > 0 )
               return $result->result();
            else
               return False;
            $result->free_result();             
      }  






public function totales_imprimir_traspaso_historico($data){

           $cadena = addslashes($data['busqueda']);
          $id_almacen= $data['id_almacen'];
          $id_session = $this->db->escape($this->session->userdata('id'));


    if ($id_almacen!=0) {
              $id_almacenid = ' AND ( m.id_almacen =  '.$id_almacen.' ) ';  
          } else {
              $id_almacenid = '';
          }          

         if ($data['id_factura']!=0) {
              $id_facturaid = ' AND ( m.id_factura =  '.$data['id_factura'].' ) ';  
          } else {
              $id_facturaid = '';
          }          

          
          if  ( ($data['fecha_inicial'] !="") and  ($data['fecha_final'] !="")) {
                           $fecha_inicial = date( 'Y-m-d', strtotime( $data['fecha_inicial'] ));
                           $fecha_final = date( 'Y-m-d', strtotime( $data['fecha_final'] ));
                          
                          
                            $fechas = ' AND ( ( DATE_FORMAT((m.fecha_apartado),"%Y-%m-%d")  >=  "'.$fecha_inicial.'" )  AND  ( DATE_FORMAT((m.fecha_apartado),"%Y-%m-%d")  <=  "'.$fecha_final.'" ) )'; 
                          

          } else {
           $fechas = '';
          }          

          //filtro de los pedidos que tienen traspasos
          $filtro = ' (  ( m.incluir <> 0 ) ) ';   //( m.id_tipo_factura <> 0 ) AND
          $where = '(
                      ('.$filtro.$id_almacenid.$id_facturaid.$fechas.') 
                       AND
                      (
                        ( CONCAT(u.nombre," ",u.apellidos) LIKE  "%'.$cadena.'%" ) OR
                        ( pr.nombre LIKE  "%'.$cadena.'%" ) OR (p.nombre LIKE  "%'.$cadena.'%") OR
                        (m.id_cliente_apartado LIKE  "%'.$cadena.'%") OR 
                        ((DATE_FORMAT((m.fecha_apartado),"%d-%m-%Y") ) LIKE  "%'.$cadena.'%") OR
                        
                        ( "Salida Parcial" LIKE  "%'.$cadena.'%" ) OR
                        ( "Salida Total" LIKE  "%'.$cadena.'%" ) OR
                        ( "(Vendedor)" LIKE  "%'.$cadena.'%" ) OR
                        ( "(Tienda)" LIKE  "%'.$cadena.'%" ) OR
                        ( m.codigo LIKE  "%'.$cadena.'%" ) 
                       ) 

            )';  

           $this->db->select("SUM(m.precio*m.cantidad_um) as subtotal", FALSE);
           $this->db->select("(SUM(m.precio*m.cantidad_um*m.iva))/100 as iva", FALSE);
           $this->db->select("SUM(m.precio*m.cantidad_um)+(SUM(m.precio*m.cantidad_um*m.iva))/100 as total", FALSE);
           $this->db->select("SUM((id_medida =1) * cantidad_um) as metros", FALSE);
           $this->db->select("SUM((id_medida =2) * cantidad_um) as kilogramos", FALSE);
           $this->db->select("COUNT(m.id_medida) as 'pieza'");
 
     
          $this->db->from($this->historico_registros_traspasos.' as m');
          $this->db->join($this->almacenes.' As a' , 'a.id = m.id_almacen AND a.activo=1');
          $this->db->join($this->usuarios.' As u' , 'u.id = m.id_usuario_apartado','LEFT');
          $this->db->join($this->proveedores.' As pr', 'u.id_cliente = pr.id','LEFT');
          $this->db->join($this->proveedores.' As p' , 'p.id = m.id_cliente_apartado','LEFT');          
          $this->db->join($this->tipos_pedidos.' As tp' , 'tp.id = m.id_tipo_pedido','LEFT');
          $this->db->join($this->tipos_facturas.' As tf' , 'tf.id = m.id_tipo_factura','LEFT');


          $this->db->where($where);
    
          
          $result = $this->db->get();
      
          if ( $result->num_rows() > 0 )
             return $result->row();
          else
             return False;
          $result->free_result();              

    }  



  } 
?>

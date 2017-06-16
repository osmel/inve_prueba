<?php if(! defined('BASEPATH')) exit('No tienes permiso para acceder a este archivo');

	class modelo_costo_inventario extends CI_Model{
		
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
      $this->cargadores             = $this->db->dbprefix('catalogo_cargador');
      
      $this->estratificacion_empresa = $this->db->dbprefix('catalogo_estratificacion_empresa');
      
      $this->productos               = $this->db->dbprefix('catalogo_productos');
      $this->proveedores             = $this->db->dbprefix('catalogo_empresas');
      $this->unidades_medidas        = $this->db->dbprefix('catalogo_unidades_medidas');

      $this->operaciones             = $this->db->dbprefix('catalogo_operaciones');
      $this->movimientos               = $this->db->dbprefix('movimientos');
      $this->registros_temporales               = $this->db->dbprefix('temporal_registros');
      $this->registros               = $this->db->dbprefix('registros_entradas');
      

      $this->colores                 = $this->db->dbprefix('catalogo_colores');
      
      $this->historico_registros_salidas = $this->db->dbprefix('historico_registros_salidas');

      $this->registros_salidas       = $this->db->dbprefix('registros_salidas');
      
      $this->historico_registros_entradas = $this->db->dbprefix('historico_registros_entradas');
      
      
      $this->composiciones     = $this->db->dbprefix('catalogo_composicion');
      $this->calidades                 = $this->db->dbprefix('catalogo_calidad');

      $this->registros_entradas               = $this->db->dbprefix('registros_entradas');
      $this->registros_cambios               = $this->db->dbprefix('registros_cambios');
      $this->almacenes       = $this->db->dbprefix('catalogo_almacenes');
      $this->tipos_facturas                         = $this->db->dbprefix('catalogo_tipos_facturas');

      


		}
     




//////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////


      



    

      public function total_registros_entrada_historica($where){

              $this->db->select("SUM((id_medida =1) * cantidad_um) as metros"); //, FALSE
              $this->db->select("SUM((id_medida =2) * cantidad_um) as kilogramos"); //, FALSE
              $this->db->select("COUNT(m.id_medida) as 'pieza'");
              

              $this->db->from($this->historico_registros_entradas.' as m');
              //$this->db->join($this->almacenes.' As a' , 'a.id = m.id_almacen AND a.activo=1');
              $this->db->join($this->productos.' As prod' , 'prod.referencia = m.referencia'); //,'LEFT'          
              $this->db->join($this->colores.' As c' , 'c.id = m.id_color');//,'LEFT'
              $this->db->join($this->unidades_medidas.' As u' , 'u.id = m.id_medida'); //,'LEFT'
              $this->db->join($this->proveedores.' As p' , 'p.id = m.id_empresa'); //,'LEFT'
              $this->db->join($this->tipos_facturas.' As tff' , 'tff.id = m.id_factura'); //,'LEFT'


              $this->db->where($where);

             $result = $this->db->get();
          
              if ( $result->num_rows() > 0 ) {
                 return $result->row();    
              }
            
              else
                 return False;
              $result->free_result();              
       }       
                      


public function totales_importes_historica($where){

           $this->db->select("SUM(m.precio*m.cantidad_um) as subtotal"); //, FALSE
           $this->db->select("(SUM(m.precio*m.cantidad_um*m.iva))/100 as iva"); //, FALSE
           $this->db->select("SUM(m.precio*m.cantidad_um)+(SUM(m.precio*m.cantidad_um*m.iva))/100 as total"); //, FALSE
   
          
          $this->db->from($this->historico_registros_entradas.' as m');
          //$this->db->join($this->almacenes.' As a' , 'a.id = m.id_almacen AND a.activo=1');
          $this->db->join($this->productos.' As prod' , 'prod.referencia = m.referencia'); //,'LEFT'
          $this->db->join($this->colores.' As c' , 'c.id = m.id_color'); //,'LEFT'
          $this->db->join($this->unidades_medidas.' As u' , 'u.id = m.id_medida'); //,'LEFT'
          $this->db->join($this->proveedores.' As p' , 'p.id = m.id_empresa'); //,'LEFT'
          $this->db->join($this->tipos_facturas.' As tff' , 'tff.id = m.id_factura'); //,'LEFT'

          $this->db->where($where);


          $result = $this->db->get();
      
          if ( $result->num_rows() > 0 )
             return $result->row();
          else
             return False;
          $result->free_result();              

    }  

    public function buscador_entrada_historica($data){

          $cadena = addslashes($data['search']['value']);
          $inicio = $data['start'];
          $largo = $data['length'];
          $estatus= "entrada"; //$data['extra_search'];
          $id_estatus= $data['id_estatus'];
          $id_empresa= addslashes($data['proveedor']);
          $id_almacen= $data['id_almacen'];
          $id_factura= $data['id_factura'];

          $factura_reporte = $data['factura_reporte'];

          $columa_order = $data['order'][0]['column'];
                 $order = $data['order'][0]['dir'];

           if ($data['draw'] ==0) {  //que se ordene por el ultimo
                 $columa_order ='-1';
                 $order = 'desc';
           } 

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
                   case '8':
                        $columna = 'm.fecha_entrada';
                     break;

                   case '12': //'9':
                        $columna = 'm.factura';
                     break;

                   case '13':
                        $columna = 'm.num_partida';
                     break;
                     
                   case '14':
                        $columna = 'm.id_almacen';
                     break;                       

                   
                   default:
                       /*$columna = 'm.factura';
                       $order = 'asc'; */
                       $columna = 'm.id';
                       $order = 'DESC';                       

                     break;
                 }              
           

          $fechas = ' ';
          if  ( ($data['fecha_inicial'] !="") and  ($data['fecha_final'] !="")) {
                           $fecha_inicial = date( 'Y-m-d', strtotime( $data['fecha_inicial'] ));
                           $fecha_final = date( 'Y-m-d', strtotime( $data['fecha_final'] ));
                          
                            $fechas .= ' AND ( ( DATE_FORMAT((m.fecha_entrada),"%Y-%m-%d")  >=  "'.$fecha_inicial.'" )  AND  ( DATE_FORMAT((m.fecha_entrada),"%Y-%m-%d")  <=  "'.$fecha_final.'" ) )'; 

          } else {
           $fechas .= ' ';
          }


            $donde = '';
             if ($id_empresa!="") {
                  $id_empre =  self::check_existente_proveedor_entrada($id_empresa);

                    if (!($id_empre)) {
                      $id_empre =0;
                    }                  

                      $donde .= ' AND ( m.id_empresa  =  '.$id_empre.' ) ';

            } else 
            {
               $donde .= ' ';
            }



         if ($factura_reporte!="") {
            $donde .= ' AND ( m.factura  =  "'.$factura_reporte.'" ) ';
        } 



          //productos
          //$id_descripcion= $data['id_descripcion'];
          $id_descripcion= addslashes($data['id_descripcion']);
          $id_color= $data['id_color'];
          $id_composicion= $data['id_composicion'];
          $id_calidad= $data['id_calidad'];


          $id_session = $this->db->escape($this->session->userdata('id'));
          //m.id_empresa,m.id_operacion, m.id_color, m.id_composicion, m.id_calidad,m.id_medida,   m.id_cargador, m.id_usuario,
          //m.id, m.referencia, m.cantidad_royo,  , m.comentario, fecha_apartado,
          //$this->db->select('DATE_FORMAT(m.fecha_entrada,"%d/%m/%Y") as ppp',false);
          //$this->db->select("a.almacen");


          $this->db->select("SQL_CALC_FOUND_ROWS(m.movimiento)");  //, FALSE
          $this->db->select('m.movimiento, m.factura, m.id_descripcion,  m.num_partida');
          $this->db->select('m.id_factura,m.id_fac_orig');
          $this->db->select('m.ancho, m.precio, m.codigo');
          $this->db->select('m.id_estatus, m.id_lote, m.consecutivo,  m.fecha_mac fecha, m.fecha_entrada,m.proceso_traspaso');
          $this->db->select('c.hexadecimal_color, c.color, p.nombre');
          $this->db->select('m.cantidad_um, u.medida, m.devolucion');
          $this->db->select("( CASE WHEN m.id_medida = 1 THEN m.cantidad_um ELSE 0 END ) AS metros"); //, FALSE
          $this->db->select("( CASE WHEN m.id_medida = 2 THEN m.cantidad_um ELSE 0 END ) AS kilogramos"); //, FALSE
          $this->db->select("( CASE WHEN  (m.devolucion <> 0)  THEN 'red'  
                                    WHEN  (m.id_apartado <> 0)  THEN 'morado' 
                                    ELSE 'black' END )
                             AS color_devolucion");    //, FALSE
          $this->db->select("prod.codigo_contable");  
           $this->db->select("(m.precio*m.cantidad_um) as subtotal"); //, FALSE
           $this->db->select("((m.precio*m.cantidad_um*m.iva))/100 as sum_iva"); //, FALSE
           $this->db->select("(m.precio*m.cantidad_um)+((m.precio*m.cantidad_um*m.iva))/100 as total"); //, FALSE
          $this->db->select("tff.tipo_factura t_factura");  

          $this->db->from($this->historico_registros_entradas.' as m');
          //$this->db->join($this->almacenes.' As a' , 'a.id = m.id_almacen AND a.activo=1');
          $this->db->join($this->productos.' As prod' , 'prod.referencia = m.referencia'); //,'LEFT'
          $this->db->join($this->colores.' As c' , 'c.id = m.id_color'); //,'LEFT'
          $this->db->join($this->unidades_medidas.' As u' , 'u.id = m.id_medida'); //,'LEFT'
          $this->db->join($this->proveedores.' As p' , 'p.id = m.id_empresa'); //,'LEFT'
          $this->db->join($this->tipos_facturas.' As tff' , 'tff.id = m.id_factura'); //,'LEFT'


          $cond= ' (p.nombre LIKE  "%'.$cadena.'%") OR  (CONCAT(m.id_lote,"-",m.consecutivo) LIKE  "%'.$cadena.'%") ';//' OR (m.consecutivo LIKE  "%'.$cadena.'%") ';




          if ($estatus=="devolucion") {
               $estatus_idid = ' AND ( m.id_estatus = "13" ) ' ;   
               $estatus_idid = ' AND ( m.id_estatus = "13" ) ' ;   
          }  else {

              if ($id_estatus!=0) {
                $estatus_idid = ' and ( m.id_estatus =  '.$id_estatus.' ) ';  
              } else {
                $estatus_idid = '';
              }

          }  

          if ($id_almacen!=0) {
          $id_almacenid = ' and ( m.id_almacen =  '.$id_almacen.' ) ';  
          } else {
          $id_almacenid = '';
          }
         

          if ($id_factura!=0) {
          $id_facturaid = ' and ( m.id_factura =  '.$id_factura.' ) ';  
          } else {
          $id_facturaid = '';
          }

          $where = '(
                      (
                         ( m.estatus_salida = "0" ) '.$estatus_idid.$id_almacenid.$id_facturaid.' 
                      ) 
                       AND
                      (  ( m.num_partida LIKE  "%'.$cadena.'%" ) OR 
                        ( m.codigo LIKE  "%'.$cadena.'%" ) OR (m.id_descripcion LIKE  "%'.$cadena.'%") OR (c.color LIKE  "%'.$cadena.'%")  OR
                        ( CONCAT(m.cantidad_um," ",u.medida) LIKE  "%'.$cadena.'%" ) OR (CONCAT(m.ancho," cm") LIKE  "%'.$cadena.'%")  OR
                        (m.factura LIKE  "%'.$cadena.'%") OR ( m.movimiento LIKE  "%'.$cadena.'%" ) OR ((DATE_FORMAT((m.fecha_entrada),"%d-%m-%Y") ) LIKE  "%'.$cadena.'%") OR '.$cond.' 
                       )

            ) ' ;                     
          
            $where_total='';


            if  (($id_calidad!="0") AND ($id_calidad!="") AND ($id_calidad!= null)) {
                   $where.= (($where!="") ? " and " : "") . "( m.id_calidad  =  ".$id_calidad." )";
                   $where_total.= (($where_total!="") ? " and " : "") . "( m.id_calidad  =  ".$id_calidad." )";
                }     

                if (($id_composicion!="0") AND ($id_composicion!="") AND ($id_composicion!= null)) {
                    $where.= (($where!="") ? " and " : "") . "( m.id_composicion  =  ".$id_composicion." ) ";
                    $where_total.= (($where_total!="") ? " and " : "") . "( m.id_composicion  =  ".$id_composicion." ) ";
                } 


                if  (($id_color!="0") AND ($id_color!="") AND ($id_color!= null)) {
                   $where.= (($where!="") ?  " and " : "") . "( m.id_color  =  ".$id_color." )";
                   $where_total.= (($where_total!="") ?  " and " : "") . "( m.id_color  =  ".$id_color." )";
                }


                //if ( ($data['val_prod_id'] !="")  && ($data['val_prod_id'] !="0") ) {
                if (($id_descripcion!="0") AND ($id_descripcion!="") AND ($id_descripcion!= null))  {                
                    $where.= (($where!="") ? " and " : "") . "( m.id_descripcion  =  '".$id_descripcion."' )";
                    $where_total.= (($where_total!="") ? " and " : "") . "( m.id_descripcion  =  '".$id_descripcion."' )";
                }                                    



         $this->db->where($where.$donde.$fechas); //

         $where_total= $where.$donde.$fechas;
    
          //ordenacion

          $this->db->order_by($columna, $order); 
          //paginacion
          $this->db->limit($largo,$inicio); 


          $result = $this->db->get();

              if ( $result->num_rows() > 0 ) {

                    $cantidad_consulta = $this->db->query("SELECT FOUND_ROWS() as cantidad");
                    $found_rows = $cantidad_consulta->row(); 
                    $registros_filtrados =  ( (int) $found_rows->cantidad);


                 

                   $retorno= "reportes";   
                  foreach ($result->result() as $row) {
                           

                          
                              $fecha= $row->fecha_entrada;
                              $columna7=$row->id_lote.'-'.$row->consecutivo; 
                              $columna6= $row->nombre;
                          

                           

                           $dato[]= array(
                                      0=>$row->codigo,
                                      1=>$row->id_descripcion,
                                      2=> $row->color.
                                        '<div style="background-color:#'.$row->hexadecimal_color.';display:block;width:15px;height:15px;margin:0 auto;"></div>',
                                      3=>$row->cantidad_um.' '.$row->medida,
                                      4=>$row->ancho.' cm',
                                      5=>number_format($row->subtotal, 2, '.', ','),
                                      6=>number_format($row->sum_iva, 2, '.', ','),
                                      7=>$row->t_factura,
                                      8=>
                                           '<a style="  padding: 1px 0px 1px 0px;" href="'.base_url().'procesar_entradas/'.base64_encode($row->movimiento).'/'.base64_encode($row->devolucion).'/'.base64_encode($retorno).'/'.base64_encode($row->id_fac_orig).'/'.base64_encode($row->id_estatus).'"
                                               type="button" class="btn btn-success btn-block">'.$row->movimiento.'</a>', 
                                      9=>$columna6,
                                      10=>$columna7,
                                      11=> date( 'd-m-Y', strtotime($fecha)),
                                      12=>$row->metros,
                                      13=>$row->kilogramos,
                                      14=>$row->color_devolucion,
                                      15=>$row->factura,
                                      16=>$row->num_partida,
                                      17=>"alm", //$row->almacen,
                                      18=>$row->proceso_traspaso,
                                      19=>$row->codigo_contable,
                                      20=>$row->id_factura,
                                      21=>number_format($row->precio, 2, '.', ','),
                                      22=>$row->id_estatus,
                                    );                    
                      }


                      return json_encode ( array(
                        "draw"            => intval( $data['draw'] ),
                        "recordsTotal"    => $registros_filtrados, 
                        "recordsFiltered" => $registros_filtrados, 
                        "data"            =>  $dato, 
                        "totales"            => 
                           array("pieza"=>intval( self::total_registros_entrada_historica($where_total)->pieza ), 
                                 "metro"=>floatval( self::total_registros_entrada_historica($where_total)->metros ),
                                  "kilogramo"=>floatval( self::total_registros_entrada_historica($where_total)->kilogramos )
                                ), 

                        "totales_importe"            =>  array(
                            "subtotal"=>floatval( self::totales_importes_historica($where_total)->subtotal ), 
                            "iva"=>floatval( self::totales_importes_historica($where_total)->iva ), 
                            "total"=>floatval( self::totales_importes_historica($where_total)->total ),
                            ),                          


                      ));
                    
              }   
              else {
                  $output = array(
                  "draw" =>  intval( $data['draw'] ),
                  "recordsTotal" => 0,
                  "recordsFiltered" =>0,
                  "aaData" => array(),
                        "totales"            => 
                           array("pieza"=>intval( self::total_registros_entrada_historica($where_total)->pieza ), 
                                 "metro"=>floatval( self::total_registros_entrada_historica($where_total)->metros ),
                                  "kilogramo"=>floatval( self::total_registros_entrada_historica($where_total)->kilogramos )
                                ), 

                        "totales_importe"            =>  array(
                            "subtotal"=>floatval( self::totales_importes_historica($where_total)->subtotal ), 
                            "iva"=>floatval( self::totales_importes_historica($where_total)->iva ), 
                            "total"=>floatval( self::totales_importes_historica($where_total)->total ),
                            ),                          


                  );
                  $array[]="";
                  return json_encode($output);
                  

              }

              $result->free_result();           

      }  
        
//////////////////////Auxiliar 


    public function buscador_entrada_home($data){

          $cadena = addslashes($data['search']['value']);
          $inicio = $data['start'];
          $largo = $data['length'];
          
          $id_estatus= $data['id_estatus'];
          $id_empresa= addslashes($data['proveedor']);
          $id_almacen= $data['id_almacen'];

          $factura_reporte = addslashes($data['factura_reporte']);

          $columa_order = $data['order'][0]['column'];
                 $order = $data['order'][0]['dir'];

           if ($data['draw'] ==0) {  //que se ordene por el ultimo
                 $columa_order ='-1';
                 $order = 'desc';
           } 

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
                   case '8':
                        $columna = 'm.fecha_entrada';
                     break;

                   case '12': //'9':
                        $columna = 'm.factura';
                     break;

                   case '13':
                        $columna = 'm.num_partida';
                     break;
                   case '14':
                        $columna = 'm.id_almacen';
                     break;                       

                   
                   default:
                       /*$columna = 'm.factura';
                       $order = 'asc'; */
                       $columna = 'm.id';
                       $order = 'DESC';                       

                     break;
                 }          

          $fechas = ' ';
          if  ( ($data['fecha_inicial'] !="") and  ($data['fecha_final'] !="")) {
                           $fecha_inicial = date( 'Y-m-d', strtotime( $data['fecha_inicial'] ));
                           $fecha_final = date( 'Y-m-d', strtotime( $data['fecha_final'] ));
                          
                          
                            $fechas .= ' AND ( ( DATE_FORMAT((m.fecha_entrada),"%Y-%m-%d")  >=  "'.$fecha_inicial.'" )  AND  ( DATE_FORMAT((m.fecha_entrada),"%Y-%m-%d")  <=  "'.$fecha_final.'" ) )'; 
                          

          } else {
           $fechas .= ' ';
          }



          $donde = '';
         if ($id_empresa!="") {
              $id_empre =  self::check_existente_proveedor_entrada($id_empresa);

                if (!($id_empre)) {
                  $id_empre =0;
                }



                  $donde .= ' AND ( m.id_empresa  =  '.$id_empre.' ) ';


        } else 
        {
           $donde .= ' ';
        }

         if ($factura_reporte!="") {
            $donde .= ' AND ( m.factura  =  "'.$factura_reporte.'" ) ';
        } 


          //productos
          $id_descripcion= addslashes($data['id_descripcion']);
          $id_color= $data['id_color'];
          $id_composicion= $data['id_composicion'];
          $id_calidad= $data['id_calidad'];


          $id_session = $this->db->escape($this->session->userdata('id'));

          //$this->db->select("SQL_CALC_FOUND_ROWS *", FALSE); 
          //$this->db->select('DATE_FORMAT(m.fecha_entrada,"%d/%m/%Y") as ppp',false);
          //m.id,m.id_empresa,m.id_operacion, ,m.id_fac_orig, m.id_cargador, m.id_usuario, m.id_color, m.id_composicion, m.id_calidad, m.referencia,
          //m.id_medida,m.cantidad_royo,, m.comentario, fecha_apartado,
          //$this->db->join($this->almacenes.' As a' , 'a.id = m.id_almacen AND a.activo=1');
          $this->db->select("SQL_CALC_FOUND_ROWS(m.movimiento)");  //, FALSE
          $this->db->select('m.movimiento, m.factura, m.id_descripcion,  m.num_partida');
          $this->db->select('m.id_factura,  m.ancho, m.precio,  m.codigo');
          $this->db->select('(m.precio*m.cantidad_um) as subtotal');           
          $this->db->select("(m.precio*m.cantidad_um*m.iva)/100 as sum_iva"); //, FALSE
          $this->db->select("(m.precio*m.cantidad_um)+(((m.precio*m.cantidad_um*m.iva))/100) as sum_total"); //, FALSE
          $this->db->select('m.id_estatus, m.id_lote, m.consecutivo,  m.fecha_mac fecha, m.fecha_entrada,m.proceso_traspaso');
          $this->db->select('c.hexadecimal_color, c.color');
          $this->db->select("tff.tipo_factura t_factura, p.nombre");  
          $this->db->select('m.cantidad_um, u.medida');
          $this->db->select("( CASE WHEN m.id_medida = 1 THEN m.cantidad_um ELSE 0 END ) AS metros"); //, FALSE
          $this->db->select("( CASE WHEN m.id_medida = 2 THEN m.cantidad_um ELSE 0 END ) AS kilogramos"); //, FALSE
          $this->db->select("( CASE WHEN  (m.devolucion <> 0)  THEN 'red'  
                                    WHEN  (m.id_apartado <> 0)  THEN 'morado' 
                                    ELSE 'black' END )
                             AS color_devolucion");    //, FALSE
          $this->db->select("prod.codigo_contable, m.devolucion");  

          $this->db->from($this->registros.' as m');
          $this->db->join($this->productos.' As prod' , 'prod.referencia = m.referencia'); //,'LEFT'
          $this->db->join($this->colores.' As c' , 'c.id = m.id_color'); //,'LEFT'
          $this->db->join($this->unidades_medidas.' As u' , 'u.id = m.id_medida'); //,'LEFT'
          $this->db->join($this->proveedores.' As p' , 'p.id = m.id_empresa'); //,'LEFT'
          $this->db->join($this->tipos_facturas.' As tff' , 'tff.id = m.id_factura'); //,'LEFT'

          $cond= ' (p.nombre LIKE  "%'.$cadena.'%") OR  (CONCAT(m.id_lote,"-",m.consecutivo) LIKE  "%'.$cadena.'%") ';
          if ($id_estatus!=0) {
            $estatus_idid = ' and ( m.id_estatus =  '.$id_estatus.' ) ';  
          } else {
            $estatus_idid = '';
          }

          if ($id_almacen!=0) {
            $id_almacenid = ' and ( m.id_almacen =  '.$id_almacen.' ) ';  
          } else {
            $id_almacenid = '';
          }

          

          $where = '(
                      (
                         ( m.estatus_salida = "0" ) '.$estatus_idid.$id_almacenid.' 
                      ) 
                       AND
                      ( ( m.num_partida LIKE  "%'.$cadena.'%" ) OR   
                        ( m.codigo LIKE  "%'.$cadena.'%" ) OR (m.id_descripcion LIKE  "%'.$cadena.'%") OR (c.color LIKE  "%'.$cadena.'%")  OR
                        ( CONCAT(m.cantidad_um," ",u.medida) LIKE  "%'.$cadena.'%" ) OR (CONCAT(m.ancho," cm") LIKE  "%'.$cadena.'%")  OR
                        (m.factura LIKE  "%'.$cadena.'%") OR ( m.movimiento LIKE  "%'.$cadena.'%" ) OR ((DATE_FORMAT((m.fecha_entrada),"%d-%m-%Y") ) LIKE  "%'.$cadena.'%") OR '.$cond.' 
                       )

            ) ' ;                     
          

          $where_total = '( ( m.estatus_salida = "0" )  '.$estatus_idid.$id_almacenid.'  )';


               if  (($id_calidad!="0") AND ($id_calidad!="") AND ($id_calidad!= null)) {
                   $where.= (($where!="") ? " and " : "") . "( m.id_calidad  =  ".$id_calidad." )";
                   $where_total.= (($where_total!="") ? " and " : "") . "( m.id_calidad  =  ".$id_calidad." )";
                }    


               if  (($data['id_factura_costo']!="0") AND ($data['id_factura_costo']!="") AND ($data['id_factura_costo']!= null)) {
                   $where.= (($where!="") ? " and " : "") . "( m.id_factura  =  ".$data['id_factura_costo']." )";
                   $where_total.= (($where_total!="") ? " and " : "") . "( m.id_factura  =  ".$data['id_factura_costo']." )";
                }    

                 

                if (($id_composicion!="0") AND ($id_composicion!="") AND ($id_composicion!= null)) {
                    $where.= (($where!="") ? " and " : "") . "( m.id_composicion  =  ".$id_composicion." ) ";
                    $where_total.= (($where_total!="") ? " and " : "") . "( m.id_composicion  =  ".$id_composicion." ) ";
                } 


                if  (($id_color!="0") AND ($id_color!="") AND ($id_color!= null)) {
                   $where.= (($where!="") ?  " and " : "") . "( m.id_color  =  ".$id_color." )";
                   $where_total.= (($where_total!="") ?  " and " : "") . "( m.id_color  =  ".$id_color." )";
                }


                //if ( ($data['val_prod_id'] !="")  && ($data['val_prod_id'] !="0") ) {
                if (($id_descripcion!="0") AND ($id_descripcion!="") AND ($id_descripcion!= null))  {                
                    $where.= (($where!="") ? " and " : "") . "( m.id_descripcion  =  '".$id_descripcion."' )";
                    $where_total.= (($where_total!="") ? " and " : "") . "( m.id_descripcion  =  '".$id_descripcion."' )";
                }

          $where_total.= $donde.$fechas; //$donde.



          $this->db->where($where.$donde.$fechas); //
    
          //ordenacion

          $this->db->order_by($columna, $order); 
          //paginacion
          $this->db->limit($largo,$inicio); 


          $result = $this->db->get();

                $data['where_total'] = $where_total; 
                

              if ( $result->num_rows() > 0 ) {

                    $cantidad_consulta = $this->db->query("SELECT FOUND_ROWS() as cantidad");
                    $found_rows = $cantidad_consulta->row(); 
                    $registros_filtrados =  ( (int) $found_rows->cantidad);


                   $retorno= "reportes";   
                  foreach ($result->result() as $row) {
                              $fecha= $row->fecha_entrada;
                              $columna7=$row->id_lote.'-'.$row->consecutivo; 
                              $columna6= $row->nombre;
                           $dato[]= array(
                                      0=>$row->codigo,
                                      1=>$row->id_descripcion,
                                      2=> $row->color.
                                        '<div style="background-color:#'.$row->hexadecimal_color.';display:block;width:15px;height:15px;margin:0 auto;"></div>',
                                      3=>$row->cantidad_um.' '.$row->medida,
                                      4=>$row->ancho.' cm',
                                      5=>number_format($row->subtotal, 2, '.', ','),
                                      6=>number_format($row->sum_iva, 2, '.', ','),
                                      7=>$row->t_factura,
                                      8=>
                                           '<a style="  padding: 1px 0px 1px 0px;" href="'.base_url().'procesar_entradas/'.base64_encode($row->movimiento).'/'.base64_encode($row->devolucion).'/'.base64_encode($retorno).'/'.base64_encode($row->id_factura).'/'.base64_encode($row->id_estatus).'"
                                               type="button" class="btn btn-success btn-block">'.$row->movimiento.'</a>', 
                                      9=>$columna6,
                                      10=>$columna7,
                                      11=> date( 'd-m-Y', strtotime($fecha)),
                                      12=>$row->metros,
                                      13=>$row->kilogramos,
                                      14=>$row->color_devolucion,
                                      15=>$row->factura,
                                      16=>$row->num_partida,
                                      17=>"alm", //$row->almacen,
                                      18=>$row->proceso_traspaso,
                                      19=>$row->codigo_contable,
                                      20=>$row->id_factura,
                                      21=>number_format($row->precio, 2, '.', ','),
                                      22=>$row->id_estatus,
                                    );                    
                      }

                      return json_encode ( array(
                        "draw"            => intval( $data['draw'] ),
                        "recordsTotal"    => $registros_filtrados, 
                        "recordsFiltered" => $registros_filtrados, 
                        "data"            =>  $dato, 
                        "totales"            =>  array("pieza"=>intval( self::total_campos_entrada_home($data)->pieza ), "metro"=>floatval( self::total_campos_entrada_home($data)->metros ), "kilogramo"=>floatval( self::total_campos_entrada_home($data)->kilogramos )), 
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
                  "totales"            =>  array("pieza"=>intval( self::total_campos_entrada_home($data)->pieza ), "metro"=>floatval( self::total_campos_entrada_home($data)->metros ), "kilogramo"=>floatval( self::total_campos_entrada_home($data)->kilogramos )), 
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
      

      public function total_campos_entrada_home($data){

              $this->db->select("SUM((id_medida =1) * cantidad_um) as metros"); //, FALSE
              $this->db->select("SUM((id_medida =2) * cantidad_um) as kilogramos"); //, FALSE
              $this->db->select("COUNT(m.id_medida) as 'pieza'");
              
             
              $this->db->from($this->registros.' as m');

              $this->db->where($data['where_total']);


             $result = $this->db->get();
          
              if ( $result->num_rows() > 0 )
                 return $result->row();
              else
                 return False;
              $result->free_result();              
       }       


//////////////////////Auxiliar 
        public function check_existente_proveedor_entrada($descripcion){
            $this->db->select("pro.id", FALSE);         
            $this->db->from($this->proveedores.' as pro ');
            $where = '(
                        (
                          ( pro.nombre =  "'.$descripcion.'" ) 
                         )
            )';   
            $this->db->where($where);
            
            $login = $this->db->get();
            if ($login->num_rows() > 0) {
                $fila = $login->row(); 
                return $fila->id;
            }    
            else
                return false;
            $login->free_result();
    }     


	} 
?>
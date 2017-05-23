<?php if(! defined('BASEPATH')) exit('No tienes permiso para acceder a este archivo');

  class modelo_reportes extends CI_Model{
    
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
      $this->catalogo_tipos_pagos  = $this->db->dbprefix('catalogo_tipos_pagos');
      
      $this->tipos_facturas                         = $this->db->dbprefix('catalogo_tipos_facturas');
      $this->tipos_pedidos                         = $this->db->dbprefix('catalogo_tipos_pedidos');
      $this->tipos_ventas                         = $this->db->dbprefix('catalogo_tipos_ventas');

      $this->historico_ctasxpagar                           = $this->db->dbprefix('historico_ctasxpagar');
      $this->historico_pagos_realizados                           = $this->db->dbprefix('historico_pagos_realizados');
      
      $this->documentos_pagos                           = $this->db->dbprefix('catalogo_documentos_pagos');

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


      public function total_registros_entrada_devo($where){

              $this->db->from($this->historico_registros_entradas.' as m');
              //$this->db->join($this->usuarios.' As us' , 'us.id = m.id_usuario_apartado','LEFT');

              $this->db->where($where);

              $cant = $this->db->count_all_results();          
     
              if ( $cant > 0 )
                 return $cant;
              else
                 return 0;         
       }      




      public function total_registros_entrada_devolucion($where){

              $this->db->select("SUM((id_medida =1) * cantidad_um) as metros", FALSE);
              $this->db->select("SUM((id_medida =2) * cantidad_um) as kilogramos", FALSE);
              $this->db->select("COUNT(m.id_medida) as 'pieza'");
              
             
              $this->db->from($this->historico_registros_entradas.' as m');
              $this->db->join($this->usuarios.' As us' , 'us.id = m.id_usuario_apartado','LEFT');
              $this->db->join($this->colores.' As c' , 'c.id = m.id_color','LEFT');
              $this->db->join($this->unidades_medidas.' As u' , 'u.id = m.id_medida','LEFT');
              $this->db->join($this->proveedores.' As p' , 'p.id = m.id_empresa','LEFT');



              $this->db->where($where);

             $result = $this->db->get();
          
              if ( $result->num_rows() > 0 ) {
                 return $result->row();    
              }
            
              else
                 return False;
              $result->free_result();              
       }       



    public function buscador_entrada_devolucion($data){

          $cadena = addslashes($data['search']['value']);
          $inicio = $data['start'];
          $largo = $data['length'];
          $estatus= $data['extra_search'];
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
                       $order = 'asc';
                       */
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

          $this->db->select("SQL_CALC_FOUND_ROWS *", FALSE); 

          $this->db->select('m.id, m.movimiento,m.id_empresa, m.factura, m.id_factura,m.id_fac_orig, m.id_descripcion, m.id_operacion, m.num_partida,m.id_estatus');
          $this->db->select('m.id_color, m.id_composicion, m.id_calidad, m.referencia');
          $this->db->select('m.id_medida,  m.cantidad_royo, m.ancho, m.precio, m.codigo, m.comentario');
          $this->db->select('m.id_estatus, m.id_lote, m.consecutivo, m.id_cargador, m.id_usuario, m.fecha_mac fecha, m.fecha_entrada,fecha_apartado,m.proceso_traspaso');
          $this->db->select('c.hexadecimal_color, c.color, p.nombre');
          $this->db->select('DATE_FORMAT(m.fecha_entrada,"%d/%m/%Y") as ppp',false);


          $this->db->select('m.cantidad_um, u.medida');

          $this->db->select('
                        CASE m.id_apartado
                          WHEN "1" THEN "ab1d1d"
                           WHEN "2" THEN "f1a914"
                           WHEN "3" THEN "14b80f"
                           
                           WHEN "4" THEN "ab1d1d"
                           WHEN "5" THEN "f1a914"
                           WHEN "6" THEN "14b80f"

                           ELSE "No Apartado"
                        END AS apartado
            ',False);

          $this->db->select("( CASE WHEN m.id_medida = 1 THEN m.cantidad_um ELSE 0 END ) AS metros", FALSE);
          $this->db->select("( CASE WHEN m.id_medida = 2 THEN m.cantidad_um ELSE 0 END ) AS kilogramos", FALSE);

          $this->db->select("( CASE WHEN  (m.devolucion <> 0)  THEN 'red'  
                                    WHEN  (m.id_apartado <> 0)  THEN 'morado' 
                                    ELSE 'black' END )
                             AS color_devolucion", FALSE);   
                      
          $this->db->select("a.almacen");
          $this->db->select("prod.codigo_contable");  


          $this->db->from($this->historico_registros_entradas.' as m');
          $this->db->join($this->almacenes.' As a' , 'a.id = m.id_almacen AND a.activo=1');
          $this->db->join($this->productos.' As prod' , 'prod.referencia = m.referencia','LEFT');          
          $this->db->join($this->colores.' As c' , 'c.id = m.id_color','LEFT');
          $this->db->join($this->unidades_medidas.' As u' , 'u.id = m.id_medida','LEFT');
          $this->db->join($this->proveedores.' As p' , 'p.id = m.id_empresa','LEFT');

                               


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
          

          $where_total = '( ( m.estatus_salida = "0" )  '.$estatus_idid.$id_almacenid.$id_facturaid.'  )';

          if ($estatus=="devolucion") {
              $where .= ' AND ( m.id_estatus = "13" ) ' ;   
              $where_total .= ' AND ( m.id_estatus = "13" ) ' ;   
          }    
          
          //OJO
          if ($estatus!="existencia") {
              $where .= ' AND ( m.id_apartado = 0 ) ' ;   
              $where_total .= ' AND ( m.id_apartado = 0 ) ' ;   
          } 



          if ( (($id_calidad!="0") AND ($id_calidad!="") AND ($id_calidad!= null))
            and (($id_composicion!="0") AND ($id_composicion!="") AND ($id_composicion!= null))
            and (($id_color!="0") AND ($id_color!="") AND ($id_color!= null))
            and (($id_descripcion!="0") AND ($id_descripcion!="") AND ($id_descripcion!= null)) 
            ) {
              $where .= ' AND ( m.id_descripcion  =  "'.$id_descripcion.'" ) AND  ( m.id_color  =  '.$id_color.' )';
              $where .= ' AND ( m.id_composicion  =  '.$id_composicion.' ) AND  ( m.id_calidad  =  '.$id_calidad.' )';
              $where_total .= ' AND ( m.id_descripcion  =  "'.$id_descripcion.'" ) AND  ( m.id_color  =  '.$id_color.' )';
              $where_total .= ' AND ( m.id_composicion  =  '.$id_composicion.' ) AND  ( m.id_calidad  =  '.$id_calidad.' )';
          }    

          elseif
           ( 
               (($id_composicion!="0") AND ($id_composicion!="") AND ($id_composicion!= null))
            and (($id_color!="0") AND ($id_color!="") AND ($id_color!= null))
            and (($id_descripcion!="0") AND ($id_descripcion!="") AND ($id_descripcion!= null)) 
            ) {
              $where .= ' AND ( m.id_descripcion  =  "'.$id_descripcion.'" ) AND  ( m.id_color  =  '.$id_color.' )';
              $where .= ' AND ( m.id_composicion  =  '.$id_composicion.' ) ';
              $where_total .= ' AND ( m.id_descripcion  =  "'.$id_descripcion.'" ) AND  ( m.id_color  =  '.$id_color.' )';
              $where_total .= ' AND ( m.id_composicion  =  '.$id_composicion.' ) ';
          }  

          elseif 
           ( (($id_color!="0") AND ($id_color!="") AND ($id_color!= null))
            and (($id_descripcion!="0") AND ($id_descripcion!="") AND ($id_descripcion!= null)) 
            ) {
              $where .= ' AND ( m.id_descripcion  =  "'.$id_descripcion.'" ) AND  ( m.id_color  =  '.$id_color.' )';
              $where_total .= ' AND ( m.id_descripcion  =  "'.$id_descripcion.'" ) AND  ( m.id_color  =  '.$id_color.' )';
          }  

          elseif  (($id_descripcion!="0") AND ($id_descripcion!="") AND ($id_descripcion!= null)) {
              $where .= ' AND ( m.id_descripcion  =  "'.$id_descripcion.'" )';
              $where_total  .= ' AND ( m.id_descripcion  =  "'.$id_descripcion.'" )';
          }  


          $where_total.= $donde.$fechas; //$donde.



          $this->db->where($where.$donde.$fechas); //
    
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
                           

                          if ($estatus=="apartado") {
                              $fecha= $row->fecha_apartado;
                              if (($row->id_apartado) >=4) {
                                $tip_apart= " (Tienda)";
                              } else {
                                $tip_apart= " (Vendedor)";
                              }  
                              $columna6= $row->dependencia.$tip_apart;
                              $columna7= 
                              '<div style="background-color:#'.$row->apartado.';display:block;width:15px;height:15px;margin:0 auto;"></div>';
                          }  else {
                              $fecha= $row->fecha_entrada;
                              $columna7=$row->id_lote.'-'.$row->consecutivo; 
                              $columna6= $row->nombre;
                          }  

                           

                           $dato[]= array(
                                      0=>$row->codigo,
                                      1=>$row->id_descripcion,
                                      2=> $row->color.
                                        '<div style="background-color:#'.$row->hexadecimal_color.';display:block;width:15px;height:15px;margin:0 auto;"></div>',
                                      3=>$row->cantidad_um.' '.$row->medida,
                                      4=>$row->ancho.' cm',
                                      5=>
                                           '<a style="  padding: 1px 0px 1px 0px;" href="'.base_url().'procesar_entradas/'.base64_encode($row->movimiento).'/'.base64_encode($row->devolucion).'/'.base64_encode($retorno).'/'.base64_encode($row->id_fac_orig).'/'.base64_encode($row->id_estatus).'"
                                               type="button" class="btn btn-success btn-block">'.$row->movimiento.'</a>', 
                                      6=>$columna6,
                                      7=>$columna7,
                                      8=> date( 'd-m-Y', strtotime($fecha)),
                                      9=>$row->metros,
                                      10=>$row->kilogramos,
                                      11=>$row->color_devolucion,
                                      12=>$row->factura,
                                      13=>$row->num_partida,
                                      14=>$row->almacen,
                                      15=>$row->proceso_traspaso,
                                      16=>$row->codigo_contable,

                                    );                    
                      }

                      return json_encode ( array(
                        "draw"            => intval( $data['draw'] ),
                        "recordsTotal"    => intval( self::total_registros_entrada_devo($where_total) ),  
                        "recordsFiltered" => $registros_filtrados, 
                        "data"            =>  $dato, 
                        "totales"            =>  array("pieza"=>intval( self::total_registros_entrada_devolucion($where_total)->pieza ), "metro"=>floatval( self::total_registros_entrada_devolucion($where_total)->metros ), "kilogramo"=>floatval( self::total_registros_entrada_devolucion($where_total)->kilogramos )), 

                      ));
                    
              }   
              else {
                  $output = array(
                  "draw" =>  intval( $data['draw'] ),
                  "recordsTotal" => 0,
                  "recordsFiltered" =>0,
                  "aaData" => array(),
                  "totales"            =>  array("pieza"=>intval( self::total_registros_entrada_devolucion($where_total)->pieza ), "metro"=>floatval( self::total_registros_entrada_devolucion($where_total)->metros ), "kilogramo"=>floatval( self::total_registros_entrada_devolucion($where_total)->kilogramos )), 
                  );
                  $array[]="";
                  return json_encode($output);
                  

              }

              $result->free_result();           

      }  


  
//////////////////////////entrada


      public function total_registros_entrada_home($data){

              $this->db->from($this->registros.' as m');
              //$this->db->join($this->usuarios.' As us' , 'us.id = m.id_usuario_apartado','LEFT');


              if ($data['estatus']=="apartado") {
                  $this->db->join($this->usuarios.' As us' , 'us.id = m.id_usuario_apartado','LEFT');
                  $this->db->join($this->proveedores.' As pr', 'us.id_cliente = pr.id','LEFT');

              }    



              $this->db->where($data['where_total']);
              

              //$this->db->where($where);



              $cant = $this->db->count_all_results();          
     
              if ( $cant > 0 )
                 return $cant;
              else
                 return 0;         
       }      



      public function total_campos_entrada_home($data){

              $this->db->select("SUM((id_medida =1) * cantidad_um) as metros", FALSE);
              $this->db->select("SUM((id_medida =2) * cantidad_um) as kilogramos", FALSE);
              $this->db->select("COUNT(m.id_medida) as 'pieza'");
              
             
              $this->db->from($this->registros.' as m');
              //$this->db->join($this->usuarios.' As us' , 'us.id = m.id_usuario_apartado','LEFT');

              //$this->db->where($where);

              if ($data['estatus']=="apartado") {
                  $this->db->join($this->usuarios.' As us' , 'us.id = m.id_usuario_apartado','LEFT');
                  $this->db->join($this->proveedores.' As pr', 'us.id_cliente = pr.id','LEFT');

              }    

              $this->db->where($data['where_total']);


             $result = $this->db->get();
          
              if ( $result->num_rows() > 0 )
                 return $result->row();
              else
                 return False;
              $result->free_result();              
       }       



    public function buscador_entrada_home($data){

          $cadena = addslashes($data['search']['value']);
          $inicio = $data['start'];
          $largo = $data['length'];
          $estatus= $data['extra_search'];
          $id_estatus= $data['id_estatus'];
          $id_empresa= addslashes($data['proveedor']);
          $id_almacen= $data['id_almacen'];
          $id_factura= $data['id_factura'];

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
                          if ($estatus=="apartado") {
                              $columna= 'pr.nombre';
                          }  else {
                              $columna= 'p.nombre';
                          }  
                     break;
                   case '7':
                          if ($estatus=="apartado") {
                              $columna= 'm.id_apartado';
                          }  else {
                              $columna= 'm.id_lote, m.consecutivo';  
                          }  
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
                          
                          if ($estatus=="apartado") {
                            $fechas .= ' AND ( ( DATE_FORMAT((m.fecha_apartado),"%Y-%m-%d")  >=  "'.$fecha_inicial.'" )  AND  ( DATE_FORMAT((m.fecha_apartado),"%Y-%m-%d")  <=  "'.$fecha_final.'" ) )'; 
                          }  else {
                            $fechas .= ' AND ( ( DATE_FORMAT((m.fecha_entrada),"%Y-%m-%d")  >=  "'.$fecha_inicial.'" )  AND  ( DATE_FORMAT((m.fecha_entrada),"%Y-%m-%d")  <=  "'.$fecha_final.'" ) )'; 
                          }  

          } else {
           $fechas .= ' ';
          }



          $donde = '';
         if ($id_empresa!="") {
              $id_empre =  self::check_existente_proveedor_entrada($id_empresa);

                if (!($id_empre)) {
                  $id_empre =0;
                }



              if ($estatus=="apartado") {
                  $donde .= ' AND ( us.id_cliente  =  '.$id_empre.' ) '; //id_cliente_apartado, id_usuario_apartado
              }    else {
                  $donde .= ' AND ( m.id_empresa  =  '.$id_empre.' ) ';
              }

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

          $this->db->select("SQL_CALC_FOUND_ROWS *", FALSE); 

          $this->db->select('m.id, m.movimiento,m.id_empresa, m.factura,m.id_factura,m.id_fac_orig, m.id_descripcion, m.id_operacion, m.num_partida');
          $this->db->select('m.id_color, m.id_composicion, m.id_calidad, m.referencia');
          $this->db->select('m.id_medida,  m.cantidad_royo, m.ancho, m.precio, m.codigo, m.comentario');
          $this->db->select('m.id_estatus, m.id_lote, m.consecutivo, m.id_cargador, m.id_usuario, m.fecha_mac fecha, m.fecha_entrada,fecha_apartado,m.proceso_traspaso');
          $this->db->select('c.hexadecimal_color, c.color, p.nombre');
          $this->db->select('DATE_FORMAT(m.fecha_entrada,"%d/%m/%Y") as ppp',false);


          
          
          if ($estatus=="apartado") {
              $this->db->select('pr.nombre as dependencia', FALSE);
          }

          $this->db->select('m.cantidad_um, u.medida');

          $this->db->select('
                        CASE m.id_apartado
                          WHEN "1" THEN "ab1d1d"
                           WHEN "2" THEN "f1a914"
                           WHEN "3" THEN "14b80f"
                           
                           WHEN "4" THEN "ab1d1d"
                           WHEN "5" THEN "f1a914"
                           WHEN "6" THEN "14b80f"

                           ELSE "No Apartado"
                        END AS apartado
            ',False);

          $this->db->select("( CASE WHEN m.id_medida = 1 THEN m.cantidad_um ELSE 0 END ) AS metros", FALSE);
          $this->db->select("( CASE WHEN m.id_medida = 2 THEN m.cantidad_um ELSE 0 END ) AS kilogramos", FALSE);
          //$this->db->select("( CASE WHEN ((m.devolucion <> 0) or m.id_apartado <> 0 ) THEN 'red' ELSE 'black' END ) AS color_devolucion", FALSE);

          //$this->db->select("( CASE WHEN ( (m.devolucion <> 0) OR (m.id_apartado <> 0)  ) THEN 'red' ELSE 'black' END ) AS color_devolucion", FALSE);         
         

          $this->db->select("( CASE WHEN  (m.devolucion <> 0)  THEN 'red'  
                                    WHEN  (m.id_apartado <> 0)  THEN 'morado' 
                                    ELSE 'black' END )
                             AS color_devolucion", FALSE);   
          
          $this->db->select("a.almacen");
          $this->db->select("prod.codigo_contable");  
          
          $this->db->from($this->registros.' as m');
          $this->db->join($this->almacenes.' As a' , 'a.id = m.id_almacen AND a.activo=1');
          $this->db->join($this->productos.' As prod' , 'prod.referencia = m.referencia','LEFT');
          $this->db->join($this->colores.' As c' , 'c.id = m.id_color','LEFT');
          $this->db->join($this->unidades_medidas.' As u' , 'u.id = m.id_medida','LEFT');
          $this->db->join($this->proveedores.' As p' , 'p.id = m.id_empresa','LEFT');
          
                               


          if ($estatus=="apartado") {
              $this->db->join($this->usuarios.' As us' , 'us.id = m.id_usuario_apartado','LEFT');
              $this->db->join($this->proveedores.' As pr', 'us.id_cliente = pr.id','LEFT');

          }    

          if ($estatus=="apartado") {
            $cond= ' (pr.nombre LIKE  "%'.$cadena.'%") OR  ( m.id_apartado LIKE  "%'.$cadena.'%" )';                 
          } else {
            $cond= ' (p.nombre LIKE  "%'.$cadena.'%") OR  (CONCAT(m.id_lote,"-",m.consecutivo) LIKE  "%'.$cadena.'%") ';//' OR (m.consecutivo LIKE  "%'.$cadena.'%") ';
          }

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
                      ( ( m.num_partida LIKE  "%'.$cadena.'%" ) OR   
                        ( m.codigo LIKE  "%'.$cadena.'%" ) OR (m.id_descripcion LIKE  "%'.$cadena.'%") OR (c.color LIKE  "%'.$cadena.'%")  OR
                        ( CONCAT(m.cantidad_um," ",u.medida) LIKE  "%'.$cadena.'%" ) OR (CONCAT(m.ancho," cm") LIKE  "%'.$cadena.'%")  OR
                        (m.factura LIKE  "%'.$cadena.'%") OR ( m.movimiento LIKE  "%'.$cadena.'%" ) OR ((DATE_FORMAT((m.fecha_entrada),"%d-%m-%Y") ) LIKE  "%'.$cadena.'%") OR '.$cond.' 
                       )

            ) ' ;                     
          

          $where_total = '( ( m.estatus_salida = "0" )  '.$estatus_idid.$id_almacenid.$id_facturaid.'  )';

          if ($estatus=="devolucion") {
              $where .= ' AND ( m.id_estatus = "13" ) ' ;   
              $where_total .= ' AND ( m.id_estatus = "13" ) ' ;   
          }    

/*
          if ($estatus=="apartado") {
              $where .= ' AND ( m.id_apartado != 0 ) ' ;   
              $where_total .= ' AND ( m.id_apartado != 0 ) ' ;      
          }    else {
              $where .= ' AND ( m.id_apartado = 0 ) ' ;   
              $where_total .= ' AND ( m.id_apartado = 0 ) ' ;   
          }

*/
          if ($estatus=="apartado") {
              $where .= ' AND ( m.id_apartado != 0 ) ' ;   
              $where_total .= ' AND ( m.id_apartado != 0 ) ' ;   
          }    elseif ($estatus!="existencia") {
              $where .= ' AND ( m.id_apartado = 0 ) ' ;   
              $where_total .= ' AND ( m.id_apartado = 0 ) ' ;   
          } 



          if ( (($id_calidad!="0") AND ($id_calidad!="") AND ($id_calidad!= null))
            and (($id_composicion!="0") AND ($id_composicion!="") AND ($id_composicion!= null))
            and (($id_color!="0") AND ($id_color!="") AND ($id_color!= null))
            and (($id_descripcion!="0") AND ($id_descripcion!="") AND ($id_descripcion!= null)) 
            ) {
              $where .= ' AND ( m.id_descripcion  =  "'.$id_descripcion.'" ) AND  ( m.id_color  =  '.$id_color.' )';
              $where .= ' AND ( m.id_composicion  =  '.$id_composicion.' ) AND  ( m.id_calidad  =  '.$id_calidad.' )';
              $where_total .= ' AND ( m.id_descripcion  =  "'.$id_descripcion.'" ) AND  ( m.id_color  =  '.$id_color.' )';
              $where_total .= ' AND ( m.id_composicion  =  '.$id_composicion.' ) AND  ( m.id_calidad  =  '.$id_calidad.' )';
          }    

          elseif
           ( 
               (($id_composicion!="0") AND ($id_composicion!="") AND ($id_composicion!= null))
            and (($id_color!="0") AND ($id_color!="") AND ($id_color!= null))
            and (($id_descripcion!="0") AND ($id_descripcion!="") AND ($id_descripcion!= null)) 
            ) {
              $where .= ' AND ( m.id_descripcion  =  "'.$id_descripcion.'" ) AND  ( m.id_color  =  '.$id_color.' )';
              $where .= ' AND ( m.id_composicion  =  '.$id_composicion.' ) ';
              $where_total .= ' AND ( m.id_descripcion  =  "'.$id_descripcion.'" ) AND  ( m.id_color  =  '.$id_color.' )';
              $where_total .= ' AND ( m.id_composicion  =  '.$id_composicion.' ) ';
          }  

          elseif 
           ( (($id_color!="0") AND ($id_color!="") AND ($id_color!= null))
            and (($id_descripcion!="0") AND ($id_descripcion!="") AND ($id_descripcion!= null)) 
            ) {
              $where .= ' AND ( m.id_descripcion  =  "'.$id_descripcion.'" ) AND  ( m.id_color  =  '.$id_color.' )';
              $where_total .= ' AND ( m.id_descripcion  =  "'.$id_descripcion.'" ) AND  ( m.id_color  =  '.$id_color.' )';
          }  

          elseif  (($id_descripcion!="0") AND ($id_descripcion!="") AND ($id_descripcion!= null)) {
              $where .= ' AND ( m.id_descripcion  =  "'.$id_descripcion.'" )';
              $where_total  .= ' AND ( m.id_descripcion  =  "'.$id_descripcion.'" )';
          }  


          $where_total.= $donde.$fechas; //$donde.



          $this->db->where($where.$donde.$fechas); //
    
          //ordenacion

          $this->db->order_by($columna, $order); 
          //paginacion
          $this->db->limit($largo,$inicio); 


          $result = $this->db->get();

                $data['where_total'] = $where_total; 
                $data['estatus'] = $estatus;

              if ( $result->num_rows() > 0 ) {

                    $cantidad_consulta = $this->db->query("SELECT FOUND_ROWS() as cantidad");
                    $found_rows = $cantidad_consulta->row(); 
                    $registros_filtrados =  ( (int) $found_rows->cantidad);


                   $retorno= "reportes";   
                  foreach ($result->result() as $row) {
                           

                          if ($estatus=="apartado") {
                              $fecha= $row->fecha_apartado;
                              if (($row->id_apartado) >=4) {
                                $tip_apart= " (Tienda)";
                              } else {
                                $tip_apart= " (Vendedor)";
                              }  
                              $columna6= $row->dependencia.$tip_apart;
                              $columna7= 
                              '<div style="background-color:#'.$row->apartado.';display:block;width:15px;height:15px;margin:0 auto;"></div>';
                          }  else {
                              $fecha= $row->fecha_entrada;
                              $columna7=$row->id_lote.'-'.$row->consecutivo; 
                              $columna6= $row->nombre;
                          }  

                           

                           $dato[]= array(
                                      0=>$row->codigo,
                                      1=>$row->id_descripcion,
                                      2=> $row->color.
                                        '<div style="background-color:#'.$row->hexadecimal_color.';display:block;width:15px;height:15px;margin:0 auto;"></div>',
                                      3=>$row->cantidad_um.' '.$row->medida,
                                      4=>$row->ancho.' cm',
                                      5=>
                                           '<a style="  padding: 1px 0px 1px 0px;" href="'.base_url().'procesar_entradas/'.base64_encode($row->movimiento).'/'.base64_encode($row->devolucion).'/'.base64_encode($retorno).'/'.base64_encode($row->id_fac_orig).'/'.base64_encode($row->id_estatus).'"
                                               type="button" class="btn btn-success btn-block">'.$row->movimiento.'</a>', 
                                      6=>$columna6,
                                      7=>$columna7,
                                      8=> date( 'd-m-Y', strtotime($fecha)),
                                      9=>$row->metros,
                                      10=>$row->kilogramos,
                                      11=>$row->color_devolucion,
                                      12=>$row->factura,
                                      13=>$row->num_partida,
                                      14=>$row->almacen,
                                      15=>$row->proceso_traspaso,
                                      16=>$row->codigo_contable,

                                      

                                    );                    
                      }

                      return json_encode ( array(
                        "draw"            => intval( $data['draw'] ),
                        "recordsTotal"    => intval( self::total_registros_entrada_home($data) ),  
                        "recordsFiltered" => $registros_filtrados, 
                        "data"            =>  $dato, 
                        "totales"            =>  array("pieza"=>intval( self::total_campos_entrada_home($data)->pieza ), "metro"=>floatval( self::total_campos_entrada_home($data)->metros ), "kilogramo"=>floatval( self::total_campos_entrada_home($data)->kilogramos )), 

                      ));
                    
              }   
              else {
                  $output = array(
                  "draw" =>  intval( $data['draw'] ),
                  "recordsTotal" => 0,
                  "recordsFiltered" =>0,
                  "aaData" => array(),
                  "totales"            =>  array("pieza"=>intval( self::total_campos_entrada_home($data)->pieza ), "metro"=>floatval( self::total_campos_entrada_home($data)->metros ), "kilogramo"=>floatval( self::total_campos_entrada_home($data)->kilogramos )), 
                  );
                  $array[]="";
                  return json_encode($output);
                  

              }

              $result->free_result();           

      }  


///////////////////////////////////////HOME//////////////////////////////////




////////////////////////////////////////Salida/////////////////////////////////////

    
      public function total_campos_salida_home($where) {

              $this->db->select("SUM((id_medida =1) * cantidad_um) as metros", FALSE);
              $this->db->select("SUM((id_medida =2) * cantidad_um) as kilogramos", FALSE);
              $this->db->select("COUNT(m.id_medida) as 'pieza'");
              
             
              $this->db->from($this->historico_registros_salidas.' as m');
              $this->db->where($where);

             $result = $this->db->get();
          
              if ( $result->num_rows() > 0 )
                 return $result->row();
              else
                 return False;
              $result->free_result();              

       }  


     public function total_salida_home($where){
              $id_session = $this->session->userdata('id');
              $this->db->from($this->historico_registros_salidas.' as m');
              $this->db->where($where);
              $cant = $this->db->count_all_results();          
     
              if ( $cant > 0 )
                 return $cant;
              else
                 return 0;         
       }           
   
   public function buscador_salida_home($data){

          $cadena = addslashes($data['search']['value']);
          $inicio = $data['start'];
          $largo = $data['length'];
          $estatus= $data['extra_search'];
          $id_estatus= $data['id_estatus'];
          $id_almacen= $data['id_almacen'];
          $id_factura= $data['id_factura'];

          $id_empresa= addslashes($data['proveedor']);

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
                        $columna = 'm.mov_salida';
                     break;
                   case '6':
                              $columna= 'us.nombre';
                     break;
                   case '7':
                              $columna= 'm.id_lote, m.consecutivo';  
                     break;
                   case '8':
                        $columna = 'm.fecha_salida';
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
                       $order = 'asc';
                       */
                       $columna = 'm.id';
                       $order = 'DESC';                       
                     break;
                 }       

          $donde = '';
         if ($id_empresa!="") {
            $id_empre =  self::check_existente_proveedor_entrada($id_empresa);

                if (!($id_empre)) {
                  $id_empre =0;
                }



            $donde .= ' AND ( m.consecutivo_venta  =  '.$id_empre.' ) ';
        } else 
        {
           $donde .= ' ';
        }

        if ($factura_reporte!="") {
            $donde .= ' AND ( m.factura  =  "'.$factura_reporte.'" ) ';
        } 

          $fechas = ' ';
          if  ( ($data['fecha_inicial'] !="") and  ($data['fecha_final'] !="")) {
                          
                           $fecha_inicial = date( 'Y-m-d', strtotime( $data['fecha_inicial'] ));
                           $fecha_final = date( 'Y-m-d', strtotime( $data['fecha_final'] ));
                          
                           $fechas .= ' AND ( ( DATE_FORMAT((m.fecha_salida),"%Y-%m-%d")  >=  "'.$fecha_inicial.'" )  AND  ( DATE_FORMAT((m.fecha_salida),"%Y-%m-%d")  <=  "'.$fecha_final.'" ) )'; 
                          
          } else {
           $fechas .= ' ';
          }


          //productos
          //$id_descripcion= $data['id_descripcion'];
          $id_descripcion= addslashes($data['id_descripcion']);
          $id_color= $data['id_color'];
          $id_composicion= $data['id_composicion'];
          $id_calidad= $data['id_calidad'];


          $id_session = $this->db->escape($this->session->userdata('id'));

          $this->db->select("SQL_CALC_FOUND_ROWS *", FALSE); 

          $this->db->select('m.id, m.mov_salida,m.id_empresa, m.factura, m.id_descripcion, m.id_operacion, m.num_partida');
          $this->db->select('m.id_color, m.id_composicion, m.id_calidad, m.referencia,m.id_tipo_pedido,m.id_tipo_factura');
          $this->db->select('m.id_medida,  m.cantidad_royo, m.ancho, m.precio, m.codigo, m.comentario');
          $this->db->select('m.id_estatus, m.id_lote, m.consecutivo, m.id_cargador, m.id_usuario, m.fecha_mac fecha, m.fecha_entrada,m.proceso_traspaso');
          $this->db->select('c.hexadecimal_color, c.color , p.nombre');
          
          $this->db->select('us.nombre as nom_cliente', FALSE);    //

          $this->db->select('p.nombre cliente,ca.nombre cargador');  
          
          if ($estatus=="apartado") {
              $this->db->select('pr.nombre as dependencia', FALSE);
          }

          $this->db->select('m.cantidad_um, u.medida');

          $this->db->select('
                        CASE m.id_apartado
                          WHEN "1" THEN "ab1d1d"
                           WHEN "2" THEN "f1a914"
                           WHEN "3" THEN "14b80f"
                           
                           WHEN "4" THEN "ab1d1d"
                           WHEN "5" THEN "f1a914"
                           WHEN "6" THEN "14b80f"

                           ELSE "No Apartado"
                        END AS apartado
            ',False);

          $this->db->select("( CASE WHEN m.id_medida = 1 THEN m.cantidad_um ELSE 0 END ) AS metros", FALSE);
          $this->db->select("( CASE WHEN m.id_medida = 2 THEN m.cantidad_um ELSE 0 END ) AS kilogramos", FALSE);
          
          $this->db->select("( CASE WHEN m.devolucion <> 0 THEN 'red' ELSE 'black' END ) AS color_devolucion", FALSE);

          $this->db->select("a.almacen");
          $this->db->select("prod.codigo_contable");  
          
         
          $this->db->from($this->historico_registros_salidas.' as m');
          $this->db->join($this->almacenes.' As a' , 'a.id = m.id_almacen AND a.activo=1');
          $this->db->join($this->productos.' As prod' , 'prod.referencia = m.referencia','LEFT');
          $this->db->join($this->colores.' As c' , 'c.id = m.id_color','LEFT');
          $this->db->join($this->unidades_medidas.' As u' , 'u.id = m.id_medida','LEFT');
          $this->db->join($this->proveedores.' As us' , 'us.id = m.consecutivo_venta','LEFT'); //
          $this->db->join($this->proveedores.' As p' , 'p.id = m.id_cliente','LEFT');
          $this->db->join($this->cargadores.' As ca' , 'ca.id = m.id_cargador','LEFT');                 
              



          if ($estatus=="apartado") {
              $this->db->join($this->usuarios.' As us' , 'us.id = m.id_usuario_apartado','LEFT');
              $this->db->join($this->proveedores.' As pr', 'us.id_cliente = pr.id','LEFT');

          }    

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
          /*
          if ($id_factura!=0) {
            $id_facturaid = ' and ( m.id_factura =  '.$id_factura.' ) ';  
          } else {
            $id_facturaid = '';
          }
          */



          if ($id_factura!=0) {
             $id_factura = (($id_factura==3) ? 0 : $id_factura);
             $id_facturaid = ' and ( m.id_tipo_factura =  '.$id_factura.' ) ';  
          } else {
             $id_facturaid = '';
          }    


          $where = '(
                      (
                         ( m.estatus_salida = "0" )  '.$estatus_idid.$id_almacenid.$id_facturaid.' 
                      ) 
                       AND
                      (  ( m.num_partida LIKE  "%'.$cadena.'%" ) OR   
                        ( m.codigo LIKE  "%'.$cadena.'%" ) OR (m.id_descripcion LIKE  "%'.$cadena.'%") OR (c.color LIKE  "%'.$cadena.'%")  OR
                        ( CONCAT(m.cantidad_um," ",u.medida) LIKE  "%'.$cadena.'%" ) OR (CONCAT(m.ancho," cm") LIKE  "%'.$cadena.'%")  OR
                        ( m.mov_salida LIKE  "%'.$cadena.'%" ) OR ((DATE_FORMAT((m.fecha_salida),"%d-%m-%Y") ) LIKE  "%'.$cadena.'%") OR 
                        (m.factura LIKE  "%'.$cadena.'%")  OR  (CONCAT(m.id_lote,"-",m.consecutivo) LIKE  "%'.$cadena.'%") 

                        OR (us.nombre LIKE  "%'.$cadena.'%")

                       )
            ) ' ;   



          $where_total = '( m.estatus_salida = "0" )  '.$estatus_idid.$id_almacenid.$id_facturaid;

           if ( (($id_calidad!="0") AND ($id_calidad!="") AND ($id_calidad!= null))
            and (($id_composicion!="0") AND ($id_composicion!="") AND ($id_composicion!= null))
            and (($id_color!="0") AND ($id_color!="") AND ($id_color!= null))
            and (($id_descripcion!="0") AND ($id_descripcion!="") AND ($id_descripcion!= null)) 
            ) {
              $where .= ' AND ( m.id_descripcion  =  "'.$id_descripcion.'" ) AND  ( m.id_color  =  '.$id_color.' )';
              $where .= ' AND ( m.id_composicion  =  '.$id_composicion.' ) AND  ( m.id_calidad  =  '.$id_calidad.' )';
              $where_total .= ' AND ( m.id_descripcion  =  "'.$id_descripcion.'" ) AND  ( m.id_color  =  '.$id_color.' )';
              $where_total .= ' AND ( m.id_composicion  =  '.$id_composicion.' ) AND  ( m.id_calidad  =  '.$id_calidad.' )';
          }    

          elseif
           ( 
               (($id_composicion!="0") AND ($id_composicion!="") AND ($id_composicion!= null))
            and (($id_color!="0") AND ($id_color!="") AND ($id_color!= null))
            and (($id_descripcion!="0") AND ($id_descripcion!="") AND ($id_descripcion!= null)) 
            ) {
              $where .= ' AND ( m.id_descripcion  =  "'.$id_descripcion.'" ) AND  ( m.id_color  =  '.$id_color.' )';
              $where .= ' AND ( m.id_composicion  =  '.$id_composicion.' ) ';
              $where_total .= ' AND ( m.id_descripcion  =  "'.$id_descripcion.'" ) AND  ( m.id_color  =  '.$id_color.' )';
              $where_total .= ' AND ( m.id_composicion  =  '.$id_composicion.' ) ';
          }  

          elseif 
           ( (($id_color!="0") AND ($id_color!="") AND ($id_color!= null))
            and (($id_descripcion!="0") AND ($id_descripcion!="") AND ($id_descripcion!= null)) 
            ) {
              $where .= ' AND ( m.id_descripcion  =  "'.$id_descripcion.'" ) AND  ( m.id_color  =  '.$id_color.' )';
              $where_total .= ' AND ( m.id_descripcion  =  "'.$id_descripcion.'" ) AND  ( m.id_color  =  '.$id_color.' )';
          }  

          elseif  (($id_descripcion!="0") AND ($id_descripcion!="") AND ($id_descripcion!= null)) {
              $where .= ' AND ( m.id_descripcion  =  "'.$id_descripcion.'" )';
              $where_total  .= ' AND ( m.id_descripcion  =  "'.$id_descripcion.'" )';
          }  


          $where_total .= $donde.$fechas;

          $this->db->where($where.$donde.$fechas); //

    
          //ordenacion
          $this->db->order_by($columna, $order); 

          //paginacion
          $this->db->limit($largo,$inicio); 


          $result = $this->db->get();

              if ( $result->num_rows() > 0 ) {

                    $cantidad_consulta = $this->db->query("SELECT FOUND_ROWS() as cantidad");
                    $found_rows = $cantidad_consulta->row(); 
                    $registros_filtrados =  ( (int) $found_rows->cantidad);


                  foreach ($result->result() as $row) {
                           

                          if ($estatus=="apartado") {

                              if (($row->id_apartado) >=4) {
                                $tip_apart= " (Tienda)";
                              } else {
                                $tip_apart= " (Vendedor)";
                              }  
                              $columna6= $row->dependencia.$tip_apart;
                              $columna7= 
                              '<div style="background-color:#'.$row->apartado.';display:block;width:15px;height:15px;margin:0 auto;"></div>';
                          }  else {
                              $columna7=$row->id_lote.'-'.$row->consecutivo; 
                              $columna6= $row->nombre;
                          }  

                           

                           $dato[]= array(
                                      0=>$row->codigo,
                                      1=>$row->id_descripcion,
                                      2=> $row->color.
                                        '<div style="background-color:#'.$row->hexadecimal_color.';display:block;width:15px;height:15px;margin:0 auto;"></div>',
                                      3=>$row->cantidad_um.' '.$row->medida,
                                      4=>$row->ancho.' cm',
                                      5=> //
                                          '<a style="padding: 1px 0px 1px 0px;" href="'.base_url().'detalle_salidas/'.base64_encode($row->mov_salida).'/'.base64_encode($row->cliente).'/'.base64_encode($row->cargador."r*").'/'.base64_encode($row->id_tipo_pedido).'/'.base64_encode($row->id_tipo_factura).'/'.base64_encode("reportes").'/'.base64_encode($row->id_estatus).'" 
                                          type="button" class="btn btn-success btn-block">'.$row->mov_salida.'</a>',
                                      6=>$row->nom_cliente, //$columna6
                                      7=>$columna7,
                                      8=> date( 'd-m-Y', strtotime($row->fecha_salida)),
                                      9=>$row->metros,
                                      10=>$row->kilogramos,
                                      11=>$row->color_devolucion,
                                      12=>$row->factura,
                                      13=>$row->num_partida,
                                      14=>$row->almacen,
                                      15=>$row->proceso_traspaso,
                                      16=>$row->codigo_contable,

                                      
                                    );                    
                      }

                      return json_encode ( array(
                        "draw"            => intval( $data['draw'] ),
                        "recordsTotal"    => intval( self::total_salida_home($where_total) ),  
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
 


///////////////////////////////devolucion_Home////////////////////////////

      public function total_cero_baja($where, $where_cond){
              $id_session = $this->session->userdata('id');

              $this->db->select("SQL_CALC_FOUND_ROWS *", FALSE); 

              //$this->db->select("p.referencia,p.descripcion, p.minimo, p.imagen, p.precio, c.hexadecimal_color,c.color,co.composicion,ca.calidad");

              $this->db->select("p.referencia, p.minimo,  p.precio, c.hexadecimal_color");

              $this->db->select("COUNT(m.referencia) as 'suma'");
              $this->db->select("p.id_color, p.id_composicion, p.id_calidad");

              $this->db->from($this->productos.' as p');
              $this->db->join($this->colores.' As c', 'p.id_color = c.id','LEFT');
              $this->db->join($this->composiciones.' As co', 'p.id_composicion = co.id','LEFT');
              $this->db->join($this->calidades.' As ca', 'p.id_calidad = ca.id','LEFT');
              $this->db->join($this->registros.' As m', 'p.referencia = m.referencia and m.id_estatus=12 ','LEFT');

              if  ($where_cond!='') {
                $this->db->where($where_cond);
              }  

              $this->db->group_by("p.referencia, p.minimo,  p.precio, c.hexadecimal_color,c.color,co.id,ca.id"); //p.imagen, p.descripcion,
              
              $this->db->having($where);


             $result = $this->db->get();

              if ( $result->num_rows() > 0 ) {
                  $cantidad_consulta = $this->db->query("SELECT FOUND_ROWS() as cantidad");
                  $found_rows = $cantidad_consulta->row(); 
                  $registros_filtrados =  ( (int) $found_rows->cantidad);
              }  
              
              $cant = $registros_filtrados;
     
              if ( $cant > 0 )
                 return $cant;
              else
                 return 0;         
       }

    public function buscador_cero_baja($data){


            $str= 'SELECT SQL_CALC_FOUND_ROWS *, p.referencia, COUNT(m.referencia) as suma,
          m.proceso_traspaso,  p.minimo, p.precio,

          ca.calidad, c.color, co.composicion, p.descripcion,


          

        ( CASE WHEN m.id_medida = 1 THEN m.cantidad_um ELSE 0 END ) AS metros, 
        ( CASE WHEN m.id_medida = 2 THEN m.cantidad_um ELSE 0 END ) AS kilogramos,  p.codigo_contable
       
       FROM (inven_catalogo_productos as p) JOIN inven_catalogo_colores As c ON p.id_color = c.id JOIN inven_catalogo_composicion As co ON p.id_composicion = co.id JOIN inven_catalogo_calidad As ca ON p.id_calidad = ca.id JOIN inven_registros_entradas As m ON m.referencia= p.referencia  JOIN inven_catalogo_almacenes As a ON a.id = m.id_almacen WHERE ( ( ( p.referencia LIKE "%%" ) OR (p.descripcion LIKE "%%") OR (CONCAT("Optimo:",p.minimo) LIKE "%%") OR (c.color LIKE "%%") OR (p.comentario LIKE "%%") OR (co.composicion LIKE "%%") OR ( ca.calidad LIKE "%%" ) OR ( p.precio LIKE "%%" ) ) ) GROUP BY p.referencia, p.descripcion, p.minimo, p.precio, c.hexadecimal_color,  co.id, ca.id HAVING ((suma>0) AND (suma < p.minimo)) ORDER BY suma DESC LIMIT 170, 45';

       $result = $this->db->query($str);



          //$result = $this->db->get();

              if ( $result->num_rows() > 0 ) {

                    /*$cantidad_consulta = $this->db->query("SELECT FOUND_ROWS() as cantidad");
                    $found_rows = $cantidad_consulta->row(); 
                    $registros_filtrados =  ( (int) $found_rows->cantidad);
                    */


                  foreach ($result->result() as $row) {
                        /*
                        $nombre_fichero ='uploads/productos/thumbnail/300X300/'.substr($row->imagen,0,strrpos($row->imagen,".")).'_thumb'.substr($row->imagen,strrpos($row->imagen,"."));
                        if (file_exists($nombre_fichero)) {
                            $imagen ='<img src="'.base_url().$nombre_fichero.'" border="0" width="75" height="75">';

                        } else {
                            $imagen ='<img src="img/sinimagen.png" border="0" width="75" height="75">';
                        }*/

                           $dato[]= array(
                                      0=>$row->referencia, 
                                      1=>$row->descripcion,
                                      2=>"1", //'Optimo:'.$row->minimo.'<br/>  Reales:'. $row->suma,
                                      3=>"1", // $imagen,

                                       // '<img src="'.base_url().'uploads/productos/thumbnail/300X300/'.substr($row->imagen,0,strrpos($row->imagen,'.')).'_thumb'.substr($row->imagen,strrpos($row->imagen,'.')).'" border="0" width="75" height="75">',
                                      4=>"1", //$row->nombre_color.                                      
                                        //'<div style="background-color:#'.$row->hexadecimal_color.';display:block;width:15px;height:15px;margin:0 auto;"></div>',
                                      5=>"1", // $row->comentario, 
                                      6=>$row->composicion,
                                      7=>$row->calidad,
                                      8=>$row->color,
                                      9=>"1", //$row->metros,
                                      10=>"1", //$row->kilogramos,
                                      11=>"1", //"black",
                                      12=>"--",
                                      13=>"--", 
                                      14=>"1", //$row->almacen,
                                      15=>"1", //$row->proceso_traspaso,
                                      16=>"1", //$row->codigo_contable,



                                    );                    

                      }

                      return json_encode ( array(
                        "draw"            => intval( $data['draw'] ),
                        "recordsTotal"    => 10, //intval( self::total_cero_baja($where_total,$where_cond) ),  
                        "recordsFiltered" => 10, //$registros_filtrados, 
                        "data"            =>  $dato, 
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



    



  public function total_existencias_baja($where, $where_cond){
              $id_session = $this->session->userdata('id');

              $this->db->select("SQL_CALC_FOUND_ROWS *", FALSE); 

              $this->db->select("p.referencia,p.descripcion, p.minimo,  p.precio, c.hexadecimal_color,c.color,co.composicion,ca.calidad"); //p.imagen,
              $this->db->select("COUNT(m.referencia) as 'suma'");
              $this->db->select("p.id_color, p.id_composicion, p.id_calidad");

              $this->db->from($this->productos.' as p');
              $this->db->join($this->colores.' As c', 'p.id_color = c.id'); //,'LEFT'
              $this->db->join($this->composiciones.' As co', 'p.id_composicion = co.id'); //,'LEFT'
              $this->db->join($this->calidades.' As ca', 'p.id_calidad = ca.id'); //,'LEFT'
              $this->db->join($this->registros.' As m', 'p.referencia = m.referencia and m.id_estatus=12 '); //,'LEFT'

              if  ($where_cond!='') {
                $this->db->where($where_cond);
              }  

              $this->db->group_by("p.referencia,p.descripcion, p.minimo,  p.precio, c.hexadecimal_color,c.color,co.composicion,ca.calidad"); //p.imagen,
              
              $this->db->having($where);


             $result = $this->db->get();

              if ( $result->num_rows() > 0 ) {
                  $cantidad_consulta = $this->db->query("SELECT FOUND_ROWS() as cantidad");
                  $found_rows = $cantidad_consulta->row(); 
                  $registros_filtrados =  ( (int) $found_rows->cantidad);
              }  
              
              $cant = $registros_filtrados;
     
              if ( $cant > 0 )
                 return $cant;
              else
                 return 0;         
       }

    


    public function buscador_existencias_baja($data){


      
      /*
      //

      COUNT(m.referencia) as suma,
      , p.comentario,p.descripcion,
         m.proceso_traspaso,  p.minimo, p.precio, c.hexadecimal_color, c.color nombre_color, co.composicion, ca.calidad,
        ( CASE WHEN m.id_medida = 1 THEN m.cantidad_um ELSE 0 END ) AS metros, 
        ( CASE WHEN m.id_medida = 2 THEN m.cantidad_um ELSE 0 END ) AS kilogramos, a.almacen, p.codigo_contable 

        c.hexadecimal_color, c.color nombre_color, co.composicion, ca.calidad,a.almacen,

        and m.id_estatus=12
      */


       $str= 'SELECT p.referencia, COUNT(m.referencia) as suma,
          m.proceso_traspaso,  p.minimo, p.precio,


          

        ( CASE WHEN m.id_medida = 1 THEN m.cantidad_um ELSE 0 END ) AS metros, 
        ( CASE WHEN m.id_medida = 2 THEN m.cantidad_um ELSE 0 END ) AS kilogramos,  p.codigo_contable
       
       FROM (inven_catalogo_productos as p) JOIN inven_catalogo_colores As c ON p.id_color = c.id JOIN inven_catalogo_composicion As co ON p.id_composicion = co.id JOIN inven_catalogo_calidad As ca ON p.id_calidad = ca.id JOIN inven_registros_entradas As m ON m.referencia= p.referencia  JOIN inven_catalogo_almacenes As a ON a.id = m.id_almacen WHERE ( ( ( p.referencia LIKE "%%" ) OR (p.descripcion LIKE "%%") OR (CONCAT("Optimo:",p.minimo) LIKE "%%") OR (c.color LIKE "%%") OR (p.comentario LIKE "%%") OR (co.composicion LIKE "%%") OR ( ca.calidad LIKE "%%" ) OR ( p.precio LIKE "%%" ) ) ) GROUP BY p.referencia, p.descripcion, p.minimo, p.precio, c.hexadecimal_color, c.color, co.composicion, ca.calidad HAVING ((suma>0) AND (suma < p.minimo)) ORDER BY suma DESC LIMIT 20, 10';

       $query = $this->db->query($str);


       return json_encode($query->result());

   }     

    public function buscador_existencias_baja1($data){


      


       $str= 'SELECT p.referencia, p.comentario, m.proceso_traspaso, p.descripcion, p.minimo, p.precio, c.hexadecimal_color, c.color nombre_color, co.composicion, ca.calidad, COUNT(m.referencia) as suma, ( CASE WHEN m.id_medida = 1 THEN m.cantidad_um ELSE 0 END ) AS metros, ( CASE WHEN m.id_medida = 2 THEN m.cantidad_um ELSE 0 END ) AS kilogramos, a.almacen, p.codigo_contable FROM (inven_catalogo_productos as p) JOIN inven_catalogo_colores As c ON p.id_color = c.id JOIN inven_catalogo_composicion As co ON p.id_composicion = co.id JOIN inven_catalogo_calidad As ca ON p.id_calidad = ca.id JOIN inven_registros_entradas As m ON m.referencia= p.referencia and m.id_estatus=12 JOIN inven_catalogo_almacenes As a ON a.id = m.id_almacen WHERE ( ( ( p.referencia LIKE "%%" ) OR (p.descripcion LIKE "%%") OR (CONCAT("Optimo:",p.minimo) LIKE "%%") OR (c.color LIKE "%%") OR (p.comentario LIKE "%%") OR (co.composicion LIKE "%%") OR ( ca.calidad LIKE "%%" ) OR ( p.precio LIKE "%%" ) ) ) GROUP BY p.referencia, p.descripcion, p.minimo, p.precio, c.hexadecimal_color, c.color, co.composicion, ca.calidad HAVING ((suma>0) AND (suma < p.minimo)) ORDER BY suma DESC LIMIT 30, 10';

       $query = $this->db->query($str);


        return $query->result();






        $id_empresa= addslashes($data['proveedor']);
           $id_empresaid = '';
             if ($id_empresa!="") {
                  $id_empre =  self::check_existente_proveedor_entrada($id_empresa);

                    if (!($id_empre)) {
                      $id_empre =0;
                    }                  

                      $id_empresaid .= ' and ( m.id_empresa  =  '.$id_empre.' )  ';

            } else 
            {
               $id_empresaid .= ' ';
            }          
            $id_empresaid .= '';




          $cadena = addslashes($data['search']['value']);
          $inicio = $data['start'];
          $largo = $data['length'];
          $estatus= $data['extra_search'];

                   //productos
          //$id_descripcion= $data['id_descripcion'];
          $id_descripcion= addslashes($data['id_descripcion']);
          $id_color= $data['id_color'];
          $id_composicion= $data['id_composicion'];
          $id_calidad= $data['id_calidad'];
          $id_almacen= $data['id_almacen'];
          $id_factura= $data['id_factura'];

          $columa_order = $data['order'][0]['column'];

          $order = $data['order'][0]['dir'];

           if ($data['draw'] ==0) {  //que se ordene por el ultimo
                 $columa_order ='-1';
                 $order = 'DESC';
           } 



      
          switch ($columa_order) {
                   case '0':
                        $columna = 'p.referencia';
                     break;
                   case '1':
                        $columna = 'p.descripcion';
                     break;
                   case '2':
                        $columna = 'suma'; // y suma = COUNT(m.referencia) p.minimo
                     break;
                   case '3':
                        //$columna = 'p.imagen'; //
                     break;
                   case '4':
                        $columna = 'c.color';
                     break;
                   case '5':
                        $columna = 'p.comentario';
                     break;
                   case '6':
                              $columna= 'co.composicion';
                     break;
                   case '7':
                              $columna= 'ca.calidad';
                     break;
                   case '8':
                        $columna = 'p.precio';
                     break;
                   case '14':
                        $columna = 'm.id_almacen';
                     break;                       

                   
                   default:
                       /*$columna = 'p.referencia';*/
                       $columna = 'suma'; //'p.id';
                       $order = 'DESC';                       
                     break;
                 }           


          $id_session = $this->db->escape($this->session->userdata('id'));

          //$this->db->select("SQL_CALC_FOUND_ROWS *", FALSE); 

           $this->db->select('p.referencia, p.comentario,m.proceso_traspaso');
          $this->db->select('p.descripcion, p.minimo,  p.precio'); //
          $this->db->select('c.hexadecimal_color,c.color nombre_color');
          $this->db->select("co.composicion", FALSE);  
          $this->db->select("ca.calidad", FALSE);  
          $this->db->select("COUNT(m.referencia) as 'suma'");

          $this->db->select("( CASE WHEN m.id_medida = 1 THEN m.cantidad_um ELSE 0 END ) AS metros", FALSE);
          $this->db->select("( CASE WHEN m.id_medida = 2 THEN m.cantidad_um ELSE 0 END ) AS kilogramos", FALSE);
          $this->db->select("a.almacen");
         
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


           



          $this->db->select("p.codigo_contable");  
          $this->db->from($this->productos.' as p');

          $this->db->join($this->colores.' As c', 'p.id_color = c.id'); //,'LEFT'
          $this->db->join($this->composiciones.' As co', 'p.id_composicion = co.id'); //,'LEFT'
          $this->db->join($this->calidades.' As ca', 'p.id_calidad = ca.id'); //,'LEFT'
          $this->db->join($this->registros.' As m', 'm.referencia= p.referencia and m.id_estatus=12 '.$id_almacenid.$id_facturaid.$id_empresaid); //,'LEFT'
          $this->db->join($this->almacenes.' As a', 'a.id = m.id_almacen'); //,'LEFT'



          //(CONCAT("Reales:",count(m.referencia)) LIKE  "%'.$cadena.'%")  OR  no se puede


         if ($estatus=="cero") {
            $activo  = ' and ( p.activo =  0 ) ';  
          } else {
            $activo ='';
          }

          $where = '(
                      
                      (
                        ( p.referencia LIKE  "%'.$cadena.'%" ) OR (p.descripcion LIKE  "%'.$cadena.'%") OR (CONCAT("Optimo:",p.minimo) LIKE  "%'.$cadena.'%")  OR
                        (c.color LIKE  "%'.$cadena.'%") OR (p.comentario LIKE  "%'.$cadena.'%")  OR
                        (co.composicion LIKE  "%'.$cadena.'%")  OR
                        ( ca.calidad LIKE  "%'.$cadena.'%" )  OR 
                        ( p.precio1 LIKE  "%'.$cadena.'%" ) 
                       )'.$activo.'

            ) ' ; 


         

                $where_cond="";        


                if  (($id_calidad!="0") AND ($id_calidad!="") AND ($id_calidad!= null)) {
                   $where.= (($where!="") ? " and " : "") . "( p.id_calidad  =  ".$id_calidad." )";
                   $where_cond.= (($where_cond!="") ? " and " : "") . "( p.id_calidad  =  ".$id_calidad." )";
                }     

                if (($id_composicion!="0") AND ($id_composicion!="") AND ($id_composicion!= null)) {
                    $where.= (($where!="") ? " and " : "") . "( p.id_composicion  =  ".$id_composicion." ) ";
                    $where_cond.= (($where_cond!="") ? " and " : "") . "( p.id_composicion  =  ".$id_composicion." ) ";
                } 


                if  (($id_color!="0") AND ($id_color!="") AND ($id_color!= null)) {
                   $where.= (($where!="") ?  " and " : "") . "( p.id_color  =  ".$id_color." )";
                   $where_cond.= (($where_cond!="") ?  " and " : "") . "( p.id_color  =  ".$id_color." )";
                }


                //if ( ($data['val_prod_id'] !="")  && ($data['val_prod_id'] !="0") ) {
                if (($id_descripcion!="0") AND ($id_descripcion!="") AND ($id_descripcion!= null))  {                
                    $where.= (($where!="") ? " and " : "") . "( p.descripcion  =  '".$id_descripcion."' )";
                    $where_cond.= (($where_cond!="") ? " and " : "") . "( p.descripcion  =  '".$id_descripcion."' )";
                }

            
    
          

          $this->db->where($where);

          $this->db->order_by($columna, $order); 

          $this->db->group_by("p.referencia,p.descripcion, p.minimo,  p.precio, c.hexadecimal_color,c.color,co.composicion,ca.calidad"); //p.imagen,
          //paginacion


         if ($estatus=="cero") {
              $this->db->having('suma <= 0');
              $where_total = 'suma <= 0';
          }   

          if ($estatus=="baja") {
              //$this->db->having('suma < p.minimo');
              //$where_total = 'suma < p.minimo';

              $this->db->having('((suma>0) AND (suma < p.minimo))');
              $where_total = '((suma>0) AND (suma < p.minimo))';

          }             
          
 

          $this->db->limit($largo,$inicio); 


          $result = $this->db->get();

              if ( $result->num_rows() > 0 ) {

                    /*$cantidad_consulta = $this->db->query("SELECT FOUND_ROWS() as cantidad");
                    $found_rows = $cantidad_consulta->row(); 
                    $registros_filtrados =  ( (int) $found_rows->cantidad);
                    */


                  foreach ($result->result() as $row) {

                           $dato[]= array(
                                      0=>$row->referencia, 



                                    );                    

                      }

                      return json_encode ( array(
                        "draw"            => intval( $data['draw'] ),
                        "recordsTotal"    => 60, //intval( self::total_existencias_baja($where_total,$where_cond) ),  
                        "recordsFiltered" => 60, //$registros_filtrados, 
                        "data"            =>  $dato, 
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





///////////////////buscador top

     public function total_top(){

          $this->db->select("SQL_CALC_FOUND_ROWS *", FALSE); 

          $this->db->select("p.referencia,p.descripcion, p.minimo, p.imagen, p.precio, c.hexadecimal_color,c.color,co.composicion,ca.calidad");
          $this->db->select("COUNT(m.referencia) as 'suma'");
          $this->db->select("p.id_color, p.id_composicion, p.id_calidad");

          $this->db->from($this->productos.' as p');
          $this->db->join($this->colores.' As c', 'p.id_color = c.id','LEFT');
          $this->db->join($this->composiciones.' As co', 'p.id_composicion = co.id','LEFT');
          $this->db->join($this->calidades.' As ca', 'p.id_calidad = ca.id','LEFT');
          $this->db->join($this->historico_registros_salidas.' As m', 'p.referencia = m.referencia','LEFT');

          $this->db->group_by("p.referencia,p.descripcion, p.minimo, p.imagen, p.precio, c.hexadecimal_color,c.color,co.composicion,ca.calidad");

          $result = $this->db->get();

          if ( $result->num_rows() > 0 ) {

                $cantidad_consulta = $this->db->query("SELECT FOUND_ROWS() as cantidad");
                $found_rows = $cantidad_consulta->row(); 
                $registros_filtrados =  ( (int) $found_rows->cantidad);
          }   

           $cant = $registros_filtrados;
     
              if ( $cant > 0 )
                 return $cant;
              else
                 return 0;         
       }      


    public function buscador_top($data){

          $cadena = addslashes($data['search']['value']);
          $inicio = $data['start'];
          $largo = $data['length'];
          $estatus= $data['extra_search'];
          $id_almacen= $data['id_almacen'];
          $id_factura= $data['id_factura'];
          $columa_order = $data['order'][0]['column'];
                 /*
           if ($data['draw'] ==0) {
                 $columa_order ='9';
                 $order = 'desc';
           } else {
                
           }
                 */
           $order = $data['order'][0]['dir'];

           if ($data['draw'] ==0) {  //que se ordene por el ultimo
                 $columa_order ='-1';
                 $order = 'DESC';
           } 

          switch ($columa_order) {
                   case '0':
                        $columna = 'p.referencia';
                     break;
                   case '1':
                        $columna = 'p.descripcion';
                     break;
                   case '2':
                        $columna = 'suma'; // y suma = COUNT(m.referencia) p.minimo
                     break;
                   case '3':
                        $columna = 'p.imagen'; //
                     break;
                   case '4':
                        $columna = 'c.color';
                     break;
                   case '5':
                        $columna = 'p.comentario';
                     break;
                   case '6':
                              $columna= 'co.composicion';
                     break;
                   case '7':
                              $columna= 'ca.calidad';
                     break;
                   case '8':
                        $columna = 'p.precio';
                     break;
                     /*
                   case '9':
                        $columna = 'suma';
                     break;*/
                    case '14':
                        $columna = 'm.id_almacen';
                     break;                       

                   default:
                       //$columna = 'suma'; //para que salga ordenado por 

                       $columna = 'suma'; // 'p.id';
                       $order = 'DESC';

                     break;
                 }           


          $id_session = $this->db->escape($this->session->userdata('id'));

          $this->db->select("SQL_CALC_FOUND_ROWS *", FALSE); 

           $this->db->select('p.referencia, p.comentario,m.proceso_traspaso');
          $this->db->select('p.descripcion, p.minimo, p.imagen, p.precio');
          $this->db->select('c.hexadecimal_color,c.color nombre_color');
          $this->db->select("co.composicion", FALSE);  
          $this->db->select("ca.calidad", FALSE);  



         $fechas = ' ';
          if  ( ($data['fecha_inicial'] !="") and  ($data['fecha_final'] !="")) {
                           $fecha_inicial = date( 'Y-m-d', strtotime( $data['fecha_inicial'] ));
                           $fecha_final = date( 'Y-m-d', strtotime( $data['fecha_final'] ));
                          
                            $fechas .= ' AND ( ( DATE_FORMAT((m.fecha_salida),"%Y-%m-%d")  >=  "'.$fecha_inicial.'" )  AND  ( DATE_FORMAT((m.fecha_salida),"%Y-%m-%d")  <=  "'.$fecha_final.'" ) )'; 


                          
          } else {
           $fechas .= ' ';
          }

          $this->db->select("COUNT(m.referencia) as 'suma'");
          

          $this->db->select("( CASE WHEN m.id_medida = 1 THEN m.cantidad_um ELSE 0 END ) AS metros", FALSE);
          $this->db->select("( CASE WHEN m.id_medida = 2 THEN m.cantidad_um ELSE 0 END ) AS kilogramos", FALSE);

          $this->db->select("a.almacen");

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
          $this->db->select("p.codigo_contable");  

          $this->db->from($this->productos.' as p');
          $this->db->join($this->colores.' As c', 'p.id_color = c.id','LEFT');
          $this->db->join($this->composiciones.' As co', 'p.id_composicion = co.id','LEFT');
          $this->db->join($this->calidades.' As ca', 'p.id_calidad = ca.id','LEFT');
          $this->db->join($this->historico_registros_salidas.' As m', 'p.referencia = m.referencia'.$fechas.''.$id_almacenid.$id_facturaid,'LEFT');
          $this->db->join($this->almacenes.' As a', 'a.id = m.id_almacen','LEFT');

          if ($estatus=="cero") {
            $activo  = ' and ( p.activo =  0 ) ';  
          } else {
            $activo ='';
          }
          
          $where = '(
                      
                      (
                        ( p.referencia LIKE  "%'.$cadena.'%" ) OR (p.descripcion LIKE  "%'.$cadena.'%") OR 
                        (c.color LIKE  "%'.$cadena.'%") OR (p.comentario LIKE  "%'.$cadena.'%")  OR
                        (co.composicion LIKE  "%'.$cadena.'%")  OR
                        ( ca.calidad LIKE  "%'.$cadena.'%" )  OR 
                        ( p.precio LIKE  "%'.$cadena.'%" ) 
                       )'.$activo.'

            ) ' ; 


          $this->db->where($where);

          $this->db->order_by($columna, $order); 
          //$this->db->order_by('suma', 'desc'); 


          $this->db->group_by("p.referencia,p.descripcion, p.minimo, p.imagen, p.precio, c.hexadecimal_color,c.color,co.composicion,ca.calidad");
          //paginacion

          $this->db->limit($largo,$inicio); 


          $result = $this->db->get();

              if ( $result->num_rows() > 0 ) {

                    $cantidad_consulta = $this->db->query("SELECT FOUND_ROWS() as cantidad");
                    $found_rows = $cantidad_consulta->row(); 
                    $registros_filtrados =  ( (int) $found_rows->cantidad);


                  foreach ($result->result() as $row) {

                        $nombre_fichero ='uploads/productos/thumbnail/516X516/'.substr($row->imagen,0,strrpos($row->imagen,".")).'_thumb'.substr($row->imagen,strrpos($row->imagen,"."));
                        if (file_exists($nombre_fichero)) {
                            $imagen ='<img src="'.base_url().$nombre_fichero.'" border="0" width="75" height="75">';
                            
                            $imagen='<a type="button" href="'.base_url().$nombre_fichero.'" border="0" width="75" height="75" target="_blank" class="button">Ver Imagen</a>';

                        } else {
                            $imagen ='<img src="img/sinimagen.png" border="0" width="75" height="75">';
                            
                            $imagen='<a type="button" href="img/sinimagen.png" border="0" width="75" height="75" target="_blank" class="button">Ver Imagen</a>';

                        }

                                                         

                           $dato[]= array(
                                      0=>$row->referencia, 
                                      1=>$row->descripcion,
                                      2=>$row->suma,
                                      3=> $imagen,
                                        //'<img src="'.base_url().'uploads/productos/thumbnail/300X300/'.substr($row->imagen,0,strrpos($row->imagen,'.')).'_thumb'.substr($row->imagen,strrpos($row->imagen,'.')).'" border="0" width="75" height="75">',
                                      4=>$row->nombre_color.                                      
                                        '<div style="background-color:#'.$row->hexadecimal_color.';display:block;width:15px;height:15px;margin:0 auto;"></div>',
                                      5=> $row->comentario, 
                                      6=>$row->composicion,
                                      7=>$row->calidad,
                                      8=>$row->precio,
                                      9=>$row->metros,
                                      10=>$row->kilogramos,
                                      11=>"black",
                                      12=>"--",
                                      13=>"--",
                                      14=>$row->almacen,
                                      15=>$row->proceso_traspaso,
                                      16=>$row->codigo_contable,

                                    );                    

                      }

                      return json_encode ( array(
                        "draw"            => intval( $data['draw'] ),
                        "recordsTotal"    => intval( self::total_top() ),  
                        "recordsFiltered" => $registros_filtrados, 
                        "data"            =>  $dato, 
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


//id, movimiento, id_documento_pago, instrumento_pago, fecha_pago, importe, comentario


//////////////////////////////////////////////////fin de reportes presentaciones/////////////////////////////////////////////////////////////////

 public function buscador_historico_entradas($data){

          $cadena = addslashes($data['search']['value']);
          $inicio = $data['start'];
          $largo = $data['length'];
          $id_almacen= $data['id_almacen'];
          $id_factura= $data['id_factura'];
          

          $columa_order = $data['order'][0]['column'];
                 $order = $data['order'][0]['dir'];

           if ($data['draw'] ==1) { //que se ordene por el ultimo
                 $columa_order ='-1';
                 $order = 'desc';
           } 



          switch ($columa_order) {
                   case '0':
                        $columna = 'm.movimiento';
                     break;
                   case '1':
                        $columna = 'tp.tipo_pago';
                     break;
                   case '2':
                        $columna = 'a.almacen';
                     break;
                   case '3':
                        $columna = 'p.nombre';
                     break;
                   case '4':
                        $columna = 'm.fecha_entrada';
                     break;
                   case '5':
                        $columna = 'm.factura';
                     break;
                   case '6':
                        $columna = 'sum_precio';
                     break;
                   case '7':
                        $columna = 'sum_iva';
                     break;
                   case '8':
                        $columna = 'sum_total';
                     break;


                   default:
                        $columna = 'm.movimiento';
                        //$this->db->order_by('m.movimiento', 'desc'); 
                     break;
                 }                 



                                      

          $id_session = $this->db->escape($this->session->userdata('id'));

          $this->db->select("SQL_CALC_FOUND_ROWS *", FALSE); //


          $this->db->select('m.movimiento');
          $this->db->select('a.almacen,m.id_factura,m.id_fac_orig, m.id_estatus');
          $this->db->select('p.nombre, m.factura,tp.tipo_pago');

          $this->db->select("MAX(DATE_FORMAT(m.fecha_entrada,'%d-%m-%Y %H:%i')) as fecha",false);
          $this->db->select("MAX(m.devolucion) devolucion",false);
          $this->db->select("MAX( CASE WHEN m.devolucion <> 0 THEN 'red' ELSE 'black' END ) AS color_devolucion", FALSE);
          
          $this->db->select('sum(m.precio*m.cantidad_um) as sum_precio');           
          $this->db->select("sum(m.precio*m.cantidad_um*m.iva)/100 as sum_iva", FALSE);
          $this->db->select("sum(m.precio*m.cantidad_um)+(sum(m.precio*m.cantidad_um*m.iva)/100) as sum_total", FALSE);


          

          $this->db->from($this->historico_registros_entradas.' as m');
          $this->db->join($this->almacenes.' As a' , 'a.id = m.id_almacen AND a.activo=1');
          $this->db->join($this->proveedores.' As p' , 'p.id = m.id_empresa','LEFT');          
          $this->db->join($this->catalogo_tipos_pagos.' As tp' , 'tp.id = m.id_tipo_pago','LEFT');

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

          if ($data['id_estatus']!=0) {
             
             $id_estatusid = ' and ( m.id_estatus =  '.$data['id_estatus'].' ) ';  
          } else {
             $id_estatusid = '';
          }               

          $fechas = ' ';
          if  ( ($data['fecha_inicial'] !="") and  ($data['fecha_final'] !="")) {
                           $fecha_inicial = date( 'Y-m-d', strtotime( $data['fecha_inicial'] ));
                           $fecha_final = date( 'Y-m-d', strtotime( $data['fecha_final'] ));
                          
                            $fechas .= ' AND ( ( DATE_FORMAT((m.fecha_entrada),"%Y-%m-%d")  >=  "'.$fecha_inicial.'" )  AND  ( DATE_FORMAT((m.fecha_entrada),"%Y-%m-%d")  <=  "'.$fecha_final.'" ) )'; 

          } else {
           $fechas .= ' ';
          }           

          $where = '(
                      (
                         ( m.id_operacion = '.$data["id_operacion"].' )    AND ( m.devolucion = 0 )'.$fechas.$id_almacenid.$id_facturaid.$id_estatusid.' 
                      ) 

                       AND
                      (  ( m.movimiento LIKE  "%'.$cadena.'%" )OR 
                        ( tp.tipo_pago LIKE  "%'.$cadena.'%" ) OR 
                        ( a.almacen LIKE  "%'.$cadena.'%" ) OR (p.nombre LIKE  "%'.$cadena.'%") OR 
                        ((DATE_FORMAT((m.fecha_entrada),"%d-%m-%Y %H:%i") ) LIKE  "%'.$cadena.'%") OR
                        (m.factura LIKE  "%'.$cadena.'%")                         
                       )



            )';   





          $where_total= $where;
                /*
                    '(
                         ( m.id_operacion = '.$data["id_operacion"].' )    AND ( m.devolucion = 0 )'.$fechas.$id_almacenid.$id_facturaid.' 
                      )';
            */

          $this->db->where($where);          

          $this->db->group_by('m.movimiento,m.id_factura,a.almacen,p.nombre,m.factura,m.id_estatus');

          
          //ordenacion
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
                                      0=>$row->movimiento,
                                      1=>$row->tipo_pago,
                                      2=>$row->almacen,
                                      3=>$row->nombre,
                                      4=>$row->fecha,
                                      5=>$row->factura,
                                      6=>number_format($row->sum_precio, 2, '.', ','),
                                      7=>number_format($row->sum_iva, 2, '.', ','),
                                      //8=>number_format($row->sum_total, 2, '.', ','),
                                      8=>number_format(($row->sum_precio+$row->sum_iva), 2, '.', ','),
                                      9=>$row->devolucion,
                                      10=>$row->id_factura,
                                      11=>$row->id_estatus,
                                      

                                    );
                      }




                      return json_encode ( array(
                        "draw"            => intval( $data['draw'] ),
                        "recordsTotal"    =>intval( self::total_historico_entradas($where_total) ), 
                        "recordsFiltered" =>   $registros_filtrados, 
                        "data"            =>  $dato,
                        "totales_importe"            =>  array(
                              "subtotal"=>floatval( self::totales_importes($where_total)->subtotal ), 
                              "iva"=>floatval( self::totales_importes($where_total)->iva ), 
                              "total"=>floatval( self::totales_importes($where_total)->total ),
                            ),                           
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


    
public function totales_importes($where){

           $this->db->select("SUM(m.precio*m.cantidad_um) as subtotal", FALSE);
           $this->db->select("(SUM(m.precio*m.cantidad_um*m.iva))/100 as iva", FALSE);
           $this->db->select("SUM(m.precio*m.cantidad_um)+(SUM(m.precio*m.cantidad_um*m.iva))/100 as total", FALSE);
   
          $this->db->from($this->historico_registros_entradas.' as m');
          $this->db->join($this->almacenes.' As a' , 'a.id = m.id_almacen AND a.activo=1');
          $this->db->join($this->proveedores.' As p' , 'p.id = m.id_empresa','LEFT');          
          $this->db->join($this->catalogo_tipos_pagos.' As tp' , 'tp.id = m.id_tipo_pago','LEFT');

          $this->db->where($where);


          $result = $this->db->get();
      
          if ( $result->num_rows() > 0 )
             return $result->row();
          else
             return False;
          $result->free_result();              

    }        
       


 public function total_historico_entradas($where){
              

              $this->db->select("SQL_CALC_FOUND_ROWS *", FALSE); //
             
          
              $this->db->from($this->historico_registros_entradas.' as m');
              $this->db->join($this->almacenes.' As a' , 'a.id = m.id_almacen AND a.activo=1');
              $this->db->join($this->proveedores.' As p' , 'p.id = m.id_empresa','LEFT');              
              $this->db->join($this->catalogo_tipos_pagos.' As tp' , 'tp.id = m.id_tipo_pago','LEFT');


              $this->db->where($where);          
              $this->db->group_by('m.movimiento,m.id_factura,a.almacen,p.nombre,m.factura,m.id_estatus');
              //$this->db->having($where);

             $result = $this->db->get();

              if ( $result->num_rows() > 0 ) {
                  $cantidad_consulta = $this->db->query("SELECT FOUND_ROWS() as cantidad");
                  $found_rows = $cantidad_consulta->row(); 
                  $registros_filtrados =  ( (int) $found_rows->cantidad);
              }  
              
              $cant = $registros_filtrados;
     
              if ( $cant > 0 )
                 return $cant;
              else
                 return 0;         
       }

/*
 public function listado_devolucion( $data ){

          $id_session = $this->session->userdata('id');
                    
          $this->db->distinct();                    
          $this->db->select('m.movimiento,m.id_empresa,p.nombre, m.factura, m.id_operacion,m.devolucion');
          $this->db->select("(DATE_FORMAT(m.fecha_entrada,'%d-%m-%Y %H:%i')) as fecha",false);
          $this->db->select("( CASE WHEN m.devolucion <> 0 THEN 'red' ELSE 'black' END ) AS color_devolucion", FALSE);
          
          $this->db->select('a.almacen');
          $this->db->from($this->historico_registros_entradas.' as m');
          $this->db->join($this->proveedores.' As p' , 'p.id = m.id_empresa','LEFT');
          $this->db->join($this->almacenes.' As a' , 'a.id = m.id_almacen AND a.activo=1');

          //$this->db->where('m.id_usuario',$id_session);
          //$this->db->where('m.id_operacion',$data['id_operacion']);

          //AND ( m.devolucion = 0 )
          $where = '(
                      (
                         ( m.id_operacion = '.$data["id_operacion"].' )   AND ( m.devolucion <> 0 )
                      ) 

            )';   


          $this->db->where($where);          

          $this->db->order_by('m.movimiento', 'desc'); 

           $result = $this->db->get();
        
            if ( $result->num_rows() > 0 )
               return $result->result();
            else
               return False;
            $result->free_result();
        }       
*/

public function buscador_historico_devolucion($data){

          $cadena = addslashes($data['search']['value']);
          $inicio = $data['start'];
          $largo = $data['length'];

          $id_almacen= $data['id_almacen'];
          $id_factura= $data['id_factura'];

          

          $columa_order = $data['order'][0]['column'];
                 $order = $data['order'][0]['dir'];

           if ($data['draw'] ==1) { //que se ordene por el ultimo
                 $columa_order ='-1';
                 $order = 'desc';
           } 



          switch ($columa_order) {
                   case '0':
                        $columna = 'm.movimiento';
                     break;
                   case '1':
                        $columna = 'a.almacen';
                     break;
                   case '2':
                        $columna = 'p.nombre';
                     break;
                   case '3':
                        $columna = 'm.fecha_entrada';
                     break;
                   case '4':
                        $columna = 'm.factura';
                     break;
                   case '5':
                        $columna = 'sum_precio';
                     break;
                   case '6':
                        $columna = 'sum_iva';
                     break;
                   case '7':
                        $columna = 'sum_total';
                     break;


                   default:
                        $columna = 'm.movimiento';
                        //$this->db->order_by('m.movimiento', 'desc'); 
                     break;
                 }                 

                                      

          $id_session = $this->db->escape($this->session->userdata('id'));

          $this->db->select("SQL_CALC_FOUND_ROWS *", FALSE); //


          //$this->db->distinct();                    

          $this->db->select('m.movimiento,m.id_empresa,p.nombre, m.factura, m.id_operacion,m.devolucion,m.id_estatus');
          $this->db->select("(DATE_FORMAT(m.fecha_entrada,'%d-%m-%Y %H:%i')) as fecha",false);
          $this->db->select("( CASE WHEN m.devolucion <> 0 THEN 'red' ELSE 'black' END ) AS color_devolucion", FALSE);

          $this->db->select('sum(m.precio*m.cantidad_um) as sum_precio');           
          $this->db->select("sum(m.precio*m.cantidad_um*m.iva)/100 as sum_iva", FALSE);
          $this->db->select("sum(m.precio*m.cantidad_um)+((sum(m.precio*m.cantidad_um*m.iva))/100) as sum_total", FALSE);

          
          $this->db->select('a.almacen,m.id_factura,m.id_fac_orig');
          $this->db->from($this->historico_registros_entradas.' as m');
          $this->db->join($this->almacenes.' As a' , 'a.id = m.id_almacen AND a.activo=1');
          $this->db->join($this->proveedores.' As p' , 'p.id = m.id_empresa','LEFT');
          

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

          $fechas = ' ';
          if  ( ($data['fecha_inicial'] !="") and  ($data['fecha_final'] !="")) {
                           $fecha_inicial = date( 'Y-m-d', strtotime( $data['fecha_inicial'] ));
                           $fecha_final = date( 'Y-m-d', strtotime( $data['fecha_final'] ));
                          
                            $fechas .= ' AND ( ( DATE_FORMAT((m.fecha_entrada),"%Y-%m-%d")  >=  "'.$fecha_inicial.'" )  AND  ( DATE_FORMAT((m.fecha_entrada),"%Y-%m-%d")  <=  "'.$fecha_final.'" ) )'; 

          } else {
           $fechas .= ' ';
          }     



          $where = '(
                      (
                         ( m.id_operacion = '.$data["id_operacion"].' )    AND ( m.devolucion <> 0 )'.$fechas.$id_almacenid.$id_facturaid.' 
                      ) 

                       AND
                      (  ( m.movimiento LIKE  "%'.$cadena.'%" )OR 
                        ( a.almacen LIKE  "%'.$cadena.'%" ) OR (p.nombre LIKE  "%'.$cadena.'%") OR 
                        ((DATE_FORMAT((m.fecha_entrada),"%d-%m-%Y %H:%i") ) LIKE  "%'.$cadena.'%") OR
                        (m.factura LIKE  "%'.$cadena.'%")                         
                       )



            )';   



          $where_total= $where; /*'(
                         ( m.id_operacion = '.$data["id_operacion"].' )    AND ( m.devolucion <> 0 )'.$fechas.$id_almacenid.$id_facturaid.' 
                      )';*/
           

          $this->db->where($where);          
          $this->db->group_by('m.movimiento,m.id_almacen,m.id_empresa,m.factura');
          

          
          //ordenacion
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
                                      0=>$row->movimiento,
                                      1=>$row->almacen,
                                      2=>$row->nombre,
                                      3=>$row->fecha,
                                      4=>$row->factura,
                                      5=>number_format($row->sum_precio, 2, '.', ','),
                                      6=>number_format($row->sum_iva, 2, '.', ','),
                                      7=>number_format($row->sum_total, 2, '.', ','),
                                      8=>$row->devolucion,
                                      9=>$row->id_factura,
                                      10=>$row->id_estatus,
                                      

                                    );
                      }




                      return json_encode ( array(
                        "draw"            => intval( $data['draw'] ),
                        "recordsTotal"    =>intval( self::total_historico_devolucion($where_total) ), 
                        "recordsFiltered" =>   $registros_filtrados, 
                        "data"            =>  $dato,
                        "totales_importe"            =>  array(
                              "subtotal"=>floatval( self::totales_importes_devolucion($where_total)->subtotal ), 
                              "iva"=>floatval( self::totales_importes_devolucion($where_total)->iva ), 
                              "total"=>floatval( self::totales_importes_devolucion($where_total)->total ),
                        ),                              

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
       

   
public function totales_importes_devolucion($where){

           $this->db->select("SUM(m.precio*m.cantidad_um) as subtotal", FALSE);
           $this->db->select("(SUM(m.precio*m.cantidad_um*m.iva))/100 as iva", FALSE);
           $this->db->select("SUM(m.precio*m.cantidad_um)+(SUM(m.precio*m.cantidad_um*m.iva))/100 as total", FALSE);
   
          $this->db->from($this->historico_registros_entradas.' as m');
          $this->db->join($this->almacenes.' As a' , 'a.id = m.id_almacen AND a.activo=1');
          $this->db->join($this->proveedores.' As p' , 'p.id = m.id_empresa','LEFT');
          



          $this->db->where($where);


          $result = $this->db->get();
      
          if ( $result->num_rows() > 0 )
             return $result->row();
          else
             return False;
          $result->free_result();              

    }              


      public function total_historico_devolucion($where){
                    $this->db->select("SQL_CALC_FOUND_ROWS *", FALSE); //
                    $this->db->from($this->historico_registros_entradas.' as m');
                    $this->db->join($this->almacenes.' As a' , 'a.id = m.id_almacen AND a.activo=1');
                    $this->db->join($this->proveedores.' As p' , 'p.id = m.id_empresa','LEFT');
                    

                    $this->db->where($where);          
                    $this->db->group_by('m.movimiento,m.id_almacen,m.id_empresa,m.factura');

                   $result = $this->db->get();

                    if ( $result->num_rows() > 0 ) {
                        $cantidad_consulta = $this->db->query("SELECT FOUND_ROWS() as cantidad");
                        $found_rows = $cantidad_consulta->row(); 
                        $registros_filtrados =  ( (int) $found_rows->cantidad);
                    }  
                    
                    $cant = $registros_filtrados;
           
                    if ( $cant > 0 )
                       return $cant;
                    else
                       return 0;         
      }

 






/*



        public function listado_salidas( $data ){

          $id_session = $this->session->userdata('id');
                    
          $this->db->distinct();                    
          $this->db->select('m.mov_salida ,p.nombre cliente, ca.nombre cargador, m.factura');   //movimiento  id_empresa  p.nombre, , m.id_operacion
          $this->db->select("(DATE_FORMAT(m.fecha_salida,'%d-%m-%Y')) as fecha",false);
          
          $this->db->select('a.almacen');
           $this->db->select("tp.tipo_pedido,m.id_tipo_pedido");          
          $this->db->select("tf.tipo_factura,m.id_tipo_factura");          


          $this->db->from($this->historico_registros_salidas.' as m');
          $this->db->join($this->proveedores.' As p' , 'p.id = m.id_cliente','LEFT');
          $this->db->join($this->cargadores.' As ca' , 'ca.id = m.id_cargador','LEFT');
          
          $this->db->join($this->tipos_pedidos.' As tp' , 'tp.id = m.id_tipo_pedido','LEFT');
          $this->db->join($this->tipos_facturas.' As tf' , 'tf.id = m.id_tipo_factura','LEFT');
          $this->db->join($this->almacenes.' As a' , 'a.id = m.id_almacen AND a.activo=1');


          //$this->db->where('m.id_usuario',$id_session);
          $this->db->where('m.id_operacion',$data['id_operacion']);

          $this->db->order_by('m.mov_salida', 'desc'); 

           $result = $this->db->get();
        
            if ( $result->num_rows() > 0 )
               return $result->result();
            else
               return False;
            $result->free_result();
        }  
*/




public function buscador_historico_salida($data){

          $cadena = addslashes($data['search']['value']);
          $inicio = $data['start'];
          $largo = $data['length'];

          $id_almacen= $data['id_almacen'];
          $id_factura= $data['id_factura'];          

          $columa_order = $data['order'][0]['column'];
                 $order = $data['order'][0]['dir'];

           if ($data['draw'] ==1) { //que se ordene por el ultimo
                 $columa_order ='-1';
                 $order = 'desc';
           } 



          switch ($columa_order) {
                   case '0':
                        $columna = 'm.mov_salida';
                     break;
                   case '1':
                        $columna = 'a.almacen';
                     break;
                   case '2':
                        $columna = 'p.nombre'; //cliente
                     break;
                   case '3':
                        $columna = 'ca.nombre'; //cargador
                     break;                     
                     
                   case '4':
                        $columna = 'm.fecha_salida';
                     break;
                   case '5':
                        $columna = 'tf.tipo_factura,tp.tipo_pedido';
                     break;

                   case '6':
                        $columna = 'm.factura';
                     break;
                   case '7':
                        $columna = 'sum_precio';
                     break;
                   case '8':
                        $columna = 'sum_iva';
                     break;
                   case '9':
                        $columna = 'sum_total';
                     break;


                   default:
                        $columna = 'm.mov_salida';
                        //$this->db->order_by('m.movimiento', 'desc'); 
                     break;
                 }                 

                                      

          $id_session = $this->db->escape($this->session->userdata('id'));

          $this->db->select("SQL_CALC_FOUND_ROWS *", FALSE); //



          //$this->db->distinct();                    
          $this->db->select('m.mov_salida , ca.nombre cargador, m.factura');  
          $this->db->select('CONCAT(u.nombre,"  ",u.apellidos) as cliente', FALSE);    //p.nombre cliente,
          $this->db->select("(DATE_FORMAT(m.fecha_salida,'%d-%m-%Y %H:%i')) as fecha",false);
          
          $this->db->select('a.almacen');
           $this->db->select("tp.tipo_pedido,m.id_tipo_pedido");          
          $this->db->select("tf.tipo_factura,m.id_tipo_factura, m.id_estatus,m.precio");          

          $this->db->select('sum(m.precio*m.cantidad_um) as sum_precio');           
          $this->db->select("sum(m.precio*m.cantidad_um*m.iva)/100 as sum_iva", FALSE);
          $this->db->select("sum(m.precio*m.cantidad_um)+((sum(m.precio*m.cantidad_um*m.iva))/100) as sum_total", FALSE);

          $this->db->select('m.id_apartado apartado, m.consecutivo_venta,m.id_cliente_apartado');  

          $this->db->select("prov_pedido.nombre cliente_pedido");
          $this->db->select("prov_apartado.nombre cliente_apartado");

          $this->db->from($this->historico_registros_salidas.' as m');
          $this->db->join($this->almacenes.' As a' , 'a.id = m.id_almacen AND a.activo=1');
          $this->db->join($this->usuarios.' As u' , 'u.id = m.id_usuario_apartado','LEFT');
          $this->db->join($this->proveedores.' As p' , 'p.id = m.id_cliente','LEFT');
          $this->db->join($this->cargadores.' As ca' , 'ca.id = m.id_cargador','LEFT');          
          $this->db->join($this->tipos_pedidos.' As tp' , 'tp.id = m.id_tipo_pedido','LEFT');
          $this->db->join($this->tipos_facturas.' As tf' , 'tf.id = m.id_tipo_factura','LEFT');
          $this->db->join($this->proveedores.' As prov_pedido' , 'prov_pedido.id = m.consecutivo_venta','LEFT');
          $this->db->join($this->proveedores.' As prov_apartado' , 'prov_apartado.id = m.id_cliente_apartado','LEFT');
          


          if ($id_almacen!=0) {
             $id_almacenid = ' and ( m.id_almacen =  '.$id_almacen.' ) ';  
          } else {
              $id_almacenid = '';
          }
         

          if ($id_factura!=0) {
             $id_factura = (($id_factura==3) ? 0 : $id_factura);
             $id_facturaid = ' and ( m.id_tipo_factura =  '.$id_factura.' ) ';  
          } else {
             $id_facturaid = '';
          }         



          if ($data['id_estatus']!=0) {
             
             $id_estatusid = ' and ( m.id_estatus =  '.$data['id_estatus'].' ) ';  
          } else {
             $id_estatusid = '';
          }         


          $fechas = ' ';
          if  ( ($data['fecha_inicial'] !="") and  ($data['fecha_final'] !="")) {
                           $fecha_inicial = date( 'Y-m-d', strtotime( $data['fecha_inicial'] ));
                           $fecha_final = date( 'Y-m-d', strtotime( $data['fecha_final'] ));
                          
                            $fechas .= ' AND ( ( DATE_FORMAT((m.fecha_salida),"%Y-%m-%d")  >=  "'.$fecha_inicial.'" )  AND  ( DATE_FORMAT((m.fecha_salida),"%Y-%m-%d")  <=  "'.$fecha_final.'" ) )'; 

          } else {
           $fechas .= ' ';
          }     


          $where = '(
                      (
                         ( m.id_operacion = '.$data["id_operacion"].' ) '.$fechas.$id_almacenid.$id_facturaid.$id_estatusid.'  
                      ) 

                       AND
                      ( 
                        ( m.mov_salida LIKE  "%'.$cadena.'%" ) OR 
                        ( a.almacen LIKE  "%'.$cadena.'%" ) OR (p.nombre LIKE  "%'.$cadena.'%") OR 
                        (ca.nombre LIKE  "%'.$cadena.'%") OR 
                        ((DATE_FORMAT((m.fecha_salida),"%d-%m-%Y %H:%i") ) LIKE  "%'.$cadena.'%") OR
                        (tf.tipo_factura LIKE  "%'.$cadena.'%") OR (tp.tipo_pedido LIKE  "%'.$cadena.'%") OR 
                        (m.factura LIKE  "%'.$cadena.'%")                         
                       )



            )';   



          $where_total= $where;
                  /*'(
                         ( m.id_operacion = '.$data["id_operacion"].' ) '.$fechas.$id_almacenid.$id_facturaid.' 
                      )';*/
           

          $this->db->where($where);          

          $this->db->group_by('m.mov_salida,m.id_tipo_pedido,m.id_tipo_factura,m.id_almacen,m.id_cliente,m.id_estatus'); //m.factura,

          
          //ordenacion
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



                              if ($row->id_tipo_factura!=0) {
                                $tipo_salida=$row->tipo_factura;
                              } else {
                                $tipo_salida=$row->tipo_pedido;
                              }


                              if ($row->apartado==3) {
                                 $num=$row->consecutivo_venta;
                                 $client=$row->cliente_apartado;

                              } else  {
                                 $num= $row->id_cliente_apartado;
                                 $client=$row->cliente_pedido;
                              }   

                               $dato[]= array(
                                      0=>$row->mov_salida,
                                      1=>$num,
                                      2=>$row->almacen,
                                      3=>$row->cliente,
                                      4=>$row->cargador,
                                      5=>$row->fecha,
                                      6=>$tipo_salida, //'tf.tipo_factura,tp.tipo_pedido';
                                      7=>$row->factura,
                                      8=>number_format($row->sum_precio, 2, '.', ','),
                                      9=>number_format($row->sum_iva, 2, '.', ','),
                                      10=>number_format($row->sum_total, 2, '.', ','),
                                      11=>$row->id_tipo_pedido,
                                      12=>$row->id_tipo_factura,
                                      13=>$client,
                                      14=>$row->id_estatus,

                                    );
                      }





                      return json_encode ( array(
                        "draw"            => intval( $data['draw'] ),
                        "recordsTotal"    =>intval( self::total_historico_salida($where_total) ), 
                        "recordsFiltered" =>   $registros_filtrados, 
                        "data"            =>  $dato,
                        "totales_importe"            =>  array(
                              "subtotal"=>floatval( self::totales_importes_salida($where_total)->subtotal ), 
                              "iva"=>floatval( self::totales_importes_salida($where_total)->iva ), 
                              "total"=>floatval( self::totales_importes_salida($where_total)->total ),
                        ),                           
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
       


   
public function totales_importes_salida($where){

           $this->db->select("SUM(m.precio*m.cantidad_um) as subtotal", FALSE);
           $this->db->select("(SUM(m.precio*m.cantidad_um*m.iva))/100 as iva", FALSE);
           $this->db->select("SUM(m.precio*m.cantidad_um)+(SUM(m.precio*m.cantidad_um*m.iva))/100 as total", FALSE);
   
         
         /* $this->db->from($this->historico_registros_salidas.' as m');
          $this->db->join($this->almacenes.' As a' , 'a.id = m.id_almacen AND a.activo=1');
          $this->db->join($this->proveedores.' As p' , 'p.id = m.id_cliente','LEFT');
          $this->db->join($this->cargadores.' As ca' , 'ca.id = m.id_cargador','LEFT');
          $this->db->join($this->tipos_pedidos.' As tp' , 'tp.id = m.id_tipo_pedido','LEFT');
          $this->db->join($this->tipos_facturas.' As tf' , 'tf.id = m.id_tipo_factura','LEFT');
          */

           $this->db->from($this->historico_registros_salidas.' as m');
          $this->db->join($this->almacenes.' As a' , 'a.id = m.id_almacen AND a.activo=1');
          $this->db->join($this->usuarios.' As u' , 'u.id = m.id_usuario_salida','LEFT');
          $this->db->join($this->proveedores.' As p' , 'p.id = m.id_cliente','LEFT');
          $this->db->join($this->cargadores.' As ca' , 'ca.id = m.id_cargador','LEFT');          
          $this->db->join($this->tipos_pedidos.' As tp' , 'tp.id = m.id_tipo_pedido','LEFT');
          $this->db->join($this->tipos_facturas.' As tf' , 'tf.id = m.id_tipo_factura','LEFT');
          $this->db->join($this->proveedores.' As prov_pedido' , 'prov_pedido.id = m.consecutivo_venta','LEFT');
          $this->db->join($this->proveedores.' As prov_apartado' , 'prov_apartado.id = m.id_cliente_apartado','LEFT');
          

          



          $this->db->where($where);


          $result = $this->db->get();
      
          if ( $result->num_rows() > 0 )
             return $result->row();
          else
             return False;
          $result->free_result();              

    }       
 public function total_historico_salida($where){
              $this->db->select("SQL_CALC_FOUND_ROWS *", FALSE); //
               
                $this->db->from($this->historico_registros_salidas.' as m');
                $this->db->join($this->almacenes.' As a' , 'a.id = m.id_almacen AND a.activo=1');
                $this->db->join($this->usuarios.' As u' , 'u.id = m.id_usuario_salida','LEFT');
                $this->db->join($this->proveedores.' As p' , 'p.id = m.id_cliente','LEFT');
                $this->db->join($this->cargadores.' As ca' , 'ca.id = m.id_cargador','LEFT');          
                $this->db->join($this->tipos_pedidos.' As tp' , 'tp.id = m.id_tipo_pedido','LEFT');
                $this->db->join($this->tipos_facturas.' As tf' , 'tf.id = m.id_tipo_factura','LEFT');
                $this->db->join($this->proveedores.' As prov_pedido' , 'prov_pedido.id = m.consecutivo_venta','LEFT');
                $this->db->join($this->proveedores.' As prov_apartado' , 'prov_apartado.id = m.id_cliente_apartado','LEFT');
                




              $this->db->where($where);          

              //$this->db->group_by('m.mov_salida,m.id_almacen,m.id_cliente,m.id_estatus'); //m.factura,
              $this->db->group_by('m.mov_salida,m.id_tipo_pedido,m.id_tipo_factura,m.id_almacen,m.id_cliente,m.id_estatus'); //m.factura,


             $result = $this->db->get();

              if ( $result->num_rows() > 0 ) {
                  $cantidad_consulta = $this->db->query("SELECT FOUND_ROWS() as cantidad");
                  $found_rows = $cantidad_consulta->row(); 
                  $registros_filtrados =  ( (int) $found_rows->cantidad);
              }  
              
              $cant = $registros_filtrados;
     
              if ( $cant > 0 )
                 return $cant;
              else
                 return 0;         
 }





























//////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////Reportes/////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////

    
 



  } 
?>
<?php if(! defined('BASEPATH')) exit('No tienes permiso para acceder a este archivo');

  class modelo_ctasxpagar extends CI_Model{
    
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


      public function editar_pago_realizado($data){
          
          $this->db->select('h.id, h.movimiento, h.id_documento_pago, h.instrumento_pago,  h.importe, h.comentario');
          $this->db->select("(DATE_FORMAT(h.fecha_pago,'%d-%m-%Y')) as fecha_pago",false);
          $this->db->from($this->historico_pagos_realizados.' as h');
          $where = '(
                          ( h.id =  "'.$data["id"].'" ) 
          )';   
  
          $this->db->where($where);

          $result = $this->db->get();

            if ( $result->num_rows() > 0 )
               return $result->row();
            else
               return False;
            $result->free_result();
      }    


      public function anadir_pago( $data ){

        $id_session = $this->session->userdata('id');
        $this->db->set( 'id_usuario',  $id_session );
        
        $this->db->set( 'movimiento', $data['movimiento'] );  
        $this->db->set( 'id_documento_pago', $data['id_documento_pago'] );  
        $this->db->set( 'instrumento_pago', $data['instrumento_pago'] );  
        $this->db->set( 'importe', $data['importe'] );  
        $this->db->set( 'comentario', $data['comentario'] );  
        $this->db->set( 'fecha_pago', $data['fecha_pago'] );  


          $this->db->insert($this->historico_pagos_realizados );
          if ($this->db->affected_rows() > 0){
                  return TRUE;
              } else {
                  return FALSE;
              }
              $result->free_result();
      }          

      public function editar_pago( $data ){

        $id_session = $this->session->userdata('id');
        $this->db->set( 'id_usuario',  $id_session );
        
        //$this->db->set( 'movimiento', $data['movimiento'] );  
        $this->db->set( 'id_documento_pago', $data['id_documento_pago'] );  
        $this->db->set( 'instrumento_pago', $data['instrumento_pago'] );  
        $this->db->set( 'importe', $data['importe'] );  
        $this->db->set( 'comentario', $data['comentario'] );  
        $this->db->set( 'fecha_pago', $data['fecha_pago'] );  

          $this->db->where( 'id', $data['id'] );  


          $this->db->update($this->historico_pagos_realizados );
          if ($this->db->affected_rows() > 0){
                  return TRUE;
              } else {
                  return FALSE;
              }
              $result->free_result();
      }          


        public function eliminar_pago( $data ){
            $this->db->delete( $this->historico_pagos_realizados, array( 'id' => $data['id'] ) );
            if ( $this->db->affected_rows() > 0 ) return TRUE;
            else return FALSE;
        }










/////////////////////////////////ctasxpagar/////////////////////////////////////////

public function buscador_ctasxpagar($data){

          $cadena = addslashes($data['search']['value']);
          $inicio = $data['start'];
          $largo = $data['length'];
          

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
                        $columna = 'subtotal';
                     break;
                   case '7':
                        $columna = 'iva';
                     break;
                   case '8':
                        $columna = 'total';
                     break;


                   default:
                        $columna = 'm.movimiento';
                        //$this->db->order_by('m.movimiento', 'desc'); 
                     break;
                 }                 

                                      

          $id_session = $this->db->escape($this->session->userdata('id'));

          $this->db->select("SQL_CALC_FOUND_ROWS *", FALSE); //


          $this->db->select('m.movimiento');
          $this->db->select('a.almacen');
          $this->db->select('p.nombre, m.factura,tp.tipo_pago,m.id_tipo_pago');

          $this->db->select("MAX(DATE_FORMAT(m.fecha_entrada,'%d-%m-%Y %H:%i')) as fecha",false);

          
          $this->db->select('p.dias_ctas_pagar');   
          $this->db->select("DATEDIFF( NOW( ) ,  fecha_entrada ) as diferencia_dias", false);                    
          $this->db->select('subtotal');           
          $this->db->select("iva", FALSE);
          $this->db->select("total", FALSE);

          $this->db->select("total-sum(pr.importe) AS monto_restante", FALSE);



          

          $this->db->from($this->historico_ctasxpagar.' as m');
          $this->db->join($this->proveedores.' As p' , 'p.id = m.id_empresa','LEFT');
          $this->db->join($this->almacenes.' As a' , 'a.id = m.id_almacen','LEFT');
          $this->db->join($this->catalogo_tipos_pagos.' As tp' , 'tp.id = m.id_tipo_pago','LEFT');
          $this->db->join($this->historico_pagos_realizados.' As pr' , 'pr.movimiento = m.movimiento','LEFT');
          


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
                          
                          

                         ( m.id_operacion = '.$data["id_operacion"].' ) '.$data["condicion"].$fechas.' 
                         
                      ) 

                       AND
                      (  ( m.movimiento LIKE  "%'.$cadena.'%" )OR 
                        ( tp.tipo_pago LIKE  "%'.$cadena.'%" ) OR 
                        ( a.almacen LIKE  "%'.$cadena.'%" ) OR (p.nombre LIKE  "%'.$cadena.'%") OR 
                        ((DATE_FORMAT((m.fecha_entrada),"%d-%m-%Y %H:%i") ) LIKE  "%'.$cadena.'%") OR
                        (m.factura LIKE  "%'.$cadena.'%")                         
                       )



            )';   





          $where_total= '(
                         ( m.id_operacion = '.$data["id_operacion"].' )'.$fechas.'   
                      )';
           

          $this->db->where($where);          

          $this->db->group_by('m.movimiento,m.id_almacen,m.id_empresa,m.factura');

          
          $this->db->having($data['having']);
          

          
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
                                      6=>number_format($row->subtotal, 2, '.', ','),
                                      7=>number_format($row->iva, 2, '.', ','),
                                      8=>number_format($row->total, 2, '.', ','),
                                      9=>abs($row->diferencia_dias-$row->dias_ctas_pagar),
                                      10=>(($row->monto_restante==null) ? $row->total : $row->monto_restante),
                                      11=>$row->id_tipo_pago,
                                      

                                    );
                      }




                      return json_encode ( array(
                        "draw"            => intval( $data['draw'] ),
                        "recordsTotal"    =>intval( self::total_ctasxpagar($where_total,$data['having']) ), 
                        "recordsFiltered" =>   $registros_filtrados, 
                        "data"            =>  $dato 
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
       


 public function total_ctasxpagar($where,$having){
              

              $this->db->select("SQL_CALC_FOUND_ROWS *", FALSE); //
             
              $this->db->select("total-sum(pr.importe) AS monto_restante", FALSE);

              $this->db->from($this->historico_ctasxpagar.' as m');
              $this->db->join($this->proveedores.' As p' , 'p.id = m.id_empresa','LEFT');
              $this->db->join($this->almacenes.' As a' , 'a.id = m.id_almacen','LEFT');
              $this->db->join($this->historico_pagos_realizados.' As pr' , 'pr.movimiento = m.movimiento','LEFT');
          
              $this->db->where($where);          
              $this->db->group_by('m.movimiento,m.id_almacen,m.id_empresa,m.factura');
              $this->db->having($having);
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





public function encabezado_pagosrealizados($data){

           $this->db->distinct();
         $this->db->select('m.movimiento');
          $this->db->select('a.almacen');
          $this->db->select('p.nombre, m.factura,tp.tipo_pago');

          $this->db->select("MAX(DATE_FORMAT(m.fecha_entrada,'%d-%m-%Y %H:%i')) as fecha",false);

          
          $this->db->select('p.dias_ctas_pagar');   
          $this->db->select("DATEDIFF( NOW( ) ,  fecha_entrada ) as diferencia_dias", false);                    
          $this->db->select('subtotal');           
          $this->db->select("iva", FALSE);
          $this->db->select("total", FALSE);

          $this->db->select("total-sum(pr.importe) AS monto_restante", FALSE);



          

          $this->db->from($this->historico_ctasxpagar.' as m');
          $this->db->join($this->proveedores.' As p' , 'p.id = m.id_empresa','LEFT');
          $this->db->join($this->almacenes.' As a' , 'a.id = m.id_almacen','LEFT');
          $this->db->join($this->catalogo_tipos_pagos.' As tp' , 'tp.id = m.id_tipo_pago','LEFT');
          $this->db->join($this->historico_pagos_realizados.' As pr' , 'pr.movimiento = m.movimiento','LEFT');
          
         

          $where = '(
                      (
                         ( m.movimiento = '.$data["movimiento"].' )
                      ) 
          )';   
           

         $this->db->where($where);          

         //$this->db->limit(1,1); 

          $result = $this->db->get();

              if ( $result->num_rows() > 0 ) {

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
                              
                  }


                return  json_encode($totales);                 
                    
              }   
                        

      }  
       




public function buscador_pagosrealizados($data){

          $cadena = addslashes($data['search']['value']);
          $inicio = $data['start'];
          $largo = $data['length'];

     //activar nuevo, editar  y eliminar
     //<!-- si configuracion lo tiene activo y es(administrador o por el contrario tiene "permiso de ver y editar") -->     
     $perfil= $this->session->userdata('id_perfil'); 
     $coleccion_id_operaciones= json_decode($this->session->userdata('coleccion_id_operaciones')); 
     if ( (count($coleccion_id_operaciones)==0) || (!($coleccion_id_operaciones)) ) {
          $coleccion_id_operaciones = array();
     }   
     $activar = (($data['configuracion']->activo==1) and ( ( $perfil == 1 ) || ((in_array(27, $coleccion_id_operaciones)) && (in_array(28, $coleccion_id_operaciones)))  ));



          

          $columa_order = $data['order'][0]['column'];
                 $order = $data['order'][0]['dir'];

           if ($data['draw'] ==1) { //que se ordene por el ultimo
                 $columa_order ='-1';
                 $order = 'desc';
           } 

          switch ($columa_order) {
                   case '0':
                        $columna = 'dp.documento_pago';
                     break;
                   case '1':
                        $columna = 'pr.instrumento_pago';
                     break;
                   case '2':
                        $columna = 'pr.fecha_pago';
                     break;
                   case '3':
                        $columna = 'pr.importe';
                     break;                     
                   case '4':
                        $columna = 'pr.comentario';
                     break;

                   default:
                        $columna = 'pr.fecha_pago';
                        //$this->db->order_by('m.movimiento', 'desc'); 
                     break;
                 }                 

                                      

          $id_session = $this->db->escape($this->session->userdata('id'));

          $this->db->select("SQL_CALC_FOUND_ROWS *", FALSE); //

          $this->db->select('pr.id,pr.movimiento, pr.id_documento_pago, pr.instrumento_pago,  pr.importe, pr.comentario');
          $this->db->select("(DATE_FORMAT(pr.fecha_pago,'%d-%m-%Y')) as fecha_pago",false);
          $this->db->select('dp.documento_pago');
          
          $this->db->select('a.almacen');
          $this->db->select('p.nombre, m.factura,tp.tipo_pago');

          $this->db->select("MAX(DATE_FORMAT(m.fecha_entrada,'%d-%m-%Y')) as fecha",false);

          
          $this->db->select('p.dias_ctas_pagar');   
          $this->db->select("DATEDIFF( NOW( ) ,  m.fecha_entrada ) as diferencia_dias", false);                    
          $this->db->select('subtotal');           
          $this->db->select("iva", FALSE);
          $this->db->select("total", FALSE);

          $this->db->select("m.total-sum(pri.importe) AS monto_restante", FALSE);

          $this->db->select("DATEDIFF( pr.fecha_pago ,  m.fecha_entrada ) as pagos_tardios", false);                    

          
          
          $this->db->from($this->historico_pagos_realizados.' as pr');

          
          $this->db->join($this->historico_ctasxpagar.' As m' , 'm.movimiento = pr.movimiento','LEFT');
          $this->db->join($this->proveedores.' As p' , 'p.id = m.id_empresa','LEFT');
          $this->db->join($this->almacenes.' As a' , 'a.id = m.id_almacen','LEFT');
          $this->db->join($this->catalogo_tipos_pagos.' As tp' , 'tp.id = m.id_tipo_pago','LEFT');
          $this->db->join($this->documentos_pagos.' As dp' , 'dp.id = pr.id_documento_pago','LEFT');
          $this->db->join($this->historico_pagos_realizados.' As pri' , 'pri.movimiento = m.movimiento','LEFT');
          
          
          

          $where = '(
                      (
                         ( m.movimiento = '.$data["movimiento"].' )
                         
                      ) 

                       AND
                      (  
                        (  dp.documento_pago LIKE  "%'.$cadena.'%" ) OR 
                        ( pr.instrumento_pago LIKE  "%'.$cadena.'%" ) OR (pr.importe LIKE  "%'.$cadena.'%") OR 
                        ((DATE_FORMAT((pr.fecha_pago),"%d-%m-%Y %H:%i") ) LIKE  "%'.$cadena.'%") OR
                        (pr.comentario LIKE  "%'.$cadena.'%")                         
                       )



            )';   





          $where_total= '(
                         ( m.id_operacion = '.$data["id_operacion"].' )   
                      )';
           

          $this->db->where($where);          

         $this->db->group_by('pr.id'); //,m.id_almacen,m.id_empresa,m.factura

          
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

      }  
       


 public function total_pagosrealizados($where){
              

              $this->db->select("SQL_CALC_FOUND_ROWS *", FALSE); //
             
              $this->db->from($this->historico_ctasxpagar.' as m');
              $this->db->join($this->proveedores.' As p' , 'p.id = m.id_empresa','LEFT');
              $this->db->join($this->almacenes.' As a' , 'a.id = m.id_almacen','LEFT');
          
              $this->db->where($where);          
             // $this->db->group_by('m.movimiento,m.id_almacen,m.id_empresa,m.factura');
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

        











public function impresion_ctasxpagar($data){

          $cadena = addslashes($data['busqueda']);          


          $id_session = $this->db->escape($this->session->userdata('id'));


          $this->db->select('m.movimiento');
          $this->db->select('a.almacen');
          $this->db->select('p.nombre, m.factura,tp.tipo_pago,m.id_tipo_pago');

          $this->db->select("MAX(DATE_FORMAT(m.fecha_entrada,'%d-%m-%Y')) as fecha",false);

          
          $this->db->select('p.dias_ctas_pagar');   
          $this->db->select("DATEDIFF( NOW( ) ,  fecha_entrada ) as diferencia_dias", false);                    
          $this->db->select('subtotal');           
          $this->db->select("iva", FALSE);
          $this->db->select("total", FALSE);

          $this->db->select("total-sum(pr.importe) AS monto_restante", FALSE);
          

          $this->db->from($this->historico_ctasxpagar.' as m');
          $this->db->join($this->proveedores.' As p' , 'p.id = m.id_empresa','LEFT');
          $this->db->join($this->almacenes.' As a' , 'a.id = m.id_almacen','LEFT');
          $this->db->join($this->catalogo_tipos_pagos.' As tp' , 'tp.id = m.id_tipo_pago','LEFT');
          $this->db->join($this->historico_pagos_realizados.' As pr' , 'pr.movimiento = m.movimiento','LEFT');
          


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
                         ( m.id_operacion = '.$data["id_operacion"].' ) '.$data["condicion"].$fechas.' 
                      ) 
                       AND
                      (  ( m.movimiento LIKE  "%'.$cadena.'%" )OR 
                        ( tp.tipo_pago LIKE  "%'.$cadena.'%" ) OR 
                        ( a.almacen LIKE  "%'.$cadena.'%" ) OR (p.nombre LIKE  "%'.$cadena.'%") OR 
                        ((DATE_FORMAT((m.fecha_entrada),"%d-%m-%Y %H:%i") ) LIKE  "%'.$cadena.'%") OR
                        (m.factura LIKE  "%'.$cadena.'%")                         
                       )
            )';   


          $where_total= '(
                         ( m.id_operacion = '.$data["id_operacion"].' )'.$fechas.'   
                      )';
           

          $this->db->where($where);          

          $this->db->group_by('m.movimiento,m.id_almacen,m.id_empresa,m.factura');

          
          $this->db->having($data['having']);
          
          //ordenacion
          $this->db->order_by('m.fecha_entrada', 'DESC'); 

            $result = $this->db->get();


            if ( $result->num_rows() > 0 )
               return $result->result();
            else
               return False;
            $result->free_result();

      }  
       





public function exportar_ctasxpagar($data){

          $cadena = addslashes($data['busqueda']);          


          $id_session = $this->db->escape($this->session->userdata('id'));

          


          $this->db->select('m.movimiento');
          $this->db->select('a.almacen');
          $this->db->select('p.nombre, m.factura,tp.tipo_pago');

          $this->db->select("MAX(DATE_FORMAT(m.fecha_entrada,'%d-%m-%Y')) as fecha",false);

          
          $this->db->select('p.dias_ctas_pagar');   
          $this->db->select("DATEDIFF( NOW( ) ,  fecha_entrada ) as diferencia_dias", false);                    
          $this->db->select('subtotal');           
          $this->db->select("iva", FALSE);
          $this->db->select("total", FALSE);

          $this->db->select("total-sum(pr.importe) AS monto_restante", FALSE);
          

          $this->db->from($this->historico_ctasxpagar.' as m');
          $this->db->join($this->proveedores.' As p' , 'p.id = m.id_empresa','LEFT');
          $this->db->join($this->almacenes.' As a' , 'a.id = m.id_almacen','LEFT');
          $this->db->join($this->catalogo_tipos_pagos.' As tp' , 'tp.id = m.id_tipo_pago','LEFT');
          $this->db->join($this->historico_pagos_realizados.' As pr' , 'pr.movimiento = m.movimiento','LEFT');
          


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
                         ( m.id_operacion = '.$data["id_operacion"].' ) '.$data["condicion"].$fechas.' 
                      ) 
                       AND
                      (  ( m.movimiento LIKE  "%'.$cadena.'%" )OR 
                        ( tp.tipo_pago LIKE  "%'.$cadena.'%" ) OR 
                        ( a.almacen LIKE  "%'.$cadena.'%" ) OR (p.nombre LIKE  "%'.$cadena.'%") OR 
                        ((DATE_FORMAT((m.fecha_entrada),"%d-%m-%Y %H:%i") ) LIKE  "%'.$cadena.'%") OR
                        (m.factura LIKE  "%'.$cadena.'%")                         
                       )
            )';   


          $where_total= '(
                         ( m.id_operacion = '.$data["id_operacion"].' )'.$fechas.'   
                      )';
           

          $this->db->where($where);          

          $this->db->group_by('m.movimiento,m.id_almacen,m.id_empresa,m.factura');

          
          $this->db->having($data['having']);
          
          //ordenacion
          $this->db->order_by('m.fecha_entrada', 'DESC'); 

            $result = $this->db->get();


            if ( $result->num_rows() > 0 )
               return $result->result();
            else
               return False;
            $result->free_result();

      }  
       






  } 
?>
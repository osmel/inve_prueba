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
      $this->usuarios                 = $this->db->dbprefix('usuarios');
        //catalogos     
      $this->actividad_comercial      = $this->db->dbprefix('catalogo_actividad_comercial');
      $this->cargadores               = $this->db->dbprefix('catalogo_cargador');
      
      $this->estratificacion_empresa  = $this->db->dbprefix('catalogo_estratificacion_empresa');
      
      $this->productos                = $this->db->dbprefix('catalogo_productos');
      $this->proveedores              = $this->db->dbprefix('catalogo_empresas');
      $this->unidades_medidas         = $this->db->dbprefix('catalogo_unidades_medidas');

      $this->operaciones              = $this->db->dbprefix('catalogo_operaciones');
      $this->movimientos              = $this->db->dbprefix('movimientos');
      $this->registros_temporales               = $this->db->dbprefix('temporal_registros');
      $this->registros                = $this->db->dbprefix('registros_entradas');
      

      $this->colores                  = $this->db->dbprefix('catalogo_colores');
      
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
     


  //$this->db->select("DATE_FORMAT(DATE_ADD(fecha_entrada, INTERVAL p.dias_ctas_pagar DAY), '%Y-%m-%d') as fecha_ven", false);                    


                /*       
select sum(total) from 
  (SELECT (total - SUM( pr.importe )) AS total2, total
  FROM inven_historico_ctasxpagar m
  LEFT JOIN inven_historico_pagos_realizados pr ON pr.movimiento = m.movimiento
  GROUP BY m.movimiento
  HAVING (total - SUM( pr.importe )) <=0
  )
  //$this->db->select("total-sum(pr.importe) AS monto_restante", FALSE);
           //$this->db->select("total-sum(pr.importe) AS monto_restante", FALSE);
*/
                  //cuando este vacio la tabla que envie este
                //http://www.datatables.net/forums/discussion/21311/empty-ajax-response-wont-render-in-datatables-1-10
          //recargo=12
          //$this->db->select("total-sum(pr.importe) AS monto_restante", FALSE);
/*
          $this->db->select("MAX(DATE_FORMAT(pr.fecha_pago,'%d-%m-%Y')) as fecha_pagada",false);
          $this->db->select("DATE_FORMAT(DATE_ADD(fecha_entrada, INTERVAL p.dias_ctas_pagar DAY), '%d-%m-%Y') as fecha_vencimiento", false);                    

*/


/////////////////////////////////Regilla principal/////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

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

          $id_almacen= $data['id_almacen'];
          $id_factura= $data['id_factura'];


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
                        $columna = 'm.total';
                     break;


                   default:
                        $columna = 'm.movimiento';
                        //$this->db->order_by('m.movimiento', 'desc'); 
                     break;
                 }                 

                                      

          $id_session = $this->db->escape($this->session->userdata('id'));

          $this->db->select("SQL_CALC_FOUND_ROWS *", FALSE); //


          $this->db->select('m.movimiento');
          $this->db->select('a.almacen,m.id_factura,m.id_fac_orig');
          $this->db->select('p.nombre, m.factura,tp.tipo_pago,m.id_tipo_pago,m.id_estatus');

          $this->db->select("MAX(DATE_FORMAT(m.fecha_entrada,'%d-%m-%Y')) as fecha",false);
          $this->db->select('p.dias_ctas_pagar');   
          $this->db->select("DATEDIFF( NOW( ) ,  fecha_entrada ) as diferencia_dias", false);                    
          $this->db->select("MAX(DATE_FORMAT(pr.fecha_pago,'%d-%m-%Y')) as fecha_pagada",false);
          $this->db->select("MAX(pr.fecha_pago) as fecha_pago",false);
          $this->db->select("DATE_FORMAT(DATE_ADD(fecha_entrada, INTERVAL p.dias_ctas_pagar DAY), '%d-%m-%Y') as fecha_vencimiento", false);                    
          
          $this->db->select("(DATE_ADD(fecha_entrada, INTERVAL p.dias_ctas_pagar DAY)) as fecha_ven", false);                    
        

          $this->db->select('subtotal');           
          $this->db->select("iva", FALSE);
          $this->db->select("m.total", FALSE);

          $this->db->select("m.total+sum((pr.id_documento_pago <> 12)*pr.importe*-1)+sum((pr.id_documento_pago = 12)*pr.importe) AS monto_restante", FALSE);
              
          //esto es para que de el monto que tuve q pagar
          $this->db->select("sum(  ( (pr.id_documento_pago <> 12) && (pr.id_documento_pago <> 13) ) *pr.importe)  AS sepago", FALSE);
          


           
          $this->db->from($this->historico_ctasxpagar.' as m');
          $this->db->join($this->almacenes.' As a' , 'a.id = m.id_almacen AND a.activo=1');
          $this->db->join($this->proveedores.' As p' , 'p.id = m.id_empresa','LEFT');          
          $this->db->join($this->catalogo_tipos_pagos.' As tp' , 'tp.id = m.id_tipo_pago','LEFT');
          //$this->db->join($this->historico_pagos_realizados.' As pr' , 'pr.movimiento = m.movimiento AND pr.id_factura=m.id_factura','LEFT');
          $this->db->join($this->historico_pagos_realizados.' As pr' , 'pr.movimiento = m.movimiento AND pr.id_factura=m.id_factura','LEFT');
           


          $fechas = ' ';
          if  ( ($data['fecha_inicial'] !="") and  ($data['fecha_final'] !="")) {
                           $fecha_inicial = date( 'Y-m-d', strtotime( $data['fecha_inicial'] ));
                           $fecha_final = date( 'Y-m-d', strtotime( $data['fecha_final'] ));
                          
                            $fechas .= ' AND ( ( DATE_FORMAT((m.fecha_entrada),"%Y-%m-%d")  >=  "'.$fecha_inicial.'" )  AND  ( DATE_FORMAT((m.fecha_entrada),"%Y-%m-%d")  <=  "'.$fecha_final.'" ) )'; 

          } else {
           $fechas .= ' ';
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


        if ( (addslashes($data['proveedor'])!="")  && (addslashes($data['proveedor'])!= null) ) {
            $proveedorid= 'and ( p.nombre =  "'.addslashes($data['proveedor']).'" ) ';
          } else {
            $proveedorid = '';
          }

          


          $where = '(
                      (
                          
                          

                         ( m.id_operacion = '.$data["id_operacion"].' ) '.$data["condicion"].$fechas.$id_almacenid.$id_facturaid.$proveedorid.' 
                         
                      ) 

                       AND
                      (  ( m.movimiento LIKE  "%'.$cadena.'%" )OR 
                        ( tp.tipo_pago LIKE  "%'.$cadena.'%" ) OR 
                        ( a.almacen LIKE  "%'.$cadena.'%" ) OR (p.nombre LIKE  "%'.$cadena.'%") OR 
                        ((DATE_FORMAT((m.fecha_entrada),"%d-%m-%Y %H:%i") ) LIKE  "%'.$cadena.'%") OR
                        (m.factura LIKE  "%'.$cadena.'%")                         
                       )



            )';   





          $where_total=$where;

          $this->db->where($where);          

          $this->db->group_by('m.movimiento,m.id_factura, m.id_almacen,m.id_empresa'); //,m.factura

          
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
                        if ($data['tipo']=='pagadas') {
                          $fecha_filtro=$row->fecha_pagada;
                        } else {
                          $fecha_filtro=$row->fecha_vencimiento;
                        }
                               $dato[]= array(
                                      0=>$row->movimiento,
                                      1=>$row->tipo_pago,
                                      2=>$row->almacen,
                                      3=>$row->nombre,
                                      4=>$row->fecha,
                                      5=>$fecha_filtro, //dife
                                      6=>$row->factura,
                                      7=>number_format($row->subtotal, 2, '.', ','),
                                      8=>number_format($row->iva, 2, '.', ','),
                                      9=>number_format($row->total, 2, '.', ','),
                                      10=>abs($row->diferencia_dias-$row->dias_ctas_pagar),
                                      11=>(($row->monto_restante==null) ? $row->total : $row->monto_restante),
                                      12=>$row->id_tipo_pago,
                                      13=>(($row->sepago==null) ? 0 : $row->sepago),
                                      14=>$row->id_factura,
                                      15=>$row->id_estatus,
                                      

                                    );
                      }




                      return json_encode ( array(
                        "draw"            => intval( $data['draw'] ),
                        "recordsTotal"    =>intval( self::total_ctasxpagar($where_total,$data['having']) ), 
                        "recordsFiltered" =>   $registros_filtrados, 
                        "data"            =>  $dato,
                        "totales_importe"            => json_decode(self::totales_importes($where_total,$data['having'])),
                     
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



public function totales_importes($where,$having){
           
          $this->db->select("MAX(DATE_FORMAT(pr.fecha_pago,'%d-%m-%Y')) as fecha_pagada",false);
          $this->db->select("(DATE_ADD(fecha_entrada, INTERVAL p.dias_ctas_pagar DAY)) as fecha_ven", false);        
          $this->db->select("MAX(pr.fecha_pago) as fecha_pago",false);
          $this->db->select("DATE_FORMAT(DATE_ADD(fecha_entrada, INTERVAL p.dias_ctas_pagar DAY), '%d-%m-%Y') as fecha_vencimiento", false);                    


            $this->db->select("m.total+sum((pr.id_documento_pago <> 12)*pr.importe*-1)+sum((pr.id_documento_pago = 12)*pr.importe) AS monto_restante", FALSE);


           $this->db->select("(m.id_tipo_pago) AS id_tipo_pago", FALSE);
           $this->db->select("(subtotal) as subtotal", FALSE);
           $this->db->select("(iva) as iva", FALSE);
           $this->db->select("(m.total) as total", FALSE);
   
           $this->db->from($this->historico_ctasxpagar.' as m');
           $this->db->join($this->almacenes.' As a' , 'a.id = m.id_almacen AND a.activo=1');
           $this->db->join($this->proveedores.' As p' , 'p.id = m.id_empresa','LEFT');           
           $this->db->join($this->catalogo_tipos_pagos.' As tp' , 'tp.id = m.id_tipo_pago','LEFT');      
           //$this->db->join($this->historico_pagos_realizados.' As pr' , 'pr.movimiento = m.movimiento','LEFT');
           $this->db->join($this->historico_pagos_realizados.' As pr' , 'pr.movimiento = m.movimiento AND pr.id_factura=m.id_factura','LEFT');

           $this->db->where($where);          
           $this->db->group_by('m.movimiento,m.id_factura'); //,m.id_almacen,m.id_empresa,m.factura
           $this->db->having($having);

           $result = $this->db->get();
      
              $subtotal =0;
              $iva =0;
              $total =0;
           if ( $result->num_rows() > 0 ) {
              
              foreach ($result->result() as $row) {
                        $subtotal+=$row->subtotal;
                        $iva+=$row->iva;
                        $total+=$row->total;
              }

              return  json_encode(array(
                                "subtotal"=>$subtotal,
                                "iva"=>$iva,
                                "total"=>$total,
                                ));

           }
           else
             return False;
           $result->free_result();              
    }         


 public function total_ctasxpagar($where,$having){
              

              $this->db->select("SQL_CALC_FOUND_ROWS *", FALSE); //
             
             
              $this->db->select("m.total+sum((pr.id_documento_pago <> 12)*pr.importe*-1)+sum((pr.id_documento_pago = 12)*pr.importe) AS monto_restante", FALSE);

  
          $this->db->select("MAX(DATE_FORMAT(pr.fecha_pago,'%d-%m-%Y')) as fecha_pagada",false);
          $this->db->select("MAX(pr.fecha_pago) as fecha_pago",false);
          $this->db->select("(DATE_ADD(fecha_entrada, INTERVAL p.dias_ctas_pagar DAY)) as fecha_ven", false);        
          $this->db->select("DATE_FORMAT(DATE_ADD(fecha_entrada, INTERVAL p.dias_ctas_pagar DAY), '%d-%m-%Y') as fecha_vencimiento", false);                    


            $this->db->from($this->historico_ctasxpagar.' as m');
            $this->db->join($this->almacenes.' As a' , 'a.id = m.id_almacen AND a.activo=1');
            $this->db->join($this->proveedores.' As p' , 'p.id = m.id_empresa','LEFT');            
            $this->db->join($this->catalogo_tipos_pagos.' As tp' , 'tp.id = m.id_tipo_pago','LEFT');
            $this->db->join($this->historico_pagos_realizados.' As pr' , 'pr.movimiento = m.movimiento AND pr.id_factura=m.id_factura','LEFT');

          
              $this->db->where($where);          
              $this->db->group_by('m.movimiento,m.id_factura,m.id_almacen,m.id_empresa,m.factura');
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


////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////Detalle MONTO////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//regilla que esta en nuevo pago
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
     $activar_nuevo = (($data['configuracion']->activo==1) and ( ( $perfil == 1 ) || (( (in_array(29, $coleccion_id_operaciones)) || (in_array(30, $coleccion_id_operaciones)) ) && (in_array(28, $coleccion_id_operaciones)))  ));

  $activar_modificar = (($data['configuracion']->activo==1) and ( ( $perfil == 1 ) || (( (in_array(29, $coleccion_id_operaciones)) || (in_array(30, $coleccion_id_operaciones)) ) && (in_array(31, $coleccion_id_operaciones)))  ));

  $activar_eliminar = (($data['configuracion']->activo==1) and ( ( $perfil == 1 ) || (( (in_array(29, $coleccion_id_operaciones)) || (in_array(30, $coleccion_id_operaciones)) ) && (in_array(32, $coleccion_id_operaciones)))  ));



          

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
                        
                     break;
                 }                 

                                      

          $id_session = $this->db->escape($this->session->userdata('id'));

          $this->db->select("SQL_CALC_FOUND_ROWS *", FALSE); //

          $this->db->select('pr.id,pr.movimiento, pr.id_documento_pago, pr.instrumento_pago,  pr.importe, pr.comentario');
          $this->db->select("(DATE_FORMAT(pr.fecha_pago,'%d-%m-%Y')) as fecha_pago",false);
          $this->db->select('dp.documento_pago,m.id_factura,m.id_fac_orig');
          
          $this->db->select('a.almacen');
          $this->db->select('p.nombre, m.factura,tp.tipo_pago');

          $this->db->select("MAX(DATE_FORMAT(m.fecha_entrada,'%d-%m-%Y')) as fecha",false);

          
          $this->db->select('p.dias_ctas_pagar');   
          $this->db->select("DATEDIFF( NOW( ) ,  m.fecha_entrada ) as diferencia_dias", false);                    
          $this->db->select('subtotal');           
          $this->db->select("iva", FALSE);
          $this->db->select("m.total", FALSE);

          $this->db->select("m.total+sum((pri.id_documento_pago <> 12)*pri.importe*-1)+sum((pri.id_documento_pago = 12)*pri.importe) AS monto_restante", FALSE);

          $this->db->select("DATEDIFF( pr.fecha_pago ,  m.fecha_entrada ) as pagos_tardios", false);                    

          $this->db->select("sum((pri.id_documento_pago = 13)*pri.importe*-1)+sum((pri.id_documento_pago <> 13)*pri.importe) AS pago_importe", FALSE);


          
          $this->db->from($this->historico_pagos_realizados.' as pr');
          $this->db->join($this->historico_ctasxpagar.' As m' , 'm.movimiento = pr.movimiento AND pr.id_factura=m.id_factura','LEFT');
          $this->db->join($this->almacenes.' As a' , 'a.id = m.id_almacen AND a.activo=1');
          $this->db->join($this->proveedores.' As p' , 'p.id = m.id_empresa','LEFT');          
          $this->db->join($this->catalogo_tipos_pagos.' As tp' , 'tp.id = m.id_tipo_pago','LEFT');
          $this->db->join($this->documentos_pagos.' As dp' , 'dp.id = pr.id_documento_pago','LEFT');
          $this->db->join($this->historico_pagos_realizados.' As pri' , 'pri.movimiento = m.movimiento AND pri.id_factura=m.id_factura','LEFT');

          $where = '(
                      (
                         ( pr.movimiento = '.$data["movimiento"].' ) AND ( pr.id_factura = '.$data["id_factura"].' )
                      ) 
                       AND
                      (  
                        (  dp.documento_pago LIKE  "%'.$cadena.'%" ) OR 
                        ( pr.instrumento_pago LIKE  "%'.$cadena.'%" ) OR (pr.importe LIKE  "%'.$cadena.'%") OR 
                        ((DATE_FORMAT((pr.fecha_pago),"%d-%m-%Y %H:%i") ) LIKE  "%'.$cadena.'%") OR
                        (pr.comentario LIKE  "%'.$cadena.'%")                         
                       )
            )';   





          $where_total= $where;

     
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
                                          "monto_restante"=>(($row->monto_restante==null) ? $row->total : $row->monto_restante),
                                          "pago_importe"=>(($row->pago_importe==null) ? $row->total : $row->pago_importe),
                                                     
                                      );

                               $dato[]= array(
                                      
                                      0=>$row->documento_pago,
                                      1=>$row->instrumento_pago,
                                      2=>$row->fecha_pago,
                                      3=>number_format($row->importe, 2, '.', ','),
                                      4=>$row->comentario,
                                      5=>$row->id,
                                      6=>$activar_nuevo,  
                                      7=>( (($row->pagos_tardios-$row->dias_ctas_pagar)<0) ? 1:0), //0->son tardios los pagos
                                      8=>$row->movimiento, 
                                      9=>$row->id_factura,
                                      10=>$activar_modificar,  
                                      11=>$activar_eliminar,   
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
                                      11=>$row->id_factura, 

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
          
          $this->db->from($this->historico_pagos_realizados.' as pr');
          $this->db->join($this->historico_ctasxpagar.' As m' , 'm.movimiento = pr.movimiento AND pr.id_factura=m.id_factura','LEFT');
          $this->db->join($this->almacenes.' As a' , 'a.id = m.id_almacen AND a.activo=1');
          $this->db->join($this->proveedores.' As p' , 'p.id = m.id_empresa','LEFT');          
          $this->db->join($this->catalogo_tipos_pagos.' As tp' , 'tp.id = m.id_tipo_pago','LEFT');
          $this->db->join($this->documentos_pagos.' As dp' , 'dp.id = pr.id_documento_pago','LEFT');
          $this->db->join($this->historico_pagos_realizados.' As pri' , 'pri.movimiento = m.movimiento AND pri.id_factura=m.id_factura','LEFT');
          $this->db->group_by('pr.id');


          
             $this->db->where($where);          
             
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
          $this->db->select("m.total", FALSE);

          
          $this->db->select("m.total+sum((pr.id_documento_pago <> 12)*pr.importe*-1)+sum((pr.id_documento_pago = 12)*pr.importe) AS monto_restante", FALSE);




          

          $this->db->from($this->historico_ctasxpagar.' as m');
          $this->db->join($this->almacenes.' As a' , 'a.id = m.id_almacen AND a.activo=1');
          $this->db->join($this->proveedores.' As p' , 'p.id = m.id_empresa','LEFT');          
          $this->db->join($this->catalogo_tipos_pagos.' As tp' , 'tp.id = m.id_tipo_pago','LEFT');
          $this->db->join($this->historico_pagos_realizados.' As pr' , 'pr.movimiento = m.movimiento AND pr.id_factura=m.id_factura','LEFT');

          $where = '(
                      (
                         ( m.movimiento = '.$data["movimiento"].' ) AND ( m.id_factura = '.$data["id_factura"].' )
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
       


////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////Nuevo pago////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        
//////////////////////buscar los datos de un nuevo pago///// 

      public function nuevo_pago_realizado($data){

          $this->db->select('m.total,m.id_factura,m.id_almacen,m.id_empresa');
          $this->db->from($this->historico_ctasxpagar.' as m');
          $where = '(
                          ( m.movimiento =  '.$data["movimiento"].' ) 
                          AND ( m.id_factura =  '.$data["id_factura"].' ) 
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
        //$this->db->set( 'importe', $data['importe'] );  
        $this->db->set( 'importe',            ($data['importe']*( ($data['id_documento_pago'] !=12) && ($data['id_documento_pago'] !=13) )) 
          +((( $data['importe'] * $data['total'] ) /100)*( ($data['id_documento_pago'] ==12) OR ($data['id_documento_pago'] ==13) ) ), false);

        $this->db->set( 'comentario', $data['comentario'] );  
        $this->db->set( 'fecha_pago', $data['fecha_pago'] );  

        $this->db->set( 'id_factura', $data['id_factura'] );  
        $this->db->set( 'id_almacen', $data['id_almacen'] );  
        $this->db->set( 'id_empresa', $data['id_empresa'] );  
        $this->db->set( 'total', $data['total'] );  



          $this->db->insert($this->historico_pagos_realizados );
          if ($this->db->affected_rows() > 0){
                  return TRUE;
              } else {
                  return FALSE;
              }
              $result->free_result();
      }          


////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////EDITAR PAGO////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        


      public function editar_pago_realizado($data){

          $this->db->select("( CASE WHEN ( (h.id_documento_pago !=12) && (h.id_documento_pago !=13)) THEN h.importe ELSE  
            FORMAT(((h.importe*100)/m.total), 2) 
            END ) AS importe", FALSE);   
          //h.importe
          $this->db->select('m.total');
          $this->db->select('h.id, h.movimiento, h.id_documento_pago, h.instrumento_pago, h.comentario, h.id_factura,h.id_almacen,h.id_empresa');
          $this->db->select("(DATE_FORMAT(h.fecha_pago,'%d-%m-%Y')) as fecha_pago",false);
          $this->db->from($this->historico_pagos_realizados.' as h');
          $this->db->join($this->historico_ctasxpagar.' As m' , 'h.movimiento = m.movimiento');
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


     
      public function editar_pago( $data ){

        $id_session = $this->session->userdata('id');
        $this->db->set( 'id_usuario',  $id_session );
        
        $this->db->set( 'id_documento_pago', $data['id_documento_pago'] );  
        $this->db->set( 'importe',            ($data['importe']*( ($data['id_documento_pago'] !=12) && ($data['id_documento_pago'] !=13) )) 
          +((( $data['importe'] * $data['total'] ) /100)*( ($data['id_documento_pago'] ==12) OR ($data['id_documento_pago'] ==13) ) ), false); 
        //$this->db->set( 'importe', $data['importe'] );        
        $this->db->set( 'instrumento_pago', $data['instrumento_pago'] );  
        
        $this->db->set( 'comentario', $data['comentario'] );  
        $this->db->set( 'fecha_pago', $data['fecha_pago'] );  

        $this->db->set( 'id_factura', $data['id_factura'] );  
        $this->db->set( 'id_almacen', $data['id_almacen'] );  
        $this->db->set( 'id_empresa', $data['id_empresa'] );  
        $this->db->set( 'total', $data['total'] );  


          $this->db->where( 'id', $data['id'] );  



          $this->db->update($this->historico_pagos_realizados );
          if ($this->db->affected_rows() > 0){
                  return TRUE;
              } else {
                  return FALSE;
              }
              $result->free_result();
      }          



////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////Eliminar PAGO////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        


        public function eliminar_pago( $data ){
            $this->db->delete( $this->historico_pagos_realizados, array( 'id' => $data['id'],'id_factura' => $data['id_factura'] ) );
            if ( $this->db->affected_rows() > 0 ) return TRUE;
            else return FALSE;
        }




/////////////////////////////////////////Imprimir detalles de los "pagos realizados"/////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

public function impresion_pagosrealizados($data){

          
          $cadena = addslashes($data['busqueda']);

          $this->db->select("pr.id", FALSE);
          //detalle                                      
          $this->db->select('dp.documento_pago,pr.instrumento_pago');                                      
          $this->db->select("(DATE_FORMAT(pr.fecha_pago,'%d-%m-%Y')) as fecha_pago",false);
          $this->db->select('pr.importe, pr.comentario');
         
          
          //encabezado
          $this->db->select('pr.movimiento,  a.almacen, p.nombre');
          $this->db->select("MAX(DATE_FORMAT(m.fecha_entrada,'%d-%m-%Y')) as fecha",false);
          $this->db->select('subtotal');           
          $this->db->select("iva", FALSE);
          $this->db->select("m.total", FALSE);
          //"dias_vencidos"=>abs($row->diferencia_dias-$row->dias_ctas_pagar),
          $this->db->select("DATEDIFF( NOW( ) ,  m.fecha_entrada ) as diferencia_dias", false);   
          $this->db->select('p.dias_ctas_pagar');   
          $this->db->select("m.total+sum((pri.id_documento_pago <> 12)*pri.importe*-1)+sum((pri.id_documento_pago = 12)*pri.importe) AS monto_restante", FALSE);          

          
          $this->db->from($this->historico_pagos_realizados.' as pr');
          $this->db->join($this->historico_ctasxpagar.' As m' , 'm.movimiento = pr.movimiento AND pr.id_factura=m.id_factura','LEFT');
          $this->db->join($this->almacenes.' As a' , 'a.id = m.id_almacen AND a.activo=1');          
          $this->db->join($this->proveedores.' As p' , 'p.id = m.id_empresa','LEFT');          
          $this->db->join($this->catalogo_tipos_pagos.' As tp' , 'tp.id = m.id_tipo_pago','LEFT');
          $this->db->join($this->documentos_pagos.' As dp' , 'dp.id = pr.id_documento_pago','LEFT');
          $this->db->join($this->historico_pagos_realizados.' As pri' , 'pri.movimiento = m.movimiento AND pri.id_factura=m.id_factura','LEFT');
          

          $where = '(
                      (
                         ( pr.movimiento = '.$data["movimiento"].' ) AND ( pr.id_factura = '.$data["id_factura"].' )
                         
                      ) 

                       AND
                      (  
                        (  dp.documento_pago LIKE  "%'.$cadena.'%" ) OR 
                        ( pr.instrumento_pago LIKE  "%'.$cadena.'%" ) OR (pr.importe LIKE  "%'.$cadena.'%") OR 
                        ((DATE_FORMAT((pr.fecha_pago),"%d-%m-%Y %H:%i") ) LIKE  "%'.$cadena.'%") OR
                        (pr.comentario LIKE  "%'.$cadena.'%")                         
                       )

            )';   


         $this->db->where($where);          
         $this->db->group_by('pr.id'); //,m.id_almacen,m.id_empresa,m.factura

          $result = $this->db->get();


          if ( $result->num_rows() > 0 )
          return $result->result();
          else
          return False;
          $result->free_result();

            

      }  

/////////////////////////////////////////1- imprimir ctas x pagar/////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


public function impresion_ctasxpagar($data){

          $cadena = addslashes($data['busqueda']);          


          $id_session = $this->db->escape($this->session->userdata('id'));

          $id_almacen= $data['id_almacen'];
          $id_factura= $data['id_factura'];

          $this->db->select('m.movimiento, m.factura');
          $this->db->select('a.almacen');
          $this->db->select('p.nombre, m.factura,tp.tipo_pago,m.id_tipo_pago');

          $this->db->select("MAX(DATE_FORMAT(m.fecha_entrada,'%d-%m-%Y')) as fecha",false);

          
          $this->db->select('p.dias_ctas_pagar');   
          $this->db->select("DATEDIFF( NOW( ) ,  fecha_entrada ) as diferencia_dias", false);                    
          $this->db->select('subtotal');           
          $this->db->select("iva", FALSE);
          $this->db->select("m.total", FALSE);

          //$this->db->select("total-sum(pr.importe) AS monto_restante", FALSE);
          $this->db->select("m.total+sum((pr.id_documento_pago <> 12)*pr.importe*-1)+sum((pr.id_documento_pago = 12)*pr.importe) AS monto_restante", FALSE);

          

          $this->db->select("MAX(DATE_FORMAT(pr.fecha_pago,'%d-%m-%Y')) as fecha_pagada",false);
          $this->db->select("MAX(pr.fecha_pago) as fecha_pago",false);
          $this->db->select("DATE_FORMAT(DATE_ADD(fecha_entrada, INTERVAL p.dias_ctas_pagar DAY), '%d-%m-%Y') as fecha_vencimiento", false);                    
          
          $this->db->select("(DATE_ADD(fecha_entrada, INTERVAL p.dias_ctas_pagar DAY)) as fecha_ven", false);   

          $this->db->from($this->historico_ctasxpagar.' as m');
          $this->db->join($this->proveedores.' As p' , 'p.id = m.id_empresa','LEFT');          
          $this->db->join($this->almacenes.' As a' , 'a.id = m.id_almacen AND a.activo=1');
          $this->db->join($this->catalogo_tipos_pagos.' As tp' , 'tp.id = m.id_tipo_pago','LEFT');
          $this->db->join($this->historico_pagos_realizados.' As pr' , 'pr.movimiento = m.movimiento AND pr.id_factura=m.id_factura','LEFT');
          


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

         if ( (addslashes($data['proveedor'])!="")  && (addslashes($data['proveedor'])!= null) ) {
            $proveedorid= 'and ( p.nombre =  "'.addslashes($data['proveedor']).'" ) ';
          } else {
            $proveedorid = '';
          }


          $where = '(
                      (
                         ( m.id_operacion = '.$data["id_operacion"].' ) '.$data["condicion"].$fechas.$id_almacenid.$id_facturaid.$proveedorid.' 
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
                         ( m.id_operacion = '.$data["id_operacion"].' )'.$fechas.$id_almacenid.$id_facturaid.' 
                      )';
           

          $this->db->where($where);          

          $this->db->group_by('m.movimiento,m.id_factura'); //,m.id_almacen,m.id_empresa,m.factura

          
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
       



    public function totales_importes_ctas($data){


          $cadena = addslashes($data['busqueda']);          


          $id_session = $this->db->escape($this->session->userdata('id'));

          $id_almacen= $data['id_almacen'];
          $id_factura= $data['id_factura'];

           
           //$this->db->select("total-sum(pr.importe) AS monto_restante", FALSE);
           $this->db->select("m.total+sum((pr.id_documento_pago <> 12)*pr.importe*-1)+sum((pr.id_documento_pago = 12)*pr.importe) AS monto_restante", FALSE);

           $this->db->select("(m.id_tipo_pago) AS id_tipo_pago", FALSE);
           $this->db->select("(subtotal) as subtotal", FALSE);
           $this->db->select("(iva) as iva", FALSE);
           $this->db->select("(m.total) as total", FALSE);

          $this->db->select("MAX(DATE_FORMAT(pr.fecha_pago,'%d-%m-%Y')) as fecha_pagada",false);
          $this->db->select("MAX(pr.fecha_pago) as fecha_pago",false);
          $this->db->select("DATE_FORMAT(DATE_ADD(fecha_entrada, INTERVAL p.dias_ctas_pagar DAY), '%d-%m-%Y') as fecha_vencimiento", false);                    
          
          $this->db->select("(DATE_ADD(fecha_entrada, INTERVAL p.dias_ctas_pagar DAY)) as fecha_ven", false);              
   
           $this->db->from($this->historico_ctasxpagar.' as m');
           $this->db->join($this->almacenes.' As a' , 'a.id = m.id_almacen AND a.activo=1');
           $this->db->join($this->proveedores.' As p' , 'p.id = m.id_empresa','LEFT');           
           $this->db->join($this->catalogo_tipos_pagos.' As tp' , 'tp.id = m.id_tipo_pago','LEFT');      
           //$this->db->join($this->historico_pagos_realizados.' As pr' , 'pr.movimiento = m.movimiento','LEFT');
           $this->db->join($this->historico_pagos_realizados.' As pr' , 'pr.movimiento = m.movimiento AND pr.id_factura=m.id_factura','LEFT');




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

          if ( (addslashes($data['proveedor'])!="")  && (addslashes($data['proveedor'])!= null) ) {
            $proveedorid= 'and ( p.nombre =  "'.addslashes($data['proveedor']).'" ) ';
          } else {
            $proveedorid = '';
          }


          $where = '(
                      (
                         ( m.id_operacion = '.$data["id_operacion"].' ) '.$data["condicion"].$fechas.$id_almacenid.$id_facturaid.$proveedorid.' 
                      ) 
                       AND
                      (  ( m.movimiento LIKE  "%'.$cadena.'%" )OR 
                        ( tp.tipo_pago LIKE  "%'.$cadena.'%" ) OR 
                        ( a.almacen LIKE  "%'.$cadena.'%" ) OR (p.nombre LIKE  "%'.$cadena.'%") OR 
                        ((DATE_FORMAT((m.fecha_entrada),"%d-%m-%Y %H:%i") ) LIKE  "%'.$cadena.'%") OR
                        (m.factura LIKE  "%'.$cadena.'%")                         
                       )
            )';   


          
           

          $this->db->where($where);          

          $this->db->group_by('m.movimiento,m.id_factura,m.id_almacen,m.id_empresa,m.factura');

          
          $this->db->having($data['having']);           



           $result = $this->db->get();
      
              $subtotal =0;
              $iva =0;
              $total =0;
           if ( $result->num_rows() > 0 ) {
              
              foreach ($result->result() as $row) {
                        $subtotal+=$row->subtotal;
                        $iva+=$row->iva;
                        $total+=$row->total;
              }

              return  json_encode(array(
                                "subtotal"=>$subtotal,
                                "iva"=>$iva,
                                "total"=>$total,
                                ));
           }
           else
             return False;
           $result->free_result();              
    }         

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////IMPRESION CTAS ESPECIFICAS/////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

public function impresion_ctas_especificas($data){

          $cadena = addslashes($data['busqueda']);          


          $id_session = $this->db->escape($this->session->userdata('id'));

          $id_almacen= $data['id_almacen'];
          $id_factura= $data['id_factura'];

          $this->db->select('m.movimiento,m.factura');
          $this->db->select('a.almacen');
          $this->db->select('p.nombre, m.factura,tp.tipo_pago,m.id_tipo_pago,m.id_factura');

          $this->db->select("MAX(DATE_FORMAT(m.fecha_entrada,'%d-%m-%Y')) as fecha",false);

          
          $this->db->select('p.dias_ctas_pagar');   
          $this->db->select("DATEDIFF( NOW( ) ,  fecha_entrada ) as diferencia_dias", false);                    
          $this->db->select('subtotal');           
          $this->db->select("iva", FALSE);
          $this->db->select("m.total", FALSE);

          
          $this->db->select("m.total+sum((pr.id_documento_pago <> 12)*pr.importe*-1)+sum((pr.id_documento_pago = 12)*pr.importe) AS monto_restante", FALSE);

           $this->db->select("sum( ( (pr.id_documento_pago <> 12) && (pr.id_documento_pago <> 13) ) *pr.importe)  AS abono", FALSE);
          $this->db->select("sum(  (pr.id_documento_pago = 12)  *pr.importe)  AS recargo", FALSE);
          $this->db->select("sum(  (pr.id_documento_pago = 13)  *pr.importe)  AS descuento", FALSE);


          

          $this->db->select("MAX(DATE_FORMAT(pr.fecha_pago,'%d-%m-%Y')) as fecha_pagada",false);
          $this->db->select("MAX(pr.fecha_pago) as fecha_pago",false);
          $this->db->select("DATE_FORMAT(DATE_ADD(fecha_entrada, INTERVAL p.dias_ctas_pagar DAY), '%d-%m-%Y') as fecha_vencimiento", false);                    
          
          $this->db->select("(DATE_ADD(fecha_entrada, INTERVAL p.dias_ctas_pagar DAY)) as fecha_ven", false);   

          
          
          $this->db->select('tf.tipo_factura');

          $this->db->from($this->historico_ctasxpagar.' as m');
          $this->db->join($this->tipos_facturas.' As tf' , 'tf.id = m.id_factura','LEFT');
          $this->db->join($this->proveedores.' As p' , 'p.id = m.id_empresa','LEFT');          
          $this->db->join($this->almacenes.' As a' , 'a.id = m.id_almacen AND a.activo=1');
          $this->db->join($this->catalogo_tipos_pagos.' As tp' , 'tp.id = m.id_tipo_pago','LEFT');
          $this->db->join($this->historico_pagos_realizados.' As pr' , 'pr.movimiento = m.movimiento AND pr.id_factura=m.id_factura','LEFT');
          


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

         if ( (addslashes($data['proveedor'])!="")  && (addslashes($data['proveedor'])!= null) ) {
            $proveedorid= 'and ( p.nombre =  "'.addslashes($data['proveedor']).'" ) ';
          } else {
            $proveedorid = '';
          }


          $where = '(
                      (
                         ( m.id_operacion = '.$data["id_operacion"].' ) '.$data["condicion"].$fechas.$id_almacenid.$id_facturaid.$proveedorid.' 
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
                         ( m.id_operacion = '.$data["id_operacion"].' )'.$fechas.$id_almacenid.$id_facturaid.' 
                      )';
           

          $this->db->where($where);          

          $this->db->group_by('m.id_empresa,m.movimiento,m.id_factura'); //,m.id_almacen,m.id_empresa,m.factura

          
          $this->db->having($data['having']);
          
          //ordenacion
          $this->db->order_by('m.id_empresa,m.id_factura,m.movimiento', 'ASC'); 

            $result = $this->db->get();


            if ( $result->num_rows() > 0 )
               return $result->result();
            else
               return False;
            $result->free_result();

      }  
       

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////IMPRESION CTAS detalladas/////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

public function impresion_ctas_detalladas($data){

          $cadena = addslashes($data['busqueda']);          
          $id_session = $this->db->escape($this->session->userdata('id'));
          $id_almacen= $data['id_almacen'];
          $id_factura= $data['id_factura'];

          $this->db->select('p.nombre');
          $this->db->select('m.movimiento,m.factura');
          $this->db->select('m.total');
          $this->db->select("(DATE_FORMAT(m.fecha_entrada,'%d-%m-%Y')) as fecha",false);
          $this->db->select('pr.importe');
          $this->db->select("(DATE_FORMAT(pr.fecha_pago,'%d-%m-%Y')) as fecha_pago",false);
          $this->db->select('dp.documento_pago');
          
          $this->db->select("(( (pr.id_documento_pago <> 12) && (pr.id_documento_pago <> 13) ) *pr.importe)  AS abono", FALSE);
          $this->db->select("((pr.id_documento_pago = 12)  *pr.importe)  AS recargo", FALSE);
          $this->db->select("((pr.id_documento_pago = 13)  *pr.importe)  AS descuento", FALSE);
          $this->db->select('p.dias_ctas_pagar');   
          $this->db->select("DATEDIFF( NOW( ) ,  m.fecha_entrada ) as diferencia_dias", false);                    
          $this->db->select('a.almacen,pr.id_documento_pago ');

          $this->db->select('tf.tipo_factura,m.id_factura');

          $this->db->from($this->historico_ctasxpagar.' as m');
          $this->db->join($this->tipos_facturas.' As tf' , 'tf.id = m.id_fac_orig','LEFT');
          $this->db->join($this->proveedores.' As p' , 'p.id = m.id_empresa','LEFT');          
          $this->db->join($this->almacenes.' As a' , 'a.id = m.id_almacen AND a.activo=1');
          $this->db->join($this->catalogo_tipos_pagos.' As tp' , 'tp.id = m.id_tipo_pago','LEFT');
          $this->db->join($this->historico_pagos_realizados.' As pr' , 'pr.movimiento = m.movimiento AND pr.id_factura=m.id_factura','LEFT');
          $this->db->join($this->documentos_pagos.' As dp' , 'dp.id = pr.id_documento_pago','LEFT');
          

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


         if ( (addslashes($data['proveedor'])!="")  && (addslashes($data['proveedor'])!= null) ) {
            $proveedorid= 'and ( p.nombre =  "'.addslashes($data['proveedor']).'" ) ';
          } else {
            $proveedorid = '';
          }

          $where = '(
                      (
                         ( m.id_operacion = '.$data["id_operacion"].' ) '.$data["condicion"].$fechas.$id_almacenid.$id_facturaid.$proveedorid.' 
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
                         ( m.id_operacion = '.$data["id_operacion"].' )'.$fechas.$id_almacenid.$id_facturaid.' 
                      )';


          $this->db->where($where.$data['filtro']);          

          //$this->db->group_by('m.movimiento,m.id_factura'); //,m.id_almacen,m.id_empresa,m.factura
          //$this->db->having($data['having']);
          //ordenacion
          $this->db->order_by('m.id_empresa,m.id_factura,m.movimiento, pr.fecha_pago', 'ASC'); 

          $result = $this->db->get();


          if ( $result->num_rows() > 0 )
             return $result->result();
          else
             return False;
          $result->free_result();

      }         


///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////IMPRESION CTAS antiguedad/////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

public function impresion_ctas_antiguedad($data){

          $cadena = addslashes($data['busqueda']);          


          $id_session = $this->db->escape($this->session->userdata('id'));

          $id_almacen= $data['id_almacen'];
          $id_factura= $data['id_factura'];

          $this->db->select('m.movimiento,m.factura');
          $this->db->select('a.almacen');
          $this->db->select('p.nombre, m.factura,tp.tipo_pago,m.id_tipo_pago');

          $this->db->select("MAX(DATE_FORMAT(m.fecha_entrada,'%d-%m-%Y')) as fecha",false);

          
          $this->db->select('p.dias_ctas_pagar');   
          $this->db->select("DATEDIFF( NOW( ) ,  fecha_entrada ) as diferencia_dias", false);                    
          $this->db->select('subtotal');           
          $this->db->select("iva", FALSE);
          $this->db->select("m.total", FALSE);

          
          $this->db->select("m.total+sum((pr.id_documento_pago <> 12)*pr.importe*-1)+sum((pr.id_documento_pago = 12)*pr.importe) AS monto_restante", FALSE);

     
          $this->db->select("sum( ( (pr.id_documento_pago <> 12) && (pr.id_documento_pago <> 13) ) *pr.importe)  AS abono", FALSE);
          $this->db->select("sum(  (pr.id_documento_pago = 12)  *pr.importe)  AS recargo", FALSE);
          $this->db->select("sum(  (pr.id_documento_pago = 13)  *pr.importe)  AS descuento", FALSE);
          $this->db->select("MAX(DATE_FORMAT(pr.fecha_pago,'%d-%m-%Y')) as fecha_pagada",false);
          $this->db->select("MAX(pr.fecha_pago) as fecha_pago",false);
          $this->db->select("DATE_FORMAT(DATE_ADD(fecha_entrada, INTERVAL p.dias_ctas_pagar DAY), '%d-%m-%Y') as fecha_vencimiento", false);                    
          
          $this->db->select("(DATE_ADD(fecha_entrada, INTERVAL p.dias_ctas_pagar DAY)) as fecha_ven", false);   

           $this->db->select('tf.tipo_factura,m.id_factura');

          $this->db->from($this->historico_ctasxpagar.' as m');
          $this->db->join($this->tipos_facturas.' As tf' , 'tf.id = m.id_factura','LEFT');
          $this->db->join($this->proveedores.' As p' , 'p.id = m.id_empresa','LEFT');          
          $this->db->join($this->almacenes.' As a' , 'a.id = m.id_almacen AND a.activo=1');
          $this->db->join($this->catalogo_tipos_pagos.' As tp' , 'tp.id = m.id_tipo_pago','LEFT');
          $this->db->join($this->historico_pagos_realizados.' As pr' , 'pr.movimiento = m.movimiento AND pr.id_factura=m.id_factura','LEFT');
          


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

         if ( (addslashes($data['proveedor'])!="")  && (addslashes($data['proveedor'])!= null) ) {
            $proveedorid= 'and ( p.nombre =  "'.addslashes($data['proveedor']).'" ) ';
          } else {
            $proveedorid = '';
          }


          $where = '(
                      (
                         ( m.id_operacion = '.$data["id_operacion"].' ) '.$data["condicion"].$fechas.$id_almacenid.$id_facturaid.$proveedorid.' 
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
                         ( m.id_operacion = '.$data["id_operacion"].' )'.$fechas.$id_almacenid.$id_facturaid.' 
                      )';
           

          $this->db->where($where);          

                  
         
         $this->db->group_by('m.id_empresa,m.movimiento,m.id_factura'); //,m.id_almacen,m.id_empresa,m.factura

          
          $this->db->having($data['having']);
          
          //ordenacion
          $this->db->order_by('m.id_empresa,m.id_factura,m.movimiento', 'ASC'); 



            $result = $this->db->get();


            if ( $result->num_rows() > 0 )
               return $result->result();
            else
               return False;
            $result->free_result();

      }        


///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////EXPORTAR//////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


public function exportar_ctasxpagar($data){

          $cadena = addslashes($data['busqueda']);          


          $id_session = $this->db->escape($this->session->userdata('id'));

          $id_almacen= $data['id_almacen'];
          $id_factura= $data['id_factura'];


          $this->db->select('m.movimiento,m.factura');
          $this->db->select('a.almacen');
          $this->db->select('p.nombre, m.factura,tp.tipo_pago,m.id_tipo_pago');

          $this->db->select("MAX(DATE_FORMAT(m.fecha_entrada,'%d-%m-%Y')) as fecha",false);

          
          $this->db->select('p.dias_ctas_pagar');   
          $this->db->select("DATEDIFF( NOW( ) ,  fecha_entrada ) as diferencia_dias", false);                    
          $this->db->select('subtotal');           
          $this->db->select("iva", FALSE);
          $this->db->select("m.total", FALSE);

          //$this->db->select("total-sum(pr.importe) AS monto_restante", FALSE);
          $this->db->select("m.total+sum((pr.id_documento_pago <> 12)*pr.importe*-1)+sum((pr.id_documento_pago = 12)*pr.importe) AS monto_restante", FALSE);

          
          $this->db->select("MAX(DATE_FORMAT(pr.fecha_pago,'%d-%m-%Y')) as fecha_pagada",false);
          $this->db->select("MAX(pr.fecha_pago) as fecha_pago",false);
          $this->db->select("DATE_FORMAT(DATE_ADD(fecha_entrada, INTERVAL p.dias_ctas_pagar DAY), '%d-%m-%Y') as fecha_vencimiento", false);                    
          
          $this->db->select("(DATE_ADD(fecha_entrada, INTERVAL p.dias_ctas_pagar DAY)) as fecha_ven", false);   


          $this->db->from($this->historico_ctasxpagar.' as m');
          $this->db->join($this->almacenes.' As a' , 'a.id = m.id_almacen AND a.activo=1');
          $this->db->join($this->proveedores.' As p' , 'p.id = m.id_empresa','LEFT');          
          $this->db->join($this->catalogo_tipos_pagos.' As tp' , 'tp.id = m.id_tipo_pago','LEFT');
          $this->db->join($this->historico_pagos_realizados.' As pr' , 'pr.movimiento = m.movimiento AND pr.id_factura=m.id_factura','LEFT');
           


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

          if ( (addslashes($data['proveedor'])!="")  && (addslashes($data['proveedor'])!= null) ) {
            $proveedorid= 'and ( p.nombre =  "'.addslashes($data['proveedor']).'" ) ';
          } else {
            $proveedorid = '';
          }


          $where = '(
                      (
                         ( m.id_operacion = '.$data["id_operacion"].' ) '.$data["condicion"].$fechas.$id_almacenid.$id_facturaid.$proveedorid.' 
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
                         ( m.id_operacion = '.$data["id_operacion"].' )'.$fechas.$id_almacenid.$id_facturaid.' 
                      )';
           

          $this->db->where($where);          

          $this->db->group_by('m.movimiento,m.id_factura'); //,m.id_almacen,m.id_empresa,m.factura

          
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
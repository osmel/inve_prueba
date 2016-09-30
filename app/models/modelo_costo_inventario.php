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

          $this->db->select("SQL_CALC_FOUND_ROWS *", FALSE); 

          $this->db->select('m.id, m.movimiento,m.id_empresa, m.factura, m.id_descripcion, m.id_operacion, m.num_partida');
          $this->db->select('m.id_color, m.id_composicion, m.id_calidad, m.referencia');
          $this->db->select('m.id_medida,  m.cantidad_royo, m.ancho, m.precio,  m.codigo, m.comentario');
          //$this->db->select('m.iva');
          $this->db->select("((m.precio*m.iva))/100 as sum_iva", FALSE);
          
          $this->db->select('m.id_estatus, m.id_lote, m.consecutivo, m.id_cargador, m.id_usuario, m.fecha_mac fecha, m.fecha_entrada,fecha_apartado,m.proceso_traspaso');
          $this->db->select('c.hexadecimal_color, c.color, p.nombre');
          $this->db->select('DATE_FORMAT(m.fecha_entrada,"%d/%m/%Y") as ppp',false);

          $this->db->select("tff.tipo_factura t_factura");  
          
          

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



          $this->db->from($this->registros.' as m');
          $this->db->join($this->productos.' As prod' , 'prod.referencia = m.referencia','LEFT');

          $this->db->join($this->colores.' As c' , 'c.id = m.id_color','LEFT');
          $this->db->join($this->unidades_medidas.' As u' , 'u.id = m.id_medida','LEFT');
          $this->db->join($this->proveedores.' As p' , 'p.id = m.id_empresa','LEFT');
          
          $this->db->join($this->almacenes.' As a' , 'a.id = m.id_almacen','LEFT');                     
          $this->db->join($this->tipos_facturas.' As tff' , 'tff.id = m.id_factura','LEFT');

          

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
                                      5=>number_format($row->precio, 2, '.', ','),
                                      6=>number_format($row->sum_iva, 2, '.', ','),
                                      7=>$row->t_factura,
                                      8=>
                                           '<a style="  padding: 1px 0px 1px 0px;" href="'.base_url().'procesar_entradas/'.base64_encode($row->movimiento).'/'.base64_encode($row->devolucion).'/'.base64_encode($retorno).'"
                                               type="button" class="btn btn-success btn-block">'.$row->movimiento.'</a>', 
                                      9=>$columna6,
                                      10=>$columna7,
                                      11=> date( 'd-m-Y', strtotime($fecha)),
                                      12=>$row->metros,
                                      13=>$row->kilogramos,
                                      14=>$row->color_devolucion,
                                      15=>$row->factura,
                                      16=>$row->num_partida,
                                      17=>$row->almacen,
                                      18=>$row->proceso_traspaso,
                                      19=>$row->codigo_contable,

                                      

                                    );                    
                      }

                      return json_encode ( array(
                        "draw"            => intval( $data['draw'] ),
                        "recordsTotal"    => intval( self::total_registros_entrada_home($data) ),  
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

           $this->db->select("SUM(precio) as subtotal", FALSE);
           $this->db->select("(SUM(precio*iva))/100 as iva", FALSE);
           $this->db->select("SUM(precio)+(SUM(precio*iva))/100 as total", FALSE);
   
          $this->db->from($this->registros.' as m');
          $this->db->where($where);


          $result = $this->db->get();
      
          if ( $result->num_rows() > 0 )
             return $result->row();
          else
             return False;
          $result->free_result();              

    }  
      

      
      public function total_registros_entrada_home($data){

              $this->db->from($this->registros.' as m');
              $this->db->where($data['where_total']);

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





//////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////Reportes/////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////

    
 



	} 
?>
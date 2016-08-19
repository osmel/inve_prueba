<?php if(! defined('BASEPATH')) exit('No tienes permiso para acceder a este archivo');

  class model_salida extends CI_Model {
    
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
      
      $this->composiciones     = $this->db->dbprefix('catalogo_composicion');
      $this->calidades                 = $this->db->dbprefix('catalogo_calidad');

      $this->registros_entradas               = $this->db->dbprefix('registros_entradas');
      $this->registros_cambios               = $this->db->dbprefix('registros_cambios');

      $this->almacenes             = $this->db->dbprefix('catalogo_almacenes');
    }






     ///////////////////////////////////////////////////////////////////




////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

       public function quitar_apartados( $data ){
            $id_almacen= $data['id_almacen'];


                $porciento_aplicar = 16;                 
                
                /*
                $this->db->set( 'iva', '((id_factura_original = 1)*'.$porciento_aplicar.')', false);
                $this->db->set( 'incluir', 0);
                $this->db->set( 'id_factura', 'id_factura_original', false);
                $this->db->set( 'id_factura_original', 0, false);
                */



            $sql= 'UPDATE '.$this->registros.' as m JOIN '.$this->usuarios.' As u  ON u.id = m.id_usuario_apartado'.
              ' SET m.id_apartado = 0, m.id_usuario_apartado ="", m.id_cliente_apartado=0,
              m.id_prorroga=0,m.fecha_apartado="",m.fecha_vencimiento="",m.id_tipo_pedido=0,m.id_tipo_factura=0,
              m.iva= ((m.id_factura_original = 1)*'.$porciento_aplicar.'),
              m.incluir =0, m.id_factura = m.id_factura_original,
              m.id_factura_original =0
              ';


            if ($id_almacen!=0) {
                    $id_almacenid = ' AND ( m.id_almacen =  '.$id_almacen.' ) ';  
                } else {
                    $id_almacenid = '';
            }             

            $where = ' where (
                          (
                            (( m.id_apartado = 6 ) or ( m.id_apartado = 3 ) ) AND ( u.id_cliente = '.$data['id_cliente'].' )
                            
                          )'.$id_almacenid.'  

                      )';   
  //AND ( m.estatus_salida = "0" ) 

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

                $where = '(
                          (
                            (( m.id_apartado = 6 ) or ( m.id_apartado = 3 ) ) AND ( u.id_cliente = '.$data['id_cliente'].' )
                            AND ( m.estatus_salida = "0" )
                          )'.$id_almacenid.' 

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

          $this->db->select('m.id, m.movimiento,m.id_empresa, m.factura, m.id_descripcion, m.id_operacion,m.devolucion, m.num_partida');
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
              $id_tipo_facturaid = ' AND ( m.id_factura =  '.$id_tipo_factura.' ) ';  
              //$id_tipo_facturaid = '';
          } else {
              $id_tipo_facturaid = '';
          } 

        
          $where = '(
                      (
                        (
                          ( ( us.id_cliente = '.$data['id_cliente'].' )  AND  ( (m.id_apartado = 3)  or ( m.id_apartado = 6 ) ) ) OR
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
                                           '<a style="  padding: 1px 0px 1px 0px;" href="'.base_url().'procesar_entradas/'.base64_encode($row->movimiento).'/'.base64_encode($row->devolucion).'/'.base64_encode($retorno).'"
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

    public function total_registros_cambio($codigo){

              $id_session = $this->session->userdata('id');
              $this->db->from($this->registros_cambios.' as m');
              $this->db->where('m.codigo',$codigo);

              $cant = $this->db->count_all_results();          
     
              if ( $cant > 0 )
                 return $cant;
              else
                 return 0;         

    }


       public function buscador_cambio($data){

          $cadena = addslashes($data['search']['value']);
          $inicio = $data['start'];
          $largo = $data['length'];
          $codigo= $data['codigo'];

          $id_session = $this->db->escape($this->session->userdata('id'));

          $this->db->select("SQL_CALC_FOUND_ROWS *", FALSE); //

          $this->db->select('m.codigo, m.num_partida');
          $this->db->select('m.referencia');
          $this->db->select('m.peso_real');
          $this->db->select('m.id_descripcion,c.color,c.hexadecimal_color, m.id_color, co.composicion, ca.calidad');
          
          $this->db->select('m.movimiento, m.fecha_entrada, p.nombre proveedor, m.factura, m.cantidad_um');
          $this->db->select('m.id_medida, m.ancho, m.id_estatus, m.id_lote, m.id,consecutivo_cambio,u.medida,m.comentario ');
         
          $this->db->from($this->registros_cambios.' as m');
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
                                      
                                    );
                      }



                      return json_encode ( array(
                        "draw"            => intval( $data['draw'] ),
                        "recordsTotal"    => intval( self::total_registros_cambio($codigo) ),  //$recordsTotal
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

          $this->db->select('m.id, m.movimiento,m.id_empresa, m.factura, m.id_descripcion, m.id_operacion,m.devolucion, m.num_partida');
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
                                           '<a style="  padding: 1px 0px 1px 0px;" href="'.base_url().'procesar_entradas/'.base64_encode($row->movimiento).'/'.base64_encode($row->devolucion).'/'.base64_encode($retorno).'"
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

             $this->db->select('"'.addslashes($data['factura']).'" AS factura',false); 

             $this->db->select('"'.htmlspecialchars($data['id_cliente']).'" AS id_cliente',false);
             $this->db->select('"'.htmlspecialchars($data['id_cargador']).'" AS id_cargador',false);
             $this->db->select('"'.$fecha_hoy.'" AS fecha_salida',false);

             $this->db->select('"'.$data['id_movimiento'].'" AS mov_salida',false); 
             

             $this->db->select('id id_entrada, movimiento, id_empresa, id_descripcion, id_color, devolucion, num_partida');
             $this->db->select('id_composicion, id_calidad, referencia, id_medida, cantidad_um, cantidad_royo, ancho');
             $this->db->select('precio, codigo, comentario, id_estatus, id_lote, consecutivo');
             $this->db->select('fecha_entrada,estatus_salida,consecutivo_venta');

             $this->db->select('id_apartado, id_usuario_apartado, id_cliente_apartado, fecha_apartado');

             $this->db->select('"'.$data['id_destino'].'" AS id_destino',false); 

             $this->db->select('id_factura');
             $this->db->select('"'.$data['id_tipo_factura'].'" AS id_tipo_factura',false); 
             $this->db->select('"'.$data['id_tipo_pedido'].'" AS id_tipo_pedido',false); 


             
            


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

            $this->db->set( 'precio_anterior', 'precio', FALSE  );
            $this->db->set( 'precio', 'precio_cambio', FALSE  );

            $this->db->set( 'id_tipo_factura', 0, FALSE  );
            $this->db->set( 'id_tipo_pedido', 0, FALSE  );

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

  

     public function consecutivo_operacion( $id ){
              
            $this->db->select("o.consecutivo");         
            $this->db->from($this->operaciones.' As o');
            $this->db->where('o.id',$id);
            $result = $this->db->get( );
                if ($result->num_rows() > 0)
                    return $result->row()->consecutivo+1;
                else 
                    return FALSE;
                $result->free_result();
     }  


        //procesando operaciones de salidas (afecta entradas y salidas)
        public function procesando_operacion_salida( $data ){

          $id_session = $this->session->userdata('id');

          $consecutivo = self::consecutivo_operacion(2); //cambio
           
          //registros de entradas
          $this->db->select('id id_entrada, fecha_entrada,  fecha_salida, movimiento, id_empresa, factura, id_descripcion, id_color, id_composicion, id_calidad,devolucion, num_partida');
          $this->db->select('referencia, id_medida, cantidad_um, cantidad_royo, ancho, precio, codigo, comentario, id_estatus, id_lote, consecutivo');
          $this->db->select('id_cargador, id_usuario, id_usuario_salida, fecha_mac, id_operacion, estatus_salida');

          $this->db->select('id_apartado,id_usuario_apartado, id_cliente_apartado,fecha_apartado');
          $this->db->select('precio_anterior, precio_cambio, id_prorroga, fecha_vencimiento, consecutivo_cambio');

          $this->db->select('id_almacen');


          $this->db->select('id_tipo_pedido,id_tipo_factura,iva, id_factura, id_factura_original');

          $this->db->select('0 incluir',false);

          
   



         

          $this->db->from($this->registros);

          $this->db->where('id_usuario_salida',$id_session);
          $this->db->where('estatus_salida','1');

          $result = $this->db->get();

          

          $objeto = $result->result();
          //copiar a tabla "historico_registros_entradas"
          
          /*
            foreach ($objeto as $key => $value) {
              $this->db->insert($this->historico_registros_entradas, $value); 
            }
          */
       

          //eliminar los registros en "registros_entradas"
          $this->db->delete($this->registros, array('id_usuario'=>$id_session,'estatus_salida'=>'1')); 



          //actualizar a registros_salidas el "mov_salida" al consecutivo q le toque
          $this->db->set('mov_salida', $consecutivo, FALSE  );
          $this->db->where('id_usuario',$id_session);
          $this->db->where('id_operacion',$data['id_operacion']); //2
          $this->db->update($this->registros_salidas);



          //registros de salidas    
          $this->db->select('m.id id_salida, m.id_entrada, m.mov_salida, m.fecha_entrada, m.fecha_salida, m.movimiento, m.id_empresa, m.id_cliente, m.factura, m.factura_salida,m.devolucion, m.num_partida');
          $this->db->select('m.id_descripcion, m.id_color, m.id_composicion, m.id_calidad, m.referencia, m.id_medida, m.cantidad_um, m.cantidad_royo, m.ancho, m.precio, m.codigo');
          $this->db->select('m.comentario, m.id_estatus, m.id_lote, m.consecutivo, m.id_cargador, m.id_usuario, m.id_usuario_salida, m.fecha_mac, m.id_operacion, m.estatus_salida');
          
          $this->db->select('ca.nombre cargador, p.nombre cliente');

          $this->db->select('m.id_apartado, m.id_usuario_apartado, m.id_cliente_apartado,m.fecha_apartado');

          $this->db->select('m.precio_anterior, m.precio_cambio, m.id_prorroga, m.fecha_vencimiento, m.consecutivo_cambio');
          
          //$this->db->select($data['valor'].' as tipo_salida', FALSE);                 
          //$this->db->select('"'.htmlspecialchars($data['id_cargador']).'" AS id_cargador',false);
          $this->db->select('"'.$data['valor'].'" AS tipo_salida',FALSE);

          $this->db->select('m.peso_real');
          $this->db->select('m.id_destino,de.nombre destino');
          $this->db->select('m.id_almacen');
          $this->db->select('m.consecutivo_venta');

          $this->db->from($this->registros_salidas.' As m');
          $this->db->join($this->proveedores.' As p' , 'p.id = m.id_cliente','LEFT');
          $this->db->join($this->cargadores.' As ca' , 'ca.id = m.id_cargador','LEFT');
          $this->db->join($this->catalogo_destinos.' As de' , 'de.id = m.id_destino','LEFT'); 


          $this->db->where('m.id_usuario',$id_session);
          $this->db->where('m.id_operacion',$data['id_operacion']); //2

          $result = $this->db->get();


          $objeto = $result->result();
          //copiar a tabla "historico_registros_salidas"
          $dato = array();
          foreach ($objeto as $key => $value) {
            $this->db->insert($this->historico_registros_salidas, $value); 
            $dato['num_movimiento'] = $value->mov_salida;
            $dato['cargador'] = $value->cargador;
            $dato['cliente'] = $value->cliente;

          }

          //actualizar (consecutivo) en tabla "operacion"   == "salida"
          $this->db->set( 'consecutivo', 'consecutivo+1', FALSE  );
          $this->db->set( 'id_usuario', $id_session );
          $this->db->where('id',2);
          $this->db->update($this->operaciones);

          //eliminar los registros en "registros_salidas"
          $this->db->delete($this->registros_salidas, array('id_usuario'=>$id_session,'id_operacion'=>$data['id_operacion'])); 

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


  /////////////////////////////////listado de la regilla///////////////////////////
        public function listado_movimientos_registros($data){

          $id_session = $this->session->userdata('id');
          $nombre_completo = $this->session->userdata('nombre_completo');
                    
          $this->db->select('m.id, m.mov_salida, m.movimiento,m.id_empresa, m.factura, m.id_descripcion, m.id_operacion,m.devolucion, m.num_partida');
          $this->db->select('m.id_color, m.id_composicion, m.id_calidad, m.referencia');
          $this->db->select('m.id_medida, m.cantidad_um, m.cantidad_royo, m.ancho, m.precio, m.codigo, m.comentario');
          $this->db->select('m.id_estatus, m.id_lote, m.consecutivo, m.id_cargador, m.id_usuario, m.fecha_mac fecha');

          $this->db->select('c.hexadecimal_color, u.medida,p.nombre, ca.nombre cargador');
          $this->db->select("( CASE WHEN m.devolucion <> 0 THEN 'red' ELSE 'black' END ) AS color_devolucion", FALSE);
          
          //$this->db->select("( CASE WHEN id_usuario_apartado <> '' THEN id_usuario_apartado ELSE '".$id_session."' END ) AS id_usuario_apartado", FALSE);
          $this->db->select("( CASE WHEN id_usuario_apartado <> '' THEN CONCAT(us.nombre, us.apellidos) ELSE '".$nombre_completo."' END ) AS nom_vendedor", FALSE);
          
          $this->db->select('m.peso_real');
          $this->db->select('m.id_destino,de.nombre destino');

          $this->db->select('a.almacen');

          $this->db->from($this->historico_registros_salidas.' as m');
          $this->db->join($this->colores.' As c' , 'c.id = m.id_color','LEFT');
          $this->db->join($this->unidades_medidas.' As u' , 'u.id = m.id_medida','LEFT');
          $this->db->join($this->proveedores.' As p' , 'p.id = m.id_cliente','LEFT');
          $this->db->join($this->cargadores.' As ca' , 'ca.id = m.id_cargador','LEFT');
          $this->db->join($this->usuarios.' As us' , 'us.id = m.id_usuario_apartado','LEFT');
          $this->db->join($this->catalogo_destinos.' As de' , 'de.id = m.id_destino','LEFT');

          $this->db->join($this->almacenes.' As a' , 'a.id = m.id_almacen','LEFT');



          //$this->db->where('m.id_usuario',$id_session);
          $this->db->where('m.id_operacion',2);
          $this->db->where('m.mov_salida',$data['encabezado']['num_movimiento']);

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



//////////////////////////////Procesando apartados pendientes
     


public function valores_movimientos_temporal(){

          $id_session = $this->session->userdata('id');
          
          $this->db->distinct();          
          $this->db->select('m.id, m.id_cliente, m.id_cargador, m.factura,m.id_destino, m.id_almacen');
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



  } 






?>

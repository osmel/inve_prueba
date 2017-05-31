<?php if(! defined('BASEPATH')) exit('No tienes permiso para acceder a este archivo');

  class model_pedido extends CI_Model {
    
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


    }



             

        public function listado_productos($limit=-1, $offset=-1){

                $this->db->distinct();
                $this->db->select('p.id, p.uid, p.referencia,  p.comentario');
                $this->db->select('p.descripcion, p.minimo, p.imagen, p.id_composicion, p.id_color,p.id_calidad,p.precio,p.ancho');
                $this->db->select('p.id_usuario, p.fecha_mac, c.hexadecimal_color,c.color nombre_color');

                $this->db->from($this->productos.' as p');
                //$this->db->join($this->colores.' As c', 'p.id_color = c.id','LEFT');
                $this->db->join($this->registros_entradas.' As c', 'p.descripcion = c.id_descripcion');                     

                
                
                if ($limit!=-1) {
                    $this->db->limit($limit, $offset); 
                } 
                $result = $this->db->get();

                  if ( $result->num_rows() > 0 )
                     return $result->result();
                  else
                     return False;
                  $result->free_result();
        }        



       public function listado_productos_unico(){

          $this->db->distinct();
          $this->db->select('p.id_descripcion descripcion');
          $this->db->from($this->registros_entradas.' as p');
          $this->db->order_by('p.id_descripcion', 'asc'); 

          $result = $this->db->get();

            if ( $result->num_rows() > 0 )
               return $result->result();
            else
               return False;
            $result->free_result();
        }   


         


        public function lista_composiciones($data){

            $this->db->distinct();
            $this->db->select("c.composicion nombre", FALSE);  
            $this->db->select("c.id", FALSE);  
            $this->db->from($this->registros_entradas.' as p');
            $this->db->join($this->composiciones.' As c', 'p.id_composicion = c.id','LEFT');
            

            $this->db->where('p.id_descripcion', ($data['val_prod']) );
            $result = $this->db->get();
            
            if ( $result->num_rows() > 0 )
               return $result->result();
            else
               return False;
            $result->free_result();
        }   


 public function lista_ancho($data){
            
            $this->db->distinct();
            $this->db->select("CONCAT(p.ancho,' cm') nombre", FALSE);  
            $this->db->select("p.ancho id", FALSE);  
            $this->db->from($this->registros_entradas.' as p');
            
          
            $this->db->where('p.id_descripcion', ($data['val_prod']) );
            $this->db->where('p.id_composicion', $data['val_comp']);
            $result = $this->db->get();
            
            if ( $result->num_rows() > 0 )
               return $result->result();
            else
               return False;
            $result->free_result();
        }   

        public function lista_colores($data){

            $this->db->distinct();
            $this->db->select("c.color nombre", FALSE);  
            $this->db->select("c.id", FALSE);  
            $this->db->select("c.hexadecimal_color", FALSE);  
            $this->db->from($this->registros_entradas.' as p');
            $this->db->join($this->colores.' As c', 'p.id_color = c.id','LEFT');
            
            $this->db->where('p.id_descripcion', ($data['val_prod']) );
            $this->db->where('p.id_composicion', $data['val_comp']);
            $this->db->where('p.ancho', floatval($data['val_ancho']));

            $this->db->order_by('c.color', 'asc'); 
            


            $result = $this->db->get();
            
            if ( $result->num_rows() > 0 )
               return $result->result();
            else
               return False;
            $result->free_result();
        }    
        public function lista_proveedores($data){

            $this->db->distinct();
            $this->db->select("c.nombre nombre", FALSE);  
            $this->db->select("c.id", FALSE);  
            $this->db->from($this->registros_entradas.' as p');
            $this->db->join($this->proveedores.' As c', 'p.id_empresa = c.id','LEFT');
            
            $this->db->where('p.id_descripcion', ($data['val_prod']) );
            $this->db->where('p.id_composicion', $data['val_comp']);
            $this->db->where('p.ancho', floatval($data['val_ancho']));
            $this->db->where('p.id_color', $data['val_color']);

            //$this->db->order_by('c.color', 'asc'); 

            $result = $this->db->get();
            
            if ( $result->num_rows() > 0 )
               return $result->result();
            else
               return False;
            $result->free_result();
        }          





          public function listado_productos_completa($data){

                //$this->db->distinct();
                $this->db->select('p.id');
                $this->db->select('p.descripcion nombre');

                $this->db->select('"'.$data['val_prod_id'].'" as activo', false);

                $this->db->from($this->productos.' as p');
                //$this->db->join($this->colores.' As c', 'p.id_color = c.id','LEFT');
                $this->db->join($this->registros_entradas.' As c', 'p.descripcion = c.id_descripcion');                     


              $filtro="";        

                if ($data['val_comp'] !=0) {
                  $filtro.= (($filtro!="") ? " and " : "") . "(c.id_composicion = '".$data["val_comp"]."') ";
                } 

                if  ($data['val_ancho']!=0){
                   $filtro.= (($filtro!="") ? " and " : "") . "(c.ancho = ".$data["val_ancho"].") ";
                }

                if  ($data['val_color']!=0){
                   $filtro.= (($filtro!="") ? " and " : "") . "(c.id_color = ".$data["val_color"].") ";
                }

                if  ($data['val_proveedor']!=0){
                   $filtro.= (($filtro!="") ? " and " : "") . "(c.id_empresa = ".$data["val_proveedor"].") ";
                }


                if ($filtro !=""){
                  $this->db->where( $filtro );               
                }


                $this->db->group_by( 'nombre' );               
                

                
                $result = $this->db->get();

                  if ( $result->num_rows() > 0 )
                     return $result->result();
                  else
                     return False;
                  $result->free_result();
        }     


        public function lista_composiciones_completa($data){

            $this->db->distinct();
            $this->db->select("c.composicion nombre", FALSE);  
            $this->db->select("c.id", FALSE);  
            $this->db->select($data['val_comp'].' as activo', false);
            $this->db->from($this->registros_entradas.' as p');
            $this->db->join($this->composiciones.' As c', 'p.id_composicion = c.id','LEFT');

            $filtro="";        

            if ( ($data['val_prod_id'] !="")  && ($data['val_prod_id'] !="0") ) {
              $filtro.= (($filtro!="") ? " and " : "") . "(p.id_descripcion = '".$data["val_prod"]."') ";
            } 

            if  ($data['val_ancho']!=0){
               $filtro.= (($filtro!="") ? " and " : "") . "(p.ancho = ".$data["val_ancho"].") ";
            }

            if  ($data['val_color']!=0){
               $filtro.= (($filtro!="") ? " and " : "") . "(p.id_color = ".$data["val_color"].") ";
            }

            if  ($data['val_proveedor']!=0){
               $filtro.= (($filtro!="") ? " and " : "") . "(p.id_empresa = ".$data["val_proveedor"].") ";
            }


            if ($filtro !=""){
              $this->db->where( $filtro );               
            }



            
            $result = $this->db->get();
            
            if ( $result->num_rows() > 0 )
               return $result->result();
            else
               return False;
            $result->free_result();
        }         


      

        public function lista_ancho_completa($data){
            
            $this->db->distinct();
            $this->db->select('CONCAT(p.ancho," cm") nombre',false);  
            $this->db->select('p.ancho id');  
            //$this->db->select('"'.$data['val_ancho_cad'].'" as activo', false);
            $this->db->select($data['val_ancho'].' as activo', false);
            $this->db->from($this->registros_entradas.' as p');
            
          
            $filtro="";        


            if ( ($data['val_prod_id'] !="")  && ($data['val_prod_id'] !="0") ) {
              $filtro.= (($filtro!="") ? " and " : "") . "(p.id_descripcion = '".$data["val_prod"]."') ";
            } 

            if  ($data['val_comp']!=0){
               $filtro.= (($filtro!="") ? " and " : "") . "(p.id_composicion = ".$data["val_comp"].") ";
            }

            if  ($data['val_color']!=0){
               $filtro.= (($filtro!="") ? " and " : "") . "(p.id_color = ".$data["val_color"].") ";
            }

            if  ($data['val_proveedor']!=0){
               $filtro.= (($filtro!="") ? " and " : "") . "(p.id_empresa = ".$data["val_proveedor"].") ";
            }

            if ($filtro !=""){
              $this->db->where( $filtro );               
            }


            $result = $this->db->get();
            
            if ( $result->num_rows() > 0 )
               return $result->result();
            else
               return False;
            $result->free_result();
        } 





        public function lista_colores_completa($data){

            $this->db->distinct();
            $this->db->select("c.color nombre", FALSE);  
            $this->db->select("c.id", FALSE);  
            $this->db->select("c.hexadecimal_color", FALSE);  
            $this->db->select($data['val_color'].' as activo', false);

            $this->db->from($this->registros_entradas.' as p');
            $this->db->join($this->colores.' As c', 'p.id_color = c.id','LEFT');
            

            $filtro="";        

            if ( ($data['val_prod_id'] !="")  && ($data['val_prod_id'] !="0") ) {
              $filtro.= (($filtro!="") ? " and " : "") . "(p.id_descripcion = '".$data["val_prod"]."') ";
            } 

            if  ($data['val_comp']!=0){
               $filtro.= (($filtro!="") ? " and " : "") . "(p.id_composicion = ".$data["val_comp"].") ";
            }

            if  ($data['val_ancho']!=0){
               $filtro.= (($filtro!="") ? " and " : "") . "(p.ancho = ".$data["val_ancho"].") ";
            }

            if  ($data['val_proveedor']!=0){
               $filtro.= (($filtro!="") ? " and " : "") . "(p.id_empresa = ".$data["val_proveedor"].") ";
            }

            

            if ($filtro !=""){
              $this->db->where( $filtro );               

            }




            $this->db->order_by('c.color', 'asc'); 
            


            $result = $this->db->get();
            
            if ( $result->num_rows() > 0 )
               return $result->result();
            else
               return False;
            $result->free_result();
        }    


          


     public function lista_proveedores_completa($data){

            $this->db->distinct();
            $this->db->select('c.nombre nombre');  //, FALSE
            $this->db->select('c.id');  //, FALSE
            $this->db->select($data['val_proveedor'].' as activo', false);
            //$this->db->select('p.id_descripcion, p.id_composicion, p.ancho, p.id_color, p.id_empresa');  
            $this->db->from($this->registros_entradas.' as p');
            $this->db->join($this->proveedores.' As c', 'p.id_empresa = c.id','LEFT');
            
            $filtro="";        

            if ( ($data['val_prod_id'] !="")  && ($data['val_prod_id'] !="0") ) {
              $filtro.= (($filtro!="") ? " and " : "") . "(p.id_descripcion = '".$data["val_prod"]."') ";
            } 

            if  ($data['val_comp']!=0){
               $filtro.= (($filtro!="") ? " and " : "") . "(p.id_composicion = ".$data["val_comp"].") ";
            }

            if  ($data['val_ancho']!=0){
               $filtro.= (($filtro!="") ? " and " : "") . "(p.ancho = ".$data["val_ancho"].") ";
            }

            if  ($data['val_color']!=0){
               $filtro.= (($filtro!="") ? " and " : "") . "(p.id_color = ".$data["val_color"].") ";
            }

            if  ($data['val_proveedor']!=0){
               $filtro.= (($filtro!="") ? " and " : "") . "(p.id_empresa = ".$data["val_proveedor"].") ";
            }



            if ($filtro !=""){
              $this->db->where( $filtro );               

            }


            $result = $this->db->get();
            
            if ( $result->num_rows() > 0 )
               return $result->result();
            else
               return False;
            $result->free_result();
        }            




////////////////////////"http://inventarios.dev.com/generar_pedidos"///////////////////////////////////////////////////////////////
 



      public function buscador_entrada_pedido($data){

          $cadena = addslashes($data['search']['value']);
          $inicio = $data['start'];
          $largo = $data['length'];
          
          //
          $id_descripcion= addslashes($data['id_descripcion']);
          $id_composicion= $data['id_composicion'];
          $ancho= floatval($data['ancho']);
          $id_color= $data['id_color'];
          $id_proveedor= $data['id_proveedor'];
          $id_almacen= $data['id_almacen'];

          $id_tipo_factura = $data['id_tipo_factura'];
           $id_tipo_pedido = $data['id_tipo_pedido'];

           
           $id_factura_filtro = $data['id_factura_filtro'];
          

          $columa_order = $data['order'][0]['column'];
                 $order = $data['order'][0]['dir'];

          switch ($columa_order) {
                   case '0':
                        $columna = 'm.codigo';
                     break;
                   case '1':
                        $columna = 'm.id_descripcion';
                     break;
                   case '3':
                        $columna = 'c.color';
                     break;
                   case '4':
                        $columna = 'm.cantidad_um';
                     break;
                   case '5':
                        $columna = 'm.ancho';
                     break;
                   case '6':
                        $columna = 'm.movimiento';
                     break;
                   case '7':
                              $columna= 'p.nombre';
                     break;
                   case '8':
                              $columna= 'm.id_lote, m.consecutivo';  
                     break;
                   
                   default:
                       $columna = 'm.codigo';
                     break;
                 }                 


          $id_session = $this->db->escape($this->session->userdata('id'));

          $this->db->select("SQL_CALC_FOUND_ROWS(m.id)", FALSE); //

          //m.id_empresa, m.id_factura, m.id_operacion,
          //$this->db->select('m.id_color, m.id_composicion, m.id_calidad, m.referencia');
          //m.id_medida,m.id_cargador, m.id_usuario, ,  m.fecha_mac fecha
          $this->db->select('m.id, m.movimiento, m.factura,  m.id_descripcion, m.devolucion, m.num_partida');
          $this->db->select('m.cantidad_um, m.cantidad_royo, m.ancho, m.precio, m.codigo, m.comentario');
          $this->db->select(' m.id_lote, m.consecutivo');

          $this->db->select('m.id_fac_orig,m.id_estatus, c.hexadecimal_color, c.color, u.medida,p.nombre, m.id_apartado');

          $this->db->select("( CASE WHEN m.id_medida = 1 THEN m.cantidad_um ELSE 0 END ) AS metros"); //, FALSE
          $this->db->select("( CASE WHEN m.id_medida = 2 THEN m.cantidad_um ELSE 0 END ) AS kilogramos"); //, FALSE
          $this->db->select("prod.imagen"); //, FALSE
          //$this->db->select("a.almacen");
          $this->db->select("prod.codigo_contable");  
          

          $this->db->from($this->registros.' as m');
          $this->db->join($this->productos.' As prod' , 'prod.referencia = m.referencia'); //,'LEFT'
          $this->db->join($this->almacenes.' As a' , 'a.id = m.id_almacen AND a.activo=1');
          $this->db->join($this->colores.' As c' , 'c.id = m.id_color'); //,'LEFT'
          $this->db->join($this->unidades_medidas.' As u' , 'u.id = m.id_medida'); //,'LEFT'
          $this->db->join($this->proveedores.' As p' , 'p.id = m.id_empresa'); //,'LEFT'
          //$this->db->join($this->usuarios.' As us' , 'us.id = m.id_usuario_apartado','LEFT');
          
          

          //filtro de busqueda
        
          if ($id_almacen!=0) {
              $id_almacenid = ' AND ( m.id_almacen =  '.$id_almacen.' ) ';  
          } else {
              $id_almacenid = '';
          } 
 
          //este no hace falta en pedido porq no se filtra
          if ($id_factura_filtro!=0) {
              $id_tipo_facturaid = ' AND ( m.id_factura =  '.$id_factura_filtro.' ) ';  
              //$id_tipo_facturaid = '';
          } else {
              $id_tipo_facturaid = '';
          } 

          $where = '(
                      (
                         
                         (( m.id_apartado = 0 ) AND ( m.id_operacion = "1" ) AND ( m.estatus_salida = "0" ) AND ( m.proceso_traspaso = 0 ) )
                      )'.$id_almacenid.$id_tipo_facturaid.' 
                       AND

                      (
                        ( m.codigo LIKE  "%'.$cadena.'%" ) OR (m.id_descripcion LIKE  "%'.$cadena.'%") OR (c.color LIKE  "%'.$cadena.'%")  OR
                        ( CONCAT(m.cantidad_um," ",u.medida) LIKE  "%'.$cadena.'%" ) OR (CONCAT(m.ancho," cm") LIKE  "%'.$cadena.'%")  OR
                        ( m.movimiento LIKE  "%'.$cadena.'%" ) OR  
                        (p.nombre LIKE  "%'.$cadena.'%") OR  (CONCAT(m.id_lote,"-",m.consecutivo) LIKE  "%'.$cadena.'%")
                       )

            )';   


          $where_total = '( m.id_apartado = 0 ) AND ( m.id_operacion = "1" ) AND ( m.estatus_salida = "0" )'.$id_almacenid.$id_tipo_facturaid;


            //$filtro="";        

                if (($id_composicion!="0") AND ($id_composicion!="") AND ($id_composicion!= null)) {
                    $where.= (($where!="") ? " and " : "") . "( m.id_composicion  =  ".$id_composicion." ) ";
                    $where_total.= (($where_total!="") ? " and " : "") . "( m.id_composicion  =  ".$id_composicion." ) ";
                } 

                if  (($ancho!=0) ) {
                   $where.= (($where!="") ? " and " : "") . "( m.ancho  =  ".$ancho." )";
                   $where_total.= (($where_total!="") ? " and " : "") . "( m.ancho  =  ".$ancho." )";
                }

                if  (($id_color!="0") AND ($id_color!="") AND ($id_color!= null)) {
                   $where.= (($where!="") ?  " and " : "") . "( m.id_color  =  ".$id_color." )";
                   $where_total.= (($where_total!="") ?  " and " : "") . "( m.id_color  =  ".$id_color." )";
                }

                if   (($id_proveedor!="0") AND ($id_proveedor!="") AND ($id_proveedor!= null)) {
                   $where.= (($where!="") ?  " and " : "") . "( m.id_empresa  =  '".$id_proveedor."' )";
                   $where_total.= (($where_total!="") ?  " and " : "") . "( m.id_empresa  =  '".$id_proveedor."' )";
                }

                //if (($id_descripcion!="0") AND ($id_descripcion!="") AND ($id_descripcion!= null)) {
                if ( ($data['val_prod_id'] !="")  && ($data['val_prod_id'] !="0") ) {
                    $where.= (($where!="") ? " and " : "") . "( m.id_descripcion  =  '".$id_descripcion."' )";
                    $where_total.= (($where_total!="") ? " and " : "") . "( m.id_descripcion  =  '".$id_descripcion."' )";
                }

                

        
  
          $this->db->where($where);
    
          //ordenacion
          $this->db->order_by($columna, $order); 

          //paginacion
          $this->db->limit($largo,$inicio); 


          $result = $this->db->get();

              if ( $result->num_rows() > 0 ) {

                    $cantidad_consulta = $this->db->query("SELECT FOUND_ROWS() as cantidad");
                    $found_rows = $cantidad_consulta->row(); 
                    $registros_filtrados =  ( (int) $found_rows->cantidad);






          $id_perfil = $this->session->userdata('id_perfil');

          $coleccion_id_operaciones= json_decode($this->session->userdata('coleccion_id_operaciones')); 
          if ( (count($coleccion_id_operaciones)==0) || (!($coleccion_id_operaciones)) ) {
                $coleccion_id_operaciones = array();
           }   


                  
                  $retorno= "generar_pedidos";  
                  foreach ($result->result() as $row) {

                         
                               $mov_entrada='<a style="  padding: 1px 0px 1px 0px;" href="'.base_url().'procesar_entradas/'.base64_encode($row->movimiento).'/'.base64_encode($row->devolucion).'/'.base64_encode($retorno).'/'.base64_encode($row->id_fac_orig).'/'.base64_encode($row->id_estatus).'" type="button" class="btn btn-success btn-block">'.$row->movimiento.'</a>';  
                          


                          $fechaSegundos = time(); 
                          $strNoCache = "?nocache=$fechaSegundos"; 
                          
                          $nombre_fichero ='uploads/productos/thumbnail/300X300/'.substr($row->imagen,0,strrpos($row->imagen,".")).'_thumb'.substr($row->imagen,strrpos($row->imagen,"."));
                          if (file_exists($nombre_fichero)) {
                              $imagen ='<img src="'.base_url().$nombre_fichero.$strNoCache.'" border="0" width="100%" height="auto">';

                          } else {
                              $imagen ='<img src="img/sinimagen.png" border="0" width="75" height="75">';
                          }

                          
                            $dato[]= array(
                                      0=>$row->codigo,
                                      1=>$row->id_descripcion,
                                      2=>$imagen,
                                      3=>$row->color.
                                        '<div style="background-color:#'.$row->hexadecimal_color.';display:block;width:15px;height:15px;margin:0 auto;"></div>',
                                      4=>$row->cantidad_um.' '.$row->medida,
                                      5=>$row->ancho.' cm',
                                      6=>$mov_entrada, 
                                      7=>$row->nombre,
                                      8=>$row->id_lote.'-'.$row->consecutivo,
                                      9=>$row->id,
                                      10=>$row->id_apartado,
                                      11=>$row->num_partida,
                                      12=>$row->metros,
                                      13=>$row->kilogramos,                                      
                                      14=>$row->imagen,
                                      15=>"alm", //$row->almacen,
                                      16=>$row->codigo_contable,
                                      17=>$row->id_estatus,
                                    );
                      }




                      return json_encode ( array(
                        "draw"            => intval( $data['draw'] ),
                        "recordsTotal"    => $registros_filtrados, 
                        "recordsFiltered" => $registros_filtrados, 
                        "data"            =>  $dato,
                        "totales"            =>  array("pieza"=>intval( self::total_campos_salida_home($where_total)->pieza ), "metro"=>floatval( self::total_campos_salida_home($where_total)->metros ), "kilogramo"=>floatval( self::total_campos_salida_home($where_total)->kilogramos )),  
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
                   "totales"            =>  array("pieza"=>intval( self::total_campos_salida_home($where_total)->pieza ), "metro"=>floatval( self::total_campos_salida_home($where_total)->metros ), "kilogramo"=>floatval( self::total_campos_salida_home($where_total)->kilogramos )), 

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
              $this->db->where($where);

             $result = $this->db->get();
          
              if ( $result->num_rows() > 0 )
                 return $result->row();
              else
                 return False;
              $result->free_result();              

       }        



//2da regilla de "/generar_pedidos"
 public function buscador_salida_pedido($data){

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
                   case '3':
                        $columna = 'c.color';
                     break;
                   case '4':
                        $columna = 'm.cantidad_um';
                     break;
                   case '5':
                        $columna = 'm.ancho';
                     break;
                   case '6':
                        $columna = 'm.movimiento';
                     break;
                   case '7':
                              $columna= 'p.nombre';
                     break;
                   case '8':
                              $columna= 'm.id_lote, m.consecutivo';  
                     break;
                   
                   default:
                       $columna = 'm.codigo';
                     break;
                 }                 



          $id_session = $this->db->escape($this->session->userdata('id'));

          $this->db->select("SQL_CALC_FOUND_ROWS(m.id)"); //
          //$this->db->select('m.id_color, m.id_composicion, m.id_calidad, m.referencia');
          //m.id_empresa,m.id_factura,m.id_operacion, m.id_medida, m.id_usuario, m.id_cargador,, m.id_apartado,   m.fecha_mac fecha, m.cantidad_royo
          //m.precio,, m.comentario
          $this->db->select('m.id, m.movimiento, m.factura,  m.id_fac_orig,m.id_descripcion,  m.devolucion, m.num_partida');
          
          $this->db->select(' m.cantidad_um, m.ancho,  m.codigo');
          $this->db->select('m.id_estatus,  m.consecutivo');
          $this->db->select('c.hexadecimal_color, c.color, u.medida,p.nombre, m.id_lote');
          $this->db->select("( CASE WHEN m.id_medida = 1 THEN m.cantidad_um ELSE 0 END ) AS metros");
          $this->db->select("( CASE WHEN m.id_medida = 2 THEN m.cantidad_um ELSE 0 END ) AS kilogramos");
          $this->db->select("prod.imagen");
          $this->db->select("prod.codigo_contable");  
          $this->db->select("a.almacen");
          $this->db->from($this->registros.' as m');
          $this->db->join($this->almacenes.' As a' , 'a.id = m.id_almacen AND a.activo=1');
          $this->db->join($this->colores.' As c' , 'c.id = m.id_color','LEFT');
          $this->db->join($this->unidades_medidas.' As u' , 'u.id = m.id_medida','LEFT');
          $this->db->join($this->proveedores.' As p' , 'p.id = m.id_empresa','LEFT');
          $this->db->join($this->usuarios.' As us' , 'us.id = m.id_usuario_apartado','LEFT');
          $this->db->join($this->productos.' As prod' , 'prod.referencia = m.referencia','LEFT');

          //filtro de busqueda
        
          $where = '(
                      (
                         
                         (  ( m.id_usuario_apartado = '.$id_session.' ) AND   ( m.id_apartado = 4 ) )
                      ) 
                       AND

                      (
                        ( m.codigo LIKE  "%'.$cadena.'%" ) OR (m.id_descripcion LIKE  "%'.$cadena.'%") OR (c.color LIKE  "%'.$cadena.'%")  OR
                        ( CONCAT(m.cantidad_um," ",u.medida) LIKE  "%'.$cadena.'%" ) OR (CONCAT(m.ancho," cm") LIKE  "%'.$cadena.'%")  OR
                        ( m.movimiento LIKE  "%'.$cadena.'%" ) OR  
                        (p.nombre LIKE  "%'.$cadena.'%") OR  (CONCAT(m.id_lote,"-",m.consecutivo) LIKE  "%'.$cadena.'%")
                       )

            )';   
          
          $where_total = '(  ( m.id_usuario_apartado = '.$id_session.' ) AND   ( m.id_apartado = 4 ) )'; 
          $this->db->where($where);
    
          //ordenacion
          $this->db->order_by($columna, $order); 

          //paginacion
          $this->db->limit($largo,$inicio); 

          $result = $this->db->get();

              if ( $result->num_rows() > 0 ) {

                    $cantidad_consulta = $this->db->query("SELECT FOUND_ROWS() as cantidad");
                    $found_rows = $cantidad_consulta->row(); 
                    $registros_filtrados =  ( (int) $found_rows->cantidad);

                   $retorno= "generar_pedidos";   


                  $id_perfil = $this->session->userdata('id_perfil');

                  $coleccion_id_operaciones= json_decode($this->session->userdata('coleccion_id_operaciones')); 
                  if ( (count($coleccion_id_operaciones)==0) || (!($coleccion_id_operaciones)) ) {
                        $coleccion_id_operaciones = array();
                   }   


                  foreach ($result->result() as $row) {


                                $mov_entrada='<a style="  padding: 1px 0px 1px 0px;" href="'.base_url().'procesar_entradas/'.base64_encode($row->movimiento).'/'.base64_encode($row->devolucion).'/'.base64_encode($retorno).'/'.base64_encode($row->id_fac_orig).'/'.base64_encode($row->id_estatus).'" type="button" class="btn btn-success btn-block">'.$row->movimiento.'</a>'; 



                          $fechaSegundos = time(); 
                          $strNoCache = "?nocache=$fechaSegundos"; 
                          
                          $nombre_fichero ='uploads/productos/thumbnail/300X300/'.substr($row->imagen,0,strrpos($row->imagen,".")).'_thumb'.substr($row->imagen,strrpos($row->imagen,"."));
                          if (file_exists($nombre_fichero)) {
                              $imagen ='<img src="'.base_url().$nombre_fichero.$strNoCache.'" border="0" width="100%" height="auto">';

                          } else {
                              $imagen ='<img src="img/sinimagen.png" border="0" width="75" height="75">';
                          }

                            $dato[]= array(
                                      0=>$row->codigo,
                                      1=>$row->id_descripcion,
                                      2=>$imagen,
                                      3=>$row->color.
                                        '<div style="background-color:#'.$row->hexadecimal_color.';display:block;width:15px;height:15px;margin:0 auto;"></div>',
                                      4=>$row->cantidad_um.' '.$row->medida,
                                      5=>$row->ancho.' cm',
                                      6=>$mov_entrada,                                           
                                      7=>$row->nombre,
                                      8=>$row->id_lote.'-'.$row->consecutivo,
                                      9=>$row->id,
                                      10=>$row->num_partida,
                                      11=>$row->metros,
                                      12=>$row->kilogramos,        
                                      13=>$row->imagen,                              
                                      14=>$row->almacen,
                                      15=>$row->codigo_contable,
                                      16=>$row->id_estatus,
                                    );
                      }

                      return json_encode ( array(
                        "draw"            => intval( $data['draw'] ),
                        "recordsTotal"    => $registros_filtrados, 
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
      
   
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////

////////////////////////"http://inventarios.dev.com/pedidos"///////////////////////////////////////////////////////////////
    
    // 1ra regilla de "/pedidos"
      public function total_apartados_pendientes($where){

              $this->db->from($this->registros.' as m');
              $this->db->where($where);
        
              $this->db->group_by("m.id_usuario_apartado, m.id_tipo_pedido,m.id_tipo_factura, m.id_cliente_apartado");
              $result = $this->db->get();
              $cant = $result->num_rows();
     
              if ( $cant > 0 )
                 return $cant;
              else
                 return 0;         

       }     

    public function buscador_apartados_pendientes($data){
          $perfil= $this->session->userdata('id_perfil'); 
          $id_session = $this->session->userdata('id');

          $id_almacen= $data['id_almacen'];
          $cadena = addslashes($data['search']['value']);
          $inicio = $data['start'];
          $largo = $data['length'];

          $columa_order = $data['order'][0]['column'];
                 $order = $data['order'][0]['dir'];

          switch ($columa_order) {
                   case '0':
                        $columna = 'u.nombre, u.apellidos';
                     break;
                   case '1':
                        $columna = 'pr.nombre';
                     break;
                   case '2':
                        $columna = 'p.nombre';
                     break;
                   case '3':
                        $columna = 'm.fecha_apartado';
                     break;
                   case '4':
                        $columna = 'm.id_apartado';
                     break;
                   case '5':
                          $columna = 'm.fecha_vencimiento';
                     break;
                   
                   default:
                       $columna = 'u.nombre, u.apellidos';
                     break;
                 }                 

          
          $fecha_hoy =  date("Y-m-d h:ia"); 
          $hoy = new DateTime($fecha_hoy);

          //$id_session = $this->db->escape($this->session->userdata('id'));

          $this->db->select("SQL_CALC_FOUND_ROWS *", FALSE); //

          $this->db->select('m.id_usuario_apartado,m.id_apartado, m.id_cliente_apartado,m.fecha_apartado,id_prorroga,m.fecha_vencimiento');  //fecha falta
          $this->db->select('p.nombre comprador ');  
          $this->db->select('m.consecutivo_venta');  
          $this->db->select('CONCAT(u.nombre,"  ",u.apellidos) as vendedor', FALSE);
          $this->db->select('pr.nombre as dependencia', FALSE);

          $this->db->select('
                        CASE m.id_apartado
                          WHEN "1" THEN "Apartado Individual"
                           WHEN "2" THEN "Apartado Confirmado"
                           WHEN "3" THEN "Disponibilidad Salida"
                           ELSE "No Apartado"
                        END AS tipo_apartado
         ',False);          


          $this->db->select('
                        CASE m.id_apartado
                          WHEN "1" THEN "ab1d1d"
                           WHEN "2" THEN "f1a914"
                           WHEN "3" THEN "14b80f"
                           ELSE "No Apartado"
                        END AS color_apartado
         ',False);  

          $this->db->select("a.almacen");
          $this->db->select("tp.tipo_pedido");          
          $this->db->select("tf.tipo_factura");          

          $this->db->select('m.id_tipo_pedido,m.id_tipo_factura', FALSE);

          $this->db->from($this->registros.' as m');
          $this->db->join($this->almacenes.' As a' , 'a.id = m.id_almacen AND a.activo=1');
          $this->db->join($this->usuarios.' As u' , 'u.id = m.id_usuario_apartado','LEFT');
          $this->db->join($this->proveedores.' As pr', 'u.id_cliente = pr.id','LEFT');
          $this->db->join($this->proveedores.' As p' , 'p.id = m.id_cliente_apartado','LEFT');
          $this->db->join($this->tipos_pedidos.' As tp' , 'tp.id = m.id_tipo_pedido','LEFT');
          $this->db->join($this->tipos_facturas.' As tf' , 'tf.id = m.id_tipo_factura','LEFT');

          //filtro de busqueda

          if ($id_almacen!=0) {
              $id_almacenid = ' AND ( m.id_almacen =  '.$id_almacen.' ) ';  
          } else {
              $id_almacenid = '';
          }            

          $where = '(
                      (
                        ( m.id_apartado = 2 ) or ( m.id_apartado = 3 ) 
                      )'.$id_almacenid.' 
                       AND
                      (
                        ( CONCAT(u.nombre," ",u.apellidos) LIKE  "%'.$cadena.'%" ) OR
                        ( pr.nombre LIKE  "%'.$cadena.'%" ) OR (p.nombre LIKE  "%'.$cadena.'%") OR
                        ((DATE_FORMAT((m.fecha_apartado),"%d-%m-%Y") ) LIKE  "%'.$cadena.'%") OR
                        ( "Apartado Individual" LIKE  "%'.$cadena.'%" ) OR
                        ( "Apartado Confirmado" LIKE  "%'.$cadena.'%" ) OR
                        ( "Disponibilidad Salida" LIKE  "%'.$cadena.'%" ) OR
                        ( "En proceso" LIKE  "%'.$cadena.'%" ) 
                       )
            )';   

          $where_total = '( m.id_apartado = 2 ) or ( m.id_apartado = 3 ) '.$id_almacenid;

         if ( $perfil == 3 )  { 

            //SELECT * FROM `inven_registros_entradas` WHERE (( id_apartado = 2 ) OR ( id_apartado = 3 ))
            $where .=' AND (m.id_usuario_apartado = "'.$id_session.'")';
            $where_total .=' AND (m.id_usuario_apartado = "'.$id_session.'")';

         }



          $this->db->where($where);
          $this->db->order_by($columna, $order); 
          $this->db->group_by("m.id_usuario_apartado, m.id_tipo_pedido,m.id_tipo_factura, m.id_cliente_apartado,m.consecutivo_venta");

          //ordenacion

          //paginacion
          $this->db->limit($largo,$inicio); 


          $result = $this->db->get();

              if ( $result->num_rows() > 0 ) {

                    $cantidad_consulta = $this->db->query("SELECT FOUND_ROWS() as cantidad");
                    $found_rows = $cantidad_consulta->row(); 
                    $registros_filtrados =  ( (int) $found_rows->cantidad);


                  foreach ($result->result() as $row) {
                        $fecha_venc= date( 'd-m-Y h:ia', strtotime($row->fecha_vencimiento));
                        $actual = new DateTime($fecha_venc);
                        $diferencia_fecha =  date_diff($actual,$hoy);

                        if ($row->id_prorroga==0) {  
                          $mi_vencimiento =    $diferencia_fecha->format('%R%h hrs');
                        } else  
                        {
                          $mi_vencimiento = "En proceso";
                        } 

                            if ($id_almacen!=0) {
                                
                                $apartar = $row->tipo_apartado.'<div style="margin-right: 15px;float:left;background-color:#'.$row->color_apartado.';width:15px;height:15px;"></div>';
                            } else {
                                $apartar ='-';
                                $row->almacen ='Todos';
                            }                                        

                            $dato[]= array(
                                      0=>$row->vendedor,
                                      1=>$row->dependencia,
                                      2=>$row->comprador,
                                      3=>date( 'd-m-Y', strtotime($row->fecha_apartado)),
                                      4=>$apartar,
                                      5=>$mi_vencimiento, //hora vencimiento
                                      6=>$row->id_usuario_apartado, 
                                      7=>$row->id_cliente_apartado,
                                      8=>$row->id_prorroga,
                                      9=>$perfil,
                                      10=>$row->almacen,
                                      11=>$row->consecutivo_venta,
                                      12=>$row->tipo_pedido,
                                      13=>$row->tipo_factura,                                      
                                      14=>$row->id_apartado, 
                                      15=>$row->id_tipo_pedido,   
                                      16=>$row->id_tipo_factura,   

                                    );
                      }


                      

                      return json_encode ( array(
                        "draw"            => intval( $data['draw'] ),
                        "recordsTotal"    => intval( self::total_apartados_pendientes($where_total) ),  
                        "recordsFiltered" => $registros_filtrados, 
                        "data"            =>  $dato 
                      ));
                    
              }   
              else {
                  $output = array(
                  "draw" =>  intval( $data['draw'] ),
                  "recordsTotal" => 0, //intval( self::total_apartados_pendientes($where_total) ),  
                  "recordsFiltered" =>0,
                  "aaData" => array()
                  );
                  $array[]="";
                  return json_encode($output);
                  

              }

              $result->free_result();           
      }  


 

 


    //2da regilla de "/pedidos"

      public function total_pedidos_pendientes($where){

              $this->db->from($this->registros.' as m');
              $this->db->where($where);
        
              $this->db->group_by("m.id_usuario_apartado,m.id_tipo_pedido,m.id_tipo_factura, m.id_cliente_apartado");
              $result = $this->db->get();
              $cant = $result->num_rows();
     
              if ( $cant > 0 )
                 return $cant;
              else
                 return 0;         

       }     

    public function buscador_pedidos_pendientes($data){

          $cadena = addslashes($data['search']['value']);
          $inicio = $data['start'];
          $largo = $data['length'];
          $id_almacen= $data['id_almacen'];


          $columa_order = $data['order'][0]['column'];
                 $order = $data['order'][0]['dir'];

          switch ($columa_order) {
                   case '0':
                        $columna = 'u.nombre, u.apellidos';
                     break;
                   case '1':
                        $columna = 'pr.nombre';
                     break;
                   case '2':
                        $columna = 'm.id_cliente_apartado';
                     break;
                   case '3':
                        $columna = 'm.fecha_apartado';
                     break;
                   case '4':
                        $columna = 'm.id_apartado';
                     break;
                   case '5':
                          $columna = 'm.fecha_vencimiento';
                     break;
                   
                   default:
                       $columna = 'u.nombre, u.apellidos';
                     break;
                 }                    

          $perfil= $this->session->userdata('id_perfil'); 
          
          $id_session = $this->session->userdata('id');
          $fecha_hoy =  date("Y-m-d h:ia"); 
          $hoy = new DateTime($fecha_hoy);


          $this->db->select("SQL_CALC_FOUND_ROWS *", FALSE); //



          $this->db->select('m.id_usuario_apartado, m.id_cliente_apartado,m.fecha_apartado,m.id_prorroga,m.fecha_vencimiento');  //fecha falta
          $this->db->select('p.nombre comprador,m.id_apartado ');  
          $this->db->select('CONCAT(u.nombre,"  ",u.apellidos) as vendedor', FALSE);
          $this->db->select('pr.nombre as dependencia', FALSE);

          $this->db->select('
                        CASE m.id_apartado
                          WHEN "4" THEN "Pedido Individual"
                           WHEN "5" THEN "Pedido Confirmado"
                           WHEN "6" THEN "Disponibilidad Salida"
                           ELSE "No Pedido"
                        END AS tipo_apartado
         ',False);          


          $this->db->select('
                        CASE m.id_apartado
                           WHEN "4" THEN "ab1d1d"
                           WHEN "5" THEN "f1a914"
                           WHEN "6" THEN "14b80f"
                           ELSE "No Pedido"
                        END AS color_apartado
         ',False);  

          $this->db->select("a.almacen");          
          
          $this->db->select("tp.tipo_pedido");          
          
          $this->db->select("tf.tipo_factura");          

          $this->db->select('m.id_tipo_pedido,m.id_tipo_factura', FALSE);

          $this->db->select("prov.nombre cliente_pedido");          

          $this->db->from($this->registros.' as m');
          $this->db->join($this->almacenes.' As a' , 'a.id = m.id_almacen AND a.activo=1');
          $this->db->join($this->usuarios.' As u' , 'u.id = m.id_usuario_apartado','LEFT');
          $this->db->join($this->proveedores.' As pr', 'u.id_cliente = pr.id','LEFT');
          $this->db->join($this->proveedores.' As p' , 'p.id = m.id_cliente_apartado','LEFT');
          $this->db->join($this->tipos_pedidos.' As tp' , 'tp.id = m.id_tipo_pedido','LEFT');
          $this->db->join($this->tipos_facturas.' As tf' , 'tf.id = m.id_tipo_factura','LEFT');

          $this->db->join($this->proveedores.' As prov' , 'prov.id = m.consecutivo_venta','LEFT');
         
          //filtro de busqueda
          if ($id_almacen!=0) {
              $id_almacenid = ' AND ( m.id_almacen =  '.$id_almacen.' ) ';  
          } else {
              $id_almacenid = '';
          } 


          $where = '(
                      (
                        ( m.id_apartado = 5 ) or ( m.id_apartado = 6 ) 
                      )'.$id_almacenid .' 
                    AND
                      (
                        ( prov.nombre LIKE  "%'.$cadena.'%" ) OR
                        ( CONCAT(u.nombre," ",u.apellidos) LIKE  "%'.$cadena.'%" ) OR
                        ( pr.nombre LIKE  "%'.$cadena.'%" ) OR (m.id_cliente_apartado LIKE  "%'.$cadena.'%") OR
                        ((DATE_FORMAT((m.fecha_apartado),"%d-%m-%Y") ) LIKE  "%'.$cadena.'%") OR
                        ( "Pedido Individual" LIKE  "%'.$cadena.'%" ) OR
                        ( "Pedido Confirmado" LIKE  "%'.$cadena.'%" ) OR
                        ( "Disponibilidad Salida" LIKE  "%'.$cadena.'%" ) OR
                        ( "En proceso" LIKE  "%'.$cadena.'%" ) 

                        
                       )                     

            )';   

          $where_total = '( m.id_apartado = 5 ) or ( m.id_apartado = 6 )'.$id_almacenid;

        if ( $perfil == 4 )  { 

            //SELECT * FROM `inven_registros_entradas` WHERE (( id_apartado = 5 ) OR ( id_apartado = 6 ))
            $where .=' AND (m.id_usuario_apartado = "'.$id_session.'")';
            $where_total .=' AND (m.id_usuario_apartado = "'.$id_session.'")';

         }




          $this->db->where($where);
          $this->db->order_by($columna, $order); 
          $this->db->group_by("m.id_usuario_apartado,m.id_tipo_pedido,m.id_tipo_factura, m.id_cliente_apartado");

          //ordenacion

          //paginacion
          $this->db->limit($largo,$inicio); 


          $result = $this->db->get();

              if ( $result->num_rows() > 0 ) {

                    $cantidad_consulta = $this->db->query("SELECT FOUND_ROWS() as cantidad");
                    $found_rows = $cantidad_consulta->row(); 
                    $registros_filtrados =  ( (int) $found_rows->cantidad);


                  foreach ($result->result() as $row) {

                        $fecha_venc= date( 'd-m-Y h:ia', strtotime($row->fecha_vencimiento));
                        $actual = new DateTime($fecha_venc);
                        $diferencia_fecha =  date_diff($actual,$hoy);

                        if ($row->id_prorroga==0) {  
                          $mi_vencimiento =    $diferencia_fecha->format('%R%h hrs');
                        } else  
                        {
                          $mi_vencimiento = "En proceso";
                        } 

                            if ($id_almacen!=0) {
                                
                                $apartar = $row->tipo_apartado.'<div style="margin-right: 15px;float:left;background-color:#'.$row->color_apartado.';width:15px;height:15px;"></div>';
                            } else {
                                $apartar ='-';
                                $row->almacen ='Todos';
                            }  

                            $dato[]= array(
                                      0=>$row->vendedor,
                                      1=>$row->dependencia,
                                      2=>$row->cliente_pedido.'<br/><b>Nro.'.$row->id_cliente_apartado.'</b>',
                                      3=>date( 'd-m-Y', strtotime($row->fecha_apartado)),
                                      4=>$apartar,
                                      5=>$mi_vencimiento, //hora vencimiento
                                      6=>$row->id_cliente_apartado,
                                      7=>$row->id_prorroga,
                                      8=>$row->almacen,
                                      9=>$row->tipo_pedido,
                                      10=>$row->tipo_factura,
                                      11=>$row->id_apartado,
                                      12=>$row->id_tipo_pedido,   
                                      13=>$row->id_tipo_factura,   

                                    );
                      }



                      return json_encode ( array(
                        "draw"            => intval( $data['draw'] ),
                        "recordsTotal"    => intval( self::total_pedidos_pendientes($where_total) ),  
                        "recordsFiltered" => $registros_filtrados, 
                        "data"            =>  $dato 
                      ));
                    
              }   
              else {
                  $output = array(
                  "draw" =>  intval( $data['draw'] ),
                  "recordsTotal" => 0, //intval( self::total_pedidos_pendientes($where_total) ),  
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
        
              $this->db->group_by("m.mov_salida, m.id_usuario_apartado, m.id_tipo_pedido,m.id_tipo_factura, m.id_cliente_apartado");
              $result = $this->db->get();
              $cant = $result->num_rows();
     
              if ( $cant > 0 )
                 return $cant;
              else
                 return 0;         

       }     

    public function buscador_pedidos_completo($data){

          $cadena = addslashes($data['search']['value']);
          $inicio = $data['start'];
          $largo = $data['length'];
          $id_almacen= $data['id_almacen'];

          $columa_order = $data['order'][0]['column'];
                 $order = $data['order'][0]['dir'];

          switch ($columa_order) {
                   case '0':
                        $columna = 'u.nombre, u.apellidos';
                     break;
                   case '1':
                        $columna = 'pr.nombre';
                     break;
                   case '2':
                        $columna = 'p.nombre';  //,m.id_cliente_apartado,
                     break;
                   case '3':
                        $columna = 'm.fecha_apartado';
                     break;
                   case '4':
                        $columna = 'm.tipo_salida,m.id_apartado';
                     break;
                   case '5':
                          $columna = 'm.mov_salida';
                     break;
                   
                   default:
                       $columna = 'u.nombre, u.apellidos';
                     break;
                 }            
          

          //$id_session = $this->db->escape($this->session->userdata('id'));
          $perfil= $this->session->userdata('id_perfil'); 
          $id_session = $this->session->userdata('id');       

          $this->db->select("SQL_CALC_FOUND_ROWS *", FALSE); //

          $this->db->select('m.id_usuario_apartado, m.id_cliente_apartado,m.fecha_apartado');  //fecha falta
          $this->db->select('p.nombre comprador, m.id_apartado apartado, m.mov_salida ');  
          $this->db->select('CONCAT(u.nombre,"  ",u.apellidos) as vendedor', FALSE);
          $this->db->select('pr.nombre as dependencia', FALSE);
          $this->db->select('m.id_tipo_pedido,m.id_tipo_factura', FALSE);
          
 
          $this->db->select('
                        CASE m.tipo_salida
                           WHEN 1 THEN "Salida Parcial"
                           WHEN 2 THEN "Salida Total"
                           ELSE "Salida Total"
                        END AS tipo_apartado
         ',False);  


          $this->db->select('
                        CASE m.id_apartado
                           WHEN "4" THEN "ab1d1d"
                           WHEN "5" THEN "f1a914"
                           WHEN "6" THEN "14b80f"
                           ELSE "No Pedido"
                        END AS color_apartado
         ',False);  

          $this->db->select('
                        CASE m.id_apartado
                           WHEN "3" THEN "(Vendedor)"
                           WHEN "6" THEN "(Tienda)"
                           ELSE "No Pedido"
                        END AS tipo_pedido
         ',False);  

          $this->db->select("a.almacen");
          $this->db->select("m.consecutivo_venta");
          $this->db->select("tp.tipo_pedido tip_pedido", false);          
          $this->db->select("tf.tipo_factura");     
          $this->db->select("prov.nombre cliente_pedido");          



          $this->db->from($this->historico_registros_salidas.' as m');
          $this->db->join($this->almacenes.' As a' , 'a.id = m.id_almacen AND a.activo=1');
          $this->db->join($this->usuarios.' As u' , 'u.id = m.id_usuario_apartado','LEFT');
          $this->db->join($this->proveedores.' As pr', 'u.id_cliente = pr.id','LEFT');
          $this->db->join($this->proveedores.' As p' , 'p.id = m.id_cliente_apartado','LEFT');
          $this->db->join($this->tipos_pedidos.' As tp' , 'tp.id = m.id_tipo_pedido','LEFT');
          $this->db->join($this->tipos_facturas.' As tf' , 'tf.id = m.id_tipo_factura','LEFT');
          $this->db->join($this->proveedores.' As prov' , 'prov.id = m.consecutivo_venta','LEFT');





          if ($id_almacen!=0) {
              $id_almacenid = ' AND ( m.id_almacen =  '.$id_almacen.' ) ';  
          } else {
              $id_almacenid = '';
          }          

          //$id_almacenid = '';
          //filtro de busqueda

          $where = '(
                      (
                        ( m.id_apartado = 3 ) or ( m.id_apartado = 6 ) 
                      )'.$id_almacenid .' 
                       AND
                      (
                        ( prov.nombre LIKE  "%'.$cadena.'%" ) OR
                        ( CONCAT(u.nombre," ",u.apellidos) LIKE  "%'.$cadena.'%" ) OR
                        ( pr.nombre LIKE  "%'.$cadena.'%" ) OR (p.nombre LIKE  "%'.$cadena.'%") OR
                        (m.id_cliente_apartado LIKE  "%'.$cadena.'%") OR 
                        ((DATE_FORMAT((m.fecha_apartado),"%d-%m-%Y") ) LIKE  "%'.$cadena.'%") OR
                        (m.mov_salida LIKE  "%'.$cadena.'%") OR 
                        ( "Salida Parcial" LIKE  "%'.$cadena.'%" ) OR
                        ( "Salida Total" LIKE  "%'.$cadena.'%" ) OR
                        ( "(Vendedor)" LIKE  "%'.$cadena.'%" ) OR
                        ( "(Tienda)" LIKE  "%'.$cadena.'%" ) 
                       )

            )';   


          
          $where_total = '( m.id_apartado = 3 ) or ( m.id_apartado = 6 )'.$id_almacenid; 

          if ( ( $perfil == 3 ) OR ( $perfil == 4 ) ) { 
            //SELECT * FROM `inven_registros_entradas` WHERE (( id_apartado = 2 ) OR ( id_apartado = 3 ))
            $where .=' AND (m.id_usuario_apartado = "'.$id_session.'")';
            $where_total .=' AND (m.id_usuario_apartado = "'.$id_session.'")';
          }
          $this->db->where($where);


          $this->db->order_by($columna, $order); 

          $this->db->group_by("m.mov_salida, m.id_usuario_apartado,m.id_tipo_pedido,m.id_tipo_factura, m.id_cliente_apartado");


          //paginacion
          $this->db->limit($largo,$inicio); 


          $result = $this->db->get();

              if ( $result->num_rows() > 0 ) {

                    $cantidad_consulta = $this->db->query("SELECT FOUND_ROWS() as cantidad");
                    $found_rows = $cantidad_consulta->row(); 
                    $registros_filtrados =  ( (int) $found_rows->cantidad);


                  foreach ($result->result() as $row) {

                              if ($row->apartado==3) {
                                 $client  = $row->comprador;
                                     $num = $row->consecutivo_venta; //$row->comprador;

                              } else  {
                                $client = $row->cliente_pedido;
                                 $num= $row->id_cliente_apartado;



                              }   

                            $dato[]= array(
                                      0=>$row->vendedor,
                                      1=>$row->dependencia,
                                      2=>$client, //$num, 
                                      3=>date( 'd-m-Y', strtotime($row->fecha_apartado)),
                                      4=>$row->tipo_apartado.' '.$row->tipo_pedido,                                      
                                      5=>$row->mov_salida,
                                      6=>$row->id_apartado,  //$row->id_cliente_apartado,
                                      7=>$row->almacen,
                                      8=>$num, //$row->consecutivo_venta,
                                      9=>$row->tip_pedido,
                                      10=>$row->tipo_factura,   
                                      11=>$row->id_tipo_pedido,   
                                      12=>$row->id_tipo_factura,   

                                                                            
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




////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//"Regilla detalle" de la 1ra PARA "Pedidos de vendedores"
  //http://inventarios.dev.com/apartado_detalle/MGNjNTUxMGYtYzQ1Mi0xMWU0LThhZGEtNzA3MWJjZTE4MWMz/MTE=

   //procesando los detalles de un apartado especifico

       

   public function buscador_apartados_detalle($data){
          $cadena = addslashes($data['search']['value']);
          $inicio = $data['start'];
          $largo = $data['length'];
          $id_almacen= $data['id_almacen'];
          $consecutivo_venta= $data['consecutivo_venta'];


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
                        $columna = 'm.movimiento';
                        
                     break;
                   case '5':
                        $columna = 'm.ancho';
                        
                     break;
                   case '6':
                              $columna= 'm.precio';
                     break;
                   case '7':
                              $columna= 'm.id_lote, m.consecutivo';  
                     break;
                   case '8':
                              $columna= 'm.num_partida';
                     break;

                   default:
                       $columna = 'm.codigo';
                     break;
                 }              

          $id_usuario = $data['id_usuario'];
          $id_cliente = $data['id_cliente'];

          $id_session = $this->db->escape($this->session->userdata('id'));

          $this->db->select("SQL_CALC_FOUND_ROWS(m.id_usuario_apartado)"); //

          //,m.movimiento,m.consecutivo, m.id_usuario_apartado, m.id_cliente_apartado, c.color nombre_color,
          $this->db->select('m.peso_real, m.id_estatus, m.devolucion, m.num_partida');  //fecha falta
          $this->db->select('p.nombre comprador');  
          $this->db->select('pr.nombre cliente');  
          $this->db->select('CONCAT(u.nombre,"  ",u.apellidos) as vendedor', FALSE);
          $this->db->select('m.codigo,m.id_descripcion, m.id_lote,m.precio,m.iva, m.fecha_apartado');  
          $this->db->select('c.hexadecimal_color, m.ancho, um.medida');
          $this->db->select('c.color,m.cantidad_um,m.movimiento,m.consecutivo');
          
          $this->db->select("( CASE WHEN m.id_medida = 1 THEN m.cantidad_um ELSE 0 END ) AS metros");
          $this->db->select("( CASE WHEN m.id_medida = 2 THEN m.cantidad_um ELSE 0 END ) AS kilogramos");
          $this->db->select('
                        CASE m.id_apartado
                          WHEN "1" THEN "Apartado Individual"
                           WHEN "2" THEN "Apartado Confirmado"
                           WHEN "3" THEN "Disponibilidad Salida"
                           ELSE "No Apartado"
                        END AS tipo_apartado
         ',False);          
          $this->db->select('
                        CASE m.id_apartado
                          WHEN "1" THEN "ab1d1d"
                           WHEN "2" THEN "f1a914"
                           WHEN "3" THEN "14b80f"
                           ELSE "No Apartado"
                        END AS color_apartado
         ',False);          

          $this->db->select("a.almacen");
          $this->db->select("m.id_factura,m.id_fac_orig,m.id_tipo_factura,m.id_tipo_pedido");
          $this->db->select("tp.tipo_pedido");          
          $this->db->select("tf.tipo_factura");  
          $this->db->select("tff.tipo_factura t_factura");  
          $this->db->select("prod.codigo_contable");  
          
          $this->db->from($this->registros.' as m');
          $this->db->join($this->almacenes.' As a' , 'a.id = m.id_almacen AND a.activo=1');
          $this->db->join($this->productos.' As prod' , 'prod.referencia = m.referencia'); //,'LEFT'
          $this->db->join($this->usuarios.' As u' , 'u.id = m.id_usuario_apartado'); //,'LEFT'
          $this->db->join($this->proveedores.' As pr', 'u.id_cliente = pr.id','LEFT');
          $this->db->join($this->proveedores.' As p' , 'p.id = m.id_cliente_apartado'); //,'LEFT'
          $this->db->join($this->unidades_medidas.' As um' , 'um.id = m.id_medida'); //,'LEFT'
          $this->db->join($this->colores.' As c', 'm.id_color = c.id'); //,'LEFT'
          $this->db->join($this->tipos_pedidos.' As tp' , 'tp.id = m.id_tipo_pedido'); //,'LEFT'
          $this->db->join($this->tipos_facturas.' As tf' , 'tf.id = m.id_tipo_factura'); //,'LEFT'
          $this->db->join($this->tipos_facturas.' As tff' , 'tff.id = m.id_factura'); //,'LEFT'



          //filtro de busqueda

          if ($id_almacen!=0) {
              $id_almacenid = ' AND ( m.id_almacen =  '.$id_almacen.' ) ';  
          } else {
              $id_almacenid = '';
          } 
          
          $where = '(
                      ( m.id_tipo_pedido =  '.$data["id_tipo_pedido"].' )  AND ( m.id_tipo_factura =  '.$data["id_tipo_factura"].' )  AND 

                      (
                        ( (m.id_apartado = 2) OR (m.id_apartado = 3) ) AND ( m.id_usuario_apartado = "'.$id_usuario.'" ) AND ( m.id_cliente_apartado = "'.$id_cliente.'" ) AND ( m.consecutivo_venta = '.$consecutivo_venta.' )
                      )'.$id_almacenid .'  
                       AND
                      (
                        ( CONCAT(m.cantidad_um," ",um.medida) LIKE  "%'.$cadena.'%" ) OR (CONCAT(m.ancho," cm") LIKE  "%'.$cadena.'%")  OR
                        ( m.codigo LIKE  "%'.$cadena.'%" ) OR (m.id_descripcion LIKE  "%'.$cadena.'%") OR (c.color LIKE  "%'.$cadena.'%")  OR
                         (CONCAT(m.id_lote,"-",m.consecutivo) LIKE  "%'.$cadena.'%") OR ( m.movimiento LIKE  "%'.$cadena.'%" ) OR                    
                         (m.precio LIKE  "%'.$cadena.'%")
                       )
            )';   
/*
          $where_total = '( (m.id_apartado = 2) OR (m.id_apartado = 3) ) AND ( m.id_usuario_apartado = "'.$id_usuario.'" ) AND ( m.id_cliente_apartado = "'.$id_cliente.'" )  AND ( m.consecutivo_venta = '.$consecutivo_venta.' ) '.$id_almacenid;
*/
          $where_total =$where;

          $this->db->where($where);
          $this->db->order_by($columna, $order); 
          //paginacion
          $this->db->limit($largo,$inicio); 

          $result = $this->db->get();

              if ( $result->num_rows() > 0 ) {

                    $cantidad_consulta = $this->db->query("SELECT FOUND_ROWS() as cantidad");
                    $found_rows = $cantidad_consulta->row(); 
                    $registros_filtrados =  ( (int) $found_rows->cantidad);


                  foreach ($result->result() as $row) {

                  $retorno= "apartado_detalle/".base64_encode($id_usuario)."/".base64_encode($id_cliente).'/'.base64_encode($id_almacen).'/'.base64_encode($consecutivo_venta).'/'.base64_encode($row->id_tipo_pedido).'/'.base64_encode($row->id_tipo_factura);
                    
                          $mi_usuario = $row->vendedor;
                          $mi_cliente = $row->cliente;
                          $mi_comprador = $row->comprador;
                          $tipo_apartado = $row->tipo_apartado;
                          $color_apartado = $row->color_apartado;
                          $mi_fecha = date( 'd-m-Y', strtotime($row->fecha_apartado));
                          $mi_hora = date( 'h:ia', strtotime($row->fecha_apartado));

                            $dato[]= array(
                                      0=>$row->codigo,
                                      1=>$row->id_descripcion,
                                      2=>$row->color.
                                       '<div style="background-color:#'.$row->hexadecimal_color.';display:block;width:15px;height:15px;margin:0 auto;"></div>',
                                      3=>$row->cantidad_um.' '.$row->medida,
                                      4=>
                                           '<a style="  padding: 1px 0px 1px 0px;" href="'.base_url().'procesar_entradas/'.base64_encode($row->movimiento).'/'.base64_encode($row->devolucion).'/'.base64_encode($retorno).'/'.base64_encode($row->id_fac_orig).'/'.base64_encode($row->id_estatus).'" 
                                               type="button" class="btn btn-success btn-block">'.$row->movimiento.'</a>', 
                                      5=>$row->ancho.' cm',
                                      6=>$row->precio,
                                      7=>$row->iva,                                  
                                      8=>$row->id_lote.'-'.$row->consecutivo,       
                                      9=>$row->num_partida,
                                      10=>$row->almacen,
                                      11=>$row->id_factura,
                                      12=>$row->id_tipo_factura,
                                      13=>$row->id_tipo_pedido,
                                      14=>$row->t_factura,    
                                      15=>$row->peso_real,
                                      16=>$row->metros,
                                      17=>$row->kilogramos,   
                                      18=>$row->codigo_contable,   
                                      19=>$row->id_fac_orig,   
                                      20=>$row->id_estatus,

                                      
                                                                     
                                    );
                            $tipo_pedido=$row->tipo_pedido;
                            $tipo_factura=$row->tipo_factura; 
                            $id_tipo_pedido=$row->id_tipo_pedido; 
                            $id_tipo_factura=$row->id_tipo_factura; 
                            
                      }

                      return json_encode ( array(
                        "draw"            => intval( $data['draw'] ),
                        "recordsTotal"     =>$registros_filtrados, 
                        "recordsFiltered" => $registros_filtrados, 
                        "data"            =>  $dato,
                         "datos"            =>  array("usuario"=>$mi_usuario, "tipo_apartado"=>$tipo_apartado, "color_apartado"=>$color_apartado, "comprador"=>$mi_comprador, "cliente"=>$mi_cliente, "mi_fecha"=>$mi_fecha, "mi_hora"=>$mi_hora, "tipo_pedido"=>$tipo_pedido,
                            "tipo_factura"=>$tipo_factura,
                             "id_tipo_pedido"=>$id_tipo_pedido,
                              "id_tipo_factura"=>$id_tipo_factura,
                             ),
                          "totales"            =>  array(
                            "pieza"=>intval( self::totales_campos_apartado($where_total)->pieza ),
                            "metro"=>floatval( self::totales_campos_apartado($where_total)->metros ),
                            "kilogramo"=>floatval( self::totales_campos_apartado($where_total)->kilogramos ), 
                           ), 
                      ));
              }   
              else {
                  $output = array(
                  "draw" =>  intval( $data['draw'] ),
                  "recordsTotal" => 0, 
                  "recordsFiltered" =>0,
                  "aaData" => array(),
                  "totales"            =>  array(
                              "pieza"=>intval( self::totales_campos_apartado($where_total)->pieza ),
                              "metro"=>floatval( self::totales_campos_apartado($where_total)->metros ),
                              "kilogramo"=>floatval( self::totales_campos_apartado($where_total)->kilogramos ), 
                             ),                  
                  );
                  $array[]="";
                  return json_encode($output);
              }
              $result->free_result();           
      }        

 
 public function totales_campos_apartado($where){

           $this->db->select("SUM((m.id_medida =1) * cantidad_um) as metros", FALSE);
              $this->db->select("SUM((m.id_medida =2) * cantidad_um) as kilogramos", FALSE);
              $this->db->select("COUNT(m.id_medida) as 'pieza'");
             
              $this->db->from($this->registros.' as m');
              $this->db->join($this->almacenes.' As a' , 'a.id = m.id_almacen AND a.activo=1');
              $this->db->join($this->usuarios.' As u' , 'u.id = m.id_usuario_apartado'); //,'LEFT'
              $this->db->join($this->proveedores.' As pr', 'u.id_cliente = pr.id','LEFT');
              $this->db->join($this->proveedores.' As p' , 'p.id = m.id_cliente_apartado'); //,'LEFT'
              $this->db->join($this->unidades_medidas.' As um' , 'um.id = m.id_medida'); //,'LEFT'
              $this->db->join($this->colores.' As c', 'm.id_color = c.id'); //,'LEFT'
              $this->db->join($this->tipos_pedidos.' As tp' , 'tp.id = m.id_tipo_pedido'); //,'LEFT'
              $this->db->join($this->tipos_facturas.' As tf' , 'tf.id = m.id_tipo_factura'); //,'LEFT'
              $this->db->join($this->tipos_facturas.' As tff' , 'tff.id = m.id_factura'); //,'LEFT'


              $this->db->where($where);

             $result = $this->db->get();
          
              if ( $result->num_rows() > 0 )
                 return $result->row();
              else
                 return False;
              $result->free_result();              

       }  

 

    public function buscador_pedido_especifico($data){
          $cadena = addslashes($data['search']['value']);
          $inicio = $data['start'];
          $largo = $data['length'];

          $columa_order = $data['order'][0]['column'];
                 $order = $data['order'][0]['dir'];
                 $id_almacen= $data['id_almacen'];

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
                        $columna = 'm.movimiento';
                        
                     break;
                   case '5':
                        $columna = 'm.ancho';
                        
                     break;
                   case '6':
                              $columna= 'm.precio';
                     break;
                   case '7':
                              $columna= 'm.id_lote, m.consecutivo';  
                     break;

                   case '8':
                              $columna= 'm.num_partida';  
                     break;                     
                   
                   default:
                       $columna = 'm.codigo';
                     break;
                 }                       

          $num_mov = $data['num_mov'];
          $id_session = $this->db->escape($this->session->userdata('id'));

          $this->db->select("SQL_CALC_FOUND_ROWS(m.id)"); //
          //m.id_cliente_apartado, m.id_usuario_apartado,//m.id_fac_orig,
          $this->db->select('m.id_estatus, m.peso_real,  m.devolucion, m.num_partida');  //fecha falta
          $this->db->select('pr.nombre dependencia');  
          $this->db->select('CONCAT(u.nombre,"  ",u.apellidos) as cliente', FALSE);
          $this->db->select('m.codigo,m.id_descripcion, m.id_lote,m.precio, m.fecha_apartado,m.iva');  
          $this->db->select('c.hexadecimal_color,c.color, m.ancho, um.medida');
          $this->db->select("( CASE WHEN m.id_medida = 1 THEN m.cantidad_um ELSE 0 END ) AS metros");
          $this->db->select("( CASE WHEN m.id_medida = 2 THEN m.cantidad_um ELSE 0 END ) AS kilogramos");
          $this->db->select('
                        CASE m.id_apartado
                          WHEN "4" THEN "Pedido Individual"
                           WHEN "5" THEN "Pedido Confirmado"
                           WHEN "6" THEN "Disponibilidad Salida"
                           ELSE "No Pedido"
                        END AS tipo_apartado
         ',FALSE);          
          $this->db->select('
                        CASE m.id_apartado
                           WHEN "4" THEN "ab1d1d"
                           WHEN "5" THEN "f1a914"
                           WHEN "6" THEN "14b80f"
                           ELSE "No Pedido"
                        END AS color_apartado
         ',FALSE);
          $this->db->select("a.almacen");

          $this->db->select("m.id_factura,m.id_tipo_factura, m.id_tipo_pedido");
          $this->db->select("tp.tipo_pedido");          
          $this->db->select("tf.tipo_factura");  
          $this->db->select("tff.tipo_factura t_factura");  
          $this->db->select("prod.codigo_contable");  
          $this->db->select("prov.nombre cliente_pedido");          
 

          $this->db->select("m.movimiento, m.consecutivo, m.cantidad_um");

          $this->db->from($this->registros.' as m');
          $this->db->join($this->almacenes.' As a' , 'a.id = m.id_almacen AND a.activo=1');
          $this->db->join($this->productos.' As prod' , 'prod.referencia = m.referencia','LEFT');
          $this->db->join($this->usuarios.' As u' , 'u.id = m.id_usuario_apartado','LEFT');
          $this->db->join($this->proveedores.' As pr', 'u.id_cliente = pr.id','LEFT');
          $this->db->join($this->unidades_medidas.' As um' , 'um.id = m.id_medida','LEFT');
          $this->db->join($this->colores.' As c', 'm.id_color = c.id','LEFT');
          $this->db->join($this->tipos_pedidos.' As tp' , 'tp.id = m.id_tipo_pedido','LEFT');
          $this->db->join($this->tipos_facturas.' As tf' , 'tf.id = m.id_tipo_factura','LEFT');
          $this->db->join($this->tipos_facturas.' As tff' , 'tff.id = m.id_factura','LEFT');


        $this->db->join($this->proveedores.' As prov' , 'prov.id = m.consecutivo_venta','LEFT');



          //filtro de busqueda


          if ($id_almacen!=0) {
              $id_almacenid = ' AND ( m.id_almacen =  '.$id_almacen.' ) ';  
          } else {
              $id_almacenid = '';
          }           

          $where = '(
                      (
                      ( m.id_tipo_pedido =  '.$data["id_tipo_pedido"].' )  AND ( m.id_tipo_factura =  '.$data["id_tipo_factura"].' )  AND 

                        (( m.id_apartado = 5 ) or ( m.id_apartado = 6 ) ) AND ( m.id_cliente_apartado = "'.$num_mov.'" )
                      )'.$id_almacenid .' 
                       AND
                      (
                        ( CONCAT(m.cantidad_um," ",um.medida) LIKE  "%'.$cadena.'%" ) OR (CONCAT(m.ancho," cm") LIKE  "%'.$cadena.'%")  OR
                        ( m.codigo LIKE  "%'.$cadena.'%" ) OR (m.id_descripcion LIKE  "%'.$cadena.'%") OR (c.color LIKE  "%'.$cadena.'%")  OR
                         (CONCAT(m.id_lote,"-",m.consecutivo) LIKE  "%'.$cadena.'%") OR ( m.movimiento LIKE  "%'.$cadena.'%" ) OR                    
                         (m.precio LIKE  "%'.$cadena.'%")
                       )
            )';   

          $this->db->where($where);

          $this->db->order_by($columna, $order); 

          /*$where_total ='(( m.id_apartado = 5 ) or ( m.id_apartado = 6 ) ) AND ( m.id_cliente_apartado = "'.$num_mov.'" )'.$id_almacenid;*/
          $where_total =$where;

          //paginacion
          $this->db->limit($largo,$inicio); 

          $result = $this->db->get();

              if ( $result->num_rows() > 0 ) {

                    $cantidad_consulta = $this->db->query("SELECT FOUND_ROWS() as cantidad");
                    $found_rows = $cantidad_consulta->row(); 
                    $registros_filtrados =  ( (int) $found_rows->cantidad);

                  //$retorno= " ";    //pedido_detalle



                  foreach ($result->result() as $row) {

                            $retorno= "pedido_detalle/".base64_encode($num_mov).'/'.base64_encode($id_almacen).'/'.base64_encode($row->id_tipo_pedido).'/'.base64_encode($row->id_tipo_factura);  //apartado detalle


                            $mi_cliente = $row->cliente;
                            $mi_dependencia = $row->dependencia;

                            $tipo_apartado = $row->tipo_apartado;
                            $color_apartado = $row->color_apartado;
                            $mi_fecha = date( 'd-m-Y', strtotime($row->fecha_apartado));
                            $mi_hora = date( 'h:ia', strtotime($row->fecha_apartado));

                               $dato[]= array(
                                      0=>$row->codigo,
                                      1=>$row->id_descripcion,
                                      2=>$row->color.
                                       '<div style="background-color:#'.$row->hexadecimal_color.';display:block;width:15px;height:15px;margin:0 auto;"></div>',
                                      3=>$row->cantidad_um.' '.$row->medida,
                                      4=>
                                           '<a style="  padding: 1px 0px 1px 0px;" href="'.base_url().'procesar_entradas/'.base64_encode($row->movimiento).'/'.base64_encode($row->devolucion).'/'.base64_encode($retorno).'/'.base64_encode($row->id_factura).'/'.base64_encode($row->id_estatus).'"
                                               type="button" class="btn btn-success btn-block">'.$row->movimiento.'</a>', 
                                      5=>$row->ancho.' cm',
                                      6=>$row->precio,
                                      7=>$row->iva,
                                      8=>$row->id_lote.'-'.$row->consecutivo,    
                                      9=>$row->num_partida,
                                      10=>$row->almacen,
                                      11=>$row->id_factura,
                                      12=>$row->id_tipo_factura,
                                      13=>$row->id_tipo_pedido,
                                      14=>$row->t_factura,
                                      15=>$row->peso_real,
                                      16=>$row->metros,
                                      17=>$row->kilogramos,                                         
                                      18=>$row->codigo_contable,    
                                      19=>$row->id_estatus,  
                                                                       
                                    );
                            
                            $tipo_pedido=$row->tipo_pedido;
                            $tipo_factura=$row->tipo_factura; 
                            $id_tipo_pedido=$row->id_tipo_pedido;   
                            $id_tipo_factura=$row->id_tipo_factura;   
                            
                            $cliente_pedido = $row->cliente_pedido;

                      }
                      return json_encode ( array(
                        "draw"            => intval( $data['draw'] ),
                        "recordsTotal"    => $registros_filtrados, 
                        "recordsFiltered" => $registros_filtrados, 
                        "data"            =>  $dato, 
                        "datos"            =>  array("num_mov"=>$num_mov, "cliente_pedido"=>$cliente_pedido, "tipo_apartado"=>$tipo_apartado, "color_apartado"=>$color_apartado, "dependencia"=>$mi_dependencia, "cliente"=>$mi_cliente, "mi_fecha"=>$mi_fecha, "mi_hora"=>$mi_hora,
                            
                            "tipo_pedido"=>$tipo_pedido,
                            "tipo_factura"=>$tipo_factura, 
                            "id_tipo_pedido"=>$id_tipo_pedido, 
                            "id_tipo_factura"=>$id_tipo_factura, 
                         ),
                          "totales"            =>  array(
                            "pieza"=>intval( self::totales_campos_pedido($where_total)->pieza ),
                            "metro"=>floatval( self::totales_campos_pedido($where_total)->metros ),
                            "kilogramo"=>floatval( self::totales_campos_pedido($where_total)->kilogramos ), 
                           ),                        
                      ));
                    
              }   
              else {
                  $output = array(
                  "draw" =>  intval( $data['draw'] ),
                  "recordsTotal" => 0, 
                  "recordsFiltered" =>0,
                  "aaData" => array(),
                  "totales"            =>  array(
                            "pieza"=>intval( self::totales_campos_pedido($where_total)->pieza ),
                            "metro"=>floatval( self::totales_campos_pedido($where_total)->metros ),
                            "kilogramo"=>floatval( self::totales_campos_pedido($where_total)->kilogramos ), 
                           ),
                  );
                  $array[]="";
                  return json_encode($output);
              }
              $result->free_result();           
      }    

  
 public function totales_campos_pedido($where){

           $this->db->select("SUM((m.id_medida =1) * cantidad_um) as metros", FALSE);
              $this->db->select("SUM((m.id_medida =2) * cantidad_um) as kilogramos", FALSE);
              $this->db->select("COUNT(m.id_medida) as 'pieza'");
             
              $this->db->from($this->registros.' as m');
              $this->db->join($this->almacenes.' As a' , 'a.id = m.id_almacen AND a.activo=1');
              $this->db->join($this->usuarios.' As u' , 'u.id = m.id_usuario_apartado','LEFT');
              $this->db->join($this->proveedores.' As pr', 'u.id_cliente = pr.id','LEFT');
              $this->db->join($this->proveedores.' As p' , 'p.id = m.id_cliente_apartado','LEFT');
              $this->db->join($this->unidades_medidas.' As um' , 'um.id = m.id_medida','LEFT');
              $this->db->join($this->colores.' As c', 'm.id_color = c.id','LEFT');
              $this->db->join($this->tipos_pedidos.' As tp' , 'tp.id = m.id_tipo_pedido','LEFT');
              $this->db->join($this->tipos_facturas.' As tf' , 'tf.id = m.id_tipo_factura','LEFT');
              $this->db->join($this->tipos_facturas.' As tff' , 'tff.id = m.id_factura','LEFT');

              $this->db->where($where);

             $result = $this->db->get();
          
              if ( $result->num_rows() > 0 )
                 return $result->row();
              else
                 return False;
              $result->free_result();              

       }  

    public function buscador_completo_especifico($data){

          $cadena = addslashes($data['search']['value']);
          $inicio = $data['start'];
          $largo = $data['length'];

          $columa_order = $data['order'][0]['column'];
                 $order = $data['order'][0]['dir'];
                 $id_almacen= $data['id_almacen'];

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


          $mov_salida = $data['mov_salida'];
          $id_apartado = $data['id_apartado'];
          

          $id_session = $this->db->escape($this->session->userdata('id'));

          //,m.id_fac_orig, m.precio, m.iva,m.id_usuario_apartado,
          //$this->db->select('CONCAT(u.nombre,"  ",u.apellidos) as vendedor', FALSE);

          $this->db->select("SQL_CALC_FOUND_ROWS(m.id)"); //
          $this->db->select('m.id_cliente_apartado, m.num_partida,m.id_estatus,m.cantidad_um');  //fecha falta
          $this->db->select('pr.nombre dependencia');  
          $this->db->select('CONCAT(u.nombre,"  ",u.apellidos) as cliente', false); //
          $this->db->select('m.codigo,m.id_descripcion, m.id_lote, m.fecha_apartado, m.consecutivo');  
          $this->db->select('(m.precio*m.cantidad_um) as sum_precio');           
          $this->db->select("(m.precio*m.cantidad_um*m.iva)/100 as sum_iva");
          $this->db->select("(m.precio*m.cantidad_um)+(((m.precio*m.cantidad_um*m.iva))/100) as sum_total");
          $this->db->select('c.hexadecimal_color,c.color nombre_color, m.ancho, um.medida');
          $this->db->select("( CASE WHEN m.id_medida = 1 THEN m.cantidad_um ELSE 0 END ) AS metros");
          $this->db->select("( CASE WHEN m.id_medida = 2 THEN m.cantidad_um ELSE 0 END ) AS kilogramos");
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
          $this->db->select("tp.tipo_pedido");          

          $this->db->select('
                        CASE m.id_apartado
                           WHEN "3" THEN "ab1d1d"
                           WHEN "6" THEN "14b80f"
                           ELSE "No Pedido"
                        END AS color_apartado
         ',False);  
          $this->db->select("a.almacen");
          $this->db->select("m.id_factura,m.id_factura_original,m.id_tipo_factura, ,m.id_tipo_pedido");
          $this->db->select("tf.tipo_factura");  
          $this->db->select("tff.tipo_factura t_factura");  
          $this->db->select("prod.codigo_contable");  
          $this->db->select("prov.nombre cliente_pedido");          
        


          $this->db->from($this->historico_registros_salidas.' as m');
          $this->db->join($this->almacenes.' As a' , 'a.id = m.id_almacen AND a.activo=1');
          $this->db->join($this->productos.' As prod' , 'prod.referencia = m.referencia','LEFT'); //
          $this->db->join($this->usuarios.' As u' , 'u.id = m.id_usuario_apartado','LEFT'); //
          $this->db->join($this->proveedores.' As pr', 'u.id_cliente = pr.id','LEFT');
          $this->db->join($this->proveedores.' As p' , 'p.id = m.id_cliente_apartado','LEFT'); //
          $this->db->join($this->unidades_medidas.' As um' , 'um.id = m.id_medida','LEFT'); //,'LEFT'
          $this->db->join($this->colores.' As c', 'c.id = m.id_color','LEFT'); //,'LEFT'
          $this->db->join($this->tipos_pedidos.' As tp' , 'tp.id=m.id_tipo_pedido','LEFT'); //,'LEFT'
          $this->db->join($this->tipos_facturas.' As tf' , 'tf.id = m.id_tipo_factura','LEFT'); //
          $this->db->join($this->tipos_facturas.' As tff' , 'tff.id = m.id_factura','LEFT'); //,'LEFT'
          $this->db->join($this->proveedores.' As prov' , 'prov.id = m.consecutivo_venta','LEFT'); //,'LEFT'

          //filtro de busqueda

          if ($id_almacen!=0) {
              $id_almacenid = ' AND ( m.id_almacen =  '.$id_almacen.' ) ';  
          } else {
              $id_almacenid = '';
          } 

          $where = '(
                      (
                        ( m.id_tipo_pedido =  '.$data["id_tipo_pedido"].' )  AND ( m.id_tipo_factura =  '.$data["id_tipo_factura"].' )  AND 

                        ( m.id_apartado =  '.$id_apartado.' )  AND ( m.mov_salida = '.$mov_salida.' )
                      )'.$id_almacenid.'  
                   AND
                      (
                        ( CONCAT(m.cantidad_um," ",um.medida) LIKE  "%'.$cadena.'%" ) OR (CONCAT(m.ancho," cm") LIKE  "%'.$cadena.'%")  OR
                        ( m.codigo LIKE  "%'.$cadena.'%" ) OR (m.id_descripcion LIKE  "%'.$cadena.'%") OR (c.color LIKE  "%'.$cadena.'%")  OR
                         (CONCAT(m.id_lote,"-",m.consecutivo) LIKE  "%'.$cadena.'%") OR 
                         (m.precio LIKE  "%'.$cadena.'%")
                       )
            )';   



          $this->db->where($where);

          //$where_total = '( m.id_apartado =  '.$id_apartado.' )  AND ( m.mov_salida = '.$mov_salida.' )'.$id_almacenid;
          $where_total =$where;

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

                            $tipo_pedido   = $row->tipo_pedido;
                            $mi_dependencia = $row->dependencia;

                            $tipo_apartado = $row->tipo_apartado;
                            $color_apartado = $row->color_apartado;
                            $mi_fecha = date( 'd-m-Y', strtotime($row->fecha_apartado));
                            $mi_hora = date( 'h:ia', strtotime($row->fecha_apartado));
                            $cliente_pedido = $row->cliente_pedido;

                            $dato[]= array(
                                      0=>$row->codigo,
                                      1=>$row->id_descripcion,
                                      2=>
                                      $row->nombre_color.'<div style="margin-right: 15px;float:left;background-color:#'.$row->hexadecimal_color.';width:15px;height:15px;"></div>',
                                      3=>$row->cantidad_um.' '.$row->medida, //metros,
                                      4=>$row->ancho.' cm',
                                      5=>number_format($row->sum_precio, 2, '.', ','),
                                      6=>number_format($row->sum_iva, 2, '.', ','),

                                      7=>$row->id_lote.'-'.$row->consecutivo,         
                                      8=>$row->num_partida,
                                      9=>$row->almacen,
                                      
                                      10=>$row->id_factura,
                                      11=>$row->id_tipo_factura,
                                      12=>$row->id_tipo_pedido,
                                      13=>$row->t_factura,  
                                      14=>$row->id_factura_original,       
                                      15=>$row->metros,
                                      16=>$row->kilogramos,     
                                      17=>$row->codigo_contable,                           
                                      18=>$row->id_estatus, 

                                                                   
                                    );
                              $tipo_pedido=$row->tipo_pedido;
                              $tipo_factura=$row->tipo_factura; 

                      }

                      return json_encode ( array(
                        "draw"            => intval( $data['draw'] ),
                        "recordsTotal"    => $registros_filtrados, 
                        "recordsFiltered" => $registros_filtrados, 
                        "data"            =>  $dato, 
                        "datos"            =>  array("tipo_pedido"=>$tipo_pedido, "num_mov"=>$num_mov, "cliente_pedido"=>$cliente_pedido, "tipo_apartado"=>$tipo_apartado, "color_apartado"=>$color_apartado, "dependencia"=>$mi_dependencia, "cliente"=>$mi_cliente, "mi_fecha"=>$mi_fecha, "mi_hora"=>$mi_hora,
                          "tipo_pedido"=>$tipo_pedido,
                          "tipo_factura"=>$tipo_factura,  
                         ),
                        "totales"            =>  array(
                              "pieza"=>intval( self::totales_campos_completo($where_total)->pieza ),
                              "metro"=>floatval( self::totales_campos_completo($where_total)->metros ),
                              "kilogramo"=>floatval( self::totales_campos_completo($where_total)->kilogramos ), 
                             ),                  
                        
                      ));
                    
              }   
              else {
                  $output = array(
                  "draw" =>  intval( $data['draw'] ),
                  "recordsTotal" => 0, 
                  "recordsFiltered" =>0,
                  "aaData" => array(),
                  "totales"            =>  array(
                              "pieza"=>intval( self::totales_campos_completo($where_total)->pieza ),
                              "metro"=>floatval( self::totales_campos_completo($where_total)->metros ),
                              "kilogramo"=>floatval( self::totales_campos_completo($where_total)->kilogramos ), 
                             ),                  
                  

                  );
                  $array[]="";
                  return json_encode($output);
              }
              $result->free_result();           
      }  



 
 public function totales_campos_completo($where){

           $this->db->select("SUM((m.id_medida =1) * cantidad_um) as metros", FALSE);
              $this->db->select("SUM((m.id_medida =2) * cantidad_um) as kilogramos", FALSE);
              $this->db->select("COUNT(m.id_medida) as 'pieza'");
             
              $this->db->from($this->historico_registros_salidas.' as m');
              $this->db->join($this->almacenes.' As a' , 'a.id = m.id_almacen AND a.activo=1');
              $this->db->join($this->usuarios.' As u' , 'u.id = m.id_usuario_apartado','LEFT');
              $this->db->join($this->proveedores.' As pr', 'u.id_cliente = pr.id','LEFT');
              $this->db->join($this->proveedores.' As p' , 'p.id = m.id_cliente_apartado','LEFT');
              $this->db->join($this->unidades_medidas.' As um' , 'um.id = m.id_medida','LEFT');
              $this->db->join($this->colores.' As c', 'm.id_color = c.id','LEFT');
              $this->db->join($this->tipos_pedidos.' As tp' , 'tp.id = m.id_tipo_pedido','LEFT');
              $this->db->join($this->tipos_facturas.' As tf' , 'tf.id = m.id_tipo_factura','LEFT');
              $this->db->join($this->tipos_facturas.' As tff' , 'tff.id = m.id_factura','LEFT');

              $this->db->where($where);

             $result = $this->db->get();
          
              if ( $result->num_rows() > 0 )
                 return $result->row();
              else
                 return False;
              $result->free_result();              

       }  
  
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////


///////////////////////////////////////////


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




        public function marcando_prorroga_venta( $data ){
              
                $id_session = $this->session->userdata('id');
                $id_almacen= $data['id_almacen'];
                $this->db->set( 'id_prorroga', '(1 XOR id_prorroga)', FALSE );
                $this->db->where('id_usuario_apartado', $data['id_usuario_apartado'] );
                $this->db->where('id_cliente_apartado', $data['id_cliente_apartado'] );
                $this->db->where('consecutivo_venta', $data['consecutivo_venta'] );
                
                if ($id_almacen!=0) {
                    $this->db->where('id_almacen', $data['id_almacen'] );
                } else {
                  //no para todos
                }                 

                $this->db->update($this->registros );
                $this->db->select( 'id_prorroga' );
                $this->db->where('id_usuario_apartado', $data['id_usuario_apartado'] );
                $this->db->where('id_cliente_apartado', $data['id_cliente_apartado'] );
                $this->db->where('consecutivo_venta', $data['consecutivo_venta'] );
                
                if ($id_almacen!=0) {
                    $this->db->where('id_almacen', $data['id_almacen'] );
                } else {
                  //no para todos
                }                 

                $colo_prorroga = $this->db->get($this->registros );

               $prorroga = $colo_prorroga->row();
               return $prorroga->id_prorroga;

        
        }     

        public function marcando_prorroga_tienda( $data ){
                $id_almacen= $data['id_almacen'];
                $id_session = $this->session->userdata('id');
                $this->db->set( 'id_prorroga', '(1 XOR id_prorroga)', FALSE );

                if ($id_almacen!=0) {
                    $id_almacenid = ' AND ( id_almacen =  '.$id_almacen.' ) ';  
                } else {
                    $id_almacenid = '';
                } 

                $where = '(
                          (
                            (( id_apartado = 5 ) or ( id_apartado = 6 ) ) AND ( id_cliente_apartado = '.$data['id_cliente_apartado'].' )
                          )'.$id_almacenid.'

                      )';   

                $this->db->where($where);    


                $this->db->update($this->registros );

                //$this->db->distinct();
                $this->db->select( 'id_prorroga' );
                $where = '(
                          (
                            (( id_apartado = 5 ) or ( id_apartado = 6 ) ) AND ( id_cliente_apartado = '.$data['id_cliente_apartado'].' )
                            )'.$id_almacenid.'

                      )';   

                $this->db->where($where);   

                $colo_prorroga = $this->db->get($this->registros );

               $prorroga = $colo_prorroga->row();
               return $prorroga->id_prorroga;

        
        }                        
   


    public function actualizar_pedido( $data ){
           
            $id_session = $this->db->escape($this->session->userdata('id'));
            
            $fecha_hoy = date('Y-m-d H:i:s');  

            $this->db->set( 'id_usuario_apartado', $id_session, FALSE  );
            $this->db->set( 'id_apartado', 4);
            $this->db->set( 'peso_real', 0); //esto es para q aparezca en 0 el peso_real cdo haga pedido
            $this->db->set( 'fecha_apartado', $fecha_hoy);  
            $this->db->set( 'id_cliente_apartado', $data['id_movimiento']);
            $this->db->set( 'consecutivo_venta', $data['id_cliente']);


            $this->db->set( 'id_tipo_factura', $data['id_tipo_factura']);
            $this->db->set( 'id_tipo_pedido', $data['id_tipo_pedido']);

      


            $this->db->where('id',$data['id']);
            $this->db->update($this->registros);
     
            if ( $this->db->affected_rows() > 0 ) return TRUE;
            else return FALSE;
            
        }

    public function quitar_pedido( $data ){
           
            $id_session = $this->db->escape($this->session->userdata('id'));
            $fecha_hoy = date('Y-m-d H:i:s');  
                
            //$this->db->set( 'precio_anterior', 'precio', FALSE  );
            //$this->db->set( 'precio', 'precio_cambio', FALSE  );
            
            $this->db->set( 'id_usuario_apartado', ''  );
            $this->db->set( 'id_apartado', 0);
            $this->db->set( 'fecha_apartado', $fecha_hoy);  
            $this->db->set( 'id_cliente_apartado', 0);
            $this->db->set( 'id_tipo_factura', 0);
            $this->db->set( 'id_tipo_pedido', 0);
            $this->db->set( 'consecutivo_venta', 0);

            $this->db->where('id',$data['id']);
            $this->db->update($this->registros);
     
            if ( $this->db->affected_rows() > 0 ) return TRUE;
            else return FALSE;
            
        }

    public function valores_movimientos_temporal(){

          $id_session = $this->db->escape($this->session->userdata('id'));
          
          $this->db->distinct();          
          $this->db->select('m.id_tipo_pedido,m.id_tipo_factura,p.nombre');
          
          
          $this->db->from($this->registros_entradas.' as m');
          $this->db->join($this->proveedores.' As p' , 'p.id = m.consecutivo_venta','LEFT');
          /*
          $this->db->join($this->usuarios.' As u' , 'u.id = m.id_usuario_apartado','LEFT');
          $this->db->join($this->proveedores.' As pr', 'u.id_cliente = pr.id','LEFT');
          $this->db->join($this->proveedores.' As p' , 'p.id = m.id_cliente_apartado','LEFT');
          */


          $where = '(  ( m.id_usuario_apartado = '.$id_session.' ) AND   ( m.id_apartado = 4 ) )'; 
          $this->db->where($where);

           $result = $this->db->get();
        
            if ( $result->num_rows() > 0 )
               return $result->row();
            else
               return False;
            $result->free_result();
      }   

   

       public function consecutivo_operacion( $id,$id_tipo_pedido,$id_tipo_factura ){
              $this->db->select("o.consecutivo,o.conse_factura,o.conse_remision,o.conse_surtido");         
              $this->db->from($this->operaciones.' As o');
              $this->db->where('o.id',$id);
              $result = $this->db->get( );
                  if ($result->num_rows() > 0) {


                  $consecutivo_actual = (( ($id_tipo_pedido == 1) && ($id_tipo_factura==1) ) ? $result->row()->conse_factura : $result->row()->conse_remision );
                  $consecutivo_actual = ( ($id_tipo_pedido==2) ? $result->row()->conse_surtido : $consecutivo_actual);
                       
                        return $consecutivo_actual+1;
                  }                    
                  else 
                      return FALSE;
                  $result->free_result();
       }  



        //cambiar estatus de unidad
        public function pedido_definitivamente( $data ){
              
              $id_session = $this->session->userdata('id');
              $fecha_hoy = date('Y-m-d H:i:s');  

              $consecutivo = self::consecutivo_operacion(4,$data['id_tipo_pedido'],$data['id_tipo_factura']); //cambio

              $this->db->set( 'fecha_vencimiento', $fecha_hoy  );
              $this->db->set( 'fecha_apartado', $fecha_hoy  );  
              $this->db->set( 'id_cliente_apartado', $consecutivo, false ); // cambio $data['num_mov'] numero de mov.
              $this->db->set( 'id_apartado', 5 , FALSE );
                
              $this->db->where('id_usuario_apartado', $id_session );
              $this->db->where('id_apartado', 4 );

              //$this->db->where('peso_real', 0);

              $this->db->update($this->registros );

              //actualizar (consecutivo) en tabla "operacion"   == "generar_pedido"
              //$this->db->set( 'consecutivo', 'consecutivo+1', FALSE  );

              if ($data['id_tipo_pedido']==2) {
                   $this->db->set( 'conse_surtido', 'conse_surtido+1', FALSE  );  
              }  else if ($data['id_tipo_factura']==1) {
                  $this->db->set( 'conse_factura', 'conse_factura+1', FALSE  );  
              } else {
                  $this->db->set( 'conse_remision', 'conse_remision+1', FALSE  );  
              }


              $this->db->set( 'id_usuario', $id_session );
              $this->db->where('id',4);
              $this->db->update($this->operaciones);


                if ($this->db->affected_rows() > 0) {
                  return TRUE;
                }  else
                   return FALSE;
       
        }    
        

        public function cancelar_traspaso_apartado_detalle( $data ){
                $id_almacen= $data['id_almacen'];
                $porciento_aplicar = 16;                 
                
                $this->db->set( 'iva', '((id_factura_original = 1)*'.$porciento_aplicar.')', false);
                $this->db->set( 'incluir', 0);
                $this->db->set( 'id_factura', 'id_factura_original', false);
                $this->db->set( 'id_factura_original', 0, false);

               // $cond_traspaso = ' AND ( ( id_factura_original <>  0 ) AND ( incluir =  1 ) )';  
                

                $this->db->where('id_factura_original!=',0,FALSE);
                $this->db->where('incluir',1);
                if ($id_almacen!=0) {
                    $this->db->where('id_almacen',$data['id_almacen']);
                } else {
                    //no porque se eliminaran "todos"
                }                 
                
                $this->db->where('id_tipo_pedido',$data['id_tipo_pedido']);
                $this->db->where('id_tipo_factura',$data['id_tipo_factura']);

                $this->db->where('id_usuario_apartado',$data['id_usuario']);
                $this->db->where('id_cliente_apartado',$data['id_cliente']);

                $this->db->where('id_apartado', 2 );
                $this->db->where('consecutivo_venta',$data['consecutivo_venta']);


                $this->db->update($this->registros );

                if ($this->db->affected_rows() > 0) {
                  return TRUE;
                }  else
                   return FALSE;                

          }            

        //cambiar estatus de unidad
        public function cancelar_apartados_detalle( $data ){
              
                $id_session = $this->session->userdata('id');
                $fecha_hoy = date('Y-m-d H:i:s'); 
                $id_almacen= $data['id_almacen']; 

              //  $this->db->set( 'precio_anterior', 'precio', FALSE  );
               // $this->db->set( 'precio', 'precio_cambio', FALSE  );

                $this->db->set( 'fecha_vencimiento', '' ); 
                $this->db->set( 'id_prorroga', 0);
                
                $this->db->set( 'fecha_apartado', '' );  
                $this->db->set( 'id_cliente_apartado', 0 );
                $this->db->set( 'id_apartado', 0);
                $this->db->set( 'id_usuario_apartado', '');
                $this->db->set( 'consecutivo_venta', 0);
                $this->db->set( 'id_tipo_pedido', 0, false);
                $this->db->set( 'id_tipo_factura', 0, false);
                $this->db->set( 'consecutivo_venta', 0);

               if ($id_almacen!=0) {
                    $this->db->where('id_almacen',$data['id_almacen']);
                } else {
                    //no porque se eliminaran "todos"
                }                 

                $this->db->where('id_tipo_pedido',$data['id_tipo_pedido']);
                $this->db->where('id_tipo_factura',$data['id_tipo_factura']);
                $this->db->where('id_usuario_apartado',$data['id_usuario']);
                $this->db->where('id_cliente_apartado',$data['id_cliente']);
                $this->db->where('id_apartado', 2 );

                $this->db->where('consecutivo_venta',$data['consecutivo_venta']);
                

                $this->db->update($this->registros );

                if ($this->db->affected_rows() > 0) {
                  return TRUE;
                }  else
                   return FALSE;
       
        }   







   public function traspaso_apartado( $data ){
                
                $id_almacen= $data['id_almacen'];
                
                if ($data['id_tipo_factura']==1){
                    $porciento_aplicar =16;  
                } else {
                     $porciento_aplicar = 0;  
                }
                
                $this->db->set( 'id_factura_original', 'id_factura', false);
                $this->db->set( 'id_factura', 'id_tipo_factura', false);
                //$this->db->set( 'precio', '( (('.$porciento_aplicar.'*precio)/100) + precio)', FALSE );
                //$this->db->set( 'iva', '('.$porciento_aplicar.')', false);
                
                
                if ($data['id_tipo_factura']==1){
                    $this->db->set( 'iva', '((id_factura = 1)*'.$porciento_aplicar.')', false);
                }
                


                $this->db->set( 'incluir', 1);


                //$this->db->set( 'id_prorroga', '(1 XOR id_prorroga)', FALSE );


                
                if ($id_almacen!=0) {
                    $id_almacenid = ' AND ( id_almacen =  '.$id_almacen.' ) ';  
                } else {
                    $id_almacenid = '';
                } 

                $cond_traspaso = ' AND ( ( id_factura <>  id_tipo_factura ) AND ( incluir =  0 ) )';  

                $where = '(
                          (
                            (( id_apartado = 2 ) or ( id_apartado = 3 ) ) AND ( id_cliente_apartado = "'.$data['id_cliente'].'" ) AND ( id_usuario_apartado = "'.$data['id_usuario'].'" ) AND ( consecutivo_venta = '.$data['consecutivo_venta'].' )
                         )'.$id_almacenid.$cond_traspaso.' 
                )';    

                $this->db->where($where);
                $this->db->update($this->registros );
                if ($this->db->affected_rows() > 0) {
                  return TRUE;
                }  else
                   return FALSE;

        }   

        /*
SELECT `precio`, `iva`, `codigo`, `consecutivo_venta`, `id_factura`, `id_pedido`, `id_tipo_pedido`, `id_tipo_factura`, `id_factura_original`, `incluir` FROM `inven_registros_entradas` WHERE id_tipo_pedido<>0

        */

            //cambiar estatus de unidad
        public function incluir_apartado( $data ){
                $id_almacen= $data['id_almacen'];

                $this->db->set( 'id_apartado', $data['id_apartado']);

                if ($id_almacen!=0) {
                    $id_almacenid = ' AND ( id_almacen =  '.$id_almacen.' ) ';  
                } else {
                    $id_almacenid = '';
                } 

                $where = '(
                          (
                            (( id_apartado = 2 ) or ( id_apartado = 3 ) ) AND ( id_cliente_apartado = "'.$data['id_cliente'].'" ) AND ( id_usuario_apartado = "'.$data['id_usuario'].'" ) AND ( consecutivo_venta = '.$data['consecutivo_venta'].' )
                         )'.$id_almacenid.' 
                )';   

                $this->db->where($where);
                $this->db->update($this->registros );
                if ($this->db->affected_rows() > 0) {
                  return TRUE;
                }  else
                   return FALSE;

        
        }   






                //cambiar estatus de unidad
        public function traspaso_pedido( $data ){
                
                $id_almacen= $data['id_almacen'];
                
                if ($data['id_tipo_factura']==1){
                    $porciento_aplicar =16;  
                } else {
                     $porciento_aplicar = 0;  
                }
                
                $this->db->set( 'id_factura_original', 'id_factura', false);
                $this->db->set( 'id_factura', 'id_tipo_factura', false);
                //$this->db->set( 'precio', '( (('.$porciento_aplicar.'*precio)/100) + precio)', FALSE );
                //$this->db->set( 'iva', '('.$porciento_aplicar.')', false);
                
                
                if ($data['id_tipo_factura']==1){
                    $this->db->set( 'iva', '((id_factura = 1)*'.$porciento_aplicar.')', false);
                }
                


                $this->db->set( 'incluir', 1);


                //$this->db->set( 'id_prorroga', '(1 XOR id_prorroga)', FALSE );


                
                if ($id_almacen!=0) {
                    $id_almacenid = ' AND ( id_almacen =  '.$id_almacen.' ) ';  
                } else {
                    $id_almacenid = '';
                } 

                $cond_traspaso = ' AND ( ( id_factura <>  id_tipo_factura ) AND ( incluir =  0 ) )';  

                $where = '(
                          ( id_tipo_pedido =  '.$data["id_tipo_pedido"].' )  AND ( id_tipo_factura =  '.$data["id_tipo_factura"].' )  AND 
                          (
                            (( id_apartado = 5 ) or ( id_apartado = 6 ) ) AND ( id_cliente_apartado = "'.$data['num_mov'].'" )
                          )'.$id_almacenid.$cond_traspaso.' 

                      )';   

                $this->db->where($where);
                $this->db->update($this->registros );
                if ($this->db->affected_rows() > 0) {
                  return TRUE;
                }  else
                   return FALSE;

        }   


                    //cambiar estatus de unidad
        public function incluir_pedido( $data ){
                
                $id_almacen= $data['id_almacen'];
                $this->db->set( 'id_apartado', $data['id_apartado']);
                
                if ($id_almacen!=0) {
                    $id_almacenid = ' AND ( id_almacen =  '.$id_almacen.' ) ';  
                } else {
                    $id_almacenid = '';
                } 

                $where = '(
                          ( id_tipo_pedido =  '.$data["id_tipo_pedido"].' )  AND ( id_tipo_factura =  '.$data["id_tipo_factura"].' )  AND 
                          (
                            (( id_apartado = 5 ) or ( id_apartado = 6 ) ) AND ( id_cliente_apartado = "'.$data['num_mov'].'" )
                          )'.$id_almacenid.' 

                      )';   

                $this->db->where($where);
                $this->db->update($this->registros );
                if ($this->db->affected_rows() > 0) {
                  return TRUE;
                }  else
                   return FALSE;

        }   


        public function cancelar_traspaso_pedido_detalle( $data ){

                $id_almacen= $data['id_almacen'];
               
                $porciento_aplicar = 16;                 
                
                $this->db->set( 'iva', '((id_factura_original = 1)*'.$porciento_aplicar.')', false);
                $this->db->set( 'incluir', 0);
                $this->db->set( 'id_factura', 'id_factura_original', false);
                $this->db->set( 'id_factura_original', 0, false);
                $this->db->set( 'consecutivo_venta', 0);

                $cond_traspaso = ' AND ( ( id_factura_original <>  0 ) AND ( incluir =  1 ) )';  

                if ($id_almacen!=0) {
                    $id_almacenid = ' AND ( id_almacen =  '.$id_almacen.' ) ';  
                } else {
                    $id_almacenid = '';
                } 

                $where = '(
                          (
                           ( id_tipo_pedido =  '.$data["id_tipo_pedido"].' )  AND ( id_tipo_factura =  '.$data["id_tipo_factura"].' )  AND 

                            (( id_apartado = 5 ) or ( id_apartado = 6 ) ) AND ( id_cliente_apartado = "'.$data['num_mov'].'" )
                          )'.$id_almacenid.$cond_traspaso.' 

                      )';   

                $this->db->where($where);                

                $this->db->update($this->registros );

                if ($this->db->affected_rows() > 0) {
                  return TRUE;
                }  else
                   return FALSE;                

          }      

        //cambiar estatus de pedidos
        public function cancelar_pedido_detalle( $data ){
              
                $id_session = $this->session->userdata('id');
                $fecha_hoy = date('Y-m-d H:i:s');  

                $id_almacen= $data['id_almacen'];

                $this->db->set( 'fecha_vencimiento', '' ); 
                $this->db->set( 'id_prorroga', 0);
                $this->db->set( 'fecha_apartado', '' );  
                $this->db->set( 'id_cliente_apartado', 0 );
                $this->db->set( 'id_apartado', 0);
                $this->db->set( 'id_usuario_apartado', '');
                $this->db->set( 'id_tipo_pedido', 0, false);
                $this->db->set( 'id_tipo_factura', 0, false);
                $this->db->set( 'consecutivo_venta', 0);



                if ($id_almacen!=0) {
                    $id_almacenid = ' AND ( id_almacen =  '.$id_almacen.' ) ';  
                } else {
                    $id_almacenid = '';
                } 

                $where = '(
                          ( id_tipo_pedido =  '.$data["id_tipo_pedido"].' )  AND ( id_tipo_factura =  '.$data["id_tipo_factura"].' )  AND 
                          
                          (
                            (( id_apartado = 5 ) or ( id_apartado = 6 ) ) AND ( id_cliente_apartado = "'.$data['num_mov'].'" )
                          )'.$id_almacenid.' 

                      )';   

                $this->db->where($where);                

                $this->db->update($this->registros );

                if ($this->db->affected_rows() > 0) {
                  return TRUE;
                }  else
                   return FALSE;
       
        }   








        public function total_apartados_detalles($id_usuario,$id_cliente){

              $id_session = $this->session->userdata('id');
              $this->db->from($this->registros.' as m');
              $this->db->where('m.id_apartado',2);
              $this->db->where('m.id_usuario_apartado',$id_usuario);
              $this->db->where('m.id_cliente_apartado',$id_cliente);
              
              $cant = $this->db->count_all_results();          
     
              if ( $cant > 0 )
                 return $cant;
              else
                 return 0;         

       }  





       //para cancelar pedidos cada 24hrs
    public function cancelar_traspaso_pedido_horario(){  
                $porciento_aplicar = 16;                 
                
                $this->db->set( 'iva', '((id_factura_original = 1)*'.$porciento_aplicar.')', false);
                $this->db->set( 'incluir', 0);
                $this->db->set( 'id_factura', 'id_factura_original', false);
                $this->db->set( 'id_factura_original', 0, false);
                $this->db->set( 'consecutivo_venta', 0);
                $where = '(
                          ((id_apartado =5) OR (id_apartado =6)) AND ( id_cliente_apartado <>0)
                                and (TIMESTAMPDIFF(HOUR,fecha_apartado,  NOW()) >=24)
                                AND ( id_factura_original <>  0 ) AND ( incluir =  1 )
                      )';   
                $this->db->where($where);                
                $this->db->update($this->registros );

                //cancelarlos
                $this->db->set( 'fecha_vencimiento', '' ); 
                $this->db->set( 'id_prorroga', 0);
                $this->db->set( 'fecha_apartado', '' );  
                $this->db->set( 'id_cliente_apartado', 0 );
                $this->db->set( 'id_apartado', 0);
                $this->db->set( 'id_usuario_apartado', '');
                $this->db->set( 'id_tipo_pedido', 0, false);
                $this->db->set( 'id_tipo_factura', 0, false);
                $this->db->set( 'consecutivo_venta', 0);


                $where = '(
                          ((id_apartado =5) OR (id_apartado =6)) AND ( id_cliente_apartado <>0)
                                and (TIMESTAMPDIFF(HOUR,fecha_apartado,  NOW()) >=24)
                      )';   
                $this->db->where($where);                
                $this->db->update($this->registros );

                if ($this->db->affected_rows() > 0) {
                  return TRUE;
                }  else
                   return FALSE;                

          }   







//////////////////////////////Procesando apartados pendientes



















        public function total_pedidos_especifico($num_mov){

              $id_session = $this->session->userdata('id');
              $this->db->from($this->registros.' as m');
              $this->db->where('m.id_usuario_apartado',$num_mov);
              $this->db->group_by("m.id_cliente_apartado");
              $result = $this->db->get();
              $cant = $result->num_rows();
     
              if ( $cant > 0 )
                 return $cant;
              else
                 return 0;         

       }      









  } 






?>

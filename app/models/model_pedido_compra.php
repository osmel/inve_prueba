<?php if(! defined('BASEPATH')) exit('No tienes permiso para acceder a este archivo');

  class model_pedido_compra extends CI_Model {
    
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

      $this->temporal_pedido_compra        = $this->db->dbprefix('temporal_pedido_compra');
      $this->historico_pedido_compra        = $this->db->dbprefix('historico_pedido_compra');
      $this->historico_cancela_pedido_compra      = $this->db->dbprefix('historico_cancela_pedido_compra');
      $this->historico_historial_compra      = $this->db->dbprefix('historico_historial_compra');

    }




/////////////////////////////////////////////////////////////////////////////////////////////////////////
 
 public function valores_movimientos_temporal(){

          $id_session = $this->session->userdata('id');
          
          $this->db->distinct();          
          //$this->db->select('m.id, m.id_empresa, m.factura,m.id_almacen,m.id_factura,m.id_tipo_pago,m.iva');
          $this->db->select('pc.comentario', false);
          $this->db->select('pc.factura');
          $this->db->select('pc.movimiento');
          $this->db->select('pc.id_almacen');
          $this->db->select('pc.id_proveedor');
          $this->db->select('pc.id_medida');
          
          $this->db->from($this->productos.' as p');
          $this->db->join($this->temporal_pedido_compra.' As pc', 'pc.id_producto = p.id','LEFT');
          
          $where = '(                      
                            ( LOCATE("'.$id_session.'", pc.id_usuario) >0)  
                    ) ';  

        

           $this->db->where($where);          



           $result = $this->db->get();
        
            if ( $result->num_rows() > 0 )
               return $result->row();
            else
               return False;
            $result->free_result();
        }    



 
 //regilla1 "nuevo_pedido_compra"
 public function buscador_entrada_compra($data){

          $cadena = addslashes($data['search']['value']);
          $inicio = $data['start'];
          $largo = $data['length'];

          $columa_order = $data['order'][0]['column'];
                 $order = $data['order'][0]['dir'];

          switch ($columa_order) {
                   case '0':
                        $columna = 'p.descripcion';
                     break;
                   case '1':
                        $columna = 'p.imagen';
                     break;
                   case '2':
                        $columna = 'c.color';
                     break;
                  case '3':
                        $columna = 'm.ancho, p.ancho';
                     break;                     
                   case '4':
                        $columna = 'co.composicion';
                     break;
                   case '5':
                        $columna = 'ca.calidad';
                     break;

                   case '6':
                        $columna = 'm.precio,p.precio';
                     break;
                   case '6':
                        $columna = 'suma';
                     break;                     
                   
                   default:
                        $columna = 'suma';
                         $order = 'ASC';
                     break;
                 }                 

          $id_almacen= $data['id_almacen'];

          $descripcion= addslashes($data['id_descripcion']);
          $id_color= $data['id_color'];
          $id_composicion= $data['id_composicion'];
          $id_calidad= $data['id_calidad'];
          



          $id_session = $this->session->userdata('id');
          
          //p.uid,p.fecha_mac,, p.activo, a.almacen,
          //$this->db->select("((m.precio*m.iva))/100 as sum_iva", FALSE);
          //$this->db->select("(m.precio)+((m.precio*m.iva))/100 as precio_total", FALSE);          

          $this->db->select("SQL_CALC_FOUND_ROWS(p.id)"); //
          $this->db->select('p.id,  p.referencia,p.codigo_contable,m.id_estatus');
          $this->db->select('p.descripcion, p.imagen, c.hexadecimal_color,c.color nombre_color');
          $this->db->select('co.composicion, ca.calidad');
          $this->db->select("m.ancho, p.ancho ancho_producto");
          $this->db->select("m.precio, p.precio precio_producto");
          $this->db->select("( CASE WHEN ".$data['id_medida']." = 1 THEN p.minimo ELSE p.minimo_kg END ) AS minimo", FALSE);
          $this->db->select("SUM((m.id_medida =".$data['id_medida'].") * m.cantidad_um) as suma", FALSE);


           if ($id_almacen!=0) {
              $id_almacenid = ' and ( m.id_almacen =  '.$id_almacen.' ) ';  
            } else {
              $id_almacenid = '';
            }   


          $this->db->from($this->productos.' as p');
          $this->db->join($this->colores.' As c', 'p.id_color = c.id'); //,'LEFT'
          $this->db->join($this->composiciones.' As co', 'p.id_composicion = co.id'); //,'LEFT'
          $this->db->join($this->calidades.' As ca', 'p.id_calidad = ca.id'); //,'LEFT'
          $this->db->join($this->registros.' As m', 'm.referencia= p.referencia'.$id_almacenid,'LEFT');
          $this->db->join($this->almacenes.' As a', 'a.id = m.id_almacen','LEFT');


          

           $where = '(                      
                                (
                                   (p.descripcion LIKE  "%'.$cadena.'%") OR 
                                   (p.codigo_contable LIKE  "%'.$cadena.'%") OR
                                   (CONCAT("Optimo:",p.minimo) LIKE  "%'.$cadena.'%")  OR
                                   
                                  (c.color LIKE  "%'.$cadena.'%") OR
                                  (co.composicion LIKE  "%'.$cadena.'%")  OR
                                  ( ca.calidad LIKE  "%'.$cadena.'%" )  OR 
                                  ( p.precio LIKE  "%'.$cadena.'%" ) OR  ( m.precio LIKE  "%'.$cadena.'%" ) OR
                                  ( p.ancho LIKE  "%'.$cadena.'%" ) OR  ( m.ancho LIKE  "%'.$cadena.'%" ) 
                                 )   
                    ) ';  



                    $where_total ='';         


                             
            //if  (($id_calidad!="0") AND ($id_calidad!="") AND ($id_calidad!= null)) 

            if ($data['id_medida']==1) {  //metro
               $where.= (($where!="") ? " and " : "") . "  (!(LOCATE('".$id_session."', id_usuario_compra) >0))";
               $where_total.= (($where_total!="") ? " and " : "") . " (!(LOCATE('".$id_session."', id_usuario_compra) >0))";
            }   else { //kg
               $where.= (($where!="") ? " and " : "") . "  (!(LOCATE('".$id_session."', id_usuario_compra_kg) >0))";
               $where_total.= (($where_total!="") ? " and " : "") . " (!(LOCATE('".$id_session."', id_usuario_compra_kg) >0))";

            }  



             

            if  (($id_calidad!="0") AND ($id_calidad!="") AND ($id_calidad!= null)) {
               $where.= (($where!="") ? " and " : "") . "( p.id_calidad  =  ".$id_calidad." )";
               $where_total.= (($where_total!="") ? " and " : "") . "( p.id_calidad  =  ".$id_calidad." )";
            }     
            if (($id_composicion!="0") AND ($id_composicion!="") AND ($id_composicion!= null)) {
                $where.= (($where!="") ? " and " : "") . "( p.id_composicion  =  ".$id_composicion." ) ";
                $where_total.= (($where_total!="") ? " and " : "") . "( p.id_composicion  =  ".$id_composicion." ) ";
            } 
            if  (($id_color!="0") AND ($id_color!="") AND ($id_color!= null)) {
               $where.= (($where!="") ?  " and " : "") . "( p.id_color  =  ".$id_color." )";
               $where_total.= (($where_total!="") ?  " and " : "") . "( p.id_color  =  ".$id_color." )";
            }
            
            //if ( ($data['val_prod_id'] !="")  && ($data['val_prod_id'] !="0") ) {
            if (($descripcion!="0") AND ($descripcion!="") AND ($descripcion!= null))  {                
                $where.= (($where!="") ? " and " : "") . "( p.descripcion  =  '".$descripcion."' )";
                $where_total.= (($where_total!="") ? " and " : "") . "( p.descripcion  =  '".$descripcion."' )";
            }



                      
          $where.= (($where!="") ? " and " : "") . "( p.activo = 0 )";                      
          $data['where_total']=$where;
          $data['id_almacenid']=$id_almacenid;


          $this->db->where($where);

          $this->db->group_by("p.referencia"); //,p.id_usuario_compra,p.id_usuario_compra

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

                     
                          //variables para cachear las imagenes                                                  
                          $fechaSegundos = time(); 
                          $strNoCache = "?nocache=$fechaSegundos"; 

                        $nombre_fichero ='';
                        $nombre_fichero ='uploads/productos/thumbnail/300X300/'.substr($row->imagen,0,strrpos($row->imagen,".")).'_thumb'.substr($row->imagen,strrpos($row->imagen,"."));
                        if (file_exists($nombre_fichero)) {
                            
                            $imagen ='<img src="'.base_url().$nombre_fichero.$strNoCache.'" border="0" width="100%" height="auto">';

                        } else {
                            $imagen ='<img src="'.base_url().'img/sinimagen.png" border="0" width="75" height="75">';
                        }



                            $dato[]= array(
                                      
                                      0=>$row->descripcion,
                                      1=>$imagen,  
                                      2=>$row->nombre_color.
                                        '<div style="background-color:#'.$row->hexadecimal_color.';display:block;width:15px;height:15px;margin:0 auto;"></div>',
                                      3=>number_format((($row->ancho>0) ? $row->ancho : $row->ancho_producto), 2, '.', ','),   
                                      4=>$row->composicion, 
                                      5=>$row->calidad, 
                                      6=>
                                      ( ( ($this->session->userdata('id_perfil')==1) || ( (in_array(80, $data['coleccion_id_operaciones'])) || (in_array(81, $data['coleccion_id_operaciones'])) )  ) ? number_format((($row->precio>0) ? $row->precio : $row->precio_producto), 2, '.', ',') : '-'),
                                        
                                      7=>'Optimo:'.$row->minimo.'<br/>  Reales:'. $row->suma,
                                      8=>$row->codigo_contable,
                                      9=>$row->id, 
                                      10=>$row->referencia,
                                      11=>null, //$row->id_estatus,
                                    );
                      }




                      return json_encode ( array(
                        "draw"            => intval( $data['draw'] ),
                        "recordsTotal"    => $registros_filtrados,  //intval( self::total_cat_productos($data) ), 
                        "recordsFiltered" =>   $registros_filtrados, 
                        "data"            =>  $dato,
                        "totales_importe"            =>  array(
                                "importe"=>(floatval( self::totales_importes($data)->precio_producto)>0) ? floatval( self::totales_importes($data)->precio_producto) : floatval( self::totales_importes($data)->precio_producto )
                                
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


public function totales_importes($data){

          $this->db->select("sum(m.precio) as precio", FALSE);
          $this->db->select("sum(p.precio) as precio_producto", FALSE);
             

          $this->db->from($this->productos.' as p');
          $this->db->join($this->colores.' As c', 'p.id_color = c.id'); //,'LEFT'
          $this->db->join($this->composiciones.' As co', 'p.id_composicion = co.id'); //,'LEFT'
          $this->db->join($this->calidades.' As ca', 'p.id_calidad = ca.id'); //,'LEFT'
          $this->db->join($this->registros.' As m', 'm.referencia= p.referencia'.$data['id_almacenid'],'LEFT');
          $this->db->join($this->almacenes.' As a', 'a.id = m.id_almacen','LEFT');



          $this->db->where($data['where_total']);
          //$this->db->group_by("p.referencia");

          $result = $this->db->get();
      
          if ( $result->num_rows() > 0 )
             return $result->row();
          else
             return False;
          $result->free_result();              

}  

 
//regilla2 "nuevo_pedido_compra"
 public function buscador_salida_compra($data){

          $cadena = addslashes($data['search']['value']);
          $inicio = $data['start'];
          $largo = $data['length'];

          $columa_order = $data['order'][0]['column'];
                 $order = $data['order'][0]['dir'];

          switch ($columa_order) {
                   case '0':
                        $columna = 'p.descripcion';
                     break;
                   case '1':
                        $columna = 'p.imagen';
                     break;
                   case '2':
                        $columna = 'c.color';
                     break;
                  case '3':
                        $columna = 'm.ancho, p.ancho';
                     break;                     
                   case '4':
                        $columna = 'co.composicion';
                     break;
                   case '5':
                        $columna = 'ca.calidad';
                     break;

                   case '6':
                        $columna = 'm.precio,p.precio';
                     break;
                   case '6':
                        $columna = 'suma';
                     break;                     
                   
                   default:
                        $columna = 'suma';
                         $order = 'ASC';
                     break;
                 }                 




          $id_almacen= $data['id_almacen'];

          $descripcion= addslashes($data['id_descripcion']);
          $id_color= $data['id_color'];
          $id_composicion= $data['id_composicion'];
          $id_calidad= $data['id_calidad'];

          $id_session = $this->session->userdata('id');
          

          $this->db->select("SQL_CALC_FOUND_ROWS(p.id)"); //
          
          $this->db->select('p.id, p.uid, p.referencia,p.codigo_contable');
          $this->db->select('p.descripcion, p.imagen,p.fecha_mac, c.hexadecimal_color,c.color nombre_color');
          $this->db->select('co.composicion, ca.calidad, p.activo');
          
          $this->db->select("m.ancho, p.ancho ancho_producto, m.id_estatus");
          $this->db->select("m.precio, p.precio precio_producto");

          $this->db->select("((m.precio*m.iva))/100 as sum_iva");
          $this->db->select("(m.precio)+((m.precio*m.iva))/100 as precio_total");          
          $this->db->select("a.almacen,pc.id_medida");

          //$this->db->select("COUNT(m.referencia) as 'suma'");
          //$this->db->select("( CASE WHEN ".$data['id_medida']." = 1 THEN p.minimo ELSE p.minimo_kg END ) AS minimo", FALSE);
           //$this->db->select("SUM((m.id_medida =".$data['id_medida'].") * m.cantidad_um) as suma", FALSE);
         
           $this->db->select("( CASE WHEN pc.id_medida = 1 THEN p.minimo ELSE p.minimo_kg END ) AS minimo", FALSE);
           $this->db->select("SUM((m.id_medida = pc.id_medida) * m.cantidad_um) as suma", FALSE);
         
         //$this->db->select('substring(id_usuario_compra, length("'.$id_session.'")+2,locate(";",id_usuario_compra,LOCATE("'.$id_session.':", id_usuario_compra)+length("'.$id_session.'"))-length("'.$id_session.'")-2) as pedido_compra', FALSE);        

          $this->db->select("pc.cantidad_royo as pedido_compra, um.medida medida");

           if ($id_almacen!=0) {
              $id_almacenid = ' and ( m.id_almacen =  '.$id_almacen.' ) ';  
            } else {
              $id_almacenid = '';
            }   


          $this->db->from($this->productos.' as p');
          $this->db->join($this->colores.' As c', 'p.id_color = c.id','LEFT');
          $this->db->join($this->composiciones.' As co', 'p.id_composicion = co.id','LEFT');
          $this->db->join($this->calidades.' As ca', 'p.id_calidad = ca.id','LEFT');
          $this->db->join($this->registros.' As m', 'm.referencia= p.referencia'.$id_almacenid,'LEFT');
          $this->db->join($this->almacenes.' As a', 'a.id = m.id_almacen','LEFT');
          $this->db->join($this->temporal_pedido_compra.' As pc', 'pc.id_producto = p.id','LEFT');
          $this->db->join($this->unidades_medidas.' As um', 'pc.id_medida = um.id'); //,'LEFT'

           $where = '(                      
                                (
                                   (p.descripcion LIKE  "%'.$cadena.'%") OR 
                                   (p.codigo_contable LIKE  "%'.$cadena.'%") OR
                                   (CONCAT("Optimo:",p.minimo) LIKE  "%'.$cadena.'%")  OR
                                   
                                  (c.color LIKE  "%'.$cadena.'%") OR
                                  (co.composicion LIKE  "%'.$cadena.'%")  OR
                                  ( ca.calidad LIKE  "%'.$cadena.'%" )  OR 
                                  ( p.precio LIKE  "%'.$cadena.'%" ) OR  ( m.precio LIKE  "%'.$cadena.'%" ) OR
                                  ( p.ancho LIKE  "%'.$cadena.'%" ) OR  ( m.ancho LIKE  "%'.$cadena.'%" ) 
                                 )  AND  ( ( LOCATE("'.$id_session.'", id_usuario_compra) >0)  || ( LOCATE("'.$id_session.'", id_usuario_compra_kg) >0)    )
                    ) ';  




                     
                     

          $data['where_total']=$where;
          $data['id_almacenid']=$id_almacenid;


          $this->db->where($where);

          $this->db->group_by("p.referencia,pc.id_medida"); //,p.id_usuario_compra,p.id_usuario_compra

          $this->db->order_by($columna, $order); 
    


          //paginacion
          //$this->db->limit($largo,$inicio); 


         $result = $this->db->get();


              if ( $result->num_rows() > 0 ) {

                    $cantidad_consulta = $this->db->query("SELECT FOUND_ROWS() as cantidad");
                    $found_rows = $cantidad_consulta->row(); 
                    $registros_filtrados =  ( (int) $found_rows->cantidad);

                  $retorno= " ";  
                  foreach ($result->result() as $row) {

                     
                          //variables para cachear las imagenes                                                  
                          $fechaSegundos = time(); 
                          $strNoCache = "?nocache=$fechaSegundos"; 

                        $nombre_fichero ='';
                        $nombre_fichero ='uploads/productos/thumbnail/300X300/'.substr($row->imagen,0,strrpos($row->imagen,".")).'_thumb'.substr($row->imagen,strrpos($row->imagen,"."));
                        if (file_exists($nombre_fichero)) {
                            
                            $imagen ='<img src="'.base_url().$nombre_fichero.$strNoCache.'" border="0" width="100%" height="auto">';

                        } else {
                            $imagen ='<img src="'.base_url().'img/sinimagen.png" border="0" width="75" height="75">';
                        }



                            $dato[]= array(
                                      
                                      0=>$row->descripcion,
                                      1=>$imagen,  
                                      2=>$row->nombre_color.
                                        '<div style="background-color:#'.$row->hexadecimal_color.';display:block;width:15px;height:15px;margin:0 auto;"></div>',
                                      3=>number_format((($row->ancho>0) ? $row->ancho : $row->ancho_producto), 2, '.', ','),   
                                      4=>$row->composicion, 
                                      5=>$row->calidad, 
                                      6=>( ( ($this->session->userdata('id_perfil')==1) || ( (in_array(80, $data['coleccion_id_operaciones'])) || (in_array(81, $data['coleccion_id_operaciones'])) )  ) ? number_format((($row->precio>0) ? $row->precio : $row->precio_producto), 2, '.', ',') : '-'),  
                                      7=>'Optimo:'.$row->minimo.'<br/>  Reales:'. $row->suma,
                                      8=>$row->codigo_contable,
                                      9=>$row->id, 
                                      10=>$row->referencia,
                                      11=>$row->pedido_compra,
                                      12=>$row->id_medida,
                                      13=>$row->medida,
                                      
                                      
                                    );
                      }




                      return json_encode ( array(
                        "draw"            => intval( $data['draw'] ),
                        "recordsTotal"    => $registros_filtrados,  //intval( self::total_cat_productos($data) ), 
                        "recordsFiltered" =>   $registros_filtrados, 
                        "data"            =>  $dato,
                          "importe"=>(floatval( self::totales_importes_salida($data)->precio_producto)>0) ? floatval( self::totales_importes_salida($data)->precio_producto) : floatval( self::totales_importes_salida($data)->precio_producto )

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

   


public function totales_importes_salida($data){

          $this->db->select("sum(m.precio) as precio", FALSE);
          $this->db->select("sum(p.precio) as precio_producto", FALSE);
             
          $this->db->from($this->productos.' as p');
          $this->db->join($this->colores.' As c', 'p.id_color = c.id','LEFT');
          $this->db->join($this->composiciones.' As co', 'p.id_composicion = co.id','LEFT');
          $this->db->join($this->calidades.' As ca', 'p.id_calidad = ca.id','LEFT');
          $this->db->join($this->registros.' As m', 'm.referencia= p.referencia'.$data['id_almacenid'],'LEFT');
          $this->db->join($this->almacenes.' As a', 'a.id = m.id_almacen','LEFT');
          $this->db->join($this->temporal_pedido_compra.' As pc', 'pc.id_producto = p.id','LEFT');


          $this->db->where($data['where_total']);
          //$this->db->group_by("p.referencia");

          $result = $this->db->get();
      
          if ( $result->num_rows() > 0 )
             return $result->row();
          else
             return False;
          $result->free_result();              

}  




     //confirmacion de "nuevo_pedido_compra"
    public function actualizar_pedido_compra( $data ){
            $id_session = ($this->session->userdata('id'));
            foreach ($data['cantidad'] as $key => $value) {

                if(!is_numeric($value['pedido_compra'])) {  //caso cuando el peso viene vacio
                  $value['pedido_compra'] = 0;                  
                } 
                $this->db->set( 'cantidad_royo', $value['pedido_compra'], FALSE  );
                $this->db->where('id_usuario',$id_session);
                $this->db->where('id_producto',$value['id']);                
                $this->db->where('id_medida',$value['id_medida']);                

                $this->db->update($this->temporal_pedido_compra);
              }
            return TRUE;       
      }
  
    public function existencia_temporales_cantidad(){

              $id_session = $this->session->userdata('id');
              $cant=0;

              $this->db->where('id_usuario',$id_session);
              $this->db->where('cantidad_royo',0);  //no tiene peso real
              $this->db->from($this->temporal_pedido_compra);
              $cant = $this->db->count_all_results();          

              if ( $cant > 0 )
                 return false;
              else
                 return true;              

        }  


      //enviando al historico despues de confirmar  
      public function enviar_historico_pedido_compra( $data ){

            $id_session = $this->session->userdata('id');
            $fecha_hoy = date('Y-m-d H:i:s');
         
            $this->db->select('id_producto,factura,id_almacen,id_proveedor,comentario,id_medida');  
             $this->db->select('descripcion, id_color, id_composicion, id_calidad, referencia, ancho, precio');
             
             $this->db->select('fecha_entrada',false);
             $this->db->select('"'.$fecha_hoy.'" AS fecha_salida',false);
             $this->db->select('"'.$id_session.'" AS id_usuario',false); 
             
             $this->db->select('"'.addslashes($data['id_almacen']).'" AS id_almacen',false); 

             $this->db->select('cantidad_royo AS  cantidad_pedida',false); 
             $this->db->select('cantidad_royo AS  cantidad_aprobada',false); 
             $this->db->select('"'.$data['consecutivo'].'" AS movimiento',false); 
             $this->db->select('"1" AS status_compra',false); 
             $this->db->select('"1" AS consecutivo_cambio',false); 
             $this->db->select('"1;" AS recorrido_status',false); 

             $this->db->from($this->temporal_pedido_compra);
             $this->db->where('id_usuario',$id_session);


            $result = $this->db->get();
              
             $objeto = $result->result();

              //copiar a tabla "registros"
              foreach ($objeto as $key => $value) {
                //return $value;
                $this->db->insert($this->historico_pedido_compra, $value); 
              }

              //
              $this->db->set( 'consecutivo', 'consecutivo+1', FALSE  );
              $this->db->set( 'id_usuario', $id_session );
              $this->db->where('id',26);
              $this->db->update($this->operaciones);

              //
              /*
              $this->db->set('id_usuario_compra','(CASE WHEN (  LOCATE("'.$id_session.'", id_usuario_compra) >0) THEN REPLACE(id_usuario_compra,"'.$id_session.';","") ELSE id_usuario_compra END )', FALSE);       
                $this->db->set('id_usuario_compra_kg','(CASE WHEN (  LOCATE("'.$id_session.'", id_usuario_compra_kg) >0) THEN REPLACE(id_usuario_compra_kg,"'.$id_session.';","") ELSE id_usuario_compra_kg END )', FALSE);   
                */
              
              $this->db->set('id_usuario_compra','');   
              $this->db->set('id_usuario_compra_kg','');   
              $this->db->update($this->productos);   

              $this->db->delete( $this->temporal_pedido_compra, array( 'id_usuario' => $id_session ) );


             return TRUE;
  }
      

//////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////revision///////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////

/////////////////////////////////////////////////////////////////////////////////////////////////////////
 public function buscador_pedido_compra($data){

          $cadena = addslashes($data['search']['value']);
          $inicio = $data['start'];
          $largo = $data['length'];

          $columa_order = $data['order'][0]['column'];
                 $order = $data['order'][0]['dir'];

          switch ($columa_order) {
                   
                   case '0':
                        $columna = 'p.movimiento';
                     break;

                   case '1':
                        $columna = 'p.consecutivo_cambio';
                     break;
                   case '2':
                        $columna = 'p.fecha_entrada';
                     break;

                   case '3':
                        $columna = 'p.factura';
                     break;
                   case '4':
                        $columna = 'a.almacen';
                     break;

                   case '5':
                        $columna = 'p.comentario';
                     break;
                   case '6':
                        $columna = 'sum(p.precio*p.cantidad_pedida)';
                     break;                     
                   case '7':
                         $columna = 'prov.nombre'; 
                        //$columna = 'p.recorrido_status';
                     break;

                   
                   default:
                        $columna = 'p.movimiento';
                         $order = 'ASC';
                     break;
                 }                 





          $id_almacen= $data['id_almacen'];


          $id_session = $this->session->userdata('id');
          

          $this->db->select("SQL_CALC_FOUND_ROWS *", FALSE); //
          
          $this->db->select('p.movimiento, p.consecutivo_cambio,   p.factura,  p.comentario,  p.recorrido_status');
          
          $this->db->select("MAX(DATE_FORMAT(p.fecha_entrada,'%d-%m-%Y')) as fecha_entrada",false);

         $this->db->select('sum(p.precio*p.cantidad_pedida) importe');
         $this->db->select('a.almacen');
         $this->db->select('prov.nombre proveedor');
         $this->db->select('med.medida');
          

           if ($id_almacen!=0) {
              $id_almacenid = ' and ( p.id_almacen =  '.$id_almacen.' ) ';  
            } else {
              $id_almacenid = '';
            }   
          
          $fechas = ' ';
          if  ( ($data['fecha_inicial'] !="") and  ($data['fecha_final'] !="")) {
                           $fecha_inicial = date( 'Y-m-d', strtotime( $data['fecha_inicial'] ));
                           $fecha_final = date( 'Y-m-d', strtotime( $data['fecha_final'] ));
                          
                            $fechas .= ' AND ( ( DATE_FORMAT((p.fecha_entrada),"%Y-%m-%d")  >=  "'.$fecha_inicial.'" )  AND  ( DATE_FORMAT((p.fecha_entrada),"%Y-%m-%d")  <=  "'.$fecha_final.'" ) )'; 

          } else {
           $fechas .= ' ';
          }


          $this->db->from($this->historico_pedido_compra.' as p');
          $this->db->join($this->almacenes.' As a' , 'a.id = p.id_almacen','LEFT');
          $this->db->join($this->productos.' As pr', 'pr.referencia= p.referencia');
          $this->db->join($this->proveedores.' As prov', 'prov.id= p.id_proveedor');
          $this->db->join($this->unidades_medidas.' As med', 'med.id= p.id_medida');
          

          //((p.precio*p.cantidad_pedida) LIKE  "%'.$cadena.'%") OR
           $where = '(                      
                                (
                                  
                                  (prov.nombre LIKE  "%'.$cadena.'%") OR     
                                  (p.movimiento LIKE  "%'.$cadena.'%") OR     
                                  (p.descripcion LIKE  "%'.$cadena.'%") OR 
                                  (p.factura LIKE  "%'.$cadena.'%") OR    
                                  (p.consecutivo_cambio LIKE  "%'.$cadena.'%") OR    
                                  (DATE_FORMAT(p.fecha_entrada,"%d-%m-%Y") LIKE  "%'.$cadena.'%") OR    
                                  (p.comentario LIKE  "%'.$cadena.'%") OR                                      
                                  (a.almacen LIKE  "%'.$cadena.'%") OR    
                                  
                                  (pr.codigo_contable LIKE  "%'.$cadena.'%") OR
                                  (p.recorrido_status LIKE  "%'.$cadena.'%")                                   
                                )  AND  (p.status_compra =  '.$data["modulo"].') '.$fechas.$id_almacenid.' 
                    ) ';  



          $data['where_total']=$where;
          $data['id_almacenid']=$id_almacenid;


          $this->db->where($where);

          $this->db->group_by("p.movimiento");

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
                                      1=>$row->medida, //$row->consecutivo_cambio,
                                      2=>$row->fecha_entrada,
                                      3=>$row->factura,
                                      4=>$row->almacen,
                                      5=>$row->comentario,
                                      6=>( ( ($this->session->userdata('id_perfil')==1) || ( (in_array(80, $data['coleccion_id_operaciones'])) || (in_array(81, $data['coleccion_id_operaciones'])) )  ) ? number_format((($row->importe>0) ? $row->importe : $row->importe), 2, '.', ',') : '-'),  
                                      7=>$row->recorrido_status,
                                      8=>$data["modulo"],
                                      9=>$row->proveedor,
                                      //10=>$row->medida,
                                     
                                    );
                      }




                      return json_encode ( array(
                        "draw"            => intval( $data['draw'] ),
                        "recordsTotal"    => $registros_filtrados,
                        "recordsFiltered" =>   $registros_filtrados, 
                        "data"            =>  $dato,
                        "totales_importe"            =>  array(
                                "total"=>floatval( self::totales_importes_pedido($data)->importe ), 
                                 
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



    public function totales_importes_pedido($data){
              $this->db->select('sum(p.precio*cantidad_pedida) importe');

              $this->db->from($this->historico_pedido_compra.' as p');
              $this->db->join($this->almacenes.' As a' , 'a.id = p.id_almacen','LEFT');
              $this->db->join($this->productos.' As pr', 'pr.referencia= p.referencia');
              $this->db->join($this->proveedores.' As prov', 'prov.id= p.id_proveedor');

              if ($data['where_total']!=''){
                $this->db->where($data['where_total']);
              }

             // $this->db->group_by("p.movimiento");

              $result = $this->db->get();
          
              if ( $result->num_rows() > 0 )
                 return $result->row();
              else
                 return False;
              $result->free_result();              
    }  






//interior de pendiente de revision
 public function valores_revision_temporal($data){

          $id_session = $this->session->userdata('id');
          
          $this->db->distinct();          
          //$this->db->select('m.id, m.id_empresa, m.factura,m.id_almacen,m.id_factura,m.id_tipo_pago,m.iva');
          $this->db->select('pc.comentario', false);
          $this->db->select('pc.factura');
          $this->db->select('pc.movimiento');
          $this->db->select('pc.id_almacen');
          $this->db->select('pc.id_proveedor');
          $this->db->select('a.almacen');
          $this->db->select("(DATE_FORMAT(pc.fecha_entrada,'%d-%m-%Y')) as fecha_entrada",false);
          $this->db->select('pc.id_medida');

          $this->db->from($this->productos.' as p');
          $this->db->join($this->historico_pedido_compra.' As pc', 'pc.id_producto = p.id','LEFT');
          $this->db->join($this->almacenes.' As a' , 'a.id = pc.id_almacen','LEFT');
          

          
          $where = '(                      
                          (pc.movimiento = "'.$data['movimiento'].'")  AND
                          (pc.status_compra =  '.$data["modulo"].') 
                    ) ';  

           $this->db->where($where);          



           $result = $this->db->get();
        
            if ( $result->num_rows() > 0 )
               return $result->row();
            else
               return False;
            $result->free_result();
 }    


   
   //cdo confirma cambios en la revision, actualiza las cantidades aprobadas
    public function actualizar_cantidad_aprobado( $data ){
            $id_session = ($this->session->userdata('id'));

            foreach ($data['cant_aprobada'] as $key => $value) {
                if(!is_numeric($value['cantidad'])) {  //caso cuando el peso viene vacio
                  $value['cantidad'] = 0;                  
                } 
                $this->db->set( 'comentario', '"'.addslashes($data['comentario']).'"', FALSE  );

                $this->db->set( 'cantidad_aprobada', $value['cantidad'], FALSE  );
                $this->db->set( 'cantidad_pedida', $data['cant_solicitada'][$key]['cantidad'], FALSE  );
                $this->db->set( 'precio', $data['precio_aprobado'][$key]['precio'], FALSE  ); //new
                $this->db->where('id_producto',$value['id']);                
                $this->db->where('id_medida',$value['id_medida']);     
                $this->db->where('movimiento',$data['movimiento']);                
                $this->db->update($this->historico_pedido_compra);
              }

          $this->db->select("sum(cantidad_aprobada<>cantidad_pedida) as desigual", FALSE);          
          $this->db->select("sum(cantidad_aprobada) as suma", FALSE);
          $this->db->from($this->historico_pedido_compra.' as p');
          $this->db->where('movimiento',$data['movimiento']);                

          $result = $this->db->get();
      
          if ( $result->num_rows() > 0 )
             return (($result->row()->suma>0) && ($result->row()->desigual==0));
          else
             return False;
          $result->free_result();              

            return TRUE;       
      }





//para pasar al historico desde aprobado
public function confirmando_aprobado( $data ){

            $id_session = $this->session->userdata('id');
            $fecha_hoy = date('Y-m-d H:i:s');
         
            $this->db->select('id_producto,factura,id_almacen,id_proveedor,comentario, id_medida');  
             $this->db->select('descripcion, id_color, id_composicion, id_calidad, referencia, ancho, precio');
             
             $this->db->select('fecha_entrada',false);
             $this->db->select('"'.$fecha_hoy.'" AS fecha_salida',false);
             $this->db->select('"'.$id_session.'" AS id_usuario',false); 
             $this->db->select('cantidad_aprobada, cantidad_pedida, movimiento,  consecutivo_cambio',false); 
             $this->db->select('"'.$data['status_compra'].'" AS status_compra',false); 
             $this->db->select('CONCAT(recorrido_status,"'.$data['status_compra'].';") as recorrido_status', false);

             $this->db->from($this->historico_pedido_compra);
             $this->db->where('movimiento',$data['movimiento']);

            $result = $this->db->get();
              
             $objeto = $result->result();

              //copiar a tabla "registros"
              foreach ($objeto as $key => $value) {
                $this->db->insert($this->historico_historial_compra, $value); 
              }

              $this->db->delete( $this->historico_pedido_compra, array( 'movimiento' => $data['movimiento'] ) );

             return TRUE;
  }
     




//cancelando pedido de compra
public function cancelar_pedido_compra( $data ){

            $id_session = $this->session->userdata('id');
            $fecha_hoy = date('Y-m-d H:i:s');
         
            $this->db->select('id_producto,factura,id_almacen,id_proveedor,comentario,id_medida');  
             $this->db->select('descripcion, id_color, id_composicion, id_calidad, referencia, ancho, precio');
             
             $this->db->select('fecha_entrada',false);
             $this->db->select('"'.$fecha_hoy.'" AS fecha_salida',false);
             $this->db->select('"'.$id_session.'" AS id_usuario',false); 
             $this->db->select('cantidad_aprobada, cantidad_pedida, movimiento,  consecutivo_cambio',false); 
             $this->db->select('"4" AS status_compra',false); 
             $this->db->select('CONCAT(recorrido_status,"4;") as recorrido_status', false);

             $this->db->from($this->historico_pedido_compra);
             $this->db->where('movimiento',$data['movimiento']);

            $result = $this->db->get();
              
             $objeto = $result->result();

              //copiar a tabla "registros"
              foreach ($objeto as $key => $value) {
                $this->db->insert($this->historico_cancela_pedido_compra, $value); 
              }

              $this->db->delete( $this->historico_pedido_compra, array( 'movimiento' => $data['movimiento'] ) );


             return TRUE;
  }






///////////////
///////////////
///////////////  

///////////////
///////////////
///////////////


///////////////
///////////////
///////////////  



















public function notificador_pedido_compra($data){

             if ($this->session->userdata('id_almacen') != 0) {
                  $id_almacenid = ' AND ( p.id_almacen =  '.$this->session->userdata('id_almacen').' ) ';  
              } else {
                  $id_almacenid = '';
              } 
            
              $this->db->from($this->historico_pedido_compra.' as p');
              //$this->db->join($this->almacenes.' As a' , 'a.id = p.id_almacen','LEFT');
              //$this->db->join($this->productos.' As pr', 'pr.referencia= p.referencia');

              $where = '(                      
                           (p.status_compra =  '.$data["modulo"].') '.$id_almacenid.' 
                    ) ';  

              $this->db->where($where);      

              $this->db->group_by("p.movimiento");
             
              $result = $this->db->get();

              $cant = $result->num_rows(); 
     
              if ( $cant > 0 )
                 return $cant;
              else
                 return 0;         
       }     


 public function total_modulo($data){
  
              $id_almacen= $data['id_almacen'];
              
              if ($id_almacen!=0) {
                  $id_almacenid = ' and ( p.id_almacen =  '.$id_almacen.' ) ';  
              } else {
                  $id_almacenid = '';
              }   

              if ($data["mod"]==4) { //cancelado
                  $this->db->from($this->historico_cancela_pedido_compra.' as p');
              }
              if ($data["mod"]==5) { //historial
                  $this->db->from($this->historico_historial_compra.' as p');
              }
              if (($data["mod"]!=4) && ($data["mod"]!=5)) {
                  $this->db->from($this->historico_pedido_compra.' as p');
              }


              $where = '(                      
                            (p.status_compra =  '.$data["mod"].') '.$id_almacenid.' 
              ) ';  


              $this->db->where($where);

              $this->db->group_by("p.movimiento");
             
              $result = $this->db->get();

              $registros_filtrados=0;
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
   


    //para pasar al 1-solicitud, 2-modificado o 3-aprobado
    public function confirmar_cambio( $data ){
              $id_session = $this->session->userdata('id');
              $fecha_hoy = date('Y-m-d H:i:s');
            
               //$this->db->select('fecha_entrada',false);
              
               //$this->db->select('"'.$fecha_hoy.'" AS fecha_salida',false);
               //$this->db->select('"'.$id_session.'" AS id_usuario',false); 

               //cuando el almacenista haga nueva modificación
               if (($data['status_compra']==1) && ($data['modulo']==2) ) {
                  $this->db->set('consecutivo_cambio','consecutivo_cambio+1' , FALSE  ); 
               }
               

               $this->db->set('fecha_salida', '"'.$fecha_hoy.'"', FALSE  );
               $this->db->set('id_usuario', '"'.$id_session.'"', FALSE  );

               $this->db->set('recorrido_status', 'CONCAT(recorrido_status,"'.$data['status_compra'].';")', FALSE  );
               $this->db->set('status_compra', '"'.$data['status_compra'].'"', FALSE  );
             $this->db->where('movimiento',$data['movimiento']);
            $this->db->update($this->historico_pedido_compra);

            return TRUE;       
      }

/////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////cancelar pedido compra///////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////







/////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////Listado de los cancelados ///////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////

/////////////////////////////////////////////////////////////////////////////////////////////////////////
 public function buscador_cancela_compra($data){

          $cadena = addslashes($data['search']['value']);
          $inicio = $data['start'];
          $largo = $data['length'];

          $columa_order = $data['order'][0]['column'];
                 $order = $data['order'][0]['dir'];

        
          switch ($columa_order) {
                   case '0':
                        $columna = 'p.movimiento';
                     break;
                   case '1':
                        $columna = 'p.consecutivo_cambio';
                     break;
                   case '2':
                        $columna = 'p.fecha_entrada';
                     break;
                   case '3':
                        $columna = 'p.factura';
                     break;
                   case '4':
                        $columna = 'a.almacen';
                     break;

                   case '5':
                        $columna = 'p.comentario';
                     break;
                   case '6':
                        $columna = 'sum(p.precio*p.cantidad_pedida)';
                     break;                     
                   case '7':
                        $columna = 'prov.nombre'; 
                        //$columna = 'p.recorrido_status';
                     break;

                   
                   default:
                        $columna = 'p.movimiento';
                         $order = 'ASC';
                     break;
            }              

          $id_almacen= $data['id_almacen'];


          $id_session = $this->session->userdata('id');
          

          $this->db->select("SQL_CALC_FOUND_ROWS *", FALSE); //
          
          $this->db->select('p.movimiento, p.consecutivo_cambio,  p.factura,  p.comentario,  p.recorrido_status');
          
          $this->db->select("MAX(DATE_FORMAT(p.fecha_entrada,'%d-%m-%Y')) as fecha_entrada",false);

         $this->db->select('sum(p.precio*p.cantidad_pedida) importe');
         $this->db->select('a.almacen');
         $this->db->select('prov.nombre proveedor');

          

           if ($id_almacen!=0) {
              $id_almacenid = ' and ( p.id_almacen =  '.$id_almacen.' ) ';  
            } else {
              $id_almacenid = '';
            }   

          $fechas = ' ';
          if  ( ($data['fecha_inicial'] !="") and  ($data['fecha_final'] !="")) {
                           $fecha_inicial = date( 'Y-m-d', strtotime( $data['fecha_inicial'] ));
                           $fecha_final = date( 'Y-m-d', strtotime( $data['fecha_final'] ));
                          
                            $fechas .= ' AND ( ( DATE_FORMAT((p.fecha_entrada),"%Y-%m-%d")  >=  "'.$fecha_inicial.'" )  AND  ( DATE_FORMAT((p.fecha_entrada),"%Y-%m-%d")  <=  "'.$fecha_final.'" ) )'; 

          } else {
           $fechas .= ' ';
          }

          $this->db->select('med.medida');

          $this->db->from($this->historico_cancela_pedido_compra.' as p');
          $this->db->join($this->almacenes.' As a' , 'a.id = p.id_almacen','LEFT');
          $this->db->join($this->productos.' As pr', 'pr.referencia= p.referencia');
          $this->db->join($this->proveedores.' As prov', 'prov.id= p.id_proveedor');
          $this->db->join($this->unidades_medidas.' As med', 'med.id= p.id_medida');



          //((p.precio*p.cantidad_pedida) LIKE  "%'.$cadena.'%") OR
           $where = '(                      
                                (
                                  (prov.nombre LIKE  "%'.$cadena.'%") OR      
                                  (p.movimiento LIKE  "%'.$cadena.'%") OR     
                                  (p.descripcion LIKE  "%'.$cadena.'%") OR 
                                  (p.factura LIKE  "%'.$cadena.'%") OR    
                                  (p.consecutivo_cambio LIKE  "%'.$cadena.'%") OR    
                                  (DATE_FORMAT(p.fecha_entrada,"%d-%m-%Y") LIKE  "%'.$cadena.'%") OR    
                                  (p.comentario LIKE  "%'.$cadena.'%") OR                                      
                                  (a.almacen LIKE  "%'.$cadena.'%") OR    
                                  
                                  (pr.codigo_contable LIKE  "%'.$cadena.'%") OR
                                  (p.recorrido_status LIKE  "%'.$cadena.'%")                                   
                                )  AND  (p.status_compra =  '.$data["modulo"].') '.$fechas.$id_almacenid.' 
                    ) ';  




                     
                     

          $data['where_total']=$where;
          $data['id_almacenid']=$id_almacenid;


          $this->db->where($where);

          $this->db->group_by("p.movimiento");

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
                                      /*
                                      movimiento, consecutivo_cambio, fecha_entrada,  factura, id_almacen, comentario,  recorrido_status
                                      */

                                      0=>$row->movimiento,
                                      1=>$row->medida, //consecutivo_cambio,
                                      2=>$row->fecha_entrada,
                                      3=>$row->factura,
                                      4=>$row->almacen,
                                      5=>$row->comentario,
                                      6=>( ( ($this->session->userdata('id_perfil')==1) || ( (in_array(80, $data['coleccion_id_operaciones'])) || (in_array(81, $data['coleccion_id_operaciones'])) )  ) ? number_format((($row->importe>0) ? $row->importe : $row->importe), 2, '.', ',') : '-'),  
                                      7=>$row->recorrido_status,
                                      8=>$data["modulo"],
                                      9=>$row->proveedor,
                                      
                                      
                                    );
                      }




                      return json_encode ( array(
                        "draw"            => intval( $data['draw'] ),
                        "recordsTotal"    => intval( self::total_cancela_compra($data) ), 
                        "recordsFiltered" =>   $registros_filtrados, 
                        "data"            =>  $dato,
                        "totales_importe"            =>  array(
                                "total"=>floatval( self::totales_importes_cancela($data)->importe ), 
                                 
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



    public function totales_importes_cancela($data){
              $this->db->select('sum(p.precio*cantidad_pedida) importe');

              $this->db->from($this->historico_cancela_pedido_compra.' as p');
              $this->db->join($this->almacenes.' As a' , 'a.id = p.id_almacen','LEFT');
              $this->db->join($this->productos.' As pr', 'pr.referencia= p.referencia');       
              $this->db->join($this->proveedores.' As prov', 'prov.id= p.id_proveedor');       

              if ($data['where_total']!=''){
                $this->db->where($data['where_total']);
              }

             // $this->db->group_by("p.movimiento");

              $result = $this->db->get();
          
              if ( $result->num_rows() > 0 )
                 return $result->row();
              else
                 return False;
              $result->free_result();              
    }  


      public function total_cancela_compra($data){
              $id_session = $this->session->userdata('id');


              $this->db->from($this->historico_cancela_pedido_compra.' as p');
              $this->db->join($this->almacenes.' As a' , 'a.id = p.id_almacen','LEFT');
              $this->db->join($this->productos.' As pr', 'pr.referencia= p.referencia');
              $this->db->join($this->proveedores.' As prov', 'prov.id= p.id_proveedor');

              if ($data['where_total']!=''){
                $this->db->where($data['where_total']);
              }

              $this->db->group_by("p.movimiento");
             
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




/////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////Listado de los historiales de las compras ///////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////

/////////////////////////////////////////////////////////////////////////////////////////////////////////
 public function buscador_historial_compra($data){

          $cadena = addslashes($data['search']['value']);
          $inicio = $data['start'];
          $largo = $data['length'];

          $columa_order = $data['order'][0]['column'];
                 $order = $data['order'][0]['dir'];

          switch ($columa_order) {
                   
                   case '0':
                        $columna = 'p.movimiento';
                     break;

                   case '1':
                        $columna = 'p.consecutivo_cambio';
                     break;
                   case '2':
                        $columna = 'p.fecha_entrada';
                     break;

                   case '3':
                        $columna = 'p.factura';
                     break;
                   case '4':
                        $columna = 'a.almacen';
                     break;

                   case '5':
                        $columna = 'p.comentario';
                     break;
                   case '6':
                        $columna = 'sum(p.precio*p.cantidad_pedida)';
                     break;                     
                   case '7':
                        //$columna = 'p.recorrido_status';
                        $columna = 'prov.nombre'; 
                     break;

                   
                   default:
                        $columna = 'p.movimiento';
                         $order = 'ASC';
                     break;
           }              

          $id_almacen= $data['id_almacen'];


          $id_session = $this->session->userdata('id');
          

          $this->db->select("SQL_CALC_FOUND_ROWS *", FALSE); //
          
          $this->db->select('p.movimiento, p.consecutivo_cambio,   p.factura,  p.comentario,  p.recorrido_status');
          
          $this->db->select("MAX(DATE_FORMAT(p.fecha_entrada,'%d-%m-%Y')) as fecha_entrada",false);

         $this->db->select('sum(p.precio*p.cantidad_pedida) importe');
         $this->db->select('a.almacen');
         $this->db->select('prov.nombre proveedor');
         $this->db->select('med.medida');

           if ($id_almacen!=0) {
              $id_almacenid = ' and ( p.id_almacen =  '.$id_almacen.' ) ';  
            } else {
              $id_almacenid = '';
            }   

          $fechas = ' ';
          if  ( ($data['fecha_inicial'] !="") and  ($data['fecha_final'] !="")) {
                           $fecha_inicial = date( 'Y-m-d', strtotime( $data['fecha_inicial'] ));
                           $fecha_final = date( 'Y-m-d', strtotime( $data['fecha_final'] ));
                          
                            $fechas .= ' AND ( ( DATE_FORMAT((p.fecha_entrada),"%Y-%m-%d")  >=  "'.$fecha_inicial.'" )  AND  ( DATE_FORMAT((p.fecha_entrada),"%Y-%m-%d")  <=  "'.$fecha_final.'" ) )'; 

          } else {
           $fechas .= ' ';
          }            




          $this->db->select("CASE WHEN (sum(m.cantidad_um*(m.id_medida=1)) is null)  THEN 0 Else sum(m.cantidad_um*(m.id_medida=1)) END AS  total_entrada_mts",false);  
            $this->db->select("CASE WHEN (sum(m.cantidad_um*(m.id_medida=2)) is null)  THEN 0 Else sum(m.cantidad_um*(m.id_medida=2)) END AS  total_entrada_kg",false);  



          $this->db->select("CASE WHEN (sum(t.cantidad_um*(t.id_medida=1)) is null)  THEN 0 Else sum(t.cantidad_um*(t.id_medida=1)) END AS  total_entrada_temp_mts",false);  
            $this->db->select("CASE WHEN (sum(t.cantidad_um*(t.id_medida=2)) is null)  THEN 0 Else sum(t.cantidad_um*(t.id_medida=2)) END AS  total_entrada_temp_kg",false);  


            $this->db->select("max(p.cantidad_aprobada*(p.id_medida=1)) total_compra_mts",false);  
            $this->db->select("max(p.cantidad_aprobada*(p.id_medida=2)) total_compra_kg",false);  
          



          $this->db->from($this->historico_historial_compra.' as p');
          $this->db->join($this->almacenes.' As a' , 'a.id = p.id_almacen','LEFT');
          $this->db->join($this->productos.' As pr', 'pr.referencia= p.referencia');
          $this->db->join($this->proveedores.' As prov', 'prov.id= p.id_proveedor');
          $this->db->join($this->unidades_medidas.' As med', 'med.id= p.id_medida');
          $this->db->join($this->historico_registros_entradas.' As m' , 'p.referencia = m.referencia AND p.movimiento=m.id_compra AND p.id_medida=m.id_medida','LEFT'); 
          $this->db->join($this->registros_temporales.' As t' , 'p.referencia = t.referencia AND p.movimiento=t.id_compra AND p.id_medida=t.id_medida','LEFT'); 

          


           $where = '(                      
                                (
                                  (prov.nombre LIKE  "%'.$cadena.'%") OR      
                                  (p.movimiento LIKE  "%'.$cadena.'%") OR     
                                  (p.descripcion LIKE  "%'.$cadena.'%") OR 
                                  (p.factura LIKE  "%'.$cadena.'%") OR    
                                  (p.consecutivo_cambio LIKE  "%'.$cadena.'%") OR    
                                  (DATE_FORMAT(p.fecha_entrada,"%d-%m-%Y") LIKE  "%'.$cadena.'%") OR    
                                  (p.comentario LIKE  "%'.$cadena.'%") OR                                      
                                  (a.almacen LIKE  "%'.$cadena.'%") OR    
                                  
                                  (pr.codigo_contable LIKE  "%'.$cadena.'%") OR
                                  (p.recorrido_status LIKE  "%'.$cadena.'%")                                   
                                )  AND  (p.status_compra =  '.$data["modulo"].') '.$fechas.$id_almacenid.' 
                    ) ';  




                     
                     

          $data['where_total']=$where;
          $data['id_almacenid']=$id_almacenid;


          $this->db->where($where);

          $this->db->group_by("p.movimiento");

          //$this->db->having("(total_compra_mts > (total_entrada_mts+total_entrada_temp_mts)) OR (total_compra_kg > (total_entrada_kg+total_entrada_temp_kg))");

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
                                      /*
                                      movimiento, consecutivo_cambio, fecha_entrada,  factura, id_almacen, comentario,  recorrido_status
                                      */

                                      0=>$row->movimiento,
                                      1=>$row->medida, //consecutivo_cambio,
                                      2=>$row->fecha_entrada,
                                      3=>$row->factura,
                                      4=>$row->almacen,
                                      5=>$row->comentario,
                                      6=>( ( ($this->session->userdata('id_perfil')==1) || ( (in_array(80, $data['coleccion_id_operaciones'])) || (in_array(81, $data['coleccion_id_operaciones'])) )  ) ? number_format((($row->importe>0) ? $row->importe : $row->importe), 2, '.', ',') : '-'),  
                                      7=>$row->recorrido_status,
                                      8=>$data["modulo"],
                                      9=>$row->proveedor,          
                                      10=>(($row->total_compra_mts <= ($row->total_entrada_mts+$row->total_entrada_temp_mts)) AND ($row->total_compra_kg <= ($row->total_entrada_kg+$row->total_entrada_temp_kg)) ) ? '<span style="color:red;">Total</span>' : (( (($row->total_entrada_mts+$row->total_entrada_temp_mts) + ($row->total_entrada_kg+$row->total_entrada_temp_kg) ) ==0 ) ? '<span style="color:blue;">Por Procesar</span>' : 'Parcial'),   
                                      
                                      
                                      
                                    );
                      }




                      return json_encode ( array(
                        "draw"            => intval( $data['draw'] ),
                        "recordsTotal"    => intval( self::total_historial_compra($data) ), 
                        "recordsFiltered" =>   $registros_filtrados, 
                        "data"            =>  $dato,
                        "totales_importe"            =>  array(
                                "total"=>floatval( self::totales_importes_historial($data)->importe ), 
                                 
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



    public function totales_importes_historial($data){
              $this->db->select('sum(p.precio*cantidad_pedida) importe');



              $this->db->from($this->historico_historial_compra.' as p');
              $this->db->join($this->almacenes.' As a' , 'a.id = p.id_almacen','LEFT');
              $this->db->join($this->productos.' As pr', 'pr.referencia= p.referencia');
              $this->db->join($this->proveedores.' As prov', 'prov.id= p.id_proveedor');

              if ($data['where_total']!=''){
                $this->db->where($data['where_total']);
              }

             // $this->db->group_by("p.movimiento");

              $result = $this->db->get();
          
              if ( $result->num_rows() > 0 )
                 return $result->row();
              else
                 return False;
              $result->free_result();              
    }  


      public function total_historial_compra($data){
              $id_session = $this->session->userdata('id');

              $this->db->from($this->historico_historial_compra.' as p');
              $this->db->join($this->almacenes.' As a' , 'a.id = p.id_almacen','LEFT');
              $this->db->join($this->productos.' As pr', 'pr.referencia= p.referencia');
              $this->db->join($this->proveedores.' As prov', 'prov.id= p.id_proveedor');

              if ($data['where_total']!=''){
                $this->db->where($data['where_total']);
              }

              $this->db->group_by("p.movimiento");
             
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







  



            





    public function checar_salida_compra($data){

            $id_session = $this->session->userdata('id');

            $this->db->select("id", FALSE);         
            $this->db->from($this->temporal_pedido_compra);
            $this->db->where('id_producto',$data['id']);
            $this->db->where('id_usuario',$id_session);

            $this->db->where('id_medida',$data['id_medida']);

           


            $login = $this->db->get();
            if ($login->num_rows() > 0) {
                return true;
            }    
            else
                return false;
            $login->free_result();
    } 

    /*
    inven_catalogo_status_pedido_compra

ALTER TABLE  `inven_temporal_pedido_compra` ADD  `id_proveedor` INT( 11 ) NOT NULL
ALTER TABLE  `inven_historico_pedido_compra` ADD  `id_proveedor` INT( 11 ) NOT NULL ;
ALTER TABLE  `inven_historico_cancela_pedido_compra` ADD  `id_proveedor` INT( 11 ) NOT NULL ;
ALTER TABLE  `inven_historico_historial_compra` ADD  `id_proveedor` INT( 11 ) NOT NULL


    */
    public function enviar_salida_compra( $data ){

            $id_session = $this->session->userdata('id');
            $fecha_hoy = date('Y-m-d H:i:s');
          
            

          if ($data['id_medida']==1) {  //metro
                $this->db->set( 'id_usuario_compra', 'CONCAT(id_usuario_compra,"'.$id_session.'",";")', FALSE );    
            }   else { //kg
               $this->db->set( 'id_usuario_compra_kg', 'CONCAT(id_usuario_compra,"'.$id_session.'",";")', FALSE );    

            }  
            
            $this->db->where('id',$data['id']);
            $this->db->update($this->productos);   


             $this->db->select($data['id'].' AS id_producto',false);  
             $this->db->select('"'.$fecha_hoy.'" AS fecha_entrada',false);
             $this->db->select('"'.$fecha_hoy.'" AS fecha_salida',false);
             
             if  (isset($data['factura'])) {
               $this->db->select('"'.addslashes($data['factura']).'" AS factura',false); 
             }
               
             $this->db->select('"'.addslashes($data['movimiento']).'" AS movimiento',false); 
             $this->db->select('"'.addslashes($data['id_almacen']).'" AS id_almacen',false); 
             $this->db->select('"'.addslashes($data['id_proveedor']).'" AS id_proveedor',false); 

             $this->db->select('"'.addslashes($data['id_medida']).'" AS id_medida',false); 

             $this->db->select('"'.addslashes($data['comentario']).'" AS comentario',false); 
             $this->db->select('"'.$id_session.'" AS id_usuario',false); 
             $this->db->select('descripcion, id_color, id_composicion, id_calidad, referencia, ancho, precio');

             $this->db->from($this->productos);
             $this->db->where('id',$data['id']);
             $result = $this->db->get();
              
             $objeto = $result->result();

              //copiar a tabla "registros"
              foreach ($objeto as $key => $value) {
                //return $value;
                $this->db->insert($this->temporal_pedido_compra, $value); 
              }
           return TRUE;
            
            
       }
   
 public function quitar_salida_compra( $data ){

            $id_session = $this->session->userdata('id');
            
          
            

            if ($data['id_medida']==1) {  //metro
               $this->db->set('id_usuario_compra','');   
                //$this->db->set('id_usuario_compra','(CASE WHEN (  LOCATE("'.$id_session.'", id_usuario_compra) >0) THEN REPLACE(id_usuario_compra,"'.$id_session.';","") ELSE id_usuario_compra END )', FALSE);       
            }   else { //kg
              $this->db->set('id_usuario_compra_kg','');   
               //$this->db->set('id_usuario_compra_kg','(CASE WHEN (  LOCATE("'.$id_session.'", id_usuario_compra_kg) >0) THEN REPLACE(id_usuario_compra_kg,"'.$id_session.';","") ELSE id_usuario_compra_kg END )', FALSE);       

            }  

            $this->db->where('id',$data['id']);
            $this->db->update($this->productos);   

            $this->db->delete( $this->temporal_pedido_compra, array( 'id_producto' => $data['id'], 'id_medida' => $data['id_medida']  ) );

           return TRUE;
            
            
       }



/////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////Listado de los historial ///////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////

 public function valores_revision_historial($data){

          $id_session = $this->session->userdata('id');
          
          $this->db->distinct();          
          //$this->db->select('m.id, m.id_empresa, m.factura,m.id_almacen,m.id_factura,m.id_tipo_pago,m.iva');
          $this->db->select('pc.comentario', false);
          $this->db->select('pc.factura');
          $this->db->select('pc.movimiento');
          $this->db->select('pc.id_almacen,pc.id_proveedor');
          $this->db->select('a.almacen');
          $this->db->select("(DATE_FORMAT(pc.fecha_entrada,'%d-%m-%Y')) as fecha_entrada",false);

          $this->db->from($this->productos.' as p');
          $this->db->join($this->historico_historial_compra.' As pc', 'pc.id_producto = p.id','LEFT');
          $this->db->join($this->almacenes.' As a' , 'a.id = pc.id_almacen','LEFT');

          
          $where = '(                      
                          (pc.movimiento = "'.$data['movimiento'].'")  AND
                          (pc.status_compra =  '.$data["modulo"].') 
                    ) ';  

           $this->db->where($where);          



           $result = $this->db->get();
        
            if ( $result->num_rows() > 0 )
               return $result->row();
            else
               return False;
            $result->free_result();
 }    



       







 public function valores_revision_cancelar($data){

          $id_session = $this->session->userdata('id');
          
          $this->db->distinct();          
          //$this->db->select('m.id, m.id_empresa, m.factura,m.id_almacen,m.id_factura,m.id_tipo_pago,m.iva');
          $this->db->select('pc.comentario', false);
          $this->db->select('pc.factura');
          $this->db->select('pc.movimiento');
          $this->db->select('pc.id_almacen');
          $this->db->select('pc.id_proveedor');
          $this->db->select('a.almacen');
          $this->db->select("(DATE_FORMAT(pc.fecha_entrada,'%d-%m-%Y')) as fecha_entrada",false);

          $this->db->from($this->productos.' as p');
          $this->db->join($this->historico_cancela_pedido_compra.' As pc', 'pc.id_producto = p.id','LEFT');
          $this->db->join($this->almacenes.' As a' , 'a.id = pc.id_almacen','LEFT');

          
          $where = '(                      
                          (pc.movimiento = "'.$data['movimiento'].'")  AND
                          (pc.status_compra =  '.$data["modulo"].') 
                    ) ';  

           $this->db->where($where);          



           $result = $this->db->get();
        
            if ( $result->num_rows() > 0 )
               return $result->row();
            else
               return False;
            $result->free_result();
 }    









 public function buscador_revisar_pedido_compra($data){

          $cadena = addslashes($data['search']['value']);
          $inicio = $data['start'];
          $largo = $data['length'];

          $columa_order = $data['order'][0]['column'];
                 $order = $data['order'][0]['dir'];

          switch ($columa_order) {
                   case '0':
                        $columna = 'p.descripcion';
                     break;
                   case '1':
                        $columna = 'p.imagen';
                     break;
                   case '2':
                        $columna = 'c.color';
                     break;
                  case '3':
                        $columna = 'pc.ancho';
                     break;                     
                   case '4':
                        $columna = 'co.composicion';
                     break;
                   case '5':
                        $columna = 'ca.calidad';
                     break;

                   case '6':
                        $columna = 'pc.precio';
                     break;
                   case '6':
                        $columna = 'suma';
                     break;                     
                   
                   default:
                        $columna = 'suma';
                         $order = 'ASC';
                     break;
                 }                 

          $id_almacen= $data['id_almacen'];
          $movimiento= $data['movimiento'];
          
          
          $id_session = $this->session->userdata('id');
          

          $this->db->select("SQL_CALC_FOUND_ROWS(p.id)"); //
          $this->db->select('p.id, p.referencia,p.codigo_contable');
          $this->db->select('p.descripcion, p.imagen, c.hexadecimal_color,c.color nombre_color');
          $this->db->select('co.composicion, ca.calidad'); //a.almacen, , p.activo, p.fecha_mac, p.uid,
          $this->db->select("pc.ancho, pc.precio");
          //$this->db->select("p.minimo");
          $this->db->select("( CASE WHEN pc.id_medida = 1 THEN p.minimo ELSE p.minimo_kg END ) AS minimo", FALSE);
          $this->db->select("COUNT(m.referencia) as 'suma'");
          $this->db->select("pc.cantidad_pedida as cantidad_pedida");
          $this->db->select("pc.cantidad_aprobada as cantidad_aprobada");
          $this->db->select("um.medida, pc.id_medida");
                                     


           if ($id_almacen!=0) {
              $id_almacenid = ' and ( pc.id_almacen =  '.$id_almacen.' ) ';  
              $id_almacenidid = ' and ( m.id_almacen =  '.$id_almacen.' ) ';  
              
            } else {
              $id_almacenid = '';
              $id_almacenidid = '';
            }   


          $this->db->from($this->productos.' as p');
          $this->db->join($this->colores.' As c', 'p.id_color = c.id','LEFT');
          $this->db->join($this->composiciones.' As co', 'p.id_composicion = co.id','LEFT');
          $this->db->join($this->calidades.' As ca', 'p.id_calidad = ca.id','LEFT');
          $this->db->join($this->historico_pedido_compra.' As pc', 'pc.id_producto = p.id','LEFT');
          $this->db->join($this->almacenes.' As a', 'a.id = pc.id_almacen'.$id_almacenid,'LEFT');
          $this->db->join($this->registros.' As m', 'm.referencia= p.referencia'.$id_almacenidid,'LEFT');
          $this->db->join($this->unidades_medidas.' As um', 'pc.id_medida= um.id');
          
          

          $where = '(                      
                            (
                               (p.descripcion LIKE  "%'.$cadena.'%") OR 
                               (p.codigo_contable LIKE  "%'.$cadena.'%") OR
                               (CONCAT("Optimo:",p.minimo) LIKE  "%'.$cadena.'%")  OR
                               
                              (c.color LIKE  "%'.$cadena.'%") OR
                              (co.composicion LIKE  "%'.$cadena.'%")  OR
                              ( ca.calidad LIKE  "%'.$cadena.'%" )  OR 
                              ( pc.precio LIKE  "%'.$cadena.'%" ) OR 
                              ( pc.ancho LIKE  "%'.$cadena.'%" ) 
                             )   AND (pc.movimiento = '.$movimiento.')  
          )';  

          $data['where_total']=$where;
          $data['id_almacenid']=$id_almacenid;
          $data['id_almacenidid']=$id_almacenidid;


          $this->db->where($where);

          $this->db->group_by("p.referencia, pc.id_medida");

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

                     
                          //variables para cachear las imagenes                                                  
                          $fechaSegundos = time(); 
                          $strNoCache = "?nocache=$fechaSegundos"; 

                        $nombre_fichero ='';
                        $nombre_fichero ='uploads/productos/thumbnail/300X300/'.substr($row->imagen,0,strrpos($row->imagen,".")).'_thumb'.substr($row->imagen,strrpos($row->imagen,"."));
                        if (file_exists($nombre_fichero)) {
                            
                            $imagen ='<img src="'.base_url().$nombre_fichero.$strNoCache.'" border="0" width="100%" height="auto">';

                        } else {
                            $imagen ='<img src="'.base_url().'img/sinimagen.png" border="0" width="75" height="75">';
                        }



                            $dato[]= array(
                                      
                                      0=>$row->descripcion,
                                      1=>$imagen,  
                                      2=>$row->nombre_color.
                                        '<div style="background-color:#'.$row->hexadecimal_color.';display:block;width:15px;height:15px;margin:0 auto;"></div>',
                                      3=>number_format((($row->ancho>0) ? $row->ancho : $row->ancho), 2, '.', ','),   
                                      4=>$row->composicion, 
                                      5=>$row->calidad, 
                                      6=>( ( ($this->session->userdata('id_perfil')==1) || ( (in_array(80, $data['coleccion_id_operaciones'])) || (in_array(81, $data['coleccion_id_operaciones'])) )  ) ? number_format((($row->precio>0) ? $row->precio : $row->precio), 2, '.', ',') : '-'),

                                      7=>'Optimo:'.$row->minimo.'<br/>  Reales:'. $row->suma,
                                      8=>$row->codigo_contable,
                                      9=>$row->id, 
                                      10=>$row->referencia,
                                      11=>$row->cantidad_pedida,
                                      12=>$row->cantidad_aprobada,
                                      13=>"",
                                      14=>"",
                                      15=>$row->medida,
                                      16=>$row->id_medida,
                                      //17=>$row->precio_aprobado,

                                      
                                      
                                    );
                      }




                      return json_encode ( array(
                        "draw"            => intval( $data['draw'] ),
                        "recordsTotal"    => $registros_filtrados, 
                        "recordsFiltered" =>   $registros_filtrados, 
                        "data"            =>  $dato,
                         "totales_importe"            =>  array(
                                "total"=>floatval( self::totales_importes_revisa($data)->importe ), 
                                 
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


     

public function totales_importes_revisa($data){
              

              $id_session = $this->session->userdata('id');

              $this->db->select('sum(pc.precio*pc.cantidad_pedida) importe');              
              

              $this->db->from($this->productos.' as p');
              $this->db->join($this->colores.' As c', 'p.id_color = c.id','LEFT');
              $this->db->join($this->composiciones.' As co', 'p.id_composicion = co.id','LEFT');
              $this->db->join($this->calidades.' As ca', 'p.id_calidad = ca.id','LEFT');
              $this->db->join($this->historico_pedido_compra.' As pc', 'pc.id_producto = p.id','LEFT');
              $this->db->join($this->almacenes.' As a', 'a.id = pc.id_almacen'.$data['id_almacenid'],'LEFT');
              $this->db->join($this->registros.' As m', 'm.referencia= p.referencia'.$data['id_almacenidid'],'LEFT');
          
              if ($data['where_total']!=''){
                $this->db->where($data['where_total']);
              }

              //$this->db->group_by("p.referencia");
             
              $result = $this->db->get();
          
              if ( $result->num_rows() > 0 )
                 return $result->row();
              else
                 return False;
              $result->free_result();   



       }     



///////////////////////////////para las cancelaciones



 public function buscador_revisar_cancela_compra($data){

          $cadena = addslashes($data['search']['value']);
          $inicio = $data['start'];
          $largo = $data['length'];

          $columa_order = $data['order'][0]['column'];
                 $order = $data['order'][0]['dir'];

          switch ($columa_order) {
                   case '0':
                        $columna = 'p.descripcion';
                     break;
                   case '1':
                        $columna = 'p.imagen';
                     break;
                   case '2':
                        $columna = 'c.color';
                     break;
                  case '3':
                        $columna = 'pc.ancho';
                     break;                     
                   case '4':
                        $columna = 'co.composicion';
                     break;
                   case '5':
                        $columna = 'ca.calidad';
                     break;

                   case '6':
                        $columna = 'pc.precio';
                     break;
                   case '6':
                        $columna = 'suma';
                     break;                     
                   
                   default:
                        $columna = 'suma';
                         $order = 'ASC';
                     break;
                 }                 

          $id_almacen= $data['id_almacen'];
          $movimiento= $data['movimiento'];
          
          
          $id_session = $this->session->userdata('id');
          

          $this->db->select("SQL_CALC_FOUND_ROWS(p.id)"); //
          $this->db->select('p.id,  p.referencia,p.codigo_contable');
          $this->db->select('p.descripcion, p.imagen, c.hexadecimal_color,c.color nombre_color');
          $this->db->select('co.composicion, ca.calidad');
          $this->db->select("pc.ancho,pc.precio");
          //$this->db->select("p.minimo"); //a.almacen,, p.activo,p.fecha_mac,p.uid,
          $this->db->select("( CASE WHEN pc.id_medida = 1 THEN p.minimo ELSE p.minimo_kg END ) AS minimo", FALSE);
          $this->db->select("COUNT(m.referencia) as 'suma'");
          $this->db->select("pc.cantidad_pedida as cantidad_pedida");
          $this->db->select("pc.cantidad_aprobada as cantidad_aprobada");

           if ($id_almacen!=0) {
              $id_almacenid = ' and ( pc.id_almacen =  '.$id_almacen.' ) ';  
              $id_almacenidid = ' and ( m.id_almacen =  '.$id_almacen.' ) ';  
              
            } else {
              $id_almacenid = '';
              $id_almacenidid = '';
            }   

            $this->db->select("um.medida, pc.id_medida");
          $this->db->from($this->productos.' as p');
          $this->db->join($this->colores.' As c', 'p.id_color = c.id','LEFT');
          $this->db->join($this->composiciones.' As co', 'p.id_composicion = co.id','LEFT');
          $this->db->join($this->calidades.' As ca', 'p.id_calidad = ca.id','LEFT');
          $this->db->join($this->historico_cancela_pedido_compra.' As pc', 'pc.id_producto = p.id','LEFT');
          $this->db->join($this->almacenes.' As a', 'a.id = pc.id_almacen'.$id_almacenid,'LEFT');
          $this->db->join($this->registros.' As m', 'm.referencia= p.referencia'.$id_almacenidid,'LEFT');
          $this->db->join($this->unidades_medidas.' As um', 'pc.id_medida= um.id');
          
          $where = '(                      
                            (
                               (p.descripcion LIKE  "%'.$cadena.'%") OR 
                               (p.codigo_contable LIKE  "%'.$cadena.'%") OR
                               (CONCAT("Optimo:",p.minimo) LIKE  "%'.$cadena.'%")  OR
                               
                              (c.color LIKE  "%'.$cadena.'%") OR
                              (co.composicion LIKE  "%'.$cadena.'%")  OR
                              ( ca.calidad LIKE  "%'.$cadena.'%" )  OR 
                              ( pc.precio LIKE  "%'.$cadena.'%" ) OR 
                              ( pc.ancho LIKE  "%'.$cadena.'%" ) 
                             )   AND (pc.movimiento = '.$movimiento.')  
          )';  

          $data['where_total']=$where;
          $data['id_almacenid']=$id_almacenid;
          $data['id_almacenidid']=$id_almacenidid;


          $this->db->where($where);
          $this->db->group_by("p.referencia,pc.id_medida");
          $this->db->order_by($columna, $order); 
          $this->db->limit($largo,$inicio); 


         $result = $this->db->get();


              if ( $result->num_rows() > 0 ) {

                    $cantidad_consulta = $this->db->query("SELECT FOUND_ROWS() as cantidad");
                    $found_rows = $cantidad_consulta->row(); 
                    $registros_filtrados =  ( (int) $found_rows->cantidad);

                  $retorno= " ";  
                  foreach ($result->result() as $row) {

                     
                          //variables para cachear las imagenes                                                  
                          $fechaSegundos = time(); 
                          $strNoCache = "?nocache=$fechaSegundos"; 

                        $nombre_fichero ='';
                        $nombre_fichero ='uploads/productos/thumbnail/300X300/'.substr($row->imagen,0,strrpos($row->imagen,".")).'_thumb'.substr($row->imagen,strrpos($row->imagen,"."));
                        if (file_exists($nombre_fichero)) {
                            
                            $imagen ='<img src="'.base_url().$nombre_fichero.$strNoCache.'" border="0" width="100%" height="auto">';

                        } else {
                            $imagen ='<img src="'.base_url().'img/sinimagen.png" border="0" width="75" height="75">';
                        }

                            $dato[]= array(
                                      
                                      0=>$row->descripcion,
                                      1=>$imagen,  
                                      2=>$row->nombre_color.
                                        '<div style="background-color:#'.$row->hexadecimal_color.';display:block;width:15px;height:15px;margin:0 auto;"></div>',
                                      3=>number_format((($row->ancho>0) ? $row->ancho : $row->ancho), 2, '.', ','),   
                                      4=>$row->composicion, 
                                      5=>$row->calidad, 
                                      
                                      6=>( ( ($this->session->userdata('id_perfil')==1) || ( (in_array(80, $data['coleccion_id_operaciones'])) || (in_array(81, $data['coleccion_id_operaciones'])) )  ) ? number_format((($row->precio>0) ? $row->precio : $row->precio), 2, '.', ',') : '-'),
                                      7=>'Optimo:'.$row->minimo.'<br/>  Reales:'. $row->suma,
                                      8=>$row->codigo_contable,
                                      9=>$row->id, 
                                      10=>$row->referencia,
                                      11=>$row->cantidad_pedida,
                                      12=>$row->cantidad_aprobada,
                                      13=>"",
                                      14=>"",
                                      15=>$row->medida,
                                      16=>$row->id_medida,

                                    );
                      }

                      return json_encode ( array(
                        "draw"            => intval( $data['draw'] ),
                        "recordsTotal"    => $registros_filtrados, 
                        "recordsFiltered" =>   $registros_filtrados, 
                        "data"            =>  $dato,
                         "totales_importe"            =>  array(
                                "total"=>floatval( self::totales_importes_cancela_compra($data)->importe ), 
                                 
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





public function totales_importes_cancela_compra($data){
              

              $id_session = $this->session->userdata('id');

              $this->db->select('sum(pc.precio*pc.cantidad_pedida) importe');              
              

              $this->db->from($this->productos.' as p');
              $this->db->join($this->colores.' As c', 'p.id_color = c.id','LEFT');
              $this->db->join($this->composiciones.' As co', 'p.id_composicion = co.id','LEFT');
              $this->db->join($this->calidades.' As ca', 'p.id_calidad = ca.id','LEFT');
              $this->db->join($this->historico_cancela_pedido_compra.' As pc', 'pc.id_producto = p.id','LEFT');
              $this->db->join($this->almacenes.' As a', 'a.id = pc.id_almacen'.$data['id_almacenid'],'LEFT');
              $this->db->join($this->registros.' As m', 'm.referencia= p.referencia'.$data['id_almacenidid'],'LEFT');
          
              if ($data['where_total']!=''){
                $this->db->where($data['where_total']);
              }

              //$this->db->group_by("p.referencia");
             
              $result = $this->db->get();
          
              if ( $result->num_rows() > 0 )
                 return $result->row();
              else
                 return False;
              $result->free_result();   



       }     



////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////       

 public function buscador_revisar_historial_compra($data){

          $cadena = addslashes($data['search']['value']);
          $inicio = $data['start'];
          $largo = $data['length'];

          $columa_order = $data['order'][0]['column'];
                 $order = $data['order'][0]['dir'];

          switch ($columa_order) {
                   case '0':
                        $columna = 'p.descripcion';
                     break;
                   case '1':
                        $columna = 'p.imagen';
                     break;
                   case '2':
                        $columna = 'c.color';
                     break;
                  case '3':
                        $columna = 'pc.ancho';
                     break;                     
                   case '4':
                        $columna = 'co.composicion';
                     break;
                   case '5':
                        $columna = 'ca.calidad';
                     break;

                   case '6':
                        $columna = 'pc.precio';
                     break;
                   case '6':
                        $columna = 'suma';
                     break;                     
                   
                   default:
                        $columna = 'suma';
                         $order = 'ASC';
                     break;
                 }                 

          $id_almacen= $data['id_almacen'];
          $movimiento= $data['movimiento'];
          
          
          $id_session = $this->session->userdata('id');
          

          $this->db->select("SQL_CALC_FOUND_ROWS(p.id)"); //
          $this->db->select('p.id, p.referencia,p.codigo_contable, m.id_operacion');
          $this->db->select('p.descripcion, p.imagen, c.hexadecimal_color,c.color nombre_color');
          $this->db->select('co.composicion, ca.calidad');
          $this->db->select("pc.ancho,pc.precio");
          //$this->db->select("p.minimo"); //a.almacen,
          $this->db->select("( CASE WHEN pc.id_medida = 1 THEN p.minimo ELSE p.minimo_kg END ) AS minimo", FALSE);
          $this->db->select("COUNT(m.referencia) as 'suma'");
          $this->db->select("pc.cantidad_pedida as cantidad_pedida");
          $this->db->select("pc.cantidad_aprobada as cantidad_aprobada");

          $this->db->select("pc.movimiento");


          //$this->db->select("h.id_compra, h.devolucion, h.movimiento_unico, h.nombre_usuario, h.id_fac_orig, h.id_estatus");

          //$this->db->select("sum(h.cantidad_um)*(h.referencia= pc.referencia and h.id_compra= pc.movimiento and h.id_medida=pc.id_medida) as suma_cantidad_um");

          $this->db->select("um.medida, pc.id_medida");



           if ($id_almacen!=0) {
              $id_almacenid = ' and ( pc.id_almacen =  '.$id_almacen.' ) ';  
              $id_almacenidid = ' and ( m.id_almacen =  '.$id_almacen.' ) ';  
              
            } else {
              $id_almacenid = '';
              $id_almacenidid = '';
            }   

          $this->db->from($this->productos.' as p');
          $this->db->join($this->colores.' As c', 'p.id_color = c.id','LEFT');
          $this->db->join($this->composiciones.' As co', 'p.id_composicion = co.id','LEFT');
          $this->db->join($this->calidades.' As ca', 'p.id_calidad = ca.id','LEFT');
          $this->db->join($this->historico_historial_compra.' As pc', 'pc.id_producto = p.id','LEFT');
          $this->db->join($this->almacenes.' As a', 'a.id = pc.id_almacen'.$id_almacenid,'LEFT');
          $this->db->join($this->registros.' As m', 'm.referencia= p.referencia'.$id_almacenidid,'LEFT');
          $this->db->join($this->unidades_medidas.' As um', 'pc.id_medida= um.id');
         // $this->db->join($this->historico_registros_entradas.' As h', 'h.referencia= pc.referencia and h.id_compra= pc.movimiento and h.id_medida=pc.id_medida','LEFT');

          $where = '(                      
                            (
                               (p.descripcion LIKE  "%'.$cadena.'%") OR 
                               (p.codigo_contable LIKE  "%'.$cadena.'%") OR
                               (CONCAT("Optimo:",p.minimo) LIKE  "%'.$cadena.'%")  OR
                               
                              (c.color LIKE  "%'.$cadena.'%") OR
                              (co.composicion LIKE  "%'.$cadena.'%")  OR
                              ( ca.calidad LIKE  "%'.$cadena.'%" )  OR 
                              ( pc.precio LIKE  "%'.$cadena.'%" ) OR 
                              ( pc.ancho LIKE  "%'.$cadena.'%" ) 
                             )   AND (pc.movimiento = '.$movimiento.')  
          )';  

          $data['where_total']=$where;
          $data['id_almacenid']=$id_almacenid;
          $data['id_almacenidid']=$id_almacenidid;


          $this->db->where($where);
          $this->db->group_by("p.referencia, pc.id_medida");
          $this->db->order_by($columna, $order); 
          $this->db->limit($largo,$inicio); 


         $result = $this->db->get();

         //return ($this->db->last_query());
              if ( $result->num_rows() > 0 ) {

                    $cantidad_consulta = $this->db->query("SELECT FOUND_ROWS() as cantidad");
                    $found_rows = $cantidad_consulta->row(); 
                    $registros_filtrados =  ( (int) $found_rows->cantidad);

                  $retorno= " ";  
                  foreach ($result->result() as $row) {

                     
                          //variables para cachear las imagenes                                                  
                          $fechaSegundos = time(); 
                          $strNoCache = "?nocache=$fechaSegundos"; 

                        $nombre_fichero ='';
                        $nombre_fichero ='uploads/productos/thumbnail/300X300/'.substr($row->imagen,0,strrpos($row->imagen,".")).'_thumb'.substr($row->imagen,strrpos($row->imagen,"."));
                        if (file_exists($nombre_fichero)) {
                            
                            $imagen ='<img src="'.base_url().$nombre_fichero.$strNoCache.'" border="0" width="100%" height="auto">';

                        } else {
                            $imagen ='<img src="'.base_url().'img/sinimagen.png" border="0" width="75" height="75">';
                        }

                          /*
                             $mov_entrada='<a style="  padding: 1px 0px 1px 0px;" href="'.base_url().'procesar_entradas/'.base64_encode((($row->id_compra!=0) ? 'C-' : (($row->devolucion<>0) ? 'D-' :  (($row->nombre_usuario!='') ? 'T-' :'E-') )).$row->movimiento_unico).'/'.base64_encode($row->devolucion).'/'.base64_encode($retorno).'/'.base64_encode($row->id_fac_orig).'/'.base64_encode($row->id_estatus).'" type="button" class="btn btn-success btn-block">'.(($row->id_compra!=0) ? 'C-' : (($row->devolucion<>0) ? 'D-' :  (($row->nombre_usuario!='') ? 'T-' :'E-') )).$row->movimiento_unico.'</a>';  
                             */

                            $dato[]= array(
                                      0=>$row->descripcion,
                                      1=>$imagen,  
                                      2=>$row->nombre_color.
                                        '<div style="background-color:#'.$row->hexadecimal_color.';display:block;width:15px;height:15px;margin:0 auto;"></div>',
                                      3=>number_format((($row->ancho>0) ? $row->ancho : $row->ancho), 2, '.', ','),   
                                      4=>$row->composicion, 
                                      5=>$row->calidad, 
                                      6=>( ( ($this->session->userdata('id_perfil')==1) || ( (in_array(80, $data['coleccion_id_operaciones'])) || (in_array(81, $data['coleccion_id_operaciones'])) )  ) ? number_format((($row->precio>0) ? $row->precio : $row->precio), 2, '.', ',') : '-'),
                                      7=>'Optimo:'.$row->minimo.'<br/>  Reales:'. $row->suma,
                                      8=>$row->codigo_contable,
                                      9=>$row->id, 
                                      10=>$row->referencia,
                                      11=>$row->cantidad_pedida,
                                      12=>$row->cantidad_aprobada,
                                      13=>(self::total_diferencia('pc.referencia ="'.$row->referencia.'" and pc.movimiento ='.$row->movimiento)->importe), //$mov_entrada,
                                      14=>(self::botones_compra('pc.referencia ="'.$row->referencia.'" and pc.movimiento ='.$row->movimiento)),
                                      15=>$row->medida,
                                      16=>$row->id_medida,

                                    );
                      }




                      return json_encode ( array(
                        "draw"            => intval( $data['draw'] ),
                        "recordsTotal"    => $registros_filtrados,  
                        "recordsFiltered" =>   $registros_filtrados, 
                        "data"            =>  $dato,
                         "totales_importe"            =>  array(
                                "total"=>floatval( self::totales_importes_historial_compra($data)->importe ), 
                                 
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




public function botones_compra($data){
              

              $id_session = $this->session->userdata('id');

              //(($row->id_compra!=0) ? 'C-' : (($row->devolucion<>0) ? 'D-' :  (($row->nombre_usuario!='') ? 'T-' :'E-') ))

              //(($row->id_compra!=0) ? 'C-' : (($row->devolucion<>0) ? 'D-' :  (($row->nombre_usuario!='') ? 'T-' :'E-') ))
              $this->db->select('pc.movimiento');  
              $this->db->select("h.id_compra, h.devolucion, h.movimiento_unico, h.nombre_usuario, h.id_fac_orig, h.id_estatus, h.precio, h.c234, h.id_operacion");

              
              $this->db->from($this->historico_historial_compra.' As pc');
               $this->db->join($this->historico_registros_entradas.' As h', 'h.referencia= pc.referencia and h.id_compra= pc.movimiento and h.id_medida=pc.id_medida');
              if ($data!=''){
                $this->db->where($data);
              }

              $this->db->group_by('h.movimiento_unico');

              $result = $this->db->get();

                if ( $result->num_rows() > 0 ) {

                  
                  $dato="";
                  foreach ($result->result() as $row) {
                    $retorno= "detalle_revision/".base64_encode($row->movimiento).'/'.base64_encode(5);  

                             $mov_entrada='<a style="  padding: 1px 0px 1px 0px;" href="'.base_url().'procesar_entradas/'.base64_encode((($row->id_operacion==72) ? 'B-' : (($row->id_operacion==71) ? 'C-' : (($row->devolucion<>0) ? 'D-' :  (($row->id_operacion==70) ? 'T-' : (($row->id_operacion==73) ? 'A-' :'E-') ) ))).$row->movimiento_unico).'/'.base64_encode($row->devolucion).'/'.base64_encode($retorno).'/'.base64_encode($row->id_fac_orig).'/'.base64_encode($row->id_estatus).'" type="button" class="btn btn-success btn-block">'.(($row->id_operacion==72) ? 'B-' : (($row->id_operacion==71) ? 'C-' : (($row->devolucion<>0) ? 'D-' :  (($row->id_operacion==70) ? 'T-' : (($row->id_operacion==73) ? 'A-' :'E-') ) ))).$row->c234.'</a>';   //.' - '.$row->precio

                            $dato.=$mov_entrada;
                                       
                                   
                      }



                      return  $dato;
                        
 
                      

                }    
                else
                 return "";  

            
              $result->free_result();   
 }          

public function total_diferencia($data){
              

              $id_session = $this->session->userdata('id');

              $this->db->select('sum(h.cantidad_um) importe');              
              
              $this->db->from($this->historico_historial_compra.' As pc');
               $this->db->join($this->historico_registros_entradas.' As h', 'h.referencia= pc.referencia and h.id_compra= pc.movimiento and h.id_medida=pc.id_medida');
              if ($data!=''){
                $this->db->where($data);
              }

              $result = $this->db->get();
          
              if ( $result->num_rows() > 0 )
                 return $result->row();
              else
                 return False;
              $result->free_result();   
 }          


public function totales_importes_historial_compra($data){
              

              $id_session = $this->session->userdata('id');

              $this->db->select('sum(pc.precio*pc.cantidad_pedida) importe');              
              

              $this->db->from($this->productos.' as p');
              $this->db->join($this->colores.' As c', 'p.id_color = c.id','LEFT');
              $this->db->join($this->composiciones.' As co', 'p.id_composicion = co.id','LEFT');
              $this->db->join($this->calidades.' As ca', 'p.id_calidad = ca.id','LEFT');
              $this->db->join($this->historico_historial_compra.' As pc', 'pc.id_producto = p.id','LEFT');
              $this->db->join($this->almacenes.' As a', 'a.id = pc.id_almacen'.$data['id_almacenid'],'LEFT');
              $this->db->join($this->registros.' As m', 'm.referencia= p.referencia'.$data['id_almacenidid'],'LEFT');
          
              if ($data['where_total']!=''){
                $this->db->where($data['where_total']);
              }

              //$this->db->group_by("p.referencia");
             
              $result = $this->db->get();
          
              if ( $result->num_rows() > 0 )
                 return $result->row();
              else
                 return False;
              $result->free_result();   



       }     





  } 
?>

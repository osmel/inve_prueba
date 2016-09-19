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

      $this->temporal_pedido_compra        = $this->db->dbprefix('temporal_pedido_compra');
      

    }


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
          

          $this->db->select("SQL_CALC_FOUND_ROWS *", FALSE); //
          
          $this->db->select('p.id, p.uid, p.referencia,p.codigo_contable');
          $this->db->select('p.descripcion, p.imagen,p.fecha_mac, c.hexadecimal_color,c.color nombre_color');
          $this->db->select('co.composicion, ca.calidad, p.activo');
          
          $this->db->select("m.ancho, p.ancho ancho_producto", FALSE);
          $this->db->select("m.precio, p.precio precio_producto", FALSE);

          $this->db->select("((m.precio*m.iva))/100 as sum_iva", FALSE);
          $this->db->select("(m.precio)+((m.precio*m.iva))/100 as precio_total", FALSE);          
          $this->db->select("a.almacen, p.minimo");
          $this->db->select("COUNT(m.referencia) as 'suma'");

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
                                 )   AND  (!(LOCATE("'.$id_session.'", id_usuario_compra) >0))         
                    ) ';  




                      $where_total ='';
                      if ( (($id_calidad!="0") AND ($id_calidad!="") AND ($id_calidad!= null))
                        and (($id_composicion!="0") AND ($id_composicion!="") AND ($id_composicion!= null))
                        and (($id_color!="0") AND ($id_color!="") AND ($id_color!= null))
                        and (($descripcion!="0") AND ($descripcion!="") AND ($descripcion!= null)) 
                        ) {
                          $where .= ' AND ( p.descripcion  =  "'.$descripcion.'" ) AND  ( p.id_color  =  '.$id_color.' )';
                          $where .= ' AND ( p.id_composicion  =  '.$id_composicion.' ) AND  ( p.id_calidad  =  '.$id_calidad.' )';
                          $where_total .= '( p.descripcion  =  "'.$descripcion.'" ) AND  ( p.id_color  =  '.$id_color.' )';
                          $where_total .= ' AND ( p.id_composicion  =  '.$id_composicion.' ) AND  ( p.id_calidad  =  '.$id_calidad.' )';
                      }    

                      elseif
                       ( 
                           (($id_composicion!="0") AND ($id_composicion!="") AND ($id_composicion!= null))
                        and (($id_color!="0") AND ($id_color!="") AND ($id_color!= null))
                        and (($descripcion!="0") AND ($descripcion!="") AND ($descripcion!= null)) 
                        ) {
                          $where .= ' AND ( p.descripcion  =  "'.$descripcion.'" ) AND  ( p.id_color  =  '.$id_color.' )';
                          $where .= ' AND ( p.id_composicion  =  '.$id_composicion.' ) ';
                          $where_total .= '( p.descripcion  =  "'.$descripcion.'" ) AND  ( p.id_color  =  '.$id_color.' )';
                          $where_total .= ' AND ( p.id_composicion  =  '.$id_composicion.' ) ';
                      }  

                      elseif 
                       ( (($id_color!="0") AND ($id_color!="") AND ($id_color!= null))
                        and (($descripcion!="0") AND ($descripcion!="") AND ($descripcion!= null)) 
                        ) {
                          $where .= ' AND ( p.descripcion  =  "'.$descripcion.'" ) AND  ( p.id_color  =  '.$id_color.' )';
                          $where_total .= '( p.descripcion  =  "'.$descripcion.'" ) AND  ( p.id_color  =  '.$id_color.' )';
                      }  

                      elseif  (($descripcion!="0") AND ($descripcion!="") AND ($descripcion!= null)) {
                          $where .= ' AND ( p.descripcion  =  "'.$descripcion.'" )';
                          $where_total  .= '( p.descripcion  =  "'.$descripcion.'" )';
                      } 

          $data['where_total']=$where;
          $data['id_almacenid']=$id_almacenid;


          $this->db->where($where);

          $this->db->group_by("p.referencia");

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
                            $imagen ='<img src="img/sinimagen.png" border="0" width="75" height="75">';
                        }



                            $dato[]= array(
                                      
                                      0=>$row->descripcion,
                                      1=>$imagen,  
                                      2=>$row->nombre_color.
                                        '<div style="background-color:#'.$row->hexadecimal_color.';display:block;width:15px;height:15px;margin:0 auto;"></div>',
                                      3=>number_format((($row->ancho>0) ? $row->ancho : $row->ancho_producto), 2, '.', ','),   
                                      4=>$row->composicion, 
                                      5=>$row->calidad, 
                                      6=>number_format((($row->precio>0) ? $row->precio : $row->precio_producto), 2, '.', ','),  
                                      7=>'Optimo:'.$row->minimo.'<br/>  Reales:'. $row->suma,
                                      8=>$row->codigo_contable,
                                      9=>$row->id, 
                                      10=>$row->referencia,
                                    );
                      }




                      return json_encode ( array(
                        "draw"            => intval( $data['draw'] ),
                        "recordsTotal"    => intval( self::total_cat_productos($data) ), 
                        "recordsFiltered" =>   $registros_filtrados, 
                        "data"            =>  $dato,
                        "totales_importe"            =>  array(
                                "precio"=>floatval( self::totales_importes($data)->precio ), 
                                "precio_producto"=>floatval( self::totales_importes($data)->precio_producto ), 
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
          $this->db->join($this->colores.' As c', 'p.id_color = c.id','LEFT');
          $this->db->join($this->composiciones.' As co', 'p.id_composicion = co.id','LEFT');
          $this->db->join($this->calidades.' As ca', 'p.id_calidad = ca.id','LEFT');
          $this->db->join($this->registros.' As m', 'm.referencia= p.referencia'.$data['id_almacenid'],'LEFT');
          $this->db->join($this->almacenes.' As a', 'a.id = m.id_almacen','LEFT');



          $this->db->where($data['where_total']);
          $this->db->group_by("p.referencia");

          $result = $this->db->get();
      
          if ( $result->num_rows() > 0 )
             return $result->row();
          else
             return False;
          $result->free_result();              

}  

 public function total_cat_productos($data){
              

              $id_session = $this->session->userdata('id');

              $this->db->from($this->productos.' as p');
              $this->db->join($this->colores.' As c', 'p.id_color = c.id','LEFT');
              $this->db->join($this->composiciones.' As co', 'p.id_composicion = co.id','LEFT');
              $this->db->join($this->calidades.' As ca', 'p.id_calidad = ca.id','LEFT');
              $this->db->join($this->registros.' As m', 'm.referencia= p.referencia'.$data['id_almacenid'],'LEFT');
              $this->db->join($this->almacenes.' As a', 'a.id = m.id_almacen','LEFT');

              if ($data['where_total']!=''){
                $this->db->where($data['where_total']);
              }

              $this->db->group_by("p.referencia");
             
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



public function totales_importes222222222($where,$having){
           /* 
           $this->db->select("total-sum(pr.importe) AS monto_restante", FALSE);
           $this->db->select("(m.id_tipo_pago) AS id_tipo_pago", FALSE);
           $this->db->select("(subtotal) as subtotal", FALSE);
           */

           //$this->db->select("(iva) as iva", FALSE);
           //$this->db->select("(total) as total", FALSE);
   
          $this->db->select("sum(m.precio) precio", FALSE);
          $this->db->select("sum(p.precio) precio_producto", FALSE);

          $this->db->from($this->productos.' as p');
          $this->db->join($this->colores.' As c', 'p.id_color = c.id','LEFT');
          $this->db->join($this->composiciones.' As co', 'p.id_composicion = co.id','LEFT');
          $this->db->join($this->calidades.' As ca', 'p.id_calidad = ca.id','LEFT');
          $this->db->join($this->registros.' As m', 'm.referencia= p.referencia'.$data['id_almacenid'],'LEFT');
          $this->db->join($this->almacenes.' As a', 'a.id = m.id_almacen','LEFT');

          $this->db->where($where);          
          $this->db->group_by("p.referencia");
          //$this->db->having($having);

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

    public function total_cat_productos222($data){

              $id_session = $this->session->userdata('id');

              $this->db->from($this->productos.' as p');
              $this->db->join($this->colores.' As c', 'p.id_color = c.id','LEFT');
              $this->db->join($this->composiciones.' As co', 'p.id_composicion = co.id','LEFT');
              $this->db->join($this->calidades.' As ca', 'p.id_calidad = ca.id','LEFT');
              $this->db->join($this->registros.' As m', 'm.referencia= p.referencia'.$data['id_almacenid'],'LEFT');
              $this->db->join($this->almacenes.' As a', 'a.id = m.id_almacen','LEFT');

              if ($data['where_total']!=''){
                $this->db->where($data['where_total']);
              }

              $this->db->group_by("p.referencia");

              $cant = $this->db->count_all_results();          
     
              if ( $cant > 0 )
                 return $cant;
              else
                 return 0;         
    }     











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
          

          $this->db->select("SQL_CALC_FOUND_ROWS *", FALSE); //
          
          $this->db->select('p.id, p.uid, p.referencia,p.codigo_contable');
          $this->db->select('p.descripcion, p.imagen,p.fecha_mac, c.hexadecimal_color,c.color nombre_color');
          $this->db->select('co.composicion, ca.calidad, p.activo');
          
          $this->db->select("m.ancho, p.ancho ancho_producto", FALSE);
          $this->db->select("m.precio, p.precio precio_producto", FALSE);

          $this->db->select("((m.precio*m.iva))/100 as sum_iva", FALSE);
          $this->db->select("(m.precio)+((m.precio*m.iva))/100 as precio_total", FALSE);          
          $this->db->select("a.almacen, p.minimo");
          $this->db->select("COUNT(m.referencia) as 'suma'");
         

         //$this->db->select('substring(id_usuario_compra, length("'.$id_session.'")+2,locate(";",id_usuario_compra,LOCATE("'.$id_session.':", id_usuario_compra)+length("'.$id_session.'"))-length("'.$id_session.'")-2) as pedido_compra', FALSE);        

          $this->db->select("pc.cantidad_royo as pedido_compra");

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
                                 )  AND  ( LOCATE("'.$id_session.'", id_usuario_compra) >0)  
                    ) ';  




                     
                     

          $data['where_total']=$where;
          $data['id_almacenid']=$id_almacenid;


          $this->db->where($where);

          $this->db->group_by("p.referencia");

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
                            $imagen ='<img src="img/sinimagen.png" border="0" width="75" height="75">';
                        }



                            $dato[]= array(
                                      
                                      0=>$row->descripcion,
                                      1=>$imagen,  
                                      2=>$row->nombre_color.
                                        '<div style="background-color:#'.$row->hexadecimal_color.';display:block;width:15px;height:15px;margin:0 auto;"></div>',
                                      3=>number_format((($row->ancho>0) ? $row->ancho : $row->ancho_producto), 2, '.', ','),   
                                      4=>$row->composicion, 
                                      5=>$row->calidad, 
                                      6=>number_format((($row->precio>0) ? $row->precio : $row->precio_producto), 2, '.', ','),  
                                      7=>'Optimo:'.$row->minimo.'<br/>  Reales:'. $row->suma,
                                      8=>$row->codigo_contable,
                                      9=>$row->id, 
                                      10=>$row->referencia,
                                      11=>$row->pedido_compra,
                                      
                                      
                                    );
                      }




                      return json_encode ( array(
                        "draw"            => intval( $data['draw'] ),
                        "recordsTotal"    => intval( self::total_cat_productos($data) ), 
                        "recordsFiltered" =>   $registros_filtrados, 
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

   
   /*

             //$this->db->select('( LOCATE("'.$id_session.':", id_usuario_compra) >0) as valor', FALSE);          
          //$this->db->select('CONCAT(recorrido,"'.$data["elemento"].'",":a,") recorrido',false);  
          //$this->db->set( 'recorrido', 'CONCAT(recorrido,"'.$data["elemento"].'",":a,")', FALSE );    
          //tamaño de id_session http://www.tutorialesprogramacionya.com/mysqlya/temarios/descripcion.php?cod=75&punto=25&inicio=
          //$this->db->select('length("'.$id_session.'") as valor', FALSE);      
          //$this->db->select('LOCATE("'.$id_session.':", id_usuario_compra)+length("'.$id_session.'") as valor', FALSE);          

          //$this->db->select('locate(";",id_usuario_compra,LOCATE("'.$id_session.':", id_usuario_compra)+length("'.$id_session.'")) as valor', FALSE);        

          //$this->db->select('SUBSTRING(id_usuario_compra, 37,3) as valor', FALSE);        
          //29ec421d-7a85-11e6-a083-7071bce181c3:30;
          //SELECT SUBSTRING(`id_usuario_compra`, 37, 40) FROM `inven_catalogo_productos` WHERE `id_usuario_compra`<>''

          //substring('Buenas tardes',3,5);  


`id`, `fecha_entrada`, `fecha_salida`, `movimiento`, `id_empresa`, `factura`, 
`id_almacen`, `comentario`, 
`id_usuario`, `fecha_mac`, 
`id_descripcion`, `id_color`, `id_composicion`, `id_calidad`, `referencia`,
 ` `ancho`, `precio`, `codigo`, 
 cantidad_royo`,
              $this->db->select('"'.htmlspecialchars($data['id_cliente']).'" AS id_cliente',false);
             $this->db->select('"'.htmlspecialchars($data['id_cargador']).'" AS id_cargador',false);

             //$this->db->select('CONCAT(id_usuario_compra,"'.$id_session.'",";") AS id_usuario_compra', FALSE );    
             //$this->db->set( 'id_usuario_compra', 'CONCAT(id_usuario_compra,"'.$id_session.'",";")', FALSE );    
             //$this->db->select('( LOCATE("'.$id_session.':", id_usuario_compra) >0) as valor', FALSE);          


   */
    
    public function enviar_salida_compra( $data ){

            $id_session = $this->session->userdata('id');
            $fecha_hoy = date('Y-m-d H:i:s');  //date_format($fecha_hoy , 'Y-m-d H:i:s');
          
            $this->db->set( 'id_usuario_compra', 'CONCAT(id_usuario_compra,"'.$id_session.'",";")', FALSE );    
            $this->db->where('id',$data['id']);
            $this->db->update($this->productos);   


             $this->db->select($data['id'].' AS id_producto',false);  
             $this->db->select('"'.$fecha_hoy.'" AS fecha_entrada',false);
             $this->db->select('"'.$fecha_hoy.'" AS fecha_salida',false);
             $this->db->select('"'.$fecha_hoy.'" AS fecha_mac',false);
             $this->db->select('"'.addslashes($data['factura']).'" AS factura',false); 
             $this->db->select('"'.addslashes($data['movimiento']).'" AS movimiento',false); 
             $this->db->select('"'.addslashes($data['id_almacen']).'" AS id_almacen',false); 
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
       /*
        public function eliminar_medida( $data ){
            $this->db->delete( $this->unidades_medidas, array( 'id' => $data['id'] ) );
            if ( $this->db->affected_rows() > 0 ) return TRUE;
            else return FALSE;
        }*/

 public function quitar_salida_compra( $data ){

            $id_session = $this->session->userdata('id');
            $fecha_hoy = date('Y-m-d H:i:s');  //date_format($fecha_hoy , 'Y-m-d H:i:s');
          
            $this->db->set('id_usuario_compra','(CASE WHEN (  LOCATE("'.$id_session.'", id_usuario_compra) >0) THEN REPLACE(id_usuario_compra,"'.$id_session.'","") ELSE id_usuario_compra END )', FALSE);       
            $this->db->where('id',$data['id']);
            $this->db->update($this->productos);   

            $this->db->delete( $this->temporal_pedido_compra, array( 'id_producto' => $data['id'] ) );

           return TRUE;
            
            
       }





        public function valores_movimientos_temporal(){

          $id_session = $this->session->userdata('id');
          
          $this->db->distinct();          
          //$this->db->select('m.id, m.id_empresa, m.factura,m.id_almacen,m.id_factura,m.id_tipo_pago,m.iva');
          $this->db->select('pc.comentario', false);
          $this->db->select('pc.factura');
          $this->db->select('pc.movimiento');
          $this->db->select('pc.id_almacen');
          
          $this->db->from($this->productos.' as p');
          $this->db->join($this->temporal_pedido_compra.' As pc', 'pc.id_producto = p.id','LEFT');
          
          $where = '(                      
                            ( LOCATE("'.$id_session.'", p.id_usuario_compra) >0)  
                    ) ';  

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

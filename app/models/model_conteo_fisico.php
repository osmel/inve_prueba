<?php if(! defined('BASEPATH')) exit('No tienes permiso para acceder a este archivo');
  class model_conteo_fisico extends CI_Model {
    
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
      
      $this->registros_entradas               = $this->db->dbprefix('registros_entradas');
      $this->registros_salidas       = $this->db->dbprefix('registros_salidas');
      $this->registros_temporales               = $this->db->dbprefix('temporal_registros');
      $this->registros_cambios               = $this->db->dbprefix('registros_cambios');

      $this->historico_registros_entradas = $this->db->dbprefix('historico_registros_entradas');
      $this->historico_registros_salidas = $this->db->dbprefix('historico_registros_salidas');
      $this->historico_registros_traspasos        = $this->db->dbprefix('historico_registros_traspasos');
      $this->historico_acceso        = $this->db->dbprefix('historico_acceso');

      $this->historico_pagos_realizados        = $this->db->dbprefix('historico_pagos_realizados');
      $this->historico_ctasxpagar        = $this->db->dbprefix('historico_ctasxpagar');

     
      $this->temporal_pedido_compra        = $this->db->dbprefix('temporal_pedido_compra');
      $this->historico_pedido_compra        = $this->db->dbprefix('historico_pedido_compra');
      $this->historico_cancela_pedido_compra      = $this->db->dbprefix('historico_cancela_pedido_compra');
      $this->historico_historial_compra      = $this->db->dbprefix('historico_historial_compra');

      $this->catalogo_operaciones      = $this->db->dbprefix('catalogo_operaciones');
      $this->almacenes      = $this->db->dbprefix('catalogo_almacenes');

      //proceso de conteo
      $this->conteo_almacen      = $this->db->dbprefix('conteo_almacen');
      $this->productos           = $this->db->dbprefix('catalogo_productos');      
      $this->operaciones             = $this->db->dbprefix('catalogo_operaciones');


      $this->colores                 = $this->db->dbprefix('catalogo_colores');
      $this->composiciones     = $this->db->dbprefix('catalogo_composicion');
      $this->calidades                 = $this->db->dbprefix('catalogo_calidad'); 
    }


////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////proceso de conteo//////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////    

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



    public function creando_conteo($data){
         $fecha_hoy = date('Y-m-d H:i:s');  

          $id_almacen= $data['id_almacen'];
          $id_descripcion= addslashes($data['id_descripcion']);
          $id_color= $data['id_color'];
          $id_composicion= $data['id_composicion'];
          $id_calidad= $data['id_calidad'];
          $id_session = $this->session->userdata('id');

          $consecutivo = self::consecutivo_operacion(50); //cambio

          /*
              codigo_contable, grupo, referencia, imagen, descripcion, id_composicion, id_color, id_calidad, id_usuario, fecha_mac, comentario, cantidad_royo, conteo1, conteo2, conteo3, estatus_conteo
          */
          
          $this->db->select('"'.$consecutivo.'" AS consecutivo',false);
          $this->db->select("p.codigo_contable,p.grupo,p.referencia");    
          $this->db->select('p.imagen');
          $this->db->select('p.descripcion');
          $this->db->select('p.id_composicion,p.id_color,p.id_calidad');
          
          //id_usuario, cantidad_royo,id_almacen
          $this->db->select("COUNT(m.referencia) as 'cantidad_royo'"); //cantidad_royo
          $this->db->select("m.id_almacen");
         
          $this->db->select('"'.$id_session.'" as id_usuario', false);
          $this->db->select('"'.$fecha_hoy.'" AS fecha_creacion',false);
          

          
          $id_almacenid = ' AND (m.id_almacen =  '.$id_almacen.' )' ;  
          
          $this->db->from($this->productos.' as p');
          $this->db->join($this->registros_entradas.' As m', 'm.referencia= p.referencia'.$id_almacenid,'LEFT');
        
          $activo  = ' and ( p.activo =  0 ) '; 
          $where = '( 
                        (m.id_almacen =  '.$id_almacen.' )'.$activo.'
                     ) ' ; 


         $where_cond ='';

         if ( (($id_calidad!="0") AND ($id_calidad!="") AND ($id_calidad!= null))
            and (($id_composicion!="0") AND ($id_composicion!="") AND ($id_composicion!= null))
            and (($id_color!="0") AND ($id_color!="") AND ($id_color!= null))
            and (($id_descripcion!="0") AND ($id_descripcion!="") AND ($id_descripcion!= null)) 
            ) {

              $where .= ' AND ( p.descripcion  =  "'.$id_descripcion.'" ) AND  ( p.id_color  =  '.$id_color.' )';
              $where .= ' AND ( p.id_composicion  =  '.$id_composicion.' ) AND  ( p.id_calidad  =  '.$id_calidad.' )';
              $where_cond = '( p.descripcion  =  "'.$id_descripcion.'" ) AND  ( p.id_color  =  '.$id_color.' )';
              $where_cond .= ' AND ( p.id_composicion  =  '.$id_composicion.' ) AND  ( p.id_calidad  =  '.$id_calidad.' )';
          }    
          elseif
           ( 
               (($id_composicion!="0") AND ($id_composicion!="") AND ($id_composicion!= null))
            and (($id_color!="0") AND ($id_color!="") AND ($id_color!= null))
            and (($id_descripcion!="0") AND ($id_descripcion!="") AND ($id_descripcion!= null)) 
            ) {
              $where .= ' AND ( p.descripcion  =  "'.$id_descripcion.'" ) AND  ( p.id_color  =  '.$id_color.' )';
              $where .= ' AND ( p.id_composicion  =  '.$id_composicion.' ) ';
              $where_cond = '( p.descripcion  =  "'.$id_descripcion.'" ) AND  ( p.id_color  =  '.$id_color.' )';
              $where_cond .= ' AND ( p.id_composicion  =  '.$id_composicion.' ) ';
          }  

          elseif 
           ( (($id_color!="0") AND ($id_color!="") AND ($id_color!= null))
            and (($id_descripcion!="0") AND ($id_descripcion!="") AND ($id_descripcion!= null)) 
            ) {
              $where .= ' AND ( p.descripcion  =  "'.$id_descripcion.'" ) AND  ( p.id_color  =  '.$id_color.' )';
              $where_cond = '( p.descripcion  =  "'.$id_descripcion.'" ) AND  ( p.id_color  =  '.$id_color.' )';
          }  

          elseif  (($id_descripcion!="0") AND ($id_descripcion!="") AND ($id_descripcion!= null)) {
              $where .= ' AND ( p.descripcion  =  "'.$id_descripcion.'" )';
              $where_cond  = '( p.descripcion  =  "'.$id_descripcion.'" )';
          }            
        
    
          

          $this->db->where($where);

          //$this->db->order_by($columna, $order); 

          $this->db->group_by("p.referencia,p.descripcion,p.id_composicion,p.id_color,p.id_calidad");
          
          $this->db->having('(cantidad_royo>0)');
          $where_total = '(cantidad_royo>0)';          
 
         



/*         $registros = $this->db->get();  


          if ($registros->num_rows() > 0) {
              return $registros->result(); 
          }    
          else
              return false;
          $registros->free_result();

          
*/

          $result = $this->db->get();


          $objeto = $result->result();

          //copiar a tabla "registros"
          foreach ($objeto as $key => $value) {
              $this->db->insert($this->conteo_almacen, $value); 
          }



          //actualizar (consecutivo) en tabla "operacion" 
          
          $this->db->set( 'consecutivo', 'consecutivo+1', FALSE  );
          $this->db->set( 'id_usuario', $id_session );
          $this->db->where('id',50);
          $this->db->update($this->operaciones);


          return true;

      }  



      


public function buscador_costos($data){

          $cadena = addslashes($data['search']['value']);
          $inicio = $data['start'];
          $largo = $data['length'];
          

          $fecha_hoy = date('Y-m-d H:i:s');  
          $id_almacen= $data['id_almacen'];
          $id_session = $this->session->userdata('id');


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
                        $columna = 'p.imagen'; 
                     break;
                   case '3':
                        $columna = 'c.color';
                     break;
                   case '4':
                              $columna= 'co.composicion';
                     break;
                   case '5':
                              $columna= 'ca.calidad';
                     break;
                   default:
                       $columna = 'p.referencia';
                       $order = 'DESC';                       
                     break;
                 }           


          $id_session = $this->db->escape($this->session->userdata('id'));

          $this->db->select("SQL_CALC_FOUND_ROWS *", FALSE); 

          
          $this->db->select("p.id,p.consecutivo, p.codigo_contable,p.grupo,p.referencia");    
          $this->db->select('p.imagen');
          $this->db->select('p.descripcion');
          $this->db->select('p.id_composicion,p.id_color,p.id_calidad, p.cantidad_royo');
          $this->db->select("p.id_almacen, p.fecha_creacion, p.id_usuario");
          $this->db->select('c.hexadecimal_color,c.color nombre_color');
          $this->db->select("co.composicion", FALSE);  
          $this->db->select("ca.calidad", FALSE);  
          $this->db->select("p.conteo1,p.conteo2,p.conteo3,p.num_conteo");  
         
          
          $id_almacenid = ' AND (p.id_almacen =  '.$id_almacen.' )' ;  
          
          $this->db->from($this->conteo_almacen.' as p');
          $this->db->join($this->almacenes.' As a', 'a.id = p.id_almacen','LEFT');
          $this->db->join($this->colores.' As c', 'p.id_color = c.id','LEFT');
          $this->db->join($this->composiciones.' As co', 'p.id_composicion = co.id','LEFT');
          $this->db->join($this->calidades.' As ca', 'p.id_calidad = ca.id','LEFT');

          $where = '(
                      
                      (
                        ( p.referencia LIKE  "%'.$cadena.'%" ) OR 
                        (p.descripcion LIKE  "%'.$cadena.'%") OR 
                        (c.color LIKE  "%'.$cadena.'%") OR
                        (co.composicion LIKE  "%'.$cadena.'%")  OR
                        ( ca.calidad LIKE  "%'.$cadena.'%" ) 
                       )'.$id_almacenid.'

            ) ' ; 



          $this->db->where($where);

          $this->db->order_by($columna, $order); 

          $this->db->group_by("p.referencia,p.descripcion,p.id_composicion,p.id_color,p.id_calidad");

          $this->db->limit($largo,$inicio); 


          $result = $this->db->get();

              if ( $result->num_rows() > 0 ) {

                    $cantidad_consulta = $this->db->query("SELECT FOUND_ROWS() as cantidad");
                    $found_rows = $cantidad_consulta->row(); 
                    $registros_filtrados =  ( (int) $found_rows->cantidad);


                  foreach ($result->result() as $row) {
                        $nombre_fichero ='uploads/productos/thumbnail/300X300/'.substr($row->imagen,0,strrpos($row->imagen,".")).'_thumb'.substr($row->imagen,strrpos($row->imagen,"."));
                        if (file_exists($nombre_fichero)) {
                            $imagen ='<img src="'.base_url().$nombre_fichero.'" border="0" width="75" height="75">';

                        } else {
                            $imagen ='<img src="img/sinimagen.png" border="0" width="75" height="75">';
                        }

                           $dato[]= array(
                                      0=>$row->referencia, 
                                      1=>$row->descripcion,
                                      2=> $imagen,
                                      3=>$row->nombre_color.                                      
                                        '<div style="background-color:#'.$row->hexadecimal_color.';display:block;width:15px;height:15px;margin:0 auto;"></div>',
                                      4=>$row->composicion,
                                      5=>$row->calidad,
                                      6=>$row->cantidad_royo,
                                      7=>$row->conteo1,
                                      8=>$row->conteo2,
                                      9=>$row->conteo3,
                                      10=>$row->id,
                                      11=>$row->num_conteo,
                                      
                                      


                                    );                    

                      }

                      return json_encode ( array(
                        "draw"            => intval( $data['draw'] ),
                        "recordsTotal"    => intval( self::total_conteo($where) ),  
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



        public function total_conteo($where){
              $this->db->from($this->conteo_almacen.' as p');
              $this->db->join($this->almacenes.' As a', 'a.id = p.id_almacen','LEFT');
              $this->db->join($this->colores.' As c', 'p.id_color = c.id','LEFT');
              $this->db->join($this->composiciones.' As co', 'p.id_composicion = co.id','LEFT');
              $this->db->join($this->calidades.' As ca', 'p.id_calidad = ca.id','LEFT');

              $this->db->where($where);
              $cant = $this->db->count_all_results();          
     
              if ( $cant > 0 )
                 return $cant;
              else
                 return 0;     
       }
////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////    


    //cuando se elimina un diseño en particular
    public function entradas($data){

       $id_almacen = $data['id_almacen'];
       
       //$this->db->select('m.id_usuario, m.id_almacen, a.almacen, us.nombre'); 
       $this->db->select('us.nombre'); 
       $this->db->from($this->registros_temporales.' As m');
       $this->db->join($this->almacenes.' As a' , 'a.id = m.id_almacen AND a.activo=1');
       $this->db->join($this->usuarios.' As us' , 'us.id = m.id_usuario','LEFT');
       $this->db->where('m.id_almacen',$id_almacen);
       $this->db->group_by("m.id_usuario");

      $registros = $this->db->get();  
      if ($registros->num_rows() > 0) {
          return $registros->result(); 
      }    
      else
          return false;
      $registros->free_result();

    }

    public function eliminar_prod_temporal( $data ){
            $this->db->delete( $this->registros_temporales, array( 'id_almacen' => $data['id_almacen'] ) );
            if ( $this->db->affected_rows() > 0 ) return TRUE;
            else return FALSE;
   }


 /////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////

    public function pedidos($data){

       $id_almacen = $data['id_almacen'];
       
       //$this->db->select('m.id_usuario_apartado, m.id_almacen, a.almacen, us.nombre'); 
       $this->db->select('us.nombre'); 
       $this->db->from($this->registros_entradas.' As m');
       $this->db->join($this->almacenes.' As a' , 'a.id = m.id_almacen AND a.activo=1');
       $this->db->join($this->usuarios.' As us' , 'us.id = m.id_usuario_apartado','LEFT');
       
       //vendedores 1,2,3
       //pedidos internos 4,5,6
      $where = '(
                        ( m.id_apartado <> 0 ) AND ( m.id_almacen ='.$id_almacen.')
            )';

      $this->db->where($where);      

      $this->db->group_by("m.id_usuario_apartado");

      $registros = $this->db->get();  
      if ($registros->num_rows() > 0) {
          return $registros->result(); 
      }    
      else
          return false;
      $registros->free_result();

    }



        
        //cancelar todos los pedidos y apartados de un almacen especifico
        public function cancelar_pedido_detalle( $data ){
                $id_almacen = $data['id_almacen'];

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
                                      ( m.id_apartado <> 0 ) AND ( m.id_almacen ='.$id_almacen.')
                          )';


                $this->db->where($where);                

                $this->db->update($this->registros );

                if ($this->db->affected_rows() > 0) {
                  return TRUE;
                }  else
                   return FALSE;
       
        }   




 /////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////DEVOLUCIONES////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////



   public function devoluciones($data){

       $id_almacen = $data['id_almacen'];
       
       //$this->db->select('m.id_user_devolucion, m.id_almacen, a.almacen, us.nombre'); 
       $this->db->select('us.nombre'); 
       $this->db->from($this->historico_registros_salidas.' as m');
       $this->db->join($this->almacenes.' As a' , 'a.id = m.id_almacen AND a.activo=1');
       $this->db->join($this->usuarios.' As us' , 'us.id = m.id_user_devolucion','LEFT');
       
       //vendedores 1,2,3
       //pedidos internos 4,5,6
      $where = '(
                        ( m.devolucion = 1  )  AND ( m.id_almacen ='.$id_almacen.')
            )';

      $this->db->where($where);      

      $this->db->group_by("m.id_user_devolucion");

      $registros = $this->db->get();  
      if ($registros->num_rows() > 0) {
          return $registros->result(); 
      }    
      else
          return false;
      $registros->free_result();

    }


    //cancelar todas las devoluciones de un almacen especifico
  public function quitar_producto_devolucion( $data ){
              $id_almacen = $data['id_almacen'];

              $this->db->set( 'id_user_devolucion', '');
              $this->db->set( 'devolucion', 0);
              $this->db->set( 'cod_devolucion', '');
              $this->db->set( 'conse_devolucion', '');
              $this->db->set( 'peso_real_devolucion', 0);  //poner a cero el  peso_real_devolucion
              $this->db->set( 'consecutivo_cambio', '0',false);
              $this->db->set( 'comentario', '');
              
              $where = '(
                        ( m.devolucion = 1  )  AND ( m.id_almacen ='.$id_almacen.')
              )';

              $this->db->where($where);
              $this->db->update($this->historico_registros_salidas);


            if ($this->db->affected_rows() > 0){
                    return TRUE;
                } else {
                    return FALSE;
                }
                $result->free_result();
        }  




 /////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////traspasos////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////



   public function traspasos($data){

       $id_almacen = $data['id_almacen'];
       
       //$this->db->select('m.id_usuario_traspaso, m.id_almacen, a.almacen, us.nombre'); 
       $this->db->select('us.nombre'); 
       $this->db->from($this->registros_entradas.' as m');
       $this->db->join($this->almacenes.' As a' , 'a.id = m.id_almacen AND a.activo=1');
       $this->db->join($this->usuarios.' As us' , 'us.id = m.id_usuario_traspaso','LEFT');
       
       //vendedores 1,2,3
       //pedidos internos 4,5,6
      $where = '(
                        ( ( incluir =  1 ) AND (proceso_traspaso = 1))  AND ( m.id_almacen ='.$id_almacen.') AND ( m.estatus_salida = "0" )
            )';

      $this->db->where($where);      

      $this->db->group_by("m.id_usuario_traspaso");

      $registros = $this->db->get();  
      if ($registros->num_rows() > 0) {
          return $registros->result(); 
      }    
      else
          return false;
      $registros->free_result();

    }


      public function quitar_productos_traspasado( $data ){
                $id_almacen = $data['id_almacen'];

                $porciento_aplicar = 16;                 
                $this->db->set( 'num_control', '');
                $this->db->set( 'comentario_traspaso', '');
                $this->db->set( 'id_usuario_traspaso', '');


                $this->db->set( 'iva', '((id_factura_original = 1)*'.$porciento_aplicar.')', false);
                $this->db->set( 'incluir', 0);
                $this->db->set( 'proceso_traspaso', 0);

                $this->db->set( 'id_factura', 'id_factura_original', false);
                $this->db->set( 'id_factura_original', 0, false);

                $where = '(
                                  ( ( incluir =  1 ) AND (proceso_traspaso = 1))  AND ( m.id_almacen ='.$id_almacen.') AND ( m.estatus_salida = "0" )
                )';

                $this->db->where($where);               

                $this->db->update($this->registros );

                if ($this->db->affected_rows() > 0) {
                  return TRUE;
                }  else
                   return FALSE;                

        }      






    
  } 


?>
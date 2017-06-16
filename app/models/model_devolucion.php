<?php if(! defined('BASEPATH')) exit('No tienes permiso para acceder a este archivo');

  class model_devolucion extends CI_Model {
    
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
      $this->registros_entradas               = $this->db->dbprefix('registros_entradas');
      $this->registros_salidas       = $this->db->dbprefix('registros_salidas');

      $this->colores                 = $this->db->dbprefix('catalogo_colores');
      $this->unidades_medidas        = $this->db->dbprefix('catalogo_unidades_medidas');
      
      $this->historico_registros_entradas = $this->db->dbprefix('historico_registros_entradas');
      $this->historico_registros_salidas = $this->db->dbprefix('historico_registros_salidas');
      
      $this->composiciones     = $this->db->dbprefix('catalogo_composicion');
      $this->calidades                 = $this->db->dbprefix('catalogo_calidad');

      
      $this->registros_cambios               = $this->db->dbprefix('registros_cambios');

      $this->almacenes             = $this->db->dbprefix('catalogo_almacenes');
      


    }





/////////////////////////////////////////////////////Verificar si el codigo existe en el historico////////////////////////////////////////////////

        public function existencia_temporales(){
            
              $id_session = $this->session->userdata('id');
              $cant=0;

              $this->db->from($this->historico_registros_salidas);

              $this->db->where('id_user_devolucion',$id_session);
              $this->db->where('devolucion',1);

              $cant = $this->db->count_all_results();          

              if ( $cant > 0 )
                 return true;
              else
                 return false;              

        }      


    public function check_existente_codigo($descripcion){
            $this->db->select("codigo", FALSE);         
            $this->db->from($this->historico_registros_salidas);
            

            $where = '(
                        (
                          ( codigo =  "'.addslashes($descripcion).'" ) 
                          
                         )

              )';   
  
            $this->db->where($where);            


            $this->db->where('id_user_devolucion','');  

/*

$where = '(
                        (
                          ( m.id_apartado = 0 ) AND  ( m.estatus_salida = "0" ) AND  ( m.devolucion != 2 ) 
                          AND  ( m.cod_devolucion = "" ) 
                        ) 
                         AND
                        (
                          ( m.codigo LIKE  "%'.$data['key'].'%" ) 
                         )

              )';  
*/




            
            $login = $this->db->get();
            if ($login->num_rows() > 0) {
                $fila = $login->row(); 
                return $fila->codigo;
            }    
            else
                return false;
            $login->free_result();
    } 




      //****************Poner en el historico un producto como devolucion************************************************************
  /*      

SELECT * 
FROM  `inven_historico_registros_salidas` 
WHERE codigo =  "QkVR48700103062016124459_2"

*/

        public function actualizar_producto_devolucion( $data ){

              $id_session = $this->session->userdata('id');
 
              $this->db->set( 'id_user_devolucion', $id_session);
              $this->db->set( 'devolucion', 1);
              
              if  (isset($data['cod_devolucion'])) {  
                $this->db->set( 'cod_devolucion', $data['cod_devolucion']);
              }  
              $this->db->set( 'conse_devolucion', $data['consecutivo']);
              $this->db->set( 'comentario', $data['comentario']);
              $this->db->set( 'peso_real_devolucion', $data['peso_real_devolucion']);



              $this->db->set( 'consecutivo_cambio', substr_count($data['codigo'], 'A')+1,false);

              
              $this->db->where('codigo',$data['codigo']);

              $this->db->update($this->historico_registros_salidas);


            if ($this->db->affected_rows() > 0){
                    return TRUE;
                } else {
                    return FALSE;
                }
                $result->free_result();
        }  


   //****************Poner en el historico un producto como devolucion************************************************************
        public function quitar_producto_devolucion( $data ){

              $id_session = $this->session->userdata('id');

              $this->db->set( 'id_user_devolucion', '');
              $this->db->set( 'devolucion', 0);
              $this->db->set( 'cod_devolucion', '');
              $this->db->set( 'conse_devolucion', '');

              $this->db->set( 'peso_real_devolucion', 0);  //poner a cero el  peso_real_devolucion

              $this->db->set( 'consecutivo_cambio', '0',false);

              

              $this->db->set( 'comentario', '');

             
              $this->db->where('codigo',$data['id']);

              $this->db->update($this->historico_registros_salidas);


            if ($this->db->affected_rows() > 0){
                    return TRUE;
                } else {
                    return FALSE;
                }
                $result->free_result();
        }  



///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function total_registros_devolucion(){

              $id_session = $this->session->userdata('id');
              $this->db->from($this->historico_registros_salidas.' as m');

              $where = '(
                          (
                            ( m.id_user_devolucion = "'.$id_session.'" )  AND ( m.devolucion != 0  ) 
                          ) 
                )';   
              $this->db->where($where);

              $cant = $this->db->count_all_results();          
     
              if ( $cant > 0 )
                 return $cant;
              else
                 return 0;         

    }


    public function buscador_devolucion($data){

          $cadena = addslashes($data['search']['value']);
          $inicio = $data['start'];
          $largo = $data['length'];
          //$codigo= $data['codigo'];
          //id_user_devolucion
          $id_session = $this->session->userdata('id');
          //print_r($id_session);

          $id_session = $this->db->escape($this->session->userdata('id'));

          $this->db->select("SQL_CALC_FOUND_ROWS(m.codigo)"); //

          $this->db->select('m.codigo');
          $this->db->select('m.num_partida');
          $this->db->select('m.referencia');
          $this->db->select('m.id_almacen');
          $this->db->select('m.peso_real,m.peso_real_devolucion');
          $this->db->select('m.id_descripcion,c.color,c.hexadecimal_color, m.id_color, co.composicion, ca.calidad');
          
          $this->db->select('m.movimiento, m.fecha_entrada, p.nombre proveedor, m.factura, m.cantidad_um');
          $this->db->select('m.id_medida, m.ancho, m.id_estatus, m.id_lote, m.id,consecutivo_cambio,u.medida,m.comentario ');
          $this->db->select('m.cod_devolucion');

          $this->db->select("( CASE WHEN m.id_medida = 1 THEN m.cantidad_um ELSE 0 END ) AS metros");
          $this->db->select("( CASE WHEN m.id_medida = 2 THEN m.cantidad_um ELSE 0 END ) AS kilogramos");
          $this->db->select("prod.codigo_contable");  


          $this->db->from($this->historico_registros_salidas.' as m');
          $this->db->join($this->productos.' As prod' , 'prod.referencia = m.referencia'); //,'LEFT'
          $this->db->join($this->colores.' As c' , 'c.id = m.id_color'); //,'LEFT'
          $this->db->join($this->composiciones.' As co' , 'co.id = m.id_composicion'); //,'LEFT'
          $this->db->join($this->calidades.' As ca' , 'ca.id = m.id_calidad'); //,'LEFT'
          $this->db->join($this->proveedores.' As p' , 'p.id = m.id_empresa'); //,'LEFT'
          $this->db->join($this->unidades_medidas.' As u' , 'u.id = m.id_medida'); //,'LEFT'
         
          //filtro de busqueda
          //( m.id_user_devolucion = '.$id_session.' )  AND ( m.devolucion != 0  ) 
          $where = '(
                      (
                        ( m.id_user_devolucion = '.$id_session.' )  AND ( m.devolucion = 1  ) 
                      ) 
            )';   
          $where_total= $where;
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
                                      8=>$row->cod_devolucion,
                                      9=>$row->num_partida,
                                      10=>$row->metros,
                                      11=>$row->kilogramos,       
                                      12=>$row->peso_real,    
                                      13=>$row->peso_real_devolucion,   
                                      14=>$row->id_almacen,  
                                      15=>$row->codigo_contable,   
                                      16=>$row->id_estatus
                                                                     


                                      
                                    );
                      }



                      return json_encode ( array(
                        "draw"            => intval( $data['draw'] ),
                        "recordsTotal"    => $registros_filtrados,// intval( self::total_registros_devolucion() ),  //$recordsTotal
                        "recordsFiltered" => $registros_filtrados, //intval( $result->num_rows() ),   //$recordsFiltered
                        "data"            =>  $dato, //self::data_output( $columns, $data )
                        "totales"            =>  array("pieza"=>intval( self::totales_campos_salida($where_total)->pieza ), "metro"=>floatval( self::totales_campos_salida($where_total)->metros ), "kilogramo"=>floatval( self::totales_campos_salida($where_total)->kilogramos ),"peso"=>floatval( self::totales_campos_salida($where_total)->peso )),  
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
                  "totales"            =>  array("pieza"=>intval( self::totales_campos_salida($where_total)->pieza ), "metro"=>floatval( self::totales_campos_salida($where_total)->metros ), "kilogramo"=>floatval( self::totales_campos_salida($where_total)->kilogramos ),"peso"=>floatval( self::totales_campos_salida($where_total)->peso )),  
                  );
                  $array[]="";
                  return json_encode($output);
                  

              }

              $result->free_result();           

      }  

 public function totales_campos_salida($where){

           $this->db->select("SUM((id_medida =1) * cantidad_um) as metros");
              $this->db->select("SUM((id_medida =2) * cantidad_um) as kilogramos");
              $this->db->select("COUNT(m.id_medida) as 'pieza'");
              $this->db->select("SUM(m.peso_real_devolucion) as 'peso'");  //se suma el precio_real_devolucion
              
             
              $this->db->from($this->historico_registros_salidas.' as m');
              $this->db->where($where);

             $result = $this->db->get();
          
              if ( $result->num_rows() > 0 )
                 return $result->row();
              else
                 return False;
              $result->free_result();              

       }  


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

   //procesando operaciones de devolucion
        public function procesando_operacion( ){

          $id_session = $this->session->userdata('id');
          $fecha_hoy = date('Y-m-d H:i:s');  

          $consecutivo = self::consecutivo_operacion(23); //cambio


           //actualizar a historico_registros_salidas el "conse_devolucion" al consecutivo q le toque
          $this->db->set('conse_devolucion', $consecutivo, FALSE  );
          $this->db->where('id_user_devolucion',$id_session);
          $this->db->where('devolucion',1);
          $this->db->update($this->historico_registros_salidas);

          //


          $this->db->select('"'.$id_session.'" as id_usuario', false);
          $this->db->select('"'.$fecha_hoy.'" AS fecha_entrada',false);
          $this->db->select('CONCAT("A",codigo) codigo',false); //anteponerle una A al codigo
          $this->db->select('1 id_operacion',false);
          $this->db->select('13 id_estatus',false);


          $this->db->select('conse_devolucion movimiento',false);
          $this->db->select('cod_devolucion factura',false);
          $this->db->select('devolucion');

          $this->db->select('num_partida');

             
          $this->db->select('id_empresa, id_descripcion, id_color, id_composicion, id_calidad, referencia');
          $this->db->select('id_medida, cantidad_um, cantidad_royo, ancho,   comentario,  id_lote, consecutivo, consecutivo_cambio'); //checar que es este consecutivo_cambio
          $this->db->select('id_cargador,  fecha_mac');
          
          //aqui  "peso_real = peso_real_devolucion"        
          $this->db->select('peso_real_devolucion peso_real',false);      

          $this->db->select('id_almacen');
          $this->db->select('precio, iva, id_factura,id_fac_orig');

          $this->db->from($this->historico_registros_salidas);

          $this->db->where('id_user_devolucion',$id_session);
          $this->db->where('devolucion',1);          

/*
          //no para que no regrese en forma de traspasos. ya el producto fue traspasado y debe de mantenerse en ese estatus, 
          //para cambiar ese estatus que lo haga de manera manual
          $this->db->select('id_pedido,  id_tipo_pedido,id_tipo_factura, id_factura_original,incluir,proceso_traspaso');

          //NO para que regresen sin apartados 
          $this->db->select('m.id_apartado, m.id_usuario_apartado, m.id_cliente_apartado,m.fecha_apartado,m.id_prorroga,m.consecutivo_venta');
*/



          $result = $this->db->get();


          $objeto = $result->result();

          //copiar a tabla "registros"
          foreach ($objeto as $key => $value) {
            
            $this->db->insert($this->historico_registros_entradas, $value); 
            $value->peso_real = 0;
            $this->db->insert($this->registros_entradas, $value); 
            $num_movimiento = $value->movimiento;  //OJO
          }


          //actualizar (consecutivo) en tabla "operacion" 
          
          $this->db->set( 'consecutivo', 'consecutivo+1', FALSE  );
          $this->db->set( 'id_usuario', $id_session );
          $this->db->where('id',23);
          $this->db->update($this->operaciones);
          

          //Actualizar los registros en historicos_salidas a  "2" indican q ya se usaron
          
          $this->db->set( 'devolucion', 2 );
          $this->db->where('id_user_devolucion',$id_session);
          $this->db->where('devolucion',1);
          $this->db->update($this->historico_registros_salidas);          

          return $num_movimiento;

          $result->free_result();          

        }


         //listado de la regilla
        public function listado_movimientos_registros($data){

          $id_session = $this->session->userdata('id');
                    
          $this->db->select('m.id, m.movimiento,m.id_empresa, m.factura, m.id_descripcion, m.id_operacion,m.devolucion, m.num_partida');
          $this->db->select('m.id_color, m.id_composicion, m.id_calidad, m.referencia');
          $this->db->select('m.id_medida, m.cantidad_um,m.peso_real, m.cantidad_royo, m.ancho, m.precio, m.codigo, m.comentario');
          $this->db->select('m.id_estatus, m.id_lote, m.consecutivo, m.id_cargador, m.id_usuario, m.fecha_mac fecha');
          $this->db->select('DATE_FORMAT((m.fecha_mac),"%d-%m-%Y %H:%i") as fecha2', false);
          $this->db->select("( CASE WHEN m.devolucion <> 0 THEN 'red' ELSE 'black' END ) AS color_devolucion", FALSE);
          

          $this->db->select('c.hexadecimal_color, u.medida,p.nombre');

          $this->db->select('a.almacen');

          $this->db->select('(m.precio*m.cantidad_um) as sum_precio'); //sum           
          $this->db->select("(m.precio*m.cantidad_um*m.iva)/100 as sum_iva", FALSE); //sum
          $this->db->select("(m.precio*m.cantidad_um)+(((m.precio*m.cantidad_um*m.iva))/100) as sum_total", FALSE); //sum

          
          $this->db->select("prod.codigo_contable");  
          
          //$this->db->from($this->registros_entradas.' as m');
          $this->db->from($this->historico_registros_entradas.' as m');
          $this->db->join($this->almacenes.' As a' , 'a.id = m.id_almacen AND a.activo=1');
          $this->db->join($this->productos.' As prod' , 'prod.referencia = m.referencia','LEFT');
          $this->db->join($this->colores.' As c' , 'c.id = m.id_color','LEFT');
          $this->db->join($this->unidades_medidas.' As u' , 'u.id = m.id_medida','LEFT');
          $this->db->join($this->proveedores.' As p' , 'p.id = m.id_empresa','LEFT');
          

          //$this->db->where('m.id_usuario',$id_session);
          //$this->db->where('m.id_operacion',1);
          //$this->db->where('m.movimiento',$data['num_mov']);

          $where = '(
                      (
                        ( m.devolucion = 1 ) AND ( m.movimiento = '.$data['num_mov'].' ) AND ( m.id_operacion = 1 )
                      ) 

            )';   


          $this->db->where($where);          

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



  } 






?>

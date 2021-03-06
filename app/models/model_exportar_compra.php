<?php if(! defined('BASEPATH')) exit('No tienes permiso para acceder a este archivo');

  class model_exportar_compra extends CI_Model {
    
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
      $this->historico_pedido_compra        = $this->db->dbprefix('historico_pedido_compra');
      $this->historico_cancela_pedido_compra      = $this->db->dbprefix('historico_cancela_pedido_compra');
      $this->historico_historial_compra      = $this->db->dbprefix('historico_historial_compra');

    }




public function buscador_revisar_historial_compra($data){

          $cadena = addslashes($data['busqueda']);
          

          $id_almacen= $data['id_almacen'];
          $movimiento= $data['movimiento'];
          
           $perfil= $this->session->userdata('id_perfil');           
          $id_session = $this->session->userdata('id');
          



          $this->db->select("(DATE_FORMAT(pc.fecha_entrada,'%d-%m-%Y')) as fecha_entrada",false);
          $this->db->select('pc.movimiento, pc.comentario,a.almacen');
          $this->db->select('p.descripcion,p.codigo_contable, p.imagen,c.color nombre_color');
          $this->db->select("pc.ancho", FALSE);
          $this->db->select('co.composicion, ca.calidad');
          
          if (($this->session->userdata('id_perfil')==1) || ( (in_array(80, $data['coleccion_id_operaciones'])) || (in_array(81, $data['coleccion_id_operaciones']))   ) ) { 
            $this->db->select("pc.precio", FALSE);  
          }
          

          //$this->db->select("p.minimo");
          $this->db->select("( CASE WHEN pc.id_medida = 1 THEN p.minimo ELSE p.minimo_kg END ) AS minimo", FALSE);
          $this->db->select("COUNT(m.referencia) as 'suma'");
          $this->db->select("pc.cantidad_pedida as cantidad_solicitada");
          $this->db->select("pc.cantidad_aprobada as cantidad_aprobada");
          $this->db->select('prov.nombre proveedor');

           if ($id_almacen!=0) {
              $id_almacenid = ' and ( pc.id_almacen =  '.$id_almacen.' ) ';  
              $id_almacenidid = ' and ( m.id_almacen =  '.$id_almacen.' ) ';  
              
            } else {
              $id_almacenid = '';
              $id_almacenidid = '';
            }   

           $this->db->select('med.medida'); 
            
          $this->db->from($this->productos.' as p');
          $this->db->join($this->colores.' As c', 'p.id_color = c.id','LEFT');
          $this->db->join($this->composiciones.' As co', 'p.id_composicion = co.id','LEFT');
          $this->db->join($this->calidades.' As ca', 'p.id_calidad = ca.id','LEFT');
          $this->db->join($this->historico_historial_compra.' As pc', 'pc.id_producto = p.id','LEFT');
          $this->db->join($this->almacenes.' As a', 'a.id = pc.id_almacen'.$id_almacenid,'LEFT');
          $this->db->join($this->registros.' As m', 'm.referencia= p.referencia'.$id_almacenidid,'LEFT');
          $this->db->join($this->proveedores.' As prov', 'prov.id= pc.id_proveedor');
          $this->db->join($this->unidades_medidas.' As med', 'med.id= pc.id_medida','LEFT');
          

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


          $this->db->where($where);

          $this->db->group_by("p.referencia,pc.id_medida");

          //$this->db->order_by($columna, $order); 
    
            $result = $this->db->get();


            if ( $result->num_rows() > 0 )
               return $result->result();
            else
               return False;
            $result->free_result();        

      }  



 public function buscador_revisar_cancela_compra($data){

          $cadena = addslashes($data['busqueda']);
          $id_almacen= $data['id_almacen'];
          $movimiento= $data['movimiento'];
          $perfil= $this->session->userdata('id_perfil');
          
          
             
          $id_session = $this->session->userdata('id');
          
          $this->db->select("(DATE_FORMAT(pc.fecha_entrada,'%d-%m-%Y')) as fecha_entrada",false);
          $this->db->select('pc.movimiento, pc.comentario,a.almacen');
          $this->db->select('p.descripcion,p.codigo_contable, p.imagen,c.color nombre_color');
          $this->db->select("pc.ancho", FALSE);
          $this->db->select('co.composicion, ca.calidad');
          
          if (($this->session->userdata('id_perfil')==1) || ( (in_array(80, $data['coleccion_id_operaciones'])) || (in_array(81, $data['coleccion_id_operaciones']))   ) ) { 
            $this->db->select("pc.precio", FALSE);  
          }
          
          //$this->db->select("p.minimo");
          $this->db->select("( CASE WHEN pc.id_medida = 1 THEN p.minimo ELSE p.minimo_kg END ) AS minimo", FALSE);

          $this->db->select("COUNT(m.referencia) as 'suma'");
          $this->db->select("pc.cantidad_pedida as cantidad_solicitada");
          $this->db->select("pc.cantidad_aprobada as cantidad_aprobada");
          $this->db->select('prov.nombre proveedor');



           if ($id_almacen!=0) {
              $id_almacenid = ' and ( pc.id_almacen =  '.$id_almacen.' ) ';  
              $id_almacenidid = ' and ( m.id_almacen =  '.$id_almacen.' ) ';  
              
            } else {
              $id_almacenid = '';
              $id_almacenidid = '';
            }   

            $this->db->select('med.medida');

          $this->db->from($this->productos.' as p');
          $this->db->join($this->colores.' As c', 'p.id_color = c.id','LEFT');
          $this->db->join($this->composiciones.' As co', 'p.id_composicion = co.id','LEFT');
          $this->db->join($this->calidades.' As ca', 'p.id_calidad = ca.id','LEFT');
          $this->db->join($this->historico_cancela_pedido_compra.' As pc', 'pc.id_producto = p.id','LEFT');
          $this->db->join($this->almacenes.' As a', 'a.id = pc.id_almacen'.$id_almacenid,'LEFT');
          $this->db->join($this->registros.' As m', 'm.referencia= p.referencia'.$id_almacenidid,'LEFT');
          $this->db->join($this->proveedores.' As prov', 'prov.id= pc.id_proveedor');
          $this->db->join($this->unidades_medidas.' As med', 'med.id= pc.id_medida','LEFT');
          

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


          $this->db->where($where);

          $this->db->group_by("p.referencia,pc.id_medida");

          $result = $this->db->get();


            if ( $result->num_rows() > 0 )
               return $result->result();
            else
               return False;
            $result->free_result();   
       }  






 public function buscador_revisar_pedido_compra($data){

          $cadena = addslashes($data['busqueda']);
          $id_almacen= $data['id_almacen'];
          $movimiento= $data['movimiento'];
          
          $perfil= $this->session->userdata('id_perfil');
             
          $id_session = $this->session->userdata('id');
          
          $this->db->select("(DATE_FORMAT(pc.fecha_entrada,'%d-%m-%Y')) as fecha_entrada",false);
          $this->db->select('pc.movimiento, pc.comentario,a.almacen');
          $this->db->select('p.descripcion,p.codigo_contable, p.imagen,c.color nombre_color');
          $this->db->select("pc.ancho", FALSE);
          $this->db->select('co.composicion, ca.calidad');
          
          if (($this->session->userdata('id_perfil')==1) || ( (in_array(80, $data['coleccion_id_operaciones'])) || (in_array(81, $data['coleccion_id_operaciones']))   ) ) { 
            $this->db->select("pc.precio", FALSE);  
          }
          

          //$this->db->select("p.minimo");
          $this->db->select("( CASE WHEN pc.id_medida = 1 THEN p.minimo ELSE p.minimo_kg END ) AS minimo", FALSE);
          $this->db->select("COUNT(m.referencia) as 'suma'");
          $this->db->select("pc.cantidad_pedida as cantidad_solicitada");
          $this->db->select("pc.cantidad_aprobada as cantidad_aprobada");
          $this->db->select('prov.nombre proveedor');


         

           if ($id_almacen!=0) {
              $id_almacenid = ' and ( pc.id_almacen =  '.$id_almacen.' ) ';  
              $id_almacenidid = ' and ( m.id_almacen =  '.$id_almacen.' ) ';  
              
            } else {
              $id_almacenid = '';
              $id_almacenidid = '';
            }   

            $this->db->select('med.medida'); 

          $this->db->from($this->productos.' as p');
          $this->db->join($this->colores.' As c', 'p.id_color = c.id','LEFT');
          $this->db->join($this->composiciones.' As co', 'p.id_composicion = co.id','LEFT');
          $this->db->join($this->calidades.' As ca', 'p.id_calidad = ca.id','LEFT');
          $this->db->join($this->historico_pedido_compra.' As pc', 'pc.id_producto = p.id','LEFT');
          $this->db->join($this->almacenes.' As a', 'a.id = pc.id_almacen'.$id_almacenid,'LEFT');
          $this->db->join($this->registros.' As m', 'm.referencia= p.referencia'.$id_almacenidid,'LEFT');
          $this->db->join($this->proveedores.' As prov', 'prov.id= pc.id_proveedor');
          $this->db->join($this->unidades_medidas.' As med', 'med.id= pc.id_medida','LEFT');

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


      
          $this->db->where($where);

          $this->db->group_by("p.referencia,pc.id_medida");

          $result = $this->db->get();


            if ( $result->num_rows() > 0 )
               return $result->result();
            else
               return False;
            $result->free_result();   



      }  



  } 
?>

<?php if(! defined('BASEPATH')) exit('No tienes permiso para acceder a este archivo');

	class model_entrada_compra extends CI_Model {
		
		private $key_hash;
		private $timezone;

		function __construct(){

			parent::__construct();
			$this->load->database("default");
			$this->key_hash    = $_SERVER['HASH_ENCRYPT'];
			

      $this->timezone    = 'UTC';
      date_default_timezone_set('America/Mexico_City'); 

      
				//usuarios
			$this->usuarios    = $this->db->dbprefix('usuarios');
				//catalogos			
			$this->actividad_comercial     = $this->db->dbprefix('catalogo_actividad_comercial');
      
      $this->estratificacion_empresa = $this->db->dbprefix('catalogo_estratificacion_empresa');
      
      $this->productos               = $this->db->dbprefix('catalogo_productos');
      $this->proveedores             = $this->db->dbprefix('catalogo_empresas');
      $this->unidades_medidas        = $this->db->dbprefix('catalogo_unidades_medidas');

      $this->operaciones             = $this->db->dbprefix('catalogo_operaciones');
      $this->movimientos               = $this->db->dbprefix('movimientos');
      $this->registros_temporales               = $this->db->dbprefix('temporal_registros');
      $this->registros               = $this->db->dbprefix('registros_entradas');
      $this->registros_cambios               = $this->db->dbprefix('registros_cambios');

      $this->colores                 = $this->db->dbprefix('catalogo_colores');
      $this->unidades_medidas        = $this->db->dbprefix('catalogo_unidades_medidas');
      $this->historico_registros_entradas        = $this->db->dbprefix('historico_registros_entradas');

      $this->almacenes                 = $this->db->dbprefix('catalogo_almacenes');
      $this->catalogo_configuraciones  = $this->db->dbprefix('catalogo_configuraciones');

      $this->catalogo_tipos_pagos  = $this->db->dbprefix('catalogo_tipos_pagos');

      $this->historico_pagos_realizados        = $this->db->dbprefix('historico_pagos_realizados');
      $this->historico_ctasxpagar        = $this->db->dbprefix('historico_ctasxpagar');


      $this->historico_historial_compra      = $this->db->dbprefix('historico_historial_compra');
      $this->tipos_facturas                         = $this->db->dbprefix('catalogo_tipos_facturas');


      //catalogos
      $this->productos                      = $this->db->dbprefix('catalogo_productos');
      $this->colores                        = $this->db->dbprefix('catalogo_colores');
      $this->composiciones                  = $this->db->dbprefix('catalogo_composicion');
      $this->calidades                      = $this->db->dbprefix('catalogo_calidad');
		}


    //********************Dependencia


/*

SELECT `p`.`descripcion` nombre, `p`.`id`, 
  CASE WHEN (sum(m.cantidad_um*(m.id_medida=1)) is null) THEN 0 Else sum(m.cantidad_um*(m.id_medida=1)) END AS total_entrada_mts,
  CASE WHEN (sum(m.cantidad_um*(m.id_medida=2)) is null) THEN 0 Else sum(m.cantidad_um*(m.id_medida=2)) END AS total_entrada_kg, 
  CASE WHEN (sum(t.cantidad_um*(t.id_medida=1)) is null) THEN 0 Else sum(t.cantidad_um*(t.id_medida=1)) END AS total_entrada_temp_mts, 
  CASE WHEN (sum(t.cantidad_um*(t.id_medida=2)) is null) THEN 0 Else sum(t.cantidad_um*(t.id_medida=2)) END AS total_entrada_temp_kg, 
  max(pc.cantidad_aprobada*(pc.id_medida=1)) total_compra_mts, 
  max(pc.cantidad_aprobada*(pc.id_medida=2)) total_compra_kg 

FROM (`inven_catalogo_productos` as p) 
  JOIN `inven_historico_historial_compra` As pc ON `p`.`referencia` = `pc`.`referencia` 
  LEFT JOIN `inven_historico_registros_entradas` As m ON `pc`.`referencia` = `m`.`referencia` AND pc.movimiento=m.id_compra AND pc.id_medida=m.id_medida 
  LEFT JOIN `inven_temporal_registros` As t ON `pc`.`referencia` = `t`.`referencia` AND pc.movimiento=t.id_compra AND pc.id_medida=t.id_medida 

WHERE `p`.`activo` = 0 AND `pc`.`movimiento` = 2 

GROUP BY `p`.`descripcion`, `p`.`referencia`




///////////////


SELECT `p`.`descripcion` nombre, `p`.`id`, 
  m.cantidad_um, m.id_medida,
  t.cantidad_um, t.id_medida,
  pc.cantidad_aprobada,pc.id_medida,
  pc.referencia

FROM (`inven_catalogo_productos` as p) 
  JOIN `inven_historico_historial_compra` As pc ON `p`.`referencia` = `pc`.`referencia` 
  LEFT JOIN `inven_historico_registros_entradas` As m ON `pc`.`referencia` = `m`.`referencia` AND pc.movimiento=m.id_compra AND pc.id_medida=m.id_medida 
  LEFT JOIN `inven_temporal_registros` As t ON `pc`.`referencia` = `t`.`referencia` AND pc.movimiento=t.id_compra AND pc.id_medida=t.id_medida 

WHERE `p`.`activo` = 0 AND `pc`.`movimiento` = 2 

GROUP BY `p`.`descripcion`, `p`.`referencia`




*/




       public function listado_productos($data){
        //$this->db->distinct();
          $this->db->select("p.descripcion nombre");  
          $this->db->select("p.id");   //m.cantidad_royo*

          $this->db->select("CASE WHEN (sum(m.cantidad_um*(m.id_medida=1)) is null)  THEN 0 Else sum(m.cantidad_um*(m.id_medida=1)) END AS  total_entrada_mts",false);  
            $this->db->select("CASE WHEN (sum(m.cantidad_um*(m.id_medida=2)) is null)  THEN 0 Else sum(m.cantidad_um*(m.id_medida=2)) END AS  total_entrada_kg",false);  



          $this->db->select("CASE WHEN (sum(t.cantidad_um*(t.id_medida=1)) is null)  THEN 0 Else sum(t.cantidad_um*(t.id_medida=1)) END AS  total_entrada_temp_mts",false);  
            $this->db->select("CASE WHEN (sum(t.cantidad_um*(t.id_medida=2)) is null)  THEN 0 Else sum(t.cantidad_um*(t.id_medida=2)) END AS  total_entrada_temp_kg",false);  


            $this->db->select("max(pc.cantidad_aprobada*(pc.id_medida=1)) total_compra_mts",false);  
            $this->db->select("max(pc.cantidad_aprobada*(pc.id_medida=2)) total_compra_kg",false);  
          



          $this->db->from($this->productos.' as p');
          $this->db->join($this->historico_historial_compra.' As pc' , 'p.referencia = pc.referencia'); //,'LEFT'
          $this->db->join($this->historico_registros_entradas.' As m' , 'pc.referencia = m.referencia AND pc.movimiento=m.id_compra AND pc.id_medida=m.id_medida','LEFT'); 
          $this->db->join($this->registros_temporales.' As t' , 'pc.referencia = t.referencia AND pc.movimiento=t.id_compra AND pc.id_medida=t.id_medida','LEFT'); 
          
          $this->db->where('p.activo',0);
          $this->db->where('pc.movimiento',$data['id_compra']);

          $this->db->group_by("p.descripcion, p.referencia"); //,pc.id_medida
          $this->db->having("(total_compra_mts > (total_entrada_mts+total_entrada_temp_mts)) OR (total_compra_kg > (total_entrada_kg+total_entrada_temp_kg))");
          
          
          //$result = $this->db->get();

          $this->db->get();
          $consulta = $this->db->last_query();
          $result = $this->db->query('select * from ('.$consulta.') as tabla group by nombre order by nombre');



            if ( $result->num_rows() > 0 )
               return $result->result();
            else
               return False;
            $result->free_result();
        }     



         public function lista_colores($data){

            //$this->db->distinct();
            $this->db->select("c.color nombre", FALSE);  
            $this->db->select("c.id", FALSE);  
            $this->db->select("c.hexadecimal_color", FALSE);  

            $this->db->select("CASE WHEN (sum(m.cantidad_um*(m.id_medida=1)) is null)  THEN 0 Else sum(m.cantidad_um*(m.id_medida=1)) END AS  total_entrada_mts",false);  
            $this->db->select("CASE WHEN (sum(m.cantidad_um*(m.id_medida=2)) is null)  THEN 0 Else sum(m.cantidad_um*(m.id_medida=2)) END AS  total_entrada_kg",false);  

                    $this->db->select("CASE WHEN (sum(t.cantidad_um*(t.id_medida=1)) is null)  THEN 0 Else sum(t.cantidad_um*(t.id_medida=1)) END AS  total_entrada_temp_mts",false);  
            $this->db->select("CASE WHEN (sum(t.cantidad_um*(t.id_medida=2)) is null)  THEN 0 Else sum(t.cantidad_um*(t.id_medida=2)) END AS  total_entrada_temp_kg",false); 


            $this->db->select("max(pc.cantidad_aprobada*(pc.id_medida=1)) total_compra_mts",false);  
            $this->db->select("max(pc.cantidad_aprobada*(pc.id_medida=2)) total_compra_kg",false);  
          

            
            $this->db->from($this->productos.' as p');
            $this->db->join($this->colores.' As c', 'p.id_color = c.id','LEFT');
            $this->db->join($this->historico_historial_compra.' As pc' , 'p.referencia = pc.referencia'); //,'LEFT'
            $this->db->join($this->historico_registros_entradas.' As m' , 'pc.referencia = m.referencia AND pc.movimiento=m.id_compra AND pc.id_medida=m.id_medida','LEFT'); 
            $this->db->join($this->registros_temporales.' As t' , 'pc.referencia = t.referencia AND pc.movimiento=t.id_compra AND pc.id_medida=t.id_medida','LEFT');

            $this->db->where('p.activo',0);
            $this->db->where('p.descripcion', ($data['val_prod']) );
            $this->db->where('pc.movimiento',$data['id_compra']);


            $this->db->group_by("c.color"); //,pc.id_medida
            //$this->db->having("(total_compra_mts > total_entrada_mts) OR (total_compra_kg > total_entrada_kg) ");
            $this->db->having("(total_compra_mts > (total_entrada_mts+total_entrada_temp_mts)) OR (total_compra_kg > (total_entrada_kg+total_entrada_temp_kg))");
          

            $this->db->order_by('c.color', 'asc'); 
            


            $result = $this->db->get();
            
            if ( $result->num_rows() > 0 )
               return $result->result();
            else
               return False;
            $result->free_result();
        }  


         public function lista_composiciones($data){
            //distinct

            //$this->db->distinct();

            $this->db->select("c.composicion nombre", FALSE);  
            $this->db->select("c.id", FALSE);  


            $this->db->select("CASE WHEN (sum(m.cantidad_um*(m.id_medida=1)) is null)  THEN 0 Else sum(m.cantidad_um*(m.id_medida=1)) END AS  total_entrada_mts",false);  
            $this->db->select("CASE WHEN (sum(m.cantidad_um*(m.id_medida=2)) is null)  THEN 0 Else sum(m.cantidad_um*(m.id_medida=2)) END AS  total_entrada_kg",false);  

                    $this->db->select("CASE WHEN (sum(t.cantidad_um*(t.id_medida=1)) is null)  THEN 0 Else sum(t.cantidad_um*(t.id_medida=1)) END AS  total_entrada_temp_mts",false);  
            $this->db->select("CASE WHEN (sum(t.cantidad_um*(t.id_medida=2)) is null)  THEN 0 Else sum(t.cantidad_um*(t.id_medida=2)) END AS  total_entrada_temp_kg",false); 

            $this->db->select("max(pc.cantidad_aprobada*(pc.id_medida=1)) total_compra_mts",false);  
            $this->db->select("max(pc.cantidad_aprobada*(pc.id_medida=2)) total_compra_kg",false);  

            $this->db->from($this->productos.' as p');
            $this->db->join($this->composiciones.' As c', 'p.id_composicion = c.id','LEFT');
            $this->db->join($this->historico_historial_compra.' As pc' , 'p.referencia = pc.referencia'); //,'LEFT'
            $this->db->join($this->historico_registros_entradas.' As m' , 'pc.referencia = m.referencia AND pc.movimiento=m.id_compra AND pc.id_medida=m.id_medida','LEFT'); 
            $this->db->join($this->registros_temporales.' As t' , 'pc.referencia = t.referencia AND pc.movimiento=t.id_compra AND pc.id_medida=t.id_medida','LEFT');
            
            $this->db->where('p.activo',0);
            $this->db->where('p.descripcion', ($data['val_prod']) );
            $this->db->where('p.id_color', $data['val_color']);
            $this->db->where('pc.movimiento',$data['id_compra']);

            $this->db->group_by("c.composicion"); //,pc.id_medida
            //$this->db->having("(total_compra_mts > total_entrada_mts) OR (total_compra_kg > total_entrada_kg) ");
            $this->db->having("(total_compra_mts > (total_entrada_mts+total_entrada_temp_mts)) OR (total_compra_kg > (total_entrada_kg+total_entrada_temp_kg))");
          

            $result = $this->db->get();
            
            if ( $result->num_rows() > 0 )
               return $result->result();
            else
               return False;
            $result->free_result();
        }   


         public function lista_calidad($data){
            //distinct
            //$this->db->distinct();

            $this->db->select("c.calidad nombre", FALSE);  
            $this->db->select("c.id", FALSE);  


             $this->db->select("CASE WHEN (sum(m.cantidad_um*(m.id_medida=1)) is null)  THEN 0 Else sum(m.cantidad_um*(m.id_medida=1)) END AS  total_entrada_mts",false);  
            $this->db->select("CASE WHEN (sum(m.cantidad_um*(m.id_medida=2)) is null)  THEN 0 Else sum(m.cantidad_um*(m.id_medida=2)) END AS  total_entrada_kg",false);  

                    $this->db->select("CASE WHEN (sum(t.cantidad_um*(t.id_medida=1)) is null)  THEN 0 Else sum(t.cantidad_um*(t.id_medida=1)) END AS  total_entrada_temp_mts",false);  
            $this->db->select("CASE WHEN (sum(t.cantidad_um*(t.id_medida=2)) is null)  THEN 0 Else sum(t.cantidad_um*(t.id_medida=2)) END AS  total_entrada_temp_kg",false); 

            $this->db->select("max(pc.cantidad_aprobada*(pc.id_medida=1)) total_compra_mts",false);  
            $this->db->select("max(pc.cantidad_aprobada*(pc.id_medida=2)) total_compra_kg",false);  



            $this->db->from($this->productos.' as p');
            $this->db->join($this->calidades.' As c', 'p.id_calidad = c.id','LEFT');
            $this->db->join($this->historico_historial_compra.' As pc' , 'p.referencia = pc.referencia'); //,'LEFT'
            $this->db->join($this->historico_registros_entradas.' As m' , 'pc.referencia = m.referencia AND pc.movimiento=m.id_compra AND pc.id_medida=m.id_medida','LEFT'); 
            $this->db->join($this->registros_temporales.' As t' , 'pc.referencia = t.referencia AND pc.movimiento=t.id_compra AND pc.id_medida=t.id_medida','LEFT');

            $this->db->where('p.activo',0);
            $this->db->where('p.descripcion', ($data['val_prod']) );
            $this->db->where('p.id_color', $data['val_color']);
            $this->db->where('p.id_composicion', $data['val_comp']);
            $this->db->where('pc.movimiento',$data['id_compra']);

            $this->db->group_by("c.calidad"); //,pc.id_medida
            //$this->db->having("(total_compra_mts > total_entrada_mts) OR (total_compra_kg > total_entrada_kg) ");            
            $this->db->having("(total_compra_mts > (total_entrada_mts+total_entrada_temp_mts)) OR (total_compra_kg > (total_entrada_kg+total_entrada_temp_kg))");
          

            $result = $this->db->get();
            
            if ( $result->num_rows() > 0 )
               return $result->result();
            else
               return False;
            $result->free_result();
            
        }    



        public function refe_producto($data){
            $this->db->select("p.referencia,p.comentario,p.imagen,pc.precio,p.ancho,p.codigo_contable");  
            $this->db->select("CASE WHEN (sum(m.cantidad_um*(m.id_medida=1)) is null)  THEN 0 Else sum(m.cantidad_um*(m.id_medida=1)) END AS  total_entrada_mts",false);  
            $this->db->select("CASE WHEN (sum(m.cantidad_um*(m.id_medida=2)) is null)  THEN 0 Else sum(m.cantidad_um*(m.id_medida=2)) END AS  total_entrada_kg",false);  

                    $this->db->select("CASE WHEN (sum(t.cantidad_um*(t.id_medida=1)) is null)  THEN 0 Else sum(t.cantidad_um*(t.id_medida=1)) END AS  total_entrada_temp_mts",false);  
            $this->db->select("CASE WHEN (sum(t.cantidad_um*(t.id_medida=2)) is null)  THEN 0 Else sum(t.cantidad_um*(t.id_medida=2)) END AS  total_entrada_temp_kg",false); 

            $this->db->select("max(pc.cantidad_aprobada*(pc.id_medida=1)) total_compra_mts",false);  
            $this->db->select("max(pc.cantidad_aprobada*(pc.id_medida=2)) total_compra_kg",false);  

            $this->db->from($this->productos.' as p');
            $this->db->join($this->historico_historial_compra.' As pc' , 'p.referencia = pc.referencia'); //,'LEFT'
            $this->db->join($this->historico_registros_entradas.' As m' , 'pc.referencia = m.referencia AND pc.movimiento=m.id_compra AND pc.id_medida=m.id_medida','LEFT'); 
            $this->db->join($this->registros_temporales.' As t' , 'pc.referencia = t.referencia AND pc.movimiento=t.id_compra AND pc.id_medida=t.id_medida','LEFT');

            $this->db->where('p.descripcion', ($data['val_prod']) );
            $this->db->where('p.id_color', $data['val_color']);
            $this->db->where('p.id_composicion', $data['val_comp']);
            $this->db->where('p.id_calidad', $data['val_calida']);

            $this->db->where('p.activo',0);
            $this->db->where('pc.movimiento',$data['id_compra']);

             $this->db->group_by("p.referencia"); //,pc.id_medida
             //$this->db->having("(total_compra_mts > total_entrada_mts) OR (total_compra_kg > total_entrada_kg) ");            
             $this->db->having("(total_compra_mts > (total_entrada_mts+total_entrada_temp_mts)) OR (total_compra_kg > (total_entrada_kg+total_entrada_temp_kg))");
          


            $result = $this->db->get();
            
            if ( $result->num_rows() > 0 )
               return $result->row();
            else
               return False;
            $result->free_result();
        }      
    //********************

        //listado de compras
        /*public function listado_compra() {

            $this->db->select("m.movimiento, m.id_proveedor, prov.nombre, um.medida, m.id_medida, m.id_almacen");
            $this->db->select("a.almacen");
      
            $this->db->from($this->historico_historial_compra.' as m');
            $this->db->join($this->unidades_medidas.' As um' , 'um.id = m.id_medida','LEFT');
            $this->db->join($this->almacenes.' As a' , 'a.id = m.id_almacen');
            $this->db->join($this->proveedores.' As prov', 'prov.id= m.id_proveedor');
            $this->db->group_by('m.movimiento'); 
            $result = $this->db->get();

                if ( $result->num_rows() > 0 )
                   return $result->result();
                else
                   return False;
                $result->free_result();
      }    
      */


    public function listado_compra(){

            $this->db->select("p.movimiento, p.id_proveedor, prov.nombre, med.medida, p.id_medida, p.id_almacen");
            $this->db->select("a.almacen");

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

          
          $this->db->group_by("p.movimiento");

          $result = $this->db->get();


              if ( $result->num_rows() > 0 ) {
                $dato=array();
                foreach ($result->result() as $row) {

                          if (!(($row->total_compra_mts <= ($row->total_entrada_mts+$row->total_entrada_temp_mts)) AND ($row->total_compra_kg <= ($row->total_entrada_kg+$row->total_entrada_temp_kg)) ) )  {
                            $dato[]= array(
                                 'status'=>(($row->total_compra_mts <= ($row->total_entrada_mts+$row->total_entrada_temp_mts)) AND ($row->total_compra_kg <= ($row->total_entrada_kg+$row->total_entrada_temp_kg)) ) ? 'Total' : (( (($row->total_entrada_mts+$row->total_entrada_temp_mts) + ($row->total_entrada_kg+$row->total_entrada_temp_kg) ) ==0 ) ? 'Por Procesar' : 'Parcial'),   
                                 "id_medida"=>$row->id_medida,
                                 "almacen"=>$row->almacen,
                                 "movimiento"=>$row->movimiento,
                                 "nombre"=>$row->nombre,
                                 "id_almacen"=>$row->id_almacen,
                                 //3=>$row->movimiento,

                               );
                          }
                }
        
                  return  ( $dato ); 
              }   
              else {
                  
                  return false; //json_encode($output);
                  

              }

              $result->free_result();           

      }  



        //1- Este es el "listado de la regilla" que esta debajo de entrada, de todo lo temporal
        public function listado_movimientos_temporal(){

          $id_session = $this->session->userdata('id');
                    
          $this->db->select('m.id, m.movimiento,m.movimiento_unico,m.id_empresa, m.factura, m.id_descripcion, m.id_operacion,m.devolucion');
          $this->db->select('m.id_color, m.id_composicion, m.id_calidad, m.referencia');
          $this->db->select('m.id_medida, m.cantidad_um, m.peso_real, m.cantidad_royo, m.ancho, m.precio, m.codigo, m.comentario');
          $this->db->select('m.id_estatus, m.id_lote, m.consecutivo, m.id_cargador, m.id_usuario, m.fecha_mac fecha');

          $this->db->select('c.hexadecimal_color, u.medida,p.nombre');
          
          $this->db->from($this->registros_temporales.' as m');
          $this->db->join($this->colores.' As c' , 'c.id = m.id_color','LEFT');
          $this->db->join($this->unidades_medidas.' As u' , 'u.id = m.id_medida','LEFT');
          $this->db->join($this->proveedores.' As p' , 'p.id = m.id_empresa','LEFT');

          $this->db->where('m.id_usuario',$id_session);
          $this->db->where('m.id_operacion',71);
          $this->db->where('m.id_compra <>',0);

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


//2-****************este es para obtener proveedor y factura temporal que se pone de primero
        public function valores_movimientos_temporal(){
          $id_session = $this->session->userdata('id');
          
          $this->db->distinct();          
          $this->db->select('m.id, m.id_empresa, m.factura,m.id_almacen,m.id_factura,m.id_fac_orig, m.id_fac_orig, m.id_tipo_pago,m.iva');
          $this->db->select('p.nombre');
          $this->db->select('m.id_compra');
          $this->db->select('m.c1, m.c2, m.c1234, m.c234, m.c34, m.id_operacion');
          
          $this->db->from($this->registros_temporales.' as m');
          $this->db->join($this->proveedores.' As p' , 'p.id = m.id_empresa','LEFT');

          $this->db->where('m.id_usuario',$id_session);
          $this->db->where('id_operacion',71);
          $this->db->where('m.id_compra <>',0);

           $result = $this->db->get();
        
            if ( $result->num_rows() > 0 )
               return $result->row();
            else
               return False;
            $result->free_result();
        }    

    //3-cuando voy agregar producto a temporal debo checar "si alguien utilizo la factura"
        //para evitar duplicidad de factura  
    public function existencia_factura($data){
              $this->db->where('factura',$data['fact_revision']);
              $this->db->from($this->historico_registros_entradas);
              $cant = $this->db->count_all_results();          

              if ( $cant > 0 )
                 return false;
              else
                 return true;              

        }   
      

   //****************Añadir un producto a temporal 
        public function anadir_producto_temporal( $data ){

          

          $id_session = $this->session->userdata('id');
          $num_tienda = $this->session->userdata('config_tienda_activo');
          $fecha_hoy2 = date('Y-m-d H:i:s');  
          
          $fecha_hoy= date ( 'Y-m-d H:i:s' , strtotime ( '+1 g' , strtotime ($fecha_hoy2) ) );

          $cant=0;

          $cant = self::consecutivo_productos($data['referencia'])->consecutivo;
          //return $data['referencia'];



          if ($cant ==false) {
             $cant =0;
          }

          $this->db->where('id_usuario',$id_session);
          $this->db->where('id_lote',$data['id_lote']);
          $this->db->where('referencia',$data['referencia']);
          $this->db->where('id_operacion',71);

          $this->db->from($this->registros_temporales);
          $cant = $this->db->count_all_results()+$cant;          

          for ($i=(1+$cant); $i <= ($data['cantidad_royo']+$cant) ; $i++) { 

             
              $this->db->set( 'id_usuario',  $id_session );
              $this->db->set( 'id_empresa',  $data['id_empresa'] );    
              $this->db->set( 'fecha_entrada', $fecha_hoy  );  
              $this->db->set( 'movimiento', $data['movimiento']); //depende de "id_tipo_pago y id_factura"
              $this->db->set( 'movimiento_unico', $data['movimiento_unico']);   //unico y nuevo


              $this->db->set('c1', $data['c1']);   
              $this->db->set('c2', $data['c2']);   
              $this->db->set('c1234', $data['c1234']);   
              $this->db->set('c234', $data['c234']);   
              $this->db->set('c34', $data['c34']);               

              $this->db->set( 'id_compra', $data['id_compra']);   //unico y nuevo
              

              if  (isset($data['factura'])) {
                $this->db->set( 'factura', $data['factura']   );  
              }

              $this->db->set( 'id_almacen', $data['id_almacen']   );  
              $this->db->set( 'id_factura', $data['id_factura']   );  
              $this->db->set( 'id_fac_orig', $data['id_factura']   );  
              $this->db->set( 'id_tipo_pago', $data['id_tipo_pago']   );  
              
              $this->db->set( 'iva', $data['iva']   );  



              $this->db->set( 'id_descripcion', $data['id_descripcion']);  
              $this->db->set( 'id_color', $data['id_color']);  
              $this->db->set( 'id_composicion', $data['id_composicion']  );  
              $this->db->set( 'id_calidad', $data['id_calidad']   );  
              $this->db->set( 'referencia', $data['referencia']   );  

              $this->db->set( 'id_medida', $data['id_medida']  );  

              $this->db->set( 'peso_real', $data['peso_real']  );  
              $this->db->set( 'cantidad_um', $data['cantidad_um']  );  
              $this->db->set( 'cantidad_royo', $data['cantidad_royo']);  
              $this->db->set( 'ancho', $data['ancho']   );   
              $this->db->set( 'precio', $data['precio']  );  

              $this->db->set( 'num_partida', $data['num_partida']  );  

              $this->db->set( 'comentario', $data['comentario']);  

              $this->db->set( 'codigo', $data['codigo'].$num_tienda.'_'.$i   );
              $this->db->set( 'id_estatus', $data['id_estatus']);

              $this->db->set( 'id_lote', $data['id_lote']);     
              $this->db->set( 'consecutivo', $i);           //data['consecutivo']
              $this->db->set( 'id_operacion', 71);           //data['consecutivo']

              $this->db->where('id_usuario',$id_session);
              $this->db->where('id_lote',$data['id_lote']);
              $this->db->where('referencia',$data['referencia']);
              $this->db->where('id_operacion',71);  //esto significa que es entrada, 

              $this->db->insert($this->registros_temporales);
            
          }
          

            

            if ($this->db->affected_rows() > 0){
                    return TRUE;
                } else {
                    return FALSE;
                }
                $result->free_result();
        }  







  // esto es para mostrar los productos temporales "regilla"
    public function buscador_productos_temporales($data){

          $cadena = addslashes($data['search']['value']);
          $inicio = $data['start'];
           $largo = $data['length'];

          $columa_order = $data['order'][0]['column'];
                 $order = $data['order'][0]['dir'];

      
          switch ($columa_order) {
                   case '1':
                        $columna = 'm.codigo';
                     break;
                   case '2':
                        $columna = 'm.id_descripcion';
                     break;
                   case '3':
                        $columna = 'c.hexadecimal_color';
                     break;
                   case '4':
                        $columna = 'm.cantidad_um, u.medida';
                     break;
                   case '5':
                        $columna = 'm.ancho';
                     break;
                   case '6':
                        $columna = 'm.peso_real';
                     break;

                   case '7':
                          $columna = 'p.nombre';
                     break;
                   case '8':
                        $columna = 'm.id_lote, m.consecutivo';
                     break;                     

                   case '9':
                        $columna = 'm.num_partida';
                     break;                     


                   case '10':
                        $columna = 'm.precio';
                     break;      


                   case '11':
                   case '12':
                        $columna = 'm.precio, m.iva';
                     break;      

                   default:
                         $columna = 'm.id_lote, m.id_descripcion';
                     break;
                 }                 


                      
          
          $fecha_hoy =  date("Y-m-d h:ia"); 
          $hoy = new DateTime($fecha_hoy);

          $id_session = $this->db->escape($this->session->userdata('id'));

          $this->db->select("SQL_CALC_FOUND_ROWS(m.id)"); //
          $this->db->select('m.id, m.id_descripcion,  m.num_partida');
          $this->db->select(' m.cantidad_um,  m.ancho, m.precio, m.codigo');
          $this->db->select('m.id_estatus, m.id_lote, m.consecutivo');
          $this->db->select('c.hexadecimal_color, u.medida,p.nombre');
          $this->db->select('m.peso_real');
          $this->db->select('m.iva');
          $this->db->select("((m.precio*m.cantidad_um*m.iva))/100 as sum_iva");
          $this->db->select("(m.precio*m.cantidad_um)+((m.precio*m.cantidad_um*m.iva))/100 as sum_total");
          $this->db->select("( CASE WHEN m.id_medida = 1 THEN m.cantidad_um ELSE 0 END ) AS metros");
          $this->db->select("( CASE WHEN m.id_medida = 2 THEN m.cantidad_um ELSE 0 END ) AS kilogramos");
          $this->db->select("prod.codigo_contable");  


          $this->db->from($this->registros_temporales.' as m');
          $this->db->join($this->productos.' As prod' , 'prod.referencia = m.referencia'); //,'LEFT'
          $this->db->join($this->colores.' As c' , 'c.id = m.id_color'); //,'LEFT'
          $this->db->join($this->unidades_medidas.' As u' , 'u.id = m.id_medida'); //,'LEFT'
          $this->db->join($this->proveedores.' As p' , 'p.id = m.id_empresa'); //,'LEFT'
        
        
          //filtro de busqueda
          //( m.id_usuario = '.$id_session.' ) or ( m.id_operacion = 1 ) 
          $where = '(
                      (
                        ( m.id_usuario = '.$id_session.' ) AND ( m.id_compra <> 0 )
                      ) 
                      AND
                      (    
                          (m.codigo LIKE  "%'.$cadena.'%") OR ( m.id_descripcion LIKE  "%'.$cadena.'%" ) OR                    
                          (CONCAT(m.id_lote," - ",m.consecutivo) LIKE  "%'.$cadena.'%" ) OR 
                          (m.ancho LIKE  "%'.$cadena.'%") 
                       )   

            )';   

        // OR ( p.nombre  "%'.$cadena.'%" ) 
 


          $where_total = '( m.id_usuario = '.$id_session.' ) AND ( m.id_compra <> 0 ) '; //or ( m.id_operacion = 1 ) 

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


                  foreach ($result->result() as $row) {
                            $dato[]= array(
                                      0=>$row->id,
                                      1=>$row->codigo,
                                      2=>$row->id_descripcion,
                                      3=>'<div style="background-color:#'.$row->hexadecimal_color.';display:block;width:15px;height:15px;margin:0 auto;"></div>',
                                      4=>$row->cantidad_um.' '.$row->medida,
                                      5=>$row->ancho.' cm', 
                                      6=>$row->nombre,
                                      7=>$row->id_lote.' - '.$row->consecutivo, 
                                      8=>$row->id_lote.' - '.$row->consecutivo, 
                                      9=>$row->num_partida,
                                      10=>$row->metros,
                                      11=>$row->kilogramos,  
                                      12=>$row->peso_real,  
                                      13=>$row->precio*$row->cantidad_um, 
                                      14=>$row->iva, 
                                      15=>$row->sum_iva, 
                                      16=>$row->sum_total, 
                                      17=>$row->codigo_contable, 
                                      18=>$row->precio,
                                      19=>$row->id_estatus,

                                                                          
                                    );
                   }

                      

                      return json_encode ( array(
                        "draw"            => intval( $data['draw'] ),
                        "recordsTotal"    =>$registros_filtrados, 
                        "recordsFiltered" => $registros_filtrados, 
                        "data"            =>  $dato,
                        "totales"            =>  array("pieza"=>intval( self::totales_campos_salida($where_total)->pieza ), "metro"=>floatval( self::totales_campos_salida($where_total)->metros ), "kilogramo"=>floatval( self::totales_campos_salida($where_total)->kilogramos ), "peso"=>floatval( self::totales_campos_salida($where_total)->peso )),  
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
                   "totales"            =>  array("pieza"=>intval( self::totales_campos_salida($where_total)->pieza ), "metro"=>floatval( self::totales_campos_salida($where_total)->metros ), "kilogramo"=>floatval( self::totales_campos_salida($where_total)->kilogramos ), "peso"=>floatval( self::totales_campos_salida($where_total)->peso )),  

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



public function diferencia_compra($where){

           $this->db->select("SUM(precio*cantidad_um) as subtotal");
           $this->db->select("(SUM(precio*cantidad_um*iva))/100 as iva");
           $this->db->select("SUM(precio*cantidad_um)+(SUM(precio*cantidad_um*iva))/100 as total");
   
          $this->db->from($this->registros_temporales.' as m');
          $this->db->where($where);

          $result = $this->db->get();
      
          if ( $result->num_rows() > 0 )
             return $result->row();
          else
             return False;
          $result->free_result();              

    }  

public function totales_importes($where){

           $this->db->select("SUM(precio*cantidad_um) as subtotal");
           $this->db->select("(SUM(precio*cantidad_um*iva))/100 as iva");
           $this->db->select("SUM(precio*cantidad_um)+(SUM(precio*cantidad_um*iva))/100 as total");
   
          $this->db->from($this->registros_temporales.' as m');
          $this->db->where($where);

          $result = $this->db->get();
      
          if ( $result->num_rows() > 0 )
             return $result->row();
          else
             return False;
          $result->free_result();              

    }  



 public function totales_campos_salida($where){

           $this->db->select("SUM((id_medida =1) * cantidad_um) as metros", FALSE);
              $this->db->select("SUM((id_medida =2) * cantidad_um) as kilogramos", FALSE);
              $this->db->select("COUNT(m.id_medida) as 'pieza'");
              $this->db->select("SUM(m.peso_real) as 'peso'");
              
             
              $this->db->from($this->registros_temporales.' as m');
              $this->db->where($where);

             $result = $this->db->get();
          
              if ( $result->num_rows() > 0 )
                 return $result->row();
              else
                 return False;
              $result->free_result();              

       }  



  
        //****************eliminar producto temporal  
        public function eliminar_prod_temporal( $data ){
            $this->db->delete( $this->registros_temporales, array( 'id' => $data['id'] ) );
            if ( $this->db->affected_rows() > 0 ) return TRUE;
            else return FALSE;
        }
        //Este es para devolver valores para el listado por ajax
         public function valores_reordenar($data){

            $this->db->select('m.id,m.id_lote,m.codigo,m.referencia,m.consecutivo');
            $this->db->from($this->registros_temporales.' as m');

            $this->db->where('m.id',$data['id']);
            $this->db->where('m.id_operacion',71);
            $this->db->where('m.id_compra <>',0);

             $result = $this->db->get();
          
              if ( $result->num_rows() > 0 )
                 return $result->row();
              else
                 return False;
              $result->free_result();
        } 

        //reordenar el producto despues de eliminado 
        public function reordenar_prod_temporal( $data ){
            $id_session = $this->session->userdata('id');

            //$this->db->set( 'codigo', 'CONCAT( mid(codigo, 1, LENGTH(codigo)-1) ,consecutivo-1)', FALSE  );
            $this->db->set( 'codigo', 'CONCAT( mid(codigo,1,LOCATE("_",codigo) ) ,consecutivo-1)', FALSE  );

            $this->db->set( 'consecutivo', 'consecutivo-1', FALSE  );
            
            $this->db->where('consecutivo >', $data->consecutivo );
            $this->db->where('id_usuario',$id_session);
            $this->db->where('id_lote',$data->id_lote );
            $this->db->where('referencia',$data->referencia);
            $this->db->where('id_operacion',71);
            $this->db->where('id_compra <>',0);


            $this->db->update($this->registros_temporales);
     
            if ( $this->db->affected_rows() > 0 ) return TRUE;
            else return FALSE;
        }














 public function confirmando_prod_libre_inventario($data){
            
            $this->db->from($this->registros.' as m');
           
            $where = '(
                        (
                          ( m.id_apartado = 0 ) AND  ( m.estatus_salida = "0" ) AND ( m.proceso_traspaso = 0 )
                        ) AND (m.id_almacen = '.$data['id_almacen'].' )  AND ( m.codigo =  "'.addslashes($data['cod']).'" ) 
              )';   

  
            $this->db->where($where);
            $login = $this->db->get();

            if ($login->num_rows() > 0) {
               return true;
            }    
            else
                return false;
            $login->free_result();
      }


      //****************Añadir un producto a temporal************************************************************
        public function actualizar_producto_inventario( $data ){

              $id_session = $this->session->userdata('id');
              $fecha_hoy = date('Y-m-d H:i:s');  

              //registros de entradas
              $this->db->select('id id_entrada, fecha_entrada, fecha_salida, movimiento,movimiento_unico, id_empresa, factura, id_descripcion, id_color,num_partida');
              $this->db->select('id_composicion, id_calidad, referencia, id_medida, cantidad_um, peso_real, cantidad_royo, ancho');
              $this->db->select('codigo,  id_estatus, id_lote, consecutivo, id_cargador, id_usuario, id_usuario_salida');
              $this->db->select('fecha_mac, id_operacion, estatus_salida, id_apartado, id_usuario_apartado, id_cliente_apartado');
              $this->db->select('fecha_apartado, id_prorroga, fecha_vencimiento, consecutivo_cambio,devolucion');

              $this->db->select('"'.$data["comentario"].'" as comentario', false);

              $this->db->select('id_almacen');
              $this->db->select('precio, iva, id_factura,id_fac_orig');
              $this->db->select('id_pedido,  id_tipo_pedido,id_tipo_factura, id_factura_original,incluir,proceso_traspaso, consecutivo_venta');
            
            $this->db->select('id_usuario_traspaso, id_tipo_pago, precio_anterior, precio_cambio, comentario_traspaso, num_control');


              $this->db->from($this->registros);

              $this->db->where('codigo',$data['codigo']);
              $result = $this->db->get();

              $objeto = $result->result();
              //copiar a tabla "registros_cambios"
              foreach ($objeto as $key => $value) {
                $this->db->insert($this->registros_cambios, $value); 
              }              

              $this->db->set( 'consecutivo_cambio', 'consecutivo_cambio+1', FALSE  );
              $this->db->set( 'id_usuario',  $id_session );
              $this->db->set( 'cantidad_um', $data['cantidad_um']  );  
              $this->db->set( 'id_medida', $data['id_medida']  );  
              $this->db->set( 'ancho', $data['ancho']   );   
              $this->db->set( 'precio', $data['precio']  );  
              $this->db->set( 'comentario', $data['comentario']);  //




              $this->db->where('codigo',$data['codigo']);

              $this->db->update($this->registros);


            if ($this->db->affected_rows() > 0){
                    return TRUE;
                } else {
                    return FALSE;
                }
                $result->free_result();
        }  
        


        public function consecutivo_productos($referencia){

          $this->db->select('p.id, p.referencia, p.consecutivo');
          $this->db->from($this->productos.' as p');
          
          $this->db->where('referencia',$referencia);  
          

          $result = $this->db->get();

            if ( $result->num_rows() > 0 )
               return $result->row();
            else
               return False;
            $result->free_result();
        } 


   




////////////////////////"http://inventarios.dev.com/pedidos"///////////////////////////////////////////////////////////////
    
    







     //reordenar http://mysql.conclase.net/curso/?sqlfun=SUBSTRING

      //****************conformar el listado por ajax  ************************************************************
      public function cant_producto_temporal( $data ){
          $id_session = $this->session->userdata('id');
          $cant=0;
          $this->db->where('id_usuario',$id_session);
          $this->db->where('id_lote',$data['id_lote']);
          $this->db->where('referencia',$data['referencia']);
          $this->db->where('id_operacion',71);

          $this->db->from($this->registros_temporales);
          $cant = $this->db->count_all_results(); 
        
            if ( $cant > 0 )
               return $cant;
            else
               return 0;

      }    


        //Este es para devolver valores para el listado por ajax
         public function listado_ajax($data){
            $id_session = $this->session->userdata('id');
            $this->db->select('m.id,m.id_lote,m.codigo');
            $this->db->select('c.hexadecimal_color');
            $this->db->select('m.id_descripcion,u.medida,m.cantidad_um,m.ancho,p.nombre,m.id_lote, m.consecutivo,');

            $this->db->from($this->registros_temporales.' as m');
            $this->db->join($this->colores.' As c' , 'c.id = m.id_color','LEFT');
            $this->db->join($this->unidades_medidas.' As u' , 'u.id = m.id_medida','LEFT');
            $this->db->join($this->proveedores.' As p' , 'p.id = m.id_empresa','LEFT');

            $this->db->where('m.id_usuario',$id_session);
            $this->db->where('id_lote',$data['id_lote']);
            $this->db->where('referencia',$data['referencia']);
            $this->db->where('id_operacion',71);

            $this->db->order_by('m.consecutivo', 'asc'); 

            $posicion=($data['total']-$data['cant_royo']);
            $this->db->limit($data['cant_royo'],$posicion); 
             $result = $this->db->get();
          
              if ( $result->num_rows() > 0 )
                 return $result->result();
              else
                 return False;
              $result->free_result();
        }     




     public function actualizando_consecutivo_productos($id_operacion){

            $id_session = $this->session->userdata('id');

            $this->db->distinct();
            $this->db->select('referencia, consecutivo'); 

            $this->db->from($this->registros_temporales);

            $this->db->where('id_usuario',$id_session);
            $this->db->where('id_operacion',$id_operacion);

            $this->db->order_by("referencia", 'ASC');
            $this->db->order_by("consecutivo", 'ASC');


            $result = $this->db->get();
            $objeto = $result->result();

          
          foreach ($objeto as $key => $value) {

            $this->db->set( 'consecutivo', $value->consecutivo, FALSE  );
            $this->db->set( 'id_usuario', $id_session );

            $this->db->where('referencia',$value->referencia);
            $this->db->update($this->productos);

          }


     }  





      public function reordenar_new_temporal(  ) {


            $id_session = $this->session->userdata('id');

            $this->db->distinct();
            $this->db->select('referencia'); 
            $this->db->from($this->registros_temporales);

            $this->db->where('id_usuario',$id_session);

            $this->db->order_by("referencia", 'ASC');

            $result = $this->db->get();
            $objeto = $result->result();

          
          foreach ($objeto as $key => $value) {
                $cant=0;
                $cant = self::consecutivo_productos($value->referencia)->consecutivo;

                if ($cant ==false) {
                   $cant =0;
                }


                
                $this->db->select('id, referencia, consecutivo'); 
                $this->db->from($this->registros_temporales);

                $this->db->where('referencia',$value->referencia);
                $this->db->where('id_usuario',$id_session);
                $this->db->order_by("referencia", 'ASC');
                $this->db->order_by("consecutivo", 'ASC');

                $result = $this->db->get();
                $objeto_productos = $result->result();

                
                foreach ($objeto_productos as $numero => $valor) {

                  $this->db->set( 'codigo', 'CONCAT( mid(codigo,1,LOCATE("_",codigo) ) ,'.($cant+$numero+1).')', FALSE  );
                  $this->db->set( 'consecutivo', $cant+$numero+1, FALSE  );

                  $this->db->where('referencia',$value->referencia);
                  $this->db->where('id_usuario',$id_session);
                  $this->db->where('id',$valor->id);

                  $this->db->update($this->registros_temporales);

                }
           }     
        }





        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


          //Este es para ver si hay productos en tabla temporal
          public function existencia_temporales(){
              $id_session = $this->session->userdata('id');
              $cant=0;

              $this->db->where('id_usuario',$id_session);
              $this->db->where('id_operacion',71);
              $this->db->where('id_compra <>',0);
              $this->db->from($this->registros_temporales);
              $cant = $this->db->count_all_results();          

              if ( $cant > 0 )
                 return true;
              else
                 return false;              

        }    


         //procesando operaciones
        public function procesando_operacion( $data ){

          $id_session = $this->session->userdata('id');
          $fecha_hoy = date('Y-m-d H:i:s');  

          $consecutivo = self::consecutivo_operacion(71,$data['id_factura']); //cambio

          $consecutivo_unico = self::consecutivo_operacion_unico(71); 

          //actualizar (consecutivo) en tabla "operacion" 
          if ($data['id_factura']==1) {
              $this->db->set( 'conse_factura', 'conse_factura+1', FALSE  );  
          } else {
              $this->db->set( 'conse_remision', 'conse_remision+1', FALSE  );  
          }
          $this->db->set( 'consecutivo', 'consecutivo+1', FALSE  );  

          $this->db->set( 'id_usuario', $id_session );
          $this->db->where('id',71);
          $this->db->update($this->operaciones);

          //actualizando nuevos consecutivos
           $this->catalogo->actualizando_nuevos_consecutivos($data);
           
           //Obtener nuevos consecutivos
           $new_consecutivo   = $this->catalogo->consecutivo_general($data);


          self::reordenar_new_temporal(); //cambio
          self::actualizando_consecutivo_productos($data['id_operacion']); //cambio

             
          //aqui lista todos los datos que fueron entrados por un usuario especifico   
          $this->db->select('id_empresa, factura, id_descripcion, id_color, id_composicion, id_calidad, referencia, num_partida,id_almacen,id_factura,id_fac_orig,iva, id_tipo_pago,id_compra');
          $this->db->select('id_medida, cantidad_um, peso_real, cantidad_royo, ancho, precio, codigo, comentario, id_estatus, id_lote, consecutivo');
          $this->db->select('id_cargador, id_usuario, fecha_mac, id_operacion');
          $this->db->select('"'.$fecha_hoy.'" AS fecha_entrada',false);

          $this->db->select($consecutivo.' AS movimiento',false); //cambio

          $this->db->select($consecutivo_unico.' AS movimiento_unico',false); //cambio


          $this->db->select($new_consecutivo->c1.' AS c1',false); 
          $this->db->select($new_consecutivo->c2.' AS c2',false); 
          $this->db->select($new_consecutivo->c1234.' AS c1234',false); 
          $this->db->select($new_consecutivo->c234.' AS c234',false); 
          $this->db->select($new_consecutivo->c34.' AS c34',false); 



          $this->db->from($this->registros_temporales);

          $this->db->where('id_usuario',$id_session);
          $this->db->where('id_operacion',$data['id_operacion']);
          $this->db->where('id_compra <>',0);

          $result = $this->db->get();

          //return $result->result(); //cambio
          $objeto = $result->result();
          //copiar a tabla "registros" e "historico_registros_entradas"
          foreach ($objeto as $key => $value) {
            $this->db->insert($this->historico_registros_entradas, $value); 
            $value->peso_real = 0;
            $this->db->insert($this->registros, $value);
            $num_movimiento = $value->movimiento;
            $num_movimiento_unico = $value->movimiento_unico;
          }

          //aqui es donde voy a agregar "historico_ctasxpagar"

                  $this->db->select($new_consecutivo->c1.' AS c1',false); 
                  $this->db->select($new_consecutivo->c2.' AS c2',false); 
                  $this->db->select($new_consecutivo->c1234.' AS c1234',false); 
                  $this->db->select($new_consecutivo->c234.' AS c234',false); 
                  $this->db->select($new_consecutivo->c34.' AS c34',false); 

                  $this->db->select('"'.addslashes($num_movimiento).'" AS movimiento',false); 
                  $this->db->select('"'.addslashes($num_movimiento_unico).'" AS movimiento_unico',false); 
                  $this->db->select('m.id_tipo_pago');
                  $this->db->select('m.id_almacen');
                  $this->db->select('m.id_empresa');
                  $this->db->select('m.fecha_entrada');
                  $this->db->select('m.factura');
                  $this->db->select('m.id_factura,m.id_fac_orig');
                  $this->db->select('m.fecha_mac, m.id_operacion,m.id_usuario');
                  $this->db->select('m.comentario');
                  
                  $this->db->select('sum(m.precio*m.cantidad_um) as subtotal');           
                  $this->db->select("sum(m.precio*m.cantidad_um*m.iva)/100 as iva", FALSE);
                  $this->db->select("sum(m.precio*m.cantidad_um)+((sum(m.precio*m.cantidad_um*m.iva))/100) as total", FALSE);
                  $this->db->select('m.id_estatus,m.id_compra');

                  $this->db->from($this->registros_temporales.' as m');

          
                  $where = '(
                                 (m.id_usuario = "'.$id_session.'" ) AND ( m.id_compra <> 0 ) AND
                                 ( m.id_operacion = '.$data['id_operacion'].' )    AND ( m.devolucion = 0 )
                           )';
                   

                  $this->db->where($where);          

                  //$this->db->group_by('m.movimiento,m.id_almacen,m.id_empresa,m.factura');
                  $this->db->group_by('m.movimiento_unico,m.id_almacen,m.id_empresa,m.factura');


                  $result_ctas_pagar = $this->db->get();

                  
                  $objeto_ctas_pagar = $result_ctas_pagar->result();
                  //copiar a tabla de "historico_ctasxpagar"  un resumen
                  foreach ($objeto_ctas_pagar as $key => $value) {
                    $this->db->insert($this->historico_ctasxpagar, $value); 
                    
                  }


          //fin de  agregar "historico_ctasxpagar" 

         

          //eliminar los registros en "temporal_registros" del usuario 
          $this->db->delete($this->registros_temporales, array('id_usuario'=>$id_session, 'id_compra <>'=>0, 'id_operacion'=>$data['id_operacion'])); 

          return $num_movimiento_unico; //$num_movimiento;

          $result->free_result();          

        }

      

        //listado de movimiento de una entrada, de un movimiento especifico
        public function listado_movimientos_registros($data){

          $id_session = $this->session->userdata('id');
                    
          $this->db->select('m.id, m.movimiento,m.movimiento_unico, m.id_empresa, m.factura, m.id_descripcion, m.devolucion, num_partida,id_almacen, a.almacen, id_factura,m.id_fac_orig,id_tipo_pago, iva');
          $this->db->select('m.id_color, m.id_composicion, m.id_calidad, m.referencia, m.id_operacion, m.c234');
          $this->db->select('m.id_medida, m.cantidad_um,m.peso_real, m.cantidad_royo, m.ancho, m.precio, m.codigo, m.comentario');
          $this->db->select('m.id_estatus, m.id_lote, m.consecutivo, m.id_cargador, m.id_usuario, m.fecha_mac fecha');
          $this->db->select('DATE_FORMAT((m.fecha_mac),"%d-%m-%Y %H:%i") as fecha2', false);

          $this->db->select("( CASE WHEN m.devolucion <> 0 THEN 'red' ELSE 'black' END ) AS color_devolucion", FALSE);
          

          $this->db->select('c.hexadecimal_color, u.medida,p.nombre');

          $this->db->select('(m.precio*m.cantidad_um) as sum_precio');           
          $this->db->select("(m.precio*m.cantidad_um*m.iva)/100 as sum_iva", FALSE);
          $this->db->select("(m.precio*m.cantidad_um)+(((m.precio*m.cantidad_um*m.iva))/100) as sum_total", FALSE);


          $this->db->select("prod.codigo_contable, m.id_compra");            
          $this->db->select('m.nombre_usuario', false); 
          $this->db->select('tipfac.tipo_factura'); 
          
          $this->db->from($this->historico_registros_entradas.' as m');
          $this->db->join($this->almacenes.' As a' , 'a.id = m.id_almacen'); //AND a.activo=1
          $this->db->join($this->productos.' As prod' , 'prod.referencia = m.referencia','LEFT');
          $this->db->join($this->colores.' As c' , 'c.id = m.id_color','LEFT');
          $this->db->join($this->unidades_medidas.' As u' , 'u.id = m.id_medida','LEFT');
          $this->db->join($this->proveedores.' As p' , 'p.id = m.id_empresa','LEFT');
          $this->db->join($this->tipos_facturas.' As tipfac' , 'tipfac.id = m.id_factura'); 
          

          if ($data['id_estatus']!=0) {
             $id_estatusid = ' and ( m.id_estatus =  '.$data['id_estatus'].' ) ';  
          } else {
             $id_estatusid = '';
          }    

          /*
          if ($data['tipo_entrada']!='T') {
             $nomb_usuario = ' and ( m.nombre_usuario =  "" )  and ( m.devolucion =  0) and ( m.id_compra =  0)  ';   //Normal
          } else {
             $nomb_usuario = ' and ( m.nombre_usuario <>  "" ) ';   //Transferencia
          }    

          if ($data['tipo_entrada']=='D') {
              $nomb_usuario = ' and ( m.devolucion <>  0) ';  //devoluciones
          } 

          if ($data['tipo_entrada']=='C') {
              $nomb_usuario = ' and ( m.id_compra <>  0) ';  //compra
          } 
          */

          if ($data['tipo_entrada']=='E') {
              $nomb_usuario = ' and  ( m.id_operacion =  1 ) and ( m.devolucion =  0) and ( m.id_compra =  0)  '; 
          } 

          if ($data['tipo_entrada']=='B') {
              $nomb_usuario = ' and  ( m.id_operacion =  72 ) ';  //compra
          } 

          if ($data['tipo_entrada']=='T') {
              $nomb_usuario = ' and  ( m.id_operacion =  70 ) ';  //compra
          } 


          if ($data['tipo_entrada']=='D') {
              $nomb_usuario = ' and ( m.devolucion <>  0) ';  //devoluciones
          } 

          if ($data['tipo_entrada']=='C') {
              $nomb_usuario = ' and  ( m.id_operacion =  71 ) ';  //compra
          } 


          if ($data['tipo_entrada']=='A') {
              $nomb_usuario = ' and  ( m.id_operacion =  73 ) ';  //compra
          } 


          $where = '(
                      (
                        (( m.id_factura = '.$data['id_factura'].' ) OR ('.$data['dev'].'=1) )  AND
                        ( m.devolucion = '.$data['dev'].' ) AND ( m.movimiento_unico = '.$data['num_mov'].' ) AND ( m.id_operacion = 71 ) '.$id_estatusid.$nomb_usuario.' 
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





        /////////////////auxiliares que devuelven consecutivos//////////////////////


       public function consecutivo_operacion( $id,$id_factura ){
              $this->db->select("o.consecutivo,o.conse_factura,o.conse_remision,o.conse_surtido");         
              $this->db->from($this->operaciones.' As o');
              $this->db->where('o.id',$id);
              $result = $this->db->get( );
                  if ($result->num_rows() > 0) {
                        $consecutivo_actual = ( ($id_factura==1) ? $result->row()->conse_factura : $result->row()->conse_remision );
                        return $consecutivo_actual+1;
                  }                    
                  else 
                      return FALSE;
                  $result->free_result();
       }  


       public function consecutivo_operacion_unico( $id ){
              $this->db->select("o.consecutivo");         
              $this->db->from($this->operaciones.' As o');
              $this->db->where('o.id',$id);
              $result = $this->db->get( );
                  if ($result->num_rows() > 0) {
                        return $result->row()->consecutivo+1;
                  }                    
                  else 
                      return FALSE;
                  $result->free_result();
       }  


	} 


?>

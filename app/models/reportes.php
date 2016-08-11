<?php if(! defined('BASEPATH')) exit('No tienes permiso para acceder a este archivo');

	class reportes extends CI_Model{
		
		private $key_hash;
		private $timezone;

		function __construct(){

      parent::__construct();
      $this->load->database("default");
      $this->key_hash    = $_SERVER['HASH_ENCRYPT'];
      $this->timezone    = 'UM1';

      
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

      $this->colores                 = $this->db->dbprefix('catalogo_colores');
      $this->unidades_medidas        = $this->db->dbprefix('catalogo_unidades_medidas');
	
		}



 


        
        public function listado_entradas( $data ){

          $id_session = $this->session->userdata('id');
                    
          $this->db->distinct();                    
          $this->db->select('m.id, m.movimiento,m.id_empresa,p.nombre, m.factura, m.id_descripcion, m.id_operacion, m.fecha_mac fecha');
          $this->db->from($this->registros.' as m');
          $this->db->join($this->proveedores.' As p' , 'p.id = m.id_empresa','LEFT');

          $this->db->where('m.id_usuario',$id_session);
          $this->db->where('m.id_operacion',$data['id_operacion']);
          $this->db->where('m.movimiento',$data['num_mov']);

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
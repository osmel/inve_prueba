<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Paginacion extends CI_Model
{
    
    public function __construct()
    {
        
        parent::__construct();


            $this->load->database("default");
            $this->key_hash    = $_SERVER['HASH_ENCRYPT'];
            $this->timezone    = 'UM1';

                //usuarios
            $this->usuarios    = $this->db->dbprefix('usuarios');

            $this->historico_acceso = $this->db->dbprefix('unidad_historico_acceso');

                //catalogos         
            $this->perfiles    = $this->db->dbprefix('perfiles');
            $this->ubicaciones = $this->db->dbprefix('catalogo_estados');
            $this->plazas = $this->db->dbprefix('catalogo_plazas');
            $this->aseguradoras = $this->db->dbprefix('catalogo_aseguradoras');
            $this->lineas = $this->db->dbprefix('catalogo_lineas');
            $this->sub_lineas = $this->db->dbprefix('catalogo_sublineas');
            $this->marcas = $this->db->dbprefix('catalogo_marcas');
            $this->modelos = $this->db->dbprefix('catalogo_modelos');
            $this->monitoreo_satelital = $this->db->dbprefix('catalogo_monitoreo_satelital');

            $this->propietarios = $this->db->dbprefix('catalogo_propietarios');
            
            //caracteristicas u operaciones unidad

            $this->generales        = $this->db->dbprefix('unidad_datos_generales');
            $this->factura          = $this->db->dbprefix('unidad_datos_factura');
            $this->carta_factura    = $this->db->dbprefix('unidad_datos_carta_factura');
            $this->circulacion      = $this->db->dbprefix('unidad_datos_circulacion');
            $this->monitoreo        = $this->db->dbprefix('unidad_datos_monitoreo');
            $this->seguro           = $this->db->dbprefix('unidad_datos_seguro');
            $this->tenencia         = $this->db->dbprefix('unidad_datos_tenencia');        
        
    }
    
    //obtenemos el total de filas para hacer la paginación
    public function num_rows() 
    {
        
        $consulta = $this->db->get('ed_unidad_datos_generales');
        return $consulta->num_rows();
        
    }
 
    //obtenemos todos los posts a paginar con la función
    //total_posts_paginados pasando lo que buscamos, la cantidad por página y el segmento
    //como parámetros de la misma
    public function paginacion($limit, $offset,$id_plaza='') 
    {

            $this->db->select( 'g.id, g.uid, g.modelo, g.estatus , m.marca , id_linea,  u.estado ubicacion, l.tipo_linea');
            $this->db->select("AES_DECRYPT(placas,'{$this->key_hash}') AS placas", FALSE);  
            $this->db->select("AES_DECRYPT(p.plaza,'{$this->key_hash}') AS plaza", FALSE);    
            
        
            $this->db->from($this->generales.' As g');
            $this->db->join($this->marcas.' As m', 'm.id = g.id_marca','LEFT');
            $this->db->join($this->ubicaciones.' As u', 'u.id = g.id_estado','LEFT');
            $this->db->join($this->plazas.' As p', 'p.id = g.id_plaza','LEFT');
            $this->db->join($this->lineas.' As l', 'l.id = g.id_linea','LEFT');

            if ($id_plaza!='') {
              $this->db->where('g.id_plaza',$id_plaza);
            }
          
            $this->db->where('g.baja',"0"); 

            $this->db->limit($limit, $offset);        
            
            $this->db->order_by('g.fecha', 'desc'); 
            
            $unidades = $this->db->get();

            if ($unidades->num_rows() > 0 )
                 return $unidades->result();
            else
                 return FALSE;
            $unidades->free_result();

        
    }
}
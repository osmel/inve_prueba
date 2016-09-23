<?php if(! defined('BASEPATH')) exit('No tienes permiso para acceder a este archivo');
  class modelo_borrar_datos extends CI_Model {
    
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


      
      

    }




    //cuando se elimina un diseño en particular
    public function eliminar_todos(){
       
        
        $this->db->empty_table( $this->registros_entradas);
        $this->db->empty_table( $this->registros_salidas);
        $this->db->empty_table( $this->registros_temporales);
        $this->db->empty_table( $this->registros_cambios);
        
        $this->db->empty_table( $this->historico_registros_entradas);
        $this->db->empty_table( $this->historico_registros_salidas);
        $this->db->empty_table( $this->historico_registros_traspasos);
        $this->db->empty_table( $this->historico_acceso);
        $this->db->empty_table( $this->historico_pagos_realizados);
        $this->db->empty_table( $this->historico_ctasxpagar);


        //todo lo que tiene que ver con pedido de compra
         $this->db->empty_table(  $this->temporal_pedido_compra );
         $this->db->empty_table(  $this->historico_pedido_compra );
         $this->db->empty_table(  $this->historico_cancela_pedido_compra);
         $this->db->empty_table( $this->historico_historial_compra   );



        
        return "todo fue eliminado";

    }




    
  } 


?>
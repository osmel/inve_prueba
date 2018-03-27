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

      $this->catalogo_operaciones      = $this->db->dbprefix('catalogo_operaciones');
      $this->conteo_almacen      = $this->db->dbprefix('conteo_almacen');
      $this->historico_conteo_almacen      = $this->db->dbprefix('historico_conteo_almacen');
      $this->catalogo_almacenes         = $this->db->dbprefix('catalogo_almacenes');

      $this->productos               = $this->db->dbprefix('catalogo_productos');

      
    }

    //

//
    //cuando se elimina un diseño en particular
    //Hasta la victoria siempre querido comandante

public function eliminar_remoto(){
  
  /*
      $bd_transferencia = $this->load->database('remoto2', TRUE);

      $bd_transferencia->empty_table( $bd_transferencia->dbprefix("remoto_registros_transferencia")); 
      $bd_transferencia->empty_table( $bd_transferencia->dbprefix("catalogo_productos")); 
      $bd_transferencia->empty_table( $bd_transferencia->dbprefix("catalogo_composicion")); 
      $bd_transferencia->empty_table( $bd_transferencia->dbprefix("catalogo_calidad")); 
      $bd_transferencia->empty_table( $bd_transferencia->dbprefix("catalogo_colores")); 
      $this->db = $this->load->database('default', TRUE);
              return "remoto fue eliminado";
  */


}  

    public function eliminar_todos(){
       
          $this->db->set( 'consecutivo', 0 );  
          $this->db->set( 'conse_factura', 0 );  
          $this->db->set( 'conse_remision', 0 );  
          $this->db->set( 'conse_surtido', 0 );  
          $this->db->set( 'conse_bodega', 0 );  
          $this->db->set( 'conse_transferencia', 0 );  
          $this->db->set( 'conse_ajuste_remision', 0 );  
          $this->db->set( 'conse_ajuste_factura', 0 );  

          $this->db->update($this->catalogo_operaciones );


          $this->db->set( 'activo', 1 );  
          $this->db->update($this->catalogo_almacenes );     

        $this->db->set( 'consecutivo', 0 );  
        $this->db->update($this->productos);     

        $this->db->empty_table( $this->conteo_almacen);
        $this->db->empty_table( $this->historico_conteo_almacen);


        $this->db->empty_table( $this->registros_entradas); //
        $this->db->empty_table( $this->registros_salidas);
        $this->db->empty_table( $this->registros_temporales);
        $this->db->empty_table( $this->registros_cambios);
        
        $this->db->empty_table( $this->historico_registros_entradas); //
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

         $this->db->empty_table( 'inven_historico_registros_transferencia' );
         $this->db->empty_table( 'remoto_registros_transferencia');
         

          $this->db->empty_table( 'inven_consecutivos' );


          $this->db->set( 'c1', 0 );  
          $this->db->set( 'c2', 0 );  
          $this->db->set( 'c1234', 0 );  
          $this->db->set( 'c234', 0 );  
          $this->db->set( 'c34', 0 );  

          $this->db->update('inven_consecutivos' );

         /*
           $this->db->empty_table( 'inven_remoto_colores');
           $this->db->empty_table( 'inven_remoto_composicion');
           $this->db->empty_table( 'inven_remoto_productos');
         */

        return "todo fue eliminado";

    }




    
  } 


?>
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
    }


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
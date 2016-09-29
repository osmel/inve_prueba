 //eliminar los registros en "registros_entradas"
          //$this->db->delete($this->registros, array('id_usuario'=>$id_session,'estatus_salida'=>'1')); 

          //actualizar a registros_salidas el "mov_salida" al consecutivo q le toque
          /*
          $this->db->set('mov_salida', $consecutivo, FALSE  );
          $this->db->where('id_usuario',$id_session);
          $this->db->where('id_operacion',$data['id_operacion']); //2
          $this->db->update($this->registros_salidas);
          */


          //registros de salidas    
         /*
          $this->db->select('m.id id_salida, m.id_entrada, m.mov_salida, m.fecha_entrada, m.fecha_salida, m.movimiento, m.id_empresa, m.id_cliente, m.factura, m.factura_salida,m.devolucion, m.num_partida');
          $this->db->select('m.id_descripcion, m.id_color, m.id_composicion, m.id_calidad, m.referencia, m.id_medida, m.cantidad_um, m.cantidad_royo, m.ancho,  m.codigo');
          $this->db->select('m.comentario, m.id_estatus, m.id_lote, m.consecutivo, m.id_cargador, m.id_usuario, m.id_usuario_salida, m.fecha_mac, m.id_operacion, m.estatus_salida');
          
          $this->db->select('ca.nombre cargador, p.nombre cliente');

          $this->db->select('m.id_apartado, m.id_usuario_apartado, m.id_cliente_apartado,m.fecha_apartado');

          $this->db->select('m.precio_anterior, m.precio_cambio, m.id_prorroga, m.fecha_vencimiento, m.consecutivo_cambio');
          
          //$this->db->select($data['valor'].' as tipo_salida', FALSE);                 
          //$this->db->select('"'.htmlspecialchars($data['id_cargador']).'" AS id_cargador',false);
          $this->db->select('"'.$data['valor'].'" AS tipo_salida',FALSE);

          $this->db->select('m.peso_real');
          //$this->db->select('m.id_destino,de.nombre destino');
          $this->db->select('m.id_almacen');
          $this->db->select('m.consecutivo_venta');
        
          $this->db->select('m.precio, m.iva, m.id_pedido, m.id_factura, m.id_tipo_pedido,m.id_tipo_factura, m.id_factura_original,m.incluir');


          $this->db->from($this->registros_salidas.' As m');
          $this->db->join($this->proveedores.' As p' , 'p.id = m.id_cliente','LEFT');
          $this->db->join($this->cargadores.' As ca' , 'ca.id = m.id_cargador','LEFT');
          //$this->db->join($this->catalogo_destinos.' As de' , 'de.id = m.id_destino','LEFT'); 


          $this->db->where('m.id_usuario',$id_session);
          $this->db->where('m.id_operacion',$data['id_operacion']); //2

          $result = $this->db->get();


          $objeto = $result->result();
          */
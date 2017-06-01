<?php











 switch ($data['extra_search']) {    
                     case 'existencia':
                      case 'apartado':
                          $this->db->from($this->registros_entradas.' as p');
                          $this->db->join($this->colores.' As c', 'c.id = p.id_color '); 
                          if ($filtro !=""){
                            $this->db->where( $filtro );               
                          }                          
                          $this->db->group_by( 'nombre' );                
                         break;
                      case 'salida':
                            $this->db->from($this->historico_registros_salidas.' as p');
                            $this->db->join($this->colores.' As c', 'c.id = p.id_color '); 

                            if ($filtro !=""){
                              $this->db->where( $filtro );               
                            }                          
                            $this->db->group_by( 'nombre' );                
                         break;
                      case 'devolucion':
                      case 'entrada':
                            $this->db->from($this->historico_registros_entradas.' as p');
                            $this->db->join($this->colores.' As c', 'c.id = p.id_color '); 
                            if ($filtro !=""){
                              $this->db->where( $filtro );               
                            }                   
                            $this->db->group_by( 'nombre' );                       
                         break;
                      case 'baja':
                      case 'cero':
                            $this->db->select("COUNT(prod.referencia) as 'suma'");
                            $this->db->select("prod.minimo");

                            $this->db->from($this->productos.' as prod');
                            $this->db->join($this->registros_entradas.' As p', 'p.referencia = prod.referencia and p.id_estatus=12');
                            $this->db->join($this->colores.' As c', 'c.id = p.id_color'); 

                            if ($filtro !=""){
                              $this->db->where( $filtro );               
                            }
                            $this->db->group_by("prod.referencia"); 

                             if ($data['extra_search']=="cero") {
                                  $this->db->having('suma <= 0');
                                  $where_total = 'suma <= 0';
                              }   
                              if ($data['extra_search']=="baja") {
                                  $this->db->having('((suma>0) AND (suma < prod.minimo))');
                                  $where_total = '((suma>0) AND (suma < prod.minimo))';
                              }  
                         break;
                      default:
                          $this->db->from($this->registros_entradas.' as p');
                          $this->db->join($this->colores.' As c', 'c.id = p.id_color '); 
                          if ($filtro !=""){
                            $this->db->where( $filtro );               
                          }                          
                          $this->db->group_by( 'nombre' );             

                        break;
                }

                $this->db->get();
                $consulta = $this->db->last_query();
                $result = $this->db->query('select * from ('.$consulta.') as cons_interna group by nombre order by nombre');















  public function listado_productos_completa($data){

                
                $this->db->select('p.id');
                $this->db->select('p.descripcion nombre');

                $this->db->select('"'.$data['val_prod_id'].'" as activo', false);

                $filtro="";        

                if  ($data['val_color']!=0){
                   $filtro.= (($filtro!="") ? " and " : "") . "(c.id_color = ".$data["val_color"].") ";
                }
                if ($data['val_comp'] !=0) {
                  $filtro.= (($filtro!="") ? " and " : "") . "(c.id_composicion = '".$data["val_comp"]."') ";
                } 
                if  ($data['val_calidad']!=0){
                   $filtro.= (($filtro!="") ? " and " : "") . "(c.id_calidad = ".$data["val_calidad"].") ";
                }

                
                

                switch ($data['extra_search']) {    
                     case 'existencia':
                      case 'apartado':
                          $this->db->from($this->productos.' as p');
                          $this->db->join($this->registros_entradas.' As c', 'p.referencia = c.referencia'); 
                          if ($filtro !=""){
                            $this->db->where( $filtro );               
                          }                          
                          $this->db->group_by( 'nombre' );                
                         break;
                      case 'salida':
                            $this->db->from($this->productos.' as p');
                            $this->db->join($this->historico_registros_salidas.' As c', 'p.referencia = c.referencia'); 
                            if ($filtro !=""){
                              $this->db->where( $filtro );               
                            }                          
                            $this->db->group_by( 'nombre' );                
                         break;
                      case 'devolucion':
                      case 'entrada':
                            $this->db->from($this->productos.' as p');
                            $this->db->join($this->historico_registros_entradas.' As c', 'p.referencia = c.referencia'); 
                            if ($filtro !=""){
                              $this->db->where( $filtro );               
                            }                   
                            $this->db->group_by( 'nombre' );                       
                         break;
                      case 'baja':
                      case 'cero':
                            $this->db->select("COUNT(c.referencia) as 'suma'");
                            $this->db->select("p.minimo, p.precio");
                            $this->db->from($this->productos.' as p');
                            $this->db->join($this->registros_entradas.' As c', 'p.referencia = c.referencia and c.id_estatus=12');
                            if ($filtro !=""){
                              $this->db->where( $filtro );               
                            }
                            $this->db->group_by("p.referencia, p.minimo,p.precio"); 

                             if ($data['extra_search']=="cero") {
                                  $this->db->having('suma <= 0');
                                  $where_total = 'suma <= 0';
                              }   
                              if ($data['extra_search']=="baja") {
                                  $this->db->having('((suma>0) AND (suma < p.minimo))');
                                  $where_total = '((suma>0) AND (suma < p.minimo))';
                              }  
                         break;
                      default:
                          $this->db->from($this->productos.' as p');
                          $this->db->join($this->registros_entradas.' As c', 'p.descripcion = c.id_descripcion and p.referencia = c.referencia');      
                          if ($filtro !=""){
                            $this->db->where( $filtro );               
                          }                          
                          $this->db->group_by( 'nombre' );                

                        break;
                }

                
                $this->db->get();
                $consulta = $this->db->last_query();
                $result = $this->db->query('select * from ('.$consulta.') as cons_interna group by nombre order by nombre');

                  if ( $result->num_rows() > 0 )
                     return $result->result();
                  else
                     return False;
                  $result->free_result();
        }  


          public function listado_productos_completa($data){

                //$this->db->distinct();
                $this->db->select('p.id');
                $this->db->select('p.descripcion nombre');

                $this->db->select('"'.$data['val_prod_id'].'" as activo', false);

                $filtro="";        

                if  ($data['val_color']!=0){
                   $filtro.= (($filtro!="") ? " and " : "") . "(c.id_color = ".$data["val_color"].") ";
                }
                if ($data['val_comp'] !=0) {
                  $filtro.= (($filtro!="") ? " and " : "") . "(c.id_composicion = '".$data["val_comp"]."') ";
                } 
                if  ($data['val_calidad']!=0){
                   $filtro.= (($filtro!="") ? " and " : "") . "(c.id_calidad = ".$data["val_calidad"].") ";
                }

                
                

                switch ($data['extra_search']) {    
                     case 'existencia':
                      case 'apartado':
                          $this->db->from($this->productos.' as p');
                          $this->db->join($this->registros_entradas.' As c', 'p.referencia = c.referencia'); //p.descripcion = c.id_descripcion and 
                          if ($filtro !=""){
                            $this->db->where( $filtro );               
                          }                          
                          $this->db->group_by( 'nombre' );                
                             /*
                             $busqueda = $this->modelo_reportes->buscador_entrada_home($data); //13 443
                             $this->db->from($this->registros.' as m');
                             $this->db->join($this->productos.' As prod' , 'prod.referencia = m.referencia'); 
                             */
                         break;
                      case 'salida':
                            $this->db->from($this->productos.' as p');
                            $this->db->join($this->historico_registros_salidas.' As c', 'p.referencia = c.referencia'); //p.descripcion = c.id_descripcion and
                            if ($filtro !=""){
                              $this->db->where( $filtro );               
                            }                          
                            $this->db->group_by( 'nombre' );                

                                /*
                                  $busqueda = $this->modelo_reportes->buscador_salida_home($data); //13 782
                                  $this->db->from($this->historico_registros_salidas.' as m');
                                  $this->db->join($this->productos.' As prod' , 'prod.referencia = m.referencia'); 
                                */  

                         break;
                      case 'devolucion':
                      case 'entrada':
                            $this->db->from($this->productos.' as p');
                            $this->db->join($this->historico_registros_entradas.' As c', 'p.referencia = c.referencia'); //p.descripcion = c.id_descripcion and 
                            if ($filtro !=""){
                              $this->db->where( $filtro );               
                            }                   
                            $this->db->group_by( 'nombre' );                       

                                      /*
                                      $busqueda = $this->modelo_reportes->buscador_entrada_devolucion($data); //13 443
                                      $this->db->from($this->historico_registros_entradas.' as m');
                                      $this->db->join($this->productos.' As prod' , 'prod.referencia = m.referencia');           
                                      */
                         break;
                      case 'baja':
                      case 'cero':
                            //$this->db->distrows();
                            $this->db->select("COUNT(c.referencia) as 'suma'");
                            $this->db->select("p.minimo, p.precio");
                            $this->db->from($this->productos.' as p');
                            $this->db->join($this->registros_entradas.' As c', 'p.referencia = c.referencia and c.id_estatus=12'); //p.descripcion = c.id_descripcion and

                            if ($filtro !=""){
                              $this->db->where( $filtro );               
                            }

                            //$this->db->group_by( 'p.referencia,nombre, p.minimo,p.precio' );                
                            $this->db->group_by("p.referencia, p.minimo,p.precio"); //nombre,

                             if ($data['extra_search']=="cero") {
                                  $this->db->having('suma <= 0');
                                  $where_total = 'suma <= 0';
                              }   
                              if ($data['extra_search']=="baja") {
                                  $this->db->having('((suma>0) AND (suma < p.minimo))');
                                  $where_total = '((suma>0) AND (suma < p.minimo))';
                              }  


                                /*
                                  $busqueda = $this->modelo_reportes->buscador_cero_baja($data); //13 1049
                                  $this->db->join($this->registros.' As m', 'm.referencia= p.referencia and m.id_estatus=12 '.$id_almacenid.$id_facturaid.$id_empresaid); 
                                  */


                         break;
                      default:
                          $this->db->from($this->productos.' as p');
                          $this->db->join($this->registros_entradas.' As c', 'p.descripcion = c.id_descripcion and p.referencia = c.referencia');      
                          if ($filtro !=""){
                            $this->db->where( $filtro );               
                          }                          
                          $this->db->group_by( 'nombre' );                

                        break;
                }

                
                $this->db->get();
                $consulta = $this->db->last_query();
                $result = $this->db->query('select * from ('.$consulta.') as cons_interna group by nombre order by nombre');

                  if ( $result->num_rows() > 0 )
                     return $result->result();
                  else
                     return False;
                  $result->free_result();
        }     

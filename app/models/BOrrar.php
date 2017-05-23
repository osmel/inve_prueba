<?php

  public function total_existencias_baja($where, $where_cond){
              $id_session = $this->session->userdata('id');

              $this->db->select("SQL_CALC_FOUND_ROWS *", FALSE); 

              $this->db->select("p.referencia,p.descripcion, p.minimo,  p.precio, c.hexadecimal_color,c.color,co.composicion,ca.calidad"); //p.imagen,
              $this->db->select("COUNT(m.referencia) as 'suma'");
              $this->db->select("p.id_color, p.id_composicion, p.id_calidad");

              $this->db->from($this->productos.' as p');
              $this->db->join($this->colores.' As c', 'p.id_color = c.id'); //,'LEFT'
              $this->db->join($this->composiciones.' As co', 'p.id_composicion = co.id'); //,'LEFT'
              $this->db->join($this->calidades.' As ca', 'p.id_calidad = ca.id'); //,'LEFT'
              $this->db->join($this->registros.' As m', 'p.referencia = m.referencia and m.id_estatus=12 '); //,'LEFT'

              if  ($where_cond!='') {
                $this->db->where($where_cond);
              }  

              $this->db->group_by("p.referencia,p.descripcion, p.minimo,  p.precio, c.hexadecimal_color,c.color,co.composicion,ca.calidad"); //p.imagen,
              
              $this->db->having($where);


             $result = $this->db->get();

              if ( $result->num_rows() > 0 ) {
                  $cantidad_consulta = $this->db->query("SELECT FOUND_ROWS() as cantidad");
                  $found_rows = $cantidad_consulta->row(); 
                  $registros_filtrados =  ( (int) $found_rows->cantidad);
              }  
              
              $cant = $registros_filtrados;
     
              if ( $cant > 0 )
                 return $cant;
              else
                 return 0;         
       }

    public function buscador_existencias_baja($data){


        $id_empresa= addslashes($data['proveedor']);
           $id_empresaid = '';
             if ($id_empresa!="") {
                  $id_empre =  self::check_existente_proveedor_entrada($id_empresa);

                    if (!($id_empre)) {
                      $id_empre =0;
                    }                  

                      $id_empresaid .= ' and ( m.id_empresa  =  '.$id_empre.' )  ';

            } else 
            {
               $id_empresaid .= ' ';
            }          
            $id_empresaid .= '';




          $cadena = addslashes($data['search']['value']);
          $inicio = $data['start'];
          $largo = $data['length'];
          $estatus= $data['extra_search'];

                   //productos
          //$id_descripcion= $data['id_descripcion'];
          $id_descripcion= addslashes($data['id_descripcion']);
          $id_color= $data['id_color'];
          $id_composicion= $data['id_composicion'];
          $id_calidad= $data['id_calidad'];
          $id_almacen= $data['id_almacen'];
          $id_factura= $data['id_factura'];

          $columa_order = $data['order'][0]['column'];

          $order = $data['order'][0]['dir'];

           if ($data['draw'] ==0) {  //que se ordene por el ultimo
                 $columa_order ='-1';
                 $order = 'DESC';
           } 



      
          switch ($columa_order) {
                   case '0':
                        $columna = 'p.referencia';
                     break;
                   case '1':
                        $columna = 'p.descripcion';
                     break;
                   case '2':
                        $columna = 'suma'; // y suma = COUNT(m.referencia) p.minimo
                     break;
                   case '3':
                        //$columna = 'p.imagen'; //
                     break;
                   case '4':
                        $columna = 'c.color';
                     break;
                   case '5':
                        $columna = 'p.comentario';
                     break;
                   case '6':
                              $columna= 'co.composicion';
                     break;
                   case '7':
                              $columna= 'ca.calidad';
                     break;
                   case '8':
                        $columna = 'p.precio';
                     break;
                   case '14':
                        $columna = 'm.id_almacen';
                     break;                       

                   
                   default:
                       /*$columna = 'p.referencia';*/
                       $columna = 'suma'; //'p.id';
                       $order = 'DESC';                       
                     break;
                 }           


          $id_session = $this->db->escape($this->session->userdata('id'));

          $this->db->select("SQL_CALC_FOUND_ROWS *", FALSE); 

           $this->db->select('p.referencia, p.comentario,m.proceso_traspaso');
          $this->db->select('p.descripcion, p.minimo,  p.precio'); //
          $this->db->select('c.hexadecimal_color,c.color nombre_color');
          $this->db->select("co.composicion", FALSE);  
          $this->db->select("ca.calidad", FALSE);  
          $this->db->select("COUNT(m.referencia) as 'suma'");

          $this->db->select("( CASE WHEN m.id_medida = 1 THEN m.cantidad_um ELSE 0 END ) AS metros", FALSE);
          $this->db->select("( CASE WHEN m.id_medida = 2 THEN m.cantidad_um ELSE 0 END ) AS kilogramos", FALSE);
          $this->db->select("a.almacen");
         
          if ($id_almacen!=0) {
            $id_almacenid = ' and ( m.id_almacen =  '.$id_almacen.' ) ';  
          } else {
            $id_almacenid = '';
          }   

          if ($id_factura!=0) {
            $id_facturaid = ' and ( m.id_factura =  '.$id_factura.' ) ';  
          } else {
            $id_facturaid = '';
          }   


           



          $this->db->select("p.codigo_contable");  
          $this->db->from($this->productos.' as p');

          $this->db->join($this->colores.' As c', 'p.id_color = c.id'); //,'LEFT'
          $this->db->join($this->composiciones.' As co', 'p.id_composicion = co.id'); //,'LEFT'
          $this->db->join($this->calidades.' As ca', 'p.id_calidad = ca.id'); //,'LEFT'
          $this->db->join($this->registros.' As m', 'm.referencia= p.referencia and m.id_estatus=12 '.$id_almacenid.$id_facturaid.$id_empresaid); //,'LEFT'
          $this->db->join($this->almacenes.' As a', 'a.id = m.id_almacen'); //,'LEFT'



          //(CONCAT("Reales:",count(m.referencia)) LIKE  "%'.$cadena.'%")  OR  no se puede


         if ($estatus=="cero") {
            $activo  = ' and ( p.activo =  0 ) ';  
          } else {
            $activo ='';
          }

          $where = '(
                      
                      (
                        ( p.referencia LIKE  "%'.$cadena.'%" ) OR (p.descripcion LIKE  "%'.$cadena.'%") OR (CONCAT("Optimo:",p.minimo) LIKE  "%'.$cadena.'%")  OR
                        (c.color LIKE  "%'.$cadena.'%") OR (p.comentario LIKE  "%'.$cadena.'%")  OR
                        (co.composicion LIKE  "%'.$cadena.'%")  OR
                        ( ca.calidad LIKE  "%'.$cadena.'%" )  OR 
                        ( p.precio LIKE  "%'.$cadena.'%" ) 
                       )'.$activo.'

            ) ' ; 


         

                $where_cond="";        


                if  (($id_calidad!="0") AND ($id_calidad!="") AND ($id_calidad!= null)) {
                   $where.= (($where!="") ? " and " : "") . "( p.id_calidad  =  ".$id_calidad." )";
                   $where_cond.= (($where_cond!="") ? " and " : "") . "( p.id_calidad  =  ".$id_calidad." )";
                }     

                if (($id_composicion!="0") AND ($id_composicion!="") AND ($id_composicion!= null)) {
                    $where.= (($where!="") ? " and " : "") . "( p.id_composicion  =  ".$id_composicion." ) ";
                    $where_cond.= (($where_cond!="") ? " and " : "") . "( p.id_composicion  =  ".$id_composicion." ) ";
                } 


                if  (($id_color!="0") AND ($id_color!="") AND ($id_color!= null)) {
                   $where.= (($where!="") ?  " and " : "") . "( p.id_color  =  ".$id_color." )";
                   $where_cond.= (($where_cond!="") ?  " and " : "") . "( p.id_color  =  ".$id_color." )";
                }


                //if ( ($data['val_prod_id'] !="")  && ($data['val_prod_id'] !="0") ) {
                if (($id_descripcion!="0") AND ($id_descripcion!="") AND ($id_descripcion!= null))  {                
                    $where.= (($where!="") ? " and " : "") . "( p.descripcion  =  '".$id_descripcion."' )";
                    $where_cond.= (($where_cond!="") ? " and " : "") . "( p.descripcion  =  '".$id_descripcion."' )";
                }

            
    
          

          $this->db->where($where);

          $this->db->order_by($columna, $order); 

          $this->db->group_by("p.referencia,p.descripcion, p.minimo,  p.precio, c.hexadecimal_color,c.color,co.composicion,ca.calidad"); //p.imagen,
          //paginacion


         if ($estatus=="cero") {
              $this->db->having('suma <= 0');
              $where_total = 'suma <= 0';
          }   

          if ($estatus=="baja") {
              //$this->db->having('suma < p.minimo');
              //$where_total = 'suma < p.minimo';

              $this->db->having('((suma>0) AND (suma < p.minimo))');
              $where_total = '((suma>0) AND (suma < p.minimo))';

          }             
          
 

          $this->db->limit($largo,$inicio); 


          $result = $this->db->get();

              if ( $result->num_rows() > 0 ) {

                    $cantidad_consulta = $this->db->query("SELECT FOUND_ROWS() as cantidad");
                    $found_rows = $cantidad_consulta->row(); 
                    $registros_filtrados =  ( (int) $found_rows->cantidad);


                  foreach ($result->result() as $row) {

                           $dato[]= array(
                                      0=>$row->referencia, 
                                      1=>$row->descripcion,
                                      2=>'Optimo:'.$row->minimo.'<br/>  Reales:'. $row->suma,
                                      3=>"bb", 
                                      4=>"aa",
                                        /*$row->nombre_color.                                      
                                        '<div style="background-color:#'.$row->hexadecimal_color.';display:block;width:15px;height:15px;margin:0 auto;"></div>',*/
                                      5=> $row->comentario, 
                                      6=>$row->composicion,
                                      7=>$row->calidad,
                                      8=>$row->precio,
                                      9=>$row->metros,
                                      10=>$row->kilogramos,
                                      11=>"black",
                                      12=>"--",
                                      13=>"--", 
                                      14=>$row->almacen,
                                      15=>$row->proceso_traspaso,
                                      16=>$row->codigo_contable,



                                    );                    

                      }

                      return json_encode ( array(
                        "draw"            => intval( $data['draw'] ),
                        "recordsTotal"    => intval( self::total_existencias_baja($where_total,$where_cond) ),  
                        "recordsFiltered" => $registros_filtrados, 
                        "data"            =>  $dato, 
                      ));
                    
              }   
              else {
                  $output = array(
                  "draw" =>  intval( $data['draw'] ),
                  "recordsTotal" => 0,
                  "recordsFiltered" =>0,
                  "aaData" => array()
                  );
                  $array[]="";
                  return json_encode($output);
              }

              $result->free_result();   
              

      }        

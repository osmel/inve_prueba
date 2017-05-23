<?php if(! defined('BASEPATH')) exit('No tienes permiso para acceder a este archivo');

  class model_consulta extends CI_Model {
    
    private $key_hash;
    private $timezone;

    function __construct(){

  parent::__construct();
      $this->load->database("default");
      $this->key_hash    = $_SERVER['HASH_ENCRYPT'];
      $this->timezone    = 'UM1';

      
        //usuarios
      $this->usuarios                       = $this->db->dbprefix('usuarios');
        //catalogos     
      $this->composiciones                  = $this->db->dbprefix('catalogo_composicion');
      $this->colores                        = $this->db->dbprefix('catalogo_colores');
      $this->anchos                         = $this->db->dbprefix('catalogo_ancho');
      $this->cargadores                     = $this->db->dbprefix('catalogo_cargador');
      $this->calidades                      = $this->db->dbprefix('catalogo_calidad');

      $this->proveedores                    = $this->db->dbprefix('catalogo_empresas');
      $this->actividad_comercial            = $this->db->dbprefix('catalogo_actividad_comercial');
      $this->operaciones                    = $this->db->dbprefix('catalogo_operaciones');
      $this->estatuss                       = $this->db->dbprefix('catalogo_estatus');
      $this->lotes                          = $this->db->dbprefix('catalogo_lotes');

      
      $this->unidades_medidas               = $this->db->dbprefix('catalogo_unidades_medidas');

      
      $this->productos                      = $this->db->dbprefix('catalogo_productos');
      
      $this->registros_temporales               = $this->db->dbprefix('temporal_registros');
      $this->registros_cambios               = $this->db->dbprefix('registros_cambios');
      $this->registros_entradas             = $this->db->dbprefix('registros_entradas');
      $this->registros_salidas       = $this->db->dbprefix('registros_salidas');
      $this->historico_registros_entradas = $this->db->dbprefix('historico_registros_entradas');
      $this->historico_registros_salidas    = $this->db->dbprefix('historico_registros_salidas');


    }



     

       public function listado_productos_unico(){

           $result = $this->db->query('

             select r.id_descripcion descripcion from (
              (SELECT e.id_descripcion
              FROM  '.$this->historico_registros_entradas.' e
              where ( ( e.id_estatus <> "13" ) and (e.estatus_salida = "0") )
              group by e.id_descripcion)

              union

              (SELECT d.id_descripcion
              FROM  '.$this->historico_registros_entradas.' d
              where ( ( d.id_estatus = "13" ) and (d.estatus_salida = "0") )
              group by d.id_descripcion)

              union

              (SELECT s.id_descripcion
              FROM  '.$this->historico_registros_salidas.' s 
              where ( ( s.estatus_salida = "0" )  )
              group by s.id_descripcion)
            ) r  
             group by r.id_descripcion
             order by r.id_descripcion ASC

          ');  

            if ( $result->num_rows() > 0 )
               return $result->result();
            else
               return False;
            $result->free_result();
        }   



        public function lista_composiciones($data){

          $result = $this->db->query('

             select h.composicion nombre, h.id from (
                (SELECT e.id_composicion
                FROM  '.$this->historico_registros_entradas.' e
                where ( ( e.id_estatus <> "13" ) and (e.estatus_salida = "0")  AND (e.id_descripcion = "'.addslashes($data["val_prod"]).'"))
                group by e.id_composicion)

                union

                (SELECT d.id_composicion
                FROM  '.$this->historico_registros_entradas.' d
                where ( ( d.id_estatus = "13" ) and (d.estatus_salida = "0") AND (d.id_descripcion = "'.addslashes($data["val_prod"]).'"))

                group by d.id_composicion)

                union

                (SELECT s.id_composicion
                FROM  '.$this->historico_registros_salidas.' s 
                where ( ( s.estatus_salida = "0" ) AND (s.id_descripcion = "'.addslashes($data["val_prod"]).'"))
                      
                group by s.id_composicion)
              ) r  
               left join '.$this->composiciones.' h on r.id_composicion=h.id
               

               group by r.id_composicion
               order by r.id_composicion ASC

          ');  



            if ( $result->num_rows() > 0 )
               return $result->result();
            else
               return False;
            $result->free_result();
        }   


      public function lista_ancho($data){


          $result = $this->db->query('

             select CONCAT(r.ancho," cm") nombre, r.ancho id from (
                (SELECT e.ancho 
                FROM  '.$this->historico_registros_entradas.' e
                where ( ( e.id_estatus <> "13" ) and (e.estatus_salida = "0")  AND (e.id_descripcion = "'.addslashes($data["val_prod"]).'")
                  AND (e.id_composicion = '.($data["val_comp"]).') )
                group by e.ancho)

                union

                (SELECT d.ancho
                FROM  '.$this->historico_registros_entradas.' d
                where ( ( d.id_estatus = "13" ) and (d.estatus_salida = "0") AND (d.id_descripcion = "'.addslashes($data["val_prod"]).'")
                  AND (d.id_composicion = '.($data["val_comp"]).') )

                group by d.ancho)

                union

                (SELECT s.ancho
                FROM  '.$this->historico_registros_salidas.' s 
                where ( ( s.estatus_salida = "0" ) AND (s.id_descripcion = "'.addslashes($data["val_prod"]).'")
                  AND (s.id_composicion = '.($data["val_comp"]).') )
                      
                group by s.ancho)
              ) r  
               group by r.ancho
               order by r.ancho ASC

          ');  




            
            if ( $result->num_rows() > 0 )
               return $result->result();
            else
               return False;
            $result->free_result();
        }   


        public function lista_colores_dep($data){



          $result = $this->db->query('

             select h.color nombre, h.id, h.hexadecimal_color from (
                (SELECT e.id_color 
                FROM  '.$this->historico_registros_entradas.' e
                where ( ( e.id_estatus <> "13" ) and (e.estatus_salida = "0")  AND (e.id_descripcion = "'.addslashes($data["val_prod"]).'")
                  AND (e.id_composicion = '.($data["val_comp"]).') AND (e.ancho = '.floatval($data["val_ancho"]).')  )
                group by e.id_color)

                union

                (SELECT d.id_color
                FROM  '.$this->historico_registros_entradas.' d
                where ( ( d.id_estatus = "13" ) and (d.estatus_salida = "0") AND (d.id_descripcion = "'.addslashes($data["val_prod"]).'")
                  AND (d.id_composicion = '.($data["val_comp"]).') AND (d.ancho = '.floatval($data["val_ancho"]).')  )

                group by d.id_color)

                union

                (SELECT s.id_color
                FROM  '.$this->historico_registros_salidas.' s 
                where ( ( s.estatus_salida = "0" ) AND (s.id_descripcion = "'.addslashes($data["val_prod"]).'")
                  AND (s.id_composicion = '.($data["val_comp"]).') AND (s.ancho = '.floatval($data["val_ancho"]).')  )
                      
                group by s.id_color)
              ) r  

               left join '.$this->colores.' h on r.id_color=h.id
               
               group by r.id_color
               order by h.color ASC


          ');  


            
            if ( $result->num_rows() > 0 )
               return $result->result();
            else
               return False;
            $result->free_result();
        }    






        public function lista_proveedores($data){

          $result = $this->db->query('

             select h.nombre nombre, h.id from (
                (SELECT e.id_empresa 
                FROM  '.$this->historico_registros_entradas.' e
                where ( ( e.id_estatus <> "13" ) and (e.estatus_salida = "0")  AND (e.id_descripcion = "'.addslashes($data["val_prod"]).'")
                  AND (e.id_composicion = '.($data["val_comp"]).') AND (e.ancho = '.floatval($data["val_ancho"]).')  
                  AND (e.id_color = '.($data["val_color"]).')  )
                group by e.id_empresa)

                union

                (SELECT d.id_empresa
                FROM  '.$this->historico_registros_entradas.' d
                where ( ( d.id_estatus = "13" ) and (d.estatus_salida = "0") AND (d.id_descripcion = "'.addslashes($data["val_prod"]).'")
                  AND (d.id_composicion = '.($data["val_comp"]).') AND (d.ancho = '.floatval($data["val_ancho"]).')  
                  AND (d.id_color = '.($data["val_color"]).')  )

                group by d.id_empresa)

                union

                (SELECT s.id_empresa
                FROM  '.$this->historico_registros_salidas.' s 
                where ( ( s.estatus_salida = "0" ) AND (s.id_descripcion = "'.addslashes($data["val_prod"]).'")
                  AND (s.id_composicion = '.($data["val_comp"]).') AND (s.ancho = '.floatval($data["val_ancho"]).')  
                  AND (s.id_color = '.($data["val_color"]).')  )
                      
                group by s.id_empresa)
              ) r  

               left join '.$this->proveedores.' h on r.id_empresa=h.id
               
               group by r.id_empresa
               order by h.nombre ASC


          ');  

            
            if ( $result->num_rows() > 0 )
               return $result->result();
            else
               return False;
            $result->free_result();
        }            





///////////////////////////////////////de totales//////////////////////////////////



      public function buscador_consulta_totales($data){

          $id_session = $this->db->escape($this->session->userdata('id'));
          $cadena = addslashes($data['search']['value']);


          $inicio = $data['start'];
          $largo = $data['length'];


          $id_descripcion= addslashes($data['id_descripcion']);
          $id_composicion= $data['id_composicion'];
          $ancho= floatval($data['ancho']);
          $id_color= $data['id_color'];
          $id_proveedor= $data['id_proveedor'];
          $id_almacen= $data['id_almacen'];


          $columa_order = $data['order'][0]['column'];
                 $order = $data['order'][0]['dir'];

          switch ($columa_order) {
                   case '0':
                        $columna = 'fecha';
                     break;
                   case '1':
                        $columna = 'totale';
                     break;
                   case '2':
                        $columna = 'totals';
                     break;
                   case '3':
                        $columna = 'totald';
                     break;
               
                   
                   default:
                        $columna = 'fecha';
                     break;
          }            






        $where =' ';
        $where1 =' ';
        $where_e =' ';



          if ( 
            (($id_proveedor!="0") AND ($id_proveedor!="") AND ($id_proveedor!= null))
            and (($id_color!="0") AND ($id_color!="") AND ($id_color!= null))
            and (($ancho!=0) ) 
            and (($id_composicion!="0") AND ($id_composicion!="") AND ($id_composicion!= null))
            and (($id_descripcion!="0") AND ($id_descripcion!="") AND ($id_descripcion!= null)) 

            ) {
              $where .= ' AND ( id_descripcion  =  "'.$id_descripcion.'" ) AND ( id_composicion  =  '.$id_composicion.' ) ';
              $where .= ' AND ( ancho  =  '.$ancho.' ) AND  ( id_color  =  '.$id_color.' )';
              $where .= ' AND ( id_empresa  =  "'.$id_proveedor.'" )';

          }    

          elseif 
           ( 
            (($id_color!="0") AND ($id_color!="") AND ($id_color!= null))
            and (($ancho!=0) ) //(($ancho!="0") AND ($ancho!="") AND ($ancho!= null))
            and (($id_composicion!="0") AND ($id_composicion!="") AND ($id_composicion!= null))
            and (($id_descripcion!="0") AND ($id_descripcion!="") AND ($id_descripcion!= null)) 
           ) {
              $where .= ' AND ( id_descripcion  =  "'.$id_descripcion.'" ) AND ( id_composicion  =  '.$id_composicion.' ) ';
              $where .= ' AND ( ancho  =  '.$ancho.' ) AND  ( id_color  =  '.$id_color.' )';
          }  


          elseif
           ( 
              (($ancho!=0) ) //(($ancho!="0") AND ($ancho!="") AND ($ancho!= null))
            and (($id_composicion!="0") AND ($id_composicion!="") AND ($id_composicion!= null))
            and (($id_descripcion!="0") AND ($id_descripcion!="") AND ($id_descripcion!= null)) 
            ) {
              $where .= ' AND ( id_descripcion  =  "'.$id_descripcion.'" ) AND ( id_composicion  =  '.$id_composicion.' ) ';
              $where .= ' AND ( ancho  =  '.$ancho.' ) ';
          } 


          elseif
           ( 
               (($id_composicion!="0") AND ($id_composicion!="") AND ($id_composicion!= null))
            and (($id_descripcion!="0") AND ($id_descripcion!="") AND ($id_descripcion!= null)) 
            ) {
              $where .= ' AND ( id_descripcion  =  "'.$id_descripcion.'" ) AND ( id_composicion  =  '.$id_composicion.' ) ';
              
          }  


          
          elseif  (($id_descripcion!="0") AND ($id_descripcion!="") AND ($id_descripcion!= null)) {
              $where .= ' AND ( id_descripcion  =  "'.$id_descripcion.'" )';
          }  
          
           $fechas_s = ' ';
           $fechas_ed = ' ';

           
          if  ( ($data['fecha_inicial'] !="") and  ($data['fecha_final'] !="")) {
                          
                           $fecha_inicial = date( 'Y-m-d', strtotime( $data['fecha_inicial'] ));
                           $fecha_final = date( 'Y-m-d', strtotime( $data['fecha_final'] ));
                            
                           //salida                 
                           $fechas_s .= ' AND ( ( DATE_FORMAT((fecha_salida),"%Y-%m-%d")  >=  "'.$fecha_inicial.'" )  AND  ( DATE_FORMAT((fecha_salida),"%Y-%m-%d")  <=  "'.$fecha_final.'" ) )'; 
                          //devolucion y entrada
                           $fechas_ed .= ' AND ( ( DATE_FORMAT((fecha_entrada),"%Y-%m-%d")  >=  "'.$fecha_inicial.'" )  AND  ( DATE_FORMAT((fecha_entrada),"%Y-%m-%d")  <=  "'.$fecha_final.'" ) )'; 

                          
          } else {
           $fechas_s .= ' ';
           $fechas_ed .= ' ';
          }
          



          if ($id_almacen!=0) {
            $id_almacenid = ' and ( id_almacen =  '.$id_almacen.' ) ';  
          } else {
            $id_almacenid = '';
          }


          $where_cadena = '(
              (
                ( totale LIKE  "%'.$cadena.'%" ) OR (totals LIKE  "%'.$cadena.'%") OR
                ( totald LIKE  "%'.$cadena.'%" ) OR (fecha LIKE  "%'.$cadena.'%")
               )
           )';   



      $result = $this->db->query('

       select DATE_FORMAT((r.fecha),"%d-%m-%Y")  as fecha, sum(r.totale) totale , sum(r.totald) totald, sum(r.totals) totals, r.operacion from (
        (SELECT e.fecha_entrada fecha, count(*) totale, 0 totald, 0 totals, "e" operacion
        FROM  '.$this->historico_registros_entradas.' e
        where ( ( e.id_estatus <> "13" ) and (e.estatus_salida = "0") '.$where.$fechas_ed.$id_almacenid.'  )
        group by e.fecha_entrada)

        union

        (SELECT d.fecha_entrada fecha, 0 totale, count(*) totald, 0 totals, "d" operacion
        FROM  '.$this->historico_registros_entradas.' d
        where ( ( d.id_estatus = "13" ) and (d.estatus_salida = "0")  '.$where.$fechas_ed.$id_almacenid.'  )
        group by d.fecha_entrada)

        union

        (SELECT s.fecha_salida fecha, 0 totale, 0 totald, count(*) totals, "s" operacion
        FROM  '.$this->historico_registros_salidas.' s 
        where ( ( s.estatus_salida = "0" ) '.$where.$fechas_s.$id_almacenid.'  )
        group by fecha_salida)
      ) r  



       group by DATE_FORMAT((r.fecha),"%Y-%m-%d")

       having '.$where_cadena.'

       order by '.$columna.' '.$order.'

          limit '.$inicio.','.$largo.'

      ');  




      $totalizacion = $this->db->query('

        select sum(todo.totale) totale , sum(todo.totald) totald, sum(todo.totals) totals from (
          
           select DATE_FORMAT((r.fecha),"%d-%m-%Y")  as fecha, sum(r.totale) totale , sum(r.totald) totald, sum(r.totals) totals, r.operacion from (
            (SELECT e.fecha_entrada fecha, count(*) totale, 0 totald, 0 totals, "e" operacion
            FROM  '.$this->historico_registros_entradas.' e
            where ( ( e.id_estatus <> "13" ) and (e.estatus_salida = "0") '.$where.$fechas_ed.$id_almacenid.'  )
            group by e.fecha_entrada)

            union

            (SELECT d.fecha_entrada fecha, 0 totale, count(*) totald, 0 totals, "d" operacion
            FROM  '.$this->historico_registros_entradas.' d
            where ( ( d.id_estatus = "13" ) and (d.estatus_salida = "0")  '.$where.$fechas_ed.$id_almacenid.'  )
            group by d.fecha_entrada)

            union

            (SELECT s.fecha_salida fecha, 0 totale, 0 totald, count(*) totals, "s" operacion
            FROM  '.$this->historico_registros_salidas.' s 
            where ( ( s.estatus_salida = "0" ) '.$where.$fechas_s.$id_almacenid.'  )
            group by fecha_salida)
          ) r  

           group by DATE_FORMAT((r.fecha),"%Y-%m-%d")

           having '.$where_cadena.'

           order by '.$columna.' '.$order.'
         ) todo
          

      ');  




      $totalizacion_pagina = $this->db->query('

        select sum(todo.totale) totale , sum(todo.totald) totald, sum(todo.totals) totals from (
          
           select DATE_FORMAT((r.fecha),"%d-%m-%Y")  as fecha, sum(r.totale) totale , sum(r.totald) totald, sum(r.totals) totals, r.operacion from (
            (SELECT e.fecha_entrada fecha, count(*) totale, 0 totald, 0 totals, "e" operacion
            FROM  '.$this->historico_registros_entradas.' e
            where ( ( e.id_estatus <> "13" ) and (e.estatus_salida = "0") '.$where.$fechas_ed.$id_almacenid.'  )
            group by e.fecha_entrada)

            union

            (SELECT d.fecha_entrada fecha, 0 totale, count(*) totald, 0 totals, "d" operacion
            FROM  '.$this->historico_registros_entradas.' d
            where ( ( d.id_estatus = "13" ) and (d.estatus_salida = "0")  '.$where.$fechas_ed.$id_almacenid.'  )
            group by d.fecha_entrada)

            union

            (SELECT s.fecha_salida fecha, 0 totale, 0 totald, count(*) totals, "s" operacion
            FROM  '.$this->historico_registros_salidas.' s 
            where ( ( s.estatus_salida = "0" ) '.$where.$fechas_s.$id_almacenid.'  )
            group by fecha_salida)
          ) r  

           group by DATE_FORMAT((r.fecha),"%Y-%m-%d")

           having '.$where_cadena.'

           order by '.$columna.' '.$order.'
            limit '.$inicio.','.$largo.'

         ) todo
          

      ');  



      $totales = $this->db->query('

       select count(*) suma from (
        (SELECT e.fecha_entrada fecha, count(*) totale, 0 totald, 0 totals, "e" operacion
        FROM  '.$this->historico_registros_entradas.' e
        where ( ( e.id_estatus <> "13" ) and (e.estatus_salida = "0") '.$where.$fechas_ed.$id_almacenid.'  )
        group by e.fecha_entrada)

        union

        (SELECT d.fecha_entrada fecha, 0 totale, count(*) totald, 0 totals, "d" operacion
        FROM  '.$this->historico_registros_entradas.' d
        where ( ( d.id_estatus = "13" ) and (d.estatus_salida = "0")  '.$where.$fechas_ed.$id_almacenid.'  )
        group by d.fecha_entrada)

        union

        (SELECT s.fecha_salida fecha, 0 totale, 0 totald, count(*) totals, "s" operacion
        FROM  '.$this->historico_registros_salidas.' s 
        where ( ( s.estatus_salida = "0" ) '.$where.$fechas_s.$id_almacenid.'  )
        group by fecha_salida)
      ) r  
       group by DATE_FORMAT((r.fecha),"%Y-%m-%d")
       order by '.$columna.' '.$order.'
      ');  



      $subtotal = $this->db->query('

       select DATE_FORMAT((r.fecha),"%d-%m-%Y")  as fecha, sum(r.totale) totale , sum(r.totald) totald, sum(r.totals) totals, r.operacion from (
        (SELECT e.fecha_entrada fecha, count(*) totale, 0 totald, 0 totals, "e" operacion
        FROM  '.$this->historico_registros_entradas.' e
        where ( ( e.id_estatus <> "13" ) and (e.estatus_salida = "0") '.$where.$fechas_ed.$id_almacenid.'  )
        group by e.fecha_entrada)

        union

        (SELECT d.fecha_entrada fecha, 0 totale, count(*) totald, 0 totals, "d" operacion
        FROM  '.$this->historico_registros_entradas.' d
        where ( ( d.id_estatus = "13" ) and (d.estatus_salida = "0")  '.$where.$fechas_ed.$id_almacenid.'  )
        group by d.fecha_entrada)

        union

        (SELECT s.fecha_salida fecha, 0 totale, 0 totald, count(*) totals, "s" operacion
        FROM  '.$this->historico_registros_salidas.' s 
        where ( ( s.estatus_salida = "0" ) '.$where.$fechas_s.$id_almacenid.'  )
        group by fecha_salida)
      ) r  
       group by DATE_FORMAT((r.fecha),"%Y-%m-%d")
       having '.$where_cadena.'
       order by '.$columna.' '.$order.'
      ');  

              if ( $result->num_rows() > 0 ) {


             
                  foreach ($result->result() as $row) {

                            $dato[]= array(
                                      0=>$row->fecha,
                                      1=>$row->totale,
                                      2=>$row->totals,
                                      3=>$row->totald,
                                      4=>$row->operacion, 
                                    );
                      }

                      return json_encode ( array(
                        "draw"            => intval( $data['draw'] ),
                        "recordsTotal"    => $totales->num_rows(), //intval( self::total_consulta_totales($where_total) ), 
                        "recordsFiltered" =>  $subtotal->num_rows(), // $registros_filtrados, 
                        "data"            =>  $dato,
                        "totales"            =>  
                                                array("totale"=>intval( $totalizacion->row()->totale ),
                                                      "totals"=>intval( $totalizacion->row()->totals ),
                                                      "totald"=>intval( $totalizacion->row()->totald ),
                                                ),

                        "subtotales"            =>  
                                                array("totale"=>intval( $totalizacion_pagina->row()->totale ),
                                                      "totals"=>intval( $totalizacion_pagina->row()->totals ),
                                                      "totald"=>intval( $totalizacion_pagina->row()->totald ),
                                                )      

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

/////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////fin de totales//////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////      
/////////////////////////////////////////////////////////////////////////////////////      

        public function total_consulta_proveedor($where_total){
              $id_session = $this->session->userdata('id');

              $this->db->from($this->productos.' as p');
              $this->db->join($this->historico_registros_entradas.' As he', 'he.referencia = p.referencia','RIGHT');
              $this->db->join($this->colores.' As c', 'p.id_color = c.id','LEFT');
              $this->db->join($this->proveedores.' As prov' , 'prov.id = he.id_empresa','LEFT');

              if ($where_total!='') {
                  $this->db->where($where_total);
              }

              $this->db->group_by("p.descripcion,p.referencia, p.minimo, p.imagen, c.color");

              $result = $this->db->get();

              if ( $result->num_rows() > 0 )
                 return $result->num_rows(); // $result->result();
              else
                 return 0;
              $result->free_result();

       }     



        public function total_consulta_producto($where_total){
              $id_session = $this->session->userdata('id');

              $this->db->from($this->proveedores.' as p');
              $this->db->join($this->historico_registros_entradas.' As he', 'he.id_empresa = p.id');          

              if ($where_total!='') {
                  $this->db->where($where_total);
              }

              $this->db->group_by("p.codigo,p.nombre, p.telefono, p.coleccion_id_actividad");

              $result = $this->db->get();

              if ( $result->num_rows() > 0 )
                 return $result->num_rows(); // $result->result();
              else
                 return 0;
              $result->free_result();

       }     


 
      public function lista_colores($data){
         

          $this->db->distinct();
          $this->db->select("c.color nombre", FALSE);  
          $this->db->select("c.id", FALSE);  
          $this->db->select("c.hexadecimal_color", FALSE);  
          

          $this->db->from($this->productos.' as p');
          $this->db->join($this->historico_registros_entradas.' As he', 'he.referencia = p.referencia','RIGHT');
          $this->db->join($this->colores.' As c', 'p.id_color = c.id','LEFT');

          $this->db->where('p.descripcion', $data['valor']);

          //$this->db->group_by("c.color,c.id, c.hexadecimal_color");
          $this->db->group_by("c.id");

          $this->db->order_by('c.color', 'asc'); 
            


            $result = $this->db->get();
            
            if ( $result->num_rows() > 0 )
               return $result->result();
            else
               return False;
            $result->free_result();
        }      




      public function check_existente_proveedor_entrada($descripcion){


            $this->db->select("pro.id", FALSE);         
            $this->db->from($this->proveedores.' as pro ');


            $where = '(
                        (
                          ( pro.nombre =  "'.$descripcion.'" ) 
                          
                         )

              )';   
  
            $this->db->where($where);


            $login = $this->db->get();

 
            if ($login->num_rows() > 0) {
                $fila = $login->row(); 
                return $fila->id;
            }    
            else
                return 0;
            $login->free_result();
    }     



      public function buscador_consulta_producto($data){


          $id_descripcion= addslashes($data['producto']);
          $id_color= $data['id_color'];


          
          $cadena = addslashes($data['search']['value']);
          $inicio = $data['start'];
          $largo = $data['length'];
          $columa_order = $data['order'][0]['column'];
                 $order = $data['order'][0]['dir'];

          switch ($columa_order) {
                   case '0':
                        $columna = 'p.codigo';
                     break;
                   case '1':
                        $columna = 'p.nombre';
                     break;
                   case '2':
                        $columna = 'p.telefono';
                     break;
                   case '3':
                        $columna = 'p.coleccion_id_actividad';
                     break;
               
                   
                   default:
                        $columna = 'p.codigo';
                     break;
                 }                 

                                      
                 
          $id_session = $this->db->escape($this->session->userdata('id'));

          $this->db->select("SQL_CALC_FOUND_ROWS(p.id)", FALSE); //
            //p.uid,  p.direccion,p.coleccion_id_actividad, p.id_usuario, p.fecha_mac
          $this->db->select('p.id,  p.codigo, p.nombre,  p.telefono'); 
          $this->db->from($this->proveedores.' as p');
          $this->db->join($this->historico_registros_entradas.' As he', 'he.id_empresa = p.id');          

          //filtro de busqueda

            $where_filtro = '';
            $where_total = '';
          if 
           ( (($id_color!="0") AND ($id_color!="") AND ($id_color!= null))
            and (($id_descripcion!="0") AND ($id_descripcion!="") AND ($id_descripcion!= null)) 
            ) {
              $where_filtro = ' AND ( he.id_descripcion  =  "'.$id_descripcion.'" ) AND  ( he.id_color  =  '.$id_color.' )';
              $where_total = '(he.id_descripcion  =  "'.$id_descripcion.'" ) AND  ( he.id_color  =  '.$id_color.' )';
          }  elseif  (($id_descripcion!="0") AND ($id_descripcion!="") AND ($id_descripcion!= null)) {
              $where_filtro  = ' AND ( he.id_descripcion  =  "'.$id_descripcion.'" )';
              $where_total  = '(he.id_descripcion  =  "'.$id_descripcion.'" )';
          }  




      
          $where = '(
                      (
                        ( p.codigo LIKE  "%'.$cadena.'%" ) OR (p.nombre LIKE  "%'.$cadena.'%") OR
                        ( p.telefono LIKE  "%'.$cadena.'%" ) 
                        
                       )

               )';   
  
          $this->db->where($where.$where_filtro);

          //ordenacion
          $this->db->order_by($columna, $order); 

          $this->db->group_by("p.codigo"); //,p.nombre, p.telefono, p.coleccion_id_actividad

          //paginacion
          $this->db->limit($largo,$inicio); 


          $result = $this->db->get();




              if ( $result->num_rows() > 0 ) {



                    $cantidad_consulta = $this->db->query("SELECT FOUND_ROWS() as cantidad");
                    $found_rows = $cantidad_consulta->row(); 
                    $registros_filtrados =  ( (int) $found_rows->cantidad);

              
                  foreach ($result->result() as $row) {

                            $dato[]= array(
                                      0=>$row->codigo,
                                      1=>$row->nombre,
                                      2=>$row->telefono,
                                      3=>'Proveedor', //$row->coleccion_id_actividad,
                                      4=>$row->id, 
                                    );
                      }



                      return json_encode ( array(
                        "draw"            => intval( $data['draw'] ),
                        "recordsTotal"    => $registros_filtrados,  //intval( self::total_consulta_producto($where_total) ), 
                        "recordsFiltered" =>   $registros_filtrados, 
                        "data"            =>  $dato 
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





      public function buscador_consulta_proveedor($data){

          $id_empresa= addslashes($data['proveedor']);

          $empre_id = self::check_existente_proveedor_entrada($id_empresa); 

          $cadena = addslashes($data['search']['value']);
          $inicio = $data['start'];
          $largo = $data['length'];
          $columa_order = $data['order'][0]['column'];
                 $order = $data['order'][0]['dir'];

          switch ($columa_order) {
                   case '0':
                        $columna = 'p.descripcion';
                     break;
                   case '1':
                        $columna = 'p.referencia';
                     break;
                   case '2':
                        $columna = 'p.minimo';
                     break;
                   case '3':
                        $columna = 'p.imagen';
                     break;
                   case '4':
                        $columna = 'c.color';
                     break;
                
                   
                   default:
                        $columna = 'p.descripcion';
                     break;
                 }                 

                                      
                 
          $id_session = $this->db->escape($this->session->userdata('id'));


         

          $this->db->select("SQL_CALC_FOUND_ROWS(p.id)", FALSE); //
          //p.uid, ,  p.comentario,  p.id_composicion, p.id_color,p.id_calidad,,p.precio,p.ancho, p.id_usuario, p.fecha_mac,
          //$this->db->select('prov.nombre proveedor, he.id_empresa');
          $this->db->select('p.id,  p.referencia');
          $this->db->select('p.descripcion, p.minimo, p.imagen');
          $this->db->select('c.hexadecimal_color,c.color nombre_color');

          $this->db->from($this->productos.' as p');
          $this->db->join($this->historico_registros_entradas.' As he', 'he.referencia = p.referencia'); //,'RIGHT'
          $this->db->join($this->colores.' As c', 'p.id_color = c.id'); // ,'LEFT'
          $this->db->join($this->proveedores.' As prov' , 'prov.id = he.id_empresa'); //,'LEFT'

          //(SELECT DISTINCT referencia FROM  "inven_historico_registros_entradas")
          //filtro de busqueda


             $donde = '';
             $where_total = '';
             if ($id_empresa!='') {
                       $donde .= '( prov.id  =  '.$empre_id.' ) AND ';
                       $where_total .= '( prov.id  =  '.$empre_id.' ) ';

            } else  {
               $donde .= '';
               $where_total .= '';
               
            }
      
          $where = '(
                      '.$donde.'
                      (
                        ( p.descripcion LIKE  "%'.$cadena.'%" ) OR (p.referencia LIKE  "%'.$cadena.'%") OR (c.color LIKE  "%'.$cadena.'%")  OR
                        ( p.minimo LIKE  "%'.$cadena.'%" ) 
                        
                       )

            )';   
  
          $this->db->where($where);

          //ordenacion
          $this->db->order_by($columna, $order); 

          //grupo

  
          $this->db->group_by("p.descripcion,p.referencia, p.minimo"); //, p.imagen, c.color


          //paginacion
          $this->db->limit($largo,$inicio); 


          $result = $this->db->get();

              if ( $result->num_rows() > 0 ) {



                    $cantidad_consulta = $this->db->query("SELECT FOUND_ROWS() as cantidad");
                    $found_rows = $cantidad_consulta->row(); 
                    $registros_filtrados =  ( (int) $found_rows->cantidad);

                  $retorno= " ";  
                  foreach ($result->result() as $row) {

                        $nombre_fichero ='uploads/productos/thumbnail/300X300/'.substr($row->imagen,0,strrpos($row->imagen,".")).'_thumb'.substr($row->imagen,strrpos($row->imagen,"."));
                        if (file_exists($nombre_fichero)) {
                            $imagen ='<img src="'.base_url().$nombre_fichero.'" border="0" width="75" height="75">';

                        } else {
                            $imagen ='<img src="img/sinimagen.png" border="0" width="75" height="75">';
                        }                    

                            $dato[]= array(
                                      
                                      0=>$row->descripcion,
                                      1=>$row->referencia,
                                      2=>$row->minimo,
                                      3=> $imagen,
                                      //'<img src="'.base_url().'uploads/productos/thumbnail/300X300/'.substr($row->imagen,0,strrpos($row->imagen,'.')).'_thumb'.substr($row->imagen,strrpos($row->imagen,'.')).'" border="0" width="75" height="75">',

                                      4=>$row->nombre_color.
                                        '<div style="background-color:#'.$row->hexadecimal_color.';display:block;width:15px;height:15px;margin:0 auto;"></div>',
                                       5=>$row->id, 
                                       6=>self::referencias_en_uso($row->referencia),
                                    );
                      }



                      return json_encode ( array(
                        "draw"            => intval( $data['draw'] ),
                        "recordsTotal"    => $registros_filtrados,  //intval( self::total_consulta_proveedor($where_total) ), 
                        "recordsFiltered" =>   $registros_filtrados, 
                        "data"            =>  $dato 
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
      

 public function referencias_en_uso($referencia) {

          $result = $this->db->query("
            select distinct r.referencia from (

            (select distinct referencia from ".$this->registros_temporales.")
              union   
            (select distinct referencia from ".$this->registros_cambios.")
              union   

            (select distinct referencia from ".$this->registros_entradas.")
              union   

            (select distinct referencia from ".$this->registros_salidas.")
              union   

            (select distinct referencia from ".$this->historico_registros_entradas.")
              union   

            (select distinct referencia from ".$this->historico_registros_salidas.")
              ) r 
           where r.referencia='".$referencia."'                                

          "
          );  

           if ( $result->num_rows() > 0 ) {
                  return 1;
              } else 
                  return 0;
            $result->free_result();                 

      }    






  } 

?>

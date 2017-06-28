<?php 
class Exportar_model extends CI_Model
{
    function __construct()
    {
        
        parent::__construct();
            $this->load->database("default");
            $this->key_hash    = $_SERVER['HASH_ENCRYPT'];
            $this->timezone    = 'UM1';

            $this->registros_temporales               = $this->db->dbprefix('temporal_registros');
                $this->productos               = $this->db->dbprefix('catalogo_productos');
                  $this->proveedores             = $this->db->dbprefix('catalogo_empresas');
                  $this->unidades_medidas        = $this->db->dbprefix('catalogo_unidades_medidas');

                  $this->operaciones             = $this->db->dbprefix('catalogo_operaciones');
                  $this->movimientos               = $this->db->dbprefix('movimientos');
                  $this->colores                 = $this->db->dbprefix('catalogo_colores');
                  $this->composiciones                 = $this->db->dbprefix('catalogo_composicion');
                  $this->unidades_medidas        = $this->db->dbprefix('catalogo_unidades_medidas');
                  $this->registros               = $this->db->dbprefix('registros_entradas');
                  $this->registros_salidas       = $this->db->dbprefix('registros_salidas');
                  $this->cargadores             = $this->db->dbprefix('catalogo_cargador');

                  $this->calidades                 = $this->db->dbprefix('catalogo_calidad');
                  $this->almacenes                 = $this->db->dbprefix('catalogo_almacenes');

                  $this->historico_registros_entradas = $this->db->dbprefix('historico_registros_entradas');
                  $this->historico_registros_salidas = $this->db->dbprefix('historico_registros_salidas');
                  $this->tipos_facturas                         = $this->db->dbprefix('catalogo_tipos_facturas');

                    //usuarios
                  $this->usuarios    = $this->db->dbprefix('usuarios');


    }



      public function buscador_consulta_totales($data){

          $id_session = $this->db->escape($this->session->userdata('id'));
          $cadena = addslashes($data['busqueda']);



          $id_descripcion= addslashes($data['id_descripcion']);
          $id_composicion= $data['id_composicion'];
          $ancho= floatval($data['ancho']);
          $id_color= $data['id_color'];
          $id_proveedor= $data['id_proveedor'];
          $id_almacen= $data['id_almacen'];






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
                ( entradas LIKE  "%'.$cadena.'%" ) OR (salidas LIKE  "%'.$cadena.'%") OR
                ( devoluciones LIKE  "%'.$cadena.'%" ) OR (fecha LIKE  "%'.$cadena.'%")
               )
           )';   



      $result = $this->db->query('

       select DATE_FORMAT((r.fecha),"%d-%m-%Y")  as fecha, sum(r.totale) Entradas ,  sum(r.totals) Salidas, sum(r.totald) Devoluciones from (
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

       

          

      ');  
//order by '.$columna.' '.$order.'



            if ( $result->num_rows() > 0 )
               return $result->result();
            else
               return False;
            $result->free_result();                              
              

      }  

      //////////////////////////////




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



    public function exportar_reportes_costo($data){


          //$cadena = addslashes($data['search']['value']);

          $cadena = addslashes($data['busqueda']);
          
          $id_estatus= $data['id_estatus'];
          $id_empresa= addslashes($data['proveedor']);
          $id_almacen= $data['id_almacen'];

          $factura_reporte = addslashes($data['factura_reporte']);


       

          $fechas = ' ';
          if  ( ($data['fecha_inicial'] !="") and  ($data['fecha_final'] !="")) {
                           $fecha_inicial = date( 'Y-m-d', strtotime( $data['fecha_inicial'] ));
                           $fecha_final = date( 'Y-m-d', strtotime( $data['fecha_final'] ));
                          
                          
                            $fechas .= ' AND ( ( DATE_FORMAT((m.fecha_entrada),"%Y-%m-%d")  >=  "'.$fecha_inicial.'" )  AND  ( DATE_FORMAT((m.fecha_entrada),"%Y-%m-%d")  <=  "'.$fecha_final.'" ) )'; 
                          

          } else {
           $fechas .= ' ';
          }



          $donde = '';
         if ($id_empresa!="") {
              $id_empre =  self::check_existente_proveedor_entrada($id_empresa);

                if (!($id_empre)) {
                  $id_empre =0;
                }



                  $donde .= ' AND ( m.id_empresa  =  '.$id_empre.' ) ';


        } else 
        {
           $donde .= ' ';
        }

         if ($factura_reporte!="") {
            $donde .= ' AND ( m.factura  =  "'.$factura_reporte.'" ) ';
        } 


          //productos
          $id_descripcion= addslashes($data['id_descripcion']);
          $id_color= $data['id_color'];
          $id_composicion= $data['id_composicion'];
          $id_calidad= $data['id_calidad'];


          $id_session = $this->db->escape($this->session->userdata('id'));

          
          $this->db->select('m.codigo, m.id_descripcion,prod.codigo_contable codigo_alterno');
          $this->db->select('c.color');
          $this->db->select('CONCAT(m.cantidad_um," ",u.medida) as cantidad', false);
          $this->db->select('CONCAT(m.ancho," ","cm") as ancho', false);
          
           $this->db->select("m.precio", FALSE);
           $this->db->select("(m.precio*m.cantidad_um) as subtotal", FALSE);
           $this->db->select("((m.precio*m.cantidad_um*m.iva))/100 as sum_iva", FALSE);
           $this->db->select("(m.precio*m.cantidad_um)+((m.precio*m.cantidad_um*m.iva))/100 as total", FALSE);


          $this->db->select("tff.tipo_factura t_factura");  

          $this->db->select('m.movimiento');
          $this->db->select('p.nombre');
          $this->db->select('DATE_FORMAT(m.fecha_entrada,"%d/%m/%Y") as ppp',false);
            


          $this->db->from($this->registros.' as m');
          $this->db->join($this->almacenes.' As a' , 'a.id = m.id_almacen AND a.activo=1');                     
          $this->db->join($this->productos.' As prod' , 'prod.referencia = m.referencia','LEFT');
          $this->db->join($this->colores.' As c' , 'c.id = m.id_color','LEFT');
          $this->db->join($this->unidades_medidas.' As u' , 'u.id = m.id_medida','LEFT');
          $this->db->join($this->proveedores.' As p' , 'p.id = m.id_empresa','LEFT');
          
          
          $this->db->join($this->tipos_facturas.' As tff' , 'tff.id = m.id_factura','LEFT');

          

            $cond= ' (p.nombre LIKE  "%'.$cadena.'%") OR  (CONCAT(m.id_lote,"-",m.consecutivo) LIKE  "%'.$cadena.'%") ';

          if ($id_estatus!=0) {
            $estatus_idid = ' and ( m.id_estatus =  '.$id_estatus.' ) ';  
          } else {
            $estatus_idid = '';
          }

          if ($id_almacen!=0) {
            $id_almacenid = ' and ( m.id_almacen =  '.$id_almacen.' ) ';  
          } else {
            $id_almacenid = '';
          }

          

          $where = '(
                      (
                         ( m.estatus_salida = "0" ) '.$estatus_idid.$id_almacenid.' 
                      ) 
                       AND
                      ( ( m.num_partida LIKE  "%'.$cadena.'%" ) OR   
                        ( m.codigo LIKE  "%'.$cadena.'%" ) OR (m.id_descripcion LIKE  "%'.$cadena.'%") OR (c.color LIKE  "%'.$cadena.'%")  OR
                        ( CONCAT(m.cantidad_um," ",u.medida) LIKE  "%'.$cadena.'%" ) OR (CONCAT(m.ancho," cm") LIKE  "%'.$cadena.'%")  OR
                        (m.factura LIKE  "%'.$cadena.'%") OR ( m.movimiento LIKE  "%'.$cadena.'%" ) OR ((DATE_FORMAT((m.fecha_entrada),"%d-%m-%Y") ) LIKE  "%'.$cadena.'%") OR '.$cond.' 
                       )

            ) ' ;                     
          

          $where_total = '( ( m.estatus_salida = "0" )  '.$estatus_idid.$id_almacenid.'  )';


          if  (($id_calidad!="0") AND ($id_calidad!="") AND ($id_calidad!= null)) {
             $where.= (($where!="") ? " and " : "") . "( m.id_calidad  =  ".$id_calidad." )";
             $where_total.= (($where_total!="") ? " and " : "") . "( m.id_calidad  =  ".$id_calidad." )";
          }     

           if  (($data['id_factura_costo']!="0") AND ($data['id_factura_costo']!="") AND ($data['id_factura_costo']!= null)) {
               $where.= (($where!="") ? " and " : "") . "( m.id_factura  =  ".$data['id_factura_costo']." )";
               $where_total.= (($where_total!="") ? " and " : "") . "( m.id_factura  =  ".$data['id_factura_costo']." )";
            } 
          
          if (($id_composicion!="0") AND ($id_composicion!="") AND ($id_composicion!= null)) {
              $where.= (($where!="") ? " and " : "") . "( m.id_composicion  =  ".$id_composicion." ) ";
              $where_total.= (($where_total!="") ? " and " : "") . "( m.id_composicion  =  ".$id_composicion." ) ";
          } 
          if  (($id_color!="0") AND ($id_color!="") AND ($id_color!= null)) {
             $where.= (($where!="") ?  " and " : "") . "( m.id_color  =  ".$id_color." )";
             $where_total.= (($where_total!="") ?  " and " : "") . "( m.id_color  =  ".$id_color." )";
          }

          if (($id_descripcion!="0") AND ($id_descripcion!="") AND ($id_descripcion!= null))  {                
          //if ( ($data['val_prod_id'] !="")  && ($data['val_prod_id'] !="0") ) {
              $where.= (($where!="") ? " and " : "") . "( m.id_descripcion  =  '".$id_descripcion."' )";
              $where_total.= (($where_total!="") ? " and " : "") . "( m.id_descripcion  =  '".$id_descripcion."' )";
          }                


          $where_total.= $donde.$fechas; //$donde.



          $this->db->where($where.$donde.$fechas); //
    
       
          //ordenacion

          //$this->db->order_by('m.factura', 'asc'); 

          $result = $this->db->get();


            if ( $result->num_rows() > 0 )
               return $result->result();
            else
               return False;
            $result->free_result();
      }  





    public function exportar_entrada_devolucion($data){

          $cadena = addslashes($data['busqueda']);
          $inicio = 0; //$data['start'];
          $largo = 10000; //$data['length'];

          $estatus= $data['extra_search'];
          $id_estatus= $data['id_estatus'];
          $id_empresa= addslashes($data['proveedor']);

          $factura_reporte = addslashes($data['factura_reporte']);
          $id_almacen= $data['id_almacen'];


          

          $fechas = ' ';
          if  ( ($data['fecha_inicial'] !="") and  ($data['fecha_final'] !="")) {
                           $fecha_inicial = date( 'Y-m-d', strtotime( $data['fecha_inicial'] ));
                           $fecha_final = date( 'Y-m-d', strtotime( $data['fecha_final'] ));
                          
                            $fechas .= ' AND ( ( DATE_FORMAT((m.fecha_entrada),"%Y-%m-%d")  >=  "'.$fecha_inicial.'" )  AND  ( DATE_FORMAT((m.fecha_entrada),"%Y-%m-%d")  <=  "'.$fecha_final.'" ) )'; 

          } else {
           $fechas .= ' ';
          }


            $donde = '';
             if ($id_empresa!="") {
                  $id_empre =  self::check_existente_proveedor_entrada($id_empresa);

                    if (!($id_empre)) {
                      $id_empre =0;
                    }                  

                      $donde .= ' AND ( m.id_empresa  =  '.$id_empre.' ) ';

            } else 
            {
               $donde .= ' ';
            }



         if ($factura_reporte!="") {
            $donde .= ' AND ( m.factura  =  "'.$factura_reporte.'" ) ';
        } 






          //productos
          //$id_descripcion= $data['id_descripcion'];
          $id_descripcion= addslashes($data['id_descripcion']);
          $id_color= $data['id_color'];
          $id_composicion= $data['id_composicion'];
          $id_calidad= $data['id_calidad'];

//
          $id_session = $this->db->escape($this->session->userdata('id'));

          $this->db->select('m.codigo, m.id_descripcion producto,prod.codigo_contable codigo_alterno');
          $this->db->select('c.color');
          $this->db->select('CONCAT(m.cantidad_um, u.medida) cantidad', false);
          $this->db->select('CONCAT(m.ancho, " cm") ancho', false);
          $this->db->select('m.movimiento');

          if ($estatus!="apartado") { //existencia
              $this->db->select('p.nombre Proveedor');
              $this->db->select('CONCAT(m.id_lote,"-",m.consecutivo) lote', false);
              $this->db->select('DATE_FORMAT((m.fecha_entrada),"%d-%m-%Y") ingreso', false); //'d-m-Y'
          } else {  //apartado        "dependencia"

                $this->db->select('
                          CASE m.id_apartado
                            WHEN "1" THEN   CONCAT (pr.nombre," (Vendedor)")
                             WHEN "2" THEN  CONCAT (pr.nombre, " (Vendedor)")
                             WHEN "3" THEN  CONCAT (pr.nombre, " (Vendedor)")
                             
                             WHEN "4" THEN  CONCAT (pr.nombre, " (Tienda)")
                             WHEN "5" THEN  CONCAT (pr.nombre, " (Tienda)")
                             WHEN "6" THEN  CONCAT (pr.nombre, " (Tienda)")

                             ELSE "No Apartado"
                          END AS dependencia
              ',False);



                $this->db->select('
                              CASE m.id_apartado
                                WHEN "1" THEN "Apartado Individual"
                                 WHEN "2" THEN "Apartado Confirmado"
                                 WHEN "3" THEN "Disponibilidad Salida"
                                 
                                 WHEN "4" THEN "Apartado Individual"
                                 WHEN "5" THEN "Apartado Confirmado"
                                 WHEN "6" THEN "Disponibilidad Salida"

                                 ELSE "No Apartado"
                              END AS tipo_apartado
                  ',False);
                  $this->db->select('DATE_FORMAT((m.fecha_apartado),"%d-%m-%Y") fecha', false); //'d-m-Y'

          }    

          if (($data['configuracion']->activo==1)) {  
            $this->db->select('m.factura');
          }  
          $this->db->select('m.num_partida'); //'d-m-Y'

          $this->db->select("a.almacen");

          $this->db->from($this->historico_registros_entradas.' as m');
          $this->db->join($this->almacenes.' As a' , 'a.id = m.id_almacen AND a.activo=1');
          $this->db->join($this->productos.' As prod' , 'prod.referencia = m.referencia','LEFT');
          $this->db->join($this->colores.' As c' , 'c.id = m.id_color','LEFT');
          $this->db->join($this->unidades_medidas.' As u' , 'u.id = m.id_medida','LEFT');
          $this->db->join($this->proveedores.' As p' , 'p.id = m.id_empresa','LEFT');
                               

          $cond= ' (p.nombre LIKE  "%'.$cadena.'%") OR  (CONCAT(m.id_lote,"-",m.consecutivo) LIKE  "%'.$cadena.'%") ';//' OR (m.consecutivo LIKE  "%'.$cadena.'%") ';


          if ($estatus=="devolucion") {
               $estatus_idid = ' AND ( m.id_estatus = "13" ) ' ;   
               $estatus_idid = ' AND ( m.id_estatus = "13" ) ' ;   
          }  else {

              if ($id_estatus!=0) {
                $estatus_idid = ' and ( m.id_estatus =  '.$id_estatus.' ) ';  
              } else {
                $estatus_idid = '';
              }

          }  


          if ($id_almacen!=0) {
            $id_almacenid = ' and ( m.id_almacen =  '.$id_almacen.' ) ';  
          } else {
            $id_almacenid = '';
          }


           if ($data['id_factura']!=0) {
              $id_facturaid = ' AND ( m.id_factura =  '.$data['id_factura'].' ) ';  
          } else {
              $id_facturaid = '';
          } 

        $where = '(
                      (
                         ( m.estatus_salida = "0" ) '.$estatus_idid.$id_almacenid.$id_facturaid.' 
                      ) 
                       AND
                      (
                        ( m.codigo LIKE  "%'.$cadena.'%" ) OR (m.id_descripcion LIKE  "%'.$cadena.'%") OR (c.color LIKE  "%'.$cadena.'%")  OR
                        ( CONCAT(m.cantidad_um," ",u.medida) LIKE  "%'.$cadena.'%" ) OR (CONCAT(m.ancho," cm") LIKE  "%'.$cadena.'%")  OR
                        (m.factura LIKE  "%'.$cadena.'%") OR ( m.movimiento LIKE  "%'.$cadena.'%" ) OR ((DATE_FORMAT((m.fecha_entrada),"%d-%m-%Y") ) LIKE  "%'.$cadena.'%") OR '.$cond.' 
                       )

            ) ' ;                     
          

          $where_total = '( ( m.estatus_salida = "0" )  '.$estatus_idid.$id_almacenid.$id_facturaid.'  )';

          if ($estatus=="devolucion") {
              $where .= ' AND ( m.id_estatus = "13" ) ' ;   
              $where_total .= ' AND ( m.id_estatus = "13" ) ' ;   
          }    
          
          //OJO
          if ($estatus!="existencia") {
              $where .= ' AND ( m.id_apartado = 0 ) ' ;   
              $where_total .= ' AND ( m.id_apartado = 0 ) ' ;   
          } 



          if  (($id_calidad!="0") AND ($id_calidad!="") AND ($id_calidad!= null)) {
             $where.= (($where!="") ? " and " : "") . "( m.id_calidad  =  ".$id_calidad." )";
             $where_total.= (($where_total!="") ? " and " : "") . "( m.id_calidad  =  ".$id_calidad." )";
          }     

          if (($id_composicion!="0") AND ($id_composicion!="") AND ($id_composicion!= null)) {
              $where.= (($where!="") ? " and " : "") . "( m.id_composicion  =  ".$id_composicion." ) ";
              $where_total.= (($where_total!="") ? " and " : "") . "( m.id_composicion  =  ".$id_composicion." ) ";
          } 


          if  (($id_color!="0") AND ($id_color!="") AND ($id_color!= null)) {
             $where.= (($where!="") ?  " and " : "") . "( m.id_color  =  ".$id_color." )";
             $where_total.= (($where_total!="") ?  " and " : "") . "( m.id_color  =  ".$id_color." )";
          }

          //if (($id_descripcion!="0") AND ($id_descripcion!="") AND ($id_descripcion!= null))  {                
          if ( ($data['val_prod_id'] !="")  && ($data['val_prod_id'] !="0") ) {
              $where.= (($where!="") ? " and " : "") . "( m.id_descripcion  =  '".$id_descripcion."' )";
              $where_total.= (($where_total!="") ? " and " : "") . "( m.id_descripcion  =  '".$id_descripcion."' )";
          }                                    


          $where_total.= $donde.$fechas; //$donde.



          $this->db->where($where.$donde.$fechas); //
    
        

          //ordenacion

         // $this->db->order_by('m.factura', 'asc'); 

          $result = $this->db->get();


            if ( $result->num_rows() > 0 )
               return $result->result();
            else
               return False;
            $result->free_result();



      }  




    public function totales_entradas($data){

          $cadena = addslashes($data['busqueda']);
          $inicio = 0; //$data['start'];
          $largo = 10000; //$data['length'];

          $estatus= $data['extra_search'];
          $id_estatus= $data['id_estatus'];
          $id_empresa= addslashes($data['proveedor']);

          $factura_reporte = addslashes($data['factura_reporte']);


     //////////


          $fechas = ' ';
          if  ( ($data['fecha_inicial'] !="") and  ($data['fecha_final'] !="")) {
                           $fecha_inicial = date( 'Y-m-d', strtotime( $data['fecha_inicial'] ));
                           $fecha_final = date( 'Y-m-d', strtotime( $data['fecha_final'] ));
                          
                          if ($estatus=="apartado") {
                            $fechas .= ' AND ( ( DATE_FORMAT((m.fecha_apartado),"%Y-%m-%d")  >=  "'.$fecha_inicial.'" )  AND  ( DATE_FORMAT((m.fecha_apartado),"%Y-%m-%d")  <=  "'.$fecha_final.'" ) )'; 
                          }  else {
                            $fechas .= ' AND ( ( DATE_FORMAT((m.fecha_entrada),"%Y-%m-%d")  >=  "'.$fecha_inicial.'" )  AND  ( DATE_FORMAT((m.fecha_entrada),"%Y-%m-%d")  <=  "'.$fecha_final.'" ) )'; 
                          }  

          } else {
           $fechas .= ' ';
          }



          $donde = '';
         if ($id_empresa!="") {
              $id_empre =  self::check_existente_proveedor_entrada($id_empresa);

              if ($estatus=="apartado") {
                  $donde .= ' AND ( us.id_cliente  =  '.$id_empre.' ) '; //id_cliente_apartado, id_usuario_apartado
              }    else {
                  $donde .= ' AND ( m.id_empresa  =  '.$id_empre.' ) ';
              }

        } else 
        {
           $donde .= ' ';
        }

         if ($factura_reporte!="") {
            $donde .= ' AND ( m.factura  =  "'.$factura_reporte.'" ) ';
        } 


          //productos
          //$id_descripcion= $data['id_descripcion'];
          $id_descripcion= addslashes($data['id_descripcion']);
          $id_color= $data['id_color'];
          $id_composicion= $data['id_composicion'];
          $id_calidad= $data['id_calidad'];


          $id_session = $this->db->escape($this->session->userdata('id'));


    //////////          


          $this->db->select("SUM((m.id_medida =1) * m.cantidad_um) as metros", FALSE);
          $this->db->select("SUM((m.id_medida =2) * m.cantidad_um) as kilogramos", FALSE);
          $this->db->select("SUM(m.ancho) as ancho", FALSE);
          $this->db->select("COUNT(m.id_medida) as 'pieza'");
          
          $this->db->from($this->registros.' as m');
          $this->db->join($this->colores.' As c' , 'c.id = m.id_color','LEFT');
          $this->db->join($this->unidades_medidas.' As u' , 'u.id = m.id_medida','LEFT');
          $this->db->join($this->proveedores.' As p' , 'p.id = m.id_empresa','LEFT');


          if ($estatus=="apartado") {
              $this->db->join($this->usuarios.' As us' , 'us.id = m.id_usuario_apartado','LEFT');
              $this->db->join($this->proveedores.' As pr', 'us.id_cliente = pr.id','LEFT');

          }    




          if ($estatus=="apartado") {
            $cond= ' (pr.nombre LIKE  "%'.$cadena.'%") OR  ( m.id_apartado LIKE  "%'.$cadena.'%" )';                 
          } else {
            $cond= ' (p.nombre LIKE  "%'.$cadena.'%") OR  (CONCAT(m.id_lote,"-",m.consecutivo) LIKE  "%'.$cadena.'%") ';//' OR (m.consecutivo LIKE  "%'.$cadena.'%") ';
          }

          if ($id_estatus!=0) {
            $estatus_idid = ' and ( m.id_estatus =  '.$id_estatus.' ) ';  
          } else {
            $estatus_idid = '';
          }
          

          $where = '(
                      (
                         ( m.estatus_salida = "0" ) '.$estatus_idid.' 
                      ) 
                       AND
                      (
                        ( m.codigo LIKE  "%'.$cadena.'%" ) OR (m.id_descripcion LIKE  "%'.$cadena.'%") OR (c.color LIKE  "%'.$cadena.'%")  OR
                        ( CONCAT(m.cantidad_um," ",u.medida) LIKE  "%'.$cadena.'%" ) OR (CONCAT(m.ancho," cm") LIKE  "%'.$cadena.'%")  OR
                        (m.factura LIKE  "%'.$cadena.'%") OR ( m.movimiento LIKE  "%'.$cadena.'%" ) OR ((DATE_FORMAT((m.fecha_entrada),"%d-%m-%Y") ) LIKE  "%'.$cadena.'%") OR '.$cond.' 
                       )

            ) ' ;                     
          

          $where_total = '( ( m.estatus_salida = "0" )  '.$estatus_idid.'  )';

          if ($estatus=="devolucion") {
              $where .= ' AND ( m.id_estatus = "13" ) ' ;   
              $where_total .= ' AND ( m.id_estatus = "13" ) ' ;   
          }    

          if ($estatus=="apartado") {
              $where .= ' AND ( m.id_apartado != 0 ) ' ;   
              $where_total .= ' AND ( m.id_apartado != 0 ) ' ;      
          }    else {
              $where .= ' AND ( m.id_apartado = 0 ) ' ;   
              $where_total .= ' AND ( m.id_apartado = 0 ) ' ;   
          }


          if ( (($id_calidad!="0") AND ($id_calidad!="") AND ($id_calidad!= null))
            and (($id_composicion!="0") AND ($id_composicion!="") AND ($id_composicion!= null))
            and (($id_color!="0") AND ($id_color!="") AND ($id_color!= null))
            and (($id_descripcion!="0") AND ($id_descripcion!="") AND ($id_descripcion!= null)) 
            ) {
              $where .= ' AND ( m.id_descripcion  =  "'.$id_descripcion.'" ) AND  ( m.id_color  =  '.$id_color.' )';
              $where .= ' AND ( m.id_composicion  =  '.$id_composicion.' ) AND  ( m.id_calidad  =  '.$id_calidad.' )';
              $where_total .= ' AND ( m.id_descripcion  =  "'.$id_descripcion.'" ) AND  ( m.id_color  =  '.$id_color.' )';
              $where_total .= ' AND ( m.id_composicion  =  '.$id_composicion.' ) AND  ( m.id_calidad  =  '.$id_calidad.' )';
          }    

          elseif
           ( 
               (($id_composicion!="0") AND ($id_composicion!="") AND ($id_composicion!= null))
            and (($id_color!="0") AND ($id_color!="") AND ($id_color!= null))
            and (($id_descripcion!="0") AND ($id_descripcion!="") AND ($id_descripcion!= null)) 
            ) {
              $where .= ' AND ( m.id_descripcion  =  "'.$id_descripcion.'" ) AND  ( m.id_color  =  '.$id_color.' )';
              $where .= ' AND ( m.id_composicion  =  '.$id_composicion.' ) ';
              $where_total .= ' AND ( m.id_descripcion  =  "'.$id_descripcion.'" ) AND  ( m.id_color  =  '.$id_color.' )';
              $where_total .= ' AND ( m.id_composicion  =  '.$id_composicion.' ) ';
          }  

          elseif 
           ( (($id_color!="0") AND ($id_color!="") AND ($id_color!= null))
            and (($id_descripcion!="0") AND ($id_descripcion!="") AND ($id_descripcion!= null)) 
            ) {
              $where .= ' AND ( m.id_descripcion  =  "'.$id_descripcion.'" ) AND  ( m.id_color  =  '.$id_color.' )';
              $where_total .= ' AND ( m.id_descripcion  =  "'.$id_descripcion.'" ) AND  ( m.id_color  =  '.$id_color.' )';
          }  

          elseif  (($id_descripcion!="0") AND ($id_descripcion!="") AND ($id_descripcion!= null)) {
              $where .= ' AND ( m.id_descripcion  =  "'.$id_descripcion.'" )';
              $where_total  .= ' AND ( m.id_descripcion  =  "'.$id_descripcion.'" )';
          }  


          $where_total.= $donde.$fechas; //$donde.



          $this->db->where($where.$donde.$fechas); //
    
          //ordenacion

          $this->db->order_by('m.factura', 'asc'); 
          //paginacion
          $this->db->limit($largo,$inicio); 


          $result = $this->db->get();


           

          if ( $result->num_rows() > 0 )
             return $result->row();
          else
             return False;
          $result->free_result();    



        }     



  public function entrada_home($data){


          $cadena = addslashes($data['busqueda']);
          $inicio = 0; //$data['start'];
          $largo = 10000; //$data['length'];

          $estatus= $data['extra_search'];
          $id_estatus= $data['id_estatus'];
          $id_empresa= addslashes($data['proveedor']);

          $factura_reporte = addslashes($data['factura_reporte']);
          $id_almacen= $data['id_almacen'];


          $fechas = ' ';
          if  ( ($data['fecha_inicial'] !="") and  ($data['fecha_final'] !="")) {
                           $fecha_inicial = date( 'Y-m-d', strtotime( $data['fecha_inicial'] ));
                           $fecha_final = date( 'Y-m-d', strtotime( $data['fecha_final'] ));
                          
                          if ($estatus=="apartado") {
                            $fechas .= ' AND ( ( DATE_FORMAT((m.fecha_apartado),"%Y-%m-%d")  >=  "'.$fecha_inicial.'" )  AND  ( DATE_FORMAT((m.fecha_apartado),"%Y-%m-%d")  <=  "'.$fecha_final.'" ) )'; 
                          }  else {
                            $fechas .= ' AND ( ( DATE_FORMAT((m.fecha_entrada),"%Y-%m-%d")  >=  "'.$fecha_inicial.'" )  AND  ( DATE_FORMAT((m.fecha_entrada),"%Y-%m-%d")  <=  "'.$fecha_final.'" ) )'; 
                          }  

          } else {
           $fechas .= ' ';
          }



          $donde = '';
         if ($id_empresa!="") {
              $id_empre =  self::check_existente_proveedor_entrada($id_empresa);

              if ($estatus=="apartado") {
                  $donde .= ' AND ( us.id_cliente  =  '.$id_empre.' ) '; //id_cliente_apartado, id_usuario_apartado
              }    else {
                  $donde .= ' AND ( m.id_empresa  =  '.$id_empre.' ) ';
              }

        } else 
        {
           $donde .= ' ';
        }

         if ($factura_reporte!="") {
            $donde .= ' AND ( m.factura  =  "'.$factura_reporte.'" ) ';
        } 


          //productos
          //$id_descripcion= $data['id_descripcion'];
          $id_descripcion= addslashes($data['id_descripcion']);
          $id_color= $data['id_color'];
          $id_composicion= $data['id_composicion'];
          $id_calidad= $data['id_calidad'];


          $id_session = $this->db->escape($this->session->userdata('id'));
    

          $this->db->select('m.codigo, m.id_descripcion producto,prod.codigo_contable codigo_alterno');
          $this->db->select('c.color');
          $this->db->select('CONCAT(m.cantidad_um, u.medida) cantidad', false);
          $this->db->select('CONCAT(m.ancho, " cm") ancho', false);
          $this->db->select('m.movimiento');

          if ($estatus!="apartado") { //existencia
              $this->db->select('p.nombre Proveedor');
              $this->db->select('CONCAT(m.id_lote,"-",m.consecutivo) lote', false);
              $this->db->select('DATE_FORMAT((m.fecha_entrada),"%d-%m-%Y") ingreso', false); //'d-m-Y'
          } else {  //apartado        "dependencia"

                $this->db->select('
                          CASE m.id_apartado
                            WHEN "1" THEN   CONCAT (pr.nombre," (Vendedor)")
                             WHEN "2" THEN  CONCAT (pr.nombre, " (Vendedor)")
                             WHEN "3" THEN  CONCAT (pr.nombre, " (Vendedor)")
                             
                             WHEN "4" THEN  CONCAT (pr.nombre, " (Tienda)")
                             WHEN "5" THEN  CONCAT (pr.nombre, " (Tienda)")
                             WHEN "6" THEN  CONCAT (pr.nombre, " (Tienda)")

                             ELSE "No Apartado"
                          END AS dependencia
              ',False);



                $this->db->select('
                              CASE m.id_apartado
                                WHEN "1" THEN "Apartado Individual"
                                 WHEN "2" THEN "Apartado Confirmado"
                                 WHEN "3" THEN "Disponibilidad Salida"
                                 
                                 WHEN "4" THEN "Apartado Individual"
                                 WHEN "5" THEN "Apartado Confirmado"
                                 WHEN "6" THEN "Disponibilidad Salida"

                                 ELSE "No Apartado"
                              END AS tipo_apartado
                  ',False);
                  $this->db->select('DATE_FORMAT((m.fecha_apartado),"%d-%m-%Y") fecha', false); //'d-m-Y'

          }    


          if (($data['configuracion']->activo==1)) {  
            $this->db->select('m.factura');
          }  
          $this->db->select('m.num_partida'); //'d-m-Y'


          $this->db->select("a.almacen");
          $this->db->from($this->registros.' as m');
          $this->db->join($this->almacenes.' As a' , 'a.id = m.id_almacen AND a.activo=1');
          $this->db->join($this->productos.' As prod' , 'prod.referencia = m.referencia','LEFT');
          $this->db->join($this->colores.' As c' , 'c.id = m.id_color','LEFT');
          $this->db->join($this->unidades_medidas.' As u' , 'u.id = m.id_medida','LEFT');
          $this->db->join($this->proveedores.' As p' , 'p.id = m.id_empresa','LEFT');
                               



          if ($estatus=="apartado") {
              $this->db->join($this->usuarios.' As us' , 'us.id = m.id_usuario_apartado','LEFT');
              $this->db->join($this->proveedores.' As pr', 'us.id_cliente = pr.id','LEFT');

          }    

          if ($estatus=="apartado") {
            $cond= ' (pr.nombre LIKE  "%'.$cadena.'%") OR  ( m.id_apartado LIKE  "%'.$cadena.'%" )';                 
          } else {
            $cond= ' (p.nombre LIKE  "%'.$cadena.'%") OR  (CONCAT(m.id_lote,"-",m.consecutivo) LIKE  "%'.$cadena.'%") ';//' OR (m.consecutivo LIKE  "%'.$cadena.'%") ';
          }

          if ($id_estatus!=0) {
            $estatus_idid = ' and ( m.id_estatus =  '.$id_estatus.' ) ';  
          } else {
            $estatus_idid = '';
          }
          
          if ($id_almacen!=0) {
            $id_almacenid = ' and ( m.id_almacen =  '.$id_almacen.' ) ';  
          } else {
            $id_almacenid = '';
          }

          if ($data['id_factura']!=0) {
              $id_facturaid = ' AND ( m.id_factura =  '.$data['id_factura'].' ) ';  
          } else {
              $id_facturaid = '';
          } 

          $where = '(
                      (
                         ( m.estatus_salida = "0" ) '.$estatus_idid.$id_almacenid.$id_facturaid.' 
                      ) 
                       AND
                      (
                        ( m.codigo LIKE  "%'.$cadena.'%" ) OR (m.id_descripcion LIKE  "%'.$cadena.'%") OR (c.color LIKE  "%'.$cadena.'%")  OR
                        ( CONCAT(m.cantidad_um," ",u.medida) LIKE  "%'.$cadena.'%" ) OR (CONCAT(m.ancho," cm") LIKE  "%'.$cadena.'%")  OR
                        (m.factura LIKE  "%'.$cadena.'%") OR ( m.movimiento LIKE  "%'.$cadena.'%" ) OR ((DATE_FORMAT((m.fecha_entrada),"%d-%m-%Y") ) LIKE  "%'.$cadena.'%") OR '.$cond.' 
                       )

            ) ' ;                     
          

          $where_total = '( ( m.estatus_salida = "0" )  '.$estatus_idid.$id_almacenid.$id_facturaid.'  )';

          if ($estatus=="devolucion") {
              $where .= ' AND ( m.id_estatus = "13" ) ' ;   
              $where_total .= ' AND ( m.id_estatus = "13" ) ' ;   
          }    

          if ($estatus=="apartado") {
              $where .= ' AND ( m.id_apartado != 0 ) ' ;   
              $where_total .= ' AND ( m.id_apartado != 0 ) ' ;   
          }    elseif ($estatus!="existencia") {
              $where .= ' AND ( m.id_apartado = 0 ) ' ;   
              $where_total .= ' AND ( m.id_apartado = 0 ) ' ;   
          } 



         if  (($id_calidad!="0") AND ($id_calidad!="") AND ($id_calidad!= null)) {
             $where.= (($where!="") ? " and " : "") . "( m.id_calidad  =  ".$id_calidad." )";
             $where_total.= (($where_total!="") ? " and " : "") . "( m.id_calidad  =  ".$id_calidad." )";
          }     

          if (($id_composicion!="0") AND ($id_composicion!="") AND ($id_composicion!= null)) {
              $where.= (($where!="") ? " and " : "") . "( m.id_composicion  =  ".$id_composicion." ) ";
              $where_total.= (($where_total!="") ? " and " : "") . "( m.id_composicion  =  ".$id_composicion." ) ";
          } 


          if  (($id_color!="0") AND ($id_color!="") AND ($id_color!= null)) {
             $where.= (($where!="") ?  " and " : "") . "( m.id_color  =  ".$id_color." )";
             $where_total.= (($where_total!="") ?  " and " : "") . "( m.id_color  =  ".$id_color." )";
          }

          //if (($id_descripcion!="0") AND ($id_descripcion!="") AND ($id_descripcion!= null))  {                
          if ( ($data['val_prod_id'] !="")  && ($data['val_prod_id'] !="0") ) {
              $where.= (($where!="") ? " and " : "") . "( m.id_descripcion  =  '".$id_descripcion."' )";
              $where_total.= (($where_total!="") ? " and " : "") . "( m.id_descripcion  =  '".$id_descripcion."' )";
          }                                    




          $where_total.= $donde.$fechas; //$donde.



          $this->db->where($where.$donde.$fechas); //
    
          //ordenacion

        //  $this->db->order_by('m.factura', 'asc'); 
          //paginacion
         // $this->db->limit($largo,$inicio); 


          $result = $this->db->get();


            if ( $result->num_rows() > 0 )
               return $result->result();
            else
               return False;
            $result->free_result();


      }  




    
////////////////////////////////////////////////////////////////////////////////////////////////
      ///////////////////////////salidas////////////////////////



   
   public function totales_salidas($data){

          $cadena = addslashes($data['busqueda']);
          $inicio = 0; //$data['start'];
          $largo = 10000; //$data['length'];

          $estatus= $data['extra_search'];
          $id_estatus= $data['id_estatus'];
          $id_empresa= addslashes($data['proveedor']);

          $factura_reporte = addslashes($data['factura_reporte']);



          $donde = '';
         if ($id_empresa!="") {
            $id_empre =  self::check_existente_proveedor_entrada($id_empresa);
            $donde .= ' AND ( m.id_cliente  =  '.$id_empre.' ) ';
        } else 
        {
           $donde .= ' ';
        }

        if ($factura_reporte!="") {
            $donde .= ' AND ( m.factura  =  "'.$factura_reporte.'" ) ';
        } 

          $fechas = ' ';
          if  ( ($data['fecha_inicial'] !="") and  ($data['fecha_final'] !="")) {
                          
                           $fecha_inicial = date( 'Y-m-d', strtotime( $data['fecha_inicial'] ));
                           $fecha_final = date( 'Y-m-d', strtotime( $data['fecha_final'] ));
                          
                           $fechas .= ' AND ( ( DATE_FORMAT((m.fecha_salida),"%Y-%m-%d")  >=  "'.$fecha_inicial.'" )  AND  ( DATE_FORMAT((m.fecha_salida),"%Y-%m-%d")  <=  "'.$fecha_final.'" ) )'; 
                          
          } else {
           $fechas .= ' ';
          }


          //productos
          //$id_descripcion= $data['id_descripcion'];
          $id_descripcion= addslashes($data['id_descripcion']);
          $id_color= $data['id_color'];
          $id_composicion= $data['id_composicion'];
          $id_calidad= $data['id_calidad'];


          $id_session = $this->db->escape($this->session->userdata('id'));

          

          $this->db->select("SUM((m.id_medida =1) * m.cantidad_um) as metros", FALSE);
          $this->db->select("SUM((m.id_medida =2) * m.cantidad_um) as kilogramos", FALSE);
          $this->db->select("SUM(m.ancho) as ancho", FALSE);
          $this->db->select("COUNT(m.id_medida) as 'pieza'");
       
    
          $this->db->from($this->historico_registros_salidas.' as m');
          $this->db->join($this->colores.' As c' , 'c.id = m.id_color','LEFT');
          $this->db->join($this->unidades_medidas.' As u' , 'u.id = m.id_medida','LEFT');
          $this->db->join($this->proveedores.' As p' , 'p.id = m.id_cliente','LEFT');


          if ($estatus=="apartado") {
              $this->db->join($this->usuarios.' As us' , 'us.id = m.id_usuario_apartado','LEFT');
              $this->db->join($this->proveedores.' As pr', 'us.id_cliente = pr.id','LEFT');

          }    

          if ($id_estatus!=0) {
            $estatus_idid = ' and ( m.id_estatus =  '.$id_estatus.' ) ';  
          } else {
            $estatus_idid = '';
          }          

          $where = '(
                      (
                         ( m.estatus_salida = "0" )  '.$estatus_idid.'
                      ) 
                       AND
                      (
                        ( m.codigo LIKE  "%'.$cadena.'%" ) OR (m.id_descripcion LIKE  "%'.$cadena.'%") OR (c.color LIKE  "%'.$cadena.'%")  OR
                        ( CONCAT(m.cantidad_um," ",u.medida) LIKE  "%'.$cadena.'%" ) OR (CONCAT(m.ancho," cm") LIKE  "%'.$cadena.'%")  OR
                        ( m.mov_salida LIKE  "%'.$cadena.'%" ) OR ((DATE_FORMAT((m.fecha_salida),"%d-%m-%Y") ) LIKE  "%'.$cadena.'%") OR 
                        (m.factura LIKE  "%'.$cadena.'%") OR (p.nombre LIKE  "%'.$cadena.'%") OR  (CONCAT(m.id_lote,"-",m.consecutivo) LIKE  "%'.$cadena.'%") 

                       )
            ) ' ;   



          $where_total = '( m.estatus_salida = "0" )  '.$estatus_idid;

           if ( (($id_calidad!="0") AND ($id_calidad!="") AND ($id_calidad!= null))
            and (($id_composicion!="0") AND ($id_composicion!="") AND ($id_composicion!= null))
            and (($id_color!="0") AND ($id_color!="") AND ($id_color!= null))
            and (($id_descripcion!="0") AND ($id_descripcion!="") AND ($id_descripcion!= null)) 
            ) {
              $where .= ' AND ( m.id_descripcion  =  "'.$id_descripcion.'" ) AND  ( m.id_color  =  '.$id_color.' )';
              $where .= ' AND ( m.id_composicion  =  '.$id_composicion.' ) AND  ( m.id_calidad  =  '.$id_calidad.' )';
              $where_total .= ' AND ( m.id_descripcion  =  "'.$id_descripcion.'" ) AND  ( m.id_color  =  '.$id_color.' )';
              $where_total .= ' AND ( m.id_composicion  =  '.$id_composicion.' ) AND  ( m.id_calidad  =  '.$id_calidad.' )';
          }    

          elseif
           ( 
               (($id_composicion!="0") AND ($id_composicion!="") AND ($id_composicion!= null))
            and (($id_color!="0") AND ($id_color!="") AND ($id_color!= null))
            and (($id_descripcion!="0") AND ($id_descripcion!="") AND ($id_descripcion!= null)) 
            ) {
              $where .= ' AND ( m.id_descripcion  =  "'.$id_descripcion.'" ) AND  ( m.id_color  =  '.$id_color.' )';
              $where .= ' AND ( m.id_composicion  =  '.$id_composicion.' ) ';
              $where_total .= ' AND ( m.id_descripcion  =  "'.$id_descripcion.'" ) AND  ( m.id_color  =  '.$id_color.' )';
              $where_total .= ' AND ( m.id_composicion  =  '.$id_composicion.' ) ';
          }  

          elseif 
           ( (($id_color!="0") AND ($id_color!="") AND ($id_color!= null))
            and (($id_descripcion!="0") AND ($id_descripcion!="") AND ($id_descripcion!= null)) 
            ) {
              $where .= ' AND ( m.id_descripcion  =  "'.$id_descripcion.'" ) AND  ( m.id_color  =  '.$id_color.' )';
              $where_total .= ' AND ( m.id_descripcion  =  "'.$id_descripcion.'" ) AND  ( m.id_color  =  '.$id_color.' )';
          }  

          elseif  (($id_descripcion!="0") AND ($id_descripcion!="") AND ($id_descripcion!= null)) {
              $where .= ' AND ( m.id_descripcion  =  "'.$id_descripcion.'" )';
              $where_total  .= ' AND ( m.id_descripcion  =  "'.$id_descripcion.'" )';
          }  


          $where_total .= $donde.$fechas;

          $this->db->where($where.$donde.$fechas); //

    
          //ordenacion
          $this->db->order_by('m.factura', 'asc');

          //paginacion
          $this->db->limit($largo,$inicio); 



          $result = $this->db->get();


           

          if ( $result->num_rows() > 0 )
             return $result->row();
          else
             return False;
          $result->free_result();                
         

      }  




   
   public function salida_home($data){

          $cadena = addslashes($data['busqueda']);
          $inicio = 0; //$data['start'];
          $largo = 10000; //$data['length'];

          $estatus= $data['extra_search'];
          $id_estatus= $data['id_estatus'];
          $id_empresa= addslashes($data['proveedor']);

          $factura_reporte = addslashes($data['factura_reporte']);

          $id_almacen= $data['id_almacen'];

          $donde = '';
         if ($id_empresa!="") {
            $id_empre =  self::check_existente_proveedor_entrada($id_empresa);
            $donde .= ' AND ( m.consecutivo_venta  =  '.$id_empre.' ) ';
        } else 
        {
           $donde .= ' ';
        }

        if ($factura_reporte!="") {
            $donde .= ' AND ( m.factura  =  "'.$factura_reporte.'" ) ';
        } 

          $fechas = ' ';
          if  ( ($data['fecha_inicial'] !="") and  ($data['fecha_final'] !="")) {
                          
                           $fecha_inicial = date( 'Y-m-d', strtotime( $data['fecha_inicial'] ));
                           $fecha_final = date( 'Y-m-d', strtotime( $data['fecha_final'] ));
                          
                           $fechas .= ' AND ( ( DATE_FORMAT((m.fecha_salida),"%Y-%m-%d")  >=  "'.$fecha_inicial.'" )  AND  ( DATE_FORMAT((m.fecha_salida),"%Y-%m-%d")  <=  "'.$fecha_final.'" ) )'; 
                          
          } else {
           $fechas .= ' ';
          }


          //productos
          //$id_descripcion= $data['id_descripcion'];
          $id_descripcion= addslashes($data['id_descripcion']);
          $id_color= $data['id_color'];
          $id_composicion= $data['id_composicion'];
          $id_calidad= $data['id_calidad'];


          $id_session = $this->db->escape($this->session->userdata('id'));

          $this->db->select('m.codigo, m.id_descripcion producto,prod.codigo_contable codigo_alterno');
          $this->db->select('c.color');
          $this->db->select('CONCAT(m.cantidad_um, u.medida) cantidad', false);
          $this->db->select('CONCAT(m.ancho, " cm") ancho', false);
          $this->db->select('m.mov_salida');
          //$this->db->select('p.nombre Cliente');
          $this->db->select('us.nombre as Cliente', FALSE);  

          $this->db->select('CONCAT(m.id_lote,"-",m.consecutivo) lote', false);
          $this->db->select('DATE_FORMAT((m.fecha_salida),"%d-%m-%Y") egreso', false); //'d-m-Y'
          
          if (($data['configuracion']->activo==1)) {  
            $this->db->select('m.factura');
          }  
          $this->db->select('m.num_partida'); //'d-m-Y'

              
          $this->db->select("a.almacen");

          $this->db->from($this->historico_registros_salidas.' as m');
          $this->db->join($this->almacenes.' As a' , 'a.id = m.id_almacen AND a.activo=1');
          $this->db->join($this->productos.' As prod' , 'prod.referencia = m.referencia','LEFT');
          $this->db->join($this->colores.' As c' , 'c.id = m.id_color','LEFT');
          $this->db->join($this->unidades_medidas.' As u' , 'u.id = m.id_medida','LEFT');
          $this->db->join($this->proveedores.' As us' , 'us.id = m.consecutivo_venta','LEFT'); //
          $this->db->join($this->proveedores.' As p' , 'p.id = m.id_cliente','LEFT');


                               



          if ($estatus=="apartado") {
              $this->db->join($this->usuarios.' As us' , 'us.id = m.id_usuario_apartado','LEFT');
              $this->db->join($this->proveedores.' As pr', 'us.id_cliente = pr.id','LEFT');

          }    

          if ($id_estatus!=0) {
            $estatus_idid = ' and ( m.id_estatus =  '.$id_estatus.' ) ';  
          } else {
            $estatus_idid = '';
          }          

          if ($id_almacen!=0) {
            $id_almacenid = ' and ( m.id_almacen =  '.$id_almacen.' ) ';  
          } else {
            $id_almacenid = '';
          }

          /*
          if ($data['id_factura']!=0) {
              $id_facturaid = ' AND ( m.id_factura =  '.$data['id_factura'].' ) ';  
          } else {
              $id_facturaid = '';
          } 
          */


          $id_factura= $data['id_factura'];    

          if ($id_factura!=0) {
             $id_factura = (($id_factura==3) ? 0 : $id_factura);
             $id_facturaid = ' and ( m.id_tipo_factura =  '.$id_factura.' ) ';  
          } else {
             $id_facturaid = '';
          }             



          $where = '(
                      (
                         ( m.estatus_salida = "0" )  '.$estatus_idid.$id_almacenid.$id_facturaid.'
                      ) 
                       AND
                      (
                        ( m.codigo LIKE  "%'.$cadena.'%" ) OR (m.id_descripcion LIKE  "%'.$cadena.'%") OR (c.color LIKE  "%'.$cadena.'%")  OR
                        ( CONCAT(m.cantidad_um," ",u.medida) LIKE  "%'.$cadena.'%" ) OR (CONCAT(m.ancho," cm") LIKE  "%'.$cadena.'%")  OR
                        ( m.mov_salida LIKE  "%'.$cadena.'%" ) OR ((DATE_FORMAT((m.fecha_salida),"%d-%m-%Y") ) LIKE  "%'.$cadena.'%") OR 
                        (m.factura LIKE  "%'.$cadena.'%") OR (p.nombre LIKE  "%'.$cadena.'%") OR  (CONCAT(m.id_lote,"-",m.consecutivo) LIKE  "%'.$cadena.'%") 

                       )
            ) ' ;   



          $where_total = '( m.estatus_salida = "0" )  '.$estatus_idid.$id_almacenid.$id_facturaid;

          if  (($id_calidad!="0") AND ($id_calidad!="") AND ($id_calidad!= null)) {
             $where.= (($where!="") ? " and " : "") . "( m.id_calidad  =  ".$id_calidad." )";
             $where_total.= (($where_total!="") ? " and " : "") . "( m.id_calidad  =  ".$id_calidad." )";
          }     
          if (($id_composicion!="0") AND ($id_composicion!="") AND ($id_composicion!= null)) {
              $where.= (($where!="") ? " and " : "") . "( m.id_composicion  =  ".$id_composicion." ) ";
              $where_total.= (($where_total!="") ? " and " : "") . "( m.id_composicion  =  ".$id_composicion." ) ";
          } 
          if  (($id_color!="0") AND ($id_color!="") AND ($id_color!= null)) {
             $where.= (($where!="") ?  " and " : "") . "( m.id_color  =  ".$id_color." )";
             $where_total.= (($where_total!="") ?  " and " : "") . "( m.id_color  =  ".$id_color." )";
          }

          //if (($id_descripcion!="0") AND ($id_descripcion!="") AND ($id_descripcion!= null))  {                
          if ( ($data['val_prod_id'] !="")  && ($data['val_prod_id'] !="0") ) {
              $where.= (($where!="") ? " and " : "") . "( m.id_descripcion  =  '".$id_descripcion."' )";
              $where_total.= (($where_total!="") ? " and " : "") . "( m.id_descripcion  =  '".$id_descripcion."' )";
          }             


          $where_total .= $donde.$fechas;

          $this->db->where($where.$donde.$fechas); //

    
          //ordenacion
          //$this->db->order_by('m.factura', 'asc');

          //paginacion
          $this->db->limit($largo,$inicio); 



          $result = $this->db->get();


            if ( $result->num_rows() > 0 )
               return $result->result();
            else
               return False;
            $result->free_result();
         

      }  




////////////////////////////////////////////////////////////////////////

    public function buscador_top($data){

    
          $cadena = addslashes($data['busqueda']);
          $inicio = 0; //$data['start'];
          $largo = 10; //$data['length'];

          $estatus= $data['extra_search'];
          $id_estatus= $data['id_estatus'];
          $id_empresa= addslashes($data['proveedor']);

          $factura_reporte = addslashes($data['factura_reporte']);
          $id_almacen= $data['id_almacen'];


          $id_session = $this->db->escape($this->session->userdata('id'));


          $this->db->select('p.referencia, p.descripcion producto,p.codigo_contable codigo_alterno');
          $this->db->select("COUNT(m.referencia) as 'rollos_vendidos'");
          $this->db->select('p.imagen');
          $this->db->select('c.color');
          $this->db->select('p.comentario');
          $this->db->select("co.composicion composición", FALSE);  
          $this->db->select("ca.calidad", FALSE);  
          //$this->db->select('p.precio');

         $fechas = ' ';
          if  ( ($data['fecha_inicial'] !="") and  ($data['fecha_final'] !="")) {
                           $fecha_inicial = date( 'Y-m-d', strtotime( $data['fecha_inicial'] ));
                           $fecha_final = date( 'Y-m-d', strtotime( $data['fecha_final'] ));
                          
                            $fechas .= ' AND ( ( DATE_FORMAT((m.fecha_salida),"%Y-%m-%d")  >=  "'.$fecha_inicial.'" )  AND  ( DATE_FORMAT((m.fecha_salida),"%Y-%m-%d")  <=  "'.$fecha_final.'" ) )'; 


                          
          } else {
           $fechas .= ' ';
          }

    
 
          $this->db->select("a.almacen");

          if ($id_almacen!=0) {
            $id_almacenid = ' and ( m.id_almacen =  '.$id_almacen.' ) ';  
          } else {
            $id_almacenid = '';
          }      


           if ($data['id_factura']!=0) {
              $id_facturaid = ' AND ( m.id_factura =  '.$data['id_factura'].' ) ';  
          } else {
              $id_facturaid = '';
          } 




          $this->db->from($this->productos.' as p');
          $this->db->join($this->colores.' As c', 'p.id_color = c.id'); //,'LEFT'
          $this->db->join($this->composiciones.' As co', 'p.id_composicion = co.id'); //,'LEFT'
          $this->db->join($this->calidades.' As ca', 'p.id_calidad = ca.id'); //,'LEFT'
          $this->db->join($this->historico_registros_salidas.' As m', 'p.referencia = m.referencia'.$fechas.''.$id_almacenid.$id_facturaid); //,'LEFT'
          $this->db->join($this->almacenes.' As a', 'a.id = m.id_almacen'); //,'LEFT'

          $where = '(
                      
                      (
                        ( p.referencia LIKE  "%'.$cadena.'%" ) OR (p.descripcion LIKE  "%'.$cadena.'%") OR 
                        (c.color LIKE  "%'.$cadena.'%") OR (p.comentario LIKE  "%'.$cadena.'%")  OR
                        (co.composicion LIKE  "%'.$cadena.'%")  OR
                        ( ca.calidad LIKE  "%'.$cadena.'%" )  OR 
                        ( p.precio LIKE  "%'.$cadena.'%" ) 
                       )

            ) ' ; 


          $this->db->where($where);

          $this->db->group_by("p.referencia, p.minimo,  p.precio"); //p.imagen,
          //$this->db->group_by("p.referencia,p.descripcion, p.minimo, p.imagen, p.precio, c.hexadecimal_color,c.color,co.composicion,ca.calidad");
          //paginacion

                //ordenacion
          $this->db->order_by('rollos_vendidos', 'desc');

          //paginacion
          $this->db->limit($largo,$inicio); 



          $result = $this->db->get();


            if ( $result->num_rows() > 0 )
               return $result->result();
            else
               return False;
            $result->free_result();

      }        




/////////////////////////////////cero y bajas//////////////////////////


    public function buscador_cero_baja($data){

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


          $cadena = addslashes($data['busqueda']);
          $inicio = 0; //$data['start'];
          $largo = 10000; //$data['length'];

          $estatus= $data['extra_search'];
          $id_estatus= $data['id_estatus'];
          $id_empresa= addslashes($data['proveedor']);

          $factura_reporte = addslashes($data['factura_reporte']);
          $id_almacen= $data['id_almacen'];
          
                  
          $id_descripcion= addslashes($data['id_descripcion']);
          $id_color= $data['id_color'];
          $id_composicion= $data['id_composicion'];
          $id_calidad= $data['id_calidad'];


          $id_session = $this->db->escape($this->session->userdata('id'));


          $this->db->select('p.referencia, p.descripcion producto,p.codigo_contable codigo_alterno');
          $this->db->select('p.minimo');
          $this->db->select("COUNT(m.referencia) as 'existencias'"); //existencias
          $this->db->select('p.imagen');
          $this->db->select('c.color');
          $this->db->select('p.comentario especificaciones');
          $this->db->select("co.composicion composición", FALSE);  
          $this->db->select("ca.calidad", FALSE);  
          //$this->db->select('p.precio');

          if ($id_almacen!=0) {
            $id_almacenid = ' and ( m.id_almacen =  '.$id_almacen.' ) ';  
          } else {
            $id_almacenid = '';
          }   

           if ($data['id_factura']!=0) {
              $id_facturaid = ' AND ( m.id_factura =  '.$data['id_factura'].' ) ';  
          } else {
              $id_facturaid = '';
          } 


          $this->db->select("a.almacen");


          $this->db->from($this->productos.' as p');
          $this->db->join($this->colores.' As c', 'p.id_color = c.id'); //,'LEFT'
          $this->db->join($this->composiciones.' As co', 'p.id_composicion = co.id'); //,'LEFT'
          $this->db->join($this->calidades.' As ca', 'p.id_calidad = ca.id'); //,'LEFT'
          $this->db->join($this->registros.' As m', 'm.referencia= p.referencia and m.id_estatus=12 '.$id_almacenid.$id_facturaid.$id_empresaid,'LEFT'); //,'LEFT'
          $this->db->join($this->almacenes.' As a', 'a.id = m.id_almacen','LEFT'); //,'LEFT'

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


         $where_cond ='';


    
    

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
            //if (($id_descripcion!="0") AND ($id_descripcion!="") AND ($id_descripcion!= null))  {                
            if ( ($data['val_prod_id'] !="")  && ($data['val_prod_id'] !="0") ) {
                $where.= (($where!="") ? " and " : "") . "( p.descripcion  =  '".$id_descripcion."' )";
                $where_cond.= (($where_cond!="") ? " and " : "") . "( p.descripcion  =  '".$id_descripcion."' )";
            }
          

          $this->db->where($where);

          $this->db->order_by('p.referencia', 'asc'); 

          //$this->db->group_by("p.referencia,p.descripcion, p.minimo, p.imagen, p.precio, c.hexadecimal_color,c.color,co.composicion,ca.calidad");
          $this->db->group_by("p.referencia, p.minimo,  p.precio"); //p.imagen,
          //paginacion


         if ($estatus=="cero") {
              $this->db->having('existencias <= 0');
              $where_total = 'existencias <= 0';
          }   

          if ($estatus=="baja") {
 
              $this->db->having('((existencias>0) AND (existencias < p.minimo))');
              $where_total = '((existencias>0) AND (existencias < p.minimo))';


          }             
          
 


          //paginacion
          $this->db->limit($largo,$inicio); 



          $result = $this->db->get();

            if ( $result->num_rows() > 0 )
               return $result->result();
            else
               return False;
            $result->free_result();



      }        





}

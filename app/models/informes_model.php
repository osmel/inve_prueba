<?php 
class Informes_model extends CI_Model
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

                
                $this->tipos_pedidos                         = $this->db->dbprefix('catalogo_tipos_pedidos');
                $this->tipos_ventas                         = $this->db->dbprefix('catalogo_tipos_ventas');



                    //usuarios
                  $this->usuarios    = $this->db->dbprefix('usuarios');


    }






    public function informe_reportes_costo($data){


          //$cadena = addslashes($data['search']['value']);

          $cadena = addslashes($data['busqueda']);
          
          $id_estatus= $data['id_estatus'];
          $id_empresa= addslashes($data['proveedor']);
          $id_almacen= $data['id_almacen'];

          $factura_reporte = addslashes($data['factura_reporte']);


            switch ($data['columna']) {
                   case '0':
                        $columna = 'm.codigo';
                     break;
                   case '1':
                        $columna = 'm.id_descripcion';
                     break;
                   case '2':
                        $columna = 'c.color';
                     break;
                   case '3':
                        $columna = 'm.cantidad_um';
                     break;
                   case '4':
                        $columna = 'm.ancho';

                     break;
                   case '5':
                        $columna = 'm.precio';
                     break;
                   case '6':                          
                              $columna= 'subtotal';                          
                     break;
                   case '7':                          
                              $columna= 'sum_iva';                            
                     break;

                   case '8':                          
                              $columna= 't_factura';                            
                     break;

                   case '9':                          
                              $columna= 'm.movimiento';                            
                     break;

                   case '10':                          
                              $columna= 'p.nombre';                            
                     break;

                   case '11':
                        $columna = 'm.fecha_entrada';
                     break;

          
                   
                   default:
                       $columna = 'm.id';
                     break;
                 }                    

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

          
          $this->db->select('m.codigo, m.id_descripcion');
          $this->db->select('c.hexadecimal_color, c.color');
          $this->db->select('CONCAT(m.cantidad_um," ",u.medida) as cantidad', false);
          $this->db->select('CONCAT(m.ancho," ","cm") as ancho', false);
          
          $this->db->select("m.precio", FALSE);
           $this->db->select("(m.precio*m.cantidad_um) as subtotal", FALSE);
           $this->db->select("((m.precio*m.cantidad_um*m.iva))/100 as sum_iva", FALSE);
           $this->db->select("(m.precio*m.cantidad_um)+((m.precio*m.cantidad_um*m.iva))/100 as total", FALSE);


          $this->db->select("tff.tipo_factura t_factura");  

          $this->db->select('m.movimiento');
          $this->db->select('p.nombre');
          $this->db->select('DATE_FORMAT(m.fecha_entrada,"%d/%m/%Y") as fecha',false);
          $this->db->select("prod.codigo_contable");  


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


                //if ( ($data['val_prod_id'] !="")  && ($data['val_prod_id'] !="0") ) {
                if (($id_descripcion!="0") AND ($id_descripcion!="") AND ($id_descripcion!= null))  {                
                    $where.= (($where!="") ? " and " : "") . "( m.id_descripcion  =  '".$id_descripcion."' )";
                    $where_total.= (($where_total!="") ? " and " : "") . "( m.id_descripcion  =  '".$id_descripcion."' )";
                }


         


          $where_total.= $donde.$fechas; //$donde.



          $this->db->where($where.$donde.$fechas); //
    
       
          //ordenacion

            $this->db->order_by($columna, $data['orden']); 

          $result = $this->db->get();


            if ( $result->num_rows() > 0 )
               return $result->result();
            else
               return False;
            $result->free_result();
      }  





    public function total_reportes_costo($data){


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

          
          $this->db->select('m.codigo, m.id_descripcion');
          $this->db->select('c.hexadecimal_color, c.color');
          $this->db->select('CONCAT(m.cantidad_um," ",u.medida) as cantidad', false);
          $this->db->select('CONCAT(m.ancho," ","cm") as ancho', false);
          
          $this->db->select("m.precio,((m.precio*m.iva))/100 as sum_iva", FALSE);
          $this->db->select("tff.tipo_factura t_factura");  

          $this->db->select('m.movimiento');
          $this->db->select('p.nombre');
          $this->db->select('DATE_FORMAT(m.fecha_entrada,"%d/%m/%Y") as fecha',false);




           $this->db->select("SUM((id_medida =1) * cantidad_um) as metros", FALSE);
           $this->db->select("SUM((id_medida =2) * cantidad_um) as kilogramos", FALSE);
           $this->db->select("COUNT(m.id_medida) as 'pieza'");

           $this->db->select("SUM(m.precio*m.cantidad_um) as subtotal", FALSE);
           $this->db->select("(SUM(m.precio*m.cantidad_um*m.iva))/100 as iva", FALSE);
           $this->db->select("SUM(m.precio*m.cantidad_um)+(SUM(m.precio*m.cantidad_um*m.iva))/100 as total", FALSE);
   
       




          $this->db->from($this->registros.' as m');
          $this->db->join($this->almacenes.' As a' , 'a.id = m.id_almacen AND a.activo=1');
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
/*
          if ($data['id_factura']!=0) {
              $id_facturaid = ' AND ( m.id_factura =  '.$data['id_factura'].' ) ';  
          } else {
              $id_facturaid = '';
          } 
*/
          

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


                //if ( ($data['val_prod_id'] !="")  && ($data['val_prod_id'] !="0") ) {
                if (($id_descripcion!="0") AND ($id_descripcion!="") AND ($id_descripcion!= null))  {                
                    $where.= (($where!="") ? " and " : "") . "( m.id_descripcion  =  '".$id_descripcion."' )";
                    $where_total.= (($where_total!="") ? " and " : "") . "( m.id_descripcion  =  '".$id_descripcion."' )";
                }



          $where_total.= $donde.$fechas; //$donde.



          $this->db->where($where.$donde.$fechas); //
    
       
          //ordenacion

          $this->db->order_by('m.factura', 'asc'); 

          $result = $this->db->get();

            if ( $result->num_rows() > 0 )
               return $result->row();
            else
               return False;
            $result->free_result();                              
              



      }  


///////////////////////////////////////////////////////////////////////////////////////////


      public function totales_consulta_totales($data){

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
                ( totale LIKE  "%'.$cadena.'%" ) OR (totals LIKE  "%'.$cadena.'%") OR
                ( totald LIKE  "%'.$cadena.'%" ) OR (fecha LIKE  "%'.$cadena.'%")
               )
           )';   



      $result = $this->db->query('

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

           
         ) todo

      ');  

//order by '.$columna.' '.$order.'



            if ( $result->num_rows() > 0 )
               return $result->row();
            else
               return False;
            $result->free_result();                              
              

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

       

          

      ');  
//order by '.$columna.' '.$order.'



            if ( $result->num_rows() > 0 )
               return $result->result();
            else
               return False;
            $result->free_result();                              
              

      }  
///////////////////////////////////////////////
///////////////////////////////////////////////






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





    public function totales_entrada_devolucion($data){

      

          $cadena = addslashes($data['busqueda']);
          $inicio = 0; //$data['start'];
          $largo = 10000; //$data['length'];

          $estatus= $data['extra_search'];
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
          

          $this->db->from($this->historico_registros_entradas.' as m');
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



          //if (($id_descripcion!="0") AND ($id_descripcion!="") AND ($id_descripcion!= null))  {                
          if ( ($data['val_prod_id'] !="")  && ($data['val_prod_id'] !="0") ) {
              $where.= (($where!="") ? " and " : "") . "( m.id_descripcion  =  '".$id_descripcion."' )";
              $where_total.= (($where_total!="") ? " and " : "") . "( m.id_descripcion  =  '".$id_descripcion."' )";
          }      
          if  (($id_color!="0") AND ($id_color!="") AND ($id_color!= null)) {
             $where.= (($where!="") ?  " and " : "") . "( m.id_color  =  ".$id_color." )";
             $where_total.= (($where_total!="") ?  " and " : "") . "( m.id_color  =  ".$id_color." )";
          }
          if (($id_composicion!="0") AND ($id_composicion!="") AND ($id_composicion!= null)) {
              $where.= (($where!="") ? " and " : "") . "( m.id_composicion  =  ".$id_composicion." ) ";
              $where_total.= (($where_total!="") ? " and " : "") . "( m.id_composicion  =  ".$id_composicion." ) ";
          } 
          if  (($id_calidad!="0") AND ($id_calidad!="") AND ($id_calidad!= null)) {
             $where.= (($where!="") ? " and " : "") . "( m.id_calidad  =  ".$id_calidad." )";
             $where_total.= (($where_total!="") ? " and " : "") . "( m.id_calidad  =  ".$id_calidad." )";
          }     



          $where_total.= $donde.$fechas; //$donde.



          $this->db->where($where.$donde.$fechas); //
    
        
   
          //ordenacion

          $this->db->order_by('m.factura', 'asc'); 

          $result = $this->db->get();


           

          if ( $result->num_rows() > 0 )
             return $result->row();
          else
             return False;
          $result->free_result();    



   }     






    public function buscador_entrada_devolucion($data){

          $cadena = addslashes($data['busqueda']);
          $inicio = 0; //$data['start'];
          $largo = 10000; //$data['length'];

          $estatus= $data['extra_search'];
          $id_estatus= $data['id_estatus'];
          $id_empresa= addslashes($data['proveedor']);
          $id_almacen= $data['id_almacen'];

          $factura_reporte = addslashes($data['factura_reporte']);

          switch ($data['columna']) {
                   case '0':
                        $columna = 'm.codigo';
                     break;
                   case '1':
                        $columna = 'm.id_descripcion';
                     break;
                   case '2':
                        $columna = 'c.color';
                     break;
                   case '3':
                        $columna = 'm.cantidad_um';
                     break;
                   case '4':
                        $columna = 'm.ancho';
                     break;
                   case '5':
                        $columna = 'm.movimiento';
                     break;
                   case '6':
                              $columna= 'p.nombre';
                     break;
                   case '7':
                              $columna= 'm.id_lote, m.consecutivo';  
                     break;
                   case '8':
                        $columna = 'm.fecha_entrada';
                     break;

                   case '12': //'9':
                        $columna = 'm.factura';
                     break;

                   case '13':
                        $columna = 'm.num_partida';
                     break;
                   case '14':
                        $columna = 'm.id_almacen';
                     break;                       

                   
                   default:
                       /*$columna = 'm.factura';
                       $order = 'asc';
                       */
                       $columna = 'm.id';
                       $order = 'DESC';                       
                     break;
                 }                 
           



          

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

          $this->db->select('m.id, m.movimiento,m.id_empresa, m.factura, m.id_descripcion, m.id_operacion, m.num_partida');
          $this->db->select('m.id_color, m.id_composicion, m.id_calidad, m.referencia');
          $this->db->select('m.id_medida,  m.cantidad_royo, m.ancho, m.precio, m.codigo, m.comentario');
          $this->db->select('m.id_estatus, m.id_lote, m.consecutivo, m.id_cargador, m.id_usuario, m.fecha_mac fecha, m.fecha_entrada,fecha_apartado');
          $this->db->select('c.hexadecimal_color, c.color, p.nombre');
          $this->db->select('DATE_FORMAT(m.fecha_entrada,"%d/%m/%Y") as ppp',false);


          $this->db->select('m.cantidad_um, u.medida');

          $this->db->select('
                        CASE m.id_apartado
                          WHEN "1" THEN "ab1d1d"
                           WHEN "2" THEN "f1a914"
                           WHEN "3" THEN "14b80f"
                           
                           WHEN "4" THEN "ab1d1d"
                           WHEN "5" THEN "f1a914"
                           WHEN "6" THEN "14b80f"

                           ELSE "No Apartado"
                        END AS apartado
            ',False);

          $this->db->select("( CASE WHEN m.id_medida = 1 THEN m.cantidad_um ELSE 0 END ) AS metros", FALSE);
          $this->db->select("( CASE WHEN m.id_medida = 2 THEN m.cantidad_um ELSE 0 END ) AS kilogramos", FALSE);

          $this->db->select("( CASE WHEN  (m.devolucion <> 0)  THEN 'red'  
                                    WHEN  (m.id_apartado <> 0)  THEN 'morado' 
                                    ELSE 'black' END )
                             AS color_devolucion", FALSE);   
          
          $this->db->select("a.almacen");

          $this->db->select("prod.codigo_contable");  

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



          //if (($id_descripcion!="0") AND ($id_descripcion!="") AND ($id_descripcion!= null))  {                
          if ( ($data['val_prod_id'] !="")  && ($data['val_prod_id'] !="0") ) {
              $where.= (($where!="") ? " and " : "") . "( m.id_descripcion  =  '".$id_descripcion."' )";
              $where_total.= (($where_total!="") ? " and " : "") . "( m.id_descripcion  =  '".$id_descripcion."' )";
          }      
          if  (($id_color!="0") AND ($id_color!="") AND ($id_color!= null)) {
             $where.= (($where!="") ?  " and " : "") . "( m.id_color  =  ".$id_color." )";
             $where_total.= (($where_total!="") ?  " and " : "") . "( m.id_color  =  ".$id_color." )";
          }
          if (($id_composicion!="0") AND ($id_composicion!="") AND ($id_composicion!= null)) {
              $where.= (($where!="") ? " and " : "") . "( m.id_composicion  =  ".$id_composicion." ) ";
              $where_total.= (($where_total!="") ? " and " : "") . "( m.id_composicion  =  ".$id_composicion." ) ";
          } 
          if  (($id_calidad!="0") AND ($id_calidad!="") AND ($id_calidad!= null)) {
             $where.= (($where!="") ? " and " : "") . "( m.id_calidad  =  ".$id_calidad." )";
             $where_total.= (($where_total!="") ? " and " : "") . "( m.id_calidad  =  ".$id_calidad." )";
          }     
        


          $where_total.= $donde.$fechas; //$donde.
          $this->db->where($where.$donde.$fechas); //

          //ordenacion

            $this->db->order_by($columna, $data['orden']); 


          $result = $this->db->get();


            if ( $result->num_rows() > 0 )
               return $result->result();
            else
               return False;
            $result->free_result();



      }  





////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////




    public function totales_entradas($data){

          $cadena = addslashes($data['busqueda']);
          $inicio = 0; //$data['start'];
          $largo = 10000; //$data['length'];

          $estatus= $data['extra_search'];
          $id_estatus= $data['id_estatus'];
          $id_empresa= addslashes($data['proveedor']);
          $id_almacen= $data['id_almacen'];

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


 

          //SELECT * FROM `inven_registros_entradas` WHERE codigo="wPLt92500130092016112934_150" or codigo="AebwT1940013009201685322_72" 

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





             
          //if (($id_descripcion!="0") AND ($id_descripcion!="") AND ($id_descripcion!= null))  {                
          if ( ($data['val_prod_id'] !="")  && ($data['val_prod_id'] !="0") ) {
              $where.= (($where!="") ? " and " : "") . "( m.id_descripcion  =  '".$id_descripcion."' )";
              $where_total.= (($where_total!="") ? " and " : "") . "( m.id_descripcion  =  '".$id_descripcion."' )";
          }      
          if  (($id_color!="0") AND ($id_color!="") AND ($id_color!= null)) {
             $where.= (($where!="") ?  " and " : "") . "( m.id_color  =  ".$id_color." )";
             $where_total.= (($where_total!="") ?  " and " : "") . "( m.id_color  =  ".$id_color." )";
          }
          if (($id_composicion!="0") AND ($id_composicion!="") AND ($id_composicion!= null)) {
              $where.= (($where!="") ? " and " : "") . "( m.id_composicion  =  ".$id_composicion." ) ";
              $where_total.= (($where_total!="") ? " and " : "") . "( m.id_composicion  =  ".$id_composicion." ) ";
          } 
          if  (($id_calidad!="0") AND ($id_calidad!="") AND ($id_calidad!= null)) {
             $where.= (($where!="") ? " and " : "") . "( m.id_calidad  =  ".$id_calidad." )";
             $where_total.= (($where_total!="") ? " and " : "") . "( m.id_calidad  =  ".$id_calidad." )";
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
          $id_almacen= $data['id_almacen'];

          $factura_reporte = addslashes($data['factura_reporte']);

            switch ($data['columna']) {
                   case '0':
                        $columna = 'm.codigo';
                     break;
                   case '1':
                        $columna = 'm.id_descripcion';
                     break;
                   case '2':
                        $columna = 'c.color';
                     break;
                   case '3':
                        $columna = 'm.cantidad_um';
                     break;
                   case '4':
                        $columna = 'm.ancho';
                     break;
                   case '5':
                        $columna = 'm.movimiento';
                     break;
                   case '6':
                          if ($estatus=="apartado") {
                              $columna= 'pr.nombre';
                          }  else {
                              $columna= 'p.nombre';
                          }  
                     break;
                   case '7':
                          if ($estatus=="apartado") {
                              $columna= 'm.id_apartado';
                          }  else {
                              $columna= 'm.id_lote, m.consecutivo';  
                          }  
                     break;
                   case '8':
                        $columna = 'm.fecha_entrada';
                     break;

                   case '12': //'9':
                        $columna = 'm.factura';
                     break;

                   case '13':
                        $columna = 'm.num_partida';
                     break;
                   case '14':
                        $columna = 'm.id_almacen';
                     break;                       

                   
                   default:
                       /*$columna = 'm.factura';
                       $order = 'asc'; */
                       $columna = 'm.id';
                       $order = 'DESC';                       

                     break;
                 }              


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

          

          $this->db->select('m.id, m.movimiento,m.id_empresa, m.factura, m.id_descripcion, m.id_operacion, m.num_partida');
          $this->db->select('m.id_color, m.id_composicion, m.id_calidad, m.referencia');
          $this->db->select('m.id_medida,  m.cantidad_royo, m.ancho, m.precio, m.codigo, m.comentario');
          $this->db->select('m.id_estatus, m.id_lote, m.consecutivo, m.id_cargador, m.id_usuario, m.fecha_mac fecha, m.fecha_entrada,fecha_apartado');
          $this->db->select('c.hexadecimal_color, c.color, p.nombre, m.id_apartado');
          $this->db->select('DATE_FORMAT(m.fecha_entrada,"%d/%m/%Y") as ppp',false);


          
          
          if ($estatus=="apartado") {
              $this->db->select('pr.nombre as dependencia', FALSE);
          }

          $this->db->select('m.cantidad_um, u.medida');

          $this->db->select('
                        CASE m.id_apartado
                          WHEN "1" THEN "ab1d1d"
                           WHEN "2" THEN "f1a914"
                           WHEN "3" THEN "14b80f"
                           
                           WHEN "4" THEN "ab1d1d"
                           WHEN "5" THEN "f1a914"
                           WHEN "6" THEN "14b80f"

                           ELSE "No Apartado"
                        END AS apartado
            ',False);

          $this->db->select("( CASE WHEN m.id_medida = 1 THEN m.cantidad_um ELSE 0 END ) AS metros", FALSE);
          $this->db->select("( CASE WHEN m.id_medida = 2 THEN m.cantidad_um ELSE 0 END ) AS kilogramos", FALSE);
          $this->db->select("( CASE WHEN m.devolucion <> 0 THEN 'red' ELSE 'black' END ) AS color_devolucion", FALSE);

          $this->db->select("a.almacen");
          $this->db->select("prod.codigo_contable");  

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


             
          //if (($id_descripcion!="0") AND ($id_descripcion!="") AND ($id_descripcion!= null))  {                
          if ( ($data['val_prod_id'] !="")  && ($data['val_prod_id'] !="0") ) {
              $where.= (($where!="") ? " and " : "") . "( m.id_descripcion  =  '".$id_descripcion."' )";
              $where_total.= (($where_total!="") ? " and " : "") . "( m.id_descripcion  =  '".$id_descripcion."' )";
          }      
          if  (($id_color!="0") AND ($id_color!="") AND ($id_color!= null)) {
             $where.= (($where!="") ?  " and " : "") . "( m.id_color  =  ".$id_color." )";
             $where_total.= (($where_total!="") ?  " and " : "") . "( m.id_color  =  ".$id_color." )";
          }
          if (($id_composicion!="0") AND ($id_composicion!="") AND ($id_composicion!= null)) {
              $where.= (($where!="") ? " and " : "") . "( m.id_composicion  =  ".$id_composicion." ) ";
              $where_total.= (($where_total!="") ? " and " : "") . "( m.id_composicion  =  ".$id_composicion." ) ";
          } 
          if  (($id_calidad!="0") AND ($id_calidad!="") AND ($id_calidad!= null)) {
             $where.= (($where!="") ? " and " : "") . "( m.id_calidad  =  ".$id_calidad." )";
             $where_total.= (($where_total!="") ? " and " : "") . "( m.id_calidad  =  ".$id_calidad." )";
          }     


          $where_total.= $donde.$fechas; //$donde.



          $this->db->where($where.$donde.$fechas); //
    
          //ordenacion

            $this->db->order_by($columna, $data['orden']); 
          //paginacion
          //$this->db->limit($largo,$inicio); 


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
          $id_almacen= $data['id_almacen'];

          $factura_reporte = addslashes($data['factura_reporte']);



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
          } */

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

          //if (($id_descripcion!="0") AND ($id_descripcion!="") AND ($id_descripcion!= null))  {                
          if ( ($data['val_prod_id'] !="")  && ($data['val_prod_id'] !="0") ) {
              $where.= (($where!="") ? " and " : "") . "( m.id_descripcion  =  '".$id_descripcion."' )";
              $where_total.= (($where_total!="") ? " and " : "") . "( m.id_descripcion  =  '".$id_descripcion."' )";
          }      
          if  (($id_color!="0") AND ($id_color!="") AND ($id_color!= null)) {
             $where.= (($where!="") ?  " and " : "") . "( m.id_color  =  ".$id_color." )";
             $where_total.= (($where_total!="") ?  " and " : "") . "( m.id_color  =  ".$id_color." )";
          }
          if (($id_composicion!="0") AND ($id_composicion!="") AND ($id_composicion!= null)) {
              $where.= (($where!="") ? " and " : "") . "( m.id_composicion  =  ".$id_composicion." ) ";
              $where_total.= (($where_total!="") ? " and " : "") . "( m.id_composicion  =  ".$id_composicion." ) ";
          } 
          if  (($id_calidad!="0") AND ($id_calidad!="") AND ($id_calidad!= null)) {
             $where.= (($where!="") ? " and " : "") . "( m.id_calidad  =  ".$id_calidad." )";
             $where_total.= (($where_total!="") ? " and " : "") . "( m.id_calidad  =  ".$id_calidad." )";
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


        switch ($data['columna']) {
                   case '0':
                        $columna = 'm.codigo';
                     break;
                   case '1':
                        $columna = 'm.id_descripcion';
                     break;
                   case '2':
                        $columna = 'c.color';
                     break;
                   case '3':
                        $columna = 'm.cantidad_um';
                     break;
                   case '4':
                        $columna = 'm.ancho';
                     break;
                   case '5':
                        $columna = 'm.mov_salida';
                     break;
                   case '6':
                             $columna= 'us.nombre';
                     break;
                   case '7':
                              $columna= 'm.id_lote, m.consecutivo';  
                     break;
                   case '8':
                        $columna = 'm.fecha_salida';
                     break;

                   case '12': //'9':
                        $columna = 'm.factura';
                     break;

                   case '13':
                        $columna = 'm.num_partida';
                     break;

                   case '14':
                        $columna = 'm.id_almacen';
                     break;                       
                   
                   default:
                       /*$columna = 'm.factura';
                       $order = 'asc';
                       */
                       $columna = 'm.mov_salida';
                       $order = 'ASC';                       
                     break;
                 }           


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

          

          $this->db->select('m.id, m.mov_salida,m.id_empresa, m.factura, m.id_descripcion, m.id_operacion, m.num_partida');
          $this->db->select('m.id_color, m.id_composicion, m.id_calidad, m.referencia');
          $this->db->select('m.id_medida,  m.cantidad_royo, m.ancho, m.precio, m.codigo, m.comentario');
          $this->db->select('m.id_estatus, m.id_lote, m.consecutivo, m.id_cargador, m.id_usuario, m.fecha_mac fecha, m.fecha_entrada');
          $this->db->select('c.hexadecimal_color, c.color ');
          $this->db->select('m.cliente, m.cargador, m.fecha_salida'); //, p.nombre

          $this->db->select('us.nombre as nombre', FALSE);    //
          
          if ($estatus=="apartado") {
              $this->db->select('pr.nombre as dependencia', FALSE);
          }

          $this->db->select('m.cantidad_um, u.medida');

          $this->db->select('
                        CASE m.id_apartado
                          WHEN "1" THEN "ab1d1d"
                           WHEN "2" THEN "f1a914"
                           WHEN "3" THEN "14b80f"
                           
                           WHEN "4" THEN "ab1d1d"
                           WHEN "5" THEN "f1a914"
                           WHEN "6" THEN "14b80f"

                           ELSE "No Apartado"
                        END AS apartado
            ',False);

          $this->db->select("( CASE WHEN m.id_medida = 1 THEN m.cantidad_um ELSE 0 END ) AS metros", FALSE);
          $this->db->select("( CASE WHEN m.id_medida = 2 THEN m.cantidad_um ELSE 0 END ) AS kilogramos", FALSE);
          
          $this->db->select("( CASE WHEN m.devolucion <> 0 THEN 'red' ELSE 'black' END ) AS color_devolucion", FALSE);
          
          $this->db->select("a.almacen");         
          $this->db->select("prod.codigo_contable");  

          $this->db->select("tp.tipo_pedido");          
          
          $this->db->select("tf.tipo_factura");          

          $this->db->select('m.id_tipo_pedido,m.id_tipo_factura', FALSE);


          $this->db->from($this->historico_registros_salidas.' as m');
          $this->db->join($this->almacenes.' As a' , 'a.id = m.id_almacen AND a.activo=1');
          $this->db->join($this->productos.' As prod' , 'prod.referencia = m.referencia','LEFT');
          $this->db->join($this->colores.' As c' , 'c.id = m.id_color','LEFT');
          $this->db->join($this->unidades_medidas.' As u' , 'u.id = m.id_medida','LEFT');
          $this->db->join($this->proveedores.' As us' , 'us.id = m.consecutivo_venta','LEFT'); //
          $this->db->join($this->proveedores.' As p' , 'p.id = m.id_cliente','LEFT');
          $this->db->join($this->tipos_pedidos.' As tp' , 'tp.id = m.id_tipo_pedido','LEFT');
          $this->db->join($this->tipos_facturas.' As tf' , 'tf.id = m.id_tipo_factura','LEFT');



                               



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


          //if (($id_descripcion!="0") AND ($id_descripcion!="") AND ($id_descripcion!= null))  {                
          if ( ($data['val_prod_id'] !="")  && ($data['val_prod_id'] !="0") ) {
              $where.= (($where!="") ? " and " : "") . "( m.id_descripcion  =  '".$id_descripcion."' )";
              $where_total.= (($where_total!="") ? " and " : "") . "( m.id_descripcion  =  '".$id_descripcion."' )";
          }      
          if  (($id_color!="0") AND ($id_color!="") AND ($id_color!= null)) {
             $where.= (($where!="") ?  " and " : "") . "( m.id_color  =  ".$id_color." )";
             $where_total.= (($where_total!="") ?  " and " : "") . "( m.id_color  =  ".$id_color." )";
          }
          if (($id_composicion!="0") AND ($id_composicion!="") AND ($id_composicion!= null)) {
              $where.= (($where!="") ? " and " : "") . "( m.id_composicion  =  ".$id_composicion." ) ";
              $where_total.= (($where_total!="") ? " and " : "") . "( m.id_composicion  =  ".$id_composicion." ) ";
          } 
          if  (($id_calidad!="0") AND ($id_calidad!="") AND ($id_calidad!= null)) {
             $where.= (($where!="") ? " and " : "") . "( m.id_calidad  =  ".$id_calidad." )";
             $where_total.= (($where_total!="") ? " and " : "") . "( m.id_calidad  =  ".$id_calidad." )";
          }     



          $where_total .= $donde.$fechas;

          $this->db->where($where.$donde.$fechas); //

    
          //ordenacion
          //$this->db->order_by('m.factura', 'asc');
          $this->db->order_by($columna, $data['orden']); 

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
          $id_almacen= $data['id_almacen'];

          $factura_reporte = addslashes($data['factura_reporte']);


          $id_session = $this->db->escape($this->session->userdata('id'));


           $this->db->select('p.referencia, p.comentario');
          $this->db->select('p.descripcion, p.minimo, p.imagen, p.precio');
          $this->db->select('c.hexadecimal_color,c.color nombre_color');
          $this->db->select("co.composicion", FALSE);  
          $this->db->select("ca.calidad", FALSE);  



         $fechas = ' ';
          if  ( ($data['fecha_inicial'] !="") and  ($data['fecha_final'] !="")) {
                           $fecha_inicial = date( 'Y-m-d', strtotime( $data['fecha_inicial'] ));
                           $fecha_final = date( 'Y-m-d', strtotime( $data['fecha_final'] ));
                          
                            $fechas .= ' AND ( ( DATE_FORMAT((m.fecha_salida),"%Y-%m-%d")  >=  "'.$fecha_inicial.'" )  AND  ( DATE_FORMAT((m.fecha_salida),"%Y-%m-%d")  <=  "'.$fecha_final.'" ) )'; 


                          
          } else {
           $fechas .= ' ';
          }

          $this->db->select("COUNT(m.referencia) as 'suma'");
          

          $this->db->select("( CASE WHEN m.id_medida = 1 THEN m.cantidad_um ELSE 0 END ) AS metros", FALSE);
          $this->db->select("( CASE WHEN m.id_medida = 2 THEN m.cantidad_um ELSE 0 END ) AS kilogramos", FALSE);

          $this->db->select("a.almacen,p.codigo_contable");

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
          $this->db->order_by('suma', 'desc');

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
          
                   //productos
          //$id_descripcion= $data['id_descripcion'];
          $id_descripcion= addslashes($data['id_descripcion']);
          $id_color= $data['id_color'];
          $id_composicion= $data['id_composicion'];
          $id_calidad= $data['id_calidad'];


          switch ($data['columna']) {
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
                        $columna = 'p.imagen'; //
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


          $this->db->select('p.referencia, p.comentario');
          $this->db->select('p.descripcion, p.minimo, p.imagen, p.precio');
          $this->db->select('c.hexadecimal_color,c.color nombre_color');
          $this->db->select("co.composicion", FALSE);  
          $this->db->select("ca.calidad", FALSE);  
          $this->db->select("COUNT(m.referencia) as 'suma'");

          $this->db->select("( CASE WHEN m.id_medida = 1 THEN m.cantidad_um ELSE 0 END ) AS metros", FALSE);
          $this->db->select("( CASE WHEN m.id_medida = 2 THEN m.cantidad_um ELSE 0 END ) AS kilogramos", FALSE);

         
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
          $this->db->select("p.codigo_contable");  


          $this->db->from($this->productos.' as p');
          $this->db->join($this->colores.' As c', 'p.id_color = c.id'); //,'LEFT'
          $this->db->join($this->composiciones.' As co', 'p.id_composicion = co.id'); //,'LEFT'
          $this->db->join($this->calidades.' As ca', 'p.id_calidad = ca.id'); //,'LEFT'
          $this->db->join($this->registros.' As m', 'm.referencia= p.referencia and m.id_estatus=12 '.$id_almacenid.$id_facturaid.$id_empresaid,'LEFT'); //
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

             
          //if (($id_descripcion!="0") AND ($id_descripcion!="") AND ($id_descripcion!= null))  {                
          if ( ($data['val_prod_id'] !="")  && ($data['val_prod_id'] !="0") ) {
              $where.= (($where!="") ? " and " : "") . "( p.descripcion  =  '".$id_descripcion."' )";
              $where_cond.= (($where_cond!="") ? " and " : "") . "( p.descripcion  =  '".$id_descripcion."' )";
          }      
          if  (($id_color!="0") AND ($id_color!="") AND ($id_color!= null)) {
             $where.= (($where!="") ?  " and " : "") . "( p.id_color  =  ".$id_color." )";
             $where_cond.= (($where_cond!="") ?  " and " : "") . "( p.id_color  =  ".$id_color." )";
          }
          if (($id_composicion!="0") AND ($id_composicion!="") AND ($id_composicion!= null)) {
              $where.= (($where!="") ? " and " : "") . "( p.id_composicion  =  ".$id_composicion." ) ";
              $where_cond.= (($where_cond!="") ? " and " : "") . "( p.id_composicion  =  ".$id_composicion." ) ";
          } 
          if  (($id_calidad!="0") AND ($id_calidad!="") AND ($id_calidad!= null)) {
             $where.= (($where!="") ? " and " : "") . "( p.id_calidad  =  ".$id_calidad." )";
             $where_cond.= (($where_cond!="") ? " and " : "") . "( p.id_calidad  =  ".$id_calidad." )";
          }        
    
          

          $this->db->where($where);

            $this->db->order_by($columna, $data['orden']); 

          //$this->db->group_by("p.referencia,p.descripcion, p.minimo, p.imagen, p.precio, c.hexadecimal_color,c.color,co.composicion,ca.calidad");
          //paginacion
          $this->db->group_by("p.referencia, p.minimo,  p.precio"); //p.imagen,


         if ($estatus=="cero") {
              $this->db->having('suma <= 0');
              $where_total = 'suma <= 0';
          }   

          if ($estatus=="baja") {
              $this->db->having('((suma>0) AND (suma < p.minimo))');
              $where_total = '((suma>0) AND (suma < p.minimo))';
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

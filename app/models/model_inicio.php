<?php if(! defined('BASEPATH')) exit('No tienes permiso para acceder a este archivo');

  class model_inicio extends CI_Model {
    
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
      $this->actividad_comercial     = $this->db->dbprefix('catalogo_actividad_comercial');
      
      $this->estratificacion_empresa = $this->db->dbprefix('catalogo_estratificacion_empresa');
      
      $this->productos               = $this->db->dbprefix('catalogo_productos');
      $this->proveedores             = $this->db->dbprefix('catalogo_empresas');
      $this->cargadores             = $this->db->dbprefix('catalogo_cargador');
      $this->unidades_medidas        = $this->db->dbprefix('catalogo_unidades_medidas');

      $this->operaciones             = $this->db->dbprefix('catalogo_operaciones');
      $this->movimientos               = $this->db->dbprefix('movimientos');
      $this->registros_temporales               = $this->db->dbprefix('temporal_registros');
      $this->registros               = $this->db->dbprefix('registros_entradas');
      $this->registros_salidas       = $this->db->dbprefix('registros_salidas');

      $this->colores                 = $this->db->dbprefix('catalogo_colores');
      $this->unidades_medidas        = $this->db->dbprefix('catalogo_unidades_medidas');
      
      $this->historico_registros_entradas = $this->db->dbprefix('historico_registros_entradas');
      $this->historico_registros_salidas = $this->db->dbprefix('historico_registros_salidas');
      
      $this->composiciones     = $this->db->dbprefix('catalogo_composicion');
      $this->calidades                 = $this->db->dbprefix('catalogo_calidad');

      $this->registros_entradas               = $this->db->dbprefix('registros_entradas');
      $this->registros_cambios               = $this->db->dbprefix('registros_cambios');
      $this->almacenes               = $this->db->dbprefix('catalogo_almacenes');


    }

//Tabla de INICIO para vendedores (presentación completa)

public function agrupando_inicio($data){

          $cadena = addslashes($data['search']['value']);
          $inicio = $data['start'];
          $largo = $data['length'];

          $id_estatus= $data['id_estatus'];
            $id_color= $data['id_color'];
            $id_almacen= $data['id_almacen'];
            $id_factura_inicio= $data['id_factura_inicio'];



          $id_session = $this->db->escape($this->session->userdata('id'));

          $this->db->select("SQL_CALC_FOUND_ROWS(p.grupo)"); // , FALSE

          $this->db->select(

            ' CASE WHEN MAX( p.id_imagen_check)="1" THEN 
                (
                  select pr.imagen FROM inven_catalogo_productos pr where ( (pr.id_imagen_check="1")  and (pr.grupo=p.grupo) )
                )
               ELSE  "no" END AS imagen
            ', FALSE
            );

          $this->db->select("COUNT(m.referencia) as 'suma'");

          $this->db->select('p.grupo');
          $this->db->select('p.descripcion'); //, p.imagen
          

        
         if ($id_almacen!=0) {
              $id_almacenid = ' AND ( m.id_almacen =  '.$id_almacen.' ) ';  
          } else {
              $id_almacenid ='';
          } 

          if ($id_factura_inicio!=0) {
              $id_tipo_facturaid = ' AND ( m.id_factura =  '.$id_factura_inicio.' ) ';  
          } else {
              $id_tipo_facturaid = '';
          } 

          //$this->db->select("a.almacen");
          $this->db->select("p.codigo_contable");  
          $this->db->from($this->productos.' as p');
          $this->db->join($this->colores.' As c', 'c.id = p.id_color'); // ,'LEFT' 
          //$this->db->join($this->composiciones.' As co', 'co.id = p.id_composicion','LEFT'); // 
          //$this->db->join($this->calidades.' As ca', 'ca.id=p.id_calidad','LEFT'); // 
          $this->db->join($this->registros.' As m', 'm.referencia = p.referencia'.$id_almacenid.$id_tipo_facturaid,'LEFT'); //
          //$this->db->join($this->almacenes.' As a' , 'a.id = m.id_almacen AND a.activo=1');


          //filtro de busqueda 
          //OR (co.composicion LIKE  "%'.$cadena.'%")  OR  ( ca.calidad LIKE  "%'.$cadena.'%" ) 
          $where = '(
                      
                      
                      (
                        ( p.referencia LIKE  "%'.$cadena.'%" ) OR (p.descripcion LIKE  "%'.$cadena.'%") OR (p.minimo LIKE  "%'.$cadena.'%")  OR
                        ( p.precio LIKE  "%'.$cadena.'%" ) OR (c.color LIKE  "%'.$cadena.'%") 
                       )

            ) ' ;   

          $where_total = '';
          if ($id_estatus!=-1) {
              $where .= ' AND ( m.id_estatus = '.$id_estatus.' ) ' ;   
              $where_total = ' ( m.id_estatus = '.$id_estatus.' ) ' ;   
          }    

          if ($id_color!=-1) {
              $where .= ' AND ( m.id_color = '.$id_color.' ) ' ;   
              $where_total = ' ( m.id_color = '.$id_color.' ) ' ;  
          }    

         if ($id_almacen!=0) {
                   $where .= ' AND ( m.id_almacen =  '.$id_almacen.' ) ';  
              $where_total = ' ( m.id_almacen =  '.$id_almacen.' ) ';  

          } 


         if ($id_factura_inicio!=0) {
                   $where .= ' AND ( m.id_factura =  '.$id_factura_inicio.' ) ';  
              $where_total = ' ( m.id_factura =  '.$id_factura_inicio.' ) ';  

          }  

          $this->db->where($where);



     
          $this->db->order_by("id_imagen_check", 'DESC'); 
          $this->db->order_by("suma", 'DESC'); 




          $this->db->group_by("p.grupo"); //,p.descripcion, ca.calidad, p.precio, p.minimo, p.imagen,

          $this->db->limit($largo,$inicio); 


          $result = $this->db->get();

              if ( $result->num_rows() > 0 ) {

                    $cantidad_consulta = $this->db->query("SELECT FOUND_ROWS() as cantidad");
                    $found_rows = $cantidad_consulta->row(); 
                    $registros_filtrados =  ( (int) $found_rows->cantidad);

                  $i=0;  $j=-1;  

                          $fechaSegundos = time(); 
                          $strNoCache = "?nocache=$fechaSegundos"; 


                  foreach ($result->result() as $row) {

                           $j= $j+ ((intval(($i % 4) ==0))*1); 
                           $x= intval($i % 4) ; 
                           if ($x==0) {
                              $dato[0]="";$dato[1]="";$dato[2]="";$dato[3]="";$dato[4]="";
                           }


                           /*
                          $fechaSegundos = time(); 
                          $strNoCache = "?nocache=$fechaSegundos"; 
                          */

                           $dato[$x][0]=  $row->imagen.$strNoCache; //substr($row->imagen,0,-4).'_thumb'.substr($row->imagen,-4);
                           $dato[$x][1]=  $row->descripcion;
                           $dato[$x][2]=  $row->grupo;
                           $dato[$x][3]=  ( self::cantidad_metro_agrupando($row->grupo) );  
                           $dato[$x][4]=  $row->codigo_contable;
                           $datas[$j] = $dato;
                        $i++;
                      }

                      $datos=$datas;
                      return json_encode ( array(
                        "draw"            => intval( $data['draw'] ),
                        "recordsTotal"    => $registros_filtrados, 
                        "recordsFiltered" => $registros_filtrados, 
                        "data"            =>  $datas, 
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


       public function cantidad_metro_agrupando($grupo){

          $id_session = $this->session->userdata('id');

          $this->db->select('u.medida, SUM(m.cantidad_um) as total'); 
         
          $this->db->from($this->registros.' as m');
          $this->db->join($this->unidades_medidas.' As u' , 'u.id = m.id_medida','LEFT');
          $this->db->join($this->productos.' As p' , 'p.referencia = m.referencia','LEFT');

          $where = '(
                        (
                          ( ( m.id_usuario_apartado = "'.$id_session.'" )  AND  (m.id_apartado = 1) ) OR (m.id_apartado = 0)
                          
                        ) 
                       AND
                      (
                        ( p.grupo = "'.$grupo.'")  AND (estatus_salida  = "0")
                       )

                    )';   

          $this->db->where($where);
          
          $this->db->group_by("u.medida");
          $this->db->order_by('u.medida', 'desc'); 
          
          //filtro de busqueda

          $result = $this->db->get();
          if ($result->num_rows() > 0) {

                $valor =''; 
                foreach ($result->result() as $filas) {        
                     $valor .=    number_format($filas->total, 2, '.', ',').' '.$filas->medida.'<br/>' ;
                  }
                  return 'Disponibilidad: '.$valor;
          }
          else 
             return 'Producto sin existencia';
            $result->free_result();    

      }        


  /////////////////////////////////////////Detalles del producto

 public function el_producto($data){

          $id_session = $this->db->escape($this->session->userdata('id'));
          $id_almacen= $data['id_almacen'];

          $this->db->select('p.referencia');
          $this->db->select('p.descripcion, p.minimo, p.imagen, p.precio');
          $this->db->select('c.hexadecimal_color,c.color nombre_color');
          $this->db->select("co.composicion", FALSE);  
          $this->db->select("ca.calidad", FALSE);  

          $this->db->from($this->productos.' as p');
          $this->db->join($this->colores.' As c', 'p.id_color = c.id','LEFT');
          $this->db->join($this->composiciones.' As co', 'p.id_composicion = co.id','LEFT');
          $this->db->join($this->calidades.' As ca', 'p.id_calidad = ca.id','LEFT');
          $this->db->join($this->registros.' As m', 'p.referencia = m.referencia','LEFT'); //,'RIGHT'

          $this->db->where('p.grupo', $data['grupo']);

          if ($id_almacen!=0) {
              $this->db->where('m.id_almacen', $data['id_almacen']);
          } else {
              //no son todos
          } 


          $result = $this->db->get();
          if ($result->num_rows() > 0)
              return $result->row();
          else 
              return FALSE;
          $result->free_result();
}        


     

//grupos de colores validos para el detalle del grupo
 public function grupo_colores($data){


          $id_session = $this->db->escape($this->session->userdata('id'));
          $id_almacen= $data['id_almacen'];

          //$this->db->select('p.referencia');
          $this->db->select('c.id id_color, c.hexadecimal_color, p.precio');

          $this->db->select('p.imagen');

          $this->db->select('COUNT(*) cantidad', FALSE);
/*
          $this->db->select('
                          CASE COUNT(*)
                            WHEN 1 THEN   CONCAT(c.color," (NE)")
                             ELSE  c.color
                          END AS nombre_color
          ',False);
*/
          $this->db->select('( CASE WHEN ( (COUNT(*) = 1) and (m.cantidad_royo is NULL)  ) THEN CONCAT(c.color," (NE)") ELSE c.color END ) AS nombre_color', FALSE);




          $this->db->from($this->productos.' as p');
          $this->db->join($this->colores.' As c', 'p.id_color = c.id','LEFT');
          $this->db->join($this->registros.' As m', 'p.referencia = m.referencia','LEFT'); //,'RIGHT'

          $this->db->where('p.grupo', $data['grupo']);

          if ($id_almacen!=0) {
              $this->db->where('m.id_almacen', $data['id_almacen']);
          } else {
              //no son todos
          } 

          //$this->db->group_by("p.referencia,c.id,c.hexadecimal_color,c.color");
          $this->db->group_by("c.id,c.hexadecimal_color,c.color");

          $result = $this->db->get();
          if ($result->num_rows() > 0)
              return $result->result();
          else 
              return FALSE;
          $result->free_result();
}        




  //cuando abre el grupo, la 1ra regilla, los colores
      public function productos_colores($data){

          $cadena = addslashes($data['search']['value']);
          $inicio = $data['start'];
          $largo = $data['length'];
          $grupo = $data['grupo'];

          $id_color = $data['id_color'];
          $id_almacen= $data['id_almacen'];
          

          $columa_order = $data['order'][0]['column'];
                 $order = $data['order'][0]['dir'];

          switch ($columa_order) {
                   case '0':
                        $columna = 'm.codigo';
                     break;
                   case '1':
                        $columna = 'm.id_lote';
                     break;
                   case '2':
                        $columna = 'm.id_medida, u.medida';
                     break;
                   case '3':
                        $columna = 'm.ancho';
                     break;
                   case '4':
                        $columna = 'm.fecha_entrada';
                     break;
                                
                   default:
                       $columna = 'm.codigo';
                     break;
                 }                 


          $id_session = ($this->session->userdata('id'));

          $this->db->select("SQL_CALC_FOUND_ROWS *", FALSE); //

          $this->db->select('m.id, m.movimiento,m.id_empresa, m.factura, m.id_descripcion, m.id_operacion, m.id_apartado');
          $this->db->select('m.id_color, m.id_composicion, m.id_calidad, m.referencia');
          $this->db->select('m.id_medida,  m.cantidad_royo, m.ancho, m.precio, m.codigo, m.comentario');
          $this->db->select('m.id_estatus, m.id_lote, m.consecutivo, m.id_cargador, m.id_usuario, m.fecha_mac fecha, m.fecha_entrada');
          $this->db->select('c.hexadecimal_color, c.color, u.medida,p.nombre');

          //$this->db->select("CONCAT(m.id_medida,' ',u.medida) AS cantidad", FALSE);
          $this->db->select("CONCAT(m.cantidad_um,' ',u.medida) AS cantidad", FALSE);
  
          $this->db->select("a.almacen");                 
          $this->db->from($this->registros.' as m');
          $this->db->join($this->almacenes.' As a' , 'a.id = m.id_almacen AND a.activo=1');
          $this->db->join($this->colores.' As c' , 'c.id = m.id_color','LEFT');
          $this->db->join($this->unidades_medidas.' As u' , 'u.id = m.id_medida','LEFT');
          $this->db->join($this->proveedores.' As p' , 'p.id = m.id_empresa','LEFT');
          $this->db->join($this->productos.' As pr' , 'pr.referencia = m.referencia','LEFT');
          

          //filtro de busqueda

          if ($id_almacen!=0) {
              $id_almacenid = ' AND ( m.id_almacen =  '.$id_almacen.' ) ';  
          } else {
              $id_almacenid = '';
          }           
        
          $where = '(

                      (
                         
                        ( ( m.id_usuario_apartado = "'.$id_session.'" )  AND  (m.id_apartado = 1) ) OR (m.id_apartado = 0) 
                      ) AND ( m.proceso_traspaso = 0 )
                        AND                     
                      (
                        ( pr.grupo = "'.$grupo.'")  AND   ( m.id_color = '.$id_color.')  AND   (estatus_salida  = "0")   
                       )
                       AND

                      (
                        ( m.codigo LIKE  "%'.$cadena.'%" ) OR (m.id_lote LIKE  "%'.$cadena.'%") OR (m.ancho LIKE  "%'.$cadena.'%")  OR
                        (CONCAT(m.id_medida," ",u.medida) LIKE  "%'.$cadena.'%")
                       )'.$id_almacenid.'

            )';   


          $where_total = '(( ( m.id_usuario_apartado = "'.$id_session.'" )  AND  (m.id_apartado = 1) ) OR (m.id_apartado = 0) ) 
                        AND                     
                      (
                        ( pr.grupo = "'.$grupo.'")  AND   ( m.id_color = '.$id_color.')  AND   (estatus_salida  = "0")   
                       )'.$id_almacenid;


  
          $this->db->where($where);
    
 
         

         //ordenacion
          $this->db->order_by($columna, $order); 

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
                                      1=>$row->id_lote,
                                      2=>$row->cantidad,
                                      3=>number_format($row->ancho, 2, '.', ','),                                      
                                      4=>date( 'd-m-Y', strtotime($row->fecha_entrada)),
                                      5=>$row->id,
                                      6=>$row->id_apartado,
                                      7=>$row->almacen,
             

                                    );
                      }




                      return json_encode ( array(
                        "draw"            => intval( $data['draw'] ),
                        "recordsTotal"    => $registros_filtrados, //intval( self::total_detalle_colores($where_total) ),  //10
                        "recordsFiltered" => $registros_filtrados, 
                        "data"            =>  $dato,
                        

                        "totales"         =>  array(
                                                    "metro_disp"       =>(self::total_porcolor($where_total)->metro_disp ), 
                                                    "metro_nodisp"     =>(self::total_porcolor($where_total)->metro_nodisp ), 

                                                    "kilogramo_disp"   =>(self::total_porcolor($where_total)->kilogramo_disp ),
                                                    "kilogramo_nodisp" =>(self::total_porcolor($where_total)->kilogramo_nodisp ),
                                              ),  

                      ));



                    
              }   
              else {
                  $output = array(
                      "draw"              =>  intval( $data['draw'] ),
                      "recordsTotal"      => 0, 
                      "recordsFiltered"   => 0,
                      "aaData"            => array(),
                  );
                  $array[]="";
                  return json_encode($output);
              }
              $result->free_result();           
      }  
      

   public function total_porcolor($where_total){

              $id_session = $this->session->userdata('id');

              $this->db->select("SUM( (m.id_medida =1) * (m.id_apartado = 0)  * m.cantidad_um) as metro_disp", FALSE);
              $this->db->select("SUM( (m.id_medida =1) * (m.id_apartado = 1)  * m.cantidad_um) as metro_nodisp", FALSE);

              $this->db->select("SUM((m.id_medida =2) * (m.id_apartado = 0) * m.cantidad_um) as kilogramo_disp", FALSE);
              $this->db->select("SUM((m.id_medida =2) * (m.id_apartado = 1) * m.cantidad_um) as kilogramo_nodisp", FALSE);
             
              $this->db->from($this->registros.' as m');
              $this->db->join($this->colores.' As c' , 'c.id = m.id_color','LEFT');
              $this->db->join($this->unidades_medidas.' As u' , 'u.id = m.id_medida','LEFT');
              $this->db->join($this->proveedores.' As p' , 'p.id = m.id_empresa','LEFT');
              $this->db->join($this->productos.' As pr' , 'pr.referencia = m.referencia','LEFT');


              if ($where_total !='') {
                  $this->db->where($where_total);  
              }


                $result = $this->db->get();
          
              if ( $result->num_rows() > 0 )
                 return $result->row();
              else
                 return False;
              $result->free_result();         
       }

//cuando abre el grupo, la 2da regilla
public function productos_colores2($data){

          $cadena = addslashes($data['search']['value']);
          $inicio = $data['start'];
          $largo = $data['length'];
          $grupo = $data['grupo'];

          $id_color = $data['id_color'];
          $id_almacen= $data['id_almacen'];
          

          $columa_order = $data['order'][0]['column'];
                 $order = $data['order'][0]['dir'];

          switch ($columa_order) {
                   case '0':
                        $columna = 'm.codigo';
                     break;
                   case '1':
                        $columna = 'm.id_lote';
                     break;
                   case '2':
                        $columna = 'm.id_medida, u.medida';
                     break;
                   case '3':
                        $columna = 'm.ancho';
                     break;
                   case '4':
                        $columna = 'm.fecha_entrada';
                     break;
                                
                   default:
                       $columna = 'm.codigo';
                     break;
                 }                 


          $id_session = ($this->session->userdata('id'));

          $this->db->select("SQL_CALC_FOUND_ROWS *", FALSE); //

          $this->db->select('m.id, m.movimiento,m.id_empresa, m.factura, m.id_descripcion, m.id_operacion, m.id_apartado');
          $this->db->select('m.id_color, m.id_composicion, m.id_calidad, m.referencia');
          $this->db->select('m.id_medida,  m.cantidad_royo, m.ancho, m.precio, m.codigo, m.comentario');
          $this->db->select('m.id_estatus, m.id_lote, m.consecutivo, m.id_cargador, m.id_usuario, m.fecha_mac fecha, m.fecha_entrada');
          $this->db->select('c.hexadecimal_color, c.color, u.medida,p.nombre');

          $this->db->select("CONCAT(m.id_medida,' ',u.medida) AS cantidad", FALSE);
  
          $this->db->select("a.almacen");                 
          $this->db->from($this->registros.' as m');
          $this->db->join($this->almacenes.' As a' , 'a.id = m.id_almacen AND a.activo=1');
          $this->db->join($this->colores.' As c' , 'c.id = m.id_color','LEFT');
          $this->db->join($this->unidades_medidas.' As u' , 'u.id = m.id_medida','LEFT');
          $this->db->join($this->proveedores.' As p' , 'p.id = m.id_empresa','LEFT');
          $this->db->join($this->productos.' As pr' , 'pr.referencia = m.referencia','LEFT');
          
          //filtro de busqueda

          
          if ($id_almacen!=0) {
              $id_almacenid = ' AND ( m.id_almacen =  '.$id_almacen.' ) ';  
          } else {
              $id_almacenid = '';
          } 
        
          $where = '(

                      (
                         
                        ( ( m.id_usuario_apartado != "'.$id_session.'" )  AND  (m.id_apartado = 1) ) 
                      ) 
                        AND                     
                      (
                        ( pr.grupo = "'.$grupo.'")  AND   ( m.id_color = '.$id_color.')  AND   (estatus_salida  = "0")   
                       )
                       AND

                      (
                        ( m.codigo LIKE  "%'.$cadena.'%" ) OR (m.id_lote LIKE  "%'.$cadena.'%") OR (m.ancho LIKE  "%'.$cadena.'%")  OR
                        (CONCAT(m.id_medida," ",u.medida) LIKE  "%'.$cadena.'%")
                       )'.$id_almacenid.'

            )';   


          $where_total = '( ( ( m.id_usuario_apartado = "'.$id_session.'" )  AND  (m.id_apartado = 1) ) OR (m.id_apartado = 0) )
              AND                     
            (
              ( pr.grupo = "'.$grupo.'")  AND   ( m.id_color = '.$id_color.')  AND   (estatus_salida  = "0")   
             )'.$id_almacenid;


  
          $this->db->where($where);
    
 
         

         //ordenacion
          $this->db->order_by($columna, $order); 

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
                                      1=>$row->id_lote,
                                      2=>$row->cantidad,
                                      3=>number_format($row->ancho, 2, '.', ','),                                      
                                      4=>date( 'd-m-Y', strtotime($row->fecha_entrada)),
                                      5=>$row->id,
                                      6=>$row->id_apartado,
                                      7=>$row->almacen,
             

                                    );
                      }




                      return json_encode ( array(
                        "draw"            => intval( $data['draw'] ),
                        "recordsTotal"    => $registros_filtrados, //intval( self::total_detalle_colores($where_total) ),  //10
                        "recordsFiltered" => $registros_filtrados, 
                        "data"            =>  $dato 
                      ));
                    
              }   
              else {
                  $output = array(
                  "draw" =>  intval( $data['draw'] ),
                  "recordsTotal"    => 0,
                  "recordsFiltered" =>0,
                  "aaData" => array()
                  );
                  $array[]="";
                  return json_encode($output);
                  

              }

              $result->free_result();           

      }  
      



     //Marcar o desmarcar un producto
        public function marcando_apartado( $data ){
              
                $id_session = $this->session->userdata('id');

                $this->db->set( 'id_usuario_apartado',  $id_session );
                $this->db->set( 'id_apartado', '(1 XOR id_apartado)', FALSE );
                $this->db->where('id', $data['id'] );
                $this->db->update($this->registros );

                $this->db->select( 'id_apartado' );
                $this->db->where('id', $data['id'] );

                $colo_apartar = $this->db->get($this->registros );
               $apartado = $colo_apartar->row();
               return $apartado->id_apartado;
        }     




///////////Una vez que procesa apartado de vendedores, nos muestra una tabla de todos los productos apartado/////////


        public function valores_movimientos_temporal(){   //este 

          $id_session = $this->session->userdata('id');
          
          $this->db->distinct();          
          $this->db->select('m.id, m.id_cliente, m.id_cargador, m.factura');
          $this->db->select('p.nombre, ca.nombre cargador');
          
          $this->db->from($this->registros_salidas.' as m');
          $this->db->join($this->proveedores.' As p' , 'p.id = m.id_cliente','LEFT');
          $this->db->join($this->cargadores.' As ca' , 'ca.id = m.id_cargador','LEFT');

          $this->db->where('m.id_usuario',$id_session);
          $this->db->where('id_operacion',2);
           $result = $this->db->get();
        
            if ( $result->num_rows() > 0 )
               return $result->row();
            else
               return False;
            $result->free_result();
        }   



         public function buscador_apartado_vendedores($data){

          $id_session = $this->session->userdata('id');

          $cadena = addslashes($data['search']['value']);
          $inicio = $data['start'];
          $largo = $data['length'];

          

          $columa_order = $data['order'][0]['column'];
                 $order = $data['order'][0]['dir'];

           if ($data['draw'] ==1) { //que se ordene por el ultimo
                 $columa_order ='-1';
                 $order = 'desc';
           } 



          switch ($columa_order) {
                   case '0':
                        $columna = 'm.codigo';
                     break;
                   case '1':
                        $columna = 'm.id_descripcion';
                     break;
                   case '2':
                        $columna = 'm.id_color';
                     break;
                   case '3':
                        $columna = 'm.cantidad_um';
                     break;
                   case '4':
                        $columna = 'm.ancho';
                     break;

                   case '5':
                        $columna = 'm.lote , m.consecutivo';
                     break;

                   
                   default:
                        $columna = 'm.id_descripcion';
                     break;
                 }                 

                                      

          

          $this->db->select("SQL_CALC_FOUND_ROWS(m.id)"); //
          
          //m.id_operacion,, m.devolucion,  m.factura,m.id_medida, m.cantidad_royo, ,  m.comentario, m.id_cargador,,  m.id_usuario, m.fecha_mac fecha
          //$this->db->select('m.id_color, m.id_composicion, m.id_calidad, m.referencia');
          $this->db->select('m.id, m.codigo,m.id_descripcion');
          
          $this->db->select('m.cantidad_um,  m.ancho, m.precio');
          $this->db->select('m.id_estatus, m.id_lote, m.consecutivo');

          $this->db->select('c.color, c.hexadecimal_color, u.medida');

          $this->db->select("a.almacen");
          $this->db->select("prod.codigo_contable");  
          $this->db->from($this->registros.' as m');
          $this->db->join($this->almacenes.' As a' , 'a.id = m.id_almacen AND a.activo=1');
          $this->db->join($this->productos.' As prod' , 'prod.referencia = m.referencia'); //,'LEFT'
          $this->db->join($this->colores.' As c' , 'c.id = m.id_color'); //,'LEFT'
          $this->db->join($this->unidades_medidas.' As u' , 'u.id = m.id_medida'); //,'LEFT'
          

          //filtro de busqueda
  
       
          $where = '(
                    (
                      ( m.id_usuario_apartado = "'.$id_session.'" ) and  ( m.id_apartado =  1 )
                    ) AND
                      (
                        ( m.codigo LIKE  "%'.$cadena.'%" ) OR (m.id_descripcion LIKE  "%'.$cadena.'%") OR
                        ( m.cantidad_um LIKE  "%'.$cadena.'%" ) OR (m.ancho LIKE  "%'.$cadena.'%") OR
                        ( m.id_lote LIKE  "%'.$cadena.'%" ) OR (m.consecutivo LIKE  "%'.$cadena.'%") 
                       )
            )';   






  
          $this->db->where($where);
    
          //ordenacion
          //$this->db->order_by($columna, $order); 

          //paginacion
          $this->db->limit($largo,$inicio); 

          $misdatos['where'] = '(( m.id_usuario_apartado = "'.$id_session.'" ) and  ( m.id_apartado =  1 ))';

          $result = $this->db->get();

              if ( $result->num_rows() > 0 ) {

                    $cantidad_consulta = $this->db->query("SELECT FOUND_ROWS() as cantidad");
                    $found_rows = $cantidad_consulta->row(); 
                    $registros_filtrados =  ( (int) $found_rows->cantidad);

                  $retorno= " ";  



                  foreach ($result->result() as $row) {
                               $dato[]= array(
                                      0=>$row->codigo,
                                      1=>$row->id_descripcion,
                                      2=> $row->color.
                                        '<div style="background-color:#'.$row->hexadecimal_color.';display:block;width:15px;height:15px;margin:0 auto;"></div>',
                                      3=>$row->cantidad_um.' '.$row->medida,
                                      4=>$row->ancho.' cm',
                                      5=>$row->precio,
                                      6=>$row->id_lote.'-'.$row->consecutivo,
                                      7=>$row->id,
                                      8=>$row->almacen,
                                      9=>$row->codigo_contable,          
                                      10=>$row->id_estatus,    
                                    );
                      }




                      return json_encode ( array(
                        "draw"            => intval( $data['draw'] ),
                        "recordsTotal"    => $registros_filtrados, //
                        "recordsFiltered" => $registros_filtrados, 
                        "data"            =>  $dato,
                        "totales"         =>  array("pieza"=>intval( self::total_campos($misdatos)->pieza ), 
                                                    "metro"=>(self::total_campos($misdatos)->metros ), 
                                                    "kilogramo"=>(self::total_campos($misdatos)->kilogramos ),
                                                    "precio"=>(self::total_campos($misdatos)->precio )
                                              ),  
                      ));
                    
              }   
              else {
                  $output = array(
                  "draw" =>  intval( $data['draw'] ),
                  "recordsTotal" => 0,
                  "recordsFiltered" =>0,
                  "aaData"    => array(),
                  
                  );
                  $array[]="";
                  return json_encode($output);
              }

              $result->free_result();           

      } 



      public function total_campos($data){
              $id_session = $this->session->userdata('id');


              $this->db->select("SUM((id_medida =1) * cantidad_um) as metros", FALSE);
              $this->db->select("SUM((id_medida =2) * cantidad_um) as kilogramos", FALSE);
              $this->db->select("COUNT(m.id_medida) as 'pieza'");
              $this->db->select("sum(m.precio) as 'precio'");
              
             
              $this->db->from($this->registros.' as m');
              $this->db->join($this->colores.' As c' , 'c.id = m.id_color','LEFT');
              $this->db->join($this->unidades_medidas.' As u' , 'u.id = m.id_medida','LEFT');


              $this->db->where($data['where']);

             $result = $this->db->get();
          
              if ( $result->num_rows() > 0 )
                 return $result->row();
              else
                 return False;
              $result->free_result();              

       }      



    
       //en la regilla de apartado, puede eliminar por productos
        public function eliminar_apartado_vendedor( $data ){
              
                $id_session = $this->session->userdata('id');
                $fecha_hoy = date('Y-m-d H:i:s');  

                $this->db->set( 'fecha_vencimiento', '' ); 
                $this->db->set( 'id_prorroga', 0);
                $this->db->set( 'fecha_apartado', '' );  
                $this->db->set( 'id_cliente_apartado', 0 );
                $this->db->set( 'id_apartado', 0);
                $this->db->set( 'id_usuario_apartado', '');
                $this->db->set( 'consecutivo_venta', 0);

                $this->db->where('id', $data['id'] );
                $this->db->update($this->registros );

                if ($this->db->affected_rows() > 0) {
                  return TRUE;
                }  else
                   return FALSE;


        }      


//////////////////////////
//////////////////////////
//////////////////////////
//////////////////////////

//////////////////////////
//////////////////////////
//////////////////////////
//////////////////////////



//////////////////////////
//////////////////////////
//////////////////////////
//////////////////////////


   











 



        //****************este es para obtener proveedor y factura temporal************************************************************



     



       public function consecutivo_operacion( $id,$id_tipo_pedido,$id_tipo_factura ){
              $this->db->select("o.consecutivo,o.conse_factura,o.conse_remision,o.conse_surtido,o.conse_bodega");         
              $this->db->from($this->operaciones.' As o');
              $this->db->where('o.id',$id);
              $result = $this->db->get( );
                  if ($result->num_rows() > 0) {
                  $consecutivo_actual = (( ($id_tipo_pedido == 1) && ($id_tipo_factura==1) ) ? $result->row()->conse_factura : $result->row()->conse_remision );
                  $consecutivo_actual = ( ($id_tipo_pedido==2) ? $result->row()->conse_surtido : $consecutivo_actual);
                  $consecutivo_actual = ( ($id_tipo_pedido==3) ? $result->row()->conse_bodega : $consecutivo_actual);
                        return $consecutivo_actual+1;
                  }                    
                  else 
                      return FALSE;
                  $result->free_result();
       }  


       public function consecutivo_operacion_unico( $id ){
              $this->db->select("o.consecutivo");         
              $this->db->from($this->operaciones.' As o');
              $this->db->where('o.id',$id);
              $result = $this->db->get( );
                  if ($result->num_rows() > 0) {
                        return $result->row()->consecutivo+1;
                  }                    
                  else 
                      return FALSE;
                  $result->free_result();
       }  


        //cambiar estatus de unidad
        public function apartar_definitivamente( $data ){
              
                $id_session = $this->session->userdata('id');
                $fecha_hoy = date('Y-m-d H:i:s');  

                

                  //obtener consecutivos para pasarlo a la impresion
                $consecutivo = $this->modelo_inicio->consecutivo_operacion($data['id_operacion_pedido'],$data['id_tipo_pedido'],$data['id_tipo_factura']);
                $consecutivo_unico = $this->modelo_inicio->consecutivo_operacion_unico($data['id_operacion_pedido']); 


                $this->db->set( 'consecutivo_venta', $consecutivo );  
                $this->db->set( 'movimiento_unico_apartado', $consecutivo_unico ); 

                $this->db->set( 'fecha_vencimiento', $fecha_hoy  );  
                $this->db->set( 'fecha_apartado', $fecha_hoy  );  
                $this->db->set( 'id_cliente_apartado',  $data['id_cliente'] );
                $this->db->set( 'id_apartado', 2, FALSE );


                $this->db->set( 'id_tipo_factura', $data['id_tipo_factura']);
                $this->db->set( 'id_tipo_pedido', $data['id_tipo_pedido']);
                $this->db->set( 'peso_real', 0); //esto es para q aparezca en 0 el peso_real cdo haga pedido

                
                $this->db->set( 'id_operacion_pedido', $data['id_operacion_pedido'] );  //new


                $this->db->where('id_usuario_apartado', $id_session );
                $this->db->where('id_apartado', 1 );

                $this->db->update($this->registros );


              //actualizar (consecutivo) en tabla "operacion"   96

              if ($data['id_tipo_pedido']==3) {
                   $this->db->set( 'conse_bodega', 'conse_bodega+1', FALSE  );  
              } else if ($data['id_tipo_pedido']==2) {
                   $this->db->set( 'conse_surtido', 'conse_surtido+1', FALSE  );  
              }  else if ($data['id_tipo_factura']==1) {
                  $this->db->set( 'conse_factura', 'conse_factura+1', FALSE  );  
              } else {
                  $this->db->set( 'conse_remision', 'conse_remision+1', FALSE  );  
              }
              $this->db->set( 'consecutivo', 'consecutivo+1', FALSE  );  //no porq se actualiza con el 4   

              $this->db->set( 'id_usuario', $id_session );
              $this->db->where('id',$data['id_operacion_pedido']);
              $this->db->update($this->operaciones);




                if ($this->db->affected_rows() > 0) {
                  return $consecutivo_unico;
                }  else
                   return FALSE;
       
        }         
   
 



        public function actualizar_consecutivo_pedido_multiples_almacenes($data) {
                $id_session = $this->session->userdata('id');

                //agarrar todos los almacenes
                $this->db->select('m.id_almacen, a.almacen'); 
                $this->db->from($this->registros.' as m');
                $this->db->join($this->almacenes.' As a' , 'a.id = m.id_almacen'); 
                $where = '(
                          (
                            ( m.id_usuario_apartado = "'.$id_session.'" ) and  ( m.id_apartado = 2 ) 
                            AND ( m.movimiento_unico_apartado = '.$data['consecutivo_unico'].' )
                            AND  ( m.id_tipo_pedido = '.$data['id_tipo_pedido'].' )
                            AND  ( m.id_tipo_factura = '.$data['id_tipo_factura'].' )
                            AND  ( m.id_operacion_pedido = '.$data['id_operacion_pedido'].' )
                          ) 
                  )';   
                $this->db->where($where);
                $this->db->group_by('m.id_almacen');

                $objeto = $this->db->get()->result();
                foreach ($objeto as $key => $value) {
                           

                            
                            $data['id_almacen'] = $value->id_almacen;
                            
                            //sino esta creado, lo crea primero q nada, para q no lo ponga en cero
                            $new_consecutivo1 = $this->catalogo->consecutivo_general_pedido($data);
                            //actualizando nuevos consecutivos
                             $this->catalogo->actualizando_nuevos_consecutivos_pedido($data);
                             //Obtener nuevos consecutivos
                             $new_consecutivo   = $this->catalogo->consecutivo_general_pedido($data);



                             $this->db->set('cp1', $new_consecutivo->c1,false); 
                             $this->db->set('cp2', $new_consecutivo->c2,false); 
                             $this->db->set('cp1234', $new_consecutivo->c1234,false); 
                             $this->db->set('cp234', $new_consecutivo->c234,false); 
                             $this->db->set('cp34', $new_consecutivo->c34,false); 

                              $where = '(
                                        (
                                          ( m.id_usuario_apartado = "'.$id_session.'" ) and  ( m.id_apartado = 2 ) 
                                          AND ( m.movimiento_unico_apartado = '.$data['consecutivo_unico'].' )
                                          AND  ( m.id_tipo_pedido = '.$data['id_tipo_pedido'].' )
                                          AND  ( m.id_tipo_factura = '.$data['id_tipo_factura'].' )
                                          AND  ( m.id_operacion_pedido = '.$data['id_operacion_pedido'].' )

                                          AND  ( m.id_almacen = '.$data['id_almacen'].' )
                                    

                                        ) 
                                )';   
                              $this->db->where($where);
                              $this->db->update($this->registros.' as m' );

                              $datum[]= ( ($data['id_operacion_pedido']==4) ? '[S] ' : (($data['id_operacion_pedido']==96) ? '[A] ' : (($data['id_operacion_pedido']==97) ? '[T] ' :  (($data['id_operacion_pedido']==98) ? '[B] ' :'[S] ') )) ).$value->id_almacen.'-'.$data['factor_salida'].'-'.$new_consecutivo->c234;



                }

                return json_encode($datum);

        }



//////////////////////Imprimir/////////////////////////////
      public function imprimir_apartar_definitivamente( $data ){

                $id_session = $this->session->userdata('id');
                $fecha_hoy = date('Y-m-d H:i:s');  

                $this->db->select("SQL_CALC_FOUND_ROWS *", FALSE); //


                
                $this->db->select('m.movimiento_unico_apartado, m.consecutivo_venta'); //consecutivos

                $this->db->select('m.id,  m.factura, m.id_descripcion, m.id_operacion,m.devolucion');
                $this->db->select('m.id_color, m.id_composicion, m.id_calidad, m.referencia');
                $this->db->select('m.id_medida, m.cantidad_um, m.cantidad_royo, m.ancho, m.precio, m.codigo, m.comentario');
                $this->db->select('m.id_estatus, m.id_lote, m.consecutivo, m.id_cargador, m.id_usuario, m.fecha_mac fecha');

                $this->db->select('c.hexadecimal_color, u.medida');
                $this->db->select("prod.codigo_contable");  

                $this->db->from($this->registros.' as m');
                $this->db->join($this->productos.' As prod' , 'prod.referencia = m.referencia','LEFT');
                $this->db->join($this->colores.' As c' , 'c.id = m.id_color','LEFT');
                $this->db->join($this->unidades_medidas.' As u' , 'u.id = m.id_medida','LEFT');

                
                //filtro de busqueda
                $where = '(
                          (
                            ( m.id_usuario_apartado = "'.$id_session.'" ) and  ( m.id_apartado =  2 ) 
                            AND ( m.movimiento_unico_apartado = '.$data['consecutivo_unico'].' )
                            AND  ( m.id_operacion_pedido = '.$data['id_operacion_pedido'].' )
                            
                          ) 
                  )';   
                $this->db->where($where);
                $result = $this->db->get();

                if ( $result->num_rows() > 0 )
                   return $result->result();
                else
                   return False;
                $result->free_result();
       
        }     




  
    public function imprimir_total_campos($data){
              $id_session = $this->session->userdata('id');


              $this->db->select('m.movimiento_unico_apartado, m.consecutivo_venta'); //consecutivos
              $this->db->select("SUM((id_medida =1) * cantidad_um) as metros", FALSE);
              $this->db->select("SUM((id_medida =2) * cantidad_um) as kilogramos", FALSE);
              $this->db->select("COUNT(m.id_medida) as 'pieza'");
              $this->db->select("sum(m.precio) as 'precio'");

             
              $this->db->from($this->registros.' as m');
              $this->db->join($this->colores.' As c' , 'c.id = m.id_color','LEFT');
              $this->db->join($this->unidades_medidas.' As u' , 'u.id = m.id_medida','LEFT');

                           
                $where = '(
                          (
                            ( m.id_usuario_apartado = "'.$id_session.'" ) and  ( m.id_apartado =  2 )
                            AND ( m.movimiento_unico_apartado = '.$data['consecutivo_unico'].' )
                            AND  ( m.id_operacion_pedido = '.$data['id_operacion_pedido'].' )
                          ) 
                  )';   

       
            $this->db->where($where);

             $result = $this->db->get();
          
              if ( $result->num_rows() > 0 )
                 return $result->row();
              else
                 return False;
              $result->free_result();              

       }      






/////////////////////////////////////////////////////NO SE USA///////////////////////////////////////////////////
/////////////////////////////////////////////////////NO SE USA///////////////////////////////////////////////////      
/////////////////////////////////////////////////////NO SE USA///////////////////////////////////////////////////
/////////////////////////////////////////////////////INICIO vendedores////////////////////////////////////////////////

    //listado de los productos apartados para un usuario

        public function listado_apartado(){

          $id_session = $this->session->userdata('id');
                    
          $this->db->select('m.id,  m.factura, m.id_descripcion, m.id_operacion,m.devolucion');
          $this->db->select('m.id_color, m.id_composicion, m.id_calidad, m.referencia');
          $this->db->select('m.id_medida, m.cantidad_um, m.cantidad_royo, m.ancho, m.precio, m.codigo, m.comentario');
          $this->db->select('m.id_estatus, m.id_lote, m.consecutivo, m.id_cargador, m.id_usuario, m.fecha_mac fecha');

          $this->db->select('c.hexadecimal_color, u.medida');

          $this->db->from($this->registros.' as m');
          $this->db->join($this->colores.' As c' , 'c.id = m.id_color','LEFT');
          $this->db->join($this->unidades_medidas.' As u' , 'u.id = m.id_medida','LEFT');

          $this->db->where('m.id_usuario_apartado',$id_session);
          $this->db->where('m.id_apartado',1);

           $result = $this->db->get();
        
            if ( $result->num_rows() > 0 )
               return $result->result();
            else
               return False;
            $result->free_result();
        }   




    public function buscador_inicio($data){

          $cadena = addslashes($data['search']['value']);
          $inicio = $data['start'];
          $largo = $data['length'];

          $id_estatus= $data['id_estatus'];
            $id_color= $data['id_color'];


          $id_session = $this->db->escape($this->session->userdata('id'));

          $this->db->select("SQL_CALC_FOUND_ROWS *", FALSE); //

          $this->db->select('p.referencia');
          $this->db->select('p.descripcion, p.minimo, p.imagen, p.precio');
          $this->db->select('c.hexadecimal_color,c.color nombre_color');
          $this->db->select("co.composicion", FALSE);  
          $this->db->select("ca.calidad", FALSE);  
          $this->db->select("COUNT(m.referencia) as 'suma'");

          $this->db->from($this->productos.' as p');
          $this->db->join($this->colores.' As c', 'p.id_color = c.id','LEFT');
          $this->db->join($this->composiciones.' As co', 'p.id_composicion = co.id','LEFT');
          $this->db->join($this->calidades.' As ca', 'p.id_calidad = ca.id','LEFT');
          $this->db->join($this->registros.' As m', 'p.referencia = m.referencia','LEFT');

          //filtro de busqueda
          $where = '(
                      
                      
                      (
                        ( p.referencia LIKE  "%'.$cadena.'%" ) OR (p.descripcion LIKE  "%'.$cadena.'%") OR (p.minimo LIKE  "%'.$cadena.'%")  OR
                        ( p.precio LIKE  "%'.$cadena.'%" ) OR (c.color LIKE  "%'.$cadena.'%") OR (co.composicion LIKE  "%'.$cadena.'%")  OR
                        ( ca.calidad LIKE  "%'.$cadena.'%" ) 
                       )

            ) ' ;   

          if ($id_estatus!=-1) {
              $where .= ' AND ( m.id_estatus = '.$id_estatus.' ) ' ;   
          }    

          if ($id_color!=-1) {
              $where .= ' AND ( m.id_color = '.$id_color.' ) ' ;   
          }    


          $this->db->where($where);


          $this->db->group_by("p.referencia,p.descripcion, p.minimo, p.imagen, p.precio, c.hexadecimal_color,c.color,co.composicion,ca.calidad");

          $this->db->limit($largo,$inicio); 


          $result = $this->db->get();

              if ( $result->num_rows() > 0 ) {

                    $cantidad_consulta = $this->db->query("SELECT FOUND_ROWS() as cantidad");
                    $found_rows = $cantidad_consulta->row(); 
                    $registros_filtrados =  ( (int) $found_rows->cantidad);

                  $i=0;  $j=-1;  
                  //$datos[]= $array();
                  foreach ($result->result() as $row) {

                           $j= $j+ ((intval(($i % 4) ==0))*1); 
                           $x= intval($i % 4) ; 
                           if ($x==0) {
                              $dato[0]="";$dato[1]="";$dato[2]="";$dato[3]="";
                           }

                           $dato[$x][0]=  $row->imagen; //substr($row->imagen,0,-4).'_thumb'.substr($row->imagen,-4);
                           $dato[$x][1]=  $row->descripcion;
                           $dato[$x][2]=  $row->nombre_color;
                           $dato[$x][3]=  $row->hexadecimal_color;
                           $dato[$x][4]=  $row->referencia;
                           $dato[$x][5]=  ( self::cantidad_metro($row->referencia) );  

                           
                           $datas[$j] = $dato;
                        $i++;
                      }

                      $datos=$datas;
                      return json_encode ( array(
                        "draw"            => intval( $data['draw'] ),
                        "recordsTotal"    => intval( self::total_productos_vendedor() ),  
                        "recordsFiltered" => $registros_filtrados, 
                        "data"            =>  $datas, 
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


    public function cantidad_metro($referencia){


          $id_session = $this->session->userdata('id');

          $this->db->select('u.medida, SUM(m.cantidad_um) as total');

          //$this->db->select("CONCAT(m.id_medida,' ',u.medida) AS cantidad", FALSE);
         
          $this->db->from($this->registros.' as m');
          $this->db->join($this->unidades_medidas.' As u' , 'u.id = m.id_medida','LEFT');

          $where = '(
                        (
                          ( ( m.id_usuario_apartado = "'.$id_session.'" )  AND  (m.id_apartado = 1) ) OR (m.id_apartado = 0)
                          
                        ) 
                       AND
                      (
                        ( m.referencia = "'.$referencia.'")  AND (estatus_salida  = "0")
                       )

                    )';   

          $this->db->where($where);
          
          $this->db->group_by("u.medida");
          $this->db->order_by('u.medida', 'desc'); 
          
          //filtro de busqueda

          $result = $this->db->get();
          if ($result->num_rows() > 0) {

                $valor =''; 
                foreach ($result->result() as $filas)
                  {                   
                     $valor .=    $filas->total.' '.$filas->medida.'<br/>' ;
                  }
                  return 'Disponibilidad: '.$valor;
          }

            
          else 
             return 'Producto sin existencia';
            $result->free_result();    

      }        





      public function total_productos_vendedor(){
              $id_session = $this->session->userdata('id');
              $this->db->from($this->productos.' as p');
              $cant = $this->db->count_all_results();          
     
              if ( $cant > 0 )
                 return $cant;
              else
                 return 0;         
       }






  } 






?>

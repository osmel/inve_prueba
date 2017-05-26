<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Conteo_fisico extends CI_Controller {

  public function __construct(){
    parent::__construct();
    $this->load->model('model_conteo_fisico', 'model_conteo_fisico');
    $this->load->model('catalogo', 'catalogo');  
    $this->load->model('modelo', 'modelo');  
    $this->load->model('model_pedido', 'modelo_pedido');    
    $this->load->model('model_pedido_compra', 'model_pedido_compra'); 
    $this->load->model('model_entradas', 'model_entrada');  

    $this->load->model('model_salida', 'modelo_salida');  


    $this->load->library(array('email')); 
    $this->load->library('Jquery_pagination');//-->la estrella del equipo 


$this->load->library('form_validation');
$this->load->library('session');
$this->load->helper('form');
$this->load->helper('url');
$this->load->library('cart');

$this->load->helper('string');
$this->load->library('email');
$this->load->library("Pdf");


  }


   public function generar_conteos_historico($id_almacen,$modulo,$movimiento){
  

        $data['id_almacen']       = base64_decode($id_almacen);
        $data['modulo']           = base64_decode($modulo);
        $data['movimiento']    = base64_decode($movimiento);
        /////////////

        set_time_limit(0); 
        ignore_user_abort(1);
        ini_set('memory_limit','512M'); 

        $this->load->library('Pdf');
        $pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetTitle('Titulo Generación de Etiqueta');
        $pdf->SetSubject('Subtitulo');
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
 
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
 

        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
 
        $pdf->setFontSubsetting(true);

        $pdf->SetFont('Times', '', 8,'','true');

 
        $pdf->setTextShadow(array('enabled' => true, 'depth_w' => 0.2, 'depth_h' => 0.2, 'color' => array(196, 196, 196), 'opacity' => 1, 'blend_mode' => 'Normal'));
 
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        $pdf->SetMargins(10, 10, 10,true);
        
        $pdf->SetAutoPageBreak(true, 10);

        $pdf->AddPage('P', array( 215.9,  279.4)); //en mm 21.59cm por 27.94cm



        $data['movimientos'] = $this->model_conteo_fisico->reporte_conteos_historico($data);

        

        //$data['etiq_mov'] ="Salida";  

       
        $html = $this->load->view('pdfs/conteos/conteos_historico', $data, true);
        

        // Imprimimos el texto con writeHTMLCell()
        $pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
         
        // ---------------------------------------------------------
        // Cerrar el documento PDF y preparamos la salida
        // Este método tiene varias opciones, consulte la documentación para más información.
        $nombre_archivo = utf8_decode("Conteos_".$data['id_almacen'].".pdf");
        $pdf->Output($nombre_archivo, 'I');



    }



public function generar_historico_inventarios(){
        $data=$_POST;


        /////////////

        set_time_limit(0); 
        ignore_user_abort(1);
        ini_set('memory_limit','512M'); 

        $this->load->library('Pdf');
        $pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetTitle('Titulo Generación de Etiqueta');
        $pdf->SetSubject('Subtitulo');
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
 
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
 

        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
 
        $pdf->setFontSubsetting(true);

        $pdf->SetFont('Times', '', 8,'','true');

 
        $pdf->setTextShadow(array('enabled' => true, 'depth_w' => 0.2, 'depth_h' => 0.2, 'color' => array(196, 196, 196), 'opacity' => 1, 'blend_mode' => 'Normal'));
 
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        $pdf->SetMargins(10, 10, 10,true);
        
        $pdf->SetAutoPageBreak(true, 10);

        $pdf->AddPage('P', array( 215.9,  279.4)); //en mm 21.59cm por 27.94cm


        $data['movimientos'] = $this->model_conteo_fisico->reporte_historico_conteo($data);

        $html = $this->load->view('pdfs/conteos/resumen_conteos', $data, true);

              


        $pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
 
        $nombre_archivo = utf8_decode("Conteos_".$data['id_almacen'].".pdf");
        $pdf->Output($nombre_archivo, 'I');



    }

/*

   //public function generar_historico_inventarios($id_almacen){
    public function generar_historico_inventarios(){
        $data=$_POST;

        //$data['id_almacen']       = base64_decode($id_almacen);
        /////////////

        set_time_limit(0); 
        ignore_user_abort(1);
        ini_set('memory_limit','512M'); 

        $this->load->library('Pdf');
        $pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetTitle('Titulo Generación de Etiqueta');
        $pdf->SetSubject('Subtitulo');
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
 
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
 

        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
 
        $pdf->setFontSubsetting(true);

        $pdf->SetFont('Times', '', 8,'','true');

 
        $pdf->setTextShadow(array('enabled' => true, 'depth_w' => 0.2, 'depth_h' => 0.2, 'color' => array(196, 196, 196), 'opacity' => 1, 'blend_mode' => 'Normal'));
 
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        $pdf->SetMargins(10, 10, 10,true);
        
        $pdf->SetAutoPageBreak(true, 10);

        $pdf->AddPage('P', array( 215.9,  279.4)); //en mm 21.59cm por 27.94cm



        $data['movimientos'] = $this->model_conteo_fisico->reporte_historico_conteo($data);

        

        //$data['etiq_mov'] ="Salida";  

       
        $html = $this->load->view('pdfs/conteos/resumen_conteos', $data, true);
        

        // Imprimimos el texto con writeHTMLCell()
        $pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
         
        // ---------------------------------------------------------
        // Cerrar el documento PDF y preparamos la salida
        // Este método tiene varias opciones, consulte la documentación para más información.
        $nombre_archivo = utf8_decode("Conteos_".$data['id_almacen'].".pdf");
        $pdf->Output($nombre_archivo, 'I');

  }

  */


  public function procesando_conteo_historico(){
      $data=$_POST;
      $this->session->set_userdata('id_almacen_ajuste', $data['id_almacen']);
      $busqueda  = $this->model_conteo_fisico->buscador_historial_conteo($data);
      echo $busqueda;
  } 


public function historico_conteo1($mov,$id_almacen){
  $data["movimiento"] = base64_decode($mov);       
  $data["id_almacen"] = base64_decode($id_almacen);     
  $data['modulo'] = 2;
  $data['titulo'] = "";
  self::historial_conteos($data);
}
public function historico_conteo2($mov,$id_almacen){
  $data["movimiento"] = base64_decode($mov);       
  $data["id_almacen"] = base64_decode($id_almacen);       
  $data['modulo'] = 3;
  $data['titulo'] = "";
  self::historial_conteos($data);
}
public function historico_conteo3($mov,$id_almacen){
  $data["movimiento"] = base64_decode($mov);       
  $data["id_almacen"] = base64_decode($id_almacen);       
  $data['modulo'] = 4;
  $data['titulo'] = "";
  self::historial_conteos($data);
}


public function historial_conteos($data){

      if ( $this->session->userdata('session') !== TRUE ) {
          redirect('');
        } else {

          $id_perfil = $this->session->userdata('id_perfil');

          $coleccion_id_operaciones= json_decode($this->session->userdata('coleccion_id_operaciones')); 
          if ( (count($coleccion_id_operaciones)==0) || (!($coleccion_id_operaciones)) ) {
                $coleccion_id_operaciones = array();
           }   

           $data['vista']  = "tabla_conteos"; 
                          
          switch ($id_perfil) {    
            case 1:
                    $this->load->view( 'conteo_fisico/historico/conteo_historico', $data );
            break;
            case 2:
            case 3:
            case 4:
                  if  (in_array(50, $coleccion_id_operaciones))  {                 
                        $this->load->view( 'conteo_fisico/historico/conteo_historico', $data );
                 }   
              break;


            default:  
              redirect('');
              break;
          }
         
       }      
  }


  function historico_conteo() { 
    if($this->session->userdata('session') === TRUE ){
              $id_perfil = $this->session->userdata('id_perfil');
              $coleccion_id_operaciones= json_decode($this->session->userdata('coleccion_id_operaciones')); 
              if ( (count($coleccion_id_operaciones)==0) || (!($coleccion_id_operaciones)) ) {
                    $coleccion_id_operaciones = array();
               }                 

                   $data['modulo'] = 1;
                   $data['vista']  = "tabla_historico_conteo";
               $data['id_almacen'] = $this->session->userdata('id_almacen_ajuste');   
                $data['almacenes'] = $this->modelo->listado_almacenes();  
                $data['proveedor'] = "";
               $data['id_factura'] = 1;
               $data['productos']  = $this->catalogo->listado_productos_existente($data);

               $data['facturas']   = $this->catalogo->listado_tipos_facturas(-1,-1,'1');  

              switch ($id_perfil) {    
                case 1: 
                   $this->load->view('conteo_fisico/historico/historico_conteo',$data );
                  break;    
                case 2:
                case 3:
                case 4:
                      if  (in_array(50, $coleccion_id_operaciones))  {                 
                               $this->load->view('conteo_fisico/historico/historico_conteo',$data );
                     }   
                  break;

                default:  
                  redirect('');
                  break;
              }
        }
        else{ 
          redirect('');
        } 
  }


 public function procesando_historico_conteo(){
     
      $data=$_POST;
      
      $this->session->set_userdata('id_almacen_ajuste', $data['id_almacen']);
      $busqueda  = $this->model_conteo_fisico->buscador_historico_conteo($data);
      
      //print_r($busqueda);
      echo $busqueda;

  }

  /////////////////////////////////////////////////////////////////////////////////////////////////////

function cargar_dependencia_existente(){
    
    $data["id_almacen"] = $this->session->userdata('id_almacen_ajuste');

    $data['campo']        = $this->input->post('campo');

    $data['val_prod']        = $this->input->post('val_prod');
    $data['val_color']        = $this->input->post('val_color');
    $data['val_comp']        = $this->input->post('val_comp');
    $data['val_calida']        = $this->input->post('val_calida');

    $data['id_factura']        = $this->input->post('id_factura');
      

    $data['dependencia']        = $this->input->post('dependencia');
    $data['proveedor']        = $this->input->post('proveedor');
    //print_r($data);
    //die;

    switch ($data['dependencia']) {
        case "producto_existente": //nunca será una dependencia
            $elementos   = $this->catalogo->listado_productos_existente($data);  
            break;
        case "color_existente":
            $elementos  = $this->catalogo->lista_colores_existente($data);            
            break;
        case "composicion_existente":
            $elementos  = $this->catalogo->lista_composiciones_existente($data);
            break;
        case "calidad_existente":
            $elementos  = $this->catalogo->lista_calidad_existente($data);
            break;

        default:
    }



      $variables = array();
    if ($elementos != false)  {     
         foreach( (json_decode(json_encode($elementos))) as $clave =>$valor ) {
            if ($data['dependencia']=="color_existente"){
              array_push($variables,array('nombre' => $valor->nombre, 'identificador' => $valor->id, 'hexadecimal_color' => $valor->hexadecimal_color)); 
            } else {
              array_push($variables,array('nombre' => $valor->nombre, 'identificador' => $valor->id, 'hexadecimal_color' => "FFFFFF"));  
            }
       }
    }  

     echo json_encode($variables);
  }



function conteos_opciones() { 
    $this->load->view('conteo_fisico/conteo_opciones' );
}


  function informe_pendiente() { 
    if($this->session->userdata('session') === TRUE ){
          $id_perfil = $this->session->userdata('id_perfil');
          $coleccion_id_operaciones= json_decode($this->session->userdata('coleccion_id_operaciones')); 
          if ( (count($coleccion_id_operaciones)==0) || (!($coleccion_id_operaciones)) ) {
                $coleccion_id_operaciones = array();
           }   

                   $data['dato']['modulo'] = 1;
                   $data['dato']['vista']  = "tabla_informe_pendiente";
                   $data['id_almacen']=$this->session->userdata('id_almacen_ajuste');   //bodega1

                    $data['almacenes']   = $this->modelo->listado_almacenes();  
                    $data['id_factura']        = 1; //levante id_factura por defecto como tipo "factura"
                    $data['proveedor']        = "";
                    $data['productos']   = $this->catalogo->listado_productos_existente($data);  
                    
                    $data['dato']['filtro']   = $this->model_conteo_fisico->obtener_filtro($data);

                     $data['facturas']   = $this->catalogo->listado_tipos_facturas(-1,-1,'1');

              switch ($id_perfil) {    
                case 1: 
                    $this->load->view('conteo_fisico/informe_pendiente',$data );
                  break;    
                case 2:
                case 3:
                case 4:
                      if  (in_array(50, $coleccion_id_operaciones))  {                 
                            $this->load->view('conteo_fisico/informe_pendiente',$data );
                     }   
                  break;

                default:  
                  redirect('');
                  break;
              }
        }
        else{ 
          redirect('');
        } 
  }

  public function almacen_ajuste_conteo(){

      $data['id_almacen']        = $this->input->post('id_almacen');
      $this->session->set_userdata('id_almacen_ajuste', $data['id_almacen']);
      echo true; // json_encode(value)

  }
    

  public function procesando_informe_pendiente(){
      $data=$_POST;
      $this->session->set_userdata('id_almacen_ajuste', $data['id_almacen']);
      $data["id_almacen"] = $this->session->userdata('id_almacen_ajuste');
      $status_almacen  = $this->modelo->status_almacen($data);       

       $dato['entradas']  = $this->model_conteo_fisico->entradas($data); 
      $dato['pedidos']  = $this->model_conteo_fisico->pedidos($data); 
      $dato['devoluciones']  = $this->model_conteo_fisico->devoluciones($data); 
      $dato['traspasos']  = $this->model_conteo_fisico->traspasos($data); 

        foreach ($dato as $clave => $valor) {
          $nombre="";
          if ($valor){
            foreach ($valor as $valor2) {
              $nombre.=$valor2->nombre.'<br>';
                
            }  
          }
          $array[] = $nombre;
          
        }
    
        if  (!( (empty($array[0])) && (empty($array[1])) && (empty($array[2])) && (empty($array[3])) )) {
            echo json_encode ( array(
              "draw"            => intval( $data['draw'] ),
              "recordsTotal"    => 1, //intval( self::total_detalle_colores($where_total) ),  //10
              "recordsFiltered" => 1, //$registros_filtrados, 
              "data"            =>  array($array),
              "status_almacen"   => $status_almacen->activo,
                  "generales"            =>  array(
                                                      "modulo_activo"=>intval( $this->model_conteo_fisico->num_conteo($data)+2 )
                                                    ),                
            ));      

        } else {
           $output = array(
                      "draw"              =>  intval( $data['draw'] ),
                      "recordsTotal"      => 0, 
                      "recordsFiltered"   => 0,
                      "aaData"            => array(),
                      "status_almacen"    => $status_almacen->activo,
                  "generales"            =>  array(
                                                      "modulo_activo"=>intval( $this->model_conteo_fisico->num_conteo($data)+2 )
                                                    ),                        
                     
                  );
                  $array[]="";
                  echo json_encode($output);
        }





  }

public function procesar_conteo($id_almacen,$id_descripcion,$id_color,$id_composicion,$id_calidad,$cantidad,$id_factura,$proveedor){

      if ( $this->session->userdata('session') !== TRUE ) {
          redirect('');
        } else {

          $id_perfil = $this->session->userdata('id_perfil');

          $coleccion_id_operaciones= json_decode($this->session->userdata('coleccion_id_operaciones')); 
          if ( (count($coleccion_id_operaciones)==0) || (!($coleccion_id_operaciones)) ) {
                $coleccion_id_operaciones = array();
           }   
              
               $data["id_almacen"] = base64_decode($id_almacen);       
           $data["id_descripcion"] = base64_decode($id_descripcion);       
                 $data["id_color"] = base64_decode($id_color);       
           $data["id_composicion"] = base64_decode($id_composicion);       
               $data["id_calidad"] = base64_decode($id_calidad);  
               $data["cantidad"] = base64_decode($cantidad);  
               
               $data["id_factura"] = base64_decode($id_factura);  
                $data["proveedor"] = base64_decode($proveedor);  

          switch ($id_perfil) {    
            case 1:
                        $this->load->view( 'conteo_fisico/conteo_modal', $data );
            break;
                case 2:
                case 3:
                case 4:
                      if  (in_array(50, $coleccion_id_operaciones))  {                 
                        $this->load->view( 'conteo_fisico/conteo_modal', $data );
                     }   
                  break;            

            default:  
              redirect('');
              break;
          }

          
       }      
  }



  public function conteos($data){

      if ( $this->session->userdata('session') !== TRUE ) {
          redirect('');
        } else {

          $id_perfil = $this->session->userdata('id_perfil');

          $coleccion_id_operaciones= json_decode($this->session->userdata('coleccion_id_operaciones')); 
          if ( (count($coleccion_id_operaciones)==0) || (!($coleccion_id_operaciones)) ) {
                $coleccion_id_operaciones = array();
           }   

            $data['facturas']   = $this->catalogo->listado_tipos_facturas(-1,-1,'1');
            $data['almacenes']   = $this->modelo->listado_almacenes();  
            $data['productos'] = $this->catalogo->listado_productos_unico(); 
            $data['dato']['vista']  = "tabla_conteos"; 

            
            $data['dato']['filtro']   = $this->model_conteo_fisico->obtener_filtro($data);
          switch ($id_perfil) {    
            case 1:
                    $this->load->view( 'conteo_fisico/conteos', $data );
            break;
                case 2:
                case 3:
                case 4:
                      if  (in_array(50, $coleccion_id_operaciones))  {                 
                        $this->load->view( 'conteo_fisico/conteos', $data );
                     }   
                  break;            



            default:  
              redirect('');
              break;
          }
         
       }      
  }

public function conteo1(){
  $data['dato']['modulo'] = 2;
  $data['titulo'] = "";
  self::conteos($data);
}
public function conteo2(){
  $data['dato']['modulo'] = 3;
  $data['titulo'] = "";
  self::conteos($data);
}
public function conteo3(){
  $data['dato']['modulo'] = 4;
  $data['titulo'] = "";
  self::conteos($data);
}


  public function confirmar_proceso_conteo() {
           $data["id_almacen"]= $this->input->post("id_almacen");
       $data["id_descripcion"]= $this->input->post("id_descripcion");       
            
             $data["id_color"]= $this->input->post("id_color");
       $data["id_composicion"]= $this->input->post("id_composicion");      
           $data["id_calidad"]= $this->input->post("id_calidad");  

           $data["id_factura"]= $this->input->post("id_factura");  
           $data["proveedor"]= $this->input->post("proveedor");  

            $dato["id"]= $this->input->post("id_color");
            $data["color"] = $this->catalogo->coger_color($dato)->color;
           $dato["id"]= $this->input->post("id_composicion");      
           $data["composicion"] = $this->catalogo->coger_composicion($dato)->composicion;
           
           $dato["id"]= $this->input->post("id_calidad");  
           $data["calidad"] = $this->catalogo->coger_calidad($dato)->calidad;

            //cancelando las operaciones que estan en procesos
  
            $this->model_conteo_fisico->eliminar_prod_temporal($data); 
            $this->model_conteo_fisico->cancelar_pedido_detalle($data); 
            $this->model_conteo_fisico->quitar_producto_devolucion($data); 
            $this->model_conteo_fisico->quitar_productos_traspasado($data); 

            $this->model_conteo_fisico->creando_conteo($data) ; 

            
            redirect('/informe_pendiente');

  }      








public function procesando_conteos(){
      $data=$_POST;
      $this->session->set_userdata('id_almacen_ajuste', $data['id_almacen']);
      $busqueda  = $this->model_conteo_fisico->buscador_costos($data);
      echo $busqueda;
  } 









public function procesar_por_conteo(){


       
      
       if($this->session->userdata('session') === TRUE ){
            $id_perfil=$this->session->userdata('id_perfil');

            $coleccion_id_operaciones= json_decode($this->session->userdata('coleccion_id_operaciones')); 
            if ( (count($coleccion_id_operaciones)==0) || (!($coleccion_id_operaciones)) ) {
                  $coleccion_id_operaciones = array();
             }  

            $errores='';


                //para big data ("datos grandes"). Para leer arreglo
                       $rawdata = file_get_contents('php://input');
                       $urldecoded = urldecode($rawdata);
                       $pairs = explode("&", $urldecoded);
                        $todo = array();
                        $vars = array();
                        foreach ($pairs as $pair) {
                            $nv = explode("=", $pair);
                            $name = urldecode($nv[0]);
                            $value = urldecode($nv[1]);
                            $pos = strpos($name, 'arreglo_cantidad');
                                if ($pos !== false) {
                                  parse_str($name.'='.$value, $output);
                                  $todo[] = $output;
                                }
                            }

                  
                        foreach ($todo as $key => $value) {
                          foreach ($value['arreglo_cantidad'] as $key1 => $value1) {
                            foreach ($value1 as $key2 => $value2) {
                              $vars[$key1][$key2] = $value2;
                            }  
                           } 
                        }



              $data['cantidad'] = $vars;
                // json_decode(json_encode( $this->input->post('arreglo_cantidad') ),true  );
      
            $data['id_almacen'] =   $this->input->post('id_almacen');
                $data['modulo'] =   $this->input->post('modulo');
            
            $dato['cantidades'] = $this->model_conteo_fisico->actualizar_cantidad($data);  
            $dato['exito'] = true;
            echo json_encode($dato);
        
    } else { //fin de session
      redirect('');
    }   
    
  }



public function procesar_contando($id_almacen,$modulo){

      if ( $this->session->userdata('session') !== TRUE ) {
          redirect('');
        } else {

          $id_perfil = $this->session->userdata('id_perfil');

          $coleccion_id_operaciones= json_decode($this->session->userdata('coleccion_id_operaciones')); 
          if ( (count($coleccion_id_operaciones)==0) || (!($coleccion_id_operaciones)) ) {
                $coleccion_id_operaciones = array();
           }   
                
               $data["id_almacen"] = base64_decode($id_almacen);       
               $data["modulo"] = base64_decode($modulo);       
               

          switch ($id_perfil) {    
            case 1:
                    $this->load->view( 'conteo_fisico/contando_modal', $data );
            break;
                case 2:
                case 3:
                case 4:
                      if  (in_array(50, $coleccion_id_operaciones))  {                 
                        $this->load->view( 'conteo_fisico/contando_modal', $data );
                     }   
                  break;                        
            default:  
              redirect('');
              break;
          }

          
       }      
  }




  public function confirmar_procesar_contando() {
           $data["id_almacen"]= $this->input->post("id_almacen");
           $data["modulo"]= $this->input->post("modulo");
           $this->model_conteo_fisico->actualizar_conteos($data);  
           redirect('/conteo'.(((int)$data["modulo"])-1) );

  }      


public function ajustes($data){

      if ( $this->session->userdata('session') !== TRUE ) {
          redirect('');
        } else {

          $id_perfil = $this->session->userdata('id_perfil');

          $coleccion_id_operaciones= json_decode($this->session->userdata('coleccion_id_operaciones')); 
          if ( (count($coleccion_id_operaciones)==0) || (!($coleccion_id_operaciones)) ) {
                $coleccion_id_operaciones = array();
           }   

            $data['facturas']   = $this->catalogo->listado_tipos_facturas(-1,-1,'1');
            $data['almacenes']   = $this->modelo->listado_almacenes();  
            $data['productos'] = $this->catalogo->listado_productos_unico(); 

           $data['dato']['vista']  = "tabla_ajustes"; 
           $data['dato']['filtro']   = $this->model_conteo_fisico->obtener_filtro($data);
          switch ($id_perfil) {    
            case 1:
                    $this->load->view( 'conteo_fisico/ajustes', $data );
            break;
                case 2:
                case 3:
                case 4:
                      if  (in_array(50, $coleccion_id_operaciones))  {                 
                        $this->load->view( 'conteo_fisico/ajustes', $data );
                     }   
                  break;                                    

            default:  
              redirect('');
              break;
          }
         
       }      
  }

public function faltante(){
  $data['dato']['modulo'] = 5;
  $data['titulo'] = "";
  self::ajustes($data);
}  

public function sobrante(){
  $data['dato']['modulo'] = 6;
  $data['titulo'] = "";
  self::ajustes($data);
}  

public function procesando_ajustes(){
      $data=$_POST;
      $this->session->set_userdata('id_almacen_ajuste', $data['id_almacen']);
      $busqueda  = $this->model_conteo_fisico->buscador_ajustes($data);
      echo $busqueda;
} 


public function entrada_sobrante($modulo,$retorno){


     if($this->session->userdata('session') === TRUE ){
          $id_perfil=$this->session->userdata('id_perfil');
          $id_cliente_asociado = $this->session->userdata('id_cliente_asociado');

          $coleccion_id_operaciones= json_decode($this->session->userdata('coleccion_id_operaciones')); 
          if ( (count($coleccion_id_operaciones)==0) || (!($coleccion_id_operaciones)) ) {
                $coleccion_id_operaciones = array();
           }   


            $data['id_empresa'] =  $this->session->userdata('id_cliente_asociado');
            $data['id_almacen']=$this->session->userdata('id_almacen_ajuste');   //bodega1

            $data['id_factura']= $this->model_conteo_fisico->valor_tipo_factura($data); 
            $data['id_tipo_pago']=2; //contado
            $data['movimiento'] = $this->model_conteo_fisico->consecutivo_operacion_ajuste_positivo(1,$data['id_factura']); //cambio
            $data['productos'] = $this->model_conteo_fisico->anadir_producto_temporal($data);

           
           $data['modulo']       = base64_decode($modulo);    
           $data['retorno']       = base64_decode($retorno);    
           
           
           

           $data['productos'] = $this->catalogo->listado_productos_unico();
           $data['colores'] = $this->catalogo->listado_colores_unico();
           $data['almacenes']   = $this->modelo->listado_almacenes();
           $data['facturas']   = $this->catalogo->listado_tipos_facturas(-1,-1,'1');
           $data['pedidos']   = $this->catalogo->listado_tipos_pedidos(-1,-1,'1');


          // $data['consecutivo']  = $this->catalogo->listado_consecutivo(2);
           
           $dato['id'] = 3;
           $data['cargador']   =  $this->catalogo->coger_cargador($dato)->nombre; 
           
           $dato['id'] = $id_cliente_asociado;
           $data['nombre']   =  $this->catalogo->tomar_proveedor($dato)->nombre; 
           $dato['id'] = 7;
           $data['configuracion'] = $this->catalogo->coger_configuracion($dato); 

         



            $data['medidas']  = $this->catalogo->listado_medidas();
            $data['estatuss']  = $this->catalogo->listado_estatus(-1,-1,'1');
            $data['lotes']  = $this->catalogo->listado_lotes(-1,-1,'1');
            $data['consecutivo']  = $this->catalogo->listado_consecutivo(1);

            $data['movimientos']  = $this->model_entrada->listado_movimientos_temporal();
            $data['val_proveedor']  = $this->model_entrada->valores_movimientos_temporal();
            $data['productos']   = $this->catalogo->listado_productos_unico_activo();

              $data['facturas']   = $this->catalogo->listado_tipos_facturas(-1,-1,'1');
              $data['pagos']   = $this->catalogo->listado_tipos_pagos();

            






              $data['dato']['filtro']   = $this->model_conteo_fisico->obtener_filtro($data);
               
            switch ($id_perfil) {    
              case 1:          
                          $this->load->view( 'conteo_fisico/entrada_sobrante',$data );
                break;
                case 2:
                case 3:
                case 4:
                      if  (in_array(50, $coleccion_id_operaciones))  {                 
                        $this->load->view( 'conteo_fisico/entrada_sobrante',$data );
                     }   
                  break;                                                    
              default:  
                redirect('');
                break;
            }
        
        }
        else{ 
          redirect('');
        }     
    
    
  }  



public function validar_proceso_sobrante(){ 

    if($this->session->userdata('session') === TRUE ){
          $id_perfil=$this->session->userdata('id_perfil');
          

          $data['dev'] = 0; 

          $coleccion_id_operaciones= json_decode($this->session->userdata('coleccion_id_operaciones')); 
          if ( (count($coleccion_id_operaciones)==0) || (!($coleccion_id_operaciones)) ) {
                $coleccion_id_operaciones = array();
           }  


            $data['id_almacen']=$this->session->userdata('id_almacen_ajuste');   //bodega1

            $data['id_factura']= $this->model_conteo_fisico->valor_tipo_factura($data);             

            $data['cantidad_um'] =  json_decode(json_encode( $this->input->post('arreglo_cantidad_um') ),true  );
            $data['ancho'] =  json_decode(json_encode( $this->input->post('arreglo_ancho') ),true  );
            $data['precio'] =  json_decode(json_encode( $this->input->post('arreglo_precio') ),true  );
            $data['pesos'] =  json_decode(json_encode( $this->input->post('arreglo_peso') ),true  );

            $this->model_conteo_fisico->actualizar_peso_real($data);

            //verificar si hay pesos reales en cero 
            $existe = $this->model_conteo_fisico->existencia_temporales_peso_real($data);
            if  (!($existe)) {
              $errores= "Existen productos sin especificar Peso real, cantidad, ancho o precio.";
            } 

            if (($existe)) {

              
                //copiar a tabla "registros" e "historico_registros_entradas"
                $data['id_operacion'] =1;
                $data['num_mov'] = $this->model_conteo_fisico->procesando_operacion($data);
                

               
                $this->load->library('ciqrcode');
                //hacemos configuraciones

            $data['movimientos']  = $this->model_conteo_fisico->listado_movimientos_registros($data);
            
                
                foreach ($data['movimientos'] as $key => $value) {
                  
                  $params['data'] = $value->codigo;
                  $params['level'] = 'H';
                  $params['size'] = 30;
                  $params['savename'] = FCPATH.'qr_code/'.$value->codigo.'.png';
                  $this->ciqrcode->generate($params);    
                
                }

              $data['exito']  = true;
              echo json_encode($data);
          

          } else { 

              $data['exito']  = false;
              $data['error'] = '<span class="error">'. $errores.'</span>';
              echo json_encode($data);

                  
          }  

            
    }
        else{ 
          redirect('');
    }  
  }


  //Esta es la Regilla de los productos
  public function procesando_temporales_sobrante(){
    $data=$_POST;
    $busqueda = $this->model_conteo_fisico->buscador_productos_temporales($data);
    echo $busqueda;
  } 

  public function procesando_servidor_ajustes(){
    $data=$_POST;
    $data['id_cliente']=0;
        if ($this->input->post('id_cliente')) {

             $data['descripcion'] = $this->input->post('id_cliente');
             $data['idproveedor'] = "2";

          $data['id_cliente'] =  $this->catalogo->checar_existente_proveedor($data);

       //$data['id_cliente'] =  $this->catalogo->check_existente_proveedor_entrada($this->input->post('id_cliente'));
    }  

    if (!($data['id_cliente'])) {
      $data['id_cliente'] =0;
    }
    //$data['id_empresa']=46;
    $data['id_almacen']   =  $this->session->userdata('id_almacen_ajuste');  
    $data['id_empresa']= $this->model_conteo_fisico->valor_id_empresa($data); 
    $busqueda = $this->model_conteo_fisico->buscador_entrada($data);
    echo $busqueda;
  }

/*
 if  ($data['proveedor']!=' '){
            $provee= 'AND ( prov.nombre LIKE  "%'.$data['proveedor'].'%" )'   ; 
         } else {
            $provee= '';
         }
         
        
          $activo  = ' and ( p.activo =  0 ) '; 
          $where = '( 
                        (m.id_almacen =  '.$id_almacen.' ) AND ( m.id_factura = '.$data['id_factura'].' ) '.$activo.$provee.'
                     ) ' ; 



*/


//////////////////////////////////////////////////////////////////////////////////  
///////////////////////////////faltante///////////////////////////////////////////////////  
//////////////////////////////////////////////////////////////////////////////////    


  public function salida_faltante($modulo,$retorno){

     if($this->session->userdata('session') === TRUE ){
          $id_perfil=$this->session->userdata('id_perfil');
          $id_cliente_asociado = $this->session->userdata('id_cliente_asociado');

          $coleccion_id_operaciones= json_decode($this->session->userdata('coleccion_id_operaciones')); 
          if ( (count($coleccion_id_operaciones)==0) || (!($coleccion_id_operaciones)) ) {
                $coleccion_id_operaciones = array();
           }   
           
           $data['modulo']       = base64_decode($modulo);    
           $data['retorno']       = base64_decode($retorno);    
           $data['id_almacen']   =  $this->session->userdata('id_almacen_ajuste');  

            $data['id_factura']= $this->model_conteo_fisico->valor_tipo_factura($data);            
           

           $data['productos'] = $this->catalogo->listado_productos_unico();
           $data['colores'] = $this->catalogo->listado_colores_unico();
           $data['almacenes']   = $this->modelo->listado_almacenes();
           $data['facturas']   = $this->catalogo->listado_tipos_facturas(-1,-1,'1');
           $data['pedidos']   = $this->catalogo->listado_tipos_pedidos(-1,-1,'1');


           $data['consecutivo']  = $this->catalogo->listado_consecutivo(2);
           
           $dato['id'] = 3;
           $data['cargador']   =  $this->catalogo->coger_cargador($dato)->nombre; 
           
           $dato['id'] = $id_cliente_asociado;
           $data['nombre']   =  $this->catalogo->tomar_proveedor($dato)->nombre; 
           $dato['id'] = 10;
           $data['configuracion'] = $this->catalogo->coger_configuracion($dato); 

            $data['dato']['filtro']   = $this->model_conteo_fisico->obtener_filtro($data);

             //$data['id_factura']= $this->model_conteo_fisico->valor_tipo_factura($data); 
               
            switch ($id_perfil) {    
              case 1:          
                          $this->load->view( 'conteo_fisico/salida_faltante',$data );
                break;
                case 2:
                case 3:
                case 4:
                      if  (in_array(50, $coleccion_id_operaciones))  {                 
                           $this->load->view( 'conteo_fisico/salida_faltante',$data );
                     }   
                  break;                                                                    
              default:  
                redirect('');
                break;
            }
        
        }
        else{ 
          redirect('');
        }     
  }


  function agregar_salida_faltante(){

      if ($this->session->userdata('session') !== TRUE) {
        redirect('');
      } else {



       if ($this->input->post('id_cliente')) {

             $data['descripcion'] = $this->input->post('id_cliente');
             $data['idproveedor'] = "2";

          $data['id_cliente'] =  $this->catalogo->checar_existente_proveedor($data);


            //$data['id_cliente'] =  $this->catalogo->check_existente_proveedor_entrada($this->input->post('id_cliente'));
            if (!($data['id_cliente'])){
              print "El cliente no existe";
            }
        } else {
          $data['id_cliente']=null;
          print "Campo <b>cliente</b> obligatorio. ";
        }
            

       if ($this->input->post('id_cargador')) {
            $data['id_cargador'] =  $this->catalogo->check_existente_cargador_entrada($this->input->post('id_cargador'));
            if (!($data['id_cargador'])){
              print "El cargador no existe";
            }
       } else {
          $data['id_cargador']=null;
          print "Campo <b>cargador</b> obligatorio. ";

        }


            

      if ( ($data['id_cliente']) and ($data['id_cargador']) ) {

            $data['id'] = $this->input->post('identificador');
            $data['id_movimiento'] = $this->input->post('movimiento');
            $data['id_almacen'] = $this->input->post('id_almacen');
            $data['id_tipo_factura'] = $this->input->post('id_tipo_factura');
            $data['id_tipo_pedido'] = $this->input->post('id_tipo_pedido');
          ///////////            
            $existe=$this->model_conteo_fisico->checar_prod_salida($data);

            if ($existe==false) {
              $this->model_conteo_fisico->enviar_prod_salida($data);  
            } 
            
                   //die;
            $actualizar = $this->model_conteo_fisico->quitar_prod_entrada($data );

            $actualizar = true;
            if ( $actualizar !== FALSE ){
              echo TRUE;
            } else {
              echo '<span class="error">No se ha podido añadir el producto</span>';
            }
  
      } else {      
        
             echo validation_errors('<span class="error">','</span>');

          }     

    } 
   }

  public function procesando_salida_ajuste(){
    $data=$_POST;
    $busqueda = $this->model_conteo_fisico->buscador_salida($data);
    echo $busqueda;
  }


  function quitar_salida_ajuste(){

      if ($this->session->userdata('session') !== TRUE) {
        redirect('');
      } else {
      
      $data['id'] = $this->input->post('identificador');
      $data['id_tipo_factura'] = $this->input->post('id_tipo_factura');
      $data['id_tipo_pedido'] = $this->input->post('id_tipo_pedido');
      

        $this->model_conteo_fisico->enviar_prod_entrada( $data );
            
      $actualizar = $this->model_conteo_fisico->quitar_prod_salidas($data );
      $dato['total'] = $this->model_conteo_fisico->total_registros_salida();
    
            

    
      if ( $actualizar !== FALSE ){
        $dato['exito']  = true;
        echo json_encode($dato);
        
      } else {
        $dato['exito']  = false;
        $dato['error'] = '<span class="error">No se ha podido actualizar el producto</span>';
        echo json_encode($dato);
      }
    } 
   }

public function procesando_salida_ajuste_definitivo(){

      if ( $this->session->userdata('session') !== TRUE ) {
          redirect('');
        } else {
          $id_perfil=$this->session->userdata('id_perfil');

          $coleccion_id_operaciones= json_decode($this->session->userdata('coleccion_id_operaciones')); 
          if ( (count($coleccion_id_operaciones)==0) || (!($coleccion_id_operaciones)) ) {
                $coleccion_id_operaciones = array();
           }   

           $data['id_tipo_pedido'] = $this->input->post('id_tipo_pedido');
           $data['id_tipo_factura'] = $this->input->post('id_tipo_factura'); 
           $data['id_almacen']     = $this->input->post('id_almacen');
           //$data['movimiento'] = $this->model_conteo_fisico->consecutivo_operacion_ajuste_positivo(1,$data['id_factura']); //cambio
           $dato['encabezado']     = $this->model_conteo_fisico->procesando_operacion_salida($data); //871
           $dato['exito']  = true;
           echo json_encode($dato);
       }    

}




   public function generar_conteos($id_almacen,$modulo,$modulo_activo){
  

        $data['id_almacen']       = base64_decode($id_almacen);
        $data['modulo']           = base64_decode($modulo);
        $data['modulo_activo']    = base64_decode($modulo_activo);
        /////////////

        set_time_limit(0); 
        ignore_user_abort(1);
        ini_set('memory_limit','512M'); 

        $this->load->library('Pdf');
        $pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetTitle('Titulo Generación de Etiqueta');
        $pdf->SetSubject('Subtitulo');
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
 
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
 

        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
 
        $pdf->setFontSubsetting(true);

        $pdf->SetFont('Times', '', 8,'','true');

 
        $pdf->setTextShadow(array('enabled' => true, 'depth_w' => 0.2, 'depth_h' => 0.2, 'color' => array(196, 196, 196), 'opacity' => 1, 'blend_mode' => 'Normal'));
 
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        $pdf->SetMargins(10, 10, 10,true);
        
        $pdf->SetAutoPageBreak(true, 10);

        $pdf->AddPage('P', array( 215.9,  279.4)); //en mm 21.59cm por 27.94cm



        $data['movimientos'] = $this->model_conteo_fisico->reporte_conteos($data);

        

        //$data['etiq_mov'] ="Salida";  

       
        $html = $this->load->view('pdfs/conteos/conteos', $data, true);
        

        // Imprimimos el texto con writeHTMLCell()
        $pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
         
        // ---------------------------------------------------------
        // Cerrar el documento PDF y preparamos la salida
        // Este método tiene varias opciones, consulte la documentación para más información.
        $nombre_archivo = utf8_decode("Conteos_".$data['id_almacen'].".pdf");
        $pdf->Output($nombre_archivo, 'I');



    }



//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////Resumen COnteo/////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////                                      


public function resumen_conteo(){

      if ( $this->session->userdata('session') !== TRUE ) {
          redirect('');
        } else {

          $id_perfil = $this->session->userdata('id_perfil');

          $coleccion_id_operaciones= json_decode($this->session->userdata('coleccion_id_operaciones')); 
          if ( (count($coleccion_id_operaciones)==0) || (!($coleccion_id_operaciones)) ) {
                $coleccion_id_operaciones = array();
           }   

            $data['facturas']   = $this->catalogo->listado_tipos_facturas(-1,-1,'1');
            $data['almacenes']   = $this->modelo->listado_almacenes();  
            $data['productos'] = $this->catalogo->listado_productos_unico(); 

            $data['dato']['modulo'] = 7;
            $data['titulo'] = "";

           $data['dato']['vista']  = "resumen_conteo"; 
           $data['dato']['filtro']   = $this->model_conteo_fisico->obtener_filtro($data);

          switch ($id_perfil) {    
            case 1:
                    $this->load->view( 'conteo_fisico/resumen_conteo', $data );
            break;
                case 2:
                case 3:
                case 4:
                      if  (in_array(50, $coleccion_id_operaciones))  {                 
                           $this->load->view( 'conteo_fisico/resumen_conteo', $data );
                     }   
                  break;               

            default:  
              redirect('');
              break;
          }
         
       }      
  }

  public function procesando_resumen_conteo(){
      $data=$_POST;
      
      $this->session->set_userdata('id_almacen_ajuste', $data['id_almacen']);

      $busqueda  = $this->model_conteo_fisico->buscador_resumen_conteo($data);
      
      //print_r($busqueda);
      echo $busqueda;
} 


//UPDATE  `inven_conteo_almacen` SET num_conteo =0, conteo3 =0

  public function resumiendo_conteo(){
      
        $data["id_almacen"] = $this->session->userdata('id_almacen_ajuste');
        $this->model_conteo_fisico->archivando_conteo($data);
        redirect('/informe_pendiente');

      
} 

/////////////////validaciones/////////////////////////////////////////  

  public function valid_selector($str)
  {
    
     $regex = "/^([-0])*$/ix";
    if ( preg_match( $regex, $str ) ){      
      $this->form_validation->set_message( 'valid_selector','<b class="requerido">*</b> El <b>%s</b> tiene que contener valor.' );
      return FALSE;
    } else {
      return TRUE;
    }

  }



  public function valid_cero($str)
  {
    
     $regex = "/^([-0])*$/ix";
    if ( preg_match( $regex, $str ) ){      
      $this->form_validation->set_message( 'valid_cero','<b class="requerido">*</b> El <b>%s</b> no puede ser cero.' );
      return FALSE;
    } else {
      return TRUE;
    }

  }
  


  function nombre_valido( $str ){
     $regex = "/^([A-Za-z ñáéíóúÑÁÉÍÓÚ]{2,60})$/i";
    //if ( ! preg_match( '/^[A-Za-zÁÉÍÓÚáéíóúÑñ \s]/', $str ) ){
    if ( ! preg_match( $regex, $str ) ){      
      $this->form_validation->set_message( 'nombre_valido','<b class="requerido">*</b> La información introducida en <b>%s</b> no es válida.' );
      return FALSE;
    } else {
      return TRUE;
    }
  }

  function valid_phone( $str ){
    if ( $str ) {
      if ( ! preg_match( '/\([0-9]\)| |[0-9]/', $str ) ){
        $this->form_validation->set_message( 'valid_phone', '<b class="requerido">*</b> El <b>%s</b> no tiene un formato válido.' );
        return FALSE;
      } else {
        return TRUE;
      }
    }
  }

  function valid_option( $str ){
    if ($str == 0) {
      $this->form_validation->set_message('valid_option', '<b class="requerido">*</b> Es necesario que selecciones una <b>%s</b>.');
      return FALSE;
    } else {
      return TRUE;
    }
  }

  function valid_date( $str ){

    $arr = explode('-', $str);
    if ( count($arr) == 3 ){
      $d = $arr[0];
      $m = $arr[1];
      $y = $arr[2];
      if ( is_numeric( $m ) && is_numeric( $d ) && is_numeric( $y ) ){
        return checkdate($m, $d, $y);
      } else {
        $this->form_validation->set_message('valid_date', '<b class="requerido">*</b> El campo <b>%s</b> debe tener una fecha válida con el formato DD-MM-YYYY.');
        return FALSE;
      }
    } else {
      $this->form_validation->set_message('valid_date', '<b class="requerido">*</b> El campo <b>%s</b> debe tener una fecha válida con el formato DD-MM-YYYY.');
      return FALSE;
    }
  }

  public function valid_email($str)
  {
    return ( ! preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $str)) ? FALSE : TRUE;
  } 


}

/* End of file nucleo.php */
/* Location: ./app/controllers/nucleo.php */

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Conteo_fisico extends CI_Controller {

  public function __construct(){
    parent::__construct();
    $this->load->model('model_conteo_fisico', 'model_conteo_fisico');
    $this->load->model('catalogo', 'catalogo');  
    $this->load->model('modelo', 'modelo');  
    $this->load->model('model_pedido', 'modelo_pedido');    


    $this->load->library(array('email')); 
    $this->load->library('Jquery_pagination');//-->la estrella del equipo 
  }

  function informe_pendiente() { 
    if($this->session->userdata('session') === TRUE ){
          $id_perfil = $this->session->userdata('id_perfil');
              switch ($id_perfil) {    
                case 1: //conteo

                   $data['dato']['modulo'] = 1;
                   $data['dato']['vista']  = "tabla_informe_pendiente";
                   $data['mod']=1;      
                   $data['dato']['cant'][1]   = 0; //$this->model_pedido_compra->total_modulo($data);
                   $data['mod']=2;      
                   $data['dato']['cant'][2]   = 0; //$this->model_pedido_compra->total_modulo($data);
                   $data['mod']=3;      
                   $data['dato']['cant'][3]   = 0; //$this->model_pedido_compra->total_modulo($data); 
                   $data['mod']=4;      
                   $data['dato']['cant'][4]   = 0; //$this->model_pedido_compra->total_modulo($data);
                   $data['mod']=5;      
                   $data['dato']['cant'][5]   = 0; //$this->model_pedido_compra->total_modulo($data);                  
                   $data['mod']=6;      
                   $data['dato']['cant'][6]   = 0; //$this->model_pedido_compra->total_modulo($data);                  


                    $data['almacenes']   = $this->modelo->coger_catalogo_almacenes(2);  
                    $data['productos'] = $this->catalogo->listado_productos_unico();
                   
                    $this->load->view('conteo_fisico/informe_pendiente',$data );
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


  public function procesando_informe_pendiente(){
      $data=$_POST;
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
            ));      

        } else {
           $output = array(
                      "draw"              =>  intval( $data['draw'] ),
                      "recordsTotal"      => 0, 
                      "recordsFiltered"   => 0,
                      "aaData"            => array(),
                     // "totales"           => 0,
                  );
                  $array[]="";
                  echo json_encode($output);
        }





  }

public function procesar_conteo($id_almacen,$id_descripcion,$id_color,$id_composicion,$id_calidad,$cantidad){

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

          switch ($id_perfil) {    
            case 1:
                    $this->load->view( 'conteo_fisico/conteo_modal', $data );
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

            $data['almacenes']   = $this->modelo->coger_catalogo_almacenes(2);  
            $data['productos'] = $this->catalogo->listado_productos_unico(); 

           $data['dato']['vista']  = "tabla_conteos"; 
           $data['mod']=1;      
           $data['dato']['cant'][1]   = 0; //$this->model_pedido_compra->total_modulo($data);
           $data['mod']=2;      
           $data['dato']['cant'][2]   = 0; //$this->model_pedido_compra->total_modulo($data);
           $data['mod']=3;      
           $data['dato']['cant'][3]   = 0; //$this->model_pedido_compra->total_modulo($data); 
           $data['mod']=4;      
           $data['dato']['cant'][4]   = 0; //$this->model_pedido_compra->total_modulo($data);
           $data['mod']=5;      
           $data['dato']['cant'][5]   = 0; //$this->model_pedido_compra->total_modulo($data);                  
           $data['mod']=6;      
           $data['dato']['cant'][6]   = 0; //$this->model_pedido_compra->total_modulo($data);                  


          switch ($id_perfil) {    
            case 1:
                    $this->load->view( 'conteo_fisico/conteos', $data );
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

            //cancelando las operaciones que estan en procesos
  /*
            $this->model_conteo_fisico->eliminar_prod_temporal($data); 
            $this->model_conteo_fisico->cancelar_pedido_detalle($data); 
            $this->model_conteo_fisico->quitar_producto_devolucion($data); 
            $this->model_conteo_fisico->quitar_productos_traspasado($data); 
*/
            $this->model_conteo_fisico->creando_conteo($data) ; 

            
            redirect('/informe_pendiente');

  }      








public function procesando_conteos(){
      $data=$_POST;
      $busqueda  = $this->model_conteo_fisico->buscador_costos($data);
      echo $busqueda;
  } 












public function procesar_contando($id_almacen){

      if ( $this->session->userdata('session') !== TRUE ) {
          redirect('');
        } else {

          $id_perfil = $this->session->userdata('id_perfil');

          $coleccion_id_operaciones= json_decode($this->session->userdata('coleccion_id_operaciones')); 
          if ( (count($coleccion_id_operaciones)==0) || (!($coleccion_id_operaciones)) ) {
                $coleccion_id_operaciones = array();
           }   
              
               $data["id_almacen"] = base64_decode($id_almacen);       
           
          switch ($id_perfil) {    
            case 1:
                    $this->load->view( 'conteo_fisico/contando_modal', $data );
            break;

            default:  
              redirect('');
              break;
          }

          
       }      
  }




  public function confirmar_procesar_contando() {
           $data["id_almacen"]= $this->input->post("id_almacen");
       $data["id_descripcion"]= $this->input->post("id_descripcion");       
             $data["id_color"]= $this->input->post("id_color");
       $data["id_composicion"]= $this->input->post("id_composicion");      
           $data["id_calidad"]= $this->input->post("id_calidad");  

            //cancelando las operaciones que estan en procesos
  /*
            $this->model_conteo_fisico->eliminar_prod_temporal($data); 
            $this->model_conteo_fisico->cancelar_pedido_detalle($data); 
            $this->model_conteo_fisico->quitar_producto_devolucion($data); 
            $this->model_conteo_fisico->quitar_productos_traspasado($data); 
*/
            $this->model_conteo_fisico->creando_conteo($data) ; 

            
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
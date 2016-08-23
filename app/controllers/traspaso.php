<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Traspaso extends CI_Controller {

  public function __construct(){
    parent::__construct();
    $this->load->model('model_pedido', 'modelo_pedido');
    $this->load->model('catalogo', 'catalogo');  
    $this->load->model('modelo', 'modelo');  
    $this->load->model('model_entradas', 'model_entrada');  
    $this->load->model('model_salida', 'modelo_salida'); 

    $this->load->model('model_traspaso', 'model_traspaso'); 

    $this->load->library(array('email')); 
    $this->load->library('Jquery_pagination');//-->la estrella del equipo 
  }

//////////////////Listado de traspaso///////////////////////////
  public function listado_traspaso(){

      if($this->session->userdata('session') === TRUE ){
          $id_perfil=$this->session->userdata('id_perfil');

          $coleccion_id_operaciones= json_decode($this->session->userdata('coleccion_id_operaciones')); 
          if ( (count($coleccion_id_operaciones)==0) || (!($coleccion_id_operaciones)) ) {
                $coleccion_id_operaciones = array();
           }   
         $data['almacenes']   = $this->modelo->coger_catalogo_almacenes(2);
           
          switch ($id_perfil) {    
            case 1:          
                        $this->load->view( 'traspaso/listado_traspaso',$data );
              break;
            case 2:
            case 3:
                  if  (in_array(4, $coleccion_id_operaciones))  {                 
                            $this->load->view( 'traspaso/listado_traspaso',$data );
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




  public function traspaso_detalle($consecutivo_traspaso){
        if($this->session->userdata('session') === TRUE ){
              
              $id_perfil=$this->session->userdata('id_perfil');

              $coleccion_id_operaciones= json_decode($this->session->userdata('coleccion_id_operaciones')); 
              if ( (count($coleccion_id_operaciones)==0) || (!($coleccion_id_operaciones)) ) {
                    $coleccion_id_operaciones = array();
               }   
               
            /*

                     //no. movimiento $data
                  $data['mov_salida'] = base64_decode($mov_salida);
                  $data['id_apartado'] = base64_decode($id_apartado);
                  $data['id_almacen'] = base64_decode($id_almacen);

                  $data['id']=$data['id_almacen'];
                  if ($data['id']==0){
                    $data['almacen'] = 'Todos'; 
                  } else {
                    $data['almacen'] = $this->catalogo->coger_almacen($data)->almacen;
                  }

            */

            $data['consecutivo_traspaso'] = base64_decode($consecutivo_traspaso);       
           
              switch ($id_perfil) {    
                case 1:          
                           $this->load->view('traspaso/traspaso_detalle',$data);
                  break;
                case 2:
                case 3:
                      if  (in_array(10, $coleccion_id_operaciones))  {            
                          $this->load->view('traspaso/traspaso_detalle',$data);
                      } else {
                        redirect('');
                      }   
                  break;
                default:  
                  redirect('');
                  break;
              } //fin del case
        }
        else{ 
          redirect('');
        }     
  }






  //1ra Regilla PARA "Pedidos de vendedores"
  public function procesando_general_traspaso(){
    $data=$_POST;
    $busqueda = $this->model_traspaso->buscador_general_traspaso($data);
    echo $busqueda;
  } 

  public function procesando_traspaso_historico(){
    $data=$_POST;
    $busqueda = $this->model_traspaso->buscador_traspaso_historico($data);
    echo $busqueda;
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
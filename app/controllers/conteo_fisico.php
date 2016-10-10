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


  public function procesar_conteo($id_almacen,$id_descripcion,$id_color,$id_composicion,$id_calidad){
           $data["id_almacen"]= base64_decode($id_almacen);       
       $data["id_descripcion"]= base64_decode($id_descripcion);       
             $data["id_color"]= base64_decode($id_color);       
       $data["id_composicion"]= base64_decode($id_composicion);       
           $data["id_calidad"]= base64_decode($id_calidad);       


      //$data=$_POST;
  /*
      $dato['entradas']  = $this->model_conteo_fisico->entradas($data); 
      $dato['pedidos']  = $this->model_conteo_fisico->pedidos($data); 
      $dato['devoluciones']  = $this->model_conteo_fisico->devoluciones($data); 
      $dato['traspasos']  = $this->model_conteo_fisico->traspasos($data); 
*/
      /*
      cancelar_pedido_detalle( $data )
      quitar_producto_devolucion( $data )
      quitar_productos_traspasado( $data )

      */


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
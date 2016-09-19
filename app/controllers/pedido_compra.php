<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Pedido_compra extends CI_Controller {

  public function __construct(){
    parent::__construct();
    $this->load->model('model_pedido', 'modelo_pedido');
    $this->load->model('catalogo', 'catalogo');  
    $this->load->model('modelo', 'modelo');  
    $this->load->model('model_entradas', 'model_entrada');  
    $this->load->model('model_salida', 'modelo_salida'); 

    $this->load->model('model_traspaso', 'model_traspaso'); 
    $this->load->model('model_pedido_compra', 'model_pedido_compra'); 
    

    $this->load->model('modelo_borrar_datos', 'modelo_borrar_datos'); 

    $this->load->library(array('email')); 
    $this->load->library('Jquery_pagination');//-->la estrella del equipo 
  }



public function modulo_pedido_compra(){

     if($this->session->userdata('session') === TRUE ){
          $id_perfil=$this->session->userdata('id_perfil');

          $coleccion_id_operaciones= json_decode($this->session->userdata('coleccion_id_operaciones')); 
          if ( (count($coleccion_id_operaciones)==0) || (!($coleccion_id_operaciones)) ) {
                $coleccion_id_operaciones = array();
           }   
           

           //no. movimiento
           $data['consecutivo']  = $this->catalogo->listado_consecutivo(26);
           //valor del cliente, cargador, factura, 
           $data['val_compra']  = $this->model_pedido_compra->valores_movimientos_temporal();
           $data['productos'] = $this->catalogo->listado_productos_unico();
           $data['colores'] = $this->catalogo->listado_colores_unico();
           
           $data['almacenes']   = $this->modelo->coger_catalogo_almacenes(2);


           
           

          switch ($id_perfil) {    
            case 1:          
                        $this->load->view( 'pedido_compra/nuevo_pedido',$data );
              break;
            case 2:
            case 3:
            case 4:
                  if  (in_array(29, $coleccion_id_operaciones))  {                 
                           $this->load->view( 'pedido_compra/nuevo_pedido',$data );
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


  public function procesando_entrada_pedido_compra(){
      $data=$_POST;
      $busqueda = $this->model_pedido_compra->buscador_entrada_compra($data);
      echo $busqueda;

  }


  public function procesando_salida_pedido_compra(){
      $data=$_POST;
      $busqueda = $this->model_pedido_compra->buscador_salida_compra($data);
      echo $busqueda;
      
  }


function cargar_dependencia_compra(){
    
    $data['campo']        = $this->input->post('campo');

    $data['val_prod']        = $this->input->post('val_prod');
    $data['val_color']        = $this->input->post('val_color');
    $data['val_comp']        = $this->input->post('val_comp');
    $data['val_calida']        = $this->input->post('val_calida');

    $data['dependencia']        = $this->input->post('dependencia');

    switch ($data['dependencia']) {
        case "producto_catalogo_compra": //nunca será una dependencia
            $elementos  = $this->catalogo->listado_productos_unico();
            break;
        case "color_catalogo_compra":
            $elementos  = $this->catalogo->lista_colores($data);
            
            break;

        case "composicion_catalogo_compra":
            $elementos  = $this->catalogo->lista_composiciones($data);
            break;
        case "calidad_catalogo_compra":
            $elementos  = $this->catalogo->lista_calidad($data);
            break;

        default:
    }



      $variables = array();
    if ($elementos != false)  {     
         foreach( (json_decode(json_encode($elementos))) as $clave =>$valor ) {
            if ($data['dependencia']=="color_catalogo_compra"){
              array_push($variables,array('nombre' => $valor->nombre, 'identificador' => $valor->id, 'hexadecimal_color' => $valor->hexadecimal_color)); 
            } else {
              array_push($variables,array('nombre' => $valor->nombre, 'identificador' => $valor->id, 'hexadecimal_color' => "FFFFFF"));  
            }
       }
    }  

     echo json_encode($variables);
  }










function agregar_salida_compra(){

      if ($this->session->userdata('session') !== TRUE) {
        redirect('');
      } else {

      $this->form_validation->set_rules( 'factura', 'Factura', 'trim|required|min_length[2]|max_lenght[180]|xss_clean');


      if ( ($this->form_validation->run() === TRUE)  ) {

            $data['id'] = $this->input->post('identificador');
            $data['movimiento'] = $this->input->post('movimiento');
            $data['factura'] = $this->input->post('factura');
            $data['comentario'] = $this->input->post('comentario');
            $data['id_almacen'] = $this->input->post('id_almacen');

            
           $actualizar = $this->model_pedido_compra->enviar_salida_compra($data);  

            //$actualizar = $this->modelo_salida->quitar_prod_entrada($data );

              //$actualizar=TRUE;
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


function quitar_salida_compra(){

      if ($this->session->userdata('session') !== TRUE) {
        redirect('');
      } else {
      
      $data['id'] = $this->input->post('identificador');
      $actualizar =  $this->model_pedido_compra->quitar_salida_compra( $data );
      $dato['val_compra']  = $this->model_pedido_compra->valores_movimientos_temporal();
      
      $actualizar=true;
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

////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////





  public function proc_pedido_compra(){


       if($this->session->userdata('session') === TRUE ){
            $id_perfil=$this->session->userdata('id_perfil');

            $coleccion_id_operaciones= json_decode($this->session->userdata('coleccion_id_operaciones')); 
            if ( (count($coleccion_id_operaciones)==0) || (!($coleccion_id_operaciones)) ) {
                  $coleccion_id_operaciones = array();
             }  

            $existe = $this->modelo_salida->existencia_temporales();

            $errores='';

        

            $this->form_validation->set_rules( 'factura', 'Factura', 'trim|required|min_length[2]|max_lenght[180]|xss_clean');

            
        if ($this->form_validation->run() === TRUE) {           
           if  (!($existe)) {
            $errores= "Debe agregar al menos un producto";
           } else {  //si estan agregados los productos entonces checar si tienen el peso real
              
              //actualizar peso real
              $data['pesos'] =  json_decode(json_encode( $this->input->post('arreglo_pedido_compra') ),true  );
              $this->modelo_salida->actualizar_peso_real($data);

              //verificar si hay pesos reales en cero 
              $existe = $this->modelo_salida->existencia_temporales_peso_real();
              if  (!($existe)) {
                $errores= "Existen productos sin especificar Peso real";
              } 

           }
           
        }  
        
          $data['id_almacen'] = $this->input->post('id_almacen');

          $data['id_tipo_pedido'] = $this->input->post('id_tipo_pedido');
          $data['id_tipo_factura'] = $this->input->post('id_tipo_factura');
          if (($existe) and ($this->form_validation->run() === TRUE) and ($data['id_cliente']) and ($data['id_cargador']) ) {
                //verificar si los apartados estan siendo totales o parciales
                $dato['valor'] = $this->modelo_salida->cantidad_apartados($data);
                $dato['id_cliente'] = $data['id_cliente'];
                    $dato['exito'] = true;
                    echo json_encode($dato);
          } else {
                $dato['exito']  = false;
                $dato['errores'] =$errores;
                $dato['error'] = validation_errors('<span class="error">','</span>');
                echo json_encode($dato);
          }   
    

    } else { //fin de session
      redirect('');
    }   
    
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
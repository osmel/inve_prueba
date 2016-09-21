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


public function proc_pedido_cambio33(){

  if ($this->session->userdata('session') ===TRUE) {

  }
   
   $dato['exito'] = true;
  echo json_encode($dato);
}

public function proc_pedido_cambio(){


       if($this->session->userdata('session') === TRUE ){
            $id_perfil=$this->session->userdata('id_perfil');

            $coleccion_id_operaciones= json_decode($this->session->userdata('coleccion_id_operaciones')); 
            if ( (count($coleccion_id_operaciones)==0) || (!($coleccion_id_operaciones)) ) {
                  $coleccion_id_operaciones = array();
             }  

            $errores='';

        

                $this->form_validation->set_rules( 'factura', 'Factura', 'trim|required|min_length[2]|max_lenght[180]|xss_clean');

                if ($this->form_validation->run() === TRUE) {           
                      //actualizar cantidad aprobada
                      $data['movimiento'] =   $this->input->post('movimiento');
                      $data['cant_solicitada'] =  json_decode(json_encode( $this->input->post('arreglo_cant_solicitada') ),true  );
                      $data['cant_aprobada'] =  json_decode(json_encode( $this->input->post('arreglo_cant_aprobada') ),true  );
                      
                      $dato['aprobado'] = $this->model_pedido_compra->actualizar_cantidad_aprobado($data);
                      $dato['exito'] = true;
                      echo json_encode($dato);
                }  else {
                    $dato['exito']  = false;
                    //$dato['errores'] =$errores;
                    $dato['error'] = validation_errors('<span class="error">','</span>');
                    echo json_encode($dato);
                }          

    } else { //fin de session
      redirect('');
    }   
    
  }




public function pedido_compra_modal($aprobado,$movimiento){

      if ( $this->session->userdata('session') !== TRUE ) {
          redirect('');
        } else {

         

         
          $id_perfil=$this->session->userdata('id_perfil');

          $coleccion_id_operaciones= json_decode($this->session->userdata('coleccion_id_operaciones')); 
          if ( (count($coleccion_id_operaciones)==0) || (!($coleccion_id_operaciones)) ) {
                $coleccion_id_operaciones = array();
           }   
          
          $data['aprobado']         = base64_decode($aprobado);
          $data['movimiento']         = base64_decode($movimiento);

          switch ($id_perfil) {    
            case 1:
                  
                    $this->load->view( 'pedido_compra/pedido_compra_modal', $data );
              break;
            case 2:
            case 3:
            case 4:
                 if  (in_array(2, $coleccion_id_operaciones))  { 
                     $this->load->view( 'pedido_compra/pedido_compra_modal', $data );
                  }  else  {
                    redirect('');
                  } 
              break;
            default:  
              redirect('');
              break;
          }

          
       }      
  }

////////////////////////////////////////////////////////////////////////////////////////////////////////

public function detalle_revision($movimiento, $modulo){

     if($this->session->userdata('session') === TRUE ){
          $id_perfil=$this->session->userdata('id_perfil');

          $coleccion_id_operaciones= json_decode($this->session->userdata('coleccion_id_operaciones')); 
          if ( (count($coleccion_id_operaciones)==0) || (!($coleccion_id_operaciones)) ) {
                $coleccion_id_operaciones = array();
           }   
           
           $data['modulo']  = base64_decode($modulo); 
           $data['movimiento']  = base64_decode($movimiento); 

           //$data['retorno'] = base64_decode($modulo);

           switch ($data['modulo']) {
             case 1:
                  $data['retorno'] ='/pendiente_revision';
                  $revisar = 'revisar_compra';
                  $data['val_compra']  = $this->model_pedido_compra->valores_revision_temporal($data);
               break;
             case 2:
                  $data['retorno'] ='/solicitar_modificacion';
                  $revisar = 'revisar_compra';
                  $data['val_compra']  = $this->model_pedido_compra->valores_revision_temporal($data);
               break;
             case 3:
                  $data['retorno'] ='/aprobado';
                  $revisar = 'revisar_compra';
                  $data['val_compra']  = $this->model_pedido_compra->valores_revision_temporal($data);
               break;
             case 4:
                  $data['retorno'] ='/cancelado';
                  $revisar = 'revisar_compra_cancelada';
                  $data['val_compra']  = $this->model_pedido_compra->valores_revision_cancelar($data);
               break;
             case 5:
                  $data['retorno'] ='/gestionar_pedido_compra';
                  $revisar = 'revisar_compra';
                  $data['val_compra']  = $this->model_pedido_compra->valores_revision_temporal($data);
               break;
             
             default:
                  $revisar = 'revisar_compra';
               break;
           }

           //no. movimiento
           
           //Aqui busca el encabezado del pedido de compra
           



           $data['almacenes']   = $this->modelo->coger_catalogo_almacenes(2);

          switch ($id_perfil) {    
            case 1:          
                        $this->load->view( 'pedido_compra/'.$revisar,$data );
              break;
            case 2:
            case 3:
            case 4:
                  if  (in_array(29, $coleccion_id_operaciones))  {                 
                           $this->load->view( 'pedido_compra/'.$revisar,$data );

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


public function procesando_revisar_pedido_compra(){
      $data=$_POST;

          $data['modulo']        = $this->input->post('modulo');      

          switch ($data['modulo']) {    
            case 1:          
            case 2:   
            case 3:   
            case 5:   
                  $busqueda = $this->model_pedido_compra->buscador_revisar_pedido_compra($data);
              break;
            case 4:
                  $busqueda = $this->model_pedido_compra->buscador_revisar_cancela_compra($data);
                  //print_r($busqueda);
                  
                  //die;

                  
              break;
            default:  
                 $busqueda = $this->model_pedido_compra->buscador_revisar_pedido_compra($data);
              break;
          }




      
      echo $busqueda;
      
  }

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////

public function procesando_pedido_compra(){
      $data=$_POST;
          $data['modulo']        = $this->input->post('modulo');      

          switch ($data['modulo']) {    
            case 1:          
            case 2:   
            case 3:   
            case 5:   
                  $busqueda = $this->model_pedido_compra->buscador_pedido_compra($data);
              break;
            case 4:
                  $busqueda = $this->model_pedido_compra->buscador_cancela_compra($data);
                  
              break;


            default:  
                 $busqueda = $this->model_pedido_compra->buscador_pedido_compra($data);
              break;
          }




      
      echo $busqueda;
      
  }


public function pendiente_revision(){
  $data['modulo'] = 1;
  $data['titulo'] = "En proceso de revisión";
  self::modulo_pedido_compra($data);
}

public function solicitar_modificacion(){
  $data['modulo'] = 2;
  $data['titulo'] = "Solicitud de cambio";
  self::modulo_pedido_compra($data);
}

public function aprobado(){
  $data['modulo'] = 3;
  $data['titulo'] = "Aprobado";
  self::modulo_pedido_compra($data);
}

public function cancelado(){
  $data['modulo'] = 4;
  $data['titulo'] = "Cancelado";
  self::modulo_pedido_compra($data);
}

public function gestionar_pedido_compra(){
  $data['modulo'] = 5;
  $data['titulo'] = "Historico de pedidos";
  self::modulo_pedido_compra($data);
}


public function modulo_pedido_compra($data){

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
                        $this->load->view( 'pedido_compra/pedido_compra',$data );
              break;
            case 2:
            case 3:
            case 4:
                  if  (in_array(29, $coleccion_id_operaciones))  {                 
                           $this->load->view( 'pedido_compra/pedido_compra',$data );
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




public function nuevo_pedido_compra($url){

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

           $data['retorno'] = base64_decode($url);


           
           

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

            
            $existe  = $this->model_pedido_compra->valores_movimientos_temporal();

            $errores='';

        

        $this->form_validation->set_rules( 'factura', 'Factura', 'trim|required|min_length[2]|max_lenght[180]|xss_clean');

            
        if ($this->form_validation->run() === TRUE) {           
           if  (!($existe)) {
            $errores= "Debe agregar al menos un producto";
           } else {  //si estan agregados los productos entonces checar si tienen el peso real
              
              //actualizar peso real
              $data['cantidad'] =  json_decode(json_encode( $this->input->post('arreglo_pedido_compra') ),true  );
              $this->model_pedido_compra->actualizar_pedido_compra($data);

              //verificar si hay cantidades a pedir en cero 
              $existe = $this->model_pedido_compra->existencia_temporales_cantidad();
              if  (!($existe)) {
                $errores= "Existen productos sin especificar cantidades a pedir";
              } 

           }
           
        }  
        
          $data['id_almacen'] = $this->input->post('id_almacen');

          $data['consecutivo']  = ($this->catalogo->listado_consecutivo(26)->consecutivo)+1;

          if ( ($existe) and ($this->form_validation->run() === TRUE) ) {
                //verificar si los apartados estan siendo totales o parciales
                    
                    $this->model_pedido_compra->enviar_historico_pedido_compra($data);

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




  function cancelar_pedido_compra($movimiento, $modulo){
      if($this->session->userdata('session') === TRUE ){
      $id_perfil=$this->session->userdata('id_perfil');

      $coleccion_id_operaciones= json_decode($this->session->userdata('coleccion_id_operaciones')); 
      if ( (count($coleccion_id_operaciones)==0) || (!($coleccion_id_operaciones)) ) {
            $coleccion_id_operaciones = array();
       }   

      
           $data['modulo']  = base64_decode($modulo); 
           $data['movimiento']  = base64_decode($movimiento); 

           //$data['retorno'] = base64_decode($modulo);

           switch ($data['modulo']) {
             case 1:
                  $data['retorno'] ='/pendiente_revision';
               break;
             case 2:
                  $data['retorno'] ='/solicitar_modificacion';
               break;
             case 3:
                  $data['retorno'] ='/aprobado';
               break;
             case 4:
                  $data['retorno'] ='/cancelado';
               break;
             case 5:
                  $data['retorno'] ='/gestionar_pedido_compra';
               break;
             
             default:
                // $data['retorno'] ='/pendiente_revision';
               break;
           }


      switch ($id_perfil) {    
        case 1:           
                    $this->load->view( 'pedido_compra/eliminar_pedido_compra',$data );
          break;
            case 2:
            case 3:
            case 4:
                  if  (in_array(29, $coleccion_id_operaciones))  {                 
                         $this->load->view( 'pedido_compra/eliminar_pedido_compra',$data );
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


  function validar_cancelar_pedido_compra(){

    $data['movimiento']        = $this->input->post('movimiento');

    $cancelar = $this->model_pedido_compra->cancelar_pedido_compra(  $data );
    
    if ( $cancelar !== FALSE ){
      echo TRUE;
    } else {
      echo '<span class="error">No se ha podido eliminar la almacen</span>';
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
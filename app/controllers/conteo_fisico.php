<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Conteo_fisico extends CI_Controller {

  public function __construct(){
    parent::__construct();
    $this->load->model('model_conteo_fisico', 'model_conteo_fisico');
    $this->load->model('catalogo', 'catalogo');  
    $this->load->model('modelo', 'modelo');  
    $this->load->model('model_pedido', 'modelo_pedido');    
    $this->load->model('model_entradas', 'model_entrada');  

    $this->load->model('model_salida', 'modelo_salida');  


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
      $this->session->set_userdata('id_almacen_ajuste', $data['id_almacen']);
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


              $data['cantidad'] =  json_decode(json_encode( $this->input->post('arreglo_cantidad') ),true  );
            $data['id_almacen'] =   $this->input->post('id_almacen');
                $data['modulo'] =   $this->input->post('modulo');
            
            $dato['cantidades'] = $this->model_conteo_fisico->actualizar_cantidad($data);  
            $dato['exito'] = true;
            echo json_encode($dato);
        
              /*
                if ( ($this->form_validation->run() === TRUE) || ($d_conf['configuracion']->activo==0)  ) {
                      //actualizar cantidad aprobada
                      $data['movimiento'] =   $this->input->post('movimiento');
                      $data['comentario'] =   $this->input->post('comentario');
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
             */   

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

            $data['almacenes']   = $this->modelo->coger_catalogo_almacenes(2);  
            $data['productos'] = $this->catalogo->listado_productos_unico(); 

           $data['dato']['vista']  = "tabla_ajustes"; 
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
                    $this->load->view( 'conteo_fisico/ajustes', $data );
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
           
           

           $data['productos'] = $this->catalogo->listado_productos_unico();
           $data['colores'] = $this->catalogo->listado_colores_unico();
           $data['almacenes']   = $this->modelo->coger_catalogo_almacenes(2);
           $data['facturas']   = $this->catalogo->listado_tipos_facturas(-1,-1,'1');
           $data['pedidos']   = $this->catalogo->listado_tipos_pedidos(-1,-1,'1');


           $data['consecutivo']  = $this->catalogo->listado_consecutivo(2);
           
           $dato['id'] = 3;
           $data['cargador']   =  $this->catalogo->coger_cargador($dato)->nombre; 
           
           $dato['id'] = $id_cliente_asociado;
           $data['nombre']   =  $this->catalogo->tomar_proveedor($dato)->nombre; 
           $dato['id'] = 7;
           $data['configuracion'] = $this->catalogo->coger_configuracion($dato); 

         
               
            switch ($id_perfil) {    
              case 1:          
                          $this->load->view( 'conteo_fisico/salida_faltante',$data );
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

public function entrada_sobrante1111($modulo,$retorno){

              $data['id_empresa'] =  $this->session->userdata('id_cliente_asociado');
              $data['id_almacen']=$this->session->userdata('id_almacen_ajuste');   //bodega1
              $data['id_factura']=2; //remision
              $data['id_tipo_pago']=2; //contado
              $data['movimiento'] = $this->model_conteo_fisico->consecutivo_operacion_entrada(1,$data['id_factura']); //cambio
              $data['productos'] = $this->model_conteo_fisico->anadir_producto_temporal($data);
  
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
            $data['id_factura']=2; //remision
            $data['id_tipo_pago']=2; //contado
            $data['movimiento'] = $this->model_conteo_fisico->consecutivo_operacion_entrada(1,$data['id_factura']); //cambio
            $data['productos'] = $this->model_conteo_fisico->anadir_producto_temporal($data);

           
           $data['modulo']       = base64_decode($modulo);    
           $data['retorno']       = base64_decode($retorno);    
           
           
           

           $data['productos'] = $this->catalogo->listado_productos_unico();
           $data['colores'] = $this->catalogo->listado_colores_unico();
           $data['almacenes']   = $this->modelo->coger_catalogo_almacenes(2);
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

            







               
            switch ($id_perfil) {    
              case 1:          
                          $this->load->view( 'conteo_fisico/entrada_sobrante',$data );
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
          $data['id_factura']=2; //remision

          $data['dev'] = 0; 

          $coleccion_id_operaciones= json_decode($this->session->userdata('coleccion_id_operaciones')); 
          if ( (count($coleccion_id_operaciones)==0) || (!($coleccion_id_operaciones)) ) {
                $coleccion_id_operaciones = array();
           }  


            $data['id_almacen']=$this->session->userdata('id_almacen_ajuste');   //bodega1

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

            $data['movimientos']  = $this->model_entrada->listado_movimientos_registros($data);
            /* 
                
                foreach ($data['movimientos'] as $key => $value) {
                  
                  $params['data'] = $value->codigo;
                  $params['level'] = 'H';
                  $params['size'] = 30;
                  $params['savename'] = FCPATH.'qr_code/'.$value->codigo.'.png';
                  $this->ciqrcode->generate($params);    
                
                }

              $data['exito']  = true;
              echo json_encode($data);
          */

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

    $busqueda = $this->model_conteo_fisico->buscador_entrada($data);
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

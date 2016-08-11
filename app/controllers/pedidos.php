<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Pedidos extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('model_pedido', 'modelo_pedido');
		$this->load->model('catalogo', 'catalogo');  
		$this->load->model('modelo', 'modelo');  
		$this->load->library(array('email')); 
        $this->load->library('Jquery_pagination');//-->la estrella del equipo		

	}


///////////////////////////////////////////////////////salidas de pedidos/////////////////////////////////////////////////


 function cargar_dependencia_pedido(){
    
    $data['campo']        = $this->input->post('campo');

    $data['val_prod']        = $this->input->post('val_prod');
    $data['val_comp']        = $this->input->post('val_comp');
    $data['val_ancho']        = $this->input->post('val_ancho');
    $data['val_color']        = $this->input->post('val_color');
    $data['val_proveedor']        = $this->input->post('val_proveedor');

    $data['dependencia']        = $this->input->post('dependencia');

    switch ($data['dependencia']) {
        case "producto_pedido": //nunca será una dependencia
            $elementos  = $this->modelo_pedido->listado_productos();
            break;
        case "composicion_pedido":
            $elementos  = $this->modelo_pedido->lista_composiciones($data);
            break;
        case "ancho_pedido":
            $elementos  = $this->modelo_pedido->lista_ancho($data);
            break;
        case "color_pedido":
            $elementos  = $this->modelo_pedido->lista_colores($data);
            break;

        case "proveedor_pedido":
            $elementos  = $this->modelo_pedido->lista_proveedores($data);
            break;

        default:
    }



      $variables = array();
    if ($elementos != false)  {     
         foreach( (json_decode(json_encode($elementos))) as $clave =>$valor ) {
            if ($data['dependencia']=="color_pedido"){
              array_push($variables,array('nombre' => $valor->nombre, 'identificador' => $valor->id, 'hexadecimal_color' => $valor->hexadecimal_color)); 
            } else {
              array_push($variables,array('nombre' => $valor->nombre, 'identificador' => $valor->id, 'hexadecimal_color' => "FFFFFF"));  
            }
       }
    }  

     echo json_encode($variables);
  }



	public function listado_pedidos(){

		 if($this->session->userdata('session') === TRUE ){
		      $id_perfil=$this->session->userdata('id_perfil');

		      $coleccion_id_operaciones= json_decode($this->session->userdata('coleccion_id_operaciones')); 
		      if ( (count($coleccion_id_operaciones)==0) || (!($coleccion_id_operaciones)) ) {
		            $coleccion_id_operaciones = array();
		       }   
		       
		       //no. movimiento
		       $data['consecutivo']  = $this->catalogo->listado_consecutivo(4);
		       //valor del cliente, cargador, factura, 
		       $data['val_proveedor']  = $this->modelo_pedido->valores_movimientos_temporal();
		       $data['productos'] = $this->modelo_pedido->listado_productos_unico();
		       $data['almacenes']   = $this->modelo->coger_catalogo_almacenes(2);

		      switch ($id_perfil) {    
		        case 1:          
		                    $this->load->view( 'salidas_pedidos/salida_pedido',$data );
		          break;
		        case 2:
		        case 3:
		              if  (in_array(4, $coleccion_id_operaciones))  {                 
		                        $this->load->view( 'salidas_pedidos/salida_pedido',$data );
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



//***********************"http://inventarios.dev.com/pedidos"**********************************//

	//muestra las 3 regillas de "/pedidos"
	public function listado_apartados(){
		if($this->session->userdata('session') === TRUE ){
		      $id_perfil=$this->session->userdata('id_perfil');

		      $coleccion_id_operaciones= json_decode($this->session->userdata('coleccion_id_operaciones')); 
		      if ( (count($coleccion_id_operaciones)==0) || (!($coleccion_id_operaciones)) ) {
		            $coleccion_id_operaciones = array();
		       }   
		       $data['almacenes']   = $this->modelo->coger_catalogo_almacenes(2);
		       //no. movimiento $data

		      switch ($id_perfil) {    
		        case 1:          
		                    $this->load->view( 'pedidos/pedidos' ,$data );     
		          break;
		        case 2:
		        case 3:
		              if  (in_array(10, $coleccion_id_operaciones))  {            
		              			$this->load->view( 'pedidos/pedidos' ,$data );     
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


////////////////////////regilla  que estan en "http://inventarios.dev.com/pedidos"//////////////////////////

		public function conteo_tienda(){
			$where_total = '( m.id_apartado = 2 ) or ( m.id_apartado = 3 ) ';
			$dato['vendedor'] = (string)$this->modelo_pedido->total_apartados_pendientes($where_total);

			$where_total = '( m.id_apartado = 5 ) or ( m.id_apartado = 6 ) ';
			$dato['tienda'] = (string)$this->modelo_pedido->total_pedidos_pendientes($where_total);  
			echo  json_encode($dato);
		}	
  

	//1ra Regilla PARA "Pedidos de vendedores"
	public function procesando_apartado_pendiente(){
		$data=$_POST;
		$busqueda = $this->modelo_pedido->buscador_apartados_pendientes($data);
		echo $busqueda;
	}	

	//2da Regilla PARA "Pedidos de tiendas"
	public function procesando_pedido_pendiente(){
		$data=$_POST;
		$busqueda = $this->modelo_pedido->buscador_pedidos_pendientes($data);
		echo $busqueda;
	}


	//3ra Regilla PARA "Histórico de Pedidos"
	public function procesando_pedido_completo(){
		$data=$_POST;
		$busqueda = $this->modelo_pedido->buscador_pedidos_completo($data);
		echo $busqueda;
	}


////////////////////////Registros de cada detalle de  "http://inventarios.dev.com/pedidos"//////////////////////////


	//"Regilla detalle" de la 1ra PARA "Pedidos de vendedores"
	//http://inventarios.dev.com/apartado_detalle/MGNjNTUxMGYtYzQ1Mi0xMWU0LThhZGEtNzA3MWJjZTE4MWMz/MTE=
	public function procesando_detalle(){
		$data=$_POST;
		$busqueda = $this->modelo_pedido->buscador_apartados_detalle($data);
		echo $busqueda;
	}

    //"Regilla detalle" de la 2da PARA  "Pedidos de tiendas" 
    //http://inventarios.dev.com/pedido_detalle/MjQ=
	public function procesando_pedido_detalle(){
		$data=$_POST;
		$busqueda = $this->modelo_pedido->buscador_pedido_especifico($data);
		echo $busqueda;
	}
	

    //"Regilla detalle" de la 3ra PARA "Histórico de Pedidos"
	//http://inventarios.dev.com/pedido_completado_detalle/NTA=/Ng==
	public function procesando_completo_detalle(){
		$data=$_POST;
		$busqueda = $this->modelo_pedido->buscador_completo_especifico($data);
		echo $busqueda;
	}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////




////////////////////////// (pedidos vendedores)
		

	public function apartado_detalle($id_usuario,$id_cliente,$id_almacen,$consecutivo_venta){


		if($this->session->userdata('session') === TRUE ){
		      $id_perfil=$this->session->userdata('id_perfil');

		      $coleccion_id_operaciones= json_decode($this->session->userdata('coleccion_id_operaciones')); 
		      if ( (count($coleccion_id_operaciones)==0) || (!($coleccion_id_operaciones)) ) {
		            $coleccion_id_operaciones = array();
		       }   
		       
		       //no. movimiento $data
		       	$data['id_usuario'] = base64_decode($id_usuario);
				$data['id_cliente'] = base64_decode($id_cliente);
				$data['id_almacen'] = base64_decode($id_almacen);

				$data['id']=$data['id_almacen'];
				if ($data['id']==0){
					$data['almacen'] = 'Todos';	
				} else {
					$data['almacen'] = $this->catalogo->coger_almacen($data)->almacen;
				}

				$data['consecutivo_venta'] = base64_decode($consecutivo_venta);				
				


		      switch ($id_perfil) {    
		        case 1:          
		                    $this->load->view( 'pedidos/apartado_detalle',$data);   
		          break;
		        case 2:
		        case 3:
		              if  (in_array(10, $coleccion_id_operaciones))  {            
		              			$this->load->view( 'pedidos/apartado_detalle',$data);
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


////////////////////////// (pedidos tiendas)

	public function pedido_detalle($num_mov,$id_almacen){


		if($this->session->userdata('session') === TRUE ){
		      $id_perfil=$this->session->userdata('id_perfil');

		      $coleccion_id_operaciones= json_decode($this->session->userdata('coleccion_id_operaciones')); 
		      if ( (count($coleccion_id_operaciones)==0) || (!($coleccion_id_operaciones)) ) {
		            $coleccion_id_operaciones = array();
		       }   
		       
		       //no. movimiento $data
				$data['num_mov'] = base64_decode($num_mov);
				$data['id_almacen'] = base64_decode($id_almacen);

				$data['id']=$data['id_almacen'];
				if ($data['id']==0){
					$data['almacen'] = 'Todos';	
				} else {
					$data['almacen'] = $this->catalogo->coger_almacen($data)->almacen;
				}


		      switch ($id_perfil) {    
		        case 1:          
		                   $this->load->view( 'pedidos/pedido_detalle',$data);
		          break;
		        case 2:
		        case 3:
		              if  (in_array(10, $coleccion_id_operaciones))  {            
		              	  $this->load->view( 'pedidos/pedido_detalle',$data);
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

////////////////////////// (Histórico de pedidos)

	public function pedido_completado_detalle($mov_salida,$id_apartado,$id_almacen,$consecutivo_venta){


		if($this->session->userdata('session') === TRUE ){
		      $id_perfil=$this->session->userdata('id_perfil');

		      $coleccion_id_operaciones= json_decode($this->session->userdata('coleccion_id_operaciones')); 
		      if ( (count($coleccion_id_operaciones)==0) || (!($coleccion_id_operaciones)) ) {
		            $coleccion_id_operaciones = array();
		       }   
		       
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

				$data['consecutivo_venta'] = base64_decode($consecutivo_venta);				

		      switch ($id_perfil) {    
		        case 1:          
		                   $this->load->view( 'pedidos/pedido_completo_detalle',$data);
		          break;
		        case 2:
		        case 3:
		              if  (in_array(10, $coleccion_id_operaciones))  {            
		              	  $this->load->view( 'pedidos/pedido_completo_detalle',$data);
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




/////////////////////////////////////hasta aqui pedidos completados///////////////////////////////////




/////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////



	function marcando_prorroga_venta(){

	    if ($this->session->userdata('session') !== TRUE) {
	      redirect('');
	    } else {

	    	$data['id_usuario_apartado'] = base64_decode($this->input->post('id_usuario_apartado'));
	    	$data['id_cliente_apartado'] = base64_decode($this->input->post('id_cliente_apartado'));
	    			 $data['id_almacen'] = $this->input->post('id_almacen');
	    	  $data['consecutivo_venta'] = base64_decode($this->input->post('consecutivo_venta'));

	    	$actualizar = $this->modelo_pedido->marcando_prorroga_venta($data);

	    	echo  $actualizar;

		}	
   }

   


	function marcando_prorroga_tienda(){

	    if ($this->session->userdata('session') !== TRUE) {
	      redirect('');
	    } else {

	    	$data['id_cliente_apartado'] = base64_decode($this->input->post('id_cliente_apartado'));
	    			 $data['id_almacen'] = $this->input->post('id_almacen');

	    	$actualizar = $this->modelo_pedido->marcando_prorroga_tienda($data);

	    	echo  $actualizar;

		}	
   }





/////////////////////////////////////////"http://inventarios.dev.com/generar_pedidos"////////////////////////////


   //1ra regilla de "/generar_pedidos"
	public function procesando_pedido_entrada(){
		$data=$_POST;
		$busqueda = $this->modelo_pedido->buscador_entrada_pedido($data);
		echo $busqueda;
	}

	//2da regilla de "/generar_pedidos"
	public function procesando_pedido_salida(){
		$data=$_POST;
		$busqueda = $this->modelo_pedido->buscador_salida_pedido($data);
		echo $busqueda;
	}



function agregar_prod_pedido(){

	    if ($this->session->userdata('session') !== TRUE) {
	      redirect('');
	    } else {
	 		$data['id'] = $this->input->post('identificador');
	 		$data['id_movimiento'] = $this->input->post('movimiento');

			$actualizar = $this->modelo_pedido->actualizar_pedido($data);
			$dato['exito']  = true;
			echo json_encode($dato);
		}	
   }


	//quitar_prod_salida
	function quitar_prod_pedido(){

	    if ($this->session->userdata('session') !== TRUE) {
	      redirect('');
	    } else {

	 		$data['id'] = $this->input->post('identificador');
			$actualizar = $this->modelo_pedido->quitar_pedido($data);
			$dato['exito']  = true;
			echo json_encode($dato);
				
		}	
   }






   	//confirmacion pedido
	public function pedido_definitivo(){

		if($this->session->userdata('session') === TRUE ){
	
		        $id_perfil=$this->session->userdata('id_perfil');
 
  		        $data['num_mov'] = $this->input->post('num_mov');

			    $actualizar = $this->modelo_pedido->pedido_definitivamente($data);

				if ( $actualizar !== FALSE ){
					echo TRUE;
				} else {
					echo '<span class="error">No se han podido apartar los productos</span>';
				}
		
		} else {      
			
   			 echo validation_errors('<span class="error">','</span>');

  		}		

	
	}	






	//////////////////////////Incluir pedido a la salida///////////////////////////////////

	function incluir_pedido(){

	    if ($this->session->userdata('session') !== TRUE) {
	      redirect('');
	    } else {

	    	$data['num_mov'] = $this->input->post('num_mov');
	    	$data['id_almacen'] = $this->input->post('id_almacen');
	    	$data['id_apartado'] = 6;

	    	$actualizar = $this->modelo_pedido->incluir_pedido($data);

	    	echo  json_encode($actualizar);

		}	
   }


	function excluir_pedido(){

	    if ($this->session->userdata('session') !== TRUE) {
	      redirect('');
	    } else {

			$data['num_mov'] = $this->input->post('num_mov');
			$data['id_almacen'] = $this->input->post('id_almacen');
	    	$data['id_apartado'] = 5;

	    	$actualizar = $this->modelo_pedido->incluir_pedido($data);

	    	echo  json_encode($actualizar);

		}	
   }




//////////////////////////eliminar pedido detalle//////////////////////////////

	public function eliminar_pedido_detalle($num_mov,$id_almacen){


	    if ($this->session->userdata('session') === TRUE ){
          $id_perfil=$this->session->userdata('id_perfil');

           $data['num_mov'] = base64_decode($num_mov);
           $data['id_almacen'] = base64_decode($id_almacen);
		   


          $coleccion_id_operaciones= json_decode($this->session->userdata('coleccion_id_operaciones')); 
          if ( (count($coleccion_id_operaciones)==0) || (!($coleccion_id_operaciones)) ) {
                $coleccion_id_operaciones = array();
           }   

          switch ($id_perfil) {    
            case 1:
                      $this->load->view( 'pedidos/eliminar_pedido', $data );                
              break;
            case 2:
            case 3:
                 if  (in_array(10, $coleccion_id_operaciones))  { 
	                      $this->load->view( 'pedidos/eliminar_pedido', $data );
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


	function validar_eliminar_pedido_detalle(){
		$data['num_mov'] = $this->input->post('num_mov');
		$data['id_almacen'] = $this->input->post('id_almacen');

		$cancelar = $this->modelo_pedido->cancelar_pedido_detalle($data);
		if ( $cancelar !== FALSE ){
			echo TRUE;
		} else {
			echo '<span class="error">No se ha podido eliminar al usuario</span>';
		}
	}




//////////////////////////////////////////////////////////////////////////////


//////////////////////////eliminar apartado detalle//////////////////////////////

	public function eliminar_apartado_detalle($id_usuario,$id_cliente,$id_almacen,$consecutivo_venta){


	    if ($this->session->userdata('session') === TRUE ){
          $id_perfil=$this->session->userdata('id_perfil');

           $data['id_usuario'] = base64_decode($id_usuario);
		   $data['id_cliente'] = base64_decode($id_cliente);
		   $data['id_almacen'] = base64_decode($id_almacen);


          $coleccion_id_operaciones= json_decode($this->session->userdata('coleccion_id_operaciones')); 
          if ( (count($coleccion_id_operaciones)==0) || (!($coleccion_id_operaciones)) ) {
                $coleccion_id_operaciones = array();
           }   

           $data['consecutivo_venta'] = base64_decode($consecutivo_venta);
				
          switch ($id_perfil) {    
            case 1:
                      $this->load->view( 'pedidos/eliminar_apartado', $data );                
              break;
            case 2:
            case 3:
                 if  (in_array(10, $coleccion_id_operaciones))  { 
	                      $this->load->view( 'pedidos/eliminar_apartado', $data );
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


	function validar_eliminar_apartado_detalle(){
		$data['id_usuario'] = $this->input->post('id_usuario');
		$data['id_cliente'] = $this->input->post('id_cliente');
		$data['id_almacen'] = $this->input->post('id_almacen');
		$data['consecutivo_venta'] = $this->input->post('consecutivo_venta');


		$cancelar = $this->modelo_pedido->cancelar_apartados_detalle($data);
		if ( $cancelar !== FALSE ){
			echo TRUE;
		} else {
			echo '<span class="error">No se ha podido eliminar al usuario</span>';
		}
	}	



//////////////////////////Incluir apartado a la salida///////////////////////////////////

	function incluir_apartado(){

	    if ($this->session->userdata('session') !== TRUE) {
	      redirect('');
	    } else {

	    	$data['id_usuario'] = $this->input->post('id_usuario');
	    	$data['id_cliente'] = $this->input->post('id_cliente');
	    	$data['id_almacen'] = $this->input->post('id_almacen');
	    	$data['consecutivo_venta'] = $this->input->post('consecutivo_venta');
	    	$data['id_apartado'] = 3;

	    	$actualizar = $this->modelo_pedido->incluir_apartado($data);

	    	echo  json_encode($actualizar);

		}	
   }


	function excluir_apartado(){

	    if ($this->session->userdata('session') !== TRUE) {
	      redirect('');
	    } else {

	    	$data['id_usuario'] = $this->input->post('id_usuario');
	    	$data['id_cliente'] = $this->input->post('id_cliente');
	    	$data['id_almacen'] = $this->input->post('id_almacen');
	    	$data['consecutivo_venta'] = $this->input->post('consecutivo_venta');
	    	$data['id_apartado'] = 2;

	    	$actualizar = $this->modelo_pedido->incluir_apartado($data);

	    	echo  json_encode($actualizar);

		}	
   }





}

/* End of file nucleo.php */
/* Location: ./app/controllers/nucleo.php */
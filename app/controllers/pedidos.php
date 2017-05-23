<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Pedidos extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('model_pedido', 'modelo_pedido');
		$this->load->model('model_pedido_compra', 'model_pedido_compra'); 
		$this->load->model('catalogo', 'catalogo');  
		$this->load->model('modelo', 'modelo');  
		$this->load->library(array('email')); 
        $this->load->library('Jquery_pagination');//-->la estrella del equipo		

	}


///////////////////////////////////////////////////////salidas de pedidos/////////////////////////////////////////////////


 function cargar_dependencia_pedido() {
    
    $data['campo']        = $this->input->post('campo');

    $data['val_prod']        = $this->input->post('val_prod');
    $data['val_prod_id']        = $this->input->post('val_prod_id');

    $data['val_comp']        = $this->input->post('val_comp');
    $data['val_ancho']        = (float)$this->input->post('val_ancho');
    $data['val_ancho_cad']        = $this->input->post('val_ancho');
    $data['val_color']        = $this->input->post('val_color');
    $data['val_proveedor']        = $this->input->post('val_proveedor');

    $data['dependencia']        = $this->input->post('dependencia');


			$elementos['producto_pedido']  = $this->modelo_pedido->listado_productos_completa($data);
        	$elementos['composicion_pedido']  = $this->modelo_pedido->lista_composiciones_completa($data);
            $elementos['ancho_pedido']  = $this->modelo_pedido->lista_ancho_completa($data);
            $elementos['color_pedido']  = $this->modelo_pedido->lista_colores_completa($data);
            $elementos['proveedor_pedido']  = $this->modelo_pedido->lista_proveedores_completa($data);

    echo json_encode($elementos); 


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
		       $data['facturas']   = $this->catalogo->listado_tipos_facturas(-1,-1,'1');
		       $data['pedidos']   = $this->catalogo->listado_tipos_pedidos(-1,-1,'1');
		       
		       

		      switch ($id_perfil) {    
		        case 1:          
		                    $this->load->view( 'salidas_pedidos/salida_pedido',$data );
		          break;
		        case 2:
		        case 3:
		        case 4:
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
		        case 4:
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


          if ($this->session->userdata('id_almacen') != 0) {
              $id_almacenid = ' AND ( m.id_almacen =  '.$this->session->userdata('id_almacen').' ) ';  
          } else {
              $id_almacenid = '';
          } 

          $perfil= $this->session->userdata('id_perfil'); 
          $id_session = $this->session->userdata('id');
          
         if ( ( $perfil == 3 ) OR ( $perfil == 4 ) ) { 
            $restriccion  =' AND (m.id_usuario_apartado = "'.$id_session.'")';
         } else {
         	$restriccion = '';
         }



          
         if (  $perfil != 4 ) {
	     		$where_total = '(( m.id_apartado = 2 ) or ( m.id_apartado = 3 ))'.$id_almacenid.$restriccion;
				$dato['vendedor'] = (string)$this->modelo_pedido->total_apartados_pendientes($where_total);
         } else {
         	$dato['vendedor'] ="0";
         }

        if (  $perfil != 3 ) {
			$where_total = '(( m.id_apartado = 5 ) or ( m.id_apartado = 6 ))'.$id_almacenid.$restriccion;
			$dato['tienda'] = (string)$this->modelo_pedido->total_pedidos_pendientes($where_total);  
         } else {
         	$dato['tienda'] ="0";
         }


         	//$data['modulo']=$this->session->userdata('id_perfil'); 
         	$data['modulo'] = ($this->session->userdata('id_perfil')!=2) ? 1: 2; 
         	$dato['compra'] = (string)$this->model_pedido_compra->notificador_pedido_compra($data);  
	
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
/*
                                      11=>$row->id_tipo_pedido,   
                                      12=>$row->id_tipo_factura,   
                                      $this->db->select('m.id_tipo_pedido,m.id_tipo_factura', FALSE);

                                      ( m.id_tipo_pedido =  '.$data["id_tipo_pedido"].' )  AND ( m.id_tipo_factura =  '.$data["id_tipo_factura"].' )  AND 

                        			$data['id_tipo_pedido'] = base64_decode($id_tipo_pedido);
										$data['id_tipo_factura'] = base64_decode($id_tipo_factura);				


*/


////////////////////////Registros de cada detalle de  "http://inventarios.dev.com/pedidos"//////////////////////////
										/*
                                      10=>$row->almacen,
                                      11=>$row->id_factura,
                                      12=>$row->id_tipo_factura,
                                      13=>$row->id_tipo_pedido,
                                      14=>$row->t_factura,    */
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

	//14=>$row->iva,
	

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
		

	public function apartado_detalle($id_usuario,$id_cliente,$id_almacen,$consecutivo_venta,$id_tipo_pedido,$id_tipo_factura){


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
				$data['id_tipo_pedido'] = base64_decode($id_tipo_pedido);
				$data['id_tipo_factura'] = base64_decode($id_tipo_factura);				

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
		        case 4:
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

	public function pedido_detalle($num_mov,$id_almacen,$id_tipo_pedido,$id_tipo_factura){


		if($this->session->userdata('session') === TRUE ){
		      $id_perfil=$this->session->userdata('id_perfil');

		      $coleccion_id_operaciones= json_decode($this->session->userdata('coleccion_id_operaciones')); 
		      if ( (count($coleccion_id_operaciones)==0) || (!($coleccion_id_operaciones)) ) {
		            $coleccion_id_operaciones = array();
		       }   
		       
		       //no. movimiento $data
				$data['num_mov'] = base64_decode($num_mov);
				$data['id_almacen'] = base64_decode($id_almacen);
				$data['id_tipo_pedido'] = base64_decode($id_tipo_pedido);
				$data['id_tipo_factura'] = base64_decode($id_tipo_factura);				


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
		        case 4:
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

	public function pedido_completado_detalle($mov_salida,$id_apartado,$id_almacen,$consecutivo_venta,$id_tipo_pedido,$id_tipo_factura){


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

				$data['id_tipo_pedido'] = base64_decode($id_tipo_pedido);
				$data['id_tipo_factura'] = base64_decode($id_tipo_factura);

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
		        case 4:
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

			  if ($this->input->post('id_cliente')) {

						$data['descripcion'] = $this->input->post('id_cliente');
						$data['idproveedor'] = "3";
						$data['id_cliente'] =  $this->catalogo->checar_existente_proveedor($data);

						if (!($data['id_cliente'])){
							$dato['mensaje'] = "El cliente no existe";
						}
			  } else {
			  	$data['id_cliente']=null;
			  	$dato['mensaje'] =  "Campo <b>cliente</b> obligatorio. ";

			  }	 		

			if  ($data['id_cliente'])  {
		 		$data['id'] = $this->input->post('identificador');
		 		$data['id_movimiento'] = $this->input->post('movimiento');
		 		$data['id_tipo_factura'] = $this->input->post('id_tipo_factura');
		 		$data['id_tipo_pedido'] = $this->input->post('id_tipo_pedido');

				$actualizar = $this->modelo_pedido->actualizar_pedido($data);
				$dato['exito']  = true;
			} else {      
					
	       		$dato['exito'] = validation_errors('<span class="error">','</span>');

	      	}		

			  
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
  		        $data['id_tipo_pedido'] = $this->input->post('id_tipo_pedido');
  		        $data['id_tipo_factura'] = $this->input->post('id_tipo_factura');

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

	    	$data['id_tipo_pedido']  = $this->input->post('id_tipo_pedido');
	    	$data['id_tipo_factura'] = $this->input->post('id_tipo_factura');

	    	$actualizar = $this->modelo_pedido->incluir_pedido($data);

	    	if ($data['id_tipo_factura']!=0) {
	    		$this->modelo_pedido->traspaso_pedido($data);
	    	}
	    	
	    	

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

	public function eliminar_pedido_detalle($num_mov,$id_almacen,$id_tipo_pedido,$id_tipo_factura){


	    if ($this->session->userdata('session') === TRUE ){
          $id_perfil=$this->session->userdata('id_perfil');

           $data['num_mov'] = base64_decode($num_mov);
           $data['id_almacen'] = base64_decode($id_almacen);
           $data['id_tipo_pedido'] = base64_decode($id_tipo_pedido);
		   $data['id_tipo_factura'] = base64_decode($id_tipo_factura);				
				
		   


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
            case 4:
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
		$data['id_tipo_pedido'] = $this->input->post('id_tipo_pedido');
		$data['id_tipo_factura'] = $this->input->post('id_tipo_factura');
		
				$this->modelo_pedido->cancelar_traspaso_pedido_detalle($data);
				
		$cancelar = $this->modelo_pedido->cancelar_pedido_detalle($data);
		if ( $cancelar !== FALSE ){
			echo TRUE;
		} else {
			echo '<span class="error">No se ha podido eliminar al usuario</span>';
		}
	}




//////////////////////////////////////////////////////////////////////////////


//////////////////////////eliminar apartado detalle//////////////////////////////

	public function eliminar_apartado_detalle($id_usuario,$id_cliente,$id_almacen,$consecutivo_venta,$id_tipo_pedido,$id_tipo_factura){


	    if ($this->session->userdata('session') === TRUE ){
          $id_perfil=$this->session->userdata('id_perfil');

           $data['id_usuario'] = base64_decode($id_usuario);
		   $data['id_cliente'] = base64_decode($id_cliente);
		   $data['id_almacen'] = base64_decode($id_almacen);
		   $data['id_tipo_pedido'] = base64_decode($id_tipo_pedido);
		   $data['id_tipo_factura'] = base64_decode($id_tipo_factura);				
				


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
            case 4:
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

		$data['id_tipo_pedido'] = $this->input->post('id_tipo_pedido');
		$data['id_tipo_factura'] = $this->input->post('id_tipo_factura');

		$this->modelo_pedido->cancelar_traspaso_apartado_detalle($data);

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

	    	$data['id_tipo_factura'] = $this->input->post('id_tipo_factura');

	    	$actualizar = $this->modelo_pedido->incluir_apartado($data);

	    	

	    	if ($data['id_tipo_factura']!=0) {
	    		$this->modelo_pedido->traspaso_apartado($data);
	    	}

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
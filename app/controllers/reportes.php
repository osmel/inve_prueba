<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Reportes extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('model_pedido', 'modelo_pedido');
		$this->load->model('modelo_reportes', 'modelo_reportes');  
		$this->load->model('modelo_costo_inventario', 'modelo_costo_inventario');  
		
	    $this->load->model('catalogo', 'catalogo');  
	    $this->load->model('modelo', 'modelo');  

		$this->load->library(array('email')); 
		$this->load->library('Jquery_pagination');//-->la estrella del equipo	
	}


//***********************Todos los catalogos**********************************//
	public function listado_reportes(){
		


  if($this->session->userdata('session') === TRUE ){
          $id_perfil=$this->session->userdata('id_perfil');

          $coleccion_id_operaciones= json_decode($this->session->userdata('coleccion_id_operaciones')); 
          if ( (count($coleccion_id_operaciones)==0) || (!($coleccion_id_operaciones)) ) {
                $coleccion_id_operaciones = array();
           }   
              $data['medidas']  = $this->catalogo->listado_medidas();
              $data['estatuss']  = $this->catalogo->listado_estatus(-1,-1,-1);
              $data['lotes']  = $this->catalogo->listado_lotes(-1,-1,'1');
              $data['productos'] = $this->catalogo->listado_productos_unico();
              $data['almacenes']   = $this->modelo->coger_catalogo_almacenes(2);
              $data['facturas']   = $this->catalogo->listado_tipos_facturas(-1,-1,'1');

          switch ($id_perfil) {    
            case 1:          

                        $this->load->view( 'reportes/reportes',$data );
              break;
            case 2:
            case 3:
            case 4:
                  if  (in_array(9, $coleccion_id_operaciones))  {                 
                            $this->load->view( 'reportes/reportes',$data );
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


	/////////////////////////////Presentacion de la regilla de inicio 
		//existencia 
	public function procesando_reporte(){ //13=>$row->num_partida,
		$data=$_POST;
		$estatus= $data['extra_search'];  //$row=> 
		switch ($estatus) {
			case 'salida':
				$busqueda = $this->modelo_reportes->buscador_salida_home($data); //13 782
			   break;
			case 'existencia':
			case 'apartado':
				$busqueda = $this->modelo_reportes->buscador_entrada_home($data); //13 443
			   break;

			case 'devolucion':
			case 'entrada':
				$busqueda = $this->modelo_reportes->buscador_entrada_devolucion($data); //13 443
			   break;

			case 'baja':
			case 'cero':
				$busqueda = $this->modelo_reportes->buscador_cero_baja($data); //13 1049
			   break;

			case 'top':
				$busqueda = $this->modelo_reportes->buscador_top($data); //1248
			   break;


			default:
				break;
		}
		echo $busqueda;
	}

//////////////////////////////////devolucion///////////////////////

	public function listado_devolucion(){

		 if($this->session->userdata('session') === TRUE ){
		      $id_perfil=$this->session->userdata('id_perfil');

		      $coleccion_id_operaciones= json_decode($this->session->userdata('coleccion_id_operaciones')); 
		      if ( (count($coleccion_id_operaciones)==0) || (!($coleccion_id_operaciones)) ) {
		            $coleccion_id_operaciones = array();
		       }   

              $data['almacenes']   = $this->modelo->coger_catalogo_almacenes(2);
              $data['facturas']   = $this->catalogo->listado_tipos_facturas(-1,-1,'1');
				
		      switch ($id_perfil) {    
		        case 1:          

		                    $this->load->view( 'reportes/devolucion/historico_devolucion',$data );
		          break;
		        case 2:
		        case 3:
		        case 4:
		              if  (in_array(9, $coleccion_id_operaciones))  {                 
		                        $this->load->view( 'reportes/devolucion/historico_devolucion',$data );
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



 public function procesando_historico_devolucion(){

    $data=$_POST;
	$busqueda  = $this->modelo_reportes->buscador_historico_devolucion($data);

    echo $busqueda;
  } 

//***********************Los azules mios**********************************//
	//consulta de entradas por movimientos
	public function listado_notas(){

		 if($this->session->userdata('session') === TRUE ){
		      $id_perfil=$this->session->userdata('id_perfil');

		      $coleccion_id_operaciones= json_decode($this->session->userdata('coleccion_id_operaciones')); 
		      if ( (count($coleccion_id_operaciones)==0) || (!($coleccion_id_operaciones)) ) {
		            $coleccion_id_operaciones = array();
		       }   


			  
              $data['almacenes']   = $this->modelo->coger_catalogo_almacenes(2);
              $data['facturas']   = $this->catalogo->listado_tipos_facturas(-1,-1,'1');




		      switch ($id_perfil) {    
		        case 1:          

		                    $this->load->view( 'reportes/entradas/historico_entrada',$data );
		          break;
		        case 2:
		        case 3:
		        case 4:
		              if  (in_array(9, $coleccion_id_operaciones))  {                 
		                        $this->load->view( 'reportes/entradas/historico_entrada',$data );
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


 public function procesando_historico_entrada(){

    $data=$_POST;
    //$busqueda = $this->catalogo->buscador_cat_colores($data);
    //$data['id_operacion'] =1;
	$busqueda  = $this->modelo_reportes->buscador_historico_entradas($data);

    echo $busqueda;
  } 

	


	//consulta de salidas por movimientos
	public function listado_salidas(){

		 if($this->session->userdata('session') === TRUE ){
		      $id_perfil=$this->session->userdata('id_perfil');

		      $coleccion_id_operaciones= json_decode($this->session->userdata('coleccion_id_operaciones')); 
		      if ( (count($coleccion_id_operaciones)==0) || (!($coleccion_id_operaciones)) ) {
		            $coleccion_id_operaciones = array();
		       }   


              $data['almacenes']   = $this->modelo->coger_catalogo_almacenes(2);
              $data['facturas']   = $this->catalogo->listado_tipos_facturas(-1,-1,'1');

		      switch ($id_perfil) {    
		        case 1:          

		                    $this->load->view( 'reportes/salidas/historico_salida',$data );
		          break;
		        case 2:
		        case 3:
		        case 4:
		              if  (in_array(9, $coleccion_id_operaciones))  {                 
		                        $this->load->view( 'reportes/salidas/historico_salida' ,$data );
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

 public function procesando_historico_salida(){

    $data=$_POST;
	$busqueda  = $this->modelo_reportes->buscador_historico_salida($data);

    echo $busqueda;
  } 


	/////////////////////////////exportar_reporte 
	public function exportar_reporte(){
		$data=$_POST;
		$estatus= $data['extra_search'];
		switch ($estatus) {
			case 'salida':
				$busqueda = $this->modelo_reportes->exportar_salida_home($data);
			   break;
			case 'existencia':
			case 'devolucion':
			case 'apartado':
				//$busqueda = $this->modelo_reportes->buscador_entrada_home($data);
			   break;
			case 'baja':
			case 'cero':
				//$busqueda = $this->modelo_reportes->buscador_cero_baja($data);
			   break;
			case 'top':
				//$busqueda = $this->modelo_reportes->buscador_top($data);
			   break;


			default:
				break;
		}
		print_r($busqueda);
		//echo json_encode(array("osmel"=>"hijos"));
	}


	public function costo_inventario(){

       if($this->session->userdata('session') === TRUE ){
          $id_perfil=$this->session->userdata('id_perfil');

          $coleccion_id_operaciones= json_decode($this->session->userdata('coleccion_id_operaciones')); 
          if ( (count($coleccion_id_operaciones)==0) || (!($coleccion_id_operaciones)) ) {
                $coleccion_id_operaciones = array();
           }   
              $data['medidas']  = $this->catalogo->listado_medidas();
              $data['estatuss']  = $this->catalogo->listado_estatus(-1,-1,-1);
              $data['lotes']  = $this->catalogo->listado_lotes(-1,-1,'1');
              $data['productos'] = $this->catalogo->listado_productos_unico();
              $data['almacenes']   = $this->modelo->coger_catalogo_almacenes(2);

          switch ($id_perfil) {    
            case 1:          

                        $this->load->view( 'reportes/costo_inventario/costo_inventario',$data );
              break;
            case 2:
            case 3:
            case 4:
                  if  (in_array(9, $coleccion_id_operaciones))  {                 
                            $this->load->view( 'reportes/costo_inventario/costo_inventario',$data );
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


public function procesando_costo_inventario(){ //13=>$row->num_partida,
		$data=$_POST;
		$busqueda = $this->modelo_costo_inventario->buscador_entrada_home($data); //13 443
		echo $busqueda;
	}




/////////////////validaciones/////////////////////////////////////////	


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
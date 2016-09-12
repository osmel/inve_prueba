<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Ctasxpagar extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('model_pedido', 'modelo_pedido');
		$this->load->model('modelo_reportes', 'modelo_reportes');  
		$this->load->model('modelo_costo_inventario', 'modelo_costo_inventario');  
		$this->load->model('modelo_ctasxpagar', 'modelo_ctasxpagar');  
		
		
	    $this->load->model('catalogo', 'catalogo');  
	    $this->load->model('modelo', 'modelo');  

		$this->load->library(array('email')); 
		$this->load->library('Jquery_pagination');//-->la estrella del equipo	
	}


 	function nuevo_pago($movimiento){
    if($this->session->userdata('session') === TRUE ){
      $id_perfil=$this->session->userdata('id_perfil');
      $data['movimiento']= base64_decode($movimiento);

      $coleccion_id_operaciones= json_decode($this->session->userdata('coleccion_id_operaciones')); 
      if ( (count($coleccion_id_operaciones)==0) || (!($coleccion_id_operaciones)) ) {
            $coleccion_id_operaciones = array();
       }   

       $data['doc_pagos'] =  $this->catalogo->listado_documentos_pagos();
       $data['retorno']='procesar_ctasxpagar/'.base64_encode($data["movimiento"]).'/'.base64_encode("listado_ctasxpagar"); 

      switch ($id_perfil) {    
        case 1:
            $this->load->view( 'ctasxpagar/nuevo_pago',$data);
          break;
        case 2:
        case 3:
        case 4:
             if  ( (in_array(8, $coleccion_id_operaciones))  || (in_array(20, $coleccion_id_operaciones))  )   { 
                $this->load->view( 'ctasxpagar/nuevo_pago',$data);
              }   
          break;


        default:  
          redirect('');
          break;
      }
    }
    else{ 
      redirect('index');
    }
  }	




  function validacion_nuevo_ctasxpagar(){
    if ($this->session->userdata('session') !== TRUE) {
      redirect('');
    } else {
      
      $this->form_validation->set_rules( 'instrumento_pago', ' Referencia', 'trim|required|min_length[3]|max_lenght[6]|xss_clean');
	  $this->form_validation->set_rules( 'comentario', 'Comentario', 'trim|min_length[3]|max_lenght[180]|xss_clean');             
      $this->form_validation->set_rules( 'importe', 'importe', 'required|callback_importe_valido|xss_clean');   
      $this->form_validation->set_rules('fecha_pago', 'fecha', 'callback_valid_date|xss_clean');

      //$this->form_validation->set_rules( 'minimo', 'Minimo',  'required|callback_valid_cero|xss_clean');   


      if ($this->form_validation->run() === TRUE){
          $data['id_documento_pago']   = $this->input->post('id_documento_pago');
          $data['instrumento_pago']   = $this->input->post('instrumento_pago');
          $data['importe']   = $this->input->post('importe');
          $data['comentario']   = $this->input->post('comentario');
          $data['movimiento']   = $this->input->post('movimiento');
          $data['fecha_pago']   = date('Y-m-d',strtotime($this->input->post('fecha_pago')));


          

          $data         =   $this->security->xss_clean($data);  
          $guardar            = $this->modelo_ctasxpagar->anadir_pago( $data );
          if ( $guardar !== FALSE ){
            echo true;
          } else {
            echo '<span class="error"><b>E01</b> - El nuevo pago no pudo ser agregado</span>';
          }
      } else {      
        echo validation_errors('<span class="error">','</span>');
      }
    }
  }



  function editar_pago_realizado( $id = '',$movimiento = '' ){
    if($this->session->userdata('session') === TRUE ){
      $id_perfil=$this->session->userdata('id_perfil');

      $coleccion_id_operaciones= json_decode($this->session->userdata('coleccion_id_operaciones')); 
      if ( (count($coleccion_id_operaciones)==0) || (!($coleccion_id_operaciones)) ) {
            $coleccion_id_operaciones = array();
       }   

      $data['id']  =  base64_decode($id);
      

      $dato['id'] = 6;
      $data['configuracion'] = $this->catalogo->coger_configuracion($dato); 
      $data['doc_pagos'] =  $this->catalogo->listado_documentos_pagos();
      $data['pago'] =  $this->modelo_ctasxpagar->editar_pago_realizado($data);
      $data['retorno']='procesar_ctasxpagar/'.$movimiento.'/'.base64_encode("listado_ctasxpagar"); 
      
      switch ($id_perfil) {    
        case 1:
                  $this->load->view( 'ctasxpagar/editar_pago', $data );
          break;
        case 2:
        case 3:
        case 4:
        	$this->load->view( 'ctasxpagar/editar_pago', $data );	
              /*
              if  ((in_array(8, $coleccion_id_operaciones))  || (in_array(11, $coleccion_id_operaciones)))  { 
                $data['producto']  = $this->catalogo->coger_producto($data);
                if ( $data['producto'] !== FALSE ){
                    $this->load->view( 'catalogos/productos/editar_producto', $data );
                } else {
                      redirect('');
                }       

             }  */ 
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




  function validacion_edicion_ctasxpagar(){
    if ($this->session->userdata('session') !== TRUE) {
      redirect('');
    } else {
      
      $this->form_validation->set_rules( 'instrumento_pago', ' Referencia', 'trim|required|min_length[3]|max_lenght[6]|xss_clean');
	  $this->form_validation->set_rules( 'comentario', 'Comentario', 'trim|min_length[3]|max_lenght[180]|xss_clean');             
      $this->form_validation->set_rules( 'importe', 'importe', 'required|callback_importe_valido|xss_clean');   
      $this->form_validation->set_rules('fecha_pago', 'fecha', 'callback_valid_date|xss_clean');

      

      if ($this->form_validation->run() === TRUE){
          $data['id_documento_pago']   = $this->input->post('id_documento_pago');
          $data['instrumento_pago']   = $this->input->post('instrumento_pago');
          $data['importe']   = $this->input->post('importe');
          $data['comentario']   = $this->input->post('comentario');
          $data['id']   = $this->input->post('id');
          $data['fecha_pago']   = date('Y-m-d',strtotime($this->input->post('fecha_pago')));

          $data         =   $this->security->xss_clean($data);  
          $guardar            = $this->modelo_ctasxpagar->editar_pago( $data );
          if ( $guardar !== FALSE ){
            echo true;
          } else {
            echo '<span class="error"><b>E01</b> - El nuevo pago no pudo ser agregado</span>';
          }
      } else {      
        echo validation_errors('<span class="error">','</span>');
      }
    }
  }




 function eliminar_pago($id = '', $instrumento_pago='',$movimiento){
      if($this->session->userdata('session') === TRUE ){
      $id_perfil=$this->session->userdata('id_perfil');

      $coleccion_id_operaciones= json_decode($this->session->userdata('coleccion_id_operaciones')); 
      if ( (count($coleccion_id_operaciones)==0) || (!($coleccion_id_operaciones)) ) {
            $coleccion_id_operaciones = array();
       }   

            $data['instrumento_pago']   = base64_decode($instrumento_pago);
            $data['id']   = base64_decode($id);
            $data['retorno']='procesar_ctasxpagar/'.$movimiento.'/'.base64_encode("listado_ctasxpagar"); 

      switch ($id_perfil) {    
        case 1:
            
            $this->load->view( 'ctasxpagar/eliminar_pago', $data );

          break;
        case 2:
        case 3:
        case 4:
                $this->load->view( 'ctasxpagar/eliminar_pago', $data );
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


  function validar_eliminar_pago(){
    if (!empty($_POST['id'])){ 
      $data['id'] = $_POST['id'];
    }
    $eliminado = $this->modelo_ctasxpagar->eliminar_pago(  $data );
    if ( $eliminado !== FALSE ){
      echo TRUE;
    } else {
      echo '<span class="error">No se ha podido eliminar el pago</span>';
    }
  }   




//////////////////////////////////////////////////////////	  //////////////////////////////////////////////////////////	  
  //////////////////////////////////////////////////////////	  //////////////////////////////////////////////////////////	  //////////////////////////////////////////////////////////	  //////////////////////////////////////////////////////////	  


	public function listado_ctasxpagar(){

		 if($this->session->userdata('session') === TRUE ){
		      $id_perfil=$this->session->userdata('id_perfil');

		      $coleccion_id_operaciones= json_decode($this->session->userdata('coleccion_id_operaciones')); 
		      if ( (count($coleccion_id_operaciones)==0) || (!($coleccion_id_operaciones)) ) {
		            $coleccion_id_operaciones = array();
		       }   

		      switch ($id_perfil) {    
		        case 1:          

		                    $this->load->view( 'ctasxpagar/ctasxpagar' );
		          break;
		        case 2:
		        case 3:
		        case 4:
		              if  (in_array(9, $coleccion_id_operaciones))  {                 
		                        $this->load->view( 'ctasxpagar/ctasxpagar' );
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

/////////////////////////////////////////////////////////////////////////////////////////////////
 public function procesando_ctas_vencidas(){

    $data=$_POST;
    

    $data['having'] = '(
                         ( monto_restante >0 ) OR ( monto_restante IS null )   
                      )';    
    $data["condicion"]=' AND (DATEDIFF( NOW( ) ,  m.fecha_entrada )-p.dias_ctas_pagar>0 ) 
    					AND (m.id_tipo_pago<>2 ) ';  // y no se ha pagado
	$busqueda  = $this->modelo_ctasxpagar->buscador_ctasxpagar($data);
    echo $busqueda;
  } 


 public function procesando_ctasxpagar(){

    $data=$_POST;
    
     $data['having'] = '(
                         ( monto_restante >0 ) OR ( monto_restante IS null )   
                      )';  
    $data["condicion"]=' AND (DATEDIFF( NOW( ) ,  fecha_entrada )-p.dias_ctas_pagar<=0 ) 
    					 AND (m.id_tipo_pago<>2 ) '; // y no se ha pagado
	$busqueda  = $this->modelo_ctasxpagar->buscador_ctasxpagar($data);
    echo $busqueda;
  } 


 public function procesando_ctas_pagadas(){

    $data=$_POST;
    
    //OR ( monto_restante NOT IS null )   
     $data['having'] = '(
                         ( monto_restante <=0 ) OR  ((monto_restante IS null) AND  (id_tipo_pago=2) )
                      )';  

    $data["condicion"]=' AND ((m.id_tipo_pago=2) or (m.id_tipo_pago<>2)) ';   //or ya esta pagado
	$busqueda  = $this->modelo_ctasxpagar->buscador_ctasxpagar($data);
    echo $busqueda;
  }   

/////////////////////////////////////////////////////////////////////////////////////////////////

	public function procesar_ctasxpagar($movimiento,$retorno){


		 if($this->session->userdata('session') === TRUE ){
		      $id_perfil=$this->session->userdata('id_perfil');
		      $data['movimiento'] = base64_decode($movimiento);
		      $data['retorno'] = base64_decode($retorno);

		      $coleccion_id_operaciones= json_decode($this->session->userdata('coleccion_id_operaciones')); 
		      if ( (count($coleccion_id_operaciones)==0) || (!($coleccion_id_operaciones)) ) {
		            $coleccion_id_operaciones = array();
		       }   

		      $dato['id'] = 6;
    		  $data['configuracion'] = $this->catalogo->coger_configuracion($dato); 
		      switch ($id_perfil) {    
		        case 1:          

		                    $this->load->view( 'ctasxpagar/detalle_ctasxpagar',$data );
		          break;
		        case 2:
		        case 3:
		        case 4:
		              if  (in_array(9, $coleccion_id_operaciones))  {                 
		                        $this->load->view( 'ctasxpagar/detalle_ctasxpagar',$data );
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




 public function procesando_pagos_realizados(){

    $data=$_POST;
 	$dato['id'] = 6;
    $data['configuracion'] = $this->catalogo->coger_configuracion($dato);    
    //print_r(expression)
    
    
	$busqueda  = $this->modelo_ctasxpagar->buscador_pagosrealizados($data);
    echo $busqueda;
  }   




  
    

    public function exportar_ctasxpagar() {
         $this->load->library('export');

        $extra_search = ($this->input->post('extra_search'));
        $data=$_POST;
         $nombre_completo=$this->session->userdata('nombre_completo');
        switch($extra_search) {
        	case "vencidas":
				    $data['having'] = '(
				                         ( monto_restante >0 ) OR ( monto_restante IS null )   
				                      )';    
				    $data["condicion"]=' AND (DATEDIFF( NOW( ) ,  m.fecha_entrada )-p.dias_ctas_pagar>0 ) 
				    					AND (m.id_tipo_pago<>2 ) ';  // y no se ha pagado
        		break;
            case "xpagar":
                /*
                
                $data['totales'] = $this->informes_model->totales_entrada_devolucion($data);        
                $html = $this->load->view('pdfs/informes/entrada', $data, true);
                */

				    $data['having'] = '(
				                         ( monto_restante >0 ) OR ( monto_restante IS null )   
				                      )';  
				    $data["condicion"]=' AND (DATEDIFF( NOW( ) ,  fecha_entrada )-p.dias_ctas_pagar<=0 ) 
				    					 AND (m.id_tipo_pago<>2 ) '; // y no se ha pagado

                 
                break;
            case "pagadas":    
					 $data['having'] = '(
					                         ( monto_restante <=0 ) OR  ((monto_restante IS null) AND  (id_tipo_pago=2) )
					                      )';  
					    $data["condicion"]=' AND ((m.id_tipo_pago=2) or (m.id_tipo_pago<>2)) '; 

            	break;
         

            default:
        }



        $data['movimientos'] = $this->modelo_ctasxpagar->exportar_ctasxpagar($data);
    	//print_r($data['movimientos']) ;
    	//die;

        if ($data['movimientos']) {
            $this->export->to_excel($data['movimientos'], 'reporte_ctas_'.date("Y-m-d_H-i-s").'-'.$nombre_completo);
        }    


    }	




    public function impresion_ctasxpagar() {
        
        $extra_search = ($this->input->post('extra_search'));
        $data=$_POST;

        switch($extra_search) {
        	case "vencidas":
				    $data['having'] = '(
				                         ( monto_restante >0 ) OR ( monto_restante IS null )   
				                      )';    
				    $data["condicion"]=' AND (DATEDIFF( NOW( ) ,  m.fecha_entrada )-p.dias_ctas_pagar>0 ) 
				    					AND (m.id_tipo_pago<>2 ) ';  // y no se ha pagado
        		break;
            case "xpagar":
                /*
                
                $data['totales'] = $this->informes_model->totales_entrada_devolucion($data);        
                $html = $this->load->view('pdfs/informes/entrada', $data, true);
                */

				    $data['having'] = '(
				                         ( monto_restante >0 ) OR ( monto_restante IS null )   
				                      )';  
				    $data["condicion"]=' AND (DATEDIFF( NOW( ) ,  fecha_entrada )-p.dias_ctas_pagar<=0 ) 
				    					 AND (m.id_tipo_pago<>2 ) '; // y no se ha pagado

                 
                break;
            case "pagadas":    
					 $data['having'] = '(
					                         ( monto_restante <=0 ) OR  ((monto_restante IS null) AND  (id_tipo_pago=2) )
					                      )';  
					    $data["condicion"]=' AND ((m.id_tipo_pago=2) or (m.id_tipo_pago<>2)) '; 

            	break;
         

            default:
        }



        $data['movimientos'] = $this->modelo_ctasxpagar->impresion_ctasxpagar($data);
        $html = $this->load->view('pdfs/ctasxpagar/'.$extra_search, $data, true);
    	//print_r($data['movimientos']) ;
    	//die;

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

        //http://www.tcpdf.org/fonts.php
        //$pdf->SetFont('freemono', '', 14, '', true);
        //$pdf->SetFont('freemono', '', 11, '', 'true');
        $pdf->SetFont('Times', '', 8,'','true');

 
        $pdf->setTextShadow(array('enabled' => true, 'depth_w' => 0.2, 'depth_h' => 0.2, 'color' => array(196, 196, 196), 'opacity' => 1, 'blend_mode' => 'Normal'));
 
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        $pdf->SetMargins(10, 10, 10,true);
        
        $pdf->SetAutoPageBreak(true, 10);

        $pdf->AddPage('P', array( 215.9,  279.4)); //en mm 21.59cm por 27.94cm



        
        $pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
        $nombre_archivo = utf8_decode("informe".$extra_search.".pdf");
        $pdf->Output($nombre_archivo, 'I');
    }










/////////////////validaciones/////////////////////////////////////////	




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
	

	function importe_valido( $str ){
		 
		if ((trim($str)=="") || (empty($str)) ) {
			$str = "";
			$regex = "/^$/";
		} else
		{
			//$regex =  '/^[-+]?(((\\\\d+)\\\\.?(\\\\d+)?)|\\\\.\\\\d+)([eE]?[+-]?\\\\d+)?$/'; 	
			$regex = "/^[+-]?(\d*\.?\d+([eE]?[+-]?\d+)?|\d+[eE][+-]?\d+)$/";
		}

		if ( ! preg_match( $regex, $str ) ){			
			$this->form_validation->set_message( 'importe_valido','<b class="">*</b> La información introducida en <b>%s</b> no es válida.' );
			return FALSE;
		} else {
			return TRUE;
		}
	}


	



	public function valid_email($str)
	{
		return ( ! preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $str)) ? FALSE : TRUE;
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



}

/* End of file nucleo.php */
/* Location: ./app/controllers/nucleo.php */
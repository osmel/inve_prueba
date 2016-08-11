<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Qr_code extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('model_pedido', 'modelo_pedido');
 		$this->load->helper("url");
	}

  	public function index(){
        //cargamos la librería    
        $this->load->library('ciqrcode');
        //hacemos configuraciones
        //$params['data'] = "Bruno es puto";
        $params['data'] = "probando scanner";
        $params['level'] = 'H';
        $params['size'] = 30;
        //decimos el directorio a guardar el codigo qr, en este 
        //caso una carpeta en la raíz llamada qr_code
        
        //FCPATH ->C:\xampp\htdocs\inventarios\
        //base_url() ->http://inventarios.dev.com/

        $params['savename'] = FCPATH.'qr_code/osmel/qrcode.png';
        //generamos el código qr
        $this->ciqrcode->generate($params);    
       

		$this->load->view('codigo_qr/lectura_codigo');
		//$this->load->view('codigo_qr/qr_mio');




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
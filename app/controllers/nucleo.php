<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Nucleo extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('model_pedido', 'modelo_pedido');
		$this->load->model('modelo', 'modelo');  
	}

	public function index(){
		
	}


	
	public function listado_reportes(){
		$data['plazas']	=        $this->modelo->coger_catalogo_plazas();
		$this->load->view( 'informes/reportes',$data );
	}



}

/* End of file nucleo.php */
/* Location: ./app/controllers/nucleo.php */
<?php if(! defined('BASEPATH')) exit('No tienes permiso para acceder a este archivo');

	class modelo_remoto extends CI_Model{
		
		private $key_hash;
		private $timezone;
    
		function __construct(){
			parent::__construct();
			
      //print_r('33333');


			$this->key_hash    = $_SERVER['HASH_ENCRYPT'];  //ojo
			$this->timezone    = 'UM1';
			
	    $this->db1 = array(
            //'dsn'       => '',
              'hostname' => 'localhost',
              'username' => 'root',
              'password' => 'root',
              'database' => 'bd_inventarios1',
              'dbdriver' => 'mysql',
              'dbprefix' => 'inven_',
              'pconnect' => TRUE,
              'db_debug' => TRUE,
              'cache_on' => FALSE,
              'cachedir' => '',
              'char_set' => 'utf8',
              'dbcollat' => 'utf8_general_ci',
              'swap_pre' => '',
              'autoinit' => true,
              'stricton' => FALSE,
          );


		}


      //editar  
    public function coger_catalogo_usuario(  ){

      $this->db1['database'] = 'bd_inventarios';
      $this->DB2 = $this->load->database( $this->db1,true);

      $this->usuarios    = $this->DB2->dbprefix('usuarios');    

      $this->DB2->select('id, nombre, apellidos');
      $result = $this->DB2->get($this->usuarios );

      if ($result->num_rows() > 0)
        return $result->row();
      else 
        return FALSE;
      $result->free_result();

    }  


   public function prueba() {

    print_r('222');

/*
        'failover' => array(),
        'save_queries' => TRUE
        'encrypt'  => FALSE,
        'compress' => FALSE,

$config['hostname'] = 'localhost';
$config['username'] = $this->session->userdata('username_database_saved');
$config['password'] =  $this->session->userdata('password_database_saved');
$config['database'] = 'mydatabase';
$config['dbdriver'] = 'mysqli';
$config['dbprefix'] = '';
$config['pconnect'] = FALSE;
$config['db_debug'] = TRUE;
$config['cache_on'] = FALSE;
$config['cachedir'] = '';
$config['char_set'] = 'utf8';
$config['dbcollat'] = 'utf8_general_ci';

$config['swap_pre'] = '';
$config['autoinit'] = TRUE;  //
$config['stricton'] = FALSE;

$this->load->database($config);
*/
   

   }    




	} 
?>
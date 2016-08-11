<?php if(! defined('BASEPATH')) exit('No tienes permiso para acceder a este archivo');

class Respaldo extends CI_Controller {
 
    function __construct()
    {
        //parent::Controller();
        parent::__construct();
        //$this->load->model('reportes', 'reporte');  
        $this->load->model('model_pedido', 'modelo_pedido');
      
    }
 

    function index(){

        $id_perfil=$this->session->userdata('id_perfil');
        switch ($id_perfil) {    
          case 1:
                    $this->load->view( 'respaldos/respaldo' );
                break;
                default:
                    redirect('');
                    break;
          }         
    }


    //esta llamada se realiza desde AJAX colaboradores dependientes de categoria
    //function exporto($categoria,$colaborador,$estatus,$fecha_inicial,$fecha_final){
    public function respaldar(){    


        if ( $this->session->userdata('session') !== TRUE ) {
            redirect('login');
        } else {

            $id_perfil=$this->session->userdata('id_perfil');


            switch ($id_perfil) {    
              case 1:
                        $this->load->dbutil();
                        $nom_bd= 'dev_impromed';
                        //Las preferencias de copia de seguridad son establecidas enviando un arreglo de valores como primer parámetro de la función "backup".

                        $prefs = array(
                                        
                                        'ignore'      => array(),           // Lista de tablas para omitir en la copia de seguridad
                                        'format'      => 'zip',             // gzip, zip, txt
                                        'filename'    => 'dev_impromed.sql',    // Nombre de archivo - NECESARIO SOLO CON ARCHIVOS ZIP
                                        'add_drop'    => TRUE,              // Agregar o no la sentencia DROP TABLE al archivo de respaldo
                                        'add_insert'  => TRUE,              // Agregar o no datos de INSERT al archivo de respaldo
                                        'newline'     => "\n"               // Caracter de nueva línea usado en el archivo de respaldo
                                      );

                        

                        if ($this->dbutil->database_exists($nom_bd))
                            {
                               
                                    // Crea una copia de seguridad de toda la base de datos y la asigna a una variable
                                    $copia_de_seguridad =& $this->dbutil->backup($prefs);          

                                    $nombre = 'respaldo_'.date('d').date('m').date('Y').'_'.random_string('alpha',4).random_string('numeric',3).'.zip';                                

                                    // Carga el asistente de archivos y escribe el archivo en su servidor
                                    $this->load->helper('file');
                                    write_file('./uploads/respaldos/'.$nombre, $copia_de_seguridad); 

                                    // Carga el asistente de descarga y envía el archivo a su escritorio
                                    /*
                                    $this->load->helper('download');
                                    force_download('copia_de_seguridad.gz', $copia_de_seguridad);          
                                     */   
                         
                                      echo TRUE;
                 
                            } else {
                                echo '<span class="error">No se realizar la salva de la base de datos</span>';
                            }
                        //redirect('usuarios');    

                    break;
                
            default:  
                redirect('');
              break;
          }


    }    
  
  } 
 
}
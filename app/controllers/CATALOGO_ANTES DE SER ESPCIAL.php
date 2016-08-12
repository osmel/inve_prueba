

//***********************almacenes **********************************//

  public function listado_almacenes(){

  if ( $this->session->userdata('session') !== TRUE ) {
      redirect('login');
    } else {
      $id_perfil=$this->session->userdata('id_perfil');

      $coleccion_id_operaciones= json_decode($this->session->userdata('coleccion_id_operaciones')); 
      if ( (count($coleccion_id_operaciones)==0) || (!($coleccion_id_operaciones)) ) {
            $coleccion_id_operaciones = array();
       }   

      switch ($id_perfil) {    
        case 1:

              ob_start();
              $this->paginacion_ajax_almacen(0);  //
              $initial_content = ob_get_contents();
              ob_end_clean();    
              $data['table'] = "<div id='paginacion'>" . $initial_content . "</div>" ;
              $this->load->view( 'paginacion/paginacion',$data);

            
          break;
        case 2:
        case 3:
             if  ( (in_array(8, $coleccion_id_operaciones))  || (in_array(20, $coleccion_id_operaciones))  )  { 
              ob_start();
              $this->paginacion_ajax_almacen(0); //
              $initial_content = ob_get_contents();
              ob_end_clean();    
              $data['table'] = "<div id='paginacion'>" . $initial_content . "</div>" ;
              $this->load->view( 'paginacion/paginacion',$data);

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

  public function paginacion_ajax_almacen($offset = 0)  {
        //hacemos la configuración de la librería jquery_pagination
        $config['base_url'] = base_url('catalogos/paginacion_ajax_almacen/');  //controlador/funcion

        $config['div'] = '#paginacion';//asignamos un id al contendor general
        $config['almacenr_class'] = 'page_link';//asignamos una clase a los links para maquetar
      $config['show_count'] = false;//en true queremos ver Viendo 1 a 10 de 52
        $config['per_page'] = 20;//-->número de almacenes por página
        $config['num_links'] = 4;//-->número de links visibles en el pie de la pagina

        $config['full_tag_open']       = '<ul class="pagination">';  
        $config['full_tag_close']      = '</ul>';
        $config['first_tag_open']      = '<li >'; 
        $config['first_tag_close']     = '</li>';
        $config['first_link']          = 'Primero'; 
        $config['last_tag_open']       = '<li >'; 
        $config['last_tag_close']      = '</li>';
        $config['last_link']           = 'Último';   
        $config['next_tag_open']       = '<li >'; 
        $config['next_tag_close']      = '</li>';
        $config['next_link']           = '&raquo;';  
        $config['prev_tag_open']       = '<li >'; 
        $config['prev_tag_close']      = '</li>';
        $config['prev_link']           = '&laquo;';   
        $config['num_tag_open']        = '<li>';
        $config['num_tag_close']       = '</li>';
        $config['cur_tag_open']        = '<li class="active"><a href="#">';
        $config['cur_tag_close']       = '</a></li>';   
        $config['total_rows'] = $this->catalogo->total_almacenes(); 
 
        //inicializamos la librería
        $this->jquery_pagination->initialize($config); 

    $data['almacenes']  = $this->catalogo->listado_almacenes($config['per_page'], $offset);

    $html = $this->load->view( 'catalogos/almacenes',$data ,true);

        $html = $html.
        '<div class="container">
      <div class="col-xs-12">
            <div id="paginacion">'.
              $this->jquery_pagination->create_links()
            .'</div>
        </div>
    </div>';
        echo $html;
 
    }   

    // crear
  function nuevo_almacen(){
    if($this->session->userdata('session') === TRUE ){
          $id_perfil=$this->session->userdata('id_perfil');

          $coleccion_id_operaciones= json_decode($this->session->userdata('coleccion_id_operaciones')); 
          if ( (count($coleccion_id_operaciones)==0) || (!($coleccion_id_operaciones)) ) {
                $coleccion_id_operaciones = array();
           }   

          switch ($id_perfil) {    
            case 1:
                $this->load->view( 'catalogos/almacenes/nuevo_almacen');
              break;
            case 2:
            case 3:
                 if  ( (in_array(8, $coleccion_id_operaciones))  || (in_array(20, $coleccion_id_operaciones))  )  { 
                    $this->load->view( 'catalogos/almacenes/nuevo_almacen');
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

  function validar_nuevo_almacen(){
    if ($this->session->userdata('session') !== TRUE) {
      redirect('');
    } else {
      $this->form_validation->set_rules('almacen', 'almacen', 'trim|required|min_length[3]|max_lenght[180]|xss_clean');
      if ($this->form_validation->run() === TRUE){
          $data['almacen']   = $this->input->post('almacen');
          $data         =   $this->security->xss_clean($data);  
          $guardar            = $this->catalogo->anadir_almacen( $data );
          if ( $guardar !== FALSE ){
            echo true;
          } else {
            echo '<span class="error"><b>E01</b> - La nueva  almacen no pudo ser agregada</span>';
          }
      } else {      
        echo validation_errors('<span class="error">','</span>');
      }
    }
  }


  // editar
  function editar_almacen( $id = '' ){

if($this->session->userdata('session') === TRUE ){
      $id_perfil=$this->session->userdata('id_perfil');

      $coleccion_id_operaciones= json_decode($this->session->userdata('coleccion_id_operaciones')); 
      if ( (count($coleccion_id_operaciones)==0) || (!($coleccion_id_operaciones)) ) {
            $coleccion_id_operaciones = array();
       }   

       $data['id']  = $id;

      switch ($id_perfil) {    
        case 1:

                $data['almacen'] = $this->catalogo->coger_almacen($data);
                if ( $data['almacen'] !== FALSE ){
                    $this->load->view( 'catalogos/almacenes/editar_almacen', $data );
                } else {
                      redirect('');
                }       

          break;
        case 2:
        case 3:
              if  ( (in_array(8, $coleccion_id_operaciones))  || (in_array(20, $coleccion_id_operaciones))  )  { 
                $data['almacen'] = $this->catalogo->coger_almacen($data);
                if ( $data['almacen'] !== FALSE ){
                    $this->load->view( 'catalogos/almacenes/editar_almacen', $data );
                } else {
                      redirect('');
                }       
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


function validacion_edicion_almacen(){
    if ($this->session->userdata('session') !== TRUE) {
      redirect('');
    } else {
      $this->form_validation->set_rules( 'almacen', 'almacen', 'trim|required|min_length[3]|max_lenght[180]|xss_clean');

      if ($this->form_validation->run() === TRUE){
            $data['id']           = $this->input->post('id');
          $data['almacen']         = $this->input->post('almacen');
          $data               = $this->security->xss_clean($data);  
          $guardar            = $this->catalogo->editar_almacen( $data );

          if ( $guardar !== FALSE ){
            echo true;

          } else {
            echo '<span class="error"><b>E01</b> - La nueva  almacen no pudo ser agregada</span>';
          }
      } else {      
        echo validation_errors('<span class="error">','</span>');
      }
    }
  }
  

  // eliminar


  function eliminar_almacen($id = '', $nombrecompleto=''){
      if($this->session->userdata('session') === TRUE ){
      $id_perfil=$this->session->userdata('id_perfil');

      $coleccion_id_operaciones= json_decode($this->session->userdata('coleccion_id_operaciones')); 
      if ( (count($coleccion_id_operaciones)==0) || (!($coleccion_id_operaciones)) ) {
            $coleccion_id_operaciones = array();
       }   

      $data['nombrecompleto']   = base64_decode($nombrecompleto);
      $data['id']         = $id;
      
      switch ($id_perfil) {    
        case 1:            
                    $this->load->view( 'catalogos/almacenes/eliminar_almacen', $data );
          break;
        case 2:
        case 3:
              if  ( (in_array(8, $coleccion_id_operaciones))  || (in_array(20, $coleccion_id_operaciones))  )  {                 
                        $this->load->view( 'catalogos/almacenes/eliminar_almacen', $data );
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


  function validar_eliminar_almacen(){
    if (!empty($_POST['id'])){ 
      $data['id'] = $_POST['id'];
    }
    $eliminado = $this->catalogo->eliminar_almacen(  $data );
    if ( $eliminado !== FALSE ){
      echo TRUE;
    } else {
      echo '<span class="error">No se ha podido eliminar la almacen</span>';
    }
  }   


//***********************tipos_facturas **********************************//

  public function listado_tipos_facturas(){

  if ( $this->session->userdata('session') !== TRUE ) {
      redirect('login');
    } else {
      $id_perfil=$this->session->userdata('id_perfil');

      $coleccion_id_operaciones= json_decode($this->session->userdata('coleccion_id_operaciones')); 
      if ( (count($coleccion_id_operaciones)==0) || (!($coleccion_id_operaciones)) ) {
            $coleccion_id_operaciones = array();
       }   

      switch ($id_perfil) {    
        case 1:

              ob_start();
              $this->paginacion_ajax_tipo_factura(0);  //
              $initial_content = ob_get_contents();
              ob_end_clean();    
              $data['table'] = "<div id='paginacion'>" . $initial_content . "</div>" ;
              $this->load->view( 'paginacion/paginacion',$data);

            
          break;
        case 2:
        case 3:
             if  ( (in_array(8, $coleccion_id_operaciones))  || (in_array(21, $coleccion_id_operaciones))  )  { 
              ob_start();
              $this->paginacion_ajax_tipo_factura(0); //
              $initial_content = ob_get_contents();
              ob_end_clean();    
              $data['table'] = "<div id='paginacion'>" . $initial_content . "</div>" ;
              $this->load->view( 'paginacion/paginacion',$data);

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

  public function paginacion_ajax_tipo_factura($offset = 0)  {
        //hacemos la configuración de la librería jquery_pagination
        $config['base_url'] = base_url('catalogos/paginacion_ajax_tipo_factura/');  //controlador/funcion

        $config['div'] = '#paginacion';//asignamos un id al contendor general
        $config['tipo_facturar_class'] = 'page_link';//asignamos una clase a los links para maquetar
      $config['show_count'] = false;//en true queremos ver Viendo 1 a 10 de 52
        $config['per_page'] = 20;//-->número de tipos_facturas por página
        $config['num_links'] = 4;//-->número de links visibles en el pie de la pagina

        $config['full_tag_open']       = '<ul class="pagination">';  
        $config['full_tag_close']      = '</ul>';
        $config['first_tag_open']      = '<li >'; 
        $config['first_tag_close']     = '</li>';
        $config['first_link']          = 'Primero'; 
        $config['last_tag_open']       = '<li >'; 
        $config['last_tag_close']      = '</li>';
        $config['last_link']           = 'Último';   
        $config['next_tag_open']       = '<li >'; 
        $config['next_tag_close']      = '</li>';
        $config['next_link']           = '&raquo;';  
        $config['prev_tag_open']       = '<li >'; 
        $config['prev_tag_close']      = '</li>';
        $config['prev_link']           = '&laquo;';   
        $config['num_tag_open']        = '<li>';
        $config['num_tag_close']       = '</li>';
        $config['cur_tag_open']        = '<li class="active"><a href="#">';
        $config['cur_tag_close']       = '</a></li>';   
        $config['total_rows'] = $this->catalogo->total_tipos_facturas(); 
 
        //inicializamos la librería
        $this->jquery_pagination->initialize($config); 

    $data['tipos_facturas']  = $this->catalogo->listado_tipos_facturas($config['per_page'], $offset);

    $html = $this->load->view( 'catalogos/tipos_facturas',$data ,true);

        $html = $html.
        '<div class="container">
      <div class="col-xs-12">
            <div id="paginacion">'.
              $this->jquery_pagination->create_links()
            .'</div>
        </div>
    </div>';
        echo $html;
 
    }   

    // crear
  function nuevo_tipo_factura(){
    if($this->session->userdata('session') === TRUE ){
          $id_perfil=$this->session->userdata('id_perfil');

          $coleccion_id_operaciones= json_decode($this->session->userdata('coleccion_id_operaciones')); 
          if ( (count($coleccion_id_operaciones)==0) || (!($coleccion_id_operaciones)) ) {
                $coleccion_id_operaciones = array();
           }   

          switch ($id_perfil) {    
            case 1:
                $this->load->view( 'catalogos/tipos_facturas/nuevo_tipo_factura');
              break;
            case 2:
            case 3:
                 if  ( (in_array(8, $coleccion_id_operaciones))  || (in_array(21, $coleccion_id_operaciones))  )  { 
                    $this->load->view( 'catalogos/tipos_facturas/nuevo_tipo_factura');
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

  function validar_nuevo_tipo_factura(){
    if ($this->session->userdata('session') !== TRUE) {
      redirect('');
    } else {
      $this->form_validation->set_rules('tipo_factura', 'tipo_factura', 'trim|required|min_length[3]|max_lenght[180]|xss_clean');
      if ($this->form_validation->run() === TRUE){
          $data['tipo_factura']   = $this->input->post('tipo_factura');
          $data         =   $this->security->xss_clean($data);  
          $guardar            = $this->catalogo->anadir_tipo_factura( $data );
          if ( $guardar !== FALSE ){
            echo true;
          } else {
            echo '<span class="error"><b>E01</b> - La nueva  tipo_factura no pudo ser agregada</span>';
          }
      } else {      
        echo validation_errors('<span class="error">','</span>');
      }
    }
  }


  // editar
  function editar_tipo_factura( $id = '' ){

if($this->session->userdata('session') === TRUE ){
      $id_perfil=$this->session->userdata('id_perfil');

      $coleccion_id_operaciones= json_decode($this->session->userdata('coleccion_id_operaciones')); 
      if ( (count($coleccion_id_operaciones)==0) || (!($coleccion_id_operaciones)) ) {
            $coleccion_id_operaciones = array();
       }   

       $data['id']  = $id;

      switch ($id_perfil) {    
        case 1:

                $data['tipo_factura'] = $this->catalogo->coger_tipo_factura($data);
                if ( $data['tipo_factura'] !== FALSE ){
                    $this->load->view( 'catalogos/tipos_facturas/editar_tipo_factura', $data );
                } else {
                      redirect('');
                }       

          break;
        case 2:
        case 3:
              if  ( (in_array(8, $coleccion_id_operaciones))  || (in_array(21, $coleccion_id_operaciones))  )  { 
                $data['tipo_factura'] = $this->catalogo->coger_tipo_factura($data);
                if ( $data['tipo_factura'] !== FALSE ){
                    $this->load->view( 'catalogos/tipos_facturas/editar_tipo_factura', $data );
                } else {
                      redirect('');
                }       
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


function validacion_edicion_tipo_factura(){
    if ($this->session->userdata('session') !== TRUE) {
      redirect('');
    } else {
      $this->form_validation->set_rules( 'tipo_factura', 'tipo_factura', 'trim|required|min_length[3]|max_lenght[180]|xss_clean');

      if ($this->form_validation->run() === TRUE){
            $data['id']           = $this->input->post('id');
          $data['tipo_factura']         = $this->input->post('tipo_factura');
          $data               = $this->security->xss_clean($data);  
          $guardar            = $this->catalogo->editar_tipo_factura( $data );

          if ( $guardar !== FALSE ){
            echo true;

          } else {
            echo '<span class="error"><b>E01</b> - La nueva  tipo_factura no pudo ser agregada</span>';
          }
      } else {      
        echo validation_errors('<span class="error">','</span>');
      }
    }
  }
  

  // eliminar


  function eliminar_tipo_factura($id = '', $nombrecompleto=''){
      if($this->session->userdata('session') === TRUE ){
      $id_perfil=$this->session->userdata('id_perfil');

      $coleccion_id_operaciones= json_decode($this->session->userdata('coleccion_id_operaciones')); 
      if ( (count($coleccion_id_operaciones)==0) || (!($coleccion_id_operaciones)) ) {
            $coleccion_id_operaciones = array();
       }   

      $data['nombrecompleto']   = base64_decode($nombrecompleto);
      $data['id']         = $id;
      
      switch ($id_perfil) {    
        case 1:            
                    $this->load->view( 'catalogos/tipos_facturas/eliminar_tipo_factura', $data );
          break;
        case 2:
        case 3:
              if  ( (in_array(8, $coleccion_id_operaciones))  || (in_array(21, $coleccion_id_operaciones))  )  {                 
                        $this->load->view( 'catalogos/tipos_facturas/eliminar_tipo_factura', $data );
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


  function validar_eliminar_tipo_factura(){
    if (!empty($_POST['id'])){ 
      $data['id'] = $_POST['id'];
    }
    $eliminado = $this->catalogo->eliminar_tipo_factura(  $data );
    if ( $eliminado !== FALSE ){
      echo TRUE;
    } else {
      echo '<span class="error">No se ha podido eliminar la tipo_factura</span>';
    }
  }   


  
//***********************tipos_pedidos **********************************//

  public function listado_tipos_pedidos(){

  if ( $this->session->userdata('session') !== TRUE ) {
      redirect('login');
    } else {
      $id_perfil=$this->session->userdata('id_perfil');

      $coleccion_id_operaciones= json_decode($this->session->userdata('coleccion_id_operaciones')); 
      if ( (count($coleccion_id_operaciones)==0) || (!($coleccion_id_operaciones)) ) {
            $coleccion_id_operaciones = array();
       }   

      switch ($id_perfil) {    
        case 1:

              ob_start();
              $this->paginacion_ajax_tipo_pedido(0);  //
              $initial_content = ob_get_contents();
              ob_end_clean();    
              $data['table'] = "<div id='paginacion'>" . $initial_content . "</div>" ;
              $this->load->view( 'paginacion/paginacion',$data);

            
          break;
        case 2:
        case 3:
             if  ( (in_array(8, $coleccion_id_operaciones))  || (in_array(22, $coleccion_id_operaciones))  )  { 
              ob_start();
              $this->paginacion_ajax_tipo_pedido(0); //
              $initial_content = ob_get_contents();
              ob_end_clean();    
              $data['table'] = "<div id='paginacion'>" . $initial_content . "</div>" ;
              $this->load->view( 'paginacion/paginacion',$data);

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

  public function paginacion_ajax_tipo_pedido($offset = 0)  {
        //hacemos la configuración de la librería jquery_pagination
        $config['base_url'] = base_url('catalogos/paginacion_ajax_tipo_pedido/');  //controlador/funcion

        $config['div'] = '#paginacion';//asignamos un id al contendor general
        $config['tipo_pedidor_class'] = 'page_link';//asignamos una clase a los links para maquetar
      $config['show_count'] = false;//en true queremos ver Viendo 1 a 10 de 52
        $config['per_page'] = 20;//-->número de tipos_pedidos por página
        $config['num_links'] = 4;//-->número de links visibles en el pie de la pagina

        $config['full_tag_open']       = '<ul class="pagination">';  
        $config['full_tag_close']      = '</ul>';
        $config['first_tag_open']      = '<li >'; 
        $config['first_tag_close']     = '</li>';
        $config['first_link']          = 'Primero'; 
        $config['last_tag_open']       = '<li >'; 
        $config['last_tag_close']      = '</li>';
        $config['last_link']           = 'Último';   
        $config['next_tag_open']       = '<li >'; 
        $config['next_tag_close']      = '</li>';
        $config['next_link']           = '&raquo;';  
        $config['prev_tag_open']       = '<li >'; 
        $config['prev_tag_close']      = '</li>';
        $config['prev_link']           = '&laquo;';   
        $config['num_tag_open']        = '<li>';
        $config['num_tag_close']       = '</li>';
        $config['cur_tag_open']        = '<li class="active"><a href="#">';
        $config['cur_tag_close']       = '</a></li>';   
        $config['total_rows'] = $this->catalogo->total_tipos_pedidos(); 
 
        //inicializamos la librería
        $this->jquery_pagination->initialize($config); 

    $data['tipos_pedidos']  = $this->catalogo->listado_tipos_pedidos($config['per_page'], $offset);

    $html = $this->load->view( 'catalogos/tipos_pedidos',$data ,true);

        $html = $html.
        '<div class="container">
      <div class="col-xs-12">
            <div id="paginacion">'.
              $this->jquery_pagination->create_links()
            .'</div>
        </div>
    </div>';
        echo $html;
 
    }   

    // crear
  function nuevo_tipo_pedido(){
    if($this->session->userdata('session') === TRUE ){
          $id_perfil=$this->session->userdata('id_perfil');

          $coleccion_id_operaciones= json_decode($this->session->userdata('coleccion_id_operaciones')); 
          if ( (count($coleccion_id_operaciones)==0) || (!($coleccion_id_operaciones)) ) {
                $coleccion_id_operaciones = array();
           }   

          switch ($id_perfil) {    
            case 1:
                $this->load->view( 'catalogos/tipos_pedidos/nuevo_tipo_pedido');
              break;
            case 2:
            case 3:
                 if  ( (in_array(8, $coleccion_id_operaciones))  || (in_array(22, $coleccion_id_operaciones))  )  { 
                    $this->load->view( 'catalogos/tipos_pedidos/nuevo_tipo_pedido');
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

  function validar_nuevo_tipo_pedido(){
    if ($this->session->userdata('session') !== TRUE) {
      redirect('');
    } else {
      $this->form_validation->set_rules('tipo_pedido', 'tipo_pedido', 'trim|required|min_length[3]|max_lenght[180]|xss_clean');
      if ($this->form_validation->run() === TRUE){
          $data['tipo_pedido']   = $this->input->post('tipo_pedido');
          $data         =   $this->security->xss_clean($data);  
          $guardar            = $this->catalogo->anadir_tipo_pedido( $data );
          if ( $guardar !== FALSE ){
            echo true;
          } else {
            echo '<span class="error"><b>E01</b> - La nueva  tipo_pedido no pudo ser agregada</span>';
          }
      } else {      
        echo validation_errors('<span class="error">','</span>');
      }
    }
  }


  // editar
  function editar_tipo_pedido( $id = '' ){

if($this->session->userdata('session') === TRUE ){
      $id_perfil=$this->session->userdata('id_perfil');

      $coleccion_id_operaciones= json_decode($this->session->userdata('coleccion_id_operaciones')); 
      if ( (count($coleccion_id_operaciones)==0) || (!($coleccion_id_operaciones)) ) {
            $coleccion_id_operaciones = array();
       }   

       $data['id']  = $id;

      switch ($id_perfil) {    
        case 1:

                $data['tipo_pedido'] = $this->catalogo->coger_tipo_pedido($data);
                if ( $data['tipo_pedido'] !== FALSE ){
                    $this->load->view( 'catalogos/tipos_pedidos/editar_tipo_pedido', $data );
                } else {
                      redirect('');
                }       

          break;
        case 2:
        case 3:
              if  ( (in_array(8, $coleccion_id_operaciones))  || (in_array(22, $coleccion_id_operaciones))  )  { 
                $data['tipo_pedido'] = $this->catalogo->coger_tipo_pedido($data);
                if ( $data['tipo_pedido'] !== FALSE ){
                    $this->load->view( 'catalogos/tipos_pedidos/editar_tipo_pedido', $data );
                } else {
                      redirect('');
                }       
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


function validacion_edicion_tipo_pedido(){
    if ($this->session->userdata('session') !== TRUE) {
      redirect('');
    } else {
      $this->form_validation->set_rules( 'tipo_pedido', 'tipo_pedido', 'trim|required|min_length[3]|max_lenght[180]|xss_clean');

      if ($this->form_validation->run() === TRUE){
            $data['id']           = $this->input->post('id');
          $data['tipo_pedido']         = $this->input->post('tipo_pedido');
          $data               = $this->security->xss_clean($data);  
          $guardar            = $this->catalogo->editar_tipo_pedido( $data );

          if ( $guardar !== FALSE ){
            echo true;

          } else {
            echo '<span class="error"><b>E01</b> - La nueva  tipo_pedido no pudo ser agregada</span>';
          }
      } else {      
        echo validation_errors('<span class="error">','</span>');
      }
    }
  }
  

  // eliminar


  function eliminar_tipo_pedido($id = '', $nombrecompleto=''){
      if($this->session->userdata('session') === TRUE ){
      $id_perfil=$this->session->userdata('id_perfil');

      $coleccion_id_operaciones= json_decode($this->session->userdata('coleccion_id_operaciones')); 
      if ( (count($coleccion_id_operaciones)==0) || (!($coleccion_id_operaciones)) ) {
            $coleccion_id_operaciones = array();
       }   

      $data['nombrecompleto']   = base64_decode($nombrecompleto);
      $data['id']         = $id;
      
      switch ($id_perfil) {    
        case 1:            
                    $this->load->view( 'catalogos/tipos_pedidos/eliminar_tipo_pedido', $data );
          break;
        case 2:
        case 3:
              if  ( (in_array(8, $coleccion_id_operaciones))  || (in_array(22, $coleccion_id_operaciones))  )  {                 
                        $this->load->view( 'catalogos/tipos_pedidos/eliminar_tipo_pedido', $data );
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


  function validar_eliminar_tipo_pedido(){
    if (!empty($_POST['id'])){ 
      $data['id'] = $_POST['id'];
    }
    $eliminado = $this->catalogo->eliminar_tipo_pedido(  $data );
    if ( $eliminado !== FALSE ){
      echo TRUE;
    } else {
      echo '<span class="error">No se ha podido eliminar la tipo_pedido</span>';
    }
  }   


  
//***********************tipos_ventas **********************************//

  public function listado_tipos_ventas(){

  if ( $this->session->userdata('session') !== TRUE ) {
      redirect('login');
    } else {
      $id_perfil=$this->session->userdata('id_perfil');

      $coleccion_id_operaciones= json_decode($this->session->userdata('coleccion_id_operaciones')); 
      if ( (count($coleccion_id_operaciones)==0) || (!($coleccion_id_operaciones)) ) {
            $coleccion_id_operaciones = array();
       }   

      switch ($id_perfil) {    
        case 1:

              ob_start();
              $this->paginacion_ajax_tipo_venta(0);  //
              $initial_content = ob_get_contents();
              ob_end_clean();    
              $data['table'] = "<div id='paginacion'>" . $initial_content . "</div>" ;
              $this->load->view( 'paginacion/paginacion',$data);

            
          break;
        case 2:
        case 3:
             if  ( (in_array(8, $coleccion_id_operaciones))  || (in_array(24, $coleccion_id_operaciones))  )  { 
              ob_start();
              $this->paginacion_ajax_tipo_venta(0); //
              $initial_content = ob_get_contents();
              ob_end_clean();    
              $data['table'] = "<div id='paginacion'>" . $initial_content . "</div>" ;
              $this->load->view( 'paginacion/paginacion',$data);

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

  public function paginacion_ajax_tipo_venta($offset = 0)  {
        //hacemos la configuración de la librería jquery_pagination
        $config['base_url'] = base_url('catalogos/paginacion_ajax_tipo_venta/');  //controlador/funcion

        $config['div'] = '#paginacion';//asignamos un id al contendor general
        $config['tipo_ventar_class'] = 'page_link';//asignamos una clase a los links para maquetar
      $config['show_count'] = false;//en true queremos ver Viendo 1 a 10 de 52
        $config['per_page'] = 20;//-->número de tipos_ventas por página
        $config['num_links'] = 4;//-->número de links visibles en el pie de la pagina

        $config['full_tag_open']       = '<ul class="pagination">';  
        $config['full_tag_close']      = '</ul>';
        $config['first_tag_open']      = '<li >'; 
        $config['first_tag_close']     = '</li>';
        $config['first_link']          = 'Primero'; 
        $config['last_tag_open']       = '<li >'; 
        $config['last_tag_close']      = '</li>';
        $config['last_link']           = 'Último';   
        $config['next_tag_open']       = '<li >'; 
        $config['next_tag_close']      = '</li>';
        $config['next_link']           = '&raquo;';  
        $config['prev_tag_open']       = '<li >'; 
        $config['prev_tag_close']      = '</li>';
        $config['prev_link']           = '&laquo;';   
        $config['num_tag_open']        = '<li>';
        $config['num_tag_close']       = '</li>';
        $config['cur_tag_open']        = '<li class="active"><a href="#">';
        $config['cur_tag_close']       = '</a></li>';   
        $config['total_rows'] = $this->catalogo->total_tipos_ventas(); 
 
        //inicializamos la librería
        $this->jquery_pagination->initialize($config); 

    $data['tipos_ventas']  = $this->catalogo->listado_tipos_ventas($config['per_page'], $offset);

    $html = $this->load->view( 'catalogos/tipos_ventas',$data ,true);

        $html = $html.
        '<div class="container">
      <div class="col-xs-12">
            <div id="paginacion">'.
              $this->jquery_pagination->create_links()
            .'</div>
        </div>
    </div>';
        echo $html;
 
    }   

    // crear
  function nuevo_tipo_venta(){
    if($this->session->userdata('session') === TRUE ){
          $id_perfil=$this->session->userdata('id_perfil');

          $coleccion_id_operaciones= json_decode($this->session->userdata('coleccion_id_operaciones')); 
          if ( (count($coleccion_id_operaciones)==0) || (!($coleccion_id_operaciones)) ) {
                $coleccion_id_operaciones = array();
           }   

          switch ($id_perfil) {    
            case 1:
                $this->load->view( 'catalogos/tipos_ventas/nuevo_tipo_venta');
              break;
            case 2:
            case 3:
                 if  ( (in_array(8, $coleccion_id_operaciones))  || (in_array(24, $coleccion_id_operaciones))  )  { 
                    $this->load->view( 'catalogos/tipos_ventas/nuevo_tipo_venta');
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

  function validar_nuevo_tipo_venta(){
    if ($this->session->userdata('session') !== TRUE) {
      redirect('');
    } else {
      $this->form_validation->set_rules('tipo_venta', 'tipo_venta', 'trim|required|min_length[3]|max_lenght[180]|xss_clean');
      if ($this->form_validation->run() === TRUE){
          $data['tipo_venta']   = $this->input->post('tipo_venta');
          $data         =   $this->security->xss_clean($data);  
          $guardar            = $this->catalogo->anadir_tipo_venta( $data );
          if ( $guardar !== FALSE ){
            echo true;
          } else {
            echo '<span class="error"><b>E01</b> - La nueva  tipo_venta no pudo ser agregada</span>';
          }
      } else {      
        echo validation_errors('<span class="error">','</span>');
      }
    }
  }


  // editar
  function editar_tipo_venta( $id = '' ){

if($this->session->userdata('session') === TRUE ){
      $id_perfil=$this->session->userdata('id_perfil');

      $coleccion_id_operaciones= json_decode($this->session->userdata('coleccion_id_operaciones')); 
      if ( (count($coleccion_id_operaciones)==0) || (!($coleccion_id_operaciones)) ) {
            $coleccion_id_operaciones = array();
       }   

       $data['id']  = $id;

      switch ($id_perfil) {    
        case 1:

                $data['tipo_venta'] = $this->catalogo->coger_tipo_venta($data);
                if ( $data['tipo_venta'] !== FALSE ){
                    $this->load->view( 'catalogos/tipos_ventas/editar_tipo_venta', $data );
                } else {
                      redirect('');
                }       

          break;
        case 2:
        case 3:
              if  ( (in_array(8, $coleccion_id_operaciones))  || (in_array(24, $coleccion_id_operaciones))  )  { 
                $data['tipo_venta'] = $this->catalogo->coger_tipo_venta($data);
                if ( $data['tipo_venta'] !== FALSE ){
                    $this->load->view( 'catalogos/tipos_ventas/editar_tipo_venta', $data );
                } else {
                      redirect('');
                }       
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


function validacion_edicion_tipo_venta(){
    if ($this->session->userdata('session') !== TRUE) {
      redirect('');
    } else {
      $this->form_validation->set_rules( 'tipo_venta', 'tipo_venta', 'trim|required|min_length[3]|max_lenght[180]|xss_clean');

      if ($this->form_validation->run() === TRUE){
            $data['id']           = $this->input->post('id');
          $data['tipo_venta']         = $this->input->post('tipo_venta');
          $data               = $this->security->xss_clean($data);  
          $guardar            = $this->catalogo->editar_tipo_venta( $data );

          if ( $guardar !== FALSE ){
            echo true;

          } else {
            echo '<span class="error"><b>E01</b> - La nueva  tipo_venta no pudo ser agregada</span>';
          }
      } else {      
        echo validation_errors('<span class="error">','</span>');
      }
    }
  }
  

  // eliminar


  function eliminar_tipo_venta($id = '', $nombrecompleto=''){
      if($this->session->userdata('session') === TRUE ){
      $id_perfil=$this->session->userdata('id_perfil');

      $coleccion_id_operaciones= json_decode($this->session->userdata('coleccion_id_operaciones')); 
      if ( (count($coleccion_id_operaciones)==0) || (!($coleccion_id_operaciones)) ) {
            $coleccion_id_operaciones = array();
       }   

      $data['nombrecompleto']   = base64_decode($nombrecompleto);
      $data['id']         = $id;
      
      switch ($id_perfil) {    
        case 1:            
                    $this->load->view( 'catalogos/tipos_ventas/eliminar_tipo_venta', $data );
          break;
        case 2:
        case 3:
              if  ( (in_array(8, $coleccion_id_operaciones))  || (in_array(24, $coleccion_id_operaciones))  )  {                 
                        $this->load->view( 'catalogos/tipos_ventas/eliminar_tipo_venta', $data );
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


  function validar_eliminar_tipo_venta(){
    if (!empty($_POST['id'])){ 
      $data['id'] = $_POST['id'];
    }
    $eliminado = $this->catalogo->eliminar_tipo_venta(  $data );
    if ( $eliminado !== FALSE ){
      echo TRUE;
    } else {
      echo '<span class="error">No se ha podido eliminar la tipo_venta</span>';
    }
  }   


  


//***********************cargadores **********************************//

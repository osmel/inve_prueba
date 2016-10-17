/////////////presentacion, filtro y paginador////////////	
	function dashboard() { 
    if($this->session->userdata('session') === TRUE ){
          $id_perfil=$this->session->userdata('id_perfil');

          $data['nodefinido_todavia']        = '';
          $data['estatuss']  = $this->catalogo->listado_estatus(-1,-1,-1);
          $data['productos'] = $this->catalogo->listado_productos_unico();
          $data['almacenes']   = $this->modelo->coger_catalogo_almacenes(2);
          $data['facturas']   = $this->catalogo->listado_tipos_facturas(-1,-1,'1');
          
		  $dato['id'] = 7;
		  $data['configuracion'] = $this->catalogo->coger_configuracion($dato); 
/*
		  if ($data['almacenes']==false) {
			

			  $this->session->set_userdata('perfil', 5);					
			  $this->session->set_userdata('id_perfil', 5);
			  $this->session->set_userdata('coleccion_id_operaciones', '');

		  	  
		  } else {
		  		//para el caso de los almacenistas
				$id_almacen = $this->session->userdata('id_almacen');
			  		$perfil = $this->session->userdata('perfil');
			  	 $id_perfil = $this->session->userdata('id_perfil');
			  	  $coleccion_id_operaciones = $this->session->userdata('coleccion_id_operaciones');

				//para el caso de los almaceneros	
				if ($id_perfil==2) {					  	

					  	  //lo convierte a un perfil 5
						  //$id_perfil = 5;
			  			  $this->session->set_userdata('perfil', 5);					
						  $this->session->set_userdata('id_perfil', 5);
						  $this->session->set_userdata('coleccion_id_operaciones', '');

						//si encuentra regresa sus estados normales
						foreach ( $data['almacenes'] as $almacen ) { 
							   if  ($almacen->id_almacen==$id_almacen) {

							   			  		$this->session->set_userdata('perfil', $perfil);					
											    $this->session->set_userdata('id_perfil', $id_perfil);
											    $this->session->set_userdata('coleccion_id_operaciones', $coleccion_id_operaciones);
							   }
					 	}
				}	 	


		  }
*/
		    	$id_perfil = $this->session->userdata('id_perfil');
		          switch ($id_perfil) {    
		            case 1:		            
		            case 4:
		                $this->load->view( 'principal/dashboard',$data );
		              break;

		            case 2:
		            	$data['id_almacen'] =  $this->session->userdata('id_almacen');
		              	$status_almacen  = $this->modelo->status_almacen($data);       
		              	if ($status_almacen->activo==1) {
		              		$this->load->view( 'principal/dashboard',$data );	
		              	} else {
		              		$this->login();
		              		//redirect('');		
		              		//$this->load->view( 'principal/dashboard',$data );	
		              	}
		              break;

		            case 3: //vendedor
		                $data['colores'] =  $this->catalogo->listado_colores(  );
		            	$data['estatuss']  = $this->catalogo->listado_estatus(-1,-1,'1');
		                $this->load->view( 'principal/inicio',$data );
		              break;
		            /*case 5: //conteo
		                $data['colores'] =  $this->catalogo->listado_colores(  );
		            	$data['estatuss']  = $this->catalogo->listado_estatus(-1,-1,'1');
		                $this->load->view( 'principal/conteo_fisico',$data );
		              break;	*/	

		            default:  
		              redirect('');
		              break;
		          }

        }
        else{ 
          redirect('');
        }	
	}

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php $this->load->view( 'header' ); ?>
<?php 
	  $perfil= $this->session->userdata('id_perfil'); 
	  $coleccion_id_operaciones= json_decode($this->session->userdata('coleccion_id_operaciones')); 

	  if ( (count($coleccion_id_operaciones)==0) || (!($coleccion_id_operaciones)) )  
	  		{
	  			$coleccion_id_operaciones = array();
	  		} 	



   $id_almacen=$this->session->userdata('id_almacen');


?>	



<div class="container margenes">
		<div class="panel panel-primary">
			<div class="panel-heading">Gestión de Traspasos</div>
			<div class="panel-body">




		<div class="notif-bot-tienda"></div>
		<div class="notif-bot-vendedor"> </div>


				
		<div class="container row">



		   <div class="col-md-5">

				<input type="hidden" id="mi_perfil" name="mi_perfil" value="<?php echo $this->session->userdata( 'id_perfil' ); ?>">

					
					    
							<label for="id_almacen_traspaso" class="col-sm-3 col-md-3 control-label">Almacén</label>
							<div class="col-sm-9 col-md-8">
							    <!--Los administradores o con permisos de entrada 
							    							****2121 sistema.js por ajax deshabilita sino hay en la regilla 
							    	que no sean almacenista 
							    	ENTONCES lista editable -->
							    <?php if (( ( $this->session->userdata( 'id_perfil' ) == 1  ) || (in_array(26, $coleccion_id_operaciones)) )  &&  (( $this->session->userdata( 'id_perfil' ) != 2 ) ) ){ ?>
									 <fieldset class="disabled_almacen">				
								<?php } else { ?>	
									 <fieldset class="disabled_almacen" disabled>
								<?php } ?>	
											<select name="id_almacen_traspaso" id="id_almacen_traspaso" class="form-control">
												
												<!--	<option value="0">Todos</option> -->
													<?php foreach ( $almacenes as $almacen ){ ?>
															<?php 
															   
																
																if  (($almacen->id_almacen==$id_almacen) )
																 {$seleccionado='selected';} else {$seleccionado='';}

																
															?>
																<option value="<?php echo $almacen->id_almacen; ?>" <?php echo $seleccionado; ?> ><?php echo $almacen->almacen; ?></option>
													<?php } ?>
												<!--rol de usuario -->
											</select>
								    </fieldset>

							</div>
					

		   </div>



		    <div class="col-md-7">

				<div class="col-md-4 ttip" title="Producto en espera de confirmación total del apartado."><span> Apartado Individual</span><div style="margin-right: 15px;float:left;background-color:#ab1d1d;width:15px;height:15px;"></div> </div>
				<div class="col-md-4 ttip" title="El apartado ha sido generado."><span> Apartado Confirmado</span><div style="margin-right: 15px;float:left;background-color:#f1a914;width:15px;height:15px;"></div></div>
				<div class="col-md-4 ttip" title="Indica que se puede procesar la salida del apartado."><span> Disponibilidad Salida</span><div style="margin-right: 15px;float:left;background-color:#14b80f;width:15px;height:15px;"></div></div>
			</div>			

			<hr/>

	
		<?php if ( ( $perfil != 3 ) ) { ?>		 
			<div class="table-responsive">
				<h4>Traspasos en proceso</h4>	
				<br>			
				<section>
					<table id="tabla_general_traspaso" class="display table table-striped table-bordered table-responsive " cellspacing="0" width="100%">

					</table>
				</section>
			</div>		

			<div class="table-responsive">
				<h4>Histórico de Traspasos</h4>	
				<br>			
				<section>
					<table id="tabla_traspaso_historico" class="display table table-striped table-bordered table-responsive" cellspacing="0" width="100%">

					</table>
				</section>
			</div>		


		<?php } ?>		

	
</div>
<hr>

<div class="row">
	<div class="col-sm-8 col-md-8"></div>

	<div class="col-sm-4 col-md-4">
		<a href="<?php echo base_url().'reportes'; ?>" type="button" class="btn btn-danger btn-block">Regresar</a>
	</div>	
	
</div>

</div>
</div>

<div class="modal fade bs-example-modal-lg" id="modalMessage" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content"></div>
	</div>
</div>	



<?php $this->load->view( 'footer' ); ?>
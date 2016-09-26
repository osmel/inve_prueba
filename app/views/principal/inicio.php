<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="notif-bot-pedidos" data-notify-html="title"></div>
<?php $this->load->view( 'header' ); ?>
<?php
	
	
   $coleccion_id_operaciones= json_decode($this->session->userdata('coleccion_id_operaciones')); 
   if ( (count($coleccion_id_operaciones)==0) || (!($coleccion_id_operaciones)) ) {
        $coleccion_id_operaciones = array();
   }  

	$id_almacen=$this->session->userdata('id_almacen');
	$config_almacen = $this->session->userdata( 'config_almacen' );
	$el_perfil = $this->session->userdata( 'id_perfil' );
?>
<style>

</style>


	<div class="container margenes" style="background-color: white !important;">
		

		<div class="row">
			
			<div class="col-sm-4 col-md-4" style="padding:15px 30px 30px 30px;">				
				<a  href="<?php echo base_url(); ?>procesar_apartados" type="button" class="btn btn-success btn-block">Procesar Apartados</a>
			</div>

			<!--status 				
			<div class="col-sm-0 col-md-3"></div>-->

	   <div class="col-xs-12 col-sm-6 col-md-2" <?php echo 'style="display:'.( (($config_almacen->activo==0)  ) ? 'none':'block').'"'; ?>>

				<input type="hidden" id="mi_perfil" name="mi_perfil" value="<?php echo $this->session->userdata( 'id_perfil' ); ?>">

					
					    <div class="form-group">
							<label for="id_almacen_inicio">Almacén</label>
							<div >
							    <!--Los administradores o con permisos de entrada 
							    							****2121 sistema.js por ajax deshabilita sino hay en la regilla 
							    	que no sean almacenista 
							    	ENTONCES lista editable -->
							    
									 <fieldset class="disabled_almacen">				
								
											<select name="id_almacen_inicio" id="id_almacen_inicio" class="form-control">
												<!--<option value="0">Selecciona una opción</option>-->
													<option value="0">Todos</option>
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
					

		   </div>	

		<div class="col-xs-12 col-sm-6 col-md-2" <?php echo 'style="display:'.( (($config_almacen->activo==0)  ) ? 'none':'block').'"'; ?>>
			<label for="descripcioon" class="col-sm-12 col-md-12">Filtro de Factura</label>
			<select name="id_factura_inicio" id="id_factura_inicio" class="form-control">
					<option value="0">Todos</option> 
					<?php foreach ( $facturas as $factura ){ ?>
								<option value="<?php echo $factura->id; ?>"><?php echo $factura->tipo_factura; ?></option>
					<?php } ?>
			</select>
		</div>		   			

			<div class="col-sm-4 col-md-2">
				<div class="form-group">
					<label for="descripcion" class="col-sm-12 col-md-12">Estatus</label>
					<div class="col-sm-12 col-md-12">
						<select name="id_estatus" id="id_estatus" class="form-control">
							    <option value="-1">Todos</option>
								<?php foreach ( $estatuss as $estatus ){ ?>
										<option value="<?php echo $estatus->id; ?>"><?php echo $estatus->estatus; ?></option>
								<?php } ?>
						</select>
					</div>
				</div>
			</div>	


			<!--colores -->
			<div class="col-sm-0 col-md-1"></div>

			<div class="col-xs-12 col-sm-4 col-md-2">
					<div class="form-group">
							<label for="descripcion" class="col-sm-12 col-md-12">Color</label>
							<div class="col-sm-12 col-md-12">
								<select name="id_color" id="id_color" class="form-control">
									    <option value="-1">Todos</option>
										<?php foreach ( $colores as $color ){ ?>
												<option value="<?php echo $color->id; ?>"><?php echo $color->color; ?></option>
										<?php } ?>
								</select>
							</div>
					</div>
			</div>		

		</div>		
		
		<!-- cuerpo todas las imagenes-->
		

		<div class="col-sm-12 col-md-12 control sin-margen " style="margin-bottom:10px">						
			<div class="container" style="padding:22px">




						<div class="table-responsive">
						 
							<section>
								<div class="ventana" style="overflow-y: auto">
									<table id="tabla_inicio" class="display table table-striped table-bordered table-responsive " cellspacing="0" width="100%">
										
										<!--      -->
								

									</table>	
								</div>
							</section>
						</div>		        	

		        
			</div>
		</div>

	</div>
	

<div class="modal fade bs-example-modal-lg" id="myModalInicio" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog" style="width:78% !important; margin-top:70px !important">
        <div class="modal-content" style="width:100% !important;"></div>
    </div>
</div>	


<?php $this->load->view( 'footer' ); ?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php $this->load->view('header'); ?>
<?php 

	
   $coleccion_id_operaciones= json_decode($this->session->userdata('coleccion_id_operaciones')); 
   if ( (count($coleccion_id_operaciones)==0) || (!($coleccion_id_operaciones)) ) {
        $coleccion_id_operaciones = array();
   }   

 	if (!isset($retorno)) {
      	$retorno ="";
    }

  $fecha_hoy = date('j-m-Y');

  
 $id_almacen=$this->session->userdata('id_almacen');


$attr = array('class' => 'form-horizontal', 'id'=>'form_entradas','name'=>$retorno,'method'=>'POST','autocomplete'=>'off','role'=>'form');
echo form_open('validar_agregar_producto', $attr);

	$config_almacen = $this->session->userdata( 'config_almacen' );
	$el_perfil = $this->session->userdata( 'id_perfil' );

//para determinar el consecutivo

if ($val_proveedor) {
	 $consecutivo_actual = ( ($val_proveedor->id_factura==1) ? $consecutivo->conse_ajuste_factura : $consecutivo->conse_ajuste_remision );
} else {
	$consecutivo_actual = $consecutivo->conse_ajuste_factura;
}	


?>		
<input type="hidden" id="conse_factura" name="conse_factura" value="<?php echo $consecutivo->conse_ajuste_factura+1; ?>">
<input type="hidden" id="conse_remision" name="conse_remision" value="<?php echo $consecutivo->conse_ajuste_remision+1; ?>">

<div class="container">

	<br>



	<div class="row">

		<h4 class="col-xs-12 col-sm-6 col-md-8">Registro de Entradas</h4>

		<input type="hidden" id="oculto_producto" name="oculto_producto" value="" color="" composicion="" calidad="">

		<div class="col-xs-6 col-sm-3 col-md-2">
			<fieldset disabled>
				<div class="form-group">
					<label for="fecha" class="col-sm-12 col-md-12 ttip" title="Campo informativo, no editable.">Fecha</label>
					<div class="col-sm-12 col-md-12">
						<input value="<?php echo $fecha_hoy; ?>"  type="text" class="form-control" id="fecha" name="fecha" placeholder="Fecha">

					</div>
				</div>
			</fieldset>	
		</div>

		<div class="col-xs-6 col-sm-3 col-md-2">
			<fieldset disabled>
				<div class="form-group">
					<label for="movimiento" class="col-sm-12 col-md-12 ttip" title="Campo informativo, no editable.">No. Movimiento</label>
					<div class="col-sm-12 col-md-12">
						<input type="text" title="Movimiento" value="<?php echo $consecutivo_actual+1; ?>" class="form-control" id="movimiento" name="movimiento" placeholder="No. Movimiento">
					</div>
				</div>
			</fieldset>			
		</div>
	</div>
	
	<div class="row">
		<div class="col-xs-12 col-sm-6 col-md-4">
			<?php if ($val_proveedor) { ?>
				<fieldset class="disabledme" disabled>							
			<?php } else { ?>
				<fieldset class="disabledme">						
			<?php } ?>

					<div class="form-group">
						<label for="descripcion" class="col-sm-12 col-md-12">Proveedor</label>
						


						<div class="col-sm-12 col-md-12">
								<?php if ($val_proveedor) { ?>
									
									<input value="<?php echo htmlspecialchars($val_proveedor->nombre); ?>" type="text" name="editar_proveedor" idproveedor="1" class="form-control buscar_proveedor ttip" title="Campo predictivo. Escriba y seleccione el nombre de un Proveedor." autocomplete="off" spellcheck="false" placeholder="Buscar Proveedor...">
								<?php } else { ?>
									<input  type="text" name="editar_proveedor" idproveedor="1" class="form-control buscar_proveedor ttip" title="Campo predictivo. Escriba y seleccione el nombre de un Proveedor." autocomplete="off" spellcheck="false" placeholder="Buscar Proveedor...">
								<?php } ?>
						</div>
					</div>
			
				</fieldset>							
			
		</div>

<!-- si configuracion lo tiene activo y es(administrador o por el contrario tiene "permiso de ver y editar") -->
		<?php if (($configuracion->activo==1)) {  ?> 

				<div class="col-xs-12 col-sm-6 col-md-2">
					<?php if ($val_proveedor) { ?>
						<fieldset class="disabledme" disabled>							
					<?php } else { ?>
						<fieldset class="disabledme">						
					<?php } ?>

						<div class="form-group">
							<label for="factura" class="col-sm-12 col-md-12">Factura/Remisión</label>
							<div class="col-sm-12 col-md-12">
										<?php if ($val_proveedor) { ?>
											<input value="<?php echo htmlspecialchars($val_proveedor->factura); ?>" type="text" class="form-control" id="factura" name="factura" placeholder="Factura">							
										<?php } else { ?>
											<input type="text" class="form-control" id="factura" name="factura" placeholder="Factura">
										<?php } ?>				
							</div>
						</div>
					
						</fieldset>							
				</div>
		<?php }  ?> 		


					<!--almacen Asociado -->
					<div class="col-xs-12 col-sm-6 col-md-2" <?php echo 'style="display:'.( (($config_almacen->activo==0) && ($el_perfil==2) ) ? 'none':'block').'"'; ?> >
					    
							<label for="id_almacen" class="col-sm-3 col-md-3 control-label">Almacén</label>
							<div class="col-sm-9 col-md-10">
							    <!--Los administradores o con permisos de entrada 
							    	Y que no este inhabilitado y 
							    	que no sean almacenista 
							    	ENTONCES lista editable -->
							    <?php if (( ( $this->session->userdata( 'id_perfil' ) == 1  ) || (in_array(1, $coleccion_id_operaciones)) ) && (!$val_proveedor) && (( $this->session->userdata( 'id_perfil' ) != 2 ) ) ){ ?>
									 <fieldset class="disabledme">				
								<?php } else { ?>	
									 <fieldset class="disabledme" disabled>
								<?php } ?>	
											<select name="id_almacen" id="id_almacen" class="form-control">
												<!--<option value="0">Selecciona una opción</option>-->
													<?php foreach ( $almacenes as $almacen ){ ?>
															<?php 
															   if  (($almacen->id_almacen==$id_almacen) && (!$val_proveedor))
																 {$seleccionado='selected';} else {$seleccionado='';}
																
																if ($val_proveedor) { //comprobar una vez que ya esten inhabilitados factura
																	 if ($almacen->id_almacen==$val_proveedor->id_almacen) {
																			$seleccionado='selected';
																		} else {
																			$seleccionado='';
																		}
																}
															?>
																<option value="<?php echo $almacen->id_almacen; ?>" <?php echo $seleccionado; ?> ><?php echo $almacen->almacen; ?></option>
													<?php } ?>
												<!--rol de usuario -->
											</select>
								    </fieldset>

							</div>
					</div>		

					


					<!--Tipos de factura -->
					<div class="col-xs-12 col-sm-6 col-md-2">
					    
							<label for="id_tipo_pago" class="col-sm-3 col-md-12">Tipo de Pago</label>
							<div class="col-sm-9 col-md-12">
							    <!--Los administradores o con permisos de entrada 
							    	Y que no este inhabilitado y 
							    	que no sean facturaista 
							    	ENTONCES lista editable -->

								<?php if ($val_proveedor) { ?>
									<fieldset class="disabledme" disabled>							
								<?php } else { ?>
									<fieldset class="disabledme">						
								<?php } ?>

											<select name="id_tipo_pago" id="id_tipo_pago" class="form-control">
												<!--<option value="0">Selecciona una opción</option>-->
													<?php foreach ( $pagos as $pago ){ ?>
															<?php 
																if ($val_proveedor) { //comprobar una vez que ya esten inhabilitados factura
																	 if ($pago->id==$val_proveedor->id_tipo_pago) {
																			$seleccionado='selected';
																		} else {
																			$seleccionado='';
																		}
																}
															?>
																<option value="<?php echo $pago->id; ?>" <?php echo $seleccionado; ?> ><?php echo $pago->tipo_pago; ?></option>
													<?php } ?>
												<!--rol de usuario -->
											</select>
								    </fieldset>

							</div>
					</div>		



					<!--Tipos de factura -->
					<div class="col-xs-12 col-sm-6 col-md-2">
					    
							<label for="id_factura" class="col-sm-3 col-md-12">Tipo de factura</label>
							<div class="col-sm-9 col-md-12">
							    <!--Los administradores o con permisos de entrada 
							    	Y que no este inhabilitado y 
							    	que no sean facturaista 
							    	ENTONCES lista editable -->

								<?php if ($val_proveedor) { ?>
									<fieldset class="disabledme" disabled>							
								<?php } else { ?>
									<fieldset class="disabledme">						
								<?php } ?>

											<select name="id_factura" id="id_factura" class="form-control">
												<!--<option value="0">Selecciona una opción</option>-->
													<?php foreach ( $facturas as $factura ){ ?>
															<?php 
																if ($val_proveedor) { //comprobar una vez que ya esten inhabilitados factura
																	 if ($factura->id==$val_proveedor->id_factura) {
																			$seleccionado='selected';
																		} else {
																			$seleccionado='';
																		}
																}
															?>
																<option value="<?php echo $factura->id; ?>" <?php echo $seleccionado; ?> ><?php echo $factura->tipo_factura; ?></option>
													<?php } ?>
												<!--rol de usuario -->
											</select>
								    </fieldset>

							</div>
					</div>		



				
				


	</div>


	




	<?php echo form_close(); ?>


			<div class="row">
				<div class="col-md-12">
					
					<h4>Listado de productos seleccionados para Entrada</h4>	
					<br>			
						<div class="notif-bot-vendedor"></div>
						<div class="table-responsive">	
								<section>
									
									<table id="tabla_entrada_sobrante"	class="display table table-striped table-bordered table-responsive" cellspacing="0" width="100%">
									</table>

								</section>
						</div>
				</div>
			</div>


				


			<br>
			<div class="row">

				<div class="col-sm-4 col-md-4"></div>
				<div class="col-sm-4 col-md-4 marginbuttom">
					<a href="<?php echo base_url(); ?><?php echo $retorno; ?>" type="button" class="btn btn-danger btn-block">Regresar</a>
				</div>

				<div class="col-sm-4 col-md-4">
					<button id="conf_entrada_sobrante"  type="button" class="btn btn-success btn-block">
						Procesar Entrada
					</button>
				</div>

			</div>
	<br>
	</div>	
</div>

<div class="modal fade bs-example-modal-lg" id="modalMessage" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
        <div class="modal-content"></div>
    </div>
</div>	

<?php $this->load->view('footer'); ?>
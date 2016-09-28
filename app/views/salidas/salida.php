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
?>	

<?php if (isset($val_proveedor->nombre)) { 
	 $mi_proveedor = htmlspecialchars($val_proveedor->nombre);
  } else {
  	 $mi_proveedor = '';
  }

 $id_almacen=$this->session->userdata('id_almacen');


	$config_almacen = $this->session->userdata( 'config_almacen' );
	$el_perfil = $this->session->userdata( 'id_perfil' );

    if ($val_proveedor) {

		$consecutivo_actual = (( ($val_proveedor->id_tipo_pedido == 1) && ($val_proveedor->id_tipo_factura==1) ) ? $consecutivo->conse_factura : $consecutivo->conse_remision );
		$consecutivo_actual = ( ($val_proveedor->id_tipo_pedido==2) ? $consecutivo->conse_surtido : $consecutivo_actual);
	} else {
		$consecutivo_actual = $consecutivo->conse_factura;
	}	



?>


<input type="hidden" id="conse_factura" name="conse_factura" value="<?php echo $consecutivo->conse_factura+1; ?>">
<input type="hidden" id="conse_remision" name="conse_remision" value="<?php echo $consecutivo->conse_remision+1; ?>">
<input type="hidden" id="conse_surtido" name="conse_surtido" value="<?php echo $consecutivo->conse_surtido+1; ?>">

<input type="hidden" id="id_proveedor" value="<?php echo $mi_proveedor; ?>">

<div class="container margenes">
<div class="panel panel-primary">
<div class="panel-heading">Registro de Salidas</div>
<div class="panel-body">				
<div class="row">
	<div class="col-xs-12 col-sm-6 col-md-2">
		<fieldset disabled>
		<div class="form-group">
			<label for="fecha" class="ttip" title="Campo informativo, no editable.">Fecha</label>
			<div>
				<input value="<?php echo $fecha_hoy; ?>"  type="text" class="form-control" id="fecha" name="fecha" placeholder="Fecha">
			</div>
		</div>
		</fieldset>	
	</div>
	<div class="col-xs-12 col-sm-6 col-md-2">
		<fieldset disabled>
			<div class="form-group">
				<label for="movimiento" class="ttip" title="Campo informativo, no editable.">No. Movimiento</label>
				<div>
					<input type="text" value="<?php echo $consecutivo_actual+1; ?>" class="form-control" id="movimiento" name="movimiento" placeholder="No. Movimiento">
				</div>
			</div>
		</fieldset>			
	</div>
	<div class="col-xs-12 col-sm-4 col-md-3">
		<?php if ($val_proveedor) { ?>
		<fieldset class="disabledme" disabled>							
		<?php } else { ?>
		<fieldset class="disabledme">						
		<?php } ?>
			<div class="form-group">
				<label for="descripcion">Cliente</label>
				<div class="input-group col-md-12 col-sm-12 col-xs-12">
					<?php if ($val_proveedor) { ?>
					<input identificador="" id="editar_proveedor" value="<?php echo htmlspecialchars($val_proveedor->nombre); ?>" type="text" name="editar_proveedor" idproveedor="2" class="buscar_proveedor form-control typeahead tt-query ttip" title="Campo predictivo. Comience a escribir el nombre de un cliente y seleccione una opción para mostrar los pedidos." autocomplete="off" spellcheck="false" placeholder="Buscar Cliente...">
					<?php } else { ?>
					<input identificador="" id="editar_proveedor" type="text" name="editar_proveedor" idproveedor="2" class="buscar_proveedor form-control typeahead tt-query ttip" title="Campo predictivo. Comience a escribir el nombre de un cliente y seleccione una opción para mostrar los pedidos." autocomplete="off" spellcheck="false" placeholder="Buscar Cliente...">
					<?php } ?>
				</div>
			</div>
		</fieldset>	
	</div>
	<div class="col-xs-12 col-sm-4 col-md-3">
		<?php if ($val_proveedor) { ?>
		<fieldset class="disabledme" disabled>							
		<?php } else { ?>
		<fieldset class="disabledme">						
		<?php } ?>
			<div class="form-group">
				<label for="descripcion">Cargador</label>
				<div class="input-group col-md-12 col-sm-12 col-xs-12">
					<?php if ($val_proveedor) { ?>
					<input value="<?php echo htmlspecialchars($val_proveedor->cargador); ?>" type="text" name="editar_cargador" class="buscar_cargador form-control typeahead tt-query ttip" title="Campo predictivo. Comience a escribir el nombre de un cargador y seleccione una opción para poder continuar." autocomplete="off" spellcheck="false" placeholder="Buscar Cargador...">
					<?php } else { ?>
					<input  type="text" name="editar_cargador" class="buscar_cargador form-control typeahead tt-query ttip" title="Campo predictivo. Comience a escribir el nombre de un cargador y seleccione una opción para poder continuar." autocomplete="off" spellcheck="false" placeholder="Buscar Cargador...">
					<?php } ?>
				</div>
			</div>
		</fieldset>	
	</div>
	<?php if (($configuracion->activo==1)) {  ?> 
		<div class="col-xs-12 col-sm-4 col-md-2">
			<?php if ($val_proveedor) { ?>
			<fieldset class="disabledme" disabled>							
			<?php } else { ?>
			<fieldset class="disabledme">						
			<?php } ?>
				<div class="form-group">
				<label for="factura">Factura/Remisión</label>
					<div>
						<?php if ($val_proveedor) { ?>
						<input value="<?php echo htmlspecialchars($val_proveedor->factura); ?>" type="text" class="form-control ttip" title="Introduzca un número de factura para continuar." id="factura" name="factura" placeholder="Factura">							
						<?php } else { ?>
						<input type="text" class="form-control ttip" title="Introduzca un número de factura para continuar." id="factura" name="factura" placeholder="Factura">
						<?php } ?>				
					</div>
				</div>
			</fieldset>	
		</div>
	<?php }  ?> 	
</div>

<div class="row">					


		<div class="notif-bot-pedidos"></div>
		
		<fieldset style="display:none;">
			<label for="factura" style="font-size:18px; color:red;">Filtros de Búsqueda</label>
		</fieldset>

		<fieldset style="display:none;">
              <div class="col-md-3">
                 <div class="form-group">
					
					<div >

                          <select class="col-sm-12 col-md-12 form-control ttip" title="Seleccione un producto para mostrar las salidas de ese producto." name="producto_filtro" id="producto_filtro" >
                            <option value="">Selecciona un producto</option>
                            <?php if($productos){ ?>
                              <?php foreach($productos as $producto){ ?>
                                <option value="<?php echo htmlspecialchars($producto->descripcion); ?>" ><?php echo htmlspecialchars($producto->descripcion); ?></option>
                              <?php } ?>
                            <?php } ?>
                          </select>
                    </div>  
                      
                 </div>
              </div>


             <div class="col-md-2">
                 <div class="form-group">
					<div >
                          <select class="col-sm-12 col-md-12 form-control ttip" title="Seleccione un color para agregar un filtro por color." name="color_filtro" id="color_filtro">
                            <option value="">Selecciona un color</option>
                            <?php if($colores){ ?>
                              <?php foreach($colores as $color){ ?>
                                <option style="background-color:#<?php echo $color->hexadecimal_color; ?>" value="<?php echo $color->id; ?>" ><?php echo $color->color; ?></option>
                              <?php } ?>
                            <?php } ?>
                          </select>
                    </div>  
                      
                 </div>
              </div>              



				<div class="col-md-3">
							<div class="form-group">
								<div>
									<input  type="text" name="editar_proveedor_filtro" id="editar_proveedor_filtro" idproveedor="1" class="form-control buscar_proveedor_filtra ttip" title="Campo predictivo. Comience a escribir el nombre de un proveedor y seleccione una opción para mostrar sus salidas." autocomplete="off" spellcheck="false" placeholder="Buscar Proveedor...">
								</div>
							</div>
					
				</div>


				<div class="col-md-2">
						<div class="form-group">
							<div>
								<input type="text" class="form-control" id="ancho_filtro" name="ancho_filtro" placeholder="Ancho">
							</div>
						</div>
				</div>


				<div class="col-md-2">
						<div class="form-group">
							<div>
								<input type="text" class="form-control" id="factura_filtro" name="factura_filtro" placeholder="Factura/Rem">
							</div>
						</div>
				</div>
			</fieldset>		


	

	


	<!--almacen Asociado -->
	<div class="col-xs-12 col-sm-6 col-md-3"  <?php echo 'style="display:'.( (($config_almacen->activo==0) && ($el_perfil==2) ) ? 'none':'block').'"'; ?>>
	    
			<label for="id_almacen" class="col-sm-3 col-md-3 control-label">Almacén</label>
			<div class="col-sm-9 col-md-10">
			    <!--Los administradores o con permisos de salida 
			    	Y que no este inhabilitado y 
			    	que no sean almacenista 
			    	ENTONCES lista editable -->
			    <?php if (( ( $this->session->userdata( 'id_perfil' ) == 1  ) || (in_array(2, $coleccion_id_operaciones) ) ) && (!$val_proveedor) &&  (( $this->session->userdata( 'id_perfil' ) != 2 ) )  && ($this->session->userdata('config_salida')==0)  ){ ?>
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








	<!--Tipos de pedidos -->
				<div class="col-xs-12 col-sm-6 col-md-2">
					    
							<label for="id_tipo_pedido_salida" class="col-sm-3 col-md-12">Tipo de pedido</label>
							<div class="col-sm-9 col-md-12">
							    <!--Los administradores o con permisos de entrada 
							    	Y que no este inhabilitado y 
							    	que no sean pedidoista 
							    	ENTONCES lista editable -->

								<?php if ($val_proveedor) { ?>
									<fieldset class="disabledme" disabled>							
								<?php } else { ?>
									<fieldset class="disabledme">						
								<?php } ?>

											<select name="id_tipo_pedido_salida" id="id_tipo_pedido_salida" class="form-control">
												<!--<option value="0">Selecciona una opción</option>-->
													<?php foreach ( $pedidos as $pedido ){ ?>
															<?php 
																if ($val_proveedor) { //comprobar una vez que ya esten inhabilitados pedido
																	 if ($pedido->id==$val_proveedor->id_tipo_pedido) {
																			$seleccionado='selected';
																		} else {
																			$seleccionado='';
																		}
																}
															?>
																<option value="<?php echo $pedido->id; ?>" <?php echo $seleccionado; ?> ><?php echo $pedido->tipo_pedido; ?></option>
													<?php } ?>
												<!--rol de usuario -->
											</select>
								    </fieldset>

							</div>
					</div>		



					

					<?php
					$mostrando ="display:block";
					 if (($val_proveedor) && ($val_proveedor->id_tipo_pedido==2)) { 

								$mostrando ="display:none";
							}
						?>



					<!--Tipos de factura -->
					<div class="col-xs-12 col-sm-6 col-md-2 tipo_factura" style="<?php echo $mostrando; ?>";>
					    
							<label for="id_tipo_factura_salida" class="col-sm-3 col-md-12">Tipo de factura</label>
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

											<select name="id_tipo_factura_salida" id="id_tipo_factura_salida" class="form-control">
												<!--<option value="0">Selecciona una opción</option>-->
													<?php foreach ( $facturas as $factura ){ ?>
															<?php 
																if ($val_proveedor) { //comprobar una vez que ya esten inhabilitados factura
																	 if ($factura->id==$val_proveedor->id_tipo_factura) {
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
















              <br/><br/>

	<div class="col-md-12">	





		<div class="table-responsive" > <!--style="overflow-x:initial !important;" -->
				<section>


					<table id="tabla_entrada" class="display table table-striped table-bordered table-responsive" cellspacing="0" width="100%">

					<thead>

					<tr>
						<th style="width:25%;">Código</th>
						<th style="width:15%;">Producto</th>
						<th style="width:15%;">Color</th>
						<th style="width:5%;">Cantidad</th>
						<th style="width:5%;">Ancho</th>
						<th style="width:5%;">No. Movimiento Entrada</th>			
						<th style="width:15%;">Proveedor</th>
						<th style="width:5%;">Lote</th>
						<th style="width:5%;">No. de Partida</th>
						<th style="width:15%;">Agregar</th>
					</tr>
					</thead>
					</table>
				</section>
		</div>			
	</div>
</div>

				<br/>
		
				<div class="row bloque_totales">						
					<div class="col-sm-0 col-md-4">	
					  
					</div>	
					<div class="col-sm-3 col-md-2">	
					  <b>Existencias por Página</b>
					</div>	

					<div class="col-sm-3 col-md-2">	
						<span id="pieza"></span>			
					</div>	
					<div class="col-sm-3 col-md-2">	
						<span id="metro"></span>			
					</div>	
					<div class="col-sm-3 col-md-2">	
						<span id="kg" ></span>				
					</div>	
				</div>			

				<div class="row bloque_totales">		
					<div class="col-sm-0 col-md-4">	
					  
					</div>	
					<div class="col-sm-3 col-md-2">	
					  <b>Existencias Totales</b>			
					</div>									
					<div class="col-sm-3 col-md-2">	
						<span id="total_pieza"></span>			
					</div>	
					<div class="col-sm-3 col-md-2">	
						<span id="total_metro"></span>			
					</div>	
					<div class="col-sm-3 col-md-2">	
						<span id="total_kg" ></span>				
					</div>	
				</div>


<div class="row">					
	<div class="col-md-12">		
		
		<h4>Orden de salida</h4>	
		<br>	
		<div class="table-responsive">
			<section>
				<table id="tabla_salida" class="display table table-striped table-responsive" cellspacing="0" width="100%">
					<thead>
						<tr>
							<th style="width:20%;">Código</th>
							<th style="width:15%;">Producto</th>
							<th style="width:15%;">Color</th>
							<th style="width:5%;">Cantidad</th>
							<th style="width:5%;">Ancho</th>
							<th style="width:5%;">No. Movimiento Entrada</th>			
							<th style="width:15%;">Proveedor</th>
							<th style="width:5%;">Lote</th>
							<th style="width:5%;">No. de Partida</th>
							<th style="width:5%;">Peso Real</th>
							
							<th style="width:15%;">Quitar</th>
						</tr>
					</thead>
				</table>
			</section>
		</div>
	</div>
</div>


				<br/>
		
				<div class="row bloque_totales">						
					<div class="col-sm-0 col-md-4">	
					  
					</div>	
					<div class="col-sm-3 col-md-2">	
					  <b>Existencias por Página</b>
					</div>	

					<div class="col-sm-3 col-md-2">	
						<span id="pieza2"></span>			
					</div>	
					<div class="col-sm-3 col-md-2">	
						<span id="metro2"></span>			
					</div>	
					<div class="col-sm-3 col-md-2">	
						<span id="kg2" ></span>				
					</div>	
				</div>			

				<div class="row bloque_totales">		
					<div class="col-sm-0 col-md-4">	
					  
					</div>	
					<div class="col-sm-3 col-md-2">	
					  <b>Existencias Totales</b>			
					</div>									
					<div class="col-sm-3 col-md-2">	
						<span id="total_pieza2"></span>			
					</div>	
					<div class="col-sm-3 col-md-2">	
						<span id="total_metro2"></span>			
					</div>	
					<div class="col-sm-3 col-md-2">	
						<span id="total_kg2" ></span>				
					</div>	
				</div>

<br>

	<div class="row">
		<div class="col-sm-4 col-md-4">	</div>

		<div class="col-sm-4 col-md-4 marginbuttom">
			<a href="<?php echo base_url(); ?>" type="button" class="btn btn-danger btn-block">Regresar</a>
		</div>

			<div class="col-sm-4 col-md-4">
				<button id="proc_salida" type="button"  class="btn btn-success btn-block">
					<span class="">Procesar Salida</span>
				</button>
			</div>


	</div>

</div>
</div>
</div>


<div class="modal fade bs-example-modal-lg" id="modalMessage" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
        <div class="modal-content"></div>
    </div>
</div>	




<!-- Modal -->
<div id="myModal" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
 	<div class="modal-dialog">
        <div class="modal-content">
    
			  <div class="modal-header">
			    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			    <h3 id="myModalLabel">Modal header</h3>
			  </div>
			  <div class="modal-body">
			    <p>One fine body…</p>
			  </div>
			  <div class="modal-footer">
			    <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
			    <button class="btn btn-primary">Save changes</button>
			  </div>

		</div>  
	</div>	  
</div>


<div id="miModal" class="modal hide fade">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3>Modal header</h3>
  </div>
  <div class="modal-body">
    <p>One fine body…</p>
  </div>
  <div class="modal-footer">
    <a href="#" class="btn">Close</a>
    <a href="#" class="btn btn-primary">Save changes</a>
  </div>
</div>

<?php $this->load->view( 'footer' ); ?>
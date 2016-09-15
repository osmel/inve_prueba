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

  ?>

<input type="hidden" id="id_proveedor" value="<?php echo $mi_proveedor; ?>">

<div class="container margenes">
<div class="panel panel-primary">
<div class="panel-heading">Registro Pedido de Compra</div>
<div class="panel-body">		
	
	<div class="row">		
		<!-- derecha comentarios-->	
	   <div class="col-sm-12 col-md-8">
			<div >
				<div class="col-xs-12 col-sm-6 col-md-4">
					<fieldset disabled>
					<div class="form-group">
						<label for="fecha" class="ttip" title="Campo informativo, no editable.">Fecha</label>
						<div>
							<input value="<?php echo $fecha_hoy; ?>"  type="text" class="form-control" id="fecha" name="fecha" placeholder="Fecha">
						</div>
					</div>
					</fieldset>	
				</div>
				<div class="col-xs-12 col-sm-6 col-md-4">
					<fieldset disabled>
						<div class="form-group">
							<label for="movimiento" class="ttip" title="Campo informativo, no editable.">No. Movimiento</label>
							<div>
								<input type="text" value="<?php echo $consecutivo->consecutivo+1; ?>" class="form-control" id="movimiento" name="movimiento" placeholder="No. Movimiento">
							</div>
						</div>
					</fieldset>			
				</div>
				

				<div class="col-xs-12 col-sm-4 col-md-4">
					<?php if ($val_proveedor) { ?>
					<fieldset class="disabledme" disabled>							
					<?php } else { ?>
					<fieldset class="disabledme">						
					<?php } ?>
						<div class="form-group">
						<label for="factura">Nro. Control</label>
							<div>
								<?php if ($val_proveedor) { ?>
								<input value="<?php echo htmlspecialchars($val_proveedor->factura); ?>" type="text" class="form-control ttip" title="Introduzca un número de factura para continuar." id="factura" name="factura" placeholder="Factura">							
								<?php } else { ?>
								<input type="text" class="form-control ttip" title="Introduzca un número de factura para continuar." id="factura" name="factura" placeholder="Nro. Control">
								<?php } ?>				
							</div>
						</div>
					</fieldset>	
				</div>

			</div>

			<div class="row">


					<!--almacen Asociado -->
					<div class="col-xs-12 col-sm-6 col-md-4">
					    
							<label for="id_almacen_modulo" class="col-sm-3 col-md-3 control-label">Almacén</label>
							<div class="col-sm-9 col-md-10">
							    <!--Los administradores o con permisos de traspaso 
							    	Y que no este inhabilitado y 
							    	que no sean almacenista 
							    	ENTONCES lista editable -->
							    <?php if (( ( $this->session->userdata( 'id_perfil' ) == 1  ) || (in_array(26, $coleccion_id_operaciones)) ) && (!$val_proveedor) && (( $this->session->userdata( 'id_perfil' ) != 2 ) ) ){ ?>
									 <fieldset class="disabledme">				
								<?php } else { ?>	
									 <fieldset class="disabledme" disabled>
								<?php } ?>	
											<select name="id_almacen_modulo" id="id_almacen_modulo" class="form-control">
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
			</div>

		</div>		
	     <!-- Izquierda comentarios-->	
		<div class="col-sm-12 col-md-4">
				<div class="form-group">
					<!--<label for="comentario" class="col-sm-4 col-md-4">Especificaciones</label>-->
					<label for="factura">Comentarios</label>
					<div class="col-sm-4 col-md-12">

						<?php if ($val_proveedor) { ?>
							<fieldset class="disabledme" disabled>							
						<?php } else { ?>
							<fieldset class="disabledme">						
						<?php } ?>					
									<?php 

										$nomb_nom='';
											if ($val_proveedor) { //comprobar una vez que ya esten inhabilitados factura
												if (isset($val_proveedor->comentario)) 
												 {	$nomb_nom = $val_proveedor->comentario;}
											}

										
									?>	

									<textarea  class="form-control" name="comentario" id="comentario" rows="5" placeholder="Comentarios"><?php echo  set_value('comentario',$nomb_nom); ?></textarea>
							  </fieldset>		
					</div>
				</div>						
		</div>	
			<!-- -->		


    </div>



    	<br/>

		<!-- primera tabla-->				
		<div class="col-md-12">	


				<div id="example22" class="row">
				              <div class="col-sm-6 col-md-3">
				                 <div class="form-group">
										<label for="descripcion">Producto</label>
				                        <select class="form-control" name="producto_catalogo" id="producto_catalogo" dependencia="color_catalogo" nombre="un color">
				                            <option value="">Seleccione un producto</option>
				                            <?php if($productos){ ?>
				                              <?php foreach($productos as $producto){ ?>
				                                <option value="<?php echo htmlspecialchars($producto->descripcion); ?>"><?php echo htmlspecialchars($producto->descripcion); ?></option>
				                              <?php } ?>
				                            <?php } ?>
				                        </select>
				                 </div>
				              </div>

				              <div class="col-sm-6 col-md-3">
				                 <div class="form-group">
									<label for="descripcion">Color</label>
				                          <select class="form-control ttip" title="Campo dependiente. Primero seleccione un PRODUCTO." name="color_catalogo" id="color_catalogo"  dependencia="composicion_catalogo" nombre="una composición">
				                            <option value="0">Seleccione un color</option>
				                          </select>
				                 </div>
				              </div>
				              
				              <div class="col-sm-6 col-md-3">
				                 <div class="form-group">
									<label for="descripcion">Composición</label>
				                          <select class="form-control ttip" title="Campo dependiente. Primero seleccione un COLOR." name="composicion_catalogo" id="composicion_catalogo" dependencia="calidad_catalogo" nombre="una calidad">
				                            <option value="0">Seleccione una composición</option>
				                          </select>
				                 </div>
				              </div>



				              <div class="col-sm-6 col-md-3">
				                 <div class="form-group">
									  <label for="descripcion">Calidad</label>
				                      <select class="form-control ttip" title="Campo dependiente. Primero seleccione una COMPOSICIÓN." name="calidad_catalogo" id="calidad_catalogo" dependencia="" nombre="">
				                        <option value="0">Seleccione una calidad</option>
				                      </select>
				                 </div>
				              </div>

				</div>     


				<div class="table-responsive" > <!--style="overflow-x:initial !important;" -->
						<section>
							<table id="tabla_entrada_pedido_compra" class="display table table-striped table-bordered table-responsive" cellspacing="0" width="100%">
							<thead>
								<tr>
								<th width="15%">Nombre de Tela</th>
								<th  width="15%">Imagen</th>
								<th  width="10%">Color</th>
								<th  width="4%">Composición</th>
								<th  width="4%">Calidad</th>
								<th  width="4%">Precio</th>
								<th style="width:5%;">Cant. Disponible</th>
								<th style="width:10%;">Agregar</th>




								</tr>
							</thead>
							</table>
						</section>
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


	<br/>
		
				<div class="row bloque_totales">						
					<div class="col-sm-0 col-md-4">	
					  
					</div>	
					<div class="col-sm-3 col-md-2">	
					  <b>Importes por Página</b>
					</div>	

					<div class="col-sm-3 col-md-2">	
						<span id="subtotal"></span>			
					</div>	
					<div class="col-sm-3 col-md-2">	
						<span id="iva"></span>			
					</div>				
					<div class="col-sm-3 col-md-2">	
						<span id="total"></span>			
					</div>	
				</div>			

				<div class="row bloque_totales">		
					<div class="col-sm-0 col-md-4">	
					  
					</div>	
					<div class="col-sm-3 col-md-2">	
					  <b>Importes Totales</b>			
					</div>									

					<div class="col-sm-3 col-md-2">	
						<span id="total_subtotal"></span>			
					</div>	
					<div class="col-sm-3 col-md-2">	
						<span id="total_iva"></span>			
					</div>					

					<div class="col-sm-3 col-md-2">	
						<span id="total_total"></span>			
					</div>	

				</div>		


		<!-- Segunda tabla-->
		<div class="col-md-12">		
			
			<h4>Orden Pedido de Compra</h4>	
			<br>	
			<div class="table-responsive">
				<section>
					<table id="tabla_salida_pedido_compra" class="display table table-striped table-bordered table-responsive" cellspacing="0" width="100%">
						<thead>
							<tr>
									<th width="15%">Nombre de Tela</th>
									<th  width="15%">Imagen</th>
									<th  width="10%">Color</th>
									<th  width="4%">Composición</th>
									<th  width="4%">Calidad</th>
									<th  width="4%">Precio</th>
									<th style="width:5%;">Cant. Disponible</th>
									<th style="width:5%;">Cant. Pedido</th>
									<th style="width:5%;">Cant. Aprobada</th>
									<th style="width:10%;">Quitar</th>
							</tr>
						</thead>
					</table>
				</section>
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

	<br/>
		
				<div class="row bloque_totales2">						
					<div class="col-sm-0 col-md-4">	
					  
					</div>	
					<div class="col-sm-3 col-md-2">	
					  <b>Importes por Página</b>
					</div>	

					<div class="col-sm-3 col-md-2">	
						<span id="subtotal2"></span>			
					</div>	
					<div class="col-sm-3 col-md-2">	
						<span id="iva2"></span>			
					</div>				
					<div class="col-sm-3 col-md-2">	
						<span id="total2"></span>			
					</div>	
				</div>			

				<div class="row bloque_totales2">		
					<div class="col-sm-0 col-md-4">	
					  
					</div>	
					<div class="col-sm-3 col-md-2">	
					  <b>Importes Totales</b>			
					</div>									

					<div class="col-sm-3 col-md-2">	
						<span id="total_subtotal2"></span>			
					</div>	
					<div class="col-sm-3 col-md-2">	
						<span id="total_iva2"></span>			
					</div>					

					<div class="col-sm-3 col-md-2">	
						<span id="total_total2"></span>			
					</div>	

				</div>				

 <br>

	<div class="row">
		<div class="col-sm-4 col-md-4">	</div>
		<div class="col-sm-4 col-md-4 marginbuttom">
			<a href="<?php echo base_url(); ?>" type="button" class="btn btn-danger btn-block">Regresar</a>
		</div>
			<div class="col-sm-4 col-md-4">
				<button id="proc_traspaso" type="button"  class="btn btn-success btn-block">
					<span class="">Procesar Pedido de Compra</span>
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
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
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


<div class="container margenes">
	<div class="panel panel-primary">
		<div id="label_reporte" class="panel-heading">Conteo físico de inventario</div>
			<div class="container">	
				<br>

			


<!-- Aqui comienza filtro	-->

				<div class="col-md-12 form-horizontal"  id="tab_filtro">      
						
						<h4>Filtros</h4>	
						<hr style="padding: 0px; margin: 15px;"/>					

					

					<div  class="row">
				
							<input type="hidden" id="mi_perfil" name="mi_perfil" value="<?php echo $this->session->userdata( 'id_perfil' ); ?>">
							
							<div id="almacen_id" class="col-xs-12 col-sm-6 col-md-2" <?php echo 'style="display:'.( (($config_almacen->activo==0) && ($el_perfil==2) ) ? 'none':'block').'"'; ?>>
								<div class="form-group">
									<label for="almacen" class="col-sm-12 col-md-12">Almacén</label>
									<div class="col-sm-12 col-md-12">
				
							    <?php if  ( $this->session->userdata( 'id_perfil' ) != 2  ) { ?>
									 <fieldset class="disabledme">				
								<?php } else { ?>	
									 <fieldset class="disabledme" disabled>
								<?php } ?>	

										<select name="id_almacen_historicos" vista="informe_pendiente" id="id_almacen_historicos" class="form-control ttip" title="Seleccione el almacén del producto a consultar.">
										
											<!-- <option value="0">Todos</option> -->

												<?php foreach ( $almacenes as $almacen ){ ?>
													<?php 
													if  (($almacen->id_almacen==$id_almacen) ) 
														{$seleccionado='selected';} else {$seleccionado='';}
													?>
													
														<option value="<?php echo $almacen->id_almacen; ?>" <?php echo $seleccionado; ?>><?php echo $almacen->almacen; ?></option>
												<?php } ?>
										</select>
									</fieldset>	

									</div>
								</div>
							</div>	



								
					</div>	


		            
							


					<div id="example2" class="row">
		                  <div class="col-xs-12 col-sm-6 col-md-4">
		                     <div class="form-group">
								<label for="descripcion" class="col-sm-12 col-md-12">Producto</label>
								<div class="col-sm-12 col-md-12">

			                          <select class="col-sm-12 col-md-12 form-control" name="producto" id="producto" dependencia="color" nombre="un color">
			                            <option value="">Seleccione un producto</option>
			                            <?php if($productos){ ?>
			                              <?php foreach($productos as $producto){ ?>
			                                <option value="<?php echo htmlspecialchars($producto->descripcion); ?>"><?php echo htmlspecialchars($producto->descripcion); ?></option>
			                              <?php } ?>
			                            <?php } ?>
			                          </select>
		                        </div>  
		                          
		                     </div>
		                  </div>

		                  <div class="col-xs-12 col-sm-6 col-md-2">
		                     <div class="form-group">
								<label for="descripcion" class="col-sm-12 col-md-12">Color</label>
								<div class="col-sm-12 col-md-12">

			                          <select class="col-sm-12 col-md-12 form-control ttip" title="Campo dependiente. Primero seleccione un PRODUCTO." name="color" id="color"  dependencia="composicion" nombre="una composición" style="padding-right:0px">
			                            <option value="0">Seleccione un color</option>
			                          </select>
		                        </div>  
		                          
		                     </div>
		                  </div>
		                  
		                  <div class="col-xs-12 col-sm-6 col-md-3">
		                     <div class="form-group">
								<label for="descripcion" class="col-sm-12 col-md-12">Composición</label>
								<div class="col-sm-12 col-md-12">

			                          <select class="col-sm-12 col-md-12 form-control ttip" title="Campo dependiente. Primero seleccione un COLOR." name="composicion" id="composicion" dependencia="calidad" nombre="una calidad" style="padding-right:0px">
			                            <option value="0">Seleccione una composición</option>
			                          </select>
		                        </div>  
		                          
		                     </div>
		                  </div>



		                  <div class="col-xs-12 col-sm-6 col-md-3">
		                     <div class="form-group">
								<label for="descripcion" class="col-sm-12 col-md-12">Calidad</label>
								<div class="col-sm-12 col-md-12">
			                          <select class="col-sm-12 col-md-12 form-control ttip" title="Campo dependiente. Primero seleccione una COMPOSICIÓN." name="calidad" id="calidad" dependencia="" nombre="" style="padding-right:0px">
			                            <option value="0">Seleccione una calidad</option>
			                          </select>
		                        </div>  
		                          
		                     </div>
		                  </div>

		            </div>     



				

<!-- Hasta aqui el filtro	-->

				<hr style="padding: 0px; margin: 8px;"/>					

				<div class="row">	
					<div class="col-md-12">	
					
						<div class="table-responsive">

							<section>
								<table id="tabla_informe_pendiente" class="display table table-striped table-bordered table-responsive" cellspacing="0" width="100%">
									<thead>
										<tr>
											<th class="text-center " width="25%">Entradas</th>
											<th class="text-center " width="25%"><strong>Pedidos</strong></th>
											<th class="text-center " width="25%"><strong>Devolución</strong></th>
											<th class="text-center " width="25%"><strong>Traspasos</strong></th>
											

										</tr>
									</thead>
								</table>
							</section>
		                           
									
							
					
						</div>
						
					</div>	
				</div>	
				
				<br/>
		
					
				
				



					<input type="hidden" id="referencia" name="referencia" value="">
					<input type="hidden" id="codigo_original" name="codigo_original" value="">

						

						<div class="row">

								<input type="hidden" id="botones" name="botones" value="existencia">
						</div>
						<br><br>

						
						<div class="row">
							<div class="col-sm-8 col-md-4"></div>
							<div class="col-sm-4 col-md-4">
								<a href="<?php echo base_url(); ?>" type="button" class="btn btn-danger btn-block">Regresar</a>
							</div>

							<div class="col-sm-4 col-md-4">
								<button type="button"  class="btn btn-success btn-block ttip" title="Cambiar el estatus del pedido para poder ser procesado en la salida." id="incluir_pedido">
									<span>Procesar conteo</span>
								</button>
							</div>								
						</div>
						<br/>
				


			</div>

	</div>

</div>


<?php $this->load->view( 'footer' ); ?>
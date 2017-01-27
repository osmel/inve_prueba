	<?php 

		$config_impresion= $this->session->userdata('config_impresion');

		$id_almacen_ajuste = 	$this->session->userdata( 'id_almacen_ajuste' );
		//echo $id_almacen_ajuste;
	 ?>
	 
	 <input type="hidden" id="modulo_activo" name="modulo_activo" value="">

				<div class="row">

					<div class="col-xs-12 col-sm-3 col-md-2">
						<label for="descripcion" class="col-sm-12 col-md-12"></label>
						<a href="<?php echo base_url(); ?>informe_pendiente"  
							type="button" class="btn <?php echo ($modulo==1) ? 'btn-warning': 'btn-info'; ?> btn-block ttip" title="Generar el conteo .">Generar Conteos. 
						</a>
					</div>


					<div class="col-xs-12 col-sm-3 col-md-2">
						<label for="descripcion" class="col-sm-12 col-md-12"></label>
						<a href="<?php echo base_url(); ?>conteo1"  
							type="button" class="btn <?php echo ($modulo==2) ? 'btn-warning': 'btn-info'; ?> btn-block ttip" title="Primer conteo">Conteo 1 <?php echo '<span class="glyphicon glyphicon-eye-open l2" style="display:none;"> <span>'; ?>
						</a>
					</div>



					<div class="col-xs-12 col-sm-3 col-md-2">
						<label for="descripcion" class="col-sm-12 col-md-12"></label>
						<a href="<?php echo base_url(); ?>conteo2"  
							type="button" class="btn <?php echo ($modulo==3) ? 'btn-warning': 'btn-info'; ?> btn-block ttip" title="Segundo conteo.">Conteo 2 <?php echo '<span class="glyphicon glyphicon-eye-open l3" style="display:none;"> <span>'; ?>
						</a>
					</div>

					<div class="col-xs-12 col-sm-3 col-md-2">
						<label for="descripcion" class="col-sm-12 col-md-12"></label>
						<a href="<?php echo base_url(); ?>conteo3"  
							type="button" class="btn <?php echo ($modulo==4) ? 'btn-warning': 'btn-info'; ?> btn-block ttip" title="Tercer conteo.">Conteo 3 <?php echo '<span class="glyphicon glyphicon-eye-open l4" style="display:none;"> <span>'; ?>
						</a>
					</div>

					<div class="col-xs-12 col-sm-3 col-md-2">
						<label for="descripcion" class="col-sm-12 col-md-12"></label>
						<a href="<?php echo base_url(); ?>faltante"  
							type="button" class="btn <?php echo ($modulo==5) ? 'btn-warning': 'btn-info'; ?> btn-block ttip" title="Ajuste negativo o Lista de faltante.">Faltante <?php echo '<span class="glyphicon glyphicon-eye-open l5" style="display:none;"> <span>'; ?>
						</a>
					</div>


					<div class="col-xs-12 col-sm-3 col-md-2">
						<label for="descripcion" class="col-sm-12 col-md-12"></label>
						<a href="<?php echo base_url(); ?>sobrante"  
							type="button" class="btn <?php echo ($modulo==6) ? 'btn-warning': 'btn-info'; ?> btn-block ttip" title="Ajuste positivo o Lista de sobrantes.">Sobrante <?php echo '<span class="glyphicon glyphicon-eye-open l6" style="display:none;"> <span>'; ?>
						</a>

					</div>

			

				</div>

				<div class="row">
					<br/>
					<div class="col-xs-12 col-sm-3 col-md-2">
						<label for="descripcion" class="col-sm-12 col-md-12"></label>
						<a href="<?php echo base_url(); ?>resumen_conteo"  
							type="button" class="btn <?php echo ($modulo==7) ? 'btn-warning': 'btn-info'; ?> btn-block ttip" title="Mostrar resumen y archivar todos los procesos.">Resumen y Archivo <?php echo '<span class="glyphicon glyphicon-eye-open l7" style="display:none;"> <span>'; ?>
						</a>
					</div>		
					
				</div>	




	<!-- Aqui comienza filtro	-->

		<div class="col-md-12 form-horizontal" id="tab_filtro" >      
						
						<h4>Filtros: </h4>	 
						<?php echo $filtro;?>
						<hr style="padding: 0px; margin: 15px;"/>					

					

					<div  class="row">
				
							<input type="hidden" id="mi_perfil" name="mi_perfil" value="<?php echo $this->session->userdata( 'id_perfil' ); ?>">
							
							<div id="almacen_id" class="col-xs-12 col-sm-6 col-md-2" <?php echo 'style="display:'.( (($config_almacen->activo==0) && ($el_perfil==2) ) ? 'none':'block').'"'; ?>>
								<div class="form-group">
									<label for="almacen" class="col-sm-12 col-md-12">Almacén</label>
									<div class="col-sm-12 col-md-12">
				
										<select name="id_almacen_historicos" vista="<?php echo $vista; ?>" id="id_almacen_historicos" class="form-control ttip" title="Seleccione el almacén del producto a consultar.">
										
											<!-- <option value="0">Todos</option> -->

												<?php foreach ( $almacenes as $almacen ){ ?>
													<?php 
													if  (($almacen->id_almacen==$id_almacen_ajuste) ) 
														{$seleccionado='selected';} else {$seleccionado='';}
													?>
													
														<option value="<?php echo $almacen->id_almacen; ?>" <?php echo $seleccionado; ?>><?php echo $almacen->almacen; ?></option>
												<?php } ?>
										</select>
									

									</div>
								</div>
							</div>	

							<fieldset id="imp_sobrante" style="display:none;">
								<div class="col-sm-3 col-md-3">
									<label for="descripcion" class="col-sm-12 col-md-12"></label>
									
									<a id="imp_etiq_rapida" href="" 
										type="button" class="btn btn-success btn-block" target="_blank">Imprimir etiquetas
									</a>
								</div>

								

								<div class="col-sm-3 col-md-3">
									<label for="descripcion" class="col-sm-12 col-md-12"></label>
									<a id="imp_nota_rapida" href=""  
										type="button" class="btn btn-success btn-block" target="_blank">Imprimir nota
									</a>
								</div>

								<div class="col-sm-3 col-md-3" <?php echo 'style="display:'.( (($config_impresion->activo==0) ) ? 'none':'block').'"'; ?>>
									<label for="descripcion" class="col-sm-12 col-md-12"></label>
									
									<a id="imp_etiq" href="" 
										type="button" class="btn btn-success btn-block" target="_blank">PDF etiquetas
									</a>
								</div>

								<div class="col-sm-3 col-md-3" <?php echo 'style="display:'.( (($config_impresion->activo==0) ) ? 'none':'block').'"'; ?>>
									<label for="descripcion" class="col-sm-12 col-md-12"></label>
									<a id="imp_nota" href=""  
										type="button" class="btn btn-success btn-block" target="_blank">PDF nota
									</a>
								</div>


							</fieldset>						


							<fieldset id="imp_faltante" style="display:none;">
								<div class="col-sm-3 col-md-3">
									<label for="descripcion" class="col-sm-12 col-md-12"></label>
									<a id="imp_nota_faltante" href=""  
										type="button" class="btn btn-success btn-block" target="_blank">Imprimir nota
									</a>
								</div>
							</fieldset>	

							<fieldset id="imp_conteos" style="display:none;">
								<div class="col-sm-3 col-md-3">
									<label for="descripcion" class="col-sm-12 col-md-12"></label>
									<a id="imp_nota_conteo" href=""  
										type="button" class="btn btn-success btn-block" target="_blank">Imprimir
									</a>
								</div>
							</fieldset>								

								
					</div>	




		           


 				<div class="row conteo_principal" style="display:none;">
					<!--Tipos de factura -->
						<div class="col-xs-12 col-sm-6 col-md-3" >
								
									<label for="descripcion">Tipo de Factura</label>
								    <!--Los administradores o con permisos de entrada 
								    	Y que no este inhabilitado y 
								    	que no sean facturaista 
								    	ENTONCES lista editable -->
										<div class="input-group col-xs-12 col-sm-12 col-md-12 ">
												<select name="id_tipo_factura_existente" id="id_tipo_factura_existente" pantalla="generar_pedidos1" class="form-control" dependencia="producto_existente" nombre="un producto">
													<!--<option value="0">Selecciona una opción</option>-->
														<?php foreach ( $facturas as $factura ){ ?>
																	<option value="<?php echo $factura->id; ?>"  ><?php echo $factura->tipo_factura; ?></option>
														<?php } ?>
													<!--rol de usuario -->
												</select>
									    </div>
								
						</div>		


						
							<div id="proveedor_id" class="col-xs-12 col-sm-6 col-md-3">

											<div class="form-group">
												<label id="label_proveedor" for="descripcion" class="col-sm-12 col-md-12">Proveedor</label>
												<div class="col-sm-12 col-md-12">
													 <input  type="text" name="editar_proveedor_historico" id="editar_proveedor_historico" vista="conteo_fisico" idproveedor="1" class="form-control buscar_proveedor_historico ttip" title="Campo predictivo. Comience a escribir y seleccione una opción para agregar un filtro de selección." autocomplete="off" spellcheck="false" placeholder="Buscar...">
												</div>
											</div>
									
								</div>		

		        </div>
							







					<!--<div id="example2" class="row" style="display:<?php echo ($modulo==1) ? 'block': 'none'; ?>"> -->
					<div id="example2" class="row conteo_principal" style="display:none;">
					
		                  <div class="col-xs-12 col-sm-6 col-md-4">
		                     <div class="form-group">
								<label for="descripcion" class="col-sm-12 col-md-12">Producto</label>
								<div class="col-sm-12 col-md-12">

			                          <select class="col-sm-12 col-md-12 form-control" name="producto_existente" id="producto_existente" dependencia="color_existente" nombre="un color">
			                            <option value="0">Seleccione un producto</option>
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

			                          <select class="col-sm-12 col-md-12 form-control ttip" title="Campo dependiente. Primero seleccione un PRODUCTO." name="color_existente" id="color_existente"  dependencia="composicion_existente" nombre="una composición" style="padding-right:0px">
			                            <option value="0">Seleccione un color</option>
			                          </select>
		                        </div>  
		                          
		                     </div>
		                  </div>
		                  
		                  <div class="col-xs-12 col-sm-6 col-md-3">
		                     <div class="form-group">
								<label for="descripcion" class="col-sm-12 col-md-12">Composición</label>
								<div class="col-sm-12 col-md-12">

			                          <select class="col-sm-12 col-md-12 form-control ttip" title="Campo dependiente. Primero seleccione un COLOR." name="composicion_existente" id="composicion_existente" dependencia="calidad_existente" nombre="una calidad" style="padding-right:0px">
			                            <option value="0">Seleccione una composición</option>
			                          </select>
		                        </div>  
		                          
		                     </div>
		                  </div>



		                  <div class="col-xs-12 col-sm-6 col-md-3">
		                     <div class="form-group">
								<label for="descripcion" class="col-sm-12 col-md-12">Calidad</label>
								<div class="col-sm-12 col-md-12">
			                          <select class="col-sm-12 col-md-12 form-control ttip" title="Campo dependiente. Primero seleccione una COMPOSICIÓN." name="calidad_existente" id="calidad_existente" dependencia="" nombre="" style="padding-right:0px">
			                            <option value="0">Seleccione una calidad</option>
			                          </select>
		                        </div>  
		                          
		                     </div>
		                  </div>

		            </div>     


		  </div>       
				
<input type="hidden" id="modulo" name="modulo" value="<?php echo $modulo; ?>">

<!-- Hasta aqui el filtro	-->				






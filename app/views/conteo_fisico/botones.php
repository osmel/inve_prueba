	<?php 

		$id_almacen_ajuste = 	$this->session->userdata( 'id_almacen_ajuste' );
		//echo $id_almacen_ajuste;
	 ?>
				<div class="row">

					<div class="col-xs-12 col-sm-3 col-md-2">
						<label for="descripcion" class="col-sm-12 col-md-12"></label>
						<a href="<?php echo base_url(); ?>informe_pendiente"  
							type="button" class="btn <?php echo ($modulo==1) ? 'btn-warning': 'btn-info'; ?> btn-block ttip" title="Se hizo un pedido y esta esperando a que el admin lo revise, O el almacenista hizo la modificacion .">Revisión - Admin. <?php echo "(".$cant[1].")"; ?>
						</a>
					</div>


					<div class="col-xs-12 col-sm-3 col-md-2">
						<label for="descripcion" class="col-sm-12 col-md-12"></label>
						<a href="<?php echo base_url(); ?>conteo1"  
							type="button" class="btn <?php echo ($modulo==2) ? 'btn-warning': 'btn-info'; ?> btn-block ttip" title="El admin pide modificar">Conteo 1. <?php echo "(".$cant[2].")"; ?>
						</a>
					</div>



					<div class="col-xs-12 col-sm-3 col-md-2">
						<label for="descripcion" class="col-sm-12 col-md-12"></label>
						<a href="<?php echo base_url(); ?>conteo2"  
							type="button" class="btn <?php echo ($modulo==3) ? 'btn-warning': 'btn-info'; ?> btn-block ttip" title="Imprimir y pasarlo al historico.">Conteo 2 <?php echo "(".$cant[3].")"; ?>
						</a>
					</div>

					<div class="col-xs-12 col-sm-3 col-md-2">
						<label for="descripcion" class="col-sm-12 col-md-12"></label>
						<a href="<?php echo base_url(); ?>conteo3"  
							type="button" class="btn <?php echo ($modulo==4) ? 'btn-warning': 'btn-info'; ?> btn-block ttip" title="Ver listado de cancelados.">Conteo 3 <?php echo "(".$cant[4].")"; ?>
						</a>
					</div>

					<div class="col-xs-12 col-sm-3 col-md-2">
						<label for="descripcion" class="col-sm-12 col-md-12"></label>
						<a href="<?php echo base_url(); ?>faltante"  
							type="button" class="btn <?php echo ($modulo==5) ? 'btn-warning': 'btn-info'; ?> btn-block ttip" title="Ajuste negativo.">Faltante <?php echo "(".$cant[5].")"; ?>
						</a>
					</div>


					<div class="col-xs-12 col-sm-3 col-md-2">
						<label for="descripcion" class="col-sm-12 col-md-12"></label>
						<a href="<?php echo base_url(); ?>sobrante"  
							type="button" class="btn <?php echo ($modulo==6) ? 'btn-warning': 'btn-info'; ?> btn-block ttip" title="Ajuste positivo.">Sobrante <?php echo "(".$cant[6].")"; ?>
						</a>
					</div>


					
					
				

				</div>





<!-- Aqui comienza filtro	-->

		<div class="col-md-12 form-horizontal"  id="tab_filtro" >      
						
						<h4>Filtros</h4>	
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



								
					</div>	


		            
							


					<div id="example2" class="row" style="display:<?php echo ($modulo==1) ? 'block': 'none'; ?>">
		                  <div class="col-xs-12 col-sm-6 col-md-4">
		                     <div class="form-group">
								<label for="descripcion" class="col-sm-12 col-md-12">Producto</label>
								<div class="col-sm-12 col-md-12">

			                          <select class="col-sm-12 col-md-12 form-control" name="producto" id="producto" dependencia="color" nombre="un color">
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


		  </div>       
				
<input type="hidden" id="modulo" name="modulo" value="<?php echo $modulo; ?>">

<!-- Hasta aqui el filtro	-->				






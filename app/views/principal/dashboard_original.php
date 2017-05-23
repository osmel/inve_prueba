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

		<div id="label_home" class="panel-heading">Existencias</div>

			<div class="container">	
			<br>	

				<div class="row">
					<div class="col-xs-12 col-sm-4 col-md-2 marginbuttom">
						<button id="existencia_home" type="button" class="btn btn-danger btn-block ttip" title="Mostrar productos con existencia en almacén.">Existencias</button>
					</div>
					
					<div class="col-xs-12 col-sm-4 col-md-2 marginbuttom">
						<button  id="devolucion_home" type="button" class="btn btn-danger btn-block ttip" title="Mostrar sólo productos devueltos.">Devoluciones</button>
					</div>
					
					<div class="col-xs-12 col-sm-4 col-md-2 marginbuttom">
						<button  id="apartado_home" type="button" class="btn btn-danger btn-block ttip" title="Mostrar sólo productos con apartado individual y/o confirmado.">Apartados</button>
					</div>
					<div class="col-xs-12 col-sm-4 col-md-2 marginbuttom">
						<button  id="cero_home" type="button" class="btn btn-danger btn-block ttip" title="Mostrar productos agotados.">Existencias Cero</button>
					</div>
					<div class="col-xs-12 col-sm-4 col-md-2 marginbuttom">
						<button  id="baja_home" type="button" class="btn btn-danger btn-block ttip" title="Mostrar productos con existencia por debajo del mínimo.">Existencias Bajas</button>
					</div>
						<input type="hidden" id="botones" name="botones" value="existencia">

					<div id="disponibilidad"  class="col-xs-12 col-sm-4 col-md-2 marginbuttom">
								<button  id="ver_filtro" type="button" class="btn btn-success btn-block ttip" title="Mostrar u ocultar filtros.">Filtros</button>
					</div>

					<div class="col-xs-12 col-sm-4 col-md-3">
						<label for="descripcion" class="col-sm-12 col-md-12"></label>
						<a href="<?php echo base_url(); ?>consulta_proveedor"  
							type="button" class="btn btn-info btn-block ttip" title="Listado de proveedores.">Consultas por Proveedor
						</a>
					</div>

					<div class="col-xs-12 col-sm-4 col-md-3">
						<label for="descripcion" class="col-sm-12 col-md-12"></label>
						<a href="<?php echo base_url(); ?>consulta_producto" type="button" class="btn btn-info btn-block ttip" title="Listado de productos.">
							Consultas por Productos
						</a>
					</div>

					<div class="col-xs-12 col-sm-4 col-md-3">
						<label for="descripcion" class="col-sm-12 col-md-12"></label>
						<a href="<?php echo base_url(); ?>consulta_totales" type="button" class="btn btn-info btn-block ttip" title="Totales de productos.">
							Reportes Totales
						</a>
					</div>



				</div>


<!-- Aqui comienza el filtro	-->

				<div class="col-md-12 form-horizontal" style="display:none;" id="tab_filtro">      
						
						
						<h4>Filtros</h4>	
						<hr style="padding: 0px; margin: 15px;"/>					
						

					<div  class="row">
						
							<div class="col-xs-12 col-sm-6 col-md-2" <?php echo 'style="display:'.( ($el_perfil==4 ) ? 'none':'block').'"'; ?>>
							    
									<label for="id_factura_home" class="col-sm-3 col-md-12">Tipo de factura</label>
									<div class="col-sm-9 col-md-12">
									    			
													<select name="id_factura_home" id="id_factura_home" class="form-control">
															<option value="0">Todos</option>	
															<?php foreach ( $facturas as $factura ){ ?>
																		<option value="<?php echo $factura->id; ?>" ><?php echo $factura->tipo_factura; ?></option>
															<?php } ?>
														<!--rol de usuario -->
													</select>
										    

									</div>
							</div>	

							<input type="hidden" id="mi_perfil" name="mi_perfil" value="<?php echo $this->session->userdata( 'id_perfil' ); ?>">
							
							<div id="almacen_id" class="col-xs-12 col-sm-6 col-md-2" <?php echo 'style="display:'.( (($config_almacen->activo==0) && ($el_perfil==2) ) ? 'none':'block').'"'; ?>>
								<div class="form-group">
									<label for="almacen" class="col-sm-12 col-md-12">Almacén</label>
									<div class="col-sm-12 col-md-12">
				
							    <?php if  ( $this->session->userdata( 'id_perfil' ) == 1  ) { ?>
									 <fieldset class="disabledme">				
								<?php } else { ?>	
									 <fieldset class="disabledme" disabled>
								<?php } ?>	

										<select name="id_almacen_home" id="id_almacen_home" class="form-control ttip" title="Seleccione el almacén del producto a consultar.">
										
											<option value="0">Todos</option>

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

					   <div id="fecha_id">
							<div id="estatus_id" class="col-xs-12 col-sm-6 col-md-2">
								<div class="form-group">
									<label for="estatus" class="col-sm-12 col-md-12">Estatus</label>
									<div class="col-sm-12 col-md-12">
										<select name="id_estatus_home" id="id_estatus_home" class="form-control ttip" title="Seleccione el estatus del producto a consultar.">
												<?php foreach ( $estatuss as $estatus ){ ?>
														<option value="<?php echo $estatus->id; ?>"><?php echo $estatus->estatus; ?></option>
												<?php } ?>
										</select>
									</div>
								</div>
							</div>							


													
							<div id="proveedor_id" class="col-xs-12 col-sm-6 col-md-3">

										<div class="form-group">
											<label id="label_proveedor" for="descripcion" class="col-sm-12 col-md-12">Proveedor</label>
											<div class="col-sm-12 col-md-12">
												 <input  type="text" name="editar_proveedor_home" id="editar_proveedor_home" idproveedor="1" class="form-control buscar_proveedor_home ttip" title="Campo predictivo. Comience a escribir y seleccione una opción para agregar un filtro de selección." autocomplete="off" spellcheck="false" placeholder="Buscar...">
											</div>
										</div>
								
							</div>						


						<div id="fecha_id" class="col-xs-12 col-sm-6 col-md-2">
							<div class="form-group">
									<label id="label_proveedor" for="descripcion" class="col-sm-12 col-md-12">Rango de fecha</label>
									<div class="input-prepend input-group  form-group col-sm-12 col-md-12" style="padding-left:30px !important; ">
			                       		<span class="add-on input-group-addon"><i class="glyphicon glyphicon-calendar fa fa-calendar"></i></span>
										<input id="foco" type="text" name="permisos"  class="form-control fecha_home ttip" title="Seleccione un rango de fechas para filtrar los resultados." value="" format = "DD-MM-YYYY"/> 
									</div>	
							</div>		
	                     </div>

						
							
								<div class="col-xs-12 col-sm-6 col-md-1" id="bloque_factura" <?php echo 'style="display:'.(($configuracion->activo==0) ? 'none':'block' ).'"'; ?> >
										<div class="form-group">
										<label id="label_proveedor" for="descripcion" class="col-sm-12 col-md-12">Factura</label>
											<div class="col-sm-12 col-md-12">
												<input type="text" class="form-control" id="factura_dashboard" name="factura_dashboard" placeholder="Factura/Rem">
											</div>
										</div>
								</div>		
							
						


					</div>			
				</div>		
							

							<!-- fecha -->






					<div id="example2" class="row">
		                  <div class="col-xs-12 col-sm-6 col-md-4">
		                     <div class="form-group">
								<label for="descripcion" class="col-sm-12 col-md-12">Producto</label>
								<div class="col-sm-12 col-md-12">

			                          <select class="col-sm-12 col-md-12 form-control" name="producto" id="producto" dependencia="color" nombre="un color">
			                            <option value="">Seleccione un producto</option>
			                            <?php if($productos){ ?>
			                              <?php foreach($productos as $producto){ ?>
			                                <option value="<?php echo $producto->descripcion; ?>"><?php echo $producto->descripcion; ?></option>
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


		            <hr style="padding: 0px; margin: 15px;"/>					
				</div>




<!-- Hasta aqui el filtro	-->


				
				
				<hr style="padding: 0px; margin: 8px;"/>					

				<div class="row">	
					<div class="col-md-12">	
					
						<div class="table-responsive">
							
							<div class="col-md-7">								
	                           <div class="col-md-4 leyen_home" style="display: block;" ><span> Apartados</span><div style="margin-right: 15px;float:left;background-color:#14b80f;width:15px;height:15px;"></div></div>
							   <div class="col-md-4 leyen_home"  style="display: block;"><span> Devoluciones</span><div style="margin-right: 15px;float:left;background-color:#ab1d1d;width:15px;height:15px;"></div> </div>
							   <div class="col-md-4 leyen_home"  style="display: block;"><span> Traspasos en proceso</span><div style="border: 1px solid black; margin-right: 15px;float:left;background-color:#fcf8e3;width:15px;height:15px;"></div> </div>
	                        </div>   
	                        <div class="col-md-8">								
	                           <div class="col-md-4 ttip leyenda" style="display: none;" title="Producto en espera de confirmación total del apartado."><span> Apartado Individual</span><div style="margin-right: 15px;float:left;background-color:#ab1d1d;width:15px;height:15px;"></div> </div>
	                           <div class="col-md-4 ttip leyenda" style="display: none;" title="El apartado ha sido generado."><span> Apartado Confirmado</span><div style="margin-right: 15px;float:left;background-color:#f1a914;width:15px;height:15px;"></div></div>
							   <div class="col-md-4 ttip leyenda" style="display: none;" title="Indica que se puede procesar la salida del apartado."><span> Disponibilidad Salida</span><div style="margin-right: 15px;float:left;background-color:#14b80f;width:15px;height:15px;"></div></div>
							</div>   
							

						   <br/>
						   <hr style="padding: 0px; margin: 8px;"/>					

   						   	<div class="notif-bot-pedidos"></div>
							<section>
								<table id="tabla_home" class="display table table-striped table-bordered table-responsive " cellspacing="0" width="100%">
									<!--
										<table id="tabla_home" class="display table table-striped table-bordered table-responsive " cellspacing="0" width="100%">
									-->
							

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



				<div class="col-md-12">		
					



					<input type="hidden" id="referencia" name="referencia" value="">
					<input type="hidden" id="codigo_original" name="codigo_original" value="">

						

						<div class="row">

								<input type="hidden" id="botones" name="botones" value="existencia">
						</div>
						

						<br/>
						<div class="row">
							<div class="col-sm-8 col-md-8"></div>
							<div class="col-sm-4 col-md-4">
								<a href="#" type="button" class="btn btn-danger btn-block">Regresar</a>
							</div>
							
						</div>
						<br/>


				</div>

			</div>
	</div>
</div>

<div class="modal fade bs-example-modal-lg" id="myModaldashboard" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog" style="width:300px; !important; margin-top:50px !important">
        <div class="modal-content" style="width:100% !important;"></div>
    </div>
</div>	

<?php $this->load->view( 'footer' ); ?>
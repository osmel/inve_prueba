<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php $this->load->view( 'header' ); ?>

<?php 
   $coleccion_id_operaciones= json_decode($this->session->userdata('coleccion_id_operaciones')); 
   if ( (count($coleccion_id_operaciones)==0) || (!($coleccion_id_operaciones)) ) {
        $coleccion_id_operaciones = array();
   }   


 $id_almacen=$this->session->userdata('id_almacen');

?>


<input type="hidden" id="modulo" name="modulo" value="<?php echo $modulo; ?>">

<div class="container margenes">
	<div class="panel panel-primary">
		<div id="label_reporte" class="panel-heading"><?php echo $titulo; ?></div>
			<div class="container">	
				<br>

				
				<div class="row">

					<div class="col-xs-12 col-sm-3 col-md-2">
						<label for="descripcion" class="col-sm-12 col-md-12"></label>
						<a href="<?php echo base_url(); ?>pendiente_revision"  
							type="button" class="btn btn-info btn-block ttip" title="Se hizo un pedido y esta esperando a que el admin lo revise, O el almacenista hizo la modificacion .">En proceso de revisión
						</a>
					</div>


					<div class="col-xs-12 col-sm-3 col-md-2">
						<label for="descripcion" class="col-sm-12 col-md-12"></label>
						<a href="<?php echo base_url(); ?>solicitar_modificacion"  
							type="button" class="btn btn-info btn-block ttip" title="El admin pide modificar">Solicitud de cambio
						</a>
					</div>



					<div class="col-xs-12 col-sm-3 col-md-2">
						<label for="descripcion" class="col-sm-12 col-md-12"></label>
						<a href="<?php echo base_url(); ?>aprobado"  
							type="button" class="btn btn-info btn-block ttip" title="Imprimir y pasarlo al historico.">Aprobado
						</a>
					</div>

					<div class="col-xs-12 col-sm-3 col-md-2">
						<label for="descripcion" class="col-sm-12 col-md-12"></label>
						<a href="<?php echo base_url(); ?>cancelado"  
							type="button" class="btn btn-info btn-block ttip" title="Ver listado de cancelados.">Cancelado
						</a>
					</div>

					


					<div class="col-xs-12 col-sm-3 col-md-2">
						<label for="descripcion" class="col-sm-12 col-md-12"></label>
						<a href="<?php echo base_url(); ?>gestionar_pedido_compra"  
							type="button" class="btn btn-info btn-block ttip" title="Todos los que fueron aprobados por el admin y confirmado en 'aprobado' por el almacenista.">Historico de pedidos
						</a>
					</div>


					

					<div id="disponibilidad"  class="col-xs-12 col-sm-3 col-md-2 marginbuttom">
								<button  id="ver_filtro" type="button" class="btn btn-success btn-block ttip" title="Mostrar u ocultar filtros.">Filtros</button>
					</div>

				

				</div>


<!-- Aqui comienza filtro	-->

	<div class="col-md-12 form-horizontal" style="display:none;" id="tab_filtro">      
						
					<h4>Filtros</h4>	
					<hr style="padding: 0px; margin: 15px;"/>					

					<div  class="row">
							
							


							<!--Tipos de almacen -->
							<div class="col-xs-12 col-sm-6 col-md-2">
								<div class="form-group">
									<label for="almacen" class="col-sm-12 col-md-12">Almacén</label>
									<div class="col-sm-12 col-md-12">
				
									    <?php if  ( $this->session->userdata( 'id_perfil' ) != 2  ) { ?>
											 <fieldset class="disabledme">				
										<?php } else { ?>	
											 <fieldset class="disabledme" disabled>
										<?php } ?>	

												<select name="id_almacen_historicos" vista="pedido_compra" id="id_almacen_historicos" class="form-control ttip" title="Seleccione el almacén del producto a consultar.">
												
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

							<!--Rango de fecha -->
							<div class="col-xs-12 col-sm-6 col-md-3">
									<label id="label_proveedor" for="descripcion" class="col-sm-12 col-md-12">Rango de fecha</label>
									<div class="input-prepend input-group  form-group" style="padding-left:15px !important;padding-right:15px !important;">
			                       		<span class="add-on input-group-addon"><i class="glyphicon glyphicon-calendar fa fa-calendar"></i></span>
										<input id="foco_historicos" vista="pedido_compra" type="text" name="permisos"  class="form-control col-sm-12 col-md-12 fecha_historicos ttip" title="Seleccione un rango de fechas para filtrar los resultados." value="" format = "DD-MM-YYYY"/> 
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

                           <div class="col-md-4 leyenda_devolucion"  style="display: none;"><span> Productos Devueltos</span><div style="margin-right: 15px;float:left;background-color:#ab1d1d;width:15px;height:15px;"></div> </div>

                           <div class="col-md-7">		
	                           <div class="col-md-4 leyen_home" style="display: block;" ><span> Apartados</span><div style="margin-right: 15px;float:left;background-color:#14b80f;width:15px;height:15px;"></div></div>
							   <div class="col-md-4 leyen_home"  style="display: block;"><span> Devoluciones</span><div style="margin-right: 15px;float:left;background-color:#ab1d1d;width:15px;height:15px;"></div> </div>
							   <div class="col-md-4 leyen_home"  style="display: block;"><span> Traspasos en proceso</span><div style="border: 1px solid black; margin-right: 15px;float:left;background-color:#fcf8e3;width:15px;height:15px;"></div> </div>
						   </div>

							<div class="col-md-8">		
		                           <div class="col-md-4 leyenda"  style="display: none;"><span> Apartado Individual</span><div style="margin-right: 15px;float:left;background-color:#ab1d1d;width:15px;height:15px;"></div> </div>
		                           <div class="col-md-4 leyenda" style="display: none;" ><span> Apartado Confirmado</span><div style="margin-right: 15px;float:left;background-color:#f1a914;width:15px;height:15px;"></div></div>
								   <div class="col-md-4 leyenda" style="display: none;" ><span> Disponibilidad Salida</span><div style="margin-right: 15px;float:left;background-color:#14b80f;width:15px;height:15px;"></div></div>
							</div>
							<br/>	
						   <hr style="padding: 0px; margin: 8px;"/>					
						   	<div class="notif-bot-pedidos"></div>
							
								<div class="col-sm-4 col-md-4 marginbuttom">
									<a href="nuevo_pedido_compra/<?php echo base64_encode($_SERVER["REQUEST_URI"]); ?>" id="nuevo_pedido_compra" type="button" class="btn btn-success btn-block">Nuevo Pedido de Compra</a>
								</div>

							<fieldset id="disa_reportes" disabled>
								<div class="col-sm-4 col-md-4 marginbuttom">
									<a id="impresion_reporte_costo" type="button" class="btn btn-success btn-block">Imprimir</a>
								</div>

								<div class="col-sm-4 col-md-4 marginbuttom">
									<a id="exportar_reportes_costo" type="button" class="btn btn-success btn-block">Exportar</a>
								</div>

							</fieldset>			
							<br>
								<!-- Segunda tabla-->
									
										
										<h4>Pedidos en proceso de revisión</h4>	
										<br>	
										<div class="table-responsive">
											<section>
												<table id="tabla_pedido_compra" class="display table table-striped table-bordered table-responsive" cellspacing="0" width="100%">
													<thead>
														<tr>
															<th width="10%">Nro. Movimiento</th>
															<th width="5%">Consecutivo cambio</th>
															<th  width="10%">Fecha</th>
															<th  width="10%">Nro. Control</th>
															<th  width="10%">Almacén</th>
															<th  width="25%">Comentario</th>
															<th  width="10%">Importe</th>
															<th  width="10%">Recorrido</th>
															<th width="10%">Revisar</th>
															<th width="10%">Cancelar Pedido</th>
														</tr>
													</thead>
												</table>
											</section>
										</div>
									


						</div>
						
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
						<span id="total_total"></span>			
					</div>	

				</div>		



				
				



					<input type="hidden" id="referencia" name="referencia" value="">
					<input type="hidden" id="codigo_original" name="codigo_original" value="">

						

						<div class="row">

								<input type="hidden" id="botones" name="botones" value="existencia">
						</div>
						<br><br>

						
						<div class="row">
							<div class="col-sm-8 col-md-8"></div>
							<div class="col-sm-4 col-md-4">
								<a href="<?php echo base_url(); ?>" type="button" class="btn btn-danger btn-block">Regresar</a>
							</div>
						</div>
						<br/>
				


			</div>

	</div>

</div>

<div class="modal fade bs-example-modal-lg" id="modalMessage" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
        <div class="modal-content"></div>
    </div>
</div>	

<?php $this->load->view( 'footer' ); ?>



<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php $this->load->view( 'header' ); ?>

<?php 
   $coleccion_id_operaciones= json_decode($this->session->userdata('coleccion_id_operaciones')); 
   if ( (count($coleccion_id_operaciones)==0) || (!($coleccion_id_operaciones)) ) {
        $coleccion_id_operaciones = array();
   }   

 $perfil= $this->session->userdata('id_perfil'); 
 $id_almacen=$this->session->userdata('id_almacen');

	$config_almacen = $this->session->userdata( 'config_almacen' );
	$el_perfil = $this->session->userdata( 'id_perfil' );


?>


<input type="hidden" id="mi_perfil" name="mi_perfil" value="<?php echo $this->session->userdata( 'id_perfil' ); ?>">
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
							type="button" class="btn <?php echo ($modulo==1) ? 'btn-warning': 'btn-info'; ?> btn-block ttip" title="Se hizo un pedido y esta esperando a que el admin lo revise, O el almacenista hizo la modificacion .">Revisión - Admin. <span class="etiq_btn1"><?php echo "(".$cant[1].")"; ?></span>
						</a>
					</div>


					<div class="col-xs-12 col-sm-3 col-md-2">
						<label for="descripcion" class="col-sm-12 col-md-12"></label>
						<a href="<?php echo base_url(); ?>solicitar_modificacion"  
							type="button" class="btn <?php echo ($modulo==2) ? 'btn-warning': 'btn-info'; ?> btn-block ttip" title="El admin pide modificar">Revisión - Alm. <span class="etiq_btn2"><?php echo "(".$cant[2].")"; ?></span>
						</a>
					</div>



					<div class="col-xs-12 col-sm-3 col-md-2">
						<label for="descripcion" class="col-sm-12 col-md-12"></label>
						<a href="<?php echo base_url(); ?>aprobado"  
							type="button" class="btn <?php echo ($modulo==3) ? 'btn-warning': 'btn-info'; ?> btn-block ttip" title="Imprimir y pasarlo al historico.">Aprobados <span class="etiq_btn3"><?php echo "(".$cant[3].")"; ?></span>
						</a>
					</div>

					<div class="col-xs-12 col-sm-3 col-md-2">
						<label for="descripcion" class="col-sm-12 col-md-12"></label>
						<a href="<?php echo base_url(); ?>cancelado"  
							type="button" class="btn <?php echo ($modulo==4) ? 'btn-warning': 'btn-info'; ?> btn-block ttip" title="Ver listado de cancelados.">Cancelados <span class="etiq_btn4"><?php echo "(".$cant[4].")"; ?></span>
						</a>
					</div>

					


					<div class="col-xs-12 col-sm-3 col-md-2">
						<label for="descripcion" class="col-sm-12 col-md-12"></label>
						<a href="<?php echo base_url(); ?>gestionar_pedido_compra"  
							type="button" class="btn <?php echo ($modulo==5) ? 'btn-warning': 'btn-info'; ?> btn-block ttip" title="Todos los que fueron aprobados por el admin y confirmado en 'aprobado' por el almacenista.">Histórico <span class="etiq_btn5"><?php echo "(".$cant[5].")"; ?></span>
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
							<div class="col-xs-12 col-sm-6 col-md-2" <?php echo 'style="display:'.( (($config_almacen->activo==0) && ($el_perfil==2) ) ? 'none':'block').'"'; ?>>
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

                           				
						   	<div class="notif-bot-pedidos"></div>
							
								<div class="col-sm-4 col-md-4">
									<a href="nuevo_pedido_compra/<?php echo base64_encode($_SERVER["REQUEST_URI"]); ?>" id="nuevo_pedido_compra" type="button" class="btn btn-success btn-block">Nuevo Pedido de Compra</a>
								</div>
									
								<div class="col-sm-12 col-md-12">
									<br/>
									<h4>Pedidos Revisión de revisión</h4>	
								</div>											
										
										<br>	
										<div class="col-md-12">
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
															<th  width="10%">Proveedor</th>
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
		
				<div class="row bloque_totales" <?php echo 'style="display:'.( ( $el_perfil!=2 ) ? 'block':'none').'"'; ?> >												
					<div class="col-sm-0 col-md-4">	
					  
					</div>	
					<div class="col-sm-3 col-md-2">	
					  <b>Importes por Página</b>
					</div>	

					<div class="col-sm-3 col-md-2">	
						<span id="total"></span>			
					</div>	
				</div>			

				<div class="row bloque_totales" <?php echo 'style="display:'.( ( $el_perfil!=2 ) ? 'block':'none').'"'; ?> >								
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



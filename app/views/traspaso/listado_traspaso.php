<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php $this->load->view( 'header' ); ?>
<?php 
	  $perfil= $this->session->userdata('id_perfil'); 
	  $coleccion_id_operaciones= json_decode($this->session->userdata('coleccion_id_operaciones')); 

	  if ( (count($coleccion_id_operaciones)==0) || (!($coleccion_id_operaciones)) )  
	  		{
	  			$coleccion_id_operaciones = array();
	  		} 	



   $id_almacen=$this->session->userdata('id_almacen');
	$config_almacen = $this->session->userdata( 'config_almacen' );
	$el_perfil = $this->session->userdata( 'id_perfil' );


?>	



<div class="container margenes">
		<div class="panel panel-primary">
			<div class="panel-heading">Gestión de Traspasos</div>
			<div class="panel-body">




		<div class="notif-bot-tienda"></div>
		<div class="notif-bot-vendedor"> </div>


				
		<div class="container row">




		   <div class="col-md-5" <?php echo 'style="display:'.( (($config_almacen->activo==0) && ($el_perfil==2) ) ? 'none':'block').'"'; ?>>

				<input type="hidden" id="mi_perfil" name="mi_perfil" value="<?php echo $this->session->userdata( 'id_perfil' ); ?>">

					
					    
							<label for="id_almacen_traspaso" class="col-sm-3 col-md-3 control-label">Almacén</label>
							<div class="col-sm-9 col-md-8">
							    <!--Los administradores o con permisos de entrada 
							    							****2121 sistema.js por ajax deshabilita sino hay en la regilla 
							    	que no sean almacenista 
							    	ENTONCES lista editable -->
							    <?php if (( ( $this->session->userdata( 'id_perfil' ) == 1  ) || (in_array(26, $coleccion_id_operaciones)) )  &&  (( $this->session->userdata( 'id_perfil' ) != 2 ) ) ){ ?>
									 <fieldset class="disabled_almacen">				
								<?php } else { ?>	
									 <fieldset class="disabled_almacen" disabled>
								<?php } ?>	
											<select name="id_almacen_traspaso" id="id_almacen_traspaso" class="form-control">
												
												<!--	<option value="0">Todos</option> -->
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



		    <div class="col-md-7">

				<div class="col-md-4 ttip" title="Producto en espera de confirmación total del apartado."><span> Apartado Individual</span><div style="margin-right: 15px;float:left;background-color:#ab1d1d;width:15px;height:15px;"></div> </div>
				<div class="col-md-4 ttip" title="El apartado ha sido generado."><span> Apartado Confirmado</span><div style="margin-right: 15px;float:left;background-color:#f1a914;width:15px;height:15px;"></div></div>
				<div class="col-md-4 ttip" title="Indica que se puede procesar la salida del apartado."><span> Disponibilidad Salida</span><div style="margin-right: 15px;float:left;background-color:#14b80f;width:15px;height:15px;"></div></div>
			</div>		




			<hr/>




	
		<?php if ( ( $perfil != 3 ) ) { ?>		 
			<div class="table-responsive">
				<h4>Traspasos en proceso</h4>	


				<!--Tipos de factura -->
				<div class="col-xs-12 col-sm-6 col-md-4">
				<label for="id_tipo_factura_traspaso" id="label_factura_traspaso" class="col-sm-3 col-md-12">Todos</label>
						<div class="col-sm-12 col-md-12">

								<select name="id_tipo_factura_historicos" vista="listado_traspaso" id="id_tipo_factura_historicos" class="form-control">
									<option value="0">Todos</option>
										<?php foreach ( $facturas as $factura ){ ?>
													<option value="<?php echo $factura->id; ?>"><?php echo $factura->tipo_factura; ?></option>
										<?php } ?>
									<!--rol de usuario -->
								</select>
						</div>
				</div>		


				<!--Rango de fecha -->
				<div class="col-xs-12 col-sm-6 col-md-3">
						<label id="label_proveedor" for="descripcion" class="col-sm-12 col-md-12">Rango de fecha</label>
						<div class="input-prepend input-group  form-group" style="padding-left:15px !important;padding-right:15px !important;">
                       		<span class="add-on input-group-addon"><i class="glyphicon glyphicon-calendar fa fa-calendar"></i></span>
							<input id="foco_historicos" vista="listado_traspaso" type="text" name="permisos"  class="form-control col-sm-12 col-md-12 fecha_historicos ttip" title="Seleccione un rango de fechas para filtrar los resultados." value="" format = "DD-MM-YYYY"/> 
						</div>	
                </div>
               


				<br>			
				<section>
					<table id="tabla_general_traspaso" class="display table table-striped table-bordered table-responsive " cellspacing="0" width="100%">

					</table>
				</section>
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





			<div class="table-responsive">
				<h4>Histórico de Traspasos</h4>	

					<div class="col-sm-4 col-md-4 marginbuttom">
						<a id="impresion_traspaso_historico" type="button" class="btn btn-success btn-block">Imprimir</a>
					</div>

				<br>			
				<section>
					<table id="tabla_traspaso_historico" class="display table table-striped table-bordered table-responsive" cellspacing="0" width="100%">

					</table>
				</section>
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

		<?php } ?>		

	
</div>
<hr>

<div class="row">
	<div class="col-sm-8 col-md-8"></div>

	<div class="col-sm-4 col-md-4">
		<a href="<?php echo base_url().'reportes'; ?>" type="button" class="btn btn-danger btn-block">Regresar</a>
	</div>	
	
</div>

</div>
</div>

<div class="modal fade bs-example-modal-lg" id="modalMessage" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content"></div>
	</div>
</div>	



<?php $this->load->view( 'footer' ); ?>
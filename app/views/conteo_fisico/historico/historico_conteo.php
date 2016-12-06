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
	
	/*
	$dato['modulo']=1;
	$dato['cant']['1']=1;
	$dato['cant']['2']=1;
	$dato['cant']['3']=1;
	$dato['cant']['4']=1;
	$dato['cant']['5']=1;
	$dato['cant']['6']=1;
	*/

	$dato['config_almacen']=$config_almacen;
	$dato['el_perfil']=$el_perfil;
	$dato['productos']=$productos;
	$dato['almacenes']=$almacenes;

	$dato['id_almacen']=$id_almacen;


		$id_almacen_ajuste = 	$this->session->userdata( 'id_almacen_ajuste' );

	
	


?>


<div class="container margenes">
	<div class="panel panel-primary">
		<div id="label_reporte" class="panel-heading">Conteo físico de inventario</div>
			<div class="container">	
				<br>

					

						


<!-- Aqui comienza filtro	-->
				<div class="row">
					<div id="disponibilidad"  class="col-xs-12 col-sm-3 col-md-2 marginbuttom">
								<button  id="ver_filtro" type="button" class="btn btn-success btn-block ttip" title="Mostrar u ocultar filtros.">Filtros</button>
					</div>
					<fieldset id="imp_historico_conteo" style="display:block;">
						<div class="col-sm-3 col-md-3">
							<a  id="imprimir_historico_conteo" type="button" class="btn btn-success btn-block">Imprimir</a>
						</div>
					</fieldset>	

				</div>

				<div class="col-md-12 form-horizontal" style="display:none;" id="tab_filtro">      
						
					<h4>Filtros</h4>	
					<hr style="padding: 0px; margin: 15px;"/>					

					<div  class="row">
							
							
							<!--Tipos de factura -->
							<div class="col-xs-12 col-sm-6 col-md-2">
							    
								<label for="id_factura_historicos" class="col-sm-3 col-md-12">Tipo de factura</label>
								<div class="col-sm-9 col-md-12">
								    			
									<select name="id_factura_historicos" vista="<?php echo $vista; ?>"  id="id_factura_historicos" class="form-control">

										<?php if ( ( $el_perfil == 1 ) || ((in_array(29, $coleccion_id_operaciones)) 
										 		&& (in_array(30, $coleccion_id_operaciones)) )														 ) { ?>
											<option value="0">Todos</option>
										<?php } ?>	

										<?php foreach ( $facturas as $factura ){ ?>
										<?php if ( ( $el_perfil == 1 ) || ( (in_array(29, $coleccion_id_operaciones) && ($factura->id==1) ) 	|| (in_array(30, $coleccion_id_operaciones) && ($factura->id==2) ) )) { ?>
										   <option value="<?php echo $factura->id; ?>" ><?php echo $factura->tipo_factura; ?></option>
											<?php } ?>
											   
										<?php } ?>
										<!--rol de usuario -->
									</select>
								</div>
							</div>	


							
							<input type="hidden" id="mi_perfil" name="mi_perfil" value="<?php echo $this->session->userdata( 'id_perfil' ); ?>">
							<!--Tipos de almacen -->
							<div id="almacen_id" class="col-xs-12 col-sm-6 col-md-2" <?php echo 'style="display:'.( (($config_almacen->activo==0) && ($el_perfil==2) ) ? 'none':'block').'"'; ?>>
								<div class="form-group">
									<label for="almacen" class="col-sm-12 col-md-12">Almacén</label>
									<div class="col-sm-12 col-md-12">
				
									    <?php if  ( $this->session->userdata( 'id_perfil' ) != 2  ) { ?>
											 <fieldset class="disabledme">				
										<?php } else { ?>	
											 <fieldset class="disabledme" disabled>
										<?php } ?>	



												
										<select name="id_almacen_historicos" vista="<?php echo $vista; ?>" id="id_almacen_historicos" class="form-control ttip" title="Seleccione el almacén del producto a consultar.">
												
													<option value="0">Todos</option>

														<?php foreach ( $almacenes as $almacen ){ ?>
															<?php 
															if  (($almacen->id_almacen==$id_almacen_ajuste) ) 
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
									<label id="label_proveedor" for="descripcion" class="col-sm-12 col-md-12">Rango de fecha de creación</label>
									<div class="input-prepend input-group  form-group" style="padding-left:15px !important;padding-right:15px !important;">
			                       		<span class="add-on input-group-addon"><i class="glyphicon glyphicon-calendar fa fa-calendar"></i></span>
										<input id="foco_historicos" vista="<?php echo $vista; ?>"  type="text" name="permisos"  class="form-control col-sm-12 col-md-12 fecha_historicos ttip" title="Seleccione un rango de fechas para filtrar los resultados." value="" format = "DD-MM-YYYY"/> 
									</div>	
			                </div>

							<div id="proveedor_id" class="col-xs-12 col-sm-6 col-md-3">

											<div class="form-group">
												<label id="label_proveedor" for="descripcion" class="col-sm-12 col-md-12">Proveedor</label>
												<div class="col-sm-12 col-md-12">
													 <input  type="text" name="editar_proveedor_historico" id="editar_proveedor_historico" vista="<?php echo $vista; ?>"  idproveedor="1" class="form-control buscar_proveedor_historico ttip" title="Campo predictivo. Comience a escribir y seleccione una opción para agregar un filtro de selección." autocomplete="off" spellcheck="false" placeholder="Buscar...">
												</div>
											</div>
									
								</div>		


		            </div>     

		            <hr style="padding: 0px; margin: 15px;"/>					
				</div>

<!-- Hasta aqui el filtro	-->	                     




			<div class="col-md-12 conteo_principal" >	
				<hr style="padding: 0px; margin: 8px;"/>					

				<div class="row">	
					<div class="col-md-12">	
					
						<div class="table-responsive">

							<section>
								<table id="tabla_historico_conteo" class="display table table-striped table-bordered table-responsive" cellspacing="0" width="100%">
									<thead>
										<tr>
							                <th rowspan="2" width="5%" class="text-center">Mov.</th>
							                <th rowspan="2" width="36%" class="text-center">Filtro</th>
							                <th colspan="3" width="9%" class="text-center">Conteos</th>
							                <th colspan="3" width="25%" class="text-center">Faltante</th>
							                <th colspan="3" width="25%" class="text-center">Sobrante</th>
							                




							            </tr>									
										<tr>
											<th class="text-center"><strong>1</strong></th>
											<th class="text-center"><strong>2</strong></th>
											<th class="text-center"><strong>3</strong></th>
											
											<th class="text-center"><strong>Status</strong></th>
											<th class="text-center"><strong>Realizado</strong></th>
											<th class="text-center"><strong>Mov.</strong></th>

											<th class="text-center"><strong>Status</strong></th>
											<th class="text-center"><strong>Realizado</strong></th>
											<th class="text-center"><strong>Mov.</strong></th>
											

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
							<div class="col-sm-8 col-md-8"></div>
							<div class="col-sm-4 col-md-4">
								<a href="<?php echo base_url(); ?>conteos_opciones" type="button" class="btn btn-danger btn-block">Regresar</a>
							</div>
						</div>
						<br/>
				


			</div>

	</div>

</div>


<?php $this->load->view( 'footer' ); ?>

<div class="modal fade bs-example-modal-lg" id="modalMessage" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content"></div>
	</div>
</div>	
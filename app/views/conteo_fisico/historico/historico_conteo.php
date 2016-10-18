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




					<fieldset id="imp_historico_conteo" style="display:block;">
						<div class="col-sm-3 col-md-3">
							<label for="descripcion" class="col-sm-12 col-md-12"></label>
							<a id="imprimir_historico_conteo" href=""  
								type="button" class="btn btn-success btn-block" target="_blank">Imprimir
							</a>
						</div>
					</fieldset>					



			<div class="col-md-12 conteo_principal" >	
				<hr style="padding: 0px; margin: 8px;"/>					

				<div class="row">	
					<div class="col-md-12">	
					
						<div class="table-responsive">

							<section>
								<table id="tabla_historico_conteo" class="display table table-striped table-bordered table-responsive" cellspacing="0" width="100%">
									<thead>
										<tr>
							                <th rowspan="2" width="9%" class="text-center">Movimiento</th>
							                <th rowspan="2" width="7%" class="text-center">Conteo1</th>
							                <th rowspan="2" width="7%" class="text-center">Conteo2</th>
							                <th rowspan="2" width="7%" class="text-center">Conteo3</th>
							                <th colspan="3" width="35%" class="text-center">Faltante</th>
							                <th colspan="3" width="35%" class="text-center">Sobrante</th>
							                




							            </tr>									
										<tr>
											
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
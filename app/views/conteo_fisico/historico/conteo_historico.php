<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php $this->load->view( 'header' ); ?>

<?php 
   $coleccion_id_operaciones= json_decode($this->session->userdata('coleccion_id_operaciones')); 
   if ( (count($coleccion_id_operaciones)==0) || (!($coleccion_id_operaciones)) ) {
        $coleccion_id_operaciones = array();
   }   



	$config_almacen = $this->session->userdata( 'config_almacen' );
	$el_perfil = $this->session->userdata( 'id_perfil' );
	$dato['config_almacen']=$config_almacen;
	$dato['el_perfil']=$el_perfil;

?>


<div class="container margenes">
	<div class="panel panel-primary">
		<div id="label_reporte" class="panel-heading">Conteo físico de inventario</div>
			<div class="container">	
				<br>


					<fieldset id="imp_historico_conteo" style="display:block;">
						<div class="col-sm-3 col-md-3">
							<label for="descripcion" class="col-sm-12 col-md-12"></label>
							<a id="imprimir_conteos_historicos" href="/generar_conteos_historico/<?php echo base64_encode($id_almacen); ?>/<?php echo base64_encode($modulo); ?>/<?php echo base64_encode($movimiento); ?>"  
								type="button" class="btn btn-success btn-block" target="_blank">Imprimir
							</a>
						</div>
					</fieldset>	







			<div class="col-md-12">	
				<hr style="padding: 0px; margin: 8px;"/>					

				<div class="row">	
					<div class="col-md-12">	
					
						<div class="table-responsive">

							<section>
								<table id="tabla_conteo_historico" class="display table table-striped table-bordered table-responsive" cellspacing="0" width="100%">
									<thead>
										<tr>
											<th class="text-center " width="15%"><strong>Referencia</strong></th>
											<th class="text-center " width="25%"><strong>Nombre de Tela</strong></th>
											<th class="text-center " width="15%"><strong>Imagen</strong></th>
											<th class="text-center " width="10%"><strong>Color</strong></th>
											<th class="text-center " width="15%"><strong>Composición</strong></th>
											<th class="text-center " width="10%"><strong>Calidad</strong></th>
											<th class="text-center " width="10%"><strong>Cantidad</strong></th>

										</tr>
									</thead>
								</table>
							</section>
		                           
									
							
					
						</div>
						
					</div>	
				</div>	
				
				<br/>
		
					
					<input type="hidden" id="modulo" name="modulo" value="<?php echo $modulo ?>">
					<input type="hidden" id="id_almacen" name="id_almacen" value="<?php echo $id_almacen ?>">
					<input type="hidden" id="movimiento" name="movimiento" value="<?php echo $movimiento ?>">

						

						<div class="row">

								<input type="hidden" id="botones" name="botones" value="existencia">
						</div>
						<br><br>

						
						<div class="row">
							<div class="col-sm-8 col-md-8 hab_proceso"></div>
							<div class="col-sm-4 col-md-4">
								<a href="<?php echo base_url(); ?>historico_conteo" type="button" class="btn btn-danger btn-block">Regresar</a>
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
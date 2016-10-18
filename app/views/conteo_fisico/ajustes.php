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
	$dato['cant']['6']=1;*/

	$dato['config_almacen']=$config_almacen;
	$dato['el_perfil']=$el_perfil;
	$dato['productos']=$productos;
	$dato['almacenes']=$almacenes;

	$dato['id_almacen']=$id_almacen;

    $id_almacen_ajuste = $this->session->userdata('id_almacen_ajuste');		


?>


<div class="container margenes">
	<div class="panel panel-primary">
		<div id="label_reporte" class="panel-heading">Conteo físico de inventario</div>
			<div class="container">	
				<br>

					<?php $this->load->view( 'conteo_fisico/botones',$dato ); ?>			








			<div class="col-md-12">	
				<hr style="padding: 0px; margin: 8px;"/>					

				<div class="row">	
					<div class="col-md-12">	
					
						<div class="table-responsive">

							<section>
								<table id="tabla_ajustes" class="display table table-striped table-bordered table-responsive" cellspacing="0" width="100%">
									<thead>
										<tr>
											<th class="text-center " width="15%"><strong>Referencia</strong></th>
											<th class="text-center " width="25%"><strong>Nombre de Tela</strong></th>
											<th class="text-center " width="15%"><strong>Imagen</strong></th>
											<th class="text-center " width="10%"><strong>Color</strong></th>
											<th class="text-center " width="15%"><strong>Composición</strong></th>
											<th class="text-center " width="10%"><strong>Calidad</strong></th>
											<th class="text-center " width="10%"><strong>Cantidad Real</strong></th>
											<th class="text-center " width="10%"><strong>Cantidad Ajustar</strong></th>

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
							<div class="col-sm-8 col-md-4 hab_proceso"></div>
							<div class="col-sm-4 col-md-4">
								<a href="<?php echo base_url(); ?>conteos_opciones" type="button" class="btn btn-danger btn-block">Regresar</a>
							</div>

							
								<div class="col-sm-4 col-md-4">
									<fieldset id="hab_proceso" style="display:block;" >
										<!--<button type="button"  class="btn btn-success btn-block ttip" title="Cambiar el estatus del pedido para poder ser procesado en la salida." id="procesar_contando">
											<span>Procesar conteo</span>
										</button>-->


										<?php if ($dato['modulo']==5) { ?>
											<a href="<?php echo base_url(); ?>salida_faltante/<?php echo base64_encode($dato['modulo']).'/'.base64_encode("faltante"); ?>" type="button" class="btn btn-success btn-block">Procesar conteo</a>
										<?php } else { ?>
											<a href="<?php echo base_url(); ?>entrada_sobrante/<?php echo base64_encode($dato['modulo']).'/'.base64_encode("sobrante"); ?>" type="button" class="btn btn-success btn-block">Procesar conteo</a>
										<?php } ?>
										
									</fieldset>	
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
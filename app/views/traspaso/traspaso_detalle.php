<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php $this->load->view( 'header' ); ?>
<div class="container margenes">


<input type="hidden" id="consecutivo_traspaso" name="consecutivo_traspaso" value="<?php echo $consecutivo_traspaso; ?>">

		<div class="panel panel-primary">
			<div class="panel-heading">Detalles de traspaso</div>
			<div class="panel-body">

					<div class="row">
						<div class="col-sm-4 col-md-2">
							<div class="form-group">
								<label for="label_consecutivo_traspaso" id="label_consecutivo_traspaso" class="col-sm-12 col-md-12">Num. Traspaso</label>
									<input type="text" disabled class="form-control" id="etiq_consecutivo_traspaso" name="etiq_consecutivo_traspaso" placeholder="">
							</div>
						</div>		

						<div class="col-sm-4 col-md-2">
							<div class="form-group">
								<label for="label_proceso" id="label_proceso" class="col-sm-12 col-md-12">Proceso</label>
									<input type="text" disabled class="form-control" id="etiq_proceso" name="etiq_proceso" placeholder="">
							</div>
						</div>		


						<div class="col-sm-4 col-md-2">
							<div class="form-group">
								<label for="label_traspaso" id="label_traspaso" class="col-sm-12 col-md-12">Traspaso</label>
									<input type="text" disabled class="form-control" id="etiq_traspaso" name="etiq_traspaso" placeholder="">
							</div>
						</div>		

						<div class="col-sm-4 col-md-2" >
							<div class="form-group">
								<label for="descripcion" class="col-sm-12 col-md-12">Fecha</label>
									<input type="text" disabled class="form-control" id="etiq_fecha" name="etiq_fecha" placeholder="10/10/15">
							</div>
						</div>



						<div class="col-sm-4 col-md-4" >
							<div class="form-group">
								<label for="motivos" id="label_motivos" class="col-sm-12 col-md-12">Motivos</label>
									<fieldset style="border:0px; " class="form-control" id="etiq_motivos" name="etiq_motivos" placeholder=""></fieldset> 
							</div>
						</div>

					    <div class="col-sm-4 col-md-3">
							<div class="form-group">
								<label for="label_responsable" id="label_responsable" class="col-sm-12 col-md-12">Responsable</label>
									<input type="text" disabled class="form-control" id="etiq_responsable" name="etiq_responsable" placeholder="">
							</div>
						</div>

					    <div class="col-sm-4 col-md-3">
							<div class="form-group">
								<label for="label_dependencia" id="label_dependencia" class="col-sm-12 col-md-12">dependencia</label>
									<input type="text" disabled class="form-control" id="etiq_dependencia" name="etiq_dependencia" placeholder="">
							</div>
						</div>		
						
					    <div class="col-sm-4 col-md-2">
							<div class="form-group">
								<label for="label_almacen" id="label_almacen" class="col-sm-12 col-md-12">Almacén</label>
									<input type="text" disabled class="form-control" id="etiq_almacen" name="etiq_almacen" placeholder="">
							</div>
						</div>		



					</div>	
					
				<div class="col-sm-1 col-md-1"> 
					<div style="margin-right: 15px;float:left;background-color:#f2dede;width:15px;height:15px;"></div>
				</div>Fueron traspasados					
	


	<hr/>



	<div class="container row">					
		<div class="col-md-12">		
					  
						
						<div class="table-responsive">
							<section>
								<table id="traspaso_historico_detalle" class="display table table-striped table-bordered table-responsive " cellspacing="0" width="100%">
								</table>
							</section>
						</div>		
						<br/>
						
						<div class="col-md-12">	
							<div class="row">
								<div class="col-sm-6 col-md-6"></div>

								<div class="col-sm-3 col-md-3">
									<label for="descripcion" class="col-sm-12 col-md-12"></label>
									<a href="<?php echo base_url(); ?>imprimir_detalle_historico_traspaso/<?php echo base64_encode($consecutivo_traspaso); ?>"  
										type="button" class="btn btn-success btn-block" target="_blank">Imprimir
									</a>
								</div>

								<div class="col-sm-3 col-md-3">
									<a href="<?php echo base_url(); ?>listado_traspaso" type="button" class="btn btn-danger btn-block">Regresar</a>
								</div>	
	
							</div>	
						</div>
						

		</div>
	</div>

</div>
</div>
<?php $this->load->view( 'footer' ); ?>
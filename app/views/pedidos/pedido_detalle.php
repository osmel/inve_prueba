<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php $this->load->view( 'header' ); ?>
<div class="container margenes">
	<input type="hidden" id="id_almacen_pedido" name="id_almacen_pedido" value="<?php echo $id_almacen; ?>">		

		<div class="panel panel-primary">
			<div class="panel-heading">Detalles de pedido &nbsp;&nbsp;&nbsp;<?php echo "<b>ALM:</b> ". $almacen; ?></div>
			<div class="panel-body">
				
		

					<div class="row">
						<div class="col-sm-4 col-md-3">
							<div class="form-group">
								<label for="descripcion" class="col-sm-12 col-md-12">Num. Mov</label>
									<input type="text" disabled class="form-control" id="etiq_num_mov" name="etiq_num_mov" placeholder="">
							</div>
						</div>		

						<div class="col-sm-4 col-md-4">
							<div class="form-group">
								<label for="descripcion" class="col-sm-12 col-md-12">Cliente</label>
									<input type="text" disabled class="form-control" id="etiq_cliente" name="etiq_cliente" placeholder="34534534554">
							</div>
						</div>							



						<div class="col-sm-4 col-md-5" >
							<div class="form-group">
								<label for="descripcion" class="col-sm-12 col-md-12">Dependencia</label>
									<input type="text" disabled class="form-control" id="etiq_dependencia" name="etiq_dependencia" placeholder="">
							</div>
						</div>
					
						<div class="col-sm-4 col-md-4" >
							<div class="form-group">
								<label for="descripcion" class="col-sm-12 col-md-12">Fecha</label>
									<input type="text" disabled class="form-control" id="etiq_fecha" name="etiq_fecha" placeholder="10/10/15">
							</div>
						</div>
						
						<div class="col-sm-4 col-md-4" >
							<div class="form-group">
								<label for="descripcion" class="col-sm-12 col-md-12">Hora</label>
									<input type="text" disabled class="form-control" id="etiq_hora" name="etiq_hora" placeholder="9:05am">
							</div>
						</div>		


						<div class="col-sm-4 col-md-4" >

								<div class="col-sm-12 col-md-12" style="height: 33px;"> </div>

							
								<div class="col-sm-1 col-md-1" id="etiq_color_apartado"> 
								</div>

								<label for="descripcion" class="col-sm-10 col-md-10">
									<span id="etiq_tipo_apartado" ></span>
								</label>
							
						</div>									




				</div>		

				<div class="col-sm-1 col-md-1"> 
					<div style="margin-right: 15px;float:left;background-color:#f2dede;width:15px;height:15px;"></div>
				</div>Serán traspasados


	<hr/>



	<div class="row">					
		<div class="col-md-12">		
					  
						<input type="hidden" id="num_mov" value="<?php echo $num_mov ; ?>">

						<div class="table-responsive">
							<section>
								<table id="pedido_detalle" class="display table table-striped table-bordered table-responsive " cellspacing="0" width="100%">
								</table>
							</section>
						</div>		
								<hr/>

						
							<div class="row">
								<div class="col-sm-3 col-md-3 marginbuttom">
									<label for="descripcion" class="col-sm-12 col-md-12"></label>
									<a href="<?php echo base_url(); ?>generar_pedido_especifico/<?php echo base64_encode($num_mov); ?>/<?php echo base64_encode(1); ?>/<?php echo base64_encode(0); ?>/<?php echo base64_encode($id_almacen); ?>/<?php echo base64_encode(0); ?>"  
										type="button" class="btn btn-success btn-block ttip" target="_blank" title="Generar un PDF del pedido para impresión.">Imprimir
									</a>
								</div>

								
								
								<div class="col-sm-3 col-md-3 marginbuttom">
									<button type="button"  class="btn btn-success btn-block ttip" title="Cambiar el estatus del pedido para que NO sea visible y no pueda ser procesada su salida." id="excluir_pedido">
										<span>Excluir de la Salida</span>
									</button>
								</div>	
								<div class="col-sm-3 col-md-3 marginbuttom">
									<button type="button"  class="btn btn-success btn-block ttip" title="Cambiar el estatus del pedido para poder ser procesado en la salida." id="incluir_pedido">
										<span>Incluir en la Salida</span>
									</button>
								</div>			
								<div class="col-sm-3 col-md-3 marginbuttom">
									<a href="<?php echo base_url(); ?>pedidos" type="button" class="btn btn-danger btn-block">Regresar</a>
								</div>	
	
							</div>	
						

		</div>
	</div>

</div>
</div>
<?php $this->load->view( 'footer' ); ?>
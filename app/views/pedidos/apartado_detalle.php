<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php $this->load->view( 'header' ); ?>

<?php 
	  $perfil= $this->session->userdata('id_perfil'); 
	  $coleccion_id_operaciones= json_decode($this->session->userdata('coleccion_id_operaciones')); 

	  if ( (count($coleccion_id_operaciones)==0) || (!($coleccion_id_operaciones)) )  
	  		{
	  			$coleccion_id_operaciones = array();
	  		} 	


?>	

<div class="container margenes">
	<div class="panel panel-primary">
			<input type="hidden" id="id_almacen_pedido" name="id_almacen_pedido" value="<?php echo $id_almacen; ?>">

			<div class="panel-heading">Detalles de pedido &nbsp;&nbsp;&nbsp;<?php echo "<b>ALM:</b> ". $almacen; ?></div>
			<div class="panel-body">
						
			

						<div class="row">						
								<div class="col-sm-4 col-md-3">
									<div class="form-group">
										<label for="descripcion" class="col-sm-12 col-md-12">Vendedor</label>
											<input type="text" disabled class="form-control" id="etiq_usuario" name="etiq_usuario" placeholder="34534534554">
									</div>
								</div>		

								<div class="col-sm-4 col-md-4">
									<div class="form-group">
										<label for="descripcion" class="col-sm-12 col-md-12">Dependencia</label>
											<input type="text" disabled class="form-control" id="etiq_cliente" name="etiq_cliente" placeholder="34534534554">
									</div>
								</div>							

								<div class="col-sm-4 col-md-5" style="margin-top:0px;">
									<div class="form-group">
										<label for="descripcion" class="col-sm-12 col-md-12">Empresa Relacionada</label>
											<input type="text" disabled class="form-control" id="etiq_comprador" name="etiq_comprador" placeholder="Iniciativa Textil">
									</div>
								</div>
							
								<div class="col-sm-4 col-md-4" style="padding-left: 0px;">
									<div class="form-group">
										<label for="descripcion" class="col-sm-12 col-md-12">Fecha</label>
										<div class="col-sm-12 col-md-12">
											<input type="text" disabled class="form-control" id="etiq_fecha" name="etiq_fecha" placeholder="10/10/15">
										</div>
									</div>
								</div>
								
								<div class="col-sm-4 col-md-4" style="padding-left: 0px;">
									<div class="form-group">
										<label for="descripcion" class="col-sm-12 col-md-12">Hora</label>
										<div class="col-sm-12 col-md-12">
											<input type="text" disabled class="form-control" id="etiq_hora" name="etiq_hora" placeholder="9:05am">
										</div>
									</div>
								</div>		


								<div class="col-sm-4 col-md-4" style="padding-left: 0px;">
									<div class="col-sm-12 col-md-12" style="height: 33px;"> </div>
										<div class="col-sm-1 col-md-1" id="etiq_color_apartado">
									    </div>
									
										<label for="descripcion" class="col-sm-10 col-md-10">
											<span id="etiq_tipo_apartado" ></span>

										</label>
									
								</div>									

						</div>		
						<br/>
				<div class="col-sm-1 col-md-1"> 
					<div style="margin-right: 15px;float:left;background-color:#f2dede;width:15px;height:15px;"></div>
				</div>Serán traspasados		

				<div class="container1"></div>



	<hr/>




	<div class="row">	
		<div class="col-md-12">					
					  
						
						<input type="hidden" id="consecutivo_venta" value="<?php echo $consecutivo_venta ; ?>">
						<input type="hidden" id="id_usuario_apartado" value="<?php echo $id_usuario ; ?>">
						<input type="hidden" id="id_cliente_apartado" value="<?php echo $id_cliente ; ?>">

						<div class="table-responsive">
							<section>
								<table id="tabla_detalle" class="display table table-striped table-bordered table-responsive " cellspacing="0" width="100%">
								</table>
							</section>
						</div>		
								<hr/>

						
							<div class="row">
								
								<div class="col-sm-3 col-md-3 marginbuttom">
									<label for="descripcion" class="col-sm-12 col-md-12"></label>
									<a href="<?php echo base_url(); ?>generar_pedido_especifico/<?php echo base64_encode($id_usuario); ?>/<?php echo base64_encode(2); ?>/<?php echo base64_encode($id_cliente); ?>/<?php echo base64_encode($id_almacen); ?>/<?php echo base64_encode($consecutivo_venta); ?>" 
										type="button" class="btn btn-success btn-block" target="_blank">Imprimir
									</a>
								</div>


								<?php if ( ( $perfil != 3 ) ) { ?>			
									<div class="col-sm-3 col-md-3 marginbuttom">
										<button type="button"  class="btn btn-success btn-block" id="excluir_salida">
											<span>Excluir de la Salida</span>
										</button>
									</div>	
									<div class="col-sm-3 col-md-3 marginbuttom">
										<button type="button"  class="btn btn-success btn-block" id="incluir_salida">
											<span>Incluir en la Salida</span>
										</button>
									</div>			
								<?php } ?>		

								<div class="col-sm-3 col-md-3 marginbuttom">
									<a href="<?php echo base_url(); ?>pedidos" type="button" class="btn btn-danger btn-block">Regresar</a>
								</div>	
	
							</div>	
						

		</div>
	</div>

</div>
</div>
<?php $this->load->view( 'footer' ); ?>
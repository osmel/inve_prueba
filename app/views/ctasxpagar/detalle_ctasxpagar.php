<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php $this->load->view( 'header' ); ?>

<?php
	 $perfil= $this->session->userdata('id_perfil'); 
   $coleccion_id_operaciones= json_decode($this->session->userdata('coleccion_id_operaciones')); 
   if ( (count($coleccion_id_operaciones)==0) || (!($coleccion_id_operaciones)) ) {
        $coleccion_id_operaciones = array();
   }   

 	if (!isset($retorno)) {
      	$retorno ="";
    }



if (ltrim($retorno)=="") {
	$regreso = " Ir a Home";
} elseif ($retorno=="reportes") {
	$regreso = " Ir a Reportes";
} else {
		$regreso = " Regresar";
}

?>   




<input type="hidden" id="movimiento" name="movimiento" value="<?php echo $movimiento; ?>">
<input type="hidden" id="id_factura" name="id_factura" value="<?php echo $id_factura; ?>">

<div class="container margenes">

		<div class="container margenes">
			<div class="panel panel-primary">
			<div class="panel-heading">Pagos Realizados</div>
			<div class="panel-body">		

					<div class="row">
						<div class="col-sm-4 col-md-2">
							<div class="form-group">
								<label for="descripcion" class="col-sm-12 col-md-12">Num. Mov</label>
									<input type="text" disabled class="form-control" id="etiq_num_mov" name="etiq_num_mov" placeholder="">
							</div>
						</div>		

						<div class="col-sm-4 col-md-2">
							<div class="form-group">
								<label for="descripcion" class="col-sm-12 col-md-12">Almacén</label>
									<input type="text" disabled class="form-control" id="etiq_almacen" name="etiq_cliente" placeholder="">
							</div>
						</div>							



						<div class="col-sm-4 col-md-5" >
							<div class="form-group">
								<label for="descripcion" class="col-sm-12 col-md-12">Proveedor</label>
									<input type="text" disabled class="form-control" id="etiq_proveedor" name="etiq_dependencia" placeholder="">
							</div>
						</div>
					
						<div class="col-sm-4 col-md-3" >
							<div class="form-group">
								<label for="descripcion" class="col-sm-12 col-md-12">Fecha Entrada</label>
									<input type="text" disabled class="form-control" id="etiq_fecha" name="etiq_fecha" placeholder="">
							</div>
						</div>
						
						<?php if ($config_factura->activo==1) { ?>
							<div class="col-sm-4 col-md-2" >
								<div class="form-group">
									<label for="descripcion" class="col-sm-12 col-md-12">Factura</label>
										<input type="text" disabled class="form-control" id="etiq_factura" name="etiq_hora" placeholder="">
								</div>
							</div>		
						<?php } ?>




						<div class="col-sm-4 col-md-2" >
							<div class="form-group">
								<label for="descripcion" class="col-sm-12 col-md-12">Subtotal</label>
									<input type="text" disabled class="form-control" id="etiq_subtotal" name="etiq_hora" placeholder="">
							</div>
						</div>		

						<div class="col-sm-4 col-md-2" >
							<div class="form-group">
								<label for="descripcion" class="col-sm-12 col-md-12">IVA</label>
									<input type="text" disabled class="form-control" id="etiq_iva" name="etiq_hora" placeholder="">
							</div>
						</div>		
						<div class="col-sm-4 col-md-2" >
							<div class="form-group">
								<label for="descripcion" class="col-sm-12 col-md-12">Total</label>
									<input type="text" disabled class="form-control" id="etiq_total" name="etiq_hora" placeholder="">
							</div>
						</div>		

						<div class="col-sm-4 col-md-2" >
							<div class="form-group">
								<label for="descripcion" class="col-sm-12 col-md-12">Días Vencidos</label>
									<input type="text" disabled class="form-control" id="etiq_dia_vencido" name="etiq_hora" placeholder="">
							</div>
						</div>		
						<div class="col-sm-4 col-md-2" >
							<div class="form-group">
								<label for="descripcion" class="col-sm-12 col-md-12">Monto a Pagar</label>
									<input type="text" disabled class="form-control" id="etiq_monto_paga" name="etiq_hora" placeholder="">
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

				<div class="col-md-12">		


							<div class="col-sm-1 col-md-1"> 
								<div style="margin-right: 15px;float:left;background-color:#f2dede;width:15px;height:15px;"></div>
							</div>Pagos tardíos

						<!--tabla-->	


							<div class="row">
								
								<div class="table-responsive">

									<div class="row">


									<!-- si configuracion lo tiene activo y es(administrador o por el contrario tiene "permiso de ver y editar") -->
									<?php if (($configuracion->activo==1) and ( ( $perfil == 1 ) || (( (in_array(29, $coleccion_id_operaciones)) || (in_array(30, $coleccion_id_operaciones)) ) && (in_array(28, $coleccion_id_operaciones)))  ))
									 { ?> 
										<div class="col-xs-12 col-sm-4 col-md-3 marginbuttom">
											<a href="<?php echo base_url(); ?>nuevo_pago/<?php echo base64_encode($movimiento).'/'.base64_encode($id_factura); ?>" type="button" class="btn btn-success btn-block">Nuevo Pago</a>
										</div>
										<fieldset id="disa_pagosrealizado"	>
											<div class="col-sm-4 col-md-4 marginbuttom">
												<a id="impresion_ctas_detalle" type="button" class="btn btn-success btn-block impresion_ctas_detalle">Imprimir</a>
											</div>
										</fieldset>

									<?php } else { ?> 	
										<fieldset disabled>
											<div class="col-xs-12 col-sm-4 col-md-3 marginbuttom">
											<br/>
												<a href="#" type="button" class="btn btn-success btn-block">Nuevo Pago</a>
											</div>
										</fieldset>	

									<?php }  ?> 


									</div>

									<section>
										<table id="tabla_pagos_realizados" class="display table table-striped table-bordered table-responsive" cellspacing="0" width="100%">
											<thead>
												<tr>
														<th class="text-center cursora" width="25%">Instrumento  </th>
														<th class="text-center cursora" width="25%">Referencia  </th>
														<th class="text-center cursora" width="7%">Fecha  </th>
														<th class="text-center cursora" width="7%">Importe  </th>
														
														<th class="text-center cursora" width="20%">Comentario </th>
														<th class="text-center " width="8%"><strong>Editar</strong></th>
														<th class="text-center " width="8%"><strong>Eliminar</strong></th>
												</tr>
											</thead>
										</table>
									</section>

								</div>
							
								


								


							</div>				
				</div>					
			<!--fin tabla-->					


		
				<div class="row">						
					<div class="col-sm-0 col-md-10">	
					  
					</div>	
					
					<div class="col-sm-3 col-md-2">	
						<br/>
						<b>Total pagado:</b> <span id="importe_pagado"></span>			
						
					</div>	
				</div>
					





			<div class="row">

					<div class="col-sm-8 col-md-9"></div>
					<div class="col-sm-4 col-md-3" >
					<br/>
						<a href="<?php echo base_url(); ?><?php echo $retorno; ?>" class="btn btn-danger btn-block"><i class="glyphicon glyphicon-backward"></i><?php echo $regreso; ?></a>
					</div>
			</div>				





					

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
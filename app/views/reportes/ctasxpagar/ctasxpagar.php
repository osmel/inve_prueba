<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php $this->load->view( 'header' ); ?>

<?php
 	if (!isset($retorno)) {
      	$retorno ="reportes";
      	$otro_retorno='listado_notas';
      	
    }
?>   
					<div class="col-md-2" > </div>
	                <div class="col-md-2" >
	                		<span> Productos Devueltos</span>
	                			<div style="margin-right: 15px;float:left;background-color:#ab1d1d;width:15px;height:15px;"></div> 
                	</div>

<div class="container margenes">

		<div class="container margenes">
			<div class="panel panel-primary">
			<div class="panel-heading">Gestión de Cuentas por pagar</div>
			<div class="panel-body">		

			<!--tabla-->	

				<div class="col-md-12">
					
					<div class="table-responsive">

						<h4>Vencidas</h4>	
						<br>	

						<section>
							<table id="tabla_ctas_vencidas" class="display table table-striped table-bordered table-responsive" cellspacing="0" width="100%">
								<thead>
									<tr>
											<th class="text-center cursora" width="10%">Movimiento  </th>
											<th class="text-center cursora" width="10%">Tipo Pago  </th>
											<th class="text-center cursora" width="5%">Almacén  </th>
											<th class="text-center cursora" width="35%">Proveedor  </th>
											
											<th class="text-center cursora" width="10%">Fecha  </th>
											<th class="text-center cursora" width="10%">Factura  </th>
											
											<th class="text-center cursora" width="10%">Subtotal  </th>
											<th class="text-center cursora" width="10%">IVA  </th>
											<th class="text-center cursora" width="10%">Total  </th>
											<th class="text-center " width="20%"><strong>Días</strong></th>
											<th class="text-center " width="20%"><strong>Estatus</strong></th>
											<th class="text-center " width="20%"><strong>Detalles</strong></th>
									</tr>
								</thead>
							</table>
						</section>

					</div>
				
					
					<div class="table-responsive">
						<h4>Por Pagar</h4>	
						<br>	

						<section>
							<table id="tabla_ctasxpagar" class="display table table-striped table-bordered table-responsive" cellspacing="0" width="100%">
								<thead>
									<tr>
											<th class="text-center cursora" width="10%">Movimiento  </th>
											<th class="text-center cursora" width="10%">Tipo Pago  </th>
											<th class="text-center cursora" width="5%">Almacén  </th>
											<th class="text-center cursora" width="35%">Proveedor  </th>
											
											<th class="text-center cursora" width="10%">Fecha  </th>
											<th class="text-center cursora" width="10%">Factura  </th>
											
											<th class="text-center cursora" width="10%">Subtotal  </th>
											<th class="text-center cursora" width="10%">IVA  </th>
											<th class="text-center cursora" width="10%">Total  </th>
											<th class="text-center " width="20%"><strong>Días</strong></th>
											<th class="text-center " width="20%"><strong>Estatus</strong></th>
											<th class="text-center " width="20%"><strong>Detalles</strong></th>
									</tr>
								</thead>
							</table>
						</section>

					</div>


					<div class="table-responsive">
						<h4>Pagadas</h4>	
						<br>	

						<section>
							<table id="tabla_ctas_pagadas" class="display table table-striped table-bordered table-responsive" cellspacing="0" width="100%">
								<thead>
									<tr>
											<th class="text-center cursora" width="10%">Movimiento  </th>
											<th class="text-center cursora" width="10%">Tipo Pago  </th>
											<th class="text-center cursora" width="5%">Almacén  </th>
											<th class="text-center cursora" width="35%">Proveedor  </th>
											
											<th class="text-center cursora" width="10%">Fecha  </th>
											<th class="text-center cursora" width="10%">Factura  </th>
											
											<th class="text-center cursora" width="10%">Subtotal  </th>
											<th class="text-center cursora" width="10%">IVA  </th>
											<th class="text-center cursora" width="10%">Total  </th>
											<th class="text-center " width="20%"><strong>Días</strong></th>
											<th class="text-center " width="20%"><strong>Estatus</strong></th>
											<th class="text-center " width="20%"><strong>Detalles</strong></th>
									</tr>
								</thead>
							</table>
						</section>

					</div>


				</div>				

			<!--fin tabla-->					


					
			<div class="row">

				
				<div class="col-sm-8 col-md-9"></div>
				<div class="col-sm-4 col-md-3">
					<a href="<?php echo base_url(); ?><?php echo $retorno; ?>" class="btn btn-danger btn-block"><i class="glyphicon glyphicon-backward"></i> Regresar</a>
				</div>
			</div>
					

			</div>
		</div>


	

		</div>





</div>












<?php $this->load->view( 'footer' ); ?>
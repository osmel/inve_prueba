<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php $this->load->view( 'header' ); ?>

<?php
 	if (!isset($retorno)) {
      	$retorno ="";
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

						<div id="fecha_ctasxpagar" class="col-xs-12 col-sm-6 col-md-3">
							<label id="label_proveedor" for="descripcion" class="col-sm-12 col-md-12">Rango de fecha</label>
							<div class="input-prepend input-group  form-group" style="padding-left:15px !important;padding-right:15px !important;">
	                       		<span class="add-on input-group-addon"><i class="glyphicon glyphicon-calendar fa fa-calendar"></i></span>
								<input id="foco_ctasxpagar" type="text" name="permisos"  class="form-control col-sm-12 col-md-12 fecha_ctasxpagar ttip" title="Seleccione un rango de fechas para filtrar los resultados." value="" format = "DD-MM-YYYY"/> 
							</div>	
	                     </div>



				<div class="col-md-12">
					
					<div class="table-responsive">

						<h4>Vencidas</h4>	
						<br>	

							<fieldset id="disa_vencidas" disabled>
								<div class="col-sm-4 col-md-4 marginbuttom">
									<a id="impresion_vencidas" type="button" class="btn btn-success btn-block impresion_ctas" tipo="vencidas">Imprimir</a>
								</div>

								<div class="col-sm-4 col-md-4 marginbuttom">
									<a id="exportar_vencidas" type="button" class="btn btn-success btn-block exportar_ctas" tipo="vencidas" >Exportar</a>
								</div>

							</fieldset>			
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
											<th class="text-center " width="20%"><strong>Días Vencidos</strong></th>
											<th class="text-center " width="20%"><strong>Monto a Pagar</strong></th>
											<th class="text-center " width="20%"><strong>Detalles</strong></th>
									</tr>
								</thead>
							</table>
						</section>

					</div>
				
					
					<div class="table-responsive">
						<h4>Por Pagar</h4>	
						<br>	

							<fieldset id="disa_xpagar" disabled>
								<div class="col-sm-4 col-md-4 marginbuttom">
									<a id="impresion_ctasxpagar" type="button" class="btn btn-success btn-block impresion_ctas" tipo="xpagar">Imprimir</a>
								</div>

								<div class="col-sm-4 col-md-4 marginbuttom">
									<a id="exportar_ctasxpagar" type="button" class="btn btn-success btn-block exportar_ctas" tipo="xpagar" >Exportar</a>
								</div>

							</fieldset>			
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
											<th class="text-center " width="20%"><strong>Días Vencidos</strong></th>
											<th class="text-center " width="20%"><strong>Monto a Pagar</strong></th>
											<th class="text-center " width="20%"><strong>Detalles</strong></th>
									</tr>
								</thead>
							</table>
						</section>

					</div>


					<div class="table-responsive">
						<h4>Pagadas</h4>	
						<br>	


							<fieldset id="disa_pagadas" disabled>
								<div class="col-sm-4 col-md-4 marginbuttom">
									<a id="impresion_pagadas" type="button" class="btn btn-success btn-block impresion_ctas" tipo="pagadas">Imprimir</a>
								</div>

								<div class="col-sm-4 col-md-4 marginbuttom">
									<a id="exportar_pagadas" type="button" class="btn btn-success btn-block exportar_ctas" tipo="pagadas">Exportar</a>
								</div>

							</fieldset>			
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
											<!-- <th class="text-center " width="20%"><strong>Días</strong></th> -->
											<th class="text-center " width="20%"><strong>Monto Pagado</strong></th>
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
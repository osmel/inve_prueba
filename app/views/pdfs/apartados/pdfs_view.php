<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php $this->load->view('header'); ?>
<?php 

 	if (!isset($retorno)) {
      	$retorno ="";
    }

if ($val_proveedor) {
		$consecutivo_actual = (( ($val_proveedor->id_tipo_pedido == 1) && ($val_proveedor->id_tipo_factura==1) ) ? $consecutivo->conse_factura : $consecutivo->conse_remision );
		$consecutivo_actual = ( ($val_proveedor->id_tipo_pedido==2) ? $consecutivo->conse_surtido : $consecutivo_actual);
	} else {
		$consecutivo_actual = $consecutivo->conse_factura;
	}	
?>		

<input type="hidden" id="conse_factura" name="conse_factura" value="<?php echo $consecutivo->conse_factura+1; ?>">
<input type="hidden" id="conse_remision" name="conse_remision" value="<?php echo $consecutivo->conse_remision+1; ?>">
<input type="hidden" id="conse_surtido" name="conse_surtido" value="<?php echo $consecutivo->conse_surtido+1; ?>">

<div class="container">

			<br/>
			<div class="row">
				<div class="col-sm-12 col-md-12"><h4 class="col-sm-12 col-md-12">Total de Apartados</h4></div>
			


					<div class="col-xs-12 col-sm-6 col-md-3" >
						<?php if ($val_proveedor) { ?>
						<fieldset class="disabledme" disabled>							
						<?php } else { ?>
						<fieldset class="disabledme">						
						<?php } ?>
							<div class="form-group" style="margin-right: 0px; margin-left: 15px;">
								<label for="descripcion">Cliente</label>
								<div class="input-group col-xs-12 col-sm-12 col-md-12 ">
									<?php if ($val_proveedor) { ?>
									<input identificador="" value="<?php echo $val_proveedor->nombre; ?>" type="text" name="editar_proveedor" idproveedor="3" class="buscar_proveedor form-control typeahead tt-query" autocomplete="off" spellcheck="false" placeholder="Buscar Empresa Relacionada...">
									<?php } else { ?>
									<input  identificador="" type="text" name="editar_proveedor" idproveedor="3" class="buscar_proveedor form-control typeahead tt-query" autocomplete="off" spellcheck="false" placeholder="Buscar Empresa Relacionada...">
									<?php } ?>
								</div>
							</div>
						</fieldset>	
					</div>

					<div class="col-xs-12 col-sm-6 col-md-2">
						<fieldset disabled>
							<div class="form-group">
								<label for="movimiento">No. Movimiento</label>
								<div>
										<input type="text" value="<?php echo $consecutivo_actual+1; ?>" class="form-control" id="movimiento" name="movimiento" placeholder="No. Movimiento">
								</div>
							</div>
						</fieldset>			
					</div>










				<div class="col-xs-12 col-sm-6 col-md-2">
					    
							<label for="id_tipo_pedido_inicio" class="col-sm-3 col-md-12">Tipo de pedido</label>
							<div class="col-sm-9 col-md-12">

											<select name="id_tipo_pedido_inicio" id="id_tipo_pedido_inicio" class="form-control">
												<!--<option value="0">Selecciona una opción</option>-->
													<?php foreach ( $pedidos as $pedido ){ ?>
																<option value="<?php echo $pedido->id; ?>" ><?php echo $pedido->tipo_pedido; ?></option>
													<?php } ?>
												<!--rol de usuario -->
											</select>

							</div>
					</div>		



					<!--Tipos de factura -->
					<div class="col-xs-12 col-sm-6 col-md-2 tipo_factura" >
					    
							<label for="id_tipo_factura_inicio" class="col-sm-3 col-md-12">Tipo de factura</label>
							<div class="col-sm-9 col-md-12">

											<select name="id_tipo_factura_inicio" id="id_tipo_factura_inicio" class="form-control">
												<!--<option value="0">Selecciona una opción</option>-->
													<?php foreach ( $facturas as $factura ){ ?>
																<option value="<?php echo $factura->id; ?>" ><?php echo $factura->tipo_factura; ?></option>
													<?php } ?>
												<!--rol de usuario -->
											</select>

							</div>
					</div>		
















			</div>


			<div class="col-md-12">
				<div class="table-responsive">

					<section>
						<table id="tabla_apartado_vendedores" class="display table table-striped table-bordered table-responsive" cellspacing="0" width="100%">
							<thead>
								<tr>
										<th class="text-center cursora" style="width:45%">Código</th>
										<th class="text-center cursora" style="width:15%">Descripción </th>
										<th class="text-center cursora" style="width:10%">Color</th>
										<th class="text-center cursora" style="width:10%">Cantidad</th>
										<th class="text-center cursora" style="width:10%">Ancho </th>
										<th class="text-center cursora" style="width:10%">Precio </th>
										<th class="text-center cursora">Lote</th>
										<th class="text-center " width="5%"><strong>Eliminar</strong></th>
										<th class="text-center cursora">Almacén</th>

								</tr>
							</thead>
						</table>
					</section>

				</div>

			</div>
		
		
		<hr style="float:left; width:100%; ">

				<div class="row bloque_totales">						
					<div class="col-sm-0 col-md-2">	
					  
					</div>	
					<div class="col-sm-12 col-md-2">	
					  <b>Existencias Totales</b>			
					</div>		
					
					<div class="col-sm-3 col-md-2">	
						<span id="total_pieza"></span>			
					</div>	
					<div class="col-sm-3 col-md-2">	
						<span id="total_metro"></span>			
					</div>	
					<div class="col-sm-3 col-md-2">	
						<span id="total_kg" ></span>				
					</div>	
					<div class="col-sm-3 col-md-2">	
						<span id="total_precio"></span>			
					</div>	

				</div>



		<hr style="float:left; width:100%; ">

		<div class="row">

			<div class="col-xs-12 col-sm-4 col-md-4"></div>
			<div class="col-xs-12 col-sm-4 col-md-4 marginbuttom">
				<a href="<?php echo base_url(); ?><?php echo $retorno; ?>" class="btn btn-danger btn-block">
					<i class="glyphicon glyphicon-backward"></i> Regresar
				</a>
			</div>

			<div class="col-xs-12 col-sm-4 col-md-4" style="padding-bottom:15px">
				<a id="conf_apartado" type="button"  class="btn btn-success btn-block">
					Confirmar e imprimir apartados
				</a>				
			</div>

		</div>	


</div>

<div class="modal fade bs-example-modal-lg" id="modalMessage" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
        <div class="modal-content"></div>
    </div>
</div>	


<?php $this->load->view('footer'); ?>



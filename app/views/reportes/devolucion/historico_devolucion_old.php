<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php $this->load->view( 'header' ); ?>

<?php
 	if (!isset($retorno)) {
      	$retorno ="reportes";
      	$otro_retorno='listado_devolucion';
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
			<div class="panel-heading">Histórico de Devolución</div>
			<div class="panel-body">				
				<div class="table-responsive">
				<table class="table table-striped table-bordered table-responsive tabla_ordenadas" >
					<thead>	
						<tr>
							<th class="text-center cursora" width="10%">Movimiento  <i class="glyphicon glyphicon-sort"></i></th>
							<th class="text-center cursora" width="10%">Almacén  <i class="glyphicon glyphicon-sort"></i></th>

							<th class="text-center cursora" width="30%">Proveedor  <i class="glyphicon glyphicon-sort"></i></th>
							<th class="text-center cursora" width="15%">Fecha  <i class="glyphicon glyphicon-sort"></i></th>
							<th class="text-center cursora" width="15%">Factura  <i class="glyphicon glyphicon-sort"></i></th>
							<th class="text-center " width="20%"><strong>Detalles</strong></th>
						</tr>
					</thead>		
					<?php if ( isset($entradas ) && !empty($entradas ) ): ?>
						<?php foreach( $entradas  as $entrada  ): ?>
							<tr style="color:<?php echo $entrada->color_devolucion?>">
								
								<td class="text-center"><?php echo $entrada->movimiento ; ?></td>
								<td class="text-center"><?php echo $entrada->almacen ; ?></td>
								<td class="text-center"><?php echo $entrada->nombre ; ?></td>
								<td class="text-center"><?php echo $entrada->fecha ; ?></td>
								<td class="text-center"><?php echo $entrada->factura ; ?></td>
								 <td>
									<a style="  padding: 1px 0px 1px 0px;" href="<?php echo base_url(); ?>procesar_entradas/<?php echo base64_encode($entrada->movimiento); ?>/<?php echo base64_encode($entrada->devolucion); ?>/<?php echo base64_encode($otro_retorno); ?>" 
									type="button" class="btn btn-success btn-block">
										Detalles
									</a>
								</td>
							</tr>
						<?php endforeach; ?>
					<?php else : ?>
							<tr>
								<td colspan="5">No existen notas </td>
							</tr>
					<?php endif; ?>						

				</table>
			</div>

					
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
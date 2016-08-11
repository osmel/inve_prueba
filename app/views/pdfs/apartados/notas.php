<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="container">

	<h4>Cliente: <?php echo $movimientos[0]->cliente; ?></h4>
	<h4>Cargador: <?php echo $movimientos[0]->cargador; ?></h4>
	<h4>Fecha: <?php echo $movimientos[0]->fecha; ?></h4>
	<h4>Movimiento:<?php echo $movimientos[0]->mov_salida; ?></h4>
	<h4>Factura: <?php echo $movimientos[0]->factura; ?></h4>


	<div class="row">
			<div class="col-md-12">
				<h4>Productos</h4>
				<div class="table-responsive">
				<table class="table table-striped table-bordered table-responsive tabla_ordenadas" >
					<thead>	
						<tr>
							<th class="text-center cursora" width="15%">Código<i class="glyphicon glyphicon-sort"></i></th>
							<th class="text-center cursora" width="20%">Descripción <i class="glyphicon glyphicon-sort"></i></th>
							<th class="text-center cursora" width="10%">Color<i class="glyphicon glyphicon-sort"></i></th>
							<th class="text-center cursora" width="5%">UM<i class="glyphicon glyphicon-sort"></i></th>
							<th class="text-center cursora" width="5%">Cantidad<i class="glyphicon glyphicon-sort"></i></th>
							<th class="text-center cursora" width="5%">Ancho <i class="glyphicon glyphicon-sort"></i></th>
							<th class="text-center cursora" width="5%">Lote<i class="glyphicon glyphicon-sort"></i></th>
							<th class="text-center cursora" width="15%">Número consecutivo<i class="glyphicon glyphicon-sort"></i></th>
						</tr>
					</thead>		

					<tbody>	
					<?php if ( isset($movimientos) && !empty($movimientos) ): ?>
						<?php foreach( $movimientos as $movimiento ): ?>
							<tr>
								<td class="text-center"><?php echo $movimiento->codigo; ?></td>								
								<td class="text-center"><?php echo $movimiento->id_descripcion; ?></td>
								<td class="text-center"><?php echo $movimiento->color; ?></td>
								<td class="text-center"><?php echo $movimiento->medida; ?></td>
								<td class="text-center"><?php echo $movimiento->cantidad_um; ?></td>
								<td class="text-center"><?php echo $movimiento->ancho; ?></td>
								<td class="text-center"><?php echo $movimiento->id_lote; ?></td>
								<td class="text-center"><?php echo $movimiento->consecutivo; ?></td>

	
							</tr>
								
						<?php endforeach; ?>
					

					<?php else : ?>
							<tr class="noproducto">
								<td colspan="10">No se han agregado producto</td>
							</tr>

					<?php endif; ?>		

					</tbody>		
				</table>
			</div>

			</div>
	</div>
	

</div>
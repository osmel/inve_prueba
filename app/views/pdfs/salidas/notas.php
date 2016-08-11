<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="container">
	<div>
		<div>
			<table style="width: 100%; border: 2px solid #222222;">
				<tbody>
					

					<tr>
						<th width="70%" style="font-size:10px; line-height: 15px;">

								<span><b>Cliente: </b> <?php echo $movimientos[0]->cliente; ?></span><br>
								<span><b>Cargador: </b> <?php echo $movimientos[0]->cargador; ?></span><br>
								<span><b>Fecha y hora: </b> <?php echo $movimientos[0]->fecha; ?></span><br>
								<span><b>Movimiento: </b><?php echo $movimientos[0]->mov_salida; ?></span><br>
								<span><b>Factura: </b> <?php echo $movimientos[0]->factura; ?></span><br>
								<span><b>Tipo de Operación: </b> <?php echo $etiq_mov; ?></span><br> 

								<span><b>Almacén: </b> <?php echo $movimientos[0]->almacen; ?></span><br> 
								
								
								<?php if ($movimientos[0]->destino!='') { ?>
										<span><b>Tipo de Salida: </b> <?php echo $movimientos[0]->destino; ?></span> 
								<?php } else { ?>
									<span><b>Tipo de Salida: </b> NP </span> 
								<?php } ?>	



									

						</th>

						<th width="30%" style="text-align:right;">
							<?php echo '<img src="'.base_url().'img/unnamed.png" width="93px" height="48px"/>'; ?>
						</th>

					</tr>

					<tr style="padding:0px;margin:0px;" height="0px;" >

						<th height="0px;">
							
						</th>

					</tr>





				</tbody>
			</table>
			<table style="width: 100%; border: 2px solid #222222;">
				<thead>
					<tr>
						<th colspan="9">
							<p><b>Productos</b></p>
						</th>
					</tr>
					<tr>
						<th width="20%">Código</th>
						<th width="19%">Descripción</th>
						<th width="10%">Color</th>
						<th width="4%">UM</th>
						<th width="10%">Cant.</th>
						<th width="9%">Ancho</th>
						<th width="9%">Peso Real</th>
						<th width="9%">Lote</th>
						<th width="10%">Vendedor</th>

						
					</tr>
				</thead>
				<tbody>
				<?php if ( isset($movimientos) && !empty($movimientos) ): ?>
					<?php foreach( $movimientos as $movimiento ): ?>
						<tr>
							<td width="20%" style="border-top: 1px solid #222222;"><?php echo $movimiento->codigo; ?></td>								
							<td width="19%" style="border-top: 1px solid #222222;"><?php echo $movimiento->id_descripcion; ?></td>
							<td width="10%" style="border-top: 1px solid #222222;"><?php echo $movimiento->color; ?></td>
							<td width="4%" style="border-top: 1px solid #222222;"><?php echo $movimiento->medida; ?></td>
							<td width="10%" style="border-top: 1px solid #222222;"><?php echo $movimiento->cantidad_um; ?></td>
							<td width="9%" style="border-top: 1px solid #222222;"><?php echo $movimiento->ancho; ?></td>
							<td width="9%" style="border-top: 1px solid #222222;"><?php echo $movimiento->peso_real; ?></td>
							<td width="9%" style="border-top: 1px solid #222222;"><?php echo $movimiento->id_lote; ?> - <?php echo $movimiento->consecutivo; ?></td>
							<td width="10%" style="border-top: 1px solid #222222;"><?php echo $movimiento->nom_vendedor; ?></td>
						</tr>
					<?php endforeach; ?>
				<?php else : ?>
						<tr class="noproducto">
							<td colspan="9">No se han agregado producto</td>
						</tr>
				<?php endif; ?>	
				</tbody>	


				<tfooter>	
						<tr>
							<td width="100%" style="border-top: 1px solid #222222; font-size: 10px; line-height: 15px; padding: 0px; margin-bottom: 0px;">
									<?php  if ($totales->metros>0) { ?>	
										<span><b>Total Metros: </b> <?php echo $totales->metros; ?></span><br>
									<?php } ?>		
									<?php  if ($totales->kilogramos>0) { ?>	
										<span><b>Total Kilogramos: </b> <?php echo $totales->kilogramos; ?></span><br>
									<?php } ?>	
									<span><b>Total Peso real: </b> <?php echo $totales->peso_real; ?></span><br>
									<span><b>Total Piezas: </b><?php echo $totales->pieza; ?></span>
							</td>
						</tr>
				</tfooter>			
					
			</table>
		</div>
	</div>
</div>
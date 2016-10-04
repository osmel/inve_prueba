<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="container">
	<div>
		<div>
			<table style="width: 100%; border: 2px solid #222222;">
				<tbody>
					<tr>
						<td>
							<p style="font-size: 15px; line-height: 20px; padding: 0px; margin-bottom: 0px;">

								<h4>Resumen de traspasos</h4>
							</p>
						</td>
						<td style="text-align: right;">
							<p>&nbsp;</p>
							<?php echo '<img src="'.base_url().'img/unnamed.png" width="93px" height="50px"/>'; ?>
						</td>
					</tr>
				</tbody>
			</table>
			<table style="width: 100%; border: 2px solid #222222; font-size: 12px;">
				<thead>
					<tr>
						<th width="20%">Traspaso</th>
						<th width="10%">Almacén</th>
						<th width="13%">Fecha</th>
						<th width="10%">Número</th>
						<th width="10%">Total Metros</th>
						<th width="10%">Total Kgs</th>
						<th width="10%">Pieza</th>
						<th width="10%">Subtotal</th>
						<th width="7%">IVA</th>

					</tr>
				</thead>	
				<tbody>	
				<?php if ( isset($movimientos) && !empty($movimientos) ): ?>
					<?php foreach( $movimientos as $movimiento ): ?>
						<tr>

							
							<td width="20%" style="border-top: 1px solid #222222;"><?php echo $movimiento->tipo_factura; ?></td>			
							<td width="10%" style="border-top: 1px solid #222222;"><?php echo $movimiento->almacen; ?></td>			
							<td width="13%" style="border-top: 1px solid #222222;"><?php echo $movimiento->fecha_apartado; ?></td>			
							<td width="10%" style="border-top: 1px solid #222222;"><?php echo $movimiento->consecutivo_traspaso; ?></td>					
							<td width="10%" style="border-top: 1px solid #222222;"><?php echo number_format($movimiento->metros, 2, '.', ','); ?></td>					
							<td width="10%" style="border-top: 1px solid #222222;"><?php echo number_format($movimiento->kilogramos, 2, '.', ','); ?></td>					
							<td width="10%" style="border-top: 1px solid #222222;"><?php echo $movimiento->pieza; ?></td>			
							<td width="10%" style="border-top: 1px solid #222222;"><?php echo number_format($movimiento->subtotal, 2, '.', ','); ?></td>					
							<td width="7%" style="border-top: 1px solid #222222;"><?php echo number_format($movimiento->iva, 2, '.', ','); ?></td>				



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
								<span><b>Subtotal: </b><?php echo number_format($totales->subtotal, 2, '.', ','); ?></span> 

									<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Iva: </b><?php echo number_format($totales->iva, 2, '.', ','); ?></span>
									<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Total: </b><?php echo number_format($totales->total, 2, '.', ','); ?></span><br>
									
									<?php  if ($totales->metros>0) { ?>	
										<span><b>Total Metros: </b> <?php echo $totales->metros; ?></span>
									<?php } ?>		
									<?php  if ($totales->kilogramos>0) { ?>	
										<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Total Kilogramos: </b> <?php echo $totales->kilogramos; ?></span>
									<?php } ?>	

									<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Total Piezas: </b><?php echo $totales->pieza; ?></span>
								
							</td>
						</tr>
				</tfooter>	


			</table>
		</div>
	</div>
</div>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="container">
	<div>
		<div>
			<table style="width: 100%; border: 2px solid #222222;">
				<tbody>

					<tr style="font-size: 10px; line-height: 15px; padding: 0px; margin-bottom: 0px;">
						<th width="70%">
							<span><b>Fecha y hora: </b> <?php echo date( 'd-m-Y h:i:s A');  ?></span>
							<p style="font-size: 10px;"><b >Entradas</b></p>
						</th>
						<th width="30%" style="text-align:right;">
						  	
							<?php echo '<img src="'.base_url().'img/unnamed.png" width="93px" height="48px"/>'; ?>
						</th>

					</tr>					


				</tbody>
			</table>
			<table style="width: 100%; border: 2px solid #222222; ">
				<thead>
					<tr><th> </th></tr>
					<tr>

						<th width="5%">Mov.</th>
						<th width="10%">Tipo Pago  </th>
						<th width="10%">Almacén</th>
						<th width="21%">Proveedor</th>
						
						<th width="13%">Fecha</th>
						<th width="11%">Factura</th>
						
						<th width="10%">Subtotal</th>
						<th width="10%">IVA</th>
						<th width="10%">Total</th>
						
						
					</tr>
				</thead>
				<tbody>
				<?php if ( isset($movimientos) && !empty($movimientos) ): ?>
					<?php foreach( $movimientos as $movimiento ): ?>
						<tr>
							<td width="5%" style="border-top: 1px solid #222222;"><?php echo $movimiento->movimiento; ?></td>					
							<td width="10%" style="border-top: 1px solid #222222;"><?php echo $movimiento->tipo_pago; ?></td>
							<td width="10%" style="border-top: 1px solid #222222;"><?php echo $movimiento->almacen; ?></td>
							<td width="21%" style="border-top: 1px solid #222222;"><?php echo $movimiento->nombre; ?></td>

							<td width="13%" style="border-top: 1px solid #222222;"><?php echo $movimiento->fecha; ?></td>
							<td width="11%" style="border-top: 1px solid #222222;"><?php echo $movimiento->factura; ?></td>
							
							
							<td width="10%" style="border-top: 1px solid #222222;"><?php echo number_format($movimiento->subtotal, 2, '.', ','); ?></td>
							<td width="10%" style="border-top: 1px solid #222222;"><?php echo number_format($movimiento->iva, 2, '.', ','); ?></td>
							<td width="10%" style="border-top: 1px solid #222222;"><?php echo number_format($movimiento->total, 2, '.', ','); ?></td>

							
							
						</tr>
					<?php endforeach; ?>
				<?php else : ?>
						<tr class="noproducto">
							<td colspan="9">No se han agregado producto</td>
						</tr>
				<?php endif; ?>	
				</tbody>	
				<tfooter>	
						<!--
						<tr>
							<td width="100%" style="border-top: 1px solid #222222; font-size: 10px; line-height: 15px; padding: 0px; margin-bottom: 0px;">
									<?php  if ($totales->metros>0) { ?>	
										<span><b>Total Metros: </b> <?php echo $totales->metros; ?></span><br>
									<?php } ?>		
									<?php  if ($totales->kilogramos>0) { ?>	
										<span><b>Total Kilogramos: </b> <?php echo $totales->kilogramos; ?></span><br>
									<?php } ?>	

									<span><b>Total Piezas: </b><?php echo $totales->pieza; ?></span>
							</td>
						</tr>-->
				</tfooter>			
					
			</table>
		</div>
	</div>
</div>
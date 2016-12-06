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
						<th width="6%">Factura</th>
						<th width="8%">Tipo Pago  </th>
						<th width="7%">Almacén</th>
						<th width="15%">Proveedor</th>
						
						<th width="8%">Fecha Creación</th>
						<th width="8%">Fecha Vencimiento</th>

						
						<th width="10%">Subtotal</th>
						<th width="10%">IVA</th>
						<th width="10%">Total</th>
						<th width="6%">Días por Vencer</th>						
						<th width="7%">Monto a Pagar</th>		
						
						
					</tr>
				</thead>
				<tbody>
				<?php if ( isset($movimientos) && !empty($movimientos) ): ?>
					<?php foreach( $movimientos as $movimiento ): ?>
						<tr>
							<td width="5%" style="border-top: 1px solid #222222;"><?php echo $movimiento->movimiento; ?></td>		<td width="6%" style="border-top: 1px solid #222222;"><?php echo $movimiento->factura; ?></td>					

							<td width="8%" style="border-top: 1px solid #222222;"><?php echo $movimiento->tipo_pago; ?></td>
							<td width="7%" style="border-top: 1px solid #222222;"><?php echo $movimiento->almacen; ?></td>
							<td width="15%" style="border-top: 1px solid #222222;"><?php echo $movimiento->nombre; ?></td>

							<td width="8%" style="border-top: 1px solid #222222;"><?php echo $movimiento->fecha; ?></td>
							<td width="8%" style="border-top: 1px solid #222222;"><?php echo $movimiento->fecha_vencimiento; ?></td>
							
							
							
							<td width="10%" style="border-top: 1px solid #222222;"><?php echo number_format($movimiento->subtotal, 2, '.', ','); ?></td>
							<td width="10%" style="border-top: 1px solid #222222;"><?php echo number_format($movimiento->iva, 2, '.', ','); ?></td>
							<td width="10%" style="border-top: 1px solid #222222;"><?php echo number_format($movimiento->total, 2, '.', ','); ?></td>

							<td width="6%" style="border-top: 1px solid #222222;"><?php echo abs($movimiento->diferencia_dias-$movimiento->dias_ctas_pagar); ?></td>
						
							<td width="7%" style="border-top: 1px solid #222222;"><?php echo (($movimiento->monto_restante==null) ? $movimiento->total : $movimiento->monto_restante); ?></td>
							
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
									
							</td>
						</tr>
				</tfooter>			
					
			</table>
		</div>
	</div>
</div>
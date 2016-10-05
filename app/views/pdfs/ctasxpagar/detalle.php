<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="container">
	<div>
		<div>
			<table style="width: 100%; border: 2px solid #222222;">
				<tbody>

					<tr style="font-size: 10px; line-height: 15px; padding: 0px; margin-bottom: 0px;">
						<th width="70%">
							<span><b>Fecha y hora: </b> <?php echo date( 'd-m-Y h:i:s A');  ?></span>
							<span><b>Num. Mov: </b> <?php echo $movimientos[0]->movimiento; ?></span><br>
							<span><b>Almaceén: </b> <?php echo $movimientos[0]->almacen; ?></span><br>
							<span><b>Proveedor: </b> <?php echo $movimientos[0]->nombre; ?></span><br>
							<span><b>Fecha Entrada: </b> <?php echo $movimientos[0]->fecha; ?></span><br>
							<span><b>Subtotal: </b> <?php echo $movimientos[0]->subtotal; ?></span><br>
							<span><b>IVA: </b> <?php echo $movimientos[0]->iva; ?></span><br>
							<span><b>Total: </b> <?php echo $movimientos[0]->total; ?></span><br>
							<span><b>Días Vencidos: </b> <?php echo abs($movimientos[0]->diferencia_dias-$movimientos[0]->dias_ctas_pagar); ?></span><br>
							<span><b>Monto a Pagar: </b> <?php echo $movimientos[0]->monto_restante; ?></span><br>



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
					  
						<th width="20%">Instrumento</th>
						<th width="15%">Referencia</th>
						<th width="10%">Fecha</th>
						<th width="15%">Importe</th>
						<th width="40%">Comentario</th>
					</tr>
				</thead>
				<tbody>
				<?php if ( isset($movimientos) && !empty($movimientos) ): ?>
					<?php foreach( $movimientos as $movimiento ): ?>
						<tr>
							<td width="20%" style="border-top: 1px solid #222222;"><?php echo $movimiento->documento_pago; ?></td>	
							<td width="15%" style="border-top: 1px solid #222222;"><?php echo $movimiento->instrumento_pago; ?></td>
							<td width="10%" style="border-top: 1px solid #222222;"><?php echo $movimiento->fecha_pago; ?></td>
							<td width="15%" style="border-top: 1px solid #222222;"><?php echo $movimiento->importe; ?></td>
							<td width="40%" style="border-top: 1px solid #222222;"><?php echo $movimiento->comentario; ?></td>
						</tr>
					<?php endforeach; ?>
				<?php else : ?>
						<tr class="noproducto">
							<td colspan="5">No se han agregado producto</td>
						</tr>
				<?php endif; ?>	
				</tbody>	
				<!--
				<tfooter>	
						<tr>
							<td width="100%" style="border-top: 1px solid #222222; font-size: 10px; line-height: 15px; padding: 0px; margin-bottom: 0px;">

									<span><b>Subtotal: </b><?php echo number_format($totales->subtotal, 2, '.', ','); ?></span> 

									<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Iva: </b><?php echo number_format($totales->iva, 2, '.', ','); ?></span>
									<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Total: </b><?php echo number_format($totales->total, 2, '.', ','); ?></span><br>
									
							</td>
						</tr>
				</tfooter>		
				-->	
					
			</table>
		</div>
	</div>
</div>
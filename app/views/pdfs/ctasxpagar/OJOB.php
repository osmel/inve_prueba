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

						<th width="10%">Doc. Pago</th>
						<th width="10%">Fecha.</th>
						<th width="10%">importe.</th>

						<th width="10%">Proveedor.</th>
						<th width="10%">Mov.</th>
						<th width="10%">Total.</th>
						<th width="10%">Fecha.</th>
						

						
						
					</tr>
				</thead>
				<tbody>
				<?php if ( isset($movimientos) && !empty($movimientos) ): ?>
					<?php foreach( $movimientos as $movimiento ): ?>
						<tr>
							<td width="10%" style="border-top: 1px solid #222222;"><?php echo $movimiento->documento_pago; ?></td>	
							<td width="10%" style="border-top: 1px solid #222222;"><?php echo $movimiento->fecha_pago; ?></td>
							<td width="10%" style="border-top: 1px solid #222222;"><?php echo $movimiento->importe; ?></td>

							<td width="10%" style="border-top: 1px solid #222222;"><?php echo $movimiento->nombre; ?></td>
							<td width="10%" style="border-top: 1px solid #222222;"><?php echo $movimiento->movimiento; ?></td>
							<td width="10%" style="border-top: 1px solid #222222;"><?php echo $movimiento->total; ?></td>
							<td width="10%" style="border-top: 1px solid #222222;"><?php echo $movimiento->fecha; ?></td>
							
							
							
							
							
						</tr>
					<?php endforeach; ?>
				<?php else : ?>
						<tr class="noproducto">
							<td colspan="9">No se han agregado producto</td>
						</tr>
				<?php endif; ?>	
				</tbody>	
				
					
			</table>
		</div>
	</div>
</div>
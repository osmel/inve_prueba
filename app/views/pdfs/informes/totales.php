<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>


<div class="container">
	<div>
		<div>
			<table style="width: 100%; border: 2px solid #222222;">
				<tbody>
					<tr>
						<th width="70%">
							<span><b>Fecha y hora: </b> <?php echo date( 'd-m-Y h:i:s A');  ?></span>
							<p style="font-size: 13px;"><b >Totales</b></p>
							<p style="font-size: 13px;"><b >Almacén:</b><?php echo $almacen; ?></p>
						</th>
						<th width="30%" style="text-align:right;">
						  	
							<?php echo '<img src="'.base_url().'img/unnamed.png" width="93px" height="48px"/>'; ?>
						</th>

					</tr>
				</tbody>
			</table>
			<table style="width: 100%; border: 2px solid #222222; font-size: 12px;">
				<thead>
					<tr><th> </th></tr>
					<tr>
						<th width="40%">Fecha</th>
						<th width="20%">Entrada</th>
						
						<th width="20%">Salida</th>
						<th width="20%">Devolución</th>
						
					</tr>
				</thead>
				<tbody>
				<?php if ( isset($movimientos) && !empty($movimientos) ): ?>
					<?php foreach( $movimientos as $movimiento ): ?>
						<tr>
							<td width="40%" style="border-top: 1px solid #222222;"><?php echo $movimiento->fecha; ?></td>								
							<td width="20%" style="border-top: 1px solid #222222;"><?php echo $movimiento->totale; ?></td>
							<td width="20%" style="border-top: 1px solid #222222;"><?php echo $movimiento->totals; ?></td>
							<td width="20%" style="border-top: 1px solid #222222;"><?php echo $movimiento->totald; ?></td>

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
							<td width="100%" style="border-top: 1px solid #222222; font-size: 13px; line-height: 20px; padding: 0px; margin-bottom: 0px;">
									<?php  if ($totales->totale>0) { ?>	
										<span><b>Total Entradas: </b> <?php echo $totales->totale; ?></span><br>
									<?php } ?>		
									<?php  if ($totales->totals>0) { ?>	
										<span><b>Total Salidas: </b> <?php echo $totales->totals; ?></span><br>
									<?php } ?>	

									<?php  if ($totales->totald>0) { ?>	
										<span><b>Total Devoluciones: </b> <?php echo $totales->totald; ?></span>
									<?php } ?>	

							</td>
						</tr>
				</tfooter>			
					
			</table>
		</div>
	</div>
</div>
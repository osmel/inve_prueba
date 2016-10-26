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

						<th width="15%">Proveedor</th>

						<th width="5%">Mov.</th>
						
						<th width="7%">Almacén</th>
						<th width="10%">Fecha Emisión</th>
						<th width="10%">Fecha Vencimiento</th>

						<th width="10%">Cargos</th>
						<th width="10%">Abonos</th>
						<th width="10%">Recargos</th>
						<th width="10%">Descuentos</th>

						<th width="7%">Saldo</th>		
						<th width="6%">Días por Vencer</th>						
						
						
						
					</tr>
				</thead>
				<tbody>
				<?php if ( isset($movimientos) && !empty($movimientos) ): ?>
						<?php 
							 $nomb = ''; 
							 $total = 0;
							 $abono = 0;
							 $recargo = 0;
							 $descuento = 0;
							 $saldo = 0;

							 $total2 = 0;
							 $abono2 = 0;
							 $recargo2 = 0;
							 $descuento2 = 0;
							 $saldo2 = 0;
						?>

					<?php foreach( $movimientos as $movimiento ): ?>

						<?php if ( ($nomb != $movimiento->nombre) && ($total!=0) ) { ?>

						<tr>
							<td width="36%" >

							</td>								
							<td width="10%" >
								<b style="font-size:14px;">Subtotal: </b>
								
							</td>									
							<td width="10%" >
								<b style="font-size:14px;"><?php echo number_format($total, 2, '.', ','); ?></b>
								
							</td>		
							<td width="10%" >
								<b style="font-size:14px;"><?php echo number_format($abono, 2, '.', ','); ?></b>
								
							</td>		
							<td width="10%" >
								<b style="font-size:14px;"><?php echo number_format($recargo, 2, '.', ','); ?></b>
								
							</td>		
							<td width="11%" >
								<b style="font-size:14px;"><?php echo number_format($descuento, 2, '.', ','); ?></b>
							</td>		
							<td width="13%" >
								<b style="font-size:14px;"><?php echo number_format($saldo, 2, '.', ','); ?></b>
							</td>		
							
						</tr>
						
						<tr> <td width="100%" ></td> </tr>

						<hr/>

						<tr> <td width="100%" ></td> </tr>
						<?php }	?>

						<tr>
							<?php if ($nomb != $movimiento->nombre) { ?>
								<td width="15%" ><?php echo $movimiento->nombre; ?></td>
							<?php 														
								$nomb = $movimiento->nombre;
								$total=0;
								 $abono = 0;
								 $recargo = 0;
								 $descuento = 0;								
								 $saldo = 0;								
								} else {
							?>		
								<td width="15%" ></td>
							<?php }

							$total = $total+ number_format($movimiento->total, 2, '.', ','); 
							$abono = $abono+ number_format($movimiento->abono, 2, '.', ','); 
							$recargo = $recargo+ number_format($movimiento->recargo, 2, '.', ','); 
							$descuento = $descuento+ number_format($movimiento->descuento, 2, '.', ','); 
							$saldo = $saldo+ (($movimiento->monto_restante==null) ? $movimiento->total : $movimiento->monto_restante);

							$total2 = $total2+ number_format($movimiento->total, 2, '.', ','); 
							$abono2 = $abono2+ number_format($movimiento->abono, 2, '.', ','); 
							$recargo2 = $recargo2+ number_format($movimiento->recargo, 2, '.', ','); 
							$descuento2 = $descuento2+ number_format($movimiento->descuento, 2, '.', ','); 
							$saldo2 = $saldo2+ (($movimiento->monto_restante==null) ? $movimiento->total : $movimiento->monto_restante);

							?> 


							<td width="5%" ><?php echo $movimiento->movimiento; ?></td>		
							
							<td width="7%" ><?php echo $movimiento->almacen; ?></td>
							<td width="10%" ><?php echo $movimiento->fecha; ?></td>
							<!--<td width="10%" ><?php echo $movimiento->fecha_vencimiento; ?></td> -->
							<td width="10%" ><?php echo $movimiento->fecha_pago; ?></td>
							
							
							
							
							<td width="10%" ><?php echo number_format($movimiento->total, 2, '.', ','); ?></td>
							
							<td width="10%" ><?php echo number_format($movimiento->abono, 2, '.', ','); ?></td>
	
							<td width="10%" ><?php echo number_format($movimiento->recargo, 2, '.', ','); ?></td>

							<td width="10%" ><?php echo number_format($movimiento->descuento, 2, '.', ','); ?></td>


							<td width="7%" ><?php echo (($movimiento->monto_restante==null) ? $movimiento->total : $movimiento->monto_restante); ?></td>

							<td width="6%" ><?php echo abs($movimiento->diferencia_dias-$movimiento->dias_ctas_pagar); ?></td>
						
							
							
						</tr>
					<?php endforeach; ?>

						<?php if ( ($total!=0) ) { ?>
							<tr>
							<td width="36%" >

							</td>								
							<td width="10%" >
								<b style="font-size:14px;">Subtotal: </b>
								
							</td>									
							<td width="10%" >
								<b style="font-size:14px;"><?php echo number_format($total, 2, '.', ','); ?></b>
								
							</td>		
							<td width="10%" >
								<b style="font-size:14px;"><?php echo number_format($abono, 2, '.', ','); ?></b>
								
							</td>		
							<td width="10%" >
								<b style="font-size:14px;"><?php echo number_format($recargo, 2, '.', ','); ?></b>
								
							</td>		
							<td width="11%" >
								<b style="font-size:14px;"><?php echo number_format($descuento, 2, '.', ','); ?></b>
							</td>		
							<td width="10%" >
								<b style="font-size:14px;"><?php echo number_format($saldo, 2, '.', ','); ?></b>
							</td>		


						</tr>
						<tr> <td width="100%" ></td> </tr>
						<hr/>



						<?php }	?>

				<?php else : ?>
						<tr class="noproducto">
							<td colspan="9">No se han agregado producto</td>
						</tr>
				<?php endif; ?>	
				</tbody>	
				<tfooter>	
						
						<tr>
							<td width="36%" >

							</td>								
							<td width="10%" >
								<b style="font-size:14px;">Total: </b>
								
							</td>									
							<td width="10%" >
								<b style="font-size:14px;"><?php echo number_format($total2, 2, '.', ','); ?></b>
								
							</td>		
							<td width="10%" >
								<b style="font-size:14px;"><?php echo number_format($abono2, 2, '.', ','); ?></b>
								
							</td>		
							<td width="10%" >
								<b style="font-size:14px;"><?php echo number_format($recargo2, 2, '.', ','); ?></b>
								
							</td>		
							<td width="11%" >
								<b style="font-size:14px;"><?php echo number_format($descuento2, 2, '.', ','); ?></b>
							</td>		
							<td width="10%" >
								<b style="font-size:14px;"><?php echo number_format($saldo2, 2, '.', ','); ?></b>
							</td>		


						</tr>

				</tfooter>			
					
			</table>
		</div>
	</div>
</div>
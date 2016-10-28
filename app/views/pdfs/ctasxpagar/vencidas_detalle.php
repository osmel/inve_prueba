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

						<th width="20%">Proveedor</th>
						<th width="5%">Mov.</th>
						<th width="10%">Almacén</th>
						<th width="10%">Días Vencidos</th>						
						<th width="15%">Tipo</th>
						<th width="15%">Operación</th>
						<th width="10%">Fecha</th>
						<th width="15%" style="text-align:right;">Importe</th>
						
						
						
						
					</tr>
				</thead>
				<tbody>
				<?php if ( isset($movimientos) && !empty($movimientos) ): ?>
						<?php 
							 $nomb = ''; 
							 $mov = 0; 
							 $id_factura=0;
							 $total = 0;
							 $saldo = 0;

							 $total2 = 0;
							 $saldo2 = 0;
						?>

					<?php foreach( $movimientos as $movimiento ): ?>

						<?php if ( ( ($nomb != $movimiento->nombre) || ($mov != $movimiento->movimiento) || ($id_factura != $movimiento->id_factura) ) && ($total!=0) ) { 

							$saldo2 = $saldo2+ ($saldo+$total);
							?>
							<tr> <td  width="100%" ></td>	</tr>
								<tr>
									<td  width="100%" >
										<b style="font-size:14px; text-align:right;">Saldo: <?php echo number_format($saldo+$total, 2, '.', ','); ?></b>
									</td>	
								</tr>
							<tr> <td width="100%" ></td> </tr>

							<?php if (  ($nomb != $movimiento->nombre)  ) { ?>							
								
								<hr/>
							<?php }	?>								

						<?php }	?>

						

							<?php 
							if (($nomb != $movimiento->nombre) || ($mov != $movimiento->movimiento) || ($id_factura != $movimiento->id_factura) ){ ?>
								<tr>
									<?php if ($nomb != $movimiento->nombre) { ?>
										<td width="20%" ><?php echo $movimiento->nombre; ?></td>
									<?php } else {	?>
										<td width="20%" ></td>
									<?php }	?>
									<td width="5%" ><?php echo $movimiento->movimiento; ?></td>		
									<td width="10%" ><?php echo $movimiento->almacen; ?></td>
									<td width="10%" ><?php echo abs($movimiento->diferencia_dias-$movimiento->dias_ctas_pagar); ?></td>
									<td width="15%" ><?php echo $movimiento->tipo_factura; ?></td>
									<td width="15%" ><b>Cargo</b></td>
									<td width="10%" ><?php echo $movimiento->fecha; ?></td>
									<td width="15%" style="text-align:right;"><?php echo number_format($movimiento->total, 2, '.', ','); ?></td>
								</tr>	
							<?php 														
								$nomb = $movimiento->nombre;
								$mov= $movimiento->movimiento;
								$id_factura=$movimiento->id_factura;
								$total=0;
								$saldo = 0;								
								} //else {
							?>		
							<?php if ($movimiento->importe!=0) { ?>
								<tr>
									<td width="20%" ></td>
									<td width="5%" ></td> 


									<td width="10%" ></td>
									<td width="10%" ></td>
									<td width="15%" ></td>
									<td width="15%" ><b><?php echo $movimiento->documento_pago; ?></b></td>
									<td width="10%" ><?php echo $movimiento->fecha_pago; ?></td>
									<td width="15%" style="text-align:right;"><?php echo number_format($movimiento->importe, 2, '.', ','); ?></td>
								</tr>
							<?php }

							$total = number_format($movimiento->total, 2, '.', ','); // $total+ number_format($movimiento->total, 2, '.', ','); 
							//$saldo = $saldo+ (($movimiento->monto_restante==null) ? $movimiento->total : $movimiento->monto_restante);
							$saldo = $saldo+ 
								(($movimiento->id_documento_pago!=12)*$movimiento->importe*-1)+
								(($movimiento->id_documento_pago==12)*$movimiento->importe);

							//   $this->db->select("total+sum((pr.id_documento_pago <> 12)*pr.importe*-1)+sum((pr.id_documento_pago = 12)*pr.importe) AS monto_restante", FALSE);

							/*$saldo2 = $saldo2+ 
								(($movimiento->id_documento_pago!=12)*$movimiento->importe*-1)+
								(($movimiento->id_documento_pago==12)*$movimiento->importe);
								*/
	

							$total2 = $total2+ number_format($movimiento->total, 2, '.', ','); 
							//$saldo2 = $saldo2+ (($movimiento->monto_restante==null) ? $movimiento->total : $movimiento->monto_restante);

							?> 


							
							
							
							

							<!-- <td width="7%" ><?php echo (($movimiento->monto_restante==null) ? $movimiento->total : $movimiento->monto_restante); ?></td> -->	

							
						
							
								
						
					<?php endforeach; 

						
					?>

						<?php if ( ($total!=0) ) { 


							$saldo2 = $saldo2+ ($saldo+$total);

							?>
							<tr> <td  width="100%" ></td>	</tr>
							<tr>

								<td  width="100%" >
									<b style="font-size:14px; text-align:right;">Saldo: <?php echo number_format($saldo+$total, 2, '.', ','); ?></b>
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
						
							<tr> <td  width="100%" ></td>	</tr>
							<tr>

								<td  width="100%" >
									<b style="font-size:14px; text-align:right;">Saldo: <?php echo number_format($saldo2, 2, '.', ','); ?></b>
								</td>	

							</tr>
							<tr> <td width="100%" ></td> </tr>
							

				</tfooter>			
					
			</table>
		</div>
	</div>
</div>
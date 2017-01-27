<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<html lang="es_MX">
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" href="<?php echo base_url(); ?>js/bootstrap-3.3.1/dist/css/bootstrap.min.css">
		
</head>
<body>

		<div class="container">
			<div>
				<div>
					<table style="width: 100%; border: 2px solid #222222;">
						<tbody>

							<tr style="font-size: 10px; line-height: 15px; padding: 0px; margin-bottom: 0px;">
								

								<th width="70%">
									<h1 style="font-size: 18px;"><b >Antigüedad</b></h1>
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
								<th width="6%">Mov.</th>
								<th width="8%">Factura</th>
								<th width="7%">Almacén</th>
								<th width="10%">Fecha Emisión</th>
								<th width="10%">Fecha Vencimiento</th>
								<th width="11%">de 1 a 7</th>
								<th width="11%">de 8 a 14</th>
								<th width="11%">de 15 o más</th>
								<th width="11%">Saldo</th>		
								<!-- <th width="6%">Días por Vencer</th>	-->
							</tr>
						</thead>
						<tbody>
						<?php if ( isset($movimientos) && !empty($movimientos) ): ?>
								<?php 
									 $nomb = ''; 
									 $total = 0;
									 
									 $saldo1 = 0;
									 $saldo2 = 0;
									 $saldo3 = 0;
									 $saldo = 0;

									 $total2 = 0;
									 
									 $saldo21 = 0;
									 $saldo22 = 0;
									 $saldo23 = 0;
									 $saldo20 = 0;
								?>

							<?php foreach( $movimientos as $movimiento ): ?>

								<?php if ( ($nomb != $movimiento->nombre) && ($total!=0) ) { ?>

								<tr>
									<td width="36%" >

									</td>								
									<td width="19%" >
										<b style="font-size:14px;">Subtotal: </b>
										
									</td>									
									<td width="11%" >
										<b style="font-size:14px;"><?php echo number_format($saldo1, 2, '.', ','); ?></b>
										
									</td>		
									<td width="11%" >
										<b style="font-size:14px;"><?php echo number_format($saldo2, 2, '.', ','); ?></b>
										
									</td>		
									<td width="11%" >
										<b style="font-size:14px;"><?php echo number_format($saldo3, 2, '.', ','); ?></b>
										
									</td>		
									<td width="11%" >
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
										 
										 $saldo1 = 0;
										 $saldo2 = 0;
										 $saldo3 = 0;

										 $saldo = 0;								
										} else {
									?>		
										<td width="15%" ></td>
									<?php }

									$total = $total+$movimiento->total; // number_format($movimiento->total, 2, '.', ','); 
									
									$saldo1 = $saldo1 + ((($movimiento->monto_restante==null) ? $movimiento->total : $movimiento->monto_restante) *(abs($movimiento->diferencia_dias-$movimiento->dias_ctas_pagar)<=7));

									$saldo2 = $saldo2 + ( (($movimiento->monto_restante==null) ? $movimiento->total : $movimiento->monto_restante) 
										*((abs($movimiento->diferencia_dias-$movimiento->dias_ctas_pagar)>=8) &&(abs($movimiento->diferencia_dias-$movimiento->dias_ctas_pagar)<=14)) );

									$saldo3 = $saldo3 + ( (($movimiento->monto_restante==null) ? $movimiento->total : $movimiento->monto_restante)
										   *(abs($movimiento->diferencia_dias-$movimiento->dias_ctas_pagar)>=15) );
									$saldo = $saldo+ (($movimiento->monto_restante==null) ? $movimiento->total : $movimiento->monto_restante);

									$saldo21 = $saldo21 + ((($movimiento->monto_restante==null) ? $movimiento->total : $movimiento->monto_restante) *(abs($movimiento->diferencia_dias-$movimiento->dias_ctas_pagar)<=7));

									$saldo22 = $saldo22 + ( (($movimiento->monto_restante==null) ? $movimiento->total : $movimiento->monto_restante) 
										*((abs($movimiento->diferencia_dias-$movimiento->dias_ctas_pagar)>=8) &&(abs($movimiento->diferencia_dias-$movimiento->dias_ctas_pagar)<=14)) );

									$saldo23 = $saldo23 + ( (($movimiento->monto_restante==null) ? $movimiento->total : $movimiento->monto_restante)
										   *(abs($movimiento->diferencia_dias-$movimiento->dias_ctas_pagar)>=15) );
									$saldo20 = $saldo20+ (($movimiento->monto_restante==null) ? $movimiento->total : $movimiento->monto_restante);
									

									?> 


									<td width="6%" ><?php echo $movimiento->movimiento; ?></td>		
									<td width="8%" ><?php echo $movimiento->factura; ?></td>
									
									<td width="7%" ><?php echo $movimiento->almacen; ?></td>
									<td width="10%" ><?php echo $movimiento->fecha; ?></td>
									<td width="10%" ><?php echo $movimiento->fecha_vencimiento; ?></td>
									
									
									<?php if (abs($movimiento->diferencia_dias-$movimiento->dias_ctas_pagar)<=7) { ?>
										<td width="11%" ><?php echo (($movimiento->monto_restante==null) ? $movimiento->total : $movimiento->monto_restante); ?></td>
									<?php } else { ?>
										<td width="11%" >0.00</td>
									<?php } ?>	
									
									<?php if ((abs($movimiento->diferencia_dias-$movimiento->dias_ctas_pagar)>=8) &&(abs($movimiento->diferencia_dias-$movimiento->dias_ctas_pagar)<=14)) { ?>
										<td width="11%" ><?php echo (($movimiento->monto_restante==null) ? $movimiento->total : $movimiento->monto_restante); ?></td>
									<?php } else { ?>
										<td width="11%" >0.00</td>
									<?php } ?>	

									
									<?php if (abs($movimiento->diferencia_dias-$movimiento->dias_ctas_pagar)>=15) { ?>
										<td width="11%" ><?php echo (($movimiento->monto_restante==null) ? $movimiento->total : $movimiento->monto_restante); ?></td>
									<?php } else { ?>
										<td width="11%" >0.00</td>
									<?php } ?>	
									

									<td width="11%" ><?php echo (($movimiento->monto_restante==null) ? $movimiento->total : $movimiento->monto_restante); ?></td>

									
									
								</tr>
							<?php endforeach; ?>

								<?php if ( ($total!=0) ) { ?>
									<tr>
									<td width="36%" >

									</td>								
									<td width="19%" >
										<b style="font-size:14px;">Subtotal: </b>
										
									</td>									
									<td width="11%" >
										<b style="font-size:14px;"><?php echo number_format($saldo1, 2, '.', ','); ?></b>
										
									</td>		
									<td width="11%" >
										<b style="font-size:14px;"><?php echo number_format($saldo2, 2, '.', ','); ?></b>
										
									</td>		
									<td width="11%" >
										<b style="font-size:14px;"><?php echo number_format($saldo3, 2, '.', ','); ?></b>
										
									</td>		
									<td width="11%" >
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
									<td width="19%" >
										<b style="font-size:14px;">Total: </b>
										
									</td>									
									
									<td width="11%" >
										<b style="font-size:14px;"><?php echo number_format($saldo21, 2, '.', ','); ?></b>
									</td>		
									<td width="11%" >
										<b style="font-size:14px;"><?php echo number_format($saldo22, 2, '.', ','); ?></b>
									</td>		
									<td width="11%" >
										<b style="font-size:14px;"><?php echo number_format($saldo23, 2, '.', ','); ?></b>
									</td>																
									
									<td width="11%" >
										<b style="font-size:14px;"><?php echo number_format($saldo20, 2, '.', ','); ?></b>
									</td>		
								</tr>
						</tfooter>			
							
					</table>
				</div>
			</div>
		</div>




</body>
</html>						
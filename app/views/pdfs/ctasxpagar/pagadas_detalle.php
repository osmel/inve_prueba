<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="container">
	<div>
		<div>
			<table style="width: 100%; border: 2px solid #222222;">
				<tbody>

					<tr style="font-size: 10px; line-height: 15px; padding: 0px; margin-bottom: 0px;">
						<th width="70%">
							<h1 style="font-size: 12px;"><b >Historico de Pagos por Proveedor</b></h1>
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

						<th width="25%">Proveedor</th>
						<th width="10%">Mov.</th>
						<th width="7%">Factura</th>

						<th width="8%">Almacén</th>
						
						<th width="10%">Tipo</th>
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

						

							<?php  	//si hay un cambio total y saldo =0 
									//y nombre, mov y id_factura toman nuevos valores
							
							if (($nomb != $movimiento->nombre) || ($mov != $movimiento->movimiento) || ($id_factura != $movimiento->id_factura) ){ ?>
								<tr>
									<?php if ($nomb != $movimiento->nombre) { ?>
										<td width="25%" ><?php echo $movimiento->nombre; ?></td>
									<?php } else {	?>
										<td width="25%" ></td>
									<?php }	?>
									<td width="10%" ><?php echo $movimiento->movimiento; ?></td>		
									<td width="7%" ><?php echo $movimiento->factura; ?></td>		
									<td width="8%" ><?php echo $movimiento->almacen; ?></td>
									
									<td width="10%" ><?php echo $movimiento->tipo_factura; ?></td>
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
								} 
							?>	






							<?php if ($movimiento->importe!=0) { //si tiene importe pues que imprima documento, fecha e importe

								?>
								<tr>
									<td width="25%" ></td>
									<td width="10%" ></td> 


									<td width="10%" ></td>
									
									<td width="15%" ></td>
									<td width="15%" ><b><?php echo $movimiento->documento_pago; ?></b></td>
									<td width="10%" ><?php echo $movimiento->fecha_pago; ?></td>
									<td width="15%" style="text-align:right;"><?php echo number_format($movimiento->importe, 2, '.', ','); ?></td>
								</tr>
							<?php }




							//que sume total
							$total = $movimiento->total; //number_format($movimiento->total, 2, '.', ','); 
							$saldo = $saldo+ 
								(($movimiento->id_documento_pago!=12)*$movimiento->importe*-1)+
								(($movimiento->id_documento_pago==12)*$movimiento->importe);

							$total2 = $total2+ number_format($movimiento->total, 2, '.', ','); 
							$total2 = $total2+$movimiento->total; // number_format($movimiento->total, 2, '.', ','); 
							?> 
						
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
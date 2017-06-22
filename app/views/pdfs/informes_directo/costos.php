<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<html lang="es_MX">
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" href="<?php echo base_url(); ?>js/bootstrap-3.3.1/dist/css/bootstrap.min.css">
		
</head>
<body>
<?php date_default_timezone_set('America/Mexico_City');  ?>
		<div class="container">
			<div>
				<div>
					

							

					
					<table style="width: 100%; border: 2px solid #222222; ">
						<thead>

							<tr>
								<td colspan="10" style="border-top: 1px solid #222222; ">
									<span><b>Fecha y hora: </b> <?php echo date( 'd-m-Y h:i:s A',  strtotime ( gmt_to_local( 'UM1', time(), TRUE)  ) );  ?></span>
									<p style="font-size: 10px;"><b >Costos</b></p>
									<span><b>Tipo de Salida: </b> <?php echo $factura;  ?></span>
									
								</td>
								<td colspan="2" style="border-top: 1px solid #222222; ">
								  	
									<?php echo '<img src="'.base_url().'img/unnamed.png" width="93px" height="48px"/>'; ?>
								</td>

							</tr>					


							<tr><th> </th></tr>
							<tr>

								<th width="11%">Código</th>
								<th width="13%">Descripción</th>
								
								<th width="8%">Color</th>
								<th width="9%">Cant.</th>
								
								<th width="9%">Ancho</th>
								<th width="5%">Precio</th>
								<th width="5%">SubTotal</th>
								<th width="5%">IVA</th>

								<th width="6%">Tipo de Factura</th>						
								<th width="5%">Movimiento</th>
								<th width="16%">Proveedor</th>
								<th width="8%">Ingreso</th>
								
								
								
							</tr>
						</thead>
						<tbody>
						<?php if ( isset($movimientos) && !empty($movimientos) ): ?>
							<?php foreach( $movimientos as $movimiento ): ?>
								<tr>
									<td width="11%" style="border-top: 1px solid #222222;"><?php echo $movimiento->codigo; ?></td>					
									<td width="13%" style="border-top: 1px solid #222222;"><?php echo $movimiento->id_descripcion.'<br/><b style="color:red;">Cód: </b>'.$movimiento->codigo_contable; ?></td>
									
									<!--
									<td width="8%" style="border-top: 1px solid #222222;"><?php echo $movimiento->color.'<div style="background-color:#'.$movimiento->hexadecimal_color.';display:block;width:15px;height:15px;margin:0 auto;"></div>'; ?></td>
									-->
									<td width="8%" style="border-top: 1px solid #222222;"><?php echo $movimiento->color; ?></td>

									<td width="9%" style="border-top: 1px solid #222222;"><?php echo $movimiento->cantidad; ?></td>

									<td width="9%" style="border-top: 1px solid #222222;"><?php echo $movimiento->ancho; ?></td>
									<td width="9%" style="border-top: 1px solid #222222;"><?php echo number_format($movimiento->precio, 2, '.', ','); ?></td>				
									<td width="5%" style="border-top: 1px solid #222222;"><?php echo number_format($movimiento->subtotal, 2, '.', ','); ?></td>				
									<td width="5%" style="border-top: 1px solid #222222;"><?php echo number_format($movimiento->sum_iva, 2, '.', ','); ?></td>				

									<td width="6%" style="border-top: 1px solid #222222;"><?php echo $movimiento->t_factura; ?></td>		
									<td width="5%" style="border-top: 1px solid #222222;"><?php echo $movimiento->movimiento; ?></td>

									<td width="16%" style="border-top: 1px solid #222222;"><?php echo $movimiento->nombre; ?></td>
									<td width="8%" style="border-top: 1px solid #222222;"><?php echo date( 'd-m-Y', strtotime($movimiento->fecha)); ?></td>
									
								</tr>
							<?php endforeach; ?>
						<?php else : ?>
								<tr class="noproducto">
									<td colspan="11">No se han agregado producto</td>
								</tr>
						<?php endif; ?>	
								<tr>
									<td colspan="12" style="border-top: 1px solid #222222; ">
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

											<br/>
											<?php  if ($totales->metros>0) { ?>	
												<span><b>Costo de mts: </b> <?php echo number_format($totales->subtotal/$totales->metros, 2, '.', ','); ?></span>
											<?php } ?>		
											<?php  if ($totales->kilogramos>0) { ?>	
												<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Costo de kgs: </b> <?php echo number_format($totales->subtotal/$totales->kilogramos, 2, '.', ',') ; ?></span>
											<?php } ?>	

										
									</td>



								</tr>


						</tbody>	
							
						
							
					</table>
				</div>
			</div>
		</div>



</body>
</html>							
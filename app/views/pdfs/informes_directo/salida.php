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
					

						
					<table style="width: 100%; border: 2px solid #222222; border-collapse: initial !important; ">
						<thead>
							<tr>
								<td colspan="8" style="border-top: 1px solid #222222; ">
									<span><b>Fecha y hora: </b> <?php echo date( 'd-m-Y h:i:s A',  strtotime ( gmt_to_local( 'UM1', time(), TRUE)  ) );  ?></span>
									<p style="font-size: 10px;"><b >Salidas</b></p>
									<span><b>Tipo de Salida: </b> <?php echo $factura;  ?></span>
								</td>
								<td colspan="3" style="border-top: 1px solid #222222; ">
								  	
									<?php echo '<img src="'.base_url().'img/unnamed.png" width="93px" height="48px"/>'; ?>
								</td>

							</tr>

							<tr><th> </th></tr>
							<tr>

								<th width="<?php echo (($configuracion->activo==0)?17:11);  ?>%">Código</th>
								<th width="16%">Descripción</th>
								
								<th width="5%">Color</th>
								<th width="6%">Cant.</th>
								
								<th width="9%">Ancho</th>
								<th width="5%">Mov.</th>
								
								<th width="16%">Cliente</th>
								<th width="6%">Lote</th>
								<th width="8%">Egreso</th>
								<?php if (($configuracion->activo==1)) {  ?>
									<th width="6%">Factura</th>			
								<?php } ?>	
								<th width="6%">Almacén</th>							

								<th width="6%">Tipo Salida</th>	
								
							</tr>
						</thead>
						<tbody >
						<?php if ( isset($movimientos) && !empty($movimientos) ): ?>
							<?php foreach( $movimientos as $movimiento ): ?>
								<tr>
									<td width="<?php echo (($configuracion->activo==0)?17:11);  ?>%" style="border-top: 1px solid #222222;"><?php echo $movimiento->codigo; ?></td>								
									<td width="16%" style="border-top: 1px solid #222222;"><?php echo $movimiento->id_descripcion.'<br/><b style="color:red;">Cód: </b>'.$movimiento->codigo_contable; ?></td>
									
									<td width="5%" style="border-top: 1px solid #222222;"><?php echo $movimiento->color.'<div style="background-color:#'.$movimiento->hexadecimal_color.';display:block;width:15px;height:15px;margin:0 auto;"></div>'; ?></td>
									<td width="6%" style="border-top: 1px solid #222222;"><?php echo $movimiento->cantidad_um.' '.$movimiento->medida; ?></td>
									<td width="9%" style="border-top: 1px solid #222222;"><?php echo $movimiento->ancho.' cm'; ?></td>
									<td width="5%" style="border-top: 1px solid #222222;"><?php echo $movimiento->mov_salida; ?></td>
									
									<td width="16%" style="border-top: 1px solid #222222;"><?php echo $movimiento->nombre; ?></td>
									<td width="6%" style="border-top: 1px solid #222222;"><?php echo $movimiento->id_lote.'-'.$movimiento->consecutivo;  ?></td>
									<td width="8%" style="border-top: 1px solid #222222;"><?php echo date( 'd-m-Y', strtotime($movimiento->fecha_salida)); ?></td>
									<?php if (($configuracion->activo==1)) {  ?>
										<td width="6%" style="border-top: 1px solid #222222;"><?php echo $movimiento->factura; ?></td>
									<?php } ?>		
									<td width="6%" style="border-top: 1px solid #222222;"><?php echo $movimiento->almacen; ?></td>

									<?php 	




									?>

									<td width="6%" style="border-top: 1px solid #222222;"><?php echo ($movimiento->id_tipo_pedido == 1)  ? $movimiento->tipo_factura : $movimiento->tipo_pedido; ?></td>
								</tr>
							<?php endforeach; ?>
						<?php else : ?>
								<tr class="noproducto">
									<td colspan="11">No se han agregado producto</td>
								</tr>
						<?php endif; ?>	
						
						<tr>
								
									<td colspan="11" style="border-top: 1px solid #222222; ">
											
											<?php  if ($totales->metros>0) { ?>	
												<span><b>Total Metros: </b> <?php echo $totales->metros; ?></span>
											<?php } ?>		
											<?php  if ($totales->kilogramos>0) { ?>	
												<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Total Kilogramos: </b> <?php echo $totales->kilogramos; ?></span>
											<?php } ?>	

											<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Total Piezas: </b><?php echo $totales->pieza; ?></span>

									</td>							
						</tr>
						

						</tbody>	


						
								
							
					</table>
				</div>
			</div>
		</div>


	
	
</body>
</html>				
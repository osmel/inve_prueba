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
						<thead>

							<tr>
								<td colspan="7" style="border-top: 1px solid #222222; ">
										<span><b>Proveedor: </b> <?php echo strtoupper($movimientos[0]->nombre); ?></span><br>
										<span><b>Fecha y hora: </b> <?php echo $movimientos[0]->fecha; ?></span><br>
										<span><b>Movimiento: </b><?php echo $movimientos[0]->movimiento; ?></span><br>
										

										<?php if (($configuracion->activo==1)) {  ?>
											<span><b>Factura: </b> <?php echo $movimientos[0]->factura; ?></span><br>
										<?php } ?>

										<span><b>Tipo de Operación: </b> <?php echo $etiq_mov; ?></span><br>
										<span><b>Almacén: </b> <?php echo $movimientos[0]->almacen; ?></span><br>
								</td>
								<td colspan="2" style="border-top: 1px solid #222222; ">
								<?php if ($movimientos[0]->id_factura!=2) { ?>
										<?php echo '<img src="'.base_url().'img/unnamed.png" width="93px" height="48px"/>'; ?>
									<?php } ?>	

								
								</td>

							</tr>



							<tr>
								<th colspan="9">
									<p><b>Productos</b></p>
								</th>
							</tr>
							<tr>
								<th width="20%">Código</th>
								<th width="20%">Descripción</th>
								<th width="10%">Color</th>
								<th width="4%">UM</th>
								<th width="10%">Cant.</th>
								<th width="9%">Precio</th>
								<th width="9%">Ancho</th>
								<th width="9%">Peso Real</th>
								<th width="9%">Lote</th>
							</tr>
						</thead>	
						<tbody>	
						<?php if ( isset($movimientos) && !empty($movimientos) ): ?>
							<?php foreach( $movimientos as $movimiento ): ?>
								<tr>
									<td width="20%" style="border-top: 1px solid #222222;"><?php echo $movimiento->codigo; ?></td>								
									<td width="20%" style="border-top: 1px solid #222222;"><?php echo $movimiento->id_descripcion.'<br/><b style="color:red;">Cód: </b>'.$movimiento->codigo_contable; ?></td>
									<td width="10%" style="border-top: 1px solid #222222;"><?php echo $movimiento->color; ?></td>
									<td width="4%" style="border-top: 1px solid #222222;"><?php echo $movimiento->medida; ?></td>
									<td width="10%" style="border-top: 1px solid #222222;"><?php echo $movimiento->cantidad_um; ?></td>
									<td width="9%" style="border-top: 1px solid #222222;"><?php echo $movimiento->precio; ?></td>
									<td width="9%" style="border-top: 1px solid #222222;"><?php echo $movimiento->ancho; ?></td>
									<td width="9%" style="border-top: 1px solid #222222;"><?php echo $movimiento->peso_real; ?></td>
									<td width="9%" style="border-top: 1px solid #222222;"><?php echo $movimiento->id_lote; ?> - <?php echo $movimiento->consecutivo; ?></td>
								</tr>
							<?php endforeach; ?>



						<?php else : ?>
								<tr class="noproducto">
									<td colspan="8">No se han agregado producto</td>
								</tr>
						<?php endif; ?>	

								<tr>

									<td colspan="9" style="border-top: 1px solid #222222; ">
										<span><b>Subtotal: </b><?php echo number_format($totales->sum_precio, 2, '.', ','); ?></span>
											<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Iva: </b><?php echo number_format($totales->sum_iva, 2, '.', ','); ?></span>
											<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Total: </b><?php echo number_format($totales->sum_total, 2, '.', ','); ?></span><br>
											
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
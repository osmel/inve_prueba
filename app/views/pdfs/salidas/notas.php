<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="container">
	<div>
		<div>
			<table style="width: 100%; border: 2px solid #222222;">
				<tbody>
					<tr>
						<th width="70%" style="font-size:10px; line-height: 15px;">
								<span><b>Vendedor: </b> <?php echo $movimientos[0]->cliente; ?></span><br>
								<span><b>Cargador: </b> <?php echo $movimientos[0]->cargador; ?></span><br>
								<span><b>Fecha y hora: </b> <?php echo $movimientos[0]->fecha; ?></span><br>
								<span><b>Movimiento: </b><?php echo $movimientos[0]->mov_salida; ?></span><br>
								<span><b>Mov Pedido: </b><?php echo $movimientos[0]->mov_pedido; ?></span><br>
								<?php if (($configuracion->activo==1)) {  ?>
									<span><b>Factura: </b> <?php echo $movimientos[0]->factura; ?></span><br>
								<?php } ?>
								<span><b>Tipo de Operación: </b> <?php echo $etiq_mov; ?></span><br> 

								<span><b>Almacén: </b> <?php echo $movimientos[0]->almacen; ?></span><br> 
								<?php if ($movimientos[0]->id_tipo_factura!=0) { ?>
										<span><b>Tipo de Salida: </b> <?php echo $movimientos[0]->tipo_factura; ?></span><br>  
								<?php } else { ?>
									<span><b>Tipo de Salida: </b> <?php echo $movimientos[0]->tipo_pedido; ?></span><br>  
								<?php } ?>	

								<?php if ($movimientos[0]->id_apartado==3) { ?>
										<span><b>Cliente: </b> <?php echo $movimientos[0]->cliente_apartado; ?></span> 
								<?php } else { ?>
									<span><b>Cliente: </b> <?php echo $movimientos[0]->cliente_pedido; ?></span> 
								<?php } ?>	

						</th>

						<th width="30%" style="text-align:right;">
							<?php if ($movimientos[0]->id_tipo_factura!=2) { ?>
								<?php echo '<img src="'.base_url().'img/unnamed.png" width="93px" height="48px"/>'; ?>
							<?php } ?>	
						</th>
					</tr>
					<tr style="padding:0px;margin:0px;" height="0px;" >
						<th height="0px;">
						</th>
					</tr>

				</tbody>
			</table>
			<table style="width: 100%; border: 2px solid #222222;">
				<thead>
					<tr>
						<th colspan="9">
							<p><b>Productos</b></p>
						</th>
					</tr>
					<tr>
						<th width="20%">Código</th>
						<th width="18%">Descripción</th>
						<th width="8%">Color</th>
						<th width="4%">UM</th>
						<th width="10%">Cant.</th>
						<th width="9%">Ancho</th>
						<th width="6%">Peso entrada</th>
						<th width="6%">Peso Real</th>
						<th width="9%">Lote</th>
						<th width="10%">Vendedor</th>

						
					</tr>
				</thead>
				<tbody>
				<?php if ( isset($movimientos) && !empty($movimientos) ): ?>
					<?php foreach( $movimientos as $movimiento ): ?>
						<tr>
							<td width="20%" style="border-top: 1px solid #222222;"><?php echo $movimiento->codigo; ?></td>								
							<td width="18%" style="border-top: 1px solid #222222;"><?php echo $movimiento->id_descripcion.'<br/><b style="color:red;">Cód: </b>'.$movimiento->codigo_contable; ?></td>
							<td width="8%" style="border-top: 1px solid #222222;"><?php echo $movimiento->color; ?></td>
							<td width="4%" style="border-top: 1px solid #222222;"><?php echo $movimiento->medida; ?></td>
							<td width="10%" style="border-top: 1px solid #222222;"><?php echo $movimiento->cantidad_um; ?></td>
							<td width="9%" style="border-top: 1px solid #222222;"><?php echo $movimiento->ancho; ?></td>
							<td width="6%" style="border-top: 1px solid #222222;"><?php echo $movimiento->peso_entrada; ?></td>
							<td width="6%" style="border-top: 1px solid #222222;"><?php echo $movimiento->peso_real; ?></td>
							<td width="9%" style="border-top: 1px solid #222222;"><?php echo $movimiento->id_lote; ?> - <?php echo $movimiento->consecutivo; ?></td>
							<td width="10%" style="border-top: 1px solid #222222;"><?php echo $movimiento->nom_vendedor; ?></td>
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
							<td width="100%" style="border-top: 1px solid #222222; font-size: 10px; line-height: 15px; padding: 0px; margin-bottom: 0px;">
									

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

									<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Total Peso real: </b> <?php echo $totales->peso_real; ?></span><br>
								
							</td>





						</tr>
				</tfooter>			
					
			</table>
		</div>

	</div>
	<br/><br/><br/>
		
	<table style="width:100%">
	  <tr>
	  	<td>
			<hr style="padding: 0px; margin: 30px; width: 200px;"/>	
			<span><b>Cargador: </b> <?php echo $movimientos[0]->cargador; ?></span><br>
		</td>	
	
	    <td>
			<hr style="padding: 0px; margin: 30px; width: 200px;"/>	
			<span><b>Recibe: </b> </span><br>
		</td>
	    
	  </tr>
	</table>
</div>
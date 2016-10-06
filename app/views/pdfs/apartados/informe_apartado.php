<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div class="container">
	<div>
		<div>
			
			<table style="width: 100%; border: 2px solid #222222;">
				<tbody>


					<tr style="font-size: 10px; line-height: 15px; padding: 0px; margin-bottom: 0px;">
						<th width="70%">
									
									<span><b>Tipo de Pedido: </b> <?php echo $totales->tipo_pedido; ?></span><br>

									<?php  if ($totales->tipo_factura!="no") { ?>	
										<span><b>Tipo de factura: </b> <?php echo $totales->tipo_factura; ?></span><br>
									<?php } ?>		


							<span><b>Cliente: </b> <?php echo $descripcion;  ?>  <br/><b>Nro.</b> <?php echo $consecutivo;  ?></span> <br/>
							<span><b>Fecha y hora: </b> <?php echo date( 'd-m-Y h:i:s A');  ?></span>
							<p style="font-size: 10px;"><b >Apartados Informe</b></p>
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
						<th width="25%">Código</th>
						<th width="25%">Descripción</th>
						<th width="10%">Color</th>
						<th width="15%">Cantidad</th>
						<th width="15%">Ancho</th>
						<!--<th width="10%">Precio</th>-->
						<th width="10%">Lote</th>
					</tr>
				</thead>
				<tbody>
				<?php if ( isset($movimientos) && !empty($movimientos) ): ?>
					<?php foreach( $movimientos as $movimiento ): ?>
						<tr>
							<td width="25%" style="border-top: 1px solid #222222;"><?php echo $movimiento->codigo; ?></td>								
							<td width="25%" style="border-top: 1px solid #222222;"><?php echo $movimiento->id_descripcion.'<br/><b style="color:red;">Cód: </b>'.$movimiento->codigo_contable; ?></td>
							<td width="10%" style="border-top: 1px solid #222222;"><?php echo $movimiento->color.'<div style="background-color:#'.$movimiento->hexadecimal_color.';display:block;width:15px;height:15px;margin:0 auto;"></div>'; ?></td>
							<td width="15%" style="border-top: 1px solid #222222;"><?php echo $movimiento->cantidad_um.' '.$movimiento->medida; ?></td>
							<td width="15%" style="border-top: 1px solid #222222;"><?php echo $movimiento->ancho.' cm'; ?></td>
							<!-- <td width="10%" style="border-top: 1px solid #222222;"><?php echo $movimiento->precio; ?></td>-->
							<td width="10%" style="border-top: 1px solid #222222;"><?php echo $movimiento->id_lote.'-'.$movimiento->consecutivo;  ?></td>

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
						<!--
							<td width="100%" style="border-top: 1px solid #222222; font-size: 10px; line-height: 15px; padding: 0px; margin-bottom: 0px;">
									<?php  if ($totales->metros>0) { ?>	
										<span><b>Total Metros: </b> <?php echo $totales->metros; ?></span><br>
									<?php } ?>		
									<?php  if ($totales->kilogramos>0) { ?>	
										<span><b>Total Kilogramos: </b> <?php echo $totales->kilogramos; ?></span><br>
									<?php } ?>	

									<span><b>Total Piezas: </b><?php echo $totales->pieza; ?></span><br>
							</td>

							-->
							<td width="100%" style="border-top: 1px solid #222222; font-size: 10px; line-height: 15px; padding: 0px; margin-bottom: 0px;">
									
									<?php  if ($totales->metros>0) { ?>	
										<span><b>Total Metros: </b> <?php echo $totales->metros; ?></span>
									<?php } ?>		
									<?php  if ($totales->kilogramos>0) { ?>	
										<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Total Kilogramos: </b> <?php echo $totales->kilogramos; ?></span>
									<?php } ?>	

									<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Total Piezas: </b><?php echo $totales->pieza; ?></span>

							</td>


						</tr>
				</tfooter>

				
					
			</table>
		</div>
	</div>
</div>
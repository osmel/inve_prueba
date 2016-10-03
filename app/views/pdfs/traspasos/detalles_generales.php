<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="container">
	<div>
		<div>
			<table style="width: 100%; border: 2px solid #222222;">
				<tbody>
					<tr>
						<td>
							<p style="font-size: 15px; line-height: 20px; padding: 0px; margin-bottom: 0px;">

									<span><b>Traspaso: </b> <?php echo $movimientos[0]->t_factura; ?></span><br>
									<span><b>Responsable: </b> <?php echo $movimientos[0]->vendedor; ?></span><br>
									<span><b>Dependencia: </b> <?php echo $movimientos[0]->dependencia; ?></span><br>
									<span><b>Almacén: </b> <?php echo $movimientos[0]->almacen; ?></span><br>
									<span><b>Fecha: </b> <?php echo $movimientos[0]->fecha_apartado; ?></span><br>

									<?php	
		                              if ($movimientos[0]->apartado==3) {
		                                 $num=$movimientos[0]->consecutivo_venta;
		                              } else  {
		                                 $num= $movimientos[0]->id_cliente_apartado;
		                              }   

		                              if ($movimientos[0]->apartado!=0) {
		                                  $proceso = "automatico";
		                                  $motivos =  $movimientos[0]->tipo_pedido.' <b>Nro.</b>'.$num;
		                              } else {
		                                  $proceso = "manual";
		                                  $motivos = "comentario";
		                              }    

		                            ?>  

		                            <span><b>Motivos: </b> <?php echo $motivos; ?></span><br>
								
							</p>
						</td>
						<td style="text-align: right;">
							<p>&nbsp;</p>
							<?php echo '<img src="'.base_url().'img/unnamed.png" width="93px" height="50px"/>'; ?>
						</td>
					</tr>
				</tbody>
			</table>
			<table style="width: 100%; border: 2px solid #222222; font-size: 12px;">
				<thead>
					<tr>
						<th colspan="9">
							<p><b>Productos</b></p>
						</th>
					</tr>
					<tr>
						<th width="30%">Código</th>
						<th width="25%">Descripción</th>
						<th width="8%">Color</th>
						<th width="10%">Cantidad</th>
						<th width="10%">Ancho</th>
						<?php if ($configuracion->activo==1) { ?> 
							<th width="9%">Lote</th>
							<th width="8%">Precio</th>
						<?php } else { ?> 	
							<th width="17%">Lote</th>
						<?php }  ?> 


					</tr>
				</thead>	
				<tbody>	
				<?php if ( isset($movimientos) && !empty($movimientos) ): ?>
					<?php foreach( $movimientos as $movimiento ): ?>
						<tr>

							
							<td width="30%" style="border-top: 1px solid #222222;"><?php echo $movimiento->codigo; ?></td>								
							<td width="25%" style="border-top: 1px solid #222222;"><?php echo $movimiento->id_descripcion.'<br/><b style="color:red;">Cód: </b>'.$movimiento->codigo_contable; ?></td>
							<td width="8%" style="border-top: 1px solid #222222;">
								<div style="background-color:#<?php echo $movimiento->hexadecimal_color; ?>;display:block;width:15px;height:15px;margin:0 auto;"></div>
							</td>
							<td width="10%" style="border-top: 1px solid #222222;"><?php echo $movimiento->cantidad_um; ?> <?php echo $movimiento->medida; ?></td>
							<td width="10%" style="border-top: 1px solid #222222;"><?php echo $movimiento->ancho; ?> cm</td>
							
							<?php if ($configuracion->activo==1) { ?> 
								<td width="9%" style="border-top: 1px solid #222222;"><?php echo $movimiento->id_lote.'-'.$movimiento->consecutivo; ?></td>							
								<td width="8%" style="border-top: 1px solid #222222;"><?php echo $movimiento->precio+($movimiento->precio*$movimiento->iva)/100; ?></td>
							<?php } else {  ?> 	
								<td width="17%" style="border-top: 1px solid #222222;"><?php echo $movimiento->id_lote.'-'.$movimiento->consecutivo; ?></td>							
							<?php }  ?> 	



						</tr>
					<?php endforeach; ?>
				<?php else : ?>
						<tr class="noproducto">
							<td colspan="9">No se han agregado producto</td>
						</tr>
				<?php endif; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
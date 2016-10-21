<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php $perfil= $this->session->userdata('id_perfil'); ?>

<div class="container">
	<div>
		<div>
			<table style="width: 100%; border: 2px solid #222222;">
				<tbody>
					<tr>
						<td>
							<p style="font-size: 15px; line-height: 20px; padding: 0px; margin-bottom: 0px;">

									<span><b>Fecha: </b> <?php echo $movimientos[0]->fecha_entrada; ?></span><br>
									<span><b>No. Movimiento: </b> <?php echo $movimientos[0]->movimiento; ?></span><br>
									<?php if (($configuracion->activo==1)) {  ?> 
										<span><b>Nro. Control: </b> <?php echo $movimientos[0]->factura; ?></span><br>
									<?php }  ?> 	

									<span><b>Almacén: </b> <?php echo $movimientos[0]->almacen; ?></span><br>
									<span><b>Proveedor: </b> <?php echo $movimientos[0]->proveedor; ?></span><br>
		                            <span><b>Comentario: </b> <?php echo $movimientos[0]->movimiento; ?></span><br>
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
						
						<th width="20%">Nombre de Tela</th>
						<th  width="10%">Imagen</th>
						<th  width="10%">Color</th>
						
						<th  width="10%">Ancho</th>
						<th  width="10%">Composición</th>
						<th  width="8%">Calidad</th>
						<?php if ($perfil!=2) { ?>
							<th  width="8%">Precio</th>
						<?php } ?>	
						
						<th width="<?php echo ( ($perfil!=2) ? '8%' : '16%') ?>">Cant. Disponible</th>
						
						<th width="8%">Cant. Solicitada</th>
						<th width="8%">Cant. Aprobada</th>
						


					</tr>
				</thead>	
				<tbody>	
				<?php if ( isset($movimientos) && !empty($movimientos) ): ?>
					<?php foreach( $movimientos as $movimiento ): ?>
						<tr>

							
							<td width="20%" style="border-top: 1px solid #222222;"><?php echo $movimiento->descripcion.'<br/><b style="color:red;">Cód: </b>'.$movimiento->codigo_contable; ?> </td>	

							 <?php
		                            $fechaSegundos = time(); 
		                            $strNoCache = "?nocache=$fechaSegundos"; 
			                        $nombre_fichero ='';
			                        $nombre_fichero ='uploads/productos/thumbnail/300X300/'.substr($movimiento->imagen,0,strrpos($movimiento->imagen,".")).'_thumb'.substr($movimiento->imagen,strrpos($movimiento->imagen,"."));
			                        if (file_exists($nombre_fichero)) {
			                            $imagen ='<img src="'.base_url().$nombre_fichero.$strNoCache.'" border="0" width="100%" height="auto">';
			                        } else {
			                            $imagen ='<img src="img/sinimagen.png" border="0" width="75" height="75">';
			                        }
							?>							
							<td width="10%" style="border-top: 1px solid #222222;"><?php echo $imagen; ?></td>
							<td width="10%" style="border-top: 1px solid #222222;"><?php echo $movimiento->nombre_color.
                                        '<div style="background-color:#'.$movimiento->hexadecimal_color.';display:block;width:15px;height:15px;margin:0 auto;"></div>'
							; ?></td>

							<td width="10%" style="border-top: 1px solid #222222;"><?php echo $movimiento->ancho; ?></td>
							<td width="10%" style="border-top: 1px solid #222222;"><?php echo $movimiento->composicion; ?></td>
							<td width="8%" style="border-top: 1px solid #222222;"><?php echo $movimiento->calidad; ?></td>

							<?php if ($perfil!=2) { ?>
							     <td width="8%" style="border-top: 1px solid #222222;"><?php echo number_format($movimiento->precio, 2, '.', ','); ?></td>
							<?php } ?>	
						
							


							<td width="<?php echo ( ($perfil!=2) ? '8%' : '16%') ?>" style="border-top: 1px solid #222222;"><?php echo 'Optimo:'.$movimiento->minimo.'<br/>  Reales:'. $movimiento->suma; ?></td>
							<td width="8%" style="border-top: 1px solid #222222;"><?php echo $movimiento->cantidad_pedida; ?></td>
							<td width="8%" style="border-top: 1px solid #222222;"><?php echo $movimiento->cantidad_aprobada; ?></td>
							


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
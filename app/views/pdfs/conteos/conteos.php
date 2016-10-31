<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="container">
	<div>
		<div>
			<table style="width: 100%; border: 2px solid #222222;">
				<tbody>
					

					<tr>
						<th width="70%" style="font-size:10px; line-height: 15px;">

								<span><b>Conteo: </b> <?php echo  $modulo-1; ?></span><br>

						</th>

						<th width="30%" style="text-align:right;">
								<?php echo '<img src="'.base_url().'img/unnamed.png" width="93px" height="48px"/>'; ?>
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
						<th class="text-center " width="15%"><strong>Referencia</strong></th>
						<th class="text-center " width="25%"><strong>Nombre de Tela</strong></th>
						<th class="text-center " width="15%"><strong>Imagen</strong></th>
						<th class="text-center " width="10%"><strong>Color</strong></th>
						<th class="text-center " width="15%"><strong>Composición</strong></th>
						<th class="text-center " width="10%"><strong>Calidad</strong></th>
						<th class="text-center " width="10%"><strong>Cantidad</strong></th>

						
					</tr>
				</thead>
				<tbody>
				<?php if ( isset($movimientos) && !empty($movimientos) ): ?>
					<?php foreach( $movimientos as $movimiento ): ?>
						<?php
							$nombre_fichero ='uploads/productos/thumbnail/300X300/'.substr($movimiento->imagen,0,strrpos($movimiento->imagen,".")).'_thumb'.substr($movimiento->imagen,strrpos($movimiento->imagen,"."));
							if (file_exists($nombre_fichero)) {
							$imagen ='<img src="'.base_url().$nombre_fichero.'" border="0" width="35" height="35">';

							} else {
							$imagen ='<img src="img/sinimagen.png" border="0" width="35" height="35">';
							}
							?>						
						<tr>
							<td width="15%" style="border-top: 1px solid #222222;"><?php echo $movimiento->referencia; ?></td>		
							<td width="25%" style="border-top: 1px solid #222222;"><?php echo $movimiento->descripcion.'<br/><b style="color:red;">Cód: </b>'.$movimiento->codigo_contable; ?></td>		
							<td width="15%" style="border-top: 1px solid #222222;"><?php echo $imagen; ?></td>		
							<td width="10%" style="border-top: 1px solid #222222;"><?php echo
								$movimiento->nombre_color.                                      
								'<div style="background-color:#'.$movimiento->hexadecimal_color.';display:block;width:15px;height:15px;margin:0 auto;"></div>';

							  ?></td>		
							<td width="15%" style="border-top: 1px solid #222222;"><?php echo $movimiento->composicion; ?></td>		
							<td width="10%" style="border-top: 1px solid #222222;"><?php echo $movimiento->calidad; ?></td>		
							<td width="10%" style="border-top: 1px solid #222222;"><?php echo '<br/><hr style=" width: 100%;"/>'	 ?></td>		

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
	<br/><br/><br/>
		
	<table style="width:100%">
	  <tr>
	  	<td>
			<hr style="padding: 0px; margin: 30px; width: 200px;"/>	
			<span><b>Almacenista: </b> </span><br>
		</td>	
	
	    <td>
			<hr style="padding: 0px; margin: 30px; width: 200px;"/>	
			<span><b>Administrador: </b> </span><br>
		</td>
	    
	  </tr>
	</table>





</div>
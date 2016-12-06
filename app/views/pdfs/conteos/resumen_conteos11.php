<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="container">
	<div>
		<div>
			<table style="width: 100%; border: 2px solid #222222;">
				<tbody>				
					<tr>
						<th width="70%" style="font-size:10px; line-height: 15px;">
								<span><b>Historico de movimientos de conteos </b> </span><br>
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
			                <th rowspan="2" width="10%" align="center"><b>Movimiento</b></th>
			                <th rowspan="2" width="40%" align="center"><b>Filtro</b></th>
			                <th colspan="3" width="25%" align="center"><b>Faltante</b></th>
			                <th colspan="3" width="25%" align="center"><b>Sobrante</b></th>
			            </tr>									
						<tr>
							
							<th class="text-center"><strong>Status</strong></th>
							<th class="text-center"><strong>Realizado</strong></th>
							<th class="text-center"><strong>Mov.</strong></th>

							<th class="text-center"><strong>Status</strong></th>
							<th class="text-center"><strong>Realizado</strong></th>
							<th class="text-center"><strong>Mov.</strong></th>
							

						</tr>
				</thead>
				<tbody>
				<?php if ( isset($movimientos) && !empty($movimientos) ): ?>
					<?php foreach( $movimientos as $movimiento ): ?>
												
						<tr>
							<td width="10%" style="border-top: 1px solid #222222;"><?php echo $movimiento->consecutivo; ?></td>		
							<?php
								  /*
								   $arreglo= explode(";", $movimiento->filtro);
		                           $filtro =''; 
		                           for ($i=0; $i < count($arreglo); $i++) { 
		                             if  ($arreglo[$i]!='') {
		                                $filtro .= (($i!=0) ? '<br/>': '').$arreglo[$i];
		                             }
		                           }
		                           */
							?>



							<td width="40%" style="border-top: 1px solid #222222;"><?php print_r(rtrim(html_entity_decode(strip_tags($movimiento->filtro))) ); ?></td>		

							<td width="8%" style="border-top: 1px solid #222222;"><?php echo ($movimiento->cant_faltante>0) ? "Si":"No"; ?></td>	
							<td width="8%" style="border-top: 1px solid #222222;"><?php echo ($movimiento->mov_faltante!=0) ? "Si":"No"; ?></td>	
						    <td width="9%" style="border-top: 1px solid #222222;"><?php echo ($movimiento->mov_faltante!=0) ? $movimiento->mov_faltante:"-";?></td>									
							<td width="8%" style="border-top: 1px solid #222222;"><?php echo ($movimiento->cant_sobrante>0) ? "Si":"No"; ?></td>	
							<td width="8%" style="border-top: 1px solid #222222;"><?php echo ($movimiento->mov_sobrante!=0) ? "Si":"No"; ?></td>	
						    <td width="9%" style="border-top: 1px solid #222222;"><?php echo ($movimiento->mov_sobrante!=0) ? $movimiento->mov_sobrante:"-";?></td>		
						</tr>
					<?php endforeach; ?>
				<?php else : ?>
						<tr class="noproducto">
							<td colspan="7">No existen movimientos</td>
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
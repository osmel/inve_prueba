<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<script type="text/javascript" src="<?php echo base_url(); ?>js/sistema.js"></script>
<?php
 	if (!isset($retorno)) {
      	$retorno ="catalogos";
    }
?>    

	<div class="container">
		<br>
		<div class="row">
			<div class="col-md-3">
				<a href="<?php echo base_url(); ?>nuevo_unidad_medida" type="button" class="btn btn-success btn-block">Nueva unidad de medida</a>
			</div>
		</div>
		<br>
		<div class="row">
			<div class="col-md-12">
				<h4>Listado de unidades de medidas</h4>
				<div class="table-responsive">
				<table class="table table-striped table-bordered table-responsive tabla_ordenadas" >
					<thead>	
						<tr>
							<th class="text-center cursora" width="70%">medida <i class="glyphicon glyphicon-sort"></i></th>
							<th class="text-center " width="10%"><strong>Editar</strong></th>
							<th class="text-center " width="10%"><strong>Eliminar</strong></th>

						</tr>
					</thead>		
					<?php if ( isset($medidas) && !empty($medidas) ): ?>
						<?php foreach( $medidas as $medida ): ?>
							<tr>
								
								<td class="text-center"><?php echo $medida->medida; ?></td>
								 <td>
									<a href="<?php echo base_url(); ?>editar_unidad_medida/<?php echo $medida->id; ?>" type="button" 
									class="btn btn-warning btn-sm btn-block" >
										<span class="glyphicon glyphicon-edit"></span>
									</a>
								</td>
								<td>
									<a href="<?php echo base_url(); ?>eliminar_unidad_medida/<?php echo $medida->id; ?>/<?php echo base64_encode($medida->medida) ; ?>"  
										class="btn btn-danger btn-sm btn-block" data-toggle="modal" data-target="#modalMessage">
										<span class="glyphicon glyphicon-remove"></span>
										
									</a>
								</td>						
							</tr>
						<?php endforeach; ?>
					<?php else : ?>
							<tr>
								<td colspan="5">No existen medidas</td>
							</tr>
					<?php endif; ?>						

				</table>
			</div>

			</div>
		</div>
		<br>
		<div class="row">

			<div class="col-md-9"></div>
			<div class="col-md-3">
				<a href="<?php echo base_url(); ?>catalogos" class="btn btn-danger btn-block"><i class="glyphicon glyphicon-backward"></i> Regresar</a>
			</div>
		</div>
	</div>

<div class="modal fade bs-example-modal-lg" id="modalMessage" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
        <div class="modal-content"></div>
    </div>
</div>	


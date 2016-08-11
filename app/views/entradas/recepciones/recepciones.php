<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<script type="text/javascript" src="<?php echo base_url(); ?>js/sistema.js"></script>
<?php
 	if (!isset($retorno)) {
      	$retorno ="entradas";
    }
?>    

	<div class="container">
		<br>
		<div class="row">
			<div class="col-md-6">
				<?php echo form_open( '', array( 'class' => 'form-horizontal', 'role' => 'search', 'id' => 'form_search_unidades', 'name' => 'form_search_recepciones', 'autocomplete' => 'off', 'method' => 'GET' ) ); ?>
					<div class="form-group col-md-10 col_100">
						<div class="input-group">
							<input type="text" name="editar_recepcion" class="buscar_palabra form-control typeahead tt-query" autocomplete="off" spellcheck="false" placeholder="Buscar...">
						</div>
					</div>

				<?php echo form_close(); ?>
			</div>
			
			<div class="col-md-3"></div>
			<div class="col-md-3">
				<a href="<?php echo base_url(); ?>nuevo_recepcion" type="button" class="btn btn-success btn-block">Nuevo recepcion</a>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<h4>Listado de recepciones</h4>
				<div class="table-responsive">
				<table class="table table-striped table-bordered table-responsive tabla_ordenadas" >
					<thead>	
						<tr>
							<th class="text-center cursora" width="10%">Id. movimiento <i class="glyphicon glyphicon-sort"></i></th>
							<th class="text-center cursora" width="10%">Recepcion <i class="glyphicon glyphicon-sort"></i></th>
							<th class="text-center cursora" width="45%">Proveedor <i class="glyphicon glyphicon-sort"></i></th>
							<th class="text-center cursora" width="15%">Fecha <i class="glyphicon glyphicon-sort"></i></th>
							<th class="text-center " width="10%"><strong>Editar</strong></th>
							<th class="text-center " width="10%"><strong>Estatus</strong></th>

						</tr>
					</thead>		
					<?php if ( isset($recepciones) && !empty($recepciones) ): ?>
						<?php foreach( $recepciones as $recepcion ): ?>
							<tr>
								<td class="text-center"><?php echo $recepcion->id; ?></td>
								<td class="text-center"><?php echo $recepcion->consecutivo; ?></td>
								<td class="text-center"><?php echo $recepcion->proveedor; ?></td>
								<td class="text-center"><?php echo $recepcion->fecha_mac; ?></td>
								 <td>
									<a href="<?php echo base_url(); ?>editar_recepcion/<?php echo $recepcion->id; ?>" type="button" 
									class="btn btn-warning btn-sm btn-block" >
										<span class="glyphicon glyphicon-edit"></span>
									</a>
								</td>
								<td>
									<a href="<?php echo base_url(); ?>eliminar_recepcion/<?php echo $recepcion->id; ?>/<?php echo base64_encode($recepcion->recepcion) ; ?>"  
										class="btn btn-danger btn-sm btn-block" data-toggle="modal" data-target="#modalMessage">
										<span class="glyphicon glyphicon-remove"></span>
										
									</a>
								</td>						
							</tr>
						<?php endforeach; ?>
					<?php else : ?>
							<tr>
								<td colspan="6">No existen recepciones</td>
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
				<a href="<?php echo base_url(); ?><?php echo $retorno; ?>" class="btn btn-danger btn-block"><i class="glyphicon glyphicon-backward"></i> Regresar</a>
			</div>
		</div>
	</div>

<div class="modal fade bs-example-modal-lg" id="modalMessage" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
        <div class="modal-content"></div>
    </div>
</div>	


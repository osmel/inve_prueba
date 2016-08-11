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
			<div class="col-md-6">
				<?php echo form_open( '', array( 'class' => 'form-horizontal', 'role' => 'search', 'id' => 'form_search_estratificaciones', 'name' => 'form_search_estratificaciones ', 'autocomplete' => 'off', 'method' => 'GET' ) ); ?>
					<div class="form-group col-md-10 col_100">
						<div class="input-group">
							<input type="text" name="editar_estratificacion_empresa" class="buscar_palabra form-control typeahead tt-query" autocomplete="off" spellcheck="false" placeholder="Buscar...">
						</div>
					</div>

				<?php echo form_close(); ?>
			</div>
			
			<div class="col-md-3"></div>
			<div class="col-md-3">
				<a href="<?php echo base_url(); ?>nuevo_estratificacion_empresa" type="button" class="btn btn-success btn-block">Nueva estratificacion empresa</a>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<h4>Listado de estratificaciones  empresas</h4>
				<div class="table-responsive">
				<table class="table table-striped table-bordered table-responsive tabla_ordenadas" >
					<thead>	
						<tr>
							<th class="text-center cursora" width="70%">estratificacion   <i class="glyphicon glyphicon-sort"></i></th>
							<th class="text-center " width="10%"><strong>Editar</strong></th>
							<th class="text-center " width="10%"><strong>Eliminar</strong></th>

						</tr>
					</thead>		
					<?php if ( isset($estratificaciones) && !empty($estratificaciones  ) ): ?>
						<?php foreach( $estratificaciones   as $estratificacion   ): ?>
							<tr>
								
								<td class="text-center"><?php echo $estratificacion  ->estratificacion  ; ?></td>
								 <td>
									<a href="<?php echo base_url(); ?>editar_estratificacion_empresa/<?php echo $estratificacion->id; ?>" type="button" 
									class="btn btn-warning btn-sm btn-block" >
										<span class="glyphicon glyphicon-edit"></span>
									</a>
								</td>
								<td>
									<a href="<?php echo base_url(); ?>eliminar_estratificacion_empresa/<?php echo $estratificacion->id; ?>/<?php echo base64_encode($estratificacion->estratificacion  ) ; ?>"  
										class="btn btn-danger btn-sm btn-block" data-toggle="modal" data-target="#modalMessage">
										<span class="glyphicon glyphicon-remove"></span>
										
									</a>
								</td>						
							</tr>
						<?php endforeach; ?>
					<?php else : ?>
							<tr>
								<td colspan="5">No existen estratificaciones  </td>
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


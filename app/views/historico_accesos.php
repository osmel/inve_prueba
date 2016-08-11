<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<script type="text/javascript" src="<?php echo base_url(); ?>js/sistema.js"></script>

<div class="container margenes">
		<div class="panel panel-primary">
			<div class="panel-heading">Listado de accesos al sistema</div>
			<div class="panel-body">
			<!--
			<div class="col-md-6">
				<?php echo form_open( '', array( 'class' => 'form-horizontal', 'role' => 'search', 'id' => 'form_search_unidades', 'name' => 'form_search_unidades', 'autocomplete' => 'off', 'method' => 'GET' ) ); ?>
					<div class="form-group col-md-10 col_100">
						<div class="input-group">
							<input type="text" name="unidad_detalle" class="buscar_palabra form-control typeahead tt-query" autocomplete="off" spellcheck="false" placeholder="Buscar...">
						</div>
					</div>

				<?php echo form_close(); ?>
			</div>	

			-->	

		<div class="row">
			<div class="col-md-12">
				<div class="table-responsive">	
					<table class="table table-striped table-bordered table-responsive tabla_ordenadas" >
						<thead>	
							<tr>
								<th class="text-left cursora" width="30%">Usuario <i class="glyphicon glyphicon-sort"></i></th>
								<th class="text-left cursora" width="10%">Rol <i class="glyphicon glyphicon-sort"></i></th>
								<th class="text-left cursora" width="10%">email <i class="glyphicon glyphicon-sort"></i></th>
								<th class="text-center cursora">Acceso <i class="glyphicon glyphicon-sort"></i></th>
								<th class="text-center cursora">Dirección IP <i class="glyphicon glyphicon-sort"></i></th>
								<th class="text-center cursora">Navegador <i class="glyphicon glyphicon-sort"></i></th>

							</tr>
						</thead>	

						<?php if ( isset($usuario_historico) && !empty($usuario_historico) ): ?>
							<?php foreach( $usuario_historico as $usuario ): ?>
								<tr>
									<td class="text-center"><?php echo $usuario->nombre.' '.$usuario->apellidos; ?></td>							
									<td class="text-center"><?php echo $usuario->perfil; ?></td>							
									<td class="text-center"><?php echo $usuario->email; ?></td>							
									<td class="text-center"><?php echo $usuario->fecha; ?></td>							
									<td class="text-center"><?php echo $usuario->ip_address; ?></td>							
									<td class="text-center"><?php echo $usuario->user_agent; ?></td>							
								</tr>
							<?php endforeach; ?>
						<?php else : ?>
								<tr>
									<td colspan="6">No existen usuarios en el histórico</td>
								</tr>
						<?php endif; ?>	

						
					</table>
				</div>
			</div>
		</div>
		<br>
		<div class="row">
			<div class="col-sm-8 col-md-9"></div>
			<div class="col-sm-4 col-md-3">
				<a href="<?php echo base_url(); ?>usuarios" class="btn btn-danger btn-block"><i class="glyphicon glyphicon-backward"></i> Regresar</a>
			</div>
		</div>
	</div>

<div class="modal fade bs-example-modal-lg" id="modalMessage2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
        <div class="modal-content"></div>
    </div>
</div>	
</div>
</div>	

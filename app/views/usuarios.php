<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<script type="text/javascript" src="<?php echo base_url(); ?>js/sistema.js"></script>

	<div class="container blanco" style="border-radius:5px;">
		<br>
		<div class="row">
			<!-- Respaldar solo los administradores-->
			
			<?php $perfil= $this->session->userdata('id_perfil');  ?>
			<?php if  ( $perfil == 1 )  { ?>
				<!--
				<div class="col-md-2">
					<a href="<?php echo base_url(); ?>respaldar"  
						type="button" style="margin-bottom:15px;"
						class="btn btn-primary btn-block" data-toggle="modal" data-target="#modalMessage36">
						
						Respaldo
					</a>
				</div>
				-->
				<div class="col-sm-3 col-md-2">
					<a href="<?php echo base_url(); ?>historicoaccesos" type="button" style="margin-bottom:15px;" class="btn btn-primary btn-block">Histórico de accesos</a>					
				</div>

			<?php } ?>		

			<?php if  ( $perfil != 1 )  { ?>
				<div class="col-md-4"></div>
			<?php } ?>		

			<div class="col-sm-3 col-md-2">
				<a href="<?php echo base_url(); ?>nuevo_usuario" type="button" style="margin-bottom:15px;" class="btn btn-primary btn-block">Agregar Usuario</a>					
			</div>
		</div>

		<div class="container row"> <!--margenes -->
			<div class="panel panel-primary">
			<div class="panel-heading">Listado de Usuarios</div>
			<div class="panel-body">

			<div class="col-md-12">
				
				<div class="table-responsive">	
					<table class="table table-striped table-bordered table-responsive tabla_ordenadas" >
					<thead>		
						<tr>
							<th class="text-center cursora" width="25%">Usuario <i class="glyphicon glyphicon-sort"></i></th>
							<th class="text-center cursora" width="20%">Perfil <i class="glyphicon glyphicon-sort"></i></th>
							<th class="text-center cursora" width="20%">E-mail <i class="glyphicon glyphicon-sort"></i></th>
							<th class="text-center cursora" width="20%">Almacén <i class="glyphicon glyphicon-sort"></i></th>
							<th class="text-center" width="5%">Rol</th>
							<th class="text-center" width="5%">Detalle</th>
						</tr>
					</thead>		
					
			 			<?php if ( isset($usuarios) && !empty($usuarios) ): ?>
							<?php foreach( $usuarios as $usuario ): ?>
								<tr>
									<td><?php echo $usuario->nombre.' '.$usuario->apellidos; ?></td>
									<td><?php echo $usuario->perfil; ?></td>
									<td><?php echo $usuario->email; ?></td>
									<td><?php echo $usuario->almacen; ?></td>
									<td>
										<a href="<?php echo base_url(); ?>editar_usuario/<?php echo $usuario->id; ?>" type="button" 
										class="btn btn-warning btn-sm btn-block" >
											<span class="glyphicon glyphicon-edit"></span>
										</a>
									</td>
									<td>

									
										<fieldset <?php 
											if ($usuario->uso==1) 
												$desabilitar_uso='disabled'; 
											else
												$desabilitar_uso=''; 

											echo $desabilitar_uso;?> 
										>									

											<a href="<?php echo base_url(); ?>eliminar_usuario/<?php echo $usuario->id; ?>/<?php echo base64_encode($usuario->nombre)  ; ?>"  
												class="btn btn-danger btn-sm btn-block" data-toggle="modal" data-target="#modalMessage">
												<span class="glyphicon glyphicon-remove"></span>
												
											</a>
										</fieldset>	

									</td>							
								</tr>
							<?php endforeach; ?>
						<?php else : ?>
							<tr>
								<td colspan="5">No existen usuarios para mostrar</td>
							</tr>
						<?php endif; ?>

								
							
						</table>
					</div>	
					</div>
				</div>
			</div>
		</div>


		
		<div class="row">
			<div class="col-sm-8 col-md-9"></div>
			<div class="col-sm-4 col-md-3">
				<a href="<?php echo base_url(); ?>" class="btn btn-danger btn-block"><i class="glyphicon glyphicon-backward"></i> Regresar</a>
			</div>
		</div>
		<br/>

	</div> <!--fin container-->

<div class="modal fade bs-example-modal-lg" id="modalMessage" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
        <div class="modal-content"></div>
    </div>
</div>	
	

<div class="modal fade bs-example-modal-lg" id="modalMessage36" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
        <div class="modal-content"></div>
    </div>
</div>	
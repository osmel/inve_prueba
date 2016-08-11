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
				<a href="<?php echo base_url(); ?>nuevo_tipo_pedido" type="button" class="btn btn-success btn-block">Nuevo tipo pedido</a>
			</div>
		</div>
		<br>
		<div class="container row">
		<div class="panel panel-primary">
			<div class="panel-heading">Listado de tipos pedidos</div>
			<div class="panel-body">
			<div class="col-md-12">
				<div class="table-responsive">
				<table class="table table-striped table-bordered table-responsive tabla_ordenadas" >
					<thead>	
						<tr>
							<th class="text-center cursora" width="70%">Tipo pedido   <i class="glyphicon glyphicon-sort"></i></th>
							<th class="text-center " width="5%"><strong>Editar</strong></th>
							<th class="text-center " width="5%"><strong>Eliminar</strong></th>

						</tr>
					</thead>		
					<?php if ( isset($tipos_pedidos) && !empty($tipos_pedidos  ) ): ?>
						<?php foreach( $tipos_pedidos   as $tipo_pedido   ): ?>
							<tr>
								
								<td class="text-center"><?php echo $tipo_pedido->tipo_pedido  ; ?></td>
								 <td>
									<a href="<?php echo base_url(); ?>editar_tipo_pedido/<?php echo $tipo_pedido->id; ?>" type="button" 
									class="btn btn-warning btn-sm btn-block" >
										<span class="glyphicon glyphicon-edit"></span>
									</a>
								</td>
								<td>
									<!--
									<a href="<?php echo base_url(); ?>eliminar_tipo_pedido/<?php echo $tipo_pedido->id; ?>/<?php echo base64_encode($tipo_pedido->tipo_pedido  ) ; ?>"  
										class="btn btn-danger btn-sm btn-block" data-toggle="modal" data-target="#modalMessage">
										<span class="glyphicon glyphicon-remove"></span>
										
									</a> -->

										<fieldset disabled="">
											  <a href="#" class="btn btn-danger btn-sm btn-block"> 
											  		<span class="glyphicon glyphicon-remove"></span> 
											  </a> 
										</fieldset>

								</td>						
							</tr>
						<?php endforeach; ?>
					<?php else : ?>
							<tr>
								<td colspan="5">No existen tipos_pedidos  </td>
							</tr>
					<?php endif; ?>						

				</table>
			</div>

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


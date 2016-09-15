<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php $this->load->view('header'); ?>

<?php
 	if (!isset($retorno)) {
      	$retorno ="catalogos";
    }
?>    

	<div class="container">
		<br>
		<div class="row">
			<div class="col-md-3">
				<a href="<?php echo base_url(); ?>nuevo_configuracion" type="button" class="btn btn-success btn-block">Nueva Configuración</a>
			</div>
		</div>
		<br>
		<div class="container row">
		<div class="panel panel-primary">
			<div class="panel-heading">Listado de configuraciones</div>
			<div class="panel-body">
			<div class="col-md-12">






				
					<div class="table-responsive">


						<section>
							<table id="tabla_cat_configuraciones" class="display table table-striped table-bordered table-responsive" cellspacing="0" width="100%">
								<thead>
									<tr>
										<th class="text-center cursora" width="15%">Configuración</th>
										<th class="text-center cursora" width="20%">valor</th>
										<th class="text-center cursora" width="20%">Activo</th>
										<th class="text-center " width="5%"><strong>Editar</strong></th>
										<th class="text-center " width="5%"><strong>Eliminar</strong></th>
									</tr>
								</thead>
							</table>
						</section>

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
<?php $this->load->view('footer'); ?>
<div class="modal fade bs-example-modal-lg" id="modalMessage" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
        <div class="modal-content"></div>
    </div>
</div>	


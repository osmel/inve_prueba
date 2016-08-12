<meta charset="UTF-8">
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php $this->load->view('header'); ?>
<?php 

 	if (!isset($retorno)) {
      	$retorno ="configuraciones";
    }

  $hidden = array('id'=>$id);
  $attr = array('class' => 'form-horizontal', 'id'=>'form_catalogos','name'=>$retorno,'method'=>'POST','autocomplete'=>'off','role'=>'form');
  echo form_open('validacion_edicion_configuracion', $attr,$hidden);
?>	
<div class="container">
		<br>
	<div class="row">
		<div class="col-sm-8 col-md-8"><h4>Edición de configuracion</h4></div>
	</div>
	<br>
	<div class="container row">
		<div class="panel panel-primary">
			<div class="panel-heading">Datos de configuracion</div>
			<div class="panel-body">
				

				<div class="col-sm-6 col-md-6">
					<div class="form-group">
						<label for="configuracion" class="col-sm-3 col-md-2 control-label">configuracion</label>
						<div class="col-sm-9 col-md-10">
							<?php 
								$nomb_nom='';
								if (isset($configuracion ->configuracion )) 
								 {	$nomb_nom = $configuracion ->configuracion ;}
							?>
							<input value="<?php echo  set_value('configuracion',$nomb_nom); ?>" type="text" class="form-control" name="configuracion" placeholder="configuracion">
						</div>
					</div>

					<div class="form-group">
						<label for="valor" class="col-sm-3 col-md-2 control-label">valor</label>
						<div class="col-sm-9 col-md-10">
							<?php 
								$nomb_nom='';
								if (isset($configuracion->valor )) 
								 {	$nomb_nom = $configuracion->valor ;}
							?>
							<input value="<?php echo  set_value('valor',$nomb_nom); ?>" type="text" class="form-control" name="valor" placeholder="valor">
						</div>
					</div>					

				</div>


				<div class="col-sm-6 col-md-6">
					<div class="form-group">
						<label for="id_actividad" class="col-sm-12 col-md-12">Activo</label>
						<div class="col-sm-12 col-md-12">
							<div class="checkbox">

										  <label for="activo" class="ttip" title="Activo">

												<?php		
												   if ($configuracion->activo==1) {$marca='checked';} else {$marca='';}
												?>
												
													<input type="checkbox" value="1" name="activo" <?php echo $marca; ?>>
													activo
	
										 </label>		
									   

							</div>		
									
						</div>
					</div>		
				</div>	





			</div>
		</div>
		
		<br>

		<div class="row">
			<div class="col-sm-4 col-md-4"></div>
			<div class="col-sm-4 col-md-4">
				<a href="<?php echo base_url(); ?><?php echo $retorno; ?>" type="button" class="btn btn-danger btn-block">Cancelar</a>
			</div>
			<div class="col-sm-4 col-md-4">
				<input type="submit" class="btn btn-success btn-block" value="Guardar"/>
			</div>
		</div>
		
	</div></div>
  <?php echo form_close(); ?>
<?php $this->load->view('footer'); ?>
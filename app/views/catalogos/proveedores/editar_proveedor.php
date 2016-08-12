<meta charset="UTF-8">
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php $this->load->view('header'); ?>
<?php 

 	if (!isset($retorno)) {
      	$retorno ="proveedores";
    }

    $perfil=$this->session->userdata('id_perfil');
   $coleccion_id_operaciones= json_decode($this->session->userdata('coleccion_id_operaciones')); 
          if ( (count($coleccion_id_operaciones)==0) || (!($coleccion_id_operaciones)) ) {
                $coleccion_id_operaciones = array();
           }       

  $hidden = array('codigo_ant'=>$codigo_ant);
  $attr = array('class' => 'form-horizontal', 'id'=>'form_catalogos','name'=>$retorno,'method'=>'POST','autocomplete'=>'off','role'=>'form');
  echo form_open('validacion_edicion_proveedor', $attr,$hidden);
?>	
<div class="container">
		<br>
	<div class="row">
		<div class="col-sm-8 col-md-8"><h4>Edición de proveedor</h4></div>
	</div>
	<br>
	<div class="container row">


		<div class="panel panel-primary">
			<div class="panel-heading">Datos del proveedor</div>
			<div class="panel-body">

				<div class="col-sm-6 col-md-6">


					<div class="form-group">
						<label for="codigo" class="col-sm-12 col-md-12">Código</label>
						<div class="col-sm-12 col-md-12">
							<?php 
								$nomb_nom='';
								if (isset($proveedor->codigo)) 
								 {	$nomb_nom = $proveedor->codigo;}
							?>
							<input restriccion="numletra" value="<?php echo  set_value('codigo',$nomb_nom); ?>" type="text" class="form-control ttip" title="Este campo solo admite números y letras."  id="codigo" name="codigo" placeholder="Código">
						</div>
					</div>

					<div class="form-group">
						<label for="nombre" class="col-sm-12 col-md-12">Nombre</label>
						<div class="col-sm-12 col-md-12">
							<?php 
								$nomb_nom='';
								if (isset($proveedor->nombre)) 
								 {	$nomb_nom = $proveedor->nombre;}
							?>
							<input value="<?php echo  set_value('nombre',$nomb_nom); ?>" type="text" class="form-control" id="nombre" name="nombre" placeholder="nombre">
						</div>
					</div>


					<div class="form-group">
						<label for="dias_ctas_pagar" class="col-sm-12 col-md-12">Días de Créditos</label>
						<div class="col-sm-12 col-md-12">
						
							<?php 
								$nomb_nom='';
								if (isset($proveedor->dias_ctas_pagar)) 
								 {	$nomb_nom = $proveedor->dias_ctas_pagar;}
							?>
							<input value="<?php echo  set_value('dias_ctas_pagar',$nomb_nom); ?>" type="text" class="form-control" id="dias_ctas_pagar" name="dias_ctas_pagar" placeholder="Días de Créditos">							
							<span>Este campo es válido, sólo para proveedor</span>
						</div>
					</div>
					

					<div class="form-group">
						<label for="telefono" class="col-sm-12 col-md-12">Teléfono empresa</label>
						<div class="col-sm-12 col-md-12">
							<?php 
								$nomb_nom='';
								if (isset($proveedor->telefono)) 
								 {	$nomb_nom = $proveedor->telefono;}
							?>
							<input value="<?php echo  set_value('telefono',$nomb_nom); ?>" type="text" class="form-control" id="telefono" name="telefono" placeholder="teléfono">
						</div>
					</div>

					<div class="form-group">
						<label for="direccion" class="col-sm-12 col-md-12">Domicilio Fiscal</label>
						<div class="col-sm-12 col-md-12">
							<?php 
								$nomb_nom='';
								if (isset($proveedor->direccion)) 
								 {	$nomb_nom = $proveedor->direccion;}
							?>	
							<textarea class="form-control" name="direccion" id="direccion" rows="5" placeholder="Domicilio Fiscal"><?php echo  set_value('direccion',$nomb_nom); ?></textarea>
						</div>
					</div>	

					<div class="form-group">
						<label for="id_actividad" class="col-sm-12 col-md-12">Actividad comercial<span class="obligatorio"> *</span></label>
						<div class="col-sm-12 col-md-12">
							<div class="checkbox">
									<?php
									$activ_id_array =(json_decode($proveedor->coleccion_id_actividad) );				
									if (count($activ_id_array)==0) {  //si el valor esta vacio
										$activ_id_array = array();
									}
									if (!($activ_id_array)) {
										$activ_id_array = array();	
									}
									
									?>							
							
									<?php foreach ( $actividades as $actividad ){ ?>
										  <label for="coleccion_id_actividad" class="ttip" title="<?php echo $actividad->tooltip; ?>">
											<?php		
											   if (in_array($actividad->id, $activ_id_array)) {$marca='checked';} else {$marca='';}
											?>
											
											<?php	if  ( 
												 	(!( ( $perfil == 1 ) || (in_array(8, $coleccion_id_operaciones)) ) )
													  and (!(in_array($actividad->id+13, $coleccion_id_operaciones))) ) { ?>

											  <fieldset disabled>
											 <?php } ?> 

												<input type="checkbox" value="<?php echo $actividad->id; ?>" name="coleccion_id_actividad[]" <?php echo $marca; ?>>
												<?php echo $actividad->actividad; ?>

											<?php	if  ( 
												 	(!( ( $perfil == 1 ) || (in_array(8, $coleccion_id_operaciones)) ) )

											  and (!(in_array($actividad->id+13, $coleccion_id_operaciones))) ) { ?>


											  </fieldset>
											<?php } ?> 
	

										</label>													
									   
									<?php } ?>
							</div>		
									
						</div>
					</div>								



				</div>


			</div>
		</div>


			
		

		<div class="row">
			<div class="col-sm-4 col-md-4"></div>
			<div class="col-sm-4 col-md-4 marginbuttom">
				<a href="<?php echo base_url(); ?><?php echo $retorno; ?>" type="button" class="btn btn-danger btn-block">Cancelar</a>
			</div>
			<div class="col-sm-4 col-md-4">
				<input type="submit" class="btn btn-success btn-block" value="Guardar"/>
			</div>
		</div>

		<br>
		
	</div></div>
  <?php echo form_close(); ?>
<?php $this->load->view('footer'); ?>
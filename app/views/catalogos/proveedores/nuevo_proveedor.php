<meta charset="UTF-8">
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php $this->load->view('header'); ?>
<?php 
 	if (!isset($retorno)) {
      	$retorno ="proveedores";
    }
 $attr = array('class' => 'form-horizontal', 'id'=>'form_catalogos','name'=>$retorno,'method'=>'POST','autocomplete'=>'off','role'=>'form');
 echo form_open('validar_nuevo_proveedor', $attr);
?>		
<div class="container">
		<br>	
	<div class="row">
		<div class="col-sm-8 col-md-8"><h4>Nuevo</h4></div>
	</div>
	<br>
	<div class="container row">

		<div class="panel panel-primary">
			<div class="panel-heading">Datos</div>
			<div class="panel-body">

				<div class="col-sm-6 col-md-6">

					<div class="form-group">
						<label for="codigo" class="col-sm-12 col-md-12">Código</label>
						<div class="col-sm-12 col-md-12">
							<input type="text" class="form-control ttip" title="Este campo solo admite números y letras." restriccion="numletra" id="codigo" name="codigo" placeholder="Código" >

						</div>
					</div>

					<div class="form-group">
						<label for="nombre" class="col-sm-12 col-md-12">Nombre</label>
						<div class="col-sm-12 col-md-12">
							<input type="text" class="form-control" id="nombre" name="nombre" placeholder="nombre">
						</div>
					</div>


					<div class="form-group">
						<label for="dias_ctas_pagar" class="col-sm-12 col-md-12">Días de Créditos</label>
						<div class="col-sm-12 col-md-12">
							<input type="text" class="form-control" id="dias_ctas_pagar" name="dias_ctas_pagar" placeholder="Días de Créditos">
							<span>Este campo es válido, sólo para proveedor</span>
						</div>
					</div>

					<div class="form-group">
						<label for="telefono" class="col-sm-12 col-md-12">Teléfono empresa</label>
						<div class="col-sm-12 col-md-12">
							<input type="text" class="form-control" id="telefono" name="telefono" placeholder="teléfono">
						</div>
					</div>

					<div class="form-group">
						<label for="direccion" class="col-sm-12 col-md-12">Domicilio Fiscal</label>
						<div class="col-sm-12 col-md-12">
							<textarea class="form-control" name="direccion" id="direccion" rows="5" placeholder="Domicilio Fiscal"></textarea>
						</div>
					</div>						

					<div class="form-group">
						<label for="id_actividad" class="col-sm-12 col-md-12">Actividad comercial<span class="obligatorio"> *</span></label>
						<div class="col-sm-12 col-md-12">
							<div class="checkbox">
									<?php foreach ( $actividades as $actividad ){ ?>
										  <label for="coleccion_id_actividad" class="ttip" title="<?php echo $actividad->tooltip; ?>">
											<input type="checkbox" value="<?php echo $actividad->id; ?>" name="coleccion_id_actividad[]"><?php echo $actividad->actividad; ?>
											
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
	</div>
</div>
<?php echo form_close(); ?>
<?php $this->load->view('footer'); ?>
<meta charset="UTF-8">
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php $this->load->view('header'); ?>
<?php 

 	if (!isset($retorno)) {
      	$retorno ="colores";
    }

  $hidden = array('id'=>$id);
  $attr = array('class' => 'form-horizontal', 'id'=>'form_catalogos','name'=>$retorno,'method'=>'POST','autocomplete'=>'off','role'=>'form');
  echo form_open('validacion_edicion_color', $attr,$hidden);
?>	
<div class="container">
		<br>
	<div class="row">
		<div class="col-sm-8 col-md-8"><h4>Edición de color</h4></div>
	</div>
	<br>
	<div class="container row">
		<div class="panel panel-primary">
			<div class="panel-heading">Datos de color</div>
			<div class="panel-body">
				<div class="col-sm-6 col-md-6">
					<div class="form-group">
						<label for="color" class="col-sm-3 col-md-2 control-label">color</label>
						<div class="col-sm-9 col-md-10">
							<?php 
								$nomb_nom='';
								if (isset($color ->color )) 
								 {	$nomb_nom = $color ->color ;}
							?>
							<input value="<?php echo  set_value('color',$nomb_nom); ?>" type="text" class="form-control ttip" title="Ingresar un nuevo color." name="color" placeholder="color">
						</div>
					</div>

				</div>

				<div class="col-sm-6 col-md-6">
					<div class="form-group">
						<span class="mini-bloque">
							<label for="hexadecimal_color" class="col-sm-3 col-md-2 control-label">color</label>
							<div class="col-sm-9 col-md-10">
								<div id="colorPicker"></div>
										<?php 
										$nomb_nom='';
										if (isset($color->hexadecimal_color )) 
										 {	$nomb_nom = $color->hexadecimal_color ;}
										?>
									<input type="text" id="hexadecimal_color" name="hexadecimal_color" placeholder="Color" value="<?php echo  set_value('hexadecimal_color',$nomb_nom); ?>" class="colorSelector ttip" title="Seleccione el tono que más se parezca al nuevo color.">
								
									<!--color anterior o viejo -->
									<div style="background-color:#<?php echo $color->hexadecimal_color; ?>;display:block;width:60px;height:15px;margin:0 auto;"></div>
									
									<div class="colorSelector"></div>
									<em>Aquí puedes seleccionar un color que más se parezca al color de tela que piensas dar de alta.</em>
							</div>	
						</span>
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
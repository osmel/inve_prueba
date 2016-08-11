<meta charset="UTF-8">
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php $this->load->view('header'); ?>
<?php 
 	if (!isset($retorno)) {
      	$retorno ="colores";
    }
 $attr = array('class' => 'form-horizontal', 'id'=>'form_catalogos','name'=>$retorno,'method'=>'POST','autocomplete'=>'off','role'=>'form');
 echo form_open('validar_nuevo_color', $attr);
?>		
<div class="container" style="background-color:transparent !important">
		<br>	
	
	<div class="container row" style="background-color:transparent !important">
		<div class="panel panel-primary">
			<div class="panel-heading">Datos de color</div>
			<div class="panel-body">
				
				<div class="col-sm-6 col-md-6">
					<div class="form-group">
						<label for="color" class="col-sm-3 col-md-2 control-label">Nombre</label>
						<div class="col-sm-9 col-md-10">
							<input type="text" class="form-control ttip" title="Ingresar un nuevo color." id="color" name="color" placeholder="Nombre del color">
							<em>Nombre personalizado del color.</em>
						</div>
					</div>
				</div>
				<div class="col-sm-6 col-md-6">
					<div class="form-group">
						<span class="mini-bloque">
							<label for="hexadecimal_color" class="col-sm-3 col-md-2 control-label">Color</label>
							<div class="col-sm-9 col-md-10">
								<div id="colorPicker"></div>
									<input type="text" id="hexadecimal_color" name="hexadecimal_color" placeholder="Color" value="<?php echo set_value('hexadecimal_color'); ?>" class="colorSelector ttip" title="Seleccione el tono que más se parezca al nuevo color.">
								<div class="colorSelector"></div>
								<em>Selecciona el color.</em>
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
	</div>
</div>
<?php echo form_close(); ?>
<?php $this->load->view('footer'); ?>
<meta charset="UTF-8">
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php $this->load->view('header'); ?>
<?php 
 	if (!isset($retorno)) {
      	$retorno ="anchos";
    }
 $attr = array('class' => 'form-horizontal', 'id'=>'form_catalogos','name'=>$retorno,'method'=>'POST','autocomplete'=>'off','role'=>'form');
 echo form_open('validar_nuevo_ancho', $attr);
?>		
<div class="container">
		<br>	
	<div class="row">
		<div class="col-sm-8 col-md-8"><h4>Nueva ancho</h4></div>
	</div>
	<br>
	<div class="container row">
		<div class="panel panel-primary">
			<div class="panel-heading">Datos de ancho</div>
			<div class="panel-body">
				<div class="col-sm-6 col-md-6">
					<div class="form-group">
						<label for="ancho" class="col-sm-3 col-md-2 control-label">ancho</label>
						<div class="col-sm-9 col-md-10">
							<input type="text" class="form-control" id="ancho" name="ancho" placeholder="ancho">
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
				<input  type="submit" class="btn btn-success btn-block" value="Guardar"/>
			</div>
		</div>
	</div>
</div>
<?php echo form_close(); ?>
<?php $this->load->view('footer'); ?>
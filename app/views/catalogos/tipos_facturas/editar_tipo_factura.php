<meta charset="UTF-8">
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php $this->load->view('header'); ?>
<?php 

 	if (!isset($retorno)) {
      	$retorno ="tipos_facturas";
    }

  $hidden = array('id'=>$id);
  $attr = array('class' => 'form-horizontal', 'id'=>'form_catalogos','name'=>$retorno,'method'=>'POST','autocomplete'=>'off','role'=>'form');
  echo form_open('validacion_edicion_tipo_factura', $attr,$hidden);
?>	
<div class="container">
		<br>
	<div class="row">
		<div class="col-sm-8 col-md-8"><h4>Edición de tipo_factura</h4></div>
	</div>
	<br>
	<div class="container row">
		<div class="panel panel-primary">
			<div class="panel-heading">Datos de tipo_factura</div>
			<div class="panel-body">
				<div class="col-sm-6 col-md-6">
					<div class="form-group">
						<label for="tipo_factura" class="col-sm-3 col-md-2 control-label">tipo_factura</label>
						<div class="col-sm-9 col-md-10">
							<?php 
								$nomb_nom='';
								if (isset($tipo_factura ->tipo_factura )) 
								 {	$nomb_nom = $tipo_factura ->tipo_factura ;}
							?>
							<input value="<?php echo  set_value('tipo_factura',$nomb_nom); ?>" type="text" class="form-control" name="tipo_factura" placeholder="tipo_factura">
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
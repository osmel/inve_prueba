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
		<div class="col-sm-8 col-md-8"><h4>Nuevo proveedor</h4></div>
	</div>
	<br>
	<div class="container row">

		<div class="panel panel-primary">
			<div class="panel-heading">Datos del proveedor</div>
			<div class="panel-body">

				<div class="col-sm-6 col-md-6">
					<div class="form-group">
						<label for="rfc_fiscal" class="col-sm-12 col-md-12">Proveedor</label>
						<?php echo form_open( '', array( 'class' => 'form-horizontal', 'role' => 'search', 'id' => 'form_search_unidades', 'name' => 'form_search_proveedores', 'autocomplete' => 'off', 'method' => 'GET' ) ); ?>
								<div class="col-sm-12 col-md-12">
									<input type="text" id="proveedor" name="editar_proveedor" class="buscar_codigo form-control typeahead tt-query" autocomplete="off" spellcheck="false" placeholder="proveedor...">

								</div>
						<?php echo form_close(); ?>

						<label class="col-sm-12 col-md-12" id="desc_proveedor"></label>
					</div>
				</div>

				<div class="col-sm-6 col-md-6">

					<div class="form-group">
						<label for="producto" class="col-sm-12 col-md-12">Producto</label>
						<?php echo form_open( '', array( 'class' => 'form-horizontal', 'role' => 'search', 'id' => 'form_search_unidades', 'name' => 'form_search_proveedores', 'autocomplete' => 'off', 'method' => 'GET' ) ); ?>
								<div class="col-sm-12 col-md-12">
									<input type="text" id="producto" name="editar_producto" class="buscar_codigo form-control typeahead tt-query" autocomplete="off" spellcheck="false" placeholder="proveedor...">

								</div>
						<?php echo form_close(); ?>

						<label class="col-sm-12 col-md-12" id="desc_producto"></label>
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
				<input style="padding:8px;" type="submit" class="btn btn-success btn-block" value="Guardar"/>
			</div>
		</div>
	</div>
</div>
<?php echo form_close(); ?>
<?php $this->load->view('footer'); ?>
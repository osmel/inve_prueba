<meta charset="UTF-8">
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php $this->load->view('header'); ?>
<?php 
 	if (!isset($retorno)) {
      	$retorno ="productos";
    }
 $attr = array('class' => 'form-horizontal', 'id'=>'form_catalogos','name'=>$retorno,'method'=>'POST','autocomplete'=>'off','role'=>'form');
 echo form_open('validar_nuevo_producto', $attr);
?>		
<div class="container">
		<br>	
	<div class="row">
		<div class="col-sm-8 col-md-8"><h4>Nuevo Producto</h4></div>
	</div>
	<br>
	<div class="container row">
		<div class="panel panel-primary">
			<div class="panel-heading">Datos del producto</div>
			<div class="panel-body">
				<div class="col-sm-6 col-md-6">

					<div class="form-group">
						<label for="producto" class="col-sm-12 col-md-12">Producto</label>
						<div class="col-sm-12 col-md-12">
							<input type="text" class="form-control" id="producto" name="producto" placeholder="Producto">
						</div>
					</div>

					<div class="form-group">
						<label for="descripcion" class="col-sm-12 col-md-12">Descripción</label>
						<div class="col-sm-12 col-md-12">
							<input type="text" class="form-control" id="descripcion" name="descripcion" placeholder="Descripción">
						</div>
					</div>

					<div class="form-group">
						<label for="color" class="col-sm-12 col-md-12">Color</label>
						<div class="col-sm-12 col-md-12">
							<input type="text" class="form-control" id="color" name="color" placeholder="Color">
						</div>
					</div>

					<div class="form-group">
						<label for="ancho" class="col-sm-12 col-md-12">Ancho</label>
						<div class="col-sm-12 col-md-12">
							<input type="text" class="form-control" id="ancho" name="ancho" placeholder="Ancho">
						</div>
					</div>

					<div class="form-group">
						<label for="composicion" class="col-sm-12 col-md-12">Composición</label>
						<div class="col-sm-12 col-md-12">
							<input type="text" class="form-control" id="composicion" name="composicion" placeholder="Composición">
						</div>
					</div>

					<div class="form-group">
						<label for="cant_metro" class="col-sm-12 col-md-12">Cantidad en metro</label>
						<div class="col-sm-12 col-md-12">
							<input type="text" class="form-control" id="cant_metro" name="cant_metro" placeholder="Cantidad en metro">
						</div>
					</div>

					<div class="form-group">
						<label for="cant_peso" class="col-sm-12 col-md-12">Cantidad en peso</label>
						<div class="col-sm-12 col-md-12">
							<input type="text" class="form-control" id="cant_peso" name="cant_peso" placeholder="Cantidad en peso">
						</div>
					</div>					



				</div>


				<div class="col-sm-6 col-md-6">


					<div class="form-group">
						<label for="precio_metro" class="col-sm-12 col-md-12">Precio en metro</label>
						<div class="col-sm-12 col-md-12">
							<input type="text" class="form-control" id="precio_metro" name="precio_metro" placeholder="Precio en metro">
						</div>
					</div>
					<div class="form-group">
						<label for="precio_peso" class="col-sm-12 col-md-12">Precio en peso</label>
						<div class="col-sm-12 col-md-12">
							<input type="text" class="form-control" id="precio_peso" name="precio_peso" placeholder="Precio en peso">
						</div>
					</div>

					


					<div class="form-group">
						<label for="ubicacion" class="col-sm-12 col-md-12">Ubicación dentro del almacén</label>
						<div class="col-sm-12 col-md-12">
							<input type="text" class="form-control" id="ubicacion" name="ubicacion" placeholder="Ubicación">
						</div>
					</div>

					<div class="form-group">
						<label for="observacion" class="col-sm-12 col-md-12">Observaciones</label>
						<div class="col-sm-12 col-md-12">
							<textarea class="form-control" name="observacion" id="observacion" rows="8" placeholder="Observaciones"></textarea>
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
	</div>
</div>
<?php echo form_close(); ?>
<?php $this->load->view('footer'); ?>
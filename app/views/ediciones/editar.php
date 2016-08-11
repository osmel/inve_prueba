<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php $this->load->view( 'header' ); ?>
	
<div class="container margenes">
		<div class="panel panel-primary">
			<div class="panel-heading">Editar</div>
			<div class="panel-body">
				<div class="container row">					
					<div class="col-md-12">	
						
							<div class="col-sm-4 col-md-4">
								<div class="form-group">									
									<div class="col-sm-12 col-md-12">
										<input type="text" class="form-control" id="descripcion" name="descripcion" placeholder="Código de producto">
									</div>
								</div>
							</div>
							<div class="col-sm-2 col-md-2">
								<label for="descripcion" class="col-sm-12 col-md-12"></label>
								<a href="#" type="button" class="btn btn-success btn-block">Buscar</a>
							</div>
							<div class="col-sm-2 col-md-2">
								<label for="descripcion" class="col-sm-12 col-md-12"></label>
								<a href="#" type="button" class="btn btn-success btn-block">Escanear</a>
							</div>
							<!-- <div class="col-sm-2 col-md-2">
							</div>
							<div class="col-sm-4 col-md-4">
								<div class="form-group">
									<label for="descripcion">Color</label>								
										<select class="form-control">
											<option>Todos</option>
											<option>Azul marino</option>
											<option>rojo</option>				
										</select>								
								</div>
							</div>		 -->	
						
					</div>
				</div>
				<hr>
				<div class="container row">
					<div class="col-sm-12 col-md-12 " style="margin-bottom:10px">
						<div class="col-sm-4 col-md-4">
							<div class="form-group">
								<label for="descripcion" class="col-sm-12 col-md-12">Producto</label>
								<div class="col-sm-12 col-md-12">
									<input type="text" class="form-control" id="descripcion" name="descripcion" placeholder="Proveedor">
								</div>
							</div>
						</div>
						<div class="col-sm-4 col-md-4">
							<div class="form-group">
								<label for="descripcion">Color</label>								
									<select class="form-control">
										<option>Todos</option>
										<option>Azul marino</option>
										<option>rojo</option>				
									</select>								
							</div>
						</div>						
						<div class="col-sm-2 col-md-2">
							<div class="form-group">
								<label for="descripcion">Composición</label>													
									<select class="form-control">
										<option>Todos</option>
										<option>Entradas</option>
										<option>Salidas</option>				
									</select>								
							</div>
						</div>
						<div class="col-sm-2 col-md-2">
							<div class="form-group">
								<label for="descripcion">Calidad</label>													
									<select class="form-control">
										<option>Uno</option>
										<option>Dos</option>
										<option>Tres</option>				
									</select>								
							</div>
						</div>
					</div>
					<div class="col-sm-12 col-md-12">
						<div class="col-sm-2 col-md-2">
							<div class="form-group">
								<label for="descripcion" class="col-sm-12 col-md-12">Cantidad</label>
								<div class="col-sm-12 col-md-12">
									<input type="text" class="form-control" id="descripcion" name="descripcion" placeholder="Proveedor">
								</div>
							</div>
						</div>
						<div class="col-sm-2 col-md-2">
							<div class="form-group">
								<label for="descripcion">selector</label>													
									<select class="form-control">
										<option>mts</option>
										<option>Kg</option>												
									</select>								
							</div>
						</div>
						<div class="col-sm-8 col-md-8">
							<div class="form-group">
								<label for="descripcion" class="col-sm-12 col-md-12">Comentarios</label>
								<div class="col-sm-12 col-md-12">
									<textarea class="form-control" name="comentario" id="comentario" rows="5" placeholder="Comentarios"></textarea>
								</div>
							</div>
						</div>	
					
						
				</div>	
			</div>
			<hr>
<div class="col-md-12">	
<div class="row">
	<div class="col-sm-4 col-md-4">
		<a href="#" type="button" class="btn btn-success btn-block">Imprimir etiqueta</a>
	</div>
	<div class="col-sm-4 col-md-4"></div>
	<div class="col-sm-4 col-md-4">
		<a href="#" type="button" class="btn btn-danger btn-block">Regresar</a>
	</div>	
</div>
</div>				
		</div>
	</div>
</div>
<?php $this->load->view( 'footer' ); ?>
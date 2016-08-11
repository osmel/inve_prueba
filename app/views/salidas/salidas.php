<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php $this->load->view( 'header' ); ?>
<div class="container margenes">
		<div class="panel panel-primary">
			<div class="panel-heading">Registro de Salidas</div>
			<div class="panel-body">
				<div class="container">
					<div class="row" style="margin-bottom:10px">
						<div class="col-sm-4 col-md-4 row">
						</div>
						<div class="col-sm-4 col-md-4">
							<div class="form-group">
								<label for="descripcion" class="col-sm-12 col-md-12">Cliente</label>
								<div class="col-sm-12 col-md-12">
									<input type="text" class="form-control" id="descripcion" name="descripcion" placeholder="Proveedor">
								</div>
							</div>
						</div>						
						<div class="col-sm-2 col-md-2">
							<div class="form-group">
								<label for="descripcion" class="col-sm-12 col-md-12">Cargador</label>
								<div class="col-sm-12 col-md-12">
									<input type="text" class="form-control" id="descripcion" name="descripcion" placeholder="Factura">
								</div>
							</div>
						</div>
						<div class="col-sm-2 col-md-2">
							<div class="form-group">
								<label for="descripcion" class="col-sm-12 col-md-12">Fecha</label>
								<div class="col-sm-12 col-md-12">
									<input type="text" class="form-control" id="descripcion" name="descripcion" placeholder="Fecha">
								</div>
							</div>
						</div>
					</div>
					<div class="col-sm-12 col-md-12 row">
						<div class="col-sm-4 col-md-4 row">
							<div class="form-group">
								<label for="descripcion" class="col-sm-12 col-md-12">Buscador</label>
								<div class="col-sm-12 col-md-12">
									<input type="text" class="form-control" id="descripcion" name="descripcion" placeholder="Proveedor">
								</div>
							</div>
						</div>
						<div class="col-sm-2 col-md-4">
						</div>
						<div class="col-sm-2 col-md-2">
							<div class="form-group">
								<label for="descripcion" class="col-sm-12 col-md-12">Factura/Remisión</label>
								<div class="col-sm-12 col-md-12">
									<input type="text" class="form-control" id="descripcion" name="descripcion" placeholder="No. Movimiento">
								</div>
							</div>
						</div>
						<div class="col-sm-2 col-md-2">
							<div class="form-group">
								<label for="descripcion" class="col-sm-12 col-md-12">No. Movimiento</label>
								<div class="col-sm-12 col-md-12">
									<input type="text" class="form-control" id="descripcion" name="descripcion" placeholder="No. Movimiento">
								</div>
							</div>
						</div>
					
						
				</div>	
			</div>
			<br><br>
			<div class="container row">					
			<div class="col-md-12">				
				<div class="table-responsive">
				<table class="table table-striped table-bordered table-responsive tabla_ordenadas" >
					<thead>	
						<tr>
							<th class="text-center cursora" width="15%">Código<i class="glyphicon glyphicon-sort"></i></th>
							<th class="text-center cursora" width="20%">Descripción <i class="glyphicon glyphicon-sort"></i></th>
							<th class="text-center cursora" width="10%">Color<i class="glyphicon glyphicon-sort"></i></th>
							<th class="text-center cursora" width="5%">Pieza <i class="glyphicon glyphicon-sort"></i></th>
							<th class="text-center cursora" width="5%">Metro<i class="glyphicon glyphicon-sort"></i></th>
							<th class="text-center cursora" width="5%">Ancho <i class="glyphicon glyphicon-sort"></i></th>
							<th class="text-center cursora" width="15%">Proveedor<i class="glyphicon glyphicon-sort"></i></th>
							<th class="text-center cursora" width="5%">Lote/#<i class="glyphicon glyphicon-sort"></i></th>
							<th class="text-center cursora" width="15%">Operación</i></th>


						</tr>
					</thead>		
						<td class="text-center">012556546546 1</td>	
						<td class="text-center">descripción</td>	
						<td class="text-center">color</td>	
						<td class="text-center">1</td>	
						<td class="text-center">25.2</td>	
						<td class="text-center">170</td>	
						<td class="text-center">Estrategas Digitales</td>	
						<td class="text-center">002-1</td>	
						<td class="text-center"><input style="padding:1px;" type="submit" class="btn btn-success btn-block" value="Agregar"/></td>	
					</tr>
					<tr>
						<td class="text-center">012556546546 1</td>	
						<td class="text-center">descripción</td>	
						<td class="text-center">color</td>	
						<td class="text-center">1</td>	
						<td class="text-center">25.2</td>	
						<td class="text-center">170</td>	
						<td class="text-center">Estrategas Digitales</td>	
						<td class="text-center">002-2</td>	
						<td class="text-center"><input style="padding:1px;" type="submit" class="btn btn-success btn-block" value="Agregar"/></td>	
					</tr>
					<tr>
						<td class="text-center">012556546546 1</td>	
						<td class="text-center">descripción</td>	
						<td class="text-center">color</td>	
						<td class="text-center">1</td>	
						<td class="text-center">25.2</td>	
						<td class="text-center">170</td>	
						<td class="text-center">Estrategas Digitales</td>	
						<td class="text-center">002-1</td>	
						<td class="text-center"><input style="padding:1px;" type="submit" class="btn btn-success btn-block" value="Agregar"/></td>	
					</tr>
					<tr>
						<td class="text-center">012556546546 1</td>	
						<td class="text-center">descripción</td>	
						<td class="text-center">color</td>	
						<td class="text-center">1</td>	
						<td class="text-center">25.2</td>	
						<td class="text-center">170</td>	
						<td class="text-center">Estrategas Digitales</td>	
						<td class="text-center">002-2</td>	
						<td class="text-center"><input style="padding:1px;" type="submit" class="btn btn-success btn-block" value="Agregar"/></td>	
					</tr>
					<tr>
						<td class="text-center">012556546546 1</td>	
						<td class="text-center">descripción</td>	
						<td class="text-center">color</td>	
						<td class="text-center">1</td>	
						<td class="text-center">25.2</td>	
						<td class="text-center">170</td>	
						<td class="text-center">Estrategas Digitales</td>	
						<td class="text-center">002-1</td>	
						<td class="text-center"><input style="padding:1px;" type="submit" class="btn btn-success btn-block" value="Agregar"/></td>	
					</tr>
									

				</table>
			</div>
		</div>
		<div class="col-sm-12 col-md-12">
			<input type="submit" class="btn btn-success btn-block" value="Escanear producto"/>
		</div>
			<br><br>

	<div class="container row">					
		<div class="col-md-12">		
			<br>
			<h4>Productos por salir</h4>	
			<br>	
				<div class="table-responsive">
				<table class="table table-striped table-bordered table-responsive tabla_ordenadas" >
					<thead>	
						<tr>
							<th class="text-center cursora" width="15%">Código<i class="glyphicon glyphicon-sort"></i></th>
							<th class="text-center cursora" width="20%">Descripción <i class="glyphicon glyphicon-sort"></i></th>
							<th class="text-center cursora" width="10%">Color<i class="glyphicon glyphicon-sort"></i></th>
							<th class="text-center cursora" width="5%">Pieza <i class="glyphicon glyphicon-sort"></i></th>
							<th class="text-center cursora" width="5%">Metro<i class="glyphicon glyphicon-sort"></i></th>
							<th class="text-center cursora" width="5%">Ancho <i class="glyphicon glyphicon-sort"></i></th>
							<th class="text-center cursora" width="15%">Proveedor<i class="glyphicon glyphicon-sort"></i></th>
							<th class="text-center cursora" width="5%">Lote/#<i class="glyphicon glyphicon-sort"></i></th>
							<th class="text-center cursora" width="15%">Operación</i></th>


						</tr>
					</thead>		
						<td class="text-center">012556546546 1</td>	
						<td class="text-center">descripción</td>	
						<td class="text-center">color</td>	
						<td class="text-center">1</td>	
						<td class="text-center">25.2</td>	
						<td class="text-center">170</td>	
						<td class="text-center">Estrategas Digitales</td>	
						<td class="text-center">002-1</td>	
						<td class="text-center"><input style="padding:1px;" type="submit" class="btn btn-danger btn-block" value="Quitar"/></td>	
					</tr>
					<tr>
						<td class="text-center">012556546546 1</td>	
						<td class="text-center">descripción</td>	
						<td class="text-center">color</td>	
						<td class="text-center">1</td>	
						<td class="text-center">25.2</td>	
						<td class="text-center">170</td>	
						<td class="text-center">Estrategas Digitales</td>	
						<td class="text-center">002-2</td>	
						<td class="text-center"><input style="padding:1px;" type="submit" class="btn btn-danger btn-block" value="Quitar"/></td>	
					</tr>
					<tr>
						<td class="text-center">012556546546 1</td>	
						<td class="text-center">descripción</td>	
						<td class="text-center">color</td>	
						<td class="text-center">1</td>	
						<td class="text-center">25.2</td>	
						<td class="text-center">170</td>	
						<td class="text-center">Estrategas Digitales</td>	
						<td class="text-center">002-1</td>	
						<td class="text-center"><input style="padding:1px;" type="submit" class="btn btn-danger btn-block" value="Quitar"/></td>	
					</tr>
					<tr>
						<td class="text-center">012556546546 1</td>	
						<td class="text-center">descripción</td>	
						<td class="text-center">color</td>	
						<td class="text-center">1</td>	
						<td class="text-center">25.2</td>	
						<td class="text-center">170</td>	
						<td class="text-center">Estrategas Digitales</td>	
						<td class="text-center">002-2</td>	
						<td class="text-center"><input style="padding:1px;" type="submit" class="btn btn-danger btn-block" value="Quitar"/></td>	
					</tr>
					<tr>
						<td class="text-center">012556546546 1</td>	
						<td class="text-center">descripción</td>	
						<td class="text-center">color</td>	
						<td class="text-center">1</td>	
						<td class="text-center">25.2</td>	
						<td class="text-center">170</td>	
						<td class="text-center">Estrategas Digitales</td>	
						<td class="text-center">002-2</td>	
						<td class="text-center"><input style="padding:1px;" type="submit" class="btn btn-danger btn-block" value="Quitar"/></td>	
					</tr>
									

				</table>
			</div>
		</div>
	</div>
</div>
<hr>
<div class="col-md-12">	
<div class="row">
	<div class="col-sm-4 col-md-4"></div>
	<div class="col-sm-4 col-md-4">
		<a href="#" type="button" class="btn btn-danger btn-block">Regresar</a>
	</div>
	<div class="col-sm-4 col-md-4">
		<input style="padding:7px;" type="submit" class="btn btn-success btn-block" value="Procesar Salida"/>
	</div>
</div>
</div>
</div>
</div>
<?php $this->load->view( 'footer' ); ?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php $this->load->view( 'header' ); ?>
<div class="container margenes">
		<div class="panel panel-primary">
			<div class="panel-heading">Detalles de pedido</div>
			<div class="panel-body">
				
		<div class="container row">
					<div class="col-sm-12 col-md-12 control" style="margin-bottom:10px">						
						<div class="col-sm-2 col-md-2">
							<div class="form-group">
								<label for="descripcion" class="col-sm-12 col-md-12">Número de pedido</label>
								<div class="col-sm-12 col-md-12">
									<input type="text" disabled class="form-control" id="descripcion" name="descripcion" placeholder="34534534554">
								</div>
							</div>
						</div>						
						<div class="col-sm-2 col-md-2">
							<div class="form-group">
								<label for="descripcion" class="col-sm-12 col-md-12">Fecha</label>
								<div class="col-sm-12 col-md-12">
									<input type="text" disabled class="form-control" id="descripcion" name="descripcion" placeholder="10/10/15">
								</div>
							</div>
						</div>
						<div class="col-sm-5 col-md-5">
							<div class="form-group">
								<label for="descripcion" class="col-sm-12 col-md-12">Cliente</label>
								<div class="col-sm-12 col-md-12">
									<input type="text" disabled class="form-control" id="descripcion" name="descripcion" placeholder="Iniciativa Textil">
								</div>
							</div>
						</div>
					
						<div class="col-sm-2 col-md-2">
							<div class="form-group">
								<label for="descripcion" class="col-sm-12 col-md-12">Hora</label>
								<div class="col-sm-12 col-md-12">
									<input type="text" disabled class="form-control" id="descripcion" name="descripcion" placeholder="9:05am">
								</div>
							</div>
						</div>						
				</div>		
	</div>
	<hr>
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
					</tr>
									

				</table>
			</div>
		<div class="col-md-12">	
			<div class="row">
				<div class="col-sm-4 col-md-4">
					<a href="#" type="button" class="btn btn-danger btn-block">Cancelar pedido</a>
				</div>	
				<div class="col-sm-4 col-md-4"></div>
				<div class="col-sm-4 col-md-4">
					<a href="#" type="button" class="btn btn-success btn-block">Imprimir</a>
				</div>				
			</div>	
		</div>
	</div>
</div>
<hr>
<div class="col-md-12">	
<div class="row">
	<div class="col-sm-3 col-md-3">
			
			<div class="col-sm-12 col-md-12">
				<input type="text" class="form-control" id="descripcion" name="descripcion" placeholder="Número de movimiento de salida">
			</div>
		
	</div>
	<div class="col-sm-3 col-md-3">
		<a href="#" type="button" class="btn btn-success btn-block">Pedido completado</a>
	</div>
	<div class="col-sm-3 col-md-3">
	</div>
	<div class="col-sm-3 col-md-3">
		<a href="#" type="button" class="btn btn-danger btn-block">Regresar</a>
	</div>
	
</div>
</div>
</div>
</div>
<?php $this->load->view( 'footer' ); ?>
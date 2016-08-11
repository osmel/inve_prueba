<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php $this->load->view( 'header' ); ?>
<div class="container margenes">
		<div class="panel panel-primary">
			<div class="panel-heading">Reportes</div>
			<div class="panel-body">
				<div class="container row">
					<div class="col-sm-12 col-md-12" style="margin-bottom:10px">						
						<div class="col-sm-2 col-md-2">
							<input type="submit" class="btn btn-success btn-block" value="Entradas"/>
						</div>						
						<div class="col-sm-2 col-md-2">
							<input type="submit" class="btn btn-success btn-block" value="Salidas"/>
						</div>
						<div class="col-sm-2 col-md-2">
							<input type="submit" class="btn btn-success btn-block" value="Devoluciones"/>
						</div>

						
						<div class="col-sm-2 col-md-2">
								<label for="descripcion" class="col-sm-12 col-md-12"></label>
									<a href="<?php echo base_url(); ?>listado_notas"  
										type="button" class="btn btn-info btn-block">Consultar Entradas
									</a>
						</div>

						<div class="col-sm-2 col-md-2">
								<label for="descripcion" class="col-sm-12 col-md-12"></label>
									<a href="<?php echo base_url(); ?>listado_salidas"  
										type="button" class="btn btn-info btn-block">Consultar Salidas
									</a>
						</div>

				
								
						<div class="col-sm-2 col-md-2">
							<input type="submit" class="btn btn-success btn-block" value="Exportar Excel"/>
						</div>
				</div>	
			</div>
			<br>
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
							<th class="text-center cursora" width="5%">Fecha Ingreso<i class="glyphicon glyphicon-sort"></i></th>
							


						</tr>
					</thead>		
						<td class="text-center">012556546546 1</td>	
						<td class="text-center">descripción</td>	
						<td class="text-center">color</td>	
						<td class="text-center">1</td>	
						<td class="text-center">25.2</td>	
						<td class="text-center">170</td>	
						<td class="text-center">Estrategas Digitales</td>	
						<td class="text-center">10/10/12</td>	
					</tr>
					<tr>
						<td class="text-center">012556546546 1</td>	
						<td class="text-center">descripción</td>	
						<td class="text-center">color</td>	
						<td class="text-center">1</td>	
						<td class="text-center">25.2</td>	
						<td class="text-center">170</td>	
						<td class="text-center">Estrategas Digitales</td>	
						<td class="text-center">10/06/12</td>
					</tr>
					<tr>
						<td class="text-center">012556546546 1</td>	
						<td class="text-center">descripción</td>	
						<td class="text-center">color</td>	
						<td class="text-center">1</td>	
						<td class="text-center">25.2</td>	
						<td class="text-center">170</td>	
						<td class="text-center">Estrategas Digitales</td>	
						<td class="text-center">10/09/12</td>	
						
					</tr>
					<tr>
						<td class="text-center">012556546546 1</td>	
						<td class="text-center">descripción</td>	
						<td class="text-center">color</td>	
						<td class="text-center">1</td>	
						<td class="text-center">25.2</td>	
						<td class="text-center">170</td>	
						<td class="text-center">Estrategas Digitales</td>	
						<td class="text-center">10/10/12</td>							
					</tr>
					<tr>
						<td class="text-center">012556546546 1</td>	
						<td class="text-center">descripción</td>	
						<td class="text-center">color</td>	
						<td class="text-center">1</td>	
						<td class="text-center">25.2</td>	
						<td class="text-center">170</td>	
						<td class="text-center">Estrategas Digitales</td>	
						<td class="text-center">10/10/12</td>							
					</tr>
									

				</table>
			</div>
		</div>		
			<br>
			<hr>
			<br>
	<div class="container row">	
		<div class="col-md-12">		
			<div class="col-md-6">								
			</div>	
			<div class="col-md-2">	
				<span>Total de piezas: 6</span>			
			</div>	
			<div class="col-md-2">	
				<span>Total de kgs: 6</span>				
			</div>	
			<div class="col-md-2">	
				<span>Total de mts: 450.5</span>			
			</div>			
		</div>		
		<div class="col-md-12">	
			<hr>
		</div>			
		<div class="col-md-12">		
			<br>
			<h4>Filtros</h4>	
			<br>

			<div class="col-sm-12 col-md-2">
					<div class="form-group">
						<label for="descripcion" class="col-sm-12 col-md-12">Factura/remisión</label>
						<div class="col-sm-12 col-md-12">
							<input type="text">
						</div>
					</div>
			</div>	

			<div class="col-sm-12 col-sm-12 col-md-6">		
				<label class="radio-inline">
				  <input type="radio" name="inlineRadioOptions" id="inlineRadio1" value="option1"> Factura/remisión
				</label>
				<label class="radio-inline">
				  <input type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2"> Código de producto
				</label>		
				<label class="radio-inline">
				  <input type="radio" name="inlineRadioOptions" id="inlineRadio3" value="option3"> Referencia de producto
				</label>			
			</div>

			<div class="col-sm-12 col-sm-12 col-md-2">
				<div class="form-group">
					<label for="descripcion" class="col-sm-12 col-md-12">Desde:</label>
					
						<input class="datepicker" data-date-format="mm/dd/yyyy">
					
				</div>
			</div>

			<div class="col-sm-12 col-sm-6 col-md-2">
				<div class="form-group">
					<label for="descripcion" class="col-sm-12 col-md-12">Hasta:</label>
					
						<input class="datepicker" data-date-format="mm/dd/yyyy">
					
				</div>
			</div>

			<div class="col-xs-12 col-sm-6 col-md-2">
				<div class="form-group">
					<label for="descripcion">Estatus</label>
					
						<select class="form-control">
							<option>Todos</option>
							<option>Normal</option>
							<option>Devolución</option>
							<option>Pieza con defecto</option>
						</select>
					
				</div>
			</div>

			<div class="col-xs-12 col-sm-6 col-md-2">
				<div class="form-group">
					<label for="descripcion">Proveedor</label>
					
						<select class="form-control">
							<option>Todos</option>
							<option>Impacto Textil</option>
							<option>Ditecsa</option>				
						</select>
					
				</div>
			</div>

			<div class="col-xs-12 col-sm-6 col-md-2">
				<div class="form-group">
					<label for="descripcion">Producto</label>
					
						<select class="form-control">
							<option>Todos</option>
							<option>Acolchado liso pegado</option>
							<option>Mezclilla</option>				
						</select>
					
				</div>
			</div>

			<div class="col-xs-12 col-sm-6 col-md-2">
				<div class="form-group">
					<label for="descripcion">Color</label>
					
						<select class="form-control">
							<option>Todos</option>
							<option>Azul marino</option>
							<option>rojo</option>				
						</select>
					
				</div>
			</div>
			
			<div class="col-xs-12 col-sm-6 col-md-2">
				<div class="form-group">
					<label for="descripcion">Operación</label>
										
						<select class="form-control">
							<option>Todos</option>
							<option>Entradas</option>
							<option>Salidas</option>				
						</select>
					
				</div>
			</div>

			<div class="col-xs-12 col-sm-6 col-md-2">
				<div class="form-group">
					<div class="checkbox">
				      <label>
				        <input type="checkbox"> TOP 10
				      </label>
				    </div>
				</div>	
			</div>	

		</div>
		<div class="col-md-12">
			<div class="col-sm-10 col-md-10">							
			</div>

			<div class="col-sm-2 col-md-2">
				<input type="submit" class="btn btn-success btn-block" value="Exportar Excel"/>
			</div>
		</div>

	</div>
</div>
<hr>
<div class="col-md-12">	
<div class="row">
	<div class="col-sm-8 col-md-8"></div>
	<div class="col-sm-4 col-md-4">
		<a href="#" type="button" class="btn btn-danger btn-block">Regresar</a>
	</div>
	
</div>
</div>
</div>
</div>
<?php $this->load->view( 'footer' ); ?>
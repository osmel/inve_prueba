<meta charset="UTF-8">
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php $this->load->view('header'); ?>
<?php 
 	if (!isset($retorno)) {
      	$retorno ="usuarios";
    }


	  $coleccion_id_operaciones= json_decode($this->session->userdata('coleccion_id_operaciones')); 


	  if ( (count($coleccion_id_operaciones)==0) || (!($coleccion_id_operaciones)) )  {
	  			$coleccion_id_operaciones = array();
	  } 	




 $attr = array('class' => 'form-horizontal', 'id'=>'form_usuarios','name'=>$retorno,'method'=>'POST','autocomplete'=>'off','role'=>'form');
 echo form_open('validar_nuevo_usuario', $attr);
?>		
<div class="container">	
     <br/>
	<div class="row">
		<div class="col-sm-8 col-md-8"><h4>Registro de nuevo Usuario</h4></div>
	</div>
	<br>
	<div class="container row">
		<div class="panel panel-primary">
			<div class="panel-heading">Datos del Usuario</div>
			
			<div class="panel-body">
				<div class="col-sm-6 col-md-6">
					<div class="form-group">
						<label for="nombre" class="col-sm-3 col-md-2 control-label">Nombre</label>
						<div class="col-sm-9 col-md-10">
							<input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre">
						</div>
					</div>
					<div class="form-group">
						<label for="apellidos" class="col-sm-3 col-md-2 control-label">Apellido(s)</label>
						<div class="col-sm-9 col-md-10">
							<input type="text" class="form-control" id="apellidos" name="apellidos" placeholder="Apellido (s)">
						</div>
					</div>
					<div class="form-group">
						<label for="email" class="col-sm-3 col-md-2 control-label">Email</label>
						<div class="col-sm-9 col-md-10">
							<input type="email" class="form-control" id="email" name="email" placeholder="Email">
						</div>
					</div>
					<div class="form-group">
						<label for="telefono" class="col-sm-3 col-md-2 control-label">Número Teléfono </label>
						<div class="col-sm-9 col-md-10">
							<input type="text" class="form-control" id="telefono" name="telefono" placeholder="Número Teléfono">
						</div>
					</div>
				</div>
				<div class="col-sm-6 col-md-6">
					<div class="form-group">
						<label for="pass_1" class="col-sm-3 col-md-2 control-label">Contraseña</label>
						<div class="col-sm-9 col-md-10">
							<input type="password" class="form-control" id="pass_1" name="pass_1" placeholder="Contraseña">
						</div>
					</div>
					<div class="form-group">
						<label for="pass_2" class="col-sm-3 col-md-2 control-label">Confirmar Contraseña</label>
						<div class="col-sm-9 col-md-10">
							<input type="password" class="form-control" id="pass_2" name="pass_2" placeholder="Confirmar Contraseña">
						</div>
					</div>

					<div class="form-group">
						<label for="id_perfil" class="col-sm-3 col-md-2 control-label">Rol de usuario</label>
						<div class="col-sm-9 col-md-10">
							<?php  if (( $this->session->userdata( 'id_perfil' ) == 2 ) || ( $this->session->userdata( 'id_perfil' ) == 4 )){ ?>											
								<fieldset disabled>
									

										<?php if ( $this->session->userdata( 'id_perfil' ) != 1 ){ ?>		
											<select disabled="disabled" name="id_perfil" id="id_perfil" class="form-control">
										<?php } else { ?>	
											<select name="id_perfil" id="id_perfil" class="form-control">
										<?php } ?>	


										<!--<option value="0">Selecciona una opción</option>-->
											<?php foreach ( $perfiles as $perfil ){ ?>
												<?php if ( $this->session->userdata( 'id_perfil' ) == $perfil->id_perfil ){ ?>
													<option value="<?php echo $perfil->id_perfil; ?>"><?php echo $perfil->perfil; ?></option>
												<?php } ?>	
											<?php } ?>
											<!--rol de usuario -->
									</select>
								</fieldset>		
						    <?php } elseif ( $this->session->userdata( 'id_perfil' ) == 1 ){ ?>											
									

										<?php if ( $this->session->userdata( 'id_perfil' ) != 1 ){ ?>		
											<select disabled="disabled" name="id_perfil" id="id_perfil" class="form-control">
										<?php } else { ?>	
											<select name="id_perfil" id="id_perfil" class="form-control">
										<?php } ?>	

										<!--<option value="0">Selecciona una opción</option>-->
											<?php foreach ( $perfiles as $perfil ){ ?>
													<option value="<?php echo $perfil->id_perfil; ?>"><?php echo $perfil->perfil; ?></option>
											<?php } ?>
											<!--rol de usuario -->
									</select>
						    <?php } ?>									    
						</div>
					</div>

					
					<!--Cliente Asociado -->
					<div class="form-group">
						<label for="id_cliente" class="col-sm-3 col-md-2 control-label">Sucursal</label>
						<div class="col-sm-9 col-md-10">
							<?php  if (( $this->session->userdata( 'id_perfil' ) == 2 ) || ( $this->session->userdata( 'id_perfil' ) == 4 )) { ?>											
								<fieldset disabled>
									

										<?php if ( $this->session->userdata( 'id_perfil' ) != 1 ){ ?>		
											<select disabled="disabled" name="id_cliente" id="id_cliente" class="form-control">
										<?php } else { ?>	
											<select name="id_cliente" id="id_cliente" class="form-control">
										<?php } ?>	


										<!--<option value="0">Selecciona una opción</option>-->
											<?php foreach ( $clientes as $cliente ){ ?>
												<?php if ( $this->session->userdata( 'id_cliente' ) == $cliente->id_cliente ){ ?>
													<option value="<?php echo $cliente->id_cliente; ?>"><?php echo $cliente->cliente; ?></option>
												<?php } ?>	
											<?php } ?>
											<!--rol de usuario -->
									</select>
								</fieldset>		
						    <?php } elseif ( $this->session->userdata( 'id_perfil' ) == 1 ){ ?>											
									


										<?php if ( $this->session->userdata( 'id_perfil' ) != 1 ){ ?>		
											<select disabled="disabled" name="id_cliente" id="id_cliente" class="form-control">
										<?php } else { ?>	
											<select name="id_cliente" id="id_cliente" class="form-control">
										<?php } ?>	

										<!--<option value="0">Selecciona una opción</option>-->
											<?php foreach ( $clientes as $cliente ){ ?>
													<option value="<?php echo $cliente->id_cliente; ?>"><?php echo $cliente->cliente; ?></option>
											<?php } ?>
											<!--rol de usuario -->
									</select>
						    <?php } ?>									    
						</div>
					</div>










				<!--almacen Asociado -->
					<div id="rol_almacen" style="display:block;" class="form-group">
						<label for="id_almacen" class="col-sm-3 col-md-2 control-label">Almacén</label>
						<div class="col-sm-9 col-md-10">

									<select name="id_almacen" id="id_almacen" class="form-control">
											<?php foreach ( $almacenes as $almacen ){ ?>
													<option value="<?php echo $almacen->id_almacen; ?>"><?php echo $almacen->almacen; ?></option>
											<?php } ?>
									</select>
						</div>
					</div>








				</div>
			

		



				<br/>
				
				<!--SOLO LOS USUARIOS ADMINISTRADORES TENDRAN PERMISO DE OPERACIONES -->	
				
					<div id="rol_perfil" style="display:block;" class="col-sm-12 col-md-12">		
					  

							<?php if ( $this->session->userdata( 'id_perfil' ) != 1 ){ ?>		
								<fieldset disabled>
							<?php } ?>	
										<h2>Permisos de operaciones</h2>
										
										<?php $grupo='';
											foreach ($operaciones as $operacion){ ?>

											<?php 

												if ($grupo!=$operacion->grupo) {
													echo '<hr> <b>'.$operacion->grupo.'</b><br/>'; 	
														

													$grupo=$operacion->grupo; 	
												}
											?>
											<div class="checkbox">
												<label for="coleccion_id_operaciones" class="ttip" title="<?php echo $operacion->tooltip; ?>">
																<?php if ( $this->session->userdata( 'id_perfil' ) != 1 ){ ?>		
																	<input type="checkbox" value="<?php echo $operacion->id; ?>" name="coleccion_id_operaciones[]" disabled><?php echo $operacion->operacion; ?> 
																<?php } else { ?>	
																	<input type="checkbox" value="<?php echo $operacion->id; ?>" name="coleccion_id_operaciones[]"><?php echo $operacion->operacion; ?> 
																<?php } ?>	
												
												</label>
											</div>
										<?php } ?>

							<?php if ( $this->session->userdata( 'id_perfil' ) != 1 ){ ?>		
								</fieldset>
							<?php } ?>			
					   					
					</div>
				

			</div>
		</div>
				
		<div class="row">	
			<div class="col-sm-4 col-md-4"></div>
			<div class="col-sm-4 col-md-4 marginbuttom">
				<a href="<?php echo base_url(); ?>usuarios" type="button" class="btn btn-danger btn-block">Cancelar</a>
			</div>
			<div class="col-sm-4 col-md-4">
				<input type="submit" class="btn btn-success btn-block" value="Guardar"/>
			</div>
		</div>
		<br/>

	</div>	
</div>
<?php echo form_close(); ?>
<?php $this->load->view('footer'); ?>
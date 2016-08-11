<meta charset="UTF-8">
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php $this->load->view('header'); ?>
<?php 

 	if (!isset($retorno)) {
      	$retorno ="proveedores";
    }

  $hidden = array('rfc_fiscal_ant'=>$rfc_fiscal_ant);
  $attr = array('class' => 'form-horizontal', 'id'=>'form_catalogos','name'=>$retorno,'method'=>'POST','autocomplete'=>'off','role'=>'form');
  echo form_open('validacion_edicion_proveedor', $attr,$hidden);
?>	
<div class="container">
		<br>
	<div class="row">
		<div class="col-sm-8 col-md-8"><h4>Edición de proveedor</h4></div>
	</div>
	<br>
	<div class="container row">


		<div class="panel panel-primary">
			<div class="panel-heading">Datos del proveedor</div>
			<div class="panel-body">

				<div class="col-sm-6 col-md-6">


					<div class="form-group">
						<label for="rfc_fiscal" class="col-sm-12 col-md-12">RFC fiscal</label>
						<div class="col-sm-12 col-md-12">
							<?php 
								$nomb_nom='';
								if (isset($proveedor->rfc_fiscal)) 
								 {	$nomb_nom = $proveedor->rfc_fiscal;}
							?>
							<input value="<?php echo  set_value('rfc_fiscal',$nomb_nom); ?>" type="text" class="form-control" id="rfc_fiscal" name="rfc_fiscal" placeholder="RFC fiscal">
						</div>
					</div>

					<div class="form-group">
						<label for="proveedor" class="col-sm-12 col-md-12">Proveedor</label>
						<div class="col-sm-12 col-md-12">
							<?php 
								$nomb_nom='';
								if (isset($proveedor->proveedor)) 
								 {	$nomb_nom = $proveedor->proveedor;}
							?>
							<input value="<?php echo  set_value('proveedor',$nomb_nom); ?>" type="text" class="form-control" id="proveedor" name="proveedor" placeholder="Proveedor">
						</div>
					</div>
					

					<div class="form-group">
						<label for="telefono_fiscal" class="col-sm-12 col-md-12">Teléfono empresa</label>
						<div class="col-sm-12 col-md-12">
							<?php 
								$nomb_nom='';
								if (isset($proveedor->telefono_fiscal)) 
								 {	$nomb_nom = $proveedor->telefono_fiscal;}
							?>
							<input value="<?php echo  set_value('telefono_fiscal',$nomb_nom); ?>" type="text" class="form-control" id="telefono_fiscal" name="telefono_fiscal" placeholder="teléfono">
						</div>
					</div>

					<div class="form-group">
						<label for="email_fiscal" class="col-sm-12 col-md-12">Correo empresa</label>
						<div class="col-sm-12 col-md-12">
							<?php 
								$nomb_nom='';
								if (isset($proveedor->email_fiscal)) 
								 {	$nomb_nom = $proveedor->email_fiscal;}
							?>
							<input value="<?php echo  set_value('email_fiscal',$nomb_nom); ?>" type="text" class="form-control" id="email_fiscal" name="email_fiscal" placeholder="Proveedor">
						</div>
					</div>

				</div>

				<div class="col-sm-6 col-md-6">
					<div class="form-group">
						<label for="razon_social" class="col-sm-12 col-md-12">Razón Social</label>
						<div class="col-sm-12 col-md-12">
							<?php 
								$nomb_nom='';
								if (isset($proveedor->razon_social)) 
								 {	$nomb_nom = $proveedor->razon_social;}
							?>	
							<textarea class="form-control" name="razon_social" id="razon_social" rows="5" placeholder="Razón Social"><?php echo  set_value('razon_social',$nomb_nom); ?></textarea>
						</div>
					</div>	

					<div class="form-group">
						<label for="domicilio_fiscal" class="col-sm-12 col-md-12">Domicilio Fiscal</label>
						<div class="col-sm-12 col-md-12">
							<?php 
								$nomb_nom='';
								if (isset($proveedor->domicilio_fiscal)) 
								 {	$nomb_nom = $proveedor->domicilio_fiscal;}
							?>	
							<textarea class="form-control" name="domicilio_fiscal" id="domicilio_fiscal" rows="5" placeholder="Domicilio Fiscal"><?php echo  set_value('domicilio_fiscal',$nomb_nom); ?></textarea>
						</div>
					</div>	

				</div>


			</div>
		</div>


		<div class="panel panel-primary">
			<div class="panel-heading">Datos Bancarios y Giro de la empresa</div>
			<div class="panel-body">

				<div class="col-sm-6 col-md-6">

					<div class="form-group">
						<label for="cta_bancaria" class="col-sm-12 col-md-12">Núm. de cta. bancaria</label>
						<div class="col-sm-12 col-md-12">
							<?php 
								$nomb_nom='';
								if (isset($proveedor->cta_bancaria)) 
								 {	$nomb_nom = $proveedor->cta_bancaria;}
							?>
							<input value="<?php echo  set_value('cta_bancaria',$nomb_nom); ?>" type="text" class="form-control" id="cta_bancaria" name="cta_bancaria" placeholder="Cuenta bancaria">
						</div>
					</div>

					<div class="form-group">
						<label for="clave_cta" class="col-sm-12 col-md-12">Clave estandarizada(18 Digítos)</label>
						<div class="col-sm-12 col-md-12">
							<?php 
								$nomb_nom='';
								if (isset($proveedor->clave_cta)) 
								 {	$nomb_nom = $proveedor->clave_cta;}
							?>
							<input value="<?php echo  set_value('clave_cta',$nomb_nom); ?>" type="text" class="form-control" id="clave_cta" name="clave_cta" placeholder="clave de cuenta">
						</div>
					</div>

					<div class="form-group">
						<label for="sucursal" class="col-sm-12 col-md-12">BANCO Y NÚM. DE SUCURSAL</label>
						<div class="col-sm-12 col-md-12">
							<?php 
								$nomb_nom='';
								if (isset($proveedor->sucursal)) 
								 {	$nomb_nom = $proveedor->sucursal;}
							?>							
							<input value="<?php echo  set_value('sucursal',$nomb_nom); ?>"  type="text" class="form-control" id="sucursal" name="sucursal" placeholder="sucursal">
						</div>
					</div>

				</div>

				<div class="col-sm-6 col-md-6">

					<div class="form-group">
						<label for="id_actividad" class="col-sm-12 col-md-12">Actividad comercial<span class="obligatorio"> *</span></label>
						<div class="col-sm-12 col-md-12">

									<select name="id_actividad"  id="id_actividad" class="form-control">
											<?php foreach ( $actividades as $actividad ){ ?>
													<?php 
													   if  ($actividad->id==$proveedor->id_actividad)
														 {$seleccionado='selected';} else {$seleccionado='';}
													?>
													<option value="<?php echo $actividad->id; ?>" <?php echo $seleccionado; ?> ><?php echo $actividad->actividad; ?></option>
											<?php } ?>
									</select>


						</div>
					</div>

					<div class="form-group">
						<label for="id_estratificacion" class="col-sm-12 col-md-12">Estratificación de Empresa<span class="obligatorio"> *</span></label>
						<div class="col-sm-12 col-md-12">
									<select name="id_estratificacion"  id="id_estratificacion" class="form-control">
											<?php foreach ( $estratificaciones as $estratificacion ){ ?>
													<?php 
													   if  ($estratificacion->id==$proveedor->id_estratificacion)
														 {$seleccionado='selected';} else {$seleccionado='';}
													?>
													<option value="<?php echo $estratificacion->id; ?>" <?php echo $seleccionado; ?> ><?php echo $estratificacion->estratificacion; ?></option>
											<?php } ?>
									</select>
						</div>
					</div>

				</div>


			</div>
		</div>



		<div class="panel panel-primary">
			<div class="panel-heading">Datos del representante legal</div>
			<div class="panel-body">

				<div class="col-sm-6 col-md-6">

					<div class="form-group">
						<label for="nombre" class="col-sm-12 col-md-12">Nombre</label>
						<div class="col-sm-12 col-md-12">
							<?php 
								$nomb_nom='';
								if (isset($proveedor->nombre)) 
								 {	$nomb_nom = $proveedor->nombre;}
							?>
							<input value="<?php echo  set_value('nombre',$nomb_nom); ?>"  type="text" class="form-control" id="nombre" name="nombre" placeholder="nombre">
						</div>
					</div>

					<div class="form-group">
						<label for="rfc" class="col-sm-12 col-md-12">CLAVE R.F.C.</label>
						<div class="col-sm-12 col-md-12">
							<?php 
								$nomb_nom='';
								if (isset($proveedor->rfc)) 
								 {	$nomb_nom = $proveedor->rfc;}
							?>
							<input value="<?php echo  set_value('rfc',$nomb_nom); ?>" type="text" class="form-control" id="rfc" name="rfc" placeholder="RFC">
						</div>
					</div>

					<div class="form-group">
						<label for="curp" class="col-sm-12 col-md-12">C.U.R.P.</label>
						<div class="col-sm-12 col-md-12">
							<?php 
								$nomb_nom='';
								if (isset($proveedor->curp)) 
								 {	$nomb_nom = $proveedor->curp;}
							?>
							<input value="<?php echo  set_value('curp',$nomb_nom); ?>" type="text" class="form-control" id="curp" name="curp" placeholder="C.U.R.P.">
						</div>
					</div>


					<div class="form-group">
						<label for="telefono" class="col-sm-12 col-md-12">Teléfono</label>
						<div class="col-sm-12 col-md-12">
							<?php 
								$nomb_nom='';
								if (isset($proveedor->telefono)) 
								 {	$nomb_nom = $proveedor->telefono;}
							?>
							<input value="<?php echo  set_value('telefono',$nomb_nom); ?>"  type="text" class="form-control" id="telefono" name="telefono" placeholder="Teléfono">
						</div>
					</div>					


				</div>

				<div class="col-sm-6 col-md-6">


					<div class="form-group">
						<label for="email" class="col-sm-12 col-md-12">Email</label>
						<div class="col-sm-12 col-md-12">
							<?php 
								$nomb_nom='';
								if (isset($proveedor->email)) 
								 {	$nomb_nom = $proveedor->email;}
							?>
							<input value="<?php echo  set_value('email',$nomb_nom); ?>" type="text" class="form-control" id="email" name="email" placeholder="Correo electrónico">
						</div>
					</div>

					<div class="form-group">
						<label for="domicilio" class="col-sm-12 col-md-12">Domicilio para recibir notificaciones</label>
						<div class="col-sm-12 col-md-12">
							<?php 
								$nomb_nom='';
								if (isset($proveedor->domicilio)) 
								 {	$nomb_nom = $proveedor->domicilio;}
							?>	
							<textarea class="form-control" name="domicilio" id="domicilio" rows="8" placeholder="Domicilio notificaciones"><?php echo  set_value('domicilio',$nomb_nom); ?></textarea>
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
		
	</div></div>
  <?php echo form_close(); ?>
<?php $this->load->view('footer'); ?>
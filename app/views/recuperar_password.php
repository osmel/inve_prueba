<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php $this->load->view( 'header' ); ?>
	<div class="container">
		<div class="row">
			<h3 class="text-center"><strong>¿No recuerdas tu contraseña?</strong></h3>
		</div>
		<br>
		<br>
		<div class="row">
			<div class="col-md-3"></div>
			<div class="col-md-6">
				<div class="formulario-fondos">
					<?php echo form_open('validar_recuperar_password', array( 'id' => 'form_login', 'name' => 'form_login', 'class' => 'form-horizontal', 'method' => 'POST', 'autocomplete' => 'off', 'role' => 'form' ) ); ?>
						<p>Danos tu email y te enviaremos un correo electrónico con tu contraseña</p>
						<div class="form-group">
							<div class="col-md-12">
								<input type="email" class="form-control" id="email" name="email" placeholder="Correo Electrónico">
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-4">
								<a href="<?php echo base_url(); ?>" class="btn btn-danger btn-block">CANCELAR</a>
							</div>
							<div class="col-md-8">
								<button type="submit" class="btn btn-primary btn-block">RECUPERAR</button>
							</div>
						</div>
					<?php echo form_close(); ?>
				</div>
			</div>
			<div class="col-md-3"></div>
		</div>
	</div>
<?php $this->load->view( 'footer_login' ); ?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="es_MX">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Sistema de almacén Iniciativa textil</title>
	<link rel="stylesheet" href="<?php echo base_url(); ?>js/bootstrap-3.3.1/dist/css/bootstrap.min.css">
	<?php echo link_tag('css/sistema.css'); ?>
		<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.js"></script>
</head>
<body>
	<div class="container-fluid">
		<div id="foo"></div>
		<header class="row">
			<div class="banner">
				<div>
					<div class="container header-content">
						<div class="col-md-6 col-lg-6 col-sm-6 col-xs-3">
							<a href="<?php echo base_url(); ?>">
								<div class="header-logo col-md-1"></div>
							</a>
						</div>
						
						<div class="col-md-6 col-lg-6 col-sm-6 col-xs-9">
							<div class="header-titulo text-right">Sistema de Control de Inventario</div>
							<div class="text-right" style="color:#104A5A !important"> Bienvenid@: <a href="<?php echo base_url(); ?>actualizar_perfil" style="#104A5A"><?php echo $this->session->userdata( 'nombre_completo' ); ?></a>
						    </div>
					   </div>
					   	 
							<div class="col-md-offset-11" id="bar_salir">
								<a title="" href="<?php echo base_url(); ?>salir" class="ttip color-blanco">Salir <i class="glyphicon glyphicon-log-out"></i></a>
							</div>

				</div>

				<?php 
				$this->load->view( 'navbar' ); 
					/*
				   if (isset($almacenes))
					   if ($almacenes!=false) {
						   $this->load->view( 'navbar' ); 
						} else {
							//$this->load->view( 'navbar_conteo' ); 
						}   
						*/

				?>

			</div>
			<div class="barra-verde"></div>
		</header>
		<div class="row-fluid" id="wrapper">
			<div class="alert" id="messages"></div>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php 
	  $perfil= $this->session->userdata('id_perfil'); 
	  $id_session = $this->session->userdata('id');
	  $coleccion_id_operaciones= json_decode($this->session->userdata('coleccion_id_operaciones')); 

	  //if (isset($params['level']) && in_array($params['level'], array('L','M','Q','H'))) $level = $params['level'];
	  //if (!in_array($condition{0},array('>', '<', '='))) {
	  //$colab_id_array =

	  if ( (count($coleccion_id_operaciones)==0) || (!($coleccion_id_operaciones)) )  
	  		{
	  			$coleccion_id_operaciones = array();
	  		} 	


		  	  if ($this->session->userdata('id_almacen') != 0) {
	              $id_almacenid = ' AND ( m.id_almacen =  '.$this->session->userdata('id_almacen').' ) ';  
	          } else {
	              $id_almacenid = '';
	          } 

 
         if ( ( $perfil == 3 ) OR ( $perfil == 4 ) ) { 
            $restriccion  =' AND (m.id_usuario_apartado = "'.$id_session.'")';
         } else {
         	$restriccion = '';
         }

          
         if (  $perfil != 4 ) {
	     		$where_total = '(( m.id_apartado = 2 ) or ( m.id_apartado = 3 ))'.$id_almacenid.$restriccion;
				$dato['vendedor'] = (string)$this->modelo_pedido->total_apartados_pendientes($where_total);
         } else {
         	$dato['vendedor'] ="0";
         }

         if (  $perfil != 3 ) {
			$where_total = '(( m.id_apartado = 5 ) or ( m.id_apartado = 6 ))'.$id_almacenid.$restriccion;
			$dato['tienda'] = (string)$this->modelo_pedido->total_pedidos_pendientes($where_total);  
         } else {
         	$dato['tienda'] ="0";
         }
	















			
			$conteos =  '<span title="Pedidos Vendedores." class="ttip">'.$dato['vendedor'].'</span><span> - </span><span title="Pedidos Tiendas." class="ttip">'.$dato['tienda'].'</span>';




?>	

<div class="row-fluid">
	<div class="navbar navbar-default navbar-custom" role="navigation">
		<div class="container">
		
			
	 <?php  if ($this->session->userdata('session')) {  ?>
				
				<div class="navbar-brand" style="margin-right: 15px;" id="bar_">
					<a href="<?php echo base_url(); ?>" style="color: #ffffff;"><i class="glyphicon glyphicon-home"></i></a>
				</div>
				<?php if ( ( $perfil == 1 ) || (in_array(10, $coleccion_id_operaciones)) ) { ?>
					<div class="navbar-brand" id="bar_pedidos">
						<a href="<?php echo base_url(); ?>pedidos" style="color: #ffffff;"><i class="glyphicon glyphicon-bullhorn"></i></a>
						<span id="etiq_conteo"><?php echo $conteos; ?></span>			
					</div>

				<?php } ?>	

				<div class="navbar-header">
					<button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#main-navbar">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
				</div>

				<div class="collapse navbar-collapse" id="main-navbar">
					<ul class="nav navbar-nav navbar-left" id="menu_opciones">

					 <?php if ( ( $perfil == 1 ) || (in_array(1, $coleccion_id_operaciones)) ) { ?>
						<li id="bar_entradas">
							<a title="Generar Entradas al Almacén." href="<?php echo base_url(); ?>entradas" class="ttip color-blanco">Entradas</a> 
						</li>
					<?php } ?>	

					<?php if ( ( $perfil == 1 ) || (in_array(23, $coleccion_id_operaciones)) ) { ?>
						<li id="bar_devolucion">
							<a title="Sección para realizar devoluciones." href="<?php echo base_url(); ?>devolucion" class="ttip color-blanco">Devoluciones</a> 
						</li>
					<?php } ?>	


					 <?php if ( ( $perfil == 1 ) || (in_array(2, $coleccion_id_operaciones)) ) { ?>
						<li id="bar_salidas">
							<a title="Generar Salidas del Almacén." href="<?php echo base_url(); ?>salidas" class="ttip color-blanco">Salidas</a> 
						</li>
					<?php } ?>		

					 <?php if ( ( $perfil == 1 ) || (in_array(4, $coleccion_id_operaciones)) ) { ?>
						<li id="bar_generar_pedidos">
							<a title="Pedidos desde una tienda o punto de venta." href="<?php echo base_url(); ?>generar_pedidos" class="ttip color-blanco">Pedido</a> 
						</li>
					<?php } ?>	

					 <?php if ( ( $perfil == 1 ) || (in_array(3, $coleccion_id_operaciones)) ) { ?>
						<li id="bar_editar_inventario">
							<a title="Editar productos del inventario." href="<?php echo base_url(); ?>editar_inventario" class="ttip color-blanco">Editar</a> 
						</li>
					<?php } ?>						


					 <?php if ( ( $perfil == 1 ) || (in_array(9, $coleccion_id_operaciones)) ) { ?>
						<li id="bar_reportes">
							<a title="Sección de reportes." href="<?php echo base_url(); ?>reportes" class="ttip color-blanco">Reportes</a> 
						</li>
					<?php } ?>	

					 <?php if ( ( $perfil == 1 ) || (in_array(26, $coleccion_id_operaciones)) ) { ?>
						<li id="bar_traspasos">
							<a title="Todos los catálogos del sistema." href="<?php echo base_url(); ?>traspasos" class="ttip color-blanco">Traspasos</a> 
						</li>
					<?php } ?>					



					 <?php if ( ( $perfil == 1 ) || (in_array(8, $coleccion_id_operaciones)) 
					 		|| (in_array(11, $coleccion_id_operaciones)) || (in_array(12, $coleccion_id_operaciones)) 
					 		|| (in_array(13, $coleccion_id_operaciones)) || (in_array(14, $coleccion_id_operaciones)) 
					 		|| (in_array(15, $coleccion_id_operaciones)) || (in_array(16, $coleccion_id_operaciones)) 
					 		|| (in_array(17, $coleccion_id_operaciones)) || (in_array(18, $coleccion_id_operaciones)) 
					 		|| (in_array(19, $coleccion_id_operaciones)) || (in_array(20, $coleccion_id_operaciones)) 
					 		|| (in_array(21, $coleccion_id_operaciones)) || (in_array(22, $coleccion_id_operaciones)) 
					 ) { ?>
						<li id="bar_catalogos">
							<a title="Todos los catálogos del sistema." href="<?php echo base_url(); ?>catalogos" class="ttip color-blanco">Catálogos</a> 
						</li>
					<?php } ?>					

					<?php if ( ( $perfil == 1 ) || ( (in_array(29, $coleccion_id_operaciones)) || (in_array(30, $coleccion_id_operaciones)) ) ) { ?>		 
						<li id="bar_usuarios"> 
							<a title="Administración cuentas por Pagar." href="<?php echo base_url(); ?>ctasxpagar" class="ttip color-blanco">Ctas X Pagar</a>
						</li>
					 <?php } ?>						



					<?php if ( ( $perfil == 1 ) || (in_array(5, $coleccion_id_operaciones)) ) { ?>		 
						<li id="bar_usuarios"> 
							<a title="Administración de alta/baja de usuarios." href="<?php echo base_url(); ?>usuarios" class="ttip color-blanco">Usuarios</a>
						</li>
					 <?php } ?>						

						
					</ul>
					<!--</div>  fin <div class="wrapper"> -- >
				</div>
	 <?php } ?>
		</div>
	</div>
</div>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<!-- <script type="text/javascript" src="<?php echo base_url(); ?>js/sistema.js"></script> -->
<?php
 	if (!isset($retorno)) {
      	$retorno ="";
    }
 $hidden = array('valor'=>$valor,'id_cliente'=>$id_cliente,'id_almacen'=>$id_almacen); 

 ?>
<?php echo form_open('validar_confirmar_salida_sino', array('class' => 'form-horizontal','id'=>'form_sino','name'=>$retorno, 'method' => 'POST', 'role' => 'form', 'autocomplete' => 'off' ) ,   $hidden ); ?>
	<div class="modal-header">
		<a class="close" data-dismiss="modal">&times;</a>
		<h3 class="text-left">Procesar Salida</h3>
	</div>
	<div class="modal-body">



			<?php if ($valor==1) { ?>
				<p>Hay productos apartados que no fueron agregados a la salida, <b>SI</b> para continuar y descartar productos apartados que no fueron agregados,</p>
				<p><b>NO</b> para continuar agregando productos a la salida.</p>
			<?php } ?>
				
			<?php if ($valor==2) { ?>
					<p>¿Desea procesar la salida? Este proceso descontará los productos del inventario.</p>
			<?php } ?>		

		<div class="alert" id="messagesModal"></div>
	</div>
	<div class="modal-footer">
		<button class="btn btn-danger" name="procesando_salida" id="deleteUserSubmit">SI</button>
		<button class="btn btn-default" data-dismiss="modal">NO</button>
	</div>
	<input type="hidden" id="valor" name="valor" value="<?php echo $valor; ?>">
	<input type="hidden" id="id_cliente" name="id_cliente" value="<?php echo $id_cliente; ?>">
	<input type="hidden" id="id_almacen" name="id_almacen" value="<?php echo $id_almacen; ?>">

	<input type="hidden" id="id_tipo_pedido" name="id_tipo_pedido" value="<?php echo $id_tipo_pedido; ?>">
	<input type="hidden" id="id_tipo_factura" name="id_tipo_factura" value="<?php echo $id_tipo_factura; ?>">

	
	
<?php echo form_close(); ?>

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
 	if (!isset($retorno)) {
      	$retorno ="";
    }


 ?>
<?php echo form_open('validar_salida_pedido', array('class' => 'form-horizontal','id'=>'form_sino','name'=>$retorno, 'method' => 'POST', 'role' => 'form', 'autocomplete' => 'off' ) ); ?>
	<div class="modal-header">
		<a class="close" data-dismiss="modal">&times;</a>
		<h3 class="text-left">Procesar Salida</h3>
	</div>
	<div class="modal-body">



			
					<p>¿Desea procesar la salida? Este proceso descontará los productos del inventario.</p>
			

		<div class="alert" id="messagesModal"></div>
	</div>
	<div class="modal-footer">
		<button class="btn btn-danger" name="procesando_salida" id="deleteUserSubmit">SI</button>
		<button class="btn btn-default" data-dismiss="modal">NO</button>
	</div>
	
	<input type="hidden" id="id_cargador" name="id_cargador" value="<?php echo $id_cargador; ?>">
	<input type="hidden" id="num_mov" name="num_mov" value="<?php echo $num_mov; ?>">
	<input type="hidden" id="id_tipo_pedido" name="id_tipo_pedido" value="<?php echo $id_tipo_pedido; ?>">
	<input type="hidden" id="id_tipo_factura" name="id_tipo_factura" value="<?php echo $id_tipo_factura; ?>">
	<input type="hidden" id="id_almacen" name="id_almacen" value="<?php echo $id_almacen; ?>">
	
	
	
<?php echo form_close(); ?>

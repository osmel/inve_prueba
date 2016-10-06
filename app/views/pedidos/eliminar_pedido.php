<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php 
 	if (!isset($retorno)) {
      	$retorno ="pedidos";
    }
$hidden = array('num_mov'=>$num_mov,'id_almacen'=>$id_almacen,'id_tipo_pedido'=>$id_tipo_pedido, 'id_tipo_factura'=>$id_tipo_factura); ?>
<?php echo form_open('validar_eliminar_pedido_detalle', array('class' => 'form-horizontal','id'=>'form_pedido','name'=>$retorno, 'method' => 'POST', 'role' => 'form', 'autocomplete' => 'off' ) ,   $hidden ); ?>
	<div class="modal-header">
		<a class="close" data-dismiss="modal">&times;</a>
		<h3 class="text-left">Eliminar Pedido</h3>
	</div>
	<div class="modal-body">
		<p>¿Está seguro de que desea eliminar el pedido?</p>
		<p>Recuerde, este proceso es completamente irreversible.</p>
		<div class="alert" id="messagesModal"></div>
	</div>
	<div class="modal-footer">
		<button class="btn btn-danger" id="deleteUserSubmit">Aceptar</button>
		<button class="btn btn-default" data-dismiss="modal">Cancelar</button>
	</div>
	
<?php echo form_close(); ?>
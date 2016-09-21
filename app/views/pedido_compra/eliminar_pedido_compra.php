<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php 
 	if (!isset($retorno)) {
      	$retorno ="/pendiente_revision";
    }
$hidden = array('movimiento'=>$movimiento); ?>
<?php echo form_open('validar_cancelar_pedido_compra', array('class' => 'form-horizontal','id'=>'form_cancelar_pedido_compra','name'=>$retorno, 'method' => 'POST', 'role' => 'form', 'autocomplete' => 'off' ) ,   $hidden ); ?>
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

	<input type="hidden" id="movimiento" name="movimiento" value="<?php echo $movimiento; ?>">
	
<?php echo form_close(); ?>
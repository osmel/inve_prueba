<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
 	if (!isset($retorno)) {
      	$retorno ="";
    }
	$hidden = array('id_almacen'=>$id_almacen); 
?>

<?php echo form_open('confirmar_procesar_contando', array('class' => 'form-horizontal','id'=>'form_sino','name'=>$retorno, 'method' => 'POST', 'role' => 'form', 'autocomplete' => 'off' ) ,   $hidden ); ?>
	<div class="modal-header">
		<a class="close" data-dismiss="modal">&times;</a>
		<h3 class="text-left">Confirmar cantidades</h3>
	</div>
	<div class="modal-body">

							 
							
							¿Desea confirmar las cantidades reales de cada producto? <br/>

							Este proceso guardara la información y no podrá ser editada en el futuro
						

		<div class="alert" id="messagesModal"></div>
	</div>
	<div class="modal-footer">
		<button class="btn btn-danger" name="procesando_confirmar_pedido" id="deleteUserSubmit">SI</button>
		<button class="btn btn-default" data-dismiss="modal">NO</button>
	</div>

	<input type="hidden" id="id_almacen" name="id_almacen" value="<?php echo $id_almacen; ?>">
	<input type="hidden" id="modulo" name="modulo" value="<?php echo $modulo; ?>">
	
	
<?php echo form_close(); ?>

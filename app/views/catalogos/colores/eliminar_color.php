<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
 	if (!isset($retorno)) {
      	$retorno ="colores";
    }
 $hidden = array('id'=>$id); ?>
<?php echo form_open('validar_eliminar_color', array('class' => 'form-horizontal','id'=>'form_catalogos','name'=>$retorno, 'method' => 'POST', 'role' => 'form', 'autocomplete' => 'off' ) ,   $hidden ); ?>
	<div class="modal-header">
		<a class="close" data-dismiss="modal">&times;</a>
		<h3 class="text-left">Eliminar colores</h3>
	</div>
	<div class="modal-body">
		<div style="background-color:#<?php echo $hexadecimal_color; ?>;display:block;width:60px;height:15px;margin:0 auto;"></div>
		<p>¿Está seguro de que desea eliminar el color <b><?php echo  $nombrecompleto ; ?></b>?</p>
		<p>Recuerde, este proceso es completamente irreversible.</p>
		<div class="alert" id="messagesModal"></div>
	</div>
	<div class="modal-footer">
		<button class="btn btn-danger" id="deleteUserSubmit">Aceptar</button>
		<button class="btn btn-default" data-dismiss="modal">Cancelar</button>
	</div>
	<input type="hidden" id="id" name="id" value="<?php echo $id; ?>">
<?php echo form_close(); ?>
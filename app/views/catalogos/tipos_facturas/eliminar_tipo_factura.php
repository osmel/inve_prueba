<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<script type="text/javascript" src="<?php echo base_url(); ?>js/sistema.js"></script>
<?php
 	if (!isset($retorno)) {
      	$retorno ="tipos_facturas";
    }
 $hidden = array('id'=>$id); ?>
<?php echo form_open('validar_eliminar_tipo_factura', array('class' => 'form-horizontal','id'=>'form_catalogos','name'=>$retorno, 'method' => 'POST', 'role' => 'form', 'autocomplete' => 'off' ) ,   $hidden ); ?>
	<div class="modal-header">
		<a class="close" data-dismiss="modal">&times;</a>
		<h3 class="text-left">Eliminar tipos_facturas</h3>
	</div>
	<div class="modal-body">
		<p>¿Está seguro de que desea eliminar el tipo_factura <b><?php echo  $nombrecompleto ; ?></b>?</p>
		<p>Recuerde, este proceso es completamente irreversible.</p>
		<div class="alert" id="messagesModal"></div>
	</div>
	<div class="modal-footer">
		<button class="btn btn-danger" id="deleteUserSubmit">Aceptar</button>
		<button class="btn btn-default" data-dismiss="modal">Cancelar</button>
	</div>
	<input type="hidden" id="id" name="id" value="<?php echo $id; ?>">
<?php echo form_close(); ?>
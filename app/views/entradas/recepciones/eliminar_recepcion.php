<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<script type="text/javascript" src="<?php echo base_url(); ?>js/sistema.js"></script>
<?php
 	if (!isset($retorno)) {
      	$retorno ="proveedores";
    }
 $hidden = array('rfc_fiscal'=>$rfc_fiscal); ?>
<?php echo form_open('validar_eliminar_proveedor', array('class' => 'form-horizontal','id'=>'form_catalogos','name'=>$retorno, 'method' => 'POST', 'role' => 'form', 'autocomplete' => 'off' ) ,   $hidden ); ?>
	<div class="modal-header">
		<a class="close" data-dismiss="modal">&times;</a>
		<h3 class="text-left">Eliminar proveedor</h3>
	</div>
	<div class="modal-body">
		<p>¿Está seguro de que desea eliminar el proveedor <b><?php echo  $nombrecompleto ; ?></b>?</p>
		<p>Recuerde, este proceso es completamente irreversible.</p>
		<div class="alert" id="messagesModal"></div>
	</div>
	<div class="modal-footer">
		<button class="btn btn-danger" id="deleteUserSubmit">Aceptar</button>
		<button class="btn btn-default" data-dismiss="modal">Cancelar</button>
	</div>

<?php echo form_close(); ?>
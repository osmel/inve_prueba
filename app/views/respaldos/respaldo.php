<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<script type="text/javascript" src="<?php echo base_url(); ?>js/sistema.js"></script>

<?php echo form_open('respaldar', array('class' => 'form-horizontal', 'id' => 'form_respaldo','name'=>'form_respaldo', 'method' => 'POST', 'role' => 'form', 'autocomplete' => 'off' ) ); ?>
	<div class="modal-header">
		<a class="close" data-dismiss="modal">&times;</a>
		<h3 class="text-left">Desea respaldar su base de datos</h3>
	</div>
	<div class="modal-body">
		<p>¿Está seguro de que desea respaldar su base de datos?</p>
		<p>Recuerde, este proceso es completamente irreversible.</p>
		<div class="alert" id="messagesModal"></div>
	</div>
	<div class="modal-footer">
		<button class="btn btn-danger" id="deleteUserSubmit">Aceptar</button>
		<button class="btn btn-default" data-dismiss="modal">Cancelar</button>
	</div>
<?php echo form_close(); ?>
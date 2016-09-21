<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<!-- <script type="text/javascript" src="<?php echo base_url(); ?>js/sistema.js"></script> -->
<?php
 	if (!isset($retorno)) {
      	$retorno ="";
    }
 $hidden = array('aprobado'=>$aprobado,'movimiento'=>$movimiento); 

 ?>
<?php echo form_open('validar_confirmar_salida_sino', array('class' => 'form-horizontal','id'=>'form_sino','name'=>$retorno, 'method' => 'POST', 'role' => 'form', 'autocomplete' => 'off' ) ,   $hidden ); ?>
	<div class="modal-header">
		<a class="close" data-dismiss="modal">&times;</a>
		<h3 class="text-left">Confirmar cambios</h3>
	</div>
	<div class="modal-body">



			<?php if ($aprobado=="true") { ?>
					<p>Aprobado.</p>
			<?php } else { ?>
					<p>Modificado.</p>
			<?php } ?>		

		<div class="alert" id="messagesModal"></div>
	</div>
	<div class="modal-footer">
		<button class="btn btn-danger" name="procesando_salida" id="deleteUserSubmit">SI</button>
		<button class="btn btn-default" data-dismiss="modal">NO</button>
	</div>
	<input type="hidden" id="aprobado" name="aprobado" value="<?php echo $aprobado; ?>">
	<input  type="hidden" id="movimiento" name="movimiento" value="<?php echo $movimiento; ?>">
	
	
<?php echo form_close(); ?>

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<!-- <script type="text/javascript" src="<?php echo base_url(); ?>js/sistema.js"></script> -->
<?php
 	if (!isset($retorno)) {
      	$retorno ="";
    }
 $hidden = array('aprobado'=>$aprobado,'movimiento'=>$movimiento); 

 ?>
<?php echo form_open('confirmar_pedido_compra', array('class' => 'form-horizontal','id'=>'form_sino','name'=>$retorno, 'method' => 'POST', 'role' => 'form', 'autocomplete' => 'off' ) ,   $hidden ); ?>
	<div class="modal-header">
		<a class="close" data-dismiss="modal">&times;</a>
		<h3 class="text-left">Confirmar cambios</h3>
	</div>
	<div class="modal-body">
	
		<?php if ($modulo==1) { ?>		
			<?php if ($aprobado=="true") { ?>
					<p>¿Desea aprobar esta orden, y enviarla al almacenista?</p>
			<?php } else { ?>
					<p>¿Desea enviar esta orden al almacenista para su modificación o aprobación?</p>
			<?php } ?>		
		<?php } else { ?>			

			<?php if ($aprobado=="true") { ?>
					<p>¿Desea aprobar esta orden?. Luego, ver sección <b>aprobados</b> para imprimir y confirmar la orden</p>
			<?php } else { ?>
					<p>¿Desea enviar esta orden al administrador para su modificación o aprobación?</p>
			<?php } ?>		

		<?php } ?>			
		

		<div class="alert" id="messagesModal"></div>
	</div>
	<div class="modal-footer">
		<button class="btn btn-danger" name="procesando_confirmar_pedido" id="deleteUserSubmit">SI</button>
		<button class="btn btn-default" data-dismiss="modal">CANCELAR</button>
	</div>
	<input type="hidden" id="aprobado" name="aprobado" value="<?php echo $aprobado; ?>">
	<input  type="hidden" id="movimiento" name="movimiento" value="<?php echo $movimiento; ?>">
	<input  type="hidden" id="modulo" name="modulo" value="<?php echo $modulo; ?>">
	<input  type="hidden" id="retorno" name="retorno" value="<?php echo $retorno; ?>">
	
	
<?php echo form_close(); ?>

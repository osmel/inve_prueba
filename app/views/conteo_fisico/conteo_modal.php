<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
 	if (!isset($retorno)) {
      	$retorno ="";
    }
	$hidden = array('id_almacen'=>$id_almacen); 
?>

<?php echo form_open('confirmar_proceso_conteo', array('class' => 'form-horizontal','id'=>'form_sino','name'=>$retorno, 'method' => 'POST', 'role' => 'form', 'autocomplete' => 'off' ) ,   $hidden ); ?>
	<div class="modal-header">
		<a class="close" data-dismiss="modal">&times;</a>
		<h3 class="text-left">Conteo físico del inventario</h3>
	</div>
	<div class="modal-body">

						<?php if ( $cantidad==0) { ?>
							¿Desea continuar para imprimir y realizar el conteo físico?
						<?php } else { ?>
						
							Existen operaciones en proceso. <br/>
							¿Desea <b>cancelar todas las operaciones</b> y continuar con el conteo físico?.
							<br/>
							Este proceso es irreversible

						<?php }  ?>
						

		<div class="alert" id="messagesModal"></div>
	</div>
	<div class="modal-footer">
		<button class="btn btn-danger" name="procesando_confirmar_pedido" id="deleteUserSubmit">SI</button>
		<button class="btn btn-default" data-dismiss="modal">NO</button>
	</div>

	<input type="hidden" id="id_almacen" name="id_almacen" value="<?php echo $id_almacen; ?>">
	<input  type="hidden" id="id_descripcion" name="id_descripcion" value="<?php echo $id_descripcion; ?>">
	<input  type="hidden" id="id_color" name="id_color" value="<?php echo $id_color; ?>">
	<input  type="hidden" id="id_composicion" name="id_composicion" value="<?php echo $id_composicion; ?>">
	<input  type="hidden" id="id_calidad" name="id_calidad" value="<?php echo $id_calidad; ?>">
	
	<input  type="hidden" id="id_factura" name="id_factura" value="<?php echo $id_factura; ?>">
	<input  type="hidden" id="proveedor" name="proveedor" value="<?php echo $proveedor; ?>">
	
	
<?php echo form_close(); ?>

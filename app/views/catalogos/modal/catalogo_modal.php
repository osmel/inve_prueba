<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<script type="text/javascript" src="<?php echo base_url(); ?>js/sistema.js"></script>

	<?php echo link_tag('css/colorpicker.css'); ?>
	<script src="<?php echo base_url(); ?>js/colorpicker.js" type="text/javascript"></script>


<?php 
	$hidden = array('catalogo'=>$catalogo,'uri'=>$uri); 
	
	$urii = base64_decode($uri); 	
	$cata = base64_decode($catalogo); 	
?>

 

<?php echo form_open('validar_catalogo_modal', array('class' => 'form-horizontal','id'=>'form_modales','name'=>$urii, 'method' => 'POST', 'role' => 'form', 'autocomplete' => 'off' ) ,   $hidden ); ?>
	<div class="modal-header">
		<a class="close" data-dismiss="modal">&times;</a>
		<div class="panel-heading">Alta a nueva <?php echo $cata; ?></div>
	</div>
	
	<div class="modal-body">
		<div class="panel panel-primary">
			
			<div class="panel-body">
					<div class="form-group">
						<label for="<?php echo $cata; ?>" class="col-sm-12 col-md-12"><?php echo ucfirst($cata); ?></label>
						<div class="col-sm-12 col-md-12">
							<input type="text" class="form-control" id="<?php echo $cata; ?>" name="<?php echo $cata; ?>" placeholder="<?php echo ucfirst($cata); ?>">
						</div>
					</div>
			</div>

			<?php if ( isset($hexadecimal_color) ) { ?>	
				<div class="panel-body">
						
						<div class="col-sm-6 col-md-6">
							<div class="form-group">
								<span class="mini-bloque">
									<label for="hexadecimal_color" class="col-sm-3 col-md-2 control-label">color</label>
									<div class="col-sm-12 col-md-12">
										<div id="colorPicker"></div>
											<input type="text" id="hexadecimal_color" name="hexadecimal_color" placeholder="Color" value="<?php echo set_value('hexadecimal_color'); ?>" class="colorSelector form-control">
										<div class="colorSelector"></div>
										<em>Aquí puedes seleccionar un color que más se parezca al color de tela que piensas dar de alta.</em>
									</div>	
								</span>
							</div>
						</div>


				</div>
			<?php } ?>		
		</div>
		
	</div>
	<div class="modal-footer">
		<button class="btn btn-danger" id="deleteUserSubmit">Aceptar</button>
		<button class="btn btn-default" data-dismiss="modal">Cancelar</button>
	</div>
	<input type="hidden" id="uri" name="uri" value="<?php echo $uri; ?>">
	<input type="hidden" id="catalogo" name="catalogo" value="<?php echo $catalogo; ?>">

<?php echo form_close(); ?>
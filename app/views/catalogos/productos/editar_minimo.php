<meta charset="UTF-8">
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php $this->load->view('header'); ?>
<?php 

	
   $coleccion_id_operaciones= json_decode($this->session->userdata('coleccion_id_operaciones')); 
   if ( (count($coleccion_id_operaciones)==0) || (!($coleccion_id_operaciones)) ) {
        $coleccion_id_operaciones = array();
   }   


 	if (!isset($retorno)) {
      	$retorno ="productos";
    }

  $hidden = array('id'=>$producto->id, 'referencia'=> $producto->referencia);
  $attr = array('class' => 'form-horizontal', 'id'=>'form_prod','name'=>$retorno,'method'=>'POST','autocomplete'=>'off','role'=>'form');
  echo form_open('validacion_edicion_minimo', $attr,$hidden);
?>	
<div class="container">
		<br>
	<div class="row">
		<div class="col-sm-8 col-md-8"><h4>Edición de mínimo</h4></div>
	</div>
	<br>
	<div class="container row">
		<div class="panel panel-primary">
			<div class="panel-heading">Datos del producto</div>
			<div class="panel-body">
				<div class="col-sm-12 col-md-6">


					<fieldset disabled>


					  <div class="form-group">
						<label for="consecutivo" class="col-sm-12 col-md-12">Último Consecutivo</label>
						<div class="col-sm-12 col-md-12">
						  <?php 
							$nomb_nom='';
							if (isset($producto->consecutivo)) 
							 {  $nomb_nom = $producto->consecutivo;}
						  ?>
						  <input restriccion="entero" value="<?php echo  set_value('consecutivo',$nomb_nom); ?>" type="text" class="form-control" id="consecutivo" name="consecutivo" placeholder="Consecutivo">
						</div>
					  </div>
					

					
						<div class="form-group">
							<label for="descripcion" class="col-sm-12 col-md-12">Nombre</label>
							<div class="col-sm-12 col-md-12">
								<?php 
									$nomb_nom='';
									if (isset($producto->descripcion)) 
									 {	$nomb_nom = $producto->descripcion;}
								?>
								<input restriccion="nominuscula" title="Letras mayusculas, números y espacios." value="<?php echo  set_value('descripcion',$nomb_nom); ?>" type="text" class="form-control ttip" id="descripcion" name="descripcion" placeholder="Nombre">
							</div>
						</div>
					

				
					  <div class="form-group">
						<label for="ancho" class="col-sm-12 col-md-12">Ancho</label>
						<div class="col-sm-12 col-md-12">
						  <?php 
							$nomb_nom='';
							if (isset($producto->ancho)) 
							 {  $nomb_nom = $producto->ancho;}
						  ?>
						  <input restriccion="decimal" value="<?php echo  set_value('ancho',$nomb_nom); ?>" type="text" class="form-control ttip" title="Números y puntos decimales." id="ancho" name="ancho" placeholder="Ancho">
						</div>
					  </div>
				
					
					
						  <div class="form-group">
							<label for="precio" class="col-sm-12 col-md-12">Precio</label>
							<div class="col-sm-12 col-md-12">
							  <?php 
								$nomb_nom='';
								if (isset($producto->precio)) 
								 {  $nomb_nom = $producto->precio;}
							  ?>
							  <input restriccion="decimal" value="<?php echo  set_value('precio',$nomb_nom); ?>" type="text" class="form-control ttip" title="Números y puntos decimales." id="precio" name="precio" placeholder="Precio">
							</div>
						  </div>
						
					
					<!--colores-->
							<div class="form-group">
								<label for="color" class="col-xs-12 col-sm-12 col-md-12 col-lg-12">Color</label>


									<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
									
										<select name="id_color" id="id_color" class="form-control">
												<?php foreach ( $colores as $color ){ ?>
														<?php 
														   if  ($color->id==$producto->id_color)
															 {$seleccionado='selected';} else {$seleccionado='';}
														?>
														<option value="<?php echo $color->id; ?>" style="background-color:#<?php echo $color->hexadecimal_color; ?>" <?php echo $seleccionado; ?>><?php echo $color->color; ?></option>
												<?php } ?>
										</select>
									</div>

							</div>
							
							<!--composiciones-->					
							<div class="form-group">
								<label for="descripcion" class="col-xs-12 col-sm-12 col-md-12 col-lg-12">Composición</label>

						              <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

									<select name="id_composicion" id="id_composicion" class="form-control">
										<?php foreach ( $composiciones as $composicion ){ ?>
											<?php 
											   if  ($composicion->id==$producto->id_composicion)
											   {$seleccionado='selected';} else {$seleccionado='';}
											?>
											<option value="<?php echo $composicion->id; ?>" <?php echo $seleccionado; ?>><?php echo $composicion->composicion; ?></option>
										<?php } ?>
									</select>
								  </div>
							  </div>  
							  
							  <!--calidades-->
							  <div class="form-group">
								<label for="calidad" class="col-xs-10 col-sm-10 col-md-10 col-lg-10">calidad</label>
						              <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

											<select name="id_calidad" id="id_calidad" class="form-control">
												<?php foreach ( $calidades as $calidad ){ ?>
													<?php 
													   if  ($calidad->id==$producto->id_calidad)
													   {$seleccionado='selected';} else {$seleccionado='';}
													?>
													<option value="<?php echo $calidad->id; ?>" <?php echo $seleccionado; ?>><?php echo $calidad->calidad; ?></option>
												<?php } ?>
											</select>
									  </div>

							  </div>
												  
							
			
			
  					  </fieldset>
					  
					
	
	
					<div class="form-group">
						<label for="minimo" class="col-sm-12 col-md-12">Minimo</label>
						<div class="col-sm-12 col-md-12">
							<?php 
								$nomb_nom='';
								if (isset($producto->minimo)) 
								 {	$nomb_nom = $producto->minimo;}
							?>
							<input value="<?php echo  set_value('minimo',$nomb_nom); ?>" type="text" class="form-control ttip" title="Números enteros."  restriccion="entero" id="minimo" name="minimo" placeholder="Minimo">
						</div>
					</div>	
					
					<fieldset disabled>
					<!-- Imagen-->	
					<div class="form-group">
						<div class="col-sm-12 col-md-12">
							<div class="panel-heading">
								<h4 class="azul bloque-informacion-azul">Imagen</h4>
							</div>
							<div class="panel-body">

									<?php
									if  ($total_archivos->cantidad==0) {
										print "Usted no tiene imagen adjunta.";
									} else  { ?>	

	 									   <a target="_blank" href="<?php echo base_url(); ?>uploads/productos/<?php echo $total_archivos->archivo; ?>" type="button">
													 <img src="<?php echo base_url(); ?>uploads/productos/thumbnail/300X300/<?php echo substr($producto->imagen,0,-4).'_thumb'.substr($producto->imagen,-4); ?>" border="0" width="300" height="200">
										   </a>


 									<?php     
									}   
									print '<br/>';
									 ?>	
								
							</div>
						</div>
					</div>



					
					
					
					<!-- comentarios-->	
					
						<div class="form-group">
							<label for="comentario" class="col-sm-12 col-md-12">Especificaciones</label>
							<div class="col-sm-12 col-md-12">
								<?php 
									$nomb_nom='';
									if (isset($producto->comentario)) 
									 {	$nomb_nom = $producto->comentario;}
								?>	

								<textarea  class="form-control" name="comentario" id="comentario" rows="6" placeholder="Comentarios"><?php echo  set_value('comentario',$nomb_nom); ?></textarea>
							</div>
						</div>						
					
					<!-- target="_blank" -->					
					</fieldset>		








				</div>


			</div>
		</div>

		
		

		<div class="row">
			<div class="col-sm-4 col-md-4"></div>
			<div class="col-sm-4 col-md-4 marginbuttom">
				<a href="<?php echo base_url(); ?><?php echo $retorno; ?>" type="button" class="btn btn-danger btn-block">Cancelar</a>
			</div>
			<div class="col-sm-4 col-md-4">
				<input type="submit" class="btn btn-success btn-block" value="Guardar"/>
			</div>
		</div>

		<br>
		
	</div></div>
  <?php echo form_close(); ?>

<div class="modal fade bs-example-modal-lg" id="modalMessage" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog" style="margin-top:150px !important">
        <div class="modal-content" ></div>
    </div>
</div>	
  
<?php $this->load->view('footer'); ?>

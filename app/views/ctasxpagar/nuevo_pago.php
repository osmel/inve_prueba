<meta charset="UTF-8">
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php $this->load->view('header'); ?>
<?php 

	
   $coleccion_id_operaciones= json_decode($this->session->userdata('coleccion_id_operaciones')); 
   if ( (count($coleccion_id_operaciones)==0) || (!($coleccion_id_operaciones)) ) {
        $coleccion_id_operaciones = array();
   }   

 	if (!isset($retorno)) {
      	$retorno ="";
    }

  $hidden = array('movimiento'=>$movimiento,'total'=>$pago->total,'id_factura'=>$pago->id_factura,'id_almacen'=>$pago->id_almacen,'id_empresa'=>$pago->id_empresa );
  $attr = array('class' => 'form-horizontal', 'id'=>'form_pago','name'=>$retorno,'method'=>'POST','autocomplete'=>'off','role'=>'form');
  echo form_open('validacion_nuevo_ctasxpagar', $attr,$hidden);

?>	
<div class="container">
		<br>
	<div class="row">
		<div class="col-sm-8 col-md-8"><h4>Nuevo pago</h4></div>
	</div>
	<br>
	<div class="container row">
		<div class="panel panel-primary">
			<div class="panel-heading">Datos del pago</div>
			<div class="panel-body">
				<div class="col-sm-12 col-md-6">


					<!--colores-->
					<div class="form-group">
						<label for="color" class="col-xs-12 col-sm-12 col-md-12 col-lg-12">Instrumento</label>



							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							
								<select name="id_documento_pago" id="id_documento_pago" class="form-control">
										<?php foreach ( $doc_pagos as $doc_pago ){ ?>
												<option value="<?php echo $doc_pago->id; ?>" ><?php echo $doc_pago->documento_pago; ?></option>
										<?php } ?>
								</select>
							</div>

					</div>

					  <div class="form-group">
						<label for="instrumento_pago" class="col-sm-12 col-md-12">Referencia</label>
						<div class="col-sm-12 col-md-12">
						
						  <input restriccion="entero" type="text" class="form-control" id="instrumento_pago" name="instrumento_pago" placeholder="Referencia">
						</div>
					  </div>
					

	
					  <div class="form-group">
						<label for="importe" class="col-sm-12 col-md-12">Importe</label>
						<div class="col-sm-12 col-md-12">
						  
						  <input restriccion="decimal" type="text" class="form-control" id="importe" name="importe" placeholder="Importe">
						</div>
					  </div>				

				</div>

				<div class="col-sm-12 col-md-6">

					<!-- comentarios-->	
					
					<div class="form-group">
						<label for="fecha_pago" class="col-sm-12 col-md-12">Fecha de pago:<span class="obligatorio"> *</span></label>
						<div class="col-sm-12 col-md-12">
							<input type="text" class="fecha  input-sm form-control" id="fecha_pago" name="fecha_pago" placeholder="DD-MM-YYYY">
								
						</div>
					</div>

						<div class="form-group">
							<label for="comentario" class="col-sm-12 col-md-12">Comentarios</label>
							<div class="col-sm-12 col-md-12">

								<textarea  class="form-control" name="comentario" id="comentario" rows="6" placeholder="Comentarios"></textarea>
							</div>
						</div>						
					
					<!-- -->		

				</div>


			</div>
		</div> <!--fin <div class="panel panel-primary"> -->

		
		

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
  
<?php $this->load->view('footer'); ?>

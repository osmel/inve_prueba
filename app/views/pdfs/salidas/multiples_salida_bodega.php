<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php $this->load->view('header'); ?>

<?php 
			$retorno = "/detalles_salidas_bodegas/".base64_encode($movimientos[0]->movimiento_unico_apartado );
			//http://inventarios.dev.com/detalles_salidas_bodegas/Ng==
 ?>
 <h1 style="text-align:center;">Se generaron los siguientes movimientos:</h1>
 <br/>
 						<div class="col-md-3"></div>
					<?php if ( isset($movimientos) && !empty($movimientos) ){  ?>
						<?php foreach( $movimientos as $mov ) { ?>
							

							<div class="col-md-3">

							<a style="padding: 1px 0px 1px 0px;" href="/detalle_salidas/<?php echo base64_encode($mov->mov_salida_unico ).'/'.base64_encode($mov->cliente).'/'.base64_encode($mov->cargador).'/'.base64_encode($mov->id_tipo_pedido).'/'.base64_encode($mov->id_tipo_factura).'/'.base64_encode($retorno).'/'.base64_encode($mov->id_estatus); ?>" type="button" class="btn btn-success btn-block">B-<?php echo ($mov->mov_salida_unico ); ?></a>
							</div>

 
						<?php } ?>
					<?php } ?>	
					

<?php echo form_close(); ?>

<?php $this->load->view('footer'); ?>



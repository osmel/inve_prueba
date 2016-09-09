<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php $this->load->view( 'header' ); ?>
<?php 

	
   $coleccion_id_operaciones= json_decode($this->session->userdata('coleccion_id_operaciones')); 
   if ( (count($coleccion_id_operaciones)==0) || (!($coleccion_id_operaciones)) ) {
        $coleccion_id_operaciones = array();
   }   

 	if (!isset($retorno)) {
      	$retorno ="";
    }


if (ltrim($retorno)=="") {
	$regreso = " Ir a Home";
} elseif ($retorno=="reportes") {
	$regreso = " Ir a Reportes";
} else {
		$regreso = " Regresar";
}


 

//print_r($movimientos->devolucion);

    //print_r($retorno);
  
$hidden = array('movimiento'=>$movimiento); 
$attr = array('class' => 'form-horizontal', 'id'=>'form_entradas1','name'=>$retorno,'method'=>'POST','autocomplete'=>'off','role'=>'form');
echo form_open('pdfs/generar', $attr,$hidden );
?>		
<div class="container margenes">
			<div class="panel panel-primary">
			<div class="panel-heading">Número de Movimiento <?php echo $movimiento; ?>
				     &nbsp;  &nbsp;  &nbsp; <b>Almacén</b>: <?php //echo $movimientos[0]->almacen; ?>
			</div>
			<div class="panel-body">		
					
					
				<div class="col-sm-6 col-md-6">
				</div>	
					<!--Imprimir-->			
					<div class="col-sm-3 col-md-3">
						<label for="descripcion" class="col-sm-12 col-md-12"></label>
						<a href="<?php echo base_url(); ?>generar_etiquetas/<?php echo base64_encode($movimiento); ?>/<?php echo base64_encode($movimiento); ?>" 
						

							type="button" class="btn btn-success btn-block" target="_blank">Imprimir etiquetas
							
						</a>
					</div>



			<div class="col-sm-12 col-md-12" style="margin-top:20px;">

					<div class="table-responsive">
						<h4>Pagos Realizados</h4>	
						<br>	

						<section>
							<table id="tabla_ctas_vencidas" class="display table table-striped table-bordered table-responsive" cellspacing="0" width="100%">
								<thead>
									<tr>
											<th class="text-center cursora" width="10%">Movimiento  </th>
											<th class="text-center cursora" width="10%">Tipo Pago  </th>
											<th class="text-center cursora" width="5%">Almacén  </th>
											<th class="text-center cursora" width="35%">Proveedor  </th>
											
											<th class="text-center cursora" width="10%">Fecha  </th>
											<th class="text-center cursora" width="10%">Factura  </th>
											
											<th class="text-center cursora" width="10%">Subtotal  </th>
											<th class="text-center cursora" width="10%">IVA  </th>
											<th class="text-center cursora" width="10%">Total  </th>
											<!-- <th class="text-center " width="20%"><strong>Días</strong></th> -->
											<th class="text-center " width="20%"><strong>Monto Pagado</strong></th>
											<th class="text-center " width="20%"><strong>Detalles</strong></th>
									</tr>
								</thead>
							</table>
						</section>

					</div>

				
				<br>
				<div class="row">

					<div class="col-sm-8 col-md-9"></div>
					<div class="col-sm-4 col-md-3" style="margin-bottom:25px;">
						<a href="<?php echo base_url(); ?><?php echo $retorno; ?>" class="btn btn-danger btn-block"><i class="glyphicon glyphicon-backward"></i><?php echo $regreso; ?></a>
					</div>
				</div>	

		</div>

<div class="modal fade bs-example-modal-lg" id="modalMessage" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
        <div class="modal-content"></div>
    </div>
</div>	


<?php echo form_close(); ?>

<?php $this->load->view('footer'); ?>



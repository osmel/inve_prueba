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


	if (ltrim($retorno)=="") {
		$regreso = "Ir a Home";
	} elseif ($retorno=="reportes") {
		$regreso = "Ir a Reportes";
	} else {
			$regreso = "Regresar";
	}
    

	$config_impresion= $this->session->userdata('config_impresion');
  
$hidden = array('id_movimiento'=>$encabezado['num_movimiento']); 
$attr = array('class' => 'form-horizontal', 'id'=>'form_entradas1','name'=>$retorno,'method'=>'POST','autocomplete'=>'off','role'=>'form');
echo form_open('pdfs/generar', $attr,$hidden );
?>		
<div class="container margenes">
			<div class="panel panel-primary">
			<div class="panel-heading">Número de Movimiento <?php echo $etiq_mov; ?>: <?php echo $encabezado['num_movimiento']; ?> </div>
			<div class="panel-body">


				<div class="col-sm-2 col-md-2">
						<h4>Almacén: <?php echo $movimientos[0]->almacen; ?></h4>
				</div>


				<div class="col-sm-2 col-md-2">
						<h4>Mov. de pedido: <?php echo $movimientos[0]->mov_pedido; ?></h4>
				</div>
				 

				<div class="col-sm-3 col-md-3">		
						<h4>Vendedor: <?php echo $encabezado['cliente']; ?></h4>
				</div>
				<div class="col-sm-3 col-md-3">
						<h4>Cargador: <?php echo $encabezado['cargador']; ?></h4>
				</div>


				 

				<div class="col-sm-2 col-md-2">
						<?php if ($movimientos[0]->id_tipo_factura!=0) { ?>
								<h4>Tipo de Salida: </b> <?php echo $movimientos[0]->tipo_factura; ?><h4>
						<?php } else { ?>
							<h4>Tipo de Salida: </b> <?php echo $movimientos[0]->tipo_pedido; ?><h4>
						<?php } ?>	
				</div>		

				<div class="col-sm-4 col-md-4" style="margin-bottom:25px">
					<label for="descripcion" class="col-sm-12 col-md-12"></label>
					<a href="<?php echo base_url(); ?>generar_salida_rapida/<?php echo base64_encode($encabezado['num_movimiento']); ?>/<?php echo base64_encode($id_tipo_pedido); ?>/<?php echo base64_encode($id_tipo_factura); ?>/<?php echo base64_encode($movimientos[0]->id_estatus); ?>"  
						type="button" class="btn btn-success btn-block" target="_blank">Imprimir nota
					</a>
				</div>				

				<div class="col-sm-4 col-md-4"  <?php echo 'style="display:'.( (($config_impresion->activo==0) ) ? 'none':'block').'"'; ?>>
					<label for="descripcion" class="col-sm-12 col-md-12"></label>
					<a href="<?php echo base_url(); ?>generar_salida/<?php echo base64_encode($encabezado['num_movimiento']); ?>/<?php echo base64_encode($id_tipo_pedido); ?>/<?php echo base64_encode($id_tipo_factura); ?>/<?php echo base64_encode($movimientos[0]->id_estatus); ?>"  
						type="button" class="btn btn-success btn-block" target="_blank">PDF nota
					</a>
				</div>



			<div class="col-md-12">
				<div class="table-responsive">
				<table class="table table-striped table-bordered table-responsive tabla_ordenadas" >
					<thead>	
						<tr>
							<th class="text-center cursora" style="width:20%">Código<i class="glyphicon glyphicon-sort"></i></th>
							<th class="text-center cursora" style="width:18%">Descripción <i class="glyphicon glyphicon-sort"></i></th>
							<th class="text-center cursora" style="width:7%">Color<i class="glyphicon glyphicon-sort"></i></th>
							<th class="text-center cursora" style="width:4%">Medida<i class="glyphicon glyphicon-sort"></i></th>
							<th class="text-center cursora" style="width:10%">Ancho <i class="glyphicon glyphicon-sort"></i></th>
							<th class="text-center cursora" style="width:6%">Peso Entrada <i class="glyphicon glyphicon-sort"></i></th>
							<th class="text-center cursora" style="width:6%">Peso Salida <i class="glyphicon glyphicon-sort"></i></th>
							<th class="text-center cursora" style="width:9%">Lote<i class="glyphicon glyphicon-sort"></i></th>
							<th class="text-center cursora" style="width:9%">No. de Partida<i class="glyphicon glyphicon-sort"></i></th>
							<th class="text-center cursora" style="width:10%">Vendedor<i class="glyphicon glyphicon-sort"></i></th>
							
						</tr>
					</thead>		

					<tbody>	
					<?php if ( isset($movimientos) && !empty($movimientos) ): ?>
						<?php foreach( $movimientos as $movimiento ): ?>
							<tr style="color:<?php echo $movimiento->color_devolucion?>">
								<td class="text-center"><?php echo $movimiento->codigo; ?></td>								
								
								<td class="text-center"><?php echo $movimiento->id_descripcion.'<br/><b style="color:red;">Cód: </b>'.$movimiento->codigo_contable; ?> </td>

								<td class="text-center">
									<div style="background-color:#<?php echo $movimiento->hexadecimal_color; ?>;display:block;width:15px;height:15px;margin:0 auto;"></div>
								</td>
								<td class="text-center"><?php echo $movimiento->cantidad_um; ?> <?php echo $movimiento->medida; ?></td>
								<td class="text-center"><?php echo $movimiento->ancho; ?> cm</td>
								<td class="text-center"><?php echo $movimiento->peso_entrada; ?> Kgs</td>
								<td class="text-center"><?php echo $movimiento->peso_real; ?> Kgs</td>
								<td class="text-center"><?php echo $movimiento->id_lote; ?> - <?php echo $movimiento->consecutivo; ?></td>
								<td class="text-center"><?php echo $movimiento->num_partida; ?></td>
								<td class="text-center"><?php echo $movimiento->nom_vendedor; ?></td>
									
							</tr>
								
						<?php endforeach; ?>
					

					<?php else : ?>
							<tr class="noproducto">
								<td colspan="10">No se han agregado producto</td>
							</tr>

					<?php endif; ?>		

					</tbody>		
				</table>
			</div>

			
		
		<br>
		<div class="row">

			<div class="col-md-9"></div>
			<div class="col-md-3">
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



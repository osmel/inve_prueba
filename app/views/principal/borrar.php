
													<!--
														<a class="thumbnail col-md-12 col-lg-12 col-xs-12">
																<span class="glyphicon glyphicon-remove"></span>
														</a>
													-->

												<!--
												<td>
													<a href="<?php echo base_url(); ?>eliminar_producto/<?php echo $producto->id; ?>/<?php echo base64_encode($producto->producto) ; ?>"  
														class="btn btn-danger btn-sm btn-block" data-toggle="modal" data-target="#modalMessage5">
														<span class="glyphicon glyphicon-remove"></span>
													</a>
												</td>						
												-->
									
									<!--
									<tr>
										<td class="text-center">012556546546 1</td>	
										<td class="text-center">001</td>	
										<td class="text-center">150</td>	
										<td class="text-center">2</td>	
										<td class="text-center">20/10/14</td>
										<td class="text-center"><input style="padding:1px;" type="submit" class="btn btn-success btn-block" value="Apartar"/></td>
									</tr>
								-->


  				<!--

                      Código
                      Lote 
                      Metros
                      Ancho 
                      Entrada
                  -->




jQuery('table').on('click','.apartar', function (e) {

	identificador = (jQuery(this).attr('identificador'));

	proveedor = jQuery('.buscar_proveedor').typeahead("val");
	cargador = jQuery('.buscar_cargador').typeahead("val");
	factura = jQuery("#factura").val();
	movimiento = jQuery("#movimiento").val();
	


	jQuery.ajax({
		        url : 'agregar_prod_salida',
		        data : { 
		        	identificador: identificador,
		        	id_cliente:proveedor,
		        	id_cargador:cargador,
		        	factura: factura,
		        	movimiento: movimiento
		        },
		        type : 'POST',
		        dataType : 'json',
		        success : function(data) {	
						if(data != true){
							//aqui es donde va el mensaje q no se ha copiado
						}else{
							jQuery("fieldset.disabledme").attr('disabled', true);

							jQuery('#tabla_salida').dataTable().fnDraw();
							jQuery('#tabla_entrada').dataTable().fnDraw();
							 return false;
							 
						}


		        }
	});						        
	
	
});                  								
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
 	if (!isset($retorno)) {
      	$retorno ="";
    }



 $hidden = array('grupo'=>$grupo); ?>

<input type="hidden" id="grupo_oculto" name="grupo_oculto" value="<?php echo $grupo; ?>">

<input type="hidden" id="id_almacen" name="id_almacen" value="<?php echo $id_almacen; ?>">

		        			<!-- Encabezado -->
		         <div class="modal-header col-xs-12 col-md-12 col-lg-12">
		            <button type="button" class="close" 
		               data-dismiss="modal" aria-hidden="true">
		                  &times;
		            </button>
		            <h4 class="modal-title" id="myModalLabel">
		               Catálogo de productos
		            </h4>
		         </div>
		         
						         <!-- Contenido -->
		         <div class="modal-body col-xs-12 col-md-12 col-lg-12">
		         	
				         	<div class="col-xs-12 col-md-12 col-lg-12">         	
								        
						         	<?php 
						         		//$nombre_fichero ='uploads/productos/thumbnail/516X516/'.(substr($el_producto->imagen,0,-4).'_thumb'.substr($el_producto->imagen,-4)); 
						         		  $nombre_fichero ='uploads/productos/thumbnail/300X300/'.substr($el_producto->imagen,0,strrpos($el_producto->imagen,".")).'_thumb'.substr($el_producto->imagen,strrpos($el_producto->imagen,"."));
										if (file_exists($nombre_fichero)) {
										    echo '<img src="'.$nombre_fichero.'" class="img_peque img-responsive col-xs-12 col-sm-4 col-md-2 col-lg-2">';
										} else {
										    echo '<img src="img/sinimagen.png" class="img_peque img-responsive col-xs-12 col-sm-4 col-md-2 col-lg-2" width="344px" height="314px">';
										}
						         	?>
								        
								        
							         	<span class="col-xs-12 col-sm-8 col-md-9 col-lg-9 nombre"><?php echo $el_producto->descripcion?></span>
									    <span class="col-xs-12 col-sm-8 col-md-9 col-lg-9 cantidadtotal"><?php echo $el_producto->composicion?></span>
									    


										<span class="col-xs-12 col-sm-4 col-md-5 col-lg-5 color">
											<select name="id_color_grupo" id="id_color_grupo" class="form-control">
													<option imagen="<?php echo $el_producto->imagen; ?>" value="-1" hexacolor="FFFFFF"  >Seleccione un color</option>
													<?php foreach ( $colores as $color ){ ?>
															<option imagen="<?php echo $color->imagen; ?>" value="<?php echo $color->id_color; ?>" precio="<?php echo $color->precio; ?>"  hexacolor="<?php echo $color->hexadecimal_color; ?>" style="background-color:#<?php echo $color->hexadecimal_color; ?>" ><?php echo $color->nombre_color; ?></option>
													<?php } ?>
											</select>
											<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="height:12px;"></div>	
											<span class="col-xs-12 col-sm-12 col-md-12 col-lg-12 color">
												 <div id="color_box" style="background-color:;display:block;width:100%;height:25px;margin:0 auto;"></div>
											</span>	

										</span>

										
										<span class="col-xs-12 col-sm-4 col-md-3 col-lg-3 preciocatalogo" style="display:none;"></span> <!--Precio: $<?php //echo $el_producto->precio?>-->
		


							</div>

							<div id="disponibilidad" class="col-xs-12 col-md-12 col-lg-12" style="padding: 20px 10px 15px 15px;">  
									 <input id="ver_dis" style="padding:1px;" type="submit" class="btn btn-success btn-block" value="Ver Disponibilidad"/>
							</div>
							<br/>


							


							


		        


<div class="container col-xs-12 col-sm-12 col-md-12 col-lg-12">	
<div class="row">					
	

									<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="cont_tab">      
										<div class="table-responsive">
											<section>
											
												<table  id="tabla_producto_color" class="display table table-striped table-bordered table-responsive " cellspacing="0">

												</table>



											</section>

										</div>

												
													<br/>

														<div class="row bloque_totales">						
															<div class="col-sm-3 col-md-6">	
															</div>		

															<div class="col-sm-3 col-md-2">	
															  <b>Cantidad disponible:</b>			
															</div>		
															
															<div class="col-sm-3 col-md-2">	
																<span id="metro_disp"></span>			
															</div>	

															<div class="col-sm-3 col-md-2">	
																<span id="kilogramo_disp" ></span>				
															</div>	



														

														</div>


														<div class="row bloque_totales">						
															<div class="col-sm-3 col-md-6">	
															</div>		
														
															<div class="col-sm-3 col-md-2">	
															  <b>Cantidad seleccionada:</b>			
															</div>		


															<div class="col-sm-3 col-md-2">	
																<span id="metro_nodisp"></span>			
															</div>	

															<div class="col-sm-3 col-md-2">	
																<span id="kilogramo_nodisp"></span>			
															</div>	

														</div>														

														<br/>

														<!-- <hr style="float:left; width:100%; "> -->
									     
										<div class="table-responsive">
											<section>
												

														<br>
														<b>Productos Apartados actualmente</b>
														<br><br><br>
												<table id="tabla_producto_color2" class="display table table-striped table-bordered table-responsive " cellspacing="0">
														
												</table>
									

											</section>
										</div>
									</div>

		</div>
</div>


 </div>





		         				<!-- pie -->
		         <div class="modal-footer">
		            <button type="button" class="btn btn-default" 
		               data-dismiss="modal">Cerrar
		            </button>
		         </div>


<?php //echo form_close(); ?>



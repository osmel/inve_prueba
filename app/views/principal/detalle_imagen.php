<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
 	if (!isset($retorno)) {
      	$retorno ="";
    }

?>

		        			<!-- Encabezado -->
		         <div class="modal-header col-xs-12 col-md-12 col-lg-12">
		            <button type="button" class="close" 
		               data-dismiss="modal" aria-hidden="true">
		                  &times;
		            </button>
		            <h4 class="modal-title" id="myModalLabel">
		               <?php echo $descripcion; ?>
		               
		            </h4>
		         </div>
		         
						         <!-- Contenido -->
		         <div class="modal-body col-xs-12 col-md-12 col-lg-12">
		         	
				         	<div class="col-xs-12 col-md-12 col-lg-12">         	
								        
						         	<?php 

				                        $fechaSegundos = time(); 
				                        $strNoCache = "?nocache=$fechaSegundos"; 

				 						$nombre_fichero ='uploads/productos/thumbnail/300X300/'.substr($imagen,0,strrpos($imagen,".")).'_thumb'.substr($imagen,strrpos($imagen,"."));
				                        if (file_exists($nombre_fichero)) {
				                            $imagen ='<img src="'.base_url().$nombre_fichero.$strNoCache.'" border="0" width="100%" height="100%">';

				                        } else {
				                            $imagen ='<img src="img/sinimagen.png" border="0" width="100%" height="100%">';
				                        }

				                        echo $imagen;

						         	?>
								        

							</div>
		 
				 </div>





		         				<!-- pie -->
		         <div class="modal-footer">
		            <button type="button" class="btn btn-default" 
		               data-dismiss="modal">Cerrar
		            </button>
		         </div>




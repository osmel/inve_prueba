jQuery(document).ready(function($) {


	var opts = {
		lines: 13, 
		length: 20, 
		width: 10, 
		radius: 30, 
		corners: 1, 
		rotate: 0, 
		direction: 1, 
		color: '#E8192C',
		speed: 1, 
		trail: 60,
		shadow: false,
		hwaccel: false,
		className: 'spinner',
		zIndex: 2e9, 
		top: '50%', // Top position relative to parent
		left: '50%' // Left position relative to parent		
	};
var target = document.getElementById('foo');


/////////////////////////////////imprimir los detalles/////////////////////////////////////////////////////////////


jQuery('body').on('click','#impresion_reporte_compra', function (e) {
	  	       busqueda = jQuery('input[type=search]').val();
	   		     modulo = jQuery("#modulo").val(); 
    		 id_almacen = jQuery("#id_almacen_compra").val(); 
     		 movimiento = jQuery("#movimiento").val(); 
    abrir('POST', '/impresion_reporte_compra', {
    			busqueda: busqueda,
			      modulo: modulo,
			  id_almacen: id_almacen,
			  movimiento: movimiento,
    }, '_blank' );
});





jQuery('body').on('click','#exportar_reportes_compra', function (e) {
 busqueda = jQuery('input[type=search]').val();
	   		     modulo = jQuery("#modulo").val(); 
    		 id_almacen = jQuery("#id_almacen_compra").val(); 
     		 movimiento = jQuery("#movimiento").val(); 


    abrir('POST', '/exportar_reportes_compra', {
    			busqueda: busqueda,
			      modulo: modulo,
			  id_almacen: id_almacen,
			  movimiento: movimiento,
    }, '_blank' );
});


//////////////////////////////////////////////////////////////////////////////////////////////

jQuery('body').on('click','#proc_aprobado', function (e) {

	jQuery('#foo').css('display','block');
	var spinner = new Spinner(opts).spin(target);

	   var retorno = jQuery("#retorno").val();
	var movimiento = jQuery("#movimiento").val();
	
	 var url = '/proc_pedido_aprobado';
   

	jQuery.ajax({
		        url : url,
		        type : 'POST',
		       	data : { 
		        	movimiento:movimiento,
		        },
		        dataType : 'json',
		        success : function(data) {	
						if(data.exito != true){
								spinner.stop();
								jQuery('#foo').css('display','none');
								jQuery('#messages').css('display','block');
								jQuery('#messages').addClass('alert-danger');
								jQuery('#messages').html(data.error);
								jQuery('html,body').animate({
									'scrollTop': jQuery('#messages').offset().top
								}, 1000);
						}else{

							spinner.stop();
							jQuery('#foo').css('display','none');
								jQuery.ajax({
									        url : '/conteo_tienda',
									        data : { 
									        	tipo: 'tienda',
									        },
									        type : 'POST',
									        dataType : 'json',
									        success : function(dato) {	
									        	MY_Socket.sendNewPost(dato.vendedor+' - '+dato.tienda,'proc_aprobado');
												window.location.href = retorno;	

									        }
								});	
						}
		        }

		        
	});						        
});


////////////////////////////Procesar pedido cambio////////////////////////////

jQuery('body').on('click','#proc_pedido_cambio', function (e) {

	jQuery('#foo').css('display','block');
	var spinner = new Spinner(opts).spin(target);

	id_almacen = jQuery('#id_almacen_compra').val();
	factura = jQuery("#factura").val();
	movimiento = jQuery("#movimiento").val();
	var retorno = jQuery("#retorno").val();
	var modulo = jQuery("#modulo").val();
	
	var comentario = jQuery("#comentario").val();
	
	
	 var url = '/proc_pedido_cambio';

	    var arreglo_cant_aprobada = [];
	    var arreglo_cant_solicitada = [];
	    var arreglo = {};

	   jQuery("#tabla_revisa_pedido_compra tbody tr td input.cant_aprobada").each(function(e) { //cant_solicitada
	   		arreglo = {};
	   		arreglo["id"] = jQuery(this).attr('identificador') ;  
	   		arreglo['cantidad'] = jQuery(this).val();
	   		//alert(arreglo['cantidad']);
	   		arreglo_cant_aprobada.push( arreglo);
	   });

	   jQuery("#tabla_revisa_pedido_compra tbody tr td input.cant_solicitada").each(function(e) { //cant_solicitada
	   		arreglo = {};
	   		arreglo["id"] = jQuery(this).attr('identificador') ;  
	   		arreglo['cantidad'] = jQuery(this).val();
	   		arreglo_cant_solicitada.push( arreglo);
	   });


	jQuery.ajax({
		        url : url,
		        type : 'POST',
		       	data : { 
		        	arreglo_cant_aprobada:arreglo_cant_aprobada,
		        	arreglo_cant_solicitada: arreglo_cant_solicitada,
		        	id_almacen:id_almacen,		
		        	factura:factura,
		        	movimiento: movimiento,
		        	modulo:modulo,
		        	comentario:comentario	
		        },
		        dataType : 'json',
		        success : function(data) {	
						if(data.exito != true){
								spinner.stop();
								jQuery('#foo').css('display','none');
								jQuery('#messages').css('display','block');
								jQuery('#messages').addClass('alert-danger');
								jQuery('#messages').html(data.error);
								jQuery('#messages').append(data.error);
								jQuery('html,body').animate({
									'scrollTop': jQuery('#messages').offset().top
								}, 1000);
						}else{

							spinner.stop();
							jQuery('#foo').css('display','none');

								jQuery.ajax({
									        url : '/conteo_tienda',
									        data : { 
									        	tipo: 'tienda',
									        },
									        type : 'POST',
									        dataType : 'json',
									        success : function(dato) {	
									        	MY_Socket.sendNewPost(dato.vendedor+' - '+dato.tienda,'proc_pedido_compra');
									        	

												//window.location.href = retorno;	

									        	
												valor= jQuery.base64.encode(data.aprobado);
												var url = "/pedido_compra_modal/"+valor+'/'+jQuery.base64.encode(movimiento)+'/'+jQuery.base64.encode(modulo)+'/'+jQuery.base64.encode(retorno);
											
												jQuery('#modalMessage').modal({
													  show:'true',
													remote:url,
												}); 									        	
												
												
									        }
								});	


						}
		        }

		        
	});						        
});


jQuery('body').on('click','#deleteUserSubmit[name="procesando_confirmar_pedido"]', function (e) {
			jQuery.ajax({
						        url : 'conteo_tienda',
						        data : { 
						        	tipo: 'tienda',
						        } ,
						        type : 'POST',
						        dataType : 'json',
						        success : function(data) {	
						        	MY_Socket.sendNewPost(data.vendedor+' - '+data.tienda,'proc_confirmar_pedido');
									return false;	
						        }
			});	
});

////////////////////////////Cancelar pedido de compra////////////////////////////

jQuery('body').on('submit','#form_cancelar_pedido_compra', function (e) {


		jQuery('#foo').css('display','block');
		var spinner = new Spinner(opts).spin(target);
		jQuery(this).ajaxSubmit({
			success: function(data){
				if(data != true){
					
					spinner.stop();
					jQuery('#foo').css('display','none');
					jQuery('#messages').css('display','block');
					jQuery('#messages').addClass('alert-danger');
					jQuery('#messages').html(data);
					jQuery('html,body').animate({
						'scrollTop': jQuery('#messages').offset().top
					}, 1000);
				

				}else{
					    $catalogo = e.target.name;
					    
						spinner.stop();
						jQuery('#foo').css('display','none');



								jQuery.ajax({
									        url : 'conteo_tienda',
									        data : { 
									        	tipo: 'tienda',
									        },
									        type : 'POST',
									        dataType : 'json',
									        success : function(data) {	
									        	MY_Socket.sendNewPost(data.vendedor+' - '+data.tienda,'form_pedido_compra');
									        	//alert('este');
									        	window.location.href = $catalogo;	
									        }
								});		



						//window.location.href = '/'+$catalogo;	
				}
			} 
		});
		return false;
	});	

/////////////////////////////////////////////////Revisar de pedido de compra/////////////////////////////////////////////////////////////////


jQuery('#tabla_revisa_pedido_compra').dataTable( {
 	"processing": true, //	//tratamiento con base de datos
	"serverSide": true,
	"ajax": {
            	"url" : "/procesando_revisar_pedido_compra",
         		"type": "POST",
         		"data": function ( d ) {
         			
         				
    				   //datos del producto
    				   d.modulo = jQuery("#modulo").val(); 
    				   d.id_almacen = jQuery("#id_almacen_compra").val(); 
     				   d.movimiento = jQuery("#movimiento").val(); 
    				   
    			 	}	
     }, 
	"language": {  //tratamiento de lenguaje
		"lengthMenu": "Mostrar _MENU_ registros por página",
		"zeroRecords": "No hay registros",
		"info": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
		"infoEmpty": "No hay registros disponibles",
		"infoFiltered": "(Mostrando _TOTAL_ de _MAX_ registros totales)",  
		"emptyTable":     "No hay registros",
		"infoPostFix":    "",
		"thousands":      ",",
		"loadingRecords": "Leyendo...",
		"processing":     "Procesando...",
		"search":         "Buscar:",
		"paginate": {
			"first":      "Primero",
			"last":       "Último",
			"next":       "Siguiente",
			"previous":   "Anterior"
		},
		"aria": {
			"sortAscending":  ": Activando para ordenar columnas ascendentes",
			"sortDescending": ": Activando para ordenar columnas descendentes"
		},
	},

   "rowCallback": function( row, data ) {
	    // Bold the grade for all 'A' grade browsers
	    if ( (data[11] == 0) && ( data[12] == 0)) {
	      jQuery('td', row).addClass( "danger" );
	    }


	


	  },		

	"infoCallback": function( settings, start, end, max, total, pre ) {
		
		if (settings.json.totales_importe) {
			jQuery('#total_total2').html('Total:'+ number_format(settings.json.totales_importe.total, 2, '.', ','));

		} else {

			jQuery('#total_total2').html('Total: 0.00');

		}			


		if (settings.json.totales_importe) {
				jQuery("#disa_reportes").attr('disabled', false);					
			} else {
				jQuery("#disa_reportes").attr('disabled', true);					
		}


	    return pre
  	} ,  	


	"footerCallback": function( tfoot, data, start, end, display ) {
	   var api = this.api(), data;
	   
			var intVal = function ( i ) {
				return typeof i === 'string' ?
					i.replace(/[\$,]/g, '')*1 :
					typeof i === 'number' ?
						i : 0;
			};

		if  (data.length>0) {   
				//importe https://datatables.net/reference/api/
				filas = api.rows().nodes();
				total_precio=0;
				$( filas ).each(function(e) {
				  	total_precio +=(jQuery(this).find('.cant_solicitada').val())*(api.column(6).data()[e]);
				  	//console.log(total_precio);
				});

				//importes
				jQuery('#total2').html('Total:'+ number_format(total_precio, 2, '.', ','));						

		} else 	{
					//importes
					jQuery('#total2').html('Total: 0.00');										

		}	
    },  	
    
	"columnDefs": [

				{ 
		                "render": function ( data, type, row ) {
		                		if (row[8]!='') {
		                			return row[0]+'<br/><b style="color:red;">Cód: </b>'+row[8];	
		                		} else {
		                			return row[0];
		                		}
		                		
		                },
		                "targets": [0]   //el 3 es la imagen q ya viene formada desde el modelo
		        },

	    		{ 
	                "render": function ( data, type, row ) {
	                		return data;
	                },
	                "targets": [1,2,3,4,5,6,7]
	            },

				        


				{
	                "render": function ( data, type, row ) {
						
						modulo= jQuery("#modulo").val(); 
						habilitar = ((modulo == 2) ? '': 'disabled'); //solo en almacen esta deshabilitado

						texto='<td>'; 
						  texto+='<fieldset '+habilitar+'>'; 
							texto+='<input restriccion="entero"  identificador="'+row[9]+'" value="'+row[11]+'" type="text" class="form-control ttip pedido_compra cant_solicitada" title="Números enteros."  placeholder="entero">';							
						  texto+='</fieldset>'; 
						texto+='</td>';
						return texto;	

	                },
	                "targets": 8
	            },	            
    			
				{
	                "render": function ( data, type, row ) {
						
						modulo= jQuery("#modulo").val(); 

						habilitar = ((modulo == 1) ? '': 'disabled'); //solo en admin esta deshabilitado

						texto='<td>'; 

						texto+='<fieldset '+habilitar+'>'; 
							texto+='<input restriccion="entero"  identificador="'+row[9]+'" value="'+row[12]+'" type="text" class="form-control ttip pedido_compra cant_aprobada" title="Números enteros."  placeholder="entero">';							
						texto+='</fieldset>'; 
						texto+='</td>';
						return texto;	

	                },
	                "targets": 9
	            },	            
    			

	          /*
	            {
	                "render": function ( data, type, row ) {
						texto='<td><button'; 
							texto+='type="button" identificador="'+row[9]+'" class="btn btn-danger btn-block quitar_compra">'; 
							 texto+='Quitar';
						texto+='</button></td>';
						return texto;	

	                },
	                "targets": 9
	            },
	            */


	        ],
});	

/////////////////////////////////////////////////Status de pedido de compra/////////////////////////////////////////////////////////////////


jQuery('#tabla_pedido_compra').dataTable( {
 	"processing": true, //	//tratamiento con base de datos
	"serverSide": true,
	"ajax": {
            	"url" : "procesando_pedido_compra",
         		"type": "POST",
         		"data": function ( d ) {
						var fecha = (jQuery('.fecha_historicos').val()).split(' / ');
						d.fecha_inicial = fecha[0];
						d.fecha_final = fecha[1];	
					    d.id_almacen = jQuery("#id_almacen_historicos").val();   
					    d.modulo = jQuery("#modulo").val();   

    			 	}	
     }, 
	"language": {  //tratamiento de lenguaje
		"lengthMenu": "Mostrar _MENU_ registros por página",
		"zeroRecords": "No hay registros",
		"info": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
		"infoEmpty": "No hay registros disponibles",
		"infoFiltered": "(Mostrando _TOTAL_ de _MAX_ registros totales)",  
		"emptyTable":     "No hay registros",
		"infoPostFix":    "",
		"thousands":      ",",
		"loadingRecords": "Leyendo...",
		"processing":     "Procesando...",
		"search":         "Buscar:",
		"paginate": {
			"first":      "Primero",
			"last":       "Último",
			"next":       "Siguiente",
			"previous":   "Anterior"
		},
		"aria": {
			"sortAscending":  ": Activando para ordenar columnas ascendentes",
			"sortDescending": ": Activando para ordenar columnas descendentes"
		},
	},

	"infoCallback": function( settings, start, end, max, total, pre ) {
		
		if (settings.json.totales_importe) {
			jQuery('#total_total').html('Total:'+ number_format(settings.json.totales_importe.total, 2, '.', ','));

		} else {

			jQuery('#total_total').html('Total: 0.00');

		}			

	    return pre
  	} ,  	


	"footerCallback": function( tfoot, data, start, end, display ) {
	   var api = this.api(), data;
	   
			var intVal = function ( i ) {
				return typeof i === 'string' ?
					i.replace(/[\$,]/g, '')*1 :
					typeof i === 'number' ?
						i : 0;
			};

		if  (data.length>0) {   

				//invisible columna de revisar para el caso de cancelar						
				if ( (jQuery("#modulo").val() == 4) || (jQuery("#modulo").val() == 5) || (jQuery("#modulo").val() == 3) ) {
					api.column(9).visible(false);	
				}

				//si eres administrador			    
				if ( (jQuery("#mi_perfil").val() == '1') ) {

					switch(jQuery("#modulo").val()) {
					    case '2':
					    case '3':
						    	//api.column(8).visible(false);
						    	//api.column(9).visible(false);
					        break;
					    default:
			              break;
					}					
				}


				//si eres almacenista		
				//alert(jQuery("#modulo").val());	    
				if ( (jQuery("#mi_perfil").val() != '1') ) {


					switch(jQuery("#modulo").val()) {
					    case '1':
						    	api.column(8).visible(false);
						    	api.column(9).visible(false);
					        break;
					    default:
			              break;
					}					
				}



				//importe
				
				total_precio = api
					.column( 6 )
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					} );				



					//importes
					jQuery('#total').html('Total:'+ number_format(total_precio, 2, '.', ','));						


		} else 	{
					//importes
					jQuery('#total').html('Total: 0.00');										

		}	
    },  	
    
    
	"columnDefs": [

	    		{ 
	                "render": function ( data, type, row ) {
	                		return data;
	                },
	                "targets": [0,1,2,3,4,5,6,7]
	            },
    			
  				{
	                "render": function ( data, type, row ) {
	                	if ( (row[8]==4) || (row[8]==5)) {
	                		icono='eye-open';  	
	                	} else {
	                		icono='edit';	
	                	}
	                	
	                	

						texto='<td>';
							texto+='<a href="detalle_revision/'+jQuery.base64.encode(row[0])+'/'+jQuery.base64.encode(row[8])+ '"'; 
							texto+=' class="btn btn-warning btn-sm btn-block" >';
								texto+=' <span class="glyphicon glyphicon-'+icono+'"></span>';
							texto+=' </a>';
						texto+='</td>';


						return texto;	
	                },
	                "targets": 8
	            },
  				{
	                "render": function ( data, type, row ) {
							texto='<td>';
	 							texto+='<a href="cancelar_pedido_compra/'+jQuery.base64.encode(row[0])+'/'+jQuery.base64.encode(row[8])+ '"'; 
										texto+='class="btn btn-danger btn-sm btn-block" data-toggle="modal" data-target="#modalMessage"> ';
										texto+='<span class="glyphicon glyphicon-remove"></span> ';
								texto+='</a>';
							texto+='</td>';


						return texto;	

	                },

	                "targets": 9
	            }	            


	        ],
});	


/////////////////////////////////////////////////pagos realizados/////////////////////////////////////////////////////////////////

jQuery('#id_almacen_compra').change(function(e) {
	var oTable =jQuery('#tabla_entrada_pedido_compra').dataTable();
	oTable._fnAjaxUpdate();		
});

jQuery("#producto_catalogo_compra, #color_catalogo_compra, #composicion_catalogo_compra, #calidad_catalogo_compra").on('change', function(e) {

		var campo = jQuery(this).attr("name");   
 		 var val_prod = jQuery('#producto_catalogo_compra option:selected').text();  		  //elemento** id
 		 var val_color = jQuery('#color_catalogo_compra').val();  		  //elemento** id
 		 var val_comp = jQuery('#composicion_catalogo_compra').val();  		  //elemento** id
 		 var val_calida = jQuery('#calidad_catalogo_compra').val();  		  //elemento** id


         var dependencia = jQuery(this).attr("dependencia"); //color composicion
         var nombre = jQuery(this).attr("nombre");           //color composicion
        
    	if (dependencia !="") {	    
	        //limpiar la dependencia
	        jQuery("#"+dependencia).html(''); 
	        //cargar la dependencia
	        cargarDependencia_catalogo_compra(campo,val_prod,val_color,val_comp,val_calida,dependencia,nombre);
        }


		var hash_url = window.location.pathname;


		//if  ( (hash_url=="/nuevo_pedido_compra") )   
		{  

				//comienzo=true; //para indicar que start comience en 0;
				var oTable =jQuery('#tabla_entrada_pedido_compra').dataTable();
				oTable._fnAjaxUpdate();
    	}	



     });




	function cargarDependencia_catalogo_compra(campo,val_prod,val_color,val_comp,val_calida,dependencia,nombre) {
		
		var url = '/cargar_dependencia_compra';	

		jQuery.ajax({
		        url : '/cargar_dependencia_compra',
		        data:{
		        	campo:campo,
		        	
		        	val_prod:val_prod,
		        	val_color:val_color,
		        	val_comp:val_comp,
		        	val_calida:val_calida,

		        	dependencia:dependencia
		        },


		        type : 'POST',
		        dataType : 'json',
		        success : function(data) {
		        		


	                 jQuery("#"+dependencia).append('<option value="0" >Seleccione '+nombre+'</option>');
             	     

					if (data != "[]") {
						
                        jQuery.each(data, function (i, valor) {
                            if (valor.nombre !== null) {
                                 jQuery("#"+dependencia).append('<option value="' + valor.identificador + '" style="background-color:#'+valor.hexadecimal_color+' !important;" >' + valor.nombre + '</option>');     
                            }
                        });

	                } 	

					
				
					jQuery("#"+dependencia).trigger('change');

                    return false;
		        },
		        error : function(jqXHR, status, error) {
		        },
		        complete : function(jqXHR, status) {
		            
		        }
		    }); 
	}


jQuery('#tabla_entrada_pedido_compra').dataTable( {
 	"processing": true, //	//tratamiento con base de datos
	"serverSide": true,
	"ajax": {
            	"url" : "/procesando_entrada_pedido_compra",
         		"type": "POST",
         		"data": function ( d ) {
         			/*	
         		 	   if (comienzo) {
         		 	   	 d.start=0;	 //comienza en cero siempre q cambia de botones
         		 	   	 d.draw =0;
         		 	   	
         		 	   }
         		 	   comienzo = false;
         		 	   
         		 	   d.comenzar = comenzar;
         		 	   comenzar = false;
         		 	   */

    				   //datos del producto
    				   d.id_almacen = jQuery("#id_almacen_compra").val(); 
     				   d.id_descripcion = jQuery("#producto_catalogo_compra").val(); 
     				   if (d.id_descripcion !='') {
     				   	  d.id_descripcion = jQuery('#producto_catalogo_compra option:selected').text();
     				   }

     				   d.id_color = jQuery("#color_catalogo_compra").val(); 
     				   d.id_composicion = jQuery("#composicion_catalogo_compra").val(); 
     				   d.id_calidad = jQuery("#calidad_catalogo_compra").val(); 
    				   
    			 	}	
     }, 
	"language": {  //tratamiento de lenguaje
		"lengthMenu": "Mostrar _MENU_ registros por página",
		"zeroRecords": "No hay registros",
		"info": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
		"infoEmpty": "No hay registros disponibles",
		"infoFiltered": "(Mostrando _TOTAL_ de _MAX_ registros totales)",  
		"emptyTable":     "No hay registros",
		"infoPostFix":    "",
		"thousands":      ",",
		"loadingRecords": "Leyendo...",
		"processing":     "Procesando...",
		"search":         "Buscar:",
		"paginate": {
			"first":      "Primero",
			"last":       "Último",
			"next":       "Siguiente",
			"previous":   "Anterior"
		},
		"aria": {
			"sortAscending":  ": Activando para ordenar columnas ascendentes",
			"sortDescending": ": Activando para ordenar columnas descendentes"
		},
	},

	"infoCallback": function( settings, start, end, max, total, pre ) {
		
		if (settings.json.totales_importe) {
			jQuery('#total_total').html('Total:'+ number_format(settings.json.totales_importe.importe, 2, '.', ','));

		} else {

			jQuery('#total_total').html('Total: 0.00');

		}			

	    return pre
  	} ,  	


	"footerCallback": function( tfoot, data, start, end, display ) {
	   var api = this.api(), data;
	   
			var intVal = function ( i ) {
				return typeof i === 'string' ?
					i.replace(/[\$,]/g, '')*1 :
					typeof i === 'number' ?
						i : 0;
			};

		if  (data.length>0) {   
				
			

				//importe
				
				total_precio = api
					.column( 6 )
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					} );				



					//importes
					jQuery('#total').html('Total:'+ number_format(total_precio, 2, '.', ','));						


		} else 	{
					//importes
					jQuery('#total').html('Total: 0.00');										

		}	
    },  	
    
	"columnDefs": [

				{ 
		                "render": function ( data, type, row ) {
		                		if (row[8]!='') {
		                			return row[0]+'<br/><b style="color:red;">Cód: </b>'+row[8];	
		                		} else {
		                			return row[0];
		                		}
		                		
		                },
		                "targets": [0]   //el 3 es la imagen q ya viene formada desde el modelo
		        },

	    		{ 
	                "render": function ( data, type, row ) {
	                		return data;
	                },
	                "targets": [1,2,3,4,5,6,7]
	            },
    			
  				{
	                "render": function ( data, type, row ) {
						texto='<td><button '; 
							texto+='type="button" class="btn btn-success btn-block agregar_compra '+row[9]+'" identificador="'+row[9]+'" >';
							texto+='<span  class="">Agregar</span>';
						texto+='</button></td>';
						return texto;	
	                },
	                "targets": 8
	            },

	        ],
});	


//Agregar las estradas a salidas en el modulo de salida "agregar la regilla de arriba a la regilla inferior"
jQuery('table').on('click','.agregar_compra', function (e) {
	jQuery(this).attr('disabled', true);		

	
	identificador = (jQuery(this).attr('identificador'));
	movimiento = jQuery("#movimiento").val();
	factura = jQuery("#factura").val();
	comentario = jQuery("#comentario").val();
	id_almacen = jQuery("#id_almacen").val();
	

	//editar_proveedor
	jQuery('#foo').css('display','block');
	var spinner = new Spinner(opts).spin(target);

 
	jQuery.ajax({
		        url : '/agregar_salida_compra',
		        data : { 
		        	identificador: identificador,
		        	movimiento: movimiento,
		        	factura: factura,
		        	comentario: comentario,
		        	id_almacen: id_almacen,
		        },
		        type : 'POST',
		       // dataType : 'json',
		        success : function(data) {	
						if(data != true){
							//alert('sad');
								spinner.stop();
								jQuery('#foo').css('display','none');
								jQuery('#messages').css('display','block');
								jQuery('#messages').addClass('alert-danger');
								jQuery('#messages').html(data);
								jQuery('html,body').animate({
									'scrollTop': jQuery('#messages').offset().top
								}, 1000);


							//aqui es donde va el mensaje q no se ha copiado
						}else{
							
							spinner.stop();
							jQuery('#foo').css('display','none');
						    jQuery('#messages').css('display','none');

							jQuery("fieldset.disabledme").attr('disabled', true);

							jQuery('#tabla_entrada_pedido_compra').dataTable().fnDraw();
							jQuery('#tabla_salida_pedido_compra').dataTable().fnDraw();


								jQuery.ajax({
									        url : 'conteo_tienda',
									        data : { 
									        	tipo: 'tienda',
									        },
									        type : 'POST',
									        dataType : 'json',
									        success : function(data) {	
									        	MY_Socket.sendNewPost(data.vendedor+' - '+data.tienda,'agregar_compra');
									        	return false;	
									        }
								});	

						}
		        }
	});		
	jQuery(this).attr('disabled', false);				        

});



/////////////////////////////////////////////////pagos realizados/////////////////////////////////////////////////////////////////



jQuery('#tabla_salida_pedido_compra').dataTable( {
 	"processing": true, //	//tratamiento con base de datos
	"serverSide": true,

	scroller:       true,
    scrollY:        250,
    scrollCollapse: false,
  /*initComplete: function ()
  	 { var api = this.api();
  	    api.scroller().scrollToRow( 5 );
  	     },*/




	"ajax": {
            	"url" : "/procesando_salida_pedido_compra",
         		"type": "POST",
         		"data": function ( d ) {
         			

    				   //datos del producto
    				   d.id_almacen = jQuery("#id_almacen_compra").val(); 
     				   d.id_descripcion = jQuery("#producto_catalogo_compra").val(); 
     				   if (d.id_descripcion !='') {
     				   	  d.id_descripcion = jQuery('#producto_catalogo_compra option:selected').text();
     				   }

     				   d.id_color = jQuery("#color_catalogo_compra").val(); 
     				   d.id_composicion = jQuery("#composicion_catalogo_compra").val(); 
     				   d.id_calidad = jQuery("#calidad_catalogo_compra").val(); 
    				   
    			 	}	
     }, 
	"language": {  //tratamiento de lenguaje
		"lengthMenu": "Mostrar _MENU_ registros por página",
		"zeroRecords": "No hay registros",
		"info": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
		"infoEmpty": "No hay registros disponibles",
		"infoFiltered": "(Mostrando _TOTAL_ de _MAX_ registros totales)",  
		"emptyTable":     "No hay registros",
		"infoPostFix":    "",
		"thousands":      ",",
		"loadingRecords": "Leyendo...",
		"processing":     "Procesando...",
		"search":         "Buscar:",
		"paginate": {
			"first":      "Primero",
			"last":       "Último",
			"next":       "Siguiente",
			"previous":   "Anterior"
		},
		"aria": {
			"sortAscending":  ": Activando para ordenar columnas ascendentes",
			"sortDescending": ": Activando para ordenar columnas descendentes"
		},
	},

	"infoCallback": function( settings, start, end, max, total, pre ) {
		/*
		console.log(settings.oScroll.sY);
		settings.oScroll.sY= 500;

		var oTable =jQuery('#tabla_salida_pedido_compra').dataTable();
		oTable._fnAjaxUpdate();
		*/

		if (settings.json.importe) {
			jQuery('#total_total2').html('Total:'+ number_format(settings.json.importe, 2, '.', ','));

		} else {

			jQuery('#total_total2').html('Total: 0.00');

		}			

	    return pre
  	} ,  	


	"footerCallback": function( tfoot, data, start, end, display ) {
	   var api = this.api(), data;
	   
			var intVal = function ( i ) {
				return typeof i === 'string' ?
					i.replace(/[\$,]/g, '')*1 :
					typeof i === 'number' ?
						i : 0;
			};

		if  (data.length>0) {   
				
			

				//importe
				
				total_precio = api
					.column( 6 )
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					} );				



					//importes
					jQuery('#total2').html('Total:'+ number_format(total_precio, 2, '.', ','));						


		} else 	{
					//importes
					jQuery('#total2').html('Total: 0.00');										

		}	
    },  	
    
	"columnDefs": [

				{ 
		                "render": function ( data, type, row ) {
		                		if (row[8]!='') {
		                			return row[0]+'<br/><b style="color:red;">Cód: </b>'+row[8];	
		                		} else {
		                			return row[0];
		                		}
		                		
		                },
		                "targets": [0]   //el 3 es la imagen q ya viene formada desde el modelo
		        },

	    		{ 
	                "render": function ( data, type, row ) {
	                		return data;
	                },
	                "targets": [1,2,3,4,5,6,7]
	            },

				{
	                "render": function ( data, type, row ) {
						texto='<td>'; 
							texto+='<input restriccion="entero" value="'+row[11]+'" identificador="'+row[9]+'" type="text" class="form-control ttip pedido_compra" title="Números enteros."  placeholder="entero">';							
						texto+='</td>';
						return texto;	

	                },
	                "targets": 8
	            },	            
    			

	            {
	                "render": function ( data, type, row ) {
						texto='<td><button'; 
							texto+='type="button" identificador="'+row[9]+'" class="btn btn-danger btn-block quitar_compra">'; 
							 texto+='Quitar';
						texto+='</button></td>';
						return texto;	

	                },
	                "targets": 9
	            },


	        ],
});	

//jQuery('.pedido_compra[restriccion="entero"]').bind('keypress paste', function (event) {
jQuery('body').on('keypress paste','.pedido_compra[restriccion="entero"]', function (event) {	
    var regex = new RegExp("^[0-9]+$");
    var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
    if (!regex.test(key)) {
       event.preventDefault();
       return false;
    }
});


jQuery('table').on('click','.quitar_compra', function (e) {

	jQuery(this).attr('disabled', true);				        
	
	identificador = (jQuery(this).attr('identificador'));
	

	jQuery.ajax({
		        url : '/quitar_salida_compra', //
		        data : { 
		        	identificador: identificador,
		        },
		        type : 'POST',
		        dataType : 'json',
		        success : function(data) {	
						if(data.exito != true){
							//aqui es donde va el mensaje q no se ha copiado
						}else{
							if(data.val_compra == false){
								jQuery("fieldset.disabledme").attr('disabled', false);
							}	
								jQuery('#tabla_entrada_pedido_compra').dataTable().fnDraw();
								jQuery('#tabla_salida_pedido_compra').dataTable().fnDraw();

								jQuery.ajax({
									        url : 'conteo_tienda',
									        data : { 
									        	tipo: 'tienda',
									        },
									        type : 'POST',
									        dataType : 'json',
									        success : function(data) {	
									        	MY_Socket.sendNewPost(data.vendedor+' - '+data.tienda,'quitar_compra');
									     		return false;
									        }
								});	

							//return false;
						}
		        }
	});	

       	jQuery(this).attr('disabled', false);				        

});





jQuery('body').on('click','#proc_pedido_compra', function (e) {

	jQuery('#foo').css('display','block');
	var spinner = new Spinner(opts).spin(target);

	id_almacen = jQuery('#id_almacen_compra').val();
	factura = jQuery("#factura").val();
	var retorno = jQuery("#retorno").val();
	
	 var url = '/proc_pedido_compra';

	    var arreglo_pedido_compra = [];
	    var arreglo = {};

	   jQuery("#tabla_salida_pedido_compra tbody tr td input.pedido_compra").each(function(e) {
	   		arreglo = {};
	   		arreglo["id"] = jQuery(this).attr('identificador') ;  
	   		arreglo['pedido_compra'] = jQuery(this).val();
	   		arreglo_pedido_compra.push( arreglo);
	   });

	jQuery.ajax({
		        url : url,
		        type : 'POST',
		       	data : { 
		        	arreglo_pedido_compra:arreglo_pedido_compra,
		        	id_almacen:id_almacen,		
		        	factura:factura        	
		        },
		        dataType : 'json',
		        success : function(data) {	
						if(data.exito != true){
								spinner.stop();
								jQuery('#foo').css('display','none');
								jQuery('#messages').css('display','block');
								jQuery('#messages').addClass('alert-danger');
								jQuery('#messages').html(data.error);
								jQuery('#messages').append(data.errores);
								jQuery('html,body').animate({
									'scrollTop': jQuery('#messages').offset().top
								}, 1000);
						}else{

							spinner.stop();
							jQuery('#foo').css('display','none');



								jQuery.ajax({
									        url : '/conteo_tienda',
									        data : { 
									        	tipo: 'tienda',
									        },
									        type : 'POST',
									        dataType : 'json',
									        success : function(dato) {	
									        	MY_Socket.sendNewPost(dato.vendedor+' - '+dato.tienda,'proc_pedido_compra');

									        	//$catalogo = 'pedido_compra';
												window.location.href = retorno;	

									        	/*
												valor= jQuery.base64.encode(data.valor);
												var url = "pro_salida/"+valor+'/'+data.id_cliente+'/'+jQuery.base64.encode(id_almacen)+'/'+jQuery.base64.encode(id_tipo_pedido)+'/'+jQuery.base64.encode(id_tipo_factura);
											
												jQuery('#modalMessage').modal({
													  show:'true',
													remote:url,
												}); 									        	
												*/
												
									        }
								});	


						}
		        }

		        
	});						        
});


////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////FIN DE PEDIDO DE COMPRA///////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////


    jQuery('body').on('submit','#form_pago', function (e) {
            
           
			jQuery('#foo').css('display','block');
			var spinner = new Spinner(opts).spin(target);
			
			jQuery(this).ajaxSubmit({
				success: function(data){
					if(data != true){
						
						spinner.stop();
						jQuery('#foo').css('display','none');
						jQuery('#messages').css('display','block');
						jQuery('#messages').addClass('alert-danger');
						jQuery('#messages').html(data);
						jQuery('html,body').animate({
							'scrollTop': jQuery('#messages').offset().top
						}, 1000);
					

					}else{
						    
						    $catalogo = e.target.name;
							spinner.stop();
							jQuery('#foo').css('display','none');
							window.location.href = '/'+$catalogo;	
							return false;
					}
				} 
			});
			return false;
	});	







	jQuery('#tabla_pagos_realizados').dataTable( {
	
	  "pagingType": "full_numbers",
		
		"processing": true,
		"serverSide": true,
		"ajax": {
	            	"url" : "/procesando_pagos_realizados",
	         		"type": "POST",
	         		 "data": function ( d ) {
	         		 	d.id_operacion=1;
	         		 	d.movimiento=jQuery("#movimiento").val();
	         		 }
	    },   
		"language": {  //tratamiento de lenguaje
			"lengthMenu": "Mostrar _MENU_ registros por página",
			"zeroRecords": "No hay registros",
			"info": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
			"infoEmpty": "No hay registros disponibles",
			"infoFiltered": "(Mostrando _TOTAL_ de _MAX_ registros totales)",  
			"emptyTable":     "No hay registros",
			"infoPostFix":    "",
			"thousands":      ",",
			"loadingRecords": "Leyendo...",
			"processing":     "Procesando...",
			"search":         "Buscar:",
			"paginate": {
				"first":      "Primero",
				"last":       "Último",
				"next":       "Siguiente",
				"previous":   "Anterior"
			},
			"aria": {
				"sortAscending":  ": Activando para ordenar columnas ascendentes",
				"sortDescending": ": Activando para ordenar columnas descendentes"
			},
		},

 			"rowCallback": function( row, data ) {
				    if  (data[7] == 0) 					    {
				      jQuery('td', row).addClass( "danger" );
				    }
			},	

			"infoCallback": function( settings, start, end, max, total, pre ) {


			    if (settings.json.totales) {
				    jQuery('#etiq_num_mov').val(  settings.json.totales.movimiento);
					jQuery('#etiq_almacen').val( settings.json.totales.tipo_pago);
					jQuery('#etiq_proveedor').val( settings.json.totales.almacen);

					jQuery('#etiq_fecha').val( settings.json.totales.fecha);
					jQuery('#etiq_factura').val( settings.json.totales.factura);
					jQuery('#etiq_subtotal').val( settings.json.totales.subtotal);
					jQuery('#etiq_iva').val( settings.json.totales.iva);
					jQuery('#etiq_total').val( settings.json.totales.total);
					jQuery('#etiq_dia_vencido').val( settings.json.totales.dias_vencidos);
					jQuery('#etiq_monto_paga').val( settings.json.totales.monto_restante);
					jQuery('#importe_pagado').html( number_format((settings.json.totales.total-settings.json.totales.monto_restante), 2, '.', ','));
					

				} else {
				    /*
				    jQuery('#total_entrada').html( 'Total de Entradas: 0');
					jQuery('#total_salida').html( 'Total de Salidas: 0');
					jQuery('#total_devoluciones').html('Total de Devoluciones: 0');
					*/

				}	

					

			    return pre;
			  } ,



		"columnDefs": [
			    	
			    	{ 
		                "render": function ( data, type, row ) {
		                		return data;

		                },
		                "targets": [0,1,2,3,4] 
		            },

     				 {
		                "render": function ( data, type, row ) {
						if (row[6]!=0) { //si esta autorizado a eliminar
							texto='<td>';
								texto+='<a href="/editar_pago_realizado/'+jQuery.base64.encode(row[5])+'/'+jQuery.base64.encode(row[8])+'" type="button"'; 
								texto+=' class="btn btn-warning btn-sm btn-block" >';
									texto+=' <span class="glyphicon glyphicon-edit"></span>';
								texto+=' </a>';
							texto+='</td>';
						} else {
							texto='<fieldset disabled> <td>';
								texto+='<a href="#" type="button"'; 
								texto+=' class="btn btn-warning btn-sm btn-block" >';
									texto+=' <span class="glyphicon glyphicon-edit"></span>';
								texto+=' </a>';
							texto+='</td> </fieldset>';							
						}




							return texto;	
		                },
		                "targets": 5
		            },
		            {
		                "render": function ( data, type, row ) {


	                	if (row[6]!=0) { //si esta autorizado a eliminar
	                	
							texto='<td><a href="/eliminar_pago/'+jQuery.base64.encode(row[5])+'/'+jQuery.base64.encode(row[1])+'/'+jQuery.base64.encode(row[8])+'" '; 
								texto+='class="btn btn-danger  btn-block" data-toggle="modal" data-target="#modalMessage">';
								texto+='<span class="glyphicon glyphicon-remove"></span>';
							texto+='</a></td>';
						} else {


								texto='	<fieldset disabled> <td>';								
									texto+=' <a href="#"'; 
									texto+=' class="btn btn-danger btn-sm btn-block">';
									texto+=' <span class="glyphicon glyphicon-remove"></span>';
									texto+=' </a>';
								texto+=' </td></fieldset>';									

						}







							return texto;	
		                },
		                "targets": 6
		            },
  					/*
  					{ 
		                 "visible": false,
		                "targets": [9]
		            }
		            */
		          
		           
		            
		        ],
	});	










//Agregar las estradas a salidas
jQuery('body').on('click','.impresion1', function (e) {
	console.log(  jQuery(this).attr('tipo') );
	//id_estatus = jQuery("#id_estatuss").val(); 
	   //id_almacen = jQuery("#id_almacen_reporte").val(); 

});



jQuery('body').on('click','.impresion_ctas', function (e) {
  	    //busqueda      = jQuery('input[type=search]').val();
  	    busqueda      = jQuery(this).parent().parent().siblings("section").find("input[type=search]").val();
	    extra_search = jQuery(this).attr('tipo'); 
		id_operacion=1;
		var fecha = (jQuery('.fecha_historicos').val()).split(' / ');
		fecha_inicial = fecha[0];
		fecha_final = fecha[1];
	    id_almacen = jQuery("#id_almacen_historicos").val(); 
	    id_factura = jQuery("#id_factura_historicos").val(); 

    abrir('POST', 'impresion_ctasxpagar', {
    			busqueda:busqueda,
			extra_search:extra_search,
			id_operacion: id_operacion,
			
			fecha_inicial:fecha_inicial, 
			fecha_final: fecha_final,
			id_almacen:id_almacen,
			id_factura:id_factura,

    }, '_blank' );
		        
	
});


jQuery('body').on('click','.exportar_ctas', function (e) {
  	    //busqueda      = jQuery('input[type=search]').val();
  	    busqueda      = jQuery(this).parent().parent().siblings("section").find("input[type=search]").val();
	    extra_search = jQuery(this).attr('tipo'); 
		id_operacion=1;
		var fecha = (jQuery('.fecha_historicos').val()).split(' / ');
		fecha_inicial = fecha[0];
		fecha_final = fecha[1];
	    id_almacen = jQuery("#id_almacen_historicos").val(); 
	    id_factura = jQuery("#id_factura_historicos").val(); 

    abrir('POST', 'exportar_ctasxpagar', {
    			busqueda:busqueda,
			extra_search:extra_search,
			id_operacion: id_operacion,
			
			fecha_inicial:fecha_inicial, 
			fecha_final: fecha_final,
			id_almacen:id_almacen,
			id_factura:id_factura,

    }, '_blank' );
		        
	
});



/////////////////////////////////////////////////ctas por pagar/////////////////////////////////////////////////////////////////

	jQuery('#tabla_ctas_vencidas').dataTable( {
	
	  "pagingType": "full_numbers",
		
		"processing": true,
		"serverSide": true,
		"ajax": {
	            	"url" : "procesando_ctas_vencidas",
	         		"type": "POST",
	         		 "data": function ( d ) {
	         		 	d.id_operacion=1;
	         		 	
						var fecha = (jQuery('.fecha_historicos').val()).split(' / ');
						d.fecha_inicial = fecha[0];
						d.fecha_final = fecha[1];
						d.id_almacen = jQuery("#id_almacen_historicos").val(); 
					    d.id_factura = jQuery("#id_factura_historicos").val(); 	


	         		 }
	         		
	     },   



	"footerCallback": function( tfoot, data, start, end, display ) {
	   var api = this.api(), data;
			var intVal = function ( i ) {
				return typeof i === 'string' ?
					i.replace(/[\$,]/g, '')*1 :
					typeof i === 'number' ?
						i : 0;
			};
		if  (data.length>0) {   
				

			total_subtotal = api
					.column( 6)
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					} );					

				
				total_iva = api
					.column( 7)
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					} );	
				
				total = api
					.column( 8)
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					} );					


					//importes
					jQuery('#subtotal').html('SubTotal:'+ number_format(total_subtotal, 2, '.', ','));
					jQuery('#iva').html('IVA:' + number_format( total_iva, 2, '.', ','));
					jQuery('#total').html('Total:'+ number_format(total, 2, '.', ','));	

		} else 	{
					//importes
					jQuery('#subtotal').html('SubTotal: 0.00');	
					jQuery('#iva').html('IVA: 0.00');	
					jQuery('#total').html('Total: 0.00');										


		}	
    },


		"infoCallback": function( settings, start, end, max, total, pre ) {
	
			if (settings.json.totales_importe) {
			  	jQuery('#total_subtotal').html( 'SubTotal:'+number_format(settings.json.totales_importe.subtotal, 2, '.', ','));
				jQuery('#total_iva').html( 'IVA:'+number_format(settings.json.totales_importe.iva, 2, '.', ','));
				jQuery('#total_total').html('Total:'+ number_format(settings.json.totales_importe.total, 2, '.', ','));

			} else {
			    jQuery('#total_subtotal').html( 'Subtotal: 0.00');
				jQuery('#total_iva').html( 'IVA: 0.00');
				jQuery('#total_total').html('Total de mts: 0.00');

			}	


			if (settings.json.recordsTotal==0) {
					jQuery("#disa_vencidas").attr('disabled', true);					
				} else {
					jQuery("#disa_vencidas").attr('disabled', false);					
			}
			return pre
		},	     

		"language": {  //tratamiento de lenguaje
			"lengthMenu": "Mostrar _MENU_ registros por página",
			"zeroRecords": "No hay registros",
			"info": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
			"infoEmpty": "No hay registros disponibles",
			"infoFiltered": "(Mostrando _TOTAL_ de _MAX_ registros totales)",  
			"emptyTable":     "No hay registros",
			"infoPostFix":    "",
			"thousands":      ",",
			"loadingRecords": "Leyendo...",
			"processing":     "Procesando...",
			"search":         "Buscar:",
			"paginate": {
				"first":      "Primero",
				"last":       "Último",
				"next":       "Siguiente",
				"previous":   "Anterior"
			},
			"aria": {
				"sortAscending":  ": Activando para ordenar columnas ascendentes",
				"sortDescending": ": Activando para ordenar columnas descendentes"
			},
		},
	
		"columnDefs": [
			    	
			    	{ 
		                "render": function ( data, type, row ) {
		                		return data;
		                },
		                "targets": [0,1,2,3,4,5,6,7,8,9] 
		            },

     				 {
		                "render": function ( data, type, row ) {


						$otro_retorno="listado_ctasxpagar";

		        		texto='<td>';
							texto+='<a style="padding: 1px 0px 1px 0px;"';
							texto+=' href="procesar_ctasxpagar/'+jQuery.base64.encode(row[0])+'/'+jQuery.base64.encode($otro_retorno)+'"'; //
							texto+='type="button" class="btn btn-warning btn-block">';
							texto+=row[10];
							texto+='</a>';
						texto+='</td>';



							return texto;	
		                },
		                "targets": 10
		            },
		            {
		                "render": function ( data, type, row ) {


						$otro_retorno="listado_ctasxpagar";
		        		texto='<td>';
							texto+='<a style="padding: 1px 0px 1px 0px;"';
							texto+=' href="procesar_entradas/'+jQuery.base64.encode(row[0])+'/'+jQuery.base64.encode(0)+'/'+jQuery.base64.encode($otro_retorno)+'"'; 							
							texto+='type="button" class="btn btn-success btn-block">';
							texto+='Detalles';
							texto+='</a>';
						texto+='</td>';



							return texto;	
		                },
		                "targets": 11
		            },
  					/*
  					{ 
		                 "visible": false,
		                "targets": [9]
		            }
		            */
		          
		           
		            
		        ],
	});	


jQuery('#tabla_ctasxpagar').dataTable( {
	
	  "pagingType": "full_numbers",
		
		"processing": true,
		"serverSide": true,
		"ajax": {
	            	"url" : "procesando_ctasxpagar",
	         		"type": "POST",
	         		 "data": function ( d ) {
	         		 	d.id_operacion = 1;

	         		 	var fecha = (jQuery('.fecha_historicos').val()).split(' / ');
						d.fecha_inicial = fecha[0];
						d.fecha_final = fecha[1];	      
						d.id_almacen = jQuery("#id_almacen_historicos").val(); 
					    d.id_factura = jQuery("#id_factura_historicos").val(); 	   		 	
	         		 }
	         		
	     },   


		"footerCallback": function( tfoot, data, start, end, display ) {
		   var api = this.api(), data;
				var intVal = function ( i ) {
					return typeof i === 'string' ?
						i.replace(/[\$,]/g, '')*1 :
						typeof i === 'number' ?
							i : 0;
				};
			if  (data.length>0) {   
					

				total_subtotal = api
						.column( 6)
						.data()
						.reduce( function (a, b) {
							return intVal(a) + intVal(b);
						} );					

					
					total_iva = api
						.column( 7)
						.data()
						.reduce( function (a, b) {
							return intVal(a) + intVal(b);
						} );	
					
					total = api
						.column( 8)
						.data()
						.reduce( function (a, b) {
							return intVal(a) + intVal(b);
						} );					


						//importes
						jQuery('#subtotal2').html('SubTotal:'+ number_format(total_subtotal, 2, '.', ','));
						jQuery('#iva2').html('IVA:' + number_format( total_iva, 2, '.', ','));
						jQuery('#total2').html('Total:'+ number_format(total, 2, '.', ','));	

			} else 	{
						//importes
						jQuery('#subtotal2').html('SubTotal: 0.00');	
						jQuery('#iva2').html('IVA: 0.00');	
						jQuery('#total2').html('Total: 0.00');										


			}	
	    },

		"infoCallback": function( settings, start, end, max, total, pre ) {
			if (settings.json.totales_importe) {
			  	jQuery('#total_subtotal2').html( 'SubTotal:'+number_format(settings.json.totales_importe.subtotal, 2, '.', ','));
				jQuery('#total_iva2').html( 'IVA:'+number_format(settings.json.totales_importe.iva, 2, '.', ','));
				jQuery('#total_total2').html('Total:'+ number_format(settings.json.totales_importe.total, 2, '.', ','));

			} else {
			    jQuery('#total_subtotal2').html( 'Subtotal: 0.00');
				jQuery('#total_iva2').html( 'IVA: 0.00');
				jQuery('#total_total2').html('Total de mts: 0.00');

			}	


			if (settings.json.recordsTotal==0) {
					jQuery("#disa_xpagar").attr('disabled', true);					
				} else {
					jQuery("#disa_xpagar").attr('disabled', false);					
			}
			return pre
		},		     

		"language": {  //tratamiento de lenguaje
			"lengthMenu": "Mostrar _MENU_ registros por página",
			"zeroRecords": "No hay registros",
			"info": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
			"infoEmpty": "No hay registros disponibles",
			"infoFiltered": "(Mostrando _TOTAL_ de _MAX_ registros totales)",  
			"emptyTable":     "No hay registros",
			"infoPostFix":    "",
			"thousands":      ",",
			"loadingRecords": "Leyendo...",
			"processing":     "Procesando...",
			"search":         "Buscar:",
			"paginate": {
				"first":      "Primero",
				"last":       "Último",
				"next":       "Siguiente",
				"previous":   "Anterior"
			},
			"aria": {
				"sortAscending":  ": Activando para ordenar columnas ascendentes",
				"sortDescending": ": Activando para ordenar columnas descendentes"
			},
		},


		"columnDefs": [
			    	{ 
		                "render": function ( data, type, row ) {
		                		return data;
		                },
		                "targets": [0,1,2,3,4,5,6,7,8,9] 
		            },
     				 {
		                "render": function ( data, type, row ) {


						$otro_retorno="listado_ctasxpagar";
		        		texto='<td>';
							texto+='<a style="padding: 1px 0px 1px 0px;"';
							texto+=' href="procesar_ctasxpagar/'+jQuery.base64.encode(row[0])+'/'+jQuery.base64.encode($otro_retorno)+'"'; //
							texto+='type="button" class="btn btn-warning btn-block">';
							texto+=row[10];
							texto+='</a>';
						texto+='</td>';
							return texto;	
		                },
		                "targets": 10
		            },
		            {
		                "render": function ( data, type, row ) {


						$otro_retorno="listado_ctasxpagar";
		        		texto='<td>';
							texto+='<a style="padding: 1px 0px 1px 0px;"';
							texto+=' href="procesar_entradas/'+jQuery.base64.encode(row[0])+'/'+jQuery.base64.encode(0)+'/'+jQuery.base64.encode($otro_retorno)+'"'; //
							texto+='type="button" class="btn btn-success btn-block">';
							texto+='Detalles';
							texto+='</a>';
						texto+='</td>';



							return texto;	
		                },
		                "targets": 11
		            },
  					/*
  					{ 
		                 "visible": false,
		                "targets": [9]
		            }
		            */
		          
		           
		            
		        ],
	});	

jQuery('#tabla_ctas_pagadas').dataTable( {
	
	  "pagingType": "full_numbers",
		
		"processing": true,
		"serverSide": true,
		"ajax": {
	            	"url" : "procesando_ctas_pagadas",
	         		"type": "POST",
	         		 "data": function ( d ) {
	         		 	d.id_operacion=1;
						var fecha = (jQuery('.fecha_historicos').val()).split(' / ');
						d.fecha_inicial = fecha[0];
						d.fecha_final = fecha[1];	
						d.id_almacen = jQuery("#id_almacen_historicos").val(); 
					    d.id_factura = jQuery("#id_factura_historicos").val(); 	         		 	
	         		 }
	         		
	     },   

		"footerCallback": function( tfoot, data, start, end, display ) {
		   var api = this.api(), data;
				var intVal = function ( i ) {
					return typeof i === 'string' ?
						i.replace(/[\$,]/g, '')*1 :
						typeof i === 'number' ?
							i : 0;
				};
			if  (data.length>0) {   
					

				total_subtotal = api
						.column( 6)
						.data()
						.reduce( function (a, b) {
							return intVal(a) + intVal(b);
						} );					

					
					total_iva = api
						.column( 7)
						.data()
						.reduce( function (a, b) {
							return intVal(a) + intVal(b);
						} );	
					
					total = api
						.column( 8)
						.data()
						.reduce( function (a, b) {
							return intVal(a) + intVal(b);
						} );					


						//importes
						jQuery('#subtotal3').html('SubTotal:'+ number_format(total_subtotal, 2, '.', ','));
						jQuery('#iva3').html('IVA:' + number_format( total_iva, 2, '.', ','));
						jQuery('#total3').html('Total:'+ number_format(total, 2, '.', ','));	

			} else 	{
						//importes
						jQuery('#subtotal3').html('SubTotal: 0.00');	
						jQuery('#iva3').html('IVA: 0.00');	
						jQuery('#total3').html('Total: 0.00');										


			}	
	    },

		"infoCallback": function( settings, start, end, max, total, pre ) {
			if (settings.json.totales_importe) {
			  	jQuery('#total_subtotal3').html( 'SubTotal:'+number_format(settings.json.totales_importe.subtotal, 2, '.', ','));
				jQuery('#total_iva3').html( 'IVA:'+number_format(settings.json.totales_importe.iva, 2, '.', ','));
				jQuery('#total_total3').html('Total:'+ number_format(settings.json.totales_importe.total, 2, '.', ','));

			} else {
			    jQuery('#total_subtotal3').html( 'Subtotal: 0.00');
				jQuery('#total_iva3').html( 'IVA: 0.00');
				jQuery('#total_total3').html('Total de mts: 0.00');
			}				

			if (settings.json.recordsTotal==0) {
					jQuery("#disa_pagadas").attr('disabled', true);					
				} else {
					jQuery("#disa_pagadas").attr('disabled', false);					
			}
			return pre
		},		     

		"language": {  //tratamiento de lenguaje
			"lengthMenu": "Mostrar _MENU_ registros por página",
			"zeroRecords": "No hay registros",
			"info": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
			"infoEmpty": "No hay registros disponibles",
			"infoFiltered": "(Mostrando _TOTAL_ de _MAX_ registros totales)",  
			"emptyTable":     "No hay registros",
			"infoPostFix":    "",
			"thousands":      ",",
			"loadingRecords": "Leyendo...",
			"processing":     "Procesando...",
			"search":         "Buscar:",
			"paginate": {
				"first":      "Primero",
				"last":       "Último",
				"next":       "Siguiente",
				"previous":   "Anterior"
			},
			"aria": {
				"sortAscending":  ": Activando para ordenar columnas ascendentes",
				"sortDescending": ": Activando para ordenar columnas descendentes"
			},
		},


		"columnDefs": [
			    	
			    	{ 
		                "render": function ( data, type, row ) {
		                		return data;
		                },
		                "targets": [0,1,2,3,4,5,6,7,8] 
		            },

     				 {
		                "render": function ( data, type, row ) {


						$otro_retorno="listado_ctasxpagar";
		        		
						if (row[11]!=2) {
			        		texto='<td>';
								texto+='<a style="padding: 1px 0px 1px 0px;"';
								texto+=' href="procesar_ctasxpagar/'+jQuery.base64.encode(row[0])+'/'+jQuery.base64.encode($otro_retorno)+'"'; //
								texto+='type="button" class="btn btn-warning btn-block">';
								texto+="Pagado"; //"row[10];
								texto+='</a>';
							texto+='</td>';
						} else {

			        		texto='<td><fieldset disabled>';
								texto+='<a style="padding: 1px 0px 1px 0px;"';
								texto+=' href="#"'; //
								texto+='type="button" class="btn btn-warning btn-block">';
								texto+='Contado';
								texto+='</a>';
							texto+='</fieldset></td>';

						}


							return texto;	
		                },
		                "targets": 9
		            },
		            {
		                "render": function ( data, type, row ) {


						$otro_retorno="listado_ctasxpagar";
		        		texto='<td>';
							texto+='<a style="padding: 1px 0px 1px 0px;"';
							texto+=' href="procesar_entradas/'+jQuery.base64.encode(row[0])+'/'+jQuery.base64.encode(0)+'/'+jQuery.base64.encode($otro_retorno)+'"'; //
							texto+='type="button" class="btn btn-success btn-block">';
							texto+='Detalles';
							texto+='</a>';
						texto+='</td>';



							return texto;	
		                },
		                "targets": 10
		            },
  					/*
  					{ 
		                 "visible": false,
		                "targets": [9]
		            }
		            */
		          
		           
		            
		        ],
	});	




/////////////////////////////////////////////////Historico de entradas/////////////////////////////////////////////////////////////////


		jQuery('#id_almacen_historicos, #id_factura_historicos, #foco_historicos').change(function(e) {
					switch(jQuery(this).attr('vista')) {
					    case "pedido_compra":
					    	var oTable =jQuery('#tabla_pedido_compra').dataTable();
					    	oTable._fnAjaxUpdate();
					        break;

					    case "entrada":
					    	var oTable =jQuery('#tabla_historico_entrada').dataTable();
					    	oTable._fnAjaxUpdate();
					        break;
					    case "salida":
					    	var oTable =jQuery('#tabla_historico_salida').dataTable();
					    	oTable._fnAjaxUpdate();
					        break;
					    case "devolucion":
							var oTable =jQuery('#tabla_historico_devolucion').dataTable();
							oTable._fnAjaxUpdate();
					        break;

					    case "cuentas":
							var oTable =jQuery('#tabla_ctas_vencidas').dataTable();
							oTable._fnAjaxUpdate();
							var oTable =jQuery('#tabla_ctasxpagar').dataTable();
							oTable._fnAjaxUpdate();
							var oTable =jQuery('#tabla_ctas_pagadas').dataTable();
							oTable._fnAjaxUpdate();
					        break;


					    default:
					        var oTable =jQuery('#tabla_historico_entrada').dataTable();			        

			              break;
					}

		});

		jQuery('.fecha_historicos').daterangepicker(
		  	  { 
			    locale: { cancelLabel: 'Cancelar',
			    		  applyLabel: 'Aceptar',
			    		  fromLabel : 'Desde',
			    		  toLabel: 'Hasta',
			    		  monthNames : "ene._feb._mar_abr._may_jun_jul._ago_sep._oct._nov._dec.".split("_"),
			    		  daysOfWeek: "Do_Lu_Ma_Mi_Ju_Vi_Sa".split("_"),
			     } , 
			    separator: ' / ',
			    format: 'DD-MM-YYYY',
			    //startDate: fecha_hoy, //'2014/09/01',
			    //endDate: fecha_hoy //'2014/12/31'
			  }
		  );


		jQuery('.fecha_historicos').on('apply.daterangepicker', function(ev, picker) {

					switch(jQuery(this).attr('vista')) {
					    case "pedido_compra":
					    	var oTable =jQuery('#tabla_pedido_compra').dataTable();
					    	oTable._fnAjaxUpdate();
					        break;

					    case "entrada":
					    	var oTable =jQuery('#tabla_historico_entrada').dataTable();
					    	oTable._fnAjaxUpdate();
					        break;
					    case "salida":
					    	var oTable =jQuery('#tabla_historico_salida').dataTable();
					    	oTable._fnAjaxUpdate();
					        break;
					    case "devolucion":
							var oTable =jQuery('#tabla_historico_devolucion').dataTable();
							oTable._fnAjaxUpdate();
					        break;

					    case "cuentas":
							var oTable =jQuery('#tabla_ctas_vencidas').dataTable();
							oTable._fnAjaxUpdate();
							var oTable =jQuery('#tabla_ctasxpagar').dataTable();
							oTable._fnAjaxUpdate();
							var oTable =jQuery('#tabla_ctas_pagadas').dataTable();
							oTable._fnAjaxUpdate();
					        break;
					    default:
					        var oTable =jQuery('#tabla_historico_entrada').dataTable();			        

			              break;
					}

		});


	jQuery('#tabla_historico_entrada').dataTable( {
	
	  "pagingType": "full_numbers",
		
		"processing": true,
		"serverSide": true,
		"ajax": {
	            	"url" : "procesando_historico_entrada",
	         		"type": "POST",
	         		 "data": function ( d ) {
	         		 	d.id_operacion=1;

						var fecha = (jQuery('.fecha_historicos').val()).split(' / ');
						d.fecha_inicial = fecha[0];
						d.fecha_final = fecha[1];	
					    d.id_almacen = jQuery("#id_almacen_historicos").val(); 
					    d.id_factura = jQuery("#id_factura_historicos").val(); 						

	         		 }
	         		
	     },   


		"footerCallback": function( tfoot, data, start, end, display ) {
		   var api = this.api(), data;
				var intVal = function ( i ) {
					return typeof i === 'string' ?
						i.replace(/[\$,]/g, '')*1 :
						typeof i === 'number' ?
							i : 0;
				};
			if  (data.length>0) {   
					

				total_subtotal = api
						.column( 6)
						.data()
						.reduce( function (a, b) {
							return intVal(a) + intVal(b);
						} );					

					
					total_iva = api
						.column( 7)
						.data()
						.reduce( function (a, b) {
							return intVal(a) + intVal(b);
						} );	
					
					total = api
						.column( 8)
						.data()
						.reduce( function (a, b) {
							return intVal(a) + intVal(b);
						} );					


						//importes
						jQuery('#subtotal').html('SubTotal:'+ number_format(total_subtotal, 2, '.', ','));
						jQuery('#iva').html('IVA:' + number_format( total_iva, 2, '.', ','));
						jQuery('#total').html('Total:'+ number_format(total, 2, '.', ','));	

			} else 	{
						//importes
						jQuery('#subtotal').html('SubTotal: 0.00');	
						jQuery('#iva').html('IVA: 0.00');	
						jQuery('#total').html('Total: 0.00');										


			}	
	    },

		"infoCallback": function( settings, start, end, max, total, pre ) {
			if (settings.json.totales_importe) {
			  	jQuery('#total_subtotal').html( 'SubTotal:'+number_format(settings.json.totales_importe.subtotal, 2, '.', ','));
				jQuery('#total_iva').html( 'IVA:'+number_format(settings.json.totales_importe.iva, 2, '.', ','));
				jQuery('#total_total').html('Total:'+ number_format(settings.json.totales_importe.total, 2, '.', ','));

			} else {
			    jQuery('#total_subtotal').html( 'Subtotal: 0.00');
				jQuery('#total_iva').html( 'IVA: 0.00');
				jQuery('#total_total').html('Total de mts: 0.00');
			}				

			return pre
		},		    	     

		"language": {  //tratamiento de lenguaje
			"lengthMenu": "Mostrar _MENU_ registros por página",
			"zeroRecords": "No hay registros",
			"info": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
			"infoEmpty": "No hay registros disponibles",
			"infoFiltered": "(Mostrando _TOTAL_ de _MAX_ registros totales)",  
			"emptyTable":     "No hay registros",
			"infoPostFix":    "",
			"thousands":      ",",
			"loadingRecords": "Leyendo...",
			"processing":     "Procesando...",
			"search":         "Buscar:",
			"paginate": {
				"first":      "Primero",
				"last":       "Último",
				"next":       "Siguiente",
				"previous":   "Anterior"
			},
			"aria": {
				"sortAscending":  ": Activando para ordenar columnas ascendentes",
				"sortDescending": ": Activando para ordenar columnas descendentes"
			},
		},


		"columnDefs": [
			    	
			    	{ 
		                "render": function ( data, type, row ) {
		                		return data;
		                },
		                "targets": [0,1,2,3,4,5,6,7,8] 
		            },


		            {
		                "render": function ( data, type, row ) {


						$otro_retorno="listado_notas";
		        		texto='<td>';
							texto+='<a style="padding: 1px 0px 1px 0px;"';
							texto+=' href="procesar_entradas/'+jQuery.base64.encode(row[0])+'/'+jQuery.base64.encode(row[9])+'/'+jQuery.base64.encode($otro_retorno)+'"'; //
							texto+='type="button" class="btn btn-success btn-block">';
							texto+='Detalles';
							texto+='</a>';
						texto+='</td>';



							return texto;	
		                },
		                "targets": 9
		            },
  					/*
  					{ 
		                 "visible": false,
		                "targets": [9]
		            }
		            */
		          
		           
		            
		        ],
	});	







	jQuery('#tabla_historico_devolucion').dataTable( {
	
	  "pagingType": "full_numbers",
		
		"processing": true,
		"serverSide": true,
		"ajax": {
	            	"url" : "procesando_historico_devolucion",
	         		"type": "POST",
	         		 "data": function ( d ) {
	         		 	d.id_operacion=1;

						var fecha = (jQuery('.fecha_historicos').val()).split(' / ');
						d.fecha_inicial = fecha[0];
						d.fecha_final = fecha[1];	
					    d.id_almacen = jQuery("#id_almacen_historicos").val(); 
					    d.id_factura = jQuery("#id_factura_historicos").val(); 						


	         		 }
	         		
	     },   

		"language": {  //tratamiento de lenguaje
			"lengthMenu": "Mostrar _MENU_ registros por página",
			"zeroRecords": "No hay registros",
			"info": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
			"infoEmpty": "No hay registros disponibles",
			"infoFiltered": "(Mostrando _TOTAL_ de _MAX_ registros totales)",  
			"emptyTable":     "No hay registros",
			"infoPostFix":    "",
			"thousands":      ",",
			"loadingRecords": "Leyendo...",
			"processing":     "Procesando...",
			"search":         "Buscar:",
			"paginate": {
				"first":      "Primero",
				"last":       "Último",
				"next":       "Siguiente",
				"previous":   "Anterior"
			},
			"aria": {
				"sortAscending":  ": Activando para ordenar columnas ascendentes",
				"sortDescending": ": Activando para ordenar columnas descendentes"
			},
		},



		"footerCallback": function( tfoot, data, start, end, display ) {
		   var api = this.api(), data;
				var intVal = function ( i ) {
					return typeof i === 'string' ?
						i.replace(/[\$,]/g, '')*1 :
						typeof i === 'number' ?
							i : 0;
				};
			if  (data.length>0) {   
					

				total_subtotal = api
						.column( 5)
						.data()
						.reduce( function (a, b) {
							return intVal(a) + intVal(b);
						} );					

					
					total_iva = api
						.column( 6)
						.data()
						.reduce( function (a, b) {
							return intVal(a) + intVal(b);
						} );	
					
					total = api
						.column( 7)
						.data()
						.reduce( function (a, b) {
							return intVal(a) + intVal(b);
						} );					


						//importes
						jQuery('#subtotal').html('SubTotal:'+ number_format(total_subtotal, 2, '.', ','));
						jQuery('#iva').html('IVA:' + number_format( total_iva, 2, '.', ','));
						jQuery('#total').html('Total:'+ number_format(total, 2, '.', ','));	

			} else 	{
						//importes
						jQuery('#subtotal').html('SubTotal: 0.00');	
						jQuery('#iva').html('IVA: 0.00');	
						jQuery('#total').html('Total: 0.00');										


			}	
	    },

		"infoCallback": function( settings, start, end, max, total, pre ) {
			if (settings.json.totales_importe) {
			  	jQuery('#total_subtotal').html( 'SubTotal:'+number_format(settings.json.totales_importe.subtotal, 2, '.', ','));
				jQuery('#total_iva').html( 'IVA:'+number_format(settings.json.totales_importe.iva, 2, '.', ','));
				jQuery('#total_total').html('Total:'+ number_format(settings.json.totales_importe.total, 2, '.', ','));

			} else {
			    jQuery('#total_subtotal').html( 'Subtotal: 0.00');
				jQuery('#total_iva').html( 'IVA: 0.00');
				jQuery('#total_total').html('Total de mts: 0.00');
			}				

			return pre
		},			


		"columnDefs": [
			    	
			    	{ 
		                "render": function ( data, type, row ) {
		                		return data;
		                },
		                "targets": [0,1,2,3,4,5,6,7] 
		            },


		            {
		                "render": function ( data, type, row ) {


						$otro_retorno="listado_devolucion";
		        		texto='<td>';
							texto+='<a style="padding: 1px 0px 1px 0px;"';
							texto+=' href="procesar_entradas/'+jQuery.base64.encode(row[0])+'/'+jQuery.base64.encode(row[8])+'/'+jQuery.base64.encode($otro_retorno)+'"'; //
							texto+='type="button" class="btn btn-success btn-block">';
							texto+='Detalles';
							texto+='</a>';
						texto+='</td>';
							return texto;	
		                },
		                "targets": 8
		            },
  					/*
  					{ 
		                 "visible": false,
		                "targets": [9]
		            }
		            */
		          
		           
		            
		        ],
	});	






	jQuery('#tabla_historico_salida').dataTable( {
	
	  "pagingType": "full_numbers",
		
		"processing": true,
		"serverSide": true,
		"ajax": {
	            	"url" : "procesando_historico_salida",
	         		"type": "POST",
	         		 "data": function ( d ) {
	         		 	d.id_operacion=2;

						var fecha = (jQuery('.fecha_historicos').val()).split(' / ');
						d.fecha_inicial = fecha[0];
						d.fecha_final = fecha[1];	
					    d.id_almacen = jQuery("#id_almacen_historicos").val(); 
					    d.id_factura = jQuery("#id_factura_historicos").val(); 						


	         		 }
	         		
	     },  



		"footerCallback": function( tfoot, data, start, end, display ) {
		   var api = this.api(), data;
				var intVal = function ( i ) {
					return typeof i === 'string' ?
						i.replace(/[\$,]/g, '')*1 :
						typeof i === 'number' ?
							i : 0;
				};
			if  (data.length>0) {   
					

				total_subtotal = api
						.column( 7)
						.data()
						.reduce( function (a, b) {
							return intVal(a) + intVal(b);
						} );					

					
					total_iva = api
						.column( 8)
						.data()
						.reduce( function (a, b) {
							return intVal(a) + intVal(b);
						} );	
					
					total = api
						.column( 9)
						.data()
						.reduce( function (a, b) {
							return intVal(a) + intVal(b);
						} );					


						//importes
						jQuery('#subtotal').html('SubTotal:'+ number_format(total_subtotal, 2, '.', ','));
						jQuery('#iva').html('IVA:' + number_format( total_iva, 2, '.', ','));
						jQuery('#total').html('Total:'+ number_format(total, 2, '.', ','));	

			} else 	{
						//importes
						jQuery('#subtotal').html('SubTotal: 0.00');	
						jQuery('#iva').html('IVA: 0.00');	
						jQuery('#total').html('Total: 0.00');										


			}	
	    },

		"infoCallback": function( settings, start, end, max, total, pre ) {
			if (settings.json.totales_importe) {
			  	jQuery('#total_subtotal').html( 'SubTotal:'+number_format(settings.json.totales_importe.subtotal, 2, '.', ','));
				jQuery('#total_iva').html( 'IVA:'+number_format(settings.json.totales_importe.iva, 2, '.', ','));
				jQuery('#total_total').html('Total:'+ number_format(settings.json.totales_importe.total, 2, '.', ','));

			} else {
			    jQuery('#total_subtotal').html( 'Subtotal: 0.00');
				jQuery('#total_iva').html( 'IVA: 0.00');
				jQuery('#total_total').html('Total de mts: 0.00');
			}				

			return pre
		},				      

		"language": {  //tratamiento de lenguaje
			"lengthMenu": "Mostrar _MENU_ registros por página",
			"zeroRecords": "No hay registros",
			"info": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
			"infoEmpty": "No hay registros disponibles",
			"infoFiltered": "(Mostrando _TOTAL_ de _MAX_ registros totales)",  
			"emptyTable":     "No hay registros",
			"infoPostFix":    "",
			"thousands":      ",",
			"loadingRecords": "Leyendo...",
			"processing":     "Procesando...",
			"search":         "Buscar:",
			"paginate": {
				"first":      "Primero",
				"last":       "Último",
				"next":       "Siguiente",
				"previous":   "Anterior"
			},
			"aria": {
				"sortAscending":  ": Activando para ordenar columnas ascendentes",
				"sortDescending": ": Activando para ordenar columnas descendentes"
			},
		},


		"columnDefs": [
			    	
			    	{ 
		                "render": function ( data, type, row ) {
		                		return data;
		                },
		                "targets": [0,1,2,3,4,5,6,7,8,9] 
		            },


		            {
		                "render": function ( data, type, row ) {


						$otro_retorno="listado_devolucion";
		        		texto='<td>';
							texto+='<a style="padding: 1px 0px 1px 0px;"';
							texto+=' href="detalle_salidas/'+jQuery.base64.encode(row[0])+'/'+jQuery.base64.encode(row[2])+'/'+jQuery.base64.encode(row[3])+'"'; //
							texto+='type="button" class="btn btn-success btn-block">';
							texto+='Detalles';
							texto+='</a>';
						texto+='</td>';
							return texto;	
		                },
		                "targets": 10
		            },



									


  					/*
  					{ 
		                 "visible": false,
		                "targets": [9]
		            }
		            */
		          
		           
		            
		        ],
	});		
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////

var existencia_costo_inventario = ['Código', 'Producto', 'Color',   'Cantidad',  'Ancho', 'precio','iva', 'Tipo Factura', 'No. Movimiento','Proveedor', 'Ingreso'];

jQuery('#id_estatuss_costo').change(function(e) {
		
		var oTable =jQuery('#tabla_costo_inventario').dataTable();
		oTable._fnAjaxUpdate();
});


jQuery('#id_almacen_costo').change(function(e) {
		
		var oTable =jQuery('#tabla_costo_inventario').dataTable();
		oTable._fnAjaxUpdate();
});

///
jQuery("#factura_costo").on('keyup', function(e) {
		var oTable =jQuery('#tabla_costo_inventario').dataTable();
		oTable._fnAjaxUpdate();
 });


jQuery("#foco_costo").focusout(function (e) {
		var oTable =jQuery('#tabla_costo_inventario').dataTable();
		oTable._fnAjaxUpdate();
 });

jQuery('.fecha_costo').on('apply.daterangepicker', function(ev, picker) {
	var oTable =jQuery('#tabla_costo_inventario').dataTable();
		oTable._fnAjaxUpdate();
 });


jQuery('.fecha_costo').daterangepicker(
	  { 
    locale: { cancelLabel: 'Cancelar',
    		  applyLabel: 'Aceptar',
    		  fromLabel : 'Desde',
    		  toLabel: 'Hasta',
    		  monthNames : "ene._feb._mar_abr._may_jun_jul._ago_sep._oct._nov._dec.".split("_"),
    		  daysOfWeek: "Do_Lu_Ma_Mi_Ju_Vi_Sa".split("_"),
     } , 
    separator: ' / ',
    format: 'DD-MM-YYYY',
  }
);





 jQuery("#producto, #color, #composicion, #calidad").on('change', function(e) {
 		var hash_url = window.location.pathname;

		if  ( (hash_url=="/costo_inventario") )   {  
 		
			var campo = jQuery(this).attr("name");   
	 		 var val_prod = jQuery('#producto option:selected').text();  		  
	 		 var val_color = jQuery('#color').val();  		  
	 		 var val_comp = jQuery('#composicion').val();  		  
	 		 var val_calida = jQuery('#calidad').val();  		  

	         var dependencia = jQuery(this).attr("dependencia"); 
	         var nombre = jQuery(this).attr("nombre");           
	        
	    	if (dependencia !="") {	    
		        //limpiar la dependencia
		        jQuery("#"+dependencia).html(''); 
		        //cargar la dependencia
		        cargarDependenciaaa(campo,val_prod,val_color,val_comp,val_calida,dependencia,nombre);
	        }

			

		
				var oTable =jQuery('#tabla_costo_inventario').dataTable();
				oTable._fnAjaxUpdate();
    	}	



     });


	function cargarDependenciaaa(campo,val_prod,val_color,val_comp,val_calida,dependencia,nombre) {
		
		var url = 'cargar_dependencia';	

		jQuery.ajax({
		        url : 'cargar_dependencia',
		        data:{
		        	campo:campo,
		        	val_prod:val_prod,
		        	val_color:val_color,
		        	val_comp:val_comp,
		        	val_calida:val_calida,
		        	dependencia:dependencia
		        },


		        type : 'POST',
		        dataType : 'json',
		        success : function(data) {
		        		
		        	 //jQuery("#"+dependencia).trigger('change');
	                 jQuery("#"+dependencia).append('<option value="0" >Seleccione '+nombre+'</option>');
                    
					if (data != "[]") {
						
                        jQuery.each(data, function (i, valor) {
                            if (valor.nombre !== null) {
                                 jQuery("#"+dependencia).append('<option value="' + valor.identificador + '" style="background-color:#'+valor.hexadecimal_color+' !important;" >' + valor.nombre + '</option>');     
                            }
                        });

	                } 	
						
					if (jQuery('#oculto_producto').val() == 'si') {
						if (dependencia=='color') {
							jQuery('#color').val(jQuery('#oculto_producto').attr('color'));	
						}

						if (dependencia=='composicion') {
							//jQuery('#composicion').val("2");	
							jQuery('#composicion').val(jQuery('#oculto_producto').attr('composicion'));	
						}

						if (dependencia=='calidad') {
							jQuery('#calidad').val(jQuery('#oculto_producto').attr('calidad'));	
							jQuery('#oculto_producto').val('no');
						}
					}	
					

					jQuery("#"+dependencia).trigger('change');
	                //
	               // jQuery('#color').change();
                    return false;
		        },
		        error : function(jqXHR, status, error) {
		        },
		        complete : function(jqXHR, status) {
		            
		        }
		    }); 
	}







/////////////////////////buscar proveedores reportes

	// busqueda de proveedors reportes
	var consulta_proveedor_costo = new Bloodhound({
	   datumTokenizer: Bloodhound.tokenizers.obj.whitespace('nombre'),
	   queryTokenizer: Bloodhound.tokenizers.whitespace,
	   //remote:'catalogos/buscador?key=%QUERY&nombre='+jQuery('.buscar_proveedor_costo').attr("name")+'&idproveedor='+jQuery('.buscar_proveedor_costo').attr("idproveedor"),

	  remote: {
	        url: 'catalogos/buscador?key=%QUERY',
	        replace: function () {
	            var q = 'catalogos/buscador?key='+encodeURIComponent(jQuery('.buscar_proveedor_costo').typeahead("val"));
					q += '&nombre='+encodeURIComponent(jQuery('.buscar_proveedor_costo.tt-input').attr("name"));
				    q += '&idproveedor='+encodeURIComponent(jQuery('.buscar_proveedor_costo.tt-input').attr("idproveedor"));
	            
	            return  q;
	        }
	    },   

	});



	consulta_proveedor_costo.initialize();

	jQuery('.buscar_proveedor_costo').typeahead(
		{
			  hint: true,
		  highlight: true,
		  minLength: 1
		},

		 {
	  
	  name: 'buscar_proveedor_costo',
	  displayKey: 'descripcion', //
	  source: consulta_proveedor_costo.ttAdapter(),
	   templates: {
	   			//header: '<h4>'+jQuery('.buscar_proveedor_costo').attr("name")+'</h4>',
			    suggestion: function (data) {  
					return '<p><strong>' + data.descripcion + '</strong></p>'+
					 '<div style="background-color:'+ '#'+data.hexadecimal_color + ';display:block;width:15px;height:15px;margin:0 auto;"></div>';

		   }
	    
	  }
	});

	jQuery('.buscar_proveedor_costo').on('typeahead:selected', function (e, datum,otro) {
	    key = datum.key;
		var oTable =jQuery('#tabla_costo_inventario').dataTable();
		oTable._fnAjaxUpdate();


	});	

	jQuery('.buscar_proveedor_costo').on('typeahead:closed', function (e) {
		var oTable =jQuery('#tabla_costo_inventario').dataTable();
		oTable._fnAjaxUpdate();

	});	



//Agregar las estradas a salidas
jQuery('body').on('click','#impresion_reporte_costo', function (e) {

	  	  busqueda      = jQuery('input[type=search]').val();
	   extra_search = 'reportes_costo'; //jQuery("#botones").val(); 
	   id_estatus = jQuery("#id_estatuss_costo").val(); 
	   id_almacen = jQuery("#id_almacen_costo").val(); 

	   id_descripcion = jQuery("#producto").val(); 
	   if (id_descripcion !='') {
	   	  id_descripcion = jQuery('#producto option:selected').text();
	   }

	   id_color = jQuery("#color").val(); 
	   id_composicion = jQuery("#composicion").val(); 
	   id_calidad = jQuery("#calidad").val(); 
		
		factura_reporte = jQuery('#factura_costo').val();					

		proveedor = jQuery("#editar_proveedor_costo").val(); 	   

		var fecha = (jQuery('.fecha_costo').val()).split(' / ');

		fecha_inicial = fecha[0];
		fecha_final = fecha[1];


    abrir('POST', 'imprimir_reportes', {
    			busqueda:busqueda,
			extra_search:extra_search,
			id_estatus:id_estatus,
			id_almacen: id_almacen,

			id_descripcion:id_descripcion, 
			id_color:id_color, 
			id_composicion:id_composicion, 
			id_calidad:id_calidad,

			factura_reporte: factura_reporte,

			proveedor:proveedor, 
			fecha_inicial:fecha_inicial, 
			fecha_final: fecha_final,
    }, '_blank' );
		        
	
});




//Agregar las estradas a salidas
jQuery('body').on('click','#exportar_reportes_costo', function (e) {

	  busqueda      = jQuery('input[type=search]').val();
	   extra_search = "reportes_costo"; 
	   id_estatus = jQuery("#id_estatuss_costo").val(); 
	   id_almacen = jQuery("#id_almacen_costo").val(); 

	   id_descripcion = jQuery("#producto").val(); 
	   if (id_descripcion !='') {
	   	  id_descripcion = jQuery('#producto option:selected').text();
	   }

	   id_color = jQuery("#color").val(); 
	   id_composicion = jQuery("#composicion").val(); 
	   id_calidad = jQuery("#calidad").val(); 
		
		factura_reporte = jQuery('#factura_costo').val();					

		proveedor = jQuery("#editar_proveedor_costo").val(); 	   

		var fecha = (jQuery('.fecha_costo').val()).split(' / ');

		fecha_inicial = fecha[0];
		fecha_final = fecha[1];


    abrir('POST', 'exportar_reportes', {
    			busqueda:busqueda,
			extra_search:extra_search,
			id_estatus:id_estatus,
			id_almacen: id_almacen,

			id_descripcion:id_descripcion, 
			id_color:id_color, 
			id_composicion:id_composicion, 
			id_calidad:id_calidad,

			factura_reporte: factura_reporte,

			proveedor:proveedor, 
			fecha_inicial:fecha_inicial, 
			fecha_final: fecha_final,
    }, '_blank' );
		        
	
});


jQuery('#tabla_costo_inventario').dataTable( {
		
	  "pagingType": "full_numbers",
 	  "order": [[ 9, "asc" ]],


	"processing": true,
	"serverSide": true,
	"ajax": {
            	"url" : "procesando_costo_inventario",
         		"type": "POST",
         		 "data": function ( d ) {
         		 	   /*if (comienzo) {
         		 	   	 d.start=0;	 //comienza en cero siempre q cambia de botones
         		 	   	 d.draw =0;
         		 	   }*/

     				   
     				     d.id_estatus = jQuery("#id_estatuss_costo").val(); 
     				     d.id_almacen = jQuery("#id_almacen_costo").val(); 

     				   //datos del producto
     				   d.id_descripcion = jQuery("#producto").val(); 
     				   if (d.id_descripcion !='') {
     				   	  d.id_descripcion = jQuery('#producto option:selected').text();
     				   }



     				   //
     				   d.id_color = jQuery("#color").val(); 
     				   d.id_composicion = jQuery("#composicion").val(); 
     				   d.id_calidad = jQuery("#calidad").val(); 
	
						d.factura_reporte = jQuery('#factura_costo').val();					

					   d.proveedor = jQuery("#editar_proveedor_costo").val(); 	   

						var fecha = (jQuery('.fecha_costo').val()).split(' / ');
						d.fecha_inicial = fecha[0];
						d.fecha_final = fecha[1];
     				   
    			 }
     },   

	"infoCallback": function( settings, start, end, max, total, pre ) {
	    if (settings.json.totales) {
		    jQuery('#total_pieza').html( 'Total de piezas:'+ settings.json.totales.pieza);
			jQuery('#total_kg').html( 'Total de kgs:'+number_format(settings.json.totales.kilogramo, 2, '.', ','));
			jQuery('#total_metro').html('Total de mts:'+ number_format(settings.json.totales.metro, 2, '.', ','));

		} else {
		    jQuery('#total_pieza').html( 'Total de piezas: 0');
			jQuery('#total_kg').html( 'Total de kgs: 0.00');
			jQuery('#total_metro').html('Total de mts: 0.00');

		}	

		if (settings.json.totales_importe) {
		  	jQuery('#total_subtotal').html( 'SubTotal:'+number_format(settings.json.totales_importe.subtotal, 2, '.', ','));
			jQuery('#total_iva').html( 'IVA:'+number_format(settings.json.totales_importe.iva, 2, '.', ','));
			jQuery('#total_total').html('Total:'+ number_format(settings.json.totales_importe.total, 2, '.', ','));


		} else {
		    jQuery('#total_subtotal').html( 'Subtotal: 0.00');
			jQuery('#total_iva').html( 'IVA: 0.00');
			jQuery('#total_total').html('Total de mts: 0.00');

		}	



			if (settings.json.recordsTotal==0) {
				jQuery("#disa_reportes").attr('disabled', true);					
			} else {
				jQuery("#disa_reportes").attr('disabled', false);					
			}

	    return pre
  	} ,    


	"footerCallback": function( tfoot, data, start, end, display ) {
	   var api = this.api(), data;
			var intVal = function ( i ) {
				return typeof i === 'string' ?
					i.replace(/[\$,]/g, '')*1 :
					typeof i === 'number' ?
						i : 0;
			};
		if  (data.length>0) {   
				total_metro = api
					.column( 12 )
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					} );
				total_kilogramo = api
					.column( 13)
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					} );
				total_pieza = (end-start);	


			total_subtotal = api
					.column( 5)
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					} );					

				
				total_iva = api
					.column( 6)
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					} );	

				//importe
				
				total_total = api
					.column( 6 )
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					} );				


			        jQuery('#pieza').html( 'Total de piezas:'+ total_pieza);
			        jQuery('#kg').html( 'Total de kgs:'+number_format(total_kilogramo, 2, '.', ','));
			        jQuery('#metro').html('Total de mts:'+ number_format(total_metro, 2, '.', ','));

					//importes
					jQuery('#subtotal').html('SubTotal:'+ number_format(total_subtotal, 2, '.', ','));
					jQuery('#iva').html('IVA:' + number_format( total_iva, 2, '.', ','));
					jQuery('#total').html('Total:'+ number_format(total_subtotal+total_iva, 2, '.', ','));	

		} else 	{
			        jQuery('#pieza').html('Total de piezas: 0');
			        jQuery('#metro').html('Total de mts: 0.00');
					jQuery('#kg').html('Total de kgs: 0.00');			        

					//importes
					jQuery('#subtotal').html('SubTotal: 0.00');	
					jQuery('#iva').html('IVA: 0.00');	
					jQuery('#total').html('Total: 0.00');										


		}	
    },
   "columnDefs": [
    			{ 
	                "render": function ( data, type, row ) {
						return data;	
	                },
	                "targets": [0,1,2,3,4,5,6,7,8,9,11,]
	            },

	            
    			{ 
	                 "visible": false,
	                "targets": [10,12,13,14,18, 15,16,17]
	            }
	],

 "rowCallback": function( row, data ) {
	    // Bold the grade for all 'A' grade browsers
	    if ( data[14] == "red" ) {
	      jQuery('td', row).addClass( "danger" );
	    }

	    if ( data[14] == "morado" ) {
	      jQuery('td', row).addClass( "success" );
	    }

	    if ( data[18] == 1 ) {
	      jQuery('td', row).addClass( "warning" );
	    }



	  },		

	"fnHeaderCallback": function( nHead, aData, iStart, iEnd, aiDisplay ) {
						var arreglo =existencia_costo_inventario;
						for (var i=0; i<=arreglo.length-1; i++) { //cant_colum
					    		nHead.getElementsByTagName('th')[i].innerHTML = arreglo[i]; 
					    	}
	},


	"language": {  
		"lengthMenu": "Mostrar _MENU_ registros por página",
		"zeroRecords": "No hay registros",
		"info": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
		"infoEmpty": "No hay registros disponibles",
		"infoFiltered": "(Mostrando _TOTAL_ de _MAX_ registros totales)",  
		"emptyTable":     "No hay registros",
		"infoPostFix":    "",
		"thousands":      ",",
		"loadingRecords": "Leyendo...",
		"processing":     "Procesando...",
		"search":         "Buscar:",
		"paginate": {
			"first":      "Primero",
			"last":       "Último",
			"next":       "Siguiente",
			"previous":   "Anterior"
		},
		"aria": {
			"sortAscending":  ": Activando para ordenar columnas ascendentes",
			"sortDescending": ": Activando para ordenar columnas descendentes"
		},
	},
});	




/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////TRASPASO///////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
var arr_general_traspaso = ['Traspaso', 'Proceso','Almacén', 'Fecha', 'Motivo',  'Número',  'Responsable','Dependencia','Detalle']; //
var arr_traspaso_historico_detalle = ['Código', 'Producto', 'Color', 'Cantidad', 'Ancho', 'Precio', 'IVA', 'Lote','No. de Partida','Almacén','Tipo factura'];




jQuery('#id_almacen_modulo').change(function(e) {
	comienzo=true; //para indicar que start comience en 0;
	var oTable =jQuery('#tabla_entrada_traspaso').dataTable();
	oTable._fnAjaxUpdate();		
});





	jQuery('#label_tipo_factura_traspaso').text(((jQuery(this).val()==2) ? "De Factura a ": "De Remisión a ")+jQuery('#id_tipo_factura_traspaso option:selected').text());

jQuery('#id_tipo_factura_traspaso').change(function(e) {
	//alert('ass');
	comienzo=true; //para indicar que start comience en 0;

	etiqueta = (jQuery(this).val()==2) ? "De Factura a ": "De Remisión a ";

	jQuery('#label_tipo_factura_traspaso').text(etiqueta+jQuery('#id_tipo_factura_traspaso option:selected').text());
	var oTable =jQuery('#tabla_entrada_traspaso').dataTable();
	oTable._fnAjaxUpdate();		

});


jQuery('#tabla_entrada_traspaso').dataTable( {
 	"processing": true, //	//tratamiento con base de datos
	"serverSide": true,
	"ajax": {
            	"url" : "procesando_entrada_traspaso",
         		"type": "POST",
         		 "data": function ( d ) {

         		 	d.id_tipo_factura_inversa = (jQuery("#id_tipo_factura_traspaso").val()==2) ? 1: 2;
				    d.id_almacen = jQuery('#id_almacen_modulo').val();	
					d.id_tipo_factura = jQuery("#id_tipo_factura_traspaso").val();
    			 } 
     }, 
	"language": {  //tratamiento de lenguaje
		"lengthMenu": "Mostrar _MENU_ registros por página",
		"zeroRecords": "No hay registros",
		"info": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
		"infoEmpty": "No hay registros disponibles",
		"infoFiltered": "(Mostrando _TOTAL_ de _MAX_ registros totales)",  
		"emptyTable":     "No hay registros",
		"infoPostFix":    "",
		"thousands":      ",",
		"loadingRecords": "Leyendo...",
		"processing":     "Procesando...",
		"search":         "Buscar:",
		"paginate": {
			"first":      "Primero",
			"last":       "Último",
			"next":       "Siguiente",
			"previous":   "Anterior"
		},
		"aria": {
			"sortAscending":  ": Activando para ordenar columnas ascendentes",
			"sortDescending": ": Activando para ordenar columnas descendentes"
		},
	},

	"infoCallback": function( settings, start, end, max, total, pre ) {
	    if (settings.json.totales) {
		    jQuery('#total_pieza').html( 'Total de piezas:'+ settings.json.totales.pieza);
		  
			jQuery('#total_kg').html( 'Total de kgs:'+number_format(settings.json.totales.kilogramo, 2, '.', ','));
			jQuery('#total_metro').html('Total de mts:'+ number_format(settings.json.totales.metro, 2, '.', ','));

		} else {
		    jQuery('#total_pieza').html( 'Total de piezas: 0');
			jQuery('#total_kg').html( 'Total de kgs: 0.00');
			jQuery('#total_metro').html('Total de mts: 0.00');

		}	
		
		if (settings.json.totales_importe) {
		  	jQuery('#total_subtotal').html( 'SubTotal:'+number_format(settings.json.totales_importe.subtotal, 2, '.', ','));
			jQuery('#total_iva').html( 'IVA:'+number_format(settings.json.totales_importe.iva, 2, '.', ','));
			jQuery('#total_total').html('Total:'+ number_format(settings.json.totales_importe.total, 2, '.', ','));

		} else {
		    jQuery('#total_subtotal').html( 'Subtotal: 0.00');
			jQuery('#total_iva').html( 'IVA: 0.00');
			jQuery('#total_total').html('Total de mts: 0.00');

		}			

	    return pre
  	} ,  	


	"footerCallback": function( tfoot, data, start, end, display ) {
	   var api = this.api(), data;
	   
			var intVal = function ( i ) {
				return typeof i === 'string' ?
					i.replace(/[\$,]/g, '')*1 :
					typeof i === 'number' ?
						i : 0;
			};

		if  (data.length>0) {   
				
				total_metro = api
					.column( 13 )
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					} );
				
				total_kilogramo = api
					.column( 14 )
					.data()
					.reduce( function (c, d) {
						return intVal(c) + intVal(d);
					} );

				total_pieza = (end-start);	

	
				total_subtotal = api
					.column( 5)
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					} );					

				
				total_iva = api
					.column( 15)
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					} );	

				//importe
				
				total_total = api
					.column( 16 )
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					} );				

			        jQuery('#pieza').html( 'Total de piezas:'+ total_pieza);
			        jQuery('#kg').html( 'Total de kgs:'+number_format(total_kilogramo, 2, '.', ','));
			        jQuery('#metro').html('Total de mts:'+ number_format(total_metro, 2, '.', ','));


					//importes
					jQuery('#subtotal').html('SubTotal:'+ number_format(total_subtotal, 2, '.', ','));
					jQuery('#iva').html('IVA:' + number_format( total_iva, 2, '.', ','));
					jQuery('#total').html('Total:'+ number_format(total_total, 2, '.', ','));						


		} else 	{
			        jQuery('#pieza').html('Total de piezas: 0');
			        jQuery('#metro').html('Total de mts: 0.00');
					jQuery('#kg').html('Total de kgs: 0.00');	

					//importes
					jQuery('#subtotal').html('SubTotal: 0.00');	
					jQuery('#iva').html('IVA: 0.00');	
					jQuery('#total').html('Total: 0.00');										

		}	
    },  		  
	"columnDefs": [
	    		{ 
	                "render": function ( data, type, row ) {
	                		return data;
	                },
	                "targets": [0,1,2,3,4,5,6,7,8,9]
	            },
    			{ 
	                "render": function ( data, type, row ) {
						return row[12];	
	                },
	                "targets": [10]
	            },

	            {
	                "render": function ( data, type, row ) {
						texto='<td><button '; 
							texto+='type="button" class="btn btn-success btn-block agregar_traspaso '+row[10]+'" identificador="'+row[10]+'" >';
							texto+='<span  class="">Agregar</span>';
						texto+='</button></td>';
						return texto;	
	                },
	                "targets": 11
	            },
				{ 
	                 "visible": false,
	                 "targets": [12,13,14,15,16]
	            }		            
	        ],
});	





jQuery('table').on('click','.agregar_traspaso', function (e) {
	jQuery(this).attr('disabled', true);		

	comentario = jQuery("#comentario").val();
	factura = jQuery("#factura").val();
	id_almacen = jQuery("#id_almacen_modulo").val();

	movimiento = jQuery("#movimiento").val();
	
	id_tipo_factura = jQuery("#id_tipo_factura_traspaso").val();
	//d.id_tipo_factura_inversa 
    id_destino= (jQuery("#id_tipo_factura_traspaso").val()==2) ? 1: 2;
	//id del producto
	identificador = (jQuery(this).attr('identificador'));

	//editar_proveedor
	jQuery('#foo').css('display','block');
	var spinner = new Spinner(opts).spin(target);

 
	jQuery.ajax({
		        url : 'agregar_prod_salida_traspaso',
		        data : { 
		        	identificador: identificador,
		        	factura: factura,
		        	movimiento: movimiento,
		        	id_destino: id_destino,
		        	id_almacen: id_almacen,
		        	id_tipo_factura: id_tipo_factura,
		        	comentario:comentario,
		        },
		        type : 'POST',
		       // dataType : 'json',
		        success : function(data) {	
						if(data != true){
							//alert('sad');
								spinner.stop();
								jQuery('#foo').css('display','none');
								jQuery('#messages').css('display','block');
								jQuery('#messages').addClass('alert-danger');
								jQuery('#messages').html(data);
								jQuery('html,body').animate({
									'scrollTop': jQuery('#messages').offset().top
								}, 1000);


							//aqui es donde va el mensaje q no se ha copiado
						}else{
							
							spinner.stop();
							jQuery('#foo').css('display','none');
						    jQuery('#messages').css('display','none');

							jQuery("fieldset.disabledme").attr('disabled', true);

							jQuery('#tabla_salida_traspaso').dataTable().fnDraw();
							jQuery('#tabla_entrada_traspaso').dataTable().fnDraw();

							
								jQuery.ajax({
									        url : 'conteo_tienda',
									        data : { 
									        	tipo: 'tienda',
									        },
									        type : 'POST',
									        dataType : 'json',
									        success : function(data) {	
									        	MY_Socket.sendNewPost(data.vendedor+' - '+data.tienda,'agregar_traspaso');
									        	return false;	
									        }
								});	
								
						}
		        }
	});		
	jQuery(this).attr('disabled', false);				        

});







jQuery('#tabla_salida_traspaso').dataTable( {
 	"processing": true, //	//tratamiento con base de datos
	"serverSide": true,
	"ajax": {
            	"url" : "procesando_salida_traspaso",
         		"type": "POST",
         		 "data": function ( d ) {

         		 	d.id_tipo_factura_inversa = (jQuery("#id_tipo_factura_traspaso").val()==2) ? 1: 2;
				    d.id_almacen = jQuery('#id_almacen_modulo').val();	
					d.id_tipo_factura = jQuery("#id_tipo_factura_traspaso").val();
    			 } 
     }, 
	"language": {  //tratamiento de lenguaje
		"lengthMenu": "Mostrar _MENU_ registros por página",
		"zeroRecords": "No hay registros",
		"info": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
		"infoEmpty": "No hay registros disponibles",
		"infoFiltered": "(Mostrando _TOTAL_ de _MAX_ registros totales)",  
		"emptyTable":     "No hay registros",
		"infoPostFix":    "",
		"thousands":      ",",
		"loadingRecords": "Leyendo...",
		"processing":     "Procesando...",
		"search":         "Buscar:",
		"paginate": {
			"first":      "Primero",
			"last":       "Último",
			"next":       "Siguiente",
			"previous":   "Anterior"
		},
		"aria": {
			"sortAscending":  ": Activando para ordenar columnas ascendentes",
			"sortDescending": ": Activando para ordenar columnas descendentes"
		},
	},

	"infoCallback": function( settings, start, end, max, total, pre ) {
	    if (settings.json.totales) {
		    jQuery('#total_pieza2').html( 'Total de piezas:'+ settings.json.totales.pieza);
			jQuery('#total_kg2').html( 'Total de kgs:'+number_format(settings.json.totales.kilogramo, 2, '.', ','));
			jQuery('#total_metro2').html('Total de mts:'+ number_format(settings.json.totales.metro, 2, '.', ','));
		} else {
		    jQuery('#total_pieza2').html( 'Total de piezas: 0');
			jQuery('#total_kg2').html( 'Total de kgs: 0.00');
			jQuery('#total_metro2').html('Total de mts: 0.00');
		}	

  		if (settings.json.totales_importe) {
		  	jQuery('#total_subtotal2').html( 'SubTotal:'+number_format(settings.json.totales_importe.subtotal, 2, '.', ','));
			jQuery('#total_iva2').html( 'IVA:'+number_format(settings.json.totales_importe.iva, 2, '.', ','));
			jQuery('#total_total2').html('Total:'+ number_format(settings.json.totales_importe.total, 2, '.', ','));

		} else {
		    jQuery('#total_subtotal2').html( 'Subtotal: 0.00');
			jQuery('#total_iva2').html( 'IVA: 0.00');
			jQuery('#total_total2').html('Total de mts: 0.00');

		}	

	    return pre
  	} ,  	


	"footerCallback": function( tfoot, data, start, end, display ) {
	   var api = this.api(), data;
			var intVal = function ( i ) {
				return typeof i === 'string' ?
					i.replace(/[\$,]/g, '')*1 :
					typeof i === 'number' ?
						i : 0;
			};

		if  (data.length>0) {   
				
				total_metro = api
					.column( 13 )
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					} );
				total_kilogramo = api
					.column( 14)
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					} );
				total_pieza = (end-start);	

				
				total_subtotal = api
					.column( 5)
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					} );					

				
				total_iva = api
					.column( 15)
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					} );	

				//importe
				
				total_total = api
					.column( 16 )
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					} );	


			        jQuery('#pieza2').html( 'Total de piezas:'+ total_pieza);
			        jQuery('#kg2').html( 'Total de kgs:'+number_format(total_kilogramo, 2, '.', ','));
			        jQuery('#metro2').html('Total de mts:'+ number_format(total_metro, 2, '.', ','));
					//importes
					jQuery('#subtotal2').html('SubTotal:'+ number_format(total_subtotal, 2, '.', ','));
					jQuery('#iva2').html('IVA:' + number_format( total_iva, 2, '.', ','));
					jQuery('#total2').html('Total:'+ number_format(total_total, 2, '.', ','));			        

		} else 	{
			        jQuery('#pieza2').html('Total de piezas: 0');
			        jQuery('#metro2').html('Total de mts: 0.00');
					jQuery('#kg2').html('Total de kgs: 0.00');	
					//importes
					jQuery('#subtotal2').html('SubTotal: 0.00');	
					jQuery('#iva2').html('IVA: 0.00');	
					jQuery('#total2').html('Total: 0.00');					

		}	
    },  		  
	"columnDefs": [
	    		{ 
	                "render": function ( data, type, row ) {
	                		return data;
	                },
	                "targets": [0,1,2,3,4,5,6,7,8,9]
	            },
    			{ 
	                "render": function ( data, type, row ) {
						return row[12];	
	                },
	                "targets": [10]
	            },

	            {
	                "render": function ( data, type, row ) {
						texto='<td><button '; 
							texto+='type="button" class="btn btn-success btn-block quitar_traspaso '+row[10]+'" identificador="'+row[10]+'" >';
							texto+='<span  class="">Quitar</span>';
						texto+='</button></td>';
						return texto;	
	                },
	                "targets": 11
	            },
				{ 
	                 "visible": false,
	                 "targets": [12,13,14,15,16]
	            }		            
	        ],
});	






jQuery('table').on('click','.quitar_traspaso', function (e) {
	jQuery(this).attr('disabled', true);		
	
	id_almacen = jQuery("#id_almacen_modulo").val();
	id_tipo_factura = jQuery("#id_tipo_factura_traspaso").val();
	identificador = (jQuery(this).attr('identificador'));

	//editar_proveedor
	jQuery('#foo').css('display','block');
	var spinner = new Spinner(opts).spin(target);

 
	jQuery.ajax({
		        url : 'quitar_prod_salida_traspaso',
		        data : { 
		        	identificador: identificador,
		        	id_almacen: id_almacen,
		        	id_tipo_factura: id_tipo_factura,
		        },
		        type : 'POST',
		        dataType : 'json',
		        success : function(data) {	
						if(data.exito != true){
							//alert('sad');
								spinner.stop();
								jQuery('#foo').css('display','none');
								jQuery('#messages').css('display','block');
								jQuery('#messages').addClass('alert-danger');
								jQuery('#messages').html(data.error);
								jQuery('html,body').animate({
									'scrollTop': jQuery('#messages').offset().top
								}, 1000);


							//aqui es donde va el mensaje q no se ha copiado
						}else{
							
							spinner.stop();
							jQuery('#foo').css('display','none');
						    jQuery('#messages').css('display','none');						

							if(data.total == 0){
								jQuery("fieldset.disabledme").attr('disabled', false);
							}	
							jQuery('#tabla_salida_traspaso').dataTable().fnDraw();
							jQuery('#tabla_entrada_traspaso').dataTable().fnDraw();
							

							
								jQuery.ajax({
									        url : 'conteo_tienda',
									        data : { 
									        	tipo: 'tienda',
									        },
									        type : 'POST',
									        dataType : 'json',
									        success : function(data) {	
									        	MY_Socket.sendNewPost(data.vendedor+' - '+data.tienda,'quitar_traspaso');
									     		return false;
									        }
								});	
							

						}
		        }
	});		
	jQuery(this).attr('disabled', false);				        
	

});


jQuery('body').on('click','#proc_traspaso', function (e) {

		jQuery('#foo').css('display','block');
		var spinner = new Spinner(opts).spin(target);

	jQuery.ajax({
		        url : 'procesando_traspaso_definitivo',
		        type : 'POST',
		        dataType : 'json',
		        success : function(datos) {	
		        
						if(datos.exito != true){
								
								spinner.stop();
								jQuery('#foo').css('display','none');
								jQuery('#messages').css('display','block');
								jQuery('#messages').addClass('alert-danger');
								jQuery('#messages').html(datos);
								jQuery('html,body').animate({
									'scrollTop': jQuery('#messages').offset().top
								}, 1000);
						}else{
							spinner.stop();
							jQuery('#foo').css('display','none');
						
						    abrir('POST', 'imprimir_detalle_traspaso_post', {
						    			datos: JSON.stringify(datos),
						    }, '_blank' );							
						    
							
							//window.location.href = '/';

								
								jQuery.ajax({
									        url : 'conteo_tienda',
									        data : { 
									        	tipo: 'tienda',
									        },
									        type : 'POST',
									        dataType : 'json',
									        success : function(data) {	
									        	MY_Socket.sendNewPost(data.vendedor+' - '+data.tienda,'quitar_traspaso');
									        	window.location.href = '/';
									        }
								});									
					
							 return false;
							
						}
		        }
	});		

			        
});


abrir = function(verb, url, data, target) {
  var form = document.createElement("form");
  form.action = url;
  form.method = verb;
  form.target = target || "_self";
  if (data) {
    for (var key in data) {
      var input = document.createElement("textarea");
      input.name = key;
      input.value = typeof data[key] === "object" ? JSON.stringify(data[key]) : data[key];
      form.appendChild(input);
    }
  }
  form.style.display = 'none';
  document.body.appendChild(form);
  form.submit();
};



//////////////////////////////////////////////////////////////////



jQuery('#id_almacen_traspaso').change(function(e) {
	comienzo=true; //para indicar que start comience en 0;
	var oTable =jQuery('#tabla_general_traspaso').dataTable();
	oTable._fnAjaxUpdate();		
	var oTable =jQuery('#tabla_traspaso_historico').dataTable();
	oTable._fnAjaxUpdate();	
	
});


jQuery('#tabla_traspaso_historico').dataTable( {
	
	  "pagingType": "full_numbers",
	"processing": true,
	"serverSide": true,
	"ajax": {
            	"url" : "procesando_traspaso_historico",
         		"type": "POST",
    			"data": function ( d ) {
						    d.id_almacen = jQuery('#id_almacen_traspaso').val();	
    			 }          		

     },   
	"infoCallback": function( settings, start, end, max, total, pre ) {
	    return pre
	},    

   "columnDefs": [

				{ 
	                "render": function ( data, type, row ) {
						if (row[1]!=0) {
							return row[1];		 //row[0]+'<b> - </b>'+
						} else {
							return row[0];	
						}
						
	                },
	                "targets": [0]
	            },   
    			{ 
	                "render": function ( data, type, row ) {
						return data;	
	                },
	                "targets": [2,3,4]
	            },
				{ 
	                "render": function ( data, type, row ) {
						return row[5]; //+' <br/><b>Nro.</b>'+row[6];										
	                },
	                "targets": [5]
	            },   	            

				{ 
	                "render": function ( data, type, row ) {
						return row[7];										
	                },
	                "targets": [6]
	            },

				{ 
	                "render": function ( data, type, row ) {
						return row[8];										
	                },
	                "targets": [7]
	            },

				{ 
	                "render": function ( data, type, row ) {
						return row[9];										
	                },
	                "targets": [8]
	            },   	         		               	         	               	            
	            
    			{ 
	                "render": function ( data, type, row ) {
    					 texto='<td><a href="traspaso_detalle/'+jQuery.base64.encode(row[7])+'" ';  
						 	texto+=' class="btn btn-success btn-block">';
						 	texto+=' Detalles';
						 texto+='</a></td>';


						return texto;	
	                },
	                "targets": [9]
	            },
    			{ 
	                 "visible": false,
	                "targets": [1]
	            }	            
	],	

	"fnHeaderCallback": function( nHead, aData, iStart, iEnd, aiDisplay ) {
		var arreglo =arr_general_traspaso;
		for (var i=0; i<=arreglo.length-1; i++) { //cant_colum
	    		nHead.getElementsByTagName('th')[i].innerHTML = arreglo[i]; 
	    	}
	},	

	"language": {  //tratamiento de lenguaje
		"lengthMenu": "Mostrar _MENU_ registros por página",
		"zeroRecords": "No hay registros",
		"info": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
		"infoEmpty": "No hay registros disponibles",
		"infoFiltered": "(Mostrando _TOTAL_ de _MAX_ registros totales)",  
		"emptyTable":     "No hay registros",
		"infoPostFix":    "",
		"thousands":      ",",
		"loadingRecords": "Leyendo...",
		"processing":     "Procesando...",
		"search":         "Buscar:",
		"paginate": {
			"first":      "Primero",
			"last":       "Último",
			"next":       "Siguiente",
			"previous":   "Anterior"
		},
		"aria": {
			"sortAscending":  ": Activando para ordenar columnas ascendentes",
			"sortDescending": ": Activando para ordenar columnas descendentes"
		},
	},
});	



jQuery('#traspaso_historico_detalle').dataTable( {
	"pagingType": "full_numbers",
	"processing": true,
	"serverSide": true,
	"ajax": {
            	"url" : "/traspaso_historico_detalle",
         		"type": "POST",
         		 "data": function ( d ) {
         		 	d.consecutivo_traspaso = jQuery("#consecutivo_traspaso").val();
    			 } 

     },   


	"infoCallback": function( settings, start, end, max, total, pre ) {
		    
	    if (settings.json.datos) {
			
			
			jQuery('#etiq_consecutivo_traspaso').val( settings.json.datos.consecutivo_traspaso);
			jQuery('#etiq_proceso').val( settings.json.datos.proceso);
			jQuery('#etiq_traspaso').val( settings.json.datos.traspaso);
		    jQuery('#etiq_fecha').val(  settings.json.datos.mi_fecha);
		    
		    jQuery('#etiq_responsable').val( settings.json.datos.responsable);
		    jQuery('#etiq_dependencia').val( settings.json.datos.dependencia);
		    jQuery('#etiq_almacen').val( settings.json.datos.almacen);
		    
		    jQuery('#etiq_motivos').html( settings.json.datos.motivos);

			if (settings.json.datos.tipo_apartado=="Vendedor") {
				jQuery('#label_cliente').text("Empresa Asociada");
				jQuery('#label_vendedor').text("Vendedor");
				
			} else {
				jQuery('#label_cliente').text("Cliente");
				jQuery('#label_vendedor').text("Num. Mov");
			}
				


		}	
		
	    return pre
	}, 
	
   "columnDefs": [
    			{ 
	                "render": function ( data, type, row ) {
						return data;	
	                },
	                "targets": [0,1,2,3,4,5,6,7,8,9,13],
	            },


    			{ 
	                 "visible": false,
	                "targets": [10,11,12,14],
	            }		            

	],	
	"fnHeaderCallback": function( nHead, aData, iStart, iEnd, aiDisplay ) {
		var arreglo =arr_traspaso_historico_detalle;
		for (var i=0; i<=arreglo.length-1; i++) { //cant_colum //
	    		nHead.getElementsByTagName('th')[i].innerHTML = arreglo[i]; 

	    	}
	},	

	"language": {  //tratamiento de lenguaje
		"lengthMenu": "Mostrar _MENU_ registros por página",
		"zeroRecords": "No hay registros",
		"info": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
		"infoEmpty": "No hay registros disponibles",
		"infoFiltered": "(Mostrando _TOTAL_ de _MAX_ registros totales)",  
		"emptyTable":     "No hay registros",
		"infoPostFix":    "",
		"thousands":      ",",
		"loadingRecords": "Leyendo...",
		"processing":     "Procesando...",
		"search":         "Buscar:",
		"paginate": {
			"first":      "Primero",
			"last":       "Último",
			"next":       "Siguiente",
			"previous":   "Anterior"
		},
		"aria": {
			"sortAscending":  ": Activando para ordenar columnas ascendentes",
			"sortDescending": ": Activando para ordenar columnas descendentes"
		},
	},
});	




jQuery('#tabla_general_traspaso').dataTable( {
	
	  "pagingType": "full_numbers",
	"processing": true,
	"serverSide": true,
	"ajax": {
            	"url" : "procesando_general_traspaso",
         		"type": "POST",
    			"data": function ( d ) {
						    d.id_almacen = jQuery('#id_almacen_traspaso').val();	
    			 }          		

     },   
	"infoCallback": function( settings, start, end, max, total, pre ) {
	    return pre
	},    

   "columnDefs": [

				{ 
	                "render": function ( data, type, row ) {
						if (row[11]!=0) {
							return row[14];		
						} else {	
							if (row[1]!=0) {
								return row[1];		 //row[0]+'<b> - </b>'+
							} else {
								return row[0];	
							}
						}	
						
	                },
	                "targets": [0]
	            },   
    			{ 
	                "render": function ( data, type, row ) {
						return data;	
	                },
	                "targets": [2,3,4]
	            },
				{ 
	                "render": function ( data, type, row ) {
	                	if (row[11]!=0) {
							return row[12];	
						} else {
							return row[5]+' <br/><b>Nro.</b>'+row[6];										
						}
						
	                },
	                "targets": [5]
	            },   	            

				{ 
	                "render": function ( data, type, row ) {
						return row[7];										
	                },
	                "targets": [6]
	            },

				{ 
	                "render": function ( data, type, row ) {
						return row[8];										
	                },
	                "targets": [7]
	            },

				{ 
	                "render": function ( data, type, row ) {
						return row[9];										
	                },
	                "targets": [8]
	            },   	         		               	         	               	            
	            
    			{ 
	                "render": function ( data, type, row ) {
    					 if (row[11]!=0) {
	    					 texto='<td><a href="traspaso_general_detalle_manual/'+jQuery.base64.encode(row[15])+'/'+jQuery.base64.encode(jQuery('#id_almacen_traspaso option:selected').val())+'" ';  
							 	texto+=' class="btn btn-success btn-block">';
							 	texto+=' Detalles';
							 texto+='</a></td>';
						 } else {
	    					 texto='<td><a href="traspaso_general_detalle/'+jQuery.base64.encode(row[6])+'/'+jQuery.base64.encode(row[10])+'/'+jQuery.base64.encode(jQuery('#id_almacen_traspaso option:selected').val())+'" ';  
							 	texto+=' class="btn btn-success btn-block">';
							 	texto+=' Detalles';
							 texto+='</a></td>';						 	

						 }	 


						return texto;	
	                },
	                "targets": [9]
	            },
    			{ 
	                 "visible": false,
	                "targets": [1,10,11,12,13,14,15]
	            }	            
	],	

	"fnHeaderCallback": function( nHead, aData, iStart, iEnd, aiDisplay ) {
		var arreglo =arr_general_traspaso;
		for (var i=0; i<=arreglo.length-1; i++) { //cant_colum
	    		nHead.getElementsByTagName('th')[i].innerHTML = arreglo[i]; 
	    	}
	},	

	"language": {  //tratamiento de lenguaje
		"lengthMenu": "Mostrar _MENU_ registros por página",
		"zeroRecords": "No hay registros",
		"info": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
		"infoEmpty": "No hay registros disponibles",
		"infoFiltered": "(Mostrando _TOTAL_ de _MAX_ registros totales)",  
		"emptyTable":     "No hay registros",
		"infoPostFix":    "",
		"thousands":      ",",
		"loadingRecords": "Leyendo...",
		"processing":     "Procesando...",
		"search":         "Buscar:",
		"paginate": {
			"first":      "Primero",
			"last":       "Último",
			"next":       "Siguiente",
			"previous":   "Anterior"
		},
		"aria": {
			"sortAscending":  ": Activando para ordenar columnas ascendentes",
			"sortDescending": ": Activando para ordenar columnas descendentes"
		},
	},
});	


jQuery('#traspaso_general_detalle').dataTable( {
	"pagingType": "full_numbers",
	"processing": true,
	"serverSide": true,
	"ajax": {
            	"url" : "/procesando_traspaso_general_detalle",
         		"type": "POST",
         		 "data": function ( d ) {
         		 		d.id_almacen = jQuery('#id_almacen_traspaso').val();	
         		 		d.num_movimiento = jQuery("#num_movimiento").val();  //numero_mov del pedido
     				   d.id_apartado = jQuery("#id_apartado").val();  //numero_mov del pedido
    			 } 

     },   


	"infoCallback": function( settings, start, end, max, total, pre ) {
		    
	    if (settings.json.datos) {
			
			
			jQuery('#etiq_consecutivo_traspaso').val( settings.json.datos.consecutivo_traspaso);
			jQuery('#etiq_proceso').val( settings.json.datos.proceso);
			jQuery('#etiq_traspaso').val( settings.json.datos.traspaso);
		    jQuery('#etiq_fecha').val(  settings.json.datos.mi_fecha);
		    
		    jQuery('#etiq_responsable').val( settings.json.datos.responsable);
		    jQuery('#etiq_dependencia').val( settings.json.datos.dependencia);
		    jQuery('#etiq_almacen').val( settings.json.datos.almacen);
		    
		    jQuery('#etiq_motivos').html( settings.json.datos.motivos);

			if (settings.json.datos.tipo_apartado=="Vendedor") {
				jQuery('#label_cliente').text("Empresa Asociada");
				jQuery('#label_vendedor').text("Vendedor");
				
			} else {
				jQuery('#label_cliente').text("Cliente");
				jQuery('#label_vendedor').text("Num. Mov");
			}
				


		}	
		
	    return pre
	}, 
	
   "columnDefs": [

			   { 
	                "render": function ( data, type, row ) {
						return data;	
	                },
	                "targets": [0,1,2,3,4,5,6,7,8,9,13],
	            },


    			{ 
	                 "visible": false,
	                "targets": [10,11,12,14],
	            }			            

	],	
	"fnHeaderCallback": function( nHead, aData, iStart, iEnd, aiDisplay ) {
		var arreglo =arr_traspaso_historico_detalle;
		for (var i=0; i<=arreglo.length-1; i++) { //cant_colum //
	    		nHead.getElementsByTagName('th')[i].innerHTML = arreglo[i]; 

	    	}
	},	

	"language": {  //tratamiento de lenguaje
		"lengthMenu": "Mostrar _MENU_ registros por página",
		"zeroRecords": "No hay registros",
		"info": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
		"infoEmpty": "No hay registros disponibles",
		"infoFiltered": "(Mostrando _TOTAL_ de _MAX_ registros totales)",  
		"emptyTable":     "No hay registros",
		"infoPostFix":    "",
		"thousands":      ",",
		"loadingRecords": "Leyendo...",
		"processing":     "Procesando...",
		"search":         "Buscar:",
		"paginate": {
			"first":      "Primero",
			"last":       "Último",
			"next":       "Siguiente",
			"previous":   "Anterior"
		},
		"aria": {
			"sortAscending":  ": Activando para ordenar columnas ascendentes",
			"sortDescending": ": Activando para ordenar columnas descendentes"
		},
	},
});	


jQuery('#traspaso_general_detalle_manual').dataTable( {
	"pagingType": "full_numbers",
	"processing": true,
	"serverSide": true,
	"ajax": {
            	"url" : "/procesando_traspaso_general_detalle_manual",
         		"type": "POST",
         		 "data": function ( d ) {
         		 		d.id_almacen = jQuery('#id_almacen_traspaso').val();	
     				    d.id_usuario = jQuery("#id_usuario").val();  //numero_mov del pedido
    			 } 

     },   


	"infoCallback": function( settings, start, end, max, total, pre ) {
		    
	    if (settings.json.datos) {
			
			
			
			jQuery('#etiq_proceso').val( settings.json.datos.proceso);
			jQuery('#etiq_traspaso').val( settings.json.datos.traspaso);
		    jQuery('#etiq_fecha').val(  settings.json.datos.mi_fecha);
		    
		    jQuery('#etiq_responsable').val( settings.json.datos.responsable);
		    jQuery('#etiq_dependencia').val( settings.json.datos.dependencia);
		    jQuery('#etiq_almacen').val( settings.json.datos.almacen);
		    
		    jQuery('#etiq_motivos').html( settings.json.datos.motivos);

			if (settings.json.datos.tipo_apartado=="Vendedor") {
				jQuery('#label_cliente').text("Empresa Asociada");
				jQuery('#label_vendedor').text("Vendedor");
				
			} else {
				jQuery('#label_cliente').text("Cliente");
				jQuery('#label_vendedor').text("Num. Mov");
			}
				


		}	
		
	    return pre
	}, 
	
   "columnDefs": [

				{ 
	                "render": function ( data, type, row ) {
						return data;	
	                },
	                "targets": [0,1,2,3,4,5,6,7,8,9,13],
	            },


    			{ 
	                 "visible": false,
	                "targets": [10,11,12,14],
	            }		
	],	
	"fnHeaderCallback": function( nHead, aData, iStart, iEnd, aiDisplay ) {
		var arreglo =arr_traspaso_historico_detalle;
		for (var i=0; i<=arreglo.length-1; i++) { //cant_colum //
	    		nHead.getElementsByTagName('th')[i].innerHTML = arreglo[i]; 

	    	}
	},	

	"language": {  //tratamiento de lenguaje
		"lengthMenu": "Mostrar _MENU_ registros por página",
		"zeroRecords": "No hay registros",
		"info": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
		"infoEmpty": "No hay registros disponibles",
		"infoFiltered": "(Mostrando _TOTAL_ de _MAX_ registros totales)",  
		"emptyTable":     "No hay registros",
		"infoPostFix":    "",
		"thousands":      ",",
		"loadingRecords": "Leyendo...",
		"processing":     "Procesando...",
		"search":         "Buscar:",
		"paginate": {
			"first":      "Primero",
			"last":       "Último",
			"next":       "Siguiente",
			"previous":   "Anterior"
		},
		"aria": {
			"sortAscending":  ": Activando para ordenar columnas ascendentes",
			"sortDescending": ": Activando para ordenar columnas descendentes"
		},
	},
});	

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////hasta aqui//TRASPASO///////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


///////////////////////Formatear
          //http://phpjs.org/functions/number_format/
function number_format(number, decimals, dec_point, thousands_sep) {
  number = (number + '')
    .replace(/[^0-9+\-Ee.]/g, '');
  var n = !isFinite(+number) ? 0 : +number,
    prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
    sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
    dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
    s = '',
    toFixedFix = function(n, prec) {
      var k = Math.pow(10, prec);
      return '' + (Math.round(n * k) / k)
        .toFixed(prec);
    };
  // Fix for IE parseFloat(0.55).toFixed(0) = 0;
  s = (prec ? toFixedFix(n, prec) : '' + Math.round(n))
    .split('.');
  if (s[0].length > 3) {
    s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
  }
  if ((s[1] || '')
    .length < prec) {
    s[1] = s[1] || '';
    s[1] += new Array(prec - s[1].length + 1)
      .join('0');
  }
  return s.join(dec);
}



	jQuery('#tabla_apartado_vendedores').dataTable( {
	
	  "pagingType": "full_numbers",
		
		"processing": true,
		"serverSide": true,
		"ajax": {
	            	"url" : "tabla_apartado_vendedores",
	         		"type": "POST",
	         		
	     },   

		"language": {  //tratamiento de lenguaje
			"lengthMenu": "Mostrar _MENU_ registros por página",
			"zeroRecords": "No hay registros",
			"info": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
			"infoEmpty": "No hay registros disponibles",
			"infoFiltered": "(Mostrando _TOTAL_ de _MAX_ registros totales)",  
			"emptyTable":     "No hay registros",
			"infoPostFix":    "",
			"thousands":      ",",
			"loadingRecords": "Leyendo...",
			"processing":     "Procesando...",
			"search":         "Buscar:",
			"paginate": {
				"first":      "Primero",
				"last":       "Último",
				"next":       "Siguiente",
				"previous":   "Anterior"
			},
			"aria": {
				"sortAscending":  ": Activando para ordenar columnas ascendentes",
				"sortDescending": ": Activando para ordenar columnas descendentes"
			},
		},


		"columnDefs": [
			    	
			    	{ 
		                "render": function ( data, type, row ) {
		                		return data;
		                },
		                "targets": [0,1,2,3,4,5,6] //
		            },


		            
		            {
		                "render": function ( data, type, row ) {
		                	
	   							texto='	<td>';								
									texto+=' <a href="eliminar_apartado_vendedores/'+(row[7])+'/'+jQuery.base64.encode(row[0])+ '"'; 
									texto+=' class="btn btn-danger btn-sm btn-block" data-toggle="modal" data-target="#modalMessage">';
									texto+=' <span class="glyphicon glyphicon-remove"></span>';
									texto+=' </a>';
								texto+=' </td>';	

							return texto;	
		                },
		                "targets": 7
		            },
		            {
		                "render": function ( data, type, row ) {
							return row[8];	
		                },
		                "targets": 8
		            },		            
		           
		            
		        ],



		"infoCallback": function( settings, start, end, max, total, pre ) {
		    if (settings.json.totales) {
			    jQuery('#total_pieza').html( 'Total de Piezas: '+ settings.json.totales.pieza);
				jQuery('#total_kg').html( 'Total de Kgs: '+number_format(settings.json.totales.kilogramo, 2, '.', ','));
				jQuery('#total_metro').html('Total de Mts: '+ number_format(settings.json.totales.metro, 2, '.', ','));
				jQuery('#total_precio').html('Importe Total: '+ number_format(settings.json.totales.precio, 2, '.', ','));
			} else 	{
			    jQuery('#total_pieza').html( 'Total de Piezas: 0.00');
				jQuery('#total_kg').html( 'Total de Kgs: 0.00');
				jQuery('#total_metro').html('Total de Mts: 0.00');
				jQuery('#total_precio').html('Importe Total: 0.00');
			}	

		    return pre
	  	} ,    

	});	



///////////////////////////////////////////////////////////////////////////////////////


////////////////////////////////////////////////////////////////////////////////////
	jQuery('#tabla_cat_configuraciones').dataTable( {
	
	  "pagingType": "full_numbers",
		
		"processing": true,
		"serverSide": true,
		"ajax": {
	            	"url" : "procesando_cat_configuraciones",
	         		"type": "POST",
	         		
	     },   

		"language": {  //tratamiento de lenguaje
			"lengthMenu": "Mostrar _MENU_ registros por página",
			"zeroRecords": "No hay registros",
			"info": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
			"infoEmpty": "No hay registros disponibles",
			"infoFiltered": "(Mostrando _TOTAL_ de _MAX_ registros totales)",  
			"emptyTable":     "No hay registros",
			"infoPostFix":    "",
			"thousands":      ",",
			"loadingRecords": "Leyendo...",
			"processing":     "Procesando...",
			"search":         "Buscar:",
			"paginate": {
				"first":      "Primero",
				"last":       "Último",
				"next":       "Siguiente",
				"previous":   "Anterior"
			},
			"aria": {
				"sortAscending":  ": Activando para ordenar columnas ascendentes",
				"sortDescending": ": Activando para ordenar columnas descendentes"
			},
		},


		"columnDefs": [
			    	
			    	{ 
		                "render": function ( data, type, row ) {
		                		return row[1];
		                },
		                "targets": [0] //,2,3,4
		            },

			    	{ 
		                "render": function ( data, type, row ) {
		                		return row[3];
		                },
		                "targets": [1] //,2,3,4
		            },

			    	{ 
		                "render": function ( data, type, row ) {
		                		return row[2];
		                },
		                "targets": [2] //,2,3,4
		            },	             

		            {
		                "render": function ( data, type, row ) {

						texto='<td>';
							texto+='<a href="editar_configuracion/'+(row[0])+'" type="button"'; 
							texto+=' class="btn btn-warning btn-sm btn-block" >';
								texto+=' <span class="glyphicon glyphicon-edit"></span>';
							texto+=' </a>';
						texto+='</td>';


							return texto;	
		                },
		                "targets": 3
		            },

		            
		            {
		                "render": function ( data, type, row ) {

	   							texto='	<fieldset disabled> <td>';								
									texto+=' <a href="#"'; 
									texto+=' class="btn btn-danger btn-sm btn-block">';
									texto+=' <span class="glyphicon glyphicon-remove"></span>';
									texto+=' </a>';
								texto+=' </td></fieldset>';	

							return texto;	
		                },
		                "targets": 4
		            },

	            
		           
		            
		        ],
	});	


////////////////////////////////////////////////////////////////////////////////////
	jQuery('#tabla_cat_proveedores').dataTable( {
	
	  "pagingType": "full_numbers",
		
		"processing": true,
		"serverSide": true,
		"ajax": {
	            	"url" : "procesando_cat_proveedores",
	         		"type": "POST",
	         		
	     },   

		"language": {  //tratamiento de lenguaje
			"lengthMenu": "Mostrar _MENU_ registros por página",
			"zeroRecords": "No hay registros",
			"info": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
			"infoEmpty": "No hay registros disponibles",
			"infoFiltered": "(Mostrando _TOTAL_ de _MAX_ registros totales)",  
			"emptyTable":     "No hay registros",
			"infoPostFix":    "",
			"thousands":      ",",
			"loadingRecords": "Leyendo...",
			"processing":     "Procesando...",
			"search":         "Buscar:",
			"paginate": {
				"first":      "Primero",
				"last":       "Último",
				"next":       "Siguiente",
				"previous":   "Anterior"
			},
			"aria": {
				"sortAscending":  ": Activando para ordenar columnas ascendentes",
				"sortDescending": ": Activando para ordenar columnas descendentes"
			},
		},


		"columnDefs": [
			    	
			    	{ 
		                "render": function ( data, type, row ) {
		                		return row[1];
		                },
		                "targets": [0] //,2,3,4
		            },

			    	{ 
		                "render": function ( data, type, row ) {
		                		return row[2];
		                },
		                "targets": [1] //,2,3,4
		            },


			    	{ 
		                "render": function ( data, type, row ) {
		                		return row[3];
		                },
		                "targets": [2] //,2,3,4
		            },		


			    	{ 
		                "render": function ( data, type, row ) {
		                		return row[4];
		                },
		                "targets": [3] //,2,3,4
		            },		            	   

		            {
		                "render": function ( data, type, row ) {

						   return row[6];	
		                },
		                 "targets": 4
		            },			             

		            {
		                "render": function ( data, type, row ) {

						texto='<td>';
							texto+='<a href="editar_proveedor/'+(row[1])+'" type="button"'; 
							texto+=' class="btn btn-warning btn-sm btn-block" >';
								texto+=' <span class="glyphicon glyphicon-edit"></span>';
							texto+=' </a>';
						texto+='</td>';


							return texto;	
		                },
		                "targets": 5
		            },

		            
		            {
		                "render": function ( data, type, row ) {

		                	if (row[5]==0) {
	   							texto='	<td>';								
									texto+=' <a href="eliminar_proveedor/'+(row[1])+'/'+jQuery.base64.encode(row[2])+ '"'; 
									texto+=' class="btn btn-danger btn-sm btn-block" data-toggle="modal" data-target="#modalMessage">';
									texto+=' <span class="glyphicon glyphicon-remove"></span>';
									texto+=' </a>';
								texto+=' </td>';	
		                	} else {
	   							texto='	<fieldset disabled> <td>';								
									texto+=' <a href="#"'; 
									texto+=' class="btn btn-danger btn-sm btn-block">';
									texto+=' <span class="glyphicon glyphicon-remove"></span>';
									texto+=' </a>';
								texto+=' </td></fieldset>';	
	                		
		                	}
									


							return texto;	
		                },
		                "targets": 6
		            },

	            
		           
		            
		        ],
	});	






	jQuery('#tabla_cat_calidades').dataTable( {
	
	  "pagingType": "full_numbers",
		
		"processing": true,
		"serverSide": true,
		"ajax": {
	            	"url" : "procesando_cat_calidades",
	         		"type": "POST",
	         		
	     },   

		"language": {  //tratamiento de lenguaje
			"lengthMenu": "Mostrar _MENU_ registros por página",
			"zeroRecords": "No hay registros",
			"info": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
			"infoEmpty": "No hay registros disponibles",
			"infoFiltered": "(Mostrando _TOTAL_ de _MAX_ registros totales)",  
			"emptyTable":     "No hay registros",
			"infoPostFix":    "",
			"thousands":      ",",
			"loadingRecords": "Leyendo...",
			"processing":     "Procesando...",
			"search":         "Buscar:",
			"paginate": {
				"first":      "Primero",
				"last":       "Último",
				"next":       "Siguiente",
				"previous":   "Anterior"
			},
			"aria": {
				"sortAscending":  ": Activando para ordenar columnas ascendentes",
				"sortDescending": ": Activando para ordenar columnas descendentes"
			},
		},


		"columnDefs": [
			    	
			    	{ 
		                "render": function ( data, type, row ) {
		                		return row[1];
		                },
		                "targets": [0] //,2,3,4
		            },

			    

		            {
		                "render": function ( data, type, row ) {

						texto='<td>';
							texto+='<a href="editar_calidad/'+(row[0])+'" type="button"'; 
							texto+=' class="btn btn-warning btn-sm btn-block" >';
								texto+=' <span class="glyphicon glyphicon-edit"></span>';
							texto+=' </a>';
						texto+='</td>';


							return texto;	
		                },
		                "targets": 1
		            },

		            
		            {
		                "render": function ( data, type, row ) {

		                	if (row[2]==0) {
	   							texto='	<td>';								
									texto+=' <a href="eliminar_calidad/'+(row[0])+'/'+jQuery.base64.encode(row[1])+ '"'; 
									texto+=' class="btn btn-danger btn-sm btn-block" data-toggle="modal" data-target="#modalMessage">';
									texto+=' <span class="glyphicon glyphicon-remove"></span>';
									texto+=' </a>';
								texto+=' </td>';	
		                	} else {
	   							texto='	<fieldset disabled> <td>';								
									texto+=' <a href="#"'; 
									texto+=' class="btn btn-danger btn-sm btn-block">';
									texto+=' <span class="glyphicon glyphicon-remove"></span>';
									texto+=' </a>';
								texto+=' </td></fieldset>';	
	                		
		                	}
									


							return texto;	
		                },
		                "targets": 2
		            },
		           
		            
		        ],
	});	







	jQuery('#tabla_cat_composiciones').dataTable( {
	
	  "pagingType": "full_numbers",
		
		"processing": true,
		"serverSide": true,
		"ajax": {
	            	"url" : "procesando_cat_composiciones",
	         		"type": "POST",
	         		
	     },   

		"language": {  //tratamiento de lenguaje
			"lengthMenu": "Mostrar _MENU_ registros por página",
			"zeroRecords": "No hay registros",
			"info": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
			"infoEmpty": "No hay registros disponibles",
			"infoFiltered": "(Mostrando _TOTAL_ de _MAX_ registros totales)",  
			"emptyTable":     "No hay registros",
			"infoPostFix":    "",
			"thousands":      ",",
			"loadingRecords": "Leyendo...",
			"processing":     "Procesando...",
			"search":         "Buscar:",
			"paginate": {
				"first":      "Primero",
				"last":       "Último",
				"next":       "Siguiente",
				"previous":   "Anterior"
			},
			"aria": {
				"sortAscending":  ": Activando para ordenar columnas ascendentes",
				"sortDescending": ": Activando para ordenar columnas descendentes"
			},
		},


		"columnDefs": [
			    	
			    	{ 
		                "render": function ( data, type, row ) {
		                		return row[1];
		                },
		                "targets": [0] //,2,3,4
		            },

			    

		            {
		                "render": function ( data, type, row ) {

						texto='<td>';
							texto+='<a href="editar_composicion/'+(row[0])+'" type="button"'; 
							texto+=' class="btn btn-warning btn-sm btn-block" >';
								texto+=' <span class="glyphicon glyphicon-edit"></span>';
							texto+=' </a>';
						texto+='</td>';


							return texto;	
		                },
		                "targets": 1
		            },

		            
		            {
		                "render": function ( data, type, row ) {

		                	if (row[2]==0) {
	   							texto='	<td>';								
									texto+=' <a href="eliminar_composicion/'+(row[0])+'/'+jQuery.base64.encode(row[1])+ '"'; 
									texto+=' class="btn btn-danger btn-sm btn-block" data-toggle="modal" data-target="#modalMessage">';
									texto+=' <span class="glyphicon glyphicon-remove"></span>';
									texto+=' </a>';
								texto+=' </td>';	
		                	} else {
	   							texto='	<fieldset disabled> <td>';								
									texto+=' <a href="#"'; 
									texto+=' class="btn btn-danger btn-sm btn-block">';
									texto+=' <span class="glyphicon glyphicon-remove"></span>';
									texto+=' </a>';
								texto+=' </td></fieldset>';	
	                		
		                	}
									


							return texto;	
		                },
		                "targets": 2
		            },
		           
		            
		        ],
	});	





	jQuery('#tabla_cat_cargadores').dataTable( {
	
	  "pagingType": "full_numbers",
		
		"processing": true,
		"serverSide": true,
		"ajax": {
	            	"url" : "procesando_cat_cargadores",
	         		"type": "POST",
	         		
	     },   

		"language": {  //tratamiento de lenguaje
			"lengthMenu": "Mostrar _MENU_ registros por página",
			"zeroRecords": "No hay registros",
			"info": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
			"infoEmpty": "No hay registros disponibles",
			"infoFiltered": "(Mostrando _TOTAL_ de _MAX_ registros totales)",  
			"emptyTable":     "No hay registros",
			"infoPostFix":    "",
			"thousands":      ",",
			"loadingRecords": "Leyendo...",
			"processing":     "Procesando...",
			"search":         "Buscar:",
			"paginate": {
				"first":      "Primero",
				"last":       "Último",
				"next":       "Siguiente",
				"previous":   "Anterior"
			},
			"aria": {
				"sortAscending":  ": Activando para ordenar columnas ascendentes",
				"sortDescending": ": Activando para ordenar columnas descendentes"
			},
		},


		"columnDefs": [
			    	
			    	{ 
		                "render": function ( data, type, row ) {
		                		return row[1];
		                },
		                "targets": [0] //,2,3,4
		            },

			    

		            {
		                "render": function ( data, type, row ) {

						texto='<td>';
							texto+='<a href="editar_cargador/'+(row[0])+'" type="button"'; 
							texto+=' class="btn btn-warning btn-sm btn-block" >';
								texto+=' <span class="glyphicon glyphicon-edit"></span>';
							texto+=' </a>';
						texto+='</td>';


							return texto;	
		                },
		                "targets": 1
		            },

		            
		            {
		                "render": function ( data, type, row ) {

		                	if (row[3]==0) {
	   							texto='	<td>';								
									texto+=' <a href="eliminar_cargador/'+(row[0])+'/'+jQuery.base64.encode(row[1])+ '"'; 
									texto+=' class="btn btn-danger btn-sm btn-block" data-toggle="modal" data-target="#modalMessage">';
									texto+=' <span class="glyphicon glyphicon-remove"></span>';
									texto+=' </a>';
								texto+=' </td>';	
		                	} else {
	   							texto='	<fieldset disabled> <td>';								
									texto+=' <a href="#"'; 
									texto+=' class="btn btn-danger btn-sm btn-block">';
									texto+=' <span class="glyphicon glyphicon-remove"></span>';
									texto+=' </a>';
								texto+=' </td></fieldset>';	
	                		
		                	}
									


							return texto;	
		                },
		                "targets": 2
		            },
		           
		            
		        ],
	});	




	jQuery('#tabla_cat_colores').dataTable( {
	
	  "pagingType": "full_numbers",
		
		"processing": true,
		"serverSide": true,
		"ajax": {
	            	"url" : "procesando_cat_colores",
	         		"type": "POST",
	         		
	     },   

		"language": {  //tratamiento de lenguaje
			"lengthMenu": "Mostrar _MENU_ registros por página",
			"zeroRecords": "No hay registros",
			"info": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
			"infoEmpty": "No hay registros disponibles",
			"infoFiltered": "(Mostrando _TOTAL_ de _MAX_ registros totales)",  
			"emptyTable":     "No hay registros",
			"infoPostFix":    "",
			"thousands":      ",",
			"loadingRecords": "Leyendo...",
			"processing":     "Procesando...",
			"search":         "Buscar:",
			"paginate": {
				"first":      "Primero",
				"last":       "Último",
				"next":       "Siguiente",
				"previous":   "Anterior"
			},
			"aria": {
				"sortAscending":  ": Activando para ordenar columnas ascendentes",
				"sortDescending": ": Activando para ordenar columnas descendentes"
			},
		},


		"columnDefs": [
			    	
			    	{ 
		                "render": function ( data, type, row ) {
		                		return row[1];
		                },
		                "targets": [0] //,2,3,4
		            },

			    	{ 
		                "render": function ( data, type, row ) {
		                		return row[2];
		                },
		                "targets": [1] //,2,3,4
		            },


		            {
		                "render": function ( data, type, row ) {

						texto='<td>';
							texto+='<a href="editar_color/'+(row[0])+'" type="button"'; 
							texto+=' class="btn btn-warning btn-sm btn-block" >';
								texto+=' <span class="glyphicon glyphicon-edit"></span>';
							texto+=' </a>';
						texto+='</td>';



							return texto;	
		                },
		                "targets": 2
		            },

		            
		            {
		                "render": function ( data, type, row ) {

		                	if (row[4]==0) {
	   							texto='	<td>';								
									texto+=' <a href="eliminar_color/'+(row[0])+'/'+jQuery.base64.encode(row[1])+'/'+jQuery.base64.encode(row[3])+ '"'; 
									texto+=' class="btn btn-danger btn-sm btn-block" data-toggle="modal" data-target="#modalMessage">';
									texto+=' <span class="glyphicon glyphicon-remove"></span>';
									texto+=' </a>';
								texto+=' </td>';	
		                	} else {
	   							texto='	<fieldset disabled> <td>';								
									texto+=' <a href="#"'; 
									texto+=' class="btn btn-danger btn-sm btn-block">';
									texto+=' <span class="glyphicon glyphicon-remove"></span>';
									texto+=' </a>';
								texto+=' </td></fieldset>';	
	                		
		                	}
									


							return texto;	
		                },
		                "targets": 3
		            },
		           
		            
		        ],
	});	



});

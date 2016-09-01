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

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////TRASPASO///////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
var arr_general_traspaso = ['Traspaso', 'Proceso','Almacén', 'Fecha', 'Motivo',  'Número',  'Responsable','Dependencia','Detalle']; //
var arr_traspaso_historico_detalle = ['Código', 'Producto', 'Color', 'Cantidad', 'Ancho', 'Precio', 'Lote','No. de Partida','Almacén','Tipo factura'];




jQuery('#id_almacen_modulo').change(function(e) {
	comienzo=true; //para indicar que start comience en 0;
	var oTable =jQuery('#tabla_entrada_traspaso').dataTable();
	oTable._fnAjaxUpdate();		
});

jQuery('#id_tipo_factura_traspaso').change(function(e) {
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
					.column( 11 )
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					} );
				total_kilogramo = api
					.column( 12)
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					} );
				total_pieza = (end-start);	

			        jQuery('#pieza').html( 'Total de piezas:'+ total_pieza);
			        jQuery('#kg').html( 'Total de kgs:'+number_format(total_kilogramo, 2, '.', ','));
			        jQuery('#metro').html('Total de mts:'+ number_format(total_metro, 2, '.', ','));

		} else 	{
			        jQuery('#pieza').html('Total de piezas: 0');
			        jQuery('#metro').html('Total de mts: 0.00');
					jQuery('#kg').html('Total de kgs: 0.00');	

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
	                 "targets": [12,13,14]
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
		    jQuery('#total_pieza').html( 'Total de piezas:'+ settings.json.totales.pieza);
		  
			jQuery('#total_kg').html( 'Total de kgs:'+number_format(settings.json.totales.kilogramo, 2, '.', ','));
			jQuery('#total_metro').html('Total de mts:'+ number_format(settings.json.totales.metro, 2, '.', ','));

		} else {
		    jQuery('#total_pieza').html( 'Total de piezas: 0');
			jQuery('#total_kg').html( 'Total de kgs: 0.00');
			jQuery('#total_metro').html('Total de mts: 0.00');

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
					.column( 11 )
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					} );
				total_kilogramo = api
					.column( 12)
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					} );
				total_pieza = (end-start);	

			        jQuery('#pieza').html( 'Total de piezas:'+ total_pieza);
			        jQuery('#kg').html( 'Total de kgs:'+number_format(total_kilogramo, 2, '.', ','));
			        jQuery('#metro').html('Total de mts:'+ number_format(total_metro, 2, '.', ','));

		} else 	{
			        jQuery('#pieza').html('Total de piezas: 0');
			        jQuery('#metro').html('Total de mts: 0.00');
					jQuery('#kg').html('Total de kgs: 0.00');	

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
	                 "targets": [12,13,14]
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

						
						    abrir('POST', 'imprimir_detalle_traspaso_post', {
						    			datos: JSON.stringify(datos),
						    }, '_blank' );							
						    
							spinner.stop();
							jQuery('#foo').css('display','none');
							window.location.href = '/';

								
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


jQuery('body').on('click','#proc_traspaso11111', function (e) {

	//comentario = jQuery("#comentario").val();
	//factura = jQuery("#factura").val();
	id_almacen = jQuery("#id_almacen_modulo").val();

	//movimiento = jQuery("#movimiento").val();
	
	id_tipo_factura = jQuery("#id_tipo_factura_traspaso").val();
	//d.id_tipo_factura_inversa 
    id_destino= (jQuery("#id_tipo_factura_traspaso").val()==2) ? 1: 2;
	//id del producto
	identificador = (jQuery(this).attr('identificador'));


	 var url = 'confirmar_salida_sino';

	    var arreglo_peso = [];
	    var arreglo = {};

	   jQuery("#tabla_salida tbody tr td input.peso_real").each(function(e) {
	   		arreglo = {};
	   		arreglo["id"] = jQuery(this).attr('identificador') ;  
	   		arreglo['peso_real'] = jQuery(this).val();
	   		arreglo_peso.push( arreglo);
	   });

	
	jQuery('#foo').css('display','block');
	var spinner = new Spinner(opts).spin(target);

	jQuery.ajax({
		        url : url,
		        type : 'POST',
		       	data : { 
		        	id_cliente: id_cliente,
		        	id_cargador: id_cargador,
		        	factura: factura,
		        	arreglo_peso:arreglo_peso,
		        	id_destino:id_destino,
		        	id_almacen:id_almacen,
		        	id_tipo_pedido:id_tipo_pedido,
		        	id_tipo_factura:id_tipo_factura
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
									        url : 'conteo_tienda',
									        data : { 
									        	tipo: 'tienda',
									        },
									        type : 'POST',
									        dataType : 'json',
									        success : function(dato) {	
									        	MY_Socket.sendNewPost(dato.vendedor+' - '+dato.tienda,'proc_salida');

												valor= jQuery.base64.encode(data.valor);

												var url = "pro_salida/"+valor+'/'+data.id_cliente+'/'+jQuery.base64.encode(id_almacen)+'/'+jQuery.base64.encode(id_tipo_pedido)+'/'+jQuery.base64.encode(id_tipo_factura);
											
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
	                "targets": [0,1,2,3,4,5,6,7,8,12],
	            },


    			{ 
	                 "visible": false,
	                "targets": [9,10,11,13],
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
	                "targets": [0,1,2,3,4,5,6,7,8,12],
	            },


    			{ 
	                 "visible": false,
	                "targets": [9,10,11,13],
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
	                "targets": [0,1,2,3,4,5,6,7,8,12],
	            },


    			{ 
	                 "visible": false,
	                "targets": [9,10,11,13],
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

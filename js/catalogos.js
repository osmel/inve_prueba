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



/////////////////////////////////////////REGILLA PRINCIPAL/////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/////////////////////////////////////////////////ctas por pagar/////////////////////////////////////////////////////////////////

/*
ctas_vencida
ctas_ctasxpagar
ctas_pagadas
cuentas
*/

	jQuery('#tabla_ctas_vencidas').dataTable( {
	
	  "pagingType": "full_numbers",
		
		"processing": true,
		"serverSide": true,
		"ajax": {
	            	"url" : "procesando_ctas_vencidas",
	         		"type": "POST",
	         		 "data": function ( d ) {
	         		 	d.id_operacion=1;

						var fecha = (jQuery('.fecha_historicos[vista="ctas_vencida"]').val()).split(' / ');
						d.fecha_inicial2 = fecha[0];
						d.fecha_final2 = fecha[1];


						var fecha = (jQuery('.fecha_historicos[vista="cuentas"]').val()).split(' / ');
						d.fecha_inicial = fecha[0];
						d.fecha_final = fecha[1];

						d.id_almacen = jQuery("#id_almacen_historicos").val(); 
					    d.id_factura = jQuery("#id_factura_historicos").val(); 	
					    d.proveedor = jQuery("#editar_proveedor_historico").val(); 	   
				  


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

			if ( jQuery('#config_entrada_activo').val() == 0 ) {
				api.column(6).visible(false);		
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
		                "targets": [0,1,2,3,4,5,6,7,8,9,10] 
		            },

     				 {
		                "render": function ( data, type, row ) {


						$otro_retorno="listado_ctasxpagar";

		        		texto='<td>';
							texto+='<a style="padding: 1px 0px 1px 0px;"';
							texto+=' href="procesar_ctasxpagar/'+jQuery.base64.encode(row[0])+'/'+jQuery.base64.encode($otro_retorno)+'/'+jQuery.base64.encode(row[14])+'"'; //
							texto+='type="button" class="btn btn-warning btn-block">';
							texto+=row[11];
							texto+='</a>';
						texto+='</td>';

							return texto;	
		                },
		                "targets": 11
		            },
		            {
		                "render": function ( data, type, row ) {


						$otro_retorno="listado_ctasxpagar";
		        		texto='<td>';
							texto+='<a style="padding: 1px 0px 1px 0px;"';
							texto+=' href="procesar_entradas/'+jQuery.base64.encode(row[0])+'/'+jQuery.base64.encode(0)+'/'+jQuery.base64.encode($otro_retorno)+'/'+jQuery.base64.encode(row[14])+'/'+jQuery.base64.encode(row[15])+'"';
							texto+='type="button" class="btn btn-success btn-block">';
							texto+='Detalles';
							texto+='</a>';
						texto+='</td>';



							return texto;	
		                },
		                "targets": 12
		            },
  					
  					{ 
		                 "visible": false,
		                "targets": [13,14]
		            }
		            
		          
		           
		            
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
						var fecha = (jQuery('.fecha_historicos[vista="ctas_ctasxpagar"]').val()).split(' / ');
						d.fecha_inicial2 = fecha[0];
						d.fecha_final2 = fecha[1];

	         		 	var fecha = (jQuery('.fecha_historicos[vista="cuentas"]').val()).split(' / ');
						d.fecha_inicial = fecha[0];
						d.fecha_final = fecha[1];	      
						d.id_almacen = jQuery("#id_almacen_historicos").val(); 
					    d.id_factura = jQuery("#id_factura_historicos").val(); 	   		 	
					    d.proveedor = jQuery("#editar_proveedor_historico").val(); 	   
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
						jQuery('#subtotal2').html('SubTotal:'+ number_format(total_subtotal, 2, '.', ','));
						jQuery('#iva2').html('IVA:' + number_format( total_iva, 2, '.', ','));
						jQuery('#total2').html('Total:'+ number_format(total, 2, '.', ','));	

			} else 	{
						//importes
						jQuery('#subtotal2').html('SubTotal: 0.00');	
						jQuery('#iva2').html('IVA: 0.00');	
						jQuery('#total2').html('Total: 0.00');										


			}	

			if ( jQuery('#config_entrada_activo').val() == 0 ) {
				api.column(6).visible(false);		
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
		                "targets": [0,1,2,3,4,5,6,7,8,9,10] 
		            },
     				 {
		                "render": function ( data, type, row ) {


						$otro_retorno="listado_ctasxpagar";
		        		texto='<td>';
							texto+='<a style="padding: 1px 0px 1px 0px;"';
							texto+=' href="procesar_ctasxpagar/'+jQuery.base64.encode(row[0])+'/'+jQuery.base64.encode($otro_retorno)+'/'+jQuery.base64.encode(row[14])+'"'; //
							texto+='type="button" class="btn btn-warning btn-block">';
							texto+=row[11];
							texto+='</a>';
						texto+='</td>';
							return texto;	
		                },
		                "targets": 11
		            },
		            {
		                "render": function ( data, type, row ) {


						$otro_retorno="listado_ctasxpagar";
		        		texto='<td>';
							texto+='<a style="padding: 1px 0px 1px 0px;"';
							texto+=' href="procesar_entradas/'+jQuery.base64.encode(row[0])+'/'+jQuery.base64.encode(0)+'/'+jQuery.base64.encode($otro_retorno)+'/'+jQuery.base64.encode(row[14])+'/'+jQuery.base64.encode(row[15])+'"'
							texto+='type="button" class="btn btn-success btn-block">';
							texto+='Detalles';
							texto+='</a>';
						texto+='</td>';



							return texto;	
		                },
		                "targets": 12
		            },
  					
  					{ 
		                 "visible": false,
		                "targets": [13,14]
		            }
		            
		          
		           
		            
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
						var fecha = (jQuery('.fecha_historicos[vista="ctas_pagadas"]').val()).split(' / ');
						d.fecha_inicial2 = fecha[0];
						d.fecha_final2 = fecha[1];	
							         		 	
						var fecha = (jQuery('.fecha_historicos[vista="cuentas"]').val()).split(' / ');
						d.fecha_inicial = fecha[0];
						d.fecha_final = fecha[1];	
						d.id_almacen = jQuery("#id_almacen_historicos").val(); 
					    d.id_factura = jQuery("#id_factura_historicos").val(); 	         		 	
					    d.proveedor = jQuery("#editar_proveedor_historico").val(); 	   
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
						jQuery('#subtotal3').html('SubTotal:'+ number_format(total_subtotal, 2, '.', ','));
						jQuery('#iva3').html('IVA:' + number_format( total_iva, 2, '.', ','));
						jQuery('#total3').html('Total:'+ number_format(total, 2, '.', ','));	

			} else 	{
						//importes
						jQuery('#subtotal3').html('SubTotal: 0.00');	
						jQuery('#iva3').html('IVA: 0.00');	
						jQuery('#total3').html('Total: 0.00');										


			}	
			if ( jQuery('#config_entrada_activo').val() == 0 ) {
				api.column(6).visible(false);		
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
		                "targets": [0,1,2,3,4,5,6,7,8,9] 
		            },

     				 {
		                "render": function ( data, type, row ) {


						$otro_retorno="listado_ctasxpagar";
		        		
						if (row[12]!=2) {
			        		texto='<td>';
								texto+='<a style="padding: 1px 0px 1px 0px;"';
								texto+=' href="procesar_ctasxpagar/'+jQuery.base64.encode(row[0])+'/'+jQuery.base64.encode($otro_retorno)+'/'+jQuery.base64.encode(row[14])+'"'; //
								texto+='type="button" class="btn btn-warning btn-block">';
								texto+=row[13]; //"Pagado"; //"row[10];
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
		                "targets": 10
		            },
		            {
		                "render": function ( data, type, row ) {


						$otro_retorno="listado_ctasxpagar";
		        		texto='<td>';
							texto+='<a style="padding: 1px 0px 1px 0px;"';
							texto+=' href="procesar_entradas/'+jQuery.base64.encode(row[0])+'/'+jQuery.base64.encode(0)+'/'+jQuery.base64.encode($otro_retorno)+'/'+jQuery.base64.encode(row[14])+'/'+jQuery.base64.encode(row[15])+'"';
							texto+='type="button" class="btn btn-success btn-block">';
							texto+='Detalles';
							texto+='</a>';
						texto+='</td>';



							return texto;	
		                },
		                "targets": 11
		            },
  					
  					{ 
		                 "visible": false,
		                "targets": [12,13,14]
		            }
		        ],

	 "rowCallback": function( row, data ) {
		    
		    if ( data[13] == data[9] ) { //monto=total -->normal
		      //jQuery('td', row).addClass( "danger" );
		    }

		    if ( data[11] < 0 ) { //se pago de mas
		      jQuery('td', row).addClass( "danger" );
		    }

		    if ( (data[11] == 0 ) &&  ( data[13] != data[9] ) ) { //se pago de mas
		      jQuery('td', row).addClass( "warning" );
		    }



		    /*if ( data[11] == "morado" ) {
		      jQuery('td', row).addClass( "success" );
		    }

		    if ( data[15] == 1 ) {
		      jQuery('td', row).addClass( "warning" );
		    }*/



		  },		



	});	


/////////////////////////////////////////Detalle Monto/////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

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
	         		 	d.id_factura=jQuery("#id_factura").val();
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
					jQuery('#etiq_almacen').val( settings.json.totales.almacen); //
					jQuery('#etiq_proveedor').val( settings.json.totales.nombre);

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

				if (settings.json.recordsTotal==0) {
						jQuery("#disa_pagosrealizado").attr('disabled', true);					
					} else {
						jQuery("#disa_pagosrealizado").attr('disabled', false);					
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
						if (row[10]!=0) { //si esta autorizado a eliminar
							texto='<td>';
								texto+='<a href="/editar_pago_realizado/'+jQuery.base64.encode(row[5])+'/'+jQuery.base64.encode(row[8])+'/'+jQuery.base64.encode(row[9])+'" type="button"'; 
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




							return "textoaa";	
		                },
		                "targets": 5
		            },
		            {
		                "render": function ( data, type, row ) {


	                	if (row[11]!=0) { //si esta autorizado a eliminar
	                	
							texto='<td><a href="/eliminar_pago/'+jQuery.base64.encode(row[5])+'/'+jQuery.base64.encode(row[1])+'/'+jQuery.base64.encode(row[8])+'/'+jQuery.base64.encode(row[9])+'" '; 
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


////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////Imprimir detalles de los "pagos realizados"//////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
jQuery('body').on('click','.impresion_ctas_detalle', function (e) {
  	    busqueda    = jQuery('input[type=search]').val();
		movimiento  = jQuery("#movimiento").val();
		id_factura  = jQuery("#id_factura").val();
    abrir('POST', '/impresion_ctas_detalle', {
    		busqueda  :busqueda,
		  movimiento:movimiento,   			
		  id_factura:id_factura,   		
    }, '_blank' );
});




////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////Impresiones de la regilla principal//////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


  //1- imprimir
jQuery('body').on('click','.impresion_ctas', function (e) {
  	    //busqueda      = jQuery('input[type=search]').val();
  	    busqueda      = jQuery(this).parent().parent().siblings("section").find("input[type=search]").val();
	    extra_search = jQuery(this).attr('tipo'); 
		id_operacion=1;
		var fecha = (jQuery('.fecha_historicos[vista="cuentas"]').val()).split(' / ');
		fecha_inicial = fecha[0];
		fecha_final = fecha[1];
	    id_almacen = jQuery("#id_almacen_historicos").val(); 
	    id_factura = jQuery("#id_factura_historicos").val(); 
		var fecha = (jQuery('.fecha_historicos[tipo="'+extra_search+'"]').val()).split(' / ');
		fecha_inicial2 = fecha[0];
		fecha_final2 = fecha[1];	    
		proveedor = jQuery("#editar_proveedor_historico").val(); 	   


    abrir('POST', 'impresion_ctasxpagar', {
    			busqueda:busqueda,
			extra_search:extra_search,
			id_operacion: id_operacion,
			
			fecha_inicial:fecha_inicial, 
			fecha_final: fecha_final,
			id_almacen:id_almacen,
			id_factura:id_factura,
	    fecha_inicial2:fecha_inicial2,
		  fecha_final2:fecha_final2,
		  proveedor:proveedor,   			

    }, '_blank' );
		        
	
});



//2- imprimir

jQuery('body').on('click','.impresion_ctas_especificas_rapida', function (e) {
  	    busqueda      = jQuery(this).parent().parent().siblings("section").find("input[type=search]").val();
	    extra_search = jQuery(this).attr('tipo'); 
		id_operacion=1;
		var fecha = (jQuery('.fecha_historicos[vista="cuentas"]').val()).split(' / ');
		fecha_inicial = fecha[0];
		fecha_final = fecha[1];
	    id_almacen = jQuery("#id_almacen_historicos").val(); 
	    id_factura = jQuery("#id_factura_historicos").val(); 
		var fecha = (jQuery('.fecha_historicos[tipo="'+extra_search+'"]').val()).split(' / ');
		fecha_inicial2 = fecha[0];
		fecha_final2 = fecha[1];	    
		proveedor = jQuery("#editar_proveedor_historico").val(); 	   


    abrir('POST', 'impresion_ctas_especificas', { //_rapida
    			busqueda:busqueda,
			extra_search:extra_search,
			id_operacion: id_operacion,
			
			fecha_inicial:fecha_inicial, 
			fecha_final: fecha_final,
			id_almacen:id_almacen,
			id_factura:id_factura,
	    fecha_inicial2:fecha_inicial2,
		  fecha_final2:fecha_final2,
		  proveedor:proveedor,   			

    }, '_blank' );
		        
	
});

jQuery('body').on('click','.impresion_ctas_especificas', function (e) {
  	    busqueda      = jQuery(this).parent().parent().siblings("section").find("input[type=search]").val();
	    extra_search = jQuery(this).attr('tipo'); 
		id_operacion=1;
		var fecha = (jQuery('.fecha_historicos[vista="cuentas"]').val()).split(' / ');
		fecha_inicial = fecha[0];
		fecha_final = fecha[1];
	    id_almacen = jQuery("#id_almacen_historicos").val(); 
	    id_factura = jQuery("#id_factura_historicos").val(); 
		var fecha = (jQuery('.fecha_historicos[tipo="'+extra_search+'"]').val()).split(' / ');
		fecha_inicial2 = fecha[0];
		fecha_final2 = fecha[1];	    
		proveedor = jQuery("#editar_proveedor_historico").val(); 	   


    abrir('POST', 'impresion_ctas_especificas', {
    			busqueda:busqueda,
			extra_search:extra_search,
			id_operacion: id_operacion,
			
			fecha_inicial:fecha_inicial, 
			fecha_final: fecha_final,
			id_almacen:id_almacen,
			id_factura:id_factura,
	    fecha_inicial2:fecha_inicial2,
		  fecha_final2:fecha_final2,
		  proveedor:proveedor,   			

    }, '_blank' );
		        
	
});

//3- imprimir
jQuery('body').on('click','.impresion_ctas_detalladas_rapida', function (e) {
  	    busqueda      = jQuery(this).parent().parent().siblings("section").find("input[type=search]").val();
	    extra_search = jQuery(this).attr('tipo'); 
		id_operacion=1;
		var fecha = (jQuery('.fecha_historicos[vista="cuentas"]').val()).split(' / ');
		fecha_inicial = fecha[0];
		fecha_final = fecha[1];
	    id_almacen = jQuery("#id_almacen_historicos").val(); 
	    id_factura = jQuery("#id_factura_historicos").val(); 
		var fecha = (jQuery('.fecha_historicos[tipo="'+extra_search+'"]').val()).split(' / ');
		fecha_inicial2 = fecha[0];
		fecha_final2 = fecha[1];	    
		proveedor = jQuery("#editar_proveedor_historico").val(); 	   


    abrir('POST', 'impresion_ctas_detalladas', { //_rapida
    			busqueda:busqueda,
			extra_search:extra_search,
			id_operacion: id_operacion,
			
			fecha_inicial:fecha_inicial, 
			fecha_final: fecha_final,
			id_almacen:id_almacen,
			id_factura:id_factura,
	    fecha_inicial2:fecha_inicial2,
		  fecha_final2:fecha_final2,
		  proveedor:proveedor,   			

    }, '_blank' );
		        
	
});

jQuery('body').on('click','.impresion_ctas_detalladas', function (e) {
  	    busqueda      = jQuery(this).parent().parent().siblings("section").find("input[type=search]").val();
	    extra_search = jQuery(this).attr('tipo'); 
		id_operacion=1;
		var fecha = (jQuery('.fecha_historicos[vista="cuentas"]').val()).split(' / ');
		fecha_inicial = fecha[0];
		fecha_final = fecha[1];
	    id_almacen = jQuery("#id_almacen_historicos").val(); 
	    id_factura = jQuery("#id_factura_historicos").val(); 
		var fecha = (jQuery('.fecha_historicos[tipo="'+extra_search+'"]').val()).split(' / ');
		fecha_inicial2 = fecha[0];
		fecha_final2 = fecha[1];	    
		proveedor = jQuery("#editar_proveedor_historico").val(); 	   


    abrir('POST', 'impresion_ctas_detalladas', {
    			busqueda:busqueda,
			extra_search:extra_search,
			id_operacion: id_operacion,
			
			fecha_inicial:fecha_inicial, 
			fecha_final: fecha_final,
			id_almacen:id_almacen,
			id_factura:id_factura,
	    fecha_inicial2:fecha_inicial2,
		  fecha_final2:fecha_final2,
		  proveedor:proveedor,   			

    }, '_blank' );
		        
	
});

//4- imprimir
jQuery('body').on('click','.impresion_ctas_antiguedad', function (e) {
  	    busqueda      = jQuery(this).parent().parent().siblings("section").find("input[type=search]").val();
	    extra_search = jQuery(this).attr('tipo'); 
		id_operacion=1;
		var fecha = (jQuery('.fecha_historicos[vista="cuentas"]').val()).split(' / ');
		fecha_inicial = fecha[0];
		fecha_final = fecha[1];
	    id_almacen = jQuery("#id_almacen_historicos").val(); 
	    id_factura = jQuery("#id_factura_historicos").val(); 
		var fecha = (jQuery('.fecha_historicos[tipo="'+extra_search+'"]').val()).split(' / ');
		fecha_inicial2 = fecha[0];
		fecha_final2 = fecha[1];	    
		proveedor = jQuery("#editar_proveedor_historico").val(); 	   


    abrir('POST', 'impresion_ctas_antiguedad', {
    			busqueda:busqueda,
			extra_search:extra_search,
			id_operacion: id_operacion,
			
			fecha_inicial:fecha_inicial, 
			fecha_final: fecha_final,
			id_almacen:id_almacen,
			id_factura:id_factura,
	    fecha_inicial2:fecha_inicial2,
		  fecha_final2:fecha_final2,
		  proveedor:proveedor,   			

    }, '_blank' );
		        
	
});

jQuery('body').on('click','.impresion_ctas_antiguedad_rapida', function (e) {
  	    busqueda      = jQuery(this).parent().parent().siblings("section").find("input[type=search]").val();
	    extra_search = jQuery(this).attr('tipo'); 
		id_operacion=1;
		var fecha = (jQuery('.fecha_historicos[vista="cuentas"]').val()).split(' / ');
		fecha_inicial = fecha[0];
		fecha_final = fecha[1];
	    id_almacen = jQuery("#id_almacen_historicos").val(); 
	    id_factura = jQuery("#id_factura_historicos").val(); 
		var fecha = (jQuery('.fecha_historicos[tipo="'+extra_search+'"]').val()).split(' / ');
		fecha_inicial2 = fecha[0];
		fecha_final2 = fecha[1];	    
		proveedor = jQuery("#editar_proveedor_historico").val(); 	   


    abrir('POST', 'impresion_ctas_antiguedad', { //_rapida
    			busqueda:busqueda,
			extra_search:extra_search,
			id_operacion: id_operacion,
			
			fecha_inicial:fecha_inicial, 
			fecha_final: fecha_final,
			id_almacen:id_almacen,
			id_factura:id_factura,
	    fecha_inicial2:fecha_inicial2,
		  fecha_final2:fecha_final2,
		  proveedor:proveedor,   			

    }, '_blank' );
		        
	
});


////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////Exportaciones de la regilla principal//////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



jQuery('body').on('click','.exportar_ctas', function (e) {
  	    //busqueda      = jQuery('input[type=search]').val();
  	    busqueda      = jQuery(this).parent().parent().siblings("section").find("input[type=search]").val();
	    extra_search = jQuery(this).attr('tipo'); 
		id_operacion=1;
		var fecha = (jQuery('.fecha_historicos[vista="cuentas"]').val()).split(' / ');
		fecha_inicial = fecha[0];
		fecha_final = fecha[1];
	    id_almacen = jQuery("#id_almacen_historicos").val(); 
	    id_factura = jQuery("#id_factura_historicos").val(); 
		
		var fecha = (jQuery('.fecha_historicos[tipo="'+extra_search+'"]').val()).split(' / ');
		fecha_inicial2 = fecha[0];
		fecha_final2 = fecha[1];	    
		proveedor = jQuery("#editar_proveedor_historico").val(); 	   
		

    abrir('POST', 'exportar_ctasxpagar', {
    			busqueda:busqueda,
			extra_search:extra_search,
			id_operacion: id_operacion,
			
			fecha_inicial:fecha_inicial, 
			fecha_final: fecha_final,
			id_almacen:id_almacen,
			id_factura:id_factura,
	    fecha_inicial2:fecha_inicial2,
		  fecha_final2:fecha_final2,
		  proveedor:proveedor,   			


    }, '_blank' );
		        
	
});



////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



var hash_url_notif = window.location.pathname;

    if  ( (hash_url_notif=="/gestionar_pedido_compra") || (hash_url_notif=="/cancelado") || (hash_url_notif=="/solicitar_modificacion")
     || (hash_url_notif=="/pendiente_revision") || (hash_url_notif=="/aprobado")) {  	

    		MY_Socket.sendNewPost(' ','notificando_compra');
		}

		/*
	    if  ( (hash_url_notif=="/informe_pendiente") ) {  	
    		MY_Socket.sendAlmacen("1",'salida_almacen');	   
		}*/





jQuery('#tabla_conteo_historico').dataTable( {
 	    "pagingType": "full_numbers",
		"processing": true,
		"serverSide": true,
		"ajax": {
	            	"url" : "/procesando_conteo_historico",
	         		"type": "POST",
	         		 "data": function ( d ) {
					    d.id_almacen = jQuery("#id_almacen").val(); 		
					    	d.modulo = jQuery("#modulo").val(); 				
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
		"columnDefs": [
					{
		                "render": function ( data, type, row ) {
			                if (row[12]=='') {
								return row[1];	
							} else {
								return row[1]+' <br/><b style="color:red;">Nro.</b>'+row[12];										

							}

		                },
		                "targets": 1
		            },	            
			    	{ 
		                "render": function ( data, type, row ) {
		                		return data;
		                },
		                "targets": [0,2,3,4,5] //
		            },
					{
		                "render": function ( data, type, row ) {
							
							modulo= jQuery("#modulo").val(); 
							valor = row[5+parseFloat(modulo)];

							//habilitar = ((modulo == 2) ? '': 'disabled'); 
							habilitar = (( parseFloat(row[11]) + 2 == parseFloat(modulo)) ? '': 'disabled'); 


							texto='<td>'; 

							texto+='<fieldset '+habilitar+'>'; 
								texto+='<input restriccion="entero"  identificador="'+row[10]+'" value="'+valor+'" type="text" class="form-control ttip cantidad" title="Números enteros."  placeholder="entero">';							
							texto+='</fieldset>'; 
							texto+='</td>';
							return texto;	

		                },
		                "targets": 6
		            },	            

		        ],

		"infoCallback": function( settings, start, end, max, total, pre ) {
				cantidad = (settings.aoData.length);
				if(cantidad == 0){
					jQuery("#imp_historico_conteo").css('display','none');
				} else {				
					jQuery("#imp_historico_conteo").css('display','block');
				}	
			return pre
		},	
	});	



//Agregar las estradas a salidas
jQuery('body').on('click','#imprimir_historico_conteo', function (e) {
	  	  busqueda      = jQuery('input[type=search]').val();
		   extra_search = 'reportes_costo'; 

		id_almacen = jQuery("#id_almacen_historicos").val(); 
		id_factura = jQuery("#id_factura_historicos").val(); 
		proveedor = jQuery("#editar_proveedor_historico").val(); 	

		var fecha = (jQuery('.fecha_historicos[vista="tabla_historico_conteo"]').val()).split(' / ');
		fecha_inicial = fecha[0];
		fecha_final = fecha[1];



    abrir('POST', 'generar_historico_inventarios', {
    			busqueda : busqueda,
			extra_search : extra_search,			
			  id_almacen : id_almacen,
			  id_factura : id_factura,

			   proveedor : proveedor, 
		   fecha_inicial : fecha_inicial, 
			 fecha_final : fecha_final,
    }, '_blank' );
});


	jQuery('#tabla_historico_conteo').dataTable( {
	    "pagingType": "full_numbers",
		"processing": true,
		"serverSide": true,
		"ajax": {
	            	"url" : "procesando_historico_conteo",
	         		"type": "POST",
	         		 "data": function ( d ) {

					     d.id_almacen = jQuery("#id_almacen_historicos").val(); 						
					     d.id_factura = jQuery("#id_factura_historicos").val();
						      d.proveedor = jQuery("#editar_proveedor_historico").val(); 	

						      var fecha = (jQuery('.fecha_historicos[vista="tabla_historico_conteo"]').val()).split(' / ');
						d.fecha_inicial = fecha[0];
						  d.fecha_final = fecha[1];					    
	         		 }
	    },   

		"infoCallback": function( settings, start, end, max, total, pre ) {
				cantidad = (settings.aoData.length);
				if(cantidad == 0){
					jQuery("#imp_historico_conteo").css('display','none');
				} else {				
					jQuery("#imp_historico_conteo").css('display','block');
				}	

				//jQuery('#imprimir_historico_conteo').attr('href','/generar_historico_inventarios/'+jQuery.base64.encode(jQuery("#id_almacen_historicos").val()) );   

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
		                "targets": [0] //
		        },
				{
	                "render": function ( data, type, row ) {
						return row[8];	

	                },
	                "targets": 1
	            },		        
				{
	                "render": function ( data, type, row ) {
							texto='<td>';
								texto+='<a style="padding: 1px 0px 1px 0px;"';
								texto+=' href="historico_conteo1/'+jQuery.base64.encode(row[0])+'/'+jQuery.base64.encode(jQuery("#id_almacen_historicos").val())+'"'; //
								texto+='type="button" class="btn btn-warning btn-block">';
								texto+='Detalles';
								texto+='</a>';
							texto+='</td>';

						return texto;	

	                },
	                "targets": 2
	            },
				{
	                "render": function ( data, type, row ) {
							texto='<td>';
								texto+='<a style="padding: 1px 0px 1px 0px;"';
								texto+=' href="historico_conteo2/'+jQuery.base64.encode(row[0])+'/'+jQuery.base64.encode(jQuery("#id_almacen_historicos").val())+'"'; //
								texto+='type="button" class="btn btn-warning btn-block">';
								texto+='Detalles';
								texto+='</a>';
							texto+='</td>';

						return texto;	
	                },
	                "targets": 3
	            },
				{
	                "render": function ( data, type, row ) {
							texto='<td>';
								texto+='<a style="padding: 1px 0px 1px 0px;"';
								texto+=' href="historico_conteo3/'+jQuery.base64.encode(row[0])+'/'+jQuery.base64.encode(jQuery("#id_almacen_historicos").val())+'"'; //
								texto+='type="button" class="btn btn-warning btn-block">';
								texto+='Detalles';
								texto+='</a>';
							texto+='</td>';

						return texto;	

	                },
	                "targets": 4
	            },
				{
	                "render": function ( data, type, row ) {
						return row[1];	
	                },
	                "targets": 5
	            },
				{
	                "render": function ( data, type, row ) {
						return row[2];	
	                },
	                "targets": 6
	            },
				{
	                "render": function ( data, type, row ) {				

						if (row[3]=='-') {
							texto='<fieldset disabled><td><button'; 
							  texto+='type="button" identificador="'+row[0]+'" class="btn btn-success btn-block">'; 
							  texto+=row[3];
							texto+='</button></td></fieldset>';
	                	} else {
							$otro_retorno="historico_conteo";
			        		texto='<td>';
								texto+='<a style="padding: 1px 0px 1px 0px;"';
								texto+=' href="detalle_salidas/'+jQuery.base64.encode(row[3])+'/'+jQuery.base64.encode(row[7])+'/'+jQuery.base64.encode('no')+'/'+jQuery.base64.encode(1)+'/'+jQuery.base64.encode(row[9])+'/'+jQuery.base64.encode("historico_conteo")+'/'+jQuery.base64.encode(row[10])+'"'; //
								texto+='type="button" class="btn btn-success btn-block">';
								texto+=row[3];
								texto+='</a>';
							texto+='</td>';
			            }

	                	return texto;	
	                	//detalle_salidas($id_movimiento=-1,$cliente=-1,$cargador=-1,$id_tipo_pedido,$id_tipo_factura)


	                },
	                "targets": 7
	            },
				{
	                "render": function ( data, type, row ) {
						return row[4];	
	                },
	                "targets": 8
	            },
				{
	                "render": function ( data, type, row ) {
						return row[5];	
	                },
	                "targets": 9
	            },
				{
	                "render": function ( data, type, row ) {
	                	if (row[6]=='-') {
							texto='<fieldset disabled><td><button'; 
							  texto+='type="button" identificador="'+row[0]+'" class="btn btn-success btn-block">'; 
							  texto+=row[6];
							texto+='</button></td></fieldset>';
	                	} else {
	                	
							$otro_retorno="historico_conteo";
			        		texto='<td>';
								texto+='<a style="padding: 1px 0px 1px 0px;"';
								texto+=' href="procesar_entradas/'+jQuery.base64.encode(row[6])+'/'+jQuery.base64.encode(0)+'/'+jQuery.base64.encode($otro_retorno)+'/'+jQuery.base64.encode(row[9])+'/'+jQuery.base64.encode(row[10])+'"';
								texto+='type="button" class="btn btn-success btn-block">';
								texto+=row[6];
								texto+='</a>';
							texto+='</td>';							


	                	}
						return texto;	
	                },
	                "targets": 10
	            },				   		            
		        ],
	});	








		jQuery('#id_almacen_historicos, #id_factura_historicos, #foco_historicos, #id_tipo_factura_historicos, #id_estatuss_historicos').change(function(e) {
					switch(jQuery(this).attr('vista')) {


						case "historico_salida":
							var oTable =jQuery('#tabla_historico_salida').dataTable();
					    	oTable._fnAjaxUpdate();
					        break;

					    case "historico_entrada":
							var oTable =jQuery('#tabla_historico_entrada').dataTable();
					    	oTable._fnAjaxUpdate();
					        break;    

						
						case "tabla_historico_conteo":
							var oTable =jQuery('#tabla_historico_conteo').dataTable();
					    	oTable._fnAjaxUpdate();
					        break;

						case "resumen_conteo":
							var oTable =jQuery('#resumen_conteo').dataTable();
					    	oTable._fnAjaxUpdate();
					        break;

						case "tabla_ajustes":
							var oTable =jQuery('#tabla_ajustes').dataTable();
					    	oTable._fnAjaxUpdate();
					        break;


						case "tabla_conteos":
							var oTable =jQuery('#tabla_conteos').dataTable();
					    	oTable._fnAjaxUpdate();
					        break;
						
						case "tabla_informe_pendiente":

							//var oTable =jQuery('#tabla_informe_pendiente').dataTable();
					    	//oTable._fnAjaxUpdate();		

								jQuery.ajax({
									        url : "/almacen_ajuste_conteo",
									        type : 'POST',
									       	data : { 
									        	id_almacen:jQuery("#id_almacen_historicos").val(),
									        },
									        //dataType : 'json',
									        success : function(data) {													
									        	window.location.href = '/informe_pendiente'; 
									        }

									        
								});	

							


							
							/*
								jQuery('#calidad_existente option:eq(0)').prop('selected', 'selected');
								jQuery('#composicion_existente option:eq(0)').prop('selected', 'selected');
								jQuery('#color_existente option:eq(0)').prop('selected', 'selected');
								jQuery('#producto_existente option:eq(0)').prop('selected', 'selected');
								jQuery('#producto_existente').trigger( "change");
							*/
					        break;

						case "costo_rollo":
							var oTable =jQuery('#tabla_costo_rollo').dataTable();
					    	oTable._fnAjaxUpdate();
					        break;


					    case "listado_traspaso":
					    
							etiqueta = (jQuery(this).val()==2) ? "De Factura a ": "De Remisión a ";
							etiqueta = (jQuery(this).val()==0) ? "" : etiqueta;
							jQuery('#label_factura_traspaso').text(etiqueta+jQuery('#id_tipo_factura_historicos option:selected').text());
 						    var oTable =jQuery('#tabla_general_traspaso').dataTable();
					    	oTable._fnAjaxUpdate();
					    	var oTable =jQuery('#tabla_traspaso_historico').dataTable();
					    	oTable._fnAjaxUpdate();
					        break;


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

jQuery("#producto_existente, #color_existente, #composicion_existente, #calidad_existente, #id_tipo_factura_existente").on('change', function(e) {

		var campo = jQuery(this).attr("name");   
 		 var val_prod = jQuery('#producto_existente option:selected').text();  		
 		 var val_color = jQuery('#color_existente').val();  		  
 		 var val_comp = jQuery('#composicion_existente').val();  	
 		 var val_calida = jQuery('#calidad_existente').val();  		


         var dependencia = jQuery(this).attr("dependencia"); 
         var nombre = jQuery(this).attr("nombre");           //color composicion
        
    	if (dependencia !="") {	    
	        //limpiar la dependencia
	        jQuery("#"+dependencia).html(''); 
	        //cargar la dependencia
	       // console.log(dependencia);
	        cargarDependencia_existente(campo,val_prod,val_color,val_comp,val_calida,dependencia,nombre);
        }


				var oTable =jQuery('#tabla_informe_pendiente').dataTable();
				oTable._fnAjaxUpdate();

     });




	function cargarDependencia_existente(campo,val_prod,val_color,val_comp,val_calida,dependencia,nombre) {
		
		var url = '/cargar_dependencia_compra';	
		//alert(jQuery('#id_tipo_factura_existente').val());
		jQuery.ajax({
		        url : '/cargar_dependencia_existente',
		        data:{
		        	campo:campo,
		        	
		        	val_prod:val_prod,
		        	val_color:val_color,
		        	val_comp:val_comp,
		        	val_calida:val_calida,
		        	id_factura:jQuery('#id_tipo_factura_existente').val(),
		        	proveedor : jQuery("#editar_proveedor_historico").val(),

		        	dependencia:dependencia,
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

					if (dependencia=='producto_existente') {
						//console.log(dependencia);	
						if ( jQuery('#producto_existente option').length >1 ) {
							jQuery('#procesar_conteo').attr('disabled',false);
						} else {
							jQuery('#procesar_conteo').attr('disabled',true);
						}
					}
					

                    return false;
		        },
		        error : function(jqXHR, status, error) {
		        },
		        complete : function(jqXHR, status) {
		            
		        }
		    }); 
	}

	//cuando comienza 
	if ( jQuery('#producto_existente option').length >1 ) {
		jQuery('#procesar_conteo').attr('disabled',false);
	} else {
		jQuery('#procesar_conteo').attr('disabled',true);
	}

///////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////Resumen COnteo///////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////



jQuery('#resumen_conteo').dataTable( {
 	    "pagingType": "full_numbers",
		"processing": true,
		"serverSide": true,
		"ajax": {
	            	"url" : "procesando_resumen_conteo",
	         		"type": "POST",
	         		 "data": function ( d ) {
					    d.id_almacen = jQuery("#id_almacen_historicos").val(); 		
					    	d.modulo = jQuery("#modulo").val(); 				
					 d.modulo_activo = jQuery("#modulo_activo").val(); 				
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
		"columnDefs": [
			    	{ 
		                "render": function ( data, type, row ) {
		                		return data;
		                },
		                "targets": [0,1,2,3] //
		            },
		            	

		        ],

		        
				"infoCallback": function( settings, start, end, max, total, pre ) {
						jQuery("#modulo_activo").val(settings.json.generales.modulo_activo);		

						cant_mod = settings.json.generales.modulo_activo ;
						if (cant_mod>4) {
							cant_mod=7;
						}
						console.log(cant_mod);

						for (var i = 2; i <= cant_mod; i++) {
							jQuery(".l"+i).css('display', 'inline-block');		
						}


						cantidad = (jQuery('#resumen_conteo').dataTable().fnSettings().aoData.length);

						if(cantidad == 0){
								jQuery("#hab_proceso").attr('disabled', true);
						} else {				
							jQuery("#hab_proceso").attr('disabled', false);
						}	

					    return pre
				  	} ,    
				
	});	

///////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////Ajustes///////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////



jQuery('body').on('click','#proc_salida_ajuste', function (e) {

		jQuery('#foo').css('display','block');
		var spinner = new Spinner(opts).spin(target);

	   	id_almacen = jQuery('#id_almacen').val();
		id_tipo_pedido = jQuery("#id_tipo_pedido_salida").val();
		id_tipo_factura = (id_tipo_pedido==2) ? 0:jQuery("#id_tipo_factura_salida").val();



		jQuery.ajax({
		        url : '/procesando_salida_ajuste_definitivo',
		        data : { 
 		        		  		 id_almacen:id_almacen,
							 id_tipo_pedido:id_tipo_pedido, 
							id_tipo_factura:id_tipo_factura, 
		        },
		        type : 'POST',
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
						//borrar el mensaje q quedo	
						jQuery('#foo').css('display','none');
						jQuery('#messages').css('display','none');



								jQuery.ajax({
									        url : '/conteo_tienda',
									        data : { 
									        	tipo: 'tienda',
									        },
									        type : 'POST',
									        dataType : 'json',
									        success : function(dato) {	
									        	MY_Socket.sendNewPost(dato.vendedor+' - '+dato.tienda+' - '+dato.compra,'conf_entrada');
						
												$catalogo = e.target.name;
												//window.location.href = '/procesar_entrar/'+jQuery.base64.encode(data.num_mov)+'/'+jQuery.base64.encode(2); //jQuery("#id_factura").val()
												window.location.href = '/faltante'; //jQuery("#id_factura").val()
									        	
									        }
								});			
								
						
					}		        			        	  
				}
		});	

});



//Agregar las estradas a salidas en el modulo de salida "agregar la regilla de arriba a la regilla inferior"
jQuery('table').on('click','.agregar_ajuste', function (e) {
	
	jQuery(this).attr('disabled', true);		
	identificador = (jQuery(this).attr('identificador')); 
	proveedor = jQuery('.buscar_proveedor').typeahead("val");
	cargador = jQuery('.buscar_cargador').typeahead("val");
	movimiento = jQuery("#movimiento").val();
	factura = jQuery("#factura").val();
	id_almacen = jQuery("#id_almacen").val();
	id_tipo_pedido = jQuery("#id_tipo_pedido_salida").val();
	id_tipo_factura = (id_tipo_pedido==2) ? 0:jQuery("#id_tipo_factura_salida").val();


	//editar_proveedor
	jQuery('#foo').css('display','block');
	var spinner = new Spinner(opts).spin(target);

 
	jQuery.ajax({
		        url : '/agregar_prod_salida',
		        data : { 
		        	identificador: identificador,
		        	id_cliente:proveedor,
		        	id_cargador:cargador,
		        	movimiento: movimiento,
		        	id_almacen: id_almacen,
		        	factura: factura,
		        	id_tipo_pedido: id_tipo_pedido,
		        	id_tipo_factura: id_tipo_factura,
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

							jQuery('#tabla_salida_ajuste').dataTable().fnDraw();
							jQuery('#tabla_entrada_ajuste').dataTable().fnDraw();


								jQuery.ajax({
									        url : '/conteo_tienda',
									        data : { 
									        	tipo: 'tienda',
									        },
									        type : 'POST',
									        dataType : 'json',
									        success : function(data) {	
									        	MY_Socket.sendNewPost(data.vendedor+' - '+data.tienda,'agregar ajustes');
									        	return false;	
									        }
								});	

								
						}
		        }
	});		
	jQuery(this).attr('disabled', false);				        
	
});

jQuery('#tabla_salida_ajuste').dataTable( {

	"processing": true, 	//tratamiento con base de datos
	"serverSide": true,
		"ajax": {
            	"url" : "/procesando_salida_ajuste",
         		"type": "POST",
         		 "data": function ( d ) {
     				
     				   if  (jQuery('.buscar_proveedor').typeahead("val")) {
     				   		d.id_cliente = jQuery('.buscar_proveedor').typeahead("val");
     				   	} else {
     				   		d.id_cliente = jQuery('#id_proveedor').val();	
     				   	}	

						    d.producto_filtro = jQuery('#producto_filtro').val();	
						    d.color_filtro = jQuery('#color_filtro').val();	
						    d.ancho_filtro = jQuery('#ancho_filtro').val();	
						    d.factura_filtro = jQuery('#factura_filtro').val();	
						    d.proveedor_filtro = jQuery('#editar_proveedor_filtro').val();	
						    d.id_almacen = jQuery('#id_almacen').val();	
						    d.modulo = jQuery('#modulo').val();	
							d.id_tipo_pedido = jQuery("#id_tipo_pedido_salida").val();
							d.id_tipo_factura = (d.id_tipo_pedido==2) ? 0:jQuery("#id_tipo_factura_salida").val();

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
	    if ( data[9] == 3 ) {
	      jQuery('td', row).addClass( "danger" );
	    }

	    if ( data[9] == 6 ) {
	      jQuery('td', row).addClass( "success" );
	    }

	  },





"infoCallback": function( settings, start, end, max, total, pre ) {
	    
		cantidad = (jQuery('#tabla_entrada_ajuste').dataTable().fnSettings().aoData.length);

		 

			if(cantidad == 0){
					jQuery("#proc_salida_ajuste").attr('disabled', false);
			} else {				
				jQuery("#proc_salida_ajuste").attr('disabled', true);
			}	
							


	    jQuery('#total_pieza2').html( 'Total de piezas: 0');
	    if (settings.json.totales) {
	    	

		    jQuery('#total_pieza2').html( 'Total de piezas:'+ settings.json.totales.pieza);
			jQuery('#total_kg2').html( 'Total de kgs:'+number_format(settings.json.totales.kilogramo, 2, '.', ','));
			jQuery('#total_metro2').html('Total de mts:'+ number_format(settings.json.totales.metro, 2, '.', ','));
			
		} else {
		    jQuery('#total_pieza2').html( 'Total de piezas: 0');
			jQuery('#total_kg2').html( 'Total de kgs: 0.00');
			jQuery('#total_metro2').html('Total de mts: 0.00');

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

			        jQuery('#pieza2').html( 'Total de piezas:'+ total_pieza);
			        jQuery('#kg2').html( 'Total de kgs:'+number_format(total_kilogramo, 2, '.', ','));
			        jQuery('#metro2').html('Total de mts:'+ number_format(total_metro, 2, '.', ','));

		} else 	{
			        jQuery('#pieza2').html('Total de piezas: 0');
			        jQuery('#metro2').html('Total de mts: 0.00');
					jQuery('#kg2').html('Total de kgs: 0.00');	

		}	
		
    },	  	
	  
	"columnDefs": [
					{
		                "render": function ( data, type, row ) {

  								var color;
		                		switch (row[15]){
		                			case "12": //normal rojo
		                				color = 'red';
		                			   break;	
		                			case "13": //devolucion verde
		                				color = 'green';
		                			   break;
		                			case "14": //defecto azul
		                				color = 'blue';
		                			   break;
		                			case "15": //ajuste naranja
		                				color = 'orange';
		                			   break;
		                			default: 
		                				color = 'red';            
		                		} 

			                if (row[14]=='') {
								return row[1];	
							} else {
								return row[1]+' <br/><b style="color:'+color+';">Nro.</b>'+row[14];										
							}

		                },
		                "targets": 1
		            },		    	

		    	{ 
	                "render": function ( data, type, row ) {
	                		return data;
	                },
	                "targets": [0,2,3,4,5,6,7]
	            },

    			{ 
	                "render": function ( data, type, row ) {
						return row[10];	
	                },
	                "targets": [8]
	            },

            
	            {
	                "render": function ( data, type, row ) {
						texto='<td><button'; 
							texto+='type="button" identificador="'+row[8]+'" class="btn btn-danger btn-block quitar_ajuste">'; 
							 texto+='Quitar';
						texto+='</button></td>';
						return texto;	

	                },
	                "targets": 9
	            },
				{ 
	                 "visible": false,
	                "targets": [10,11,12,13,14,15]
	            }		            
	        ],
});	



//Quitar las salidas y retornarlas a estradas "modulo de salida"
jQuery('table').on('click','.quitar_ajuste', function (e) {

	jQuery(this).attr('disabled', true);				        
	
	identificador = (jQuery(this).attr('identificador'));
	id_tipo_pedido = jQuery("#id_tipo_pedido_salida").val();
	id_tipo_factura = (id_tipo_pedido==2) ? 0:jQuery("#id_tipo_factura_salida").val();

	jQuery.ajax({
		        url : '/quitar_salida_ajuste', //
		        data : { 
		        	identificador: identificador,
		        	id_tipo_pedido: id_tipo_pedido,
		        	id_tipo_factura: id_tipo_factura,
		        },
		        type : 'POST',
		        dataType : 'json',
		        success : function(data) {	
						
						if(data.exito != true){
							//aqui es donde va el mensaje q no se ha copiado
						}else{
							/*
							if(data.total == 0){
								jQuery("#proc_salida_ajuste").attr('disabled', true);
							} else {
								jQuery("#proc_salida_ajuste").attr('disabled', false);
							}*/	

							jQuery('#tabla_entrada_ajuste').dataTable().fnDraw();
							jQuery('#tabla_salida_ajuste').dataTable().fnDraw();

								jQuery.ajax({
									        url : '/conteo_tienda',
									        data : { 
									        	tipo: 'tienda',
									        },
									        type : 'POST',
									        dataType : 'json',
									        success : function(data) {	
									        	MY_Socket.sendNewPost(data.vendedor+' - '+data.tienda,'quitar_ajuste');
									     		return false;
									        }
								});	

							//return false;
						}
						
		        }
	});	

       	jQuery(this).attr('disabled', false);				        

});


jQuery('#tabla_entrada_ajuste').dataTable( {
 	"processing": true, //	//tratamiento con base de datos
	"serverSide": true,
	"ajax": {
            	"url" : "/procesando_servidor_ajustes",
         		"type": "POST",
         		 "data": function ( d ) {
     				   if  (jQuery('.buscar_proveedor').typeahead("val")) {
     				   		d.id_cliente = jQuery('.buscar_proveedor').typeahead("val");
     				   	} else {
     				   		d.id_cliente = jQuery('#id_proveedor').val();	
     				   	}	


						    d.producto_filtro = jQuery('#producto_filtro').val();	
						    


						    d.color_filtro = jQuery('#color_filtro').val();	

						    d.ancho_filtro = jQuery('#ancho_filtro').val();	
						    //alert(d.ancho_filtro);
						    d.factura_filtro = jQuery('#factura_filtro').val();	

						    d.proveedor_filtro = jQuery('#editar_proveedor_filtro').val();	

						    d.id_almacen = jQuery('#id_almacen').val();	
						    d.modulo = jQuery('#modulo').val();	

							d.id_tipo_pedido = jQuery("#id_tipo_pedido_salida").val();
							d.id_tipo_factura = (d.id_tipo_pedido==2) ? 0:jQuery("#id_tipo_factura_salida").val();

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
		    if ( data[9] == 3 ) {
		      jQuery('td', row).addClass( "danger" );
		    }

		    if ( data[9] == 6 ) {
		      jQuery('td', row).addClass( "success" );
		    }

		  },

	"infoCallback": function( settings, start, end, max, total, pre ) {
		cantidad = (jQuery('#tabla_entrada_ajuste').dataTable().fnSettings().aoData.length);
		if(cantidad == 0){
					jQuery("#proc_salida_ajuste").attr('disabled', false);
			} else {				
				jQuery("#proc_salida_ajuste").attr('disabled', true);
			}	
				

	    if (settings.json.totales) {
		    jQuery('#total_pieza').html( 'Total de piezas:'+ settings.json.totales.pieza);
		  
			jQuery('#total_kg').html( 'Total de kgs:'+number_format(settings.json.totales.kilogramo, 2, '.', ','));
			jQuery('#total_metro').html('Total de mts:'+ number_format(settings.json.totales.metro, 2, '.', ','));

		} else {
		    jQuery('#total_pieza').html( 'Total de piezas: 0');
			jQuery('#total_kg').html( 'Total de kgs: 0.00');
			jQuery('#total_metro').html('Total de mts: 0.00');

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
  								var color;
		                		switch (row[14]){
		                			case "12": //normal rojo
		                				color = 'red';
		                			   break;	
		                			case "13": //devolucion verde
		                				color = 'green';
		                			   break;
		                			case "14": //defecto azul
		                				color = 'blue';
		                			   break;
		                			case "15": //ajuste naranja
		                				color = 'orange';
		                			   break;
		                			default: 
		                				color = 'red';            
		                		} 

			                if (row[13]=='') {
								return row[1];	
							} else {
								return row[1]+' <br/><b style="color:'+color+';">Nro.</b>'+row[13];										
							}

		                },
		                "targets": 1
		            },	 	
	    		{ 
	                "render": function ( data, type, row ) {
	                		return data;
	                },
	                "targets": [0,2,3,4,5,6,7]
	            },
    			{ 
	                "render": function ( data, type, row ) {
						return row[10];	
	                },
	                "targets": [8]
	            },

	            {
	                "render": function ( data, type, row ) {
						texto='<td><button '; 
							texto+='type="button" class="btn btn-success btn-block agregar_ajuste '+row[8]+'" identificador="'+row[8]+'" >';
							texto+='<span  class="">Agregar</span>';
						texto+='</button></td>';
						return texto;	
	                },
	                "targets": 9
	            },
				{ 
	                 "visible": false,
	                 "targets": [10,11,12,13,14]
	            }		            
	        ],
});	


jQuery('#tabla_ajustes').dataTable( {
 	    "pagingType": "full_numbers",
		"processing": true,
		"serverSide": true,
		"ajax": {
	            	"url" : "procesando_ajustes",
	         		"type": "POST",
	         		 "data": function ( d ) {
					    d.id_almacen = jQuery("#id_almacen_historicos").val(); 		
					    	d.modulo = jQuery("#modulo").val(); 				
					 d.modulo_activo = jQuery("#modulo_activo").val(); 				
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
		"columnDefs": [

					{
		                "render": function ( data, type, row ) {
			                if (row[13]=='') {
								return row[1];	
							} else {
								return row[1]+' <br/><b style="color:red;">Nro.</b>'+row[13];										
							}

		                },
		                "targets": 1
		            },	 		
			    	{ 
		                "render": function ( data, type, row ) {
		                		return data;
		                },
		                "targets": [0,2,3,4,5] //
		            },

					{
		                "render": function ( data, type, row ) {
							return row[6];	

		                },
		                "targets": 6
		            },	            

					{
		                "render": function ( data, type, row ) {
							return row[12];	

		                },
		                "targets": 7
		            },	            

		        ],



						


				"infoCallback": function( settings, start, end, max, total, pre ) {
						jQuery("#modulo_activo").val(settings.json.generales.modulo_activo);		


						cant_mod = settings.json.generales.modulo_activo ;
						if (cant_mod>4) {
							cant_mod=7;
						}
						//console.log(cant_mod);

						for (var i = 2; i <= cant_mod; i++) {
							jQuery(".l"+i).css('display', 'inline-block');		
						}

						if (settings.aoData.length>0) {
							jQuery("#hab_proceso").attr('disabled', false);			

								if ( jQuery(modulo).val()==5) {
									if  ( settings.json.generales.faltante==2 ) {
										jQuery("#hab_proceso").attr('disabled', true);					
										jQuery('#imp_faltante').css('display','block');
										//  public function generar_salida($id_movimiento,$id_tipo_pedido,$id_tipo_factura,$id_estatus){
										jQuery('#imp_nota_faltante').attr('href','/generar_salida/'+jQuery.base64.encode(settings.json.generales.mov_faltante)+'/'+jQuery.base64.encode(1)+'/'+jQuery.base64.encode(settings.aoData[0]['_aData'][14])+'/'+jQuery.base64.encode(15) );   
										//generar_salida($id_movimiento,$id_tipo_pedido,$id_tipo_factura)
									} else {
										jQuery("#hab_proceso").attr('disabled', false);					
										jQuery('#imp_faltante').css('display','none');
										jQuery('#imp_nota_faltante').attr("href","");   
									}
								}								

								if ( jQuery(modulo).val()==6) {
									if (settings.json.generales.sobrante==2){
										jQuery("#hab_proceso").attr('disabled', true);					
										jQuery('#imp_sobrante').css('display','block');
										
										jQuery('#imp_etiq_rapida').attr('href','/generar_etiquetas_rapida/'+jQuery.base64.encode(settings.json.generales.mov_sobrante)+'/'+jQuery.base64.encode(0)+'/'+jQuery.base64.encode(settings.aoData[0]['_aData'][14])+'/'+jQuery.base64.encode(15) );
										jQuery('#imp_etiq').attr('href','/generar_etiquetas/'+jQuery.base64.encode(settings.json.generales.mov_sobrante)+'/'+jQuery.base64.encode(0)+'/'+jQuery.base64.encode(settings.aoData[0]['_aData'][14])+'/'+jQuery.base64.encode(15) );

										jQuery('#imp_nota_rapida').attr('href','/generar_notas_rapida/'+jQuery.base64.encode(settings.json.generales.mov_sobrante)+'/'+jQuery.base64.encode(0)+'/'+jQuery.base64.encode(settings.aoData[0]['_aData'][14])+'/'+jQuery.base64.encode(15) );  
										jQuery('#imp_nota').attr('href','/generar_notas/'+jQuery.base64.encode(settings.json.generales.mov_sobrante)+'/'+jQuery.base64.encode(0)+'/'+jQuery.base64.encode(settings.aoData[0]['_aData'][14])+'/'+jQuery.base64.encode(15) );  

									} else {
										jQuery("#hab_proceso").attr('disabled', false);					

										jQuery('#imp_sobrante').css('display','none');
										jQuery('#imp_etiq_rapida').attr("href","");   
										jQuery('#imp_etiq').attr("href","");   
										jQuery('#imp_nota_rapida').attr("href","");   
										jQuery('#imp_nota').attr("href","");   
									}
								}	
								

						} else {
							jQuery("#hab_proceso").attr('disabled', true);					
						}

						


				
					    return pre
				  	} ,    
	});	




///////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////sobrantes///////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////


var productos_temporales = ['Código', 'Descripción','Color', 'Lote - No. consecutivo', 'No. de Partida','Medida(Mts)','Ancho(cm)','Precio','Peso Real']; 



jQuery('body').on('click','#conf_entrada_sobrante', function (e) {

		jQuery('#foo').css('display','block');
		var spinner = new Spinner(opts).spin(target);

	   var arreglo_cantidad_um = [];
	   var arreglo_ancho = [];
	   var arreglo_precio = [];
	   var arreglo_peso = [];

	    var arreglo = {};

   		jQuery("#tabla_entrada_sobrante tbody tr td input.cantidad_um").each(function(e) {
	   		arreglo = {};
	   		arreglo["id"] = jQuery(this).attr('identificador');  
	   		arreglo['cantidad_um'] = jQuery(this).val();
	   		arreglo_cantidad_um.push( arreglo);
	   });

   		jQuery("#tabla_entrada_sobrante tbody tr td input.ancho").each(function(e) {
	   		arreglo = {};
	   		arreglo["id"] = jQuery(this).attr('identificador');  
	   		arreglo['ancho'] = jQuery(this).val();
	   		arreglo_ancho.push( arreglo);
	   });

	   jQuery("#tabla_entrada_sobrante tbody tr td input.precio").each(function(e) {
	   		arreglo = {};
	   		arreglo["id"] = jQuery(this).attr('identificador');  
	   		arreglo['precio'] = jQuery(this).val();
	   		arreglo_precio.push( arreglo);
	   });

	   jQuery("#tabla_entrada_sobrante tbody tr td input.peso_real").each(function(e) {
	   		arreglo = {};
	   		arreglo["id"] = jQuery(this).attr('identificador');  
	   		arreglo['peso_real'] = jQuery(this).val();
	   		arreglo_peso.push( arreglo);
	   });



		jQuery.ajax({
		        url : '/validar_proceso_sobrante',
		        data : { 
		        		  		dato: "valor",
							 arreglo_cantidad_um:arreglo_cantidad_um, 
							       arreglo_ancho:arreglo_ancho, 
							      arreglo_precio:arreglo_precio,
							    	arreglo_peso:arreglo_peso,
		        },
		        type : 'POST',
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
						//borrar el mensaje q quedo	
						jQuery('#foo').css('display','none');
						jQuery('#messages').css('display','none');



								jQuery.ajax({
									        url : '/conteo_tienda',
									        data : { 
									        	tipo: 'tienda',
									        },
									        type : 'POST',
									        dataType : 'json',
									        success : function(dato) {	
									        	MY_Socket.sendNewPost(dato.vendedor+' - '+dato.tienda+' - '+dato.compra,'conf_entrada');
						
												$catalogo = e.target.name;
												//window.location.href = '/procesar_entrar/'+jQuery.base64.encode(data.num_mov)+'/'+jQuery.base64.encode(2); //jQuery("#id_factura").val()
												window.location.href = '/sobrante'; //jQuery("#id_factura").val()
									        	
									        }
								});			
								
						
					}		        			        	  
				}
		});	

});



jQuery('#tabla_entrada_sobrante').dataTable( {
	"pagingType": "full_numbers",
	"processing": true,
	"serverSide": true,
	"ajax": {
            	"url" : "/procesando_temporales_sobrante",
         		"type": "POST"
     },   
	"infoCallback": function( settings, start, end, max, total, pre ) {
			if (settings.json.recordsTotal==0) {
				jQuery("#disa_reportes").attr('disabled', true);					
			} else {
				jQuery("#disa_reportes").attr('disabled', false);					
			}
	    return pre
  	} ,    
   "columnDefs": [
   				{ 
		                "render": function ( data, type, row ) {
		                		if (row[17]!='') {
		                			return row[2]+'<br/><b style="color:red;">Cód: </b>'+row[17];	
		                		} else {
		                			return row[2];
		                		}
		                		
		                },
		                "targets": [2]   //el 3 es la imagen q ya viene formada desde el modelo
		        },  
    			{ 
	                "render": function ( data, type, row ) {
						return data;	
	                },
	                "targets": [1,3]
	            },
    			{ 
	                "render": function ( data, type, row ) {
						return row[8];	
	                },
	                "targets": [4]
	            },

    			{ 
	                "render": function ( data, type, row ) {
						return row[9];	
	                },
	                "targets": [5]
	            },


	   
				{
	                "render": function ( data, type, row ) {
						
						//modulo= jQuery("#modulo").val(); 

						texto='<td>'; 
							texto+='<input restriccion="decimal" value="'+row[4]+'" identificador="'+row[0]+'" type="text" class="form-control ttip cantidad_um" title="Números y puntos decimales."  placeholder="0.00">';							
						texto+='</td>';
						return texto;	

	                },
	                "targets": 6
	            },	 
				{
	                "render": function ( data, type, row ) {
						
						//modulo= jQuery("#modulo").val(); 

						texto='<td>'; 
							texto+='<input restriccion="decimal" value="'+row[5]+'" identificador="'+row[0]+'" type="text" class="form-control ttip ancho" title="Números y puntos decimales."  placeholder="0.00">';							
						texto+='</td>';
						return texto;	

	                },
	                "targets": 7
	            },	 	            

				{
	                "render": function ( data, type, row ) {
						
						//modulo= jQuery("#modulo").val(); 

						texto='<td>'; 
							texto+='<input restriccion="decimal" value="'+row[13]+'" identificador="'+row[0]+'" type="text" class="form-control ttip precio" title="Números y puntos decimales."  placeholder="0.00">';							
						texto+='</td>';
						return texto;	

	                },
	                "targets": 8
	            },	 	            
				{
	                "render": function ( data, type, row ) {
						
						//modulo= jQuery("#modulo").val(); 

						texto='<td>'; 
							texto+='<input restriccion="decimal" value="'+row[12]+'" identificador="'+row[0]+'" type="text" class="form-control ttip peso_real" title="Números y puntos decimales."  placeholder="0.00">';							
						texto+='</td>';
						return texto;	

	                },
	                "targets": 9
	            },	            

    			{ 
	                 "visible": false,
	                "targets": [0,10,11,12,13,14,15,16,17] //11,12
	            }

	],	


	"fnHeaderCallback": function( nHead, aData, iStart, iEnd, aiDisplay ) {
		var arreglo =productos_temporales;
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

///////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////conteo físico///////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////



jQuery('body').on('click','#procesar_contando', function (e) {

	jQuery('#foo').css('display','block');
	var spinner = new Spinner(opts).spin(target);

	id_almacen = jQuery('#id_almacen_historicos').val();
	var modulo = jQuery("#modulo").val();
	

	
	 var url = '/procesar_por_conteo';

	    var arreglo_cantidad = [];
	    
	    var arreglo = {};

	   jQuery("#tabla_conteos tbody tr td input.cantidad").each(function(e) { //cant_solicitada
	   		arreglo = {};
	   		arreglo["id"] = jQuery(this).attr('identificador') ;  
	   		arreglo['cantidad'] = jQuery(this).val();
	   		arreglo_cantidad.push( arreglo);
	   });

	jQuery.ajax({
		        url : url,
		        type : 'POST',
		       	data : { 
		        	
		        	id_almacen:id_almacen,		
		        	modulo:modulo,
		        	arreglo_cantidad:arreglo_cantidad,
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

							id_almacen 	= jQuery.base64.encode(jQuery("#id_almacen_historicos").val()); 
								  modulo = jQuery.base64.encode(jQuery("#modulo").val());	
							var url = "/procesar_contando/"+id_almacen+"/"+modulo;

							jQuery('#modalMessage').modal({
								  show:'true',
								remote:url,
							}); 

						}
		        }

		        
	});						        
});


									        	
	jQuery('#tabla_conteos').dataTable( {
 	    //"pagingType": "full_numbers",
 	    "paging": false,
		"processing": true,
		"serverSide": true,
		"ajax": {
	            	"url" : "procesando_conteos",
	         		"type": "POST",
	         		 "data": function ( d ) {
					    d.id_almacen = jQuery("#id_almacen_historicos").val(); 		
					    	d.modulo = jQuery("#modulo").val(); 				
					 d.modulo_activo = jQuery("#modulo_activo").val(); 				
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
		"columnDefs": [
					{
		                "render": function ( data, type, row ) {
								var color;	
		                		switch (row[13]){
		                			case "12": //normal rojo
		                				color = 'red';
		                			   break;	
		                			case "13": //devolucion verde
		                				color = 'green';
		                			   break;
		                			case "14": //defecto azul
		                				color = 'blue';
		                			   break;
		                			case "15": //ajuste naranja
		                				color = 'orange';
		                			   break;
		                			default: 
		                				color = 'red';            
		                		} 

			                if (row[12]=='') {
								return row[1];	
							} else {
								return row[1]+' <br/><b style="color:'+color+';">Nro.</b>'+row[12];										
							}

		                },
		                "targets": 1
		            },	            

			    	{ 
		                "render": function ( data, type, row ) {
		                		return data;
		                },
		                "targets": [0,2,3,4,5] //
		            },

					{
		                "render": function ( data, type, row ) {
							
							modulo= jQuery("#modulo").val(); 
							valor = row[5+parseFloat(modulo)];

							//habilitar = ((modulo == 2) ? '': 'disabled'); 
							habilitar = (( parseFloat(row[11]) + 2 == parseFloat(modulo)) ? '': 'disabled'); 


							texto='<td>'; 

							texto+='<fieldset '+habilitar+'>'; 
								texto+='<input restriccion="entero"  identificador="'+row[10]+'" value="'+valor+'" type="text" class="form-control ttip cantidad" title="Números enteros."  placeholder="entero">';							
							texto+='</fieldset>'; 
							texto+='</td>';
							return texto;	

		                },
		                "targets": 6
		            },	   
		            { 
		                 "visible": false,
		                "targets": [7,8,9,10,11,12,13]
		        	}

		        ],

				"infoCallback": function( settings, start, end, max, total, pre ) {

						jQuery("#modulo_activo").val(settings.json.generales.modulo_activo);					
						

						cant_mod = settings.json.generales.modulo_activo ;
						if (cant_mod>4) {
							cant_mod=7;
						}

						for (var i = 2; i <= cant_mod; i++) {
							jQuery(".l"+i).css('display', 'inline-block');		
						}



						if (settings.json.generales.modulo_activo!=jQuery("#modulo").val()) {
							jQuery("#hab_proceso").attr('disabled', true);					
						} else {
							jQuery("#hab_proceso").attr('disabled', false);					
						}

						if (settings.json.generales.modulo_activo>= parseInt(jQuery("#modulo").val()) ) {	
										id_almacen = jQuery("#id_almacen_historicos").val(); 		
					 				   	modulo = jQuery("#modulo").val(); 				
					 					modulo_activo = jQuery("#modulo_activo").val(); 	

										jQuery('#imp_conteos').css('display','block');
										jQuery('#imp_nota_conteo').attr('href','/generar_conteos/'+jQuery.base64.encode(id_almacen)+'/'+jQuery.base64.encode(modulo)+'/'+jQuery.base64.encode(modulo_activo) );   


									} else {
										jQuery('#imp_conteos').css('display','none');
										jQuery('#imp_nota_conteo').attr("href","");   
						}



					    return pre
				  	} ,    



	});	


jQuery('body').on('keypress paste','.cantidad[restriccion="entero"]', function (event) {	
    var regex = new RegExp("^[0-9]+$");
    var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
    if (!regex.test(key)) {
       event.preventDefault();
       return false;
    }
});



jQuery('body').on('click','#procesar_conteo', function (e) {
		id_almacen 	= jQuery.base64.encode(jQuery("#id_almacen_historicos").val()); 

	id_descripcion 	= 	jQuery.base64.encode(jQuery("#producto_existente").val());
	id_color 	= 	  jQuery.base64.encode(jQuery("#color_existente").val()); 
	id_composicion 	= jQuery.base64.encode(jQuery("#composicion_existente").val());
	id_calidad 	= 	  jQuery.base64.encode(jQuery("#calidad_existente").val());
	

		   cantidad = jQuery.base64.encode(jQuery('#tabla_informe_pendiente').dataTable().fnSettings().aoData.length);

	id_factura = jQuery.base64.encode(jQuery('#id_tipo_factura_existente').val());
	 proveedor = jQuery.base64.encode( (jQuery("#editar_proveedor_historico").val()!='') ? jQuery("#editar_proveedor_historico").val() : ' ' );

	var url = "/procesar_conteo/"+id_almacen+'/'+id_descripcion+'/'+id_color+'/'+id_composicion+'/'+id_calidad+'/'+cantidad+'/'+id_factura+'/'+proveedor;

	jQuery('#modalMessage').modal({
		  show:'true',
		remote:url,
	}); 
});





	jQuery('#tabla_informe_pendiente').dataTable( {
	
	  "pagingType": "full_numbers",
		
		"processing": true,
		"serverSide": true,
		"ajax": {
	            	"url" : "procesando_informe_pendiente",
	         		"type": "POST",
	         		 "data": function ( d ) {
					    d.id_almacen = jQuery("#id_almacen_historicos").val(); 						
	         		 }
	     },   

		"infoCallback": function( settings, start, end, max, total, pre ) {
		    //console.log((jQuery('#producto_existente > option').length));

						jQuery("#modulo_activo").val(settings.json.generales.modulo_activo);		

						cant_mod = settings.json.generales.modulo_activo ;
						if (cant_mod>4) {
							cant_mod=7;
						}
						//console.log(cant_mod);

						for (var i = 2; i <= cant_mod; i++) {
							jQuery(".l"+i).css('display', 'inline-block');		
						}
   

				//cuando no hay productos
			if (jQuery('#producto_existente > option').length>1) {
				jQuery(".conteo_principal").css('display','block');  	
				jQuery(".mensaje_proceso").css('display','none');
			} else {
				jQuery(".conteo_principal").css('display','none');
				jQuery(".mensaje_proceso").css('display','block');
				
			}		     

		    if (settings.json.status_almacen==0) {
				jQuery(".conteo_principal").css('display','none');  	
				jQuery(".mensaje_proceso").css('display','block');
				MY_Socket.sendAlmacen("1",'salida_almacen');	   
			} else {
				jQuery(".conteo_principal").css('display','block');
				jQuery(".mensaje_proceso").css('display','none');
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


		"columnDefs": [
			    



			    	{ 
		                "render": function ( data, type, row ) {
		                		return data;
		                },
		                "targets": [0,1,2,3] //
		            },

		            
		        ],

	});	
///////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////


jQuery('#tabla_costo_rollo').dataTable( {
		
	  "pagingType": "full_numbers",
 	  "order": [[ 9, "asc" ]],


	"processing": true,
	"serverSide": true,
	"ajax": {
            	"url" : "procesando_costo_rollo",
         		"type": "POST",
         		 "data": function ( d ) {
         		 	   /*if (comienzo) {
         		 	   	 d.start=0;	 //comienza en cero siempre q cambia de botones
         		 	   	 d.draw =0;
         		 	   }*/

     				   	 d.id_almacen = jQuery("#id_almacen_historicos[vista='costo_rollo']").val(); 
     				   	 d.id_factura = jQuery("#id_factura_historicos[vista='costo_rollo']").val(); 
     				     d.id_estatus = jQuery("#id_estatuss_historicos[vista='costo_rollo']").val(); 
      					 d.factura_reporte = jQuery('#factura_historicos[vista="costo_rollo"]').val();					
					     d.proveedor = jQuery("#editar_proveedor_historico[vista='costo_rollo']").val(); 	   
 				     
						var fecha = (jQuery('.fecha_historicos[vista="costo_rollo"]').val()).split(' / ');
						d.fecha_inicial = fecha[0];
						d.fecha_final = fecha[1];


     				   //datos del producto
     				   d.id_descripcion = jQuery("#producto").val(); 
     				   if (d.id_descripcion !='') {
     				   	  d.id_descripcion = jQuery('#producto option:selected').text();
     				   }

     				   d.id_color = jQuery("#color").val(); 
     				   d.id_composicion = jQuery("#composicion").val(); 
     				   d.id_calidad = jQuery("#calidad").val(); 
	
	
     				   
    			 }
     },   

	"infoCallback": function( settings, start, end, max, total, pre ) {
	    if (settings.json.totales) {
		    jQuery('#total_pieza').html( 'Total de piezas:'+ settings.json.totales.pieza);
			jQuery('#total_kg').html( 'Total de kgs:'+number_format(settings.json.totales.kilogramo, 2, '.', ','));
			jQuery('#total_metro').html('Total de mts:'+ number_format(settings.json.totales.metro, 2, '.', ','));

			//costos
			jQuery('#total_costom').html( 'Costo de mts:'+number_format((parseFloat(settings.json.totales_importe.subtotal)/parseFloat(settings.json.totales.metro)), 2, '.', ','));
			jQuery('#total_costokg').html( 'Costo de kgs:'+number_format( (parseFloat(settings.json.totales_importe.subtotal)/parseFloat(settings.json.totales.kilogramo)), 2, '.', ','));

		} else {
		    jQuery('#total_pieza').html( 'Total de piezas: 0');
			jQuery('#total_kg').html( 'Total de kgs: 0.00');
			jQuery('#total_metro').html('Total de mts: 0.00');

			//costos	
			jQuery('#total_costom').html('Costo de mts: 0.00');
			jQuery('#total_costokg').html('Costo de kgs: 0.00');

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

					//costos
					jQuery('#costokg').html( 'Costo de kgs:'+number_format( (parseFloat(total_subtotal)/parseFloat(total_kilogramo)), 2, '.', ','));
					jQuery('#costom').html( 'Costo de mts:'+number_format( (parseFloat(total_subtotal)/parseFloat(total_metro)), 2, '.', ','));



		} else 	{
			        jQuery('#pieza').html('Total de piezas: 0');
			        jQuery('#metro').html('Total de mts: 0.00');
					jQuery('#kg').html('Total de kgs: 0.00');			        

					//importes
					jQuery('#subtotal').html('SubTotal: 0.00');	
					jQuery('#iva').html('IVA: 0.00');	
					jQuery('#total').html('Total: 0.00');										

					//costos
					jQuery('#costokg').html('Total de kgs: 0.00');										
					jQuery('#costom').html('Total de mts: 0.00');										


		}	
    },
   "columnDefs": [

				{ 
		                "render": function ( data, type, row ) {
								var color;
		                		switch (row[22]){
		                			case "12": //normal rojo
		                				color = 'red';
		                			   break;	
		                			case "13": //devolucion verde
		                				color = 'green';
		                			   break;
		                			case "14": //defecto azul
		                				color = 'blue';
		                			   break;
		                			case "15": //ajuste naranja
		                				color = 'orange';
		                			   break;
		                			default: 
		                				color = 'red';            
		                		} 		                	

		                		if (row[19]!='') {
		                			return row[1]+'<br/><b style="color:'+color+';">Cód: </b>'+row[19];	
		                		} else {
		                			return row[1];
		                		}
		                		
		                },
		                "targets": [1]   //el 3 es la imagen q ya viene formada desde el modelo
		        },   	
    			{ 
	                "render": function ( data, type, row ) {
						return data;	
	                },
	                "targets": [0,2,3,4] //,5,6,7,8,9,11
	            },
				{ 
	                "render": function ( data, type, row ) {
						return row[21];	
	                },
	                "targets": [5]
	            },
    			{ 
	                "render": function ( data, type, row ) {
						return row[5];	
	                },
	                "targets": [6]
	            },
    			{ 
	                "render": function ( data, type, row ) {
						return row[6];	
	                },
	                "targets": [7]
	            },
    			{ 
	                "render": function ( data, type, row ) {
						return row[7];	
	                },
	                "targets": [8]
	            },

    			{ 
	                "render": function ( data, type, row ) {
						return row[8];	
	                },
	                "targets": [9]
	            },
    			{ 
	                "render": function ( data, type, row ) {
						return row[9];	
	                },
	                "targets": [10]
	            },

    			{ 
	                "render": function ( data, type, row ) {
						return row[11];	
	                },
	                "targets": [11]
	            },	            
    			{ 
	                 "visible": false,
	                "targets": [12,13,14,15,16,17,18,19,20,21,22]
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
									        	MY_Socket.sendNewPost(dato.vendedor+' - '+dato.tienda+' - '+dato.compra,'proc_aprobado_compra');
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
									        	MY_Socket.sendNewPost(dato.vendedor+' - '+dato.tienda+' - '+dato.compra,'proc_pedido_compra');
									        	
									        	
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
									        	MY_Socket.sendNewPost(data.vendedor+' - '+data.tienda,'form_cancelar_pedido_compra');
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
	"pageLength": 500,  //que soporte 500 registro
	"lengthChange": false,  //y que deshabilite el cambio de cantidad de registro
 	"processing": true, //	//tratamiento con base de datos
	"serverSide": true,
	scroller:       true,
    scrollY:        250,
    scrollCollapse: false,	
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

				if ( (jQuery("#el_perfil").val() == '2') ) {
							 api.column(6).visible(false);
				}


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
//
//
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


		if ( (jQuery("#config_compra_activo").val() == 1)  ) {
			api.column(3).visible(true);
		} else {
			api.column(3).visible(false);
		}	


		if  (data.length>0) {   

				//invisible columna de revisar para el caso de cancelar						
				if ( (jQuery("#modulo").val() == 4) || (jQuery("#modulo").val() == 5) || (jQuery("#modulo").val() == 3) ) {
					api.column(9).visible(false);	
				}

				//si eres administrador			    
				if ( (jQuery("#mi_perfil").val() != '2') ) {
					switch(jQuery("#modulo").val()) {
					    case '2':
						    	api.column(8).visible(false);
						    	api.column(9).visible(false);

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
				if ( (jQuery("#mi_perfil").val() == '2') ) { //if ( (jQuery("#mi_perfil").val() != '1') ) {

							api.column(6).visible(false);
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
	                "targets": [0,1,2,3,4,5,6]
	            },
  				{
	                "render": function ( data, type, row ) {
						return row[9];	
	                },
	                "targets": 7
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
				
				if ( (jQuery("#el_perfil").val() == '2') ) {
							 api.column(6).visible(false);
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
								var color;
		                		switch (row[11]){
		                			case "12": //normal rojo
		                				color = 'red';
		                			   break;	
		                			case "13": //devolucion verde
		                				color = 'green';
		                			   break;
		                			case "14": //defecto azul
		                				color = 'blue';
		                			   break;
		                			case "15": //ajuste naranja
		                				color = 'orange';
		                			   break;
		                			default: 
		                				color = 'red';            
		                		} 		                	
		                		if (row[8]!='') {
		                			return row[0]+'<br/><b style="color:'+color+';">Cód: </b>'+row[8];	
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
				{ 
		                 "visible": false,
		                "targets": [9,10,11]
		        }


	        ],
});	


//Agregar las estradas a salidas en el modulo de salida "agregar la regilla de arriba a la regilla inferior"
jQuery('table').on('click','.agregar_compra', function (e) {
	jQuery(this).attr('disabled', true);		

	
	identificador = (jQuery(this).attr('identificador'));
	movimiento = jQuery("#movimiento").val();
	factura = jQuery("#factura").val();
	comentario = jQuery("#comentario").val();
	id_almacen = jQuery("#id_almacen_compra").val();
	id_proveedor = jQuery("#id_proveedor_compra").val();
	

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
		        	id_proveedor:id_proveedor,
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
	"pageLength": 500,  //que soporte 500 registro
	"lengthChange": false,  //y que deshabilite el cambio de cantidad de registro
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

				if ( (jQuery("#el_perfil").val() == '2') ) {
							 api.column(6).visible(false);
				}				
			

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

  								var color;
		                		switch (row[12]){
		                			case "12": //normal rojo
		                				color = 'red';
		                			   break;	
		                			case "13": //devolucion verde
		                				color = 'green';
		                			   break;
		                			case "14": //defecto azul
		                				color = 'blue';
		                			   break;
		                			case "15": //ajuste naranja
		                				color = 'orange';
		                			   break;
		                			default: 
		                				color = 'red';            
		                		} 

		                		if (row[8]!='') {
		                			return row[0]+'<br/><b style="color:'+color+';">Cód: </b>'+row[8];	
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
				{ 
		                 "visible": false,
		                "targets": [10,11,12]
		        }	            


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
									        	MY_Socket.sendNewPost(dato.vendedor+' - '+dato.tienda+' - '+dato.compra,'proc_pedido_compra');

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

	





/////////////////////////buscar proveedores historico

	// busqueda de proveedors reportes
	var consulta_proveedor_historico = new Bloodhound({
	   datumTokenizer: Bloodhound.tokenizers.obj.whitespace('nombre'),
	   queryTokenizer: Bloodhound.tokenizers.whitespace,
	   //remote:'catalogos/buscador?key=%QUERY&nombre='+jQuery('.buscar_proveedor_historico').attr("name")+'&idproveedor='+jQuery('.buscar_proveedor_historico').attr("idproveedor"),

	  remote: {
	        url: 'catalogos/buscador?key=%QUERY',
	        replace: function () {
	            var q = 'catalogos/buscador?key='+encodeURIComponent(jQuery('.buscar_proveedor_historico').typeahead("val"));
					q += '&nombre='+encodeURIComponent(jQuery('.buscar_proveedor_historico.tt-input').attr("name"));
				    q += '&idproveedor='+encodeURIComponent(jQuery('.buscar_proveedor_historico.tt-input').attr("idproveedor"));
	            
	            return  q;
	        }
	    },   

	});



	consulta_proveedor_historico.initialize();

	jQuery('.buscar_proveedor_historico').typeahead(
		{
			  hint: true,
		  highlight: true,
		  minLength: 1
		},

		 {
	  
	  name: 'buscar_proveedor_historico',
	  displayKey: 'descripcion', //
	  source: consulta_proveedor_historico.ttAdapter(),
	   templates: {
	   			//header: '<h4>'+jQuery('.buscar_proveedor_historico').attr("name")+'</h4>',
			    suggestion: function (data) {  
					return '<p><strong>' + data.descripcion + '</strong></p>'+
					 '<div style="background-color:'+ '#'+data.hexadecimal_color + ';display:block;width:15px;height:15px;margin:0 auto;"></div>';

		   }
	    
	  }
	});

	jQuery('.buscar_proveedor_historico').on('typeahead:selected', function (e, datum,otro) {
				switch(jQuery(this).attr('vista')) {
					    case "cuentas":
							var oTable =jQuery('#tabla_ctas_vencidas').dataTable();
							oTable._fnAjaxUpdate();
							var oTable =jQuery('#tabla_ctasxpagar').dataTable();
							oTable._fnAjaxUpdate();
							var oTable =jQuery('#tabla_ctas_pagadas').dataTable();
							oTable._fnAjaxUpdate();
					        break;
					    
					    case "conteo_fisico":
										/*	        //
								 var campo = "editar_proveedor_historico";
						 		 var val_prod = jQuery('#producto_existente option:selected').text();  		
						 		 var val_color = jQuery('#color_existente').val();  		  
						 		 var val_comp = jQuery('#composicion_existente').val();  	
						 		 var val_calida = jQuery('#calidad_existente').val();  		
						         var dependencia = "producto_existente";
						         var nombre = "un producto";
						         

						    	if (dependencia !="") {	    
							        //limpiar la dependencia
							        jQuery("#"+dependencia).html(''); 
							        //cargar la dependencia
							        cargarDependencia_existente(campo,val_prod,val_color,val_comp,val_calida,dependencia,nombre);
						        }


										var oTable =jQuery('#tabla_informe_pendiente').dataTable();
										oTable._fnAjaxUpdate();
										*/

			              break;
			            default: 
			            	// 
			               break;
					}		


	});	

	jQuery('.buscar_proveedor_historico').on('typeahead:closed', function (e) {
				switch(jQuery(this).attr('vista')) {

						case "tabla_historico_conteo":
							var oTable =jQuery('#tabla_historico_conteo').dataTable();
					    	oTable._fnAjaxUpdate();
					        break;

						case "costo_rollo":
							var oTable =jQuery('#tabla_costo_rollo').dataTable();
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
					    case "conteo_fisico":

											        //
								 var campo = "editar_proveedor_historico";
						 		 var val_prod = jQuery('#producto_existente option:selected').text();  		
						 		 var val_color = jQuery('#color_existente').val();  		  
						 		 var val_comp = jQuery('#composicion_existente').val();  	
						 		 var val_calida = jQuery('#calidad_existente').val();  		
						         var dependencia = "producto_existente";
						         var nombre = "un producto";
						         

						    	if (dependencia !="") {	    
							        //limpiar la dependencia
							        jQuery("#"+dependencia).html(''); 
							        //cargar la dependencia
							        cargarDependencia_existente(campo,val_prod,val_color,val_comp,val_calida,dependencia,nombre);
						        }


										var oTable =jQuery('#tabla_informe_pendiente').dataTable();
										oTable._fnAjaxUpdate();
								
			              break;
			            default: 
			            	// 
			               break;
					}		
	});	




/////////////////////////////////////////////////Historico de entradas/////////////////////////////////////////////////////////////////





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

						case "costo_rollo":
							var oTable =jQuery('#tabla_costo_rollo').dataTable();
					    	oTable._fnAjaxUpdate();
					        break;

						case "tabla_historico_conteo":
							var oTable =jQuery('#tabla_historico_conteo').dataTable();
					    	oTable._fnAjaxUpdate();
					        break;



 						case "ctas_vencida":
							var oTable =jQuery('#tabla_ctas_vencidas').dataTable();
							oTable._fnAjaxUpdate();
					        break;
 						case "ctas_ctasxpagar":
							var oTable =jQuery('#tabla_ctasxpagar').dataTable();
							oTable._fnAjaxUpdate();
					        break;
 						case "ctas_pagadas":
							var oTable =jQuery('#tabla_ctas_pagadas').dataTable();
							oTable._fnAjaxUpdate();
					        break;

 						case "listado_traspaso":
					 		var oTable =jQuery('#tabla_general_traspaso').dataTable();
					    	oTable._fnAjaxUpdate();
					    	var oTable =jQuery('#tabla_traspaso_historico').dataTable();
					    	oTable._fnAjaxUpdate();
					        break;						
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
					    d.id_estatus = jQuery("#id_estatuss_historicos").val(); 			

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

			if ( jQuery('#config_entrada_activo').val() == 0 ) {
				api.column(5).visible(false);		
			}	


			if (( jQuery('#config_almacen').val() == 0 ) && (jQuery('#el_perfil').val()==2) ) {
				api.column(2).visible(false);		
			}	else {
				api.column(2).visible(true);		
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
							texto+=' href="procesar_entradas/'+jQuery.base64.encode(row[0])+'/'+jQuery.base64.encode(row[9])+'/'+jQuery.base64.encode($otro_retorno)+'/'+jQuery.base64.encode(row[10])+'/'+jQuery.base64.encode(row[11])+'"'; //
							texto+='type="button" class="btn btn-success btn-block">';
							texto+='Detalles';
							texto+='</a>';
						texto+='</td>';



							return texto;	
		                },
		                "targets": 9
		            },
  					
  					{ 
		                 "visible": false,
		                "targets": [10]
		            }
		            
		          
		           
		            
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

			if ( jQuery('#config_entrada_activo').val() == 0 ) {
				api.column(4).visible(false);		
			}		


			if (( jQuery('#config_almacen').val() == 0 ) && (jQuery('#el_perfil').val()==2) ) {
				api.column(1).visible(false);		
			}	else {
				api.column(1).visible(true);		
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
							texto+=' href="procesar_entradas/'+jQuery.base64.encode(row[0])+'/'+jQuery.base64.encode(row[8])+'/'+jQuery.base64.encode($otro_retorno)+'/'+jQuery.base64.encode(row[9])+'/'+jQuery.base64.encode(row[10])+'"';
							texto+='type="button" class="btn btn-success btn-block">';
							texto+='Detalles';
							texto+='</a>';
						texto+='</td>';
							return texto;	
		                },
		                "targets": 8
		            },
  					
  					{ 
		                 "visible": false,
		                "targets": [9]
		            }
		            
		          
		           
		            
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
					    d.id_estatus = jQuery("#id_estatuss_historicos").val(); 						
					    

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
						.column( 8)
						.data()
						.reduce( function (a, b) {
							return intVal(a) + intVal(b);
						} );					

					
					total_iva = api
						.column( 9)
						.data()
						.reduce( function (a, b) {
							return intVal(a) + intVal(b);
						} );	
					
					total = api
						.column( 10)
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


			if ( jQuery('#config_salida_activo').val() == 0 ) {
				api.column(7).visible(false);		
			}			

			if (( jQuery('#config_almacen').val() == 0 ) && (jQuery('#el_perfil').val()==3) ) {
				api.column(2).visible(false);		
			}	else {
				api.column(2).visible(true);		
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
							if (row[13]==null) {
								return '<b/> Nro.'+row[1];												
							} else {
								return row[13]+'<b/> Nro.'+row[1];		
							}

							
		                },
		                "targets": 1
		            },


			    	{ 
		                "render": function ( data, type, row ) {
		                		return data;
		                },
		                "targets": [0,2,3,4,5,6,7,8,9,10] 
		            },


		            {
		                "render": function ( data, type, row ) {


						$otro_retorno="listado_devolucion";
		        		texto='<td>';
							texto+='<a style="padding: 1px 0px 1px 0px;"';
							texto+=' href="detalle_salidas/'+jQuery.base64.encode(row[0])+'/'+jQuery.base64.encode(row[3])+'/'+jQuery.base64.encode(row[4])+'/'+jQuery.base64.encode(row[11])+'/'+jQuery.base64.encode(row[12])+'/'+jQuery.base64.encode("listado_salidas")+'/'+jQuery.base64.encode(row[14])+'"'; //
							texto+='type="button" class="btn btn-success btn-block">';
							texto+='Detalles';
							texto+='</a>';
						texto+='</td>';
							return texto;	
		                },
		                "targets": 11
		            },
  					
  					{ 
		                 "visible": false,
		                "targets": [12,13]
		            }
		            
		          
		           
		            
		        ],
	});		
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////

var existencia_costo_inventario = ['Código', 'Producto', 'Color',   'Cantidad',  'Ancho', 'Precio', 'SubTotal', 'iva', 'Tipo Factura', 'No. Movimiento','Proveedor', 'Ingreso'];

jQuery('#id_estatuss_costo, #id_factura_costo').change(function(e) {
		
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
jQuery('body').on('click','#impresion_rapido_costo', function (e) {

	  	  busqueda      = jQuery('input[type=search]').val();
	   extra_search = 'reportes_costo'; //jQuery("#botones").val(); 
	   id_estatus = jQuery("#id_estatuss_costo").val(); 
	   id_factura_costo = jQuery("#id_factura_costo").val(); 
	   
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

		var oTable =jQuery('#tabla_costo_inventario').DataTable();
		    order = oTable.order();


    abrir('POST', 'imprimir_rapida', {
    			busqueda:busqueda,
			extra_search:extra_search,
			id_estatus:id_estatus,
			id_factura_costo:id_factura_costo,
			id_almacen: id_almacen,

			id_descripcion:id_descripcion, 
			id_color:id_color, 
			id_composicion:id_composicion, 
			id_calidad:id_calidad,

			factura_reporte: factura_reporte,

			proveedor:proveedor, 
			fecha_inicial:fecha_inicial, 
			fecha_final: fecha_final,


			columna : order[0][0],
      		orden : order[0][1],



    }, '_blank' );
		        
	
});



//Agregar las estradas a salidas
jQuery('body').on('click','#impresion_reporte_costo', function (e) {

	  	  busqueda      = jQuery('input[type=search]').val();
	   extra_search = 'reportes_costo'; //jQuery("#botones").val(); 
	   id_estatus = jQuery("#id_estatuss_costo").val(); 
	   id_factura_costo = jQuery("#id_factura_costo").val(); 
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
			id_factura_costo:id_factura_costo,
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
	   id_factura_costo = jQuery("#id_factura_costo").val(); 
	   id_almacen = jQuery("#id_almacen_costo").val(); 

	   id_descripcion = jQuery("#producto").val(); 
	   if (id_descripcion !='') {
	   	  id_descripcion = jQuery('#producto option:selected').text();
	   }

	   id_color = jQuery("#color").val(); 
	   id_composicion = jQuery("#composicion").val(); 
	   id_calidad = jQuery("#calidad").val(); 

	   val_prod_id = jQuery('#producto_rep option:selected').val();
		
	   factura_reporte = jQuery('#factura_costo').val();					

	   proveedor = jQuery("#editar_proveedor_costo").val(); 	   

	   var fecha = (jQuery('.fecha_costo').val()).split(' / ');

	   fecha_inicial = fecha[0];
	   fecha_final = fecha[1];


    abrir('POST', 'exportar_reportes', {
    			busqueda:busqueda,
			extra_search:extra_search,
			id_estatus:id_estatus,
			id_factura_costo:id_factura_costo,
			id_almacen: id_almacen,

			id_descripcion:id_descripcion, 
			id_color:id_color, 
			id_composicion:id_composicion, 
			id_calidad:id_calidad,
			val_prod_id: val_prod_id,

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
    				   d.id_factura_costo = jQuery("#id_factura_costo").val(); 
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

			//costos
			jQuery('#total_costom').html( 'Costo de mts:'+number_format((parseFloat(settings.json.totales_importe.subtotal)/parseFloat(settings.json.totales.metro)), 2, '.', ','));
			jQuery('#total_costokg').html( 'Costo de kgs:'+number_format( (parseFloat(settings.json.totales_importe.subtotal)/parseFloat(settings.json.totales.kilogramo)), 2, '.', ','));


		} else {
		    jQuery('#total_pieza').html( 'Total de piezas: 0');
			jQuery('#total_kg').html( 'Total de kgs: 0.00');
			jQuery('#total_metro').html('Total de mts: 0.00');

			//costos	
			jQuery('#total_costom').html('Costo de mts: 0.00');
			jQuery('#total_costokg').html('Costo de kgs: 0.00');

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

					//costos
					jQuery('#costokg').html( 'Costo de kgs:'+number_format( (parseFloat(total_subtotal)/parseFloat(total_kilogramo)), 2, '.', ','));
					jQuery('#costom').html( 'Costo de mts:'+number_format( (parseFloat(total_subtotal)/parseFloat(total_metro)), 2, '.', ','));



		} else 	{
			        jQuery('#pieza').html('Total de piezas: 0');
			        jQuery('#metro').html('Total de mts: 0.00');
					jQuery('#kg').html('Total de kgs: 0.00');			        

					//importes
					jQuery('#subtotal').html('SubTotal: 0.00');	
					jQuery('#iva').html('IVA: 0.00');	
					jQuery('#total').html('Total: 0.00');		

					//costos
					jQuery('#costokg').html('Total de kgs: 0.00');										
					jQuery('#costom').html('Total de mts: 0.00');	


		}	
    },
   "columnDefs": [

				{ 
		                "render": function ( data, type, row ) {
		                		var color;
		                		switch (row[22]){
		                			case "12": //normal rojo
		                				color = 'red';
		                			   break;	
		                			case "13": //devolucion verde
		                				color = 'green';
		                			   break;
		                			case "14": //defecto azul
		                				color = 'blue';
		                			   break;
		                			case "15": //ajuste naranja
		                				color = 'orange';
		                			   break;
		                			default: 
		                				color = 'red';            
		                		} 

		                		if (row[19]!='') {
		                			return row[1]+'<br/><b style="color:'+color+';">Cód: </b>'+row[19];	
		                		} else {
		                			return row[1];
		                		}
		                		
		                },
		                "targets": [1]   //el 3 es la imagen q ya viene formada desde el modelo
		        },   	

    			{ 
	                "render": function ( data, type, row ) {
						return data;	
	                },
	                "targets": [0,2,3,4] //5,6,7,8,9,11
	            },

				{ 
	                "render": function ( data, type, row ) {
						return row[21];	
	                },
	                "targets": [5]
	            },
    			{ 
	                "render": function ( data, type, row ) {
						return row[5];	
	                },
	                "targets": [6]
	            },
    			{ 
	                "render": function ( data, type, row ) {
						return row[6];	
	                },
	                "targets": [7]
	            },
    			{ 
	                "render": function ( data, type, row ) {
						return row[7];	
	                },
	                "targets": [8]
	            },

    			{ 
	                "render": function ( data, type, row ) {
						return row[8];	
	                },
	                "targets": [9]
	            },
    			{ 
	                "render": function ( data, type, row ) {
						return row[9];	
	                },
	                "targets": [10]
	            },

    			{ 
	                "render": function ( data, type, row ) {
						return row[11];	
	                },
	                "targets": [11]
	            },	            

	            
    			{ 
	                 "visible": false,
	                "targets": [12,13,14,15,16,17,18,19,20,21,22]
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
//var arr_general_traspaso = ['Traspaso', 'Proceso','Almacén', 'Fecha', 'Motivo',  'Número',  'Responsable','Dependencia','Detalle']; //
/*
var arr_general_traspaso = ['Traspaso', 'Proceso','Almacén', 'Fecha', 'Motivo',  'Número',  'Responsable','Dependencia','Total Metro','Total Kgs','Detalle']; //
var arr_general_traspaso = ['Traspaso', 'Proceso','Almacén', 'Fecha', 'Motivo',  'Número',  'Responsable','Dependencia','Total Metro','Total Kgs','Detalle']; //
*/
var arr_general_traspaso = ['Traspaso', 'Proceso','Almacén', 'Fecha', 'Motivo',  'Número',  'Responsable','Dependencia','Total Metro','Total Kgs','Pieza','Subtotal','Iva','Detalle']; //
var arr_traspaso_historico_detalle = ['Código', 'Producto', 'Color', 'Cantidad', 'Ancho', 'Precio', 'SubTotal', 'IVA', 'Lote','No. de Partida','Almacén','Tipo factura'];




jQuery('#id_almacen_modulo').change(function(e) {
	comienzo=true; //para indicar que start comience en 0;
	var oTable =jQuery('#tabla_entrada_traspaso').dataTable();
	oTable._fnAjaxUpdate();		
});




//modulo traspaso
jQuery('#label_tipo_factura_traspaso').text(((jQuery(this).val()==2) ? "De Factura a ": "De Remisión a ")+jQuery('#id_tipo_factura_traspaso option:selected').text());

jQuery('#id_tipo_factura_traspaso').change(function(e) {
	comienzo=true; //para indicar que start comience en 0;
	etiqueta = (jQuery(this).val()==2) ? "De Factura a ": "De Remisión a ";
	jQuery('#label_tipo_factura_traspaso').text(etiqueta+jQuery('#id_tipo_factura_traspaso option:selected').text());
	var oTable =jQuery('#tabla_entrada_traspaso').dataTable();
	oTable._fnAjaxUpdate();		

});

jQuery('#id_tipo_factura_traspaso').on('change', function(e) {
  consecutivo_actual = ( (jQuery(this).val()==1) ? jQuery("#conse_factura").val() : jQuery("#conse_remision").val() );
  jQuery("#movimiento").val(consecutivo_actual);
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
		                        var color;
		                        switch (row[19]){
		                          case "12": //normal rojo
		                            color = 'red';
		                             break; 
		                          case "13": //devolucion verde
		                            color = 'green';
		                             break;
		                          case "14": //defecto azul
		                            color = 'blue';
		                             break;
		                          case "15": //ajuste naranja
		                            color = 'orange';
		                             break;
		                          default: 
		                            color = 'red';            
		                        } 

		                		if (row[17]!='') {
		                			return row[1]+'<br/><b style="color:'+color+';">Cód: </b>'+row[17];	
		                		} else {
		                			return row[1];
		                		}
		                		
		                },
		                "targets": [1]   //el 3 es la imagen q ya viene formada desde el modelo
		        },   	

	    		{ 
	                "render": function ( data, type, row ) {
	                		return data;
	                },
	                "targets": [0,2,3,4,18]
	            },
    			{ 
	                "render": function ( data, type, row ) {
						return row[18];	
	                },
	                "targets": [5]
	            },
    			{ 
	                "render": function ( data, type, row ) {
						return row[5];	
	                },
	                "targets": [6]
	            },
    			{ 
	                "render": function ( data, type, row ) {
						return row[6];	
	                },
	                "targets": [7]
	            },
    			{ 
	                "render": function ( data, type, row ) {
						return row[7];	
	                },
	                "targets": [8]
	            },

    			{ 
	                "render": function ( data, type, row ) {
						return row[8];	
	                },
	                "targets": [9]
	            },
    			{ 
	                "render": function ( data, type, row ) {
						return row[9];	
	                },
	                "targets": [10]
	            },






    			{ 
	                "render": function ( data, type, row ) {
						return row[12];	
	                },
	                "targets": [11]
	            },

	            {
	                "render": function ( data, type, row ) {
						texto='<td><button '; 
							texto+='type="button" class="btn btn-success btn-block agregar_traspaso '+row[10]+'" identificador="'+row[10]+'" >';
							texto+='<span  class="">Agregar</span>';
						texto+='</button></td>';
						return texto;	
	                },
	                "targets": 12
	            },
				{ 
	                 "visible": false,
	                 "targets": [13,14,15,16,17,18,19]
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
		                        var color;
		                        switch (row[19]){
		                          case "12": //normal rojo
		                            color = 'red';
		                             break; 
		                          case "13": //devolucion verde
		                            color = 'green';
		                             break;
		                          case "14": //defecto azul
		                            color = 'blue';
		                             break;
		                          case "15": //ajuste naranja
		                            color = 'orange';
		                             break;
		                          default: 
		                            color = 'red';            
		                        } 


		                		if (row[17]!='') {
		                			return row[1]+'<br/><b style="color:'+color+';">Cód: </b>'+row[17];	
		                		} else {
		                			return row[1];
		                		}
		                		
		                },
		                "targets": [1]   //el 3 es la imagen q ya viene formada desde el modelo
		        },   		
	    		{ 
	                "render": function ( data, type, row ) {
	                		return data;
	                },
	                "targets": [0,2,3,4]
	            },

				{ 
	                "render": function ( data, type, row ) {
						return row[18];	
	                },
	                "targets": [5]
	            },
    			{ 
	                "render": function ( data, type, row ) {
						return row[5];	
	                },
	                "targets": [6]
	            },
    			{ 
	                "render": function ( data, type, row ) {
						return row[6];	
	                },
	                "targets": [7]
	            },
    			{ 
	                "render": function ( data, type, row ) {
						return row[7];	
	                },
	                "targets": [8]
	            },

    			{ 
	                "render": function ( data, type, row ) {
						return row[8];	
	                },
	                "targets": [9]
	            },
    			{ 
	                "render": function ( data, type, row ) {
						return row[9];	
	                },
	                "targets": [10]
	            },

    			{ 
	                "render": function ( data, type, row ) {
						return row[12];	
	                },
	                "targets": [11]
	            },

	            {
	                "render": function ( data, type, row ) {
						texto='<td><button '; 
							texto+='type="button" class="btn btn-success btn-block quitar_traspaso '+row[10]+'" identificador="'+row[10]+'" >';
							texto+='<span  class="">Quitar</span>';
						texto+='</button></td>';
						return texto;	
	                },
	                "targets": 12
	            },
				{ 
	                 "visible": false,
	                 "targets": [13,14,15,16,17,18,19]
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
 				data:{ 
		        	id_factura: jQuery("#id_tipo_factura_traspaso").val(),
		        },		        
		        success : function(datos) {	
		        
						if(datos.exito != true){
								
								spinner.stop();
								jQuery('#foo').css('display','none');
								jQuery('#messages').css('display','block');
								jQuery('#messages').addClass('alert-danger');
								jQuery('#messages').html(datos.error);
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



jQuery('body').on('click','#impresion_traspaso_historico', function (e) {
	  	       busqueda = jQuery('input[type=search]').val();
	   		 	id_almacen = jQuery('#id_almacen_traspaso').val();	
				var fecha = (jQuery('.fecha_historicos').val()).split(' / ');
				fecha_inicial = fecha[0];
				fecha_final = fecha[1];
				id_factura = jQuery("#id_tipo_factura_historicos").val(); 

    abrir('POST', '/impresion_traspaso_historico', {
    			   busqueda: busqueda,
			     id_almacen: id_almacen,
			  fecha_inicial: fecha_inicial,
			    fecha_final: fecha_final,
			     id_factura:id_factura
    }, '_blank' );
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
							var fecha = (jQuery('.fecha_historicos').val()).split(' / ');
							d.fecha_inicial = fecha[0];
							d.fecha_final = fecha[1];
							d.id_factura = jQuery("#id_tipo_factura_historicos").val(); 
							//id_factura_inversa = (jQuery("#id_tipo_factura_historicos").val()==2) ? 1: 2;
							//d.id_factura_inversa = (jQuery("#id_tipo_factura_historicos").val()==0) ? 0: id_factura_inversa;

    			 }          		

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

				{  //metro
	                "render": function ( data, type, row ) {
						return row[10];										
	                },
	                "targets": [9]
	            },   	         		               	         	               	            
				{ //kg
	                "render": function ( data, type, row ) {
						return row[11];										
	                },
	                "targets": [10]
	            },
				{ //pieza
	                "render": function ( data, type, row ) {
						return row[12];										
	                },
	                "targets": [11]
	            },     
				{  //suma de precio subtotal
	                "render": function ( data, type, row ) {
						return row[13];										
	                },
	                "targets": [12]
	            },     
				{ //iva
	                "render": function ( data, type, row ) {
						return row[14];										
	                },
	                "targets": [13]
	            },     	            

	            
    			{ 
	                "render": function ( data, type, row ) {
    					 texto='<td><a href="traspaso_detalle/'+jQuery.base64.encode(row[7])+'/'+jQuery.base64.encode(row[16])+'" ';  
						 	texto+=' class="btn btn-success btn-block">';
						 	texto+=' Detalles';
						 texto+='</a></td>';


						return texto;	
	                },
	                "targets": [14]
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
					.column( 10 )
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					} );
				
				total_kilogramo = api
					.column( 11 )
					.data()
					.reduce( function (c, d) {
						return intVal(c) + intVal(d);
					} );

				//total_pieza = (end-start);	

				total_pieza = api
					.column( 12 )
					.data()
					.reduce( function (c, d) {
						return intVal(c) + intVal(d);
				} );
	
				total_subtotal = api
					.column( 13)
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					} );					

				
				total_iva = api
					.column( 14)
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					} );	

				//importe
						

			        jQuery('#pieza2').html( 'Total de piezas:'+ total_pieza);
			        jQuery('#kg2').html( 'Total de kgs:'+number_format(total_kilogramo, 2, '.', ','));
			        jQuery('#metro2').html('Total de mts:'+ number_format(total_metro, 2, '.', ','));


					//importes
					jQuery('#subtotal2').html('SubTotal:'+ number_format(total_subtotal, 2, '.', ','));
					jQuery('#iva2').html('IVA:' + number_format( total_iva, 2, '.', ','));
					//jQuery('#total2').html('Total:'+ number_format( (total_subtotal+total_iva), 2, '.', ','));						
					jQuery('#total2').html('Total:'+ number_format( parseFloat(total_subtotal)+parseFloat(total_iva), 2, '.', ','));						

		} else 	{
			        jQuery('#pieza2').html('Total de piezas: 0');
			        jQuery('#metro2').html('Total de mts: 0.00');
					jQuery('#kg2').html('Total de kgs: 0.00');	

					//importes
					jQuery('#subtotal2').html('SubTotal: 0.00');	
					jQuery('#iva2').html('IVA: 0.00');	
					jQuery('#total2').html('Total: 0.00');										

		}	


		if (( jQuery('#config_almacen').val() == 0 ) && (jQuery('#el_perfil').val()==2) ) {
			api.column(3).visible(false);		
		}	else {
			api.column(3).visible(true);		
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
         		 	d.id_factura = jQuery("#id_factura").val();
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
				jQuery('#label_cliente').text("Cliente");
				jQuery('#label_vendedor').text("Vendedor");
				
			} else {
				jQuery('#label_cliente').text("Sucursal");
				jQuery('#label_vendedor').text("Num. Mov");
			}
				


		}	
		
	    return pre
	}, 
	
   "columnDefs": [


				{ 
		                "render": function ( data, type, row ) {
		                		var color;
		                        switch (row[19]){
		                          case "12": //normal rojo
		                            color = 'red';
		                             break; 
		                          case "13": //devolucion verde
		                            color = 'green';
		                             break;
		                          case "14": //defecto azul
		                            color = 'blue';
		                             break;
		                          case "15": //ajuste naranja
		                            color = 'orange';
		                             break;
		                          default: 
		                            color = 'red';            
		                        } 

		                		if (row[15]!='') {
		                			return row[1]+'<br/><b style="color:'+color+';">Cód: </b>'+row[15];	
		                		} else {
		                			return row[1];
		                		}
		                		
		                },
		                "targets": [1]   //el 3 es la imagen q ya viene formada desde el modelo
		        },   	   
    			{ 
	                "render": function ( data, type, row ) {
						return data;	
	                },
	                "targets": [0,2,3,4], //,5,6,7,8,9,13
	            },

				{ 
	                "render": function ( data, type, row ) {
	                		return data;
	                },
	                "targets": [0,2,3,4]
	            },

				{ 
	                "render": function ( data, type, row ) {
						return row[18];	
	                },
	                "targets": [5]
	            },
    			{ 
	                "render": function ( data, type, row ) {
						return row[5];	
	                },
	                "targets": [6]
	            },
    			{ 
	                "render": function ( data, type, row ) {
						return row[6];	
	                },
	                "targets": [7]
	            },
    			{ 
	                "render": function ( data, type, row ) {
						return row[7];	
	                },
	                "targets": [8]
	            },

    			{ 
	                "render": function ( data, type, row ) {
						return row[8];	
	                },
	                "targets": [9]
	            },
    			{ 
	                "render": function ( data, type, row ) {
						return row[9];	
	                },
	                "targets": [10]
	            },

    			{ 
	                "render": function ( data, type, row ) {
						return row[13];	
	                },
	                "targets": [11]
	            },	            


    			{ 
	                 "visible": false,
	                "targets": [12,13,14,15,16,17,18,19],
	            }		            

	],	


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
					.column( 16 )
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					} );
				
				total_kilogramo = api
					.column( 17 )
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
					.column( 6)
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					} );	

				//importe
						

			        jQuery('#pieza').html( 'Total de piezas:'+ total_pieza);
			        jQuery('#kg').html( 'Total de kgs:'+number_format(total_kilogramo, 2, '.', ','));
			        jQuery('#metro').html('Total de mts:'+ number_format(total_metro, 2, '.', ','));


					//importes
					jQuery('#subtotal').html('SubTotal:'+ number_format(total_subtotal, 2, '.', ','));
					jQuery('#iva').html('IVA:' + number_format( total_iva, 2, '.', ','));
					jQuery('#total').html('Total:'+ number_format( parseFloat(total_subtotal)+parseFloat(total_iva), 2, '.', ','));						

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
							var fecha = (jQuery('.fecha_historicos').val()).split(' / ');
							d.fecha_inicial = fecha[0];
							d.fecha_final = fecha[1];
							d.id_factura = jQuery("#id_tipo_factura_historicos").val(); 						    
    			 }          		

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

				{  //metro
	                "render": function ( data, type, row ) {
						return row[16];										
	                },
	                "targets": [9]
	            },   	         		               	         	               	            
				{ //kg
	                "render": function ( data, type, row ) {
						return row[17];										
	                },
	                "targets": [10]
	            },     

				{ //pieza
	                "render": function ( data, type, row ) {
						return row[18];										
	                },
	                "targets": [11]
	            },     
				{  //suma de precio subtotal
	                "render": function ( data, type, row ) {
						return row[19];										
	                },
	                "targets": [12]
	            },     
				{ //iva
	                "render": function ( data, type, row ) {
						return row[20];										
	                },
	                "targets": [13]
	            },     	            

	            
    			{ 
	                "render": function ( data, type, row ) {
    					 if (row[11]!=0) {
	    					 texto='<td><a href="traspaso_general_detalle_manual/'+jQuery.base64.encode(row[15])+'/'+jQuery.base64.encode(jQuery('#id_almacen_traspaso option:selected').val())+'/'+jQuery.base64.encode(row[22])+'" ';  
							 	texto+=' class="btn btn-success btn-block">';
							 	texto+=' Detalles';
							 texto+='</a></td>';
						 } else {
	    					 texto='<td><a href="traspaso_general_detalle/'+jQuery.base64.encode(row[6])+'/'+jQuery.base64.encode(row[10])+'/'+jQuery.base64.encode(jQuery('#id_almacen_traspaso option:selected').val())+'/'+jQuery.base64.encode(row[22])+'" ';  
							 	texto+=' class="btn btn-success btn-block">';
							 	texto+=' Detalles';
							 texto+='</a></td>';						 	

						 }	 


						return texto;	
	                },
	                "targets": [14]
	            },


    			{ 
	                 "visible": false,
	                "targets": [1,15,16,17,18,19,20,21,22]
	            }	            
	],	

	"fnHeaderCallback": function( nHead, aData, iStart, iEnd, aiDisplay ) {
		var arreglo =arr_general_traspaso;
		for (var i=0; i<=arreglo.length-1; i++) { //cant_colum
	    		nHead.getElementsByTagName('th')[i].innerHTML = arreglo[i]; 
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
					.column( 16 )
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					} );
				
				total_kilogramo = api
					.column( 17 )
					.data()
					.reduce( function (c, d) {
						return intVal(c) + intVal(d);
					} );

				//total_pieza = (end-start);	

				total_pieza = api
					.column( 18 )
					.data()
					.reduce( function (c, d) {
						return intVal(c) + intVal(d);
				} );
	
				total_subtotal = api
					.column( 19)
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					} );					

				
				total_iva = api
					.column( 20)
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					} );	

				//importe
						

			        jQuery('#pieza').html( 'Total de piezas:'+ total_pieza);
			        jQuery('#kg').html( 'Total de kgs:'+number_format(total_kilogramo, 2, '.', ','));
			        jQuery('#metro').html('Total de mts:'+ number_format(total_metro, 2, '.', ','));


					//importes
					jQuery('#subtotal').html('SubTotal:'+ number_format(total_subtotal, 2, '.', ','));
					jQuery('#iva').html('IVA:' + number_format( total_iva, 2, '.', ','));
					jQuery('#total').html('Total:'+ number_format( parseFloat(total_subtotal)+parseFloat(total_iva), 2, '.', ','));						


		} else 	{
			        jQuery('#pieza').html('Total de piezas: 0');
			        jQuery('#metro').html('Total de mts: 0.00');
					jQuery('#kg').html('Total de kgs: 0.00');	

					//importes
					jQuery('#subtotal').html('SubTotal: 0.00');	
					jQuery('#iva').html('IVA: 0.00');	
					jQuery('#total').html('Total: 0.00');										

		}	


		if (( jQuery('#config_almacen').val() == 0 ) && (jQuery('#el_perfil').val()==2) ) {
			api.column(3).visible(false);		
		}	else {
			api.column(3).visible(true);		
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
     				   d.id_factura = jQuery("#id_factura").val();
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
				
				total_metro = api
					.column( 16 )
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					} );
				
				total_kilogramo = api
					.column( 17 )
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
					.column( 6)
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					} );	

				//importe
						

			        jQuery('#pieza').html( 'Total de piezas:'+ total_pieza);
			        jQuery('#kg').html( 'Total de kgs:'+number_format(total_kilogramo, 2, '.', ','));
			        jQuery('#metro').html('Total de mts:'+ number_format(total_metro, 2, '.', ','));


					//importes
					jQuery('#subtotal').html('SubTotal:'+ number_format(total_subtotal, 2, '.', ','));
					jQuery('#iva').html('IVA:' + number_format( total_iva, 2, '.', ','));
					jQuery('#total').html('Total:'+ number_format( parseFloat(total_subtotal)+parseFloat(total_iva), 2, '.', ','));						

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
				jQuery('#label_cliente').text("Cliente");
				jQuery('#label_vendedor').text("Vendedor");
				
			} else {
				jQuery('#label_cliente').text("Sucursal");
				jQuery('#label_vendedor').text("Num. Mov");
			}
				


		}	
		
	    return pre
	}, 
	
   "columnDefs": [



				{ 
		                "render": function ( data, type, row ) {
		                		if (row[15]!='') {
		                			return row[1]+'<br/><b style="color:red;">Cód: </b>'+row[15];	
		                		} else {
		                			return row[1];
		                		}
		                		
		                },
		                "targets": [1]   //el 3 es la imagen q ya viene formada desde el modelo
		        },   	   

			   { 
	                "render": function ( data, type, row ) {
						return data;	
	                },
	                "targets": [0,2,3,4],
	            },


				{ 
	                "render": function ( data, type, row ) {
						return row[18];	
	                },
	                "targets": [5]
	            },
    			{ 
	                "render": function ( data, type, row ) {
						return row[5];	
	                },
	                "targets": [6]
	            },
    			{ 
	                "render": function ( data, type, row ) {
						return row[6];	
	                },
	                "targets": [7]
	            },
    			{ 
	                "render": function ( data, type, row ) {
						return row[7];	
	                },
	                "targets": [8]
	            },

    			{ 
	                "render": function ( data, type, row ) {
						return row[8];	
	                },
	                "targets": [9]
	            },
    			{ 
	                "render": function ( data, type, row ) {
						return row[9];	
	                },
	                "targets": [10]
	            },

    			{ 
	                "render": function ( data, type, row ) {
						return row[13];	
	                },
	                "targets": [11]
	            },


    			{ 
	                 "visible": false,
	                "targets": [12,13,14,15,16,17,18], //10,11,
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
     				    d.id_factura = jQuery("#id_factura").val();
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
				
				total_metro = api
					.column( 16 )
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					} );
				
				total_kilogramo = api
					.column( 17 )
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
					.column( 6)
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					} );	

				//importe
						

			        jQuery('#pieza').html( 'Total de piezas:'+ total_pieza);
			        jQuery('#kg').html( 'Total de kgs:'+number_format(total_kilogramo, 2, '.', ','));
			        jQuery('#metro').html('Total de mts:'+ number_format(total_metro, 2, '.', ','));


					//importes
					jQuery('#subtotal').html('SubTotal:'+ number_format(total_subtotal, 2, '.', ','));
					jQuery('#iva').html('IVA:' + number_format( total_iva, 2, '.', ','));
					jQuery('#total').html('Total:'+ number_format( parseFloat(total_subtotal)+parseFloat(total_iva), 2, '.', ','));						

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
				jQuery('#label_cliente').text("Cliente");
				jQuery('#label_vendedor').text("Vendedor");
				
			} else {
				jQuery('#label_cliente').text("Sucursal");
				jQuery('#label_vendedor').text("Num. Mov");
			}
				


		}	
		
	    return pre
	}, 
	
   "columnDefs": [
				{ 
		                "render": function ( data, type, row ) {
								var color;
		                        switch (row[19]){
		                          case "12": //normal rojo
		                            color = 'red';
		                             break; 
		                          case "13": //devolucion verde
		                            color = 'green';
		                             break;
		                          case "14": //defecto azul
		                            color = 'blue';
		                             break;
		                          case "15": //ajuste naranja
		                            color = 'orange';
		                             break;
		                          default: 
		                            color = 'red';            
		                        } 

		                		if (row[15]!='') {
		                			return row[1]+'<br/><b style="color:'+color+';">Cód: </b>'+row[15];	
		                		} else {
		                			return row[1];
		                		}
		                		
		                },
		                "targets": [1]   //el 3 es la imagen q ya viene formada desde el modelo
		        },   	



				{ 
	                "render": function ( data, type, row ) {
						return data;	
	                },
	                "targets": [0,2,3,4], //,5,6,7,8,9,13
	            },


				{ 
	                "render": function ( data, type, row ) {
						return row[18];	
	                },
	                "targets": [5]
	            },
    			{ 
	                "render": function ( data, type, row ) {
						return row[5];	
	                },
	                "targets": [6]
	            },
    			{ 
	                "render": function ( data, type, row ) {
						return row[6];	
	                },
	                "targets": [7]
	            },
    			{ 
	                "render": function ( data, type, row ) {
						return row[7];	
	                },
	                "targets": [8]
	            },

    			{ 
	                "render": function ( data, type, row ) {
						return row[8];	
	                },
	                "targets": [9]
	            },
    			{ 
	                "render": function ( data, type, row ) {
						return row[9];	
	                },
	                "targets": [10]
	            },

    			{ 
	                "render": function ( data, type, row ) {
						return row[13];	
	                },
	                "targets": [11]
	            },


    			{ 
	                 "visible": false,
	                "targets": [12,13,14,15,16,17,18,19], //10,11,
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

		"footerCallback": function( tfoot, data, start, end, display ) {
			var api = this.api();
			api.column(5).visible(false);		

		},	

		"columnDefs": [
			    	{ 
		                "render": function ( data, type, row ) {
		                        var color;
		                        switch (row[10]){
		                          case "12": //normal rojo
		                            color = 'red';
		                             break; 
		                          case "13": //devolucion verde
		                            color = 'green';
		                             break;
		                          case "14": //defecto azul
		                            color = 'blue';
		                             break;
		                          case "15": //ajuste naranja
		                            color = 'orange';
		                             break;
		                          default: 
		                            color = 'red';            
		                        } 


		                		if (row[9]!='') {
		                			return row[1]+'<br/><b style="color:'+color+';">Cód: </b>'+row[9];	
		                		} else {
		                			return row[1];
		                		}
		                		
		                },
		                "targets": [1]   //el 3 es la imagen q ya viene formada desde el modelo
		        	},   
			    	{ 
		                "render": function ( data, type, row ) {
		                		return data;
		                },
		                "targets": [0,2,3,4,5,6] //
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

		        	{ 	
		                 "visible": false,
		                "targets": [9,10]
		            }           
		           
		            
		        ],



		"infoCallback": function( settings, start, end, max, total, pre ) {
		    if (settings.json.totales) {
			    jQuery('#total_pieza').html( 'Total de Piezas: '+ settings.json.totales.pieza);
				jQuery('#total_kg').html( 'Total de Kgs: '+number_format(settings.json.totales.kilogramo, 2, '.', ','));
				jQuery('#total_metro').html('Total de Mts: '+ number_format(settings.json.totales.metro, 2, '.', ','));
				//jQuery('#total_precio').html('Importe Total: '+ number_format(settings.json.totales.precio, 2, '.', ','));
			} else 	{
			    jQuery('#total_pieza').html( 'Total de Piezas: 0.00');
				jQuery('#total_kg').html( 'Total de Kgs: 0.00');
				jQuery('#total_metro').html('Total de Mts: 0.00');
				//jQuery('#total_precio').html('Importe Total: 0.00');
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

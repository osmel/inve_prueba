
jQuery('#tabla_detalle').dataTable( {
  "pagingType": "full_numbers",
  "processing": true,
  "serverSide": true,
  "ajax": {
              "url" : "/procesando_detalle",
            "type": "POST",
             "data": function ( d ) {
               d.id_usuario = jQuery("#id_usuario_apartado").val();  //"0cc5510f-c452-11e4-8ada-7071bce181c3"; //
               d.id_cliente = jQuery("#id_cliente_apartado").val();  //3; //
               d.id_almacen = jQuery('#id_almacen_pedido').val(); 
               d.consecutivo_venta = jQuery('#consecutivo_venta').val();  
           } 

     },   


  "infoCallback": function( settings, start, end, max, total, pre ) {
      if (settings.json.datos) {
        jQuery('#etiq_usuario').val(  settings.json.datos.usuario);
        jQuery('#etiq_cliente').val(  settings.json.datos.cliente);
      jQuery('#etiq_comprador').val(  settings.json.datos.comprador+'  Nro.'+jQuery('#consecutivo_venta').val());

        jQuery('#etiq_fecha').val(  settings.json.datos.mi_fecha);
        jQuery('#etiq_hora').val(  settings.json.datos.mi_hora);
        jQuery('#etiq_tipo_apartado').html(  settings.json.datos.tipo_apartado);
        jQuery('#etiq_color_apartado').html('<div style="margin-right: 15px;float:left;background-color:#'+settings.json.datos.color_apartado+';width:15px;height:15px;"></div>');

        jQuery('#id_tipo_factura').val(settings.json.datos.id_tipo_factura);

      if (settings.json.datos.tipo_factura!=null) {
          jQuery('.panel-heading > span').text( settings.json.datos.tipo_pedido+' - '+settings.json.datos.tipo_factura );   
        }else {
          jQuery('.panel-heading > span').text( settings.json.datos.tipo_pedido); 
        }

    } 
      return pre
  },    

   "columnDefs": [
          { 
                  "render": function ( data, type, row ) {
            return data;  
                  },
                  "targets": [0,1,2,3,4,5,6,7,8,9,10,14],
              },


          { 
                   "visible": false,
                  "targets": [11,12,13],
              }             

  ],  

  "rowCallback": function( row, data ) {
              
        if (( data[11] != data[12]) && ( data[12] != 0)  ) {
          jQuery('td', row).addClass( "danger" );
        }

        if ( data[6] == 0 ) {   
          //jQuery('td', row).removeClass( "danger" );
        }

   }, 
 
  "fnHeaderCallback": function( nHead, aData, iStart, iEnd, aiDisplay ) {
    var arreglo =arr_apartado_detalle;
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

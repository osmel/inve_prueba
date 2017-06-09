$(function(){

  window.MY_Socket = {
    
  // Instanciar al "cliente Socket.IO" y conectar con el servidor
	//socket : io.connect('http://162.243.163.167:8080'),
  socket : io.connect(location.host+':8080'),
	//socket : io.connect('http://localhost:8080'),
  // Configurar los controladores de eventos iniciales para el cliente Socket.IO
  // estos son los que inicializan los controladores para cada evento que ocurra,
  //en este caso esta escuchando constantemente si sucede
  // una  this.socket.on('conexion' : para disparar el mensaje de "estoy trabajando"
    bindEvents : function() {
      
      this.socket.on('conexion',MY_Socket.conexionMessage);   //llama a la funcion  conexionMessage
      
      //cuando le transmiten el nuevo mensaje, al equipo del que envia el mensaje
      this.socket.on('broadcastNewPost',MY_Socket.updateMessages);

      this.socket.on('broadcastSalidaAlmacen',MY_Socket.salida_almacen);
    },

  // Esto sólo indica que una conexión Socket.IO ha comenzado.
    conexionMessage : function(data) {
      console.log(data.message);   //variable message fue la que envio
    },


  // En la actualización 'broadcastNewPost' la lista de mensajes de otros usuarios
    updateMessages : function(data) {
    // Debido a que el "mensaje se transmite(broadcasted)" dos veces (una para el equipo "team" y  nuevamente por los "administradores")
       // Necesitamos Asegurarnos que sólo se muestra una vez si el administrador está también en el mismo
       // Equipo como el remitente(sender).

		//jQuery('#etiq_conteo').val(  data.message);
		var hash_url = window.location.pathname;

    //alert(data.message);



                $.ajax({
                          url : '/conteo_tienda',
                          data : { 
                            tipo: 'tienda',
                          },
                          type : 'POST',
                          dataType : 'json',
                          success : function(dato) {  
                            $('#etiq_conteo').text(dato.vendedor+' - '+dato.tienda+' - '+dato.compra);
                      
                          }
                }); 

    //$('#etiq_conteo').text(data.message);


    mensaje_notif ='';
    caja_notificacion ='';



var url_pedidos = hash_url.split( '/' )
//console.log(a[1]);

/*
"gestionar_pedido_compra"
"cancelado"
"solicitar_modificacion"
"pendiente_revision"    
"aprobado"             

   tabla_pedido_compra


SELECT * 
FROM  `inven_historico_registros_salidas` 
WHERE mov_salida =4240 or movimiento =4240

*/

if  ( (url_pedidos[1]=="gestionar_pedido_compra") || (url_pedidos[1]=="cancelado") || (url_pedidos[1]=="solicitar_modificacion")
  || (url_pedidos[1]=="pendiente_revision") || (url_pedidos[1]=="aprobado")) {  



                $.ajax({
                          url : '/notificacion_compra',
                          data : {                             
                            id_almacen : jQuery('#id_almacen_historicos').val(),
                          },
                          type : 'POST',
                          dataType : 'json',
                          success : function(dato) {                              
                            jQuery.each(dato.cant, function (i, valor) {
                                   $(".etiq_btn"+i).text("("+valor+")");
                            });

                      
                          }
                }); 

 $('#tabla_pedido_compra').dataTable().fnDraw();

              switch(data.tipo) {
                  case "incluir_pedido":
                         mensaje_notif=  "Se incluyó apartado de tienda para salida.";
                         caja_notificacion = '.notif-bot-pedidos';
                      break;     

                      case "form_pedido":
                             mensaje_notif=  "Se eliminó apartado de tienda.";
                             caja_notificacion = '.notif-bot-pedidos';
                          break; 

                      case "proc_salida":
                             mensaje_notif=  "Salida de producto. Se actualizó el inventario.";
                             caja_notificacion = '.notif-bot-pedidos';
                          break;   

                      case "proc_pedido_compra":
                             mensaje_notif=  "Orden de compra generada.";
                             caja_notificacion = '.notif-bot-pedidos';
                          break;                    
                          case "form_cancelar_pedido_compra":
                             mensaje_notif=  "Orden de compra. Cancelada.";
                             caja_notificacion = '.notif-bot-pedidos';
                          break;         
                          case "proc_aprobado_compra":
                             mensaje_notif=  "Orden de compra aprobada.";
                             caja_notificacion = '.notif-bot-pedidos';
                          break;         
                                        

                  
              default:
                  mensaje_notif=  "notificación";
          }     


}  

if  ( (url_pedidos[1]=="pedido_detalle")  ) {  //estos son los que tienen el pedido del conteo
    $('#pedido_detalle').dataTable().fnDraw();

              switch(data.tipo) {
                  case "incluir_pedido":
                         mensaje_notif=  "Se incluyó apartado de tienda para salida.";
                         caja_notificacion = '.notif-bot-pedidos';
                      break;     

                      case "form_pedido":
                             mensaje_notif=  "Se eliminó apartado de tienda.";
                             caja_notificacion = '.notif-bot-pedidos';
                          break; 

                      case "proc_salida":
                             mensaje_notif=  "Salida de producto. Se actualizó el inventario.";
                             caja_notificacion = '.notif-bot-pedidos';
                          break;   

                      case "proc_pedido_compra":
                             mensaje_notif=  "Orden de compra generada.";
                             caja_notificacion = '.notif-bot-pedidos';
                          break;                    
                          case "form_cancelar_pedido_compra":
                             mensaje_notif=  "Orden de compra. Cancelada.";
                             caja_notificacion = '.notif-bot-pedidos';
                          break;         
                          case "proc_aprobado_compra":
                             mensaje_notif=  "Orden de compra aprobada.";
                             caja_notificacion = '.notif-bot-pedidos';
                          break;         
                                        

                  
              default:
                  mensaje_notif=  "notificación";
          }            

}  

//para los vendedores
if  ( (url_pedidos[1]=="apartado_detalle")  ) {  //estos son los que tienen el pedido del conteo

    $('#tabla_detalle').dataTable().fnDraw();

              switch(data.tipo) {
                  case "incluir_salida":
                     mensaje_notif=  "Se incluyó apartado de vendedor para salida.";
                     caja_notificacion = '.notif-bot-pedidos';
                  break;
      
                  case "form_apartado":
                      mensaje_notif=  "Se eliminó apartado de vendedor.";
                      caja_notificacion = '.notif-bot-pedidos';
                  break;

                  case "proc_salida":
                      mensaje_notif=  "Salida de producto. Se actualizó el inventario.";
                      caja_notificacion = '.notif-bot-pedidos';
                  break;   

                      case "proc_pedido_compra":
                             mensaje_notif=  "Orden de compra generada.";
                             caja_notificacion = '.notif-bot-pedidos';
                          break;                    
                          case "form_cancelar_pedido_compra":
                             mensaje_notif=  "Orden de compra. Cancelada.";
                             caja_notificacion = '.notif-bot-pedidos';
                          break;         
                          case "proc_aprobado_compra":
                             mensaje_notif=  "Orden de compra aprobada.";
                             caja_notificacion = '.notif-bot-pedidos';
                          break;         
                                                     

                  
              default:
                  mensaje_notif=  "notificación";
          }            



}  


    if  ( (hash_url=="/listado_traspaso")  ) {  //estos son los que tienen el pedido del conteo
           $('#tabla_general_traspaso').dataTable().fnDraw();
            $('#tabla_traspaso_historico').dataTable().fnDraw();




          switch(data.tipo) {


      
             case "conf_apartado":
                     mensaje_notif=  "Nuevo pedido de vendedor.";
                     caja_notificacion = '.notif-bot-pedidos';
                  break;
              case "conf_pedido":
                     mensaje_notif=  "Nuevo pedido de tienda.";
                     caja_notificacion = '.notif-bot-pedidos';
                  break;

              case "form_apartado":
                     mensaje_notif=  "Se eliminó apartado de vendedor.";
                     caja_notificacion = '.notif-bot-pedidos';
                  break;


              case "form_pedido":
                     mensaje_notif=  "Se eliminó apartado de tienda.";
                     caja_notificacion = '.notif-bot-pedidos';
                  break;     


              //salida    
              case "agregar":
                     mensaje_notif=  "Producto(s) apartado(s) pendiente(s) de incluir en salida.";
                     caja_notificacion = '.notif-bot-pedidos';
                  break;     

              case "agregar_traspaso":
                     mensaje_notif=  "Producto(s) en proceso de traspaso(s).";
                     caja_notificacion = '.notif-bot-pedidos';
                  break;     

               case "quitar_traspaso":
                     mensaje_notif=  "Liberado del estado de traspaso(s).";
                     caja_notificacion = '.notif-bot-pedidos';
                  break;       



              case "quitar":
                     mensaje_notif=  "Liberado del estado Producto(s) apartado(s) pendiente(s) de incluir en salida.";
                     caja_notificacion = '.notif-bot-pedidos';
                  break;     

              case "proc_salida":
                     mensaje_notif=  "Salida de producto. Se actualizó el inventario.";
                     caja_notificacion = '.notif-bot-pedidos';
                  break;   
                      case "proc_pedido_compra":
                             mensaje_notif=  "Orden de compra generada.";
                             caja_notificacion = '.notif-bot-pedidos';
                          break;                    
                          case "form_cancelar_pedido_compra":
                             mensaje_notif=  "Orden de compra. Cancelada.";
                             caja_notificacion = '.notif-bot-pedidos';
                          break;         
                          case "proc_aprobado_compra":
                             mensaje_notif=  "Orden de compra aprobada.";
                             caja_notificacion = '.notif-bot-pedidos';
                          break;         
                                                     


              //generar_pedido
              case "agregar_pedido":
                     mensaje_notif=  "Producto apartado. Para realizar un pedido, procese y confirme apartado.";
                     caja_notificacion = '.notif-bot-pedidos';
                  break;  

              case "quitar_pedido":
                     mensaje_notif=  "Producto desapartado por tienda.";
                     caja_notificacion = '.notif-bot-pedidos';
                  break;                                        

             //apartado de vendedores en el home de vendedor
              case "apartar":
                     mensaje_notif=  "Producto apartado. No se ha procesado el pedido.";
                     caja_notificacion = '.notif-bot-pedidos';
                  break;                                        


              case "noapartar":
                     mensaje_notif=  "Producto desapartado por vendedor.";
                     caja_notificacion = '.notif-bot-pedidos';
                  break;                                        

                 //incluye apartados y pedidos
              case "incluir_salida":
                     mensaje_notif=  "Se incluyó apartado de vendedor para salida.";
                     caja_notificacion = '.notif-bot-pedidos';
                  break;


              case "incluir_pedido":
                     mensaje_notif=  "Se incluyó apartado de tienda para salida.";
                     caja_notificacion = '.notif-bot-pedidos';
                  break;     

                  //excluye apartados y pedidos

              case "excluir_salida":
                     mensaje_notif=  "Se excluyó apartado de vendedor de la salida.";
                     caja_notificacion = '.notif-bot-pedidos';
                  break;


              case "excluir_pedido":
                     mensaje_notif=  "Se excluyó apartado de tienda de la salida.";
                     caja_notificacion = '.notif-bot-pedidos';
                  break;   


              //entrada de datos    
              case "conf_entrada":
                     mensaje_notif=  "Entrada de productos, actualizando inventario. Espere por favor.";
                     caja_notificacion = '.notif-bot-pedidos';
                  break;

               //Devolución de datos   
              case "conf_devolucion":
                     mensaje_notif=  "Devolución de productos, actualizando inventario. Espere por favor.";
                     caja_notificacion = '.notif-bot-pedidos';
                  break;    


                  
              default:
                  mensaje_notif=  "notificación";

             //http://www.webcodo.net/simple-live-notifications-with-jquery/#.VZofI3Wlyko     
          }            

    }  

    if  ( (hash_url=="/pedidos")  ) {  //estos son los que tienen el pedido del conteo
        $('#tabla_apartado').dataTable().fnDraw();
        $('#tabla_pedido').dataTable().fnDraw();

       
          switch(data.tipo) {
              case "conf_apartado":
                     mensaje_notif=  "Nuevo pedido de vendedor.";
                     caja_notificacion = '.notif-bot-vendedor';
                  break;
              case "conf_pedido":
                     mensaje_notif=  "Nuevo pedido de tienda.";
                     caja_notificacion = '.notif-bot-tienda';
                  break;

              case "form_apartado":
                     mensaje_notif=  "Se eliminó apartado de vendedor.";
                     caja_notificacion = '.notif-bot-vendedor';
                  break;


              case "form_pedido":
                     mensaje_notif=  "Se eliminó apartado de tienda.";
                     caja_notificacion = '.notif-bot-tienda';
                  break;

               //incluye apartados y pedidos de vendedor y tienda   
              case "incluir_salida":
                     mensaje_notif=  "Se incluyó apartado de vendedor para salida.";
                     caja_notificacion = '.notif-bot-vendedor';
                  break;
              case "incluir_pedido":
                     mensaje_notif=  "Se incluyó apartado de tienda para salida.";
                     caja_notificacion = '.notif-bot-tienda';
                  break;

              //excluye apartados y pedidos de vendedor y tienda
              case "excluir_salida":
                     mensaje_notif=  "Se excluyó apartado de vendedor de la salida.";
                     caja_notificacion = '.notif-bot-vendedor';
                  break;
              case "excluir_pedido":
                     mensaje_notif=  "Se excluyó apartado de tienda de la salida.";
                     caja_notificacion = '.notif-bot-tienda';
                  break;


              //entrada de datos    
              case "conf_entrada":
                     mensaje_notif=  "Entrada de productos, actualizando inventario. Espere por favor.";
                     caja_notificacion = '.notif-bot-vendedor';
                  break;

               //Devolución de datos   
              case "conf_devolucion":
                     mensaje_notif=  "Devolución de productos, actualizando inventario. Espere por favor.";
                     caja_notificacion = '.notif-bot-vendedor';
                  break;     


                  
              default:
                  mensaje_notif=  "notificación";

             //http://www.webcodo.net/simple-live-notifications-with-jquery/#.VZofI3Wlyko     
          }

    }    


    if  ( (hash_url=="/salidas")  ) {
        $('#tabla_entrada').dataTable().fnDraw();
        //$('#tabla_salida').dataTable().fnDraw();

          switch(data.tipo) {
              case "conf_apartado":
                     mensaje_notif=  "Nuevo pedido de vendedor.";
                     caja_notificacion = '.notif-bot-pedidos';
                  break;
              case "conf_pedido":
                     mensaje_notif=  "Nuevo pedido de tienda.";
                     caja_notificacion = '.notif-bot-pedidos';
                  break;

              case "form_apartado":
                     mensaje_notif=  "Se eliminó apartado de vendedor.";
                     caja_notificacion = '.notif-bot-pedidos';
                  break;


              case "form_pedido":
                     mensaje_notif=  "Se eliminó apartado de tienda.";
                     caja_notificacion = '.notif-bot-pedidos';
                  break;     


              //salida    
              case "agregar":
                     mensaje_notif=  "Producto(s) apartado(s) pendiente(s) de incluir en salida.";
                     caja_notificacion = '.notif-bot-pedidos';
                  break;     

              case "agregar_traspaso":
                     mensaje_notif=  "Producto(s) en proceso de traspaso(s).";
                     caja_notificacion = '.notif-bot-pedidos';
                  break;     

               case "quitar_traspaso":
                     mensaje_notif=  "Liberado del estado de traspaso(s).";
                     caja_notificacion = '.notif-bot-pedidos';
                  break;       



              case "quitar":
                     mensaje_notif=  "Liberado del estado Producto(s) apartado(s) pendiente(s) de incluir en salida.";
                     caja_notificacion = '.notif-bot-pedidos';
                  break;     

              case "proc_salida":
                     mensaje_notif=  "Salida de producto. Se actualizó el inventario.";
                     caja_notificacion = '.notif-bot-pedidos';
                  break;   
                      case "proc_pedido_compra":
                             mensaje_notif=  "Orden de compra generada.";
                             caja_notificacion = '.notif-bot-pedidos';
                          break;                    
                          case "form_cancelar_pedido_compra":
                             mensaje_notif=  "Orden de compra. Cancelada.";
                             caja_notificacion = '.notif-bot-pedidos';
                          break;         
                          case "proc_aprobado_compra":
                             mensaje_notif=  "Orden de compra aprobada.";
                             caja_notificacion = '.notif-bot-pedidos';
                          break;         
                                                     

              //generar_pedido
              case "agregar_pedido":
                     mensaje_notif=  "Producto apartado. Para realizar un pedido, procese y confirme apartado.";
                     caja_notificacion = '.notif-bot-pedidos';
                  break;  

              case "quitar_pedido":
                     mensaje_notif=  "Producto desapartado por tienda.";
                     caja_notificacion = '.notif-bot-pedidos';
                  break;                                        

             //apartado de vendedores en el home de vendedor
              case "apartar":
                     mensaje_notif=  "Producto apartado. No se ha procesado el pedido.";
                     caja_notificacion = '.notif-bot-pedidos';
                  break;                                        


              case "noapartar":
                     mensaje_notif=  "Producto desapartado por vendedor.";
                     caja_notificacion = '.notif-bot-pedidos';
                  break;                                        

                 //incluye apartados y pedidos
              case "incluir_salida":
                     mensaje_notif=  "Se incluyó apartado de vendedor para salida.";
                     caja_notificacion = '.notif-bot-pedidos';
                  break;


              case "incluir_pedido":
                     mensaje_notif=  "Se incluyó apartado de tienda para salida.";
                     caja_notificacion = '.notif-bot-pedidos';
                  break;     

                  //excluye apartados y pedidos

              case "excluir_salida":
                     mensaje_notif=  "Se excluyó apartado de vendedor de la salida.";
                     caja_notificacion = '.notif-bot-pedidos';
                  break;


              case "excluir_pedido":
                     mensaje_notif=  "Se excluyó apartado de tienda de la salida.";
                     caja_notificacion = '.notif-bot-pedidos';
                  break;   


              //entrada de datos    
              case "conf_entrada":
                     mensaje_notif=  "Entrada de productos, actualizando inventario. Espere por favor.";
                     caja_notificacion = '.notif-bot-pedidos';
                  break;

               //Devolución de datos   
              case "conf_devolucion":
                     mensaje_notif=  "Devolución de productos, actualizando inventario. Espere por favor.";
                     caja_notificacion = '.notif-bot-pedidos';
                  break;     


              default:
                  mensaje_notif=  "notificación";
          }


    }    


    if  ( (hash_url=="/generar_pedidos")  ) { //pedido
        $('#pedido_entrada').dataTable().fnDraw();
        //$('#pedido_salida').dataTable().fnDraw();

          switch(data.tipo) {
              case "conf_apartado":
                     mensaje_notif=  "Nuevo pedido de vendedor.";
                     caja_notificacion = '.notif-bot-pedidos';
                  break;
              case "conf_pedido":
                     mensaje_notif=  "Nuevo pedido de tienda.";
                     caja_notificacion = '.notif-bot-pedidos';
                  break;
              case "form_apartado":
                     mensaje_notif=  "Se eliminó apartado de vendedor.";
                     caja_notificacion = '.notif-bot-pedidos';
                  break;


              case "form_pedido":
                     mensaje_notif=  "Se eliminó apartado de tienda.";
                     caja_notificacion = '.notif-bot-pedidos';
                  break; 


             //salida    
              case "agregar":
                     mensaje_notif=  "Producto(s) apartado(s) pendiente(s) de incluir en salida.";
                     caja_notificacion = '.notif-bot-pedidos';
                  break;  
             
             case "agregar_traspaso":
                     mensaje_notif=  "Producto(s) en proceso traspaso(s).";
                     caja_notificacion = '.notif-bot-pedidos';
                  break;                          

             case "quitar_traspaso":
                     mensaje_notif=  "Liberado del estado de traspaso(s).";
                     caja_notificacion = '.notif-bot-pedidos';
                  break;       

              case "quitar":
                     mensaje_notif=  "Liberado del estado Producto(s) apartado(s) pendiente(s) de incluir en salida.";
                     caja_notificacion = '.notif-bot-pedidos';
                  break;     

              case "proc_salida":
                     mensaje_notif=  "Salida de producto. Se actualizó el inventario.";
                     caja_notificacion = '.notif-bot-pedidos';
                  break;   

                          case "proc_pedido_compra":
                             mensaje_notif=  "Orden de compra generada.";
                             caja_notificacion = '.notif-bot-pedidos';
                          break;                    
                          case "form_cancelar_pedido_compra":
                             mensaje_notif=  "Orden de compra. Cancelada.";
                             caja_notificacion = '.notif-bot-pedidos';
                          break;         
                          case "proc_aprobado_compra":
                             mensaje_notif=  "Orden de compra aprobada.";
                             caja_notificacion = '.notif-bot-pedidos';
                          break;         
                                   

              //generar_pedido
              case "agregar_pedido":
                     mensaje_notif=  "Producto apartado. Para realizar un pedido, procese y confirme apartado.";
                     caja_notificacion = '.notif-bot-pedidos';
                  break;  

              case "quitar_pedido":
                     mensaje_notif=  "Producto desapartado por tienda.";
                     caja_notificacion = '.notif-bot-pedidos';
                  break;                      
      
             //apartado de vendedores en el home de vendedor
              case "apartar":
                     mensaje_notif=  "Producto apartado. No se ha procesado el pedido.";
                     caja_notificacion = '.notif-bot-pedidos';
                  break;                                        


              case "noapartar":
                     mensaje_notif=  "Producto desapartado por vendedor.";
                     caja_notificacion = '.notif-bot-pedidos';
                  break;                                        

              //entrada de datos    
              case "conf_entrada":
                     mensaje_notif=  "Entrada de productos, actualizando inventario. Espere por favor.";
                     caja_notificacion = '.notif-bot-pedidos';
                  break;

               //Devolución de datos   
              case "conf_devolucion":
                     mensaje_notif=  "Devolución de productos, actualizando inventario. Espere por favor.";
                     caja_notificacion = '.notif-bot-pedidos';
                  break;     



              default:
                  mensaje_notif=  "notificación";
          }

    }    


    if  ( (hash_url=="/reportes")  ) {
        $('#tabla_reporte').dataTable().fnDraw();

          switch(data.tipo) {
              case "conf_apartado":
                     mensaje_notif=  "Nuevo pedido de vendedor.";
                     caja_notificacion = '.notif-bot-pedidos';
                  break;
              case "conf_pedido":
                     mensaje_notif=  "Nuevo pedido de tienda.";
                     caja_notificacion = '.notif-bot-pedidos';
                  break;
              case "form_apartado":
                     mensaje_notif=  "Se eliminó apartado de vendedor.";
                     caja_notificacion = '.notif-bot-pedidos';
                  break;
              case "form_pedido":
                     mensaje_notif=  "Se eliminó apartado de tienda.";
                     caja_notificacion = '.notif-bot-pedidos';
                  break;                  

             //salida    
              case "agregar":
                     mensaje_notif=  "Producto(s) apartado(s) pendiente(s) de incluir en salida.";
                     caja_notificacion = '.notif-bot-pedidos';
                  break;   
            
              case "agregar_traspaso":
                     mensaje_notif=  "Producto(s) en proceso traspaso(s).";
                     caja_notificacion = '.notif-bot-pedidos';
                  break;            
              
              case "quitar_traspaso":
                     mensaje_notif=  "Liberado del estado de traspaso(s).";
                     caja_notificacion = '.notif-bot-pedidos';
                  break;       


              case "quitar":
                     mensaje_notif=  "Liberado del estado Producto(s) apartado(s) pendiente(s) de incluir en salida.";
                     caja_notificacion = '.notif-bot-pedidos';
                  break;     

              case "proc_salida":
                     mensaje_notif=  "Salida de producto. Se actualizó el inventario.";
                     caja_notificacion = '.notif-bot-pedidos';
                  break;   

                          case "proc_pedido_compra":
                             mensaje_notif=  "Orden de compra generada.";
                             caja_notificacion = '.notif-bot-pedidos';
                          break;                    
                          case "form_cancelar_pedido_compra":
                             mensaje_notif=  "Orden de compra. Cancelada.";
                             caja_notificacion = '.notif-bot-pedidos';
                          break;         
                          case "proc_aprobado_compra":
                             mensaje_notif=  "Orden de compra aprobada.";
                             caja_notificacion = '.notif-bot-pedidos';
                          break;         
                                    

              //generar_pedido
              case "agregar_pedido":
                     mensaje_notif=  "Producto apartado. Para realizar un pedido, procese y confirme apartado.";
                     caja_notificacion = '.notif-bot-pedidos';
                  break;  

              case "quitar_pedido":
                     mensaje_notif=  "Producto desapartado por tienda.";
                     caja_notificacion = '.notif-bot-pedidos';
                  break;                                        

             //apartado de vendedores en el home de vendedor
              case "apartar":
                     mensaje_notif=  "Producto apartado. No se ha procesado el pedido.";
                     caja_notificacion = '.notif-bot-pedidos';
                  break;                                        


              case "noapartar":
                     mensaje_notif=  "Producto desapartado por vendedor.";
                     caja_notificacion = '.notif-bot-pedidos';
                  break;                                        



                 //incluye apartados y pedidos
              case "incluir_salida":
                     mensaje_notif=  "Se incluyó apartado de vendedor para salida.";
                     caja_notificacion = '.notif-bot-pedidos';
                  break;


              case "incluir_pedido":
                     mensaje_notif=  "Se incluyó apartado de tienda para salida.";
                     caja_notificacion = '.notif-bot-pedidos';
                  break;     

                  //excluye apartados y pedidos

              case "excluir_salida":
                     mensaje_notif=  "Se excluyó apartado de vendedor de la salida.";
                     caja_notificacion = '.notif-bot-pedidos';
                  break;


              case "excluir_pedido":
                     mensaje_notif=  "Se excluyó apartado de tienda de la salida.";
                     caja_notificacion = '.notif-bot-pedidos';
                  break;   


              //entrada de datos    
              case "conf_entrada":
                     mensaje_notif=  "Entrada de productos, actualizando inventario. Espere por favor.";
                     caja_notificacion = '.notif-bot-pedidos';
                  break;

               //Devolución de datos   
              case "conf_devolucion":
                     mensaje_notif=  "Devolución de productos, actualizando inventario. Espere por favor.";
                     caja_notificacion = '.notif-bot-pedidos';
                  break;     


              default:
                  mensaje_notif=  "notificación";
          }        
    }    



    //home 
    if  ( (hash_url=="/")  ) { //home
        $('#tabla_home').dataTable().fnDraw();
        $('#tabla_inicio').dataTable().fnDraw();
        $('#tabla_producto_color').dataTable().fnDraw();
        

          switch(data.tipo) {
              case "conf_apartado":
                     mensaje_notif=  "Nuevo pedido de vendedor.";
                     caja_notificacion = '.notif-bot-pedidos';
                  break;
              case "conf_pedido":
                     mensaje_notif=  "Nuevo pedido de tienda.";
                     caja_notificacion = '.notif-bot-pedidos';
                  break;
              case "form_apartado":
                     mensaje_notif=  "Se eliminó apartado de vendedor.";
                     caja_notificacion = '.notif-bot-pedidos';
                  break;


              case "form_pedido":
                     mensaje_notif=  "Se eliminó apartado de tienda.";
                     caja_notificacion = '.notif-bot-pedidos';
                  break;     


             //salida    
              case "agregar":
                     mensaje_notif=  "Producto(s) apartado(s) pendiente(s) de incluir en salida.";
                     caja_notificacion = '.notif-bot-pedidos';
                  break;     
    
              case "agregar_traspaso":
                     mensaje_notif=  "Producto(s) en proceso traspaso(s).";
                     caja_notificacion = '.notif-bot-pedidos';
                  break;      
              
              case "quitar_traspaso":
                     mensaje_notif=  "Liberado del estado de traspaso(s).";
                     caja_notificacion = '.notif-bot-pedidos';
                  break;       


              case "quitar":
                     mensaje_notif=  "Liberado del estado Producto(s) apartado(s) pendiente(s) de incluir en salida.";
                     caja_notificacion = '.notif-bot-pedidos';
                  break;     

              case "proc_salida":
                     mensaje_notif=  "Salida de producto. Se actualizó el inventario.";
                     caja_notificacion = '.notif-bot-pedidos';
                  break;   

                          case "proc_pedido_compra":
                             mensaje_notif=  "Orden de compra generada.";
                             caja_notificacion = '.notif-bot-pedidos';
                          break;                    
                          case "form_cancelar_pedido_compra":
                             mensaje_notif=  "Orden de compra. Cancelada.";
                             caja_notificacion = '.notif-bot-pedidos';
                          break;         
                          case "proc_aprobado_compra":
                             mensaje_notif=  "Orden de compra aprobada.";
                             caja_notificacion = '.notif-bot-pedidos';
                          break;         
                                   

              //generar_pedido
              case "agregar_pedido":
                     mensaje_notif=  "Producto apartado. Para realizar un pedido, procese y confirme apartado.";
                     caja_notificacion = '.notif-bot-pedidos';
                  break;  

              case "quitar_pedido":
                     mensaje_notif=  "Producto desapartado por tienda.";
                     caja_notificacion = '.notif-bot-pedidos';
                  break;                                        


             //apartado de vendedores en el home de vendedor
              case "apartar":
                     mensaje_notif=  "Producto apartado. No se ha procesado el pedido.";
                     caja_notificacion = '.notif-bot-pedidos';
                  break;                                        


              case "noapartar":
                     mensaje_notif=  "Producto desapartado por vendedor.";
                     caja_notificacion = '.notif-bot-pedidos';
                  break;                                        
                          


                 //incluye apartados y pedidos
              case "incluir_salida":
                     mensaje_notif=  "Se incluyó apartado de vendedor para salida.";
                     caja_notificacion = '.notif-bot-pedidos';
                  break;


              case "incluir_pedido":
                     mensaje_notif=  "Se incluyó apartado de tienda para salida.";
                     caja_notificacion = '.notif-bot-pedidos';
                  break;     

                  //excluye apartados y pedidos

              case "excluir_salida":
                     mensaje_notif=  "Se excluyó apartado de vendedor de la salida.";
                     caja_notificacion = '.notif-bot-pedidos';
                  break;


              case "excluir_pedido":
                     mensaje_notif=  "Se excluyó apartado de tienda de la salida.";
                     caja_notificacion = '.notif-bot-pedidos';
                  break;   

              //entrada de datos    
              case "conf_entrada":
                     mensaje_notif=  "Entrada de productos, actualizando inventario. Espere por favor.";
                     caja_notificacion = '.notif-bot-pedidos';
                  break;

               //Devolución de datos   
              case "conf_devolucion":
                     mensaje_notif=  "Devolución de productos, actualizando inventario. Espere por favor.";
                     caja_notificacion = '.notif-bot-pedidos';
                  break;     

              default:
                  mensaje_notif=  "notificación";
          }        
    }    




    if  ( (hash_url=="/traspasos")  ) { //pedido
        $('#tabla_entrada_traspaso').dataTable().fnDraw();
        //$('#pedido_salida').dataTable().fnDraw();

          switch(data.tipo) {
              case "conf_apartado":
                     mensaje_notif=  "Nuevo pedido de vendedor.";
                     caja_notificacion = '.notif-bot-pedidos';
                  break;
              case "conf_pedido":
                     mensaje_notif=  "Nuevo pedido de tienda.";
                     caja_notificacion = '.notif-bot-pedidos';
                  break;
              case "form_apartado":
                     mensaje_notif=  "Se eliminó apartado de vendedor.";
                     caja_notificacion = '.notif-bot-pedidos';
                  break;


              case "form_pedido":
                     mensaje_notif=  "Se eliminó apartado de tienda.";
                     caja_notificacion = '.notif-bot-pedidos';
                  break; 


             //salida    
              case "agregar":
                     mensaje_notif=  "Producto(s) apartado(s) pendiente(s) de incluir en salida.";
                     caja_notificacion = '.notif-bot-pedidos';
                  break;  
             
             case "agregar_traspaso":
                     mensaje_notif=  "Producto(s) en proceso traspaso(s).";
                     caja_notificacion = '.notif-bot-pedidos';
                  break;           
              
              case "quitar_traspaso":
                     mensaje_notif=  "Liberado del estado de traspaso(s).";
                     caja_notificacion = '.notif-bot-pedidos';
                  break;       


              case "quitar":
                     mensaje_notif=  "Liberado del estado Producto(s) apartado(s) pendiente(s) de incluir en salida.";
                     caja_notificacion = '.notif-bot-pedidos';
                  break;     

              case "proc_salida":
                     mensaje_notif=  "Salida de producto. Se actualizó el inventario.";
                     caja_notificacion = '.notif-bot-pedidos';
                  break;   

                          case "proc_pedido_compra":
                             mensaje_notif=  "Orden de compra generada.";
                             caja_notificacion = '.notif-bot-pedidos';
                          break;                    
                          case "form_cancelar_pedido_compra":
                             mensaje_notif=  "Orden de compra. Cancelada.";
                             caja_notificacion = '.notif-bot-pedidos';
                          break;         
                          case "proc_aprobado_compra":
                             mensaje_notif=  "Orden de compra aprobada.";
                             caja_notificacion = '.notif-bot-pedidos';
                          break;         
                                   

              //generar_pedido
              case "agregar_pedido":
                     mensaje_notif=  "Producto apartado. Para realizar un pedido, procese y confirme apartado.";
                     caja_notificacion = '.notif-bot-pedidos';
                  break;  

              case "quitar_pedido":
                     mensaje_notif=  "Producto desapartado por tienda.";
                     caja_notificacion = '.notif-bot-pedidos';
                  break;                      
      
             //apartado de vendedores en el home de vendedor
              case "apartar":
                     mensaje_notif=  "Producto apartado. No se ha procesado el pedido.";
                     caja_notificacion = '.notif-bot-pedidos';
                  break;                                        


              case "noapartar":
                     mensaje_notif=  "Producto desapartado por vendedor.";
                     caja_notificacion = '.notif-bot-pedidos';
                  break;                                        

              //entrada de datos    
              case "conf_entrada":
                     mensaje_notif=  "Entrada de productos, actualizando inventario. Espere por favor.";
                     caja_notificacion = '.notif-bot-pedidos';
                  break;

               //Devolución de datos   
              case "conf_devolucion":
                     mensaje_notif=  "Devolución de productos, actualizando inventario. Espere por favor.";
                     caja_notificacion = '.notif-bot-pedidos';
                  break;     



              default:
                  mensaje_notif=  "notificación";
          }

    }    




    //mensaje de notificacion
    if ((mensaje_notif !='') && (mensaje_notif !=  "notificación") ){
      /*
              $('<div/>', {  //texto de notificacion
                id: 'notif-bot', //id q debe estar mal
                class : 'notif-bot alert alert-info', //las clases q se le anaden
                text: mensaje_notif //'Texto de notificacion!' 
                }).appendTo(caja_notificacion)  //dentro del div que se agrega
                  .delay(5000)
                  .fadeOut();
      */
        
        //$(caja_notificacion).notify(mensaje_notif, 
         $.notify(mensaje_notif,  

              {
                // whether to hide the notification on click
                clickToHide: true,
                // whether to auto-hide the notification
                autoHide: true,
                // if autoHide, hide after milliseconds
                autoHideDelay: 1000,
                // show the arrow pointing at the element
                arrowShow: true,
                // arrow size in pixels
                arrowSize: 5,
                // default positions
                elementPosition: 'bottom left',
                globalPosition: 'top right',
                // default style
                style: 'bootstrap',
                // default class (string or [string])
                className: 'success',
                // show animation
                showAnimation: 'slideDown',
                // show animation duration
                showDuration: 400,
                // hide animation
                hideAnimation: 'slideUp',
                // hide animation duration
                hideDuration: 200,
                // padding between element and notification
                gap: 2
              }
          );

    }




        //console.log(data);

      if( ( !userIsAnAdmin() && data.team != 'admin') ||
          ( userIsAnAdmin() && data.team === 'admin') ){

    // Envía el html parcial con el nuevo mensaje a la función jQuery que lo mostrará.
       // App.showBroadcastedMessage(data.message);
      }
    },

salida_almacen : function(data) {

  
  mensaje_notif=  "CONTEO EN INVENTARIO.";
  caja_notificacion = '.notif-bot-pedidos';
  
     $.notify(mensaje_notif,  

          {
            // whether to hide the notification on click
            clickToHide: true,
            // whether to auto-hide the notification
            autoHide: true,
            // if autoHide, hide after milliseconds
            autoHideDelay: 1000,
            // show the arrow pointing at the element
            arrowShow: true,
            // arrow size in pixels
            arrowSize: 5,
            // default positions
            elementPosition: 'bottom left',
            globalPosition: 'top right',
            // default style
            style: 'bootstrap',
            // default class (string or [string])
            className: 'success',
            // show animation
            showAnimation: 'slideDown',
            // show animation duration
            showDuration: 40,
            // hide animation
            hideAnimation: 'slideUp',
            // hide animation duration
            hideDuration: 2000,
            // padding between element and notification
            gap: 2
          }
      );

    window.location.href = '/salir';  

},    
  
  sendAlmacen : function(id_almacen,tipo) {  
      var sessionId = readCookie('inven_session');
      MY_Socket.socket.emit('newAlmacen',id_almacen,sessionId,tipo);
  },

  // Esta emitirá un html parcial que contiene un mensaje nuevo,
    // Más el ID del equipo(teamId) del usuario que envía el mensaje.
    //sendNewPost : function(msg,team) {
    sendNewPost : function(msg,tipo) {  
      var sessionId = readCookie('inven_session');
      MY_Socket.socket.emit('newPost',msg,sessionId,tipo);
          /*
                jQuery.ajax({
                          url : 'session',
                          data : { 
                            tipo: 'nada',
                          },
                          type : 'POST',
                          dataType : 'json',
                          success : function(data) {  
                            if (data.exito == true) {
                                //alert('osmel');
                                console.log(msg+' - '+data.sala);
                                MY_Socket.socket.emit('newPost',msg,data.sala);
                            } else 
                            {
                              console.log('no hay datos');
                            }
                              
                          }
                });         
      */
    },

  // Únase(Join) a un socket.io 'room' basado en el equipo del usuario
    joinRoom : function(){
    // Obtener la sessionID de CodeIgniter de la cookie
      var sessionId = readCookie('inven_session');
      //console.log(sessionId);
      if(sessionId) {
    // Envía el "sessionID" al servidor Node en un esfuerzo para unirse a un "room"
        //console.log(sessionId);
                  MY_Socket.socket.emit('joinRoom',sessionId);
/*

                jQuery.ajax({
                          url : 'session',
                          data : { 
                            tipo: 'nada',
                          },
                          type : 'POST',
                          dataType : 'json',
                          success : function(data) {  
                            if (data.exito == true) {
                               console.log(data.sala);
                                MY_Socket.socket.emit('joinRoom',data.sala);

                            } else 
                            {
                              console.log('no hay datos');
                            }
                              
                          }
                });   


*/


        
      } else {
    // Si no existe sessionID, no trata de unirse a un room.
        console.log('No session id found. Broadcast disabled.');
    // esperamos cerrar la sesión url? (//forward to logout url?)
      }
    }

  } // end window.MY_Socket
  

  // Comenzando !! Start it up!
  
  MY_Socket.bindEvents();

  //Provoco el evento joinRoom para obtener la "sessionID"
  MY_Socket.joinRoom();

  //MY_Socket.sendNewPost( "mi primer mensaje", "3");
  //MY_Socket.sendNewPost( "mi primer mensaje");

  // Read a cookie. http://www.quirksmode.org/js/cookies.html#script
  function readCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
      var c = ca[i];
      while (c.charAt(0)==' ') c = c.substring(1,c.length);
      if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
  }
   
/* Este buscará la insignia(badge) 'Admin' en la ventana actual.
   Este es un método super-hacky "para determinar si el usuario es un administrador"
   Para que los mensajes desde el usuario del mismo equipo que el administrador no se
   dupliquen en el flujo de mensajes(message stream). */
   
  function userIsAnAdmin(){
    var val = false;
    $('.userTeamBadge').children().each(function(i,el){
       if ($(el).text() == 'Admin'){
         val = true;
       }
    });
    return val;
    
  }
});

     var io = require('socket.io').listen(8080);
     var redis = require('redis');  //2

 var mysql  = require("mysql");


var pool    =    mysql.createPool({
      connectionLimit   :   100,
      host              :   'localhost',
      user              :   'root',
      password          :   'root',
      database          :   'iniciativatextilalmacen',
      debug             :   false
});


   io.sockets.on('connection', function (socket) {

        var rClient = redis.createClient();

        //mensaje en la consola y al cliente conectado
       // console.log("1 Usuario esta conectado");
        socket.emit('conexion', { message: 'Soy el servidor, Que quieres??' });
        
        //esperando evento "joinRoom", 
        //este debe correr cuando el usuario
        //realmente se loguee
          socket.on('joinRoom', function(sessionId){
            var parsedRes, team, isAdmin;

            console.log(sessionId);

            // Use the redis client to get all session data for the user
            rClient.get('sessions:'+sessionId, function(err,res){
              //console.log(res);
              

              //console.log(parsedRes.id_perfil);

              
              //console.log(res);
              parsedRes = JSON.parse(res);
              team = parsedRes.sala; //id_perfil //parsedRes.teamId;
              //console.log(parsedRes.sala);
              if (team==1){
                isAdmin = true;
              }
                // isAdmin = parsedRes.isAdmin;

              // Join a room that matches the user's teamId
              //console.log('Joining room ' + team.toString());

              //el usuario se unira a la habitacion (1,2,3.. o n)
              socket.join(team.toString());
              
              

              // Join the 'admin' room if user is an admin
              if (isAdmin) {
                //console.log('Joining room for Admins');

                //el usuario se unira a la habitacion ('admin')
                socket.join('admin');
              }

            

            });

          }); // fin de joinRoom


         socket.on('newPost', function (post,sessionId,tipo) {   // 'newPost', 'mensaje', 1
          
             var parsedRes, team, isAdmin;

              rClient.get('sessions:'+sessionId, function(err,res){
                  
                  parsedRes = JSON.parse(res);
                   team = parsedRes.sala; //id_perfil //parsedRes.teamId;
                
                  //Se transmite el mensaje al equipo del remitente
                  var broadcastData = {message: post, team: team,tipo: tipo};
                  socket.broadcast.to(team.toString()).emit('broadcastNewPost',broadcastData);

                  // Se transmite el mensaje a todos los administradores
                  broadcastData.team = 'admin';
                  socket.broadcast.to('admin').emit('broadcastNewPost',broadcastData);

            
              });
            
          }); //fin del socket newPost

          


    });  //fin de la conexion

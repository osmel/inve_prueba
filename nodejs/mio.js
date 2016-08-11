 var io = require('socket.io').listen(8080);

var redis = require('redis');  //2

io.sockets.on('connection', function (socket) {
    
    var rClient = redis.createClient(); //2

    socket.emit('conexion', { message: 'Soy el servidor, Que quieres??' });

	
	
	
	

 socket.on('newPost', function (post,sessionId) {   // 'newPost', 'mensaje', 1
//console.log('Broadcasting a post to team: ' + team.toString());

//console.log("post: "+post); // mi primer mensaje
//console.log("team: "+ team); //1
//console.log("sessionId: "+ sessionId);
//console.log(sessionId);
  
     var parsedRes, team, isAdmin;

     rClient.get('sessions:'+sessionId, function(err,res){
          
          parsedRes = JSON.parse(res);
           team = parsedRes.id_perfil; //parsedRes.teamId;
        
          //Se transmite el mensaje al equipo del remitente
          
          
          var broadcastData = {message: post, team: team};
          socket.broadcast.to(team.toString()).emit('broadcastNewPost',broadcastData);

          // Se transmite el mensaje a todos los administradores
          broadcastData.team = 'admin';
          socket.broadcast.to('admin').emit('broadcastNewPost',broadcastData);

    
      });

  });












    
    socket.on('joinRoom', function(sessionId){
      var parsedRes, team, isAdmin;

      // Use the redis client to get all session data for the user
      rClient.get('sessions:'+sessionId, function(err,res){
        //console.log(res);
        

        //console.log(parsedRes.id_perfil);

        
        //console.log(res);
        parsedRes = JSON.parse(res);
        team = parsedRes.id_perfil; //parsedRes.teamId;
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




});

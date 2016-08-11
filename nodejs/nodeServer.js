var sys = require('sys');
var net = require('net');
var io = require('socket.io').listen(3400);

var tcp = net.createConnection(9600); // usar serproxy para usar localhost:5331
tcp.setEncoding('ascii');
tcp.addListener('connect', function(){
  sys.puts('conectado a TCP');
});

io.sockets.on('connection', function (socket) {
  socket.emit('newconnection', { hello: 'world' });

  socket.on('dataclient', function (data) {
    sys.puts(data.theword) // escribir en la consola
    tcp.write(data.theword);
  });
  
  socket.on('disconnect', function () {
    sys.puts('user disconnected');
  });

  websocket = socket;
});

tcp.addListener('data', function(dat){
  sys.puts('dato TCP');
  sys.puts(sys.inspect(dat)); // output array object
 
  websocket.emit('datatcp', { data: dat });
});
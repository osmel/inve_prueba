//http://www.codeproject.com/Articles/389676/Arduino-and-the-Web-using-NodeJS-and-SerialPort
var com = require("serialport");

//Lista los puertos
com.list(function (err, ports) {
  ports.forEach(function(port) {
    console.log(port.comName); //COM4
    console.log(port.pnpId); //DLSUSB_05F9_4204\COMMPORT01\6&107B1B07&0&0000
    console.log(port.manufacturer); //USB-COM Port Driver
  });
});

//agarra el  --> port = "COM4"  baudrate = 9600
var serialPort = new com.SerialPort("COM4", {
	baudRate: 9600,
	dataBits: 8,
	parity: 'none',
	stopBits: 1,
    parser: com.parsers.readline('\r\n')
});

//abrir el port 
serialPort.on('open',function() {
  console.log('Port open');
});

//leer los datos que emite el port.
serialPort.on('data', function(data) {
  console.log(data);
});

/*



com.on("open", function () {
  console.log('open');
  serialPort.on('data', function(data) {
    console.log('data received: ' + data);
  });
  com.write("ls\n", function(err, results) {
    console.log('err ' + err);
    console.log('results ' + results);
  });
});

  */
/*
serialPort.on('open',function() {
  console.log('Port open');
});

serialPort.on('data', function(data) {
  console.log(data);
});
*/

/*
var serialPort = require("serialport");
//lista mis puertos
serialPort.list(function (err, ports) {
  ports.forEach(function(port) {
    console.log(port.comName);
    console.log(port.pnpId);
    console.log(port.manufacturer);
  });
});
*/
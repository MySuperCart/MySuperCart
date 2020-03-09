var app = require('express')();
var server = require('http').Server(app);
var io = require('socket.io')(server);

server.listen(9001);

// Client Socket
console.log("running handler function");
var client_socket = require('socket.io-client')('http://localhost:9001');
client_socket.on('connect', function(){
	console.log("client_socket connected");
});
client_socket.on('disconnect', function(){
	console.log("client_socket disconnected");
});

app.get('/news', function (req, res) {

	client_socket.emit('news', { hello: 'world' });
	client_socket.disconnect();
	res.writeHead(200);
	res.end('Hello Word');
});

io.on('connection', function (socket) {
  // socket.emit('news', { hello: 'world' });
  socket.on('news', function (data) {
    console.log(data);
  });
});

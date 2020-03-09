var app = require('express')();
const bodyParser = require('body-parser');
var server = require('http').Server(app);
var io = require('socket.io')(server);

app.use(bodyParser.urlencoded({ extended: true }));

server.listen(9001);

// Client Socket
var client_socket = require('socket.io-client')('http://localhost:9001');
// client_socket.on('connect', function(){});
// client_socket.on('disconnect', function(){});


app.get('/', function(req, res){
  res.sendFile(__dirname + '/index.html');
});

app.post('/news', function (req, res) {
	console.log(req.body.message);
	client_socket.emit('news', req.body.message);
	res.status(200).json({ "success": true });
});

io.on('connection', function (socket) {
	socket.on('news', function (data) {
		io.emit('news', data);
	});
});

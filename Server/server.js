/**
 * @author Léo Benoist jobs.leo@benoi.st
 */

var container = {};

container.express = require('express');
container.app = container.express();
container.server = require('http').createServer(container.app);
container.io = require('socket.io').listen(container.server);

require(__dirname + '/app/config/config')(container);
require(__dirname + '/app/config/security')(container);
require(__dirname + '/app/config/routing')(container);

require(__dirname + '/src/sockets/io')(container);

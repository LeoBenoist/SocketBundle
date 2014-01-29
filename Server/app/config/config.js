module.exports = function (container) {

    container.port = 1337;
    container.backend_ip = '127.0.0.1';

    require(__dirname + '/config_express')(container);
    require(__dirname + '/config_socketio')(container);
    require(__dirname + '/config_model')(container);

}
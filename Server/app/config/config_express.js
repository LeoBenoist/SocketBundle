module.exports = function (container) {

    container.app.configure(function () {
        container.app.set('port', process.env.PORT || container.port);
        container.app.use(container.express.logger('dev'));
        container.app.use(container.express.errorHandler());
        container.app.use(container.express.bodyParser());
        container.app.use(container.express.methodOverride());
    });

    container.server.listen(container.port);
}
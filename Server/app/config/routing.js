module.exports = function (container) {
    var controlDir = __dirname + "/../../src/controllers/";

    var safire = require(controlDir + 'safire.http')(container);
    container.app.get('/', function (req, res) {
        container.filter.backend(req, res, safire.index);
    });
    container.app.post('/safire/user/session/register', function (req, res) {
        container.filter.backend(req, res, safire.userSessionRegister);
    });
    container.app.post('/safire/label/session/grant', function (req, res) {
        container.filter.backend(req, res, safire.labelSessionGrant);
    });
    container.app.post('/safire/label/update', function (req, res) {
        container.filter.backend(req, res, safire.labelUpdate);
    });
}
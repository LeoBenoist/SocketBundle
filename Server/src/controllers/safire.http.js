module.exports = function (container) {

    var SafireController = {};

    SafireController.index = function (req, res) {
        res.send('Real time server is up');
    }

    SafireController.userSessionRegister = function (req, res) {
        container.model.registerSessionUser(req.body.session, req.body.user);
        res.send('');
    }

    SafireController.labelSessionGrant = function (req, res) {

        container.model.grantAUserLabel(req.body.user, req.body.label);
        res.send('');
    }

    SafireController.labelUpdate = function (req, res) {
        if (container.model.labelSession[req.body.label] != null) {
            var sockets = container.model.labelSession[req.body.label];

            for (var i = 0 in sockets) {
                sockets[i].emit(req.body.label, req.body.data);
                console.log("Update : " + req.body.label + " send to user : " + i);
            }
        } else {
            res.send('INFO : This label is not registered by any user' + req.body.label);
        }
        res.send('');
    }

    return SafireController;
}
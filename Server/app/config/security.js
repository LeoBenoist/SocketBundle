module.exports = function (container) {

    container.filter = {};

    container.filter.backend = function (req, res, method) {
        if (req.ip === container.backend_ip) {
            method(req, res);
        } else {
            res.send('403 Forbidden');
        }
    }

}

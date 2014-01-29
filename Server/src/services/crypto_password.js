module.exports = function (password) {
    return require('crypto').createHmac('sha1', password).digest('hex');
}
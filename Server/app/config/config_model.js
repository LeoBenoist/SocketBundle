module.exports = function (container) {
    var modelDir = __dirname + "/../../src/model/";

    require(modelDir + 'user')(container);

}
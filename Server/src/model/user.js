module.exports = function (container) {
    container.model = {};

    container.model.sessionUser = []; //Temp array to match sf2 user to a new user
    container.model.userSocket = {};
    container.model.labelSession = {};
    container.model.grantUserLabel = {};


    container.model.registerSessionUser = function (session, user) {
        container.model.sessionUser[session] = user;
        console.log("INFO SF2 : New User id : " + user + " session : " + session);
    }

    container.model.registerUserSocket = function (session, socket) {

        if (container.model.sessionUser[session] != null) {
            var userId = container.model.sessionUser[session];
            if (container.model.userSocket[userId] == null) {
                container.model.userSocket[userId] = [];
            }
            container.model.userSocket[userId][session] = socket;
            delete container.model.sessionUser[session]; //Delete Temps
            console.log("INFO : New User " + session);
            return userId;
        } else {
            console.log("Erorr : Session not match " + session);
        }
    }


    container.model.registerLabelSession = function (label, session, socket) {
        if (container.model.labelSession[label] == null) {
            container.model.labelSession[label] = {};
        }

        container.model.labelSession[label][session] = socket;

        console.log("INFO : Label : " + label + " register by : " + session);
    }

    container.model.unregisterLabelSession = function (label, session) {
        if (container.model.labelSession[label]) {
            if (container.model.labelSession[label][session]) {
                delete container.model.labelSession[label][session];
            }
        }
    }

    container.model.grantAUserLabel = function (user, label) {
        if (container.model.grantUserLabel[user] == null) {
            container.model.grantUserLabel[user] = {};
        }
        container.model.grantUserLabel[user][label] = true;
        console.log("INFO SF2 : Label : " + label + " granted for : " + user);
    }


}






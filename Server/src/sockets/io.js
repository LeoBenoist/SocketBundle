module.exports = function (container) {
    container.io.sockets.on('connection', function (socket) {

        socket.on('session', function (session) {

            var userId = container.model.registerUserSocket(session, socket);

            socket.on('label', function (label) {
                if (container.model.grantUserLabel[userId]) {
                    if (container.model.grantUserLabel[userId][label]) {
                        container.model.registerLabelSession(label, session, socket);
                    }
                }
                //todo Delete labels after disconnent with registeredLabels
            });

            socket.on('removelabel', function (label) {
                console.log("Trying to remove : " + label + " for user : " + session);
                container.model.unregisterLabelSession(label, session);
            });

            socket.on('disconnect', function () {
                if (container.model.userSocket[userId]) {
                    if (container.model.userSocket[userId][session]) {
                        delete container.model.userSocket[userId][session];
                        //Remove all granted label
                        for (var label in container.model.grantUserLabel[userId]) {
                            if (container.model.labelSession[label]) {
                                if (container.model.labelSession[label][session]) {
                                    delete container.model.labelSession[label][session];
                                }
                            }
                        }
                    }

                    try {
                        if (Object.keys(container.model.userSocket[userId]).length == 0) {
                            setTimeout(function () {
                                try {
                                    if (Object.keys(container.model.userSocket[userId]).length == 0) {
                                        delete container.model.userSocket[userId];
                                        if (container.model.grantUserLabel[userId]) {
                                            delete container.model.grantUserLabel[userId];
                                        }
                                        console.log("INFO : Total Disconnect User " + session);
                                    }
                                } catch (e) {
                                    console.log("ERROR : Timeout Crash");
                                }
                            }, 10000);

                        }
                    }
                    catch (e) {
                    }
                    console.log("INFO : Disconnect User " + session);
                    //console.log(container.model.sessionUser);
                    //console.log(container.model.userSocket);
                    //console.log(container.model.labelSession);
                    //console.log(container.model.grantUserLabel);
                }
            });
        });
    });
}

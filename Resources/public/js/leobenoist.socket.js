function unsubscribeUpdate(label) {
    if (socket != undefined) {
        socket.emit('removelabel', label);
        socket.removeAllListeners(label);
    }
}
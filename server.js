const express = require("express");//use express

const app = express(); // create instance of express

const http = require("http").createServer(app); //use http with instance of express

const port = 3000;//start the server

const io = require("socket.io")(http);// create socket instance with http

http.listen(port, function() {
    console.log("listening the port "+ port);
});

//connection to emit
io.sockets.on('connection' , function (socket){
    socket.on("send" , function (data){
        io.sockets.emit("receiver" , data);
    });
});

//add listener for new connection
io.on("connection", function(socket) {
    //this is socket for each user
    console.log("user Connected ", socket.id);

    //server should listen from each client via it's socket
    socket.on("newMessage" , function (data) {
        console.log("Client Says: " , data);

        //server will send message to all connected clients
        // send same message back to all users
        io.emit("newMessage" , data);
    });
});

app.get("/", function (request, result) {
    result.send("Hello motherfuckers");
})

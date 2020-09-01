<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <title>Messaging</title>


    <script>


    </script>
</head>
<body>

<!--  list where all messages will be displayed-->
<ul id="messages"></ul>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="js/socket.io.js"></script>

<script>

    const server = "http://localhost:3000";
    let ioC;
    let socket;

    let now = new Date();
    let localTime = now.toLocaleTimeString('tr-TR', { hour12: false });

    function curr_date(){
        let curr_date = now.getDate();
        let curr_month = now.getMonth() + 1; //Months are zero base
        let curr_year = now.getFullYear();
        return curr_date + "/" + curr_month + "/" + curr_year;}

    let curr_dates = curr_date();


    $(document).ready(function () {
        ioC =  io(server);
        socket = ioC.connect();
        console.log(socket);
        if (socket) {

            const name = prompt("Please enter a Nickname");
            const personId = uniqId(16);

            let emit = socket.emit("send" , name);

            if (emit !== false) {
                sessionStorage.ssetItem('personId' , personId);
                sessionStorage.setItem('name' , name);

                console.log('Person Id From Session : ' + sessionStorage.getItem('personId') + '\nName From Session :' + sessionStorage.getItem('name'));

                //client will listen from server
                ioC.on("newMessage" , function (data){
                    console.log("Server says" , data);

                    //display message
                    let li = document.createElement("li");
                    li.innerHTML = data.name + ':   ' + data.message + ' :  ' + data.date + '-' + data.curr_date;

                    let messages = document.getElementById("messages");
                    messages.appendChild(li);
                });
            } else {
                alert();
            }
        }
    });


    function uniqId(length) {
        let resultId         = '';
        let characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        let charactersLength = characters.length;
        for (let i = 0; i < length; i++) {
            resultId += characters.charAt(Math.floor(Math.random() * charactersLength));
        }
        return resultId;
    }

    function sendMessage(){
        //get message
        let message = document.getElementById("message");

        //sending message from client
        ioC.emit("newMessage" , {'message': message.value, 'name': sessionStorage.getItem('name') , 'date' : localTime , 'curr_date' : curr_dates});

        message.value  = ''; //clear textarea after sending message
        //this is prevent the form from submitting
        return false;
    }
</script>


<form onsubmit="event.preventDefault(); return sendMessage();">
    <input id="message" placeholder="Enter Message">
    <input type="submit" value="Send">
    <hr>
</form>
</body>
</html>

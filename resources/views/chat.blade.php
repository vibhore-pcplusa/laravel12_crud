<!DOCTYPE html>
<html>
<head>
    <title>Chat App</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    
    @vite(['resources/js/app.js'])
    <style>
        body { font-family: Arial; padding: 20px; }
        #messages { border: 1px solid #ccc; height: 300px; padding: 10px; overflow-y: auto; }
        #message-input { width: 80%; padding: 10px; }
        #send-btn { padding: 10px 20px; }
    </style>
</head>
<body>

<h2>Live Chat</h2>
<div id="client-count">Connected Clients: 0</div>
<div id="messages"></div>

<br>

<input type="text" id="message-input" placeholder="Type message..." />
<button id="send-btn">Send</button>

</body>
</html>

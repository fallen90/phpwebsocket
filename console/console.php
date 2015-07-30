<!DOCTYPE html>
<html>
<head>
<meta charset='UTF-8' />
<style type="text/css">
<!--
.chat_wrapper {
	width: 600px;
	margin-right: auto;
	margin-left: auto;
	background: #CCCCCC;
	border: 1px solid #999999;
	padding: 10px;
	font: 12px 'lucida grande',tahoma,verdana,arial,sans-serif;
}
.chat_wrapper .message_box {
	background: #FFFFFF;
	height: 500px;
	overflow: auto;
	padding: 10px;
	border: 1px solid #999999;
}
.chat_wrapper .panel input{
	padding: 2px 2px 2px 5px;
}
.system_msg{color: #BDBDBD;font-style: italic;}
.user_name{font-weight:bold;}
.user_message{color: #88B6E0;}
#user_typing {
	display:none;
}
p.pre {
	white-space: pre-wrap;
	border:1px solid #111;
	margin-top:4px;
	margin-bottom:4px;
}
-->
</style>
</head>
<body>	
<?php 
$colours = array('007AFF','FF7000','FF7000','15E25F','CFC700','CFC700','CF1100','CF00BE','F00');
$user_colour = array_rand($colours);
?>

<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>

<script language="javascript" type="text/javascript">  
var wsUri = "ws://192.168.5.103:32154/"; 	
	websocket = new WebSocket(wsUri);

$(document).ready(function(){
	websocket.onopen = function(ev) {
		console.log("Connection Openned", ev);
	}

	$('#send-btn').click(function(){
		websocket.send($('#message').val());
	});

	websocket.onmessage = function(ev) {
		$('#message_box').append('<p class="pre">' + ev.data + '</pre>');
	};
	
	websocket.onerror	= function(ev){
		console.error("ERROR SOCKET",ev);
	}; 

	websocket.onclose 	= function(ev){
		console.info("Connection Closed");
	}; 
});

</script>
<div class="chat_wrapper">
	<div class="message_box" id="message_box"></div>
	<div class="panel">
		<input type="text" name="name" id="name" placeholder="Your Name" maxlength="10" style="width:20%"  />
		<input type="text" name="message" id="message" placeholder="Message" maxlength="80" style="width:60%" />
		<button id="send-btn">Send</button>
		<span id="user_typing"><span class='user'></span> is typing...</span>
	</div>
</div>

</body>
</html>
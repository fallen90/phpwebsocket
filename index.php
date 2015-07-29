<!DOCTYPE html>
<html>
<head>
<meta charset='UTF-8' />
<style type="text/css">
<!--
.chat_wrapper {
	width: 500px;
	margin-right: auto;
	margin-left: auto;
	background: #CCCCCC;
	border: 1px solid #999999;
	padding: 10px;
	font: 12px 'lucida grande',tahoma,verdana,arial,sans-serif;
}
.chat_wrapper .message_box {
	background: #FFFFFF;
	height: 150px;
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
var wsUri = "ws://192.168.5.103:9090/server2.php"; 	
	websocket = new WebSocket(wsUri);
$(document).ready(function(){

	var user = 'user_' + new Date().getTime();
	console.log('user ' + user + ' has connected');
	
	//create a new WebSocket object.
	 
	
	websocket.onopen = function(ev) { // connection is open 
		$('#message_box').append("<div id='msg_" + new Date().getTime() + "' class=\"system_msg\">Connected!</div>"); //notify user
		var msg = {
			message: 'User '+user+' connected',
			name: 'system',
			color : '111'
		};
		websocket.send(JSON.stringify(msg));
	}

	$('#send-btn').click(function(){ //use clicks message send button	
		var mymessage = $('#message').val(); //get message text
		var myname = $('#name').val(); //get user name
		
		if(myname == ""){ //empty name?
			alert("Enter your Name please!");
			return;
		}
		if(mymessage == ""){ //emtpy message?
			return;
		}
		
		//prepare json data
		var msg = {
		message: mymessage,
		name: myname,
		color : '<?php echo $colours[$user_colour]; ?>'
		};
		//convert and send data to server
		websocket.send(JSON.stringify(msg));
		$('#message').val(''); //reset text
	});
	$('#message').on('change keyup keydown paste', function(e){
		var msg = {
			type : 'typing',
			message: $('#message').val(),
			name: user
		};
		//convert and send data to server
		websocket.send(JSON.stringify(msg));
	});
	//#### Message received from server?
	websocket.onmessage = function(ev) {
		var msg = JSON.parse(ev.data); //PHP sends Json data
		var type = msg.type; //message type
		var umsg = msg.message; //message text
		var uname = msg.name; //user name
		var ucolor = msg.color; //color
		var msg_id = new Date().getTime();
		console.log("recieved message of type " + type);


		if (type=='typing' && uname != user){
			$('#user_typing').show().find('.user').html(user + " " + umsg);
		}


		if(type == 'usermsg') 
		{
			$('#message_box').append("<div id='msg_" + msg_id + "'><span class=\"user_name\" style=\"color:#"+ucolor+"\">"+uname+"</span> : <span class=\"user_message\">"+umsg+"</span></div>");
			$('#user_typing').hide().find('.user').html('');
			$("#message_box").scrollTop($('#message_box').scrollTop() + $('#msg_' + msg_id).position().top);
		}
		if(type == 'system')
		{
			$('#message_box').append("<div id='msg_" + msg_id + "' class=\"system_msg\">"+umsg+"</div>");
			$("#message_box").scrollTop($('#message_box').scrollTop() + $('#msg_' + msg_id).position().top);
		}
		
		
	};
	
	websocket.onerror	= function(ev){$('#message_box').append("<div class=\"system_error\">Error Occurred - "+ev.data+"</div>");}; 
	websocket.onclose 	= function(ev){$('#message_box').append("<div class=\"system_msg\">Connection Closed</div>");}; 
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
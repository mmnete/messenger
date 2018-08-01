<?php
$db = new mysqli("localhost","root","","messanger_db");
?>
<!DOCTYPE html>
<html>
<head>
	<title>Messenger</title>
	<style type="text/css">
	html {
		height: 100%;
	}
	body {
		margin: 0px;
		padding: 0px;
		height: 100%;
		font-family: Helvetica, Arial, Sans-serif;
		font-size: 14px;
	}
	.msg-container {
		width: 100%;
		height: 100%;
	}
	.header {
		width: 100%;
		height: 30px;
		border-bottom: 1px solid #CCC;
		text-align: center;
		padding: 15px 0px 5px;
		font-size: 20px;
		font-weight: normal;
	}
	.msg-area {
		height: calc(100% - 102px);
		width: 100%;
		background-color:#FFF;
		overflow-y: scroll;
	}
	.msginput {
		padding: 5px;
		margin: 10px;
		font-size: 14px;
		width: calc(100% - 20px);
		outline: none;
	}
	.bottom {
		width: 100%;
		height: 50px;
		position: fixed;
		bottom: 0px;
		border-top: 1px solid #CCC;
		background-color: #EBEBEB;
	}
	#whitebg {
		width: 100%;
		height: 100%;
		background-color: #FFF;
		overflow-y: scroll;
		opacity: 0.6;
		display: none;
		position: absolute;
		top: 0px;
		z-index: 1000;
	}
	#loginbox {
		width: 600px;
		height: 350px;
		border: 1px solid #CCC;
		background-color: #FFF;
		position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
		z-index: 1001;
		display: none;
	}
	h1 {
		padding: 0px;
		margin: 20px 0px 0px 0px;
		text-align: center;
		font-weight: normal;
	}
	button {
		background-color: #43ACEC;
		border: none;
		color: #FFF;
		font-size: 16px;
		margin: 0px auto;
		width: 150px;
	}
	.buttonp {
		width: 150px;
		margin: 0px auto;
	}
	.msg {
		margin: 10px 10px;
		background-color: #f1f0f0;
		max-width: calc(45% - 20px);
		color: #000;
		padding: 10px;
		font-size: 14px;
	}
	.msgfrom {
		background-color: #0084ff;
		color: #FFF;
		margin: 10px 10px 10px 55%;
	}
	.msgarr {
		width: 0;
		height: 0;
		border-left: 8px solid transparent;
		border-right: 8px solid transparent;
		border-bottom: 8px solid #f1f0f0;
		transform: rotate(315deg);
		margin: -12px 0px 0px 45px;
	}
	.msgarrfrom {
		border-bottom: 8px solid #0084ff;
		float: right;
		margin-right: 45px;
	}
	.msgsentby {
		color: #8C8C8C;
		font-size: 12px;
		margin: 4px 0px 0px 10px;
	}
	.msgsentbyfrom {
		float: right;
		margin-right: 12px;
	}
	</style>
</head>
<body onload="checkcookie(); update();">
<div id="whitebg"></div>
<div id="loginbox">
<h1>Pick a username:</h1>
<p><input type="text" name="pickusername" id="cusername" placeholder="Pick a username" class="msginput"></p>
<p class="buttonp"><button onclick="chooseusername()">Choose Username</button></p>
</div>
<div class="msg-container">
	<div class="header">Messenger <button onclick="logout()" style="position: absolute; right: 10px; top: 15px;">Exit</button></div>
	<div class="msg-area" id="msg-area"></div>
	<div class="bottom"><input type="text" name="msginput" class="msginput" id="msginput" onkeydown="if (event.keyCode == 13) sendmsg()" value="" placeholder="Enter your message here ... (Press enter to send message)"></div>
</div>
<script type="text/javascript">

  var msgArea = document.getElementById("msg-area");
  var msginput = document.getElementById("msginput");

 function showLogin(){
   document.getElementById("whitebg").style.display = "inline-block";
   document.getElementById("loginbox").style.display = "inline-block";
 }

 function hideLogin(){
   document.getElementById("whitebg").style.display = "none";
   document.getElementById("loginbox").style.display = "none";
 }

 function chooseusername()
 {
   var user = document.getElementById("cusername").value;
   document.cookie = "messengerUname=" + user;
   checkcookie();
 }

 function checkcookie(){
   if(document.cookie.indexOf("messengerUname") == -1)
   {
     showLogin();
   }else
   {
     hideLogin();
   }
 }

 function getcookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for(var i = 0; i <ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}


 function update()
 {

   if(document.cookie.indexOf("messengerUname") == -1)
   {
     return;
   }

      var xmlhttp = new XMLHttpRequest();
      var username = getcookie("messengerUname");
      var output = "";

     xmlhttp.onreadystatechange = function (){
       if(xmlhttp.readyState == 4 && xmlhttp.status == 200)
       {
          var response = xmlhttp.responseText.split("\n");
          var r1 = response.length;
          var item = "";

          for(var i = 0; i < r1; i++)
          {
            item = response[i].split("\\");
            if(item[1] != undefined)
            {
                if (item[0] == username) {
						     	output += "<div class=\"msgc\" style=\"margin-bottom: 30px;\"> <div class=\"msg msgfrom\">" + item[1] + "<div onclick='deleteMessage("+item[2]+")' style='color:red;'>X</div></div> <div class=\"msgarr msgarrfrom\"></div> <div class=\"msgsentby msgsentbyfrom\">Sent by " + item[0] + "</div> </div>";
					    	} else {
						     	output += "<div class=\"msgc\"> <div class=\"msg\">" + item[1] + "</div> <div class=\"msgarr\"></div> <div class=\"msgsentby\">Sent by " + item[0] + "</div> </div>";
					    	}
            }
          }
           msgArea.innerHTML = output;
           msgArea.scrollTop  = msgArea.scrollHeight;
       }
     }

     xmlhttp.open("GET","get-messages.php",true);
     xmlhttp.send();
 }

 function escapehtml(text) {
  return text
      .replace(/&/g, "&amp;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;")
      .replace(/"/g, "&quot;")
      .replace(/'/g, "&#039;");
}

 function sendmsg()
 {
   var message = document.getElementById("msginput").value;
   if(message != "")
   {
      var username = getcookie("messengerUname");

      var xmlhttp = new XMLHttpRequest();

      xmlhttp.onreadystatechange = function (){
        if(xmlhttp.readyState == 4 && xmlhttp.status == 200)
        {
          message = escapehtml(message)
          msgArea.innerHTML += "<div class=\"msgc\" style=\"margin-bottom: 30px;\"> <div class=\"msg msgfrom\">" + message + "/nLoading...</div> <div class=\"msgarr msgarrfrom\"></div> <div class=\"msgsentby msgsentbyfrom\">Sent by " + username + "</div> </div>";
          msginput.value = "";
        }
      }

      xmlhttp.open("GET","update-messages.php?username="+username+"&message="+message, true);
      xmlhttp.send();
   }

 }


setInterval(function(){

  update();

}, 2500);


function logout()
{
  //we will just delete the coookie.....
  document.cookie = "messengerUname" + '=; expires=Thu, 01 Jan 1970 00:00:01 GMT;';
  msgArea.innerHTML = "";
  checkcookie();
}

function deleteMessage(id)
{

  var xmlhttp = new XMLHttpRequest();


  xmlhttp.onreadystatechange = function (){
    if(xmlhttp.readyState == 4 && xmlhttp.status == 200)
    {
      alert("Message deleted!");
    }
  }

  xmlhttp.open("GET","delete-messages.php?id="+id,true);
  xmlhttp.send();

}



</script>
</body>
</html>

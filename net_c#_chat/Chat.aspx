<%@ Page Language="C#" AutoEventWireup="true" CodeFile="Chat.aspx.cs" Inherits="Chat" %>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" >
<head id="Head1" runat="server">
    <title>Комета</title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
<script src="js/jQuery.Xaoc.js" type="text/javascript"></script>
<!-- jQuery -->
<script type="text/javascript" src="js/jquery.js"></script>
<!-- iColor-->
<script type="text/javascript" src="js/iColorPicker.js"></script>
<!-- markItUp! -->
<script type="text/javascript" src="js/jquery.markitup.js"></script>
<!-- markItUp! toolbar settings -->
<script type="text/javascript" src="js/lang.js"></script>
<script type="text/javascript" src="js/set.js"></script>
<!-- markItUp! skin -->
<link rel="stylesheet" type="text/css" href="style.css" />
<!--  markItUp! toolbar skin -->
</head>
<body style="text-align: center">
<script type="text/javascript">
<!--
$(document).ready(function()	{
	// Add markItUp! to your textarea in one line
	// $('textarea').markItUp( { Settings }, { OptionalExtraSettings } );
	$('#txtMssg').markItUp(full);
	
	// You can add content from anywhere in your page
	// $.markItUp( { Settings } );	
	$('.add').click(function() {
 		$.markItUp( { 	openWith:'<opening tag>',
						closeWith:'<\/closing tag>',
						placeHolder:"New content"
					}
				);
 		return false;
	});
	
	// And you can add/remove markItUp! whenever you want
	// $(textarea).markItUpRemove();
	$('.toggle').click(function() {
		if ($("#txtMssg.markItUpEditor").length === 1) {
 			$("#txtMssg").markItUpRemove();
			$("span", this).text("get markItUp! back");
		} else {
			$('#txtMssg').markItUp(full);
			$("span", this).text("remove markItUp!");
		}
 		return false;
	});
});

-->
</script>
    <script language="javascript" type="text/javascript">
$(document).ready(function () {

    $("#txtMssg").focus(); // по поле ввода сообщения ставим фокус
	$('#txtMssg').keyup(function(e) { 

	if (e.keyCode == 13) { 

	var text = $(this).val();
	var length = text.length; 

	// отправляем
	if (length > 1) {
	Send(onCheckPost, onError)
	$(this).val("");
	} 
	}
	});      
    LoadUser(onCheckLoadUser, onErrorU);
    setInterval("Load(onCheckLoad, onErrorL);", 250); // создаём таймер который будет вызывать загрузку сообщений каждые 1 секунды (1000 миллисекунд)
    setInterval("LoadUser(onCheckLoadUser, onErrorU);", 5000); // создаём таймер который будет вызывать загрузку сообщений каждые 1 секунды (1000 миллисекунд)
});  
    </script>
<form id="pac_form" runat="server">
    <table align="center" width="650px" border="0" cellpadding="0" cellspacing="0">
    <tr>    <td colspan="10" style="text-align: center; height: 25px;">
        <strong><span style="font-size: 16pt; font-family: Courier New;">
    ASP.net Ajax Chat By KANekT
            </span></strong></td></tr>

    <tr style="font-family: Times New Roman">
        <td style="width: 550px; height: 450px;">
        <div id="onError" style="left: 0px; top: 0px"></div>
        <div id="divChat" class="chat" style="left: 0px; top: 0px"></div>
                        </td>
        <td valign="top" style="width: 100px; height: 450px;">
            <div id="divUsers" style="overflow: auto; height: 450px; border-top: solid 2px gray; border-bottom: solid 2px gray; border-left: solid 1px gray; border-right: solid 1px gray;">
</div>

                      </td>            
    </tr>
    <tr>
        <td style="height: 195px; text-align: center">
        
        <table border="0" cellpadding="0" cellspacing="5">
        <tr>
            <td align="right" valign="middle" style="width:50px">Ник: </td>
            <td align="left" style="width: 500px">
                <table border="0" cellpadding="0" cellspacing="2">
                <tr>
                    <td><input id="txtName" type="text" maxlength="17" readonly="readonly" style="border:1px solid #aaaaaa; width: 145px;" value="<%=ChatLogik.Chat.GetUserName()%>"  />
                    <input id="mycolor" name="mycolor" type="text" value="#fff" class="iColorPicker" />
                    </td>
                   </tr>
                </table>
            </td>  
        </tr>
        <tr><td colspan="5">
                                    <input id="btnClean" runat="server" type="button" onclick="Clean()" value="Очистить чат" style="cursor:pointer;border:1px solid gray; height: 42px; width: 100%; font-size: 7pt;"/>
        </td></tr>
        <tr>
            <td align="right" style="width: 50px;" valign="middle">Сообщение:&nbsp;<br />
            </td>
            <td align="left" style="height: 45px; width: 500px;" valign="top">
                <table border="0" cellpadding="0" cellspacing="2">
                <tr>
                    <td><textarea cols="100" rows="2" id="txtMssg" style="border:1px solid gray; width: 320px; height: 40px;"></textarea>
                        </td>
                    <td>
                                    <!-- Кнопка "Отправить" -->
                        <input id="btnSend" type="button" value="Отправить" onclick="Send(onCheckPost, onError)" style="cursor:pointer;border:1px solid gray; height: 42px;"/>
                       </td>
                </tr>
                </table>
             </td>  
        </tr>
        </table>
            <span style="font-size: 8pt; color: #ff3333; font-family: Georgia"></span>
        </td>
        <td valign="middle" style="width: 100px; height: 195px;">
                <input id="btnLogin" runat="server" type="button" onclick="location.replace('Default.aspx');" value="Вход в Чат" style="cursor:pointer;border:1px solid gray; height: 30px; width: 100%; font-size: 7pt;" size="30"/>&nbsp;<br />
            
            <asp:Button ID="btnLoginOff" runat="server" Text="Выход из Чата"  style="cursor:pointer;border:1px solid gray; height: 34px; width: 100%; font-size: 7pt;" size="30" OnClick="btnLoginOff_Click"/>
            <br /><input id="btnRefresh" runat="server" type="submit" onclick="document.forms['pac_form'].submit();" value="Рестарт Чата" style="cursor:pointer;border:1px solid gray; height: 34px; width: 100%; font-size: 7pt;" size="30"/>
            <br /><asp:Label ID="LabelT" runat="server" Font-Names="Georgia" Font-Size="Smaller"></asp:Label></td>
    </tr>
    </table>
    </form>
</body>
</html>

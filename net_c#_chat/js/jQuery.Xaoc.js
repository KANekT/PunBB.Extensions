var l_id = 0; // номер последнего сообщения, что получил пользователь

var ColorerObject = null;
var ColorerOldBackColor = '';
var ColorerOldFontColor = '';
function myColorer(x,type)
{
	if (type=='over')
	{
		if (x.style.backgroundColor != ColorerOldBackColor)
		{
			ColorerObject = x;
			ColorerOldBackColor = x.style.backgroundColor;
			ColorerOldFontColor = x.style.color;
			x.style.backgroundColor = '#E6C768';
			x.style.color = 'black';
		}
	}
	else
	{
		ColorerObject.style.backgroundColor = ColorerOldBackColor;
		ColorerObject.style.color = ColorerOldFontColor;
		ColorerOldBackColor = '';
		ColorerOldFontColor = '';
	}
}

function ExecuteService(params, url, callbackSuccess, callbackError)
{
    $.ajax({
      type: "POST",
      url: url,
      contentType: "application/json; charset=utf-8",
      dataType: "json",
      data: params,
      success: callbackSuccess,
      error: callbackError
    });                                        
}
function onCheckLoad(msg)
{
    if (msg.d)
    {

    $("#divChat").append(msg.d.LastMessage);
	if(msg.d.LastId != l_id || l_id == 0)
	{
	$("#divChat").scrollTop($("#divChat").get(0).scrollHeight); // прокручиваем сообщения вниз
	} 
	l_id = msg.d.LastId;
    }
    else 
    {
    $("#divChat").text("Еще никто не флудил.");
    }
} 

function onError(XMLHttpRequest, textStatus, errorThrown)
{
    $("#onError").text("Ошибка при выполнении AJAX-запроса Post. Попробуйте перезагрузить страницу.");
}  
function onErrorU(XMLHttpRequest, textStatus, errorThrown)
{
    $("#onError").text("Ошибка при выполнении AJAX-запроса User. Попробуйте перезагрузить страницу.");
}  
function onErrorL(XMLHttpRequest, textStatus, errorThrown)
{
    $("#onError").text("Ошибка при выполнении AJAX-запроса Load. Попробуйте перезагрузить страницу.");
} 
function Load(callbackResult, callbackError)
{ 
        var text = $("#txtName")[0].value;
        
        if (text.length > 0)
        {                        
            var params = "{login:'"+text+"', last_id:'"+l_id+"'}";
            ExecuteService(
                params, 
                "WebService.asmx/Load",
                callbackResult,
                callbackError
            );       
        }
}  
function LoadUser(callbackResult, callbackError)
{ 
            var params = "";
            ExecuteService(
                params, 
                "WebService.asmx/LoadUser",
                callbackResult,
                callbackError
            );       
} 
function Send(callbackResult, callbackError)
{       
    var message = $("#txtMssg")[0].value;
    var login = $("#txtName")[0].value;
    var color = $("#mycolor")[0].value;
    if (message.length > 0 && login.length > 0)
    {                        
        var params = "{text:'"+message+"', login:'"+login+"', color:'"+color+"'}";
        ExecuteService(
            params, 
            "WebService.asmx/Post",
            callbackResult,
            callbackError
        );       
    }
    
    $("#txtMssg").val(""); // очистим поле ввода сообщения
    $("#txtMssg").focus(); // и поставим на него фокус
}    
function onCheckPost(msg)
{
    if (msg.d)
    {
    }
    else 
    {
    $("#onError").text("Ошибка при добавлении сообщения.");
    }
}
function onCheckLoadUser(msg)
{
    if (msg.d)
    {
        $("#divUsers").html(msg.d);
    }
    else 
    {
    $("#divUsers").text("Ошибка при загрузке пользователей.");
    }
}
function Clean()
{
    $("#divChat").text("");
}
using System;
using System.Collections;
using System.Linq;
using System.Web;
using System.Web.Services;
using System.Web.Services.Protocols;
using System.Xml.Linq;
using ChatLogik;

/// <summary>
/// Summary description for WebService
/// </summary>
[WebService]
// To allow this Web Service to be called from script, using ASP.NET AJAX, uncomment the following line. 
[System.Web.Script.Services.ScriptService]
public class WebService : System.Web.Services.WebService {

    public WebService () {

        //Uncomment the following line if using designed components 
        //InitializeComponent(); 
    }

    [WebMethod]
    public ChatLogik.Last Load(string login, int last_id)
    {
        ChatLogik.Last result = ChatLogik.Chat.Load(login, last_id);
        return result;
    }
    [WebMethod]
    public string LoadUser()
    {
        string result = ChatLogik.Chat.GetUsersDiv();
        return result;
    }
    [WebMethod]
    public bool Post(string text, string login, string color)
    {
        bool result = true;

        ChatLogik.Chat.Post(text, login, color);

        return result;
    }
    
}


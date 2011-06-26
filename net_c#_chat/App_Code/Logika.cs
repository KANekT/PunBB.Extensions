using System;
using System.Data;
using System.Configuration;
using System.Web;
using System.Web.Security;
using System.Web.UI;
using System.Web.UI.WebControls;
using System.Web.UI.WebControls.WebParts;
using System.Web.UI.HtmlControls;
using System.Collections;
using System.Text.RegularExpressions;
using System.Threading;
using System.Globalization;

/// <summary>
/// Summary description for Logika
/// </summary>
namespace ChatLogik
{
    public class Last
    {
        #region Private Members

        private string m_last_message;             // сообщение
        private string m_last_id;                  // последнее сообщение

        #endregion


        public Last()
        {
        }


        #region Properties

        public string LastMessage
        {
            get { return m_last_message; }
            set { m_last_message = value; }
        }
        public string LastId
        {
            get { return m_last_id; }
            set { m_last_id = value; }
        }

        #endregion
    }

    public class Business
    {
        public Business()
        {
        }

        public static User CurrentUser
        {
            get
            {
                if (HttpContext.Current.Session["ChatCurrentUser"] == null)
                    HttpContext.Current.Session["ChatCurrentUser"] = new User();

                return (User)HttpContext.Current.Session["ChatCurrentUser"];
            }
            set
            {
                HttpContext.Current.Session["ChatCurrentUser"] = value;
            }
        }

        public static App CurrentApp
        {
            get
            {
                if (HttpContext.Current.Application["ChatCurrentApp"] == null)
                    HttpContext.Current.Application["ChatCurrentApp"] = new App();

                return (App)HttpContext.Current.Application["ChatCurrentApp"];
            }
            set
            {
                HttpContext.Current.Application["ChatCurrentApp"] = value;
            }
        }
        public static Last CurrentLast
        {
            get
            {
                if (HttpContext.Current.Application["ChatCurrentLast"] == null)
                    HttpContext.Current.Application["ChatCurrentLast"] = new Last();

                return (Last)HttpContext.Current.Application["ChatCurrentLast"];
            }
            set
            {
                HttpContext.Current.Application["ChatCurrentLast"] = value;
            }
        }
    }

    public class User
    {
        #region Private Members

        private bool m_ajax_chat_counted;       // Посчитал шо ли
        private string m_ajax_chat_user_name;   // Имя пользователя
        private string m_ajax_chat_user_ip;     // АйПи
        private bool m_ajax_chat_user_log;      // Хрень какая-то
        private int m_ajax_chat_last_read_id;   // Последний сообщение

        #endregion

        public User()
        {
            m_ajax_chat_counted = false;
            m_ajax_chat_user_log = false;
            m_ajax_chat_user_name = "";
            m_ajax_chat_user_ip = "";
            m_ajax_chat_last_read_id = -1;
        }

        #region Properties

        public bool AjaxChatCounted
        {
            get { return m_ajax_chat_counted; }
            set { m_ajax_chat_counted = value; }
        }

        public string AjaxChatUserName
        {
            get { return m_ajax_chat_user_name; }
            set { m_ajax_chat_user_name = value; }
        }
        public string AjaxChatUserIp
        {
            get { return m_ajax_chat_user_ip; }
            set { m_ajax_chat_user_ip = value; }
        }

        public int AjaxChatLastReadId
        {
            get { return m_ajax_chat_last_read_id; }
            set { m_ajax_chat_last_read_id = value; }
        }
        public bool AjaxChatUserLog
        {
            get { return m_ajax_chat_user_log; }
            set { m_ajax_chat_user_log = value; }
        }

        #endregion

    }

    public class App
    {
        #region Private Members

        private Hashtable m_ajax_chat_users_w;                  // пользотели
        private Hashtable m_ajax_chat_users_ip;                 // АйПи
        private Hashtable m_ajax_chat_users_w_last_activity;    // Активность
        private ArrayList m_ajax_chat;                          // Сообщения
        private int m_count;

        #endregion


        public App()
        {
            m_ajax_chat_users_w = new Hashtable();
            m_ajax_chat_users_w_last_activity = new Hashtable();
            m_ajax_chat_users_ip = new Hashtable();
            m_ajax_chat = new ArrayList();
            m_count = 0;
        }


        #region Properties

        public Hashtable AjaxChatUsersW
        {
            get { return m_ajax_chat_users_w; }
            set { m_ajax_chat_users_w = value; }
        }
        public Hashtable AjaxChatUsersIp
        {
            get { return m_ajax_chat_users_ip; }
            set { m_ajax_chat_users_ip = value; }
        }
        public Hashtable AjaxChatUsersWLastActivity
        {
            get { return m_ajax_chat_users_w_last_activity; }
            set { m_ajax_chat_users_w_last_activity = value; }
        }

        public ArrayList AjaxChat
        {
            get { return m_ajax_chat; }
            set { m_ajax_chat = value; }
        }
        public int AjaxCount
        {
            get { return m_count; }
            set { m_count = value; }
        }
        #endregion
    }
    public class Chat
    {
        public Chat() { }
        public static void UpdateUserLastActivityTime(string login)
        {
            if (login != null && login != "")
                UpdateUserLastActivityTime(login, Business.CurrentApp.AjaxChatUsersWLastActivity);
        }

        public static void UpdateUserLastActivityTime(string login, Hashtable ajax_chat_users_w_last_activity)
        {
            if (ajax_chat_users_w_last_activity[login] == null)
                ajax_chat_users_w_last_activity.Add(login, DateTime.Now);
            else
                ajax_chat_users_w_last_activity[login] = DateTime.Now;
        }
        public static void Post(string message, string login, string color)
        {

                if (message != "" || login != "Гость")
                {
                    DateTime now_time = DateTime.Now;
                    string rer = message.Substring(message.Length - 2, 2);
                    if (message.Substring(message.Length - 2, 2) == "\r\n")
                    {
                        message = message.Substring(0,message.Length - 2);
                    }
                    message = Regex.Replace(message, @"<", "&lt;");
                    message = Regex.Replace(message, @">", "&gt;");
                    message = message.Replace("\r\n", "<br>");
                    message = message.Replace("\n", "");

                    // Add time and nickname to identify message
                    string message_head = "[" + now_time.ToLongTimeString() + "] ";
                    if (color != "#fff" && color != "#000")
                    {
                        message = "<font style=\"color:" + color + "\">"+ message +"</font>";
                    }
                    message = message_head + "<b>" + login + "</b>: " + message;

                    Business.CurrentApp.AjaxChat.Insert(0, message);
                    Business.CurrentApp.AjaxCount = Business.CurrentApp.AjaxCount + 1;
                }
        }
        public static Last Load(string login, int last_id)
        {
            string sResponse = "";
            string History = "";
            int id = Business.CurrentApp.AjaxCount;
            //if (Business.CurrentUser.AjaxChatLastReadId > 0) i_count = Business.CurrentUser.AjaxChatLastReadId;

            if (id != last_id && id > last_id) id = id - last_id;
            else id = 0;

            if (last_id == 0)
            {
                id = Business.CurrentApp.AjaxChat.Count;
            }

            for (int i = 0; i < id; i++)
            //while (Business.CurrentApp.AjaxChat.Count >= i_count)
            {
                sResponse = Business.CurrentApp.AjaxChat[id - i - 1].ToString();
                string part = @"\[toPM=(.*?)\]";
                Match r = Regex.Match(sResponse, part);
                if (r.ToString() != "")
                {
                    string login_pm = Regex.Replace(r.ToString(), @"\[toPM=(.*?)\]", "$1");
                    string patt = @"<b>(.*?)</b>";
                    Match t = Regex.Match(sResponse, patt);
                    string login_fr = Regex.Replace(t.ToString(), @"<b>(.*?)</b>", "$1");

                    if (login_pm == login || login_fr == login)
                    {
                        string privat = "";
                        if (login_fr == login)
                        {
                            privat = "<i>Приватное для " + login_pm + "</i>:";
                        }
                        else
                        {
                            privat = "<i>Приватное:</i>:";
                        }
                        History += Regex.Replace(sResponse, @"\[toPM=(.*?)\](.*?)\[/pm\]", privat + " $2") + "<br>";
                    }
                    else
                    {
                        sResponse = "";
                    }
                }
                else
                {
                    History += BBcode.BBcod(BBcode.BBsmile(sResponse)) + "<br>";
                }
            }
            UpdateUserLastActivityTime(login);

            if (Business.CurrentApp.AjaxChat.Count > 100)
            {
                Business.CurrentApp.AjaxChat.RemoveAt(Business.CurrentApp.AjaxChat.Count-1);
            }

            Business.CurrentLast.LastMessage = History;
            Business.CurrentLast.LastId = Business.CurrentApp.AjaxCount.ToString();
            return (Business.CurrentLast);
        }
        public static string GetUserName()
        {
            if (Business.CurrentUser.AjaxChatUserName == "")
                return "Гость";
            /*Chat.GetNextUserName();*/
            return Business.CurrentUser.AjaxChatUserName;
        }
        public static string GetUsersDiv()
        {
            DateTime last_activity;
            DateTime now_time;
            now_time = DateTime.Now;
            string userDiv = "";
            if (Business.CurrentApp.AjaxChatUsersW.Keys.Count > 0)
            {
                userDiv = "<table>";
                // Add list of users to datasource
                foreach (object key in Business.CurrentApp.AjaxChatUsersW.Keys)
                {

                    last_activity = (DateTime)Business.CurrentApp.AjaxChatUsersWLastActivity[key];
                    long epoch = (DateTime.Now.ToUniversalTime().Ticks - 621355968000000000) / 10000000;
                    long epoch2 = (last_activity.ToUniversalTime().Ticks - 621355968000000000) / 10000000;
                    if (epoch < epoch2 + 15)
                    {
                        userDiv += "<tr><td><div style=\"height:20px; float: left;\" onclick=\"document.getElementById('txtMssg').value='[i]" + key.ToString() + "[/i], ';var r=txtMssg.createTextRange();r.collapse(false);r.select();\" onmouseover=\"myColorer(this,'over');this.style.cursor='hand';\" onmouseout=\"myColorer(this,'out');\"><nobr>" + key.ToString() + "</nobr></div></td><td><div style=\"height:20px; float: left;\" onclick=\"document.getElementById('txtMssg').value='[toPM=" + key.ToString() + "][/pm]';var r=txtMssg.createTextRange();r.collapse(false);r.select();\" onmouseover=\"myColorer(this,'over');this.style.cursor='hand';\" onmouseout=\"myColorer(this,'out');\"><nobr>PM</nobr></div></td></tr>";
                    }
                }
            userDiv += "</table>";
            }
            else
            {
                userDiv = "Нет никто";
            }

            return userDiv;
        }
    }
}

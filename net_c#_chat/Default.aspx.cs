using System;
using System.Data;
using System.Configuration;
using System.Web;
using System.Web.Security;
using System.Web.UI;
using System.Web.UI.WebControls;
using System.Web.UI.WebControls.WebParts;
using System.Web.UI.HtmlControls;
using ChatLogik;

public partial class _Default : System.Web.UI.Page
{
    protected void Page_Load(object sender, EventArgs e)
    {
    }
    protected void BtnLogin_Click(object sender, EventArgs e)
    {
        string newUser = TextBox.Text.ToString();
        DateTime last_activity;
        DateTime now_time;
        now_time = DateTime.Now;

        if (Business.CurrentApp.AjaxChatUsersW[newUser] != null)
        {
            if (Business.CurrentApp.AjaxChatUsersWLastActivity[newUser] != null)
            {

                last_activity = (DateTime)Business.CurrentApp.AjaxChatUsersWLastActivity[newUser];

                long epoch = (DateTime.Now.ToUniversalTime().Ticks - 621355968000000000) / 10000000;
                long epoch2 = (last_activity.ToUniversalTime().Ticks - 621355968000000000) / 10000000;
                if (epoch > epoch2 + 60)
                {
                    Business.CurrentApp.AjaxChatUsersW.Remove(newUser);
                    Business.CurrentApp.AjaxChatUsersWLastActivity.Remove(newUser);
                    Business.CurrentApp.AjaxChatUsersW.Add(newUser, 1);
                    Business.CurrentUser.AjaxChatUserName = newUser;
                    Business.CurrentApp.AjaxChatUsersWLastActivity[newUser] = DateTime.Now;
                    Business.CurrentApp.AjaxChatUsersIp[newUser] = Request.UserHostAddress.ToString();
                    Business.CurrentUser.AjaxChatUserLog = true;
                    Response.Redirect("Chat.aspx");
                }
                long sek = epoch2 + 60 - epoch;

                Label1.Text = "“акой ник уже используетс€ или заблокирован на " + sek + " секунд.";

            }
            else
            {
                Business.CurrentApp.AjaxChatUsersW.Remove(newUser);
                Business.CurrentApp.AjaxChatUsersW.Add(newUser, 1);
                Business.CurrentUser.AjaxChatUserName = newUser;
                Business.CurrentApp.AjaxChatUsersWLastActivity[newUser] = DateTime.Now;
                Business.CurrentApp.AjaxChatUsersIp[newUser] = Request.UserHostAddress.ToString();
                Business.CurrentUser.AjaxChatUserLog = true;
                Response.Redirect("Chat.aspx");
            }

            if (Label1.Text == "")
                Label1.Text = "“акой ник уже используетс€ или заблокирован на минуту.";


            // Add to writing users list
            /*Business.CurrentApp.AjaxChatUsersW.Add(TextBox.Text.ToString(), 1);*/

            //throw new Exception("“акой ник уже используетс€.");


        }
        else
        {
            Business.CurrentApp.AjaxChatUsersW.Add(newUser, 1);
            Business.CurrentUser.AjaxChatUserName = newUser;
            Business.CurrentApp.AjaxChatUsersWLastActivity[newUser] = DateTime.Now;
            Business.CurrentApp.AjaxChatUsersIp[newUser] = Request.UserHostAddress.ToString();
            Business.CurrentUser.AjaxChatUserLog = true;
            Response.Redirect("Chat.aspx");

        }

    }

}

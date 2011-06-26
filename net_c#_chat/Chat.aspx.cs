using System;
using System.Data;
using System.Configuration;
using System.Collections;
using System.Web;
using System.Web.Security;
using System.Web.UI;
using System.Web.UI.WebControls;
using System.Web.UI.WebControls.WebParts;
using System.Web.UI.HtmlControls;
using System.Threading;
using System.Globalization;
using ChatLogik;

public partial class Chat : System.Web.UI.Page
{
    protected void Page_Load(object sender, EventArgs e)
    {
        DateTime dt = new DateTime(2010, 02, 11);
        DateTime nt = new DateTime(DateTime.Now.Year, DateTime.Now.Month, DateTime.Now.Day);
        long s1 = (nt.Ticks - dt.Ticks) / 864000000000;

        this.LabelT.Text = "<nobr>Чат живет: " + s1.ToString() + " дней.</nobr>";
    }

    protected void btnLoginOff_Click(object sender, EventArgs e)
    {
        string newUser = ChatLogik.Chat.GetUserName();

        Business.CurrentApp.AjaxChatUsersW.Remove(newUser);
        Business.CurrentApp.AjaxChatUsersWLastActivity.Remove(newUser);
        Response.Redirect("Default.aspx");
    }
}

using System;
using System.Data;
using System.Configuration;
using System.Web;
using System.Web.Security;
using System.Web.UI;
using System.Web.UI.WebControls;
using System.Web.UI.WebControls.WebParts;
using System.Web.UI.HtmlControls;
using System.Text.RegularExpressions;

/// <summary>
/// Summary description for BBcode
/// </summary>
public class BBcode
{
    public static string BBcod(string message)
    {
        message = Regex.Replace(message, @"\[BR\]", "<br>");
        message = Regex.Replace(message, @"\[b\](.*?)\[/b\]", "<strong>$1</strong>");
        message = Regex.Replace(message, @"\[i\](.*?)\[/i\]", "<em>$1</em>");
        message = Regex.Replace(message, @"\[u\](.*?)\[/u\]", "<font class=\"bbu\">$1</font>");
        message = Regex.Replace(message, @"\[h\](.*?)\[/h\]", "</p><h5>$1</h5><p>");
        message = Regex.Replace(message, @"\[url\]([^\[]*?)\[/url\]", "<a href=$1>$1</a>");
        message = Regex.Replace(message, @"\[url=([^\[]+?)\](.*?)\[/url\]", "<a href=$1>$2</a>");
        message = Regex.Replace(message, @"\[email\]([^\[]*?)\[/email\]", "<a href=mailto:$1>$1</a>");
        message = Regex.Replace(message, @"\[email=([^\[]*?)\](.*?)\[/email\]", "<a href=mailto:$1>$2</a>");
        message = Regex.Replace(message, @"\[img\]((ht|f)tps?://)([^\s<']*?)\[/img\]", "<img src=$1$3>");
        /*message = Regex.Replace(message, @"\[colou?r=([a-zA-Z]{3,20}|\#[0-9a-fA-F]{6}|\#[0-9a-fA-F]{3})](.*?)\[/colou?r\]", "<font style=\"color: $1\">$2</font>");*/

        return message;
    }
    public static string BBsmile(string message)
    {
        message = Regex.Replace(message, @"\:\)", "[img]http://10.106.100.250/images/smilies/smile.png[/img]");
        message = Regex.Replace(message, @"\:\|", "[img]http://10.106.100.250/images/smilies/neutral.png[/img]");
        message = Regex.Replace(message, @"\:\(", "[img]http://10.106.100.250/images/smilies/sad.png[/img]");
        message = Regex.Replace(message, @"\:D", "[img]http://10.106.100.250/images/smilies/big_smile.png[/img]");
        message = Regex.Replace(message, @"\:o", "[img]http://10.106.100.250/images/smilies/yikes.png[/img]");
        message = Regex.Replace(message, @"\;\)", "[img]http://10.106.100.250/images/smilies/wink.png[/img]");
        message = Regex.Replace(message, @"\:h", "[img]http://10.106.100.250/images/smilies/hmm.png[/img]");
        message = Regex.Replace(message, @"\:P", "[img]http://10.106.100.250/images/smilies/tongue.png[/img]");
        message = Regex.Replace(message, @"\:lol\:", "[img]http://10.106.100.250/images/smilies/lol.png[/img]");
        message = Regex.Replace(message, @"\:mad\:", "[img]http://10.106.100.250/images/smilies/mad.png[/img]");
        message = Regex.Replace(message, @"\:rolleyes\:", "[img]http://10.106.100.250/images/smilies/roll.png[/img]");
        message = Regex.Replace(message, @"\:cool\:", "[img]http://10.106.100.250/images/smilies/cool.png[/img]");

        return message;
    }
}

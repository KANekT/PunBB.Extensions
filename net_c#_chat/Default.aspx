<%@ Page Language="C#" AutoEventWireup="true" CodeFile="Default.aspx.cs" Inherits="_Default" %>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head runat="server">
    <title>Untitled Page</title>
</head>
<body>
    <form id="form" runat="server">
			<asp:TextBox id="TextBox" style="Z-INDEX: 100; LEFT: 413px; POSITION: absolute; TOP: 213px"
				runat="server" tabIndex="2" maxlength="17"></asp:TextBox>
			<asp:Label id="Label2" style="Z-INDEX: 107; LEFT: 288px; POSITION: absolute; TOP: 213px" runat="server">Имя пользователя</asp:Label>
			<asp:Button id="BtnLogin" style="Z-INDEX: 102; LEFT: 289px; POSITION: absolute; TOP: 242px;cursor:pointer;border:1px solid gray;" runat="server"
				Text="Вход" tabIndex="3" Width="277px" OnClick="BtnLogin_Click" Height="42px"></asp:Button>
			<asp:RequiredFieldValidator id="RequiredFieldValidator2" style="Z-INDEX: 104; LEFT: 573px; POSITION: absolute; TOP: 212px"
				runat="server" ErrorMessage="RequiredFieldValidator" ControlToValidate="TextBox"></asp:RequiredFieldValidator>
			<asp:Label id="Label1" style="Z-INDEX: 107; LEFT: 573px; POSITION: absolute; TOP: 253px" runat="server"></asp:Label>
    </form>
</body>
</html>

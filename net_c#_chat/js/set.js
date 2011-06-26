/**
 * MarkItUp! extended settings for PunBB
 */
var full = {
	previewParserVar: 'text',
	previewPosition: 'before',
	previewAutoRefresh: false,
	onEnter: {keepDefault: false, replaceWith: '\n'},
	markupSet: [
		{name: L.bold, className:'mBold', key:'B', openWith:'[b]', closeWith:'[/b]'},
		{name: L.italic, className:'mItalic', key:'I', openWith:'[i]', closeWith:'[/i]'},
		{name: L.underline, className: 'mUnderline', key:'U', openWith:'[u]', closeWith:'[/u]'},
		{separator:'---------------' },
		{name: L.link, className: 'mLink', key:'L', openWith:'[url=[![URL:!:http://]!]]', closeWith:'[/url]', placeHolder: L.link_text},
		{name: L.email, className: 'mEmail', openWith:'[email=[![' + L.email_addr + ':!:john@doe.com]!]]', closeWith:'[/email]', placeHolder: L.email_text},
		{separator:'---------------' },
		{name: L.smilies, className: "mSmilies", dropMenu:  [
			{name: L.smilies,	openWith:':)',	className:"col1" },
			{name: L.smilies,	openWith:':|', className:"col2" },
			{name: L.smilies, 	openWith:':(', className:"col3" },
			{name: L.smilies, 	openWith:':D', className:"col4" },
			{name: L.smilies, 	openWith:':o', className:"col5" },
			{name: L.smilies,	openWith:';)', className:"col6" },

			{name: L.smilies, 	openWith:':h', className:"col7" },
			{name: L.smilies, 	openWith:':P',	className:"col8" },
			{name: L.smilies,	openWith:':lol:',	className:"col9" },

			{name: L.smilies, 	openWith:':mad:', className:"col10" },
			{name: L.smilies, 	openWith:':rolleyes:',	className:"col11" },
			{name: L.smilies,	openWith:':cool:',	className:"col12" }
		] },
		{separator:'---------------' },
		{name: L.clean, className:"mClean", replaceWith:function(markitup) { return markitup.selection.replace(/\[(.*?)\]/g, "") } }
	]
}
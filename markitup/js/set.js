/**
 * MarkItUp! extended settings for PunBB
 */

var mySettings = {
	previewParserVar: 'text',
	previewPosition: 'before',
	previewAutoRefresh: false,
	onEnter: {keepDefault: false, replaceWith: '\n'},
	markupSet: [
		{name: L.bold, className:'mBold', key:'B', openWith:'[b]', closeWith:'[/b]'},
		{name: L.italic, className:'mItalic', key:'I', openWith:'[i]', closeWith:'[/i]'},
		{name: L.underline, className: 'mUnderline', key:'U', openWith:'[u]', closeWith:'[/u]'},
		{name: L.strike, className: 'mStrike', key:'S', openWith:'[s]', closeWith: '[/s]'},
		{separator:'---------------' },
		{name: L.align, className: 'mAlign',
		dropMenu: [
			{name: L.center, className: 'mCenter', openWith: '[center]', closeWith: '[/center]'},
			{name: L.justify, className: 'mJustify', openWith: '[justify]', closeWith: '[/justify]'},
			{name: L.left, className: 'mLeft', openWith: '[left]', closeWith: '[/left]'},
			{name: L.right, className: 'mRight', openWith: '[right]', closeWith: '[/right]'}
		]},
		{name: L.font, className: 'mFonts', key:'F',
		dropMenu :[
			{name: L.size, className: 'mFonts', openWith:'[size=[![' + L.size_pt +']!]]', closeWith:'[/size]' },
			{name: L.h1, className: 'mH1', openWith:'[h]', closeWith:'[/h]' }
		]},
		{name: L.color, className:'palette', dropMenu: [
			{name: L.yellow,	openWith:'[color=#FCE94F]', closeWith: '[/color]',	className:"col1-1" },
			{name: L.yellow,	openWith:'[color=#EDD400]', closeWith: '[/color]', 	className:"col1-2" },
			{name: L.yellow, 	openWith:'[color=#C4A000]', closeWith: '[/color]', 	className:"col1-3" },
			{name: L.orange, 	openWith:'[color=#FCAF3E]', closeWith: '[/color]', 	className:"col2-1" },
			{name: L.orange, 	openWith:'[color=#F57900]', closeWith: '[/color]', 	className:"col2-2" },
			{name: L.orange,	openWith:'[color=#CE5C00]', closeWith: '[/color]', 	className:"col2-3" },

			{name: L.brown, 	openWith:'[color=#E9B96E]', closeWith: '[/color]', 	className:"col3-1" },
			{name: L.brown, 	openWith:'[color=#C17D11]', closeWith: '[/color]', 	className:"col3-2" },
			{name: L.brown,		openWith:'[color=#8F5902]',  closeWith: '[/color]',	className:"col3-3" },

			{name: L.green, 	openWith:'[color=#8AE234]', closeWith: '[/color]', 	className:"col4-1" },
			{name: L.green, 	openWith:'[color=#73D216]',  closeWith: '[/color]',	className:"col4-2" },
			{name: L.green,		openWith:'[color=#4E9A06]',  closeWith: '[/color]',	className:"col4-3" },

			{name: L.blue, 		openWith:'[color=#729FCF]',  closeWith: '[/color]',	className:"col5-1" },
			{name: L.blue, 		openWith:'[color=#3465A4]',  closeWith: '[/color]',	className:"col5-2" },
			{name: L.blue,		openWith:'[color=#204A87]',  closeWith: '[/color]',	className:"col5-3" },

			{name: L.purple, 	openWith:'[color=#AD7FA8]',  closeWith: '[/color]',	className:"col6-1" },
			{name: L.purple, 	openWith:'[color=#75507B]',  closeWith: '[/color]',	className:"col6-2" },
			{name: L.purple,	openWith:'[color=#5C3566]',  closeWith: '[/color]',	className:"col6-3" },

			{name: L.red, 		openWith:'[color=#EF2929]',  closeWith: '[/color]',	className:"col7-1" },
			{name: L.red, 		openWith:'[color=#CC0000]',  closeWith: '[/color]',	className:"col7-2" },
			{name: L.red,		openWith:'[color=#A40000]',  closeWith: '[/color]',	className:"col7-3" },

			{name: L.gray, 		openWith:'[color=#FFFFFF]',  closeWith: '[/color]',	className:"col8-1" },
			{name: L.gray, 		openWith:'[color=#D3D7CF]',  closeWith: '[/color]',	className:"col8-2" },
			{name: L.gray,		openWith:'[color=#BABDB6]',  closeWith: '[/color]',	className:"col8-3" },

			{name: L.gray, 		openWith:'[color=#888A85]',  closeWith: '[/color]',	className:"col9-1" },
			{name: L.gray, 		openWith:'[color=#555753]',  closeWith: '[/color]',	className:"col9-2" },
			{name: L.gray,		openWith:'[color=#000000]',  closeWith: '[/color]',	className:"col9-3" }
		]},
		{separator:'---------------' },
		{name: L.picture, className: 'mPicture', key:'P', replaceWith:'[img][![' + L.picture_url + ':!:http://]!][/img]'},
		{name: L.link, className: 'mLink', key:'L', openWith:'[url=[![URL:!:http://]!]]', closeWith:'[/url]', placeHolder: L.link_text},
		{name: L.email, className: 'mEmail', openWith:'[email=[![' + L.email_addr + ':!:john@doe.com]!]]', closeWith:'[/email]', placeHolder: L.email_text},
		{separator:'---------------' },
		{name: L.ul, className: 'mUl', openWith:'[list]\n', closeWith:'\n[/list]'},
		{name: L.li, className: 'mLi', openWith:'[*]', key: 'M', closeWith: '[/*]'},
		{separator:'---------------' },
		{name: L.quote, className:'mQuote', openWith:'[quote=]', closeWith:'[/quote]'},
		{name: L.code, className: 'mCode', openWith:'[code]', closeWith:'[/code]'},
		{name: L.hide, className: 'mHide', openWith:'[hide]', closeWith:'[/hide]'},
		{name: L.smilies, className: "mSmilies", dropMenu:  [
			{name: L.smilies,	openWith:':)',	className:"col1" },
			{name: L.smilies,	openWith:':|', className:"col2" },
			{name: L.smilies, 	openWith:':(', className:"col3" },
			{name: L.smilies, 	openWith:':D', className:"col4" },
			{name: L.smilies, 	openWith:':o', className:"col5" },
			{name: L.smilies,	openWith:';)', className:"col6" },

			{name: L.smilies, 	openWith:':/', className:"col7" },
			{name: L.smilies, 	openWith:':P',	className:"col8" },
			{name: L.smilies,	openWith:':lol:',	className:"col9" },

			{name: L.smilies, 	openWith:':mad:', className:"col10" },
			{name: L.smilies, 	openWith:':rolleyes:',	className:"col11" },
			{name: L.smilies,	openWith:':cool:',	className:"col12" },
		] },
		{separator:'---------------' },
		{name: L.clean, className:"mClean", replaceWith:function(markitup) { return markitup.selection.replace(/\[(.*?)\]/g, "") } },
	]
}
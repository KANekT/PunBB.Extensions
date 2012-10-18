var myOpt = {
	buttons: "bold,italic,underline,strike,bullist2,numlist,quote,code,spoiler,hide,search,link,video,fontcolor,fontsize,justifyleft,justifycenter,justifyright",
	allButtons: {
		quote: {
			transform: {
				'<div class="quotebox"><blockquote><p>{SELTEXT}</p></blockquote></div>':'[quote]{SELTEXT}[/quote]',
				'<div class="quotebox"><cite>{AUTHOR} написал:</cite><blockquote><p>{SELTEXT}</p></blockquote></div>':'[quote={AUTHOR}]{SELTEXT}[/quote]'
			}
		},
		code: {
			buttonHTML: '<span class="ve-tlb-code"></span>',
			transform: {
				'<div class="codebox"><pre><code>{SELTEXT}</code></pre></div>':'[code]{SELTEXT}[/code]'
			}
		},
		spoiler: {
			title: 'Сворачиваемый текст',
			buttonHTML: '<span class="ve-tlb-spoiler"></span>',
			buttonText: 'Спойлер',
			transform: {
				'<div class="fancy_spoiler_switcher"><div class="fancy_spoiler_switcher_header">Спойлер (+/-)</div><div class="fancy_spoiler_">{SELTEXT}</div></div>':'[spoiler]{SELTEXT}[/spoiler]'
			}
		},
		hide: {
			title: 'Скрываемый текст',
			buttonHTML: '<span style="background: url(/extensions/nya_editor/css/Oxygen/img/hide.png) no-repeat scroll 2px 2px transparent; width: 20px;"></span>',
			buttonText: 'Hide',
			transform: {
				'<div class="hidebox"><blockquote><p>{SELTEXT}</p></blockquote></div>':'[hide]{SELTEXT}[/hide]',
				'<div class="hidebox"><cite>Hidden text [{CNT}]:</cite><blockquote><p>{SELTEXT}</p></blockquote></div>':'[hide={CNT}]{SELTEXT}[/hide]'
			}
		},
		search: {
			title: 'Поиск',
			buttonText: 'Поиск',
			transform: {
				'<div class="search"><cite><p>Поиск: {SELTEXT}</p></cite></div>':'[search={SELTEXT}]Поиск[/search]'
			}
		},
		//select options
		fs_verysmall: {
			title: CURLANG.fs_verysmall,
			buttonText: "fs1",
			excmd: 'fontSize',
			exvalue: "1",
			transform: {
				'<font size="1">{SELTEXT}</font>':'[size=1]{SELTEXT}[/size]'
			}
		},
		fs_small: {
			title: CURLANG.fs_small,
			buttonText: "fs2",
			excmd: 'fontSize',
			exvalue: "2",
			transform: {
				'<font size="2">{SELTEXT}</font>':'[size=2]{SELTEXT}[/size]'
			}
		},
		fs_normal: {
			title: CURLANG.fs_normal,
			buttonText: "fs3",
			excmd: 'fontSize',
			exvalue: "3",
			transform: {
				'<font size="3">{SELTEXT}</font>':'[size=3]{SELTEXT}[/size]'
			}
		},
		fs_big: {
			title: CURLANG.fs_big,
			buttonText: "fs4",
			excmd: 'fontSize',
			exvalue: "4",
			transform: {
				'<font size="4">{SELTEXT}</font>':'[size=4]{SELTEXT}[/size]'
			}
		},
		fs_verybig: {
			title: CURLANG.fs_verybig,
			buttonText: "fs5",
			excmd: 'fontSize',
			exvalue: "6",
			transform: {
				'<font size="6">{SELTEXT}</font>':'[size=6]{SELTEXT}[/size]'
			}
		},
		video: {
			title: 'Вставка video',
			buttonHTML: '<span class="ve-tlb-video"></span>',
			buttonText: 'video',
			modal: { //Описание модального окна
				title: "Добавление видеоролика",
				width: "600px",
				tabs: [
					{
						input: [ //Список полей формы
							{param: "SRC",title:"Введите адрес видео:",validation: '^http(s)?://.*?\.*'},
						]
					}
				],
			},
			transform: {
				'<div class="video2">Видео: {SRC}</div>':'[video]{SRC}[/video]'
			}
		}
	},
	bodyClass: "entry-content",
	smileList: []
}
$(document).ready(function()	{
	if ($('textarea').attr('name') == 'req_message'){
		$('textarea[name=req_message]').wysibb(myOpt);
	}

	$('textarea').prop('required', false);
	$('iframe.wysibb-text-iframe').load(function(){
		$(this).contents().keypress(function(e){
			if (e.ctrlKey && e.keyCode == 13) {
				if ($(this).contents().find('.wysibb-body').text().length > 0) {
					$('input[name=submit_button]').click();
				}
			}
		});
		$(this).contents().find('.wysibb-body').css({'background' : 'none'});
	});
});
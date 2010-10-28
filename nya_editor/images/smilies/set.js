/* 
Smiley set for editor, in natural order. 
Please try to use similar syntax in your sets, because the parser is quite sensitive.

name - smiley name (for internal use)
code - representation in text
file - representation as image
prio - priority for parsing. 1 is highest, 50 is normal. Usually smilies which may
intersect with other smilies have higher priorities over them.
lang - internationalized tip

Copyright © 2004-2009 Kolobok Smiles 
*/
var smileSet = [
	{
		name: "angel",
		code: "O:-)",
		file: "angel.gif",
		prio: 60,
		lang: smileL.angel
	},
	{
		name: "smile",
		code: ":-)",
		file: "smile.gif",
		prio: 10,
		lang: smileL.smile
	},
	{
		name: "sad",
		code: ":-(",
		file: "sad.gif",
		prio: 10,
		lang: smileL.sad
	},
	{
		name: "wink",
		code: ";-)",
		file: "wink.gif",
		prio: 20,
		lang: smileL.wink
	},
	{
		name: "tongue",
		code: ":-P",
		file: "tongue.gif",
		prio: 50,
		lang: smileL.tongue
	},
	{
		name: "dirol",
		code: "8-)",
		file: "dirol.gif",
		prio: 50,
		lang: smileL.dirol
	},
	{
		name: "biggrin",
		code: ":-D",
		file: "biggrin.gif",
		prio: 40,
		lang: smileL.biggrin
	},
	{
		name: "blush",
		code: ":-[",
		file: "blush.gif",
		prio: 50,
		lang: smileL.blush
	},
	{
		name: "shok",
		code: "=-O",
		file: "shok.gif",
		prio: 50,
		lang: smileL.shok
	},
	{
		name: "kiss2",
		code: ":-*",
		file: "kiss2.gif",
		prio: 30,
		lang: smileL.kiss2
	},
	{
		name: "cray",
		code: ":|(",
		file: "cray.gif",
		prio: 40,
		lang: smileL.cray
	},
	{
		name: "secret",
		code: ":-X",
		file: "secret.gif",
		prio: 40,
		lang: smileL.secret
	},
	{
		name: "aggressive",
		code: "]-o",
		file: "aggressive.gif",
		prio: 50,
		lang: smileL.aggressive
	},
	{
		name: "fool",
		code: ":-|",
		file: "fool.gif",
		prio: 50,
		lang: smileL.fool
	},
	{
		name: "beee",
		code: ":-/",
		file: "beee.gif",
		prio: 30,
		lang: smileL.beee
	},
	{
		name: "mosking",
		code: "*JOKINGLY*",
		file: "mosking.gif",
		prio: 60,
		lang: smileL.mosking
	},
	{
		name: "diablo",
		code: "]:-]",
		file: "diablo.gif",
		prio: 40,
		lang: smileL.diablo
	},	
	{
		name: "music2",
		code: "[:-}",
		file: "music2.gif",
		prio: 40,
		lang: smileL.music2
	},	
	{
		name: "air_kiss",
		code: "*KISSED*",
		file: "air_kiss.gif",
		prio: 60,
		lang: smileL.air_kiss
	},	
	{
		name: "bad",
		code: ":-!",
		file: "bad.gif",
		prio: 50,
		lang: smileL.bad
	},	
	{
		name: "boredom",
		code: "*TIRED*",
		file: "boredom.gif",
		prio: 40,
		lang: smileL.boredom
	},	
	{
		name: "stop",
		code: "*STOP*",
		file: "stop.gif",
		prio: 60,
		lang: smileL.stop
	},	
	{
		name: "kiss3",
		code: "*KISSING*",
		file: "kiss3.gif",
		prio: 60,
		lang: smileL.kiss3
	},	
	{
		name: "give_rose",
		code: "@}-]--",
		file: "give_rose.gif",
		prio: 60,
		lang: smileL.give_rose
	},	
	{
		name: "good",
		code: "*THUMBS UP*",
		file: "good.gif",
		prio: 40,
		lang: smileL.good
	},	
	{
		name: "drinks",
		code: "*DRINK*",
		file: "drinks.gif",
		prio: 30,
		lang: smileL.drinks
	},	
	{
		name: "heart",
		code: "*IN LOVE*",
		file: "heart.gif",
		prio: 20,
		lang: smileL.heart
	},	
	{
		name: "bomb",
		code: "@=",
		file: "bomb.gif",
		prio: 20,
		lang: smileL.bomb
	},	
	{
		name: "help",
		code: "*HELP*",
		file: "help.gif",
		prio: 60,
		lang: smileL.help
	},	
	{
		name: "new_russian",
		code: "]m[",
		file: "new_russian.gif",
		prio: 40,
		lang: smileL.new_russian
	},	
	{
		name: "wacko2",
		code: "%)",
		file: "wacko2.gif",
		prio: 20,
		lang: smileL.wacko2
	},	
	{
		name: "ok",
		code: "*OK*",
		file: "ok.gif",
		prio: 30,
		lang: smileL.ok
	},	
	{
		name: "mamba",
		code: "*WASSUP*",
		file: "mamba.gif",
		prio: 60,
		lang: smileL.mamba
	},	
	{
		name: "sorry",
		code: "*SORRY*",
		file: "sorry.gif",
		prio: 30,
		lang: smileL.sorry
	},	
	{
		name: "clapping",
		code: "*BRAVO*",
		file: "clapping.gif",
		prio: 30,
		lang: smileL.clapping
	},		
	{
		name: "rofl",
		code: "*ROFL*",
		file: "rofl.gif",
		prio: 10,
		lang: smileL.rofl
	},		
	{
		name: "pardon",
		code: "*PARDON*",
		file: "pardon.gif",
		prio: 30,
		lang: smileL.pardon
	},		
	{
		name: "nea",
		code: "*NO*",
		file: "nea.gif",
		prio: 30,
		lang: smileL.nea
	},		
	{
		name: "crazy",
		code: "*CRAZY*",
		file: "crazy.gif",
		prio: 40,
		lang: smileL.crazy
	},		
	{
		name: "unknw",
		code: "*DONT_KNOW*",
		file: "unknw.gif",
		prio: 40,
		lang: smileL.unknw
	},		
	{
		name: "dance4",
		code: "*DANCE*",
		file: "dance4.gif",
		prio: 60,
		lang: smileL.dance4
	},		
	{
		name: "yahoo",
		code: "*YAHOO*",
		file: "yahoo.gif",
		prio: 40,
		lang: smileL.yahoo
	},		
	{
		name: "preved",
		code: "*HI*",
		file: "preved.gif",
		prio: 40,
		lang: smileL.preved
	},			
	{
		name: "bye",
		code: "*BYE*",
		file: "bye.gif",
		prio: 60,
		lang: smileL.bye
	},			
	{
		name: "yes3",
		code: "*YES*",
		file: "yes3.gif",
		prio: 30,
		lang: smileL.yes3
	},			
	{
		name: "acute",
		code: ";D",
		file: "acute.gif",
		prio: 40,
		lang: smileL.acute
	},			
	{
		name: "dash1",
		code: "*WALL*",
		file: "dash1.gif",
		prio: 40,
		lang: smileL.dash1
	},			
	{
		name: "mail1",
		code: "*WRITE*",
		file: "mail1.gif",
		prio: 40,
		lang: smileL.mail1
	},			
	{
		name: "scratch_head",
		code: "*SCRATCH*",
		file: "scratch_head.gif",
		prio: 60,
		lang: smileL.scratch_head
	}		
];

// Editor dialog display properties
var smileBox = {
	perRow: 7 // Smilies per row
};
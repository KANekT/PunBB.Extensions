$(function() {
	function split( val ) {
		return val.split( /,\s*/ );
	}
	function extractLast( term ) {
		return split( term ).pop();
	}
	$("input[name='pun_tags']").autocomplete({
		source: function( request, response ) {
			$.getJSON( PUNBB.env.base_url + "extensions/k_tags/functions.php", {
				term: extractLast( request.term )
			}, response );
		},
		focus: function() {
			return false;
		},
		select: function( event, ui ) {
				var terms = split( this.value );
				terms.pop();
				terms.push( ui.item.value );
				terms.push( "" );
				this.value = terms.join( ", " );
				return false;
			}
	});
	$('.k_tags').click(function(){
		var txt = $(this).html();
		var val = $("input[name='pun_tags']");
		var terms = split( val.val() );
			terms.pop();
			terms.push( txt );
			terms.push( "" );
			val.val(terms.join( ", " ));		
	});
});
$(document).ready(function() {
		var nya = $("input[name='pun_tags']");
		var vtx = nya.val();
		if (vtx.length > 0) vtx += ", ";
		nya.val(vtx);
});

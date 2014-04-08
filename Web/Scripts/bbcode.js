$(document).ready(function() {
	var balises={	'gras':['[b]', '[\\b]'] ,
					'italic':['[i]', '[\\i]'],
					'souligne':['[s]', '[\\s]'],
					'lien':['[url]', '[\\url]']
				};
				
				
    $('input[class="bbcode-button"]').click(function(){
		var textarea = $('textarea');
		var range=$(textarea).getSelection(  );
		var baliseName = $(this).attr('name');

        if( jQuery.inArray(baliseName, balises) ){
			var currentBalises=balises[baliseName];
			
			$(textarea).replaceSelection( currentBalises[0]+''+range.text+''+currentBalises[1]);	
		}
    });
 });

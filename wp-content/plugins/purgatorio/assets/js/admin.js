/* global purgatorioData */

(function($) {
	var thankyouText = $('#footer-thankyou').html();
	if(typeof thankyouText !== 'undefined'){
		thankyouText = thankyouText.slice(0, -1);
		$("#footer-thankyou").html(thankyouText+' | ');
		$('<a/>', {
		    href: purgatorioData.wp_docs_url,
		    target: '_blank',
		    text: purgatorioData.wp_docs_text
		}).appendTo('#footer-thankyou');
	}

})(jQuery);

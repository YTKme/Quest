/**
 * 
 */

$(function () {
	
	// Initialize
	init();
	
	/**
	 * Main
	 */
	function init () {
		// Set snippet
		setSnippet();
		
		// Set navigation click
		setNavigationClick();
	}
	
	/**
	 * Set snippet
	 */
	function setSnippet () {
		$('.snippet').snippet(
			'javascript',
			{
				style: 'emacs'
			}
		);
	}
	
	/**
	 * Set navigation click
	 */
	function setNavigationClick () {
		// Section scroll
		$('a[id^="lstNavigation"]').click(function () {
			var section = $(this).html();
			var target = $('h3[name="hd' + section.replace(/\s/g, '') + '"]');
			var scrollToPosition = $(target).offset().top;
			var navigationHeight = $('nav[role="navigation"]').outerHeight() + 15;
			
			// Scroll to section
			$("html, body").animate({
				scrollTop: scrollToPosition - navigationHeight
			}, 1000);
		});
		
		// Top scroll
		$('.btnTop').click(function () {
			// Scroll to top
			$("html, body").animate({
				scrollTop: 0
			}, 1000);
		});
	}
	
});
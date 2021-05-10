/**
 * @author Anis Sheikh
 */
jQuery(function($) {
	// preload page
    var $body = $('body')
    $body.addClass('vougueapp-body-loading');

    setTimeout(function() {
        $body.removeClass('vougueapp-body-loading');
        $('.vougueapp-page-loading').fadeOut(250);
    }, 1500);
});
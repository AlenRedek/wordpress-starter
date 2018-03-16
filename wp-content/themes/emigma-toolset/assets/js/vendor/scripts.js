// Theme scripts inside js/src directory
jQuery.noConflict();

(function($) {

    var pg = $.cantica.purgatorio();
    
    pg.btnBodyClass('.navbar-toggle', 'nav-open');
    pg.btnClosestClass('.open-close-button', '.enable-title-box');
    
    $(window).on('resize', function () {
        // Useful if you constantly need to call a function
        pg.setHighest('.same-height');
    }).resize();

})( jQuery );

"use strict";

jQuery(document).ready(function($) {
    var screenMd    = 1025,
        screenSm    = 768,
        viewportW   = viewport().width,
        windowW     = $(window).width();

    /*
    ******************************************************************************************************
    	Calculates the correct window height and width (including scrollbar)
    ******************************************************************************************************
    */
    function viewport() {
        var e = window, a = 'inner';
        if (!('innerWidth' in window )) {
            a = 'client';
            e = document.documentElement || document.body;
        }
        return { width : e[ a+'Width' ] , height : e[ a+'Height' ] };
    }

    /*
    ******************************************************************************************************
    	Navbar & mobile drobdown toggle
    ******************************************************************************************************
    */
    $('.navbar-toggle').click(function(){
		$(this).toggleClass('open');
		$('body').toggleClass('nav-open');
	});
	
	/*
    ******************************************************************************************************
        Desktop navbar dropdown smooth effect
    ******************************************************************************************************
    */
    if ( viewportW >= screenMd ) {
        // Add slideup & fadein animation to dropdown
        $('#main-menu').find('.dropdown').on('show.bs.dropdown', function(e){
            var $dropdown = $(this).find('.dropdown-menu');
            var orig_margin_top = parseInt($dropdown.css('margin-top'));
            $dropdown
                .css({'margin-top': (orig_margin_top + 10) + 'px', opacity: 0})
                .animate({'margin-top': orig_margin_top + 'px', opacity: 1},
                250, function(){
                    $(this).css({'margin-top':''});
                });
        });

        // Add slidedown & fadeout animation to dropdown
        $('#main-menu').find('.dropdown').on('hide.bs.dropdown', function(e){
            var $dropdown = $(this).find('.dropdown-menu');
            var orig_margin_top = parseInt($dropdown.css('margin-top'));
            $dropdown
                .css({'margin-top': orig_margin_top + 'px', opacity: 1, display: 'block'})
                .animate({'margin-top': (orig_margin_top + 10) + 'px', opacity: 0},
                250, function(){
                    $(this).css({'margin-top':'', display:''});
                });
        });
    }

	/*
    ******************************************************************************************************
    	Ekko lightbox
    ******************************************************************************************************
    */
	$(document).delegate('*[data-toggle="lightbox"]', 'click', function(event) {
        event.preventDefault();
        $(this).ekkoLightbox({
            left_arrow_class: '.slick-prev .slick-arrow',
            right_arrow_class: '.slick-next .slick-arrow',
            scale_height: true
        });
    });

	/*
    ******************************************************************************************************
    	Tables
    ******************************************************************************************************
    */
    $('.entry-content table').each(function(){
        $(this).addClass('table');
    });
	if(viewportW < screenSm ){
		if($('.table').length){
			var $table  = $('.table');
			$table.each(function(i, table) {
				if($(table).find('thead').length){
					$(table).wrap('<div class="no-more-tables" />');
					var head = [],
					    tableHeadCell = 'th';
					if( !$(table).find('thead').children('tr').children(tableHeadCell).length ){
					    tableHeadCell = 'td';
					}
					$(table).find('thead').children('tr').children(tableHeadCell).each(function(j, td) {
						head[j] = $(td).text();
					});
					if(head){
						$(table).find('tbody td').each(function(k, td) {
							$(td).attr('data-title',(head[jQuery(td).index()])).removeAttr('height width');
						});
					}
				} else {
					$(table).wrap('<div class="table-responsive" />');
				}
			});
		}
	}

	/*
    ******************************************************************************************************
    	Panel toggle
    ******************************************************************************************************
    */
    /*$('.panel-toggle').click(function(){
        var $wrapper = $(this).parent('.panel-body');
		$wrapper.toggleClass('collapsed');
		if($wrapper.hasClass('collapsed')){
		    $wrapper.find('.panel-collapse').slideUp();
		}else{
            $wrapper.find('.panel-collapse').slideDown();
		}
	});*/

    /*
    ******************************************************************************************************
    	Contact Form 7 CSS class fix
    ******************************************************************************************************
    */
    $('input[type=radio]:checked').parent('label').addClass('checked');
    $('input[type=radio]').change(function() {
        $(this).parent('label').parent('.radio').siblings('.radio').find('label').removeClass('checked');
        $(this).parent('label').addClass('checked');
    });

    $('input[type=checkbox]:checked').parent('label').addClass('checked');
    $('input[type=checkbox]').change(function() {
        $(this).parent('label').toggleClass('checked');
    });
    $('input[type="submit"]').removeClass('btn-default');
    $('input[type="submit"]').addClass('btn');
    $('input[type="submit"]').addClass('btn-primary');

    /*
    ******************************************************************************************************
    	WP Login form function CSS class fix
    ******************************************************************************************************
    */
    // $('.footer-content #loginform > p').addClass('form-group');
    // $('.footer-content #loginform > p > .input').addClass('form-control');
    // $('.footer-content #loginform > p > .button-primary').addClass('btn btn-primary');

    /*
    ******************************************************************************************************
    	Clear an input field with an 'X'
    ******************************************************************************************************
    */
    $(`.clear-input-group`).each(function(i, e){

        var $inputField     = $('input', this),
            $formGroup      = $('.form-group', this),
            inputID         = `clear-input-${i}`;

        // Add class to input field
        $inputField.addClass(`${inputID} clearable-input`);
        $inputField.data('clear-input', inputID);

        var $clearWrapper = $('<div/>', {
                'class': 'clear-input-wrapper',
            }).appendTo($formGroup);

        $($inputField).prependTo($clearWrapper);

        // Create clear element
        if($(`#${inputID}`).length === 0){
            $('<span/>', {
                'id': inputID,
                'class': 'clear-input-value fa',
                'click': function() {
                    // Clear input value
                    $(`input.${inputID}`).val('').focus();
                    $(this).removeClass('active');
                }
            }).appendTo($clearWrapper);
        }
    });

    $('input.clearable-input').on('input',function(e){
        var clearFieldID = $(this).data('clear-input'),
            $clearField = $(`#${clearFieldID}`);
        if($(this).val().length > 0){
            $clearField.addClass('active');
        }else{
            $clearField.removeClass('active');
        }
    });


    /*
    ******************************************************************************************************
    	Back to top
    ******************************************************************************************************
    */
    $(window).scroll(function(){
		if ($(this).scrollTop() > 150) {
			$('#scroll-top').fadeIn(500);
			setTimeout(function () {
                 $('#scroll-top').addClass('transition');
             }, 600);
		} else {
		    $('#scroll-top').removeClass('transition');
			$('#scroll-top').fadeOut(500);
		}
	});

	/*
    ******************************************************************************************************
    	Smooth scrolling
    ******************************************************************************************************
    */
    $('.btn-scroll').bind('click.smoothscroll', function(e) {
        e.preventDefault();
        var target = this.hash,
            $target = $(target),
            position = 0;
        if(target.length > 0){
            position = $target.offset().top
        }
        $('html, body').stop().animate( {
            'scrollTop': position
        }, 2000, 'easeOutExpo', function () {
            window.location.hash = target;
        } );
    });

    /*
    ******************************************************************************************************
        Height controller
    ******************************************************************************************************
    */
    function findHighest(element){
        var maxHeight = 0;
        $(element).css('height', 'initial');
        if(window.themeVars.viewportW >= window.themeVars.screenSm){
        	$(element).each(function(i) {
        		var elHeight = $(this).height();
        		if(elHeight > maxHeight){
        			maxHeight = elHeight;
        		}
        	});
        	$(element).css('height', `${maxHeight}px`);
        }
    }
    $(window).on('resize', function () {
        
    }).resize();

	/*
    ******************************************************************************************************
        Close other accordions once one has been opened
    ******************************************************************************************************
    */
    var $accordion = $('#accordion');
    $accordion.on('show.bs.collapse','.collapse', function() {
        $accordion.find('.collapse.in').collapse('hide');
    });

    /*
    ******************************************************************************************************
    	Select with links
    ******************************************************************************************************
    */
    $('.select-links').on('change', function() {
        var link = $(this).find(':selected').data('href');
        if(link !== undefined){
            window.location.href = link;
        }
    });

    /*
    ******************************************************************************************************
        Resize iFrames to 16:9 ratio
    ******************************************************************************************************
    */
    $( window ).resize(function() {
        resizeElement169('.entry-content iframe');
    });
    resizeElement169('.entry-content iframe');

});

/*
******************************************************************************************************
	Resize element to 16:9 ration
******************************************************************************************************
*/
var resizeElement169 = function(selector){
    jQuery(selector).each(function(){
        var $element = jQuery(this);
        var elementWidth    = $element.outerWidth(),
            elementHeight   = Math.floor((elementWidth * 9) / 16);
        $element.css('height', elementHeight+'px');
    });
}
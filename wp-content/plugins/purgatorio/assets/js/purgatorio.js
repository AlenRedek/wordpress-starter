"use strict";
jQuery.noConflict();

(function( $ ){
    
    $.widget( 'cantica.purgatorio', {

        // Default options.
        options: {
            screenLg:   1200,
            screenMd: 	992,
            screenSm: 	768,
            viewportW: 	0,
            windowW: 	0
        },

        _create: function() {
            this.options.viewportW  = this._viewport().width;
            this.options.windowW    = $(window).width();
        },

        _setOption: function(key, value) {
            this._super( key, value );
        },

        _setOptions: function(options) {
            this._super( options );
        },

        /**
		 * Calculates the correct window height and width (including scrollbar)
		 *
		 * @access private
		 * @param
		 * @return
		 *
		**/
        _viewport: function() {
            var e = window, a = 'inner';
            if (!('innerWidth' in window )) {
                a = 'client';
                e = document.documentElement || document.body;
            }
            return { width : e[ a+'Width' ] , height : e[ a+'Height' ] };
        },
        
        /**
		 * Toggle body class, when specific button is pressed
		 *
		 * @access public
		 * @param string, string
		 * @return
		 *
		**/
        btnBodyClass: function(buttonSelector, bodyClass){
            $(buttonSelector).click(function(){
				$(this).toggleClass('open');
				$('body').toggleClass(bodyClass);
			});
        },
        
        /**
		 * Toggle closest element class, when specific button is pressed
		 *
		 * @access public
		 * @param string, string
		 * @return
		 *
		**/
        btnClosestClass: function(buttonSelector, closestClass){
            $(buttonSelector).click(function(){
				$(this).toggleClass('open');
				$(this).closest(closestClass).toggleClass('open');
			});
        },

        /**
		 * Redirect to URL on dropdown select
		 *
		 * @access public
		 * @param string
		 * @return
		 *
		**/
        redirectOnSelect: function(selectSelector){
            $(selectSelector).on('change', function () {
                var url = $(this).val();
                if (url) {
                    window.location = url;
                }
                return false;
            });
        },

        /**
		 * Bootstrap table styling
		 *
		 * @access public
		 * @param string
		 * @return
		 *
		**/
        bootstrapTables: function(tableSelector) {
            $(tableSelector).each(function(i, table){
                var $table = $(table);
                if($table.length){
                    $table.addClass('table table-striped table-hover').wrap('<div class="table-responsive" />');
                }
            });
        },
        
        /**
		 * Close other accordions once one has been opened
		 *
		 * @access public
		 * @param string
		 * @return
		 *
		**/
        closeAccordions: function(accordionSelector){
		    var $accordion = $(accordionSelector);
		    $accordion.on('show.bs.collapse','.collapse', function(e) {
		        $accordion.find('.collapse.in').collapse('hide').siblings('.card-header').removeClass('active');
		        $(e.target).siblings('.card-header').addClass('active');
		    });
		    $accordion.on('hide.bs.collapse','.collapse', function(e) {
		        $(e.target).siblings('.card-header').removeClass('active');
		    });
        },
        
        /**
		 * Initialize Ekko lightbox
		 *
		 * @access public
		 * @param
		 * @return
		 *
		**/
        ekkoLightbox: function(){
            $(document).delegate('*[data-toggle="lightbox"]', 'click', function(event) {
		        event.preventDefault();
		        $(this).ekkoLightbox({
		            scale_height: true
		        });
		    });
        },
        
        /**
		 * Smooth scroll to anchor target
		 *
		 * @access public
		 * @param string
		 * @return
		 *
		**/
        smoothScroll: function(clickedBtnSelector, offset){
            offset = typeof offset !== 'undefined' ? offset : 0;
            $(clickedBtnSelector).bind('click.smoothscroll', function(e) {
                e.preventDefault();
                var anchorLink      = this.hash,
                    $target         = $(anchorLink),
                    position        = 0 + offset;
                if($target.length > 0){
                  position += $target.offset().top;
                }
                $('html, body').stop().animate( {
                    'scrollTop': position
                }, 2000, 'easeOutExpo', function () {
                    window.location.hash = anchorLink;
                });
            });
        },
        
        /**
		 * Scroll back to top when button is clicked
		 *
		 * @access public
		 * @param string
		 * @return
		 *
		**/
        backToTop: function(buttonSelector){
            var $toTopButton = $(buttonSelector);

            $(window).scroll(function(){
                if ($(this).scrollTop() > 150) {
                    $toTopButton.fadeIn(500);
                    setTimeout(function () {
                         $toTopButton.addClass('transition');
                     }, 600);
                } else {
                    $toTopButton.removeClass('transition');
                    $toTopButton.fadeOut(500);
                }
            });

            this.smoothScroll(buttonSelector);
        },
        
        /**
		 * Set all elements to same height within same class name
		 *
		 * @access public
		 * @param string
		 * @return
		 *
		**/
        setHighest: function(elementsSelector){
            if(this.options.viewportW < this.options.screenSm ){
                return false;
            }
            var maxHeight = 0;
            $(elementsSelector).css('height', 'initial');
            $(elementsSelector).each(function(i) {
              var elHeight = $(this).outerHeight();
              if(elHeight > maxHeight){
                maxHeight = elHeight;
              }
            });
            $(elementsSelector).css('height', `${maxHeight}px`);
        },
        
        /**
		 * Desktop navbar dropdown smooth effect
		 *
		 * @access public
		 * @param string
		 * @return
		 *
		**/
        smoothNavbar: function(menuSelector){
            if(this.options.viewportW >= this.options.screenMd ){
                return false;
            }
            
            // Add slideup & fadein animation to dropdown
	        $(menuSelector).find('.dropdown').on('show.bs.dropdown', function(e){
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
	        $(menuSelector).find('.dropdown').on('hide.bs.dropdown', function(e){
	            var $dropdown = $(this).find('.dropdown-menu');
	            var orig_margin_top = parseInt($dropdown.css('margin-top'));
	            $dropdown
	                .css({'margin-top': orig_margin_top + 'px', opacity: 1, display: 'block'})
	                .animate({'margin-top': (orig_margin_top + 10) + 'px', opacity: 0},
	                250, function(){
	                    $(this).css({'margin-top':'', display:''});
	                });
	        });
        },
        
        /**
		 * Responsive tranform tables on smaller devices - Header row becomes one column, other column represents data
		 *
		 * @access public
		 * @param string
		 * @return
		 *
		**/
        responsiveTables: function(tableSelector) {
            if(this.options.viewportW >= this.options.screenSm ){
                return false;
            }

            $(tableSelector).each(function(i, table){
                var $table  = $(table);
                if($table.length){
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
                }
            });
        },
        
        /**
		 * Clear an input field with an 'X'
		 *
		 * @access public
		 * @param string
		 * @return
		 *
		**/
        clearInput: function(inputSelector){
            $(inputSelector).each(function(i, e){
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
        },

    });

})( jQuery );

/* global loadmoreData */

jQuery(document).ready(function($) {

    var buttonText = loadmoreData.no_more_posts;
    
    $(loadmoreData.container).each(function(i, loadmoreContainer){
	    console.log($(loadmoreContainer));
	    $(loadmoreContainer).find(loadmoreData.element).each(function(j, e){
	        if(j >= loadmoreData.posts_per_page){
	            $(e).addClass('load-hidden-item');
	            buttonText = loadmoreData.show_more_posts;
	        }
	    });
	
	    var loadmoreWrapper = $('<div/>', {
	        'class': 'loadmore-wrapper'
	    }).appendTo($(loadmoreContainer));
	
	    $('<button/>', {
	        'class': 'btn btn-secondary loadmore-button',
	        'click': function() {
	            var count   = 0,
	        		num     = loadmoreData.posts_per_page;
	        	$(loadmoreContainer).find(`${loadmoreData.element}.load-hidden-item`).each(function(j, e){
	                if(num > 0){
	        			num--;
	        			count++;
	        			$(e).show(200, 'linear', function(){
	        				$(this).removeClass('load-hidden-item');
	        			});
	        		}
	            }).promise().done(function(){
	        		if(num >= count){
	        			buttonText = loadmoreData.no_more_posts;
	        		}
	        		$(loadmoreContainer).find('.loadmore-button').text(buttonText);
	        	});
	        }
	    })
	    .appendTo(loadmoreWrapper)
	    .text(buttonText);
    });

    
});
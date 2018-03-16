jQuery(document).ready(function($) {
    //facebook
    $('.facebook-share > a').click(function(e){e.preventDefault();
        window.open( 'https://www.facebook.com/sharer/sharer.php?u='+jQuery(this).attr('href'), "facebookWindow", "height=380,width=660,resizable=0,toolbar=0,menubar=0,status=0,location=0,scrollbars=0" );
        return false;
    });

    //twitter
    $('.twitter-share > a').click(function(e){e.preventDefault();
        window.open( 'http://twitter.com/intent/tweet?text='+$(this).closest('article').find('.entry-title').text() +' '+jQuery(this).attr('href'), "twitterWindow", "height=380,width=660,resizable=0,toolbar=0,menubar=0,status=0,location=0,scrollbars=0" );
        return false;
    });

    //pinterest
    $('.pinterest-share > a').click(function(e){e.preventDefault();
        window.open( 'http://pinterest.com/pin/create/button/?url='+jQuery(this).attr('href')+'&media='+$(this).closest('article').find('img').first().attr('src')+'&description='+$(this).closest('article').find('.entry-title').text(), "pinterestWindow", "height=640,width=660,resizable=0,toolbar=0,menubar=0,status=0,location=0,scrollbars=0" );
        return false;
    });


    //google
    $('.googleplus-share > a').click(function(e){e.preventDefault();
    console.log(jQuery(this).attr('href'));
        window.open( 'https://plus.google.com/share?url='+jQuery(this).attr('href'), "googleWindow", "height=640,width=660,resizable=0,toolbar=0,menubar=0,status=0,location=0,scrollbars=0" );
        return false;
    });
});

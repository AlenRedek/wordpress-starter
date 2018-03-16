function mh_mailchimp_subscribe(form, msg, email_only, list_id){
	var vals = jQuery(form).serializeArray(),
		preloader = jQuery(form+'-preloader');
	jQuery(msg).html('');
	preloader.css('opacity','1');
	jQuery.ajax({
		url:'/wp-admin/admin-ajax.php',
		method:'POST',
		dataType:'json',
		data:{action:'mh_mailchimp_app', vals:vals, email_only:email_only, list_id:list_id},
	}).done(function(data){
		preloader.css('opacity','0');
		jQuery(msg).text(data.msg);
	}).error(function(err){
		preloader.css('opacity','0');
		jQuery(msg).text('Mailchimp error');
		console.log('Mailchimp error', err);
	});
}
<?php

require_once('mh_mailchimp_class.php');
global $mailchimp;
$mailchimp = new MailChimpApp();

require_once('ar_mailchimp_widget.php');
add_action( 'widgets_init', 'emigma_mailchimp_widget_init' );
function emigma_mailchimp_widget_init() {
    register_widget( 'emigma_mailchimp_widget' );
}

?>
<?php

class emigma_mailchimp_widget extends WP_Widget {

    public function __construct(){
        $widget_details = array(
            'classname' => 'emigma_mailchimp_widget',
            'description' => __('Mailchimp newsletter signup form', 'emigma')
        );

        parent::__construct( 'emigma_mailchimp_widget', __('Mailchimp signup form', 'emigma'), $widget_details );

        //add_action('wp_enqueue_scripts', array($this, 'mfc_assets'));

    }

     public function mfc_assets() {
        $mailchimp_dir = str_replace(get_stylesheet_directory(), '', dirname(__FILE__));
        wp_enqueue_style('mh_mailchimp', get_stylesheet_directory_uri() . $mailchimp_dir . '/assets/css/mailchimp.css', array(), '2.1');
		wp_enqueue_script('mh_mailchimp', get_stylesheet_directory_uri() . $mailchimp_dir . '/assets/js/mailchimp.js', array('jquery'), '2.1', true);

		add_action('wp_ajax_mh_mailchimp_app', array(MailChimpApp, 'mh_mailchimp_app'));
		add_action('wp_ajax_nopriv_mh_mailchimp_app', array(MailChimpApp, 'mh_mailchimp_app'));
    }

	public function update( $new_instance, $old_instance ) {
	    return $new_instance;
	}

    public function form( $instance ){

        $title = '';
	    if( !empty( $instance['title'] ) ) {
	        $title = $instance['title'];
	    }

	    $description = '';
	    if( !empty( $instance['description'] ) ) {
	        $description = $instance['description'];
	    }

	    $list_id = '';
	    if( !empty( $instance['list_id'] ) ) {
	        $list_id = $instance['list_id'];
	    }

        ?>

        <p>
            <label for="<?php echo $this->get_field_name( 'title' ); ?>"><?php _e( 'Title', 'emigma' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_name( 'description' ); ?>"><?php _e( 'Description', 'emigma' ); ?></label>
            <textarea class="widefat" id="<?php echo $this->get_field_id( 'description' ); ?>" name="<?php echo $this->get_field_name( 'description' ); ?>" type="text" ><?php echo esc_attr( $description ); ?></textarea>
        </p>

        <p>
            <label for="<?php echo $this->get_field_name( 'list_id' ); ?>"><?php _e( 'List ID', 'emigma' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'list_id' ); ?>" name="<?php echo $this->get_field_name( 'list_id' ); ?>" type="text" value="<?php echo esc_attr( $list_id ); ?>" />
        </p>

    <?php
    }

    public function widget( $args, $instance ) {
        global $mailchimp;
		echo $args['before_widget'];
		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
		}

		echo $mailchimp->get_mailchimp_template('mailchimp_form', 'mc_message', true, $instance['list_id'], $instance['description']);

		echo $args['after_widget'];
    }
}

?>
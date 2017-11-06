<?php

class pg_business_card_widget extends WP_Widget {

    public function __construct(){
        $widget_details = array(
            'classname' => 'pg_business_card_widget',
            'description' => __('Displays company business card.', 'purgatorio')
        );

        parent::__construct( 'pg_business_card_widget', __('[Purgatorio] Business card', 'purgatorio'), $widget_details );

        add_action('admin_enqueue_scripts', array($this, 'mfc_assets'));
    }

    public function mfc_assets() {
        wp_enqueue_script('media-upload');
        wp_enqueue_script('thickbox');
        wp_enqueue_script('purgatorio-media-upload', PURGATORIO__PLUGIN_URL . 'assets/js/media-upload.js', array('jquery'));
        wp_enqueue_style('thickbox');
    }

    public function form( $instance ){
		$title = '';
	    if( !empty( $instance['title'] ) ) {
	        $title = $instance['title'];
	    }
	    $business_name = '';
	    if( !empty( $instance['business_name'] ) ) {
	        $business_name = $instance['business_name'];
	    }
	    $business_address = '';
	    if( !empty( $instance['business_address'] ) ) {
	        $business_address = $instance['business_address'];
	    }
	    $business_postcode = '';
	    if( !empty( $instance['business_postcode'] ) ) {
	        $business_postcode = $instance['business_postcode'];
	    }
	    $business_city = '';
	    if( !empty( $instance['business_city'] ) ) {
	        $business_city = $instance['business_city'];
	    }
	    $business_country = '';
	    if( !empty( $instance['business_country'] ) ) {
	        $business_country = $instance['business_country'];
	    }
	    $business_email = '';
	    if( !empty( $instance['business_email'] ) ) {
	        $business_email = $instance['business_email'];
	    }
	    $business_phone = '';
	    if( !empty( $instance['business_phone'] ) ) {
	        $business_phone = $instance['business_phone'];
	    }
	    $business_fax = '';
	    if( !empty( $instance['business_fax'] ) ) {
	        $business_fax = $instance['business_fax'];
	    }
		$business_price_range = '';
	    if( !empty( $instance['business_price_range'] ) ) {
	        $business_price_range = $instance['business_price_range'];
	    }
	    $business_image = '';
		if(isset($instance['business_image'])) {
		    $business_image = $instance['business_image'];
		}

	    ?>
	    <p>
            <label for="<?php echo $this->get_field_name( 'title' ); ?>"><?php _e( 'Title', 'purgatorio' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>
	    <p>
            <label for="<?php echo $this->get_field_name( 'business_name' ); ?>"><?php _e( 'Name', 'purgatorio' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'business_name' ); ?>" name="<?php echo $this->get_field_name( 'business_name' ); ?>" type="text" value="<?php echo esc_attr( $business_name ); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_name( 'business_address' ); ?>"><?php _e( 'Address', 'purgatorio' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'business_address' ); ?>" name="<?php echo $this->get_field_name( 'business_address' ); ?>" type="text" value="<?php echo esc_attr( $business_address ); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_name( 'business_postcode' ); ?>"><?php _e( 'Postcode', 'purgatorio' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'business_postcode' ); ?>" name="<?php echo $this->get_field_name( 'business_postcode' ); ?>" type="text" value="<?php echo esc_attr( $business_postcode ); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_name( 'business_city' ); ?>"><?php _e( 'City', 'purgatorio' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'business_city' ); ?>" name="<?php echo $this->get_field_name( 'business_city' ); ?>" type="text" value="<?php echo esc_attr( $business_city ); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_name( 'business_country' ); ?>"><?php _e( 'Country', 'purgatorio' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'business_country' ); ?>" name="<?php echo $this->get_field_name( 'business_country' ); ?>" type="text" value="<?php echo esc_attr( $business_country ); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_name( 'business_email' ); ?>"><?php _e( 'E-mail', 'purgatorio' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'business_email' ); ?>" name="<?php echo $this->get_field_name( 'business_email' ); ?>" type="email" value="<?php echo esc_attr( $business_email ); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_name( 'business_phone' ); ?>"><?php _e( 'Phone number', 'purgatorio' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'business_phone' ); ?>" name="<?php echo $this->get_field_name( 'business_phone' ); ?>" type="text" value="<?php echo esc_attr( $business_phone ); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_name( 'business_fax' ); ?>"><?php _e( 'Fax number', 'purgatorio' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'business_fax' ); ?>" name="<?php echo $this->get_field_name( 'business_fax' ); ?>" type="text" value="<?php echo esc_attr( $business_fax ); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_name( 'business_price_range' ); ?>"><?php _e( 'Price range', 'purgatorio' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'business_price_range' ); ?>" name="<?php echo $this->get_field_name( 'business_price_range' ); ?>" type="text" value="<?php echo esc_attr( $business_price_range ); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_name( 'business_image' ); ?>"><?php _e( 'Image', 'purgatorio' ); ?></label>
            <input name="<?php echo $this->get_field_name( 'business_image' ); ?>" id="<?php echo $this->get_field_id( 'business_image' ); ?>" class="widefat" type="text" size="36"  value="<?php echo esc_url( $business_image ); ?>" />
            <input class="upload_image_button" type="button" value="<?php _e( 'Upload Image', 'purgatorio' ); ?>" />
        </p>
        <?php
    }

    public function widget( $args, $instance ) {
    	echo $args['before_widget'];
		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
		}
		?>

		<div itemscope itemtype="http://schema.org/LocalBusiness">
			<div>
				<p class="text-uppercase"><span itemprop="name"><?php echo $instance['business_name']; ?></span></p>
				<?php if( $instance['business_address'] || $instance['business_postcode'] || $instance['business_city'] ): ?>
					<div>
						<div class="pull-left mb-5"><i class="fa fa-map-marker fa-meta"></i></div>
						<div itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
							<div itemprop="streetAddress"><?php echo $instance['business_address']; ?></div>
							<span itemprop="postalCode"><?php echo $instance['business_postcode']; ?></span> <span itemprop="addressLocality"><?php echo $instance['business_city']; ?></span>
							<div itemprop="addressCountry"><?php echo $instance['business_country']; ?></div>
						</div>
						<div class="clearfix"></div>
					</div>
				<?php endif; ?>
				<?php if( $instance['business_email'] ): ?>
					<p><i class="fa fa-envelope-o fa-meta"></i><a href="mailto:<?php echo $instance['business_email']; ?>"><span itemprop="email"><?php echo $instance['business_email']; ?></span></a></p>
				<?php endif; ?>
				<?php if( $instance['business_phone'] ): ?>
					<p><i class="fa fa-phone fa-meta"></i><a href="tel:<?php echo $instance['business_phone']; ?>"><span itemprop="telephone"><?php echo $instance['business_phone']; ?></span></a></p>
				<?php endif; ?>
				<?php if( $instance['business_fax'] ): ?>
					<p><i class="fa fa-fax fa-meta"></i><a href="tel:<?php echo $instance['business_fax']; ?>"><span itemprop="FaxNumber"><?php echo $instance['business_fax']; ?></span></a></p>
				<?php endif; ?>

			</div>
			<?php if( $instance['business_image'] ): ?>
			<div class="mt-3">
				<img src="<?php echo $instance['business_image'] ?>" class="img-responsive" itemprop="image" />
			</div>
			<?php else: ?>
				<meta itemprop="image" content="<?php echo get_site_icon_url(); ?>"/>
			<?php endif; ?>
      <meta itemprop="priceRange" content="<?php echo $instance['business_price_range'] ? $instance['business_price_range'] : '$000 - $000'; ?>"/>
		</div>

        <?php

		echo $args['after_widget'];
    }
}

?>
